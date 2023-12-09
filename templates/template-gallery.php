<?php
/**
 * Template Name: Gallery template
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
<?php
$gallery = get_field( 'gallery' ); ?>
<div class="row">
     <div class="d-flex flex-wrap">
<?php foreach( $gallery as $img ) :?>
    <div class="p-3">
  <a href="<?php echo $img['url'];?>" data-lightbox="gallery" data-title="<?php echo $img['title'];?>">
  <img src="<?php echo $img['sizes']['thumbnail'];?>"></a>
  </div>
 <?php endforeach; ?>
</div>
        </div>
        </div>
        </section>
        <?php
get_footer();