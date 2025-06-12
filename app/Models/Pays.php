<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pays extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pays';

    protected $fillable = [
        // Identification de base
        'nom',
        'nom_anglais',
        'code',                     // FR, US, ES, etc.
        'code_iso3',                // FRA, USA, ESP, etc.
        'code_atp_wta',             // Code officiel ATP/WTA
        'region_tennis',            // 'Europe', 'Amerique_Nord', 'Asie', etc.
        'sous_region',              // 'Europe_Sud', 'Amerique_Centrale', etc.

        // Données géographiques et climatiques
        'latitude',
        'longitude',
        'altitude_moyenne',         // m au-dessus niveau mer
        'fuseau_horaire',           // UTC offset
        'hemisphere',               // 'Nord', 'Sud'
        'climat_dominant',          // 'tempere', 'tropical', 'aride', etc.
        'temperature_moyenne_ete',  // °C
        'temperature_moyenne_hiver', // °C
        'humidite_moyenne',         // %
        'precipitation_annuelle',   // mm
        'mois_saison_seche',        // JSON: mois favorables tennis
        'mois_saison_pluies',       // JSON: mois défavorables

        // Culture et tradition tennis
        'niveau_culture_tennis',    // 0-100 : popularité tennis
        'surface_preference',       // Surface historiquement dominante
        'tradition_tennis_score',   // 0-100 : ancienneté tradition tennis
        'grands_champions_nb',      // Nombre de légendes produites
        'style_jeu_national',       // 'offensif', 'defensif', 'varie', 'tactique'
        'heritage_tennis',          // JSON: champions emblématiques
        'rivalites_historiques',    // JSON: pays rivaux tennistiquement

        // Infrastructure et développement tennis
        'nb_courts_total',          // Nombre total de courts
        'nb_courts_professionnels', // Courts niveau professionnel
        'nb_clubs_tennis',          // Clubs affiliés fédération
        'nb_academies_elite',       // Académies haut niveau
        'qualite_infrastructure',   // 0-100 : qualité infrastructures
        'accessibilite_tennis',     // 0-100 : facilité accès au tennis
        'cout_pratique_tennis',     // 0-100 : coût relatif (0=gratuit, 100=très cher)

        // Système de formation et détection
        'systeme_formation_score',  // 0-100 : qualité système formation
        'age_detection_talents',    // Âge moyen détection talents
        'nb_entraineurs_certifies', // Entraîneurs certifiés fédération
        'centres_entrainement_nb',  // Centres d'entraînement nationaux
        'programme_jeunes_score',   // 0-100 : qualité programmes jeunes
        'bourse_tennis_disponible', // Si bourses pour jeunes talents
        'echange_international',    // 0-100 : facilité échanges internationaux

        // Performance et résultats historiques
        'nb_joueurs_top_100_actuel',// Joueurs actuellement top 100
        'nb_joueurs_top_100_historique', // Maximum historique top 100
        'nb_titres_grand_chelem',   // Total titres GC tous joueurs
        'nb_titres_masters',        // Total titres Masters/WTA 1000
        'nb_finales_coupe_davis',   // Finales Coupe Davis
        'nb_finales_fed_cup',       // Finales Fed Cup/BJK Cup
        'classement_par_equipe',    // Rang équipe nationale actuel
        'evolution_5_ans',          // 'progression', 'stable', 'declin'

        // Facteurs économiques et support
        'pib_par_habitant',         // USD
        'investissement_tennis',    // Million USD/an dans tennis
        'soutien_etatique_score',   // 0-100 : soutien gouvernemental
        'sponsor_prive_score',      // 0-100 : soutien sponsors privés
        'prime_performances',       // USD pour victoires importantes
        'budget_federation',        // Million USD budget fédération
        'professionalisation_score', // 0-100 : niveau professionnalisation

        // Médias et popularité
        'couverture_media_score',   // 0-100 : couverture médiatique tennis
        'audience_tv_tennis',       // % population regardant tennis
        'presence_reseaux_sociaux', // 0-100 : engagement réseaux sociaux
        'celebrite_joueurs_score',  // 0-100 : célébrité joueurs nationaux
        'support_public_matches',   // 0-100 : support dans stades
        'passion_fans_score',       // 0-100 : intensité passion fans

        // Avantages/désavantages compétitifs
        'avantage_domicile_score',  // 0-100 : avantage à domicile
        'adaptation_voyages',       // 0-100 : facilité adaptation voyages
        'gestion_pression_score',   // 0-100 : gestion pression médiatique
        'mental_competition',       // 0-100 : force mentale traditionnelle
        'preparation_physique',     // 0-100 : qualité préparation physique
        'innovation_technique',     // 0-100 : innovation dans technique/tactique
        'science_sport_score',      // 0-100 : utilisation sciences du sport

        // Spécificités par surface (crucial pour prédictions)
        'performance_terre_battue', // 0-100 : performance historique terre
        'performance_dur',          // 0-100 : performance historique dur
        'performance_gazon',        // 0-100 : performance historique gazon
        'performance_indoor',       // 0-100 : performance historique indoor
        'adaptation_surface',       // 0-100 : capacité adaptation surfaces
        'polyvalence_joueurs',      // 0-100 : polyvalence moyenne joueurs

        // Facteurs génétiques et anthropométriques
        'taille_moyenne_joueurs',   // cm moyenne joueurs professionnels
        'poids_moyen_joueurs',      // kg moyenne joueurs professionnels
        'envergure_moyenne',        // cm envergure moyenne
        'vitesse_deplacement',      // 0-100 : vitesse déplacement moyenne
        'puissance_naturelle',      // 0-100 : puissance naturelle moyenne
        'endurance_genetique',      // 0-100 : endurance naturelle
        'coordination_motrice',     // 0-100 : coordination moyenne

        // Voyages et logistique
        'accessibilite_aeroports',  // 0-100 : facilité accès transport aérien
        'qualite_transport_interne',// 0-100 : qualité transports internes
        'decalage_horaire_impact',  // 0-100 : impact moyen décalages horaires
        'distance_moyenne_tournois',// km distance moyenne vers tournois
        'facilite_visa',            // 0-100 : facilité obtention visas
        'cout_voyages_moyen',       // USD coût moyen voyages/an
        'adaptation_climat',        // 0-100 : capacité adaptation climats

        // Innovation et technologie
        'utilisation_technologie',  // 0-100 : adoption technologies tennis
        'recherche_sport_score',    // 0-100 : recherche en sciences du sport
        'data_analytics_usage',     // 0-100 : utilisation analytics
        'equipement_innovation',    // 0-100 : innovation équipements
        'medical_sport_score',      // 0-100 : médecine du sport
        'nutrition_sport_score',    // 0-100 : nutrition sportive
        'psychologie_sport_score',  // 0-100 : psychologie du sport

        // Facteurs sociétaux et culturels
        'egalite_genre_tennis',     // 0-100 : égalité hommes/femmes tennis
        'diversite_sociale_tennis', // 0-100 : diversité sociale dans tennis
        'education_priorite',       // 0-100 : priorité éducation vs sport
        'equilibre_vie_sport',      // 0-100 : équilibre vie personnelle/sport
        'pression_sociale_reussite',// 0-100 : pression sociale réussite
        'acceptation_echec',        // 0-100 : acceptation échecs temporaires
        'culture_travail_dur',      // 0-100 : culture effort et persévérance

        // Rivalités et géopolitique sportive
        'rivalites_actuelles',      // JSON: pays rivaux actuels
        'alliances_sportives',      // JSON: pays alliés/partenaires
        'echanges_entraineurs',     // 0-100 : facilité échanges entraîneurs
        'cooperation_internationale',// 0-100 : coopération tennis internationale
        'soft_power_tennis',        // 0-100 : influence via tennis
        'diplomatie_sportive',      // 0-100 : utilisation tennis diplomatique

        // Défis et opportunités
        'defis_principaux',         // JSON: défis majeurs tennis national
        'opportunites_croissance',  // JSON: opportunités développement
        'menaces_externes',         // JSON: menaces pour tennis national
        'strategies_developpement', // JSON: stratégies développement
        'objectifs_5_ans',          // JSON: objectifs à 5 ans
        'investissements_prevus',   // Million USD investissements prévus

        // Métadonnées et tracking
        'derniere_mise_a_jour',
        'source_donnees_principale',// Source primaire des données
        'fiabilite_donnees',        // 0-100 : fiabilité des données
        'completude_profil',        // 0-100 : complétude du profil
        'derniere_analyse_experte', // Date dernière analyse par expert
        'tendance_globale',         // 'progression', 'stabilite', 'declin'
        'potentiel_futur',          // 0-100 : potentiel développement futur
    ];

    protected $casts = [
        // Coordonnées géographiques
        'latitude' => 'decimal:6',
        'longitude' => 'decimal:6',
        'altitude_moyenne' => 'integer',
        'temperature_moyenne_ete' => 'decimal:1',
        'temperature_moyenne_hiver' => 'decimal:1',
        'humidite_moyenne' => 'decimal:1',
        'precipitation_annuelle' => 'integer',

        // Scores et niveaux (0-100)
        'niveau_culture_tennis' => 'decimal:1',
        'tradition_tennis_score' => 'decimal:1',
        'qualite_infrastructure' => 'decimal:1',
        'accessibilite_tennis' => 'decimal:1',
        'cout_pratique_tennis' => 'decimal:1',
        'systeme_formation_score' => 'decimal:1',
        'programme_jeunes_score' => 'decimal:1',
        'echange_international' => 'decimal:1',
        'soutien_etatique_score' => 'decimal:1',
        'sponsor_prive_score' => 'decimal:1',
        'professionalisation_score' => 'decimal:1',
        'couverture_media_score' => 'decimal:1',
        'audience_tv_tennis' => 'decimal:1',
        'presence_reseaux_sociaux' => 'decimal:1',
        'celebrite_joueurs_score' => 'decimal:1',
        'support_public_matches' => 'decimal:1',
        'passion_fans_score' => 'decimal:1',
        'avantage_domicile_score' => 'decimal:1',
        'adaptation_voyages' => 'decimal:1',
        'gestion_pression_score' => 'decimal:1',
        'mental_competition' => 'decimal:1',
        'preparation_physique' => 'decimal:1',
        'innovation_technique' => 'decimal:1',
        'science_sport_score' => 'decimal:1',
        'performance_terre_battue' => 'decimal:1',
        'performance_dur' => 'decimal:1',
        'performance_gazon' => 'decimal:1',
        'performance_indoor' => 'decimal:1',
        'adaptation_surface' => 'decimal:1',
        'polyvalence_joueurs' => 'decimal:1',
        'vitesse_deplacement' => 'decimal:1',
        'puissance_naturelle' => 'decimal:1',
        'endurance_genetique' => 'decimal:1',
        'coordination_motrice' => 'decimal:1',
        'accessibilite_aeroports' => 'decimal:1',
        'qualite_transport_interne' => 'decimal:1',
        'decalage_horaire_impact' => 'decimal:1',
        'facilite_visa' => 'decimal:1',
        'adaptation_climat' => 'decimal:1',
        'utilisation_technologie' => 'decimal:1',
        'recherche_sport_score' => 'decimal:1',
        'data_analytics_usage' => 'decimal:1',
        'equipement_innovation' => 'decimal:1',
        'medical_sport_score' => 'decimal:1',
        'nutrition_sport_score' => 'decimal:1',
        'psychologie_sport_score' => 'decimal:1',
        'egalite_genre_tennis' => 'decimal:1',
        'diversite_sociale_tennis' => 'decimal:1',
        'education_priorite' => 'decimal:1',
        'equilibre_vie_sport' => 'decimal:1',
        'pression_sociale_reussite' => 'decimal:1',
        'acceptation_echec' => 'decimal:1',
        'culture_travail_dur' => 'decimal:1',
        'echanges_entraineurs' => 'decimal:1',
        'cooperation_internationale' => 'decimal:1',
        'soft_power_tennis' => 'decimal:1',
        'diplomatie_sportive' => 'decimal:1',
        'fiabilite_donnees' => 'decimal:1',
        'completude_profil' => 'decimal:1',
        'potentiel_futur' => 'decimal:1',

        // Entiers
        'nb_courts_total' => 'integer',
        'nb_courts_professionnels' => 'integer',
        'nb_clubs_tennis' => 'integer',
        'nb_academies_elite' => 'integer',
        'age_detection_talents' => 'integer',
        'nb_entraineurs_certifies' => 'integer',
        'centres_entrainement_nb' => 'integer',
        'nb_joueurs_top_100_actuel' => 'integer',
        'nb_joueurs_top_100_historique' => 'integer',
        'nb_titres_grand_chelem' => 'integer',
        'nb_titres_masters' => 'integer',
        'nb_finales_coupe_davis' => 'integer',
        'nb_finales_fed_cup' => 'integer',
        'classement_par_equipe' => 'integer',
        'grands_champions_nb' => 'integer',
        'fuseau_horaire' => 'integer',
        'distance_moyenne_tournois' => 'integer',
        'taille_moyenne_joueurs' => 'integer',
        'poids_moyen_joueurs' => 'integer',
        'envergure_moyenne' => 'integer',

        // Decimaux financiers
        'pib_par_habitant' => 'decimal:2',
        'investissement_tennis' => 'decimal:2',
        'prime_performances' => 'decimal:2',
        'budget_federation' => 'decimal:2',
        'cout_voyages_moyen' => 'decimal:2',
        'investissements_prevus' => 'decimal:2',

        // JSON arrays
        'mois_saison_seche' => 'array',
        'mois_saison_pluies' => 'array',
        'heritage_tennis' => 'array',
        'rivalites_historiques' => 'array',
        'rivalites_actuelles' => 'array',
        'alliances_sportives' => 'array',
        'defis_principaux' => 'array',
        'opportunites_croissance' => 'array',
        'menaces_externes' => 'array',
        'strategies_developpement' => 'array',
        'objectifs_5_ans' => 'array',

        // Booleans
        'bourse_tennis_disponible' => 'boolean',

        // Dates
        'derniere_mise_a_jour' => 'datetime',
        'derniere_analyse_experte' => 'date'
    ];

    protected $appends = [
        'force_tennis_globale',
        'potentiel_competitive',
        'avantage_geographique',
        'qualite_ecosystem_tennis',
        'influence_internationale',
        'niveau_professionnalisme',
        'capacite_adaptation',
        'facteur_domicile'
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function joueurs()
    {
        return $this->hasMany(Joueur::class);
    }

    public function tournois()
    {
        return $this->hasMany(Tournoi::class);
    }

    public function joueursActuelsTop100()
    {
        return $this->hasMany(Joueur::class)
            ->where('classement_atp_wta', '<=', 100)
            ->whereNotNull('classement_atp_wta');
    }

    public function championsTitres()
    {
        return $this->hasMany(Joueur::class)
            ->whereHas('palmares', function($query) {
                $query->where('niveau_tournoi', 'grand_chelem');
            });
    }

    public function centresFormation()
    {
        return $this->hasMany(CentreFormation::class);
    }

    public function academiesTennis()
    {
        return $this->hasMany(AcademieTennis::class);
    }

    // Relations géographiques
    public function paysVoisins()
    {
        return $this->belongsToMany(Pays::class, 'pays_voisins', 'pays_id', 'pays_voisin_id');
    }

    public function region()
    {
        return $this->belongsTo(RegionTennis::class, 'region_tennis');
    }

    // Relations de voyage (pour calcul décalages horaires, distances)
    public function distancesVers()
    {
        return $this->hasMany(DistancePays::class, 'pays_origine_id');
    }

    // ===================================================================
    // ACCESSORS
    // ===================================================================

    public function getForceTennisGlobaleAttribute()
    {
        $score = 0;
        $facteurs = 0;

        // Performance actuelle (30%)
        if ($this->nb_joueurs_top_100_actuel !== null) {
            $score += min(30, $this->nb_joueurs_top_100_actuel * 3);
            $facteurs += 30;
        }

        // Infrastructure et formation (25%)
        if ($this->systeme_formation_score !== null) {
            $score += $this->systeme_formation_score * 0.25;
            $facteurs += 25;
        }

        // Tradition et culture (20%)
        if ($this->tradition_tennis_score !== null) {
            $score += $this->tradition_tennis_score * 0.20;
            $facteurs += 20;
        }

        // Support économique (15%)
        if ($this->soutien_etatique_score !== null && $this->sponsor_prive_score !== null) {
            $scoreEconomique = ($this->soutien_etatique_score + $this->sponsor_prive_score) / 2;
            $score += $scoreEconomique * 0.15;
            $facteurs += 15;
        }

        // Innovation et professionnalisme (10%)
        if ($this->professionalisation_score !== null) {
            $score += $this->professionalisation_score * 0.10;
            $facteurs += 10;
        }

        return $facteurs > 0 ? round($score / $facteurs * 100, 1) : 50;
    }

    public function getPotentielCompetitiveAttribute()
    {
        $potentiel = 0;

        // Potentiel démographique
        $potentiel += min(20, $this->nb_joueurs_top_100_actuel * 4);

        // Qualité formation
        $potentiel += $this->systeme_formation_score * 0.3;

        // Infrastructure
        $potentiel += $this->qualite_infrastructure * 0.2;

        // Support économique
        $potentiel += ($this->soutien_etatique_score + $this->sponsor_prive_score) / 2 * 0.15;

        // Innovation
        $potentiel += $this->innovation_technique * 0.1;

        // Évolution récente
        if ($this->evolution_5_ans === 'progression') $potentiel += 15;
        elseif ($this->evolution_5_ans === 'stable') $potentiel += 5;

        return round(min(100, $potentiel), 1);
    }

    public function getAvantageGeographiqueAttribute()
    {
        $avantage = 50; // Base neutre

        // Climat favorable
        if ($this->climat_dominant === 'tempere') $avantage += 10;
        elseif ($this->climat_dominant === 'mediterraneen') $avantage += 15;

        // Adaptation surface préférée
        $surfaces = ['terre_battue', 'dur', 'gazon'];
        foreach ($surfaces as $surface) {
            if ($this->surface_preference === $surface) {
                $performance = $this->{"performance_{$surface}"};
                if ($performance > 70) $avantage += 10;
            }
        }

        // Facilité voyages
        $avantage += ($this->adaptation_voyages - 50) * 0.3;

        // Décalage horaire
        $avantage -= $this->decalage_horaire_impact * 0.2;

        return round(max(0, min(100, $avantage)), 1);
    }

    public function getQualiteEcosystemTennisAttribute()
    {
        return round((
            $this->qualite_infrastructure * 0.25 +
            $this->systeme_formation_score * 0.25 +
            $this->accessibilite_tennis * 0.2 +
            $this->professionalisation_score * 0.15 +
            $this->innovation_technique * 0.15
        ), 1);
    }

    public function getInfluenceInternationaleAttribute()
    {
        return round((
            $this->soft_power_tennis * 0.3 +
            $this->cooperation_internationale * 0.25 +
            $this->diplomatie_sportive * 0.2 +
            ($this->nb_titres_grand_chelem / 10) * 0.25 // Normalisation titres GC
        ), 1);
    }

    public function getNiveauProfessionnalismeAttribute()
    {
        return round((
            $this->professionalisation_score * 0.4 +
            $this->utilisation_technologie * 0.2 +
            $this->science_sport_score * 0.2 +
            $this->data_analytics_usage * 0.2
        ), 1);
    }

    public function getCapaciteAdaptationAttribute()
    {
        return round((
            $this->adaptation_surface * 0.3 +
            $this->adaptation_voyages * 0.25 +
            $this->adaptation_climat * 0.25 +
            $this->polyvalence_joueurs * 0.2
        ), 1);
    }

    public function getFacteurDomicileAttribute()
    {
        return round((
            $this->avantage_domicile_score * 0.4 +
            $this->support_public_matches * 0.3 +
            $this->passion_fans_score * 0.2 +
            $this->couverture_media_score * 0.1
        ), 1);
    }

    // ===================================================================
    // SCOPES
    // ===================================================================

    public function scopePuissancesTennis($query, $seuilForce = 70)
    {
        return $query->whereRaw("
            (nb_joueurs_top_100_actuel * 3 + systeme_formation_score * 0.5 + tradition_tennis_score * 0.3) >= ?
        ", [$seuilForce]);
    }

    public function scopeRegionTennis($query, $region)
    {
        return $query->where('region_tennis', $region);
    }

    public function scopeAvecJoueursTop100($query)
    {
        return $query->where('nb_joueurs_top_100_actuel', '>', 0);
    }

    public function scopeSpecialistesSurface($query, $surface)
    {
        $colonne = "performance_{$surface}";
        return $query->where($colonne, '>=', 70);
    }

    public function scopeEnProgression($query)
    {
        return $query->where('evolution_5_ans', 'progression');
    }

    public function scopeInfrastructuresSolides($query, $seuil = 70)
    {
        return $query->where('qualite_infrastructure', '>=', $seuil);
    }

    public function scopePaysRivaux($query, $paysId)
    {
        return $query->whereRaw("JSON_CONTAINS(rivalites_actuelles, ?)", ["\"{$paysId}\""]);
    }

    public function scopeAllies($query, $paysId)
    {
        return $query->whereRaw("JSON_CONTAINS(alliances_sportives, ?)", ["\"{$paysId}\""]);
    }

    public function scopeDecalageHoraireProche($query, $fuseauCible, $tolerance = 3)
    {
        return $query->whereBetween('fuseau_horaire', [
            $fuseauCible - $tolerance,
            $fuseauCible + $tolerance
        ]);
    }

    // ===================================================================
    // METHODS TENNIS AI GEOPOLITICS
    // ===================================================================

    /**
     * Analyser l'avantage/désavantage pour un match spécifique
     */
    public function analyserAvantageMatch(MatchTennis $match, $paysAdversaire)
    {
        $avantages = [];

        // 1. Avantage domicile
        if ($match->pays_id === $this->id) {
            $avantages['domicile'] = [
                'type' => 'avantage',
                'valeur' => $this->facteur_domicile,
                'description' => 'Match à domicile'
            ];
        }

        // 2. Avantage surface
        $surface = $match->surface?->code;
        if ($surface) {
            $perfSurface = $this->{"performance_{$surface}"};
            $perfAdversaire = $paysAdversaire->{"performance_{$surface}"};

            if ($perfSurface > $perfAdversaire + 10) {
                $avantages['surface'] = [
                    'type' => 'avantage',
                    'valeur' => $perfSurface - $perfAdversaire,
                    'description' => "Spécialiste {$surface}"
                ];
            }
        }

        // 3. Facteur décalage horaire
        $decalage = abs($this->fuseau_horaire - $match->fuseau_horaire_local);
        if ($decalage <= 2) {
            $avantages['decalage'] = [
                'type' => 'avantage',
                'valeur' => 10 - $decalage * 3,
                'description' => 'Décalage horaire favorable'
            ];
        }

        // 4. Climat similaire
        if ($this->adaptation_climat > 75) {
            $avantages['climat'] = [
                'type' => 'avantage',
                'valeur' => $this->adaptation_climat - 50,
                'description' => 'Bonne adaptation climatique'
            ];
        }

        // 5. Style de jeu favorable
        $styleAdversaire = $paysAdversaire->style_jeu_national;
        $avantageStyle = $this->calculerAvantageStyle($this->style_jeu_national, $styleAdversaire);
        if (abs($avantageStyle) > 5) {
            $avantages['style'] = [
                'type' => $avantageStyle > 0 ? 'avantage' : 'desavantage',
                'valeur' => abs($avantageStyle),
                'description' => "Style {$this->style_jeu_national} vs {$styleAdversaire}"
            ];
        }

        return $avantages;
    }

    /**
     * Prédire l'évolution du tennis national
     */
    public function predireEvolutionTennis($annees = 5)
    {
        $prediction = [
            'tendance_actuelle' => $this->evolution_5_ans,
            'forces' => $this->identifierForces(),
            'faiblesses' => $this->identifierFaiblesses(),
            'opportunites' => $this->opportunites_croissance,
            'menaces' => $this->menaces_externes
        ];

        // Prédiction nombre joueurs top 100
        $croissanceJoueurs = $this->calculerCroissanceJoueurs();
        $prediction['joueurs_top_100_prevus'] = max(0, round(
            $this->nb_joueurs_top_100_actuel + ($croissanceJoueurs * $annees)
        ));

        // Prédiction force tennis globale
        $evolutionForce = $this->calculerEvolutionForce();
        $prediction['force_tennis_prevue'] = max(0, min(100, round(
            $this->force_tennis_globale + ($evolutionForce * $annees)
        )));

        // Prédiction classement par équipe
        $prediction['classement_equipe_prevu'] = $this->predireClassementEquipe($annees);

        // Facteurs d'incertitude
        $prediction['niveau_confiance'] = $this->calculerConfiancePrediction();

        return $prediction;
    }

    /**
     * Comparer avec un autre pays
     */
    public function comparerAvec(Pays $autrePays)
    {
        return [
            'force_globale' => [
                'pays' => $this->force_tennis_globale,
                'autre' => $autrePays->force_tennis_globale,
                'avantage' => $this->force_tennis_globale > $autrePays->force_tennis_globale ? 'pays' : 'autre',
                'difference' => round($this->force_tennis_globale - $autrePays->force_tennis_globale, 1)
            ],
            'infrastructure' => [
                'pays' => $this->qualite_infrastructure,
                'autre' => $autrePays->qualite_infrastructure,
                'avantage' => $this->qualite_infrastructure > $autrePays->qualite_infrastructure ? 'pays' : 'autre'
            ],
            'tradition' => [
                'pays' => $this->tradition_tennis_score,
                'autre' => $autrePays->tradition_tennis_score,
                'avantage' => $this->tradition_tennis_score > $autrePays->tradition_tennis_score ? 'pays' : 'autre'
            ],
            'joueurs_actuels' => [
                'pays' => $this->nb_joueurs_top_100_actuel,
                'autre' => $autrePays->nb_joueurs_top_100_actuel,
                'avantage' => $this->nb_joueurs_top_100_actuel > $autrePays->nb_joueurs_top_100_actuel ? 'pays' : 'autre'
            ],
            'surfaces' => [
                'terre_battue' => [
                    'pays' => $this->performance_terre_battue,
                    'autre' => $autrePays->performance_terre_battue,
                    'avantage' => $this->performance_terre_battue > $autrePays->performance_terre_battue ? 'pays' : 'autre'
                ],
                'dur' => [
                    'pays' => $this->performance_dur,
                    'autre' => $autrePays->performance_dur,
                    'avantage' => $this->performance_dur > $autrePays->performance_dur ? 'pays' : 'autre'
                ],
                'gazon' => [
                    'pays' => $this->performance_gazon,
                    'autre' => $autrePays->performance_gazon,
                    'avantage' => $this->performance_gazon > $autrePays->performance_gazon ? 'pays' : 'autre'
                ]
            ],
            'potentiel_futur' => [
                'pays' => $this->potentiel_futur,
                'autre' => $autrePays->potentiel_futur,
                'avantage' => $this->potentiel_futur > $autrePays->potentiel_futur ? 'pays' : 'autre'
            ]
        ];
    }

    /**
     * Calculer l'impact du décalage horaire pour un voyage
     */
    public function calculerImpactDecalage($paysCible)
    {
        $decalage = abs($this->fuseau_horaire - $paysCible->fuseau_horaire);

        $impact = [
            'decalage_heures' => $decalage,
            'direction' => $this->fuseau_horaire < $paysCible->fuseau_horaire ? 'est' : 'ouest',
            'severite' => $this->determinerSeveriteDecalage($decalage),
            'jours_adaptation' => $this->calculerJoursAdaptation($decalage),
            'impact_performance' => $this->calculerImpactPerformance($decalage),
            'recommandations' => $this->genererRecommandationsVoyage($decalage)
        ];

        return $impact;
    }

    /**
     * Identifier les rivaux tennistiques
     */
    public function identifierRivaux()
    {
        $rivaux = [];

        // Rivaux historiques configurés
        if ($this->rivalites_historiques) {
            foreach ($this->rivalites_historiques as $rivalId) {
                $rival = Pays::find($rivalId);
                if ($rival) {
                    $rivaux[] = [
                        'pays' => $rival,
                        'type' => 'historique',
                        'intensite' => $this->calculerIntensiteRivalite($rival)
                    ];
                }
            }
        }

        // Rivaux contemporains (même niveau, même région)
        $rivaux_contemporains = Pays::where('region_tennis', $this->region_tennis)
            ->where('id', '!=', $this->id)
            ->whereBetween('force_tennis_globale', [
                $this->force_tennis_globale - 15,
                $this->force_tennis_globale + 15
            ])
            ->get();

        foreach ($rivaux_contemporains as $rival) {
            $rivaux[] = [
                'pays' => $rival,
                'type' => 'contemporain',
                'intensite' => $this->calculerIntensiteRivalite($rival)
            ];
        }

        return $rivaux;
    }

    /**
     * Analyser l'écosystème tennis complet
     */
    public function analyserEcosysteme()
    {
        return [
            'forces_cles' => $this->identifierForces(),
            'points_amelioration' => $this->identifierFaiblesses(),
            'benchmark_mondial' => $this->comparerAvecMoyenneMondiale(),
            'recommandations_strategiques' => $this->genererRecommandationsStrategiques(),
            'investissements_prioritaires' => $this->identifierInvestissementsPrioritaires(),
            'partenariats_strategiques' => $this->identifierPartenairiatsPotentiels(),
            'timeline_objectifs' => $this->genererTimelineObjectifs()
        ];
    }

    // ===================================================================
    // HELPER METHODS
    // ===================================================================

    private function calculerAvantageStyle($style1, $style2)
    {
        $avantages = [
            'offensif' => ['defensif' => 10, 'tactique' => -5, 'varie' => 0],
            'defensif' => ['offensif' => -10, 'tactique' => 5, 'varie' => 0],
            'tactique' => ['offensif' => 5, 'defensif' => -5, 'varie' => 0],
            'varie' => ['offensif' => 0, 'defensif' => 0, 'tactique' => 0]
        ];

        return $avantages[$style1][$style2] ?? 0;
    }

    private function identifierForces()
    {
        $forces = [];

        if ($this->tradition_tennis_score > 80) $forces[] = 'Tradition tennis exceptionnelle';
        if ($this->qualite_infrastructure > 85) $forces[] = 'Infrastructure de classe mondiale';
        if ($this->systeme_formation_score > 80) $forces[] = 'Système de formation excellent';
        if ($this->nb_joueurs_top_100_actuel >= 5) $forces[] = 'Réservoir de talents important';
        if ($this->soutien_etatique_score > 75) $forces[] = 'Soutien étatique fort';
        if ($this->innovation_technique > 80) $forces[] = 'Innovation technique avancée';

        return $forces;
    }

    private function identifierFaiblesses()
    {
        $faiblesses = [];

        if ($this->accessibilite_tennis < 50) $faiblesses[] = 'Accessibilité tennis limitée';
        if ($this->cout_pratique_tennis > 75) $faiblesses[] = 'Coût de pratique élevé';
        if ($this->adaptation_voyages < 60) $faiblesses[] = 'Difficultés adaptation voyages';
        if ($this->polyvalence_joueurs < 65) $faiblesses[] = 'Polyvalence limitée surfaces';
        if ($this->sponsor_prive_score < 50) $faiblesses[] = 'Sponsoring privé insuffisant';

        return $faiblesses;
    }

    private function calculerCroissanceJoueurs()
    {
        $facteurs = [
            'infrastructure' => $this->qualite_infrastructure / 100 * 0.3,
            'formation' => $this->systeme_formation_score / 100 * 0.25,
            'soutien' => ($this->soutien_etatique_score + $this->sponsor_prive_score) / 200 * 0.2,
            'accessibilite' => $this->accessibilite_tennis / 100 * 0.15,
            'evolution' => $this->evolution_5_ans === 'progression' ? 0.1 : 0
        ];

        return array_sum($facteurs) * 2; // Croissance annuelle estimée
    }

    private function calculerEvolutionForce()
    {
        $evolution = 0;

        if ($this->evolution_5_ans === 'progression') $evolution += 2;
        elseif ($this->evolution_5_ans === 'declin') $evolution -= 2;

        $evolution += ($this->investissements_prevus / 10) * 0.5; // Impact investissements
        $evolution += ($this->potentiel_futur - 50) * 0.1; // Impact potentiel

        return $evolution;
    }

    private function determinerSeveriteDecalage($decalage)
    {
        if ($decalage <= 2) return 'faible';
        if ($decalage <= 5) return 'modere';
        if ($decalage <= 8) return 'important';
        return 'severe';
    }

    private function calculerJoursAdaptation($decalage)
    {
        return min(7, ceil($decalage * 0.7)); // Règle approximative
    }

    private function calculerImpactPerformance($decalage)
    {
        return min(25, $decalage * 3); // % de réduction performance
    }

    private function genererRecommandationsVoyage($decalage)
    {
        $recommandations = [];

        if ($decalage >= 6) {
            $recommandations[] = 'Arriver 4-5 jours avant le tournoi';
            $recommandations[] = 'Therapy de luminothérapie';
        } elseif ($decalage >= 3) {
            $recommandations[] = 'Arriver 2-3 jours avant';
            $recommandations[] = 'Ajuster horaires sommeil progressivement';
        }

        return $recommandations;
    }

    private function calculerIntensiteRivalite(Pays $rival)
    {
        $intensite = 0;

        // Proximité niveau
        $diffNiveau = abs($this->force_tennis_globale - $rival->force_tennis_globale);
        $intensite += max(0, 20 - $diffNiveau);

        // Même région
        if ($this->region_tennis === $rival->region_tennis) $intensite += 20;

        // Historique confrontations
        if ($this->rivalites_historiques && in_array($rival->id, $this->rivalites_historiques)) {
            $intensite += 30;
        }

        return min(100, $intensite);
    }

    private function comparerAvecMoyenneMondiale()
    {
        $moyennes = Pays::selectRaw('
            AVG(force_tennis_globale) as force_moyenne,
            AVG(systeme_formation_score) as formation_moyenne,
            AVG(qualite_infrastructure) as infrastructure_moyenne
        ')->first();

        return [
            'force_vs_moyenne' => $this->force_tennis_globale - $moyennes->force_moyenne,
            'formation_vs_moyenne' => $this->systeme_formation_score - $moyennes->formation_moyenne,
            'infrastructure_vs_moyenne' => $this->qualite_infrastructure - $moyennes->infrastructure_moyenne
        ];
    }

    private function genererRecommandationsStrategiques()
    {
        $recommandations = [];

        if ($this->accessibilite_tennis < 60) {
            $recommandations[] = 'Développer programmes tennis scolaire';
        }

        if ($this->qualite_infrastructure < 70) {
            $recommandations[] = 'Investir dans infrastructure moderne';
        }

        if ($this->systeme_formation_score < 75) {
            $recommandations[] = 'Renforcer formation entraîneurs';
        }

        return $recommandations;
    }

    private function identifierInvestissementsPrioritaires()
    {
        $priorites = [];

        $scores = [
            'infrastructure' => $this->qualite_infrastructure,
            'formation' => $this->systeme_formation_score,
            'accessibilite' => $this->accessibilite_tennis,
            'technologie' => $this->utilisation_technologie
        ];

        asort($scores); // Trier par score croissant

        foreach ($scores as $domaine => $score) {
            if ($score < 70) {
                $priorites[] = [
                    'domaine' => $domaine,
                    'score_actuel' => $score,
                    'impact_estime' => 'high'
                ];
            }
        }

        return $priorites;
    }

    private function identifierPartenairiatsPotentiels()
    {
        return Pays::where('cooperation_internationale', '>', 75)
            ->where('id', '!=', $this->id)
            ->where('region_tennis', '!=', $this->region_tennis)
            ->orderBy('force_tennis_globale', 'desc')
            ->limit(5)
            ->get();
    }

    private function genererTimelineObjectifs()
    {
        $timeline = [];

        if ($this->objectifs_5_ans) {
            foreach ($this->objectifs_5_ans as $index => $objectif) {
                $timeline[] = [
                    'annee' => $index + 1,
                    'objectif' => $objectif,
                    'mesures' => $this->strategies_developpement[$index] ?? 'À définir'
                ];
            }
        }

        return $timeline;
    }

    private function calculerConfiancePrediction()
    {
        $confiance = 60; // Base

        if ($this->fiabilite_donnees > 80) $confiance += 20;
        if ($this->completude_profil > 90) $confiance += 15;
        if ($this->derniere_analyse_experte && $this->derniere_analyse_experte->diffInMonths(now()) < 6) $confiance += 15;

        return min(95, $confiance);
    }

    private function predireClassementEquipe($annees)
    {
        $evolutionPrevue = $this->calculerEvolutionForce() * $annees;
        $nouveauClassement = $this->classement_par_equipe - ($evolutionPrevue / 2); // Amélioration force = meilleur classement

        return max(1, round($nouveauClassement));
    }

    // ===================================================================
    // STATIC METHODS
    // ===================================================================

    /**
     * Obtenir le classement mondial des forces tennis
     */
    public static function getClassementMondial()
    {
        return self::orderBy('force_tennis_globale', 'desc')
            ->select(['id', 'nom', 'code', 'force_tennis_globale', 'nb_joueurs_top_100_actuel'])
            ->get()
            ->values()
            ->map(function($pays, $index) {
                $pays->rang_mondial = $index + 1;
                return $pays;
            });
    }

    /**
     * Analyser les tendances mondiales du tennis
     */
    public static function analyserTendancesMondiales()
    {
        return [
            'croissance_globale' => self::where('evolution_5_ans', 'progression')->count(),
            'declin_global' => self::where('evolution_5_ans', 'declin')->count(),
            'regions_dominantes' => self::selectRaw('region_tennis, AVG(force_tennis_globale) as force_moyenne')
                ->groupBy('region_tennis')
                ->orderBy('force_moyenne', 'desc')
                ->get(),
            'investissement_total' => self::sum('investissement_tennis'),
            'joueurs_top_100_total' => self::sum('nb_joueurs_top_100_actuel'),
            'distribution_surfaces' => self::selectRaw('surface_preference, COUNT(*) as count')
                ->groupBy('surface_preference')
                ->get()
        ];
    }

    /**
     * Identifier les pays émergents
     */
    public static function getPaysEmergents()
    {
        return self::where('evolution_5_ans', 'progression')
            ->where('potentiel_futur', '>', 70)
            ->where('force_tennis_globale', '<', 70) // Pas encore établis
            ->orderBy('potentiel_futur', 'desc')
            ->get();
    }

    // ===================================================================
    // VALIDATION RULES
    // ===================================================================

    public static function validationRules()
    {
        return [
            'nom' => 'required|string|max:255',
            'code' => 'required|string|size:2|unique:pays,code',
            'code_iso3' => 'required|string|size:3|unique:pays,code_iso3',
            'region_tennis' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'fuseau_horaire' => 'required|integer|between:-12,12'
        ];
    }
}
