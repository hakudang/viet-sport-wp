<?php
// includes/taxonomies/match_sport.php
add_action('init', function () {
    register_taxonomy('match_sport', ['match'], [
        'labels' => [
            'name'          => 'Môn thể thao',
            'singular_name' => 'Môn',
        ],
        'public'       => true,
        'hierarchical' => false,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'sport'],
    ]);
});

// Seed ví dụ
add_action('init', function () {
    if (get_option('vsp_match_sport_seeded')) return;
    foreach (['サッカー','バドミントン','ピクルボール','テニス','フットサル','釣り'] as $name) {
        if (!term_exists($name, 'match_sport')) {
            wp_insert_term(ucfirst($name), 'match_sport', ['slug'=>$name]);
        }
    }
    update_option('vsp_match_sport_seeded', 1, true);
});
