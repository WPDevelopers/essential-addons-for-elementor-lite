<?php

namespace Essential_Addons_Elementor\Classes;

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly.

class WPDeveloper_Setup_Wizard {
    public $templately_status;

    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'setup_wizard_scripts' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'wp_ajax_save_setup_wizard_data', [ $this, 'save_setup_wizard_data' ] );
        add_action( 'wp_ajax_save_eael_elements_data', [ $this, 'save_eael_elements_data' ] );
        add_action( 'in_admin_header', [ $this, 'remove_notice' ], 1000 );
        $this->templately_status = $this->templately_active_status();
    }

    /**
     * templately_active_status
     * @return bool
     */
    public function templately_active_status() {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        return is_plugin_active( 'templately/templately.php' );
    }

    /**
     * Remove all notice in setup wizard page
     */
    public function remove_notice() {
        if ( isset($_GET[ 'page' ]) && $_GET[ 'page' ] == 'eael-setup-wizard' ) {
            remove_all_actions( 'admin_notices' );
            remove_all_actions( 'all_admin_notices' );
        }
    }

    /**
     * setup_wizard_scripts
     * @param $hook
     * @return array
     */
    public function setup_wizard_scripts( $hook ) {
        if ( isset( $hook ) && $hook == 'admin_page_eael-setup-wizard' ) {
            wp_enqueue_style( 'essential_addons_elementor-setup-wizard-css', EAEL_PLUGIN_URL . 'assets/admin/css/admin.css', false, EAEL_PLUGIN_VERSION );
            wp_enqueue_style( 'sweetalert2-css', EAEL_PLUGIN_URL . 'assets/admin/vendor/sweetalert2/css/sweetalert2.min.css', false, EAEL_PLUGIN_VERSION );
            wp_enqueue_script( 'sweetalert2-js', EAEL_PLUGIN_URL . 'assets/admin/vendor/sweetalert2/js/sweetalert2.min.js', array( 'jquery', 'sweetalert2-core-js' ), EAEL_PLUGIN_VERSION, true );
            wp_enqueue_script( 'sweetalert2-core-js', EAEL_PLUGIN_URL . 'assets/admin/vendor/sweetalert2/js/core.js', array( 'jquery' ), EAEL_PLUGIN_VERSION, true );
            wp_enqueue_script( 'essential_addons_elementor-setup-wizard-js', EAEL_PLUGIN_URL . 'assets/admin/js/admin.js', array( 'jquery' ), EAEL_PLUGIN_VERSION, true );
            wp_localize_script( 'essential_addons_elementor-setup-wizard-js', 'localize', array(
                'ajaxurl'       => admin_url( 'admin-ajax.php' ),
                'nonce'         => wp_create_nonce( 'essential-addons-elementor' ),
                'success_image' => EAEL_PLUGIN_URL . 'assets/admin/images/success.gif',
            ) );
        }
        return [];
    }

    /**
     * Create admin menu for setup wizard
     */
    public function admin_menu() {

        add_submenu_page(
            null,
            __( 'Essential Addons ', 'essential-addons-for-elementor-lite' ),
            __( 'Essential Addons ', 'essential-addons-for-elementor-lite' ),
            'manage_options',
            'eael-setup-wizard',
            [ $this, 'render_wizard' ]
        );
    }

    /**
     * Render tav step
     */
    public function tab_step() {
        !$this->templately_status ? $wizard_column = 'five' : $wizard_column = 'four';
        ?>
        <ul class="eael-setup-wizard <?php echo $wizard_column; ?>" data-step="1">
            <li class="step">
                <div class="icon">
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                         xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                    <g>
                                        <path class="st0" d="M50,25c0-1.9-1.3-3.8-3-4.4c-1.6-0.6-3.2-2-3.7-3.1c-0.5-1.1-0.3-3.3,0.4-4.9c0.8-1.6,0.3-3.9-1-5.2
                                            c-1.3-1.3-3.7-1.8-5.2-1c-1.6,0.8-3.7,0.9-4.9,0.4C31.5,6.2,30,4.6,29.4,3c-0.6-1.7-2.6-3-4.4-3c-1.9,0-3.8,1.3-4.4,3
                                            c-0.6,1.7-2,3.3-3.1,3.7c-1.1,0.5-3.3,0.3-4.9-0.4C11,5.5,8.6,6,7.3,7.3C6,8.6,5.5,11,6.3,12.6c0.8,1.6,0.9,3.7,0.4,4.9
                                            C6.2,18.6,4.6,20,3,20.6c-1.7,0.6-3,2.6-3,4.4c0,1.9,1.3,3.8,3,4.4c1.7,0.6,3.2,2,3.7,3.1c0.5,1.1,0.3,3.3-0.4,4.9
                                            c-0.8,1.6-0.3,3.9,1,5.2c1.3,1.3,3.7,1.8,5.2,1c1.6-0.8,3.7-0.9,4.9-0.4c1.1,0.5,2.6,2.1,3.1,3.7c0.6,1.7,2.6,3,4.4,3
                                            c1.9,0,3.8-1.3,4.4-3c0.6-1.6,2-3.3,3.1-3.7c1.1-0.5,3.3-0.3,4.9,0.4c1.6,0.8,3.9,0.3,5.2-1c1.3-1.3,1.8-3.7,1-5.2
                                            c-0.8-1.6-0.9-3.7-0.4-4.9c0.5-1.1,2.1-2.6,3.7-3.1C48.7,28.8,50,26.9,50,25L50,25z M25,34.2c-5.1,0-9.2-4.1-9.2-9.2
                                            c0-5.1,4.1-9.2,9.2-9.2c5.1,0,9.2,4.1,9.2,9.2C34.2,30.1,30.1,34.2,25,34.2L25,34.2z M25,34.2"/>
                                    </g>
                                    </svg>
                </div>
                <div class="name"><?php _e( 'Configuration', 'essential-addons-for-elementor-lite' ); ?></div>
            </li>
            <li class="step">
                <div class="icon">
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                         xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                <g>
                                    <path class="st0" d="M18.8,4.2H2.1C0.9,4.2,0,5.1,0,6.3v16.7C0,24.1,0.9,25,2.1,25h16.7c1.2,0,2.1-0.9,2.1-2.1V6.3
    C20.8,5.1,19.9,4.2,18.8,4.2z"/>
                                    <path class="st0" d="M18.8,29.2H6.3c-1.2,0-2.1,0.9-2.1,2.1v12.5c0,1.2,0.9,2.1,2.1,2.1h12.5c1.2,0,2.1-0.9,2.1-2.1V31.3
    C20.8,30.1,19.9,29.2,18.8,29.2z"/>
                                    <path class="st0" d="M43.8,29.2H27.1c-1.2,0-2.1,0.9-2.1,2.1v16.7c0,1.2,0.9,2.1,2.1,2.1h16.7c1.2,0,2.1-0.9,2.1-2.1V31.3
    C45.8,30.1,44.9,29.2,43.8,29.2z"/>
                                    <path class="st0" d="M47.9,0H27.1C25.9,0,25,0.9,25,2.1v20.8c0,1.2,0.9,2.1,2.1,2.1h20.8c1.2,0,2.1-0.9,2.1-2.1V2.1
    C50,0.9,49.1,0,47.9,0z"/>
                                </g>
                            </svg>
                </div>
                <div class="name"><?php _e( 'Elements', 'essential-addons-for-elementor-lite' ); ?></div>
            </li>
            <?php if ( !$this->templately_status ): ?>
                <li class="step">
                    <div class="icon">
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                             xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                             viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                <g>
                                    <path class="st0" d="M9,38.9c7.3,0,7.3,11.1,0,11.1C1.7,50,1.7,38.9,9,38.9z"/>
                                    <path class="st0" d="M25.3,38.9c7.3,0,7.3,11.1,0,11.1C18,50,18,38.9,25.3,38.9z"/>
                                    <g>
                                        <path class="st0"
                                              d="M41.4,38.9c7.3,0,7.3,11.1,0,11.1C34.2,50,34.2,38.9,41.4,38.9z"/>
                                        <path class="st0" d="M35.1,9.3c-0.3,0-0.6,0-0.9,0c-1.4-6.9-9.3-11.2-15.9-8.5c0,1.5,0,3.2,0,3.9c1.5,0,4.4,0,5.8,0v5.7
        c-1.9,0-3.8,0-5.8,0c0,2,0,7.6,0,9.6c1.7,0,4,0,6,0c-1.5,6.8-11.3,6.4-11.8-0.7c0-2.5,0-6.4,0-9C-1,11.9,1.4,33.2,15.4,33
        c0.1,0,19.7,0,19.8,0C50.6,32.8,50.6,9.4,35.1,9.3z"/>
                                    </g>
                                </g>
                            </svg>
                    </div>
                    <div class="name"><?php _e( 'Templately', 'essential-addons-for-elementor-lite' ); ?></div>
                </li>
            <?php endif; ?>
            <li class="step">
                <div class="icon">
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                         xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                <path class="st0" d="M48.9,16.6c-0.7-0.7-1.6-1.1-2.6-1.1c-1,0-1.9,0.4-2.6,1.1l-8.8,8.8L24.7,15.1l8.8-8.8c0.7-0.7,1.1-1.6,1.1-2.6
c0-1-0.4-1.9-1.1-2.6C32.7,0.4,31.8,0,30.8,0c-1,0-1.9,0.4-2.6,1.1l-8.8,8.8l-5.6-5.6c-0.3-0.3-0.6-0.4-1-0.4c-0.4,0-0.7,0.2-1,0.4
c-8,8.8-3.7,28-3.2,29.9l-8.1,8.1c-0.3,0.2-0.4,0.6-0.4,0.9c0,0.4,0.1,0.7,0.4,0.9L5.5,49c0.3,0.3,0.6,0.4,0.9,0.4
c0.3,0,0.7-0.1,0.9-0.4l8.1-8.1c3.9,1,8.8,1.6,13.2,1.6c5.2,0,12.3-0.8,16.7-4.8c0.3-0.2,0.4-0.6,0.4-1c0-0.4-0.1-0.7-0.4-1
l-5.2-5.2l8.8-8.8C50.4,20.4,50.4,18,48.9,16.6z"/>
                            </svg>
                </div>
                <div class="name"><?php _e( 'Integrations', 'essential-addons-for-elementor-lite' ); ?></div>
            </li>
            <li class="step">
                <div class="icon">
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                         xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                                <path class="st0" d="M48.5,3.1l-0.3-0.3c-0.9-0.9-2.1-1.4-3.3-1.3c-1.2,0-2.4,0.6-3.3,1.5L16.1,30.9l-0.5,0.2l-0.5-0.2l-6.3-7.4
c-0.9-1.1-2.2-1.7-3.6-1.8c-1.4-0.1-2.8,0.5-3.8,1.5c-1.6,1.6-1.8,4.1-0.5,5.9l13.1,18.3c0.7,1,1.9,1.7,3.2,1.7h1.1
c2.2,0,4.2-1.1,5.4-2.8L49.1,9.5C50.5,7.5,50.2,4.8,48.5,3.1z"/>
                            </svg>
                </div>
                <div class="name"><?php _e( 'Finalize', 'essential-addons-for-elementor-lite' ); ?></div>
            </li>
        </ul>
        <?php
    }

    /**
     * Tav view content
     */
    public function tab_content() {
        ?>
        <div class="eael-setup-body">
            <form class="eael-setup-wizard-form" method="post">
                <div id="configuration" class="setup-content">
                    <div class="eael-input-group config-list">
                        <input id="basic"
                               value="basic"
                               class="eael_preferences" name="eael_preferences" type="radio" checked>
                        <label for="basic">
                            <div class="eael-radio-circle"></div>
                            <div class="eael-radio-text">
                                <strong><?php _e( 'Basic (Recommended)', 'essential-addons-for-elementor-lite' ); ?></strong>
                                <p> <?php _e( 'For websites where you want to only use the basic features and keep your site
                                    lightweight. Most basic elements are activated in this option. ', 'essential-addons-for-elementor-lite' ); ?></p>
                            </div>
                        </label>
                    </div>
                    <div class="eael-input-group config-list">
                        <input id="advance" value="advance"
                               class="eael_preferences"
                               name="eael_preferences"
                               type="radio">
                        <label for="advance">
                            <div class="eael-radio-circle"></div>
                            <div class="eael-radio-text">
                                <strong><?php _e( 'Advanced', 'essential-addons-for-elementor-lite' ) ?></strong>
                                <p> <?php _e( 'For advanced users who are trying to build complex websites with advanced
                                    functionalities with Elementor. All the dynamic elements will be activated in this
                                    option.', 'essential-addons-for-elementor-lite' ) ?> </p>
                            </div>
                        </label>
                    </div>
                    <div class="eael-input-group config-list">
                        <input id="custom" value="custom" class="eael_preferences" name="eael_preferences" name="radio"
                               type="radio">
                        <label for="custom">
                            <div class="eael-radio-circle"></div>
                            <div class="eael-radio-text">
                                <strong><?php _e( 'Custom', 'essential-addons-for-elementor-lite' ); ?></strong>
                                <p> <?php _e( 'Pick this option if you want to configure the elements as per your wish.', 'essential-addons-for-elementor-lite' ); ?> </p>
                            </div>
                        </label>
                    </div>
                </div>
                <?php $this->eael_elements(); ?>
                <?php if ( !$this->templately_status ): ?>
                    <div id="templately" class="setup-content eael-box eael-templately-popup" style="background-image:
                            url('<?php echo EAEL_PLUGIN_URL . 'assets/admin/images/templately.jpg'; ?>');">
                        <?php if ( !is_plugin_active( 'templately/templately.php' ) ) $this->eael_templately_plugin_popup(); ?>
                    </div>
                <?php endif; ?>
                <?php $this->eael_integrations(); ?>
                <div id="finalize" class="setup-content eael-box">

                    <h2><?php _e("Getting Started","essential-addons-for-elementor-lite") ?></h2>
                    <p><?php _e("Complete the Setup Wizard and Check out the walk-through tutorials to enhance your Elementor page
                        building experience","essential-addons-for-elementor-lite") ?> 🔥</p>

                    <div class="eael-iframe">
                        <iframe src="https://www.youtube.com/embed/uuyXfUDqRZM" frameborder="0"></iframe>
                    </div>
                    <div class="eael-setup-final-info">
                        <div>
                            <div class="eael-input-group">
                                <input type="checkbox" id="eael_user_email_address" name="eael_user_email_address"
                                       >
                                <label for="eael_user_email_address"><?php _e( 'Share non-sensitive diagnostic data and plugin
                                    usage
                                    information', 'essential-addons-for-elementor-lite' ) ?></label>
                            </div>
                            <p style="display: none"
                               class="eael-whatwecollecttext"><?php _e( 'We collect non-sensitive diagnostic data and plugin usage information. Your site URL, WordPress &amp; PHP version, plugins &amp; themes and email address to send you the discount coupon. This data lets us make sure this plugin always stays compatible with the most popular plugins and themes. No spam, we promise.', 'essential-addons-for-elementor-lite' ) ?></p>
                            <button type="button"
                                    class="btn-collect"><?php _e( 'What Do We Collect?', 'essential-addons-for-elementor-lite' ); ?></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php
    }

    /**
     * Footer content
     */
    public function setup_wizard_footer() {
        ?>
        <div class="eael-setup-footer">
            <button id="eael-prev"
                    class="button eael-btn"><?php _e( '< Previous', 'essential-addons-for-elementor-lite' ) ?></button>
            <button id="eael-next"
                    class="button eael-btn"><?php _e( 'Next >', 'essential-addons-for-elementor-lite' ) ?></button>
            <button id="eael-save" style="display: none"
                    class="button eael-btn eael-setup-wizard-save"><?php _e( 'Finish', 'essential-addons-for-elementor-lite' ) ?></button>
        </div>
        <?php
    }

    /**
     * render_wizard
     */
    public function render_wizard() {
        ?>
        <div class="eael-setup-wizard-wrap">
            <?php
            $this->change_site_title();
            $this->tab_step();
            $this->tab_content();
            $this->setup_wizard_footer();
            ?>
        </div>
        <?php
    }

    /**
     * EAEL elements list
     */
    public function eael_elements() {

        ?>
        <div id="elements" class="setup-content eael-box">
            <div class="row">
                <?php foreach ( $this->get_element_list() as $key => $item ): ?>
                    <h4 class="eael-elements-cat"><?php echo $item[ 'title' ]; ?></h4>
                    <div class="eael-checkbox-container eael-elements-container eael-<?php echo $key; ?>">
                        <?php foreach ( $item[ 'elements' ] as $element ):
                            $preferences = $checked = '';
                            if ( isset( $element[ 'preferences' ] ) ) {
                                $preferences = $element[ 'preferences' ];
                                if ( $element[ 'preferences' ] == 'basic' ) {
                                    $checked = 'checked';
                                }
                            }
                            ?>
                            <div class="eael-checkbox">
                                <div class="eael-elements-info">
                                    <input data-preferences="<?php echo $preferences; ?>" type="checkbox"
                                           class="eael-element" id="<?php echo $element[ 'key' ]; ?>"
                                           name="eael_element[<?php echo $element[ 'key' ]; ?>]"
                                        <?php echo $checked; ?> >
                                    <label for="<?php echo $element[ 'key' ]; ?>"
                                           class="eael-element-title"><?php echo $element[ 'title' ]; ?></label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    /**
     * EAEL plugin integrations
     */
    public function eael_integrations() {
        ?>
        <div id="integrations" class="setup-content eael-box">
            <div class="row">
                <?php foreach ( $this->get_plugin_list() as $plugin ) { ?>
                    <div class="col-one-third">
                        <div class="eael-admin-block-wrapper">
                            <div class="eael-admin-block eael-admin-block-integrations">
                                <div class="eael-admin-block-content">
                                    <div class="eael-admin-block-integrations-logo">
                                        <img src="<?php echo $plugin[ 'logo' ]; ?>" alt="logo"/>
                                    </div>
                                    <h2 class="eael-admin-block-integrations-title"><?php echo $plugin[ 'title' ]; ?></h2>
                                    <p class="eael-admin-block-integrations-text"><?php echo $plugin[ 'desc' ]; ?></p>
                                    <div class="eael-admin-block-integrations-btn-wrap">
                                        <?php if ( $this->get_local_plugin_data( $plugin[ 'basename' ] ) === false ) { ?>
                                            <a class="ea-button wpdeveloper-plugin-installer" data-action="install"
                                               data-slug="<?php echo $plugin[ 'slug' ]; ?>"><?php _e( 'Install', 'essential-addons-for-elementor-lite' ); ?></a>
                                        <?php } else { ?>
                                            <?php if ( is_plugin_active( $plugin[ 'basename' ] ) ) { ?>
                                                <a class="ea-button wpdeveloper-plugin-installer"><?php _e( 'Activated', 'essential-addons-for-elementor-lite' ); ?></a>
                                            <?php } else { ?>
                                                <a class="ea-button wpdeveloper-plugin-installer" data-action="activate"
                                                   data-basename="<?php echo $plugin[ 'basename' ]; ?>"><?php _e( 'Activate', 'essential-addons-for-elementor-lite' ); ?></a>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php
    }

    /**
     * get_plugin_list
     * @return array
     */
    public function get_plugin_list() {
        return [
            [
                'slug'     => 'betterdocs',
                'basename' => 'betterdocs/betterdocs.php',
                'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/bd-logo.png',
                'title'    => __( 'BetterDocs', 'essential-addons-for-elementor-lite' ),
                'desc'     => __( 'BetterDocs will help you to create & organize your documentation page in a beautiful way that will make your visitors find any help article easily', 'essential-addons-for-elementor-lite' ),
            ],
            [
                'slug'     => 'embedpress',
                'basename' => 'embedpress/embedpress.php',
                'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/ep-logo.png',
                'title'    => __( 'EmbedPress', 'essential-addons-for-elementor-lite' ),
                'desc'     => __( 'EmbedPress lets you embed videos, images, posts, audio, maps and upload PDF, DOC, PPT & all other types of content into your WordPress site. ', 'essential-addons-for-elementor-lite' ),
            ],
            [
                'slug'     => 'reviewx',
                'basename' => 'reviewx/reviewx.php',
                'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/review-logo.png',
                'title'    => __( 'ReviewX', 'essential-addons-for-elementor-lite' ),
                'desc'     => __( 'ReviewX lets you get instant customer ratings and multi criteria reviews to add credibility to your WooCommerce Store and increase conversion rates.', 'essential-addons-for-elementor-lite' ),
            ],
            [
                'slug'     => 'notificationx',
                'basename' => 'notificationx/notificationx.php',
                'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/nx-logo.png',
                'title'    => __( 'NotificationX', 'essential-addons-for-elementor-lite' ),
                'desc'     => __( 'Best FOMO Social Proof Plugin to boost your sales conversion. Create stunning Sales Popup & Notification Bar With Elementor Support', 'essential-addons-for-elementor-lite' ),
            ],
            [
                'slug'     => 'easyjobs',
                'basename' => 'easyjobs/easyjobs.php',
                'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/easy-jobs-logo.png',
                'title'    => __( 'EasyJobs', 'essential-addons-for-elementor-lite' ),
                'desc'     => __( 'Easy solution for the job recruitment to attract, manage & hire right talent faster. The Best Talent Recruitment Suite which lets you manage jobs & career page in Elementor.', 'essential-addons-for-elementor-lite' ),
            ],
            [
                'slug'     => 'crowdfundly',
                'basename' => 'crowdfundly/crowdfundly.php',
                'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/crowdfundly-logo.png',
                'title'    => __( 'Crowdfundly', 'essential-addons-for-elementor-lite' ),
                'desc'     => __( 'Crowdfundly is a Software as a Service (SaaS) digital crowdfunding solution. Best fundraising solution in WordPress with Elementor & WooCommerce support.', 'essential-addons-for-elementor-lite' ),
            ],
        ];
    }

    /**
     * get_local_plugin_data
     *
     * @param mixed $basename
     * @return array|false
     */
    public function get_local_plugin_data( $basename = '' ) {

        if ( empty( $basename ) ) {
            return false;
        }

        if ( !function_exists( 'get_plugins' ) ) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $plugins = get_plugins();

        if ( !isset( $plugins[ $basename ] ) ) {
            return false;
        }

        return $plugins[ $basename ];
    }

    /**
     * Templately promotion popup
     */
    public function eael_templately_plugin_popup() {
        ?>
        <div class="eael-popup__wrapper">
            <div class="eael-popup__block">
                <div class="eael-popup__logo">
                    <img src="<?php echo EAEL_PLUGIN_URL . 'assets/admin/images/templately/logo.svg'; ?>" alt="">
                </div>
                <p><?php _e( 'Get the best out of Essential Addons & boost your Elementor design experience with Templately. Deploy in hundreds of websites with 1-click, push to cloud and collaborate with your whole team to build sites faster than ever.', 'essential-addons-for-elementor-lite' ) ?></p>

                <?php if ( $this->get_local_plugin_data( 'templately/templately.php' ) === false ) { ?>
                    <a class="eael-popup__button wpdeveloper-plugin-installer" data-action="install"
                       data-slug="<?php echo 'templately'; ?>"><?php _e( 'Install', 'essential-addons-for-elementor-lite' ); ?></a>
                <?php } else { ?>
                    <?php if ( is_plugin_active( 'templately/templately.php' ) ) { ?>
                        <a class="eael-popup__button wpdeveloper-plugin-installer"><?php _e( 'Activated', 'essential-addons-for-elementor-lite' ); ?></a>
                    <?php } else { ?>
                        <a class="eael-popup__button wpdeveloper-plugin-installer" data-action="activate"
                           data-basename="<?php echo 'templately/templately.php'; ?>"><?php _e( 'Activate', 'essential-addons-for-elementor-lite' ); ?></a>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <?php
    }

    /**
     * Save setup wizard data
     */
    public function save_setup_wizard_data() {

        check_ajax_referer( 'essential-addons-elementor', 'security' );

        if(!current_user_can('manage_options')){
            wp_send_json_error(__('you are not allowed to do this action', 'essential-addons-for-elementor-lite'));
        }

        if ( !isset( $_POST[ 'fields' ] ) ) {
            return;
        }

        parse_str( $_POST[ 'fields' ], $fields );

        if ( isset($fields[ 'eael_user_email_address' ]) ) {
            $this->wpins_process();
        }
        update_option( 'eael_setup_wizard', 'complete' );
        if ( $this->save_element_list( $fields ) ) {
            wp_send_json_success( [ 'redirect_url' => admin_url( 'admin.php?page=eael-settings' ) ] );
        }
        wp_send_json_error();
    }

    /**
     * save_eael_elements_data
     */
    public function save_eael_elements_data() {
        check_ajax_referer( 'essential-addons-elementor', 'security' );

        if(!current_user_can('manage_options')){
            wp_send_json_error(__('you are not allowed to do this action', 'essential-addons-for-elementor-lite'));
        }

        if ( !isset( $_POST[ 'fields' ] ) ) {
            return;
        }

        parse_str( $_POST[ 'fields' ], $fields );

        if ( $this->save_element_list( $fields ) ) {
            wp_send_json_success();
        }
        wp_send_json_error();
    }

    /**
     * save_element_list
     * @param $fields
     * @return bool
     */
    public function save_element_list( $fields ) {
        if ( !empty( $fields ) ) {

            $el_list      = $fields[ 'eael_element' ];
            $save_element = [];
            foreach ( $GLOBALS[ 'eael_config' ][ 'elements' ] as $key => $item ) {
                $save_element[ $key ] = ( isset( $el_list[ $key ] ) ) ? 1 : '';
            }
	        $save_element = array_merge($save_element,$this->get_dummy_widget());
            update_option( 'eael_save_settings', $save_element );
            return true;
        }
        return false;
    }

    /**
     * get_element_list
     * @return array[]
     */
    public function get_element_list() {
        return [
            'content-elements'         => [
                'title'    => __( 'Content Elements', 'essential-addons-for-elementor-lite' ),
                'elements' => [
                    [
                        'key'         => 'creative-btn',
                        'title'       => __( 'Creative Button', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'basic',
                    ],
                    [
                        'key'         => 'team-members',
                        'title'       => __( 'Team Member', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'basic',
                    ],
                    [
                        'key'         => 'testimonials',
                        'title'       => __( 'Testimonial', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'basic',
                    ],
                    [
                        'key'         => 'flip-box',
                        'title'       => __( 'Flip Box', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'basic',
                    ],
                    [
                        'key'         => 'info-box',
                        'title'       => __( 'Info Box', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'basic',
                    ],
                    [
                        'key'         => 'dual-header',
                        'title'       => __( 'Dual Color Heading', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'basic',
                    ],
                    [
                        'key'         => 'tooltip',
                        'title'       => __( 'Tooltip', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'basic',
                    ],
                    [
                        'key'         => 'adv-accordion',
                        'title'       => __( 'Advanced Accordion', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'basic',
                    ],
                    [
                        'key'         => 'adv-tabs',
                        'title'       => __( 'Advanced Tabs', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'basic',
                    ],
                    [
                        'key'         => 'feature-list',
                        'title'       => __( 'Feature List', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'basic',

                    ],
                    [
                        'key'         => 'sticky-video',
                        'title'       => __( 'Sticky Video', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'basic',
                    ],
                    [
                        'key'         => 'event-calendar',
                        'title'       => __( 'Event Calendar', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'advance',
                    ],
                    [
                        'key'         => 'simple-menu',
                        'title'       => __( 'Simple Menu', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'basic',
                    ],
                ]
            ],
            'dynamic-content-elements' => [
                'title'    => __( 'Dynamic Content Elements', 'essential-addons-for-elementor-lite' ),
                'elements' => [
                    [
                        'key'         => 'post-grid',
                        'title'       => __( 'Post Grid', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'advance',
                    ],
                    [
                        'key'   => 'post-timeline',
                        'title' => __( 'Post Timeline', 'essential-addons-for-elementor-lite' ),
                    ],
                    [
                        'key'         => 'data-table',
                        'title'       => __( 'Data Table', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'basic',
                    ],
                    [
                        'key'   => 'advanced-data-table',
                        'title' => __( 'Advanced Data Table', 'essential-addons-for-elementor-lite' ),
                    ],
                    [
                        'key'         => 'content-ticker',
                        'title'       => __( 'Content Ticker', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'basic',
                    ]
                ]
            ],
            'creative-elements'        => [
                'title'    => __( 'Creative Elements', 'essential-addons-for-elementor-lite' ),
                'elements' => [
                    [
                        'key'         => 'count-down',
                        'title'       => __( 'Countdown', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'basic',
                    ],
                    [
                        'key'         => 'fancy-text',
                        'title'       => __( 'Fancy Text', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'basic',
                    ],
                    [
                        'key'         => 'filter-gallery',
                        'title'       => __( 'Filterable Gallery', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'basic',
                    ],
                    [
                        'key'         => 'image-accordion',
                        'title'       => __( 'Image Accordion', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'basic',
                    ],
                    [
                        'key'         => 'progress-bar',
                        'title'       => __( 'Progress Bar', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'basic',
                    ],
                ]
            ],
            'marketing-elements'       => [
                'title'    => __( 'Marketing & Social Feed Elements', 'essential-addons-for-elementor-lite' ),
                'elements' => [
                    [
                        'key'         => 'call-to-action',
                        'title'       => __( 'Call To Action', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'basic',
                    ],
                    [
                        'key'         => 'price-table',
                        'title'       => __( 'Pricing Table', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'basic',
                    ],
                    [
                        'key'         => 'twitter-feed',
                        'title'       => __( 'Twitter Feed', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'advance',
                    ],
                    [
                        'key'         => 'facebook-feed',
                        'title'       => __( 'Facebook Feed', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'advance',
                    ],

                ]
            ],
            'form-styler-elements'     => [
                'title'    => __( 'Form Styler Elements', 'essential-addons-for-elementor-lite' ),
                'elements' => [
                    [
                        'key'         => 'contact-form-7',
                        'title'       => __( 'Contact Form 7', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'advance',
                    ],
                    [
                        'key'   => 'weforms',
                        'title' => __( 'weForms', 'essential-addons-for-elementor-lite' ),
                    ],
                    [
                        'key'   => 'ninja-form',
                        'title' => __( 'Ninja Form', 'essential-addons-for-elementor-lite' ),
                    ],
                    [
                        'key'   => 'gravity-form',
                        'title' => __( 'Gravity Form', 'essential-addons-for-elementor-lite' ),
                    ],
                    [
                        'key'   => 'caldera-form',
                        'title' => __( 'Caldera Form', 'essential-addons-for-elementor-lite' ),
                    ],
                    [
                        'key'   => 'wpforms',
                        'title' => __( 'WPForms', 'essential-addons-for-elementor-lite' ),
                    ],
                    [
                        'key'   => 'fluentform',
                        'title' => __( 'Fluent Forms', 'essential-addons-for-elementor-lite' ),
                    ],
                    [
                        'key'   => 'formstack',
                        'title' => __( 'Formstack', 'essential-addons-for-elementor-lite' ),
                    ],
                    [
                        'key'   => 'typeform',
                        'title' => __( 'Typeform', 'essential-addons-for-elementor-lite' ),
                    ],
                    [
                        'key'         => 'login-register',
                        'title'       => __( 'Login Register Form', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'advance',
                    ],
                ]
            ],
            'documentation-elements'   => [
                'title'    => __( 'Documentation Elements', 'essential-addons-for-elementor-lite' ),
                'elements' => [
                    [
                        'key'   => 'betterdocs-category-grid',
                        'title' => __( 'BetterDocs Category Grid', 'essential-addons-for-elementor-lite' ),
                    ],
                    [
                        'key'   => 'betterdocs-category-box',
                        'title' => __( 'BetterDocs Category Box', 'essential-addons-for-elementor-lite' ),

                    ],
                    [
                        'key'   => 'betterdocs-search-form',
                        'title' => __( 'BetterDocs Search Form', 'essential-addons-for-elementor-lite' ),
                    ]
                ]
            ],
            'woocommerce-elements'     => [
                'title'    => __( 'WooCommerce Elements', 'essential-addons-for-elementor-lite' ),
                'elements' => [
                    [
                        'key'         => 'product-grid',
                        'title'       => __( 'Product Grid', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'advance',
                    ],
                    [
                        'key'         => 'woo-product-carousel',
                        'title'       => __( 'Woo Product Carousel', 'essential-addons-for-elementor-lite' ),
                    ],
                    [
                        'key'   => 'woo-checkout',
                        'title' => __( 'Woo Checkout', 'essential-addons-for-elementor-lite' ),
                    ],
                    [
                        'key'         => 'woo-product-compare',
                        'title'       => __( 'Woo Product Compare', 'essential-addons-for-elementor-lite' ),
                        'preferences' => 'advance',
                    ],
	                [
		                'key'         => 'woo-product-gallery',
		                'title'       => __( 'Woo Product Gallery', 'essential-addons-for-elementor-lite' ),
		                'preferences' => 'advance',
	                ]
                ]
            ]
        ];
    }

    public static function redirect() {
        update_option( 'eael_setup_wizard', 'init' );
        wp_redirect( admin_url( 'admin.php?page=eael-setup-wizard' ) );
    }

    public function change_site_title() {
        ?>
        <script>
            document.title = "<?php _e( 'Quick Setup Wizard- Essential Addons', 'essential-addons-for-elementor-lite' ); ?>"
        </script>
        <?php
    }

    public function wpins_process(){
        $plugin_name = basename( EAEL_PLUGIN_FILE, '.php' );
        if ( class_exists( '\Essential_Addons_Elementor\Classes\Plugin_Usage_Tracker' ) ){
            $tracker = \Essential_Addons_Elementor\Classes\Plugin_Usage_Tracker::get_instance( EAEL_PLUGIN_FILE, [
                'opt_in'       => true,
                'goodbye_form' => true,
                'item_id'      => '760e8569757fa16992d8'
            ] );
            $tracker->set_is_tracking_allowed( true );
            $tracker->do_tracking( true );
        }
    }

    public function get_dummy_widget(){
        return [
            'embedpress'                  => 1,
            'woocommerce-review'          => 1,
            'career-page'                 => 1,
            'crowdfundly-single-campaign' => 1,
            'crowdfundly-organization'    => 1,
            'crowdfundly-all-campaign'    => 1,
        ];
    }
}


