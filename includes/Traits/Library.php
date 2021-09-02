<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Library
{
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
    public function remove_files($uid = null, $ext = ['css', 'js'])
    {
        foreach ($ext as $e) {
            $path = EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . ($uid ? $uid : 'eael') . '.min.' . $e;

            if (file_exists($path)) {
                unlink($path);
            }
        }
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
    public function clear_cache_files()
    {
        check_ajax_referer('essential-addons-elementor', 'security');

        if(!current_user_can('manage_options')){
            wp_send_json_error(__('you are not allowed to do this action', 'essential-addons-for-elementor-lite'));
        }

        if (isset($_REQUEST['posts'])) {
            if (!empty($_POST['posts'])) {
                foreach (json_decode($_POST['posts']) as $post) {
                    $this->remove_files('post-' . $post);
                }
            }
        } else {
            // clear cache files
            $this->empty_dir(EAEL_ASSET_PATH);
        }

        wp_send_json(true);
    }

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
        
        if (!empty($_REQUEST['action']) && !$this->check_background_action($_REQUEST['action'])) {
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

        if (!empty($_REQUEST['action']) && !$this->check_background_action($_REQUEST['action'])) {
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
        ];
        if (in_array($action_name, $allow_action)){
            return true;
        }
        return false;
    }

	/**
	 * Remove some old options value from wp_options table which are not use any more
	 *
	 * @since 4.7.4
	 */
	public function remove_old_options_cache() {
		$status = get_option( "eael_remove_old_cache" );
		if ( !$status ) {
			update_option("eael_remove_old_cache",true);
			global $wpdb;
			$sql     = "from {$wpdb->options} as options_tb 
    				inner join (SELECT option_id FROM {$wpdb->options} 
    				WHERE ((option_name like '%\_elements' and LENGTH(option_name) = 18 and option_name not like '%\_eael_elements') 
    				           or (option_name like '%\_custom_js' and LENGTH(option_name) = 19 and option_name not like '%\_eael_custom_js' and (option_value IS NULL or option_value = ''))) 
    				  and autoload = 'yes') AS options_tb2 
    				    ON options_tb2.option_id = options_tb.option_id";
			$selection_sql  = "select count(options_tb.option_id) as total ".$sql;
			$results = $wpdb->get_var( $selection_sql );
			if ( $results > 1 ) {
				$deletiation_sql  = "delete options_tb ".$sql;
				$wpdb->query($deletiation_sql);
			}
		}
	}

	/*
	 * Check some other cookie for solve asset loading issue
	 */
	public function check_third_party_cookie_status() {
		global $Password_Protected;
		if ( is_object( $Password_Protected ) && method_exists( $Password_Protected, 'cookie_name' ) && isset( $_COOKIE[ $Password_Protected->cookie_name() ] ) ) {
			return true;
		}
		return false;
	}
}
