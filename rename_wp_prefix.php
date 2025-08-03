<?php

/**
 * Script đổi prefix bảng WordPress, tự lấy config từ wp-config.php
 */

$wp_config_path = __DIR__ . '/wp-config.php';
if (!file_exists($wp_config_path)) {
    die("❌ Không tìm thấy wp-config.php\n");
}

// Lấy thông tin DB từ wp-config
$config = file_get_contents($wp_config_path);

preg_match("/define\\( *'DB_NAME', *'(.+?)' *\\);/", $config, $db_name);
preg_match("/define\\( *'DB_USER', *'(.+?)' *\\);/", $config, $db_user);
preg_match("/define\\( *'DB_PASSWORD', *'(.*?)' *\\);/", $config, $db_pass);
preg_match("/define\\( *'DB_HOST', *'(.+?)' *\\);/", $config, $db_host);
preg_match("/\\\$table_prefix *= *'(.+?)';/", $config, $current_prefix);

if (!$db_name || !$db_user || !$db_pass || !$db_host || !$current_prefix) {
    die("❌ Không đọc được thông tin từ wp-config.php\n");
}

define('DB_NAME',     $db_name[1]);
define('DB_USER',     $db_user[1]);
define('DB_PASSWORD', $db_pass[1]);
define('DB_HOST',     $db_host[1]);

$old_prefix = 'wpstg0_'; // ✅ prefix hiện tại (staging)
$new_prefix = 'wp_';     // ✅ prefix bạn muốn chuyển về

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_errno) {
    die("❌ Không thể kết nối DB: " . $mysqli->connect_error);
}

echo "🔗 Kết nối DB thành công: " . DB_NAME . "\n";
echo "🔁 Đổi prefix từ [$old_prefix] → [$new_prefix]...\n";

// Bước 1: Đổi tên các bảng
$res = $mysqli->query("SHOW TABLES LIKE '{$old_prefix}%'");
if (!$res || $res->num_rows === 0) {
    die("❌ Không có bảng nào cần đổi\n");
}

while ($row = $res->fetch_row()) {
    $old_table = $row[0];
    $new_table = preg_replace('/^' . preg_quote($old_prefix, '/') . '/', $new_prefix, $old_table);
    echo "👉 Đổi bảng: $old_table → $new_table\n";
    if (!$mysqli->query("RENAME TABLE `$old_table` TO `$new_table`")) {
        echo "❌ Lỗi: " . $mysqli->error . "\n";
    }
}

// Bước 2: Cập nhật `options` và `usermeta`
$options_table = $new_prefix . 'options';
$usermeta_table = $new_prefix . 'usermeta';

echo "🧼 Đang cập nhật bảng $options_table...\n";
// $mysqli->query("UPDATE `$options_table` SET option_name = REPLACE(option_name, '$old_prefix', '$new_prefix') WHERE option_name LIKE '{$old_prefix}%'");
// Xóa các dòng có thể bị trùng sau khi đổi prefix
$mysqli->query("DELETE FROM `$options_table` WHERE option_name IN (
  SELECT option_name FROM (
    SELECT REPLACE(option_name, '$old_prefix', '$new_prefix') AS option_name
    FROM `$options_table`
    WHERE option_name LIKE '{$old_prefix}%'
  ) AS temp
  WHERE EXISTS (
    SELECT 1 FROM `$options_table` WHERE option_name = temp.option_name
  )
)") or die("❌ Lỗi khi xóa option trùng: " . $mysqli->error);

// Tiếp theo mới update
$mysqli->query("UPDATE `$options_table` SET option_name = REPLACE(option_name, '$old_prefix', '$new_prefix') WHERE option_name LIKE '{$old_prefix}%'")
    or die("❌ Lỗi update options: " . $mysqli->error);

echo "🧼 Đang cập nhật bảng $usermeta_table...\n";
$mysqli->query("UPDATE `$usermeta_table` SET meta_key = REPLACE(meta_key, '$old_prefix', '$new_prefix') WHERE meta_key LIKE '{$old_prefix}%'");

echo "\n✅ Đã hoàn tất đổi prefix sang [$new_prefix]\n";
echo "⚠️ Nhớ sửa lại trong wp-config.php dòng:\n\n";
echo "\$table_prefix = '$new_prefix';\n";
