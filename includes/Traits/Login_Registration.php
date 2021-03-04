<?php

namespace Essential_Addons_Elementor\Traits;

use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Trait Login_Registration is responsible for login or registering user using custom login | register widget.
 * @package Essential_Addons_Elementor\Traits
 */
trait Login_Registration {
	/**
	 * @var bool
	 */
	public static $send_custom_email = false;
	public static $send_custom_email_admin = false;
	/**
	 * It will contain all email related options like email subject, content, email content type etc.
	 * @var array   $email_options {
	 *      Used to build wp_mail().
	 * @type string $template_type The type of the email template; custom | default.
	 * @type string $subject       The subject of the email.
	 * @type string $message       The body of the email.
	 * @type string $content_type  The type of the email body; plain | html
	 * }
	 */
	public static $email_options = [];

	public function login_or_register_user() {
		do_action( 'eael/login-register/before-processing-login-register', $_POST );
		// login or register form?
		if ( isset( $_POST['eael-login-submit'] ) ) {
			$this->log_user_in();
		} elseif ( isset( $_POST['eael-register-submit'] ) ) {
			$this->register_user();
		}
		do_action( 'eael/login-register/after-processing-login-register', $_POST );

	}

	/**
	 * It logs the user in when the login form is submitted normally without AJAX.
	 */
	public function log_user_in() {
		$ajax   = wp_doing_ajax();
		// before even thinking about login, check security and exit early if something is not right.
		$page_id = 0;
		if ( ! empty( $_POST['page_id'] ) ) {
			$page_id = intval( $_POST['page_id'], 10 );
		} else {
			$err_msg = __( 'Page ID is missing', 'essential-addons-for-elementor-lite' );
		}

		$widget_id = 0;
		if ( ! empty( $_POST['widget_id'] ) ) {
			$widget_id = sanitize_text_field( $_POST['widget_id'] );
		} else {
			$err_msg = __( 'Widget ID is missing', 'essential-addons-for-elementor-lite' );
		}

		if (!empty( $err_msg )){
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			update_option( 'eael_login_error_' . $widget_id, $err_msg, false );

            if (isset($_SERVER['HTTP_REFERER'])) {
                wp_safe_redirect($_SERVER['HTTP_REFERER']);
                exit();
            }
		}


		if ( empty( $_POST['eael-login-nonce'] ) ) {
			$err_msg = __( 'Insecure form submitted without security token', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			update_option( 'eael_login_error_' . $widget_id, $err_msg, false );

            if (isset($_SERVER['HTTP_REFERER'])) {
                wp_safe_redirect($_SERVER['HTTP_REFERER']);
                exit();
            }
		}

		if ( ! wp_verify_nonce( $_POST['eael-login-nonce'], 'eael-login-action' ) ) {
			$err_msg = __( 'Security token did not match', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			update_option( 'eael_login_error_' . $widget_id, $err_msg, false );

            if (isset($_SERVER['HTTP_REFERER'])) {
                wp_safe_redirect($_SERVER['HTTP_REFERER']);
                exit();
            }
		}
		$settings = $this->lr_get_widget_settings( $page_id, $widget_id);

		if ( is_user_logged_in() ) {
			$err_msg = isset( $settings['err_loggedin'] ) ? $settings['err_loggedin'] : __( 'You are already logged in', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			update_option( 'eael_login_error_' . $widget_id, $err_msg, false );

            if (isset($_SERVER['HTTP_REFERER'])) {
                wp_safe_redirect($_SERVER['HTTP_REFERER']);
                exit();
            }
		}

		do_action( 'eael/login-register/before-login' );

		$widget_id = ! empty( $_POST['widget_id'] ) ? sanitize_text_field( $_POST['widget_id'] ) : '';
		if ( isset( $_POST['g-recaptcha-enabled'] ) && ! $this->lr_validate_recaptcha() ) {
			$err_msg = isset( $settings['err_recaptcha'] ) ? $settings['err_recaptcha'] : __( 'You did not pass recaptcha challenge.', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			update_option( 'eael_login_error_' . $widget_id, $err_msg, false );

            if (isset($_SERVER['HTTP_REFERER'])) {
                wp_safe_redirect($_SERVER['HTTP_REFERER']);
                exit();
            } // vail early if recaptcha failed
		}

		$user_login = ! empty( $_POST['eael-user-login'] ) ? sanitize_text_field( $_POST['eael-user-login'] ) : '';
		if ( is_email( $user_login ) ) {
			$user_login = sanitize_email( $user_login );
		}

		$password   = ! empty( $_POST['eael-user-password'] ) ? sanitize_text_field( $_POST['eael-user-password'] ) : '';
		$rememberme = ! empty( $_POST['eael-rememberme'] ) ? sanitize_text_field( $_POST['eael-rememberme'] ) : '';

		$credentials = [
			'user_login'    => $user_login,
			'user_password' => $password,
			'remember'      => ( 'forever' === $rememberme ),
		];
		$user_data   = wp_signon( $credentials );

		if ( is_wp_error( $user_data ) ) {
			$err_msg = '';
			if ( isset( $user_data->errors['invalid_email'][0] ) ) {
				$err_msg = isset( $settings['err_email'] ) ? $settings['err_email'] : __( 'Invalid Email. Please check your email or try again with your username.', 'essential-addons-for-elementor-lite' );
			} elseif ( isset( $user_data->errors['invalid_username'][0] )) {
				$err_msg = isset( $settings['err_username'] ) ? $settings['err_username'] : __( 'Invalid Username. Please check your username or try again with your email.', 'essential-addons-for-elementor-lite' );

			} elseif ( isset( $user_data->errors['incorrect_password'][0] ) || isset( $user_data->errors['empty_password'][0] ) ) {
				$err_msg = isset( $settings['err_pass'] ) ? $settings['err_pass'] : __( 'Invalid Password', 'essential-addons-for-elementor-lite' );

			}

			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			update_option( 'eael_login_error_' . $widget_id, $err_msg, false );
		} else {
			wp_set_current_user( $user_data->ID, $user_login );
			do_action( 'wp_login', $user_data->user_login, $user_data );
			do_action( 'eael/login-register/after-login', $user_data->user_login, $user_data );
			if ( $ajax ) {

				$data = [
					'message' => isset( $settings['success_login'] ) ? $settings['success_login'] : __( 'You are logged in successfully', 'essential-addons-for-elementor-lite' ),
				];
				if ( ! empty( $_POST['redirect_to'] ) ) {
					$data['redirect_to'] = esc_url( $_POST['redirect_to'] );
				}
				wp_send_json_success( $data );
			}

			if ( ! empty( $_POST['redirect_to'] ) ) {
				wp_safe_redirect( esc_url( $_POST['redirect_to'] ) );
				exit();
			}
		}
        if (isset($_SERVER['HTTP_REFERER'])) {
            wp_safe_redirect($_SERVER['HTTP_REFERER']);
            exit();
        }
	}

	/**
	 * It register the user in when the registration form is submitted normally without AJAX.
	 */
	public function register_user() {
		$ajax = wp_doing_ajax();

		// validate & sanitize the request data
		if ( empty( $_POST['eael-register-nonce'] ) ) {
			if ( $ajax ) {
				wp_send_json_error( __( 'Insecure form submitted without security token', 'essential-addons-for-elementor-lite' ) );
			}

            if (isset($_SERVER['HTTP_REFERER'])) {
                wp_safe_redirect($_SERVER['HTTP_REFERER']);
                exit();
            }
		}
		if ( ! wp_verify_nonce( $_POST['eael-register-nonce'], 'eael-register-action' ) ) {
			if ( $ajax ) {
				wp_send_json_error( __( 'Security token did not match', 'essential-addons-for-elementor-lite' ) );
			}

            if (isset($_SERVER['HTTP_REFERER'])) {
                wp_safe_redirect($_SERVER['HTTP_REFERER']);
                exit();
            }
		}
		$page_id = $widget_id = 0;
        if ( ! empty( $_POST['page_id'] ) ) {
            $page_id = intval( $_POST['page_id'] );
        } else {
            $err_msg = __( 'Page ID is missing', 'essential-addons-for-elementor-lite' );
        }
        if ( ! empty( $_POST['widget_id'] ) ) {
            $widget_id = sanitize_text_field( $_POST['widget_id'] );
        } else {
            $err_msg = __( 'Widget ID is missing', 'essential-addons-for-elementor-lite' );
        }

        if (!empty( $err_msg )){
            if ( $ajax ) {
                wp_send_json_error( $err_msg );
            }
            update_option( 'eael_register_errors_' . $widget_id, $err_msg, false );

            if (isset($_SERVER['HTTP_REFERER'])) {
                wp_safe_redirect($_SERVER['HTTP_REFERER']);
                exit();
            }
            return false;
        }



		$settings = $this->lr_get_widget_settings( $page_id, $widget_id);


		if ( is_user_logged_in() ) {
			$err_msg = isset( $settings['err_loggedin'] ) ? $settings['err_loggedin'] : __( 'You are already logged in.', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}

            if (isset($_SERVER['HTTP_REFERER'])) {
                wp_safe_redirect($_SERVER['HTTP_REFERER']);
                exit();
            }
		}

		do_action( 'eael/login-register/before-register' );

		// prepare the data
		$errors               = [];
		$registration_allowed = get_option( 'users_can_register' );
		$protocol             = is_ssl() ? "https://" : "http://";
		$url                  = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		// vail early if reg is closed.
		if ( ! $registration_allowed ) {
			$errors['registration'] = __( 'Registration is closed on this site', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $errors['registration'] );
			}

            //update_option( 'eael_register_errors_' . $widget_id, $errors, false );// if we redirect to other page, we dont need to save value
            wp_safe_redirect( site_url( 'wp-login.php?registration=disabled' ) );
			exit();
		}
		// prepare vars and flag errors


		if ( isset( $_POST['eael_tnc_active'] ) && empty( $_POST['eael_accept_tnc'] ) ) {
			$errors['terms_conditions'] =  isset( $settings['err_tc'] ) ? $settings['err_tc'] : __( 'You did not accept the Terms and Conditions. Please accept it and try again.', 'essential-addons-for-elementor-lite' );
		}
		if ( isset( $_POST['g-recaptcha-enabled'] ) && ! $this->lr_validate_recaptcha() ) {
			$errors['recaptcha'] = isset( $settings['err_recaptcha'] ) ? $settings['err_recaptcha'] : __( 'You did not pass recaptcha challenge.', 'essential-addons-for-elementor-lite' );
		}

		if ( ! empty( $_POST['email'] ) && is_email( $_POST['email'] ) ) {
			$email = sanitize_email( $_POST['email'] );
			if ( email_exists( $email ) ) {
				$errors['email'] = isset( $settings['err_email_used'] ) ? $settings['err_email_used'] : __( 'The provided email is already registered with other account. Please login or reset password or use another email.', 'essential-addons-for-elementor-lite' );
			}
		} else {
			$errors['email'] = isset( $settings['err_email_missing'] ) ? $settings['err_email_missing'] : __( 'Email is missing or Invalid', 'essential-addons-for-elementor-lite' );
		}

		// if user provided user name, validate & sanitize it
		if ( isset( $_POST['user_name'] ) ) {
			$username = $_POST['user_name'];
			if ( ! validate_username( $username ) || mb_strlen( $username ) > 60 ) {
				$errors['user_name'] = isset( $settings['err_username'] ) ? $settings['err_username'] : __( 'Invalid username provided.', 'essential-addons-for-elementor-lite' );
			}elseif(username_exists( $username )){
				$errors['user_name'] = isset( $settings['err_username_used'] ) ? $settings['err_username_used'] : __( 'The username already registered.', 'essential-addons-for-elementor-lite' );

			}
		} else {
			// user has not provided username, so generate one from the provided email.
			if ( empty( $errors['email'] ) && isset( $email ) ) {
				$username = $this->generate_username_from_email( $email );
			}
		}

		// Dynamic Password Generation
		$is_pass_auto_generated = false; // emailing is must for autogen pass
		if ( ! empty( $_POST['password'] ) ) {
			$password = wp_unslash( sanitize_text_field( $_POST['password'] ) );
		} else {
			$password               = wp_generate_password();
			$is_pass_auto_generated = true;
		}
		if ( isset( $_POST['confirm_pass'] ) ) {
			$confirm_pass = wp_unslash( sanitize_text_field( $_POST['confirm_pass'] ) );
			if ( $confirm_pass !== $password ) {
				$errors['confirm_pass'] = isset( $settings['err_conf_pass'] ) ? $settings['err_conf_pass'] : __( 'The confirmed password did not match.', 'essential-addons-for-elementor-lite' );
			}
		}

		// if any error found, abort
		if ( ! empty( $errors ) ) {
			if ( $ajax ) {
				$err_msg = '<ol>';
				foreach ( $errors as $error ) {
					$err_msg .= "<li>{$error}</li>";
				}
				$err_msg .= '</ol>';
				wp_send_json_error( $err_msg );
			}
			update_option( 'eael_register_errors_' . $widget_id, $errors, false );
			wp_safe_redirect( esc_url( $url ) );
			exit();
		}

		/*------General Mail Related Stuff------*/
		self::$email_options['username']            = $username;
		self::$email_options['password']            = $password;
		self::$email_options['email']               = $email;
		self::$email_options['firstname']           = '';
		self::$email_options['lastname']            = '';
		self::$email_options['website']             = '';
		self::$email_options['password_reset_link'] = '';

		// handle registration...
		$user_data = [
			'user_login' => $username,
			'user_pass'  => $password,
			'user_email' => $email,
		];

		if ( ! empty( $_POST['first_name'] ) ) {
			$user_data['first_name'] = self::$email_options['firstname'] = sanitize_text_field( $_POST['first_name'] );
		}
		if ( ! empty( $_POST['last_name'] ) ) {
			$user_data['last_name'] = self::$email_options['lastname'] = sanitize_text_field( $_POST['last_name'] );
		}
		if ( ! empty( $_POST['website'] ) ) {
			$user_data['user_url'] = self::$email_options['website'] = esc_url_raw( $_POST['website'] );
		}
		$register_actions    = [];
		$custom_redirect_url = '';
		if ( !empty( $settings) ) {
			$register_actions    = ! empty( $settings['register_action'] ) ? (array) $settings['register_action'] : [];
			$custom_redirect_url = ! empty( $settings['register_redirect_url']['url'] ) ? $settings['register_redirect_url']['url'] : '/';
			if ( ! empty( $settings['register_user_role'] ) ) {
				$user_data['role'] = sanitize_text_field( $settings['register_user_role'] );
			}


			// set email related stuff
			/*------User Mail Related Stuff------*/
			if ( $is_pass_auto_generated || ( in_array( 'send_email', $register_actions ) && 'custom' === $settings['reg_email_template_type'] ) ) {
				self::$send_custom_email = true;
			}
			if ( isset( $settings['reg_email_subject'] ) ) {
				self::$email_options['subject'] = $settings['reg_email_subject'];
			}
			if ( isset( $settings['reg_email_message'] ) ) {
				self::$email_options['message'] = $settings['reg_email_message'];
			}
			if ( isset( $settings['reg_email_content_type'] ) ) {
				self::$email_options['headers'] = 'Content-Type: text/' . $settings['reg_email_content_type'] . '; charset=UTF-8' . "\r\n";
			}


			/*------Admin Mail Related Stuff------*/
			self::$send_custom_email_admin = ( ! empty( $settings['reg_admin_email_template_type'] ) && 'custom' === $settings['reg_admin_email_template_type'] );
			if ( isset( $settings['reg_admin_email_subject'] ) ) {
				self::$email_options['admin_subject'] = $settings['reg_admin_email_subject'];
			}
			if ( isset( $settings['reg_admin_email_message'] ) ) {
				self::$email_options['admin_message'] = $settings['reg_admin_email_message'];
			}
			if ( isset( $settings['reg_admin_email_content_type'] ) ) {
				self::$email_options['admin_headers'] = 'Content-Type: text/' . $settings['reg_admin_email_content_type'] . '; charset=UTF-8' . "\r\n";
			}
		}


		$user_data = apply_filters( 'eael/login-register/new-user-data', $user_data );

		do_action( 'eael/login-register/before-insert-user', $user_data );
		$user_id = wp_insert_user( $user_data );
		do_action( 'eael/login-register/after-insert-user', $user_id, $user_data );

		if ( is_wp_error( $user_id ) ) {
			// error happened during user creation
			$errors['user_create'] = isset( $settings['err_unknown'] ) ? $settings['err_unknown'] :  __( 'Sorry, something went wrong. User could not be registered.', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $errors['user_create'] );
			}
			update_option( 'eael_register_errors_' . $widget_id, $errors, false );
			wp_safe_redirect( esc_url( $url ) );
			exit();
		}


		// generate password reset link for autogenerated password
		if ( $is_pass_auto_generated ) {
			update_user_option( $user_id, 'default_password_nag', true, true ); // Set up the password change nag.
			$user = get_user_by( 'id', $user_id );
			$key  = get_password_reset_key( $user );
			if ( ! is_wp_error( $key ) ) {
				self::$email_options['password_reset_link'] = network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user->user_login ), 'login' ) . "\r\n\r\n";
			}
		}

		$admin_or_both = $is_pass_auto_generated || in_array( 'send_email', $register_actions ) ? 'both' : 'admin';


		/**
		 * Fires after a new user registration has been recorded.
		 *
		 * @param int $user_id ID of the newly registered user.
		 *
		 * @since 4.4.0
		 */
		remove_action( 'register_new_user', 'wp_send_new_user_notifications' );
		do_action( 'register_new_user', $user_id );

		wp_new_user_notification( $user_id, null, $admin_or_both );

		// success & handle after registration action as defined by user in the widget
		if ( ! $ajax && !in_array( 'redirect', $register_actions ) ) {
			update_option( 'eael_register_success_' . $widget_id, 1, false );
		}


		// Handle after registration action
		$data = [
			'message' => isset( $settings['success_register'] ) ? $settings['success_register'] : __( 'Your registration completed successfully.', 'essential-addons-for-elementor-lite' ),
		];
		// should user be auto logged in?
		if ( in_array( 'auto_login', $register_actions ) && ! is_user_logged_in() ) {
			wp_signon( [
				'user_login'    => $username,
				'user_password' => $password,
				'remember'      => true,
			] );
            $this->delete_registration_options($widget_id);

			if ( $ajax ) {
				if ( in_array( 'redirect', $register_actions ) ) {
					$data['redirect_to'] = $custom_redirect_url;
				}
				wp_send_json_success( $data );
			}

			// if custom redirect not available then refresh the current page to show admin bar
			if ( ! in_array( 'redirect', $register_actions ) ) {
				wp_safe_redirect( esc_url( $url ) );
				exit();
			}
		}

		// custom redirect?
		if ( $ajax ) {
			if ( in_array( 'redirect', $register_actions ) ) {
				$data['redirect_to'] = $custom_redirect_url;
			}
			wp_send_json_success( $data );
		}

		if ( in_array( 'redirect', $register_actions ) ) {
			wp_safe_redirect( $custom_redirect_url );
			exit();
		}

        if (isset($_SERVER['HTTP_REFERER'])) {
            wp_safe_redirect($_SERVER['HTTP_REFERER']);
            exit();
        }

	}

	public function generate_username_from_email( $email, $suffix = '' ) {

		$username_parts = [];
		if ( empty( $username_parts ) ) {
			$email_parts    = explode( '@', $email );
			$email_username = $email_parts[0];

			// Exclude common prefixes.
			if ( in_array( $email_username, [
				'sales',
				'hello',
				'mail',
				'contact',
				'info',
			], true ) ) {
				// Get the domain part.
				$email_username = $email_parts[1];
			}

			$username_parts[] = sanitize_user( $email_username, true );
		}
		$username = strtolower( implode( '', $username_parts ) );

		if ( $suffix ) {
			$username .= $suffix;
		}

		$username = sanitize_user( $username, true );
		if ( username_exists( $username ) ) {
			// Generate something unique to append to the username in case of a conflict with another user.
			$suffix = '-' . zeroise( wp_rand( 0, 9999 ), 4 );

			return $this->generate_username_from_email( $email, $suffix );
		}

		return $username;
	}

	/**
	 * Get Widget data.
	 *
	 * @param array  $elements Element array.
	 * @param string $form_id  Element ID.
	 *
	 * @return bool|array
	 */
	public function find_element_recursive( $elements, $form_id ) {

		foreach ( $elements as $element ) {
			if ( $form_id === $element['id'] ) {
				return $element;
			}

			if ( ! empty( $element['elements'] ) ) {
				$element = $this->find_element_recursive( $element['elements'], $form_id );

				if ( $element ) {
					return $element;
				}
			}
		}

		return false;
	}

	public function get_user_roles() {
		$user_roles[''] = __( 'Default', 'essential-addons-for-elementor-lite' );
		if ( function_exists( 'get_editable_roles' ) ) {
			$wp_roles = get_editable_roles();
			$roles    = $wp_roles ? $wp_roles : [];
			if ( ! empty( $roles ) && is_array( $roles ) ) {
				foreach ( $wp_roles as $role_key => $role ) {
					$user_roles[ $role_key ] = $role['name'];
				}
			}
		}

		return apply_filters( 'eael/login-register/new-user-roles', $user_roles );
	}

	/**
	 * It store data temporarily,5 minutes by default
	 *
	 * @param     $name
	 * @param     $data
	 * @param int $time time in seconds. Default is 300s = 5 minutes
	 *
	 * @return bool it returns true if the data saved, otherwise, false returned.
	 */
	public function set_transient( $name, $data, $time = 300 ) {
		$time = empty( $time ) ? (int) $time : ( 5 * MINUTE_IN_SECONDS );

		return set_transient( $name, $data, $time );
	}

	/**
	 * Filters the contents of the new user notification email sent to the new user.
	 *
	 * @param array    $email_data It contains, to, subject, message, headers etc.
	 * @param \WP_User $user       User object for new user.
	 * @param string   $blogname   The site title.
	 *
	 * @return array
	 * @since 4.9.0
	 */
	public function new_user_notification_email( $email_data, $user, $blogname ) {
		if ( ! self::$send_custom_email ) {
			return $email_data;
		}

		if ( ! empty( self::$email_options['subject'] ) ) {
			$email_data['subject'] = self::$email_options['subject'];
		}

		if ( ! empty( self::$email_options['message'] ) ) {
			$email_data['message'] = $this->replace_placeholders( self::$email_options['message'], 'user' );
		}

		if ( ! empty( self::$email_options['headers'] ) ) {
			$email_data['headers'] = self::$email_options['headers'];
		}

		return apply_filters( 'eael/login-register/new-user-email-data', $email_data, $user, $blogname );

	}

	/**
	 * Filters the contents of the new user notification email sent to the site admin.
	 *
	 * @param array    $email_data It contains, to, subject, message, headers etc.
	 * @param \WP_User $user       User object for new user.
	 * @param string   $blogname   The site title.
	 *
	 * @return array
	 * @since 4.9.0
	 */
	public function new_user_notification_email_admin( $email_data, $user, $blogname ) {

		if ( ! self::$send_custom_email_admin ) {
			return $email_data;
		}

		if ( ! empty( self::$email_options['admin_subject'] ) ) {
			$email_data['subject'] = self::$email_options['admin_subject'];
		}

		if ( ! empty( self::$email_options['admin_message'] ) ) {
			$email_data['message'] = $this->replace_placeholders( self::$email_options['admin_message'], 'admin' );
		}

		if ( ! empty( self::$email_options['admin_headers'] ) ) {
			$email_data['headers'] = self::$email_options['admin_headers'];
		}

		return apply_filters( 'eael/login-register/new-user-admin-email-data', $email_data, $user, $blogname );
	}

	/**
	 * It replaces placeholders with dynamic value and returns it.
	 *
	 * @param        $message
	 * @param string $receiver
	 *
	 * @return null|string|string[]
	 */
	public function replace_placeholders( $message, $receiver = 'user' ) {
		$placeholders = [
			'/\[password\]/',
			'/\[password_reset_link\]/',
			'/\[username\]/',
			'/\[email\]/',
			'/\[firstname\]/',
			'/\[lastname\]/',
			'/\[website\]/',
			'/\[loginurl\]/',
			'/\[sitetitle\]/',
		];
		$replacement  = [
			self::$email_options['password'],
			self::$email_options['password_reset_link'],
			self::$email_options['username'],
			self::$email_options['email'],
			self::$email_options['firstname'],
			self::$email_options['lastname'],
			self::$email_options['website'],
			wp_login_url(),
			get_option( 'blogname' ),
		];

		if ( 'user' !== $receiver ) {
			// remove password from admin mail, because admin should not see user's plain password
			unset( $placeholders[0] );
			unset( $placeholders[1] );
			unset( $replacement[0] );
			unset( $replacement[1] );
		}

		return preg_replace( $placeholders, $replacement, $message );
	}

	public function lr_validate_recaptcha() {
		if ( ! isset( $_REQUEST['g-recaptcha-response'] ) ) {
			return false;
		}
		$endpoint = 'https://www.google.com/recaptcha/api/siteverify';
		$data     = [
			'secret'   => get_option( 'eael_recaptcha_secret' ),
			'response' => $_REQUEST['g-recaptcha-response'],
			'ip'       => $_SERVER['REMOTE_ADDR'],
		];

		$res = json_decode( wp_remote_retrieve_body( wp_remote_post( $endpoint, [ 'body' => $data ] ) ), 1 );
		if ( isset( $res['success'] ) ) {
			return $res['success'];
		}

		return false;
	}

	public function lr_get_widget_settings( $page_id, $widget_id ) {
		$document = Plugin::$instance->documents->get( $page_id );
		$settings = [];
		if ( $document ) {
			$elements    = Plugin::instance()->documents->get( $page_id )->get_elements_data();
			$widget_data = $this->find_element_recursive( $elements, $widget_id );

			if(!empty($widget_data)) {
                $widget      = Plugin::instance()->elements_manager->create_element_instance( $widget_data );
                if ( $widget ) {
                    $settings    = $widget->get_settings_for_display();
                }
            }

		}
		return $settings;
	}

    public function delete_registration_options($widget_id)
    {
        delete_option('eael_register_success_' . $widget_id);
        delete_option('eael_register_errors_' . $widget_id);
	}
}
