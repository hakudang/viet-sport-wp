<?php
/**
 * Admin page
 *
 * @package NCSUCP
 */

use Nilambar\Welcome\Welcome;

add_action(
	'wp_welcome_init',
	function() {
		$obj = new Welcome( 'plugin', 'nifty-coming-soon-and-under-construction-page' );

		$obj->set_page(
			array(
				'top_level_menu' => true,
				'menu_title'     => esc_html__( 'Nifty Options', 'nifty-coming-soon-and-under-construction-page' ),
				'menu_slug'      => 'nifty-coming-soon',
				'page_title'     => esc_html__( 'Nifty Coming Soon', 'nifty-coming-soon-and-under-construction-page' ),
				/* translators: %s: version. */
				'page_subtitle'  => sprintf( esc_html__( 'Version: %s', 'nifty-coming-soon-and-under-construction-page' ), NCSUCP_VERSION ),
				'menu_icon'      => NCSUCP_URL . '/assets/images/menu.png',
			)
		);

		$obj->set_quick_links(
			array(
				array(
					'text' => 'View Details',
					'url'  => 'https://wphait.com/plugins/nifty-coming-soon-and-under-construction-page/',
					'type' => 'primary',
				),
				array(
					'text' => 'View Demo',
					'url'  => 'https://dandure.com/ncsucp/free/',
					'type' => 'secondary',
				),
				array(
					'text' => 'Leave a Review',
					'url'  => 'https://wordpress.org/support/plugin/nifty-coming-soon-and-under-construction-page/reviews/#new-post',
					'type' => 'secondary',
				),
			)
		);

		$obj->add_tab(
			array(
				'id'    => 'welcome',
				'title' => 'Welcome',
				'type'  => 'grid',
				'items' => array(
					array(
						'title'       => 'Customize Coming Soon',
						'icon'        => 'dashicons dashicons-admin-customizer',
						'description' => 'You can customize plugin options using Customizer.',
						'button_text' => 'Customize',
						'button_url'  => nifty_cs_get_customizer_url(),
						'button_type' => 'primary',
					),
					array(
						'title'       => 'Get Support',
						'icon'        => 'dashicons dashicons-editor-help',
						'description' => 'Please visit the support forum if you have any queries or support request.',
						'button_text' => 'Visit Support',
						'button_url'  => 'https://wordpress.org/support/plugin/nifty-coming-soon-and-under-construction-page/#new-post',
						'button_type' => 'secondary',
						'is_new_tab'  => true,
					),
					array(
						'title'       => 'Plugin Documentation',
						'icon'        => 'dashicons dashicons-admin-page',
						'description' => 'Please check the plugin documentation for detailed information on how to setup and customize it.',
						'button_text' => 'Documentation',
						'button_url'  => 'https://dandure.com/documentation/nifty-coming-soon-and-under-construction-page/',
						'button_type' => 'secondary',
						'is_new_tab'  => true,
					),
					array(
						'title'       => 'View Premium Demos',
						'icon'        => 'dashicons dashicons-desktop',
						'description' => 'Several premade themes are available in the premium version. You can check those out using following link.',
						'button_text' => 'View Demos',
						'button_url'  => 'https://dandure.com/ncsucp',
						'button_type' => 'secondary',
						'is_new_tab'  => true,
					),
				),
			)
		);

		$obj->add_tab(
			array(
				'id'              => 'themes',
				'title'           => 'Themes',
				'type'            => 'custom',
				'render_callback' => 'nifty_cs_render_themes_tab_content',
			)
		);

		$obj->add_tab(
			array(
				'id'             => 'free-vs-pro',
				'title'          => 'Free vs Pro',
				'type'           => 'comparison',
				'upgrade_button' => array(
					'url' => NCSUCP_UPGRADE_URL,
				),
				'items'          => nifty_cs_get_comparison_items(),
			)
		);

		$obj->set_sidebar(
			array(
				'render_callback' => 'nifty_cs_render_welcome_sidebar',
			)
		);

		$obj->run();
	}
);

/**
 * Render welcome page sidebar content.
 *
 * @since 3.0.2
 *
 * @param Welcome $welcome_object Instance of Welcome class.
 */
function nifty_cs_render_welcome_sidebar( $welcome_object ) {
	$welcome_object->render_sidebar_box(
		array(
			'title'        => 'Upgrade to Premium',
			'content'      => 'Buy pro plugin for additional blocks and beautiful premade themes.',
			'class'        => 'gray',
			'button_text'  => 'Buy Pro Plugin',
			'button_url'   => NCSUCP_UPGRADE_URL,
			'button_class' => 'button button-primary button-upgrade',
		),
		$welcome_object
	);

	$welcome_object->render_sidebar_box(
		array(
			'title'        => 'Leave a Review',
			'content'      => $welcome_object->get_stars() . sprintf( 'Are you enjoying %1$s? We would appreciate a review.', $welcome_object->get_name() ),
			'button_text'  => 'Submit Review',
			'button_url'   => 'https://wordpress.org/support/plugin/nifty-coming-soon-and-under-construction-page/reviews/#new-post',
			'button_class' => 'button',
		),
		$welcome_object
	);

	$welcome_object->render_sidebar_box(
		array(
			'title'   => 'Our Plugins',
			'content' => '<div class="wpc-plugins-list"><ol><li><a href="https://wphait.com/plugins/nifty-coming-soon-and-under-construction-page/" target="_blank">Coming Soon & Maintenance Mode Page</a></li>
			<li><a href="https://wphait.com/plugins/post-grid-elementor-addon/" target="_blank">Post Grid Elementor Addon</a></li>
			</ol></div>',
		),
		$welcome_object
	);
}

/**
 * Render themes tab content.
 *
 * @since 3.0.2
 */
function nifty_cs_render_themes_tab_content() {
	?>
	<p>Buy pro plugin to unlock beautiful premade themes.</p>

	<a href="<?php echo esc_url( NCSUCP_UPGRADE_URL ); ?>" class="button button-primary" target="_blank">Buy Pro Plugin</a>

	<?php $all_themes = nifty_cs_get_themes(); ?>
		<div class="ncs-themes-grid wpw-grid wpw-col-3">
			<?php foreach ( $all_themes as $theme_key => $theme ) : ?>
				<?php
				$is_featured    = ( isset( $theme['new'] ) && true === $theme['new'] ) ? true : false;
				$featured_class = $is_featured ? 'ncs-theme-featured' : '';
				?>

				<div class="ncs-theme <?php echo esc_attr( $featured_class ); ?>" data-tags='<?php echo ( isset( $theme['tags'] ) ) ? wp_json_encode( $theme['tags'] ) : ''; ?>'>
					<?php if ( $is_featured ) : ?>
						<span class="featured">NEW</span>
					<?php endif; ?>
					<a href="<?php echo esc_url( $theme['preview_url'] ); ?>" target="_blank">
						<img src="<?php echo esc_url( NCSUCP_URL . "/themes/{$theme_key}/preview.jpg" ); ?>" alt="<?php echo esc_attr( $theme['label'] ); ?>" />
					</a>
					<div class="ncs-theme-content">
						<h4><a href="<?php echo esc_url( $theme['preview_url'] ); ?>" target="_blank"><?php echo esc_html( $theme['label'] ); ?></a></h4>
						<div class="buttons">
							<a href="<?php echo esc_url( $theme['preview_url'] ); ?>" class="preview" target="_blank"><span class="preview-text">Preview</span><span class="preview-icon dashicons dashicons-external"></span></a>
						</div><!-- .buttons -->
					</div><!-- .ncs-theme-content -->
				</div>

			<?php endforeach; ?>
		</div><!-- .ncs-themes-grid -->


	<?php
}

/**
 * Load admin page assets.
 *
 * @since 1.0.0
 *
 * @param string $hook Hook name.
 */
function nifty_cs_load_admin_page_assets( $hook ) {
	if ( 'toplevel_page_nifty-coming-soon' !== $hook ) {
		return;
	}

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_style( 'nifty-cs-admin-page', NCSUCP_URL . '/assets/css/admin-page' . $min . '.css', array(), NCSUCP_VERSION );
}

add_action( 'admin_enqueue_scripts', 'nifty_cs_load_admin_page_assets' );
