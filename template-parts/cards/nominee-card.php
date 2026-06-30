<?php
/**
 * Nominee archive card.
 *
 * @package msrseminars
 *
 * @var array $args {
 *     @type bool $show_award_terms Show award taxonomy badges.
 * }
 */

$show_award_terms = ! isset( $args['show_award_terms'] ) || $args['show_award_terms'];
$terms            = $show_award_terms ? get_the_terms( get_the_ID(), 'award' ) : array();
if ( is_wp_error( $terms ) ) {
	$terms = array();
}
?>
<div class="col-xl-4 col-lg-4 col-md-6">
	<article <?php post_class( 'nominee-card post panel msr-reveal msr-reveal--up' ); ?>>
		<?php msrseminars_render_card_media(); ?>
		<div class="nominee-card__body listing-text text-center">
			<?php if ( $show_award_terms && $terms ) : ?>
				<div class="nominee-card__terms" aria-label="<?php esc_attr_e( 'Award categories', 'msrseminars' ); ?>">
					<?php foreach ( $terms as $term ) : ?>
						<?php if ( $term instanceof WP_Term ) : ?>
							<span><?php echo esc_html( $term->name ); ?></span>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<h2 class="h4 nominee-card__title"><?php the_title(); ?></h2>
			<?php if ( get_field( 'job_title' ) ) : ?>
				<p class="mb-1">
					<i class="fa-solid fa-briefcase" aria-hidden="true"></i>
					<?php echo esc_html( (string) get_field( 'job_title' ) ); ?>
				</p>
			<?php endif; ?>
			<?php if ( get_field( 'company' ) ) : ?>
				<p class="text-muted mb-2">
					<i class="fa-solid fa-location-dot" aria-hidden="true"></i>
					<?php echo esc_html( (string) get_field( 'company' ) ); ?>
				</p>
			<?php endif; ?>
			<?php
			$excerpt = msrseminars_get_person_profile_excerpt( get_the_ID(), 24 );
			if ( $excerpt ) :
				?>
				<p class="small text-muted"><?php echo esc_html( $excerpt ); ?></p>
				<a class="btn btn-primary btn-sm mt-2" href="<?php the_permalink(); ?>">
					<?php esc_html_e( 'Read profile', 'msrseminars' ); ?>
				</a>
			<?php endif; ?>
		</div>
	</article>
</div>
