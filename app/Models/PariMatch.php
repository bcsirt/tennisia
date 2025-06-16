<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PariMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_tennis_id',
        'cote_j1_live',
        'cote_j2_live',
        'cote_j1_ouverture',
        'cote_j2_ouverture',
        'volume_paris',
        'volume_j1',
        'volume_j2',
        'mouvement_cote',
        'anomalie_detectee',
        'type_anomalie',
        'details_anomalie',
        'bookmaker_source',
        'timestamp_maj',
        'pourcentage_paris_j1',
        'pourcentage_paris_j2',
    ];

    protected $casts = [
        'cote_j1_live' => 'decimal:2',
        'cote_j2_live' => 'decimal:2',
        'cote_j1_ouverture' => 'decimal:2',
        'cote_j2_ouverture' => 'decimal:2',
        'volume_paris' => 'decimal:2',
        'volume_j1' => 'decimal:2',
        'volume_j2' => 'decimal:2',
        'anomalie_detectee' => 'boolean',
        'timestamp_maj' => 'datetime',
        'pourcentage_paris_j1' => 'decimal:1',
        'pourcentage_paris_j2' => 'decimal:1',
        'details_anomalie' => 'array',
    ];

    protected $appends = [
        'probabilite_implicite_j1',
        'probabilite_implicite_j2',
        'marge_bookmaker',
        'variation_cote_j1',
        'variation_cote_j2',
    ];

    // Relations
    public function match()
    {
        return $this->belongsTo(MatchTennis::class, 'match_tennis_id');
    }

    // Accessors pour les calculs de probabilités
    public function getProbabiliteImpliciteJ1Attribute()
    {
        return $this->cote_j1_live ? round(1 / $this->cote_j1_live * 100, 2) : null;
    }

    public function getProbabiliteImpliciteJ2Attribute()
    {
        return $this->cote_j2_live ? round(1 / $this->cote_j2_live * 100, 2) : null;
    }

    public function getMargeBookmakerAttribute()
    {
        if (! $this->cote_j1_live || ! $this->cote_j2_live) {
            return null;
        }

        $prob_totale = (1 / $this->cote_j1_live) + (1 / $this->cote_j2_live);

        return round(($prob_totale - 1) * 100, 2);
    }

    public function getVariationCoteJ1Attribute()
    {
        if (! $this->cote_j1_ouverture || ! $this->cote_j1_live) {
            return null;
        }

        return round((($this->cote_j1_live - $this->cote_j1_ouverture) / $this->cote_j1_ouverture) * 100, 2);
    }

    public function getVariationCoteJ2Attribute()
    {
        if (! $this->cote_j2_ouverture || ! $this->cote_j2_live) {
            return null;
        }

        return round((($this->cote_j2_live - $this->cote_j2_ouverture) / $this->cote_j2_ouverture) * 100, 2);
    }

    // Scopes pour les requêtes courantes
    public function scopeAvecAnomalies($query)
    {
        return $query->where('anomalie_detectee', true);
    }

    public function scopeMouvementSignificatif($query, $seuil = 10)
    {
        return $query->where(function ($q) use ($seuil) {
            $q->whereRaw('ABS(((cote_j1_live - cote_j1_ouverture) / cote_j1_ouverture) * 100) >= ?', [$seuil])
                ->orWhereRaw('ABS(((cote_j2_live - cote_j2_ouverture) / cote_j2_ouverture) * 100) >= ?', [$seuil]);
        });
    }

    public function scopeVolumeEleve($query, $seuil = 10000)
    {
        return $query->where('volume_paris', '>=', $seuil);
    }

    // Méthodes utilitaires
    public function detecterAnomalie()
    {
        $anomalies = [];

        // Mouvement de cote suspect (>30% en peu de temps)
        if (abs($this->variation_cote_j1) > 30 || abs($this->variation_cote_j2) > 30) {
            $anomalies[] = 'mouvement_cote_extreme';
        }

        // Volume anormalement élevé
        if ($this->volume_paris > 50000) {
            $anomalies[] = 'volume_suspect';
        }

        // Déséquilibre dans les paris (90%+ sur un joueur)
        if ($this->pourcentage_paris_j1 > 90 || $this->pourcentage_paris_j2 > 90) {
            $anomalies[] = 'desequilibre_paris';
        }

        // Marge bookmaker anormalement faible
        if ($this->marge_bookmaker < 2) {
            $anomalies[] = 'marge_faible';
        }

        if (! empty($anomalies)) {
            $this->update([
                'anomalie_detectee' => true,
                'type_anomalie' => implode(',', $anomalies),
                'details_anomalie' => $anomalies,
            ]);
        }

        return $anomalies;
    }

    public function calculerIndiceMouvement()
    {
        // Indice composite du mouvement du marché
        $variation_moyenne = (abs($this->variation_cote_j1) + abs($this->variation_cote_j2)) / 2;
        $facteur_volume = min($this->volume_paris / 10000, 5); // Plafonné à 5

        return round($variation_moyenne * $facteur_volume, 2);
    }

    public function estArbitrage($marge_max = -1)
    {
        // Détecte si une opportunité d'arbitrage existe
        return $this->marge_bookmaker <= $marge_max;
    }

    public function valeurAttendue($prediction_ia)
    {
        // Calcule la valeur attendue par rapport à la prédiction IA
        $prob_marche_j1 = $this->probabilite_implicite_j1 / 100;
        $prob_ia_j1 = $prediction_ia / 100;

        if ($prob_ia_j1 > $prob_marche_j1) {
            return ($this->cote_j1_live * $prob_ia_j1) - 1;
        }

        return null;
    }
}
