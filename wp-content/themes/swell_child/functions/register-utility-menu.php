<?php
/**
 * File: functions/register-utility-menu.php
 * Đăng ký location utility_menu + tự tạo & gán menu "Utility (Top)" nếu chưa có.
 */


add_action('after_setup_theme', function () {
    register_nav_menus([
        'utility_menu' => __('Header Utility (Top)', 'swell_child'),
    ]);
});


/**
 * Sau khi kích hoạt theme (chạy 1 lần), tự tạo menu và gán vào 'utility_menu'
 * để không phải vào WP Admin.
 */
add_action('after_switch_theme', function () {
    // Lấy map location hiện tại
    $locations = (array) get_theme_mod('nav_menu_locations', []);


    // Nếu đã có gán rồi thì thôi
    if ( ! empty($locations['utility_menu']) ) return;


    // Tìm menu theo tên; nếu chưa có thì tạo mới
    $menu = wp_get_nav_menu_object('Utility (Top)');
    if ( ! $menu ) {
        $menu_id = wp_create_nav_menu('Utility (Top)');
        if ( is_wp_error($menu_id) ) return; // phòng sự cố hiếm
    } else {
        $menu_id = (int) $menu->term_id;
    }


    // Gán menu này vào location 'utility_menu'
    $locations['utility_menu'] = $menu_id;
    set_theme_mod('nav_menu_locations', $locations);
});
