<?php

/**
 * Template Name: Search Match Page
 * File: swell_child/page-search-match.php
 * Description: Trang tạo và tìm kiếm sân chơi gần bạn (/match)
 */

get_header(); // dùng header mặc định của SWELL
?>

<main id="primary" class="match-page">
    <!-- Dùng container chuẩn của SWELL -->
     <!-- l-container + article.c-entry > .post_content là combo “chuẩn SWELL” 
      giúp phần 1 giữ đúng định dạng, spacing, typography như mọi trang Page khác -->
    <div class="l-container">

        <!-- Phần 1: Nội dung page /match với markup SWELL để giữ đúng style -->
        <article <?php post_class('c-entry'); ?>>
            <div class="post_content">
                <?php
                // render page hiện tại - Tìm sân chơi - /match page
                while (have_posts()) : the_post(); 
                    the_content(); // SWELL sẽ áp toàn bộ typography/spacing cho block editor
                endwhile;
                ?>
            </div>
        </article>

        <!-- Phần 2: Form tìm theo postcode -->
        <section class="match-form-wrapper" aria-labelledby="match-form-postcode-title">
            <h2 id="match-form-postcode-title" class="screen-reader-text">Tìm sân theo postcode</h2>
            <?php get_template_part('templates/match/match-search-by-postcode-form'); ?>
        </section>

        <!-- Phần 3: Form tìm theo tỉnh thành -->
        <section class="match-form-wrapper" aria-labelledby="match-form-pref-title">
            <h2 id="match-form-pref-title" class="screen-reader-text">Tìm sân theo tỉnh thành</h2>
            <?php get_template_part('templates/match/match-search-by-district-form'); ?>
        </section>

        <!-- Phần 4: Danh sách sân chơi -->
        <section class="match-list-wrapper" aria-labelledby="match-list-title">
            <h2 id="match-list-title">Danh sách sân chơi</h2>
            <?php echo do_shortcode('[match-list]'); // render danh sách sân chơi ?>
        </section>

    </div>
</main>

<?php get_footer(); ?>