<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Essential_Addons_Elementor\Classes\Helper as EnqueueHelper;

trait Enqueue
{
    public function before_enqueue_styles($widgets)
    {
        // Compatibility: Gravity forms
        if (in_array('gravity-form', $widgets) && class_exists('GFCommon')) {
            foreach (EnqueueHelper::get_gravity_form_list() as $form_id => $form_name) {
                if ($form_id != '0') {
                    gravity_form_enqueue_scripts($form_id);
                }
            }
        }

        // Compatibility: WPforms
        if (in_array('wpforms', $widgets) && function_exists('wpforms')) {
            wpforms()->frontend->assets_css();
        }

        // Compatibility: Caldera forms
        if (in_array('caldera-form', $widgets) && class_exists('Caldera_Forms')) {
            add_filter('caldera_forms_force_enqueue_styles_early', '__return_true');
        }

        // Compatibility: Fluent forms
        if (in_array('fluentform', $widgets) && defined('FLUENTFORM')) {
            wp_register_style(
                'fluent-form-styles',
                WP_PLUGIN_URL . '/fluentform/public/css/fluent-forms-public.css',
                false,
                FLUENTFORM_VERSION
            );

            wp_register_style(
                'fluentform-public-default',
                WP_PLUGIN_URL . '/fluentform/public/css/fluentform-public-default.css',
                false,
                FLUENTFORM_VERSION
            );
        }

        // Compatibility: reCaptcha with login/register
        if (in_array('login-register', $widgets) && $site_key = get_option('eael_recaptcha_sitekey')) {
	        $recaptcha_api_args['render'] = 'explicit';
	        if ( $recaptcha_language = get_option( 'eael_recaptcha_language' ) ) {
		        $recaptcha_api_args['hl'] = $recaptcha_language;
	        }
	        $recaptcha_api_args = apply_filters( 'eael_lr_recaptcha_api_args', $recaptcha_api_args );
	        $recaptcha_api_args = http_build_query( $recaptcha_api_args );
            wp_register_script('eael-recaptcha', "https://www.google.com/recaptcha/api.js?{$recaptcha_api_args}", false, EAEL_PLUGIN_VERSION, false);
        }
    }

    public function enqueue_scripts()
    {
        if (!apply_filters('eael/is_plugin_active', 'elementor/elementor.php')) {
            return;
        }

        if ($this->is_running_background()) {
            return;
        }

        if ($this->uid === null) {
            return;
        }
		//fix asset loading issue if no custom elementor css is not used.
	    $this->loaded_templates[] = get_the_ID();
        // register fontawesome as fallback
        wp_register_style(
            'font-awesome-5-all',
            ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/all.min.css',
            false,
            EAEL_PLUGIN_VERSION
        );

        wp_register_style(
            'font-awesome-4-shim',
            ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/v4-shims.min.css',
            false,
            EAEL_PLUGIN_VERSION
        );

        wp_register_script(
            'font-awesome-4-shim',
            ELEMENTOR_ASSETS_URL . 'lib/font-awesome/js/v4-shims.min.js',
            false,
            EAEL_PLUGIN_VERSION
        );

        // register reading progress assets
        wp_register_style(
            'eael-reading-progress',
            EAEL_PLUGIN_URL . 'assets/front-end/css/view/reading-progress.min.css',
            false,
            EAEL_PLUGIN_VERSION
        );

        wp_register_script(
            'eael-reading-progress',
            EAEL_PLUGIN_URL . 'assets/front-end/js/view/reading-progress.min.js',
            ['jquery'],
            EAEL_PLUGIN_VERSION
        );

        // register Table of contents assets
        wp_register_style(
            'eael-table-of-content',
            EAEL_PLUGIN_URL . 'assets/front-end/css/view/table-of-content.min.css',
            false,
            EAEL_PLUGIN_VERSION
        );

        wp_register_script(
            'eael-table-of-content',
            EAEL_PLUGIN_URL . 'assets/front-end/js/view/table-of-content.min.js',
            ['jquery'],
            EAEL_PLUGIN_VERSION
        );

        // register scroll to top assets
        wp_register_style(
            'eael-scroll-to-top',
            EAEL_PLUGIN_URL . 'assets/front-end/css/view/scroll-to-top.min.css',
            false,
            EAEL_PLUGIN_VERSION
        );

        wp_register_script(
            'eael-scroll-to-top',
            EAEL_PLUGIN_URL . 'assets/front-end/js/view/scroll-to-top.min.js',
            ['jquery'],
            EAEL_PLUGIN_VERSION
        );

	    // localize object
	    $this->localize_objects = apply_filters( 'eael/localize_objects', [
		    'ajaxurl'        => admin_url( 'admin-ajax.php' ),
		    'nonce'          => wp_create_nonce( 'essential-addons-elementor' ),
		    'i18n'           => [
			    'added'   => __( 'Added ', 'essential-addons-for-elementor-lite' ),
			    'compare' => __( 'Compare', 'essential-addons-for-elementor-lite' ),
			    'loading' => esc_html__( 'Loading...', 'essential-addons-for-elementor-lite' )
		    ],
		    'page_permalink' => get_the_permalink(),
	    ] );

        // edit mode
        if ($this->is_edit_mode()) {
            $elements = $this->get_settings();

            // if no widget in page, return
            if (empty($elements)) {
                return;
            }

            // run hook before enqueue styles
            do_action('eael/before_enqueue_styles', $elements);

            // css
            if (get_option('elementor_css_print_method') == 'internal') {
                $this->css_strings = $this->generate_strings($elements, 'edit', 'css');
            } else {
                // generate editor style
                if (!$this->has_assets_files($this->uid, 'css')) {
                    $this->generate_script($this->uid, $elements, 'edit', 'css');
                }

                // enqueue
                wp_enqueue_style(
                    $this->uid,
                    $this->safe_url(EAEL_ASSET_URL . '/' . $this->uid . '.min.css'),
                    false,
                    time()
                );
            }

            // run hook before enqueue scripts
            do_action('eael/before_enqueue_scripts', $elements);

            // js
            if (get_option('eael_js_print_method') == 'internal') {
                $this->js_strings = $this->generate_strings($elements, 'edit', 'js');
            } else {
                // generate editor script
                if (!$this->has_assets_files($this->uid, 'js')) {
                    $this->generate_script($this->uid, $elements, 'edit', 'js');
                }

                // enqueue
                wp_enqueue_script(
                    $this->uid,
                    $this->safe_url(EAEL_ASSET_URL . '/' . $this->uid . '.min.js'),
                    ['jquery'],
                    time(),
                    true
                );

                // localize
                wp_localize_script($this->uid, 'localize', $this->localize_objects);
            }
        }

        // view mode
        if ($this->is_preview_mode()) {

            if ($this->request_requires_update) {
                $elements = $this->get_settings();
                $tmp_uid = $this->get_temp_uid();
            } else {
                $elements = get_option($this->uid . '_eael_elements');
            }

            // if no widget in page, return
            if (empty($elements)) {
                return;
            }
            // run hook before enqueue styles
            do_action('eael/before_enqueue_styles', $elements);

            // css
            if (get_option('elementor_css_print_method') == 'internal') {
                $this->css_strings = $this->generate_strings($elements, 'view', 'css');
            } else {
                if ($this->request_requires_update) {
                    // generate script if not exists
                    if (!$this->has_assets_files($tmp_uid, 'css')) {
                        $this->generate_script($tmp_uid, $elements, 'view', 'css');
                    }

                    // enqueue
                    wp_enqueue_style(
                        $this->uid,
                        $this->safe_url(EAEL_ASSET_URL . '/' . $tmp_uid . '.min.css'),
                        false,
                        time()
                    );
                } else {
                    // generate script if not exists
                    if (!$this->has_assets_files($this->uid, 'css')) {
                        $this->generate_script($this->uid, $elements, 'view', 'css');
                    }

                    // enqueue
                    wp_enqueue_style(
                        $this->uid,
                        $this->safe_url(EAEL_ASSET_URL . '/' . $this->uid . '.min.css'),
                        false,
                        time()
                    );
                }
            }

            // run hook before enqueue scripts
            do_action('eael/before_enqueue_scripts', $elements);

            // js
            if (get_option('eael_js_print_method') == 'internal') {
                $this->js_strings = $this->generate_strings($elements, 'view', 'js');
            } else {
                if ($this->request_requires_update) {
                    // generate script if not exists
                    if (!$this->has_assets_files($tmp_uid, 'js')) {
                        $this->generate_script($tmp_uid, $elements, 'view', 'js');
                    }

                    // enqueue
                    wp_enqueue_script(
                        $this->uid,
                        $this->safe_url(EAEL_ASSET_URL . '/' . $tmp_uid . '.min.js'),
                        ['jquery'],
                        time(),
                        true
                    );
                } else {
                    // generate script if not exists
                    if (!$this->has_assets_files($this->uid, 'js')) {
                        $this->generate_script($this->uid, $elements, 'view', 'js');
                    }

                    // enqueue
                    wp_enqueue_script(
                        $this->uid,
                        $this->safe_url(EAEL_ASSET_URL . '/' . $this->uid . '.min.js'),
                        ['jquery'],
                        time(),
                        true
                    );
                }

                // localize script
                wp_localize_script($this->uid, 'localize', $this->localize_objects);
            }
        }
    }

    // editor styles
    public function editor_enqueue_scripts()
    {
        // ea icon font
        wp_enqueue_style(
            'ea-icon',
            $this->safe_url(EAEL_PLUGIN_URL . 'assets/admin/css/eaicon.css'),
            false,
	        EAEL_PLUGIN_VERSION
        );

        // editor style
        wp_enqueue_style(
            'eael-editor',
            $this->safe_url(EAEL_PLUGIN_URL . 'assets/admin/css/editor.css'),
            false
        );
    }

    // inline enqueue styles
    public function enqueue_inline_styles()
    {
        if ($this->is_edit_mode() || $this->is_preview_mode()) {
            if ($this->css_strings) {
	            printf( '<style id="%1$s">%2$s</style>', esc_attr( $this->uid ), $this->css_strings );
            }
        }
    }

    // inline enqueue scripts
    public function enqueue_inline_scripts()
    {
        // view/edit mode mode
        if ($this->is_edit_mode() || $this->is_preview_mode()) {
            if ($this->js_strings) {
                printf('<script>%1$s</script>','var localize ='.wp_json_encode($this->localize_objects));
	            printf( '<script id="%1$s">%2$s</script>', esc_attr( $this->uid ), $this->js_strings );
            }
        }
    }

    // templately promo enqueue scripts
    public function templately_promo_enqueue_scripts(){
        // enqueue
        wp_register_script(
            'templately-promo',
            EAEL_PLUGIN_URL . 'assets/admin/js/eael-templately-promo.js',
            ['jquery'],
            EAEL_PLUGIN_VERSION
        );

        wp_localize_script('templately-promo','localize',[
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'essential-addons-elementor' ),
        ]);
        wp_enqueue_script('templately-promo');
        // enqueue
        wp_enqueue_style(
            'templately-promo',
            EAEL_PLUGIN_URL . 'assets/admin/css/eael-templately-promo.css',
            EAEL_PLUGIN_VERSION
        );


    }

    public function templately_promo_enqueue_style(){
        $src = EAEL_PLUGIN_URL . 'assets/admin/images/templately/logo-icon.svg';
        $css = "
		.elementor-add-new-section .elementor-add-templately-promo-button{
            background-color: #5d4fff;
            background-image: url({$src});
            background-repeat: no-repeat;
            background-position: center center;
            margin-left: 5px;
            position: relative;
            bottom: 5px;
        }";
        wp_add_inline_style( 'elementor-icons', $css );
    }

	// replace beehive theme's swiper slider lib file with elementor's swiper lib file
	public function beehive_theme_swiper_slider_compatibility( $scripts ) {
		unset( $scripts['swiper'] );
		unset( $scripts['beehive-elements'] );

		$scripts['beehive-elements'] = array(
			'src'       => EAEL_PLUGIN_URL . 'assets/front-end/js/view/beehive-elements.min.js',
			'deps'      => array( 'jquery' ),
			'in_footer' => true,
			'enqueue'   => true,
		);

		return $scripts;
	}
}
