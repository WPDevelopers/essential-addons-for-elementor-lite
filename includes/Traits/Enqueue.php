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
            false,
	        EAEL_PLUGIN_VERSION
        );
    }
    
    // frontend styles
    public function frontend_enqueue_scripts()
    {
        // ea icon font
        wp_register_style(
            'ea-icon-frontend',
            $this->safe_url(EAEL_PLUGIN_URL . 'assets/admin/css/eaicon.css'),
            false,
	        EAEL_PLUGIN_VERSION
        );
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
            position: relative;
        }
        
		.elementor-add-new-section .elementor-add-templately-promo-button > i{
            height: 12px;
        }
        
        body .elementor-add-new-section .elementor-add-section-area-button {
            margin-left: 0;
        }";
        wp_add_inline_style( 'elementor-icons', $css );
    }

	public function essential_blocks_promo_enqueue_scripts() {
		if ( is_plugin_active( 'essential-blocks/essential-blocks.php' ) || get_option( 'eael_gb_eb_popup_hide' ) ) {
			return;
		}

		add_action( 'admin_footer', [ $this, 'essential_blocks_promo_admin_js_template' ] );
		wp_enqueue_script( 'eael-gutenberg', $this->safe_url( EAEL_PLUGIN_URL . 'assets/admin/js/eael-essential-blocks-promo.js' ), [ 'jquery' ], EAEL_PLUGIN_VERSION, true );
		wp_enqueue_style( 'eael-gutenberg', $this->safe_url( EAEL_PLUGIN_URL . 'assets/admin/css/eael-essential-blocks-promo.css' ), [], EAEL_PLUGIN_VERSION );
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
