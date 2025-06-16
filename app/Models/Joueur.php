<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Joueur extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'joueurs';

    protected $fillable = [
        // Informations personnelles de base
        'nom',
        'prenom',
        'nom_complet_display',    // Nom affiché (parfois différent)
        'surnom',                 // "Rafa", "Fed", "Nole"
        'pays_id',
        'pays_residence_id',      // Pays de résidence (souvent différent)
        'ville_naissance',
        'ville_residence',
        'date_naissance',
        'sexe',
        'nationalites_multiples', // JSON pour double nationalité

        // Caractéristiques physiques et style
        'main',                   // 'droitier', 'gaucher'
        'revers',                 // 'une_main', 'deux_mains'
        'taille',                 // cm
        'poids',                  // kg
        'envergure',              // cm (importante pour le service)
        'allonge',                // Portée des bras
        'groupe_sanguin',         // Pour médical
        'imc',                    // Calculé automatiquement

        // Style de jeu et tactique
        'style_jeu_principal',    // 'baseline', 'serve_volley', 'all_court', 'counterpuncher'
        'style_jeu_secondaire',   // Style alternatif
        'position_court_favorite', // 'fond', 'mi_court', 'filet'
        'agressivite_jeu',        // 1-10 (défensif à offensif)
        'vitesse_deplacement',    // 1-10
        'endurance_niveau',       // 1-10
        'force_mentale',          // 1-10
        'regularite_niveau',      // 1-10 (constance)
        'punch_niveau',           // 1-10 (capacité à finir points)

        // Préférences et performances surfaces
        'surface_favorite',       // 'dur', 'terre', 'gazon', 'indoor'
        'surface_detestee',       // Surface la moins aimée
        'performance_dur',        // Coefficient 0-100
        'performance_terre',      // Coefficient 0-100
        'performance_gazon',      // Coefficient 0-100
        'performance_indoor',     // Coefficient 0-100
        'vitesse_surface_preferee', // 'lente', 'moyenne', 'rapide'

        // Conditions de jeu optimales
        'temperature_optimale',   // °C préférée
        'tolere_vent',           // 1-10 tolérance au vent
        'tolere_chaleur',        // 1-10 tolérance chaleur
        'tolere_froid',          // 1-10 tolérance froid
        'prefere_jour_nuit',     // 'jour', 'nuit', 'indifferent'
        'performance_altitude',   // Performance en altitude 1-10

        // Classements et points
        'classement_atp_wta',
        'classement_precedent',
        'meilleur_classement',
        'pire_classement',
        'points_actuels',
        'points_precedents',
        'points_race',            // Points course au Masters
        'elo_rating_global',      // ELO global
        'elo_dur',               // ELO surface dur
        'elo_terre',             // ELO terre battue
        'elo_gazon',             // ELO gazon
        'niveau_joueur_id',

        // Statistiques carrière étendues
        'victoires_saison',
        'defaites_saison',
        'victoires_carriere',
        'defaites_carriere',
        'titres_carriere',
        'titres_saison',
        'finales_carriere',
        'finales_saison',
        'demi_finales_carriere',
        'prize_money_carriere',
        'prize_money_saison',

        // Statistiques par niveau tournoi
        'titres_grand_chelem',
        'finales_grand_chelem',
        'titres_masters_1000',
        'finales_masters_1000',
        'titres_atp_500',
        'titres_atp_250',

        // Records et achievements
        'plus_long_match',        // Durée en minutes
        'plus_court_match',       // Durée en minutes
        'serie_victoires_max',    // Plus longue série
        'serie_defaites_max',     // Plus longue série de défaites
        'nb_tie_breaks_gagnes',
        'nb_tie_breaks_perdus',
        'record_vs_top_10',       // Format "15-8" (V-D)
        'record_vs_top_50',
        'record_vs_top_100',

        // Forme et condition
        'forme_actuelle',         // 1-10
        'confiance_niveau',       // 1-10
        'motivation_niveau',      // 1-10
        'fatigue_niveau',         // 1-10 (10 = très fatigué)
        'stress_niveau',          // 1-10
        'derniere_evaluation_forme', // Date

        // Équipe et staff
        'entraineur_principal',
        'entraineur_physique',
        'entraineur_mental',
        'manager',
        'medecin',
        'physiotherapeute',
        'academie_formation',
        'sponsor_principal',
        'equipementier',

        // Matériel et équipement
        'marque_raquette',
        'modele_raquette',
        'poids_raquette',         // grammes
        'tension_cordage',        // kg
        'type_cordage',
        'marque_chaussures',
        'type_grip',
        'marque_vetements',

        // Données financières étendues
        'prize_money',            // Prize money total (legacy)
        'salaire_annuel_estime',  // Estimation revenus
        'valeur_sponsoring',      // Valeur contrats sponsoring
        'cout_equipe_annuel',     // Coût de l'équipe
        'investissement_formation', // Investissement en formation

        // Blessures et santé
        'historique_blessures_majeures', // JSON
        'zones_fragiles',         // JSON des zones à risque
        'allergies',              // JSON
        'traitements_medicaux',   // JSON
        'derniere_visite_medicale',
        'aptitude_medicale',      // 'apte', 'apte_reserve', 'inapte'

        // Analyse comportementale
        'temperament',            // 'calme', 'explosif', 'variable'
        'gestion_pression',       // 1-10
        'leadership',             // 1-10
        'fair_play',             // 1-10
        'media_relations',        // 1-10
        'popularite_fans',        // 1-10
        'charisma',              // 1-10

        // Données techniques avancées
        'vitesse_service_max',    // km/h
        'vitesse_service_moyenne',
        'vitesse_coup_droit_max',
        'vitesse_revers_max',
        'precision_service',      // Pourcentage zones
        'puissance_frappe',       // 1-10
        'qualite_retour',         // 1-10
        'jeu_filet',             // 1-10
        'anticipation',          // 1-10
        'reactivite',            // 1-10

        // Statut et carrière
        'statut',                 // 'actif', 'inactif', 'retraite', 'suspendu'
        'date_debut_pro',
        'date_retraite',
        'annees_experience',      // Calculé automatiquement
        'pic_carriere_atteint',   // Boolean
        'phase_carriere',         // 'montee', 'pic', 'plateau', 'declin'

        // Objectifs et projections
        'objectif_classement',    // Objectif de classement
        'objectif_tournois',      // JSON des tournois visés
        'potentiel_estime',       // 1-100 potentiel estimé
        'progression_prevue',     // 'hausse', 'stable', 'baisse'
        'retraite_estimee',       // Année estimée de retraite

        // Données sociales et marketing
        'followers_instagram',
        'followers_twitter',
        'followers_total',
        'engagement_social',      // Taux d'engagement
        'valeur_marketing',       // Valeur marketing estimée
        'langues_parlees',        // JSON
        'pays_fan_base',          // JSON des pays de fans

        // Métadonnées système
        'photo_url',
        'photos_galerie',         // JSON d'URLs
        'videos_highlights',      // JSON d'URLs
        'derniere_maj_stats',
        'derniere_maj_classement',
        'source_donnees_principal', // Source des données
        'fiabilite_donnees',      // 1-10
        'actif',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_debut_pro' => 'date',
        'date_retraite' => 'date',
        'derniere_evaluation_forme' => 'date',
        'derniere_visite_medicale' => 'date',
        'derniere_maj_stats' => 'datetime',
        'derniere_maj_classement' => 'datetime',

        // Entiers
        'taille' => 'integer',
        'poids' => 'integer',
        'envergure' => 'integer',
        'allonge' => 'integer',
        'classement_atp_wta' => 'integer',
        'classement_precedent' => 'integer',
        'meilleur_classement' => 'integer',
        'pire_classement' => 'integer',
        'points_actuels' => 'integer',
        'points_precedents' => 'integer',
        'points_race' => 'integer',
        'victoires_saison' => 'integer',
        'defaites_saison' => 'integer',
        'victoires_carriere' => 'integer',
        'defaites_carriere' => 'integer',
        'titres_carriere' => 'integer',
        'titres_saison' => 'integer',
        'finales_carriere' => 'integer',
        'finales_saison' => 'integer',
        'demi_finales_carriere' => 'integer',
        'titres_grand_chelem' => 'integer',
        'finales_grand_chelem' => 'integer',
        'titres_masters_1000' => 'integer',
        'finales_masters_1000' => 'integer',
        'titres_atp_500' => 'integer',
        'titres_atp_250' => 'integer',
        'plus_long_match' => 'integer',
        'plus_court_match' => 'integer',
        'serie_victoires_max' => 'integer',
        'serie_defaites_max' => 'integer',
        'nb_tie_breaks_gagnes' => 'integer',
        'nb_tie_breaks_perdus' => 'integer',
        'poids_raquette' => 'integer',
        'tension_cordage' => 'integer',
        'vitesse_service_max' => 'integer',
        'vitesse_service_moyenne' => 'integer',
        'vitesse_coup_droit_max' => 'integer',
        'vitesse_revers_max' => 'integer',
        'annees_experience' => 'integer',
        'objectif_classement' => 'integer',
        'followers_instagram' => 'integer',
        'followers_twitter' => 'integer',
        'followers_total' => 'integer',
        'fiabilite_donnees' => 'integer',

        // Décimaux
        'imc' => 'decimal:2',
        'temperature_optimale' => 'decimal:1',
        'performance_dur' => 'decimal:1',
        'performance_terre' => 'decimal:1',
        'performance_gazon' => 'decimal:1',
        'performance_indoor' => 'decimal:1',
        'elo_rating_global' => 'decimal:1',
        'elo_dur' => 'decimal:1',
        'elo_terre' => 'decimal:1',
        'elo_gazon' => 'decimal:1',
        'prize_money_carriere' => 'decimal:2',
        'prize_money_saison' => 'decimal:2',
        'prize_money' => 'decimal:2',
        'salaire_annuel_estime' => 'decimal:2',
        'valeur_sponsoring' => 'decimal:2',
        'cout_equipe_annuel' => 'decimal:2',
        'investissement_formation' => 'decimal:2',
        'precision_service' => 'decimal:2',
        'potentiel_estime' => 'decimal:1',
        'valeur_marketing' => 'decimal:2',
        'engagement_social' => 'decimal:2',

        // Booléens
        'pic_carriere_atteint' => 'boolean',
        'actif' => 'boolean',

        // JSON
        'nationalites_multiples' => 'json',
        'historique_blessures_majeures' => 'json',
        'zones_fragiles' => 'json',
        'allergies' => 'json',
        'traitements_medicaux' => 'json',
        'objectif_tournois' => 'json',
        'langues_parlees' => 'json',
        'pays_fan_base' => 'json',
        'photos_galerie' => 'json',
        'videos_highlights' => 'json',
    ];

    protected $appends = [
        'nom_complet',
        'age',
        'pourcentage_victoires_saison',
        'pourcentage_victoires_carriere',
        'classement_evolution',
        'est_top_joueur',
        'surface_dominante',
        'forme_recente_score',
        'indice_performance_global',
        'potentiel_progression',
        'facteur_ajustement_ia',
        'profil_style_complet',
        'indicateurs_cles',
    ];

    // ===================================================================
    // RELATIONSHIPS (existantes + nouvelles)
    // ===================================================================

    public function pays()
    {
        return $this->belongsTo(Pays::class);
    }

    public function paysResidence()
    {
        return $this->belongsTo(Pays::class, 'pays_residence_id');
    }

    public function niveau()
    {
        return $this->belongsTo(NiveauJoueur::class, 'niveau_joueur_id');
    }

    public function statistiques()
    {
        return $this->hasMany(StatistiqueJoueur::class);
    }

    public function statistiquesParSurface($surface)
    {
        return $this->hasMany(StatistiqueJoueur::class)
            ->where('surface', $surface);
    }

    public function blessures()
    {
        return $this->hasMany(Blessure::class);
    }

    public function blessuresActives()
    {
        return $this->hasMany(Blessure::class)
            ->where('est_active', true);
    }

    public function confrontations()
    {
        return $this->hasMany(Confrontation::class, 'joueur1_id');
    }

    public function formeRecente()
    {
        return $this->hasOne(FormeRecente::class);
    }

    public function evaluationsPhysiques()
    {
        return $this->hasMany(EvaluationPhysique::class);
    }

    public function analysesTechniques()
    {
        return $this->hasMany(AnalyseTechnique::class);
    }

    // Relations matchs étendues
    public function matchsJoueur1()
    {
        return $this->hasMany(MatchTennis::class, 'joueur1_id');
    }

    public function matchsJoueur2()
    {
        return $this->hasMany(MatchTennis::class, 'joueur2_id');
    }

    public function matchsGagnes()
    {
        return $this->hasMany(MatchTennis::class, 'gagnant_id');
    }

    public function matchsParSurface($surface)
    {
        return MatchTennis::where(function ($query) {
            $query->where('joueur1_id', $this->id)
                ->orWhere('joueur2_id', $this->id);
        })->whereHas('tournoi.surface', function ($q) use ($surface) {
            $q->where('code', $surface);
        });
    }

    public function predictions()
    {
        return $this->hasMany(Prediction::class, 'gagnant_predit_id');
    }

    public function predictionsReussies()
    {
        return $this->hasMany(Prediction::class, 'gagnant_predit_id')
            ->where('est_correcte', true);
    }

    // ===================================================================
    // ACCESSORS EXISTANTS AMÉLIORÉS + NOUVEAUX
    // ===================================================================

    public function getNomCompletAttribute()
    {
        return $this->nom_complet_display ?: $this->prenom.' '.$this->nom;
    }

    public function getAgeAttribute()
    {
        return $this->date_naissance ? $this->date_naissance->age : null;
    }

    public function getPourcentageVictoiresSaisonAttribute()
    {
        $total = $this->victoires_saison + $this->defaites_saison;

        return $total > 0 ? round(($this->victoires_saison / $total) * 100, 2) : 0;
    }

    public function getPourcentageVictoiresCarriereAttribute()
    {
        $total = $this->victoires_carriere + $this->defaites_carriere;

        return $total > 0 ? round(($this->victoires_carriere / $total) * 100, 2) : 0;
    }

    public function getClassementEvolutionAttribute()
    {
        if (! $this->classement_precedent || ! $this->classement_atp_wta) {
            return 'stable';
        }

        $evolution = $this->classement_precedent - $this->classement_atp_wta;

        if ($evolution > 5) {
            return 'forte_hausse';
        }
        if ($evolution > 0) {
            return 'hausse';
        }
        if ($evolution < -5) {
            return 'forte_baisse';
        }
        if ($evolution < 0) {
            return 'baisse';
        }

        return 'stable';
    }

    public function getEstTopJoueurAttribute()
    {
        return $this->classement_atp_wta && $this->classement_atp_wta <= 100;
    }

    public function getSurfaceDominanteAttribute()
    {
        $performances = [
            'dur' => $this->performance_dur ?? 50,
            'terre' => $this->performance_terre ?? 50,
            'gazon' => $this->performance_gazon ?? 50,
            'indoor' => $this->performance_indoor ?? 50,
        ];

        return array_keys($performances, max($performances))[0];
    }

    public function getFormeRecenteScoreAttribute()
    {
        $facteurs = [
            $this->forme_actuelle ?? 5,
            $this->confiance_niveau ?? 5,
            $this->motivation_niveau ?? 5,
            (10 - ($this->fatigue_niveau ?? 5)), // Inverser fatigue
            (10 - ($this->stress_niveau ?? 5)),   // Inverser stress
        ];

        return round(array_sum($facteurs) / count($facteurs), 1);
    }

    public function getIndicePerformanceGlobalAttribute()
    {
        $composantes = [
            'classement' => $this->calculerScoreClassement(),
            'forme' => $this->forme_recente_score,
            'surfaces' => $this->calculerScoreSurfaces(),
            'experience' => $this->calculerScoreExperience(),
            'mentale' => ($this->force_mentale ?? 5) * 10,
            'physique' => $this->calculerScorePhysique(),
        ];

        return round(array_sum($composantes) / count($composantes), 1);
    }

    public function getPotentielProgressionAttribute()
    {
        $age = $this->age;
        $classement = $this->classement_atp_wta;
        $potentiel = $this->potentiel_estime ?? 50;

        // Potentiel selon l'âge
        if ($age < 20) {
            $facteurAge = 1.2;
        } elseif ($age < 25) {
            $facteurAge = 1.1;
        } elseif ($age < 30) {
            $facteurAge = 1.0;
        } elseif ($age < 33) {
            $facteurAge = 0.9;
        } else {
            $facteurAge = 0.7;
        }

        // Potentiel selon classement actuel
        if ($classement > 500) {
            $facteurClassement = 1.3;
        } elseif ($classement > 200) {
            $facteurClassement = 1.1;
        } elseif ($classement > 100) {
            $facteurClassement = 1.0;
        } else {
            $facteurClassement = 0.8;
        }

        return round($potentiel * $facteurAge * $facteurClassement, 1);
    }

    public function getFacteurAjustementIaAttribute()
    {
        // Facteur global d'ajustement pour algorithmes IA (-1 à +1)
        $composantes = [
            'forme' => ($this->forme_recente_score - 5) / 5,
            'blessure' => $this->estBlesse() ? -0.3 : 0,
            'surface' => $this->getAjustementSurface(),
            'conditions' => $this->getAjustementConditions(),
            'mental' => (($this->force_mentale ?? 5) - 5) / 5,
        ];

        return max(-1, min(1, array_sum($composantes) / count($composantes)));
    }

    public function getProfilStyleCompletAttribute()
    {
        return [
            'style_principal' => $this->style_jeu_principal,
            'style_secondaire' => $this->style_jeu_secondaire,
            'agressivite' => $this->agressivite_jeu,
            'position_favorite' => $this->position_court_favorite,
            'surface_dominante' => $this->surface_dominante,
            'points_forts' => $this->getPointsForts(),
            'points_faibles' => $this->getPointsFaibles(),
        ];
    }

    public function getIndicateursClesAttribute()
    {
        return [
            'forme' => $this->forme_recente_score.'/10',
            'performance' => $this->indice_performance_global.'/100',
            'evolution' => $this->classement_evolution,
            'surface_dominante' => ucfirst($this->surface_dominante),
            'potentiel' => $this->potentiel_progression.'/100',
            'experience' => $this->annees_experience.' ans',
            'titre_saison' => $this->titres_saison,
            'prize_money' => number_format($this->prize_money_saison, 0, ',', ' ').'€',
        ];
    }

    // ===================================================================
    // SCOPES EXISTANTS + NOUVEAUX
    // ===================================================================

    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopeParSexe($query, $sexe)
    {
        return $query->where('sexe', $sexe);
    }

    public function scopeParNationalite($query, $paysId)
    {
        return $query->where('pays_id', $paysId);
    }

    public function scopeTopClassement($query, $limite = 100)
    {
        return $query->where('classement_atp_wta', '<=', $limite)
            ->where('classement_atp_wta', '>', 0)
            ->orderBy('classement_atp_wta');
    }

    public function scopeParSurfaceFavorite($query, $surface)
    {
        return $query->where('surface_favorite', $surface);
    }

    public function scopeAvecBlessure($query)
    {
        return $query->whereHas('blessuresActives');
    }

    public function scopeSansBlessure($query)
    {
        return $query->whereDoesntHave('blessuresActives');
    }

    public function scopeParStyleJeu($query, $style)
    {
        return $query->where('style_jeu_principal', $style)
            ->orWhere('style_jeu_secondaire', $style);
    }

    public function scopeParPhaseCarriere($query, $phase)
    {
        return $query->where('phase_carriere', $phase);
    }

    public function scopeJeunesEspoirs($query)
    {
        return $query->whereRaw('YEAR(CURDATE()) - YEAR(date_naissance) <= 21')
            ->where('classement_atp_wta', '<=', 500);
    }

    public function scopeVeterans($query)
    {
        return $query->whereRaw('YEAR(CURDATE()) - YEAR(date_naissance) >= 35');
    }

    public function scopeEnForme($query)
    {
        return $query->where('forme_actuelle', '>=', 7);
    }

    public function scopeEnDifficulte($query)
    {
        return $query->where('forme_actuelle', '<=', 4)
            ->orWhere('classement_evolution', 'forte_baisse');
    }

    public function scopeGrandsChampions($query)
    {
        return $query->where('titres_grand_chelem', '>', 0);
    }

    public function scopeParFormeRecente($query, $min, $max)
    {
        return $query->whereBetween('forme_actuelle', [$min, $max]);
    }

    public function scopeRecherche($query, $terme)
    {
        return $query->where(function ($q) use ($terme) {
            $q->where('nom', 'LIKE', "%{$terme}%")
                ->orWhere('prenom', 'LIKE', "%{$terme}%")
                ->orWhere('surnom', 'LIKE', "%{$terme}%");
        });
    }

    // ===================================================================
    // METHODS EXISTANTES AMÉLIORÉES + NOUVELLES
    // ===================================================================

    /**
     * Obtenir le classement ELO du joueur (amélioré)
     */
    public function getEloRating($surface = null)
    {
        if ($surface) {
            $champElo = "elo_{$surface}";

            return $this->$champElo ?? 1500;
        }

        return $this->elo_rating_global ?? 1500;
    }

    /**
     * Calculer la forme récente (amélioré)
     */
    public function getFormeRecente($nbMatchs = 5)
    {
        $matchsRecents = MatchTennis::where(function ($query) {
            $query->where('joueur1_id', $this->id)
                ->orWhere('joueur2_id', $this->id);
        })
            ->whereHas('statut', function ($q) {
                $q->where('code', 'termine');
            })
            ->orderBy('date_match', 'desc')
            ->limit($nbMatchs)
            ->get();

        $victoires = $matchsRecents->where('gagnant_id', $this->id)->count();
        $total = $matchsRecents->count();

        return [
            'victoires' => $victoires,
            'defaites' => $total - $victoires,
            'total' => $total,
            'pourcentage' => $total > 0 ? round(($victoires / $total) * 100, 2) : 0,
            'serie' => $this->getSerieActuelle($matchsRecents),
            'qualite_adversaires' => $this->getQualiteAdversairesRecents($matchsRecents),
        ];
    }

    /**
     * Obtenir les statistiques H2H (amélioré)
     */
    public function getHeadToHead($adversaireId)
    {
        $confrontation = Confrontation::where(function ($query) use ($adversaireId) {
            $query->where(['joueur1_id' => $this->id, 'joueur2_id' => $adversaireId])
                ->orWhere(['joueur1_id' => $adversaireId, 'joueur2_id' => $this->id]);
        })->first();

        if (! $confrontation) {
            return [
                'victoires' => 0,
                'defaites' => 0,
                'total' => 0,
                'pourcentage' => 0,
                'dernier_match' => null,
                'serie_actuelle' => 0,
            ];
        }

        $victoires = $confrontation->joueur1_id == $this->id ?
            $confrontation->victoires_joueur1 :
            $confrontation->victoires_joueur2;

        $defaites = $confrontation->confrontations_totales - $victoires;

        return [
            'victoires' => $victoires,
            'defaites' => $defaites,
            'total' => $confrontation->confrontations_totales,
            'pourcentage' => $confrontation->confrontations_totales > 0 ?
                round(($victoires / $confrontation->confrontations_totales) * 100, 2) : 0,
            'dernier_match' => $confrontation->derniere_confrontation,
            'serie_actuelle' => $confrontation->serie_actuelle ?? 0,
        ];
    }

    /**
     * Vérifier si le joueur est blessé (amélioré)
     */
    public function estBlesse()
    {
        return $this->blessuresActives()->exists();
    }

    /**
     * Obtenir les blessures actives avec détails
     */
    public function getBlessuresActives()
    {
        return $this->blessuresActives()
            ->with('typeBlessure')
            ->get()
            ->map(function ($blessure) {
                return [
                    'type' => $blessure->typeBlessure->nom,
                    'zone' => $blessure->typeBlessure->zone_corporelle,
                    'gravite' => $blessure->gravite,
                    'impact_performance' => $blessure->typeBlessure->facteur_ajustement_ia,
                    'date_debut' => $blessure->date_debut,
                    'duree_estimee' => $blessure->duree_estimee_guerison,
                ];
            });
    }

    /**
     * Analyser les performances par surface
     */
    public function getAnalyseSurfaces()
    {
        return [
            'dur' => [
                'performance' => $this->performance_dur,
                'elo' => $this->elo_dur,
                'matchs_saison' => $this->matchsParSurface('hard')->count(),
                'pourcentage_victoires' => $this->getPourcentageVictoiresSurface('hard'),
            ],
            'terre' => [
                'performance' => $this->performance_terre,
                'elo' => $this->elo_terre,
                'matchs_saison' => $this->matchsParSurface('clay')->count(),
                'pourcentage_victoires' => $this->getPourcentageVictoiresSurface('clay'),
            ],
            'gazon' => [
                'performance' => $this->performance_gazon,
                'elo' => $this->elo_gazon,
                'matchs_saison' => $this->matchsParSurface('grass')->count(),
                'pourcentage_victoires' => $this->getPourcentageVictoiresSurface('grass'),
            ],
        ];
    }

    /**
     * Prédire probabilité de victoire (amélioré avec IA)
     */
    public function getProbabiliteVictoire($adversaire, $surface = 'dur', $conditions = null)
    {
        // Base ELO
        $eloJoueur = $this->getEloRating($surface);
        $eloAdversaire = $adversaire->getEloRating($surface);
        $probabiliteBase = 1 / (1 + pow(10, ($eloAdversaire - $eloJoueur) / 400));

        // Ajustements IA
        $ajustements = [
            'forme' => $this->facteur_ajustement_ia - $adversaire->facteur_ajustement_ia,
            'surface' => $this->getAvantageSurface($surface) - $adversaire->getAvantageSurface($surface),
            'h2h' => $this->getFacteurH2H($adversaire->id),
            'conditions' => $conditions ? $this->getAdaptationConditions($conditions) : 0,
            'blessures' => $this->getImpactBlessures(),
        ];

        $ajustementTotal = array_sum($ajustements) / 10; // Normaliser
        $probabiliteFinale = $probabiliteBase + $ajustementTotal;

        return max(5, min(95, round($probabiliteFinale * 100, 1)));
    }

    /**
     * Générer le rapport de performance complet
     */
    public function genererRapportPerformance()
    {
        return [
            'identite' => [
                'nom' => $this->nom_complet,
                'age' => $this->age,
                'nationalite' => $this->pays->nom,
                'classement' => $this->classement_atp_wta,
            ],
            'forme_actuelle' => [
                'score_forme' => $this->forme_recente_score,
                'confiance' => $this->confiance_niveau,
                'motivation' => $this->motivation_niveau,
                'fatigue' => $this->fatigue_niveau,
                'evolution_classement' => $this->classement_evolution,
            ],
            'style_jeu' => $this->profil_style_complet,
            'surfaces' => $this->getAnalyseSurfaces(),
            'points_forts' => $this->getPointsForts(),
            'points_faibles' => $this->getPointsFaibles(),
            'blessures' => $this->getBlessuresActives(),
            'objectifs' => [
                'classement_vise' => $this->objectif_classement,
                'tournois_vises' => $this->objectif_tournois,
                'potentiel' => $this->potentiel_progression,
            ],
            'facteur_ia' => $this->facteur_ajustement_ia,
            'indicateurs' => $this->indicateurs_cles,
        ];
    }

    // ===================================================================
    // METHODS PRIVÉES DE CALCUL
    // ===================================================================

    private function calculerScoreClassement()
    {
        if (! $this->classement_atp_wta) {
            return 50;
        }

        // Score inversement proportionnel au classement
        if ($this->classement_atp_wta <= 10) {
            return 95;
        }
        if ($this->classement_atp_wta <= 50) {
            return 85;
        }
        if ($this->classement_atp_wta <= 100) {
            return 70;
        }
        if ($this->classement_atp_wta <= 300) {
            return 55;
        }
        if ($this->classement_atp_wta <= 500) {
            return 40;
        }

        return 25;
    }

    private function calculerScoreSurfaces()
    {
        $performances = [
            $this->performance_dur ?? 50,
            $this->performance_terre ?? 50,
            $this->performance_gazon ?? 50,
            $this->performance_indoor ?? 50,
        ];

        return array_sum($performances) / count($performances);
    }

    private function calculerScoreExperience()
    {
        $experience = $this->annees_experience ?? 0;

        if ($experience >= 15) {
            return 90;
        }
        if ($experience >= 10) {
            return 80;
        }
        if ($experience >= 5) {
            return 70;
        }
        if ($experience >= 2) {
            return 60;
        }

        return 50;
    }

    private function calculerScorePhysique()
    {
        $composantes = [
            $this->vitesse_deplacement ?? 5,
            $this->endurance_niveau ?? 5,
            (10 - ($this->fatigue_niveau ?? 5)), // Inverser fatigue
            $this->estBlesse() ? 3 : 8, // Pénalité blessure
        ];

        return (array_sum($composantes) / count($composantes)) * 10;
    }

    private function getPointsForts()
    {
        $forts = [];

        if ($this->vitesse_service_max > 200) {
            $forts[] = 'Service puissant';
        }
        if ($this->precision_service > 65) {
            $forts[] = 'Service précis';
        }
        if ($this->endurance_niveau >= 8) {
            $forts[] = 'Endurance excellente';
        }
        if ($this->force_mentale >= 8) {
            $forts[] = 'Mental solide';
        }
        if ($this->jeu_filet >= 7) {
            $forts[] = 'Jeu au filet';
        }
        if ($this->reactivite >= 8) {
            $forts[] = 'Réactivité';
        }

        return $forts;
    }

    private function getPointsFaibles()
    {
        $faibles = [];

        if ($this->vitesse_service_max < 160) {
            $faibles[] = 'Service manque puissance';
        }
        if ($this->endurance_niveau <= 4) {
            $faibles[] = 'Endurance limitée';
        }
        if ($this->force_mentale <= 4) {
            $faibles[] = 'Fragilité mentale';
        }
        if ($this->jeu_filet <= 4) {
            $faibles[] = 'Jeu au filet faible';
        }
        if ($this->gestion_pression <= 4) {
            $faibles[] = 'Gestion pression';
        }

        return $faibles;
    }

    // Autres méthodes privées helper...
    private function getSerieActuelle($matchs)
    { /* ... */ return 0;
    }

    private function getQualiteAdversairesRecents($matchs)
    { /* ... */ return 'moyenne';
    }

    private function getPourcentageVictoiresSurface($surface)
    { /* ... */ return 50;
    }

    private function getAvantageSurface($surface)
    { /* ... */ return 0;
    }

    private function getFacteurH2H($adversaireId)
    { /* ... */ return 0;
    }

    private function getAdaptationConditions($conditions)
    { /* ... */ return 0;
    }

    private function getImpactBlessures()
    { /* ... */ return 0;
    }

    private function getAjustementSurface()
    { /* ... */ return 0;
    }

    private function getAjustementConditions()
    { /* ... */ return 0;
    }

    // ===================================================================
    // VALIDATION RULES
    // ===================================================================

    public static function validationRules()
    {
        return [
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'pays_id' => 'required|exists:pays,id',
            'date_naissance' => 'required|date|before:today',
            'sexe' => 'required|in:M,F',
            'main' => 'required|in:droitier,gaucher',
            'revers' => 'required|in:une_main,deux_mains',
            'taille' => 'required|integer|between:150,230',
            'poids' => 'required|integer|between:50,150',
            'classement_atp_wta' => 'nullable|integer|min:1',
            'statut' => 'required|in:actif,inactif,retraite,suspendu',
            'style_jeu_principal' => 'nullable|in:baseline,serve_volley,all_court,counterpuncher',
        ];
    }

    // ===================================================================
    // BOOT METHODS
    // ===================================================================

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($joueur) {
            // Auto-calculs
            if ($joueur->taille && $joueur->poids) {
                $joueur->imc = round($joueur->poids / pow($joueur->taille / 100, 2), 2);
            }

            if ($joueur->date_debut_pro) {
                $joueur->annees_experience = $joueur->date_debut_pro->diffInYears(now());
            }

            // Déterminer phase carrière
            if ($joueur->age) {
                if ($joueur->age < 25) {
                    $joueur->phase_carriere = 'montee';
                } elseif ($joueur->age < 30) {
                    $joueur->phase_carriere = 'pic';
                } elseif ($joueur->age < 33) {
                    $joueur->phase_carriere = 'plateau';
                } else {
                    $joueur->phase_carriere = 'declin';
                }
            }

            // Valeurs par défaut
            if ($joueur->actif === null) {
                $joueur->actif = true;
            }
            if (! $joueur->elo_rating_global) {
                $joueur->elo_rating_global = 1500;
            }
        });
    }
}
