<?php
/**
 * File: match-hooks.php
 * Vị trí: /wp-content/themes/swell_child/functions/match/match-hooks.php
 * Description: Hook & filter cho module Match, bao gồm tắt CAPTCHA khi ở local
 * Author: Dang
 */
// 🔒 Tắt CAPTCHA của plugin User Registration & Membership (URM) trong môi trường local
add_filter( 'user_registration_form_recaptcha_enabled', function( $enabled ) {
    // Kiểm tra nếu đang ở môi trường local (domain kết thúc bằng .local)
    if ( strpos( home_url(), '.local' ) !== false ) {
        return false;
    }

    // Trả lại giá trị gốc nếu không phải local
    return $enabled;
});

// Tô sáng menu khi ở khu Match
add_filter('nav_menu_css_class', function($classes, $item){
  if (!function_exists('vsp_is_match_context') || !vsp_is_match_context()) return $classes;

  $match_root = trailingslashit( home_url('/match') );
  $item_url   = trailingslashit( $item->url );

  if (strpos($item_url, $match_root) === 0) {
    $classes[] = 'current-menu-item';
  }
  return $classes;
}, 10, 2);