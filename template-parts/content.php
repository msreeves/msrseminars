<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package msrseminars
 */

?>

<section>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="panel text-center">
				     <p> <?php $exclude = array( 6 );

// The categories list.
$cat_list = array();

foreach ( get_the_category() as $cat ) {
    if ( ! in_array( $cat->term_id, $exclude ) ) {
        $cat_list[] = '<a href="' . esc_url( get_category_link( $cat->term_id ) ) .
            '"><span>' . $cat->name . '</span></a>';
    }
}

// Display a simple comma-separated list of links.
echo implode( ' ', $cat_list );?>
                 </p>  
				 <?php
if(in_category(6)){
?>
<h3> <i>This is Sponsored content</i></h3>
<?php } ?> 
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<p class="entry-meta">
				<i class="fa fa-clock fa-2xl"></i> 
                <?php echo get_the_date(); ?>
		</p><!-- .entry-meta -->
		<?php endif; ?>
			</div><!-- .entry-header -->

	<div class="entry-content">
		  <?php get_template_part('templates/partials/featured-image'); ?>
		  <section>
		<?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'msrsandbox' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			)
		);

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'msrsandbox' ),
				'after'  => '</div>',
			)
		);
		?>
		</section>
	</div><!-- .entry-content -->
    <?php

/*
 * Output comments wrapper if it's a post, or if comments are open,
 * or if there's a comment number – and check for password.
*/
if ((is_single() || is_page()) && (comments_open() || get_comments_number()) && !post_password_required())
{
?>

		<div class="comments-wrapper section-inner">

			<?php comments_template(); ?>

		</div><!-- .comments-wrapper -->

		<?php
}
?>
    <?php $related = new WP_Query(
    array(
        'category__in'   => wp_get_post_categories( $post->ID ),
        'posts_per_page' => 3,
        'post__not_in'   => array( $post->ID )
    )
); ?>

        <?php if ( $related->have_posts() ) : ?>
              <div class="row">			
				<h1> More with <?php $category = get_the_category(); echo $category[0]->cat_name; ?> </h1>
          <?php while ( $related->have_posts() ) : $related->the_post(); ?>	
         <?php get_template_part( 'templates/partials/post-listing/posts/subcategory' ); ?>
          <?php endwhile; ?>
          <?php wp_reset_query() ?>
      </div>
        <?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
</section>
