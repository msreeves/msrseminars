<?php
/**
 * Main sponsor logo bar (home hero).
 *
 * @package msrseminars
 *
 * @var array $args { sponsors: array }
 */

$sponsors = isset( $args['sponsors'] ) && is_array( $args['sponsors'] ) ? $args['sponsors'] : array();
if ( ! $sponsors ) {
	return;
}
?>
<div class="seminars-main-sponsor">
	<div class="container">
		<p class="seminars-main-sponsor__label" id="seminars-main-sponsor-heading"><?php esc_html_e( 'Main sponsor', 'msrseminars' ); ?></p>
		<div class="seminars-main-sponsor__logos">
			<?php foreach ( $sponsors as $sponsor ) : ?>
				<?php
				if ( ! $sponsor instanceof WP_Post ) {
					continue;
				}
				$sponsor_link = get_field( 'link', $sponsor );
				$sponsor_url  = '';
				if ( is_array( $sponsor_link ) && ! empty( $sponsor_link['url'] ) ) {
					$sponsor_url = (string) $sponsor_link['url'];
				} elseif ( is_string( $sponsor_link ) ) {
					$sponsor_url = $sponsor_link;
				}
				$thumb_id = (int) get_post_thumbnail_id( $sponsor );
				$img_url  = $thumb_id ? (string) wp_get_attachment_image_url( $thumb_id, 'medium' ) : '';
				$alt      = $thumb_id ? (string) get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ) : '';
				if ( '' === $alt ) {
					$alt = get_the_title( $sponsor );
				}
				if ( '' === $img_url ) {
					continue;
				}
				?>
				<?php if ( $sponsor_url ) : ?>
				<a class="seminars-main-sponsor__logo seminars-logo-tile" href="<?php echo esc_url( $sponsor_url ); ?>" target="_blank" rel="noopener noreferrer">
					<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $alt ); ?>" loading="lazy" decoding="async" />
				</a>
				<?php else : ?>
				<span class="seminars-main-sponsor__logo seminars-logo-tile">
					<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $alt ); ?>" loading="lazy" decoding="async" />
				</span>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
</div>
