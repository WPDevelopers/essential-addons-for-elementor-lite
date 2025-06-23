<?php
namespace Essential_Addons_Elementor\Controls;
use Elementor\Control_Choose;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor choose control.
 *
 * A base control for creating choose control. Displays radio buttons styled as
 * groups of buttons with icons for each option.
 *
 * @since 5.6.0
 */
class EAEL_Choose extends Control_Choose {

	/**
	 * Render choose control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 5.6.0
	 * @access public
	 */
	public function content_template() {
		$control_uid_input_type = '{{value}}';
		?>
		<div class="elementor-control-field">
			<label class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<div class="elementor-choices{{ data.image_choose ? ' eael-image-choices' : ' eael-choices' }}{{ data.multiline ? ' eael-multiline' : '' }}">
					<# _.each( data.options, function( options, value ) { #>
					<input id="<?php $this->print_control_uid( $control_uid_input_type ); ?>" type="radio" name="elementor-choose-{{ data.name }}-{{ data._cid }}" value="{{ value }}">
					<label class="elementor-choices-label elementor-control-unit-1 {{ !options.text ? ' tooltip-target' : '' }}" for="<?php $this->print_control_uid( $control_uid_input_type ); ?>" data-tooltip="{{ options.title }}" title="{{ options.title }}">
						<# if( options.image ){ #>
							<img class="eael-image-option" src="{{ options.image }}" alt="{{ options.title }}" />
						<# } else if( options.text ){ #>
                            <span class="eael-text-option">{{{ options.text }}}</span>
						<# } else{ #>
							<i class="{{ options.icon }}" aria-hidden="true"></i>
						<# } #>
						<span class="elementor-screen-only">{{{ options.title }}}</span>
					</label>
					<# } ); #>
				</div>
			</div>
		</div>

		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}

	/**
	 * Get choose control default settings.
	 *
	 * Retrieve the default settings of the choose control. Used to return the
	 * default settings while initializing the choose control.
	 *
	 * @since 5.6.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'options' => [],
			'toggle' => true,
		];
	}
}