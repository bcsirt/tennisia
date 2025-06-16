<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Sponsor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'nom_complet',
        'logo',
        'logo_alt',
        'site_web',
        'description',
        'secteur_activite',
        'pays_origine',
        'chiffre_affaires_annuel',
        'budget_tennis_annuel',
        'statut_entreprise',
        'niveau_prestige',
        'visibilite_mondiale',
        'engagement_tennis',
        'historique_tennis',
        'politique_sponsoring',
        'objectifs_marketing',
        'cible_demographique',
        'retour_investissement_attendu',
        'duree_engagement_moyenne',
        'nb_joueurs_sponsorises',
        'nb_tournois_sponsorises',
        'nb_equipements_fournis',
        'type_sponsoring_prefere',
        'budget_par_joueur_moyen',
        'budget_par_joueur_max',
        'criteres_selection_joueurs',
        'bonus_performance_politique',
        'exigences_contractuelles',
        'flexibilite_contrats',
        'support_marketing_niveau',
        'support_technique_niveau',
        'reseaux_sociaux_push',
        'evenements_prives_organises',
        'formation_joueurs_offerte',
        'soutien_blessure_niveau',
        'tolerance_controverses',
        'image_marque_importance',
        'innovation_produits',
        'responsabilite_sociale',
        'note_reputation',
        'stabilite_financiere',
        'concurrents_principaux',
        'avantages_competitifs',
        'risques_identifies',
        'tendances_marche_position',
        'partenariats_strategiques',
        'presence_digitale_score',
        'influence_media_score',
        'satisfaction_joueurs_moyenne',
        'taux_renouvellement_contrats',
        'actif'
    ];

    protected $casts = [
        'chiffre_affaires_annuel' => 'decimal:2',
        'budget_tennis_annuel' => 'decimal:2',
        'niveau_prestige' => 'integer', // 1-10
        'visibilite_mondiale' => 'integer', // 1-10
        'engagement_tennis' => 'integer', // 1-10
        'retour_investissement_attendu' => 'decimal:2',
        'duree_engagement_moyenne' => 'decimal:1', // années
        'nb_joueurs_sponsorises' => 'integer',
        'nb_tournois_sponsorises' => 'integer',
        'nb_equipements_fournis' => 'integer',
        'budget_par_joueur_moyen' => 'decimal:2',
        'budget_par_joueur_max' => 'decimal:2',
        'criteres_selection_joueurs' => 'array',
        'bonus_performance_politique' => 'array',
        'exigences_contractuelles' => 'array',
        'flexibilite_contrats' => 'integer', // 1-10
        'support_marketing_niveau' => 'integer', // 1-10
        'support_technique_niveau' => 'integer', // 1-10
        'reseaux_sociaux_push' => 'integer', // 1-10
        'evenements_prives_organises' => 'integer',
        'formation_joueurs_offerte' => 'boolean',
        'soutien_blessure_niveau' => 'integer', // 1-10
        'tolerance_controverses' => 'integer', // 1-10
        'image_marque_importance' => 'integer', // 1-10
        'innovation_produits' => 'integer', // 1-10
        'responsabilite_sociale' => 'integer', // 1-10
        'note_reputation' => 'decimal:1', // 1-10
        'stabilite_financiere' => 'integer', // 1-10
        'concurrents_principaux' => 'array',
        'avantages_competitifs' => 'array',
        'risques_identifies' => 'array',
        'partenariats_strategiques' => 'array',
        'presence_digitale_score' => 'integer', // 1-10
        'influence_media_score' => 'integer', // 1-10
        'satisfaction_joueurs_moyenne' => 'decimal:1', // 1-10
        'taux_renouvellement_contrats' => 'decimal:2', // pourcentage
        'actif' => 'boolean'
    ];

    protected $appends = [
        'attractivite_sponsor',
        'impact_performance_estime',
        'score_fiabilite',
        'potentiel_croissance',
        'valeur_partenariat'
    ];

    // Relations
    public function contratsSponsorisation()
    {
        return $this->hasMany(ContratSponsorisation::class);
    }

    public function joueursActuels()
    {
        return $this->belongsToMany(Joueur::class, 'contrat_sponsorisations')
            ->wherePivot('statut_contrat', 'actif')
            ->wherePivot('date_fin', '>', now());
    }

    public function ancienJoueurs()
    {
        return $this->belongsToMany(Joueur::class, 'contrat_sponsorisations')
            ->wherePivot('statut_contrat', 'termine');
    }

    public function tournoisSponsorises()
    {
        return $this->belongsToMany(Tournoi::class, 'sponsor_tournois')
            ->withPivot('niveau_sponsoring', 'montant', 'annee');
    }

    public function equipementsFournis()
    {
        return $this->hasMany(EquipementSponsor::class);
    }

    public function evaluationsPerformance()
    {
        return $this->hasMany(EvaluationSponsor::class);
    }

    // Accessors pour les métriques calculées
    public function getAttractiviteSponsorAttribute()
    {
        // Score d'attractivité pour les joueurs
        $facteurs = [
            'prestige' => $this->niveau_prestige * 0.25,
            'budget' => min($this->budget_par_joueur_max / 1000000, 10) * 0.20,
            'support' => ($this->support_marketing_niveau + $this->support_technique_niveau) / 2 * 0.15,
            'flexibilite' => $this->flexibilite_contrats * 0.15,
            'reputation' => $this->note_reputation * 0.10,
            'stabilite' => $this->stabilite_financiere * 0.10,
            'satisfaction' => $this->satisfaction_joueurs_moyenne * 0.05
        ];

        return round(array_sum($facteurs), 1);
    }

    public function getImpactPerformanceEstimeAttribute()
    {
        // Impact estimé sur la performance des joueurs sponsorisés
        $impact_base = 0;

        // Impact positif du support
        $impact_positif = (
            ($this->support_technique_niveau / 10) * 0.03 +
            ($this->support_marketing_niveau / 10) * 0.01 +
            ($this->soutien_blessure_niveau / 10) * 0.02
        );

        // Impact négatif de la pression
        $pression_score = $this->calculerPressionContractuelle();
        $impact_negatif = ($pression_score / 10) * 0.02;

        $impact_net = $impact_positif - $impact_negatif;

        return round($impact_net, 3);
    }

    public function getScoreFiabiliteAttribute()
    {
        // Score de fiabilité comme partenaire
        $facteurs = [
            'stabilite_financiere' => $this->stabilite_financiere * 0.30,
            'taux_renouvellement' => ($this->taux_renouvellement_contrats / 100) * 10 * 0.25,
            'historique_tennis' => min($this->historique_tennis / 5, 10) * 0.20,
            'satisfaction_joueurs' => $this->satisfaction_joueurs_moyenne * 0.15,
            'reputation' => $this->note_reputation * 0.10
        ];

        return round(array_sum($facteurs), 1);
    }

    public function getPotentielCroissanceAttribute()
    {
        // Potentiel de croissance du sponsor
        $facteurs = [
            'innovation' => $this->innovation_produits,
            'presence_digitale' => $this->presence_digitale_score,
            'tendances_marche' => $this->tendances_marche_position ?? 5,
            'partenariats' => min(count($this->partenariats_strategiques ?? []), 10)
        ];

        return round(array_sum($facteurs) / count($facteurs), 1);
    }

    public function getValeurPartenariatAttribute()
    {
        // Valeur globale du partenariat
        return round((
            $this->attractivite_sponsor * 0.30 +
            $this->score_fiabilite * 0.30 +
            $this->potentiel_croissance * 0.20 +
            ($this->budget_par_joueur_max / 1000000) * 0.20
        ), 1);
    }

    // Scopes pour les requêtes courantes
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopePrestigieux($query, $niveau_min = 8)
    {
        return $query->where('niveau_prestige', '>=', $niveau_min);
    }

    public function scopeBudgetEleve($query, $montant_min = 1000000)
    {
        return $query->where('budget_par_joueur_max', '>=', $montant_min);
    }

    public function scopeSecteur($query, $secteur)
    {
        return $query->where('secteur_activite', $secteur);
    }

    public function scopeFiable($query, $score_min = 8)
    {
        return $query->where('stabilite_financiere', '>=', $score_min)
            ->where('note_reputation', '>=', $score_min);
    }

    public function scopeInnovateur($query, $score_min = 7)
    {
        return $query->where('innovation_produits', '>=', $score_min);
    }

    // Méthodes d'analyse et de prédiction
    public function analyserCompatibiliteJoueur(Joueur $joueur)
    {
        $compatibilite = [];

        // Analyse des critères de sélection
        $criteres = $this->criteres_selection_joueurs ?? [];

        // Ranking
        if (isset($criteres['ranking_max'])) {
            $ranking_joueur = $joueur->ranking_atp ?? $joueur->ranking_wta ?? 1000;
            $compatibilite['ranking'] = $ranking_joueur <= $criteres['ranking_max'];
        }

        // Age
        if (isset($criteres['age_max']) && isset($criteres['age_min'])) {
            $age = $joueur->age;
            $compatibilite['age'] = $age >= $criteres['age_min'] && $age <= $criteres['age_max'];
        }

        // Nationalité
        if (isset($criteres['nationalites_preferees'])) {
            $compatibilite['nationalite'] = in_array($joueur->nationalite, $criteres['nationalites_preferees']);
        }

        // Style de jeu
        if (isset($criteres['styles_jeu'])) {
            $compatibilite['style'] = in_array($joueur->style_jeu, $criteres['styles_jeu']);
        }

        // Surface de prédilection
        if (isset($criteres['surfaces_cibles'])) {
            $compatibilite['surface'] = in_array($joueur->surface_preferee, $criteres['surfaces_cibles']);
        }

        // Image publique
        $compatibilite['image'] = $this->evaluerImageJoueur($joueur);

        // Score de compatibilité global
        $score_total = 0;
        $criteres_evalues = 0;

        foreach ($compatibilite as $critere => $valeur) {
            if (is_bool($valeur)) {
                $score_total += $valeur ? 1 : 0;
                $criteres_evalues++;
            } elseif (is_numeric($valeur)) {
                $score_total += $valeur;
                $criteres_evalues++;
            }
        }

        $score_compatibilite = $criteres_evalues > 0 ? ($score_total / $criteres_evalues) * 10 : 5;

        return [
            'score_global' => round($score_compatibilite, 1),
            'details' => $compatibilite,
            'recommandation' => $this->genererRecommandationContrat($score_compatibilite, $joueur)
        ];
    }

    private function evaluerImageJoueur(Joueur $joueur)
    {
        // Évaluation de l'image du joueur pour le sponsor
        $score_image = 5; // Base neutre

        // Facteurs positifs
        if ($joueur->nb_titres_grand_chelem > 0) $score_image += 2;
        if ($joueur->ranking_atp <= 10 || $joueur->ranking_wta <= 10) $score_image += 1.5;
        if ($joueur->popularite_sociale ?? 0 > 8) $score_image += 1;

        // Facteurs négatifs
        if ($joueur->controverses_recentes ?? 0 > 2) {
            $score_image -= min($joueur->controverses_recentes * 0.5, 3);
        }

        // Adaptation selon la tolérance du sponsor
        if ($this->tolerance_controverses < 5 && ($joueur->controverses_recentes ?? 0) > 0) {
            $score_image -= 2;
        }

        return round(max(1, min(10, $score_image)), 1);
    }

    public function calculerPressionContractuelle()
    {
        // Calcul du niveau de pression exercée sur les joueurs
        $pression = 0;

        $exigences = $this->exigences_contractuelles ?? [];

        // Nombre d'apparitions obligatoires
        if (isset($exigences['apparitions_minimales'])) {
            $pression += min($exigences['apparitions_minimales'] / 10, 3);
        }

        // Objectifs de performance
        if (isset($exigences['objectifs_ranking'])) {
            $pression += 2;
        }

        // Exclusivités sectorielles
        if (isset($exigences['exclusivite_sectorielle']) && $exigences['exclusivite_sectorielle']) {
            $pression += 1;
        }

        // Obligations réseaux sociaux
        if (isset($exigences['posts_sociaux_mensuels'])) {
            $pression += min($exigences['posts_sociaux_mensuels'] / 20, 2);
        }

        // Ajustement selon flexibilité
        $pression *= (11 - $this->flexibilite_contrats) / 10;

        return round(min(10, $pression), 1);
    }

    public function predireImpactSurJoueur(Joueur $joueur, $type_contrat = 'principal')
    {
        // Prédiction de l'impact du sponsoring sur le joueur
        $impacts = [];

        // Impact financier (motivation)
        $impact_financier = $this->calculerImpactFinancier($joueur, $type_contrat);
        $impacts['motivation'] = $impact_financier * 0.02; // 2% max d'impact

        // Impact de la pression
        $pression = $this->calculerPressionContractuelle();
        $impacts['stress'] = -($pression / 10) * 0.015; // Impact négatif du stress

        // Impact du support technique
        $impacts['technique'] = ($this->support_technique_niveau / 10) * 0.01;

        // Impact marketing (confiance)
        $impacts['confiance'] = ($this->support_marketing_niveau / 10) * 0.005;

        // Impact de l'équipement
        if ($this->secteur_activite === 'equipement_sportif') {
            $impacts['equipement'] = ($this->innovation_produits / 10) * 0.008;
        }

        $impact_total = array_sum($impacts);

        return [
            'impact_global' => round($impact_total, 3),
            'details' => $impacts,
            'duree_adaptation' => $this->estimer_duree_adaptation($type_contrat),
            'risques' => $this->identifierRisquesContrat($joueur)
        ];
    }

    private function calculerImpactFinancier(Joueur $joueur, $type_contrat)
    {
        // Impact relatif du contrat sur la situation financière
        $gains_actuels = $joueur->gains_annuels_estimes ?? 500000;

        $montant_estime = match($type_contrat) {
            'principal' => $this->budget_par_joueur_max * 0.8,
            'secondaire' => $this->budget_par_joueur_moyen * 0.6,
            'equipement' => $this->budget_par_joueur_moyen * 0.3,
            default => $this->budget_par_joueur_moyen * 0.5
        };

        return min($montant_estime / $gains_actuels, 2); // Impact max de 2x
    }

    public function genererOffre(Joueur $joueur, $type_contrat = 'principal')
    {
        $compatibilite = $this->analyserCompatibiliteJoueur($joueur);
        $impact = $this->predireImpactSurJoueur($joueur, $type_contrat);

        if ($compatibilite['score_global'] < 6) {
            return null; // Pas d'offre si compatibilité faible
        }

        // Calcul du montant de l'offre
        $montant_base = match($type_contrat) {
            'principal' => $this->budget_par_joueur_max * 0.7,
            'secondaire' => $this->budget_par_joueur_moyen * 0.6,
            'equipement' => $this->budget_par_joueur_moyen * 0.4,
            default => $this->budget_par_joueur_moyen * 0.5
        };

        // Ajustements selon performance et potentiel
        $facteur_ranking = $this->calculerFacteurRanking($joueur);
        $facteur_potentiel = $this->calculerFacteurPotentiel($joueur);

        $montant_final = $montant_base * $facteur_ranking * $facteur_potentiel;

        return [
            'montant_annuel' => round($montant_final, 2),
            'duree_proposee' => $this->duree_engagement_moyenne,
            'bonus_performance' => $this->genererBonusPerformance($joueur),
            'exigences' => $this->adapterExigences($joueur),
            'score_attractivite' => $compatibilite['score_global'],
            'impact_predit' => $impact['impact_global']
        ];
    }

    public function analyserConcurrence(Joueur $joueur)
    {
        // Analyse de la concurrence pour un joueur donné
        $concurrents = Sponsor::where('secteur_activite', $this->secteur_activite)
            ->where('id', '!=', $this->id)
            ->actif()
            ->get();

        $analyses = [];

        foreach ($concurrents as $concurrent) {
            $compatibilite_concurrent = $concurrent->analyserCompatibiliteJoueur($joueur);
            $offre_estimee = $concurrent->genererOffre($joueur);

            $analyses[] = [
                'sponsor' => $concurrent->nom,
                'attractivite' => $concurrent->attractivite_sponsor,
                'compatibilite' => $compatibilite_concurrent['score_global'],
                'offre_estimee' => $offre_estimee['montant_annuel'] ?? 0,
                'avantage_competitif' => $this->attractivite_sponsor - $concurrent->attractivite_sponsor
            ];
        }

        return collect($analyses)->sortByDesc('attractivite');
    }

    public function genererRapportPerformance()
    {
        $joueurs_actuels = $this->joueursActuels;

        return [
            'sponsor_info' => [
                'nom' => $this->nom,
                'secteur' => $this->secteur_activite,
                'budget_total' => $this->budget_tennis_annuel,
                'nb_joueurs' => $joueurs_actuels->count()
            ],
            'performance_globale' => [
                'attractivite' => $this->attractivite_sponsor,
                'fiabilite' => $this->score_fiabilite,
                'impact_moyen' => $this->impact_performance_estime,
                'satisfaction' => $this->satisfaction_joueurs_moyenne
            ],
            'joueurs_sponsorises' => $joueurs_actuels->map(function($joueur) {
                return [
                    'nom' => $joueur->nom,
                    'ranking' => $joueur->ranking_atp ?? $joueur->ranking_wta,
                    'performance_recent' => $joueur->forme_actuelle ?? 5,
                    'roi_estime' => $this->calculerROIJoueur($joueur)
                ];
            }),
            'recommandations' => $this->genererRecommandationsAmelioration()
        ];
    }

    // Méthodes statiques pour analyses globales
    public static function topSponsors($limite = 10)
    {
        return self::actif()
            ->orderByDesc('attractivite_sponsor')
            ->orderByDesc('budget_par_joueur_max')
            ->limit($limite)
            ->get();
    }

    public static function analyseMarcheSponsoring()
    {
        return [
            'sponsors_actifs' => self::actif()->count(),
            'budget_total_marche' => self::actif()->sum('budget_tennis_annuel'),
            'budget_moyen_joueur' => self::actif()->avg('budget_par_joueur_moyen'),
            'repartition_secteurs' => self::actif()
                ->groupBy('secteur_activite')
                ->selectRaw('secteur_activite, count(*) as total, sum(budget_tennis_annuel) as budget')
                ->get(),
            'satisfaction_moyenne' => self::actif()->avg('satisfaction_joueurs_moyenne')
        ];
    }

    public static function opportunitesSponsoring(Joueur $joueur)
    {
        return self::actif()
            ->fiable(7)
            ->get()
            ->map(function($sponsor) use ($joueur) {
                $compatibilite = $sponsor->analyserCompatibiliteJoueur($joueur);
                $offre = $sponsor->genererOffre($joueur);

                return [
                    'sponsor' => $sponsor,
                    'score_opportunite' => $compatibilite['score_global'],
                    'offre_estimee' => $offre['montant_annuel'] ?? 0,
                    'impact_predit' => $sponsor->impact_performance_estime
                ];
            })
            ->sortByDesc('score_opportunite')
            ->take(10);
    }
}
