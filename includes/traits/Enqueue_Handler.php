<?php

namespace Essential_Addons_Elementor\Traits;

trait Enqueue_Handler
{
    public function eael_generate_editor_scripts()
    {
        $active_components = Essential_Addons_EL::eael_activated_modules();
        $paths = array();

        foreach ($active_components as $component) {
            switch ($component) {
                case 'adv-accordion':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/' . $component . '/index.js';
                    break;

                case 'adv-tabs':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/' . $component . '/index.js';
                    break;
            }
        }

        // if ($is_component_active['adv-accordion']) {
        //     wp_enqueue_script(
        //         'essential_addons_elementor-fancy-text-js',
        //         ESSENTIAL_ADDONS_EL_URL . 'assets/js/fancy-text.js',
        //         array('jquery'), '1.0', true
        //     );
        // }

        // if ($is_component_active['fancy-text']) {
        //     wp_enqueue_script(
        //         'essential_addons_elementor-fancy-text-js',
        //         ESSENTIAL_ADDONS_EL_URL . 'assets/js/fancy-text.js',
        //         array('jquery'), '1.0', true
        //     );
        // }

        // if ($is_component_active['count-down']) {
        //     wp_enqueue_script(
        //         'essential_addons_elementor-countdown-js',
        //         ESSENTIAL_ADDONS_EL_URL . 'assets/js/countdown.min.js',
        //         array('jquery'), '1.0', true
        //     );
        // }

        // if ($is_component_active['post-grid'] || $is_component_active['twitter-feed']) {
        //     wp_enqueue_script(
        //         'essential_addons_elementor-masonry-js',
        //         ESSENTIAL_ADDONS_EL_URL . 'assets/js/masonry.min.js',
        //         array('jquery'), '1.0', true
        //     );
        // }

        // if ($is_component_active['post-grid'] || $is_component_active['post-timeline']) {
        //     wp_enqueue_script(
        //         'essential_addons_elementor-load-more-js',
        //         ESSENTIAL_ADDONS_EL_URL . 'assets/js/load-more.js',
        //         array('jquery'), '1.0', true
        //     );

        //     $eael_js_settings = array(
        //         'ajaxurl' => admin_url('admin-ajax.php'),
        //     );

        //     wp_localize_script(
        //         'essential_addons_elementor-load-more-js',
        //         'eaelPostGrid', $eael_js_settings
        //     );
        // }

        // if ($is_component_active['twitter-feed']) {
        //     wp_enqueue_script(
        //         'essential_addons_elementor-codebird-js',
        //         ESSENTIAL_ADDONS_EL_URL . 'assets/social-feeds/codebird.js',
        //         array('jquery'), '1.0', true
        //     );
        // }

        // if ($is_component_active['twitter-feed']) {
        //     wp_enqueue_script(
        //         'essential_addons_elementor-doT-js',
        //         ESSENTIAL_ADDONS_EL_URL . 'assets/social-feeds/doT.min.js',
        //         array('jquery'), '1.0', true
        //     );

        //     wp_enqueue_script(
        //         'essential_addons_elementor-moment-js',
        //         ESSENTIAL_ADDONS_EL_URL . 'assets/social-feeds/moment.js',
        //         array('jquery'), '1.0', true
        //     );

        //     wp_enqueue_script(
        //         'essential_addons_elementor-socialfeed-js',
        //         ESSENTIAL_ADDONS_EL_URL . 'assets/social-feeds/jquery.socialfeed.js',
        //         array('jquery'), '1.0', true
        //     );
        // }

        // if ($is_component_active['filter-gallery']) {
        //     wp_enqueue_script(
        //         'essential_addons_mixitup-js',
        //         ESSENTIAL_ADDONS_EL_URL . 'assets/js/mixitup.min.js',
        //         array('jquery'), '1.0', true
        //     );
        //     wp_enqueue_script(
        //         'essential_addons_magnific-popup-js',
        //         ESSENTIAL_ADDONS_EL_URL . 'assets/js/jquery.magnific-popup.min.js',
        //         array('jquery'), '1.0', true
        //     );

        //     wp_register_script(
        //         'essential_addons_isotope-js',
        //         ESSENTIAL_ADDONS_EL_URL . 'assets/js/isotope.pkgd.min.js',
        //         array('jquery'), '1.0', true
        //     );

        //     wp_register_script(
        //         'jquery-resize',
        //         ESSENTIAL_ADDONS_EL_URL . 'assets/js/jquery.resize.min.js',
        //         array('jquery'), '1.0', true
        //     );
        // }

        // if ($is_component_active['price-table']) {
        //     wp_enqueue_style(
        //         'essential_addons_elementor-tooltipster',
        //         ESSENTIAL_ADDONS_EL_URL . 'assets/css/tooltipster.bundle.min.css'
        //     );
        //     wp_enqueue_script(
        //         'essential_addons_elementor-tooltipster-js',
        //         ESSENTIAL_ADDONS_EL_URL . 'assets/js/tooltipster.bundle.min.js',
        //         array('jquery'), '1.0', true
        //     );
        // }

        // if ($is_component_active['progress-bar']) {
        //     wp_enqueue_script(
        //         'essential_addons_elementor-progress-bar',
        //         ESSENTIAL_ADDONS_EL_URL . 'assets/js/progress-bar.js',
        //         array('jquery'), '1.0', true
        //     );
        // }

        // if ($is_component_active['section-particles']) {
        //     wp_enqueue_script(
        //         'particles-js',
        //         ESSENTIAL_ADDONS_EL_URL . 'assets/js/particles.js',
        //         ['jquery'], '1.0', true
        //     );

        //     $preset_themes = require ESSENTIAL_ADDONS_EL_PATH . 'extensions/eael-particle-section/particle-themes.php';
        //     wp_localize_script(
        //         'particles-js',
        //         'ParticleThemesData',
        //         $preset_themes
        //     );
        // }
    }
}
