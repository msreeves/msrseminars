<?php
/**
 * Agenda single content.
 *
 * @package msrseminars
 */

?>

<article <?php post_class(); ?> id="post-agenda<?php the_ID(); ?>">
<div class="container">
	<div class="row g-0">
		<div class="col-sm-12">
			<section class="post-inner">
		<div class="entry-content">
			<?php the_title( '<h1>', '</h1>' ); ?>
			<?php
			$information = get_field( 'information' );
			if ( $information ) {
				echo '<div class="msr-rich-text">';
				msrseminars_render_rich_text( $information );
				echo '</div>';
			}
			?>
				<?php get_template_part( 'templates/partials/featured-image' ); ?>
				<section aria-labelledby="seminars-agenda-sponsors-heading">
					<?php
					$featured_posts = get_field( 'sponsors' );
					if ( $featured_posts ) :
						?>
    <h2 id="seminars-agenda-sponsors-heading" class="h5"><?php esc_html_e( 'Track sponsors', 'msrseminars' ); ?></h2>
     <div class="sponsor">
        <?php
		foreach ( $featured_posts as $featured_post ) :
			$sponsor_name = get_the_title( $featured_post );
			$sponsor_link = get_field( 'link', $featured_post );
			$sponsor_url  = '';
			if ( is_array( $sponsor_link ) && ! empty( $sponsor_link['url'] ) ) {
				$sponsor_url = (string) $sponsor_link['url'];
			} elseif ( is_string( $sponsor_link ) ) {
				$sponsor_url = $sponsor_link;
			}
			if ( '' === $sponsor_url ) {
				continue;
			}
			$link_label = ( is_array( $sponsor_link ) && ! empty( $sponsor_link['title'] ) )
				? (string) $sponsor_link['title']
				: $sponsor_name;
			$thumb_id   = (int) get_post_thumbnail_id( $featured_post );
			$image      = $thumb_id ? wp_get_attachment_image_src( $thumb_id, 'single-post-thumbnail' ) : false;
			$img_url    = ( is_array( $image ) && ! empty( $image[0] ) ) ? (string) $image[0] : '';
			?>
    <a href="<?php echo esc_url( $sponsor_url ); ?>" aria-label="<?php echo esc_attr( $link_label ); ?>">
        <?php if ( $img_url ) : ?>
        <img src="<?php echo esc_url( $img_url ); ?>" alt="" loading="lazy" decoding="async" />
        <?php else : ?>
        <span class="screen-reader-text"><?php echo esc_html( $link_label ); ?></span>
        <?php endif; ?>
    </a>
            <hr>
    <?php endforeach; ?>
        </div>
    <?php endif; ?>
		</section>
		</div><!-- .entry-content -->

	</section><!-- .post-inner -->
	<section class="seminars-agenda-schedule" aria-labelledby="seminars-agenda-schedule-heading">
		<h2 id="seminars-agenda-schedule-heading" class="h5 visually-hidden"><?php esc_html_e( 'Schedule', 'msrseminars' ); ?></h2>
	 <?php
	 set_query_var( 'msr_agenda_schedule_rows', msrseminars_get_sorted_schedule_rows( get_the_ID() ) );
	 get_template_part( 'template-parts/agenda/schedule' );
	 set_query_var( 'msr_agenda_schedule_rows', null );
	 ?>
	</section>
	</div>
		</div>
	<div class="section-inner">
		<?php
		wp_link_pages(
			array(
				'before'      => '<nav class="post-nav-links bg-light-background" aria-label="' . esc_attr__( 'Page', 'msrseminars' ) . '"><span class="label">' . __( 'Pages:', 'msrseminars' ) . '</span>',
				'after'       => '</nav>',
				'link_before' => '<span class="page-number">',
				'link_after'  => '</span>',
			)
		);

		edit_post_link();
		?>

	</div><!-- .section-inner -->

	<?php

	if ( ( is_single() || is_page() ) && ( comments_open() || get_comments_number() ) && ! post_password_required() ) {
		?>

		<div class="comments-wrapper section-inner">

			<?php comments_template(); ?>

		</div><!-- .comments-wrapper -->

		<?php
	}
	?>
</article><!-- .post -->
