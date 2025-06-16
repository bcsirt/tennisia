<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * MODÈLE COMPLÉMENTAIRE : HISTORIQUE COMPORTEMENTAL
 *
 * Suit l'évolution du comportement d'un joueur sur des périodes définies
 * pour détecter des patterns et ajuster les prédictions
 */
class HistoriqueComportemental extends Model
{
    protected $table = 'historique_comportemental';

    protected $fillable = [
        'joueur_id',
        'periode_debut',
        'periode_fin',
        'score_fair_play',
        'nombre_sanctions',
        'gravite_moyenne',
        'evolution_tendance',
        'facteur_risque',           // 1-10
        'nombre_matchs_periode',    // Matchs joués dans la période
        'sanctions_par_match',      // Ratio sanctions/matchs
        'pattern_detecte',          // Type de pattern comportemental
        'confiance_analyse',        // Niveau de confiance de l'analyse (1-10)
        'notes_analyste',          // Notes libres de l'analyste
        'derniere_maj_auto',       // Dernière mise à jour automatique
        'validee_par_analyste'     // Boolean - validation manuelle
    ];

    protected $casts = [
        'periode_debut' => 'date',
        'periode_fin' => 'date',
        'score_fair_play' => 'decimal:2',
        'gravite_moyenne' => 'decimal:2',
        'facteur_risque' => 'integer',
        'sanctions_par_match' => 'decimal:4',
        'confiance_analyse' => 'integer',
        'derniere_maj_auto' => 'datetime',
        'validee_par_analyste' => 'boolean'
    ];

    // CONSTANTES POUR LES TENDANCES
    public const TENDANCE_AMELIORATION = 'amelioration';
    public const TENDANCE_DETERIORATION = 'deterioration';
    public const TENDANCE_STABLE = 'stable';
    public const TENDANCE_VOLATILE = 'volatile';
    public const TENDANCE_NOUVEAU_JOUEUR = 'nouveau_joueur';

    // CONSTANTES POUR LES PATTERNS
    public const PATTERN_STRESS_GRAND_CHELEM = 'stress_grand_chelem';
    public const PATTERN_CONFLIT_ARBITRE = 'conflit_arbitre';
    public const PATTERN_PRESSION_RANKING = 'pression_ranking';
    public const PATTERN_FATIGUE_FIN_SAISON = 'fatigue_fin_saison';
    public const PATTERN_INSTABILITE_JEUNE = 'instabilite_jeune';
    public const PATTERN_VETERAN_CALME = 'veteran_calme';

    /**
     * RELATIONS
     */
    public function joueur(): BelongsTo
    {
        return $this->belongsTo(Joueur::class);
    }

    public function sanctions(): HasMany
    {
        return $this->hasMany(Sanction::class, 'joueur_id', 'joueur_id')
            ->whereBetween('created_at', [$this->periode_debut, $this->periode_fin]);
    }

    /**
     * SCOPES
     */
    public function scopeRecentes(Builder $query, int $mois = 6): Builder
    {
        return $query->where('periode_fin', '>=', Carbon::now()->subMonths($mois));
    }

    public function scopeParTendance(Builder $query, string $tendance): Builder
    {
        return $query->where('evolution_tendance', $tendance);
    }

    public function scopeRisqueEleve(Builder $query): Builder
    {
        return $query->where('facteur_risque', '>=', 7);
    }

    public function scopeValidees(Builder $query): Builder
    {
        return $query->where('validee_par_analyste', true);
    }

    public function scopeConfiance(Builder $query, int $niveauMin = 7): Builder
    {
        return $query->where('confiance_analyse', '>=', $niveauMin);
    }

    public function scopePourPeriode(Builder $query, Carbon $debut, Carbon $fin): Builder
    {
        return $query->where('periode_debut', '>=', $debut)
            ->where('periode_fin', '<=', $fin);
    }

    /**
     * ACCESSORS
     */
    public function getFacteurRisquePredictionAttribute(): float
    {
        // Joueur avec historique de sanctions = plus de variance dans les résultats
        $facteurBase = 1 + ($this->facteur_risque / 100); // 1.00 à 1.10

        // Ajustement selon le pattern détecté
        $ajustementPattern = match($this->pattern_detecte) {
            self::PATTERN_STRESS_GRAND_CHELEM => 0.05,
            self::PATTERN_PRESSION_RANKING => 0.03,
            self::PATTERN_INSTABILITE_JEUNE => 0.04,
            self::PATTERN_VETERAN_CALME => -0.02,
            default => 0
        };

        return max(1.0, $facteurBase + $ajustementPattern);
    }

    public function getTendanceLabelAttribute(): string
    {
        return match($this->evolution_tendance) {
            self::TENDANCE_AMELIORATION => 'En amélioration',
            self::TENDANCE_DETERIORATION => 'En dégradation',
            self::TENDANCE_STABLE => 'Stable',
            self::TENDANCE_VOLATILE => 'Volatile',
            self::TENDANCE_NOUVEAU_JOUEUR => 'Nouveau joueur',
            default => 'Non définie'
        };
    }

    public function getPatternLabelAttribute(): string
    {
        return match($this->pattern_detecte) {
            self::PATTERN_STRESS_GRAND_CHELEM => 'Stress Grand Chelem',
            self::PATTERN_CONFLIT_ARBITRE => 'Conflits avec arbitrage',
            self::PATTERN_PRESSION_RANKING => 'Pression classement',
            self::PATTERN_FATIGUE_FIN_SAISON => 'Fatigue fin de saison',
            self::PATTERN_INSTABILITE_JEUNE => 'Instabilité jeune joueur',
            self::PATTERN_VETERAN_CALME => 'Vétéran expérimenté',
            default => 'Aucun pattern détecté'
        };
    }

    public function getEstFiableAttribute(): bool
    {
        return $this->confiance_analyse >= 7 &&
            $this->nombre_matchs_periode >= 5 &&
            $this->periode_fin->diffInDays($this->periode_debut) >= 30;
    }

    /**
     * MÉTHODES STATIQUES DE CRÉATION
     */

    /**
     * Génère automatiquement l'historique comportemental pour une période
     */
    public static function genererPourPeriode(int $joueurId, Carbon $debut, Carbon $fin): self
    {
        $sanctions = Sanction::pourJoueur($joueurId)
            ->whereBetween('created_at', [$debut, $fin])
            ->get();

        $matchs = MatchTennis::where(function($q) use ($joueurId) {
            $q->where('joueur1_id', $joueurId)
                ->orWhere('joueur2_id', $joueurId);
        })
            ->whereBetween('date_match', [$debut, $fin])
            ->count();

        $historique = new self([
            'joueur_id' => $joueurId,
            'periode_debut' => $debut,
            'periode_fin' => $fin,
            'nombre_sanctions' => $sanctions->count(),
            'nombre_matchs_periode' => $matchs,
            'sanctions_par_match' => $matchs > 0 ? $sanctions->count() / $matchs : 0,
            'gravite_moyenne' => $sanctions->avg('gravite') ?? 0,
            'derniere_maj_auto' => now()
        ]);

        // Calcul du score fair-play
        $historique->score_fair_play = $historique->calculerScoreFairPlay($sanctions);

        // Analyse de la tendance
        $historique->evolution_tendance = $historique->analyserTendance($joueurId, $debut, $fin);

        // Calcul du facteur de risque
        $historique->facteur_risque = $historique->calculerFacteurRisque($sanctions);

        // Détection de patterns
        [$pattern, $confiance] = $historique->detecterPattern($joueurId, $sanctions);
        $historique->pattern_detecte = $pattern;
        $historique->confiance_analyse = $confiance;

        $historique->save();

        return $historique;
    }

    /**
     * Met à jour automatiquement tous les historiques récents
     */
    public static function mettreAJourAutomatique(): void
    {
        $joueursActifs = Joueur::whereHas('matchs', function($q) {
            $q->where('date_match', '>=', Carbon::now()->subMonths(3));
        })->get();

        foreach ($joueursActifs as $joueur) {
            $finPeriode = Carbon::now();
            $debutPeriode = $finPeriode->copy()->subMonths(3);

            $existant = self::where('joueur_id', $joueur->id)
                ->where('periode_fin', '>=', $finPeriode->subDays(7))
                ->first();

            if (!$existant) {
                self::genererPourPeriode($joueur->id, $debutPeriode, $finPeriode);
            }
        }
    }

    /**
     * MÉTHODES PRIVÉES DE CALCUL
     */

    private function calculerScoreFairPlay(Collection $sanctions): float
    {
        if ($sanctions->isEmpty()) {
            return 10.0;
        }

        $penalite = $sanctions->sum(function($sanction) {
            return $sanction->gravite * 0.15;
        });

        // Bonus pour amélioration récente
        $sanctionsRecentes = $sanctions->where('created_at', '>=',
            $this->periode_fin->subDays(30));
        $sanctionsAnciennes = $sanctions->where('created_at', '<',
            $this->periode_fin->subDays(30));

        if ($sanctionsAnciennes->count() > $sanctionsRecentes->count()) {
            $penalite *= 0.8; // Réduction de 20% si amélioration
        }

        return max(0, 10 - $penalite);
    }

    private function analyserTendance(int $joueurId, Carbon $debut, Carbon $fin): string
    {
        // Comparaison avec la période précédente
        $periodePrecedente = self::where('joueur_id', $joueurId)
            ->where('periode_fin', '<=', $debut)
            ->orderByDesc('periode_fin')
            ->first();

        if (!$periodePrecedente) {
            return self::TENDANCE_NOUVEAU_JOUEUR;
        }

        $diffScore = $this->score_fair_play - $periodePrecedente->score_fair_play;
        $diffFacteur = $this->facteur_risque - $periodePrecedente->facteur_risque;

        // Calcul de volatilité
        $sanctions = Sanction::pourJoueur($joueurId)
            ->whereBetween('created_at', [$debut, $fin])
            ->get();

        $volatilite = $this->calculerVolatilite($sanctions);

        if ($volatilite > 3) {
            return self::TENDANCE_VOLATILE;
        }

        if ($diffScore > 1 && $diffFacteur < -1) {
            return self::TENDANCE_AMELIORATION;
        }

        if ($diffScore < -1 && $diffFacteur > 1) {
            return self::TENDANCE_DETERIORATION;
        }

        return self::TENDANCE_STABLE;
    }

    private function calculerVolatilite(Collection $sanctions): float
    {
        if ($sanctions->count() < 3) {
            return 0;
        }

        $gravites = $sanctions->pluck('gravite')->toArray();
        $moyenne = array_sum($gravites) / count($gravites);

        $variance = array_sum(array_map(function($g) use ($moyenne) {
                return pow($g - $moyenne, 2);
            }, $gravites)) / count($gravites);

        return sqrt($variance);
    }

    private function calculerFacteurRisque(Collection $sanctions): int
    {
        $facteur = 1; // Base minimale

        // Nombre de sanctions
        $facteur += min($sanctions->count(), 5);

        // Gravité moyenne
        $graviteMoyenne = $sanctions->avg('gravite') ?? 0;
        $facteur += intval($graviteMoyenne / 2);

        // Récidives
        $recidives = $sanctions->where('recidive', true)->count();
        $facteur += $recidives * 2;

        // Sanctions récentes (plus d'impact)
        $sanctionsRecentes = $sanctions->where('created_at', '>=',
            $this->periode_fin->subDays(30));
        if ($sanctionsRecentes->count() > $sanctions->count() * 0.6) {
            $facteur += 2; // Concentration récente = risque élevé
        }

        return min(10, $facteur);
    }

    private function detecterPattern(int $joueurId, Collection $sanctions): array
    {
        $confiance = 5; // Base
        $pattern = null;

        $joueur = Joueur::find($joueurId);

        // Pattern âge
        if ($joueur->age < 22 && $sanctions->count() > 2) {
            $pattern = self::PATTERN_INSTABILITE_JEUNE;
            $confiance = 8;
        } elseif ($joueur->age > 30 && $sanctions->count() < 2) {
            $pattern = self::PATTERN_VETERAN_CALME;
            $confiance = 7;
        }

        // Pattern Grand Chelem (analyse des tournois)
        $sanctionsGrandChelem = $sanctions->filter(function($sanction) {
            return in_array($sanction->match->tournoi->categorie,
                ['Grand Slam', 'Grand_Slam']);
        });

        if ($sanctionsGrandChelem->count() > $sanctions->count() * 0.6) {
            $pattern = self::PATTERN_STRESS_GRAND_CHELEM;
            $confiance = 9;
        }

        // Pattern conflit arbitre
        $arbitresFrequents = $sanctions->groupBy('arbitre_nom')
            ->filter(function($group) { return $group->count() > 1; });

        if ($arbitresFrequents->count() > 0) {
            $pattern = self::PATTERN_CONFLIT_ARBITRE;
            $confiance = 7;
        }

        // Pattern fin de saison
        $sanctionsFinAnnee = $sanctions->filter(function($sanction) {
            return $sanction->created_at->month >= 10;
        });

        if ($sanctionsFinAnnee->count() > $sanctions->count() * 0.7) {
            $pattern = self::PATTERN_FATIGUE_FIN_SAISON;
            $confiance = 6;
        }

        // Pattern pression ranking
        $joueurTop100 = $joueur->ranking_atp <= 100 || $joueur->ranking_wta <= 100;
        if ($joueurTop100 && $sanctions->avg('gravite') > 6) {
            $pattern = self::PATTERN_PRESSION_RANKING;
            $confiance = 8;
        }

        return [$pattern, $confiance];
    }

    /**
     * MÉTHODES D'ANALYSE AVANCÉE
     */

    /**
     * Compare le comportement avec la moyenne du circuit
     */
    public function comparerAvecCircuit(): array
    {
        $moyenneCircuit = self::where('periode_fin', '>=', Carbon::now()->subMonths(6))
            ->avg('score_fair_play');

        $percentileRisque = self::where('facteur_risque', '<=', $this->facteur_risque)
                ->count() / self::count() * 100;

        return [
            'score_vs_moyenne' => $this->score_fair_play - $moyenneCircuit,
            'percentile_fair_play' => $percentileRisque,
            'categorie' => match(true) {
                $this->score_fair_play >= 9 => 'Excellent',
                $this->score_fair_play >= 7 => 'Bon',
                $this->score_fair_play >= 5 => 'Moyen',
                $this->score_fair_play >= 3 => 'Problématique',
                default => 'Critique'
            }
        ];
    }

    /**
     * Prédit l'évolution comportementale future
     */
    public function predireEvolutionFuture(): array
    {
        $historiques = self::where('joueur_id', $this->joueur_id)
            ->orderBy('periode_fin')
            ->take(5)
            ->get();

        if ($historiques->count() < 3) {
            return [
                'prediction' => 'insuffisant_donnees',
                'confiance' => 1
            ];
        }

        $tendanceScore = $historiques->last()->score_fair_play -
            $historiques->first()->score_fair_play;

        $tendanceRisque = $historiques->last()->facteur_risque -
            $historiques->first()->facteur_risque;

        $prediction = match(true) {
            $tendanceScore > 1 && $tendanceRisque < -1 => 'amelioration_continue',
            $tendanceScore < -1 && $tendanceRisque > 1 => 'deterioration_preoccupante',
            abs($tendanceScore) <= 0.5 => 'stabilite',
            default => 'evolution_incertaine'
        };

        $confiance = min(10, $historiques->count() * 2);

        return [
            'prediction' => $prediction,
            'confiance' => $confiance,
            'facteur_risque_estime_3_mois' => max(1, min(10,
                $this->facteur_risque + ($tendanceRisque * 0.5)
            ))
        ];
    }

    /**
     * Génère un rapport comportemental complet
     */
    public function genererRapportComplet(): array
    {
        return [
            'periode' => [
                'debut' => $this->periode_debut->format('d/m/Y'),
                'fin' => $this->periode_fin->format('d/m/Y'),
                'duree_jours' => $this->periode_debut->diffInDays($this->periode_fin)
            ],
            'metriques' => [
                'score_fair_play' => $this->score_fair_play,
                'facteur_risque' => $this->facteur_risque,
                'sanctions_par_match' => round($this->sanctions_par_match, 3),
                'gravite_moyenne' => round($this->gravite_moyenne, 2)
            ],
            'analyse' => [
                'tendance' => $this->tendance_label,
                'pattern' => $this->pattern_label,
                'confiance' => $this->confiance_analyse,
                'fiabilite' => $this->est_fiable ? 'Fiable' : 'Données insuffisantes'
            ],
            'comparaison' => $this->comparerAvecCircuit(),
            'prediction' => $this->predireEvolutionFuture(),
            'impact_predictions' => [
                'facteur_risque' => $this->facteur_risque_prediction,
                'recommandation' => $this->facteur_risque >= 7 ?
                    'Surveillance accrue recommandée' : 'Comportement stable'
            ]
        ];
    }
}
