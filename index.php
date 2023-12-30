<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package msrseminars
 */

get_header();

$image = get_field('image' , 'option');
$date = get_field('date' , 'option');
$time = get_field('time' , 'option');
$venue = get_field('venue' , 'option');
$sponsors = get_field('main_sponsor' , 'option');
?>

<main id="site-content">
<?php if (is_page( 95 )) : ?>
  <?php if( get_field('hero' ,'option') ) : ?>
  <div class="p-5 background-image" style="background-image: url('<?php echo the_field('image', 'option'); ?>');">
  <div class="mask p-5" style="background-color: rgba(0, 0, 0, 0.2);">
    <div class="d-flex justify-content-center align-items-center h-100">
      <div class="text-white text-center">
        <h1><?php the_field('name', 'option'); ?></h1>
        <h2><?php echo $venue['name']; ?></h2>
          <i class="fa fa-map-marker fa-2xl" aria-hidden="true"></i>
          <h3><?php echo $venue['address']; ?> </h3> 
         <h3><?php if($date['start']) : ?>  <i class="fa-solid fa-calendar"></i> <?php endif; ?> <?php echo $date['start']; ?> <?php if($date['finish']) : ?> - <?php echo $date['finish']; ?><?php endif; ?> </h3> 
          <h3><?php if($time['start']) : ?>  <i class="fa-solid fa-clock"></i> <?php endif; ?> <?php echo $time['start']; ?> <?php if($time['finish']) : ?> - <?php echo $time['finish']; ?><?php endif; ?> </h3> 
         <div class="d-flex justify-content-around">
                                      <?php 
$link = get_field('link1', 'option');
if( $link ): 
    $link_url = $link['url'];
    $link_title = $link['title'];
    $link_target = $link['target'] ? $link['target'] : '_self';
    ?>
    <a class="m-1" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><button><?php echo esc_html( $link_title ); ?></button></a>
      <?php endif; ?>
                   <?php 
$link = get_field('link2', 'option');
if( $link ): 
    $link_url = $link['url'];
    $link_title = $link['title'];
    $link_target = $link['target'] ? $link['target'] : '_self';
    ?>
    <a class="m-1" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><button class="secondary"><?php echo esc_html( $link_title ); ?></button></a>
      <?php endif; ?>
      </div>
          </div>  
    </div>
</div>
</div>
  <?php if( $sponsors ) : ?>
              <div class="row g-0">                            
				   <div class="col-xl-6 col-lg-6">
               		<div class="panel">
				<div class="my-auto">
				<h2 class="text-center">Main Sponsor</h2>
		  </div>
		  </div>
                </div>
                    <div class="col-xl-6 col-lg-6"> 
                                        		<div class="panel">
                                               <div class="sponsor">
                                               <?php foreach( $sponsors as $sponsor ): ?>
                                                <a href="<?php echo the_field('link' , $sponsor);?>" target="_blank">
                                               <?php $image = wp_get_attachment_image_src(
                                                   get_post_thumbnail_id(
                                                       $sponsor
                                                   ),
                                               ); ?>
                                                <img src="<?php echo $image[0]; ?>" alt=""> 
                                              </a>
                                                <?php endforeach; ?>
                                              </div>
                            </div>
                            </div>
                     </div>
                       <?php endif; ?>
<?php else : ?>
  <?php endif; ?>
    <?php
      $sections = get_field( 'add_sections' );

    if ( $sections ) :
        foreach ( $sections as $section ) :
            $template = str_replace( '_', '-', $section['acf_fc_layout'] );
            get_template_part( 'inc/components/' . $template, '', $section );
        endforeach;
      endif;
?>
<?php else : ?>
  <section>
  <div class="container">
    <div class="panel">
       <h1> <?php the_title(); ?> </h1>
    <?php the_content(); ?>
</div>
  </div>
</section>
<?php endif; ?>
</main>
<?php
get_footer();