<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategorieTournoi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categorie_tournois';

    protected $fillable = [
        'nom',
        'nom_court',
        'code',                    // grand_chelem, masters_1000, atp_500, etc.
        'description',
        'niveau_hierarchique',     // 1=Grand Slam, 2=Masters 1000, etc.
        'points_atp_gagnant',     // Points ATP par défaut pour le gagnant
        'points_wta_gagnant',     // Points WTA par défaut pour le gagnant
        'prize_money_minimum',    // Prize money minimum requis
        'nb_tournois_par_an',     // Nombre typique de tournois par an
        'couleur_hex',            // Couleur pour l'affichage (UI)
        'icone',                  // Icône ou emoji représentatif
        'ordre_affichage',        // Ordre pour les listes
        'est_majeur',             // Booléen : tournoi majeur ou non
        'genre',                  // 'homme', 'femme', 'mixte'
        'actif',                   // Catégorie active ou obsolète
    ];

    protected $casts = [
        'niveau_hierarchique' => 'integer',
        'points_atp_gagnant' => 'integer',
        'points_wta_gagnant' => 'integer',
        'prize_money_minimum' => 'decimal:2',
        'nb_tournois_par_an' => 'integer',
        'ordre_affichage' => 'integer',
        'est_majeur' => 'boolean',
        'actif' => 'boolean',
    ];

    protected $appends = [
        'est_grand_chelem',
        'est_masters',
        'prestige_level',
        'tournois_count',
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function tournois()
    {
        return $this->hasMany(Tournoi::class, 'categorie_tournoi_id');
    }

    public function tournoidsActifs()
    {
        return $this->hasMany(Tournoi::class, 'categorie_tournoi_id')
            ->where('statut', '!=', 'annule');
    }

    public function tournoiSaison($annee = null)
    {
        $annee = $annee ?? date('Y');

        return $this->hasMany(Tournoi::class, 'categorie_tournoi_id')
            ->whereHas('saison', function ($q) use ($annee) {
                $q->where('annee', $annee);
            });
    }

    // ===================================================================
    // ACCESSORS
    // ===================================================================

    public function getEstGrandChelemAttribute()
    {
        return $this->code === 'grand_chelem';
    }

    public function getEstMastersAttribute()
    {
        return $this->code === 'masters_1000';
    }

    public function getPrestigeLevelAttribute()
    {
        $levels = [
            'grand_chelem' => 'Prestige Maximum',
            'masters_1000' => 'Elite',
            'atp_500' => 'Majeur',
            'atp_250' => 'Standard',
            'challenger' => 'Développement',
            'itf' => 'Formation',
        ];

        return $levels[$this->code] ?? 'Non défini';
    }

    public function getTournoisCountAttribute()
    {
        return $this->tournois()->count();
    }

    public function getNomCompletAttribute()
    {
        $suffixe = $this->est_majeur ? ' ⭐' : '';

        return $this->nom.$suffixe;
    }

    public function getPointsMoyensAttribute()
    {
        // Moyenne entre ATP et WTA si les deux existent
        $pointsAtp = $this->points_atp_gagnant ?? 0;
        $pointsWta = $this->points_wta_gagnant ?? 0;

        if ($pointsAtp > 0 && $pointsWta > 0) {
            return ($pointsAtp + $pointsWta) / 2;
        }

        return max($pointsAtp, $pointsWta);
    }

    // ===================================================================
    // SCOPES
    // ===================================================================

    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    public function scopeParGenre($query, $genre)
    {
        return $query->where('genre', $genre);
    }

    public function scopeMajeurs($query)
    {
        return $query->where('est_majeur', true);
    }

    public function scopeParNiveauHierarchique($query, $niveau)
    {
        return $query->where('niveau_hierarchique', $niveau);
    }

    public function scopeOrdonnes($query)
    {
        return $query->orderBy('niveau_hierarchique')
            ->orderBy('ordre_affichage')
            ->orderBy('nom');
    }

    public function scopeGrandsChelem($query)
    {
        return $query->where('code', 'grand_chelem');
    }

    public function scopeMasters($query)
    {
        return $query->where('code', 'masters_1000');
    }

    public function scopeAvecTournois($query)
    {
        return $query->has('tournois');
    }

    public function scopeRecherche($query, $terme)
    {
        return $query->where(function ($q) use ($terme) {
            $q->where('nom', 'LIKE', "%{$terme}%")
                ->orWhere('nom_court', 'LIKE', "%{$terme}%")
                ->orWhere('code', 'LIKE', "%{$terme}%")
                ->orWhere('description', 'LIKE', "%{$terme}%");
        });
    }

    // ===================================================================
    // STATIC METHODS
    // ===================================================================

    /**
     * Obtenir les catégories par ordre de prestige
     */
    public static function parPrestige()
    {
        return self::actifs()
            ->ordonnes()
            ->get();
    }

    /**
     * Obtenir la hiérarchie complète
     */
    public static function getHierarchie()
    {
        return self::actifs()
            ->select('code', 'nom', 'niveau_hierarchique', 'couleur_hex')
            ->ordonnes()
            ->get()
            ->mapWithKeys(function ($cat) {
                return [$cat->code => [
                    'nom' => $cat->nom,
                    'niveau' => $cat->niveau_hierarchique,
                    'couleur' => $cat->couleur_hex,
                ]];
            });
    }

    /**
     * Obtenir les statistiques par catégorie
     */
    public static function getStatistiques($annee = null)
    {
        $annee = $annee ?? date('Y');

        return self::actifs()
            ->withCount(['tournoiSaison as tournois_cette_annee' => function ($q) {
                // Le withCount utilise automatiquement la relation tournoiSaison
            }])
            ->get()
            ->map(function ($categorie) {
                return [
                    'nom' => $categorie->nom,
                    'code' => $categorie->code,
                    'tournois_cette_annee' => $categorie->tournois_cette_annee,
                    'points_max' => $categorie->points_moyens,
                    'prestige' => $categorie->prestige_level,
                ];
            });
    }

    // ===================================================================
    // METHODS
    // ===================================================================

    /**
     * Obtenir les points de distribution par round
     */
    public function getDistributionPoints()
    {
        $pointsBase = $this->points_moyens;

        if (! $pointsBase) {
            return [];
        }

        // Distribution typique tennis professionnel
        return [
            'gagnant' => $pointsBase,
            'finaliste' => round($pointsBase * 0.6),
            'demi_finaliste' => round($pointsBase * 0.36),
            'quart_finaliste' => round($pointsBase * 0.18),
            'huitieme' => round($pointsBase * 0.09),
            'deuxieme_tour' => round($pointsBase * 0.045),
            'premier_tour' => round($pointsBase * 0.01),
        ];
    }

    /**
     * Obtenir la configuration typique d'un tournoi de cette catégorie
     */
    public function getConfigurationTournoi()
    {
        $configs = [
            'grand_chelem' => [
                'nb_joueurs' => 128,
                'nb_sets_victoire' => 3,
                'format_finale' => 5,
                'duree_jours' => 14,
            ],
            'masters_1000' => [
                'nb_joueurs' => 56,
                'nb_sets_victoire' => 2,
                'format_finale' => 3,
                'duree_jours' => 8,
            ],
            'atp_500' => [
                'nb_joueurs' => 32,
                'nb_sets_victoire' => 2,
                'format_finale' => 3,
                'duree_jours' => 7,
            ],
            'atp_250' => [
                'nb_joueurs' => 28,
                'nb_sets_victoire' => 2,
                'format_finale' => 3,
                'duree_jours' => 6,
            ],
        ];

        return $configs[$this->code] ?? [
            'nb_joueurs' => 32,
            'nb_sets_victoire' => 2,
            'format_finale' => 3,
            'duree_jours' => 7,
        ];
    }

    /**
     * Vérifier si un prize money est conforme
     */
    public function prizeMoneySuffisant($montant)
    {
        if (! $this->prize_money_minimum) {
            return true;
        }

        return $montant >= $this->prize_money_minimum;
    }

    /**
     * Obtenir la couleur d'affichage
     */
    public function getCouleurAffichage()
    {
        if ($this->couleur_hex) {
            return $this->couleur_hex;
        }

        // Couleurs par défaut selon la catégorie
        $couleursDefaut = [
            'grand_chelem' => '#FFD700',    // Or
            'masters_1000' => '#FF6B35',    // Orange
            'atp_500' => '#4ECDC4',         // Turquoise
            'atp_250' => '#45B7D1',         // Bleu
            'challenger' => '#96CEB4',       // Vert
            'itf' => '#DDA0DD',              // Violet
        ];

        return $couleursDefaut[$this->code] ?? '#6C757D';
    }

    // ===================================================================
    // VALIDATION RULES
    // ===================================================================

    public static function validationRules()
    {
        return [
            'nom' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:categorie_tournois,code',
            'niveau_hierarchique' => 'required|integer|min:1|max:10',
            'points_atp_gagnant' => 'nullable|integer|min:0|max:5000',
            'points_wta_gagnant' => 'nullable|integer|min:0|max:5000',
            'genre' => 'required|in:homme,femme,mixte',
            'couleur_hex' => 'nullable|regex:/^#[A-Fa-f0-9]{6}$/',
        ];
    }

    // ===================================================================
    // BOOT METHODS
    // ===================================================================

    protected static function boot()
    {
        parent::boot();

        // Générer automatiquement l'ordre d'affichage
        static::creating(function ($categorie) {
            if (! $categorie->ordre_affichage) {
                $maxOrdre = self::max('ordre_affichage') ?? 0;
                $categorie->ordre_affichage = $maxOrdre + 1;
            }

            // Valeurs par défaut
            if ($categorie->actif === null) {
                $categorie->actif = true;
            }
        });
    }
}
