<?php
/**
 * Animated text template
 *
 * @package NCSUCP
 */

$first_message  = nifty_cs_get_option( 'your_coming_soon_message', true );
$second_message = nifty_cs_get_option( 'enter_second_coming_soon_message', true );

$disable_animation = nifty_cs_get_option( 'disable_animation' );
?>

<div class="nifty-block nifty-coming-soon-message">
	<div id="animated_intro" class="tlt">
		<ul class="texts">
			<?php if ( ! empty( $first_message ) ) : ?>
				<li class="intro-first"><?php echo esc_html( $first_message ); ?></li>
			<?php endif; ?>

			<?php if ( ! empty( $second_message ) && 'off' !== $disable_animation ) : ?>
				<li class="intro-second"><?php echo esc_html( $second_message ); ?></li>
			<?php endif; ?>

			<li> </li>
		</ul>
	</div>
</div>
