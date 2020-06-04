<?php

namespace Essential_Addons_Elementor\Elements;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Plugin;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Class Login_Register
 * @package Essential_Addons_Elementor\Elements
 */
class Login_Register extends Widget_Base {
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
	 * @inheritDoc
	 */
	protected function _register_controls() {
		/*----Content Tab----*/
		$this->init_content_general_controls();
		$this->init_content_login_fields_controls();
		$this->init_content_login_options_controls();


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
			'description' => __( 'Choose the type of form you want to show by default. Note: you can show both form in a single page even if you select only login or registration from below. You will see option for that in other sections.', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::SELECT,
			'options'     => [
				'login'        => __( 'Login', EAEL_TEXTDOMAIN ),
				'registration' => __( 'Registration', EAEL_TEXTDOMAIN ),
			],
			'default'     => 'login',
		] );

		if ( ! $this->user_can_register ) {
			$this->add_control( 'registration_off_notice', [
				'type'            => Controls_Manager::RAW_HTML,
				/* translators: %1$s is settings page link open tag, %2$s is link closing tag */
				'raw'             => sprintf( __( 'Registration is disabled on your site. Please enable it to use registration form. You can enable it from Dashboard » Settings » General » %1$sMembership%2$s.', EAEL_TEXTDOMAIN ), '<a href="' . esc_attr( esc_url( admin_url( 'options-general.php' ) ) ) . '" target="_blank">', '</a>' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'condition'       => [
					'default_form_type' => 'login',
				],
			] );
		}

		$this->end_controls_section();
	}

	/**
	 * It adds controls related to Login Form Fields section to the Widget Content Tab
	 */
	protected function init_content_login_fields_controls() {
		$this->start_controls_section( 'section_content_login_fields', [
			'label' => __( 'Login Form Fields', EAEL_TEXTDOMAIN ),
            'condition' => [
                    'default_form_type' => 'login'
            ],
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
			'label' => __( 'Login Form Actions', EAEL_TEXTDOMAIN ),
			'condition' => [
				'default_form_type' => 'login'
			],
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

		$this->add_control( 'show_lost_password', [
			'label'     => __( 'Lost your password?', EAEL_TEXTDOMAIN ),
			'type'      => Controls_Manager::SWITCHER,
			'default'   => 'yes',
			'label_off' => __( 'Hide', EAEL_TEXTDOMAIN ),
			'label_on'  => __( 'Show', EAEL_TEXTDOMAIN ),
		] );


		$this->add_control( 'show_lost_password_text', [
			'label'     => __( 'Text', EAEL_TEXTDOMAIN ),
			'type'      => Controls_Manager::TEXT,
			'dynamic'   => [
				'active' => true,
			],
			'default'   => __( 'Lost your password?', EAEL_TEXTDOMAIN ),
			'condition' => [
				'show_lost_password' => 'yes',
			],
		] );

		$this->add_control( 'lost_password_select', [
			'label'     => __( 'Link to', EAEL_TEXTDOMAIN ),
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
				'lost_password_select' => 'custom',
				'show_lost_password'   => 'yes',
			],
		] );

		/*--show registration related control only if registration is enable on the site--*/
		if ( $this->user_can_register ) {
			$this->add_control( 'show_registration_button', [
				'label'     => __( 'Registration Button', EAEL_TEXTDOMAIN ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_off' => __( 'Hide', EAEL_TEXTDOMAIN ),
				'label_on'  => __( 'Show', EAEL_TEXTDOMAIN ),
			] );

			$this->add_control( 'registration_button_link', [
				'label'     => __( 'Registration Text', EAEL_TEXTDOMAIN ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => [
					'active' => true,
				],
				'default'   => __( 'Register', EAEL_TEXTDOMAIN ),
				'condition' => [
					'show_registration_button' => 'yes',
				],
			] );

			$this->add_control( 'registration_button_action', [
				'label'     => __( 'Registration Button Action', EAEL_TEXTDOMAIN ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'default' => __( 'Default WordPress Page', EAEL_TEXTDOMAIN ),
					'custom'  => __( 'Custom URL', EAEL_TEXTDOMAIN ),
				],
				'default'   => 'default',
				'condition' => [
					'show_registration_button' => 'yes',
				],
			] );
		}

		$this->add_control( 'footer_divider', [
			'label'      => __( 'Divider', EAEL_TEXTDOMAIN ),
			'type'       => Controls_Manager::TEXT,
			'default'    => '|',
			'selectors'  => [
				'{{WRAPPER}} .uael-login-form-footer a.uael-login-form-footer-link:not(:last-child) span:after' => 'content: "{{VALUE}}"; margin: 0 0.4em;',
			],
			'separator'  => 'after',
			'conditions' => [
				'relation' => 'and',
				'terms'    => [
					[
						'name'     => 'show_lost_password',
						'operator' => '==',
						'value'    => 'yes',
					],
					[
						'name'     => 'show_registration_button',
						'operator' => '==',
						'value'    => 'yes',
					],
				],
			],
		] );

		$this->add_control( 'show_logged_in_message', [
			'label'     => __( 'Logged in Message', EAEL_TEXTDOMAIN ),
			'type'      => Controls_Manager::SWITCHER,
			'default'   => 'yes',
			'label_off' => __( 'Hide', EAEL_TEXTDOMAIN ),
			'label_on'  => __( 'Show', EAEL_TEXTDOMAIN ),
		] );

		$this->add_responsive_control( 'footer_text_align', [
			'label'      => __( 'Alignment', EAEL_TEXTDOMAIN ),
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
				'{{WRAPPER}} .uael-login-form-footer' => 'justify-content: {{VALUE}};',
			],
			'conditions' => [
				'relation' => 'or',
				'terms'    => [
					[
						'name'     => 'show_lost_password',
						'operator' => '==',
						'value'    => 'yes',
					],
					[
						'name'     => 'show_registration_button',
						'operator' => '==',
						'value'    => 'yes',
					],
				],
			],
		] );

		$this->add_control( 'footer_text_color', [
			'label'      => __( 'Text Color', EAEL_TEXTDOMAIN ),
			'type'       => Controls_Manager::COLOR,
			'scheme'     => [
				'type'  => Color::get_type(),
				'value' => Color::COLOR_4,
			],
			'selectors'  => [
				'{{WRAPPER}} .uael-login-form-footer, {{WRAPPER}} .uael-login-form-footer a' => 'color: {{VALUE}};',
			],
			'conditions' => [
				'relation' => 'or',
				'terms'    => [
					[
						'name'     => 'show_lost_password',
						'operator' => '==',
						'value'    => 'yes',
					],
					[
						'name'     => 'show_registration_button',
						'operator' => '==',
						'value'    => 'yes',
					],
				],
			],
		] );


		$this->end_controls_section();
	}


	protected function render() {
		$settings                         = $this->get_settings_for_display();
		$this->should_print_login_form    = ( ! empty( $settings['default_form_type'] ) && 'login' === $settings['default_form_type'] );
		$this->should_print_register_form = ( $this->user_can_register && ( ! empty( $settings['default_form_type'] ) && 'registration' === $settings['default_form_type'] ) );
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
		if ( $this->should_print_login_form ) { ?>
            <div class="login ">
                <form name="loginform" id="loginform" action="/wp-login.php" method="post">
                    <p>
                        <label for="user_login">Username or Email Address</label>
                        <input type="text" name="log" id="user_login" class="input" value="" size="20" autocapitalize="off" autocomplete="off" placeholder="Username or Email Address"
                        >
                    </p>

                    <div class="user-pass-wrap">
                        <label for="user_pass">Password</label>
                        <div class="wp-pwd">
                            <input type="password" name="pwd" id="user_pass" class="input password-input" value="" size="20" autocomplete="off" placeholder="Password"
                            >
                            <button type="button" class="button button-secondary wp-hide-pw hide-if-no-js" aria-label="Show password">
                        <span class="dashicons dashicons-visibility"
                              aria-hidden="true"></span>
                                <!-- will be used to toggle visibility -->
                                <!-- <span class="dashicons dashicons-hidden"
									aria-hidden="true"></span> -->
                            </button>
                        </div>
                    </div>

                    <p class="forgetmenot">
                        <input name="rememberme" type="checkbox" id="rememberme" value="forever">
                        <label for="rememberme">Remember Me</label>
                    </p>
                    <p class="submit">
                        <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="Log In">
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
            <div class="register">
                <form name="registerform" id="registerform" action="/wp-login.php?action=register" method="post" novalidate="novalidate">
                    <p>
                        <label for="user_login">Username</label>
                        <input type="text" name="user_login" id="user_login" class="input" value="" size="20" autocapitalize="off">
                    </p>
                    <p>
                        <label for="user_email">Email</label>
                        <input type="email" name="user_email" id="user_email" class="input" value="" size="25">
                    </p>
                    <p id="reg_passmail">
                        Registration confirmation will be emailed to you. </p>
                    <br class="clear">
                    <input type="hidden" name="redirect_to" value="">
                    <p class="submit">
                        <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="Register">
                    </p>
                </form>
            </div>
			<?php
		}
	}


}