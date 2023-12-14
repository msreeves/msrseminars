    <div class="col-lg-4">
      <div class="panel">
        <div class="listing-image">
             <?php get_template_part( 'templates/partials/featured-image' ); ?>
        </div>
               <div class="listing-text text-center">
            <h2><?php the_title() ?></h2>
            <?php if ( get_field('job_title') ) : ?>
            <p> <i class="fa fa-briefcase fa-xl" aria-hidden="true"></i> <?php print get_field('job_title') ?> </p>
             <?php endif; ?>
              <?php if ( get_field('profile') ) : ?>
            <p> <?php
                $trim_length = 150;
                $custom_field = 'profile';
                $value = get_post_meta($post->ID, $custom_field, true);
                if ($value) {
                if (strlen($value) > $trim_length)
                $value = rtrim(substr($value,0,$trim_length)) .'...';
                print $value;
                }?></p>
             <?php endif; ?>
              <a href="<?php echo the_permalink(); ?>"><button>Read Profile</button></a>
                </div>
  </div>
       </div>