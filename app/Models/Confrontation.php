<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Confrontation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'confrontations';

    protected $fillable = [
        // Joueurs
        'joueur1_id',
        'joueur2_id',

        // Résultats globaux
        'victoires_joueur1',
        'victoires_joueur2',
        'matchs_nuls', // Très rare au tennis mais possible (partage de titre)

        // Statistiques par surface (CRUCIAL pour tennis)
        'victoires_j1_dur',
        'victoires_j2_dur',
        'victoires_j1_terre_battue',
        'victoires_j2_terre_battue',
        'victoires_j1_gazon',
        'victoires_j2_gazon',
        'victoires_j1_indoor',
        'victoires_j2_indoor',

        // Résultats par catégorie de tournoi
        'victoires_j1_grand_chelem',
        'victoires_j2_grand_chelem',
        'victoires_j1_masters',
        'victoires_j2_masters',
        'victoires_j1_atp500',
        'victoires_j2_atp500',
        'victoires_j1_atp250',
        'victoires_j2_atp250',

        // Analyse des sets (patterns importants)
        'victoires_j1_straight_sets', // 2-0 ou 3-0
        'victoires_j2_straight_sets',
        'victoires_j1_trois_sets',    // 2-1
        'victoires_j2_trois_sets',
        'victoires_j1_cinq_sets',     // 3-2 (Grand Chelem hommes)
        'victoires_j2_cinq_sets',

        // Forme récente dans la confrontation
        'forme_j1_5_derniers', // W-L-W-W-L format
        'forme_j2_5_derniers',
        'forme_j1_10_derniers',
        'forme_j2_10_derniers',
        'serie_victoires_j1_actuelle',
        'serie_victoires_j2_actuelle',
        'plus_longue_serie_j1',
        'plus_longue_serie_j2',

        // Données temporelles
        'premier_match_date',
        'dernier_match_date',
        'duree_moyenne_matchs', // en minutes
        'match_le_plus_long',   // durée en minutes
        'match_le_plus_court',  // durée en minutes

        // Contexte psychologique/mental
        'abandons_j1',          // Nombre d'abandons du joueur 1
        'abandons_j2',
        'walkover_j1',
        'walkover_j2',
        'retournements_j1',     // Victoires après avoir été mené 2 sets
        'retournements_j2',
        'tie_breaks_j1',        // Tie-breaks gagnés
        'tie_breaks_j2',

        // Domination et tendances
        'elo_moyen_j1_confrontations',
        'elo_moyen_j2_confrontations',
        'evolution_dominance',   // JSON: évolution dans le temps
        'facteur_surprise',      // Nombre d'upsets dans cette confrontation

        // Métadonnées
        'fiabilite_donnees',
        'derniere_analyse',
        'source_donnees_id',
        'notes_analystes'
    ];

    protected $casts = [
        // Entiers basiques
        'victoires_joueur1' => 'integer',
        'victoires_joueur2' => 'integer',
        'matchs_nuls' => 'integer',

        // Victoires par surface
        'victoires_j1_dur' => 'integer',
        'victoires_j2_dur' => 'integer',
        'victoires_j1_terre_battue' => 'integer',
        'victoires_j2_terre_battue' => 'integer',
        'victoires_j1_gazon' => 'integer',
        'victoires_j2_gazon' => 'integer',

        // Victoires par catégorie
        'victoires_j1_grand_chelem' => 'integer',
        'victoires_j2_grand_chelem' => 'integer',
        'victoires_j1_masters' => 'integer',
        'victoires_j2_masters' => 'integer',

        // Séries
        'serie_victoires_j1_actuelle' => 'integer',
        'serie_victoires_j2_actuelle' => 'integer',
        'plus_longue_serie_j1' => 'integer',
        'plus_longue_serie_j2' => 'integer',

        // Durées
        'duree_moyenne_matchs' => 'integer',
        'match_le_plus_long' => 'integer',
        'match_le_plus_court' => 'integer',

        // Contexte
        'abandons_j1' => 'integer',
        'abandons_j2' => 'integer',
        'tie_breaks_j1' => 'integer',
        'tie_breaks_j2' => 'integer',

        // ELO et analyses
        'elo_moyen_j1_confrontations' => 'decimal:2',
        'elo_moyen_j2_confrontations' => 'decimal:2',
        'facteur_surprise' => 'decimal:1',
        'fiabilite_donnees' => 'decimal:1',

        // Dates et JSON
        'premier_match_date' => 'date',
        'dernier_match_date' => 'date',
        'derniere_analyse' => 'datetime',
        'evolution_dominance' => 'array'
    ];

    protected $appends = [
        'total_matchs',
        'pourcentage_j1',
        'pourcentage_j2',
        'joueur_dominant',
        'equilibre_confrontation',
        'tendance_recente',
        'surface_favorite_j1',
        'surface_favorite_j2',
        'niveau_rivalite'
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function joueur1()
    {
        return $this->belongsTo(Joueur::class, 'joueur1_id');
    }

    public function joueur2()
    {
        return $this->belongsTo(Joueur::class, 'joueur2_id');
    }

    public function sourceDonnees()
    {
        return $this->belongsTo(SourceDonnees::class, 'source_donnees_id');
    }

    // Relation vers tous les matchs de cette confrontation
    public function matchsHistorique()
    {
        return MatchTennis::where(function($query) {
            $query->where(['joueur1_id' => $this->joueur1_id, 'joueur2_id' => $this->joueur2_id])
                ->orWhere(['joueur1_id' => $this->joueur2_id, 'joueur2_id' => $this->joueur1_id]);
        })
            ->whereNotNull('gagnant_id')
            ->orderBy('date_match', 'desc');
    }

    public function dernierMatch()
    {
        return $this->matchsHistorique()->first();
    }

    public function premierMatch()
    {
        return $this->matchsHistorique()->orderBy('date_match', 'asc')->first();
    }

    // ===================================================================
    // ACCESSORS
    // ===================================================================

    public function getTotalMatchsAttribute()
    {
        return $this->victoires_joueur1 + $this->victoires_joueur2 + ($this->matchs_nuls ?? 0);
    }

    public function getPourcentageJ1Attribute()
    {
        return $this->total_matchs > 0 ?
            round(($this->victoires_joueur1 / $this->total_matchs) * 100, 1) : 0;
    }

    public function getPourcentageJ2Attribute()
    {
        return $this->total_matchs > 0 ?
            round(($this->victoires_joueur2 / $this->total_matchs) * 100, 1) : 0;
    }

    public function getJoueurDominantAttribute()
    {
        if ($this->victoires_joueur1 > $this->victoires_joueur2) {
            return $this->joueur1_id;
        } elseif ($this->victoires_joueur2 > $this->victoires_joueur1) {
            return $this->joueur2_id;
        }
        return null; // Égalité
    }

    public function getEquilibreConfrontationAttribute()
    {
        if ($this->total_matchs < 3) return 'insuffisant';

        $difference = abs($this->victoires_joueur1 - $this->victoires_joueur2);
        $pourcentageDiff = ($difference / $this->total_matchs) * 100;

        if ($pourcentageDiff <= 20) return 'très_équilibré';
        if ($pourcentageDiff <= 40) return 'équilibré';
        if ($pourcentageDiff <= 60) return 'déséquilibré';
        return 'très_déséquilibré';
    }

    public function getTendanceRecenteAttribute()
    {
        if (!$this->forme_j1_5_derniers) return 'indéterminée';

        // Analyser les 5 derniers matchs
        $forme1 = str_split($this->forme_j1_5_derniers);
        $victoires1_recentes = count(array_filter($forme1, fn($x) => $x === 'W'));

        if ($victoires1_recentes >= 4) return 'j1_dominant';
        if ($victoires1_recentes >= 3) return 'j1_légèrement_dominant';
        if ($victoires1_recentes == 2) return 'équilibré';
        if ($victoires1_recentes <= 1) return 'j2_dominant';

        return 'équilibré';
    }

    public function getSurfaceFavoriteJ1Attribute()
    {
        $surfaces = [
            'dur' => $this->victoires_j1_dur,
            'terre_battue' => $this->victoires_j1_terre_battue,
            'gazon' => $this->victoires_j1_gazon,
            'indoor' => $this->victoires_j1_indoor
        ];

        $surfaceFavorite = array_search(max($surfaces), $surfaces);
        return $surfaceFavorite ?: 'non_déterminée';
    }

    public function getSurfaceFavoriteJ2Attribute()
    {
        $surfaces = [
            'dur' => $this->victoires_j2_dur,
            'terre_battue' => $this->victoires_j2_terre_battue,
            'gazon' => $this->victoires_j2_gazon,
            'indoor' => $this->victoires_j2_indoor
        ];

        $surfaceFavorite = array_search(max($surfaces), $surfaces);
        return $surfaceFavorite ?: 'non_déterminée';
    }

    public function getNiveauRivaliteAttribute()
    {
        $score = 0;

        // Nombre de matchs (plus il y en a, plus c'est une rivalité)
        $score += min($this->total_matchs * 2, 20);

        // Équilibre (plus c'est équilibré, plus c'est une rivalité)
        $equilibre = $this->equilibre_confrontation;
        if ($equilibre === 'très_équilibré') $score += 30;
        elseif ($equilibre === 'équilibré') $score += 20;
        elseif ($equilibre === 'déséquilibré') $score += 10;

        // Durée de la rivalité
        if ($this->premier_match_date && $this->dernier_match_date) {
            $anneesRivalite = $this->premier_match_date->diffInYears($this->dernier_match_date);
            $score += min($anneesRivalite * 3, 15);
        }

        // Matchs importants (Grand Chelem, Masters)
        $matchsImportants = ($this->victoires_j1_grand_chelem + $this->victoires_j2_grand_chelem) * 5 +
            ($this->victoires_j1_masters + $this->victoires_j2_masters) * 3;
        $score += min($matchsImportants, 20);

        // Retournements et drama
        $score += ($this->retournements_j1 + $this->retournements_j2) * 2;

        if ($score >= 80) return 'rivalité_légendaire';
        if ($score >= 60) return 'grande_rivalité';
        if ($score >= 40) return 'rivalité_notable';
        if ($score >= 20) return 'rivalité_émergente';
        return 'confrontation_standard';
    }

    public function getDureeFormateeAttribute()
    {
        if (!$this->duree_moyenne_matchs) return null;

        $heures = floor($this->duree_moyenne_matchs / 60);
        $minutes = $this->duree_moyenne_matchs % 60;

        return sprintf('%dh%02d', $heures, $minutes);
    }

    // ===================================================================
    // SCOPES
    // ===================================================================

    public function scopeAvecJoueur($query, $joueurId)
    {
        return $query->where('joueur1_id', $joueurId)
            ->orWhere('joueur2_id', $joueurId);
    }

    public function scopeRivalitesActives($query, $moisMax = 12)
    {
        return $query->where('dernier_match_date', '>=', now()->subMonths($moisMax));
    }

    public function scopeEquilibrees($query)
    {
        return $query->whereRaw('ABS(victoires_joueur1 - victoires_joueur2) <= (victoires_joueur1 + victoires_joueur2) * 0.2');
    }

    public function scopeMinimumMatchs($query, $minimum = 5)
    {
        return $query->whereRaw('(victoires_joueur1 + victoires_joueur2) >= ?', [$minimum]);
    }

    public function scopeGrandesRivalites($query)
    {
        return $query->minimumMatchs(10)
            ->rivalitesActives(24);
    }

    public function scopeParSurface($query, $surface)
    {
        $colonneJ1 = "victoires_j1_{$surface}";
        $colonneJ2 = "victoires_j2_{$surface}";

        return $query->whereRaw("({$colonneJ1} + {$colonneJ2}) > 0");
    }

    public function scopeFiables($query, $seuilFiabilite = 80)
    {
        return $query->where('fiabilite_donnees', '>=', $seuilFiabilite);
    }

    // ===================================================================
    // METHODS TENNIS AI
    // ===================================================================

    /**
     * Prédire le vainqueur de la prochaine confrontation
     */
    public function predireProchainVainqueur($surface = null, $categorieTournoi = null)
    {
        $score = 0;

        // 1. Historique global (30%)
        if ($this->total_matchs > 0) {
            $avantageJ1 = ($this->victoires_joueur1 - $this->victoires_joueur2) / $this->total_matchs;
            $score += $avantageJ1 * 30;
        }

        // 2. Surface spécifique (25%)
        if ($surface) {
            $victJ1Surface = $this->{"victoires_j1_{$surface}"} ?? 0;
            $victJ2Surface = $this->{"victoires_j2_{$surface}"} ?? 0;
            $totalSurface = $victJ1Surface + $victJ2Surface;

            if ($totalSurface > 0) {
                $avantageSurface = ($victJ1Surface - $victJ2Surface) / $totalSurface;
                $score += $avantageSurface * 25;
            }
        }

        // 3. Forme récente (25%)
        if ($this->forme_j1_5_derniers) {
            $forme1 = str_split($this->forme_j1_5_derniers);
            $victoires1_recentes = count(array_filter($forme1, fn($x) => $x === 'W'));
            $avantageFormeRecente = ($victoires1_recentes - 2.5) / 2.5; // Centré sur 2.5/5
            $score += $avantageFormeRecente * 25;
        }

        // 4. Série actuelle (20%)
        $serieJ1 = $this->serie_victoires_j1_actuelle ?? 0;
        $serieJ2 = $this->serie_victoires_j2_actuelle ?? 0;
        if ($serieJ1 > 0 || $serieJ2 > 0) {
            $avantageSerie = ($serieJ1 - $serieJ2) / max($serieJ1 + $serieJ2, 1);
            $score += $avantageSerie * 20;
        }

        // Conversion en probabilité
        $probabiliteJ1 = 0.5 + ($score / 200); // Normalisation autour de 50%
        $probabiliteJ1 = max(0.1, min(0.9, $probabiliteJ1)); // Bornes 10%-90%

        return [
            'joueur_favori_id' => $probabiliteJ1 > 0.5 ? $this->joueur1_id : $this->joueur2_id,
            'probabilite_joueur1' => round($probabiliteJ1 * 100, 1),
            'probabilite_joueur2' => round((1 - $probabiliteJ1) * 100, 1),
            'confiance' => $this->calculerConfiance(),
            'facteurs' => [
                'historique_global' => $this->total_matchs,
                'surface_specifique' => $surface ? $this->getMatchsSurface($surface) : 0,
                'forme_recente' => $this->forme_j1_5_derniers ? 5 : 0,
                'serie_actuelle' => max($serieJ1, $serieJ2)
            ]
        ];
    }

    /**
     * Calculer le niveau de confiance de la prédiction
     */
    public function calculerConfiance()
    {
        $confiance = 0;

        // Plus de matchs = plus de confiance
        $confiance += min($this->total_matchs * 5, 40);

        // Données récentes = plus de confiance
        if ($this->dernier_match_date && $this->dernier_match_date->diffInMonths(now()) <= 12) {
            $confiance += 20;
        }

        // Fiabilité des données
        $confiance += ($this->fiabilite_donnees ?? 80) * 0.3;

        // Variété des surfaces
        $surfaces = [$this->victoires_j1_dur + $this->victoires_j2_dur > 0,
            $this->victoires_j1_terre_battue + $this->victoires_j2_terre_battue > 0,
            $this->victoires_j1_gazon + $this->victoires_j2_gazon > 0];
        $confiance += count(array_filter($surfaces)) * 5;

        return min(100, round($confiance, 1));
    }

    /**
     * Analyser l'évolution de la dominance dans le temps
     */
    public function analyserEvolutionDominance()
    {
        if (!$this->evolution_dominance) return null;

        $evolution = $this->evolution_dominance;
        $tendances = [];

        for ($i = 1; $i < count($evolution); $i++) {
            $periode_actuelle = $evolution[$i];
            $periode_precedente = $evolution[$i-1];

            $changement = $periode_actuelle['ratio_j1'] - $periode_precedente['ratio_j1'];

            $tendances[] = [
                'periode' => $periode_actuelle['periode'],
                'changement' => $changement,
                'tendance' => $changement > 0.1 ? 'j1_progresse' :
                    ($changement < -0.1 ? 'j2_progresse' : 'stable')
            ];
        }

        return $tendances;
    }

    /**
     * Obtenir les facteurs psychologiques de la confrontation
     */
    public function getFacteursPsychologiques()
    {
        $facteurs = [];

        // Joueur avec complexe psychologique
        if ($this->total_matchs >= 5) {
            $ratio_j1 = $this->pourcentage_j1;
            if ($ratio_j1 <= 20) {
                $facteurs[] = [
                    'type' => 'complexe_psychologique',
                    'joueur_affecte' => $this->joueur1_id,
                    'intensite' => 'forte'
                ];
            } elseif ($ratio_j1 >= 80) {
                $facteurs[] = [
                    'type' => 'dominance_psychologique',
                    'joueur_dominant' => $this->joueur1_id,
                    'intensite' => 'forte'
                ];
            }
        }

        // Série actuelle
        if ($this->serie_victoires_j1_actuelle >= 3) {
            $facteurs[] = [
                'type' => 'série_victoires',
                'joueur' => $this->joueur1_id,
                'nombre' => $this->serie_victoires_j1_actuelle
            ];
        } elseif ($this->serie_victoires_j2_actuelle >= 3) {
            $facteurs[] = [
                'type' => 'série_victoires',
                'joueur' => $this->joueur2_id,
                'nombre' => $this->serie_victoires_j2_actuelle
            ];
        }

        // Retournements spectaculaires
        if (($this->retournements_j1 + $this->retournements_j2) > 0) {
            $facteurs[] = [
                'type' => 'mental_fort',
                'description' => 'Capacité aux retournements dans cette confrontation'
            ];
        }

        return $facteurs;
    }

    /**
     * Comparer les performances par surface
     */
    public function comparerParSurface()
    {
        $surfaces = ['dur', 'terre_battue', 'gazon', 'indoor'];
        $comparaison = [];

        foreach ($surfaces as $surface) {
            $victJ1 = $this->{"victoires_j1_{$surface}"} ?? 0;
            $victJ2 = $this->{"victoires_j2_{$surface}"} ?? 0;
            $total = $victJ1 + $victJ2;

            if ($total > 0) {
                $comparaison[$surface] = [
                    'total_matchs' => $total,
                    'victoires_j1' => $victJ1,
                    'victoires_j2' => $victJ2,
                    'pourcentage_j1' => round(($victJ1 / $total) * 100, 1),
                    'joueur_dominant' => $victJ1 > $victJ2 ? $this->joueur1_id : $this->joueur2_id,
                    'niveau_dominance' => abs($victJ1 - $victJ2) / $total
                ];
            }
        }

        return $comparaison;
    }

    /**
     * Obtenir les statistiques complètes de la rivalité
     */
    public function getStatistiquesCompletes()
    {
        return [
            'resume' => [
                'total_matchs' => $this->total_matchs,
                'duree_rivalite_annees' => $this->premier_match_date ?
                    $this->premier_match_date->diffInYears($this->dernier_match_date) : 0,
                'niveau_rivalite' => $this->niveau_rivalite,
                'equilibre' => $this->equilibre_confrontation
            ],
            'resultats' => [
                'joueur1' => [
                    'victoires' => $this->victoires_joueur1,
                    'pourcentage' => $this->pourcentage_j1,
                    'serie_actuelle' => $this->serie_victoires_j1_actuelle,
                    'plus_longue_serie' => $this->plus_longue_serie_j1
                ],
                'joueur2' => [
                    'victoires' => $this->victoires_joueur2,
                    'pourcentage' => $this->pourcentage_j2,
                    'serie_actuelle' => $this->serie_victoires_j2_actuelle,
                    'plus_longue_serie' => $this->plus_longue_serie_j2
                ]
            ],
            'par_surface' => $this->comparerParSurface(),
            'facteurs_psychologiques' => $this->getFacteursPsychologiques(),
            'prediction_prochaine' => $this->predireProchainVainqueur()
        ];
    }

    /**
     * Mettre à jour après un nouveau match
     */
    public function mettreAJourApresMatch(MatchTennis $match)
    {
        // Mise à jour des victoires globales
        if ($match->gagnant_id == $this->joueur1_id) {
            $this->victoires_joueur1++;
            $this->serie_victoires_j1_actuelle++;
            $this->serie_victoires_j2_actuelle = 0;
        } else {
            $this->victoires_joueur2++;
            $this->serie_victoires_j2_actuelle++;
            $this->serie_victoires_j1_actuelle = 0;
        }

        // Mise à jour par surface
        $surface = $match->surface?->code;
        if ($surface) {
            $colonneVictoires = $match->gagnant_id == $this->joueur1_id ?
                "victoires_j1_{$surface}" : "victoires_j2_{$surface}";
            $this->$colonneVictoires++;
        }

        // Mise à jour des dates
        $this->dernier_match_date = $match->date_match;
        if (!$this->premier_match_date) {
            $this->premier_match_date = $match->date_match;
        }

        // Mise à jour forme récente (simplifié)
        $this->updateFormeRecente($match);

        $this->save();
    }

    /**
     * Mettre à jour la forme récente
     */
    private function updateFormeRecente(MatchTennis $match)
    {
        $resultatJ1 = $match->gagnant_id == $this->joueur1_id ? 'W' : 'L';
        $resultatJ2 = $match->gagnant_id == $this->joueur2_id ? 'W' : 'L';

        // Ajouter le nouveau résultat et garder seulement les 5 derniers
        $this->forme_j1_5_derniers = substr($resultatJ1 . ($this->forme_j1_5_derniers ?? ''), 0, 5);
        $this->forme_j2_5_derniers = substr($resultatJ2 . ($this->forme_j2_5_derniers ?? ''), 0, 5);

        // Idem pour 10 derniers
        $this->forme_j1_10_derniers = substr($resultatJ1 . ($this->forme_j1_10_derniers ?? ''), 0, 10);
        $this->forme_j2_10_derniers = substr($resultatJ2 . ($this->forme_j2_10_derniers ?? ''), 0, 10);
    }

    private function getMatchsSurface($surface)
    {
        $victJ1 = $this->{"victoires_j1_{$surface}"} ?? 0;
        $victJ2 = $this->{"victoires_j2_{$surface}"} ?? 0;
        return $victJ1 + $victJ2;
    }

    // ===================================================================
    // VALIDATION RULES
    // ===================================================================

    public static function validationRules()
    {
        return [
            'joueur1_id' => 'required|exists:joueurs,id',
            'joueur2_id' => 'required|exists:joueurs,id|different:joueur1_id',
            'victoires_joueur1' => 'required|integer|min:0',
            'victoires_joueur2' => 'required|integer|min:0',
            'fiabilite_donnees' => 'required|numeric|between:0,100'
        ];
    }

    // ===================================================================
    // BOOT METHODS
    // ===================================================================

    protected static function boot()
    {
        parent::boot();

        // Auto-calculs lors de la sauvegarde
        static::saving(function ($confrontation) {
            // Mise à jour dernière analyse
            $confrontation->derniere_analyse = now();

            // Calcul automatique des plus longues séries
            $confrontation->plus_longue_serie_j1 = max(
                $confrontation->plus_longue_serie_j1 ?? 0,
                $confrontation->serie_victoires_j1_actuelle ?? 0
            );

            $confrontation->plus_longue_serie_j2 = max(
                $confrontation->plus_longue_serie_j2 ?? 0,
                $confrontation->serie_victoires_j2_actuelle ?? 0
            );
        });
    }
}
