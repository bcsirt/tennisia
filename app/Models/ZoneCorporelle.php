<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZoneCorporelle extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'zone_corporelles';

    protected $fillable = [
        // Identification
        'nom',
        'nom_medical',            // Nom anatomique officiel
        'nom_anglais',            // Tennis anatomy term
        'code',                   // 'shoulder', 'elbow', 'knee', etc.
        'synonymes',              // JSON des autres appellations
        'description',

        // Classification anatomique
        'categorie_principale',   // 'membre_superieur', 'membre_inferieur', 'tronc', 'tete'
        'sous_categorie',         // 'bras', 'avant_bras', 'cuisse', 'jambe'
        'systeme_anatomique',     // 'musculo_squelettique', 'articulaire', 'nerveux'
        'lateralite',            // 'bilateral', 'gauche', 'droite', 'central'
        'position_corporelle',    // 'proximal', 'distal', 'central'

        // Zones spécifiques tennis
        'zone_parent_id',         // Zone parent (épaule -> bras)
        'sous_zones',             // JSON des sous-zones
        'zones_adjacentes',       // JSON des zones connexes
        'chaine_cinetique',       // Position dans chaîne mouvement tennis

        // Importance tennis
        'importance_tennis',      // 1-10 importance pour le tennis
        'sollicitation_frequence', // 1-10 fréquence sollicitation
        'impact_performance',     // 1-10 impact sur performance
        'complexite_mouvement',   // 1-10 complexité des mouvements

        // Impact sur coups tennis
        'impact_service',         // 1-10 impact sur service
        'impact_coup_droit',      // 1-10 impact coup droit
        'impact_revers',          // 1-10 impact revers
        'impact_vollee',          // 1-10 impact volée
        'impact_smash',           // 1-10 impact smash
        'impact_deplacement',     // 1-10 impact déplacements
        'impact_equilibre',       // 1-10 impact équilibre

        // Mouvements et biomécanique
        'mouvements_principaux',  // JSON des mouvements principaux
        'muscles_impliques',      // JSON des muscles principaux
        'articulations_liees',    // JSON des articulations
        'amplitude_mouvement',    // Amplitude normale (degrés)
        'force_requise',          // 1-10 force nécessaire
        'coordination_requise',   // 1-10 coordination nécessaire

        // Risques et vulnérabilités
        'niveau_risque_tennis',   // 1-10 niveau risque général tennis
        'types_blessures_frequentes', // JSON des blessures communes
        'facteurs_risque',        // JSON des facteurs de risque
        'surfaces_risque',        // JSON surfaces augmentant risque
        'conditions_risque',      // JSON conditions météo risquées
        'styles_jeu_risque',      // JSON styles de jeu à risque

        // Age et développement
        'age_maturation',         // Age de maturation de la zone
        'sensibilite_croissance', // Sensible pendant croissance
        'evolution_age',          // Evolution avec l'âge
        'periode_vulnerable',     // Période la plus vulnérable

        // Prévention spécialisée
        'exercices_prevention',   // JSON exercices préventifs
        'echauffement_specifique', // JSON échauffement spécifique
        'etirements_recommandes', // JSON étirements
        'renforcement_cible',     // JSON exercices renforcement
        'materiel_protection',    // Matériel de protection
        'surveillance_signes',    // Signes à surveiller

        // Traitement et récupération
        'premiers_soins_type',    // Type premiers soins typiques
        'traitements_courants',   // JSON traitements habituels
        'temps_guerison_moyen',   // Temps guérison moyen (jours)
        'complications_possibles', // JSON complications possibles
        'retour_jeu_criteres',    // JSON critères retour au jeu

        // Réadaptation
        'phases_readaptation',    // JSON phases de récupération
        'exercices_readaptation', // JSON exercices rééducation
        'tests_fonctionnels',     // JSON tests avant retour
        'progression_type',       // Type de progression recommandée
        'surveillance_post',      // Surveillance post-retour

        // Données épidémiologiques tennis
        'frequence_blessures',    // % blessures cette zone tennis
        'age_moyen_blessures',    // Age moyen blessures
        'sexe_plus_touche',       // 'M', 'F', 'egal'
        'niveau_plus_touche',     // Niveau plus touché
        'saison_pic_blessures',   // Saison avec plus de blessures

        // Performance et optimisation
        'influence_puissance',    // Influence sur puissance
        'influence_precision',    // Influence sur précision
        'influence_endurance',    // Influence sur endurance
        'influence_vitesse',      // Influence sur vitesse
        'compensation_possible',  // Compensation possible si blessée
        'impact_style_adaptation', // Impact sur adaptation style

        // Technologies et évaluation
        'methodes_evaluation',    // JSON méthodes évaluation
        'examens_recommandes',    // JSON examens conseillés
        'biomarqueurs',           // JSON biomarqueurs spécifiques
        'technologies_suivi',     // JSON technologies de suivi
        'capteurs_applicables',   // Capteurs utilisables

        // Spécificités par niveau
        'amateur_considerations', // Considérations joueurs amateurs
        'pro_considerations',     // Considérations joueurs pros
        'junior_considerations',  // Considérations juniors
        'veteran_considerations', // Considérations vétérans

        // Recherche et innovation
        'recherches_actuelles',   // JSON recherches en cours
        'innovations_traitement', // JSON innovations thérapeutiques
        'tendances_prevention',   // JSON tendances prévention
        'references_scientifiques', // JSON références études

        // Interface et visualisation
        'position_anatomique',    // JSON coordonnées anatomiques
        'couleur_visualisation',  // Couleur pour schémas
        'icone_representation',   // Icône représentative
        'schema_anatomique_url',  // URL schéma anatomique
        'video_exercices_url',    // URL vidéos exercices

        // Métadonnées
        'ordre_affichage',        // Ordre dans listes
        'priorite_medicale',      // Priorité médicale 1-10
        'complexite_traitement',  // Complexité traitement 1-10
        'cout_traitement_moyen',  // Coût moyen traitement
        'specialiste_recommande', // Type spécialiste recommandé
        'derniere_maj_medicale',  // Dernière MAJ données médicales
        'valide_medical',         // Validé par professionnel
        'actif',
    ];

    protected $casts = [
        'synonymes' => 'json',
        'sous_zones' => 'json',
        'zones_adjacentes' => 'json',
        'mouvements_principaux' => 'json',
        'muscles_impliques' => 'json',
        'articulations_liees' => 'json',
        'types_blessures_frequentes' => 'json',
        'facteurs_risque' => 'json',
        'surfaces_risque' => 'json',
        'conditions_risque' => 'json',
        'styles_jeu_risque' => 'json',
        'exercices_prevention' => 'json',
        'echauffement_specifique' => 'json',
        'etirements_recommandes' => 'json',
        'renforcement_cible' => 'json',
        'surveillance_signes' => 'json',
        'traitements_courants' => 'json',
        'complications_possibles' => 'json',
        'retour_jeu_criteres' => 'json',
        'phases_readaptation' => 'json',
        'exercices_readaptation' => 'json',
        'tests_fonctionnels' => 'json',
        'surveillance_post' => 'json',
        'methodes_evaluation' => 'json',
        'examens_recommandes' => 'json',
        'biomarqueurs' => 'json',
        'technologies_suivi' => 'json',
        'capteurs_applicables' => 'json',
        'recherches_actuelles' => 'json',
        'innovations_traitement' => 'json',
        'tendances_prevention' => 'json',
        'references_scientifiques' => 'json',
        'position_anatomique' => 'json',

        // Entiers
        'zone_parent_id' => 'integer',
        'importance_tennis' => 'integer',
        'sollicitation_frequence' => 'integer',
        'impact_performance' => 'integer',
        'complexite_mouvement' => 'integer',
        'impact_service' => 'integer',
        'impact_coup_droit' => 'integer',
        'impact_revers' => 'integer',
        'impact_vollee' => 'integer',
        'impact_smash' => 'integer',
        'impact_deplacement' => 'integer',
        'impact_equilibre' => 'integer',
        'amplitude_mouvement' => 'integer',
        'force_requise' => 'integer',
        'coordination_requise' => 'integer',
        'niveau_risque_tennis' => 'integer',
        'age_maturation' => 'integer',
        'temps_guerison_moyen' => 'integer',
        'age_moyen_blessures' => 'integer',
        'ordre_affichage' => 'integer',
        'priorite_medicale' => 'integer',
        'complexite_traitement' => 'integer',

        // Décimaux
        'frequence_blessures' => 'decimal:2',
        'influence_puissance' => 'decimal:1',
        'influence_precision' => 'decimal:1',
        'influence_endurance' => 'decimal:1',
        'influence_vitesse' => 'decimal:1',
        'cout_traitement_moyen' => 'decimal:2',

        // Booléens
        'sensibilite_croissance' => 'boolean',
        'compensation_possible' => 'boolean',
        'valide_medical' => 'boolean',
        'actif' => 'boolean',

        // Dates
        'derniere_maj_medicale' => 'date',
    ];

    protected $appends = [
        'niveau_criticite',
        'impact_tennis_global',
        'profil_risque_complet',
        'coups_principalement_affectes',
        'strategies_prevention',
        'programme_readaptation_type',
        'facteur_ajustement_ia',
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function blessures()
    {
        return $this->hasMany(Blessure::class, 'zone_corporelle_id');
    }

    public function blessuresActives()
    {
        return $this->hasMany(Blessure::class, 'zone_corporelle_id')
            ->where('est_active', true);
    }

    public function typesBlessures()
    {
        return $this->belongsToMany(TypeBlessure::class, 'blessures')
            ->distinct();
    }

    public function zoneParent()
    {
        return $this->belongsTo(ZoneCorporelle::class, 'zone_parent_id');
    }

    public function sousZones()
    {
        return $this->hasMany(ZoneCorporelle::class, 'zone_parent_id');
    }

    public function joueursConcernes()
    {
        return $this->belongsToMany(Joueur::class, 'blessures')
            ->withPivot(['date_debut', 'date_fin', 'gravite'])
            ->distinct();
    }

    public function statistiquesBlessures()
    {
        return $this->hasMany(StatistiqueBlessure::class, 'zone_corporelle_id');
    }

    // ===================================================================
    // ACCESSORS
    // ===================================================================

    public function getNiveauCriticiteAttribute()
    {
        $score = 0;

        // Importance tennis
        $score += ($this->importance_tennis ?? 0) * 2;

        // Fréquence blessures
        $score += ($this->frequence_blessures ?? 0) / 2;

        // Impact performance
        $score += ($this->impact_performance ?? 0) * 1.5;

        // Temps guérison
        if ($this->temps_guerison_moyen > 90) {
            $score += 10;
        } elseif ($this->temps_guerison_moyen > 30) {
            $score += 5;
        }

        if ($score >= 40) {
            return 'Critique';
        }
        if ($score >= 30) {
            return 'Élevée';
        }
        if ($score >= 20) {
            return 'Modérée';
        }
        if ($score >= 10) {
            return 'Faible';
        }

        return 'Très faible';
    }

    public function getImpactTennisGlobalAttribute()
    {
        $impacts = [
            $this->impact_service ?? 0,
            $this->impact_coup_droit ?? 0,
            $this->impact_revers ?? 0,
            $this->impact_vollee ?? 0,
            $this->impact_smash ?? 0,
            $this->impact_deplacement ?? 0,
        ];

        $moyenne = array_sum($impacts) / count(array_filter($impacts));

        if ($moyenne >= 8) {
            return 'Impact majeur';
        }
        if ($moyenne >= 6) {
            return 'Impact important';
        }
        if ($moyenne >= 4) {
            return 'Impact modéré';
        }
        if ($moyenne >= 2) {
            return 'Impact mineur';
        }

        return 'Impact minimal';
    }

    public function getProfilRisqueCompletAttribute()
    {
        return [
            'niveau_global' => $this->niveau_risque_tennis,
            'frequence_blessures' => $this->frequence_blessures.'%',
            'surfaces_risque' => $this->surfaces_risque ?? [],
            'styles_risque' => $this->styles_jeu_risque ?? [],
            'age_vulnerable' => $this->age_moyen_blessures.' ans',
            'saison_critique' => $this->saison_pic_blessures,
            'temps_guerison' => $this->temps_guerison_moyen.' jours',
        ];
    }

    public function getCoupsPrincipalementAffectesAttribute()
    {
        $coups = [
            'Service' => $this->impact_service ?? 0,
            'Coup droit' => $this->impact_coup_droit ?? 0,
            'Revers' => $this->impact_revers ?? 0,
            'Volée' => $this->impact_vollee ?? 0,
            'Smash' => $this->impact_smash ?? 0,
            'Déplacement' => $this->impact_deplacement ?? 0,
        ];

        // Retourner coups avec impact >= 6
        return array_keys(array_filter($coups, function ($impact) {
            return $impact >= 6;
        }));
    }

    public function getStrategiesPreventionAttribute()
    {
        $strategies = [];

        if ($this->exercices_prevention) {
            $strategies['exercices'] = $this->exercices_prevention;
        }

        if ($this->echauffement_specifique) {
            $strategies['echauffement'] = $this->echauffement_specifique;
        }

        if ($this->etirements_recommandes) {
            $strategies['etirements'] = $this->etirements_recommandes;
        }

        if ($this->materiel_protection) {
            $strategies['materiel'] = $this->materiel_protection;
        }

        return $strategies;
    }

    public function getProgrammeReadaptationTypeAttribute()
    {
        if (! $this->phases_readaptation) {
            return 'Standard';
        }

        $phases = count($this->phases_readaptation);
        $duree = $this->temps_guerison_moyen ?? 30;

        if ($duree > 90 && $phases >= 4) {
            return 'Complexe long terme';
        }
        if ($duree > 60 && $phases >= 3) {
            return 'Intensif moyen terme';
        }
        if ($duree > 30) {
            return 'Standard court terme';
        }

        return 'Rapide';
    }

    public function getFacteurAjustementIaAttribute()
    {
        // Facteur pour les algorithmes IA (-1 à +1)
        $facteur = 0;

        // Plus la zone est critique, plus l'impact est négatif
        $criticite = $this->niveau_criticite;
        switch ($criticite) {
            case 'Critique': $facteur = -0.8;
                break;
            case 'Élevée': $facteur = -0.6;
                break;
            case 'Modérée': $facteur = -0.4;
                break;
            case 'Faible': $facteur = -0.2;
                break;
            default: $facteur = -0.1;
        }

        // Ajustement selon possibilité de compensation
        if ($this->compensation_possible) {
            $facteur *= 0.7; // Réduction impact si compensation possible
        }

        return max(-1, min(0, $facteur));
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

    public function scopeMembreSuperieur($query)
    {
        return $query->where('categorie_principale', 'membre_superieur');
    }

    public function scopeMembreInferieur($query)
    {
        return $query->where('categorie_principale', 'membre_inferieur');
    }

    public function scopeTronc($query)
    {
        return $query->where('categorie_principale', 'tronc');
    }

    public function scopeCritiques($query)
    {
        return $query->where('importance_tennis', '>=', 8)
            ->orWhere('niveau_risque_tennis', '>=', 7);
    }

    public function scopeFrequemmentBlessees($query)
    {
        return $query->where('frequence_blessures', '>=', 10);
    }

    public function scopeGuerisonLente($query)
    {
        return $query->where('temps_guerison_moyen', '>', 60);
    }

    public function scopeAffectantCoups($query, $coup)
    {
        $champ = "impact_{$coup}";

        return $query->where($champ, '>=', 6);
    }

    public function scopeRisqueSurface($query, $surface)
    {
        return $query->whereJsonContains('surfaces_risque', $surface);
    }

    public function scopeZonesPrincipales($query)
    {
        return $query->whereNull('zone_parent_id');
    }

    public function scopeSousZones($query)
    {
        return $query->whereNotNull('zone_parent_id');
    }

    public function scopeOrdonnes($query)
    {
        return $query->orderBy('priorite_medicale', 'desc')
            ->orderBy('importance_tennis', 'desc')
            ->orderBy('ordre_affichage')
            ->orderBy('nom');
    }

    public function scopeRecherche($query, $terme)
    {
        return $query->where(function ($q) use ($terme) {
            $q->where('nom', 'LIKE', "%{$terme}%")
                ->orWhere('nom_medical', 'LIKE', "%{$terme}%")
                ->orWhere('code', 'LIKE', "%{$terme}%")
                ->orWhere('categorie_principale', 'LIKE', "%{$terme}%");
        });
    }

    // ===================================================================
    // STATIC METHODS
    // ===================================================================

    /**
     * Créer les zones corporelles tennis standard
     */
    public static function creerZonesStandard()
    {
        $zones = [
            // MEMBRE SUPÉRIEUR
            [
                'nom' => 'Épaule',
                'nom_medical' => 'Articulation gléno-humérale',
                'code' => 'shoulder',
                'categorie_principale' => 'membre_superieur',
                'sous_categorie' => 'bras',
                'importance_tennis' => 9,
                'sollicitation_frequence' => 10,
                'impact_service' => 10,
                'impact_smash' => 10,
                'impact_coup_droit' => 8,
                'impact_revers' => 6,
                'niveau_risque_tennis' => 8,
                'frequence_blessures' => 15.2,
                'temps_guerison_moyen' => 45,
                'types_blessures_frequentes' => ['tendinite_coiffe', 'inflammation', 'dechirure'],
                'exercices_prevention' => ['rotations_externes', 'renforcement_coiffe', 'etirements_posterieur'],
                'priorite_medicale' => 9,
            ],
            [
                'nom' => 'Coude',
                'nom_medical' => 'Articulation huméro-cubitale',
                'code' => 'elbow',
                'categorie_principale' => 'membre_superieur',
                'sous_categorie' => 'avant_bras',
                'importance_tennis' => 10,
                'sollicitation_frequence' => 10,
                'impact_coup_droit' => 10,
                'impact_revers' => 10,
                'impact_service' => 8,
                'impact_vollee' => 9,
                'niveau_risque_tennis' => 9,
                'frequence_blessures' => 23.5, // Tennis elbow très fréquent
                'temps_guerison_moyen' => 90,
                'types_blessures_frequentes' => ['tennis_elbow', 'epitrochleite', 'bursite'],
                'surfaces_risque' => ['hard'],
                'exercices_prevention' => ['renforcement_extenseurs', 'etirements_avant_bras', 'massage_transverse'],
                'priorite_medicale' => 10,
            ],
            [
                'nom' => 'Poignet',
                'nom_medical' => 'Articulation radio-carpienne',
                'code' => 'wrist',
                'categorie_principale' => 'membre_superieur',
                'sous_categorie' => 'avant_bras',
                'importance_tennis' => 8,
                'sollicitation_frequence' => 9,
                'impact_coup_droit' => 9,
                'impact_revers' => 9,
                'impact_vollee' => 8,
                'impact_service' => 7,
                'niveau_risque_tennis' => 6,
                'frequence_blessures' => 12.3,
                'temps_guerison_moyen' => 30,
                'types_blessures_frequentes' => ['tendinite', 'entorse', 'syndrome_canal_carpien'],
                'exercices_prevention' => ['flexions_extensions', 'rotations', 'renforcement_grip'],
                'priorite_medicale' => 7,
            ],

            // MEMBRE INFÉRIEUR
            [
                'nom' => 'Hanche',
                'nom_medical' => 'Articulation coxo-fémorale',
                'code' => 'hip',
                'categorie_principale' => 'membre_inferieur',
                'sous_categorie' => 'cuisse',
                'importance_tennis' => 7,
                'sollicitation_frequence' => 8,
                'impact_deplacement' => 9,
                'impact_service' => 6,
                'impact_coup_droit' => 7,
                'impact_revers' => 7,
                'niveau_risque_tennis' => 5,
                'frequence_blessures' => 8.7,
                'temps_guerison_moyen' => 35,
                'types_blessures_frequentes' => ['pubalgie', 'tendinite_psoas', 'bursite'],
                'exercices_prevention' => ['renforcement_fessiers', 'etirements_psoas', 'stabilisation'],
                'priorite_medicale' => 6,
            ],
            [
                'nom' => 'Genou',
                'nom_medical' => 'Articulation fémoro-tibiale',
                'code' => 'knee',
                'categorie_principale' => 'membre_inferieur',
                'sous_categorie' => 'jambe',
                'importance_tennis' => 9,
                'sollicitation_frequence' => 9,
                'impact_deplacement' => 10,
                'impact_service' => 6,
                'impact_equilibre' => 8,
                'niveau_risque_tennis' => 7,
                'frequence_blessures' => 14.6,
                'temps_guerison_moyen' => 60,
                'types_blessures_frequentes' => ['syndrome_rotulien', 'entorse_lca', 'tendinite_rotulienne'],
                'surfaces_risque' => ['hard', 'clay'],
                'exercices_prevention' => ['renforcement_quadriceps', 'proprioception', 'etirements_ischio'],
                'priorite_medicale' => 8,
            ],
            [
                'nom' => 'Cheville',
                'nom_medical' => 'Articulation tibio-tarsienne',
                'code' => 'ankle',
                'categorie_principale' => 'membre_inferieur',
                'sous_categorie' => 'pied',
                'importance_tennis' => 8,
                'sollicitation_frequence' => 10,
                'impact_deplacement' => 10,
                'impact_equilibre' => 9,
                'niveau_risque_tennis' => 8,
                'frequence_blessures' => 18.2,
                'temps_guerison_moyen' => 21,
                'types_blessures_frequentes' => ['entorse_externe', 'tendinite_achille', 'impaction'],
                'surfaces_risque' => ['clay', 'grass'],
                'exercices_prevention' => ['proprioception', 'renforcement_peroneaux', 'etirements_triceps'],
                'priorite_medicale' => 8,
            ],
            [
                'nom' => 'Pied',
                'nom_medical' => 'Extrémité distale membre inférieur',
                'code' => 'foot',
                'categorie_principale' => 'membre_inferieur',
                'sous_categorie' => 'pied',
                'importance_tennis' => 7,
                'sollicitation_frequence' => 9,
                'impact_deplacement' => 9,
                'impact_equilibre' => 8,
                'niveau_risque_tennis' => 6,
                'frequence_blessures' => 11.4,
                'temps_guerison_moyen' => 25,
                'types_blessures_frequentes' => ['fasciite_plantaire', 'metatarsalgie', 'nevrome_morton'],
                'surfaces_risque' => ['hard'],
                'exercices_prevention' => ['renforcement_voute', 'etirements_plantaire', 'massage_voute'],
                'priorite_medicale' => 6,
            ],

            // TRONC
            [
                'nom' => 'Dos (lombaires)',
                'nom_medical' => 'Rachis lombaire',
                'code' => 'lower_back',
                'categorie_principale' => 'tronc',
                'sous_categorie' => 'colonne_vertebrale',
                'importance_tennis' => 8,
                'sollicitation_frequence' => 8,
                'impact_service' => 9,
                'impact_coup_droit' => 8,
                'impact_revers' => 8,
                'impact_smash' => 10,
                'niveau_risque_tennis' => 7,
                'frequence_blessures' => 16.8,
                'temps_guerison_moyen' => 35,
                'types_blessures_frequentes' => ['lumbago', 'hernie_discale', 'contracture'],
                'exercices_prevention' => ['gainage', 'etirements_psoas', 'renforcement_profond'],
                'priorite_medicale' => 8,
            ],
            [
                'nom' => 'Abdominaux',
                'nom_medical' => 'Muscles abdominaux',
                'code' => 'abdominals',
                'categorie_principale' => 'tronc',
                'sous_categorie' => 'abdomen',
                'importance_tennis' => 7,
                'sollicitation_frequence' => 7,
                'impact_service' => 8,
                'impact_coup_droit' => 6,
                'impact_revers' => 6,
                'impact_smash' => 7,
                'niveau_risque_tennis' => 4,
                'frequence_blessures' => 6.2,
                'temps_guerison_moyen' => 21,
                'types_blessures_frequentes' => ['elongation', 'contracture', 'dechirure'],
                'exercices_prevention' => ['gainage_variee', 'renforcement_progressif', 'etirements'],
                'priorite_medicale' => 5,
            ],
        ];

        foreach ($zones as $zone) {
            // Valeurs par défaut
            $zone['actif'] = true;
            $zone['valide_medical'] = true;
            $zone['derniere_maj_medicale'] = now();

            self::firstOrCreate(
                ['code' => $zone['code']],
                $zone
            );
        }
    }

    /**
     * Obtenir les zones par catégorie
     */
    public static function parCategorie()
    {
        return self::actifs()
            ->ordonnes()
            ->get()
            ->groupBy('categorie_principale');
    }

    /**
     * Obtenir les statistiques globales
     */
    public static function getStatistiquesGlobales()
    {
        return [
            'nb_zones_total' => self::count(),
            'nb_zones_critiques' => self::critiques()->count(),
            'frequence_moyenne_blessures' => self::avg('frequence_blessures'),
            'temps_guerison_moyen' => self::avg('temps_guerison_moyen'),
            'zones_plus_touchees' => self::orderBy('frequence_blessures', 'desc')
                ->take(5)
                ->pluck('frequence_blessures', 'nom'),
            'repartition_categories' => self::selectRaw('categorie_principale, COUNT(*) as nb')
                ->groupBy('categorie_principale')
                ->pluck('nb', 'categorie_principale'),
        ];
    }

    // ===================================================================
    // METHODS
    // ===================================================================

    /**
     * Obtenir le programme de prévention personnalisé
     */
    public function getProgrammePrevention($joueur = null, $surface = null)
    {
        $programme = [
            'echauffement' => $this->echauffement_specifique ?? [],
            'renforcement' => $this->renforcement_cible ?? [],
            'etirements' => $this->etirements_recommandes ?? [],
            'exercices_prevention' => $this->exercices_prevention ?? [],
        ];

        // Personnalisation selon le joueur
        if ($joueur) {
            // Ajustements selon l'âge
            if ($joueur->age > 30) {
                $programme['recommandations'][] = 'Surveillance accrue (âge > 30)';
                $programme['frequence_prevention'] = 'Quotidienne';
            }

            // Selon historique blessures
            if ($joueur->hasHistoriqueBlessureZone($this->id)) {
                $programme['recommandations'][] = 'Prévention renforcée (antécédents)';
                $programme['intensite'] = 'Élevée';
            }

            // Selon style de jeu
            if ($this->styles_jeu_risque && in_array($joueur->style_jeu_principal, $this->styles_jeu_risque)) {
                $programme['recommandations'][] = 'Adaptation style de jeu recommandée';
            }
        }

        // Personnalisation selon surface
        if ($surface && $this->surfaces_risque && in_array($surface, $this->surfaces_risque)) {
            $programme['recommandations'][] = "Attention particulière sur {$surface}";
            $programme['intensite_surface'] = 'Renforcée';
        }

        return $programme;
    }

    /**
     * Évaluer le risque pour un joueur donné
     */
    public function evaluerRisque($joueur, $conditions = [])
    {
        $risqueBase = $this->niveau_risque_tennis;
        $facteurs = [];

        // Facteurs joueur
        if ($joueur->age > 30) {
            $risqueBase += 1;
            $facteurs[] = 'Âge > 30 ans';
        }

        if ($joueur->hasHistoriqueBlessureZone($this->id)) {
            $risqueBase += 2;
            $facteurs[] = 'Antécédents blessures';
        }

        // Style de jeu
        if ($this->styles_jeu_risque && in_array($joueur->style_jeu_principal, $this->styles_jeu_risque)) {
            $risqueBase += 1;
            $facteurs[] = 'Style de jeu à risque';
        }

        // Conditions externes
        if (isset($conditions['surface']) && $this->surfaces_risque) {
            if (in_array($conditions['surface'], $this->surfaces_risque)) {
                $risqueBase += 1;
                $facteurs[] = 'Surface à risque';
            }
        }

        if (isset($conditions['fatigue']) && $conditions['fatigue'] > 7) {
            $risqueBase += 1;
            $facteurs[] = 'Fatigue élevée';
        }

        return [
            'risque_global' => min(10, $risqueBase),
            'niveau' => $this->getNiveauRisque(min(10, $risqueBase)),
            'facteurs_contributeurs' => $facteurs,
            'recommandations' => $this->getRecommandationsRisque($risqueBase),
        ];
    }

    /**
     * Obtenir le plan de réadaptation complet
     */
    public function getPlanReadaptation($typeBlessure = null, $gravite = 5)
    {
        $plan = [
            'duree_estimee' => $this->temps_guerison_moyen,
            'phases' => $this->phases_readaptation ?? [],
            'exercices' => $this->exercices_readaptation ?? [],
            'tests_controle' => $this->tests_fonctionnels ?? [],
            'criteres_retour' => $this->retour_jeu_criteres ?? [],
        ];

        // Ajustement selon gravité
        if ($gravite >= 8) {
            $plan['duree_estimee'] *= 1.5;
            $plan['surveillance'] = 'Renforcée';
        } elseif ($gravite <= 3) {
            $plan['duree_estimee'] *= 0.7;
            $plan['surveillance'] = 'Standard';
        }

        // Ajustement selon type blessure
        if ($typeBlessure) {
            $plan['recommandations_specifiques'] = "Adaptation pour {$typeBlessure}";
        }

        return $plan;
    }

    /**
     * Analyser l'impact sur les performances tennis
     */
    public function analyserImpactPerformance($gravite = 5)
    {
        $impacts = [
            'service' => $this->calculerImpactCoups('service', $gravite),
            'coup_droit' => $this->calculerImpactCoups('coup_droit', $gravite),
            'revers' => $this->calculerImpactCoups('revers', $gravite),
            'vollee' => $this->calculerImpactCoups('vollee', $gravite),
            'smash' => $this->calculerImpactCoups('smash', $gravite),
            'deplacement' => $this->calculerImpactCoups('deplacement', $gravite),
        ];

        // Impact global
        $impactGlobal = array_sum($impacts) / count($impacts);

        return [
            'impact_global' => round($impactGlobal, 1),
            'niveau_impact' => $this->getNiveauImpact($impactGlobal),
            'coups_affectes' => $impacts,
            'coups_plus_touches' => $this->getCoupsPlusAffectes($impacts),
            'adaptations_recommandees' => $this->getAdaptationsRecommandees($impacts),
            'facteur_ia' => $this->facteur_ajustement_ia * ($gravite / 10),
        ];
    }

    /**
     * Générer le rapport complet de la zone
     */
    public function genererRapportComplet()
    {
        return [
            'identification' => [
                'nom' => $this->nom,
                'nom_medical' => $this->nom_medical,
                'categorie' => $this->categorie_principale,
                'importance_tennis' => $this->importance_tennis.'/10',
            ],
            'profil_risque' => $this->profil_risque_complet,
            'impact_tennis' => [
                'global' => $this->impact_tennis_global,
                'coups_affectes' => $this->coups_principalement_affectes,
                'niveau_criticite' => $this->niveau_criticite,
            ],
            'prevention' => $this->strategies_prevention,
            'readaptation' => [
                'type_programme' => $this->programme_readaptation_type,
                'duree_moyenne' => $this->temps_guerison_moyen.' jours',
            ],
            'donnees_epidemiologiques' => [
                'frequence' => $this->frequence_blessures.'%',
                'age_moyen' => $this->age_moyen_blessures.' ans',
                'sexe_plus_touche' => $this->sexe_plus_touche,
            ],
            'facteur_ia' => $this->facteur_ajustement_ia,
        ];
    }

    // ===================================================================
    // METHODS PRIVÉES
    // ===================================================================

    private function getNiveauRisque($score)
    {
        if ($score >= 8) {
            return 'Très élevé';
        }
        if ($score >= 6) {
            return 'Élevé';
        }
        if ($score >= 4) {
            return 'Modéré';
        }
        if ($score >= 2) {
            return 'Faible';
        }

        return 'Très faible';
    }

    private function getRecommandationsRisque($risque)
    {
        $recommandations = [];

        if ($risque >= 7) {
            $recommandations[] = 'Surveillance médicale renforcée';
            $recommandations[] = 'Prévention quotidienne obligatoire';
        }

        if ($risque >= 5) {
            $recommandations[] = 'Échauffement prolongé';
            $recommandations[] = 'Adaptation charge d\'entraînement';
        }

        return $recommandations;
    }

    private function calculerImpactCoups($coup, $gravite)
    {
        $champImpact = "impact_{$coup}";
        $impactBase = $this->$champImpact ?? 0;

        // Ajustement selon gravité blessure
        return min(10, ($impactBase * $gravite) / 10);
    }

    private function getNiveauImpact($score)
    {
        if ($score >= 8) {
            return 'Majeur';
        }
        if ($score >= 6) {
            return 'Important';
        }
        if ($score >= 4) {
            return 'Modéré';
        }
        if ($score >= 2) {
            return 'Mineur';
        }

        return 'Minimal';
    }

    private function getCoupsPlusAffectes($impacts)
    {
        return array_keys(array_filter($impacts, function ($impact) {
            return $impact >= 6;
        }));
    }

    private function getAdaptationsRecommandees($impacts)
    {
        $adaptations = [];

        foreach ($impacts as $coup => $impact) {
            if ($impact >= 7) {
                $adaptations[] = "Éviter {$coup} temporairement";
            } elseif ($impact >= 5) {
                $adaptations[] = "Réduire intensité {$coup}";
            }
        }

        return $adaptations;
    }

    // ===================================================================
    // VALIDATION RULES
    // ===================================================================

    public static function validationRules()
    {
        return [
            'nom' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:zone_corporelles,code',
            'categorie_principale' => 'required|in:membre_superieur,membre_inferieur,tronc,tete',
            'importance_tennis' => 'required|integer|min:1|max:10',
            'niveau_risque_tennis' => 'required|integer|min:1|max:10',
            'temps_guerison_moyen' => 'nullable|integer|min:1|max:365',
            'frequence_blessures' => 'nullable|numeric|min:0|max:100',
        ];
    }

    // ===================================================================
    // BOOT METHODS
    // ===================================================================

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($zone) {
            // Auto-calcul priorité médicale
            $zone->priorite_medicale = round(
                (($zone->importance_tennis ?? 5) +
                    ($zone->niveau_risque_tennis ?? 5) +
                    (($zone->frequence_blessures ?? 0) / 10)) / 3
            );

            // Ordre d'affichage par défaut
            if (! $zone->ordre_affichage) {
                $zone->ordre_affichage = $zone->priorite_medicale;
            }

            // Valeurs par défaut
            if ($zone->actif === null) {
                $zone->actif = true;
            }
            if ($zone->compensation_possible === null) {
                // Membres supérieurs généralement plus compensables
                $zone->compensation_possible = $zone->categorie_principale === 'membre_superieur';
            }
        });
    }
}
