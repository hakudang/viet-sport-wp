<?php

/**
 * Load style.css của Child Theme, kèm timestamp để tránh cache trình duyệt
 * 
 * Khi bạn sửa file style.css, trình duyệt sẽ tự hiểu đó là file mới vì có version dạng timestamp,
 * ví dụ: /style.css?ver=20250730123045 → Không bị lỗi cache CSS cũ.
 */
add_action('wp_enqueue_scripts', function () {
    $timestamp = date('Ymdgis', filemtime(get_stylesheet_directory() . '/style.css')); // Lấy thời gian chỉnh sửa file
    wp_enqueue_style(
        'child_style',                                      // ID của style
        get_stylesheet_directory_uri() . '/style.css',      // Đường dẫn tới file style.css
        [],                                                  // Không phụ thuộc style nào khác
        $timestamp                                           // Version theo thời gian → chống cache
    );

    // 🔽 Nếu muốn thêm CSS hoặc JS khác → thêm dưới đây

}, 11);



/**
 * Nạp Google Fonts: Ưu tiên Roboto (Việt/Anh), fallback sang Noto Sans JP (Nhật)
 */
function add_custom_fonts() {
	wp_enqueue_style(
		'google-fonts',
		'https://fonts.googleapis.com/css2?family=Roboto&family=Noto+Sans+JP&display=swap',
		false
	);
}
add_action('wp_enqueue_scripts', 'add_custom_fonts');

/**
 * Load các file cần thiết từ thư mục includes/
 */
require_once get_stylesheet_directory() . '/includes/load.php';