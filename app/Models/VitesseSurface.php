<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VitesseSurface extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'vitesse_surfaces';

    protected $fillable = [
        // Informations de base
        'nom',
        'nom_anglais',            // "Slow", "Medium", "Fast"
        'code',                   // 'slow', 'medium', 'fast', 'very_fast'
        'description',

        // Mesures de vitesse
        'valeur',                 // Valeur numérique principale (0-100)
        'court_pace_index',       // CPI officiel ITF (0-100)
        'coefficient_restitution', // Coefficient de restitution balle
        'angle_rebond_moyen',     // Angle de rebond en degrés
        'hauteur_rebond_relative', // Hauteur relative du rebond

        // Classification tennis
        'categorie_itf',          // Classification ITF officielle
        'niveau_vitesse',         // 1=très lent, 5=très rapide
        'est_homologue_itf',      // Homologué par l'ITF
        'norme_reference',        // Norme de référence utilisée

        // Caractéristiques de jeu
        'favorise_puissance',     // Favorise le jeu de puissance
        'favorise_precision',     // Favorise la précision
        'favorise_endurance',     // Favorise l'endurance
        'avantage_service',       // Avantage au service (1-10)
        'avantage_retour',        // Avantage au retour (1-10)
        'facilite_passing_shots', // Facilite les passing shots

        // Impact sur les styles de jeu
        'avantage_baseline',      // Avantage joueurs de fond (1-10)
        'avantage_serve_volley',  // Avantage serveurs-volleyeurs (1-10)
        'avantage_all_court',     // Avantage joueurs complets (1-10)
        'penalise_defensive',     // Pénalise le jeu défensif

        // Conditions d'influence
        'sensible_humidite',      // Sensible à l'humidité
        'sensible_temperature',   // Sensible à la température
        'sensible_altitude',      // Sensible à l'altitude
        'facteur_meteo',          // Facteur météorologique (1-10)

        // Statistiques type
        'duree_moyenne_point',    // Durée moyenne des points (secondes)
        'nb_coups_moyen_echange', // Nombre de coups moyen par échange
        'pourcentage_aces_type',  // % d'aces typique sur cette vitesse
        'pourcentage_breaks_type', // % de breaks typique

        // Configuration d'affichage
        'couleur_hex',            // Couleur représentative
        'icone',                  // Icône ou emoji
        'ordre_affichage',        // Ordre dans les listes
        'est_visible',            // Visible dans l'interface

        // Métadonnées
        'notes',
        'source_donnees',         // Source des mesures
        'date_derniere_mesure',   // Date de dernière mesure
        'actif',
    ];

    protected $casts = [
        'valeur' => 'decimal:2',
        'court_pace_index' => 'decimal:2',
        'coefficient_restitution' => 'decimal:3',
        'angle_rebond_moyen' => 'decimal:1',
        'hauteur_rebond_relative' => 'decimal:2',
        'niveau_vitesse' => 'integer',
        'avantage_service' => 'integer',
        'avantage_retour' => 'integer',
        'avantage_baseline' => 'integer',
        'avantage_serve_volley' => 'integer',
        'avantage_all_court' => 'integer',
        'facteur_meteo' => 'integer',
        'duree_moyenne_point' => 'decimal:1',
        'nb_coups_moyen_echange' => 'decimal:1',
        'pourcentage_aces_type' => 'decimal:1',
        'pourcentage_breaks_type' => 'decimal:1',
        'ordre_affichage' => 'integer',
        'est_homologue_itf' => 'boolean',
        'favorise_puissance' => 'boolean',
        'favorise_precision' => 'boolean',
        'favorise_endurance' => 'boolean',
        'facilite_passing_shots' => 'boolean',
        'penalise_defensive' => 'boolean',
        'sensible_humidite' => 'boolean',
        'sensible_temperature' => 'boolean',
        'sensible_altitude' => 'boolean',
        'est_visible' => 'boolean',
        'actif' => 'boolean',
        'date_derniere_mesure' => 'date',
    ];

    protected $appends = [
        'classification_detaillee',
        'style_jeu_favorise',
        'impact_conditions',
        'vitesse_relative',
        'caracteristiques_principales',
        'avantage_tactique_principal',
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function surfaces()
    {
        return $this->hasMany(Surface::class, 'vitesse_surface_id');
    }

    public function tournois()
    {
        return $this->hasManyThrough(Tournoi::class, Surface::class, 'vitesse_surface_id', 'surface_id');
    }

    public function matchs()
    {
        return $this->hasManyThrough(MatchTennis::class, Tournoi::class, 'surface_id', 'tournoi_id')
            ->whereHas('tournoi.surface', function ($q) {
                $q->where('vitesse_surface_id', $this->id);
            });
    }

    // ===================================================================
    // ACCESSORS
    // ===================================================================

    public function getClassificationDetailleeAttribute()
    {
        $classifications = [
            1 => 'Très lente (Clay lourd)',
            2 => 'Lente (Clay standard)',
            3 => 'Moyenne (Hard court standard)',
            4 => 'Rapide (Hard court rapide)',
            5 => 'Très rapide (Grass, Hard indoor)',
        ];

        return $classifications[$this->niveau_vitesse] ?? 'Non classifiée';
    }

    public function getStyleJeuFavoriseAttribute()
    {
        $avantages = [
            'baseline' => $this->avantage_baseline ?? 5,
            'serve_volley' => $this->avantage_serve_volley ?? 5,
            'all_court' => $this->avantage_all_court ?? 5,
        ];

        $styleMax = array_keys($avantages, max($avantages))[0];

        $styles = [
            'baseline' => 'Joueurs de fond de court',
            'serve_volley' => 'Serveurs-volleyeurs',
            'all_court' => 'Joueurs complets',
        ];

        return $styles[$styleMax] ?? 'Équilibré';
    }

    public function getImpactConditionsAttribute()
    {
        $impacts = [];

        if ($this->sensible_humidite) {
            $impacts[] = 'Humidité';
        }
        if ($this->sensible_temperature) {
            $impacts[] = 'Température';
        }
        if ($this->sensible_altitude) {
            $impacts[] = 'Altitude';
        }

        return empty($impacts) ? 'Stable' : implode(', ', $impacts);
    }

    public function getVitesseRelativeAttribute()
    {
        if ($this->valeur >= 80) {
            return 'Très rapide';
        }
        if ($this->valeur >= 60) {
            return 'Rapide';
        }
        if ($this->valeur >= 40) {
            return 'Moyenne';
        }
        if ($this->valeur >= 20) {
            return 'Lente';
        }

        return 'Très lente';
    }

    public function getCaracteristiquesPrincipalesAttribute()
    {
        $caracteristiques = [];

        if ($this->favorise_puissance) {
            $caracteristiques[] = 'Puissance';
        }
        if ($this->favorise_precision) {
            $caracteristiques[] = 'Précision';
        }
        if ($this->favorise_endurance) {
            $caracteristiques[] = 'Endurance';
        }
        if ($this->facilite_passing_shots) {
            $caracteristiques[] = 'Passing shots';
        }

        return empty($caracteristiques) ? ['Équilibrée'] : $caracteristiques;
    }

    public function getAvantagesTactiquePrincipalAttribute()
    {
        if ($this->avantage_service > $this->avantage_retour + 2) {
            return 'Service dominateur';
        } elseif ($this->avantage_retour > $this->avantage_service + 2) {
            return 'Retour favorisé';
        } else {
            return 'Équilibré service/retour';
        }
    }

    public function getCouleurAffichageAttribute()
    {
        if ($this->couleur_hex) {
            return $this->couleur_hex;
        }

        // Couleurs par défaut selon la vitesse
        $couleurs = [
            1 => '#8B4513', // Marron (clay lent)
            2 => '#CD853F', // Sable (clay moyen)
            3 => '#4682B4', // Bleu acier (hard moyen)
            4 => '#FF6347', // Rouge tomate (rapide)
            5 => '#32CD32',  // Vert lime (très rapide)
        ];

        return $couleurs[$this->niveau_vitesse] ?? '#808080';
    }

    // ===================================================================
    // SCOPES
    // ===================================================================

    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    public function scopeVisibles($query)
    {
        return $query->where('est_visible', true);
    }

    public function scopeHomologues($query)
    {
        return $query->where('est_homologue_itf', true);
    }

    public function scopeParNiveau($query, $niveau)
    {
        return $query->where('niveau_vitesse', $niveau);
    }

    public function scopeLentes($query)
    {
        return $query->whereIn('niveau_vitesse', [1, 2]);
    }

    public function scopeMoyennes($query)
    {
        return $query->where('niveau_vitesse', 3);
    }

    public function scopeRapides($query)
    {
        return $query->whereIn('niveau_vitesse', [4, 5]);
    }

    public function scopeParValeur($query, $min, $max)
    {
        return $query->whereBetween('valeur', [$min, $max]);
    }

    public function scopeFavoriseStyle($query, $style)
    {
        $champ = "avantage_{$style}";

        return $query->where($champ, '>=', 7);
    }

    public function scopeSensibleMeteo($query)
    {
        return $query->where(function ($q) {
            $q->where('sensible_humidite', true)
                ->orWhere('sensible_temperature', true)
                ->orWhere('sensible_altitude', true);
        });
    }

    public function scopeOrdonnes($query)
    {
        return $query->orderBy('niveau_vitesse')
            ->orderBy('valeur')
            ->orderBy('nom');
    }

    public function scopeRecherche($query, $terme)
    {
        return $query->where(function ($q) use ($terme) {
            $q->where('nom', 'LIKE', "%{$terme}%")
                ->orWhere('code', 'LIKE', "%{$terme}%")
                ->orWhere('description', 'LIKE', "%{$terme}%")
                ->orWhere('categorie_itf', 'LIKE', "%{$terme}%");
        });
    }

    // ===================================================================
    // STATIC METHODS
    // ===================================================================

    /**
     * Créer les vitesses standard du tennis
     */
    public static function creerVitessesStandard()
    {
        $vitesses = [
            [
                'nom' => 'Très lente',
                'code' => 'very_slow',
                'nom_anglais' => 'Very Slow',
                'niveau_vitesse' => 1,
                'valeur' => 15,
                'court_pace_index' => 20,
                'avantage_baseline' => 9,
                'avantage_serve_volley' => 3,
                'avantage_service' => 3,
                'avantage_retour' => 8,
                'favorise_endurance' => true,
                'favorise_precision' => true,
                'duree_moyenne_point' => 8.5,
                'nb_coups_moyen_echange' => 12,
                'pourcentage_aces_type' => 3.2,
            ],
            [
                'nom' => 'Lente',
                'code' => 'slow',
                'nom_anglais' => 'Slow',
                'niveau_vitesse' => 2,
                'valeur' => 30,
                'court_pace_index' => 35,
                'avantage_baseline' => 8,
                'avantage_serve_volley' => 4,
                'avantage_service' => 4,
                'avantage_retour' => 7,
                'favorise_endurance' => true,
                'favorise_precision' => true,
                'duree_moyenne_point' => 6.8,
                'nb_coups_moyen_echange' => 9,
                'pourcentage_aces_type' => 5.1,
            ],
            [
                'nom' => 'Moyenne',
                'code' => 'medium',
                'nom_anglais' => 'Medium',
                'niveau_vitesse' => 3,
                'valeur' => 50,
                'court_pace_index' => 50,
                'avantage_baseline' => 6,
                'avantage_serve_volley' => 6,
                'avantage_all_court' => 8,
                'avantage_service' => 6,
                'avantage_retour' => 6,
                'duree_moyenne_point' => 5.2,
                'nb_coups_moyen_echange' => 7,
                'pourcentage_aces_type' => 8.3,
            ],
            [
                'nom' => 'Rapide',
                'code' => 'fast',
                'nom_anglais' => 'Fast',
                'niveau_vitesse' => 4,
                'valeur' => 75,
                'court_pace_index' => 70,
                'avantage_baseline' => 4,
                'avantage_serve_volley' => 8,
                'avantage_service' => 8,
                'avantage_retour' => 4,
                'favorise_puissance' => true,
                'facilite_passing_shots' => false,
                'duree_moyenne_point' => 3.8,
                'nb_coups_moyen_echange' => 4.5,
                'pourcentage_aces_type' => 12.7,
            ],
            [
                'nom' => 'Très rapide',
                'code' => 'very_fast',
                'nom_anglais' => 'Very Fast',
                'niveau_vitesse' => 5,
                'valeur' => 90,
                'court_pace_index' => 85,
                'avantage_baseline' => 3,
                'avantage_serve_volley' => 9,
                'avantage_service' => 9,
                'avantage_retour' => 3,
                'favorise_puissance' => true,
                'penalise_defensive' => true,
                'duree_moyenne_point' => 2.9,
                'nb_coups_moyen_echange' => 3.2,
                'pourcentage_aces_type' => 18.4,
            ],
        ];

        foreach ($vitesses as $vitesse) {
            $vitesse['est_homologue_itf'] = true;
            $vitesse['actif'] = true;
            $vitesse['est_visible'] = true;

            self::firstOrCreate(
                ['code' => $vitesse['code']],
                $vitesse
            );
        }
    }

    /**
     * Obtenir la classification complète
     */
    public static function getClassification()
    {
        return self::actifs()
            ->ordonnes()
            ->get()
            ->mapWithKeys(function ($vitesse) {
                return [$vitesse->code => [
                    'nom' => $vitesse->nom,
                    'niveau' => $vitesse->niveau_vitesse,
                    'valeur' => $vitesse->valeur,
                    'style_favorise' => $vitesse->style_jeu_favorise,
                ]];
            });
    }

    // ===================================================================
    // METHODS
    // ===================================================================

    /**
     * Calculer l'impact de conditions sur la vitesse
     */
    public function calculerImpactConditions($temperature = null, $humidite = null, $altitude = null)
    {
        $vitesseAjustee = $this->valeur;
        $impacts = [];

        // Impact température
        if ($temperature !== null && $this->sensible_temperature) {
            if ($temperature > 30) {
                $vitesseAjustee += 3; // Plus rapide par temps chaud
                $impacts[] = 'Température élevée: +3 vitesse';
            } elseif ($temperature < 10) {
                $vitesseAjustee -= 5; // Plus lent par temps froid
                $impacts[] = 'Température basse: -5 vitesse';
            }
        }

        // Impact humidité
        if ($humidite !== null && $this->sensible_humidite) {
            if ($humidite > 80) {
                $vitesseAjustee -= 4; // Plus lent par forte humidité
                $impacts[] = 'Humidité élevée: -4 vitesse';
            }
        }

        // Impact altitude
        if ($altitude !== null && $this->sensible_altitude) {
            if ($altitude > 1000) {
                $facteurAltitude = ($altitude - 1000) / 1000 * 2;
                $vitesseAjustee += $facteurAltitude; // Plus rapide en altitude
                $impacts[] = "Altitude: +{$facteurAltitude} vitesse";
            }
        }

        return [
            'vitesse_originale' => $this->valeur,
            'vitesse_ajustee' => round($vitesseAjustee, 1),
            'impacts' => $impacts,
            'difference' => round($vitesseAjustee - $this->valeur, 1),
        ];
    }

    /**
     * Recommander les stratégies selon la vitesse
     */
    public function getStrategiesRecommandees()
    {
        $strategies = [];

        if ($this->niveau_vitesse <= 2) { // Surfaces lentes
            $strategies = [
                'Privilégier la constance et la longueur',
                'Jouer avec beaucoup d\'effet',
                'Préparer les points patiemment',
                'Utiliser les angles pour fatiguer l\'adversaire',
                'Rester principalement en fond de court',
            ];
        } elseif ($this->niveau_vitesse >= 4) { // Surfaces rapides
            $strategies = [
                'Service-volée si maîtrisé',
                'Prendre la balle tôt et jouer court',
                'Minimiser les échanges longs',
                'Jouer sur les premières balles',
                'Attaquer le filet quand possible',
            ];
        } else { // Surfaces moyennes
            $strategies = [
                'Adapter le jeu selon l\'adversaire',
                'Utiliser tous les coups du répertoire',
                'Alterner jeu d\'attaque et de patience',
                'Prendre l\'initiative quand possible',
                'Rester flexible tactiquement',
            ];
        }

        return $strategies;
    }

    /**
     * Analyser l'adéquation avec un style de joueur
     */
    public function analyserAdequationStyle($styleJoueur)
    {
        $adequations = [
            'baseline' => $this->avantage_baseline ?? 5,
            'serve_volley' => $this->avantage_serve_volley ?? 5,
            'all_court' => $this->avantage_all_court ?? 5,
            'defensive' => $this->penalise_defensive ? 2 : 6,
            'aggressive' => $this->favorise_puissance ? 8 : 5,
        ];

        $score = $adequations[$styleJoueur] ?? 5;

        $evaluations = [
            1 - 2 => 'Très défavorable',
            3 - 4 => 'Défavorable',
            5 - 6 => 'Neutre',
            7 - 8 => 'Favorable',
            9 - 10 => 'Très favorable',
        ];

        foreach ($evaluations as $range => $evaluation) {
            if (is_string($range)) {
                [$min, $max] = explode('-', $range);
                if ($score >= $min && $score <= $max) {
                    return [
                        'score' => $score,
                        'evaluation' => $evaluation,
                        'recommandations' => $this->getRecommandationsPourStyle($styleJoueur, $score),
                    ];
                }
            }
        }

        return [
            'score' => $score,
            'evaluation' => 'Non évalué',
            'recommandations' => [],
        ];
    }

    /**
     * Obtenir les recommandations pour un style donné
     */
    private function getRecommandationsPourStyle($style, $score)
    {
        if ($score >= 7) {
            return ["Cette surface convient parfaitement à votre style {$style}"];
        } elseif ($score <= 4) {
            return [
                "Cette surface est défavorable à votre style {$style}",
                'Adaptez votre jeu en conséquence',
                'Considérez changer de tactique',
            ];
        } else {
            return ["Surface neutre pour votre style {$style}"];
        }
    }

    /**
     * Obtenir les statistiques de performance sur cette vitesse
     */
    public function getStatistiquesPerformance()
    {
        return [
            'duree_moyenne_point' => $this->duree_moyenne_point,
            'nb_coups_moyen' => $this->nb_coups_moyen_echange,
            'pourcentage_aces' => $this->pourcentage_aces_type,
            'pourcentage_breaks' => $this->pourcentage_breaks_type,
            'style_dominant' => $this->style_jeu_favorise,
            'avantage_principal' => $this->avantage_tactique_principal,
        ];
    }

    // ===================================================================
    // VALIDATION RULES
    // ===================================================================

    public static function validationRules()
    {
        return [
            'nom' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:vitesse_surfaces,code',
            'valeur' => 'required|numeric|min:0|max:100',
            'niveau_vitesse' => 'required|integer|min:1|max:5',
            'court_pace_index' => 'nullable|numeric|min:0|max:100',
            'avantage_service' => 'nullable|integer|min:1|max:10',
            'avantage_retour' => 'nullable|integer|min:1|max:10',
        ];
    }

    // ===================================================================
    // BOOT METHODS
    // ===================================================================

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($vitesse) {
            // Auto-calcul des avantages si manquants
            if (! $vitesse->avantage_all_court) {
                $vitesse->avantage_all_court = round(
                    (($vitesse->avantage_baseline ?? 5) + ($vitesse->avantage_serve_volley ?? 5)) / 2
                );
            }

            // Générer l'ordre d'affichage
            if (! $vitesse->ordre_affichage) {
                $vitesse->ordre_affichage = $vitesse->niveau_vitesse ?? 1;
            }

            // Valeurs par défaut
            if ($vitesse->actif === null) {
                $vitesse->actif = true;
            }
            if ($vitesse->est_visible === null) {
                $vitesse->est_visible = true;
            }
        });
    }
}
