<?php
/**
 * Contact template
 *
 * @package NCSUCP
 */

$contact_website = nifty_cs_get_option( 'enter_you_website_or_company_name' );
$contact_address = nifty_cs_get_option( 'enter_your_address' );
$contact_phone   = nifty_cs_get_option( 'enter_your_phone_number' );
$contact_email   = nifty_cs_get_option( 'enter_your_email_address' );
?>

<div class="nifty-block nifty-contact-details">

	<?php if ( ! empty( $contact_website ) ) : ?>
		<h4 class="contact-company-name"><?php echo esc_html( $contact_website ); ?></h4>
	<?php endif; ?>

	<ul>

		<?php if ( ! empty( $contact_address ) || is_customize_preview() ) : ?>
			<li>
				<span aria-hidden="true" class="icon-home"></span>
				<span class="contact-address-content"><?php echo esc_html( $contact_address ); ?></span>
			</li>
		<?php endif; ?>

		<?php if ( ! empty( $contact_phone ) || is_customize_preview() ) : ?>
			<li>
				<span aria-hidden="true" class="icon-phone"></span>
				<span class="contact-phone-content"><?php echo esc_html( $contact_phone ); ?></span>
			</li>
		<?php endif; ?>

		<?php if ( ! empty( $contact_email ) || is_customize_preview() ) : ?>
			<li>
				<span aria-hidden="true" class="icon-envelope"></span>
				<span class="contact-email"><a href="<?php echo esc_url( 'mailto:' . $contact_email ); ?>"><?php echo esc_html( $contact_email ); ?></a></span>
			</li>
		<?php endif; ?>

	</ul>
</div><!-- .nifty-contact-details -->
