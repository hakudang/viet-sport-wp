<?php
/**
 * File: match-menu-switch.php
 * Vị trí: /wp-content/themes/swell_child/functions/match/match-menu-switch.php
 * Map header Swell ('header_menu') sang menu 'match_menu' khi đang ở /match
 * KHÔNG gọi get_nav_menu_locations() trong filter này để tránh đệ quy.
 */
add_filter('theme_mod_nav_menu_locations', function ($locations) {
  // Chỉ chạy ở frontend + trong ngữ cảnh Match
  if (is_admin()) return $locations;
  if (!function_exists('vsp_is_match_context') || !vsp_is_match_context()) return $locations;

  // $locations là mảng [location => term_id] hiện tại (đÃ có 'match_menu' nếu bạn gán trong WP Admin)
  if (!empty($locations['match_menu'])) {
    // Swell dùng 'header_menu' cho header
    $locations['header_menu'] = $locations['match_menu'];
  }

  return $locations;
}, 10);
