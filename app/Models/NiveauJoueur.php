<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class NiveauJoueur extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'niveau_joueurs';

    protected $fillable = [
        // Identification
        'nom',
        'nom_officiel',           // Nom officiel ATP/WTA/ITF
        'nom_court',              // Abréviation
        'code',                   // Code unique niveau
        'description',
        'description_detaillee',  // Description complète du niveau
        'synonymes',              // JSON autres appellations

        // Classification hiérarchique
        'hierarchie_niveau',      // 1-20 niveau hiérarchique
        'categorie_principale',   // 'professionnel', 'semi_pro', 'amateur', 'junior'
        'sous_categorie',         // 'elite', 'regional', 'national', 'international'
        'circuit_principal',      // 'atp', 'wta', 'itf', 'challengers', 'futures'
        'division',               // Division ou sous-niveau si applicable

        // Critères de classement
        'classement_min',         // Classement minimum requis
        'classement_max',         // Classement maximum du niveau
        'points_min',             // Points minimum requis
        'points_max',             // Points maximum du niveau
        'classement_special',     // Classement spécial (ex: protected ranking)
        'criteres_alternatifs',   // JSON critères alternatifs

        // Caractéristiques âge
        'age_min',                // Âge minimum
        'age_max',                // Âge maximum
        'age_optimal',            // Âge optimal pour ce niveau
        'age_moyen',              // Âge moyen des joueurs
        'categories_age',         // JSON catégories d'âge
        'restrictions_age',       // Restrictions d'âge spéciales

        // Performance et standards
        'niveau_technique',       // 1-10 niveau technique requis
        'niveau_physique',        // 1-10 niveau physique requis
        'niveau_mental',          // 1-10 niveau mental requis
        'niveau_tactique',        // 1-10 niveau tactique requis
        'experience_requise',     // Années expérience requises
        'matchs_min_saison',      // Matchs minimum par saison

        // Compétences techniques
        'vitesse_service_min',    // Vitesse service minimum (km/h)
        'vitesse_service_moy',    // Vitesse service moyenne attendue
        'precision_service_min',  // Précision service minimum (%)
        'endurance_requise',      // Niveau endurance 1-10
        'force_requise',          // Niveau force 1-10
        'agilite_requise',        // Niveau agilité 1-10

        // Surfaces et conditions
        'surfaces_maitrisees',    // JSON surfaces à maîtriser
        'adaptabilite_surfaces',  // Capacité adaptation surfaces 1-10
        'conditions_jeu',         // JSON conditions de jeu requises
        'tolerance_conditions',   // Tolérance conditions difficiles 1-10

        // Aspects financiers
        'prize_money_min',        // Prize money minimum annuel
        'prize_money_moyen',      // Prize money moyen du niveau
        'cout_participation',     // Coût participation tournois
        'sponsoring_typique',     // Niveau sponsoring typique
        'salaire_moyen',          // Salaire/revenus moyens
        'investissement_requis',  // Investissement formation requis

        // Tournois et compétitions
        'tournois_accessibles',   // JSON tournois accessibles
        'tournois_obligatoires',  // JSON tournois obligatoires
        'nb_tournois_min',        // Nombre minimum tournois/an
        'nb_tournois_max',        // Nombre maximum tournois/an
        'wild_cards_eligibles',   // Éligible aux wild cards
        'qualifications_requises', // Qualifications obligatoires

        // Formation et encadrement
        'entraineur_requis',      // Entraîneur professionnel requis
        'equipe_support_min',     // Équipe support minimum
        'formation_continue',     // Formation continue requise
        'certifications',         // JSON certifications requises
        'academie_recommandee',   // Académie/centre recommandé
        'mentoring_disponible',   // Mentoring disponible

        // Progression de carrière
        'niveau_precedent_id',    // Niveau précédent dans progression
        'niveau_suivant_id',      // Niveau suivant dans progression
        'duree_typique',          // Durée typique dans ce niveau (mois)
        'taux_progression',       // % progression vers niveau sup (%)
        'criteres_promotion',     // JSON critères promotion
        'criteres_relegation',    // JSON critères rétrogradation

        // Support institutionnel
        'federation_support',     // Support fédération nationale
        'aide_financiere',        // Aide financière disponible
        'bourse_disponible',      // Bourses disponibles
        'assurance_couverte',     // Assurance couverte
        'protection_sociale',     // Protection sociale
        'droits_syndicaux',       // Droits syndicaux

        // Obligations et responsabilités
        'obligations_media',      // Obligations médiatiques
        'controles_antidopage',   // Contrôles antidopage requis
        'code_conduite',          // Code de conduite applicable
        'transparence_revenus',   // Transparence revenus requise
        'responsabilites_sociales', // Responsabilités sociales

        // Lifestyle et contraintes
        'voyages_requis',         // Voyages requis (jours/an)
        'flexibilite_calendrier', // Flexibilité calendrier 1-10
        'pression_media',         // Niveau pression médiatique 1-10
        'pression_performance',   // Pression performance 1-10
        'equilibre_vie_privee',   // Équilibre vie privée 1-10
        'stress_niveau',          // Niveau stress typique 1-10

        // Développement et opportunités
        'opportunites_carriere',  // JSON opportunités après-carrière
        'reconversion_facilitee', // Reconversion facilitée
        'reseau_professionnel',   // Accès réseau professionnel
        'visibilite_media',       // Visibilité médiatique 1-10
        'influence_sociale',      // Influence sociale 1-10
        'legacy_potentiel',       // Potentiel héritage sportif 1-10

        // Risques et défis
        'risque_blessure',        // Niveau risque blessure 1-10
        'risque_burnout',         // Risque épuisement 1-10
        'instabilite_revenus',    // Instabilité revenus 1-10
        'concurrence_niveau',     // Niveau concurrence 1-10
        'pression_resultats',     // Pression résultats 1-10
        'defis_principaux',       // JSON défis principaux

        // Statistiques niveau
        'nb_joueurs_mondial',     // Nombre joueurs mondialement
        'nb_joueurs_actifs',      // Nombre joueurs actifs
        'taux_renouvellement',    // Taux renouvellement annuel (%)
        'duree_carriere_moy',     // Durée carrière moyenne (années)
        'pic_carriere_age',       // Âge pic carrière moyen
        'retraite_age_moy',       // Âge retraite moyen

        // Diversité et inclusion
        'representation_femmes',  // % représentation femmes
        'diversite_geographique', // Diversité géographique 1-10
        'accessibilite_sociale',  // Accessibilité sociale 1-10
        'programmes_inclusion',   // Programmes inclusion disponibles
        'barrières_entree',       // JSON barrières à l'entrée
        'initiatives_diversite',  // JSON initiatives diversité

        // Innovation et évolution
        'evolution_technologique', // Adoption technologie 1-10
        'innovation_entrainement', // Innovation entraînement 1-10
        'data_analytics_usage',   // Usage analytics données 1-10
        'professionnalisation',   // Niveau professionnalisation 1-10
        'adaptation_changements', // Adaptation changements 1-10

        // Santé et bien-être
        'support_medical',        // Support médical 1-10
        'suivi_psychologique',    // Suivi psychologique disponible
        'prevention_blessures',   // Programmes prévention 1-10
        'nutrition_encadree',     // Nutrition encadrée
        'recovery_protocols',     // Protocoles récupération
        'wellness_programs',      // Programmes bien-être

        // Performance et métriques
        'winrate_moyen',          // Taux victoire moyen (%)
        'consistency_requise',    // Consistance requise 1-10
        'clutch_ability',         // Capacité moments cruciaux 1-10
        'adaptability_score',     // Score adaptabilité 1-10
        'mental_toughness',       // Résistance mentale 1-10
        'learning_curve',         // Courbe apprentissage 1-10

        // Mesures et évaluation
        'metriques_evaluation',   // JSON métriques évaluation
        'kpis_principaux',        // JSON KPIs principaux
        'benchmarks_secteur',     // JSON benchmarks secteur
        'standards_qualite',      // Standards qualité requis
        'certifications_niveau',  // Certifications du niveau
        'audits_reguliers',       // Audits réguliers requis

        // Communication et image
        'communication_skills',   // Compétences communication 1-10
        'image_publique',         // Gestion image publique 1-10
        'social_media_presence',  // Présence réseaux sociaux 1-10
        'fan_engagement',         // Engagement fans 1-10
        'brand_value',            // Valeur marque 1-10
        'marketability',          // Potentiel marketing 1-10

        // Gouvernance et régulation
        'organisme_regulation',   // Organisme de régulation
        'regles_specifiques',     // JSON règles spécifiques
        'sanctions_possibles',    // JSON sanctions possibles
        'appels_procedures',      // Procédures d'appel
        'transparence_niveau',    // Niveau transparence 1-10
        'accountability',         // Responsabilité/redevabilité 1-10

        // Métadonnées et gestion
        'date_creation_niveau',   // Date création du niveau
        'derniere_revision',      // Dernière révision critères
        'prochaine_revision',     // Prochaine révision prévue
        'stabilite_criteres',     // Stabilité critères 1-10
        'evolution_prevue',       // JSON évolutions prévues
        'obsolescence_risque',    // Risque obsolescence 1-10

        // Système et intégration
        'compatibilite_systemes', // Compatibilité autres systèmes
        'integration_facilite',   // Facilité intégration 1-10
        'api_disponibles',        // APIs disponibles
        'data_quality',           // Qualité données 1-10
        'maintenance_niveau',     // Niveau maintenance requis 1-10
        'support_technique',      // Support technique disponible

        'couleur_affichage',      // Couleur pour UI
        'icone',                  // Icône représentative
        'ordre_affichage',        // Ordre affichage
        'popularite',             // Popularité 1-10
        'prestige',               // Prestige 1-10
        'actif'
    ];

    protected $casts = [
        // JSON
        'synonymes' => 'json',
        'criteres_alternatifs' => 'json',
        'categories_age' => 'json',
        'surfaces_maitrisees' => 'json',
        'conditions_jeu' => 'json',
        'tournois_accessibles' => 'json',
        'tournois_obligatoires' => 'json',
        'certifications' => 'json',
        'criteres_promotion' => 'json',
        'criteres_relegation' => 'json',
        'opportunites_carriere' => 'json',
        'defis_principaux' => 'json',
        'barrières_entree' => 'json',
        'initiatives_diversite' => 'json',
        'metriques_evaluation' => 'json',
        'kpis_principaux' => 'json',
        'benchmarks_secteur' => 'json',
        'regles_specifiques' => 'json',
        'sanctions_possibles' => 'json',
        'evolution_prevue' => 'json',

        // Entiers
        'hierarchie_niveau' => 'integer',
        'classement_min' => 'integer',
        'classement_max' => 'integer',
        'points_min' => 'integer',
        'points_max' => 'integer',
        'age_min' => 'integer',
        'age_max' => 'integer',
        'age_optimal' => 'integer',
        'age_moyen' => 'integer',
        'niveau_technique' => 'integer',
        'niveau_physique' => 'integer',
        'niveau_mental' => 'integer',
        'niveau_tactique' => 'integer',
        'experience_requise' => 'integer',
        'matchs_min_saison' => 'integer',
        'vitesse_service_min' => 'integer',
        'vitesse_service_moy' => 'integer',
        'endurance_requise' => 'integer',
        'force_requise' => 'integer',
        'agilite_requise' => 'integer',
        'adaptabilite_surfaces' => 'integer',
        'tolerance_conditions' => 'integer',
        'nb_tournois_min' => 'integer',
        'nb_tournois_max' => 'integer',
        'niveau_precedent_id' => 'integer',
        'niveau_suivant_id' => 'integer',
        'duree_typique' => 'integer',
        'voyages_requis' => 'integer',
        'flexibilite_calendrier' => 'integer',
        'pression_media' => 'integer',
        'pression_performance' => 'integer',
        'equilibre_vie_privee' => 'integer',
        'stress_niveau' => 'integer',
        'visibilite_media' => 'integer',
        'influence_sociale' => 'integer',
        'legacy_potentiel' => 'integer',
        'risque_blessure' => 'integer',
        'risque_burnout' => 'integer',
        'instabilite_revenus' => 'integer',
        'concurrence_niveau' => 'integer',
        'pression_resultats' => 'integer',
        'nb_joueurs_mondial' => 'integer',
        'nb_joueurs_actifs' => 'integer',
        'duree_carriere_moy' => 'integer',
        'pic_carriere_age' => 'integer',
        'retraite_age_moy' => 'integer',
        'diversite_geographique' => 'integer',
        'accessibilite_sociale' => 'integer',
        'evolution_technologique' => 'integer',
        'innovation_entrainement' => 'integer',
        'data_analytics_usage' => 'integer',
        'professionnalisation' => 'integer',
        'adaptation_changements' => 'integer',
        'support_medical' => 'integer',
        'prevention_blessures' => 'integer',
        'consistency_requise' => 'integer',
        'clutch_ability' => 'integer',
        'adaptability_score' => 'integer',
        'mental_toughness' => 'integer',
        'learning_curve' => 'integer',
        'communication_skills' => 'integer',
        'image_publique' => 'integer',
        'social_media_presence' => 'integer',
        'fan_engagement' => 'integer',
        'brand_value' => 'integer',
        'marketability' => 'integer',
        'transparence_niveau' => 'integer',
        'accountability' => 'integer',
        'stabilite_criteres' => 'integer',
        'obsolescence_risque' => 'integer',
        'integration_facilite' => 'integer',
        'data_quality' => 'integer',
        'maintenance_niveau' => 'integer',
        'ordre_affichage' => 'integer',
        'popularite' => 'integer',
        'prestige' => 'integer',

        // Décimaux
        'precision_service_min' => 'decimal:1',
        'prize_money_min' => 'decimal:2',
        'prize_money_moyen' => 'decimal:2',
        'cout_participation' => 'decimal:2',
        'sponsoring_typique' => 'decimal:2',
        'salaire_moyen' => 'decimal:2',
        'investissement_requis' => 'decimal:2',
        'taux_progression' => 'decimal:1',
        'taux_renouvellement' => 'decimal:1',
        'representation_femmes' => 'decimal:1',
        'winrate_moyen' => 'decimal:1',

        // Booléens
        'wild_cards_eligibles' => 'boolean',
        'entraineur_requis' => 'boolean',
        'formation_continue' => 'boolean',
        'mentoring_disponible' => 'boolean',
        'federation_support' => 'boolean',
        'aide_financiere' => 'boolean',
        'bourse_disponible' => 'boolean',
        'assurance_couverte' => 'boolean',
        'protection_sociale' => 'boolean',
        'droits_syndicaux' => 'boolean',
        'obligations_media' => 'boolean',
        'controles_antidopage' => 'boolean',
        'transparence_revenus' => 'boolean',
        'reconversion_facilitee' => 'boolean',
        'reseau_professionnel' => 'boolean',
        'suivi_psychologique' => 'boolean',
        'nutrition_encadree' => 'boolean',
        'audits_reguliers' => 'boolean',
        'support_technique' => 'boolean',
        'actif' => 'boolean',

        // Dates
        'date_creation_niveau' => 'date',
        'derniere_revision' => 'date',
        'prochaine_revision' => 'date'
    ];

    protected $appends = [
        'niveau_difficulte_global',
        'score_attractivite',
        'profil_joueur_type',
        'avantages_principaux',
        'challenges_principaux',
        'trajectoire_carriere',
        'support_ecosystem',
        'requirements_summary'
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function joueurs()
    {
        return $this->hasMany(Joueur::class, 'niveau_joueur_id');
    }

    public function joueursActifs()
    {
        return $this->hasMany(Joueur::class, 'niveau_joueur_id')
            ->where('statut', 'actif');
    }

    public function niveauPrecedent()
    {
        return $this->belongsTo(NiveauJoueur::class, 'niveau_precedent_id');
    }

    public function niveauSuivant()
    {
        return $this->belongsTo(NiveauJoueur::class, 'niveau_suivant_id');
    }

    public function progressions()
    {
        return $this->hasMany(ProgressionNiveau::class, 'niveau_actuel_id');
    }

    public function evaluations()
    {
        return $this->hasMany(EvaluationNiveau::class, 'niveau_joueur_id');
    }

    public function statistiques()
    {
        return $this->hasMany(StatistiqueNiveau::class, 'niveau_joueur_id');
    }

    public function criteres()
    {
        return $this->hasMany(CritereNiveau::class, 'niveau_joueur_id');
    }

    // ===================================================================
    // ACCESSORS
    // ===================================================================

    public function getNiveauDifficulteGlobalAttribute()
    {
        $composantes = [
            $this->niveau_technique ?? 5,
            $this->niveau_physique ?? 5,
            $this->niveau_mental ?? 5,
            $this->niveau_tactique ?? 5,
            $this->concurrence_niveau ?? 5,
            $this->pression_resultats ?? 5
        ];

        $moyenne = array_sum($composantes) / count($composantes);

        if ($moyenne >= 9) return 'Extrême';
        if ($moyenne >= 7) return 'Très élevé';
        if ($moyenne >= 5) return 'Élevé';
        if ($moyenne >= 3) return 'Modéré';
        return 'Accessible';
    }

    public function getScoreAttractiviteAttribute()
    {
        $facteurs = [
            'prestige' => ($this->prestige ?? 5) * 2,
            'revenus' => min(20, ($this->salaire_moyen ?? 50000) / 10000),
            'equilibre_vie' => ($this->equilibre_vie_privee ?? 5) * 1.5,
            'opportunites' => count($this->opportunites_carriere ?? []) * 2,
            'support' => ($this->support_medical ?? 5) * 1.2,
            'visibilite' => ($this->visibilite_media ?? 5) * 1.3
        ];

        return round(array_sum($facteurs) / 6, 1);
    }

    public function getProfilJoueurTypeAttribute()
    {
        $profil = [];

        // Âge
        if ($this->age_optimal) {
            $profil['age_optimal'] = $this->age_optimal . ' ans';
        }

        // Niveau technique
        $niveaux = ['Débutant', 'Amateur', 'Confirmé', 'Avancé', 'Expert',
            'Semi-pro', 'Professionnel', 'Elite', 'Monde', 'Légende'];
        $profil['niveau_technique'] = $niveaux[$this->niveau_technique - 1] ?? 'Non défini';

        // Expérience
        if ($this->experience_requise) {
            $profil['experience'] = $this->experience_requise . ' ans minimum';
        }

        // Classification
        $profil['classification'] = $this->categorie_principale;

        return $profil;
    }

    public function getAvantagesPrincipauxAttribute()
    {
        $avantages = [];

        if ($this->prize_money_moyen > 100000) {
            $avantages[] = 'Revenus substantiels';
        }

        if ($this->prestige >= 8) {
            $avantages[] = 'Prestige élevé';
        }

        if ($this->support_medical >= 8) {
            $avantages[] = 'Support médical excellent';
        }

        if ($this->visibilite_media >= 7) {
            $avantages[] = 'Visibilité médiatique';
        }

        if ($this->reseau_professionnel) {
            $avantages[] = 'Accès réseau professionnel';
        }

        if ($this->reconversion_facilitee) {
            $avantages[] = 'Reconversion facilitée';
        }

        return $avantages;
    }

    public function getChallengesPrincipauxAttribute()
    {
        $challenges = [];

        if ($this->pression_resultats >= 8) {
            $challenges[] = 'Pression résultats intense';
        }

        if ($this->concurrence_niveau >= 8) {
            $challenges[] = 'Concurrence très élevée';
        }

        if ($this->instabilite_revenus >= 7) {
            $challenges[] = 'Instabilité financière';
        }

        if ($this->risque_blessure >= 7) {
            $challenges[] = 'Risque blessures élevé';
        }

        if ($this->voyages_requis > 200) {
            $challenges[] = 'Voyages intensifs';
        }

        if ($this->equilibre_vie_privee <= 4) {
            $challenges[] = 'Équilibre vie privée difficile';
        }

        return $challenges;
    }

    public function getTrajectoireCarriereAttribute()
    {
        $trajectoire = [];

        if ($this->niveauPrecedent) {
            $trajectoire['depuis'] = $this->niveauPrecedent->nom;
        }

        $trajectoire['actuel'] = $this->nom;
        $trajectoire['duree_typique'] = ($this->duree_typique ?? 12) . ' mois';

        if ($this->niveauSuivant) {
            $trajectoire['progression_vers'] = $this->niveauSuivant->nom;
            $trajectoire['taux_progression'] = ($this->taux_progression ?? 0) . '%';
        }

        if ($this->pic_carriere_age) {
            $trajectoire['pic_carriere'] = $this->pic_carriere_age . ' ans';
        }

        return $trajectoire;
    }

    public function getSupportEcosystemAttribute()
    {
        $support = [];

        if ($this->federation_support) {
            $support[] = 'Support fédération';
        }

        if ($this->aide_financiere) {
            $support[] = 'Aide financière';
        }

        if ($this->mentoring_disponible) {
            $support[] = 'Mentoring';
        }

        if ($this->suivi_psychologique) {
            $support[] = 'Suivi psychologique';
        }

        if ($this->nutrition_encadree) {
            $support[] = 'Nutrition encadrée';
        }

        return $support;
    }

    public function getRequirementsSummaryAttribute()
    {
        return [
            'classement' => $this->classement_min ?
                "Top {$this->classement_min}" . ($this->classement_max ? " - {$this->classement_max}" : '') :
                'Non défini',
            'age' => ($this->age_min ?? 'N/A') . ' - ' . ($this->age_max ?? 'N/A') . ' ans',
            'experience' => ($this->experience_requise ?? 0) . ' ans minimum',
            'niveau_physique' => ($this->niveau_physique ?? 0) . '/10',
            'niveau_technique' => ($this->niveau_technique ?? 0) . '/10',
            'matchs_saison' => ($this->matchs_min_saison ?? 0) . '+ matchs/an',
            'investissement' => number_format($this->investissement_requis ?? 0, 0, ',', ' ') . '€'
        ];
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

    public function scopeProfessionnels($query)
    {
        return $query->where('categorie_principale', 'professionnel');
    }

    public function scopeAmateurs($query)
    {
        return $query->where('categorie_principale', 'amateur');
    }

    public function scopeJuniors($query)
    {
        return $query->where('categorie_principale', 'junior');
    }

    public function scopeElite($query)
    {
        return $query->where('sous_categorie', 'elite');
    }

    public function scopeParCircuit($query, $circuit)
    {
        return $query->where('circuit_principal', $circuit);
    }

    public function scopeAccessibles($query, $age = null, $classement = null)
    {
        $q = $query;

        if ($age) {
            $q->where(function($subQuery) use ($age) {
                $subQuery->where('age_min', '<=', $age)
                    ->where('age_max', '>=', $age);
            });
        }

        if ($classement) {
            $q->where(function($subQuery) use ($classement) {
                $subQuery->whereNull('classement_min')
                    ->orWhere('classement_min', '>=', $classement);
            });
        }

        return $q;
    }

    public function scopePrestigieux($query)
    {
        return $query->where('prestige', '>=', 8);
    }

    public function scopeAvecSupport($query)
    {
        return $query->where('support_medical', '>=', 7)
            ->where('federation_support', true);
    }

    public function scopeProgression($query)
    {
        return $query->whereNotNull('niveau_suivant_id')
            ->where('taux_progression', '>', 10);
    }

    public function scopeOrdonnes($query)
    {
        return $query->orderBy('hierarchie_niveau')
            ->orderBy('prestige', 'desc')
            ->orderBy('nom');
    }

    public function scopeRecherche($query, $terme)
    {
        return $query->where(function($q) use ($terme) {
            $q->where('nom', 'LIKE', "%{$terme}%")
                ->orWhere('code', 'LIKE', "%{$terme}%")
                ->orWhere('categorie_principale', 'LIKE', "%{$terme}%")
                ->orWhere('circuit_principal', 'LIKE', "%{$terme}%");
        });
    }

    // ===================================================================
    // STATIC METHODS
    // ===================================================================

    /**
     * Créer les niveaux tennis standard
     */
    public static function creerNiveauxStandard()
    {
        $niveaux = [
            [
                'nom' => 'ATP Top 10',
                'nom_officiel' => 'ATP Tour Top 10',
                'code' => 'atp_top10',
                'hierarchie_niveau' => 20,
                'categorie_principale' => 'professionnel',
                'sous_categorie' => 'elite',
                'circuit_principal' => 'atp',
                'classement_min' => 1,
                'classement_max' => 10,
                'points_min' => 3000,
                'age_optimal' => 27,
                'niveau_technique' => 10,
                'niveau_physique' => 10,
                'niveau_mental' => 10,
                'niveau_tactique' => 10,
                'prize_money_moyen' => 8000000,
                'prestige' => 10,
                'visibilite_media' => 10,
                'pression_resultats' => 10,
                'concurrence_niveau' => 10,
                'support_medical' => 10,
                'entraineur_requis' => true,
                'nb_joueurs_mondial' => 10
            ],
            [
                'nom' => 'ATP Top 50',
                'nom_officiel' => 'ATP Tour Top 50',
                'code' => 'atp_top50',
                'hierarchie_niveau' => 19,
                'categorie_principale' => 'professionnel',
                'sous_categorie' => 'elite',
                'circuit_principal' => 'atp',
                'classement_min' => 11,
                'classement_max' => 50,
                'points_min' => 1200,
                'age_optimal' => 26,
                'niveau_technique' => 9,
                'niveau_physique' => 9,
                'niveau_mental' => 9,
                'niveau_tactique' => 9,
                'prize_money_moyen' => 2500000,
                'prestige' => 9,
                'visibilite_media' => 9,
                'pression_resultats' => 9,
                'concurrence_niveau' => 9,
                'support_medical' => 9,
                'entraineur_requis' => true,
                'nb_joueurs_mondial' => 40
            ],
            [
                'nom' => 'ATP Top 100',
                'nom_officiel' => 'ATP Tour Top 100',
                'code' => 'atp_top100',
                'hierarchie_niveau' => 18,
                'categorie_principale' => 'professionnel',
                'sous_categorie' => 'elite',
                'circuit_principal' => 'atp',
                'classement_min' => 51,
                'classement_max' => 100,
                'points_min' => 600,
                'age_optimal' => 25,
                'niveau_technique' => 9,
                'niveau_physique' => 8,
                'niveau_mental' => 8,
                'niveau_tactique' => 8,
                'prize_money_moyen' => 800000,
                'prestige' => 8,
                'visibilite_media' => 7,
                'pression_resultats' => 8,
                'concurrence_niveau' => 8,
                'support_medical' => 8,
                'entraineur_requis' => true,
                'nb_joueurs_mondial' => 50
            ],
            [
                'nom' => 'ATP Professional',
                'nom_officiel' => 'ATP Tour Professional',
                'code' => 'atp_pro',
                'hierarchie_niveau' => 17,
                'categorie_principale' => 'professionnel',
                'sous_categorie' => 'international',
                'circuit_principal' => 'atp',
                'classement_min' => 101,
                'classement_max' => 300,
                'points_min' => 150,
                'age_optimal' => 24,
                'niveau_technique' => 8,
                'niveau_physique' => 8,
                'niveau_mental' => 7,
                'niveau_tactique' => 7,
                'prize_money_moyen' => 300000,
                'prestige' => 7,
                'visibilite_media' => 5,
                'pression_resultats' => 7,
                'concurrence_niveau' => 7,
                'support_medical' => 7,
                'entraineur_requis' => true,
                'nb_joueurs_mondial' => 200
            ],
            [
                'nom' => 'Challengers',
                'nom_officiel' => 'ATP Challenger Tour',
                'code' => 'challengers',
                'hierarchie_niveau' => 16,
                'categorie_principale' => 'professionnel',
                'sous_categorie' => 'national',
                'circuit_principal' => 'challengers',
                'classement_min' => 301,
                'classement_max' => 800,
                'points_min' => 50,
                'age_optimal' => 23,
                'niveau_technique' => 7,
                'niveau_physique' => 7,
                'niveau_mental' => 6,
                'niveau_tactique' => 6,
                'prize_money_moyen' => 80000,
                'prestige' => 6,
                'visibilite_media' => 3,
                'pression_resultats' => 6,
                'concurrence_niveau' => 6,
                'support_medical' => 6,
                'entraineur_requis' => true,
                'nb_joueurs_mondial' => 500
            ],
            [
                'nom' => 'ITF Futures',
                'nom_officiel' => 'ITF World Tennis Tour',
                'code' => 'itf_futures',
                'hierarchie_niveau' => 15,
                'categorie_principale' => 'semi_pro',
                'sous_categorie' => 'international',
                'circuit_principal' => 'itf',
                'classement_min' => 801,
                'classement_max' => 2000,
                'points_min' => 1,
                'age_optimal' => 22,
                'niveau_technique' => 6,
                'niveau_physique' => 6,
                'niveau_mental' => 5,
                'niveau_tactique' => 5,
                'prize_money_moyen' => 25000,
                'prestige' => 5,
                'visibilite_media' => 2,
                'pression_resultats' => 5,
                'concurrence_niveau' => 5,
                'support_medical' => 4,
                'entraineur_requis' => false,
                'nb_joueurs_mondial' => 1200
            ],
            [
                'nom' => 'National Elite',
                'nom_officiel' => 'Elite National Level',
                'code' => 'national_elite',
                'hierarchie_niveau' => 14,
                'categorie_principale' => 'semi_pro',
                'sous_categorie' => 'national',
                'circuit_principal' => 'national',
                'classement_min' => 2001,
                'age_optimal' => 21,
                'niveau_technique' => 6,
                'niveau_physique' => 5,
                'niveau_mental' => 5,
                'niveau_tactique' => 5,
                'prize_money_moyen' => 10000,
                'prestige' => 4,
                'visibilite_media' => 2,
                'pression_resultats' => 4,
                'concurrence_niveau' => 4,
                'support_medical' => 3,
                'federation_support' => true,
                'nb_joueurs_mondial' => 3000
            ],
            [
                'nom' => 'Regional Advanced',
                'nom_officiel' => 'Advanced Regional Level',
                'code' => 'regional_advanced',
                'hierarchie_niveau' => 13,
                'categorie_principale' => 'amateur',
                'sous_categorie' => 'regional',
                'circuit_principal' => 'regional',
                'age_optimal' => 20,
                'niveau_technique' => 5,
                'niveau_physique' => 5,
                'niveau_mental' => 4,
                'niveau_tactique' => 4,
                'prize_money_moyen' => 2000,
                'prestige' => 3,
                'visibilite_media' => 1,
                'pression_resultats' => 3,
                'concurrence_niveau' => 3,
                'support_medical' => 2,
                'nb_joueurs_mondial' => 10000
            ],
            [
                'nom' => 'Junior Elite',
                'nom_officiel' => 'Elite Junior Level',
                'code' => 'junior_elite',
                'hierarchie_niveau' => 12,
                'categorie_principale' => 'junior',
                'sous_categorie' => 'elite',
                'circuit_principal' => 'junior',
                'age_min' => 14,
                'age_max' => 18,
                'age_optimal' => 17,
                'niveau_technique' => 6,
                'niveau_physique' => 5,
                'niveau_mental' => 4,
                'niveau_tactique' => 4,
                'prestige' => 4,
                'visibilite_media' => 2,
                'pression_resultats' => 4,
                'concurrence_niveau' => 4,
                'support_medical' => 3,
                'formation_continue' => true,
                'nb_joueurs_mondial' => 5000
            ],
            [
                'nom' => 'Club Advanced',
                'nom_officiel' => 'Advanced Club Level',
                'code' => 'club_advanced',
                'hierarchie_niveau' => 10,
                'categorie_principale' => 'amateur',
                'sous_categorie' => 'local',
                'circuit_principal' => 'club',
                'age_optimal' => 25,
                'niveau_technique' => 4,
                'niveau_physique' => 4,
                'niveau_mental' => 3,
                'niveau_tactique' => 3,
                'prestige' => 2,
                'visibilite_media' => 1,
                'pression_resultats' => 2,
                'concurrence_niveau' => 2,
                'support_medical' => 1,
                'equilibre_vie_privee' => 8,
                'nb_joueurs_mondial' => 50000
            ]
        ];

        foreach ($niveaux as $index => $niveau) {
            // Valeurs par défaut communes
            $niveau['actif'] = true;
            $niveau['date_creation_niveau'] = now();
            $niveau['ordre_affichage'] = 20 - $index;
            $niveau['voyages_requis'] = max(0, ($niveau['hierarchie_niveau'] - 10) * 20);
            $niveau['duree_typique'] = max(12, 36 - $niveau['hierarchie_niveau']);
            $niveau['taux_progression'] = max(5, 50 - $niveau['hierarchie_niveau'] * 2);

            // Progression entre niveaux
            if ($index > 0) {
                $niveau['niveau_suivant_id'] = $index; // ID du niveau précédent dans la liste
            }
            if ($index < count($niveaux) - 1) {
                $niveau['niveau_precedent_id'] = $index + 2; // ID du niveau suivant
            }

            self::firstOrCreate(
                ['code' => $niveau['code']],
                $niveau
            );
        }
    }

    /**
     * Obtenir la hiérarchie complète
     */
    public static function getHierarchie()
    {
        return self::actifs()
            ->ordonnes()
            ->get()
            ->mapWithKeys(function($niveau) {
                return [$niveau->code => [
                    'nom' => $niveau->nom,
                    'hierarchie' => $niveau->hierarchie_niveau,
                    'prestige' => $niveau->prestige,
                    'categorie' => $niveau->categorie_principale
                ]];
            });
    }

    /**
     * Obtenir les métriques globales
     */
    public static function getMetriquesGlobales()
    {
        return [
            'nb_niveaux_total' => self::count(),
            'nb_niveaux_actifs' => self::actifs()->count(),
            'nb_professionnels' => self::professionnels()->count(),
            'nb_amateurs' => self::amateurs()->count(),
            'nb_juniors' => self::juniors()->count(),
            'prestige_moyen' => self::avg('prestige'),
            'niveau_plus_prestigieux' => self::orderBy('prestige', 'desc')->first(),
            'joueurs_total' => Joueur::count(),
            'repartition_categories' => self::selectRaw('categorie_principale, COUNT(*) as nb')
                ->groupBy('categorie_principale')
                ->pluck('nb', 'categorie_principale')
        ];
    }

    // ===================================================================
    // METHODS
    // ===================================================================

    /**
     * Évaluer si un joueur peut accéder à ce niveau
     */
    public function peutAcceder($joueur)
    {
        $criteres = [];
        $respecte = true;

        // Âge
        if ($this->age_min && $joueur->age < $this->age_min) {
            $criteres[] = "Trop jeune (min: {$this->age_min} ans)";
            $respecte = false;
        }
        if ($this->age_max && $joueur->age > $this->age_max) {
            $criteres[] = "Trop âgé (max: {$this->age_max} ans)";
            $respecte = false;
        }

        // Classement
        if ($this->classement_min && $joueur->classement_atp_wta > $this->classement_min) {
            $criteres[] = "Classement insuffisant (requis: top {$this->classement_min})";
            $respecte = false;
        }

        // Points
        if ($this->points_min && $joueur->points_actuels < $this->points_min) {
            $criteres[] = "Points insuffisants (requis: {$this->points_min})";
            $respecte = false;
        }

        // Expérience
        if ($this->experience_requise && $joueur->annees_experience < $this->experience_requise) {
            $criteres[] = "Expérience insuffisante (requis: {$this->experience_requise} ans)";
            $respecte = false;
        }

        return [
            'peut_acceder' => $respecte,
            'criteres_non_respectes' => $criteres,
            'pourcentage_adequation' => $this->calculerAdequation($joueur),
            'recommendations' => $this->getRecommandationsAcces($joueur)
        ];
    }

    /**
     * Calculer le coût total pour atteindre ce niveau
     */
    public function calculerCoutTotal($dureeAnnees = 1)
    {
        return [
            'investissement_formation' => $this->investissement_requis ?? 0,
            'cout_participation_annuel' => ($this->cout_participation ?? 0) * $dureeAnnees,
            'cout_equipe_support' => $this->calculerCoutEquipe($dureeAnnees),
            'cout_voyages' => $this->calculerCoutVoyages($dureeAnnees),
            'total_estime' => $this->calculerCoutTotalEstime($dureeAnnees),
            'retour_investissement' => $this->calculerROI($dureeAnnees)
        ];
    }

    /**
     * Analyser les opportunités après carrière
     */
    public function getOpportunitesApresCarriere()
    {
        return [
            'entrainement' => $this->prestige >= 7,
            'commentaire_media' => $this->visibilite_media >= 6,
            'management_sportif' => $this->reseau_professionnel,
            'business_tennis' => $this->brand_value >= 6,
            'academie_creation' => $this->niveau_technique >= 8,
            'consulting' => $this->experience_requise >= 10,
            'federation_roles' => $this->prestige >= 8,
            'opportunites_specifiques' => $this->opportunites_carriere ?? []
        ];
    }

    /**
     * Générer un plan de progression vers ce niveau
     */
    public function genererPlanProgression($joueurActuel)
    {
        $niveauActuel = $joueurActuel->niveau;
        $etapes = [];

        // Collecter tous les niveaux intermédiaires
        $niveau = $niveauActuel;
        while ($niveau && $niveau->niveau_suivant_id !== $this->id) {
            $niveau = $niveau->niveauSuivant;
            if ($niveau) {
                $etapes[] = [
                    'niveau' => $niveau->nom,
                    'duree_estimee' => $niveau->duree_typique . ' mois',
                    'objectifs_cles' => $this->getObjectifsNiveau($niveau),
                    'taux_reussite' => $niveau->taux_progression . '%'
                ];
            }
        }

        return [
            'niveau_actuel' => $niveauActuel->nom,
            'niveau_cible' => $this->nom,
            'etapes_intermediaires' => $etapes,
            'duree_totale_estimee' => $this->calculerDureeTotale($etapes),
            'investissement_total' => $this->calculerInvestissementTotal($etapes),
            'probabilite_succes' => $this->calculerProbabiliteSucces($joueurActuel),
            'facteurs_critiques' => $this->getFacteursCritiques(),
            'recommandations' => $this->getRecommandationsProgression($joueurActuel)
        ];
    }

    /**
     * Comparer avec d'autres niveaux
     */
    public function comparerAvec($autreNiveau)
    {
        return [
            'prestige' => [
                'actuel' => $this->prestige,
                'compare' => $autreNiveau->prestige,
                'difference' => $this->prestige - $autreNiveau->prestige
            ],
            'difficulte' => [
                'actuel' => $this->niveau_difficulte_global,
                'compare' => $autreNiveau->niveau_difficulte_global
            ],
            'revenus' => [
                'actuel' => $this->prize_money_moyen,
                'compare' => $autreNiveau->prize_money_moyen,
                'ratio' => $autreNiveau->prize_money_moyen > 0 ?
                    round($this->prize_money_moyen / $autreNiveau->prize_money_moyen, 2) : 0
            ],
            'support' => [
                'actuel' => $this->support_medical,
                'compare' => $autreNiveau->support_medical
            ],
            'attractivite' => [
                'actuel' => $this->score_attractivite,
                'compare' => $autreNiveau->score_attractivite
            ]
        ];
    }

    // ===================================================================
    // METHODS PRIVÉES
    // ===================================================================

    private function calculerAdequation($joueur)
    {
        $score = 0;
        $criteres = 0;

        // Âge
        if ($this->age_optimal) {
            $ecartAge = abs($joueur->age - $this->age_optimal);
            $score += max(0, 100 - $ecartAge * 10);
            $criteres++;
        }

        // Classement
        if ($this->classement_min && $joueur->classement_atp_wta) {
            $score += $joueur->classement_atp_wta <= $this->classement_min ? 100 : 0;
            $criteres++;
        }

        // Expérience
        if ($this->experience_requise) {
            $score += $joueur->annees_experience >= $this->experience_requise ? 100 :
                ($joueur->annees_experience / $this->experience_requise) * 100;
            $criteres++;
        }

        return $criteres > 0 ? round($score / $criteres, 1) : 0;
    }

    private function getRecommandationsAcces($joueur)
    {
        $recommendations = [];

        if ($this->classement_min && $joueur->classement_atp_wta > $this->classement_min) {
            $recommendations[] = "Améliorer classement (actuellement {$joueur->classement_atp_wta}, requis: {$this->classement_min})";
        }

        if ($this->experience_requise && $joueur->annees_experience < $this->experience_requise) {
            $manque = $this->experience_requise - $joueur->annees_experience;
            $recommendations[] = "Acquérir {$manque} années d'expérience supplémentaires";
        }

        return $recommendations;
    }

    private function calculerCoutEquipe($duree)
    {
        $coutBase = 0;

        if ($this->entraineur_requis) $coutBase += 50000 * $duree;
        if ($this->support_medical >= 7) $coutBase += 30000 * $duree;
        if ($this->niveau_physique >= 8) $coutBase += 20000 * $duree; // Préparateur physique

        return $coutBase;
    }

    private function calculerCoutVoyages($duree)
    {
        return ($this->voyages_requis ?? 0) * 200 * $duree; // 200€ par jour voyage
    }

    private function calculerCoutTotalEstime($duree)
    {
        return ($this->investissement_requis ?? 0) +
            ($this->cout_participation ?? 0) * $duree +
            $this->calculerCoutEquipe($duree) +
            $this->calculerCoutVoyages($duree);
    }

    private function calculerROI($duree)
    {
        $cout = $this->calculerCoutTotalEstime($duree);
        $revenu = ($this->prize_money_moyen ?? 0) * $duree;

        return $cout > 0 ? round((($revenu - $cout) / $cout) * 100, 1) : 0;
    }

    private function getObjectifsNiveau($niveau)
    {
        return [
            'Classement cible: ' . ($niveau->classement_min ?? 'N/A'),
            'Points minimum: ' . number_format($niveau->points_min ?? 0),
            'Niveau technique: ' . ($niveau->niveau_technique ?? 0) . '/10',
            'Matchs par saison: ' . ($niveau->matchs_min_saison ?? 0)
        ];
    }

    private function calculerDureeTotale($etapes)
    {
        $total = 0;
        foreach ($etapes as $etape) {
            $total += (int) str_replace(' mois', '', $etape['duree_estimee']);
        }
        return $total . ' mois';
    }

    private function calculerInvestissementTotal($etapes)
    {
        // Simulation calcul investissement
        return count($etapes) * 50000;
    }

    private function calculerProbabiliteSucces($joueur)
    {
        $facteurs = [
            'age' => $joueur->age <= ($this->age_optimal ?? 25) ? 1 : 0.7,
            'forme' => ($joueur->forme_actuelle ?? 5) / 10,
            'classement' => $joueur->classement_atp_wta ?
                min(1, 1000 / $joueur->classement_atp_wta) : 0.5
        ];

        return round(array_product($facteurs) * 100, 1);
    }

    private function getFacteursCritiques()
    {
        $facteurs = [];

        if ($this->niveau_mental >= 8) $facteurs[] = 'Mental très important';
        if ($this->pression_resultats >= 8) $facteurs[] = 'Gestion pression cruciale';
        if ($this->concurrence_niveau >= 8) $facteurs[] = 'Concurrence intense';

        return $facteurs;
    }

    private function getRecommandationsProgression($joueur)
    {
        $recommendations = [];

        if ($this->niveau_physique > ($joueur->endurance_niveau ?? 5)) {
            $recommendations[] = 'Intensifier préparation physique';
        }

        if ($this->niveau_mental > ($joueur->force_mentale ?? 5)) {
            $recommendations[] = 'Travailler aspect mental avec psychologue';
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
            'code' => 'required|string|max:50|unique:niveau_joueurs,code',
            'hierarchie_niveau' => 'required|integer|min:1|max:20',
            'categorie_principale' => 'required|in:professionnel,semi_pro,amateur,junior',
            'circuit_principal' => 'nullable|in:atp,wta,itf,challengers,futures,national,regional,club,junior',
            'classement_min' => 'nullable|integer|min:1',
            'age_min' => 'nullable|integer|min:10|max:50',
            'age_max' => 'nullable|integer|min:10|max:50',
            'niveau_technique' => 'nullable|integer|min:1|max:10',
            'prestige' => 'nullable|integer|min:1|max:10'
        ];
    }

    // ===================================================================
    // BOOT METHODS
    // ===================================================================

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($niveau) {
            // Auto-calculs
            if (!$niveau->ordre_affichage) {
                $niveau->ordre_affichage = $niveau->hierarchie_niveau ?? 1;
            }

            // Calculer score attractivité si manquant
            if (!$niveau->score_attractivite) {
                $niveau->score_attractivite = $niveau->getScoreAttractiviteAttribute();
            }

            // Valeurs par défaut
            if ($niveau->actif === null) $niveau->actif = true;
            if (!$niveau->date_creation_niveau) $niveau->date_creation_niveau = now();
        });

        static::created(function ($niveau) {
            \Log::info("Nouveau niveau joueur créé: {$niveau->nom} (hiérarchie: {$niveau->hierarchie_niveau})");
        });
    }
}
