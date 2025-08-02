<?php
/**
 * File: includes/load.php
 * Mục đích: Load các module cần thiết cho child theme
 * Gồm: Đăng ký CPT, taxonomy, migrate dữ liệu cũ và setup tỉnh thành
 */

// 🔹 Custom Post Types
// Đăng ký CPT cho CLB thể thao (sport_team) và sự kiện thể thao (sport_event)
// Mỗi CPT được tách thành 1 file riêng trong thư mục /cpt/
require_once __DIR__ . '/cpt/sport-team.php';   // CPT: sport_team → đại diện cho các câu lạc bộ
require_once __DIR__ . '/cpt/sport-event.php';  // CPT: sport_event → đại diện cho các sự kiện thể thao

// 🔹 Taxonomies
require_once __DIR__ . '/taxonomies/team_location.php';
require_once __DIR__ . '/taxonomies/team_sport_name.php';
require_once __DIR__ . '/taxonomies/team_category.php';
require_once __DIR__ . '/taxonomies/team_status.php';

require_once __DIR__ . '/taxonomies/event_location.php';
require_once __DIR__ . '/taxonomies/event_sport_name.php';
require_once __DIR__ . '/taxonomies/event_category.php';
require_once __DIR__ . '/taxonomies/event_status.php';

// Service - Chạy 1 lần rồi
// 🔹 Khi hook init, gọi Flush rewrite rules 1 lần duy nhất sau khi chỉnh permalink hoặc thêm rewrite cho taxonomy/post type
// tránh lỗi 404 ✅ Chạy 1 lần rồi comment lại
// require_once get_stylesheet_directory() . '/includes/flush-rewrite-once.php';

// 🔸 thêm taxonomy 'team_location', 'event_location' danh sách 47 tỉnh thành Nhật Bản cho sport_team, sport_event (chạy 1 lần, sau đó COMMENT lại)
// require_once __DIR__ . '/services/setup-japan-prefectures.php'; // ✅ Đã chạy. Có thể giữ lại hoặc xoá nếu không cần.

// 🔸 thêm taxonomy 'team_sport_name', 'event_sport_name' cho sport_team, sport_event (chạy 1 lần, sau đó COMMENT lại)
// require_once __DIR__ . '/services/setup-sport-name.php'; // ✅ Chạy 1 lần rồi comment lại

// 🔸 thêm taxonomy 'team_category', 'event_category' cho sport_team, sport_event (chạy 1 lần, sau đó COMMENT lại)
// require_once __DIR__ . '/services/setup-sport-category.php'; // ✅ Nhớ comment lại sau khi chạy

// 🔸  thêm taxonomy 'team_status', 'event_status' cho sport_team, sport_event (chạy 1 lần, sau đó COMMENT lại)
// require_once __DIR__ . '/services/setup-sport-status.php'; // ✅ Chạy 1 lần rồi comment lại




