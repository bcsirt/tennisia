<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class AlgorithmeIA extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'algorithme_ias';

    protected $fillable = [
        // Identification et métadonnées
        'nom',
        'nom_technique',          // Nom technique précis
        'code',                   // Code unique pour l'algorithme
        'version',                // Version de l'algorithme
        'description',
        'description_technique',  // Description technique détaillée
        'auteur',                // Créateur de l'algorithme
        'equipe_developpement',   // Équipe de développement

        // Classification algorithme
        'type_algorithme',        // 'neural_network', 'random_forest', 'gradient_boosting', etc.
        'sous_type',              // 'deep_learning', 'ensemble', 'linear', etc.
        'famille_ml',             // 'supervised', 'unsupervised', 'reinforcement'
        'approche',               // 'classification', 'regression', 'clustering'
        'complexite_niveau',      // 1-10 niveau de complexité

        // Spécialisation tennis
        'specialisation_tennis',  // 'match_outcome', 'score_prediction', 'injury_risk', etc.
        'surfaces_optimise',      // JSON des surfaces optimisées
        'conditions_optimise',    // JSON des conditions optimisées
        'styles_jeu_optimise',    // JSON des styles de jeu optimisés
        'niveau_joueurs_cible',   // 'amateur', 'semi_pro', 'pro', 'elite'

        // Architecture et paramètres
        'architecture',           // JSON de l'architecture du modèle
        'hyperparametres',        // JSON des hyperparamètres
        'parametres_entrainement', // JSON des paramètres d'entraînement
        'features_utilisees',     // JSON des features utilisées
        'features_importantes',   // JSON des features les plus importantes
        'preprocessing_pipeline', // JSON du pipeline de preprocessing

        // Performance et métriques
        'precision_globale',      // Précision globale (%)
        'precision_validation',   // Précision sur validation (%)
        'precision_test',         // Précision sur test (%)
        'rappel',                 // Recall/Sensibilité (%)
        'f1_score',               // Score F1 (%)
        'auc_roc',               // Area Under Curve ROC
        'log_loss',              // Log loss
        'brier_score',           // Brier score pour calibration

        // Performance par contexte tennis
        'precision_dur',          // Précision sur dur (%)
        'precision_terre',        // Précision sur terre (%)
        'precision_gazon',        // Précision sur gazon (%)
        'precision_indoor',       // Précision indoor (%)
        'precision_top_100',      // Précision matchs top 100
        'precision_qualifs',      // Précision qualifications
        'precision_finales',      // Précision phases finales

        // Données d'entraînement
        'taille_dataset',         // Nombre d'exemples d'entraînement
        'periode_donnees_debut',  // Début période données
        'periode_donnees_fin',    // Fin période données
        'nb_features',            // Nombre de features
        'nb_matchs_entrainement', // Nombre de matchs d'entraînement
        'nb_joueurs_couverts',    // Nombre de joueurs couverts
        'balance_classes',        // Équilibre des classes (%)

        // Temps et ressources
        'temps_entrainement',     // Temps d'entraînement (minutes)
        'temps_inference',        // Temps d'inférence moyen (ms)
        'memoire_requise',        // Mémoire requise (MB)
        'cpu_requis',            // CPU requis
        'gpu_requis',            // GPU requis
        'scalabilite',           // Niveau de scalabilité 1-10

        // Validation et testing
        'methode_validation',     // 'k_fold', 'time_series', 'monte_carlo'
        'nb_folds_validation',    // Nombre de folds
        'validation_croisee',     // Validation croisée utilisée
        'test_ab_actif',         // A/B test en cours
        'pourcentage_trafic',    // % du trafic utilisé
        'baseline_comparaison',   // Algorithme de référence

        // Monitoring et drift
        'drift_detection',        // Détection de drift activée
        'seuil_drift',           // Seuil de détection drift
        'derniere_detection_drift', // Dernière détection drift
        'stabilite_score',        // Score de stabilité 1-10
        'robustesse_score',       // Score de robustesse 1-10

        // Explicabilité et interprétabilité
        'interpretable',          // Algorithme interprétable
        'shap_active',           // SHAP values calculées
        'lime_active',           // LIME activé
        'feature_importance',     // JSON importance des features
        'explications_disponibles', // Types d'explications disponibles
        'complexite_explicabilite', // Complexité explicabilité 1-10

        // Biais et équité
        'biais_detecte',         // Biais détecté
        'types_biais',           // JSON des types de biais
        'equite_score',          // Score d'équité 1-10
        'fairness_metrics',      // JSON métriques équité
        'correction_biais',      // Correction de biais appliquée

        // Déploiement et production
        'statut_deploiement',    // 'dev', 'staging', 'production', 'retired'
        'date_deploiement',      // Date de déploiement
        'date_derniere_maj',     // Dernière mise à jour
        'version_production',    // Version en production
        'rollback_possible',     // Rollback possible
        'canary_deployment',     // Déploiement canary actif

        // Optimisation et amélioration
        'auto_tuning',           // Auto-tuning activé
        'hyperopt_actif',        // Optimisation hyperparamètres active
        'derniere_optimisation', // Dernière optimisation
        'nb_optimisations',      // Nombre d'optimisations effectuées
        'amelioration_continue', // Amélioration continue activée

        // Ensemble et combinaison
        'est_ensemble',          // Fait partie d'un ensemble
        'algorithmes_ensemble',  // JSON des algorithmes dans l'ensemble
        'poids_ensemble',        // Poids dans l'ensemble
        'methode_combinaison',   // Méthode de combinaison
        'meta_learner',          // Meta-learner utilisé

        // Feedback et apprentissage
        'feedback_utilisateurs', // Feedback utilisateurs activé
        'apprentissage_online',  // Apprentissage en ligne
        'mise_a_jour_auto',     // Mise à jour automatique
        'frequence_retrain',    // Fréquence de re-entraînement
        'derniere_retrain',     // Dernier re-entraînement

        // Business et impact
        'impact_business',       // Impact business estimé
        'roi_estime',           // ROI estimé
        'cout_operationnel',    // Coût opérationnel
        'gain_precision',       // Gain de précision vs baseline
        'satisfaction_utilisateurs', // Satisfaction utilisateurs 1-10

        // Sécurité et conformité
        'niveau_securite',       // Niveau sécurité 1-10
        'chiffrement_actif',    // Chiffrement activé
        'audit_trail',          // Trail d'audit
        'conformite_rgpd',      // Conformité RGPD
        'anonymisation',        // Anonymisation des données

        // Documentation et maintenance
        'documentation_url',     // URL documentation
        'code_repository',       // Repository du code
        'tests_unitaires',       // Tests unitaires présents
        'couverture_tests',      // Couverture de tests (%)
        'mainteneur_principal',  // Mainteneur principal
        'support_actif',         // Support actif

        // Limites et contraintes
        'limites_connues',       // JSON des limites connues
        'contraintes_usage',     // JSON des contraintes d'usage
        'cas_non_supportes',     // JSON des cas non supportés
        'donnees_requises_min',  // Données minimum requises
        'seuil_confiance_min',   // Seuil confiance minimum

        // Métadonnées système
        'tags',                  // JSON tags pour catégorisation
        'priorite',              // Priorité 1-10
        'criticite',             // Criticité pour le système
        'environnement_dev',     // Environnement de développement
        'dependances',           // JSON des dépendances
        'actif'
    ];

    protected $casts = [
        // JSON
        'surfaces_optimise' => 'json',
        'conditions_optimise' => 'json',
        'styles_jeu_optimise' => 'json',
        'architecture' => 'json',
        'hyperparametres' => 'json',
        'parametres_entrainement' => 'json',
        'features_utilisees' => 'json',
        'features_importantes' => 'json',
        'preprocessing_pipeline' => 'json',
        'types_biais' => 'json',
        'fairness_metrics' => 'json',
        'explications_disponibles' => 'json',
        'feature_importance' => 'json',
        'algorithmes_ensemble' => 'json',
        'limites_connues' => 'json',
        'contraintes_usage' => 'json',
        'cas_non_supportes' => 'json',
        'tags' => 'json',
        'dependances' => 'json',

        // Décimaux
        'version' => 'decimal:2',
        'precision_globale' => 'decimal:2',
        'precision_validation' => 'decimal:2',
        'precision_test' => 'decimal:2',
        'rappel' => 'decimal:2',
        'f1_score' => 'decimal:2',
        'auc_roc' => 'decimal:3',
        'log_loss' => 'decimal:4',
        'brier_score' => 'decimal:4',
        'precision_dur' => 'decimal:2',
        'precision_terre' => 'decimal:2',
        'precision_gazon' => 'decimal:2',
        'precision_indoor' => 'decimal:2',
        'precision_top_100' => 'decimal:2',
        'precision_qualifs' => 'decimal:2',
        'precision_finales' => 'decimal:2',
        'balance_classes' => 'decimal:2',
        'temps_inference' => 'decimal:2',
        'seuil_drift' => 'decimal:3',
        'poids_ensemble' => 'decimal:3',
        'gain_precision' => 'decimal:2',
        'couverture_tests' => 'decimal:2',
        'seuil_confiance_min' => 'decimal:3',

        // Entiers
        'complexite_niveau' => 'integer',
        'taille_dataset' => 'integer',
        'nb_features' => 'integer',
        'nb_matchs_entrainement' => 'integer',
        'nb_joueurs_couverts' => 'integer',
        'temps_entrainement' => 'integer',
        'memoire_requise' => 'integer',
        'scalabilite' => 'integer',
        'nb_folds_validation' => 'integer',
        'pourcentage_trafic' => 'integer',
        'stabilite_score' => 'integer',
        'robustesse_score' => 'integer',
        'complexite_explicabilite' => 'integer',
        'equite_score' => 'integer',
        'nb_optimisations' => 'integer',
        'satisfaction_utilisateurs' => 'integer',
        'niveau_securite' => 'integer',
        'priorite' => 'integer',
        'criticite' => 'integer',

        // Booléens
        'test_ab_actif' => 'boolean',
        'drift_detection' => 'boolean',
        'interpretable' => 'boolean',
        'shap_active' => 'boolean',
        'lime_active' => 'boolean',
        'biais_detecte' => 'boolean',
        'rollback_possible' => 'boolean',
        'canary_deployment' => 'boolean',
        'auto_tuning' => 'boolean',
        'hyperopt_actif' => 'boolean',
        'amelioration_continue' => 'boolean',
        'est_ensemble' => 'boolean',
        'feedback_utilisateurs' => 'boolean',
        'apprentissage_online' => 'boolean',
        'mise_a_jour_auto' => 'boolean',
        'chiffrement_actif' => 'boolean',
        'audit_trail' => 'boolean',
        'conformite_rgpd' => 'boolean',
        'anonymisation' => 'boolean',
        'tests_unitaires' => 'boolean',
        'support_actif' => 'boolean',
        'correction_biais' => 'boolean',
        'validation_croisee' => 'boolean',
        'actif' => 'boolean',

        // Dates
        'periode_donnees_debut' => 'date',
        'periode_donnees_fin' => 'date',
        'date_deploiement' => 'date',
        'date_derniere_maj' => 'datetime',
        'derniere_detection_drift' => 'datetime',
        'derniere_optimisation' => 'datetime',
        'derniere_retrain' => 'datetime'
    ];

    protected $appends = [
        'niveau_performance',
        'statut_sante',
        'score_fiabilite',
        'recommandations_amelioration',
        'contextes_optimaux',
        'metriques_cles',
        'impact_global'
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function configurations()
    {
        return $this->hasMany(ConfigurationIA::class, 'algorithme_ia_id');
    }

    public function configurationActive()
    {
        return $this->hasOne(ConfigurationIA::class, 'algorithme_ia_id')
            ->where('est_active', true);
    }

    public function predictions()
    {
        return $this->hasMany(Prediction::class, 'algorithme_ia_id');
    }

    public function predictionsReussies()
    {
        return $this->hasMany(Prediction::class, 'algorithme_ia_id')
            ->where('est_correcte', true);
    }

    public function evaluations()
    {
        return $this->hasMany(EvaluationAlgorithme::class, 'algorithme_ia_id');
    }

    public function derniereEvaluation()
    {
        return $this->hasOne(EvaluationAlgorithme::class, 'algorithme_ia_id')
            ->latest();
    }

    public function experimentations()
    {
        return $this->hasMany(ExperimentationIA::class, 'algorithme_ia_id');
    }

    public function logsPerformance()
    {
        return $this->hasMany(LogPerformanceIA::class, 'algorithme_ia_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(FeedbackAlgorithme::class, 'algorithme_ia_id');
    }

    public function ensembleParent()
    {
        return $this->belongsTo(EnsembleIA::class, 'ensemble_ia_id');
    }

    public function membreEnsembles()
    {
        return $this->belongsToMany(EnsembleIA::class, 'ensemble_algorithmes')
            ->withPivot(['poids', 'role', 'actif']);
    }

    // ===================================================================
    // ACCESSORS
    // ===================================================================

    public function getNiveauPerformanceAttribute()
    {
        $score = $this->precision_globale ?? 0;

        if ($score >= 85) return 'Excellent';
        if ($score >= 75) return 'Très bon';
        if ($score >= 65) return 'Bon';
        if ($score >= 55) return 'Moyen';
        if ($score >= 45) return 'Faible';
        return 'Très faible';
    }

    public function getStatutSanteAttribute()
    {
        $facteurs = [];
        $score = 100;

        // Drift détecté
        if ($this->derniere_detection_drift &&
            $this->derniere_detection_drift > now()->subDays(7)) {
            $score -= 30;
            $facteurs[] = 'Drift détecté récemment';
        }

        // Performance en baisse
        if ($this->precision_globale < ($this->precision_validation - 5)) {
            $score -= 20;
            $facteurs[] = 'Dégradation performance';
        }

        // Biais détecté
        if ($this->biais_detecte) {
            $score -= 15;
            $facteurs[] = 'Biais détecté';
        }

        // Pas de maintenance récente
        if (!$this->date_derniere_maj ||
            $this->date_derniere_maj < now()->subMonths(3)) {
            $score -= 10;
            $facteurs[] = 'Maintenance requise';
        }

        if ($score >= 90) return ['statut' => 'Excellent', 'facteurs' => []];
        if ($score >= 70) return ['statut' => 'Bon', 'facteurs' => $facteurs];
        if ($score >= 50) return ['statut' => 'Attention', 'facteurs' => $facteurs];
        return ['statut' => 'Critique', 'facteurs' => $facteurs];
    }

    public function getScoreFiabiliteAttribute()
    {
        $composantes = [
            'precision' => ($this->precision_globale ?? 0) / 100,
            'stabilite' => ($this->stabilite_score ?? 5) / 10,
            'robustesse' => ($this->robustesse_score ?? 5) / 10,
            'historique' => $this->getScoreHistorique(),
            'tests' => ($this->couverture_tests ?? 0) / 100
        ];

        return round(array_sum($composantes) / count($composantes) * 100, 1);
    }

    public function getRecommandationsAmeliorationAttribute()
    {
        $recommandations = [];

        // Basé sur la performance
        if ($this->precision_globale < 70) {
            $recommandations[] = 'Améliorer le preprocessing des données';
            $recommandations[] = 'Optimiser les hyperparamètres';
        }

        // Basé sur l\'explicabilité
        if (!$this->interpretable && !$this->shap_active) {
            $recommandations[] = 'Activer SHAP pour l\'explicabilité';
        }

        // Basé sur le drift
        if (!$this->drift_detection) {
            $recommandations[] = 'Activer la détection de drift';
        }

        // Basé sur les tests
        if (($this->couverture_tests ?? 0) < 80) {
            $recommandations[] = 'Améliorer la couverture de tests';
        }

        // Basé sur la maintenance
        if (!$this->date_derniere_maj ||
            $this->date_derniere_maj < now()->subMonths(2)) {
            $recommandations[] = 'Maintenance et mise à jour requises';
        }

        return $recommandations;
    }

    public function getContextesOptimauxAttribute()
    {
        $contextes = [];

        // Surfaces
        $surfaces = [];
        if (($this->precision_dur ?? 0) >= 75) $surfaces[] = 'Dur';
        if (($this->precision_terre ?? 0) >= 75) $surfaces[] = 'Terre battue';
        if (($this->precision_gazon ?? 0) >= 75) $surfaces[] = 'Gazon';
        if (($this->precision_indoor ?? 0) >= 75) $surfaces[] = 'Indoor';

        if (!empty($surfaces)) $contextes['surfaces'] = $surfaces;

        // Niveaux de joueurs
        if (($this->precision_top_100 ?? 0) >= 75) {
            $contextes['niveau'] = 'Top 100';
        }

        // Phases de tournoi
        if (($this->precision_finales ?? 0) >= 75) {
            $contextes['phases'] = 'Phases finales';
        }

        // Conditions spécialisées
        if ($this->conditions_optimise) {
            $contextes['conditions'] = $this->conditions_optimise;
        }

        return $contextes;
    }

    public function getMetriquesClesAttribute()
    {
        return [
            'precision' => ($this->precision_globale ?? 0) . '%',
            'f1_score' => ($this->f1_score ?? 0) . '%',
            'auc_roc' => $this->auc_roc ?? 0,
            'temps_inference' => ($this->temps_inference ?? 0) . 'ms',
            'fiabilite' => $this->score_fiabilite . '%',
            'statut' => $this->statut_sante['statut'],
            'predictions_total' => $this->predictions()->count(),
            'taux_succes' => $this->getTauxSucces() . '%'
        ];
    }

    public function getImpactGlobalAttribute()
    {
        $score = 0;

        // Impact performance
        $score += ($this->precision_globale ?? 0) * 0.4;

        // Impact business
        $score += ($this->satisfaction_utilisateurs ?? 5) * 10 * 0.2;

        // Impact utilisation
        $nbPredictions = $this->predictions()->count();
        $scoreUtilisation = min(100, $nbPredictions / 100); // Normalisé sur 10000 prédictions
        $score += $scoreUtilisation * 0.3;

        // Impact fiabilité
        $score += $this->score_fiabilite * 0.1;

        return round($score, 1);
    }

    // ===================================================================
    // SCOPES
    // ===================================================================

    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    public function scopeEnProduction($query)
    {
        return $query->where('statut_deploiement', 'production');
    }

    public function scopeEnDeveloppement($query)
    {
        return $query->where('statut_deploiement', 'dev');
    }

    public function scopePerformants($query, $seuilPrecision = 70)
    {
        return $query->where('precision_globale', '>=', $seuilPrecision);
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type_algorithme', $type);
    }

    public function scopeParSpecialisation($query, $specialisation)
    {
        return $query->where('specialisation_tennis', $specialisation);
    }

    public function scopeInterpretables($query)
    {
        return $query->where('interpretable', true)
            ->orWhere('shap_active', true);
    }

    public function scopeEnsembles($query)
    {
        return $query->where('est_ensemble', true);
    }

    public function scopeAvecDrift($query)
    {
        return $query->where('derniere_detection_drift', '>', now()->subDays(7));
    }

    public function scopeNecessitentMaintenance($query)
    {
        return $query->where(function($q) {
            $q->where('date_derniere_maj', '<', now()->subMonths(2))
                ->orWhere('precision_globale', '<', 60)
                ->orWhere('biais_detecte', true);
        });
    }

    public function scopeOptimisesPour($query, $contexte, $valeur)
    {
        switch ($contexte) {
            case 'surface':
                return $query->whereJsonContains('surfaces_optimise', $valeur);
            case 'style':
                return $query->whereJsonContains('styles_jeu_optimise', $valeur);
            case 'condition':
                return $query->whereJsonContains('conditions_optimise', $valeur);
            default:
                return $query;
        }
    }

    public function scopeAvecTestsAB($query)
    {
        return $query->where('test_ab_actif', true);
    }

    public function scopeOrdonnesParPerformance($query)
    {
        return $query->orderBy('precision_globale', 'desc')
            ->orderBy('f1_score', 'desc')
            ->orderBy('auc_roc', 'desc');
    }

    public function scopeRecherche($query, $terme)
    {
        return $query->where(function($q) use ($terme) {
            $q->where('nom', 'LIKE', "%{$terme}%")
                ->orWhere('code', 'LIKE', "%{$terme}%")
                ->orWhere('type_algorithme', 'LIKE', "%{$terme}%")
                ->orWhere('specialisation_tennis', 'LIKE', "%{$terme}%");
        });
    }

    // ===================================================================
    // STATIC METHODS
    // ===================================================================

    /**
     * Créer les algorithmes IA tennis standard
     */
    public static function creerAlgorithmesStandard()
    {
        $algorithmes = [
            [
                'nom' => 'TennisNet Pro',
                'nom_technique' => 'Deep Neural Network for Tennis Match Prediction',
                'code' => 'tennisnet_pro',
                'version' => 2.1,
                'type_algorithme' => 'neural_network',
                'sous_type' => 'deep_learning',
                'famille_ml' => 'supervised',
                'approche' => 'classification',
                'specialisation_tennis' => 'match_outcome',
                'surfaces_optimise' => ['dur', 'terre', 'gazon'],
                'niveau_joueurs_cible' => 'pro',
                'precision_globale' => 84.2,
                'precision_validation' => 82.8,
                'f1_score' => 83.5,
                'auc_roc' => 0.891,
                'nb_features' => 127,
                'taille_dataset' => 45000,
                'interpretable' => false,
                'shap_active' => true,
                'statut_deploiement' => 'production',
                'complexite_niveau' => 8,
                'priorite' => 10
            ],
            [
                'nom' => 'Surface Specialist',
                'nom_technique' => 'Random Forest Surface-Adapted Predictor',
                'code' => 'surface_specialist',
                'version' => 1.5,
                'type_algorithme' => 'random_forest',
                'sous_type' => 'ensemble',
                'famille_ml' => 'supervised',
                'approche' => 'classification',
                'specialisation_tennis' => 'surface_performance',
                'surfaces_optimise' => ['terre'],
                'niveau_joueurs_cible' => 'pro',
                'precision_globale' => 79.6,
                'precision_terre' => 87.3,
                'precision_dur' => 75.1,
                'f1_score' => 78.9,
                'auc_roc' => 0.832,
                'nb_features' => 89,
                'taille_dataset' => 28000,
                'interpretable' => true,
                'feature_importance' => [
                    'elo_surface' => 0.23,
                    'forme_recente' => 0.18,
                    'h2h_surface' => 0.15
                ],
                'statut_deploiement' => 'production',
                'complexite_niveau' => 6,
                'priorite' => 8
            ],
            [
                'nom' => 'Injury Risk Predictor',
                'nom_technique' => 'Gradient Boosting Injury Risk Assessment',
                'code' => 'injury_risk_gb',
                'version' => 1.2,
                'type_algorithme' => 'gradient_boosting',
                'sous_type' => 'ensemble',
                'famille_ml' => 'supervised',
                'approche' => 'regression',
                'specialisation_tennis' => 'injury_risk',
                'conditions_optimise' => ['chaleur_extreme', 'humidite_elevee'],
                'niveau_joueurs_cible' => 'pro',
                'precision_globale' => 76.8,
                'rappel' => 82.1,
                'f1_score' => 79.3,
                'nb_features' => 156,
                'taille_dataset' => 15000,
                'interpretable' => true,
                'lime_active' => true,
                'statut_deploiement' => 'production',
                'complexite_niveau' => 7,
                'priorite' => 9
            ],
            [
                'nom' => 'Score Predictor Elite',
                'nom_technique' => 'LSTM Score Progression Model',
                'code' => 'score_lstm',
                'version' => 1.8,
                'type_algorithme' => 'lstm',
                'sous_type' => 'deep_learning',
                'famille_ml' => 'supervised',
                'approche' => 'regression',
                'specialisation_tennis' => 'score_prediction',
                'surfaces_optimise' => ['dur', 'indoor'],
                'niveau_joueurs_cible' => 'elite',
                'precision_globale' => 71.4,
                'precision_finales' => 78.9,
                'precision_top_100' => 74.2,
                'f1_score' => 72.1,
                'nb_features' => 203,
                'taille_dataset' => 22000,
                'temps_inference' => 45.2,
                'interpretable' => false,
                'shap_active' => true,
                'statut_deploiement' => 'staging',
                'complexite_niveau' => 9,
                'priorite' => 7
            ],
            [
                'nom' => 'Weather Impact Analyzer',
                'nom_technique' => 'Support Vector Machine Weather Adaptation',
                'code' => 'weather_svm',
                'version' => 1.0,
                'type_algorithme' => 'svm',
                'sous_type' => 'kernel',
                'famille_ml' => 'supervised',
                'approche' => 'classification',
                'specialisation_tennis' => 'weather_impact',
                'conditions_optimise' => ['vent_fort', 'temperature_extreme', 'humidite'],
                'niveau_joueurs_cible' => 'pro',
                'precision_globale' => 68.9,
                'rappel' => 71.2,
                'f1_score' => 70.0,
                'nb_features' => 67,
                'taille_dataset' => 18000,
                'interpretable' => true,
                'statut_deploiement' => 'production',
                'complexite_niveau' => 5,
                'priorite' => 6
            ],
            [
                'nom' => 'Ensemble Master',
                'nom_technique' => 'Meta-Learning Ensemble Predictor',
                'code' => 'ensemble_master',
                'version' => 3.0,
                'type_algorithme' => 'ensemble',
                'sous_type' => 'meta_learning',
                'famille_ml' => 'supervised',
                'approche' => 'classification',
                'specialisation_tennis' => 'match_outcome',
                'est_ensemble' => true,
                'algorithmes_ensemble' => ['tennisnet_pro', 'surface_specialist', 'weather_svm'],
                'methode_combinaison' => 'weighted_voting',
                'precision_globale' => 86.7,
                'precision_validation' => 85.1,
                'f1_score' => 85.9,
                'auc_roc' => 0.912,
                'statut_deploiement' => 'production',
                'complexite_niveau' => 10,
                'priorite' => 10
            ]
        ];

        foreach ($algorithmes as $algo) {
            // Valeurs par défaut
            $algo['actif'] = true;
            $algo['drift_detection'] = true;
            $algo['auto_tuning'] = true;
            $algo['feedback_utilisateurs'] = true;
            $algo['conformite_rgpd'] = true;
            $algo['tests_unitaires'] = true;
            $algo['support_actif'] = true;
            $algo['date_derniere_maj'] = now();

            self::firstOrCreate(
                ['code' => $algo['code']],
                $algo
            );
        }
    }

    /**
     * Obtenir les algorithmes par spécialisation
     */
    public static function parSpecialisation()
    {
        return self::actifs()
            ->select('specialisation_tennis', 'nom', 'precision_globale', 'statut_deploiement')
            ->ordonnesParPerformance()
            ->get()
            ->groupBy('specialisation_tennis');
    }

    /**
     * Obtenir les métriques globales
     */
    public static function getMetriquesGlobales()
    {
        return [
            'nb_algorithmes_total' => self::count(),
            'nb_en_production' => self::enProduction()->count(),
            'precision_moyenne' => self::avg('precision_globale'),
            'nb_avec_drift' => self::avecDrift()->count(),
            'nb_necessitent_maintenance' => self::necessitentMaintenance()->count(),
            'nb_predictions_total' => Prediction::count(),
            'algorithme_plus_performant' => self::ordonnesParPerformance()->first(),
            'algorithme_plus_utilise' => self::withCount('predictions')
                ->orderBy('predictions_count', 'desc')
                ->first()
        ];
    }

    // ===================================================================
    // METHODS
    // ===================================================================

    /**
     * Évaluer les performances de l'algorithme
     */
    public function evaluerPerformances($periodeDays = 30)
    {
        $predictions = $this->predictions()
            ->where('created_at', '>=', now()->subDays($periodeDays))
            ->whereNotNull('est_correcte')
            ->get();

        $totalPredictions = $predictions->count();
        $predictionsCorrectes = $predictions->where('est_correcte', true)->count();

        $precision = $totalPredictions > 0 ?
            round(($predictionsCorrectes / $totalPredictions) * 100, 2) : 0;

        // Calculer métriques par contexte
        $metriquesContexte = [];

        // Par surface
        foreach (['dur', 'terre', 'gazon'] as $surface) {
            $predictionsSurface = $predictions->filter(function($p) use ($surface) {
                return $p->match?->tournoi?->surface?->code === $surface;
            });

            if ($predictionsSurface->count() > 0) {
                $correctesSurface = $predictionsSurface->where('est_correcte', true)->count();
                $metriquesContexte['surfaces'][$surface] = round(
                    ($correctesSurface / $predictionsSurface->count()) * 100, 2
                );
            }
        }

        return [
            'periode' => $periodeDays . ' jours',
            'total_predictions' => $totalPredictions,
            'precision_periode' => $precision,
            'evolution_precision' => $this->calculerEvolutionPrecision($precision),
            'metriques_contexte' => $metriquesContexte,
            'confiance_moyenne' => $predictions->avg('confiance') ?? 0,
            'drift_detecte' => $this->detecterDrift($predictions),
            'recommandations' => $this->genererRecommandations($precision, $metriquesContexte)
        ];
    }

    /**
     * Optimiser les hyperparamètres
     */
    public function optimiserHyperparametres($iterations = 50)
    {
        if (!$this->hyperopt_actif) {
            return ['erreur' => 'Optimisation non activée pour cet algorithme'];
        }

        // Simulation d'optimisation bayésienne
        $bestScore = $this->precision_globale;
        $bestParams = $this->hyperparametres;

        for ($i = 0; $i < $iterations; $i++) {
            $nouveauxParams = $this->genererNouveauxParams($bestParams);
            $score = $this->evaluerAvecParams($nouveauxParams);

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestParams = $nouveauxParams;
            }
        }

        return [
            'amelioration' => $bestScore - $this->precision_globale,
            'nouveaux_params' => $bestParams,
            'score_optimal' => $bestScore,
            'nb_iterations' => $iterations,
            'mise_a_jour_recommandee' => $bestScore > $this->precision_globale + 1
        ];
    }

    /**
     * Générer une explication de prédiction
     */
    public function expliquerPrediction($prediction)
    {
        if (!$this->interpretable && !$this->shap_active && !$this->lime_active) {
            return ['erreur' => 'Algorithme non explicable'];
        }

        $explication = [
            'prediction' => $prediction->resultat_predit,
            'confiance' => $prediction->confiance,
            'algorithme' => $this->nom
        ];

        // SHAP values si disponibles
        if ($this->shap_active && $this->feature_importance) {
            $explication['feature_importance'] = $this->feature_importance;
            $explication['facteurs_decisifs'] = $this->getFacteursDecisifs($prediction);
        }

        // Explication simplifiée
        if ($this->interpretable) {
            $explication['regles_appliquees'] = $this->getReglesAppliquees($prediction);
        }

        // Contexte tennis
        $explication['contexte_tennis'] = $this->getContexteTennis($prediction);

        return $explication;
    }

    /**
     * Détecter et corriger les biais
     */
    public function detecterBiais()
    {
        $predictions = $this->predictions()->with('match.joueur1', 'match.joueur2')->get();
        $biais = [];

        // Biais par nationalité
        $predictionsPourJoueursPays = $predictions->groupBy(function($p) {
            return $p->match->joueur1->pays_id;
        });

        foreach ($predictionsPourJoueursPays as $paysId => $preds) {
            if ($preds->count() >= 50) { // Minimum pour analyse statistique
                $tauxSucces = $preds->where('est_correcte', true)->count() / $preds->count();
                if ($tauxSucces < 0.6 || $tauxSucces > 0.9) {
                    $biais[] = [
                        'type' => 'nationalite',
                        'pays_id' => $paysId,
                        'taux_succes' => round($tauxSucces * 100, 1),
                        'nb_predictions' => $preds->count()
                    ];
                }
            }
        }

        // Biais par ranking
        $topPlayers = $predictions->filter(function($p) {
            return $p->match->joueur1->classement_atp_wta <= 50 ||
                $p->match->joueur2->classement_atp_wta <= 50;
        });

        if ($topPlayers->count() >= 100) {
            $tauxSuccesTop = $topPlayers->where('est_correcte', true)->count() / $topPlayers->count();
            $tauxSuccesGlobal = $predictions->where('est_correcte', true)->count() / $predictions->count();

            if (abs($tauxSuccesTop - $tauxSuccesGlobal) > 0.1) {
                $biais[] = [
                    'type' => 'ranking',
                    'ecart' => round(($tauxSuccesTop - $tauxSuccesGlobal) * 100, 1),
                    'favorise' => $tauxSuccesTop > $tauxSuccesGlobal ? 'top_players' : 'autres'
                ];
            }
        }

        // Mettre à jour le statut
        $this->update([
            'biais_detecte' => !empty($biais),
            'types_biais' => $biais
        ]);

        return [
            'biais_detectes' => $biais,
            'necessite_correction' => !empty($biais),
            'recommandations_correction' => $this->getRecommandationsCorrection($biais)
        ];
    }

    /**
     * Créer un test A/B
     */
    public function creerTestAB($algorithmeComparaison, $pourcentageTrafic = 50)
    {
        return [
            'test_id' => uniqid('ab_'),
            'algorithme_a' => $this->id,
            'algorithme_b' => $algorithmeComparaison->id,
            'pourcentage_trafic' => $pourcentageTrafic,
            'metriques_suivi' => ['precision', 'confiance', 'satisfaction'],
            'duree_prevue' => 30, // jours
            'seuil_signification' => 0.05,
            'statut' => 'active'
        ];
    }

    /**
     * Générer un rapport complet
     */
    public function genererRapportComplet()
    {
        return [
            'identification' => [
                'nom' => $this->nom,
                'version' => $this->version,
                'type' => $this->type_algorithme,
                'specialisation' => $this->specialisation_tennis
            ],
            'performance' => [
                'niveau' => $this->niveau_performance,
                'precision_globale' => $this->precision_globale . '%',
                'metriques_cles' => $this->metriques_cles,
                'contextes_optimaux' => $this->contextes_optimaux
            ],
            'sante_algorithme' => $this->statut_sante,
            'fiabilite' => [
                'score' => $this->score_fiabilite . '%',
                'facteurs' => $this->getFacteursFiabilite()
            ],
            'utilisation' => [
                'statut' => $this->statut_deploiement,
                'predictions_total' => $this->predictions()->count(),
                'utilisation_mensuelle' => $this->getUtilisationMensuelle()
            ],
            'maintenance' => [
                'derniere_maj' => $this->date_derniere_maj,
                'recommandations' => $this->recommandations_amelioration,
                'priorite_maintenance' => $this->getPrioriteMaintenanc

            ],
            'impact' => [
                'score_global' => $this->impact_global,
                'satisfaction_users' => $this->satisfaction_utilisateurs . '/10',
                'gain_vs_baseline' => $this->gain_precision . '%'
            ]
        ];
    }

    // ===================================================================
    // METHODS PRIVÉES
    // ===================================================================

    private function getScoreHistorique()
    {
        // Basé sur l'historique des évaluations
        $evaluations = $this->evaluations()
            ->where('created_at', '>=', now()->subMonths(6))
            ->orderBy('created_at')
            ->get();

        if ($evaluations->count() < 3) return 0.5;

        $tendance = $evaluations->last()->precision_globale - $evaluations->first()->precision_globale;
        $stabilite = $evaluations->pluck('precision_globale')->std();

        // Score basé sur tendance positive et faible variabilité
        return max(0, min(1, (50 + $tendance - $stabilite * 5) / 100));
    }

    private function getTauxSucces()
    {
        $total = $this->predictions()->whereNotNull('est_correcte')->count();
        if ($total === 0) return 0;

        $succes = $this->predictionsReussies()->count();
        return round(($succes / $total) * 100, 1);
    }

    private function calculerEvolutionPrecision($precisionActuelle)
    {
        $anciennePrecision = $this->precision_globale;
        $evolution = $precisionActuelle - $anciennePrecision;

        if ($evolution > 2) return 'Amélioration significative';
        if ($evolution > 0.5) return 'Légère amélioration';
        if ($evolution > -0.5) return 'Stable';
        if ($evolution > -2) return 'Légère dégradation';
        return 'Dégradation significative';
    }

    private function detecterDrift($predictions)
    {
        // Comparer première et seconde moitié de la période
        $moitie = $predictions->count() / 2;
        $premierePartie = $predictions->take($moitie);
        $secondePartie = $predictions->skip($moitie);

        if ($premierePartie->count() === 0 || $secondePartie->count() === 0) {
            return false;
        }

        $precision1 = $premierePartie->where('est_correcte', true)->count() / $premierePartie->count();
        $precision2 = $secondePartie->where('est_correcte', true)->count() / $secondePartie->count();

        return abs($precision1 - $precision2) > ($this->seuil_drift ?? 0.05);
    }

    private function genererRecommandations($precision, $metriquesContexte)
    {
        $recommandations = [];

        if ($precision < 70) {
            $recommandations[] = 'Performance globale en dessous du seuil acceptable';
        }

        foreach ($metriquesContexte['surfaces'] ?? [] as $surface => $precisionSurface) {
            if ($precisionSurface < $precision - 10) {
                $recommandations[] = "Performance faible sur {$surface}: {$precisionSurface}%";
            }
        }

        return $recommandations;
    }

    private function genererNouveauxParams($paramsActuels)
    {
        // Simulation de génération de nouveaux paramètres
        $nouveauxParams = $paramsActuels;

        // Ajuster aléatoirement quelques paramètres
        if (isset($nouveauxParams['learning_rate'])) {
            $nouveauxParams['learning_rate'] *= (0.8 + mt_rand() / mt_getrandmax() * 0.4);
        }

        return $nouveauxParams;
    }

    private function evaluerAvecParams($params)
    {
        // Simulation d'évaluation avec nouveaux paramètres
        return $this->precision_globale + (mt_rand() / mt_getrandmax() - 0.5) * 5;
    }

    private function getFacteursDecisifs($prediction)
    {
        // Simulation des facteurs les plus importants pour cette prédiction
        return [
            'ELO rating différence' => 0.23,
            'Forme récente' => 0.18,
            'Performance sur surface' => 0.15,
            'Head-to-head' => 0.12,
            'Conditions météo' => 0.08
        ];
    }

    private function getReglesAppliquees($prediction)
    {
        // Simulation des règles appliquées
        return [
            'Si ELO différence > 100 alors forte probabilité',
            'Si surface favorite alors bonus +15%',
            'Si conditions défavorables alors malus -10%'
        ];
    }

    private function getContexteTennis($prediction)
    {
        $match = $prediction->match;
        return [
            'surface' => $match->tournoi->surface->nom,
            'tournoi_importance' => $match->tournoi->categorie->nom,
            'conditions_meteo' => $match->conditionMeteo?->resume_conditions,
            'phase_tournoi' => $match->round->nom
        ];
    }

    private function getRecommandationsCorrection($biais)
    {
        $recommandations = [];

        foreach ($biais as $b) {
            switch ($b['type']) {
                case 'nationalite':
                    $recommandations[] = 'Équilibrer dataset par nationalité';
                    break;
                case 'ranking':
                    $recommandations[] = 'Ajuster pondération features ranking';
                    break;
            }
        }

        return $recommandations;
    }

    private function getFacteursFiabilite()
    {
        return [
            'Historique stable' => $this->getScoreHistorique() > 0.7,
            'Tests complets' => ($this->couverture_tests ?? 0) > 80,
            'Monitoring actif' => $this->drift_detection,
            'Maintenance récente' => $this->date_derniere_maj > now()->subMonths(1)
        ];
    }

    private function getUtilisationMensuelle()
    {
        return $this->predictions()
            ->where('created_at', '>=', now()->subMonth())
            ->count();
    }

    private function getPrioriteMaintenanc

    ()
    {
        $score = 0;

        if (($this->precision_globale ?? 0) < 70) $score += 3;
        if ($this->biais_detecte) $score += 2;
        if (!$this->date_derniere_maj || $this->date_derniere_maj < now()->subMonths(2)) $score += 2;
        if ($this->derniere_detection_drift && $this->derniere_detection_drift > now()->subDays(7)) $score += 1;

        if ($score >= 5) return 'Critique';
        if ($score >= 3) return 'Élevée';
        if ($score >= 1) return 'Modérée';
        return 'Faible';
    }

    // ===================================================================
    // VALIDATION RULES
    // ===================================================================

    public static function validationRules()
    {
        return [
            'nom' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:algorithme_ias,code',
            'type_algorithme' => 'required|in:neural_network,random_forest,gradient_boosting,svm,lstm,ensemble',
            'specialisation_tennis' => 'required|in:match_outcome,score_prediction,injury_risk,surface_performance,weather_impact',
            'precision_globale' => 'nullable|numeric|min:0|max:100',
            'version' => 'required|numeric|min:0.1',
            'statut_deploiement' => 'required|in:dev,staging,production,retired'
        ];
    }

    // ===================================================================
    // BOOT METHODS
    // ===================================================================

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($algorithme) {
            // Auto-calculs
            if ($algorithme->precision_validation && $algorithme->precision_test) {
                $algorithme->precision_globale = ($algorithme->precision_validation + $algorithme->precision_test) / 2;
            }

            // Déterminer complexité selon type
            if (!$algorithme->complexite_niveau) {
                $complexites = [
                    'neural_network' => 8,
                    'lstm' => 9,
                    'ensemble' => 10,
                    'gradient_boosting' => 7,
                    'random_forest' => 6,
                    'svm' => 5
                ];
                $algorithme->complexite_niveau = $complexites[$algorithme->type_algorithme] ?? 5;
            }

            // Valeurs par défaut
            if ($algorithme->actif === null) $algorithme->actif = true;
            if (!$algorithme->date_derniere_maj) $algorithme->date_derniere_maj = now();
        });

        static::created(function ($algorithme) {
            // Log de création
            \Log::info("Nouvel algorithme IA créé: {$algorithme->nom} ({$algorithme->code})");
        });
    }
}
