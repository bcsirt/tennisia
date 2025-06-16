<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Surface extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'surfaces';

    protected $fillable = [
        // Identification de base
        'nom',
        'code',                     // 'dur', 'terre_battue', 'gazon', 'indoor'
        'nom_technique',            // Nom technique officiel
        'vitesse_surface_id',
        'couleur_principale',       // Couleur dominante
        'texture_visuelle',         // Description texture

        // Caractéristiques physiques CRUCIALES
        'vitesse_numerique',        // Vitesse sur échelle 0-100
        'coefficient_rebond',       // Hauteur rebond (0-100)
        'coefficient_friction',     // Friction/adhérence (0-100)
        'absorption_energie',       // Absorption choc (0-100)
        'regularite_rebond',        // Consistance rebond (0-100)
        'resistance_glissement',    // Résistance glissades (0-100)

        // Impact sur styles de jeu (ESSENTIEL prédictions)
        'avantage_serveurs',        // Avantage aux gros serveurs (0-100)
        'avantage_baseliners',      // Avantage joueurs fond de court (0-100)
        'avantage_attaquants',      // Avantage jeu d'attaque (0-100)
        'avantage_contres',         // Avantage contre-attaquants (0-100)
        'avantage_puissance',       // Avantage jeu puissant (0-100)
        'avantage_precision',       // Avantage jeu précis (0-100)
        'avantage_endurance',       // Avantage endurants (0-100)
        'avantage_vitesse',         // Avantage joueurs rapides (0-100)

        // Effets techniques sur balles
        'effet_lift_influence',     // Influence du lift (0-100)
        'effet_slice_influence',    // Influence du slice (0-100)
        'effet_amorti_efficacite',  // Efficacité amortis (0-100)
        'effet_passing_facilite',   // Facilité passing shots (0-100)
        'trajectoire_modification', // Modification trajectoires (0-100)

        // Conditions météo et environnement
        'sensibilite_chaleur',      // Sensibilité chaleur (0-100)
        'sensibilite_froid',        // Sensibilité froid (0-100)
        'sensibilite_humidite',     // Sensibilité humidité (0-100)
        'sensibilite_vent',         // Sensibilité vent (0-100)
        'impact_pluie',             // Impact pluie (0-100)
        'temps_sechage',            // Temps séchage après pluie (minutes)

        // Usure et fatigue physique
        'impact_fatigue_jambes',    // Fatigue jambes (0-100)
        'impact_fatigue_generale',  // Fatigue générale (0-100)
        'risque_blessures',         // Risque blessures (0-100)
        'zones_corps_sollicitees',  // JSON: zones corps plus sollicitées
        'recuperation_entre_points', // Temps récupération optimal

        // Duree et rythme de jeu
        'duree_moyenne_points',     // Durée moyenne points (secondes)
        'duree_moyenne_matchs',     // Durée moyenne matchs (minutes)
        'facteur_rallyes_longs',    // Fréquence rallyes longs (0-100)
        'rythme_jeu_influence',     // Influence sur rythme (0-100)
        'breaks_frequence',         // Fréquence breaks de service (0-100)

        // Spécificités tactiques
        'importance_premier_service', // Importance 1er service (0-100)
        'efficacite_retour',        // Facilité retour service (0-100)
        'jeu_filet_facilite',       // Facilité montées au filet (0-100)
        'defense_efficacite',       // Efficacité jeu défensif (0-100)
        'variation_tactique_needed', // Besoin variations tactiques (0-100)

        // Conditions spéciales
        'altitude_optimale',        // Altitude optimale (mètres)
        'temperature_ideale',       // Température idéale (Celsius)
        'humidite_ideale',          // Humidité idéale (%)
        'conditions_indoor_outdoor', // 'indoor', 'outdoor', 'both'
        'eclairage_influence',      // Influence éclairage artificiel

        // Maintenance et qualité
        'frequence_maintenance',    // Jours entre maintenances
        'duree_vie_surface',        // Durée de vie (années)
        'cout_maintenance',         // Coût maintenance annuel
        'qualite_standard',         // Standard qualité (0-100)
        'variations_qualite',       // Variations possibles qualité

        // Historique et évolution
        'premiere_utilisation',     // Date première utilisation pro
        'evolution_technologique',  // Évolutions récentes
        'tournois_principaux',      // JSON: tournois utilisant cette surface
        'adoption_mondiale',        // % courts dans le monde

        // Influence psychologique
        'factor_intimidation',      // Facteur intimidation (0-100)
        'adaptation_temps_requis',  // Temps adaptation requis (jours)
        'avantage_experience',      // Avantage expérience surface (0-100)
        'stress_adaptation',        // Stress lié adaptation (0-100)

        // Données statistiques pros
        'pourcentage_aces_moyen',   // % aces moyen sur cette surface
        'pourcentage_doubles_fautes', // % doubles fautes moyen
        'longueur_rallyes_moyenne', // Nombre coups par rallye
        'breaks_par_set_moyen',     // Breaks de service par set
        'tie_breaks_frequence',     // % sets allant au tie-break

        // Spécialistes et anti-spécialistes
        'nb_specialistes_historiques', // Nb spécialistes reconnus
        'specialistes_celebres',    // JSON: joueurs célèbres sur surface
        'style_jeu_optimal',        // Style optimal pour cette surface
        'handicaps_frequents',      // Handicaps fréquents autres styles

        // Prédictivité et IA
        'predictibilite_resultats', // Prédictibilité résultats (0-100)
        'facteur_surprise',         // Potentiel upsets (0-100)
        'importance_classement',    // Importance classement ATP (0-100)
        'importance_forme',         // Importance forme récente (0-100)
        'importance_h2h',           // Importance H2H (0-100)
        'importance_experience',    // Importance expérience surface (0-100)

        // Métadonnées
        'date_derniere_analyse',
        'source_donnees_physiques', // Source analyses physiques
        'validee_par_experts',      // Validation par experts
        'niveau_certitude_donnees', // Certitude données (0-100)
        'derniere_maj_caracteristiques',
        'commentaires_experts',      // Notes experts tennis
    ];

    protected $casts = [
        // Caractéristiques physiques
        'vitesse_numerique' => 'decimal:1',
        'coefficient_rebond' => 'decimal:1',
        'coefficient_friction' => 'decimal:1',
        'absorption_energie' => 'decimal:1',
        'regularite_rebond' => 'decimal:1',
        'resistance_glissement' => 'decimal:1',

        // Avantages styles de jeu
        'avantage_serveurs' => 'decimal:1',
        'avantage_baseliners' => 'decimal:1',
        'avantage_attaquants' => 'decimal:1',
        'avantage_contres' => 'decimal:1',
        'avantage_puissance' => 'decimal:1',
        'avantage_precision' => 'decimal:1',
        'avantage_endurance' => 'decimal:1',
        'avantage_vitesse' => 'decimal:1',

        // Effets techniques
        'effet_lift_influence' => 'decimal:1',
        'effet_slice_influence' => 'decimal:1',
        'effet_amorti_efficacite' => 'decimal:1',
        'effet_passing_facilite' => 'decimal:1',
        'trajectoire_modification' => 'decimal:1',

        // Sensibilités environnement
        'sensibilite_chaleur' => 'decimal:1',
        'sensibilite_froid' => 'decimal:1',
        'sensibilite_humidite' => 'decimal:1',
        'sensibilite_vent' => 'decimal:1',
        'impact_pluie' => 'decimal:1',

        // Fatigue et physique
        'impact_fatigue_jambes' => 'decimal:1',
        'impact_fatigue_generale' => 'decimal:1',
        'risque_blessures' => 'decimal:1',

        // Durées et rythmes
        'duree_moyenne_points' => 'decimal:1',
        'duree_moyenne_matchs' => 'integer',
        'facteur_rallyes_longs' => 'decimal:1',
        'rythme_jeu_influence' => 'decimal:1',
        'breaks_frequence' => 'decimal:1',

        // Conditions optimales
        'altitude_optimale' => 'integer',
        'temperature_ideale' => 'integer',
        'humidite_ideale' => 'integer',
        'temps_sechage' => 'integer',
        'recuperation_entre_points' => 'integer',
        'frequence_maintenance' => 'integer',

        // Coûts et durées
        'duree_vie_surface' => 'integer',
        'cout_maintenance' => 'decimal:2',
        'qualite_standard' => 'decimal:1',
        'adaptation_temps_requis' => 'integer',

        // Statistiques
        'pourcentage_aces_moyen' => 'decimal:1',
        'pourcentage_doubles_fautes' => 'decimal:1',
        'longueur_rallyes_moyenne' => 'decimal:1',
        'breaks_par_set_moyen' => 'decimal:1',
        'tie_breaks_frequence' => 'decimal:1',

        // Prédictivité
        'predictibilite_resultats' => 'decimal:1',
        'facteur_surprise' => 'decimal:1',
        'importance_classement' => 'decimal:1',
        'importance_forme' => 'decimal:1',
        'importance_h2h' => 'decimal:1',
        'importance_experience' => 'decimal:1',

        // Spécialistes
        'nb_specialistes_historiques' => 'integer',
        'adoption_mondiale' => 'decimal:1',

        // Facteurs psychologiques
        'factor_intimidation' => 'decimal:1',
        'avantage_experience' => 'decimal:1',
        'stress_adaptation' => 'decimal:1',

        // Qualité données
        'niveau_certitude_donnees' => 'decimal:1',

        // JSON fields
        'zones_corps_sollicitees' => 'array',
        'tournois_principaux' => 'array',
        'specialistes_celebres' => 'array',

        // Booleans
        'validee_par_experts' => 'boolean',

        // Dates
        'premiere_utilisation' => 'date',
        'date_derniere_analyse' => 'date',
        'derniere_maj_caracteristiques' => 'date',
    ];

    protected $appends = [
        'caracteristiques_dominantes',
        'style_jeu_favorise',
        'niveau_difficulte_adaptation',
        'impact_meteorologique',
        'profil_joueur_ideal',
        'facteurs_cles_prediction',
        'indice_spectacle',
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function vitesse()
    {
        return $this->belongsTo(VitesseSurface::class, 'vitesse_surface_id');
    }

    public function tournois()
    {
        return $this->hasMany(Tournoi::class);
    }

    public function matchs()
    {
        return $this->hasMany(MatchTennis::class);
    }

    public function statistiquesJoueurs()
    {
        return $this->hasMany(StatistiqueJoueur::class);
    }

    public function configurationsIA()
    {
        return $this->hasMany(ConfigurationIA::class)
            ->whereNotNull('config_par_surface');
    }

    // Relations calculées
    public function joueursSpecialistes()
    {
        return $this->hasMany(StatistiqueJoueur::class)
            ->where('ratio_victoires', '>', 0.75)
            ->where('nombre_matchs_echantillon', '>=', 20);
    }

    public function matchsRecents($jours = 30)
    {
        return $this->matchs()
            ->where('date_match', '>=', now()->subDays($jours))
            ->whereNotNull('gagnant_id');
    }

    // ===================================================================
    // ACCESSORS
    // ===================================================================

    public function getCaracteristiquesDominantesAttribute()
    {
        $caracteristiques = [
            'vitesse' => $this->vitesse_numerique,
            'rebond' => $this->coefficient_rebond,
            'friction' => $this->coefficient_friction,
            'regularite' => $this->regularite_rebond,
        ];

        arsort($caracteristiques);

        return array_keys(array_slice($caracteristiques, 0, 2, true));
    }

    public function getStyleJeuFavoriseAttribute()
    {
        $styles = [
            'serveurs' => $this->avantage_serveurs,
            'baseliners' => $this->avantage_baseliners,
            'attaquants' => $this->avantage_attaquants,
            'contres' => $this->avantage_contres,
        ];

        $styleDominant = array_search(max($styles), $styles);

        return [
            'style_principal' => $styleDominant,
            'score' => max($styles),
            'styles_favorises' => array_filter($styles, fn ($score) => $score >= 70),
        ];
    }

    public function getNiveauDifficulteAdaptationAttribute()
    {
        $facteurs = [
            $this->stress_adaptation,
            $this->variation_tactique_needed,
            100 - $this->regularite_rebond, // Moins régulier = plus difficile
            $this->sensibilite_conditions_moyenne,
        ];

        $moyenne = array_sum($facteurs) / count($facteurs);

        if ($moyenne >= 80) {
            return 'tres_difficile';
        }
        if ($moyenne >= 60) {
            return 'difficile';
        }
        if ($moyenne >= 40) {
            return 'modere';
        }
        if ($moyenne >= 20) {
            return 'facile';
        }

        return 'tres_facile';
    }

    public function getImpactMeteorologiqueAttribute()
    {
        return [
            'sensibilite_globale' => ($this->sensibilite_chaleur + $this->sensibilite_froid +
                    $this->sensibilite_humidite + $this->sensibilite_vent) / 4,
            'condition_optimale' => [
                'temperature' => $this->temperature_ideale,
                'humidite' => $this->humidite_ideale,
                'vent' => 'faible',
            ],
            'conditions_problematiques' => $this->getConditionsProblematiques(),
        ];
    }

    public function getProfilJoueurIdealAttribute()
    {
        $profil = [];

        // Style de jeu optimal
        $styleOptimal = $this->style_jeu_favorise['style_principal'];
        $profil['style_jeu'] = $styleOptimal;

        // Caractéristiques physiques
        if ($this->impact_fatigue_generale > 70) {
            $profil['endurance_requise'] = 'excellente';
        }

        if ($this->avantage_vitesse > 75) {
            $profil['vitesse_deplacement'] = 'cruciale';
        }

        if ($this->avantage_puissance > 75) {
            $profil['puissance'] = 'importante';
        }

        // Technique requise
        if ($this->effet_lift_influence > 80) {
            $profil['maitrise_lift'] = 'essentielle';
        }

        if ($this->jeu_filet_facilite > 70) {
            $profil['jeu_filet'] = 'avantageux';
        }

        // Mental
        if ($this->stress_adaptation > 60) {
            $profil['adaptation_mentale'] = 'cruciale';
        }

        return $profil;
    }

    public function getFacteursClesPredictionAttribute()
    {
        $facteurs = [
            'classement' => $this->importance_classement,
            'forme_recente' => $this->importance_forme,
            'h2h' => $this->importance_h2h,
            'experience_surface' => $this->importance_experience,
        ];

        arsort($facteurs);

        return [
            'facteur_principal' => array_key_first($facteurs),
            'poids_factors' => $facteurs,
            'predictibilite' => $this->predictibilite_resultats,
            'potentiel_surprise' => $this->facteur_surprise,
        ];
    }

    public function getIndiceSpectacleAttribute()
    {
        $score = 0;

        // Rallyes longs = plus spectaculaire
        $score += $this->facteur_rallyes_longs * 0.3;

        // Breaks fréquents = plus d'émotions
        $score += $this->breaks_frequence * 0.2;

        // Jeu varié = plus intéressant
        $score += $this->variation_tactique_needed * 0.2;

        // Facilité jeu d'attaque = plus spectaculaire
        $score += $this->avantage_attaquants * 0.15;

        // Efficacité amortis et passing = plus de show
        $score += ($this->effet_amorti_efficacite + $this->effet_passing_facilite) / 2 * 0.15;

        return round($score, 1);
    }

    private function getSensibiliteConditionsMoyenneAttribute()
    {
        return ($this->sensibilite_chaleur + $this->sensibilite_froid +
                $this->sensibilite_humidite + $this->sensibilite_vent) / 4;
    }

    // ===================================================================
    // SCOPES
    // ===================================================================

    public function scopeRapides($query, $seuilVitesse = 70)
    {
        return $query->where('vitesse_numerique', '>=', $seuilVitesse);
    }

    public function scopeLentes($query, $seuilVitesse = 40)
    {
        return $query->where('vitesse_numerique', '<=', $seuilVitesse);
    }

    public function scopeIndoor($query)
    {
        return $query->where('conditions_indoor_outdoor', 'indoor')
            ->orWhere('conditions_indoor_outdoor', 'both');
    }

    public function scopeOutdoor($query)
    {
        return $query->where('conditions_indoor_outdoor', 'outdoor')
            ->orWhere('conditions_indoor_outdoor', 'both');
    }

    public function scopeFavoriseStyle($query, $style, $seuilAvantage = 70)
    {
        $colonneAvantage = "avantage_{$style}";

        return $query->where($colonneAvantage, '>=', $seuilAvantage);
    }

    public function scopeSensiblesMeteo($query, $seuilSensibilite = 60)
    {
        return $query->where(function ($q) use ($seuilSensibilite) {
            $q->where('sensibilite_chaleur', '>=', $seuilSensibilite)
                ->orWhere('sensibilite_humidite', '>=', $seuilSensibilite)
                ->orWhere('sensibilite_vent', '>=', $seuilSensibilite);
        });
    }

    public function scopeMaintenanceRequise($query)
    {
        return $query->whereRaw('DATEDIFF(NOW(), derniere_maj_caracteristiques) >= frequence_maintenance');
    }

    public function scopeValidees($query)
    {
        return $query->where('validee_par_experts', true)
            ->where('niveau_certitude_donnees', '>=', 80);
    }

    // ===================================================================
    // METHODS TENNIS AI SURFACE
    // ===================================================================

    /**
     * Calculer l'impact de la surface sur un match spécifique
     */
    public function calculerImpactMatch(MatchTennis $match)
    {
        $impact = [
            'facteurs_environnement' => $this->analyserFacteursEnvironnement($match),
            'avantages_joueurs' => $this->analyserAvantagesJoueurs($match),
            'predictions_ajustements' => $this->calculerAjustementsPredictions($match),
            'duree_estimee' => $this->estimerDureeMatch($match),
            'style_jeu_optimal' => $this->determinerStyleOptimal($match),
        ];

        return $impact;
    }

    /**
     * Analyser la compatibilité d'un joueur avec la surface
     */
    public function analyserCompatibiliteJoueur(Joueur $joueur)
    {
        // Récupérer statistiques du joueur sur cette surface
        $stats = $joueur->statistiques()
            ->where('surface_id', $this->id)
            ->latest()
            ->first();

        if (! $stats) {
            return $this->estimerCompatibiliteSansStats($joueur);
        }

        $compatibilite = [
            'score_global' => $this->calculerScoreCompatibilite($joueur, $stats),
            'points_forts' => $this->identifierPointsFortsSurface($joueur, $stats),
            'points_faibles' => $this->identifierPointsFaiblesSurface($joueur, $stats),
            'recommandations' => $this->genererRecommandations($joueur, $stats),
            'potentiel_progression' => $this->evaluerPotentielProgression($joueur, $stats),
        ];

        return $compatibilite;
    }

    /**
     * Adapter les prédictions selon la surface
     */
    public function adapterPredictions(array $predictionBase, MatchTennis $match)
    {
        $adaptations = [];

        // Adaptation selon spécialisation des joueurs
        $joueur1Specialiste = $this->estSpecialiste($match->joueur1);
        $joueur2Specialiste = $this->estSpecialiste($match->joueur2);

        if ($joueur1Specialiste && ! $joueur2Specialiste) {
            $adaptations['bonus_joueur1'] = 15; // +15% chances
        } elseif ($joueur2Specialiste && ! $joueur1Specialiste) {
            $adaptations['bonus_joueur2'] = 15;
        }

        // Adaptation selon conditions météo
        $conditionsMeteo = $match->condition_meteo;
        if ($conditionsMeteo) {
            $impactMeteo = $this->calculerImpactMeteo($conditionsMeteo);
            $adaptations['facteur_meteo'] = $impactMeteo;
        }

        // Adaptation selon importance tournoi
        $importanceTournoi = $match->tournoi?->importance_match ?? 1;
        if ($this->stress_adaptation > 70 && $importanceTournoi >= 3) {
            $adaptations['facteur_stress'] = 10; // Stress supplémentaire
        }

        // Adaptation selon durée estimée
        $dureeEstimee = $this->estimerDureeMatch($match);
        if ($dureeEstimee > 180) { // Plus de 3h
            $adaptations['facteur_endurance'] = 20; // Endurance cruciale
        }

        return $this->appliquerAdaptations($predictionBase, $adaptations);
    }

    /**
     * Optimiser la configuration IA pour cette surface
     */
    public function optimiserConfigurationIA(ConfigurationIA $config)
    {
        $optimisations = [];

        // Ajuster l'importance des features selon la surface
        $featuresImportance = $config->features_importance ?? [];

        // Si surface lente, augmenter importance endurance
        if ($this->vitesse_numerique < 50) {
            $featuresImportance['endurance'] = ($featuresImportance['endurance'] ?? 0.1) * 1.3;
            $featuresImportance['rallyes_longs'] = ($featuresImportance['rallyes_longs'] ?? 0.1) * 1.4;
        }

        // Si surface rapide, augmenter importance service
        if ($this->vitesse_numerique > 70) {
            $featuresImportance['service_power'] = ($featuresImportance['service_power'] ?? 0.1) * 1.4;
            $featuresImportance['aces'] = ($featuresImportance['aces'] ?? 0.1) * 1.3;
        }

        // Ajuster selon prédictibilité
        if ($this->predictibilite_resultats > 80) {
            $featuresImportance['classement'] = ($featuresImportance['classement'] ?? 0.1) * 1.2;
        } else {
            $featuresImportance['forme_recente'] = ($featuresImportance['forme_recente'] ?? 0.1) * 1.3;
        }

        $optimisations['features_importance'] = $featuresImportance;

        // Ajuster hyperparamètres
        $hyperparams = $config->hyperparametres ?? [];

        // Surface irrégulière = modèle plus flexible
        if ($this->regularite_rebond < 60) {
            $hyperparams['max_depth'] = min(($hyperparams['max_depth'] ?? 6) + 2, 15);
        }

        $optimisations['hyperparametres'] = $hyperparams;

        return $optimisations;
    }

    /**
     * Générer profil détaillé de la surface
     */
    public function genererProfilDetaille()
    {
        return [
            'identite' => [
                'nom' => $this->nom,
                'code' => $this->code,
                'vitesse_categorie' => $this->getCategorieVitesse(),
                'couleur' => $this->couleur_principale,
            ],
            'caracteristiques_physiques' => [
                'vitesse' => $this->vitesse_numerique,
                'rebond' => $this->coefficient_rebond,
                'friction' => $this->coefficient_friction,
                'regularite' => $this->regularite_rebond,
            ],
            'impact_jeu' => [
                'style_favorise' => $this->style_jeu_favorise,
                'duree_moyenne_match' => $this->duree_moyenne_matchs,
                'longueur_rallyes' => $this->longueur_rallyes_moyenne,
                'frequence_breaks' => $this->breaks_frequence,
            ],
            'adaptation_requise' => [
                'difficulte' => $this->niveau_difficulte_adaptation,
                'temps_requis' => $this->adaptation_temps_requis,
                'stress_associe' => $this->stress_adaptation,
            ],
            'conditions_optimales' => [
                'temperature' => $this->temperature_ideale,
                'humidite' => $this->humidite_ideale,
                'conditions' => $this->conditions_indoor_outdoor,
            ],
            'specialistes_celebres' => $this->specialistes_celebres,
            'facteurs_prediction' => $this->facteurs_cles_prediction,
            'indice_spectacle' => $this->indice_spectacle,
        ];
    }

    /**
     * Analyser les tendances de performance sur cette surface
     */
    public function analyserTendancesPerformance($periode = 365)
    {
        $matchs = $this->matchsRecents($periode);

        $analyses = [
            'upsets_frequence' => $this->calculerFrequenceUpsets($matchs),
            'correlation_classement' => $this->calculerCorrelationClassement($matchs),
            'impact_forme_recente' => $this->calculerImpactFormeRecente($matchs),
            'avantage_experience' => $this->calculerAvantageExperience($matchs),
            'evolution_jeu' => $this->analyserEvolutionJeu($matchs),
        ];

        return $analyses;
    }

    // ===================================================================
    // HELPER METHODS
    // ===================================================================

    private function analyserFacteursEnvironnement(MatchTennis $match)
    {
        $facteurs = [];

        // Impact température
        if ($match->temperature) {
            $diffTemp = abs($match->temperature - $this->temperature_ideale);
            $facteurs['temperature_impact'] = min(100, $diffTemp * $this->sensibilite_chaleur / 10);
        }

        // Impact humidité
        if ($match->humidite) {
            $diffHumidite = abs($match->humidite - $this->humidite_ideale);
            $facteurs['humidite_impact'] = min(100, $diffHumidite * $this->sensibilite_humidite / 50);
        }

        // Impact vent
        if ($match->vitesse_vent) {
            $facteurs['vent_impact'] = $match->vitesse_vent * $this->sensibilite_vent / 10;
        }

        return $facteurs;
    }

    private function analyserAvantagesJoueurs(MatchTennis $match)
    {
        $avantages = [];

        // Analyser style de jeu joueur 1
        $styleJ1 = $this->determinerStyleJoueur($match->joueur1);
        $avantages['joueur1'] = $this->calculerAvantageStyle($styleJ1);

        // Analyser style de jeu joueur 2
        $styleJ2 = $this->determinerStyleJoueur($match->joueur2);
        $avantages['joueur2'] = $this->calculerAvantageStyle($styleJ2);

        return $avantages;
    }

    private function calculerAjustementsPredictions(MatchTennis $match)
    {
        $ajustements = [];

        // Ajustement selon spécialisation
        $expJ1 = $this->getExperienceSurface($match->joueur1);
        $expJ2 = $this->getExperienceSurface($match->joueur2);

        if ($expJ1 > $expJ2 + 10) { // 10+ matchs d'écart
            $ajustements['bonus_experience_j1'] = 8;
        } elseif ($expJ2 > $expJ1 + 10) {
            $ajustements['bonus_experience_j2'] = 8;
        }

        return $ajustements;
    }

    private function estimerDureeMatch(MatchTennis $match)
    {
        $dureeBase = $this->duree_moyenne_matchs;

        // Ajustements selon contexte
        $multiplicateur = 1;

        // Importance tournoi
        if ($match->tournoi?->importance_match >= 3) {
            $multiplicateur *= 1.1;
        }

        // Équilibre des joueurs
        $diffClassement = abs(($match->joueur1->classement_atp_wta ?? 100) -
            ($match->joueur2->classement_atp_wta ?? 100));
        if ($diffClassement < 10) {
            $multiplicateur *= 1.15; // Match équilibré = plus long
        }

        return (int) ($dureeBase * $multiplicateur);
    }

    private function estSpecialiste(Joueur $joueur)
    {
        $stats = $joueur->statistiques()
            ->where('surface_id', $this->id)
            ->latest()
            ->first();

        if (! $stats) {
            return false;
        }

        return $stats->ratio_victoires > 0.75 &&
            $stats->nombre_matchs_echantillon >= 15;
    }

    private function calculerScoreCompatibilite(Joueur $joueur, StatistiqueJoueur $stats)
    {
        $score = $stats->ratio_victoires * 100;

        // Bonus si spécialiste reconnu
        if ($this->specialistes_celebres &&
            in_array($joueur->nom_complet, $this->specialistes_celebres)) {
            $score += 10;
        }

        return min(100, $score);
    }

    private function getCategorieVitesse()
    {
        $vitesse = $this->vitesse_numerique;

        if ($vitesse >= 80) {
            return 'tres_rapide';
        }
        if ($vitesse >= 65) {
            return 'rapide';
        }
        if ($vitesse >= 45) {
            return 'moyenne';
        }
        if ($vitesse >= 30) {
            return 'lente';
        }

        return 'tres_lente';
    }

    private function getConditionsProblematiques()
    {
        $problematiques = [];

        if ($this->sensibilite_chaleur > 70) {
            $problematiques[] = 'chaleur_excessive';
        }

        if ($this->sensibilite_humidite > 70) {
            $problematiques[] = 'humidite_elevee';
        }

        if ($this->sensibilite_vent > 60) {
            $problematiques[] = 'vent_fort';
        }

        return $problematiques;
    }

    private function calculerAvantageStyle($style)
    {
        $proprieteAvantage = "avantage_{$style}";

        return $this->$proprieteAvantage ?? 50;
    }

    private function determinerStyleJoueur(Joueur $joueur)
    {
        // Logique simplifiée - en réalité analyserait stats détaillées
        $stats = $joueur->statistiques()->latest()->first();

        if (! $stats) {
            return 'baseliners';
        } // Par défaut

        if ($stats->aces_par_match > 8) {
            return 'serveurs';
        }
        if ($stats->force_service > 80) {
            return 'serveurs';
        }
        if ($stats->longueur_rallyes_moyenne > 6) {
            return 'baseliners';
        }

        return 'baseliners';
    }

    private function appliquerAdaptations($predictionBase, $adaptations)
    {
        $predictionAdaptee = $predictionBase;

        foreach ($adaptations as $type => $valeur) {
            switch ($type) {
                case 'bonus_joueur1':
                    $predictionAdaptee['probabilite_joueur1'] += $valeur;
                    $predictionAdaptee['probabilite_joueur2'] -= $valeur;
                    break;
                case 'bonus_joueur2':
                    $predictionAdaptee['probabilite_joueur2'] += $valeur;
                    $predictionAdaptee['probabilite_joueur1'] -= $valeur;
                    break;
            }
        }

        // Normaliser les probabilités
        $total = $predictionAdaptee['probabilite_joueur1'] + $predictionAdaptee['probabilite_joueur2'];
        if ($total !== 100) {
            $predictionAdaptee['probabilite_joueur1'] = ($predictionAdaptee['probabilite_joueur1'] / $total) * 100;
            $predictionAdaptee['probabilite_joueur2'] = ($predictionAdaptee['probabilite_joueur2'] / $total) * 100;
        }

        return $predictionAdaptee;
    }

    private function getExperienceSurface(Joueur $joueur)
    {
        return $joueur->statistiques()
            ->where('surface_id', $this->id)
            ->sum('nombre_matchs_echantillon') ?? 0;
    }

    // ===================================================================
    // STATIC METHODS
    // ===================================================================

    /**
     * Obtenir les surfaces par caractéristique dominante
     */
    public static function getParCaracteristique($caracteristique, $seuilMin = 70)
    {
        $colonne = "avantage_{$caracteristique}";

        return self::where($colonne, '>=', $seuilMin)
            ->orderBy($colonne, 'desc')
            ->get();
    }

    /**
     * Analyser les différences entre surfaces
     */
    public static function analyserDifferences()
    {
        $surfaces = self::all();

        $analyses = [];

        foreach ($surfaces as $surface1) {
            foreach ($surfaces as $surface2) {
                if ($surface1->id >= $surface2->id) {
                    continue;
                }

                $differences = [
                    'vitesse' => abs($surface1->vitesse_numerique - $surface2->vitesse_numerique),
                    'rebond' => abs($surface1->coefficient_rebond - $surface2->coefficient_rebond),
                    'friction' => abs($surface1->coefficient_friction - $surface2->coefficient_friction),
                ];

                $analyses["{$surface1->code}_vs_{$surface2->code}"] = $differences;
            }
        }

        return $analyses;
    }

    /**
     * Recommander surface pour un joueur
     */
    public static function recommanderPourJoueur(Joueur $joueur)
    {
        $surfaces = self::all();
        $recommandations = [];

        foreach ($surfaces as $surface) {
            $compatibilite = $surface->analyserCompatibiliteJoueur($joueur);
            $recommandations[$surface->code] = $compatibilite['score_global'];
        }

        arsort($recommandations);

        return $recommandations;
    }

    // ===================================================================
    // VALIDATION RULES
    // ===================================================================

    public static function validationRules()
    {
        return [
            'nom' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:surfaces,code',
            'vitesse_numerique' => 'required|numeric|between:0,100',
            'coefficient_rebond' => 'required|numeric|between:0,100',
            'coefficient_friction' => 'required|numeric|between:0,100',
            'temperature_ideale' => 'required|integer|between:-10,50',
            'niveau_certitude_donnees' => 'required|numeric|between:0,100',
        ];
    }
}
