<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('match_tennis', function (Blueprint $table) {
            $table->id();

            // ===================================================================
            // IDENTIFICATION ET PARTICIPANTS
            // ===================================================================

            $table->string('nom_match', 200)->nullable();
            $table->string('code_match', 50)->unique()->nullable();
            $table->string('identifiant_externe', 100)->nullable()->comment('ID source externe');
            $table->foreignId('joueur1_id')->constrained('joueurs');
            $table->foreignId('joueur2_id')->constrained('joueurs');
            $table->foreignId('gagnant_id')->nullable()->constrained('joueurs');
            $table->foreignId('perdant_id')->nullable()->constrained('joueurs');
            $table->enum('type_match', ['singles', 'doubles'])->default('singles');

            // ===================================================================
            // CONTEXTE TOURNOI ET COMPÉTITION
            // ===================================================================

            $table->foreignId('tournoi_id')->constrained('tournois');
            $table->string('phase_tournoi', 100)->nullable()->comment('finale, demi-finale, quart, etc.');
            $table->enum('importance_match', [
                'finale', 'demi_finale', 'quart_finale', 'huitieme_finale',
                'troisieme_tour', 'deuxieme_tour', 'premier_tour',
                'qualifications', 'exhibition'
            ])->nullable();
            $table->unsignedTinyInteger('numero_tour')->nullable()->comment('Numéro du tour');
            $table->boolean('match_titre')->default(false)->comment('Match pour un titre');
            $table->decimal('prize_money_gagnant', 12, 2)->nullable();
            $table->decimal('prize_money_perdant', 12, 2)->nullable();
            $table->unsignedSmallInteger('points_atp_gagnant')->nullable();
            $table->unsignedSmallInteger('points_atp_perdant')->nullable();

            // ===================================================================
            // PROGRAMMATION ET TIMING
            // ===================================================================

            $table->date('date_match');
            $table->time('heure_debut')->nullable();
            $table->time('heure_fin')->nullable();
            $table->unsignedSmallInteger('duree_minutes')->nullable();
            $table->unsignedTinyInteger('duree_heures')->nullable();
            $table->unsignedTinyInteger('duree_jeu_effectif')->nullable()->comment('temps jeu réel');
            $table->enum('session', ['day', 'night', 'afternoon', 'evening'])->nullable();
            $table->boolean('match_reporte')->default(false);
            $table->string('raison_report', 200)->nullable();
            $table->boolean('match_suspendu')->default(false);
            $table->text('raison_suspension')->nullable();

            // ===================================================================
            // SURFACE ET CONDITIONS
            // ===================================================================

            $table->enum('surface', ['dur', 'terre', 'gazon', 'indoor', 'carpet'])->nullable();
            $table->string('type_surface_detail', 100)->nullable()->comment('Plexi, terre battue rouge, etc.');
            $table->enum('vitesse_surface', ['très_lente', 'lente', 'moyenne', 'rapide', 'très_rapide'])->nullable();
            $table->boolean('surface_couverte')->default(false);
            $table->boolean('surface_exterieure')->default(true);
            $table->string('nom_court', 100)->nullable();
            $table->unsignedSmallInteger('capacite_court')->nullable();
            $table->decimal('altitude_m', 6, 1)->nullable()->comment('mètres');

            // ===================================================================
            // CONDITIONS MÉTÉOROLOGIQUES
            // ===================================================================

            $table->decimal('temperature_debut', 4, 1)->nullable()->comment('°C');
            $table->decimal('temperature_fin', 4, 1)->nullable()->comment('°C');
            $table->decimal('temperature_moyenne', 4, 1)->nullable()->comment('°C');
            $table->unsignedTinyInteger('humidite_pourcent')->nullable();
            $table->decimal('vitesse_vent_kmh', 4, 1)->nullable();
            $table->enum('direction_vent', ['N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW'])->nullable();
            $table->enum('conditions_meteo', [
                'ensoleille', 'nuageux', 'couvert', 'pluie_legere',
                'pluie_forte', 'orage', 'brouillard', 'vent_fort'
            ])->nullable();
            $table->boolean('interruption_pluie')->default(false);
            $table->unsignedSmallInteger('duree_interruption_min')->nullable();
            $table->text('conditions_particulieres')->nullable();

            // ===================================================================
            // SCORE ET RÉSULTAT
            // ===================================================================

            $table->string('score_final', 50)->nullable()->comment('6-4, 7-6, 6-2');
            $table->string('score_detaille', 200)->nullable()->comment('Score complet avec tie-breaks');
            $table->unsignedTinyInteger('sets_gagnant')->nullable();
            $table->unsignedTinyInteger('sets_perdant')->nullable();
            $table->unsignedTinyInteger('nb_sets_joues')->nullable();
            $table->enum('format_sets', ['3_sets', '5_sets'])->default('3_sets');

            // Score par set détaillé
            $table->string('set1_score', 20)->nullable();
            $table->string('set2_score', 20)->nullable();
            $table->string('set3_score', 20)->nullable();
            $table->string('set4_score', 20)->nullable();
            $table->string('set5_score', 20)->nullable();

            // Tie-breaks
            $table->string('set1_tiebreak', 20)->nullable();
            $table->string('set2_tiebreak', 20)->nullable();
            $table->string('set3_tiebreak', 20)->nullable();
            $table->string('set4_tiebreak', 20)->nullable();
            $table->string('set5_tiebreak', 20)->nullable();

            $table->unsignedTinyInteger('nb_tiebreaks')->default(0);
            $table->boolean('set_decisif_joue')->default(false);

            // ===================================================================
            // STATUT ET DÉROULEMENT
            // ===================================================================

            $table->enum('statut_match', [
                'programme', 'en_cours', 'termine', 'suspendu',
                'reporte', 'annule', 'forfait', 'walkover'
            ])->default('programme');
            $table->enum('type_victoire', [
                'normale', 'forfait', 'walkover', 'disqualification',
                'abandon_blessure', 'abandon_autre'
            ])->nullable();
            $table->string('raison_arret', 200)->nullable();
            $table->boolean('match_officiel')->default(true);
            $table->boolean('match_comptabilise')->default(true);
            $table->text('commentaires_arbitre')->nullable();

            // ===================================================================
            // ARBITRAGE ET OFFICIELS
            // ===================================================================

            $table->string('arbitre_chaise', 100)->nullable();
            $table->string('arbitre_ligne_1', 100)->nullable();
            $table->string('arbitre_ligne_2', 100)->nullable();
            $table->string('juge_filet', 100)->nullable();
            $table->string('superviseur', 100)->nullable();
            $table->boolean('hawkeye_disponible')->default(false);
            $table->unsignedTinyInteger('challenges_joueur1')->default(0);
            $table->unsignedTinyInteger('challenges_joueur2')->default(0);
            $table->unsignedTinyInteger('challenges_reussis_j1')->default(0);
            $table->unsignedTinyInteger('challenges_reussis_j2')->default(0);

            // ===================================================================
            // STATISTIQUES MATCH GÉNÉRALES
            // ===================================================================

            $table->unsignedSmallInteger('total_points_joues')->nullable();
            $table->unsignedSmallInteger('total_jeux_joues')->nullable();
            $table->unsignedSmallInteger('points_gagnant')->nullable();
            $table->unsignedSmallInteger('points_perdant')->nullable();
            $table->unsignedSmallInteger('jeux_gagnant')->nullable();
            $table->unsignedSmallInteger('jeux_perdant')->nullable();
            $table->decimal('pourcentage_points_gagnant', 5, 2)->nullable();
            $table->decimal('pourcentage_points_perdant', 5, 2)->nullable();

            // ===================================================================
            // DONNÉES TECHNIQUES ET CAPTEURS
            // ===================================================================

            $table->json('donnees_hawkeye')->nullable()->comment('Données techniques Hawk-Eye');
            $table->json('statistiques_vitesse')->nullable()->comment('Vitesses services, coups');
            $table->json('trajectoires_balles')->nullable()->comment('Données trajectoires');
            $table->json('positions_joueurs')->nullable()->comment('Heatmap positions');
            $table->json('donnees_biometriques')->nullable()->comment('Fréquence cardiaque, etc.');
            $table->decimal('distance_parcourue_j1', 8, 2)->nullable()->comment('mètres');
            $table->decimal('distance_parcourue_j2', 8, 2)->nullable()->comment('mètres');
            $table->unsignedSmallInteger('calories_j1')->nullable();
            $table->unsignedSmallInteger('calories_j2')->nullable();

            // ===================================================================
            // IMPACT CLASSEMENTS ET ELO
            // ===================================================================

            $table->decimal('elo_j1_avant', 7, 2)->nullable();
            $table->decimal('elo_j2_avant', 7, 2)->nullable();
            $table->decimal('elo_j1_apres', 7, 2)->nullable();
            $table->decimal('elo_j2_apres', 7, 2)->nullable();
            $table->decimal('variation_elo_j1', 6, 2)->nullable();
            $table->decimal('variation_elo_j2', 6, 2)->nullable();
            $table->unsignedInteger('classement_j1_avant')->nullable();
            $table->unsignedInteger('classement_j2_avant')->nullable();
            $table->unsignedInteger('classement_j1_apres')->nullable();
            $table->unsignedInteger('classement_j2_apres')->nullable();

            // ===================================================================
            // ANALYSE ET PRÉDICTIONS
            // ===================================================================

            $table->decimal('probabilite_victoire_j1', 5, 2)->nullable()->comment('% avant match');
            $table->decimal('probabilite_victoire_j2', 5, 2)->nullable()->comment('% avant match');
            $table->string('favori_avant_match', 20)->nullable()->comment('joueur1 ou joueur2');
            $table->decimal('cote_j1', 6, 2)->nullable()->comment('Cote bookmaker');
            $table->decimal('cote_j2', 6, 2)->nullable()->comment('Cote bookmaker');
            $table->boolean('surprise_resultat')->default(false);
            $table->decimal('facteur_surprise', 4, 2)->nullable()->comment('Niveau surprise 0-10');
            $table->json('facteurs_cles_victoire')->nullable();
            $table->json('moments_decisifs')->nullable()->comment('Points/jeux clés');

            // ===================================================================
            // CONTEXTE FACE-À-FACE
            // ===================================================================

            $table->unsignedTinyInteger('h2h_avant_j1')->nullable()->comment('Victoires J1 en H2H');
            $table->unsignedTinyInteger('h2h_avant_j2')->nullable()->comment('Victoires J2 en H2H');
            $table->unsignedTinyInteger('h2h_apres_j1')->nullable()->comment('Victoires J1 après ce match');
            $table->unsignedTinyInteger('h2h_apres_j2')->nullable()->comment('Victoires J2 après ce match');
            $table->string('derniere_confrontation', 100)->nullable();
            $table->enum('h2h_surface_trend', ['j1_domine', 'j2_domine', 'equilibre'])->nullable();

            // ===================================================================
            // DONNÉES DIFFUSION ET AUDIENCE
            // ===================================================================

            $table->boolean('diffuse_tv')->default(false);
            $table->json('chaines_diffusion')->nullable();
            $table->boolean('streaming_disponible')->default(false);
            $table->unsignedInteger('audience_tv')->nullable();
            $table->unsignedInteger('viewers_streaming')->nullable();
            $table->string('langue_commentaire', 10)->nullable();
            $table->json('commentateurs')->nullable();
            $table->boolean('match_featured')->default(false);

            // ===================================================================
            // DONNÉES MARKETING ET BUSINESS
            // ===================================================================

            $table->decimal('revenus_billetterie', 12, 2)->nullable();
            $table->decimal('revenus_sponsoring', 12, 2)->nullable();
            $table->decimal('revenus_diffusion', 12, 2)->nullable();
            $table->unsignedSmallInteger('spectateurs_presents')->nullable();
            $table->decimal('taux_remplissage', 5, 2)->nullable()->comment('% capacité');
            $table->json('sponsors_visibles')->nullable();
            $table->decimal('valeur_marketing_estime', 12, 2)->nullable();

            // ===================================================================
            // ANALYSE POST-MATCH
            // ===================================================================

            $table->json('analyse_tactique')->nullable();
            $table->json('points_forts_gagnant')->nullable();
            $table->json('points_faibles_perdant')->nullable();
            $table->json('facteurs_victoire')->nullable();
            $table->text('resume_match')->nullable();
            $table->decimal('note_qualite_match', 3, 1)->nullable()->comment('Note 0-10');
            $table->decimal('note_spectacle', 3, 1)->nullable()->comment('Note spectacle 0-10');
            $table->json('moments_marquants')->nullable();

            // ===================================================================
            // IMPACT CARRIÈRE
            // ===================================================================

            $table->boolean('record_personnel_j1')->default(false);
            $table->boolean('record_personnel_j2')->default(false);
            $table->boolean('premier_titre_j1')->default(false);
            $table->boolean('premier_titre_j2')->default(false);
            $table->boolean('milestone_carriere')->default(false);
            $table->string('type_milestone', 100)->nullable();
            $table->json('records_battus')->nullable();
            $table->json('statistiques_historiques')->nullable();

            // ===================================================================
            // DONNÉES TECHNIQUES IA
            // ===================================================================

            $table->json('features_pre_match')->nullable()->comment('Features avant match pour IA');
            $table->json('features_post_match')->nullable()->comment('Features calculées après');
            $table->decimal('difficulte_prediction', 4, 2)->nullable()->comment('Difficulté 0-10');
            $table->decimal('valeur_apprentissage', 4, 2)->nullable()->comment('Valeur pour ML');
            $table->boolean('outlier_detecte')->default(false);
            $table->json('anomalies_detectees')->nullable();
            $table->decimal('confiance_donnees', 4, 2)->nullable()->comment('Confiance qualité 0-10');

            // ===================================================================
            // MÉTADONNÉES ET SOURCES
            // ===================================================================

            $table->string('source_donnees', 100)->nullable();
            $table->string('url_source', 500)->nullable();
            $table->json('sources_multiples')->nullable();
            $table->timestamp('derniere_maj_score')->nullable();
            $table->timestamp('derniere_maj_stats')->nullable();
            $table->timestamp('verification_donnees')->nullable();
            $table->boolean('donnees_validees')->default(false);
            $table->string('validateur', 100)->nullable();
            $table->decimal('score_fiabilite', 4, 2)->nullable()->comment('Fiabilité 0-10');

            // ===================================================================
            // LIENS ET MÉDIAS
            // ===================================================================

            $table->string('url_video_highlights', 500)->nullable();
            $table->string('url_video_complet', 500)->nullable();
            $table->json('photos_match')->nullable();
            $table->json('articles_presse')->nullable();
            $table->json('reactions_joueurs')->nullable();
            $table->json('citations_marquantes')->nullable();
            $table->string('hashtag_officiel', 100)->nullable();

            // ===================================================================
            // GESTION VERSIONS ET SYNCHRONISATION
            // ===================================================================

            $table->string('version_donnees', 20)->default('1.0');
            $table->json('historique_modifications')->nullable();
            $table->foreignId('import_donnees_id')->nullable()->constrained('import_donnees');
            $table->timestamp('sync_externe')->nullable();
            $table->string('checksum_donnees', 64)->nullable();
            $table->boolean('necessite_validation')->default(false);
            $table->text('notes_validation')->nullable();

            // ===================================================================
            // TIMESTAMPS ET SOFT DELETES
            // ===================================================================

            $table->timestamps();
            $table->softDeletes();

            // ===================================================================
            // INDEX POUR PERFORMANCES ET REQUÊTES IA
            // ===================================================================

            // Index de base
            $table->index(['date_match'], 'idx_date_match');
            $table->index(['statut_match'], 'idx_statut');
            $table->index(['surface'], 'idx_surface');
            $table->index(['tournoi_id', 'phase_tournoi'], 'idx_tournoi_phase');
            $table->index(['importance_match'], 'idx_importance');
            $table->index(['gagnant_id', 'date_match'], 'idx_gagnant_date');

            // Index pour les joueurs
            $table->index(['joueur1_id', 'joueur2_id'], 'idx_participants');
            $table->index(['joueur1_id', 'date_match'], 'idx_j1_date');
            $table->index(['joueur2_id', 'date_match'], 'idx_j2_date');

            // Index pour analyses statistiques
            $table->index(['surface', 'date_match'], 'idx_surface_date');
            $table->index(['format_sets', 'nb_sets_joues'], 'idx_format_sets');
            $table->index(['duree_minutes'], 'idx_duree');
            $table->index(['note_qualite_match'], 'idx_qualite');

            // Index pour l'IA et prédictions
            $table->index(['surface', 'importance_match', 'statut_match'], 'idx_ia_contexte');
            $table->index(['surprise_resultat', 'facteur_surprise'], 'idx_ia_surprise');
            $table->index(['difficulte_prediction', 'valeur_apprentissage'], 'idx_ia_learning');
            $table->index(['elo_j1_avant', 'elo_j2_avant'], 'idx_ia_elo');

            // Index pour performance et monitoring
            $table->index(['donnees_validees', 'score_fiabilite'], 'idx_qualite_donnees');
            $table->index(['created_at'], 'idx_created');
            $table->index(['updated_at'], 'idx_updated');
            $table->index(['import_donnees_id'], 'idx_import');

            // Index composites complexes pour requêtes IA fréquentes
            $table->index([
                'surface', 'importance_match', 'joueur1_id', 'joueur2_id', 'date_match'
            ], 'idx_ia_prediction_context');

            $table->index([
                'statut_match', 'donnees_validees', 'surface', 'date_match'
            ], 'idx_ia_training_data');

            $table->index([
                'gagnant_id', 'surface', 'importance_match', 'date_match'
            ], 'idx_ia_player_performance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_tennis');
    }
};
