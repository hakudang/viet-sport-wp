<?php
/**
 * File name: functions/page-admin-columns.php
 * Description: Thêm cột "Slug" vào bảng quản lý trang (Pages) trong admin.
 * Vị trí: /wp-content/themes/swell_child/functions/page-admin-columns.php
 */

// Thêm cột Slug vào danh sách cột của Page
add_filter('manage_pages_columns', 'viet_sport_add_slug_column');
function viet_sport_add_slug_column($columns) {
    $columns['slug'] = __('Slug');
    return $columns;
}

// Hiển thị giá trị slug cho từng Page
add_action('manage_pages_custom_column', 'viet_sport_show_slug_column', 10, 2);
function viet_sport_show_slug_column($column_name, $post_id) {
    if ($column_name === 'slug') {
        $post = get_post($post_id);
        echo esc_html($post->post_name);
    }
}

// Cho phép sắp xếp theo Slug trong danh sách Pages
add_filter('manage_edit-page_sortable_columns', 'viet_sport_make_slug_sortable');
function viet_sport_make_slug_sortable($columns) {
    $columns['slug'] = 'name';
    return $columns;
}
