<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

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
     * Extending plugin links
     *
     * @since 3.0.0
     */
    public function insert_plugin_links($links)
    {
        // settings
        $links[] = sprintf('<a href="admin.php?page=eael-settings">' . __('Settings') . '</a>');

        // go pro
        if (!$this->pro_enabled) {
            $links[] = sprintf('<a href="https://wpdeveloper.net/in/upgrade-essential-addons-elementor" target="_blank" style="color: #39b54a; font-weight: bold;">' . __('Go Pro') . '</a>');
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
            $links[] = sprintf('<a href="https://essential-addons.com/elementor/docs/?utm_medium=admin&utm_source=wp.org&utm_term=ea" target="_blank">' . __('Docs & FAQs') . '</a>');

            // video tutorials
            $links[] = sprintf('<a href="https://www.youtube.com/channel/UCOjzLEdsnpnFVkm1JKFurPA?utm_medium=admin&utm_source=wp.org&utm_term=ea" target="_blank">' . __('Video Tutorials') . '</a>');
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
            $message = __('<strong>Essential Addons for Elementor</strong> requires <strong>Elementor</strong> plugin to be active. Please activate Elementor to continue.', 'essential-addons-for-elementor-lite');
            $button_text = __('Activate Elementor', 'essential-addons-for-elementor-lite');
        } else {
            $activation_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');
            $message = sprintf(__('<strong>Essential Addons for Elementor</strong> requires <strong>Elementor</strong> plugin to be installed and activated. Please install Elementor to continue.', 'essential-addons-for-elementor-lite'), '<strong>', '</strong>');
            $button_text = __('Install Elementor', 'essential-addons-for-elementor-lite');
        }

        $button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';

        printf('<div class="error"><p>%1$s</p>%2$s</div>', __($message), $button);
    }

    /**
     * Optional usage tracker
     *
     * @since v1.0.0
     */
    public function start_plugin_tracking()
    {
        new Plugin_Usage_Tracker(
            EAEL_PLUGIN_FILE,
            'http://app.wpdeveloper.net',
            array(),
            true,
            true,
            1
        );
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
     * Save default values to db
     *
     * @since v3.0.0
     */
    public function save_global_values($post_id, $editor_data)
    {
        $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers('page');
        $page_settings_model = $page_settings_manager->get_model($post_id);
        $eael_ext_reading_progress_global = $page_settings_model->get_settings('eael_ext_reading_progress_global');
        $global_settings = get_option('eael_global_settings');

        if($page_settings_model->get_settings('eael_ext_reading_progress_global') == 'yes') {
            $global_settings['reading_progress'] = [
                'post_id' => $post_id,
                'enabled' => ($page_settings_model->get_settings('eael_ext_reading_progress_global') == 'yes' ? true : false),
                'display_condition' => $page_settings_model->get_settings('eael_ext_reading_progress_global_display_condition'),
                'position' => $page_settings_model->get_settings('eael_ext_reading_progress_position'),
                'height' => $page_settings_model->get_settings('eael_ext_reading_progress_height'),
                'bg_color' => $page_settings_model->get_settings('eael_ext_reading_progress_bg_color'),
                'fill_color' => $page_settings_model->get_settings('eael_ext_reading_progress_fill_color'),
                'animation_speed' => $page_settings_model->get_settings('eael_ext_reading_progress_animation_speed'),
            ];
        } else {
            if(isset($global_settings['reading_progress']['post_id']) && $global_settings['reading_progress']['post_id'] == $post_id) {
                $global_settings['reading_progress'] = [];
            }
        }

        //save table of content global value
        if($page_settings_model->get_settings('eael_ext_toc_global') == 'yes' && $page_settings_model->get_settings('eael_ext_table_of_content') == 'yes') {
            $typography_fields = [
                'font_family',
                'font_weight',
                'text_transform',
                'font_style',
                'text_decoration',
                'font_size',
                'letter_spacing',
                'line_height'
            ];
            $global_settings['eael_ext_table_of_content'] = [
                'post_id'                                           => $post_id,
                'enabled'                                           => ($page_settings_model->get_settings('eael_ext_toc_global') == 'yes' ? true : false),
                'display_condition'                                 => $page_settings_model->get_settings('eael_ext_toc_global_display_condition'),
                'eael_ext_toc_title'                                => $page_settings_model->get_settings('eael_ext_toc_title'),
                'eael_ext_toc_position'                             => $page_settings_model->get_settings('eael_ext_toc_position'),
                'eael_ext_toc_supported_heading_tag'                => $page_settings_model->get_settings('eael_ext_toc_supported_heading_tag'),
                'eael_ext_toc_collapse_sub_heading'                 => $page_settings_model->get_settings( 'eael_ext_toc_collapse_sub_heading'),
                'eael_ext_toc_word_wrap'                            => $page_settings_model->get_settings( 'eael_ext_toc_word_wrap'),
                'eael_ext_toc_box_shadow'                           => $page_settings_model->get_settings( 'eael_ext_toc_box_shadow'),
                'eael_ext_toc_table_box_shadow_box_shadow'          => $page_settings_model->get_settings( 'eael_ext_toc_table_box_shadow_box_shadow'),
                'eael_ext_toc_auto_collapse'                        => $page_settings_model->get_settings( 'eael_ext_toc_auto_collapse'),
                'eael_ext_toc_border_border'                        => $page_settings_model->get_settings( 'eael_ext_toc_border_border'),
                'eael_ext_toc_border_width'                         => $page_settings_model->get_settings( 'eael_ext_toc_border_width'),
                'eael_ext_toc_border_color'                         => $page_settings_model->get_settings( 'eael_ext_toc_border_color'),
                'eael_ext_toc_box_border_radius'                    => $page_settings_model->get_settings( 'eael_ext_toc_box_border_radius'),
                'eael_ext_toc_sticky_offset'                        => $page_settings_model->get_settings( 'eael_ext_toc_sticky_offset'),
                'eael_ext_toc_sticky_scroll'                        => $page_settings_model->get_settings( 'eael_ext_toc_sticky_scroll'),
                'eael_ext_toc_sticky_z_index'                       => $page_settings_model->get_settings( 'eael_ext_toc_sticky_z_index'),

                //toc header setting
                'eael_ext_table_of_content_header_bg'                   => $page_settings_model->get_settings('eael_ext_table_of_content_header_bg'),
                'eael_ext_table_of_content_header_text_color'           => $page_settings_model->get_settings('eael_ext_table_of_content_header_text_color'),
                'eael_ext_table_of_content_header_icon'                 => $page_settings_model->get_settings('eael_ext_table_of_content_header_icon'),
                'eael_ext_toc_header_padding'                           => $page_settings_model->get_settings('eael_ext_toc_header_padding'),

                //close button setting
                'eael_ext_table_of_content_close_button_bg'         => $page_settings_model->get_settings('eael_ext_table_of_content_close_button_bg'),
                'eael_ext_table_of_content_close_button_text_color' => $page_settings_model->get_settings('eael_ext_table_of_content_close_button_text_color'),
                'eael_ext_toc_close_button_text_style'              => $page_settings_model->get_settings('eael_ext_toc_close_button_text_style'),

                //toc body setting
                'eael_ext_table_of_content_body_bg'                 => $page_settings_model->get_settings('eael_ext_table_of_content_body_bg'),
                'eael_ext_toc_body_padding'                         => $page_settings_model->get_settings('eael_ext_toc_body_padding'),

                //list style setting
                'eael_ext_table_of_content_list_style'              => $page_settings_model->get_settings('eael_ext_table_of_content_list_style'),
                'eael_ext_toc_top_level_space'                      => $page_settings_model->get_settings('eael_ext_toc_top_level_space'),
                'eael_ext_toc_subitem_level_space'                  => $page_settings_model->get_settings('eael_ext_toc_subitem_level_space'),
                'eael_ext_toc_list_icon'                            => $page_settings_model->get_settings('eael_ext_toc_list_icon'),
                'eael_ext_table_of_content_list_text_color'         => $page_settings_model->get_settings('eael_ext_table_of_content_list_text_color'),
                'eael_ext_table_of_content_list_text_color_active'  => $page_settings_model->get_settings('eael_ext_table_of_content_list_text_color_active'),
                'eael_ext_table_of_list_hover_color'  => $page_settings_model->get_settings('eael_ext_table_of_list_hover_color'),
                'eael_ext_table_of_content_list_separator_style'    => $page_settings_model->get_settings('eael_ext_table_of_content_list_separator_style'),
                'eael_ext_table_of_content_list_separator_color'    => $page_settings_model->get_settings('eael_ext_table_of_content_list_separator_color'),
                'eael_ext_toc_box_list_bullet_size'                 => $page_settings_model->get_settings('eael_ext_toc_box_list_bullet_size'),
                'eael_ext_toc_box_list_top_position'                => $page_settings_model->get_settings('eael_ext_toc_box_list_top_position'),
                'eael_ext_toc_indicator_size'                       => $page_settings_model->get_settings('eael_ext_toc_indicator_size'),
                'eael_ext_toc_indicator_position'                   => $page_settings_model->get_settings('eael_ext_toc_indicator_position'),
            ];
            foreach ($typography_fields as $typography_field){
                $header_fields_attr = 'eael_ext_table_of_content_header_typography_'.$typography_field;
                $list_fields_attr   = 'eael_ext_table_of_content_list_typography_normal_'.$typography_field;
                $global_settings['eael_ext_table_of_content'][$header_fields_attr]   = $page_settings_model->get_settings($header_fields_attr);
                $global_settings['eael_ext_table_of_content'][$list_fields_attr]     = $page_settings_model->get_settings($list_fields_attr);
            }
        } else {
            if(isset($global_settings['eael_ext_table_of_content']['post_id']) && $global_settings['eael_ext_table_of_content']['post_id'] == $post_id) {
                $global_settings['eael_ext_table_of_content'] = [];
            }
        }

        update_option('eael_global_settings', $global_settings );
    }
}
