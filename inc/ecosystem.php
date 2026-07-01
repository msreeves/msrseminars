<?php
/**
 * Seminars ecosystem outbound links — Events hub, MSR Awards, Atlas Briefing.
 *
 * @package msrseminars
 */

function msrseminars_get_ecosystem_link_defaults() {
	return array(
		'hub'        => array(
			'label'       => __( 'MSR Events hub', 'msrseminars' ),
			'url'         => 'http://msrevents.local:8888/',
			'description' => __( 'Programme routing, ceremony pages, and cross-programme stories on the MSR demonstration events hub.', 'msrseminars' ),
			'cta'         => __( 'Visit the events hub', 'msrseminars' ),
		),
		'awards'     => array(
			'label'       => __( 'MSR Awards', 'msrseminars' ),
			'url'         => 'http://msrevents.local:8888/msrawards/',
			'description' => __( 'Recognition programme connected to seminar keynote and panel coverage in the demo estate.', 'msrseminars' ),
			'cta'         => __( 'Visit MSR Awards', 'msrseminars' ),
		),
		'publishing' => array(
			'label'       => __( 'Atlas Briefing insights', 'msrseminars' ),
			'url'         => 'http://127.0.0.1:8888/sites/wp/msrpublishing/insights/',
			'description' => __( 'Post-event resources, commentary, and replay-linked articles from Atlas Briefing.', 'msrseminars' ),
			'cta'         => __( 'Read Atlas Briefing', 'msrseminars' ),
		),
	);
}

function msrseminars_get_ecosystem_option_keys() {
	return array(
		'hub'        => 'msr_seminars_ecosystem_hub_url',
		'awards'     => 'msr_seminars_ecosystem_awards_url',
		'publishing' => 'msr_seminars_ecosystem_publishing_url',
	);
}

function msrseminars_get_ecosystem_links() {
	$defaults = msrseminars_get_ecosystem_link_defaults();
	$links    = array();

	foreach ( $defaults as $slug => $item ) {
		$url = function_exists( 'msrseminars_get_programme_url_option' )
			? msrseminars_get_programme_url_option( $slug )
			: '';
		if ( '' === $url ) {
			$url = $item['url'];
		}
		if ( '' === $url ) {
			continue;
		}
		$links[] = array_merge( array( 'key' => $slug ), $item, array( 'url' => $url ) );
	}

	return $links;
}

function msrseminars_render_ecosystem_band() {
	$links = msrseminars_get_ecosystem_links();
	if ( ! $links ) {
		return;
	}
	?>
	<section class="msr-ecosystem msr-reveal" aria-labelledby="msr-ecosystem-heading">
		<div class="container">
			<header class="msr-ecosystem__header text-center mb-4">
				<h2 id="msr-ecosystem-heading" class="h4 msr-ecosystem__title mb-2">
					<?php echo esc_html( msrseminars_get_ecosystem_band_title() ); ?>
				</h2>
				<p class="msr-ecosystem__lead mb-0">
					<?php echo esc_html( msrseminars_get_ecosystem_band_lead() ); ?>
				</p>
			</header>
			<div class="row g-3 justify-content-center">
				<?php foreach ( $links as $link ) : ?>
					<div class="col-md-4">
						<div class="msr-ecosystem__card h-100">
							<h3 class="h6 msr-ecosystem__card-title mb-2"><?php echo esc_html( $link['label'] ); ?></h3>
							<p class="small msr-ecosystem__card-copy mb-3"><?php echo esc_html( $link['description'] ); ?></p>
							<a class="btn btn-outline-primary" href="<?php echo esc_url( $link['url'] ); ?>">
								<?php echo esc_html( $link['cta'] ); ?>
							</a>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}
