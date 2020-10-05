<?php
namespace Essential_Addons_Elementor\Elements\Controls;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}
use \Elementor\Base_Data_Control;

class EA_Select2 extends Base_Data_Control {

    public function get_type() {
        return 'ea_select2';
    }

    public function enqueue() {
        wp_register_script( 'ea-select2', EAEL_PLUGIN_URL.'assets/front-end/js/edit/ea-select2.js', [ 'jquery-elementor-select2' ], '1.0.0',true );
        wp_localize_script(
            'ea-select2',
            'ea_select2_localize',
            [
                'ajaxurl' => admin_url( 'admin-ajax.php' )
            ]
        );
        wp_enqueue_script( 'ea-select2' );
    }

    protected function get_default_settings() {
        return [
            'multiple' => false,
            'source_type' => 'post',
        ];
    }

    public function content_template() {
        $control_uid = $this->get_control_uid();
        ?>
        <# var controlUID = '<?php echo $control_uid; ?>'; #>
        <# var currentID = elementor.panel.currentView.currentPageView.model.attributes.settings.attributes[data.name]; #>
        <div class="elementor-control-field">
            <# if ( data.label ) { #>
            <label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
            <# } #>
            <div class="elementor-control-input-wrapper elementor-control-unit-5">
                <select id="<?php echo $control_uid; ?>" class="ea-select2" data-setting="{{ data.name }}"></select>
            </div>
        </div>
        <#
            ( function( $ ) {
                $( document.body ).trigger( 'ea_select2_init',{currentID:currentID,data:data,controlUID:controlUID} );
            }( jQuery ) );
        #>
        <?php
    }
}