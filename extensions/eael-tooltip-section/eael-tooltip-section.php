<?php

if( !defined( 'ABSPATH' ) ) exit;

use Elementor\Elementor_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Element_Base;
use Elementor\Widget_Base;

class EAEL_Tooltip_Section extends Module_Base {

    public function __construct() {
        parent::__construct();

	    add_action( 'elementor/element/section/section_advanced/after_section_end',[ $this, 'register_controls' ], 10 );
	    add_action('elementor/element/common/_section_style/after_section_end', [ $this,'register_controls'], 10 );

	    add_action( 'elementor/frontend/section/before_render',array( $this,'before_render') );
	    add_action( 'elementor/frontend/section/after_render',array( $this,'after_render') );

//	    add_action( 'elementor/widget/before_render_content',[ $this,'before_render' ] );
//	    add_action( 'elementor/widget/render_content',[ $this,'after_render' ] );

    }

    public function get_name() {
        return 'eael-tooltip-section';
    }

    public function register_controls( $element ) {

        $element->start_controls_section(
            'eael_tooltip_section',
            [
                'label' => __( 'EA Tooltip', 'essential-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_ADVANCED
            ]
        );

        $element->add_control(
            'eael_tooltip_section_enable',
            [
                'label' => __( 'Enable Tooltip', 'essential-addons-elementor' ),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

	    $element->start_controls_tabs( 'eael_tooltip_tabs' );

		    $element->start_controls_tab( 'eael_tooltip_settings', [
			    'label' 	=> __( 'Settings', 'essential-addons-elementor' ),
			    'condition'	=> [
				    'eael_tooltip_section_enable!' => '',
			    ],
		    ] );

		    $element->add_control(
			    'eael_tooltip_section_content',
			    [
				    'label'   => __( 'Content', 'essential-addons-elementor' ),
				    'type'    => Controls_Manager::TEXT,
				    'default' 		=> __( 'I am a tooltip', 'essential-addons-elementor' ),
				    'dynamic' 		=> [ 'active' => true ],
				    'frontend_available'	=> true,
				    'condition'	=> [
						'eael_tooltip_section_enable!' => '',
					],
			    ]
		    );

		    $element->add_control(
			    'eael_tooltip_section_target',
			    [
				    'label'   => __( 'Target', 'essential-addons-elementor' ),
				    'type'    => Controls_Manager::SELECT,
				    'default' 	=> 'element',
				    'options' 	=> [
					    'element' 	=> __( 'Element', 'essential-addons-elementor' ),
					    'custom' 	=> __( 'Custom', 'essential-addons-elementor' ),
				    ],
				    'frontend_available' => true,
				    'condition'	=> [
						'eael_tooltip_section_enable!' => '',
					],
			    ]
		    );

		    $element->add_control(
			    'eael_tooltip_section_selector',
			    [
				    'label'   => __( 'CSS Selector', 'essential-addons-elementor' ),
				    'type'    => Controls_Manager::TEXT,
				    'description'	=> __( 'Use a CSS selector for any html element WITHIN this element.', 'essential-addons-elementor' ),
				    'default' 		=> '',
				    'placeholder' 	=> __( '.css-selector', 'essential-addons-elementor' ),
				    'frontend_available'	=> true,
				    'condition'	=> [
						'eael_tooltip_section_enable!' => '',
						'eael_tooltip_section_target' => 'custom',
					],
			    ]
		    );

		    $element->add_control(
			    'eael_tooltip_section_trigger',
			    [
				    'label'   => __( 'Trigger', 'essential-addons-elementor' ),
				    'type'    => Controls_Manager::SELECT,
				    'default' 	=> 'hover',
				    'options' 	=> [
					    'hover' 	=> __( 'Hover', 'essential-addons-elementor' ),
					    'click' 	=> __( 'Click', 'essential-addons-elementor' ),
					    'load' 		=> __( 'Page Load', 'essential-addons-elementor' ),
				    ],
				    'frontend_available' => true,
				    'condition'	=> [
						'eael_tooltip_section_enable!' => '',
					],
			    ]
		    );

		    $element->add_control(
			    'eael_tooltip_section_disable',
			    [
				    'label'   => __( 'Disable On', 'essential-addons-elementor' ),
				    'type'    => Controls_Manager::SELECT,
				    'default' 	=> '',
				    'options' 	=> [
					    '' 			=> __( 'None', 'essential-addons-elementor' ),
					    'tablet' 	=> __( 'Tablet & Mobile', 'essential-addons-elementor' ),
					    'mobile' 	=> __( 'Mobile', 'essential-addons-elementor' ),
				    ],
				    'frontend_available' => true,
				    'condition'	=> [
						'eael_tooltip_section_enable!' => '',
					],
			    ]
		    );

			$element->add_control(
			    'eael_tooltip_section_position',
			    [
				    'label'   => __( 'Position', 'essential-addons-elementor' ),
				    'type'    => Controls_Manager::SELECT,
				    'default' 	=> '',
				    'options' 	=> [
					    '' 			=> __( 'Global', 'essential-addons-elementor' ),
					    'bottom' 	=> __( 'Bottom', 'essential-addons-elementor' ),
					    'left' 		=> __( 'Left', 'essential-addons-elementor' ),
					    'top' 		=> __( 'Top', 'essential-addons-elementor' ),
					    'right' 	=> __( 'Right', 'essential-addons-elementor' ),
				    ],
				    'frontend_available' => true,
				    'condition'	=> [
						'eael_tooltip_section_enable!' => '',
					],
			    ]
		    );

			$element->add_control(
			    'eael_tooltip_section_delay_out',
			    [
				    'label'   => __( 'Delay out (s)', 'essential-addons-elementor' ),
				    'type'    => Controls_Manager::SLIDER,
				    'range' 	=> [
					    'px' 	=> [
						    'min' 	=> 0,
						    'max' 	=> 1,
						    'step'	=> 0.1,
					    ],
				    ],
				    'frontend_available' => true,
				    'condition'	=> [
						'eael_tooltip_section_enable!' => '',
					],
			    ]
		    );

		    $element->add_control(
			    'eael_tooltip_section_duration',
			    [
				    'label'   => __( 'Duration', 'essential-addons-elementor' ),
				    'type'    => Controls_Manager::SLIDER,
				    'range' 	=> [
					    'px' 	=> [
						    'min' 	=> 0,
						    'max' 	=> 2,
						    'step'	=> 0.1,
					    ],
				    ],
				    'frontend_available' => true,
				    'condition'	=> [
					    'eael_tooltip_section_enable!' => '',
				    ],
			    ]
		    );

	        $element->end_controls_tab();

	        $element->start_controls_tab( 'eael_tooltip_section_styles', [
			    'label' 	=> __( 'Styles', 'essential-addons-elementor' ),
			    'condition'	=> [
				    'eael_tooltip_section_enable!' => '',
			    ],
		    ] );

		    $element->add_control(
			    'eael_tooltip_section_width',
			    [
				    'label' 		=> __( 'Max Width', 'essential-addons-elementor' ),
				    'type' 			=> Controls_Manager::SLIDER,
				    'default' 	=> [
					    'size' 	=> '',
				    ],
				    'range' 	=> [
					    'px' 	=> [
						    'min' 	=> 0,
						    'max' 	=> 500,
					    ],
				    ],
				    'label_block'	=> false,
				    'selectors'		=> [
					    '.ee-tooltip.ee-tooltip-{{ID}}' => 'max-width: {{SIZE}}{{UNIT}};',
				    ],
				    'condition'	=> [
					    'eael_tooltip_section_enable!' => '',
				    ],
			    ]
		    );

		    $element->add_control(
			    'eael_tooltip_section_distance',
			    [
				    'label' 		=> __( 'Distance', 'essential-addons-elementor' ),
				    'type' 			=> Controls_Manager::SLIDER,
				    'size_units' 	=> [ 'px' ],
				    'label_block'	=> false,
				    'selectors'		=> [
					    '.ee-tooltip.ee-tooltip-{{ID}}.to--top' 		=> 'transform: translateY(-{{SIZE}}{{UNIT}});',
					    '.ee-tooltip.ee-tooltip-{{ID}}.to--bottom' 		=> 'transform: translateY({{SIZE}}{{UNIT}});',
					    '.ee-tooltip.ee-tooltip-{{ID}}.to--left' 		=> 'transform: translateX(-{{SIZE}}{{UNIT}});',
					    '.ee-tooltip.ee-tooltip-{{ID}}.to--right' 		=> 'transform: translateX({{SIZE}}{{UNIT}});',
				    ],
				    'condition'	=> [
					    'eael_tooltip_section_enable!' => '',
				    ],
			    ]
		    );

		    $element->add_control(
			    'eael_tooltip_section_arrow',
			    [
				    'label'		=> __( 'Arrow', 'essential-addons-elementor' ),
				    'type' 		=> Controls_Manager::SELECT,
				    'default' 	=> '',
				    'options' 	=> [
					    '' 		=> __( 'Show', 'essential-addons-elementor' ),
					    'none' 	=> __( 'Hide', 'essential-addons-elementor' ),
				    ],
				    'selectors' => [
					    '.ee-tooltip.ee-tooltip-{{ID}}:after' => 'content: {{VALUE}};',
				    ],
				    'condition'	=> [
					    'eael_tooltip_section_enable!' => '',
				    ],
			    ]
		    );

		    $element->add_control(
			    'eael_tooltip_section_align',
			    [
				    'label' 	=> __( 'Text Align', 'essential-addons-elementor' ),
				    'type' 		=> Controls_Manager::CHOOSE,
				    'options' 	=> [
					    'left' 	=> [
						    'title' 	=> __( 'Left', 'essential-addons-elementor' ),
						    'icon' 		=> 'fa fa-align-left',
					    ],
					    'center' 	=> [
						    'title' => __( 'Center', 'essential-addons-elementor' ),
						    'icon' 	=> 'fa fa-align-center',
					    ],
					    'right' 	=> [
						    'title' => __( 'Right', 'essential-addons-elementor' ),
						    'icon'	=> 'fa fa-align-right',
					    ],
				    ],
				    'selectors' => [
					    '.ee-tooltip.ee-tooltip-{{ID}}' => 'text-align: {{VALUE}};',
				    ],
				    'condition'	=> [
					    'eael_tooltip_section_enable!' => '',
				    ],
			    ]
		    );

		    $element->add_control(
			    'eael_tooltip_section_padding',
			    [
				    'label' 		=> __( 'Padding', 'essential-addons-elementor' ),
				    'type' 			=> Controls_Manager::DIMENSIONS,
				    'size_units' 	=> [ 'px', 'em', '%' ],
				    'selectors' 	=> [
					    '.ee-tooltip.ee-tooltip-{{ID}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				    ],
				    'condition'	=> [
					    'eael_tooltip_section_enable!' => '',
				    ],
			    ]
		    );

		    $element->add_control(
			    'eael_tooltip_section_border_radius',
			    [
				    'label' 		=> __( 'Border Radius', 'essential-addons-elementor' ),
				    'type' 			=> Controls_Manager::DIMENSIONS,
				    'size_units' 	=> [ 'px', '%' ],
				    'selectors' 	=> [
					    '.ee-tooltip.ee-tooltip-{{ID}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				    ],
				    'condition'	=> [
					    'eael_tooltip_section_enable!' => '',
				    ],
			    ]
		    );

		    $element->add_group_control(
			    Group_Control_Typography::get_type(),
			    [
				    'name' 		=> 'eael_tooltip_section_typography',
				    'selector' 	=> '.ee-tooltip.ee-tooltip-{{ID}}',
				    'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
				    'separator' => 'after',
				    'condition'	=> [
					    'eael_tooltip_section_enable!' => '',
				    ],
			    ]
		    );

		    $element->add_control(
			    'eael_tooltip_section_background_color',
			    [
				    'label' 	=> __( 'Background Color', 'essential-addons-elementor' ),
				    'type' 		=> Controls_Manager::COLOR,
				    'selectors' => [
					    '.ee-tooltip.ee-tooltip-{{ID}}' 					=> 'background-color: {{VALUE}};',
					    '.ee-tooltip.ee-tooltip-{{ID}}.to--top:after' 		=> 'border-top-color: {{VALUE}};',
					    '.ee-tooltip.ee-tooltip-{{ID}}.to--left:after' 		=> 'border-left-color: {{VALUE}};',
					    '.ee-tooltip.ee-tooltip-{{ID}}.to--right:after' 	=> 'border-right-color: {{VALUE}};',
					    '.ee-tooltip.ee-tooltip-{{ID}}.to--bottom:after' 	=> 'border-bottom-color: {{VALUE}};',
				    ],
				    'condition'	=> [
					    'eael_tooltip_section_enable!' => '',
				    ],
			    ]
		    );

		    $element->add_control(
			    'eael_tooltip_section_color',
			    [
				    'label' 	=> __( 'Color', 'essential-addons-elementor' ),
				    'type' 		=> Controls_Manager::COLOR,
				    'selectors' => [
					    '.ee-tooltip.ee-tooltip-{{ID}}' 		=> 'color: {{VALUE}};',
				    ],
				    'condition'	=> [
					    'eael_tooltip_section_enable!' => '',
				    ],
			    ]
		    );

		    $element->add_group_control(
			    Group_Control_Box_Shadow::get_type(),
			    [
				    'name' 		=> 'eael_tooltip_section_box_shadow',
				    'selector' => '.ee-tooltip.ee-tooltip-{{ID}}',
				    'separator'	=> '',
			    ]
		    );

	        $element->end_controls_tab();

	    $element->end_controls_tabs();

	    $element->end_controls_section();

    }

    public function before_render( $element ) {

        if( $element->get_settings('eael_tooltip_section_enable') == 'yes' ){

            $element->add_render_attribute( 'eael-section-tooltip', [
            	'id' => 'eael-section-tooltip-' . $element->get_id(),
            	'class' => 'eael-section-tooltip',
            ]);

        }

    }

    public function after_render( $element ) {

	    $data     = $element->get_data();
	    $settings = $element->get_settings_for_display();

	    ?>

	    <span <?php echo $element->get_render_attribute_string( 'eael-section-tooltip' ); ?> data-tippy="Tooltip">
	        <?php echo $this->parse_text_editor( $settings['eael_tooltip_section_content'], $element ); ?>
	    </span>

<?php }

	/**
	 * Parse text editor.
	 *
	 * Parses the content from rich text editor with shortcodes, oEmbed and
	 * filtered data.
	 *
	 * @since 1.9.12
	 * @access protected
	 *
	 * @param string $content Text editor content.
	 *
	 * @return string Parsed content.
	 */
	protected function parse_text_editor( $content, $element ) {
		/** This filter is documented in wp-includes/widgets/class-wp-widget-text.php */
		$content = apply_filters( 'widget_text', $content, $element->get_settings() );

		$content = shortcode_unautop( $content );
		$content = do_shortcode( $content );
		$content = wptexturize( $content );

		if ( $GLOBALS['wp_embed'] instanceof \WP_Embed ) {
			$content = $GLOBALS['wp_embed']->autoembed( $content );
		}

		return $content;
	}
}

EAEL_Tooltip_Section::instance();