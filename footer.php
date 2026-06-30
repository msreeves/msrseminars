<?php
/**
 * The template for displaying the footer
 *
 * @package msrseminars
 */

?>
<?php
if ( function_exists( 'msrseminars_show_leaderboard_ads' ) && msrseminars_show_leaderboard_ads() ) {
	get_template_part( 'templates/partials/leaderboard/footer' );
}
?>
<?php
if ( function_exists( 'msrseminars_render_site_footer' ) ) {
	msrseminars_render_site_footer();
}
?>
<?php wp_footer(); ?>
</body>
</html>
