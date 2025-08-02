<?php
/**
 * File: includes/flush-rewrite-once.php
 * Mục đích: Flush rewrite rules 1 lần duy nhất sau khi chỉnh permalink hoặc thêm rewrite cho taxonomy/post type
 * Vì WordPress cache rewrite rules → nếu không flush sẽ bị lỗi 404 với URL mới
 *
 * Cơ chế:
 * - Chạy khi khởi động (hook 'init')
 * - Kiểm tra flag 'rewrite_rules_flushed' trong options table
 * - Nếu chưa flush: gọi flush_rewrite_rules() và lưu flag lại để không lặp lại nữa
 */

add_action('init', function () {
    if (!get_option('rewrite_rules_flushed')) {
        flush_rewrite_rules(); // 🚨 BẮT BUỘC để URL rewrite mới hoạt động
        update_option('rewrite_rules_flushed', 1); // ✅ Đánh dấu đã flush để không chạy lại nữa
    }
});
