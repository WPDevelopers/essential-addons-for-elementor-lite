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
            __('Essential Addons', 'Essential Addons'),
            __('Essential Addons', 'essential-addons-elementor'),
            'manage_options',
            'eael-settings',
            [$this, 'eael_admin_settings_page'],
            $this->safe_protocol(EAEL_PLUGIN_URL . '/assets/admin/images/ea-icon-white.svg'),
            '58.6'
        );
    }

    /**
     * Loading all essential scripts
     *
     * @since 1.1.2
     */
    public function admin_enqueue_scripts($hook)
    {
        wp_enqueue_style('essential_addons_elementor-notice-css', EAEL_PLUGIN_URL . '/assets/admin/css/notice.css', false, EAEL_PLUGIN_VERSION);

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
						<h2 class="title"><?php echo __('Essential Addons Settings', 'essential-addons-elementor'); ?></h2>
					</div>
					<div class="eael-header-right">
					<button type="submit" class="button eael-btn js-eael-settings-save"><?php echo __('Save settings', 'essential-addons-elementor'); ?></button>
					</div>
				</div>
			  	<div class="eael-settings-tabs">
			    	<ul class="eael-tabs">
				      	<li><a href="#general" class="active"><img src="<?php echo EAEL_PLUGIN_URL . '/assets/admin/images/icon-general.svg'; ?>" alt="essential-addons-general-settings"><span><?php echo __('General', 'essential-addons-elementor'); ?></span></a></li>
				      	<li><a href="#elements"><img src="<?php echo EAEL_PLUGIN_URL . '/assets/admin/images/icon-elements.svg'; ?>" alt="essential-addons-elements"><span><?php echo __('Elements', 'essential-addons-elementor'); ?></span></a></li>
                        <li><a href="#extensions"><img src="<?php echo EAEL_PLUGIN_URL . '/assets/admin/images/icon-extensions.svg'; ?>" alt="essential-addons-extensions"><span><?php echo __('Extensions', 'essential-addons-elementor'); ?></span></a></li>
                        <li><a href="#tools"><img src="<?php echo EAEL_PLUGIN_URL . '/assets/admin/images/icon-tools.svg'; ?>" alt="essential-addons-tools"><span><?php echo __('Tools', 'essential-addons-elementor'); ?></span></a></li>
                        <?php if (!$this->pro_enabled) {?>
                            <li><a href="#go-pro"><img src="<?php echo EAEL_PLUGIN_URL . '/assets/admin/images/icon-upgrade.svg'; ?>" alt="essential-addons-go-pro"><span><?php echo __('Go Premium', 'essential-addons-elementor'); ?></span></a></li>
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

        // update new settings
        $updated = update_option('eael_save_settings', array_merge(array_fill_keys($this->get_registered_elements(), 0), array_map(function ($value) {return 1;}, $settings)));

        // Saving Google Map Api Key
        update_option('eael_save_google_map_api', @$settings['google-map-api']);

        // Saving Mailchimp Api Key
        update_option('eael_save_mailchimp_api', @$settings['mailchimp-api']);
        
        // Saving Duplicator Settings
        update_option('eael_save_post_duplicator_post_type', @$settings['post-duplicator-post-type']);

        // Build assets files
        $this->generate_scripts(array_keys($settings));

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
        $notice->maybe_later_time = '7 Day';

        $notice->text_domain = 'essential-addons-elementor';

        $scheme = (parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY)) ? '&' : '?';
        $url = $_SERVER['REQUEST_URI'] . $scheme;
        $notice->links = [
            'review' => array(
                'later' => array(
                    'link' => 'https://wpdeveloper.net/review-essential-addons-elementor',
                    'target' => '_blank',
                    'label' => __('Ok, you deserve it!', 'essential-addons-elementor'),
                    'icon_class' => 'dashicons dashicons-external',
                ),
                'allready' => array(
                    'link' => $url,
                    'label' => __('I already did', 'essential-addons-elementor'),
                    'icon_class' => 'dashicons dashicons-smiley',
                    'data_args' => [
                        'dismiss' => true,
                    ],
                ),
                'maybe_later' => array(
                    'link' => $url,
                    'label' => __('Maybe Later', 'essential-addons-elementor'),
                    'icon_class' => 'dashicons dashicons-calendar-alt',
                    'data_args' => [
                        'later' => true,
                    ],
                ),
                'support' => array(
                    'link' => 'https://wpdeveloper.net/support',
                    'label' => __('I need help', 'essential-addons-elementor'),
                    'icon_class' => 'dashicons dashicons-sos',
                ),
                'never_show_again' => array(
                    'link' => $url,
                    'label' => __('Never show again', 'essential-addons-elementor'),
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
        $notice->message('review', '<p>' . __('We hope you\'re enjoying Essential Addons for Elementor! Could you please do us a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?', 'essential-addons-elementor') . '</p>');
        $notice->thumbnail('review', plugins_url('assets/admin/images/icon-ea-logo.svg', EAEL_PLUGIN_BASENAME));
        /**
         * This is upsale notice settings
         * classes for wrapper, 
         * Message message for showing.
         */
        $notice->classes( 'upsale', 'notice is-dismissible ' );
        $notice->message( 'upsale', '<p>'. __( '7000+ People already using <a href="https://wpdeveloper.net/ea/notificationX" target="_blank">NotificationX</a> to increase their Sales & Engagement!', $notice->text_domain ) .'</p>' );
        $notice->thumbnail( 'upsale', plugins_url( 'assets/admin/images/nx-icon.svg', EAEL_PLUGIN_BASENAME ) );

        // Update Notice For PRO Version
        if( $this->pro_enabled && \version_compare( EAEL_PRO_PLUGIN_VERSION, '3.3.0', '<' ) ) {
            $notice->classes( 'update', 'notice is-dismissible ' );
            $notice->message( 'update', '<p>'. __( 'You are using an incompatible version of Essential Addons PRO. Please update to v3.3.0+. <a href="https://essential-addons.com/elementor/docs/manually-update-essential-addons-pro/" target="_blank">Follow manual update guide.</a>', $notice->text_domain ) .'</p>' );
            $notice->thumbnail( 'update', plugins_url( 'assets/admin/images/icon-ea-logo.svg', EAEL_PLUGIN_BASENAME ) );
        }

        $notice->upsale_args = array(
            'slug'      => 'notificationx',
            'page_slug' => 'nx-builder',
            'file'      => 'notificationx.php',
            'btn_text'  => __( 'Install Free', 'essential-addons-elementor' ),
            'condition' => [
                'by' => 'class',
                'class' => 'NotificationX'
            ],
        );

        $notice->options_args = array(
            'notice_will_show' => [
                'opt_in' => $notice->timestamp,
                'upsale' => $notice->makeTime($notice->timestamp, '1 Day'),
                'review' => $notice->makeTime($notice->timestamp, '3 Day'), // after 3 days
            ],
        );
        if( $this->pro_enabled && \version_compare( EAEL_PRO_PLUGIN_VERSION, '3.3.0', '<' ) ) { 
            $notice->options_args['notice_will_show']['update'] = $notice->timestamp;
        }

        $notice->init();
    }

    public function admin_bar($wp_admin_bar) {
        if(is_admin()) {
            return;
        }

        if(!$this->get_settings('quick_tools')) {
            return;
        }

        $wp_admin_bar->add_node([
            'id'    => 'ea-wp-admin-bar',
            'meta'  => [
                'class' => 'ea-wp-admin-bar'
            ],
            'title' => 'EA Tools'
        ]);

        $wp_admin_bar->add_node([
            'parent'    => 'ea-wp-admin-bar',
            'id'    => 'ea-all-cache-clear',
            'href'  => '#',
            'meta'  => [
                'class' => 'ea-all-cache-clear'
            ],
            'title' => 'Clear All Cache'
        ]);

        $wp_admin_bar->add_node([
            'parent'    => 'ea-wp-admin-bar',
            'id'    => 'ea-clear-cache-'.get_queried_object_id(),
            'href'  => '#',
            'meta'  => [
                'class' => 'ea-clear-cache',
                'html'   => '<div class="ea-clear-cache-id" data-pageid="'.get_queried_object_id().'">'
            ],
            'title' => 'Clear Page Cache'
        ]);

        
    }
}
