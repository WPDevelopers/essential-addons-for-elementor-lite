<?php

namespace Essential_Addons_Elementor\Classes;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Unified reCAPTCHA Manager for Essential Addons
 * 
 * This class consolidates all reCAPTCHA functionality across the Login/Register widget
 * to eliminate code duplication and provide a single source of truth for reCAPTCHA handling.
 * 
 * @since 6.2.1
 */
class Recaptcha_Manager {

    /**
     * Default action for reCAPTCHA v3
     */
    const V3_DEFAULT_ACTION = 'eael_login_register_form';

    /**
     * Default score threshold for reCAPTCHA v3
     */
    const V3_DEFAULT_THRESHOLD = 0.5;

    /**
     * Supported form types
     */
    const SUPPORTED_FORMS = ['login', 'register', 'lostpassword'];

    /**
     * Get reCAPTCHA configuration for a specific form type
     * 
     * @param string $form_type The form type (login, register, lostpassword)
     * @param array $settings Widget settings
     * @return array Configuration array
     */
    public static function get_config($form_type, $settings = []) {
        if (!in_array($form_type, self::SUPPORTED_FORMS)) {
            return [];
        }

        $version = self::get_version($settings);
        $is_enabled = self::is_enabled($form_type, $settings);

        return [
            'enabled' => $is_enabled,
            'version' => $version,
            'site_key' => self::get_site_key($version),
            'theme' => self::get_theme($form_type, $settings),
            'size' => self::get_size($form_type, $settings),
            'threshold' => self::get_threshold($settings),
            'language' => self::get_language($version),
            'badge_hide' => self::is_badge_hidden(),
        ];
    }

    /**
     * Check if reCAPTCHA is enabled for a specific form
     * 
     * @param string $form_type The form type
     * @param array $settings Widget settings
     * @return bool
     */
    public static function is_enabled($form_type, $settings = []) {
        // v3 applies to all forms when enabled
        if (self::get_version($settings) === 'v3') {
            return !empty($settings['enable_login_register_recaptcha']) && 
                   $settings['enable_login_register_recaptcha'] === 'yes';
        }

        // v2 requires individual form enablement
        $setting_key = "enable_{$form_type}_recaptcha";
        return !empty($settings[$setting_key]) && $settings[$setting_key] === 'yes';
    }

    /**
     * Get reCAPTCHA version from settings
     * 
     * @param array $settings Widget settings
     * @return string
     */
    public static function get_version($settings = []) {
        return !empty($settings['login_register_recaptcha_version']) 
            ? $settings['login_register_recaptcha_version'] 
            : 'v2';
    }

    /**
     * Get site key for the specified version
     * 
     * @param string $version reCAPTCHA version (v2 or v3)
     * @return string
     */
    public static function get_site_key($version = 'v2') {
        $option_key = $version === 'v3' ? 'eael_recaptcha_sitekey_v3' : 'eael_recaptcha_sitekey';
        return get_option($option_key, '');
    }

    /**
     * Get secret key for the specified version
     * 
     * @param string $version reCAPTCHA version (v2 or v3)
     * @return string
     */
    public static function get_secret_key($version = 'v2') {
        $option_key = $version === 'v3' ? 'eael_recaptcha_secret_v3' : 'eael_recaptcha_secret';
        return get_option($option_key, '');
    }

    /**
     * Get theme setting for a specific form
     * 
     * @param string $form_type The form type
     * @param array $settings Widget settings
     * @return string
     */
    public static function get_theme($form_type, $settings = []) {
        $setting_key = "{$form_type}_rc_theme";
        return !empty($settings[$setting_key]) ? $settings[$setting_key] : 'light';
    }

    /**
     * Get size setting for a specific form
     * 
     * @param string $form_type The form type
     * @param array $settings Widget settings
     * @return string
     */
    public static function get_size($form_type, $settings = []) {
        $setting_key = "{$form_type}_rc_size";
        return !empty($settings[$setting_key]) ? $settings[$setting_key] : 'normal';
    }

    /**
     * Get score threshold for reCAPTCHA v3
     * 
     * @param array $settings Widget settings
     * @return float
     */
    public static function get_threshold($settings = []) {
        $threshold = self::V3_DEFAULT_THRESHOLD;
        
        if (!empty($settings['login_register_recaptcha_v3_score_threshold']['size'])) {
            $threshold = floatval($settings['login_register_recaptcha_v3_score_threshold']['size']);
        }
        
        // Ensure threshold is within valid range
        $threshold = max(0, min(1, $threshold));
        
        return apply_filters('eael_recaptcha_threshold', $threshold);
    }

    /**
     * Get language setting for reCAPTCHA
     * 
     * @param string $version reCAPTCHA version
     * @return string
     */
    public static function get_language($version = 'v2') {
        $option_key = $version === 'v3' ? 'eael_recaptcha_language_v3' : 'eael_recaptcha_language';
        return get_option($option_key, '');
    }

    /**
     * Check if reCAPTCHA badge should be hidden
     * 
     * @return bool
     */
    public static function is_badge_hidden() {
        return get_option('eael_recaptcha_badge_hide', false);
    }

    /**
     * Render reCAPTCHA node HTML for a specific form
     * 
     * @param string $form_type The form type
     * @param string $widget_id Widget ID
     * @param array $settings Widget settings
     * @return string HTML output
     */
    public static function render_node($form_type, $widget_id, $settings = []) {
        $config = self::get_config($form_type, $settings);
        
        if (!$config['enabled']) {
            return '';
        }

        $node_id = "{$form_type}-recaptcha-node-" . esc_attr($widget_id);
        $html = "<input type='hidden' name='g-recaptcha-enabled' value='1'/>";
        $html .= "<div id='" . esc_attr($node_id) . "' class='eael-recaptcha-wrapper'></div>";

        // Add action field for v3 non-AJAX forms
        if ($config['version'] === 'v3' && empty($settings['enable_ajax'])) {
            $html .= "<input type='hidden' name='action' value='" . self::V3_DEFAULT_ACTION . "'/>";
        }

        return $html;
    }

    /**
     * Get data attributes for form wrapper
     * 
     * @param string $form_type The form type
     * @param array $settings Widget settings
     * @return array
     */
    public static function get_data_attributes($form_type, $settings = []) {
        $config = self::get_config($form_type, $settings);
        
        return [
            "data-recaptcha-theme" => esc_attr($config['theme']),
            "data-recaptcha-size" => esc_attr($config['size']),
        ];
    }

    /**
     * Validate reCAPTCHA response
     * 
     * @param string $version reCAPTCHA version (v2 or v3)
     * @param array $settings Widget settings
     * @return bool
     */
    public static function validate($version = 'v2', $settings = []) {
        if (!isset($_REQUEST['g-recaptcha-response'])) {
            return false;
        }

        $secret_key = self::get_secret_key($version);
        if (empty($secret_key)) {
            return false;
        }

        $endpoint = 'https://www.recaptcha.net/recaptcha/api/siteverify';
        $data = [
            'secret' => $secret_key,
            'response' => sanitize_text_field($_REQUEST['g-recaptcha-response']),
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
        ];

        $response = wp_remote_post($endpoint, ['body' => $data]);
        
        if (is_wp_error($response)) {
            return false;
        }

        $result = json_decode(wp_remote_retrieve_body($response), true);
        
        if (!isset($result['success'])) {
            return false;
        }

        if ($version === 'v3') {
            return self::validate_v3_response($result, $settings);
        }

        return $result['success'];
    }

    /**
     * Validate reCAPTCHA v3 specific response
     * 
     * @param array $result API response
     * @param array $settings Widget settings
     * @return bool
     */
    private static function validate_v3_response($result, $settings = []) {
        // Check action
        $action_ok = !isset($result['action']) || $result['action'] === self::V3_DEFAULT_ACTION;
        
        // Check score
        $score_ok = isset($result['score']) && $result['score'] > self::get_threshold($settings);
        
        return $action_ok && $score_ok && $result['success'];
    }

    /**
     * Enqueue reCAPTCHA scripts
     * 
     * @param string $version reCAPTCHA version
     * @param array $settings Widget settings
     */
    public static function enqueue_scripts($version = 'v2', $settings = []) {
        if ($version === 'v3') {
            self::enqueue_v3_scripts($settings);
        } else {
            self::enqueue_v2_scripts();
        }
    }

    /**
     * Enqueue reCAPTCHA v2 scripts
     */
    private static function enqueue_v2_scripts() {
        if (!wp_script_is('eael-recaptcha', 'registered')) {
            wp_register_script('eael-recaptcha', 'https://www.recaptcha.net/recaptcha/api.js', [], EAEL_PLUGIN_VERSION, false);
        }
        wp_enqueue_script('eael-recaptcha');
    }

    /**
     * Enqueue reCAPTCHA v3 scripts
     * 
     * @param array $settings Widget settings
     */
    private static function enqueue_v3_scripts($settings = []) {
        $site_key = self::get_site_key('v3');
        if (empty($site_key)) {
            return;
        }

        $args = ['render' => $site_key];
        
        $language = self::get_language('v3');
        if (!empty($language)) {
            $args['hl'] = $language;
        }

        $args = apply_filters('eael_lr_recaptcha_api_args_v3', $args);
        $query_string = http_build_query($args);
        
        wp_register_script('eael-recaptcha-v3', "https://www.recaptcha.net/recaptcha/api.js?{$query_string}", [], EAEL_PLUGIN_VERSION, false);
        wp_enqueue_script('eael-recaptcha-v3');
        wp_dequeue_script('eael-recaptcha');
    }

    /**
     * Get global data attributes for the main wrapper
     * 
     * @param array $settings Widget settings
     * @return array
     */
    public static function get_global_data_attributes($settings = []) {
        $version = self::get_version($settings);
        
        return [
            'data-recaptcha-sitekey' => esc_attr(self::get_site_key('v2')),
            'data-recaptcha-sitekey-v3' => esc_attr(self::get_site_key('v3')),
            'data-login-recaptcha-version' => esc_attr($version),
            'data-register-recaptcha-version' => esc_attr($version),
            'data-lostpassword-recaptcha-version' => esc_attr($version),
        ];
    }
}
