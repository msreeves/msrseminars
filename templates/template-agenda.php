<?php
/**
 * Template Name: Agenda Template
 * Template Post Type: post, page
 * 
 * @package WordPress
 * @subpackage msrseminars
 * @since msrseminars 1.0
 */
get_header();
?>
<section>
  <div class="container">
      <div class="panel">
        <?php the_title( '<h1>', '</h1>' ); ?>
          <?php the_content(); ?>
      </div>
<div class="agenda-tabs">
<?php $args = array(
  'post_type'   => 'agenda'
);
   $post_names = get_posts( $args ); ?>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs">
  <li class="active">
      <a href="#all" data-bs-toggle="tab" role="tab" aria-controls="all" aria-selected="all"><button><?php echo $post_name->name ?>All</button></a>
    </li>
    <?php foreach($post_names as $post_name) { ?>
      <li>
        <a href="#<?php echo $post_name->post_name ?>" data-bs-toggle="tab"><button><?php echo $post_name->post_title; ?></button></a>
      </li>
    <? } ?>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">

    <div class="tab-pane fade show active" id="all">
      <?php 	
      $args = array(
        'post_type' => 'agenda',
        'posts_per_page' => -1,
      );
      $all_posts = new WP_Query( $args );		
      ?>

      <?php if ( $all_posts->have_posts() ) : ?>
            <div class="row">
          <?php while ( $all_posts->have_posts() ) : $all_posts->the_post(); ?>	
          <?php the_title( '<h2>', '</h2>' ); ?>
              <?php get_template_part( 'templates/partials/post-listing/listing-agenda' ); ?>
          <?php endwhile; ?>
          <?php wp_reset_query() ?>
      </div>
      <?php endif; ?>

    </div>

    <?php foreach($post_names as $post_name) { ?>

      <div class="tab-pane fade" id="<?php echo $post_name->post_name ?>">
        <?php 	
        $args = array(
          'post_type' => 'agenda',
          'posts_per_page'  => 1,
          'name' => $post_name->post_name 
        );
        $posts = new WP_Query( $args );		
        ?>

        <?php if ( $posts->have_posts() ) : ?>
              <div class="row">
          <?php while ( $posts->have_posts() ) : $posts->the_post(); ?>	
          <?php get_template_part( 'templates/partials/post-listing/listing-agenda' ); ?>
          <?php endwhile; ?>
          <?php wp_reset_query() ?>
      </div>
        <?php endif; ?>

      </div>
    <? }  ?>

  </div>
        </div>
        </div>
</section>
<?php
get_footer();