<?php

namespace Elementor;
use Elementor\Group_Control_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function eael_elementor_init(){
    Plugin::instance()->elements_manager->add_category(
        'essential-addons-elementor',
        [
            'title'  => 'Essential Addons',
            'icon' => 'font'
        ],
        1
    );

    /**
     * Initialize EAE_Helper
     */
    new EAE_Helper;

}
add_action('elementor/init','Elementor\eael_elementor_init');


class EAE_Helper {

    

}


