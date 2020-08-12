<?php
namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit();
}
// Exit if accessed directly

use Essential_Addons_Elementor\Classes\WPDeveloper_Notice;

trait Admin
{
    /**
     * Create an admin menu.
     *
     * @since 1.1.2
     */
    public function admin_menu()
    {
        add_menu_page(
            __('Essential Addons', 'essential-addons-for-elementor-lite'),
            __('Essential Addons', 'essential-addons-for-elementor-lite'),
            'manage_options',
            'eael-settings',
            [$this, 'eael_admin_settings_page'],
            $this->safe_protocol(EAEL_PLUGIN_URL . 'assets/admin/images/ea-icon-white.svg'),
            '58.6'
        );
        $plugins = \get_option('active_plugins');
        if (!in_array('templately/templately.php', $plugins)) {
            add_submenu_page(
                'eael-settings',
                __('Templates Cloud', 'essential-addons-for-elementor-lite'),
                __('Templates Cloud', 'essential-addons-for-elementor-lite'),
                'manage_options',
                'template-cloud',
                [$this, 'templately_page']
            );
        }
    }
    /**
     * Template Page Outputs
     *
     * @return void
     */
    public function templately_page()
    {
        $plugin_name = basename(EAEL_PLUGIN_BASENAME, '.php');
        $button_text = __('Install Templately', 'essential-addons-for-elementor-lite');
        if (!function_exists('get_plugins')) {
            include ABSPATH . '/wp-admin/includes/plugin.php';
        }
        $plugins = \get_plugins();
        $installed = false;
        if (isset($plugins['templately/templately.php'])) {
            $installed = true;
            $button_text = __('Activate Templately', 'essential-addons-for-elementor-lite');
        }

        ?>
            <div class="wrap">
                <hr class="wp-header-end">
                <div class="template-cloud">
                    <div class="template-cloud-header">
                        <svg width="200" enable-background="new 0 0 200 50" viewBox="0 0 200 50" xmlns="http://www.w3.org/2000/svg"><circle cx="5.5" cy="44.1" fill="#ffb45a" r="5.5"/><circle cx="21.6" cy="44.1" fill="#ff7b8e" r="5.5"/><circle cx="37.6" cy="44.1" fill="#5ac0ff" r="5.5"/><path d="m31.3 9.3c-.3 0-.6 0-.9 0-1.2-5.3-5.9-9.2-11.5-9.2-1.5 0-2.9.3-4.2.8v3.9h5.7v5.6c-1.9 0-3.8 0-5.7 0v9.5h5.9c-.2.4-.4 1-.5 1.3-1.1 2.6-3.7 4-6.5 3.5-2.6-.5-4.5-2.7-4.7-5.5 0-.8 0-1.6 0-2.4 0-1.2 0-5.2 0-6.5-.7 0-1.3 0-2 0-4 1.9-6.9 6-6.9 10.7 0 6.5 5.3 11.8 11.8 11.8h19.6c6.5-.1 11.7-5.3 11.7-11.8 0-6.4-5.3-11.7-11.8-11.7z" fill="#5633d1"/><g fill="#424c5e"><path d="m64.6 29.5c-1.2 0-1.9-.7-1.9-2.1v-7h3.4v-3.1h-3.8l-.6-3h-2.9v14.2c0 2.8 1.3 4.1 3.8 4.1h3.4v-3.1z"/><path d="m75.3 17.3c-4.8 0-7.2 2.5-7.2 7.5 0 5.2 2.8 7.8 8.3 7.8 1.9 0 3.6-.1 4.9-.4v-3.1c-1.5.3-3.1.4-4.6.4-3.2 0-4.8-1.1-4.8-3.2h10.2c.1-.6.1-1.3.1-1.9.1-4.7-2.3-7.1-6.9-7.1zm-3.3 6.3c.2-2.2 1.3-3.3 3.3-3.3 2.1 0 3.2 1.1 3.2 3.2v.1z"/><path d="m102.4 17.3c-1.7 0-3.3.7-4.9 2.1-.7-1.4-2-2.1-4-2.1-1.9 0-3.6.7-4.9 2.2l-.5-2.2h-3v15.3h3.9v-10.6c1-1 2.1-1.5 3.2-1.5 1.4 0 2.1.9 2.1 2.6v9.6h3.9v-10.6c1.1-1 2.2-1.5 3.3-1.5 1.5 0 2.3.8 2.3 2.6v9.6h3.9v-9.5c0-4.1-1.8-6-5.3-6z"/><path d="m117.8 17.3c-2.3 0-4.4.2-6.4.6v21.1h3.9v-7.1c1 .5 2.1.7 3.1.7 4.8 0 7.3-2.7 7.3-8-.1-4.9-2.7-7.3-7.9-7.3zm.5 12.1c-1.1 0-2.1-.3-3.1-.8v-8c.7-.1 1.6-.2 2.7-.2 2.5 0 3.8 1.4 3.8 4.1.1 3.3-1.1 4.9-3.4 4.9z"/><path d="m128.5 11.7h3.9v20.8h-3.9z"/><path d="m142.1 17.3c-1.7 0-3.5.2-5.6.7v3.1c2-.5 3.9-.7 5.6-.7 2 0 3 .7 3 2.1v1.2c-1-.2-2.1-.3-3.1-.3-4.4 0-6.6 1.5-6.6 4.6 0 3.2 1.9 4.8 5.6 4.8 1.6 0 3.1-.5 4.4-1.4l1.4 1.4h2.2v-10.3c-.1-3.6-2.4-5.2-6.9-5.2zm-.6 12.5c-1.6 0-2.3-.7-2.3-2s.9-1.9 2.8-1.9c1.1 0 2.1.1 3.1.3v2.4c-1.2.8-2.4 1.2-3.6 1.2z"/><path d="m158 29.5c-1.2 0-1.9-.7-1.9-2.1v-7h3.4v-3.1h-3.8l-.6-3h-2.9v14.2c0 2.8 1.3 4.1 3.8 4.1h3.4v-3.1z"/><path d="m168.7 17.3c-4.8 0-7.2 2.5-7.2 7.5 0 5.2 2.8 7.8 8.3 7.8 1.9 0 3.6-.1 4.9-.4v-3.1c-1.5.3-3.1.4-4.6.4-3.2 0-4.8-1.1-4.8-3.2h10.2c.1-.6.1-1.3.1-1.9.1-4.8-2.3-7.1-6.9-7.1zm-3.3 6.3c.2-2.2 1.3-3.3 3.3-3.3 2.1 0 3.2 1.1 3.2 3.2v.1z"/><path d="m178.6 11.7h3.9v20.8h-3.9z"/><path d="m196 17.2-3.8 11-3.9-11h-4.1l5.9 15.4c-.8 1.7-2 2.9-3.7 3.7l1.9 2.6c2.5-1.2 4.3-3.2 5.4-5.9l6.3-15.8z"/></g></svg>
                    </div> <!-- Logo -->
                    <div class="template-cloud-body">
                        <div class="template-cloud-install">
                            <div class="templately-left">
                                <div class="templately-cloud-title">
                                    <h1><?php echo __( 'Explore 100+ Free Templates', 'essential-addons-for-elementor-lite' ); ?></h1>
                                    <p><?php echo __( 'From multipurpose themes to niche templates, you’ll always find something that catches your eye.', 'essential-addons-for-elementor-lite' ); ?></p>
                                </div>
                            </div>
                            <div class="templately-installer-wrapper">
                                <div class="templately-left">
                                    <div class="templately-admin-title">
                                        <div class="templately-cloud-video-container"><iframe height="350" src="https://www.youtube.com/embed/coLxfjnrm3I" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
                                    </div>
                                </div>
                                <div class="templately-right">
                                    <div class="templately-admin-install">
                                        <p><?php echo __( 'Install Templately by Essential Addons to get access to the templates library and cloud.', 'essential-addons-for-elementor-lite' ); ?></p>
                                        <button class="eae-activate-templately"><?php echo $button_text; ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    ( function( $ ){
                        $( document ).ready(function( $ ){
                            $('body').on('click', '.eae-activate-templately', function( e ){
                                var self = $(this);
                                self.text('<?php echo !$installed ? esc_js('Installing...') : esc_js('Activating...'); ?>');
                                e.preventDefault();
                                $.ajax({
                                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                    type: 'POST',
                                    data: {
                                        action: 'wpdeveloper_upsale_core_install_<?php echo $plugin_name; ?>',
                                        _wpnonce: '<?php echo wp_create_nonce('wpdeveloper_upsale_core_install_' . $plugin_name); ?>',
                                        slug : 'templately',
                                        file : 'templately.php'
                                    },
                                    complete: function() {
                                        self.attr('disabled', 'disabled');
                                        self.removeClass('install-now updating-message');
                                    }
                                }).done(function(){
                                    self.text('<?php echo esc_js('Installed'); ?>').delay(3000);
                                    window.location.href = '<?php echo admin_url("admin.php?page=templately"); ?>';
                                }).fail(function(){
                                    self.removeClass('install-now updating-message');
                                });
                            });
                        });
                    })( jQuery );
                </script>
            </div>
        <?php
}
    /**
     * Loading all essential scripts
     *
     * @since 1.1.2
     */
    public function admin_enqueue_scripts($hook)
    {
        wp_enqueue_style('essential_addons_elementor-notice-css', EAEL_PLUGIN_URL . '/assets/admin/css/notice.css', false, EAEL_PLUGIN_VERSION);

        if ($hook == 'essential-addons_page_template-cloud') {
            wp_enqueue_style('essential_addons_elementor-template-cloud-css', EAEL_PLUGIN_URL . '/assets/admin/css/cloud.css', false, EAEL_PLUGIN_VERSION);
        }

        if (isset($hook) && $hook == 'toplevel_page_eael-settings') {
            wp_enqueue_style('essential_addons_elementor-admin-css', EAEL_PLUGIN_URL . '/assets/admin/css/admin.css', false, EAEL_PLUGIN_VERSION);
            if ($this->pro_enabled) {
                wp_enqueue_style('eael_pro-admin-css', EAEL_PRO_PLUGIN_URL . '/assets/admin/css/admin.css', false, EAEL_PRO_PLUGIN_VERSION);
            }
            wp_enqueue_style('sweetalert2-css', EAEL_PLUGIN_URL . '/assets/admin/vendor/sweetalert2/css/sweetalert2.min.css', false, EAEL_PLUGIN_VERSION);
            wp_enqueue_script('sweetalert2-js', EAEL_PLUGIN_URL . '/assets/admin/vendor/sweetalert2/js/sweetalert2.min.js', array('jquery', 'sweetalert2-core-js'), EAEL_PLUGIN_VERSION, true);
            wp_enqueue_script('sweetalert2-core-js', EAEL_PLUGIN_URL . '/assets/admin/vendor/sweetalert2/js/core.js', array('jquery'), EAEL_PLUGIN_VERSION, true);
            wp_enqueue_script('essential_addons_elementor-admin-js', EAEL_PLUGIN_URL . '/assets/admin/js/admin.js', array('jquery'), EAEL_PLUGIN_VERSION, true);

            wp_localize_script('essential_addons_elementor-admin-js', 'localize', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('essential-addons-elementor'),
            ));
        }
    }

    /**
     * Create settings page.
     *
     * @since 1.1.2
     */
    public function eael_admin_settings_page()
    {
        ?>
        <div class="eael-settings-wrap">
		  	<form action="" method="POST" id="eael-settings" name="eael-settings">
		  		<div class="eael-header-bar">
					<div class="eael-header-left">
						<div class="eael-admin-logo-inline">
							<img src="<?php echo EAEL_PLUGIN_URL . '/assets/admin/images/icon-ea-logo.svg'; ?>" alt="essential-addons-for-elementor">
						</div>
						<h2 class="title"><?php echo __('Essential Addons Settings', 'essential-addons-for-elementor-lite'); ?></h2>
					</div>
					<div class="eael-header-right">
					<button type="submit" class="button eael-btn js-eael-settings-save"><?php echo __('Save settings', 'essential-addons-for-elementor-lite'); ?></button>
					</div>
				</div>
			  	<div class="eael-settings-tabs">
			    	<ul class="eael-tabs">
				      	<li><a href="#general" class="active"><img src="<?php echo EAEL_PLUGIN_URL . '/assets/admin/images/icon-general.svg'; ?>" alt="essential-addons-general-settings"><span><?php echo __('General', 'essential-addons-for-elementor-lite'); ?></span></a></li>
				      	<li><a href="#elements"><img src="<?php echo EAEL_PLUGIN_URL . '/assets/admin/images/icon-elements.svg'; ?>" alt="essential-addons-elements"><span><?php echo __('Elements', 'essential-addons-for-elementor-lite'); ?></span></a></li>
                        <li><a href="#extensions"><img src="<?php echo EAEL_PLUGIN_URL . '/assets/admin/images/icon-extensions.svg'; ?>" alt="essential-addons-extensions"><span><?php echo __('Extensions', 'essential-addons-for-elementor-lite'); ?></span></a></li>
                        <li><a href="#tools"><img src="<?php echo EAEL_PLUGIN_URL . '/assets/admin/images/icon-tools.svg'; ?>" alt="essential-addons-tools"><span><?php echo __('Tools', 'essential-addons-for-elementor-lite'); ?></span></a></li>
                        <?php if (!$this->pro_enabled) {?>
                            <li><a href="#go-pro"><img src="<?php echo EAEL_PLUGIN_URL . '/assets/admin/images/icon-upgrade.svg'; ?>" alt="essential-addons-go-pro"><span><?php echo __('Go Premium', 'essential-addons-for-elementor-lite'); ?></span></a></li>
                        <?php }?>
                    </ul>
                    <?php
include_once EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'includes/templates/admin/general.php';
        include_once EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'includes/templates/admin/elements.php';
        include_once EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'includes/templates/admin/extensions.php';
        include_once EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'includes/templates/admin/tools.php';
        if (!$this->pro_enabled) {
            include_once EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'includes/templates/admin/go-pro.php';
        }
        ?>
                </div>
            </form>
        </div>
        <?php
}

    /**
     * Saving data with ajax request
     * @param
     * @return  array
     * @since 1.1.2
     */
    public function save_settings()
    {
        check_ajax_referer('essential-addons-elementor', 'security');

        if (!isset($_POST['fields'])) {
            return;
        }

        parse_str($_POST['fields'], $settings);

        // Saving Google Map Api Key
        update_option('eael_save_google_map_api', @$settings['google-map-api']);

        // Saving Mailchimp Api Key
        update_option('eael_save_mailchimp_api', @$settings['mailchimp-api']);

        // Saving TYpeForm token
        update_option('eael_save_typeform_personal_token', @$settings['typeform-personal-token']);

        // Saving Duplicator Settings
        update_option('eael_save_post_duplicator_post_type', @$settings['post-duplicator-post-type']);

        // save js print method
        update_option('eael_js_print_method', @$settings['eael-js-print-method']);

        $defaults = array_fill_keys(array_keys(array_merge($this->registered_elements, $this->registered_extensions)), false);
        $elements = array_merge($defaults, array_fill_keys(array_keys(array_intersect_key($settings, $defaults)), true));

        // update new settings
        $updated = update_option('eael_save_settings', $elements);

        // clear assets files
        $this->empty_dir(EAEL_ASSET_PATH);

        wp_send_json($updated);
    }

    public function admin_notice()
    {
        $notice = new WPDeveloper_Notice(EAEL_PLUGIN_BASENAME, EAEL_PLUGIN_VERSION);
        /**
         * Current Notice End Time.
         * Notice will dismiss in 3 days if user does nothing.
         */
        $notice->cne_time = '3 Day';
        /**
         * Current Notice Maybe Later Time.
         * Notice will show again in 7 days
         */
        $notice->maybe_later_time = '21 Day';

        $scheme = (parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY)) ? '&' : '?';
        $url = $_SERVER['REQUEST_URI'] . $scheme;
        $notice->links = [
            'review' => array(
                'later' => array(
                    'link' => 'https://wpdeveloper.net/review-essential-addons-elementor',
                    'target' => '_blank',
                    'label' => __('Ok, you deserve it!', 'essential-addons-for-elementor-lite'),
                    'icon_class' => 'dashicons dashicons-external',
                ),
                'allready' => array(
                    'link' => $url,
                    'label' => __('I already did', 'essential-addons-for-elementor-lite'),
                    'icon_class' => 'dashicons dashicons-smiley',
                    'data_args' => [
                        'dismiss' => true,
                    ],
                ),
                'maybe_later' => array(
                    'link' => $url,
                    'label' => __('Maybe Later', 'essential-addons-for-elementor-lite'),
                    'icon_class' => 'dashicons dashicons-calendar-alt',
                    'data_args' => [
                        'later' => true,
                    ],
                ),
                'support' => array(
                    'link' => 'https://wpdeveloper.net/support',
                    'label' => __('I need help', 'essential-addons-for-elementor-lite'),
                    'icon_class' => 'dashicons dashicons-sos',
                ),
                'never_show_again' => array(
                    'link' => $url,
                    'label' => __('Never show again', 'essential-addons-for-elementor-lite'),
                    'icon_class' => 'dashicons dashicons-dismiss',
                    'data_args' => [
                        'dismiss' => true,
                    ],
                ),
            ),
        ];

        /**
         * This is review message and thumbnail.
         */
        $notice->message('review', '<p>' . __('We hope you\'re enjoying Essential Addons for Elementor! Could you please do us a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?', 'essential-addons-for-elementor-lite') . '</p>');
        $notice->thumbnail('review', plugins_url('assets/admin/images/icon-ea-logo.svg', EAEL_PLUGIN_BASENAME));
        /**
         * This is upsale notice settings
         * classes for wrapper,
         * Message message for showing.
         */
        // $notice->classes('upsale', 'notice is-dismissible ');
        // $notice->message('upsale', '<p>' . __('5,000+ People using <a href="https://betterdocs.co/wordpress-plugin" target="_blank">BetterDocs</a> to create better Documentation & Knowledge Base!', 'essential-addons-for-elementor-lite') . '</p>');
        // $notice->thumbnail('upsale', plugins_url('assets/admin/images/icon-documentation.svg', EAEL_PLUGIN_BASENAME));

        // Update Notice For PRO Version
        if ($this->pro_enabled && \version_compare(EAEL_PRO_PLUGIN_VERSION, '4.0.0', '<')) {
            $notice->classes('update', 'notice is-dismissible ');
            $notice->message('update', '<p>' . __('You are using an incompatible version of Essential Addons PRO. Please update to v4.0.0+. If you do not see automatic update, <a href="https://essential-addons.com/elementor/docs/manually-update-essential-addons-pro/" target="_blank">Follow manual update guide.</a>', 'essential-addons-for-elementor-lite') . '</p>');
            $notice->thumbnail('update', plugins_url('assets/admin/images/icon-ea-logo.svg', EAEL_PLUGIN_BASENAME));
        }

        // $notice->upsale_args = array(
        //     'slug' => 'betterdocs',
        //     'page_slug' => 'betterdocs-setup',
        //     'file' => 'betterdocs.php',
        //     'btn_text' => __('Install Free', 'essential-addons-for-elementor-lite'),
        //     'condition' => [
        //         'by' => 'class',
        //         'class' => 'BetterDocs',
        //     ],
        // );
        $notice->options_args = array(
            'notice_will_show' => [
                'opt_in' => $notice->timestamp,
                'review' => $notice->makeTime($notice->timestamp, '7 Day'), // after 3 days
            ],
        );
        if ($this->pro_enabled && \version_compare(EAEL_PRO_PLUGIN_VERSION, '4.0.0', '<')) {
            $notice->options_args['notice_will_show']['update'] = $notice->timestamp;
        }

        $notice->init();
    }
}
