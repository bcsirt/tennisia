<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Actualite extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'slug',
        'contenu',
        'resume',
        'meta_description',
        'date_publication',
        'date_evenement',
        'auteur_id',
        'image',
        'image_alt',
        'source_url',
        'source_nom',
        'categorie_actualite',
        'sous_categorie',
        'priorite',
        'urgence_niveau',
        'statut',
        'moderation_statut',
        'langue',
        'pays_concerne',
        'tournoi_concerne_id',
        'joueurs_mentionnes',
        'equipes_mentionnees',
        'impact_predictions',
        'sentiment_global',
        'sentiment_joueurs',
        'tags_tennis',
        'mots_cles',
        'nb_vues',
        'nb_partages',
        'nb_commentaires',
        'score_engagement',
        'facteur_viral',
        'credibilite_score',
        'verification_status',
        'fact_check_note',
        'algorithme_classification',
        'confiance_classification',
        'date_expiration_pertinence',
        'mise_a_jour_automatique',
        'notification_sent',
        'push_sent',
        'email_sent',
        'social_media_posted',
        'seo_optimise',
        'featured',
        'breaking_news',
        'exclusif',
        'analyse_ia_complete',
        'predictions_impactees'
    ];

    protected $casts = [
        'date_publication' => 'datetime',
        'date_evenement' => 'datetime',
        'date_expiration_pertinence' => 'datetime',
        'priorite' => 'integer', // 1-10
        'urgence_niveau' => 'integer', // 1-5
        'joueurs_mentionnes' => 'array',
        'equipes_mentionnees' => 'array',
        'impact_predictions' => 'array',
        'sentiment_global' => 'decimal:2', // -1 à +1
        'sentiment_joueurs' => 'array',
        'tags_tennis' => 'array',
        'mots_cles' => 'array',
        'nb_vues' => 'integer',
        'nb_partages' => 'integer',
        'nb_commentaires' => 'integer',
        'score_engagement' => 'decimal:2',
        'facteur_viral' => 'decimal:2',
        'credibilite_score' => 'decimal:1', // 1-10
        'confiance_classification' => 'decimal:2',
        'notification_sent' => 'boolean',
        'push_sent' => 'boolean',
        'email_sent' => 'boolean',
        'social_media_posted' => 'boolean',
        'seo_optimise' => 'boolean',
        'featured' => 'boolean',
        'breaking_news' => 'boolean',
        'exclusif' => 'boolean',
        'analyse_ia_complete' => 'boolean',
        'mise_a_jour_automatique' => 'boolean',
        'predictions_impactees' => 'array'
    ];

    protected $appends = [
        'age_actualite',
        'pertinence_actuelle',
        'impact_score',
        'viralite_potentiel',
        'urgence_classification'
    ];

    // Relations
    public function auteur()
    {
        return $this->belongsTo(User::class, 'auteur_id');
    }

    public function tournoi()
    {
        return $this->belongsTo(Tournoi::class, 'tournoi_concerne_id');
    }

    public function joueursMentionnes()
    {
        return $this->belongsToMany(Joueur::class, 'actualite_joueur')
            ->withPivot('type_mention', 'sentiment_specifique', 'impact_predit');
    }

    public function predictionsMises()
    {
        return $this->belongsToMany(Prediction::class, 'actualite_prediction_impact')
            ->withPivot('type_impact', 'facteur_ajustement', 'confiance');
    }

    public function commentaires()
    {
        return $this->hasMany(CommentaireActualite::class);
    }

    // Accessors pour les métriques calculées
    public function getAgeActualiteAttribute()
    {
        return $this->date_publication ? $this->date_publication->diffForHumans() : null;
    }

    public function getPertinenceActuelleAttribute()
    {
        // Pertinence décroissante avec le temps selon le type d'actualité
        if (!$this->date_publication) return 0;

        $jours_passes = $this->date_publication->diffInDays(now());

        $duree_pertinence = match($this->categorie_actualite) {
            'breaking_news', 'blessure', 'forfait' => 3, // 3 jours
            'transfert', 'coach_change' => 30, // 30 jours
            'classement', 'resultat' => 7, // 7 jours
            'interview', 'analyse' => 14, // 14 jours
            default => 7
        };

        return max(0, round((1 - ($jours_passes / $duree_pertinence)) * 100, 1));
    }

    public function getImpactScoreAttribute()
    {
        // Score d'impact combinant plusieurs facteurs
        $score_base = 0;

        // Impact selon la catégorie
        $impact_categorie = match($this->categorie_actualite) {
            'blessure', 'forfait' => 9,
            'transfert', 'coach_change' => 7,
            'breaking_news' => 8,
            'scandale', 'controverse' => 6,
            'performance', 'resultat' => 5,
            default => 3
        };

        // Bonus pour joueurs top 10
        $bonus_ranking = 0;
        foreach ($this->joueurs_mentionnes ?? [] as $joueur_id) {
            $joueur = Joueur::find($joueur_id);
            if ($joueur && ($joueur->ranking_atp <= 10 || $joueur->ranking_wta <= 10)) {
                $bonus_ranking += 2;
            }
        }

        // Facteur urgence
        $facteur_urgence = ($this->urgence_niveau ?? 1) / 5;

        return round($impact_categorie + $bonus_ranking * $facteur_urgence, 1);
    }

    public function getViralitePotentielAttribute()
    {
        // Potentiel viral basé sur engagement et facteurs
        $base_score = $this->score_engagement ?? 0;

        // Bonus facteurs viraux
        $bonus = 0;
        if ($this->breaking_news) $bonus += 0.3;
        if ($this->exclusif) $bonus += 0.2;
        if ($this->categorie_actualite === 'scandale') $bonus += 0.4;
        if ($this->sentiment_global && abs($this->sentiment_global) > 0.7) $bonus += 0.2;

        return round(min(10, $base_score + $bonus), 1);
    }

    public function getUrgenceClassificationAttribute()
    {
        $urgence = $this->urgence_niveau ?? 1;

        return match($urgence) {
            5 => 'critique',
            4 => 'urgent',
            3 => 'important',
            2 => 'normal',
            1 => 'faible'
        };
    }

    // Scopes pour les requêtes courantes
    public function scopePubliee($query)
    {
        return $query->where('statut', 'publiee');
    }

    public function scopeBreakingNews($query)
    {
        return $query->where('breaking_news', true);
    }

    public function scopeRecente($query, $jours = 7)
    {
        return $query->where('date_publication', '>=', now()->subDays($jours));
    }

    public function scopeCategorie($query, $categorie)
    {
        return $query->where('categorie_actualite', $categorie);
    }

    public function scopeImpactant($query, $score_min = 7)
    {
        return $query->whereRaw('(priorite * urgence_niveau) >= ?', [$score_min]);
    }

    public function scopePertinente($query)
    {
        return $query->where('date_expiration_pertinence', '>', now())
            ->orWhereNull('date_expiration_pertinence');
    }

    public function scopeJoueurConcerne($query, $joueur_id)
    {
        return $query->whereJsonContains('joueurs_mentionnes', $joueur_id);
    }

    public function scopeVirale($query, $score_min = 7)
    {
        return $query->where('facteur_viral', '>=', $score_min);
    }

    // Méthodes d'analyse et de classification automatique
    public function analyserContenuIA()
    {
        $resultats = [];

        // Extraction des entités tennistiques
        $entites = $this->extraireEntitesTennis();
        $resultats['entites'] = $entites;

        // Classification automatique
        $classification = $this->classiferActualite();
        $resultats['classification'] = $classification;

        // Analyse de sentiment
        $sentiment = $this->analyserSentiment();
        $resultats['sentiment'] = $sentiment;

        // Détection d'impact sur prédictions
        $impact = $this->detecterImpactPredictions();
        $resultats['impact_predictions'] = $impact;

        // Mise à jour des champs
        $this->update([
            'algorithme_classification' => $classification['categorie'],
            'confiance_classification' => $classification['confiance'],
            'sentiment_global' => $sentiment['global'],
            'sentiment_joueurs' => $sentiment['joueurs'],
            'impact_predictions' => $impact,
            'analyse_ia_complete' => true
        ]);

        return $resultats;
    }

    private function extraireEntitesTennis()
    {
        // Extraction d'entités spécifiques au tennis du contenu
        $entites = [
            'joueurs' => [],
            'tournois' => [],
            'surfaces' => [],
            'techniques' => [],
            'classements' => []
        ];

        $texte = strtolower($this->titre . ' ' . $this->contenu . ' ' . $this->resume);

        // Recherche de joueurs mentionnés
        $joueurs = Joueur::all();
        foreach ($joueurs as $joueur) {
            $nom_complet = strtolower($joueur->prenom . ' ' . $joueur->nom);
            if (str_contains($texte, strtolower($joueur->nom)) ||
                str_contains($texte, $nom_complet)) {
                $entites['joueurs'][] = $joueur->id;
            }
        }

        // Recherche de tournois
        $tournois = Tournoi::all();
        foreach ($tournois as $tournoi) {
            if (str_contains($texte, strtolower($tournoi->nom))) {
                $entites['tournois'][] = $tournoi->id;
            }
        }

        // Surfaces tennis
        $surfaces = ['dur', 'terre battue', 'gazon', 'indoor', 'outdoor'];
        foreach ($surfaces as $surface) {
            if (str_contains($texte, $surface)) {
                $entites['surfaces'][] = $surface;
            }
        }

        return $entites;
    }

    private function classiferActualite()
    {
        $texte = strtolower($this->titre . ' ' . $this->contenu);
        $mots_cles_categories = [
            'blessure' => ['blessé', 'blessure', 'injury', 'injured', 'forfait', 'withdraw'],
            'transfert' => ['signe', 'contrat', 'coach', 'entraineur', 'sponsor'],
            'resultat' => ['victoire', 'défaite', 'gagne', 'perd', 'score', 'set'],
            'classement' => ['ranking', 'classement', 'atp', 'wta', 'points'],
            'breaking_news' => ['urgent', 'breaking', 'exclusif', 'dernière minute'],
            'scandale' => ['scandale', 'controverse', 'polémique', 'accusation'],
            'performance' => ['performance', 'forme', 'niveau', 'jeu'],
            'interview' => ['interview', 'déclaration', 'conférence', 'dit', 'explique'],
            'tournoi' => ['tournoi', 'competition', 'draw', 'tableau', 'wild card']
        ];

        $scores = [];
        foreach ($mots_cles_categories as $categorie => $mots_cles) {
            $score = 0;
            foreach ($mots_cles as $mot) {
                $score += substr_count($texte, $mot) * 10;
            }
            $scores[$categorie] = $score;
        }

        $categorie_principale = array_keys($scores, max($scores))[0];
        $confiance = max($scores) > 0 ? min(max($scores) / 50, 1) : 0.3;

        return [
            'categorie' => $categorie_principale,
            'confiance' => round($confiance, 2),
            'scores_detailles' => $scores
        ];
    }

    private function analyserSentiment()
    {
        // Analyse de sentiment simple basée sur des mots-clés
        $mots_positifs = ['victoire', 'gagne', 'excellent', 'brillant', 'formidable', 'win', 'victory'];
        $mots_negatifs = ['défaite', 'perd', 'blessé', 'forfait', 'échec', 'lose', 'injury', 'poor'];

        $texte = strtolower($this->titre . ' ' . $this->contenu);

        $score_positif = 0;
        $score_negatif = 0;

        foreach ($mots_positifs as $mot) {
            $score_positif += substr_count($texte, $mot);
        }

        foreach ($mots_negatifs as $mot) {
            $score_negatif += substr_count($texte, $mot);
        }

        $sentiment_global = 0;
        if ($score_positif + $score_negatif > 0) {
            $sentiment_global = ($score_positif - $score_negatif) / ($score_positif + $score_negatif);
        }

        return [
            'global' => round($sentiment_global, 2),
            'joueurs' => $this->analyserSentimentParJoueur(),
            'score_positif' => $score_positif,
            'score_negatif' => $score_negatif
        ];
    }

    private function analyserSentimentParJoueur()
    {
        $sentiments = [];

        foreach ($this->joueurs_mentionnes ?? [] as $joueur_id) {
            $joueur = Joueur::find($joueur_id);
            if ($joueur) {
                // Analyser le contexte autour du nom du joueur
                $contexte = $this->extraireContexteJoueur($joueur);
                $sentiment = $this->calculerSentimentContexte($contexte);
                $sentiments[$joueur_id] = $sentiment;
            }
        }

        return $sentiments;
    }

    private function detecterImpactPredictions()
    {
        $impacts = [];

        // Impact selon catégorie
        $impact_par_categorie = [
            'blessure' => ['type' => 'performance', 'facteur' => -0.15],
            'forfait' => ['type' => 'disponibilite', 'facteur' => -1.0],
            'coach_change' => ['type' => 'tactique', 'facteur' => -0.05],
            'transfert' => ['type' => 'motivation', 'facteur' => 0.03],
            'scandale' => ['type' => 'mental', 'facteur' => -0.08]
        ];

        if (isset($impact_par_categorie[$this->categorie_actualite])) {
            $impact_base = $impact_par_categorie[$this->categorie_actualite];

            foreach ($this->joueurs_mentionnes ?? [] as $joueur_id) {
                $impacts[] = [
                    'joueur_id' => $joueur_id,
                    'type_impact' => $impact_base['type'],
                    'facteur' => $impact_base['facteur'],
                    'duree_jours' => $this->calculerDureeImpact(),
                    'confiance' => $this->confiance_classification ?? 0.5
                ];
            }
        }

        return $impacts;
    }

    public function notifierImpactPredictions()
    {
        // Notifier les services de prédiction d'un changement
        if (!empty($this->impact_predictions)) {
            event(new ActualiteImpactPrediction($this));

            // Marquer les prédictions à recalculer
            $this->marquerPredictionsARecalculer();
        }
    }

    public function genererTagsAutomatiques()
    {
        $tags = [];

        // Tags basés sur la catégorie
        $tags[] = '#' . $this->categorie_actualite;

        // Tags des joueurs mentionnés
        foreach ($this->joueurs_mentionnes ?? [] as $joueur_id) {
            $joueur = Joueur::find($joueur_id);
            if ($joueur) {
                $tags[] = '#' . str_replace(' ', '', $joueur->nom);
            }
        }

        // Tags du tournoi
        if ($this->tournoi) {
            $tags[] = '#' . str_replace(' ', '', $this->tournoi->nom);
        }

        // Tags de surface
        $entites = $this->extraireEntitesTennis();
        foreach ($entites['surfaces'] ?? [] as $surface) {
            $tags[] = '#' . str_replace(' ', '', $surface);
        }

        $this->update(['tags_tennis' => array_unique($tags)]);

        return $tags;
    }

    public function optimiserSEO()
    {
        // Génération automatique de slug
        if (!$this->slug) {
            $this->slug = Str::slug($this->titre);
        }

        // Meta description automatique
        if (!$this->meta_description) {
            $this->meta_description = Str::limit(strip_tags($this->resume ?: $this->contenu), 160);
        }

        // Mots-clés SEO
        $mots_cles_seo = [];

        // Joueurs mentionnés comme mots-clés
        foreach ($this->joueurs_mentionnes ?? [] as $joueur_id) {
            $joueur = Joueur::find($joueur_id);
            if ($joueur) {
                $mots_cles_seo[] = $joueur->prenom . ' ' . $joueur->nom;
            }
        }

        // Catégorie et tournoi
        $mots_cles_seo[] = $this->categorie_actualite;
        if ($this->tournoi) {
            $mots_cles_seo[] = $this->tournoi->nom;
        }

        $this->update([
            'mots_cles' => array_unique($mots_cles_seo),
            'seo_optimise' => true
        ]);
    }

    public function calculerEngagement()
    {
        // Score d'engagement basé sur métriques
        $vues_normalized = min($this->nb_vues / 10000, 1);
        $partages_normalized = min($this->nb_partages / 1000, 1);
        $commentaires_normalized = min($this->nb_commentaires / 100, 1);

        $score = ($vues_normalized * 0.4) +
            ($partages_normalized * 0.4) +
            ($commentaires_normalized * 0.2);

        $this->update(['score_engagement' => round($score * 10, 2)]);

        return $score;
    }

    // Méthodes statiques pour analyses globales
    public static function actualitesImpactantes($jours = 7)
    {
        return self::recente($jours)
            ->impactant(7)
            ->orderByDesc('impact_score')
            ->get();
    }

    public static function tendancesActualites($jours = 30)
    {
        return self::recente($jours)
            ->selectRaw('categorie_actualite, COUNT(*) as total')
            ->groupBy('categorie_actualite')
            ->orderByDesc('total')
            ->get();
    }

    public static function actualitesVirales($jours = 7)
    {
        return self::recente($jours)
            ->virale(7)
            ->orderByDesc('facteur_viral')
            ->get();
    }
}
