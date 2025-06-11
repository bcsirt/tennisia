<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tournoi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tournois';

    protected $fillable = [
        // Informations de base
        'nom',
        'nom_court',
        'code_atp_wta', // Code officiel ATP/WTA
        'categorie_tournoi_id',
        'pays_id',
        'ville',
        'surface_id',
        'saison_id',

        // Dates et timing
        'date_debut',
        'date_fin',
        'date_inscription_limite',
        'fuseau_horaire',

        // Structure du tournoi
        'nb_joueurs_tableau_principal',
        'nb_joueurs_qualifications',
        'nb_sets_victoire', // 2 ou 3 pour gagner
        'format_finale', // Best of 3 ou 5

        // Informations financières
        'prize_money_total',
        'prize_money_gagnant',
        'currency',
        'points_atp_wta_gagnant',

        // Organisation
        'organisateur',
        'sponsor_principal',
        'directeur_tournoi',
        'site_web',

        // Conditions
        'indoor_outdoor',
        'vitesse_surface_id',
        'altitude', // mètres au-dessus du niveau de la mer

        // Statut et métadonnées
        'statut', // programme, en_cours, termine, annule
        'edition_numero',
        'annee_creation',
        'notes',
        'logo_url',

        // Données techniques
        'source_donnees_id',
        'derniere_maj'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_inscription_limite' => 'date',
        'derniere_maj' => 'datetime',
        'nb_joueurs_tableau_principal' => 'integer',
        'nb_joueurs_qualifications' => 'integer',
        'nb_sets_victoire' => 'integer',
        'prize_money_total' => 'decimal:2',
        'prize_money_gagnant' => 'decimal:2',
        'points_atp_wta_gagnant' => 'integer',
        'edition_numero' => 'integer',
        'annee_creation' => 'integer',
        'altitude' => 'integer'
    ];

    protected $appends = [
        'duree',
        'est_en_cours',
        'est_termine',
        'est_grand_chelem',
        'est_masters',
        'phase_actuelle',
        'pourcentage_completion'
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function categorie()
    {
        return $this->belongsTo(CategorieTournoi::class, 'categorie_tournoi_id');
    }

    public function pays()
    {
        return $this->belongsTo(Pays::class);
    }

    public function surface()
    {
        return $this->belongsTo(Surface::class);
    }

    public function vitesseSurface()
    {
        return $this->belongsTo(VitesseSurface::class, 'vitesse_surface_id');
    }

    public function saison()
    {
        return $this->belongsTo(Saison::class);
    }

    public function sourceDonnees()
    {
        return $this->belongsTo(SourceDonnees::class, 'source_donnees_id');
    }

    // Relations vers les matchs et tableaux
    public function matchs()
    {
        return $this->hasMany(MatchTennis::class)->orderBy('date_match');
    }

    public function matchsTermines()
    {
        return $this->hasMany(MatchTennis::class)
            ->whereHas('statut', function($q) {
                $q->where('code', 'termine');
            });
    }

    public function matchsProgrammes()
    {
        return $this->hasMany(MatchTennis::class)
            ->whereHas('statut', function($q) {
                $q->where('code', 'programme');
            });
    }

    public function matchsEnCours()
    {
        return $this->hasMany(MatchTennis::class)
            ->whereHas('statut', function($q) {
                $q->where('code', 'en_cours');
            });
    }

    // Relations par rounds
    public function premiersToursMatchs()
    {
        return $this->hasMany(MatchTennis::class)
            ->whereHas('round', function($q) {
                $q->where('code', 'premier_tour');
            });
    }

    public function finale()
    {
        return $this->hasOne(MatchTennis::class)
            ->whereHas('round', function($q) {
                $q->where('code', 'finale');
            });
    }

    public function demiFinales()
    {
        return $this->hasMany(MatchTennis::class)
            ->whereHas('round', function($q) {
                $q->where('code', 'demi_finale');
            });
    }

    // Participants
    public function joueurs()
    {
        return $this->belongsToMany(Joueur::class, 'matchs_tennis')
            ->distinct()
            ->withPivot('round_tournoi_id', 'statut_match_id');
    }

    public function gagnant()
    {
        return $this->hasOneThrough(
            Joueur::class,
            MatchTennis::class,
            'tournoi_id',
            'id',
            'id',
            'gagnant_id'
        )->whereHas('matchTennis.round', function($q) {
            $q->where('code', 'finale');
        });
    }

    public function finaliste()
    {
        $finale = $this->finale;
        if (!$finale || !$finale->gagnant_id) return null;

        $perdantId = $finale->gagnant_id == $finale->joueur1_id ?
            $finale->joueur2_id : $finale->joueur1_id;

        return $this->belongsTo(Joueur::class, 'finaliste_id')->where('id', $perdantId);
    }

    // ===================================================================
    // ACCESSORS
    // ===================================================================

    public function getDureeAttribute()
    {
        if (!$this->date_debut || !$this->date_fin) return null;
        return $this->date_debut->diffInDays($this->date_fin) + 1;
    }

    public function getEstEnCoursAttribute()
    {
        return $this->statut === 'en_cours' ||
            (now()->between($this->date_debut, $this->date_fin) && $this->statut !== 'termine');
    }

    public function getEstTermineAttribute()
    {
        return $this->statut === 'termine' ||
            ($this->finale && $this->finale->est_termine);
    }

    public function getEstGrandChelemAttribute()
    {
        return $this->categorie?->code === 'grand_chelem';
    }

    public function getEstMastersAttribute()
    {
        return $this->categorie?->code === 'masters_1000';
    }

    public function getPhaseActuelleAttribute()
    {
        if ($this->est_termine) return 'Terminé';
        if (!$this->est_en_cours) return 'À venir';

        // Déterminer la phase basée sur les matchs en cours/récents
        $derniersMatchs = $this->matchs()
            ->whereHas('statut', function($q) {
                $q->whereIn('code', ['en_cours', 'termine']);
            })
            ->with('round')
            ->orderBy('date_match', 'desc')
            ->limit(5)
            ->get();

        if ($derniersMatchs->isEmpty()) return 'Premier tour';

        $phases = ['finale', 'demi_finale', 'quart_finale', 'huitieme', 'deuxieme_tour', 'premier_tour'];

        foreach ($phases as $phase) {
            if ($derniersMatchs->contains(function($match) use ($phase) {
                return $match->round?->code === $phase;
            })) {
                return ucfirst(str_replace('_', ' ', $phase));
            }
        }

        return 'En cours';
    }

    public function getPourcentageCompletionAttribute()
    {
        $totalMatchs = $this->matchs()->count();
        if ($totalMatchs === 0) return 0;

        $matchsTermines = $this->matchsTermines()->count();
        return round(($matchsTermines / $totalMatchs) * 100, 1);
    }

    public function getNomCompletAttribute()
    {
        $suffixes = [];

        if ($this->edition_numero) {
            $suffixes[] = "#{$this->edition_numero}";
        }

        if ($this->saison?->annee) {
            $suffixes[] = $this->saison->annee;
        }

        return $this->nom . (empty($suffixes) ? '' : ' (' . implode(' - ', $suffixes) . ')');
    }

    public function getPrizeMoneyCategoryAttribute()
    {
        if (!$this->prize_money_total) return 'Non défini';

        $amount = $this->prize_money_total;

        if ($amount >= 50000000) return 'Premium'; // 50M+
        if ($amount >= 10000000) return 'Elite';   // 10M+
        if ($amount >= 5000000) return 'Major';    // 5M+
        if ($amount >= 1000000) return 'Standard'; // 1M+
        return 'Challenger';
    }

    // ===================================================================
    // SCOPES
    // ===================================================================

    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours')
            ->orWhere(function($q) {
                $q->whereBetween(now(), ['date_debut', 'date_fin'])
                    ->where('statut', '!=', 'termine');
            });
    }

    public function scopeTermines($query)
    {
        return $query->where('statut', 'termine');
    }

    public function scopeProgrammes($query)
    {
        return $query->where('statut', 'programme')
            ->where('date_debut', '>', now());
    }

    public function scopeParCategorie($query, $categorieCode)
    {
        return $query->whereHas('categorie', function($q) use ($categorieCode) {
            $q->where('code', $categorieCode);
        });
    }

    public function scopeParSurface($query, $surfaceCode)
    {
        return $query->whereHas('surface', function($q) use ($surfaceCode) {
            $q->where('code', $surfaceCode);
        });
    }

    public function scopeParPays($query, $paysCode)
    {
        return $query->whereHas('pays', function($q) use ($paysCode) {
            $q->where('code_iso', $paysCode);
        });
    }

    public function scopeParSaison($query, $annee)
    {
        return $query->whereHas('saison', function($q) use ($annee) {
            $q->where('annee', $annee);
        });
    }

    public function scopeGrandsSlams($query)
    {
        return $query->parCategorie('grand_chelem');
    }

    public function scopeMasters($query)
    {
        return $query->parCategorie('masters_1000');
    }

    public function scopeAvecPrizeMoney($query, $minimum = null)
    {
        $q = $query->whereNotNull('prize_money_total');

        if ($minimum) {
            $q->where('prize_money_total', '>=', $minimum);
        }

        return $q;
    }

    public function scopeRecherche($query, $terme)
    {
        return $query->where(function($q) use ($terme) {
            $q->where('nom', 'LIKE', "%{$terme}%")
                ->orWhere('nom_court', 'LIKE', "%{$terme}%")
                ->orWhere('ville', 'LIKE', "%{$terme}%");
        });
    }

    // ===================================================================
    // METHODS
    // ===================================================================

    /**
     * Obtenir le tableau du tournoi organisé par rounds
     */
    public function getTableauComplet()
    {
        $rounds = RoundTournoi::orderBy('ordre')->get();
        $tableau = [];

        foreach ($rounds as $round) {
            $matchs = $this->matchs()
                ->where('round_tournoi_id', $round->id)
                ->with(['joueur1', 'joueur2', 'gagnant'])
                ->orderBy('date_match')
                ->get();

            if ($matchs->isNotEmpty()) {
                $tableau[$round->nom] = $matchs;
            }
        }

        return $tableau;
    }

    /**
     * Calculer les points ATP/WTA pour chaque round
     */
    public function getPointsParRound()
    {
        $basePoints = $this->points_atp_wta_gagnant;

        if (!$basePoints) return [];

        // Points standard par round (% du total)
        $distribution = [
            'finale' => 1.0,
            'demi_finale' => 0.6,
            'quart_finale' => 0.36,
            'huitieme' => 0.18,
            'troisieme_tour' => 0.09,
            'deuxieme_tour' => 0.045,
            'premier_tour' => 0.01
        ];

        $points = [];
        foreach ($distribution as $round => $ratio) {
            $points[$round] = (int)($basePoints * $ratio);
        }

        return $points;
    }

    /**
     * Obtenir les statistiques du tournoi
     */
    public function getStatistiques()
    {
        $stats = [
            'nb_matchs_total' => $this->matchs()->count(),
            'nb_matchs_termines' => $this->matchsTermines()->count(),
            'nb_participants' => $this->joueurs()->count(),
            'duree_moyenne_match' => $this->matchsTermines()
                ->whereNotNull('duree_match')
                ->avg('duree_match'),
            'nb_upsets' => $this->getNombreUpsets(),
            'surface_dominante' => $this->surface?->nom,
            'temperature_moyenne' => $this->matchs()
                ->whereNotNull('temperature')
                ->avg('temperature')
        ];

        return $stats;
    }

    /**
     * Compter le nombre d'upsets (favoris éliminés)
     */
    public function getNombreUpsets()
    {
        return $this->matchsTermines()
            ->whereNotNull('cote_joueur1')
            ->whereNotNull('cote_joueur2')
            ->get()
            ->filter(function($match) {
                // Upset si le joueur avec la plus haute cote a gagné
                $coteFavorite = min($match->cote_joueur1, $match->cote_joueur2);
                $gagnantCote = $match->gagnant_id == $match->joueur1_id ?
                    $match->cote_joueur1 : $match->cote_joueur2;

                return $gagnantCote > $coteFavorite && $gagnantCote >= 2.5;
            })
            ->count();
    }

    /**
     * Obtenir le top joueurs par classement participants
     */
    public function getTopJoueursParticipants($limite = 10)
    {
        return $this->joueurs()
            ->whereNotNull('classement_atp_wta')
            ->orderBy('classement_atp_wta')
            ->limit($limite)
            ->get();
    }

    /**
     * Vérifier si le tournoi est complet
     */
    public function estComplet()
    {
        $matchsAttendus = $this->calculerNombreMatchsAttendus();
        $matchsRealises = $this->matchs()->count();

        return $matchsRealises >= $matchsAttendus;
    }

    /**
     * Calculer le nombre théorique de matchs
     */
    private function calculerNombreMatchsAttendus()
    {
        $nbJoueurs = $this->nb_joueurs_tableau_principal;

        if (!$nbJoueurs) return 0;

        // Formule: n-1 matchs pour éliminer n-1 joueurs
        return $nbJoueurs - 1;
    }

    /**
     * Obtenir le prochain match important
     */
    public function getProchainMatchImportant()
    {
        return $this->matchs()
            ->whereHas('statut', function($q) {
                $q->where('code', 'programme');
            })
            ->whereHas('round', function($q) {
                $q->whereIn('code', ['finale', 'demi_finale', 'quart_finale']);
            })
            ->orderBy('date_match')
            ->first();
    }

    // ===================================================================
    // VALIDATION RULES
    // ===================================================================

    public static function validationRules()
    {
        return [
            'nom' => 'required|string|max:200',
            'categorie_tournoi_id' => 'required|exists:categorie_tournois,id',
            'pays_id' => 'required|exists:pays,id',
            'surface_id' => 'required|exists:surfaces,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'nb_joueurs_tableau_principal' => 'required|integer|min:8|max:256',
            'prize_money_total' => 'nullable|numeric|min:0',
            'statut' => 'required|in:programme,en_cours,termine,annule'
        ];
    }

    // ===================================================================
    // BOOT METHODS
    // ===================================================================

    protected static function boot()
    {
        parent::boot();

        // Auto-update du statut basé sur les dates
        static::saving(function ($tournoi) {
            if ($tournoi->date_debut && $tournoi->date_fin) {
                $now = now();

                if ($now < $tournoi->date_debut) {
                    $tournoi->statut = 'programme';
                } elseif ($now->between($tournoi->date_debut, $tournoi->date_fin)) {
                    if ($tournoi->statut !== 'termine') {
                        $tournoi->statut = 'en_cours';
                    }
                } elseif ($now > $tournoi->date_fin) {
                    $tournoi->statut = 'termine';
                }
            }
        });
    }
}
