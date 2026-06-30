<?php
/**
 * Programme home hero — image, scrim, facts, CTAs.
 *
 * @package msrseminars
 */

$hero_on = (bool) get_field( 'hero', 'option' );
if ( ! $hero_on ) {
	return;
}

$hero_bg = msrseminars_hero_background_url( get_field( 'image', 'option' ) );
$venue   = get_field( 'venue', 'option' );
$date    = get_field( 'date', 'option' );
$time    = get_field( 'time', 'option' );
if ( ! is_array( $venue ) ) {
	$venue = array();
}
if ( ! is_array( $date ) ) {
	$date = array();
}
if ( ! is_array( $time ) ) {
	$time = array();
}

$venue_name  = isset( $venue['name'] ) ? (string) $venue['name'] : '';
$venue_addr  = isset( $venue['address'] ) ? (string) $venue['address'] : '';
$date_start  = isset( $date['start'] ) ? (string) $date['start'] : '';
$date_finish = isset( $date['finish'] ) ? (string) $date['finish'] : '';
$time_start  = isset( $time['start'] ) ? (string) $time['start'] : '';
$time_finish = isset( $time['finish'] ) ? (string) $time['finish'] : '';
$has_facts   = $venue_addr || $date_start || $date_finish || $time_start || $time_finish;
?>
<section class="msr-seminars-hero<?php echo $hero_bg ? '' : ' msr-seminars-hero--no-image'; ?>"<?php echo $hero_bg ? ' style="background-image: url(' . esc_url( $hero_bg ) . ');"' : ''; ?>>
	<div class="msr-seminars-hero__scrim" aria-hidden="true"></div>
	<div class="msr-seminars-hero__inner">
		<div class="msr-seminars-hero__content msr-reveal">
			<p class="msr-seminars-hero__eyebrow"><?php esc_html_e( 'Seminars programme', 'msrseminars' ); ?></p>
			<?php if ( function_exists( 'msrseminars_get_programme_format_label' ) ) : ?>
			<p class="msr-seminars-hero__format-badge"><?php echo esc_html( msrseminars_get_programme_format_label() ); ?></p>
			<?php endif; ?>
			<h1><?php echo esc_html( (string) get_field( 'name', 'option' ) ); ?></h1>
			<?php if ( $venue_name ) : ?>
			<p class="msr-seminars-hero__lead"><?php echo esc_html( $venue_name ); ?></p>
			<?php endif; ?>
			<?php if ( $has_facts ) : ?>
			<ul class="msr-seminars-hero__facts">
				<?php if ( $date_start || $date_finish ) : ?>
				<li class="msr-seminars-hero__fact">
					<?php if ( $date_start ) : ?><i class="fa-solid fa-calendar" aria-hidden="true"></i><?php endif; ?>
					<span><?php echo esc_html( trim( $date_start . ( $date_finish ? ' - ' . $date_finish : '' ) ) ); ?></span>
				</li>
				<?php endif; ?>
				<?php if ( $time_start || $time_finish ) : ?>
				<li class="msr-seminars-hero__fact">
					<?php if ( $time_start ) : ?><i class="fa-solid fa-clock" aria-hidden="true"></i><?php endif; ?>
					<span><?php echo esc_html( trim( $time_start . ( $time_finish ? ' - ' . $time_finish : '' ) ) ); ?></span>
				</li>
				<?php endif; ?>
				<?php if ( $venue_addr ) : ?>
				<li class="msr-seminars-hero__fact msr-seminars-hero__fact--address">
					<i class="fa-solid fa-location-dot" aria-hidden="true"></i>
					<span class="msr-seminars-hero__fact-text"><?php msrseminars_render_rich_text( $venue_addr ); ?></span>
				</li>
				<?php endif; ?>
			</ul>
			<?php endif; ?>
			<div class="seminars-ctas ctas">
				<?php msrseminars_render_cta_link( get_field( 'link1', 'option' ) ); ?>
				<?php msrseminars_render_cta_link( get_field( 'link2', 'option' ), 'btn btn-outline-primary' ); ?>
				<p class="msr-seminars-hero__cta-note"><?php esc_html_e( 'Preview — registration opens at launch', 'msrseminars' ); ?></p>
			</div>
		</div>
	</div>
</section>
