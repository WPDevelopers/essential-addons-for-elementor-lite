<?php
namespace Essential_Addons_Elementor\Elements;

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
		return 'eicon-elementor-circle';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

   public function get_keywords() {
		return [ 'code', 'snippet', 'code snippet', 'ea', 'essential addons' ];
	}

   public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/ea-code-snippet/';
	}

   protected function register_controls() {
      $this->start_controls_section(
			'code_snippet_section',
			[
				'label' => esc_html__( 'Code Snippet', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

      $this->add_control(
         'code_content',
         [
               'label'       => __( 'Code', 'essential-addons-for-elementor-lite' ),
               'type'        => Controls_Manager::CODE,
               'language'    => 'html', // Default language for CodeMirror
               'rows'        => 15,
               'default'     => "// Paste or type your code here…",
               'placeholder' => __( 'Enter your code snippet here...', 'essential-addons-for-elementor-lite' ),
               'description' => __( 'Enter your code snippet. Use Tab for indentation and enjoy syntax highlighting in the editor.', 'essential-addons-for-elementor-lite' ),
         ]
      );

      $this->add_control(
            'language',
            [
               'label'   => __( 'Language', 'essential-addons-for-elementor-lite' ),
               'type'    => Controls_Manager::SELECT,
               'default' => 'html',
               'options' => [
                  'javascript'  => __( 'JavaScript', 'essential-addons-for-elementor-lite' ),
                  'php'         => __( 'PHP', 'essential-addons-for-elementor-lite' ),
                  'python'      => __( 'Python', 'essential-addons-for-elementor-lite' ),
                  'java'        => __( 'Java', 'essential-addons-for-elementor-lite' ),
                  'ruby'        => __( 'Ruby', 'essential-addons-for-elementor-lite' ),
                  'bash'        => __( 'Bash', 'essential-addons-for-elementor-lite' ),
                  'json'        => __( 'JSON', 'essential-addons-for-elementor-lite' ),
                  'yaml'        => __( 'YAML', 'essential-addons-for-elementor-lite' ),
                  'html'        => __( 'HTML', 'essential-addons-for-elementor-lite' ),
                  'css'         => __( 'CSS', 'essential-addons-for-elementor-lite' ),
                  'sql'         => __( 'SQL', 'essential-addons-for-elementor-lite' ),
                  'xml'         => __( 'XML', 'essential-addons-for-elementor-lite' ),
                  'cpp'         => __( 'C++', 'essential-addons-for-elementor-lite' ),
                  'csharp'      => __( 'C#', 'essential-addons-for-elementor-lite' ),
                  'go'          => __( 'Go', 'essential-addons-for-elementor-lite' ),
                  'rust'        => __( 'Rust', 'essential-addons-for-elementor-lite' ),
                  'swift'       => __( 'Swift', 'essential-addons-for-elementor-lite' ),
                  'kotlin'      => __( 'Kotlin', 'essential-addons-for-elementor-lite' ),
                  'typescript'  => __( 'TypeScript', 'essential-addons-for-elementor-lite' ),
               ],
               'description' => __( 'Choose language for syntax highlighting.', 'essential-addons-for-elementor-lite' ),
         ]
      );

      $this->end_controls_section();
      
      $this->start_controls_section(
			'display_options_section',
			[
				'label' => esc_html__( 'Display Options', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

      $this->add_control(
            'show_header',
         [
               'label'        => __( 'Show header bar', 'essential-addons-for-elementor-lite' ),
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
               'label'        => __( 'Enable copy button', 'essential-addons-for-elementor-lite' ),
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
               'label'        => __( 'Enable copy tooltip', 'essential-addons-for-elementor-lite' ),
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
               'label'        => __( 'Show line numbers', 'essential-addons-for-elementor-lite' ),
               'type'         => Controls_Manager::SWITCHER,
               'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
               'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
               'return_value' => 'yes',
               'default'      => 'no',
               'description'  => __( 'Display line numbers in the code block.', 'essential-addons-for-elementor-lite' ),
         ]
      );
      
      $this->end_controls_section();

      $this->start_controls_section(
         'file_preview_section',
         [
               'label' => __( 'File Preview Header', 'essential-addons-for-elementor-lite' ),
               'tab'   => Controls_Manager::TAB_CONTENT,
               'condition' => [
                  'show_header' => 'yes',
               ],
         ]
      );

      $this->add_control(
         'show_language_label',
         [
               'label'        => __( 'Show Language Label', 'essential-addons-for-elementor-lite' ),
               'type'         => Controls_Manager::SWITCHER,
               'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
               'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
               'return_value' => 'yes',
               'default'      => 'yes',
               'description'  => __( 'Display the programming language label in the header.', 'essential-addons-for-elementor-lite' ),
         ]
      );

      $this->add_control(
         'file_name',
         [
               'label' => __( 'File Name', 'essential-addons-for-elementor-lite' ),
               'type' => Controls_Manager::TEXT,
               'default' => 'filename.js',
               'placeholder' => __( 'Enter filename with extension', 'essential-addons-for-elementor-lite' ),
               'description' => __( 'Enter the filename to display in the header (e.g., hero-section.tsx)', 'essential-addons-for-elementor-lite' ),
         ]
      );

      $this->add_control(
         'show_traffic_lights',
         [
               'label' => __( 'Show window buttons', 'essential-addons-for-elementor-lite' ),
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
               'label' => __( 'Show language icon', 'essential-addons-for-elementor-lite' ),
               'type' => Controls_Manager::SWITCHER,
               'label_on' => __( 'Show', 'essential-addons-for-elementor-lite' ),
               'label_off' => __( 'Hide', 'essential-addons-for-elementor-lite' ),
               'return_value' => 'yes',
               'default' => 'yes',
               'description' => __( 'Display the default icon for this file type.', 'essential-addons-for-elementor-lite' ),
         ]
      );

      $this->add_control(
         'file_icon',
         [
               'label' => __( 'Custom language icon', 'essential-addons-for-elementor-lite' ),
               'type' => Controls_Manager::MEDIA,
               'media_types' => [ 'image' ],
               'description' => __( 'Upload a custom icon to override the default.', 'essential-addons-for-elementor-lite' ),
               'condition' => [
                  'show_file_icon' => 'yes',
               ],
         ]
      );
      
      $this->end_controls_section();
      
   }

   protected function render() {
      echo '<h1>Code Snippet</h1>';
   }
}