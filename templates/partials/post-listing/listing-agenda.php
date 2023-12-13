    <?php if( have_rows('schedule') ): ?>

    <?php while( have_rows('schedule') ) : the_row(); ?>
         <div class="agenda panel h-auto"> 
            <div class="row">
                <div class="col-lg-2">
    <?php while( have_rows('time') ): the_row(); 

        $start = get_sub_field('start');
        $finish = get_sub_field('finish');

        ?>
        <h2><?php echo $start ?> - <?php echo $finish ?></h2> 
    <?php endwhile; ?>

                 </div>
                 <div class="col-lg-6">
                <h2><?php echo get_sub_field('name') ?> </h2> 
                <?php echo get_sub_field('about') ?>
                   <?php
    $featured_posts = get_sub_field('sponsor');
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
    <a href="<?php echo the_field('link' , $featured_post);?>"> <?php $image = wp_get_attachment_image_src(
                                                   get_post_thumbnail_id(
                                                       $featured_post
                                                   ),
                                               ); ?>
                                                <img src="<?php echo $image[0]; ?>" alt=""> </a>   
            <hr></hr>
    <?php endforeach; ?>
        </div>
    <?php endif; ?>
                 </div>
                  <div class="col-lg-4">
                    <?php
$judicators = get_sub_field('judicators');
if( $judicators ): ?>
     <h3>Judicator: </h3> 
    <?php foreach( $judicators as $judicator ): 
        $permalink = get_permalink( $judicator->ID );
        $title = get_the_title( $judicator->ID );
        ?>
            <a class="d-inline-block" href="<?php echo esc_url( $permalink ); ?>"><button><?php echo esc_html( $title ); ?></button></a>
    <?php endforeach; ?>
<?php endif; ?>
<hr></hr>
                           <?php
$panelists = get_sub_field('panelists');
if( $panelists ): ?>
     <h3>Panelist: </h3> 
    <?php foreach( $panelists as $panelist ): 
        $permalink = get_permalink( $panelist->ID );
        $title = get_the_title( $panelist->ID );
        ?>
            <a class="d-inline-block" href="<?php echo esc_url( $permalink ); ?>"><button><?php echo esc_html( $title ); ?></button></a>
    <?php endforeach; ?>
<?php endif; ?>
                 </div>
            </div>
        </div>
    <?php endwhile; ?>

<?php endif; ?>