<?php
/**
 * Script đổi prefix bảng WordPress, tự lấy config từ wp-config.php
 * Tác dụng: đổi prefix bảng, cập nhật options, usermeta, user_roles
 */

$wp_config_path = __DIR__ . '/wp-config.php';
if (!file_exists($wp_config_path)) {
    die("❌ Không tìm thấy wp-config.php\n");
}

// Đọc file wp-config.php
$config = file_get_contents($wp_config_path);

// Lấy thông tin cấu hình DB và prefix cũ
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

$old_prefix = $current_prefix[1];  // prefix hiện tại (vd: wpstg0_)
$new_prefix = 'wp_';               // prefix mới (vd: wp_)

// Kết nối DB
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_errno) {
    die("❌ Không thể kết nối DB: " . $mysqli->connect_error);
}

echo "🔗 Kết nối DB thành công: " . DB_NAME . "\n";
echo "🔁 Đổi prefix từ [$old_prefix] → [$new_prefix]...\n";

// 1. Đổi tên các bảng
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

// 2. Cập nhật option_name trong options table
$options_table = $new_prefix . 'options';
echo "🧼 Cập nhật prefix trong bảng $options_table...\n";

// Xoá option trùng trước khi update
$mysqli->query("DELETE FROM `$options_table` WHERE option_name IN (
  SELECT option_name FROM (
    SELECT REPLACE(option_name, '$old_prefix', '$new_prefix') AS option_name
    FROM `$options_table`
    WHERE option_name LIKE '{$old_prefix}%'
  ) AS temp
  WHERE EXISTS (
    SELECT 1 FROM `$options_table` WHERE option_name = temp.option_name
  )
)") or die("❌ Lỗi khi xoá option trùng: " . $mysqli->error);

// Cập nhật option_name có prefix cũ
$mysqli->query("UPDATE `$options_table` 
SET option_name = REPLACE(option_name, '$old_prefix', '$new_prefix') 
WHERE option_name LIKE '{$old_prefix}%'") or die("❌ Lỗi update options: " . $mysqli->error);

// ✅ Fix đặc biệt: đổi lại `wp_user_roles` nếu bị sai
$mysqli->query("UPDATE `$options_table` 
SET option_name = '{$new_prefix}user_roles' 
WHERE option_name = '{$old_prefix}user_roles'") or die("❌ Lỗi update wp_user_roles: " . $mysqli->error);

// 3. Cập nhật meta_key trong bảng usermeta
$usermeta_table = $new_prefix . 'usermeta';
echo "🧼 Cập nhật prefix trong bảng $usermeta_table...\n";

$mysqli->query("UPDATE `$usermeta_table` 
SET meta_key = REPLACE(meta_key, '$old_prefix', '$new_prefix') 
WHERE meta_key LIKE '{$old_prefix}%'") or die("❌ Lỗi update usermeta: " . $mysqli->error);

echo "\n✅ Đã hoàn tất đổi prefix sang [$new_prefix]\n";
echo "⚠️ Nhớ sửa lại trong wp-config.php dòng:\n\n";
echo "\$table_prefix = '$new_prefix';\n";
