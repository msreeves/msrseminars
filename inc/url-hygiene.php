<?php
/**
 * Demo external URL fixes — portfolio social menus.
 *
 * @package msrseminars
 */

/**
 * @param string $url Raw menu URL.
 * @return string
 */
function msrseminars_normalize_external_url( $url ) {
	$url = is_string( $url ) ? trim( $url ) : '';
	if ( $url === '' ) {
		return '';
	}

	if ( stripos( $url, 'https://www.youtube.co.uk' ) === 0 ) {
		return 'https://www.youtube.com' . substr( $url, strlen( 'https://www.youtube.co.uk' ) );
	}
	if ( stripos( $url, 'http://www.youtube.co.uk' ) === 0 ) {
		return 'https://www.youtube.com' . substr( $url, strlen( 'http://www.youtube.co.uk' ) );
	}

	return $url;
}
