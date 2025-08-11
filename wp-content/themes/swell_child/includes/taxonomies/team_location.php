<?php

/**
 * File: /includes/taxonomies/team_location.php
 * Mục đích: Đăng ký taxonomy (tách riêng) cho sport_team và sport_event
 * - Mỗi CPT có 4 taxonomy riêng: Location, Sport name, Category, Status
 * - Giúp VK Filter hiển thị số lượng chính xác tuyệt đối cho từng CPT
 */
add_action('init', function () {
    register_taxonomy('team_location', ['sport_team'], [
        'label'        => 'Tỉnh thành (Team)',
        'hierarchical' => true,
        'public'       => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => [
            'slug' => 'location',
            'with_front' => false,
        ],
    ]);
});
