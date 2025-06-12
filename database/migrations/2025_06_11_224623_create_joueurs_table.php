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
        Schema::create('joueurs', function (Blueprint $table) {
            $table->id();

            // ===================================================================
            // INFORMATIONS PERSONNELLES DE BASE
            // ===================================================================

            $table->string('nom', 100);
            $table->string('prenom', 100);
            $table->string('nom_complet_display', 200)->nullable();
            $table->string('surnom', 100)->nullable();
            $table->foreignId('pays_id')->constrained('pays');
            $table->foreignId('pays_residence_id')->nullable()->constrained('pays');
            $table->string('ville_naissance', 100)->nullable();
            $table->string('ville_residence', 100)->nullable();
            $table->date('date_naissance');
            $table->enum('sexe', ['M', 'F']);
            $table->json('nationalites_multiples')->nullable();

            // ===================================================================
            // CARACTÉRISTIQUES PHYSIQUES ET STYLE
            // ===================================================================

            $table->enum('main', ['droitier', 'gaucher']);
            $table->enum('revers', ['une_main', 'deux_mains']);
            $table->unsignedSmallInteger('taille')->comment('cm');
            $table->unsignedSmallInteger('poids')->comment('kg');
            $table->unsignedSmallInteger('envergure')->nullable()->comment('cm');
            $table->unsignedSmallInteger('allonge')->nullable()->comment('cm');
            $table->string('groupe_sanguin', 5)->nullable();
            $table->decimal('imc', 4, 2)->nullable();

            // ===================================================================
            // STYLE DE JEU ET TACTIQUE
            // ===================================================================

            $table->enum('style_jeu_principal', ['baseline', 'serve_volley', 'all_court', 'counterpuncher'])->nullable();
            $table->enum('style_jeu_secondaire', ['baseline', 'serve_volley', 'all_court', 'counterpuncher'])->nullable();
            $table->enum('position_court_favorite', ['fond', 'mi_court', 'filet'])->nullable();
            $table->unsignedTinyInteger('agressivite_jeu')->nullable()->comment('1-10');
            $table->unsignedTinyInteger('vitesse_deplacement')->nullable()->comment('1-10');
            $table->unsignedTinyInteger('endurance_niveau')->nullable()->comment('1-10');
            $table->unsignedTinyInteger('force_mentale')->nullable()->comment('1-10');
            $table->unsignedTinyInteger('regularite_niveau')->nullable()->comment('1-10');
            $table->unsignedTinyInteger('punch_niveau')->nullable()->comment('1-10');

            // ===================================================================
            // PRÉFÉRENCES ET PERFORMANCES SURFACES
            // ===================================================================

            $table->enum('surface_favorite', ['dur', 'terre', 'gazon', 'indoor'])->nullable();
            $table->enum('surface_detestee', ['dur', 'terre', 'gazon', 'indoor'])->nullable();
            $table->decimal('performance_dur', 5, 1)->nullable()->comment('0-100');
            $table->decimal('performance_terre', 5, 1)->nullable()->comment('0-100');
            $table->decimal('performance_gazon', 5, 1)->nullable()->comment('0-100');
            $table->decimal('performance_indoor', 5, 1)->nullable()->comment('0-100');
            $table->enum('vitesse_surface_preferee', ['lente', 'moyenne', 'rapide'])->nullable();

            // ===================================================================
            // CONDITIONS DE JEU OPTIMALES
            // ===================================================================

            $table->decimal('temperature_optimale', 4, 1)->nullable()->comment('°C');
            $table->unsignedTinyInteger('tolere_vent')->nullable()->comment('1-10');
            $table->unsignedTinyInteger('tolere_chaleur')->nullable()->comment('1-10');
            $table->unsignedTinyInteger('tolere_froid')->nullable()->comment('1-10');
            $table->enum('prefere_jour_nuit', ['jour', 'nuit', 'indifferent'])->nullable();
            $table->unsignedTinyInteger('performance_altitude')->nullable()->comment('1-10');

            // ===================================================================
            // CLASSEMENTS ET POINTS
            // ===================================================================

            $table->unsignedInteger('classement_atp_wta')->nullable();
            $table->unsignedInteger('classement_precedent')->nullable();
            $table->unsignedInteger('meilleur_classement')->nullable();
            $table->unsignedInteger('pire_classement')->nullable();
            $table->unsignedInteger('points_actuels')->nullable();
            $table->unsignedInteger('points_precedents')->nullable();
            $table->unsignedInteger('points_race')->nullable();
            $table->decimal('elo_rating_global', 6, 1)->nullable()->default(1500);
            $table->decimal('elo_dur', 6, 1)->nullable()->default(1500);
            $table->decimal('elo_terre', 6, 1)->nullable()->default(1500);
            $table->decimal('elo_gazon', 6, 1)->nullable()->default(1500);
            $table->foreignId('niveau_joueur_id')->nullable()->constrained('niveau_joueurs');

            // ===================================================================
            // STATISTIQUES CARRIÈRE ÉTENDUES
            // ===================================================================

            $table->unsignedInteger('victoires_saison')->default(0);
            $table->unsignedInteger('defaites_saison')->default(0);
            $table->unsignedInteger('victoires_carriere')->default(0);
            $table->unsignedInteger('defaites_carriere')->default(0);
            $table->unsignedInteger('titres_carriere')->default(0);
            $table->unsignedInteger('titres_saison')->default(0);
            $table->unsignedInteger('finales_carriere')->default(0);
            $table->unsignedInteger('finales_saison')->default(0);
            $table->unsignedInteger('demi_finales_carriere')->default(0);
            $table->decimal('prize_money_carriere', 12, 2)->default(0);
            $table->decimal('prize_money_saison', 12, 2)->default(0);

            // ===================================================================
            // STATISTIQUES PAR NIVEAU TOURNOI
            // ===================================================================

            $table->unsignedInteger('titres_grand_chelem')->default(0);
            $table->unsignedInteger('finales_grand_chelem')->default(0);
            $table->unsignedInteger('titres_masters_1000')->default(0);
            $table->unsignedInteger('finales_masters_1000')->default(0);
            $table->unsignedInteger('titres_atp_500')->default(0);
            $table->unsignedInteger('titres_atp_250')->default(0);

            // ===================================================================
            // RECORDS ET ACHIEVEMENTS
            // ===================================================================

            $table->unsignedInteger('plus_long_match')->nullable()->comment('minutes');
            $table->unsignedInteger('plus_court_match')->nullable()->comment('minutes');
            $table->unsignedInteger('serie_victoires_max')->default(0);
            $table->unsignedInteger('serie_defaites_max')->default(0);
            $table->unsignedInteger('nb_tie_breaks_gagnes')->default(0);
            $table->unsignedInteger('nb_tie_breaks_perdus')->default(0);
            $table->string('record_vs_top_10', 20)->nullable()->comment('format V-D');
            $table->string('record_vs_top_50', 20)->nullable()->comment('format V-D');
            $table->string('record_vs_top_100', 20)->nullable()->comment('format V-D');

            // ===================================================================
            // FORME ET CONDITION
            // ===================================================================

            $table->unsignedTinyInteger('forme_actuelle')->nullable()->comment('1-10');
            $table->unsignedTinyInteger('confiance_niveau')->nullable()->comment('1-10');
            $table->unsignedTinyInteger('motivation_niveau')->nullable()->comment('1-10');
            $table->unsignedTinyInteger('fatigue_niveau')->nullable()->comment('1-10');
            $table->unsignedTinyInteger('stress_niveau')->nullable()->comment('1-10');
            $table->date('derniere_evaluation_forme')->nullable();

            // ===================================================================
            // ÉQUIPE ET STAFF
            // ===================================================================

            $table->string('entraineur_principal', 100)->nullable();
            $table->string('entraineur_physique', 100)->nullable();
            $table->string('entraineur_mental', 100)->nullable();
            $table->string('manager', 100)->nullable();
            $table->string('medecin', 100)->nullable();
            $table->string('physiotherapeute', 100)->nullable();
            $table->string('academie_formation', 100)->nullable();
            $table->string('sponsor_principal', 100)->nullable();
            $table->string('equipementier', 100)->nullable();

            // ===================================================================
            // MATÉRIEL ET ÉQUIPEMENT
            // ===================================================================

            $table->string('marque_raquette', 50)->nullable();
            $table->string('modele_raquette', 100)->nullable();
            $table->unsignedSmallInteger('poids_raquette')->nullable()->comment('grammes');
            $table->unsignedTinyInteger('tension_cordage')->nullable()->comment('kg');
            $table->string('type_cordage', 100)->nullable();
            $table->string('marque_chaussures', 50)->nullable();
            $table->string('type_grip', 50)->nullable();
            $table->string('marque_vetements', 50)->nullable();

            // ===================================================================
            // DONNÉES FINANCIÈRES ÉTENDUES
            // ===================================================================

            $table->decimal('prize_money', 12, 2)->nullable()->comment('legacy field');
            $table->decimal('salaire_annuel_estime', 12, 2)->nullable();
            $table->decimal('valeur_sponsoring', 12, 2)->nullable();
            $table->decimal('cout_equipe_annuel', 12, 2)->nullable();
            $table->decimal('investissement_formation', 12, 2)->nullable();

            // ===================================================================
            // BLESSURES ET SANTÉ
            // ===================================================================

            $table->json('historique_blessures_majeures')->nullable();
            $table->json('zones_fragiles')->nullable();
            $table->json('allergies')->nullable();
            $table->json('traitements_medicaux')->nullable();
            $table->date('derniere_visite_medicale')->nullable();
            $table->enum('aptitude_medicale', ['apte', 'apte_reserve', 'inapte'])->default('apte');

            // ===================================================================
            // ANALYSE COMPORTEMENTALE
            // ===================================================================

            $table->enum('temperament', ['calme', 'explosif', 'variable'])->nullable();
            $table->unsignedTinyInteger('gestion_pression')->nullable()->comment('1-10');
            $table->unsignedTinyInteger('leadership')->nullable()->comment('1-10');
            $table->unsignedTinyInteger('fair_play')->nullable()->comment('1-10');
            $table->unsignedTinyInteger('media_relations')->nullable()->comment('1-10');
            $table->unsignedTinyInteger('popularite_fans')->nullable()->comment('1-10');
            $table->unsignedTinyInteger('charisma')->nullable()->comment('1-10');

            // ===================================================================
            // DONNÉES TECHNIQUES AVANCÉES
            // ===================================================================

            $table->unsignedSmallInteger('vitesse_service_max')->nullable()->comment('km/h');
            $table->unsignedSmallInteger('vitesse_service_moyenne')->nullable()->comment('km/h');
            $table->unsignedSmallInteger('vitesse_coup_droit_max')->nullable()->comment('km/h');
            $table->unsignedSmallInteger('vitesse_revers_max')->nullable()->comment('km/h');
            $table->decimal('precision_service', 5, 2)->nullable()->comment('pourcentage zones');
            $table->unsignedTinyInteger('puissance_frappe')->nullable()->comment('1-10');
            $table->unsignedTinyInteger('qualite_retour')->nullable()->comment('1-10');
            $table->unsignedTinyInteger('jeu_filet')->nullable()->comment('1-10');
            $table->unsignedTinyInteger('anticipation')->nullable()->comment('1-10');
            $table->unsignedTinyInteger('reactivite')->nullable()->comment('1-10');

            // ===================================================================
            // STATUT ET CARRIÈRE
            // ===================================================================

            $table->enum('statut', ['actif', 'inactif', 'retraite', 'suspendu'])->default('actif');
            $table->date('date_debut_pro')->nullable();
            $table->date('date_retraite')->nullable();
            $table->unsignedTinyInteger('annees_experience')->nullable();
            $table->boolean('pic_carriere_atteint')->default(false);
            $table->enum('phase_carriere', ['montee', 'pic', 'plateau', 'declin'])->nullable();

            // ===================================================================
            // OBJECTIFS ET PROJECTIONS
            // ===================================================================

            $table->unsignedInteger('objectif_classement')->nullable();
            $table->json('objectif_tournois')->nullable();
            $table->decimal('potentiel_estime', 5, 1)->nullable()->comment('1-100');
            $table->enum('progression_prevue', ['hausse', 'stable', 'baisse'])->nullable();
            $table->year('retraite_estimee')->nullable();

            // ===================================================================
            // DONNÉES SOCIALES ET MARKETING
            // ===================================================================

            $table->unsignedInteger('followers_instagram')->default(0);
            $table->unsignedInteger('followers_twitter')->default(0);
            $table->unsignedInteger('followers_total')->default(0);
            $table->decimal('engagement_social', 5, 2)->nullable()->comment('taux engagement');
            $table->decimal('valeur_marketing', 12, 2)->nullable();
            $table->json('langues_parlees')->nullable();
            $table->json('pays_fan_base')->nullable();

            // ===================================================================
            // MÉTADONNÉES SYSTÈME
            // ===================================================================

            $table->string('photo_url', 500)->nullable();
            $table->json('photos_galerie')->nullable();
            $table->json('videos_highlights')->nullable();
            $table->timestamp('derniere_maj_stats')->nullable();
            $table->timestamp('derniere_maj_classement')->nullable();
            $table->string('source_donnees_principal', 100)->nullable();
            $table->unsignedTinyInteger('fiabilite_donnees')->nullable()->comment('1-10');
            $table->boolean('actif')->default(true);

            // ===================================================================
            // TIMESTAMPS ET SOFT DELETES
            // ===================================================================

            $table->timestamps();
            $table->softDeletes();

            // ===================================================================
            // INDEX POUR PERFORMANCES
            // ===================================================================

            $table->index(['classement_atp_wta', 'sexe'], 'idx_classement_sexe');
            $table->index(['surface_favorite', 'statut'], 'idx_surface_statut');
            $table->index(['forme_actuelle', 'actif'], 'idx_forme_actif');
            $table->index(['pays_id', 'statut'], 'idx_pays_statut');
            $table->index(['date_naissance'], 'idx_age');
            $table->index(['elo_rating_global'], 'idx_elo_global');
            $table->index(['phase_carriere', 'potentiel_estime'], 'idx_carriere_potentiel');
            $table->index(['titres_carriere'], 'idx_titres');
            $table->index(['prize_money_carriere'], 'idx_prize_money');
            $table->index(['created_at'], 'idx_created');
            $table->index(['updated_at'], 'idx_updated');

            // Index composites pour requêtes IA fréquentes
            $table->index(['sexe', 'classement_atp_wta', 'surface_favorite'], 'idx_ia_predictions');
            $table->index(['statut', 'forme_actuelle', 'elo_rating_global'], 'idx_ia_performance');
            $table->index(['phase_carriere', 'age', 'potentiel_estime'], 'idx_ia_evolution');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('joueurs');
    }
};
