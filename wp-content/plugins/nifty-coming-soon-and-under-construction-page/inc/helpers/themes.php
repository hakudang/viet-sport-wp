<?php
/**
 * Themes
 *
 * @package NCSUCP
 */

/**
 * Return all themes.
 *
 * @since 1.0.0
 *
 * @return array Themes array.
 */
function nifty_cs_get_themes() {
	return array(
		'charity'        => array(
			'label'       => esc_html__( 'Charity', 'nifty-coming-soon-and-under-construction-page' ),
			'preview_url' => 'https://dandure.com/ncsucp/charity/',
			'new'         => true,
		),
		'furniture'      => array(
			'label'       => esc_html__( 'Furniture', 'nifty-coming-soon-and-under-construction-page' ),
			'preview_url' => 'https://dandure.com/ncsucp/furniture/',
			'new'         => true,
		),
		'skin-care'      => array(
			'label'       => esc_html__( 'Skin Care', 'nifty-coming-soon-and-under-construction-page' ),
			'preview_url' => 'https://dandure.com/ncsucp/skin-care/',
			'new'         => true,
		),
		'minimal'        => array(
			'label'       => esc_html__( 'Minimal', 'nifty-coming-soon-and-under-construction-page' ),
			'preview_url' => 'https://dandure.com/ncsucp/minimal/',
		),
		'digital-agency' => array(
			'label'       => esc_html__( 'Digital Agency', 'nifty-coming-soon-and-under-construction-page' ),
			'preview_url' => 'https://dandure.com/ncsucp/digital-agency/',
		),
		'app'            => array(
			'label'       => esc_html__( 'App', 'nifty-coming-soon-and-under-construction-page' ),
			'preview_url' => 'https://dandure.com/ncsucp/app/',
		),
		'cake-shop'      => array(
			'label'       => esc_html__( 'Cake Shop', 'nifty-coming-soon-and-under-construction-page' ),
			'preview_url' => 'https://dandure.com/ncsucp/cake-shop/',
		),
		'coffee-shop'    => array(
			'label'       => esc_html__( 'Coffee Shop', 'nifty-coming-soon-and-under-construction-page' ),
			'preview_url' => 'https://dandure.com/ncsucp/coffee-shop/',
		),
		'construction'   => array(
			'label'       => esc_html__( 'Construction', 'nifty-coming-soon-and-under-construction-page' ),
			'preview_url' => 'https://dandure.com/ncsucp/construction/',
		),
		'dental'         => array(
			'label'       => esc_html__( 'Dental', 'nifty-coming-soon-and-under-construction-page' ),
			'preview_url' => 'https://dandure.com/ncsucp/dental/',
		),
		'fitness'        => array(
			'label'       => esc_html__( 'Fitness', 'nifty-coming-soon-and-under-construction-page' ),
			'preview_url' => 'https://dandure.com/ncsucp/fitness/',
		),
		'fruits'         => array(
			'label'       => esc_html__( 'Fruits', 'nifty-coming-soon-and-under-construction-page' ),
			'preview_url' => 'https://dandure.com/ncsucp/fruits/',
		),
		'kennel-club'    => array(
			'label'       => esc_html__( 'Kennel Club', 'nifty-coming-soon-and-under-construction-page' ),
			'preview_url' => 'https://dandure.com/ncsucp/kennel-club/',
		),
		'yoga'           => array(
			'label'       => esc_html__( 'Yoga', 'nifty-coming-soon-and-under-construction-page' ),
			'preview_url' => 'https://dandure.com/ncsucp/yoga/',
		),
	);
}
