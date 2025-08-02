<?php
/**
 * File: /includes/services/setup-sport-status.php
 * Mục đích: Tạo sẵn các term trong taxonomy
 * - team_status (cho sport_team)
 * - event_status (cho sport_event)
 * 
 * Các trạng thái:
 * - オン → on
 * - オフ → off
 * - サンプル → sample
 */

add_action('init', 'insert_sport_statuses_once', 999); // Ưu tiên sau khi register taxonomy

function insert_sport_statuses_once() {
    // Nếu đã chạy rồi thì không chạy lại
    if (get_option('sport_statuses_created') === 'yes') return;

    // Check taxonomy tồn tại
    if (!taxonomy_exists('team_status') || !taxonomy_exists('event_status')) return;

    // Danh sách trạng thái
    $statuses = [
        'オン'     => 'on',
        'オフ'     => 'off',
        'サンプル' => 'sample',
    ];

    $taxonomies = ['team_status', 'event_status'];

    foreach ($taxonomies as $taxonomy) {
        foreach ($statuses as $name => $slug) {
            if (!term_exists($name, $taxonomy)) {
                wp_insert_term($name, $taxonomy, ['slug' => $slug]);
            }
        }
    }

    update_option('sport_statuses_created', 'yes'); // Đánh dấu đã chạy
}
