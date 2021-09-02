<?php

namespace Essential_Addons_Elementor\Elements;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use Essential_Addons_Elementor\Classes\Helper as HelperCLass;
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
	protected $ds;
	/**
	 * @var bool|false|int
	 */
	protected $page_id;
	/**
	 * @var bool|string
	 */
	protected $form_illustration_url;
	/**
	 * @var bool|string
	 */
	protected $form_logo;
	/**
	 * What form to show by default on initial page load. login or register ?
	 * @var string
	 */
	protected $default_form;
	/**
	 * Form illustration position
	 * @var mixed|string
	 */
	protected $form_illustration_pos;
	/**
	 * Form logo position
	 * @var mixed|string
	 */
	protected $form_logo_pos;
	/**
	 * Google reCAPTCHA Site key
	 * @var string|false
	 */
	protected $recaptcha_sitekey;
	/**
	 * @var mixed|void
	 */
	protected $pro_enabled;

	/**
	 * Login_Register constructor.
	 * Initializing the Login_Register widget class.
	 * @inheritDoc
	 */
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		$this->user_can_register = get_option( 'users_can_register' );
		$this->recaptcha_sitekey = get_option( 'eael_recaptcha_sitekey' );
		$this->in_editor         = Plugin::instance()->editor->is_edit_mode();
		$this->pro_enabled       = apply_filters( 'eael/pro_enabled', false );

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
		return esc_html__( 'Login | Register Form', 'essential-addons-for-elementor-lite' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_icon() {
		return 'eaicon-login';
	}

	public function get_script_depends() {
		$scripts   = parent::get_script_depends();
		$scripts[] = 'eael-recaptcha';

		return apply_filters( 'eael/login-register/scripts', $scripts );
	}

	/**
	 * @inheritDoc
	 */
	public function get_style_depends() {
		$styles   = parent::get_style_depends();
		$styles[] = 'dashicons';

		return apply_filters( 'eael/login-register/styles', $styles );
	}

	/**
	 * @inheritDoc
	 */
	public function get_keywords() {
		return [
			'ea login register',
			'ea register login',
			'signin form',
			'signup form',
			'sign in form',
			'sign up form',
			'authentication',
			'google',
			'facebook',
			'ea',
			'essential addons',
		];
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/login-register-form/';
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
			'user_name'    => __( 'Username', 'essential-addons-for-elementor-lite' ),
			'email'        => __( 'Email', 'essential-addons-for-elementor-lite' ),
			'password'     => __( 'Password', 'essential-addons-for-elementor-lite' ),
			'confirm_pass' => __( 'Confirm Password', 'essential-addons-for-elementor-lite' ),
			'first_name'   => __( 'First Name', 'essential-addons-for-elementor-lite' ),
			'last_name'    => __( 'Last Name', 'essential-addons-for-elementor-lite' ),
			'website'      => __( 'Website', 'essential-addons-for-elementor-lite' ),
		] );
	}

	/**
	 * @inheritDoc
	 */
	protected function _register_controls() {
		/*----Content Tab----*/
		do_action( 'eael/login-register/before-content-controls', $this );
		$this->init_content_general_controls();
		$this->init_form_header_controls();
		// Login Form Related---
		$this->init_content_login_fields_controls();
		$this->init_content_login_options_controls();

		if(!$this->pro_enabled){
			$this->social_login_promo();
        }

		do_action( 'eael/login-register/after-login-controls-section', $this );
		// Registration For Related---
		$this->init_content_register_fields_controls();
		$this->init_content_register_options_controls();
		$this->init_content_register_user_email_controls();
		$this->init_content_register_admin_email_controls();
		//Terms & Conditions
		$this->init_content_terms_controls();
		// Error Messages
		$this->init_content_validation_messages_controls();
		do_action( 'eael/login-register/after-content-controls', $this );

		if(!$this->pro_enabled){
			$this->show_pro_promotion();
		}

		/*----Style Tab----*/
		do_action( 'eael/login-register/before-style-controls', $this );
		$this->init_style_general_controls();
		$this->init_style_header_content_controls( 'login' );
		$this->init_style_header_content_controls( 'register' );
		$this->init_style_input_fields_controls();
		$this->init_style_input_labels_controls();
		$this->init_style_login_button_controls();
		$this->init_style_register_button_controls();
		$this->init_style_login_link_controls();
		$this->init_style_register_link_controls();
		$this->init_style_login_recaptcha_controls();
		$this->init_style_register_recaptcha_controls();
		do_action( 'eael/login-register/after-style-controls', $this );

	}

	/**
	 * It adds controls related to Login Form Types section to the Widget Content Tab
	 */
	protected function init_content_general_controls() {
		$this->start_controls_section( 'section_content_general', [
			'label' => __( 'General', 'essential-addons-for-elementor-lite' ),
		] );
		$this->add_control( 'default_form_type_notice', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => __( 'Choose the type of form you want to show by default. Note: you can show both forms in a single page even if you select only login or registration from below.', 'essential-addons-for-elementor-lite' ),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
		] );
		$this->add_control( 'default_form_type', [
			'label'   => __( 'Default Form Type', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'login'    => __( 'Login', 'essential-addons-for-elementor-lite' ),
				'register' => __( 'Registration', 'essential-addons-for-elementor-lite' ),
			],
			'default' => 'login',
		] );
		if ( ! $this->user_can_register ) {
			$this->add_control( 'registration_off_notice', [
				'type'            => Controls_Manager::RAW_HTML,
				/* translators: %1$s is settings page link open tag, %2$s is link closing tag */
				'raw'             => sprintf( __( 'Registration is disabled on your site. Please enable it to use registration form. You can enable it from Dashboard » Settings » General » %1$sMembership%2$s.', 'essential-addons-for-elementor-lite' ), '<a href="' . esc_attr( esc_url( admin_url( 'options-general.php' ) ) ) . '" target="_blank">', '</a>' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'condition'       => [
					'default_form_type' => 'register',
				],
			] );
		}
		$this->add_control( 'hide_for_logged_in_user', [
			'label'   => __( 'Hide all Forms from Logged-in Users', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );
		$this->add_control( 'redirect_for_logged_in_user', [
			'label'   => __( 'Redirect for Logged-in Users', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'no',
		] );
		$this->add_control( 'redirect_url_for_logged_in_user', [
			'type'          => Controls_Manager::URL,
			'show_label'    => false,
			'show_external' => false,
			'placeholder'   => site_url(),
			'description'   => __( 'Please note that only your current domain is allowed here to keep your site secure.', 'essential-addons-for-elementor-lite' ),
			'condition'     => [
				'redirect_for_logged_in_user' => 'yes',
			],
			'default'       => [
				'url'         => site_url(),
				'is_external' => false,
				'nofollow'    => true,
			],
		] );
		$this->add_control( 'gen_lgn_content_po_toggle', [
			'label'        => __( 'Login Form General', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Controls', 'essential-addons-for-elementor-lite' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',
			'default'      => 'yes',
		] );
		$this->start_popover();
		$this->add_control( 'show_log_out_message', [
			'label'   => __( 'Show Logout Link', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );
		$this->add_control( 'show_lost_password', [
			'label'   => __( 'Show Lost your password?', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'lost_password_text', [
			'label'       => __( 'Lost Password Text', 'essential-addons-for-elementor-lite' ),
			'label_block' => true,
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => __( 'Forgot password?', 'essential-addons-for-elementor-lite' ),
			'condition'   => [
				'show_lost_password' => 'yes',
			],
		] );
		$this->add_control( 'lost_password_link_type', [
			'label'       => __( 'Lost Password Link to', 'essential-addons-for-elementor-lite' ),
			'label_block' => true,
			'type'        => Controls_Manager::SELECT,
			'options'     => [
				'default' => __( 'Default WordPress Page', 'essential-addons-for-elementor-lite' ),
				'custom'  => __( 'Custom URL', 'essential-addons-for-elementor-lite' ),
			],
			'default'     => 'default',
			'condition'   => [
				'show_lost_password' => 'yes',
			],
		] );
		$this->add_control( 'lost_password_url', [
			'label'         => __( 'Custom Lost Password URL', 'essential-addons-for-elementor-lite' ),
			'label_block'   => true,
			'type'          => Controls_Manager::URL,
			'show_external' => false,
			'dynamic'       => [
				'active' => true,
			],
			'condition'     => [
				'lost_password_link_type' => 'custom',
				'show_lost_password'      => 'yes',
			],
		] );
		$this->add_control( 'login_show_remember_me', [
			'label'     => __( 'Remember Me Field', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SWITCHER,
			'default'   => 'yes',
			'label_off' => __( 'Hide', 'essential-addons-for-elementor-lite' ),
			'label_on'  => __( 'Show', 'essential-addons-for-elementor-lite' ),
		] );
		$this->add_control( 'remember_text', [
			'label'       => __( 'Remember Me Field Text', 'essential-addons-for-elementor-lite' ),
			'label_block' => true,
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => __( 'Remember Me', 'essential-addons-for-elementor-lite' ),
			'condition'   => [
				'login_show_remember_me' => 'yes',
			],
		] );
		if ( $this->user_can_register ) {
			$this->add_control( 'reg_hr', [
				'type' => Controls_Manager::DIVIDER,
			] );
			$this->add_control( 'show_register_link', [
				'label'     => __( 'Show Register Link', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',

			] );
			$this->add_control( 'registration_link_text', [
				'label'       => __( 'Register Link Text', 'essential-addons-for-elementor-lite' ),
				'label_block' => true,
				'description' => __( 'You can put text in two lines to make the last line linkable. Pro Tip: You can keep the first line empty and put the text only in the second line to get a link only.', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => __( " \nRegister Now", 'essential-addons-for-elementor-lite' ),
				'condition'   => [
					'show_register_link' => 'yes',
				],
			] );
			$this->add_control( 'registration_link_action', [
				'label'       => __( 'Registration Link Action', 'essential-addons-for-elementor-lite' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'default' => __( 'WordPress Registration Page', 'essential-addons-for-elementor-lite' ),
					'custom'  => __( 'Custom URL', 'essential-addons-for-elementor-lite' ),
					'form'    => __( 'Show Register Form', 'essential-addons-for-elementor-lite' ),
				],
				'default'     => 'form',
				'condition'   => [
					'show_register_link' => 'yes',
				],
			] );
			$this->add_control( 'custom_register_url', [
				'label'         => __( 'Custom Register URL', 'essential-addons-for-elementor-lite' ),
				'label_block'   => true,
				'type'          => Controls_Manager::URL,
				'show_external' => false,
				'dynamic'       => [
					'active' => true,
				],
				'condition'     => [
					'registration_link_action' => 'custom',
					'show_register_link'       => 'yes',
				],
			] );
		} else {
			$this->add_control( 'show_register_link', [
				'label'     => __( 'Show Register Link', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => 'no',
				'separator' => 'before',
			] );
		}
		$this->add_control( 'enable_login_recaptcha', [
			'label'        => __( 'Enable Google reCAPTCHA', 'essential-addons-for-elementor-lite' ),
			'description'  => __( 'reCAPTCHA will prevent spam login from bots.', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
			'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',
		] );
		if ( empty( $this->recaptcha_sitekey ) ) {
			$this->add_control( 'eael_login_recaptcha_keys_missing', [
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf( __( 'reCAPTCHA API keys are missing. Please add them from %sDashboard >> Essential Addons >> Elements >> Login | Register Form %sSettings', 'essential-addons-for-elementor-lite' ), '<strong>', '</strong>' ),
				'content_classes' => 'eael-warning',
				'condition'       => [
					'enable_login_recaptcha' => 'yes',
				],
			] );
		}
		$this->end_popover();


		/*--show registration related control only if registration is enable on the site--*/
		if ( $this->user_can_register ) {
			$this->add_control( 'gen_reg_content_po_toggle', [
				'label'        => __( 'Register Form General', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'essential-addons-for-elementor-lite' ),
				'label_on'     => __( 'Custom', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			] );
			$this->start_popover();
			$this->add_control( 'show_login_link', [
				'label'   => __( 'Show Login Link', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			] );
			$this->add_control( 'login_link_text', [
				'label'       => __( 'Login Link Text', 'essential-addons-for-elementor-lite' ),
				'label_block' => true,
				'description' => __( 'You can put text in two lines to make the last line linkable. Pro Tip: You can keep the first line empty and put the text only in the second line to get a link only.', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => __( " \nSign In", 'essential-addons-for-elementor-lite' ),
				'condition'   => [
					'show_login_link' => 'yes',
				],
			] );
			$this->add_control( 'login_link_action', [
				'label'       => __( 'Login Link Action', 'essential-addons-for-elementor-lite' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'default' => __( 'Default WordPress Page', 'essential-addons-for-elementor-lite' ),
					'custom'  => __( 'Custom URL', 'essential-addons-for-elementor-lite' ),
					'form'    => __( 'Show Login Form', 'essential-addons-for-elementor-lite' ),
				],
				'default'     => 'form',
				'condition'   => [
					'show_login_link' => 'yes',
				],
			] );
			$this->add_control( 'custom_login_url', [
				'label'         => __( 'Custom Login URL', 'essential-addons-for-elementor-lite' ),
				'label_block'   => true,
				'show_external' => false,
				'type'          => Controls_Manager::URL,
				'dynamic'       => [
					'active' => true,
				],
				'condition'     => [
					'login_link_action' => 'custom',
					'show_login_link'   => 'yes',
				],
			] );
			$this->add_control( 'enable_register_recaptcha', [
				'label'        => __( 'Enable Google reCAPTCHA', 'essential-addons-for-elementor-lite' ),
				'description'  => __( 'reCAPTCHA will prevent spam registration from bots.', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
			] );
			if ( empty( $this->recaptcha_sitekey ) ) {
				$this->add_control( 'eael_recaptcha_keys_missing', [
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf( __( 'reCAPTCHA API keys are missing. Please add them from %sDashboard >> Essential Addons >> Elements >> Login | Register Form %sSettings', 'essential-addons-for-elementor-lite' ), '<strong>', '</strong>' ),
					'content_classes' => 'eael-warning',
					'condition'       => [
						'enable_register_recaptcha' => 'yes',
					],
				] );
			}
			$this->end_popover();

		} else {
			$this->add_control( 'show_login_link', [
				'label'   => __( 'Show Login Link', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::HIDDEN,
				'default' => 'no',
			] );
		}

		do_action( 'eael/login-register/after-general-controls', $this );

		if ( !$this->pro_enabled ) {
			$this->add_control( 'enable_ajax', [
				'label'   => sprintf( __( 'Submit Form via AJAX %s', 'essential-addons-for-elementor-lite' ), '<i class="eael-pro-labe eicon-pro-icon"></i>' ),
				'type'    => Controls_Manager::SWITCHER,
				'classes' => 'eael-pro-control',
			] );
		}

		$this->end_controls_section();
	}

	/**
	 * It adds controls related to Login Form Fields section to the Widget Content Tab
	 */
	protected function init_content_login_fields_controls() {
		$this->start_controls_section( 'section_content_login_fields', [
			'label'      => __( 'Login Form Fields', 'essential-addons-for-elementor-lite' ),
			'conditions' => $this->get_form_controls_display_condition( 'login' ),
		] );

		$this->add_control( 'login_label_types', [
			'label'   => __( 'Labels & Placeholders', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'default' => __( 'Default', 'essential-addons-for-elementor-lite' ),
				'custom'  => __( 'Custom', 'essential-addons-for-elementor-lite' ),
				'none'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
			],
			'default' => 'default',
		] );

		$this->add_control( 'login_labels_heading', [
			'label'     => __( 'Labels', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [ 'login_label_types' => 'custom', ],
		] );


		$this->add_control( 'login_user_label', [
			'label'       => __( 'Username Label', 'essential-addons-for-elementor-lite' ),
			'placeholder' => __( 'Username or Email Address', 'essential-addons-for-elementor-lite' ),
			'default'     => __( 'Username or Email Address', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'label_block' => true,
			'condition'   => [ 'login_label_types' => 'custom', ],
		] );

		$this->add_control( 'login_password_label', [
			'label'       => __( 'Password Label', 'essential-addons-for-elementor-lite' ),
			'placeholder' => __( 'Password', 'essential-addons-for-elementor-lite' ),
			'default'     => __( 'Password', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'label_block' => true,
			'condition'   => [ 'login_label_types' => 'custom', ],
		] );

		$this->add_control( 'login_placeholders_heading', [
			'label'     => esc_html__( 'Placeholders', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
			'condition' => [ 'login_label_types' => 'custom', ],
			'separator' => 'before',
		] );

		$this->add_control( 'login_user_placeholder', [
			'label'       => __( 'Username Placeholder', 'essential-addons-for-elementor-lite' ),
			'placeholder' => __( 'Username or Email Address', 'essential-addons-for-elementor-lite' ),
			'default'     => __( 'Username or Email Address', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'label_block' => true,
			'condition'   => [ 'login_label_types' => 'custom', ],
		] );

		$this->add_control( 'login_password_placeholder', [
			'label'       => __( 'Password Placeholder', 'essential-addons-for-elementor-lite' ),
			'placeholder' => __( 'Password', 'essential-addons-for-elementor-lite' ),
			'default'     => __( 'Password', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'label_block' => true,
			'condition'   => [ 'login_label_types' => 'custom', ],
		] );

		$this->add_responsive_control( 'login_field_width', [
			'label'      => esc_html__( 'Input Fields width', 'essential-addons-for-elementor-lite' ),
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
				'{{WRAPPER}} .eael-login-form input:not(.eael-lr-btn)' => 'width: {{SIZE}}{{UNIT}};',
			],
			'separator'  => 'before',
		] );

		$this->add_control( 'password_toggle', [
			'label'     => __( 'Password Visibility Icon', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SWITCHER,
			'label_off' => __( 'Hide', 'essential-addons-for-elementor-lite' ),
			'label_on'  => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'default'   => 'yes',
		] );
		do_action( 'eael/login-register/after-pass-visibility-controls', $this );


		/*--Login Fields Button--*/
		$this->add_control( 'login_button_heading', [
			'label'     => esc_html__( 'Login Button', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'login_button_text', [
			'label'       => __( 'Button Text', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'default'     => __( 'Log In', 'essential-addons-for-elementor-lite' ),
			'placeholder' => __( 'Log In', 'essential-addons-for-elementor-lite' ),
		] );

		$this->end_controls_section();
	}

	protected function init_form_header_controls() {
		$this->start_controls_section( 'section_content_lr_form_header', [
			'label' => __( 'Form Header Content', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control( 'lr_form_image', [
			'label'   => __( 'Form Header Image', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::MEDIA,
			'dynamic' => [
				'active' => true,
			],
			'default' => [
				'url' => Utils::get_placeholder_image_src(),
			],
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'      => 'lr_form_image',
			// Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
			'default'   => 'full',
			'separator' => 'none',
		] );

		$this->add_control( "lr_form_image_position", [
			'label'     => __( 'Header Image Position', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'left'  => [
					'title' => __( 'Left', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-arrow-left',
				],
				'right' => [
					'title' => __( 'Right', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-arrow-right',
				],
			],
			'default'   => 'left',
			'separator' => 'after',
		] );

		$this->add_control( 'lr_form_logo', [
			'label'   => __( 'Form Header Logo', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::MEDIA,
			'dynamic' => [
				'active' => true,
			],
			'default' => [
				'url' => Utils::get_placeholder_image_src(),
			],
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'      => 'lr_form_logo',
			'default'   => 'full',
			'separator' => 'none',
		] );
		$this->add_control( "lr_form_logo_position", [
			'label'     => __( 'Form Logo Position', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'inline' => [
					'title' => __( 'Left', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-arrow-left',
				],
				'block'  => [
					'title' => __( 'Top', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-arrow-up',
				],
			],
			'default'   => 'left',
			'separator' => 'after',
		] );

		$this->add_control( 'login_form_title', [
			'label'       => __( 'Login Form Title', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'placeholder' => __( 'Welcome Back!', 'essential-addons-for-elementor-lite' ),
			'separator'   => 'before',
		] );
		$this->add_control( 'login_form_subtitle', [
			'label'       => __( 'Login Form Sub Title', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXTAREA,
			'dynamic'     => [ 'active' => true, ],
			'placeholder' => __( 'Please login to your account', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control( 'register_form_title', [
			'label'       => __( 'Register Form Title', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'placeholder' => __( 'Create a New Account', 'essential-addons-for-elementor-lite' ),
			'separator'   => 'before',
		] );
		$this->add_control( 'register_form_subtitle', [
			'label'       => __( 'Register Form Sub Title', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXTAREA,
			'dynamic'     => [ 'active' => true, ],
			'placeholder' => __( 'Create an account to enjoy awesome features.', 'essential-addons-for-elementor-lite' ),
		] );

		$this->end_controls_section();
	}

	protected function init_content_login_options_controls() {

		$this->start_controls_section( 'section_content_login_options', [
			'label'      => __( 'Login Form Options', 'essential-addons-for-elementor-lite' ),
			'conditions' => $this->get_form_controls_display_condition( 'login' ),
		] );

		$this->add_control( 'redirect_after_login', [
			'label' => __( 'Redirect After Login', 'essential-addons-for-elementor-lite' ),
			'type'  => Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'redirect_url', [
			'type'          => Controls_Manager::URL,
			'show_label'    => false,
			'show_external' => false,
			'placeholder'   => admin_url(),
			'description'   => __( 'Please note that only your current domain is allowed here to keep your site secure.', 'essential-addons-for-elementor-lite' ),
			'condition'     => [
				'redirect_after_login' => 'yes',
			],
			'default'       => [
				'url'         => admin_url(),
				'is_external' => false,
				'nofollow'    => true,
			],
			'separator'     => 'after',
		] );

		$this->end_controls_section();
	}

	protected function social_login_promo() {

		$this->start_controls_section( 'section_content_social_login', [
			'label'      => __( 'Social Login', 'essential-addons-elementor' ),
			'conditions' => $this->get_form_controls_display_condition( 'login' ),
		] );

		$this->add_control( 'enable_google_login', [
			'label'   => sprintf( __( 'Enable Login with Google %s', 'essential-addons-for-elementor-lite' ),  '<i class="eael-pro-labe eicon-pro-icon"></i>' ),
			'type'    => Controls_Manager::SWITCHER,
			'classes' => 'eael-pro-control',
		] );

		$this->add_control( 'enable_fb_login', [
			'label'   => sprintf( __( 'Enable Login with Facebook %s', 'essential-addons-for-elementor-lite' ),  '<i class="eael-pro-labe eicon-pro-icon"></i>' ),
			'type'    => Controls_Manager::SWITCHER,
			'classes' => 'eael-pro-control',
		] );

		$this->end_controls_section();
	}

	protected function init_content_terms_controls() {
		$this->start_controls_section( 'section_content_terms_conditions', [
			'label'      => __( 'Terms & Conditions', 'essential-addons-for-elementor-lite' ),
			'conditions' => $this->get_form_controls_display_condition( 'register' ),
		] );

		$this->add_control( 'show_terms_conditions', [
			'label'        => __( 'Enforce Terms & Conditions', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
			'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
			'default'      => 'no',
			'return_value' => 'yes',
		] );

		$this->add_control( 'acceptance_label', [
			'label'       => __( 'Acceptance Label', 'essential-addons-for-elementor-lite' ),
			'description' => __( 'Eg. I accept the terms & conditions. Note: First line is checkbox label & Last line will be used as link text.', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 2,
			'label_block' => true,
			'placeholder' => __( 'I Accept the Terms and Conditions.', 'essential-addons-for-elementor-lite' ),
			/* translators: \n means new line. So, Don't translate this*/
			'default'     => __( "I Accept\n the Terms and Conditions.", 'essential-addons-for-elementor-lite' ),
			'condition'   => [
				'show_terms_conditions' => 'yes',
			],
		] );

		$this->add_control( 'acceptance_text_source', [
			'label'     => __( 'Content Source', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'editor' => __( 'Editor', 'essential-addons-for-elementor-lite' ),
				'custom' => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			],
			'default'   => 'custom',
			'condition' => [
				'show_terms_conditions' => 'yes',
			],
		] );

		$this->add_control( 'acceptance_text', [
			'label'     => __( 'Terms and Conditions', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::WYSIWYG,
			'rows'      => 3,
			'default'   => __( 'Please go through the following terms and conditions carefully.', 'essential-addons-for-elementor-lite' ),
			'condition' => [
				'show_terms_conditions'  => 'yes',
				'acceptance_text_source' => 'editor',
			],
		] );


		$this->add_control( 'acceptance_text_url', [
			'label'       => __( 'Terms & Conditions URL', 'essential-addons-for-elementor-lite' ),
			'description' => __( 'Enter the link where your terms & condition or privacy policy is found.', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::URL,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => [
				'url'         => get_the_permalink( get_option( 'wp_page_for_privacy_policy' ) ),
				'is_external' => true,
				'nofollow'    => true,
			],
			'condition'   => [
				'show_terms_conditions'  => 'yes',
				'acceptance_text_source' => 'custom',
			],
		] );

		$this->end_controls_section();
	}

	protected function init_content_validation_messages_controls() {
		$this->start_controls_section( 'section_content_errors', [
			'label' => __( 'Validation Messages', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control( 'err_message_heading', [
			'label' => esc_html__( 'Error Messages', 'essential-addons-for-elementor-lite' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_control( 'err_email', [
			'label'       => __( 'Invalid Email', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Eg. Your email is invalid.', 'essential-addons-for-elementor-lite' ),
			'default'     => __( "You have used an invalid email", 'essential-addons-for-elementor-lite' ),
		] );
		$this->add_control( 'err_email_missing', [
			'label'       => __( 'Email is missing', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Eg. Email is missing or Invalid', 'essential-addons-for-elementor-lite' ),
			'default'     => __( 'Email is missing or Invalid', 'essential-addons-for-elementor-lite' ),
		] );
		$this->add_control( 'err_email_used', [
			'label'       => __( 'Already Used Email', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Eg. Your email is already in use..', 'essential-addons-for-elementor-lite' ),
			'default'     => __( 'The provided email is already registered with other account. Please login or reset password or use another email.', 'essential-addons-for-elementor-lite' ),
		] );
		$this->add_control( 'err_username', [
			'label'       => __( 'Invalid Username', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Eg. Your username is invalid.', 'essential-addons-for-elementor-lite' ),
			'default'     => __( "You have used an invalid username", 'essential-addons-for-elementor-lite' ),
		] );
		$this->add_control( 'err_username_used', [
			'label'       => __( 'Username already in use', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Eg. Your username is already registered.', 'essential-addons-for-elementor-lite' ),
			'default'     => __( 'Invalid username provided or the username already registered.', 'essential-addons-for-elementor-lite' ),
		] );
		$this->add_control( 'err_pass', [
			'label'       => __( 'Invalid Password', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Eg. Your password is invalid', 'essential-addons-for-elementor-lite' ),
			'default'     => __( "Your password is invalid.", 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control( 'err_conf_pass', [
			'label'       => __( 'Invalid Password Confirmed', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Eg. Password did not matched', 'essential-addons-for-elementor-lite' ),
			'default'     => __( "Your confirmed password did not match", 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control( 'err_loggedin', [
			'label'       => __( 'Already Logged In', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Eg. You are already logged in', 'essential-addons-for-elementor-lite' ),
			'default'     => __( "You are already logged in", 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control( 'err_recaptcha', [
			'label'       => __( 'reCAPTCHA Failed', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Eg. reCAPTCHA Validation Failed', 'essential-addons-for-elementor-lite' ),
			'default'     => __( "You did not pass reCAPTCHA challenge.", 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control( 'err_tc', [
			'label'       => __( 'Terms & Condition Error', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Eg. You must accept the Terms & Conditions', 'essential-addons-for-elementor-lite' ),
			'default'     => __( 'You did not accept the Terms and Conditions. Please accept it and try again.', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control( 'err_unknown', [
			'label'       => __( 'Other Errors', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Eg. Something went wrong', 'essential-addons-for-elementor-lite' ),
			'default'     => __( "Something went wrong!", 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control( 'success_message_heading', [
			'label'     => esc_html__( 'Success Messages', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'success_login', [
			'label'       => __( 'Successful Login', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Eg. You have logged in successfully', 'essential-addons-for-elementor-lite' ),
			'default'     => __( "You have logged in successfully", 'essential-addons-for-elementor-lite' ),
		] );
		$this->add_control( 'success_register', [
			'label'       => __( 'Successful Registration', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => __( 'Registration completed successfully, Check your inbox for password if you did not provided while registering.', 'essential-addons-for-elementor-lite' ),
			'placeholder' => __( 'eg. Registration completed successfully', 'essential-addons-for-elementor-lite' ),
		] );

		$this->end_controls_section();
	}

	protected function show_pro_promotion(){

        $this->start_controls_section(
            'eael_section_pro',
            [
                'label' => __( 'Go Premium for More Features', 'essential-addons-for-elementor-lite' ),
            ]
        );

        $this->add_control(
            'eael_control_get_pro',
            [
                'label'       => __( 'Unlock more possibilities', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => [
                    '1' => [
                        'title' => '',
                        'icon'  => 'fa fa-unlock-alt',
                    ],
                ],
                'default'     => '1',
                'description' => '<span class="pro-feature"> Get the  <a href="https://wpdeveloper.net/upgrade/ea-pro" target="_blank">Pro version</a> for more stunning elements and customization options.</span>',
            ]
        );

        $this->end_controls_section();

    }

	protected function init_content_register_fields_controls() {

		$this->start_controls_section( 'section_content_register_fields', [
			'label'      => __( 'Register Form Fields', 'essential-addons-for-elementor-lite' ),
			'conditions' => $this->get_form_controls_display_condition( 'register' ),
		] );
		$this->add_control( 'register_form_field_note', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => __( 'Select the type of fields you want to show in the registration form', 'essential-addons-for-elementor-lite' ),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
		] );
		$repeater = new Repeater();

		$repeater->add_control( 'field_type', [
			'label'   => __( 'Type', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SELECT,
			'options' => $this->get_form_field_types(),
			'default' => 'first_name',
		] );

		$repeater->add_control( 'field_label', [
			'label'   => __( 'Label', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
			'dynamic' => [
				'active' => true,
			],
		] );

		$repeater->add_control( 'placeholder', [
			'label'   => __( 'Placeholder', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
			'dynamic' => [
				'active' => true,
			],
		] );

		$repeater->add_control( 'required', [
			'label'     => __( 'Required', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SWITCHER,
			'condition' => [
				'field_type!' => [
					'email',
					'password',
					'confirm_pass',
				],
			],
		] );

		$repeater->add_control( 'required_note', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => __( 'Note: This field is required by default.', 'essential-addons-for-elementor-lite' ),
			'condition'       => [
				'field_type' => [
					'email',
					'password',
					'confirm_pass',
				],
			],
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
		] );

		$repeater->add_responsive_control( 'width', [
			'label'   => __( 'Field Width', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				''    => __( 'Default', 'essential-addons-for-elementor-lite' ),
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
		apply_filters( 'eael/login-register/register-repeater', $repeater );
		$rf = [
			'label'       => __( 'Fields', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => apply_filters( 'eael/login-register/register-repeater-fields', $repeater->get_controls() ),
			'default'     => apply_filters( 'eael/login-register/register-rf-default', [
				[
					'field_type'  => 'user_name',
					'field_label' => __( 'Username', 'essential-addons-for-elementor-lite' ),
					'placeholder' => __( 'Username', 'essential-addons-for-elementor-lite' ),
					'width'       => '100',
				],
				[
					'field_type'  => 'email',
					'field_label' => __( 'Email', 'essential-addons-for-elementor-lite' ),
					'placeholder' => __( 'Email', 'essential-addons-for-elementor-lite' ),
					'required'    => 'yes',
					'width'       => '100',
				],
				[
					'field_type'  => 'password',
					'field_label' => __( 'Password', 'essential-addons-for-elementor-lite' ),
					'placeholder' => __( 'Password', 'essential-addons-for-elementor-lite' ),
					'required'    => 'yes',
					'width'       => '100',
				],
			] ),
			'title_field' => '{{ field_label }}',
		];
		if ( $this->pro_enabled ) {
			$rf['title_field'] = '{{{ elementor.helpers.renderIcon( this, icon, {}, "i", "panel" ) || \'<i class="{{ icon }}" aria-hidden="true"></i>\' }}} {{ field_label }}';
		}
		$this->add_control( 'register_fields', $rf );

		$this->add_control( 'show_labels', [
			'label'   => __( 'Show Label', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'mark_required', [
			'label'     => __( 'Show Required Mark', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SWITCHER,
			'condition' => [
				'show_labels' => 'yes',
			],
		] );
		do_action( 'eael/login-register/after-register-options-controls', $this );

		/*--Register Fields Button--*/
		$this->add_control( 'reg_button_heading', [
			'label'     => esc_html__( 'Register Button', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'reg_button_text', [
			'label'   => __( 'Button Text', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::TEXT,
			'dynamic' => [ 'active' => true, ],
			'default' => __( 'Register', 'essential-addons-for-elementor-lite' ),
		] );


		$this->end_controls_section();
	}

	protected function init_content_register_options_controls() {

		$this->start_controls_section( 'section_content_register_actions', [
			'label'      => __( 'Register Form Options', 'essential-addons-for-elementor-lite' ),
			'conditions' => $this->get_form_controls_display_condition( 'register' ),
		] );

		$this->add_control( 'register_action', [
			'label'       => __( 'Register Actions', 'essential-addons-for-elementor-lite' ),
			'description' => __( 'You can select what should happen after a user registers successfully', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::SELECT2,
			'multiple'    => true,
			'label_block' => true,
			'default'     => 'send_email',
			'options'     => [
				'redirect'   => __( 'Redirect', 'essential-addons-for-elementor-lite' ),
				'auto_login' => __( 'Auto Login', 'essential-addons-for-elementor-lite' ),
				'send_email' => __( 'Notify User By Email', 'essential-addons-for-elementor-lite' ),
			],
		] );

		$this->add_control( 'register_redirect_url', [
			'type'          => Controls_Manager::URL,
			'label'         => __( 'Custom Redirect URL', 'essential-addons-for-elementor-lite' ),
			'show_external' => false,
			'placeholder'   => __( 'eg. https://your-link.com/wp-admin/', 'essential-addons-for-elementor-lite' ),
			'description'   => __( 'Please note that only your current domain is allowed here to keep your site secure.', 'essential-addons-for-elementor-lite' ),
			'default'       => [
				'url'         => get_admin_url(),
				'is_external' => false,
				'nofollow'    => true,
			],
			'condition'     => [
				'register_action' => 'redirect',
			],
		] );

        if(current_user_can('create_users')){
            $user_role = $this->get_user_roles();
        }else{
            $user_role = [
                get_option( 'default_role' ) =>  ucfirst(get_option( 'default_role' ))
            ];
        }

		$this->add_control( 'register_user_role', [
			'label'     => __( 'New User Role', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => '',
			'options'   => $user_role,
			'separator' => 'before',
		] );

		$this->end_controls_section();
	}

	protected function init_content_register_user_email_controls() {
		/* translators: %s: Site Name */
		$default_subject = sprintf( __( 'Thank you for registering on "%s"!', 'essential-addons-for-elementor-lite' ), get_option( 'blogname' ) );
		$default_message = $default_subject . "\r\n\r\n";
		$default_message .= __( 'Username: [username]', 'essential-addons-for-elementor-lite' ) . "\r\n\r\n";
		$default_message .= __( 'Password: [password]', 'essential-addons-for-elementor-lite' ) . "\r\n\r\n";
		$default_message .= __( 'To reset your password, visit the following address:', 'essential-addons-for-elementor-lite' ) . "\r\n\r\n";
		$default_message .= "[password_reset_link]\r\n\r\n";
		$default_message .= __( 'Please click the following address to login to your account:', 'essential-addons-for-elementor-lite' ) . "\r\n\r\n";
		$default_message .= wp_login_url() . "\r\n";

		$this->start_controls_section( 'section_content_reg_email', [
			'label'      => __( 'Register User Email Options', 'essential-addons-for-elementor-lite' ),
			'conditions' => [
				'relation' => 'or',
				'terms'    => [
					[
						'name'  => 'show_register_link',
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
						'value' => 'register',
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
			'label'       => __( 'Email Template Type', 'essential-addons-for-elementor-lite' ),
			'description' => __( 'Default template uses WordPress Default email template. So, please select the Custom Option to send the user proper information if you used any username field.', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => 'default',
			'render_type' => 'none',
			'options'     => [
				'default' => __( 'WordPres Default', 'essential-addons-for-elementor-lite' ),
				'custom'  => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			],
		] );

		$this->add_control( 'reg_email_subject', [
			'label'       => __( 'Email Subject', 'essential-addons-for-elementor-lite' ),
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
			'label'       => __( 'Email Message', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::WYSIWYG,
			'placeholder' => __( 'Enter Your Custom Email Message..', 'essential-addons-for-elementor-lite' ),
			'default'     => $default_message,
			'label_block' => true,
			'render_type' => 'none',
			'condition'   => [
				'reg_email_template_type' => 'custom',
			],
		] );

		$this->add_control( 'reg_email_content_note', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => __( '<strong>Note:</strong> You can use dynamic content in the email body like [fieldname]. For example [username] will be replaced by user-typed username. Available tags are: [password], [username], [email], [firstname],[lastname], [website], [loginurl], [password_reset_link] and [sitetitle] ', 'essential-addons-for-elementor-lite' ),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			'condition'       => [
				'reg_email_template_type' => 'custom',
			],
			'render_type'     => 'none',
		] );

		$this->add_control( 'reg_email_content_type', [
			'label'       => __( 'Email Content Type', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => 'html',
			'render_type' => 'none',
			'options'     => [
				'html'  => __( 'HTML', 'essential-addons-for-elementor-lite' ),
				'plain' => __( 'Plain', 'essential-addons-for-elementor-lite' ),
			],
			'condition'   => [
				'reg_email_template_type' => 'custom',
			],
		] );

		$this->end_controls_section();
	}

	protected function init_content_register_admin_email_controls() {
		/* translators: %s: Site Name */
		$default_subject = sprintf( __( '["%s"] New User Registration', 'essential-addons-for-elementor-lite' ), get_option( 'blogname' ) );
		/* translators: %s: Site Name */
		$default_message = sprintf( __( "New user registration on your site %s", 'essential-addons-for-elementor-lite' ), get_option( 'blogname' ) ) . "\r\n\r\n";
		$default_message .= __( 'Username: [username]', 'essential-addons-for-elementor-lite' ) . "\r\n\r\n";
		$default_message .= __( 'Email: [email]', 'essential-addons-for-elementor-lite' ) . "\r\n\r\n";


		$this->start_controls_section( 'section_content_reg_admin_email', [
			'label'      => __( 'Register Admin Email Options', 'essential-addons-for-elementor-lite' ),
			'conditions' => [
				'relation' => 'or',
				'terms'    => [
					[
						'name'  => 'show_register_link',
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
						'value' => 'register',
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
			'label'       => __( 'Email Template Type', 'essential-addons-for-elementor-lite' ),
			'description' => __( 'Default template uses WordPress Default Admin email template. You can customize it by choosing the custom option.', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => 'default',
			'render_type' => 'none',
			'options'     => [
				'default' => __( 'WordPres Default', 'essential-addons-for-elementor-lite' ),
				'custom'  => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			],
		] );

		$this->add_control( 'reg_admin_email_subject', [
			'label'       => __( 'Email Subject', 'essential-addons-for-elementor-lite' ),
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
			'label'       => __( 'Email Message', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::WYSIWYG,
			'placeholder' => __( 'Enter Your Custom Email Message..', 'essential-addons-for-elementor-lite' ),
			'default'     => $default_message,
			'label_block' => true,
			'render_type' => 'none',
			'condition'   => [
				'reg_admin_email_template_type' => 'custom',
			],
		] );

		$this->add_control( 'reg_admin_email_content_note', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => __( '<strong>Note:</strong> You can use dynamic content in the email body like [fieldname]. For example [username] will be replaced by user-typed username. Available tags are: [username], [email], [firstname],[lastname], [website], [loginurl] and [sitetitle] ', 'essential-addons-for-elementor-lite' ),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			'condition'       => [
				'reg_admin_email_template_type' => 'custom',
			],
			'render_type'     => 'none',
		] );

		$this->add_control( 'reg_admin_email_content_type', [
			'label'       => __( 'Email Content Type', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => 'html',
			'render_type' => 'none',
			'options'     => [
				'html'  => __( 'HTML', 'essential-addons-for-elementor-lite' ),
				'plain' => __( 'Plain', 'essential-addons-for-elementor-lite' ),
			],
			'condition'   => [
				'reg_admin_email_template_type' => 'custom',
			],
		] );

		$this->end_controls_section();
	}

	/**
	 * It prints controls for managing general style of both login and registration form
	 */
	protected function init_style_general_controls() {
		$this->start_controls_section( 'section_style_general', [
			'label' => __( 'General', 'essential-addons-for-elementor-lite' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );
		//---Form Container or Box
		$this->add_control( 'form_form_wrap_po_toggle', [
			'label'        => __( 'Container Box', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Default', 'essential-addons-for-elementor-lite' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',
		] );
		$this->start_popover();
		$this->add_responsive_control( "eael_form_wrap_width", [
			'label'           => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
			'type'            => Controls_Manager::SLIDER,
			'size_units'      => [
				'px',
				'rem',
				'%',
			],
			'range'           => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 10,
					'step' => .5,
				],
				'%'   => [
					'min' => 0,
					'max' => 100,
				],
			],
			'desktop_default' => [
				'unit' => '%',
				'size' => 65,
			],
			'tablet_default'  => [
				'unit' => '%',
				'size' => 75,
			],
			'mobile_default'  => [
				'unit' => '%',
				'size' => 100,
			],
			'selectors'       => [
				"{{WRAPPER}} .eael-lr-form-wrapper" => 'width: {{SIZE}}{{UNIT}};',
			],
			'condition'       => [
				'form_form_wrap_po_toggle' => 'yes',
			],
		] );

		$this->add_responsive_control( "eael_form_wrap_margin", [
			'label'      => __( 'Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-lr-form-wrapper" => $this->apply_dim( 'margin' ),
			],
			'condition'  => [
				'form_form_wrap_po_toggle' => 'yes',
			],
		] );
		$this->add_responsive_control( "eael_form_wrap_padding", [
			'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-lr-form-wrapper" => $this->apply_dim( 'padding' ),
			],
			'condition'  => [
				'form_form_wrap_po_toggle' => 'yes',
			],
		] );
		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'      => "eael_form_wrap_border",
			'selector'  => "{{WRAPPER}} .eael-lr-form-wrapper",
			'condition' => [
				'form_form_wrap_po_toggle' => 'yes',
			],
		] );
		$this->add_control( "eael_form_wrap_border_radius", [
			'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-lr-form-wrapper" => $this->apply_dim( 'border-radius' ),
			],
			'condition'  => [
				'form_form_wrap_po_toggle' => 'yes',
			],
		] );
		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => "eael_form_wrap_bg_color",
			'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
			'types'     => [
				'classic',
				'gradient',
			],
			'selector'  => "{{WRAPPER}} .eael-lr-form-wrapper",
			'condition' => [
				'form_form_wrap_po_toggle' => 'yes',
			],
		] );
		$this->end_popover();
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'label'    => __( 'Container Box Shadow', 'essential-addons-for-elementor-lite' ),
			'name'     => 'eael_form_wrap_shadow',
			'selector' => "{{WRAPPER}} .eael-lr-form-wrapper",
			'exclude'  => [
				'box_shadow_position',
			],
		] );

		//----Form Wrapper-----
		$this->add_control( 'form_form_po_toggle', [
			'label'        => __( 'Form', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Default', 'essential-addons-for-elementor-lite' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',
			'separator'    => 'before',
		] );
		$this->start_popover();
		$this->add_control( 'eael_form_wrapper_heading', [
			'type'      => Controls_Manager::HEADING,
			'label'     => __( '---Form Wrapper---', 'essential-addons-for-elementor-lite' ),
			'separator' => 'before',
		] );

		$this->add_responsive_control( "eael_form_width", [
			'label'           => esc_html__( 'Wrapper width', 'essential-addons-for-elementor-lite' ),
			'type'            => Controls_Manager::SLIDER,
			'size_units'      => [
				'px',
				'rem',
				'%',
			],
			'range'           => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 10,
					'step' => .5,
				],
				'%'   => [
					'min' => 0,
					'max' => 100,
				],
			],
			'desktop_default' => [
				'unit' => '%',
				'size' => 50,
			],
			'tablet_default'  => [
				'unit' => '%',
				'size' => 75,
			],
			'mobile_default'  => [
				'unit' => '%',
				'size' => 100,
			],
			'selectors'       => [
				"{{WRAPPER}} .lr-form-wrapper" => 'width: {{SIZE}}{{UNIT}};',
			],
			'condition'       => [
				'form_form_po_toggle' => 'yes',
			],
		] );

		$this->add_responsive_control( "eael_form_margin", [
			'label'      => __( 'Wrapper Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper" => $this->apply_dim( 'margin' ),
			],
			'condition'  => [
				'form_form_po_toggle' => 'yes',
			],
		] );
		$this->add_responsive_control( "eael_form_padding", [
			'label'      => __( 'Wrapper Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper" => $this->apply_dim( 'padding' ),
			],
			'condition'  => [
				'form_form_po_toggle' => 'yes',
			],
		] );
		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'      => "eael_form_border",
			'selector'  => "{{WRAPPER}} .lr-form-wrapper",
			'condition' => [
				'form_form_po_toggle' => 'yes',
			],
		] );
		$this->add_control( "eael_form_border_radius", [
			'label'      => __( 'Wrapper Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper" => $this->apply_dim( 'border-radius' ),
			],
			'condition'  => [
				'form_form_po_toggle' => 'yes',
			],
		] );
		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => "eael_form_bg_color",
			'label'    => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
			'types'    => [
				'classic',
				'gradient',
			],
			'selector' => "{{WRAPPER}} .lr-form-wrapper",
		] );

		$this->add_control( 'eael_form_input_container', [
			'type'      => Controls_Manager::HEADING,
			'label'     => __( '---Form Style---', 'essential-addons-for-elementor-lite' ),
			'separator' => 'before',
		] );
		$this->add_responsive_control( "eael_form_ic_width", [
			'label'      => esc_html__( 'Form width', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'rem',
				'%',
			],
			'range'      => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 10,
					'step' => .5,
				],
				'%'   => [
					'min' => 0,
					'max' => 100,
				],
			],
			'default'    => [
				'unit' => '%',
				'size' => 100,
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper form" => 'width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'form_form_po_toggle' => 'yes',
			],
		] );

		$this->add_responsive_control( "eael_form_ic_margin", [
			'label'      => __( 'Form Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper form" => $this->apply_dim( 'margin' ),
			],
			'condition'  => [
				'form_form_po_toggle' => 'yes',
			],
		] );
		$this->add_responsive_control( "eael_form_ic_padding", [
			'label'      => __( 'Form Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper form" => $this->apply_dim( 'padding' ),
			],
			'condition'  => [
				'form_form_po_toggle' => 'yes',
			],
		] );
		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'      => "eael_form_ic_border",
			'selector'  => "{{WRAPPER}} .lr-form-wrapper form",
			'condition' => [
				'form_form_po_toggle' => 'yes',
			],
		] );
		$this->add_control( "eael_form_ic_border_radius", [
			'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper form" => $this->apply_dim( 'border-radius' ),
			],
			'condition'  => [
				'form_form_po_toggle' => 'yes',
			],
		] );
		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => "eael_form_ic_bg_color",
			'label'    => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
			'types'    => [
				'classic',
				'gradient',
			],
			'selector' => "{{WRAPPER}} .lr-form-wrapper form",
		] );
		$this->end_popover();

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'label'    => __( 'Form Wrapper Shadow', 'essential-addons-for-elementor-lite' ),
			'name'     => 'eael_form_shadow',
			'selector' => "{{WRAPPER}} .lr-form-wrapper",
			'exclude'  => [
				'box_shadow_position',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'label'    => __( 'Form Shadow', 'essential-addons-for-elementor-lite' ),
			'name'     => 'eael_form_ic_shadow',
			'selector' => "{{WRAPPER}} .lr-form-wrapper form",
			'exclude'  => [
				'box_shadow_position',
			],
		] );
		//@TODO; add only input wrapper shadow
		$this->end_controls_section();
	}

	protected function init_style_header_content_controls( $form_type = 'login' ) {
		$this->start_controls_section( "section_style_{$form_type}_header_content", [
			'label'      => sprintf( __( '%s Form Header', 'essential-addons-for-elementor-lite' ), ucfirst( $form_type ) ),
			// Login Form Header | Register Form Header
			'tab'        => Controls_Manager::TAB_STYLE,
			'conditions' => $this->get_form_controls_display_condition( $form_type ),
		] );
		//Define all css selectors ahead for better management
		$illustration_selector = "{{WRAPPER}} .eael-{$form_type}-form-wrapper .lr-form-illustration";
		$header_selector       = "{{WRAPPER}} .eael-{$form_type}-form-wrapper .lr-form-header";
		$logo_selector         = "{{WRAPPER}} .eael-{$form_type}-form-wrapper .lr-form-header img";
		$title_selector        = "{{WRAPPER}} .eael-{$form_type}-form-wrapper .lr-form-header .form-dsc h4";
		$subtitle_selector     = "{{WRAPPER}} .eael-{$form_type}-form-wrapper .lr-form-header .form-dsc p";
		$this->add_control( "{$form_type}_fhc_po_toggle", [
			'label'        => __( 'Header Content', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Default', 'essential-addons-for-elementor-lite' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',
		] );

		$this->start_popover();

		$this->add_responsive_control( "{$form_type}_fhc_width", [
			'label'      => esc_html__( 'Header width', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'rem',
				'%',
			],
			'range'      => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 10,
					'step' => .5,
				],
				'%'   => [
					'min' => 0,
					'max' => 100,
				],
			],
			'default'    => [
				'unit' => '%',
				'size' => 100,
			],
			'selectors'  => [
				$header_selector => 'width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				"{$form_type}_fhc_po_toggle" => 'yes',
			],
		] );
		$this->add_responsive_control( "{$form_type}_fhc_height", [
			'label'      => esc_html__( 'Header height', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'rem',
				'%',
			],
			'range'      => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 10,
					'step' => .5,
				],
				'%'   => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [
				$header_selector => 'height: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				"{$form_type}_fhc_po_toggle" => 'yes',
			],
		] );
		$this->add_responsive_control( "{$form_type}_fhc_margin", [
			'label'      => __( 'Header Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$header_selector => $this->apply_dim( 'margin' ),
			],
			'condition'  => [
				"{$form_type}_fhc_po_toggle" => 'yes',
			],
		] );
		$this->add_responsive_control( "{$form_type}_fhc_padding", [
			'label'      => __( 'Header Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$header_selector => $this->apply_dim( 'padding' ),
			],
			'condition'  => [
				"{$form_type}_fhc_po_toggle" => 'yes',
			],
		] );


		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'      => "{$form_type}_fhc_border",
			'selector'  => $header_selector,
			'condition' => [
				"{$form_type}_fhc_po_toggle" => 'yes',
			],
		] );
		$this->add_control( "{$form_type}_fhc_border_radius", [
			'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				$header_selector => $this->apply_dim( 'border-radius' ),
			],
			'condition'  => [
				"{$form_type}_fhc_po_toggle" => 'yes',
			],
		] );
		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => "{$form_type}_form_header_bg",
			'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
			'types'     => [
				'classic',
				'gradient',
			],
			'selector'  => $header_selector,
			'condition' => [
				"{$form_type}_fhc_po_toggle" => 'yes',
			],
		] );
		$this->end_popover();


		$this->add_control( "{$form_type}_form_img_po_toggle", [
			'label'        => __( 'Form Illustration', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Default', 'essential-addons-for-elementor-lite' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',
			'separator'    => 'before',
		] );
		$this->start_popover();
		$this->add_responsive_control( "{$form_type}_form_img_width", [
			'label'           => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
			'type'            => Controls_Manager::SLIDER,
			'size_units'      => [
				'px',
				'rem',
				'%',
			],
			'range'           => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 10,
					'step' => .5,
				],
				'%'   => [
					'min' => 0,
					'max' => 100,
				],
			],
			'desktop_default' => [
				'unit' => '%',
				'size' => 50,
			],
			'tablet_default'  => [
				'unit' => '%',
				'size' => 100,
			],
			'mobile_default'  => [
				'unit' => '%',
				'size' => 100,
			],
			'selectors'       => [
				$illustration_selector => 'width: {{SIZE}}{{UNIT}};',
			],
			'condition'       => [
				"{$form_type}_form_img_po_toggle" => 'yes',
			],
		] );
		$this->add_responsive_control( "{$form_type}_form_img_height", [
			'label'           => esc_html__( 'Height', 'essential-addons-for-elementor-lite' ),
			'type'            => Controls_Manager::SLIDER,
			'size_units'      => [
				'px',
				'rem',
			],
			'range'           => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 10,
					'step' => .5,
				],
			],
			'desktop_default' => [
				'unit' => 'px',
				'size' => 375,
			],
			'tablet_default'  => [
				'unit' => 'px',
				'size' => 375,
			],
			'mobile_default'  => [
				'unit' => 'px',
				'size' => 375,
			],
			'selectors'       => [
				$illustration_selector => 'height: {{SIZE}}{{UNIT}};',
			],
			'condition'       => [
				"{$form_type}_form_img_po_toggle" => 'yes',
			],
		] );
		$this->add_control( "{$form_type}_form_img_margin", [
			'label'      => __( 'Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$illustration_selector => $this->apply_dim( 'margin' ),
			],
			'condition'  => [
				"{$form_type}_form_img_po_toggle" => 'yes',
			],
		] );
		$this->add_control( "{$form_type}_form_img_padding", [
			'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$illustration_selector => $this->apply_dim( 'padding' ),
			],
			'condition'  => [
				"{$form_type}_form_img_po_toggle" => 'yes',
			],
		] );
		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'      => "{$form_type}_form_img_border",
			'selector'  => $illustration_selector,
			'condition' => [
				"{$form_type}_form_img_po_toggle" => 'yes',
			],
		] );
		$this->add_control( "{$form_type}_form_img_border_radius", [
			'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				$illustration_selector => $this->apply_dim( 'border-radius' ),
			],
			'condition'  => [
				"{$form_type}_form_img_po_toggle" => 'yes',
			],
		] );
		$this->end_popover();
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'label'    => __( 'Illustration Shadow', 'essential-addons-for-elementor-lite' ),
			'name'     => "{$form_type}_form_img_shadow",
			'selector' => $illustration_selector,
			'exclude'  => [
				'box_shadow_position',
			],
		] );
		$this->add_control( "{$form_type}_form_logo_po_toggle", [
			'label'        => __( 'Form Logo', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Default', 'essential-addons-for-elementor-lite' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',
			'separator'    => 'before',
		] );
		$this->start_popover();
		$this->add_responsive_control( "{$form_type}_form_logo_width", [
			'label'      => esc_html__( 'width', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'rem',
				'%',
			],
			'range'      => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 10,
					'step' => .5,
				],
				'%'   => [
					'min' => 0,
					'max' => 100,
				],
			],
			'default'    => [
				'unit' => 'px',
				'size' => 100,
			],
			'selectors'  => [
				$logo_selector => 'width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				"{$form_type}_form_logo_po_toggle" => 'yes',
			],
		] );
		$this->add_responsive_control( "{$form_type}_form_logo_height", [
			'label'      => esc_html__( 'height', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'rem',
				'%',
			],
			'range'      => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 10,
					'step' => .5,
				],
				'%'   => [
					'min' => 0,
					'max' => 100,
				],
			],
			'default'    => [
				'unit' => 'px',
				'size' => 100,
			],
			'selectors'  => [
				$logo_selector => 'height: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				"{$form_type}_form_logo_po_toggle" => 'yes',
			],
		] );
		$this->add_responsive_control( "{$form_type}_form_logo_margin", [
			'label'      => __( 'Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$logo_selector => $this->apply_dim( 'margin' ),
			],
			'condition'  => [
				"{$form_type}_form_logo_po_toggle" => 'yes',
			],
		] );
		$this->add_responsive_control( "{$form_type}_form_logo_padding", [
			'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$logo_selector => $this->apply_dim( 'padding' ),
			],
			'condition'  => [
				"{$form_type}_form_logo_po_toggle" => 'yes',
			],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'      => "{$form_type}_form_logo_border",
			'selector'  => $logo_selector,
			'condition' => [
				"{$form_type}_form_logo_po_toggle" => 'yes',
			],
		] );
		$this->add_control( "{$form_type}_form_logo_border_radius", [
			'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				$logo_selector => $this->apply_dim( 'border-radius' ),
			],
			'condition'  => [
				"{$form_type}_form_logo_po_toggle" => 'yes',
			],
		] );
		$this->end_popover();
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'label'    => __( 'Logo Shadow', 'essential-addons-for-elementor-lite' ),
			'name'     => "{$form_type}_form_logo_shadow",
			'selector' => $logo_selector,
			'exclude'  => [
				'box_shadow_position',
			],
		] );


		/*-- Title Typography --*/
		$this->add_control( "{$form_type}_form_title_po_toggle", [
			'label'        => __( 'Title', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Default', 'essential-addons-for-elementor-lite' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',
			'separator'    => 'before',
		] );
		$this->start_popover();
		$this->add_responsive_control( "{$form_type}_form_title_margin", [
			'label'      => __( 'Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$title_selector => $this->apply_dim( 'margin' ),
			],
			'condition'  => [
				"{$form_type}_form_title_po_toggle" => 'yes',
			],
		] );
		$this->add_responsive_control( "{$form_type}_form_title_padding", [
			'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$title_selector => $this->apply_dim( 'padding' ),
			],
			'condition'  => [
				"{$form_type}_form_title_po_toggle" => 'yes',
			],
		] );
		$this->add_control( "{$form_type}_form_title_color", [
			'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				$title_selector => 'color: {{VALUE}};',
			],
			'condition' => [
				"{$form_type}_form_title_po_toggle" => 'yes',
			],
		] );
		$this->add_control( "{$form_type}_form_title_bg_color", [
			'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				$title_selector => 'background: {{VALUE}};',
			],
			'condition' => [
				"{$form_type}_form_title_po_toggle" => 'yes',
			],
		] );


		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'      => "{$form_type}_form_title_border",
			'selector'  => $title_selector,
			'condition' => [
				"{$form_type}_form_title_po_toggle" => 'yes',
			],
		] );
		$this->add_control( "{$form_type}_form_title_border_radius", [
			'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				$title_selector => $this->apply_dim( 'border-radius' ),
			],
			'condition'  => [
				"{$form_type}_form_title_po_toggle" => 'yes',
			],
		] );

		$this->end_popover();
		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => "{$form_type}_form_title_typo",
			'label'    => __( 'Title Typography', 'essential-addons-for-elementor-lite' ),
			'selector' => $title_selector,
		] );

		/*Subtitle----*/
		$this->add_control( "{$form_type}_form_subtitle_po_toggle", [
			'label'        => __( 'Subtitle', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Default', 'essential-addons-for-elementor-lite' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',
			'separator'    => 'before',
		] );
		$this->start_popover();
		$this->add_control( "{$form_type}_form_subtitle_margin", [
			'label'      => __( 'Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$subtitle_selector => $this->apply_dim( 'margin' ),
			],
			'condition'  => [
				"{$form_type}_form_subtitle_po_toggle" => 'yes',
			],
		] );
		$this->add_control( "{$form_type}_form_subtitle_padding", [
			'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$subtitle_selector => $this->apply_dim( 'padding' ),
			],
			'condition'  => [
				"{$form_type}_form_subtitle_po_toggle" => 'yes',
			],
		] );
		$this->add_control( "{$form_type}_form_subtitle_color", [
			'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				$subtitle_selector => 'color: {{VALUE}};',
			],
			'condition' => [
				"{$form_type}_form_subtitle_po_toggle" => 'yes',
			],
		] );
		$this->add_control( "{$form_type}_form_subtitle_bg_color", [
			'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				$subtitle_selector => 'background: {{VALUE}};',
			],
			'condition' => [
				"{$form_type}_form_subtitle_po_toggle" => 'yes',
			],
		] );


		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'      => "{$form_type}_form_subtitle_border",
			'selector'  => $subtitle_selector,
			'condition' => [
				"{$form_type}_form_subtitle_po_toggle" => 'yes',
			],
		] );
		$this->add_control( "{$form_type}_form_subtitle_border_radius", [
			'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				$subtitle_selector => $this->apply_dim( 'border-radius' ),
			],
			'condition'  => [
				"{$form_type}_form_subtitle_po_toggle" => 'yes',
			],
		] );

		$this->end_popover();
		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => "{$form_type}_form_subtitle_typo",
			'label'    => __( 'Subtitle Typography', 'essential-addons-for-elementor-lite' ),
			'selector' => $subtitle_selector,
		] );

		$this->end_controls_section();
	}

	protected function init_style_input_fields_controls() {
		$this->start_controls_section( 'section_style_form_fields', [
			'label' => __( 'Form Fields', 'essential-addons-for-elementor-lite' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );
		$this->add_control( 'eael_form_field_po_toggle', [
			'label'        => __( 'Spacing', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Default', 'essential-addons-for-elementor-lite' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',
		] );

		$this->start_popover();
		$this->add_control( 'eael_form_input_fields_heading', [
			'type'  => Controls_Manager::HEADING,
			'label' => __( 'Form Input Fields', 'essential-addons-for-elementor-lite' ),
		] );
		$this->add_responsive_control( "eael_form_field_margin", [
			'label'      => __( 'Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper .eael-lr-form-group" => $this->apply_dim( 'margin' ),
			],
			'condition'  => [
				'eael_form_field_po_toggle' => 'yes',
			],
		] );
		$this->add_responsive_control( "eael_form_field_padding", [
			'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper .eael-lr-form-control" => $this->apply_dim( 'padding' ),
			],
			'condition'  => [
				'eael_form_field_po_toggle' => 'yes',
			],
		] );

		$this->add_control( 'eael_form_tc_fields_heading', [
			'type'      => Controls_Manager::HEADING,
			'label'     => __( 'Terms & Condition Field', 'essential-addons-for-elementor-lite' ),
			'separator' => 'before',
		] );
		$this->add_responsive_control( "eael_form_tc_field_margin", [
			'label'      => __( 'Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper .eael_accept_tnc_wrap" => $this->apply_dim( 'margin' ),
			],
			'condition'  => [
				'eael_form_field_po_toggle' => 'yes',
			],
		] );
		$this->add_responsive_control( "eael_form_tc_field_padding", [
			'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper .eael_accept_tnc_wrap" => $this->apply_dim( 'padding' ),
			],
			'condition'  => [
				'eael_form_field_po_toggle' => 'yes',
			],
		] );
		$this->end_popover();
		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => "eael_fields_typography",
			'selector' => "{{WRAPPER}} .lr-form-wrapper .eael-lr-form-control",
		] );
		$this->add_responsive_control( "ph_align", [
			'label'     => __( 'Text Alignment', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'left'   => [
					'title' => __( 'Left', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-h-align-left',
				],
				'center' => [
					'title' => __( 'Center', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-h-align-center',
				],
				'right'  => [
					'title' => __( 'Right', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-h-align-right',
				],
			],
			'default'   => 'left',
			'selectors' => [
				"{{WRAPPER}} .lr-form-wrapper .eael-lr-form-control" => 'text-align: {{VALUE}};',
			],
		] );
		$this->add_control( 'eael_form_label_colors_heading', [
			'type'      => Controls_Manager::HEADING,
			'label'     => __( 'Colors & Border', 'essential-addons-for-elementor-lite' ),
			'separator' => 'before',
		] );
		$this->start_controls_tabs( "tabs_form_fields_style" );

		/*-----Form Input Fields NORMAL state------ */
		$this->start_controls_tab( "tab_form_field_style_normal", [
			'label' => __( 'Normal', 'essential-addons-for-elementor-lite' ),
		] );
		$this->add_control( 'eael_field_color', [
			'label'     => __( 'Text Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .lr-form-wrapper .eael-lr-form-control" => 'color: {{VALUE}};',
			],
		] );
		$this->add_control( 'eael_field_placeholder_color', [
			'label'     => __( 'Placeholder Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .lr-form-wrapper input.eael-lr-form-control::placeholder" => 'color: {{VALUE}};',
			],
		] );
		$this->add_control( 'eael_field_bg_color', [
			'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				"{{WRAPPER}} .lr-form-wrapper .eael-lr-form-control" => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => "eael_field_border",
			'selector' => "{{WRAPPER}} .lr-form-wrapper .eael-lr-form-control",
		] );
		$this->add_control( "eael_field_border_radius", [
			'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper .eael-lr-form-control" => $this->apply_dim( 'border-radius' ),
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( "tab_form_field_style_active", [
			'label' => __( 'Focus', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control( 'eael_field_placeholder_color_active', [
			'label'     => __( 'Placeholder Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .lr-form-wrapper input.eael-lr-form-control:focus::placeholder" => 'color: {{VALUE}};',
			],
		] );
		$this->add_control( 'eael_field_bg_color_active', [
			'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				"{{WRAPPER}} .lr-form-wrapper .eael-lr-form-control:focus" => 'background-color: {{VALUE}};',
			],
		] );
		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => "eael_field_border_focus",
			'selector' => "{{WRAPPER}} .lr-form-wrapper .eael-lr-form-control:focus",
		] );
		$this->add_control( "eael_field_border_radius_focus", [
			'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper .eael-lr-form-control:focus" => $this->apply_dim( 'border-radius' ),
			],
		] );
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	protected function init_style_input_labels_controls() {
		$this->start_controls_section( 'section_style_form_labels', [
			'label' => __( 'Form Labels', 'essential-addons-for-elementor-lite' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );
		$this->add_control( 'eael_form_label_po_toggle', [
			'label'        => __( 'Spacing', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Default', 'essential-addons-for-elementor-lite' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',
		] );

		$this->start_popover();
		$this->add_responsive_control( "eael_form_label_margin", [
			'label'      => __( 'Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper .eael-field-label" => $this->apply_dim( 'margin' ),
			],
			'condition'  => [
				'eael_form_label_po_toggle' => 'yes',
			],
		] );
		$this->add_responsive_control( "eael_form_label_padding", [
			'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper .eael-field-label" => $this->apply_dim( 'padding' ),
			],
			'condition'  => [
				'eael_form_label_po_toggle' => 'yes',
			],
		] );
		$this->end_popover();
		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => "eael_label_typography",
			'selector' => "{{WRAPPER}} .lr-form-wrapper .eael-field-label",
		] );

		$this->add_control( 'eael_form_label_c_po_toggle', [
			'label'        => __( 'Colors', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Default', 'essential-addons-for-elementor-lite' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',
		] );

		$this->start_popover();
		$this->add_control( 'eael_label_color', [
			'label'     => __( 'Text Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .lr-form-wrapper .eael-field-label" => 'color: {{VALUE}};',
			],
			'condition' => [
				'eael_form_label_c_po_toggle' => 'yes',
			],
		] );
		$this->add_control( 'eael_label_bg_color', [
			'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				"{{WRAPPER}} .lr-form-wrapper .eael-field-label" => 'background-color: {{VALUE}};',
			],
			'condition' => [
				'eael_form_label_c_po_toggle' => 'yes',
			],
		] );
		$this->end_popover();

		$this->add_control( 'eael_form_label_b_po_toggle', [
			'label'        => __( 'Border', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Default', 'essential-addons-for-elementor-lite' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',
		] );

		$this->start_popover();
		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'      => "eael_label_border",
			'selector'  => "{{WRAPPER}} .lr-form-wrapper .eael-field-label",
			'condition' => [
				'eael_form_label_b_po_toggle' => 'yes',
			],
		] );
		$this->add_control( "eael_label_border_radius", [
			'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper .eael-field-label" => $this->apply_dim( 'border-radius' ),
			],
			'condition'  => [
				'eael_form_label_b_po_toggle' => 'yes',
			],
		] );
		$this->end_popover();

		$this->add_control( 'rmark_po_toggle', [
			'label'     => __( 'Required Mark Style', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::POPOVER_TOGGLE,
			'condition' => [
				'show_labels'   => 'yes',
				'mark_required' => 'yes',
			],
		] );

		$this->start_popover();
		$this->add_control( 'rmark_sign', [
			'label'       => __( 'Mark Sign', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '*',
			'placeholder' => 'Enter * or (required) etc.',
			'selectors'   => [
				"{{WRAPPER}} .eael-lr-form-wrapper .eael-lr-form-group label.mark-required:after" => 'content: "{{VALUE}}";',
			],
			'condition'   => [
				'rmark_po_toggle' => 'yes',
			],
		] );
		$this->add_control( "rmark_size", [
			'label'      => esc_html__( 'Size', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'rem',
				'%',
			],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				],
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-lr-form-wrapper .eael-lr-form-group label.mark-required:after" => 'font-size: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'rmark_po_toggle' => 'yes',
			],
		] );
		$this->add_control( "rmakr_color", [
			'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .eael-lr-form-wrapper .eael-lr-form-group label.mark-required:after" => 'color: {{VALUE}};',
			],
			'condition' => [
				'rmark_po_toggle' => 'yes',
			],
		] );

		$this->add_responsive_control( "rmark_valign", [
			'label'     => esc_html__( 'Vertical Alignment', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min'  => - 50,
					'max'  => 50,
					'step' => 0,
				],
			],
			'default'   => [
				'unit' => 'px',
				'size' => 17,
			],
			'selectors' => [
				"{{WRAPPER}} .eael-lr-form-wrapper .eael-lr-form-group label.mark-required:after" => 'top: {{SIZE}}px;',
			],
			'condition' => [
				'rmark_po_toggle' => 'yes',
			],
		] );
		$this->add_responsive_control( "rmark_halign", [
			'label'     => esc_html__( 'Horizontal Alignment', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min'  => - 50,
					'max'  => 50,
					'step' => 0,
				],
			],
			'default'   => [
				'unit' => 'px',
				'size' => - 10,
			],
			'selectors' => [
				"{{WRAPPER}} .eael-lr-form-wrapper .eael-lr-form-group label.mark-required:after" => 'right: {{SIZE}}px;',
			],
			'condition' => [
				'rmark_po_toggle' => 'yes',
			],
		] );

		$this->end_popover();
		$this->add_control( 'lpv_po_toggle', [
			'label'     => __( 'Password Visibility Style', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::POPOVER_TOGGLE,
			'condition' => [
				'password_toggle' => 'yes',
			],
		] );
		$this->start_popover();

		$this->add_responsive_control( "lpv_size", [
			'label'      => esc_html__( 'Icon Size', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'rem',
				'%',
			],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				],
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-lr-form-wrapper .eael-lr-form-group .dashicons" => 'font-size: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
			],
			'condition'  => [
				'lpv_po_toggle' => 'yes',
			],
		] );
		$this->add_control( "lvp_open_color", [
			'label'     => __( 'Open Eye Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .eael-lr-form-wrapper .eael-lr-form-group .dashicons-visibility" => 'color: {{VALUE}};',
			],
			'condition' => [
				'lpv_po_toggle' => 'yes',
			],
		] );
		$this->add_control( "lvp_close_color", [
			'label'     => __( 'Close Eye Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .eael-lr-form-wrapper .eael-lr-form-group .dashicons-hidden" => 'color: {{VALUE}};',
			],
			'condition' => [
				'lpv_po_toggle' => 'yes',
			],
		] );

		$this->add_responsive_control( "lpv_valign", [
			'label'     => esc_html__( 'Vertical Alignment', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min'  => - 50,
					'max'  => 50,
					'step' => 1,
				],
			],
			'default'   => [
				'unit' => 'px',
				'size' => 0.73,
			],
			'selectors' => [
				"{{WRAPPER}} .eael-lr-form-wrapper .eael-lr-form-group .wp-hide-pw" => 'top: {{SIZE}}px;',
			],
			'condition' => [
				'lpv_po_toggle' => 'yes',
			],
		] );
		$this->add_responsive_control( "lpv_halign", [
			'label'     => esc_html__( 'Horizontal Alignment', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min'  => - 50,
					'max'  => 50,
					'step' => 1,
				],
			],
			'default'   => [
				'unit' => 'px',
				'size' => - 27,
			],
			'selectors' => [
				"{{WRAPPER}} .eael-lr-form-wrapper .eael-lr-form-group .wp-hide-pw" => 'right: {{SIZE}}px;',
			],
			'condition' => [
				'lpv_po_toggle' => 'yes',
			],
		] );

		$this->end_popover();

		//Remember Me Style
		$this->add_control( 'eael_form_rm_fields_heading', [
			'type'      => Controls_Manager::HEADING,
			'label'     => __( 'Remember Me Field', 'essential-addons-for-elementor-lite' ),
			'separator' => 'before',
		] );
		$this->add_control( 'remember_me_style_pot', [
			'label'        => __( 'Remember Me Style', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Default', 'essential-addons-for-elementor-lite' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',
			'condition'    => [
				'login_show_remember_me' => 'yes',
			],
		] );

		$this->start_popover();
		$this->add_control( 'remember_me_style', [
			'label'     => __( 'Style', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'lr-checkbox',
			'options'   => [
				'lr-checkbox' => __( 'Checkbox', 'essential-addons-for-elementor-lite' ),
				'lr-toggle'   => __( 'Toggle', 'essential-addons-for-elementor-lite' ),
			],
			'condition' => [
				'remember_me_style_pot' => 'yes',
			],
			'separator' => 'before',
		] );

		$this->add_responsive_control( "eael_form_rm_field_margin", [
			'label'      => __( 'Container Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper .eael-forever-forget" => $this->apply_dim( 'margin' ),
			],
			'condition'  => [
				'remember_me_style_pot' => 'yes',
			],
		] );
		$this->add_responsive_control( "eael_form_rm_field_padding", [
			'label'      => __( 'Container Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper .eael-forever-forget" => $this->apply_dim( 'padding' ),
			],
			'condition'  => [
				'remember_me_style_pot' => 'yes',
			],
		] );
		$this->add_responsive_control( "eael_form_rm_lbl_margin", [
			'label'      => __( 'Label Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper .forget-menot" => $this->apply_dim( 'margin' ),
			],
			'condition'  => [
				'remember_me_style_pot' => 'yes',
			],
		] );
		$this->add_responsive_control( "eael_form_rm_lbl_padding", [
			'label'      => __( 'Label Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper .forget-menot" => $this->apply_dim( 'padding' ),
			],
			'condition'  => [
				'remember_me_style_pot' => 'yes',
			],
		] );

		$this->add_control( 'eael_rm_label_color', [
			'label'     => __( 'Text Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .lr-form-wrapper .forget-menot" => 'color: {{VALUE}};',
			],
			'condition' => [
				'remember_me_style_pot' => 'yes',
			],
		] );
		$this->add_control( 'eael_rm_label_bg_color', [
			'label'     => __( 'Text Background', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				"{{WRAPPER}} .lr-form-wrapper .forget-menot" => 'background-color: {{VALUE}};',
			],
			'condition' => [
				'remember_me_style_pot' => 'yes',
			],
		] );
		$this->add_control( 'eael_rm_checkbox_color', [
			'label'     => __( 'Checkbox | Toggle Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .lr-form-wrapper .forget-menot  input[type=checkbox]:checked" => 'border-color: {{VALUE}};background: {{VALUE}};',
			],
			'condition' => [
				'remember_me_style_pot' => 'yes',
			],
		] );
		$this->end_popover();
		$this->add_group_control( Group_Control_Typography::get_type(), [
			'label'    => __( 'Remember Me Typography', 'essential-addons-for-elementor-lite' ),
			'name'     => "eael_rm_label_typography",
			'selector' => "{{WRAPPER}} .lr-form-wrapper .forget-menot",
		] );
		$this->end_controls_section();
	}

	protected function init_style_login_button_controls() {
		$this->_init_button_style( 'login' );
	}

	protected function init_style_register_button_controls() {
		$this->_init_button_style( 'register' );
	}

	protected function init_style_login_link_controls() {
		$this->_init_link_style( 'login' );
	}

	protected function init_style_register_link_controls() {
		$this->_init_link_style( 'register' );
	}

	protected function init_style_login_recaptcha_controls() {
		$this->_init_recaptcha_style( 'login' );
	}

	protected function init_style_register_recaptcha_controls() {
		$this->_init_recaptcha_style( 'register' );
	}

	/**
	 * Print style controls for a specific type of button.
	 *
	 * @param string $button_type the type of the button. accepts login or register.
	 */
	protected function _init_button_style( $button_type = 'login' ) {
		$this->start_controls_section( "section_style_{$button_type}_btn", [
			'label'      => sprintf( __( '%s Button', 'essential-addons-for-elementor-lite' ), ucfirst( $button_type ) ),
			'tab'        => Controls_Manager::TAB_STYLE,
			'conditions' => $this->get_form_controls_display_condition( $button_type ),
		] );
		$this->add_control( "{$button_type}_btn_pot", [
			'label'        => __( 'Spacing', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Default', 'essential-addons-for-elementor-lite' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',
		] );
		$this->start_popover();
		$this->add_responsive_control( "{$button_type}_btn_margin", [
			'label'      => __( 'Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-{$button_type}-form .eael-lr-btn" => $this->apply_dim( 'margin' ),
			],
			'condition'  => [
				"{$button_type}_btn_pot" => 'yes',
			],
		] );
		$this->add_responsive_control( "{$button_type}_btn_padding", [
			'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-{$button_type}-form .eael-lr-btn" => $this->apply_dim( 'padding' ),
			],
			'condition'  => [
				"{$button_type}_btn_pot" => 'yes',
			],
		] );
		$this->end_popover();
		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => "{$button_type}_btn_typography",
			'selector' => "{{WRAPPER}} .eael-{$button_type}-form .eael-lr-btn",
		] );
		$this->add_responsive_control( "{$button_type}_btn_d_type", [
			'label'     => __( 'Display as', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'row'    => __( 'Inline', 'essential-addons-for-elementor-lite' ),
				'column' => __( 'Block', 'essential-addons-for-elementor-lite' ),
			],
			'default'   => 'row',
			'selectors' => [
				"{{WRAPPER}} .eael-{$button_type}-form .eael-lr-footer"    => 'flex-direction: {{VALUE}};',
				"{{WRAPPER}} .eael-{$button_type}-form .eael-sign-wrapper" => 'padding-top: 0;',
			],
		] );


		$this->add_responsive_control( "{$button_type}_btn_jc", [
			'label'     => __( 'Justify Content', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'flex-start'    => __( 'Start', 'essential-addons-for-elementor-lite' ),
				'flex-end'      => __( 'End', 'essential-addons-for-elementor-lite' ),
				'center'        => __( 'Center', 'essential-addons-for-elementor-lite' ),
				'space-between' => __( 'Space Between', 'essential-addons-for-elementor-lite' ),
				'space-around'  => __( 'Space Around', 'essential-addons-for-elementor-lite' ),
				'space-evenly'  => __( 'Space Evenly', 'essential-addons-for-elementor-lite' ),
			],
			'default'   => 'space-between',
			'condition' => [
				"{$button_type}_btn_d_type" => 'row',
			],
			'selectors' => [
				"{{WRAPPER}} .eael-{$button_type}-form .eael-lr-footer" => 'justify-content: {{VALUE}};',
			],
		] );
		$this->add_responsive_control( "{$button_type}_btn_align", [
			'label'     => __( 'Alignment', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'mr-auto'         => [
					'title' => __( 'Left', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-h-align-left',
				],
				'ml-auto mr-auto' => [
					'title' => __( 'Center', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-h-align-center',
				],
				'ml-auto'         => [
					'title' => __( 'Right', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-h-align-right',
				],
			],
			'default'   => '',
			'condition' => [
				"{$button_type}_btn_d_type" => 'column',
			],
		] );
		$this->add_control( "tabs_{$button_type}_btn_colors_heading", [
			'type'      => Controls_Manager::HEADING,
			'label'     => __( 'Colors & Border', 'essential-addons-for-elementor-lite' ),
			'separator' => 'before',
		] );

		$this->start_controls_tabs( "tabs_{$button_type}_btn_style" );
		/*-----Login Button NORMAL state------ */
		$this->start_controls_tab( "tab_{$button_type}_btn_normal", [
			'label' => __( 'Normal', 'essential-addons-for-elementor-lite' ),
		] );
		$this->add_control( "{$button_type}_btn_color", [
			'label'     => __( 'Text Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .eael-{$button_type}-form .eael-lr-btn" => 'color: {{VALUE}};',
			],
		] );
		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => "{$button_type}_btn_bg_color",
			'label'    => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
			'types'    => [
				'classic',
				'gradient',
			],
			'selector' => "{{WRAPPER}} .eael-{$button_type}-form .eael-lr-btn",
		] );
		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => "{$button_type}_btn_border",
			'selector' => "{{WRAPPER}} .eael-{$button_type}-form .eael-lr-btn",
		] );
		$this->add_control( "{$button_type}_btn_border_radius", [
			'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-{$button_type}-form .eael-lr-btn" => $this->apply_dim( 'border-radius' ),
			],
		] );
		$this->end_controls_tab();

		/*-----Login Button HOVER state------ */
		$this->start_controls_tab( "tab_{$button_type}_button_hover", [
			'label' => __( 'Hover', 'essential-addons-for-elementor-lite' ),
		] );
		$this->add_control( "{$button_type}_button_color_hover", [
			'label'     => __( 'Text Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .eael-{$button_type}-form .eael-lr-btn:hover" => 'color: {{VALUE}};',
			],
		] );
		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => "{$button_type}_btn_bg_color_hover",
			'label'    => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
			'types'    => [
				'classic',
				'gradient',
			],
			'selector' => "{{WRAPPER}} .eael-{$button_type}-form .eael-lr-btn:hover",
		] );
		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => "{$button_type}_btn_border_hover",
			'selector' => "{{WRAPPER}} .eael-{$button_type}-form .eael-lr-btn:hover",
		] );
		$this->add_control( "{$button_type}_btn_border_radius_hover", [
			'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-{$button_type}-form .eael-lr-btn:hover" => $this->apply_dim( 'border-radius' ),
			],
		] );
		$this->end_controls_tab();
		$this->end_controls_tabs();
		/*-----ends Button tabs--------*/

		$this->add_responsive_control( "{$button_type}_btn_width", [
			'label'      => esc_html__( 'Button width', 'essential-addons-for-elementor-lite' ),
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
				"{{WRAPPER}} .eael-{$button_type}-form .eael-lr-btn" => 'width: {{SIZE}}{{UNIT}};',
			],
			'separator'  => 'before',
		] );
		$this->add_responsive_control( "{$button_type}_btn_height", [
			'label'      => esc_html__( 'Button Height', 'essential-addons-for-elementor-lite' ),
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
				"{{WRAPPER}} .eael-{$button_type}-form .eael-lr-btn" => 'height: {{SIZE}}{{UNIT}};',
			],
		] );
		$this->end_controls_section();
	}

	/**
	 * Print style controls for a specific type of reCAPTCHA.
	 *
	 * @param string $form_type the type of the reCAPTCHA. accepts login or register.
	 */
	protected function _init_recaptcha_style( $form_type = 'login' ) {
		$this->start_controls_section( "section_style_{$form_type}_rc", [
			'label'     => sprintf( __( '%s Form reCAPTCHA', 'essential-addons-for-elementor-lite' ), ucfirst( $form_type ) ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				"enable_{$form_type}_recaptcha" => 'yes',
			],
		] );
		$this->add_responsive_control( "{$form_type}_rc_margin", [
			'label'      => __( 'Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-{$form_type}-form .eael-recaptcha-wrapper" => $this->apply_dim( 'margin' ),
			],

		] );

		$this->add_control( "{$form_type}_rc_theme", [
			'label'   => __( 'Theme', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'light' => __( 'Light', 'essential-addons-for-elementor-lite' ),
				'dark'  => __( 'Dark', 'essential-addons-for-elementor-lite' ),
			],
			'default' => 'light',
		] );

		$this->add_control( "{$form_type}_rc_size", [
			'label'   => __( 'Size', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'normal'  => __( 'Normal', 'essential-addons-for-elementor-lite' ),
				'compact' => __( 'Compact', 'essential-addons-for-elementor-lite' ),
			],
			'default' => 'normal',
		] );

		$this->end_controls_section();
	}

	/**
	 * Print style controls for a specific type of link on register or login form.
	 *
	 * @param string $form_type the type of form where the link is being shown. accepts login or register.
	 */
	protected function _init_link_style( $form_type = 'login' ) {
		$form_name = 'login' === $form_type ? __( 'Register', 'essential-addons-for-elementor-lite' ) : __( 'Login', 'essential-addons-for-elementor-lite' );
		$this->start_controls_section( "section_style_{$form_type}_link", [
			'label'     => sprintf( __( '%s Link', 'essential-addons-for-elementor-lite' ), ucfirst( $form_name ) ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				"show_{$form_type}_link" => 'yes',
			],
		] );
		$this->add_control( "{$form_type}_link_style_notice", [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => sprintf( __( 'Here you can style the %s link displayed on the %s Form', 'essential-addons-for-elementor-lite' ), $form_name, ucfirst( $form_type ) ),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
		] );
		$this->add_control( "{$form_type}_link_pot", [
			'label'        => __( 'Spacing', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Default', 'essential-addons-for-elementor-lite' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',
		] );
		$this->start_popover();
		$this->add_responsive_control( "{$form_type}_link_margin", [
			'label'      => __( 'Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-{$form_type}-form .eael-lr-link" => $this->apply_dim( 'margin' ),
			],
			'condition'  => [
				"{$form_type}_link_pot" => 'yes',
			],
		] );
		$this->add_responsive_control( "{$form_type}_link_padding", [
			'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-{$form_type}-form .eael-lr-link" => $this->apply_dim( 'padding' ),
			],
			'condition'  => [
				"{$form_type}_link_pot" => 'yes',
			],
		] );
		$this->end_popover();
		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => "{$form_type}_link_typography",
			'selector' => "{{WRAPPER}} .eael-{$form_type}-form .eael-lr-link",
		] );

		$this->add_responsive_control( "{$form_type}_link_d_type", [
			'label'     => __( 'Display as', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'row'    => __( 'Inline', 'essential-addons-for-elementor-lite' ),
				'column' => __( 'Block', 'essential-addons-for-elementor-lite' ),
			],
			'default'   => 'row',
			'selectors' => [
				"{{WRAPPER}} .eael-{$form_type}-form .eael-sign-wrapper" => 'display:flex; flex-direction: {{VALUE}};',
			],
		] );


		$this->add_responsive_control( "{$form_type}_link_jc", [
			'label'     => __( 'Justify Content', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'flex-start'    => __( 'Start', 'essential-addons-for-elementor-lite' ),
				'flex-end'      => __( 'End', 'essential-addons-for-elementor-lite' ),
				'center'        => __( 'Center', 'essential-addons-for-elementor-lite' ),
				'space-between' => __( 'Space Between', 'essential-addons-for-elementor-lite' ),
				'space-around'  => __( 'Space Around', 'essential-addons-for-elementor-lite' ),
				'space-evenly'  => __( 'Space Evenly', 'essential-addons-for-elementor-lite' ),
			],
			'default'   => 'center',
			'condition' => [
				"{$form_type}_link_d_type" => 'row',
			],
			'selectors' => [
				"{{WRAPPER}} .eael-{$form_type}-form .eael-sign-wrapper" => 'justify-content: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( "{$form_type}_link_ai", [
			'label'     => __( 'Align Items', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'flex-start'   => __( 'Start', 'essential-addons-for-elementor-lite' ),
				'flex-end'     => __( 'End', 'essential-addons-for-elementor-lite' ),
				'center'       => __( 'Center', 'essential-addons-for-elementor-lite' ),
				'stretch'      => __( 'Stretch', 'essential-addons-for-elementor-lite' ),
				'baseline'     => __( 'Baseline', 'essential-addons-for-elementor-lite' ),
				'space-evenly' => __( 'Space Evenly', 'essential-addons-for-elementor-lite' ),
			],
			'default'   => 'center',
			'condition' => [
				"{$form_type}_link_d_type" => 'column',
			],
			'selectors' => [
				"{{WRAPPER}} .eael-{$form_type}-form .eael-sign-wrapper" => 'align-items: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( "{$form_type}_link_align", [
			'label'     => __( 'Alignment', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'mr-auto'         => [
					'title' => __( 'Left', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-h-align-left',
				],
				'ml-auto mr-auto' => [
					'title' => __( 'Center', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-h-align-center',
				],
				'ml-auto'         => [
					'title' => __( 'Right', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-h-align-right',
				],
			],
			'default'   => '',
			'condition' => [
				"{$form_type}_link_d_type" => 'column',
			],
		] );

		$this->add_control( "tabs_{$form_type}_link_colors_heading", [
			'type'      => Controls_Manager::HEADING,
			'label'     => __( 'Colors & Border', 'essential-addons-for-elementor-lite' ),
			'separator' => 'before',
		] );

		$this->start_controls_tabs( "tabs_{$form_type}_link_style" );
		/*----- Link NORMAL state------ */
		$this->start_controls_tab( "tab_{$form_type}_link_normal", [
			'label' => __( 'Normal', 'essential-addons-for-elementor-lite' ),
		] );
		$this->add_control( "{$form_type}_link_color", [
			'label'     => __( 'Text Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .eael-{$form_type}-form .eael-lr-link" => 'color: {{VALUE}};',
			],
		] );
		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => "{$form_type}_link_bg_color",
			'label'    => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
			'types'    => [
				'classic',
				'gradient',
			],
			'selector' => "{{WRAPPER}} .eael-{$form_type}-form .eael-lr-link",
		] );
		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => "{$form_type}_link_border",
			'selector' => "{{WRAPPER}} .eael-{$form_type}-form .eael-lr-link",
		] );
		$this->add_control( "{$form_type}_link_border_radius", [
			'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-{$form_type}-form .eael-lr-link" => $this->apply_dim( 'border-radius' ),
			],
		] );
		$this->end_controls_tab();

		/*-----Link HOVER state------ */
		$this->start_controls_tab( "tab_{$form_type}_link_hover", [
			'label' => __( 'Hover', 'essential-addons-for-elementor-lite' ),
		] );
		$this->add_control( "{$form_type}_link_color_hover", [
			'label'     => __( 'Text Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .eael-{$form_type}-form .eael-lr-link:hover" => 'color: {{VALUE}};',
			],
		] );
		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => "{$form_type}_link_bg_color_hover",
			'label'    => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
			'types'    => [
				'classic',
				'gradient',
			],
			'selector' => "{{WRAPPER}} .eael-{$form_type}-form .eael-lr-link:hover",
		] );
		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => "{$form_type}_link_border_hover",
			'selector' => "{{WRAPPER}} .eael-{$form_type}-form .eael-lr-link:hover",
		] );
		$this->add_control( "{$form_type}_link_border_radius_hover", [
			'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .eael-{$form_type}-form .eael-lr-link:hover" => $this->apply_dim( 'border-radius' ),
			],
		] );
		$this->end_controls_tab();
		$this->end_controls_tabs();
		/*-----ends Link tabs--------*/
		$this->add_responsive_control( "{$form_type}_link_wrap_width", [
			'label'      => esc_html__( 'Link Container width', 'essential-addons-for-elementor-lite' ),
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
				"{{WRAPPER}} .eael-{$form_type}-form .eael-sign-wrapper" => 'width: {{SIZE}}{{UNIT}};',
			],
			'separator'  => 'before',
		] );
		$this->add_responsive_control( "{$form_type}_link_width", [
			'label'      => esc_html__( 'Link width', 'essential-addons-for-elementor-lite' ),
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
				"{{WRAPPER}} .eael-{$form_type}-form .eael-lr-link" => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( "{$form_type}_link_height", [
			'label'      => esc_html__( 'Link Height', 'essential-addons-for-elementor-lite' ),
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
				"{{WRAPPER}} .eael-{$form_type}-form .eael-lr-link" => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	/**
	 * Get conditions for displaying login form and registration
	 *
	 * @param string $type
	 *
	 * @return array
	 */
	public function get_form_controls_display_condition( $type = 'login' ) {
		$form_type = in_array( $type, [
			'login',
			'register',
		] ) ? $type : 'login';

		return [
			'relation' => 'or',
			'terms'    => [
				[
					'name'  => "show_{$form_type}_link",
					'value' => 'yes',
				],
				[
					'name'  => 'default_form_type',
					'value' => $form_type,
				],
			],
		];
	}

	protected function render() {
		if ( ! is_admin() && 'yes' === $this->get_settings_for_display( 'redirect_for_logged_in_user' ) && is_user_logged_in() ) {
			if ( $redirect = $this->get_settings_for_display( 'redirect_url_for_logged_in_user' )['url'] ) {
				$redirect = wp_sanitize_redirect( $redirect );
				$logged_in_location = wp_validate_redirect( $redirect, site_url() ); ?>
                <div class="" data-logged-in-location="<?php echo empty( $logged_in_location ) ? '' : esc_url( $logged_in_location ); ?>"></div>
				<?php
			}
		}

		//Note. forms are handled in Login_Registration Trait used in the Bootstrap class.
		if ( ! $this->in_editor && 'yes' === $this->get_settings_for_display( 'hide_for_logged_in_user' ) && is_user_logged_in() ) {
			return; // do not show any form for already logged in user. but let edit on editor
		}

		$this->ds                      = $this->get_settings_for_display();
		$this->default_form            = $this->get_settings_for_display( 'default_form_type' );
		$this->should_print_login_form = ( 'login' === $this->default_form || 'yes' === $this->get_settings_for_display( 'show_login_link' ) );

		$this->should_print_register_form = ( $this->user_can_register && ( 'register' === $this->get_settings_for_display( 'default_form_type' ) || 'yes' === $this->get_settings_for_display( 'show_register_link' ) ) );
		if ( Plugin::$instance->documents->get_current() ) {
			$this->page_id = Plugin::$instance->documents->get_current()->get_main_id();
		}


		//handle form illustration
		$form_image_id               = ! empty( $this->ds['lr_form_image']['id'] ) ? $this->ds['lr_form_image']['id'] : '';
		$this->form_illustration_pos = ! empty( $this->ds['lr_form_image_position'] ) ? $this->ds['lr_form_image_position'] : 'left';
		$this->form_illustration_url = Group_Control_Image_Size::get_attachment_image_src( $form_image_id, 'lr_form_image', $this->ds );

		$form_logo_id        = ! empty( $this->ds['lr_form_logo']['id'] ) ? $this->ds['lr_form_logo']['id'] : '';
		$this->form_logo     = Group_Control_Image_Size::get_attachment_image_src( $form_logo_id, 'lr_form_logo', $this->ds );
		$this->form_logo_pos = ! empty( $this->ds['lr_form_logo_position'] ) ? $this->ds['lr_form_logo_position'] : 'inline';
		$login_redirect_url = '';
		if ( ! empty( $this->ds['redirect_after_login'] ) && 'yes' === $this->ds['redirect_after_login'] ) {
			$login_redirect_url = !empty( $this->ds[ 'redirect_url' ][ 'url' ] ) ? esc_url( $this->ds[ 'redirect_url' ][ 'url' ] ) : '';
		}
		?>
        <div class="eael-login-registration-wrapper <?php echo empty( $form_image_id ) ? '' : esc_attr( 'has-illustration' ); ?>"
             data-is-ajax="<?php echo esc_attr( $this->get_settings_for_display( 'enable_ajax' ) ); ?>"
             data-widget-id="<?php echo esc_attr( $this->get_id() ); ?>"
             data-recaptcha-sitekey="<?php echo esc_attr( get_option( 'eael_recaptcha_sitekey' ) ); ?>"
             data-redirect-to="<?php echo esc_attr( $login_redirect_url ); ?>"
        >
			<?php
			$this->print_login_form();
			$this->print_register_form();
			?>
        </div>

		<?php
	}

	protected function print_login_form() {
		if ( $this->should_print_login_form ) {
			// prepare all login form related vars
			$default_hide_class = 'register' === $this->default_form || isset($_GET['eael-register']) ? 'eael-lr-d-none' : '';

			//Reg link related
			$reg_link_action = ! empty( $this->ds['registration_link_action'] ) ? $this->ds['registration_link_action'] : 'form';
			$show_reg_link   = ( $this->user_can_register && 'yes' === $this->get_settings( 'show_register_link' ) );
			$reg_link_text   = ! empty( $this->get_settings( 'registration_link_text' ) ) ? HelperCLass::eael_wp_kses($this->get_settings( 'registration_link_text' )) : __( 'Register', 'essential-addons-for-elementor-lite' );
			$parts           = explode( "\n", $reg_link_text );
			$reg_link_text   = array_pop( $parts );
			$reg_message     = array_shift( $parts );

			$reg_link_placeholder = '<span class="d-ib">%1$s</span> <a href="%2$s" id="eael-lr-reg-toggle" class="eael-lr-link" data-action="%3$s" %5$s>%4$s</a>';
			$reg_atts             = $reg_url = '';
			switch ( $reg_link_action ) {
				case 'custom':
					$reg_url  = ! empty( $this->ds['custom_register_url']['url'] ) ? $this->ds['custom_register_url']['url'] : '';
					$reg_atts = ! empty( $this->ds['custom_register_url']['is_external'] ) ? ' target="_blank"' : '';
					$reg_atts .= ! empty( $this->ds['custom_register_url']['nofollow'] ) ? ' rel="nofollow"' : '';
					break;
				case 'default':
					$reg_url = wp_registration_url();
					break;
			}

			$reg_link = sprintf( $reg_link_placeholder, $reg_message, esc_attr( $reg_url ), esc_attr( $reg_link_action ), $reg_link_text, $reg_atts );


			// login form fields related
			$label_type      = ! empty( $this->ds['login_label_types'] ) ? $this->ds['login_label_types'] : 'default';
			$is_custom_label = ( 'custom' === $label_type );
			$display_label   = ( 'none' !== $label_type );

			//Default label n placeholder
			$u_label = $u_ph = __( 'Username or Email Address', 'essential-addons-for-elementor-lite' );
			$p_label = $p_ph = __( 'Password', 'essential-addons-for-elementor-lite' );
			// custom label n placeholder
			if ( $is_custom_label ) {
				$u_label = isset( $this->ds['login_user_label'] ) ? $this->ds['login_user_label'] : '';
				$p_label = isset( $this->ds['login_password_label'] ) ? $this->ds['login_password_label'] : '';
				$u_ph    = isset( $this->ds['login_user_placeholder'] ) ? $this->ds['login_user_placeholder'] : '';
				$p_ph    = isset( $this->ds['login_password_placeholder'] ) ? $this->ds['login_password_placeholder'] : '';
			}


			$btn_text         = ! empty( $this->ds['login_button_text'] ) ? $this->ds['login_button_text'] : '';
			$show_logout_link = ( ! empty( $this->ds['show_log_out_message'] ) && 'yes' === $this->ds['show_log_out_message'] );
			$show_rememberme  = ( ! empty( $this->ds['login_show_remember_me'] ) && 'yes' === $this->ds['login_show_remember_me'] );
			$remember_text         = isset( $this->ds['remember_text'] ) ? $this->ds['remember_text'] : esc_html__( 'Remember Me', 'essential-addons-for-elementor-lite');
			$rm_type          = ! empty( $this->ds['remember_me_style'] ) ? $this->ds['remember_me_style'] : '';
			$show_pv_icon     = ( ! empty( $this->ds['password_toggle'] ) && 'yes' === $this->ds['password_toggle'] );

			//Loss password
			$show_lp = ( ! empty( $this->ds['show_lost_password'] ) && 'yes' === $this->ds['show_lost_password'] );
			$lp_text = ! empty( $this->ds['lost_password_text'] ) ? HelperCLass::eael_wp_kses($this->ds['lost_password_text']) : __( 'Forgot password?', 'essential-addons-for-elementor-lite' );
			$lp_link = sprintf( '<a href="%s">%s</a>', esc_attr( wp_lostpassword_url() ), $lp_text );
			if ( ! empty( $this->ds['lost_password_link_type'] ) && 'custom' === $this->ds['lost_password_link_type'] ) {
				$lp_url  = ! empty( $this->ds['lost_password_url']['url'] ) ? $this->ds['lost_password_url']['url'] : wp_lostpassword_url();
				$lp_atts = ! empty( $this->ds['lost_password_url']['is_external'] ) ? ' target="_blank"' : '';
				$lp_atts .= ! empty( $this->ds['lost_password_url']['nofollow'] ) ? ' rel="nofollow"' : '';
				$lp_link = sprintf( '<a href="%s" %s >%s</a>', esc_attr( $lp_url ), $lp_atts, $lp_text );
			}

			// btn alignment
			$btn_align = isset( $this->ds['login_btn_align'] ) ? $this->ds['login_btn_align'] : '';
			// btn alignment
			$link_align = isset( $this->ds['login_link_align'] ) ? $this->ds['login_link_align'] : '';
			// reCAPTCHA style
			$rc_theme = isset( $this->ds['login_rc_theme'] ) ? $this->ds['login_rc_theme'] : 'light';
			$rc_size  = isset( $this->ds['login_rc_size'] ) ? $this->ds['login_rc_size'] : 'normal';
			// input icons
			$show_icon  = ( $this->pro_enabled && ! empty( $this->ds['show_login_icon'] ) && 'yes' === $this->ds['show_login_icon'] );
			$icon_class = $show_icon ? 'lr-icon-showing' : '';
			?>
            <section
                    id="eael-login-form-wrapper"
                    class="<?php echo esc_attr( $default_hide_class ); ?>"
                    data-recaptcha-theme="<?php echo esc_attr( $rc_theme ); ?>"
                    data-recaptcha-size="<?php echo esc_attr( $rc_size ); ?>">
                <div class="eael-login-form-wrapper eael-lr-form-wrapper style-2 <?php echo esc_attr( $icon_class ); ?>">
					<?php
					if ( $show_logout_link && is_user_logged_in() && ! $this->in_editor ) {
						/* translators: %s user display name */
						$logged_in_msg = sprintf( __( 'You are already logged in as %s. ', 'essential-addons-for-elementor-lite' ), wp_get_current_user()->display_name );
						printf( '%1$s   (<a href="%2$s">%3$s</a>)', $logged_in_msg, esc_url( wp_logout_url() ), __( 'Logout', 'essential-addons-for-elementor-lite' ) );
					} else {
						if ( 'left' === $this->form_illustration_pos ) {
							$this->print_form_illustration();
						}
						?>
                        <div class="lr-form-wrapper">
							<?php $this->print_form_header( 'login' ); ?>
							<?php do_action( 'eael/login-register/before-login-form', $this ); ?>
                            <form class="eael-login-form eael-lr-form"
                                  id="eael-login-form"
                                  method="post">
								<?php do_action( 'eael/login-register/after-login-form-open', $this ); ?>
                                <div class="eael-lr-form-group">
									<?php if ( $display_label && $u_label ) {
										printf( '<label for="eael-user-login" class="eael-field-label">%s</label>', $u_label );
									} ?>
                                    <input type="text"
                                           name="eael-user-login"
                                           id="eael-user-login"
                                           class="eael-lr-form-control"
                                           aria-describedby="emailHelp"
                                           placeholder="<?php if ( $display_label && $u_ph ) {
										       echo esc_attr( $u_ph );
									       } ?>"
                                           required>
									<?php
									if ( $show_icon ) {
										echo '<i class="fas fa-user"></i>';
									} ?>
                                </div>
                                <div class="eael-lr-form-group">
									<?php if ( $display_label && $p_label ) {
										printf( '<label for="eael-user-password" class="eael-field-label">%s</label>', $p_label );
									} ?>
                                    <div class="eael-lr-password-wrapper">
                                        <input type="password"
                                               name="eael-user-password"
                                               class="eael-lr-form-control"
                                               id="eael-user-password"
                                               placeholder="<?php if ( $display_label && $p_ph ) {
											       echo esc_attr( $p_ph );
										       } ?>"
                                               required>
										<?php if ( $show_pv_icon ) { ?>
                                            <button type="button"
                                                    id="wp-hide-pw"
                                                    class="wp-hide-pw hide-if-no-js"
                                                    aria-label="Show password">
                                                <span class="dashicons dashicons-visibility"
                                                      aria-hidden="true"></span>
                                            </button>
										<?php } ?>
										<?php
										if ( $show_icon ) {
											echo '<i class="fas fa-lock"></i>';
										} ?>
                                    </div>
                                </div>
                                <div class="eael-forever-forget eael-lr-form-group">
									<?php if ( $show_rememberme && !empty( $remember_text )) { ?>
                                        <p class="forget-menot">
                                            <input name="eael-rememberme"
                                                   type="checkbox"
                                                   id="rememberme"
                                                   class="remember-me <?php echo esc_attr( $rm_type ); ?>"
                                                   value="forever">
                                            <label for="rememberme"
                                                   class="eael-checkbox-label rememberme"><?php echo esc_html( $remember_text ); ?></label>
                                        </p>
									<?php }
									if ( $show_lp ) {
										echo '<p class="forget-pass">' . $lp_link . '</p>';//XSS ok. already escaped
									} ?>

                                </div>

								<?php
								do_action( 'eael/login-register/before-recaptcha', $this );
								$this->print_recaptcha_node( 'login' );
								do_action( 'eael/login-register/after-recaptcha', $this );
								do_action( 'eael/login-register/before-login-footer', $this );
								?>


                                <div class="eael-lr-footer">
                                    <input type="submit"
                                           name="eael-login-submit"
                                           id="eael-login-submit"
                                           class="g-recaptcha eael-lr-btn eael-lr-btn-block <?php echo esc_attr( $btn_align ); ?>"
                                           value="<?php echo esc_attr( $btn_text ); ?>"/>
									<?php if ( $show_reg_link ) { ?>
                                        <div class="eael-sign-wrapper <?php echo esc_attr( $link_align ); ?>">
											<?php echo $reg_link; // XSS ok. already escaped ?>
                                        </div>
									<?php } ?>

                                </div>
								<?php do_action( 'eael/login-register/after-login-footer', $this );
								?>
                                <div class="eael-form-validation-container">
									<?php $this->print_login_validation_errors(); ?>
                                </div>
								<?php
								$this->print_necessary_hidden_fields( 'login' );

								$this->print_login_validation_errors();

								do_action( 'eael/login-register/before-login-form-close', $this );
								?>
                            </form>
							<?php do_action( 'eael/login-register/after-login-form', $this ); ?>
                        </div>
						<?php
						if ( 'right' === $this->form_illustration_pos ) {
							$this->print_form_illustration();
						}
					}
					?>
                </div>

            </section>
			<?php
		}
	}

	protected function print_register_form() {
		if ( $this->should_print_register_form ) {
			$default_hide_class = 'login' === $this->default_form && !isset($_GET['eael-register']) ? 'eael-lr-d-none' : ''; //eael-register flag for show error/success message when formal form submit
			$is_pass_valid      = false; // Does the form has a password field?
			$is_pass_confirmed  = false;
			// placeholders to flag if user use one type of field more than once.
			$email_exists        = 0;
			$user_name_exists    = 0;
			$password_exists     = 0;
			$confirm_pass_exists = 0;
			$first_name_exists   = 0;
			$last_name_exists    = 0;
			$website_exists      = 0;
			$f_labels            = [
				'email'            => __( 'Email', 'essential-addons-for-elementor-lite' ),
				'password'         => __( 'Password', 'essential-addons-for-elementor-lite' ),
				'confirm_password' => __( 'Confirm Password', 'essential-addons-for-elementor-lite' ),
				'user_name'        => __( 'Username', 'essential-addons-for-elementor-lite' ),
				'first_name'       => __( 'First Name', 'essential-addons-for-elementor-lite' ),
				'last_name'        => __( 'Last Name', 'essential-addons-for-elementor-lite' ),
				'website'          => __( 'Website', 'essential-addons-for-elementor-lite' ),
			];
			$repeated_f_labels   = [];


			//Login link related
			$lgn_link_action = ! empty( $this->ds['login_link_action'] ) ? $this->ds['login_link_action'] : 'form';
			$show_lgn_link   = 'yes' === $this->get_settings( 'show_login_link' );
			$lgn_link_text   = ! empty( $this->get_settings( 'login_link_text' ) ) ? HelperCLass::eael_wp_kses($this->get_settings( 'login_link_text' )) : __( 'Login', 'essential-addons-for-elementor-lite' );
			$btn_text        = ! empty( $this->ds['reg_button_text'] ) ? $this->ds['reg_button_text'] : '';

			$parts                = explode( "\n", $lgn_link_text );
			$lgn_link_text        = array_pop( $parts );
			$lgn_message          = array_shift( $parts );
			$lgn_link_placeholder = '<span class="d-ib">%1$s</span> <a href="%2$s" id="eael-lr-login-toggle" class="eael-lr-link" data-action="%3$s" %5$s>%4$s</a>';
			$lgn_url              = $lgn_atts = '';

			switch ( $lgn_link_action ) {
				case 'custom':
					$lgn_url  = ! empty( $this->ds['custom_login_url']['url'] ) ? $this->ds['custom_login_url']['url'] : '';
					$lgn_atts = ! empty( $this->ds['custom_login_url']['is_external'] ) ? ' target="_blank"' : '';
					$lgn_atts .= ! empty( $this->ds['custom_login_url']['nofollow'] ) ? ' rel="nofollow"' : '';
					break;
				case 'default':
					$lgn_url = wp_login_url();
					break;
			}
			$lgn_link = sprintf( $lgn_link_placeholder, $lgn_message, esc_attr( $lgn_url ), esc_attr( $lgn_link_action ), $lgn_link_text, $lgn_atts );

			// btn alignment
			$btn_align  = isset( $this->ds['register_btn_align'] ) ? $this->ds['register_btn_align'] : '';
			$link_align = isset( $this->ds['register_link_align'] ) ? $this->ds['register_link_align'] : '';
			// reCAPTCHA style
			$rc_theme = isset( $this->ds['register_rc_theme'] ) ? $this->ds['register_rc_theme'] : 'light';
			$rc_size  = isset( $this->ds['register_rc_size'] ) ? $this->ds['register_rc_size'] : 'normal';
			// input icons
			$show_icon  = ( $this->pro_enabled && ! empty( $this->ds['show_register_icon'] ) && 'yes' === $this->ds['show_register_icon'] );
			$icon_class = $show_icon ? 'lr-icon-showing' : '';
			ob_start();
			?>
            <section
                    id="eael-register-form-wrapper"
                    class="<?php echo esc_attr( $default_hide_class ); ?>"
                    data-recaptcha-theme="<?php echo esc_attr( $rc_theme ); ?>"
                    data-recaptcha-size="<?php echo esc_attr( $rc_size ); ?>">
                <div class="eael-register-form-wrapper eael-lr-form-wrapper style-2 <?php echo esc_attr( $icon_class ); ?>">
					<?php if ( 'left' === $this->form_illustration_pos ) {
						$this->print_form_illustration();
					} ?>
                    <div class="lr-form-wrapper">
						<?php
						$this->print_form_header( 'register' );
						do_action( 'eael/login-register/before-register-form', $this );
						?>
                        <form class="eael-register-form eael-lr-form"
                              id="eael-register-form"
                              method="post">
							<?php do_action( 'eael/login-register/after-register-form-open', $this ); ?>
							<?php // Print all dynamic fields
							foreach ( $this->ds['register_fields'] as $f_index => $field ) :
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

								$current_field_required = ( ! empty( $field['required'] ) || in_array( $field_type, [
										'password',
										'confirm_pass',
										'email',
									] ) );

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
										'id'          => 'form-field-' . $field_type,
									],
									$label_key => [
										'for'   => 'form-field-' . $field_type,
										'class' => 'eael-field-label',
									],
								] );

								// print require field attributes
								if ( $current_field_required ) {
									$this->add_render_attribute( $input_key, [
										'required'      => 'required',
										'aria-required' => 'true',
									] );
									if ( 'yes' === $this->ds['mark_required'] ) {
										$this->add_render_attribute( $label_key, [
											'class' => 'mark-required',
										] );
									}

								}


								// add css classes to the main input field wrapper.
								$this->add_render_attribute( [
									$field_group_key => [
										'class' => [
											'eael-lr-form-group',
											'eael-field-type-' . $field_type,
											'eael-w-' . $field['width'],
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
									if ( 'yes' === $this->ds['show_labels'] && ! empty( $field['field_label'] ) ) {
										echo '<label ' . $this->get_render_attribute_string( $label_key ) . '>' . esc_attr( $field['field_label'] ) . '</label>';
									}
									echo '<input ' . $this->get_render_attribute_string( $input_key ) . '>';
									if ( $show_icon && ! empty( $field['icon'] ) ) {
										Icons_Manager::render_icon( $field['icon'], [ 'aria-hidden' => 'true' ] );
									}
									?>
                                </div>

								<?php
								if ( 'password' === $field['field_type'] ) {
									do_action( 'eael/login-register/after-password-field', $this );
								}
							endforeach;
							$this->print_necessary_hidden_fields( 'register' );
							$this->print_terms_condition_notice();
							$this->print_recaptcha_node( 'register' );
							?>

                            <div class="eael-lr-footer">
                                <input type="submit"
                                       name="eael-register-submit"
                                       id="eael-register-submit"
                                       class="eael-lr-btn eael-lr-btn-block<?php echo esc_attr( $btn_align ); ?>"
                                       value="<?php echo esc_attr( $btn_text ); ?>"/>
								<?php if ( $show_lgn_link ) { ?>
                                    <div class="eael-sign-wrapper  <?php echo esc_attr( $link_align ); ?>">
										<?php echo $lgn_link; ?>
                                    </div>
								<?php } ?>
                            </div>

                            <div class="eael-form-validation-container">
								<?php $this->print_validation_message(); ?>
                            </div>
							<?php

							do_action( 'eael/login-register/before-register-form-close', $this );
							?>
                        </form>
						<?php do_action( 'eael/login-register/after-register-form', $this ); ?>
                    </div>
					<?php if ( 'right' === $this->form_illustration_pos ) {
						$this->print_form_illustration();
					} ?>
                </div>
            </section>
			<?php
			$form_markup = apply_filters( 'eael/login-register/register-form-markup', ob_get_clean() );
			// if we are in the editor then show error related to different input field.
			if ( $this->in_editor ) {
				$repeated            = $this->print_error_for_repeated_fields( $repeated_f_labels );
				$email_field_missing = $this->print_error_for_missing_email_field( $email_exists );
				$pass_missing        = $this->print_error_for_missing_password_field( $password_exists, $confirm_pass_exists );
				if ( $repeated || $email_field_missing || $pass_missing ) {
					return false; // error found, exit, dont show form.
				}
				echo $form_markup; //XSS OK, data sanitized already.
			} else {
				echo $form_markup; //XSS OK, data sanitized already.
			}
		}
	}

	protected function print_form_illustration() {
		if ( ! empty( $this->form_illustration_url ) ) { ?>
            <div class="lr-form-illustration lr-img-pos-<?php echo esc_attr( $this->form_illustration_pos ); ?>"
                 style="background-image: url('<?php echo esc_attr( esc_url( $this->form_illustration_url ) ); ?>');"></div>
		<?php }
	}

	/**
	 * @param string $form_type the type of form. Available values: login and register
	 */
	protected function print_form_header( $form_type = 'login' ) {
		$title    = ! empty( $this->ds["{$form_type}_form_title"] ) ?  $this->ds["{$form_type}_form_title"]  : '';
		$subtitle = ! empty( $this->ds["{$form_type}_form_subtitle"] ) ? esc_html( $this->ds["{$form_type}_form_subtitle"] ) : '';
		if ( empty( $this->form_logo ) && empty( $title ) && empty( $subtitle ) ) {
			return;
		}

		?>
        <div class="lr-form-header header-<?php echo esc_attr( $this->form_logo_pos ); ?>">
			<?php if ( ! empty( $this->form_logo ) ) { ?>
                <div class="form-logo">
                    <img src="<?php echo esc_attr( esc_url( $this->form_logo ) ); ?>"
                         alt="<?php esc_attr_e( 'Form Logo Image', 'essential-addons-for-elementor-lite' ); ?>">
                </div>
			<?php } ?>

			<?php if ( ! empty( $title ) || ! empty( $subtitle ) ) { ?>
                <div class="form-dsc">
					<?php
					if ( ! empty( $title ) ) {
						echo "<h4>{$title}</h4>"; // data escaped already.
					}

					if ( ! empty( $subtitle ) ) {
						echo "<p>{$subtitle}</p>"; // data escaped already.
					} ?>
                </div>
			<?php } ?>
        </div>
		<?php
	}

	protected function print_necessary_hidden_fields( $form_type = 'login' ) {
		if ( 'login' === $form_type ) {
			if ( ! empty( $this->ds['redirect_after_login'] ) && 'yes' === $this->ds['redirect_after_login'] ) {
				$login_redirect_url = ! empty( $this->ds['redirect_url']['url'] ) ? esc_url( $this->ds['redirect_url']['url'] ) : '';
				?>
                <input type="hidden"
                       name="redirect_to"
                       value="<?php echo esc_attr( $login_redirect_url ); ?>">
			<?php }
		}
		// add login security nonce
		wp_nonce_field( "eael-{$form_type}-action", "eael-{$form_type}-nonce" );
		?>
        <input type="hidden"
               name="page_id"
               value="<?php echo esc_attr( $this->page_id ); ?>">
        <input type="hidden"
               name="widget_id"
               value="<?php echo esc_attr( $this->get_id() ); ?>">
		<?php
	}

	protected function print_terms_condition_notice() {
		if ( empty( $this->ds['show_terms_conditions'] ) || 'yes' !== $this->ds['show_terms_conditions'] ) {
			return;
		}
		$l         = isset( $this->ds['acceptance_label'] ) ? HelperCLass::eael_wp_kses($this->ds['acceptance_label']) : '';
		$parts     = explode( "\n", $l );
		$label     = array_shift( $parts );
		$link_text = array_pop( $parts );
		$source    = isset( $this->ds['acceptance_text_source'] ) ? $this->ds['acceptance_text_source'] : 'editor';
		$tc_text   = isset( $this->ds['acceptance_text'] ) ? $this->ds['acceptance_text'] : '';
		$tc_link   = '<a href="#" id="eael-lr-tnc-link" class="eael-lr-tnc-link">' . esc_html( $link_text ) . '</a>';
		if ( 'custom' === $source ) {
			$tc_url  = ! empty( $this->ds['acceptance_text_url']['url'] ) ? esc_url( $this->ds['acceptance_text_url']['url'] ) : esc_url( get_the_permalink( get_option( 'wp_page_for_privacy_policy' ) ) );
			$tc_atts = ! empty( $this->ds['acceptance_text_url']['is_external'] ) ? ' target="_blank"' : '';
			$tc_atts .= ! empty( $this->ds['acceptance_text_url']['nofollow'] ) ? ' rel="nofollow"' : '';
			$tc_link = sprintf( '<a href="%1$s" id="eael-lr-tnc-link" class="eael-lr-tnc-link" %2$s>%3$s</a>', esc_attr( $tc_url ), $tc_atts, $link_text );
		}

		?>
        <div class="eael_accept_tnc_wrap">
            <input type="hidden"
                   name="eael_tnc_active"
                   value="1">
            <input type="checkbox"
                   name="eael_accept_tnc"
                   class="eael_accept_tnc lr-toggle"
                   value="1"
                   id="eael_accept_tnc">
            <label for="eael_accept_tnc"
                   class="eael-checkbox-label check-accept">
				<?php
				echo esc_html( $label );
				?>
            </label>
			<?php
			echo $tc_link; // XSS ok. already sanitized.
			?>
        </div>

		<?php
		$tc = '<div class="eael-lr-tnc-wrap">';
		$tc .= $this->parse_text_editor( $tc_text );
		$tc .= '</div>';
		echo $tc;


	}

	protected function print_login_validation_errors() {
		$error_key = 'eael_login_error_' . $this->get_id();
		if ( $login_error = apply_filters( 'eael/login-register/login-error-message', get_option( $error_key ) ) ) {
			do_action( 'eael/login-register/before-showing-login-error', $login_error, $this );
			?>
            <p class="eael-form-msg invalid">
				<?php echo esc_html( $login_error ); ?>
            </p>
			<?php
			do_action( 'eael/login-register/after-showing-login-error', $login_error, $this );

			delete_option( $error_key );
		}
	}

	protected function print_recaptcha_node( $form_type = 'login' ) {
		if ( 'yes' === $this->get_settings_for_display( "enable_{$form_type}_recaptcha" ) ) {
			$id = "{$form_type}-recaptcha-node-" . $this->get_id();
			echo "<input type='hidden' name='g-recaptcha-enabled' value='1'/><div id='{$id}' class='eael-recaptcha-wrapper'></div>";
		}
	}

	protected function print_error_for_repeated_fields( $repeated_fields ) {
		if ( ! empty( $repeated_fields ) ) {
			$error_fields = '<strong>' . implode( "</strong>, <strong>", $repeated_fields ) . '</strong>';
			?>
            <p class='eael-register-form-error elementor-alert elementor-alert-warning'>
				<?php
				/* translators: %s: Error fields */
				printf( __( 'Error! you seem to have added %s field in the form more than once.', 'essential-addons-for-elementor-lite' ), $error_fields );
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
				printf( __( 'Error! It is required to use %s field.', 'essential-addons-for-elementor-lite' ), '<strong>Email</strong>' );
				?>
            </p>
			<?php
			return true;
		}

		return false;
	}

	/**
	 * It shows error if Confirm Password Field is used without using Password Field.
	 *
	 * @param $password_exist
	 * @param $confirm_pass_exist
	 *
	 * @return bool
	 */
	protected function print_error_for_missing_password_field( $password_exist, $confirm_pass_exist ) {
		if ( empty( $password_exist ) && ! empty( $confirm_pass_exist ) ) {
			?>
            <p class='eael-register-form-error elementor-alert elementor-alert-warning'>
				<?php
				/* translators: %s: Error String */
				printf( __( 'Error! It is required to use %s field with %s Field.', 'essential-addons-for-elementor-lite' ), '<strong>Password</strong>', '<strong>Password Confirmation</strong>' );
				?>
            </p>
			<?php
			return true;
		}

		return false;
	}

	protected function print_validation_message() {
		$errors  = get_option( 'eael_register_errors_' . $this->get_id() );
		$success = get_option( 'eael_register_success_' . $this->get_id() );
		if ( empty( $errors ) && empty( $success ) ) {
			return;
		}
		if ( ! empty( $errors ) && is_array( $errors ) ) {
			$this->print_registration_errors_message( $errors );
		} else {
			$this->print_registration_success_message( $success );
		}
	}

	protected function print_registration_errors_message( $errors ) {
		?>
        <div class="eael-form-msg invalid">
			<?php
			if ( ! empty( $this->ds['err_unknown'] ) ) {
				printf( '<p>%s</p>', esc_html( $this->ds['err_unknown'] ) );
			}
			?>
            <ol>
				<?php
				foreach ( $errors as $register_error ) {
					printf( '<li>%s</li>', esc_html( $register_error ) );
				}
				?>
            </ol>
        </div>
		<?php
		delete_option( 'eael_register_errors_' . $this->get_id() );
	}

	protected function print_registration_success_message( $success ) {

		if ( $success ) {
			$message = '<p class="eael-form-msg valid">' . esc_html( $this->get_settings_for_display( 'success_register' ) ) . '</p>';
			echo apply_filters( 'eael/login-register/registration-success-msg', $message, $success );

			delete_option( 'eael_register_success_' . $this->get_id() );

			return true; // it will help in case we wanna know if error is printed.
		}

		return false;
	}

	/**
	 * It will apply value like Elementor's dimension control to a property and return it.
	 *
	 * @param string $css_property CSS property name
	 *
	 * @return string
	 */
	public function apply_dim( $css_property ) {
		return "{$css_property}: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};";
	}

}
