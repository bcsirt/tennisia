<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LogPrediction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'logs_predictions';

    protected $fillable = [
        // Identification et traçabilité
        'prediction_id',
        'uuid_prediction',           // UUID unique pour traçabilité
        'session_id',               // ID session utilisateur
        'batch_id',                 // ID batch pour prédictions groupées
        'version_model',            // Version du modèle IA utilisé
        'version_algorithme',       // Version de l'algorithme
        'environnement',            // 'dev', 'staging', 'production'

        // Timing et exécution
        'date_prediction',
        'timestamp_debut',          // Début calcul prédiction
        'timestamp_fin',            // Fin calcul prédiction
        'duree_calcul_ms',          // Durée calcul en millisecondes
        'timestamp_publication',    // Quand la prédiction a été publiée
        'delai_avant_match',        // Heures avant le match

        // Type et contexte de prédiction
        'type_prediction',          // 'match_outcome', 'set_score', 'total_games', 'performance'
        'granularite',              // 'match', 'set', 'jeu', 'point'
        'contexte_generation',      // 'automatique', 'demande_utilisateur', 'batch_quotidien'
        'priorite',                 // 1-10 (importance de la prédiction)
        'public_cible',             // 'grand_public', 'professionnels', 'parieurs', 'analystes'

        // Identification match et joueurs
        'match_tennis_id',
        'joueur1_id',
        'joueur2_id',
        'tournoi_id',
        'surface_match',
        'conditions_meteo',         // JSON conditions météo
        'importance_match',         // 'finale', 'demi_finale', '1er_tour', etc.

        // Prédiction principale
        'prediction_principale',    // JSON résultat principal prédit
        'probabilite_joueur1',      // 0-100 % de victoire joueur 1
        'probabilite_joueur2',      // 0-100 % de victoire joueur 2
        'gagnant_predit_id',        // ID joueur prédit gagnant
        'confiance_globale',        // 0-100 confiance globale
        'marge_victoire_predite',   // Score ou marge prédite

        // Prédictions détaillées
        'score_sets_predit',        // JSON score par set prédit
        'duree_match_predite',      // Minutes prédites
        'nb_jeux_total_predit',     // Nombre total de jeux prédit
        'nb_tie_breaks_predit',     // Nombre de tie-breaks prédit
        'performance_keys_predites', // JSON performances clés prédites

        // Modèles et algorithmes utilisés
        'modele_principal',         // 'random_forest', 'xgboost', 'neural_network', 'ensemble'
        'modeles_secondaires',      // JSON autres modèles consultés
        'poids_modeles',            // JSON poids accordés à chaque modèle
        'consensus_modeles',        // Score de consensus entre modèles
        'algorithme_fusion',        // Méthode de fusion des prédictions

        // Features et données d'entrée
        'features_utilisees',       // JSON features utilisées
        'nb_features_total',        // Nombre total de features
        'features_importantes',     // JSON top features importantes
        'poids_features',           // JSON poids des features
        'donnees_manquantes',       // JSON features manquantes
        'qualite_donnees_score',    // 0-100 qualité des données d'entrée

        // Historique et form des joueurs
        'h2h_record',               // JSON record face-à-face
        'forme_joueur1',            // Score forme récente joueur 1
        'forme_joueur2',            // Score forme récente joueur 2
        'elo_joueur1',              // ELO joueur 1 au moment prédiction
        'elo_joueur2',              // ELO joueur 2 au moment prédiction
        'classement_joueur1',       // Classement joueur 1
        'classement_joueur2',       // Classement joueur 2
        'facteurs_forme',           // JSON facteurs de forme considérés

        // Contexte surface et conditions
        'adaptation_surface_j1',    // Score adaptation surface joueur 1
        'adaptation_surface_j2',    // Score adaptation surface joueur 2
        'avantage_surface',         // Quel joueur avantagé par surface
        'impact_conditions',        // Impact météo/conditions
        'facteurs_contextuels',     // JSON autres facteurs contextuels

        // Métriques de confiance et incertitude
        'intervalles_confiance',    // JSON intervalles de confiance
        'incertitude_score',        // 0-100 score d'incertitude
        'volatilite_prediction',    // Volatilité de la prédiction
        'stabilite_modele',         // Stabilité du modèle sur données similaires
        'coherence_historique',     // Cohérence avec prédictions historiques

        // Explications et interprétabilité
        'explications_principales', // JSON explications principales
        'facteurs_decisifs',        // JSON facteurs les plus décisifs
        'scenarios_alternatifs',    // JSON scénarios alternatifs considérés
        'sensibilite_features',     // JSON sensibilité aux changements features
        'counterfactuals',          // JSON analyses contrefactuelles
        'interpretations_humaines', // JSON interprétations ajoutées par humains

        // Performance et validation
        'score_validation_croisee', // Score validation croisée du modèle
        'score_backtesting',        // Performance sur données historiques
        'calibration_score',        // Score de calibration probabiliste
        'overconfidence_score',     // Score de surconfiance détecté
        'coherence_ensemble',       // Cohérence avec ensemble de modèles

        // Comparaisons et benchmarks
        'prediction_consensus_marche', // Prédiction consensus du marché
        'ecart_consensus',          // Écart avec consensus
        'prediction_experts_humains', // JSON prédictions d'experts
        'ecart_experts',            // Écart avec experts
        'prediction_modele_simple', // Prédiction modèle baseline simple
        'amelioration_vs_baseline', // Amélioration vs modèle simple

        // Monitoring et alertes
        'alertes_generees',         // JSON alertes automatiques
        'anomalies_detectees',      // JSON anomalies dans les données
        'derive_detectee',          // Boolean dérive du modèle détectée
        'score_sante_modele',       // 0-100 santé générale du modèle
        'indicateurs_degradation',  // JSON indicateurs de dégradation

        // Impact business et utilisation
        'nb_consultations',         // Nombre de consultations prédiction
        'score_utilite_utilisateur', // Score utilité perçue (feedback)
        'impact_monetaire_estime',  // Impact monétaire estimé
        'valeur_informationnelle',  // Valeur informationnelle calculée
        'cout_calcul',              // Coût de calcul de la prédiction
        'roi_estime',               // ROI estimé de cette prédiction

        // Post-match et feedback
        'resultat_reel',            // JSON résultat réel du match
        'gagnant_reel_id',          // ID gagnant réel
        'score_reel',               // Score réel du match
        'duree_reelle_match',       // Durée réelle en minutes
        'prediction_correcte',      // Boolean prédiction principale correcte
        'erreur_probabiliste',      // Erreur probabiliste (Brier score)
        'erreur_absolue',           // Erreur absolue sur probabilités
        'surprise_factor',          // Facteur de surprise du résultat

        // Analyse performance détaillée
        'performance_par_set',      // JSON performance prédiction par set
        'precision_duree',          // Précision prédiction durée
        'precision_nb_jeux',        // Précision prédiction nombre jeux
        'facteurs_erreur',          // JSON facteurs ayant causé erreurs
        'lecons_apprises',          // JSON leçons pour amélioration

        // Feedback et apprentissage
        'feedback_utilisateurs',    // JSON feedback des utilisateurs
        'feedback_experts',         // JSON feedback d'experts
        'annotations_post_match',   // JSON annotations ajoutées après
        'utilise_retraining',       // Boolean utilisé pour réentraînement
        'poids_echantillon_futur',  // Poids pour entraînement futur
        'valeur_pedagogique',       // Valeur pour améliorer modèle

        // Reproductibilité et debug
        'seed_aleatoire',           // Seed pour reproductibilité
        'parametres_modele',        // JSON paramètres exacts du modèle
        'configuration_systeme',    // JSON config système au moment calcul
        'hash_donnees_entree',      // Hash des données d'entrée
        'checksum_model',           // Checksum du modèle utilisé
        'trace_execution',          // JSON trace d'exécution détaillée

        // Conformité et audit
        'justifications_decision',  // JSON justifications pour audit
        'conformite_reglementaire', // Boolean conformité aux règles
        'traçabilite_complete',     // Boolean traçabilité complète
        'validation_humaine',       // Boolean validation par humain
        'niveau_automatisation',    // Niveau d'automatisation 0-100
        'responsable_validation',   // ID personne responsable validation

        // Métriques techniques
        'memoire_utilisee_mb',      // Mémoire utilisée pour calcul
        'cpu_utilise_pourcent',     // % CPU utilisé
        'io_operations',            // Nombre opérations I/O
        'cache_hit_rate',           // Taux de hits cache
        'donnees_transferees_mb',   // Données transférées
        'latence_reseau_ms',        // Latence réseau

        // Classification et tags
        'tags_prediction',          // JSON tags/labels de classification
        'categorie_complexite',     // 'simple', 'moyenne', 'complexe', 'très_complexe'
        'niveau_risque',            // 'faible', 'moyen', 'élevé', 'critique'
        'public_diffusion',         // 'interne', 'partenaires', 'public'
        'statut_publication',       // 'brouillon', 'validee', 'publiee', 'retiree'

        // Versions et évolutions
        'version_donnees',          // Version des données utilisées
        'migration_model',          // Boolean prédiction après migration modèle
        'a_b_test_variant',         // Variant test A/B si applicable
        'experimentation_flag',     // Boolean fait partie d'expérimentation
        'baseline_comparison',      // Comparaison avec version baseline

        // Métadonnées système
        'serveur_calcul',           // Serveur ayant effectué le calcul
        'region_calcul',            // Région géographique calcul
        'fuseau_horaire',           // Fuseau horaire calcul
        'langue_explications',      // Langue des explications
        'format_sortie',            // Format de sortie de la prédiction
        'compression_utilisee',     // Boolean compression des données

        // Nettoyage et archivage
        'archive',                  // Boolean archivé
        'date_archivage',           // Date archivage
        'raison_archivage',         // Raison de l'archivage
        'peut_etre_supprime',       // Boolean peut être supprimé
        'date_suppression_prevue',  // Date suppression prévue
        'sauvegarde_effectuee'      // Boolean sauvegarde effectuée
    ];

    protected $casts = [
        // Dates et timestamps
        'date_prediction' => 'datetime',
        'timestamp_debut' => 'datetime',
        'timestamp_fin' => 'datetime',
        'timestamp_publication' => 'datetime',
        'date_archivage' => 'datetime',
        'date_suppression_prevue' => 'datetime',

        // Entiers
        'duree_calcul_ms' => 'integer',
        'delai_avant_match' => 'integer',
        'priorite' => 'integer',
        'nb_features_total' => 'integer',
        'classement_joueur1' => 'integer',
        'classement_joueur2' => 'integer',
        'duree_match_predite' => 'integer',
        'nb_jeux_total_predit' => 'integer',
        'nb_tie_breaks_predit' => 'integer',
        'duree_reelle_match' => 'integer',
        'nb_consultations' => 'integer',
        'memoire_utilisee_mb' => 'integer',
        'cpu_utilise_pourcent' => 'integer',
        'io_operations' => 'integer',
        'donnees_transferees_mb' => 'integer',
        'latence_reseau_ms' => 'integer',

        // Décimaux - Probabilités et scores
        'probabilite_joueur1' => 'decimal:2',
        'probabilite_joueur2' => 'decimal:2',
        'confiance_globale' => 'decimal:2',
        'consensus_modeles' => 'decimal:2',
        'qualite_donnees_score' => 'decimal:1',
        'forme_joueur1' => 'decimal:1',
        'forme_joueur2' => 'decimal:1',
        'elo_joueur1' => 'decimal:1',
        'elo_joueur2' => 'decimal:1',
        'adaptation_surface_j1' => 'decimal:1',
        'adaptation_surface_j2' => 'decimal:1',
        'incertitude_score' => 'decimal:1',
        'volatilite_prediction' => 'decimal:2',
        'stabilite_modele' => 'decimal:1',
        'coherence_historique' => 'decimal:1',
        'score_validation_croisee' => 'decimal:3',
        'score_backtesting' => 'decimal:3',
        'calibration_score' => 'decimal:3',
        'overconfidence_score' => 'decimal:2',
        'coherence_ensemble' => 'decimal:2',
        'ecart_consensus' => 'decimal:2',
        'ecart_experts' => 'decimal:2',
        'amelioration_vs_baseline' => 'decimal:3',
        'score_sante_modele' => 'decimal:1',
        'score_utilite_utilisateur' => 'decimal:1',
        'impact_monetaire_estime' => 'decimal:2',
        'valeur_informationnelle' => 'decimal:2',
        'cout_calcul' => 'decimal:4',
        'roi_estime' => 'decimal:2',
        'erreur_probabiliste' => 'decimal:4',
        'erreur_absolue' => 'decimal:3',
        'surprise_factor' => 'decimal:2',
        'precision_duree' => 'decimal:2',
        'precision_nb_jeux' => 'decimal:2',
        'poids_echantillon_futur' => 'decimal:3',
        'valeur_pedagogique' => 'decimal:2',
        'cache_hit_rate' => 'decimal:2',
        'niveau_automatisation' => 'decimal:1',
        'baseline_comparison' => 'decimal:3',

        // Booléens
        'prediction_correcte' => 'boolean',
        'derive_detectee' => 'boolean',
        'utilise_retraining' => 'boolean',
        'conformite_reglementaire' => 'boolean',
        'traçabilite_complete' => 'boolean',
        'validation_humaine' => 'boolean',
        'migration_model' => 'boolean',
        'experimentation_flag' => 'boolean',
        'compression_utilisee' => 'boolean',
        'archive' => 'boolean',
        'peut_etre_supprime' => 'boolean',
        'sauvegarde_effectuee' => 'boolean',

        // JSON
        'prediction_principale' => 'json',
        'score_sets_predit' => 'json',
        'performance_keys_predites' => 'json',
        'modeles_secondaires' => 'json',
        'poids_modeles' => 'json',
        'features_utilisees' => 'json',
        'features_importantes' => 'json',
        'poids_features' => 'json',
        'donnees_manquantes' => 'json',
        'h2h_record' => 'json',
        'facteurs_forme' => 'json',
        'conditions_meteo' => 'json',
        'facteurs_contextuels' => 'json',
        'intervalles_confiance' => 'json',
        'explications_principales' => 'json',
        'facteurs_decisifs' => 'json',
        'scenarios_alternatifs' => 'json',
        'sensibilite_features' => 'json',
        'counterfactuals' => 'json',
        'interpretations_humaines' => 'json',
        'prediction_experts_humains' => 'json',
        'alertes_generees' => 'json',
        'anomalies_detectees' => 'json',
        'indicateurs_degradation' => 'json',
        'resultat_reel' => 'json',
        'performance_par_set' => 'json',
        'facteurs_erreur' => 'json',
        'lecons_apprises' => 'json',
        'feedback_utilisateurs' => 'json',
        'feedback_experts' => 'json',
        'annotations_post_match' => 'json',
        'parametres_modele' => 'json',
        'configuration_systeme' => 'json',
        'trace_execution' => 'json',
        'justifications_decision' => 'json',
        'tags_prediction' => 'json'
    ];

    protected $appends = [
        'duree_humanized',
        'performance_globale',
        'niveau_confiance',
        'qualite_prediction',
        'facteurs_cles_succes',
        'resume_performance',
        'statut_prediction',
        'insights_amelioration',
        'score_impact_business',
        'indicateurs_sante'
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function prediction()
    {
        return $this->belongsTo(Prediction::class);
    }

    public function match()
    {
        return $this->belongsTo(MatchTennis::class, 'match_tennis_id');
    }

    public function joueur1()
    {
        return $this->belongsTo(Joueur::class, 'joueur1_id');
    }

    public function joueur2()
    {
        return $this->belongsTo(Joueur::class, 'joueur2_id');
    }

    public function gagnantPredit()
    {
        return $this->belongsTo(Joueur::class, 'gagnant_predit_id');
    }

    public function gagnantReel()
    {
        return $this->belongsTo(Joueur::class, 'gagnant_reel_id');
    }

    public function tournoi()
    {
        return $this->belongsTo(Tournoi::class, 'tournoi_id');
    }

    public function responsableValidation()
    {
        return $this->belongsTo(User::class, 'responsable_validation');
    }

    public function logsSimilaires()
    {
        return $this->hasMany(LogPrediction::class, 'match_tennis_id', 'match_tennis_id')
            ->where('id', '!=', $this->id);
    }

    public function metriquesPerformance()
    {
        return $this->hasMany(MetriquePerformanceModel::class, 'log_prediction_id');
    }

    // ===================================================================
    // SCOPES
    // ===================================================================

    public function scopeParModele($query, $modele)
    {
        return $query->where('modele_principal', $modele);
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type_prediction', $type);
    }

    public function scopeCorrectes($query)
    {
        return $query->where('prediction_correcte', true);
    }

    public function scopeIncorrectes($query)
    {
        return $query->where('prediction_correcte', false);
    }

    public function scopeHauteConfiance($query)
    {
        return $query->where('confiance_globale', '>=', 80);
    }

    public function scopeFaibleConfiance($query)
    {
        return $query->where('confiance_globale', '<=', 60);
    }

    public function scopeAujourdhui($query)
    {
        return $query->whereDate('date_prediction', today());
    }

    public function scopePeriode($query, $debut, $fin)
    {
        return $query->whereBetween('date_prediction', [$debut, $fin]);
    }

    public function scopeAvecResultat($query)
    {
        return $query->whereNotNull('resultat_reel');
    }

    public function scopeEnAttente($query)
    {
        return $query->whereNull('resultat_reel');
    }

    public function scopeValidees($query)
    {
        return $query->where('validation_humaine', true);
    }

    public function scopeAutomatiques($query)
    {
        return $query->where('niveau_automatisation', '>=', 80);
    }

    public function scopeAvecDrive($query)
    {
        return $query->where('derive_detectee', true);
    }

    public function scopePerformantes($query)
    {
        return $query->where('erreur_probabiliste', '<=', 0.25); // Brier score <= 0.25
    }

    public function scopePourRetraining($query)
    {
        return $query->where('utilise_retraining', true);
    }

    public function scopeComplexes($query)
    {
        return $query->whereIn('categorie_complexite', ['complexe', 'très_complexe']);
    }

    public function scopeParJoueur($query, $joueurId)
    {
        return $query->where(function($q) use ($joueurId) {
            $q->where('joueur1_id', $joueurId)
                ->orWhere('joueur2_id', $joueurId);
        });
    }

    public function scopeParSurface($query, $surface)
    {
        return $query->where('surface_match', $surface);
    }

    // ===================================================================
    // ACCESSORS INTELLIGENTS
    // ===================================================================

    public function getDureeHumanizedAttribute()
    {
        if (!$this->duree_calcul_ms) return 'N/A';

        $duree = $this->duree_calcul_ms;
        if ($duree < 1000) return $duree . 'ms';
        if ($duree < 60000) return round($duree / 1000, 1) . 's';
        return round($duree / 60000, 1) . 'min';
    }

    public function getPerformanceGlobaleAttribute()
    {
        if (!$this->resultat_reel) return null;

        $composantes = [
            'precision' => $this->prediction_correcte ? 100 : 0,
            'calibration' => max(0, 100 - (abs($this->erreur_probabiliste ?? 0.5) * 200)),
            'confiance' => $this->confiance_globale ?? 50,
            'stabilite' => $this->stabilite_modele ?? 50,
            'coherence' => $this->coherence_historique ?? 50
        ];

        return round(array_sum($composantes) / count($composantes), 1);
    }

    public function getNiveauConfianceAttribute()
    {
        $confiance = $this->confiance_globale ?? 50;

        if ($confiance >= 95) return 'Très haute';
        if ($confiance >= 85) return 'Haute';
        if ($confiance >= 75) return 'Bonne';
        if ($confiance >= 65) return 'Moyenne';
        if ($confiance >= 50) return 'Faible';
        return 'Très faible';
    }

    public function getQualitePredictionAttribute()
    {
        $facteurs = [
            'donnees' => $this->qualite_donnees_score ?? 50,
            'modele' => $this->score_sante_modele ?? 50,
            'coherence' => $this->coherence_ensemble ?? 50,
            'stabilite' => $this->stabilite_modele ?? 50
        ];

        $score = array_sum($facteurs) / count($facteurs);

        if ($score >= 90) return 'Excellente';
        if ($score >= 80) return 'Très bonne';
        if ($score >= 70) return 'Bonne';
        if ($score >= 60) return 'Acceptable';
        if ($score >= 50) return 'Médiocre';
        return 'Mauvaise';
    }

    public function getFacteursClesSuccesAttribute()
    {
        $facteurs = [];

        if (($this->confiance_globale ?? 0) >= 85) {
            $facteurs[] = 'Confiance élevée';
        }

        if (($this->qualite_donnees_score ?? 0) >= 80) {
            $facteurs[] = 'Données de qualité';
        }

        if (($this->consensus_modeles ?? 0) >= 80) {
            $facteurs[] = 'Consensus modèles';
        }

        if (($this->coherence_historique ?? 0) >= 80) {
            $facteurs[] = 'Cohérence historique';
        }

        if (count($this->features_importantes ?? []) >= 10) {
            $facteurs[] = 'Riches features';
        }

        return $facteurs;
    }

    public function getResumePerformanceAttribute()
    {
        if (!$this->resultat_reel) {
            return [
                'statut' => 'En attente du résultat',
                'confiance' => $this->niveau_confiance,
                'qualite' => $this->qualite_prediction,
                'gagnant_predit' => $this->gagnantPredit?->nom_complet,
                'probabilite' => max($this->probabilite_joueur1 ?? 0, $this->probabilite_joueur2 ?? 0) . '%'
            ];
        }

        return [
            'statut' => $this->prediction_correcte ? '✅ Correcte' : '❌ Incorrecte',
            'performance' => $this->performance_globale . '/100',
            'erreur_prob' => round($this->erreur_probabiliste ?? 0, 3),
            'surprise' => round($this->surprise_factor ?? 0, 2),
            'gagnant_predit' => $this->gagnantPredit?->nom_complet,
            'gagnant_reel' => $this->gagnantReel?->nom_complet,
            'ecart_duree' => abs(($this->duree_match_predite ?? 0) - ($this->duree_reelle_match ?? 0)) . ' min'
        ];
    }

    public function getStatutPredictionAttribute()
    {
        if ($this->archive) return 'Archivée';
        if ($this->derive_detectee) return 'Dérive détectée';
        if (!$this->validation_humaine && $this->niveau_automatisation < 80) return 'En attente validation';
        if (!$this->resultat_reel) return 'En attente résultat';
        if ($this->prediction_correcte) return 'Réussie';
        return 'Échouée';
    }

    public function getInsightsAmeliorationAttribute()
    {
        $insights = [];

        // Analyse des erreurs
        if ($this->resultat_reel && !$this->prediction_correcte) {
            if (($this->confiance_globale ?? 0) >= 80) {
                $insights[] = 'Surconfiance détectée - calibrer le modèle';
            }

            if (count($this->facteurs_erreur ?? []) > 0) {
                $insights[] = 'Facteurs d\'erreur identifiés: ' . implode(', ', array_slice($this->facteurs_erreur, 0, 3));
            }

            if (($this->surprise_factor ?? 0) >= 2) {
                $insights[] = 'Résultat très surprenant - analyser les données atypiques';
            }
        }

        // Qualité des données
        if (($this->qualite_donnees_score ?? 0) < 70) {
            $insights[] = 'Améliorer la qualité des données d\'entrée';
        }

        // Performance du modèle
        if (($this->score_sante_modele ?? 0) < 80) {
            $insights[] = 'Santé du modèle dégradée - considérer un réentraînement';
        }

        return $insights;
    }

    public function getScoreImpactBusinessAttribute()
    {
        $facteurs = [
            'utilite' => $this->score_utilite_utilisateur ?? 50,
            'consultations' => min(100, ($this->nb_consultations ?? 0) * 10),
            'roi' => min(100, max(0, 50 + ($this->roi_estime ?? 0))),
            'precision' => $this->prediction_correcte ? 100 : 0
        ];

        return round(array_sum($facteurs) / count($facteurs), 1);
    }

    public function getIndicateursSanteAttribute()
    {
        return [
            'sante_modele' => $this->score_sante_modele ?? 50,
            'derive_detectee' => $this->derive_detectee,
            'qualite_donnees' => $this->qualite_donnees_score ?? 50,
            'stabilite' => $this->stabilite_modele ?? 50,
            'coherence' => $this->coherence_ensemble ?? 50,
            'anomalies' => count($this->anomalies_detectees ?? []),
            'degradation' => count($this->indicateurs_degradation ?? []) > 0
        ];
    }

    // ===================================================================
    // METHODS PRINCIPALES
    // ===================================================================

    /**
     * Enregistrer une nouvelle prédiction
     */
    public static function enregistrerPrediction($prediction, $contexte = [])
    {
        $debut = microtime(true);

        $log = static::create([
            'prediction_id' => $prediction->id,
            'uuid_prediction' => \Str::uuid(),
            'timestamp_debut' => now(),
            'match_tennis_id' => $prediction->match_tennis_id,
            'joueur1_id' => $prediction->match->joueur1_id,
            'joueur2_id' => $prediction->match->joueur2_id,
            'tournoi_id' => $prediction->match->tournoi_id,
            'gagnant_predit_id' => $prediction->gagnant_predit_id,
            'probabilite_joueur1' => $prediction->probabilite_joueur1,
            'probabilite_joueur2' => $prediction->probabilite_joueur2,
            'confiance_globale' => $prediction->confiance,
            'type_prediction' => $contexte['type'] ?? 'match_outcome',
            'modele_principal' => $contexte['modele'] ?? 'ensemble',
            'version_algorithme' => $contexte['version'] ?? '1.0',
            'environnement' => app()->environment(),
            'features_utilisees' => $contexte['features'] ?? [],
            'explications_principales' => $contexte['explications'] ?? []
        ]);

        $fin = microtime(true);
        $log->update([
            'timestamp_fin' => now(),
            'duree_calcul_ms' => round(($fin - $debut) * 1000)
        ]);

        return $log;
    }

    /**
     * Mettre à jour avec le résultat réel
     */
    public function mettreAJourResultat($resultatMatch)
    {
        $this->update([
            'resultat_reel' => [
                'gagnant_id' => $resultatMatch->gagnant_id,
                'score' => $resultatMatch->score,
                'duree_minutes' => $resultatMatch->duree_minutes,
                'sets' => $resultatMatch->sets_detail
            ],
            'gagnant_reel_id' => $resultatMatch->gagnant_id,
            'score_reel' => $resultatMatch->score,
            'duree_reelle_match' => $resultatMatch->duree_minutes,
            'prediction_correcte' => $this->gagnant_predit_id === $resultatMatch->gagnant_id
        ]);

        $this->calculerMetriquesPerformance();
        $this->analyserFacteursErreur();
        $this->genererLeconsApprises();

        return $this;
    }

    /**
     * Calculer les métriques de performance
     */
    public function calculerMetriquesPerformance()
    {
        if (!$this->resultat_reel) return $this;

        // Brier Score (erreur probabiliste)
        $probPredite = $this->gagnant_predit_id === $this->joueur1_id ?
            $this->probabilite_joueur1 / 100 : $this->probabilite_joueur2 / 100;
        $resultatBinaire = $this->prediction_correcte ? 1 : 0;
        $this->erreur_probabiliste = pow($probPredite - $resultatBinaire, 2);

        // Erreur absolue
        $this->erreur_absolue = abs($probPredite - $resultatBinaire);

        // Facteur de surprise
        $this->surprise_factor = 1 / max(0.01, $probPredite);

        // Précision durée
        if ($this->duree_match_predite && $this->duree_reelle_match) {
            $erreurDuree = abs($this->duree_match_predite - $this->duree_reelle_match);
            $this->precision_duree = max(0, 100 - ($erreurDuree / $this->duree_reelle_match * 100));
        }

        $this->save();

        return $this;
    }

    /**
     * Analyser les facteurs d'erreur
     */
    public function analyserFacteursErreur()
    {
        if ($this->prediction_correcte) return $this;

        $facteursErreur = [];

        // Analyse des features importantes mal évaluées
        if ($this->features_importantes) {
            foreach ($this->features_importantes as $feature => $importance) {
                if ($importance > 0.8) {
                    $facteursErreur[] = "Feature critique: {$feature}";
                }
            }
        }

        // Analyse forme récente
        if (abs($this->forme_joueur1 - $this->forme_joueur2) < 10) {
            $facteursErreur[] = "Formes très équilibrées non anticipées";
        }

        // Analyse conditions
        if ($this->conditions_meteo && isset($this->conditions_meteo['conditions_extremes'])) {
            $facteursErreur[] = "Conditions météo extrêmes";
        }

        // Facteur surprise
        if ($this->surprise_factor >= 3) {
            $facteursErreur[] = "Résultat très improbable";
        }

        $this->update(['facteurs_erreur' => $facteursErreur]);

        return $this;
    }

    /**
     * Générer les leçons apprises
     */
    public function genererLeconsApprises()
    {
        $lecons = [];

        if (!$this->prediction_correcte) {
            // Leçons sur les erreurs
            if (($this->confiance_globale ?? 0) >= 80) {
                $lecons[] = "Réduire la confiance pour des profils de matchs similaires";
            }

            if (count($this->donnees_manquantes ?? []) > 5) {
                $lecons[] = "Améliorer la collecte de données pour ce type de match";
            }

            if (($this->consensus_modeles ?? 0) < 60) {
                $lecons[] = "Divergence modèles - investiguer les causes";
            }
        } else {
            // Leçons sur les succès
            if (($this->confiance_globale ?? 0) >= 90 && $this->prediction_correcte) {
                $lecons[] = "Excellent modèle pour ce profil de match";
            }

            if (count($this->features_importantes ?? []) >= 15) {
                $lecons[] = "Features riches donnent de bons résultats";
            }
        }

        $this->update(['lecons_apprises' => $lecons]);

        return $this;
    }

    /**
     * Détecter une dérive du modèle
     */
    public function detecterDerive()
    {
        // Analyser les performances récentes du même modèle
        $performances = static::where('modele_principal', $this->modele_principal)
            ->where('date_prediction', '>=', now()->subDays(30))
            ->whereNotNull('resultat_reel')
            ->get();

        if ($performances->count() < 10) return false;

        $tauxSucces = $performances->where('prediction_correcte', true)->count() / $performances->count();
        $brierMoyen = $performances->avg('erreur_probabiliste');

        // Dérive détectée si performance dégradée
        $derive = $tauxSucces < 0.6 || $brierMoyen > 0.3;

        if ($derive) {
            $this->update([
                'derive_detectee' => true,
                'indicateurs_degradation' => [
                    'taux_succes' => $tauxSucces,
                    'brier_moyen' => $brierMoyen,
                    'echantillon' => $performances->count()
                ]
            ]);
        }

        return $derive;
    }

    /**
     * Générer rapport complet
     */
    public function genererRapportComplet()
    {
        return [
            'identification' => [
                'uuid' => $this->uuid_prediction,
                'date' => $this->date_prediction,
                'modele' => $this->modele_principal,
                'version' => $this->version_algorithme
            ],
            'match' => [
                'joueur1' => $this->joueur1?->nom_complet,
                'joueur2' => $this->joueur2?->nom_complet,
                'tournoi' => $this->tournoi?->nom,
                'surface' => $this->surface_match
            ],
            'prediction' => [
                'gagnant_predit' => $this->gagnantPredit?->nom_complet,
                'probabilites' => [
                    'joueur1' => $this->probabilite_joueur1 . '%',
                    'joueur2' => $this->probabilite_joueur2 . '%'
                ],
                'confiance' => $this->niveau_confiance,
                'qualite' => $this->qualite_prediction
            ],
            'performance' => $this->resume_performance,
            'technique' => [
                'features' => count($this->features_utilisees ?? []),
                'duree_calcul' => $this->duree_humanized,
                'qualite_donnees' => $this->qualite_donnees_score . '/100',
                'consensus' => $this->consensus_modeles . '%'
            ],
            'business' => [
                'consultations' => $this->nb_consultations,
                'impact_score' => $this->score_impact_business,
                'roi' => $this->roi_estime
            ],
            'sante_modele' => $this->indicateurs_sante,
            'ameliorations' => $this->insights_amelioration,
            'facteurs_cles' => $this->facteurs_cles_succes
        ];
    }

    // ===================================================================
    // STATIC ANALYSIS METHODS
    // ===================================================================

    /**
     * Analyser performance globale d'un modèle
     */
    public static function analyserPerformanceModele($modele, $periode = 30)
    {
        $logs = static::where('modele_principal', $modele)
            ->where('date_prediction', '>=', now()->subDays($periode))
            ->whereNotNull('resultat_reel')
            ->get();

        if ($logs->isEmpty()) return null;

        return [
            'nb_predictions' => $logs->count(),
            'taux_succes' => round($logs->where('prediction_correcte', true)->count() / $logs->count() * 100, 2),
            'brier_score_moyen' => round($logs->avg('erreur_probabiliste'), 4),
            'confiance_moyenne' => round($logs->avg('confiance_globale'), 1),
            'surprise_factor_moyen' => round($logs->avg('surprise_factor'), 2),
            'derive_detectee' => $logs->where('derive_detectee', true)->count() > 0,
            'performance_par_surface' => $logs->groupBy('surface_match')->map(function($group) {
                return [
                    'count' => $group->count(),
                    'taux_succes' => round($group->where('prediction_correcte', true)->count() / $group->count() * 100, 2)
                ];
            })
        ];
    }

    /**
     * Identifier les meilleures prédictions pour le réentraînement
     */
    public static function identifierMeilleuresPredictions($limite = 100)
    {
        return static::whereNotNull('resultat_reel')
            ->where('qualite_donnees_score', '>=', 80)
            ->where('confiance_globale', '>=', 70)
            ->where('anomalie_detectee', false)
            ->orderByRaw('
                CASE
                    WHEN prediction_correcte = 1 AND confiance_globale >= 90 THEN 1
                    WHEN prediction_correcte = 0 AND confiance_globale <= 60 THEN 2
                    ELSE 3
                END
            ')
            ->limit($limite)
            ->get();
    }

    // ===================================================================
    // VALIDATION RULES
    // ===================================================================

    public static function validationRules()
    {
        return [
            'prediction_id' => 'required|exists:predictions,id',
            'match_tennis_id' => 'required|exists:match_tennis,id',
            'joueur1_id' => 'required|exists:joueurs,id',
            'joueur2_id' => 'required|exists:joueurs,id',
            'type_prediction' => 'required|in:match_outcome,set_score,total_games,performance',
            'modele_principal' => 'required|string|max:100',
            'probabilite_joueur1' => 'required|numeric|between:0,100',
            'probabilite_joueur2' => 'required|numeric|between:0,100',
            'confiance_globale' => 'required|numeric|between:0,100'
        ];
    }

    // ===================================================================
    // BOOT METHODS
    // ===================================================================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($log) {
            $log->uuid_prediction = $log->uuid_prediction ?? \Str::uuid();
            $log->date_prediction = $log->date_prediction ?? now();
            $log->environnement = app()->environment();
            $log->timestamp_debut = $log->timestamp_debut ?? now();
        });

        static::created(function ($log) {
            // Déclencheur analyse automatique après création
            if ($log->resultat_reel) {
                $log->calculerMetriquesPerformance();
                $log->analyserFacteursErreur();
                $log->detecterDerive();
            }
        });

        static::updated(function ($log) {
            // Si résultat ajouté, déclencher analyses
            if ($log->wasChanged('resultat_reel') && $log->resultat_reel) {
                $log->calculerMetriquesPerformance();
                $log->analyserFacteursErreur();
                $log->genererLeconsApprises();
                $log->detecterDerive();
            }
        });
    }
}
