<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Arbitre extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'nationalite',
        'date_naissance',
        'experience_annees',
        'niveau_certification',
        'specialisation_surface',
        'nb_matchs_arbitres',
        'nb_grands_chelems',
        'nb_masters_1000',
        'nb_finales_arbitrees',
        'style_arbitrage',
        'tolerance_niveau',
        'gestion_pression',
        'vitesse_decisions',
        'communication_joueurs',
        'gestion_incidents',
        'controle_temps',
        'precision_calls',
        'coherence_decisions',
        'autorite_court',
        'adaptation_contexte',
        'langues_parlees',
        'matchs_controverses',
        'sanctions_donnees',
        'warnings_distribues',
        'code_violations_appelees',
        'score_performance',
        'evaluation_atp',
        'evaluation_wta',
        'preference_joueurs',
        'historique_incidents',
        'formation_continue',
        'technologie_utilisation',
        'statut_actif',
        'derniere_formation',
        'points_faibles_identifies',
        'points_forts_reconnus',
        'objectifs_amelioration',
        'feedback_joueurs',
        'notes_superviseurs'
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'experience_annees' => 'integer',
        'nb_matchs_arbitres' => 'integer',
        'nb_grands_chelems' => 'integer',
        'nb_masters_1000' => 'integer',
        'nb_finales_arbitrees' => 'integer',
        'tolerance_niveau' => 'integer', // 1-10
        'gestion_pression' => 'integer', // 1-10
        'vitesse_decisions' => 'integer', // 1-10
        'communication_joueurs' => 'integer', // 1-10
        'gestion_incidents' => 'integer', // 1-10
        'controle_temps' => 'integer', // 1-10
        'precision_calls' => 'decimal:1', // 1-10
        'coherence_decisions' => 'decimal:1', // 1-10
        'autorite_court' => 'integer', // 1-10
        'adaptation_contexte' => 'integer', // 1-10
        'langues_parlees' => 'array',
        'matchs_controverses' => 'integer',
        'sanctions_donnees' => 'integer',
        'warnings_distribues' => 'integer',
        'code_violations_appelees' => 'integer',
        'score_performance' => 'decimal:2', // 1-10
        'evaluation_atp' => 'decimal:1',
        'evaluation_wta' => 'decimal:1',
        'preference_joueurs' => 'array',
        'historique_incidents' => 'array',
        'formation_continue' => 'boolean',
        'technologie_utilisation' => 'array',
        'statut_actif' => 'boolean',
        'derniere_formation' => 'date',
        'points_faibles_identifies' => 'array',
        'points_forts_reconnus' => 'array',
        'objectifs_amelioration' => 'array',
        'feedback_joueurs' => 'array',
        'notes_superviseurs' => 'array'
    ];

    protected $appends = [
        'age',
        'niveau_experience',
        'score_competence_global',
        'indice_controverse',
        'autorite_effective',
        'impact_match_estime'
    ];

    // Relations
    public function matchsArbitres()
    {
        return $this->hasMany(MatchTennis::class, 'arbitre_principal_id');
    }

    public function evaluationsArbitrage()
    {
        return $this->hasMany(EvaluationArbitrage::class);
    }

    // Accessors pour les métriques calculées
    public function getAgeAttribute()
    {
        return $this->date_naissance ? $this->date_naissance->age : null;
    }

    public function getNiveauExperienceAttribute()
    {
        // Niveau d'expérience basé sur matchs et tournois prestigieux
        $score_base = min($this->experience_annees * 0.5, 5);
        $score_matchs = min($this->nb_matchs_arbitres / 200, 3);
        $score_prestige = ($this->nb_grands_chelems * 0.3) + ($this->nb_masters_1000 * 0.2);

        return round(min(10, $score_base + $score_matchs + $score_prestige), 1);
    }

    public function getScoreCompetenceGlobalAttribute()
    {
        // Score composite des compétences d'arbitrage
        $competences = [
            'precision_calls' => 0.25,
            'coherence_decisions' => 0.20,
            'gestion_pression' => 0.15,
            'communication_joueurs' => 0.15,
            'autorite_court' => 0.15,
            'gestion_incidents' => 0.10
        ];

        $score_total = 0;
        foreach ($competences as $competence => $poids) {
            $valeur = $this->$competence ?? 5;
            $score_total += $valeur * $poids;
        }

        return round($score_total, 1);
    }

    public function getIndiceControverseAttribute()
    {
        // Indice de controverse (plus bas = mieux)
        if ($this->nb_matchs_arbitres == 0) return 0;

        $ratio_controverses = ($this->matchs_controverses / $this->nb_matchs_arbitres) * 100;
        $ratio_violations = ($this->code_violations_appelees / $this->nb_matchs_arbitres) * 10;

        return round($ratio_controverses + $ratio_violations, 2);
    }

    public function getAutoriteEffectiveAttribute()
    {
        // Autorité effective = autorité perçue - controverses
        $autorite_base = $this->autorite_court ?? 5;
        $malus_controverse = min($this->indice_controverse / 10, 3);

        return round(max(1, $autorite_base - $malus_controverse), 1);
    }

    public function getImpactMatchEstimeAttribute()
    {
        // Estimation de l'impact de l'arbitre sur le déroulement du match
        $facteurs = [
            'autorite' => $this->autorite_effective / 10,
            'experience' => min($this->niveau_experience / 10, 1),
            'coherence' => ($this->coherence_decisions ?? 5) / 10,
            'gestion_pression' => ($this->gestion_pression ?? 5) / 10
        ];

        $impact_moyen = array_sum($facteurs) / count($facteurs);

        // Impact neutre = 0, positif > 0, négatif < 0
        return round(($impact_moyen - 0.5) * 2, 2); // Scale -1 à +1
    }

    // Scopes pour les requêtes courantes
    public function scopeExperimente($query, $annees_min = 10)
    {
        return $query->where('experience_annees', '>=', $annees_min);
    }

    public function scopeGrandChelemApprouve($query)
    {
        return $query->where('nb_grands_chelems', '>', 0)
            ->where('niveau_certification', 'chair_umpire_gold');
    }

    public function scopeActif($query)
    {
        return $query->where('statut_actif', true);
    }

    public function scopeSpecialisteSurface($query, $surface)
    {
        return $query->where('specialisation_surface', $surface)
            ->orWhereJsonContains('specialisation_surface', $surface);
    }

    public function scopeFaibleControverse($query, $seuil_max = 5)
    {
        return $query->whereRaw('(matchs_controverses / GREATEST(nb_matchs_arbitres, 1)) * 100 <= ?', [$seuil_max]);
    }

    public function scopeHautePerformance($query, $score_min = 8)
    {
        return $query->where('score_performance', '>=', $score_min);
    }

    // Méthodes d'analyse et de prédiction
    public function analyserStyleArbitrage()
    {
        $style = [];

        // Style basé sur les métriques
        if ($this->tolerance_niveau <= 3) {
            $style[] = 'strict';
        } elseif ($this->tolerance_niveau >= 7) {
            $style[] = 'tolerant';
        } else {
            $style[] = 'equilibre';
        }

        if ($this->vitesse_decisions >= 8) {
            $style[] = 'decisif_rapide';
        } elseif ($this->vitesse_decisions <= 4) {
            $style[] = 'reflexif_lent';
        }

        if ($this->communication_joueurs >= 8) {
            $style[] = 'communicatif';
        } elseif ($this->communication_joueurs <= 4) {
            $style[] = 'distant';
        }

        return $style;
    }

    public function predireImpactSurJoueur(Joueur $joueur)
    {
        // Prédiction de l'impact de cet arbitre sur un joueur spécifique
        $impact_base = $this->impact_match_estime;

        // Historique avec ce joueur
        $historique = $this->historique_incidents[$joueur->id] ?? null;
        if ($historique) {
            if ($historique['incidents'] > 2) {
                $impact_base -= 0.2; // Impact négatif
            }
        }

        // Compatibilité nationalité (bias potentiel)
        if ($this->nationalite === $joueur->nationalite) {
            // Attention au bias, mais peut apporter confiance
            $impact_base += 0.1;
        }

        // Style joueur vs style arbitrage
        $style_arbitre = $this->analyserStyleArbitrage();
        if (in_array('strict', $style_arbitre) && $joueur->temperament === 'explosif') {
            $impact_base -= 0.15; // Risque de tension
        }

        return round($impact_base, 2);
    }

    public function calculerAvantageMaison($tournament_country)
    {
        // Avantage potentiel lié à la nationalité dans certains tournois
        if ($this->nationalite === $tournament_country) {
            $avantage = 0.1; // Bonus léger connaissance locale

            // Mais attention aux controverses
            if ($this->indice_controverse > 10) {
                $avantage -= 0.05; // Malus si historique controversé
            }

            return $avantage;
        }

        return 0;
    }

    public function detecterSignauxAlarme()
    {
        $signaux = [];

        // Trop de controverses
        if ($this->indice_controverse > 15) {
            $signaux[] = 'controverse_elevee';
        }

        // Baisse de performance
        if ($this->score_performance < 6) {
            $signaux[] = 'performance_insuffisante';
        }

        // Manque de formation récente
        if ($this->derniere_formation && $this->derniere_formation->diffInMonths(now()) > 12) {
            $signaux[] = 'formation_obsolete';
        }

        // Trop de violations appelées (signe de rigidité excessive)
        if ($this->nb_matchs_arbitres > 0) {
            $ratio_violations = $this->code_violations_appelees / $this->nb_matchs_arbitres;
            if ($ratio_violations > 2) {
                $signaux[] = 'rigidite_excessive';
            }
        }

        // Manque d'autorité
        if ($this->autorite_court < 5) {
            $signaux[] = 'autorite_insuffisante';
        }

        return $signaux;
    }

    public function recommandationsAmelioration()
    {
        $recommandations = [];
        $signaux = $this->detecterSignauxAlarme();

        foreach ($signaux as $signal) {
            switch ($signal) {
                case 'controverse_elevee':
                    $recommandations[] = 'Formation en gestion de conflits et communication';
                    break;
                case 'performance_insuffisante':
                    $recommandations[] = 'Évaluation approfondie et coaching personnalisé';
                    break;
                case 'formation_obsolete':
                    $recommandations[] = 'Mise à jour formation réglementaire et technique';
                    break;
                case 'rigidite_excessive':
                    $recommandations[] = 'Formation en flexibilité et gestion situationnelle';
                    break;
                case 'autorite_insuffisante':
                    $recommandations[] = 'Coaching en présence et leadership sur court';
                    break;
            }
        }

        return $recommandations;
    }

    public function genererProfilArbitrage()
    {
        return [
            'identite' => [
                'nom_complet' => $this->prenom . ' ' . $this->nom,
                'age' => $this->age,
                'nationalite' => $this->nationalite,
                'experience' => $this->experience_annees . ' ans'
            ],
            'certification' => [
                'niveau' => $this->niveau_certification,
                'specialisation' => $this->specialisation_surface,
                'statut' => $this->statut_actif ? 'Actif' : 'Inactif'
            ],
            'experience' => [
                'niveau' => $this->niveau_experience,
                'matchs_total' => $this->nb_matchs_arbitres,
                'grands_chelems' => $this->nb_grands_chelems,
                'masters_1000' => $this->nb_masters_1000,
                'finales' => $this->nb_finales_arbitrees
            ],
            'competences' => [
                'score_global' => $this->score_competence_global,
                'precision' => $this->precision_calls,
                'coherence' => $this->coherence_decisions,
                'autorite' => $this->autorite_effective,
                'gestion_pression' => $this->gestion_pression
            ],
            'style' => [
                'type' => $this->analyserStyleArbitrage(),
                'tolerance' => $this->tolerance_niveau,
                'communication' => $this->communication_joueurs,
                'vitesse_decision' => $this->vitesse_decisions
            ],
            'performance' => [
                'score' => $this->score_performance,
                'controverse' => $this->indice_controverse,
                'impact_estime' => $this->impact_match_estime,
                'evaluation_atp' => $this->evaluation_atp,
                'evaluation_wta' => $this->evaluation_wta
            ],
            'analyse' => [
                'signaux_alarme' => $this->detecterSignauxAlarme(),
                'recommandations' => $this->recommandationsAmelioration(),
                'points_forts' => $this->points_forts_reconnus,
                'points_faibles' => $this->points_faibles_identifies
            ]
        ];
    }

    // Méthodes statiques pour analyses globales
    public static function meilleuarsArbitres($limite = 10)
    {
        return self::actif()
            ->orderByDesc('score_performance')
            ->orderBy('indice_controverse')
            ->limit($limite)
            ->get();
    }

    public static function arbitresPourTournoi($niveau_tournoi, $surface = null)
    {
        $query = self::actif()->hautePerformance(7);

        if ($niveau_tournoi === 'grand_chelem') {
            $query->grandChelemApprouve();
        }

        if ($surface) {
            $query->specialisteSurface($surface);
        }

        return $query->get();
    }

    public static function analysePerformanceGlobale()
    {
        return [
            'arbitres_actifs' => self::actif()->count(),
            'score_moyen' => self::actif()->avg('score_performance'),
            'controverse_moyenne' => self::actif()->avg('indice_controverse'),
            'experience_moyenne' => self::actif()->avg('experience_annees'),
            'certification_repartition' => self::actif()
                ->groupBy('niveau_certification')
                ->selectRaw('niveau_certification, count(*) as total')
                ->get()
        ];
    }
}
