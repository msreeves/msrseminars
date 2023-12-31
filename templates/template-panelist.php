<?php
/**
 * Template Name: panelists template
 *
 * @package WordPress
 * @subpackage msrseminars
 * @since msrseminars 1.0
 */

get_header();
?>
<section class="people">
  <div class="container">
      <div class="panel">
        <?php the_title( '<h1>', '</h1>' ); ?>
          <?php the_content(); ?>
      </div>
         <?php 	
      $args = array(
        'post_type' => 'panelist',
        'posts_per_page' => -1,
        'meta_key'       => 'name',
        'orderby'        => 'meta_value',
        'order'          => 'ASC'
      );
      $all_partners = new WP_Query( $args );		
      ?>

      <?php if ( $all_partners->have_posts() ) : ?>
            <div class="row">
          <?php while ( $all_partners->have_posts() ) : $all_partners->the_post(); ?>	
      <?php get_template_part( 'templates/partials/post-listing/listing-panelist' ); ?>
          <?php endwhile; ?>
          <?php wp_reset_query() ?>
      </div>
      <?php endif; ?>
      </div>
</section>
<?php
get_footer();