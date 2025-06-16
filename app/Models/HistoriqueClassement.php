<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistoriqueClassement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'historique_classements';

    protected $fillable = [
        // Identification et base
        'joueur_id',
        'date_classement',
        'semaine_atp',              // Semaine ATP (1-52)
        'annee',
        'periode_reference',        // '2024-W15', '2024-Q2'

        // Types de classements
        'type_classement',          // 'atp', 'wta', 'itf', 'elo_global', 'elo_surface', 'national', 'junior'
        'sous_type',               // 'singles', 'doubles', 'race'
        'categorie_age',           // 'senior', 'junior', 'veteran'
        'surface_specifique',      // 'dur', 'terre', 'gazon', 'indoor' (pour ELO surface)

        // Classements et positions
        'classement_actuel',
        'classement_precedent',
        'classement_il_y_a_4_semaines',
        'classement_il_y_a_12_semaines',
        'classement_debut_annee',
        'meilleur_classement_annee',
        'pire_classement_annee',
        'meilleur_classement_carriere',
        'classement_pays',          // Classement national
        'classement_zone',          // Classement zone géographique

        // Points et scores
        'points_actuels',
        'points_precedents',
        'points_gagnes_semaine',
        'points_perdus_semaine',
        'points_evolution',         // Différence points
        'points_a_defendre',        // Points à défendre prochaines semaines
        'points_race',              // Points course au Masters/WTA Finals
        'points_race_precedents',

        // ELO et ratings alternatifs
        'elo_rating',
        'elo_precedent',
        'elo_evolution',
        'elo_max_carriere',
        'elo_surface_dur',
        'elo_surface_terre',
        'elo_surface_gazon',
        'elo_surface_indoor',
        'rating_alternatif',        // Autres systèmes de rating
        'rating_live',              // Rating en temps réel

        // Évolutions et tendances
        'evolution_position',       // Nombre de places gagnées/perdues
        'evolution_pourcentage',    // % d'évolution
        'tendance_4_semaines',      // 'hausse', 'baisse', 'stable'
        'tendance_12_semaines',     // Tendance sur 3 mois
        'tendance_annee',           // Tendance annuelle
        'streak_hausse',            // Nombre de semaines consécutives en hausse
        'streak_baisse',            // Nombre de semaines consécutives en baisse
        'volatilite_score',         // Score de volatilité du classement
        'stabilite_score',          // Score de stabilité

        // Contexte et événements
        'evenement_declencheur',    // 'victoire_tournoi', 'finale', 'elimination_precoce'
        'tournoi_impact_id',        // ID tournoi ayant impacté le classement
        'resultat_impact',          // 'victoire', 'finale', '1/2', '1/4', '1er_tour'
        'points_tournoi_impact',    // Points gagnés/perdus au tournoi
        'adversaires_battus_semaine', // JSON des adversaires battus
        'defaites_semaine',         // JSON des défaites

        // Comparaisons et benchmarks
        'ecart_top_1',              // Écart en points avec le #1
        'ecart_top_10',             // Écart en points avec le #10
        'ecart_top_50',             // Écart en points avec le #50
        'ecart_top_100',            // Écart en points avec le #100
        'pourcentage_vs_top_1',     // % des points du #1
        'places_vers_top_10',       // Nombre de places pour atteindre top 10
        'places_vers_top_50',       // Nombre de places pour atteindre top 50
        'classement_cohorte',       // Classement parmi joueurs même âge

        // Prédictions et projections
        'classement_predit_4_semaines', // Classement prédit par IA
        'classement_predit_12_semaines',
        'classement_predit_fin_annee',
        'probabilite_top_10',       // % chance d'atteindre top 10
        'probabilite_top_50',       // % chance d'atteindre top 50
        'projection_points_fin_annee',
        'confiance_prediction',     // Score confiance prédiction IA
        'facteurs_prediction',      // JSON facteurs pris en compte

        // Métriques de performance
        'forme_recent',             // Score forme récente 1-100
        'momentum',                 // Score momentum 1-100
        'progression_vitesse',      // Vitesse de progression
        'acceleration',             // Accélération progression
        'potentiel_restant',        // Potentiel d'amélioration estimé
        'performance_vs_attente',   // Performance vs attente
        'facteur_surprise',         // Facteur de surprise des résultats

        // Analyse concurrentielle
        'rivaux_directs',           // JSON des rivaux classements similaires
        'menaces_montantes',        // JSON joueurs qui montent rapidement
        'opportunites_progression', // JSON opportunités de progresser
        'calendrier_impact',        // Impact calendrier sur classement
        'surface_favorisante',      // Surface favorisant progression

        // Métriques spécialisées
        'constance_resultats',      // Score constance 1-100
        'resistance_baisse',        // Résistance aux baisses de classement
        'capacite_rebond',          // Capacité à rebondir après baisse
        'efficacite_progression',   // Efficacité de la progression
        'durabilite_position',      // Durabilité de la position actuelle

        // Objectifs et benchmarks
        'objectif_classement',      // Objectif classement du joueur
        'distance_objectif',        // Distance en places vers objectif
        'temps_estime_objectif',    // Temps estimé pour atteindre objectif
        'probabilite_objectif',     // Probabilité d'atteindre objectif
        'plan_progression',         // Plan de progression recommandé

        // Facteurs externes
        'impact_blessures',         // Impact blessures sur classement
        'impact_pause_carriere',    // Impact pause/sabbatique
        'impact_changement_coach',  // Impact changement entraîneur
        'impact_conditions_voyage', // Impact déplacements/voyages
        'facteurs_personnels',      // JSON facteurs personnels

        // Données historiques enrichies
        'classement_meme_periode_annee_precedente',
        'evolution_vs_annee_precedente',
        'record_personnel_cette_semaine', // Record personnel atteint cette semaine
        'pic_forme_periode',        // Pic de forme sur la période
        'creux_forme_periode',      // Creux de forme sur la période

        // Métriques financières liées
        'prize_money_cumule',       // Prize money cumulé à cette date
        'prize_money_evolution',    // Évolution prize money
        'valeur_market_estimee',    // Valeur marchande estimée
        'evolution_valeur_market',  // Évolution valeur marchande
        'impact_sponsoring',        // Impact sur contrats sponsoring

        // Analyse régionale et voyages
        'classement_surface_dominante', // Classement sur surface favorite
        'performance_domicile',     // Performance matchs à domicile
        'performance_deplacement',  // Performance en déplacement
        'adaptation_fuseaux',       // Adaptation aux fuseaux horaires
        'preference_geographique',  // Préférence géographique

        // Données d'entraînement IA
        'features_ia',              // JSON features pour IA
        'target_progression',       // Target pour modèles prédictifs
        'poids_echantillon',        // Poids de cet échantillon pour IA
        'cluster_joueur',           // Cluster de joueurs similaires
        'pattern_evolution',        // Pattern d'évolution détecté

        // Validation et qualité
        'source_donnees',           // Source des données classement
        'fiabilite_donnees',        // Score fiabilité 1-100
        'derniere_verification',    // Date dernière vérification
        'anomalie_detectee',        // Boolean anomalie détectée
        'correction_appliquee',     // Boolean correction appliquée
        'validee_officiellement',   // Boolean validation officielle

        // Métadonnées système
        'version_algorithme',       // Version algorithme de calcul
        'timestamp_calcul',         // Timestamp du calcul
        'duree_calcul_ms',          // Durée calcul en millisecondes
        'hash_donnees',             // Hash des données pour intégrité
        'import_donnees_id',        // Lien vers l'import ayant créé cet enregistrement

        // Notes et commentaires
        'notes_automatiques',       // Notes générées automatiquement
        'commentaires_analystes',   // Commentaires analystes
        'alertes_generees',         // JSON alertes générées
        'notifications_envoyees',    // JSON notifications envoyées
    ];

    protected $casts = [
        // Dates
        'date_classement' => 'date',
        'derniere_verification' => 'datetime',
        'timestamp_calcul' => 'datetime',

        // Entiers - Classements
        'semaine_atp' => 'integer',
        'annee' => 'integer',
        'classement_actuel' => 'integer',
        'classement_precedent' => 'integer',
        'classement_il_y_a_4_semaines' => 'integer',
        'classement_il_y_a_12_semaines' => 'integer',
        'classement_debut_annee' => 'integer',
        'meilleur_classement_annee' => 'integer',
        'pire_classement_annee' => 'integer',
        'meilleur_classement_carriere' => 'integer',
        'classement_pays' => 'integer',
        'classement_zone' => 'integer',
        'classement_predit_4_semaines' => 'integer',
        'classement_predit_12_semaines' => 'integer',
        'classement_predit_fin_annee' => 'integer',
        'classement_cohorte' => 'integer',
        'classement_meme_periode_annee_precedente' => 'integer',

        // Entiers - Points et évolutions
        'points_actuels' => 'integer',
        'points_precedents' => 'integer',
        'points_gagnes_semaine' => 'integer',
        'points_perdus_semaine' => 'integer',
        'points_evolution' => 'integer',
        'points_a_defendre' => 'integer',
        'points_race' => 'integer',
        'points_race_precedents' => 'integer',
        'points_tournoi_impact' => 'integer',
        'evolution_position' => 'integer',
        'streak_hausse' => 'integer',
        'streak_baisse' => 'integer',
        'ecart_top_1' => 'integer',
        'ecart_top_10' => 'integer',
        'ecart_top_50' => 'integer',
        'ecart_top_100' => 'integer',
        'places_vers_top_10' => 'integer',
        'places_vers_top_50' => 'integer',
        'objectif_classement' => 'integer',
        'distance_objectif' => 'integer',
        'temps_estime_objectif' => 'integer',
        'projection_points_fin_annee' => 'integer',
        'prize_money_cumule' => 'integer',
        'prize_money_evolution' => 'integer',
        'duree_calcul_ms' => 'integer',

        // Décimaux - ELO et ratings
        'elo_rating' => 'decimal:1',
        'elo_precedent' => 'decimal:1',
        'elo_evolution' => 'decimal:1',
        'elo_max_carriere' => 'decimal:1',
        'elo_surface_dur' => 'decimal:1',
        'elo_surface_terre' => 'decimal:1',
        'elo_surface_gazon' => 'decimal:1',
        'elo_surface_indoor' => 'decimal:1',
        'rating_alternatif' => 'decimal:1',
        'rating_live' => 'decimal:1',

        // Décimaux - Pourcentages et scores
        'evolution_pourcentage' => 'decimal:2',
        'volatilite_score' => 'decimal:1',
        'stabilite_score' => 'decimal:1',
        'pourcentage_vs_top_1' => 'decimal:2',
        'probabilite_top_10' => 'decimal:2',
        'probabilite_top_50' => 'decimal:2',
        'confiance_prediction' => 'decimal:1',
        'forme_recent' => 'decimal:1',
        'momentum' => 'decimal:1',
        'progression_vitesse' => 'decimal:2',
        'acceleration' => 'decimal:2',
        'potentiel_restant' => 'decimal:1',
        'performance_vs_attente' => 'decimal:1',
        'facteur_surprise' => 'decimal:1',
        'constance_resultats' => 'decimal:1',
        'resistance_baisse' => 'decimal:1',
        'capacite_rebond' => 'decimal:1',
        'efficacite_progression' => 'decimal:1',
        'durabilite_position' => 'decimal:1',
        'probabilite_objectif' => 'decimal:2',
        'impact_blessures' => 'decimal:1',
        'evolution_vs_annee_precedente' => 'decimal:2',
        'valeur_market_estimee' => 'decimal:2',
        'evolution_valeur_market' => 'decimal:2',
        'performance_domicile' => 'decimal:1',
        'performance_deplacement' => 'decimal:1',
        'adaptation_fuseaux' => 'decimal:1',
        'poids_echantillon' => 'decimal:3',
        'fiabilite_donnees' => 'decimal:1',

        // Booléens
        'record_personnel_cette_semaine' => 'boolean',
        'anomalie_detectee' => 'boolean',
        'correction_appliquee' => 'boolean',
        'validee_officiellement' => 'boolean',

        // JSON
        'adversaires_battus_semaine' => 'json',
        'defaites_semaine' => 'json',
        'facteurs_prediction' => 'json',
        'rivaux_directs' => 'json',
        'menaces_montantes' => 'json',
        'opportunites_progression' => 'json',
        'facteurs_personnels' => 'json',
        'features_ia' => 'json',
        'alertes_generees' => 'json',
        'notifications_envoyees' => 'json',
    ];

    protected $appends = [
        'evolution_humanized',
        'tendance_globale',
        'position_relative',
        'momentum_description',
        'niveau_classement',
        'progression_annee',
        'potentiel_progression',
        'facteurs_cles_evolution',
        'prediction_next_month',
        'zones_opportunite',
        'alertes_importantes',
        'resume_evolution',
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function joueur()
    {
        return $this->belongsTo(Joueur::class);
    }

    public function tournoiImpact()
    {
        return $this->belongsTo(Tournoi::class, 'tournoi_impact_id');
    }

    public function importDonnees()
    {
        return $this->belongsTo(ImportDonnees::class, 'import_donnees_id');
    }

    public function historiquePrecedent()
    {
        return $this->hasOne(HistoriqueClassement::class, 'joueur_id', 'joueur_id')
            ->where('date_classement', '<', $this->date_classement)
            ->where('type_classement', $this->type_classement)
            ->orderBy('date_classement', 'desc');
    }

    public function historiqueSuivant()
    {
        return $this->hasOne(HistoriqueClassement::class, 'joueur_id', 'joueur_id')
            ->where('date_classement', '>', $this->date_classement)
            ->where('type_classement', $this->type_classement)
            ->orderBy('date_classement', 'asc');
    }

    public function comparaisonsCohorte()
    {
        return $this->hasMany(HistoriqueClassement::class, 'date_classement', 'date_classement')
            ->where('type_classement', $this->type_classement)
            ->whereHas('joueur', function ($q) {
                $ageMin = $this->joueur->age - 2;
                $ageMax = $this->joueur->age + 2;
                $q->whereRaw('YEAR(CURDATE()) - YEAR(date_naissance) BETWEEN ? AND ?', [$ageMin, $ageMax]);
            });
    }

    // ===================================================================
    // SCOPES
    // ===================================================================

    public function scopeParJoueur($query, $joueurId)
    {
        return $query->where('joueur_id', $joueurId);
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type_classement', $type);
    }

    public function scopeATP($query)
    {
        return $query->where('type_classement', 'atp');
    }

    public function scopeWTA($query)
    {
        return $query->where('type_classement', 'wta');
    }

    public function scopeELO($query)
    {
        return $query->whereIn('type_classement', ['elo_global', 'elo_surface']);
    }

    public function scopeAnneeActuelle($query)
    {
        return $query->where('annee', now()->year);
    }

    public function scopePeriode($query, $debut, $fin)
    {
        return $query->whereBetween('date_classement', [$debut, $fin]);
    }

    public function scopeDernieresSemaines($query, $nb = 4)
    {
        return $query->where('date_classement', '>=', now()->subWeeks($nb));
    }

    public function scopeTop10($query)
    {
        return $query->where('classement_actuel', '<=', 10);
    }

    public function scopeTop50($query)
    {
        return $query->where('classement_actuel', '<=', 50);
    }

    public function scopeTop100($query)
    {
        return $query->where('classement_actuel', '<=', 100);
    }

    public function scopeEnProgression($query)
    {
        return $query->where('evolution_position', '>', 0);
    }

    public function scopeEnBaisse($query)
    {
        return $query->where('evolution_position', '<', 0);
    }

    public function scopeForteProgression($query)
    {
        return $query->where('evolution_position', '>', 10);
    }

    public function scopeForteChute($query)
    {
        return $query->where('evolution_position', '<', -10);
    }

    public function scopeRecordPersonnel($query)
    {
        return $query->where('record_personnel_cette_semaine', true);
    }

    public function scopeMomentumPositif($query)
    {
        return $query->where('momentum', '>=', 70);
    }

    public function scopeValidees($query)
    {
        return $query->where('validee_officiellement', true);
    }

    public function scopeAvecAnomalies($query)
    {
        return $query->where('anomalie_detectee', true);
    }

    public function scopeParSurface($query, $surface)
    {
        return $query->where('surface_specifique', $surface);
    }

    // ===================================================================
    // ACCESSORS INTELLIGENTS
    // ===================================================================

    public function getEvolutionHumanizedAttribute()
    {
        $evolution = $this->evolution_position ?? 0;

        if ($evolution > 50) {
            return "📈 Progression spectaculaire (+{$evolution} places)";
        }
        if ($evolution > 20) {
            return "🚀 Forte progression (+{$evolution} places)";
        }
        if ($evolution > 5) {
            return "⬆️ Progression (+{$evolution} places)";
        }
        if ($evolution > 0) {
            return "↗️ Légère progression (+{$evolution} places)";
        }
        if ($evolution == 0) {
            return '➡️ Classement stable';
        }
        if ($evolution > -5) {
            return "↘️ Légère baisse ({$evolution} places)";
        }
        if ($evolution > -20) {
            return "⬇️ Baisse ({$evolution} places)";
        }
        if ($evolution > -50) {
            return "📉 Forte baisse ({$evolution} places)";
        }

        return "💥 Chute spectaculaire ({$evolution} places)";
    }

    public function getTendanceGlobaleAttribute()
    {
        $tendances = [
            $this->tendance_4_semaines,
            $this->tendance_12_semaines,
            $this->tendance_annee,
        ];

        $positives = count(array_filter($tendances, fn ($t) => $t === 'hausse'));
        $negatives = count(array_filter($tendances, fn ($t) => $t === 'baisse'));

        if ($positives >= 2) {
            return 'progression_confirmee';
        }
        if ($negatives >= 2) {
            return 'declin_confirme';
        }

        return 'evolution_mixte';
    }

    public function getPositionRelativeAttribute()
    {
        $classement = $this->classement_actuel;

        if (! $classement) {
            return 'non_classe';
        }
        if ($classement <= 3) {
            return 'elite_mondiale';
        }
        if ($classement <= 10) {
            return 'top_10';
        }
        if ($classement <= 20) {
            return 'top_20';
        }
        if ($classement <= 50) {
            return 'top_50';
        }
        if ($classement <= 100) {
            return 'top_100';
        }
        if ($classement <= 300) {
            return 'professionnel';
        }

        return 'challenger';
    }

    public function getMomentumDescriptionAttribute()
    {
        $momentum = $this->momentum ?? 50;

        if ($momentum >= 90) {
            return 'momentum_exceptionnel';
        }
        if ($momentum >= 80) {
            return 'momentum_excellent';
        }
        if ($momentum >= 70) {
            return 'momentum_bon';
        }
        if ($momentum >= 60) {
            return 'momentum_positif';
        }
        if ($momentum >= 40) {
            return 'momentum_neutre';
        }
        if ($momentum >= 30) {
            return 'momentum_negatif';
        }

        return 'momentum_tres_negatif';
    }

    public function getNiveauClassementAttribute()
    {
        $type = $this->type_classement;
        $classement = $this->classement_actuel;

        if (! $classement) {
            return 'Non classé';
        }

        switch ($type) {
            case 'atp':
            case 'wta':
                if ($classement <= 3) {
                    return 'Légende du tennis';
                }
                if ($classement <= 10) {
                    return 'Elite mondiale';
                }
                if ($classement <= 20) {
                    return 'Très haut niveau';
                }
                if ($classement <= 50) {
                    return 'Haut niveau';
                }
                if ($classement <= 100) {
                    return 'Professionnel confirmé';
                }
                if ($classement <= 300) {
                    return 'Professionnel';
                }

                return 'Challenger';

            case 'elo_global':
                if ($this->elo_rating >= 2400) {
                    return 'Elite ELO';
                }
                if ($this->elo_rating >= 2200) {
                    return 'Très fort ELO';
                }
                if ($this->elo_rating >= 2000) {
                    return 'Bon niveau ELO';
                }
                if ($this->elo_rating >= 1800) {
                    return 'Niveau moyen ELO';
                }

                return 'Niveau débutant ELO';

            default:
                return "Classé #{$classement}";
        }
    }

    public function getProgressionAnneeAttribute()
    {
        $debut = $this->classement_debut_annee ?? $this->classement_actuel;
        $actuel = $this->classement_actuel;

        if (! $debut || ! $actuel) {
            return 0;
        }

        return $debut - $actuel; // Positif = progression
    }

    public function getPotentielProgressionAttribute()
    {
        $facteurs = [
            'age' => $this->calculerFacteurAge(),
            'momentum' => ($this->momentum ?? 50) / 100,
            'forme' => ($this->forme_recent ?? 50) / 100,
            'constance' => ($this->constance_resultats ?? 50) / 100,
            'potentiel_restant' => ($this->potentiel_restant ?? 50) / 100,
        ];

        return round(array_sum($facteurs) / count($facteurs) * 100, 1);
    }

    public function getFacteursClesEvolutionAttribute()
    {
        $facteurs = [];

        // Facteurs positifs
        if (($this->momentum ?? 0) >= 70) {
            $facteurs[] = 'Momentum positif';
        }
        if (($this->forme_recent ?? 0) >= 70) {
            $facteurs[] = 'Forme excellente';
        }
        if (($this->constance_resultats ?? 0) >= 70) {
            $facteurs[] = 'Constance résultats';
        }
        if ($this->evolution_position > 0) {
            $facteurs[] = 'Progression récente';
        }

        // Facteurs négatifs
        if (($this->impact_blessures ?? 0) >= 5) {
            $facteurs[] = 'Impact blessures';
        }
        if ($this->evolution_position < -10) {
            $facteurs[] = 'Baisse importante';
        }
        if (($this->volatilite_score ?? 0) >= 7) {
            $facteurs[] = 'Instabilité résultats';
        }

        return $facteurs;
    }

    public function getPredictionNextMonthAttribute()
    {
        return [
            'classement_predit' => $this->classement_predit_4_semaines,
            'evolution_prevue' => ($this->classement_actuel ?? 0) - ($this->classement_predit_4_semaines ?? 0),
            'confiance' => $this->confiance_prediction ?? 50,
            'facteurs' => $this->facteurs_prediction ?? [],
        ];
    }

    public function getZonesOpportuniteAttribute()
    {
        $zones = [];

        if (($this->probabilite_top_10 ?? 0) >= 20) {
            $zones[] = 'Potentiel Top 10';
        }

        if (($this->probabilite_top_50 ?? 0) >= 50) {
            $zones[] = 'Progression vers Top 50';
        }

        if (($this->surface_favorisante ?? '') !== '') {
            $zones[] = "Opportunité surface {$this->surface_favorisante}";
        }

        return $zones;
    }

    public function getAlertesImportantesAttribute()
    {
        $alertes = [];

        if ($this->anomalie_detectee) {
            $alertes[] = ['type' => 'anomalie', 'message' => 'Anomalie détectée dans les données'];
        }

        if (($this->evolution_position ?? 0) < -50) {
            $alertes[] = ['type' => 'chute', 'message' => 'Chute importante du classement'];
        }

        if ($this->record_personnel_cette_semaine) {
            $alertes[] = ['type' => 'record', 'message' => 'Record personnel atteint !'];
        }

        if (($this->points_a_defendre ?? 0) > 1000) {
            $alertes[] = ['type' => 'points_a_defendre', 'message' => 'Nombreux points à défendre'];
        }

        return $alertes;
    }

    public function getResumeEvolutionAttribute()
    {
        return [
            'classement_actuel' => $this->classement_actuel,
            'evolution' => $this->evolution_humanized,
            'niveau' => $this->niveau_classement,
            'tendance' => $this->tendance_globale,
            'momentum' => $this->momentum_description,
            'points' => number_format($this->points_actuels),
            'progression_annee' => $this->progression_annee,
            'potentiel' => $this->potentiel_progression.'%',
            'prediction_1_mois' => $this->prediction_next_month,
            'facteurs_cles' => $this->facteurs_cles_evolution,
            'alertes' => $this->alertes_importantes,
        ];
    }

    // ===================================================================
    // METHODS PRINCIPALES
    // ===================================================================

    /**
     * Calculer l'évolution depuis la période précédente
     */
    public function calculerEvolution()
    {
        $precedent = $this->historiquePrecedent;
        if (! $precedent) {
            return $this;
        }

        $this->evolution_position = ($precedent->classement_actuel ?? 0) - ($this->classement_actuel ?? 0);
        $this->points_evolution = ($this->points_actuels ?? 0) - ($precedent->points_actuels ?? 0);
        $this->elo_evolution = ($this->elo_rating ?? 0) - ($precedent->elo_rating ?? 0);

        if ($precedent->points_actuels > 0) {
            $this->evolution_pourcentage = (($this->points_evolution ?? 0) / $precedent->points_actuels) * 100;
        }

        $this->save();

        return $this;
    }

    /**
     * Calculer les tendances sur différentes périodes
     */
    public function calculerTendances()
    {
        // Tendance 4 semaines
        $hist4Sem = HistoriqueClassement::where('joueur_id', $this->joueur_id)
            ->where('type_classement', $this->type_classement)
            ->where('date_classement', '<=', $this->date_classement->subWeeks(4))
            ->orderBy('date_classement', 'desc')
            ->first();

        if ($hist4Sem) {
            $diff4 = ($hist4Sem->classement_actuel ?? 0) - ($this->classement_actuel ?? 0);
            $this->tendance_4_semaines = $diff4 > 5 ? 'hausse' : ($diff4 < -5 ? 'baisse' : 'stable');
        }

        // Tendance 12 semaines
        $hist12Sem = HistoriqueClassement::where('joueur_id', $this->joueur_id)
            ->where('type_classement', $this->type_classement)
            ->where('date_classement', '<=', $this->date_classement->subWeeks(12))
            ->orderBy('date_classement', 'desc')
            ->first();

        if ($hist12Sem) {
            $diff12 = ($hist12Sem->classement_actuel ?? 0) - ($this->classement_actuel ?? 0);
            $this->tendance_12_semaines = $diff12 > 10 ? 'hausse' : ($diff12 < -10 ? 'baisse' : 'stable');
        }

        // Tendance année
        $diffAnnee = ($this->classement_debut_annee ?? 0) - ($this->classement_actuel ?? 0);
        $this->tendance_annee = $diffAnnee > 20 ? 'hausse' : ($diffAnnee < -20 ? 'baisse' : 'stable');

        $this->save();

        return $this;
    }

    /**
     * Calculer les écarts avec les benchmarks
     */
    public function calculerEcartsBenchmarks()
    {
        $dateClassement = $this->date_classement;
        $typeClassement = $this->type_classement;

        // Récupérer les points du top 1, 10, 50, 100
        $benchmarks = HistoriqueClassement::where('date_classement', $dateClassement)
            ->where('type_classement', $typeClassement)
            ->whereIn('classement_actuel', [1, 10, 50, 100])
            ->pluck('points_actuels', 'classement_actuel')
            ->toArray();

        if (isset($benchmarks[1])) {
            $this->ecart_top_1 = $benchmarks[1] - ($this->points_actuels ?? 0);
            $this->pourcentage_vs_top_1 = ($this->points_actuels ?? 0) / $benchmarks[1] * 100;
        }

        if (isset($benchmarks[10])) {
            $this->ecart_top_10 = $benchmarks[10] - ($this->points_actuels ?? 0);
        }

        if (isset($benchmarks[50])) {
            $this->ecart_top_50 = $benchmarks[50] - ($this->points_actuels ?? 0);
        }

        if (isset($benchmarks[100])) {
            $this->ecart_top_100 = $benchmarks[100] - ($this->points_actuels ?? 0);
        }

        $this->save();

        return $this;
    }

    /**
     * Calculer les scores de momentum et forme
     */
    public function calculerScoresPerformance()
    {
        // Momentum basé sur évolution récente
        $facteursMomentum = [
            'evolution_4_sem' => $this->calculerScoreEvolution4Semaines(),
            'constance' => $this->constance_resultats ?? 50,
            'progression_vitesse' => min(100, ($this->progression_vitesse ?? 0) * 50),
            'points_evolution' => min(100, max(0, 50 + (($this->points_evolution ?? 0) / 100))),
        ];

        $this->momentum = round(array_sum($facteursMomentum) / count($facteursMomentum), 1);

        // Forme récente
        $this->forme_recent = $this->calculerFormeRecente();

        // Volatilité et stabilité
        $this->volatilite_score = $this->calculerVolatilite();
        $this->stabilite_score = 100 - $this->volatilite_score;

        $this->save();

        return $this;
    }

    /**
     * Prédire l'évolution future
     */
    public function predireEvolution()
    {
        // Utiliser les facteurs pour prédiction IA
        $features = [
            'momentum' => $this->momentum ?? 50,
            'forme' => $this->forme_recent ?? 50,
            'age_joueur' => $this->joueur->age ?? 25,
            'evolution_recente' => $this->evolution_position ?? 0,
            'constance' => $this->constance_resultats ?? 50,
            'points_actuels' => $this->points_actuels ?? 0,
            'surface_favorable' => $this->surface_favorisante === $this->joueur->surface_favorite ? 1 : 0,
        ];

        // Algorithme simplifié de prédiction
        $score_prediction = ($features['momentum'] + $features['forme'] + $features['constance']) / 3;

        // Ajustement selon l'âge
        if ($features['age_joueur'] < 25) {
            $score_prediction *= 1.1;
        }
        if ($features['age_joueur'] > 30) {
            $score_prediction *= 0.9;
        }

        // Prédiction classement 4 semaines
        $evolution_prevue = ($score_prediction - 50) * 0.5; // Facteur conservateur
        $this->classement_predit_4_semaines = max(1, ($this->classement_actuel ?? 100) - $evolution_prevue);

        // Prédiction 12 semaines
        $this->classement_predit_12_semaines = max(1, ($this->classement_actuel ?? 100) - ($evolution_prevue * 2));

        // Confiance prédiction
        $this->confiance_prediction = min(100, $this->stabilite_score + 20);

        // Probabilités top 10/50
        $this->calculerProbabilitesProgression();

        $this->features_ia = $features;
        $this->save();

        return $this;
    }

    /**
     * Analyser la comparaison avec la cohorte d'âge
     */
    public function analyserCohorte()
    {
        $age = $this->joueur->age;
        $cohorte = HistoriqueClassement::where('date_classement', $this->date_classement)
            ->where('type_classement', $this->type_classement)
            ->whereHas('joueur', function ($q) use ($age) {
                $q->whereRaw('YEAR(CURDATE()) - YEAR(date_naissance) BETWEEN ? AND ?', [$age - 2, $age + 2]);
            })
            ->orderBy('classement_actuel')
            ->get();

        $position = $cohorte->search(function ($item) {
            return $item->joueur_id === $this->joueur_id;
        });

        $this->classement_cohorte = $position !== false ? $position + 1 : null;

        $this->save();

        return $this;
    }

    /**
     * Détecter les anomalies dans les données
     */
    public function detecterAnomalies()
    {
        $anomalies = [];

        // Évolution trop brutale
        if (abs($this->evolution_position ?? 0) > 200) {
            $anomalies[] = 'evolution_brutale';
        }

        // Points incohérents avec classement
        if ($this->classement_actuel && $this->points_actuels) {
            $precedent = $this->historiquePrecedent;
            if ($precedent && abs(($this->points_actuels - $precedent->points_actuels) / max(1, $precedent->points_actuels)) > 0.5) {
                $anomalies[] = 'points_incoherents';
            }
        }

        // ELO incohérent
        if ($this->elo_rating && ($this->elo_rating < 1000 || $this->elo_rating > 3000)) {
            $anomalies[] = 'elo_incoherent';
        }

        $this->anomalie_detectee = ! empty($anomalies);
        $this->alertes_generees = $anomalies;

        $this->save();

        return $this;
    }

    /**
     * Générer insights complets pour l'IA
     */
    public function genererInsightsIA()
    {
        return [
            'identifiant' => $this->id,
            'joueur' => [
                'id' => $this->joueur_id,
                'nom' => $this->joueur->nom_complet,
                'age' => $this->joueur->age,
            ],
            'classement' => [
                'actuel' => $this->classement_actuel,
                'evolution' => $this->evolution_position,
                'niveau' => $this->niveau_classement,
                'position_relative' => $this->position_relative,
            ],
            'tendances' => [
                'court_terme' => $this->tendance_4_semaines,
                'moyen_terme' => $this->tendance_12_semaines,
                'long_terme' => $this->tendance_annee,
                'globale' => $this->tendance_globale,
            ],
            'performance' => [
                'momentum' => $this->momentum,
                'forme' => $this->forme_recent,
                'constance' => $this->constance_resultats,
                'stabilite' => $this->stabilite_score,
                'potentiel' => $this->potentiel_progression,
            ],
            'predictions' => [
                '1_mois' => $this->prediction_next_month,
                'probabilite_top_10' => $this->probabilite_top_10,
                'probabilite_top_50' => $this->probabilite_top_50,
                'confiance' => $this->confiance_prediction,
            ],
            'contexte' => [
                'facteurs_cles' => $this->facteurs_cles_evolution,
                'zones_opportunite' => $this->zones_opportunite,
                'alertes' => $this->alertes_importantes,
            ],
            'donnees_ia' => [
                'features' => $this->features_ia,
                'cluster' => $this->cluster_joueur,
                'pattern' => $this->pattern_evolution,
                'poids' => $this->poids_echantillon,
            ],
            'qualite' => [
                'fiabilite' => $this->fiabilite_donnees,
                'anomalie' => $this->anomalie_detectee,
                'validee' => $this->validee_officiellement,
            ],
        ];
    }

    // ===================================================================
    // METHODS PRIVÉES DE CALCUL
    // ===================================================================

    private function calculerFacteurAge()
    {
        $age = $this->joueur->age ?? 25;

        if ($age < 20) {
            return 0.9;
        } // Très jeune, potentiel élevé mais instable
        if ($age < 25) {
            return 1.0;
        } // Age optimal pour progression
        if ($age < 30) {
            return 0.8;
        } // Maturité mais progression plus lente
        if ($age < 33) {
            return 0.6;
        } // Maintien niveau plus que progression

        return 0.4; // Déclin probable
    }

    private function calculerScoreEvolution4Semaines()
    {
        $evolution = $this->evolution_position ?? 0;

        if ($evolution > 20) {
            return 90;
        }
        if ($evolution > 10) {
            return 80;
        }
        if ($evolution > 5) {
            return 70;
        }
        if ($evolution > 0) {
            return 60;
        }
        if ($evolution == 0) {
            return 50;
        }
        if ($evolution > -5) {
            return 40;
        }
        if ($evolution > -10) {
            return 30;
        }
        if ($evolution > -20) {
            return 20;
        }

        return 10;
    }

    private function calculerFormeRecente()
    {
        // Simplification - à adapter selon les données disponibles
        $facteurs = [
            'momentum' => $this->momentum ?? 50,
            'evolution' => $this->calculerScoreEvolution4Semaines(),
            'constance' => $this->constance_resultats ?? 50,
        ];

        return round(array_sum($facteurs) / count($facteurs), 1);
    }

    private function calculerVolatilite()
    {
        // Calcul basé sur les variations des 12 dernières semaines
        $historiques = HistoriqueClassement::where('joueur_id', $this->joueur_id)
            ->where('type_classement', $this->type_classement)
            ->where('date_classement', '<=', $this->date_classement)
            ->where('date_classement', '>=', $this->date_classement->copy()->subWeeks(12))
            ->orderBy('date_classement')
            ->pluck('classement_actuel')
            ->toArray();

        if (count($historiques) < 3) {
            return 50;
        }

        $variations = [];
        for ($i = 1; $i < count($historiques); $i++) {
            $variations[] = abs($historiques[$i] - $historiques[$i - 1]);
        }

        $variationMoyenne = array_sum($variations) / count($variations);

        return min(100, $variationMoyenne * 2); // Facteur d'échelle
    }

    private function calculerProbabilitesProgression()
    {
        $facteurs = [
            'momentum' => $this->momentum ?? 50,
            'age' => $this->calculerFacteurAge() * 100,
            'forme' => $this->forme_recent ?? 50,
            'potentiel' => $this->potentiel_restant ?? 50,
        ];

        $scoreGlobal = array_sum($facteurs) / count($facteurs);

        // Probabilité top 10
        if (($this->classement_actuel ?? 200) <= 20) {
            $this->probabilite_top_10 = min(95, $scoreGlobal * 1.2);
        } elseif (($this->classement_actuel ?? 200) <= 50) {
            $this->probabilite_top_10 = min(80, $scoreGlobal);
        } else {
            $this->probabilite_top_10 = min(50, $scoreGlobal * 0.6);
        }

        // Probabilité top 50
        if (($this->classement_actuel ?? 200) <= 100) {
            $this->probabilite_top_50 = min(95, $scoreGlobal * 1.1);
        } else {
            $this->probabilite_top_50 = min(70, $scoreGlobal * 0.8);
        }
    }

    // ===================================================================
    // STATIC METHODS
    // ===================================================================

    /**
     * Obtenir l'évolution d'un joueur sur une période
     */
    public static function getEvolutionJoueur($joueurId, $type = 'atp', $periodeDebut = null, $periodeFin = null)
    {
        $query = static::where('joueur_id', $joueurId)
            ->where('type_classement', $type)
            ->orderBy('date_classement');

        if ($periodeDebut) {
            $query->where('date_classement', '>=', $periodeDebut);
        }
        if ($periodeFin) {
            $query->where('date_classement', '<=', $periodeFin);
        }

        return $query->get();
    }

    /**
     * Obtenir le top des progressions d'une semaine
     */
    public static function getTopProgressions($date = null, $type = 'atp', $limite = 10)
    {
        $date = $date ?? now();

        return static::where('date_classement', $date)
            ->where('type_classement', $type)
            ->where('evolution_position', '>', 0)
            ->orderBy('evolution_position', 'desc')
            ->limit($limite)
            ->with('joueur')
            ->get();
    }

    /**
     * Obtenir les records personnels d'une semaine
     */
    public static function getRecordsPersonnels($date = null, $type = 'atp')
    {
        $date = $date ?? now();

        return static::where('date_classement', $date)
            ->where('type_classement', $type)
            ->where('record_personnel_cette_semaine', true)
            ->with('joueur')
            ->get();
    }

    // ===================================================================
    // VALIDATION RULES
    // ===================================================================

    public static function validationRules()
    {
        return [
            'joueur_id' => 'required|exists:joueurs,id',
            'date_classement' => 'required|date',
            'type_classement' => 'required|in:atp,wta,itf,elo_global,elo_surface,national,junior',
            'classement_actuel' => 'nullable|integer|min:1',
            'points_actuels' => 'nullable|integer|min:0',
            'elo_rating' => 'nullable|numeric|between:800,3000',
        ];
    }

    // ===================================================================
    // BOOT METHODS
    // ===================================================================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($historique) {
            $historique->annee = $historique->annee ?? $historique->date_classement->year;
            $historique->semaine_atp = $historique->semaine_atp ?? $historique->date_classement->weekOfYear;
            $historique->timestamp_calcul = now();
        });

        static::saved(function ($historique) {
            // Calculs automatiques après sauvegarde
            $historique->calculerEvolution();
            $historique->calculerTendances();
            $historique->calculerEcartsBenchmarks();
            $historique->calculerScoresPerformance();
            $historique->detecterAnomalies();

            // Prédictions IA
            $historique->predireEvolution();
            $historique->analyserCohorte();
        });
    }
}
