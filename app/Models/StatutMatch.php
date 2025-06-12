<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatutMatch extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'statut_matchs';

    // ===================================================================
    // CONSTANTES TENNIS STANDARD
    // ===================================================================

    // IDs constants pour référence rapide
    const PROGRAMME = 1;
    const EN_COURS = 2;
    const TERMINE = 3;
    const ABANDONNE = 4;
    const REPORTE = 5;
    const ANNULE = 6;
    const WALKOVER = 7;
    const FORFAIT = 8;
    const INTERROMPU = 9;
    const SUSPENDU = 10;

    // Codes string pour l'API et logique métier
    const CODE_PROGRAMME = 'programme';
    const CODE_EN_COURS = 'en_cours';
    const CODE_TERMINE = 'termine';
    const CODE_ABANDONNE = 'abandonne';
    const CODE_REPORTE = 'reporte';
    const CODE_ANNULE = 'annule';
    const CODE_WALKOVER = 'walkover';
    const CODE_FORFAIT = 'forfait';
    const CODE_INTERROMPU = 'interrompu';
    const CODE_SUSPENDU = 'suspendu';

    protected $fillable = [
        // Informations de base
        'nom',
        'nom_court',              // "Terminé", "En cours", "W.O."
        'nom_anglais',            // "Completed", "In Progress", "Walkover"
        'code',                   // 'termine', 'en_cours', etc.
        'description',

        // Classification du statut
        'categorie',              // 'actif', 'termine', 'annule', 'reporte'
        'type',                   // 'normal', 'abandon', 'forfait', 'meteo'
        'est_final',              // Statut final (ne peut plus changer)
        'est_actif',              // Match en cours de jeu
        'est_termine',            // Match terminé normalement
        'est_annule',             // Match annulé/forfait
        'est_comptabilise',       // Compte dans les statistiques

        // Ordre et flux
        'ordre_chronologique',    // 1, 2, 3... ordre dans le processus
        'precedents_possibles',   // JSON: statuts qui peuvent mener à celui-ci
        'suivants_possibles',     // JSON: statuts possibles après celui-ci
        'transitions_auto',       // JSON: transitions automatiques

        // Impact sur le jeu
        'autorise_score',         // Permet d'enregistrer un score
        'autorise_statistiques',  // Permet d'enregistrer des stats détaillées
        'autorise_modification',  // Permet de modifier le match
        'necessite_confirmation', // Nécessite confirmation pour changer
        'declenche_notification', // Déclenche notifications

        // Règles tennis
        'attribue_victoire',      // Attribue automatiquement la victoire
        'gagnant_automatique',    // ID du gagnant si automatique
        'points_atp_wta',         // Attribue des points de classement
        'prize_money',            // Attribue le prize money
        'compte_confrontation',   // Compte dans les confrontations h2h

        // Informations admin
        'motif_requis',           // Motif obligatoire pour ce statut
        'commentaire_requis',     // Commentaire obligatoire
        'approbation_requise',    // Nécessite approbation superviseur
        'niveau_acces_requis',    // Niveau d'accès minimum pour utiliser

        // Affichage et UI
        'couleur_hex',            // Couleur d'affichage
        'couleur_fond',           // Couleur de fond
        'icone',                  // Icône représentative
        'emoji',                  // Emoji pour affichage mobile
        'css_classe',             // Classe CSS pour styling

        // Temporalité
        'duree_expiration',       // Durée avant expiration (heures)
        'permet_reprogrammation', // Permet de reprogrammer le match
        'delai_minimum_avant',    // Délai minimum avant le match (heures)

        // Métadonnées
        'priorite_affichage',     // Priorité pour l'affichage
        'ordre_affichage',        // Ordre dans les listes
        'est_visible',            // Visible dans l'interface publique
        'est_interne',            // Statut interne uniquement
        'notes',
        'actif'
    ];

    protected $casts = [
        'ordre_chronologique' => 'integer',
        'precedents_possibles' => 'json',
        'suivants_possibles' => 'json',
        'transitions_auto' => 'json',
        'gagnant_automatique' => 'integer',
        'duree_expiration' => 'integer',
        'delai_minimum_avant' => 'integer',
        'priorite_affichage' => 'integer',
        'ordre_affichage' => 'integer',
        'niveau_acces_requis' => 'integer',
        'est_final' => 'boolean',
        'est_actif' => 'boolean',
        'est_termine' => 'boolean',
        'est_annule' => 'boolean',
        'est_comptabilise' => 'boolean',
        'autorise_score' => 'boolean',
        'autorise_statistiques' => 'boolean',
        'autorise_modification' => 'boolean',
        'necessite_confirmation' => 'boolean',
        'declenche_notification' => 'boolean',
        'attribue_victoire' => 'boolean',
        'points_atp_wta' => 'boolean',
        'prize_money' => 'boolean',
        'compte_confrontation' => 'boolean',
        'motif_requis' => 'boolean',
        'commentaire_requis' => 'boolean',
        'approbation_requise' => 'boolean',
        'permet_reprogrammation' => 'boolean',
        'est_visible' => 'boolean',
        'est_interne' => 'boolean',
        'actif' => 'boolean'
    ];

    protected $appends = [
        'nom_complet',
        'niveau_urgence',
        'actions_possibles',
        'impact_classification',
        'duree_type',
        'couleur_affichage'
    ];

    // ===================================================================
    // RELATIONSHIPS
    // ===================================================================

    public function matchs()
    {
        return $this->hasMany(MatchTennis::class, 'statut_match_id');
    }

    public function matchsRecents()
    {
        return $this->hasMany(MatchTennis::class, 'statut_match_id')
            ->where('date_match', '>=', now()->subDays(30));
    }

    public function transitions()
    {
        return $this->hasMany(TransitionStatutMatch::class, 'statut_depuis_id');
    }

    // ===================================================================
    // ACCESSORS
    // ===================================================================

    public function getNomCompletAttribute()
    {
        $suffixes = [];

        if ($this->est_final) $suffixes[] = '🔒';
        if ($this->necessite_confirmation) $suffixes[] = '⚠️';
        if ($this->est_interne) $suffixes[] = '🔒';

        return $this->nom . (empty($suffixes) ? '' : ' ' . implode(' ', $suffixes));
    }

    public function getNiveauUrgenceAttribute()
    {
        if ($this->code === self::CODE_INTERROMPU) return 'Critique';
        if ($this->code === self::CODE_EN_COURS) return 'Élevée';
        if ($this->code === self::CODE_REPORTE) return 'Modérée';
        if ($this->est_final) return 'Faible';

        return 'Standard';
    }

    public function getActionsPossiblesAttribute()
    {
        $actions = [];

        if ($this->autorise_score) $actions[] = 'Enregistrer score';
        if ($this->autorise_statistiques) $actions[] = 'Saisir statistiques';
        if ($this->autorise_modification) $actions[] = 'Modifier match';
        if ($this->permet_reprogrammation) $actions[] = 'Reprogrammer';

        // Actions selon le statut
        if ($this->code === self::CODE_PROGRAMME) {
            $actions[] = 'Démarrer match';
            $actions[] = 'Reporter';
            $actions[] = 'Annuler';
        }

        if ($this->code === self::CODE_EN_COURS) {
            $actions[] = 'Terminer match';
            $actions[] = 'Interrompre';
            $actions[] = 'Déclarer abandon';
        }

        if ($this->code === self::CODE_INTERROMPU) {
            $actions[] = 'Reprendre';
            $actions[] = 'Reporter à plus tard';
            $actions[] = 'Annuler définitivement';
        }

        return $actions;
    }

    public function getImpactClassificationAttribute()
    {
        if (!$this->est_comptabilise) return 'Aucun impact';
        if ($this->points_atp_wta && $this->prize_money) return 'Impact complet';
        if ($this->points_atp_wta) return 'Points uniquement';
        if ($this->prize_money) return 'Prize money uniquement';

        return 'Impact partiel';
    }

    public function getDureeTypeAttribute()
    {
        if ($this->est_actif) return 'Variable';
        if ($this->est_final) return 'Permanent';
        if ($this->duree_expiration) return "Temporaire ({$this->duree_expiration}h)";

        return 'Indéterminée';
    }

    public function getCouleurAffichageAttribute()
    {
        if ($this->couleur_hex) return $this->couleur_hex;

        // Couleurs par défaut selon le statut
        $couleurs = [
            self::CODE_PROGRAMME => '#3498db',     // Bleu
            self::CODE_EN_COURS => '#e74c3c',      // Rouge
            self::CODE_TERMINE => '#27ae60',       // Vert
            self::CODE_ABANDONNE => '#f39c12',     // Orange
            self::CODE_REPORTE => '#9b59b6',       // Violet
            self::CODE_ANNULE => '#95a5a6',        // Gris
            self::CODE_WALKOVER => '#f39c12',      // Orange
            self::CODE_FORFAIT => '#e67e22',       // Orange foncé
            self::CODE_INTERROMPU => '#e74c3c',    // Rouge
            self::CODE_SUSPENDU => '#34495e'       // Gris foncé
        ];

        return $couleurs[$this->code] ?? '#bdc3c7';
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

    public function scopeFinaux($query)
    {
        return $query->where('est_final', true);
    }

    public function scopeEnCours($query)
    {
        return $query->where('est_actif', true);
    }

    public function scopeTermines($query)
    {
        return $query->where('est_termine', true);
    }

    public function scopeComptabilises($query)
    {
        return $query->where('est_comptabilise', true);
    }

    public function scopeParCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrdonnes($query)
    {
        return $query->orderBy('ordre_chronologique')
            ->orderBy('priorite_affichage')
            ->orderBy('nom');
    }

    public function scopeAvecTransitions($query)
    {
        return $query->whereNotNull('suivants_possibles');
    }

    public function scopeRecherche($query, $terme)
    {
        return $query->where(function($q) use ($terme) {
            $q->where('nom', 'LIKE', "%{$terme}%")
                ->orWhere('code', 'LIKE', "%{$terme}%")
                ->orWhere('description', 'LIKE', "%{$terme}%");
        });
    }

    // ===================================================================
    // STATIC METHODS
    // ===================================================================

    /**
     * Créer les statuts standard du tennis
     */
    public static function creerStatutsStandard()
    {
        $statuts = [
            [
                'id' => self::PROGRAMME,
                'nom' => 'Programmé',
                'nom_court' => 'Prog.',
                'code' => self::CODE_PROGRAMME,
                'categorie' => 'attente',
                'type' => 'normal',
                'ordre_chronologique' => 1,
                'suivants_possibles' => [self::CODE_EN_COURS, self::CODE_REPORTE, self::CODE_ANNULE, self::CODE_WALKOVER],
                'autorise_modification' => true,
                'permet_reprogrammation' => true,
                'declenche_notification' => true,
                'couleur_hex' => '#3498db',
                'icone' => 'calendar'
            ],
            [
                'id' => self::EN_COURS,
                'nom' => 'En cours',
                'nom_court' => 'Live',
                'code' => self::CODE_EN_COURS,
                'categorie' => 'actif',
                'type' => 'normal',
                'ordre_chronologique' => 2,
                'est_actif' => true,
                'precedents_possibles' => [self::CODE_PROGRAMME, self::CODE_INTERROMPU],
                'suivants_possibles' => [self::CODE_TERMINE, self::CODE_ABANDONNE, self::CODE_INTERROMPU],
                'autorise_score' => true,
                'autorise_statistiques' => true,
                'declenche_notification' => true,
                'couleur_hex' => '#e74c3c',
                'icone' => 'play'
            ],
            [
                'id' => self::TERMINE,
                'nom' => 'Terminé',
                'nom_court' => 'Fini',
                'code' => self::CODE_TERMINE,
                'categorie' => 'termine',
                'type' => 'normal',
                'ordre_chronologique' => 3,
                'est_final' => true,
                'est_termine' => true,
                'est_comptabilise' => true,
                'precedents_possibles' => [self::CODE_EN_COURS],
                'attribue_victoire' => true,
                'points_atp_wta' => true,
                'prize_money' => true,
                'compte_confrontation' => true,
                'couleur_hex' => '#27ae60',
                'icone' => 'check'
            ],
            [
                'id' => self::ABANDONNE,
                'nom' => 'Abandonné',
                'nom_court' => 'Aband.',
                'code' => self::CODE_ABANDONNE,
                'categorie' => 'termine',
                'type' => 'abandon',
                'ordre_chronologique' => 3,
                'est_final' => true,
                'est_termine' => true,
                'est_comptabilise' => true,
                'precedents_possibles' => [self::CODE_EN_COURS],
                'attribue_victoire' => true,
                'points_atp_wta' => true,
                'prize_money' => true,
                'compte_confrontation' => true,
                'motif_requis' => true,
                'couleur_hex' => '#f39c12',
                'icone' => 'stop'
            ],
            [
                'id' => self::REPORTE,
                'nom' => 'Reporté',
                'nom_court' => 'Rep.',
                'code' => self::CODE_REPORTE,
                'categorie' => 'reporte',
                'type' => 'meteo',
                'ordre_chronologique' => 1,
                'precedents_possibles' => [self::CODE_PROGRAMME, self::CODE_INTERROMPU],
                'suivants_possibles' => [self::CODE_PROGRAMME, self::CODE_ANNULE],
                'permet_reprogrammation' => true,
                'duree_expiration' => 168, // 7 jours
                'motif_requis' => true,
                'couleur_hex' => '#9b59b6',
                'icone' => 'clock'
            ],
            [
                'id' => self::ANNULE,
                'nom' => 'Annulé',
                'nom_court' => 'Ann.',
                'code' => self::CODE_ANNULE,
                'categorie' => 'annule',
                'type' => 'annulation',
                'ordre_chronologique' => 0,
                'est_final' => true,
                'est_annule' => true,
                'precedents_possibles' => [self::CODE_PROGRAMME, self::CODE_REPORTE],
                'motif_requis' => true,
                'commentaire_requis' => true,
                'approbation_requise' => true,
                'couleur_hex' => '#95a5a6',
                'icone' => 'x'
            ],
            [
                'id' => self::WALKOVER,
                'nom' => 'Walkover',
                'nom_court' => 'W.O.',
                'code' => self::CODE_WALKOVER,
                'categorie' => 'termine',
                'type' => 'forfait',
                'ordre_chronologique' => 3,
                'est_final' => true,
                'est_termine' => true,
                'est_comptabilise' => true,
                'precedents_possibles' => [self::CODE_PROGRAMME, self::CODE_EN_COURS],
                'attribue_victoire' => true,
                'points_atp_wta' => true,
                'prize_money' => true,
                'compte_confrontation' => true,
                'motif_requis' => true,
                'couleur_hex' => '#f39c12',
                'icone' => 'user-x'
            ],
            [
                'id' => self::FORFAIT,
                'nom' => 'Forfait',
                'nom_court' => 'Forf.',
                'code' => self::CODE_FORFAIT,
                'categorie' => 'termine',
                'type' => 'forfait',
                'ordre_chronologique' => 3,
                'est_final' => true,
                'est_termine' => true,
                'est_comptabilise' => true,
                'precedents_possibles' => [self::CODE_PROGRAMME],
                'attribue_victoire' => true,
                'points_atp_wta' => false, // Pas de points en cas de forfait avant match
                'prize_money' => false,
                'compte_confrontation' => false,
                'motif_requis' => true,
                'approbation_requise' => true,
                'couleur_hex' => '#e67e22',
                'icone' => 'ban'
            ],
            [
                'id' => self::INTERROMPU,
                'nom' => 'Interrompu',
                'nom_court' => 'Inter.',
                'code' => self::CODE_INTERROMPU,
                'categorie' => 'suspendu',
                'type' => 'meteo',
                'ordre_chronologique' => 2,
                'precedents_possibles' => [self::CODE_EN_COURS],
                'suivants_possibles' => [self::CODE_EN_COURS, self::CODE_REPORTE, self::CODE_ANNULE],
                'duree_expiration' => 24, // 24 heures max
                'autorise_modification' => true,
                'necessité_confirmation' => true,
                'couleur_hex' => '#e74c3c',
                'icone' => 'pause'
            ],
            [
                'id' => self::SUSPENDU,
                'nom' => 'Suspendu',
                'nom_court' => 'Susp.',
                'code' => self::CODE_SUSPENDU,
                'categorie' => 'suspendu',
                'type' => 'disciplinaire',
                'ordre_chronologique' => 0,
                'precedents_possibles' => [self::CODE_PROGRAMME, self::CODE_EN_COURS],
                'suivants_possibles' => [self::CODE_PROGRAMME, self::CODE_ANNULE],
                'motif_requis' => true,
                'approbation_requise' => true,
                'niveau_acces_requis' => 5, // Superviseur
                'est_interne' => true,
                'couleur_hex' => '#34495e',
                'icone' => 'shield'
            ]
        ];

        foreach ($statuts as $statut) {
            $statut['est_visible'] = !($statut['est_interne'] ?? false);
            $statut['actif'] = true;

            self::firstOrCreate(
                ['code' => $statut['code']],
                $statut
            );
        }
    }

    /**
     * Obtenir les statuts par catégorie
     */
    public static function parCategorie($categorie = null)
    {
        $query = self::actifs()->ordonnes();

        if ($categorie) {
            $query->where('categorie', $categorie);
        }

        return $query->get()->groupBy('categorie');
    }

    /**
     * Obtenir les transitions possibles
     */
    public static function getTransitionsPossibles()
    {
        return self::actifs()
            ->whereNotNull('suivants_possibles')
            ->get()
            ->mapWithKeys(function($statut) {
                return [$statut->code => $statut->suivants_possibles];
            });
    }

    // ===================================================================
    // METHODS
    // ===================================================================

    /**
     * Vérifier si la transition vers un autre statut est possible
     */
    public function peutTransitionnerVers($codeStatutCible)
    {
        if (!$this->suivants_possibles) return false;

        return in_array($codeStatutCible, $this->suivants_possibles);
    }

    /**
     * Obtenir les statuts suivants possibles avec détails
     */
    public function getStatutsSuivantsPossibles()
    {
        if (!$this->suivants_possibles) return collect();

        return self::whereIn('code', $this->suivants_possibles)
            ->actifs()
            ->ordonnes()
            ->get();
    }

    /**
     * Vérifier si ce statut nécessite des données spécifiques
     */
    public function getNecessitesDonnees()
    {
        $necessites = [];

        if ($this->motif_requis) $necessites[] = 'motif';
        if ($this->commentaire_requis) $necessites[] = 'commentaire';
        if ($this->attribue_victoire && !$this->gagnant_automatique) $necessites[] = 'gagnant';
        if ($this->autorise_score) $necessites[] = 'score';

        return $necessites;
    }

    /**
     * Calculer l'impact sur les statistiques du joueur
     */
    public function getImpactStatistiques()
    {
        return [
            'compte_match' => $this->est_comptabilise,
            'compte_victoire' => $this->est_termine && $this->attribue_victoire,
            'compte_defaite' => $this->est_termine && $this->attribue_victoire,
            'points_classement' => $this->points_atp_wta,
            'prize_money' => $this->prize_money,
            'confrontation_h2h' => $this->compte_confrontation,
            'statistiques_detaillees' => $this->autorise_statistiques && $this->est_termine
        ];
    }

    /**
     * Générer le résumé d'un changement de statut
     */
    public function genererResumeChangement($ancienStatut = null, $motif = null)
    {
        $resume = [
            'nouveau_statut' => $this->nom,
            'couleur' => $this->couleur_affichage,
            'icone' => $this->icone,
            'est_final' => $this->est_final,
            'impact' => $this->impact_classification,
            'actions_possibles' => $this->actions_possibles
        ];

        if ($ancienStatut) {
            $resume['ancien_statut'] = $ancienStatut->nom;
            $resume['transition_valide'] = $ancienStatut->peutTransitionnerVers($this->code);
        }

        if ($motif) {
            $resume['motif'] = $motif;
        }

        if ($this->duree_expiration) {
            $resume['expiration'] = now()->addHours($this->duree_expiration);
        }

        return $resume;
    }

    /**
     * Valider une transition de statut
     */
    public function validerTransition($statutActuel, array $donnees = [])
    {
        $erreurs = [];

        // Vérifier si la transition est autorisée
        if (!$statutActuel->peutTransitionnerVers($this->code)) {
            $erreurs[] = "Transition non autorisée de '{$statutActuel->nom}' vers '{$this->nom}'";
        }

        // Vérifier les données requises
        $necessites = $this->getNecessitesDonnees();
        foreach ($necessites as $champ) {
            if (empty($donnees[$champ])) {
                $erreurs[] = "Le champ '{$champ}' est obligatoire pour ce statut";
            }
        }

        // Vérifications spécifiques selon le statut
        if ($this->code === self::CODE_TERMINE && empty($donnees['score'])) {
            $erreurs[] = "Le score est obligatoire pour terminer un match";
        }

        if ($this->attribue_victoire && empty($donnees['gagnant_id'])) {
            $erreurs[] = "Le gagnant doit être spécifié";
        }

        return [
            'valide' => empty($erreurs),
            'erreurs' => $erreurs
        ];
    }

    /**
     * Obtenir les statistiques d'utilisation du statut
     */
    public function getStatistiquesUtilisation()
    {
        return [
            'nb_matchs_total' => $this->matchs()->count(),
            'nb_matchs_mois_actuel' => $this->matchs()
                ->whereMonth('date_match', now()->month)
                ->count(),
            'nb_matchs_semaine' => $this->matchs()
                ->whereBetween('date_match', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
            'pourcentage_total' => $this->getPourcentageUtilisation(),
            'duree_moyenne' => $this->getDureeMoyenneStatut(),
            'tendance' => $this->getTendanceUtilisation()
        ];
    }

    /**
     * Calculer le pourcentage d'utilisation par rapport à tous les matchs
     */
    private function getPourcentageUtilisation()
    {
        $totalMatchs = MatchTennis::count();
        if ($totalMatchs === 0) return 0;

        $matchsStatut = $this->matchs()->count();
        return round(($matchsStatut / $totalMatchs) * 100, 1);
    }

    /**
     * Calculer la durée moyenne dans ce statut
     */
    private function getDureeMoyenneStatut()
    {
        // Si statut final, pas de durée
        if ($this->est_final) return null;

        // Logique pour calculer le temps passé dans ce statut
        // (nécessiterait un système de logs des transitions)
        return null;
    }

    /**
     * Analyser la tendance d'utilisation
     */
    private function getTendanceUtilisation()
    {
        $moisActuel = $this->matchs()
            ->whereMonth('date_match', now()->month)
            ->count();

        $moisPrecedent = $this->matchs()
            ->whereMonth('date_match', now()->subMonth()->month)
            ->count();

        if ($moisPrecedent === 0) return 'stable';

        $variation = (($moisActuel - $moisPrecedent) / $moisPrecedent) * 100;

        if ($variation > 10) return 'hausse';
        if ($variation < -10) return 'baisse';
        return 'stable';
    }

    // ===================================================================
    // VALIDATION RULES
    // ===================================================================

    public static function validationRules()
    {
        return [
            'nom' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:statut_matchs,code',
            'categorie' => 'required|in:attente,actif,termine,annule,reporte,suspendu',
            'type' => 'required|in:normal,abandon,forfait,meteo,disciplinaire,annulation',
            'ordre_chronologique' => 'required|integer|min:0|max:10',
            'couleur_hex' => 'nullable|regex:/^#[A-Fa-f0-9]{6}$/'
        ];
    }

    // ===================================================================
    // BOOT METHODS
    // ===================================================================

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($statut) {
            // Auto-génération des booléens selon le code
            if ($statut->code) {
                switch ($statut->code) {
                    case self::CODE_EN_COURS:
                        $statut->est_actif = true;
                        $statut->autorise_score = true;
                        $statut->autorise_statistiques = true;
                        break;

                    case self::CODE_TERMINE:
                        $statut->est_final = true;
                        $statut->est_termine = true;
                        $statut->est_comptabilise = true;
                        $statut->attribue_victoire = true;
                        $statut->points_atp_wta = true;
                        $statut->prize_money = true;
                        break;

                    case self::CODE_ANNULE:
                        $statut->est_final = true;
                        $statut->est_annule = true;
                        $statut->motif_requis = true;
                        break;
                }
            }

            // Ordre d'affichage par défaut
            if (!$statut->ordre_affichage) {
                $statut->ordre_affichage = $statut->ordre_chronologique ?? 1;
            }

            // Valeurs par défaut
            if ($statut->actif === null) $statut->actif = true;
            if ($statut->est_visible === null) $statut->est_visible = true;
        });
    }
}
