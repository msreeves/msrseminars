<?php
/**
 * Seminars programme surfaces — delegate journey timeline.
 *
 * @package msrseminars
 */

function msrseminars_get_delegate_phases() {
	return array(
		'registration' => array(
			'label'       => __( 'Registration open', 'msrseminars' ),
			'description' => __( 'Delegates can register for the seminar programme while agenda and speaker details are published.', 'msrseminars' ),
		),
		'pre_event'    => array(
			'label'       => __( 'Pre-event briefing', 'msrseminars' ),
			'description' => __( 'Briefing materials and agenda preparation content help delegates plan their session track selections.', 'msrseminars' ),
		),
		'live'         => array(
			'label'       => __( 'Live sessions', 'msrseminars' ),
			'description' => __( 'Agenda tracks and panel sessions are in progress across the live seminar programme.', 'msrseminars' ),
		),
		'replay'       => array(
			'label'       => __( 'Post-event replay', 'msrseminars' ),
			'description' => __( 'Replay highlights and Atlas Briefing resources extend learning after the live programme.', 'msrseminars' ),
		),
	);
}

function msrseminars_get_delegate_phase() {
	$phases  = msrseminars_get_delegate_phases();
	$default = 'live';
	$stored  = sanitize_key( (string) get_option( 'msr_seminars_delegate_phase', $default ) );

	return isset( $phases[ $stored ] ) ? $stored : $default;
}

function msrseminars_render_delegate_timeline() {
	$phases = msrseminars_get_delegate_phases();
	$active = msrseminars_get_delegate_phase();
	if ( ! isset( $phases[ $active ] ) ) {
		return;
	}

	$active_label = $phases[ $active ]['label'];
	?>
	<section class="seminars-delegate-timeline msr-reveal" aria-labelledby="seminars-delegate-heading">
		<div class="container">
			<header class="seminars-delegate-timeline__header text-center mb-4">
				<h2 id="seminars-delegate-heading" class="h4 seminars-delegate-timeline__title mb-2">
					<?php esc_html_e( 'Programme timeline', 'msrseminars' ); ?>
				</h2>
				<p class="seminars-delegate-timeline__status mb-0">
					<?php
					printf(
						esc_html__( 'Current phase: %s', 'msrseminars' ),
						esc_html( $active_label )
					);
					?>
				</p>
			</header>
			<ol class="seminars-delegate-timeline__list list-unstyled mb-0">
				<?php foreach ( $phases as $slug => $phase ) : ?>
					<?php
					$is_active = ( $slug === $active );
					$item_cls  = 'seminars-delegate-timeline__item' . ( $is_active ? ' is-active' : '' );
					?>
					<li class="<?php echo esc_attr( $item_cls ); ?>">
						<div class="seminars-delegate-timeline__marker" aria-hidden="true"></div>
						<div class="seminars-delegate-timeline__body">
							<h3 class="h6 seminars-delegate-timeline__label mb-1"><?php echo esc_html( $phase['label'] ); ?></h3>
							<p class="small seminars-delegate-timeline__copy mb-0"><?php echo esc_html( $phase['description'] ); ?></p>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</section>
	<?php
}
