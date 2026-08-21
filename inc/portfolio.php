<?php
/**
 * Portfolio demonstration surfaces — stats, featured sessions, CTA preview copy.
 *
 * @package msrseminars
 */

/**
 * Count published sessions across all agenda tracks (excludes breaks/networking).
 *
 * @return int
 */
function msrseminars_count_programme_sessions() {
	$count = 0;
	foreach ( msrseminars_get_agenda_posts() as $track ) {
		if ( ! ( $track instanceof WP_Post ) ) {
			continue;
		}
		foreach ( msrseminars_get_sorted_schedule_rows( $track->ID ) as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$format = sanitize_key( (string) ( $row['session_format'] ?? '' ) );
			if ( in_array( $format, array( 'break', 'networking' ), true ) ) {
				continue;
			}
			if ( '' !== trim( (string) ( $row['name'] ?? '' ) ) ) {
				++$count;
			}
		}
	}
	return $count;
}

/**
 * Programme day count from agenda day groups.
 *
 * @return int
 */
function msrseminars_count_programme_days() {
	$groups = msrseminars_get_agenda_day_groups();
	return $groups ? count( $groups ) : 0;
}

/**
 * Social proof stats for the programme home.
 *
 * @return array<int, array{value: string, label: string}>
 */
function msrseminars_get_programme_stats() {
	$tracks   = count( msrseminars_get_agenda_posts() );
	$sessions = msrseminars_count_programme_sessions();
	$days     = msrseminars_count_programme_days();

	return array(
		array(
			'value' => $tracks > 0 ? (string) $tracks : '5',
			'label' => __( 'Tracks', 'msrseminars' ),
		),
		array(
			'value' => $sessions > 0 ? (string) $sessions : '27',
			'label' => __( 'Sessions', 'msrseminars' ),
		),
		array(
			'value' => $days > 0 ? (string) $days : '2',
			'label' => __( 'Programme days', 'msrseminars' ),
		),
		array(
			'value' => '500+',
			'label' => __( 'Delegates (capacity)', 'msrseminars' ),
		),
	);
}

/**
 * Featured agenda sessions for home (keynote, workshop, panel preferred).
 *
 * @param int $limit Max cards.
 * @return array<int, array{title: string, track: string, time: string, url: string, format: string}>
 */
function msrseminars_get_featured_sessions( $limit = 3 ) {
	$pool = array();
	foreach ( msrseminars_get_agenda_posts() as $track ) {
		if ( ! ( $track instanceof WP_Post ) ) {
			continue;
		}
		foreach ( array_values( msrseminars_get_sorted_schedule_rows( $track->ID ) ) as $index => $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$title = trim( (string) ( $row['name'] ?? '' ) );
			if ( '' === $title ) {
				continue;
			}
			$format = sanitize_key( (string) ( $row['session_format'] ?? '' ) );
			if ( in_array( $format, array( 'break', 'networking' ), true ) ) {
				continue;
			}
			$pool[] = array(
				'title'   => $title,
				'track'   => $track->post_title,
				'time'    => msrseminars_format_session_time_range( $row, $track->ID ),
				'url'     => msrseminars_get_session_permalink( $track->ID, $index ),
				'format'  => $format,
				'summary' => trim( (string) ( $row['about'] ?? '' ) ),
				'weight'  => msrseminars_get_featured_session_weight( $format, $title ),
			);
		}
	}

	if ( ! $pool ) {
		return array();
	}

	usort(
		$pool,
		static function ( $a, $b ) {
			return ( $b['weight'] ?? 0 ) <=> ( $a['weight'] ?? 0 );
		}
	);

	$selected = array();
	$seen     = array();
	foreach ( $pool as $item ) {
		$key = sanitize_title( $item['title'] );
		if ( isset( $seen[ $key ] ) ) {
			continue;
		}
		$seen[ $key ] = true;
		unset( $item['weight'] );
		if ( ! empty( $item['summary'] ) ) {
			$item['summary'] = wp_trim_words( wp_strip_all_tags( (string) $item['summary'] ), 28, '…' );
		}
		$selected[] = $item;
		if ( count( $selected ) >= $limit ) {
			break;
		}
	}

	return $selected;
}

/**
 * Sort weight for featured session picks.
 *
 * @param string $format Session format slug.
 * @param string $title  Session title.
 * @return int
 */
function msrseminars_get_featured_session_weight( $format, $title ) {
	$weight = 0;
	if ( 'keynote' === $format ) {
		$weight += 30;
	}
	if ( 'workshop' === $format ) {
		$weight += 20;
	}
	if ( 'panel' === $format ) {
		$weight += 15;
	}
	if ( false !== stripos( $title, 'keynote' ) ) {
		$weight += 10;
	}
	if ( false !== stripos( $title, 'workshop' ) ) {
		$weight += 8;
	}
	if ( false !== stripos( $title, 'panel' ) ) {
		$weight += 6;
	}
	return $weight;
}

/**
 * Deep link to a session anchor on track single or agenda archive.
 *
 * @param int $track_id Agenda post ID.
 * @param int $index    Row index.
 * @return string
 */
function msrseminars_get_session_permalink( $track_id, $index ) {
	$track_url = get_permalink( (int) $track_id );
	if ( ! $track_url ) {
		return msrseminars_get_page_url( 'agenda', '/agenda/' );
	}
	$anchor = msrseminars_get_session_anchor_id( (int) $track_id, (int) $index );
	return $track_url . '#' . $anchor;
}

/**
 * Primary header CTA with portfolio preview note (seminars).
 *
 * @return void
 */
function msrseminars_render_header_cta() {
	if ( ! function_exists( 'msr_get_primary_cta' ) ) {
		return;
	}

	$cta = msr_get_primary_cta();
	if ( empty( $cta['label'] ) || empty( $cta['url'] ) ) {
		return;
	}

	$agenda_url    = msrseminars_get_page_url( 'agenda', '/agenda/' );
	$register_url  = msrseminars_get_page_url( 'for-delegates', '/for-delegates/' );
	$main_url      = $agenda_url ? $agenda_url : (string) $cta['url'];
	$secondary_url = $register_url ? $register_url : $main_url;
	?>
	<div class="msr-primary-cta msr-primary-cta--seminars">
		<div class="msr-primary-cta__actions">
			<a class="btn btn-primary msr-primary-cta__main" href="<?php echo esc_url( $main_url ); ?>"><?php echo esc_html( (string) $cta['label'] ); ?></a>
			<?php if ( ! empty( $cta['sub'] ) ) : ?>
			<a class="btn btn-outline-primary msr-primary-cta__sub" href="<?php echo esc_url( $secondary_url ); ?>"><?php echo esc_html( (string) $cta['sub'] ); ?></a>
			<?php endif; ?>
		</div>
		<p class="msr-primary-cta__preview small mb-0"><?php esc_html_e( 'Preview — registration opens at launch', 'msrseminars' ); ?></p>
	</div>
	<?php
}

/**
 * Programme stats strip (social proof).
 *
 * @return void
 */
function msrseminars_render_programme_stats() {
	$stats = msrseminars_get_programme_stats();
	if ( ! $stats ) {
		return;
	}
	?>
	<section class="seminars-programme-stats msr-reveal" aria-labelledby="seminars-programme-stats-heading">
		<div class="container">
			<h2 id="seminars-programme-stats-heading" class="visually-hidden"><?php esc_html_e( 'Programme at a glance', 'msrseminars' ); ?></h2>
			<ul class="seminars-programme-stats__list list-unstyled mb-0">
				<?php foreach ( $stats as $stat ) : ?>
				<li class="seminars-programme-stats__item">
					<p class="seminars-programme-stats__value mb-0"><?php echo esc_html( $stat['value'] ); ?></p>
					<p class="seminars-programme-stats__label mb-0"><?php echo esc_html( $stat['label'] ); ?></p>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php
}

/**
 * Featured sessions band for programme home.
 *
 * @return void
 */
function msrseminars_render_featured_sessions() {
	$sessions = msrseminars_get_featured_sessions( 3 );
	if ( ! $sessions ) {
		return;
	}
	$agenda_url = msrseminars_get_page_url( 'agenda', '/agenda/' );
	?>
	<section class="seminars-featured-sessions msr-reveal" aria-labelledby="seminars-featured-sessions-heading">
		<div class="container">
			<header class="seminars-featured-sessions__header text-center">
				<h2 id="seminars-featured-sessions-heading" class="h4 seminars-featured-sessions__title mb-2">
					<?php esc_html_e( 'Featured sessions', 'msrseminars' ); ?>
				</h2>
				<p class="seminars-featured-sessions__lead mb-0">
					<?php esc_html_e( 'A sample of headline sessions from the seeded agenda — swap for live programme picks before launch.', 'msrseminars' ); ?>
				</p>
				<?php if ( $agenda_url ) : ?>
				<div class="seminars-featured-sessions__cta seminars-ctas">
					<a class="btn btn-outline-primary seminars-featured-sessions__agenda-btn" href="<?php echo esc_url( $agenda_url ); ?>"><?php esc_html_e( 'View full agenda', 'msrseminars' ); ?></a>
				</div>
				<?php endif; ?>
			</header>
			<ul class="seminars-featured-sessions__grid list-unstyled mb-0">
				<?php foreach ( $sessions as $session ) : ?>
				<li class="seminars-featured-sessions__item panel">
					<article class="seminars-featured-sessions__card">
						<a class="seminars-featured-sessions__card-link" href="<?php echo esc_url( $session['url'] ); ?>">
							<h3 class="h6 seminars-featured-sessions__session-title mb-0"><?php echo esc_html( $session['title'] ); ?></h3>
						</a>
						<ul class="seminars-featured-sessions__chips list-unstyled mb-0" role="list">
							<?php if ( ! empty( $session['format'] ) ) : ?>
							<li>
								<span class="seminars-featured-sessions__chip">
									<?php echo esc_html( msrseminars_get_session_format_label( $session['format'] ) ); ?>
								</span>
							</li>
							<?php endif; ?>
							<?php if ( ! empty( $session['track'] ) ) : ?>
							<li>
								<span class="seminars-featured-sessions__chip"><?php echo esc_html( $session['track'] ); ?></span>
							</li>
							<?php endif; ?>
							<?php if ( ! empty( $session['time'] ) ) : ?>
							<li>
								<span class="seminars-featured-sessions__chip">
									<time><?php echo esc_html( $session['time'] ); ?></time>
								</span>
							</li>
							<?php endif; ?>
						</ul>
						<?php if ( ! empty( $session['summary'] ) ) : ?>
						<p class="seminars-featured-sessions__summary mb-0"><?php echo esc_html( $session['summary'] ); ?></p>
						<?php endif; ?>
					</article>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php
}

/**
 * Delivery format label for hero (portfolio default).
 *
 * @return string
 */
function msrseminars_get_programme_format_label() {
	$label = (string) apply_filters( 'msrseminars_programme_format_label', __( 'Hybrid · in-person & livestream', 'msrseminars' ) );
	return trim( $label );
}

add_filter(
	'msr_primary_cta',
	static function ( $cta, $ctx ) {
		if ( 'seminars' !== $ctx || ! is_array( $cta ) ) {
			return $cta;
		}
		$agenda_url = msrseminars_get_page_url( 'agenda', '/agenda/' );
		if ( $agenda_url ) {
			$cta['url'] = $agenda_url;
		}
		$register_url = msrseminars_get_page_url( 'for-delegates', '/for-delegates/' );
		if ( $register_url ) {
			$cta['sub_url'] = $register_url;
		}
		return $cta;
	},
	10,
	2
);
