<?php
/**
 * The default template for displaying content
 *
 * Used for both singular and index.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage msrseminars
 * @since msrseminars 1.0
 */

?>

<article <?php post_class(); ?> id="post-agenda<?php the_ID(); ?>">
<div class="container">
	<div class="row g-0">
		<div class="col-sm-12">
				<?php the_title( '<h1>', '</h1>' ); ?>
		<?php get_template_part( 'templates/partials/featured-image' ); ?>
			<div class="post-inner">
		<div class="entry-content">
			<div class="listing-panel">
				<?php print get_field('information') ?> 
			</div>
		</div><!-- .entry-content -->

	</div><!-- .post-inner -->
	 <?php get_template_part( 'templates/partials/post-listing/listing-agenda' ); ?>
	</div>
		</div>
	<div class="section-inner">
		<?php
		wp_link_pages(
			array(
				'before'      => '<nav class="post-nav-links bg-light-background" aria-label="' . esc_attr__( 'Page', 'msrseminars' ) . '"><span class="label">' . __( 'Pages:', 'msrseminars' ) . '</span>',
				'after'       => '</nav>',
				'link_before' => '<span class="page-number">',
				'link_after'  => '</span>',
			)
		);

		edit_post_link();
		?>

	</div><!-- .section-inner -->

	<?php

	/*
	 * Output comments wrapper if it's a post, or if comments are open,
	 * or if there's a comment number – and check for password.
	 */
	if ( ( is_single() || is_page() ) && ( comments_open() || get_comments_number() ) && ! post_password_required() ) {
		?>

		<div class="comments-wrapper section-inner">

			<?php comments_template(); ?>

		</div><!-- .comments-wrapper -->

		<?php
	}
	?>

</article><!-- .post -->
