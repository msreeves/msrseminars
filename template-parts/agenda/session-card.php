<?php
/**
 * Agenda session card (timeline row).
 *
 * @package msrseminars
 *
 * @var array<string, mixed> $args {
 *     @type array  $row     Schedule row.
 *     @type int    $post_id Agenda track post ID.
 *     @type int    $index   Row index within track.
 * }
 */

$row        = isset( $args['row'] ) && is_array( $args['row'] ) ? $args['row'] : array();
$post_id    = isset( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();
$index      = isset( $args['index'] ) ? (int) $args['index'] : 0;
$context_id = isset( $args['context_id'] ) ? sanitize_key( (string) $args['context_id'] ) : 'single';

if ( ! $row ) {
	return;
}

$session_title = trim( (string) ( $row['name'] ?? '' ) );
$time_label    = msrseminars_format_session_time_range( $row, $post_id );
$start_display = msrseminars_format_session_start_display( $row, $post_id );
$start_iso     = msrseminars_get_session_datetime( $row, $post_id, 'start' );
$end_iso       = msrseminars_get_session_datetime( $row, $post_id, 'end' );
$format_key    = sanitize_key( (string) ( $row['session_format'] ?? '' ) );
$format_label  = msrseminars_get_session_format_label( $format_key );
$topic_key     = sanitize_key( (string) ( $row['session_topic'] ?? '' ) );
$topic_label   = msrseminars_get_session_topic_label( $topic_key );
$track_title   = get_the_title( $post_id );
$track_slug    = sanitize_title( (string) get_post_field( 'post_name', $post_id ) );
$session_id    = msrseminars_get_session_anchor_id( $post_id, $index );
$collapse_id   = 'seminars-agenda-session-' . $context_id . '-' . $post_id . '-' . $index;
$is_break      = in_array( $format_key, array( 'break', 'networking' ), true );
$use_anchor_id = ( 'single' === $context_id || 0 !== strpos( $context_id, 'all-' ) );

$panelists  = $row['panelists'] ?? null;
$judicators = $row['judicators'] ?? null;
$speakers   = array();
if ( is_array( $panelists ) ) {
	$speakers = array_merge( $speakers, $panelists );
}
if ( is_array( $judicators ) ) {
	$speakers = array_merge( $speakers, $judicators );
}

$card_classes = 'seminars-agenda-session__card panel h-auto';
if ( $is_break ) {
	$card_classes .= ' seminars-agenda-session__card--break';
}
?>
<article
	class="seminars-agenda-session"
	<?php if ( $use_anchor_id ) : ?>
	id="<?php echo esc_attr( $session_id ); ?>"
	<?php endif; ?>
	role="listitem"
	data-session-anchor="<?php echo esc_attr( $session_id ); ?>"
	data-agenda-track="<?php echo esc_attr( $track_slug ); ?>"
	data-agenda-format="<?php echo esc_attr( $format_key ); ?>"
	data-agenda-topic="<?php echo esc_attr( $topic_key ); ?>"
>
	<div class="seminars-agenda-session__rail" aria-hidden="true">
		<?php if ( $start_display ) : ?>
			<?php if ( $start_iso ) : ?>
			<time class="seminars-agenda-session__rail-time" datetime="<?php echo esc_attr( $start_iso ); ?>">
				<?php echo esc_html( $start_display ); ?>
			</time>
			<?php else : ?>
			<span class="seminars-agenda-session__rail-time"><?php echo esc_html( $start_display ); ?></span>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<div class="seminars-agenda-session__body">
		<div class="<?php echo esc_attr( $card_classes ); ?>">
			<div class="seminars-agenda-session__header">
				<button
					class="seminars-agenda-session__toggle btn btn-link w-100 text-start d-lg-none"
					type="button"
					data-bs-toggle="collapse"
					data-bs-target="#<?php echo esc_attr( $collapse_id ); ?>"
					aria-expanded="false"
					aria-controls="<?php echo esc_attr( $collapse_id ); ?>"
				>
					<span class="seminars-agenda-session__toggle-time"><?php echo esc_html( $start_display ); ?></span>
					<span class="seminars-agenda-session__toggle-title"><?php echo esc_html( $session_title ); ?></span>
				</button>
				<div class="seminars-agenda-session__summary d-none d-lg-block">
					<?php if ( $time_label ) : ?>
					<p class="seminars-agenda-session__time mb-1">
						<?php if ( $start_iso ) : ?>
						<time datetime="<?php echo esc_attr( $start_iso ); ?>"><?php echo esc_html( $time_label ); ?></time>
						<?php else : ?>
						<?php echo esc_html( $time_label ); ?>
						<?php endif; ?>
					</p>
					<?php endif; ?>
					<?php if ( $session_title ) : ?>
					<h2 class="h4 seminars-agenda-session__title mb-0"><?php echo esc_html( $session_title ); ?></h2>
					<?php endif; ?>
				</div>
				<?php if ( $format_label || $topic_label ) : ?>
				<div class="seminars-agenda-session__badges d-flex flex-wrap gap-2 mb-0">
					<?php if ( $topic_label ) : ?>
					<p class="seminars-agenda-session__topic mb-0">
						<span class="badge rounded-pill seminars-session-topic seminars-session-topic--<?php echo esc_attr( $topic_key ); ?>">
							<?php echo esc_html( $topic_label ); ?>
						</span>
					</p>
					<?php endif; ?>
					<?php if ( $format_label ) : ?>
					<p class="seminars-agenda-session__format mb-0">
						<span class="badge rounded-pill seminars-session-format seminars-session-format--<?php echo esc_attr( $format_key ); ?>">
							<?php echo esc_html( $format_label ); ?>
						</span>
					</p>
					<?php endif; ?>
				</div>
				<?php endif; ?>
			</div>
			<div id="<?php echo esc_attr( $collapse_id ); ?>" class="collapse seminars-agenda-session__details">
				<div class="seminars-agenda-session__details-inner">
					<?php if ( ! empty( $row['about'] ) && ! $is_break ) : ?>
					<div class="msr-rich-text seminars-agenda-session__about">
						<?php msrseminars_render_rich_text( $row['about'] ); ?>
					</div>
					<?php endif; ?>
					<?php if ( $speakers ) : ?>
					<div class="seminars-agenda-session__people">
						<p class="small text-uppercase text-muted mb-2"><?php esc_html_e( 'Speakers', 'msrseminars' ); ?></p>
						<?php msrseminars_render_agenda_speaker_chips( $speakers ); ?>
					</div>
					<?php endif; ?>
					<?php
					$featured_posts = $row['sponsor'] ?? null;
					if ( $featured_posts ) :
						if ( ! is_array( $featured_posts ) ) {
							$featured_posts = array( $featured_posts );
						}
						?>
					<div class="seminars-agenda-session__sponsors">
						<p class="small text-uppercase text-muted mb-2"><?php esc_html_e( 'Session sponsor', 'msrseminars' ); ?></p>
						<div class="seminars-agenda-session__sponsor-logos d-flex flex-wrap gap-3 align-items-center">
							<?php
							foreach ( $featured_posts as $featured_post ) :
								$sponsor_id = $featured_post instanceof WP_Post ? (int) $featured_post->ID : (int) $featured_post;
								if ( $sponsor_id <= 0 ) {
									continue;
								}
								$sponsor_name = get_the_title( $sponsor_id );
								$sponsor_link = get_field( 'link', $sponsor_id );
								$sponsor_url  = '';
								if ( is_array( $sponsor_link ) && ! empty( $sponsor_link['url'] ) ) {
									$sponsor_url = (string) $sponsor_link['url'];
								} elseif ( is_string( $sponsor_link ) ) {
									$sponsor_url = $sponsor_link;
								}
								if ( '' === $sponsor_url ) {
									continue;
								}
								$link_label = ( is_array( $sponsor_link ) && ! empty( $sponsor_link['title'] ) )
									? (string) $sponsor_link['title']
									: $sponsor_name;
								$thumb_id   = (int) get_post_thumbnail_id( $sponsor_id );
								?>
							<a class="seminars-logo-tile seminars-agenda-session__sponsor-link" href="<?php echo esc_url( $sponsor_url ); ?>" aria-label="<?php echo esc_attr( $link_label ); ?>">
								<?php if ( $thumb_id ) : ?>
									<?php
									echo wp_get_attachment_image(
										$thumb_id,
										'medium',
										false,
										array(
											'class'    => 'seminars-agenda-session__sponsor-logo',
											'alt'      => '',
											'loading'  => 'lazy',
											'decoding' => 'async',
										)
									);
									?>
								<?php else : ?>
									<span><?php echo esc_html( $link_label ); ?></span>
								<?php endif; ?>
							</a>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>
					<?php msrseminars_render_session_calendar_actions( $row, $post_id, $index, $track_title ); ?>
					<?php if ( $end_iso ) : ?>
					<meta itemprop="endDate" content="<?php echo esc_attr( $end_iso ); ?>">
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</article>
