<?php
/**
 * File: functions/match/seed-match-menu.php
 * Seed/cập nhật Match Menu kèm description (JP) cho từng item.
 * - Luôn cập nhật title + description (idempotent).
 * - Chỉ tạo menu & gán location ở lần đầu (dựa trên option).
 */

add_action('after_setup_theme', function () {
    // Tên menu & location
    $menu_name   = 'Match Menu';
    $location    = 'match_menu';

    // 1) Lấy/tạo menu
    $menu_obj = wp_get_nav_menu_object($menu_name);
    if (!$menu_obj) {
        $menu_id = wp_create_nav_menu($menu_name);
        if (is_wp_error($menu_id)) return;
        $menu_obj = wp_get_nav_menu_object($menu_id);
    }
    $menu_id = (int) $menu_obj->term_id;

    // 2) Gán menu vào location (chỉ cần làm 1 lần)
    if (!get_option('vsp_match_menu_seeded')) {
        $locations = (array) get_theme_mod('nav_menu_locations', []);
        if (empty($locations[$location])) {
            $locations[$location] = $menu_id;
            set_theme_mod('nav_menu_locations', $locations);
        }
        update_option('vsp_match_menu_seeded', 1, true);
    }

    // 3) Map URL -> item hiện có
    $existing_items = wp_get_nav_menu_items($menu_id) ?: [];
    $by_url = [];
    foreach ($existing_items as $it) {
        $by_url[ rtrim((string)$it->url, '/') ] = $it;
    }

    // 4) Upsert helper (cập nhật title + description nếu đã tồn tại)
    $upsert = function (string $title, string $url, string $desc = '', array $classes = []) use ($menu_id, &$by_url) {
        $url  = rtrim($url, '/');
        $args = [
            'menu-item-title'       => $title,
            'menu-item-url'         => $url,
            'menu-item-description' => $desc,                       // 👈 Description (SWELL tự hiển thị)
            'menu-item-classes'     => implode(' ', $classes),
            'menu-item-status'      => 'publish',
        ];
        if (isset($by_url[$url])) {
            wp_update_nav_menu_item($menu_id, (int) $by_url[$url]->ID, $args);
        } else {
            $item_id = wp_update_nav_menu_item($menu_id, 0, $args);
            if (!is_wp_error($item_id)) {
                $by_url[$url] = get_post($item_id);
            }
        }
    };

    // 5) Danh sách item + mô tả JP
    $base = home_url('/match');
    $upsert('Tạo sân',   $base . '/create',        'マッチ開催する');
    $upsert('Tin nhắn',  $base . '/messages',      '伝言ページ');
    $upsert('Tham gia',  $base . '/joined',        '参加申込みした');
    $upsert('Chủ xị',    $base . '/hosted',        '主催中');
    $upsert('Đang xem',  $base . '/viewing',       '最近見た');
    $upsert('Theo dõi',  $base . '/following',     'ウォッチリスト');
    $upsert('Thông báo', $base . '/notifications', 'アラート');
    $upsert('Cảnh báo',  $base . '/alerts',        '欠員お知らせ');
    $upsert('Khác',      $base . '/more',          'その他の機能');
});
