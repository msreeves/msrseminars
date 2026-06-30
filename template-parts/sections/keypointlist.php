<?php
/**
 * ACF: Flexible Content > Layouts > Key Point list
 *
 * @package WordPress
 * @subpackage msrseminars
 */

$columns = is_array( $args['keypoint'] ?? null ) ? $args['keypoint'] : array();
?>

<section class="seminars-keypoints msr-reveal">
  <div class="container">
    <div class="row">
    <?php foreach ( $columns as $column ) :
       $heading      = $column['title'] ?? '';
       $icon_class   = msrseminars_sanitize_fa_icon_class( $column['icon_class'] ?? '' );
       $icon         = $icon_class ? '' : msrseminars_acf_image_url( $column['icon'] ?? '' );
       $number       = $column['number'] ?? '';
       $introduction = $column['introduction'] ?? '';
       ?>
      <div class="col-xl-3 mx-auto">
        <div class="post panel">
          <?php if ( $icon_class || $icon ) : ?>
          <div class="icon" aria-hidden="true">
          <?php if ( $icon_class ) : ?>
          <i class="<?php echo esc_attr( $icon_class ); ?>"></i>
          <?php else : ?>
          <img src="<?php echo esc_url( $icon ); ?>" alt="" />
          <?php endif; ?>
           </div>
          <?php endif; ?>
        <div class="listing-text text-center">
           <?php if ( $number ) : ?>
        <h2 class="count"><?php echo esc_html( $number ); ?></h2>
        <?php endif; ?>
        <h2><?php echo esc_html( $heading ); ?></h2>
          <p><?php echo esc_html( $introduction ); ?></p>
           </div>
     </div>
    </div>
    <?php endforeach; ?>
     </div>
  </div>
</section>
