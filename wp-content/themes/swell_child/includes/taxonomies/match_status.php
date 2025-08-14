<?php
// includes/taxonomies/match_status.php
add_action('init', function () {
    register_taxonomy('match_status', ['match'], [
        'labels' => [
            'name'          => 'Trạng thái',
            'singular_name' => 'Trạng thái',
        ],
        'public'       => true,
        'hierarchical' => false,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'match-status'],
    ]);
});

// Seed terms & set default when first created
add_action('init', function () {
    if (get_option('vsp_match_status_seeded')) return;
    $terms = [
        ['doing','Đang tuyển'],
        ['closed','Dừng tuyển'],
        ['cancel','Hủy chơi'],
        ['done','Kết thúc'],
    ];
    foreach ($terms as [$slug, $name]) {
        if (!term_exists($slug, 'match_status')) {
            wp_insert_term($name, 'match_status', ['slug' => $slug]);
        }
    }
    update_option('vsp_match_status_seeded', 1, true);
});

// Default = doing on first save
add_action('save_post_match', function ($post_id, $post, $update) {
    if (wp_is_post_revision($post_id) || $update) return;
    $term = get_term_by('slug','doing','match_status');
    if ($term) wp_set_post_terms($post_id, [$term->term_id], 'match_status', false);
}, 10, 3);
