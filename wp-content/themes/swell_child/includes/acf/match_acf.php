<?php
// includes/acf/match_acf.php
add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group([
        'key' => 'group_match_acf',
        'title' => 'Match Fields',
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'match']]],
        'fields' => [
            ['key' => 'field_start_date', 'label' => 'Ngày bắt đầu', 'name' => 'start_date', 'type' => 'date_picker', 'return_format' => 'Y-m-d', 'display_format' => 'Y-m-d', 'first_day' => 1],
            ['key' => 'field_start_time', 'label' => 'Giờ bắt đầu', 'name' => 'start_time', 'type' => 'time_picker', 'display_format' => 'H:i', 'return_format' => 'H:i', 'default_value' => '18:00'],
            ['key' => 'field_hours', 'label' => 'Số giờ', 'name' => 'hours', 'type' => 'number', 'min' => 1, 'step' => 0.5],
            ['key' => 'field_stop_date', 'label' => 'Ngày dừng tuyển', 'name' => 'stop_date', 'type' => 'date_picker', 'return_format' => 'Y-m-d', 'display_format' => 'Y-m-d', 'first_day' => 1],
            ['key' => 'field_stop_time', 'label' => 'Giờ dừng tuyển', 'name' => 'stop_time', 'type' => 'time_picker', 'display_format' => 'H:i', 'return_format' => 'H:i', 'default_value' => '20:00'],
            ['key' => 'field_place_name', 'label' => 'Tên địa điểm', 'name' => 'place_name', 'type' => 'text'],
            ['key' => 'field_google_map', 'label' => 'Google map', 'name' => 'google_map', 'type' => 'google_map', 'center' => ['lat' => 35.681236, 'lng' => 139.767125], 'zoom' => 12],
            ['key' => 'field_people', 'label' => 'Số người', 'name' => 'people', 'type' => 'number', 'min' => 1, 'step' => 1],
            ['key' => 'field_stop_method', 'label' => 'Phương thức chốt dừng tuyển', 'name' => 'stop_method', 'type' => 'radio', 'choices' => [
                'by_deadline' => 'Theo hạn (stop_date/time)',
                'auto_when_full' => 'Đủ người tự đóng',
                'manual' => 'Thủ công',
            ], 'layout' => 'horizontal'],
            ['key' => 'field_court_info', 'label' => 'Thông tin sân', 'name' => 'court_info', 'type' => 'text'],
            ['key' => 'field_tel_chu_xi', 'label' => 'SĐT chủ xị', 'name' => 'tel_chu_xi', 'type' => 'text', 'wrapper' => ['width' => 50]],
            ['key' => 'field_ask_part_tel', 'label' => 'Yêu cầu người tham gia nhập SDT', 'name' => 'ask_participant_tel', 'type' => 'true_false', 'ui' => 1, 'wrapper' => ['width' => 50]],
            ['key' => 'field_details', 'label' => 'Thông tin chi tiết', 'name' => 'details', 'type' => 'wysiwyg'],
        ],
    ]);
});
