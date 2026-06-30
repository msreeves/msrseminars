<?php
/**
 * ACF: Flexible Content > Layouts > Call to Action
 *
 * @package msrseminars
 */

$heading      = isset( $args['title'] ) ? (string) $args['title'] : '';
$image_id     = msrseminars_acf_attachment_id( $args['image'] ?? '' );
$introduction = isset( $args['introduction'] ) ? (string) $args['introduction'] : '';

if ( ! $image_id && function_exists( 'get_field' ) ) {
	$image_id = msrseminars_acf_attachment_id( get_field( 'image', 'option' ) );
}

$image     = $image_id ? (string) wp_get_attachment_image_url( $image_id, 'large' ) : msrseminars_acf_image_url( $args['image'] ?? '' );
$has_image = '' !== $image;

$section_class = 'cta' . ( $has_image ? '' : ' cta--no-media' );
?>

<section class="<?php echo esc_attr( $section_class ); ?>">
	<div class="container">
		<div class="panel seminars-cta-panel">
			<div class="row align-items-center g-4">
				<?php if ( $has_image ) : ?>
				<div class="col-lg-6">
					<div class="seminars-cta-panel__media">
						<?php if ( $image_id ) : ?>
							<?php
							echo wp_get_attachment_image(
								$image_id,
								'large',
								false,
								array(
									'class'         => 'seminars-cta-panel__img',
									'alt'           => '',
									'loading'       => 'lazy',
									'decoding'      => 'async',
									'fetchpriority' => 'low',
								)
							);
							?>
						<?php else : ?>
						<img
							class="seminars-cta-panel__img"
							src="<?php echo esc_url( $image ); ?>"
							alt=""
							loading="lazy"
							decoding="async"
							fetchpriority="low"
						/>
						<?php endif; ?>
					</div>
				</div>
				<?php endif; ?>
				<div class="<?php echo esc_attr( $has_image ? 'col-lg-6' : 'col-lg-10 mx-auto' ); ?>">
					<div class="seminars-cta-panel__body text-center">
						<?php if ( $heading ) : ?>
						<h2><?php echo esc_html( $heading ); ?></h2>
						<?php endif; ?>
						<?php if ( $introduction ) : ?>
						<div class="msr-rich-text"><?php msrseminars_render_rich_text( $introduction ); ?></div>
						<?php endif; ?>
						<div class="seminars-ctas ctas">
							<?php msrseminars_render_cta_link( $args['link1'] ?? null ); ?>
							<?php msrseminars_render_cta_link( $args['link2'] ?? null, 'btn btn-outline-primary' ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
