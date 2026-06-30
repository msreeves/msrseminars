<?php
/**
 * Panelist single content.
 *
 * @package msrseminars
 */

?>

<article <?php post_class( 'seminars-person' ); ?> id="post-panelist<?php the_ID(); ?>">
	<div class="container">
		<div class="row g-0">
			<div class="col-xl-6 col-lg-6">
				<div class="panel">
					<div class="my-auto text-center">
						<?php msrseminars_render_person_social_links(); ?>
						<?php the_title( '<h1>', '</h1>' ); ?>
						<?php msrseminars_render_person_job_company(); ?>
					</div>
				</div>
			</div>
			<div class="col-xl-6 col-lg-6">
				<div class="panel">
					<div class="listing-image">
						<?php
						if ( has_post_thumbnail() ) {
							the_post_thumbnail();
							$caption = wp_get_attachment_caption( get_post_thumbnail_id() );
							if ( $caption ) {
								echo '<p class="small text-muted">' . esc_html( (string) $caption ) . '</p>';
							}
						}
						?>
					</div>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="panel">
					<?php if ( get_field( 'profile' ) ) : ?>
						<div class="post-inner">
							<?php msrseminars_render_person_profile(); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<div class="section-inner">
		<?php
		wp_link_pages(
			array(
				'before'      => '<nav class="post-nav-links bg-light-background" aria-label="' . esc_attr__( 'Page', 'msrseminars' ) . '"><span class="label">' . __( 'Pages:', 'msrseminars' ) . '</span>',
				'after'       => '</nav>',
				'link_before' => '<span class="page-number">',
				'link_after'  => '</span>',
			)
		);

		edit_post_link();
		?>
	</div>
</article>
