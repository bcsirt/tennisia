<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Joueur extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'joueurs';

    protected $fillable = [
        // Informations personnelles
        'nom',
        'prenom',
        'pays_id',
        'date_naissance',
        'sexe',
        'main',
        'revers',
        'taille',
        'poids',

        // Classement et performance
        'classement_atp_wta',
        'classement_precedent',
        'meilleur_classement',
        'points_actuels',
        'niveau_joueur_id',

        // Statistiques carrière
        'victoires_saison',
        'defaites_saison',
        'victoires_carriere',
        'defaites_carriere',
        'titres_carriere',
        'prize_money',

        // Statut
        'statut',
        'date_debut_pro',
        'entraineur',
        'surface_favorite',
        'photo_url'
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_debut_pro' => 'date',
        'taille' => 'integer',
        'poids' => 'integer',
        'classement_atp_wta' => 'integer',
        'classement_precedent' => 'integer',
        'meilleur_classement' => 'integer',
        'points_actuels' => 'integer',
        'victoires_saison' => 'integer',
        'defaites_saison' => 'integer',
        'victoires_carriere' => 'integer',
        'defaites_carriere' => 'integer',
        'titres_carriere' => 'integer',
        'prize_money' => 'decimal:2'
    ];

    protected $appends = [
        'nom_complet',
        'age',
        'pourcentage_victoires_saison',
        'pourcentage_victoires_carriere'
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function pays()
    {
        return $this->belongsTo(Pays::class);
    }

    public function niveau()
    {
        return $this->belongsTo(NiveauJoueur::class, 'niveau_joueur_id');
    }

    public function statistiques()
    {
        return $this->hasMany(StatistiqueJoueur::class);
    }

    public function blessures()
    {
        return $this->hasMany(Blessure::class);
    }

    public function confrontations()
    {
        return $this->hasMany(Confrontation::class, 'joueur1_id');
    }

    public function formeRecente()
    {
        return $this->hasOne(FormeRecente::class);
    }

    // Relations matchs (joueur peut être joueur1 ou joueur2)
    public function matchsJoueur1()
    {
        return $this->hasMany(MatchTennis::class, 'joueur1_id');
    }

    public function matchsJoueur2()
    {
        return $this->hasMany(MatchTennis::class, 'joueur2_id');
    }

    // Tous les matchs du joueur (union des deux relations)
    public function getAllMatchsAttribute()
    {
        return MatchTennis::where('joueur1_id', $this->id)
            ->orWhere('joueur2_id', $this->id)
            ->orderBy('date_match', 'desc')
            ->get();
    }

    public function matchsGagnes()
    {
        return $this->hasMany(MatchTennis::class, 'gagnant_id');
    }

    public function predictions()
    {
        return $this->hasMany(Prediction::class, 'gagnant_predit_id');
    }

    // ===================================================================
    // ACCESSORS
    // ===================================================================

    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function getAgeAttribute()
    {
        return $this->date_naissance ? $this->date_naissance->age : null;
    }

    public function getPourcentageVictoiresSaisonAttribute()
    {
        $total = $this->victoires_saison + $this->defaites_saison;
        return $total > 0 ? round(($this->victoires_saison / $total) * 100, 2) : 0;
    }

    public function getPourcentageVictoiresCarriereAttribute()
    {
        $total = $this->victoires_carriere + $this->defaites_carriere;
        return $total > 0 ? round(($this->victoires_carriere / $total) * 100, 2) : 0;
    }

    public function getClassementEvolutionAttribute()
    {
        if (!$this->classement_precedent || !$this->classement_atp_wta) {
            return 'stable';
        }

        $evolution = $this->classement_precedent - $this->classement_atp_wta;

        if ($evolution > 0) return 'hausse';
        if ($evolution < 0) return 'baisse';
        return 'stable';
    }

    public function getEstTopJoueurAttribute()
    {
        return $this->classement_atp_wta && $this->classement_atp_wta <= 100;
    }

    public function getSurfaceStatistiquesAttribute()
    {
        return $this->statistiques()
            ->selectRaw('surface,
                              SUM(victoires) as total_victoires,
                              SUM(defaites) as total_defaites,
                              ROUND((SUM(victoires) / (SUM(victoires) + SUM(defaites))) * 100, 2) as pourcentage')
            ->groupBy('surface')
            ->get();
    }

    // ===================================================================
    // SCOPES
    // ===================================================================

    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopeParSexe($query, $sexe)
    {
        return $query->where('sexe', $sexe);
    }

    public function scopeParNationalite($query, $paysId)
    {
        return $query->where('pays_id', $paysId);
    }

    public function scopeTopClassement($query, $limite = 100)
    {
        return $query->where('classement_atp_wta', '<=', $limite)
            ->where('classement_atp_wta', '>', 0)
            ->orderBy('classement_atp_wta');
    }

    public function scopeParSurfaceFavorite($query, $surface)
    {
        return $query->where('surface_favorite', $surface);
    }

    public function scopeAvecBlessure($query)
    {
        return $query->whereHas('blessures', function($q) {
            $q->where('statut', 'active');
        });
    }

    public function scopeSansBlessure($query)
    {
        return $query->whereDoesntHave('blessures', function($q) {
            $q->where('statut', 'active');
        });
    }

    public function scopeRecherche($query, $terme)
    {
        return $query->where(function($q) use ($terme) {
            $q->where('nom', 'LIKE', "%{$terme}%")
                ->orWhere('prenom', 'LIKE', "%{$terme}%");
        });
    }

    // ===================================================================
    // METHODS
    // ===================================================================

    /**
     * Obtenir le classement ELO du joueur
     */
    public function getEloRating($surface = null)
    {
        $query = $this->statistiques();

        if ($surface) {
            $query->where('surface', $surface);
        }

        return $query->latest()->value('elo_rating') ?? 1500; // ELO par défaut
    }

    /**
     * Calculer la forme récente du joueur
     */
    public function getFormeRecente($nbMatchs = 5)
    {
        $matchsRecents = MatchTennis::where(function($query) {
            $query->where('joueur1_id', $this->id)
                ->orWhere('joueur2_id', $this->id);
        })
            ->where('statut', 'termine')
            ->orderBy('date_match', 'desc')
            ->limit($nbMatchs)
            ->get();

        $victoires = $matchsRecents->where('gagnant_id', $this->id)->count();

        return [
            'victoires' => $victoires,
            'defaites' => $matchsRecents->count() - $victoires,
            'pourcentage' => $matchsRecents->count() > 0 ?
                round(($victoires / $matchsRecents->count()) * 100, 2) : 0
        ];
    }

    /**
     * Obtenir les statistiques face-à-face contre un adversaire
     */
    public function getHeadToHead($adversaireId)
    {
        $confrontation = Confrontation::where(function($query) use ($adversaireId) {
            $query->where(['joueur1_id' => $this->id, 'joueur2_id' => $adversaireId])
                ->orWhere(['joueur1_id' => $adversaireId, 'joueur2_id' => $this->id]);
        })->first();

        if (!$confrontation) {
            return ['victoires' => 0, 'defaites' => 0, 'total' => 0];
        }

        $victoires = $confrontation->joueur1_id == $this->id ?
            $confrontation->victoires_joueur1 :
            $confrontation->victoires_joueur2;

        $defaites = $confrontation->confrontations_totales - $victoires;

        return [
            'victoires' => $victoires,
            'defaites' => $defaites,
            'total' => $confrontation->confrontations_totales
        ];
    }

    /**
     * Vérifier si le joueur est blessé
     */
    public function estBlesse()
    {
        return $this->blessures()
            ->where('statut', 'active')
            ->whereNull('date_guerison')
            ->exists();
    }

    /**
     * Obtenir la surface préférée basée sur les statistiques
     */
    public function getSurfacePrefereeCalculee()
    {
        return $this->statistiques()
            ->selectRaw('surface,
                              (SUM(victoires) / (SUM(victoires) + SUM(defaites))) * 100 as pourcentage')
            ->groupBy('surface')
            ->havingRaw('SUM(victoires) + SUM(defaites) >= 5') // Minimum 5 matchs
            ->orderBy('pourcentage', 'desc')
            ->first()
            ->surface ?? 'dur';
    }

    /**
     * Prédire la probabilité de victoire contre un adversaire
     */
    public function getProbabiliteVictoire($adversaire, $surface = 'dur')
    {
        // Calcul simplifié basé sur ELO
        $eloJoueur = $this->getEloRating($surface);
        $eloAdversaire = $adversaire->getEloRating($surface);

        $probabilite = 1 / (1 + pow(10, ($eloAdversaire - $eloJoueur) / 400));

        return round($probabilite * 100, 2);
    }

    // ===================================================================
    // VALIDATION RULES (pour les Form Requests)
    // ===================================================================

    public static function validationRules()
    {
        return [
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'pays_id' => 'required|exists:pays,id',
            'date_naissance' => 'required|date|before:today',
            'sexe' => 'required|in:M,F',
            'main' => 'required|in:droitier,gaucher',
            'revers' => 'required|in:une_main,deux_mains',
            'taille' => 'required|integer|between:150,230',
            'poids' => 'required|integer|between:50,150',
            'classement_atp_wta' => 'nullable|integer|min:1',
            'statut' => 'required|in:actif,inactif,retraite'
        ];
    }
}
