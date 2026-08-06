<?php

namespace Essential_Addons_Elementor\MegaMenu\Renderers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Editor (Backbone/Underscore) renderer for the Mega Menu widget.
 *
 * Prints the same DOM the PHP renderer produces, minus the submenu panels —
 * those are real Elementor container views which the nested elements editor
 * mounts into `.eael-mega-menu__panels` for us.
 *
 * @since 6.3.0
 */
class Editor_Renderer {

	/**
	 * Print the widget's editor template.
	 */
	public static function content_template() {
		?>
		<#
		const elementUid = view.getIDInt().toString(),
			items = settings.eael_mega_menu_items || [],
			trigger = 'click' === settings.eael_mega_menu_trigger ? 'click' : 'hover',
			animation = settings.eael_mega_menu_animation || 'fade',
			breakpoint = settings.eael_mega_menu_breakpoint || 'tablet',
			hasToggle = 'none' !== breakpoint,
			containerId = 'eael-mega-menu-container-' + elementUid,
			wrapperClasses = [
				'eael-mega-menu',
				'eael-mega-menu--trigger-' + trigger,
				'eael-mega-menu--anim-' + animation
			];

		if ( ! hasToggle ) {
			wrapperClasses.push( 'eael-mega-menu--no-toggle' );
		}

		if ( 'yes' === settings.eael_mega_menu_toggle_full_width ) {
			wrapperClasses.push( 'eael-mega-menu--stretch-dropdown' );
		}

		const renderIcon = function( icon ) {
			if ( ! icon || ! icon.value ) {
				return '';
			}

			const rendered = elementor.helpers.renderIcon( view, icon, { 'aria-hidden': true }, 'i', 'object' );

			return rendered && rendered.value ? rendered.value : '';
		};

		const indicatorIcon = renderIcon( settings.eael_mega_menu_indicator_icon );
		#>
		<nav class="{{ wrapperClasses.join( ' ' ) }}"
			data-widget-number="{{ elementUid }}"
			data-trigger="{{ trigger }}"
			data-breakpoint="{{ breakpoint }}"
			data-touch-mode="false"
			aria-label="<?php echo esc_attr__( 'Mega Menu', 'essential-addons-for-elementor-lite' ); ?>">

			<# if ( hasToggle ) {
				const toggleOpenIcon = renderIcon( settings.eael_mega_menu_toggle_icon ),
					toggleCloseIcon = renderIcon( settings.eael_mega_menu_toggle_close_icon ),
					toggleText = settings.eael_mega_menu_toggle_text || '';
			#>
			<button class="eael-mega-menu__toggle"
				type="button"
				aria-expanded="false"
				aria-controls="{{ containerId }}"
				aria-label="<?php echo esc_attr__( 'Toggle menu', 'essential-addons-for-elementor-lite' ); ?>">
				<# if ( toggleOpenIcon ) { #>
					<span class="eael-mega-menu__toggle-icon eael-mega-menu__toggle-icon--open">{{{ toggleOpenIcon }}}</span>
				<# } #>
				<# if ( toggleCloseIcon ) { #>
					<span class="eael-mega-menu__toggle-icon eael-mega-menu__toggle-icon--close">{{{ toggleCloseIcon }}}</span>
				<# } #>
				<# if ( toggleText ) { #>
					<span class="eael-mega-menu__toggle-text">{{{ toggleText }}}</span>
				<# } #>
			</button>
			<# } #>

			<div class="eael-mega-menu__container" id="{{ containerId }}">
				<ul class="eael-mega-menu__list">
					<# _.each( items, function( item, index ) {
						const position = index + 1,
							hasSubmenu = 'undefined' === typeof item.eael_mega_menu_item_has_submenu
								? true
								: 'yes' === item.eael_mega_menu_item_has_submenu,
							url = item.eael_mega_menu_item_link && item.eael_mega_menu_item_link.url
								? item.eael_mega_menu_item_link.url
								: '',
							hasUrl = '' !== url,
							itemId = item.eael_mega_menu_item_css_id
								? item.eael_mega_menu_item_css_id
								: 'eael-mega-menu-item-' + elementUid + '-' + position,
							panelId = 'eael-mega-menu-panel-' + elementUid + '-' + position,
							itemIcon = renderIcon( item.eael_mega_menu_item_icon ),
							itemClasses = [ 'eael-mega-menu__item' ],
							tag = hasUrl ? 'a' : ( hasSubmenu ? 'button' : 'span' );

						if ( hasSubmenu ) {
							itemClasses.push( 'eael-mega-menu__item--has-submenu' );
						}

						if ( item.eael_mega_menu_item_css_classes ) {
							itemClasses.push( item.eael_mega_menu_item_css_classes );
						}
					#>
					<li class="{{ itemClasses.join( ' ' ) }}" data-item-index="{{ position }}" style="--eael-mm-order: {{ position * 2 }};">
						<{{{ tag }}} class="eael-mega-menu__link"
							id="{{ itemId }}"
							<# if ( hasUrl ) { #>href="{{ url }}"<# } #>
							<# if ( ! hasUrl && hasSubmenu ) { #>type="button" aria-expanded="false" aria-controls="{{ panelId }}"<# } #>>
							<# if ( itemIcon ) { #>
								<span class="eael-mega-menu__item-icon">{{{ itemIcon }}}</span>
							<# } #>
							<span class="eael-mega-menu__item-label">{{{ item.eael_mega_menu_item_label }}}</span>
							<# if ( hasSubmenu && ! hasUrl && indicatorIcon ) { #>
								<span class="eael-mega-menu__item-indicator">{{{ indicatorIcon }}}</span>
							<# } #>
						</{{{ tag }}}>

						<# if ( hasSubmenu && hasUrl ) { #>
							<button class="eael-mega-menu__disclosure"
								type="button"
								aria-expanded="false"
								aria-controls="{{ panelId }}">
								<span class="eael-mega-menu__item-indicator">{{{ indicatorIcon }}}</span>
							</button>
						<# } #>
					</li>
					<# } ); #>
				</ul>
				<div class="eael-mega-menu__panels"></div>
			</div>
		</nav>
		<?php
	}
}
