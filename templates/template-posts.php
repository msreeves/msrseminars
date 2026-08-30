<?php
/**
 * Template Name: Posts Template
 *
 * @package WordPress
 * @subpackage msrseminars
 * @since msrsandbox 1.0
 */
get_header();
?>
<main id="site-content" class="site-main">
<section class="seminars-archive-listing seminars-topics-archive">
	<div class="container">
		<header class="seminars-topics-intro" data-msr-filter-intro>
			<?php /* Title + description sync from filter tabs; hidden while All is active. */ ?>
			<div data-msr-filter-intro-body hidden>
				<h1 data-msr-filter-intro-title></h1>
				<div class="seminars-topics-intro__description" data-msr-filter-intro-description></div>
			</div>
		</header>
		<?php get_template_part( 'template-parts/forms/site-search' ); ?>
		<?php
		get_template_part(
			'templates/partials/filter-tabs',
			'',
			array(
				'taxonomy'      => 'category',
				'post_type'     => 'post',
				'all_label'     => __( 'All', 'msrseminars' ),
				'listing_all'   => 'template-parts/cards/post-card',
				'listing_term'  => 'template-parts/cards/post-card',
				'parent'        => 0,
				'filter_label'  => __( 'Filter topics', 'msrseminars' ),
				'query_args'    => array(
					'orderby' => 'date',
					'order'   => 'ASC',
				),
				'empty_message' => __( 'No posts found in this category.', 'msrseminars' ),
			)
		);
		?>
	</div>
</section>
</main>
<?php
get_footer();
