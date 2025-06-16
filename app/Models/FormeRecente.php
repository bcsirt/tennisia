<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormeRecente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'forme_recentes';

    protected $fillable = [
        // Référence joueur
        'joueur_id',
        'surface_id',               // CRUCIAL: Forme par surface
        'saison_id',                // Forme par saison

        // Résultats par période (fondamental)
        'victoires_5',
        'defaites_5',
        'victoires_10',
        'defaites_10',
        'victoires_20',
        'defaites_20',
        'victoires_52',             // Année glissante
        'defaites_52',

        // Analyse qualitative des victoires (CRUCIAL pour prédictions)
        'victoires_top_10',         // Contre top 10
        'victoires_top_50',         // Contre top 50
        'victoires_top_100',        // Contre top 100
        'defaites_vs_inferieurs',   // Défaites contre moins bien classés
        'upsets_causes',            // Upsets causés (victoires surprises)
        'upsets_subis',             // Upsets subis (défaites surprises)

        // Momentum et séries (psychologie)
        'serie_victoires_actuelle',
        'serie_defaites_actuelle',
        'plus_longue_serie_v',      // Plus longue série victoires récente
        'plus_longue_serie_d',      // Plus longue série défaites récente
        'changements_momentum',     // Nombre de changements V/D

        // Performance par catégorie de tournoi
        'forme_grand_chelem',       // Format: "3-2" (V-D)
        'forme_masters',
        'forme_atp500',
        'forme_atp250',
        'forme_challengers',

        // Contexte des matchs récents
        'matchs_domicile',          // Dans son pays
        'victoires_domicile',
        'matchs_deplacements_longs', // Voyages +8h de décalage
        'victoires_deplacements',
        'matchs_altitude',          // >1000m altitude
        'victoires_altitude',

        // Performance par sets (endurance/mental)
        'matchs_3_sets',
        'victoires_3_sets',
        'matchs_4_sets',
        'victoires_4_sets',
        'matchs_5_sets',
        'victoires_5_sets',
        'retournements_reussis',    // Victoires après être mené 2 sets
        'retournements_rates',      // Défaites après avoir mené 2 sets

        // Données physiques/mentales
        'abandons_recents',
        'blessures_mineures',       // Soins médicaux pendant matchs
        'temps_jeu_total',          // Minutes jouées total
        'duree_moyenne_match',
        'matchs_marathon',          // >3h
        'victoires_marathon',

        // Évolution et tendances
        'elo_evolution',            // Évolution ELO sur période
        'classement_evolution',     // Évolution classement
        'tendance_generale',        // 'hausse', 'baisse', 'stable'
        'confiance_index',          // Indice confiance 0-100
        'forme_index',              // Indice forme global 0-100

        // Spécificités techniques
        'aces_par_match',           // Moyenne aces récents
        'double_fautes_par_match',  // Moyenne DF récents
        'premier_service_pct',      // % premier service récent
        'break_points_conversion',  // % conversion BP récents
        'break_points_saved',       // % BP sauvés récents

        // Contexte temporel et conditions
        'matchs_indoor',
        'victoires_indoor',
        'matchs_outdoor',
        'victoires_outdoor',
        'matchs_chaleur',           // >30°C
        'victoires_chaleur',
        'matchs_froid',             // <10°C
        'victoires_froid',

        // Métadonnées et fiabilité
        'date_mise_a_jour',
        'derniere_analyse',
        'fiabilite_donnees',        // 0-100%
        'nombre_matchs_echantillon',
        'periode_analysee_debut',
        'periode_analysee_fin',
        'source_donnees_id',
    ];

    protected $casts = [
        // Résultats par période
        'victoires_5' => 'integer',
        'defaites_5' => 'integer',
        'victoires_10' => 'integer',
        'defaites_10' => 'integer',
        'victoires_20' => 'integer',
        'defaites_20' => 'integer',
        'victoires_52' => 'integer',
        'defaites_52' => 'integer',

        // Qualité victoires
        'victoires_top_10' => 'integer',
        'victoires_top_50' => 'integer',
        'victoires_top_100' => 'integer',
        'defaites_vs_inferieurs' => 'integer',
        'upsets_causes' => 'integer',
        'upsets_subis' => 'integer',

        // Séries et momentum
        'serie_victoires_actuelle' => 'integer',
        'serie_defaites_actuelle' => 'integer',
        'plus_longue_serie_v' => 'integer',
        'plus_longue_serie_d' => 'integer',
        'changements_momentum' => 'integer',

        // Performance sets
        'matchs_3_sets' => 'integer',
        'victoires_3_sets' => 'integer',
        'retournements_reussis' => 'integer',
        'retournements_rates' => 'integer',

        // Physique
        'abandons_recents' => 'integer',
        'blessures_mineures' => 'integer',
        'temps_jeu_total' => 'integer',
        'duree_moyenne_match' => 'integer',
        'matchs_marathon' => 'integer',
        'victoires_marathon' => 'integer',

        // Évolutions
        'elo_evolution' => 'decimal:2',
        'classement_evolution' => 'integer',
        'confiance_index' => 'decimal:1',
        'forme_index' => 'decimal:1',

        // Techniques (moyennes)
        'aces_par_match' => 'decimal:1',
        'double_fautes_par_match' => 'decimal:1',
        'premier_service_pct' => 'decimal:1',
        'break_points_conversion' => 'decimal:1',
        'break_points_saved' => 'decimal:1',

        // Conditions
        'matchs_indoor' => 'integer',
        'victoires_indoor' => 'integer',
        'matchs_outdoor' => 'integer',
        'victoires_outdoor' => 'integer',
        'matchs_chaleur' => 'integer',
        'victoires_chaleur' => 'integer',

        // Métadonnées
        'fiabilite_donnees' => 'decimal:1',
        'nombre_matchs_echantillon' => 'integer',

        // Dates
        'date_mise_a_jour' => 'date',
        'derniere_analyse' => 'datetime',
        'periode_analysee_debut' => 'date',
        'periode_analysee_fin' => 'date',
    ];

    protected $appends = [
        'ratio_5_matchs',
        'ratio_10_matchs',
        'ratio_20_matchs',
        'momentum_textuel',
        'qualite_victoires',
        'endurance_mentale',
        'adaptabilite_conditions',
        'niveau_forme_textuel',
        'facteur_confiance',
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function joueur()
    {
        return $this->belongsTo(Joueur::class);
    }

    public function surface()
    {
        return $this->belongsTo(Surface::class);
    }

    public function saison()
    {
        return $this->belongsTo(Saison::class);
    }

    public function sourceDonnees()
    {
        return $this->belongsTo(SourceDonnees::class, 'source_donnees_id');
    }

    // Relations calculées vers matchs récents
    public function matchsRecents($limite = 5)
    {
        return MatchTennis::where(function ($query) {
            $query->where('joueur1_id', $this->joueur_id)
                ->orWhere('joueur2_id', $this->joueur_id);
        })
            ->whereNotNull('gagnant_id')
            ->orderBy('date_match', 'desc')
            ->limit($limite);
    }

    // ===================================================================
    // ACCESSORS (Analyses automatiques)
    // ===================================================================

    public function getRatio5MatchsAttribute()
    {
        $total = $this->victoires_5 + $this->defaites_5;

        return $total > 0 ? round($this->victoires_5 / $total, 3) : 0;
    }

    public function getRatio10MatchsAttribute()
    {
        $total = $this->victoires_10 + $this->defaites_10;

        return $total > 0 ? round($this->victoires_10 / $total, 3) : 0;
    }

    public function getRatio20MatchsAttribute()
    {
        $total = $this->victoires_20 + $this->defaites_20;

        return $total > 0 ? round($this->victoires_20 / $total, 3) : 0;
    }

    public function getMomentumTextuelAttribute()
    {
        if ($this->serie_victoires_actuelle >= 5) {
            return 'momentum_excellent';
        }
        if ($this->serie_victoires_actuelle >= 3) {
            return 'momentum_bon';
        }
        if ($this->serie_victoires_actuelle >= 1) {
            return 'momentum_positif';
        }
        if ($this->serie_defaites_actuelle >= 5) {
            return 'momentum_tres_negatif';
        }
        if ($this->serie_defaites_actuelle >= 3) {
            return 'momentum_negatif';
        }
        if ($this->serie_defaites_actuelle >= 1) {
            return 'momentum_legrement_negatif';
        }

        return 'momentum_neutre';
    }

    public function getQualiteVictoiresAttribute()
    {
        $totalVictoires = $this->victoires_20;
        if ($totalVictoires === 0) {
            return 0;
        }

        $score = 0;
        $score += ($this->victoires_top_10 * 10);      // Top 10 = 10 points
        $score += ($this->victoires_top_50 * 5);       // Top 50 = 5 points
        $score += ($this->victoires_top_100 * 2);      // Top 100 = 2 points
        $score += ($this->upsets_causes * 8);          // Upsets = 8 points

        // Malus pour défaites contre inférieurs
        $score -= ($this->defaites_vs_inferieurs * 3);
        $score -= ($this->upsets_subis * 5);

        return max(0, round($score / $totalVictoires, 1));
    }

    public function getEnduranceMentaleAttribute()
    {
        $score = 50; // Base neutre

        // Bonus pour victoires en 3+ sets
        if ($this->matchs_3_sets > 0) {
            $score += ($this->victoires_3_sets / $this->matchs_3_sets) * 20;
        }

        if ($this->matchs_5_sets > 0) {
            $score += ($this->victoires_5_sets / $this->matchs_5_sets) * 15;
        }

        // Bonus pour retournements
        $score += $this->retournements_reussis * 5;

        // Malus pour retournements ratés
        $score -= $this->retournements_rates * 3;

        // Malus pour abandons
        $score -= $this->abandons_recents * 10;

        // Bonus pour victoires marathon
        if ($this->matchs_marathon > 0) {
            $score += ($this->victoires_marathon / $this->matchs_marathon) * 10;
        }

        return max(0, min(100, round($score, 1)));
    }

    public function getAdaptabiliteConditionsAttribute()
    {
        $score = 0;
        $facteurs = 0;

        // Indoor vs Outdoor
        if ($this->matchs_indoor > 0) {
            $score += ($this->victoires_indoor / $this->matchs_indoor) * 25;
            $facteurs++;
        }

        if ($this->matchs_outdoor > 0) {
            $score += ($this->victoires_outdoor / $this->matchs_outdoor) * 25;
            $facteurs++;
        }

        // Conditions extrêmes
        if ($this->matchs_chaleur > 0) {
            $score += ($this->victoires_chaleur / $this->matchs_chaleur) * 25;
            $facteurs++;
        }

        if ($this->matchs_altitude > 0) {
            $score += ($this->victoires_altitude / $this->matchs_altitude) * 25;
            $facteurs++;
        }

        return $facteurs > 0 ? round($score / $facteurs, 1) : 50;
    }

    public function getNiveauFormeTextuelAttribute()
    {
        $index = $this->forme_index;

        if ($index >= 90) {
            return 'forme_exceptionnelle';
        }
        if ($index >= 80) {
            return 'excellent_forme';
        }
        if ($index >= 70) {
            return 'bonne_forme';
        }
        if ($index >= 60) {
            return 'forme_correcte';
        }
        if ($index >= 50) {
            return 'forme_moyenne';
        }
        if ($index >= 40) {
            return 'forme_difficile';
        }

        return 'forme_preoccupante';
    }

    public function getFacteurConfianceAttribute()
    {
        $confiance = 0;

        // Série de victoires augmente confiance
        $confiance += min($this->serie_victoires_actuelle * 5, 25);

        // Qualité des victoires
        $confiance += min($this->qualite_victoires, 25);

        // Évolution positive
        if ($this->elo_evolution > 0) {
            $confiance += min($this->elo_evolution / 10, 15);
        }

        // Constance (peu de changements momentum)
        if ($this->changements_momentum <= 3) {
            $confiance += 10;
        }

        // Malus pour défaites récentes
        $confiance -= $this->serie_defaites_actuelle * 8;

        // Malus pour abandons
        $confiance -= $this->abandons_recents * 15;

        return max(0, min(100, round($confiance, 1)));
    }

    public function getDureeFormateeAttribute()
    {
        if (! $this->duree_moyenne_match) {
            return null;
        }

        $heures = floor($this->duree_moyenne_match / 60);
        $minutes = $this->duree_moyenne_match % 60;

        return sprintf('%dh%02d', $heures, $minutes);
    }

    // ===================================================================
    // SCOPES
    // ===================================================================

    public function scopeParSurface($query, $surfaceCode)
    {
        return $query->whereHas('surface', function ($q) use ($surfaceCode) {
            $q->where('code', $surfaceCode);
        });
    }

    public function scopeEnForme($query, $seuilRatio = 0.6)
    {
        return $query->whereRaw('victoires_5 / (victoires_5 + defaites_5) >= ?', [$seuilRatio]);
    }

    public function scopeEnDifficulte($query, $seuilRatio = 0.4)
    {
        return $query->whereRaw('victoires_5 / (victoires_5 + defaites_5) <= ?', [$seuilRatio]);
    }

    public function scopeAvecSerie($query, $minSerie = 3)
    {
        return $query->where('serie_victoires_actuelle', '>=', $minSerie);
    }

    public function scopeEnProgression($query)
    {
        return $query->where('elo_evolution', '>', 0)
            ->where('forme_index', '>=', 60);
    }

    public function scopeEnRegression($query)
    {
        return $query->where('elo_evolution', '<', 0)
            ->where('forme_index', '<=', 40);
    }

    public function scopeRecentes($query, $jours = 30)
    {
        return $query->where('date_mise_a_jour', '>=', now()->subDays($jours));
    }

    public function scopeFiables($query, $seuilFiabilite = 80)
    {
        return $query->where('fiabilite_donnees', '>=', $seuilFiabilite);
    }

    public function scopeAvecMinimumMatchs($query, $minimum = 5)
    {
        return $query->where('nombre_matchs_echantillon', '>=', $minimum);
    }

    // ===================================================================
    // METHODS TENNIS AI AVANCÉES
    // ===================================================================

    /**
     * Calculer l'indice de forme global
     */
    public function calculerIndiceForme()
    {
        $score = 0;

        // 1. Résultats récents (40% du score)
        $score += $this->ratio_5_matchs * 40;

        // 2. Momentum série (20% du score)
        if ($this->serie_victoires_actuelle > 0) {
            $score += min($this->serie_victoires_actuelle * 4, 20);
        } else {
            $score -= $this->serie_defaites_actuelle * 4;
        }

        // 3. Qualité des victoires (20% du score)
        $score += ($this->qualite_victoires / 10) * 20;

        // 4. Évolution ELO (15% du score)
        if ($this->elo_evolution) {
            $score += min(max($this->elo_evolution / 5, -15), 15);
        }

        // 5. Condition physique (5% du score)
        $score -= $this->abandons_recents * 5;
        $score += ($this->endurance_mentale / 100) * 5;

        $this->forme_index = max(0, min(100, round($score, 1)));

        return $this->forme_index;
    }

    /**
     * Prédire la forme pour les prochains matchs
     */
    public function predireFormeFuture($nombreMatchs = 5)
    {
        $formeActuelle = $this->forme_index;

        // Facteurs d'évolution
        $tendance = 0;

        // Momentum positif = amélioration probable
        if ($this->serie_victoires_actuelle >= 3) {
            $tendance += 2;
        } elseif ($this->serie_defaites_actuelle >= 3) {
            $tendance -= 2;
        }

        // Évolution ELO récente
        if ($this->elo_evolution > 20) {
            $tendance += 3;
        } elseif ($this->elo_evolution < -20) {
            $tendance -= 3;
        }

        // Régression vers la moyenne (effet naturel)
        if ($formeActuelle > 80) {
            $tendance -= 1; // Forme exceptionnelle difficile à maintenir
        } elseif ($formeActuelle < 30) {
            $tendance += 2; // Remontée probable
        }

        // Projection
        $formeFuture = $formeActuelle;
        for ($i = 1; $i <= $nombreMatchs; $i++) {
            $formeFuture += $tendance * (1 / $i); // Effet diminue dans le temps
            $formeFuture = max(10, min(90, $formeFuture)); // Bornes réalistes
        }

        return [
            'forme_actuelle' => $formeActuelle,
            'forme_predite' => round($formeFuture, 1),
            'evolution_attendue' => round($formeFuture - $formeActuelle, 1),
            'confiance_prediction' => $this->facteur_confiance,
            'facteurs_evolution' => [
                'momentum' => $this->momentum_textuel,
                'evolution_elo' => $this->elo_evolution,
                'stabilite' => $this->changements_momentum <= 3,
            ],
        ];
    }

    /**
     * Comparer la forme avec un autre joueur
     */
    public function comparerFormeAvec(FormeRecente $autre)
    {
        return [
            'forme' => [
                'joueur' => $this->forme_index,
                'autre' => $autre->forme_index,
                'avantage' => $this->forme_index > $autre->forme_index ? 'joueur' : 'autre',
                'difference' => round($this->forme_index - $autre->forme_index, 1),
            ],
            'momentum' => [
                'joueur' => $this->momentum_textuel,
                'autre' => $autre->momentum_textuel,
                'serie_joueur' => $this->serie_victoires_actuelle - $this->serie_defaites_actuelle,
                'serie_autre' => $autre->serie_victoires_actuelle - $autre->serie_defaites_actuelle,
            ],
            'qualite_victoires' => [
                'joueur' => $this->qualite_victoires,
                'autre' => $autre->qualite_victoires,
                'avantage' => $this->qualite_victoires > $autre->qualite_victoires ? 'joueur' : 'autre',
            ],
            'endurance' => [
                'joueur' => $this->endurance_mentale,
                'autre' => $autre->endurance_mentale,
                'avantage' => $this->endurance_mentale > $autre->endurance_mentale ? 'joueur' : 'autre',
            ],
            'adaptabilite' => [
                'joueur' => $this->adaptabilite_conditions,
                'autre' => $autre->adaptabilite_conditions,
                'avantage' => $this->adaptabilite_conditions > $autre->adaptabilite_conditions ? 'joueur' : 'autre',
            ],
        ];
    }

    /**
     * Identifier les patterns de performance
     */
    public function identifierPatterns()
    {
        $patterns = [];

        // Pattern momentum
        if ($this->serie_victoires_actuelle >= 4) {
            $patterns[] = [
                'type' => 'hot_streak',
                'description' => "Série de {$this->serie_victoires_actuelle} victoires",
                'impact_prediction' => 'positif_fort',
            ];
        }

        // Pattern qualité adversaires
        if ($this->victoires_top_10 >= 2) {
            $patterns[] = [
                'type' => 'giant_killer',
                'description' => 'Excellente performance contre le top 10',
                'impact_prediction' => 'positif_fort',
            ];
        }

        // Pattern conditions difficiles
        if ($this->victoires_marathon >= 2) {
            $patterns[] = [
                'type' => 'endurance_master',
                'description' => 'Solide dans les matchs longs',
                'impact_prediction' => 'positif_modere',
            ];
        }

        // Pattern inquiétant
        if ($this->defaites_vs_inferieurs >= 2) {
            $patterns[] = [
                'type' => 'consistency_issues',
                'description' => 'Défaites problématiques contre inférieurs',
                'impact_prediction' => 'negatif_modere',
            ];
        }

        // Pattern blessures
        if ($this->abandons_recents >= 2) {
            $patterns[] = [
                'type' => 'injury_concern',
                'description' => 'Abandons récents inquiétants',
                'impact_prediction' => 'negatif_fort',
            ];
        }

        return $patterns;
    }

    /**
     * Obtenir recommandations pour prédictions
     */
    public function getRecommandationsPrediction()
    {
        $recommandations = [];

        // Forme excellente
        if ($this->forme_index >= 80) {
            $recommandations[] = [
                'type' => 'forme_excellente',
                'message' => 'Joueur en excellente forme, favori probable',
                'coefficient_confiance' => 1.2,
            ];
        }

        // Momentum fort
        if ($this->serie_victoires_actuelle >= 4) {
            $recommandations[] = [
                'type' => 'momentum_positif',
                'message' => 'Momentum très positif, augmenter probabilités',
                'coefficient_confiance' => 1.15,
            ];
        }

        // Forme difficile
        if ($this->forme_index <= 30) {
            $recommandations[] = [
                'type' => 'forme_preoccupante',
                'message' => 'Forme préoccupante, diminuer probabilités',
                'coefficient_confiance' => 0.8,
            ];
        }

        // Qualité adversaires
        if ($this->qualite_victoires >= 8) {
            $recommandations[] = [
                'type' => 'qualite_elevee',
                'message' => 'Excellente qualité des victoires récentes',
                'coefficient_confiance' => 1.1,
            ];
        }

        return $recommandations;
    }

    /**
     * Mettre à jour après un nouveau match
     */
    public function mettreAJourApresMatch(MatchTennis $match)
    {
        $estVictoire = $match->gagnant_id === $this->joueur_id;

        // Décaler les statistiques (nouveau match = màj toutes les périodes)
        if ($estVictoire) {
            $this->victoires_5++;
            $this->victoires_10++;
            $this->victoires_20++;
            $this->victoires_52++;

            // Série
            $this->serie_victoires_actuelle++;
            $this->serie_defaites_actuelle = 0;
            $this->plus_longue_serie_v = max($this->plus_longue_serie_v, $this->serie_victoires_actuelle);
        } else {
            $this->defaites_5++;
            $this->defaites_10++;
            $this->defaites_20++;
            $this->defaites_52++;

            // Série
            $this->serie_defaites_actuelle++;
            $this->serie_victoires_actuelle = 0;
            $this->plus_longue_serie_d = max($this->plus_longue_serie_d, $this->serie_defaites_actuelle);
        }

        // Supprimer le match le plus ancien si nécessaire (garder exactement N matchs)
        $this->ajusterPeriodes();

        // Analyser qualité de la victoire/défaite
        $this->analyserQualiteMatch($match, $estVictoire);

        // Recalculer indices
        $this->calculerIndiceForme();
        $this->calculerConfiance();

        // Mettre à jour date
        $this->date_mise_a_jour = now();
        $this->derniere_analyse = now();

        $this->save();
    }

    /**
     * Ajuster les périodes pour maintenir exactement N matchs
     */
    private function ajusterPeriodes()
    {
        // Si on dépasse 5 matchs pour la période de 5
        $total5 = $this->victoires_5 + $this->defaites_5;
        if ($total5 > 5) {
            // Logic pour retirer le plus ancien (simplifiée ici)
            $ratio = 5 / $total5;
            $this->victoires_5 = (int) ($this->victoires_5 * $ratio);
            $this->defaites_5 = 5 - $this->victoires_5;
        }

        // Même logique pour autres périodes...
    }

    /**
     * Analyser la qualité du match
     */
    private function analyserQualiteMatch(MatchTennis $match, bool $estVictoire)
    {
        $adversaire = $match->joueur1_id === $this->joueur_id ? $match->joueur2 : $match->joueur1;
        $classementAdversaire = $adversaire->classement_atp_wta;

        if ($estVictoire && $classementAdversaire) {
            if ($classementAdversaire <= 10) {
                $this->victoires_top_10++;
            } elseif ($classementAdversaire <= 50) {
                $this->victoires_top_50++;
            } elseif ($classementAdversaire <= 100) {
                $this->victoires_top_100++;
            }

            // Upset causé ?
            $monClassement = $this->joueur->classement_atp_wta;
            if ($monClassement && $classementAdversaire < $monClassement) {
                $this->upsets_causes++;
            }
        } elseif (! $estVictoire && $classementAdversaire) {
            // Défaite contre inférieur ?
            $monClassement = $this->joueur->classement_atp_wta;
            if ($monClassement && $classementAdversaire > $monClassement) {
                $this->defaites_vs_inferieurs++;
            }

            if ($classementAdversaire > $monClassement + 20) {
                $this->upsets_subis++;
            }
        }

        // Analyser contexte du match
        if ($match->duree_match && $match->duree_match > 180) { // Plus de 3h
            $this->matchs_marathon++;
            if ($estVictoire) {
                $this->victoires_marathon++;
            }
        }

        // Abandon ?
        if ($match->raison_fin === 'abandon' && ! $estVictoire) {
            $this->abandons_recents++;
        }
    }

    /**
     * Calculer l'indice de confiance
     */
    private function calculerConfiance()
    {
        $this->confiance_index = $this->getFacteurConfianceAttribute();
    }

    // ===================================================================
    // STATIC METHODS
    // ===================================================================

    /**
     * Obtenir les joueurs en meilleure forme
     */
    public static function getTopForme($limite = 10)
    {
        return self::avecMinimumMatchs(5)
            ->orderBy('forme_index', 'desc')
            ->limit($limite)
            ->with('joueur')
            ->get();
    }

    /**
     * Analyser les tendances globales de forme
     */
    public static function analyserTendancesGlobales()
    {
        return [
            'moyenne_forme' => self::avg('forme_index'),
            'joueurs_en_progression' => self::enProgression()->count(),
            'joueurs_en_regression' => self::enRegression()->count(),
            'series_actives' => self::avecSerie(5)->count(),
            'forme_par_surface' => self::selectRaw('surface_id, AVG(forme_index) as moyenne')
                ->groupBy('surface_id')
                ->with('surface')
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
            'victoires_5' => 'required|integer|min:0|max:5',
            'defaites_5' => 'required|integer|min:0|max:5',
            'victoires_10' => 'required|integer|min:0|max:10',
            'defaites_10' => 'required|integer|min:0|max:10',
            'forme_index' => 'required|numeric|between:0,100',
            'fiabilite_donnees' => 'required|numeric|between:0,100',
        ];
    }

    // ===================================================================
    // BOOT METHODS
    // ===================================================================

    protected static function boot()
    {
        parent::boot();

        // Auto-calculs lors de la sauvegarde
        static::saving(function ($forme) {
            // Calculer forme_index si pas défini
            if (! $forme->forme_index) {
                $forme->calculerIndiceForme();
            }

            // Calculer confiance_index
            $forme->calculerConfiance();

            // Cohérence des données
            $total5 = $forme->victoires_5 + $forme->defaites_5;
            if ($total5 > 5) {
                throw new \InvalidArgumentException('Total des 5 derniers matchs ne peut dépasser 5');
            }

            // Mise à jour automatique des totaux
            $forme->nombre_matchs_echantillon = max($total5, $forme->victoires_20 + $forme->defaites_20);
        });
    }
}
