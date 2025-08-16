<?php
/**
 * Template Name: Search Match Page
 * File: swell_child/page-search-match_tem.php
 * Description: Trang tạo và tìm kiếm sân chơi gần bạn (/match)
 */
get_header();
?>
<main id="primary" class="match-page">
  <div class="l-container">

    <!-- Phần 1: Nội dung page /match -->
    <article <?php post_class('c-entry'); ?>>
      <div class="post_content">
        <?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?>
      </div>
    </article>

    <!-- Phần 2: Form tìm theo postcode -->
    <section class="match-form match-form--postcode" aria-labelledby="match-form-postcode-title">
      <h2 id="match-form-postcode-title" class="screen-reader-text">Tìm sân theo postcode</h2>
      <?php get_template_part('templates/match/match-search-by-postcode-form_tem'); ?>
    </section>

    <!-- Phần 3: Form tìm theo tỉnh thành -->
    <section class="match-form match-form--prefecture" aria-labelledby="match-form-pref-title">
      <h2 id="match-form-pref-title" class="screen-reader-text">Tìm sân theo tỉnh thành</h2>
      <?php get_template_part('templates/match/match-search-by-district-form_tem'); ?>
    </section>

    <!-- Phần 4: Danh sách sân chơi -->
    <section class="match-list-wrapper" aria-labelledby="match-list-title">
      <h2 id="match-list-title">Danh sách sân chơi</h2>
      <?php echo do_shortcode('[match-list]'); ?>
    </section>

  </div>
</main>
<?php get_footer(); ?>
