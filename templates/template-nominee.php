<?php
/**
 * Template Name: Nominees Template
 *
 * @package WordPress
 * @subpackage msrseminars
 * @since msrsandbox 1.0
 */
get_header();
?>
<section class="people">
	<div class="container">
		<div class="panel">
			<?php the_title( '<h1>', '</h1>' ); ?>
			<?php the_content(); ?>
			<?php get_template_part( 'template-parts/forms/site-search' ); ?>
		</div>
		<?php
		get_template_part(
			'templates/partials/filter-tabs',
			'',
			array(
				'taxonomy'      => 'award',
				'post_type'     => 'nominee',
				'all_label'     => __( 'All', 'msrseminars' ),
				'listing_all'       => 'template-parts/cards/nominee-card',
				'listing_all_args'  => array( 'show_award_terms' => true ),
				'listing_term'      => 'template-parts/cards/nominee-card',
				'listing_term_args' => array( 'show_award_terms' => false ),
				'query_args'    => array(
					'meta_key' => 'name',
					'orderby'  => 'meta_value',
					'order'    => 'ASC',
				),
				'empty_message' => __( 'No nominees found in this category.', 'msrseminars' ),
			)
		);
		?>
	</div>
</section>
<?php
get_footer();
