<?php
/**
 * Mega Menu — mobile toggle button markup.
 *
 * Rendered by \Essential_Addons_Elementor\MegaMenu\Renderers\Frontend_Renderer,
 * which exposes itself as `$this` and the template data as `$args`.
 *
 * @var array $args
 *
 * @since 6.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

$settings     = $args['settings'];
$container_id = $args['container_id'];

$open_icon  = isset( $settings['eael_mega_menu_toggle_icon'] ) ? $settings['eael_mega_menu_toggle_icon'] : [];
$close_icon = isset( $settings['eael_mega_menu_toggle_close_icon'] ) ? $settings['eael_mega_menu_toggle_close_icon'] : [];
$text       = isset( $settings['eael_mega_menu_toggle_text'] ) ? $settings['eael_mega_menu_toggle_text'] : '';
?>
<button class="eael-mega-menu__toggle"
	type="button"
	aria-expanded="false"
	aria-controls="<?php echo esc_attr( $container_id ); ?>"
	aria-label="<?php echo esc_attr__( 'Toggle menu', 'essential-addons-for-elementor-lite' ); ?>">
	<?php if ( ! empty( $open_icon['value'] ) ) : ?>
		<span class="eael-mega-menu__toggle-icon eael-mega-menu__toggle-icon--open">
			<?php \Elementor\Icons_Manager::render_icon( $open_icon, [ 'aria-hidden' => 'true' ] ); ?>
		</span>
	<?php endif; ?>

	<?php if ( ! empty( $close_icon['value'] ) ) : ?>
		<span class="eael-mega-menu__toggle-icon eael-mega-menu__toggle-icon--close">
			<?php \Elementor\Icons_Manager::render_icon( $close_icon, [ 'aria-hidden' => 'true' ] ); ?>
		</span>
	<?php endif; ?>

	<?php if ( '' !== trim( (string) $text ) ) : ?>
		<span class="eael-mega-menu__toggle-text"><?php echo esc_html( $text ); ?></span>
	<?php endif; ?>
</button>
