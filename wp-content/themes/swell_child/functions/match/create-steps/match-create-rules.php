<?php
// Bảo vệ file nếu bị truy cập trực tiếp
defined('ABSPATH') || exit;

// Lấy nội dung page hiện tại
$page_content = apply_filters(
    'the_content',
    get_post_field('post_content', get_queried_object_id())
);

// Chỉ hiển thị nếu không phải bước 13 hoặc 14
if (!empty($page_content) && !in_array($current_step, [13, 14], true)) :
?>
    <div class="quy-dinh-tao-san">
		<main id="main_content" class="l-main l-article">
            <article
                class="l-main__body p-page"
                data-postid="<?php echo get_queried_object_id(); ?>"
            >
                <div class="c-postContent p-page__content">
                    <?php echo $page_content; ?>
                </div>
            </article>
        </main>
    </div>
<?php endif; ?>
