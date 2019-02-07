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
		public $versions = [
            '2.9.4',
            '2.9.3',
            '2.9.2',
            '2.9.1',
            '2.9.0',
            '2.8.7',
            '2.8.6',
            '2.8.5',
            '2.8.4',
            '2.8.3',
            '2.8.2',
            '2.8.1',
            '2.8.0',
            '2.7.1',
            '2.7.1',
            '2.7.9',
            '2.7.8',
            '2.7.7',
            '2.7.6',
            '2.7.5',
            '2.7.4',
            '2.7.3',
            '2.7.2',
            '2.7.1',
            '2.7.0',
            '2.6.0',
            '2.5.0',
            '2.4.3',
            '2.4.2',
            '2.4.1',
            '2.4.0',
            '2.3.1',
            '2.3.0',
            '2.2.5',
            '2.2.4',
            '2.2.3',
            '2.2.2',
            '2.2.1',
            '2.2.0',
            '2.1',
            '2.0',
            '1.1.0',
            '1.0.1',
            '1.0.0'
        ];

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
                // self::$instance->get_plugin_data();
            }
            return self::$instance;
        }

        public function __construct() {
            add_filter( 'insert_eael_versions_html', [$this, 'versions_select'] );
            $this->get_plugin_data();
        }

        /**
         * Setup Variables
         * 
         * @access private
         */
        private function setup_plugin_vars() {
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



    }

}

EAEL_Rollback::instance();