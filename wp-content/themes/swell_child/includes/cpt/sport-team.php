<?php
/**
 *  File: /includes/cpt/sport-team.php
 * CPT: sport_team
 */
add_action('init', function () {
    register_post_type('sport_team', [
        'labels' => [
            'name'               => 'Sport teams',
            'singular_name'      => 'Sport team',
            'menu_name'          => 'Sport teams',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Sport Team',
            'edit_item'          => 'Edit Sport Team',
            'new_item'           => 'New Sport Team',
            'view_item'          => 'View Sport Team',
            'search_items'       => 'Search Sport Teams',
            'not_found'          => 'No Sport Teams found',
            'not_found_in_trash' => 'No Sport Teams found in Trash',
        ],
        'public'             => true,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-groups',
        'supports'           => ['title', 'editor', 'thumbnail'],
        'has_archive'        => true,
        'show_in_rest'       => true,
    ]);
});
