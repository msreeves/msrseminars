<?php
/**
 * Partner / sponsor archive card.
 *
 * @package msrseminars
 *
 * @var array $args {
 *     @type bool $compact Inline logo chip (home partner strip).
 * }
 */

$compact = ! empty( $args['compact'] );
$link    = msrseminars_get_acf_link_parts( get_field( 'link' ) );
$tier_slug   = msrseminars_get_partner_tier( get_the_ID() );
$tier_labels = msrseminars_get_sponsor_tiers();
$tier_label  = $tier_labels[ $tier_slug ] ?? '';
$media_args = array();
if ( $link['url'] ) {
	$media_args = array(
		'link_url'    => $link['url'],
		'link_target' => $link['target'],
	);
}
if ( ! $link['url'] && ! $compact ) {
	return;
}
?>
<div class="<?php echo esc_attr( $compact ? 'seminars-partner-chip' : 'seminars-partners-grid__item' ); ?>">
	<article <?php post_class( $compact ? 'partner-card partner-card--compact' : 'partner-card msr-reveal msr-reveal--up' ); ?>>
		<div class="partner-card__logo seminars-logo-tile<?php echo $compact ? ' seminars-logo-tile--compact' : ''; ?>">
			<?php
			if ( $link['url'] ) {
				msrseminars_render_card_media( null, 'medium', $media_args );
			} else {
				msrseminars_render_card_media( null, 'medium' );
			}
			?>
		</div>
		<?php if ( ! $compact && $tier_label ) : ?>
			<p class="partner-card__tier small text-center mb-2">
				<span class="partner-card__tier-badge"><?php echo esc_html( $tier_label ); ?></span>
			</p>
		<?php endif; ?>
	</article>
</div>
