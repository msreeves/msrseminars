<?php
/**
 * ACF: Flexible Content > Layouts > Listing Partners
 *
 * @package msrseminars
 */

$heading      = isset( $args['title'] ) ? (string) $args['title'] : '';
$introduction = isset( $args['introduction'] ) ? (string) $args['introduction'] : '';
?>

<section class="partner msrseminars-partner-list seminars-partners-section">
	<div class="container">
		<?php if ( $heading || $introduction ) : ?>
		<header class="seminars-partners-intro seminars-partners-intro--section msr-reveal">
			<?php if ( $heading ) : ?>
			<h2><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>
			<?php if ( $introduction ) : ?>
			<p class="lead"><?php echo wp_kses_post( $introduction ); ?></p>
			<?php endif; ?>
		</header>
		<?php endif; ?>
		<?php msrseminars_render_partner_tier_grid(); ?>
	</div>
</section>
