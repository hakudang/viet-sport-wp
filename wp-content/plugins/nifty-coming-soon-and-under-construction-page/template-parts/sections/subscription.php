<?php
/**
 * Subscription template
 *
 * @package NCSUCP
 */

// Built-in form status.
$nifty_form = nifty_cs_get_option( 'enable_sign_up_form' );

?>
<div class="nifty-block nifty-subscription">

	<?php if ( 'off' !== $nifty_form ) : ?>

		<div class="nifty-subscribe nifty-text-center">

			<form>
				<?php $signup_intro = nifty_cs_get_option( 'sign_up_form_intro_text' ); ?>

				<?php if ( ! empty( $signup_intro ) ) : ?>
					<div class="nifty-heading">
						<h3><?php echo esc_html( $signup_intro ); ?></h3>
					</div><!-- .nifty-heading -->
				<?php endif; ?>

				<div class="nifty-subscribe-form">
					<input type="text" autocomplete="off" placeholder="<?php echo esc_attr( nifty_cs_get_option( 'enter_email_text' ) ); ?>" />
					<input type="submit" class="button prefix" value="<?php echo esc_attr( nifty_cs_get_option( 'sign_up_button_text' ) ); ?>" />
				</div><!-- .nifty-subscribe-form -->
			</form>

			<div class="nifty-subscribe-message"></div>

		</div><!-- .nifty-subscribe -->
	<?php else : ?>

		<?php $custom_signup_form = nifty_cs_get_option( 'insert_custom_signup_form' ); ?>

		<?php if ( strlen( $custom_signup_form ) > 0 ) : ?>
			<div class="nifty-subscribe-custom">
				<?php echo $custom_signup_form; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div><!-- .nifty-subscribe-custom -->
		<?php endif; ?>

	<?php endif; ?>

</div><!-- .nifty-subscription -->
