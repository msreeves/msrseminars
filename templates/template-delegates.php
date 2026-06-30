<?php
/**
 * Template Name: Delegates template
 *
 * Delegate journey preview for portfolio demonstration.
 *
 * @package msrseminars
 */

get_header();
?>
<main id="site-content" class="site-main seminars-delegate-page">
	<section>
		<div class="container">
			<div class="panel text-center mb-4">
				<?php the_title( '<h1>', '</h1>' ); ?>
				<p class="lead"><?php echo esc_html( msrseminars_get_delegates_page_lead() ); ?></p>
				<?php the_content(); ?>
				<?php get_template_part( 'template-parts/forms/site-search' ); ?>
			</div>
			<?php
			if ( function_exists( 'msrseminars_render_delegate_journey' ) ) {
				msrseminars_render_delegate_journey();
			}
			if ( function_exists( 'msrseminars_render_delegate_timeline' ) ) {
				msrseminars_render_delegate_timeline();
			}
			if ( function_exists( 'msrseminars_render_ecosystem_band' ) ) {
				msrseminars_render_ecosystem_band();
			}
			?>
		</div>
	</section>
</main>
<?php
get_footer();
