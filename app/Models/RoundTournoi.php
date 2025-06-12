<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoundTournoi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'round_tournois';

    protected $fillable = [
        // Informations de base
        'nom',
        'nom_court',              // "1R", "QF", "F"
        'nom_anglais',            // "First Round", "Quarterfinals", "Final"
        'code',                   // 'premier_tour', 'finale', etc.
        'description',

        // Position dans le tournoi
        'ordre',                  // 1, 2, 3... pour l'ordre chronologique
        'niveau',                 // 1=Qualifs, 2=Tableau principal, 3=Phases finales
        'position_inverse',       // Position depuis la fin (finale=1, demi=2, etc.)

        // Configuration du round
        'nb_joueurs_entrants',    // Joueurs qui commencent ce round
        'nb_joueurs_sortants',    // Joueurs qui passent au round suivant
        'nb_matchs_attendus',     // Nombre de matchs pour ce round
        'est_elimination',        // true/false (vs round robin)
        'format_match',           // 'best_of_3', 'best_of_5'

        // Types de rounds
        'type',                   // 'qualification', 'principal', 'finale'
        'est_round_principal',    // Fait partie du tableau principal
        'est_phase_finale',       // Quart, demi, finale
        'est_finale',             // Round final du tournoi
        'est_qualification',      // Round de qualification

        // Points et récompenses
        'points_atp_defaut',      // Points ATP par défaut pour ce round
        'points_wta_defaut',      // Points WTA par défaut pour ce round
        'pourcentage_prize_money', // % du prize money pour ce round

        // Affichage et métadonnées
        'couleur_hex',            // Couleur pour l'affichage
        'icone',                  // Icône représentative
        'ordre_affichage',        // Ordre dans les listes
        'est_visible',            // Visible dans l'interface
        'abreviation',            // "F", "SF", "QF", "R16", etc.

        // Règles spéciales
        'permet_bye',             // Autorise les byes
        'permet_walkover',        // Autorise les walkovers
        'duree_prevue_jours',     // Durée prévue en jours

        // Métadonnées
        'notes',
        'actif'
    ];

    protected $casts = [
        'ordre' => 'integer',
        'niveau' => 'integer',
        'position_inverse' => 'integer',
        'nb_joueurs_entrants' => 'integer',
        'nb_joueurs_sortants' => 'integer',
        'nb_matchs_attendus' => 'integer',
        'points_atp_defaut' => 'integer',
        'points_wta_defaut' => 'integer',
        'pourcentage_prize_money' => 'decimal:3',
        'ordre_affichage' => 'integer',
        'duree_prevue_jours' => 'integer',
        'est_elimination' => 'boolean',
        'est_round_principal' => 'boolean',
        'est_phase_finale' => 'boolean',
        'est_finale' => 'boolean',
        'est_qualification' => 'boolean',
        'permet_bye' => 'boolean',
        'permet_walkover' => 'boolean',
        'est_visible' => 'boolean',
        'actif' => 'boolean'
    ];

    protected $appends = [
        'nom_complet',
        'importance_level',
        'taux_elimination',
        'est_round_decisif',
        'difficulte_relative'
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function matchs()
    {
        return $this->hasMany(MatchTennis::class, 'round_tournoi_id')
            ->orderBy('date_match');
    }

    public function matchsTermines()
    {
        return $this->hasMany(MatchTennis::class, 'round_tournoi_id')
            ->whereHas('statut', function($q) {
                $q->where('code', 'termine');
            });
    }

    public function matchsProgrammes()
    {
        return $this->hasMany(MatchTennis::class, 'round_tournoi_id')
            ->whereHas('statut', function($q) {
                $q->where('code', 'programme');
            });
    }

    public function matchsEnCours()
    {
        return $this->hasMany(MatchTennis::class, 'round_tournoi_id')
            ->whereHas('statut', function($q) {
                $q->where('code', 'en_cours');
            });
    }

    // Relations hiérarchiques
    public function roundPrecedent()
    {
        return $this->hasOne(RoundTournoi::class, 'ordre', 'ordre')
            ->where('ordre', $this->ordre - 1);
    }

    public function roundSuivant()
    {
        return $this->hasOne(RoundTournoi::class, 'ordre', 'ordre')
            ->where('ordre', $this->ordre + 1);
    }

    // ===================================================================
    // ACCESSORS
    // ===================================================================

    public function getNomCompletAttribute()
    {
        $suffixes = [];

        if ($this->est_finale) {
            $suffixes[] = '🏆';
        } elseif ($this->est_phase_finale) {
            $suffixes[] = '⭐';
        }

        if ($this->est_qualification) {
            $suffixes[] = '(Q)';
        }

        return $this->nom . (empty($suffixes) ? '' : ' ' . implode(' ', $suffixes));
    }

    public function getImportanceLevelAttribute()
    {
        if ($this->est_finale) return 'Critique';
        if ($this->position_inverse <= 2) return 'Très élevée'; // Finale, demi
        if ($this->position_inverse <= 4) return 'Élevée';       // Quart
        if ($this->est_phase_finale) return 'Importante';
        if ($this->est_round_principal) return 'Modérée';
        return 'Standard';
    }

    public function getTauxEliminationAttribute()
    {
        if (!$this->nb_joueurs_entrants || !$this->est_elimination) return 0;

        $elimines = $this->nb_joueurs_entrants - $this->nb_joueurs_sortants;
        return round(($elimines / $this->nb_joueurs_entrants) * 100, 1);
    }

    public function getEstRoundDecisifAttribute()
    {
        return $this->est_finale || $this->position_inverse <= 3;
    }

    public function getDifficulteRelativeAttribute()
    {
        // Score de difficulté basé sur plusieurs facteurs
        $score = 0;

        // Position dans le tournoi (plus on avance, plus c'est dur)
        $score += ($this->ordre ?? 0) * 10;

        // Phases finales sont plus difficiles
        if ($this->est_phase_finale) $score += 30;
        if ($this->est_finale) $score += 50;

        // Niveau de round
        $score += ($this->niveau ?? 1) * 5;

        return min(100, $score);
    }

    public function getAbreviationStandardAttribute()
    {
        // Abréviations tennis standard
        $abreviations = [
            'finale' => 'F',
            'demi_finale' => 'SF',
            'quart_finale' => 'QF',
            'huitieme' => 'R16',
            'troisieme_tour' => 'R32',
            'deuxieme_tour' => 'R64',
            'premier_tour' => 'R128',
            'qualification_finale' => 'Q3',
            'qualification_deuxieme' => 'Q2',
            'qualification_premier' => 'Q1'
        ];

        return $abreviations[$this->code] ?? $this->abreviation ?? strtoupper(substr($this->nom, 0, 2));
    }

    // ===================================================================
    // SCOPES
    // ===================================================================

    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    public function scopeVisibles($query)
    {
        return $query->where('est_visible', true);
    }

    public function scopeTableauPrincipal($query)
    {
        return $query->where('est_round_principal', true);
    }

    public function scopeQualifications($query)
    {
        return $query->where('est_qualification', true);
    }

    public function scopePhasesFinales($query)
    {
        return $query->where('est_phase_finale', true);
    }

    public function scopeFinales($query)
    {
        return $query->where('est_finale', true);
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeParNiveau($query, $niveau)
    {
        return $query->where('niveau', $niveau);
    }

    public function scopeOrdonnes($query)
    {
        return $query->orderBy('ordre')
            ->orderBy('ordre_affichage')
            ->orderBy('nom');
    }

    public function scopeOrdonnésInverse($query)
    {
        return $query->orderBy('position_inverse')
            ->orderBy('nom');
    }

    public function scopeAvecMatchs($query)
    {
        return $query->has('matchs');
    }

    public function scopeRecherche($query, $terme)
    {
        return $query->where(function($q) use ($terme) {
            $q->where('nom', 'LIKE', "%{$terme}%")
                ->orWhere('nom_court', 'LIKE', "%{$terme}%")
                ->orWhere('code', 'LIKE', "%{$terme}%")
                ->orWhere('abreviation', 'LIKE', "%{$terme}%");
        });
    }

    // ===================================================================
    // STATIC METHODS
    // ===================================================================

    /**
     * Créer la structure complète d'un tournoi standard
     */
    public static function creerStructureTournoi($nbJoueurs = 128)
    {
        $rounds = [];
        $ordre = 1;

        // Calculer les rounds nécessaires
        $nbRounds = log($nbJoueurs, 2);

        for ($i = $nbRounds; $i >= 1; $i--) {
            $joueursCeRound = pow(2, $i);
            $joueursRoundSuivant = $joueursCeRound / 2;

            $roundData = self::getConfigurationRound($i, $joueursCeRound, $joueursRoundSuivant, $ordre);
            $rounds[] = self::create($roundData);
            $ordre++;
        }

        return collect($rounds);
    }

    /**
     * Configuration d'un round selon le niveau
     */
    private static function getConfigurationRound($niveau, $entrants, $sortants, $ordre)
    {
        $configurations = [
            7 => [ // 128 joueurs -> Premier tour
                'nom' => 'Premier tour',
                'code' => 'premier_tour',
                'abreviation' => 'R128'
            ],
            6 => [ // 64 joueurs -> Deuxième tour
                'nom' => 'Deuxième tour',
                'code' => 'deuxieme_tour',
                'abreviation' => 'R64'
            ],
            5 => [ // 32 joueurs -> Troisième tour
                'nom' => 'Troisième tour',
                'code' => 'troisieme_tour',
                'abreviation' => 'R32'
            ],
            4 => [ // 16 joueurs -> Huitièmes
                'nom' => 'Huitièmes de finale',
                'code' => 'huitieme',
                'abreviation' => 'R16'
            ],
            3 => [ // 8 joueurs -> Quarts
                'nom' => 'Quarts de finale',
                'code' => 'quart_finale',
                'abreviation' => 'QF'
            ],
            2 => [ // 4 joueurs -> Demis
                'nom' => 'Demi-finales',
                'code' => 'demi_finale',
                'abreviation' => 'SF'
            ],
            1 => [ // 2 joueurs -> Finale
                'nom' => 'Finale',
                'code' => 'finale',
                'abreviation' => 'F'
            ]
        ];

        $config = $configurations[$niveau] ?? [
            'nom' => "Round {$ordre}",
            'code' => "round_{$ordre}",
            'abreviation' => "R{$entrants}"
        ];

        return array_merge($config, [
            'ordre' => $ordre,
            'niveau' => $niveau >= 4 ? 3 : 2, // 3=phases finales, 2=principal
            'position_inverse' => 8 - $niveau, // Position depuis la fin
            'nb_joueurs_entrants' => $entrants,
            'nb_joueurs_sortants' => $sortants,
            'nb_matchs_attendus' => $entrants / 2,
            'est_elimination' => true,
            'est_round_principal' => true,
            'est_phase_finale' => $niveau <= 3,
            'est_finale' => $niveau === 1,
            'format_match' => $niveau === 1 ? 'best_of_5' : 'best_of_3',
            'permet_bye' => $niveau >= 6,
            'est_visible' => true,
            'actif' => true
        ]);
    }

    /**
     * Obtenir la hiérarchie complète des rounds
     */
    public static function getHierarchie()
    {
        return self::actifs()
            ->ordonnes()
            ->get()
            ->mapWithKeys(function($round) {
                return [$round->code => [
                    'nom' => $round->nom,
                    'ordre' => $round->ordre,
                    'importance' => $round->importance_level,
                    'abreviation' => $round->abreviation_standard
                ]];
            });
    }

    // ===================================================================
    // METHODS
    // ===================================================================

    /**
     * Calculer les points ATP/WTA pour ce round dans un tournoi donné
     */
    public function getPointsPourTournoi(Tournoi $tournoi)
    {
        $pointsBase = $tournoi->points_atp_wta_gagnant;

        if (!$pointsBase) return 0;

        // Distribution des points selon le round
        $distributions = [
            'finale' => 1.0,
            'demi_finale' => 0.6,
            'quart_finale' => 0.36,
            'huitieme' => 0.18,
            'troisieme_tour' => 0.09,
            'deuxieme_tour' => 0.045,
            'premier_tour' => 0.01
        ];

        $ratio = $distributions[$this->code] ?? $this->pourcentage_prize_money ?? 0.01;

        return (int)($pointsBase * $ratio);
    }

    /**
     * Calculer le prize money pour ce round dans un tournoi donné
     */
    public function getPrizeMoneyPourTournoi(Tournoi $tournoi)
    {
        if (!$tournoi->prize_money_total || !$this->pourcentage_prize_money) {
            return 0;
        }

        return $tournoi->prize_money_total * $this->pourcentage_prize_money;
    }

    /**
     * Vérifier si le round est complet (tous les matchs joués)
     */
    public function estComplet()
    {
        $matchsAttendus = $this->nb_matchs_attendus;
        $matchsTermines = $this->matchsTermines()->count();

        return $matchsAttendus > 0 && $matchsTermines >= $matchsAttendus;
    }

    /**
     * Obtenir le pourcentage de completion du round
     */
    public function getPourcentageCompletion()
    {
        if (!$this->nb_matchs_attendus) return 0;

        $matchsTermines = $this->matchsTermines()->count();
        return min(100, round(($matchsTermines / $this->nb_matchs_attendus) * 100, 1));
    }

    /**
     * Obtenir les statistiques du round
     */
    public function getStatistiques()
    {
        return [
            'nb_matchs_total' => $this->matchs()->count(),
            'nb_matchs_termines' => $this->matchsTermines()->count(),
            'nb_matchs_programmes' => $this->matchsProgrammes()->count(),
            'pourcentage_completion' => $this->getPourcentageCompletion(),
            'duree_moyenne_match' => $this->matchsTermines()
                ->whereNotNull('duree_match')
                ->avg('duree_match'),
            'nb_upsets' => $this->getNombreUpsets(),
            'taux_completion' => $this->estComplet() ? 100 : $this->getPourcentageCompletion()
        ];
    }

    /**
     * Compter les upsets dans ce round
     */
    public function getNombreUpsets()
    {
        return $this->matchsTermines()
            ->whereNotNull('classement_joueur1')
            ->whereNotNull('classement_joueur2')
            ->get()
            ->filter(function($match) {
                // Upset si le moins bien classé a gagné
                $classementGagnant = $match->gagnant_id == $match->joueur1_id ?
                    $match->classement_joueur1 : $match->classement_joueur2;
                $classementPerdant = $match->gagnant_id == $match->joueur1_id ?
                    $match->classement_joueur2 : $match->classement_joueur1;

                return $classementGagnant > $classementPerdant + 20; // Différence de 20 places minimum
            })
            ->count();
    }

    /**
     * Obtenir la liste des qualifiés pour le round suivant
     */
    public function getQualifies()
    {
        return $this->matchsTermines()
            ->with('gagnant')
            ->get()
            ->pluck('gagnant')
            ->filter();
    }

    /**
     * Vérifier la cohérence du round
     */
    public function verifierCoherence()
    {
        $erreurs = [];

        // Vérifier le nombre de matchs
        if ($this->est_elimination && $this->nb_joueurs_entrants && $this->nb_matchs_attendus) {
            $matchsCalcules = $this->nb_joueurs_entrants / 2;
            if ($this->nb_matchs_attendus != $matchsCalcules) {
                $erreurs[] = "Nombre de matchs incohérent: {$this->nb_matchs_attendus} vs {$matchsCalcules} calculé";
            }
        }

        // Vérifier la progression des joueurs
        if ($this->nb_joueurs_entrants && $this->nb_joueurs_sortants) {
            if ($this->nb_joueurs_sortants > $this->nb_joueurs_entrants) {
                $erreurs[] = "Plus de sortants que d'entrants";
            }
        }

        return $erreurs;
    }

    // ===================================================================
    // VALIDATION RULES
    // ===================================================================

    public static function validationRules()
    {
        return [
            'nom' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:round_tournois,code',
            'ordre' => 'required|integer|min:1',
            'nb_joueurs_entrants' => 'nullable|integer|min:2',
            'nb_joueurs_sortants' => 'nullable|integer|min:1',
            'type' => 'required|in:qualification,principal,finale',
            'format_match' => 'required|in:best_of_3,best_of_5',
            'pourcentage_prize_money' => 'nullable|numeric|min:0|max:1'
        ];
    }

    // ===================================================================
    // BOOT METHODS
    // ===================================================================

    protected static function boot()
    {
        parent::boot();

        // Auto-génération des valeurs calculées
        static::saving(function ($round) {
            // Générer l'ordre d'affichage si manquant
            if (!$round->ordre_affichage) {
                $round->ordre_affichage = $round->ordre ?? 1;
            }

            // Auto-calcul des booléens selon le code
            if ($round->code) {
                $round->est_finale = $round->code === 'finale';
                $round->est_phase_finale = in_array($round->code, [
                    'finale', 'demi_finale', 'quart_finale'
                ]);
                $round->est_qualification = str_contains($round->code, 'qualification');
            }

            // Valeurs par défaut
            if ($round->actif === null) $round->actif = true;
            if ($round->est_visible === null) $round->est_visible = true;
            if ($round->est_elimination === null) $round->est_elimination = true;
        });
    }
}
