<?php
/**
 * Front-end performance helpers — LCP preload, image dimensions.
 *
 * @package msrseminars
 */

/**
 * Resolve an ACF image field to a valid attachment ID.
 *
 * @param mixed $value Raw ACF value.
 * @return int Attachment ID or 0.
 */
function msrseminars_acf_attachment_id( $value ) {
	if ( '' === $value || null === $value ) {
		return 0;
	}
	if ( is_numeric( $value ) ) {
		return msrseminars_sanitize_attachment_id( (int) $value );
	}
	if ( is_array( $value ) && ! empty( $value['ID'] ) ) {
		return msrseminars_sanitize_attachment_id( (int) $value['ID'] );
	}
	if ( is_string( $value ) ) {
		$trim = trim( $value );
		if ( '' === $trim ) {
			return 0;
		}
		if ( ctype_digit( $trim ) ) {
			return msrseminars_sanitize_attachment_id( (int) $trim );
		}
		$maybe = maybe_unserialize( $trim );
		if ( $maybe !== $trim ) {
			return msrseminars_acf_attachment_id( $maybe );
		}
	}
	return 0;
}

/**
 * Preload programme home hero background (LCP candidate).
 *
 * @return void
 */
function msrseminars_preload_programme_home_hero() {
	if ( is_admin() || ! function_exists( 'msrseminars_is_programme_home' ) || ! msrseminars_is_programme_home() ) {
		return;
	}
	if ( ! function_exists( 'get_field' ) || ! (bool) get_field( 'hero', 'option' ) ) {
		return;
	}

	$attachment_id = msrseminars_acf_attachment_id( get_field( 'image', 'option' ) );
	$src           = $attachment_id ? wp_get_attachment_image_url( $attachment_id, 'large' ) : '';
	if ( ! $src ) {
		$src = msrseminars_hero_background_url( get_field( 'image', 'option' ) );
	}
	if ( ! $src ) {
		return;
	}

	printf(
		'<link rel="preload" as="image" href="%s" fetchpriority="high" />' . "\n",
		esc_url( $src )
	);
}
add_action( 'wp_head', 'msrseminars_preload_programme_home_hero', 2 );
