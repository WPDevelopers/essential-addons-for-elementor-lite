<?php
namespace Essential_Addons_Elementor\Elements;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use ElementsKit_Lite\Modules\Widget_Builder\Controls\Control_Type_Border;

// If this file is called directly, abort.
if (!defined( 'ABSPATH' ) ) {
   exit;
}
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Code_Snippet extends Widget_Base {
   public function get_name() {
		return 'eael-code-snippet';
	}
   
   public function get_title() {
		return esc_html__( 'Code Snippet', 'essential-addons-for-elementor-lite' );
	}

   public function get_icon() {
		return 'eaicon-code-snippet';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

   public function get_keywords() {
		return [ 
         'code', 
         'snippet', 
         'code snippet', 
         'code block', 
         'programming', 
         'ea code', 
         'ea', 
         'essential addons' 
      ];
	}

   public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/ea-code-snippet/';
	}

   protected function register_controls() {
      $this->start_controls_section(
			'code_snippet_section',
			[
				'label' => esc_html__( 'Code Settings', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

      $this->add_control(
            'language',
            [
               'label'   => __( 'Programming Language', 'essential-addons-for-elementor-lite' ),
               'type'    => Controls_Manager::SELECT2,
               'default' => 'html',
               'options' => [
                  'html'       => __( 'HTML', 'essential-addons-for-elementor-lite' ),
                  'css'        => __( 'CSS', 'essential-addons-for-elementor-lite' ),
                  'scss'       => __( 'SCSS', 'essential-addons-for-elementor-lite' ),
                  'php'        => __( 'PHP', 'essential-addons-for-elementor-lite' ),
                  'py'         => __( 'Python', 'essential-addons-for-elementor-lite' ),
                  'js'         => __( 'JavaScript', 'essential-addons-for-elementor-lite' ),
                  'jsx'        => __( 'React JS', 'essential-addons-for-elementor-lite' ),
                  'vue'        => __( 'Vue JS', 'essential-addons-for-elementor-lite' ),
                  'ts'         => __( 'TypeScript', 'essential-addons-for-elementor-lite' ),
                  'sql'        => __( 'SQL', 'essential-addons-for-elementor-lite' ),
                  'json'       => __( 'JSON', 'essential-addons-for-elementor-lite' ),
                  'xml'        => __( 'XML', 'essential-addons-for-elementor-lite' ),
                  'java'       => __( 'Java', 'essential-addons-for-elementor-lite' ),
                  'rd'         => __( 'Ruby', 'essential-addons-for-elementor-lite' ),
                  'bash'       => __( 'Bash', 'essential-addons-for-elementor-lite' ),
                  'yml'        => __( 'YAML', 'essential-addons-for-elementor-lite' ),
                  'cpp'        => __( 'C++', 'essential-addons-for-elementor-lite' ),
                  'cs'         => __( 'C#', 'essential-addons-for-elementor-lite' ),
                  'go'         => __( 'Go', 'essential-addons-for-elementor-lite' ),
                  'rs'         => __( 'Rust', 'essential-addons-for-elementor-lite' ),
                  'swift'      => __( 'Swift', 'essential-addons-for-elementor-lite' ),
                  'kt'         => __( 'Kotlin', 'essential-addons-for-elementor-lite' ),
                  'md'         => __( 'Markdown', 'essential-addons-for-elementor-lite' ),
                  'sh'         => __( 'Shell', 'essential-addons-for-elementor-lite' ),
                  'ps1'        => __( 'Powershell', 'essential-addons-for-elementor-lite' ),
                  'dockerfile' => __( 'Docker', 'essential-addons-for-elementor-lite' ),
               ],
               'description' => __( 'Choose language for syntax highlighting.', 'essential-addons-for-elementor-lite' ),
         ]
      );

      $this->add_control(
         'code_content',
         [
               'label'       => __( 'Code Snippet', 'essential-addons-for-elementor-lite' ),
               'type'        => Controls_Manager::CODE,
               'language'    => 'html',
               'rows'        => 15,
               'default'     => "// Paste or type your code here…",
               'placeholder' => __( 'Enter your code snippet here...', 'essential-addons-for-elementor-lite' ),
               'description' => __( 'Type or paste your code here. Use Tab to indent; syntax highlighting will appear in the live preview.', 'essential-addons-for-elementor-lite' ),
               'ai'          => [
                  'active' => false,
               ]
         ]
      );

      $this->end_controls_section();
      
      $this->start_controls_section(
			'appearance_section',
			[
				'label' => esc_html__( 'Appearance', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

      $this->add_control(
         'theme',
         [
               'label'   => __( 'Theme', 'essential-addons-for-elementor-lite' ),
               'type'    => Controls_Manager::CHOOSE,
               'default' => 'light',
               'toggle' => false,
               'options' => [
                  'light' => [
                     'title' => __( 'Light', 'essential-addons-for-elementor-lite' ),
                     'icon' => 'eicon-light-mode',
                  ],
                  'dark'  => [
                     'title' => __( 'Dark', 'essential-addons-for-elementor-lite' ),
                     'icon' => 'eicon-dark-mode',
                  ],
               ],
               'description' => __( 'Choose light or dark styling for the code snippet block.', 'essential-addons-for-elementor-lite' ),
         ]
      );

      $this->add_control(
            'show_header',
         [
               'label'        => __( 'Header bar', 'essential-addons-for-elementor-lite' ),
               'type'         => Controls_Manager::SWITCHER,
               'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
               'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
               'return_value' => 'yes',
               'default'      => 'yes',
               'description'  => __( 'Toggle the filename header and copy button.', 'essential-addons-for-elementor-lite' ),
         ]
      );

      $this->add_control(
         'show_copy_button',
         [
               'label'        => __( 'Copy button', 'essential-addons-for-elementor-lite' ),
               'type'         => Controls_Manager::SWITCHER,
               'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
               'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
               'return_value' => 'yes',
               'default'      => 'yes',
               'description'  => __( 'Show a one-click copy-to-clipboard icon.', 'essential-addons-for-elementor-lite' ),
               'condition'    => [
                  'show_header' => 'yes',
               ],
         ]
      );

      $this->add_control(
         'show_copy_tooltip',
         [
               'label'        => __( 'Copy button tooltip', 'essential-addons-for-elementor-lite' ),
               'type'         => Controls_Manager::SWITCHER,
               'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
               'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
               'return_value' => 'yes',
               'default'      => 'no',
               'description'  => __( 'Display a tooltip (‘Copied!’) on copy button hover or click.', 'essential-addons-for-elementor-lite' ),
               'condition'    => [
                  'show_header' => 'yes',
                  'show_copy_button' => 'yes',
               ],
         ]
      );

      $this->add_control(
         'show_line_numbers',
         [
               'label'        => __( 'Line numbers', 'essential-addons-for-elementor-lite' ),
               'type'         => Controls_Manager::SWITCHER,
               'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
               'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
               'return_value' => 'yes',
               'default'      => 'no',
               'description'  => __( 'Display line numbers in the code block.', 'essential-addons-for-elementor-lite' ),
         ]
      );

      $this->add_control(
         'code_view_mode',
         [
            'label' => esc_html__( 'View Mode', 'essential-addons-for-elementor-lite' ),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
               'default' => [
                  'title' => esc_html__( 'Default', 'essential-addons-for-elementor-lite' ),
                  'icon' => 'eicon-menu-toggle',
               ],
               'fixed' => [
                  'title' => esc_html__( 'Fixed', 'essential-addons-for-elementor-lite' ),
                  'icon' => 'eicon-spacer',
               ],
               'collapsed' => [
                  'title' => esc_html__( 'Collapsed', 'essential-addons-for-elementor-lite' ),
                  'icon' => 'eicon-v-align-stretch',
               ],
            ],
            'default' => 'default',
            'toggle' => false,
         ]
      );

      $this->add_responsive_control(
         'code_snippet_height',
         [
            'label'      => esc_html__( 'Height', 'essential-addons-for-elementor-lite' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem' ],
            'range'      => [
               'px' => [
                  'min'  => 0,
                  'max'  => 1000,
                  'step' => 5,
               ],
               'em' => [
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 0.5,
               ],
               'rem' => [
                  'min'  => 0,
                  'max'  => 100,
                  'step' => 0.5,
               ],
            ],
            'default' => [
               'unit' => 'px',
               'size' => 300,
            ],
            'selectors' => [
               '{{WRAPPER}} .eael-code-snippet-content' => 'height: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
               'code_view_mode!' => 'default',
            ],
         ]
      );

      $this->add_control(
         'code_collapse_inidicator_content',
         [
            'label' => esc_html__( 'Indicator', 'essential-addons-for-elementor-lite' ),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
               'code_view_mode' => 'collapsed',
            ],
         ]
      );

      $this->add_control(
         'code_collapse_inidicator_type',
         [
            'label' => esc_html__( 'Type', 'essential-addons-for-elementor-lite' ),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
               'full_width' => [
                  'title' => esc_html__( 'Full Width', 'essential-addons-for-elementor-lite' ),
                  'icon' => 'eicon-text-align-justify',
               ],
               'button' => [
                  'title' => esc_html__( 'Button', 'essential-addons-for-elementor-lite' ),
                  'icon' => 'eicon-button',
               ],
            ],
            'default' => 'full_width',
            'toggle' => false,
            'condition' => [
               'code_view_mode' => 'collapsed',
            ],
         ]
      );

      $this->add_control(
         'code_collapse_inidicator_position',
         [
            'label' => esc_html__( 'Position', 'essential-addons-for-elementor-lite' ),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
               'left' => [
                  'title' => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
                  'icon' => 'eicon-text-align-left',
               ],
               'center' => [
                  'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
                  'icon' => 'eicon-text-align-center',
               ],
               'right' => [
                  'title' => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
                  'icon' => 'eicon-text-align-right',
               ],
            ],
            'default' => '',
            'toggle' => true,
            'selectors' => [
               '{{WRAPPER}} .eael-code-snippet-collapsed-indicator-wrapper' => 'justify-content: {{VALUE}};',
               '{{WRAPPER}} .eael-code-snippet-collapsed-indicator-wrapper .eael-cs-indicator-type-full_width' => 'justify-content: {{VALUE}};',
            ],
            'condition' => [
               'code_view_mode' => 'collapsed',
            ],
         ]
      );

      $this->add_control(
         'code_collapse_inidicator_content_type',
         [
            'label' => esc_html__( 'Content Type', 'essential-addons-for-elementor-lite' ),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
               'text' => [
                  'title' => esc_html__( 'Text', 'essential-addons-for-elementor-lite' ),
                  'icon' => 'eicon-animation-text',
               ],
               'icon' => [
                  'title' => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
                  'icon' => 'eicon-favorite',
               ],
            ],
            'default' => 'text',
            'toggle' => false,
            'condition' => [
               'code_view_mode' => 'collapsed',
            ],
         ]
      );

      $this->add_control(
         'code_collapse_inidicator_text_collapsed',
         [
            'label' => esc_html__( 'Text (Collapsed)', 'essential-addons-for-elementor-lite' ),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__( 'Show more', 'essential-addons-for-elementor-lite' ),
            'ai' => [
               'active' => false,
            ],
            'condition' => [
               'code_view_mode' => 'collapsed',
               'code_collapse_inidicator_content_type' => 'text',
            ],
         ]
      );
      $this->add_control(
         'code_collapse_inidicator_text_expanded',
         [
            'label' => esc_html__( 'Text (Expanded)', 'essential-addons-for-elementor-lite' ),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__( 'Show less', 'essential-addons-for-elementor-lite' ),
            'ai' => [
               'active' => false,
            ],
            'condition' => [
               'code_view_mode' => 'collapsed',
               'code_collapse_inidicator_content_type' => 'text',
            ],
         ]
      );

      $this->add_control(
         'code_collapse_inidicator_icon_collapsed',
         [
            'label' => esc_html__( 'Icon (Collapsed)', 'essential-addons-for-elementor-lite' ),
            'type' => Controls_Manager::ICONS,
            'default' => [
               'value' => 'fas fa-angle-down',
               'library' => 'fa-solid',
            ],
            'condition' => [
               'code_view_mode' => 'collapsed',
               'code_collapse_inidicator_content_type' => 'icon',
            ],
         ]
      );

      $this->add_control(
         'code_collapse_inidicator_icon_expanded',
         [
            'label' => esc_html__( 'Icon (Expanded)', 'essential-addons-for-elementor-lite' ),
            'type' => Controls_Manager::ICONS,
            'default' => [
               'value' => 'fas fa-angle-up',
               'library' => 'fa-solid',
            ],
            'condition' => [
               'code_view_mode' => 'collapsed',
               'code_collapse_inidicator_content_type' => 'icon',
            ],
         ]
      );
      
      $this->end_controls_section();

      $this->start_controls_section(
         'file_preview_section',
         [
               'label' => __( 'Header', 'essential-addons-for-elementor-lite' ),
               'tab'   => Controls_Manager::TAB_CONTENT,
               'condition' => [
                  'show_header' => 'yes',
               ],
         ]
      );

      $this->add_control(
         'file_name',
         [
               'label'       => __( 'File Name', 'essential-addons-for-elementor-lite' ),
               'type'        => Controls_Manager::TEXT,
               'default'     => 'filename',
               'placeholder' => __( 'Enter filename with extension', 'essential-addons-for-elementor-lite' ),
               'description' => __( 'Enter the filename to display in the header (e.g., hero-section.tsx)', 'essential-addons-for-elementor-lite' ),
               'ai'          => [ 'active' => false ],
               'dynamic'     => [ 'active' => true ],
         ]
      );

      $this->add_control(
         'show_traffic_lights',
         [
               'label' => __( 'Window buttons', 'essential-addons-for-elementor-lite' ),
               'type' => Controls_Manager::SWITCHER,
               'label_on' => __( 'Show', 'essential-addons-for-elementor-lite' ),
               'label_off' => __( 'Hide', 'essential-addons-for-elementor-lite' ),
               'return_value' => 'yes',
               'default' => 'yes',
               'description' => __( 'Display macOS-style close/minimize/maximize circles.', 'essential-addons-for-elementor-lite' ),
         ]
      );

      $this->add_control(
         'show_file_icon',
         [
               'label' => __( 'Language icon', 'essential-addons-for-elementor-lite' ),
               'type' => Controls_Manager::SWITCHER,
               'label_on' => __( 'Show', 'essential-addons-for-elementor-lite' ),
               'label_off' => __( 'Hide', 'essential-addons-for-elementor-lite' ),
               'return_value' => 'yes',
               'default' => 'yes',
               'description' => __( 'Display the default icon for this file type.', 'essential-addons-for-elementor-lite' ),
         ]
      );

      $this->add_control(
         'file_icon_type',
         [
            'label' => esc_html__( 'Icon Type', 'essential-addons-for-elementor-lite' ),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
               'image' => [
                  'title' => esc_html__( 'Image', 'essential-addons-for-elementor-lite' ),
                  'icon' => 'eicon-image',
               ],
               'icon' => [
                  'title' => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
                  'icon' => 'eicon-favorite',
               ],
            ],
            'default' => 'image',
            'toggle' => true,
            'condition' => [
               'show_file_icon' => 'yes',
            ],
         ]
      );

      $this->add_control(
         'file_icon',
         [
               'label'       => '',
               'type'        => Controls_Manager::MEDIA,
               // 'media_types' => [ 'image' ],
               'description' => __( 'Upload a custom icon to override the default.', 'essential-addons-for-elementor-lite' ),
               'condition'   => [
                  'show_file_icon' => 'yes',
                  'file_icon_type' => 'image',
               ],
               'ai' => [
					'active' => false,
				],
         ]
      );

      $this->add_control(
         'file_icon_custom',
         [
               'label'       => '',
               'type'        => Controls_Manager::ICONS,
               'fa4compatibility' => 'icon',
               'condition'   => [
                  'show_file_icon' => 'yes',
                  'file_icon_type' => 'icon',
               ],
         ]
      );

      $this->end_controls_section();

      // Style Tab - Wrapper
      $this->start_controls_section(
			'wrapper_style_section',
			[
				'label' => esc_html__( 'Wrapper', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

      $this->add_responsive_control(
            'wrapper_margin',
            [
               'label'      => __( 'Margin', 'essential-addons-for-elementor-lite' ),
               'type'       => Controls_Manager::DIMENSIONS,
               'size_units' => [ 'px', 'em', '%' ],
               'selectors'  => [
                  '{{WRAPPER}} .eael-code-snippet-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
               ],
         ]
      );

      $this->add_responsive_control(
            'wrapper_padding',
            [
               'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
               'type'       => Controls_Manager::DIMENSIONS,
               'size_units' => [ 'px', 'em', '%' ],
               'selectors'  => [
                  '{{WRAPPER}} .eael-code-snippet-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
               ],
         ]
      );

      $this->add_control(
            'wrapper_background_color',
            [
               'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
               'type'      => Controls_Manager::COLOR,
               'selectors' => [
                  '{{WRAPPER}} .eael-code-snippet-wrapper' => 'background-color: {{VALUE}};',
               ],
         ]
      );

      $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'wrapper_border',
				'selector' => '{{WRAPPER}} .eael-code-snippet-wrapper',
			]
		);

      $this->add_control(
			'wrapper_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-code-snippet-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

      $this->end_controls_section();

      // Style Tab - Header
      $this->start_controls_section(
         'header_style_section',
         [
               'label'     => __( 'Header', 'essential-addons-for-elementor-lite' ),
               'tab'       => Controls_Manager::TAB_STYLE,
               'condition' => [
                  'show_header' => 'yes',
               ],
         ]
      );

      $this->add_responsive_control(
         'header_padding',
         [
               'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
               'type'       => Controls_Manager::DIMENSIONS,
               'size_units' => [ 'px', 'em', '%' ],
               'selectors'  => [
                  '{{WRAPPER}} .eael-code-snippet-header.eael-file-preview-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
               ],
         ]
      );

      $this->add_control(
         'header_background_color',
         [
               'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
               'type'      => Controls_Manager::COLOR,
               'selectors' => [
                  '{{WRAPPER}} .eael-code-snippet-header.eael-file-preview-header' => 'background-color: {{VALUE}};',
               ],
         ]
      );

      $this->add_control(
         'header_border_color',
         [
               'label'     => __( 'Border Color', 'essential-addons-for-elementor-lite' ),
               'type'      => Controls_Manager::COLOR,
               'selectors' => [
                  '{{WRAPPER}} .eael-code-snippet-header.eael-file-preview-header' => 'border-bottom-color: {{VALUE}};',
               ],
         ]
      );

      $this->add_responsive_control(
         'header_border_width',
         [
               'label'      => __( 'Border Width', 'essential-addons-for-elementor-lite' ),
               'type'       => Controls_Manager::SLIDER,
               'size_units' => [ 'px' ],
               'range'      => [
                  'px' => [
                     'min' => 0,
                     'max' => 10,
                  ],
               ],
               'selectors'  => [
                  '{{WRAPPER}} .eael-code-snippet-header.eael-file-preview-header' => 'border-bottom-width: {{SIZE}}{{UNIT}}; border-bottom-style: solid;',
               ],
               'description' => __( 'Controls border bottom width', 'essential-addons-for-elementor-lite' ),
         ]
      );

      $this->add_control(
         'file_custom_icon_style_heading',
         [
            'label' => esc_html__( 'File Icon', 'essential-addons-for-elementor-lite' ),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
               'show_file_icon' => 'yes',
               'file_icon_type' => 'icon',
            ],
         ]
      );

      $this->add_control(
         'file_custom_icon_color',
         [
               'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
               'type'      => Controls_Manager::COLOR,
               'selectors' => [
                  '{{WRAPPER}} .eael-file-icon i' => 'color: {{VALUE}};',
                  '{{WRAPPER}} .eael-file-icon svg' => 'fill: {{VALUE}};',
               ],
               'condition' => [
                  'show_file_icon' => 'yes',
                  'file_icon_type' => 'icon',
               ],
         ]
      );

      $this->add_control(
         'file_name_style_heading',
         [
            'label' => esc_html__( 'File Name', 'essential-addons-for-elementor-lite' ),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
         ]
      );
      $this->add_group_control(
         Group_Control_Typography::get_type(),
         [
               'name'     => 'file_name_typography',
               'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
               'selector' => '{{WRAPPER}} .eael-code-snippet-header .file-name-text',
         ]
      );

      $this->add_control(
         'file_name_color',
         [
               'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
               'type'      => Controls_Manager::COLOR,
         'selectors' => [
                  '{{WRAPPER}} .eael-code-snippet-header .file-name-text' => 'color: {{VALUE}};',
               ],
         ]
      );

      $this->add_control(
         'copy_button_style_heading',
         [
            'label' => esc_html__( 'Copy Button', 'essential-addons-for-elementor-lite' ),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
               'show_copy_button' => 'yes',
            ],
         ]
      );

      $this->add_control(
         'copy_button_color',
         [
               'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
               'type'      => Controls_Manager::COLOR,
               'selectors' => [
                  '{{WRAPPER}} .eael-code-snippet-copy-button' => 'color: {{VALUE}};',
               ],
               'condition' => [
                  'show_copy_button' => 'yes',
               ],
         ]
      );

      $this->add_group_control(
         Group_Control_Border::get_type(),
         [
            'name' => 'copy_button_border',
            'selector' => '{{WRAPPER}} .eael-code-snippet-copy-button',
         ]
      );

      $this->add_responsive_control(
         'copy_button_border_radius',
         [
            'label' => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'selectors' => [
               '{{WRAPPER}} .eael-code-snippet-copy-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
         ]
      );

      $this->end_controls_section();

      // Style Tab - Line Numbers
      $this->start_controls_section(
         'line_numbers_style_section',
         [
               'label'     => __( 'Line Numbers', 'essential-addons-for-elementor-lite' ),
               'tab'       => Controls_Manager::TAB_STYLE,
               'condition' => [
                  'show_line_numbers' => 'yes',
               ],
         ]
      );

      $this->add_responsive_control(
         'line_numbers_padding',
         [
               'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
               'type'       => Controls_Manager::DIMENSIONS,
               'size_units' => [ 'px', 'em', '%' ],
               'selectors'  => [
                  '{{WRAPPER}} .eael-code-snippet-line-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
               ],
         ]
      );

      $this->add_control(
         'line_numbers_color',
         [
               'label'     => __( 'Line Number Color', 'essential-addons-for-elementor-lite' ),
               'type'      => Controls_Manager::COLOR,
               'selectors' => [
                  '{{WRAPPER}} .eael-code-snippet-line-numbers .line-number' => 'color: {{VALUE}};',
               ],
         ]
      );

      $this->add_control(
         'line_numbers_background_color',
         [
               'label'     => __( 'Line Number Background', 'essential-addons-for-elementor-lite' ),
               'type'      => Controls_Manager::COLOR,
               'selectors' => [
                  '{{WRAPPER}} .eael-code-snippet-line-numbers' => 'background-color: {{VALUE}};',
               ],
         ]
      );

      $this->add_control(
         'line_numbers_border_color',
         [
               'label'     => __( 'Border Color', 'essential-addons-for-elementor-lite' ),
               'type'      => Controls_Manager::COLOR,
               'selectors' => [
                  '{{WRAPPER}} .eael-code-snippet-line-numbers' => 'border-right-color: {{VALUE}};',
               ],
         ]
      );

      $this->add_responsive_control(
         'line_numbers_border_width',
         [
               'label'      => __( 'Border Width', 'essential-addons-for-elementor-lite' ),
               'type'       => Controls_Manager::SLIDER,
               'size_units' => [ 'px' ],
               'range'      => [
                  'px' => [
                     'min' => 0,
                     'max' => 20,
                  ],
               ],
               'selectors'  => [
                  '{{WRAPPER}} .eael-code-snippet-line-numbers' => 'border-right-width: {{SIZE}}{{UNIT}}; border-right-style: solid;',
               ],
               'description' => __( 'Controls border right width', 'essential-addons-for-elementor-lite' ),
         ]
      );

      $this->end_controls_section();

      // Style Tab - Code Content Area
      $this->start_controls_section(
         'code_content_style_section',
         [
               'label' => __( 'Code Content Area', 'essential-addons-for-elementor-lite' ),
               'tab'   => Controls_Manager::TAB_STYLE,
         ]
      );

      $this->add_responsive_control(
         'code_content_padding',
         [
               'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
               'type'       => Controls_Manager::DIMENSIONS,
               'size_units' => [ 'px', 'em', '%' ],
               'selectors'  => [
                  '{{WRAPPER}} .eael-code-snippet-code' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
               ],
         ]
      );

      $this->add_control(
         'code_content_background_color',
         [
               'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
               'type'      => Controls_Manager::COLOR,
               'selectors' => [
                  '{{WRAPPER}} .eael-code-snippet-code' => 'background-color: {{VALUE}};',
               ],
         ]
      );

      $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .eael-code-snippet-line-numbers, {{WRAPPER}} .eael-code-snippet-code, {{WRAPPER}} .eael-code-snippet-code code',
			]
		);
      $this->end_controls_section();

      // Style Tab - Code Content
      $this->start_controls_section(
         'code_content_collapse_style_section',
         [
               'label' => __( 'Collapse Indicator', 'essential-addons-for-elementor-lite' ),
               'tab'   => Controls_Manager::TAB_STYLE,
               'condition' => [
                  'code_view_mode' => 'collapsed',
               ],
         ]
      );

      $this->add_group_control(
         Group_Control_Typography::get_type(),
         [
            'name' => 'code_content_collapsed_indicator_typography',
            'selector' => '{{WRAPPER}} .eael-code-snippet-collapsed-indicator-wrapper .eael-code-snippet-collapsed-indicator .eael-code-snippet-collapsed-indicator-text',
            'condition' => [
               'code_collapse_inidicator_content_type' => 'text'
            ]
         ]
      );

      $this->add_responsive_control(
         'code_content_collapsed_indicator_icon_size',
         [
            'label' => esc_html__( 'Icon Size', 'essential-addons-for-elementor-lite' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem' ],
            'range' => [
               'px' => [
                  'min' => 0,
                  'max' => 1000,
                  'step' => 5,
               ],
            ],
            'selectors' => [
               '{{WRAPPER}} .eael-code-snippet-collapsed-indicator-wrapper .eael-code-snippet-collapsed-indicator i' => 'font-size: {{SIZE}}{{UNIT}};',
               '{{WRAPPER}} .eael-code-snippet-collapsed-indicator-wrapper .eael-code-snippet-collapsed-indicator svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
               'code_collapse_inidicator_content_type' => 'icon'
            ]
         ]
      );

      $this->add_control(
         'code_content_collapsed_indicator_color',
         [
               'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
               'type'      => Controls_Manager::COLOR,
               'selectors' => [
                  '{{WRAPPER}} .eael-code-snippet-collapsed-indicator-wrapper .eael-code-snippet-collapsed-indicator i' => 'color: {{VALUE}};',
                  '{{WRAPPER}} .eael-code-snippet-collapsed-indicator-wrapper .eael-code-snippet-collapsed-indicator svg' => 'fill: {{VALUE}};',
                  '{{WRAPPER}} .eael-code-snippet-collapsed-indicator-wrapper .eael-code-snippet-collapsed-indicator .eael-code-snippet-collapsed-indicator-text' => 'color: {{VALUE}};',
               ],
         ]
      );

      $this->add_group_control(
         Group_Control_Background::get_type(),
         [
            'name' => 'code_content_collapsed_indicator_background',
            'types' => [ 'classic', 'gradient' ],
            'exclude' => [ 'image' ],
            'selector' => '{{WRAPPER}} .eael-code-snippet-collapsed-indicator-wrapper .eael-code-snippet-collapsed-indicator',
         ]
      );

      $this->add_group_control(
         Group_Control_Border::get_type(),
         [
            'name' => 'code_content_collapsed_indicator_border',
            'selector' => '{{WRAPPER}} .eael-code-snippet-collapsed-indicator-wrapper .eael-code-snippet-collapsed-indicator',
         ]
      );

      $this->add_responsive_control(
         'code_content_collapsed_indicator_border_radius',
         [
               'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
               'type'       => Controls_Manager::DIMENSIONS,
               'size_units' => [ 'px', '%' ],
               'selectors'  => [
                  '{{WRAPPER}} .eael-code-snippet-collapsed-indicator-wrapper .eael-code-snippet-collapsed-indicator' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
               ],
         ]
      );

      $this->add_responsive_control(
         'code_content_collapsed_indicator_height',
         [
            'label' => esc_html__( 'Height', 'essential-addons-for-elementor-lite' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%', 'em', 'rem' ],
            'range' => [
               'px' => [
                  'min' => 0,
                  'max' => 100,
                  'step' => 5,
               ],
               '%' => [
                  'min' => 0,
                  'max' => 100,
               ],
            ],
            'default' => [
               'unit' => 'px',
               'size' => 30,
            ],
            'selectors' => [
               '{{WRAPPER}} .eael-code-snippet-collapsed-indicator-wrapper' => 'height: {{SIZE}}{{UNIT}};',
               '{{WRAPPER}} .eael-code-snippet-collapsed-indicator-wrapper .eael-code-snippet-collapsed-indicator' => 'height: {{SIZE}}{{UNIT}};',
            ],
         ]
      );

      $this->add_responsive_control(
         'code_content_collapsed_indicator_width',
         [
            'label' => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem', 'custom' ],
            'range' => [
               'px' => [
                  'min' => 0,
                  'max' => 100,
                  'step' => 5,
               ],
               '%' => [
                  'min' => 0,
                  'max' => 100,
               ],
            ],
            'default' => [
               'unit' => 'px',
               'size' => 30,
            ],
            'selectors' => [
               '{{WRAPPER}} .eael-code-snippet-collapsed-indicator-wrapper .eael-code-snippet-collapsed-indicator.eael-cs-indicator-type-button' => 'width: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
               'code_view_mode' => 'collapsed',
               'code_collapse_inidicator_type' => 'button',
            ],
         ]
      );

      $this->add_responsive_control(
         'code_content_collapsed_indicator_padding',
         [
            'label' => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', 'rem' ],
            'default' => [
               'top'      => 20,
               'right'    => 20,
               'bottom'   => 20,
               'left'     => 20,
               'unit'     => 'px',
               'isLinked' => true,
            ],
            'selectors' => [
               '{{WRAPPER}} .eael-code-snippet-collapsed-indicator-wrapper .eael-code-snippet-collapsed-indicator.eael-cs-indicator-type-full_width' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
               'code_view_mode' => 'collapsed',
               'code_collapse_inidicator_type' => 'full_width',
            ],
         ]
      );

      $this->end_controls_section();
   }

   /**
     * Get default file icon based on programming language
     * @return string Emoji icon for the language
     */
   public static function get_file_icon_by_language( $language ) {
      $icons = [
         'html'       => '🌐',
         'css'        => '🎨',
         'scss'       => '🎨',
         'php'        => '🐘',
         'py'         => '🐍',
         'js'         => '📄',
         'jsx'        => '⚛️',
         'vue'        => '🟩',
         'ts'         => '📘',
         'sql'        => '🗃️',
         'json'       => '📋',
         'xml'        => '📄',
         'java'       => '☕',
         'rd'         => '💎',
         'bash'       => '💻',
         'yml'        => '📋',
         'cpp'        => '➕➕',
         'cs'         => '🔷',
         'go'         => '🐹',
         'rs'         => '🦀',
         'swift'      => '🦉',
         'kt'         => '🎯',
         'md'         => '📝',
         'sh'         => '💻',
         'ps1'        => '📟',
         'dockerfile' => '🐳',
      ];

      return $icons[$language] ?? '📄';
   }

   protected function render() {
      $settings = $this->get_settings_for_display();

      // Generate unique snippet ID for this widget instance
      $snippet_id = 'eael-code-snippet-' . $this->get_id();

      // Generate line numbers if needed
      $line_numbers = [];
      if ( $settings['show_line_numbers'] ) {
         $lines        = explode( "\n", $settings['code_content'] );
         $line_numbers = range( 1, count( $lines ) );
      }

      $file_icon      = !empty($settings['file_icon']['url']) || !empty($settings['file_icon_custom']['value']) ?? '';
      $show_file_icon = $settings['show_file_icon'] ?? 'yes';
      $language       = $settings['language'] ?? 'html';
      $file_name      = $settings['file_name'] ?? '';
      $theme          = $settings['theme'] ?? 'light';
      $show_traffic_lights = $settings['show_traffic_lights'] ?? 'yes';
      $show_line_numbers = $settings['show_line_numbers'] ?? 'no';
      $show_header    = $settings['show_header'] ?? 'yes';
      $show_copy_button = $settings['show_copy_button'] ?? 'yes';
      $show_copy_tooltip = $settings['show_copy_tooltip'] ?? 'no';

      $this->add_render_attribute( 'eael-snippet-wrapper', 'class', 'eael-code-snippet-wrapper theme-' . $theme );
      $this->add_render_attribute( 'eael-snippet-wrapper', 'data-language', $language );
      $this->add_render_attribute( 'eael-snippet-wrapper', 'data-copy-button',  'yes' === $show_copy_button );
      $this->add_render_attribute( 'eael-snippet-wrapper', 'data-snippet-id', $snippet_id );
      $this->add_render_attribute( 'eael-snippet-wrapper', 'id', $snippet_id );
      $this->add_render_attribute( 'eael-snippet-wrapper', 'class',  'view-mode-' . $settings['code_view_mode'] );

      ?>
      <div <?php $this->print_render_attribute_string( 'eael-snippet-wrapper' ); ?> >
      <?php if ( 'yes' === $show_header ) { ?>
         <div class="eael-code-snippet-header eael-file-preview-header">
            <div class="eael-file-preview-left">
            <?php if ( 'yes' === $show_traffic_lights ) { ?>
               <div class="eael-traffic-lights">
                  <span class="traffic-light traffic-light-red"></span>
                  <span class="traffic-light traffic-light-yellow"></span>
                  <span class="traffic-light traffic-light-green"></span>
               </div>
            <?php } ?>
               <div class="eael-file-info">
                  <?php if ( 'yes' === $show_file_icon ) { ?>
                  <div class="eael-file-icon">
                     <?php if ( ! empty( $file_icon ) ) { 
                        if ( $settings['file_icon_type'] === 'icon' ) {
                           Icons_Manager::render_icon( $settings['file_icon_custom'], [ 'aria-hidden' => 'true' ] );
                        } else {
                           echo wp_get_attachment_image( $settings['file_icon']['id'], 'thumbnail', true, [ 'alt' => esc_attr__( 'File icon', 'essential-addons-for-elementor-lite' ) ] );
                        }
                        } else {
                        ?>
                        <span class="eael-file-icon-emoji"><?php echo esc_html( self::get_file_icon_by_language( $language ) ); ?></span>
                        <?php
                     } ?>
                  </div>
                  <?php }
                  if ( ! empty( $file_name ) ) { ?>
                  <div class="eael-file-name">
                     <span class="file-name-text">
                        <?php 
                        if ( strpos( $file_name, '.' ) === false && ! empty( $language ) ) {
                           echo esc_html( $file_name . '.' . $language );
                        } else {
                           echo esc_html( $file_name );
                        }
                        ?>
                     </span>
                  </div>
                  <?php } ?>
               </div>
            </div>

            <?php if ( 'yes' === $show_copy_button ) { 
               $this->add_render_attribute( 'eael-code-snippet-copy-button', [
                  'data-clipboard-target' => '#' . esc_attr( $snippet_id ) . ' .eael-code-snippet-code code',
                  'class' => 'eael-code-snippet-copy-button',
                  'type' => 'button',
                  'aria-label' => esc_attr__( 'Copy code to clipboard', 'essential-addons-for-elementor-lite' )
               ] );
               $this->add_render_attribute( 'eael-code-snippet-copy-button', 'aria-label', esc_attr__( 'Copy code to clipboard', 'essential-addons-for-elementor-lite' ) );
               ?>
            <div class="eael-file-preview-right">
               <div class="eael-code-snippet-copy-container">
                  <button <?php $this->print_render_attribute_string( 'eael-code-snippet-copy-button' ); ?>>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                           <path d="M16 1H4C2.9 1 2 1.9 2 3V17H4V3H16V1ZM19 5H8C6.9 5 6 5.9 6 7V21C6 22.1 6.9 23 8 23H19C20.1 23 21 22.1 21 21V7C21 5.9 20.1 5 19 5ZM19 21H8V7H19V21Z" fill="currentColor"/>
                        </svg>
                  </button>
                  <?php if ( 'yes' === $show_copy_tooltip ) { ?>
                  <div class="eael-code-snippet-tooltip"><?php esc_html_e( 'Copy to clipboard', 'essential-addons-for-elementor-lite' ); ?></div>
                  <?php } ?>
               </div>
            </div>
            <?php } ?>
         </div>
      <?php } ?>

         <div class="eael-code-snippet-content">
            <?php if( 'yes' === $show_line_numbers ) { ?>
            <div class="eael-code-snippet-line-numbers" aria-hidden="true">
               <?php foreach ( $line_numbers as $line_number ) { ?>
                  <div class="line-number"><?php echo esc_html( $line_number ); ?></div>
               <?php } ?>
            </div>
            <?php } ?>
            <pre class="eael-code-snippet-code language-<?php echo esc_attr( $language ); ?>"><code><?php echo esc_html( $settings['code_content'] ); ?></code></pre>
            <?php if( 'collapsed' === $settings['code_view_mode'] ) { 
               $indcator_type_class = !empty($settings['code_collapse_inidicator_type']) ? 'eael-cs-indicator-type-' . $settings['code_collapse_inidicator_type'] : '';
               $this->add_render_attribute( 'eael_code_snippet_view_mode', [
                  'class' => [ 
                     'eael-code-snippet-collapsed-indicator',
                     $indcator_type_class,
                     'eael-cs-code-collapsed'
                  ]
              ] );
               ?>
               <div class="eael-code-snippet-collapsed-indicator-wrapper">
                  <div <?php $this->print_render_attribute_string( 'eael_code_snippet_view_mode' ); ?>>
                     <?php if ( 'text' === $settings['code_collapse_inidicator_content_type'] ) { ?>
                        <span class="eael-code-snippet-collapsed-indicator-text eael-csi-collapsed"><?php echo esc_html( $settings['code_collapse_inidicator_text_collapsed'] ); ?></span>
                        <span class="eael-code-snippet-collapsed-indicator-text eael-csi-expanded"><?php echo esc_html( $settings['code_collapse_inidicator_text_expanded'] ); ?></span>
                        <?php } else { ?>
                        <?php Icons_Manager::render_icon( $settings['code_collapse_inidicator_icon_collapsed'], [ 'aria-hidden' => 'true', 'class' => 'eael-code-snippet-collapsed-indicator-icon eael-csi-collapsed' ] ); ?>
                        <?php Icons_Manager::render_icon( $settings['code_collapse_inidicator_icon_expanded'], [ 'aria-hidden' => 'true', 'class' => 'eael-code-snippet-collapsed-indicator-icon eael-csi-expanded' ] ); ?>
                        <?php } ?>
                  </div>
               </div>
            <?php } ?>
         </div>
      </div>
      <?php
   }
}