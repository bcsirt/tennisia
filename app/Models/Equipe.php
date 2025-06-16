<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'joueur1_id',
        'joueur2_id',
        'date_formation',
        'statut_equipe',
        'ranking_double_atp',
        'ranking_double_wta',
        'points_ranking',
        'surface_preferee',
        'style_jeu_equipe',
        'formation_preferee',
        'nb_matchs_ensemble',
        'nb_victoires_ensemble',
        'nb_defaites_ensemble',
        'nb_titres_ensemble',
        'compatibilite_score',
        'complementarite_styles',
        'niveau_communication',
        'niveau_coordination',
        'experience_grande_scene',
        'gestion_pression_equipe',
        'specialisation_surfaces',
        'points_forts_equipe',
        'points_faibles_equipe',
        'coach_double',
        'entraineur_mental',
        'derniere_competition',
        'forme_actuelle',
        'blessures_actives',
        'conflits_internes',
        'motivation_niveau',
        'objectifs_saison',
        'historique_confrontations',
        'statistiques_surfaces',
        'notes_coach',
        'analyse_video_disponible',
    ];

    protected $casts = [
        'date_formation' => 'date',
        'ranking_double_atp' => 'integer',
        'ranking_double_wta' => 'integer',
        'points_ranking' => 'integer',
        'nb_matchs_ensemble' => 'integer',
        'nb_victoires_ensemble' => 'integer',
        'nb_defaites_ensemble' => 'integer',
        'nb_titres_ensemble' => 'integer',
        'compatibilite_score' => 'decimal:1', // 1-10
        'complementarite_styles' => 'decimal:1', // 1-10
        'niveau_communication' => 'integer', // 1-10
        'niveau_coordination' => 'integer', // 1-10
        'experience_grande_scene' => 'integer', // 1-10
        'gestion_pression_equipe' => 'integer', // 1-10
        'specialisation_surfaces' => 'array',
        'points_forts_equipe' => 'array',
        'points_faibles_equipe' => 'array',
        'coach_double' => 'boolean',
        'entraineur_mental' => 'boolean',
        'derniere_competition' => 'date',
        'forme_actuelle' => 'integer', // 1-10
        'blessures_actives' => 'array',
        'conflits_internes' => 'boolean',
        'motivation_niveau' => 'integer', // 1-10
        'objectifs_saison' => 'array',
        'historique_confrontations' => 'array',
        'statistiques_surfaces' => 'array',
        'notes_coach' => 'array',
        'analyse_video_disponible' => 'boolean',
    ];

    protected $appends = [
        'pourcentage_victoires',
        'ranking_combine',
        'indice_chimie',
        'niveau_experience',
        'score_forme_equipe',
        'potentiel_equipe',
    ];

    // Relations
    public function joueur1()
    {
        return $this->belongsTo(Joueur::class, 'joueur1_id');
    }

    public function joueur2()
    {
        return $this->belongsTo(Joueur::class, 'joueur2_id');
    }

    public function matchsDouble()
    {
        return $this->hasMany(MatchTennis::class, 'equipe1_id')
            ->orWhere('equipe2_id', $this->id);
    }

    public function statistiquesEquipe()
    {
        return $this->hasMany(StatistiqueEquipe::class);
    }

    // Accessors pour les métriques calculées
    public function getPourcentageVictoiresAttribute()
    {
        if ($this->nb_matchs_ensemble == 0) {
            return 0;
        }

        return round(($this->nb_victoires_ensemble / $this->nb_matchs_ensemble) * 100, 1);
    }

    public function getRankingCombineAttribute()
    {
        // Combine les rankings individuels des joueurs
        $ranking_j1 = $this->joueur1->ranking_double ?? 1000;
        $ranking_j2 = $this->joueur2->ranking_double ?? 1000;

        // Formule de ranking combiné (moyenne pondérée)
        return round(($ranking_j1 + $ranking_j2) / 2, 0);
    }

    public function getIndiceChimieAttribute()
    {
        // Indice composite de la chimie d'équipe
        $facteurs = [
            'compatibilite' => $this->compatibilite_score * 0.3,
            'complementarite' => $this->complementarite_styles * 0.25,
            'communication' => $this->niveau_communication * 0.25,
            'coordination' => $this->niveau_coordination * 0.2,
        ];

        return round(array_sum($facteurs), 1);
    }

    public function getNiveauExperienceAttribute()
    {
        // Niveau d'expérience basé sur matchs joués et titres
        $score_matchs = min($this->nb_matchs_ensemble / 50, 5); // Max 5 points
        $score_titres = min($this->nb_titres_ensemble * 2, 3); // Max 3 points
        $score_scene = $this->experience_grande_scene / 5; // Max 2 points

        return round($score_matchs + $score_titres + $score_scene, 1);
    }

    public function getScoreFormeEquipeAttribute()
    {
        // Score de forme actuelle de l'équipe
        $forme_base = $this->forme_actuelle ?? 5;

        // Ajustements
        $ajustements = 0;

        if (! empty($this->blessures_actives)) {
            $ajustements -= count($this->blessures_actives) * 1.5;
        }

        if ($this->conflits_internes) {
            $ajustements -= 2;
        }

        if ($this->motivation_niveau >= 8) {
            $ajustements += 1;
        }

        // Fraîcheur (temps depuis dernière compétition)
        if ($this->derniere_competition) {
            $jours_repos = $this->derniere_competition->diffInDays(now());
            if ($jours_repos > 30) {
                $ajustements -= 1; // Manque de rythme
            } elseif ($jours_repos < 3) {
                $ajustements -= 0.5; // Fatigue
            }
        }

        return round(max(1, min(10, $forme_base + $ajustements)), 1);
    }

    public function getPotentielEquipeAttribute()
    {
        // Potentiel maximum de l'équipe
        $potentiel_individuel = $this->calculerPotentielIndividuel();
        $synergie = $this->indice_chimie / 10;
        $experience_factor = min($this->niveau_experience / 10, 1);

        return round($potentiel_individuel * $synergie * (0.7 + 0.3 * $experience_factor), 1);
    }

    // Scopes pour les requêtes courantes
    public function scopeTopRanking($query, $limite_ranking = 50)
    {
        return $query->where('ranking_double_atp', '<=', $limite_ranking)
            ->orWhere('ranking_double_wta', '<=', $limite_ranking);
    }

    public function scopeExperience($query, $nb_matchs_min = 20)
    {
        return $query->where('nb_matchs_ensemble', '>=', $nb_matchs_min);
    }

    public function scopeChimieElevee($query, $score_min = 7.5)
    {
        return $query->whereRaw('(compatibilite_score + complementarite_styles + niveau_communication + niveau_coordination) / 4 >= ?', [$score_min]);
    }

    public function scopeFormeOptimale($query, $score_min = 7)
    {
        return $query->where('forme_actuelle', '>=', $score_min)
            ->where('conflits_internes', false);
    }

    public function scopeSpecialisteSurface($query, $surface)
    {
        return $query->where('surface_preferee', $surface)
            ->orWhereJsonContains('specialisation_surfaces', $surface);
    }

    public function scopeAvecCoach($query)
    {
        return $query->where('coach_double', true);
    }

    // Méthodes d'analyse et de prédiction
    public function calculerPotentielIndividuel()
    {
        // Potentiel basé sur les rankings individuels
        $ranking_j1 = $this->joueur1->ranking_double ?? 1000;
        $ranking_j2 = $this->joueur2->ranking_double ?? 1000;

        // Conversion ranking -> potentiel (1-10)
        $potentiel_j1 = max(1, 10 - ($ranking_j1 / 100));
        $potentiel_j2 = max(1, 10 - ($ranking_j2 / 100));

        return ($potentiel_j1 + $potentiel_j2) / 2;
    }

    public function analyserComplementarite()
    {
        $j1_style = $this->joueur1->style_jeu ?? 'polyvalent';
        $j2_style = $this->joueur2->style_jeu ?? 'polyvalent';

        $complementarites = [
            'attaquant-defenseur' => 9,
            'defenseur-attaquant' => 9,
            'serveur_volleyeur-passeur' => 8,
            'passeur-serveur_volleyeur' => 8,
            'puissance-placement' => 8,
            'placement-puissance' => 8,
            'gaucher-droitier' => 7,
            'droitier-gaucher' => 7,
            'attaquant-attaquant' => 6,
            'defenseur-defenseur' => 5,
        ];

        $combinaison = $j1_style.'-'.$j2_style;

        return $complementarites[$combinaison] ?? 6;
    }

    public function predirePerformanceSurface($surface)
    {
        // Prédiction performance sur une surface donnée
        $score_base = $this->potentiel_equipe;

        // Bonus surface préférée
        if ($this->surface_preferee === $surface) {
            $score_base += 1;
        }

        // Bonus spécialisation
        if (in_array($surface, $this->specialisation_surfaces ?? [])) {
            $score_base += 0.5;
        }

        // Statistiques historiques sur cette surface
        $stats_surface = $this->statistiques_surfaces[$surface] ?? null;
        if ($stats_surface && isset($stats_surface['pourcentage_victoires'])) {
            $ajustement = ($stats_surface['pourcentage_victoires'] - 50) / 50;
            $score_base += $ajustement;
        }

        return round(max(1, min(10, $score_base)), 1);
    }

    public function detecterPointsForts()
    {
        $points_forts = [];

        // Analyse basée sur les métriques
        if ($this->indice_chimie >= 8) {
            $points_forts[] = 'chimie_exceptionnelle';
        }

        if ($this->niveau_experience >= 8) {
            $points_forts[] = 'experience_elevee';
        }

        if ($this->gestion_pression_equipe >= 8) {
            $points_forts[] = 'mental_solide';
        }

        if ($this->pourcentage_victoires >= 70) {
            $points_forts[] = 'equipe_gagnante';
        }

        if ($this->nb_titres_ensemble >= 5) {
            $points_forts[] = 'palmares_impressionnant';
        }

        // Points forts déclarés
        if (! empty($this->points_forts_equipe)) {
            $points_forts = array_merge($points_forts, $this->points_forts_equipe);
        }

        return array_unique($points_forts);
    }

    public function detecterPointsFaibles()
    {
        $points_faibles = [];

        // Analyse basée sur les métriques
        if ($this->indice_chimie <= 5) {
            $points_faibles[] = 'chimie_insuffisante';
        }

        if ($this->nb_matchs_ensemble < 10) {
            $points_faibles[] = 'manque_experience_commune';
        }

        if (! empty($this->blessures_actives)) {
            $points_faibles[] = 'blessures_handicapantes';
        }

        if ($this->conflits_internes) {
            $points_faibles[] = 'conflits_equipe';
        }

        if ($this->motivation_niveau <= 4) {
            $points_faibles[] = 'motivation_faible';
        }

        // Points faibles déclarés
        if (! empty($this->points_faibles_equipe)) {
            $points_faibles = array_merge($points_faibles, $this->points_faibles_equipe);
        }

        return array_unique($points_faibles);
    }

    public function calculerAvantageVsEquipe(Equipe $adversaire)
    {
        // Calcul de l'avantage contre une équipe adverse
        $avantages = [];

        // Avantage de ranking
        $diff_ranking = $adversaire->ranking_combine - $this->ranking_combine;
        if ($diff_ranking > 50) {
            $avantages['ranking'] = min($diff_ranking / 100, 2);
        }

        // Avantage d'expérience
        $diff_experience = $this->niveau_experience - $adversaire->niveau_experience;
        if ($diff_experience > 2) {
            $avantages['experience'] = $diff_experience * 0.5;
        }

        // Avantage de forme
        $diff_forme = $this->score_forme_equipe - $adversaire->score_forme_equipe;
        if ($diff_forme > 1) {
            $avantages['forme'] = $diff_forme * 0.3;
        }

        // Avantage de chimie
        $diff_chimie = $this->indice_chimie - $adversaire->indice_chimie;
        if ($diff_chimie > 1) {
            $avantages['chimie'] = $diff_chimie * 0.2;
        }

        // Historique des confrontations
        if (isset($this->historique_confrontations[$adversaire->id])) {
            $historique = $this->historique_confrontations[$adversaire->id];
            if ($historique['victoires'] > $historique['defaites']) {
                $avantages['historique'] = ($historique['victoires'] - $historique['defaites']) * 0.1;
            }
        }

        return [
            'avantage_total' => round(array_sum($avantages), 2),
            'details' => $avantages,
        ];
    }

    public function recommandationsAmelioration()
    {
        $recommandations = [];

        // Basé sur les points faibles détectés
        $points_faibles = $this->detecterPointsFaibles();

        foreach ($points_faibles as $faiblesse) {
            switch ($faiblesse) {
                case 'chimie_insuffisante':
                    $recommandations[] = 'Intensifier les entraînements en double et la communication';
                    break;
                case 'manque_experience_commune':
                    $recommandations[] = 'Jouer plus de tournois ensemble pour développer l\'automatisme';
                    break;
                case 'blessures_handicapantes':
                    $recommandations[] = 'Traitement médical prioritaire et adaptation du jeu';
                    break;
                case 'conflits_equipe':
                    $recommandations[] = 'Médiation et travail avec psychologue du sport';
                    break;
                case 'motivation_faible':
                    $recommandations[] = 'Redéfinir les objectifs et renouveler la motivation';
                    break;
            }
        }

        return $recommandations;
    }

    public function genererProfilEquipe()
    {
        return [
            'identite' => [
                'nom' => $this->nom,
                'formation' => $this->date_formation,
                'statut' => $this->statut_equipe,
                'style_jeu' => $this->style_jeu_equipe,
            ],
            'classement' => [
                'ranking_atp' => $this->ranking_double_atp,
                'ranking_wta' => $this->ranking_double_wta,
                'ranking_combine' => $this->ranking_combine,
                'points' => $this->points_ranking,
            ],
            'performance' => [
                'matchs_total' => $this->nb_matchs_ensemble,
                'victoires' => $this->nb_victoires_ensemble,
                'pourcentage_victoires' => $this->pourcentage_victoires,
                'titres' => $this->nb_titres_ensemble,
            ],
            'dynamique_equipe' => [
                'indice_chimie' => $this->indice_chimie,
                'compatibilite' => $this->compatibilite_score,
                'complementarite' => $this->complementarite_styles,
                'communication' => $this->niveau_communication,
                'coordination' => $this->niveau_coordination,
            ],
            'forme_actuelle' => [
                'score_forme' => $this->score_forme_equipe,
                'motivation' => $this->motivation_niveau,
                'blessures' => $this->blessures_actives,
                'conflits' => $this->conflits_internes,
            ],
            'analyse' => [
                'potentiel' => $this->potentiel_equipe,
                'experience' => $this->niveau_experience,
                'points_forts' => $this->detecterPointsForts(),
                'points_faibles' => $this->detecterPointsFaibles(),
                'recommandations' => $this->recommandationsAmelioration(),
            ],
        ];
    }

    // Méthodes statiques pour analyses globales
    public static function meilleuresEquipes($surface = null, $limite = 10)
    {
        $query = self::query();

        if ($surface) {
            $query->specialisteSurface($surface);
        }

        return $query->orderByDesc('potentiel_equipe')
            ->limit($limite)
            ->get();
    }

    public static function equipesMontantes($periode_mois = 6)
    {
        return self::where('date_formation', '>=', now()->subMonths($periode_mois))
            ->where('potentiel_equipe', '>=', 7)
            ->orderByDesc('potentiel_equipe')
            ->get();
    }

    public static function analyseCompetitiviteDouble()
    {
        return [
            'nb_equipes_actives' => self::where('statut_equipe', 'active')->count(),
            'niveau_moyen' => self::avg('potentiel_equipe'),
            'chimie_moyenne' => self::avg('indice_chimie'),
            'experience_moyenne' => self::avg('niveau_experience'),
        ];
    }
}
