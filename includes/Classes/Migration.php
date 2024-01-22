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
	public function plugin_upgrade_hook( $upgrader_object, $options ) {
		if ( isset( $options['action'], $options['type'] ) && $options['action'] === 'update' && $options['type'] === 'plugin' ) {
			if ( ( isset( $options['plugins'] ) &&
			       ( in_array( EAEL_PLUGIN_BASENAME, $options['plugins'] ) ||
			         in_array( 'essential-addons-elementor/essential_adons_elementor.php', $options['plugins'] )
			       )
			     ) || ( isset( $options['plugin'] ) &&
			            in_array( $options['plugin'], [ EAEL_PLUGIN_BASENAME, 'essential-addons-elementor/essential_adons_elementor.php' ] )
			     )
			) {
				// remove old cache files
				$this->empty_dir( EAEL_ASSET_PATH );
			}
		}
	}

    /**
     * Plugin migrator
     *
     * @since 3.0.0
     */
	public function migrator() {
		// set current version to db
		if ( get_option( 'eael_version' ) != EAEL_PLUGIN_VERSION ) {
			// update plugin version
			update_option( 'eael_version', EAEL_PLUGIN_VERSION );
		}

		add_action( 'eael_after_clear_cache_files', [ $this, 'reduce_options_data' ] );
	}


	public function reduce_options_data() {
		$status = get_transient( 'eael_reduce_op_table_data' );
		if ( $status ) {
			return false;
		}

		global $wpdb;
		$sql           = "from {$wpdb->options} as options_tb 
                inner join (SELECT option_id FROM {$wpdb->options} 
                WHERE ((option_name like '%\_eael_elements' and LENGTH(option_name) = 23 ) 
                           or (option_name like '%\_eael_custom_js' and LENGTH(option_name) = 24)
                           or (option_name like '%\_eael_updated_at' and LENGTH(option_name) = 25)
                           or (option_name = 'eael_reduce_op_table_data')
                           or (option_name = 'eael_remove_old_cache')
                           or (option_name = 'eael_editor_updated_at')
                           or (option_name like 'eael_login_error_%'))
                  ) AS options_tb2 
                    ON options_tb2.option_id = options_tb.option_id";
		$selection_sql = "select count(options_tb.option_id) as total " . $sql;

		$results = $wpdb->get_var( $selection_sql );
		if ( $results > 0 ) {
			$deletiation_sql = "delete options_tb " . $sql;
			$wpdb->query( $deletiation_sql );
		}

		set_transient( 'eael_reduce_op_table_data', 1, DAY_IN_SECONDS );
		wp_clear_scheduled_hook( 'eael_remove_unused_options_data' );
	}
}
