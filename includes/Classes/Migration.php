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

		add_action( 'eael_cleanup_lr_form_messages', [ $this, 'cleanup_lr_form_messages' ] );
		if ( ! get_option( 'eael_lr_form_messages_cleaned' ) && ! wp_next_scheduled( 'eael_cleanup_lr_form_messages' ) ) {
			wp_schedule_single_event( time() + MINUTE_IN_SECONDS, 'eael_cleanup_lr_form_messages' );
		}
	}

	/**
	 * One-time removal of Login | Register lost / reset password message options.
	 *
	 * These only hold one-time form messages, so deleting them all is safe; at
	 * worst a visitor misses a pending notice. Runs in batches over WP-Cron and
	 * stops for good once nothing is left.
	 */
	public function cleanup_lr_form_messages() {
		global $wpdb;

		$prefixes = [
			'eael_show_reset_password_on_form_submit_',
			'eael_resetpassword_error_',
			'eael_resetpassword_success_',
			'eael_lostpassword_error_',
			'eael_lostpassword_success_',
		];
		$where    = [];
		foreach ( $prefixes as $prefix ) {
			$where[] = $wpdb->prepare( 'option_name LIKE %s', $wpdb->esc_like( $prefix ) . '%' );
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Each LIKE clause is prepared above.
		$names = $wpdb->get_col( "SELECT option_name FROM {$wpdb->options} WHERE " . implode( ' OR ', $where ) . ' LIMIT 5000' );

		if ( empty( $names ) ) {
			update_option( 'eael_lr_form_messages_cleaned', 1 );
			return;
		}

		$placeholders = implode( ',', array_fill( 0, count( $names ), '%s' ) );
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name IN ($placeholders)", $names ) );

		foreach ( $names as $name ) {
			wp_cache_delete( $name, 'options' );
		}

		wp_schedule_single_event( time() + MINUTE_IN_SECONDS, 'eael_cleanup_lr_form_messages' );
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
                           or (option_name = 'eael_gb_eb_popup_hide')
                           or (option_name like 'eael_login_error_%'))
                  ) AS options_tb2 
                    ON options_tb2.option_id = options_tb.option_id";
		$selection_sql = "select count(options_tb.option_id) as total " . $sql;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
		$results = $wpdb->get_var( $selection_sql );
		if ( $results > 0 ) {
			$deletiation_sql = "delete options_tb " . $sql;

      // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
			$wpdb->query( $deletiation_sql );
		}

		set_transient( 'eael_reduce_op_table_data', 1, DAY_IN_SECONDS );
		wp_clear_scheduled_hook( 'eael_remove_unused_options_data' );
	}
}