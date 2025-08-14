<?php
/**
 * File: includes/load.php
 * Mục đích: Load các module cần thiết cho child theme
 * Gồm: setup/theme support, CPTs, taxonomies, Utility menu + URM, Match module, enqueue
 */

// ===============================
// Theme setup / menu locations cơ bản
// ===============================
require_once get_theme_file_path( 'includes/setup.php' );

// ===============================
// Custom Post Types
// ===============================
require_once get_theme_file_path( 'includes/custom-post-types.php' );


// ===============================
// Utility menu (menu nhỏ trên header) + URM
// Thứ tự: đăng ký location → render → bơm item URM
// ===============================
require_once get_theme_file_path( 'functions/register-utility-menu.php' );
require_once get_theme_file_path( 'functions/render-utility-menu.php' );
require_once get_theme_file_path( 'functions/urm-menu-hooks.php' ); // ✅ chỉ require 1 lần (xoá dòng trùng phía dưới)


// ===============================
// Menus (header/main) - helpers
// ===============================
require_once get_theme_file_path( 'functions/menu-anchor-rewriter.php' );

// ===============================
// Match module
// ===============================
require_once get_theme_file_path( 'functions/match/match-helpers.php' );
require_once get_theme_file_path( 'functions/match/match-menu-switch.php' );
require_once get_theme_file_path( 'functions/match/match-hooks.php' );
require_once get_theme_file_path('functions/match/seed-match-menu.php'); // Match menu: tự seed dữ liệu & gán location 'match_menu'

// ===============================
// Ẩn thanh Admin Bar ở frontend
// ===============================
// Gọi file ẩn thanh Admin Bar ở frontend cho user không phải admin,
// nhưng vẫn hiển thị khi truy cập khu vực quản trị (/wp-admin)
require_once get_theme_file_path('functions/hide-admin-bar.php');

// ===============================
// Admin customizations
// ===============================
require_once get_theme_file_path( 'functions/page-admin-columns.php' );

// ===============================
// Enqueue CSS/JS cho child theme
// ===============================
require_once get_theme_file_path( 'includes/enqueue-scripts.php' );






