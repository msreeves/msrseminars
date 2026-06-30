<?php
/**
 * The template for displaying archive pages
 *
 * @package msrseminars
 */

get_header();
?>

<main id="site-content" class="site-main">
<section class="seminars-archive-listing">
	<div class="container">
		<header class="seminars-archive-intro">
			<?php the_archive_title( '<h1>', '</h1>' ); ?>
			<?php the_archive_description( '<div class="seminars-archive-intro__description">', '</div>' ); ?>
		</header>
		<?php if ( have_posts() ) : ?>
			<?php get_template_part( 'template-parts/forms/site-search' ); ?>
			<div class="row msr-card-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/cards/post-card' );
				endwhile;
				?>
			</div>
			<nav class="msr-archive-pagination" aria-label="<?php esc_attr_e( 'Archive pages', 'msrseminars' ); ?>">
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
			<?php
			msrseminars_render_empty_state(
				array(
					'context' => 'archive',
				)
			);
			?>
		<?php endif; ?>
	</div>
</section>
</main>

<?php
get_footer();
