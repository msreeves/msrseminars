/**
 * Theme JS + CSS entry — Vite → dist/app.js / dist/app.css
 * Vanilla modules only (no jQuery). Bootstrap + Fancybox bundled (Phase 19).
 */
import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

import '../scss/app.scss';
import './filter-tabs.js';
import './agenda-filters.js';
import './mobile-nav.js';
import './scroll-reveal.js';

function msrseminarsLoadDeferredModules() {
	if ( document.querySelector( '[data-fancybox="gallery"]' ) ) {
		import( './fancybox-init.js' );
	}
	if ( document.querySelector( '[data-loadmore-max-pages], .btn-load-more' ) ) {
		import( './ajax.js' );
	}
	if ( document.querySelector( '.count' ) ) {
		import( './scroll-counter.js' );
	}
}

if ( 'requestIdleCallback' in window ) {
	requestIdleCallback( msrseminarsLoadDeferredModules, { timeout: 2500 } );
} else {
	document.addEventListener( 'DOMContentLoaded', msrseminarsLoadDeferredModules );
}
