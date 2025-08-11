<?php
/**
 * File: includes/setup.php
 * Mục đích: Đăng ký menu, hỗ trợ theme...
 */

// Đăng ký menu
function viet_sport_register_menus() {
    register_nav_menus([
        'primary' => __('Menu chính', 'viet_sport'),
        // Có thể thêm menu riêng cho mobile hoặc module match ở đây nếu cần
        'match_menu' => __('Menu Match', 'viet_sport'),
    ]);
}
add_action('after_setup_theme', 'viet_sport_register_menus');
