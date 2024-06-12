<?php

namespace Essential_Addons_Elementor\Traits;

use Elementor\Plugin;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Library
{
	public $a;
    /**
     *  Return array of registered elements.
     *
     * @todo filter output
     */

    public function get_registered_elements()
    {
        return array_keys($this->registered_elements);
    }

    /**
     * Return saved settings
     *
     * @since 3.0.0
     */
    public function get_settings($element = null)
    {
        $defaults = array_fill_keys(array_keys(array_merge($this->registered_elements, $this->registered_extensions)), true);
        $elements = get_option('eael_save_settings', $defaults);
        $elements = array_merge($defaults, $elements);

        return (isset($element) ? (isset($elements[$element]) ? $elements[$element] : 0) : array_keys(array_filter($elements)));
    }

    /**
     * @param $page_obj
     * @param $key
     * @return string
     */
    public function get_extension_settings($page_settings = [], $global_settings = [], $extension = '', $key = '')
    {
        if (isset($page_settings) && $page_settings->get_settings($extension) == 'yes') {
            return $page_settings->get_settings($key);
        } else if (isset($global_settings[$extension]['enabled'])) {
            return isset($global_settings[$extension][$key]) ? $global_settings[$extension][$key] : '';
        }

        return '';
    }

    /**
     * @param $id
     * @param $global_data
     * @return string
     */
    public function get_typography_data($id, $global_data)
    {
        $typo_data = '';
        $fields_keys = [
            'font_family',
            'font_weight',
            'text_transform',
            'font_style',
            'text_decoration',
            'font_size',
            'letter_spacing',
            'line_height',
        ];

        foreach ($fields_keys as $key => $field) {
            $typo_attr = $global_data[$id . '_' . $field];
            $attr = str_replace('_', '-', $field);

            if (in_array($field, ['font_size', 'letter_spacing', 'line_height'])) {
                if (!empty($typo_attr['size'])) {
                    $typo_data .= "{$attr}:{$typo_attr['size']}{$typo_attr['unit']} !important;";
                }
            } elseif (!empty($typo_attr)) {
                $typo_data .= ($attr == 'font-family') ? "{$attr}:{$typo_attr}, sans-serif;" : "{$attr}:{$typo_attr};";
            }
        }

        return $typo_data;
    }

    /**
     * Check if assets files exists
     *
     * @since 3.0.0
     */
    public function has_assets_files($uid = null, $ext = ['css', 'js'])
    {
        if (!is_array($ext)) {
            $ext = (array) $ext;
        }

        foreach ($ext as $e) {
            $path = EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . ($uid ? $uid : 'eael') . '.min.' . $e;

            if (!is_readable($this->safe_path($path))) {
                return false;
            }
        }

        return true;
    }

	/**
	 * Remove files
	 *
	 * @since 3.0.0
	 */
	public function remove_files( $post_id = null, $ext = [ 'css', 'js' ] ) {
		foreach ( $ext as $e ) {
			$path = EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . 'eael' . ( $post_id ? '-' . $post_id : '' ) . '.' . $e;
			if ( file_exists( $path ) ) {
				unlink( $path );
			}
		}
		do_action( 'eael_remove_assets', $post_id, $ext );
	}

    /**
     * Remove files in dir
     *
     * @since 3.0.0
     */
    public function empty_dir($path)
    {
        if (!is_dir($path) || !file_exists($path)) {
            return;
        }

        foreach (scandir($path) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            unlink($this->safe_path($path . DIRECTORY_SEPARATOR . $item));
        }
    }

    /**
     * Clear cache files
     *
     * @since 3.0.0
     */


    /**
     * Check if wp running in background
     *
     * @since 3.0.0
     */
    public function is_running_background()
    {
        if (wp_doing_cron()) {
            return true;
        }

        if (wp_doing_ajax()) {
            return true;
        }
        
        if (!empty($_REQUEST['action']) && !$this->check_background_action( sanitize_text_field( $_REQUEST['action'] ) )) {
            return true;
        }

        return false;
    }

    /**
     * Check if elementor edit mode or not
     *
     * @since 3.0.0
     */
    public function is_edit_mode()
    {
        if (isset($_REQUEST['elementor-preview'])) {
            return true;
        }

        return false;
    }

    /**
     * Check if elementor edit mode or not
     *
     * @since 3.0.0
     */
    public function is_preview_mode()
    {
        if (isset($_REQUEST['elementor-preview'])) {
            return false;
        }

        if (!empty($_REQUEST['action']) && !$this->check_background_action( sanitize_text_field( $_REQUEST['action'] ) )) {
            return false;
        }


        return true;
    }

    /**
     * Check if a plugin is installed
     *
     * @since v3.0.0
     */
    public function is_plugin_installed($basename)
    {
        if (!function_exists('get_plugins')) {
            include_once ABSPATH . '/wp-admin/includes/plugin.php';
        }

        $installed_plugins = get_plugins();

        return isset($installed_plugins[$basename]);
    }

    /**
     * Generate safe path
     *
     * @since v3.0.0
     */
    public function safe_path($path)
    {
        $path = str_replace(['//', '\\\\'], ['/', '\\'], $path);

        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }

    /**
     * Generate safe url
     *
     * @since v3.0.0
     */
    public function safe_url($url)
    {
        if (is_ssl()) {
            $url = wp_parse_url($url);

            if (!empty($url['host'])) {
                $url['scheme'] = 'https';
            }

            return $this->unparse_url($url);
        }

        return $url;
    }

    public function unparse_url($parsed_url)
    {
        $scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host = isset($parsed_url['host']) ? $parsed_url['host'] : '';
        $port = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
        $user = isset($parsed_url['user']) ? $parsed_url['user'] : '';
        $pass = isset($parsed_url['pass']) ? ':' . $parsed_url['pass'] : '';
        $pass = ($user || $pass) ? "$pass@" : '';
        $path = isset($parsed_url['path']) ? $parsed_url['path'] : '';
        $query = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
        $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';

        return "$scheme$user$pass$host$port$path$query$fragment";
    }

    /**
     * Allow to load asset for some pre defined action query param in elementor preview
     * @return bool
     */
    public function check_background_action($action_name){
        $allow_action = [
        	'subscriptions',
	        'mepr_unauthorized',
	        'home',
	        'subscriptions',
	        'payments',
	        'newpassword',
	        'manage_sub_accounts',
	        'ppw_postpass',
        ];

		$allow_action = apply_filters( 'eael/asset-builder/allowed_actions', $allow_action );

        if (in_array($action_name, $allow_action)){
            return true;
        }
        return false;
    }

	/*
	 * Check some other cookie for solve asset loading issue
	 */
	public function check_third_party_cookie_status($id='') {
		global $Password_Protected;
		if ( is_object( $Password_Protected ) && method_exists( $Password_Protected, 'cookie_name' ) && isset( $_COOKIE[ $Password_Protected->cookie_name() ] ) ) {
			return true;
		}
		return false;
	}

	/**
	 * check_protected_content_status
	 *
	 * check EaeL Protected content cookie set or not
	 *
	 * @return bool
	 */
	public function check_protected_content_status(){
		if(!empty($_POST['eael_protected_content_id'])){
			if(!empty($_POST['protection_password_'.$_POST['eael_protected_content_id']])){
				return true;
			}
		}
		return false;
	}

}
