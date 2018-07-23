<?php
namespace Elementor;

function eael_elementor_init(){
    Plugin::instance()->elements_manager->add_category(
        'essential-addons-elementor',
        [
            'title'  => 'Essential Addons',
            'icon' => 'font'
        ],
        1
    );
}
add_action('elementor/init','Elementor\eael_elementor_init');



