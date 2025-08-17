<?php
// =============================================
// BƯỚC 3: NHẬP ĐỊA ĐIỂM – tên sân và tỉnh/thành
// =============================================

// Bắt đầu session nếu chưa có
if (!session_id()) session_start();

// Lấy dữ liệu từ session nếu có
$data = $_SESSION['match'] ?? [];
$districts = json_decode(DISTRICT_LABELS, true);

// Xử lý khi người dùng submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra nonce để tránh CSRF
    if (
        !isset($_POST['tao_san_choi_nonce']) ||
        !wp_verify_nonce($_POST['tao_san_choi_nonce'], 'tao_san_choi_action')
    ) {
        echo '<p>❌ Phiên không hợp lệ.</p>';
        exit;
    }

    // Lưu dữ liệu vừa nhập vào session
    $_SESSION['match'] = array_merge($data, $_POST);

    // Chuyển sang bước tiếp theo
    wp_redirect('?step=' . ($current_step + 1));
    exit;
}

// Hiển thị phần header từ theme
get_header();
?>

<!-- ==============================
     FORM BƯỚC 3: Nhập địa điểm
============================== -->
<div class="form-create-match match-create-step<?php echo $current_step; ?>">
    <form method="post">
        <!-- CSRF nonce -->
        <?php wp_nonce_field('tao_san_choi_action', 'tao_san_choi_nonce'); ?>

        <!-- Nhập tên địa điểm -->
        <label for="place_name">Tên địa điểm: <span style="color:red">*</span> </label>
        <input type="text" id="place_name" name="place_name" required
            value="<?php echo esc_attr($data['place_name'] ?? ''); ?>">

        <!-- Dropdown tỉnh / thành -->
		<label for="district">Tỉnh / Thành phố: <span style="color:red">*</span></label>
		<select id="district" name="district" required>
    		<option value="">-- Chọn --</option>
            <?php

			foreach ($districts as $slug => $label) {
        		$selected = ($data['district'] ?? '') === $slug ? 'selected' : '';
        		echo "<option value='" . esc_attr($slug) . "' $selected>" . esc_html($label) . "</option>";
    		}
            ?>
        </select>

        <!-- Nút điều hướng -->
        <div class="form-buttons">
            <?php if ($current_step > 1): ?>
                <!-- Nút "← Về trước" – xử lý bằng JS, không submit form -->
                <button type="button" id="btn-back" class="btn btn-secondary">← Về trước</button>
            <?php endif; ?>

            <!-- Nút "Tiếp tục" -->
            <button type="submit" class="btn btn-primary">Tiếp tục</button>
        </div>
    </form>
</div>

<!-- ======= Lấy nội dung gốc của trang hiện tại (trang sử dụng template này)  ======== -->
<?php include SWELL_CHILD_PATH . '/functions/match/create-steps/match-create-content.php'; ?>

<?php get_footer(); ?>

<!-- ==============================
     JS: Xử lý nút "← Về trước"
     → Tránh submit khi field required chưa được điền
============================== -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnBack = document.getElementById('btn-back');
    // Nếu tồn tại nút back thì gắn event click
    if (btnBack) {
        btnBack.addEventListener('click', function () {
            // Điều hướng về bước trước
            window.location.href = '?step=<?php echo $current_step - 1; ?>';
        });
    }
});
</script>
