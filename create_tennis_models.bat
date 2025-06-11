@echo off
REM =============================================================================
REM CRÉATION DE TOUS LES MODÈLES TENNIS AI - WINDOWS + PHPSTORM
REM =============================================================================

echo 🎾 Création des modèles Tennis AI sous Windows...
echo.

REM =============================================================================
REM MODÈLES PRINCIPAUX (9 modèles)
REM =============================================================================

echo 📊 Création des modèles principaux...

REM 1. Joueur
php artisan make:model Joueur -mfsc
echo ✅ Joueur créé

REM 2. Tournoi
php artisan make:model Tournoi -mfsc
echo ✅ Tournoi créé

REM 3. MatchTennis (attention au mot-clé réservé 'match')
php artisan make:model MatchTennis -mfsc
echo ✅ MatchTennis créé

REM 4. StatistiqueJoueur
php artisan make:model StatistiqueJoueur -mfsc
echo ✅ StatistiqueJoueur créé

REM 5. Confrontation
php artisan make:model Confrontation -mfsc
echo ✅ Confrontation créé

REM 6. Prediction
php artisan make:model Prediction -mfsc
echo ✅ Prediction créé

REM 7. FormeRecente
php artisan make:model FormeRecente -mfsc
echo ✅ FormeRecente créé

REM 8. Blessure
php artisan make:model Blessure -mfsc
echo ✅ Blessure créé

REM 9. ConfigurationIA
php artisan make:model ConfigurationIA -mfsc
echo ✅ ConfigurationIA créé

echo.

REM =============================================================================
REM MODÈLES DE RÉFÉRENCE (15 modèles)
REM =============================================================================

echo 🗃️ Création des modèles de référence...

REM DONNÉES DE BASE
REM 10. Surface
php artisan make:model Surface -mfsc
echo ✅ Surface créé

REM 11. Pays
php artisan make:model Pays -mfsc
echo ✅ Pays créé

REM 12. Saison
php artisan make:model Saison -mfsc
echo ✅ Saison créé

REM STRUCTURE TOURNOIS
REM 13. CategorieTournoi
php artisan make:model CategorieTournoi -mfsc
echo ✅ CategorieTournoi créé

REM 14. RoundTournoi
php artisan make:model RoundTournoi -mfsc
echo ✅ RoundTournoi créé

REM 15. VitesseSurface
php artisan make:model VitesseSurface -mfsc
echo ✅ VitesseSurface créé

REM CONDITIONS & CONTEXTE
REM 16. ConditionMeteo
php artisan make:model ConditionMeteo -mfsc
echo ✅ ConditionMeteo créé

REM 17. StatutMatch
php artisan make:model StatutMatch -mfsc
echo ✅ StatutMatch créé

REM BLESSURES & SANTÉ
REM 18. TypeBlessure
php artisan make:model TypeBlessure -mfsc
echo ✅ TypeBlessure créé

REM 19. ZoneCorporelle
php artisan make:model ZoneCorporelle -mfsc
echo ✅ ZoneCorporelle créé

REM IA & PRÉDICTIONS
REM 20. AlgorithmeIA
php artisan make:model AlgorithmeIA -mfsc
echo ✅ AlgorithmeIA créé

REM 21. TypePrediction
php artisan make:model TypePrediction -mfsc
echo ✅ TypePrediction créé

REM 22. NiveauJoueur
php artisan make:model NiveauJoueur -mfsc
echo ✅ NiveauJoueur créé

REM GESTION DONNÉES
REM 23. SourceDonnees
php artisan make:model SourceDonnees -mfsc
echo ✅ SourceDonnees créé

REM 24. ImportDonnees
php artisan make:model ImportDonnees -mfsc
echo ✅ ImportDonnees créé

echo.

REM =============================================================================
REM MODÈLES COMPLÉMENTAIRES
REM =============================================================================

echo 🔧 Création des modèles complémentaires...

REM StatistiqueMatch (détails par match)
php artisan make:model StatistiqueMatch -mfsc
echo ✅ StatistiqueMatch créé

REM HistoriqueClassement (évolution classements)
php artisan make:model HistoriqueClassement -mfsc
echo ✅ HistoriqueClassement créé

REM LogPrediction (logs des prédictions)
php artisan make:model LogPrediction -mfsc
echo ✅ LogPrediction créé

echo.

REM =============================================================================
REM COMMANDES SPÉCIALISÉES TENNIS AI
REM =============================================================================

echo 🤖 Création des commandes spécialisées...

REM Commandes pour l'import de données
php artisan make:command Tennis/ImportHistoricalData
php artisan make:command Tennis/SyncDailyData
php artisan make:command Tennis/SyncRankings
php artisan make:command Tennis/UpdatePlayerStats
php artisan make:command Tennis/ValidateDataQuality
php artisan make:command Tennis/TrainIAModel
php artisan make:command Tennis/GeneratePredictions
echo ✅ Commandes Tennis créées

REM Jobs pour traitement asynchrone
php artisan make:job Tennis/ImportTournamentData
php artisan make:job Tennis/ProcessMatchStatistics
php artisan make:job Tennis/UpdatePlayerRankings
php artisan make:job Tennis/TrainPredictionModel
php artisan make:job Tennis/AnalyzePredictionAccuracy
echo ✅ Jobs Tennis créés

REM Requests de validation
php artisan make:request StoreJoueurRequest
php artisan make:request UpdateJoueurRequest
php artisan make:request StoreTournoiRequest
php artisan make:request UpdateTournoiRequest
php artisan make:request StoreMatchTennisRequest
php artisan make:request UpdateMatchTennisRequest
php artisan make:request StorePredictionRequest
php artisan make:request UpdatePredictionRequest
echo ✅ Form Requests créés

echo.

REM =============================================================================
REM RÉSUMÉ
REM =============================================================================

echo 🎯 RÉSUMÉ DES CRÉATIONS :
echo 📊 Modèles principaux: 9
echo 🗃️ Modèles de référence: 15
echo 🔧 Modèles complémentaires: 3
echo 📝 Total modèles: 27
echo.
echo 🔧 Commandes Tennis: 7
echo ⚙️ Jobs Tennis: 5
echo 📋 Form Requests: 8
echo.
echo ✅ TOUS LES MODÈLES TENNIS AI CRÉÉS !
echo.

REM =============================================================================
REM INSTRUCTIONS PHPSTORM
REM =============================================================================

echo 🎛️ CONFIGURATION PHPSTORM :
echo 1. Installer le plugin Laravel
echo 2. Configurer l'interpréteur PHP
echo 3. Activer le support Composer
echo 4. Configurer la base de données
echo.

echo 📁 FICHIERS CRÉÉS DANS :
echo - app\Models\
echo - database\migrations\
echo - database\factories\
echo - database\seeders\
echo - app\Http\Controllers\
echo - app\Console\Commands\Tennis\
echo - app\Jobs\Tennis\
echo - app\Http\Requests\
echo.

echo 🚀 PROCHAINES ÉTAPES :
echo 1. Ouvrir PHPStorm et actualiser le projet
echo 2. Configurer les migrations
echo 3. Définir les relations dans les modèles
echo 4. Implémenter les controllers
echo 5. Créer les routes
echo.

pause
