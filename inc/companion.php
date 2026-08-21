<?php
/**
 * Event Companion demo bridge — link-out URLs + lifecycle promo surfaces.
 *
 * Hub event ACF is source of truth after A3-hub; until then seminars options
 * `msr_seminars_companion_demo_url` / `msr_seminars_booking_url` (seeded locally).
 *
 * @package msrseminars
 */

/**
 * Local portfolio demo URL (A0 default).
 *
 * @return string
 */
function msrseminars_get_companion_demo_url_default() {
	return 'http://127.0.0.1:8888/sites/portfolio/projects/event-companion/?event=msrseminars';
}

/**
 * @return string Absolute companion demo URL or empty.
 */
function msrseminars_get_companion_demo_url() {
	$stored = trim( (string) get_option( 'msr_seminars_companion_demo_url', '' ) );
	if ( '' !== $stored ) {
		return esc_url_raw( $stored );
	}
	return msrseminars_get_companion_demo_url_default();
}

/**
 * @return string Booking / register URL (may be mailto).
 */
function msrseminars_get_companion_booking_url() {
	$stored = trim( (string) get_option( 'msr_seminars_booking_url', '' ) );
	if ( '' !== $stored ) {
		return $stored;
	}
	return 'mailto:hello@example.com?subject=MSR%20Seminars%20registration%20(demo)';
}

/**
 * Home band — registration phase only (playbook A3-seminars).
 *
 * @return bool
 */
function msrseminars_should_show_companion_home_band() {
	return 'registration' === msrseminars_get_delegate_phase()
		&& '' !== msrseminars_get_companion_demo_url();
}

/**
 * Agenda text link — registration + pre_event + live (hide on replay).
 *
 * @return bool
 */
function msrseminars_should_show_companion_agenda_link() {
	$phase = msrseminars_get_delegate_phase();
	return in_array( $phase, array( 'registration', 'pre_event', 'live' ), true )
		&& '' !== msrseminars_get_companion_demo_url();
}

/**
 * For-delegates one-liner — registration only.
 *
 * @return bool
 */
function msrseminars_should_show_companion_delegates_line() {
	return msrseminars_should_show_companion_home_band();
}

/**
 * Compact home promo band.
 */
function msrseminars_render_companion_home_band() {
	if ( ! msrseminars_should_show_companion_home_band() ) {
		return;
	}

	$url = msrseminars_get_companion_demo_url();
	?>
	<section class="seminars-companion-band msr-reveal" aria-labelledby="seminars-companion-band-heading">
		<div class="container">
			<div class="seminars-companion-band__inner">
				<div class="seminars-companion-band__copy">
					<p class="seminars-companion-band__eyebrow mb-1">
						<?php esc_html_e( 'Companion demo', 'msrseminars' ); ?>
					</p>
					<h2 id="seminars-companion-band-heading" class="h5 seminars-companion-band__title mb-2">
						<?php esc_html_e( 'Plan your day — companion app demo', 'msrseminars' ); ?>
					</h2>
					<p class="seminars-companion-band__lead mb-0">
						<?php esc_html_e( 'Website plus companion schedule demo: today, on now, and saved sessions. Portfolio demonstration — not an App Store product.', 'msrseminars' ); ?>
					</p>
				</div>
				<div class="seminars-companion-band__actions seminars-ctas">
					<a class="btn btn-primary" href="<?php echo esc_url( $url ); ?>">
						<?php esc_html_e( 'Open companion demo', 'msrseminars' ); ?>
					</a>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Optional agenda filter-bar companion link.
 */
function msrseminars_render_companion_agenda_link() {
	if ( ! msrseminars_should_show_companion_agenda_link() ) {
		return;
	}

	$url = msrseminars_get_companion_demo_url();
	?>
	<p class="seminars-companion-agenda-link">
		<a href="<?php echo esc_url( $url ); ?>">
			<?php esc_html_e( 'Full schedule in companion demo', 'msrseminars' ); ?>
		</a>
	</p>
	<?php
}

/**
 * Optional for-delegates journey line.
 */
function msrseminars_render_companion_delegates_line() {
	if ( ! msrseminars_should_show_companion_delegates_line() ) {
		return;
	}

	$url = msrseminars_get_companion_demo_url();
	?>
	<p class="seminars-companion-delegates-line">
		<a href="<?php echo esc_url( $url ); ?>">
			<?php esc_html_e( 'Companion demo', 'msrseminars' ); ?>
		</a>
		<?php esc_html_e( ' — day-of schedule alongside this website.', 'msrseminars' ); ?>
	</p>
	<?php
}
