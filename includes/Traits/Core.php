<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Elementor\Plugin;
use \Essential_Addons_Elementor\Classes\Plugin_Usage_Tracker;

trait Core
{
    /**
     * Extending plugin links
     *
     * @since 3.0.0
     */
    public function i18n()
    {
        load_plugin_textdomain('essential-addons-for-elementor-lite');
    }

    /**
     * Check if a plugin is active
     *
     * @since 3.0.0
     */
    public function is_plugin_active($plugin)
    {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        return is_plugin_active($plugin);
    }

    /**
     * Extending plugin links
     *
     * @since 3.0.0
     */
    public function insert_plugin_links($links)
    {
        // settings
        $links[] = sprintf('<a href="admin.php?page=eael-settings">' . __('Settings', 'essential-addons-for-elementor-lite') . '</a>');

        // go pro
        if (!$this->pro_enabled) {
            $links[] = sprintf('<a href="https://wpdeveloper.com/in/upgrade-essential-addons-elementor" target="_blank" style="color: #524cff; font-weight: bold;">' . __('Go Pro', 'essential-addons-for-elementor-lite') . '</a>');
        }

        return $links;
    }

    /**
     * Extending plugin row meta
     *
     * @since 3.0.0
     */
    public function insert_plugin_row_meta($links, $file)
    {
        if (EAEL_PLUGIN_BASENAME == $file) {
            // docs & faq
            $links[] = sprintf('<a href="https://essential-addons.com/elementor/docs/?utm_medium=admin&utm_source=wp.org&utm_term=ea" target="_blank">' . __('Docs & FAQs', 'essential-addons-for-elementor-lite') . '</a>');

            // video tutorials
            $links[] = sprintf('<a href="https://www.youtube.com/channel/UCOjzLEdsnpnFVkm1JKFurPA?utm_medium=admin&utm_source=wp.org&utm_term=ea" target="_blank">' . __('Video Tutorials', 'essential-addons-for-elementor-lite') . '</a>');
        }

        return $links;
    }

    /**
     * Redirect to options page
     *
     * @since v1.0.0
     */
    public function redirect_on_activation()
    {
        if (get_transient('eael_do_activation_redirect')) {
            delete_transient('eael_do_activation_redirect');

            if (!isset($_GET['activate-multi'])) {
                wp_redirect("admin.php?page=eael-settings");
            }
        }
    }

    /**
     * Check if elementor plugin is activated
     *
     * @since v1.0.0
     */
    public function elementor_not_loaded()
    {
        if (!current_user_can('activate_plugins')) {
            return;
        }

        $elementor = 'elementor/elementor.php';

        if ($this->is_plugin_installed($elementor)) {
            $activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $elementor . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $elementor);

            $message = sprintf(__('%1$sEssential Addons for Elementor%2$s requires %1$sElementor%2$s plugin to be active. Please activate Elementor to continue.', 'essential-addons-for-elementor-lite'), "<strong>", "</strong>");

            $button_text = __('Activate Elementor', 'essential-addons-for-elementor-lite');
        } else {
            $activation_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');

            $message = sprintf(__('%1$sEssential Addons for Elementor%2$s requires %1$sElementor%2$s plugin to be installed and activated. Please install Elementor to continue.', 'essential-addons-for-elementor-lite'), '<strong>', '</strong>');
            $button_text = __('Install Elementor', 'essential-addons-for-elementor-lite');
        }

        $button = '<p><a href="' . esc_url( $activation_url ) . '" class="button-primary">' . esc_html( $button_text ) . '</a></p>';

        printf('<div class="error"><p>%1$s</p>%2$s</div>', __($message), $button);
    }

    /**
     * Optional usage tracker
     *
     * @since v1.0.0
     */
    public function start_plugin_tracking()
    {
        $tracker = Plugin_Usage_Tracker::get_instance( EAEL_PLUGIN_FILE, [
            'opt_in'       => true,
            'goodbye_form' => true,
            'item_id'      => '760e8569757fa16992d8'
        ] );
        $tracker->set_notice_options(array(
            'notice' => __( 'Want to help make <strong>Essential Addons for Elementor</strong> even more awesome? You can get a <strong>10% discount coupon</strong> for Pro upgrade if you allow.', 'essential-addons-for-elementor-lite' ),
            'extra_notice' => __( 'We collect non-sensitive diagnostic data and plugin usage information.
            Your site URL, WordPress & PHP version, plugins & themes and email address to send you the
            discount coupon. This data lets us make sure this plugin always stays compatible with the most
            popular plugins and themes. No spam, I promise.', 'essential-addons-for-elementor-lite' ),
        ));
        $tracker->init();
    }

    /**
     * Save default values to db
     *
     * @since v3.0.0
     */
    public function set_default_values()
    {
        $defaults = array_fill_keys(array_keys(array_merge($GLOBALS['eael_config']['elements'], $GLOBALS['eael_config']['extensions'])), 1);
        $values = get_option('eael_save_settings');

        return update_option('eael_save_settings', wp_parse_args($values, $defaults));
    }

    /**
     * Save setup wizard data
     *
     * @since v4.0.0
     */
    public function enable_setup_wizard()
    {
        if ( !get_option( 'eael_version' ) && !get_option( 'eael_setup_wizard' ) ) {
            update_option( 'eael_setup_wizard', 'redirect' );
        }
    }

    /**
     * Save default values to db
     *
     * @since v3.0.0
     */
    public function save_global_values($post_id, $editor_data)
    {
        if (wp_doing_cron()) {
            return;
        }

        $document = Plugin::$instance->documents->get($post_id, false);
        $global_settings = get_option('eael_global_settings', []);
         
        if ($document->get_settings('eael_ext_reading_progress_global') == 'yes' && $document->get_settings('eael_ext_reading_progress') == 'yes') {
            $global_settings['reading_progress'] = [
                'post_id' => $post_id,
                'enabled' => true,
                'eael_ext_reading_progress_global_display_condition' => $document->get_settings('eael_ext_reading_progress_global_display_condition'),
                'eael_ext_reading_progress_position' => $document->get_settings('eael_ext_reading_progress_position'),
                'eael_ext_reading_progress_height' => $document->get_settings('eael_ext_reading_progress_height'),
                'eael_ext_reading_progress_bg_color' => $document->get_settings('eael_ext_reading_progress_bg_color'),
                'eael_ext_reading_progress_fill_color' => $document->get_settings('eael_ext_reading_progress_fill_color'),
                'eael_ext_reading_progress_animation_speed' => $document->get_settings('eael_ext_reading_progress_animation_speed'),
            ];
        } else {
            if (isset($global_settings['reading_progress']['post_id']) && $global_settings['reading_progress']['post_id'] == $post_id) {
                $global_settings['reading_progress'] = [
                    'post_id' => null,
                    'enabled' => false,
                ];
            }
        }

        //save table of contents global value
        if ($document->get_settings('eael_ext_toc_global') == 'yes' && $document->get_settings('eael_ext_table_of_content') == 'yes') {
            $typography_fields = [
                'font_family',
                'font_weight',
                'text_transform',
                'font_style',
                'text_decoration',
                'font_size',
                'letter_spacing',
                'line_height',
            ];

            $global_settings['eael_ext_table_of_content'] = [
                'post_id' => $post_id,
                'enabled' => ($document->get_settings('eael_ext_toc_global') == 'yes'),
                'eael_ext_toc_global_display_condition' => $document->get_settings('eael_ext_toc_global_display_condition'),
                'eael_ext_toc_title' => $document->get_settings('eael_ext_toc_title'),
                'eael_ext_toc_title_tag' => $document->get_settings('eael_ext_toc_title_tag'),
                'eael_ext_toc_position' => $document->get_settings('eael_ext_toc_position'),
                'eael_ext_toc_supported_heading_tag' => $document->get_settings('eael_ext_toc_supported_heading_tag'),
                'eael_ext_toc_content_selector' => $document->get_settings('eael_ext_toc_content_selector'),
                'eael_toc_exclude_selector' => $document->get_settings('eael_toc_exclude_selector'),
                'eael_ext_toc_collapse_sub_heading' => $document->get_settings('eael_ext_toc_collapse_sub_heading'),
                'eael_ext_toc_use_title_in_url' => $document->get_settings('eael_ext_toc_use_title_in_url'),
                'eael_ext_toc_word_wrap' => $document->get_settings('eael_ext_toc_word_wrap'),
                'eael_ext_toc_table_box_shadow_box_shadow' => $document->get_settings('eael_ext_toc_table_box_shadow_box_shadow'),
                'eael_ext_toc_auto_collapse' => $document->get_settings('eael_ext_toc_auto_collapse'),
                'eael_ext_toc_auto_highlight' => $document->get_settings('eael_ext_toc_auto_highlight'),
                'eael_ext_toc_auto_highlight_single_item_only' => $document->get_settings('eael_ext_toc_auto_highlight_single_item_only'),
                'eael_ext_toc_hide_in_mobile' => $document->get_settings('eael_ext_toc_hide_in_mobile'),
                'eael_ext_toc_border_border' => $document->get_settings('eael_ext_toc_border_border'),
                'eael_ext_toc_border_width' => $document->get_settings('eael_ext_toc_border_width'),
                'eael_ext_toc_border_color' => $document->get_settings('eael_ext_toc_border_color'),
                'eael_ext_toc_box_border_radius' => $document->get_settings('eael_ext_toc_box_border_radius'),
                'eael_ext_toc_sticky_offset' => $document->get_settings('eael_ext_toc_sticky_offset'),
                'eael_ext_toc_sticky_scroll' => $document->get_settings('eael_ext_toc_sticky_scroll'),
                'eael_ext_toc_sticky_z_index' => $document->get_settings('eael_ext_toc_sticky_z_index'),

                //toc header setting
                'eael_ext_table_of_content_header_bg' => $document->get_settings('eael_ext_table_of_content_header_bg'),
                'eael_ext_table_of_content_header_text_color' => $document->get_settings('eael_ext_table_of_content_header_text_color'),
                'eael_ext_table_of_content_header_icon' => $document->get_settings('eael_ext_table_of_content_header_icon'),
                'eael_ext_toc_header_padding' => $document->get_settings('eael_ext_toc_header_padding'),
                'eael_ext_toc_width' => $document->get_settings('eael_ext_toc_width'),

                //close button setting
                'eael_ext_table_of_content_close_button_bg' => $document->get_settings('eael_ext_table_of_content_close_button_bg'),
                'eael_ext_table_of_content_close_button_text_color' => $document->get_settings('eael_ext_table_of_content_close_button_text_color'),
                'eael_ext_toc_close_button_text_style' => $document->get_settings('eael_ext_toc_close_button_text_style'),
                'eael_ext_table_of_content_close_button_icon_size' => $document->get_settings('eael_ext_table_of_content_close_button_icon_size'),
                'eael_ext_table_of_content_close_button_size' => $document->get_settings('eael_ext_table_of_content_close_button_size'),
                'eael_ext_table_of_content_close_button_line_height' => $document->get_settings('eael_ext_table_of_content_close_button_line_height'),
                'eael_ext_table_of_content_close_button_border_radius' => $document->get_settings('eael_ext_table_of_content_close_button_border_radius'),
                'eael_ext_table_of_content_close_button_box_shadow' => $document->get_settings('eael_ext_table_of_content_close_button_box_shadow_box_shadow'),

                //toc body setting
                'eael_ext_table_of_content_body_bg' => $document->get_settings('eael_ext_table_of_content_body_bg'),
                'eael_ext_toc_body_padding' => $document->get_settings('eael_ext_toc_body_padding'),

                //list style setting
                'eael_ext_table_of_content_list_style' => $document->get_settings('eael_ext_table_of_content_list_style'),
                'eael_ext_toc_top_level_space' => $document->get_settings('eael_ext_toc_top_level_space'),
                'eael_ext_toc_subitem_level_space' => $document->get_settings('eael_ext_toc_subitem_level_space'),
                'eael_ext_toc_list_icon' => $document->get_settings('eael_ext_toc_list_icon'),
                'eael_ext_table_of_content_list_text_color' => $document->get_settings('eael_ext_table_of_content_list_text_color'),
                'eael_ext_table_of_content_list_text_color_active' => $document->get_settings('eael_ext_table_of_content_list_text_color_active'),
                'eael_ext_table_of_list_hover_color' => $document->get_settings('eael_ext_table_of_list_hover_color'),
                'eael_ext_table_of_content_list_separator_style' => $document->get_settings('eael_ext_table_of_content_list_separator_style'),
                'eael_ext_table_of_content_list_separator_color' => $document->get_settings('eael_ext_table_of_content_list_separator_color'),
                'eael_ext_toc_box_list_bullet_size' => $document->get_settings('eael_ext_toc_box_list_bullet_size'),
                'eael_ext_toc_box_list_top_position' => $document->get_settings('eael_ext_toc_box_list_top_position'),
                'eael_ext_toc_indicator_size' => $document->get_settings('eael_ext_toc_indicator_size'),
                'eael_ext_toc_indicator_position' => $document->get_settings('eael_ext_toc_indicator_position'),
            ];
            foreach ($typography_fields as $typography_field) {
                $header_fields_attr = 'eael_ext_table_of_content_header_typography_' . $typography_field;
                $list_fields_attr = 'eael_ext_table_of_content_list_typography_normal_' . $typography_field;
                $global_settings['eael_ext_table_of_content'][$header_fields_attr] = $document->get_settings($header_fields_attr);
                $global_settings['eael_ext_table_of_content'][$list_fields_attr] = $document->get_settings($list_fields_attr);
            }
        } else {
            if (isset($global_settings['eael_ext_table_of_content']['post_id']) && $global_settings['eael_ext_table_of_content']['post_id'] == $post_id) {
                $global_settings['eael_ext_table_of_content'] = [];
            }
        }

        //Scroll to Top global settings : updated on elementor/editor/after_save action
        $global_settings['eael_ext_scroll_to_top'] = $this->get_ext_scroll_to_top_global_settings($post_id, $document, $global_settings);
        
        // set editor time
        update_option('eael_editor_updated_at', strtotime('now'));

        // update options
        update_option('eael_global_settings', $global_settings);
    }

    /**
     * Save default values to db while trashing a post
     *
     * @since 3.0.0
     */
    public function save_global_values_trashed_post($post_id)
    {
        if (wp_doing_cron()) {
            return;
        }

	    if ( ! $this->is_activate_elementor() ) {
		    return false;
	    }

        $document = Plugin::$instance->documents->get($post_id, false);
        $global_settings = get_option('eael_global_settings');

        // Reading Progress Bar
        if ( isset($global_settings['reading_progress']['post_id']) && $global_settings['reading_progress']['post_id'] == $post_id ) {
            $global_settings['reading_progress'] = [
                'post_id' => null,
                'enabled' => false,
            ];
        }

        // Table of Contents
        if ( isset($global_settings['eael_ext_table_of_content']['post_id']) && $global_settings['eael_ext_table_of_content']['post_id'] == $post_id ) {
            $global_settings['eael_ext_table_of_content'] = [];
        }

        // Scroll to Top
        if ( isset($global_settings['eael_ext_scroll_to_top']['post_id']) && $global_settings['eael_ext_scroll_to_top']['post_id'] == $post_id ) {
            $global_settings['eael_ext_scroll_to_top'] = [
                'post_id' => null,
                'enabled' => false,
            ];
        }

        // set editor time
        update_option('eael_editor_updated_at', strtotime('now'));

        // update options
        update_option('eael_global_settings', $global_settings);
    }

    /**
     * Get global settings of Scroll to Top extension
     * 
     * @return array
     * @since v5.0.0
     */
    public function get_ext_scroll_to_top_global_settings($post_id, $document, $global_settings){
        
        $global_settings_scroll_to_top = !empty($global_settings['eael_ext_scroll_to_top']) ? $global_settings['eael_ext_scroll_to_top'] : array();
        $document_settings = $document->get_settings();
        
        if ($document->get_settings('eael_ext_scroll_to_top_global') == 'yes' && $document->get_settings('eael_ext_scroll_to_top') == 'yes') {
            $global_settings_scroll_to_top = [
                'post_id' => $post_id,
                'enabled' => true,
                'eael_ext_scroll_to_top_global_display_condition' => $document->get_settings('eael_ext_scroll_to_top_global_display_condition'),
                'eael_ext_scroll_to_top_position_text' => $document->get_settings('eael_ext_scroll_to_top_position_text'),
                'eael_ext_scroll_to_top_position_bottom' => $document->get_settings('eael_ext_scroll_to_top_position_bottom'),
                'eael_ext_scroll_to_top_position_left' => $document->get_settings('eael_ext_scroll_to_top_position_left'),
                'eael_ext_scroll_to_top_position_right' => $document->get_settings('eael_ext_scroll_to_top_position_right'),
                'eael_ext_scroll_to_top_button_width' => $document->get_settings('eael_ext_scroll_to_top_button_width'),
                'eael_ext_scroll_to_top_button_height' => $document->get_settings('eael_ext_scroll_to_top_button_height'),
                'eael_ext_scroll_to_top_z_index' => $document->get_settings('eael_ext_scroll_to_top_z_index'),
                'eael_ext_scroll_to_top_button_opacity' => $document->get_settings('eael_ext_scroll_to_top_button_opacity'),
                'eael_ext_scroll_to_top_button_icon_image' => $document->get_settings('eael_ext_scroll_to_top_button_icon_image'),
                'eael_ext_scroll_to_top_button_icon_size' => $document->get_settings('eael_ext_scroll_to_top_button_icon_size'),
                'eael_ext_scroll_to_top_button_icon_svg_size' => $document->get_settings('eael_ext_scroll_to_top_button_icon_svg_size'),
                'eael_ext_scroll_to_top_button_icon_color' => $this->eael_ext_stt_fetch_color_or_global_color($document_settings, 'eael_ext_scroll_to_top_button_icon_color'),
                'eael_ext_scroll_to_top_button_bg_color' => $this->eael_ext_stt_fetch_color_or_global_color($document_settings, 'eael_ext_scroll_to_top_button_bg_color'),
                'eael_ext_scroll_to_top_button_border_radius' => $document->get_settings('eael_ext_scroll_to_top_button_border_radius'),
            ];
        } else {
            if (
                ( isset($global_settings['eael_ext_scroll_to_top']['post_id']) && $global_settings['eael_ext_scroll_to_top']['post_id'] == $post_id )
                || 
                ( isset($global_settings['eael_ext_scroll_to_top']['post_id']) && 'publish' !== get_post_status($global_settings['eael_ext_scroll_to_top']['post_id']) )
               ) {
                $global_settings_scroll_to_top = [
                    'post_id' => null,
                    'enabled' => false,
                ];
            }
        }

        return $global_settings_scroll_to_top;
    }
    
    public function eael_ext_stt_fetch_color_or_global_color($settings, $control_name=''){
        if( !isset($settings[$control_name])) {
            return '';
        }

        $color = $settings[$control_name];

        if(!empty($settings['__globals__']) && !empty($settings['__globals__'][$control_name])){
            $color = $settings['__globals__'][$control_name];
            $color_arr = explode('?id=', $color); //E.x. 'globals/colors/?id=primary'

            $color_name = count($color_arr) > 1 ? $color_arr[1] : '';
            if( !empty($color_name) ) {
                $color = "var( --e-global-color-$color_name )";
            }
        }

        return $color;
    }
}
