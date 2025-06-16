<?php

namespace App\Services;

// app/Services/PredictionTempsReelService.php
use App\Models\BlessureMatch;

class PredictionTempsReelService
{
    public function ajusterPourBlessure(BlessureMatch $blessure): array
    {
        $match = $blessure->match;
        $joueurBlesse = $blessure->joueur;
        $adversaire = $match->joueur1_id === $joueurBlesse->id ?
            $match->joueur2 : $match->joueur1;

        // Calcul de l'ajustement
        $ajustement = $blessure->calculerAjustementProbabilite();
        $impact = $blessure->calculerImpactPerformance();

        // Nouvelles probabilités
        $anciennes = [
            'joueur_blesse' => $match->prediction_j1 ?? 0.5,
            'adversaire' => $match->prediction_j2 ?? 0.5
        ];

        $nouvelles = [
            'joueur_blesse' => max(0.05, $anciennes['joueur_blesse'] - $ajustement),
            'adversaire' => min(0.95, $anciennes['adversaire'] + $ajustement)
        ];

        // Prédiction d'évolution
        $evolution = $blessure->predireEvolution();

        return [
            'probabilites_avant' => $anciennes,
            'probabilites_apres' => $nouvelles,
            'ajustement_applique' => $ajustement,
            'impact_detaille' => $impact,
            'evolution_predite' => $evolution,
            'recommandations' => $blessure->genererRecommandations(),
            'confiance_prediction' => $this->calculerConfiancePrediction($blessure)
        ];
    }

    private function calculerConfiancePrediction(BlessureMatch $blessure): int
    {
        $confiance = 5; // Base

        // Plus de confiance si validation médicale
        if ($blessure->validation_medicale) {
            $confiance += 3;
        }

        // Plus de confiance si soins documentés
        if ($blessure->temps_soins_minutes > 0) {
            $confiance += 2;
        }

        // Moins de confiance si nouvelle blessure inconnue
        if ($blessure->gravite <= 2) {
            $confiance -= 1;
        }

        return min(10, max(1, $confiance));
    }
}
