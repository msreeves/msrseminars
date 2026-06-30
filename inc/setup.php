<?php
/**
 * Theme setup — body class and programme shell hooks.
 *
 * @package msrseminars
 */

/**
 * Programme body class for scoped SCSS.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function msrseminars_body_classes( $classes ) {
	$classes[] = 'msr-seminars';
	$classes[] = 'msr-assets-self-hosted';
	return $classes;
}
add_filter( 'body_class', 'msrseminars_body_classes' );

/**
 * Core theme supports (document title, HTML5).
 */
function msrseminars_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
}
add_action( 'after_setup_theme', 'msrseminars_theme_setup' );
