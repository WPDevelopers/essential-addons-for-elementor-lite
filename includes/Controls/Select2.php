<?php

namespace Essential_Addons_Elementor\Controls;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Base_Data_Control;

class Select2 extends Base_Data_Control
{
    public function get_type()
    {
        return 'eael-select2';
    }

	public function enqueue() {
		wp_register_script( 'eael-select2', EAEL_PLUGIN_URL . 'assets/front-end/js/edit/ea-select2.js',
			[ 'jquery-elementor-select2' ], '1.0.1', true );
		wp_localize_script(
			'eael-select2',
			'eael_select2_localize',
			[
				'ajaxurl'         => esc_url( admin_url( 'admin-ajax.php' ) ),
				'search_text'     => esc_html__( 'Search', 'essential-addons-for-elementor-lite' ),
				'remove'          => __( 'Remove', 'essential-addons-for-elementor-lite' ),
				'thumbnail'       => __( 'Image', 'essential-addons-for-elementor-lite' ),
				'name'            => __( 'Title', 'essential-addons-for-elementor-lite' ),
				'price'           => __( 'Price', 'essential-addons-for-elementor-lite' ),
				'quantity'        => __( 'Quantity', 'essential-addons-for-elementor-lite' ),
				'subtotal'        => __( 'Subtotal', 'essential-addons-for-elementor-lite' ),
				'cl_login_status' => __( 'User Status', 'essential-addons-for-elementor-lite' ),
				'cl_post_type'    => __( 'Post Type', 'essential-addons-for-elementor-lite' ),
				'cl_browser'      => __( 'Browser', 'essential-addons-for-elementor-lite' ),
				'cl_date_time'    => __( 'Date & Time', 'essential-addons-for-elementor-lite' ),
				'cl_dynamic'      => __( 'Dynamic Field', 'essential-addons-for-elementor-lite' ),
			]
		);
		wp_enqueue_script( 'eael-select2' );
	}

    protected function get_default_settings()
    {
        return [
            'multiple' => false,
            'source_name' => 'post_type',
            'source_type' => 'post',
        ];
    }

    public function content_template()
    {
        $control_uid = $this->get_control_uid();
        ?>
        <# var controlUID = '<?php echo esc_html( $control_uid ); ?>'; #>
        <# var currentID = elementor.panel.currentView.currentPageView.model.attributes.settings.attributes[data.name]; #>
        <div class="elementor-control-field">
            <# if ( data.label ) { #>
            <label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{data.label }}}</label>
            <# } #>
            <div class="elementor-control-input-wrapper elementor-control-unit-5">
                <# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
                <select id="<?php echo esc_attr( $control_uid ); ?>" {{ multiple }} class="ea-select2" data-setting="{{ data.name }}"></select>
            </div>
        </div>
        <#
        ( function( $ ) {
        $( document.body ).trigger( 'eael_select2_init',{currentID:data.controlValue,data:data,controlUID:controlUID,multiple:data.multiple} );
        }( jQuery ) );
        #>
        <?php
    }
}
