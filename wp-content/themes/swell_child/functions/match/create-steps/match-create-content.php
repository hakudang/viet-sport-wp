<?php

/**
 * File: swell_child/functions/match/create-steps/match-create-content.php
 * Purpose: Hiển thị phần nội dung của trang match/match_create theo đúng định dạng SWELL.
 * - SWELL markup: <div class="l-container"><article class="c-entry"><div class="post_content">…</div></article>
 * - Chỉ hiển thị nếu không ở bước 13/14 (confirm & create)
 */
defined('ABSPATH') || exit;

// Bảo vệ: nếu biến $current_step chưa có, mặc định = 1
if (!isset($current_step)) {
    $current_step = 1;
}

// Ẩn nội dung trang ở bước 13 & 14
if (in_array((int) $current_step, [13, 14], true)) {
    return;
}

// Lấy content của page hiện tại mà template này đang gán
$page_id = get_queried_object_id();
if (!$page_id) {
    return;
}

$content = get_post_field('post_content', $page_id); // raw content
if (trim((string) $content) === '') {
    return; // Không có gì để hiển thị
}

?>
<main id="primary" class="match-page">
    <div class="l-container">
        <?php
        // Đảm bảo post_class và filter the_content hoạt động đúng theo Page này
        $the_post = get_post($page_id);
        setup_postdata($the_post);
        ?>
        <article <?php post_class('c-entry', $page_id); ?>>
            <div class="post_content">
                <?php
                // Áp filter the_content để SWELL/Block Editor render chuẩn
                echo apply_filters('the_content', $content);
                ?>
            </div>
        </article>
        <?php wp_reset_postdata(); ?>
    </div>
</main>