<?php
/**
 * This is the class that sends all the data back to the home site
 * It also handles opting in and deactivation
 * @version 1.1.2
 */

namespace Essential_Addons_Elementor\Classes;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly.

class Plugin_Usage_Tracker
{

    private $wpins_version = '1.1.3';
    private $home_url = '';
    private $plugin_file = '';
    private $plugin_name = '';
    private $options = array();
    private $require_optin = true;
    private $include_goodbye_form = true;
    private $marketing = false;
    private $collect_email = false;
    private $pro_plugin_name = 'Essential Addons for Elementor Pro';

    /**
     * Class constructor
     *
     * @param $_home_url                The URL to the site we're sending data to
     * @param $_plugin_file                The file path for this plugin
     * @param $_options                    Plugin options to track
     * @param $_require_optin            Whether user opt-in is required (always required on WordPress.org)
     * @param $_include_goodbye_form    Whether to include a form when the user deactivates
     * @param $_marketing                Marketing method:
     *                                    0: Don't collect email addresses
     *                                    1: Request permission same time as tracking opt-in
     *                                    2: Request permission after opt-in
     */
    public function __construct(
        $_plugin_file,
        $_home_url,
        $_options,
        $_require_optin = true,
        $_include_goodbye_form = true,
        $_marketing = false) {

        $this->plugin_file = $_plugin_file;
        $this->home_url = trailingslashit($_home_url);
        $this->plugin_name = basename($this->plugin_file, '.php');
        $this->options = $_options;
        $this->require_optin = $_require_optin;
        $this->include_goodbye_form = $_include_goodbye_form;
        $this->marketing = $_marketing;

        // Schedule some tracking when activated
        register_activation_hook($this->plugin_file, array($this, 'schedule_tracking'));
        // Deactivation hook
        register_deactivation_hook($this->plugin_file, array($this, 'deactivate_this_plugin'));

        // Get it going
        $this->init();

    }
    /**
     * Migrate to the new Insights
     * @return void
     */
    public function migrate_plan()
    {
        $old_key = array_flip(['wisdom_allow_tracking', 'wisdom_last_track_time', 'wisdom_block_notice', 'wisdom_collect_email', 'wisdom_admin_emails', 'wisdom_deactivation_reason_' . $this->plugin_name, 'wisdom_deactivation_details_' . $this->plugin_name]);
        $new_key = ['wpins_allow_tracking', 'wpins_last_track_time', 'wpins_block_notice', 'wpins_collect_email', 'wpins_admin_emails', 'wpins_deactivation_reason_' . $this->plugin_name, 'wpins_deactivation_details_' . $this->plugin_name];

        foreach ( $old_key as $key => $value) {
            $old_data = get_option( $key );
            if ( ! $old_data ) {
                continue;
            }
            update_option($new_key[$value], $old_data);
            delete_option( $key );
        }
    }
    /**
     * This function is fired for one time in a life time!
     * if the data is not removed.
     * @return void
     */
    public function force_track_for_one_time()
    {
        $is_tracked = get_option('wpins_' . $this->plugin_name . '_force_tracked');
        if (!$is_tracked) {
            $this->do_tracking(true);
            update_option('wpins_' . $this->plugin_name . '_force_tracked', true);
        }
    }

    public function init()
    {
        $is_migrated = get_option('wpins_' . $this->plugin_name . '_migrated');

        if ( version_compare($this->wpins_version, '1.1.2', '>') && ! $is_migrated ) {
            $this->migrate_plan();
            update_option('wpins_' . $this->plugin_name . '_migrated', true);
        }
        // Check marketing
        if ($this->marketing == 3) {
            $this->set_can_collect_email(true, $this->plugin_name);
        }
        // Check whether opt-in is required
        // If not, then tracking is allowed
        if (!$this->require_optin) {
            $this->set_can_collect_email(true, $this->plugin_name);
            $this->set_is_tracking_allowed(true);
            $this->update_block_notice();
            $this->do_tracking(true);
        }
        // Hook our do_tracking function to the daily action
        add_action('wpdeveloper_notice_clicked_for_' . $this->plugin_name, array($this, 'clicked'));

        add_action('put_do_weekly_action', array($this, 'do_tracking'));

        // Use this action for local testing and for one time force tracking in a life time.
        add_action('admin_init', array($this, 'force_track_for_one_time'));
        // add_action( 'admin_init', array( $this, 'force_tracking' ) );

        // Display the admin notice on activation
        add_action('wpdeveloper_optin_notice_for_' . $this->plugin_name, array($this, 'optin_notice'));
        add_action('admin_notices', array($this, 'marketing_notice'));

        // Deactivation
        add_filter('plugin_action_links_' . plugin_basename($this->plugin_file), array($this, 'filter_action_links'));
        add_action('admin_footer-plugins.php', array($this, 'goodbye_ajax'));
        add_action('wp_ajax_goodbye_form_' . esc_attr($this->plugin_name), array($this, 'goodbye_form_callback'));

    }

    /**
     * When the plugin is activated
     * Create scheduled event
     * And check if tracking is enabled - perhaps the plugin has been reactivated
     *
     * @since 1.0.0
     */
    public function schedule_tracking()
    {
        // For historical reasons, this is called 'weekly' but is in fact daily
        if (!wp_next_scheduled('put_do_weekly_action')) {
            wp_schedule_event(time(), 'daily', 'put_do_weekly_action');
        }
    }
    /**
     * This function is responsible for force tracking the plugin,
     * if users are allowed to do!
     *
     * @return void
     */
    public function force_tracking()
    {
        $this->do_tracking(true);
    }

    /**
     * This is our function to get everything going
     * Check that user has opted in
     * Collect data
     * Then send it back
     *
     * @since 1.0.0
     * @param $force    Force tracking if it's not time
     */
    public function do_tracking($force = false)
    {
        // If the home site hasn't been defined, we just drop out. Nothing much we can do.
        if (!$this->home_url) {
            return;
        }

        // Check to see if the user has opted in to tracking
        $allow_tracking = $this->get_is_tracking_allowed();
        if (!$allow_tracking) {
            return;
        }

        // Check to see if it's time to track
        $track_time = $this->get_is_time_to_track();
        if (!$track_time && !$force) {
            return;
        }

        $this->set_admin_email();

        // Get our data
        $body = $this->get_data();

        // Send the data
        $this->send_data($body);
    }

    /**
     * Send the data to the home site
     *
     * @since 1.0.0
     */
    public function send_data($body)
    {

        $request = wp_remote_post(
            esc_url($this->home_url . '?usage_tracker=hello'),
            array(
                'method' => 'POST',
                'timeout' => 20,
                'redirection' => 5,
                'httpversion' => '1.1',
                'blocking' => true,
                'body' => $body,
                'user-agent' => 'PUT/1.0.0; ' . get_bloginfo('url'),
            )
        );

        $this->set_track_time();

        if (is_wp_error($request)) {
            return $request;
        }

    }

    /**
     * Here we collect most of the data
     *
     * @since 1.0.0
     */
    public function get_data()
    {

        // Use this to pass error messages back if necessary
        $body['message'] = '';

        // Use this array to send data back
        $body = array(
            'plugin_slug' => sanitize_text_field($this->plugin_name),
            'url' => get_bloginfo('url'),
            'site_name' => get_bloginfo('name'),
            'site_version' => get_bloginfo('version'),
            'site_language' => get_bloginfo('language'),
            'charset' => get_bloginfo('charset'),
            'wpins_version' => $this->wpins_version,
            'php_version' => phpversion(),
            'multisite' => is_multisite(),
            'file_location' => __FILE__,
        );

        // Collect the email if the correct option has been set
        if ($this->get_can_collect_email()) {
            $body['email'] = $this->get_admin_email();
        }
        $body['marketing_method'] = $this->marketing;

        $body['server'] = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : '';

        // Retrieve current plugin information
        if (!function_exists('get_plugins')) {
            include ABSPATH . '/wp-admin/includes/plugin.php';
        }

        $plugins = array_keys(get_plugins());
        $active_plugins = get_option('active_plugins', array());

        foreach ($plugins as $key => $plugin) {
            if (in_array($plugin, $active_plugins)) {
                // Remove active plugins from list so we can show active and inactive separately
                unset($plugins[$key]);
            }
        }

        $body['active_plugins'] = $active_plugins;
        $body['inactive_plugins'] = $plugins;

        // Check text direction
        $body['text_direction'] = 'LTR';
        if (function_exists('is_rtl')) {
            if (is_rtl()) {
                $body['text_direction'] = 'RTL';
            }
        } else {
            $body['text_direction'] = 'not set';
        }

        /**
         * Get our plugin data
         * Currently we grab plugin name and version
         * Or, return a message if the plugin data is not available
         * @since 1.0.0
         */
        $plugin = $this->plugin_data();
        if (empty($plugin)) {
            // We can't find the plugin data
            // Send a message back to our home site
            $body['message'] .= __('We can\'t detect any plugin information. This is most probably because you have not included the code in the plugin main file.', 'plugin-usage-tracker');
            $body['status'] = 'Data not found'; // Never translated
        } else {
            if (isset($plugin['Name'])) {
                $body['plugin'] = sanitize_text_field($plugin['Name']);
            }
            if (isset($plugin['Version'])) {
                $body['version'] = sanitize_text_field($plugin['Version']);
            }
            $body['status'] = 'Active'; // Never translated
        }

        /**
         * Get our plugin options
         * @since 1.0.0
         */
        $options = $this->options;
        $plugin_options = array();
        if (!empty($options) && is_array($options)) {
            foreach ($options as $option) {
                $fields = get_option($option);
                // Check for permission to send this option
                if (isset($fields['wpins_registered_setting'])) {
                    foreach ($fields as $key => $value) {
                        $plugin_options[$key] = $value;
                    }
                }
            }
        }
        $body['plugin_options'] = $this->options; // Returns array
        $body['plugin_options_fields'] = $plugin_options; // Returns object

        /**
         * Get our theme data
         * Currently we grab theme name and version
         * @since 1.0.0
         */
        $theme = wp_get_theme();
        if ($theme->Name) {
            $body['theme'] = sanitize_text_field($theme->Name);
        }
        if ($theme->Version) {
            $body['theme_version'] = sanitize_text_field($theme->Version);
        }

        // Return the data
        return $body;

    }

    /**
     * Return plugin data
     * @since 1.0.0
     */
    public function plugin_data()
    {
        // Being cautious here
        if (!function_exists('get_plugin_data')) {
            include ABSPATH . '/wp-admin/includes/plugin.php';
        }
        // Retrieve current plugin information
        $plugin = get_plugin_data($this->plugin_file);
        return $plugin;
    }

    /**
     * Deactivating plugin
     * @since 1.0.0
     */
    public function deactivate_this_plugin()
    {
        // Check to see if the user has opted in to tracking
        $allow_tracking = $this->get_is_tracking_allowed();
        if (!$allow_tracking) {
            return;
        }
        $body = $this->get_data();
        $body['status'] = 'Deactivated'; // Never translated
        $body['deactivated_date'] = time();

        // Add deactivation form data
        if (false !== get_option('wpins_deactivation_reason_' . $this->plugin_name)) {
            $body['deactivation_reason'] = get_option('wpins_deactivation_reason_' . $this->plugin_name);
        }
        if (false !== get_option('wpins_deactivation_details_' . $this->plugin_name)) {
            $body['deactivation_details'] = get_option('wpins_deactivation_details_' . $this->plugin_name);
        }

        $this->send_data($body);
        // Clear scheduled update
        wp_clear_scheduled_hook('put_do_weekly_action');
    }

    /**
     * Is tracking allowed?
     * @since 1.0.0
     */
    public function get_is_tracking_allowed()
    {
        // First, check if the user has changed their mind and opted out of tracking
        if ($this->has_user_opted_out()) {
            $this->set_is_tracking_allowed(false, $this->plugin_name);
            return false;
        }
        // The wpins_allow_tracking option is an array of plugins that are being tracked
        $allow_tracking = get_option('wpins_allow_tracking');
        // If this plugin is in the array, then tracking is allowed
        if (isset($allow_tracking[$this->plugin_name])) {
            return true;
        }
        return false;
    }

    /**
     * Set if tracking is allowed
     * Option is an array of all plugins with tracking permitted
     * More than one plugin may be using the tracker
     * @since 1.0.0
     * @param $is_allowed    Boolean        true if tracking is allowed, false if not
     */
    public function set_is_tracking_allowed($is_allowed, $plugin = null)
    {
        if (empty($plugin)) {
            $plugin = $this->plugin_name;
        }
        // The wpins_allow_tracking option is an array of plugins that are being tracked
        $allow_tracking = get_option('wpins_allow_tracking');

        // If the user has decided to opt out
        if ($this->has_user_opted_out()) {
            if (isset($allow_tracking[$plugin])) {
                unset($allow_tracking[$plugin]);
            }
        } else if ($is_allowed || !$this->require_optin) {
            // If the user has agreed to allow tracking or if opt-in is not required
            if (empty($allow_tracking) || !is_array($allow_tracking)) {
                // If nothing exists in the option yet, start a new array with the plugin name
                $allow_tracking = array($plugin => $plugin);
            } else {
                // Else add the plugin name to the array
                $allow_tracking[$plugin] = $plugin;
            }
        } else {
            if (isset($allow_tracking[$plugin])) {
                unset($allow_tracking[$plugin]);
            }
        }
        update_option('wpins_allow_tracking', $allow_tracking);
    }

    /**
     * Has the user opted out of allowing tracking?
     * @since 1.1.0
     * @return Boolean
     */
    public function has_user_opted_out()
    {
        // Iterate through the options that are being tracked looking for wpins_opt_out setting
        if (!empty($this->options)) {
            foreach ($this->options as $option_name) {
                // Check each option
                $options = get_option($option_name);
                // If we find the setting, return true
                if (!empty($options['wpins_opt_out'])) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Check if it's time to track
     * @since 1.1.1
     */
    public function get_is_time_to_track()
    {
        // Let's see if we're due to track this plugin yet
        $track_times = get_option('wpins_last_track_time', array());
        if (!isset($track_times[$this->plugin_name])) {
            // If we haven't set a time for this plugin yet, then we must track it
            return true;
        } else {
            // If the time is set, let's see if it's more than a day ago
            if ($track_times[$this->plugin_name] < strtotime('-1 day')) {
                return true;
            }
        }
        return false;
    }

    /**
     * Record the time we send tracking data
     * @since 1.1.1
     */
    public function set_track_time()
    {
        // We've tracked, so record the time
        $track_times = get_option('wpins_last_track_time', array());
        // Set different times according to plugin, in case we are tracking multiple plugins
        $track_times[$this->plugin_name] = time();
        update_option('wpins_last_track_time', $track_times);
    }

    /**
     * Set if we should block the opt-in notice for this plugin
     * Option is an array of all plugins that have received a response from the user
     * @since 1.0.0
     */
    public function update_block_notice($plugin = null)
    {
        if (empty($plugin)) {
            $plugin = $this->plugin_name;
        }
        $block_notice = get_option('wpins_block_notice');
        if (empty($block_notice) || !is_array($block_notice)) {
            // If nothing exists in the option yet, start a new array with the plugin name
            $block_notice = array($plugin => $plugin);
        } else {
            // Else add the plugin name to the array
            $block_notice[$plugin] = $plugin;
        }
        update_option('wpins_block_notice', $block_notice);
    }

    /**
     * Can we collect the email address?
     * @since 1.0.0
     */
    public function get_can_collect_email()
    {
        // The wpins_collect_email option is an array of plugins that are being tracked
        $collect_email = get_option('wpins_collect_email');
        // If this plugin is in the array, then we can collect the email address
        if (isset($collect_email[$this->plugin_name])) {
            return true;
        }
        return false;
    }

    /**
     * Set if user has allowed us to collect their email address
     * Option is an array of all plugins with email collection permitted
     * More than one plugin may be using the tracker
     * @since 1.0.0
     * @param $can_collect    Boolean        true if collection is allowed, false if not
     */
    public function set_can_collect_email($can_collect, $plugin = null)
    {
        if (empty($plugin)) {
            $plugin = $this->plugin_name;
        }
        // The wpins_collect_email option is an array of plugins that are being tracked
        $collect_email = get_option('wpins_collect_email');
        // If the user has agreed to allow tracking or if opt-in is not required
        if ($can_collect) {
            if (empty($collect_email) || !is_array($collect_email)) {
                // If nothing exists in the option yet, start a new array with the plugin name
                $collect_email = array($plugin => $plugin);
            } else {
                // Else add the plugin name to the array
                $collect_email[$plugin] = $plugin;
            }
        } else {
            if (isset($collect_email[$plugin])) {
                unset($collect_email[$plugin]);
            }
        }
        update_option('wpins_collect_email', $collect_email);
    }

    /**
     * Get the correct email address to use
     * @since 1.1.2
     * @return Email address
     */
    public function get_admin_email()
    {
        // The wpins_collect_email option is an array of plugins that are being tracked
        $email = get_option('wpins_admin_emails');
        // If this plugin is in the array, then we can collect the email address
        if (isset($email[$this->plugin_name])) {
            return $email[$this->plugin_name];
        }
        return false;
    }

    /**
     * Set the correct email address to use
     * There might be more than one admin on the site
     * So we only use the first admin's email address
     * @param $email    Email address to set
     * @param $plugin    Plugin name to set email address for
     * @since 1.1.2
     */
    public function set_admin_email($email = null, $plugin = null)
    {
        if (empty($plugin)) {
            $plugin = $this->plugin_name;
        }
        // If no email address passed, try to get the current user's email
        if (empty($email)) {
            // Have to check that current user object is available
            if (function_exists('wp_get_current_user')) {
                $current_user = wp_get_current_user();
                $email = $current_user->user_email;
            }
        }
        // The wpins_admin_emails option is an array of admin email addresses
        $admin_emails = get_option('wpins_admin_emails');
        if (empty($admin_emails) || !is_array($admin_emails)) {
            // If nothing exists in the option yet, start a new array with the plugin name
            $admin_emails = array($plugin => sanitize_email($email));
        } else if (empty($admin_emails[$plugin])) {
            // Else add the email address to the array, if not already set
            $admin_emails[$plugin] = sanitize_email($email);
        }
        update_option('wpins_admin_emails', $admin_emails);
    }

    public function clicked()
    {
        // Check for plugin args
        if (isset($_GET['plugin']) && isset($_GET['plugin_action'])) {
            $plugin = sanitize_text_field($_GET['plugin']);
            $action = sanitize_text_field($_GET['plugin_action']);
            if ($action == 'yes') {
                $this->set_is_tracking_allowed(true, $plugin);
                $this->do_tracking(true); // Run this straightaway
            } else {
                $this->set_is_tracking_allowed(false, $plugin);
            }
            $this->update_block_notice($plugin);
        }
    }

    /**
     * Display the admin notice to users to allow them to opt in
     *
     * @since 1.0.0
     */
    public function optin_notice()
    {
        // Check whether to block the notice, e.g. because we're in a local environment
        // wpins_block_notice works the same as wpins_allow_tracking, an array of plugin names
        $block_notice = get_option('wpins_block_notice');
        if (isset($block_notice[$this->plugin_name])) {
            return;
        }

        if (!current_user_can('manage_options')) {
            return;
        }

        // @credit EDD
        // Don't bother asking user to opt in if they're in local dev
        $is_local = false;
        if (stristr(network_site_url('/'), '.dev') !== false || stristr(network_site_url('/'), 'localhost') !== false || stristr(network_site_url('/'), ':8888') !== false) {
            $is_local = true;
        }
        $is_local = apply_filters('wpins_is_local_' . $this->plugin_name, $is_local);
        if ($is_local) {
            $this->update_block_notice();
        } else {

            // Display the notice requesting permission to track
            // Retrieve current plugin information
            $plugin = $this->plugin_data();
            $plugin_name = $plugin['Name'];

            // Args to add to query if user opts in to tracking
            $yes_args = array(
                'plugin' => $this->plugin_name,
                'plugin_action' => 'yes',
            );

            // Decide how to request permission to collect email addresses
            if ($this->marketing == 1) {
                // Option 1 combines permissions to track and collect email
                $yes_args['marketing_optin'] = 'yes';
            } else if ($this->marketing == 2) {
                // Option 2 enables a second notice that fires after the user opts in to tracking
                $yes_args['marketing'] = 'yes';
            }
            $url_yes = add_query_arg($yes_args);
            $url_no = add_query_arg(array(
                'plugin' => $this->plugin_name,
                'plugin_action' => 'no',
            ));

            // Decide on notice text
            if ($this->marketing != 1) {
                // Standard notice text
                $notice_text = __('Thank you for installing our plugin. We would like to track its usage on your site. We don\'t record any sensitive data, only information regarding the WordPress environment and plugin settings, which we will use to help us make improvements to the plugin. Tracking is completely optional.', 'plugin-usage-tracker');
            } else {
                // If we have option 1 for marketing, we include reference to sending product information here
                $notice_text = __('Want to help make <strong>Essential Addons for Elementor</strong> even more awesome? You can get a <strong>10% discount coupon</strong> for Pro upgrade if you allow. <a class="insights-data-we-collect" href="#">What we collect.</a>', 'plugin-usage-tracker');
            }
            // And we allow you to filter the text anyway
            $notice_text = apply_filters('wpins_notice_text_' . esc_attr($this->plugin_name), $notice_text);?>

			<div class="notice notice-info updated put-dismiss-notice">
				<p><?php echo __($notice_text); ?></p>
				<div class="eael-insights-data" style="display: none;">
					<p><?php echo __('We collect non-sensitive diagnostic data and plugin usage information. Your site URL, WordPress & PHP version, plugins & themes and email address to send you the discount coupon. This data lets us make sure this plugin always stays compatible with the most popular plugins and themes. No spam, I promise.'); ?></p>
				</div>
				<p>
					<a href="<?php echo esc_url($url_yes); ?>" class="button-primary"><?php _e('Sure, I\'d like to help', 'plugin-usage-tracker');?></a>
					<a href="<?php echo esc_url($url_no); ?>" class="button-secondary"><?php _e('No Thanks', 'plugin-usage-tracker');?></a>
				</p>
				<?php echo "<script type='text/javascript'>jQuery('.insights-data-we-collect').on('click', function(e) {
						e.preventDefault();
						jQuery('.eael-insights-data').slideToggle('fast');
					});
					</script>"; ?>
			</div>
		<?php
}
    }
    /**
     * Display the marketing notice to users if enabled
     * Only displays after the user has opted in to tracking
     *
     * @since 1.0.0
     */
    public function marketing_notice()
    {
        // Check if user has opted in to marketing
        if (isset($_GET['marketing_optin'])) {
            // Set marketing optin
            $this->set_can_collect_email(sanitize_text_field($_GET['marketing_optin']), $this->plugin_name);
            // Do tracking
            $this->do_tracking(true);
        } else if (isset($_GET['marketing']) && $_GET['marketing'] == 'yes') {
            // Display the notice requesting permission to collect email address
            // Retrieve current plugin information
            $plugin = $this->plugin_data();
            $plugin_name = $plugin['Name'];

            $url_yes = add_query_arg(array(
                'plugin' => $this->plugin_name,
                'marketing_optin' => 'yes',
            ));
            $url_no = add_query_arg(array(
                'plugin' => $this->plugin_name,
                'marketing_optin' => 'no',
            ));

            $marketing_text = __('Thank you for opting in to tracking. Would you like to receive occasional news about this plugin, including details of new features and special offers?', 'plugin-usage-tracker');
            $marketing_text = apply_filters('wpins_marketing_text_' . esc_attr($this->plugin_name), $marketing_text);?>

			<div class="notice notice-info updated put-dismiss-notice">
				<p><?php echo '<strong>' . esc_html($plugin_name) . '</strong>'; ?></p>
				<p><?php echo esc_html($marketing_text); ?></p>
				<p>
					<a href="<?php echo esc_url($url_yes); ?>" data-putnotice="yes" class="button-secondary"><?php _e('Yes Please', 'plugin-usage-tracker');?></a>
					<a href="<?php echo esc_url($url_no); ?>" data-putnotice="no" class="button-secondary"><?php _e('No Thank You', 'plugin-usage-tracker');?></a>
				</p>
			</div>
			<?php }
    }

    /**
     * Filter the deactivation link to allow us to present a form when the user deactivates the plugin
     * @since 1.0.0
     */
    public function filter_action_links($links)
    {
        // Check to see if the user has opted in to tracking
        if (!$this->get_is_tracking_allowed()) {
            return $links;
        }
        if (isset($links['deactivate']) && $this->include_goodbye_form) {
            $deactivation_link = $links['deactivate'];

            // Insert an onClick action to allow form before deactivating
            $deactivation_link = str_replace('<a ', '<div class="wpdev-put-goodbye-form-wrapper-' . esc_attr($this->plugin_name) . '"><div class="wpdev-put-goodbye-form-bg-' . esc_attr($this->plugin_name) . '"></div><span class="wpdev-put-goodbye-form" id="wpdev-put-goodbye-form-' . esc_attr($this->plugin_name) . '"></span></div><a onclick="javascript:event.preventDefault();" id="wpdev-put-goodbye-link-' . esc_attr($this->plugin_name) . '" ', $deactivation_link);
            $links['deactivate'] = $deactivation_link;
        }
        return $links;
    }

    /*
     * Form text strings
     * These are non-filterable and used as fallback in case filtered strings aren't set correctly
     * @since 1.0.0
     */
    public function form_default_text()
    {
        $form = array();
        $form['heading'] = __('Sorry to see you go', 'plugin-usage-tracker');
        $form['body'] = __('Before you deactivate the plugin, would you quickly give us your reason for doing so?', 'plugin-usage-tracker');

        $form['options'] = array(
            __('I no longer need the plugin', 'plugin-usage-tracker'),
            [
                'label' => __('I found a better plugin', 'plugin-usage-tracker'),
                'extra_field' => __('Please share which plugin', 'plugin-usage-tracker'),
            ],
            __("I couldn't get the plugin to work", 'plugin-usage-tracker'),
            __('It\'s a temporary deactivation', 'plugin-usage-tracker'),
            __('I have ' . $this->pro_plugin_name, 'plugin-usage-tracker'),
            [
                'label' => __('Other', 'plugin-usage-tracker'),
                'extra_field' => __('Please share the reason', 'plugin-usage-tracker'),
                'type' => 'textarea',
            ],
        );

        return $form;
    }

    /**
     * Form text strings
     * These can be filtered
     * The filter hook must be unique to the plugin
     * @since 1.0.0
     */
    public function form_filterable_text()
    {
        $form = $this->form_default_text();
        return apply_filters('wpins_form_text_' . esc_attr($this->plugin_name), $form);
    }

    /**
     * Form text strings
     * These can be filtered
     * @since 1.0.0
     */
    public function goodbye_ajax()
    {
        // Get our strings for the form
        $form = $this->form_filterable_text();
        if (!isset($form['heading']) || !isset($form['body']) || !isset($form['options']) || !is_array($form['options']) || !isset($form['details'])) {
            // If the form hasn't been filtered correctly, we revert to the default form
            $form = $this->form_default_text();
        }
        // Build the HTML to go in the form
        $html = '<div class="wpdev-put-goodbye-form-head"><strong>' . esc_html($form['heading']) . '</strong></div>';
        $html .= '<div class="wpdev-put-goodbye-form-body"><p class="wpdev-put-goodbye-form-caption">' . esc_html($form['body']) . '</p>';
        if (is_array($form['options'])) {
            $html .= '<div id="wpdev-' . esc_attr($this->plugin_name) . '-goodbye-options" class="wpdev-' . esc_attr($this->plugin_name) . '-goodbye-options"><ul>';
            foreach ($form['options'] as $option) {
                if (is_array($option)) {
                    $id = strtolower(str_replace(" ", "_", esc_attr($option['label'])));
                    $id = $id . '_' . esc_attr($this->plugin_name);
                    $html .= '<li class="has-goodbye-extra">';
                    $html .= '<input type="radio" name="wpdev-' . esc_attr($this->plugin_name) . '-goodbye-options" id="' . $id . '" value="' . esc_attr($option['label']) . '">';
                    $html .= '<div><label for="' . $id . '">' . esc_attr($option['label']) . '</label>';
                    if (isset($option['extra_field']) && !isset($option['type'])) {
                        $html .= '<input type="text" style="display: none" name="' . $id . '" id="' . str_replace(" ", "", esc_attr($option['extra_field'])) . '" placeholder="' . esc_attr($option['extra_field']) . '">';
                    }
                    if (isset($option['extra_field']) && isset($option['type'])) {
                        $html .= '<' . $option['type'] . ' style="display: none" type="text" name="' . $id . '" id="' . str_replace(" ", "", esc_attr($option['extra_field'])) . '" placeholder="' . esc_attr($option['extra_field']) . '"></' . $option['type'] . '>';
                    }
                    $html .= '</div></li>';
                } else {
                    $id = strtolower(str_replace(" ", "_", esc_attr($option)));
                    $id = $id . '_' . esc_attr($this->plugin_name);
                    $html .= '<li><input type="radio" name="wpdev-' . esc_attr($this->plugin_name) . '-goodbye-options" id="' . $id . '" value="' . esc_attr($option) . '"> <label for="' . $id . '">' . esc_attr($option) . '</label></li>';
                }
            }
            $html .= '</ul></div><!-- .wpdev-' . esc_attr($this->plugin_name) . '-goodbye-options -->';
        }
        $html .= '</div><!-- .wpdev-put-goodbye-form-body -->';
        $html .= '<p class="deactivating-spinner"><span class="spinner"></span> ' . __('Submitting form', 'plugin-usage-tracker') . '</p>';
        ?>
		<style type="text/css">
			.wpdev-put-form-active-<?php echo esc_attr($this->plugin_name); ?> .wpdev-put-goodbye-form-bg-<?php echo esc_attr($this->plugin_name); ?> {
				background: rgba( 0, 0, 0, .8 );
				position: fixed;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				z-index: 9;
			}
			.wpdev-put-goodbye-form-wrapper-<?php echo esc_attr($this->plugin_name); ?> {
				position: relative;
				display: none;
			}
			.wpdev-put-form-active-<?php echo esc_attr($this->plugin_name); ?> .wpdev-put-goodbye-form-wrapper-<?php echo esc_attr($this->plugin_name); ?> {
				display: flex !important;
				align-items: center;
				justify-content: center;
				width: 100%;
				height: 100%;
				position: fixed;
				left: 0px;
				top: 0px;
			}
			.wpdev-put-goodbye-form {
				display: none;
			}
			.wpdev-put-form-active-<?php echo esc_attr($this->plugin_name); ?> .wpdev-put-goodbye-form {
				position: relative !important;
				width: 550px;
				max-width: 80%;
				background: #fff;
				box-shadow: 2px 8px 23px 3px rgba(0,0,0,.2);
				border-radius: 3px;
				white-space: normal;
				overflow: hidden;
				display: block;
				z-index: 999999;
			}
			.wpdev-put-goodbye-form-head {
				background: #fff;
				color: #495157;
				padding: 18px;
				box-shadow: 0 0 8px rgba(0,0,0,.1);
				font-size: 15px;
			}
			.wpdev-put-goodbye-form .wpdev-put-goodbye-form-head strong {
				font-size: 15px;
			}
			.wpdev-put-goodbye-form-body {
				padding: 8px 18px;
				color: #333;
			}
			.wpdev-put-goodbye-form-body label {
				color: #6d7882;
				padding-left: 5px;
			}
			.wpdev-put-goodbye-form-body .wpdev-put-goodbye-form-caption {
				font-weight: 500;
				font-size: 15px;
				color: #495157;
				line-height: 1.4;
			}
			.wpdev-put-goodbye-form-body #wpdev-<?php echo esc_attr($this->plugin_name); ?>-goodbye-options {
				padding-top: 5px;
			}
			.wpdev-put-goodbye-form-body #wpdev-<?php echo esc_attr($this->plugin_name); ?>-goodbye-options ul > li {
				margin-bottom: 15px;
			}
			.deactivating-spinner {
				display: none;
					padding-bottom: 20px !important;
			}
			.deactivating-spinner .spinner {
				float: none;
				margin: 4px 4px 0 18px;
				vertical-align: bottom;
				visibility: visible;
			}
			.wpdev-put-goodbye-form-footer {
				padding: 8px 18px;
				margin-bottom: 15px;
			}
			.wpdev-put-goodbye-form-footer > .wpdev-put-goodbye-form-buttons {
				display: flex;
				align-items: center;
				justify-content: space-between;
			}
			.wpdev-put-goodbye-form-footer .eael-put-submit-btn {
				background-color: #d30c5c;
				-webkit-border-radius: 3px;
				border-radius: 3px;
				color: #fff;
				line-height: 1;
				padding: 15px 20px;
				font-size: 13px;
			}
			.wpdev-put-goodbye-form-footer .eael-put-deactivate-btn {
				font-size: 13px;
				color: #a4afb7;
				background: none;
				float: right;
				padding-right: 10px;
				width: auto;
				text-decoration: underline;
			}
			#wpdev-<?php echo esc_attr($this->plugin_name); ?>-goodbye-options ul li > div {
				display: inline;
				padding-left: 3px;
			}
			#wpdev-<?php echo esc_attr($this->plugin_name); ?>-goodbye-options ul li > div > input, #wpdev-<?php echo esc_attr($this->plugin_name); ?>-goodbye-options ul li > div > textarea {
				margin: 10px 18px;
				padding: 8px;
				width: 80%;
			}
		</style>
		<script>
			jQuery(document).ready(function($){
				$("#wpdev-put-goodbye-link-<?php echo esc_attr($this->plugin_name); ?>").on("click",function(){
					// We'll send the user to this deactivation link when they've completed or dismissed the form
					var url = document.getElementById("wpdev-put-goodbye-link-<?php echo esc_attr($this->plugin_name); ?>");
					$('body').toggleClass('wpdev-put-form-active-<?php echo esc_attr($this->plugin_name); ?>');
					$("#wpdev-put-goodbye-form-<?php echo esc_attr($this->plugin_name); ?>").fadeIn();
					$("#wpdev-put-goodbye-form-<?php echo esc_attr($this->plugin_name); ?>").html( '<?php echo $html; ?>' + '<div class="wpdev-put-goodbye-form-footer"><div class="wpdev-put-goodbye-form-buttons"><a id="put-submit-form-<?php echo esc_attr($this->plugin_name); ?>" class="eael-put-submit-btn" href="#"><?php _e('Submit and Deactivate', 'plugin-usage-tracker');?></a>&nbsp;<a class="eael-put-deactivate-btn" href="'+url+'"><?php _e('Just Deactivate', 'plugin-usage-tracker');?></a></div></div>');
					$('#put-submit-form-<?php echo esc_attr($this->plugin_name); ?>').on('click', function(e){
						// As soon as we click, the body of the form should disappear
						$("#wpdev-put-goodbye-form-<?php echo esc_attr($this->plugin_name); ?> .wpdev-put-goodbye-form-body").fadeOut();
						$("#wpdev-put-goodbye-form-<?php echo esc_attr($this->plugin_name); ?> .wpdev-put-goodbye-form-footer").fadeOut();
						// Fade in spinner
						$("#wpdev-put-goodbye-form-<?php echo esc_attr($this->plugin_name); ?> .deactivating-spinner").fadeIn();
						e.preventDefault();
						var checkedInput = $("input[name='wpdev-<?php echo esc_attr($this->plugin_name); ?>-goodbye-options']:checked"),
							checkedInputVal, details;
						if( checkedInput.length > 0 ) {
							checkedInputVal = checkedInput.val();
							details = $('input[name="'+ checkedInput[0].id +'"], textarea[name="'+ checkedInput[0].id +'"]').val();
						}

						if( typeof details === 'undefined' ) {
							details = '';
						}
						if( typeof checkedInputVal === 'undefined' ) {
							checkedInputVal = 'No Reason';
						}

						var data = {
							'action': 'goodbye_form_<?php echo esc_attr($this->plugin_name); ?>',
							'values': checkedInputVal,
							'details': details,
							'security': "<?php echo wp_create_nonce('wpins_goodbye_form'); ?>",
							'dataType': "json"
						}

						$.post(
							ajaxurl,
							data,
							function(response){
								// Redirect to original deactivation URL
								window.location.href = url;
							}
						);
					});
					$('#wpdev-<?php echo esc_attr($this->plugin_name); ?>-goodbye-options > ul ').on('click', 'li label, li > input', function( e ){
						var parent = $(this).parents('li');
						parent.siblings().find('label').next('input, textarea').css('display', 'none');
						parent.find('label').next('input, textarea').css('display', 'block');
					});
					// If we click outside the form, the form will close
					$('.wpdev-put-goodbye-form-bg-<?php echo esc_attr($this->plugin_name); ?>').on('click',function(){
						$("#wpdev-put-goodbye-form-<?php echo esc_attr($this->plugin_name); ?>").fadeOut();
						$('body').removeClass('wpdev-put-form-active-<?php echo esc_attr($this->plugin_name); ?>');
					});
				});


			});
		</script>
	<?php }

    /**
     * AJAX callback when the form is submitted
     * @since 1.0.0
     */
    public function goodbye_form_callback()
    {
        check_ajax_referer('wpins_goodbye_form', 'security');
        if (isset($_POST['values'])) {
            $values = $_POST['values'];
            update_option('wpins_deactivation_reason_' . $this->plugin_name, $values);
        }
        if (isset($_POST['details'])) {
            $details = sanitize_text_field($_POST['details']);
            update_option('wpins_deactivation_details_' . $this->plugin_name, $details);
        }
        $this->do_tracking(); // Run this straightaway
        echo 'success';
        wp_die();
    }

}