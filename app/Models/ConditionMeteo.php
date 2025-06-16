<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConditionMeteo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'condition_meteos';

    protected $fillable = [
        // Identifiants
        'nom',
        'code',                     // 'sunny', 'windy', 'hot', 'humid', etc.
        'description',
        'nom_anglais',

        // Mesures principales
        'temperature',              // °C
        'temperature_ressentie',    // °C avec facteur vent/humidité
        'humidite',                // % humidité relative
        'vitesse_vent',            // km/h
        'direction_vent',          // degrés (0-360)
        'pression_atmospherique',  // hPa
        'point_rosee',             // °C

        // Conditions visuelles/atmosphériques
        'couverture_nuageuse',     // % de couverture
        'visibilite',              // km
        'indice_uv',               // 0-11+
        'precipitation',           // mm/h
        'type_precipitation',      // 'rain', 'drizzle', 'storm', null

        // Classification météo
        'categorie',               // 'ideale', 'correcte', 'difficile', 'extreme'
        'severite',                // 1-10 (10 = très sévère)
        'est_extreme',             // Conditions extrêmes
        'est_jouable',             // Conditions permettant le jeu
        'necessite_interruption',  // Nécessite arrêt du jeu

        // Impact sur le tennis
        'impact_vitesse_balle',    // -10 à +10 (ralentit/accélère)
        'impact_rebond',           // -10 à +10 (bas/haut)
        'impact_spin',             // -10 à +10 (moins/plus d'effet)
        'impact_service',          // -10 à +10 (défavorise/favorise)
        'impact_endurance',        // -10 à +10 (fatigue/énergise)
        'impact_concentration',    // -10 à +10 (distrait/favorise)

        // Surfaces affectées
        'impact_dur',              // Impact sur hard court (-10 à +10)
        'impact_terre',            // Impact sur terre battue
        'impact_gazon',            // Impact sur gazon
        'impact_indoor',           // Impact en intérieur

        // Facteurs de jeu
        'favorise_grands',         // Favorise les grands joueurs
        'favorise_petits',         // Favorise les petits joueurs
        'favorise_puissance',      // Favorise le jeu de puissance
        'favorise_precision',      // Favorise la précision
        'favorise_patience',       // Favorise le jeu patient
        'favorise_agressivite',    // Favorise l'agressivité

        // Risques et sécurité
        'risque_blessure',         // 1-10 risque de blessure
        'risque_deshydratation',   // 1-10 risque déshydratation
        'risque_glissade',         // 1-10 risque de glissade
        'necessite_protection',    // Protection solaire/autre nécessaire

        // Timing et durée
        'heure_mesure',            // Heure de la mesure
        'duree_prevue',            // Durée prévue des conditions (heures)
        'tendance',                // 'amelioration', 'degradation', 'stable'
        'fiabilite_prevision',     // % de fiabilité de la prévision

        // Métadonnées
        'source_donnees',          // Source météo
        'station_mesure',          // Station météorologique
        'coordonnees_gps',         // JSON lat/lng
        'altitude_station',        // Altitude en mètres
        'derniere_maj',            // Dernière mise à jour
        'est_historique',          // Donnée historique vs temps réel
        'actif',
    ];

    protected $casts = [
        'temperature' => 'decimal:1',
        'temperature_ressentie' => 'decimal:1',
        'humidite' => 'decimal:1',
        'vitesse_vent' => 'decimal:1',
        'direction_vent' => 'integer',
        'pression_atmospherique' => 'decimal:1',
        'point_rosee' => 'decimal:1',
        'couverture_nuageuse' => 'integer',
        'visibilite' => 'decimal:1',
        'indice_uv' => 'integer',
        'precipitation' => 'decimal:2',
        'severite' => 'integer',
        'impact_vitesse_balle' => 'integer',
        'impact_rebond' => 'integer',
        'impact_spin' => 'integer',
        'impact_service' => 'integer',
        'impact_endurance' => 'integer',
        'impact_concentration' => 'integer',
        'impact_dur' => 'integer',
        'impact_terre' => 'integer',
        'impact_gazon' => 'integer',
        'impact_indoor' => 'integer',
        'risque_blessure' => 'integer',
        'risque_deshydratation' => 'integer',
        'risque_glissade' => 'integer',
        'duree_prevue' => 'decimal:1',
        'fiabilite_prevision' => 'integer',
        'altitude_station' => 'integer',
        'coordonnees_gps' => 'json',
        'heure_mesure' => 'datetime',
        'derniere_maj' => 'datetime',
        'est_extreme' => 'boolean',
        'est_jouable' => 'boolean',
        'necessite_interruption' => 'boolean',
        'favorise_grands' => 'boolean',
        'favorise_petits' => 'boolean',
        'favorise_puissance' => 'boolean',
        'favorise_precision' => 'boolean',
        'favorise_patience' => 'boolean',
        'favorise_agressivite' => 'boolean',
        'necessite_protection' => 'boolean',
        'est_historique' => 'boolean',
        'actif' => 'boolean',
    ];

    protected $appends = [
        'resume_conditions',
        'niveau_difficulte',
        'impact_global_jeu',
        'recommendations_joueurs',
        'surface_optimale',
        'facteur_ajustement_ia',
        'indice_confort',
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function matchs()
    {
        return $this->hasMany(MatchTennis::class, 'condition_meteo_id');
    }

    public function tournois()
    {
        return $this->hasManyThrough(Tournoi::class, MatchTennis::class,
            'condition_meteo_id', 'id', 'id', 'tournoi_id');
    }

    public function predictions()
    {
        return $this->hasMany(Prediction::class, 'condition_meteo_id');
    }

    // ===================================================================
    // ACCESSORS
    // ===================================================================

    public function getResumeConditionsAttribute()
    {
        $elements = [];

        $elements[] = "{$this->temperature}°C";

        if ($this->humidite) {
            $elements[] = "{$this->humidite}% humid.";
        }
        if ($this->vitesse_vent > 10) {
            $elements[] = "Vent {$this->vitesse_vent}km/h";
        }
        if ($this->precipitation > 0) {
            $elements[] = "Pluie {$this->precipitation}mm/h";
        }
        if ($this->indice_uv >= 8) {
            $elements[] = "UV élevé ({$this->indice_uv})";
        }

        return implode(', ', $elements);
    }

    public function getNiveauDifficulteAttribute()
    {
        $score = 0;

        // Température extrême
        if ($this->temperature < 5 || $this->temperature > 35) {
            $score += 3;
        } elseif ($this->temperature < 10 || $this->temperature > 30) {
            $score += 2;
        }

        // Vent fort
        if ($this->vitesse_vent > 30) {
            $score += 4;
        } elseif ($this->vitesse_vent > 20) {
            $score += 2;
        } elseif ($this->vitesse_vent > 15) {
            $score += 1;
        }

        // Humidité extrême
        if ($this->humidite > 90 || $this->humidite < 20) {
            $score += 2;
        } elseif ($this->humidite > 80 || $this->humidite < 30) {
            $score += 1;
        }

        // Précipitations
        if ($this->precipitation > 0) {
            $score += 5;
        }

        // UV très élevé
        if ($this->indice_uv >= 10) {
            $score += 2;
        } elseif ($this->indice_uv >= 8) {
            $score += 1;
        }

        $niveaux = [
            0 - 1 => 'Idéales',
            2 - 3 => 'Bonnes',
            4 - 6 => 'Correctes',
            7 - 9 => 'Difficiles',
            10 - 15 => 'Très difficiles',
            16 - 20 => 'Extrêmes',
        ];

        foreach ($niveaux as $range => $niveau) {
            if (is_string($range)) {
                [$min, $max] = explode('-', $range);
                if ($score >= $min && $score <= $max) {
                    return $niveau;
                }
            }
        }

        return 'Extrêmes';
    }

    public function getImpactGlobalJeuAttribute()
    {
        $impacts = [
            $this->impact_vitesse_balle ?? 0,
            $this->impact_rebond ?? 0,
            $this->impact_service ?? 0,
            $this->impact_endurance ?? 0,
        ];

        $moyenne = array_sum($impacts) / count($impacts);

        if ($moyenne >= 3) {
            return 'Très favorable';
        }
        if ($moyenne >= 1) {
            return 'Favorable';
        }
        if ($moyenne >= -1) {
            return 'Neutre';
        }
        if ($moyenne >= -3) {
            return 'Défavorable';
        }

        return 'Très défavorable';
    }

    public function getRecommendationsJoueursAttribute()
    {
        $recommendations = [];

        // Conditions chaudes
        if ($this->temperature > 30) {
            $recommendations[] = 'Hydratation renforcée';
            $recommendations[] = 'Pauses fréquentes';
            $recommendations[] = 'Protection solaire';
        }

        // Vent fort
        if ($this->vitesse_vent > 20) {
            $recommendations[] = 'Ajuster la trajectoire des balles';
            $recommendations[] = 'Renforcer la concentration';
            $recommendations[] = 'Adapter le service';
        }

        // Humidité élevée
        if ($this->humidite > 80) {
            $recommendations[] = 'Prévoir serviettes supplémentaires';
            $recommendations[] = 'Changer de grip plus souvent';
        }

        // Froid
        if ($this->temperature < 15) {
            $recommendations[] = 'Échauffement prolongé';
            $recommendations[] = 'Vêtements adaptés';
        }

        return empty($recommendations) ? ['Conditions standards'] : $recommendations;
    }

    public function getSurfaceOptimaleAttribute()
    {
        $impacts = [
            'dur' => $this->impact_dur ?? 0,
            'terre' => $this->impact_terre ?? 0,
            'gazon' => $this->impact_gazon ?? 0,
            'indoor' => $this->impact_indoor ?? 0,
        ];

        $surfaceOptimale = array_keys($impacts, max($impacts))[0];

        $surfaces = [
            'dur' => 'Hard court',
            'terre' => 'Terre battue',
            'gazon' => 'Gazon',
            'indoor' => 'Indoor',
        ];

        return $surfaces[$surfaceOptimale] ?? 'Toutes surfaces';
    }

    public function getFacteurAjustementIaAttribute()
    {
        // Facteur d'ajustement pour les algorithmes IA (-1 à +1)
        $facteurs = [];

        // Impact température
        if ($this->temperature > 35) {
            $facteurs[] = -0.3;
        } elseif ($this->temperature > 30) {
            $facteurs[] = -0.1;
        } elseif ($this->temperature < 5) {
            $facteurs[] = -0.4;
        } elseif ($this->temperature < 10) {
            $facteurs[] = -0.2;
        }

        // Impact vent
        if ($this->vitesse_vent > 30) {
            $facteurs[] = -0.4;
        } elseif ($this->vitesse_vent > 20) {
            $facteurs[] = -0.2;
        } elseif ($this->vitesse_vent > 15) {
            $facteurs[] = -0.1;
        }

        // Impact pluie
        if ($this->precipitation > 5) {
            $facteurs[] = -0.8;
        } elseif ($this->precipitation > 0) {
            $facteurs[] = -0.3;
        }

        // Impact humidité
        if ($this->humidite > 90) {
            $facteurs[] = -0.2;
        } elseif ($this->humidite < 20) {
            $facteurs[] = -0.1;
        }

        return empty($facteurs) ? 0 : max(-1, min(1, array_sum($facteurs)));
    }

    public function getIndiceConfortAttribute()
    {
        $score = 100; // Score parfait de base

        // Pénalités température
        if ($this->temperature > 35 || $this->temperature < 5) {
            $score -= 40;
        } elseif ($this->temperature > 30 || $this->temperature < 10) {
            $score -= 20;
        } elseif ($this->temperature > 28 || $this->temperature < 12) {
            $score -= 10;
        }

        // Pénalités vent
        if ($this->vitesse_vent > 25) {
            $score -= 30;
        } elseif ($this->vitesse_vent > 15) {
            $score -= 15;
        } elseif ($this->vitesse_vent > 10) {
            $score -= 5;
        }

        // Pénalités humidité
        if ($this->humidite > 85) {
            $score -= 20;
        } elseif ($this->humidite > 75) {
            $score -= 10;
        } elseif ($this->humidite < 25) {
            $score -= 10;
        }

        // Pénalités précipitations
        if ($this->precipitation > 0) {
            $score -= 50;
        }

        // Bonus/pénalités UV
        if ($this->indice_uv > 9) {
            $score -= 15;
        } elseif ($this->indice_uv > 7) {
            $score -= 5;
        }

        return max(0, min(100, $score));
    }

    // ===================================================================
    // SCOPES
    // ===================================================================

    public function scopeJouables($query)
    {
        return $query->where('est_jouable', true);
    }

    public function scopeExtremes($query)
    {
        return $query->where('est_extreme', true);
    }

    public function scopeParCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    public function scopeTemperatureEntre($query, $min, $max)
    {
        return $query->whereBetween('temperature', [$min, $max]);
    }

    public function scopeVentFort($query, $seuilKmh = 20)
    {
        return $query->where('vitesse_vent', '>', $seuilKmh);
    }

    public function scopeHumiditeElevee($query, $seuilPourcent = 80)
    {
        return $query->where('humidite', '>', $seuilPourcent);
    }

    public function scopeAvecPrecipitations($query)
    {
        return $query->where('precipitation', '>', 0);
    }

    public function scopeUvEleve($query, $seuilIndice = 8)
    {
        return $query->where('indice_uv', '>=', $seuilIndice);
    }

    public function scopeTempsReel($query)
    {
        return $query->where('est_historique', false);
    }

    public function scopeHistoriques($query)
    {
        return $query->where('est_historique', true);
    }

    public function scopeRecentes($query, $heures = 24)
    {
        return $query->where('heure_mesure', '>=', now()->subHours($heures));
    }

    public function scopeFavorablePourSurface($query, $surface)
    {
        $champ = "impact_{$surface}";

        return $query->where($champ, '>=', 3);
    }

    public function scopeRecherche($query, $terme)
    {
        return $query->where(function ($q) use ($terme) {
            $q->where('nom', 'LIKE', "%{$terme}%")
                ->orWhere('description', 'LIKE', "%{$terme}%")
                ->orWhere('categorie', 'LIKE', "%{$terme}%");
        });
    }

    // ===================================================================
    // STATIC METHODS
    // ===================================================================

    /**
     * Créer une condition météo à partir de données brutes
     */
    public static function creerDepuisDonneesBrutes(array $donnees)
    {
        $condition = new self;

        // Mapping des données
        $condition->temperature = $donnees['temperature'] ?? null;
        $condition->humidite = $donnees['humidity'] ?? null;
        $condition->vitesse_vent = $donnees['wind_speed'] ?? null;
        $condition->direction_vent = $donnees['wind_direction'] ?? null;
        $condition->precipitation = $donnees['precipitation'] ?? 0;

        // Auto-calculs
        $condition->calculerImpacts();
        $condition->determinerCategorie();
        $condition->evaluerJouabilite();

        return $condition;
    }

    /**
     * Obtenir les conditions idéales pour le tennis
     */
    public static function getConditionsIdeales()
    {
        return [
            'temperature' => [18, 25],      // 18-25°C
            'humidite' => [40, 60],         // 40-60%
            'vitesse_vent' => [0, 10],      // 0-10 km/h
            'precipitation' => 0,            // Pas de pluie
            'indice_uv' => [3, 6],          // UV modéré
            'couverture_nuageuse' => [20, 50], // Partiellement nuageux
        ];
    }

    // ===================================================================
    // METHODS
    // ===================================================================

    /**
     * Calculer automatiquement les impacts sur le jeu
     */
    public function calculerImpacts()
    {
        // Impact température
        if ($this->temperature > 35) {
            $this->impact_endurance = -8;
            $this->impact_concentration = -5;
        } elseif ($this->temperature > 30) {
            $this->impact_endurance = -4;
            $this->impact_concentration = -2;
        } elseif ($this->temperature < 5) {
            $this->impact_vitesse_balle = -6;
            $this->impact_rebond = -4;
        }

        // Impact vent
        if ($this->vitesse_vent > 25) {
            $this->impact_service = -6;
            $this->impact_precision = -8;
        } elseif ($this->vitesse_vent > 15) {
            $this->impact_service = -3;
            $this->impact_precision = -4;
        }

        // Impact humidité
        if ($this->humidite > 85) {
            $this->impact_endurance = -3;
            $this->impact_concentration = -2;
        }

        // Impact surface selon conditions
        $this->calculerImpactsSurfaces();
    }

    /**
     * Calculer l'impact sur les différentes surfaces
     */
    private function calculerImpactsSurfaces()
    {
        // Terre battue et humidité
        if ($this->humidite > 70) {
            $this->impact_terre = -3; // Plus lourde
        } elseif ($this->humidite < 40) {
            $this->impact_terre = 2; // Plus rapide
        }

        // Gazon et pluie
        if ($this->precipitation > 0) {
            $this->impact_gazon = -8; // Très glissant
        }

        // Hard court et température
        if ($this->temperature > 35) {
            $this->impact_dur = -2; // Surface chaude
        }

        // Indoor protégé des éléments
        $this->impact_indoor = min(5, 10 - abs($this->facteur_ajustement_ia * 10));
    }

    /**
     * Déterminer automatiquement la catégorie
     */
    public function determinerCategorie()
    {
        $difficulte = $this->niveau_difficulte;

        $categories = [
            'Idéales' => 'ideale',
            'Bonnes' => 'correcte',
            'Correctes' => 'correcte',
            'Difficiles' => 'difficile',
            'Très difficiles' => 'extreme',
            'Extrêmes' => 'extreme',
        ];

        $this->categorie = $categories[$difficulte] ?? 'correcte';
        $this->est_extreme = in_array($difficulte, ['Très difficiles', 'Extrêmes']);
    }

    /**
     * Évaluer si les conditions permettent le jeu
     */
    public function evaluerJouabilite()
    {
        $this->est_jouable = true;
        $this->necessite_interruption = false;

        // Conditions qui empêchent le jeu
        if ($this->precipitation > 1) {
            $this->est_jouable = false;
            $this->necessite_interruption = true;
        }

        if ($this->vitesse_vent > 50) {
            $this->est_jouable = false;
            $this->necessite_interruption = true;
        }

        if ($this->temperature > 45 || $this->temperature < -5) {
            $this->est_jouable = false;
        }

        // Conditions qui nécessitent une pause
        if ($this->temperature > 40 || $this->indice_uv > 10) {
            $this->necessite_interruption = true;
        }
    }

    /**
     * Calculer l'effet sur la performance d'un joueur
     */
    public function calculerEffetPerformance($styleJoueur, $surface = 'dur')
    {
        $effet = 0;

        // Style de jeu et conditions
        if ($styleJoueur === 'baseline' && $this->vitesse_vent > 20) {
            $effet -= 15; // Plus difficile pour les joueurs de fond
        }

        if ($styleJoueur === 'serve_volley' && $this->vitesse_vent > 15) {
            $effet -= 25; // Très difficile pour serve-volley
        }

        // Surface et conditions
        $impactSurface = $this->{"impact_{$surface}"} ?? 0;
        $effet += $impactSurface * 2;

        // Facteurs généraux
        $effet += $this->impact_endurance ?? 0;
        $effet += $this->impact_concentration ?? 0;

        return max(-50, min(50, $effet)); // Limiter entre -50% et +50%
    }

    /**
     * Générer un rapport météo complet
     */
    public function genererRapport()
    {
        return [
            'resume' => $this->resume_conditions,
            'niveau_difficulte' => $this->niveau_difficulte,
            'indice_confort' => $this->indice_confort,
            'jouabilite' => $this->est_jouable ? 'Jouable' : 'Non jouable',
            'surface_optimale' => $this->surface_optimale,
            'recommendations' => $this->recommendations_joueurs,
            'impacts_jeu' => [
                'vitesse_balle' => $this->impact_vitesse_balle,
                'rebond' => $this->impact_rebond,
                'service' => $this->impact_service,
                'endurance' => $this->impact_endurance,
            ],
            'facteur_ia' => $this->facteur_ajustement_ia,
            'risques' => [
                'blessure' => $this->risque_blessure ?? 0,
                'deshydratation' => $this->risque_deshydratation ?? 0,
                'glissade' => $this->risque_glissade ?? 0,
            ],
        ];
    }

    /**
     * Comparer avec des conditions idéales
     */
    public function comparerAvecIdeales()
    {
        $ideales = self::getConditionsIdeales();
        $ecarts = [];

        foreach ($ideales as $param => $valeurIdeale) {
            if (is_array($valeurIdeale)) {
                $valeurActuelle = $this->$param;
                if ($valeurActuelle < $valeurIdeale[0]) {
                    $ecarts[$param] = "Trop bas ({$valeurActuelle} < {$valeurIdeale[0]})";
                } elseif ($valeurActuelle > $valeurIdeale[1]) {
                    $ecarts[$param] = "Trop élevé ({$valeurActuelle} > {$valeurIdeale[1]})";
                }
            } else {
                if ($valeurIdeale != $this->$param) {
                    $ecarts[$param] = "Différent de l'idéal ({$this->$param} vs {$valeurIdeale})";
                }
            }
        }

        return $ecarts;
    }

    // ===================================================================
    // VALIDATION RULES
    // ===================================================================

    public static function validationRules()
    {
        return [
            'nom' => 'required|string|max:100',
            'temperature' => 'required|numeric|min:-20|max:50',
            'humidite' => 'nullable|numeric|min:0|max:100',
            'vitesse_vent' => 'nullable|numeric|min:0|max:200',
            'precipitation' => 'nullable|numeric|min:0',
            'indice_uv' => 'nullable|integer|min:0|max:15',
            'categorie' => 'required|in:ideale,correcte,difficile,extreme',
        ];
    }

    // ===================================================================
    // BOOT METHODS
    // ===================================================================

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($condition) {
            // Auto-calculs si pas déjà définis
            if (! $condition->nom && $condition->temperature) {
                $condition->genererNomAutomatique();
            }

            // Calculs automatiques
            $condition->calculerImpacts();
            $condition->determinerCategorie();
            $condition->evaluerJouabilite();

            // Valeurs par défaut
            if ($condition->actif === null) {
                $condition->actif = true;
            }
            if (! $condition->heure_mesure) {
                $condition->heure_mesure = now();
            }
        });
    }

    /**
     * Générer un nom automatique basé sur les conditions
     */
    private function genererNomAutomatique()
    {
        $elements = [];

        // Température
        if ($this->temperature > 30) {
            $elements[] = 'Chaud';
        } elseif ($this->temperature < 10) {
            $elements[] = 'Froid';
        } else {
            $elements[] = 'Tempéré';
        }

        // Vent
        if ($this->vitesse_vent > 25) {
            $elements[] = 'Venteux';
        } elseif ($this->vitesse_vent > 15) {
            $elements[] = 'Brise';
        }

        // Humidité
        if ($this->humidite > 80) {
            $elements[] = 'Humide';
        } elseif ($this->humidite < 30) {
            $elements[] = 'Sec';
        }

        // Précipitations
        if ($this->precipitation > 5) {
            $elements[] = 'Pluvieux';
        } elseif ($this->precipitation > 0) {
            $elements[] = 'Bruine';
        }

        $this->nom = implode(' et ', $elements) ?: 'Standard';
    }
}
