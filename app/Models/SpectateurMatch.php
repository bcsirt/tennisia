<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpectateurMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_tennis_id',
        'nb_vip',
        'nb_loges',
        'nb_tribunes',
        'nb_total_spectateurs',
        'capacite_court',
        'pourcentage_remplissage',
        'support_j1_pourcentage',
        'support_j2_pourcentage',
        'support_neutre_pourcentage',
        'niveau_ambiance',
        'niveau_bruit',
        'presence_celebrites',
        'incidents',
        'type_incidents',
        'description_incidents',
        'heure_pic_affluence',
        'meteo_impact_affluence',
        'facteur_home_advantage',
        'tension_crowd',
        'encouragements_audibles',
    ];

    protected $casts = [
        'nb_vip' => 'integer',
        'nb_loges' => 'integer',
        'nb_tribunes' => 'integer',
        'nb_total_spectateurs' => 'integer',
        'capacite_court' => 'integer',
        'pourcentage_remplissage' => 'decimal:1',
        'support_j1_pourcentage' => 'decimal:1',
        'support_j2_pourcentage' => 'decimal:1',
        'support_neutre_pourcentage' => 'decimal:1',
        'niveau_ambiance' => 'integer', // 1-10
        'niveau_bruit' => 'integer', // 1-10
        'presence_celebrites' => 'boolean',
        'incidents' => 'boolean',
        'type_incidents' => 'array',
        'description_incidents' => 'array',
        'heure_pic_affluence' => 'time',
        'meteo_impact_affluence' => 'decimal:1',
        'facteur_home_advantage' => 'decimal:2',
        'tension_crowd' => 'integer', // 1-10
        'encouragements_audibles' => 'boolean',
    ];

    protected $appends = [
        'densite_spectateurs',
        'indice_atmosphere',
        'avantage_support',
        'impact_psychologique_estime',
    ];

    // Relations
    public function match()
    {
        return $this->belongsTo(MatchTennis::class, 'match_tennis_id');
    }

    // Accessors pour les calculs d'impact
    public function getDensiteSpectateurAttribute()
    {
        if (! $this->capacite_court || $this->capacite_court == 0) {
            return 0;
        }

        return round($this->nb_total_spectateurs / $this->capacite_court, 3);
    }

    public function getIndiceAtmosphereAttribute()
    {
        // Calcul composite de l'atmosphère (0-100)
        $base_score = ($this->pourcentage_remplissage / 100) * 30;
        $ambiance_score = ($this->niveau_ambiance / 10) * 25;
        $bruit_score = ($this->niveau_bruit / 10) * 20;
        $tension_score = ($this->tension_crowd / 10) * 15;
        $celebrity_bonus = $this->presence_celebrites ? 10 : 0;

        return round($base_score + $ambiance_score + $bruit_score + $tension_score + $celebrity_bonus, 1);
    }

    public function getAvantageSupportAttribute()
    {
        // Différence de support entre les joueurs
        return round($this->support_j1_pourcentage - $this->support_j2_pourcentage, 1);
    }

    public function getImpactPsychologiqueEstimeAttribute()
    {
        // Estimation de l'impact psychologique sur les joueurs (0-10)
        $crowd_factor = ($this->indice_atmosphere / 100) * 4;
        $support_factor = (abs($this->avantage_support) / 100) * 3;
        $pressure_factor = ($this->tension_crowd / 10) * 2;
        $noise_factor = ($this->niveau_bruit / 10) * 1;

        return round($crowd_factor + $support_factor + $pressure_factor + $noise_factor, 1);
    }

    // Scopes pour les requêtes courantes
    public function scopeAtmosphereIntensite($query, $niveau_min = 7)
    {
        return $query->where('niveau_ambiance', '>=', $niveau_min)
            ->where('pourcentage_remplissage', '>=', 80);
    }

    public function scopeAvecIncidents($query)
    {
        return $query->where('incidents', true);
    }

    public function scopeSupportDesequilibre($query, $seuil = 70)
    {
        return $query->where(function ($q) use ($seuil) {
            $q->where('support_j1_pourcentage', '>=', $seuil)
                ->orWhere('support_j2_pourcentage', '>=', $seuil);
        });
    }

    public function scopeCourtPlein($query, $seuil = 95)
    {
        return $query->where('pourcentage_remplissage', '>=', $seuil);
    }

    // Méthodes d'analyse
    public function categoriserAmbiance()
    {
        $indice = $this->indice_atmosphere;

        if ($indice >= 80) {
            return 'explosive';
        }
        if ($indice >= 60) {
            return 'intense';
        }
        if ($indice >= 40) {
            return 'moderate';
        }
        if ($indice >= 20) {
            return 'calme';
        }

        return 'apathique';
    }

    public function detecterFacteursInfluence()
    {
        $facteurs = [];

        // Support déséquilibré
        if (abs($this->avantage_support) > 50) {
            $favori = $this->support_j1_pourcentage > $this->support_j2_pourcentage ? 'j1' : 'j2';
            $facteurs[] = "support_massif_{$favori}";
        }

        // Court plein
        if ($this->pourcentage_remplissage > 95) {
            $facteurs[] = 'court_plein';
        }

        // Haute tension
        if ($this->tension_crowd >= 8) {
            $facteurs[] = 'tension_elevee';
        }

        // Présence de célébrités
        if ($this->presence_celebrites) {
            $facteurs[] = 'celebrites_presentes';
        }

        // Encouragements audibles
        if ($this->encouragements_audibles && $this->niveau_bruit >= 7) {
            $facteurs[] = 'encouragements_impactants';
        }

        // Incidents perturbateurs
        if ($this->incidents) {
            $facteurs[] = 'incidents_perturbateurs';
        }

        return $facteurs;
    }

    public function calculerImpactRanking($ranking_j1, $ranking_j2)
    {
        // L'impact de la foule varie selon le ranking des joueurs
        $diff_ranking = abs($ranking_j1 - $ranking_j2);

        // Plus la différence est grande, plus l'impact de la foule peut être significatif
        $facteur_surprise = min($diff_ranking / 50, 2);

        return $this->impact_psychologique_estime * $facteur_surprise;
    }

    public function estEnvironnementHostile($pour_joueur = 'j1')
    {
        // Détermine si l'environnement est hostile pour un joueur donné
        $support_joueur = $pour_joueur === 'j1' ? $this->support_j1_pourcentage : $this->support_j2_pourcentage;

        return $support_joueur < 20 &&
            $this->niveau_bruit >= 7 &&
            $this->tension_crowd >= 6 &&
            $this->pourcentage_remplissage >= 70;
    }

    public function predireImpactPerformance($pour_joueur = 'j1')
    {
        // Prédiction de l'impact sur la performance (-5 à +5)
        $support_joueur = $pour_joueur === 'j1' ? $this->support_j1_pourcentage : $this->support_j2_pourcentage;

        // Facteur de support
        $impact_support = ($support_joueur - 50) / 10; // -5 à +5

        // Facteur d'intensité (peut stresser ou motiver)
        $intensite_factor = $this->indice_atmosphere / 100;

        // Les joueurs expérimentés gèrent mieux la pression
        $impact_final = $impact_support * $intensite_factor;

        return round(max(-5, min(5, $impact_final)), 1);
    }

    public function genererRapportAmbiance()
    {
        return [
            'categorie_ambiance' => $this->categoriserAmbiance(),
            'indice_atmosphere' => $this->indice_atmosphere,
            'impact_psychologique' => $this->impact_psychologique_estime,
            'facteurs_influence' => $this->detecterFacteursInfluence(),
            'avantage_support' => $this->avantage_support,
            'environnement_hostile_j1' => $this->estEnvironnementHostile('j1'),
            'environnement_hostile_j2' => $this->estEnvironnementHostile('j2'),
            'impact_predit_j1' => $this->predireImpactPerformance('j1'),
            'impact_predit_j2' => $this->predireImpactPerformance('j2'),
        ];
    }
}
