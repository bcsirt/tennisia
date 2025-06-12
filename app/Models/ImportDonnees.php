<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ImportDonnees extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'import_donnees';

    protected $fillable = [
        // Identification et source
        'source_donnees_id',
        'identifiant_unique',        // UUID pour cet import
        'nom_import',                // Nom descriptif de l'import
        'description',
        'version_import',            // v1.0, v1.1, etc.

        // Timing et planification
        'date_import',
        'date_debut',
        'date_fin',
        'duree_execution',           // millisecondes
        'date_planifiee',            // Import planifié
        'declencheur',               // 'manuel', 'automatique', 'api', 'cron'
        'priorite',                  // 1-10

        // Type et catégorie
        'type_donnees',              // 'joueurs', 'matchs', 'tournois', 'classements', 'statistiques'
        'sous_type',                 // 'joueurs_atp', 'matchs_grand_chelem', etc.
        'categorie_tennis',          // 'atp', 'wta', 'itf', 'junior'
        'surface_concernee',         // 'dur', 'terre', 'gazon', 'indoor', 'toutes'
        'periode_donnees',           // '2024', '2024-Q1', 'saison_actuelle'
        'niveau_tournoi',            // 'grand_chelem', 'masters_1000', 'atp_500', etc.

        // Statut et résultats
        'statut',                    // 'en_attente', 'en_cours', 'succes', 'erreur', 'partiel', 'annule'
        'statut_detaille',           // Statut plus précis
        'pourcentage_completion',    // 0-100
        'etape_actuelle',            // 'extraction', 'transformation', 'validation', 'insertion'
        'etapes_total',              // Nombre total d'étapes

        // Métriques quantitatives
        'nb_enregistrements_source', // Nombre dans la source
        'nb_enregistrements_traites',// Nombre traités
        'nb_insertions',             // Nouveaux enregistrements
        'nb_mises_a_jour',          // Enregistrements modifiés
        'nb_doublons_ignores',      // Doublons évités
        'nb_erreurs_donnees',       // Erreurs de données
        'nb_validations_echouees',  // Échecs de validation

        // Métriques par modèle cible
        'joueurs_importes',
        'joueurs_mis_a_jour',
        'matchs_importes',
        'matchs_mis_a_jour',
        'tournois_importes',
        'tournois_mis_a_jour',
        'statistiques_importees',
        'classements_importes',

        // Métriques techniques
        'taille_donnees_mo',         // Taille des données en Mo
        'vitesse_traitement',        // enregistrements/seconde
        'memoire_utilisee_mo',       // Pic mémoire
        'cpu_utilise_pourcent',      // % CPU moyen
        'nb_requetes_api',           // Requêtes API effectuées
        'temps_reponse_api_moyen',   // ms

        // Gestion des erreurs
        'a_erreurs',                 // Boolean
        'nb_erreurs_total',
        'types_erreurs',             // JSON des types d'erreurs
        'erreurs_critiques',         // JSON des erreurs bloquantes
        'erreurs_mineures',          // JSON des erreurs non bloquantes
        'premiere_erreur',           // Message première erreur
        'derniere_erreur',           // Message dernière erreur
        'log_erreurs_complet',       // JSON log complet

        // Retry et robustesse
        'nb_tentatives',             // Nombre de tentatives
        'tentative_actuelle',        // Tentative en cours
        'derniere_tentative',        // Timestamp dernière tentative
        'prochaine_tentative',       // Timestamp prochaine tentative
        'strategy_retry',            // 'linear', 'exponential', 'fixed'
        'delai_retry_seconds',       // Délai entre tentatives

        // Configuration import
        'parametres_import',         // JSON config spécifique
        'mapping_champs',            // JSON mapping source -> modèle
        'regles_transformation',     // JSON règles de transformation
        'filtres_appliques',         // JSON filtres appliqués
        'regles_validation',         // JSON règles de validation
        'options_performance',       // JSON options d'optimisation

        // Traçabilité et audit
        'utilisateur_declencheur',   // ID utilisateur qui a lancé
        'ip_origine',                // IP d'origine
        'user_agent',                // User-Agent
        'checksum_donnees',          // Hash des données importées
        'signature_source',          // Signature de la source
        'version_modeles',           // Version des modèles à l'import

        // Résultats et impact
        'donnees_brutes',            // JSON échantillon données brutes
        'donnees_transformees',      // JSON échantillon données transformées
        'modifications_detectees',   // JSON des modifications détectées
        'impact_sur_modeles',        // JSON impact sur modèles finaux
        'alertes_generees',          // JSON alertes générées
        'notifications_envoyees',    // JSON notifications

        // Performance et optimisation
        'utilise_cache',             // Boolean utilisation cache
        'cache_hit_rate',            // % de cache hits
        'utilise_bulk_insert',       // Boolean insertion en masse
        'utilise_transactions',      // Boolean transactions
        'index_optimises',           // Boolean index optimisés
        'compression_utilisee',      // Boolean compression

        // Métadonnées contextuelles
        'saison_tennis',             // '2024', '2023-2024'
        'semaine_atp',               // Semaine ATP (1-52)
        'tournoi_en_cours',          // ID tournoi si applicable
        'periode_classement',        // Période de classement
        'contexte_meta',             // JSON métadonnées contextuelles

        // Qualité des données
        'score_qualite_global',      // 0-100 score qualité
        'completude_donnees',        // 0-100 % de complétude
        'coherence_donnees',         // 0-100 % de cohérence
        'fraicheur_donnees',         // Age des données (heures)
        'fiabilite_source',          // 0-100 fiabilité de la source
        'precision_estimee',         // 0-100 précision estimée

        // Intégration IA
        'utilise_pour_ia',           // Boolean utilisé pour IA
        'impact_predictions',        // JSON impact sur prédictions
        'modeles_ia_affectes',       // JSON modèles IA impactés
        'recalcul_necessaire',       // Boolean recalcul IA requis
        'score_confiance_ia',        // 0-100 confiance IA

        // Archivage et nettoyage
        'archive',                   // Boolean archivé
        'date_archivage',
        'peut_etre_supprime',        // Boolean suppression possible
        'date_suppression_prevue',
        'sauvegarde_effectuee',      // Boolean sauvegardé
        'compression_archive'        // Ratio compression
    ];

    protected $casts = [
        // Dates
        'date_import' => 'datetime',
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'date_planifiee' => 'datetime',
        'derniere_tentative' => 'datetime',
        'prochaine_tentative' => 'datetime',
        'date_archivage' => 'datetime',
        'date_suppression_prevue' => 'datetime',

        // JSON
        'types_erreurs' => 'json',
        'erreurs_critiques' => 'json',
        'erreurs_mineures' => 'json',
        'log_erreurs_complet' => 'json',
        'parametres_import' => 'json',
        'mapping_champs' => 'json',
        'regles_transformation' => 'json',
        'filtres_appliques' => 'json',
        'regles_validation' => 'json',
        'options_performance' => 'json',
        'donnees_brutes' => 'json',
        'donnees_transformees' => 'json',
        'modifications_detectees' => 'json',
        'impact_sur_modeles' => 'json',
        'alertes_generees' => 'json',
        'notifications_envoyees' => 'json',
        'contexte_meta' => 'json',
        'impact_predictions' => 'json',
        'modeles_ia_affectes' => 'json',

        // Entiers
        'duree_execution' => 'integer',
        'priorite' => 'integer',
        'pourcentage_completion' => 'integer',
        'etapes_total' => 'integer',
        'nb_enregistrements_source' => 'integer',
        'nb_enregistrements_traites' => 'integer',
        'nb_insertions' => 'integer',
        'nb_mises_a_jour' => 'integer',
        'nb_doublons_ignores' => 'integer',
        'nb_erreurs_donnees' => 'integer',
        'nb_validations_echouees' => 'integer',
        'joueurs_importes' => 'integer',
        'joueurs_mis_a_jour' => 'integer',
        'matchs_importes' => 'integer',
        'matchs_mis_a_jour' => 'integer',
        'tournois_importes' => 'integer',
        'tournois_mis_a_jour' => 'integer',
        'statistiques_importees' => 'integer',
        'classements_importes' => 'integer',
        'nb_erreurs_total' => 'integer',
        'nb_tentatives' => 'integer',
        'tentative_actuelle' => 'integer',
        'delai_retry_seconds' => 'integer',
        'memoire_utilisee_mo' => 'integer',
        'cpu_utilise_pourcent' => 'integer',
        'nb_requetes_api' => 'integer',
        'temps_reponse_api_moyen' => 'integer',
        'semaine_atp' => 'integer',
        'score_qualite_global' => 'integer',
        'completude_donnees' => 'integer',
        'coherence_donnees' => 'integer',
        'fraicheur_donnees' => 'integer',
        'fiabilite_source' => 'integer',
        'precision_estimee' => 'integer',
        'score_confiance_ia' => 'integer',

        // Décimaux
        'taille_donnees_mo' => 'decimal:2',
        'vitesse_traitement' => 'decimal:2',
        'cache_hit_rate' => 'decimal:2',
        'compression_archive' => 'decimal:2',

        // Booléens
        'a_erreurs' => 'boolean',
        'utilise_cache' => 'boolean',
        'utilise_bulk_insert' => 'boolean',
        'utilise_transactions' => 'boolean',
        'index_optimises' => 'boolean',
        'compression_utilisee' => 'boolean',
        'utilise_pour_ia' => 'boolean',
        'recalcul_necessaire' => 'boolean',
        'archive' => 'boolean',
        'peut_etre_supprime' => 'boolean',
        'sauvegarde_effectuee' => 'boolean'
    ];

    protected $appends = [
        'duree_humanized',
        'statut_avec_icone',
        'taux_succes',
        'vitesse_humanized',
        'impact_global',
        'score_performance',
        'est_reussi',
        'necessite_attention',
        'resume_execution'
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function source()
    {
        return $this->belongsTo(SourceDonnees::class, 'source_donnees_id');
    }

    public function utilisateurDeclencheur()
    {
        return $this->belongsTo(User::class, 'utilisateur_declencheur');
    }

    public function logsExecution()
    {
        return $this->hasMany(LogExecution::class);
    }

    public function metriquesDetails()
    {
        return $this->hasMany(MetriqueImport::class);
    }

    public function donneesImportees()
    {
        return $this->morphToMany(Model::class, 'importable');
    }

    // Relations vers les modèles créés/modifiés
    public function joueursAffectes()
    {
        return $this->morphedByMany(Joueur::class, 'importable');
    }

    public function matchsAffectes()
    {
        return $this->morphedByMany(MatchTennis::class, 'importable');
    }

    public function tournoislAffectes()
    {
        return $this->morphedByMany(Tournoi::class, 'importable');
    }

    // ===================================================================
    // SCOPES
    // ===================================================================

    public function scopeReussis($query)
    {
        return $query->where('statut', 'succes');
    }

    public function scopeEnErreur($query)
    {
        return $query->where('statut', 'erreur');
    }

    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type_donnees', $type);
    }

    public function scopeParSource($query, $sourceId)
    {
        return $query->where('source_donnees_id', $sourceId);
    }

    public function scopeAujourdhui($query)
    {
        return $query->whereDate('date_import', today());
    }

    public function scopePeriode($query, $debut, $fin)
    {
        return $query->whereBetween('date_import', [$debut, $fin]);
    }

    public function scopeAvecErreurs($query)
    {
        return $query->where('a_erreurs', true);
    }

    public function scopeSansErreurs($query)
    {
        return $query->where('a_erreurs', false);
    }

    public function scopeHautePriorite($query)
    {
        return $query->where('priorite', '<=', 3);
    }

    public function scopeNecessitantRetry($query)
    {
        return $query->where('statut', 'erreur')
            ->where('tentative_actuelle', '<', 'nb_tentatives')
            ->where('prochaine_tentative', '<=', now());
    }

    public function scopeUtilisesPourIA($query)
    {
        return $query->where('utilise_pour_ia', true);
    }

    public function scopeQualiteElevee($query)
    {
        return $query->where('score_qualite_global', '>=', 80);
    }

    public function scopeParSaison($query, $saison)
    {
        return $query->where('saison_tennis', $saison);
    }

    public function scopeArchives($query)
    {
        return $query->where('archive', true);
    }

    public function scopeActifs($query)
    {
        return $query->where('archive', false);
    }

    // ===================================================================
    // ACCESSORS
    // ===================================================================

    public function getDureeHumanizedAttribute()
    {
        if (!$this->duree_execution) return 'N/A';

        $duree = $this->duree_execution;
        if ($duree < 1000) return $duree . 'ms';
        if ($duree < 60000) return round($duree / 1000, 1) . 's';
        return round($duree / 60000, 1) . 'min';
    }

    public function getStatutAvecIconeAttribute()
    {
        $icones = [
            'en_attente' => '⏳',
            'en_cours' => '🔄',
            'succes' => '✅',
            'erreur' => '❌',
            'partiel' => '⚠️',
            'annule' => '🚫'
        ];

        return ($icones[$this->statut] ?? '❓') . ' ' . ucfirst($this->statut);
    }

    public function getTauxSuccesAttribute()
    {
        if ($this->nb_enregistrements_traites === 0) return 100;

        $reussites = $this->nb_insertions + $this->nb_mises_a_jour;
        return round(($reussites / $this->nb_enregistrements_traites) * 100, 2);
    }

    public function getVitesseHumanizedAttribute()
    {
        if (!$this->vitesse_traitement) return 'N/A';

        $vitesse = $this->vitesse_traitement;
        if ($vitesse < 1) return round($vitesse * 60, 1) . '/min';
        return round($vitesse, 1) . '/sec';
    }

    public function getImpactGlobalAttribute()
    {
        $total = $this->nb_insertions + $this->nb_mises_a_jour;

        if ($total === 0) return 'aucun';
        if ($total < 10) return 'faible';
        if ($total < 100) return 'moyen';
        if ($total < 1000) return 'important';
        return 'majeur';
    }

    public function getScorePerformanceAttribute()
    {
        $composantes = [
            'qualite' => $this->score_qualite_global ?? 50,
            'vitesse' => $this->calculerScoreVitesse(),
            'fiabilite' => $this->calculerScoreFiabilite(),
            'completude' => $this->completude_donnees ?? 50
        ];

        return round(array_sum($composantes) / count($composantes), 1);
    }

    public function getEstReussiAttribute()
    {
        return in_array($this->statut, ['succes', 'partiel']) &&
            $this->taux_succes >= 80;
    }

    public function getNecessiteAttentionAttribute()
    {
        return $this->statut === 'erreur' ||
            $this->a_erreurs ||
            $this->taux_succes < 80 ||
            $this->score_qualite_global < 60;
    }

    public function getResumeExecutionAttribute()
    {
        return [
            'statut' => $this->statut_avec_icone,
            'duree' => $this->duree_humanized,
            'traites' => number_format($this->nb_enregistrements_traites),
            'nouveaux' => number_format($this->nb_insertions),
            'modifies' => number_format($this->nb_mises_a_jour),
            'erreurs' => number_format($this->nb_erreurs_total),
            'taux_succes' => $this->taux_succes . '%',
            'qualite' => $this->score_qualite_global . '/100'
        ];
    }

    // ===================================================================
    // METHODS PRINCIPALES
    // ===================================================================

    /**
     * Démarrer l'import
     */
    public function demarrer($parametres = [])
    {
        $this->update([
            'statut' => 'en_cours',
            'date_debut' => now(),
            'tentative_actuelle' => ($this->tentative_actuelle ?? 0) + 1,
            'parametres_import' => array_merge($this->parametres_import ?? [], $parametres),
            'etape_actuelle' => 'extraction',
            'pourcentage_completion' => 0
        ]);

        Log::info("Import démarré: {$this->nom_import}", [
            'import_id' => $this->id,
            'source' => $this->source->nom,
            'type' => $this->type_donnees
        ]);

        return $this;
    }

    /**
     * Mettre à jour le progrès
     */
    public function mettreAJourProgres($etape, $pourcentage, $donnees = [])
    {
        $update = [
            'etape_actuelle' => $etape,
            'pourcentage_completion' => min(100, max(0, $pourcentage)),
            'nb_enregistrements_traites' => $donnees['traites'] ?? $this->nb_enregistrements_traites
        ];

        // Mettre à jour les compteurs spécifiques si fournis
        foreach (['insertions', 'mises_a_jour', 'erreurs_donnees'] as $champ) {
            if (isset($donnees[$champ])) {
                $update["nb_{$champ}"] = $donnees[$champ];
            }
        }

        $this->update($update);

        return $this;
    }

    /**
     * Terminer avec succès
     */
    public function terminerAvecSucces($resume = [])
    {
        $this->update([
            'statut' => $this->nb_erreurs_total > 0 ? 'partiel' : 'succes',
            'statut_detaille' => $this->genererStatutDetaille(),
            'date_fin' => now(),
            'duree_execution' => $this->date_debut ?
                $this->date_debut->diffInMilliseconds(now()) : null,
            'pourcentage_completion' => 100,
            'etape_actuelle' => 'termine',
            'vitesse_traitement' => $this->calculerVitesseTraitement(),
            'score_qualite_global' => $this->calculerScoreQualite(),
            'impact_sur_modeles' => $this->analyserImpactModeles()
        ]);

        // Mettre à jour les métriques de la source
        $this->source->incrementerCompteurs(true);

        Log::info("Import terminé avec succès: {$this->nom_import}", [
            'import_id' => $this->id,
            'resume' => $this->resume_execution
        ]);

        return $this;
    }

    /**
     * Terminer en erreur
     */
    public function terminerEnErreur($erreur, $details = [])
    {
        $this->update([
            'statut' => 'erreur',
            'statut_detaille' => 'Erreur: ' . $erreur,
            'date_fin' => now(),
            'duree_execution' => $this->date_debut ?
                $this->date_debut->diffInMilliseconds(now()) : null,
            'a_erreurs' => true,
            'derniere_erreur' => $erreur,
            'erreurs_critiques' => array_merge($this->erreurs_critiques ?? [], [$erreur]),
            'prochaine_tentative' => $this->calculerProchaineeTentative()
        ]);

        // Mettre à jour les métriques de la source
        $this->source->incrementerCompteurs(false);

        Log::error("Import terminé en erreur: {$this->nom_import}", [
            'import_id' => $this->id,
            'erreur' => $erreur,
            'details' => $details
        ]);

        return $this;
    }

    /**
     * Ajouter une erreur sans arrêter l'import
     */
    public function ajouterErreur($erreur, $critique = false)
    {
        $this->increment('nb_erreurs_total');
        $this->update(['a_erreurs' => true]);

        if ($critique) {
            $erreursCritiques = $this->erreurs_critiques ?? [];
            $erreursCritiques[] = $erreur;
            $this->update(['erreurs_critiques' => $erreursCritiques]);
        } else {
            $erreursMineurs = $this->erreurs_mineures ?? [];
            $erreursMineurs[] = $erreur;
            $this->update(['erreurs_mineures' => $erreursMineurs]);
        }

        return $this;
    }

    /**
     * Traiter les données importées pour les modèles Tennis
     */
    public function traiterDonneesJoueurs($donneesJoueurs)
    {
        $joueursTraites = 0;
        $joueursInseres = 0;
        $joursMisAJour = 0;
        $erreurs = [];

        foreach ($donneesJoueurs as $donneeJoueur) {
            try {
                // Appliquer le mapping des champs
                $donneesMappees = $this->appliquerMapping($donneeJoueur, 'joueur');

                // Validation
                if (!$this->validerDonnees($donneesMappees, 'joueur')) {
                    $this->ajouterErreur("Validation échouée pour joueur: " . json_encode($donneeJoueur));
                    continue;
                }

                // Rechercher joueur existant
                $joueur = Joueur::where('nom', $donneesMappees['nom'])
                    ->where('prenom', $donneesMappees['prenom'])
                    ->first();

                if ($joueur) {
                    // Mise à jour
                    $joueur->update($donneesMappees);
                    $joursMisAJour++;
                } else {
                    // Insertion
                    $joueur = Joueur::create($donneesMappees);
                    $joueursInseres++;
                }

                // Lier à l'import
                $this->joueursAffectes()->attach($joueur->id);
                $joueursTraites++;

            } catch (\Exception $e) {
                $erreurs[] = $e->getMessage();
                $this->ajouterErreur("Erreur traitement joueur: " . $e->getMessage());
            }
        }

        // Mettre à jour les compteurs
        $this->update([
            'nb_enregistrements_traites' => $joueursTraites,
            'joueurs_importes' => $joueursInseres,
            'joueurs_mis_a_jour' => $joursMisAJour,
            'nb_erreurs_donnees' => count($erreurs)
        ]);

        return [
            'traites' => $joueursTraites,
            'inseres' => $joueursInseres,
            'mis_a_jour' => $joursMisAJour,
            'erreurs' => $erreurs
        ];
    }

    /**
     * Générer rapport détaillé
     */
    public function genererRapport()
    {
        return [
            'identifiant' => $this->identifiant_unique,
            'execution' => $this->resume_execution,
            'source' => [
                'nom' => $this->source->nom,
                'type' => $this->source->type_donnees,
                'fiabilite' => $this->source->fiabilite_score
            ],
            'donnees' => [
                'type' => $this->type_donnees,
                'surface' => $this->surface_concernee,
                'periode' => $this->periode_donnees,
                'saison' => $this->saison_tennis
            ],
            'resultats' => [
                'joueurs' => [
                    'importes' => $this->joueurs_importes,
                    'mis_a_jour' => $this->joueurs_mis_a_jour
                ],
                'matchs' => [
                    'importes' => $this->matchs_importes,
                    'mis_a_jour' => $this->matchs_mis_a_jour
                ],
                'tournois' => [
                    'importes' => $this->tournois_importes,
                    'mis_a_jour' => $this->tournois_mis_a_jour
                ]
            ],
            'qualite' => [
                'score_global' => $this->score_qualite_global,
                'completude' => $this->completude_donnees,
                'coherence' => $this->coherence_donnees,
                'fraicheur' => $this->fraicheur_donnees
            ],
            'performance' => [
                'duree' => $this->duree_humanized,
                'vitesse' => $this->vitesse_humanized,
                'memoire' => $this->memoire_utilisee_mo . ' Mo',
                'score' => $this->score_performance
            ],
            'erreurs' => [
                'total' => $this->nb_erreurs_total,
                'critiques' => count($this->erreurs_critiques ?? []),
                'mineures' => count($this->erreurs_mineures ?? [])
            ],
            'impact_ia' => [
                'utilise' => $this->utilise_pour_ia,
                'modeles_affectes' => $this->modeles_ia_affectes,
                'recalcul_necessaire' => $this->recalcul_necessaire,
                'score_confiance' => $this->score_confiance_ia
            ]
        ];
    }

    // ===================================================================
    // METHODS PRIVÉES
    // ===================================================================

    private function appliquerMapping($donnees, $typeModele)
    {
        $mapping = $this->mapping_champs[$typeModele] ?? [];
        $resultat = [];

        foreach ($mapping as $champSource => $champCible) {
            if (isset($donnees[$champSource])) {
                $resultat[$champCible] = $donnees[$champSource];
            }
        }

        return $resultat;
    }

    private function validerDonnees($donnees, $typeModele)
    {
        $regles = $this->regles_validation[$typeModele] ?? [];

        foreach ($regles as $champ => $regle) {
            if (!$this->validerChamp($donnees[$champ] ?? null, $regle)) {
                return false;
            }
        }

        return true;
    }

    private function validerChamp($valeur, $regle)
    {
        // Implémentation basique de validation
        if ($regle === 'required' && empty($valeur)) return false;
        if (strpos($regle, 'max:') === 0) {
            $max = (int) substr($regle, 4);
            if (strlen($valeur) > $max) return false;
        }

        return true;
    }

    private function calculerVitesseTraitement()
    {
        if (!$this->duree_execution || $this->duree_execution === 0) return 0;

        return round($this->nb_enregistrements_traites / ($this->duree_execution / 1000), 2);
    }

    private function calculerScoreQualite()
    {
        $composantes = [
            'completude' => $this->completude_donnees ?? 80,
            'coherence' => $this->coherence_donnees ?? 80,
            'precision' => $this->precision_estimee ?? 80,
            'taux_succes' => $this->taux_succes
        ];

        return round(array_sum($composantes) / count($composantes), 1);
    }

    private function analyserImpactModeles()
    {
        return [
            'joueurs_impactes' => $this->joueurs_importes + $this->joueurs_mis_a_jour,
            'matchs_impactes' => $this->matchs_importes + $this->matchs_mis_a_jour,
            'classements_impactes' => $this->classements_importes,
            'recalcul_elo_necessaire' => $this->joueurs_mis_a_jour > 0
        ];
    }

    private function genererStatutDetaille()
    {
        if ($this->statut === 'succes') {
            return "Import réussi: {$this->nb_insertions} nouveaux, {$this->nb_mises_a_jour} modifiés";
        }

        return "Import partiel: {$this->nb_erreurs_total} erreurs détectées";
    }

    private function calculerProchaineeTentative()
    {
        if ($this->tentative_actuelle >= $this->nb_tentatives) return null;

        $delai = $this->delai_retry_seconds ?? 300; // 5 minutes par défaut

        if ($this->strategy_retry === 'exponential') {
            $delai *= pow(2, $this->tentative_actuelle - 1);
        }

        return now()->addSeconds($delai);
    }

    private function calculerScoreVitesse() { return 75; }
    private function calculerScoreFiabilite() { return 85; }

    // ===================================================================
    // VALIDATION RULES
    // ===================================================================

    public static function validationRules()
    {
        return [
            'source_donnees_id' => 'required|exists:source_donnees,id',
            'nom_import' => 'required|string|max:255',
            'type_donnees' => 'required|in:joueurs,matchs,tournois,classements,statistiques',
            'statut' => 'required|in:en_attente,en_cours,succes,erreur,partiel,annule',
            'priorite' => 'integer|between:1,10'
        ];
    }

    // ===================================================================
    // BOOT METHODS
    // ===================================================================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($import) {
            $import->identifiant_unique = $import->identifiant_unique ?? \Str::uuid();
            $import->statut = $import->statut ?? 'en_attente';
            $import->priorite = $import->priorite ?? 5;
            $import->nb_tentatives = $import->nb_tentatives ?? 3;
            $import->tentative_actuelle = $import->tentative_actuelle ?? 0;
        });
    }
}
