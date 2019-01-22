<?php

if( !defined( 'ABSPATH' ) ) exit; //Exit if accessed directly

/**
 * EAEL version rollback class
 * 
 * 
 * @since 2.8.5
 */
class EAEL_Version_Rollback {

    /**
     * Plugin repository url
     * 
     * @access protected
     */
    protected $package_url;

    /**
     * Plugin version
     * 
     * @access protected
     */
    protected $plugin_version;

    /**
     * Plugin name
     * 
     * @access protected
     */
    protected $plugin_name;

    /**
     * Plugin slug
     * 
     * @access protected
     */
    protected $plugin_slug;


    public function __construct( array $args = [] ) {

        foreach( $args as $key => $value ) {
            $this->{$key} = $value;
        }

    }

    /**
     * Print inline styles
     */
    private function print_inline_style() {
		?>
		<style>
			.wrap {
				overflow: hidden;
			}

			h1 {
				background: #6ec1e4;
				text-align: center;
				color: #fff !important;
				padding: 70px !important;
				text-transform: uppercase;
				letter-spacing: 1px;
			}
			h1 img {
				max-width: 300px;
				display: block;
				margin: auto auto 50px;
			}
		</style>
		<?php
    }

    /**
     * Create package for upgrade
     * 
     * @access protected
     */
    protected function apply_package() {
        
        $update_plugins = get_transient( 'update_plugins' );

        if( ! is_object($update_plugins) ) {
            $update_plugins = new \stdClass();
        }

        $plugin_info              = new \stdClass();
        $plugin_info->new_version = $this->plugin_version;
        $plugin_info->slug        = $this->plugin_slug;
        $plugin_info->package     = $this->package_url;
        $plugin_info->url         = "https://www.essential-addons.com/elementor/";

        $update_plugins->response[ $this->plugin_name ] = $plugin_info;

        set_site_transient( 'update_plugins', $update_plugins );

    }

    /**
     * Upgrade plugin
     * 
     * @access protected
     */
    protected function upgrade() {

        require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

        $logo_url = ESSENTIAL_ADDONS_EL_URL . 'admin/assets/images/ea-logo.png';
        $title =  '<img src="' . $logo_url . '" alt="Essential Addons Elementor">';
        $title .=  sprintf(__( "Rolling Back to %s", 'essential-addons-elementor' ), $this->plugin_version);

        $upgrader_args = [
            'url'    => 'update.php?action=upgrade-plugin&plugin=' . rawurlencode( $this->plugin_name ),
            'plugin' => $this->plugin_name,
            'nonce'  => 'upgrade-plugin_' . $this->plugin_name,
            'title'  => $title
        ];

        $this->print_inline_style();

        $upgrader = new \Plugin_Upgrader( new \Plugin_Upgrader_Skin( $upgrader_args ) );
        $upgrader->upgrade( $this->plugin_name );
        
    }

    /**
     * Trigger plugin upgrader action.
     * 
     * @access protected
     */
    public function run() {
        $this->apply_package();
        $this->upgrade();
    }
    
}