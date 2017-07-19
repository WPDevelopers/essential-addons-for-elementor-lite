<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


class Widget_Eael_Countdown extends Widget_Base {

	public function get_name() {
		return 'eael-countdown';
	}

	public function get_title() {
		return esc_html__( 'EA Countdown', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'eicon-countdown';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}
	
	
	protected function _register_controls() {

		
  		$this->start_controls_section(
  			'eael_section_countdown_settings_general',
  			[
  				'label' => esc_html__( 'Countdown Settings', 'essential-addons-elementor' )
  			]
  		);
		
		$this->add_control(
			'eael_countdown_due_time',
			[
				'label' => esc_html__( 'Countdown Due Date', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DATE_TIME,
				'default' => date("Y-m-d", strtotime("+ 1 day")),
				'description' => esc_html__( 'Set the due date and time', 'essential-addons-elementor' ),
			]
		);

		$this->add_control(
			'eael_countdown_label_view',
			[
				'label' => esc_html__( 'Label Position', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'eael-countdown-label-block',
				'options' => [
					'eael-countdown-label-block' => esc_html__( 'Block', 'essential-addons-elementor' ),
					'eael-countdown-label-inline' => esc_html__( 'Inline', 'essential-addons-elementor' ),
				],
			]
		);

		$this->add_responsive_control(
			'eael_countdown_label_padding_left',
			[
				'label' => esc_html__( 'Left spacing for Labels', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'description' => esc_html__( 'Use when you select inline labels', 'essential-addons-elementor' ),
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-label' => 'padding-left:{{SIZE}}px;',
				],
				'condition' => [
					'eael_countdown_label_view' => 'eael-countdown-label-inline',
				],
			]
		);


		$this->end_controls_section();

  		$this->start_controls_section(
  			'eael_section_countdown_settings_content',
  			[
  				'label' => esc_html__( 'Content Settings', 'essential-addons-elementor' )
  			]
  		);


		$this->add_control(
			'eael_countdown_days',
			[
				'label' => esc_html__( 'Display Days', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'eael_countdown_days_label',
			[
				'label' => esc_html__( 'Custom Label for Days', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Days', 'essential-addons-elementor' ),
				'description' => esc_html__( 'Leave blank to hide', 'essential-addons-elementor' ),
				'condition' => [
					'eael_countdown_days' => 'yes',
				],
			]
		);
		

		$this->add_control(
			'eael_countdown_hours',
			[
				'label' => esc_html__( 'Display Hours', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'eael_countdown_hours_label',
			[
				'label' => esc_html__( 'Custom Label for Hours', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Hours', 'essential-addons-elementor' ),
				'description' => esc_html__( 'Leave blank to hide', 'essential-addons-elementor' ),
				'condition' => [
					'eael_countdown_hours' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_countdown_minutes',
			[
				'label' => esc_html__( 'Display Minutes', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'eael_countdown_minutes_label',
			[
				'label' => esc_html__( 'Custom Label for Minutes', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Minutes', 'essential-addons-elementor' ),
				'description' => esc_html__( 'Leave blank to hide', 'essential-addons-elementor' ),
				'condition' => [
					'eael_countdown_minutes' => 'yes',
				],
			]
		);
			
		$this->add_control(
			'eael_countdown_seconds',
			[
				'label' => esc_html__( 'Display Seconds', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'eael_countdown_seconds_label',
			[
				'label' => esc_html__( 'Custom Label for Seconds', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Seconds', 'essential-addons-elementor' ),
				'description' => esc_html__( 'Leave blank to hide', 'essential-addons-elementor' ),
				'condition' => [
					'eael_countdown_seconds' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_countdown_separator_heading',
			[
				'label' => __( 'Countdown Separator', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_countdown_separator',
			[
				'label' => esc_html__( 'Display Separator', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'eael-countdown-show-separator',
				'default' => '',
			]
		);

		$this->add_control(
			'eael_countdown_separator_color',
			[
				'label' => esc_html__( 'Separator Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'eael_countdown_separator' => 'eael-countdown-show-separator',
				],
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-digits::after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_countdown_separator_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .eael-countdown-digits::after',
				'condition' => [
					'eael_countdown_separator' => 'eael-countdown-show-separator',
				],
			]
		);


		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'eael_section_countdown_styles_general',
			[
				'label' => esc_html__( 'Countdown Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);
		
		$this->add_control(
			'eael_countdown_background',
			[
				'label' => esc_html__( 'Box Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#262625',
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-item > div' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_countdown_spacing',
			[
				'label' => esc_html__( 'Space Between Boxes', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-item > div' => 'margin-right:{{SIZE}}px; margin-left:{{SIZE}}px;',
					'{{WRAPPER}} .eael-countdown-container' => 'margin-right: -{{SIZE}}px; margin-left: -{{SIZE}}px;',
				],
			]
		);
		
		$this->add_responsive_control(
			'eael_countdown_container_margin_bottom',
			[
				'label' => esc_html__( 'Space Below Container', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-container' => 'margin-bottom:{{SIZE}}px;',
				],
			]
		);
		
		$this->add_responsive_control(
			'eael_countdown_box_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-item > div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_countdown_box_border',
				'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
				'selector' => '{{WRAPPER}} .eael-countdown-item > div',
			]
		);

		$this->add_control(
			'eael_countdown_box_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-item > div' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
			]
		);
		
		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'eael_section_countdown_styles_content',
			[
				'label' => esc_html__( 'Color &amp; Typography', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_countdown_digits_heading',
			[
				'label' => __( 'Countdown Digits', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_countdown_digits_color',
			[
				'label' => esc_html__( 'Digits Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fec503',
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-digits' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_countdown_digit_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .eael-countdown-digits',
			]
		);

		$this->add_control(
			'eael_countdown_label_heading',
			[
				'label' => __( 'Countdown Labels', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_countdown_label_color',
			[
				'label' => esc_html__( 'Label Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-label' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_countdown_label_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .eael-countdown-label',
			]
		);		


		$this->end_controls_section();


		
		$this->start_controls_section(
			'eael_section_countdown_styles_individual',
			[
				'label' => esc_html__( 'Individual Box Styling', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_countdown_days_label_heading',
			[
				'label' => __( 'Days', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_countdown_days_background_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-item > div.eael-countdown-days' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_countdown_days_digit_color',
			[
				'label' => esc_html__( 'Digit Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-days .eael-countdown-digits' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_countdown_days_label_color',
			[
				'label' => esc_html__( 'Label Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-days .eael-countdown-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_countdown_days_border_color',
			[
				'label' => esc_html__( '', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-item > div.eael-countdown-days' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_countdown_hours_label_heading',
			[
				'label' => __( 'Hours', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_countdown_hours_background_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-item > div.eael-countdown-hours' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_countdown_hours_digit_color',
			[
				'label' => esc_html__( 'Digit Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-hours .eael-countdown-digits' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_countdown_hours_label_color',
			[
				'label' => esc_html__( 'Label Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-hours .eael-countdown-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_countdown_hours_border_color',
			[
				'label' => esc_html__( 'Border Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-item > div.eael-countdown-hours' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_countdown_minutes_label_heading',
			[
				'label' => __( 'Minutes', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_countdown_minutes_background_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-item > div.eael-countdown-minutes' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_countdown_minutes_digit_color',
			[
				'label' => esc_html__( 'Digit Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-minutes .eael-countdown-digits' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_countdown_minutes_label_color',
			[
				'label' => esc_html__( 'Label Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-minutes .eael-countdown-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_countdown_minutes_border_color',
			[
				'label' => esc_html__( 'Border Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-item > div.eael-countdown-minutes' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_countdown_seconds_label_heading',
			[
				'label' => __( 'Seconds', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_countdown_seconds_background_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-item > div.eael-countdown-seconds' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_countdown_seconds_digit_color',
			[
				'label' => esc_html__( 'Digit Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-seconds .eael-countdown-digits' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_countdown_seconds_label_color',
			[
				'label' => esc_html__( 'Label Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-seconds .eael-countdown-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_countdown_seconds_border_color',
			[
				'label' => esc_html__( 'Border Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-countdown-item > div.eael-countdown-seconds' => 'border-color: {{VALUE}};',
				],
			]
		);


		$this->end_controls_section();
		

	}


	protected function render( ) {
		
      $settings = $this->get_settings();
		
		$get_due_date =  esc_attr($settings['eael_countdown_due_time']);
		$due_date = date("M d Y G:i:s", strtotime($get_due_date));
		
	
	?>

	<div class="eael-countdown-wrapper">
		<div class="eael-countdown-container <?php echo esc_attr($settings['eael_countdown_label_view'] ); ?> <?php echo esc_attr($settings['eael_countdown_separator'] ); ?>">		
			<ul id="eael-countdown-<?php echo esc_attr($this->get_id()); ?>" class="eael-countdown-items" data-date="<?php echo esc_attr($due_date) ; ?>">
			    <?php if ( ! empty( $settings['eael_countdown_days'] ) ) : ?><li class="eael-countdown-item"><div class="eael-countdown-days"><span data-days class="eael-countdown-digits">00</span><?php if ( ! empty( $settings['eael_countdown_days_label'] ) ) : ?><span class="eael-countdown-label"><?php echo esc_attr($settings['eael_countdown_days_label'] ); ?></span><?php endif; ?></div></li><?php endif; ?>
			    <?php if ( ! empty( $settings['eael_countdown_hours'] ) ) : ?><li class="eael-countdown-item"><div class="eael-countdown-hours"><span data-hours class="eael-countdown-digits">00</span><?php if ( ! empty( $settings['eael_countdown_hours_label'] ) ) : ?><span class="eael-countdown-label"><?php echo esc_attr($settings['eael_countdown_hours_label'] ); ?></span><?php endif; ?></div></li><?php endif; ?>
			   <?php if ( ! empty( $settings['eael_countdown_minutes'] ) ) : ?><li class="eael-countdown-item"><div class="eael-countdown-minutes"><span data-minutes class="eael-countdown-digits">00</span><?php if ( ! empty( $settings['eael_countdown_minutes_label'] ) ) : ?><span class="eael-countdown-label"><?php echo esc_attr($settings['eael_countdown_minutes_label'] ); ?></span><?php endif; ?></div></li><?php endif; ?>
			   <?php if ( ! empty( $settings['eael_countdown_seconds'] ) ) : ?><li class="eael-countdown-item"><div class="eael-countdown-seconds"><span data-seconds class="eael-countdown-digits">00</span><?php if ( ! empty( $settings['eael_countdown_seconds_label'] ) ) : ?><span class="eael-countdown-label"><?php echo esc_attr($settings['eael_countdown_seconds_label'] ); ?></span><?php endif; ?></div></li><?php endif; ?>
			</ul>
			<div class="clearfix"></div>
		</div>
	</div>


	<script type="text/javascript">
	jQuery(document).ready(function($) {
		'use strict';
		$("#eael-countdown-<?php echo esc_attr($this->get_id()); ?>").countdown();
	});
	</script>
	
	<?php
	
	}

	protected function content_template() {
		
		?>
		
	
		<?php
	}
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Countdown() );