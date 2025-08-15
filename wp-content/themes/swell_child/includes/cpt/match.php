<?php
// includes/cpt/match.php
add_action('init', function () {
    $labels = [
        'name'                  => 'Matches',
        'singular_name'         => 'Match',
        'menu_name'             => 'Matches',
        'name_admin_bar'        => 'Match',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New Match',
        'new_item'              => 'New Match',
        'edit_item'             => 'Edit Match',
        'view_item'             => 'View Match',
        'all_items'             => 'All Matches',
        'search_items'          => 'Search Matches',
        'parent_item_colon'     => 'Parent Matches:',
        'not_found'             => 'No matches found.',
        'not_found_in_trash'    => 'No matches found in Trash.',
        'archives'              => 'Match Archives',
        'attributes'            => 'Match Attributes',
        'insert_into_item'      => 'Insert into match',
        'uploaded_to_this_item' => 'Uploaded to this match',
        'filter_items_list'     => 'Filter matches list',
        'items_list_navigation' => 'Matches list navigation',
        'items_list'            => 'Matches list',
    ];

    register_post_type('match', [
        'labels'             => $labels,
        'public'             => true,
        // 'has_archive'        => true,
        // 'rewrite'            => ['slug' => 'match', 'with_front' => true],
        'has_archive' => 'matches',                 // archive tại /matches
        'rewrite'     => ['slug' => 'matches', 'with_front' => false], // single: /matches/%postname%
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-location',
        'supports'           => ['title', 'editor', 'author', 'thumbnail', 'comments'],
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'capability_type'    => 'post',
        'map_meta_cap'       => true,
    ]);
});
