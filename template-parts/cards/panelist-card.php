<?php
/**
 * Panelist archive card.
 *
 * @package msrseminars
 */
?>
<div class="col-xl-4 col-lg-4">
	<article <?php post_class( 'panelist-card post panel msr-reveal msr-reveal--up' ); ?>>
		<?php msrseminars_render_card_media(); ?>
		<div class="panelist-card__body listing-text text-center">
			<h2 class="h4 panelist-card__title"><?php the_title(); ?></h2>
			<?php if ( get_field( 'job_title' ) ) : ?>
				<p class="panelist-card__job mb-1">
					<i class="fa-solid fa-briefcase" aria-hidden="true"></i>
					<?php echo esc_html( (string) get_field( 'job_title' ) ); ?>
				</p>
			<?php endif; ?>
			<?php
			$excerpt = msrseminars_get_person_profile_excerpt( get_the_ID(), 24 );
			if ( $excerpt ) :
				?>
				<p class="panelist-card__excerpt small text-muted"><?php echo esc_html( $excerpt ); ?></p>
				<a class="btn btn-primary btn-sm mt-2" href="<?php the_permalink(); ?>">
					<?php esc_html_e( 'Read profile', 'msrseminars' ); ?>
				</a>
			<?php endif; ?>
		</div>
	</article>
</div>
