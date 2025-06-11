<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class MatchTennis extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'matchs_tennis';

    protected $fillable = [
        // Joueurs et contexte
        'joueur1_id',
        'joueur2_id',
        'tournoi_id',
        'surface_id',
        'round_tournoi_id',
        'statut_match_id',

        // Date et horaire
        'date_match',
        'heure_match',
        'duree_match', // en minutes

        // Conditions de jeu
        'condition_meteo_id',
        'temperature',
        'humidite',
        'vitesse_vent',
        'indoor_outdoor',

        // Résultats
        'score_final',
        'score_detaille', // JSON avec sets/jeux
        'gagnant_id',
        'raison_fin', // Normal, abandon, walkover, disqualification

        // Données pour IA
        'cote_joueur1',
        'cote_joueur2',
        'prediction_pre_match',
        'confidence_level',

        // Métadonnées
        'source_donnees_id',
        'import_id',
        'notes',
        'diffuse_tv'
    ];

    protected $casts = [
        'date_match' => 'datetime',
        'heure_match' => 'datetime',
        'duree_match' => 'integer',
        'temperature' => 'integer',
        'humidite' => 'integer',
        'vitesse_vent' => 'integer',
        'cote_joueur1' => 'decimal:2',
        'cote_joueur2' => 'decimal:2',
        'prediction_pre_match' => 'decimal:2',
        'confidence_level' => 'decimal:2',
        'score_detaille' => 'array',
        'diffuse_tv' => 'boolean'
    ];

    protected $appends = [
        'nom_match',
        'duree_formatee',
        'est_termine',
        'probabilite_joueur1',
        'probabilite_joueur2'
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function joueur1()
    {
        return $this->belongsTo(Joueur::class, 'joueur1_id');
    }

    public function joueur2()
    {
        return $this->belongsTo(Joueur::class, 'joueur2_id');
    }

    public function gagnant()
    {
        return $this->belongsTo(Joueur::class, 'gagnant_id');
    }

    public function perdant()
    {
        $perdantId = $this->gagnant_id == $this->joueur1_id ?
            $this->joueur2_id : $this->joueur1_id;
        return $this->belongsTo(Joueur::class, 'perdant_id')->where('id', $perdantId);
    }

    public function tournoi()
    {
        return $this->belongsTo(Tournoi::class);
    }

    public function surface()
    {
        return $this->belongsTo(Surface::class);
    }

    public function round()
    {
        return $this->belongsTo(RoundTournoi::class, 'round_tournoi_id');
    }

    public function statut()
    {
        return $this->belongsTo(StatutMatch::class, 'statut_match_id');
    }

    public function conditionMeteo()
    {
        return $this->belongsTo(ConditionMeteo::class, 'condition_meteo_id');
    }

    public function sourceDonnees()
    {
        return $this->belongsTo(SourceDonnees::class, 'source_donnees_id');
    }

    // Relations avec prédictions et statistiques
    public function predictions()
    {
        return $this->hasMany(Prediction::class, 'match_tennis_id');
    }

    public function predictionPrincipale()
    {
        return $this->hasOne(Prediction::class, 'match_tennis_id')
            ->where('type_prediction_id', 1); // Type "Gagnant"
    }

    public function statistiques()
    {
        return $this->hasMany(StatistiqueMatch::class, 'match_tennis_id');
    }

    public function confrontation()
    {
        return $this->hasOne(Confrontation::class, function($query) {
            $query->where(function($q) {
                $q->where(['joueur1_id' => $this->joueur1_id, 'joueur2_id' => $this->joueur2_id])
                    ->orWhere(['joueur1_id' => $this->joueur2_id, 'joueur2_id' => $this->joueur1_id]);
            });
        });
    }

    // ===================================================================
    // ACCESSORS
    // ===================================================================

    public function getNomMatchAttribute()
    {
        if (!$this->joueur1 || !$this->joueur2) {
            return 'Match en cours de définition';
        }
        return $this->joueur1->nom_complet . ' vs ' . $this->joueur2->nom_complet;
    }

    public function getDureeFormateeAttribute()
    {
        if (!$this->duree_match) return null;

        $heures = floor($this->duree_match / 60);
        $minutes = $this->duree_match % 60;

        return $heures > 0 ?
            sprintf('%dh %02dmin', $heures, $minutes) :
            sprintf('%dmin', $minutes);
    }

    public function getEstTermineAttribute()
    {
        return $this->statut?->code === 'termine' || !empty($this->gagnant_id);
    }

    public function getProbabiliteJoueur1Attribute()
    {
        if ($this->cote_joueur1) {
            // Conversion cote décimale en probabilité
            return round((1 / $this->cote_joueur1) * 100, 2);
        }
        return $this->prediction_pre_match;
    }

    public function getProbabiliteJoueur2Attribute()
    {
        if ($this->cote_joueur2) {
            return round((1 / $this->cote_joueur2) * 100, 2);
        }
        return $this->prediction_pre_match ? 100 - $this->prediction_pre_match : null;
    }

    public function getScoreReadableAttribute()
    {
        if (!$this->score_detaille) {
            return $this->score_final;
        }

        $sets = $this->score_detaille;
        $scoreFormat = [];

        foreach ($sets as $set) {
            $scoreFormat[] = $set['joueur1'] . '-' . $set['joueur2'];
        }

        return implode(', ', $scoreFormat);
    }

    public function getSurfaceNomAttribute()
    {
        return $this->surface?->nom ?? 'Surface inconnue';
    }

    public function getRoundNomAttribute()
    {
        return $this->round?->nom ?? 'Tour inconnu';
    }

    public function getImportanceMatchAttribute()
    {
        // Calcul basé sur le tournoi et le round
        $importance = 1; // Base

        if ($this->tournoi) {
            switch ($this->tournoi->categorie?->code) {
                case 'grand_chelem': $importance *= 4; break;
                case 'masters_1000': $importance *= 3; break;
                case 'atp_500': $importance *= 2; break;
                case 'atp_250': $importance *= 1.5; break;
            }
        }

        if ($this->round) {
            switch ($this->round->code) {
                case 'finale': $importance *= 3; break;
                case 'demi_finale': $importance *= 2.5; break;
                case 'quart_finale': $importance *= 2; break;
                case 'huitieme': $importance *= 1.5; break;
            }
        }

        return round($importance, 1);
    }

    // ===================================================================
    // SCOPES
    // ===================================================================

    public function scopeTermines($query)
    {
        return $query->whereHas('statut', function($q) {
            $q->where('code', 'termine');
        })->orWhereNotNull('gagnant_id');
    }

    public function scopeEnCours($query)
    {
        return $query->whereHas('statut', function($q) {
            $q->where('code', 'en_cours');
        });
    }

    public function scopeProgrammes($query)
    {
        return $query->whereHas('statut', function($q) {
            $q->where('code', 'programme');
        });
    }

    public function scopeAujourdhui($query)
    {
        return $query->whereDate('date_match', today());
    }

    public function scopeParJoueur($query, $joueurId)
    {
        return $query->where('joueur1_id', $joueurId)
            ->orWhere('joueur2_id', $joueurId);
    }

    public function scopeParSurface($query, $surfaceCode)
    {
        return $query->whereHas('surface', function($q) use ($surfaceCode) {
            $q->where('code', $surfaceCode);
        });
    }

    public function scopeParTournoi($query, $tournoiId)
    {
        return $query->where('tournoi_id', $tournoiId);
    }

    public function scopeAvecPrediction($query)
    {
        return $query->whereHas('predictions');
    }

    public function scopeRecents($query, $jours = 7)
    {
        return $query->where('date_match', '>=', now()->subDays($jours));
    }

    public function scopeImportants($query)
    {
        return $query->whereHas('tournoi.categorie', function($q) {
            $q->whereIn('code', ['grand_chelem', 'masters_1000']);
        });
    }

    // ===================================================================
    // METHODS
    // ===================================================================

    /**
     * Déterminer le gagnant du match
     */
    public function determinerGagnant()
    {
        if (!$this->score_detaille || !is_array($this->score_detaille)) {
            return null;
        }

        $setsJoueur1 = 0;
        $setsJoueur2 = 0;

        foreach ($this->score_detaille as $set) {
            if ($set['joueur1'] > $set['joueur2']) {
                $setsJoueur1++;
            } else {
                $setsJoueur2++;
            }
        }

        // Match au meilleur des 3 ou 5 sets
        $setsRequis = $this->tournoi?->categorie?->code === 'grand_chelem' ? 3 : 2;

        if ($setsJoueur1 >= $setsRequis) {
            return $this->joueur1_id;
        } elseif ($setsJoueur2 >= $setsRequis) {
            return $this->joueur2_id;
        }

        return null; // Match pas terminé
    }

    /**
     * Calculer la durée du match en fonction du score
     */
    public function calculerDureeEstimee()
    {
        if (!$this->score_detaille) return null;

        $nbSets = count($this->score_detaille);
        $totalJeux = 0;

        foreach ($this->score_detaille as $set) {
            $totalJeux += $set['joueur1'] + $set['joueur2'];
        }

        // Estimation: 4 minutes par jeu + 2 minutes par set
        return ($totalJeux * 4) + ($nbSets * 2);
    }

    /**
     * Obtenir l'historique face-à-face
     */
    public function getHeadToHeadStats()
    {
        $matchsPrecedents = self::where(function($query) {
            $query->where(['joueur1_id' => $this->joueur1_id, 'joueur2_id' => $this->joueur2_id])
                ->orWhere(['joueur1_id' => $this->joueur2_id, 'joueur2_id' => $this->joueur1_id]);
        })
            ->where('id', '!=', $this->id)
            ->whereNotNull('gagnant_id')
            ->get();

        $victoires1 = $matchsPrecedents->where('gagnant_id', $this->joueur1_id)->count();
        $victoires2 = $matchsPrecedents->where('gagnant_id', $this->joueur2_id)->count();

        return [
            'total_matchs' => $matchsPrecedents->count(),
            'victoires_joueur1' => $victoires1,
            'victoires_joueur2' => $victoires2,
            'matchs_precedents' => $matchsPrecedents->take(5)
        ];
    }

    /**
     * Calculer les points ATP/WTA gagnés
     */
    public function getPointsGagnes($joueurId)
    {
        if (!$this->est_termine || $this->gagnant_id != $joueurId) {
            return 0;
        }

        $pointsBase = [
            'grand_chelem' => [
                'finale' => 2000, 'demi_finale' => 1200, 'quart_finale' => 720,
                'huitieme' => 360, 'troisieme_tour' => 180, 'deuxieme_tour' => 90,
                'premier_tour' => 10
            ],
            'masters_1000' => [
                'finale' => 1000, 'demi_finale' => 600, 'quart_finale' => 360,
                'huitieme' => 180, 'troisieme_tour' => 90, 'deuxieme_tour' => 45,
                'premier_tour' => 10
            ]
            // Ajouter autres catégories...
        ];

        $categorieCode = $this->tournoi?->categorie?->code;
        $roundCode = $this->round?->code;

        return $pointsBase[$categorieCode][$roundCode] ?? 0;
    }

    /**
     * Vérifier si un joueur était favori
     */
    public function etaitFavori($joueurId)
    {
        if ($joueurId == $this->joueur1_id && $this->cote_joueur1 && $this->cote_joueur2) {
            return $this->cote_joueur1 < $this->cote_joueur2;
        } elseif ($joueurId == $this->joueur2_id && $this->cote_joueur1 && $this->cote_joueur2) {
            return $this->cote_joueur2 < $this->cote_joueur1;
        }
        return null;
    }

    /**
     * Obtenir le facteur surprise du résultat
     */
    public function getFacteurSurprise()
    {
        if (!$this->est_termine || !$this->cote_joueur1 || !$this->cote_joueur2) {
            return 0;
        }

        $coteGagnant = $this->gagnant_id == $this->joueur1_id ?
            $this->cote_joueur1 : $this->cote_joueur2;

        // Plus la cote est élevée, plus c'est surprenant
        return $coteGagnant > 3.0 ? round(($coteGagnant - 1) * 10, 1) : 0;
    }

    // ===================================================================
    // VALIDATION RULES
    // ===================================================================

    public static function validationRules()
    {
        return [
            'joueur1_id' => 'required|exists:joueurs,id',
            'joueur2_id' => 'required|exists:joueurs,id|different:joueur1_id',
            'tournoi_id' => 'required|exists:tournois,id',
            'surface_id' => 'required|exists:surfaces,id',
            'date_match' => 'required|date',
            'round_tournoi_id' => 'required|exists:round_tournois,id',
            'statut_match_id' => 'required|exists:statut_matchs,id',
            'cote_joueur1' => 'nullable|numeric|min:1.01',
            'cote_joueur2' => 'nullable|numeric|min:1.01',
            'temperature' => 'nullable|integer|between:-10,50',
            'humidite' => 'nullable|integer|between:0,100'
        ];
    }

    // ===================================================================
    // BOOT METHODS
    // ===================================================================

    protected static function boot()
    {
        parent::boot();

        // Auto-déterminer le gagnant lors de la sauvegarde
        static::saving(function ($match) {
            if ($match->score_detaille && !$match->gagnant_id) {
                $match->gagnant_id = $match->determinerGagnant();
            }

            // Auto-calculer la durée si pas définie
            if ($match->score_detaille && !$match->duree_match) {
                $match->duree_match = $match->calculerDureeEstimee();
            }
        });
    }
}
