<?php
/**
 * Category archive — topics hub parity (filter tabs + skip-link target).
 *
 * @package msrseminars
 */

get_header();

$term = get_queried_object();
$slug = ( $term instanceof WP_Term ) ? $term->slug : '';
$topics_url = msrseminars_get_page_url( 'topics', '/topics/' );
$term_name  = ( $term instanceof WP_Term ) ? $term->name : '';
$term_desc  = ( $term instanceof WP_Term ) ? term_description( $term ) : '';
?>

<main id="site-content" class="site-main">
<section class="seminars-archive-listing seminars-topics-archive">
	<div class="container">
		<header class="seminars-topics-intro" data-msr-filter-intro>
			<?php if ( '' !== $topics_url ) : ?>
			<p class="seminars-topics-intro__back small mb-2">
				<a href="<?php echo esc_url( $topics_url ); ?>"><?php esc_html_e( '← All topics', 'msrseminars' ); ?></a>
			</p>
			<?php endif; ?>
			<div data-msr-filter-intro-body<?php echo '' === $slug ? ' hidden' : ''; ?>>
				<h1 data-msr-filter-intro-title><?php echo esc_html( $term_name ); ?></h1>
				<div class="seminars-topics-intro__description" data-msr-filter-intro-description>
					<?php echo wp_kses_post( $term_desc ); ?>
				</div>
			</div>
		</header>
		<?php get_template_part( 'template-parts/forms/site-search' ); ?>
		<?php
		get_template_part(
			'templates/partials/filter-tabs',
			'',
			array(
				'taxonomy'          => 'category',
				'post_type'         => 'post',
				'all_label'         => __( 'All', 'msrseminars' ),
				'listing_all'       => 'template-parts/cards/post-card',
				'listing_term'      => 'template-parts/cards/post-card',
				'parent'            => 0,
				'initial_term_slug' => $slug,
				'filter_label'      => __( 'Filter topics', 'msrseminars' ),
				'query_args'        => array(
					'orderby' => 'date',
					'order'   => 'ASC',
				),
				'empty_message'     => __( 'No posts found in this category.', 'msrseminars' ),
			)
		);
		?>
	</div>
</section>
</main>

<?php
get_footer();
