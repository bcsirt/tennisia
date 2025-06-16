<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatistiqueMatch extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'statistiques_match';

    protected $fillable = [
        // Identification
        'match_tennis_id',
        'joueur_id',
        'set_numero',              // 1, 2, 3, 4, 5 (null = total match)
        'position_match',          // 'joueur1', 'joueur2'
        'statut_joueur',          // 'gagnant', 'perdant'

        // ===== STATISTIQUES SERVICE =====

        // Aces et fautes
        'aces',
        'aces_set1', 'aces_set2', 'aces_set3', 'aces_set4', 'aces_set5',
        'double_fautes',
        'double_fautes_set1', 'double_fautes_set2', 'double_fautes_set3', 'double_fautes_set4', 'double_fautes_set5',

        // Première balle
        'premieres_balles_tentees',
        'premieres_balles_reussies',
        'pourcentage_premiere_balle',
        'points_gagnes_premiere_balle',
        'points_joues_premiere_balle',
        'pourcentage_points_premiere_balle',

        // Deuxième balle
        'points_gagnes_deuxieme_balle',
        'points_joues_deuxieme_balle',
        'pourcentage_points_deuxieme_balle',

        // Service général
        'points_service_gagnes',
        'points_service_joues',
        'pourcentage_service',
        'jeux_service_gagnes',
        'jeux_service_joues',
        'pourcentage_jeux_service',

        // Vitesse et puissance service
        'vitesse_service_max',
        'vitesse_service_moyenne',
        'vitesse_premiere_balle_moyenne',
        'vitesse_deuxieme_balle_moyenne',
        'vitesse_aces_moyenne',

        // Placement service
        'services_centre',
        'services_exterieur',
        'services_corps',
        'services_gagnants',
        'services_attaques',

        // ===== STATISTIQUES RETOUR =====

        // Retour première balle
        'points_retour_premiere_balle_gagnes',
        'points_retour_premiere_balle_joues',
        'pourcentage_retour_premiere_balle',

        // Retour deuxième balle
        'points_retour_deuxieme_balle_gagnes',
        'points_retour_deuxieme_balle_joues',
        'pourcentage_retour_deuxieme_balle',

        // Retour général
        'points_retour_gagnes',
        'points_retour_joues',
        'pourcentage_retour',
        'jeux_retour_gagnes',
        'jeux_retour_joues',
        'retours_gagnants',
        'erreurs_retour',

        // ===== BREAK POINTS =====

        'break_points_sauves',
        'break_points_affrontes',
        'pourcentage_break_points_sauves',
        'break_points_convertis',
        'break_points_obtenus',
        'pourcentage_break_points_convertis',
        'breaks_realises',
        'breaks_concedes',

        // ===== JEU GENERAL =====

        // Points et jeux
        'points_gagnes',
        'points_joues',
        'pourcentage_points_gagnes',
        'jeux_gagnes',
        'jeux_joues',
        'pourcentage_jeux_gagnes',

        // Coups gagnants et erreurs
        'coups_gagnants',
        'coups_gagnants_coup_droit',
        'coups_gagnants_revers',
        'coups_gagnants_service',
        'coups_gagnants_volley',
        'coups_gagnants_smash',
        'coups_gagnants_lob',

        'erreurs_directes',
        'erreurs_directes_coup_droit',
        'erreurs_directes_revers',
        'erreurs_directes_service',
        'erreurs_directes_volley',
        'erreurs_directes_smash',

        'ratio_gagnants_erreurs',

        // ===== JEU AU FILET =====

        'points_filet_gagnes',
        'points_filet_joues',
        'pourcentage_points_filet',
        'montees_filet',
        'volleys_gagnants',
        'volleys_erreurs',
        'smashes_gagnes',
        'smashes_erreurs',

        // ===== TIEBREAKS =====

        'tiebreaks_joues',
        'tiebreaks_gagnes',
        'pourcentage_tiebreaks',
        'points_tiebreak_gagnes',
        'points_tiebreak_joues',

        // ===== STATISTIQUES AVANCEES =====

        // Vitesse et puissance
        'vitesse_coup_droit_max',
        'vitesse_coup_droit_moyenne',
        'vitesse_revers_max',
        'vitesse_revers_moyenne',
        'vitesse_volley_moyenne',

        // Zones de jeu
        'points_gagnes_fond_court',
        'points_gagnes_mi_court',
        'points_gagnes_filet',
        'pourcentage_fond_court',
        'pourcentage_mi_court',

        // Physique et effort
        'distance_parcourue',        // mètres
        'vitesse_deplacement_max',   // km/h
        'vitesse_deplacement_moyenne',
        'calories_brulees',
        'frequence_cardiaque_max',
        'frequence_cardiaque_moyenne',

        // Temps et rythme
        'temps_entre_points_moyen',  // secondes
        'temps_au_service',          // secondes total
        'temps_entre_services',      // secondes moyen
        'points_rapides',            // < 4 coups
        'points_longs',              // > 9 coups
        'echanges_plus_10_coups',

        // ===== EFFICACITE PAR ZONE =====

        // Efficacité par direction (coup droit)
        'coups_droits_croises',
        'coups_droits_croises_gagnes',
        'coups_droits_longline',
        'coups_droits_longline_gagnes',
        'pourcentage_efficacite_coup_droit',

        // Efficacité par direction (revers)
        'revers_croises',
        'revers_croises_gagnes',
        'revers_longline',
        'revers_longline_gagnes',
        'pourcentage_efficacite_revers',

        // ===== PRESSION ET MOMENTS CLES =====

        // Moments décisifs
        'points_importants_gagnes',   // Break points, set points, match points
        'points_importants_joues',
        'pourcentage_points_importants',
        'match_points_sauves',
        'match_points_convertis',
        'set_points_sauves',
        'set_points_convertis',

        // Performance par set
        'premier_set_gagne',         // boolean
        'deuxieme_set_gagne',        // boolean
        'troisieme_set_gagne',       // boolean
        'quatrieme_set_gagne',       // boolean
        'cinquieme_set_gagne',       // boolean

        // ===== ADAPTATION ET TACTIQUE =====

        // Style de jeu observé
        'style_jeu_observe',         // 'agressif', 'defensif', 'varié'
        'position_court_moyenne',    // 'fond', 'mi_court', 'filet'
        'tendance_tactique',         // 'attaque', 'contre', 'construction'

        // Adaptations
        'changements_tactiques',     // Nombre de changements observés
        'efficacite_adaptations',    // Score 1-10
        'reaction_pression',         // Score 1-10

        // ===== CONDITIONS ET CONTEXTE =====

        // Impact conditions
        'performance_sous_pression', // Score 1-10
        'gestion_fatigue',          // Score 1-10
        'adaptation_conditions',     // Score 1-10
        'constance_niveau',         // Score 1-10

        // Momentum
        'points_consécutifs_max',
        'jeux_consécutifs_max',
        'comebacks_realises',        // Nombre de remontées
        'avances_perdues',          // Nombre d'avances perdues

        // ===== DONNEES BRUTES POUR IA =====

        // Séquences détaillées (JSON)
        'sequence_points',          // Détail point par point
        'pattern_service',          // Patterns de service observés
        'pattern_retour',           // Patterns de retour observés
        'zones_faiblesses',         // Zones de faiblesse détectées
        'zones_forces',             // Zones de force détectées

        // Métriques IA
        'score_dominance',          // Score de domination 0-100
        'score_agressivite',        // Score d'agressivité 0-100
        'score_regularite',         // Score de régularité 0-100
        'score_mental',             // Score mental 0-100
        'score_physique',           // Score physique 0-100
        'score_tactique',           // Score tactique 0-100

        // Prédicteurs performance
        'indicateur_forme',         // Score forme 0-100
        'indicateur_confiance',     // Score confiance 0-100
        'indicateur_motivation',    // Score motivation 0-100
        'facteur_ajustement_elo',   // Facteur ajustement ELO

        // ===== METADONNEES =====

        // Qualité des données
        'completude_donnees',       // % complétude des stats
        'fiabilite_donnees',        // Score fiabilité 0-100
        'source_statistiques',      // 'officiel', 'estimé', 'calculé'
        'methode_collecte',         // 'automatique', 'manuel', 'mixte'

        // Horodatage
        'derniere_maj_stats',
        'duree_collecte',           // millisecondes
        'version_algorithme',       // Version algorithme de calcul

        // Flags
        'stats_validees',           // Boolean validation
        'stats_completes',          // Boolean complétude
        'utilisable_pour_ia',       // Boolean utilisable IA
        'anomalie_detectee',         // Boolean anomalie
    ];

    protected $casts = [
        // Dates
        'derniere_maj_stats' => 'datetime',

        // Entiers - Service
        'aces' => 'integer',
        'aces_set1' => 'integer', 'aces_set2' => 'integer', 'aces_set3' => 'integer', 'aces_set4' => 'integer', 'aces_set5' => 'integer',
        'double_fautes' => 'integer',
        'double_fautes_set1' => 'integer', 'double_fautes_set2' => 'integer', 'double_fautes_set3' => 'integer', 'double_fautes_set4' => 'integer', 'double_fautes_set5' => 'integer',
        'premieres_balles_tentees' => 'integer',
        'premieres_balles_reussies' => 'integer',
        'points_gagnes_premiere_balle' => 'integer',
        'points_joues_premiere_balle' => 'integer',
        'points_gagnes_deuxieme_balle' => 'integer',
        'points_joues_deuxieme_balle' => 'integer',
        'points_service_gagnes' => 'integer',
        'points_service_joues' => 'integer',
        'jeux_service_gagnes' => 'integer',
        'jeux_service_joues' => 'integer',
        'services_centre' => 'integer',
        'services_exterieur' => 'integer',
        'services_corps' => 'integer',
        'services_gagnants' => 'integer',
        'services_attaques' => 'integer',

        // Entiers - Retour
        'points_retour_premiere_balle_gagnes' => 'integer',
        'points_retour_premiere_balle_joues' => 'integer',
        'points_retour_deuxieme_balle_gagnes' => 'integer',
        'points_retour_deuxieme_balle_joues' => 'integer',
        'points_retour_gagnes' => 'integer',
        'points_retour_joues' => 'integer',
        'jeux_retour_gagnes' => 'integer',
        'jeux_retour_joues' => 'integer',
        'retours_gagnants' => 'integer',
        'erreurs_retour' => 'integer',

        // Entiers - Break points
        'break_points_sauves' => 'integer',
        'break_points_affrontes' => 'integer',
        'break_points_convertis' => 'integer',
        'break_points_obtenus' => 'integer',
        'breaks_realises' => 'integer',
        'breaks_concedes' => 'integer',

        // Entiers - Jeu général
        'points_gagnes' => 'integer',
        'points_joues' => 'integer',
        'jeux_gagnes' => 'integer',
        'jeux_joues' => 'integer',
        'coups_gagnants' => 'integer',
        'coups_gagnants_coup_droit' => 'integer',
        'coups_gagnants_revers' => 'integer',
        'coups_gagnants_service' => 'integer',
        'coups_gagnants_volley' => 'integer',
        'coups_gagnants_smash' => 'integer',
        'coups_gagnants_lob' => 'integer',
        'erreurs_directes' => 'integer',
        'erreurs_directes_coup_droit' => 'integer',
        'erreurs_directes_revers' => 'integer',
        'erreurs_directes_service' => 'integer',
        'erreurs_directes_volley' => 'integer',
        'erreurs_directes_smash' => 'integer',

        // Entiers - Filet
        'points_filet_gagnes' => 'integer',
        'points_filet_joues' => 'integer',
        'montees_filet' => 'integer',
        'volleys_gagnants' => 'integer',
        'volleys_erreurs' => 'integer',
        'smashes_gagnes' => 'integer',
        'smashes_erreurs' => 'integer',

        // Entiers - Tiebreaks
        'tiebreaks_joues' => 'integer',
        'tiebreaks_gagnes' => 'integer',
        'points_tiebreak_gagnes' => 'integer',
        'points_tiebreak_joues' => 'integer',

        // Entiers - Physique
        'distance_parcourue' => 'integer',
        'calories_brulees' => 'integer',
        'frequence_cardiaque_max' => 'integer',
        'frequence_cardiaque_moyenne' => 'integer',
        'temps_au_service' => 'integer',
        'points_rapides' => 'integer',
        'points_longs' => 'integer',
        'echanges_plus_10_coups' => 'integer',

        // Entiers - Zones
        'coups_droits_croises' => 'integer',
        'coups_droits_croises_gagnes' => 'integer',
        'coups_droits_longline' => 'integer',
        'coups_droits_longline_gagnes' => 'integer',
        'revers_croises' => 'integer',
        'revers_croises_gagnes' => 'integer',
        'revers_longline' => 'integer',
        'revers_longline_gagnes' => 'integer',

        // Entiers - Moments clés
        'points_importants_gagnes' => 'integer',
        'points_importants_joues' => 'integer',
        'match_points_sauves' => 'integer',
        'match_points_convertis' => 'integer',
        'set_points_sauves' => 'integer',
        'set_points_convertis' => 'integer',
        'changements_tactiques' => 'integer',
        'points_consécutifs_max' => 'integer',
        'jeux_consécutifs_max' => 'integer',
        'comebacks_realises' => 'integer',
        'avances_perdues' => 'integer',

        // Entiers - Métadonnées
        'duree_collecte' => 'integer',
        'completude_donnees' => 'integer',
        'fiabilite_donnees' => 'integer',

        // Décimaux - Pourcentages
        'pourcentage_premiere_balle' => 'decimal:2',
        'pourcentage_points_premiere_balle' => 'decimal:2',
        'pourcentage_points_deuxieme_balle' => 'decimal:2',
        'pourcentage_service' => 'decimal:2',
        'pourcentage_jeux_service' => 'decimal:2',
        'pourcentage_retour_premiere_balle' => 'decimal:2',
        'pourcentage_retour_deuxieme_balle' => 'decimal:2',
        'pourcentage_retour' => 'decimal:2',
        'pourcentage_break_points_sauves' => 'decimal:2',
        'pourcentage_break_points_convertis' => 'decimal:2',
        'pourcentage_points_gagnes' => 'decimal:2',
        'pourcentage_jeux_gagnes' => 'decimal:2',
        'pourcentage_points_filet' => 'decimal:2',
        'pourcentage_tiebreaks' => 'decimal:2',
        'pourcentage_fond_court' => 'decimal:2',
        'pourcentage_mi_court' => 'decimal:2',
        'pourcentage_efficacite_coup_droit' => 'decimal:2',
        'pourcentage_efficacite_revers' => 'decimal:2',
        'pourcentage_points_importants' => 'decimal:2',

        // Décimaux - Vitesses
        'vitesse_service_max' => 'decimal:1',
        'vitesse_service_moyenne' => 'decimal:1',
        'vitesse_premiere_balle_moyenne' => 'decimal:1',
        'vitesse_deuxieme_balle_moyenne' => 'decimal:1',
        'vitesse_aces_moyenne' => 'decimal:1',
        'vitesse_coup_droit_max' => 'decimal:1',
        'vitesse_coup_droit_moyenne' => 'decimal:1',
        'vitesse_revers_max' => 'decimal:1',
        'vitesse_revers_moyenne' => 'decimal:1',
        'vitesse_volley_moyenne' => 'decimal:1',
        'vitesse_deplacement_max' => 'decimal:1',
        'vitesse_deplacement_moyenne' => 'decimal:1',

        // Décimaux - Ratios et scores
        'ratio_gagnants_erreurs' => 'decimal:2',
        'temps_entre_points_moyen' => 'decimal:1',
        'temps_entre_services' => 'decimal:1',
        'score_dominance' => 'decimal:1',
        'score_agressivite' => 'decimal:1',
        'score_regularite' => 'decimal:1',
        'score_mental' => 'decimal:1',
        'score_physique' => 'decimal:1',
        'score_tactique' => 'decimal:1',
        'indicateur_forme' => 'decimal:1',
        'indicateur_confiance' => 'decimal:1',
        'indicateur_motivation' => 'decimal:1',
        'facteur_ajustement_elo' => 'decimal:3',
        'efficacite_adaptations' => 'decimal:1',
        'reaction_pression' => 'decimal:1',
        'performance_sous_pression' => 'decimal:1',
        'gestion_fatigue' => 'decimal:1',
        'adaptation_conditions' => 'decimal:1',
        'constance_niveau' => 'decimal:1',

        // Booléens
        'premier_set_gagne' => 'boolean',
        'deuxieme_set_gagne' => 'boolean',
        'troisieme_set_gagne' => 'boolean',
        'quatrieme_set_gagne' => 'boolean',
        'cinquieme_set_gagne' => 'boolean',
        'stats_validees' => 'boolean',
        'stats_completes' => 'boolean',
        'utilisable_pour_ia' => 'boolean',
        'anomalie_detectee' => 'boolean',

        // JSON
        'sequence_points' => 'json',
        'pattern_service' => 'json',
        'pattern_retour' => 'json',
        'zones_faiblesses' => 'json',
        'zones_forces' => 'json',
    ];

    protected $appends = [
        'efficacite_service_globale',
        'efficacite_retour_globale',
        'dominance_score',
        'performance_globale',
        'points_forts_match',
        'points_faibles_match',
        'resume_performance',
        'impact_elo_estime',
        'niveau_jeu_observe',
        'facteurs_cles_performance',
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function match()
    {
        return $this->belongsTo(MatchTennis::class, 'match_tennis_id');
    }

    public function joueur()
    {
        return $this->belongsTo(Joueur::class);
    }

    public function adversaire()
    {
        return $this->hasOneThrough(
            Joueur::class,
            StatistiqueMatch::class,
            'match_tennis_id',
            'id',
            'match_tennis_id',
            'joueur_id'
        )->where('statistiques_match.joueur_id', '!=', $this->joueur_id);
    }

    public function comparaison()
    {
        return $this->hasOne(StatistiqueMatch::class, 'match_tennis_id', 'match_tennis_id')
            ->where('joueur_id', '!=', $this->joueur_id);
    }

    // ===================================================================
    // SCOPES
    // ===================================================================

    public function scopeParJoueur($query, $joueurId)
    {
        return $query->where('joueur_id', $joueurId);
    }

    public function scopeParMatch($query, $matchId)
    {
        return $query->where('match_tennis_id', $matchId);
    }

    public function scopeParSet($query, $set)
    {
        return $query->where('set_numero', $set);
    }

    public function scopeTotalMatch($query)
    {
        return $query->whereNull('set_numero');
    }

    public function scopeGagnants($query)
    {
        return $query->where('statut_joueur', 'gagnant');
    }

    public function scopePerdants($query)
    {
        return $query->where('statut_joueur', 'perdant');
    }

    public function scopeValidees($query)
    {
        return $query->where('stats_validees', true);
    }

    public function scopeCompletes($query)
    {
        return $query->where('stats_completes', true);
    }

    public function scopeUtilisablesPourIA($query)
    {
        return $query->where('utilisable_pour_ia', true);
    }

    public function scopePerformanceElevee($query)
    {
        return $query->where('score_dominance', '>=', 70);
    }

    public function scopeAvecAnomalies($query)
    {
        return $query->where('anomalie_detectee', true);
    }

    // ===================================================================
    // ACCESSORS INTELLIGENTS
    // ===================================================================

    public function getEfficaciteServiceGlobaleAttribute()
    {
        $composantes = [
            'aces' => min(100, ($this->aces ?? 0) * 10), // Max 10 aces = 100%
            'premiere_balle' => $this->pourcentage_premiere_balle ?? 0,
            'efficacite_premiere' => $this->pourcentage_points_premiere_balle ?? 0,
            'efficacite_deuxieme' => $this->pourcentage_points_deuxieme_balle ?? 0,
            'double_fautes' => max(0, 100 - (($this->double_fautes ?? 0) * 15)), // Pénalité par DF
        ];

        return round(array_sum($composantes) / count($composantes), 1);
    }

    public function getEfficaciteRetourGlobaleAttribute()
    {
        if (! $this->points_retour_joues || $this->points_retour_joues === 0) {
            return 0;
        }

        $composantes = [
            'retour_premiere' => $this->pourcentage_retour_premiere_balle ?? 0,
            'retour_deuxieme' => $this->pourcentage_retour_deuxieme_balle ?? 0,
            'breaks' => min(100, ($this->break_points_convertis ?? 0) * 25), // Max 4 breaks = 100%
            'retours_gagnants' => min(100, ($this->retours_gagnants ?? 0) * 20),
        ];

        return round(array_sum($composantes) / count($composantes), 1);
    }

    public function getDominanceScoreAttribute()
    {
        $facteurs = [
            'service' => $this->efficacite_service_globale * 0.3,
            'retour' => $this->efficacite_retour_globale * 0.3,
            'winners' => min(100, ($this->coups_gagnants ?? 0) * 2) * 0.2,
            'erreurs' => max(0, 100 - (($this->erreurs_directes ?? 0) * 3)) * 0.2,
        ];

        return round(array_sum($facteurs), 1);
    }

    public function getPerformanceGlobaleAttribute()
    {
        return [
            'dominance' => $this->dominance_score,
            'service' => $this->efficacite_service_globale,
            'retour' => $this->efficacite_retour_globale,
            'agressivite' => $this->score_agressivite ?? 50,
            'regularite' => $this->score_regularite ?? 50,
            'mental' => $this->score_mental ?? 50,
            'physique' => $this->score_physique ?? 50,
        ];
    }

    public function getPointsFortsMatchAttribute()
    {
        $points_forts = [];

        // Service
        if (($this->aces ?? 0) >= 10) {
            $points_forts[] = 'Service puissant';
        }
        if (($this->pourcentage_premiere_balle ?? 0) >= 70) {
            $points_forts[] = 'Première balle précise';
        }
        if (($this->pourcentage_points_premiere_balle ?? 0) >= 75) {
            $points_forts[] = 'Efficacité première balle';
        }

        // Retour
        if (($this->pourcentage_retour_premiere_balle ?? 0) >= 40) {
            $points_forts[] = 'Retour première balle';
        }
        if (($this->pourcentage_break_points_convertis ?? 0) >= 40) {
            $points_forts[] = 'Conversion break points';
        }

        // Jeu général
        if (($this->ratio_gagnants_erreurs ?? 0) >= 1.5) {
            $points_forts[] = 'Ratio winners/erreurs';
        }
        if (($this->pourcentage_points_filet ?? 0) >= 70) {
            $points_forts[] = 'Jeu au filet';
        }

        return $points_forts;
    }

    public function getPointsFaiblesMatchAttribute()
    {
        $points_faibles = [];

        // Service
        if (($this->double_fautes ?? 0) >= 8) {
            $points_faibles[] = 'Doubles fautes';
        }
        if (($this->pourcentage_premiere_balle ?? 0) <= 50) {
            $points_faibles[] = 'Première balle imprécise';
        }

        // Retour
        if (($this->pourcentage_retour_premiere_balle ?? 0) <= 25) {
            $points_faibles[] = 'Retour première balle';
        }
        if (($this->pourcentage_break_points_convertis ?? 0) <= 20) {
            $points_faibles[] = 'Conversion break points';
        }

        // Jeu général
        if (($this->ratio_gagnants_erreurs ?? 0) <= 0.8) {
            $points_faibles[] = 'Trop d\'erreurs directes';
        }
        if (($this->erreurs_directes ?? 0) >= 40) {
            $points_faibles[] = 'Erreurs directes nombreuses';
        }

        return $points_faibles;
    }

    public function getResumePerformanceAttribute()
    {
        return [
            'dominance' => $this->dominance_score.'/100',
            'service' => $this->efficacite_service_globale.'/100',
            'retour' => $this->efficacite_retour_globale.'/100',
            'aces' => $this->aces ?? 0,
            'double_fautes' => $this->double_fautes ?? 0,
            'winners' => $this->coups_gagnants ?? 0,
            'erreurs' => $this->erreurs_directes ?? 0,
            'breaks' => $this->break_points_convertis ?? 0,
            'points_forts' => $this->points_forts_match,
            'points_faibles' => $this->points_faibles_match,
        ];
    }

    public function getImpactEloEstimeAttribute()
    {
        $impact_base = 0;

        // Impact selon dominance
        if ($this->dominance_score >= 80) {
            $impact_base += 20;
        } elseif ($this->dominance_score >= 60) {
            $impact_base += 10;
        } elseif ($this->dominance_score <= 30) {
            $impact_base -= 15;
        } elseif ($this->dominance_score <= 50) {
            $impact_base -= 5;
        }

        // Ajustements contextuels
        $classement_adversaire = $this->match->getClassementAdversaire($this->joueur_id);
        if ($classement_adversaire <= 50) {
            $impact_base *= 1.5;
        } // Bonus vs top 50
        if ($classement_adversaire >= 500) {
            $impact_base *= 0.7;
        } // Malus vs classement bas

        return round($impact_base, 1);
    }

    public function getNiveauJeuObserveAttribute()
    {
        $score = $this->dominance_score;

        if ($score >= 85) {
            return 'Exceptionnel';
        }
        if ($score >= 70) {
            return 'Très bon';
        }
        if ($score >= 55) {
            return 'Bon';
        }
        if ($score >= 40) {
            return 'Moyen';
        }
        if ($score >= 25) {
            return 'Faible';
        }

        return 'Très faible';
    }

    public function getFacteursClesPerformanceAttribute()
    {
        return [
            'efficacite_service' => $this->efficacite_service_globale,
            'efficacite_retour' => $this->efficacite_retour_globale,
            'ratio_winners_erreurs' => $this->ratio_gagnants_erreurs ?? 0,
            'gestion_break_points' => ($this->pourcentage_break_points_sauves ?? 0 + $this->pourcentage_break_points_convertis ?? 0) / 2,
            'agressivite' => $this->score_agressivite ?? 50,
            'regularite' => $this->score_regularite ?? 50,
            'pression' => $this->performance_sous_pression ?? 50,
            'adaptation' => $this->adaptation_conditions ?? 50,
        ];
    }

    // ===================================================================
    // METHODS PRINCIPALES
    // ===================================================================

    /**
     * Calculer toutes les statistiques dérivées
     */
    public function calculerStatistiquesDerivees()
    {
        $this->calculerPourcentages();
        $this->calculerRatios();
        $this->calculerScoresPerformance();
        $this->detecterPatterns();
        $this->evaluerQualiteDonnees();

        $this->save();

        return $this;
    }

    /**
     * Comparer avec l'adversaire
     */
    public function comparerAvecAdversaire()
    {
        $statsAdversaire = $this->comparaison;
        if (! $statsAdversaire) {
            return null;
        }

        return [
            'service' => [
                'joueur' => $this->efficacite_service_globale,
                'adversaire' => $statsAdversaire->efficacite_service_globale,
                'avantage' => $this->efficacite_service_globale - $statsAdversaire->efficacite_service_globale,
            ],
            'retour' => [
                'joueur' => $this->efficacite_retour_globale,
                'adversaire' => $statsAdversaire->efficacite_retour_globale,
                'avantage' => $this->efficacite_retour_globale - $statsAdversaire->efficacite_retour_globale,
            ],
            'dominance' => [
                'joueur' => $this->dominance_score,
                'adversaire' => $statsAdversaire->dominance_score,
                'avantage' => $this->dominance_score - $statsAdversaire->dominance_score,
            ],
        ];
    }

    /**
     * Analyser l'évolution par set
     */
    public function analyserEvolutionSets()
    {
        $sets = StatistiqueMatch::where('match_tennis_id', $this->match_tennis_id)
            ->where('joueur_id', $this->joueur_id)
            ->whereNotNull('set_numero')
            ->orderBy('set_numero')
            ->get();

        $evolution = [];
        foreach ($sets as $set) {
            $evolution[$set->set_numero] = [
                'service' => $set->efficacite_service_globale,
                'retour' => $set->efficacite_retour_globale,
                'dominance' => $set->dominance_score,
                'gagne' => $set->{"set{$set->set_numero}_gagne"} ?? false,
            ];
        }

        return $evolution;
    }

    /**
     * Identifier les moments clés du match
     */
    public function identifierMomentssCles()
    {
        $moments = [];

        // Break points critiques
        if ($this->break_points_convertis > 0) {
            $moments[] = [
                'type' => 'break_realise',
                'importance' => 'haute',
                'impact' => 'positif',
                'nombre' => $this->break_points_convertis,
            ];
        }

        if ($this->break_points_sauves > 0) {
            $moments[] = [
                'type' => 'break_sauve',
                'importance' => 'haute',
                'impact' => 'positif',
                'nombre' => $this->break_points_sauves,
            ];
        }

        // Séries de points
        if (($this->points_consécutifs_max ?? 0) >= 6) {
            $moments[] = [
                'type' => 'serie_points',
                'importance' => 'moyenne',
                'impact' => 'positif',
                'nombre' => $this->points_consécutifs_max,
            ];
        }

        // Comebacks
        if (($this->comebacks_realises ?? 0) > 0) {
            $moments[] = [
                'type' => 'comeback',
                'importance' => 'très_haute',
                'impact' => 'positif',
                'nombre' => $this->comebacks_realises,
            ];
        }

        return $moments;
    }

    /**
     * Générer insights pour l'IA
     */
    public function genererInsightsIA()
    {
        return [
            'performance_globale' => $this->performance_globale,
            'facteurs_cles' => $this->facteurs_cles_performance,
            'points_forts' => $this->points_forts_match,
            'points_faibles' => $this->points_faibles_match,
            'patterns_detectes' => [
                'service' => $this->pattern_service,
                'retour' => $this->pattern_retour,
            ],
            'zones_analyse' => [
                'forces' => $this->zones_forces,
                'faiblesses' => $this->zones_faiblesses,
            ],
            'evolution_match' => $this->analyserEvolutionSets(),
            'moments_cles' => $this->identifierMomentssCles(),
            'impact_elo' => $this->impact_elo_estime,
            'facteur_ajustement' => $this->facteur_ajustement_elo,
            'fiabilite_donnees' => $this->fiabilite_donnees,
            'utilisable_prediction' => $this->utilisable_pour_ia,
        ];
    }

    // ===================================================================
    // METHODS PRIVÉES DE CALCUL
    // ===================================================================

    private function calculerPourcentages()
    {
        // Service
        if ($this->premieres_balles_tentees > 0) {
            $this->pourcentage_premiere_balle = ($this->premieres_balles_reussies / $this->premieres_balles_tentees) * 100;
        }

        if ($this->points_joues_premiere_balle > 0) {
            $this->pourcentage_points_premiere_balle = ($this->points_gagnes_premiere_balle / $this->points_joues_premiere_balle) * 100;
        }

        if ($this->points_service_joues > 0) {
            $this->pourcentage_service = ($this->points_service_gagnes / $this->points_service_joues) * 100;
        }

        // Retour
        if ($this->points_retour_joues > 0) {
            $this->pourcentage_retour = ($this->points_retour_gagnes / $this->points_retour_joues) * 100;
        }

        // Break points
        if ($this->break_points_affrontes > 0) {
            $this->pourcentage_break_points_sauves = ($this->break_points_sauves / $this->break_points_affrontes) * 100;
        }

        if ($this->break_points_obtenus > 0) {
            $this->pourcentage_break_points_convertis = ($this->break_points_convertis / $this->break_points_obtenus) * 100;
        }
    }

    private function calculerRatios()
    {
        if (($this->erreurs_directes ?? 0) > 0) {
            $this->ratio_gagnants_erreurs = ($this->coups_gagnants ?? 0) / $this->erreurs_directes;
        }
    }

    private function calculerScoresPerformance()
    {
        // Score de dominance (calculé via accessor)
        $this->score_dominance = $this->dominance_score;

        // Score d'agressivité
        $this->score_agressivite = $this->calculerScoreAgressivite();

        // Score de régularité
        $this->score_regularite = $this->calculerScoreRegularite();

        // Score mental
        $this->score_mental = $this->calculerScoreMental();
    }

    private function detecterPatterns()
    {
        // Patterns de service
        $patternService = [];
        if (($this->services_centre ?? 0) > ($this->services_exterieur ?? 0)) {
            $patternService[] = 'preference_centre';
        }
        if (($this->aces ?? 0) >= 10) {
            $patternService[] = 'service_puissant';
        }
        $this->pattern_service = $patternService;

        // Patterns de retour
        $patternRetour = [];
        if (($this->retours_gagnants ?? 0) >= 5) {
            $patternRetour[] = 'retour_agressif';
        }
        $this->pattern_retour = $patternRetour;
    }

    private function evaluerQualiteDonnees()
    {
        $completude = 0;
        $champs_cles = ['aces', 'double_fautes', 'points_gagnes', 'coups_gagnants', 'erreurs_directes'];

        foreach ($champs_cles as $champ) {
            if (! is_null($this->$champ)) {
                $completude += 20;
            }
        }

        $this->completude_donnees = $completude;
        $this->stats_completes = $completude >= 80;
        $this->utilisable_pour_ia = $this->stats_completes && ! $this->anomalie_detectee;
    }

    private function calculerScoreAgressivite()
    {
        $facteurs = [
            'winners' => min(100, ($this->coups_gagnants ?? 0) * 2),
            'montees_filet' => min(100, ($this->montees_filet ?? 0) * 5),
            'retours_gagnants' => min(100, ($this->retours_gagnants ?? 0) * 10),
            'vitesse_service' => min(100, (($this->vitesse_service_max ?? 150) - 150) * 2),
        ];

        return round(array_sum($facteurs) / count($facteurs), 1);
    }

    private function calculerScoreRegularite()
    {
        $erreurs_ratio = ($this->erreurs_directes ?? 0) / max(1, $this->points_joues ?? 1);
        $constance = max(0, 100 - ($erreurs_ratio * 100));

        return round($constance, 1);
    }

    private function calculerScoreMental()
    {
        $facteurs = [
            'break_points' => $this->pourcentage_break_points_sauves ?? 50,
            'points_importants' => $this->pourcentage_points_importants ?? 50,
            'constance' => $this->constance_niveau ?? 50,
        ];

        return round(array_sum($facteurs) / count($facteurs), 1);
    }

    // ===================================================================
    // VALIDATION RULES
    // ===================================================================

    public static function validationRules()
    {
        return [
            'match_tennis_id' => 'required|exists:match_tennis,id',
            'joueur_id' => 'required|exists:joueurs,id',
            'set_numero' => 'nullable|integer|between:1,5',
            'position_match' => 'required|in:joueur1,joueur2',
            'statut_joueur' => 'required|in:gagnant,perdant',
            'aces' => 'nullable|integer|min:0|max:50',
            'double_fautes' => 'nullable|integer|min:0|max:30',
        ];
    }

    // ===================================================================
    // BOOT METHODS
    // ===================================================================

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($stat) {
            // Auto-calculs avant sauvegarde
            $stat->calculerPourcentages();
            $stat->calculerRatios();

            // Validation cohérence
            if ($stat->aces > 50 || $stat->double_fautes > 30) {
                $stat->anomalie_detectee = true;
            }

            // Mise à jour timestamp
            $stat->derniere_maj_stats = now();
        });

        static::saved(function ($stat) {
            // Recalcul des statistiques après sauvegarde
            $stat->calculerStatistiquesDerivees();
        });
    }
}
