<?php

/**
 * File: /includes/taxonomies/match_sport.php
 * Mục đích: Đăng ký taxonomy (tách riêng) cho match
 * - Mỗi CPT có 4 taxonomy riêng: Location, Sport name, Category, Status
 * - Giúp VK Filter hiển thị số lượng chính xác tuyệt đối cho từng CPT
 */
// 1) Register
add_action('init', function () {
    register_taxonomy('match_sport', ['match'], [
        'labels' => [
            'name'          => 'Sports',
            'singular_name' => 'Sport',
        ],
        'public'           => true,
        'hierarchical'     => true,
        'show_admin_column' => true,
        'show_in_rest'     => true,
        'rewrite'          => ['slug' => 'sport', 'with_front' => false],
    ]);
}, 9);

// ✅ Seed: map slug => name + check theo slug (KHÔNG destructure [$slug,$name])
add_action('init', function () {
    $sports = [
        'soccer'     => 'サッカー',
        'badminton'  => 'バドミントン',
        'pickleball' => 'ピクルボール',
        'tennis'     => 'テニス',
        'futsal'     => 'フットサル',
        'fishing'    => '釣り',
    ];
    foreach ($sports as $slug => $name) {
        if ( ! get_term_by('slug', $slug, 'match_sport') ) {
            wp_insert_term($name, 'match_sport', ['slug' => $slug]);
        }
    }
}, 11);