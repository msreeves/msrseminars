<?php
/**
 * Awards programme footer — escaped markup, menu-first with explore fallback.
 *
 * @package msrseminars
 */

/**
 * Social icons from the `social` menu location.
 *
 * @return void
 */
function msrseminars_render_footer_social_menu() {
	if ( ! has_nav_menu( 'social' ) ) {
		return;
	}

	$locations = get_nav_menu_locations();
	$menu_id   = isset( $locations['social'] ) ? (int) $locations['social'] : 0;
	if ( $menu_id <= 0 ) {
		return;
	}

	$menu = wp_get_nav_menu_object( $menu_id );
	if ( ! $menu ) {
		return;
	}

	$items = wp_get_nav_menu_items( $menu_id );
	if ( ! $items ) {
		return;
	}
	?>
	<div class="seminars-site-footer__social">
		<h2 class="seminars-site-footer__heading h6 text-uppercase"><?php echo esc_html( $menu->name ); ?></h2>
		<div class="seminars-site-footer__social-icons d-flex flex-wrap gap-3">
			<?php foreach ( $items as $item ) : ?>
				<?php if ( empty( $item->url ) ) { continue; } ?>
				<a
					class="seminars-site-footer__social-link"
					href="<?php echo esc_url( $item->url ); ?>"
					target="_blank"
					rel="noopener noreferrer"
					aria-label="<?php echo esc_attr( $item->title ); ?>"
				>
					<i class="fa-brands fa-<?php echo esc_attr( sanitize_title( $item->title ) ); ?> fa-2xl" aria-hidden="true"></i>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
}

/**
 * Explore column — footer menu or seeded fallback links + search.
 *
 * @return void
 */
function msrseminars_render_footer_explore_nav() {
	$search = home_url( '/?s=' );
	?>
	<div class="seminars-site-footer__explore">
		<h2 class="seminars-site-footer__heading h6 text-uppercase"><?php esc_html_e( 'Explore', 'msrseminars' ); ?></h2>
		<ul class="seminars-site-footer__links list-unstyled mb-0">
			<?php if ( has_nav_menu( 'footer' ) ) : ?>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'items_wrap'     => '%3$s',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
				?>
			<?php else : ?>
				<?php foreach ( msrseminars_get_footer_explore_links() as $link ) : ?>
					<li>
						<a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['title'] ); ?></a>
					</li>
				<?php endforeach; ?>
			<?php endif; ?>
			<li>
				<a href="<?php echo esc_url( $search ); ?>"><?php esc_html_e( 'Search', 'msrseminars' ); ?></a>
			</li>
		</ul>
	</div>
	<?php
}

/**
 * Full awards footer shell.
 *
 * @return void
 */
function msrseminars_render_site_footer() {
	$brand   = get_bloginfo( 'name' );
	$tagline = get_bloginfo( 'description' );
	?>
	<footer id="colophon" class="site-footer seminars-site-footer">
		<div class="container py-4">
			<div class="row g-4 align-items-start">
				<div class="col-lg-4">
					<p class="seminars-site-footer__brand mb-1"><?php echo esc_html( $brand ); ?></p>
					<?php if ( '' !== trim( $tagline ) ) : ?>
						<p class="seminars-site-footer__tagline small mb-0"><?php echo esc_html( $tagline ); ?></p>
					<?php endif; ?>
					<?php if ( msrseminars_show_footer_demo_note() ) : ?>
						<p class="seminars-site-footer__demo small text-muted mt-2 mb-0">
							<?php echo esc_html( msrseminars_get_footer_demo_note() ); ?>
						</p>
					<?php endif; ?>
				</div>
				<div class="col-lg-4">
					<?php msrseminars_render_footer_explore_nav(); ?>
				</div>
				<div class="col-lg-4">
					<?php msrseminars_render_footer_social_menu(); ?>
				</div>
			</div>
		</div>
	</footer>
	<?php
}
