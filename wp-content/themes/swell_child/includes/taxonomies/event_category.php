<?php
/**
 * File: /includes/taxonomies/event_category.php
 * Mục đích: Đăng ký taxonomy cho sport_event
 * - Mỗi CPT có 4 taxonomy riêng: Location, Sport name, Category, Status
 * - Giúp VK Filter hiển thị số lượng chính xác tuyệt đối cho từng CPT
 */
add_action('init', function () {
    register_taxonomy('event_category', ['sport_event'], [
        'label' => 'Phân loại (Event)',
        'hierarchical' => true,
        'public' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'event-category', 'with_front' => false],
    ]);
});
