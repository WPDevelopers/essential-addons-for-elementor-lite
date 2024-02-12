<?php

namespace Essential_Addons_Elementor\Traits;

use Elementor\Plugin;
use Essential_Addons_Elementor\Classes\Helper;

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
	public static $send_custom_email_lostpassword = false;
	public static $eael_custom_profile_field_prefix = 'eael_custom_profile_field_';

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
	public static $email_options_lostpassword = [];

	public static $recaptcha_v3_default_action = 'eael_login_register_form';

	public static function get_recaptcha_threshold() {
		return apply_filters( 'eael_recaptcha_threshold', 0.5 );
	}

	public function login_or_register_user() {
		do_action( 'eael/login-register/before-processing-login-register', $_POST );
		// login or register form?
		if ( isset( $_POST['eael-login-submit'] ) ) {
			$this->log_user_in();
		} else if ( isset( $_POST['eael-register-submit'] ) ) {
			$this->register_user();
		} else if ( isset( $_POST['eael-lostpassword-submit'] ) ) {
			$this->send_password_reset();
		} else if ( isset( $_POST['eael-resetpassword-submit'] ) ) {
			$this->reset_password();
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
			setcookie( 'eael_login_error_' . $widget_id, $err_msg );

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
			setcookie( 'eael_login_error_' . $widget_id, $err_msg );

            if (isset($_SERVER['HTTP_REFERER'])) {
                wp_safe_redirect($_SERVER['HTTP_REFERER']);
                exit();
            }
		}
		if ( ! wp_verify_nonce( $_POST['eael-login-nonce'], 'essential-addons-elementor' ) ) {
			$err_msg = __( 'Security token did not match', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			setcookie( 'eael_login_error_' . $widget_id, $err_msg );

            if (isset($_SERVER['HTTP_REFERER'])) {
                wp_safe_redirect($_SERVER['HTTP_REFERER']);
                exit();
            }
		}
		$settings = $this->lr_get_widget_settings( $page_id, $widget_id);

		if ( is_user_logged_in() ) {
			$err_msg = isset( $settings['err_loggedin'] ) ? Helper::eael_wp_kses( $settings['err_loggedin'] ) : __( 'You are already logged in', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			setcookie( 'eael_login_error_' . $widget_id, $err_msg );

            if (isset($_SERVER['HTTP_REFERER'])) {
                wp_safe_redirect($_SERVER['HTTP_REFERER']);
                exit();
            }
		}

		do_action( 'eael/login-register/before-login', $_POST, $settings, $this );

		$widget_id = ! empty( $_POST['widget_id'] ) ? sanitize_text_field( $_POST['widget_id'] ) : '';

		//v2 or v3
		$is_version_2 = isset( $settings['enable_register_recaptcha'] ) && 'yes' === $settings['enable_register_recaptcha'];
		$is_version_3 = isset( $settings['login_register_recaptcha_version'] ) && 'v3' === $settings['login_register_recaptcha_version'];
		if ( $is_version_2 || $is_version_3 ) {
			$ld_recaptcha_version = $is_version_3 ? 'v3' : 'v2';
			
			if( ! $this->lr_validate_recaptcha($ld_recaptcha_version) ) {
				$err_msg = isset( $settings['err_recaptcha'] ) ? Helper::eael_wp_kses( $settings['err_recaptcha'] ) : __( 'You did not pass recaptcha challenge.', 'essential-addons-for-elementor-lite' );
				if ( $ajax ) {
					wp_send_json_error( $err_msg );
				}
				setcookie( 'eael_login_error_' . $widget_id, $err_msg );

				if (isset($_SERVER['HTTP_REFERER'])) {
					wp_safe_redirect($_SERVER['HTTP_REFERER']);
					exit();
				} // fail early if recaptcha failed
			}
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
				$err_msg = isset( $settings['err_email'] ) ? Helper::eael_wp_kses( $settings['err_email'] ) : __( 'Invalid Email. Please check your email or try again with your username.', 'essential-addons-for-elementor-lite' );
			} elseif ( isset( $user_data->errors['invalid_username'][0] )) {
				$err_msg = isset( $settings['err_username'] ) ? Helper::eael_wp_kses( $settings['err_username'] ) : __( 'Invalid Username. Please check your username or try again with your email.', 'essential-addons-for-elementor-lite' );

			} elseif ( isset( $user_data->errors['incorrect_password'][0] ) || isset( $user_data->errors['empty_password'][0] ) ) {
				$err_msg = isset( $settings['err_pass'] ) ? Helper::eael_wp_kses( $settings['err_pass'] ) : __( 'Invalid Password', 'essential-addons-for-elementor-lite' );
			} else {
				if( ! empty( $user_data->errors ) ){
					foreach( $user_data->errors as $error ) {
						$err_msg = is_array( $error ) && ! empty( $error[0] ) ? Helper::eael_wp_kses( $error[0] ) : __('Something went wrong!', 'essential-addons-for-elementor-lite');
						break;
					}
				}
			}

			$err_msg = apply_filters('eael/login-register/login-validatiob-error-message', $err_msg, $user_data);
			$err_msg = is_array( $err_msg ) && ! empty( $err_msg[0] ) ? Helper::eael_wp_kses( $err_msg[0] ) : Helper::eael_wp_kses( $err_msg );

			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			setcookie( 'eael_login_error_' . $widget_id, $err_msg );
		} else {
			wp_set_current_user( $user_data->ID, $user_login );
			$current_user_role = ! empty( $user_data->roles[0] ) ? $user_data->roles[0] : '';

			$redirect_to = '';
			if ( ! empty( $_POST['redirect_to'] ) ) {
				$redirect_to = esc_url_raw( $_POST['redirect_to'] );
				if( ! empty( $current_user_role ) ){
					$redirect_to = ! empty( $_POST['redirect_to_' . esc_html( $current_user_role )] ) ? esc_url_raw( $_POST['redirect_to_' . esc_html( $current_user_role )] ) : $redirect_to;
				}
			}

			do_action( 'wp_login', $user_data->user_login, $user_data );
			do_action( 'eael/login-register/after-login', $user_data->user_login, $user_data );

			$custom_redirect_url 	= ! empty( $_POST['redirect_to'] ) ? sanitize_url( $_POST['redirect_to'] ) : '';
			$previous_page_url 		= ! empty( $_POST['redirect_to_prev_page_login'] ) ? sanitize_url( $_POST['redirect_to_prev_page_login'] ) : '';
			$custom_redirect_url 	= ! empty( $settings['login_redirect_url_prev_page'] ) && $settings['login_redirect_url_prev_page'] === 'yes' ? $previous_page_url : $custom_redirect_url;

			if ( $ajax ) {
				$data = [
					'message' => isset( $settings['success_login'] ) ? Helper::eael_wp_kses( $settings['success_login'] ) : __( 'You are logged in successfully', 'essential-addons-for-elementor-lite' ),
				];

				if ( ! empty( $custom_redirect_url ) ) {
					$data['redirect_to'] = esc_url_raw( $custom_redirect_url );
				}
				wp_send_json_success( $data );
			}

			if ( ! empty( $custom_redirect_url ) ) {
				wp_safe_redirect( esc_url_raw( $custom_redirect_url ) );
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
		if ( ! wp_verify_nonce( $_POST['eael-register-nonce'], 'essential-addons-elementor' ) ) {
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
			if ( in_array( get_post_status( $page_id ), [ 'future', 'draft', 'pending' ] ) ) {
				$err_msg = __( 'You have to publish the page first.', 'essential-addons-for-elementor-lite' );
			}
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
			$err_msg = isset( $settings['err_loggedin'] ) ? Helper::eael_wp_kses( $settings['err_loggedin'] ) : __( 'You are already logged in.', 'essential-addons-for-elementor-lite' );
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
            wp_safe_redirect(
				add_query_arg(
					array(
						'registration'      => 'disabled',
					),
					esc_url_raw( $this->eael_wp_login_url() )
				)
			);
			exit();
		}
		// prepare vars and flag errors
		$settings_register_fields = isset($settings['register_fields']) ? $settings['register_fields'] : array();

		$eael_custom_profile_fields_text = $this->get_eael_custom_profile_fields('text');
		$eael_custom_profile_fields_image = $this->get_eael_custom_profile_fields('image');
		$eael_custom_profile_fields = array_merge( $eael_custom_profile_fields_text, $eael_custom_profile_fields_image );

		$eael_custom_profile_fields_image_keys = array_keys( $eael_custom_profile_fields_image );

		if( count($settings_register_fields) ){
			foreach($settings_register_fields as $register_field){
				if( isset( $register_field['field_type'] ) && 'eael_phone_number' === $register_field['field_type']	){
					//Phone number field
					if( !empty( $register_field['required'] ) && 'yes' === $register_field['required'] && empty( $_POST['eael_phone_number'] ) ) {
						$errors['eael_phone_number'] = isset( $settings['err_phone_number_missing'] ) ? $settings['err_phone_number_missing'] : __( 'Phone number is required', 'essential-addons-for-elementor-lite' );
					}
				}

				if( isset( $register_field['field_type'] ) && in_array( $register_field['field_type'], $eael_custom_profile_fields_image_keys )	){

					if ( ! empty( $_FILES[ $register_field['field_type'] ] ) && 4 !== $_FILES[ $register_field['field_type'] ]["error"] ) {
						$custom_field_file_name 		= sanitize_text_field( $_FILES[ $register_field['field_type'] ]["name"] );
						$custom_field_file_extension 	= end( ( explode( ".", $custom_field_file_name ) ) ); # extra () to prevent notice
						$custom_field_file_size 		= floatval( $_FILES[ $register_field['field_type'] ]["size"] );

						$unsupported_extensions = ['svg', 'php', 'js', 'aiff', 'psd', 'exr', 'wma', 'sql', 'm2v', 'swf', 'py', 'java', 'json', 'html', 'yaml', 'css', 'rb', 'cpp', 'c', 'cs', 'swift', 'kt', 'go', 'ts'];

						if( ! empty ( $register_field['field_type_custom_image_extensions'] ) ||  in_array($custom_field_file_extension, $unsupported_extensions) ){
							$field_type_custom_image_extensions_trimmed = trim( sanitize_text_field( $register_field['field_type_custom_image_extensions'] ), ' ,\n\r\0\x0B' );
							$field_type_custom_image_extensions_array 	= array_unique( explode( ',', $field_type_custom_image_extensions_trimmed ) );

							foreach( $field_type_custom_image_extensions_array as $item_key => $field_type_custom_image_extension ){
								$field_type_custom_image_extensions_array[$item_key] = strtolower( trim( sanitize_text_field( $field_type_custom_image_extension ), ' ,\n\r\0\x0B' ) );
							}

							if( ! in_array( '.' . strtolower( $custom_field_file_extension ), $field_type_custom_image_extensions_array ) ) {
								$errors[ $register_field['field_type'] ] = isset( $settings['field_type_custom_image_extensions_error'] ) ? $settings['field_type_custom_image_extensions_error'] : __( 'Sorry, you are not allowed to upload this file type.', 'essential-addons-for-elementor-lite' );
							}
						}
						$register_field['field_type_custom_image_filesize'] 		= empty ( $register_field['field_type_custom_image_filesize'] ) 		? 5 : $register_field['field_type_custom_image_filesize'];
						$register_field['field_type_custom_image_filename_length'] 	= empty ( $register_field['field_type_custom_image_filename_length'] ) 	? 128 : $register_field['field_type_custom_image_filename_length'];

						if( ! empty ( $register_field['field_type_custom_image_filesize'] ) ){
							$field_type_custom_image_filesize 		= floatval( $register_field['field_type_custom_image_filesize'] );
							$field_type_custom_image_filesize 		= $field_type_custom_image_filesize > 512 ? 512 : $field_type_custom_image_filesize;
							$field_type_custom_image_filesize_kb 	= $field_type_custom_image_filesize * 1000000;

							if( $custom_field_file_size > $field_type_custom_image_filesize_kb ) {
                                $errors[ $register_field['field_type'] ] = isset( $settings['field_type_custom_image_filesize_error'] ) ? $settings['field_type_custom_image_filesize_error'] : __( 'File size exceeded. Maximum size is ' . floatval( $field_type_custom_image_filesize ) . 'MB' , 'essential-addons-for-elementor-lite' );
                            }
						}

						if( ! empty ( $register_field['field_type_custom_image_filename_length'] ) ){
							$field_type_custom_image_filename_length	= intval( $register_field['field_type_custom_image_filename_length'] );

							if( strlen( $custom_field_file_name ) > $field_type_custom_image_filename_length ) {
                                $errors[ $register_field['field_type'] ] = isset( $settings['field_type_custom_image_filename_length_error'] ) ? $settings['field_type_custom_image_filename_length_error'] : __( 'Filename length exceeded. Maximum length is ' . intval( $field_type_custom_image_filename_length ), 'essential-addons-for-elementor-lite' );
                            }
						}
					}
				}

				//Validate HTML tags on input fields; Throw error if found (Although we are sanitizing before saving)
				if( isset( $register_field['field_type'] ) && !empty( $_POST[$register_field['field_type']] ) ){
					if( preg_match('/<[^<]+>/', $_POST[ $register_field['field_type'] ] ) ){
						$errors[ sanitize_text_field( $register_field['field_type'] ) ] = __( sprintf('%s can not contain HTML tags', sanitize_text_field( $register_field['field_label'] ) ), 'essential-addons-for-elementor-lite' );
					}
				}
			}
		}

		if ( isset( $_POST['eael_tnc_active'] ) && empty( $_POST['eael_accept_tnc'] ) ) {
			$errors['terms_conditions'] =  isset( $settings['err_tc'] ) ? Helper::eael_wp_kses( $settings['err_tc'] ) : __( 'You did not accept the Terms and Conditions. Please accept it and try again.', 'essential-addons-for-elementor-lite' );
		}
		//v2 or v3
		$is_version_2 = isset( $settings['enable_register_recaptcha'] ) && 'yes' === $settings['enable_register_recaptcha'];
		$is_version_3 = isset( $settings['login_register_recaptcha_version'] ) && 'v3' === $settings['login_register_recaptcha_version'];
		if ( $is_version_2 || $is_version_3 ) {
			$ld_recaptcha_version = $is_version_3 ? 'v3' : 'v2';
			
			if( ! $this->lr_validate_recaptcha($ld_recaptcha_version) ) {
				$errors['recaptcha'] = isset( $settings['err_recaptcha'] ) ? Helper::eael_wp_kses( $settings['err_recaptcha'] ) : __( 'You did not pass recaptcha challenge.', 'essential-addons-for-elementor-lite' );
			}
		}

		if ( !empty( $_POST['eael_phone_number'] ) && ! $this->eael_is_phone( sanitize_text_field( $_POST['eael_phone_number'] )) ) {
			$errors['eael_phone_number'] =  isset( $settings['err_phone_number_invalid'] ) ? $settings['err_phone_number_invalid'] : __( 'Invalid phone number provided', 'essential-addons-for-elementor-lite' );
		}

		if ( ! empty( $_POST['email'] ) && is_email( $_POST['email'] ) ) {
			$email = sanitize_email( $_POST['email'] );
			if ( email_exists( $email ) ) {
				$errors['email'] = isset( $settings['err_email_used'] ) ? Helper::eael_wp_kses( $settings['err_email_used'] ) : __( 'The provided email is already registered with other account. Please login or reset password or use another email.', 'essential-addons-for-elementor-lite' );
			}
		} else {
			$errors['email'] = isset( $settings['err_email_missing'] ) ? Helper::eael_wp_kses( $settings['err_email_missing'] ) : __( 'Email is missing or Invalid', 'essential-addons-for-elementor-lite' );
		}

		// if user provided user name, validate & sanitize it
		if ( isset( $_POST['user_name'] ) ) {
			$username = sanitize_user( $_POST['user_name'] );
			if ( ! validate_username( $username ) || mb_strlen( $username ) > 60 ) {
				$errors['user_name'] = isset( $settings['err_username'] ) ? Helper::eael_wp_kses( $settings['err_username'] ) : __( 'Invalid username provided.', 'essential-addons-for-elementor-lite' );
			}elseif(username_exists( $username )){
				$errors['user_name'] = isset( $settings['err_username_used'] ) ? Helper::eael_wp_kses( $settings['err_username_used'] ) : __( 'The username already registered.', 'essential-addons-for-elementor-lite' );

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
			$password = sanitize_text_field( $_POST['password'] );
		} else {
			$password               = wp_generate_password();
			$is_pass_auto_generated = true;
		}

		if ( isset( $_POST['confirm_pass'] ) ) {
			$confirm_pass = sanitize_text_field( $_POST['confirm_pass'] );
			if ( $confirm_pass !== $password ) {
				$errors['confirm_pass'] = isset( $settings['err_conf_pass'] ) ? Helper::eael_wp_kses( $settings['err_conf_pass'] ) : __( 'The confirmed password did not match.', 'essential-addons-for-elementor-lite' );
			}
		}

		if(!$is_pass_auto_generated){
			$errors = apply_filters( 'eael/login-register/register-user-password-validation', $errors, $settings, $password );
		}
		
		// if any error found, abort
		if ( ! empty( $errors ) ) {
			if ( $ajax ) {
				$err_msg = '<ol>';
				if ( count( $errors ) === 1 ) {
					$err_msg = '<ol class="'. esc_attr('eael-list-style-none-wrap').'">';
				}
				
				foreach ( $errors as $error ) {
					$err_msg .= "<li>{$error}</li>";
				}
				$err_msg .= '</ol>';
				wp_send_json_error( $err_msg );
			}
			update_option( 'eael_register_errors_' . $widget_id, $errors, false );
			wp_safe_redirect( esc_url_raw( $url ) );
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
		self::$email_options['eael_phone_number'] 	= '';

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

		if ( ! empty( $_POST['eael_phone_number'] ) ) {
			$user_data['eael_phone_number'] = self::$email_options['eael_phone_number'] = sanitize_text_field( $_POST['eael_phone_number'] );
		}

		if( count( $eael_custom_profile_fields_text ) ){
			foreach( $eael_custom_profile_fields_text as $eael_custom_profile_field_text_key => $eael_custom_profile_field_text_value ){
				self::$email_options[$eael_custom_profile_field_text_key] = '';

				if ( ! empty( $_POST[ $eael_custom_profile_field_text_key ] ) ) {
					$user_data[$eael_custom_profile_field_text_key] = self::$email_options[$eael_custom_profile_field_text_key] = sanitize_text_field( $_POST[ $eael_custom_profile_field_text_key ] );
				}
			}
		}

		$register_actions    = [];
		$custom_redirect_url = '';
		if ( !empty( $settings) ) {
			$register_actions    	= ! empty( $settings['register_action'] ) ? (array) $settings['register_action'] : [];
			$custom_redirect_url 	= ! empty( $settings['register_redirect_url']['url'] ) ? esc_url_raw( $settings['register_redirect_url']['url'] ) : '/';

			$previous_page_url 		= ! empty( $_POST['redirect_to_prev_page'] ) ? sanitize_url( $_POST['redirect_to_prev_page'] ) : '';
			$custom_redirect_url 	= ! empty( $settings['register_redirect_url_prev_page'] ) && $settings['register_redirect_url_prev_page'] === 'yes' ? $previous_page_url : $custom_redirect_url;

			if ( ! empty( $settings['register_user_role'] ) ) {
				$user_data['role'] 	= sanitize_text_field( $settings['register_user_role'] );
			}

			if ( ! empty( $user_data['role'] ) && strtolower( $user_data['role'] ) === 'administrator' ) {
				$err_msg = __( 'Invalid Role!', 'essential-addons-for-elementor-lite' );

				if ( $ajax ) {
					wp_send_json_error( $err_msg );
				}
				update_option( 'eael_register_errors_' . $widget_id, $err_msg, false );

				if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
					wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
					exit();
				}

				return false;
			}

			// set email related stuff
			/*------User Mail Related Stuff------*/
			if ( $is_pass_auto_generated || ( in_array( 'send_email', $register_actions ) && 'custom' === $settings['reg_email_template_type'] ) ) {
				self::$send_custom_email = true;
			}
			if ( isset( $settings['reg_email_subject'] ) ) {
				self::$email_options['subject'] = Helper::eael_wp_kses( $settings['reg_email_subject'] );
			}
			if ( isset( $settings['reg_email_message'] ) ) {
				self::$email_options['message'] = $settings['reg_email_message'];
			}
			if ( isset( $settings['reg_email_content_type'] ) ) {
				self::$email_options['headers'] = 'Content-Type: text/' . wp_strip_all_tags( $settings['reg_email_content_type'] ) . '; charset=UTF-8' . "\r\n";
			}


			/*------Admin Mail Related Stuff------*/
			self::$send_custom_email_admin = ( ! empty( $settings['reg_admin_email_template_type'] ) && 'custom' === $settings['reg_admin_email_template_type'] );
			if ( isset( $settings['reg_admin_email_subject'] ) ) {
				self::$email_options['admin_subject'] = Helper::eael_wp_kses( $settings['reg_admin_email_subject'] );
			}
			if ( isset( $settings['reg_admin_email_message'] ) ) {
				self::$email_options['admin_message'] = Helper::eael_wp_kses( $settings['reg_admin_email_message'] );
			}
			if ( isset( $settings['reg_admin_email_content_type'] ) ) {
				self::$email_options['admin_headers'] = 'Content-Type: text/' . wp_strip_all_tags( $settings['reg_admin_email_content_type'] ) . '; charset=UTF-8' . "\r\n";
			}
		}

		$custom_redirect_url = apply_filters( 'eael/login-register/register-redirect-url', $custom_redirect_url, $this );

		$user_data = apply_filters( 'eael/login-register/new-user-data', $user_data );

		do_action( 'eael/login-register/before-insert-user', $user_data );
		$user_default_role = get_option( 'default_role' );

		if ( ! empty( $user_default_role ) && empty( $user_data['role'] ) ) {
			$user_data['role'] = $user_default_role;
		}

		$user_id = wp_insert_user( $user_data );

		if( count( $eael_custom_profile_fields_image ) ){
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/media.php' );

			foreach( $eael_custom_profile_fields_image as $eael_custom_profile_field_image_key => $eael_custom_profile_field_value ){
				self::$email_options[$eael_custom_profile_field_image_key] = '';

				if ( ! empty( $_FILES[ $eael_custom_profile_field_image_key ] ) ) {
					$attachment_id = media_handle_upload( $eael_custom_profile_field_image_key, 0, [ 'post_author' => $user_id ] );
					if ( ! is_wp_error( $attachment_id ) ) {
						$user_data[ $eael_custom_profile_field_image_key ] = sanitize_text_field( $attachment_id );
						self::$email_options[$eael_custom_profile_field_image_key] = wp_get_attachment_image_url( sanitize_text_field( $attachment_id ) );
					}
				}
			}
		}

		if ( ! empty( $user_data['eael_phone_number'] ) ) {
			update_user_meta( $user_id, 'eael_phone_number', $user_data['eael_phone_number'] );
		}

		if( count( $eael_custom_profile_fields ) ){
			foreach( $eael_custom_profile_fields as $eael_custom_profile_field_key => $eael_custom_profile_field_value ){
				if ( ! empty( $user_data[ $eael_custom_profile_field_key ] ) ) {
					update_user_meta( $user_id, self::$eael_custom_profile_field_prefix . $eael_custom_profile_field_key, $user_data[ $eael_custom_profile_field_key ] );
				}
			}
		}

		do_action( 'eael/login-register/after-insert-user', $user_id, $user_data );

		if ( is_wp_error( $user_id ) ) {
			// error happened during user creation
			$errors['user_create'] = isset( $settings['err_unknown'] ) ? Helper::eael_wp_kses( $settings['err_unknown'] ) :  __( 'Sorry, something went wrong. User could not be registered.', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $errors['user_create'] );
			}
			update_option( 'eael_register_errors_' . $widget_id, $errors, false );
			wp_safe_redirect( esc_url_raw( $url ) );
			exit();
		}

		do_action( 'eael/login-register/mailchimp-integration-action', $user_id, $user_data, $settings );

		// generate password reset link for autogenerated password
		if ( $is_pass_auto_generated ) {
			update_user_option( $user_id, 'default_password_nag', true, true ); // Set up the password change nag.
			$user = get_user_by( 'id', $user_id );
			$key  = get_password_reset_key( $user );
			if ( ! is_wp_error( $key ) ) {
				self::$email_options['password_reset_link'] = add_query_arg(
																array(
																	'action'	=> 'rp',
																	'key'		=> $key,
																	'login'		=> rawurlencode( $user->user_login ),
																),
																esc_url_raw( $this->eael_wp_login_url() )
															);
				self::$email_options['password_reset_link'] = self::$email_options['password_reset_link'] . "\r\n\r\n";
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
			'message' => isset( $settings['success_register'] ) ? Helper::eael_wp_kses( $settings['success_register'] ) : __( 'Your registration completed successfully.', 'essential-addons-for-elementor-lite' ),
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
				if ( in_array( 'redirect', $register_actions ) && ! empty( $custom_redirect_url ) ) {
					$data['redirect_to'] = $custom_redirect_url;
				}
				wp_send_json_success( $data );
			}

			// if custom redirect not available then refresh the current page to show admin bar
			if ( ! in_array( 'redirect', $register_actions ) ) {
				wp_safe_redirect( esc_url_raw( $url ) );
				exit();
			}
		}

		// custom redirect?
		if ( $ajax ) {
			if ( in_array( 'redirect', $register_actions ) && ! empty( $custom_redirect_url ) ) {
				$data['redirect_to'] = $custom_redirect_url;
			}
			wp_send_json_success( $data );
		}

		if ( in_array( 'redirect', $register_actions ) && ! empty( $custom_redirect_url ) ) {
			wp_safe_redirect( $custom_redirect_url );
			exit();
		}

        if (isset($_SERVER['HTTP_REFERER'])) {
            wp_safe_redirect($_SERVER['HTTP_REFERER']);
            exit();
        }

	}

	/**
	 * It sends the user an email with reset password link. Lost Password form is submitted normally without AJAX.
	 */
	public function send_password_reset() {
		$ajax   = wp_doing_ajax();
		// before even thinking about sending mail, check security and exit early if something is not right.
		$page_id = 0;
		$page_id_for_popup = 0;
		$resetpassword_in_popup_selector = '';
		if ( ! empty( $_POST['page_id'] ) ) {
			$page_id = intval( $_POST['page_id'], 10 );
			$page_id_for_popup = ! empty( $_POST['page_id_for_popup'] ) ? intval( $_POST['page_id_for_popup'], 10 ) : $page_id;
			$resetpassword_in_popup_selector = ! empty( $_POST['resetpassword_in_popup_selector'] ) ? sanitize_text_field( $_POST['resetpassword_in_popup_selector'] ) : '';
		} else {
			$err_msg = esc_html__( 'Page ID is missing', 'essential-addons-for-elementor-lite' );
		}

		$widget_id = 0;
		if ( ! empty( $_POST['widget_id'] ) ) {
			$widget_id = sanitize_text_field( $_POST['widget_id'] );
		} else {
			$err_msg = esc_html__( 'Widget ID is missing', 'essential-addons-for-elementor-lite' );
		}

		if (!empty( $err_msg )){
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			update_option( 'eael_losstpassword_error_' . $widget_id, $err_msg, false );

            if (isset($_SERVER['HTTP_REFERER'])) {
                wp_safe_redirect($_SERVER['HTTP_REFERER']);
                exit();
            }
		}


		if ( empty( $_POST['eael-lostpassword-nonce'] ) ) {
			$err_msg = esc_html__( 'Insecure form submitted without security token', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			update_option( 'eael_lostpassword_error_' . $widget_id, $err_msg, false );

            if (isset($_SERVER['HTTP_REFERER'])) {
                wp_safe_redirect($_SERVER['HTTP_REFERER']);
                exit();
            }
		}
		if ( ! wp_verify_nonce( $_POST['eael-lostpassword-nonce'], 'essential-addons-elementor' ) ) {
			$err_msg = esc_html__( 'Security token did not match', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			update_option( 'eael_lostpassword_error_' . $widget_id, $err_msg, false );

            if (isset($_SERVER['HTTP_REFERER'])) {
                wp_safe_redirect($_SERVER['HTTP_REFERER']);
                exit();
            }
		}
		
		$settings = $this->lr_get_widget_settings( $page_id, $widget_id);

		if ( is_user_logged_in() ) {
			$err_msg = isset( $settings['err_loggedin'] ) ? Helper::eael_wp_kses( $settings['err_loggedin'] ) : esc_html__( 'You are already logged in', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			update_option( 'eael_lostpassword_error_' . $widget_id, $err_msg, false );

            if (isset($_SERVER['HTTP_REFERER'])) {
                wp_safe_redirect($_SERVER['HTTP_REFERER']);
                exit();
            }
		}

		do_action( 'eael/login-register/before-lostpassword-email' );

		$widget_id = ! empty( $_POST['widget_id'] ) ? sanitize_text_field( $_POST['widget_id'] ) : '';

		if( $_POST['eael-user-lostpassword'] != wp_strip_all_tags( $_POST['eael-user-lostpassword'] ) ){
			// contains html tag
			$err_msg = esc_html__( 'There is no account with that username or email address.', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			update_option( 'eael_lostpassword_error_' . $widget_id, $err_msg, false );

            if (isset($_SERVER['HTTP_REFERER'])) {
                wp_safe_redirect($_SERVER['HTTP_REFERER']);
                exit();
            }
		}

		$user_login = ! empty( $_POST['eael-user-lostpassword'] ) ? sanitize_text_field( $_POST['eael-user-lostpassword'] ) : '';
		if ( is_email( $user_login ) ) {
			$user_login = sanitize_email( $user_login );
		}

		// set email related stuff
		if ( ! empty( $settings['enable_reset_password'] ) && 'yes' === $settings['enable_reset_password'] ) {
			self::$send_custom_email_lostpassword = true;
		}
		if ( isset( $settings['lostpassword_email_subject'] ) ) {
			self::$email_options_lostpassword['subject'] = Helper::eael_wp_kses( $settings['lostpassword_email_subject'] );
		}
		if ( isset( $settings['lostpassword_email_message_reset_link_text'] ) ) {
			self::$email_options_lostpassword['reset_link_text'] = Helper::eael_wp_kses( $settings['lostpassword_email_message_reset_link_text'] );
		}
		if ( isset( $settings['lostpassword_email_message'] ) ) {
			self::$email_options_lostpassword['message'] = $settings['lostpassword_email_message'];
		}
		if ( isset( $settings['lostpassword_email_content_type'] ) ) {
			self::$email_options_lostpassword['headers'] = 'Content-Type: text/' . Helper::eael_wp_kses( $settings['lostpassword_email_content_type'] ) . '; charset=UTF-8' . "\r\n";
		}

		if ( isset($_SERVER['HTTP_REFERER']) ) {
			self::$email_options_lostpassword['http_referer'] = esc_url_raw( strtok( $_SERVER['HTTP_REFERER'], '?' ) );
		}
		
		if ( isset($page_id) ) {
			self::$email_options_lostpassword['page_id'] = sanitize_text_field( $page_id );
		}

		if ( ! empty( $page_id_for_popup ) ) {
			self::$email_options_lostpassword['page_id'] = sanitize_text_field( $page_id_for_popup );
		}

		if ( ! empty( $resetpassword_in_popup_selector ) ) {
			self::$email_options_lostpassword['resetpassword_in_popup_selector'] = sanitize_text_field( $resetpassword_in_popup_selector );
		}

		if ( isset($widget_id) ) {
			self::$email_options_lostpassword['widget_id'] = sanitize_text_field( $widget_id );
		}

		add_filter( 'retrieve_password_notification_email', [ $this, 'eael_retrieve_password_notification_email' ], 10, 4 );
		
		$results = retrieve_password( $user_login );
		
		if ( is_wp_error( $results ) ) {
			$err_msg = '';
			if ( isset( $results->errors['invalidcombo'][0] ) ) {
				$err_msg = esc_html__( 'There is no account with that username or email address.', 'essential-addons-for-elementor-lite' );
			}else if( isset( $results->errors ) && count( $results->errors ) ) {
				$err_msg = esc_html__( 'There is no account with that username or email address.', 'essential-addons-for-elementor-lite' );
			}

			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			update_option( 'eael_lostpassword_error_' . $widget_id, $err_msg, false );
		} else {
			$lostpassword_success_message = ! empty( $settings['success_lostpassword'] ) ? Helper::eael_wp_kses( $settings['success_lostpassword'] ) : Helper::eael_wp_kses( 'Check your email for the confirmation link.' ); 
			$data = [
				'message' => $lostpassword_success_message,
			];

			if ( $ajax ) {
				if ( ! empty( $_POST['redirect_to'] ) ) {
					$data['redirect_to'] = esc_url_raw( $_POST['redirect_to'] );
				}
				wp_send_json_success( $data );
			} else {
				update_option( 'eael_lostpassword_success_' . $widget_id, $data['message'], false );
			}

			if ( ! empty( $_POST['redirect_to'] ) ) {
				wp_safe_redirect( esc_url_raw( $_POST['redirect_to'] ) );
				exit();
			}
		}
        if (isset($_SERVER['HTTP_REFERER'])) {
            wp_safe_redirect($_SERVER['HTTP_REFERER']);
            exit();
        }
	}
	
	/**
	 * It reset the password with user submitted new password.
	 */
	public function reset_password() {
		$ajax   = wp_doing_ajax();
		$page_id = 0;
		if ( ! empty( $_POST['page_id'] ) ) {
			$page_id = intval( $_POST['page_id'], 10 );
		} else {
			$err_msg = esc_html__( 'Page ID is missing', 'essential-addons-for-elementor-lite' );
		}

		$widget_id = 0;
		if ( ! empty( $_POST['widget_id'] ) ) {
			$widget_id = sanitize_text_field( $_POST['widget_id'] );
		} else {
			$err_msg = esc_html__( 'Widget ID is missing', 'essential-addons-for-elementor-lite' );
		}

		update_option( 'eael_show_reset_password_on_form_submit_' . $widget_id, true, false );

		if (!empty( $err_msg )){
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			update_option( 'eael_resetpassword_error_' . $widget_id, $err_msg, false );

            if (isset($_SERVER['HTTP_REFERER'])) {
                wp_safe_redirect($_SERVER['HTTP_REFERER']);
                exit();
            }
		}

		if ( empty( $_POST['eael-resetpassword-nonce'] ) ) {
			$err_msg = esc_html__( 'Insecure form submitted without security token', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			update_option( 'eael_resetpassword_error_' . $widget_id, $err_msg, false );

            if (isset($_SERVER['HTTP_REFERER'])) {
                wp_safe_redirect($_SERVER['HTTP_REFERER']);
                exit();
            }
		}
		if ( ! wp_verify_nonce( $_POST['eael-resetpassword-nonce'], 'essential-addons-elementor' ) ) {
			$err_msg = esc_html__( 'Security token did not match', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			update_option( 'eael_resetpassword_error_' . $widget_id, $err_msg, false );

            if (isset($_SERVER['HTTP_REFERER'])) {
                wp_safe_redirect($_SERVER['HTTP_REFERER']);
                exit();
            }
		}
		$settings = $this->lr_get_widget_settings( $page_id, $widget_id);

		if ( is_user_logged_in() ) {
			$err_msg = isset( $settings['err_loggedin'] ) ? Helper::eael_wp_kses( $settings['err_loggedin'] ) : esc_html__( 'You are already logged in', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			update_option( 'eael_resetpassword_error_' . $widget_id, $err_msg, false );

            if (isset($_SERVER['HTTP_REFERER'])) {
                wp_safe_redirect($_SERVER['HTTP_REFERER']);
                exit();
            }
		}

		do_action( 'eael/login-register/before-resetpassword-email' );

		$widget_id = ! empty( $_POST['widget_id'] ) ? sanitize_text_field( $_POST['widget_id'] ) : '';
		// Check if password is one or all empty spaces.
		$errors = [];
		if ( ! empty( $_POST['eael-pass1'] ) ) {
			$post_eael_pass1 = trim( $_POST['eael-pass1'] );

			if ( empty( $post_eael_pass1 ) ) {
				$errors['password_reset_empty_space'] = isset( $settings['err_pass'] ) ? Helper::eael_wp_kses( $settings['err_pass'] ) : esc_html__( 'The password cannot be a space or all spaces.', 'essential-addons-for-elementor-lite' );
			}
		} else {
			if ( empty( $_POST['eael-pass1'] ) ) {
				$errors['password_reset_empty_space'] = isset( $settings['err_pass'] ) ? Helper::eael_wp_kses( $settings['err_pass'] ) : esc_html__( 'The password cannot be a space or all spaces.', 'essential-addons-for-elementor-lite' );
			}
		}

		if( ! empty( $_POST['eael-pass1'] ) && strlen( trim( $_POST['eael-pass1'] ) ) == 0 ){
			$errors['password_reset_empty'] = esc_html__( 'The password cannot be empty.', 'essential-addons-for-elementor-lite' );
		}
		
		// Check if password fields do not match.
		if ( ! empty( $_POST['eael-pass1'] ) && $_POST['eael-pass2'] !== $_POST['eael-pass1'] ) {
			$errors['password_reset_mismatch'] = isset( $settings['err_conf_pass'] ) ? Helper::eael_wp_kses( $settings['err_conf_pass'] ) : esc_html__( 'The passwords do not match.', 'essential-addons-for-elementor-lite' );
		}

		if ( ( ! count( $errors ) ) && isset( $_POST['eael-pass1'] ) && ! empty( $_POST['eael-pass1'] ) ) {
			$rp_data_db['rp_key']   = ! empty( $_POST['rp_key'] ) ? sanitize_text_field( $_POST['rp_key'] ) : '';
			$rp_data_db['rp_login'] = ! empty( $_POST['rp_login'] ) ? sanitize_text_field( $_POST['rp_login'] ) : '';
			
			$user = check_password_reset_key( $rp_data_db['rp_key'], $rp_data_db['rp_login'] );

			if( is_wp_error( $user ) || ! $user ){
				$data['message'] = isset( $settings['error_resetpassword'] ) ? Helper::eael_wp_kses( $settings['error_resetpassword'] ) : esc_html__( 'Invalid user name found!', 'essential-addons-for-elementor-lite' );

				$success_key = 'eael_resetpassword_success_' . esc_attr( $widget_id );
				delete_option( $success_key );

				if($ajax){
					wp_send_json_error( $data['message'] );
				}else {
					update_option( 'eael_resetpassword_error_' . $widget_id, $data['message'], false );
				}
			}

			if( $user && ! is_wp_error( $user ) ){
				try {
					reset_password( $user, sanitize_text_field( $_POST['eael-pass1'] ) );
					$data['message'] = isset( $settings['success_resetpassword'] ) ? Helper::eael_wp_kses( $settings['success_resetpassword'] ) : esc_html__( 'Your password has been reset.', 'essential-addons-for-elementor-lite' );

					$error_key = 'eael_resetpassword_error_' . esc_attr( $widget_id );
					delete_option( $error_key );
					delete_option( 'eael_show_reset_password_on_form_submit_' . $widget_id );

					if($ajax){
						// $custom_redirect_url = ! empty( $settings['resetpassword_redirect_url']['url'] ) ? $settings['resetpassword_redirect_url']['url'] : '/';
						if( ! empty( $_POST['resetpassword_redirect_to'] ) ){
							$data['redirect_to'] = esc_url_raw( $_POST['resetpassword_redirect_to'] );
						}

						wp_send_json_success( $data );
					} else {
						update_option( 'eael_resetpassword_success_' . $widget_id, $data['message'], false );
					}

					if ( ! empty( $_POST['resetpassword_redirect_to'] ) ) {
						wp_safe_redirect( esc_url_raw( $_POST['resetpassword_redirect_to'] ) );
						exit();
					}
				} catch ( \Exception $e ) {
					// Do nothing
					unset( $e );
				}
			}

			if (isset($_SERVER['HTTP_REFERER'])) {
				wp_safe_redirect( strtok( $_SERVER['HTTP_REFERER'], '?' ) );
				exit();
			}
		} else {
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
				update_option( 'eael_resetpassword_error_' . $widget_id, maybe_serialize( $errors ), false );

				if (isset( $_SERVER['HTTP_REFERER'] )) {
					wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
					exit();
				}
			}
		}

	}

	public function eael_redirect_to_reset_password(){
		if( empty($_GET['eael-resetpassword']) ){
			return;
		}

		$this->page_id = isset( $_GET['page_id'] ) ? intval( $_GET['page_id'] ) : 0;
		$this->widget_id = isset( $_GET['widget_id'] ) ? sanitize_text_field( $_GET['widget_id'] ) : '';
		$this->resetpassword_in_popup_selector = isset( $_GET['popup-selector'] ) ? sanitize_text_field( $_GET['popup-selector'] ) : '';
		$rp_page_url = get_permalink( $this->page_id );
		
		$rp_path = '/';
		$rp_cookie       = 'wp-resetpass-' . COOKIEHASH;

		if ( isset( $_GET['key'] ) && isset( $_GET['login'] ) ) {
			$value = sprintf( '%s:%s', wp_unslash( $_GET['login'] ), wp_unslash( $_GET['key'] ) );
			setcookie( $rp_cookie, $value, 0, $rp_path, COOKIE_DOMAIN, is_ssl(), true );

			wp_safe_redirect( remove_query_arg( array( 'key', 'login' ) ) );
			exit;
		}

		if ( isset( $_COOKIE[ $rp_cookie ] ) && 0 < strpos( $_COOKIE[ $rp_cookie ], ':' ) ) {
			list( $rp_login, $rp_key ) = explode( ':', wp_unslash( $_COOKIE[ $rp_cookie ] ), 2 );

			$user = check_password_reset_key( $rp_key, $rp_login );

			if ( isset( $_POST['eael-pass1'] ) && isset( $_POST['rp_key'] ) && ! hash_equals( $rp_key, $_POST['rp_key'] ) ) {
				$user = false;
			}
		} else {
			$user = false;
		}

		if ( ! $user || is_wp_error( $user ) ) {
			setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
			update_option( 'eael_lostpassword_error_' . esc_attr( $this->widget_id ) . '_show', 1, false );

			if ( $user && $user->get_error_code() === 'expired_key' ) {
				wp_redirect( $rp_page_url . '?eael-lostpassword=1&error=expiredkey' );
			} else {
				wp_redirect( $rp_page_url . '?eael-lostpassword=1&error=expiredkey' );
			}

			exit;
		}

		if( $this->resetpassword_in_popup_selector ){
			wp_redirect( $rp_page_url . '?eael-resetpassword=1&popup-selector=' . $this->resetpassword_in_popup_selector );
		} else {
			wp_redirect( $rp_page_url . '?eael-resetpassword=1' );
		}

		exit;
	}

	public function eael_retrieve_password_notification_email( $defaults, $key, $user_login, $user_data ){
		if ( ! self::$send_custom_email_lostpassword ) {
			return $defaults;
		}

		if ( ! empty( self::$email_options_lostpassword['subject'] ) ) {
			$defaults['subject'] = self::$email_options_lostpassword['subject'];
		}

		$page_id = self::$email_options_lostpassword['page_id'] ? self::$email_options_lostpassword['page_id'] : 0;
		$widget_id = self::$email_options_lostpassword['widget_id'] ? self::$email_options_lostpassword['widget_id'] : '';
		$resetpassword_in_popup_selector = self::$email_options_lostpassword['resetpassword_in_popup_selector'] ? str_replace(' ', '_', self::$email_options_lostpassword['resetpassword_in_popup_selector']) : '';

		if ( ! empty( self::$email_options_lostpassword['message'] ) ) {
			if ( ! empty( $key ) ) {
				$locale = get_user_locale( $user_data );
				self::$email_options_lostpassword['password_reset_link'] = add_query_arg(
					array(
						'action'				=> 'rp',
						'eael-resetpassword'	=> 1,
						'key'					=> $key,
						'login'					=> rawurlencode( $user_login ),
					),
					esc_url_raw( $this->eael_wp_login_url() )
				);
				self::$email_options_lostpassword['password_reset_link'] = self::$email_options_lostpassword['password_reset_link'] . '&page_id='. $page_id . '&widget_id='. $widget_id .'&wp_lang=' . $locale . "\r\n\r\n";

                if( ! empty( $resetpassword_in_popup_selector ) ){
					self::$email_options_lostpassword['password_reset_link'] = add_query_arg(
						array(
							'action'				=> 'rp',
							'eael-resetpassword'	=> '1',
							'key'					=> $key,
							'login'					=> rawurlencode( $user_login ),
							'page_id'				=> $page_id,
							'widget_id'				=> $widget_id,
							'popup-selector'		=> $resetpassword_in_popup_selector,
							'wp_lang'				=> $locale,
						),
						esc_url_raw( $this->eael_wp_login_url() )
					);
				}
			}

			if( is_object($user_data) ) {
				$user_meta = get_user_meta( $user_data->ID );
				self::$email_options_lostpassword['username'] = $user_login;
				self::$email_options_lostpassword['firstname'] = !empty( $user_meta['first_name'][0] ) ? $user_meta['first_name'][0] : '';
				self::$email_options_lostpassword['lastname'] = !empty( $user_meta['last_name'][0] ) ? $user_meta['last_name'][0] : '';
				self::$email_options_lostpassword['email'] = $user_data->user_email;
				self::$email_options_lostpassword['website'] = $user_data->user_url;				
			}
			$defaults['message'] = $this->replace_placeholders_lostpassword( self::$email_options_lostpassword['message'] );
		}

		if ( ! empty( self::$email_options_lostpassword['headers'] ) ) {
			$defaults['headers'] = self::$email_options_lostpassword['headers'];
		}

		$defaults['message'] = wpautop( $defaults['message'] );
		
		return $defaults;
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
					if ( $role_key === 'administrator' ) {
						continue;
					}
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
			if ( isset( self::$email_options['password_reset_link'] ) && self::$email_options['password_reset_link'] != '' ) {
				$_message = $email_data['message'];
				$start    = 'action=rp&key=';
				$end      = '&login=';
				$_message = substr( $_message, strpos( $_message, $start ) + strlen( $start ) );
				$key      = substr( $_message, 0, strpos( $_message, $end ) );
				if ( ! empty( $key ) ) {
					self::$email_options_lostpassword['password_reset_link'] = add_query_arg(
						array(
							'action'				=> 'rp',
							'key'					=> $key,
							'login'					=> rawurlencode( $user->user_login ),
						),
						esc_url_raw( $this->eael_wp_login_url() )
					);
					self::$email_options['password_reset_link'] = self::$email_options['password_reset_link'] . "\r\n\r\n";
				}
			}
			$email_data['message'] = $this->replace_placeholders( self::$email_options['message'], 'user' );
		}

		if ( ! empty( self::$email_options['headers'] ) ) {
			$email_data['headers'] = self::$email_options['headers'];
		}

		$email_data['message'] = wpautop( $email_data['message'] );

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

		$email_data['message'] = wpautop( $email_data['message'] );

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
			'/\[eael_phone_number\]/',
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
			self::$email_options['eael_phone_number'],
			self::$email_options['password'],
			self::$email_options['password_reset_link'],
			self::$email_options['username'],
			self::$email_options['email'],
			self::$email_options['firstname'],
			self::$email_options['lastname'],
			self::$email_options['website'],
			esc_url_raw( $this->eael_wp_login_url() ),
			get_option( 'blogname' ),
		];

		if ( 'user' !== $receiver ) {
			// remove password from admin mail, because admin should not see user's plain password
			unset( $placeholders[1] );
			unset( $placeholders[2] );
			unset( $replacement[1] );
			unset( $replacement[2] );
		}

		$message = preg_replace( $placeholders, $replacement, $message );

		$message = $this->replace_placeholders_custom_fields($message);

		return $message;
	}

	public function replace_placeholders_custom_fields( $message ){
		// replace custom profile field shortcodes
		$eael_custom_profile_fields_text = $this->get_eael_custom_profile_fields('text');
		$eael_custom_profile_fields_image = $this->get_eael_custom_profile_fields('image');
		$eael_custom_profile_fields_text_keys = count( $eael_custom_profile_fields_text ) ? array_keys( $eael_custom_profile_fields_text ) : [];
		$eael_custom_profile_fields_image_keys = count( $eael_custom_profile_fields_image ) ? array_keys( $eael_custom_profile_fields_image ) : [];

		$custom_field_placeholders = $custom_field_replacements = [];

		if( count( $eael_custom_profile_fields_text_keys ) ){
			foreach( $eael_custom_profile_fields_text_keys as $eael_custom_profile_fields_text_key){
				$custom_field_placeholders[] = '/\[' . esc_html( $eael_custom_profile_fields_text_key ) . '\]/';
				$custom_field_replacements[] = esc_html( self::$email_options[$eael_custom_profile_fields_text_key] );
			}
		}

		if( count( $eael_custom_profile_fields_image_keys ) ){
			foreach( $eael_custom_profile_fields_image_keys as $eael_custom_profile_fields_image_key){
				$custom_field_placeholders[] = '/\[' . esc_html( $eael_custom_profile_fields_image_key ) . '\]/';
				$custom_field_replacements[] = esc_url( self::$email_options[$eael_custom_profile_fields_image_key] );
			}
		}

		if( count( $custom_field_placeholders ) ){
			$message = preg_replace( $custom_field_placeholders, $custom_field_replacements, $message );
		}

		return $message;
	}

	/**
	 * It replaces placeholders with dynamic value and returns it.
	 *
	 * @param        $message
	 * @param string $receiver
	 *
	 * @return null|string|string[]
	 */
	public function replace_placeholders_lostpassword( $message ) {
		$reset_link_text   = !empty( self::$email_options_lostpassword['reset_link_text'] ) ? self::$email_options_lostpassword['reset_link_text'] : esc_html__('Click here to reset your password', 'essential-addons-for-elementor-lite');
		$password_reset_link = !empty( self::$email_options_lostpassword['password_reset_link'] ) ? '<a href="'.esc_url_raw( self::$email_options_lostpassword['password_reset_link'] ).'">' . esc_html( $reset_link_text ) . '</a>' : '';
		$username 		   = !empty( self::$email_options_lostpassword['username'] ) ? self::$email_options_lostpassword['username'] : '';
		$email 			   = !empty( self::$email_options_lostpassword['email'] ) ? self::$email_options_lostpassword['email'] : '';
		$firstname 		   = !empty( self::$email_options_lostpassword['firstname'] ) ? self::$email_options_lostpassword['firstname'] : '';
		$lastname 		   = !empty( self::$email_options_lostpassword['lastname'] ) ? self::$email_options_lostpassword['lastname'] : '';
		$website 		   = !empty( self::$email_options_lostpassword['website'] ) ? self::$email_options_lostpassword['website'] : '';
		
		$placeholders = [
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
			$password_reset_link,
			$username,
			$email,
			$firstname,
			$lastname,
			$website,
			esc_url_raw( $this->eael_wp_login_url() ),
			get_option( 'blogname' ),
		];

		return preg_replace( $placeholders, $replacement, $message );
	}

	/**
	 * It replaces placeholders with dynamic value and returns it.
	 *
	 * @param        $text
	 * @param string $receiver
	 *
	 * @return null|string|string[]
	 */
	public function replace_placeholders_logout_link_text( $text ) {
		$current_user   = wp_get_current_user()->display_name;
		$logout_link 	= sprintf( '<a href="%1$s">%2$s</a>', esc_url( wp_logout_url() ), __( 'Logout', 'essential-addons-for-elementor-lite' ) );

		$placeholders = [
			'/\[username\]/',
			'/\[logout_link\]/',
		];
		$replacement  = [
			$current_user,
			$logout_link,
		];

		return preg_replace( $placeholders, $replacement, $text );
	}

	public function lr_validate_recaptcha($version = 'v2') {
		if ( ! isset( $_REQUEST['g-recaptcha-response'] ) ) {
			return false;
		}
		$endpoint = 'https://www.recaptcha.net/recaptcha/api/siteverify';
		$data     = [
			'secret'   => 'v3' === $version ? get_option( 'eael_recaptcha_secret_v3' ) : get_option( 'eael_recaptcha_secret' ),
			'response' => sanitize_text_field( $_REQUEST['g-recaptcha-response'] ),
			'ip'       => $_SERVER['REMOTE_ADDR'],
		];

		$res = json_decode( wp_remote_retrieve_body( wp_remote_post( $endpoint, [ 'body' => $data ] ) ), 1 );
		if ( isset( $res['success'] ) ) {
			if('v3' === $version ) {
				$action = self::$recaptcha_v3_default_action;
				$action_ok = ! isset( $res['action'] ) ? true : $action === $res['action'];
				return $action_ok && isset( $res['score'] ) && ( $res['score'] > self::get_recaptcha_threshold() );
			}else {
				return $res['success'];				
			}
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

	/**
	 * Add extra custom fields on user profile (e.x. edit page and Registration form).
	 * @param \WP_User $user
	 * 
	 * @since 5.1.4
	 */
	public function eael_extra_user_profile_fields( $user ){ ?>
		<?php $eael_custom_profile_fields_text = $this->get_eael_custom_profile_fields('text'); ?>
		<?php $eael_custom_profile_fields_image = $this->get_eael_custom_profile_fields('image'); ?>

		<?php //if ( count( $eael_custom_profile_fields_text ) || count( $eael_custom_profile_fields_image ) ): ?>
		<h3><?php _e("EA Login | Register Form", "blank"); ?></h3>
		<?php // endif; ?>

		<table class="form-table">
		<tr>
				<th><label for="eael_phone_number"><?php _e("Phone"); ?></label></th>
				<td>
					<input type="text" name="eael_phone_number" id="eael_phone_number" value="<?php echo esc_attr( get_the_author_meta( 'eael_phone_number', $user->ID ) ); ?>" class="regular-text" /><br />
					<p class="description"><?php esc_html_e("Please enter your phone number."); ?></p>
				</td>
			</tr>
		<?php
		if( count( $eael_custom_profile_fields_text ) ) :
			foreach( $eael_custom_profile_fields_text as $eael_custom_profile_field_text_key => $eael_custom_profile_field_value ) :
		?>
			<tr>
				<th><label for="<?php echo esc_attr( $eael_custom_profile_field_text_key ); ?>"><?php _e( esc_html( $eael_custom_profile_field_value ) ); ?></label></th>
				<td>
					<input type="text" name="<?php echo esc_attr( $eael_custom_profile_field_text_key ); ?>" id="<?php echo esc_attr( $eael_custom_profile_field_text_key ); ?>" value="<?php echo esc_attr( get_the_author_meta( self::$eael_custom_profile_field_prefix . $eael_custom_profile_field_text_key, $user->ID ) ); ?>" class="regular-text" /><br />
					<!-- <p class="description"><?php //printf( __( "Please Enter %s", 'essential-addons-for-elementor-lite'), esc_html( $custom_profile_fields_text )); ?></p> -->
				</td>
			</tr>
		<?php
			endforeach;
		endif;
		?>

		<?php
		if( count( $eael_custom_profile_fields_image ) ) :
			foreach( $eael_custom_profile_fields_image as $eael_custom_profile_field_image_key => $eael_custom_profile_field_value ) :
				$user_meta_attachment_id = get_the_author_meta( self::$eael_custom_profile_field_prefix . $eael_custom_profile_field_image_key, $user->ID );
		?>
			<tr>
				<th><label for="<?php echo esc_attr( $eael_custom_profile_field_image_key ); ?>"><?php _e( esc_html( $eael_custom_profile_field_value ) ); ?></label></th>
				<td>
					<input type="text" name="<?php echo esc_attr( $eael_custom_profile_field_image_key ); ?>" id="<?php echo esc_attr( $eael_custom_profile_field_image_key ); ?>" value="<?php echo esc_attr( $user_meta_attachment_id ); ?>" class="regular-text" /><br />
					<p class="description"><?php printf( __( "Above, input the %s of the attachment.", 'essential-addons-for-elementor-lite'), esc_html( 'ID' )); ?></p>
					<?php
					if( ! empty( $user_meta_attachment_id ) ){
						echo Helper::eael_wp_kses( wp_get_attachment_image($user_meta_attachment_id, 'thumbnail', 1) );
					}
					?>
				</td>
			</tr>
		<?php
			endforeach;
		endif;
		?>
		</table>
	<?php }

	/**
	 * Save extra custom fields of user profile
	 * @param int $user_id
	 * 
	 * @since 5.1.4
	 */
	public function eael_save_extra_user_profile_fields( $user_id ){
		if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {
			return;
		}

		if ( !current_user_can( 'edit_user', $user_id ) ) { 
			return false; 
		}
		update_user_meta( $user_id, 'eael_phone_number', sanitize_text_field( $_POST['eael_phone_number'] ) );

		$eael_custom_profile_fields = $this->get_eael_custom_profile_fields('all');

		if( count( $eael_custom_profile_fields ) ){
			foreach( $eael_custom_profile_fields as $eael_custom_profile_field_key => $eael_custom_profile_field_value ){
				if( empty( $_POST[ $eael_custom_profile_field_key ] ) ){
					continue;
				}

				update_user_meta( $user_id, sanitize_key( self::$eael_custom_profile_field_prefix . $eael_custom_profile_field_key ), sanitize_text_field( $_POST[ $eael_custom_profile_field_key ] ) );
			}
		}
	}

	public function eael_is_phone($phone){
		if ( 0 < strlen( trim( preg_replace( '/[\s\#0-9_\-\+\/\(\)\.]/', '', $phone ) ) ) ) {
			return false;
		}

		if( strlen( str_replace(['+', '00', ' ', '(', ')', '-', '.', '_', '/'], '', $phone) ) === 0 ) {
			return false;
		}

		//Phone number length can't be more than 15
		if( strlen( str_replace(['+', '00', ' ', '(', ')', '-', '.', '_', '/'], '', $phone) ) > 15 ) {
			return false;
		}

		return true;
	}

	public function eael_wp_login_url(){
		return apply_filters( 'eael/login-register/wp-login-url', wp_login_url() );
	}

	public function get_eael_custom_profile_fields( $type = 'text' ){
		$eael_custom_profile_fields = [];
		$custom_profile_fields_arr 	= [];

		$eael_custom_profile_field_text_trimmed  	= trim( get_option( 'eael_custom_profile_fields_text' ), ' ,\n\r\0\x0B' );
		$eael_custom_profile_field_image_trimmed 	= trim( get_option( 'eael_custom_profile_fields_img' ), ' ,\n\r\0\x0B' );
		$eael_custom_profile_field_text_trimmed 	= str_replace(self::$eael_custom_profile_field_prefix, '', $eael_custom_profile_field_text_trimmed);
		$eael_custom_profile_field_image_trimmed 	= str_replace(self::$eael_custom_profile_field_prefix, '', $eael_custom_profile_field_image_trimmed);

		$custom_profile_fields_text_arr 			= ! empty ( $eael_custom_profile_field_text_trimmed ) ? array_unique( explode( ',', $eael_custom_profile_field_text_trimmed ) ) 	: [];
		$custom_profile_fields_img_arr  			= ! empty( $eael_custom_profile_field_image_trimmed ) ? array_unique( explode( ',', $eael_custom_profile_field_image_trimmed ) ) 	: [];
		$custom_profile_fields_all_arr 				= array_merge( $custom_profile_fields_text_arr, $custom_profile_fields_img_arr );

		switch( $type ){
			case 'text':
				$custom_profile_fields_arr = $custom_profile_fields_text_arr;
				break;

			case 'image':
				$custom_profile_fields_arr = $custom_profile_fields_img_arr;
				break;

			case 'all':
				$custom_profile_fields_arr = $custom_profile_fields_all_arr;
				break;

			default:
				break;
		}

		if( count( $custom_profile_fields_arr ) ){
			foreach( $custom_profile_fields_arr as $custom_profile_field_text ){
				$custom_profile_field_slug = str_replace(' ', '_', trim( strtolower( sanitize_text_field( $custom_profile_field_text ) ), ' ' ));
				$eael_custom_profile_fields[ sanitize_text_field( $custom_profile_field_slug ) ] = __( esc_html( $custom_profile_field_text ), 'essential-addons-for-elementor-lite' );
			}
		}

		return $eael_custom_profile_fields;
	}
}
