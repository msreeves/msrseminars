<?php
/**
 * MSR Seminars ACF options — admin-first site copy and programme URLs.
 *
 * @package msrseminars
 */

/**
 * @param string $field ACF field name.
 * @param string $default Fallback when empty.
 * @return string
 */
function msrseminars_get_option_string( $field, $default = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}
	$value = get_field( $field, 'option' );
	if ( ! is_string( $value ) || '' === trim( $value ) ) {
		return $default;
	}
	return trim( $value );
}

/**
 * @param string $field ACF field name.
 * @param bool   $default Fallback.
 * @return bool
 */
function msrseminars_get_option_bool( $field, $default = false ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}
	$value = get_field( $field, 'option' );
	if ( null === $value || '' === $value ) {
		return $default;
	}
	return (bool) $value;
}

/**
 * Ecosystem band heading.
 *
 * @return string
 */
function msrseminars_get_ecosystem_band_title() {
	return msrseminars_get_option_string(
		'ecosystem_band_title',
		__( 'MSR ecosystem', 'msrseminars' )
	);
}

/**
 * Ecosystem band lead copy.
 *
 * @return string
 */
function msrseminars_get_ecosystem_band_lead() {
	return msrseminars_get_option_string(
		'ecosystem_band_lead',
		__( 'MSR Seminars connects to the events hub, MSR Awards, and Atlas Briefing in the local demonstration estate.', 'msrseminars' )
	);
}

/**
 * Agenda page lead.
 *
 * @return string
 */
function msrseminars_get_agenda_page_lead() {
	return msrseminars_get_option_string(
		'agenda_page_lead',
		__( 'Session tracks and timings for delegates—browse by day, then expand a session for speakers and detail.', 'msrseminars' )
	);
}

/**
 * Panelists page lead.
 *
 * @return string
 */
function msrseminars_get_panelists_page_lead() {
	return msrseminars_get_option_string(
		'panelists_page_lead',
		__( 'Speakers and facilitators across MSR Seminars sessions—profiles, roles, and the topics they cover.', 'msrseminars' )
	);
}

/**
 * Partners page lead.
 *
 * @return string
 */
function msrseminars_get_partners_page_lead() {
	return msrseminars_get_option_string(
		'partners_page_lead',
		__( 'Supporters helping deliver MSR Seminars sessions and delegate resources.', 'msrseminars' )
	);
}

/**
 * For delegates page lead.
 *
 * @return string
 */
function msrseminars_get_delegates_page_lead() {
	return msrseminars_get_option_string(
		'delegates_page_lead',
		__( 'Delegate guidance for exploring MSR Seminars agenda, speakers, and lifecycle timelines before wiring live registration flows.', 'msrseminars' )
	);
}

/**
 * Delegate journey band lead.
 *
 * @return string
 */
function msrseminars_get_delegate_journey_lead() {
	return msrseminars_get_option_string(
		'delegate_journey_lead',
		__( 'Step-by-step delegate guidance for portfolio review — replace with live registration and LMS flows before a production seminar season.', 'msrseminars' )
	);
}

/**
 * Footer demo disclaimer line.
 *
 * @return string
 */
function msrseminars_get_footer_demo_note() {
	return msrseminars_get_option_string(
		'footer_demo_note',
		__( 'Demonstration seminars programme for portfolio review.', 'msrseminars' )
	);
}

/**
 * Whether the footer demo disclaimer is shown.
 *
 * @return bool
 */
function msrseminars_show_footer_demo_note() {
	return msrseminars_get_option_bool( 'show_footer_demo_note', true );
}

/**
 * Home meta description fallback.
 *
 * @return string
 */
function msrseminars_get_seo_home_description() {
	return msrseminars_get_option_string(
		'seo_home_description',
		__( 'MSR Seminars — delegate learning programme with agenda, panelists, and post-event resources in the MSR demonstration estate.', 'msrseminars' )
	);
}

/**
 * Agenda page meta description fallback.
 *
 * @return string
 */
function msrseminars_get_seo_agenda_description() {
	return msrseminars_get_option_string(
		'seo_agenda_description',
		__( 'Browse MSR Seminars agenda — session tracks, timings, speakers, and delegate resources for portfolio review.', 'msrseminars' )
	);
}

/**
 * Search meta description fallback.
 *
 * @return string
 */
function msrseminars_get_seo_search_description() {
	return msrseminars_get_option_string(
		'seo_search_description',
		__( 'Search MSR Seminars agenda, panelists, topics, and programme news.', 'msrseminars' )
	);
}

/**
 * Programme outbound URL from options (ACF) with legacy wp_option fallback.
 *
 * @param string $slug hub|awards|publishing.
 * @return string
 */
function msrseminars_get_programme_url_option( $slug ) {
	$acf_fields = array(
		'hub'        => 'msr_programme_hub_url',
		'awards'     => 'msr_programme_awards_url',
		'publishing' => 'msr_programme_publishing_url',
	);
	$legacy_keys = function_exists( 'msrseminars_get_ecosystem_option_keys' )
		? msrseminars_get_ecosystem_option_keys()
		: array();

	if ( isset( $acf_fields[ $slug ] ) ) {
		$url = msrseminars_get_option_string( $acf_fields[ $slug ], '' );
		if ( '' !== $url ) {
			return esc_url_raw( $url );
		}
	}

	if ( isset( $legacy_keys[ $slug ] ) ) {
		$stored = (string) get_option( $legacy_keys[ $slug ], '' );
		if ( '' !== trim( $stored ) ) {
			return esc_url_raw( $stored );
		}
	}

	return '';
}
