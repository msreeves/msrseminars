    <?php if( have_rows('schedule') ): ?>

    <?php while( have_rows('schedule') ) : the_row(); ?>

         <div class="post panel"> 
            <div class="row">
                <div class="col-lg-2">
    <?php while( have_rows('time') ): the_row(); 

        $start = get_sub_field('start');
        $finish = get_sub_field('finish');

        ?>
        <h2><?php echo $start ?> </h2> 
        <h2><?php echo $finish ?> </h2> 
    <?php endwhile; ?>
                   <?php
    $featured_posts = get_sub_field('panel_sponsors');
    if( $featured_posts ): ?>
    <p> Sponsors:</p>
     <div class="sponsor">
        <?php foreach( $featured_posts as $featured_post ): 
        ?>  
                       <?php 
$link = get_field('link');
    $link_url = $link['url'];
    $link_title = $link['title'];
    $link_target = $link['target'] ? $link['target'] : '_self';
    ?>
    <a href="<?php echo the_field('link' , $featured_post);?>"><?php the_post_thumbnail($featured_post , 'full'); ?></a>   
            <hr></hr>
    <?php endforeach; ?>
        </div>
    <?php endif; ?>

                 </div>
                 <div class="col-lg-6">
                <h2><?php echo get_sub_field('name') ?> </h2> 
                <?php echo get_sub_field('about') ?>
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
            <span class="d-inline-block"> <a href="<?php echo esc_url( $permalink ); ?>"><button><?php echo esc_html( $title ); ?></button></a></span>
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
            <span class="d-inline-block"> <a href="<?php echo esc_url( $permalink ); ?>"><button><?php echo esc_html( $title ); ?></button></a></span>
    <?php endforeach; ?>
<?php endif; ?>
                 </div>
            </div>
        </div>
    <?php endwhile; ?>

<?php endif; ?>