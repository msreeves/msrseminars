<?php
/**
 * The main template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package msrseminars
 */

get_header();
?>

<main id="site-content">
<?php if ( msrseminars_is_programme_home() ) : ?>
	<?php get_template_part( 'template-parts/sections/home-hero' ); ?>
	<?php
	$sponsors = get_field( 'main_sponsor', 'option' );
	if ( $sponsors ) {
		get_template_part(
			'template-parts/components/main-sponsor-bar',
			null,
			array( 'sponsors' => $sponsors )
		);
	}
	if ( function_exists( 'msrseminars_render_programme_stats' ) ) {
		msrseminars_render_programme_stats();
	}
	if ( function_exists( 'msrseminars_render_featured_sessions' ) ) {
		msrseminars_render_featured_sessions();
	}
	?>
	<?php
	$sections = get_field( 'add_sections' );

	if ( is_array( $sections ) ) :
		foreach ( $sections as $section ) :
			if ( ! is_array( $section ) || empty( $section['acf_fc_layout'] ) ) {
				continue;
			}
			$template = str_replace( '_', '-', $section['acf_fc_layout'] );
			get_template_part( 'template-parts/sections/' . $template, '', $section );
		endforeach;
	endif;
	?>
	<?php
	if ( function_exists( 'msrseminars_render_delegate_timeline' ) ) {
		msrseminars_render_delegate_timeline();
	}
	if ( function_exists( 'msrseminars_render_ecosystem_band' ) ) {
		msrseminars_render_ecosystem_band();
	}
	?>
<?php else : ?>
	<section>
		<div class="container">
			<div class="panel">
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</div>
		</div>
	</section>
<?php endif; ?>
</main>
<?php
get_footer();
