<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @package msrseminars
 */

?>

<section class="no-results not-found">
	<div class="container">
	<div class="panel">
		<?php if ( is_search() ) : ?>
			<h1 class="page-title"><?php esc_html_e( 'Sorry, no luck in your search ', 'msrseminars' ); ?></h1>

			<h3 class="text-center"><?php esc_html_e( 'Please try again with some different keywords.', 'msrseminars' ); ?></h3>
			 <?php get_template_part( 'inc/controllers/searchbar' ); ?>
			<?php endif; ?>
		</div>
	</div>
</section>
