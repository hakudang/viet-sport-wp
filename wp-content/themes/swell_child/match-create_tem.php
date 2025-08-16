<?php
/**
 * Template Name: Match Create Page
 * File: templates/match/match-create_tem.php
 * Mục đích: Template cho trang tạo sân chơi mới (/match/create)
 * - Sử dụng các bước từ 1 đến 14 để tạo sân chơi
 * - Bước 13 là xác nhận thông tin trước khi lưu
 */

if (!session_id()) session_start();

// Chỉ member mới tạo sân chơi 
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url());
    exit;
}


// Danh sách bước hợp lệ
$valid_steps = range(1, 14); // [1, 2, ..., 14]

$step = isset($_GET['step']) ? intval($_GET['step']) : 1;
if (!in_array($step, $valid_steps, true)) {
    $step = 1; // fallback an toàn
}

$current_step = $step;


$template_file = SWELL_CHILD_PATH . "/functions/match/create-steps/match-create-step{$step}.php";

if (file_exists($template_file)) {
    include $template_file;
} else {
    // Nếu bước chưa làm → nhảy đến bước xác nhận (13)
    wp_redirect('?step=13');
    exit;
}
