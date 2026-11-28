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

		add_action( 'eael_migrate_adv_tabs_responsive_layout', [ $this, 'migrate_adv_tabs_responsive_vertical_layout' ] );
		if ( ! get_option( 'eael_adv_tabs_responsive_layout_migrated' ) && ! wp_next_scheduled( 'eael_migrate_adv_tabs_responsive_layout' ) ) {
			// Stamped once here, not recomputed per batch — see the cutoff explanation in
			// migrate_adv_tabs_responsive_vertical_layout()'s docblock.
			if ( false === get_option( 'eael_adv_tabs_responsive_layout_cutoff', false ) ) {
				update_option( 'eael_adv_tabs_responsive_layout_cutoff', gmdate( 'Y-m-d H:i:s' ) );
			}
			wp_schedule_single_event( time() + MINUTE_IN_SECONDS, 'eael_migrate_adv_tabs_responsive_layout' );
		}
	}

	/**
	 * Backfill Advanced Tabs' `responsive_vertical_layout` control on existing posts (#1043).
	 *
	 * The render condition used to add the `responsive-vertical-layout` class when the value
	 * was != 'yes' — the opposite of what the "Vertical Layout" label promises. The condition
	 * is now `== 'yes'`. Since the control defaults to 'yes' and Elementor only persists a
	 * control once a user touches it, every pre-fix widget has the key missing, so the
	 * corrected condition would silently flip its mobile layout.
	 *
	 * Explicitly backfilling 'no' into every pre-existing widget that lacks the key freezes
	 * its current (wrapped/side-by-side) mobile appearance. A widget created after the fix
	 * shipped must NOT be touched, so eligibility is decided by a fixed cutoff timestamp
	 * (stamped once, in migrator()) compared against:
	 *   - post_date_gmt (creation time), for most posts — not post_modified_gmt, which any
	 *     unrelated wp_update_post() call bumps regardless of whether this widget was touched.
	 *   - post_modified_gmt, as a fallback only when post_date_gmt is still the WordPress
	 *     zero-date ('0000-00-00 00:00:00'), which is how a draft/pending/auto-draft post is
	 *     stored before first publish. post_modified_gmt is reliable there because the widget
	 *     data can't exist yet without at least one real wp_update_post() call.
	 *
	 * Runs once, in batches over WP-Cron, same pattern as cleanup_lr_form_messages() above. A
	 * row that fails to json_decode() (corrupted/legacy data) is still marked done so it can't
	 * block the migration from ever completing.
	 */
	public function migrate_adv_tabs_responsive_vertical_layout() {
		global $wpdb;

		$cutoff = get_option( 'eael_adv_tabs_responsive_layout_cutoff' );

		if ( ! $cutoff ) {
			return;
		}

		// NOT EXISTS against the "done" marker paginates: already-migrated posts drop out of
		// the result set, so repeated runs advance instead of reprocessing the same 50 rows.
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- static LIKE pattern, no user input.
		$rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT pm.post_id, pm.meta_value FROM {$wpdb->postmeta} pm
				INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id AND (
					( p.post_date_gmt != '0000-00-00 00:00:00' AND p.post_date_gmt <= %s )
					OR
					( p.post_date_gmt = '0000-00-00 00:00:00' AND p.post_modified_gmt <= %s )
				)
				WHERE pm.meta_key = '_elementor_data' AND pm.meta_value LIKE %s
				AND NOT EXISTS (
					SELECT 1 FROM {$wpdb->postmeta} done
					WHERE done.post_id = pm.post_id AND done.meta_key = '_eael_adv_tabs_responsive_layout_migrated'
				)
				LIMIT 50",
				$cutoff,
				$cutoff,
				'%eael-adv-tabs%'
			)
		);

		if ( empty( $rows ) ) {
			update_option( 'eael_adv_tabs_responsive_layout_migrated', 1 );
			return;
		}

		foreach ( $rows as $row ) {
			$elements = json_decode( $row->meta_value, true );

			if ( ! is_array( $elements ) ) {
				// Not valid JSON (legacy serialized data, a corrupted/truncated blob) — mark
				// done anyway, or this row would keep matching the LIKE scan forever and the
				// migration would never complete.
				update_post_meta( $row->post_id, '_eael_adv_tabs_responsive_layout_migrated', 1 );
				continue;
			}

			$changed = false;
			$this->backfill_adv_tabs_responsive_layout( $elements, $changed );

			if ( $changed ) {
				update_post_meta( $row->post_id, '_elementor_data', wp_slash( wp_json_encode( $elements ) ) );
			}

			// Marker so this post isn't re-selected by a later batch.
			update_post_meta( $row->post_id, '_eael_adv_tabs_responsive_layout_migrated', 1 );
		}

		wp_schedule_single_event( time() + MINUTE_IN_SECONDS, 'eael_migrate_adv_tabs_responsive_layout' );
	}

	/**
	 * Recursively walk an Elementor element tree, backfilling 'no' into every
	 * eael-adv-tabs widget's settings that doesn't already have an explicit
	 * responsive_vertical_layout value.
	 *
	 * @param array $elements Element tree (by reference).
	 * @param bool  $changed  Set true if any element was modified (by reference).
	 */
	private function backfill_adv_tabs_responsive_layout( &$elements, &$changed ) {
		foreach ( $elements as &$element ) {
			if (
				isset( $element['widgetType'], $element['settings'] )
				&& 'eael-adv-tabs' === $element['widgetType']
				&& is_array( $element['settings'] )
				&& ! array_key_exists( 'responsive_vertical_layout', $element['settings'] )
			) {
				$element['settings']['responsive_vertical_layout'] = 'no';
				$changed                                           = true;
			}

			if ( ! empty( $element['elements'] ) && is_array( $element['elements'] ) ) {
				$this->backfill_adv_tabs_responsive_layout( $element['elements'], $changed );
			}
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