<?php
/**
 * ACF: Flexible Content > Layouts > Listing Panelist
 *
 * @package msrseminars
 */

$heading      = isset( $args['title'] ) ? (string) $args['title'] : '';
$introduction = isset( $args['introduction'] ) ? (string) $args['introduction'] : '';
?>

<section class="msrseminars-panelists-block">
	<div class="container">
		<div class="panel">
			<?php if ( $heading ) : ?>
			<h2 class="msr-reveal"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>
			<?php if ( $introduction ) : ?>
			<p class="lead msr-reveal"><?php echo wp_kses_post( $introduction ); ?></p>
			<?php endif; ?>
			<?php get_template_part( 'template-parts/forms/site-search' ); ?>
		</div>
		<?php
		$all_posts = new WP_Query(
			array(
				'post_type'      => 'panelist',
				'posts_per_page' => -1,
				'meta_key'       => 'name',
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);
		?>

		<?php if ( $all_posts->have_posts() ) : ?>
		<div class="row">
			<?php
			while ( $all_posts->have_posts() ) :
				$all_posts->the_post();
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
