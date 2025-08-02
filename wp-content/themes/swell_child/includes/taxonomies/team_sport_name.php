<?php
/**
 * File: /includes/taxonomies/team_sport_name.php
 * Mục đích: Đăng ký taxonomy (tách riêng) cho sport_team
 * - Mỗi CPT có 4 taxonomy riêng: Location, Sport name, Category, Status
 * - Giúp VK Filter hiển thị số lượng chính xác tuyệt đối cho từng CPT
 */
add_action('init', function () {
    register_taxonomy('team_sport_name', ['sport_team'], [
        'label' => 'Bộ môn (Team)',
        'hierarchical' => true,
        'public' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'sport-name', 'with_front' => false],
    ]);
});
