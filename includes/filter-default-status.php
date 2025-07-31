<?php
/**
 * File: includes/filter-default-status.php
 * Mục đích:
 * 1. Tự động lọc các bài sport_team chỉ hiển thị trạng thái "オン"
 * ❌ Không cần fix VK Filter nữa vì taxonomy đã tách riêng → không còn đếm sai
 */

/**
 * 1️⃣ Filter mặc định cho archive sport_team → chỉ hiển thị bài có team_status = 'オン'
 */
add_action('pre_get_posts', function($query) {
    if (
        !is_admin() &&
        $query->is_main_query() &&
        is_post_type_archive('sport_team') &&
        empty($_GET['team_status']) // nếu user chưa chọn filter
    ) {
        $query->set('tax_query', [
            [
                'taxonomy' => 'team_status',
                'field'    => 'slug',
                'terms'    => ['オン'],
            ]
        ]);
    }
});
