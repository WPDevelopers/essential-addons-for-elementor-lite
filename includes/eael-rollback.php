<?php

/**
 * Main EAEL Rollback Class
 * 
 * @since 2.8.3
 */
if( ! class_exists('EAEL_Rollback') ) {

    /**
     * Class EAEL_Rollback
     */
    final class EAEL_Rollback {

        /**
         * EAEL_Rollback instance
         * 
         * @var EAEL_Rollback The one and only
         * @since 1.0
         */
        private static $instance;

        /**
         * Plugin repo url.
         * 
         * @var string
         */
        public $plugins_repo = 'http://plugins.svn.wordpress.org';

        /**
		 * Plugin file.
		 *
		 * @var string
		 */
		public $plugin_file;

		/**
		 * Plugin slug.
		 *
		 * @var string
		 */
        public $plugin_slug = 'essential-addons-for-elementor-lite';
        
        /**
		 * Versions.
		 *
		 * @var array
		 */
		public $versions;

		/**
		 * Current version.
		 *
		 * @var string
		 */
        public $current_version;

        /**
         * Plugins Data
         * 
         * @var array $plugins_data
         */
        public $plugins_data = [];
        
        /**
         * Main EAEL_Rollback Instance
         * 
         * @return EAEL_Rollback
         */
        public static function instance() {
            if( ! isset( self::$instance ) && ! ( self::$instance instanceof EAEL_Rollback ) && is_admin() ) {
                self::$instance = new self();

                // Only setup plugin rollback on specific page.
                self::$instance->setup_plugin_vars();
                self::$instance->get_plugin_data();
            }
            return self::$instance;
        }

        public function __construct() {
            add_filter( 'insert_eael_versions_html', [$this, 'versions_select'] );
        }

        /**
         * Setup Variables
         * 
         * @access private
         */
        private function setup_plugin_vars() {
            $svn_tags = $this->get_svn_tags();
            $this->set_svn_versions_data($svn_tags);
            $this->versions_select('plugin');
        }

        /**
         * Get plugins data
         * 
         * @return array plugin data
         */
        public function get_plugin_data() {
            if ( ! function_exists( 'get_plugins' ) ) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }

            $plugins = get_plugins();
            if( array_key_exists('essential-addons-for-elementor-lite/essential_adons_elementor.php', $plugins) ) {
                $this->plugins_data = $plugins['essential-addons-for-elementor-lite/essential_adons_elementor.php'];
                $this->current_version = $this->plugins_data['Version'];
                return $this->plugins_data;
            }
        }

        /**
         * Get Subversion Tags
         * 
         * cURL wp.org repo to get the proper tags
         * 
         * @param $type
         * @param $slug
         * @return null|string
         */
        public function get_svn_tags() {
            $url = $this->plugins_repo . '/' . $this->plugin_slug . '/tags/';

            $response = wp_remote_get($url);

            // If we have an error
            if( wp_remote_retrieve_response_code( $response ) !== 200) return null;

            // Return response body
            return wp_remote_retrieve_body( $response );
        }

        /**
         * Set SVN Version Data
         * 
         * @param $html
         * 
         * @return array|bool
         */
        public function set_svn_versions_data( $html ) {
            if( ! $html ) return false;

            $DOM = new DOMDocument;
            $DOM->loadHTML( $html );

            $versions = [];
            $items = $DOM->getElementsByTagName('a');

            foreach( $items as $item ) {
                $href = str_replace( '/', '', $item->getAttribute('href') ); // Remove trailing slash

                if( strpos( $href, 'http' ) === false && '..' !== $href ) {
                    $versions[] = $href;
                }
            }

            $this->versions = array_reverse( $versions );
            return $versions;
        }

        /**
         * Versions Select
         * 
         * Outputs the version radio buttons to select a rollback;
         * 
         * @param $type
         * @return bool|string
         */
        public function versions_select( $vh ) {
            if( !$this->versions ) return false;

            $type = 'plugin';
            $vh .= '<select name="'. $type .'_version" id="eael_plugins_versions">';
                usort($this->versions, 'version_compare');
                $this->versions = array_reverse($this->versions);

                // Loop through versions and output in a radio list
                foreach( $this->versions as $v ) {
                    
                    // Is this the current version?
                    if( $v == $this->current_version ) {
                        $vh .= '<option value"'. esc_attr($v).'" disabled>'.$v;
                        $vh .= '<span class="current-version">'. __( ' &nbsp; Installed Version', 'essential-addons-elementor' ) .'</span>';
                        $vh .= '</option>';
                    }
                    $vh .= '<option value"'. esc_attr($v).'">'.$v.'</option>';

                }

            $vh .= '</select>';

            return $vh;
        }


        /**
         * Plugin Action Link
         * 
         * Adds a rollback button into the eael version control tab with appropriate query strings.
         */
        public function eael_plugin_upgrade_confirmation() {
            
        }



    }

}

EAEL_Rollback::instance();