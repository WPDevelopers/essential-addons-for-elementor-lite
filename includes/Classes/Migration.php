<?php

namespace Essential_Addons_Elementor\Classes;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

class Migration
{
    use \Essential_Addons_Elementor\Traits\Core;
    use \Essential_Addons_Elementor\Traits\Library;

    /**
     * Plugin activation hook
     *
     * @since 3.0.0
     */
    public function plugin_activation_hook()
    {
        // remove old cache files
        $this->empty_dir(EAEL_ASSET_PATH);

        //check setup wizard condition
        $this->enable_setup_wizard();

        // save default values
        $this->set_default_values();

    }

    /**
     * Plugin deactivation hook
     *
     * @since 3.0.0
     */
    public function plugin_deactivation_hook()
    {
        $this->empty_dir(EAEL_ASSET_PATH);
    }

    /**
     * Plugin upgrade hook
     *
     * @since 3.0.0
     */
    public function plugin_upgrade_hook($upgrader_object, $options)
    {
        if ($options['action'] == 'update' && $options['type'] == 'plugin') {
            if (isset($options['plugins'][EAEL_PLUGIN_BASENAME])) {
                // remove old cache files
                $this->empty_dir(EAEL_ASSET_PATH);
            }
        }
    }

    /**
     * Plugin migrator
     *
     * @since 3.0.0
     */
    public function migrator()
    {
        // set current version to db
        if (get_option('eael_version') != EAEL_PLUGIN_VERSION) {
            // update plugin version
            update_option('eael_version', EAEL_PLUGIN_VERSION);
        }

        $this->reduce_options_data();
    }


    private function reduce_options_data(){

	    $status = get_option( 'eael_reduce_op_table_data' );
	    if ( $status || wp_doing_ajax()  ) {
		    return false;
	    }

	    global $wpdb;
	    $sql           = "from {$wpdb->options} as options_tb 
                inner join (SELECT option_id FROM {$wpdb->options} 
                WHERE ((option_name like '%\_eael_elements' and LENGTH(option_name) = 23 ) 
                           or (option_name like '%\_eael_custom_js' and LENGTH(option_name) = 24)
                           or (option_name like '%\_eael_updated_at' and LENGTH(option_name) = 25))
                  ) AS options_tb2 
                    ON options_tb2.option_id = options_tb.option_id";
	    $selection_sql = "select count(options_tb.option_id) as total " . $sql;

	    $results       = $wpdb->get_var( $selection_sql );
	    if ( $results > 0 ) {
		    $deletiation_sql = "delete options_tb " . $sql;
		    $wpdb->query( $deletiation_sql );
	    }

	    update_option( 'eael_reduce_op_table_data', 1 );
    }
}
