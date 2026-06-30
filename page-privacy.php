<?php
/**
 * Privacy notice (demo) — slug: privacy.
 *
 * @package msrseminars
 */

get_header();
?>
<main id="site-content" class="awards-privacy-page">
	<div class="container py-5">
		<div class="row justify-content-center">
			<div class="col-lg-8">
				<header class="mb-4">
					<h1 class="entry-title"><?php esc_html_e( 'Privacy notice (demonstration)', 'msrseminars' ); ?></h1>
					<p class="text-muted"><?php esc_html_e( 'Portfolio placeholder — not legal advice. Replace before production launch.', 'msrseminars' ); ?></p>
				</header>
				<?php if ( have_posts() ) : ?>
					<?php
					while ( have_posts() ) {
						the_post();
						if ( get_the_content() !== '' ) {
							?>
							<div class="entry-content">
								<?php the_content(); ?>
							</div>
							<?php
						}
					}
					?>
				<?php endif; ?>
				<div class="awards-privacy-page__demo small text-muted">
					<p><?php esc_html_e( 'MSR Seminars is a demonstration programme site. Registration and delegate flows shown in portfolio review do not store or transmit personal data. Connect a privacy policy and consent flow before a live seminar season.', 'msrseminars' ); ?></p>
					<p class="mb-0">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Seminars home', 'msrseminars' ); ?></a>
						<span aria-hidden="true"> · </span>
						<a href="<?php echo esc_url( home_url( '/agenda/' ) ); ?>"><?php esc_html_e( 'Agenda', 'msrseminars' ); ?></a>
					</p>
				</div>
			</div>
		</div>
	</div>
</main>
<?php
get_footer();
