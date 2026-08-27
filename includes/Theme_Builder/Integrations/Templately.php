<?php
/**
 * Templately hand-off from the preset picker.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

namespace Essential_Addons_Elementor\Theme_Builder\Integrations;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * What the picker needs to know about Templately, and how to get there.
 *
 * The preset library is deliberately small — a handful of starting points, not a
 * catalogue. Templately is the catalogue, so the picker ends with a way into it,
 * and that way has to work from all three states the plugin can be in: missing,
 * installed but inactive, and active.
 *
 * This class only *reports* state and resolves the destination. Installing is a
 * privileged action and lives behind the AJAX endpoint in `Admin\Ajax`, which is
 * where the nonce and the capability are checked.
 *
 * @since 6.7.3
 */
class Templately {

	/**
	 * wordpress.org slug.
	 */
	const SLUG = 'templately';

	/**
	 * Plugin file, relative to the plugins directory.
	 */
	const BASENAME = 'templately/templately.php';

	/**
	 * Templately is present on disk.
	 *
	 * @since 6.7.3
	 *
	 * @return bool
	 */
	public static function is_installed() {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return array_key_exists( self::BASENAME, get_plugins() );
	}

	/**
	 * Templately is running.
	 *
	 * @since 6.7.3
	 *
	 * @return bool
	 */
	public static function is_active() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return is_plugin_active( self::BASENAME );
	}

	/**
	 * Where the link goes once Templately is running.
	 *
	 * @since 6.7.3
	 *
	 * @return string
	 */
	public static function library_url() {
		return admin_url( 'edit.php?post_type=templately_library' );
	}

	/**
	 * Can this user get Templately into a usable state from here.
	 *
	 * Two different capabilities depending on what is actually needed — a user who
	 * may activate an installed plugin is not necessarily allowed to download a
	 * new one, and the notice should not offer an action that will be refused.
	 *
	 * @since 6.7.3
	 *
	 * @return bool
	 */
	public static function user_can_enable() {
		return self::is_installed()
			? current_user_can( 'activate_plugins' )
			: current_user_can( 'install_plugins' );
	}

	/**
	 * The state the picker renders from.
	 *
	 * `state` is one of:
	 *
	 * - `active`   — link straight through.
	 * - `inactive` — installed; one click activates and goes.
	 * - `missing`  — one click installs, activates and goes.
	 * - `blocked`  — not usable and this user cannot change that, so the picker
	 *                shows nothing rather than an action that would be refused.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	public static function get_state_for_ui() {
		if ( self::is_active() ) {
			return [
				'state' => 'active',
				'url'   => self::library_url(),
			];
		}

		if ( ! self::user_can_enable() ) {
			return [
				'state' => 'blocked',
				'url'   => '',
			];
		}

		return [
			'state' => self::is_installed() ? 'inactive' : 'missing',
			'url'   => self::library_url(),
		];
	}
}
