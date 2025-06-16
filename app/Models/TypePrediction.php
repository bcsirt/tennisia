<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypePrediction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'type_predictions';

    protected $fillable = [
        // Identification
        'nom',
        'nom_technique',          // Nom technique précis
        'code',                   // Code unique du type
        'description',
        'description_technique',  // Description technique détaillée
        'synonymes',              // JSON des autres appellations
        'abreviation',            // Abréviation courte

        // Classification prédiction
        'categorie_principale',   // 'outcome', 'score', 'performance', 'risk', 'behavior'
        'sous_categorie',         // 'binary', 'multi_class', 'regression', 'time_series'
        'domaine_tennis',         // 'match', 'joueur', 'tournoi', 'saison', 'carriere'
        'specialisation',         // 'surface', 'weather', 'injury', 'style', 'mental'
        'approche_ml',            // 'classification', 'regression', 'clustering', 'ranking'

        // Complexité et difficulté
        'niveau_difficulte',      // 1-10 niveau de difficulté
        'complexite_calcul',      // 1-10 complexité computationnelle
        'incertitude_intrinseque', // 1-10 incertitude naturelle
        'predictibilite',         // 1-10 degré de prédictibilité
        'volatilite',             // 1-10 volatilité des résultats
        'dependance_contexte',    // 1-10 dépendance au contexte

        // Temporalité
        'horizon_prediction',     // 'immediate', 'court_terme', 'moyen_terme', 'long_terme'
        'duree_validite',         // Durée validité prédiction (heures)
        'frequence_maj',          // Fréquence mise à jour (heures)
        'delai_avant_match',      // Délai minimum avant match (heures)
        'window_prediction',      // Fenêtre temporelle optimale
        'peremption_auto',        // Péremption automatique

        // Métriques de performance ciblées
        'precision_cible',        // Précision visée (%)
        'precision_minimale',     // Précision minimum acceptable (%)
        'precision_actuelle',     // Précision actuelle moyenne (%)
        'recall_cible',           // Recall visé (%)
        'f1_cible',              // F1-Score visé (%)
        'confiance_moyenne',      // Niveau confiance moyen
        'calibration_score',      // Score de calibration

        // Performance par contexte tennis
        'precision_dur',          // Précision surface dur (%)
        'precision_terre',        // Précision terre battue (%)
        'precision_gazon',        // Précision gazon (%)
        'precision_indoor',       // Précision indoor (%)
        'precision_outdoor',      // Précision outdoor (%)
        'precision_top_100',      // Précision top 100 (%)
        'precision_qualifs',      // Précision qualifications (%)
        'precision_finales',      // Précision phases finales (%)

        // Facteurs d'influence
        'sensible_surface',       // Sensible au type de surface
        'sensible_meteo',         // Sensible aux conditions météo
        'sensible_forme',         // Sensible à la forme joueur
        'sensible_fatigue',       // Sensible à la fatigue
        'sensible_pression',      // Sensible à la pression
        'sensible_historique',    // Sensible à l'historique H2H
        'sensible_classement',    // Sensible aux classements
        'sensible_age',           // Sensible à l'âge joueurs

        // Données requises
        'features_obligatoires',  // JSON features obligatoires
        'features_optionnelles',  // JSON features optionnelles
        'donnees_historiques_min', // Minimum données historiques (jours)
        'nb_matchs_min',          // Nombre minimum matchs requis
        'qualite_donnees_min',    // Qualité minimum données (1-10)
        'sources_donnees',        // JSON sources recommandées

        // Paramètres de confiance
        'seuil_confiance_min',    // Seuil confiance minimum
        'seuil_confiance_pub',    // Seuil pour publication
        'incertitude_max',        // Incertitude maximum acceptable
        'marge_erreur_typique',   // Marge erreur typique (%)
        'intervalle_confiance',   // Intervalle confiance standard

        // Utilisation et audience
        'audience_cible',         // 'public', 'expert', 'professionnel', 'paris'
        'cas_usage',              // JSON cas d'usage typiques
        'valeur_business',        // Valeur business 1-10
        'impact_utilisateur',     // Impact utilisateur 1-10
        'criticite_erreur',       // Criticité erreur 1-10
        'frequence_demande',      // Fréquence demandes par jour

        // Contraintes et limites
        'conditions_optimales',   // JSON conditions optimales
        'conditions_interdites',  // JSON conditions interdites
        'limites_connues',        // JSON limites connues
        'cas_non_supportes',      // JSON cas non supportés
        'biais_potentiels',       // JSON biais potentiels
        'risques_surestimation',  // Risques de surestimation
        'risques_sous_estimation', // Risques de sous-estimation

        // Algorithmes et techniques
        'algorithmes_recommandes', // JSON algorithmes recommandés
        'techniques_preprocessing', // JSON techniques preprocessing
        'methodes_validation',    // JSON méthodes validation
        'metriques_evaluation',   // JSON métriques évaluation
        'benchmarks_reference',   // JSON benchmarks de référence

        // Interprétabilité
        'interpretabilite_requise', // Interprétabilité requise
        'explicabilite_niveau',   // Niveau explicabilité 1-10
        'shap_applicable',        // SHAP applicable
        'lime_applicable',        // LIME applicable
        'feature_importance',     // Importance features activée
        'visualisations_disponibles', // JSON visualisations

        // Monitoring et qualité
        'monitoring_actif',       // Monitoring qualité actif
        'drift_detection',        // Détection drift activée
        'alertes_performance',    // Alertes performance activées
        'logs_predictions',       // Logging prédictions
        'audit_trail',            // Trail audit
        'controle_qualite',       // Contrôle qualité automatique

        // Calibration et amélioration
        'recalibration_auto',     // Recalibration automatique
        'apprentissage_continu',  // Apprentissage continu
        'feedback_integration',   // Intégration feedback
        'auto_tuning',            // Auto-tuning paramètres
        'optimisation_continue',  // Optimisation continue

        // Ensemble et combinaisons
        'combinable',             // Peut être combiné avec autres
        'types_compatibles',      // JSON types compatibles
        'synergie_types',         // JSON synergies avec autres types
        'conflits_types',         // JSON conflits potentiels
        'agregation_methode',     // Méthode agrégation recommandée

        // Distribution et probabilités
        'type_distribution',      // Type distribution résultats
        'parametres_distribution', // JSON paramètres distribution
        'queue_distribution',     // Comportement queues distribution
        'outliers_frequence',     // Fréquence outliers (%)
        'asymetrie',              // Asymétrie distribution
        'kurtosis',               // Kurtosis distribution

        // Sportif et contexte tennis
        'impact_classement',      // Impact sur classements
        'impact_selection',       // Impact sélection équipes
        'impact_strategic',       // Impact stratégique match
        'impact_preparation',     // Impact préparation
        'impact_paris',           // Impact paris sportifs
        'impact_media',           // Impact couverture média

        // Coûts et ressources
        'cout_calcul',            // Coût calcul (échelle 1-10)
        'temps_calcul_moyen',     // Temps calcul moyen (ms)
        'memoire_requise',        // Mémoire requise (MB)
        'ressources_gpu',         // Ressources GPU requises
        'scalabilite',            // Niveau scalabilité 1-10
        'cout_stockage',          // Coût stockage données

        // Réglementation et éthique
        'conforme_rgpd',          // Conforme RGPD
        'anonymisation_requise',  // Anonymisation requise
        'consentement_requis',    // Consentement utilisateur requis
        'transparence_niveau',    // Niveau transparence 1-10
        'ethique_score',          // Score éthique 1-10
        'biais_acceptables',      // JSON biais acceptables

        // Interface et présentation
        'format_affichage',       // Format affichage recommandé
        'unite_mesure',           // Unité de mesure
        'precision_affichage',    // Précision affichage (décimales)
        'visualisation_type',     // Type visualisation recommandé
        'couleur_theme',          // Thème couleur pour UI
        'icone_representative',   // Icône représentative

        // Historique et évolution
        'date_creation_type',     // Date création du type
        'version_actuelle',       // Version actuelle du type
        'evolutions_prevues',     // JSON évolutions prévues
        'obsolescence_prevue',    // Date obsolescence prévue
        'remplacant_prevu',       // Type remplaçant prévu
        'historique_versions',    // JSON historique versions

        // Tests et validation
        'tests_disponibles',      // Tests automatisés disponibles
        'validation_croisee',     // Validation croisée activée
        'bootstrap_validation',   // Bootstrap validation
        'stress_tests',           // Stress tests disponibles
        'regression_tests',       // Tests régression
        'performance_baseline',   // Baseline performance

        // Documentation et support
        'documentation_url',      // URL documentation
        'exemples_utilisation',   // JSON exemples utilisation
        'faq_disponible',         // FAQ disponible
        'support_technique',      // Support technique disponible
        'communaute_active',      // Communauté utilisateurs active
        'formation_requise',      // Formation requise pour utilisation

        // Métadonnées système
        'tags',                   // JSON tags catégorisation
        'mots_cles',             // JSON mots-clés recherche
        'popularite_score',       // Score popularité 1-10
        'maturite_niveau',        // Niveau maturité 1-10
        'stabilite_api',          // Stabilité API 1-10
        'adoption_rate',          // Taux adoption (%)
        'satisfaction_users',     // Satisfaction utilisateurs 1-10

        // Gestion et gouvernance
        'proprietaire',           // Propriétaire du type
        'mainteneur_principal',   // Mainteneur principal
        'equipe_responsible',     // Équipe responsable
        'statut_maintenance',     // Statut maintenance
        'roadmap_evolution',      // JSON roadmap évolution
        'budget_maintenance',     // Budget maintenance annuel

        'ordre_affichage',        // Ordre affichage
        'priorite',               // Priorité 1-10
        'actif',
    ];

    protected $casts = [
        // JSON
        'synonymes' => 'json',
        'features_obligatoires' => 'json',
        'features_optionnelles' => 'json',
        'sources_donnees' => 'json',
        'cas_usage' => 'json',
        'conditions_optimales' => 'json',
        'conditions_interdites' => 'json',
        'limites_connues' => 'json',
        'cas_non_supportes' => 'json',
        'biais_potentiels' => 'json',
        'algorithmes_recommandes' => 'json',
        'techniques_preprocessing' => 'json',
        'methodes_validation' => 'json',
        'metriques_evaluation' => 'json',
        'benchmarks_reference' => 'json',
        'visualisations_disponibles' => 'json',
        'types_compatibles' => 'json',
        'synergie_types' => 'json',
        'conflits_types' => 'json',
        'parametres_distribution' => 'json',
        'biais_acceptables' => 'json',
        'evolutions_prevues' => 'json',
        'historique_versions' => 'json',
        'exemples_utilisation' => 'json',
        'tags' => 'json',
        'mots_cles' => 'json',
        'roadmap_evolution' => 'json',

        // Entiers
        'niveau_difficulte' => 'integer',
        'complexite_calcul' => 'integer',
        'incertitude_intrinseque' => 'integer',
        'predictibilite' => 'integer',
        'volatilite' => 'integer',
        'dependance_contexte' => 'integer',
        'duree_validite' => 'integer',
        'frequence_maj' => 'integer',
        'delai_avant_match' => 'integer',
        'donnees_historiques_min' => 'integer',
        'nb_matchs_min' => 'integer',
        'qualite_donnees_min' => 'integer',
        'valeur_business' => 'integer',
        'impact_utilisateur' => 'integer',
        'criticite_erreur' => 'integer',
        'frequence_demande' => 'integer',
        'explicabilite_niveau' => 'integer',
        'temps_calcul_moyen' => 'integer',
        'memoire_requise' => 'integer',
        'scalabilite' => 'integer',
        'cout_stockage' => 'integer',
        'transparence_niveau' => 'integer',
        'ethique_score' => 'integer',
        'precision_affichage' => 'integer',
        'popularite_score' => 'integer',
        'maturite_niveau' => 'integer',
        'stabilite_api' => 'integer',
        'satisfaction_users' => 'integer',
        'ordre_affichage' => 'integer',
        'priorite' => 'integer',

        // Décimaux
        'version_actuelle' => 'decimal:2',
        'precision_cible' => 'decimal:2',
        'precision_minimale' => 'decimal:2',
        'precision_actuelle' => 'decimal:2',
        'recall_cible' => 'decimal:2',
        'f1_cible' => 'decimal:2',
        'confiance_moyenne' => 'decimal:3',
        'calibration_score' => 'decimal:3',
        'precision_dur' => 'decimal:2',
        'precision_terre' => 'decimal:2',
        'precision_gazon' => 'decimal:2',
        'precision_indoor' => 'decimal:2',
        'precision_outdoor' => 'decimal:2',
        'precision_top_100' => 'decimal:2',
        'precision_qualifs' => 'decimal:2',
        'precision_finales' => 'decimal:2',
        'seuil_confiance_min' => 'decimal:3',
        'seuil_confiance_pub' => 'decimal:3',
        'incertitude_max' => 'decimal:3',
        'marge_erreur_typique' => 'decimal:2',
        'intervalle_confiance' => 'decimal:2',
        'outliers_frequence' => 'decimal:2',
        'asymetrie' => 'decimal:3',
        'kurtosis' => 'decimal:3',
        'cout_calcul' => 'decimal:1',
        'adoption_rate' => 'decimal:2',
        'budget_maintenance' => 'decimal:2',

        // Booléens
        'peremption_auto' => 'boolean',
        'sensible_surface' => 'boolean',
        'sensible_meteo' => 'boolean',
        'sensible_forme' => 'boolean',
        'sensible_fatigue' => 'boolean',
        'sensible_pression' => 'boolean',
        'sensible_historique' => 'boolean',
        'sensible_classement' => 'boolean',
        'sensible_age' => 'boolean',
        'interpretabilite_requise' => 'boolean',
        'shap_applicable' => 'boolean',
        'lime_applicable' => 'boolean',
        'feature_importance' => 'boolean',
        'monitoring_actif' => 'boolean',
        'drift_detection' => 'boolean',
        'alertes_performance' => 'boolean',
        'logs_predictions' => 'boolean',
        'audit_trail' => 'boolean',
        'controle_qualite' => 'boolean',
        'recalibration_auto' => 'boolean',
        'apprentissage_continu' => 'boolean',
        'feedback_integration' => 'boolean',
        'auto_tuning' => 'boolean',
        'optimisation_continue' => 'boolean',
        'combinable' => 'boolean',
        'conforme_rgpd' => 'boolean',
        'anonymisation_requise' => 'boolean',
        'consentement_requis' => 'boolean',
        'tests_disponibles' => 'boolean',
        'validation_croisee' => 'boolean',
        'bootstrap_validation' => 'boolean',
        'stress_tests' => 'boolean',
        'regression_tests' => 'boolean',
        'faq_disponible' => 'boolean',
        'support_technique' => 'boolean',
        'communaute_active' => 'boolean',
        'formation_requise' => 'boolean',
        'actif' => 'boolean',

        // Dates
        'date_creation_type' => 'date',
        'obsolescence_prevue' => 'date',
    ];

    protected $appends = [
        'niveau_complexite_global',
        'score_fiabilite',
        'contextes_optimaux',
        'facteurs_influence_principaux',
        'recommandations_usage',
        'metriques_performance_globales',
        'score_maturite',
        'niveau_adoption',
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function predictions()
    {
        return $this->hasMany(Prediction::class, 'type_prediction_id');
    }

    public function predictionsReussies()
    {
        return $this->hasMany(Prediction::class, 'type_prediction_id')
            ->where('est_correcte', true);
    }

    public function algorithmes()
    {
        return $this->belongsToMany(AlgorithmeIA::class, 'algorithme_type_predictions')
            ->withPivot(['performance', 'recommande', 'actif']);
    }

    public function algorithmesRecommandes()
    {
        return $this->belongsToMany(AlgorithmeIA::class, 'algorithme_type_predictions')
            ->wherePivot('recommande', true);
    }

    public function evaluations()
    {
        return $this->hasMany(EvaluationTypePrediction::class, 'type_prediction_id');
    }

    public function configurationIA()
    {
        return $this->hasMany(ConfigurationIA::class, 'type_prediction_id');
    }

    public function metriquesPerformance()
    {
        return $this->hasMany(MetriquePerformance::class, 'type_prediction_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(FeedbackPrediction::class, 'type_prediction_id');
    }

    // ===================================================================
    // ACCESSORS
    // ===================================================================

    public function getNiveauComplexiteGlobalAttribute()
    {
        $composantes = [
            $this->niveau_difficulte ?? 5,
            $this->complexite_calcul ?? 5,
            $this->incertitude_intrinseque ?? 5,
            $this->dependance_contexte ?? 5,
        ];

        $moyenne = array_sum($composantes) / count($composantes);

        if ($moyenne >= 8) {
            return 'Très complexe';
        }
        if ($moyenne >= 6) {
            return 'Complexe';
        }
        if ($moyenne >= 4) {
            return 'Modéré';
        }
        if ($moyenne >= 2) {
            return 'Simple';
        }

        return 'Très simple';
    }

    public function getScoreFiabiliteAttribute()
    {
        $composantes = [
            'precision' => ($this->precision_actuelle ?? 50) / 100,
            'stabilite' => ($this->maturite_niveau ?? 5) / 10,
            'calibration' => $this->calibration_score ?? 0.5,
            'monitoring' => $this->monitoring_actif ? 1 : 0.5,
            'validation' => $this->validation_croisee ? 1 : 0.5,
        ];

        return round(array_sum($composantes) / count($composantes) * 100, 1);
    }

    public function getContextesOptimauxAttribute()
    {
        $contextes = [];

        // Surfaces optimales
        $surfaces = [];
        if (($this->precision_dur ?? 0) >= 75) {
            $surfaces[] = 'Dur';
        }
        if (($this->precision_terre ?? 0) >= 75) {
            $surfaces[] = 'Terre battue';
        }
        if (($this->precision_gazon ?? 0) >= 75) {
            $surfaces[] = 'Gazon';
        }
        if (($this->precision_indoor ?? 0) >= 75) {
            $surfaces[] = 'Indoor';
        }

        if (! empty($surfaces)) {
            $contextes['surfaces'] = $surfaces;
        }

        // Niveaux de compétition
        if (($this->precision_top_100 ?? 0) >= 75) {
            $contextes['niveau'] = 'Top 100';
        }
        if (($this->precision_finales ?? 0) >= 75) {
            $contextes['phases'] = 'Phases finales';
        }

        // Conditions spéciales
        if ($this->conditions_optimales) {
            $contextes['conditions'] = $this->conditions_optimales;
        }

        return $contextes;
    }

    public function getFacteursInfluencePrincipauxAttribute()
    {
        $facteurs = [];

        if ($this->sensible_surface) {
            $facteurs[] = 'Surface';
        }
        if ($this->sensible_meteo) {
            $facteurs[] = 'Météo';
        }
        if ($this->sensible_forme) {
            $facteurs[] = 'Forme joueur';
        }
        if ($this->sensible_fatigue) {
            $facteurs[] = 'Fatigue';
        }
        if ($this->sensible_pression) {
            $facteurs[] = 'Pression psychologique';
        }
        if ($this->sensible_historique) {
            $facteurs[] = 'Historique H2H';
        }
        if ($this->sensible_classement) {
            $facteurs[] = 'Classements';
        }
        if ($this->sensible_age) {
            $facteurs[] = 'Âge joueurs';
        }

        return $facteurs;
    }

    public function getRecommandationsUsageAttribute()
    {
        $recommandations = [];

        // Basé sur la complexité
        if ($this->niveau_difficulte >= 8) {
            $recommandations[] = 'Réservé aux experts expérimentés';
        }

        // Basé sur l'horizon temporel
        if ($this->horizon_prediction === 'long_terme') {
            $recommandations[] = 'Prévoir large marge d\'incertitude';
        }

        // Basé sur la sensibilité
        if (count($this->facteurs_influence_principaux) > 5) {
            $recommandations[] = 'Surveiller de nombreux facteurs contextuels';
        }

        // Basé sur les données requises
        if ($this->nb_matchs_min > 50) {
            $recommandations[] = 'Nécessite historique substantiel';
        }

        // Basé sur la criticité
        if ($this->criticite_erreur >= 8) {
            $recommandations[] = 'Validation manuelle recommandée';
        }

        return $recommandations;
    }

    public function getMetriquesPerformanceGlobalesAttribute()
    {
        return [
            'precision_moyenne' => ($this->precision_actuelle ?? 0).'%',
            'confiance_moyenne' => round(($this->confiance_moyenne ?? 0) * 100, 1).'%',
            'calibration' => round(($this->calibration_score ?? 0) * 100, 1).'%',
            'fiabilite' => $this->score_fiabilite.'%',
            'predictions_total' => $this->predictions()->count(),
            'taux_succes' => $this->getTauxSucces().'%',
            'temps_calcul' => ($this->temps_calcul_moyen ?? 0).'ms',
            'adoption' => ($this->adoption_rate ?? 0).'%',
        ];
    }

    public function getScoreMaturiteAttribute()
    {
        $facteurs = [
            'niveau_base' => $this->maturite_niveau ?? 5,
            'stabilite' => $this->stabilite_api ?? 5,
            'documentation' => $this->documentation_url ? 10 : 5,
            'tests' => $this->tests_disponibles ? 10 : 5,
            'support' => $this->support_technique ? 10 : 5,
            'adoption' => ($this->adoption_rate ?? 0) / 10,
        ];

        return round(array_sum($facteurs) / count($facteurs), 1);
    }

    public function getNiveauAdoptionAttribute()
    {
        $adoption = $this->adoption_rate ?? 0;
        $usage = $this->predictions()->count();

        if ($adoption >= 80 && $usage >= 1000) {
            return 'Très élevé';
        }
        if ($adoption >= 60 && $usage >= 500) {
            return 'Élevé';
        }
        if ($adoption >= 40 && $usage >= 100) {
            return 'Modéré';
        }
        if ($adoption >= 20 && $usage >= 50) {
            return 'Faible';
        }

        return 'Très faible';
    }

    // ===================================================================
    // SCOPES
    // ===================================================================

    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    public function scopeParCategorie($query, $categorie)
    {
        return $query->where('categorie_principale', $categorie);
    }

    public function scopeParDomaine($query, $domaine)
    {
        return $query->where('domaine_tennis', $domaine);
    }

    public function scopeParHorizon($query, $horizon)
    {
        return $query->where('horizon_prediction', $horizon);
    }

    public function scopeSimples($query)
    {
        return $query->where('niveau_difficulte', '<=', 4);
    }

    public function scopeComplexes($query)
    {
        return $query->where('niveau_difficulte', '>=', 7);
    }

    public function scopePerformants($query, $seuilPrecision = 70)
    {
        return $query->where('precision_actuelle', '>=', $seuilPrecision);
    }

    public function scopeInterpretables($query)
    {
        return $query->where('interpretabilite_requise', true)
            ->orWhere('shap_applicable', true);
    }

    public function scopeTempsReel($query)
    {
        return $query->where('horizon_prediction', 'immediate')
            ->where('temps_calcul_moyen', '<=', 100);
    }

    public function scopeAvecMonitoring($query)
    {
        return $query->where('monitoring_actif', true)
            ->where('drift_detection', true);
    }

    public function scopeCombinable($query)
    {
        return $query->where('combinable', true);
    }

    public function scopePopulaires($query)
    {
        return $query->where('popularite_score', '>=', 7)
            ->orWhere('adoption_rate', '>=', 50);
    }

    public function scopeMatures($query)
    {
        return $query->where('maturite_niveau', '>=', 7)
            ->where('stabilite_api', '>=', 8);
    }

    public function scopeSpecialises($query, $specialisation)
    {
        return $query->where('specialisation', $specialisation);
    }

    public function scopeOrdonnesParPerformance($query)
    {
        return $query->orderBy('precision_actuelle', 'desc')
            ->orderBy('confiance_moyenne', 'desc')
            ->orderBy('popularite_score', 'desc');
    }

    public function scopeRecherche($query, $terme)
    {
        return $query->where(function ($q) use ($terme) {
            $q->where('nom', 'LIKE', "%{$terme}%")
                ->orWhere('code', 'LIKE', "%{$terme}%")
                ->orWhere('categorie_principale', 'LIKE', "%{$terme}%")
                ->orWhere('domaine_tennis', 'LIKE', "%{$terme}%")
                ->orWhere('specialisation', 'LIKE', "%{$terme}%");
        });
    }

    // ===================================================================
    // STATIC METHODS
    // ===================================================================

    /**
     * Créer les types de prédictions tennis standard
     */
    public static function creerTypesStandard()
    {
        $types = [
            [
                'nom' => 'Gagnant Match',
                'nom_technique' => 'Binary Match Outcome Prediction',
                'code' => 'match_winner',
                'categorie_principale' => 'outcome',
                'sous_categorie' => 'binary',
                'domaine_tennis' => 'match',
                'specialisation' => 'general',
                'approche_ml' => 'classification',
                'niveau_difficulte' => 6,
                'horizon_prediction' => 'court_terme',
                'precision_cible' => 75.0,
                'precision_actuelle' => 73.2,
                'precision_dur' => 74.5,
                'precision_terre' => 71.8,
                'precision_gazon' => 69.3,
                'sensible_surface' => true,
                'sensible_forme' => true,
                'sensible_historique' => true,
                'audience_cible' => 'public',
                'valeur_business' => 9,
                'popularite_score' => 10,
                'maturite_niveau' => 9,
            ],
            [
                'nom' => 'Score Exact',
                'nom_technique' => 'Exact Score Multi-Class Prediction',
                'code' => 'exact_score',
                'categorie_principale' => 'score',
                'sous_categorie' => 'multi_class',
                'domaine_tennis' => 'match',
                'specialisation' => 'scoring',
                'approche_ml' => 'classification',
                'niveau_difficulte' => 9,
                'horizon_prediction' => 'court_terme',
                'precision_cible' => 45.0,
                'precision_actuelle' => 42.7,
                'precision_finales' => 38.2,
                'sensible_surface' => true,
                'sensible_forme' => true,
                'sensible_fatigue' => true,
                'audience_cible' => 'expert',
                'valeur_business' => 8,
                'criticite_erreur' => 6,
                'popularite_score' => 7,
                'maturite_niveau' => 7,
            ],
            [
                'nom' => 'Durée Match',
                'nom_technique' => 'Match Duration Regression',
                'code' => 'match_duration',
                'categorie_principale' => 'performance',
                'sous_categorie' => 'regression',
                'domaine_tennis' => 'match',
                'specialisation' => 'temporal',
                'approche_ml' => 'regression',
                'niveau_difficulte' => 7,
                'horizon_prediction' => 'immediate',
                'precision_cible' => 65.0,
                'precision_actuelle' => 61.4,
                'marge_erreur_typique' => 25.0,
                'sensible_surface' => true,
                'sensible_meteo' => true,
                'sensible_fatigue' => true,
                'audience_cible' => 'professionnel',
                'valeur_business' => 6,
                'popularite_score' => 6,
                'maturite_niveau' => 6,
            ],
            [
                'nom' => 'Risque Blessure',
                'nom_technique' => 'Injury Risk Assessment',
                'code' => 'injury_risk',
                'categorie_principale' => 'risk',
                'sous_categorie' => 'regression',
                'domaine_tennis' => 'joueur',
                'specialisation' => 'medical',
                'approche_ml' => 'regression',
                'niveau_difficulte' => 8,
                'horizon_prediction' => 'moyen_terme',
                'precision_cible' => 70.0,
                'precision_actuelle' => 68.9,
                'sensible_meteo' => true,
                'sensible_fatigue' => true,
                'sensible_age' => true,
                'audience_cible' => 'professionnel',
                'valeur_business' => 10,
                'criticite_erreur' => 9,
                'interpretabilite_requise' => true,
                'popularite_score' => 8,
                'maturite_niveau' => 8,
            ],
            [
                'nom' => 'Performance Surface',
                'nom_technique' => 'Surface-Specific Performance Prediction',
                'code' => 'surface_performance',
                'categorie_principale' => 'performance',
                'sous_categorie' => 'regression',
                'domaine_tennis' => 'joueur',
                'specialisation' => 'surface',
                'approche_ml' => 'regression',
                'niveau_difficulte' => 6,
                'horizon_prediction' => 'moyen_terme',
                'precision_cible' => 78.0,
                'precision_actuelle' => 76.3,
                'precision_dur' => 79.1,
                'precision_terre' => 82.4,
                'precision_gazon' => 68.7,
                'sensible_surface' => true,
                'sensible_forme' => true,
                'audience_cible' => 'expert',
                'valeur_business' => 7,
                'popularite_score' => 7,
                'maturite_niveau' => 8,
            ],
            [
                'nom' => 'Upset Probability',
                'nom_technique' => 'Upset Event Probability Assessment',
                'code' => 'upset_probability',
                'categorie_principale' => 'outcome',
                'sous_categorie' => 'binary',
                'domaine_tennis' => 'match',
                'specialisation' => 'anomaly',
                'approche_ml' => 'classification',
                'niveau_difficulte' => 8,
                'horizon_prediction' => 'immediate',
                'precision_cible' => 72.0,
                'precision_actuelle' => 69.8,
                'sensible_forme' => true,
                'sensible_pression' => true,
                'sensible_classement' => true,
                'audience_cible' => 'expert',
                'valeur_business' => 8,
                'popularite_score' => 6,
                'maturite_niveau' => 6,
            ],
            [
                'nom' => 'Impact Météo',
                'nom_technique' => 'Weather Impact Performance Modifier',
                'code' => 'weather_impact',
                'categorie_principale' => 'performance',
                'sous_categorie' => 'regression',
                'domaine_tennis' => 'match',
                'specialisation' => 'weather',
                'approche_ml' => 'regression',
                'niveau_difficulte' => 5,
                'horizon_prediction' => 'immediate',
                'precision_cible' => 68.0,
                'precision_actuelle' => 65.7,
                'sensible_meteo' => true,
                'sensible_surface' => true,
                'audience_cible' => 'professionnel',
                'valeur_business' => 6,
                'combinable' => true,
                'popularite_score' => 5,
                'maturite_niveau' => 7,
            ],
            [
                'nom' => 'Évolution Classement',
                'nom_technique' => 'Ranking Evolution Forecast',
                'code' => 'ranking_evolution',
                'categorie_principale' => 'performance',
                'sous_categorie' => 'time_series',
                'domaine_tennis' => 'joueur',
                'specialisation' => 'career',
                'approche_ml' => 'regression',
                'niveau_difficulte' => 9,
                'horizon_prediction' => 'long_terme',
                'precision_cible' => 55.0,
                'precision_actuelle' => 52.3,
                'marge_erreur_typique' => 35.0,
                'sensible_forme' => true,
                'sensible_age' => true,
                'sensible_classement' => true,
                'audience_cible' => 'expert',
                'valeur_business' => 7,
                'interpretabilite_requise' => true,
                'popularite_score' => 5,
                'maturite_niveau' => 5,
            ],
        ];

        foreach ($types as $type) {
            // Valeurs par défaut communes
            $type['actif'] = true;
            $type['monitoring_actif'] = true;
            $type['drift_detection'] = true;
            $type['validation_croisee'] = true;
            $type['conforme_rgpd'] = true;
            $type['date_creation_type'] = now();
            $type['version_actuelle'] = 1.0;
            $type['duree_validite'] = 24; // 24 heures par défaut
            $type['seuil_confiance_min'] = 0.6;
            $type['intervalle_confiance'] = 95.0;

            self::firstOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }

    /**
     * Obtenir les types par catégorie
     */
    public static function parCategorie()
    {
        return self::actifs()
            ->select('categorie_principale', 'nom', 'precision_actuelle', 'popularite_score')
            ->ordonnesParPerformance()
            ->get()
            ->groupBy('categorie_principale');
    }

    /**
     * Obtenir les métriques globales
     */
    public static function getMetriquesGlobales()
    {
        return [
            'nb_types_total' => self::count(),
            'nb_types_actifs' => self::actifs()->count(),
            'precision_moyenne' => self::avg('precision_actuelle'),
            'nb_performants' => self::performants()->count(),
            'nb_complexes' => self::complexes()->count(),
            'adoption_moyenne' => self::avg('adoption_rate'),
            'type_plus_populaire' => self::orderBy('popularite_score', 'desc')->first(),
            'type_plus_performant' => self::ordonnesParPerformance()->first(),
            'nb_predictions_total' => Prediction::count(),
        ];
    }

    // ===================================================================
    // METHODS
    // ===================================================================

    /**
     * Évaluer la performance du type de prédiction
     */
    public function evaluerPerformance($periodeDays = 30)
    {
        $predictions = $this->predictions()
            ->where('created_at', '>=', now()->subDays($periodeDays))
            ->whereNotNull('est_correcte')
            ->get();

        $totalPredictions = $predictions->count();
        $predictionsCorrectes = $predictions->where('est_correcte', true)->count();

        $precision = $totalPredictions > 0 ?
            round(($predictionsCorrectes / $totalPredictions) * 100, 2) : 0;

        // Métriques détaillées
        $confianceMoyenne = $predictions->avg('confiance') ?? 0;
        $ecartType = $this->calculerEcartType($predictions);

        // Performance par algorithme
        $performanceAlgos = $predictions->groupBy('algorithme_ia_id')
            ->map(function ($preds) {
                $correct = $preds->where('est_correcte', true)->count();

                return [
                    'precision' => round(($correct / $preds->count()) * 100, 2),
                    'confiance_moyenne' => $preds->avg('confiance'),
                ];
            });

        return [
            'periode' => $periodeDays.' jours',
            'total_predictions' => $totalPredictions,
            'precision_periode' => $precision,
            'evolution_vs_cible' => $precision - $this->precision_cible,
            'confiance_moyenne' => round($confianceMoyenne, 3),
            'ecart_type' => $ecartType,
            'performance_algorithmes' => $performanceAlgos,
            'calibration' => $this->calculerCalibration($predictions),
            'recommandations' => $this->genererRecommandationsPerformance($precision),
        ];
    }

    /**
     * Analyser l'adéquation avec un contexte
     */
    public function analyserAdequationContexte($contexte)
    {
        $score = 50; // Score de base
        $facteurs = [];

        // Surface
        if (isset($contexte['surface'])) {
            $precisionSurface = $this->{"precision_{$contexte['surface']}"};
            if ($precisionSurface) {
                $score += ($precisionSurface - 70) * 0.5;
                $facteurs[] = "Surface {$contexte['surface']}: {$precisionSurface}%";
            }
        }

        // Niveau joueurs
        if (isset($contexte['niveau']) && $contexte['niveau'] === 'top_100') {
            if ($this->precision_top_100) {
                $score += ($this->precision_top_100 - 70) * 0.3;
                $facteurs[] = "Top 100: {$this->precision_top_100}%";
            }
        }

        // Conditions météo
        if (isset($contexte['meteo']) && $this->sensible_meteo) {
            if ($contexte['meteo']['conditions_difficiles']) {
                $score -= 10;
                $facteurs[] = 'Sensible aux conditions météo difficiles';
            }
        }

        // Horizon temporel
        if (isset($contexte['horizon'])) {
            if ($contexte['horizon'] === $this->horizon_prediction) {
                $score += 15;
                $facteurs[] = 'Horizon temporel optimal';
            }
        }

        $score = max(0, min(100, $score));

        return [
            'score_adequation' => round($score, 1),
            'niveau' => $this->getNiveauAdequation($score),
            'facteurs_decisifs' => $facteurs,
            'recommandation' => $this->getRecommandationAdequation($score),
            'confiance_contexte' => $this->calculerConfianceContexte($contexte),
        ];
    }

    /**
     * Optimiser les paramètres du type
     */
    public function optimiserParametres()
    {
        if (! $this->auto_tuning) {
            return ['erreur' => 'Auto-tuning non activé'];
        }

        $optimisations = [];

        // Optimiser seuil de confiance
        $nouveauSeuil = $this->optimiserSeuilConfiance();
        if ($nouveauSeuil !== $this->seuil_confiance_min) {
            $optimisations[] = [
                'parametre' => 'seuil_confiance_min',
                'ancienne_valeur' => $this->seuil_confiance_min,
                'nouvelle_valeur' => $nouveauSeuil,
                'gain_estime' => $this->calculerGainSeuil($nouveauSeuil),
            ];
        }

        // Optimiser fréquence de mise à jour
        $nouvelleFrequence = $this->optimiserFrequenceMaj();
        if ($nouvelleFrequence !== $this->frequence_maj) {
            $optimisations[] = [
                'parametre' => 'frequence_maj',
                'ancienne_valeur' => $this->frequence_maj,
                'nouvelle_valeur' => $nouvelleFrequence,
                'impact_ressources' => $this->calculerImpactRessources($nouvelleFrequence),
            ];
        }

        return [
            'optimisations_proposees' => $optimisations,
            'gain_global_estime' => $this->calculerGainGlobal($optimisations),
            'impact_utilisateurs' => $this->evaluerImpactUtilisateurs($optimisations),
        ];
    }

    /**
     * Générer un rapport de recommandations
     */
    public function genererRecommandations()
    {
        $recommandations = [];

        // Performance
        if ($this->precision_actuelle < $this->precision_cible) {
            $recommandations['performance'][] = 'Améliorer la précision (actuel: '.
                $this->precision_actuelle.'%, cible: '.$this->precision_cible.'%)';
        }

        // Données
        if ($this->nb_matchs_min > 100) {
            $recommandations['donnees'][] = 'Réduire les exigences de données historiques';
        }

        // Complexité
        if ($this->niveau_difficulte >= 8 && $this->popularite_score <= 5) {
            $recommandations['usage'][] = 'Simplifier pour augmenter l\'adoption';
        }

        // Monitoring
        if (! $this->monitoring_actif) {
            $recommandations['technique'][] = 'Activer le monitoring continu';
        }

        // Explicabilité
        if ($this->criticite_erreur >= 7 && ! $this->interpretabilite_requise) {
            $recommandations['explicabilite'][] = 'Ajouter l\'explicabilité (criticité élevée)';
        }

        return $recommandations;
    }

    /**
     * Créer un ensemble de types compatibles
     */
    public function creerEnsemble($autresTypes)
    {
        if (! $this->combinable) {
            return ['erreur' => 'Type non combinable'];
        }

        $compatibles = [];
        $incompatibles = [];

        foreach ($autresTypes as $type) {
            if ($this->estCompatibleAvec($type)) {
                $compatibles[] = [
                    'type' => $type->nom,
                    'synergie' => $this->calculerSynergie($type),
                    'poids_recommande' => $this->calculerPoidsRecommande($type),
                ];
            } else {
                $incompatibles[] = [
                    'type' => $type->nom,
                    'raison' => $this->getRaisonIncompatibilite($type),
                ];
            }
        }

        return [
            'ensemble_possible' => ! empty($compatibles),
            'types_compatibles' => $compatibles,
            'types_incompatibles' => $incompatibles,
            'performance_estimee' => $this->estimerPerformanceEnsemble($compatibles),
            'methode_agregation' => $this->recommanderMethodeAgregation($compatibles),
        ];
    }

    // ===================================================================
    // METHODS PRIVÉES
    // ===================================================================

    private function getTauxSucces()
    {
        $total = $this->predictions()->whereNotNull('est_correcte')->count();
        if ($total === 0) {
            return 0;
        }

        $succes = $this->predictionsReussies()->count();

        return round(($succes / $total) * 100, 1);
    }

    private function calculerEcartType($predictions)
    {
        $confiances = $predictions->pluck('confiance')->toArray();
        if (empty($confiances)) {
            return 0;
        }

        $moyenne = array_sum($confiances) / count($confiances);
        $variances = array_map(function ($x) use ($moyenne) {
            return pow($x - $moyenne, 2);
        }, $confiances);

        return sqrt(array_sum($variances) / count($variances));
    }

    private function calculerCalibration($predictions)
    {
        // Calculer la calibration (Brier Score)
        $score = 0;
        foreach ($predictions as $pred) {
            $probabilite = $pred->confiance;
            $resultat = $pred->est_correcte ? 1 : 0;
            $score += pow($probabilite - $resultat, 2);
        }

        return $predictions->count() > 0 ? $score / $predictions->count() : 1;
    }

    private function genererRecommandationsPerformance($precision)
    {
        $recommandations = [];

        if ($precision < $this->precision_minimale) {
            $recommandations[] = 'Performance critique - Révision urgente requise';
        } elseif ($precision < $this->precision_cible) {
            $recommandations[] = 'Performance sous la cible - Optimisation recommandée';
        } else {
            $recommandations[] = 'Performance satisfaisante';
        }

        return $recommandations;
    }

    private function getNiveauAdequation($score)
    {
        if ($score >= 80) {
            return 'Excellent';
        }
        if ($score >= 65) {
            return 'Bon';
        }
        if ($score >= 50) {
            return 'Moyen';
        }
        if ($score >= 35) {
            return 'Faible';
        }

        return 'Inadéquat';
    }

    private function getRecommandationAdequation($score)
    {
        if ($score >= 65) {
            return 'Utilisation recommandée';
        }
        if ($score >= 50) {
            return 'Utilisation possible avec précautions';
        }

        return 'Utilisation déconseillée';
    }

    private function calculerConfianceContexte($contexte)
    {
        // Simuler calcul confiance selon contexte
        $base = 0.7;

        if (isset($contexte['donnees_qualite']) && $contexte['donnees_qualite'] >= 8) {
            $base += 0.1;
        }

        if (isset($contexte['historique_suffisant']) && $contexte['historique_suffisant']) {
            $base += 0.1;
        }

        return min(1.0, $base);
    }

    private function optimiserSeuilConfiance()
    {
        // Simuler optimisation du seuil
        $predictions = $this->predictions()->latest()->limit(1000)->get();
        $seuils = [0.5, 0.6, 0.7, 0.8, 0.9];
        $meilleurSeuil = $this->seuil_confiance_min;
        $meilleurScore = 0;

        foreach ($seuils as $seuil) {
            $score = $this->evaluerSeuilConfiance($predictions, $seuil);
            if ($score > $meilleurScore) {
                $meilleurScore = $score;
                $meilleurSeuil = $seuil;
            }
        }

        return $meilleurSeuil;
    }

    private function evaluerSeuilConfiance($predictions, $seuil)
    {
        $valides = $predictions->where('confiance', '>=', $seuil);
        if ($valides->count() === 0) {
            return 0;
        }

        $precision = $valides->where('est_correcte', true)->count() / $valides->count();
        $couverture = $valides->count() / $predictions->count();

        return $precision * 0.7 + $couverture * 0.3; // Pondération
    }

    private function optimiserFrequenceMaj()
    {
        // Basé sur la volatilité et la demande
        if ($this->volatilite >= 8) {
            return 1;
        } // Hourly
        if ($this->volatilite >= 6) {
            return 4;
        } // Every 4 hours
        if ($this->volatilite >= 4) {
            return 12;
        } // Twice daily

        return 24; // Daily
    }

    private function calculerGainSeuil($nouveauSeuil)
    {
        return abs($nouveauSeuil - $this->seuil_confiance_min) * 10; // Simulé
    }

    private function calculerImpactRessources($frequence)
    {
        return round(24 / $frequence * $this->cout_calcul, 1);
    }

    private function calculerGainGlobal($optimisations)
    {
        return array_sum(array_column($optimisations, 'gain_estime'));
    }

    private function evaluerImpactUtilisateurs($optimisations)
    {
        return count($optimisations) > 2 ? 'Élevé' : 'Faible';
    }

    private function estCompatibleAvec($autreType)
    {
        // Vérifier compatibilité
        if ($this->conflits_types && in_array($autreType->code, $this->conflits_types)) {
            return false;
        }

        return $this->domaine_tennis === $autreType->domaine_tennis ||
            $this->types_compatibles && in_array($autreType->code, $this->types_compatibles);
    }

    private function calculerSynergie($autreType)
    {
        if ($this->synergie_types && in_array($autreType->code, $this->synergie_types)) {
            return 'Élevée';
        }

        return $this->categorie_principale !== $autreType->categorie_principale ? 'Modérée' : 'Faible';
    }

    private function calculerPoidsRecommande($autreType)
    {
        $precision1 = $this->precision_actuelle ?? 50;
        $precision2 = $autreType->precision_actuelle ?? 50;

        $total = $precision1 + $precision2;

        return round($precision1 / $total, 2);
    }

    private function getRaisonIncompatibilite($autreType)
    {
        if ($this->conflits_types && in_array($autreType->code, $this->conflits_types)) {
            return 'Conflit explicite détecté';
        }

        return 'Domaines incompatibles';
    }

    private function estimerPerformanceEnsemble($compatibles)
    {
        if (empty($compatibles)) {
            return 0;
        }

        $performances = array_column($compatibles, 'synergie');
        $bonus = count($performances) * 2; // Bonus ensemble

        return min(100, ($this->precision_actuelle ?? 50) + $bonus);
    }

    private function recommanderMethodeAgregation($compatibles)
    {
        if (count($compatibles) <= 2) {
            return 'weighted_average';
        }
        if (count($compatibles) <= 4) {
            return 'voting';
        }

        return 'stacking';
    }

    // ===================================================================
    // VALIDATION RULES
    // ===================================================================

    public static function validationRules()
    {
        return [
            'nom' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:type_predictions,code',
            'categorie_principale' => 'required|in:outcome,score,performance,risk,behavior',
            'domaine_tennis' => 'required|in:match,joueur,tournoi,saison,carriere',
            'horizon_prediction' => 'required|in:immediate,court_terme,moyen_terme,long_terme',
            'niveau_difficulte' => 'required|integer|min:1|max:10',
            'precision_cible' => 'nullable|numeric|min:0|max:100',
            'audience_cible' => 'required|in:public,expert,professionnel,paris',
        ];
    }

    // ===================================================================
    // BOOT METHODS
    // ===================================================================

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($type) {
            // Auto-calculs
            if (! $type->ordre_affichage) {
                $type->ordre_affichage = ($type->popularite_score ?? 5) * 10;
            }

            // Seuils par défaut selon difficulté
            if (! $type->precision_minimale && $type->niveau_difficulte) {
                $minimales = [1 => 90, 2 => 85, 3 => 80, 4 => 75, 5 => 70,
                    6 => 65, 7 => 60, 8 => 55, 9 => 50, 10 => 45];
                $type->precision_minimale = $minimales[$type->niveau_difficulte];
            }

            // Valeurs par défaut
            if ($type->actif === null) {
                $type->actif = true;
            }
            if (! $type->version_actuelle) {
                $type->version_actuelle = 1.0;
            }
            if (! $type->intervalle_confiance) {
                $type->intervalle_confiance = 95.0;
            }
        });

        static::created(function ($type) {
            \Log::info("Nouveau type de prédiction créé: {$type->nom} ({$type->code})");
        });
    }
}
