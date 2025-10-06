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
use WP_Roles;

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
	 * Login custom redirect url
	 * @var bool
	 */
	protected $login_custom_redirect_url;
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
	 * Should lost password form be printed?
	 * @var bool
	 */
	protected $should_print_lostpassword_form;
	/**
	 * Should reset password form be printed?
	 * @var bool
	 */
	protected $should_print_resetpassword_form;
	/**
	 * Should reset password form be printed in editor?
	 * @var bool
	 */
	protected $should_print_resetpassword_form_editor;
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
	 * @var bool|false|int
	 */
	protected $page_id_for_popup;
	/**
	 * @var string
	 */
	protected $resetpassword_in_popup_selector;
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
	 * Google reCAPTCHA v3 Site key
	 * @var string|false
	 */
	protected $recaptcha_sitekey_v3;
	
	/**
	 * Google reCAPTCHA v3 badge hide flag
	 * @var string|false
	 */
	protected $recaptcha_badge_hide;

	/**
	 * Cloudflare Turnstile Site key
	 * @var string|false
	 */
	protected $cloudflare_turnstile_sitekey;

	/**
	 * Cloudflare Turnstile Secret key
	 * @var string|false
	 */
	protected $cloudflare_turnstile_secretkey;

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
		$this->cloudflare_turnstile_sitekey = get_option( 'eael_cloudflare_turnstile_sitekey' );
		$this->cloudflare_turnstile_secretkey = get_option( 'eael_cloudflare_turnstile_secretkey' );
		$this->recaptcha_sitekey_v3 = get_option( 'eael_recaptcha_sitekey_v3' );
		$this->recaptcha_badge_hide = get_option('eael_recaptcha_badge_hide');
		$this->in_editor         = Plugin::instance()->editor->is_edit_mode();
		$this->pro_enabled       = apply_filters( 'eael/pro_enabled', false );

		if( ! empty( $this->cloudflare_turnstile_sitekey ) ){
			wp_register_script( 'eael-cloudflare', 'https://challenges.cloudflare.com/turnstile/v0/api.js' );
		}

		if ( $this->recaptcha_badge_hide ) {
			add_filter( 'body_class', [ $this, 'add_login_register_body_class' ] );
		}
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

	public function has_widget_inner_wrapper(): bool {
        return ! HelperCLass::eael_e_optimized_markup();
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
		$eael_form_field_types = [
			'user_name'    => __( 'Username', 'essential-addons-for-elementor-lite' ),
			'email'        => __( 'Email', 'essential-addons-for-elementor-lite' ),
			'password'     => __( 'Password', 'essential-addons-for-elementor-lite' ),
			'confirm_pass' => __( 'Confirm Password', 'essential-addons-for-elementor-lite' ),
			'first_name'   => __( 'First Name', 'essential-addons-for-elementor-lite' ),
			'last_name'    => __( 'Last Name', 'essential-addons-for-elementor-lite' ),
			'website'      => __( 'Website', 'essential-addons-for-elementor-lite' ),
			'honeypot'     => __( 'Honeypot', 'essential-addons-for-elementor-lite' ),
		];

		if( 'on' === get_option( 'eael_custom_profile_fields' ) ){
			$eael_form_field_types['eael_phone_number'] = __( 'Phone', 'essential-addons-for-elementor-lite' );
			$eael_custom_profile_fields = $this->get_eael_custom_profile_fields( 'all' );
			$eael_form_field_types = array_merge( $eael_form_field_types, $eael_custom_profile_fields );
		}
		
		return apply_filters( 'eael/registration-form-fields', $eael_form_field_types );
	}

	/**
	 * @inheritDoc
	 */
	protected function register_controls() {
		/*----Content Tab----*/
		do_action( 'eael/login-register/before-content-controls', $this );
		$this->init_content_general_controls();
		$this->init_bot_protection_controls();
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

		// Lost Password Form Related---
		$this->init_content_lostpassword_fields_controls();
		$this->init_content_lostpassword_user_email_controls();

		// Reset Password Form Related---
		$this->init_content_resetpassword_fields_controls();
		$this->init_content_resetpassword_options_controls();

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
		$this->init_style_header_content_controls( 'lostpassword' );
		$this->init_style_header_content_controls( 'resetpassword' );
		$this->init_style_input_fields_controls();
		$this->init_style_input_labels_controls();
		$this->init_style_login_button_controls();
		$this->init_style_register_button_controls();
		$this->init_style_lostpassword_button_controls();
		$this->init_style_resetpassword_button_controls();
		$this->init_style_login_link_controls();
		$this->init_style_register_link_controls();
		$this->init_style_login_recaptcha_controls();
		$this->init_style_register_recaptcha_controls();
		$this->init_style_lostpassword_recaptcha_controls();
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
				'lostpassword' => __( 'Lost Password', 'essential-addons-for-elementor-lite' ),
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

		$this->add_control( 'enable_reset_password', [
			'label'   => __( 'Enable Reset Password Form', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'no',
			'conditions' => [
				'relation' => 'or',
				'terms'    => [
					[
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'show_lost_password',
								'value' => 'yes'
							],
							[
								'name' => 'lost_password_link_type',
								'value' => 'form',
							]
						]
					],
					[
						'name'  => 'default_form_type',
						'value' => 'lostpassword',
					]
				],
			],
		] );

		// preview reset password form
		$this->add_control( 'preview_reset_password', [
			'label'   => __( 'Preview Reset Password Form', 'essential-addons-for-elementor-lite' ),
			'description' => __( 'This will show a preview of the reset password form in the editor.', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'no',
			'condition' => [
				'enable_reset_password' => 'yes'
			],
		] );

		$this->add_control( 'hide_for_logged_in_user', [
			'label'   => __( 'Hide all Forms from Logged-in Users', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => '',
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
		$this->add_control( 'log_out_link_text', [
			'label'       => __( 'Logout Link Text', 'essential-addons-for-elementor-lite' ),
			'label_block' => true,
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => __( 'You are already logged in as [username]. ([logout_link])', 'essential-addons-for-elementor-lite' ),
			'condition'   => [
				'show_log_out_message' => 'yes',
			],
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
			'default'     => __( 'Forgot Password?', 'essential-addons-for-elementor-lite' ),
			'condition'   => [
				'show_lost_password' => 'yes',
			],
			'ai' => [
				'active' => false,
			],
		] );
		$this->add_control( 'lost_password_link_type', [
			'label'       => __( 'Lost Password Link to', 'essential-addons-for-elementor-lite' ),
			'label_block' => true,
			'type'        => Controls_Manager::SELECT,
			'options'     => [
				'default' => __( 'Default WordPress Page', 'essential-addons-for-elementor-lite' ),
				'custom'  => __( 'Custom URL', 'essential-addons-for-elementor-lite' ),
				'form'  => __( 'Show Lost Password Form', 'essential-addons-for-elementor-lite' ),
			],
			'default'     => 'default',
			'condition'   => [
				'show_lost_password' => 'yes',
			],
		] );
		$this->add_control( 'lost_password_link_type_notice', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => __( "Note: To use the Reset Password Form enable it from Content » General » Enabled Reset Password Form.", 'essential-addons-for-elementor-lite' ),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			'condition'		  => [
				'lost_password_link_type' => 'form'
			]
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
			'ai' => [
				'active' => false,
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
			do_action( 'eael/login-register/mailchimp-integration', $this );
			$this->end_popover();

		} else {
			$this->add_control( 'show_login_link', [
				'label'   => __( 'Show Login Link', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::HIDDEN,
				'default' => 'no',
			] );
		}

		// Lost Password Form general settings starts
		$this->add_control( 'gen_lostpassword_content_po_toggle', [
			'label'        => __( 'Lost Password Form General', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Controls', 'essential-addons-for-elementor-lite' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',
			'default'      => 'yes',
		] );
		$this->start_popover();
		$this->add_control( 'show_login_link_lostpassword', [
			'label'   => __( 'Show Login Link', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );
		$this->add_control( 'login_link_text_lostpassword', [
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
				'show_login_link_lostpassword' => 'yes',
			],
		] );
		$this->add_control( 'login_link_action_lostpassword', [
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
				'show_login_link_lostpassword' => 'yes',
			],
		] );
		$this->add_control( 'custom_login_url_lostpass', [
			'label'         => __( 'Custom Login URL', 'essential-addons-for-elementor-lite' ),
			'label_block'   => true,
			'show_external' => false,
			'type'          => Controls_Manager::URL,
			'dynamic'       => [
				'active' => true,
			],
			'condition'     => [
				'login_link_action_lostpassword' => 'custom',
				'show_login_link_lostpassword'   => 'yes',
			],
		] );
		$this->end_popover();

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

	protected function init_bot_protection_controls() {
		$this->start_controls_section( 
			'section_content_bot_protection', 
			[
				'label' => __( 'Bot Protection', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'bot_protection_notice',
			[
				'type'        => Controls_Manager::NOTICE,
				'notice_type' => 'info',
				'content'     => esc_html__( 'This helps protect your site from spam form submissions by bots.', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'google_recaptcha_heading',
			[
				'label' => __( 'Google reCAPTCHA', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control( 'enable_login_register_recaptcha', [
			'label'        => __( 'Enable', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'login_register_recaptcha_version', [
			'label'       => __( 'Version', 'essential-addons-for-elementor-lite' ),
			'label_block' => false,
			'type'        => Controls_Manager::CHOOSE,
			'toggle'      => false,
			'default'     => 'v2',
			'options'     => [
				'v2' => [
					'title' => __( 'v2', 'essential-addons-for-elementor-lite' ),
					'text'  => 'v2',
				],
				'v3' => [
					'title' => __( 'v3', 'essential-addons-for-elementor-lite' ),
					'text'  => 'v3',
				],
			],
			'condition'   => [
				'enable_login_register_recaptcha' => 'yes',
			],
		] );

		$this->add_control( 'login_register_recaptcha_v3_description', [
			'type'      => Controls_Manager::RAW_HTML,
			'content_classes' => 'elementor-control-field-description',
			'raw'       => __( '<p style="margin-top:-15px">v3 will be applied to all forms. After saving, reload the preview to see the changes.</p>', 'essential-addons-for-elementor-lite' ),
			'condition' => [
				'login_register_recaptcha_version' => 'v3',
				'enable_login_register_recaptcha'   => 'yes',
			],
		] );

		$this->add_control( 
			'enable_login_recaptcha_heading',
			[
				'label' => __( 'Apply on', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'enable_login_register_recaptcha' => 'yes',
					'login_register_recaptcha_version' => 'v2',
				],
			]
		);

		$this->add_control( 'enable_login_recaptcha', [
			'label'        => __( 'Login Form', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
			'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',
			'condition'    => [
				'enable_login_register_recaptcha' => 'yes',
				'login_register_recaptcha_version' => 'v2',
			],
		] );

		if( $this->user_can_register ) {
			$this->add_control( 'enable_register_recaptcha', [
				'label'        => __( 'Registration Form', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'condition'    => [
					'enable_login_register_recaptcha' => 'yes',
					'login_register_recaptcha_version' => 'v2',
				],
			] );
		}

		$this->add_control( 'enable_lostpassword_recaptcha', [
			'label'        => __( 'Lost Password Form', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
			'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',
			'condition'    => [
				'enable_login_register_recaptcha' => 'yes',
				'login_register_recaptcha_version' => 'v2',
			],
		] );
		
		if ( empty( $this->recaptcha_sitekey ) ) {
			$this->add_control( 
				'eael_recaptcha_keys_missing', [
				'type'            => Controls_Manager::NOTICE,
				'notice_type'     => 'warning',
				'heading'         => __( 'reCAPTCHA v3 API keys are missing', 'essential-addons-for-elementor-lite' ),
				'content'         => sprintf( __( 'Please add them from  %sDashboard >> Essential Addons >> Elements >> Login | Register Form %sSettings', 'essential-addons-for-elementor-lite' ), '<a href="'.esc_url( site_url( '/wp-admin/admin.php?page=eael-settings' ) ).'" target="_blank"><strong>', '</strong></a>' ),
				'condition'       => [
					'enable_login_register_recaptcha' => 'yes',
					'login_register_recaptcha_version' => 'v2',
				],
			] );
		}

		if ( empty( $this->recaptcha_sitekey_v3 ) ) {
			$this->add_control( 
				'eael_recaptcha_keys_missing_v3', [
				'type'            => Controls_Manager::NOTICE,
				'notice_type'     => 'warning',
				'heading'         => __( 'reCAPTCHA v3 API keys are missing', 'essential-addons-for-elementor-lite' ),
				'content'         => sprintf( __( 'Please add them from  %sDashboard >> Essential Addons >> Elements >> Login | Register Form %sSettings', 'essential-addons-for-elementor-lite' ), '<a href="'.esc_url( site_url( '/wp-admin/admin.php?page=eael-settings' ) ).'" target="_blank"><strong>', '</strong></a>' ),
				'condition'       => [
					'enable_login_register_recaptcha' => 'yes',
					'login_register_recaptcha_version' => 'v3',
				],
			] );
		}

		$this->add_control( 'login_register_recaptcha_v3_score_threshold', [
			'label'       => esc_html__( 'Score Threshold', 'essential-addons-for-elementor-lite' ),
			'description' => esc_html__( 'By default, you can use a threshold of 0.5.', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'%',
			],
			'range'      => [
				'%' => [
					'min'  => 0,
					'max'  => 1,
					'step' => 0.1,
				],
			],
			'default'    => [
				'unit' => '%',
				'size' => 0.5,
			],
			'condition'       => [
				'enable_login_register_recaptcha' => 'yes',
				'login_register_recaptcha_version' => 'v3',
			],
		] );

		$this->add_control(
			'cloudflare_turnstile_heading',
			[
				'label' => __( 'Cloudflare Turnstile', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control( 
			'enable_cloudflare_turnstile', 
			[
			'label'        => __( 'Enable', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
		] );

		$this->add_control(
			'enable_cloudflare_turnstile_appearance_notice',
			[
				'type' => Controls_Manager::NOTICE,
				'notice_type' => 'info',
				'heading' => __( 'Appearance', 'essential-addons-for-elementor-lite' ),
				'content' => __( 'Cloudflare Turnstile will be applied on frontend only.', 'essential-addons-for-elementor-lite' ),
				'condition' => [
					'enable_cloudflare_turnstile' => 'yes',
				],
			]
		);

		$this->add_control(
			'cloudflare_turnstile_theme',
			[
				'label'   => __( 'Theme', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'auto' => [
						'title' => __( 'Auto', 'essential-addons-for-elementor-lite' ),
						'icon' => 'eicon-ai',
					],
					'light' => [
						'title' => __( 'Light', 'essential-addons-for-elementor-lite' ),
						'icon' => 'eicon-light-mode',
					],
					'dark' => [
						'title' => __( 'Dark', 'essential-addons-for-elementor-lite' ),
						'icon' => 'eicon-dark-mode',
					],
				],
				'toggle'    => false,
				'default'   => 'auto',
				'condition' => [
					'enable_cloudflare_turnstile' => 'yes',
				],
			]
		);


		if ( empty( $this->cloudflare_turnstile_sitekey ) || empty( $this->cloudflare_turnstile_secretkey ) ) {
			$this->add_control( 
				'eael_cloudflare_turnstile_keys_missing', [
				'type'            => Controls_Manager::NOTICE,
				'notice_type'     => 'warning',
				'heading'         => __( 'Cloudflare Turnstile Site Key or Secret Key is missing', 'essential-addons-for-elementor-lite' ),
				'content'         => sprintf( __( 'Please add it from  %sDashboard >> Essential Addons >> Elements >> Login | Register Form %sSettings', 'essential-addons-for-elementor-lite' ), '<a href="'.esc_url( site_url( '/wp-admin/admin.php?page=eael-settings' ) ).'" target="_blank"><strong>', '</strong></a>' ),
				'condition'       => [
					'enable_cloudflare_turnstile' => 'yes',
				],
			] );
		}

		$this->add_control( 'enable_cloudflare_turnstile_heading', [
			'label' => __( 'Apply on', 'essential-addons-for-elementor-lite' ),
			'type' => Controls_Manager::HEADING,
			'condition' => [
				'enable_cloudflare_turnstile' => 'yes',
			],
		] );

		$this->add_control( 
			'enable_cloudflare_turnstile_on_login',
			[
				'label'     => __( 'Login Form', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'enable_cloudflare_turnstile' => 'yes',
				],
			]
		);

		if( $this->user_can_register ) {
			$this->add_control( 
				'enable_cloudflare_turnstile_on_register',
				[
					'label'     => __( 'Registration Form', 'essential-addons-for-elementor-lite' ),
					'type'      => Controls_Manager::SWITCHER,
					'condition' => [
						'enable_cloudflare_turnstile' => 'yes',
					],
				]
			);
		}

		$this->add_control( 
			'enable_cloudflare_turnstile_on_lostpassword',
			[
				'label'     => __( 'Lost Password Form', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'enable_cloudflare_turnstile' => 'yes',
				],
			]
		);

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
			'ai' => [
				'active' => false,
			],
		] );

		$this->add_control( 'login_password_label', [
			'label'       => __( 'Password Label', 'essential-addons-for-elementor-lite' ),
			'placeholder' => __( 'Password', 'essential-addons-for-elementor-lite' ),
			'default'     => __( 'Password', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'label_block' => true,
			'condition'   => [ 'login_label_types' => 'custom', ],
			'ai' => [
				'active' => false,
			],
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
			'ai' => [
				'active' => false,
			],
		] );

		$this->add_control( 'login_password_placeholder', [
			'label'       => __( 'Password Placeholder', 'essential-addons-for-elementor-lite' ),
			'placeholder' => __( 'Password', 'essential-addons-for-elementor-lite' ),
			'default'     => __( 'Password', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'label_block' => true,
			'condition'   => [ 'login_label_types' => 'custom', ],
			'ai' => [
				'active' => false,
			],
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
				'{{WRAPPER}} .eael-login-form .eael-user-login' => 'width: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .eael-login-form .eael-user-password' => 'width: {{SIZE}}{{UNIT}};',
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

		$this->add_control( 'login_form_fields_remember_me_heading', [
			'label'     => esc_html__( 'Remember Me', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'login_show_remember_me' => 'yes',
			]
		] );

		$this->add_control( 'login_form_fields_remember_me_checked', [
			'label'     => __( 'Checked By Default', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SWITCHER,
			'label_off' => __( 'No', 'essential-addons-for-elementor-lite' ),
			'label_on'  => __( 'Yes', 'essential-addons-for-elementor-lite' ),
			'default'   => '',
			'condition' => [
				'login_show_remember_me' => 'yes',
			]
		] );

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
			'ai' => [
				'active' => false,
			],
		] );

		$this->end_controls_section();
	}
	
	/**
	 * It adds controls related to Lost Password Form Fields section to the Widget Content Tab
	 */
	protected function init_content_lostpassword_fields_controls() {
		$this->start_controls_section( 'section_content_lostpass_fields', [
			'label'      => __( 'Lost Password Form Fields', 'essential-addons-for-elementor-lite' ),
			'conditions' => [
				'relation' => 'or',
				'terms'    => [
					[
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'show_lost_password',
								'value' => 'yes'
							],
							[
								'name' => 'lost_password_link_type',
								'value' => 'form',
							]
						]
					],
					[
						'name'  => 'default_form_type',
						'value' => 'lostpassword',
					]
				],
			],
		] );

		$this->add_control( 'lostpassword_label_types', [
			'label'   => __( 'Label & Placeholder', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'default' => __( 'Default', 'essential-addons-for-elementor-lite' ),
				'custom'  => __( 'Custom', 'essential-addons-for-elementor-lite' ),
				'none'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
			],
			'default' => 'default',
		] );

		$this->add_control( 'lostpassword_labels_heading', [
			'label'     => __( 'Label', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [ 'lostpassword_label_types' => 'custom', ],
		] );


		$this->add_control( 'lostpassword_user_label', [
			'label'       => __( 'Username Label', 'essential-addons-for-elementor-lite' ),
			'placeholder' => __( 'Username or Email Address', 'essential-addons-for-elementor-lite' ),
			'default'     => __( 'Username or Email Address', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'label_block' => true,
			'condition'   => [ 'lostpassword_label_types' => 'custom', ],
			'ai' => [
				'active' => false,
			],
		] );

		$this->add_control( 'lostpassword_placeholders_heading', [
			'label'     => esc_html__( 'Placeholder', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
			'condition' => [ 'lostpassword_label_types' => 'custom', ],
			'separator' => 'before',
		] );

		$this->add_control( 'lostpassword_user_placeholder', [
			'label'       => __( 'Username Placeholder', 'essential-addons-for-elementor-lite' ),
			'placeholder' => __( 'Username or Email Address', 'essential-addons-for-elementor-lite' ),
			'default'     => __( 'Username or Email Address', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'label_block' => true,
			'condition'   => [ 'lostpassword_label_types' => 'custom', ],
			'ai' => [
				'active' => false,
			],
		] );

		$this->add_responsive_control( 'lostpassword_field_width', [
			'label'      => esc_html__( 'Input Field width', 'essential-addons-for-elementor-lite' ),
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
				'{{WRAPPER}} .eael-lostpassword-form .eael-lr-form-group' => 'width: {{SIZE}}{{UNIT}};',
			],
			'separator'  => 'before',
		] );

		/*--Lost Password Fields Button--*/
		$this->add_control( 'lostpassword_button_heading', [
			'label'     => esc_html__( 'Lost Password Button', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'lostpassword_button_text', [
			'label'       => __( 'Button Text', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'default'     => __( 'Reset Password', 'essential-addons-for-elementor-lite' ),
			'placeholder' => __( 'Reset Password', 'essential-addons-for-elementor-lite' ),
			'ai' => [
				'active' => false,
			],
		] );

		$this->end_controls_section();
	}

	/**
	 * It adds controls related to Reset Password Form Fields section to the Widget Content Tab
	 */
	protected function init_content_resetpassword_fields_controls() {
		$this->start_controls_section( 'section_content_resetpass_fields', [
			'label'      => __( 'Reset Password Form Fields', 'essential-addons-for-elementor-lite' ),
			'conditions' => [
				'relation' => 'or',
				'terms'    => [
					[
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'show_lost_password',
								'value' => 'yes'
							],
							[
								'name' => 'lost_password_link_type',
								'value' => 'form',
							]
						]
					],
					[
						'name'  => 'default_form_type',
						'value' => 'lostpassword',
					]
				],
			],
		] );

		$this->add_control( 'resetpassword_label_types', [
			'label'   => __( 'Labels & Placeholders', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'default' => __( 'Default', 'essential-addons-for-elementor-lite' ),
				'custom'  => __( 'Custom', 'essential-addons-for-elementor-lite' ),
				'none'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
			],
			'default' => 'default',
		] );

		$this->add_control( 'resetpassword_labels_heading', [
			'label'     => __( 'Labels', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [ 'resetpassword_label_types' => 'custom', ],
		] );

		$this->add_control( 'resetpassword_password_label', [
			'label'       => __( 'Password Label', 'essential-addons-for-elementor-lite' ),
			'placeholder' => __( 'New Password', 'essential-addons-for-elementor-lite' ),
			'default'     => __( 'New Password', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'label_block' => true,
			'condition'   => [ 'resetpassword_label_types' => 'custom', ],
			'ai' => [
				'active' => false,
			],
		] );

		$this->add_control( 'resetpassword_confirm_password_label', [
			'label'       => __( 'Confirm Password Label', 'essential-addons-for-elementor-lite' ),
			'placeholder' => __( 'Confirm New Password', 'essential-addons-for-elementor-lite' ),
			'default'     => __( 'Confirm New Password', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'label_block' => true,
			'condition'   => [ 'resetpassword_label_types' => 'custom', ],
			'ai' => [
				'active' => false,
			],
		] );

		$this->add_control( 'resetpassword_placeholders_heading', [
			'label'     => esc_html__( 'Placeholders', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
			'condition' => [ 'resetpassword_label_types' => 'custom', ],
			'separator' => 'before',
		] );

		$this->add_control( 'resetpassword_password_placeholder', [
			'label'       => __( 'Password Placeholder', 'essential-addons-for-elementor-lite' ),
			'placeholder' => __( 'New Password', 'essential-addons-for-elementor-lite' ),
			'default'     => __( 'New Password', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'label_block' => true,
			'condition'   => [ 'resetpassword_label_types' => 'custom', ],
			'ai' => [
				'active' => false,
			],
		] );

		$this->add_control( 'resetpassword_confirm_password_placeholder', [
			'label'       => __( 'Confirm Password Placeholder', 'essential-addons-for-elementor-lite' ),
			'placeholder' => __( 'Confirm New Password', 'essential-addons-for-elementor-lite' ),
			'default'     => __( 'Confirm New Password', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'label_block' => true,
			'condition'   => [ 'resetpassword_label_types' => 'custom', ],
			'ai' => [
				'active' => false,
			],
		] );

		$this->add_responsive_control( 'resetpassword_field_width', [
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
				'{{WRAPPER}} .eael-resetpassword-form .eael-lr-form-group ' => 'width: {{SIZE}}{{UNIT}};',
			],
			'separator'  => 'before',
		] );

		$this->add_control( 'password_toggle_resetpassword', [
			'label'     => __( 'Password Visibility Icon', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SWITCHER,
			'label_off' => __( 'Hide', 'essential-addons-for-elementor-lite' ),
			'label_on'  => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'default'   => 'yes',
		] );

		/*--Reset Password Fields Button--*/
		$this->add_control( 'resetpassword_button_heading', [
			'label'     => esc_html__( 'Reset Password Button', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'resetpassword_button_text', [
			'label'       => __( 'Button Text', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'default'     => __( 'Save Password', 'essential-addons-for-elementor-lite' ),
			'placeholder' => __( 'Save Password', 'essential-addons-for-elementor-lite' ),
			'ai' => [
				'active' => false,
			],
		] );

		$this->end_controls_section();
	}

	protected function init_content_resetpassword_options_controls() {

		$this->start_controls_section( 'section_content_resetpassword_options', [
			'label'      => __( 'Reset Password Form Options', 'essential-addons-for-elementor-lite' ),
			'conditions' => $this->get_form_controls_display_condition( 'resetpassword' ),
		] );

		$this->add_control( 'redirect_after_resetpassword', [
			'label' => __( 'Redirect After Password Reset', 'essential-addons-for-elementor-lite' ),
			'type'  => Controls_Manager::SWITCHER,
		] );

		global $wp;
		$this->add_control( 'redirect_url_resetpassword', [
			'type'          => Controls_Manager::URL,
			'show_label'    => false,
			'show_external' => false,
			'placeholder'   => get_permalink( get_the_ID() ),
			'description'   => __( 'Please note that only your current domain is allowed here to keep your site secure.', 'essential-addons-for-elementor-lite' ),
			'condition'     => [
				'redirect_after_resetpassword' => 'yes',
			],
			'default'       => [
				'url'         => get_permalink( get_the_ID() ),
				'is_external' => false,
				'nofollow'    => true,
			],
			'separator'     => 'after',
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
			'ai' => [
				'active' => false,
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
		] );

		$this->add_control( 'show_image_on_lostpassword_form', [
			'label'   => __( 'Show on Lost Password Form', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
			'conditions' => [
				'relation' => 'or',
				'terms'    => [
					[
						'name'  => "show_lost_password",
						'value' => 'yes',
					],
					[
						'name'  => 'default_form_type',
						'value' => 'lostpassword',
					]
				],
			],
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
			'ai' => [
				'active' => false,
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
		] );

		$this->add_control( 'show_logo_on_lostpassword_form', [
			'label'   => __( 'Show on Lost Password Form', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
			'conditions' => [
				'relation' => 'or',
				'terms'    => [
					[
						'name'  => "show_lost_password",
						'value' => 'yes',
					],
					[
						'name'  => 'default_form_type',
						'value' => 'lostpassword',
					]
				],
			],
			'separator' => 'after',
		] );

		$this->add_control( 'login_form_title', [
			'label'       => __( 'Login Form Title', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'placeholder' => __( 'Welcome Back!', 'essential-addons-for-elementor-lite' ),
			'separator'   => 'before',
			'ai' => [
				'active' => false,
			],
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
			'ai' => [
				'active' => false,
			],
		] );
		$this->add_control( 'register_form_subtitle', [
			'label'       => __( 'Register Form Sub Title', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXTAREA,
			'dynamic'     => [ 'active' => true, ],
			'placeholder' => __( 'Create an account to enjoy awesome features.', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control( 'lostpassword_form_title', [
			'label'       => __( 'Lost Password Form Title', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'placeholder' => __( 'Get New Password', 'essential-addons-for-elementor-lite' ),
			'separator'   => 'before',
			'ai' => [
				'active' => false,
			],
		] );
		$this->add_control( 'lostpassword_form_subtitle', [
			'label'       => __( 'Lost Password Form Sub Title', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXTAREA,
			'dynamic'     => [ 'active' => true, ],
			'placeholder' => __( 'Please enter your username or email address. You will receive an email message with instructions on how to reset your password.', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control( 'resetpassword_form_title', [
			'label'       => __( 'Reset Password Form Title', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'placeholder' => __( 'Reset Password', 'essential-addons-for-elementor-lite' ),
			'separator'   => 'before',
			'ai' => [
				'active' => false,
			],
		] );
		$this->add_control( 'resetpassword_form_subtitle', [
			'label'       => __( 'Reset Password Form Sub Title', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXTAREA,
			'dynamic'     => [ 'active' => true, ],
			'placeholder' => __( 'Enter your new password below.', 'essential-addons-for-elementor-lite' ),
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
			'condition'     => [
				'redirect_after_login' => 'yes',
				'login_redirect_url_prev_page!' => 'yes',
			],
			'default'       => [
				'url'         => admin_url(),
				'is_external' => false,
				'nofollow'    => true,
			],
		] );

		$this->add_control( 'redirect_based_on_roles', [
			'label' => __( 'Redirect Based On User Roles', 'essential-addons-for-elementor-lite' ),
			'type'  => Controls_Manager::SWITCHER,
			'condition' => [
				'redirect_after_login' => 'yes',
			]
		] );

		$user_roles = $this->eael_get_role_names();

		if( ! empty( $user_roles ) && is_array( $user_roles ) && count( $user_roles ) ){
			foreach( $user_roles as $user_role_key => $user_role_value ){
				$this->add_control( 'redirect_url_' . esc_html( $user_role_key ), [
					'type'          => Controls_Manager::URL,
					'label'			=> esc_html( __( $user_role_value, 'essential-addons-for-elementor-lite' ) ),
					'show_external' => false,
					'placeholder'   => admin_url(),
					'condition'     => [
						'redirect_after_login' 		=> 'yes',
						'redirect_based_on_roles' 	=> 'yes',
					],
				] );
			}
		}

		$this->add_control( 'login_redirect_url_prev_page', [
			'label'   => __( 'Redirect to Previous Page', 'essential-addons-for-elementor-lite' ),
			'description'   => __( 'Redirect to the last visited page before login.', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => '',
			'condition'     => [
				'redirect_after_login' => 'yes',
			],
		] );

		$this->end_controls_section();
	}

	public function eael_get_role_names() {

		global $wp_roles;

		if ( ! isset( $wp_roles ) )
			$wp_roles = new WP_Roles();

		return $wp_roles->get_names();
	}

	protected function social_login_promo() {

		$this->start_controls_section( 'section_content_social_login', [
			'label'      => __( 'Social Login', 'essential-addons-for-elementor-lite' ),
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

		$this->add_control( 'eael_terms_conditions_field_type', [
			'label'       => __( 'Field Type', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => 'toggle',
			'options'     => [
				'toggle' => __( 'Toggle', 'essential-addons-for-elementor-lite' ),
				'checkbox'  => __( 'Checkbox', 'essential-addons-for-elementor-lite' ),
			],
			'condition'   => [
				'show_terms_conditions' => 'yes',
			],
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

		$terms_conditions_url = get_the_permalink( get_option( 'wp_page_for_privacy_policy' ) );

		$this->add_control( 'acceptance_text_url', [
			'label'       => __( 'Terms & Conditions URL', 'essential-addons-for-elementor-lite' ),
			'description' => __( 'Enter the link where your terms & condition or privacy policy is found.', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::URL,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => [
				'url'         => ! empty( $terms_conditions_url ) && 'false' !== $terms_conditions_url ? $terms_conditions_url : '',
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
			'ai' => [
				'active' => false,
			],
		] );
		$this->add_control( 'err_email_missing', [
			'label'       => __( 'Email is missing', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Eg. Email is missing or Invalid', 'essential-addons-for-elementor-lite' ),
			'default'     => __( 'Email is missing or Invalid', 'essential-addons-for-elementor-lite' ),
			'ai' => [
				'active' => false,
			],
		] );
		$this->add_control( 'err_email_used', [
			'label'       => __( 'Already Used Email', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Eg. Your email is already in use..', 'essential-addons-for-elementor-lite' ),
			'default'     => __( 'The provided email is already registered with other account. Please login or reset password or use another email.', 'essential-addons-for-elementor-lite' ),
			'ai' => [
				'active' => false,
			],
		] );
		$this->add_control( 'err_username', [
			'label'       => __( 'Invalid Username', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Eg. Your username is invalid.', 'essential-addons-for-elementor-lite' ),
			'default'     => __( "You have used an invalid username", 'essential-addons-for-elementor-lite' ),
			'ai' => [
				'active' => false,
			],
		] );
		$this->add_control( 'err_username_used', [
			'label'       => __( 'Username already in use', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Eg. Your username is already registered.', 'essential-addons-for-elementor-lite' ),
			'default'     => __( 'Invalid username provided or the username already registered.', 'essential-addons-for-elementor-lite' ),
			'ai' => [
				'active' => false,
			],
		] );
		$this->add_control( 'err_pass', [
			'label'       => __( 'Invalid Password', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Eg. Your password is invalid', 'essential-addons-for-elementor-lite' ),
			'default'     => __( "Your password is invalid.", 'essential-addons-for-elementor-lite' ),
			'ai' => [
				'active' => false,
			],
		] );

		$this->add_control( 'err_conf_pass', [
			'label'       => __( 'Invalid Password Confirmed', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Eg. Password did not matched', 'essential-addons-for-elementor-lite' ),
			'default'     => __( "Your confirmed password did not match", 'essential-addons-for-elementor-lite' ),
			'ai' => [
				'active' => false,
			],
		] );

		$this->add_control( 'err_loggedin', [
			'label'       => __( 'Already Logged In', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Eg. You are already logged in', 'essential-addons-for-elementor-lite' ),
			'default'     => __( "You are already logged in", 'essential-addons-for-elementor-lite' ),
			'ai' => [
				'active' => false,
			],
		] );

		$this->add_control( 'err_recaptcha', [
			'label'       => __( 'reCAPTCHA Failed', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Eg. reCAPTCHA Validation Failed', 'essential-addons-for-elementor-lite' ),
			'default'     => __( "You did not pass reCAPTCHA challenge.", 'essential-addons-for-elementor-lite' ),
			'ai' => [
				'active' => false,
			],
			'condition'   => [
				'enable_recaptcha' => 'yes',
			],
		] );

		$this->add_control( 'err_cloudflare_turnstile', [
			'label'       => __( 'Cloudflare Turnstile Failed', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Eg. Cloudflare Turnstile Validation Failed', 'essential-addons-for-elementor-lite' ),
			'default'     => __( "You did not pass Cloudflare Turnstile challenge.", 'essential-addons-for-elementor-lite' ),
			'ai' => [
				'active' => false,
			],
			'condition'   => [
				'enable_cloudflare_turnstile' => 'yes',
			],
		] );

		$this->add_control( 'err_reset_password_key_expired', [
			'label'       => __( 'Reset Password Expired Error', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Eg. Your password reset link appears to be invalid. Please request a new link.', 'essential-addons-for-elementor-lite' ),
			'default'     => __( 'Your password reset link appears to be invalid. Please request a new link.', 'essential-addons-for-elementor-lite' ),
			'ai' => [
				'active' => false,
			],
		] );
		
		$this->add_control( 'err_tc', [
			'label'       => __( 'Terms & Condition Error', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Eg. You must accept the Terms & Conditions', 'essential-addons-for-elementor-lite' ),
			'default'     => __( 'You did not accept the Terms and Conditions. Please accept it and try again.', 'essential-addons-for-elementor-lite' ),
			'ai' => [
				'active' => false,
			],
		] );

		$this->add_control( 'err_unknown', [
			'label'       => __( 'Other Errors', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Eg. Something went wrong', 'essential-addons-for-elementor-lite' ),
			'default'     => __( "Something went wrong!", 'essential-addons-for-elementor-lite' ),
			'ai' => [
				'active' => false,
			],
		] );

		$this->add_control( 'err_phone_number_missing', [
			'label'       => __( 'Phone number is missing', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Phone number is missing', 'essential-addons-for-elementor-lite' ),
			'default'     => __( 'Phone number is missing', 'essential-addons-for-elementor-lite' ),
			'ai' => [
				'active' => false,
			],
		] );

		$this->add_control( 'err_phone_number_invalid', [
			'label'       => __( 'Invalid phone number provided', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Invalid phone number provided', 'essential-addons-for-elementor-lite' ),
			'default'     => __( 'Invalid phone number provided', 'essential-addons-for-elementor-lite' ),
			'ai' => [
				'active' => false,
			],
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
			'ai' => [
				'active' => false,
			],
		] );
		$this->add_control( 'success_register', [
			'label'       => __( 'Successful Registration', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => __( 'Registration completed successfully, Check your inbox for password if you did not provided while registering.', 'essential-addons-for-elementor-lite' ),
			'placeholder' => __( 'eg. Registration completed successfully', 'essential-addons-for-elementor-lite' ),
		] );
		$this->add_control( 'success_lostpassword', [
			'label'       => __( 'Lost Password Form Success', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => __( 'Check your email for the confirmation link.', 'essential-addons-for-elementor-lite' ),
			'placeholder' => __( 'eg. Check your email for the confirmation link.', 'essential-addons-for-elementor-lite' ),
		] );
		$this->add_control( 'success_resetpassword', [
			'label'       => __( 'Successful Password Reset', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => __( 'Your password has been reset.', 'essential-addons-for-elementor-lite' ),
			'placeholder' => __( 'eg. Your password has been reset.', 'essential-addons-for-elementor-lite' ),
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
				'description' => '<span class="pro-feature"> Get the  <a href="https://wpdeveloper.com/upgrade/ea-pro" target="_blank">Pro version</a> for more stunning elements and customization options.</span>',
			]
		);

		$this->end_controls_section();

	}

	protected function init_content_register_fields_controls() {
		$custom_fields_image = array_keys( $this->get_eael_custom_profile_fields( 'image' ) );

		$this->start_controls_section( 'section_content_register_fields', [
			'label'      => __( 'Register Form Fields', 'essential-addons-for-elementor-lite' ),
			'conditions' => $this->get_form_controls_display_condition( 'register' ),
		] );
		$this->add_control( 'register_form_field_note', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => __( sprintf( 'Select the type of fields you want to show in the registration form. You can enable custom fields from EA Dashboard » Elements » <a href="%s" target="_blank">Login Register Form Settings</a>.', esc_url( site_url('/wp-admin/admin.php?page=eael-settings') ) ), 'essential-addons-for-elementor-lite' ),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
		] );
		$repeater = new Repeater();

		$repeater->add_control( 'field_type', [
			'label'   => __( 'Type', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SELECT,
			'options' => $this->get_form_field_types(),
			'default' => 'first_name',
		] );

		$repeater->add_control( 'field_type_custom_image_note', [
			'type'            	=> Controls_Manager::RAW_HTML,
			'raw'             	=> __( 'File upload will not work if AJAX submission is enabled.', 'essential-addons-for-elementor-lite' ),
			'condition'       	=> [
				'field_type' 	=> array_keys( $this->get_eael_custom_profile_fields( 'image' ) ),
			],
			'content_classes' => 'eael-warning',
		] );

		$repeater->add_control( 'field_label', [
			'label'   => __( 'Label', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
			'dynamic' => [
				'active' => true,
			],
			'ai' => [
				'active' => false,
			],
		] );

		$repeater->add_control( 'field_type_custom_image_extensions', [
			'label'   		=> __( 'File Extensions', 'essential-addons-for-elementor-lite' ),
			'description'	=> __('Only extensions that is permitted on WordPress.', 'essential-addons-for-elementor-lite'),
			'type'    		=> Controls_Manager::TEXT,
			'default'		=> '',
			'placeholder' 	=> '.png, .jpg, .jpeg',
			'dynamic' => [
				'active' => true,
			],
			'condition' => [
				'field_type' => $custom_fields_image,
			],
		] );

		$repeater->add_control(
            'field_type_custom_image_filename_length',
            [
                'label' 		=> __('Max Filename Length', 'essential-addons-for-elementor-lite'),
                'type' 			=> Controls_Manager::NUMBER,
                'placeholder'	=> '128',
                'default' 		=> '128',
				'min' 			=> '1',
				'max' 			=> '128',
                'condition' => [
					'field_type' => $custom_fields_image,
				],
            ]
        );

		$max_file_size = wp_max_upload_size();
		if( $max_file_size ){
			$max_file_size = $max_file_size / 1048576; //(1024x1024=1048576)
		}

		$repeater->add_control(
            'field_type_custom_image_filesize',
            [
                'label' 		=> __('Max File Size (MB)', 'essential-addons-for-elementor-lite'),
                'description'	=> sprintf( __('Set max file size up to %s MB.', 'essential-addons-for-elementor-lite'), $max_file_size ),
                'type' 			=> Controls_Manager::NUMBER,
                'placeholder' 	=> '5',
                'default' 		=> '5',
				'Min'			=> '1',
				'Max'			=> $max_file_size,
                'condition' 	=> [
					'field_type' => $custom_fields_image,
				],
            ]
        );

		$repeater->add_control( 'placeholder', [
			'label'   => __( 'Placeholder', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '',
			'dynamic' => [
				'active' => true,
			],
			'condition' => [
				'field_type!' => array_merge( $custom_fields_image, ['honeypot'] ),
			],
			'ai' => [
				'active' => false,
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
					'honeypot'
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
			'type'    => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%' ],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 1000,
					'step' => 5,
				],
				'%' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'default' => [
				'unit' => '%',
				'size' => 100,
			],
			'selectors' => [
				'{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}};',
			],
			'condition'       => [
				'field_type!' => [
					'honeypot'
				],
			],
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
				],
				[
					'field_type'  => 'email',
					'field_label' => __( 'Email', 'essential-addons-for-elementor-lite' ),
					'placeholder' => __( 'Email', 'essential-addons-for-elementor-lite' ),
					'required'    => 'yes',
				],
				[
					'field_type'  => 'password',
					'field_label' => __( 'Password', 'essential-addons-for-elementor-lite' ),
					'placeholder' => __( 'Password', 'essential-addons-for-elementor-lite' ),
					'required'    => 'yes',
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

		$this->add_control( 'password_toggle_register', [
			'label'     => __( 'Password Visibility Icon', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SWITCHER,
			'label_off' => __( 'Hide', 'essential-addons-for-elementor-lite' ),
			'label_on'  => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'default'   => '',
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
			'ai' => [
				'active' => false,
			],
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
				'register_redirect_url_prev_page!' => 'yes'
			],
		] );

		$this->add_control( 'register_redirect_url_prev_page', [
			'label'   => __( 'Redirect to Previous Page', 'essential-addons-for-elementor-lite' ),
			'description'   => __( 'Redirect to the last visited page before registration.', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => '',
			'condition'     => [
				'register_action' => 'redirect',
			],
		] );

		if ( current_user_can( 'administrator' ) ) {
			$user_role = $this->get_user_roles();
		} else {
			$user_role = [
				'' => __( 'Default', 'essential-addons-for-elementor-lite' ),
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
			'ai' => [
				'active' => false,
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
			'raw'             => __( '<strong>Note:</strong> You can use dynamic content in the email body like [fieldname]. For example [username] will be replaced by user-typed username. Available tags are: [password], [username], [email], [firstname],[lastname], [website], [loginurl], [password_reset_link], [eael_phone_number] and [sitetitle]. <br>For custom profile fields use slug of the field name e.x. [my_custom_field_1]', 'essential-addons-for-elementor-lite' ),
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
			'ai' => [
				'active' => false,
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
			'raw'             => __( '<strong>Note:</strong> You can use dynamic content in the email body like [fieldname]. For example [username] will be replaced by user-typed username. Available tags are: [username], [email], [firstname],[lastname], [website], [loginurl], [eael_phone_number] and [sitetitle]. <br>For custom profile fields use slug of the field name e.x. [my_custom_field_1]', 'essential-addons-for-elementor-lite' ),
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

	protected function init_content_lostpassword_user_email_controls() {
		/* translators: %s: Site Name */
		$default_subject = __( 'Password Reset Confirmation', 'essential-addons-for-elementor-lite' );
		$default_message = __( 'Someone has requested a password reset for the following account:', 'essential-addons-for-elementor-lite' ) . "\r\n\r\n";
		$default_message .= __( 'Sitename: [sitetitle]', 'essential-addons-for-elementor-lite' ) . "\r\n\r\n";
		$default_message .= __( 'Username: [username]', 'essential-addons-for-elementor-lite' ) . "\r\n\r\n";
		$default_message .= __( 'If this was a mistake, ignore this email and nothing will happen.', 'essential-addons-for-elementor-lite' ) . "\r\n\r\n";
		$default_message .= __( 'To reset your password, visit the following address:', 'essential-addons-for-elementor-lite' ) . "\r\n\r\n";
		$default_message .= '[password_reset_link]' . "\r\n\r\n";
		$default_message .= __( 'Thanks!', 'essential-addons-for-elementor-lite' );

		$this->start_controls_section( 'section_content_lostpassword_email', [
			'label'      => __( 'Lost Password Email Options', 'essential-addons-for-elementor-lite' ),
			'condition' => [
				'enable_reset_password' => 'yes'
			],
		] );

		$this->add_control( 'lostpassword_email_subject', [
			'label'       => __( 'Email Subject', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => $default_subject,
			'default'     => $default_subject,
			'label_block' => true,
			'render_type' => 'none',
			'ai' => [
				'active' => false,
			],
		] );

		$this->add_control( 'lostpassword_email_message', [
			'label'       => __( 'Email Message', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::WYSIWYG,
			'placeholder' => $default_message,
			'default'     => $default_message,
			'label_block' => true,
			'render_type' => 'none',
		] );

		$this->add_control( 'lostpassword_email_content_note', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => __( '<strong>Note:</strong> You can use dynamic content in the email body like [fieldname]. For example [username] will be replaced by user-typed username. Available tags are: [username], [email], [firstname],[lastname], [website], [password_reset_link] and [sitetitle] ', 'essential-addons-for-elementor-lite' ),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			'render_type'     => 'none',
		] );

		$this->add_control( 'lostpassword_email_message_reset_link_text', [
			'label'       => __( 'Reset Link Text', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => __( 'Enter Reset Link Text', 'essential-addons-for-elementor-lite' ),
			'default'     => __( 'Click here to reset your password', 'essential-addons-for-elementor-lite'),
			'label_block' => false,
			'render_type' => 'none',
			'ai' => [
				'active' => false,
			],
		] );

		$this->add_control( 'lostpassword_email_message_reset_link_in_popup', [
			'label'        => __( 'Reset Link in Popup', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
			'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',
			'default'      => '',
		] );

		$this->add_control( 'lostpassword_email_message_reset_link_in_popup_warning', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => __( 'Enable "Reset Link in Popup" feature if your form is displayed in a popup.', 'essential-addons-for-elementor-lite' ),
			'content_classes' => 'eael-warning',
		] );

		$this->add_control( 'lostpassword_email_message_reset_link_popup_selector', [
			'label'       => __( 'Popup Button Selector', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => '.parent .child',
			'description' => __( 'Specify the class for the popup button that can be automatically triggered upon the page\'s loading.', 'essential-addons-for-elementor-lite' ),
			'condition'       => [
				'lostpassword_email_message_reset_link_in_popup' => 'yes',
			],
			'ai' => [
				'active' => false,
			],
		] );

		$this->add_control( 'lostpassword_email_content_type', [
			'label'       => __( 'Email Content Type', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => 'html',
			'render_type' => 'none',
			'options'     => [
				'html'  => __( 'HTML', 'essential-addons-for-elementor-lite' ),
				'plain' => __( 'Plain', 'essential-addons-for-elementor-lite' ),
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
		$this->add_control( 'eael_form_wrap_width_form_type', [
			'label'   => __( 'Apply Width on', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'default' => __( 'All Forms', 'essential-addons-for-elementor-lite' ),
				'lostpassword'  => __( 'Lost Password', 'essential-addons-for-elementor-lite' ),
			],
			'default' => 'default',
		] );
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
				'eael_form_wrap_width_form_type' => 'default'
			],
		] );

		$this->add_responsive_control( "eael_form_wrap_width_lostpassword", [
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
				"{{WRAPPER}} .eael-lr-form-wrapper.eael-lostpassword-form-wrapper" => 'width: {{SIZE}}{{UNIT}};',
				"{{WRAPPER}} .eael-lr-form-wrapper.eael-resetpassword-form-wrapper" => 'width: {{SIZE}}{{UNIT}};',
			],
			'condition'       => [
				'form_form_wrap_po_toggle' => 'yes',
				'eael_form_wrap_width_form_type' => 'lostpassword'
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
		$form_type_for_heading = 'lostpassword' == $form_type ? __( 'Lost Password', 'essential-addons-for-elementor-lite' ) : $form_type;
		$form_type_for_heading = 'resetpassword' == $form_type ? __( 'Reset Password', 'essential-addons-for-elementor-lite' ) : $form_type_for_heading;

		$this->start_controls_section( "section_style_{$form_type}_header_content", [
			'label'      => sprintf( __( '%s Form Header', 'essential-addons-for-elementor-lite' ), ucfirst( $form_type_for_heading ) ),
			// Login Form Header | Register Form Header | Lost Password Form Header | Reset Password Form Header
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
		$this->add_responsive_control( "{$form_type}_form_subtitle_margin", [
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
		$this->add_responsive_control( "{$form_type}_form_subtitle_padding", [
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
			'ai' => [
				'active' => false,
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
			'label'     => __( 'Password Visibility', 'essential-addons-for-elementor-lite' ),
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
				"{{WRAPPER}} .eael-lr-form-wrapper.eael-login-form-wrapper .eael-lr-form-group .dashicons" => 'font-size: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
			],
			'condition'  => [
				'lpv_po_toggle' => 'yes',
			],
		] );
		$this->add_control( "lvp_open_color", [
			'label'     => __( 'Open Eye Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .eael-lr-form-wrapper.eael-login-form-wrapper .eael-lr-form-group .dashicons-visibility" => 'color: {{VALUE}};',
			],
			'condition' => [
				'lpv_po_toggle' => 'yes',
			],
		] );
		$this->add_control( "lvp_close_color", [
			'label'     => __( 'Close Eye Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .eael-lr-form-wrapper.eael-login-form-wrapper .eael-lr-form-group .dashicons-hidden" => 'color: {{VALUE}};',
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
				"{{WRAPPER}} .eael-lr-form-wrapper.eael-login-form-wrapper .eael-lr-form-group .wp-hide-pw" => 'top: {{SIZE}}px;',
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
				"{{WRAPPER}} .eael-lr-form-wrapper.eael-login-form-wrapper .eael-lr-form-group .wp-hide-pw" => 'right: {{SIZE}}px;',
			],
			'condition' => [
				'lpv_po_toggle' => 'yes',
			],
		] );

		$this->end_popover();

		$this->add_control( 'lpv_po_toggle_register', [
			'label'     => __( 'Register Password Visibility Style', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::POPOVER_TOGGLE,
			'condition' => [
				'password_toggle_register' => 'yes',
			],
		] );
		$this->start_popover();

		$this->add_responsive_control( "lpv_size_register", [
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
				"{{WRAPPER}} .eael-lr-form-wrapper.eael-register-form-wrapper .eael-lr-form-group .dashicons" => 'font-size: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
			],
			'condition'  => [
				'lpv_po_toggle_register' => 'yes',
			],
		] );
		$this->add_control( "lvp_open_color_register", [
			'label'     => __( 'Open Eye Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .eael-lr-form-wrapper.eael-register-form-wrapper .eael-lr-form-group .dashicons-visibility" => 'color: {{VALUE}};',
			],
			'condition' => [
				'lpv_po_toggle_register' => 'yes',
			],
		] );
		$this->add_control( "lvp_close_color_register", [
			'label'     => __( 'Close Eye Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .eael-lr-form-wrapper.eael-register-form-wrapper .eael-lr-form-group .dashicons-hidden" => 'color: {{VALUE}};',
			],
			'condition' => [
				'lpv_po_toggle_register' => 'yes',
			],
		] );

		$this->add_responsive_control( "lpv_valign_register", [
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
				"{{WRAPPER}} .eael-lr-form-wrapper.eael-register-form-wrapper .eael-lr-form-group .wp-hide-pw" => 'top: {{SIZE}}px;',
			],
			'condition' => [
				'lpv_po_toggle_register' => 'yes',
			],
		] );
		$this->add_responsive_control( "lpv_halign_register", [
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
				"{{WRAPPER}} .eael-lr-form-wrapper.eael-register-form-wrapper .eael-lr-form-group .wp-hide-pw" => 'right: {{SIZE}}px;',
			],
			'condition' => [
				'lpv_po_toggle_register' => 'yes',
			],
		] );

		$this->end_popover();

		$this->add_control( 'lpv_po_toggle_resetpassword', [
			'label'     => __( 'Reset Password Visibility', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::POPOVER_TOGGLE,
			'condition' => [
				'password_toggle_resetpassword' => 'yes',
			],
		] );
		$this->start_popover();

		$this->add_responsive_control( "lpv_size_resetpassword", [
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
				"{{WRAPPER}} .eael-lr-form-wrapper.eael-resetpassword-form-wrapper .eael-lr-form-group .dashicons" => 'font-size: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
			],
			'condition'  => [
				'lpv_po_toggle_resetpassword' => 'yes',
			],
		] );
		$this->add_control( "lvp_open_color_resetpassword", [
			'label'     => __( 'Open Eye Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .eael-lr-form-wrapper.eael-resetpassword-form-wrapper .eael-lr-form-group .dashicons-visibility" => 'color: {{VALUE}};',
			],
			'condition' => [
				'lpv_po_toggle_resetpassword' => 'yes',
			],
		] );
		$this->add_control( "lvp_close_color_resetpassword", [
			'label'     => __( 'Close Eye Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .eael-lr-form-wrapper.eael-resetpassword-form-wrapper .eael-lr-form-group .dashicons-hidden" => 'color: {{VALUE}};',
			],
			'condition' => [
				'lpv_po_toggle_resetpassword' => 'yes',
			],
		] );

		$this->add_responsive_control( "lpv_valign_resetpassword", [
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
				"{{WRAPPER}} .eael-lr-form-wrapper.eael-resetpassword-form-wrapper .eael-lr-form-group .wp-hide-pw" => 'top: {{SIZE}}px;',
			],
			'condition' => [
				'lpv_po_toggle_resetpassword' => 'yes',
			],
		] );
		$this->add_responsive_control( "lpv_halign_resetpassword", [
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
				"{{WRAPPER}} .eael-lr-form-wrapper.eael-resetpassword-form-wrapper .eael-lr-form-group .wp-hide-pw" => 'right: {{SIZE}}px;',
			],
			'condition' => [
				'lpv_po_toggle_resetpassword' => 'yes',
			],
		] );

		$this->end_popover();

		//Remember Me Style
		$this->add_control( 'eael_form_rm_fields_heading', [
			'type'      => Controls_Manager::HEADING,
			'label'     => __( 'Remember Me', 'essential-addons-for-elementor-lite' ),
			'separator' => 'before',
		] );
		$this->add_control( 'remember_me_style_pot', [
			'label'        => __( 'Style', 'essential-addons-for-elementor-lite' ),
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
				"{{WRAPPER}} .lr-form-wrapper .forget-menot label" => $this->apply_dim( 'margin' ),
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

		$this->add_responsive_control( "eael_form_rm_checkbox_margin", [
			'label'      => __( 'Checkbox Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper .forget-menot input" => $this->apply_dim( 'margin' ),
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
				"{{WRAPPER}} .lr-form-wrapper .eael-forever-forget .forget-menot  input[type=checkbox]:checked" => 'border-color: {{VALUE}} !important;background: {{VALUE}} !important;',
				"{{WRAPPER}} .lr-form-wrapper .eael-forever-forget input[type=checkbox]:hover:not(:checked):not(:disabled)" => 'border-color: {{VALUE}} !important;',
			],
			'condition' => [
				'remember_me_style_pot' => 'yes',
			],
		] );
		$this->end_popover();
		$this->add_group_control( Group_Control_Typography::get_type(), [
			'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
			'name'     => "eael_rm_label_typography",
			'selector' => "{{WRAPPER}} .lr-form-wrapper .forget-menot, {{WRAPPER}} .lr-form-wrapper .forget-menot label",
		] );

		//Forget Password Style
		$this->add_control( 'eael_form_forget_pass_fields_heading', [
			'type'      => Controls_Manager::HEADING,
			'label'     => __( 'Forgot Password', 'essential-addons-for-elementor-lite' ),
			'separator' => 'before',
		] );
		$this->add_control( 'forget_pass_style_pot', [
			'label'        => __( 'Style', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Default', 'essential-addons-for-elementor-lite' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',
		] );

		$this->start_popover();

		$this->add_responsive_control( "eael_form_forget_pass_field_margin", [
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
				'forget_pass_style_pot' => 'yes',
			],
		] );
		$this->add_responsive_control( "eael_form_forget_pass_field_padding", [
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
				'forget_pass_style_pot' => 'yes',
			],
		] );
		$this->add_responsive_control( "eael_form_forget_pass_lbl_margin", [
			'label'      => __( 'Label Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper .forget-pass" => $this->apply_dim( 'margin' ),
			],
			'condition'  => [
				'forget_pass_style_pot' => 'yes',
			],
		] );
		$this->add_responsive_control( "eael_form_forget_pass_lbl_padding", [
			'label'      => __( 'Label Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper .forget-pass" => $this->apply_dim( 'padding' ),
			],
			'condition'  => [
				'forget_pass_style_pot' => 'yes',
			],
		] );

		$this->add_control( 'eael_forget_pass_label_color_normal', [
			'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .lr-form-wrapper .forget-pass a" => 'color: {{VALUE}};',
			],
			'separator' => 'before',
			'condition' => [
				'forget_pass_style_pot' => 'yes',
			],
		] );
		$this->add_control( 'eael_forget_pass_label_bg_color_normal', [
			'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				"{{WRAPPER}} .lr-form-wrapper .forget-pass" => 'background-color: {{VALUE}};',
			],
			'condition' => [
				'forget_pass_style_pot' => 'yes',
			],
		] );
		
		$this->add_control( 'eael_forget_pass_label_color_hover', [
			'label'     => __( 'Hover Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .lr-form-wrapper .forget-pass:hover a" => 'color: {{VALUE}};',
			],
			'condition' => [
				'forget_pass_style_pot' => 'yes',
			],
		] );
		$this->add_control( 'eael_forget_pass_label_bg_color_hover', [
			'label'     => __( 'Background Hover Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				"{{WRAPPER}} .lr-form-wrapper .forget-pass:hover" => 'background-color: {{VALUE}};',
			],
			'condition' => [
				'forget_pass_style_pot' => 'yes',
			],
		] );

		$this->end_popover();
		$this->add_group_control( Group_Control_Typography::get_type(), [
			'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
			'name'     => "eael_forget_pass_label_typography",
			'selector' => "{{WRAPPER}} .lr-form-wrapper .forget-pass a",
		] );

		//Terms & Conditions Style
		$this->add_control( 'eael_form_terms_fields_heading', [
			'type'      => Controls_Manager::HEADING,
			'label'     => __( 'Terms & Conditions', 'essential-addons-for-elementor-lite' ),
			'separator' => 'before',
		] );
		$this->add_control( 'terms_conditions_style_pot', [
			'label'        => __( 'Style', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Default', 'essential-addons-for-elementor-lite' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor-lite' ),
			'return_value' => 'yes',
			'condition'    => [
				'show_terms_conditions' => 'yes',
			],
		] );

		$this->start_popover();
		$this->add_responsive_control( "eael_form_terms_field_margin", [
			'label'      => __( 'Container Margin', 'essential-addons-for-elementor-lite' ),
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
				'terms_conditions_style_pot' => 'yes',
			],
		] );
		$this->add_responsive_control( "eael_form_terms_field_padding", [
			'label'      => __( 'Container Padding', 'essential-addons-for-elementor-lite' ),
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
				'terms_conditions_style_pot' => 'yes',
			],
		] );
		$this->add_responsive_control( "eael_form_terms_lbl_margin", [
			'label'      => __( 'Label Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper .eael_accept_tnc_wrap label" => $this->apply_dim( 'margin' ),
				"{{WRAPPER}} .lr-form-wrapper .eael_accept_tnc_wrap a" => $this->apply_dim( 'margin' ),
			],
			'condition'  => [
				'terms_conditions_style_pot' => 'yes',
			],
		] );
		$this->add_responsive_control( "eael_form_terms_lbl_padding", [
			'label'      => __( 'Label Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper .eael_accept_tnc_wrap label" => $this->apply_dim( 'padding' ),
				"{{WRAPPER}} .lr-form-wrapper .eael_accept_tnc_wrap a" => $this->apply_dim( 'padding' ),
			],
			'condition'  => [
				'terms_conditions_style_pot' => 'yes',
			],
		] );

		$this->add_responsive_control( "eael_form_terms_checkbox_margin", [
			'label'      => __( 'Checkbox Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} .lr-form-wrapper .eael_accept_tnc_wrap input" => $this->apply_dim( 'margin' ),
			],
			'condition'  => [
				'terms_conditions_style_pot' => 'yes',
			],
		] );

		$this->add_control( 'eael_terms_label_color', [
			'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .lr-form-wrapper .eael_accept_tnc_wrap" => 'color: {{VALUE}};',
			],
			'condition' => [
				'terms_conditions_style_pot' => 'yes',
			],
		] );

		$this->add_control( 'eael_terms_label_link_color', [
			'label'     => __( 'Link Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .lr-form-wrapper .eael_accept_tnc_wrap a" => 'color: {{VALUE}};',
			],
			'condition' => [
				'terms_conditions_style_pot' => 'yes',
			],
		] );

		$this->add_control( 'eael_terms_label_bg_color', [
			'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				"{{WRAPPER}} .lr-form-wrapper .eael_accept_tnc_wrap" => 'background-color: {{VALUE}};',
			],
			'condition' => [
				'terms_conditions_style_pot' => 'yes',
			],
		] );
		$this->add_control( 'eael_terms_checkbox_color', [
			'label'     => __( 'Checkbox | Toggle Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"{{WRAPPER}} .lr-form-wrapper .eael_accept_tnc_wrap  input[type=checkbox]:checked" => 'border-color: {{VALUE}} !important; background: {{VALUE}} !important;',
				"{{WRAPPER}} .lr-form-wrapper .eael_accept_tnc_wrap input[type=checkbox]:hover:not(:checked):not(:disabled)" => 'border-color: {{VALUE}} !important;',
			],
			'condition' => [
				'terms_conditions_style_pot' => 'yes',
			],
		] );
		$this->end_popover();
		$this->add_group_control( Group_Control_Typography::get_type(), [
			'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
			'name'     => "eael_terms_label_typography",
			'selector' => "{{WRAPPER}} .lr-form-wrapper .eael_accept_tnc_wrap, {{WRAPPER}} .lr-form-wrapper .eael_accept_tnc_wrap label",
		] );

		$this->end_controls_section();
	}

	protected function init_style_login_button_controls() {
		$this->_init_button_style( 'login' );
	}

	protected function init_style_register_button_controls() {
		$this->_init_button_style( 'register' );
	}

	protected function init_style_lostpassword_button_controls() {
		$this->_init_button_style( 'lostpassword' );
	}
	
	protected function init_style_resetpassword_button_controls() {
		$this->_init_button_style( 'resetpassword' );
	}

	protected function init_style_login_link_controls() {
		$this->_init_link_style( 'login' );
	}

	protected function init_style_register_link_controls() {
		$link_section_conditions = [
			'relation' => 'or',
			'terms' => [
				[
					'name' => 'show_register_link',
					'value' => 'yes',
				],
				[
					'relation' => 'and',
					'terms' => [
						[
							'name'  => 'show_lost_password',
							'value' => 'yes',
						],
						[
							'name'  => 'lost_password_link_type',
							'value' => 'form',
						],
						[
							'name'  => 'show_login_link_lostpassword',
							'value' => 'yes',
						],
					]
				],
				[
					'relation' => 'and',
					'terms' => [
						[
							'name'  => 'default_form_type',
							'value' => 'lostpassword',
						],
						[
							'name'  => 'show_login_link_lostpassword',
							'value' => 'yes',
						],
					]
				],
				
			],
		];

		$this->start_controls_section( "section_style_register_link", [
			'label'     => sprintf( __( '%s Link', 'essential-addons-for-elementor-lite' ), ucfirst( 'Login' ) ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'conditions' => $link_section_conditions,
		] );
		
		if( $this->user_can_register ) {
			$this->_init_link_style( 'register', 0 );
			
			$this->add_control('separator_login_link_for_two_forms',
			[
				'type' => Controls_Manager::RAW_HTML,
				'separator' => 'before'
			]);
		}
		
		$this->_init_link_style( 'lostpassword', 0 );

		$this->end_controls_section();
	}

	protected function init_style_login_recaptcha_controls() {
		$this->_init_recaptcha_style( 'login' );
	}

	protected function init_style_register_recaptcha_controls() {
		$this->_init_recaptcha_style( 'register' );
	}

	protected function init_style_lostpassword_recaptcha_controls() {
		$this->_init_recaptcha_style( 'lostpassword' );
	}

	/**
	 * Print style controls for a specific type of button.
	 *
	 * @param string $button_type the type of the button. accepts login or register.
	 */
	protected function _init_button_style( $button_type = 'login' ) {
		$button_text = 'lostpassword' === $button_type ? esc_html__('Lost Password', 'essential-addons-for-elementor-lite') : ucfirst( $button_type );
		$button_text = 'resetpassword' === $button_type ? esc_html__('Reset Password', 'essential-addons-for-elementor-lite') : $button_text;

		$this->start_controls_section( "section_style_{$button_type}_btn", [
			'label'      => sprintf( __( '%s Button', 'essential-addons-for-elementor-lite' ), esc_html( $button_text ) ),
			'tab'        => Controls_Manager::TAB_STYLE,
			'conditions' => $this->get_form_controls_display_condition( $button_type ),
		] );

		$this->add_control( "{$button_type}_button_style_notice", [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => sprintf( __( 'Here you can style the button displayed on the %s Form', 'essential-addons-for-elementor-lite' ), 
										esc_html( $button_text ), 
										esc_html( $button_text ) 
									),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
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

		//Show spinner
		do_action( "eael/login-register/after-init-{$button_type}-button-style", $this, $button_type );
		
		if ( !$this->pro_enabled ) {
			$this->add_control( "{$button_type}_btn_show_spinner", [
				'label'   => sprintf( __( 'Show Spinner %s', 'essential-addons-for-elementor-lite' ), '<i class="eael-pro-labe eicon-pro-icon"></i>' ),
				'type'    => Controls_Manager::SWITCHER,
				'classes' => 'eael-pro-control',
			] );
		}

		$this->end_controls_section();
	}

	/**
	 * Print style controls for a specific type of reCAPTCHA.
	 *
	 * @param string $form_type the type of the reCAPTCHA. accepts login or register.
	 */
	protected function _init_recaptcha_style( $form_type = 'login' ) {
		$form_label = 'lostpassword' === $form_type ? __( 'Lost Password', 'essential-addons-for-elementor-lite' ) : ucfirst( $form_type );

		$this->start_controls_section( "section_style_{$form_type}_rc", [
			'label'     => sprintf( __( '%s Form reCAPTCHA', 'essential-addons-for-elementor-lite' ), $form_label ),
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
	protected function _init_link_style( $form_type = 'login', $show_as_section = 1 ) {
		$form_name = 'login' === $form_type ? __( 'Register', 'essential-addons-for-elementor-lite' ) : __( 'Login', 'essential-addons-for-elementor-lite' );
		$form_name = 'lostpassword' === $form_type ? __( 'Login (Lost Password)', 'essential-addons-for-elementor-lite' ) : $form_name;
		$link_section_condition = [
			"show_{$form_type}_link" => 'yes',
		];

		if( 'lostpassword' === $form_type ){
			$link_section_condition = [
				'show_lost_password' => 'yes',
				'lost_password_link_type' => 'form',
				'show_login_link_lostpassword' => 'yes',
			];
		}

		if( $show_as_section ){
			$this->start_controls_section( "section_style_{$form_type}_link", [
				'label'     => sprintf( __( '%s Link', 'essential-addons-for-elementor-lite' ), ucfirst( $form_name ) ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => $link_section_condition,
			] );
		}

		$this->add_control( "{$form_type}_link_style_notice", [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => sprintf( __( 'Here you can style the %s link displayed on the %s Form', 'essential-addons-for-elementor-lite' ), 
										'lostpassword' === $form_type ? __('Login', 'essential-addons-for-elementor-lite') : $form_name, 
										'lostpassword' === $form_type ? __('Lost Password', 'essential-addons-for-elementor-lite') : ucfirst( $form_type ) 
									),
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

		if( $show_as_section ){
			$this->end_controls_section();
		}
	}

	/**
	 * Get conditions for displaying login form and registration
	 *
	 * @param string $type
	 *
	 * @return array
	 */
	public function get_form_controls_display_condition( $type = 'login' ) {
		$type = 'resetpassword' === $type ? esc_html( 'lostpassword' ) : esc_html( $type );

		$form_type = in_array( $type, [
			'login',
			'register',
			'lostpassword'
		] ) ? $type : 'login';

		$terms_condition = [
			[
				'name'  => 'default_form_type',
				'value' => $form_type,
			]
		];

		if('lostpassword' === $form_type){
			$terms_condition[] = [
				'relation' => 'and',
				'terms' => [
					[
						'name' => 'show_lost_password',
						'value' => 'yes'
					],
					[
						'name' => 'lost_password_link_type',
						'value' => 'form',
					]
				]
			];
		}else {
			$terms_condition[] = [
				'name'  => "show_{$form_type}_link",
				'value' => 'yes',
			];
		}
		
		$terms_relation_conditions = [
			'relation' => 'or',
			'terms'    => $terms_condition,
		];

		return $terms_relation_conditions;
	}

	public function add_login_register_body_class( $classes ) {
		$classes[] = 'eael-login-register-page-body';

		return $classes;
	}

	protected function render() {

		if ( ! current_user_can( 'manage_options' ) && 'yes' === $this->get_settings_for_display( 'redirect_for_logged_in_user' ) && is_user_logged_in() ) {
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
		$this->should_print_login_form = ( 'login' === $this->default_form || 'yes' === $this->get_settings_for_display( 'show_login_link' ) || 'yes' === $this->get_settings_for_display( 'show_login_link_lostpassword' ) );
		$this->should_print_register_form = ( $this->user_can_register && ( 'register' === $this->get_settings_for_display( 'default_form_type' ) || 'yes' === $this->get_settings_for_display( 'show_register_link' ) ) );
		$this->should_print_lostpassword_form = ( 'lostpassword' === $this->default_form || 'yes' === $this->get_settings_for_display( 'show_lost_password' ) );
		$this->should_print_resetpassword_form_editor = $this->in_editor && 'yes' === $this->get_settings_for_display( 'preview_reset_password' );
		
		if ( Plugin::$instance->documents->get_current() ) {
			$this->page_id = Plugin::$instance->documents->get_current()->get_main_id();
		}

		$this->page_id_for_popup = get_queried_object_id();

		//handle form illustration
		$form_image_id               = ! empty( $this->ds['lr_form_image']['id'] ) ? $this->ds['lr_form_image']['id'] : '';
		$this->form_illustration_pos = ! empty( $this->ds['lr_form_image_position'] ) ? $this->ds['lr_form_image_position'] : 'left';
		$this->form_illustration_url = Group_Control_Image_Size::get_attachment_image_src( $form_image_id, 'lr_form_image', $this->ds );

		$form_logo_id        = ! empty( $this->ds['lr_form_logo']['id'] ) ? $this->ds['lr_form_logo']['id'] : '';
		$this->form_logo     = Group_Control_Image_Size::get_attachment_image_src( $form_logo_id, 'lr_form_logo', $this->ds );
		$this->form_logo_pos = ! empty( $this->ds['lr_form_logo_position'] ) ? $this->ds['lr_form_logo_position'] : 'inline';
		$login_redirect_url = '';
		$resetpassword_redirect_url = '';

		if ( ! empty( $this->ds['redirect_after_login'] ) && 'yes' === $this->ds['redirect_after_login'] ) {
			$login_redirect_url = !empty( $this->ds[ 'redirect_url' ][ 'url' ] ) ? esc_url( $this->ds[ 'redirect_url' ][ 'url' ] ) : '';
		}
		
		$this->login_custom_redirect_url = apply_filters( 'eael/login-register/login-redirect-url', $login_redirect_url, $this );

		if ( ! empty( $this->ds['redirect_after_resetpassword'] ) && 'yes' === $this->ds['redirect_after_resetpassword'] ) {
			$resetpassword_redirect_url = !empty( $this->ds[ 'redirect_url_resetpassword' ][ 'url' ] ) ? esc_url( $this->ds[ 'redirect_url_resetpassword' ][ 'url' ] ) : '';
		}

		if ( ! empty( $this->ds['lostpassword_email_message_reset_link_in_popup'] ) && 'yes' === $this->ds['lostpassword_email_message_reset_link_in_popup'] ) {
			$this->resetpassword_in_popup_selector = ! empty( $this->ds[ 'lostpassword_email_message_reset_link_popup_selector' ] ) ? sanitize_text_field( $this->ds[ 'lostpassword_email_message_reset_link_popup_selector' ] ) : '';
		}

		$login_recaptcha_version = $register_recaptcha_version = $lostpassword_recaptcha_version = ! empty( $this->ds['login_register_recaptcha_version'] ) ? $this->ds['login_register_recaptcha_version'] : 'v2';

		if ( get_option('eael_recaptcha_sitekey_v3') && ( 'v3' === $login_recaptcha_version || 'v3' === $register_recaptcha_version || 'v3' === $lostpassword_recaptcha_version)  ) {
			$site_key = esc_html( get_option('eael_recaptcha_sitekey_v3') );
			
	        if ( $recaptcha_language = esc_html( get_option( 'eael_recaptcha_language_v3' ) ) ) {
		        $recaptcha_api_args1['hl'] = $recaptcha_language;
	        }

            $recaptcha_api_args1['render'] = $site_key;
            
	        $recaptcha_api_args1 = apply_filters( 'eael_lr_recaptcha_api_args_v3', $recaptcha_api_args1 );
	        $recaptcha_api_args1 = http_build_query( $recaptcha_api_args1 );
            wp_register_script('eael-recaptcha-v3', "https://www.recaptcha.net/recaptcha/api.js?{$recaptcha_api_args1}", false, EAEL_PLUGIN_VERSION, false);
			wp_enqueue_script('eael-recaptcha-v3');
			wp_dequeue_script('eael-recaptcha');
        }
		?>
        <div class="eael-login-registration-wrapper <?php echo empty( $form_image_id ) ? '' : esc_attr( 'has-illustration' ); ?>"
             data-is-ajax="<?php echo esc_attr( $this->get_settings_for_display( 'enable_ajax' ) ); ?>"
             data-widget-id="<?php echo esc_attr( $this->get_id() ); ?>"
             data-page-id="<?php echo esc_attr( $this->page_id ); ?>"
             data-recaptcha-sitekey="<?php echo esc_attr( get_option( 'eael_recaptcha_sitekey' ) ); ?>"
			 data-recaptcha-sitekey-v3="<?php echo esc_attr( get_option( 'eael_recaptcha_sitekey_v3' ) ); ?>"
			 data-login-recaptcha-version="<?php echo esc_attr( $login_recaptcha_version ); ?>"
			 data-register-recaptcha-version="<?php echo esc_attr( $register_recaptcha_version ); ?>"
			 data-lostpassword-recaptcha-version="<?php echo esc_attr( $lostpassword_recaptcha_version ); ?>"
             data-redirect-to="<?php echo esc_attr( $this->login_custom_redirect_url ); ?>"
             data-resetpassword-redirect-to="<?php echo esc_attr( $resetpassword_redirect_url ); ?>"
        >
			<?php
			$this->print_resetpassword_form(); // set a new password; user will land on this form via email reset password link.
			$this->print_login_form();
			$this->print_register_form();
			$this->print_lostpassword_form(); //request for a new password.
			
			if ( $this->recaptcha_badge_hide ) {
			?>
				<div class="eael-recaptcha-no-branding-wrapper">
					<small>
					This site is protected by reCAPTCHA and the Google
					<a href="https://policies.google.com/privacy">Privacy Policy</a> and
					<a href="https://policies.google.com/terms">Terms of Service</a> apply.
					</small>
				</div>
			<?php
			}
			?>
        </div>

		<?php
	}

	protected function print_login_form() {
		if ( $this->should_print_login_form ) {
			// prepare all login form related vars
			$default_hide_class = ( 'register' === $this->default_form || 'lostpassword' === $this->default_form || $this->should_print_resetpassword_form_editor || isset($_GET['eael-register']) || isset($_GET['eael-lostpassword']) || isset($_GET['eael-resetpassword']) ) ? 'eael-lr-d-none' : '';

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

			$reg_link = sprintf( $reg_link_placeholder, $reg_message, esc_url( $reg_url ), esc_attr( $reg_link_action ), $reg_link_text, $reg_atts );


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


			$btn_text         = ! empty( $this->ds['login_button_text'] ) ? sanitize_text_field( $this->ds['login_button_text'] ) : '';
			$show_logout_link = ( ! empty( $this->ds['show_log_out_message'] ) && 'yes' === $this->ds['show_log_out_message'] );
			$show_rememberme  = ( ! empty( $this->ds['login_show_remember_me'] ) && 'yes' === $this->ds['login_show_remember_me'] );
			$remember_text    = isset( $this->ds['remember_text'] ) ? $this->ds['remember_text'] : esc_html__( 'Remember Me', 'essential-addons-for-elementor-lite');
			$remember_checked = ( ! empty( $this->ds['login_form_fields_remember_me_checked'] ) && 'yes' === $this->ds['login_form_fields_remember_me_checked'] );
			$rm_type          = ! empty( $this->ds['remember_me_style'] ) ? $this->ds['remember_me_style'] : '';
			$show_pv_icon     = ( ! empty( $this->ds['password_toggle'] ) && 'yes' === $this->ds['password_toggle'] );

			//Loss password
			$show_lp = ( ! empty( $this->ds['show_lost_password'] ) && 'yes' === $this->ds['show_lost_password'] );
			$lp_text = ! empty( $this->ds['lost_password_text'] ) ? HelperCLass::eael_wp_kses($this->ds['lost_password_text']) : __( 'Forgot Password?', 'essential-addons-for-elementor-lite' );
			$lp_link = sprintf( '<a href="%s">%s</a>', esc_url( wp_lostpassword_url() ), $lp_text );
			if ( ! empty( $this->ds['lost_password_link_type'] ) && 'custom' === $this->ds['lost_password_link_type'] ) {
				$lp_url  = ! empty( $this->ds['lost_password_url']['url'] ) ? $this->ds['lost_password_url']['url'] : wp_lostpassword_url();
				$lp_atts = ! empty( $this->ds['lost_password_url']['is_external'] ) ? ' target="_blank"' : '';
				$lp_atts .= ! empty( $this->ds['lost_password_url']['nofollow'] ) ? ' rel="nofollow"' : '';
				$lp_link = sprintf( '<a href="%s" %s >%s</a>', esc_url( $lp_url ), $lp_atts, $lp_text );
			} else if ( ! empty( $this->ds['lost_password_link_type'] ) && 'form' === $this->ds['lost_password_link_type'] ){
				$lp_link = sprintf( '<a id="eael-lr-lostpassword-toggle" href="" data-action="form">%s</a>', $lp_text );
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

			$show_login_spinner  = !empty( $this->ds['login_btn_show_spinner'] ) ? $this->ds['login_btn_show_spinner'] : '';
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
						$logout_link_text = ! empty( $this->ds['log_out_link_text'] ) ? $this->ds['log_out_link_text'] : 'You are already logged in as [username]. ([logout_link])';
						$logout_link_text = $this->replace_placeholders_logout_link_text($logout_link_text);
						echo wp_kses( __( $logout_link_text, 'essential-addons-for-elementor-lite' ), HelperCLass::eael_allowed_tags() );
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
                                <div class="eael-lr-form-group eael-user-login">
									<?php if ( $display_label && $u_label ) {
										echo '<label for="eael-user-login" class="eael-field-label">' . wp_kses( $u_label, HelperCLass::eael_allowed_tags() ) . '</label>';
									} ?>
                                    <input type="text"
                                           name="eael-user-login"
                                           id="eael-user-login"
                                           class="eael-lr-form-control"
                                           placeholder="<?php if ( $display_label && $u_ph ) {
										       echo esc_attr( $u_ph );
									       } ?>"
                                           required>
									<?php
									if ( $show_icon ) {
										echo '<i class="fas fa-user"></i>';
									} ?>
                                </div>
                                <div class="eael-lr-form-group eael-user-password">
									<?php if ( $display_label && $p_label ) {
										echo '<label for="eael-user-password" class="eael-field-label">' . wp_kses( $p_label, HelperCLass::eael_allowed_tags() ) . '</label>';
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
												   <?php if ( $remember_checked ) : ?>
												   checked 
												   <?php endif; ?>
                                                   class="remember-me <?php echo esc_attr( $rm_type ); ?>"
                                                   value="forever">
                                            <label for="rememberme"
                                                   class="eael-checkbox-label rememberme"><?php echo esc_html( $remember_text ); ?></label>
                                        </p>
									<?php }
									if ( $show_lp ) {
										echo '<p class="forget-pass">' . wp_kses( $lp_link, HelperCLass::eael_allowed_tags( [ 'a' => [ 'data-action' => [] ] ] ) ) . '</p>';
									} ?>

                                </div>

								<?php
								do_action( 'eael/login-register/before-recaptcha', $this );
								$this->print_bot_protection_node( 'login' );
								do_action( 'eael/login-register/after-recaptcha', $this );
								do_action( 'eael/login-register/before-login-footer', $this );
								?>


                                <div class="eael-lr-footer">
									<div class="eael-lr-form-loader-wrapper">
                                    	<input type="submit"
                                           name="eael-login-submit"
                                           id="eael-login-submit"
                                           class="g-recaptcha eael-lr-btn eael-lr-btn-block <?php echo esc_attr( $btn_align ); ?>"
                                           value="<?php echo esc_attr( $btn_text ); ?>"/>
										
										<?php if( !empty( $show_login_spinner ) && 'true' === $show_login_spinner ): ?>
										<span class="eael-lr-form-loader eael-lr-login-form-loader d-none<?php echo esc_attr($this->in_editor ? '-editor' : '') ?>">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M304 48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zm0 416a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM48 304a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm464-48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM142.9 437A48 48 0 1 0 75 369.1 48 48 0 1 0 142.9 437zm0-294.2A48 48 0 1 0 75 75a48 48 0 1 0 67.9 67.9zM369.1 437A48 48 0 1 0 437 369.1 48 48 0 1 0 369.1 437z"/></svg>
										</span>
										<?php endif; ?>

									</div>
									<?php if ( $show_reg_link ) { ?>
                                        <div class="eael-sign-wrapper <?php echo esc_attr( $link_align ); ?>">
											<?php echo wp_kses( $reg_link, HelperCLass::eael_allowed_tags( [ 'a' => [ 'data-action' => [] ] ] ) ); ?>
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

			<script>
				jQuery(document).ready(function($){
					var eael_get_login_status = localStorage.getItem( 'eael-is-login-form' );
					if( eael_get_login_status === 'true' ) {
						setTimeout(function() {
							var button = jQuery('[eael-login="yes"]');
							if( ! button.hasClass('eael-clicked') ) {
								button.trigger('click').addClass('eael-clicked');
							}
						}, 100);

						setTimeout(function() {
							jQuery('[eael-login="yes"]').removeClass('eael-clicked')
						}, 500);
					}
				});
			</script>
			<?php
		}
	}

	protected function print_register_form() {
		if ( $this->should_print_register_form ) {
			$default_hide_class = ( 'login' === $this->default_form || 'lostpassword' === $this->default_form || $this->should_print_resetpassword_form_editor || isset($_GET['eael-lostpassword']) || isset($_GET['eael-resetpassword']) ) && !isset($_GET['eael-register']) ? 'eael-lr-d-none' : ''; //eael-register flag for show error/success message when formal form submit
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
			$eael_phone_number_exists = 0;
			$honeypot_exists = 0;
			
			$f_labels            = [
				'email'            	=> __( 'Email', 'essential-addons-for-elementor-lite' ),
				'password'         	=> __( 'Password', 'essential-addons-for-elementor-lite' ),
				'confirm_password' 	=> __( 'Confirm Password', 'essential-addons-for-elementor-lite' ),
				'user_name'        	=> __( 'Username', 'essential-addons-for-elementor-lite' ),
				'first_name'       	=> __( 'First Name', 'essential-addons-for-elementor-lite' ),
				'last_name'        	=> __( 'Last Name', 'essential-addons-for-elementor-lite' ),
				'website'          	=> __( 'Website', 'essential-addons-for-elementor-lite' ),
				'eael_phone_number'	=> __( 'Phone', 'essential-addons-for-elementor-lite' ),
				'honeypot'			=> __( 'Honeypot', 'essential-addons-for-elementor-lite' ),
			];

			$eael_custom_profile_fields_text = $this->get_eael_custom_profile_fields( 'text' );
			$eael_custom_profile_fields_image = $this->get_eael_custom_profile_fields( 'image' );
			$eael_custom_profile_fields = array_merge( $eael_custom_profile_fields_text, $eael_custom_profile_fields_image );

			$f_labels = array_merge($f_labels, $eael_custom_profile_fields);

			foreach( $eael_custom_profile_fields as $eael_custom_profile_field_key => $eael_custom_profile_field_value ) {
				$eael_custom_profile_field_key_exists = $eael_custom_profile_field_key . '_exists';
				$$eael_custom_profile_field_key_exists = 0; // dynamic variable
			}

			$repeated_f_labels   = [];

			//Login link related
			$lgn_link_action = ! empty( $this->ds['login_link_action'] ) ? sanitize_text_field( $this->ds['login_link_action'] ) : 'form';
			$show_lgn_link   = 'yes' === $this->get_settings( 'show_login_link' );
			$lgn_link_text   = ! empty( $this->get_settings( 'login_link_text' ) ) ? HelperCLass::eael_wp_kses($this->get_settings( 'login_link_text' )) : __( 'Login', 'essential-addons-for-elementor-lite' );
			$btn_text        = ! empty( $this->ds['reg_button_text'] ) ? sanitize_text_field( $this->ds['reg_button_text'] ) : '';

			$parts                = explode( "\n", $lgn_link_text );
			$lgn_link_text        = array_pop( $parts );
			$lgn_message          = array_shift( $parts );
			$lgn_link_placeholder = '<span class="d-ib">%1$s</span> <a href="%2$s" id="eael-lr-login-toggle" class="eael-lr-link" data-action="%3$s" %5$s>%4$s</a>';
			$lgn_url              = $lgn_atts = '';

			$show_register_spinner  = !empty( $this->ds['register_btn_show_spinner'] ) ? $this->ds['register_btn_show_spinner'] : '';
			$show_pv_icon     		= ( ! empty( $this->ds['password_toggle_register'] ) && 'yes' === $this->ds['password_toggle_register'] );

			switch ( $lgn_link_action ) {
				case 'custom':
					$lgn_url  = ! empty( $this->ds['custom_login_url']['url'] ) ? sanitize_url( $this->ds['custom_login_url']['url'] ) : '';
					$lgn_atts = ! empty( $this->ds['custom_login_url']['is_external'] ) ? ' target="_blank"' : '';
					$lgn_atts .= ! empty( $this->ds['custom_login_url']['nofollow'] ) ? ' rel="nofollow"' : '';
					break;
				case 'default':
					$lgn_url = wp_login_url();
					break;
			}
			$lgn_link = sprintf( $lgn_link_placeholder, $lgn_message, esc_url( $lgn_url ), esc_attr( $lgn_link_action ), $lgn_link_text, $lgn_atts );

			// btn alignment
			$btn_align  = isset( $this->ds['register_btn_align'] ) ? esc_html( $this->ds['register_btn_align'] ) : '';
			$link_align = isset( $this->ds['register_link_align'] ) ? esc_html( $this->ds['register_link_align'] ) : '';
			// reCAPTCHA style
			$rc_theme = isset( $this->ds['register_rc_theme'] ) ? esc_html( $this->ds['register_rc_theme'] ) : 'light';
			$rc_size  = isset( $this->ds['register_rc_size'] ) ? esc_html( $this->ds['register_rc_size'] ) : 'normal';
			// input icons
			$show_icon  = ( $this->pro_enabled && ! empty( $this->ds['show_register_icon'] ) && 'yes' === $this->ds['show_register_icon'] );
			$icon_class = $show_icon ? 'lr-icon-showing' : '';

			$use_weak_password = true;
			if( isset( $this->ds['use_weak_password'] ) ){
				$use_weak_password = !empty( $this->ds['use_weak_password'] ) ? 1 : 0;
			}

			$password_min_length = !empty( $this->ds['weak_pass_min_char'] ) ? intval( $this->ds['weak_pass_min_char'] ) : '';
			$password_one_uppercase = !empty( $this->ds['weak_pass_one_uppercase'] ) ? true : false;
			$password_one_lowercase = !empty( $this->ds['weak_pass_one_lowercase'] ) ? true : false;
			$password_one_number = !empty( $this->ds['weak_pass_one_number'] ) ? true : false;
			$password_one_special = !empty( $this->ds['weak_pass_one_special'] ) ? true : false;

			ob_start();
			?>
            <section
                    id="eael-register-form-wrapper"
                    class="<?php echo esc_attr( $default_hide_class ); ?>"
                    data-recaptcha-theme="<?php echo esc_attr( $rc_theme ); ?>"
                    data-recaptcha-size="<?php echo esc_attr( $rc_size ); ?>"
                    data-use-weak-password="<?php echo esc_attr( $use_weak_password ); ?>"
                    data-password-min-length="<?php echo esc_attr( $password_min_length ); ?>"
                    data-password-one-uppercase="<?php echo esc_attr( $password_one_uppercase ); ?>"
                    data-password-one-lowercase="<?php echo esc_attr( $password_one_lowercase ); ?>"
                    data-password-one-number="<?php echo esc_attr( $password_one_number ); ?>"
                    data-password-one-special="<?php echo esc_attr( $password_one_special ); ?>"
					>
                <div class="eael-register-form-wrapper eael-lr-form-wrapper style-2 <?php echo esc_attr( $icon_class ); ?>">
					<?php if ( 'left' === $this->form_illustration_pos ) {
						$this->print_form_illustration();
					} ?>
                    <div class="lr-form-wrapper">
						<?php
						$this->print_form_header( 'register' );
						do_action( 'eael/login-register/before-register-form', $this );

						$has_file_input = 0;
						foreach ( $this->ds['register_fields'] as $single_field ) {
							$single_field_type = $single_field['field_type'];

							if( ! empty( $eael_custom_profile_fields_image[ $single_field_type ] ) ){
								$has_file_input = 1;
								break;
							}
						}
						?>
                        <form class="eael-register-form eael-lr-form"
                              id="eael-register-form"
                              method="post"
							  <?php if ( $has_file_input ) : ?>
								enctype="multipart/form-data"
							  <?php endif; ?>
							  >
							<?php do_action( 'eael/login-register/after-register-form-open', $this ); ?>
							<?php // Print all dynamic fields
							foreach ( $this->ds['register_fields'] as $f_index => $field ) :
								$field_type = $field['field_type'];
								$dynamic_field_name = "{$field_type}_exists";
								$$dynamic_field_name ++; //NOTE, double $$ intentional. Dynamically update the var check eg. $username_exists++ to prevent user from using the same field twice
								// is same field repeated?
								if( isset( $$dynamic_field_name ) ){
									if ( $$dynamic_field_name > 1 ) {
										$repeated_f_labels[] = $f_labels[ $field_type ];
									}
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
									case 'eael_phone_number':
									case 'user_name':
									case 'first_name':
									case 'last_name':
									case 'honeypot':
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

								if( ! empty( $eael_custom_profile_fields_text[ $field_type ] ) ){
									$field_input_type = 'text';
								}

								if( ! empty( $eael_custom_profile_fields_image[ $field_type ] ) ){
									$field_input_type = 'file';
								}

								$field_type_honeypot = 'eaelhoneyp' . esc_attr( $this->get_id() );
								$field_type = 'honeypot' === $field_type ? $field_type_honeypot : $field_type;

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
								$field_group_key_array = [
									'class' => [
										'eael-lr-form-group',
										'elementor-repeater-item-'.$field['_id'],
										'eael-field-type-' . $field_type,
									],
								];

								if ( $field_type_honeypot === $field_type ){
									$field_group_key_array['style'] = 'display:none;';
									$field['field_label'] = '';
								}

								$this->add_render_attribute( [
									$field_group_key => $field_group_key_array,
								] );

								?>
                                <div <?php $this->print_render_attribute_string( $field_group_key ) ?>>
									<?php
									if ( 'yes' === $this->ds['show_labels'] && ! empty( $field['field_label'] ) ) {
										echo '<label '; $this->print_render_attribute_string( $label_key ); echo '>' . wp_kses( $field['field_label'], HelperCLass::eael_allowed_tags() ) . '</label>';
									}
									if( 'password' === $field['field_type'] ){
										echo '<div class="eael-lr-password-wrapper-register">';
											echo '<input '; $this->print_render_attribute_string( $input_key ); echo '>';

											if ( $show_pv_icon ) { ?>
												<button type="button"
														id="wp-hide-pw-register"
														class="wp-hide-pw hide-if-no-js"
														aria-label="Show password">
													<span class="dashicons dashicons-visibility"
														aria-hidden="true"></span>
												</button>
											<?php }

											if ( $show_icon && ! empty( $field['icon'] ) ) {
												Icons_Manager::render_icon( $field['icon'], [ 'aria-hidden' => 'true' ] );
											}
										echo '</div>';
									} else {
										echo '<input '; $this->print_render_attribute_string( $input_key ); echo '>';

										if ( $show_icon && ! empty( $field['icon'] ) ) {
											Icons_Manager::render_icon( $field['icon'], [ 'aria-hidden' => 'true' ] );
										}
									}
									?>

								<?php
								if ( 'password' === $field['field_type'] ) {
									do_action( 'eael/login-register/after-password-field', $this );
								}
								
								if ( 'email' === $field['field_type'] ) {
									do_action( 'eael/login-register/after-email-field' );
								}
								
                                echo "</div>";
							endforeach;
							$this->print_necessary_hidden_fields( 'register' );
							$this->print_terms_condition_notice();

							do_action( 'eael/login-register/before-register-recaptcha', $this );
							$this->print_bot_protection_node( 'register' );
							do_action( 'eael/login-register/after-register-recaptcha', $this );
							do_action( 'eael/login-register/before-register-footer', $this );
							?>

                            <div class="eael-lr-footer">
								<div class="eael-lr-form-loader-wrapper">
                                	<input type="submit"
                                       name="eael-register-submit"
                                       id="eael-register-submit"
                                       class="eael-lr-btn eael-lr-btn-block<?php echo esc_attr( $btn_align ); ?>"
                                       value="<?php echo esc_attr( $btn_text ); ?>"/>
										
									<?php if( !empty( $show_register_spinner ) && 'true' === $show_register_spinner ): ?>
									<span class="eael-lr-form-loader eael-lr-register-form-loader d-none<?php echo esc_attr($this->in_editor ? '-editor' : ''); ?>">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M304 48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zm0 416a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM48 304a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm464-48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM142.9 437A48 48 0 1 0 75 369.1 48 48 0 1 0 142.9 437zm0-294.2A48 48 0 1 0 75 75a48 48 0 1 0 67.9 67.9zM369.1 437A48 48 0 1 0 437 369.1 48 48 0 1 0 369.1 437z"/></svg>
									</span>
									<?php endif; ?>

								</div>
								<?php if ( $show_lgn_link ) { ?>
                                    <div class="eael-sign-wrapper <?php echo esc_attr( $link_align ); ?>">
										<?php echo wp_kses( $lgn_link, HelperCLass::eael_allowed_tags( [ 'a' => [ 'data-action' => [] ] ] ) ); ?>
                                    </div>
								<?php } ?>
                            </div>

							<?php do_action( 'eael/login-register/after-register-footer', $this ); ?>

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
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $form_markup; //XSS OK, data sanitized already.
			} else {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $form_markup; //XSS OK, data sanitized already.
			}
		}
	}

	protected function print_lostpassword_form(){
		if ( $this->should_print_lostpassword_form ) {
			$form_not_enabled = ! ( 'lostpassword' === $this->default_form || ( 'yes' === $this->get_settings_for_display( 'show_lost_password' ) && 'form' === $this->get_settings_for_display( 'lost_password_link_type' ) ) );

			if( $form_not_enabled && isset( $_GET['eael-lostpassword'] ) ){
				wp_safe_redirect( remove_query_arg( array( 'eael-lostpassword' ) ) );
				exit;
			}

			// prepare all lostpassword form related vars
			$default_hide_class = ( 'register' === $this->default_form || 'login' === $this->default_form || $this->should_print_resetpassword_form_editor || isset($_GET['eael-register']) || isset($_GET['eael-resetpassword']) ) && !isset($_GET['eael-lostpassword']) ? 'eael-lr-d-none' : '';

			//Login link related
			$login_link_action_lostpassword = ! empty( $this->ds['login_link_action_lostpassword'] ) ? esc_html( $this->ds['login_link_action_lostpassword'] ) : 'form';
			$show_login_link_lostpassword   = ( 'yes' === $this->get_settings( 'show_login_link_lostpassword' ) );
			$login_link_text_lostpassword   = ! empty( $this->get_settings( 'login_link_text_lostpassword' ) ) ? HelperCLass::eael_wp_kses($this->get_settings( 'login_link_text_lostpassword' )) : __( 'Login', 'essential-addons-for-elementor-lite' );
			$parts           = explode( "\n", $login_link_text_lostpassword );
			$login_link_text_lostpassword   = array_pop( $parts );
			$login_message_lostpassword     = array_shift( $parts );

			$success_key = 'eael_lostpassword_success_' . esc_attr( $this->get_id() );
			$lostpassword_success = apply_filters( 'eael/login-register/lostpassword-success-message', get_option( $success_key ) );
			$hide_class_after_submission = ! empty( $lostpassword_success ) ? 'eael-d-none' : ''; 

			$login_link_placeholder_lostpassword = '<span class="d-ib">%1$s</span> <a href="%2$s" id="eael-lr-login-toggle-lostpassword" class="eael-lr-link" data-action="%3$s" %5$s %6$s>%4$s</a>';
			$login_atts_lostpassword             = $login_url_lostpassword = '';
			switch ( $login_link_action_lostpassword ) {
				case 'custom':
					$login_url_lostpassword  = ! empty( $this->ds['custom_login_url_lostpass']['url'] ) ? esc_url_raw( $this->ds['custom_login_url_lostpass']['url'] ) : '';
					$login_atts_lostpassword = ! empty( $this->ds['custom_login_url_lostpass']['is_external'] ) ? ' target="_blank"' : '';
					$login_atts_lostpassword .= ! empty( $this->ds['custom_login_url_lostpass']['nofollow'] ) ? ' rel="nofollow"' : '';
					$this->add_link_attributes( 'login_button_lostpassword', $this->ds['custom_login_url_lostpass'] );
					break;
				case 'default':
					$login_url_lostpassword = wp_login_url();
					break;
			}

			$login_link_lostpassword = sprintf( $login_link_placeholder_lostpassword, $login_message_lostpassword, esc_url( $login_url_lostpassword ), esc_attr( $login_link_action_lostpassword ), $login_link_text_lostpassword, $login_atts_lostpassword, $this->get_render_attribute_string( 'login_button_lostpassword' ) );

			// lost password form fields related
			$label_type      = ! empty( $this->ds['lostpassword_label_types'] ) ? esc_html( $this->ds['lostpassword_label_types'] ) : 'default';
			$is_custom_label = ( 'custom' === $label_type );
			$display_label   = ( 'none' !== $label_type );

			//Default label n placeholder
			$u_label = $u_ph = esc_html__( 'Username or Email Address', 'essential-addons-for-elementor-lite' );
			
			// custom label n placeholder
			if ( $is_custom_label ) {
				$u_label = isset( $this->ds['lostpassword_user_label'] ) ? esc_html__( wp_strip_all_tags( $this->ds['lostpassword_user_label'] ), 'essential-addons-for-elementor-lite' ) : '';
				$u_ph    = isset( $this->ds['lostpassword_user_placeholder'] ) ? esc_html__( wp_strip_all_tags( $this->ds['lostpassword_user_placeholder'] ), 'essential-addons-for-elementor-lite' ) : '';
			}
			$btn_text         = ! empty( $this->ds['lostpassword_button_text'] ) ? sanitize_text_field( $this->ds['lostpassword_button_text'] ) : '';

			// btn alignment
			$btn_align = isset( $this->ds['lostpassword_btn_align'] ) ? esc_html( $this->ds['lostpassword_btn_align'] ) : '';
			// link alignment
			$link_align = isset( $this->ds['lostpassword_link_align'] ) ? esc_html( $this->ds['lostpassword_link_align'] ) : '';

			// reCAPTCHA style
			$rc_theme = isset( $this->ds['lostpassword_rc_theme'] ) ? esc_html( $this->ds['lostpassword_rc_theme'] ) : 'light';
			$rc_size  = isset( $this->ds['lostpassword_rc_size'] ) ? esc_html( $this->ds['lostpassword_rc_size'] ) : 'normal';
			
			// input icons
			$show_icon  = ( $this->pro_enabled && ! empty( $this->ds['show_lostpassword_icon'] ) && 'yes' === esc_html( $this->ds['show_lostpassword_icon'] ) );
			$icon_class = $show_icon ? 'lr-icon-showing' : '';
			?>
            <section
                    id="eael-lostpassword-form-wrapper"
                    class="<?php echo esc_attr( $default_hide_class ); ?>"
					data-recaptcha-theme="<?php echo esc_attr( $rc_theme ); ?>"
                    data-recaptcha-size="<?php echo esc_attr( $rc_size ); ?>"
                    >
                <div class="eael-lostpassword-form-wrapper eael-lr-form-wrapper style-2 <?php echo esc_attr( $icon_class ); ?>">
					<?php
					if ( 'left' === $this->form_illustration_pos ) {
						$this->print_form_illustration('lostpassword');
					}
					?>
					<div class="lr-form-wrapper">
						<?php $this->print_form_header( 'lostpassword' ); ?>
						<?php do_action( 'eael/login-register/before-lostpassword-form', $this ); ?>
						<form class="eael-lostpassword-form eael-lr-form"
							  id="eael-lostpassword-form"
							  method="post">
							<?php do_action( 'eael/login-register/after-lostpassword-form-open', $this ); ?>
							<div class="eael-lr-form-group <?php echo esc_attr( $hide_class_after_submission ); ?>">
								<?php if ( $display_label && $u_label ) {
									printf( '<label for="eael-user-lostpassword" class="eael-field-label">%s</label>', esc_html__( $u_label, 'essential-addons-for-elementor-lite' ) );
								} ?>
								<input type="text"
									   name="eael-user-lostpassword"
									   id="eael-user-lostpassword"
									   class="eael-lr-form-control"
									   placeholder="<?php if ( $display_label && $u_ph ) {
										   echo esc_attr( $u_ph );
									   } ?>"
									   required>
								<?php
								if ( $show_icon ) {
									echo '<i class="fas fa-user"></i>';
								} ?>
							</div>

							<?php
							if( empty( $lostpassword_success ) ){
								do_action( 'eael/login-register/before-lostpassword-recaptcha', $this );
								$this->print_bot_protection_node( 'lostpassword' );
								do_action( 'eael/login-register/after-lostpassword-recaptcha', $this );
							}
							do_action( 'eael/login-register/before-lostpassword-footer', $this );
							?>

							<div class="eael-lr-footer">
								<input type="submit"
									   name="eael-lostpassword-submit"
									   id="eael-lostpassword-submit"
									   class="g-recaptcha eael-lr-btn eael-lr-btn-block <?php echo esc_attr( $btn_align ); ?>  <?php echo esc_attr( $hide_class_after_submission ); ?>"
									   value="<?php echo esc_attr( $btn_text ); ?>"/>
								<?php if ( $show_login_link_lostpassword ) { ?>
									<div class="eael-sign-wrapper <?php echo esc_attr( $link_align ); ?>">
										<?php echo wp_kses( $login_link_lostpassword, HelperCLass::eael_allowed_tags( [ 'a' => [ 'data-action' => [] ] ] ) ); ?>
									</div>
								<?php } ?>

							</div>
							<?php do_action( 'eael/login-register/after-lostpassword-footer', $this );
							?>
							<div class="eael-form-validation-container">
								<?php $this->print_lostpassword_validation_errors(); ?>
							</div>
							<?php
							$this->print_necessary_hidden_fields( 'lostpassword' );

							$this->print_lostpassword_validation_errors();

							do_action( 'eael/login-register/before-lostpassword-form-close', $this );
							?>
						</form>
						<?php do_action( 'eael/login-register/after-lostpassword-form', $this ); ?>
					</div>
					<?php
					if ( 'right' === $this->form_illustration_pos ) {
						$this->print_form_illustration('lostpassword');
					}
					?>
                </div>

            </section>
			<?php
		}
	}

	protected function print_resetpassword_form(){
		$default_hide_class = ( 'register' === $this->default_form || 'login' === $this->default_form || 'lostpassword' === $this->default_form || isset($_GET['eael-register']) || isset($_GET['eael-lostpassword']) ) && !isset($_GET['eael-resetpassword']) ? 'eael-lr-d-none' : '';
		$default_hide_class = $this->should_print_resetpassword_form_editor ? '' : $default_hide_class;
		$rp_page_url = ! empty( $this->page_id_for_popup ) ? get_permalink( $this->page_id_for_popup ) : get_permalink( $this->page_id ); 

		if ( $this->should_print_resetpassword_form_editor || ( ! empty( $_GET['eael-resetpassword'] ) ) ) {
			$show_resetpassword_on_form_submit = get_option('eael_show_reset_password_on_form_submit_' . $this->get_id());

			$validation_required = true;
			if ( $this->should_print_resetpassword_form_editor || $show_resetpassword_on_form_submit ) {
				$validation_required = false;
			}

			$rp_data['rp_login'] = $_GET['eael_login'] ?? '';
			$rp_data['rp_key']   = $_GET['eael_key'] ?? '';
			
			if( $validation_required && ! isset( $_POST['eael-resetpassword-submit'] ) ){
				$user = check_password_reset_key( $rp_data['rp_key'], $rp_data['rp_login'] );

				if ( empty( $rp_data['rp_key'] ) || ! $user || is_wp_error( $user ) ) {
					$rp_err_msg = ! empty( $this->ds['err_reset_password_key_expired'] ) ? esc_html__( wp_strip_all_tags( $this->ds['err_reset_password_key_expired'] ), 'essential-addons-for-elementor-lite' ) : __( 'Your password reset link appears to be invalid. Please request a new link.', 'essential-addons-for-elementor-lite' );
					update_option( 'eael_lostpassword_error_' . esc_attr( $this->get_id() ), $rp_err_msg, false );
		
					$resetpassword_redirect_url = esc_url_raw( $rp_page_url . '?eael-lostpassword=1&error=expiredkey' );
					
					if( ! empty( $this->resetpassword_in_popup_selector ) ){
						$resetpassword_redirect_url = esc_url_raw( $rp_page_url . '?eael-lostpassword=1&error=expiredkey&popup-selector=' . $this->resetpassword_in_popup_selector );
					}
					?>
					<script type="text/javascript">
						document.location.href = <?php echo json_encode( $resetpassword_redirect_url ); ?>;
					</script>
					<?php
					exit;
				}
			}
			
			delete_option('eael_show_reset_password_on_form_submit_' . $this->get_id());

			// lost password form fields related
			$label_type      = ! empty( $this->ds['resetpassword_label_types'] ) ? esc_html( $this->ds['resetpassword_label_types'] ) : 'default';
			$is_custom_label = ( 'custom' === $label_type );
			$display_label   = ( 'none' !== $label_type );

			$success_key = 'eael_resetpassword_success_' . esc_attr( $this->get_id() );
			$resetpassword_success = apply_filters( 'eael/login-register/resetpassword-success-message', get_option( $success_key ) );
			$hide_class_after_submission = ! empty( $resetpassword_success ) ? 'eael-d-none' : ''; 

			//Default label
			$password_label = __( 'New Password', 'essential-addons-for-elementor-lite' );
			$confirm_password_label = __( 'Confirm New Password', 'essential-addons-for-elementor-lite' );
			
			$password_placeholder = __( 'New Password', 'essential-addons-for-elementor-lite' );
			$confirm_password_placeholder = __( 'Confirm New Password', 'essential-addons-for-elementor-lite' );
			
			// custom label n placeholder
			if ( $is_custom_label ) {
				$password_label = isset( $this->ds['resetpassword_password_label'] ) ? __( $this->ds['resetpassword_password_label'], 'essential-addons-for-elementor-lite' ) : '';
				$confirm_password_label = isset( $this->ds['resetpassword_confirm_password_label'] ) ? __( $this->ds['resetpassword_confirm_password_label'], 'essential-addons-for-elementor-lite' ) : '';
				
				$password_placeholder = isset( $this->ds['resetpassword_password_placeholder'] ) ? __( $this->ds['resetpassword_password_placeholder'], 'essential-addons-for-elementor-lite' ) : '';
				$confirm_password_placeholder = isset( $this->ds['resetpassword_confirm_password_placeholder'] ) ? __( $this->ds['resetpassword_confirm_password_placeholder'], 'essential-addons-for-elementor-lite' ) : '';
			}

			$btn_text         = ! empty( $this->ds['resetpassword_button_text'] ) ? __( sanitize_text_field( $this->ds['resetpassword_button_text'] ), 'essential-addons-for-elementor-lite' ) : '';

			// btn alignment
			$btn_align = isset( $this->ds['resetpassword_btn_align'] ) ? esc_html( $this->ds['resetpassword_btn_align'] ) : '';
			// input icons
			$show_icon  = ( $this->pro_enabled && ! empty( $this->ds['show_resetpassword_icon'] ) && 'yes' === esc_html( $this->ds['show_resetpassword_icon'] ) );
			$icon_class = $show_icon ? 'lr-icon-showing' : '';

			$show_pv_icon     = ( ! empty( $this->ds['password_toggle_resetpassword'] ) && 'yes' === $this->ds['password_toggle_resetpassword'] );
			?>
            <section
                    id="eael-resetpassword-form-wrapper"
                    class="<?php echo esc_attr( $default_hide_class ); ?>"
                    >
                <div class="eael-resetpassword-form-wrapper eael-lr-form-wrapper style-2 <?php echo esc_attr( $icon_class ); ?>">
					<?php
					if ( 'left' === $this->form_illustration_pos ) {
						$this->print_form_illustration('resetpassword');
					}
					?>
					<div class="lr-form-wrapper">
						<?php $this->print_form_header( 'resetpassword' ); ?>
						<?php do_action( 'eael/login-register/before-resetpassword-form', $this ); ?>
						<form class="eael-resetpassword-form eael-lr-form"
							  id="eael-resetpassword-form"
							  method="post">
							<?php do_action( 'eael/login-register/after-resetpassword-form-open', $this ); ?>
							<div class="eael-lr-form-group <?php echo esc_attr( $hide_class_after_submission ); ?>">
								<?php if ( $display_label && $password_label ) {
									printf( '<label for="eael-pass1" class="eael-field-label">%s</label>', esc_html( wp_strip_all_tags( $password_label ) ) );
								} ?>
								<div class="eael-lr-password-wrapper eael-lr-resetpassword-wrapper eael-lr-resetpassword1-wrapper">
									<input type="password"
										name="eael-pass1"
										id="eael-pass1"
										class="eael-lr-form-control"
										placeholder="<?php esc_html_e( wp_strip_all_tags( $password_placeholder ), 'essential-addons-for-elementor-lite' ); ?>"
										required>

									<?php if ( $show_pv_icon ) { ?>
										<button type="button"
												id="wp-hide-pw1"
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
							
							<div class="eael-lr-form-group <?php echo esc_attr( $hide_class_after_submission ); ?>">
								<?php if ( $display_label && $confirm_password_label ) {
									printf( '<label for="eael-pass2" class="eael-field-label">%s</label>', esc_html( wp_strip_all_tags( $confirm_password_label ) ) );
								} ?>
								<div class="eael-lr-password-wrapper eael-lr-resetpassword-wrapper eael-lr-resetpassword2-wrapper">
									<input type="password"
										name="eael-pass2"
										id="eael-pass2"
										class="eael-lr-form-control"
										placeholder="<?php esc_html_e( wp_strip_all_tags( $confirm_password_placeholder ), 'essential-addons-for-elementor-lite' ); ?>"
										required>

									<?php if ( $show_pv_icon ) { ?>
										<button type="button"
												id="wp-hide-pw2"
												class="wp-hide-pw hide-if-no-js eael-d-none"
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

							<?php
							do_action( 'eael/login-register/before-resetpassword-footer', $this );
							?>

							<div class="eael-lr-footer">
								<input type="hidden" name="rp_key" value="<?php echo esc_attr( !empty( $rp_data['rp_key'] ) ? esc_html( $rp_data['rp_key'] ) : '' ); ?>" />
								<input type="hidden" name="rp_login" value="<?php echo esc_attr( !empty( $rp_data['rp_login'] ) ? esc_html( $rp_data['rp_login'] ) : '' ); ?>" />

								<input type="submit"
									   name="eael-resetpassword-submit"
									   id="eael-resetpassword-submit"
									   class="eael-lr-btn eael-lr-btn-block <?php echo esc_attr( $btn_align ); ?> <?php echo esc_attr( $hide_class_after_submission ); ?>"
									   value="<?php echo esc_attr( $btn_text ); ?>"/>
							</div>
							<?php do_action( 'eael/login-register/after-resetpassword-footer', $this );
							?>
							<div class="eael-form-validation-container">
								<?php $this->print_resetpassword_validation_errors(); ?>
							</div>
							<?php
							$this->print_necessary_hidden_fields( 'resetpassword' );

							$this->print_resetpassword_validation_errors();

							do_action( 'eael/login-register/before-resetpassword-form-close', $this );
							?>
						</form>
						<?php do_action( 'eael/login-register/after-resetpassword-form', $this ); ?>
					</div>
					<?php
					if ( 'right' === $this->form_illustration_pos ) {
						$this->print_form_illustration('resetpassword');
					}
					?>
                </div>

            </section>
			<?php
		}
	}

	protected function print_form_illustration($form_type = 'login') {
		$show_form_image_class = '';
		if( 'lostpassword' === $form_type || 'resetpassword' === $form_type ){
			$show_form_image_class = ! empty( $this->ds['show_image_on_lostpassword_form'] ) && 'yes' === $this->ds['show_image_on_lostpassword_form'] ? '' : 'eael-d-none';
		}

		if ( ! empty( $this->form_illustration_url ) ) { ?>
            <div class="lr-form-illustration lr-img-pos-<?php echo esc_attr( $this->form_illustration_pos ); ?>  <?php echo esc_attr( $show_form_image_class ); ?>"
                 style="background-image: url('<?php echo esc_attr( esc_url( $this->form_illustration_url ) ); ?>');"></div>
		<?php }
	}

	/**
	 * @param string $form_type the type of form. Available values: login and register
	 */
	protected function print_form_header( $form_type = 'login' ) {
		$title    = ! empty( $this->ds["{$form_type}_form_title"] ) ?  wp_strip_all_tags( $this->ds["{$form_type}_form_title"] )  : '';
		$subtitle = ! empty( $this->ds["{$form_type}_form_subtitle"] ) ? $this->ds["{$form_type}_form_subtitle"] : '';
		
		$show_form_logo_class = '';
		if( 'lostpassword' === $form_type || 'resetpassword' === $form_type ){
			$show_form_logo_class = ! empty( $this->ds['show_logo_on_lostpassword_form'] ) && 'yes' === $this->ds['show_logo_on_lostpassword_form'] ? '' : 'eael-d-none';
		}
		
		if ( empty( $this->form_logo ) && empty( $title ) && empty( $subtitle ) ) {
			return;
		}

		?>
        <div class="lr-form-header header-<?php echo esc_attr( $this->form_logo_pos ); ?>">
			<?php if ( ! empty( $this->form_logo ) ) { ?>
                <div class="form-logo <?php echo esc_attr( $show_form_logo_class ); ?>">
                    <img src="<?php echo esc_url( $this->form_logo ); ?>"
                         alt="<?php esc_attr_e( 'Form Logo Image', 'essential-addons-for-elementor-lite' ); ?>">
                </div>
			<?php } ?>

			<?php if ( ! empty( $title ) || ! empty( $subtitle ) ) { ?>
                <div class="form-dsc">
					<?php
					if ( ! empty( $title ) ) {
						echo "<h4>" . esc_html( $title ) . "</h4>"; // data escaped already.
					}

					if ( ! empty( $subtitle ) ) {
						echo "<p>" . wp_kses( $subtitle, HelperCLass::eael_allowed_tags() ) . "</p>"; // data escaped already.
					} ?>
                </div>
			<?php } ?>
        </div>
		<?php
	}

	protected function print_necessary_hidden_fields( $form_type = 'login' ) {
		if ( 'login' === $form_type ) {
			if ( ! empty( $this->ds['redirect_after_login'] ) && 'yes' === $this->ds['redirect_after_login'] ) {
				?>
                <input type="hidden"
                       name="redirect_to"
                       value="<?php echo esc_attr( $this->login_custom_redirect_url ); ?>">
			<?php }

			if ( ! empty( $this->ds['redirect_based_on_roles'] ) && 'yes' === $this->ds['redirect_based_on_roles'] ) {
				$user_roles = $this->eael_get_role_names();

				if( ! empty( $user_roles ) && is_array( $user_roles ) && count( $user_roles ) ){
					foreach( $user_roles as $user_role_key => $user_role_value ){
						$login_redirect_url = ! empty( $this->ds['redirect_url_' . esc_html( $user_role_key ) ]['url'] ) ? esc_url( $this->ds['redirect_url_' . esc_html( $user_role_key )]['url'] ) : '';
						?>
						<input type="hidden"
							name="redirect_to_<?php echo esc_html( $user_role_key ); ?>"
							value="<?php echo esc_attr( $login_redirect_url ); ?>">
						<?php
					}
				}
			}

			if ( ! empty( $this->ds['login_redirect_url_prev_page'] ) && 'yes' === $this->ds['login_redirect_url_prev_page'] ) {
				$login_redirect_url_prev_page = ! empty( $_SERVER['HTTP_REFERER'] ) ? esc_url_raw( $_SERVER['HTTP_REFERER'] ) : '';
				?>
				<input type="hidden"
					name="redirect_to_prev_page_login"
					value="<?php echo esc_attr( $login_redirect_url_prev_page ); ?>">
			<?php }
		}

		if ( 'register' === $form_type ) {
			if ( ! empty( $this->ds['register_redirect_url_prev_page'] ) && 'yes' === $this->ds['register_redirect_url_prev_page'] ) {
				$register_redirect_url_prev_page = ! empty( $_SERVER['HTTP_REFERER'] ) ? esc_url_raw( $_SERVER['HTTP_REFERER'] ) : '';
				?>
                <input type="hidden"
                       name="redirect_to_prev_page"
                       value="<?php echo esc_attr( $register_redirect_url_prev_page ); ?>">
			<?php }
		}

		if ( 'resetpassword' === $form_type ) {
			if ( ! empty( $this->ds['redirect_after_resetpassword'] ) && 'yes' === $this->ds['redirect_after_resetpassword'] ) {
				$resetpassword_redirect_url = ! empty( $this->ds['redirect_url_resetpassword']['url'] ) ? esc_url( $this->ds['redirect_url_resetpassword']['url'] ) : '';
				?>
                <input type="hidden"
                       name="resetpassword_redirect_to"
                       value="<?php echo esc_attr( $resetpassword_redirect_url ); ?>">
			<?php }
		}

		// add login security nonce
		wp_nonce_field( "eael-{$form_type}-action", "eael-{$form_type}-nonce" );
		?>
        <input type="hidden"
               name="page_id"
               value="<?php echo esc_attr( $this->page_id ); ?>">
		<input type="hidden"
               name="page_id_for_popup"
               value="<?php echo esc_attr( ! empty( $this->page_id_for_popup ) ? $this->page_id_for_popup : $this->page_id ); ?>">	   
		<input type="hidden"
               name="resetpassword_in_popup_selector"
               value="<?php echo esc_attr( ! empty( $this->resetpassword_in_popup_selector ) ? $this->resetpassword_in_popup_selector : '' ); ?>">
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
			$tc_link = sprintf( '<a href="%1$s" id="eael-lr-tnc-link" class="eael-lr-tnc-link" %2$s>%3$s</a>', esc_url( $tc_url ), $tc_atts, $link_text );
		}
		$lrtoggle = ! empty( $this->ds['eael_terms_conditions_field_type'] ) && 'toggle' === $this->ds['eael_terms_conditions_field_type'] ? 'lr-toggle' : '';
		?>
        <div class="eael_accept_tnc_wrap">
            <input type="hidden"
                   name="eael_tnc_active"
                   value="1">
            <input type="checkbox"
                   name="eael_accept_tnc"
                   class="eael_accept_tnc <?php echo esc_attr($lrtoggle); ?>"
                   value="1"
                   id="eael_accept_tnc">
            <label for="eael_accept_tnc"
                   class="eael-checkbox-label check-accept">
				<?php
				echo esc_html( $label );
				?>
            </label>
			<?php
			echo wp_kses( $tc_link, HelperCLass::eael_allowed_tags( [ 'a' => [ 'data-action' => [] ] ] ) );
			?>
        </div>

		<?php
		$tc = '<div class="eael-lr-tnc-wrap">';
		$tc .= $this->parse_text_editor( $tc_text );
		$tc .= '</div>';
		echo wp_kses( $tc, HelperCLass::eael_allowed_tags() );


	}

	protected function print_login_validation_errors() {
		$resetpassword_success_key = 'eael_resetpassword_success_' . $this->get_id();
		$resetpassword_success     = apply_filters( 'eael/login-register/resetpassword-success-message', json_decode( get_option( $resetpassword_success_key ) ) );

		if ( ! empty( $resetpassword_success ) && 'register' !== $this->ds['default_form_type'] ) {
			$this->print_resetpassword_success_message( $resetpassword_success );
		}
	}

	protected function print_lostpassword_validation_errors() {
		$error_key = 'eael_lostpassword_error_' . esc_attr( $this->get_id() );
		$error_key_show = $error_key . '_show';
		
		$success_key = 'eael_lostpassword_success_' . esc_attr( $this->get_id() );
		
		if ( intval( get_option( $error_key_show ) ) ) {
			$rp_err_msg = isset( $this->ds['err_reset_password_key_expired'] ) ? esc_html__( $this->ds['err_reset_password_key_expired'], 'essential-addons-for-elementor-lite' ) : esc_html__( 'Hey Your password reset link appears to be invalid. Please request a new link.', 'essential-addons-for-elementor-lite' );
			?>
            <p class="eael-form-msg invalid">
				<?php echo esc_html__( $rp_err_msg, 'essential-addons-for-elementor-lite' ); ?>
            </p>
			<?php
			delete_option( $error_key_show );
		}
		
		if ( $lostpassword_error = apply_filters( 'eael/login-register/lostpassword-error-message', get_option( $error_key ) ) ) {
			do_action( 'eael/login-register/before-showing-lostpassword-error', $lostpassword_error, $this );
			?>
            <p class="eael-form-msg invalid">
				<?php echo wp_kses( $lostpassword_error, HelperCLass::eael_allowed_tags() ); ?>
            </p>
			<?php
			do_action( 'eael/login-register/after-showing-login-error', $lostpassword_error, $this );

			delete_option( $error_key );
		}

		if ( $lostpassword_success = apply_filters( 'eael/login-register/lostpassword-success-message', get_option( $success_key ) ) ) {
			do_action( 'eael/login-register/before-showing-lostpassword-success', $lostpassword_success, $this );
			?>
            <p class="eael-form-msg valid">
				<?php echo esc_html( $lostpassword_success ); ?>
            </p>
			<?php
			do_action( 'eael/login-register/after-showing-login-success', $lostpassword_success, $this );

			delete_option( $success_key );
		}
	}

	protected function print_resetpassword_validation_errors() {
		$error_key = 'eael_resetpassword_error_' . $this->get_id();
		
		if ( $resetpassword_error = apply_filters( 'eael/login-register/resetpassword-error-message', json_decode( get_option( $error_key ), true ) ) ) {
			do_action( 'eael/login-register/before-showing-resetpassword-error', $resetpassword_error, $this );
			?>
            <div class="eael-form-msg invalid">
				<?php 
					if( is_array( $resetpassword_error ) ) {
						if( count( $resetpassword_error ) ){
							echo "<ol>";
							foreach( $resetpassword_error as $error ) {
								echo "<li>" . esc_html( $error ) . "</li>";
							}
							echo "</ol>";
						}
					} else {
						echo esc_html( $resetpassword_error );
					}
				?>
            </div>
			<?php
			do_action( 'eael/login-register/after-showing-login-error', $resetpassword_error, $this );

			delete_option( $error_key );
		} 

		$success_key = 'eael_resetpassword_success_' . esc_attr( $this->get_id() );
		$resetpassword_success = apply_filters( 'eael/login-register/resetpassword-success-message', json_decode( get_option( $success_key ) ) );
		if ( ! empty( $resetpassword_success ) ) {
			$this->print_resetpassword_success_message( $resetpassword_success );
		}
	}

	protected function print_bot_protection_node( $form_type = 'login' ) {
		if ( 'yes' === $this->get_settings_for_display( "enable_{$form_type}_recaptcha" ) || 'v3' === $this->ds["login_register_recaptcha_version"] ) {
			$id = "{$form_type}-recaptcha-node-" . esc_attr( $this->get_id() );
			echo "<input type='hidden' name='g-recaptcha-enabled' value='1'/><div id='" . esc_attr( $id ) . "' class='eael-recaptcha-wrapper'></div>";

			if( 'v3' === $this->ds["login_register_recaptcha_version"] && ( ! $this->ds[ 'enable_ajax' ] ) ){
				echo "<input type='hidden' name='action' value='eael_login_register_form'/>";
			}
		}

		if ( ! empty( $this->cloudflare_turnstile_sitekey ) && 'yes' === $this->get_settings_for_display( "enable_cloudflare_turnstile" ) && ( 'yes' === $this->get_settings_for_display( "enable_cloudflare_turnstile_on_{$form_type}" ) ) ) {
			$id = "eael-{$form_type}-cloudflare-turnstile-" . esc_attr( $this->get_id() );
			wp_enqueue_script( 'eael-cloudflare' );
			echo "<div class='cf-turnstile' data-theme='{$this->ds['cloudflare_turnstile_theme']}' data-sitekey='{$this->cloudflare_turnstile_sitekey}'></div>";
		}
	}

	protected function print_error_for_repeated_fields( $repeated_fields ) {
		if ( ! empty( $repeated_fields ) ) {
			$error_fields = '<strong>' . implode( "</strong>, <strong>", $repeated_fields ) . '</strong>';
			?>
            <p class='eael-register-form-error elementor-alert elementor-alert-warning'>
				<?php
				/* translators: %s: Error fields */
				$error_msg = sprintf( __( 'Error! you seem to have added %s field in the form more than once.', 'essential-addons-for-elementor-lite' ), $error_fields );
				echo wp_kses( $error_msg, HelperCLass::eael_allowed_tags() );
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
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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
		$resetpassword_success_key = 'eael_resetpassword_success_' . $this->get_id();
		$resetpassword_success = apply_filters( 'eael/login-register/resetpassword-success-message', get_option( $resetpassword_success_key ) );

		if ( empty( $errors ) && empty( $success ) && empty( $resetpassword_success ) ) {
			return;
		}
		if ( ! empty( $errors ) && is_array( $errors ) ) {
			$this->print_registration_errors_message( $errors );
		} else if( ! empty ( $success ) ) {
			$this->print_registration_success_message( $success );
		} else if( !empty( $resetpassword_success ) && 'register' === $this->ds['default_form_type'] ){
			$this->print_resetpassword_success_message( $resetpassword_success );
		} 
	}

	protected function print_registration_errors_message( $errors ) {
		?>
        <div class="eael-form-msg invalid">
			<?php
			if ( ! empty( $this->ds['err_unknown'] ) ) {
				// printf( '<p>%s</p>', esc_html( $this->ds['err_unknown'] ) );
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
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo apply_filters( 'eael/login-register/registration-success-msg', $message, $success );

			delete_option( 'eael_register_success_' . $this->get_id() );

			return true; // it will help in case we wanna know if error is printed.
		}

		return false;
	}
	
	protected function print_resetpassword_success_message( $resetpassword_success ) {
		$resetpassword_success_key = 'eael_resetpassword_success_' . $this->get_id();

		do_action( 'eael/login-register/before-showing-resetpassword-success', $resetpassword_success, $this );
		?>
		<div class="eael-form-msg valid">
			<?php 
				echo esc_html( $resetpassword_success );
			?>
		</div>
		<?php
		do_action( 'eael/login-register/after-showing-resetpassword-success', $resetpassword_success, $this );

		delete_option( $resetpassword_success_key );
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