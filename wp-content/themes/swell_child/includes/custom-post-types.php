<?php

/**
 * File: includes/custom-post-types.php
 * Mục đích: Gom tất cả require của CPT và Taxonomy vào một nơi.
 * Gọi từ includes/load.php
 */

/**
 * ===============================
 * Custom Post Types
 * ===============================
 */
require_once get_theme_file_path('includes/cpt/sport_team.php');
require_once get_theme_file_path('includes/cpt/sport_event.php');
require_once get_theme_file_path('includes/cpt/match.php');

/**
 * ===============================
 * Taxonomies
 * ===============================
 */
// Taxonomies cho Team
require_once get_theme_file_path('includes/taxonomies/team_location.php');
require_once get_theme_file_path('includes/taxonomies/team_sport_name.php');
require_once get_theme_file_path('includes/taxonomies/team_category.php');
require_once get_theme_file_path('includes/taxonomies/team_status.php');

// Taxonomies cho Event
require_once get_theme_file_path('includes/taxonomies/event_location.php');
require_once get_theme_file_path('includes/taxonomies/event_sport_name.php');
require_once get_theme_file_path('includes/taxonomies/event_category.php');
require_once get_theme_file_path('includes/taxonomies/event_status.php');

// Taxonomies cho Match
require_once get_theme_file_path('includes/taxonomies/match_sport.php');
require_once get_theme_file_path('includes/taxonomies/match_status.php');
require_once get_theme_file_path('includes/taxonomies/match_prefecture.php');
