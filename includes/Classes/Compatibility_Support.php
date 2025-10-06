<?php
namespace Essential_Addons_Elementor\Classes;

class Compatibility_Support {
	public function __construct() {
		if ( $this->is_mondialrelay_plugin_active() ) {
			add_action( 'eael_mondialrelay_order_after_shipping', [$this, 'eael_mondialrelay_shipping_action'] );
		}
	}

	/**
    * Handles Mondial Relay shipping method integration
    * Checks if the plugin is active and adds necessary shipping form actions
    *
    * @return void
    */
	public function eael_mondialrelay_shipping_action() {
		// Check if Mondial Relay plugin is active
		if (!$this->is_mondialrelay_plugin_active()) {
			return;
		}

		// Get the chosen shipping method
		$chosen_shipping_methods = WC()->session->get('chosen_shipping_methods');
		if (empty($chosen_shipping_methods)) {
			return;
		}

		// Parse shipping method details
		$method_string = $chosen_shipping_methods[0];
		list($method_id, $instance_id) = explode(':', $method_string);

		// Add shipping form if Mondial Relay is selected
		if ($method_id === 'mondialrelay_official_shipping') {
			add_action( 'woocommerce_review_order_after_shipping', [$this, 'eael_mondialrelay_shipping_form_after'] );
		}
	}

	/**
	* Checks if Mondial Relay plugin is active
	*
	* @return bool
	*/
	private function is_mondialrelay_plugin_active() {
		// Include plugin.php to ensure plugin functions are available
		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin_path = 'mondialrelay-wordpress/mondialrelay-wordpress.php';
		return in_array( $plugin_path, apply_filters('active_plugins', get_option('active_plugins' ) ) ) || is_plugin_active_for_network( $plugin_path );
	}

	/**
	 * Renders the Mondial Relay shipping form in the WooCommerce checkout page.
	 * This method adds a table row customly from EA plugin
	 * @since 1.0.0
	 * @return void
	 */
	public function eael_mondialrelay_shipping_form_after() {
		?>
		<tr class="mrwp" style="display:none">
			<th><?php echo __('Livraison Mondial Relay','essential-addons-for-elementor-lite');?>
			<br>
			<em id="parcel_shop_info" class="parcel_shop_info"><?php echo __("Vous n'avez pas encore choisi de Point Relais®",'essential-addons-for-elementor-lite');?></em>
			</th>
			<td><a id="modaal_link" class="modaal_link" href="#modaal"><?php echo  __('Choisir un Point Relais®', 'essential-addons-for-elementor-lite'); ?></a></td>
		</tr>
		<script>
			jQuery(".modaal_link").modaal({
            overlay_opacity: .4,
            after_open: mrwpModaalOpen,
            after_callback_delay: 100,
            before_open: mrwpResetSearch,
            content_source: "#modaal"
        });
		</script>
    <?php
	}
}