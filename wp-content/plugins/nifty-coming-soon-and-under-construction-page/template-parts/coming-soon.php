<?php
/**
 * Main coming template part
 *
 * @package NCSUCP
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php
	if ( is_customize_preview() ) {
		wp_head();
	}
	?>
	<?php nifty_cs_head(); ?>
</head>

<body <?php nifty_cs_body_class(); ?>>
	<div class="nifty-main-wrapper" id="nifty-full-wrapper">
		<div class="nifty-container nifty-text-center">

			<?php nifty_cs_render_preloader(); ?>

			<?php
			$all_blocks = nifty_cs_all_page_blocks();

			$page_blocks = nifty_cs_get_option( 'page_blocks' );

			if ( ! empty( $page_blocks ) && is_array( $page_blocks ) ) {
				foreach ( $page_blocks as $item ) {
					if ( isset( $all_blocks[ $item ] ) ) {
						$template = $all_blocks[ $item ]['template'];
						require NCSUCP_DIR . "/{$template}";
					}
				}
			}
			?>

		</div><!-- .nifty-container -->
	</div><!-- .nifty-main-wrapper -->

	<?php
	if ( is_customize_preview() ) {
		wp_footer();
	}
	?>
	<?php nifty_cs_footer(); ?>

</body>
</html>
