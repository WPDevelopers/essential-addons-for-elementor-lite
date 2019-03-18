<?php
namespace Essential_Addons_Elementor\Admin;

/**
 * Admin Settings Page
 */

if (!defined('ABSPATH')) {
    exit();
}
// Exit if accessed directly

/**
 * Contains Default Component keys
 * @var array
 * @since 2.3.0
 */

trait Admin
{
    protected $is_pro = false;

    public function get_registered_elements()
    {
        return $this->registered_elements; // filter output later...
    }

    public function get_settings($element = null)
    {
        $elements = get_option('eael_save_settings', array_fill_keys($this->registered_elements, true));

        if ($element) {
            return $elements[$element];
        }

        return $elements;
    }

    // public function add_action_with_ajax()
    // {
    //     global $wp_version;
    //     $post_types = [];
    //     $remoteargs = array(
    //         'timeout' => 5,
    //         'redirection' => 5,
    //         'httpversion' => '1.0',
    //         'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url(),
    //         'blocking' => true,
    //         'headers' => array(),
    //         'cookies' => array(),
    //         'sslverify' => false,
    //     );
    //     $otherurl = $_POST['url'];

    //     $otherurl = $otherurl . 'wp-json/wp/v2/types';

    //     $response = wp_remote_get($otherurl, $remoteargs);
    //     $response = json_decode($response['body']);
    //     // echo '<pre>', print_r( $response, 1 ), '</pre>';
    //     foreach ($response as $type) {
    //         $post_types[$type->rest_base] = $type->name;
    //     }
    //     $eael_exclude_cpts = array('elementor_library', 'media', 'product');
    //     foreach ($eael_exclude_cpts as $exclude_cpt) {
    //         unset($post_types[$exclude_cpt]);
    //     }
    //     // echo '<pre>', print_r( $post_types, 1 ), '</pre>';
    //     echo json_encode($post_types);

    //     add_action('wp_ajax_save_facebook_feed_settings', array($this, 'eael_save_facebook_feed_settings'));

    // }

    /**
     * Loading all essential scripts
     * @param
     * @return void
     * @since 1.1.2
     */
    public function admin_enqueue_scripts($hook)
    {
        wp_enqueue_style('essential_addons_elementor-notice-css', plugins_url('/', __FILE__) . 'assets/css/eael-notice.css');

        if (isset($hook) && $hook == 'plugins.php') {
            wp_enqueue_style('essential_addons_elementor-sweetalert2-css', plugins_url('/', __FILE__) . 'assets/vendor/sweetalert2/css/sweetalert2.min.css');
            wp_enqueue_script('essential_addons_sweetalert2-js', plugins_url('/', __FILE__) . 'assets/vendor/sweetalert2/js/sweetalert2.min.js', array('jquery', 'essential_addons_core-js'), '1.0', true);
            wp_enqueue_script('essential_addons_core-js', plugins_url('/', __FILE__) . 'assets/vendor/sweetalert2/js/core.js', array('jquery'), '1.0', true);
        }

        if (isset($hook) && $hook == 'elementor_page_eael-settings') {
            wp_enqueue_style('essential_addons_elementor-admin-css', plugins_url('/', __FILE__) . 'assets/css/admin.css');
            wp_enqueue_style('essential_addons_elementor-sweetalert2-css', plugins_url('/', __FILE__) . 'assets/vendor/sweetalert2/css/sweetalert2.min.css');
            wp_enqueue_script('essential_addons_core-js', plugins_url('/', __FILE__) . 'assets/vendor/sweetalert2/js/core.js', array('jquery'), '1.0', true);
            wp_enqueue_script('essential_addons_sweetalert2-js', plugins_url('/', __FILE__) . 'assets/vendor/sweetalert2/js/sweetalert2.min.js', array('jquery', 'essential_addons_core-js'), '1.0', true);
            wp_enqueue_script('essential_addons_elementor-admin-js', plugins_url('/', __FILE__) . 'assets/js/admin.js', array('jquery'), '1.0', true);
            wp_localize_script('essential_addons_elementor-admin-js', 'localize', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('essential-addons-elementor'),
            ));
        }
    }

    /**
     * Create an admin menu.
     * @param
     * @return void
     * @since 1.1.2
     */
    public function admin_menu()
    {
        add_submenu_page(
            'elementor',
            'Essential Addons',
            'Essential Addons',
            'manage_options',
            'eael-settings',
            array($this, 'eael_admin_settings_page')
        );
    }

    /**
     * Create settings page.
     * @param
     * @return void
     * @since 1.1.2
     */
    public function eael_admin_settings_page()
    {
        // $this->eael_default_settings = array_fill_keys($this->eael_default_keys, true);
        // $this->eael_get_settings = get_option('eael_save_settings', $this->eael_default_settings);
        // $eael_new_settings = array_diff_key($this->eael_default_settings, $this->eael_get_settings);

        // if (!empty($eael_new_settings)) {
        //     $eael_updated_settings = array_merge($this->eael_get_settings, $eael_new_settings);
        //     update_option('eael_save_settings', $eael_updated_settings);
        // }
        // $this->eael_get_settings = get_option('eael_save_settings', $this->eael_default_settings);
        ?>
		<div class="eael-settings-wrap">
		  	<form action="" method="POST" id="eael-settings" name="eael-settings">
		  		<div class="eael-header-bar">
					<div class="eael-header-left">
						<div class="eael-admin-logo-inline">
							<img src="<?php echo plugins_url('/', __FILE__) . 'assets/images/ea-logo.svg'; ?>">
						</div>
						<h2 class="title"><?php _e('Essential Addons Settings', 'essential-addons-elementor');?></h2>
					</div>
					<div class="eael-header-right">
					<button type="submit" class="button eael-btn js-eael-settings-save"><?php _e('Save settings', 'essential-addons-elementor');?></button>
					</div>
				</div>
			  	<div class="eael-settings-tabs">
			    	<ul class="eael-tabs">
				      	<li><a href="#general" class="active"><img src="<?php echo plugins_url('/', __FILE__) . 'assets/images/icon-settings.svg'; ?>"><span>General</span></a></li>
				      	<li><a href="#elements"><img src="<?php echo plugins_url('/', __FILE__) . 'assets/images/icon-modules.svg'; ?>"><span>Elements</span></a></li>
						<li><a href="#extensions"><img src="<?php echo plugins_url('/', __FILE__) . 'assets/images/icon-extensions.svg'; ?>"><span>Extensions</span></a></li>
						<li><a href="#go-pro"><img src="<?php echo plugins_url('/', __FILE__) . 'assets/images/icon-upgrade.svg'; ?>"><span>Go Premium</span></a></li>
			    	</ul>
					<?php
					include 'partials/general.php';
					include 'partials/elements.php';
					include 'partials/extensions.php';
					include 'partials/go-pro.php';
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

        if (isset($_POST['fields'])) {
            parse_str($_POST['fields'], $settings);
        } else {
            return;
        }

        $updated = update_option('eael_save_settings', array_merge(array_fill_keys($this->get_registered_elements(), 0), array_map(function ($value) {return 1;}, $settings)));
        do_action('eael_generate_editor_scripts', array_keys($settings));

        wp_send_json($updated);
    }

}
