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
			<section class="post-inner">
		<div class="entry-content">
			<?php the_title( '<h1>', '</h1>' ); ?>
			<?php
    $featured_posts = get_field('sponsors');
    if( $featured_posts ): ?>
    <h3 class="text-center"> Sponsors:</h3>
     <div class="sponsor">
        <?php foreach( $featured_posts as $featured_post ): 
        ?>  
                       <?php 
$link = get_field('link');
    $link_url = $link['url'];
    $link_title = $link['title'];
    $link_target = $link['target'] ? $link['target'] : '_self';
    ?>
    <a href="<?php echo the_field('link' , $featured_post);?>"><?php $image = wp_get_attachment_image_src(
                                                   get_post_thumbnail_id(
                                                       $featured_post
                                                   ),
                                               ); ?>
                                                <img src="<?php echo $image[0]; ?>" alt=""> </a> </a>   
            <hr></hr>
    <?php endforeach; ?>
        </div>
    <?php endif; ?>
			<?php print get_field('information') ?> 
			<?php get_template_part( 'templates/partials/featured-image' ); ?>
				
		</div><!-- .entry-content -->

	</section><!-- .post-inner -->
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
