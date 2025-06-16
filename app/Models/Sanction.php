<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sanction extends Model
{
    protected $fillable = [
        'match_tennis_id',
        'joueur_id',
        'type_sanction',
        'description',
        'moment_match',      // Moment précis dans le match
        'set_numero',        // Numéro du set
        'jeu_numero',        // Numéro du jeu
        'score_moment',      // Score au moment de la sanction
        'montant_amende',
        'gravite',           // 1-10 échelle de gravité
        'arbitre_nom',       // Nom de l'arbitre
        'contexte',          // Contexte de la sanction
        'recidive',          // Boolean si récidive dans le match
        'impact_match',      // Impact estimé sur le match
        'sanction_confirmee', // Boolean si confirmée après appel
    ];

    protected $casts = [
        'montant_amende' => 'decimal:2',
        'gravite' => 'integer',
        'recidive' => 'boolean',
        'sanction_confirmee' => 'boolean',
        'impact_match' => 'integer', // 1-5 échelle d'impact
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // CONSTANTES POUR LES TYPES DE SANCTIONS
    public const TYPE_AVERTISSEMENT = 'avertissement';

    public const TYPE_FAUTE = 'faute';

    public const TYPE_CODE_VIOLATION = 'code_violation';

    public const TYPE_CONDUITE_ANTISPORTIVE = 'conduite_antisportive';

    public const TYPE_CONTESTATION_ARBITRAGE = 'contestation_arbitrage';

    public const TYPE_RETARD = 'retard';

    public const TYPE_COACHING = 'coaching_illegal';

    public const TYPE_EQUIPEMENT = 'equipement_non_conforme';

    public const TYPE_AMENDE_FINANCIERE = 'amende_financiere';

    public const TYPE_FORFAIT = 'forfait';

    public static function getTypesSanctions(): array
    {
        return [
            self::TYPE_AVERTISSEMENT => 'Avertissement',
            self::TYPE_FAUTE => 'Faute technique',
            self::TYPE_CODE_VIOLATION => 'Violation du code',
            self::TYPE_CONDUITE_ANTISPORTIVE => 'Conduite antisportive',
            self::TYPE_CONTESTATION_ARBITRAGE => 'Contestation arbitrage',
            self::TYPE_RETARD => 'Retard',
            self::TYPE_COACHING => 'Coaching illégal',
            self::TYPE_EQUIPEMENT => 'Équipement non conforme',
            self::TYPE_AMENDE_FINANCIERE => 'Amende financière',
            self::TYPE_FORFAIT => 'Forfait',
        ];
    }

    // CONSTANTES POUR LA GRAVITÉ
    public const GRAVITE_MINEURE = 1;

    public const GRAVITE_LEGERE = 3;

    public const GRAVITE_MODEREE = 5;

    public const GRAVITE_GRAVE = 7;

    public const GRAVITE_TRES_GRAVE = 10;

    /**
     * RELATIONS
     */
    public function match(): BelongsTo
    {
        return $this->belongsTo(MatchTennis::class, 'match_tennis_id');
    }

    public function joueur(): BelongsTo
    {
        return $this->belongsTo(Joueur::class, 'joueur_id');
    }

    /**
     * SCOPES
     */
    public function scopeGraves(Builder $query): Builder
    {
        return $query->where('gravite', '>=', self::GRAVITE_GRAVE);
    }

    public function scopeAvecAmende(Builder $query): Builder
    {
        return $query->whereNotNull('montant_amende')
            ->where('montant_amende', '>', 0);
    }

    public function scopeParType(Builder $query, string $type): Builder
    {
        return $query->where('type_sanction', $type);
    }

    public function scopeRecentes(Builder $query, int $jours = 30): Builder
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($jours));
    }

    public function scopePourJoueur(Builder $query, int $joueurId): Builder
    {
        return $query->where('joueur_id', $joueurId);
    }

    public function scopeParTournoi(Builder $query, int $tournoiId): Builder
    {
        return $query->whereHas('match', function ($q) use ($tournoiId) {
            $q->where('tournoi_id', $tournoiId);
        });
    }

    /**
     * ACCESSORS
     */
    public function getTypeSanctionLabelAttribute(): string
    {
        return self::getTypesSanctions()[$this->type_sanction] ?? $this->type_sanction;
    }

    public function getGraviteLabelAttribute(): string
    {
        return match ($this->gravite) {
            1, 2 => 'Mineure',
            3, 4 => 'Légère',
            5, 6 => 'Modérée',
            7, 8 => 'Grave',
            9, 10 => 'Très grave',
            default => 'Non définie'
        };
    }

    public function getEstGraveAttribute(): bool
    {
        return $this->gravite >= self::GRAVITE_GRAVE;
    }

    public function getMomentCompletAttribute(): string
    {
        $moment = "Set {$this->set_numero}";
        if ($this->jeu_numero) {
            $moment .= ", Jeu {$this->jeu_numero}";
        }
        if ($this->score_moment) {
            $moment .= " ({$this->score_moment})";
        }

        return $moment;
    }

    /**
     * MÉTHODES MÉTIER
     */

    /**
     * Calcule l'impact potentiel de la sanction sur la performance
     */
    public function calculerImpactPerformance(): array
    {
        $impact = [
            'mental' => 0,      // Impact psychologique
            'tactique' => 0,    // Impact tactique
            'physique' => 0,    // Impact physique
            'financier' => 0,    // Impact financier
        ];

        // Impact selon le type de sanction
        switch ($this->type_sanction) {
            case self::TYPE_CODE_VIOLATION:
                $impact['mental'] = $this->gravite * 0.8;
                $impact['tactique'] = $this->gravite * 0.6;
                break;

            case self::TYPE_CONDUITE_ANTISPORTIVE:
                $impact['mental'] = $this->gravite * 1.2;
                break;

            case self::TYPE_COACHING:
                $impact['tactique'] = $this->gravite * 1.0;
                break;

            case self::TYPE_RETARD:
                $impact['physique'] = $this->gravite * 0.5;
                break;
        }

        // Impact financier
        if ($this->montant_amende > 0) {
            $impact['financier'] = min($this->montant_amende / 1000, 10); // Normalisé sur 10
        }

        // Bonus si récidive
        if ($this->recidive) {
            $impact = array_map(fn ($val) => $val * 1.5, $impact);
        }

        return $impact;
    }

    /**
     * Vérifie si la sanction peut affecter les prédictions futures
     */
    public function peutAffecterPredictions(): bool
    {
        return $this->gravite >= self::GRAVITE_MODEREE ||
            $this->montant_amende > 500 ||
            $this->recidive;
    }

    /**
     * Génère un score de "fair-play" pour le joueur
     */
    public static function calculerScoreFairPlay(int $joueurId, int $nombreMatchs = 10): float
    {
        $sanctions = self::pourJoueur($joueurId)
            ->whereHas('match', function ($q) {
                $q->orderBy('date_match', 'desc')->limit(10);
            })
            ->get();

        if ($sanctions->isEmpty()) {
            return 10.0; // Score parfait
        }

        $penalite = $sanctions->sum(function ($sanction) {
            return $sanction->gravite * 0.1;
        });

        return max(0, 10 - $penalite);
    }

    /**
     * Statistiques de sanctions pour un joueur
     */
    public static function statistiquesJoueur(int $joueurId): array
    {
        $sanctions = self::pourJoueur($joueurId)->get();

        return [
            'total_sanctions' => $sanctions->count(),
            'sanctions_graves' => $sanctions->where('gravite', '>=', self::GRAVITE_GRAVE)->count(),
            'total_amendes' => $sanctions->sum('montant_amende'),
            'types_frequents' => $sanctions->groupBy('type_sanction')
                ->map->count()
                ->sortDesc()
                ->take(3)
                ->toArray(),
            'score_fair_play' => self::calculerScoreFairPlay($joueurId),
            'recidives' => $sanctions->where('recidive', true)->count(),
            'derniere_sanction' => $sanctions->sortByDesc('created_at')->first()?->created_at,
        ];
    }

    /**
     * Trend des sanctions pour détecter des patterns comportementaux
     */
    public static function analyserTendanceComportementale(int $joueurId, int $moisRecents = 6): array
    {
        $sanctionsRecentes = self::pourJoueur($joueurId)
            ->where('created_at', '>=', Carbon::now()->subMonths($moisRecents))
            ->orderBy('created_at')
            ->get();

        $tendance = [
            'evolution' => 'stable', // stable, deterioration, amelioration
            'frequence_moyenne' => 0,
            'gravite_moyenne' => 0,
            'types_emergents' => [],
            'pattern_detected' => false,
        ];

        if ($sanctionsRecentes->count() < 2) {
            return $tendance;
        }

        // Calcul de l'évolution
        $premieresPeriode = $sanctionsRecentes->take($sanctionsRecentes->count() / 2);
        $dernierePeriode = $sanctionsRecentes->skip($sanctionsRecentes->count() / 2);

        $graviteMoyennePremiere = $premieresPeriode->avg('gravite') ?? 0;
        $graviteMoyenneDerniere = $dernierePeriode->avg('gravite') ?? 0;

        if ($graviteMoyenneDerniere > $graviteMoyennePremiere * 1.2) {
            $tendance['evolution'] = 'deterioration';
        } elseif ($graviteMoyenneDerniere < $graviteMoyennePremiere * 0.8) {
            $tendance['evolution'] = 'amelioration';
        }

        $tendance['frequence_moyenne'] = $sanctionsRecentes->count() / $moisRecents;
        $tendance['gravite_moyenne'] = $sanctionsRecentes->avg('gravite');

        // Détection de patterns (ex: sanctions récurrentes vs certains arbitres)
        $arbitresFrequents = $sanctionsRecentes->groupBy('arbitre_nom')
            ->filter(function ($group) {
                return $group->count() > 1;
            });

        if ($arbitresFrequents->isNotEmpty()) {
            $tendance['pattern_detected'] = true;
            $tendance['pattern_type'] = 'conflit_arbitre';
        }

        return $tendance;
    }

    /**
     * Impact estimé sur le match en cours
     */
    public function impactSurMatch(): array
    {
        $impact = $this->calculerImpactPerformance();

        // Facteur temporel - impact plus fort en début de match
        $facteurTemporel = 1.0;
        if ($this->set_numero == 1) {
            $facteurTemporel = 1.3; // Plus d'impact en début
        } elseif ($this->set_numero >= 4) {
            $facteurTemporel = 0.7; // Moins d'impact en fin de match
        }

        return [
            'probabilite_victoire_reduite' => min($impact['mental'] * $facteurTemporel * 0.02, 0.15),
            'risque_abandon' => $this->gravite >= 8 ? 0.1 : 0.01,
            'impact_service' => $impact['mental'] * 0.01, // Impact sur % première balle
            'impact_retour' => $impact['mental'] * 0.015,
            'duree_effet_estimee' => $this->gravite * 2, // en jeux
        ];
    }
}
