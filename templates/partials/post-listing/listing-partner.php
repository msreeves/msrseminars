         <div class="col-lg-4 mx-auto">
              <div class="panel">
                <div class="partner-listing-image">
                          <?php 
$link = get_field('link');
    $link_url = $link['url'];
    $link_title = $link['title'];
    $link_target = $link['target'] ? $link['target'] : '_self';
    ?>
    <a href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php the_post_thumbnail(); ?></a>
          </div>
         </div>
             </div>