<?php
/**
 * TENNIS AI APPLICATION CONTEXT
 * =============================
 *
 * DOMAIN: Professional Tennis (ATP/WTA/ITF)
 * TECH STACK: Laravel 11, PHP 8.2+, RubixML, MySQL
 *
 * CORE MODELS:
 * - Joueur: Professional tennis players with rankings, stats
 * - MatchTennis: Tennis matches with scores, conditions, predictions
 * - Tournoi: ATP/WTA tournaments (Grand Slams, Masters, etc.)
 * - Prediction: AI-powered match outcome predictions
 * - StatistiqueJoueur: Detailed player statistics by surface
 * - Confrontation: Head-to-head records between players
 *
 * TENNIS SURFACES:
 * - Hard Court (most common): Australian Open, US Open
 * - Clay Court (slow): French Open, Monte Carlo
 * - Grass Court (fast): Wimbledon
 *
 * TOURNAMENT CATEGORIES:
 * - Grand Slam: Australian Open, French Open, Wimbledon, US Open
 * - ATP Masters 1000: Indian Wells, Miami, Monte Carlo, etc.
 * - ATP 500: Barcelona, Hamburg, Washington, etc.
 * - ATP 250: Smaller tour events
 *
 * RANKING SYSTEMS:
 * - ATP Ranking: Men's professional ranking
 * - WTA Ranking: Women's professional ranking
 * - ELO Rating: Chess-style rating for tennis predictions
 *
 * PREDICTION FEATURES:
 * - Surface-specific performance
 * - Recent form (last 5-10 matches)
 * - Head-to-head records
 * - Player physical condition
 * - Tournament importance
 * - Weather conditions impact
 *
 * COMMON TENNIS STATISTICS:
 * - First serve percentage
 * - Aces per match
 * - Break points saved/converted
 * - Return games won
 * - Tiebreak win percentage
 *
 * DATA SOURCES:
 * - ATP Tour official data
 * - WTA official data
 * - Tennis Abstract (Jeff Sackmann)
 * - Betting odds for market predictions
 */

// This file helps GitHub Copilot understand tennis domain
// Keep it open when working on tennis-related code
