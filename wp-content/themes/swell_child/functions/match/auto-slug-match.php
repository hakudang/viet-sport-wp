<?php
/**
 * file name : match/auto-slug-match.php
 * Tạo slug tự động cho sân chơi mới
 * - Slug là 8 chữ số, bắt đầu từ 00000001
 * - Tự động tăng dần khi tạo mới
 * - Chỉ áp dụng khi post_type = match và post_status = publish
 * - Không áp dụng khi cập nhật bài viết (có ID)
 */
add_filter('wp_insert_post_data', 'create_slug_match_8_num', 10, 2);

function create_slug_match_8_num($data, $postarr) {
    if (
        $data['post_type'] === 'match' &&
        $data['post_status'] === 'publish' &&
        empty($postarr['ID']) // Chỉ khi tạo mới
    ) {
        global $wpdb;

        $max_slug = $wpdb->get_var("
            SELECT post_name
            FROM {$wpdb->prefix}posts
            WHERE post_type = 'match' AND post_status = 'publish'
            AND post_name REGEXP '^[0-9]{8}$'
            ORDER BY post_name DESC
            LIMIT 1
        ");

        $next_num = 1;
        if ($max_slug) {
            $next_num = intval($max_slug) + 1;
        }

        $data['post_name'] = str_pad($next_num, 8, '0', STR_PAD_LEFT);
    }

    return $data;
}
