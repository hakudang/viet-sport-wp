<?php

/**
 * SWELL Child Theme Functions
 * File: functions.php
 * Mục đích: Chứa các hàm tùy chỉnh cho theme con SWELL
 * Ngày cập nhật: 2025-08-04
 * Người viết: Dang
 */

// Bảo vệ file nếu bị truy cập trực tiếp
defined( 'ABSPATH' ) || exit;

/**
 * SWELL CHILD PATH, URI
 */
define( 'SWELL_CHILD_PATH', get_stylesheet_directory() );
define( 'SWELL_CHILD_URI', get_stylesheet_directory_uri() );

/* ==============================================================
   [ASSETS] - Load Google Fonts (VN/EN/JP) + style.css
   - Font chính: Be Vietnam Pro (cho tiếng Việt và tiếng Anh)
   - Fallback: Noto Sans JP (cho tiếng Nhật, hoặc khi Be Vietnam Pro lỗi)
   - Preconnect đến Google Fonts để tăng tốc độ tải font trên Safari/iOS
   - Tự động nhúng style.css từ theme
   - Dùng 1 hàm duy nhất để quản lý tài nguyên frontend
   Ngày cập nhật: 2025-08-04
   Người viết: Dang (cập nhật bởi ChatGPT)
   ============================================================== */

if ( ! function_exists('enqueue_theme_assets') ) {
    function enqueue_theme_assets() {
        // Preconnect đến Google Fonts để tối ưu tốc độ tải, tránh rớt font trên iOS
        wp_enqueue_script('preconnect-google-fonts', '', [], '', false);
        add_action('wp_head', function() {
            echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
            echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
        }, 0);

        // Nhúng Google Fonts:
        // - Font chính: Be Vietnam Pro (wght 400–700) cho tiếng Việt và tiếng Anh
        // - Fallback: Noto Sans JP (wght 400,700) dùng cho tiếng Nhật
        wp_enqueue_style(
            'global-fonts',
            'https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Noto+Sans+JP:wght@400;700&display=swap',
            [],
            null
        );

        // Nhúng style.css của theme hiện tại (child hoặc parent)
        wp_enqueue_style(
            'theme-style',
            get_stylesheet_uri(),
            [],
            filemtime( get_stylesheet_directory() . '/style.css' ) // Cache busting theo timestamp
        );
    }

    add_action('wp_enqueue_scripts', 'enqueue_theme_assets');
}

/* ============================ END [ASSETS] ============================ */




// ===============================
// [INCLUDES] - Load file cấu hình từ thư mục includes/
// Mục đích: Tách riêng logic vào các file chuyên biệt để dễ bảo trì
// Thư mục: /includes/load.php (nên chứa các require khác nếu cần)
// Ngày cập nhật: 2025-08-04
// Người viết: Dang
// ===============================

require_once get_stylesheet_directory() . '/includes/load.php';

/* ============================ END [INCLUDES] ============================ */

