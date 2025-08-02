<?php
/**
 *  File: /includes/cpt/sport-event.php
 * CPT: sport_team
 */
add_action('init', function () {
    register_post_type('sport_event', [
        'labels' => [
            'name'               => 'Sport events',
            'singular_name'      => 'Sport event',
            'menu_name'          => 'Sport events',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Sport Event',
            'edit_item'          => 'Edit Sport Event',
            'new_item'           => 'New Sport Event',
            'view_item'          => 'View Sport Event',
            'search_items'       => 'Search Sport Events',
            'not_found'          => 'No Sport Events found',
            'not_found_in_trash' => 'No Sport Events found in Trash',
        ],
        'public'             => true,
        'menu_position'      => 6,
        'menu_icon'          => 'dashicons-calendar-alt',
        'supports'           => ['title', 'editor', 'thumbnail'],
        'has_archive'        => true,
        'show_in_rest'       => true,
    ]);
});
