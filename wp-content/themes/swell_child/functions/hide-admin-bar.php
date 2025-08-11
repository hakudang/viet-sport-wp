<?php
/**
 * File name: functions/hide-admin-bar.php
 * Hide WP Admin Bar on front-end for non-admin users.
 * - Vẫn giữ Admin Bar trong /wp-admin để quản trị tiện thao tác.
 */
add_filter('show_admin_bar', function ($show) {
    if (is_admin()) return $show;                 // luôn cho phép trong Dashboard
    return current_user_can('manage_options')     // chỉ admin (manage_options) mới thấy ở frontend
        ? $show
        : false;
});
