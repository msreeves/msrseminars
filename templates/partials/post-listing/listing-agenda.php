    <?php if( have_rows('schedule') ): ?>

    <?php while( have_rows('schedule') ) : the_row(); ?>
         <div class="agenda panel h-auto"> 
            <div class="row">
                <div class="col-lg-4">
    <?php while( have_rows('time') ): the_row(); 

        $start = get_sub_field('start');
        $finish = get_sub_field('finish');

        ?>
        <h2><?php echo $start ?> - <?php echo $finish ?></h2> 
    <?php endwhile; ?>
  <?php
    $featured_posts = get_sub_field('sponsor');
    if( $featured_posts ): ?>
    <h3> Sponsors:</h3>
     <div class="sponsor">
        <?php foreach( $featured_posts as $featured_post ): 
        ?>  
                       <?php 
    $link = get_field('link');
    $link_url = $link['url'];
    $link_title = $link['title'];
    $link_target = $link['target'] ? $link['target'] : '_self';
    ?>
    <a href="<?php echo the_field('link' , $featured_post);?>"> <?php $image = wp_get_attachment_image_src(
                                                   get_post_thumbnail_id(
                                                       $featured_post
                                                   ),
                                               ); ?>
                                                <img src="<?php echo $image[0]; ?>" alt="<?php the_post_thumbnail_caption(); ?>"> </a>   
            <hr></hr>
    <?php endforeach; ?>
        </div>
    <?php endif; ?>
                 </div>
                 <div class="col-lg-8">
                <h2><?php echo get_sub_field('name') ?> </h2> 
                <?php echo get_sub_field('about') ?>
                 <?php
$judicators = get_sub_field('judicators');
if( $judicators ): ?>
     <h3>Judicator: </h3> 
     <div class="panelists">
    <?php foreach( $judicators as $judicator ): 
        $permalink = get_permalink( $judicator->ID );
        $title = get_the_title( $judicator->ID );
        ?>

            <a href="<?php echo esc_url( $permalink ); ?>"><div class="panel"><div class="listing-image"><?php $image = wp_get_attachment_image_src(
                                                   get_post_thumbnail_id(
                                                       $judicator->ID,
                                                   ),
                                                   "single-post-thumbnail"
                                               ); ?>
                                                <img src="<?php echo $image[0]; ?>" alt="<?php the_post_thumbnail_caption(); ?>"></div><div class="listing-text my-auto"><h4><?php echo esc_html( $title ); ?></h4></div></div></a>
    <?php endforeach; ?>
                                            </div>
<?php endif; ?>
<hr></hr>
                           <?php
$panelists = get_sub_field('panelists');
if( $panelists ): ?>
     <h3>Panelist: </h3> 
      <div class="panelists">
    <?php foreach( $panelists as $panelist ): 
        $permalink = get_permalink( $panelist->ID );
        $title = get_the_title( $panelist->ID );
        ?>

            <a href="<?php echo esc_url( $permalink ); ?>"><div class="panel"><div class="listing-image"><?php $image = wp_get_attachment_image_src(
                                                   get_post_thumbnail_id(
                                                       $panelist->ID
                                                   ),
                                                   "single-post-thumbnail"
                                               ); ?>
                                                <img src="<?php echo $image[0]; ?>" alt="<?php the_post_thumbnail_caption(); ?>"></div><div class="listing-text my-auto"><h4><?php echo esc_html( $title ); ?></h4></div></div></a>
    <?php endforeach; ?>
    </div>
<?php endif; ?>
                 </div>
            </div>
        </div>
    <?php endwhile; ?>

<?php endif; ?>