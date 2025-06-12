<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SourceDonnees extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'source_donnees';

    protected $fillable = [
        // Informations de base
        'nom',
        'description',
        'url_base',
        'url_documentation',
        'fournisseur',
        'contact_support',

        // Type et format des données
        'type_donnees',           // 'atp', 'wta', 'itf', 'betting', 'statistics', 'weather'
        'categories_donnees',     // JSON: ['joueurs', 'matchs', 'tournois', 'classements']
        'format_donnees',         // 'json', 'xml', 'csv', 'html', 'api_rest', 'websocket'
        'version_api',
        'protocole',              // 'https', 'http', 'ftp', 'sftp'
        'encoding',               // 'utf-8', 'iso-8859-1'

        // Authentification et sécurité
        'necessite_auth',         // boolean
        'type_auth',              // 'api_key', 'oauth', 'basic', 'bearer', 'none'
        'cle_api',
        'secret_api',
        'token_access',
        'token_refresh',
        'expire_token',
        'headers_auth',           // JSON pour headers personnalisés
        'params_auth',            // JSON pour paramètres d'auth

        // Configuration synchronisation
        'frequence_maj',          // 'temps_reel', 'horaire', 'quotidien', 'hebdomadaire', 'manuel'
        'heure_maj_quotidienne',  // Format HH:MM pour sync quotidienne
        'jours_maj_hebdomadaire', // JSON: ['lundi', 'mercredi', 'vendredi']
        'intervalle_minutes',     // Pour fréquence horaire/temps réel
        'timezone',               // Timezone de la source

        // Limites et quotas
        'limite_requetes_heure',
        'limite_requetes_jour',
        'limite_requetes_mois',
        'delai_entre_requetes',   // millisecondes
        'timeout_requete',        // secondes
        'retry_max',
        'backoff_strategy',       // 'linear', 'exponential', 'fixed'

        // Endpoints spécifiques tennis
        'endpoint_joueurs',
        'endpoint_matchs',
        'endpoint_tournois',
        'endpoint_classements',
        'endpoint_statistiques',
        'endpoint_cotes',         // Pour sites de paris
        'endpoint_meteo',         // Conditions météo
        'endpoint_blessures',

        // Paramètres de requête
        'params_defaut',          // JSON des paramètres par défaut
        'format_date_source',     // Format date de la source
        'pagination_type',        // 'offset', 'page', 'cursor', 'none'
        'pagination_params',      // JSON config pagination
        'limite_par_page',

        // Mapping et transformation
        'mapping_champs',         // JSON mapping champs source -> modèle
        'transformations',        // JSON règles de transformation
        'filtres_donnees',        // JSON filtres à appliquer
        'validation_rules',       // JSON règles de validation

        // Monitoring et qualité
        'actif',
        'priorite',               // 1-10 (1 = très haute, 10 = très basse)
        'fiabilite_score',        // 1-100 score de fiabilité
        'precision_donnees',      // 1-100 précision des données
        'fraicheur_donnees',      // Âge max acceptable des données (heures)
        'seuil_alerte_erreurs',   // Nombre d'erreurs pour déclencher alerte

        // Statistiques utilisation
        'derniere_synchronisation',
        'prochaine_synchronisation',
        'derniere_reponse_ok',
        'nb_requetes_total',
        'nb_requetes_succes',
        'nb_requetes_erreur',
        'nb_donnees_importees',
        'taille_moyenne_reponse', // KB
        'temps_reponse_moyen',    // millisecondes

        // Gestion d'erreurs
        'derniere_erreur',
        'nb_erreurs_consecutives',
        'statut_connexion',       // 'ok', 'erreur', 'timeout', 'quota_depasse', 'maintenance'
        'message_erreur',
        'code_erreur_http',
        'en_maintenance',
        'maintenance_jusqu',

        // Coûts et business
        'cout_par_requete',       // Coût en centimes
        'cout_mensuel_estime',
        'budget_mensuel_limite',
        'plan_abonnement',        // 'gratuit', 'basique', 'premium', 'enterprise'
        'date_expiration_plan',

        // Métadonnées
        'pays_couverture',        // JSON des pays couverts
        'surfaces_couverture',    // JSON des surfaces couvertes
        'niveaux_tournois',       // JSON des niveaux couverts
        'annees_historique',      // Combien d'années d'historique
        'langue_donnees',
        'notes_techniques',
        'changelog_api'           // JSON des changements d'API
    ];

    protected $casts = [
        // Dates
        'derniere_synchronisation' => 'datetime',
        'prochaine_synchronisation' => 'datetime',
        'derniere_reponse_ok' => 'datetime',
        'expire_token' => 'datetime',
        'date_expiration_plan' => 'date',
        'maintenance_jusqu' => 'datetime',

        // JSON
        'categories_donnees' => 'json',
        'headers_auth' => 'json',
        'params_auth' => 'json',
        'jours_maj_hebdomadaire' => 'json',
        'params_defaut' => 'json',
        'pagination_params' => 'json',
        'mapping_champs' => 'json',
        'transformations' => 'json',
        'filtres_donnees' => 'json',
        'validation_rules' => 'json',
        'pays_couverture' => 'json',
        'surfaces_couverture' => 'json',
        'niveaux_tournois' => 'json',
        'changelog_api' => 'json',

        // Booléens
        'necessite_auth' => 'boolean',
        'actif' => 'boolean',
        'en_maintenance' => 'boolean',

        // Entiers
        'limite_requetes_heure' => 'integer',
        'limite_requetes_jour' => 'integer',
        'limite_requetes_mois' => 'integer',
        'delai_entre_requetes' => 'integer',
        'timeout_requete' => 'integer',
        'retry_max' => 'integer',
        'limite_par_page' => 'integer',
        'priorite' => 'integer',
        'fiabilite_score' => 'integer',
        'precision_donnees' => 'integer',
        'fraicheur_donnees' => 'integer',
        'seuil_alerte_erreurs' => 'integer',
        'nb_requetes_total' => 'integer',
        'nb_requetes_succes' => 'integer',
        'nb_requetes_erreur' => 'integer',
        'nb_donnees_importees' => 'integer',
        'taille_moyenne_reponse' => 'integer',
        'temps_reponse_moyen' => 'integer',
        'nb_erreurs_consecutives' => 'integer',
        'code_erreur_http' => 'integer',
        'annees_historique' => 'integer',

        // Décimaux
        'cout_par_requete' => 'decimal:4',
        'cout_mensuel_estime' => 'decimal:2',
        'budget_mensuel_limite' => 'decimal:2'
    ];

    protected $appends = [
        'statut_global',
        'fiabilite_niveau',
        'taux_succes',
        'cout_actuel_mois',
        'quota_utilise_jour',
        'prochaine_sync_humanized',
        'performance_score',
        'est_operationnelle',
        'categories_supportees'
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function imports()
    {
        return $this->hasMany(ImportDonnees::class);
    }

    public function dernier_import()
    {
        return $this->hasOne(ImportDonnees::class)->latest();
    }

    public function imports_succes()
    {
        return $this->hasMany(ImportDonnees::class)->where('statut', 'succes');
    }

    public function imports_erreur()
    {
        return $this->hasMany(ImportDonnees::class)->where('statut', 'erreur');
    }

    public function logs_synchronisation()
    {
        return $this->hasMany(LogSynchronisation::class);
    }

    // ===================================================================
    // SCOPES
    // ===================================================================

    public function scopeActives($query)
    {
        return $query->where('actif', true);
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type_donnees', $type);
    }

    public function scopeOperationnelles($query)
    {
        return $query->where('actif', true)
            ->where('statut_connexion', 'ok')
            ->where('en_maintenance', false);
    }

    public function scopeHautePriorite($query)
    {
        return $query->where('priorite', '<=', 3);
    }

    public function scopeHauteFiabilite($query)
    {
        return $query->where('fiabilite_score', '>=', 80);
    }

    public function scopeTempsReel($query)
    {
        return $query->where('frequence_maj', 'temps_reel');
    }

    public function scopeNecessitantSync($query)
    {
        return $query->where('actif', true)
            ->where(function($q) {
                $q->whereNull('prochaine_synchronisation')
                    ->orWhere('prochaine_synchronisation', '<=', now());
            });
    }

    public function scopeEnErreur($query)
    {
        return $query->where('statut_connexion', '!=', 'ok');
    }

    public function scopeParSurface($query, $surface)
    {
        return $query->whereJsonContains('surfaces_couverture', $surface);
    }

    public function scopeCouvrantPays($query, $pays)
    {
        return $query->whereJsonContains('pays_couverture', $pays);
    }

    public function scopeGratuites($query)
    {
        return $query->where('plan_abonnement', 'gratuit');
    }

    public function scopePayantes($query)
    {
        return $query->where('plan_abonnement', '!=', 'gratuit');
    }

    // ===================================================================
    // ACCESSORS
    // ===================================================================

    public function getStatutGlobalAttribute()
    {
        if (!$this->actif) return 'inactif';
        if ($this->en_maintenance) return 'maintenance';
        if ($this->statut_connexion === 'quota_depasse') return 'quota_depasse';
        if ($this->nb_erreurs_consecutives >= $this->seuil_alerte_erreurs) return 'alerte';
        if ($this->statut_connexion === 'ok') return 'operationnel';
        return 'erreur';
    }

    public function getFiabiliteNiveauAttribute()
    {
        $score = $this->fiabilite_score ?? 50;

        if ($score >= 90) return 'excellent';
        if ($score >= 80) return 'bon';
        if ($score >= 60) return 'moyen';
        if ($score >= 40) return 'faible';
        return 'tres_faible';
    }

    public function getTauxSuccesAttribute()
    {
        if ($this->nb_requetes_total === 0) return 100;

        return round(($this->nb_requetes_succes / $this->nb_requetes_total) * 100, 2);
    }

    public function getCoutActuelMoisAttribute()
    {
        // Calculer coût du mois en cours
        $requetesMois = $this->imports()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return ($requetesMois * $this->cout_par_requete) / 100; // Convertir centimes en euros
    }

    public function getQuotaUtiliseJourAttribute()
    {
        if (!$this->limite_requetes_jour) return 0;

        $requetesAujourdhui = $this->imports()
            ->whereDate('created_at', today())
            ->count();

        return round(($requetesAujourdhui / $this->limite_requetes_jour) * 100, 1);
    }

    public function getProchaineSyncHumanizedAttribute()
    {
        return $this->prochaine_synchronisation?->diffForHumans();
    }

    public function getPerformanceScoreAttribute()
    {
        $composantes = [
            'fiabilite' => $this->fiabilite_score ?? 50,
            'taux_succes' => $this->taux_succes,
            'temps_reponse' => $this->calculerScoreTempsReponse(),
            'disponibilite' => $this->calculerScoreDisponibilite()
        ];

        return round(array_sum($composantes) / count($composantes), 1);
    }

    public function getEstOperationnelleAttribute()
    {
        return $this->actif &&
            $this->statut_connexion === 'ok' &&
            !$this->en_maintenance &&
            $this->nb_erreurs_consecutives < $this->seuil_alerte_erreurs;
    }

    public function getCategoriesSupporteesAttribute()
    {
        return $this->categories_donnees ?? [];
    }

    // ===================================================================
    // METHODS PRINCIPALES
    // ===================================================================

    /**
     * Tester la connexion à la source
     */
    public function testerConnexion()
    {
        try {
            $startTime = microtime(true);

            $response = Http::timeout($this->timeout_requete ?? 30)
                ->withHeaders($this->getHeadersAuth())
                ->get($this->url_base, $this->params_defaut ?? []);

            $endTime = microtime(true);
            $tempsReponse = round(($endTime - $startTime) * 1000); // millisecondes

            if ($response->successful()) {
                $this->update([
                    'statut_connexion' => 'ok',
                    'derniere_reponse_ok' => now(),
                    'temps_reponse_moyen' => $this->calculerTempsReponseMoyen($tempsReponse),
                    'nb_erreurs_consecutives' => 0,
                    'message_erreur' => null
                ]);

                return [
                    'succes' => true,
                    'temps_reponse' => $tempsReponse,
                    'taille_reponse' => strlen($response->body())
                ];
            } else {
                $this->enregistrerErreur($response->status(), $response->body());
                return ['succes' => false, 'erreur' => 'HTTP ' . $response->status()];
            }

        } catch (\Exception $e) {
            $this->enregistrerErreur(0, $e->getMessage());
            return ['succes' => false, 'erreur' => $e->getMessage()];
        }
    }

    /**
     * Effectuer une requête à l'API
     */
    public function effectuerRequete($endpoint, $params = [])
    {
        if (!$this->est_operationnelle) {
            throw new \Exception("Source non opérationnelle: {$this->statut_global}");
        }

        // Vérifier quotas
        if (!$this->verifierQuotas()) {
            throw new \Exception("Quota dépassé pour la source {$this->nom}");
        }

        // Attendre si nécessaire (rate limiting)
        $this->respecterRateLimit();

        try {
            $url = rtrim($this->url_base, '/') . '/' . ltrim($endpoint, '/');
            $parametres = array_merge($this->params_defaut ?? [], $params);

            $response = Http::timeout($this->timeout_requete ?? 30)
                ->withHeaders($this->getHeadersAuth())
                ->get($url, $parametres);

            $this->incrementerCompteurs($response->successful());

            if ($response->successful()) {
                return $this->traiterReponse($response);
            } else {
                $this->enregistrerErreur($response->status(), $response->body());
                throw new \Exception("Erreur API: HTTP {$response->status()}");
            }

        } catch (\Exception $e) {
            $this->enregistrerErreur(0, $e->getMessage());
            throw $e;
        }
    }

    /**
     * Synchroniser les données de cette source
     */
    public function synchroniser($categoriesSpecifiques = null)
    {
        $categories = $categoriesSpecifiques ?? $this->categories_donnees ?? [];
        $resultats = [];

        foreach ($categories as $categorie) {
            try {
                $endpoint = $this->getEndpointPourCategorie($categorie);
                if (!$endpoint) continue;

                $donnees = $this->effectuerRequete($endpoint);
                $importees = $this->importerDonnees($categorie, $donnees);

                $resultats[$categorie] = [
                    'succes' => true,
                    'nb_importees' => $importees
                ];

            } catch (\Exception $e) {
                $resultats[$categorie] = [
                    'succes' => false,
                    'erreur' => $e->getMessage()
                ];

                Log::error("Erreur sync {$this->nom} - {$categorie}: " . $e->getMessage());
            }
        }

        $this->calculerProchaineSynchronisation();

        return $resultats;
    }

    /**
     * Calculer le score de fiabilité
     */
    public function calculerScoreFiabilite()
    {
        $composantes = [
            'taux_succes' => $this->taux_succes,
            'stabilite' => $this->calculerStabilite(),
            'fraicheur' => $this->calculerFraicheur(),
            'completude' => $this->calculerCompletude()
        ];

        $score = array_sum($composantes) / count($composantes);

        $this->update(['fiabilite_score' => round($score, 1)]);

        return $score;
    }

    /**
     * Obtenir les données formatées pour l'IA
     */
    public function getDonneesFormateesIA()
    {
        return [
            'identifiant' => $this->id,
            'nom' => $this->nom,
            'type' => $this->type_donnees,
            'fiabilite' => $this->fiabilite_score / 100,
            'precision' => $this->precision_donnees / 100,
            'fraicheur' => $this->fraicheur_donnees,
            'performance' => $this->performance_score / 100,
            'cout_unitaire' => $this->cout_par_requete,
            'categories' => $this->categories_donnees,
            'couverture_geo' => $this->pays_couverture,
            'surfaces' => $this->surfaces_couverture,
            'actif' => $this->est_operationnelle
        ];
    }

    // ===================================================================
    // METHODS PRIVÉES
    // ===================================================================

    private function getHeadersAuth()
    {
        $headers = $this->headers_auth ?? [];

        switch ($this->type_auth) {
            case 'api_key':
                $headers['X-API-Key'] = $this->cle_api;
                break;
            case 'bearer':
                $headers['Authorization'] = 'Bearer ' . $this->token_access;
                break;
            case 'basic':
                $headers['Authorization'] = 'Basic ' . base64_encode($this->cle_api . ':' . $this->secret_api);
                break;
        }

        return $headers;
    }

    private function verifierQuotas()
    {
        if ($this->limite_requetes_jour) {
            $requetesAujourdhui = $this->imports()->whereDate('created_at', today())->count();
            if ($requetesAujourdhui >= $this->limite_requetes_jour) {
                $this->update(['statut_connexion' => 'quota_depasse']);
                return false;
            }
        }

        return true;
    }

    private function respecterRateLimit()
    {
        if ($this->delai_entre_requetes) {
            $dernierImport = $this->imports()->latest()->first();
            if ($dernierImport) {
                $delaiEcoule = $dernierImport->created_at->diffInMilliseconds(now());
                if ($delaiEcoule < $this->delai_entre_requetes) {
                    usleep(($this->delai_entre_requetes - $delaiEcoule) * 1000);
                }
            }
        }
    }

    private function traiterReponse($response)
    {
        $donnees = null;

        switch ($this->format_donnees) {
            case 'json':
                $donnees = $response->json();
                break;
            case 'xml':
                $donnees = simplexml_load_string($response->body());
                break;
            case 'csv':
                $donnees = str_getcsv($response->body());
                break;
            default:
                $donnees = $response->body();
        }

        // Appliquer transformations si définies
        if ($this->transformations) {
            $donnees = $this->appliquerTransformations($donnees);
        }

        return $donnees;
    }

    private function enregistrerErreur($codeHttp, $message)
    {
        $this->increment('nb_erreurs_consecutives');
        $this->update([
            'derniere_erreur' => now(),
            'code_erreur_http' => $codeHttp,
            'message_erreur' => $message,
            'statut_connexion' => $codeHttp === 0 ? 'timeout' : 'erreur'
        ]);
    }

    private function incrementerCompteurs($succes)
    {
        $this->increment('nb_requetes_total');
        if ($succes) {
            $this->increment('nb_requetes_succes');
        } else {
            $this->increment('nb_requetes_erreur');
        }
    }

    private function getEndpointPourCategorie($categorie)
    {
        $mapping = [
            'joueurs' => $this->endpoint_joueurs,
            'matchs' => $this->endpoint_matchs,
            'tournois' => $this->endpoint_tournois,
            'classements' => $this->endpoint_classements,
            'statistiques' => $this->endpoint_statistiques,
            'cotes' => $this->endpoint_cotes,
            'meteo' => $this->endpoint_meteo,
            'blessures' => $this->endpoint_blessures
        ];

        return $mapping[$categorie] ?? null;
    }

    private function importerDonnees($categorie, $donnees)
    {
        // Cette méthode sera implémentée selon la logique métier
        // Elle devra créer les ImportDonnees et traiter les données
        return 0;
    }

    private function calculerProchaineSynchronisation()
    {
        $prochaine = null;

        switch ($this->frequence_maj) {
            case 'temps_reel':
                $prochaine = now()->addMinutes($this->intervalle_minutes ?? 5);
                break;
            case 'horaire':
                $prochaine = now()->addHour();
                break;
            case 'quotidien':
                $heure = $this->heure_maj_quotidienne ?? '06:00';
                $prochaine = now()->addDay()->setTimeFromTimeString($heure);
                break;
            case 'hebdomadaire':
                $jours = $this->jours_maj_hebdomadaire ?? ['lundi'];
                // Logic pour calculer le prochain jour de la semaine
                break;
        }

        if ($prochaine) {
            $this->update(['prochaine_synchronisation' => $prochaine]);
        }
    }

    // Méthodes de calcul des scores (à implémenter selon les besoins)
    private function calculerScoreTempsReponse() { return 80; }
    private function calculerScoreDisponibilite() { return 90; }
    private function calculerTempsReponseMoyen($nouveau) { return $nouveau; }
    private function calculerStabilite() { return 85; }
    private function calculerFraicheur() { return 90; }
    private function calculerCompletude() { return 95; }
    private function appliquerTransformations($donnees) { return $donnees; }

    // ===================================================================
    // VALIDATION RULES
    // ===================================================================

    public static function validationRules()
    {
        return [
            'nom' => 'required|string|max:255',
            'url_base' => 'required|url',
            'type_donnees' => 'required|in:atp,wta,itf,betting,statistics,weather',
            'format_donnees' => 'required|in:json,xml,csv,html,api_rest,websocket',
            'frequence_maj' => 'required|in:temps_reel,horaire,quotidien,hebdomadaire,manuel',
            'priorite' => 'required|integer|between:1,10',
            'timeout_requete' => 'required|integer|min:5|max:300',
            'actif' => 'boolean'
        ];
    }

    // ===================================================================
    // BOOT METHODS
    // ===================================================================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($source) {
            // Valeurs par défaut
            $source->actif = $source->actif ?? true;
            $source->priorite = $source->priorite ?? 5;
            $source->fiabilite_score = $source->fiabilite_score ?? 50;
            $source->precision_donnees = $source->precision_donnees ?? 50;
            $source->timeout_requete = $source->timeout_requete ?? 30;
            $source->retry_max = $source->retry_max ?? 3;
            $source->seuil_alerte_erreurs = $source->seuil_alerte_erreurs ?? 5;
        });

        static::saved(function ($source) {
            if ($source->actif && !$source->prochaine_synchronisation) {
                $source->calculerProchaineSynchronisation();
            }
        });
    }
}
