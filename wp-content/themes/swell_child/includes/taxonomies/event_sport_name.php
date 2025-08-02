<?php
/**
 * File: /includes/taxonomies/event_sport_name.php
 * Mục đích: Đăng ký taxonomy (tách riêng) cho sport_event
 * - Mỗi CPT có 4 taxonomy riêng: Location, Sport name, Category, Status
 * - Giúp VK Filter hiển thị số lượng chính xác tuyệt đối cho từng CPT
 */
add_action('init', function () {
    register_taxonomy('event_sport_name', ['sport_event'], [
        'label' => 'Bộ môn (Event)',
        'hierarchical' => true,
        'public' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'event-sport-name', 'with_front' => false],
    ]);
});
