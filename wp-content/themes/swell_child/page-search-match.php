<?php

/**
 * Template Name: Search Match Page
 * File Name : page-search-match.php
 * Description: Trang tạo và tìm kiếm sân chơi gần bạn
 */

// WordPress sẽ tự động tìm file header-match.php
get_header('match'); ?>

<div class="container">
    <!-- <h2>Trang Tìm Sân Chơi</h2> -->

    <div class="match-page-content">
        <?php
        // Hiển thị nội dung trong editor của trang /match
        while (have_posts()) : the_post();
            the_content();
        endwhile;
        ?>
    </div>

    <!-- Hiển thị form tìm kiếm theo postcode -->
    <div class="match-form-wrapper">
        <?php get_template_part('templates/match/match-search-by-postcode-form'); ?>
    </div>
    <!-- Hiển thị form tìm kiếm theo tỉnh thành -->
    <div class="match-form-wrapper">
        <?php get_template_part('templates/match/match-search-by-district-form'); ?>
    </div>
</div>


<?php get_footer(); ?>
