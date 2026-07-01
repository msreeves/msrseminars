<?php
/**
 * Search helpers — highlight markup and query guards.
 *
 * @package msrseminars
 */

/**
 * Allowed HTML for search highlight spans.
 *
 * @return array<string, array<string, bool>>
 */
function msrseminars_search_highlight_allowed_html() {
	return array(
		'strong' => array(
			'class' => array( 'search-highlight' ),
		),
		'p'      => array(),
	);
}

/**
 * Highlight search terms in plain text and return safe HTML.
 *
 * @param string $text Source text.
 * @return string
 */
function msrseminars_search_highlight_text( $text ) {
	$query = trim( (string) get_search_query() );
	$text  = (string) $text;
	if ( '' === $query || '' === $text ) {
		return esc_html( $text );
	}

	$parts = preg_split( '/\s+/u', $query, -1, PREG_SPLIT_NO_EMPTY );
	if ( ! $parts ) {
		return esc_html( $text );
	}

	$pattern = '/' . implode(
		'|',
		array_map(
			static function ( $part ) {
				return preg_quote( $part, '/' );
			},
			$parts
		)
	) . '/iu';

	$highlighted = preg_replace( $pattern, '<strong class="search-highlight">$0</strong>', $text );
	if ( ! is_string( $highlighted ) ) {
		return esc_html( $text );
	}

	return wp_kses( $highlighted, msrseminars_search_highlight_allowed_html() );
}

/**
 * Highlighted post title for search results.
 *
 * @return string Safe HTML.
 */
function msrseminars_search_title_highlight() {
	return msrseminars_search_highlight_text( get_the_title() );
}

/**
 * Highlighted excerpt for search results.
 *
 * @return string Safe HTML wrapped in paragraph.
 */
function msrseminars_search_excerpt_highlight() {
	return '<p>' . msrseminars_search_highlight_text( get_the_excerpt() ) . '</p>';
}

/**
 * Exclude pages from front-end search (nominees/judges remain CPT-driven routes).
 *
 * @return void
 */
function msrseminars_exclude_pages_from_search() {
	global $wp_post_types;
	if ( isset( $wp_post_types['page'] ) ) {
		$wp_post_types['page']->exclude_from_search = true;
	}
}
add_action( 'init', 'msrseminars_exclude_pages_from_search' );

/**
 * Backward-compatible aliases (legacy templates).
 *
 * @deprecated Use msrseminars_search_excerpt_highlight().
 */
function search_excerpt_highlight() {
	echo msrseminars_search_excerpt_highlight(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * @deprecated Use msrseminars_search_title_highlight().
 */
function search_title_highlight() {
	echo msrseminars_search_title_highlight(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
