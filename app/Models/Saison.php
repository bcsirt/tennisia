<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Saison extends Model
{
    protected $fillable = [
        'annee',
        'date_debut',
        'date_fin',
        'est_active'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'est_active' => 'boolean'
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function statistiquesJoueurs()
    {
        return $this->hasMany(StatistiqueJoueur::class);
    }

    public function tournois()
    {
        return $this->hasMany(Tournoi::class);
    }

    // Relation vers les matchs via les tournois
    public function matchs()
    {
        return $this->hasManyThrough(MatchTennis::class, Tournoi::class);
    }

    // ==========================================
    // SCOPES - GitHub Copilot va bien les suggérer
    // ==========================================

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('est_active', true);
    }

    public function scopeCourante(Builder $query): Builder
    {
        return $query->where('annee', now()->year);
    }

    public function scopeRecentes(Builder $query, int $nbAnnees = 3): Builder
    {
        return $query->where('annee', '>=', now()->year - $nbAnnees);
    }

    // ==========================================
    // MÉTHODES MÉTIER
    // ==========================================

    /**
     * Retourne les statistiques de base de la saison
     */
    public function getStatsDeBase(): array
    {
        return [
            'nb_tournois' => $this->tournois()->count(),
            'nb_matchs' => $this->matchs()->count(),
            'nb_joueurs' => $this->statistiquesJoueurs()->distinct('joueur_id')->count()
        ];
    }

    /**
     * Vérifie si la saison est en cours
     */
    public function estEnCours(): bool
    {
        $maintenant = now();
        return $this->date_debut <= $maintenant && $maintenant <= $this->date_fin;
    }

    /**
     * Retourne les tournois Grand Slam de la saison
     */
    public function getGrandSlams()
    {
        return $this->tournois()
            ->where('categorie', 'Grand Slam')
            ->orderBy('date_debut')
            ->get();
    }

    // ==========================================
    // MÉTHODES STATIQUES
    // ==========================================

    /**
     * Retourne la saison actuelle ou la crée
     */
    public static function actuelle(): self
    {
        return static::firstOrCreate(
            ['annee' => now()->year],
            [
                'date_debut' => now()->startOfYear(),
                'date_fin' => now()->endOfYear(),
                'est_active' => true
            ]
        );
    }
}
