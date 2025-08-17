<?php
// =============================================
// BƯỚC 2: NHẬP THỜI GIAN – ngày, giờ, số giờ
// =============================================

// Bắt đầu session nếu chưa có
if (!session_id()) session_start();

// Lấy dữ liệu session đã lưu (nếu có)
$data = $_SESSION['match'] ?? [];

// Tự động gợi ý ngày bắt đầu sau hôm nay 2 ngày
$today_plus_2 = date('Y-m-d', strtotime('+2 days'));

// =============================================
// Xử lý khi người dùng nhấn nút "Tiếp tục"
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra nonce để chống CSRF
    if (
        !isset($_POST['tao_san_choi_nonce']) ||
        !wp_verify_nonce($_POST['tao_san_choi_nonce'], 'tao_san_choi_action')
    ) {
        echo '<p>❌ Phiên không hợp lệ.</p>';
        exit;
    }

    // Gộp dữ liệu mới với dữ liệu cũ vào session
    $_SESSION['match'] = array_merge($data, $_POST);

    // Chuyển sang bước kế tiếp
    wp_redirect('?step=' . ($current_step + 1));
    exit;
}

// Load header của theme
get_header();
?>

<!-- ==============================
     FORM BƯỚC 2: Nhập thời gian
============================== -->
<div class="form-create-match match-create-step<?php echo $current_step; ?>">
    <form method="post">
        <!-- CSRF nonce -->
        <?php wp_nonce_field('tao_san_choi_action', 'tao_san_choi_nonce'); ?>

        <!-- Ngày khai mạc -->
        <label for="start_date">Ngày khai mạc: <span style="color:red">*</span></label>
        <input type="date" id="start_date" name="start_date"
            value="<?php echo esc_attr($data['start_date'] ?? $today_plus_2); ?>" required>

        <div class="start-time">
            <!-- Giờ khai mạc -->
            <label for="start_time">Lúc: <span style="color:red">*</span></label>
            <input type="time" id="start_time" name="start_time"
                value="<?php echo esc_attr($data['start_time'] ?? '18:00'); ?>" required>

            <!-- Số giờ chơi -->
            <label for="hours">Số giờ: <span style="color:red">*</span></label>
            <input type="number" id="hours" name="hours" min="1"
                value="<?php echo esc_attr($data['hours'] ?? 2); ?>" required>
        </div>

        <!-- Ngày dừng tuyển -->
        <label for="stop_date">Ngày dừng tuyển: <span style="color:red">*</span></label>
        <input type="date" id="stop_date" name="stop_date"
            value="<?php echo esc_attr($data['stop_date'] ?? ''); ?>" readonly required>

        <div class="closed-time">
            <!-- Số ngày trước khai mạc -->
            <label for="days">Trước khai mạc: <span style="color:red">*</span></label>
            <input type="number" id="days" name="days" min="1"
                value="<?php echo esc_attr($data['days'] ?? 2); ?>" required> ngày

            <!-- Giờ dừng tuyển -->
            <label for="stop_time">Lúc: <span style="color:red">*</span></label>
            <input type="time" id="stop_time" name="stop_time"
                value="<?php echo esc_attr($data['stop_time'] ?? '20:00'); ?>" required>
        </div>

        <!-- Nút điều hướng -->
        <div class="form-buttons">
            <?php if ($current_step > 1): ?>
                <!-- Nút quay lại (không submit form) -->
                <button type="button" id="btn-back" class="btn btn-secondary">← Về trước</button>
            <?php endif; ?>
            <!-- Nút tiếp tục -->
            <button type="submit" class="btn btn-primary">Tiếp tục</button>
        </div>
    </form>
</div>

<!-- ======= Lấy nội dung gốc của trang hiện tại (trang sử dụng template này)  ======== -->
<?php include SWELL_CHILD_PATH . '/functions/match/create-steps/match-create-content.php'; ?>

<?php get_footer(); ?>

<!-- ==============================
     JS: Tự động tính ngày dừng tuyển
============================== -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('start_date');
        const daysInput = document.getElementById('days');
        const stopDateInput = document.getElementById('stop_date');

        function updateStopDate() {
            const startDateVal = startDateInput.value;
            const daysVal = parseInt(daysInput.value);

            if (startDateVal && !isNaN(daysVal)) {
                const startDate = new Date(startDateVal);
                const stopDate = new Date(startDate);
                stopDate.setDate(startDate.getDate() - daysVal);

                const yyyy = stopDate.getFullYear();
                const mm = String(stopDate.getMonth() + 1).padStart(2, '0');
                const dd = String(stopDate.getDate()).padStart(2, '0');

                stopDateInput.value = `${yyyy}-${mm}-${dd}`;
            }
        }

        daysInput.addEventListener('input', updateStopDate);
        startDateInput.addEventListener('change', updateStopDate);

        // Trigger mặc định khi form load
        if (startDateInput.value) updateStopDate();
    });
</script>

<!-- ==============================
     JS: Xử lý nút "← Về trước" bằng điều hướng URL
============================== -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnBack = document.getElementById('btn-back');
        if (btnBack) {
            btnBack.addEventListener('click', function() {
                // Không submit form, chỉ chuyển URL
                window.location.href = '?step=<?php echo $current_step - 1; ?>';
            });
        }
    });
</script>