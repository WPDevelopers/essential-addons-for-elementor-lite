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
               'label'       => __( 'File Name', 'essential-addons-for-elementor-lite' ),
               'type'        => Controls_Manager::TEXT,
               'default'     => 'filename',
               'placeholder' => __( 'Enter filename with extension', 'essential-addons-for-elementor-lite' ),
               'description' => __( 'Enter the filename to display in the header (e.g., hero-section.tsx)', 'essential-addons-for-elementor-lite' ),
               'ai'          => [
                  'active' => false,
               ]
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
               'label'       => __( 'Custom language icon', 'essential-addons-for-elementor-lite' ),
               'type'        => Controls_Manager::MEDIA,
               'media_types' => [ 'image' ],
               'description' => __( 'Upload a custom icon to override the default.', 'essential-addons-for-elementor-lite' ),
               'condition'   => [
                  'show_file_icon' => 'yes',
               ],
               'ai' => [
					'active' => false,
				],
         ]
      );
      
      $this->end_controls_section();
      
      $this->start_controls_section(
         'theme_section',
         [
               'label' => __( 'Theme', 'essential-addons-for-elementor-lite' ),
               'tab'   => Controls_Manager::TAB_CONTENT,
         ]
      );

      $this->add_control(
         'theme',
         [
               'label'   => __( 'Theme', 'essential-addons-for-elementor-lite' ),
               'type'    => Controls_Manager::SELECT,
               'default' => 'light',
               'options' => [
                  'light' => __( 'Light', 'essential-addons-for-elementor-lite' ),
                  'dark'  => __( 'Dark', 'essential-addons-for-elementor-lite' ),
               ],
               'description' => __( 'Choose light or dark styling for the code snippet block.', 'essential-addons-for-elementor-lite' ),
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

      // Generate line numbers if needed
      $line_numbers = [];
      if ( $settings['show_line_numbers'] ) {
         $lines        = explode( "\n", $settings['code_content'] );
         $line_numbers = range( 1, count( $lines ) );
      }

      $file_icon      = $settings['file_icon']['url'] ?? '';
      $show_file_icon = $settings['show_file_icon'] ?? 'yes';
      $language       = $settings['language'] ?? 'html';
      $file_name      = $settings['file_name'] ?? '';
      $theme          = $settings['theme'] ?? 'light';

      ?>
      <div class="eael-code-snippet-wrapper theme-<?php echo esc_attr( $theme ); ?>">
         <div class="eael-code-snippet-header eael-file-preview-header">
            <div class="eael-file-preview-left">
               <div class="eael-traffic-lights">
                  <span class="traffic-light traffic-light-red"></span>
                  <span class="traffic-light traffic-light-yellow"></span>
                  <span class="traffic-light traffic-light-green"></span>
               </div>
               <div class="eael-file-info">
                  <?php if ( 'yes' === $show_file_icon ) { ?>
                  <div class="eael-file-icon">
                     <?php if ( ! empty( $file_icon ) ) { ?>
                     <img src="<?php echo esc_url( $file_icon ); ?>" alt="<?php esc_attr_e( 'File icon', 'essential-addons-for-elementor-lite' ); ?>" />
                     <?php } else {
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

            <div class="eael-file-preview-right">
                     <div class="eael-code-snippet-copy-container">
                        <button class="eael-code-snippet-copy-button"
                                 type="button"
                                 data-clipboard-target="#snippet_id .eael-code-snippet-code code"
                                 aria-label="<?php esc_attr_e( 'Copy code to clipboard', 'essential-addons-for-elementor-lite' ); ?>">
                              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                 <path d="M16 1H4C2.9 1 2 1.9 2 3V17H4V3H16V1ZM19 5H8C6.9 5 6 5.9 6 7V21C6 22.1 6.9 23 8 23H19C20.1 23 21 22.1 21 21V7C21 5.9 20.1 5 19 5ZM19 21H8V7H19V21Z" fill="currentColor"/>
                              </svg>
                        </button>
                        <div class="eael-code-snippet-tooltip"><?php esc_html_e( 'Copy to clipboard', 'essential-addons-for-elementor-lite' ); ?></div>
                     </div>
            </div>

         </div>
         <div class="eael-code-snippet-content">
            <?php if( $settings['show_line_numbers'] ) { ?>
            <div class="eael-code-snippet-line-numbers" aria-hidden="true">
               <?php foreach ( $line_numbers as $key => $line_number ) { ?>
                  <div class="line-number"><?php echo esc_html( $line_number ); ?></div>
               <?php } ?>
            </div>
            <?php } ?>
            <pre class="eael-code-snippet-code language-javascript"><code><?php echo esc_html( $settings['code_content'] ); ?></code></pre>
         </div>
      </div>
      <?php
   }
}