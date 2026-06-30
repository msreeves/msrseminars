<?php
/**
 * Panelist profile rendering — escaped output for singles and listings.
 *
 * @package msrseminars
 */

/**
 * Sanitize social platform slug for Font Awesome brand icon class.
 *
 * @param mixed $platform Raw ACF value.
 * @return string Safe slug for fa-{slug} (empty if invalid).
 */
function msrseminars_sanitize_social_platform_slug( $platform ) {
	$slug = sanitize_key( (string) $platform );
	if ( '' === $slug ) {
		return '';
	}
	if ( 'x' === $slug ) {
		$slug = 'twitter';
	}
	$allowed = array( 'facebook', 'twitter', 'linkedin', 'instagram', 'youtube', 'tiktok' );
	return in_array( $slug, $allowed, true ) ? $slug : '';
}

/**
 * Job title + company lines with icons.
 *
 * @param int|null $post_id Post ID.
 * @return void
 */
function msrseminars_render_person_job_company( $post_id = null ) {
	$post_id   = null === $post_id ? get_the_ID() : (int) $post_id;
	$job_title = get_field( 'job_title', $post_id );
	$company   = get_field( 'company', $post_id );

	if ( $job_title ) {
		printf(
			'<h2 class="seminars-person__job"><i class="fa-solid fa-briefcase" aria-hidden="true"></i> %s</h2>',
			esc_html( (string) $job_title )
		);
	}
	if ( $company ) {
		printf(
			'<h3 class="seminars-person__company"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> %s</h3>',
			esc_html( (string) $company )
		);
	}
}

/**
 * Social link row from ACF repeater.
 *
 * @param int|null $post_id Post ID.
 * @return void
 */
function msrseminars_render_person_social_links( $post_id = null ) {
	$post_id = null === $post_id ? get_the_ID() : (int) $post_id;
	if ( ! have_rows( 'social', $post_id ) ) {
		return;
	}
	echo '<div class="seminars-person__social">';
	while ( have_rows( 'social', $post_id ) ) {
		the_row();
		$url      = (string) get_sub_field( 'link' );
		$platform = msrseminars_sanitize_social_platform_slug( get_sub_field( 'platform' ) );
		if ( '' === $url || '' === $platform ) {
			continue;
		}
		printf(
			'<a class="seminars-person__social-link" href="%s" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-%s" aria-hidden="true"></i><span class="screen-reader-text">%s</span></a>',
			esc_url( $url ),
			esc_attr( $platform ),
			esc_html( ucfirst( $platform ) )
		);
	}
	echo '</div>';
}

/**
 * WYSIWYG profile body inside entry-content.
 *
 * @param int|null $post_id Post ID.
 * @return void
 */
function msrseminars_render_person_profile( $post_id = null ) {
	$post_id = null === $post_id ? get_the_ID() : (int) $post_id;
	$profile = get_field( 'profile', $post_id );
	if ( ! $profile ) {
		return;
	}
	echo '<div class="entry-content seminars-person__profile">';
	echo wp_kses_post( (string) $profile );
	echo '</div>';
}

/**
 * Plain-text profile excerpt for archive cards.
 *
 * @param int|null $post_id Post ID.
 * @param int      $words   Max words.
 * @return string Escaped excerpt (empty when no profile).
 */
function msrseminars_get_person_profile_excerpt( $post_id = null, $words = 24 ) {
	$post_id = null === $post_id ? get_the_ID() : (int) $post_id;
	$profile = get_field( 'profile', $post_id );
	if ( ! $profile ) {
		return '';
	}
	return wp_trim_words( wp_strip_all_tags( (string) $profile ), (int) $words, '…' );
}

/**
 * Linked person chips in agenda schedule rows.
 *
 * @param mixed  $people  Post objects or IDs from ACF relationship field.
 * @param string $heading Section heading.
 * @return void
 */
function msrseminars_render_agenda_people_list( $people, $heading ) {
	if ( empty( $people ) || ! is_array( $people ) ) {
		return;
	}

	echo '<h3>' . esc_html( $heading ) . '</h3>';
	echo '<div class="panelists">';

	foreach ( $people as $person ) {
		$post_id = $person instanceof WP_Post ? (int) $person->ID : (int) $person;
		if ( $post_id <= 0 ) {
			continue;
		}
		$permalink = get_permalink( $post_id );
		$title     = get_the_title( $post_id );
		$thumb_id  = msrseminars_sanitize_attachment_id( (int) get_post_thumbnail_id( $post_id ) );
		$caption   = $thumb_id ? wp_get_attachment_caption( $thumb_id ) : '';
		$img_alt   = '' !== (string) $caption ? (string) $caption : $title;

		printf( '<a href="%s">', esc_url( $permalink ) );
		echo '<div class="panel"><div class="listing-image">';
		if ( $thumb_id ) {
			echo wp_get_attachment_image(
				$thumb_id,
				'medium',
				false,
				array(
					'alt'      => $img_alt,
					'loading'  => 'lazy',
					'decoding' => 'async',
				)
			);
		}
		printf(
			'</div><div class="listing-text my-auto"><h4>%s</h4></div></div></a>',
			esc_html( $title )
		);
	}

	echo '</div>';
}
