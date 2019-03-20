<?php

namespace Essential_Addons_Elementor\Traits;

use Essential_Addons_EL;
use MatthiasMullie\Minify;

trait Enqueue_Handler
{
    public function generate_editor_scripts()
    {
        $active_components = Essential_Addons_EL::eael_activated_modules();
        $paths = array();

        foreach ($active_components as $key => $component) {
            switch ($key) {
                case 'adv-accordion':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/' . $key . '/index.js';
                    break;

                case 'adv-tabs':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/' . $key . '/index.js';
                    break;

                case 'fancy-text':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/vendor/fancy-text/fancy-text.js';
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/' . $key . '/index.js';
                    break;

                case 'count-down':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/vendor/countdown/countdown.min.js';
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/' . $key . '/index.js';
                    break;
                
                case 'data-table':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/' . $key . '/index.js';
                    break;

                case 'filterable-gallery':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/vendor/isotope/isotope.pkgd.min.js';
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/vendor/magnific-popup/jquery.magnific-popup.min.js';
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/' . $key . '/index.js';
                    break;

                case 'image-accordion':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/' . $key . '/index.js';
                    break;

                case 'post-timeline':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/vendor/load-more/load-more.js';
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/' . $key . '/index.js';
                    break;

                case 'price-table':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/vendor/tooltipster/tooltipster.bundle.min.js';
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/' . $key . '/index.js';
                    break;

                case 'progress-bar':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/vendor/progress-bar/progress-bar.js';
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/' . $key . '/index.js';
                    break;

                case 'twitter-feed':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/vendor/isotope/isotope.pkgd.min.js';
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/social-feeds/codebird.js';
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/social-feeds/doT.min.js';
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/social-feeds/moment.js';
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/social-feeds/jquery.socialfeed.js';
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/' . $key . '/index.js';
                    break;

                case 'post-grid':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/vendor/isotope/isotope.pkgd.min.js';
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/vendor/load-more/load-more.js';
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/' . $key . '/index.js';
                    break;
            }
        }

        $minifier = new Minify\JS($paths);
        $minifier->minify(WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'eael.js');

    }


    public function generate_editor_style()
    {
        $active_components = Essential_Addons_EL::eael_activated_modules();
        $paths = array();

        foreach( $active_components as $key => $component ) {
            switch($key) {
                case 'contact-form-7':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/contact-form7.css';
                    break;

                case 'count-down':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/countdown.css';
                    break;

                case 'creative-btn':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/creative-button.css';
                    break;

                case 'fancy-text':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/fancy-text.css';
                    break;

                case 'img-comparison':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/';
                    break;

                case 'instagram-gallery':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/';
                    break;

                case 'interactive-promo':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/';
                    break;

                case 'lightbox':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/';
                    break;

                case 'post-block':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/';
                    break;

                case 'post-grid':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/post-grid.css';
                    break;

                case 'post-timeline':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/post-timeline.css';
                    break;

                case 'product-grid':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/woo-products.css';
                    break;

                case 'team-members':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/team-members.css';
                    break;

                case 'testimonial-slider':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/testimonial-slider.css';
                    break;

                case 'testimonials':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/';
                    break;

                case 'weforms':
                        $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/weform.css';
                    break;

                case 'static-product':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/';
                    break;

                case 'call-to-action':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/call-to-action.css';
                    break;

                case 'flip-box':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/flipbox.css';
                    break;

                case 'info-box':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/infobox.css';
                    break;

                case 'dual-header':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/dual-color-heading.css';
                    break;

                case 'price-table':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/pricing-table.css';
                    break;

                case 'flip-carousel':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/flipbox.css';
                    break;

                case 'interactive-cards':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/';
                    break;

                case 'ninja-form':
                        $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/ninja-form.css';
                    break;

                case 'gravity-form':
                        $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/gravity-form.css';
                    break;

                case 'caldera-form':
                        $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/caldera-form.css';
                    break;
        
                case 'data-table':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/data-table.css';
                    break;

                case 'filter-gallery':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/filterable-gallery.css';
                    break;

                case 'image-accordion':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/img-accordion.css';
                    break;

                case 'content-ticker':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/content-ticker.css';
                    break;

                case 'tooltip':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/tooltip.css';
                    break;

                case 'adv-accordion':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/advance-accordion.css';
                    break;

                case 'adv-tabs':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/advance-tabs.css';
                    break;

                case 'progress-bar':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/progress-bar.css';
                    break;

                case 'feature-list':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/css/feature-list.css';
                    break;

            }
        }
    }
}
