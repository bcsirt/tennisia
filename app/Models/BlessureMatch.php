<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

/**
 * MODÈLE BLESSURE EN MATCH
 *
 * Capture les blessures survenant pendant un match pour ajuster
 * les prédictions en temps réel et analyser l'impact sur la performance
 */
class BlessureMatch extends Model
{
    protected $fillable = [
        'match_tennis_id',
        'joueur_id',
        'type_blessure',
        'zone_corporelle',
        'moment_match',           // Moment précis (set, jeu)
        'set_numero',
        'jeu_numero',
        'score_moment',           // Score au moment de la blessure
        'gravite',               // 1-10
        'description',
        'impact_immediat',       // Impact observé immédiatement
        'evolution_match',       // Comment la blessure évolue
        'soins_recus',          // Soins médicaux reçus
        'temps_soins_minutes',   // Durée des soins
        'retour_possible',       // Boolean
        'abandon_cause',         // Boolean si cause d'abandon
        'contexte_apparition',   // Circonstances
        'temperature_court',     // Température au moment
        'duree_match_avant',     // Durée du match avant blessure
        'fatigue_estimee',       // Niveau de fatigue estimé (1-10)
        'surface_match',         // Surface du match
        'validation_medicale'    // Validation par médecin
    ];

    protected $casts = [
        'gravite' => 'integer',
        'set_numero' => 'integer',
        'jeu_numero' => 'integer',
        'temps_soins_minutes' => 'integer',
        'retour_possible' => 'boolean',
        'abandon_cause' => 'boolean',
        'temperature_court' => 'decimal:1',
        'duree_match_avant' => 'integer', // en minutes
        'fatigue_estimee' => 'integer',
        'validation_medicale' => 'boolean'
    ];

    // CONSTANTES TYPES DE BLESSURES
    public const TYPE_MUSCULAIRE = 'musculaire';
    public const TYPE_ARTICULATION = 'articulation';
    public const TYPE_TENDON = 'tendon';
    public const TYPE_CRAMPE = 'crampe';
    public const TYPE_ENTORSE = 'entorse';
    public const TYPE_FATIGUE = 'fatigue';
    public const TYPE_DOULEUR_DOS = 'douleur_dos';
    public const TYPE_TROUBLE_RESPIRATOIRE = 'trouble_respiratoire';
    public const TYPE_MALAISE = 'malaise';
    public const TYPE_PLAIE = 'plaie';

    // ZONES CORPORELLES
    public const ZONE_EPAULE = 'epaule';
    public const ZONE_COUDE = 'coude';
    public const ZONE_POIGNET = 'poignet';
    public const ZONE_DOS = 'dos';
    public const ZONE_HANCHE = 'hanche';
    public const ZONE_GENOU = 'genou';
    public const ZONE_CHEVILLE = 'cheville';
    public const ZONE_PIED = 'pied';
    public const ZONE_MOLLET = 'mollet';
    public const ZONE_CUISSE = 'cuisse';
    public const ZONE_ABDOMEN = 'abdomen';

    // NIVEAUX DE GRAVITÉ
    public const GRAVITE_LEGERE = 1;        // Gêne légère
    public const GRAVITE_MODEREE = 3;       // Impact modéré
    public const GRAVITE_SIGNIFICATIVE = 5; // Impact significatif
    public const GRAVITE_SEVERE = 7;        // Impact sévère
    public const GRAVITE_CRITIQUE = 10;     // Abandon probable

    public static function getTypesBlessures(): array
    {
        return [
            self::TYPE_MUSCULAIRE => 'Blessure musculaire',
            self::TYPE_ARTICULATION => 'Problème articulaire',
            self::TYPE_TENDON => 'Blessure tendon/ligament',
            self::TYPE_CRAMPE => 'Crampe musculaire',
            self::TYPE_ENTORSE => 'Entorse',
            self::TYPE_FATIGUE => 'Fatigue extrême',
            self::TYPE_DOULEUR_DOS => 'Douleur dorsale',
            self::TYPE_TROUBLE_RESPIRATOIRE => 'Trouble respiratoire',
            self::TYPE_MALAISE => 'Malaise général',
            self::TYPE_PLAIE => 'Plaie/coupure'
        ];
    }

    public static function getZonesCorporelles(): array
    {
        return [
            self::ZONE_EPAULE => 'Épaule',
            self::ZONE_COUDE => 'Coude',
            self::ZONE_POIGNET => 'Poignet',
            self::ZONE_DOS => 'Dos',
            self::ZONE_HANCHE => 'Hanche',
            self::ZONE_GENOU => 'Genou',
            self::ZONE_CHEVILLE => 'Cheville',
            self::ZONE_PIED => 'Pied',
            self::ZONE_MOLLET => 'Mollet',
            self::ZONE_CUISSE => 'Cuisse',
            self::ZONE_ABDOMEN => 'Abdomen'
        ];
    }

    /**
     * RELATIONS
     */
    public function match(): BelongsTo
    {
        return $this->belongsTo(MatchTennis::class, 'match_tennis_id');
    }

    public function joueur(): BelongsTo
    {
        return $this->belongsTo(Joueur::class, 'joueur_id');
    }

    public function evolutionsBlessure(): HasMany
    {
        return $this->hasMany(EvolutionBlessureMatch::class);
    }

    /**
     * SCOPES
     */
    public function scopeGraves(Builder $query): Builder
    {
        return $query->where('gravite', '>=', self::GRAVITE_SEVERE);
    }

    public function scopeCausantAbandon(Builder $query): Builder
    {
        return $query->where('abandon_cause', true);
    }

    public function scopeParType(Builder $query, string $type): Builder
    {
        return $query->where('type_blessure', $type);
    }

    public function scopeParZone(Builder $query, string $zone): Builder
    {
        return $query->where('zone_corporelle', $zone);
    }

    public function scopeRecentes(Builder $query, int $jours = 30): Builder
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($jours));
    }

    public function scopePourJoueur(Builder $query, int $joueurId): Builder
    {
        return $query->where('joueur_id', $joueurId);
    }

    public function scopeAvecSoins(Builder $query): Builder
    {
        return $query->whereNotNull('soins_recus')
            ->where('temps_soins_minutes', '>', 0);
    }

    /**
     * ACCESSORS
     */
    public function getTypeBlessureLabelAttribute(): string
    {
        return self::getTypesBlessures()[$this->type_blessure] ?? $this->type_blessure;
    }

    public function getZoneCorporelleLabelAttribute(): string
    {
        return self::getZonesCorporelles()[$this->zone_corporelle] ?? $this->zone_corporelle;
    }

    public function getGraviteLabelAttribute(): string
    {
        return match($this->gravite) {
            1, 2 => 'Légère',
            3, 4 => 'Modérée',
            5, 6 => 'Significative',
            7, 8 => 'Sévère',
            9, 10 => 'Critique',
            default => 'Non évaluée'
        };
    }

    public function getMomentCompletAttribute(): string
    {
        $moment = "Set {$this->set_numero}";
        if ($this->jeu_numero) {
            $moment .= ", Jeu {$this->jeu_numero}";
        }
        if ($this->score_moment) {
            $moment .= " ({$this->score_moment})";
        }
        return $moment;
    }

    public function getImpactEstimeAttribute(): array
    {
        return $this->calculerImpactPerformance();
    }

    /**
     * MÉTHODES MÉTIER
     */

    /**
     * Calcule l'impact immédiat sur la performance
     */
    public function calculerImpactPerformance(): array
    {
        $impact = [
            'service' => 0,          // Impact sur le service (%)
            'retour' => 0,           // Impact sur le retour (%)
            'deplacement' => 0,      // Impact sur les déplacements (%)
            'puissance' => 0,        // Impact sur la puissance (%)
            'endurance' => 0,        // Impact sur l'endurance (%)
            'mental' => 0,           // Impact psychologique (%)
            'probabilite_abandon' => 0 // Probabilité d'abandon (%)
        ];

        // Impact selon la zone blessée
        switch ($this->zone_corporelle) {
            case self::ZONE_EPAULE:
                $impact['service'] = $this->gravite * 3;
                $impact['puissance'] = $this->gravite * 2;
                break;

            case self::ZONE_POIGNET:
                $impact['service'] = $this->gravite * 2;
                $impact['retour'] = $this->gravite * 2.5;
                break;

            case self::ZONE_DOS:
                $impact['service'] = $this->gravite * 2.5;
                $impact['deplacement'] = $this->gravite * 1.5;
                break;

            case self::ZONE_GENOU:
            case self::ZONE_CHEVILLE:
                $impact['deplacement'] = $this->gravite * 3;
                $impact['endurance'] = $this->gravite * 2;
                break;

            case self::ZONE_MOLLET:
            case self::ZONE_CUISSE:
                $impact['deplacement'] = $this->gravite * 2.5;
                $impact['endurance'] = $this->gravite * 3;
                break;
        }

        // Impact selon le type de blessure
        switch ($this->type_blessure) {
            case self::TYPE_CRAMPE:
                $impact['endurance'] = $this->gravite * 4;
                $impact['deplacement'] = $this->gravite * 2;
                break;

            case self::TYPE_FATIGUE:
                $impact['endurance'] = $this->gravite * 3;
                $impact['mental'] = $this->gravite * 1.5;
                break;

            case self::TYPE_TROUBLE_RESPIRATOIRE:
                $impact['endurance'] = $this->gravite * 5;
                break;
        }

        // Impact psychologique
        $impact['mental'] = max($impact['mental'], $this->gravite * 1.2);

        // Probabilité d'abandon
        if ($this->gravite >= self::GRAVITE_SEVERE) {
            $impact['probabilite_abandon'] = min(90, $this->gravite * 8);
        }

        // Facteur temporel - plus grave si tôt dans le match
        if ($this->set_numero <= 2) {
            $impact = array_map(fn($val) => $val * 1.2, $impact);
        }

        // Facteur surface
        if ($this->surface_match === 'clay' &&
            in_array($this->zone_corporelle, [self::ZONE_GENOU, self::ZONE_CHEVILLE])) {
            $impact['deplacement'] *= 1.3; // Terre battue plus exigeante
        }

        return array_map(fn($val) => min(100, round($val, 1)), $impact);
    }

    /**
     * Calcule l'ajustement des probabilités de victoire
     */
    public function calculerAjustementProbabilite(): float
    {
        $impact = $this->calculerImpactPerformance();

        // Formule pondérée selon l'importance de chaque aspect
        $impactGlobal = (
                $impact['service'] * 0.25 +
                $impact['retour'] * 0.25 +
                $impact['deplacement'] * 0.20 +
                $impact['endurance'] * 0.15 +
                $impact['mental'] * 0.15
            ) / 100;

        // Réduction de probabilité proportionnelle
        return min(0.4, $impactGlobal * 0.6); // Max 40% de réduction
    }

    /**
     * Prédit l'évolution de la blessure dans le match
     */
    public function predireEvolution(): array
    {
        $prediction = [
            'amelioration_probable' => false,
            'stabilisation_probable' => false,
            'aggravation_probable' => false,
            'abandon_probable' => false,
            'confiance' => 5
        ];

        // Analyse selon le type de blessure
        switch ($this->type_blessure) {
            case self::TYPE_CRAMPE:
                if ($this->temps_soins_minutes >= 3) {
                    $prediction['amelioration_probable'] = true;
                    $prediction['confiance'] = 8;
                } else {
                    $prediction['aggravation_probable'] = true;
                    $prediction['confiance'] = 7;
                }
                break;

            case self::TYPE_FATIGUE:
                $prediction['aggravation_probable'] = true;
                $prediction['confiance'] = 9;
                break;

            case self::TYPE_ENTORSE:
            case self::TYPE_MUSCULAIRE:
                if ($this->gravite >= self::GRAVITE_SIGNIFICATIVE) {
                    $prediction['aggravation_probable'] = true;
                    $prediction['confiance'] = 8;
                } else {
                    $prediction['stabilisation_probable'] = true;
                    $prediction['confiance'] = 6;
                }
                break;
        }

        // Facteur durée du match
        if ($this->duree_match_avant > 180) { // Plus de 3h
            $prediction['aggravation_probable'] = true;
            $prediction['confiance'] = min(10, $prediction['confiance'] + 2);
        }

        // Facteur température
        if ($this->temperature_court > 30) {
            $prediction['aggravation_probable'] = true;
        }

        // Probabilité d'abandon
        if ($this->gravite >= self::GRAVITE_CRITIQUE ||
            ($this->gravite >= self::GRAVITE_SEVERE && $prediction['aggravation_probable'])) {
            $prediction['abandon_probable'] = true;
        }

        return $prediction;
    }

    /**
     * Analyse les patterns de blessures pour un joueur
     */
    public static function analyserPatternsBlessures(int $joueurId): array
    {
        $blessures = self::pourJoueur($joueurId)
            ->with('match')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        if ($blessures->isEmpty()) {
            return ['pattern' => 'aucune_donnee'];
        }

        $patterns = [
            'zones_frequentes' => $blessures->groupBy('zone_corporelle')
                ->map->count()
                ->sortDesc()
                ->take(3)
                ->toArray(),
            'types_frequents' => $blessures->groupBy('type_blessure')
                ->map->count()
                ->sortDesc()
                ->take(3)
                ->toArray(),
            'surfaces_problematiques' => $blessures->groupBy('surface_match')
                ->map->count()
                ->sortDesc()
                ->toArray(),
            'moments_critiques' => $blessures->groupBy('set_numero')
                ->map->count()
                ->toArray(),
            'facteur_temperature' => $blessures->where('temperature_court', '>', 30)->count(),
            'facteur_fatigue' => $blessures->where('duree_match_avant', '>', 180)->count(),
            'tendance_gravite' => $blessures->take(10)->avg('gravite') -
                $blessures->skip(10)->avg('gravite')
        ];

        // Détection de patterns spécifiques
        $patternsDetectes = [];

        if ($patterns['zones_frequentes'] &&
            array_values($patterns['zones_frequentes'])[0] >= 3) {
            $patternsDetectes[] = 'zone_vulnerable_' .
                array_keys($patterns['zones_frequentes'])[0];
        }

        if ($patterns['facteur_temperature'] > $blessures->count() * 0.6) {
            $patternsDetectes[] = 'sensible_chaleur';
        }

        if ($patterns['facteur_fatigue'] > $blessures->count() * 0.5) {
            $patternsDetectes[] = 'fatigue_matchs_longs';
        }

        $patterns['patterns_detectes'] = $patternsDetectes;
        $patterns['risque_global'] = $this->calculerRisqueGlobal($blessures);

        return $patterns;
    }

    private static function calculerRisqueGlobal($blessures): string
    {
        $frequence = $blessures->count();
        $graviteMoyenne = $blessures->avg('gravite');
        $abandons = $blessures->where('abandon_cause', true)->count();

        $score = $frequence * 2 + $graviteMoyenne * 3 + $abandons * 5;

        return match(true) {
            $score <= 10 => 'faible',
            $score <= 25 => 'modere',
            $score <= 40 => 'eleve',
            default => 'tres_eleve'
        };
    }

    /**
     * Recommandations basées sur la blessure
     */
    public function genererRecommandations(): array
    {
        $recommendations = [
            'soins_immediats' => [],
            'surveillance' => [],
            'prediction_impact' => [],
            'conseil_medical' => []
        ];

        // Soins immédiats selon le type
        switch ($this->type_blessure) {
            case self::TYPE_CRAMPE:
                $recommendations['soins_immediats'][] = 'Hydratation immédiate';
                $recommendations['soins_immediats'][] = 'Étirements légers';
                $recommendations['soins_immediats'][] = 'Massage de la zone';
                break;

            case self::TYPE_ENTORSE:
                $recommendations['soins_immediats'][] = 'Application de glace';
                $recommendations['soins_immediats'][] = 'Immobilisation relative';
                $recommendations['conseil_medical'][] = 'Évaluation médicale recommandée';
                break;
        }

        // Surveillance
        if ($this->gravite >= self::GRAVITE_SIGNIFICATIVE) {
            $recommendations['surveillance'][] = 'Surveiller l\'évolution à chaque changement de côté';
            $recommendations['surveillance'][] = 'Observer les compensations gestuelles';
        }

        // Impact sur les prédictions
        $impact = $this->calculerImpactPerformance();
        if (max($impact) > 20) {
            $recommendations['prediction_impact'][] =
                'Ajustement significatif des probabilités requis';
        }

        return $recommendations;
    }

    /**
     * Export pour analyse ML
     */
    public function toMLFeatures(): array
    {
        return [
            'type_blessure_encoded' => array_search($this->type_blessure,
                array_keys(self::getTypesBlessures())),
            'zone_encoded' => array_search($this->zone_corporelle,
                array_keys(self::getZonesCorporelles())),
            'gravite_normalized' => $this->gravite / 10,
            'moment_match_normalized' => ($this->set_numero +
                    ($this->jeu_numero / 15)) / 5, // Normalisé sur 5 sets
            'duree_avant_normalized' => min(1, $this->duree_match_avant / 300),
            'temperature_normalized' => ($this->temperature_court - 15) / 25,
            'fatigue_normalized' => $this->fatigue_estimee / 10,
            'soins_binaire' => $this->temps_soins_minutes > 0 ? 1 : 0,
            'abandon_resultant' => $this->abandon_cause ? 1 : 0
        ];
    }
}

/**
 * MODÈLE COMPLÉMENTAIRE : ÉVOLUTION BLESSURE DANS LE MATCH
 */
class EvolutionBlessureMatch extends Model
{
    protected $fillable = [
        'blessure_match_id',
        'moment_observation',
        'set_numero',
        'jeu_numero',
        'evolution_type',      // amelioration, stabilisation, aggravation
        'gravite_actuelle',
        'impact_observe',
        'notes_observateur'
    ];

    protected $casts = [
        'set_numero' => 'integer',
        'jeu_numero' => 'integer',
        'gravite_actuelle' => 'integer'
    ];

    public function blessureMatch(): BelongsTo
    {
        return $this->belongsTo(BlessureMatch::class);
    }

    /**
     * Calcule la tendance d'évolution
     */
    public function getTendanceEvolutionAttribute(): string
    {
        $evolutions = self::where('blessure_match_id', $this->blessure_match_id)
            ->orderBy('set_numero')
            ->orderBy('jeu_numero')
            ->get();

        if ($evolutions->count() < 2) {
            return 'stable';
        }

        $premiere = $evolutions->first();
        $derniere = $evolutions->last();

        $diffGravite = $derniere->gravite_actuelle - $premiere->gravite_actuelle;

        return match(true) {
            $diffGravite >= 2 => 'aggravation_forte',
            $diffGravite >= 1 => 'aggravation_legere',
            $diffGravite <= -2 => 'amelioration_forte',
            $diffGravite <= -1 => 'amelioration_legere',
            default => 'stable'
        };
    }
}
