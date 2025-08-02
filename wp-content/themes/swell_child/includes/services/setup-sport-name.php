<?php
/**
 * File: /includes/services/setup-sport-name.php
 * Mục đích: Tạo sẵn các term trong taxonomy
 * - team_sport_name (cho sport_team)
 * - event_sport_name (cho sport_event)
 * 
 * Gồm các bộ môn tiếng Nhật với slug tiếng Anh:
 * - サッカー → football
 * - バドミントン → badminton
 * - ピックルボール → pickleball
 * - テニス → tennis
 * - 卓球 → ping-pong
 * - バレーボール → volleyball
 * - バスケットボール → basketball
 * - 釣り → fishing
 */

add_action('init', 'insert_sport_names_once', 999); // Ưu tiên sau khi register taxonomy

function insert_sport_names_once() {
    // Nếu đã chạy trước đó, thì dừng
    if (get_option('sport_names_created') === 'yes') return;

    // Check xem taxonomy tồn tại chưa
    if (!taxonomy_exists('team_sport_name') || !taxonomy_exists('event_sport_name')) return;

    // Danh sách môn thể thao (key: tiếng Nhật, value: slug tiếng Anh)
    $sports = [
        'サッカー'         => 'football',
        'フットサル'       => 'futsal',
        'バドミントン'     => 'badminton',
        'ピックルボール'   => 'pickleball',
        'テニス'           => 'tennis',
        '卓球'             => 'ping-pong',
        'バレーボール'     => 'volleyball',
        'バスケットボール' => 'basketball',
        '釣り'             => 'fishing',
    ];

    // Mỗi taxonomy đều được insert các term tương ứng
    $taxonomies = ['team_sport_name', 'event_sport_name'];

    foreach ($taxonomies as $taxonomy) {
        foreach ($sports as $name => $slug) {
            if (!term_exists($name, $taxonomy)) {
                wp_insert_term($name, $taxonomy, ['slug' => $slug]);
            }
        }
    }

    update_option('sport_names_created', 'yes'); // Đánh dấu đã tạo
}
