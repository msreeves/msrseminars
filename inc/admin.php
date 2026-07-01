<?php
/**
 * Admin-first helpers — pages, menus, and taxonomy resolution (not hardcoded IDs in templates).
 *
 * @package msrseminars
 */

/**
 * Published page permalink by slug, with optional path fallback.
 *
 * @param string $slug     Page post_name.
 * @param string $fallback Relative path if page missing.
 * @return string
 */
function msrseminars_get_page_url( $slug, $fallback = '' ) {
	$page = get_page_by_path( $slug, OBJECT, 'page' );
	if ( $page instanceof WP_Post && 'publish' === $page->post_status ) {
		return (string) get_permalink( $page );
	}
	if ( '' !== $fallback ) {
		return home_url( $fallback );
	}
	return '';
}

/**
 * Agenda track permalink by post slug.
 *
 * @param string $slug Agenda post_name e.g. training.
 * @return string
 */
function msrseminars_get_agenda_track_url( $slug ) {
	$post = get_page_by_path( sanitize_title( (string) $slug ), OBJECT, 'agenda' );
	if ( $post instanceof WP_Post && 'publish' === $post->post_status ) {
		return (string) get_permalink( $post );
	}
	return '';
}

/**
 * Programme home page ID from Reading settings or known slugs (not a hardcoded post ID).
 *
 * @return int
 */
function msrseminars_get_programme_home_page_id() {
	$front_id = (int) get_option( 'page_on_front' );
	if ( $front_id > 0 ) {
		return $front_id;
	}

	foreach ( array( 'home' ) as $slug ) {
		$page = get_page_by_path( $slug, OBJECT, 'page' );
		if ( $page instanceof WP_Post && 'publish' === $page->post_status ) {
			return (int) $page->ID;
		}
	}

	return 0;
}

/**
 * Whether the current request is the seminars programme home (hero + flexible sections).
 *
 * @return bool
 */
function msrseminars_is_programme_home() {
	if ( is_front_page() ) {
		return true;
	}

	$home_id = msrseminars_get_programme_home_page_id();
	if ( $home_id > 0 && is_page( $home_id ) ) {
		return true;
	}

	return (bool) apply_filters( 'msrseminars_is_programme_home', false );
}

/**
 * Sponsored category slug (admin-managed term).
 *
 * @return string
 */
function msrseminars_sponsored_category_slug() {
	return (string) apply_filters( 'msrseminars_sponsored_category_slug', 'sponsored-content' );
}

/**
 * Category IDs to hide from public term lists and treat as sponsored ribbons.
 *
 * @return int[]
 */
function msrseminars_get_excluded_sponsored_category_ids() {
	$ids = array();

	$term = get_term_by( 'slug', msrseminars_sponsored_category_slug(), 'category' );
	if ( $term instanceof WP_Term ) {
		$ids[] = (int) $term->term_id;
	}

	$legacy = (int) apply_filters( 'msrseminars_sponsored_category_id', 6 );
	if ( $legacy > 0 && ! in_array( $legacy, $ids, true ) ) {
		$ids[] = $legacy;
	}

	return array_values( array_unique( array_filter( $ids ) ) );
}

/**
 * Whether a post is in the sponsored category.
 *
 * @param int|null $post_id Post ID (default current post).
 * @return bool
 */
function msrseminars_is_sponsored_post( $post_id = null ) {
	$post_id = null === $post_id ? get_the_ID() : (int) $post_id;
	if ( $post_id <= 0 ) {
		return false;
	}
	foreach ( msrseminars_get_excluded_sponsored_category_ids() as $term_id ) {
		if ( has_category( $term_id, $post_id ) ) {
			return true;
		}
	}
	return false;
}

/**
 * Exclude sponsored category terms on the front end (slug-based + optional filter ID).
 *
 * @param WP_Term[]|false $terms    Terms.
 * @param int             $post_id  Post ID.
 * @param string          $taxonomy Taxonomy.
 * @return WP_Term[]|false
 */
function msrseminars_filter_sponsored_category_terms( $terms, $post_id, $taxonomy ) {
	unset( $post_id );
	if ( is_admin() || ! is_array( $terms ) || 'category' !== $taxonomy ) {
		return $terms;
	}

	$exclude = msrseminars_get_excluded_sponsored_category_ids();
	if ( ! $exclude ) {
		return $terms;
	}

	foreach ( $terms as $key => $term ) {
		if ( $term instanceof WP_Term && in_array( (int) $term->term_id, $exclude, true ) ) {
			unset( $terms[ $key ] );
		}
	}

	return $terms;
}
add_filter( 'get_the_terms', 'msrseminars_filter_sponsored_category_terms', 100, 3 );

/**
 * Nav links from a registered theme location (Appearance → Menus).
 *
 * @param string $location Theme location slug.
 * @return array<int, array{title: string, url: string}>
 */
function msrseminars_get_nav_links_from_location( $location ) {
	$locations = get_nav_menu_locations();
	if ( empty( $locations[ $location ] ) ) {
		return array();
	}

	$items = wp_get_nav_menu_items( (int) $locations[ $location ] );
	if ( ! $items ) {
		return array();
	}

	$links = array();
	foreach ( $items as $item ) {
		if ( empty( $item->url ) || 'publish' !== $item->post_status ) {
			continue;
		}
		$links[] = array(
			'title' => $item->title,
			'url'   => $item->url,
		);
	}

	return $links;
}

/**
 * Agenda track nav items (published agenda CPT posts).
 *
 * @return array<int, array{title: string, url: string}>
 */
function msrseminars_get_agenda_track_nav_items() {
	$slugs = array( 'training', 'academic', 'leadership', 'technology', 'social' );
	$items = array();

	foreach ( $slugs as $slug ) {
		$url = msrseminars_get_agenda_track_url( $slug );
		if ( '' === $url ) {
			continue;
		}
		$post  = get_page_by_path( $slug, OBJECT, 'agenda' );
		$title = ( $post instanceof WP_Post ) ? $post->post_title : ucfirst( $slug );
		$items[] = array(
			'title' => $title,
			'url'   => $url,
		);
	}

	return $items;
}

/**
 * Top-level topic category nav items (excludes sponsored).
 *
 * @return array<int, array{title: string, url: string}>
 */
function msrseminars_get_topic_category_nav_items() {
	$exclude = msrseminars_get_excluded_sponsored_category_ids();
	$terms   = get_terms(
		array(
			'taxonomy'   => 'category',
			'parent'     => 0,
			'hide_empty' => false,
			'exclude'    => $exclude,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);

	if ( is_wp_error( $terms ) || ! $terms ) {
		return array();
	}

	$items = array();
	foreach ( $terms as $term ) {
		if ( ! $term instanceof WP_Term ) {
			continue;
		}
		$items[] = array(
			'title' => $term->name,
			'url'   => (string) get_category_link( $term->term_id ),
		);
	}

	return $items;
}

/**
 * Primary nav tree when no WP menu is assigned (matches primary-nav seed IA).
 *
 * @return array<int, array{title: string, url: string, children?: array<int, array{title: string, url: string}>}>
 */
function msrseminars_get_primary_nav_tree() {
	$agenda_url = msrseminars_get_page_url( 'agenda', '/agenda/' );
	$topics_url = msrseminars_get_page_url( 'topics', '/topics/' );

	$tree = array(
		array(
			'title' => __( 'Home', 'msrseminars' ),
			'url'   => home_url( '/' ),
		),
	);

	if ( '' !== $agenda_url ) {
		$tree[] = array(
			'title'    => __( 'Agenda', 'msrseminars' ),
			'url'      => $agenda_url,
			'children' => msrseminars_get_agenda_track_nav_items(),
		);
	}

	$tree[] = array(
		'title' => __( 'Panelists', 'msrseminars' ),
		'url'   => msrseminars_get_page_url( 'panelists', '/panelists/' ),
	);
	$tree[] = array(
		'title' => __( 'For delegates', 'msrseminars' ),
		'url'   => msrseminars_get_page_url( 'for-delegates', '/for-delegates/' ),
	);

	if ( '' !== $topics_url ) {
		$tree[] = array(
			'title'    => __( 'Topics', 'msrseminars' ),
			'url'      => $topics_url,
			'children' => msrseminars_get_topic_category_nav_items(),
		);
	}

	$tree[] = array(
		'title' => __( 'Partners', 'msrseminars' ),
		'url'   => msrseminars_get_page_url( 'partners', '/partners/' ),
	);
	$tree[] = array(
		'title' => __( 'About', 'msrseminars' ),
		'url'   => msrseminars_get_page_url( 'about-us', '/about-us/' ),
	);

	return array_values(
		array_filter(
			$tree,
			static function ( $item ) {
				return ! empty( $item['url'] );
			}
		)
	);
}

/**
 * Fallback primary IA when no menu is assigned to menu-1.
 *
 * @return array<int, array{title: string, url: string}>
 */
function msrseminars_get_primary_nav_fallback_links() {
	$links = array();
	foreach ( msrseminars_get_primary_nav_tree() as $item ) {
		if ( empty( $item['url'] ) ) {
			continue;
		}
		$links[] = array(
			'title' => $item['title'],
			'url'   => $item['url'],
		);
		if ( ! empty( $item['children'] ) ) {
			foreach ( $item['children'] as $child ) {
				if ( empty( $child['url'] ) ) {
					continue;
				}
				$links[] = $child;
			}
		}
	}

	return $links;
}

/**
 * Render one fallback nav item (walker-compatible markup).
 *
 * @param array{title: string, url: string, children?: array<int, array{title: string, url: string}>} $item Nav item.
 * @param int                                                                                           $depth Depth.
 * @return void
 */
function msrseminars_render_primary_nav_fallback_item( $item, $depth = 0 ) {
	if ( empty( $item['url'] ) ) {
		return;
	}

	$children     = ! empty( $item['children'] ) ? $item['children'] : array();
	$has_children = $children && 0 === $depth;
	$item_class   = 0 === $depth ? 'seminars-nav__item' : 'seminars-nav__subitem';
	$link_class   = 0 === $depth ? 'seminars-nav__link' : 'seminars-nav__sublink';

	if ( $has_children ) {
		$item_class .= ' has-sub menu-item-has-children seminars-nav__item--has-children';
	}

	echo '<li class="' . esc_attr( $item_class ) . '">';

	$link_attrs = ' href="' . esc_url( $item['url'] ) . '"';
	if ( $has_children ) {
		$link_attrs .= ' aria-haspopup="true" aria-expanded="false"';
	}

	if ( $has_children ) {
		echo '<div class="seminars-nav__row">';
	}

	printf(
		'<a class="%s"%s><span>%s',
		esc_attr( $link_class ),
		$link_attrs, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		esc_html( $item['title'] )
	);
	if ( $has_children ) {
		echo '<i class="fa-solid fa-chevron-down seminars-nav__chevron" aria-hidden="true"></i>';
	}
	echo '</span></a>';

	if ( $has_children ) {
		printf(
			'<button type="button" class="seminars-nav__toggle" aria-expanded="false" aria-label="%s"><i class="fa-solid fa-chevron-down seminars-nav__chevron" aria-hidden="true"></i></button></div>',
			esc_attr(
				sprintf(
					/* translators: %s: parent menu item title */
					__( 'Show submenu for %s', 'msrseminars' ),
					$item['title']
				)
			)
		);
		echo '<ul class="seminars-nav__submenu">';
		foreach ( $children as $child ) {
			msrseminars_render_primary_nav_fallback_item( $child, $depth + 1 );
		}
		echo '</ul>';
	}

	echo '</li>';
}

/**
 * Fallback primary menu when no menu is assigned to menu-1.
 *
 * @return void
 */
function msrseminars_primary_menu_fallback() {
	echo '<div id="cssmenu" class="seminars-nav"><ul class="seminars-nav__list">';
	foreach ( msrseminars_get_primary_nav_tree() as $item ) {
		msrseminars_render_primary_nav_fallback_item( $item );
	}
	echo '</ul></div>';
}

/**
 * Whether legacy leaderboard advert carousels should render.
 *
 * Default off unless header adverts exist in admin (enable via filter when needed).
 *
 * @return bool
 */
function msrseminars_show_leaderboard_ads() {
	if ( is_admin() ) {
		return false;
	}

	$default = false;

	$header_ads = get_posts(
		array(
			'post_type'      => 'advert',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'tax_query'      => array(
				array(
					'taxonomy' => 'location',
					'field'    => 'slug',
					'terms'    => 'header',
				),
			),
		)
	);

	if ( ! empty( $header_ads ) ) {
		$default = true;
	}

	/**
	 * Filter leaderboard advert partials on seminars routes.
	 *
	 * @param bool $show Whether to show header/footer advert carousels.
	 */
	return (bool) apply_filters( 'msrseminars_show_leaderboard_ads', $default );
}

/**
 * Footer explore fallback when no footer menu is assigned.
 *
 * @return array<int, array{title: string, url: string}>
 */
function msrseminars_get_footer_explore_links() {
	foreach ( array( 'footer', 'menu-1' ) as $location ) {
		$links = msrseminars_get_nav_links_from_location( $location );
		if ( ! empty( $links ) ) {
			return $links;
		}
	}

	$links = array(
		array(
			'title' => __( 'Home', 'msrseminars' ),
			'url'   => home_url( '/' ),
		),
		array(
			'title' => __( 'Agenda', 'msrseminars' ),
			'url'   => msrseminars_get_page_url( 'agenda', '/agenda/' ),
		),
		array(
			'title' => __( 'Panelists', 'msrseminars' ),
			'url'   => msrseminars_get_page_url( 'panelists', '/panelists/' ),
		),
		array(
			'title' => __( 'Topics', 'msrseminars' ),
			'url'   => msrseminars_get_page_url( 'topics', '/topics/' ),
		),
		array(
			'title' => __( 'For delegates', 'msrseminars' ),
			'url'   => msrseminars_get_page_url( 'for-delegates', '/for-delegates/' ),
		),
		array(
			'title' => __( 'About', 'msrseminars' ),
			'url'   => msrseminars_get_page_url( 'about-us', '/about-us/' ),
		),
		array(
			'title' => __( 'Privacy', 'msrseminars' ),
			'url'   => msrseminars_get_page_url( 'privacy', '/privacy/' ),
		),
	);

	return array_values(
		array_filter(
			$links,
			static function ( $link ) {
				return ! empty( $link['url'] );
			}
		)
	);
}
