<?php
/**
 * Seminars SEO — meta description (supersedes legacy tenweb_meta_description).
 *
 * @package msrseminars
 */

/**
 * Whether copy looks like Latin / lorem placeholder (not programme meta).
 *
 * @param string $text Plain text.
 * @return bool
 */
function msrseminars_seo_is_placeholder_copy( $text ) {
	$text = strtolower( trim( wp_strip_all_tags( $text ) ) );
	if ( '' === $text ) {
		return true;
	}

	$patterns = array(
		'lorem ipsum',
		'class aptent taciti',
		'dolor sit amet',
		'ut et neque lacus',
		'in et arcu eu dui',
		'nulla consequat et mas',
	);

	foreach ( $patterns as $pattern ) {
		if ( str_contains( $text, $pattern ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Normalise and trim a meta description string.
 *
 * @param string $description Raw description.
 * @return string
 */
function msrseminars_seo_normalize_description( $description ) {
	$description = wp_strip_all_tags( (string) $description );
	$description = strip_shortcodes( $description );
	$description = preg_replace( '/\s+/', ' ', $description );
	$description = trim( (string) $description );

	if ( '' === $description || msrseminars_seo_is_placeholder_copy( $description ) ) {
		return '';
	}

	return mb_substr( $description, 0, 300, 'UTF-8' );
}

/**
 * Curated meta descriptions for programme pages (slug-keyed).
 *
 * @return array<string, string>
 */
function msrseminars_seo_curated_page_descriptions() {
	return array(
		'agenda'       => msrseminars_get_seo_agenda_description(),
		'panelists'    => msrseminars_get_panelists_page_lead(),
		'partners'     => msrseminars_get_partners_page_lead(),
		'for-delegates' => msrseminars_get_delegates_page_lead(),
	);
}

/**
 * Meta description for a singular page or CPT.
 *
 * @param WP_Post $post Post object.
 * @return string
 */
function msrseminars_seo_description_for_post( WP_Post $post ) {
	$excerpt = msrseminars_seo_normalize_description( $post->post_excerpt );
	if ( '' !== $excerpt ) {
		return $excerpt;
	}

	$curated = msrseminars_seo_curated_page_descriptions();
	$slug    = sanitize_title( (string) $post->post_name );
	if ( isset( $curated[ $slug ] ) ) {
		return msrseminars_seo_normalize_description( $curated[ $slug ] );
	}

	return msrseminars_seo_normalize_description( $post->post_content );
}

/**
 * @return void
 */
function msrseminars_render_meta_description() {
	if ( is_admin() ) {
		return;
	}

	if ( is_singular() ) {
		global $post;
		if ( ! $post instanceof WP_Post ) {
			return;
		}

		$description = msrseminars_seo_description_for_post( $post );
		if ( '' !== $description ) {
			echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
		}
		return;
	}

	if ( is_home() || is_front_page() ) {
		$description = msrseminars_seo_normalize_description( (string) get_bloginfo( 'description' ) );
		if ( '' === $description ) {
			$description = msrseminars_seo_normalize_description( msrseminars_get_seo_home_description() );
		}
		if ( '' !== $description ) {
			echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
		}
		return;
	}

	if ( is_search() ) {
		$description = msrseminars_seo_normalize_description( msrseminars_get_seo_search_description() );
		if ( '' !== $description ) {
			echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
		}
		return;
	}

	if ( is_category() ) {
		$description = msrseminars_seo_normalize_description( (string) category_description() );
		if ( '' !== $description ) {
			echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
		}
	}
}
add_action( 'wp_head', 'msrseminars_render_meta_description', 1 );
