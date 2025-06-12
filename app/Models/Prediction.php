<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prediction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'predictions';

    protected $fillable = [
        // Références essentielles
        'match_tennis_id',
        'algorithme_ia_id',
        'type_prediction_id',
        'log_prediction_id',

        // Résultat et confiance
        'resultat_predit',          // JSON: gagnant, score, sets détaillés
        'confiance_globale',        // 0-100%
        'confiance_par_facteur',    // JSON: confiance par type de donnée

        // Probabilités détaillées
        'probabilite_joueur1',      // 0-100%
        'probabilite_joueur2',      // 0-100%
        'probabilite_nul',          // Très rare mais possible
        'marge_erreur',             // +/- %

        // Prédictions avancées
        'score_predit_exact',       // "6-4, 6-2"
        'sets_predit',              // 2, 3, 4, 5
        'duree_predite',            // en minutes
        'nombre_aces_predit',       // Total des deux joueurs
        'tie_breaks_predits',       // Nombre de tie-breaks

        // Facteurs utilisés dans la prédiction
        'facteur_elo',              // Impact ELO (0-100)
        'facteur_surface',          // Impact surface (0-100)
        'facteur_h2h',              // Impact head-to-head (0-100)
        'facteur_forme_recente',    // Impact forme (0-100)
        'facteur_fatigue',          // Impact fatigue (0-100)
        'facteur_meteo',            // Impact météo (0-100)
        'facteur_psychologique',    // Impact mental (0-100)
        'facteur_physique',         // Impact condition physique (0-100)

        // Contexte de la prédiction
        'moment_prediction',        // pre_match, live_set1, live_set2, etc.
        'donnees_disponibles',      // JSON: quelles données étaient disponibles
        'modele_version',           // Version du modèle ML utilisé
        'features_utilisees',       // JSON: liste des features ML
        'poids_features',           // JSON: importance de chaque feature

        // Résultats réels (après le match)
        'resultat_reel',            // JSON: vrai résultat
        'accuracy_globale',         // Précision de cette prédiction
        'accuracy_par_type',        // JSON: précision par type
        'erreur_absolue',           // Différence avec la réalité
        'facteur_surprise',         // Si upset détecté

        // Machine Learning
        'rubixML_features',         // JSON: features pour RubixML
        'rubixML_prediction',       // Résultat brut RubixML
        'rubixML_confidence',       // Confiance RubixML
        'ensemble_method',          // Méthode d'ensemble utilisée
        'cross_validation_score',   // Score validation croisée

        // Métadonnées temps réel
        'created_at_match_time',    // Moment relatif au match
        'mise_a_jour_live',         // JSON: mises à jour pendant match
        'validee_par_expert',       // Validation humaine
        'commentaire_expert',       // Notes d'analyse
        'niveau_difficulte',        // Difficulté de prédiction (1-10)

        // Performance et apprentissage
        'contribue_entrainement',   // Si utilisé pour réentraîner
        'flagged_for_review',       // Flaggé pour review
        'outlier_detected',         // Détection d'anomalie
        'model_drift_indicator'     // Indicateur de dérive du modèle
    ];

    protected $casts = [
        // Probabilités et scores
        'probabilite_joueur1' => 'decimal:2',
        'probabilite_joueur2' => 'decimal:2',
        'probabilite_nul' => 'decimal:2',
        'confiance_globale' => 'decimal:2',
        'marge_erreur' => 'decimal:2',
        'accuracy_globale' => 'decimal:2',
        'erreur_absolue' => 'decimal:3',

        // Facteurs (tous 0-100)
        'facteur_elo' => 'decimal:1',
        'facteur_surface' => 'decimal:1',
        'facteur_h2h' => 'decimal:1',
        'facteur_forme_recente' => 'decimal:1',
        'facteur_fatigue' => 'decimal:1',
        'facteur_meteo' => 'decimal:1',
        'facteur_psychologique' => 'decimal:1',
        'facteur_physique' => 'decimal:1',

        // Prédictions spécifiques
        'duree_predite' => 'integer',
        'nombre_aces_predit' => 'integer',
        'tie_breaks_predits' => 'integer',
        'sets_predit' => 'integer',

        // ML et données complexes
        'rubixML_confidence' => 'decimal:3',
        'cross_validation_score' => 'decimal:3',
        'niveau_difficulte' => 'integer',
        'facteur_surprise' => 'decimal:2',

        // JSON fields
        'resultat_predit' => 'array',
        'confiance_par_facteur' => 'array',
        'resultat_reel' => 'array',
        'accuracy_par_type' => 'array',
        'donnees_disponibles' => 'array',
        'features_utilisees' => 'array',
        'poids_features' => 'array',
        'rubixML_features' => 'array',
        'mise_a_jour_live' => 'array',

        // Booleans
        'validee_par_expert' => 'boolean',
        'contribue_entrainement' => 'boolean',
        'flagged_for_review' => 'boolean',
        'outlier_detected' => 'boolean',
        'model_drift_indicator' => 'boolean',

        // Dates
        'created_at_match_time' => 'datetime'
    ];

    protected $appends = [
        'qualite_prediction',
        'niveau_confiance_textuel',
        'joueur_favori',
        'facteur_dominant',
        'precision_relative',
        'surprise_niveau',
        'apprentissage_value'
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function match()
    {
        return $this->belongsTo(MatchTennis::class, 'match_tennis_id');
    }

    public function algorithme()
    {
        return $this->belongsTo(AlgorithmeIA::class, 'algorithme_ia_id');
    }

    public function type()
    {
        return $this->belongsTo(TypePrediction::class, 'type_prediction_id');
    }

    public function log()
    {
        return $this->belongsTo(LogPrediction::class, 'log_prediction_id');
    }

    // Relations vers données utilisées
    public function confrontation()
    {
        if (!$this->match) return null;

        return Confrontation::where(function($query) {
            $query->where([
                'joueur1_id' => $this->match->joueur1_id,
                'joueur2_id' => $this->match->joueur2_id
            ])->orWhere([
                'joueur1_id' => $this->match->joueur2_id,
                'joueur2_id' => $this->match->joueur1_id
            ]);
        })->first();
    }

    public function statistiquesJ1()
    {
        if (!$this->match) return null;

        return StatistiqueJoueur::where('joueur_id', $this->match->joueur1_id)
            ->where('surface_id', $this->match->surface_id)
            ->latest()
            ->first();
    }

    public function statistiquesJ2()
    {
        if (!$this->match) return null;

        return StatistiqueJoueur::where('joueur_id', $this->match->joueur2_id)
            ->where('surface_id', $this->match->surface_id)
            ->latest()
            ->first();
    }

    // ===================================================================
    // ACCESSORS (Intelligence automatique)
    // ===================================================================

    public function getQualitePredictionAttribute()
    {
        if (!$this->accuracy_globale) return 'non_evaluee';

        $accuracy = $this->accuracy_globale;

        if ($accuracy >= 90) return 'excellente';
        if ($accuracy >= 80) return 'tres_bonne';
        if ($accuracy >= 70) return 'bonne';
        if ($accuracy >= 60) return 'moyenne';
        if ($accuracy >= 50) return 'faible';
        return 'tres_faible';
    }

    public function getNiveauConfianceTextuelAttribute()
    {
        $confiance = $this->confiance_globale;

        if ($confiance >= 95) return 'confiance_absolue';
        if ($confiance >= 85) return 'tres_confiant';
        if ($confiance >= 75) return 'confiant';
        if ($confiance >= 65) return 'moderement_confiant';
        if ($confiance >= 50) return 'peu_confiant';
        return 'tres_incertain';
    }

    public function getJoueurFavoriAttribute()
    {
        if (!$this->match) return null;

        return $this->probabilite_joueur1 > $this->probabilite_joueur2 ?
            $this->match->joueur1_id : $this->match->joueur2_id;
    }

    public function getFacteurDominantAttribute()
    {
        $facteurs = [
            'elo' => $this->facteur_elo,
            'surface' => $this->facteur_surface,
            'h2h' => $this->facteur_h2h,
            'forme_recente' => $this->facteur_forme_recente,
            'fatigue' => $this->facteur_fatigue,
            'meteo' => $this->facteur_meteo,
            'psychologique' => $this->facteur_psychologique,
            'physique' => $this->facteur_physique
        ];

        return array_search(max($facteurs), $facteurs);
    }

    public function getPrecisionRelativeAttribute()
    {
        if (!$this->accuracy_globale || !$this->confiance_globale) return null;

        // Ratio entre accuracy réelle et confiance prédite
        return round($this->accuracy_globale / $this->confiance_globale, 3);
    }

    public function getSurpriseNiveauAttribute()
    {
        if (!$this->facteur_surprise) return 'normal';

        $surprise = $this->facteur_surprise;

        if ($surprise >= 8) return 'upset_majeur';
        if ($surprise >= 6) return 'upset_notable';
        if ($surprise >= 4) return 'surprise_moderee';
        if ($surprise >= 2) return 'legere_surprise';
        return 'normal';
    }

    public function getApprentissageValueAttribute()
    {
        $value = 0;

        // Plus d'erreur = plus d'apprentissage
        if ($this->erreur_absolue) {
            $value += min($this->erreur_absolue * 10, 30);
        }

        // Outliers sont précieux
        if ($this->outlier_detected) $value += 20;

        // Upsets majeurs = apprentissage important
        if ($this->facteur_surprise >= 6) $value += 25;

        // Prédictions difficiles bien réussies
        if ($this->niveau_difficulte >= 8 && $this->accuracy_globale >= 80) {
            $value += 25;
        }

        return min(100, round($value, 1));
    }

    public function getDureeFormateeAttribute()
    {
        if (!$this->duree_predite) return null;

        $heures = floor($this->duree_predite / 60);
        $minutes = $this->duree_predite % 60;

        return sprintf('%dh%02d', $heures, $minutes);
    }

    // ===================================================================
    // SCOPES
    // ===================================================================

    public function scopePreMatch($query)
    {
        return $query->where('moment_prediction', 'pre_match');
    }

    public function scopeLive($query)
    {
        return $query->where('moment_prediction', 'LIKE', 'live_%');
    }

    public function scopeAvecResultat($query)
    {
        return $query->whereNotNull('resultat_reel');
    }

    public function scopePrecises($query, $seuilAccuracy = 80)
    {
        return $query->where('accuracy_globale', '>=', $seuilAccuracy);
    }

    public function scopeConfiantes($query, $seuilConfiance = 75)
    {
        return $query->where('confiance_globale', '>=', $seuilConfiance);
    }

    public function scopeUpsets($query)
    {
        return $query->where('facteur_surprise', '>=', 4);
    }

    public function scopeOutliers($query)
    {
        return $query->where('outlier_detected', true);
    }

    public function scopeValidees($query)
    {
        return $query->where('validee_par_expert', true);
    }

    public function scopeParAlgorithme($query, $algorithmeId)
    {
        return $query->where('algorithme_ia_id', $algorithmeId);
    }

    public function scopeRecentes($query, $jours = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($jours));
    }

    public function scopeAprentissage($query)
    {
        return $query->where('contribue_entrainement', true);
    }

    public function scopeDifficiles($query, $niveauMin = 7)
    {
        return $query->where('niveau_difficulte', '>=', $niveauMin);
    }

    // ===================================================================
    // METHODS TENNIS AI ULTRA-AVANCÉES
    // ===================================================================

    /**
     * Générer une prédiction complète avec RubixML
     */
    public static function genererPredictionComplete(MatchTennis $match, $algorithmeIA = null)
    {
        $prediction = new self();
        $prediction->match_tennis_id = $match->id;
        $prediction->algorithme_ia_id = $algorithmeIA?->id ?? AlgorithmeIA::getDefault()->id;
        $prediction->type_prediction_id = TypePrediction::where('code', 'gagnant')->first()->id;
        $prediction->moment_prediction = 'pre_match';
        $prediction->created_at_match_time = now();

        // 1. Collecter toutes les features
        $features = $prediction->collecterFeatures($match);
        $prediction->rubixML_features = $features;
        $prediction->features_utilisees = array_keys($features);

        // 2. Calculs probabilistes avancés
        $probabilites = $prediction->calculerProbabilites($match, $features);
        $prediction->probabilite_joueur1 = $probabilites['joueur1'];
        $prediction->probabilite_joueur2 = $probabilites['joueur2'];

        // 3. Facteurs individuels
        $facteurs = $prediction->analyserFacteurs($match);
        foreach ($facteurs as $facteur => $valeur) {
            $prediction->{"facteur_{$facteur}"} = $valeur;
        }

        // 4. Prédictions détaillées
        $details = $prediction->predireDetails($match, $probabilites);
        $prediction->score_predit_exact = $details['score'];
        $prediction->sets_predit = $details['sets'];
        $prediction->duree_predite = $details['duree'];
        $prediction->nombre_aces_predit = $details['aces'];
        $prediction->tie_breaks_predits = $details['tie_breaks'];

        // 5. Confiance et métadonnées
        $prediction->confiance_globale = $prediction->calculerConfiance($features, $facteurs);
        $prediction->niveau_difficulte = $prediction->evaluerDifficulte($match);
        $prediction->donnees_disponibles = $prediction->inventorierDonnees($match);

        // 6. Résultat final structuré
        $prediction->resultat_predit = [
            'gagnant_predit_id' => $prediction->joueur_favori,
            'probabilite_victoire' => max($prediction->probabilite_joueur1, $prediction->probabilite_joueur2),
            'score_predit' => $prediction->score_predit_exact,
            'confiance' => $prediction->confiance_globale,
            'facteur_cle' => $prediction->facteur_dominant
        ];

        $prediction->save();
        return $prediction;
    }

    /**
     * Collecter toutes les features pour ML
     */
    private function collecterFeatures(MatchTennis $match)
    {
        $features = [];

        // Features joueurs de base
        $j1 = $match->joueur1;
        $j2 = $match->joueur2;

        $features['j1_classement'] = $j1->classement_atp_wta ?? 999;
        $features['j2_classement'] = $j2->classement_atp_wta ?? 999;
        $features['diff_classement'] = abs($features['j1_classement'] - $features['j2_classement']);

        // Features ELO
        $statsJ1 = $this->statistiquesJ1();
        $statsJ2 = $this->statistiquesJ2();

        if ($statsJ1 && $statsJ2) {
            $features['j1_elo'] = $statsJ1->elo_rating ?? 1500;
            $features['j2_elo'] = $statsJ2->elo_rating ?? 1500;
            $features['diff_elo'] = $features['j1_elo'] - $features['j2_elo'];

            // Features performance
            $features['j1_ratio_victoires'] = $statsJ1->ratio_victoires;
            $features['j2_ratio_victoires'] = $statsJ2->ratio_victoires;
            $features['j1_force_service'] = $statsJ1->force_service;
            $features['j2_force_service'] = $statsJ2->force_service;
            $features['j1_force_retour'] = $statsJ1->force_retour;
            $features['j2_force_retour'] = $statsJ2->force_retour;
        }

        // Features head-to-head
        $h2h = $this->confrontation();
        if ($h2h) {
            $features['h2h_total_matchs'] = $h2h->total_matchs;
            $features['h2h_j1_pourcentage'] = $h2h->pourcentage_j1;
            $features['h2h_equilibre'] = $h2h->equilibre_confrontation === 'très_équilibré' ? 1 : 0;

            // H2H par surface
            $surface = $match->surface?->code;
            if ($surface) {
                $features["h2h_j1_{$surface}"] = $h2h->{"victoires_j1_{$surface}"} ?? 0;
                $features["h2h_j2_{$surface}"] = $h2h->{"victoires_j2_{$surface}"} ?? 0;
            }
        }

        // Features contextuelles
        $features['surface_id'] = $match->surface_id;
        $features['categorie_tournoi'] = $match->tournoi?->categorie_tournoi_id;
        $features['importance_match'] = $match->importance_match ?? 1;

        // Features météo/conditions
        $features['temperature'] = $match->temperature ?? 20;
        $features['humidite'] = $match->humidite ?? 50;
        $features['indoor'] = $match->indoor_outdoor === 'indoor' ? 1 : 0;

        // Features temporelles
        $features['jour_semaine'] = $match->date_match->dayOfWeek;
        $features['heure_match'] = $match->heure_match ? $match->heure_match->hour : 14;

        return $features;
    }

    /**
     * Calculer probabilités avec algorithme sophistiqué
     */
    private function calculerProbabilites(MatchTennis $match, array $features)
    {
        $scoreJ1 = 50; // Base neutre

        // 1. Impact ELO (35% du poids)
        if (isset($features['diff_elo'])) {
            $eloImpact = $features['diff_elo'] / 400 * 35;
            $scoreJ1 += $eloImpact;
        }

        // 2. Impact H2H (25% du poids)
        if (isset($features['h2h_j1_pourcentage'])) {
            $h2hImpact = ($features['h2h_j1_pourcentage'] - 50) * 0.25;
            $scoreJ1 += $h2hImpact;
        }

        // 3. Impact surface spécifique (20% du poids)
        $surface = $match->surface?->code;
        if ($surface && isset($features["h2h_j1_{$surface}"], $features["h2h_j2_{$surface}"])) {
            $totalSurface = $features["h2h_j1_{$surface}"] + $features["h2h_j2_{$surface}"];
            if ($totalSurface > 0) {
                $surfaceImpact = (($features["h2h_j1_{$surface}"] / $totalSurface) - 0.5) * 20;
                $scoreJ1 += $surfaceImpact;
            }
        }

        // 4. Impact forme/statistiques récentes (15% du poids)
        if (isset($features['j1_ratio_victoires'], $features['j2_ratio_victoires'])) {
            $formeImpact = ($features['j1_ratio_victoires'] - $features['j2_ratio_victoires']) * 15;
            $scoreJ1 += $formeImpact;
        }

        // 5. Impact conditions/contexte (5% du poids)
        if (isset($features['importance_match'])) {
            $contextImpact = ($features['importance_match'] - 1) * 2;
            $scoreJ1 += $contextImpact;
        }

        // Normalisation et bornes
        $probJ1 = max(5, min(95, $scoreJ1));
        $probJ2 = 100 - $probJ1;

        return [
            'joueur1' => round($probJ1, 2),
            'joueur2' => round($probJ2, 2)
        ];
    }

    /**
     * Analyser l'importance de chaque facteur
     */
    private function analyserFacteurs(MatchTennis $match)
    {
        $facteurs = [];

        // ELO (toujours important)
        $facteurs['elo'] = 85;

        // Surface (très important au tennis)
        $facteurs['surface'] = 75;

        // H2H (dépend du nombre de matchs)
        $h2h = $this->confrontation();
        $facteurs['h2h'] = $h2h ? min(90, $h2h->total_matchs * 10) : 10;

        // Forme récente
        $facteurs['forme_recente'] = 70;

        // Fatigue (dépend du contexte)
        $facteurs['fatigue'] = 40;

        // Météo (plus important outdoor)
        $facteurs['meteo'] = $match->indoor_outdoor === 'outdoor' ? 60 : 20;

        // Psychologique (plus important dans les rivalités)
        $facteurs['psychologique'] = $h2h && $h2h->niveau_rivalite !== 'confrontation_standard' ? 80 : 50;

        // Physique
        $facteurs['physique'] = 55;

        return $facteurs;
    }

    /**
     * Prédire détails spécifiques du match
     */
    private function predireDetails(MatchTennis $match, array $probabilites)
    {
        $probMax = max($probabilites['joueur1'], $probabilites['joueur2']);

        // Prédiction nombre de sets
        $sets = 2; // Par défaut
        if ($probMax < 60) $sets = 3; // Match serré = plus long
        if ($match->tournoi?->categorie?->code === 'grand_chelem') {
            $sets = $probMax > 80 ? 3 : 4; // Hommes Grand Chelem
        }

        // Prédiction score basé sur probabilité
        $scoreOptions = $this->genererOptionsScore($sets, $probMax);
        $scorePredit = $scoreOptions[array_rand($scoreOptions)];

        // Prédiction durée
        $dureeBase = $sets * 45; // 45min par set en moyenne
        $facteurVariation = $probMax > 75 ? 0.8 : 1.2; // Match déséquilibré = plus rapide
        $duree = (int)($dureeBase * $facteurVariation);

        // Prédiction aces (basé sur statistiques joueurs)
        $acesPredit = $this->predireAces($match);

        // Prédiction tie-breaks
        $tieBreaks = $probMax < 65 ? rand(1, 2) : 0;

        return [
            'score' => $scorePredit,
            'sets' => $sets,
            'duree' => $duree,
            'aces' => $acesPredit,
            'tie_breaks' => $tieBreaks
        ];
    }

    /**
     * Calculer niveau de confiance
     */
    private function calculerConfiance(array $features, array $facteurs)
    {
        $confiance = 50; // Base

        // Plus de données = plus de confiance
        $nbFeatures = count(array_filter($features, fn($v) => $v !== null));
        $confiance += min($nbFeatures * 2, 30);

        // H2H riche = plus de confiance
        if (isset($features['h2h_total_matchs'])) {
            $confiance += min($features['h2h_total_matchs'] * 3, 15);
        }

        // Différence ELO claire = plus de confiance
        if (isset($features['diff_elo'])) {
            $confiance += min(abs($features['diff_elo']) / 50, 10);
        }

        // Facteurs cohérents = plus de confiance
        $facteursMoyens = array_sum($facteurs) / count($facteurs);
        if ($facteursMoyens > 70) $confiance += 10;

        return min(95, max(30, round($confiance, 1)));
    }

    /**
     * Évaluer difficulté de prédiction
     */
    private function evaluerDifficulte(MatchTennis $match)
    {
        $difficulte = 5; // Base

        // Joueurs de niveau similaire = plus difficile
        $j1 = $match->joueur1;
        $j2 = $match->joueur2;

        if ($j1->classement_atp_wta && $j2->classement_atp_wta) {
            $diffClassement = abs($j1->classement_atp_wta - $j2->classement_atp_wta);
            if ($diffClassement < 10) $difficulte += 3;
            elseif ($diffClassement < 50) $difficulte += 1;
        }

        // H2H équilibré = plus difficile
        $h2h = $this->confrontation();
        if ($h2h && $h2h->equilibre_confrontation === 'très_équilibré') {
            $difficulte += 2;
        }

        // Premier match entre joueurs = plus difficile
        if (!$h2h || $h2h->total_matchs === 0) {
            $difficulte += 2;
        }

        return min(10, max(1, $difficulte));
    }

    /**
     * Évaluer après le match réel
     */
    public function evaluerApresMatch(MatchTennis $match)
    {
        if (!$match->est_termine || !$match->gagnant_id) return;

        // Stocker résultat réel
        $this->resultat_reel = [
            'gagnant_reel_id' => $match->gagnant_id,
            'score_reel' => $match->score_final,
            'duree_reelle' => $match->duree_match,
            'sets_reels' => count($match->score_detaille ?? [])
        ];

        // Calculer accuracy
        $this->accuracy_globale = $this->calculerAccuracy($match);
        $this->accuracy_par_type = $this->calculerAccuracyParType($match);

        // Détecter upset
        $this->facteur_surprise = $match->getFacteurSurprise();

        // Calculer erreur absolue
        $probPredite = $this->joueur_favori == $match->gagnant_id ?
            max($this->probabilite_joueur1, $this->probabilite_joueur2) :
            min($this->probabilite_joueur1, $this->probabilite_joueur2);

        $this->erreur_absolue = abs($probPredite - 100); // 100% si correct, 0% si faux

        // Détection outlier
        $this->outlier_detected = $this->detecterOutlier($match);

        // Valeur pour apprentissage
        $this->contribue_entrainement = $this->erreur_absolue > 0.2 || $this->outlier_detected;

        $this->save();

        // Mettre à jour modèle si nécessaire
        if ($this->contribue_entrainement) {
            $this->contribuerAApprentissage();
        }
    }

    /**
     * Calculer accuracy de la prédiction
     */
    private function calculerAccuracy(MatchTennis $match)
    {
        $score = 0;
        $totalPoints = 0;

        // Accuracy du gagnant (40 points)
        $totalPoints += 40;
        if ($this->joueur_favori == $match->gagnant_id) {
            $score += 40;
        }

        // Accuracy de la probabilité (30 points)
        $totalPoints += 30;
        $probPredite = $this->joueur_favori == $match->gagnant_id ?
            max($this->probabilite_joueur1, $this->probabilite_joueur2) :
            min($this->probabilite_joueur1, $this->probabilite_joueur2);

        if ($this->joueur_favori == $match->gagnant_id) {
            // Bonne prédiction : plus la prob était haute, plus c'est bon
            $score += ($probPredite / 100) * 30;
        } else {
            // Mauvaise prédiction : moins la prob était haute, moins c'est grave
            $score += ((100 - $probPredite) / 100) * 30;
        }

        // Accuracy du nombre de sets (15 points)
        if ($this->sets_predit && $match->score_detaille) {
            $totalPoints += 15;
            $setsReels = count($match->score_detaille);
            if ($this->sets_predit == $setsReels) {
                $score += 15;
            } elseif (abs($this->sets_predit - $setsReels) == 1) {
                $score += 7;
            }
        }

        // Accuracy de la durée (15 points)
        if ($this->duree_predite && $match->duree_match) {
            $totalPoints += 15;
            $erreurDuree = abs($this->duree_predite - $match->duree_match) / $match->duree_match;
            if ($erreurDuree <= 0.1) $score += 15;      // ±10%
            elseif ($erreurDuree <= 0.2) $score += 10;  // ±20%
            elseif ($erreurDuree <= 0.3) $score += 5;   // ±30%
        }

        return $totalPoints > 0 ? round(($score / $totalPoints) * 100, 2) : 0;
    }

    /**
     * Détecter si c'est un outlier/anomalie
     */
    private function detecterOutlier(MatchTennis $match)
    {
        // Upset majeur avec haute confiance = outlier
        if ($this->facteur_surprise >= 6 && $this->confiance_globale >= 80) {
            return true;
        }

        // Prédiction très confiante mais très fausse
        if ($this->confiance_globale >= 90 && $this->accuracy_globale <= 30) {
            return true;
        }

        // Match facile prédit mais upset réel
        if ($this->niveau_difficulte <= 3 && $this->facteur_surprise >= 5) {
            return true;
        }

        return false;
    }

    /**
     * Contribuer à l'apprentissage du modèle
     */
    private function contribuerAApprentissage()
    {
        // Ici, intégration avec RubixML pour réentraînement
        // TODO: Implémenter la logique de réentraînement

        // Marquer pour review si c'est un outlier important
        if ($this->outlier_detected && $this->facteur_surprise >= 7) {
            $this->flagged_for_review = true;
            $this->save();
        }
    }

    // Helper methods
    private function genererOptionsScore($sets, $probabilite)
    {
        if ($sets == 2) {
            return $probabilite > 70 ? ['6-2, 6-1', '6-3, 6-2', '6-1, 6-3'] :
                ($probabilite > 55 ? ['6-4, 6-3', '7-5, 6-4', '6-3, 7-5'] :
                    ['7-6, 6-4', '6-4, 7-6', '7-5, 7-6']);
        }
        // Logic for 3+ sets...
        return ['6-4, 3-6, 6-3'];
    }

    private function predireAces(MatchTennis $match)
    {
        $acesJ1 = $this->statistiquesJ1()?->aces ?? 5;
        $acesJ2 = $this->statistiquesJ2()?->aces ?? 5;
        $matchsJ1 = $this->statistiquesJ1()?->matchsJoues() ?? 1;
        $matchsJ2 = $this->statistiquesJ2()?->matchsJoues() ?? 1;

        return (int)(($acesJ1 / $matchsJ1) + ($acesJ2 / $matchsJ2));
    }

    private function inventorierDonnees(MatchTennis $match)
    {
        return [
            'joueurs' => true,
            'classements' => (bool)($match->joueur1->classement_atp_wta && $match->joueur2->classement_atp_wta),
            'h2h' => (bool)$this->confrontation(),
            'statistiques_surface' => (bool)($this->statistiquesJ1() && $this->statistiquesJ2()),
            'meteo' => (bool)$match->temperature,
            'tournoi' => (bool)$match->tournoi,
            'surface' => (bool)$match->surface
        ];
    }

    private function calculerAccuracyParType(MatchTennis $match)
    {
        return [
            'gagnant' => $this->joueur_favori == $match->gagnant_id ? 100 : 0,
            'sets' => $this->sets_predit == count($match->score_detaille ?? []) ? 100 : 0,
            'duree' => $this->duree_predite && $match->duree_match ?
                100 - (abs($this->duree_predite - $match->duree_match) / $match->duree_match * 100) : 0
        ];
    }

    // ===================================================================
    // VALIDATION RULES
    // ===================================================================

    public static function validationRules()
    {
        return [
            'match_tennis_id' => 'required|exists:matchs_tennis,id',
            'algorithme_ia_id' => 'required|exists:algorithme_ias,id',
            'type_prediction_id' => 'required|exists:type_predictions,id',
            'probabilite_joueur1' => 'required|numeric|between:0,100',
            'probabilite_joueur2' => 'required|numeric|between:0,100',
            'confiance_globale' => 'required|numeric|between:0,100',
            'niveau_difficulte' => 'required|integer|between:1,10'
        ];
    }

    // ===================================================================
    // STATIC METHODS
    // ===================================================================

    /**
     * Obtenir l'accuracy moyenne par algorithme
     */
    public static function getAccuracyParAlgorithme()
    {
        return self::avecResultat()
            ->selectRaw('algorithme_ia_id, AVG(accuracy_globale) as accuracy_moyenne, COUNT(*) as nb_predictions')
            ->groupBy('algorithme_ia_id')
            ->with('algorithme')
            ->get();
    }

    /**
     * Détecter dérive du modèle
     */
    public static function detecterDeriveModele($joursRecents = 30)
    {
        $accuracyRecente = self::recentes($joursRecents)->avecResultat()->avg('accuracy_globale');
        $accuracyHistorique = self::avecResultat()->avg('accuracy_globale');

        $derive = $accuracyHistorique - $accuracyRecente;

        return [
            'derive_detectee' => $derive > 5, // Baisse de 5% = dérive
            'accuracy_historique' => round($accuracyHistorique, 2),
            'accuracy_recente' => round($accuracyRecente, 2),
            'derive_points' => round($derive, 2)
        ];
    }
}
