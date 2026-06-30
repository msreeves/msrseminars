<?php
/**
 * Seminars agenda — day metadata, session sort, legacy time fallback.
 *
 * @package msrseminars
 */

/**
 * Agenda posts ordered for the programme page (day / track containers).
 *
 * @return WP_Post[]
 */
function msrseminars_get_agenda_posts() {
	$posts = get_posts(
		array(
			'post_type'              => 'agenda',
			'posts_per_page'         => -1,
			'orderby'                => 'title',
			'order'                  => 'ASC',
			'update_post_meta_cache' => true,
			'update_post_term_cache' => false,
		)
	);

	if ( empty( $posts ) ) {
		return array();
	}

	usort(
		$posts,
		static function ( $a, $b ) {
			$order_a = get_post_meta( $a->ID, 'agenda_sort_order', true );
			$order_b = get_post_meta( $b->ID, 'agenda_sort_order', true );
			$has_a   = '' !== (string) $order_a;
			$has_b   = '' !== (string) $order_b;

			if ( $has_a && $has_b ) {
				$cmp = (int) $order_a <=> (int) $order_b;
				if ( 0 !== $cmp ) {
					return $cmp;
				}
			} elseif ( $has_a ) {
				return -1;
			} elseif ( $has_b ) {
				return 1;
			}

			$date_a = msrseminars_get_agenda_date_ymd( $a->ID );
			$date_b = msrseminars_get_agenda_date_ymd( $b->ID );
			if ( $date_a && $date_b && $date_a !== $date_b ) {
				return strcmp( $date_a, $date_b );
			}

			return strcasecmp( $a->post_title, $b->post_title );
		}
	);

	return $posts;
}

/**
 * Tab label: Day label + optional date + track title.
 *
 * @param WP_Post $post Agenda post.
 * @return string
 */
function msrseminars_get_agenda_tab_label( WP_Post $post ) {
	$label = trim( (string) get_field( 'agenda_label', $post->ID ) );
	$date  = msrseminars_get_agenda_date_ymd( $post->ID );
	$parts = array();

	if ( '' !== $label ) {
		$parts[] = $label;
	}
	if ( $date ) {
		$parts[] = wp_date( 'j M', strtotime( $date . ' 12:00:00' ) );
	}
	if ( empty( $parts ) ) {
		return $post->post_title;
	}

	$head = implode( ' · ', $parts );
	if ( $label && $post->post_title !== $label ) {
		return $head . ' — ' . $post->post_title;
	}

	return $head;
}

/**
 * Normalized Ymd for an agenda post.
 *
 * @param int $post_id Agenda post ID.
 * @return string Empty or Ymd.
 */
function msrseminars_get_agenda_date_ymd( $post_id ) {
	$raw = get_field( 'agenda_date', $post_id );
	if ( ! is_string( $raw ) || '' === trim( $raw ) ) {
		return '';
	}
	$raw = trim( $raw );
	if ( preg_match( '/^\d{8}$/', $raw ) ) {
		return $raw;
	}
	$ts = strtotime( $raw );
	return $ts ? gmdate( 'Ymd', $ts ) : '';
}

/**
 * Schedule rows for an agenda post, sorted by session start.
 *
 * @param int $post_id Agenda post ID.
 * @return array<int, array<string, mixed>>
 */
function msrseminars_get_sorted_schedule_rows( $post_id ) {
	$post_id = (int) $post_id;
	$rows    = get_field( 'schedule', $post_id );
	if ( ! is_array( $rows ) || ! $rows ) {
		return array();
	}

	$indexed = array_values( $rows );
	usort(
		$indexed,
		static function ( $a, $b ) use ( $post_id ) {
			$left  = msrseminars_get_session_start_timestamp( is_array( $a ) ? $a : array(), $post_id );
			$right = msrseminars_get_session_start_timestamp( is_array( $b ) ? $b : array(), $post_id );
			if ( $left === $right ) {
				return 0;
			}
			return ( $left < $right ) ? -1 : 1;
		}
	);

	return $indexed;
}

/**
 * Unix timestamp for session sort + datetime attributes.
 *
 * @param array<string, mixed> $row     Schedule row.
 * @param int                  $post_id Agenda post ID.
 * @return int
 */
function msrseminars_get_session_start_timestamp( array $row, $post_id ) {
	$start = msrseminars_get_session_datetime( $row, $post_id, 'start' );
	if ( $start ) {
		$ts = strtotime( $start );
		if ( $ts ) {
			return $ts;
		}
	}

	return PHP_INT_MAX;
}

/**
 * ISO-8601 local datetime string for session start/end.
 *
 * @param array<string, mixed> $row     Schedule row.
 * @param int                  $post_id Agenda post ID.
 * @param string               $which   start|end.
 * @return string
 */
function msrseminars_get_session_datetime( array $row, $post_id, $which = 'start' ) {
	$key = ( 'end' === $which ) ? 'session_end' : 'session_start';
	if ( ! empty( $row[ $key ] ) ) {
		$ts = strtotime( (string) $row[ $key ] );
		if ( $ts ) {
			return wp_date( 'c', $ts );
		}
	}

	$date_ymd = msrseminars_get_agenda_date_ymd( $post_id );
	if ( ! $date_ymd ) {
		return '';
	}

	$time_row = isset( $row['time'] ) && is_array( $row['time'] ) ? $row['time'] : array();
	$legacy   = ( 'end' === $which )
		? ( $time_row['finish'] ?? $time_row['end'] ?? '' )
		: ( $time_row['start'] ?? '' );

	$legacy = trim( (string) $legacy );
	if ( '' === $legacy ) {
		return '';
	}

	$ts = strtotime( $date_ymd . ' ' . $legacy );
	return $ts ? wp_date( 'c', $ts ) : '';
}

/**
 * Human-readable session time range.
 *
 * @param array<string, mixed> $row     Schedule row.
 * @param int                  $post_id Agenda post ID.
 * @return string
 */
function msrseminars_format_session_time_range( array $row, $post_id ) {
	$start_iso = msrseminars_get_session_datetime( $row, $post_id, 'start' );
	$end_iso   = msrseminars_get_session_datetime( $row, $post_id, 'end' );

	if ( $start_iso && $end_iso ) {
		$start_ts = strtotime( $start_iso );
		$end_ts   = strtotime( $end_iso );
		if ( $start_ts && $end_ts ) {
			return wp_date( 'g:i a', $start_ts ) . ' – ' . wp_date( 'g:i a', $end_ts );
		}
	}

	$time_row = isset( $row['time'] ) && is_array( $row['time'] ) ? $row['time'] : array();
	$start    = trim( (string) ( $time_row['start'] ?? '' ) );
	$finish   = trim( (string) ( $time_row['finish'] ?? $time_row['end'] ?? '' ) );
	if ( $start && $finish ) {
		return $start . ' – ' . $finish;
	}

	return $start ?: $finish;
}

/**
 * Session format label for display.
 *
 * @param mixed $format Stored select value.
 * @return string
 */
function msrseminars_get_session_format_label( $format ) {
	$format = sanitize_key( (string) $format );
	$labels = array(
		'keynote'    => __( 'Keynote', 'msrseminars' ),
		'panel'      => __( 'Panel', 'msrseminars' ),
		'workshop'   => __( 'Workshop', 'msrseminars' ),
		'break'      => __( 'Break', 'msrseminars' ),
		'networking' => __( 'Networking', 'msrseminars' ),
	);

	return $labels[ $format ] ?? '';
}

/**
 * Cross-cutting session topic choices (ACF + filters).
 *
 * @return array<string, string> slug => label.
 */
function msrseminars_get_session_topic_choices() {
	return array(
		'ai-innovation'  => __( 'AI & Innovation', 'msrseminars' ),
		'governance'     => __( 'Governance & Risk', 'msrseminars' ),
		'people-culture' => __( 'People & Culture', 'msrseminars' ),
		'sustainability' => __( 'Sustainability', 'msrseminars' ),
		'leadership'     => __( 'Leadership', 'msrseminars' ),
		'digital'        => __( 'Digital Transformation', 'msrseminars' ),
	);
}

/**
 * Session topic label for display.
 *
 * @param mixed $topic Stored select value.
 * @return string
 */
function msrseminars_get_session_topic_label( $topic ) {
	$topic  = sanitize_key( (string) $topic );
	$labels = msrseminars_get_session_topic_choices();

	return $labels[ $topic ] ?? '';
}

/**
 * Display-only start time for timeline rail.
 *
 * @param array<string, mixed> $row     Schedule row.
 * @param int                  $post_id Agenda post ID.
 * @return string
 */
function msrseminars_format_session_start_display( array $row, $post_id ) {
	$start_iso = msrseminars_get_session_datetime( $row, $post_id, 'start' );
	if ( $start_iso ) {
		$start_ts = strtotime( $start_iso );
		if ( $start_ts ) {
			return wp_date( 'g:i a', $start_ts );
		}
	}

	$time_row = isset( $row['time'] ) && is_array( $row['time'] ) ? $row['time'] : array();

	return trim( (string) ( $time_row['start'] ?? '' ) );
}

/**
 * Agenda tracks grouped by conference day for day-first navigation.
 *
 * @return array<int, array{key: string, label: string, date_ymd: string, tracks: WP_Post[]}>
 */
function msrseminars_get_agenda_day_groups() {
	$posts  = msrseminars_get_agenda_posts();
	$groups = array();

	foreach ( $posts as $post ) {
		$date_ymd = msrseminars_get_agenda_date_ymd( $post->ID );
		$key      = $date_ymd ?: 'undated-' . $post->ID;
		$label    = trim( (string) get_field( 'agenda_label', $post->ID ) );

		if ( ! isset( $groups[ $key ] ) ) {
			$groups[ $key ] = array(
				'key'      => $key,
				'label'    => $label,
				'date_ymd' => $date_ymd,
				'tracks'   => array(),
			);
		}

		if ( '' === $groups[ $key ]['label'] && '' !== $label ) {
			$groups[ $key ]['label'] = $label;
		}

		$groups[ $key ]['tracks'][] = $post;
	}

	return array_values( $groups );
}

/**
 * Day tab label from a day group.
 *
 * @param array{label?: string, date_ymd?: string} $group Day group.
 * @return string
 */
function msrseminars_get_agenda_day_tab_label( array $group ) {
	$parts = array();
	$label = trim( (string) ( $group['label'] ?? '' ) );
	$date  = trim( (string) ( $group['date_ymd'] ?? '' ) );

	if ( '' !== $label ) {
		$parts[] = $label;
	}
	if ( '' !== $date ) {
		$parts[] = wp_date( 'j M', strtotime( $date . ' 12:00:00' ) );
	}

	if ( empty( $parts ) ) {
		return __( 'Schedule', 'msrseminars' );
	}

	return implode( ' · ', $parts );
}

/**
 * Compact speaker chips for agenda session cards.
 *
 * @param mixed  $people Post objects or IDs.
 * @return void
 */
function msrseminars_render_agenda_speaker_chips( $people ) {
	if ( empty( $people ) || ! is_array( $people ) ) {
		return;
	}

	echo '<ul class="seminars-agenda-speakers list-unstyled d-flex flex-wrap gap-2 mb-0" role="list">';

	foreach ( $people as $person ) {
		$post_id = $person instanceof WP_Post ? (int) $person->ID : (int) $person;
		if ( $post_id <= 0 ) {
			continue;
		}

		$title    = get_the_title( $post_id );
		$permalink = get_permalink( $post_id );
		$thumb_id = msrseminars_sanitize_attachment_id( (int) get_post_thumbnail_id( $post_id ) );

		echo '<li class="seminars-agenda-speakers__item" role="listitem">';
		printf(
			'<a class="seminars-agenda-speakers__chip" href="%s">',
			esc_url( $permalink )
		);
		if ( $thumb_id ) {
			echo wp_get_attachment_image(
				$thumb_id,
				'thumbnail',
				false,
				array(
					'class'    => 'seminars-agenda-speakers__avatar',
					'alt'      => '',
					'loading'  => 'lazy',
					'decoding' => 'async',
				)
			);
		}
		printf(
			'<span class="seminars-agenda-speakers__name">%s</span></a></li>',
			esc_html( $title )
		);
	}

	echo '</ul>';
}

/**
 * Render a track timeline (sorted session cards).
 *
 * @param WP_Post $track      Agenda track post.
 * @param string  $context_id Unique DOM prefix for this render context (tab pane).
 * @return void
 */
function msrseminars_render_agenda_track_timeline( WP_Post $track, $context_id = 'single' ) {
	$rows = msrseminars_get_sorted_schedule_rows( $track->ID );
	if ( ! $rows ) {
		return;
	}

	global $post;
	$previous_post = $post;
	$post          = $track;
	setup_postdata( $track );

	echo '<div class="seminars-agenda-track">';
	printf(
		'<h3 class="h5 seminars-agenda-track__heading">%s</h3>',
		esc_html( $track->post_title )
	);
	echo '<div class="seminars-agenda-timeline" role="list">';

	foreach ( array_values( $rows ) as $index => $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}
		get_template_part(
			'template-parts/agenda/session',
			'card',
			array(
				'row'        => $row,
				'post_id'    => $track->ID,
				'index'      => $index,
				'context_id' => sanitize_key( (string) $context_id ),
			)
		);
	}

	echo '</div></div>';

	$post = $previous_post;
	wp_reset_postdata();
}

/**
 * Stable anchor id for a session row (deep links + calendar filenames).
 *
 * @param int $post_id Track post ID.
 * @param int $index   Row index.
 * @return string
 */
function msrseminars_get_session_anchor_id( $post_id, $index ) {
	return 'seminars-session-' . (int) $post_id . '-' . (int) $index;
}

/**
 * Track and format options present in published agenda data.
 *
 * @return array{tracks: array<string, string>, formats: array<string, string>}
 */
function msrseminars_get_agenda_filter_catalog() {
	$tracks  = array();
	$formats = array();
	$topics  = array();

	foreach ( msrseminars_get_agenda_day_groups() as $group ) {
		foreach ( $group['tracks'] as $track ) {
			if ( ! ( $track instanceof WP_Post ) ) {
				continue;
			}
			$slug = sanitize_title( $track->post_name );
			if ( '' !== $slug ) {
				$tracks[ $slug ] = $track->post_title;
			}
			foreach ( msrseminars_get_sorted_schedule_rows( $track->ID ) as $row ) {
				if ( ! is_array( $row ) ) {
					continue;
				}
				$format_key = sanitize_key( (string) ( $row['session_format'] ?? '' ) );
				if ( '' !== $format_key ) {
					$label = msrseminars_get_session_format_label( $format_key );
					if ( '' !== $label ) {
						$formats[ $format_key ] = $label;
					}
				}
				$topic_key = sanitize_key( (string) ( $row['session_topic'] ?? '' ) );
				if ( '' !== $topic_key ) {
					$topic_label = msrseminars_get_session_topic_label( $topic_key );
					if ( '' !== $topic_label ) {
						$topics[ $topic_key ] = $topic_label;
					}
				}
			}
		}
	}

	return array(
		'tracks'  => $tracks,
		'formats' => $formats,
		'topics'  => $topics,
	);
}

/**
 * Render track + format filter bars for the agenda page.
 *
 * @return void
 */
function msrseminars_render_agenda_session_filters() {
	$catalog = msrseminars_get_agenda_filter_catalog();
	$tracks  = $catalog['tracks'];
	$formats = $catalog['formats'];
	$topics  = $catalog['topics'];

	if ( count( $tracks ) < 2 && count( $formats ) < 2 && count( $topics ) < 2 ) {
		return;
	}

	echo '<div class="seminars-agenda-filters" data-seminars-agenda-filters>';

	if ( count( $tracks ) >= 2 ) {
		echo '<div class="seminars-agenda-filters__group">';
		msrseminars_filter_bar_open( __( 'Filter sessions by track', 'msrseminars' ) );
		msrseminars_agenda_filter_button( __( 'All tracks', 'msrseminars' ), 'track', 'all', true );
		foreach ( $tracks as $slug => $label ) {
			msrseminars_agenda_filter_button( $label, 'track', $slug, false );
		}
		msrseminars_filter_bar_close();
		echo '</div>';
	}

	if ( count( $topics ) >= 2 ) {
		echo '<div class="seminars-agenda-filters__group">';
		msrseminars_filter_bar_open( __( 'Filter sessions by topic', 'msrseminars' ) );
		msrseminars_agenda_filter_button( __( 'All topics', 'msrseminars' ), 'topic', 'all', true );
		foreach ( $topics as $slug => $label ) {
			msrseminars_agenda_filter_button( $label, 'topic', $slug, false );
		}
		msrseminars_filter_bar_close();
		echo '</div>';
	}

	if ( count( $formats ) >= 2 ) {
		echo '<div class="seminars-agenda-filters__group">';
		msrseminars_filter_bar_open( __( 'Filter sessions by format', 'msrseminars' ) );
		msrseminars_agenda_filter_button( __( 'All formats', 'msrseminars' ), 'format', 'all', true );
		foreach ( $formats as $slug => $label ) {
			msrseminars_agenda_filter_button( $label, 'format', $slug, false );
		}
		msrseminars_filter_bar_close();
		echo '</div>';
	}

	printf(
		'<p class="seminars-agenda-filters__status" role="status" aria-live="polite" data-seminars-agenda-filter-status>%s <strong class="seminars-agenda-filters__count" data-seminars-agenda-filter-count></strong></p>',
		esc_html__( 'Showing all published sessions.', 'msrseminars' )
	);
	echo '<p class="seminars-agenda-filters__empty" data-seminars-agenda-filter-empty hidden>' . esc_html__( 'No sessions match these filters. Clear a filter to see the full agenda.', 'msrseminars' ) . '</p>';
	echo '</div>';
}

/**
 * Agenda filter bar button (client-side session filter).
 *
 * @param string $label  Button label.
 * @param string $filter track|format.
 * @param string $value  Filter value or all.
 * @param bool   $active Selected state.
 * @return void
 */
function msrseminars_agenda_filter_button( $label, $filter, $value, $active = false ) {
	$classes = 'seminars-filter-bar__link seminars-agenda-filters__button';
	if ( $active ) {
		$classes .= ' is-active';
	}
	echo '<li class="seminars-filter-bar__item">';
	printf(
		'<button type="button" class="%s" data-seminars-agenda-filter="%s" data-filter-value="%s" aria-pressed="%s">%s</button>',
		esc_attr( $classes ),
		esc_attr( sanitize_key( $filter ) ),
		esc_attr( sanitize_title( (string) $value ) ),
		$active ? 'true' : 'false',
		esc_html( $label )
	);
	echo '</li>';
}

/**
 * Plain-text excerpt for calendar event bodies.
 *
 * @param mixed $about WYSIWYG / string field.
 * @return string
 */
function msrseminars_get_session_calendar_description( $about ) {
	if ( is_array( $about ) ) {
		$about = implode( ' ', array_map( 'wp_strip_all_tags', $about ) );
	}
	$text = wp_strip_all_tags( (string) $about );
	$text = preg_replace( '/\s+/', ' ', $text );
	return trim( (string) $text );
}

/**
 * Escape text for iCalendar properties.
 *
 * @param string $text Raw text.
 * @return string
 */
function msrseminars_ical_escape( $text ) {
	$text = (string) $text;
	$text = str_replace( array( '\\', ';', ',', "\r\n", "\n", "\r" ), array( '\\\\', '\\;', '\\,', '\\n', '\\n', '' ), $text );
	return $text;
}

/**
 * Session start/end timestamps for calendar export.
 *
 * @param array<string, mixed> $row     Schedule row.
 * @param int                  $post_id Agenda track post ID.
 * @return array{start: int, end: int}|null
 */
function msrseminars_get_session_calendar_times( array $row, $post_id ) {
	$start_iso = msrseminars_get_session_datetime( $row, $post_id, 'start' );
	if ( ! $start_iso ) {
		return null;
	}
	$start_ts = strtotime( $start_iso );
	if ( ! $start_ts ) {
		return null;
	}

	$end_iso = msrseminars_get_session_datetime( $row, $post_id, 'end' );
	$end_ts  = $end_iso ? strtotime( $end_iso ) : false;
	if ( ! $end_ts || $end_ts <= $start_ts ) {
		$end_ts = $start_ts + HOUR_IN_SECONDS;
	}

	return array(
		'start' => $start_ts,
		'end'   => $end_ts,
	);
}

/**
 * Google Calendar template URL for a session.
 *
 * @param array<string, mixed> $row         Schedule row.
 * @param int                  $post_id     Track post ID.
 * @param string               $track_title Track label.
 * @return string
 */
function msrseminars_get_session_google_calendar_url( array $row, $post_id, $track_title ) {
	$times = msrseminars_get_session_calendar_times( $row, $post_id );
	if ( ! $times ) {
		return '';
	}

	$title = trim( (string) ( $row['name'] ?? '' ) );
	if ( '' === $title ) {
		$title = $track_title;
	}

	$details = msrseminars_get_session_calendar_description( $row['about'] ?? '' );
	if ( '' !== $track_title ) {
		$details = trim( $track_title . ( $details ? ' — ' . $details : '' ) );
	}

	$dates = gmdate( 'Ymd\THis\Z', $times['start'] ) . '/' . gmdate( 'Ymd\THis\Z', $times['end'] );

	return add_query_arg(
		array(
			'action'   => 'TEMPLATE',
			'text'     => $title,
			'dates'    => $dates,
			'details'  => $details,
			'location' => get_bloginfo( 'name' ),
		),
		'https://calendar.google.com/calendar/render'
	);
}

/**
 * Download URL for a single-session ICS file.
 *
 * @param int $post_id Track post ID.
 * @param int $index   Row index.
 * @return string
 */
function msrseminars_get_session_ics_url( $post_id, $index ) {
	$agenda_url = msrseminars_get_agenda_page_url();
	if ( ! $agenda_url ) {
		$agenda_url = home_url( '/' );
	}

	return add_query_arg(
		array(
			'msr_agenda_ics' => (int) $post_id . '-' . (int) $index,
		),
		$agenda_url
	);
}

/**
 * Agenda landing page URL (for calendar links).
 *
 * @return string
 */
function msrseminars_get_agenda_page_url() {
	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'posts_per_page' => 1,
			'meta_key'       => '_wp_page_template',
			'meta_value'     => 'templates/template-agenda.php',
			'fields'         => 'ids',
		)
	);
	if ( $pages ) {
		$url = get_permalink( (int) $pages[0] );
		return $url ? $url : '';
	}

	$slug_page = get_page_by_path( 'agenda' );
	if ( $slug_page ) {
		$url = get_permalink( $slug_page );
		return $url ? $url : '';
	}

	return '';
}

/**
 * Build RFC5545 ICS payload for one session row.
 *
 * @param array<string, mixed> $row         Schedule row.
 * @param int                  $post_id     Track post ID.
 * @param int                  $index       Row index.
 * @param string               $track_title Track label.
 * @return string
 */
function msrseminars_build_session_ics_content( array $row, $post_id, $index, $track_title ) {
	$times = msrseminars_get_session_calendar_times( $row, $post_id );
	if ( ! $times ) {
		return '';
	}

	$title = trim( (string) ( $row['name'] ?? '' ) );
	if ( '' === $title ) {
		$title = $track_title;
	}

	$description = msrseminars_get_session_calendar_description( $row['about'] ?? '' );
	if ( '' !== $track_title ) {
		$description = trim( $track_title . ( $description ? ' — ' . $description : '' ) );
	}

	$uid      = sprintf( 'session-%d-%d@%s', (int) $post_id, (int) $index, wp_parse_url( home_url(), PHP_URL_HOST ) ?: 'msrseminars.local' );
	$stamp    = gmdate( 'Ymd\THis\Z' );
	$start    = gmdate( 'Ymd\THis\Z', $times['start'] );
	$end      = gmdate( 'Ymd\THis\Z', $times['end'] );
	$summary  = msrseminars_ical_escape( $title );
	$body     = msrseminars_ical_escape( $description );
	$location = msrseminars_ical_escape( get_bloginfo( 'name' ) );

	$lines = array(
		'BEGIN:VCALENDAR',
		'VERSION:2.0',
		'PRODID:-//MSR Seminars//Agenda//EN',
		'CALSCALE:GREGORIAN',
		'METHOD:PUBLISH',
		'BEGIN:VEVENT',
		'UID:' . $uid,
		'DTSTAMP:' . $stamp,
		'DTSTART:' . $start,
		'DTEND:' . $end,
		'SUMMARY:' . $summary,
	);

	if ( '' !== $body ) {
		$lines[] = 'DESCRIPTION:' . $body;
	}
	if ( '' !== $location ) {
		$lines[] = 'LOCATION:' . $location;
	}

	$lines[] = 'END:VEVENT';
	$lines[] = 'END:VCALENDAR';

	return implode( "\r\n", $lines ) . "\r\n";
}

/**
 * Serve a single-session ICS download when requested.
 *
 * @return void
 */
function msrseminars_maybe_serve_agenda_ics() {
	if ( ! isset( $_GET['msr_agenda_ics'] ) ) {
		return;
	}

	$raw = sanitize_text_field( wp_unslash( (string) $_GET['msr_agenda_ics'] ) );
	if ( ! preg_match( '/^(\d+)-(\d+)$/', $raw, $matches ) ) {
		status_header( 400 );
		exit;
	}

	$post_id = (int) $matches[1];
	$index   = (int) $matches[2];
	$track   = get_post( $post_id );

	if ( ! $track instanceof WP_Post || 'agenda' !== $track->post_type || 'publish' !== $track->post_status ) {
		status_header( 404 );
		exit;
	}

	$rows = msrseminars_get_sorted_schedule_rows( $post_id );
	if ( ! isset( $rows[ $index ] ) || ! is_array( $rows[ $index ] ) ) {
		status_header( 404 );
		exit;
	}

	$ics = msrseminars_build_session_ics_content( $rows[ $index ], $post_id, $index, $track->post_title );
	if ( '' === $ics ) {
		status_header( 404 );
		exit;
	}

	$filename = sanitize_file_name( msrseminars_get_session_anchor_id( $post_id, $index ) . '.ics' );

	nocache_headers();
	header( 'Content-Type: text/calendar; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- plain ICS payload.
	echo $ics;
	exit;
}
add_action( 'template_redirect', 'msrseminars_maybe_serve_agenda_ics' );

/**
 * Render add-to-calendar actions for a session card.
 *
 * @param array<string, mixed> $row         Schedule row.
 * @param int                  $post_id     Track post ID.
 * @param int                  $index       Row index.
 * @param string               $track_title Track label.
 * @return void
 */
function msrseminars_render_session_calendar_actions( array $row, $post_id, $index, $track_title ) {
	if ( ! msrseminars_get_session_calendar_times( $row, $post_id ) ) {
		return;
	}

	$google_url = msrseminars_get_session_google_calendar_url( $row, $post_id, $track_title );
	$ics_url    = msrseminars_get_session_ics_url( $post_id, $index );

	if ( ! $google_url && ! $ics_url ) {
		return;
	}

	echo '<div class="seminars-agenda-calendar" data-seminars-agenda-calendar>';
	echo '<p class="small text-uppercase text-muted mb-2">' . esc_html__( 'Add to calendar', 'msrseminars' ) . '</p>';
	echo '<ul class="seminars-agenda-calendar__list list-unstyled d-flex flex-wrap gap-2 mb-0" role="list">';

	if ( $google_url ) {
		echo '<li role="listitem">';
		printf(
			'<a class="seminars-agenda-calendar__link" href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
			esc_url( $google_url ),
			esc_html__( 'Google Calendar', 'msrseminars' )
		);
		echo '</li>';
	}

	if ( $ics_url ) {
		echo '<li role="listitem">';
		printf(
			'<a class="seminars-agenda-calendar__link" href="%s" download>%s</a>',
			esc_url( $ics_url ),
			esc_html__( 'Download .ics', 'msrseminars' )
		);
		echo '</li>';
	}

	echo '</ul></div>';
}
