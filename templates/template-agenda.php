<?php
/**
 * Template Name: Agenda Template
 *
 * @package msrseminars
 */

get_header();

$day_groups = msrseminars_get_agenda_day_groups();
$tab_id     = 'msr-agenda-tabs';
?>
<main id="site-content">
<section class="msrseminars-agenda-page">
	<div class="container">
		<div class="panel">
			<?php the_title( '<h1>', '</h1>' ); ?>
			<p class="lead"><?php echo esc_html( msrseminars_get_agenda_page_lead() ); ?></p>
			<?php the_content(); ?>
			<?php get_template_part( 'template-parts/forms/site-search' ); ?>
		</div>
		<?php if ( $day_groups ) : ?>
		<div class="agenda-tabs msr-agenda-tabs seminars-agenda" id="<?php echo esc_attr( $tab_id ); ?>">
			<div class="seminars-agenda-day-nav">
				<ul class="nav nav-tabs msr-agenda-tabs__nav seminars-agenda-day-nav__list" role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="<?php echo esc_attr( $tab_id ); ?>-all-tab" data-bs-toggle="tab" data-bs-target="#<?php echo esc_attr( $tab_id ); ?>-all" type="button" role="tab" aria-controls="<?php echo esc_attr( $tab_id ); ?>-all" aria-selected="true"><?php esc_html_e( 'All days', 'msrseminars' ); ?></button>
					</li>
					<?php foreach ( $day_groups as $group ) : ?>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="<?php echo esc_attr( $tab_id . '-' . $group['key'] ); ?>-tab" data-bs-toggle="tab" data-bs-target="#<?php echo esc_attr( $tab_id . '-' . $group['key'] ); ?>" type="button" role="tab" aria-controls="<?php echo esc_attr( $tab_id . '-' . $group['key'] ); ?>" aria-selected="false"><?php echo esc_html( msrseminars_get_agenda_day_tab_label( $group ) ); ?></button>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php msrseminars_render_agenda_session_filters(); ?>
			<div class="tab-content msr-agenda-tabs__content">
				<div class="tab-pane fade show active" id="<?php echo esc_attr( $tab_id ); ?>-all" role="tabpanel" aria-labelledby="<?php echo esc_attr( $tab_id ); ?>-all-tab" tabindex="0">
					<?php
					$has_sessions = false;
					foreach ( $day_groups as $group ) :
						$group_has_rows = false;
						foreach ( $group['tracks'] as $track ) {
							if ( msrseminars_get_sorted_schedule_rows( $track->ID ) ) {
								$group_has_rows = true;
								break;
							}
						}
						if ( ! $group_has_rows ) {
							continue;
						}
						$has_sessions = true;
						?>
					<section class="seminars-agenda-day-group mb-4">
						<h2 class="h3 seminars-agenda-day-group__heading"><?php echo esc_html( msrseminars_get_agenda_day_tab_label( $group ) ); ?></h2>
						<?php foreach ( $group['tracks'] as $track ) : ?>
							<?php msrseminars_render_agenda_track_timeline( $track, 'all-' . $group['key'] ); ?>
						<?php endforeach; ?>
					</section>
					<?php endforeach; ?>
					<?php if ( ! $has_sessions ) : ?>
					<?php
					msrseminars_render_empty_state(
						array(
							'context' => 'listing',
							'title'   => __( 'No agenda sessions yet', 'msrseminars' ),
							'message' => __( 'Agenda sessions will appear here when published in the admin.', 'msrseminars' ),
							'inline'  => true,
						)
					);
					?>
					<?php endif; ?>
				</div>
				<?php foreach ( $day_groups as $group ) : ?>
				<div class="tab-pane fade" id="<?php echo esc_attr( $tab_id . '-' . $group['key'] ); ?>" role="tabpanel" aria-labelledby="<?php echo esc_attr( $tab_id . '-' . $group['key'] ); ?>-tab" tabindex="0">
					<?php
					$has_day_sessions = false;
					foreach ( $group['tracks'] as $track ) {
						if ( msrseminars_get_sorted_schedule_rows( $track->ID ) ) {
							$has_day_sessions = true;
							msrseminars_render_agenda_track_timeline( $track, $group['key'] );
						}
					}
					if ( ! $has_day_sessions ) :
						msrseminars_render_empty_state(
							array(
								'context' => 'listing',
								'title'   => __( 'No sessions on this day', 'msrseminars' ),
								'message' => __( 'Published sessions for this day will appear here.', 'msrseminars' ),
								'inline'  => true,
							)
						);
					endif;
					?>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php else : ?>
		<?php
		msrseminars_render_empty_state(
			array(
				'context' => 'listing',
				'title'   => __( 'No agenda published yet', 'msrseminars' ),
				'message' => __( 'Agenda sessions will appear here when published in the admin.', 'msrseminars' ),
			)
		);
		?>
		<?php endif; ?>
	</div>
</section>
</main>
<?php
get_footer();
