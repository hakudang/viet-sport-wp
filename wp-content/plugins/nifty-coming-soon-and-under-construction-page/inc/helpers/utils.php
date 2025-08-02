<?php
/**
 * Utilities helpers
 *
 * @package NCSUCP
 */

/**
 * Render attributes.
 *
 * @since 1.0.0
 *
 * @param array $attributes Attributes.
 * @param bool  $display Whether to echo or not.
 */
function nifty_cs_render_attr( $attributes, $display = true ) {
	if ( empty( $attributes ) ) {
		return;
	}

	$html = '';

	foreach ( $attributes as $name => $value ) {
		$esc_value = '';

		if ( 'class' === $name && is_array( $value ) ) {
			$value = join( ' ', array_unique( $value ) );
		}

		if ( false !== $value && 'href' === $name ) {
			$esc_value = esc_url( $value );
		} elseif ( false !== $value ) {
			$esc_value = esc_attr( $value );
		}

		if ( ! in_array( $name, array( 'class', 'id', 'title', 'style', 'name' ), true ) ) {
			$html .= false !== $value ? sprintf( ' %s="%s"', esc_html( $name ), $esc_value ) : esc_html( " {$name}" );
		} else {
			$html .= $value ? sprintf( ' %s="%s"', esc_html( $name ), $esc_value ) : '';
		}
	}

	if ( ! empty( $html ) && true === $display ) {
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	} else {
		return $html;
	}
}

/**
 * Return post ID by slug.
 *
 * @since 3.0.0
 *
 * @param string $slug Slug.
 * @param string $post_type Post type.
 * @return int Post ID.
 */
function nifty_cs_get_post_by_slug( $slug, $post_type = 'post' ) {
	global $wpdb;

	// phpcs:disable
	$post_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type=%s AND post_name = %s LIMIT 1;", $post_type, $slug ) );
	// phpcs:enable

	return $post_id;
}

/**
 * Generates CSS rules.
 *
 * @since 1.0.0
 *
 * @param mixed $value Value.
 * @param array $args Arguments.
 * @return string CSS rules string.
 */
function nifty_cs_generate_css( $value, $args = array() ) {
	$value = esc_attr( $value );

	$rules = '';

	foreach ( $args as $arg ) {
		$item = wp_parse_args(
			$arg,
			array(
				'selector' => '',
				'property' => '',
				'unit'     => '',
				'prefix'   => '',
				'suffix'   => '',
			)
		);

		if ( empty( $item['selector'] ) || empty( $item['property'] ) ) {
			continue;
		}

		$selector = $item['selector'];
		$property = $item['property'];
		$unit     = $item['unit'];
		$prefix   = $item['prefix'];
		$suffix   = $item['suffix'];

		if ( 'font-family' === $property ) {
			$value = "'{$value}'";
		}

		$css = "{$selector}{{$property}:{$prefix}{$value}{$unit}{$suffix};}\n";

		$rules .= $css;
	}

	return $rules;
}

/**
 * Return site slug.
 *
 * @since 1.0.0
 *
 * @param string $url URL.
 * @return string Site slug.
 */
function nifty_cs_get_site_slug( $url ) {
	$output = '';

	$pieces = wp_parse_url( $url );

	$domain = isset( $pieces['host'] ) ? $pieces['host'] : '';

	if ( preg_match( '/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs ) ) {
		$output = explode( '.', $regs['domain'] )[0];
	}

	return $output;
}
