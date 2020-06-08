<?php

namespace Essential_Addons_Elementor\Elements;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
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
	 * It contains the message if user provides invalid username or email for login
	 * @var string|void
	 */
	protected $invalid_login;
	/**
	 * It contains the message if user provides invalid password for login
	 * @var string|void
	 */
	protected $invalid_password;

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


		/*----Style Tab----*/


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
			'label'        => __( 'Hide from Logged in Users', EAEL_TEXTDOMAIN ),
			'description'  => __( 'You can hide the form for already logged in user.', EAEL_TEXTDOMAIN ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', EAEL_TEXTDOMAIN ),
			'label_off'    => __( 'No', EAEL_TEXTDOMAIN ),
			'return_value' => 'yes',
		] );

		$this->add_control( 'show_login_link', [
			'label'       => __( 'Login Link', EAEL_TEXTDOMAIN ),
			'description' => __( 'You can add a "Login" Link below the registration form', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::SWITCHER,
			'default'     => 'yes',
			'label_off'   => __( 'Hide', EAEL_TEXTDOMAIN ),
			'label_on'    => __( 'Show', EAEL_TEXTDOMAIN ),
			'condition'   => [
				'default_form_type' => 'registration',
			],
		] );

		$this->add_control( 'login_link_action', [
			'label'     => __( 'Login Link Action', EAEL_TEXTDOMAIN ),
			'description'     => __( 'Select what should happen when the login link is clicked', EAEL_TEXTDOMAIN ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'default' => __( 'Default WordPress Page', EAEL_TEXTDOMAIN ),
				'custom'  => __( 'Custom URL', EAEL_TEXTDOMAIN ),
				'form'  => __( 'Show Login Form', EAEL_TEXTDOMAIN ),
			],
			'default'   => 'default',
			'condition' => [
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
				'show_login_link'        => 'yes',
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
				'label'       => __( 'Register Link', EAEL_TEXTDOMAIN ),
				'description' => __( 'You can add a "Register" Link below the login form', EAEL_TEXTDOMAIN ),

				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_off' => __( 'Hide', EAEL_TEXTDOMAIN ),
				'label_on'  => __( 'Show', EAEL_TEXTDOMAIN ),
				'condition' => [
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
					'default_form_type'        => 'login',
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
					'default_form_type'        => 'login',
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
					'show_login_link'        => 'yes',
				],
			] );
		}


		$this->add_control( 'show_log_out_message', [
			'label'       => __( 'Show Logout Link', EAEL_TEXTDOMAIN ),
			'description' => __( 'This option will show a message with logout link instead of a login form for the logged in user', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::SWITCHER,
			'default'     => 'yes',
			'label_off'   => __( 'Hide', EAEL_TEXTDOMAIN ),
			'label_on'    => __( 'Show', EAEL_TEXTDOMAIN ),
			'condition'   => [
				'default_form_type' => 'login',
			],
		] );


		$this->add_control( 'show_lost_password', [
			'label'       => __( 'Show Lost your password?', EAEL_TEXTDOMAIN ),
			'description' => __( 'You can add a "Forgot Password" Link below the the form', EAEL_TEXTDOMAIN ),

			'type'      => Controls_Manager::SWITCHER,
			'default'   => 'yes',
			'label_off' => __( 'No', EAEL_TEXTDOMAIN ),
			'label_on'  => __( 'Yes', EAEL_TEXTDOMAIN ),
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

		$this->add_responsive_control( 'login_align', [
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
		] );

		$this->add_responsive_control( 'login_button_width', [
			'label'      => esc_html__( 'Login Button width', EAEL_TEXTDOMAIN ),
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

			'selectors' => [
				'{{WRAPPER}} .eael-login-form button' => 'width: {{SIZE}}{{UNIT}};',
			],
			'separator' => 'before',
		] );

		$this->end_controls_section();
	}

	protected function init_content_login_options_controls() {

		$this->start_controls_section( 'section_content_login_actions', [
			'label'      => __( 'Login Form Actions', EAEL_TEXTDOMAIN ),
			'conditions' => $this->get_login_controls_display_condition(),
		] );

		$this->add_control( 'redirect_after_login', [
			'label'     => __( 'Redirect After Login', EAEL_TEXTDOMAIN ),
			'type'      => Controls_Manager::SWITCHER,
			'default'   => '',
			'label_off' => __( 'Off', EAEL_TEXTDOMAIN ),
			'label_on'  => __( 'On', EAEL_TEXTDOMAIN ),
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
			'label'     => __( 'Redirect After Logout', EAEL_TEXTDOMAIN ),
			'type'      => Controls_Manager::SWITCHER,
			'default'   => '',
			'label_off' => __( 'Off', EAEL_TEXTDOMAIN ),
			'label_on'  => __( 'On', EAEL_TEXTDOMAIN ),
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
			'label'        => __( 'Required', EAEL_TEXTDOMAIN ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
			'condition'    => [
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


		$this->end_controls_section();
	}

	protected function init_content_register_options_controls() {

		$this->start_controls_section( 'section_content_register_actions', [
			'label'      => __( 'Register Form Options', EAEL_TEXTDOMAIN ),
			'conditions' => $this->get_register_controls_display_condition(),
		] );

		$this->add_control( 'register_action', [
			'label'     => __( 'Register Actions', EAEL_TEXTDOMAIN ),
			'description'     => __( 'You can select what should happen after a user registers successfully', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::SELECT2,
			'multiple'    => true,
			'label_block' => true,
			'default'     => 'send_email',
			'options'     => array(
				'redirect'   => __( 'Redirect', EAEL_TEXTDOMAIN ),
				'auto_login' => __( 'Auto Login', EAEL_TEXTDOMAIN ),
				'send_email' => __( 'Send Email', EAEL_TEXTDOMAIN ),
			),
		] );

		$this->add_control( 'register_redirect_url', [
			'type'          => Controls_Manager::URL,
			'show_label'    => false,
			'show_external' => false,
			'placeholder'   => __( 'eg. https://your-link.com/wp-admin/', EAEL_TEXTDOMAIN ),
			'description'   => __( 'Please note that only your current domain is allowed here to keep your site secure.', EAEL_TEXTDOMAIN ),
			'default' => [
				'url' => get_admin_url(),
				'is_external' => false,
				'nofollow' => true,
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
		$settings = $this->get_settings_for_display();

		$this->should_print_login_form = ( 'login' === $this->get_settings_for_display( 'default_form_type' ) || 'yes' === $this->get_settings_for_display( 'show_login_link' ) );

		$this->should_print_register_form = ( $this->user_can_register && ( 'registration' === $this->get_settings_for_display( 'default_form_type' ) || 'yes' === $this->get_settings_for_display( 'show_registration_link' ) ) );

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
			$this->check_login_validation_errors();
			?>
            <div class="eaal-login-form-wrapper ">
                <form name="eael-loginform" id="eael-loginform" method="post">
					<?php
					// add login security nonce
					wp_nonce_field( 'eael-login-action', 'eael-login-nonce' );
					?>
                    <p>
                        <label for="eael-user-login">Username or Email Address</label>
                        <input type="text" name="eael-user-login" id="eael-user-login" class="input" value="" size="20" autocapitalize="off" autocomplete="off" placeholder="Username or Email Address" required>
						<?php if ( $this->invalid_login ) { ?>
                            <span class="eael-input-error">
			                <?php echo esc_html( $this->invalid_login ); ?>
                            </span>
						<?php } ?>
                    </p>

                    <div class="user-pass-wrap">
                        <label for="eael-user-password">Password</label>
                        <div class="wp-pwd">
                            <input type="password" name="eael-user-password" id="eael-user-password" class="input password-input" value="" size="20" autocomplete="off" placeholder="Password" required>
                            <button type="button" class="button button-secondary wp-hide-pw hide-if-no-js" aria-label="Show password">
                        <span class="dashicons dashicons-visibility"
                              aria-hidden="true"></span>
                                <!-- will be used to toggle visibility -->
                                <!-- <span class="dashicons dashicons-hidden"
									aria-hidden="true"></span> -->
                            </button>
							<?php if ( $this->invalid_password ) { ?>
                                <span class="eael-input-error">
                                    <?php echo esc_html( $this->invalid_password ); ?>
                                </span>
							<?php } ?>
                        </div>
                    </div>

                    <p class="forgetmenot">
                        <input name="eael-rememberme" type="checkbox" id="eael-rememberme" value="forever">
                        <label for="eael-rememberme">Remember Me</label>
                    </p>
                    <p class="submit">
                        <input type="submit" name="eael-login-submit" id="eael-login-submit" class="button button-primary button-large" value="Log In">
                        <input type="hidden" name="redirect_to" value="/wp-admin/">
                        <input type="hidden" name="testcookie" value="1">
                    </p>
                </form>
            </div>
			<?php
		}
	}

	protected function print_register_form() {
		if ( $this->should_print_register_form ) { ?>
            <div class="eael-register-form-wrapper">
                <form name="eael-registerform" id="eael-registerform" method="post">
					<?php
					// add security nonce
					wp_nonce_field( 'eael-register-action', 'eael-register-nonce' );
					?>
                    <p>
                        <label for="user_login">Username</label>
                        <input type="text" name="user_login" id="user_login" class="input" value="" size="20" autocapitalize="off" required>
                    </p>
                    <p>
                        <label for="user_email">Email</label>
                        <input type="email" name="user_email" id="user_email" class="input" value="" size="25" required>
                    </p>
                    <p id="reg_passmail">
                        Registration confirmation will be emailed to you. </p>
                    <br class="clear">
                    <input type="hidden" name="redirect_to" value="">
                    <p class="submit">
                        <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="Register" required>
                    </p>
                </form>
            </div>
			<?php
		}
	}

	protected function check_login_validation_errors() {
		// handle error message.
		if ( session_id() && isset( $_SESSION['eael_login_error'] ) ) {

			$login_error = isset( $_SESSION['eael_login_error'] ) ? $_SESSION['eael_login_error'] : '';

			if ( $login_error ) {
				switch ( $login_error ) {
					//@TODO; in future, maybe let site-owner customize error messages.
					case 'invalid_username':
						$this->invalid_login = __( 'Invalid Username. Please check your username or try again with your email.', EAEL_TEXTDOMAIN );
						break;
					case 'invalid_email':
						$this->invalid_login = __( 'Invalid Email. Please check your email or try again with your username.', EAEL_TEXTDOMAIN );
						break;
					case 'incorrect_password':
						$this->invalid_password = __( 'Invalid Password. Please check your password and try again', EAEL_TEXTDOMAIN );
						break;

				}
				unset( $_SESSION['eael_login_error'] );
			}

		}
	}


}