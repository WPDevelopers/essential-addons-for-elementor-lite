<?php

namespace Essential_Addons_Elementor\Elements;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Essential_Addons_Elementor\Traits\Login_Registration;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Class Login_Register
 * @package Essential_Addons_Elementor\Elements
 */
class Login_Register extends Widget_Base {

	use Login_Registration;

	/**
	 * Does the site allows new user registration?
	 * @var bool
	 */
	protected $user_can_register;

	/**
	 * Are you currently in Elementor Editor Screen?
	 * @var bool
	 */
	protected $in_editor;

	/**
	 * Should login form be printed?
	 * @var bool
	 */
	protected $should_print_login_form;

	/**
	 * Should registration form be printed?
	 * @var bool
	 */
	protected $should_print_register_form;

	/**
	 * It contains an array of settings for the display
	 * @var array
	 */
	protected $d_settings;
	/**
	 * @var bool|false|int
	 */
	protected $page_id;

	/**
	 * Login_Register constructor.
	 * Initializing the Login_Register widget class.
	 * @inheritDoc
	 */
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		$this->user_can_register = get_option( 'users_can_register' );
		$this->in_editor         = Plugin::instance()->editor->is_edit_mode();
	}

	/**
	 * @inheritDoc
	 */
	public function get_name() {
		return 'eael-login-register';
	}

	/**
	 * @inheritDoc
	 */
	public function get_title() {
		return esc_html__( 'Login | Register Form', EAEL_TEXTDOMAIN );
	}

	/**
	 * @inheritDoc
	 */
	public function get_icon() {
		return 'eicon-lock-user'; //@TODO; use better icon later
	}

	/**
	 * @inheritDoc
	 */
	public function get_keywords() {
		return [
			'login',
			'ea login',
			'register',
			'ea register',
			'registration',
			'ea registration',
			'sign in',
			'sign out',
			'logout',
			'auth',
			'authentication',
			'user-registration',
			'google',
			'facebook',
			'ea',
			'essential addons',
		];
	}

	/**
	 * @inheritDoc
	 */
	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	/**
	 * Get an array of form field types.
	 * @return array
	 */
	protected function get_form_field_types() {
		return apply_filters( 'eael/registration-form-fields', [
			'user_name'    => __( 'Username', EAEL_TEXTDOMAIN ),
			'email'        => __( 'Email', EAEL_TEXTDOMAIN ),
			'password'     => __( 'Password', EAEL_TEXTDOMAIN ),
			'confirm_pass' => __( 'Confirm Password', EAEL_TEXTDOMAIN ),
			'first_name'   => __( 'First Name', EAEL_TEXTDOMAIN ),
			'last_name'    => __( 'Last Name', EAEL_TEXTDOMAIN ),
			'website'      => __( 'Website', EAEL_TEXTDOMAIN ),
		] );
	}

	/**
	 * @inheritDoc
	 */
	protected function _register_controls() {
		/*----Content Tab----*/
		$this->init_content_general_controls();
		// Login Form Related---
		$this->init_content_login_fields_controls();
		$this->init_content_login_options_controls();
		// Registration For Related---
		$this->init_content_register_fields_controls();
		$this->init_content_register_options_controls();
		$this->init_content_register_user_email_controls();
		$this->init_content_register_admin_email_controls();
		$this->init_content_register_validation_message_controls();


		/*----Style Tab----*/
		$this->init_style_general_controls();
		$this->init_style_input_fields_controls();
		$this->init_style_input_labels_controls();
		$this->init_style_login_button_controls();
		$this->init_style_register_button_controls();

	}

	/**
	 * It adds controls related to Login Form Types section to the Widget Content Tab
	 */
	protected function init_content_general_controls() {
		$this->start_controls_section( 'section_content_general', [
			'label' => __( 'General', EAEL_TEXTDOMAIN ),
		] );

		$this->add_control( 'default_form_type', [
			'label'       => __( 'Default Form Type', EAEL_TEXTDOMAIN ),
			'description' => __( 'Choose the type of form you want to show by default. Note: you can show both form in a single page even if you select only login or registration from below.', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::SELECT,
			'options'     => [
				'login'        => __( 'Login', EAEL_TEXTDOMAIN ),
				'registration' => __( 'Registration', EAEL_TEXTDOMAIN ),
			],
			'default'     => 'login',
		] );

		$this->add_control( 'hide_for_logged_in_user', [
			'label'       => __( 'Hide from Logged in Users', EAEL_TEXTDOMAIN ),
			//'description' => __( 'You can hide the form for already logged in user.', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'show_login_link', [
			'label'       => __( 'Show Login Link', EAEL_TEXTDOMAIN ),
			//'description' => __( 'You can add a "Login" Link below the registration form', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::SWITCHER,
			'default'     => 'yes',
			'condition'   => [
				'default_form_type' => 'registration',
			],
		] );

		$this->add_control( 'login_link_action', [
			'label'       => __( 'Login Link Action', EAEL_TEXTDOMAIN ),
			//'description' => __( 'Select what should happen when the login link is clicked', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::SELECT,
			'options'     => [
				'default' => __( 'Default WordPress Page', EAEL_TEXTDOMAIN ),
				'custom'  => __( 'Custom URL', EAEL_TEXTDOMAIN ),
				'form'    => __( 'Show Login Form', EAEL_TEXTDOMAIN ),
			],
			'default'     => 'default',
			'condition'   => [
				'show_login_link' => 'yes',
			],
		] );

		$this->add_control( 'custom_login_url', [
			'label'     => __( 'Custom Login URL', EAEL_TEXTDOMAIN ),
			'type'      => Controls_Manager::URL,
			'dynamic'   => [
				'active' => true,
			],
			'condition' => [
				'login_link_action' => 'custom',
				'show_login_link'   => 'yes',
			],
		] );

		if ( ! $this->user_can_register ) {
			$this->add_control( 'registration_off_notice', [
				'type'            => Controls_Manager::RAW_HTML,
				/* translators: %1$s is settings page link open tag, %2$s is link closing tag */
				'raw'             => sprintf( __( 'Registration is disabled on your site. Please enable it to use registration form. You can enable it from Dashboard » Settings » General » %1$sMembership%2$s.', EAEL_TEXTDOMAIN ), '<a href="' . esc_attr( esc_url( admin_url( 'options-general.php' ) ) ) . '" target="_blank">', '</a>' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'condition'       => [
					'default_form_type' => 'registration',
				],
			] );
		}


		/*--show registration related control only if registration is enable on the site--*/
		if ( $this->user_can_register ) {
			$this->add_control( 'show_registration_link', [
				'label'       => __( 'Show Register Link', EAEL_TEXTDOMAIN ),
				//'description' => __( 'You can add a "Register" Link below the login form', EAEL_TEXTDOMAIN ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'condition'   => [
					'default_form_type' => 'login',
				],
			] );

			$this->add_control( 'registration_link_text', [
				'label'     => __( 'Register Link Text', EAEL_TEXTDOMAIN ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => [
					'active' => true,
				],
				'default'   => __( 'Register', EAEL_TEXTDOMAIN ),
				'condition' => [
					'show_registration_link' => 'yes',
					'default_form_type'      => 'login',
				],
			] );

			$this->add_control( 'registration_link_action', [
				'label'     => __( 'Registration Link Action', EAEL_TEXTDOMAIN ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'default' => __( 'WordPress Registration Page', EAEL_TEXTDOMAIN ),
					'custom'  => __( 'Custom URL', EAEL_TEXTDOMAIN ),
					'form'    => __( 'Display Form', EAEL_TEXTDOMAIN ),
				],
				'default'   => 'default',
				'condition' => [
					'show_registration_link' => 'yes',
					'default_form_type'      => 'login',
				],
			] );

			$this->add_control( 'custom_register_url', [
				'label'     => __( 'Custom Register URL', EAEL_TEXTDOMAIN ),
				'type'      => Controls_Manager::URL,
				'dynamic'   => [
					'active' => true,
				],
				'condition' => [
					'login_link_action' => 'custom',
					'show_login_link'   => 'yes',
				],
			] );
		}


		$this->add_control( 'show_log_out_message', [
			'label'       => __( 'Show Logout Link', EAEL_TEXTDOMAIN ),
			//'description' => __( 'This option will show a message with logout link instead of a login form for the logged in user', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::SWITCHER,
			'default'     => 'yes',
			'condition'   => [
				'default_form_type' => 'login',
			],
		] );


		$this->add_control( 'show_lost_password', [
			'label'       => __( 'Show Lost your password?', EAEL_TEXTDOMAIN ),
			//'description' => __( 'You can add a "Forgot Password" Link below the the form', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::SWITCHER,
			'default'     => 'yes',
		] );


		$this->add_control( 'show_lost_password_text', [
			'label'     => __( 'Lost Password Text', EAEL_TEXTDOMAIN ),
			'type'      => Controls_Manager::TEXT,
			'dynamic'   => [
				'active' => true,
			],
			'default'   => __( 'Lost your password?', EAEL_TEXTDOMAIN ),
			'condition' => [
				'show_lost_password' => 'yes',
			],
		] );

		$this->add_control( 'lost_password_link_type', [
			'label'     => __( 'Lost Password Link to', EAEL_TEXTDOMAIN ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'default' => __( 'Default WordPress Page', EAEL_TEXTDOMAIN ),
				'custom'  => __( 'Custom URL', EAEL_TEXTDOMAIN ),
			],
			'default'   => 'default',
			'condition' => [
				'show_lost_password' => 'yes',
			],
		] );

		$this->add_control( 'lost_password_url', [
			'label'     => __( 'Enter URL', EAEL_TEXTDOMAIN ),
			'type'      => Controls_Manager::URL,
			'dynamic'   => [
				'active' => true,
			],
			'condition' => [
				'lost_password_link_type' => 'custom',
				'show_lost_password'      => 'yes',
			],
		] );

		$this->add_control( 'footer_divider', [
			'label'      => __( 'Link Divider', EAEL_TEXTDOMAIN ),
			'type'       => Controls_Manager::TEXT,
			'default'    => '|',
			'selectors'  => [
				'{{WRAPPER}} .eael-login-form-footer a.eael-login-form-footer-link:not(:last-child) span:after' => 'content: "{{VALUE}}"; margin: 0 0.4em;',
			],
			'separator'  => 'before',
			'conditions' => [
				'terms' => [
					[
						'name'     => 'show_lost_password',
						'value'    => 'yes',
						'relation' => 'or',
						'terms'    => [
							[
								'name'  => 'show_login_link',
								'value' => 'yes',
								'terms' => [
									[
										'name'  => 'default_form_type',
										'value' => 'registration',
									],
								],
							],
							[
								'name'  => 'show_registration_link',
								'value' => 'yes',
								'terms' => [
									[
										'name'  => 'default_form_type',
										'value' => 'login',
									],
								],
							],
						],
					],

				],
			],
		] );

		$this->add_responsive_control( 'footer_text_align', [
			'label'      => __( 'Link Alignment', EAEL_TEXTDOMAIN ),
			'type'       => Controls_Manager::CHOOSE,
			'options'    => [
				'flex-start' => [
					'title' => __( 'Left', EAEL_TEXTDOMAIN ),
					'icon'  => 'eicon-text-align-left',
				],
				'center'     => [
					'title' => __( 'Center', EAEL_TEXTDOMAIN ),
					'icon'  => 'eicon-text-align-center',
				],
				'flex-end'   => [
					'title' => __( 'Right', EAEL_TEXTDOMAIN ),
					'icon'  => 'eicon-text-align-right',
				],
			],
			'separator'  => 'before',
			'default'    => 'flex-start',
			'selectors'  => [
				'{{WRAPPER}} .eael-login-form-footer' => 'justify-content: {{VALUE}};',
			],
			'conditions' => [
				'relation' => 'or',
				'terms'    => $this->get_login_footer_controls_display_condition(),
			],
		] );

		$this->add_control( 'footer_text_color', [
			'label'      => __( 'Link Text Color', EAEL_TEXTDOMAIN ),
			'type'       => Controls_Manager::COLOR,
			'scheme'     => [
				'type'  => Color::get_type(),
				'value' => Color::COLOR_4,
			],
			'selectors'  => [
				'{{WRAPPER}} .eael-login-form-footer, {{WRAPPER}} .eael-login-form-footer a' => 'color: {{VALUE}};',
			],
			'conditions' => [
				'relation' => 'or',
				'terms'    => $this->get_login_footer_controls_display_condition(),
			],
		] );


		$this->end_controls_section();
	}

	/**
	 * It adds controls related to Login Form Fields section to the Widget Content Tab
	 */
	protected function init_content_login_fields_controls() {
		$this->start_controls_section( 'section_content_login_fields', [
			'label'      => __( 'Login Form Fields', EAEL_TEXTDOMAIN ),
			'conditions' => $this->get_login_controls_display_condition(),
		] );

		$this->add_control( 'login_label_types', [
			'label'   => __( 'Login Fields Label', EAEL_TEXTDOMAIN ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'default' => __( 'Default', EAEL_TEXTDOMAIN ),
				'custom'  => __( 'Custom', EAEL_TEXTDOMAIN ),
				'none'    => __( 'Hide', EAEL_TEXTDOMAIN ),
			],
			'default' => 'default',
		] );

		$this->add_control( 'login_labels_heading', [
			'label'     => esc_html__( 'Labels', EAEL_TEXTDOMAIN ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [ 'login_label_types' => 'custom', ],
		] );


		$this->add_control( 'login_user_label', [
			'label'       => __( 'Username Label', EAEL_TEXTDOMAIN ),
			'default'     => __( 'Username or Email Address', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'label_block' => true,
			'condition'   => [ 'login_label_types' => 'custom', ],
		] );

		$this->add_control( 'login_password_label', [
			'label'       => __( 'Password Label', EAEL_TEXTDOMAIN ),
			'default'     => __( 'Password', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'label_block' => true,
			'condition'   => [ 'login_label_types' => 'custom', ],
		] );

		$this->add_control( 'login_placeholders_heading', [
			'label'     => esc_html__( 'Placeholders', EAEL_TEXTDOMAIN ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'login_user_placeholder', [
			'label'       => __( 'Username Placeholder', EAEL_TEXTDOMAIN ),
			'default'     => __( 'Username or Email Address', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'label_block' => true,
			'condition'   => [ 'login_label_types' => 'custom', ],
		] );

		$this->add_control( 'login_password_placeholder', [
			'label'       => __( 'Password Placeholder', EAEL_TEXTDOMAIN ),
			'default'     => __( 'Password', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'label_block' => true,
			'condition'   => [ 'login_label_types' => 'custom', ],
		] );

		$this->add_responsive_control( 'login_field_width', [
			'label'      => esc_html__( 'Input Fields width', EAEL_TEXTDOMAIN ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'%',
			],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 500,
					'step' => 5,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'default'    => [
				'unit' => '%',
				'size' => 100,
			],
			'selectors'  => [
				'{{WRAPPER}} .eael-login-form input' => 'width: {{SIZE}}{{UNIT}};',
			],
			'separator'  => 'before',
		] );

		$this->add_control( 'login_show_remember_me', [
			'label'     => __( 'Remember Me Checkbox', EAEL_TEXTDOMAIN ),
			'type'      => Controls_Manager::SWITCHER,
			'default'   => 'yes',
			'label_off' => __( 'Hide', EAEL_TEXTDOMAIN ),
			'label_on'  => __( 'Show', EAEL_TEXTDOMAIN ),
		] );

		$this->add_control( 'login_enable_ajax', [
			'label'   => __( 'Submit Login Form via AJAX', EAEL_TEXTDOMAIN ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		/*--Login Fields Button--*/
		$this->add_control( 'login_button_heading', [
			'label'     => esc_html__( 'Login Button', EAEL_TEXTDOMAIN ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'login_button_text', [
			'label'   => __( 'Button Text', EAEL_TEXTDOMAIN ),
			'type'    => Controls_Manager::TEXT,
			'dynamic' => [ 'active' => true, ],
			'default' => __( 'Log In', EAEL_TEXTDOMAIN ),
		] );

		$this->end_controls_section();
	}

	protected function init_content_login_options_controls() {

		$this->start_controls_section( 'section_content_login_actions', [
			'label'      => __( 'Login Form Actions', EAEL_TEXTDOMAIN ),
			'conditions' => $this->get_login_controls_display_condition(),
		] );

		$this->add_control( 'redirect_after_login', [
			'label' => __( 'Redirect After Login', EAEL_TEXTDOMAIN ),
			'type'  => Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'redirect_url', [
			'type'          => Controls_Manager::URL,
			'show_label'    => false,
			'show_external' => false,
			'placeholder'   => __( 'https://your-link.com', EAEL_TEXTDOMAIN ),
			'description'   => __( 'Please note that only your current domain is allowed here to keep your site secure.', EAEL_TEXTDOMAIN ),
			'condition'     => [
				'redirect_after_login' => 'yes',
			],
			'separator'     => 'after',
		] );

		$this->add_control( 'redirect_after_logout', [
			'label' => __( 'Redirect After Logout', EAEL_TEXTDOMAIN ),
			'type'  => Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'redirect_logout_url', [
			'type'          => Controls_Manager::URL,
			'show_label'    => false,
			'show_external' => false,
			'placeholder'   => __( 'https://your-link.com', EAEL_TEXTDOMAIN ),
			'description'   => __( 'Please note that only your current domain is allowed here to keep your site secure.', EAEL_TEXTDOMAIN ),
			'condition'     => [
				'redirect_after_logout' => 'yes',
			],
			'separator'     => 'after',
		] );

		$this->end_controls_section();
	}

	protected function init_content_register_fields_controls() {

		$this->start_controls_section( 'section_content_register_fields', [
			'label'      => __( 'Register Form Fields', EAEL_TEXTDOMAIN ),
			'conditions' => $this->get_register_controls_display_condition(),
		] );
		$this->add_control( 'register_form_field_note', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => __( 'Select the type of fields you want to show in the registration form', EAEL_TEXTDOMAIN ),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
		] );
		$repeater = new Repeater();

		$repeater->add_control( 'field_type', [
			'label'   => __( 'Type', EAEL_TEXTDOMAIN ),
			'type'    => Controls_Manager::SELECT,
			'options' => $this->get_form_field_types(),
			'default' => 'first_name',
		] );

		$repeater->add_control( 'field_label', [
			'label'   => __( 'Label', EAEL_TEXTDOMAIN ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
			'dynamic' => [
				'active' => true,
			],
		] );

		$repeater->add_control( 'placeholder', [
			'label'   => __( 'Placeholder', EAEL_TEXTDOMAIN ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
			'dynamic' => [
				'active' => true,
			],
		] );

		$repeater->add_control( 'required', [
			'label'     => __( 'Required', EAEL_TEXTDOMAIN ),
			'type'      => Controls_Manager::SWITCHER,
			'condition' => [
				'field_type!' => [
					'email',
					'password',
				],
			],
		] );

		$repeater->add_control( 'required_note', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => __( 'Note: This field is required by default.', EAEL_TEXTDOMAIN ),
			'condition'       => [
				'field_type' => [
					'email',
					'password',
				],
			],
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
		] );

		$repeater->add_responsive_control( 'width', [
			'label'   => __( 'Field Width', EAEL_TEXTDOMAIN ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				''    => __( 'Default', EAEL_TEXTDOMAIN ),
				'100' => '100%',
				'80'  => '80%',
				'75'  => '75%',
				'66'  => '66%',
				'60'  => '60%',
				'50'  => '50%',
				'40'  => '40%',
				'33'  => '33%',
				'25'  => '25%',
				'20'  => '20%',
			],
			'default' => '100',
		] );

		$this->add_control( 'register_fields', [
			'type'        => Controls_Manager::REPEATER,
			'fields'      => array_values( $repeater->get_controls() ),
			'default'     => [
				[
					'field_type'  => 'user_name',
					'field_label' => __( 'Username', EAEL_TEXTDOMAIN ),
					'placeholder' => __( 'Username', EAEL_TEXTDOMAIN ),
					'width'       => '100',
				],
				[
					'field_type'  => 'email',
					'field_label' => __( 'Email', EAEL_TEXTDOMAIN ),
					'placeholder' => __( 'Email', EAEL_TEXTDOMAIN ),
					'required'    => 'yes',
					'width'       => '100',
				],
				[
					'field_type'  => 'password',
					'field_label' => __( 'Password', EAEL_TEXTDOMAIN ),
					'placeholder' => __( 'Password', EAEL_TEXTDOMAIN ),
					'required'    => 'yes',
					'width'       => '100',
				],
			],
			'title_field' => '{{{ field_label }}}',
		] );

		$this->add_control( 'show_labels', [
			'label'   => __( 'Show Label', EAEL_TEXTDOMAIN ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'mark_required', [
			'label'     => __( 'Show Required Mark', EAEL_TEXTDOMAIN ),
			'type'      => Controls_Manager::SWITCHER,
			'condition' => [
				'show_labels' => 'yes',
			],
		] );


		$this->end_controls_section();
	}

	protected function init_content_register_options_controls() {

		$this->start_controls_section( 'section_content_register_actions', [
			'label'      => __( 'Register Form Options', EAEL_TEXTDOMAIN ),
			'conditions' => $this->get_register_controls_display_condition(),
		] );

		$this->add_control( 'register_action', [
			'label'       => __( 'Register Actions', EAEL_TEXTDOMAIN ),
			'description' => __( 'You can select what should happen after a user registers successfully', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::SELECT2,
			'multiple'    => true,
			'label_block' => true,
			'default'     => 'send_email',
			'options'     => [
				'redirect'   => __( 'Redirect', EAEL_TEXTDOMAIN ),
				'auto_login' => __( 'Auto Login', EAEL_TEXTDOMAIN ),
				'send_email' => __( 'Notify User By Email', EAEL_TEXTDOMAIN ),
			],
		] );

		$this->add_control( 'register_redirect_url', [
			'type'          => Controls_Manager::URL,
			'label'         => __( 'Custom Redirect URL', EAEL_TEXTDOMAIN ),
			'show_external' => false,
			'placeholder'   => __( 'eg. https://your-link.com/wp-admin/', EAEL_TEXTDOMAIN ),
			'description'   => __( 'Please note that only your current domain is allowed here to keep your site secure.', EAEL_TEXTDOMAIN ),
			'default'       => [
				'url'         => get_admin_url(),
				'is_external' => false,
				'nofollow'    => true,
			],
			'condition'     => [
				'register_action' => 'redirect',
			],
		] );

		$this->add_control( 'register_user_role', [
			'label'     => __( 'New User Role', EAEL_TEXTDOMAIN ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'default',
			'options'   => $this->get_user_roles(),
			'separator' => 'before',
		] );


		$this->end_controls_section();
	}

	protected function init_content_register_user_email_controls() {
		/* translators: %s: Site Name */
		$default_subject = sprintf( __( 'Thank you for registering on "%s"!', EAEL_TEXTDOMAIN ), get_option( 'blogname' ) );
		$default_message = $default_subject . "\r\n\r\n";
		$default_message .= __( 'Username: [username]', EAEL_TEXTDOMAIN ) . "\r\n\r\n";
		$default_message .= __( 'Password: [password]', EAEL_TEXTDOMAIN ) . "\r\n\r\n";
		$default_message .= __( 'To reset your password, visit the following address:', EAEL_TEXTDOMAIN ) . "\r\n\r\n";
		$default_message .= "[password_reset_link]\r\n\r\n";
		$default_message .= __( 'Please click the following address to login to your account:', EAEL_TEXTDOMAIN ) . "\r\n\r\n";
		$default_message .= wp_login_url() . "\r\n";

		$this->start_controls_section( 'section_content_reg_email', [
			'label'      => __( 'Register User Email Options', EAEL_TEXTDOMAIN ),
			'conditions' => [
				'relation' => 'or',
				'terms'    => [
					[
						'name'  => 'show_registration_link',
						'value' => 'yes',
						//@TODO; debug why multi-level condition is not working.
						//'relation' => 'and',
						//'terms'    => [
						//	[
						//		'name'     => 'register_action',
						//		'value'    => 'send_email',
						//		'operator' => '===',
						//	],
						//],
					],
					[
						'name'  => 'default_form_type',
						'value' => 'registration',
						//'relation' => 'and',
						//'terms'    => [
						//	[
						//		'name'     => 'register_action',
						//		'value'    => 'send_email',
						//		'operator' => '===',
						//	],
						//],
					],
				],
			],
		] );

		$this->add_control( 'reg_email_template_type', [
			'label'       => __( 'Email Template Type', EAEL_TEXTDOMAIN ),
			'description' => __( 'Default template uses WordPress Default email template. So, please select the Custom Option to send user proper information to user if you did you use any username field.', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::SELECT,
			'default'     => 'default',
			'render_type' => 'none',
			'options'     => [
				'default' => __( 'WordPres Default', EAEL_TEXTDOMAIN ),
				'custom'  => __( 'Custom', EAEL_TEXTDOMAIN ),
			],
		] );

		$this->add_control( 'reg_email_subject', [
			'label'       => __( 'Email Subject', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => $default_subject,
			'default'     => $default_subject,
			'label_block' => true,
			'render_type' => 'none',
			'condition'   => [
				'reg_email_template_type' => 'custom',
			],
		] );

		$this->add_control( 'reg_email_message', [
			'label'       => __( 'Email Message', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::WYSIWYG,
			'placeholder' => __( 'Enter Your Custom Email Message..', EAEL_TEXTDOMAIN ),
			'default'     => $default_message,
			'label_block' => true,
			'render_type' => 'none',
			'condition'   => [
				'reg_email_template_type' => 'custom',
			],
		] );

		$this->add_control( 'reg_email_content_note', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => __( '<strong>Note:</strong> You can use dynamic content in the email body like [fieldname]. For example [username] will be replaced by user-typed username. Available tags are: [password], [username], [email], [firstname],[lastname], [website], [loginurl], [password_reset_link] and [sitetitle] ', EAEL_TEXTDOMAIN ),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			'condition'       => [
				'reg_email_template_type' => 'custom',
			],
			'render_type'     => 'none',
		] );

		$this->add_control( 'reg_email_content_type', [
			'label'       => __( 'Email Content Type', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::SELECT,
			'default'     => 'html',
			'render_type' => 'none',
			'options'     => [
				'html'  => __( 'HTML', EAEL_TEXTDOMAIN ),
				'plain' => __( 'Plain', EAEL_TEXTDOMAIN ),
			],
			'condition'   => [
				'reg_email_template_type' => 'custom',
			],
		] );

		$this->end_controls_section();
	}

	protected function init_content_register_admin_email_controls() {
		/* translators: %s: Site Name */
		$default_subject = sprintf( __( '["%s"] New User Registration', EAEL_TEXTDOMAIN ), get_option( 'blogname' ) );
		/* translators: %s: Site Name */
		$default_message = sprintf( __( "New user registration on your site %s", EAEL_TEXTDOMAIN ), get_option( 'blogname' ) ) . "\r\n\r\n";
		$default_message .= __( 'Username: [username]', EAEL_TEXTDOMAIN ) . "\r\n\r\n";
		$default_message .= __( 'Email: [email]', EAEL_TEXTDOMAIN ) . "\r\n\r\n";


		$this->start_controls_section( 'section_content_reg_admin_email', [
			'label'      => __( 'Register Admin Email Options', EAEL_TEXTDOMAIN ),
			'conditions' => [
				'relation' => 'or',
				'terms'    => [
					[
						'name'  => 'show_registration_link',
						'value' => 'yes',
						//@TODO; debug why multi-level condition is not working.
						//'relation' => 'and',
						//'terms'    => [
						//	[
						//		'name'     => 'register_action',
						//		'value'    => 'send_email',
						//		'operator' => '===',
						//	],
						//],
					],
					[
						'name'  => 'default_form_type',
						'value' => 'registration',
						//'relation' => 'and',
						//'terms'    => [
						//	[
						//		'name'     => 'register_action',
						//		'value'    => 'send_email',
						//		'operator' => '===',
						//	],
						//],
					],
				],
			],
		] );

		$this->add_control( 'reg_admin_email_template_type', [
			'label'       => __( 'Email Template Type', EAEL_TEXTDOMAIN ),
			'description' => __( 'Default template uses WordPress Default Admin email template. You can customize it by choosing the custom option.', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::SELECT,
			'default'     => 'default',
			'render_type' => 'none',
			'options'     => [
				'default' => __( 'WordPres Default', EAEL_TEXTDOMAIN ),
				'custom'  => __( 'Custom', EAEL_TEXTDOMAIN ),
			],
		] );

		$this->add_control( 'reg_admin_email_subject', [
			'label'       => __( 'Email Subject', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => $default_subject,
			'default'     => $default_subject,
			'label_block' => true,
			'render_type' => 'none',
			'condition'   => [
				'reg_admin_email_template_type' => 'custom',
			],
		] );

		$this->add_control( 'reg_admin_email_message', [
			'label'       => __( 'Email Message', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::WYSIWYG,
			'placeholder' => __( 'Enter Your Custom Email Message..', EAEL_TEXTDOMAIN ),
			'default'     => $default_message,
			'label_block' => true,
			'render_type' => 'none',
			'condition'   => [
				'reg_admin_email_template_type' => 'custom',
			],
		] );

		$this->add_control( 'reg_admin_email_content_note', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => __( '<strong>Note:</strong> You can use dynamic content in the email body like [fieldname]. For example [username] will be replaced by user-typed username. Available tags are: [username], [email], [firstname],[lastname], [website], [loginurl] and [sitetitle] ', EAEL_TEXTDOMAIN ),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			'condition'       => [
				'reg_admin_email_template_type' => 'custom',
			],
			'render_type'     => 'none',
		] );

		$this->add_control( 'reg_admin_email_content_type', [
			'label'       => __( 'Email Content Type', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::SELECT,
			'default'     => 'html',
			'render_type' => 'none',
			'options'     => [
				'html'  => __( 'HTML', EAEL_TEXTDOMAIN ),
				'plain' => __( 'Plain', EAEL_TEXTDOMAIN ),
			],
			'condition'   => [
				'reg_admin_email_template_type' => 'custom',
			],
		] );

		$this->end_controls_section();
	}

	protected function init_content_register_validation_message_controls() {
		$this->start_controls_section( 'section_content_reg_validation', [
			'label'      => __( 'Register Validation Messages', EAEL_TEXTDOMAIN ),
			'tab'        => Controls_Manager::TAB_CONTENT,
			'conditions' => $this->get_register_controls_display_condition(),
		] );

		$this->add_control( 'reg_success_message', [
			'label'       => __( 'Success Message', EAEL_TEXTDOMAIN ),
			'description' => __( 'Specify what you want to show when registration succeeds', EAEL_TEXTDOMAIN ),
			'label_block' => true,
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => __( 'Thank you for registering with us! Please check your mail for your account info', EAEL_TEXTDOMAIN ),
		] );

		$this->add_control( 'reg_error_message', [
			'label'       => __( 'Error Message', EAEL_TEXTDOMAIN ),
			'description' => __( 'Specify what you want to show when registration fails', EAEL_TEXTDOMAIN ),

			'label_block' => true,
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => __( 'Error: Something went wrong! Registration failed..', EAEL_TEXTDOMAIN ),
		] );

		$this->end_controls_section();

	}

	/**
	 * It prints controls for managing general style of both login and registration form
	 */
	protected function init_style_general_controls() {
		$this->start_controls_section( 'section_style_general', [
			'label' => __( 'General', EAEL_TEXTDOMAIN ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );
		$this->add_control( "eael_form_margin", [
			'label'      => __( 'Form Margin', EAEL_TEXTDOMAIN ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-form" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );
		$this->add_control( "eael_form_padding", [
			'label'      => __( 'Form Padding', EAEL_TEXTDOMAIN ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-form" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );
		$this->end_controls_section();
	}

	protected function init_style_input_fields_controls() {
		$this->start_controls_section( 'section_style_form_fields', [
			'label' => __( 'Form Fields', EAEL_TEXTDOMAIN ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );
		$this->add_control( "eael_form_field_margin", [
			'label'      => __( 'Margin', EAEL_TEXTDOMAIN ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-form .input-field" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_control( "eael_form_field_padding", [
			'label'      => __( 'Padding', EAEL_TEXTDOMAIN ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-form .input-field" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );
		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => "eael_fields_typography",
			'selector' => "{{WRAPPER}} .eael-form .input-field",
		] );
		$this->start_controls_tabs( "tabs_form_fields_style" );

		/*-----Form Input Fields NORMAL state------ */
		$this->start_controls_tab( "tab_form_field_style_normal", [
			'label' => __( 'Normal', EAEL_TEXTDOMAIN ),
		] );
		$this->add_control( 'eael_field_color', [
			'label'     => __( 'Text Color', EAEL_TEXTDOMAIN ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .eael-form .input-field" => 'color: {{VALUE}};',
			],
		] );
		$this->add_control( 'eael_field_placeholder_color', [
			'label'     => __( 'Placeholder Color', EAEL_TEXTDOMAIN ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .eael-form .input-field" => 'color: {{VALUE}};',
			],
		] );
		$this->add_control( 'eael_field_bg_color', [
				'label'     => __( 'Background Color', EAEL_TEXTDOMAIN ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					"{{WRAPPER}} .eael-form .input-field" => 'background-color: {{VALUE}};',
				],
			] );
		$this->end_controls_tab();

		$this->start_controls_tab( "tab_form_field_style_active", [
			'label' => __( 'Active', EAEL_TEXTDOMAIN ),
		] );
		$this->add_control( 'eael_field_color_active', [
			'label'     => __( 'Text Color', EAEL_TEXTDOMAIN ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .eael-form .input-field" => 'color: {{VALUE}};',
			],
		] );
		$this->add_control( 'eael_field_placeholder_color_active', [
			'label'     => __( 'Placeholder Color', EAEL_TEXTDOMAIN ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .eael-form .input-field" => 'color: {{VALUE}};',
			],
		] );
		$this->add_control( 'eael_field_bg_color_active', [
				'label'     => __( 'Background Color', EAEL_TEXTDOMAIN ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					"{{WRAPPER}} .eael-form .input-field" => 'background-color: {{VALUE}};',
				],
			] );

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	protected function init_style_input_labels_controls() {
		$this->start_controls_section( 'section_style_form_labels', [
			'label' => __( 'Form Labels', EAEL_TEXTDOMAIN ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );
		$this->add_control( "eael_form_label_padding", [
			'label'      => __( 'Spacing', EAEL_TEXTDOMAIN ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-form .input-lable" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );
		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => "eael_label_typography",
			'selector' => "{{WRAPPER}} .eael-form .input-label",
		] );

		$this->add_control( 'eael_label_color', [
			'label'     => __( 'Text Color', EAEL_TEXTDOMAIN ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .eael-form .input-label" => 'color: {{VALUE}};',
			],
		] );
		$this->add_control( 'eael_label_bg_color', [
				'label'     => __( 'Background Color', EAEL_TEXTDOMAIN ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					"{{WRAPPER}} .eael-form .input-label" => 'background-color: {{VALUE}};',
				],
			] );
		$this->end_controls_section();
	}

	protected function init_style_login_button_controls() {
		$this->_init_button_style( 'login' );
	}

	protected function init_style_register_button_controls() {
		$this->_init_button_style( 'register' );
	}

	protected function init_style_validation_message_controls() {
		$this->start_controls_section( 'section_style_validation_message', [
			'label' => __( 'Validation Message', EAEL_TEXTDOMAIN ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->end_controls_section();
	}

	/**
	 * Print style controls for a specific type of button.
	 *
	 * @param string $button_type the type of the button. accepts login or register.
	 */
	protected function _init_button_style( $button_type = 'login' ) {
		$this->start_controls_section( "section_style_{$button_type}_btn", [
			'label' => sprintf( __( '%s Button', EAEL_TEXTDOMAIN ), ucfirst( $button_type ) ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );
		$this->add_control( "{$button_type}_btn_margin", [
			'label'      => __( 'Margin', EAEL_TEXTDOMAIN ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-{$button_type}-form button" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );
		$this->add_control( "{$button_type}_btn_padding", [
			'label'      => __( 'Padding', EAEL_TEXTDOMAIN ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-{$button_type}-form button" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );


		$this->start_controls_tabs( "tabs_{$button_type}_btn_style" );
		/*-----Login Button NORMAL state------ */
		$this->start_controls_tab( "tab_{$button_type}_btn_normal", [
			'label' => __( 'Normal', EAEL_TEXTDOMAIN ),
		] );
		$this->add_control( "{$button_type}_btn_color", [
			'label'     => __( 'Text Color', EAEL_TEXTDOMAIN ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .eael-{$button_type}-form button" => 'color: {{VALUE}};',
			],
		] );
		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => "{$button_type}_btn_bg_color",
			'label'    => __( 'Background Color', EAEL_TEXTDOMAIN ),
			'types'    => [
				'classic',
				'gradient',
			],
			'selector' => "{{WRAPPER}} .eael-{$button_type}-form button",
		] );
		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => "{$button_type}_btn_border",
			'selector' => "{{WRAPPER}} .eael-{$button_type}-form button",
		] );
		$this->add_control( "{$button_type}_btn_border_radius", [
			'label'      => __( 'Border Radius', EAEL_TEXTDOMAIN ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-{$button_type}-form button" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );
		$this->end_controls_tab();

		/*-----Login Button HOVER state------ */
		$this->start_controls_tab( "tab_{$button_type}_button_hover", [
			'label' => __( 'Hover', EAEL_TEXTDOMAIN ),
		] );
		$this->add_control( "{$button_type}_button_color_hover", [
			'label'     => __( 'Text Color', EAEL_TEXTDOMAIN ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .eael-{$button_type}-form button:hover" => 'color: {{VALUE}};',
			],
		] );
		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => "{$button_type}_btn_bg_color_hover",
			'label'    => __( 'Background Color', EAEL_TEXTDOMAIN ),
			'types'    => [
				'classic',
				'gradient',
			],
			'selector' => "{{WRAPPER}} .eael-{$button_type}-form button",
		] );
		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => "{$button_type}_btn_border_hover",
			'selector' => "{{WRAPPER}} .eael-{$button_type}-form button:hover",
		] );
		$this->add_control( "{$button_type}_btn_border_radius_hover", [
			'label'      => __( 'Border Radius', EAEL_TEXTDOMAIN ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-{$button_type}-form:hover button" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );
		$this->add_control( "{$button_type}_btn_hover_animation", [
			'label' => __( 'Animation', EAEL_TEXTDOMAIN ),
			'type'  => Controls_Manager::HOVER_ANIMATION,
		] );
		$this->end_controls_tab();
		$this->end_controls_tabs();
		/*-----ends button tabs--------*/


		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => "{$button_type}_btn_typography",
			'selector' => "{{WRAPPER}} .eael-{$button_type}-form button",
		] );
		$this->add_responsive_control( "{$button_type}_btn_align", [
			'label'        => __( 'Alignment', EAEL_TEXTDOMAIN ),
			'type'         => Controls_Manager::CHOOSE,
			'options'      => [
				'start'   => [
					'title' => __( 'Left', EAEL_TEXTDOMAIN ),
					'icon'  => 'eicon-text-align-left',
				],
				'center'  => [
					'title' => __( 'Center', EAEL_TEXTDOMAIN ),
					'icon'  => 'eicon-text-align-center',
				],
				'end'     => [
					'title' => __( 'Right', EAEL_TEXTDOMAIN ),
					'icon'  => 'eicon-text-align-right',
				],
				'stretch' => [
					'title' => __( 'Justified', EAEL_TEXTDOMAIN ),
					'icon'  => 'eicon-text-align-justify',
				],
			],
			'prefix_class' => 'elementor%s-button-align-',
			'default'      => '',
			'separator'    => 'before',
		] );

		$this->add_responsive_control( "{$button_type}_btn_width", [
			'label'      => esc_html__( 'Button width', EAEL_TEXTDOMAIN ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'%',
			],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 500,
					'step' => 5,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-{$button_type}-form button" => 'width: {{SIZE}}{{UNIT}};',
			],
		] );
		$this->add_responsive_control( "{$button_type}_btn_height", [
			'label'      => esc_html__( 'Button Height', EAEL_TEXTDOMAIN ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'%',
			],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 500,
					'step' => 5,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-{$button_type}-form button" => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	/**
	 * Get conditions for displaying login form related controls
	 * @return array
	 */
	protected function get_login_controls_display_condition() {
		return [
			'relation' => 'or',
			'terms'    => [
				[
					'name'  => 'show_login_link',
					'value' => 'yes',
				],
				[
					'name'  => 'default_form_type',
					'value' => 'login',
				],
			],
		];
	}

	/**
	 * Get conditions for displaying login form footer related controls
	 * @return array
	 */
	protected function get_login_footer_controls_display_condition() {
		return [
			[
				'name'     => 'show_lost_password',
				'operator' => '==',
				'value'    => 'yes',
			],
			[
				'name'     => 'show_registration_link',
				'operator' => '==',
				'value'    => 'yes',
			],
			[
				'name'     => 'show_login_link',
				'operator' => '==',
				'value'    => 'yes',
			],
		];
	}

	/**
	 * Get conditions for displaying registration form related controls
	 * @return array
	 */
	protected function get_register_controls_display_condition() {
		return [
			'relation' => 'or',
			'terms'    => [
				[
					'name'  => 'show_registration_link',
					'value' => 'yes',
				],
				[
					'name'  => 'default_form_type',
					'value' => 'registration',
				],
			],
		];
	}

	protected function render() {
		//Note. forms are handled in Login_Registration Trait used in the Bootstrap class.
		$this->d_settings = $this->get_settings_for_display();
		//error_log( print_r( $this->d_settings, 1));
		$this->should_print_login_form = ( 'login' === $this->get_settings_for_display( 'default_form_type' ) || 'yes' === $this->get_settings_for_display( 'show_login_link' ) );

		$this->should_print_register_form = ( $this->user_can_register && ( 'registration' === $this->get_settings_for_display( 'default_form_type' ) || 'yes' === $this->get_settings_for_display( 'show_registration_link' ) ) );
		if ( Plugin::$instance->documents->get_current() ) {
			$this->page_id = Plugin::$instance->documents->get_current()->get_main_id();
		}
		?>
        <div class="eael-login-registration-wrapper">
			<?php
			$this->print_login_form();
			$this->print_register_form();
			?>
        </div>
		<?php
	}

	protected function print_login_form() {
		if ( $this->should_print_login_form ) {

			?>
            <div class="eael-login-form-wrapper eael-lr-form-wrapper style-2">
                <div class="lr-form-illustration" style="background-image: url('https://images.pexels.com/photos/3280211/pexels-photo-3280211.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260');">
                </div>

                <form class="eael-login-form eael-lr-form" id="eael-login-form" method="post">
	                <?php
	                // add login security nonce
	                wp_nonce_field( 'eael-login-action', 'eael-login-nonce' );
	                ?>

                    <div class="eael-lr-form-group">
                        <label for="eael-user-login">Username or Email Address</label>
                        <input type="text" name="eael-user-login" id="eael-user-login" class="eael-lr-form-control"
                               aria-describedby="emailHelp" placeholder="email@domain.com">
                    </div>
                    <div class="eael-lr-form-group">
                        <label for="eael-user-password">Password</label>
                        <div class="eael-lr-password-wrapper">
                            <input type="password" class="eael-lr-form-control" id=""
                                   placeholder="Password">
                            <button type="button" class="wp-hide-pw hide-if-no-js" aria-label="Show password">
                                <span class="dashicons dashicons-visibility" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                    <div class="eael-forever-forget eael-lr-form-group">
                        <p class="forget-menot">
                            <input name="rememberme" type="checkbox" id="rememberme" value="forever">
                            <label for="rememberme">Remember Me</label>
                        </p>
                        <p class="forget-pass">
                            <a href="#">Forgot password?</a>
                        </p>
                    </div>
                    <input type="submit" class="eael-lr-btn eael-lr-btn-block" value="Sign in" />
                    <div class="eael-sign-up">
                        Don't have an account? <a href="#">Register Now</a>
                    </div>
                    <input type="hidden" name="redirect_to" value="/wp-admin/">
                    <input type="hidden" name="testcookie" value="1">
                    <input type="hidden" name="page_id" value="<?php echo esc_attr( $this->page_id ); ?>">
                    <input type="hidden" name="widget_id" value="<?php echo esc_attr( $this->get_id() ); ?>">
                    <p>
		                <?php $this->print_login_validation_errors(); ?>
                    </p>
                </form>
            </div>
			<?php
		}
	}

	protected function print_register_form() {
		if ( $this->should_print_register_form ) {
			$page_id           = '';
			$is_pass_valid     = false; // Does the form has a password field?
			$is_pass_confirmed = false;
			// placeholders to flag if user use one type of field more than once.
			$email_exists        = 0;
			$user_name_exists    = 0;
			$password_exists     = 0;
			$confirm_pass_exists = 0;
			$first_name_exists   = 0;
			$last_name_exists    = 0;
			$website_exists      = 0;
			$f_labels            = [
				'email'        => 'Email',
				'password'     => 'Password',
				'confirm_pass' => 'Confirm Password',
				'user_name'    => 'Username',
				'first_name'   => 'First Name',
				'last_name'    => 'Last Name',
				'website'      => 'Website',
			];
			$repeated_f_labels   = [];
			if ( Plugin::$instance->documents->get_current() ) {
				$page_id = Plugin::$instance->documents->get_current()->get_main_id();
			}
			ob_start();
			?>
            <div class="eael-register-form-wrapper eael-lr-form-wrapper">
                <form name="eael-register-form eael-lr-form" id="eael-register-form" method="post">
					<?php wp_nonce_field( 'eael-register-action', 'eael-register-nonce' );

					// Print all dynamic fields
					foreach ( $this->d_settings['register_fields'] as $f_index => $field ) :
						$field_type = $field['field_type'];
						$dynamic_field_name = "{$field_type}_exists";
						$$dynamic_field_name ++; //NOTE, double $$ intentional. Dynamically update the var check eg. $username_exists++ to prevent user from using the same field twice
						// is same field repeated?
						if ( $$dynamic_field_name > 1 ) {
							$repeated_f_labels[] = $f_labels[ $field_type ];
						}
						if ( 'password' === $field_type ) {
							$is_pass_valid = true;
						}

						//keys for attribute binding
						$input_key       = "input{$f_index}";
						$label_key       = "label{$f_index}";
						$field_group_key = "field-group{$f_index}";

						// determine proper input tag type
						switch ( $field_type ) {
							case 'user_name':
							case 'first_name':
							case 'last_name':
								$field_input_type = 'text';
								$this->add_render_attribute( $input_key, 'class', 'elementor-field-textual' );
								break;
							case 'confirm_pass':
								$field_input_type = 'password';
								break;
							case 'website':
								$field_input_type = 'url';
								break;
							default:
								$field_input_type = $field_type;
						}

						$this->add_render_attribute( [
							$input_key => [
								'name'        => $field_type,
								'type'        => $field_input_type,
								'placeholder' => $field['placeholder'],
								'class'       => [
									'eael-lr-form-control',
									'form-field-' . $field_type,
								],
							],
							$label_key => [
								'for'   => 'form-field-' . $field_type,
								'class' => 'eael-field-label',
							],
						] );


						// print require field attributes
						$rf_class = '';
						if ( ! empty( $field['required'] ) || in_array( $field_type, [
								'password',
								'confirm_pass',
								'email',
							] ) ) {
							$this->add_render_attribute( $input_key, [
								'required'      => 'required',
								'aria-required' => 'true',
							] );

							$rf_class = "elementor-field-required";
							if ( 'yes' === $this->d_settings['mark_required'] ) {
								$rf_class = ' elementor-mark-required';
							}
						}


						// add css classes to the main input field wrapper. @TODO; we can update class name according to frontend dev later here.
						$this->add_render_attribute( [
							$field_group_key => [
								'class' => [
									'eael-lr-form-group',
									'elementor-field-type-' . $field_type,
									'elementor-col-' . $field['width'],
									$rf_class,
								],
							],
						] );

						if ( ! empty( $field['width_tablet'] ) ) {
							$this->add_render_attribute( $field_group_key, 'class', 'elementor-md-' . $field['width_tablet'] );
						}

						if ( ! empty( $field['width_mobile'] ) ) {
							$this->add_render_attribute( $field_group_key, 'class', 'elementor-sm-' . $field['width_mobile'] );
						}

						?>
                        <div <?php $this->print_render_attribute_string( $field_group_key ) ?>>
							<?php
							if ( 'yes' === $this->d_settings['show_labels'] && ! empty( $field['field_label'] ) ) {
								echo '<label ' . $this->get_render_attribute_string( $label_key ) . '>' . esc_attr( $field['field_label'] ) . '</label>';
							}
							echo '<input ' . $this->get_render_attribute_string( $input_key ) . '>';
							?>
                        </div>
					<?php
					endforeach;
					?>

                    <input type="hidden" name="page_id" value="<?php echo esc_attr( $page_id ); ?>">
                    <input type="hidden" name="widget_id" value="<?php echo esc_attr( $this->get_id() ); ?>">
                    <p class="submit">
                        <input type="submit" name="eael-register-submit" id="eael-register-submit" class="eael-lr-btn eael-lr-btn-block" value="Register" required>
                    </p>
                </form>

				<?php
				$this->print_registration_validation_errors();
				?>
            </div>
			<?php
			$form_markup = ob_get_clean();
			// if we are in the editor then show error related to repeater field.
			if ( $this->in_editor ) {
				$repeated            = $this->print_error_for_repeated_fields( $repeated_f_labels );
				$email_field_missing = $this->print_error_for_missing_email_field( $email_exists );
				if ( $repeated || $email_field_missing ) {
					return false; // error found, exit, dont show form.
				}
				echo $form_markup; //XSS OK, data sanitized already.
			} else {
				echo $form_markup; //XSS OK, data sanitized already.
			}
		}
	}

	protected function print_login_validation_errors() {
		if ( $login_error = get_transient( 'eael_login_error' ) ) { ?>
            <p class="eael-input-error">
				<?php echo esc_html( $login_error ); ?>
            </p>
			<?php
			delete_transient( 'eael_login_error' );
		}

	}

	protected function print_error_for_repeated_fields( $repeated_fields ) {
		if ( ! empty( $repeated_fields ) ) {
			$error_fields = '<strong>' . implode( "</strong>, <strong>", $repeated_fields ) . '</strong>';
			?>
            <p class='eael-register-form-error elementor-alert elementor-alert-warning'>
				<?php
				/* translators: %s: Error fields */
				printf( __( 'Error! you seem to have added %s field in the form more than once.', EAEL_TEXTDOMAIN ), $error_fields );
				?>
            </p>
			<?php
			return true;
		}

		return false;
	}

	protected function print_error_for_missing_email_field( $email_exist ) {
		if ( empty( $email_exist ) ) {
			?>
            <p class='eael-register-form-error elementor-alert elementor-alert-warning'>
				<?php
				/* translators: %s: Error String */
				printf( __( 'Error! It is required to use %s field.', EAEL_TEXTDOMAIN ), '<strong>Email</strong>' );
				?>
            </p>
			<?php
			return true;
		}

		return false;
	}

	protected function print_registration_validation_errors() {
		$errors = get_transient( 'eael_register_errors' );
		if ( ! empty( $errors ) && is_array( $errors ) ) { ?>
            <div class="eael-registration-errors-container">
                <ul class="eael-registration-errors errors">
					<?php
					foreach ( $errors as $register_error ) {
						echo '<li class="error-message">' . esc_html( $register_error ) . '</li>';
					}
					?>
                </ul>
            </div>
			<?php
			delete_transient( 'eael_register_errors' );

			return true; // it will help in case we wanna if error is printed.
		}

		return false;
	}


}