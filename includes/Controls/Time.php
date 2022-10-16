<?php

namespace Essential_Addons_Elementor\Controls;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Base_Data_Control;

class Time extends Base_Data_Control {

	public function get_type() {
		return 'eael-time';
	}

	protected function get_default_settings() {
		return [
			'label_block'  => false,
			'time_options' => [],
		];
	}

	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
        <div class="elementor-control-field">
            <label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <input id="<?php echo $control_uid; ?>" placeholder="{{ data.placeholder }}"
                       class="elementor-time-picker" type="time" data-setting="{{ data.name }}">
            </div>
        </div>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{data.description }}}</div>
        <# } #>
		<?php
	}
}

