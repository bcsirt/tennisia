<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatistiqueJoueur extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'statistique_joueurs';

    protected $fillable = [
        // Références
        'joueur_id',
        'saison_id',
        'surface_id', // CRUCIAL: Stats par surface
        'categorie_tournoi_id', // Stats par niveau de tournoi

        // Résultats généraux
        'victoires',
        'defaites',
        'victoires_consecutives',
        'defaites_consecutives',
        'abandon_total',
        'walkover_donnes',
        'walkover_recus',

        // Service (données cruciales pour prédictions)
        'aces',
        'double_fautes',
        'premiers_services_tentes',
        'premiers_services_reussis',
        'deuxiemes_services_tentes',
        'deuxiemes_services_reussis',
        'points_gagnes_premier_service',
        'points_gagnes_deuxieme_service',
        'jeux_service_gagnes',
        'jeux_service_joues',

        // Retour (défense)
        'points_retour_gagnes',
        'points_retour_joues',
        'break_points_converts',
        'break_points_totaux',
        'break_points_sauves',
        'break_points_concedes',
        'jeux_retour_gagnes',
        'jeux_retour_joues',

        // Performance générale
        'points_gagnes_total',
        'points_joues_total',
        'jeux_gagnes',
        'jeux_perdus',
        'sets_gagnes',
        'sets_perdus',

        // Situations spéciales
        'tie_breaks_gagnes',
        'tie_breaks_joues',
        'matches_5_sets',
        'matches_4_sets',
        'matches_3_sets',

        // Durée et physique
        'duree_moyenne_match', // en minutes
        'duree_totale_jouee',
        'matchs_plus_3h',
        'matchs_moins_1h',

        // Classement et performance
        'elo_rating',
        'elo_evolution',
        'meilleur_elo_periode',
        'classement_debut_periode',
        'classement_fin_periode',
        'meilleur_classement_periode',

        // Contexte mental/physique
        'victoires_top_10',
        'defaites_top_10',
        'victoires_top_50',
        'defaites_top_50',
        'victoires_finaliste',
        'defaites_finaliste',

        // Prize money et points
        'prize_money_gagne',
        'points_atp_wta_gagnes',
        'tournois_joues',
        'tournois_gagnes',
        'finales_jouees',
        'demi_finales_jouees',

        // Forme récente (derniers 52 matchs standard)
        'forme_recente_5_matchs',
        'forme_recente_10_matchs',
        'forme_recente_20_matchs',

        // Métadonnées
        'derniere_mise_a_jour',
        'fiabilite_donnees', // 0-100%
        'nombre_matchs_echantillon'
    ];

    protected $casts = [
        // Entiers
        'victoires' => 'integer',
        'defaites' => 'integer',
        'aces' => 'integer',
        'double_fautes' => 'integer',
        'premiers_services_tentes' => 'integer',
        'premiers_services_reussis' => 'integer',
        'points_gagnes_total' => 'integer',
        'points_joues_total' => 'integer',
        'tie_breaks_gagnes' => 'integer',
        'tie_breaks_joues' => 'integer',
        'duree_moyenne_match' => 'integer',
        'duree_totale_jouee' => 'integer',
        'tournois_joues' => 'integer',
        'tournois_gagnes' => 'integer',
        'nombre_matchs_echantillon' => 'integer',

        // Décimaux
        'elo_rating' => 'decimal:2',
        'elo_evolution' => 'decimal:2',
        'meilleur_elo_periode' => 'decimal:2',
        'prize_money_gagne' => 'decimal:2',
        'fiabilite_donnees' => 'decimal:1',

        // Floats pour pourcentages
        'forme_recente_5_matchs' => 'float',
        'forme_recente_10_matchs' => 'float',
        'forme_recente_20_matchs' => 'float',

        // Dates
        'derniere_mise_a_jour' => 'datetime'
    ];

    protected $appends = [
        'ratio_victoires',
        'pourcentage_premier_service',
        'pourcentage_deuxieme_service',
        'pourcentage_points_service',
        'pourcentage_break_points_converts',
        'pourcentage_break_points_sauves',
        'efficacite_retour',
        'niveau_performance',
        'tendance_elo'
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function joueur()
    {
        return $this->belongsTo(Joueur::class);
    }

    public function saison()
    {
        return $this->belongsTo(Saison::class);
    }

    public function surface()
    {
        return $this->belongsTo(Surface::class);
    }

    public function categorieTournoi()
    {
        return $this->belongsTo(CategorieTournoi::class, 'categorie_tournoi_id');
    }

    // Relations calculées
    public function matchsJoues()
    {
        return $this->victoires + $this->defaites;
    }

    // ===================================================================
    // ACCESSORS (Calculs automatiques)
    // ===================================================================

    public function getRatioVictoiresAttribute()
    {
        $total = $this->victoires + $this->defaites;
        return $total > 0 ? round($this->victoires / $total, 4) : 0;
    }

    public function getPourcentagePremierServiceAttribute()
    {
        return $this->premiers_services_tentes > 0 ?
            round(($this->premiers_services_reussis / $this->premiers_services_tentes) * 100, 1) : 0;
    }

    public function getPourcentageDeuxiemeServiceAttribute()
    {
        return $this->deuxiemes_services_tentes > 0 ?
            round(($this->deuxiemes_services_reussis / $this->deuxiemes_services_tentes) * 100, 1) : 0;
    }

    public function getPourcentagePointsServiceAttribute()
    {
        $pointsServiceTotal = $this->points_gagnes_premier_service + $this->points_gagnes_deuxieme_service;
        $pointsServiceJoues = $this->premiers_services_tentes + $this->deuxiemes_services_tentes;

        return $pointsServiceJoues > 0 ?
            round(($pointsServiceTotal / $pointsServiceJoues) * 100, 1) : 0;
    }

    public function getPourcentageBreakPointsConvertsAttribute()
    {
        return $this->break_points_totaux > 0 ?
            round(($this->break_points_converts / $this->break_points_totaux) * 100, 1) : 0;
    }

    public function getPourcentageBreakPointsSauvesAttribute()
    {
        return $this->break_points_concedes > 0 ?
            round(($this->break_points_sauves / $this->break_points_concedes) * 100, 1) : 0;
    }

    public function getEfficaciteRetourAttribute()
    {
        return $this->points_retour_joues > 0 ?
            round(($this->points_retour_gagnes / $this->points_retour_joues) * 100, 1) : 0;
    }

    public function getNiveauPerformanceAttribute()
    {
        $score = 0;

        // Ratio victoires (40% du score)
        $score += $this->ratio_victoires * 40;

        // Service (30% du score)
        $serviceScore = ($this->pourcentage_premier_service / 100) * 15 +
            ($this->pourcentage_points_service / 100) * 15;
        $score += $serviceScore;

        // Retour (20% du score)
        $score += ($this->efficacite_retour / 100) * 20;

        // Break points (10% du score)
        $breakScore = ($this->pourcentage_break_points_converts / 100) * 5 +
            ($this->pourcentage_break_points_sauves / 100) * 5;
        $score += $breakScore;

        return round($score, 1);
    }

    public function getTendanceEloAttribute()
    {
        if (!$this->elo_evolution) return 'stable';

        if ($this->elo_evolution > 50) return 'forte_hausse';
        if ($this->elo_evolution > 20) return 'hausse';
        if ($this->elo_evolution > 5) return 'legere_hausse';
        if ($this->elo_evolution < -50) return 'forte_baisse';
        if ($this->elo_evolution < -20) return 'baisse';
        if ($this->elo_evolution < -5) return 'legere_baisse';

        return 'stable';
    }

    public function getDureeFormateeAttribute()
    {
        if (!$this->duree_moyenne_match) return null;

        $heures = floor($this->duree_moyenne_match / 60);
        $minutes = $this->duree_moyenne_match % 60;

        return sprintf('%dh%02d', $heures, $minutes);
    }

    public function getForceServiceAttribute()
    {
        // Score composite du service (0-100)
        $acesParMatch = $this->matchsJoues() > 0 ? $this->aces / $this->matchsJoues() : 0;
        $doubleFautesParMatch = $this->matchsJoues() > 0 ? $this->double_fautes / $this->matchsJoues() : 0;

        $score = 0;
        $score += min($acesParMatch * 10, 30); // Max 30 points pour les aces
        $score += $this->pourcentage_premier_service * 0.3; // Max 30 points
        $score += $this->pourcentage_points_service * 0.4; // Max 40 points
        $score -= min($doubleFautesParMatch * 5, 20); // Malus double fautes

        return max(0, min(100, round($score, 1)));
    }

    public function getForceRetourAttribute()
    {
        // Score composite du retour (0-100)
        $score = 0;
        $score += $this->efficacite_retour * 0.5; // Max 50 points
        $score += $this->pourcentage_break_points_converts * 0.3; // Max 30 points
        $score += min(($this->jeux_retour_gagnes / max($this->jeux_retour_joues, 1)) * 100 * 0.2, 20); // Max 20 points

        return round($score, 1);
    }

    // ===================================================================
    // SCOPES
    // ===================================================================

    public function scopeParSurface($query, $surfaceCode)
    {
        return $query->whereHas('surface', function($q) use ($surfaceCode) {
            $q->where('code', $surfaceCode);
        });
    }

    public function scopeParSaison($query, $annee)
    {
        return $query->whereHas('saison', function($q) use ($annee) {
            $q->where('annee', $annee);
        });
    }

    public function scopeParCategorie($query, $categorieCode)
    {
        return $query->whereHas('categorieTournoi', function($q) use ($categorieCode) {
            $q->where('code', $categorieCode);
        });
    }

    public function scopeAuMoinsXMatchs($query, $minimum = 10)
    {
        return $query->whereRaw('(victoires + defaites) >= ?', [$minimum]);
    }

    public function scopeTopPerformeurs($query, $limite = 10)
    {
        return $query->auMoinsXMatchs(20)
            ->orderByRaw('(victoires / (victoires + defaites)) DESC')
            ->limit($limite);
    }

    public function scopeEloSuperieur($query, $seuil = 2000)
    {
        return $query->where('elo_rating', '>=', $seuil);
    }

    public function scopeEnProgression($query)
    {
        return $query->where('elo_evolution', '>', 0);
    }

    public function scopeEnRegression($query)
    {
        return $query->where('elo_evolution', '<', 0);
    }

    public function scopeFiables($query, $seuilFiabilite = 80)
    {
        return $query->where('fiabilite_donnees', '>=', $seuilFiabilite);
    }

    // ===================================================================
    // METHODS TENNIS AI
    // ===================================================================

    /**
     * Comparer les performances avec un autre joueur
     */
    public function comparerAvec(StatistiqueJoueur $autre)
    {
        return [
            'ratio_victoires' => [
                'joueur' => $this->ratio_victoires,
                'autre' => $autre->ratio_victoires,
                'avantage' => $this->ratio_victoires > $autre->ratio_victoires ? 'joueur' : 'autre'
            ],
            'elo_rating' => [
                'joueur' => $this->elo_rating,
                'autre' => $autre->elo_rating,
                'difference' => $this->elo_rating - $autre->elo_rating
            ],
            'service' => [
                'joueur' => $this->force_service,
                'autre' => $autre->force_service,
                'avantage' => $this->force_service > $autre->force_service ? 'joueur' : 'autre'
            ],
            'retour' => [
                'joueur' => $this->force_retour,
                'autre' => $autre->force_retour,
                'avantage' => $this->force_retour > $autre->force_retour ? 'joueur' : 'autre'
            ]
        ];
    }

    /**
     * Calculer la probabilité de victoire basée sur l'ELO
     */
    public function calculerProbabiliteVictoire($eloAdversaire)
    {
        $difference = $this->elo_rating - $eloAdversaire;
        return 1 / (1 + pow(10, -$difference / 400));
    }

    /**
     * Obtenir les points forts du joueur
     */
    public function getPointsForts()
    {
        $points = [];

        if ($this->pourcentage_premier_service >= 70) {
            $points[] = 'Service puissant';
        }

        if ($this->force_service >= 80) {
            $points[] = 'Serveur exceptionnel';
        }

        if ($this->efficacite_retour >= 40) {
            $points[] = 'Excellent retourneur';
        }

        if ($this->pourcentage_break_points_converts >= 50) {
            $points[] = 'Opportuniste sur break points';
        }

        if ($this->tie_breaks_joues > 0 && ($this->tie_breaks_gagnes / $this->tie_breaks_joues) >= 0.6) {
            $points[] = 'Solide en tie-break';
        }

        if ($this->duree_moyenne_match && $this->duree_moyenne_match >= 180) {
            $points[] = 'Endurant physiquement';
        }

        return $points;
    }

    /**
     * Obtenir les points faibles du joueur
     */
    public function getPointsFaibles()
    {
        $faiblesses = [];

        if ($this->pourcentage_premier_service < 55) {
            $faiblesses[] = 'Premier service irrégulier';
        }

        if ($this->matchsJoues() > 0 && ($this->double_fautes / $this->matchsJoues()) > 3) {
            $faiblesses[] = 'Trop de doubles fautes';
        }

        if ($this->efficacite_retour < 30) {
            $faiblesses[] = 'Retour perfectible';
        }

        if ($this->pourcentage_break_points_sauves < 60) {
            $faiblesses[] = 'Vulnérable sur son service';
        }

        if ($this->abandon_total > 0 && $this->abandon_total / $this->matchsJoues() > 0.05) {
            $faiblesses[] = 'Fragilité physique/mentale';
        }

        return $faiblesses;
    }

    /**
     * Prédire l'évolution ELO basée sur la tendance
     */
    public function predireEvoElo($matchsAVenir = 5)
    {
        if (!$this->elo_evolution) return $this->elo_rating;

        // Prédiction simple basée sur la tendance actuelle avec amortissement
        $facteurAmortissement = 0.8; // La tendance s'amortit avec le temps
        $evolutionPrevue = $this->elo_evolution * $facteurAmortissement * ($matchsAVenir / 10);

        return $this->elo_rating + $evolutionPrevue;
    }

    /**
     * Calculer l'indice de forme récente
     */
    public function getIndiceForme()
    {
        // Pondération: forme récente (5 matchs) plus importante
        $indiceForme = 0;
        $indiceForme += $this->forme_recente_5_matchs * 0.5;
        $indiceForme += $this->forme_recente_10_matchs * 0.3;
        $indiceForme += $this->forme_recente_20_matchs * 0.2;

        return round($indiceForme, 2);
    }

    /**
     * Obtenir le niveau de dominance sur surface
     */
    public function getNiveauDominanceSurface()
    {
        if (!$this->surface) return 'indéterminé';

        $ratioVictoires = $this->ratio_victoires;
        $eloRating = $this->elo_rating;

        if ($ratioVictoires >= 0.85 && $eloRating >= 2400) return 'dominant';
        if ($ratioVictoires >= 0.75 && $eloRating >= 2200) return 'très_fort';
        if ($ratioVictoires >= 0.65 && $eloRating >= 2000) return 'fort';
        if ($ratioVictoires >= 0.55 && $eloRating >= 1800) return 'solide';
        if ($ratioVictoires >= 0.45) return 'moyen';

        return 'faible';
    }

    // ===================================================================
    // VALIDATION RULES
    // ===================================================================

    public static function validationRules()
    {
        return [
            'joueur_id' => 'required|exists:joueurs,id',
            'saison_id' => 'required|exists:saisons,id',
            'surface_id' => 'nullable|exists:surfaces,id',
            'victoires' => 'required|integer|min:0',
            'defaites' => 'required|integer|min:0',
            'aces' => 'required|integer|min:0',
            'double_fautes' => 'required|integer|min:0',
            'elo_rating' => 'nullable|numeric|between:800,3000',
            'fiabilite_donnees' => 'required|numeric|between:0,100'
        ];
    }

    // ===================================================================
    // BOOT METHODS
    // ===================================================================

    protected static function boot()
    {
        parent::boot();

        // Auto-calcul de certains champs lors de la sauvegarde
        static::saving(function ($stat) {
            // Mise à jour automatique de la dernière MaJ
            $stat->derniere_mise_a_jour = now();

            // Calcul du nombre de matchs échantillon
            $stat->nombre_matchs_echantillon = $stat->victoires + $stat->defaites;

            // Validation de cohérence des données
            if ($stat->premiers_services_reussis > $stat->premiers_services_tentes) {
                $stat->premiers_services_tentes = $stat->premiers_services_reussis;
            }
        });
    }
}
