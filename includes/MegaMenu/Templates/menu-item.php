<?php
/**
 * Mega Menu — single menu item markup.
 *
 * Rendered by \Essential_Addons_Elementor\MegaMenu\Renderers\Frontend_Renderer,
 * which exposes itself as `$this` and the template data as `$args`.
 *
 * Accessibility follows the WAI-ARIA "Disclosure Navigation with Top Level
 * Links" pattern: an item that has both a link and a submenu keeps the link
 * navigable and gets a dedicated disclosure button, while an item without a
 * link becomes the disclosure control itself.
 *
 * @var array $args
 *
 * @since 6.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

$prepared  = $args['prepared'];
$settings  = $args['settings'];
$renderer  = $args['renderer'];
$indicator = isset( $settings['eael_mega_menu_indicator_icon'] ) ? $settings['eael_mega_menu_indicator_icon'] : [];

if ( $prepared['has_url'] ) {
	$tag = 'a';
} elseif ( $prepared['has_submenu'] ) {
	$tag = 'button';
} else {
	$tag = 'span';
}

$show_inline_indicator = $prepared['has_submenu'] && ! $prepared['has_url'];
$show_disclosure       = $prepared['has_submenu'] && $prepared['has_url'];
?>
<li class="<?php echo esc_attr( $renderer->get_item_classes( $prepared ) ); ?>"
	data-item-index="<?php echo esc_attr( $prepared['position'] ); ?>"
	style="--eael-mm-order: <?php echo (int) $prepared['position'] * 2; ?>;">
	<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- attributes escaped by Elementor's render attribute API. ?>
	<<?php echo esc_html( $tag ); ?> <?php echo $renderer->get_link_attributes( $prepared ); ?>>
		<?php if ( ! empty( $prepared['icon']['value'] ) ) : ?>
			<span class="eael-mega-menu__item-icon"><?php $renderer->render_icon( $prepared['icon'] ); ?></span>
		<?php endif; ?>

		<span class="eael-mega-menu__item-label"><?php echo wp_kses_post( $prepared['label'] ); ?></span>

		<?php if ( $show_inline_indicator && ! empty( $indicator['value'] ) ) : ?>
			<span class="eael-mega-menu__item-indicator"><?php $renderer->render_icon( $indicator ); ?></span>
		<?php endif; ?>
	</<?php echo esc_html( $tag ); ?>>

	<?php if ( $show_disclosure ) : ?>
		<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- attributes escaped by Elementor's render attribute API. ?>
		<button <?php echo $renderer->get_disclosure_attributes( $prepared ); ?>>
			<span class="eael-mega-menu__item-indicator">
				<?php $renderer->render_icon( $indicator ); ?>
			</span>
		</button>
	<?php endif; ?>
</li>
