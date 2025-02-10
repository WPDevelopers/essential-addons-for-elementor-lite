<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Typography;
use \Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use \Elementor\Widget_Base;
use \Elementor\Repeater;
use Essential_Addons_Elementor\Classes\Helper as HelperClass;


class Fancy_Text extends Widget_Base {


	public function get_name() {
		return 'eael-fancy-text';
	}

	public function get_title() {
		return esc_html__( 'Fancy Text', 'essential-addons-for-elementor-lite');
	}

	public function get_icon() {
		return 'eaicon-fancy-text';
	}

    public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

    public function get_keywords() {
        return [
			'ea fancy text',
			'ea typing text',
			'animated headline',
			'Headline',
			'typewriter',
			'text effect',
			'text typing effect',
			'text animation',
			'animated heading',
			'ea',
			'essential addons'
		];
    }

	public function get_style_depends() {
        return [
            'e-animations',
        ];
    }

	protected function is_dynamic_content():bool {
        return false;
    }

	public function has_widget_inner_wrapper(): bool {
        return ! HelperClass::eael_e_optimized_markup();
    }

    public function get_custom_help_url() {
        return 'https://essential-addons.com/elementor/docs/fancy-text/';
    }

	protected function register_controls() {

		// Content Controls
  		$this->start_controls_section(
  			'eael_fancy_text_content',
  			[
  				'label' => esc_html__( 'Fancy Text', 'essential-addons-for-elementor-lite')
  			]
  		);


		$this->add_control(
			'eael_fancy_text_prefix',
			[
				'label'       => esc_html__( 'Prefix Text', 'essential-addons-for-elementor-lite'),
				'placeholder' => esc_html__( 'Place your prefix text', 'essential-addons-for-elementor-lite'),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'This is the ', 'essential-addons-for-elementor-lite'),
				'dynamic'     => [ 'active' => true ],
				'ai' => [
					'active' => false,
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'eael_fancy_text_strings_text_field',
			[
				'label'			=> esc_html__( 'Fancy String', 'essential-addons-for-elementor-lite'),
				'type'			=> Controls_Manager::TEXT,
				'label_block'	=> true,
				'dynamic'		=> [ 'active' => true ],
				'ai' => [
					'active' => false,
				],
			]
		);

		$this->add_control(
			'eael_fancy_text_strings',
			[
				'label'       => __( 'Fancy Text Strings', 'essential-addons-for-elementor-lite'),
				'type'        => Controls_Manager::REPEATER,
				'show_label'  => true,
				'fields'      =>  $repeater->get_controls(),
				'title_field' => '{{ eael_fancy_text_strings_text_field }}',
				'default'     => [
					[
						'eael_fancy_text_strings_text_field' => __( 'First string', 'essential-addons-for-elementor-lite'),
					],
					[
						'eael_fancy_text_strings_text_field' => __( 'Second string', 'essential-addons-for-elementor-lite'),
					],
					[
						'eael_fancy_text_strings_text_field' => __( 'Third string', 'essential-addons-for-elementor-lite'),
					]
				],
			]
		);

		$this->add_control(
			'eael_fancy_text_suffix',
			[
				'label'       => esc_html__( 'Suffix Text', 'essential-addons-for-elementor-lite'),
				'placeholder' => esc_html__( 'Place your suffix text', 'essential-addons-for-elementor-lite'),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( ' of the sentence.', 'essential-addons-for-elementor-lite'),
				'dynamic'     => [ 'active' => true ],
				'ai' => [
					'active' => false,
				],
			]
		);

		$this->end_controls_section();

		// Settings Control
  		$this->start_controls_section(
  			'eael_fancy_text_settings',
  			[
  				'label' => esc_html__( 'Fancy Text Settings', 'essential-addons-for-elementor-lite')
  			]
		);

		$style_options = apply_filters(
			'fancy_text_style_types',
			[
				'styles'	=> [
					'style-1' => esc_html__( 'Style 1', 'essential-addons-for-elementor-lite'),
					'style-2' => esc_html__( 'Style 2 (Pro)', 'essential-addons-for-elementor-lite'),
				],
				'conditions'	=> ['style-2']
			]
		);

  		$this->add_control(
			'eael_fancy_text_style',
			[
				'label'   => esc_html__( 'Style Type', 'essential-addons-for-elementor-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'style-1',
				'options' => $style_options['styles']
			]
		);

		$this->add_control(
			'eael_fancy_text_style_pro_alert',
			[
				'label'     => esc_html__( 'Only available in pro version!', 'essential-addons-for-elementor-lite'),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'eael_fancy_text_style' => $style_options['conditions'],
				]
			]
		);

		$this->add_responsive_control(
			'eael_fancy_text_alignment',
			[
				'label' => esc_html__( 'Alignment', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'essential-addons-for-elementor-lite'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'essential-addons-for-elementor-lite'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .eael-fancy-text-container' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'eael_fancy_text_transition_type',
			[
				'label' => esc_html__( 'Animation Type', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::SELECT,
				'default' => 'typing',
				'options' => [
					'typing' => esc_html__( 'Typing', 'essential-addons-for-elementor-lite'),
					'fadeIn' => esc_html__( 'Fade', 'essential-addons-for-elementor-lite'),
					'fadeInUp' => esc_html__( 'Fade Up', 'essential-addons-for-elementor-lite'),
					'fadeInDown' => esc_html__( 'Fade Down', 'essential-addons-for-elementor-lite'),
					'fadeInLeft' => esc_html__( 'Fade Left', 'essential-addons-for-elementor-lite'),
					'fadeInRight' => esc_html__( 'Fade Right', 'essential-addons-for-elementor-lite'),
					'zoomIn' => esc_html__( 'Zoom', 'essential-addons-for-elementor-lite'),
					'bounceIn' => esc_html__( 'Bounce', 'essential-addons-for-elementor-lite'),
					'swing' => esc_html__( 'Swing', 'essential-addons-for-elementor-lite'),
				],
			]
		);


		$this->add_control(
			'eael_fancy_text_speed',
			[
				'label' => esc_html__( 'Typing Speed', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::NUMBER,
				'default' => '50',
				'condition' => [
					'eael_fancy_text_transition_type' => 'typing',
				],
			]
		);

		$this->add_control(
			'eael_fancy_text_delay',
			[
				'label' => esc_html__( 'Delay on Change', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::NUMBER,
				'default' => '2500'
			]
		);

		$this->add_control(
			'eael_fancy_text_loop',
			[
				'label' => esc_html__( 'Loop the animation', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes'
			]
		);

		$this->add_control(
			'eael_fancy_text_cursor',
			[
				'label' => esc_html__( 'Display Type Cursor', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'eael_fancy_text_transition_type' => 'typing',
				],
			]
		);


		$this->end_controls_section();


		if(!apply_filters('eael/pro_enabled', false)) {
			$this->start_controls_section(
				'eael_section_pro',
				[
					'label' => __( 'Go Premium for More Features', 'essential-addons-for-elementor-lite')
				]
			);

			$this->add_control(
				'eael_control_get_pro',
				[
					'label' => __( 'Unlock more possibilities', 'essential-addons-for-elementor-lite'),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'1' => [
							'title' => '',
							'icon' => 'fa fa-unlock-alt',
						],
					],
					'default' => '1',
					'description' => '<span class="pro-feature"> Get the  <a href="https://wpdeveloper.com/upgrade/ea-pro" target="_blank">Pro version</a> for more stunning elements and customization options.</span>'
				]
			);

			$this->end_controls_section();
		}


		$this->start_controls_section(
			'eael_fancy_text_prefix_styles',
			[
				'label' => esc_html__( 'Prefix Text Styles', 'essential-addons-for-elementor-lite'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_fancy_text_prefix_color',
			[
				'label' => esc_html__( 'Prefix Text Color', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-fancy-text-prefix' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'typography',
             'global' => [
	             'default' => Global_Typography::TYPOGRAPHY_PRIMARY
             ],
				'fields_options' => [
					'typography' => ['default' => 'yes'],
					'font_size' => ['default' => ['size' => 22]],
					'font_weight' => ['default' => 600],
					'line_height' => ['default' => ['size' => 1]],
				],
				'selector' => '{{WRAPPER}} .eael-fancy-text-prefix',
			]
		);


		$this->end_controls_section();


		$this->start_controls_section(
			'eael_fancy_text_strings_styles',
			[
				'label' => esc_html__( 'Fancy Text Styles', 'essential-addons-for-elementor-lite'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_fancy_text_color_selector',
			[
				'label' => esc_html__('Choose Background Type', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'solid-color' => [
						'title' => __('Color', 'essential-addons-for-elementor-lite'),
						'icon' => 'eicon-paint-brush',
					],
					'gradient-color' => [
						'title' => __('Gradient', 'essential-addons-for-elementor-lite'),
						'icon' => 'eicon-barcode',
					],
				],
				'toggle' => true,
				'default' => 'solid-color',
                'condition' => [
                    'eael_fancy_text_style' => 'style-1',
                ]
			]
		);

		$this->add_control(
			'eael_fancy_text_strings_background_color',
			[
				'label' => esc_html__( 'Background', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-fancy-text-strings' => 'background: {{VALUE}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'terms' => [
								[
									'name' => 'eael_fancy_text_color_selector',
									'operator' => '==',
									'value' => 'solid-color',
								],
							],
						],
						[
							'name' => 'eael_fancy_text_style',
							'operator' => '==',
							'value' => 'style-2',
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'eael_fancy_text_color_gradient',
				'types'    => ['gradient'],
				'fields_options' => [
					'background' => [
						'label' => _x( 'Gradient Color', 'Text Shadow Control', 'essential-addons-for-elementor-lite' ),
						'toggle' => false,
						'default' => 'gradient',
					],
					'color' => [
						'default' => '#062ACA',
					],
					'color_b' => [
						'default' => '#9401D9',
					]
				],
				'selector' => '{{WRAPPER}} .eael-fancy-text-strings',
				'condition' => [
					'eael_fancy_text_color_selector' => 'gradient-color',
					'eael_fancy_text_style' => 'style-1',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
            'name' => 'eael_fancy_text_strings_typography',
            'global' => [
	            'default' => Global_Typography::TYPOGRAPHY_PRIMARY
            ],
				'fields_options' => [
					'typography' => ['default' => 'yes'],
					'font_size' => ['default' => ['size' => 22]],
					'font_weight' => ['default' => 600],
				],
				'selector' => '{{WRAPPER}} .eael-fancy-text-strings, {{WRAPPER}} .typed-cursor',
			]
		);

		$this->add_control(
			'eael_fancy_text_strings_color',
			[
				'label' => esc_html__( 'Solid Color', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-fancy-text-strings' => 'color: {{VALUE}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'terms' => [
								[
									'name' => 'eael_fancy_text_style',
									'operator' => '==',
									'value' => 'style-1',
								]
							],
						],
						[
							'name' => 'eael_fancy_text_style',
							'operator' => '==',
							'value' => 'style-2',
						],
					],
				],
			]
		);

		$this->add_control(
			'eael_fancy_text_cursor_color',
			[
				'label' => esc_html__( 'Typing Cursor Color', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-fancy-text-strings::after' => 'color: {{VALUE}};',
				],
				'condition' => [
					'eael_fancy_text_cursor' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_fancy_text_strings_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-fancy-text-strings' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_fancy_text_strings_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-fancy-text-strings' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);



		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_fancy_text_strings_border',
				'selector' => '{{WRAPPER}} .eael-fancy-text-strings',
			]
		);


		$this->add_control(
			'eael_fancy_text_strings_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-fancy-text-strings' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();



		$this->start_controls_section(
			'eael_fancy_text_suffix_styles',
			[
				'label' => esc_html__( 'Suffix Text Styles', 'essential-addons-for-elementor-lite'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_fancy_text_suffix_color',
			[
				'label' => esc_html__( 'Suffix Text Color', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-fancy-text-suffix' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'ending_typography',
             'global' => [
	             'default' => Global_Typography::TYPOGRAPHY_PRIMARY
             ],
				'fields_options' => [
					'typography' => ['default' => 'yes'],
					'font_size' => ['default' => ['size' => 22]],
					'font_weight' => ['default' => 600],
					'line_height' => ['default' => ['size' => 1]],
				],
				'selector' => '{{WRAPPER}} .eael-fancy-text-suffix',
			]
		);


		$this->end_controls_section();

	}

	public function fancy_text($settings) {
		$fancy_text = array("");
		foreach ( $settings as $item ) {
			if ( ! empty( $item['eael_fancy_text_strings_text_field'] ) ) {
				$fancy_text[] = HelperClass::eael_wp_kses( html_entity_decode( $item['eael_fancy_text_strings_text_field'] ) );
			}
		}

		$fancy_text = implode("|",$fancy_text);
		return str_replace( '&', '&amp;', $fancy_text);
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$fancy_text = $this->fancy_text($settings['eael_fancy_text_strings']);
		if(!apply_filters('eael/pro_enabled', false)) { $settings['eael_fancy_text_style'] = 'style-1'; }
		$this->add_render_attribute( 'fancy-text', 'class', 'eael-fancy-text-container' );
		$this->add_render_attribute( 'fancy-text', 'class', esc_attr($settings['eael_fancy_text_style']) );
		$this->add_render_attribute( 'fancy-text', 'data-fancy-text-id', esc_attr($this->get_id()) );
		$this->add_render_attribute( 'fancy-text', 'data-fancy-text', $fancy_text );
		$this->add_render_attribute( 'fancy-text', 'data-fancy-text-transition-type', $settings['eael_fancy_text_transition_type'] );
		$this->add_render_attribute( 'fancy-text', 'data-fancy-text-speed', $settings['eael_fancy_text_speed'] );
		$this->add_render_attribute( 'fancy-text', 'data-fancy-text-delay', $settings['eael_fancy_text_delay'] );
		$this->add_render_attribute( 'fancy-text', 'data-fancy-text-cursor', $settings['eael_fancy_text_cursor'] );
		$this->add_render_attribute( 'fancy-text', 'data-fancy-text-loop', $settings['eael_fancy_text_loop'] );
	?>

	<div  <?php $this->print_render_attribute_string( 'fancy-text' ); ?> >
		<?php if ( ! empty( $settings['eael_fancy_text_prefix'] ) ) : ?>
			<span class="eael-fancy-text-prefix"><?php echo wp_kses( $settings['eael_fancy_text_prefix'], HelperClass::eael_allowed_tags() ); ?> </span>
		<?php endif; ?>

		<?php if ( $settings['eael_fancy_text_transition_type']  == 'fancy' ) : ?>
			<span id="eael-fancy-text-<?php echo esc_attr($this->get_id()); ?>" class="eael-fancy-text-strings
			<?php echo esc_attr( $settings['eael_fancy_text_color_selector'] ) ?>"></span>
		<?php endif; ?>

		<?php if ( $settings['eael_fancy_text_transition_type']  != 'fancy' ) : ?>
			<span id="eael-fancy-text-<?php echo esc_attr($this->get_id()); ?>" class="eael-fancy-text-strings <?php echo esc_attr( $settings['eael_fancy_text_color_selector'] ); ?>">
				<noscript>
					<?php
						$eael_fancy_text_strings_list = "";
						foreach ( $settings['eael_fancy_text_strings'] as $item ) {
							$eael_fancy_text_strings_list .=  $item['eael_fancy_text_strings_text_field'] . ', ';
						}
						echo wp_kses( rtrim($eael_fancy_text_strings_list, ", "), HelperClass::eael_allowed_tags() );
					?>
				</noscript>
			</span>
		<?php endif; ?>

		<?php if ( ! empty( $settings['eael_fancy_text_suffix'] ) ) : ?>
			<span class="eael-fancy-text-suffix"> <?php echo wp_kses( $settings['eael_fancy_text_suffix'], HelperClass::eael_allowed_tags() ); ?></span>
		<?php endif; ?>
	</div><!-- close .eael-fancy-text-container -->

	<div class="clearfix"></div>

	<?php

	}

}
