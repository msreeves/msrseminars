/**
 * Run callback when DOM is ready (safe for dynamic import after DOMContentLoaded).
 *
 * @param {() => void} fn
 */
export function msrseminarsDomReady( fn ) {
	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', fn );
		return;
	}
	fn();
}
