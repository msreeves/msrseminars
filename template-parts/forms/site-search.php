<?php
/**
 * Site search form partial.
 *
 * @package msrseminars
 */

$msr_sb = sanitize_html_class( get_stylesheet() . '-site-search' );
?>
<div class="p-5 msr-site-search">
	<form role="search" method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Site search', 'msrseminars' ); ?>">
		<div class="input-group flex-wrap flex-md-nowrap">
			<label class="screen-reader-text" for="<?php echo esc_attr( $msr_sb ); ?>"><?php esc_html_e( 'Search', 'msrseminars' ); ?></label>
			<input class="form-control" type="search" name="s" id="<?php echo esc_attr( $msr_sb ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search…', 'msrseminars' ); ?>" autocomplete="off" />
			<input class="btn btn-success" type="submit" value="<?php esc_attr_e( 'Search', 'msrseminars' ); ?>" />
		</div>
	</form>
</div>
