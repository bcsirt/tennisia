<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeBlessure extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'type_blessures';

    protected $fillable = [
        // Informations de base
        'nom',
        'nom_medical',            // Nom médical officiel
        'nom_anglais',            // Tennis injury name
        'code',                   // 'tennis_elbow', 'ankle_sprain', etc.
        'description',
        'synonymes',              // JSON des autres noms

        // Classification médicale
        'categorie',              // 'musculaire', 'articulaire', 'osseuse', 'tendineuse'
        'sous_categorie',         // 'acute', 'chronic', 'overuse', 'traumatic'
        'zone_corporelle',        // 'bras', 'jambe', 'dos', 'epaule', etc.
        'partie_specifique',      // 'coude', 'poignet', 'cheville', 'genou'
        'cote_affecte',          // 'gauche', 'droite', 'bilateral', 'variable'

        // Gravité et impact
        'niveau_gravite',         // 1-10 (10 = très grave)
        'est_grave',              // Blessure considérée comme grave
        'est_chronique',          // Peut devenir chronique
        'est_recidivante',        // Tendance à récidiver
        'necessite_chirurgie',    // Peut nécessiter une intervention

        // Durées et récupération
        'duree_min_repos',        // Jours minimum de repos
        'duree_max_repos',        // Jours maximum de repos
        'duree_moyenne_guerison', // Durée moyenne de guérison (jours)
        'duree_readaptation',     // Durée de réadaptation (jours)
        'delai_retour_competition', // Délai pour retour compétition (jours)

        // Impact sur la performance
        'impact_vitesse',         // Impact sur vitesse de jeu (-10 à +10)
        'impact_puissance',       // Impact sur puissance (-10 à +10)
        'impact_endurance',       // Impact sur endurance (-10 à +10)
        'impact_mobilite',        // Impact sur mobilité (-10 à +10)
        'impact_precision',       // Impact sur précision (-10 à +10)
        'impact_mental',          // Impact psychologique (-10 à +10)

        // Spécificités tennis
        'affecte_service',        // Affecte le service
        'affecte_coup_droit',     // Affecte le coup droit
        'affecte_revers',         // Affecte le revers
        'affecte_volley',         // Affecte les volées
        'affecte_deplacement',    // Affecte les déplacements
        'affecte_smash',          // Affecte le smash

        // Facteurs de risque
        'facteurs_risque',        // JSON des facteurs (âge, style, surface)
        'surfaces_risque',        // JSON surfaces qui augmentent le risque
        'conditions_risque',      // JSON conditions météo risquées
        'style_jeu_risque',       // Styles de jeu à risque (JSON)

        // Prévention
        'mesures_prevention',     // JSON des mesures préventives
        'exercices_prevention',   // JSON des exercices préventifs
        'materiel_prevention',    // Matériel de prévention recommandé
        'echauffement_specifique', // Échauffement spécifique nécessaire

        // Traitement et récupération
        'traitements_initiaux',   // JSON des premiers soins
        'traitements_medicaux',   // JSON des traitements médicaux
        'phases_readaptation',    // JSON des phases de récupération
        'exercices_readaptation', // JSON des exercices de récupération

        // Statistiques et épidémiologie
        'frequence_tennis',       // Fréquence dans le tennis (%)
        'age_moyen_occurrence',   // Âge moyen d'occurrence
        'ratio_homme_femme',      // Ratio hommes/femmes (float)
        'saison_frequente',       // Saison où elle est plus fréquente
        'niveau_jeu_frequent',    // Niveau de jeu le plus touché

        // Impact sur carrière
        'peut_finir_carriere',    // Peut mettre fin à une carrière
        'impact_classement',      // Impact typique sur classement (-X places)
        'pourcentage_recidive',   // % de risque de récidive
        'cout_medical_moyen',     // Coût médical moyen

        // Détection et diagnostic
        'symptomes_principaux',   // JSON des symptômes
        'tests_diagnostic',       // JSON des tests médicaux
        'examens_necessaires',    // JSON des examens (IRM, radio, etc.)
        'criteres_diagnostic',    // Critères de diagnostic

        // Retour au jeu
        'criteres_retour',        // Critères pour reprendre
        'tests_retour',           // Tests avant retour au jeu
        'programme_retour',       // Programme de retour progressif
        'surveillance_post',      // Surveillance post-retour

        // Métadonnées
        'source_medicale',        // Source des informations médicales
        'derniere_maj_medicale',  // Dernière mise à jour médicale
        'valide_medical',         // Validé par professionnel médical
        'references_etudes',      // JSON des références d'études
        'icone',                  // Icône représentative
        'couleur_hex',            // Couleur d'affichage
        'ordre_affichage',        // Ordre dans les listes
        'actif',
    ];

    protected $casts = [
        'synonymes' => 'json',
        'niveau_gravite' => 'integer',
        'duree_min_repos' => 'integer',
        'duree_max_repos' => 'integer',
        'duree_moyenne_guerison' => 'integer',
        'duree_readaptation' => 'integer',
        'delai_retour_competition' => 'integer',
        'impact_vitesse' => 'integer',
        'impact_puissance' => 'integer',
        'impact_endurance' => 'integer',
        'impact_mobilite' => 'integer',
        'impact_precision' => 'integer',
        'impact_mental' => 'integer',
        'facteurs_risque' => 'json',
        'surfaces_risque' => 'json',
        'conditions_risque' => 'json',
        'style_jeu_risque' => 'json',
        'mesures_prevention' => 'json',
        'exercices_prevention' => 'json',
        'traitements_initiaux' => 'json',
        'traitements_medicaux' => 'json',
        'phases_readaptation' => 'json',
        'exercices_readaptation' => 'json',
        'frequence_tennis' => 'decimal:2',
        'age_moyen_occurrence' => 'decimal:1',
        'ratio_homme_femme' => 'decimal:2',
        'impact_classement' => 'integer',
        'pourcentage_recidive' => 'decimal:1',
        'cout_medical_moyen' => 'decimal:2',
        'symptomes_principaux' => 'json',
        'tests_diagnostic' => 'json',
        'examens_necessaires' => 'json',
        'criteres_diagnostic' => 'json',
        'criteres_retour' => 'json',
        'tests_retour' => 'json',
        'programme_retour' => 'json',
        'surveillance_post' => 'json',
        'references_etudes' => 'json',
        'ordre_affichage' => 'integer',
        'derniere_maj_medicale' => 'date',
        'est_grave' => 'boolean',
        'est_chronique' => 'boolean',
        'est_recidivante' => 'boolean',
        'necessite_chirurgie' => 'boolean',
        'affecte_service' => 'boolean',
        'affecte_coup_droit' => 'boolean',
        'affecte_revers' => 'boolean',
        'affecte_volley' => 'boolean',
        'affecte_deplacement' => 'boolean',
        'affecte_smash' => 'boolean',
        'peut_finir_carriere' => 'boolean',
        'valide_medical' => 'boolean',
        'actif' => 'boolean',
    ];

    protected $appends = [
        'niveau_gravite_texte',
        'duree_recuperation_estimee',
        'impact_global_performance',
        'risque_surface_principale',
        'coups_tennis_affectes',
        'phase_critique_saison',
        'facteur_ajustement_ia',
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function blessures()
    {
        return $this->hasMany(Blessure::class, 'type_blessure_id');
    }

    public function blessuresActives()
    {
        return $this->hasMany(Blessure::class, 'type_blessure_id')
            ->where('est_active', true);
    }

    public function joueursConcernes()
    {
        return $this->belongsToMany(Joueur::class, 'blessures')
            ->withPivot(['date_debut', 'date_fin', 'gravite'])
            ->withTimestamps();
    }

    public function statistiquesBlessures()
    {
        return $this->hasMany(StatistiqueBlessure::class, 'type_blessure_id');
    }

    // ===================================================================
    // ACCESSORS
    // ===================================================================

    public function getNiveauGraviteTexteAttribute()
    {
        $niveaux = [
            1 - 2 => 'Très légère',
            3 - 4 => 'Légère',
            5 - 6 => 'Modérée',
            7 - 8 => 'Grave',
            9 - 10 => 'Très grave',
        ];

        foreach ($niveaux as $range => $niveau) {
            if (is_string($range)) {
                [$min, $max] = explode('-', $range);
                if ($this->niveau_gravite >= $min && $this->niveau_gravite <= $max) {
                    return $niveau;
                }
            }
        }

        return 'Non évaluée';
    }

    public function getDureeRecuperationEstimeeAttribute()
    {
        if (! $this->duree_moyenne_guerison) {
            return 'Non déterminée';
        }

        $jours = $this->duree_moyenne_guerison;

        if ($jours <= 7) {
            return '1 semaine';
        }
        if ($jours <= 14) {
            return '2 semaines';
        }
        if ($jours <= 30) {
            return '1 mois';
        }
        if ($jours <= 60) {
            return '2 mois';
        }
        if ($jours <= 90) {
            return '3 mois';
        }
        if ($jours <= 180) {
            return '6 mois';
        }

        return 'Plus de 6 mois';
    }

    public function getImpactGlobalPerformanceAttribute()
    {
        $impacts = [
            $this->impact_vitesse ?? 0,
            $this->impact_puissance ?? 0,
            $this->impact_endurance ?? 0,
            $this->impact_mobilite ?? 0,
            $this->impact_precision ?? 0,
        ];

        $moyenne = array_sum($impacts) / count(array_filter($impacts, function ($v) {
            return $v !== 0;
        }));

        if ($moyenne <= -7) {
            return 'Impact très sévère';
        }
        if ($moyenne <= -4) {
            return 'Impact sévère';
        }
        if ($moyenne <= -2) {
            return 'Impact modéré';
        }
        if ($moyenne <= -1) {
            return 'Impact léger';
        }

        return 'Impact minimal';
    }

    public function getRisqueSurfacePrincipaleAttribute()
    {
        if (! $this->surfaces_risque) {
            return 'Toutes surfaces';
        }

        $surfaces = $this->surfaces_risque;

        // Mapper vers noms complets
        $mapping = [
            'hard' => 'Dur',
            'clay' => 'Terre battue',
            'grass' => 'Gazon',
            'indoor' => 'Indoor',
        ];

        $surfacesNoms = array_map(function ($surface) use ($mapping) {
            return $mapping[$surface] ?? $surface;
        }, $surfaces);

        return implode(', ', $surfacesNoms);
    }

    public function getCoupsTennisAffectesAttribute()
    {
        $coups = [];

        if ($this->affecte_service) {
            $coups[] = 'Service';
        }
        if ($this->affecte_coup_droit) {
            $coups[] = 'Coup droit';
        }
        if ($this->affecte_revers) {
            $coups[] = 'Revers';
        }
        if ($this->affecte_volley) {
            $coups[] = 'Volée';
        }
        if ($this->affecte_smash) {
            $coups[] = 'Smash';
        }
        if ($this->affecte_deplacement) {
            $coups[] = 'Déplacements';
        }

        return empty($coups) ? ['Aucun impact spécifique'] : $coups;
    }

    public function getPhaseCritiqueSaisonAttribute()
    {
        if (! $this->saison_frequente) {
            return 'Toute l\'année';
        }

        $saisons = [
            'printemps' => 'Printemps (saison terre)',
            'ete' => 'Été (saison gazon + dur)',
            'automne' => 'Automne (fin de saison)',
            'hiver' => 'Hiver (indoor)',
            'debut_saison' => 'Début de saison',
            'fin_saison' => 'Fin de saison',
        ];

        return $saisons[$this->saison_frequente] ?? $this->saison_frequente;
    }

    public function getFacteurAjustementIaAttribute()
    {
        // Facteur d'ajustement pour les algorithmes IA (-1 à +1)
        $facteur = 0;

        // Impact selon la gravité
        if ($this->niveau_gravite >= 8) {
            $facteur = -0.8;
        } elseif ($this->niveau_gravite >= 6) {
            $facteur = -0.5;
        } elseif ($this->niveau_gravite >= 4) {
            $facteur = -0.3;
        } elseif ($this->niveau_gravite >= 2) {
            $facteur = -0.1;
        }

        // Ajustement selon l'impact sur performance
        $impactMoyen = array_sum([
            $this->impact_vitesse ?? 0,
            $this->impact_puissance ?? 0,
            $this->impact_endurance ?? 0,
        ]) / 3;

        $facteur += $impactMoyen / 10; // Normaliser entre -1 et +1

        return max(-1, min(1, $facteur));
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
        return $query->where('categorie', $categorie);
    }

    public function scopeParZoneCorporelle($query, $zone)
    {
        return $query->where('zone_corporelle', $zone);
    }

    public function scopeGraves($query)
    {
        return $query->where('est_grave', true)
            ->orWhere('niveau_gravite', '>=', 7);
    }

    public function scopeChroniques($query)
    {
        return $query->where('est_chronique', true);
    }

    public function scopeRecidivantes($query)
    {
        return $query->where('est_recidivante', true);
    }

    public function scopeNecessitentChirurgie($query)
    {
        return $query->where('necessite_chirurgie', true);
    }

    public function scopeAffectantCoups($query, $coup)
    {
        $champ = "affecte_{$coup}";

        return $query->where($champ, true);
    }

    public function scopeFrequentesSur($query, $surface)
    {
        return $query->whereJsonContains('surfaces_risque', $surface);
    }

    public function scopeParDureeRecuperation($query, $min, $max)
    {
        return $query->whereBetween('duree_moyenne_guerison', [$min, $max]);
    }

    public function scopeRecuperationRapide($query)
    {
        return $query->where('duree_moyenne_guerison', '<=', 14);
    }

    public function scopeRecuperationLongue($query)
    {
        return $query->where('duree_moyenne_guerison', '>', 90);
    }

    public function scopeOrdonnes($query)
    {
        return $query->orderBy('niveau_gravite', 'desc')
            ->orderBy('frequence_tennis', 'desc')
            ->orderBy('nom');
    }

    public function scopeRecherche($query, $terme)
    {
        return $query->where(function ($q) use ($terme) {
            $q->where('nom', 'LIKE', "%{$terme}%")
                ->orWhere('nom_medical', 'LIKE', "%{$terme}%")
                ->orWhere('zone_corporelle', 'LIKE', "%{$terme}%")
                ->orWhere('partie_specifique', 'LIKE', "%{$terme}%");
        });
    }

    // ===================================================================
    // STATIC METHODS
    // ===================================================================

    /**
     * Créer les types de blessures tennis standard
     */
    public static function creerBlessuresStandard()
    {
        $blessures = [
            [
                'nom' => 'Tennis Elbow',
                'nom_medical' => 'Épicondylite latérale',
                'code' => 'tennis_elbow',
                'categorie' => 'tendineuse',
                'sous_categorie' => 'overuse',
                'zone_corporelle' => 'bras',
                'partie_specifique' => 'coude',
                'niveau_gravite' => 6,
                'est_chronique' => true,
                'est_recidivante' => true,
                'duree_moyenne_guerison' => 90,
                'impact_coup_droit' => true,
                'affecte_revers' => true,
                'affecte_service' => true,
                'impact_puissance' => -6,
                'impact_precision' => -4,
                'frequence_tennis' => 23.5,
                'surfaces_risque' => ['hard'],
                'facteurs_risque' => ['technique_défaillante', 'raquette_inadaptée', 'surentraînement'],
            ],
            [
                'nom' => 'Entorse cheville',
                'nom_medical' => 'Entorse ligamentaire cheville',
                'code' => 'ankle_sprain',
                'categorie' => 'articulaire',
                'sous_categorie' => 'traumatic',
                'zone_corporelle' => 'jambe',
                'partie_specifique' => 'cheville',
                'niveau_gravite' => 5,
                'duree_moyenne_guerison' => 21,
                'affecte_deplacement' => true,
                'impact_mobilite' => -8,
                'impact_vitesse' => -5,
                'frequence_tennis' => 18.2,
                'surfaces_risque' => ['clay', 'grass'],
                'facteurs_risque' => ['terrain_glissant', 'fatigue', 'chaussures_inadaptées'],
            ],
            [
                'nom' => 'Déchirure musculaire',
                'nom_medical' => 'Lésion musculaire aiguë',
                'code' => 'muscle_tear',
                'categorie' => 'musculaire',
                'sous_categorie' => 'acute',
                'zone_corporelle' => 'jambe',
                'partie_specifique' => 'cuisse',
                'niveau_gravite' => 7,
                'duree_moyenne_guerison' => 42,
                'affecte_deplacement' => true,
                'affecte_service' => true,
                'impact_vitesse' => -9,
                'impact_puissance' => -7,
                'frequence_tennis' => 15.8,
                'facteurs_risque' => ['échauffement_insuffisant', 'fatigue', 'déséquilibre_musculaire'],
            ],
            [
                'nom' => 'Tendinite épaule',
                'nom_medical' => 'Tendinopathie de la coiffe des rotateurs',
                'code' => 'shoulder_tendinitis',
                'categorie' => 'tendineuse',
                'sous_categorie' => 'overuse',
                'zone_corporelle' => 'bras',
                'partie_specifique' => 'epaule',
                'niveau_gravite' => 6,
                'est_chronique' => true,
                'duree_moyenne_guerison' => 60,
                'affecte_service' => true,
                'affecte_smash' => true,
                'impact_puissance' => -8,
                'impact_precision' => -3,
                'frequence_tennis' => 12.4,
                'facteurs_risque' => ['volume_service_élevé', 'technique_défaillante'],
            ],
            [
                'nom' => 'Lumbago',
                'nom_medical' => 'Lombalgie aiguë',
                'code' => 'lower_back_pain',
                'categorie' => 'musculaire',
                'sous_categorie' => 'acute',
                'zone_corporelle' => 'dos',
                'partie_specifique' => 'lombaires',
                'niveau_gravite' => 5,
                'duree_moyenne_guerison' => 14,
                'affecte_service' => true,
                'affecte_coup_droit' => true,
                'affecte_revers' => true,
                'impact_mobilite' => -7,
                'impact_puissance' => -5,
                'frequence_tennis' => 11.2,
                'facteurs_risque' => ['rotation_excessive', 'déséquilibre_musculaire'],
            ],
            [
                'nom' => 'Fasciite plantaire',
                'nom_medical' => 'Aponévrosite plantaire',
                'code' => 'plantar_fasciitis',
                'categorie' => 'tendineuse',
                'sous_categorie' => 'overuse',
                'zone_corporelle' => 'jambe',
                'partie_specifique' => 'pied',
                'niveau_gravite' => 4,
                'est_chronique' => true,
                'duree_moyenne_guerison' => 45,
                'affecte_deplacement' => true,
                'impact_mobilite' => -6,
                'impact_endurance' => -4,
                'frequence_tennis' => 9.8,
                'surfaces_risque' => ['hard'],
                'facteurs_risque' => ['surpoids', 'chaussures_usées', 'surface_dure'],
            ],
            [
                'nom' => 'Tendinite poignet',
                'nom_medical' => 'Tendinopathie du poignet',
                'code' => 'wrist_tendinitis',
                'categorie' => 'tendineuse',
                'sous_categorie' => 'overuse',
                'zone_corporelle' => 'bras',
                'partie_specifique' => 'poignet',
                'niveau_gravite' => 5,
                'duree_moyenne_guerison' => 30,
                'affecte_coup_droit' => true,
                'affecte_revers' => true,
                'affecte_volley' => true,
                'impact_precision' => -7,
                'impact_puissance' => -4,
                'frequence_tennis' => 8.5,
                'facteurs_risque' => ['grip_incorrect', 'raquette_lourde'],
            ],
            [
                'nom' => 'Syndrome rotulien',
                'nom_medical' => 'Syndrome fémoro-patellaire',
                'code' => 'patella_syndrome',
                'categorie' => 'articulaire',
                'sous_categorie' => 'overuse',
                'zone_corporelle' => 'jambe',
                'partie_specifique' => 'genou',
                'niveau_gravite' => 6,
                'est_chronique' => true,
                'duree_moyenne_guerison' => 75,
                'affecte_deplacement' => true,
                'impact_mobilite' => -8,
                'impact_endurance' => -6,
                'frequence_tennis' => 7.3,
                'surfaces_risque' => ['hard', 'clay'],
                'facteurs_risque' => ['déséquilibre_musculaire', 'surcharge'],
            ],
        ];

        foreach ($blessures as $blessure) {
            // Valeurs par défaut
            $blessure['actif'] = true;
            $blessure['valide_medical'] = true;
            $blessure['derniere_maj_medicale'] = now();

            self::firstOrCreate(
                ['code' => $blessure['code']],
                $blessure
            );
        }
    }

    /**
     * Obtenir les blessures par zone corporelle
     */
    public static function parZoneCorporelle()
    {
        return self::actifs()
            ->select('zone_corporelle', 'partie_specifique', 'nom', 'niveau_gravite', 'frequence_tennis')
            ->ordonnes()
            ->get()
            ->groupBy('zone_corporelle');
    }

    /**
     * Obtenir les statistiques globales des blessures tennis
     */
    public static function getStatistiquesGlobales()
    {
        return [
            'nb_types_total' => self::count(),
            'nb_types_graves' => self::graves()->count(),
            'nb_types_chroniques' => self::chroniques()->count(),
            'duree_moyenne_globale' => self::avg('duree_moyenne_guerison'),
            'blessures_plus_frequentes' => self::orderBy('frequence_tennis', 'desc')
                ->take(5)
                ->pluck('nom', 'frequence_tennis'),
            'zones_plus_touchees' => self::selectRaw('zone_corporelle, COUNT(*) as nb')
                ->groupBy('zone_corporelle')
                ->orderBy('nb', 'desc')
                ->pluck('nb', 'zone_corporelle'),
        ];
    }

    // ===================================================================
    // METHODS
    // ===================================================================

    /**
     * Calculer le risque de blessure selon les conditions
     */
    public function calculerRisqueBlessure($surface, $conditionsMeteo = null, $styleJeu = null)
    {
        $risqueBase = $this->frequence_tennis ?? 0;
        $facteurAjustement = 1.0;

        // Ajustement selon la surface
        if ($this->surfaces_risque && in_array($surface, $this->surfaces_risque)) {
            $facteurAjustement *= 1.5; // +50% de risque
        }

        // Ajustement selon les conditions météo
        if ($conditionsMeteo && $this->conditions_risque) {
            foreach ($this->conditions_risque as $condition) {
                if (isset($conditionsMeteo[$condition])) {
                    $facteurAjustement *= 1.3; // +30% par condition défavorable
                }
            }
        }

        // Ajustement selon le style de jeu
        if ($styleJeu && $this->style_jeu_risque) {
            if (in_array($styleJeu, $this->style_jeu_risque)) {
                $facteurAjustement *= 1.4; // +40% de risque
            }
        }

        return min(100, $risqueBase * $facteurAjustement);
    }

    /**
     * Obtenir le plan de prévention personnalisé
     */
    public function getPlanPrevention($joueur = null)
    {
        $plan = [
            'mesures_generales' => $this->mesures_prevention ?? [],
            'exercices' => $this->exercices_prevention ?? [],
            'materiel' => $this->materiel_prevention,
            'echauffement' => $this->echauffement_specifique,
        ];

        // Personnalisation selon le joueur
        if ($joueur) {
            // Ajustements selon l'âge, historique, style, etc.
            if ($joueur->age > 30 && $this->est_chronique) {
                $plan['mesures_specifiques'][] = 'Surveillance accrue (âge > 30 ans)';
            }

            if ($joueur->hasHistoriqueBlessure($this->id)) {
                $plan['mesures_specifiques'][] = 'Suivi renforcé (antécédents)';
            }
        }

        return $plan;
    }

    /**
     * Estimer l'impact sur le classement
     */
    public function estumerImpactClassement($classementActuel, $dureeArret = null)
    {
        $duree = $dureeArret ?? $this->duree_moyenne_guerison;

        if (! $duree) {
            return 0;
        }

        // Formule d'estimation basée sur la durée et la gravité
        $impactBase = $this->impact_classement ?? 0;

        // Ajustement selon la durée réelle
        if ($duree > $this->duree_moyenne_guerison) {
            $facteurDuree = $duree / $this->duree_moyenne_guerison;
            $impactBase *= $facteurDuree;
        }

        // Impact différent selon le niveau
        if ($classementActuel <= 100) {
            $impactBase *= 1.5; // Plus d'impact pour les tops joueurs
        } elseif ($classementActuel <= 500) {
            $impactBase *= 1.2;
        }

        return round($impactBase);
    }

    /**
     * Générer le rapport de blessure complet
     */
    public function genererRapportComplet()
    {
        return [
            'informations_generales' => [
                'nom' => $this->nom,
                'nom_medical' => $this->nom_medical,
                'gravite' => $this->niveau_gravite_texte,
                'zone' => $this->zone_corporelle,
                'frequence' => $this->frequence_tennis.'%',
            ],
            'impact_tennis' => [
                'coups_affectes' => $this->coups_tennis_affectes,
                'impact_performance' => $this->impact_global_performance,
                'duree_recuperation' => $this->duree_recuperation_estimee,
            ],
            'facteurs_risque' => [
                'surfaces' => $this->risque_surface_principale,
                'facteurs' => $this->facteurs_risque ?? [],
                'saison_critique' => $this->phase_critique_saison,
            ],
            'prevention' => [
                'mesures' => $this->mesures_prevention ?? [],
                'exercices' => $this->exercices_prevention ?? [],
                'materiel' => $this->materiel_prevention,
            ],
            'traitement' => [
                'premiers_soins' => $this->traitements_initiaux ?? [],
                'medical' => $this->traitements_medicaux ?? [],
                'readaptation' => $this->phases_readaptation ?? [],
            ],
            'retour_jeu' => [
                'criteres' => $this->criteres_retour ?? [],
                'tests' => $this->tests_retour ?? [],
                'programme' => $this->programme_retour ?? [],
            ],
            'facteur_ia' => $this->facteur_ajustement_ia,
        ];
    }

    /**
     * Comparer avec d'autres types de blessures
     */
    public function comparerAvec($autreTypeBlessure)
    {
        return [
            'gravite' => [
                'actuelle' => $this->niveau_gravite,
                'comparee' => $autreTypeBlessure->niveau_gravite,
                'difference' => $this->niveau_gravite - $autreTypeBlessure->niveau_gravite,
            ],
            'frequence' => [
                'actuelle' => $this->frequence_tennis,
                'comparee' => $autreTypeBlessure->frequence_tennis,
                'difference' => $this->frequence_tennis - $autreTypeBlessure->frequence_tennis,
            ],
            'duree_guerison' => [
                'actuelle' => $this->duree_moyenne_guerison,
                'comparee' => $autreTypeBlessure->duree_moyenne_guerison,
                'difference' => $this->duree_moyenne_guerison - $autreTypeBlessure->duree_moyenne_guerison,
            ],
        ];
    }

    /**
     * Obtenir les recommandations selon le profil du joueur
     */
    public function getRecommandationsProfil($age, $sexe, $niveau, $styleJeu)
    {
        $recommendations = [];

        // Selon l'âge
        if ($age > 35 && $this->est_chronique) {
            $recommendations[] = 'Prévention renforcée recommandée après 35 ans';
        }

        // Selon le niveau
        if ($niveau === 'professionnel' && $this->peut_finir_carriere) {
            $recommendations[] = 'Suivi médical spécialisé obligatoire (niveau pro)';
        }

        // Selon le style de jeu
        if ($this->style_jeu_risque && in_array($styleJeu, $this->style_jeu_risque)) {
            $recommendations[] = "Votre style de jeu ({$styleJeu}) augmente les risques";
        }

        return $recommendations;
    }

    // ===================================================================
    // VALIDATION RULES
    // ===================================================================

    public static function validationRules()
    {
        return [
            'nom' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:type_blessures,code',
            'categorie' => 'required|in:musculaire,articulaire,osseuse,tendineuse,nerveuse',
            'zone_corporelle' => 'required|in:bras,jambe,dos,tete,tronc',
            'niveau_gravite' => 'required|integer|min:1|max:10',
            'duree_moyenne_guerison' => 'nullable|integer|min:1|max:365',
            'frequence_tennis' => 'nullable|numeric|min:0|max:100',
        ];
    }

    // ===================================================================
    // BOOT METHODS
    // ===================================================================

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($typeBlessure) {
            // Auto-détermination de la gravité
            if ($typeBlessure->duree_moyenne_guerison > 90) {
                $typeBlessure->est_grave = true;
            }

            if ($typeBlessure->pourcentage_recidive > 50) {
                $typeBlessure->est_recidivante = true;
            }

            // Ordre d'affichage par défaut
            if (! $typeBlessure->ordre_affichage) {
                $typeBlessure->ordre_affichage = $typeBlessure->niveau_gravite;
            }

            // Valeurs par défaut
            if ($typeBlessure->actif === null) {
                $typeBlessure->actif = true;
            }
        });
    }
}
