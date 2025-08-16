<?php
// =============================================
// BƯỚC 1: NHẬP TIÊU ĐỀ SÂN CHƠI
// =============================================

// Bắt đầu session nếu chưa có
if (!session_id()) session_start();

// Lấy dữ liệu từ session (nếu có)
$data = $_SESSION['san-choi'] ?? [];

// =============================================
// Xử lý khi người dùng nhấn "Tiếp tục"
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra nonce để bảo vệ CSRF
    if (
        !isset($_POST['tao_san_choi_nonce']) ||
        !wp_verify_nonce($_POST['tao_san_choi_nonce'], 'tao_san_choi_action')
    ) {
        echo '<p>❌ Phiên không hợp lệ.</p>';
        exit;
    }

    // Gộp dữ liệu vừa nhập với session
    $_SESSION['san-choi'] = array_merge($data, $_POST);

    // Chuyển đến bước tiếp theo
    wp_redirect('?step=' . ($current_step + 1));
    exit;
}

// Load header từ theme
get_header();
?>

<!-- ==============================
     FORM BƯỚC 1: Nhập tiêu đề
============================== -->
<div class="form-tao-san-choi form-step-<?php echo $current_step; ?>">
    <form method="post">
        <!-- CSRF nonce -->
        <?php wp_nonce_field('tao_san_choi_action', 'tao_san_choi_nonce'); ?>

        <!-- Tiêu đề sân chơi -->
        <label for="title">Tiêu đề: <span style="color:red">*</span></label>
        <input
            type="text"
            id="title"
            name="title"
            required
            value="<?php echo esc_attr($data['title'] ?? ''); ?>"
        >

        <!-- Nút điều hướng -->
        <div class="form-buttons">
            <!-- Không có nút "Về trước" vì đây là bước đầu tiên -->
            <button
                id="next-btn"
                type="submit"
                class="btn btn-primary"
                disabled
            >
                Tiếp tục
            </button>
        </div>
    </form>
</div>

<!-- ======= Lấy nội dung gốc của trang hiện tại (trang sử dụng template này)  ======== -->
<?php include SWELL_CHILD_PATH . '/functions/match/create-steps/match-create-rules.php'; ?>

<?php get_footer(); ?>

<!-- ==============================
     STYLE: Trạng thái khi nút bị disable
============================== -->
<style>
    #next-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>

<!-- ==============================
     JS: Bật nút "Tiếp tục" khi có nội dung
============================== -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const titleInput = document.getElementById('title');
    const nextBtn = document.getElementById('next-btn');

    function toggleButton() {
        const value = titleInput.value.trim();
        nextBtn.disabled = value === '';
    }

    // Gắn các sự kiện cần thiết
    ['input', 'change', 'blur'].forEach(evt =>
        titleInput.addEventListener(evt, toggleButton)
    );

    // Kiểm tra lần đầu khi trang load
    toggleButton();
});
</script>
