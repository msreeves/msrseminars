<?php
/**
 * Template Name: Gallery template
 *
 * @package msrseminars
 */

get_header();
?>
<section>
	<div class="container">
		<div class="panel">
			<?php the_title( '<h1>', '</h1>' ); ?>
			<?php the_content(); ?>
		</div>
		<?php
		$gallery = get_field( 'gallery' );
		if ( is_array( $gallery ) && $gallery ) :
			?>
			<div class="row">
				<div class="d-flex flex-wrap">
					<?php foreach ( $gallery as $img ) : ?>
						<?php
						if ( ! is_array( $img ) ) {
							continue;
						}
						$url       = ! empty( $img['url'] ) ? (string) $img['url'] : '';
						$thumb     = ! empty( $img['sizes']['thumbnail'] ) ? (string) $img['sizes']['thumbnail'] : $url;
						$title     = ! empty( $img['title'] ) ? (string) $img['title'] : '';
						$alt       = ! empty( $img['alt'] ) ? (string) $img['alt'] : $title;
						if ( '' === $thumb ) {
							continue;
						}
						?>
						<div class="p-3">
							<a href="<?php echo esc_url( $url ); ?>" data-lightbox="gallery" data-title="<?php echo esc_attr( $title ); ?>">
								<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $alt ); ?>" loading="lazy" decoding="async" />
							</a>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
