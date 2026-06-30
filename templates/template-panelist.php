<?php
/**
 * Template Name: panelists template
 *
 * @package msrseminars
 */

get_header();
?>
<main id="site-content">
<section class="people msrseminars-panelists-page">
	<div class="container">
		<div class="panel">
			<?php the_title( '<h1>', '</h1>' ); ?>
			<p class="lead"><?php echo esc_html( msrseminars_get_panelists_page_lead() ); ?></p>
			<?php the_content(); ?>
			<?php get_template_part( 'template-parts/forms/site-search' ); ?>
		</div>
		<?php
		$all_panelists = new WP_Query(
			array(
				'post_type'      => 'panelist',
				'posts_per_page' => -1,
				'meta_key'       => 'name',
				'orderby'        => 'meta_value',
				'order'          => 'ASC',
			)
		);
		?>

		<?php if ( $all_panelists->have_posts() ) : ?>
		<div class="row">
			<?php
			while ( $all_panelists->have_posts() ) :
				$all_panelists->the_post();
				get_template_part( 'template-parts/cards/panelist-card' );
			endwhile;
			wp_reset_postdata();
			?>
		</div>
		<?php else : ?>
		<?php
		msrseminars_render_empty_state(
			array(
				'context' => 'listing',
				'title'   => __( 'No panelists published yet', 'msrseminars' ),
				'message' => __( 'Speaker profiles will appear here when panelists are published in the admin.', 'msrseminars' ),
			)
		);
		?>
		<?php endif; ?>
	</div>
</section>
</main>
<?php
get_footer();
