<?php
/**
 * File: /includes/services/setup-sport-category.php
 * Mục đích: Tạo sẵn các term trong taxonomy
 * - team_category (cho sport_team)
 * - event_category (cho sport_event)
 * 
 * Gồm các phân loại tiếng Nhật với slug tiếng Anh:
 * - 社会人 → adult
 * - OVER40 → over40
 * - 学生 → student
 * - 女性 → women
 * - プロ → pro
 */

add_action('init', 'insert_sport_categories_once', 999); // Ưu tiên sau khi register taxonomy

function insert_sport_categories_once() {
    // Nếu đã chạy trước đó, thì dừng
    if (get_option('sport_categories_created') === 'yes') return;

    // Đảm bảo taxonomy tồn tại
    if (!taxonomy_exists('team_category') || !taxonomy_exists('event_category')) return;

    // Danh sách phân loại
    $categories = [
        '社会人' => 'adult',
        'OVER40' => 'over40',
        '学生'   => 'student',
        '女性'   => 'women',
        'プロ'   => 'pro',
    ];

    $taxonomies = ['team_category', 'event_category'];

    foreach ($taxonomies as $taxonomy) {
        foreach ($categories as $name => $slug) {
            if (!term_exists($name, $taxonomy)) {
                wp_insert_term($name, $taxonomy, ['slug' => $slug]);
            }
        }
    }

    update_option('sport_categories_created', 'yes'); // Gắn cờ để không chạy lại
}
