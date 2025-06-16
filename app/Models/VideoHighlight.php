<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class VideoHighlight extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_tennis_id',
        'url',
        'url_thumbnail',
        'type',
        'sous_type',
        'set_numero',
        'game_numero',
        'point_numero',
        'score_moment',
        'minute_match',
        'duree_video',
        'joueur_principal_id',
        'joueur_adverse_id',
        'type_coup',
        'zone_court',
        'impact_momentum',
        'niveau_spectaculaire',
        'description',
        'tags',
        'qualite_video',
        'vues_count',
        'likes_count',
        'shares_count',
        'timestamp_creation',
        'source_video',
        'analysable_ia',
        'metadata_technique'
    ];

    protected $casts = [
        'set_numero' => 'integer',
        'game_numero' => 'integer',
        'point_numero' => 'integer',
        'minute_match' => 'integer',
        'duree_video' => 'integer', // en secondes
        'joueur_principal_id' => 'integer',
        'joueur_adverse_id' => 'integer',
        'impact_momentum' => 'integer', // -5 à +5
        'niveau_spectaculaire' => 'integer', // 1-10
        'vues_count' => 'integer',
        'likes_count' => 'integer',
        'shares_count' => 'integer',
        'timestamp_creation' => 'datetime',
        'analysable_ia' => 'boolean',
        'tags' => 'array',
        'metadata_technique' => 'array'
    ];

    protected $appends = [
        'engagement_rate',
        'categorie_impact',
        'moment_cle_match',
        'viralite_score'
    ];

    // Relations
    public function match()
    {
        return $this->belongsTo(MatchTennis::class, 'match_tennis_id');
    }

    public function joueurPrincipal()
    {
        return $this->belongsTo(Joueur::class, 'joueur_principal_id');
    }

    public function joueurAdverse()
    {
        return $this->belongsTo(Joueur::class, 'joueur_adverse_id');
    }

    // Accessors pour les métriques calculées
    public function getEngagementRateAttribute()
    {
        if ($this->vues_count == 0) return 0;

        $total_interactions = $this->likes_count + $this->shares_count;
        return round(($total_interactions / $this->vues_count) * 100, 2);
    }

    public function getCategorieImpactAttribute()
    {
        $impact = abs($this->impact_momentum);

        if ($impact >= 4) return 'game_changer';
        if ($impact >= 3) return 'tournant';
        if ($impact >= 2) return 'important';
        if ($impact >= 1) return 'notable';
        return 'neutre';
    }

    public function getMomentCleMatchAttribute()
    {
        // Détermine si c'est un moment clé basé sur le contexte
        $moments_cles = [
            'match_point',
            'set_point',
            'break_point',
            'tie_break',
            'comeback',
            'debut_match',
            'fin_set'
        ];

        return in_array($this->type, $moments_cles) || $this->impact_momentum >= 3;
    }

    public function getViraliteScoreAttribute()
    {
        // Score de viralité basé sur engagement et spectacle
        $engagement_factor = min($this->engagement_rate / 10, 5); // Max 5 points
        $spectacle_factor = $this->niveau_spectaculaire / 2; // Max 5 points
        $views_factor = min(log($this->vues_count + 1) / 2, 5); // Max 5 points (logarithmique)

        return round($engagement_factor + $spectacle_factor + $views_factor, 1);
    }

    // Scopes pour les requêtes courantes
    public function scopeMomentsSpectaculaires($query, $niveau_min = 8)
    {
        return $query->where('niveau_spectaculaire', '>=', $niveau_min);
    }

    public function scopeHighImpact($query, $impact_min = 3)
    {
        return $query->where('impact_momentum', '>=', $impact_min)
            ->orWhere('impact_momentum', '<=', -$impact_min);
    }

    public function scopeViraux($query, $score_min = 10)
    {
        return $query->whereRaw('(vues_count + likes_count * 10 + shares_count * 20) >= ?', [$score_min]);
    }

    public function scopeAnalysablesIA($query)
    {
        return $query->where('analysable_ia', true);
    }

    public function scopeParJoueur($query, $joueur_id)
    {
        return $query->where('joueur_principal_id', $joueur_id);
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeParSet($query, $set_numero)
    {
        return $query->where('set_numero', $set_numero);
    }

    // Méthodes d'analyse et de classification
    public function categoriserHighlight()
    {
        $categories = [];

        // Par type de coup
        if (in_array($this->type_coup, ['ace', 'service_gagnant'])) {
            $categories[] = 'service_power';
        }

        if (in_array($this->type_coup, ['passing', 'lob', 'drop_shot'])) {
            $categories[] = 'technique_fine';
        }

        if (in_array($this->type_coup, ['smash', 'volley_gagnante'])) {
            $categories[] = 'jeu_offensif';
        }

        // Par contexte
        if ($this->moment_cle_match) {
            $categories[] = 'moment_decisif';
        }

        if ($this->niveau_spectaculaire >= 9) {
            $categories[] = 'pure_spectacle';
        }

        if (abs($this->impact_momentum) >= 4) {
            $categories[] = 'game_changer';
        }

        return $categories;
    }

    public function extraireMetadonneesTechniques()
    {
        // Analyse technique automatisée (à implémenter avec IA)
        $metadata = $this->metadata_technique ?? [];

        // Structure suggérée pour l'analyse IA future
        $analyse_suggeree = [
            'vitesse_balle' => null,
            'angle_frappe' => null,
            'position_joueur' => null,
            'direction_coup' => null,
            'effet_balle' => null, // lift, slice, flat
            'distance_parcourue' => null,
            'temps_reaction' => null,
            'qualite_placement' => null // 1-10
        ];

        return array_merge($analyse_suggeree, $metadata);
    }

    public function genererThumbnail()
    {
        // Génération automatique de thumbnail au moment clé
        if (!$this->url_thumbnail && $this->url) {
            // Logique de génération de thumbnail
            // À implémenter avec service vidéo
            return "thumbnail_generated_at_" . ($this->duree_video / 2) . "s";
        }

        return $this->url_thumbnail;
    }

    public function calculerScoreRecommandation($user_preferences = [])
    {
        // Score de recommandation personnalisé
        $score_base = $this->viralite_score;

        // Ajustements selon préférences utilisateur
        if (isset($user_preferences['joueur_favori']) &&
            $user_preferences['joueur_favori'] == $this->joueur_principal_id) {
            $score_base += 3;
        }

        if (isset($user_preferences['type_favori']) &&
            $user_preferences['type_favori'] == $this->type) {
            $score_base += 2;
        }

        if (isset($user_preferences['prefer_spectaculaire']) &&
            $user_preferences['prefer_spectaculaire'] &&
            $this->niveau_spectaculaire >= 8) {
            $score_base += 2;
        }

        return round($score_base, 1);
    }

    public function marquerVue()
    {
        $this->increment('vues_count');
    }

    public function marquerLike()
    {
        $this->increment('likes_count');
    }

    public function marquerPartage($platform = null)
    {
        $this->increment('shares_count');

        // Log de la plateforme de partage si nécessaire
        if ($platform) {
            $shares_data = $this->metadata_technique ?? [];
            $shares_data['platforms'][$platform] = ($shares_data['platforms'][$platform] ?? 0) + 1;
            $this->update(['metadata_technique' => $shares_data]);
        }
    }

    public function estTrendingCandidate()
    {
        // Détermine si le highlight peut devenir trending
        $recent = $this->created_at->diffInHours(now()) <= 24;
        $high_engagement = $this->engagement_rate > 5;
        $viral_potential = $this->viralite_score > 8;

        return $recent && $high_engagement && $viral_potential;
    }

    public function genererRapportAnalyse()
    {
        return [
            'classification' => $this->categoriserHighlight(),
            'impact_momentum' => $this->categorie_impact,
            'viralite' => $this->viralite_score,
            'engagement' => $this->engagement_rate,
            'moment_cle' => $this->moment_cle_match,
            'metadata_technique' => $this->extraireMetadonneesTechniques(),
            'trending_potential' => $this->estTrendingCandidate(),
            'score_spectacle' => $this->niveau_spectaculaire
        ];
    }

    // Méthodes statiques pour l'analyse globale
    public static function topHighlightsByJoueur($joueur_id, $limit = 10)
    {
        return self::where('joueur_principal_id', $joueur_id)
            ->orderByDesc('viralite_score')
            ->limit($limit)
            ->get();
    }

    public static function momentsDecisifsMatch($match_id)
    {
        return self::where('match_tennis_id', $match_id)
            ->where('impact_momentum', '>=', 3)
            ->orderBy('minute_match')
            ->get();
    }

    public static function highlightsViraux($periode_jours = 7)
    {
        return self::where('created_at', '>=', now()->subDays($periode_jours))
            ->orderByDesc('viralite_score')
            ->limit(50)
            ->get();
    }
}
