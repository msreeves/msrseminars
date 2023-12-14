<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package msrseminars
 */

?>

  <div class="col-lg-4">
    <div class="panel">  
        <div class="listing-image">
           <?php get_template_part( 'templates/partials/featured-image' ); ?>
            			         <?php
if(in_category(10)){
?>
<span class="sponsored">This is Sponsored content</span>
<?php } ?> 
            </div>
            <div class="listing-text">
               <p> <?php $exclude = array( 6 );

// The categories list.
$cat_list = array();

foreach ( get_the_category() as $cat ) {
    if ( ! in_array( $cat->term_id, $exclude ) ) {
        $cat_list[] = '<a href="' . esc_url( get_category_link( $cat->term_id ) ) .
            '"><span>' . $cat->name . '</span></a>';
    }
}

// Display a simple comma-separated list of links.
echo implode( ' ', $cat_list );?>
                 </p>  
            <h3> <?php echo search_title_highlight(); ?></h3>
            <?php echo search_excerpt_highlight(); ?>
            <a href="<?php echo the_permalink(); ?>"><button>Read more</button></a>
                    </div>
                </div>
                    </div>