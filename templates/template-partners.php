<?php
/**
 * Template Name: Partners template
 *
 * @package msrseminars
 */

get_header();
?>

<main id="site-content" class="site-main">
<section class="partner msrseminars-partners-page seminars-archive-listing">
	<div class="container">
		<header class="seminars-partners-intro">
			<?php the_title( '<h1>', '</h1>' ); ?>
			<p class="lead"><?php echo esc_html( msrseminars_get_partners_page_lead() ); ?></p>
			<?php if ( get_the_content() ) : ?>
				<div class="seminars-partners-intro__content msr-rich-text">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>
		</header>
		<?php msrseminars_render_partner_tier_grid(); ?>
		<div class="seminars-partners-supplement">
			<?php get_template_part( 'template-parts/forms/site-search' ); ?>
		</div>
		<?php
		if ( function_exists( 'msrseminars_render_ecosystem_band' ) ) {
			msrseminars_render_ecosystem_band();
		}
		?>
	</div>
</section>
</main>
<?php
get_footer();
