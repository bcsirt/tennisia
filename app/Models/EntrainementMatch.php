<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class EntrainementMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_tennis_id',
        'joueur_id',
        'type_entrainement',
        'sous_type_entrainement',
        'session',
        'date_entrainement',
        'duree_minutes',
        'intensite',
        'surface_entrainement',
        'conditions_meteo',
        'partenaire_entrainement_id',
        'coach_present',
        'objectifs_session',
        'exercices_specifiques',
        'nb_services_pratiques',
        'nb_retours_pratiques',
        'nb_coups_droits',
        'nb_revers',
        'nb_volleys',
        'temps_cardio',
        'temps_musculation',
        'temps_technique',
        'temps_tactique',
        'frequence_cardiaque_moyenne',
        'frequence_cardiaque_max',
        'calories_brulees',
        'niveau_fatigue_avant',
        'niveau_fatigue_apres',
        'niveau_confiance_avant',
        'niveau_confiance_apres',
        'points_techniques_travailles',
        'scenarios_tactiques',
        'recuperation_active',
        'qualite_sommeil_precedent',
        'nutrition_pre_entrainement',
        'hydratation_ml',
        'blessures_signalees',
        'douleurs_musculaires',
        'notes_coach',
        'auto_evaluation_joueur',
        'progression_detectee',
        'preparation_adversaire_specifique',
        'simulation_conditions_match',
        'description'
    ];

    protected $casts = [
        'date_entrainement' => 'datetime',
        'duree_minutes' => 'integer',
        'intensite' => 'integer', // 1-10
        'coach_present' => 'boolean',
        'objectifs_session' => 'array',
        'exercices_specifiques' => 'array',
        'nb_services_pratiques' => 'integer',
        'nb_retours_pratiques' => 'integer',
        'nb_coups_droits' => 'integer',
        'nb_revers' => 'integer',
        'nb_volleys' => 'integer',
        'temps_cardio' => 'integer',
        'temps_musculation' => 'integer',
        'temps_technique' => 'integer',
        'temps_tactique' => 'integer',
        'frequence_cardiaque_moyenne' => 'integer',
        'frequence_cardiaque_max' => 'integer',
        'calories_brulees' => 'integer',
        'niveau_fatigue_avant' => 'integer', // 1-10
        'niveau_fatigue_apres' => 'integer', // 1-10
        'niveau_confiance_avant' => 'integer', // 1-10
        'niveau_confiance_apres' => 'integer', // 1-10
        'points_techniques_travailles' => 'array',
        'scenarios_tactiques' => 'array',
        'recuperation_active' => 'boolean',
        'qualite_sommeil_precedent' => 'integer', // 1-10
        'hydratation_ml' => 'integer',
        'blessures_signalees' => 'array',
        'douleurs_musculaires' => 'array',
        'notes_coach' => 'array',
        'auto_evaluation_joueur' => 'array',
        'progression_detectee' => 'array',
        'preparation_adversaire_specifique' => 'boolean',
        'simulation_conditions_match' => 'boolean'
    ];

    protected $appends = [
        'charge_entrainement',
        'efficacite_session',
        'impact_confiance',
        'readiness_score',
        'fatigue_delta',
        'volume_technique_total'
    ];

    // Relations
    public function match()
    {
        return $this->belongsTo(MatchTennis::class, 'match_tennis_id');
    }

    public function joueur()
    {
        return $this->belongsTo(Joueur::class, 'joueur_id');
    }

    public function partenaireEntrainement()
    {
        return $this->belongsTo(Joueur::class, 'partenaire_entrainement_id');
    }

    // Accessors pour les métriques calculées
    public function getChargeEntrainementAttribute()
    {
        // Calcul de la charge d'entraînement (Training Load)
        if (!$this->duree_minutes || !$this->intensite) return 0;

        $charge_base = $this->duree_minutes * $this->intensite;

        // Facteurs d'ajustement
        $facteur_cardio = $this->frequence_cardiaque_moyenne ?
            ($this->frequence_cardiaque_moyenne / 150) : 1;

        return round($charge_base * $facteur_cardio, 1);
    }

    public function getEfficaciteSessionAttribute()
    {
        // Efficacité basée sur l'amélioration de confiance et objectifs atteints
        if (!$this->niveau_confiance_avant || !$this->niveau_confiance_apres) return null;

        $gain_confiance = $this->niveau_confiance_apres - $this->niveau_confiance_avant;
        $objectifs_score = count($this->objectifs_session ?? []) * 2;
        $progression_score = count($this->progression_detectee ?? []) * 3;

        $score_total = $gain_confiance + $objectifs_score + $progression_score;
        return round(max(0, min(10, $score_total)), 1);
    }

    public function getImpactConfianceAttribute()
    {
        if (!$this->niveau_confiance_avant || !$this->niveau_confiance_apres) return 0;
        return $this->niveau_confiance_apres - $this->niveau_confiance_avant;
    }

    public function getReadinessScoreAttribute()
    {
        // Score de préparation pour le match (0-100)
        $facteurs = [];

        // Fatigue (inversée)
        if ($this->niveau_fatigue_apres) {
            $facteurs['fatigue'] = (10 - $this->niveau_fatigue_apres) * 10;
        }

        // Confiance
        if ($this->niveau_confiance_apres) {
            $facteurs['confiance'] = $this->niveau_confiance_apres * 10;
        }

        // Qualité du sommeil
        if ($this->qualite_sommeil_precedent) {
            $facteurs['sommeil'] = $this->qualite_sommeil_precedent * 8;
        }

        // Absence de blessures
        $facteurs['sante'] = empty($this->blessures_signalees) ? 20 : 10;

        // Préparation spécifique
        if ($this->preparation_adversaire_specifique) {
            $facteurs['preparation'] = 15;
        }

        if ($this->simulation_conditions_match) {
            $facteurs['simulation'] = 10;
        }

        $score_moyen = !empty($facteurs) ? array_sum($facteurs) / count($facteurs) : 50;
        return round(min(100, $score_moyen), 1);
    }

    public function getFatigueDeltaAttribute()
    {
        if (!$this->niveau_fatigue_avant || !$this->niveau_fatigue_apres) return 0;
        return $this->niveau_fatigue_apres - $this->niveau_fatigue_avant;
    }

    public function getVolumeTechniqueTotalAttribute()
    {
        // Volume total des coups techniques pratiqués
        return ($this->nb_services_pratiques ?? 0) +
            ($this->nb_retours_pratiques ?? 0) +
            ($this->nb_coups_droits ?? 0) +
            ($this->nb_revers ?? 0) +
            ($this->nb_volleys ?? 0);
    }

    // Scopes pour les requêtes courantes
    public function scopeParJoueur($query, $joueur_id)
    {
        return $query->where('joueur_id', $joueur_id);
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type_entrainement', $type);
    }

    public function scopeIntenseSession($query, $intensite_min = 7)
    {
        return $query->where('intensite', '>=', $intensite_min);
    }

    public function scopeAvecCoach($query)
    {
        return $query->where('coach_present', true);
    }

    public function scopePreparationSpecifique($query)
    {
        return $query->where('preparation_adversaire_specifique', true);
    }

    public function scopeRecentSessions($query, $jours = 7)
    {
        return $query->where('date_entrainement', '>=', now()->subDays($jours));
    }

    public function scopeAvecBlessures($query)
    {
        return $query->whereNotNull('blessures_signalees')
            ->where('blessures_signalees', '!=', '[]');
    }

    // Méthodes d'analyse et de classification
    public function categoriserSession()
    {
        $categories = [];

        // Par intensité
        if ($this->intensite >= 8) {
            $categories[] = 'haute_intensite';
        } elseif ($this->intensite >= 6) {
            $categories[] = 'intensite_moderee';
        } else {
            $categories[] = 'recuperation_active';
        }

        // Par focus
        if ($this->temps_technique > $this->temps_physique) {
            $categories[] = 'focus_technique';
        } elseif ($this->temps_physique > $this->temps_technique) {
            $categories[] = 'focus_physique';
        }

        if ($this->preparation_adversaire_specifique) {
            $categories[] = 'preparation_tactique';
        }

        // Par volume
        if ($this->volume_technique_total > 500) {
            $categories[] = 'volume_eleve';
        }

        return $categories;
    }

    public function detecterSignauxAlarme()
    {
        $signaux = [];

        // Fatigue excessive
        if ($this->niveau_fatigue_apres >= 8) {
            $signaux[] = 'fatigue_excessive';
        }

        // Baisse de confiance
        if ($this->impact_confiance < -2) {
            $signaux[] = 'baisse_confiance';
        }

        // Blessures signalées
        if (!empty($this->blessures_signalees)) {
            $signaux[] = 'blessures_actives';
        }

        // Sommeil insuffisant
        if ($this->qualite_sommeil_precedent <= 4) {
            $signaux[] = 'sommeil_deficient';
        }

        // Charge d'entraînement excessive
        if ($this->charge_entrainement > 800) {
            $signaux[] = 'surcharge_entrainement';
        }

        return $signaux;
    }

    public function calculerOptimalitePreparation($match)
    {
        // Évalue si la préparation est optimale pour le match donné
        $score_preparation = 0;
        $facteurs = [];

        // Surface matching
        if ($this->surface_entrainement === $match->surface) {
            $score_preparation += 20;
            $facteurs[] = 'surface_adaptee';
        }

        // Préparation spécifique adversaire
        if ($this->preparation_adversaire_specifique) {
            $score_preparation += 25;
            $facteurs[] = 'adversaire_etudie';
        }

        // Simulation conditions match
        if ($this->simulation_conditions_match) {
            $score_preparation += 20;
            $facteurs[] = 'conditions_simulees';
        }

        // Timing optimal (2-3 jours avant)
        $jours_avant = $this->date_entrainement->diffInDays($match->date_match);
        if ($jours_avant >= 1 && $jours_avant <= 3) {
            $score_preparation += 15;
            $facteurs[] = 'timing_optimal';
        }

        // Absence de signaux d'alarme
        if (empty($this->detecterSignauxAlarme())) {
            $score_preparation += 20;
            $facteurs[] = 'etat_optimal';
        }

        return [
            'score' => min(100, $score_preparation),
            'facteurs_positifs' => $facteurs
        ];
    }

    public function recommandationsAmelioration()
    {
        $recommandations = [];
        $signaux = $this->detecterSignauxAlarme();

        foreach ($signaux as $signal) {
            switch ($signal) {
                case 'fatigue_excessive':
                    $recommandations[] = 'Réduire l\'intensité et augmenter la récupération';
                    break;
                case 'baisse_confiance':
                    $recommandations[] = 'Travailler sur des exercices de réussite et renforcement mental';
                    break;
                case 'blessures_actives':
                    $recommandations[] = 'Consultation médicale et adaptation du programme';
                    break;
                case 'sommeil_deficient':
                    $recommandations[] = 'Optimiser l\'hygiène de sommeil et la récupération';
                    break;
                case 'surcharge_entrainement':
                    $recommandations[] = 'Réduire le volume et planifier une période de récupération';
                    break;
            }
        }

        return $recommandations;
    }

    public function predireImpactSurMatch()
    {
        // Prédiction de l'impact sur la performance en match
        $impact_base = $this->readiness_score / 100;

        // Ajustements selon les facteurs
        $ajustements = [];

        if ($this->preparation_adversaire_specifique) {
            $ajustements['tactique'] = 0.05;
        }

        if ($this->efficacite_session >= 8) {
            $ajustements['confiance'] = 0.03;
        }

        if ($this->volume_technique_total >= 400) {
            $ajustements['technique'] = 0.02;
        }

        // Facteurs négatifs
        if (!empty($this->blessures_signalees)) {
            $ajustements['blessure'] = -0.08;
        }

        if ($this->fatigue_delta >= 3) {
            $ajustements['fatigue'] = -0.05;
        }

        $impact_final = $impact_base + array_sum($ajustements);

        return [
            'impact_global' => round($impact_final, 3),
            'facteurs_impact' => $ajustements,
            'niveau_preparation' => $this->categoriserNiveauPreparation($impact_final)
        ];
    }

    private function categoriserNiveauPreparation($impact)
    {
        if ($impact >= 0.9) return 'optimal';
        if ($impact >= 0.7) return 'bon';
        if ($impact >= 0.5) return 'moyen';
        if ($impact >= 0.3) return 'sous_optimal';
        return 'problematique';
    }

    public function genererRapportPreparation()
    {
        $optimality = $this->calculerOptimalitePreparation($this->match);
        $impact_prediction = $this->predireImpactSurMatch();

        return [
            'session_info' => [
                'type' => $this->type_entrainement,
                'duree' => $this->duree_minutes,
                'intensite' => $this->intensite,
                'categories' => $this->categoriserSession()
            ],
            'metriques_cles' => [
                'charge_entrainement' => $this->charge_entrainement,
                'efficacite' => $this->efficacite_session,
                'readiness_score' => $this->readiness_score,
                'impact_confiance' => $this->impact_confiance
            ],
            'preparation_match' => [
                'score_optimalite' => $optimality['score'],
                'facteurs_positifs' => $optimality['facteurs_positifs'],
                'impact_predit' => $impact_prediction['impact_global'],
                'niveau_preparation' => $impact_prediction['niveau_preparation']
            ],
            'alertes' => [
                'signaux_alarme' => $this->detecterSignauxAlarme(),
                'recommandations' => $this->recommandationsAmelioration()
            ],
            'volume_technique' => [
                'total_coups' => $this->volume_technique_total,
                'services' => $this->nb_services_pratiques,
                'retours' => $this->nb_retours_pratiques,
                'coups_fond' => ($this->nb_coups_droits ?? 0) + ($this->nb_revers ?? 0)
            ]
        ];
    }

    // Méthodes statiques pour analyses globales
    public static function analysePreparationJoueur($joueur_id, $periode_jours = 30)
    {
        $sessions = self::parJoueur($joueur_id)
            ->where('date_entrainement', '>=', now()->subDays($periode_jours))
            ->get();

        return [
            'nb_sessions' => $sessions->count(),
            'charge_moyenne' => $sessions->avg('charge_entrainement'),
            'intensite_moyenne' => $sessions->avg('intensite'),
            'progression_confiance' => $sessions->avg('impact_confiance'),
            'readiness_moyenne' => $sessions->avg('readiness_score')
        ];
    }

    public static function comparaisonAvecPairs($joueur_id, $ranking_range = 10)
    {
        // Comparaison avec des joueurs de niveau similaire
        // À implémenter selon votre logique de ranking
        return [
            'volume_entrainement' => 'comparison_data',
            'intensite_moyenne' => 'comparison_data',
            'efficacite_sessions' => 'comparison_data'
        ];
    }
}
