<?php
/**
 * Subscription template
 *
 * @package NCSUCP
 */

?>
<div class="nifty-block nifty-slider">

	<?php
		$navigation = nifty_cs_get_option( 'disable_navigation' );
		$nifty_form = nifty_cs_get_option( 'enable_sign_up_form' );
	?>

	<div class="nifty-slider-content">
		<div class="swiper nifty-legacy-slider">
			<?php if ( 'off' !== $navigation ) : ?>
				<div class="swiper-pagination"></div>
			<?php endif; ?>

			<div class="swiper-wrapper">
				<!-- Slides -->
				<?php
				$all_blocks = nifty_cs_all_page_blocks();

				$slider_blocks = nifty_cs_get_option( 'slider_blocks' );

				if ( ! empty( $slider_blocks ) && is_array( $slider_blocks ) ) {
					foreach ( $slider_blocks as $item ) {
						if ( isset( $all_blocks[ $item ] ) ) {
							$template = $all_blocks[ $item ]['template'];
							echo '<div class="swiper-slide">';
							require NCSUCP_DIR . "/{$template}";
							echo '</div>';
						}
					}
				}
				?>
			</div>
		</div>
	</div>
</div>
