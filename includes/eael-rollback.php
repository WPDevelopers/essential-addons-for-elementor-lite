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
         * Main EAEL_Rollback Instance
         * 
         * @return EAEL_Rollback
         */
        public static function instance() {
            if( ! isset( self::$instance ) && ! ( self::$instance instanceof EAEL_Rollback ) && is_admin() ) {
                self::$instance = new self();

                // Only setup plugin rollback on specific page.
                self::$instance->setup_plugin_vars();
            }
            return self::$instance;
        }

        /**
         * Setup Variables
         * 
         * @access private
         */
        private function setup_plugin_vars() {
            $svn_tags = $this->get_svn_tags();
            $this->set_svn_versions_data($svn_tags);
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
        public static function set_svn_versions_data( $html ) {
            if( ! $html ) return false;

            $DOM = new DOMDocument;
            $DOM->loadHTML( $html );

            $items = $DOM->getElementsByTagName('a');

            // var_dump();

        }



    }

}

EAEL_Rollback::instance();