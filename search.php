<?php
/**
 * The template for displaying search results pages
 *
 * @package msrseminars
 */

get_header();
?>

<main id="site-content" class="site-main">
	<section>
		<div class="container">
			<?php if ( have_posts() ) : ?>
			<header class="page-header panel">
				<h1 class="page-title">
					<?php
					printf(
						/* translators: %s: search query */
						esc_html__( 'Search results for “%s”', 'msrseminars' ),
						esc_html( get_search_query() )
					);
					?>
				</h1>
				<p class="text-center" role="status">
					<?php
					global $wp_query;
					$found = (int) $wp_query->found_posts;
					printf(
						/* translators: %d: number of results */
						esc_html( _n( '%d result found.', '%d results found.', $found, 'msrseminars' ) ),
						$found
					);
					?>
				</p>
			</header>
			<?php get_template_part( 'template-parts/forms/site-search' ); ?>
			<div class="row msr-card-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', 'search' );
				endwhile;
				?>
			</div>
			<nav class="msr-search-pagination" aria-label="<?php esc_attr_e( 'Search results pages', 'msrseminars' ); ?>">
				<?php
				the_posts_pagination(
					array(
						'mid_size'  => 2,
						'prev_text' => __( 'Previous', 'msrseminars' ),
						'next_text' => __( 'Next', 'msrseminars' ),
					)
				);
				?>
			</nav>
			<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php
get_footer();
