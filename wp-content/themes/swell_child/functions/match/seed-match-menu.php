<?php

/**
 * File: functions/match/seed-match-menu.php
 * Mục đích:
 * - Tự tạo menu "Match Menu" (nếu chưa có), gán vào location 'match_menu'
 * - Tự thêm sẵn các item cho module Match
 * - Chạy 1 lần duy nhất, có thể reset bằng cách xóa option 'vsp_match_menu_seeded'
 */

add_action('after_setup_theme', function () {

    // Chỉ chạy 1 lần
    if (get_option('vsp_match_menu_seeded')) return;

    // Đảm bảo location 'match_menu' đã được đăng ký (trong includes/setup.php)
    $locations = (array) get_theme_mod('nav_menu_locations', []);

    // Lấy/ tạo menu theo tên
    $menu      = wp_get_nav_menu_object('Match Menu');
    if (! $menu) {
        $menu_id = wp_create_nav_menu('Match Menu');
        if (is_wp_error($menu_id)) return;
    } else {
        $menu_id = (int) $menu->term_id;
    }

    // Gán menu này cho location 'match_menu' nếu chưa gán
    if (empty($locations['match_menu'])) {
        $locations['match_menu'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }

    // Helper: thêm item nếu chưa có URL đó
    $existing_items = wp_get_nav_menu_items($menu_id) ?: [];
    $existing_urls  = array_map(function ($it) {
        return rtrim((string)$it->url, '/');
    }, $existing_items);
    $add_item = function ($title, $url) use ($menu_id, &$existing_urls) {
        $url = rtrim($url, '/');
        if (in_array($url, $existing_urls, true)) return;
        wp_update_nav_menu_item($menu_id, 0, [
            'menu-item-title'  => $title,
            'menu-item-url'    => $url,
            'menu-item-status' => 'publish',
        ]);
        $existing_urls[] = $url;
    };

    // Base cho module match
    $base = home_url('/match');

    // Danh sách mục theo yêu cầu
    $add_item('Tạo sân',           $base . '/create');
    $add_item('Tin nhắn',          $base . '/messages');
    $add_item('Tham gia',          $base . '/joined');
    $add_item('Chủ xị',            $base . '/hosted');
    $add_item('Đang xem',          $base . '/viewing');
    $add_item('Theo dõi',          $base . '/following');
    $add_item('Thông báo',         $base . '/notifications');
    $add_item('Cảnh báo',          $base . '/alerts');
    $add_item('Khác',              $base . '/more');

    // Đánh dấu đã seed
    update_option('vsp_match_menu_seeded', 1, true);
});
