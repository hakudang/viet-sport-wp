<?php
// includes/taxonomies/match_status.php

// 1) Đăng ký taxonomy
add_action('init', function () {
    register_taxonomy('match_status', ['match'], [
        'labels' => [
            'name'          => 'Trạng thái',
            'singular_name' => 'Trạng thái',
        ],
        'public'            => true,
        'hierarchical'      => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'match-status', 'with_front' => false],

    ]);
}, 9);

// 2) Seed term (idempotent, KHÔNG cần option)
add_action('init', function () {
    $terms = [
        ['doing',  'Đang tuyển'],
        ['closed', 'Dừng tuyển'],
        ['cancel', 'Hủy chơi'],
        ['done',   'Kết thúc'],
    ];
    foreach ($terms as [$slug, $name]) {
        if (! term_exists($slug, 'match_status')) {
            wp_insert_term($name, 'match_status', ['slug' => $slug]);
        }
    }
}, 11);

// 3) Mặc định = doing khi tạo mới
add_action('save_post_match', function ($post_id, $post, $update) {
    if (wp_is_post_revision($post_id) || $update) return;
    $term = get_term_by('slug', 'doing', 'match_status');
    if ($term) wp_set_post_terms($post_id, [$term->term_id], 'match_status', false);
}, 10, 3);
