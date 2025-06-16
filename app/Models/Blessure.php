<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blessure extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'blessures';

    protected $fillable = [
        // Références essentielles
        'joueur_id',
        'type_blessure_id',
        'zone_corporelle_id',
        'tournoi_id',                // Blessure survenue pendant quel tournoi
        'match_tennis_id',           // Match spécifique où c'est arrivé

        // Temporalité et durée
        'date_debut',
        'date_fin',
        'date_guerison_prevue',
        'date_guerison_reelle',
        'duree_estimee_jours',
        'duree_reelle_jours',
        'temps_sans_competition',    // Jours d'arrêt compétition

        // Gravité et classification
        'gravite',                   // 1-10 (1=légère, 10=très grave)
        'niveau_douleur',            // 1-10 échelle douleur
        'pourcentage_handicap',      // 0-100% impact sur performance
        'phase_blessure',            // 'aigue', 'chronique', 'recovery', 'guerison'
        'classification_medicale',    // 'grade_1', 'grade_2', 'grade_3'

        // Circonstances et causes
        'circonstances',             // 'match', 'entrainement', 'vie_quotidienne'
        'surface_incident',          // Surface où c'est arrivé
        'conditions_meteo_incident', // Conditions lors de l'incident
        'fatigue_niveau',            // Niveau fatigue avant blessure (1-10)
        'minute_match_incident',     // À quel moment du match
        'score_moment_incident',     // Score au moment de la blessure

        // Impact sur le jeu (CRUCIAL pour prédictions)
        'impact_service',            // 0-100% réduction efficacité service
        'impact_coup_droit',         // 0-100% réduction coup droit
        'impact_revers',             // 0-100% réduction revers
        'impact_volees',             // 0-100% réduction volées
        'impact_mobilite',           // 0-100% réduction déplacements
        'impact_endurance',          // 0-100% réduction endurance
        'impact_mental',             // 0-100% impact psychologique

        // Gestion médicale
        'traitement_suivi',          // JSON: traitements, séances kiné, etc.
        'medecin_suivi_id',          // Médecin responsable
        'centre_medical_id',         // Centre de soins
        'antidouleurs_utilises',     // Médicaments utilisés
        'infiltrations',             // Nombre d'infiltrations
        'chirurgie_necessaire',      // Intervention chirurgicale
        'date_chirurgie',

        // Récidives et historique
        'est_recidive',              // Si c'est une récidive
        'blessure_originale_id',     // Référence vers blessure initiale
        'nombre_recidives',          // Combien de fois cette zone
        'facteur_recidive',          // Risque de récidive (0-100%)
        'prevention_appliquee',      // Mesures préventives prises

        // Performance post-blessure
        'matchs_retour_graduel',     // Nombre de matchs de réadaptation
        'performance_retour_pct',    // % performance au retour
        'rechute_dans_3_mois',       // Si rechute rapide
        'impact_long_terme',         // Impact permanent éventuel

        // Données biomécaniques
        'compensation_gestuelle',    // Adaptations gestuelles forcées
        'desequilibre_musculaire',   // Déséquilibres créés
        'zone_compensation_id',      // Autres zones affectées
        'modification_technique',    // Changements techniques requis

        // Contexte et environnement
        'stress_psychologique',      // Niveau stress à l'époque (1-10)
        'charge_entrainement',       // Intensité entraînement récente
        'nombre_matchs_recents',     // Nb matchs 2 semaines avant
        'voyages_recents',           // Fatigue voyages
        'changement_equipement',     // Nouveau matériel récent

        // Prévention et apprentissage
        'signal_alerte_avant',       // Y a-t-il eu des signaux ?
        'facteurs_declenchants',     // JSON: facteurs identifiés
        'recommandations_prevention', // Mesures pour éviter récidive
        'impact_calendrier',         // Tournois manqués
        'cout_medical_estime',       // Coût médical
        'cout_manque_a_gagner',      // Prize money perdu

        // Métadonnées et suivi
        'statut_actuel',             // 'active', 'guerison', 'chronique', 'geree'
        'derniere_evaluation',       // Date dernière évaluation médicale
        'prochaine_evaluation',      // Prochaine visite médicale
        'notes_medicales',           // Notes confidentielles médecin
        'visible_public',            // Si info publique ou privée
        'source_information_id',     // Source de l'info (joueur, staff, etc.)
        'fiabilite_diagnostic',       // 0-100% fiabilité du diagnostic
    ];

    protected $casts = [
        // Dates
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_guerison_prevue' => 'date',
        'date_guerison_reelle' => 'date',
        'date_chirurgie' => 'date',
        'derniere_evaluation' => 'date',
        'prochaine_evaluation' => 'date',

        // Entiers
        'gravite' => 'integer',
        'niveau_douleur' => 'integer',
        'duree_estimee_jours' => 'integer',
        'duree_reelle_jours' => 'integer',
        'temps_sans_competition' => 'integer',
        'fatigue_niveau' => 'integer',
        'minute_match_incident' => 'integer',
        'nombre_recidives' => 'integer',
        'matchs_retour_graduel' => 'integer',
        'infiltrations' => 'integer',
        'stress_psychologique' => 'integer',
        'charge_entrainement' => 'integer',
        'nombre_matchs_recents' => 'integer',

        // Pourcentages (décimaux)
        'pourcentage_handicap' => 'decimal:1',
        'impact_service' => 'decimal:1',
        'impact_coup_droit' => 'decimal:1',
        'impact_revers' => 'decimal:1',
        'impact_volees' => 'decimal:1',
        'impact_mobilite' => 'decimal:1',
        'impact_endurance' => 'decimal:1',
        'impact_mental' => 'decimal:1',
        'facteur_recidive' => 'decimal:1',
        'performance_retour_pct' => 'decimal:1',
        'fiabilite_diagnostic' => 'decimal:1',

        // Coûts
        'cout_medical_estime' => 'decimal:2',
        'cout_manque_a_gagner' => 'decimal:2',

        // JSON fields
        'traitement_suivi' => 'array',
        'facteurs_declenchants' => 'array',
        'recommandations_prevention' => 'array',

        // Booleans
        'est_recidive' => 'boolean',
        'chirurgie_necessaire' => 'boolean',
        'rechute_dans_3_mois' => 'boolean',
        'signal_alerte_avant' => 'boolean',
        'changement_equipement' => 'boolean',
        'visible_public' => 'boolean',
    ];

    protected $appends = [
        'duree_totale',
        'est_active',
        'est_chronique',
        'niveau_gravite_textuel',
        'impact_global_performance',
        'risque_pour_match',
        'phase_recuperation',
        'recommandation_participation',
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function joueur()
    {
        return $this->belongsTo(Joueur::class);
    }

    public function type()
    {
        return $this->belongsTo(TypeBlessure::class, 'type_blessure_id');
    }

    public function zone()
    {
        return $this->belongsTo(ZoneCorporelle::class, 'zone_corporelle_id');
    }

    public function tournoi()
    {
        return $this->belongsTo(Tournoi::class);
    }

    public function match()
    {
        return $this->belongsTo(MatchTennis::class, 'match_tennis_id');
    }

    public function medecin()
    {
        return $this->belongsTo(Medecin::class, 'medecin_suivi_id');
    }

    public function centreMedical()
    {
        return $this->belongsTo(CentreMedical::class, 'centre_medical_id');
    }

    public function sourceInformation()
    {
        return $this->belongsTo(SourceInformation::class, 'source_information_id');
    }

    // Relations de récidive
    public function blessureOriginale()
    {
        return $this->belongsTo(Blessure::class, 'blessure_originale_id');
    }

    public function recidives()
    {
        return $this->hasMany(Blessure::class, 'blessure_originale_id');
    }

    public function zoneCompensation()
    {
        return $this->belongsTo(ZoneCorporelle::class, 'zone_compensation_id');
    }

    // Relations vers impacts
    public function matchsAffectes()
    {
        return MatchTennis::where('joueur1_id', $this->joueur_id)
            ->orWhere('joueur2_id', $this->joueur_id)
            ->whereBetween('date_match', [$this->date_debut, $this->date_fin ?? now()]);
    }

    // ===================================================================
    // ACCESSORS
    // ===================================================================

    public function getDureeTotaleAttribute()
    {
        if (! $this->date_debut) {
            return 0;
        }

        $dateFin = $this->date_guerison_reelle ?? $this->date_fin ?? now();

        return $this->date_debut->diffInDays($dateFin);
    }

    public function getEstActiveAttribute()
    {
        return $this->statut_actuel === 'active' ||
            (! $this->date_fin && $this->date_debut <= now());
    }

    public function getEstChroniqueAttribute()
    {
        return $this->phase_blessure === 'chronique' ||
            $this->duree_totale > 90 || // Plus de 3 mois
            $this->nombre_recidives >= 3;
    }

    public function getNiveauGraviteTextuelAttribute()
    {
        $gravite = $this->gravite;

        if ($gravite >= 9) {
            return 'critique';
        }
        if ($gravite >= 7) {
            return 'severe';
        }
        if ($gravite >= 5) {
            return 'modere';
        }
        if ($gravite >= 3) {
            return 'leger';
        }

        return 'mineur';
    }

    public function getImpactGlobalPerformanceAttribute()
    {
        // Calcul pondéré de l'impact global
        $impacts = [
            'service' => $this->impact_service * 0.25,      // 25% - crucial tennis
            'mobilite' => $this->impact_mobilite * 0.20,    // 20% - déplacements
            'endurance' => $this->impact_endurance * 0.15,  // 15% - physique
            'coup_droit' => $this->impact_coup_droit * 0.15, // 15%
            'revers' => $this->impact_revers * 0.15,        // 15%
            'mental' => $this->impact_mental * 0.10,         // 10% - psychologique
        ];

        return round(array_sum($impacts), 1);
    }

    public function getRisquePourMatchAttribute()
    {
        if (! $this->est_active) {
            return 0;
        }

        $risque = 0;

        // Gravité de base
        $risque += $this->gravite * 5;

        // Impact sur performance
        $risque += $this->impact_global_performance * 0.5;

        // Douleur actuelle
        $risque += $this->niveau_douleur * 3;

        // Facteur récidive
        $risque += $this->facteur_recidive * 0.3;

        // Phase critique
        if ($this->phase_blessure === 'aigue') {
            $risque += 20;
        }

        return min(100, round($risque, 1));
    }

    public function getPhaseRecuperationAttribute()
    {
        if (! $this->est_active) {
            return 'guerison_complete';
        }

        $joursBlessure = $this->date_debut->diffInDays(now());
        $dureeEstimee = $this->duree_estimee_jours ?? 30;

        $progression = $joursBlessure / $dureeEstimee;

        if ($progression < 0.25) {
            return 'phase_aigue';
        }
        if ($progression < 0.50) {
            return 'debut_guerison';
        }
        if ($progression < 0.75) {
            return 'guerison_active';
        }
        if ($progression < 1.0) {
            return 'fin_guerison';
        }

        return 'retour_progressif';
    }

    public function getRecommandationParticipationAttribute()
    {
        if (! $this->est_active) {
            return 'participation_normale';
        }

        $risque = $this->risque_pour_match;

        if ($risque >= 80) {
            return 'interdiction_formelle';
        }
        if ($risque >= 60) {
            return 'fortement_deconseille';
        }
        if ($risque >= 40) {
            return 'participation_limitee';
        }
        if ($risque >= 20) {
            return 'surveillance_medicale';
        }

        return 'participation_normale_avec_suivi';
    }

    // ===================================================================
    // SCOPES
    // ===================================================================

    public function scopeActives($query)
    {
        return $query->where('statut_actuel', 'active')
            ->where(function ($q) {
                $q->whereNull('date_fin')
                    ->orWhere('date_fin', '>=', now());
            });
    }

    public function scopeChroniques($query)
    {
        return $query->where('phase_blessure', 'chronique')
            ->orWhere('nombre_recidives', '>=', 3);
    }

    public function scopeGraves($query, $seuilGravite = 7)
    {
        return $query->where('gravite', '>=', $seuilGravite);
    }

    public function scopeRecentes($query, $jours = 30)
    {
        return $query->where('date_debut', '>=', now()->subDays($jours));
    }

    public function scopeParZone($query, $zoneCode)
    {
        return $query->whereHas('zone', function ($q) use ($zoneCode) {
            $q->where('code', $zoneCode);
        });
    }

    public function scopeParType($query, $typeCode)
    {
        return $query->whereHas('type', function ($q) use ($typeCode) {
            $q->where('code', $typeCode);
        });
    }

    public function scopeAvecImpactSignificatif($query, $seuilImpact = 30)
    {
        return $query->where('impact_global_performance', '>=', $seuilImpact);
    }

    public function scopeRecidivantes($query)
    {
        return $query->where('est_recidive', true)
            ->orWhere('nombre_recidives', '>', 0);
    }

    public function scopeAnterieuresTournoi($query, Tournoi $tournoi)
    {
        return $query->where('date_debut', '<', $tournoi->date_debut)
            ->where(function ($q) use ($tournoi) {
                $q->whereNull('date_fin')
                    ->orWhere('date_fin', '>=', $tournoi->date_debut);
            });
    }

    // ===================================================================
    // METHODS TENNIS AI MEDICAL
    // ===================================================================

    /**
     * Évaluer l'impact sur un match spécifique
     */
    public function evaluerImpactMatch(MatchTennis $match)
    {
        if (! $this->est_active) {
            return null;
        }

        $impacts = [];

        // Impact par surface
        $surface = $match->surface?->code;
        $multiplierSurface = $this->getMultiplierSurface($surface);

        // Impact par durée de match prévue
        $dureeEstimee = $match->duree_predite ?? 120; // 2h par défaut
        $facteurDuree = min(1.5, $dureeEstimee / 120); // Plus long = plus d'impact

        // Calculs d'impact ajustés
        $impacts['service'] = min(100, $this->impact_service * $multiplierSurface);
        $impacts['mobilite'] = min(100, $this->impact_mobilite * $multiplierSurface * $facteurDuree);
        $impacts['endurance'] = min(100, $this->impact_endurance * $facteurDuree);
        $impacts['mental'] = $this->impact_mental;

        // Impact global pour ce match
        $impactGlobal = ($impacts['service'] * 0.3 + $impacts['mobilite'] * 0.3 +
            $impacts['endurance'] * 0.2 + $impacts['mental'] * 0.2);

        return [
            'impact_global' => round($impactGlobal, 1),
            'impacts_detailles' => $impacts,
            'recommandation' => $this->getRecommandationPourMatch($impactGlobal),
            'risque_aggravation' => $this->calculerRisqueAggravation($match),
            'adaptations_necessaires' => $this->getAdaptationsNecessaires(),
        ];
    }

    /**
     * Prédire l'évolution de la blessure
     */
    public function predireEvolution($joursAVenir = 14)
    {
        $evolution = [];
        $douleurActuelle = $this->niveau_douleur;
        $impactActuel = $this->impact_global_performance;

        for ($jour = 1; $jour <= $joursAVenir; $jour++) {
            // Amélioration naturelle (dépend de la phase)
            $tauxGuerison = $this->getTauxGuerison();

            $douleurPrevue = max(0, $douleurActuelle - ($tauxGuerison * $jour));
            $impactPrevu = max(0, $impactActuel - ($tauxGuerison * $jour * 2));

            $evolution[$jour] = [
                'douleur_estimee' => round($douleurPrevue, 1),
                'impact_performance' => round($impactPrevu, 1),
                'recommandation_jour' => $this->getRecommandationJour($impactPrevu),
                'risque_rechute' => $this->calculerRisqueRechute($jour),
            ];
        }

        return $evolution;
    }

    /**
     * Analyser les patterns de blessures du joueur
     */
    public function analyserPatternsJoueur()
    {
        $blessuresJoueur = Blessure::where('joueur_id', $this->joueur_id)
            ->orderBy('date_debut')
            ->get();

        $patterns = [
            'zones_frequentes' => $this->getZonesFrequentes($blessuresJoueur),
            'types_recurrents' => $this->getTypesRecurrents($blessuresJoueur),
            'facteurs_declenchants' => $this->getFacteursFrequents($blessuresJoueur),
            'periodes_risque' => $this->getPeriodesRisque($blessuresJoueur),
            'correlation_performance' => $this->getCorrelationPerformance($blessuresJoueur),
        ];

        return $patterns;
    }

    /**
     * Recommander des mesures préventives
     */
    public function recommanderPrevention()
    {
        $recommandations = [];

        // Basé sur la zone
        $zoneCode = $this->zone?->code;
        $recommandations['specifiques_zone'] = $this->getPreventionParZone($zoneCode);

        // Basé sur les récidives
        if ($this->nombre_recidives > 0) {
            $recommandations['anti_recidive'] = [
                'renforcement_musculaire' => 'Priorité absolue',
                'echauffement_prolonge' => 'Obligatoire',
                'surveillance_medicale' => 'Régulière',
            ];
        }

        // Basé sur les facteurs déclenchants
        if ($this->facteurs_declenchants) {
            $recommandations['facteurs_triggers'] = $this->getPreventionFacteurs();
        }

        // Basé sur la surface
        if ($this->surface_incident) {
            $recommandations['par_surface'] = $this->getPreventionParSurface();
        }

        return $recommandations;
    }

    /**
     * Calculer l'impact sur le classement
     */
    public function calculerImpactClassement()
    {
        $impact = [
            'points_perdus_directs' => 0,
            'tournois_manques' => 0,
            'degradation_estimee' => 0,
        ];

        // Tournois manqués pendant la blessure
        if ($this->date_debut && $this->duree_estimee_jours) {
            $dateFin = $this->date_debut->addDays($this->duree_estimee_jours);

            $tournoiManques = Tournoi::whereBetween('date_debut', [$this->date_debut, $dateFin])
                ->where('importance_points', '>', 0)
                ->get();

            $impact['tournois_manques'] = $tournoiManques->count();
            $impact['points_perdus_directs'] = $tournoiManques->sum('points_defending');
        }

        // Impact sur performance après retour
        if ($this->performance_retour_pct && $this->performance_retour_pct < 90) {
            $impact['degradation_estimee'] = (100 - $this->performance_retour_pct) * 2;
        }

        return $impact;
    }

    /**
     * Mettre à jour l'état après évaluation médicale
     */
    public function mettreAJourEvaluation(array $evaluationData)
    {
        // Mettre à jour les données médicales
        $this->niveau_douleur = $evaluationData['douleur'] ?? $this->niveau_douleur;
        $this->pourcentage_handicap = $evaluationData['handicap'] ?? $this->pourcentage_handicap;
        $this->phase_blessure = $evaluationData['phase'] ?? $this->phase_blessure;

        // Recalculer les impacts
        $this->recalculerImpacts();

        // Mettre à jour prédictions
        $this->ajusterPrognostic();

        $this->derniere_evaluation = now();
        $this->save();

        // Notifier si changement significatif
        if ($this->detecterChangementSignificatif($evaluationData)) {
            $this->notifierEquipe();
        }
    }

    /**
     * Détecter les signaux d'alerte pour récidive
     */
    public function detecterSignauxRecidive()
    {
        $signaux = [];

        // Douleur qui revient
        if ($this->statut_actuel === 'guerison' && $this->niveau_douleur > 3) {
            $signaux[] = [
                'type' => 'douleur_retour',
                'niveau_alerte' => 'moyen',
                'message' => 'Retour de douleur après guérison',
            ];
        }

        // Compensation excessive
        if ($this->compensation_gestuelle && $this->desequilibre_musculaire) {
            $signaux[] = [
                'type' => 'compensation_dangereuse',
                'niveau_alerte' => 'eleve',
                'message' => 'Compensations créant déséquilibres',
            ];
        }

        // Charge de travail trop importante
        if ($this->charge_entrainement >= 8 && $this->facteur_recidive > 60) {
            $signaux[] = [
                'type' => 'surcharge_entrainement',
                'niveau_alerte' => 'eleve',
                'message' => 'Charge d\'entraînement excessive pour cette blessure',
            ];
        }

        return $signaux;
    }

    // ===================================================================
    // HELPER METHODS
    // ===================================================================

    private function getMultiplierSurface($surface)
    {
        $multipliers = [
            'terre_battue' => 1.2, // Plus dur pour mobilité
            'dur' => 1.0,
            'gazon' => 0.9,
            'indoor' => 0.95,
        ];

        return $multipliers[$surface] ?? 1.0;
    }

    private function getTauxGuerison()
    {
        $taux = 0.1; // Base 10% par jour

        // Ajustements selon gravité
        if ($this->gravite <= 3) {
            $taux = 0.15;
        } elseif ($this->gravite >= 7) {
            $taux = 0.05;
        }

        // Ajustements selon phase
        if ($this->phase_blessure === 'aigue') {
            $taux *= 0.5;
        } elseif ($this->phase_blessure === 'recovery') {
            $taux *= 1.5;
        }

        return $taux;
    }

    private function getRecommandationPourMatch($impactGlobal)
    {
        if ($impactGlobal >= 70) {
            return 'forfait_obligatoire';
        }
        if ($impactGlobal >= 50) {
            return 'participation_tres_risquee';
        }
        if ($impactGlobal >= 30) {
            return 'participation_avec_limitations';
        }
        if ($impactGlobal >= 15) {
            return 'surveillance_renforcee';
        }

        return 'participation_normale_avec_suivi';
    }

    private function calculerRisqueAggravation(MatchTennis $match)
    {
        $risque = $this->gravite * 5;

        // Plus de risque si match important
        if ($match->importance_match >= 3) {
            $risque += 15;
        }

        // Plus de risque si adversaire difficile
        if ($match->difficulte_prevue >= 7) {
            $risque += 10;
        }

        return min(100, $risque);
    }

    private function getAdaptationsNecessaires()
    {
        $adaptations = [];

        if ($this->impact_service > 30) {
            $adaptations[] = 'Réduire puissance service';
        }

        if ($this->impact_mobilite > 40) {
            $adaptations[] = 'Éviter déplacements extrêmes';
        }

        if ($this->impact_endurance > 50) {
            $adaptations[] = 'Gestion effort sur match long';
        }

        return $adaptations;
    }

    private function getZonesFrequentes($blessures)
    {
        return $blessures->groupBy('zone_corporelle_id')
            ->map->count()
            ->sortDesc()
            ->take(3);
    }

    private function getFacteursFrequents($blessures)
    {
        $facteurs = [];
        foreach ($blessures as $blessure) {
            if ($blessure->facteurs_declenchants) {
                foreach ($blessure->facteurs_declenchants as $facteur) {
                    $facteurs[$facteur] = ($facteurs[$facteur] ?? 0) + 1;
                }
            }
        }

        return $facteurs;
    }

    private function recalculerImpacts()
    {
        // Recalcul automatique basé sur évaluation récente
        // Logique complexe basée sur zone, type, phase...
    }

    private function detecterChangementSignificatif($evaluation)
    {
        return abs($this->niveau_douleur - ($evaluation['douleur'] ?? 0)) >= 3;
    }

    private function notifierEquipe()
    {
        // Système de notification équipe médicale/coaching
    }

    // ===================================================================
    // STATIC METHODS
    // ===================================================================

    /**
     * Obtenir les joueurs actuellement blessés
     */
    public static function getJoueursBlesses()
    {
        return self::actives()
            ->with(['joueur', 'zone', 'type'])
            ->orderBy('gravite', 'desc')
            ->get()
            ->groupBy('joueur_id');
    }

    /**
     * Analyser les tendances de blessures
     */
    public static function analyserTendances()
    {
        return [
            'zones_plus_frequentes' => self::selectRaw('zone_corporelle_id, COUNT(*) as total')
                ->groupBy('zone_corporelle_id')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->with('zone')
                ->get(),
            'types_plus_frequents' => self::selectRaw('type_blessure_id, COUNT(*) as total')
                ->groupBy('type_blessure_id')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->with('type')
                ->get(),
            'periodes_risque' => self::selectRaw('MONTH(date_debut) as mois, COUNT(*) as total')
                ->groupBy('mois')
                ->orderBy('total', 'desc')
                ->get(),
        ];
    }

    // ===================================================================
    // VALIDATION RULES
    // ===================================================================

    public static function validationRules()
    {
        return [
            'joueur_id' => 'required|exists:joueurs,id',
            'type_blessure_id' => 'required|exists:type_blessures,id',
            'zone_corporelle_id' => 'required|exists:zone_corporelles,id',
            'date_debut' => 'required|date',
            'gravite' => 'required|integer|between:1,10',
            'niveau_douleur' => 'required|integer|between:0,10',
            'pourcentage_handicap' => 'required|numeric|between:0,100',
        ];
    }
}
