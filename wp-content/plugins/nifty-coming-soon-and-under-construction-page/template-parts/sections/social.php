<?php
/**
 * Social template
 *
 * @package NCSUCP
 */

$social_intro = nifty_cs_get_option( 'social_links_intro_text' );

$socials = array(
	'facebook'  => array(
		'option' => 'facebook_page_or_profile_url',
		'icon'   => 'icon-facebook',
	),
	'twitter'   => array(
		'option' => 'twitter_url',
		'icon'   => 'icon-twitter',
	),
	'youtube'   => array(
		'option' => 'youtube_url',
		'icon'   => 'icon-youtube',
	),
	'linkedin'  => array(
		'option' => 'linkedin_profile_url',
		'icon'   => 'icon-linkedin',
	),
	'pinterest' => array(
		'option' => 'pinterest_url',
		'icon'   => 'icon-pinterest',
	),
	'instagram' => array(
		'option' => 'instagram_url',
		'icon'   => 'icon-instagram',
	),
	'vimeo'     => array(
		'option' => 'vimeo_url',
		'icon'   => 'icon-vimeo',
	),
);
?>
<div class="nifty-block nifty-socials">

	<?php if ( ! empty( $social_intro ) ) : ?>
		<div class="nifty-heading">
			<h3><?php echo esc_html( $social_intro ); ?></h3>
		</div>
	<?php endif; ?>

	<div class="nifty-socials-icons">
		<ul>
		<?php foreach ( $socials as $site ) : ?>
			<?php
			$url = nifty_cs_get_option( $site['option'] );

			if ( empty( $url ) ) {
				continue;
			}
			?>

			<li><a href="<?php echo esc_url( $url ); ?>"><span aria-hidden="true" class="<?php echo esc_attr( $site['icon'] ); ?>"></span></a></li>

		<?php endforeach; ?>
		</ul>
	</div>

</div>
