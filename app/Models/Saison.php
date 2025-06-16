<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Saison extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'saisons';

    protected $fillable = [
        // Informations de base
        'annee',
        'nom',                    // "Saison ATP/WTA 2024"
        'nom_court',              // "2024"
        'date_debut',
        'date_fin',
        'est_active',

        // Informations tennis spécifiques
        'circuit',                // 'atp', 'wta', 'mixte'
        'nb_semaines_calendrier', // 52 semaines typiquement
        'nb_tournois_prevu',      // Nombre de tournois prévus
        'prize_money_total',      // Prize money total de la saison

        // Phases de saison tennis
        'debut_saison_terre',     // Début saison terre battue (avril)
        'debut_saison_gazon',     // Début saison gazon (juin)
        'debut_saison_dur_fin',   // Retour sur dur (août)

        // Événements majeurs
        'dates_grand_chelem',     // JSON des dates des 4 GS
        'dates_masters_cup',      // Date du Masters/WTA Finals
        'date_fin_classement',    // Date de fin pour classement annuel

        // Métadonnées
        'statut',                 // 'planifiee', 'en_cours', 'terminee'
        'notes',
        'version',                // Version du calendrier (parfois révisé)
        'source_donnees_id',
        'derniere_maj',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'debut_saison_terre' => 'date',
        'debut_saison_gazon' => 'date',
        'debut_saison_dur_fin' => 'date',
        'date_fin_classement' => 'date',
        'dates_grand_chelem' => 'json',
        'dates_masters_cup' => 'json',
        'est_active' => 'boolean',
        'nb_semaines_calendrier' => 'integer',
        'nb_tournois_prevu' => 'integer',
        'prize_money_total' => 'decimal:2',
        'derniere_maj' => 'datetime',
        'version' => 'float',
    ];

    protected $appends = [
        'est_en_cours',
        'est_terminee',
        'phase_actuelle',
        'surface_dominante_actuelle',
        'semaine_calendrier_actuelle',
        'pourcentage_completion',
        'prochains_evenements_majeurs',
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function statistiquesJoueurs()
    {
        return $this->hasMany(StatistiqueJoueur::class);
    }

    public function tournois()
    {
        return $this->hasMany(Tournoi::class)->orderBy('date_debut');
    }

    public function tournoiActifs()
    {
        return $this->hasMany(Tournoi::class)
            ->where('statut', '!=', 'annule');
    }

    public function matchs()
    {
        return $this->hasManyThrough(MatchTennis::class, Tournoi::class);
    }

    public function joueurs()
    {
        return $this->belongsToMany(Joueur::class, 'statistique_joueurs')
            ->withPivot(['classement_final', 'points_total', 'victoires', 'defaites'])
            ->distinct();
    }

    public function sourceDonnees()
    {
        return $this->belongsTo(SourceDonnees::class, 'source_donnees_id');
    }

    // Relations spécialisées
    public function grandsChelem()
    {
        return $this->tournois()
            ->whereHas('categorie', function ($q) {
                $q->where('code', 'grand_chelem');
            })
            ->orderBy('date_debut');
    }

    public function masters1000()
    {
        return $this->tournois()
            ->whereHas('categorie', function ($q) {
                $q->where('code', 'masters_1000');
            });
    }

    public function tournoiParSurface($surface)
    {
        return $this->tournois()
            ->whereHas('surface', function ($q) use ($surface) {
                $q->where('code', $surface);
            });
    }

    // ===================================================================
    // ACCESSORS
    // ===================================================================

    public function getEstEnCoursAttribute()
    {
        $maintenant = now();

        return $this->date_debut <= $maintenant &&
            $maintenant <= $this->date_fin &&
            $this->statut === 'en_cours';
    }

    public function getEstTermineeAttribute()
    {
        return $this->statut === 'terminee' || now() > $this->date_fin;
    }

    public function getPhaseActuelleAttribute()
    {
        if (! $this->est_en_cours) {
            return $this->est_terminee ? 'Terminée' : 'À venir';
        }

        $maintenant = now();

        // Déterminer la phase basée sur les dates
        if ($this->debut_saison_gazon && $maintenant >= $this->debut_saison_gazon && $maintenant < $this->debut_saison_dur_fin) {
            return 'Saison gazon';
        } elseif ($this->debut_saison_terre && $maintenant >= $this->debut_saison_terre && $maintenant < $this->debut_saison_gazon) {
            return 'Saison terre battue';
        } elseif ($this->debut_saison_dur_fin && $maintenant >= $this->debut_saison_dur_fin) {
            return 'Fin de saison (dur)';
        } else {
            return 'Début de saison (dur)';
        }
    }

    public function getSurfaceDominanteActuelleAttribute()
    {
        $phase = $this->phase_actuelle;

        $surfaces = [
            'Saison gazon' => 'grass',
            'Saison terre battue' => 'clay',
            'Fin de saison (dur)' => 'hard',
            'Début de saison (dur)' => 'hard',
        ];

        return $surfaces[$phase] ?? 'hard';
    }

    public function getSemaineCalendrierActuelleAttribute()
    {
        if (! $this->est_en_cours) {
            return null;
        }

        return $this->date_debut->diffInWeeks(now()) + 1;
    }

    public function getPourcentageCompletionAttribute()
    {
        if ($this->statut === 'planifiee') {
            return 0;
        }
        if ($this->statut === 'terminee') {
            return 100;
        }

        $totalJours = $this->date_debut->diffInDays($this->date_fin);
        $joursEcoules = $this->date_debut->diffInDays(now());

        return min(100, round(($joursEcoules / $totalJours) * 100, 1));
    }

    public function getProchainEvenementsMajeursAttribute()
    {
        if (! $this->est_en_cours) {
            return [];
        }

        $evenements = [];
        $maintenant = now();

        // Prochains Grand Chelem
        $prochainGS = $this->grandsChelem()
            ->where('date_debut', '>', $maintenant)
            ->first();

        if ($prochainGS) {
            $evenements[] = [
                'type' => 'Grand Chelem',
                'nom' => $prochainGS->nom,
                'date' => $prochainGS->date_debut,
                'jours_restants' => $maintenant->diffInDays($prochainGS->date_debut),
            ];
        }

        return collect($evenements)->sortBy('date')->take(3)->values()->all();
    }

    public function getDureeAttribute()
    {
        if (! $this->date_debut || ! $this->date_fin) {
            return null;
        }

        return $this->date_debut->diffInDays($this->date_fin) + 1;
    }

    public function getNomCompletAttribute()
    {
        $circuit = strtoupper($this->circuit ?? 'ATP/WTA');

        return "Saison {$circuit} {$this->annee}";
    }

    // ===================================================================
    // SCOPES
    // ===================================================================

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
        return $query->where('annee', '>=', now()->year - $nbAnnees)
            ->orderBy('annee', 'desc');
    }

    public function scopeTerminees(Builder $query): Builder
    {
        return $query->where('statut', 'terminee');
    }

    public function scopeEnCours(Builder $query): Builder
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopePlanifiees(Builder $query): Builder
    {
        return $query->where('statut', 'planifiee');
    }

    public function scopeParCircuit(Builder $query, string $circuit): Builder
    {
        return $query->where('circuit', $circuit);
    }

    public function scopeAtp(Builder $query): Builder
    {
        return $query->where('circuit', 'atp');
    }

    public function scopeWta(Builder $query): Builder
    {
        return $query->where('circuit', 'wta');
    }

    public function scopeAvecTournois(Builder $query): Builder
    {
        return $query->has('tournois');
    }

    public function scopeRecherche(Builder $query, string $terme): Builder
    {
        return $query->where(function ($q) use ($terme) {
            $q->where('nom', 'LIKE', "%{$terme}%")
                ->orWhere('annee', 'LIKE', "%{$terme}%")
                ->orWhere('notes', 'LIKE', "%{$terme}%");
        });
    }

    // ===================================================================
    // STATIC METHODS
    // ===================================================================

    /**
     * Retourne la saison actuelle ou la crée
     */
    public static function actuelle(): self
    {
        return static::firstOrCreate(
            ['annee' => now()->year],
            [
                'nom' => 'Saison ATP/WTA '.now()->year,
                'nom_court' => (string) now()->year,
                'date_debut' => Carbon::create(now()->year, 1, 1),
                'date_fin' => Carbon::create(now()->year, 11, 30),
                'est_active' => true,
                'statut' => 'en_cours',
                'circuit' => 'mixte',
                'nb_semaines_calendrier' => 52,
            ]
        );
    }

    /**
     * Créer une nouvelle saison avec les phases tennis standard
     */
    public static function creerSaisonStandard(int $annee, string $circuit = 'mixte'): self
    {
        return static::create([
            'annee' => $annee,
            'nom' => 'Saison '.strtoupper($circuit)." {$annee}",
            'nom_court' => (string) $annee,
            'circuit' => $circuit,
            'date_debut' => Carbon::create($annee, 1, 1),
            'date_fin' => Carbon::create($annee, 11, 30),

            // Phases tennis standard
            'debut_saison_terre' => Carbon::create($annee, 4, 1),
            'debut_saison_gazon' => Carbon::create($annee, 6, 1),
            'debut_saison_dur_fin' => Carbon::create($annee, 8, 1),
            'date_fin_classement' => Carbon::create($annee, 11, 15),

            'nb_semaines_calendaire' => 52,
            'statut' => $annee == now()->year ? 'en_cours' :
                ($annee > now()->year ? 'planifiee' : 'terminee'),
            'est_active' => true,
        ]);
    }

    // ===================================================================
    // METHODS
    // ===================================================================

    /**
     * Retourne les statistiques complètes de la saison
     */
    public function getStatistiquesCompletes(): array
    {
        $baseStats = $this->getStatsDeBase();

        return array_merge($baseStats, [
            'nb_grand_chelem' => $this->grandsChelem()->count(),
            'nb_masters_1000' => $this->masters1000()->count(),
            'prize_money_distribue' => $this->tournois()->sum('prize_money_total'),
            'nb_matchs_par_surface' => $this->getMatchsParSurface(),
            'top_joueurs_points' => $this->getTopJoueursPoints(10),
            'tournoi_le_plus_dote' => $this->getTournoiLePlusDote(),
            'duree_moyenne_tournoi' => $this->getDureeMoyenneTournoi(),
            'nb_upsets_majeurs' => $this->getNombreUpsetsMajeurs(),
        ]);
    }

    /**
     * Retourne les statistiques de base de la saison
     */
    public function getStatsDeBase(): array
    {
        return [
            'nb_tournois' => $this->tournois()->count(),
            'nb_matchs' => $this->matchs()->count(),
            'nb_joueurs' => $this->statistiquesJoueurs()->distinct('joueur_id')->count(),
            'tournois_termines' => $this->tournois()->where('statut', 'termine')->count(),
        ];
    }

    /**
     * Vérifie si la saison est en cours
     */
    public function estEnCours(): bool
    {
        return $this->est_en_cours;
    }

    /**
     * Retourne les tournois Grand Slam de la saison
     */
    public function getGrandSlams()
    {
        return $this->grandsChelem()->get();
    }

    /**
     * Obtenir la répartition des matchs par surface
     */
    public function getMatchsParSurface(): array
    {
        return $this->matchs()
            ->join('tournois', 'matchs_tennis.tournoi_id', '=', 'tournois.id')
            ->join('surfaces', 'tournois.surface_id', '=', 'surfaces.id')
            ->groupBy('surfaces.code', 'surfaces.nom')
            ->selectRaw('surfaces.code, surfaces.nom, COUNT(*) as nb_matchs')
            ->pluck('nb_matchs', 'nom')
            ->toArray();
    }

    /**
     * Top joueurs par points de la saison
     */
    public function getTopJoueursPoints(int $limite = 10): array
    {
        return $this->statistiquesJoueurs()
            ->with('joueur')
            ->orderBy('points_total', 'desc')
            ->limit($limite)
            ->get()
            ->map(function ($stat) {
                return [
                    'joueur' => $stat->joueur->nom_complet,
                    'points' => $stat->points_total,
                    'classement' => $stat->classement_final,
                    'victoires' => $stat->victoires,
                    'defaites' => $stat->defaites,
                ];
            })
            ->toArray();
    }

    /**
     * Tournoi avec le plus gros prize money
     */
    public function getTournoiLePlusDote()
    {
        return $this->tournois()
            ->orderBy('prize_money_total', 'desc')
            ->first();
    }

    /**
     * Durée moyenne des tournois
     */
    public function getDureeMoyenneTournoi(): float
    {
        return $this->tournois()
            ->whereNotNull('date_debut')
            ->whereNotNull('date_fin')
            ->get()
            ->avg(function ($tournoi) {
                return $tournoi->duree;
            }) ?? 0;
    }

    /**
     * Nombre d'upsets majeurs dans la saison
     */
    public function getNombreUpsetsMajeurs(): int
    {
        return $this->matchs()
            ->whereHas('tournoi.categorie', function ($q) {
                $q->whereIn('code', ['grand_chelem', 'masters_1000']);
            })
            ->whereRaw('ABS(classement_joueur1 - classement_joueur2) >= 50')
            ->count();
    }

    /**
     * Obtenir le calendrier de la saison organisé par mois
     */
    public function getCalendrierParMois(): array
    {
        return $this->tournois()
            ->with(['categorie', 'surface', 'pays'])
            ->get()
            ->groupBy(function ($tournoi) {
                return $tournoi->date_debut->format('Y-m');
            })
            ->map(function ($tournois, $mois) {
                return [
                    'mois' => Carbon::createFromFormat('Y-m', $mois)->translatedFormat('F Y'),
                    'nb_tournois' => $tournois->count(),
                    'tournois' => $tournois->map(function ($t) {
                        return [
                            'nom' => $t->nom,
                            'categorie' => $t->categorie->nom,
                            'surface' => $t->surface->nom,
                            'pays' => $t->pays->nom,
                            'date_debut' => $t->date_debut,
                            'date_fin' => $t->date_fin,
                        ];
                    }),
                ];
            })
            ->toArray();
    }

    /**
     * Vérifier la cohérence du calendrier
     */
    public function verifierCoherence(): array
    {
        $erreurs = [];

        // Vérifier les dates des phases
        if ($this->debut_saison_terre && $this->debut_saison_gazon) {
            if ($this->debut_saison_terre >= $this->debut_saison_gazon) {
                $erreurs[] = 'La saison terre battue doit commencer avant la saison gazon';
            }
        }

        // Vérifier les Grand Slems
        $grandSlams = $this->grandsChelem()->count();
        if ($grandSlams != 4 && $this->est_terminee) {
            $erreurs[] = "Nombre incorrect de Grand Slems: {$grandSlams} (attendu: 4)";
        }

        // Vérifier la cohérence des dates
        if ($this->date_debut >= $this->date_fin) {
            $erreurs[] = 'Date de début postérieure à la date de fin';
        }

        return $erreurs;
    }

    // ===================================================================
    // VALIDATION RULES
    // ===================================================================

    public static function validationRules(): array
    {
        return [
            'annee' => 'required|integer|min:1950|max:2050',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'circuit' => 'required|in:atp,wta,mixte',
            'statut' => 'required|in:planifiee,en_cours,terminee',
            'nb_tournois_prevu' => 'nullable|integer|min:0|max:200',
            'prize_money_total' => 'nullable|numeric|min:0',
        ];
    }

    // ===================================================================
    // BOOT METHODS
    // ===================================================================

    protected static function boot()
    {
        parent::boot();

        // Auto-update du statut basé sur les dates
        static::saving(function ($saison) {
            $maintenant = now();

            if ($maintenant < $saison->date_debut) {
                $saison->statut = 'planifiee';
            } elseif ($maintenant->between($saison->date_debut, $saison->date_fin)) {
                if ($saison->statut !== 'terminee') {
                    $saison->statut = 'en_cours';
                }
            } elseif ($maintenant > $saison->date_fin) {
                $saison->statut = 'terminee';
            }

            // Générer le nom si manquant
            if (! $saison->nom) {
                $circuit = strtoupper($saison->circuit ?? 'ATP/WTA');
                $saison->nom = "Saison {$circuit} {$saison->annee}";
            }

            if (! $saison->nom_court) {
                $saison->nom_court = (string) $saison->annee;
            }
        });

        // Désactiver les autres saisons actives du même circuit
        static::saved(function ($saison) {
            if ($saison->est_active) {
                self::where('id', '!=', $saison->id)
                    ->where('circuit', $saison->circuit)
                    ->where('est_active', true)
                    ->update(['est_active' => false]);
            }
        });
    }
}
