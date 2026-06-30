<?php
/**
 * 404 — seminars programme chrome with helpful links.
 *
 * @package msrseminars
 */

get_header();

$home      = home_url( '/' );
$agenda    = msrseminars_get_page_url( 'agenda', '/agenda/' );
$panelists = msrseminars_get_page_url( 'panelists', '/panelists/' );
$topics    = msrseminars_get_page_url( 'topics', '/topics/' );
$search    = home_url( '/?s=' );
?>
<main id="site-content" class="seminars-error-page">
	<div class="container py-5 text-center">
		<p class="seminars-error-page__code display-1 mb-2" aria-hidden="true">404</p>
		<h1 class="h2 mb-3"><?php esc_html_e( 'Page not found', 'msrseminars' ); ?></h1>
		<p class="text-muted mb-4 seminars-error-page__lead">
			<?php esc_html_e( 'That URL is not part of the MSR Seminars programme site, or it may have moved.', 'msrseminars' ); ?></p>
		<nav class="d-flex flex-wrap gap-2 justify-content-center" aria-label="<?php esc_attr_e( 'Helpful links', 'msrseminars' ); ?>">
			<a class="btn btn-primary" href="<?php echo esc_url( $home ); ?>"><?php esc_html_e( 'Home', 'msrseminars' ); ?></a>
			<a class="btn btn-outline-primary" href="<?php echo esc_url( $agenda ); ?>"><?php esc_html_e( 'Agenda', 'msrseminars' ); ?></a>
			<a class="btn btn-outline-primary" href="<?php echo esc_url( $panelists ); ?>"><?php esc_html_e( 'Panelists', 'msrseminars' ); ?></a>
			<a class="btn btn-outline-primary" href="<?php echo esc_url( $topics ); ?>"><?php esc_html_e( 'Topics', 'msrseminars' ); ?></a>
			<a class="btn btn-outline-primary" href="<?php echo esc_url( $search ); ?>"><?php esc_html_e( 'Search', 'msrseminars' ); ?></a>
		</nav>
	</div>
</main>
<?php
get_footer();
