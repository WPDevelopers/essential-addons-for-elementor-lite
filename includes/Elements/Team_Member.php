<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Image_Size;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Background;
use Elementor\Repeater;
use \Elementor\Utils;
use \Elementor\Widget_Base;

use \Essential_Addons_Elementor\Classes\Helper as HelperClass;
class Team_Member extends Widget_Base {

	public function get_name() {
		return 'eael-team-member';
	}

	public function get_title() {
		return esc_html__( 'Team Member', 'essential-addons-for-elementor-lite');
	}

	public function get_icon() {
		return 'eaicon-team-mamber';
	}

   	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	public function get_keywords()
	{
        return [
			'team',
			'member',
			'team member',
			'ea team member',
			'ea team members',
			'person',
			'card',
			'meet the team',
			'team builder',
			'our team',
			'ea',
			'essential addons'
		];
    }

	public function get_custom_help_url()
	{
        return 'https://essential-addons.com/elementor/docs/team-members/';
    }

	protected function register_controls() {


  		$this->start_controls_section(
  			'eael_section_team_member_image',
  			[
  				'label' => esc_html__( 'Team Member Image', 'essential-addons-for-elementor-lite')
  			]
  		);


		$this->add_control(
			'eael_team_member_image',
			[
				'label' => __( 'Team Member Avatar', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);


		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
				'default' => 'full',
				'condition' => [
					'eael_team_member_image[url]!' => '',
				],
			]
		);


		$this->end_controls_section();

  		$this->start_controls_section(
  			'eael_section_team_member_content',
  			[
  				'label' => esc_html__( 'Team Member Content', 'essential-addons-for-elementor-lite')
  			]
  		);


		$this->add_control(
			'eael_team_member_name',
			[
				'label' => esc_html__( 'Name', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
				'default' => esc_html__( 'John Doe', 'essential-addons-for-elementor-lite'),
			]
		);

		$this->add_control(
			'eael_team_member_job_title',
			[
				'label' => esc_html__( 'Job Position', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
				'default' => esc_html__( 'Software Engineer', 'essential-addons-for-elementor-lite'),
			]
		);

		$this->add_control(
			'eael_team_member_description',
			[
				'label' => esc_html__( 'Description', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
				'default' => esc_html__( 'Add team member description here. Remove the text if not necessary.', 'essential-addons-for-elementor-lite'),
			]
		);


		$this->end_controls_section();


  		$this->start_controls_section(
  			'eael_section_team_member_social_profiles',
  			[
  				'label' => esc_html__( 'Social Profiles', 'essential-addons-for-elementor-lite')
  			]
  		);

		$this->add_control(
			'eael_team_member_enable_social_profiles',
			[
				'label' => esc_html__( 'Display Social Profiles?', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

        $repeater = new Repeater();

        $repeater->add_control(
            'social_new',
            [
                'label' => esc_html__( 'Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'social',
                'default' => [
                    'value' => 'fab fa-wordpress',
                    'library' => 'fa-brands',
                ],
            ]
        );

        $repeater->add_control(
            'link',
            [
                'name' => 'link',
                'label' => esc_html__( 'Link', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::URL,
                'dynamic'   => ['active' => true],
                'label_block' => true,
                'default' => [
                    'url' => '',
                    'is_external' => 'true',
                ],
                'placeholder' => esc_html__( 'Place URL here', 'essential-addons-for-elementor-lite'),
            ]
        );
		
		$this->add_control(
			'eael_team_member_social_profile_links',
			[
				'type' => Controls_Manager::REPEATER,
				'condition' => [
					'eael_team_member_enable_social_profiles!' => '',
				],
				'default' => [
					[
						'social_new' => [
							'value' => 'fab fa-facebook',
							'library' => 'fa-brands'
						]
					],
					[
						'social_new' => [
							'value' => 'fab fa-twitter',
							'library' => 'fa-brands'
						]
					],
					[
						'social_new' => [
							'value' => 'fab fa-google-plus',
							'library' => 'fa-brands'
						]
					],
					[
						'social_new' => [
							'value' => 'fab fa-linkedin',
							'library' => 'fa-brands'
						]
					],
				],
				'fields' => $repeater->get_controls(),
				'title_field' => '<i class="{{ social_new.value }}"></i>',
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
			'eael_section_team_members_styles_general',
			[
				'label' => esc_html__( 'Team Member Styles', 'essential-addons-for-elementor-lite'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$team_member_style_presets_options = apply_filters('eael_team_member_style_presets_options', [
			'eael-team-members-simple'        => esc_html__( 'Simple Style', 		'essential-addons-for-elementor-lite' ),
			'eael-team-members-overlay'       => esc_html__( 'Overlay Style', 	'essential-addons-for-elementor-lite' ),
			'eael-team-members-centered'      => esc_html__( 'Centered Style', 	'essential-addons-for-elementor-lite' ),
			'eael-team-members-circle'        => esc_html__( 'Circle Style', 	'essential-addons-for-elementor-lite' ),
			'eael-team-members-social-bottom' => esc_html__( 'Social on Bottom', 	'essential-addons-for-elementor-lite' ),
			'eael-team-members-social-right'  => esc_html__( 'Social on Right', 	'essential-addons-for-elementor-lite' ),
		]);

		$this->add_control(
			'eael_team_members_preset',
			[
				'label'   => esc_html__( 'Style Preset', 'essential-addons-for-elementor-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'eael-team-members-simple',
				'options' => $team_member_style_presets_options
			]
		);

		$team_member_style_presets_condition = apply_filters('eael_team_member_style_presets_condition', [
			'eael-team-members-centered',
			'eael-team-members-circle',
			'eael-team-members-social-bottom',
			'eael-team-members-social-right'
		]);

		$this->add_control(
			'eael_team_members_preset_pro_alert',
			[
				'label'     => esc_html__( 'Only available in pro version!', 'essential-addons-for-elementor-lite'),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'eael_team_members_preset' => $team_member_style_presets_condition
				]
			]
		);

		$this->add_control(
			'content_card_style',
			[
				'label' => __( 'Content Card', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::HEADING,
				'separator'	=> 'before'
			]
		);


		$this->add_control(
			'content_card_height',
			[
				'label' => esc_html__( 'Height', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::SLIDER,
				'size_units'	=> [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
					'em'	=> [
						'min'	=> 0,
						'max'	=> 200
					]
				],
				'default'	=> [
					'unit'	=> 'px',
					'size'	=> 'auto'
				],
				'selectors' => [
					'{{WRAPPER}} .eael-team-item .eael-team-content' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_team_members_overlay_background',
			[
				'label' => esc_html__( 'Overlay Color', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(255,255,255,0.8)',
				'selectors' => [
					'{{WRAPPER}} .eael-team-members-overlay .eael-team-content' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'eael_team_members_preset' => 'eael-team-members-overlay',
				],
			]
		);

		$this->add_control(
			'eael_team_members_background',
			[
				'label' => esc_html__( 'Content Background Color', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-team-item .eael-team-content' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_team_members_alignment',
			[
				'label' => esc_html__( 'Set Alignment', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options' => [
					'default' => [
						'title' => __( 'Default', 'essential-addons-for-elementor-lite'),
						'icon' => 'fa fa-ban',
					],
					'left' => [
						'title' => esc_html__( 'Left', 'essential-addons-for-elementor-lite'),
						'icon' => 'eicon-text-align-left',
					],
					'centered' => [
						'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'essential-addons-for-elementor-lite'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'eael-team-align-default',
				'prefix_class' => 'eael-team-align-',
			]
		);

		$this->add_responsive_control(
			'eael_team_members_padding',
			[
				'label' => esc_html__( 'Content Padding', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eael-team-item .eael-team-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_team_members_border',
				'label' => esc_html__( 'Border', 'essential-addons-for-elementor-lite'),
				'selector' => '{{WRAPPER}} .eael-team-item',
			]
		);

		$this->add_control(
			'eael_team_members_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .eael-team-item' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'eael_section_team_members_image_styles',
			[
				'label' => esc_html__( 'Team Member Image Style', 'essential-addons-for-elementor-lite'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_responsive_control(
			'eael_team_members_image_width',
			[
				'label' => esc_html__( 'Image Width', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'size_units' => [ '%', 'px' ],
				'selectors' => [
					'{{WRAPPER}} .eael-team-item figure img' => 'width:{{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'eael_team_members_preset!' => 'eael-team-members-circle'
				]
			]
		);

		do_action('eael/team_member_circle_controls', $this);


		$this->add_responsive_control(
			'eael_team_members_image_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-team-item figure img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_team_members_image_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eael-team-item figure img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_team_members_image_border',
				'label' => esc_html__( 'Border', 'essential-addons-for-elementor-lite'),
				'selector' => '{{WRAPPER}} .eael-team-item figure img',
			]
		);

		$this->add_control(
			'eael_team_members_image_rounded',
			[
				'label' => esc_html__( 'Rounded Avatar?', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'team-avatar-rounded',
				'default' => '',
			]
		);


		$this->add_control(
			'eael_team_members_image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .eael-team-item figure img' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
				'condition' => [
					'eael_team_members_image_rounded!' => 'team-avatar-rounded',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'eael_section_team_members_typography',
			[
				'label' => esc_html__( 'Color &amp; Typography', 'essential-addons-for-elementor-lite'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_team_members_name_heading',
			[
				'label' => __( 'Member Name', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_team_members_name_color',
			[
				'label' => esc_html__( 'Member Name Color', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::COLOR,
				'default' => '#272727',
				'selectors' => [
					'{{WRAPPER}} .eael-team-item .eael-team-member-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_team_members_name_typography',
				'selector' => '{{WRAPPER}} .eael-team-item .eael-team-member-name',
			]
		);

		$this->add_control(
			'eael_team_members_position_heading',
			[
				'label' => __( 'Member Job Position', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::HEADING,
				'separator'	=> 'before'
			]
		);

		$this->add_control(
			'eael_team_members_position_color',
			[
				'label' => esc_html__( 'Job Position Color', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::COLOR,
				'default' => '#272727',
				'selectors' => [
					'{{WRAPPER}} .eael-team-item .eael-team-member-position' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_team_members_position_typography',
				'selector' => '{{WRAPPER}} .eael-team-item .eael-team-member-position',
			]
		);

		$this->add_control(
			'eael_team_members_description_heading',
			[
				'label' => __( 'Member Description', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::HEADING,
				'separator'	=> 'before'
			]
		);

		$this->add_control(
			'eael_team_members_description_color',
			[
				'label' => esc_html__( 'Description Color', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::COLOR,
				'default' => '#272727',
				'selectors' => [
					'{{WRAPPER}} .eael-team-item .eael-team-content .eael-team-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_team_members_description_typography',
				'selector' => '{{WRAPPER}} .eael-team-item .eael-team-content .eael-team-text',
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'eael_section_team_members_social_profiles_styles',
			[
				'label' => esc_html__( 'Social Profiles Style', 'essential-addons-for-elementor-lite'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);


		$this->add_control(
			'eael_team_members_social_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default'	=> [
					'size'	=> 35,
					'unit'	=> 'px'
				],
				'selectors' => [
					// '{{WRAPPER}} .eael-team-member-social-link > a' => 'width: {{SIZE}}px; height: {{SIZE}}px; line-height: {{SIZE}}px;',
					'{{WRAPPER}} .eael-team-member-social-link > a i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eael-team-member-social-link > a img' => 'width: {{SIZE}}px; height: {{SIZE}}px; line-height: {{SIZE}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'eael_team_members_social_profiles_padding',
			[
				'label' => esc_html__( 'Social Profiles Margin', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eael-team-content > .eael-team-member-social-profiles' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .eael-team-image > .eael-team-member-social-profiles' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_team_members_social_icons_padding',
			[
				'label'      => esc_html__( 'Social Icon Padding', 'essential-addons-for-elementor-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-team-content > .eael-team-member-social-profiles li.eael-team-member-social-link > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .eael-team-image > .eael-team-member-social-profiles li.eael-team-member-social-link > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'eael_team_members_social_icons_spacing',
			[
				'label'      => esc_html__( 'Social Icon Distance', 'essential-addons-for-elementor-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-team-content > .eael-team-member-social-profiles li.eael-team-member-social-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .eael-team-image > .eael-team-member-social-profiles li.eael-team-member-social-link' 	=> 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'eael_team_members_social_icons_used_gradient_bg',
			[
				'label' => __( 'Use Gradient Background', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off' => __( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
			]
		);


		$this->start_controls_tabs( 'eael_team_members_social_icons_style_tabs' );

		$this->start_controls_tab( 'normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite') ] );

		$this->add_control(
			'eael_team_members_social_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::COLOR,
				'default' => '#f1ba63',
				'selectors' => [
					'{{WRAPPER}} .eael-team-member-social-link > a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_team_members_social_icon_background',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-team-member-social-link > a' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'eael_team_members_social_icons_used_gradient_bg' => ''
				]
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'eael_team_members_social_icon_gradient_background',
				'label' => __( 'Background', 'essential-addons-for-elementor-lite' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eael-team-member-social-link > a',
				'condition' => [
					'eael_team_members_social_icons_used_gradient_bg' => 'yes'
				]
			]
		);


		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_team_members_social_icon_border',
				'selector' => '{{WRAPPER}} .eael-team-member-social-link > a',
			]
		);

		$this->add_control(
			'eael_team_members_social_icon_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-team-member-social-link > a' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_team_members_social_icon_typography',
				'selector' => '{{WRAPPER}} .eael-team-member-social-link > a',
			]
		);


		$this->end_controls_tab();

		$this->start_controls_tab( 'eael_team_members_social_icon_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite') ] );

		$this->add_control(
			'eael_team_members_social_icon_hover_color',
			[
				'label' => esc_html__( 'Icon Hover Color', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::COLOR,
				'default' => '#ad8647',
				'selectors' => [
					'{{WRAPPER}} .eael-team-member-social-link > a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_team_members_social_icon_hover_background',
			[
				'label' => esc_html__( 'Hover Background', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-team-member-social-link > a:hover' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'eael_team_members_social_icons_used_gradient_bg' => ''
				]
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'eael_team_members_social_icon_hover_gradient_background',
				'label' => __( 'Background', 'essential-addons-for-elementor-lite' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eael-team-member-social-link > a:hover',
				'condition' => [
					'eael_team_members_social_icons_used_gradient_bg' => 'yes'
				]
			]
		);

		$this->add_control(
			'eael_team_members_social_icon_hover_border_color',
			[
				'label' => esc_html__( 'Hover Border Color', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-team-member-social-link > a:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();


		$this->end_controls_section();


	}


	protected function render( ) {

        $settings = $this->get_settings_for_display();
		$team_member_image = $this->get_settings( 'eael_team_member_image' );
		$team_member_image_url = Group_Control_Image_Size::get_attachment_image_src( $team_member_image['id'], 'thumbnail', $settings );
		if( empty( $team_member_image_url ) ) : $team_member_image_url = $team_member_image['url']; else: $team_member_image_url = $team_member_image_url; endif;
		$team_member_classes = $this->get_settings('eael_team_members_preset') . " " . $this->get_settings('eael_team_members_image_rounded');

	?>


	<div id="eael-team-member-<?php echo esc_attr($this->get_id()); ?>" class="eael-team-item <?php echo $team_member_classes; ?>">
		<div class="eael-team-item-inner">
			<div class="eael-team-image">
				<figure>
					<img src="<?php echo esc_url($team_member_image_url);?>" alt="<?php echo esc_attr( get_post_meta($team_member_image['id'], '_wp_attachment_image_alt', true) ); ?>">
				</figure>
				<?php if( 'eael-team-members-social-right' === $settings['eael_team_members_preset'] ) : ?>
					<?php do_action('eael/team_member_social_right_markup', $settings); ?>
				<?php endif; ?>
			</div>

			<div class="eael-team-content">
				<h3 class="eael-team-member-name"><?php echo HelperClass::eael_wp_kses($settings['eael_team_member_name']); ?></h3>
				<h4 class="eael-team-member-position"><?php echo HelperClass::eael_wp_kses($settings['eael_team_member_job_title']); ?></h4>

				<?php if( 'eael-team-members-social-bottom' === $settings['eael_team_members_preset'] ) : ?>
					<?php do_action('eael/team_member_social_botton_markup', $settings); ?>
				<?php else: ?>
					<?php if ( ! empty( $settings['eael_team_member_enable_social_profiles'] ) && 'eael-team-members-social-right' !== $settings['eael_team_members_preset'] ): ?>
						<ul class="eael-team-member-social-profiles">
							<?php foreach ( $settings['eael_team_member_social_profile_links'] as $item ) : ?>
								<?php $icon_migrated = isset($item['__fa4_migrated']['social_new']);
								$icon_is_new = empty($item['social']); ?>
								<?php if ( ! empty( $item['social'] ) || !empty($item['social_new'])) : ?>
									<?php $target = $item['link']['is_external'] ? ' target="_blank"' : ''; ?>
									<li class="eael-team-member-social-link">
										<a href="<?php echo esc_attr( $item['link']['url'] ); ?>" <?php echo $target; ?>>
											<?php if ($icon_is_new || $icon_migrated) { ?>
												<?php if( isset( $item['social_new']['value']['url'] ) ) : ?>
													<img src="<?php echo esc_attr($item['social_new']['value']['url'] ); ?>" alt="<?php echo esc_attr(get_post_meta($item['social_new']['value']['id'], '_wp_attachment_image_alt', true)); ?>" />
												<?php else : ?>
													<i class="<?php echo esc_attr($item['social_new']['value'] ); ?>"></i>
												<?php endif; ?>
											<?php } else { ?>
												<i class="<?php echo esc_attr($item['social'] ); ?>"></i>
											<?php } ?>
										</a>
									</li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<p class="eael-team-text"><?php echo HelperClass::eael_wp_kses($settings['eael_team_member_description']); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
	}
}
