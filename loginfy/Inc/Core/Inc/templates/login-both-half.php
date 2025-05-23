<?php

/**
 * Template Name: Login Customizer
 *
 * Template to display the WordPress login form in the Customizer.
 * This is essentially a stripped down version of wp-login.php, though not accessible from outside the Customizer.
 */

// Redirect if viewed from outside of the WordPress Customizer.
if ( ! is_customize_preview() ) {
	$loginfy_login_customizer_page = get_page_by_path( 'loginfy-login-customizer' );

	// Generate the redirect url.
	$loginfy_login_customizer_redirect_url = add_query_arg(
		[
			'autofocus[panel]' => 'loginfy_panel',
			'return'           => admin_url( 'index.php' ),
			'url'              => rawurlencode( get_permalink( $loginfy_login_customizer_page ) ),
		],
		admin_url( 'customize.php' )
	);

	wp_safe_redirect( $loginfy_login_customizer_redirect_url );
}

// Redirect to HTTPS login if forced to use SSL.
if ( force_ssl_admin() && ! is_ssl() ) {
	if ( !empty($_SERVER['REQUEST_URI']) && (0 === strpos( esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ), 'http' ) ) ) {
		wp_safe_redirect( set_url_scheme( esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ), 'https' ) );
		exit();
	} else {
        if(isset($_SERVER['HTTP_HOST'])){
            wp_safe_redirect('https://' . esc_url_raw(wp_unslash($_SERVER['HTTP_HOST'])) . esc_url_raw(wp_unslash($_SERVER['REQUEST_URI'])));
            exit();
        }
	}
}

/**
 * Output the login page header.
 *
 * @since 2.1.0
 *
 * @param string   $title    Optional. WordPress login Page title to display in the `<title>` element.
 *                           Default 'Log In'.
 * @param string   $message  Optional. Message to display in header. Default empty.
 * @param WP_Error $wp_error Optional. The error to pass. Default is a WP_Error instance.
 */

function login_header( $title = 'Log In', $message = '', $wp_error = null ) {
	global $error, $interim_login, $action;

	// Don't index any of these forms
	add_action( 'login_head', 'wp_sensitive_page_meta' );

	add_action( 'login_head', 'wp_login_viewport_meta' );

	if ( ! is_wp_error( $wp_error ) ) {
		$wp_error = new WP_Error();
	}

	// Shake it!
	$loginfy_shake_error_codes = [ 'empty_password', 'empty_email', 'invalid_email', 'invalidcombo', 'empty_username', 'invalid_username', 'incorrect_password', 'retrieve_password_email_failure' ];
	/**
	 * Filters the error codes array for shaking the login form.
	 *
	 * @since 3.0.0
	 *
	 * @param array $loginfy_shake_error_codes Error codes that shake the login form.
	 */
	$loginfy_shake_error_codes = apply_filters( 'shake_error_codes', $loginfy_shake_error_codes );

	if ( $loginfy_shake_error_codes && $wp_error->has_errors() && in_array( $wp_error->get_error_code(), $loginfy_shake_error_codes, true ) ) {
		add_action( 'login_head', 'wp_shake_js', 12 );
	}

	$loginfy_login_title = get_bloginfo( 'name', 'display' );

	/* translators: Login screen title. 1: Login screen name, 2: Network or site name. */
	$loginfy_login_title = sprintf( __( '%1$s &lsaquo; %2$s &#8212; WordPress', 'loginfy' ), $title, $loginfy_login_title );

	if ( wp_is_recovery_mode() ) {
		/* translators: %s: Login screen title. */
		$loginfy_login_title = sprintf( __( 'Recovery Mode &#8212; %s', 'loginfy' ), $loginfy_login_title );
	}

	/**
	 * Filters the title tag content for login page.
	 *
	 * @since 4.9.0
	 *
	 * @param string $loginfy_login_title The page title, with extra context added.
	 * @param string $title       The original page title.
	 */
	$loginfy_login_title = apply_filters( 'login_title', $loginfy_login_title, $title );

	?>

	<!DOCTYPE html>
	<!--[if IE 8]>
		<html xmlns="http://www.w3.org/1999/xhtml" class="ie8" <?php language_attributes(); ?>>
	<![endif]-->
	<!--[if !(IE 8) ]><!-->
	<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<!--<![endif]-->

	<head>
		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
		<title><?php echo wp_kses_post( $loginfy_login_title ); ?></title>
		<?php

		wp_enqueue_style( 'login' );

		/**
		 * Enqueue scripts and styles for the login page.
		 *
		 * @since 3.1.0
		 */
		do_action( 'login_enqueue_scripts' );

		/**
		 * Fires in the login page header after scripts are enqueued.
		 *
		 * @since 2.1.0
		 */
		do_action( 'login_head' );

		$loginfy_login_header_url = 'https://wordpress.org/';

		/**
		 * Filters link URL of the header logo above login form.
		 *
		 * @since 2.1.0
		 *
		 * @param string $loginfy_login_header_url Login header logo URL.
		 */
		$loginfy_login_header_url = apply_filters( 'login_headerurl', $loginfy_login_header_url );

		$login_header_title = '';

		/**
		 * Filters the title attribute of the header logo above login form.
		 *
		 * @since 2.1.0
		 * @deprecated 5.2.0 Use login_headertext
		 *
		 * @param string $login_header_title Login header logo title attribute.
		 */
		$login_header_title = apply_filters_deprecated(
			'login_headertitle',
			[ $login_header_title ],
			'5.2.0',
			'login_headertext',
			__( 'Usage of the title attribute on the login logo is not recommended for accessibility reasons. Use the link text instead.', 'loginfy' )
		);

		$login_header_text = empty( $login_header_title ) ? __( 'Powered by WordPress', 'loginfy' ) : $login_header_title;

		/**
		 * Filters the link text of the header logo above the login form.
		 *
		 * @since 5.2.0
		 *
		 * @param string $login_header_text The login header logo link text.
		 */
		$login_header_text = apply_filters( 'login_headertext', $login_header_text );

		$classes = [ 'login-action-' . $action, 'wp-core-ui' ];

		if ( is_rtl() ) {
			$classes[] = 'rtl';
		}

		if ( $interim_login ) {
			$classes[] = 'interim-login';

			?>
			<style type="text/css">
				html {
					background-color: transparent;
				}
			</style>
			<?php

			if ( 'success' === $interim_login ) {
				$classes[] = 'interim-login-success';
			}
		}

		$classes[] = ' locale-' . sanitize_html_class( strtolower( str_replace( '_', '-', get_locale() ) ) );

		/**
		 * Filters the login page body classes.
		 *
		 * @since 3.5.0
		 *
		 * @param array  $classes An array of body classes.
		 * @param string $action  The action that brought the visitor to the login page.
		 */
		$classes = apply_filters( 'login_body_class', $classes, $action );

		?>
	</head>

	<body class="login <?php echo esc_attr( implode( ' ', $classes ) ); ?>">

		<?php
		/**
		 * Fires in the login page header after the body tag is opened.
		 *
		 * @since 4.6.0
		 */
		do_action( 'login_header' );

		?>
		<div class="columns all-centered">
			<div class="is-half">
				<!-- Image or another content goes here -->
			</div>
			<div class="is-half">
				<div id="login">
					<h1>
						<a class="loginfy-logo-login-link" href="<?php echo esc_url( $loginfy_login_header_url ); ?>">
							<?php echo wp_kses_post( $login_header_text ); ?>
						</a>
					</h1>
					<?php
					/**
					 * Filters the message to display above the login form.
					 *
					 * @since 2.1.0
					 *
					 * @param string $message Login message text.
					 */
					$message = apply_filters( 'login_message', $message );

					if ( ! empty( $message ) ) {
						echo wp_kses_post( $message ) . "\n";
					}

					// In case a plugin uses $error rather than the $wp_errors object.
					if ( ! empty( $error ) ) {
						$wp_error->add( 'error', $error );
						unset( $error );
					}

					if ( $wp_error->has_errors() ) {
						$errors   = '';
						$messages = '';

						foreach ( $wp_error->get_error_codes() as $code ) {
							$severity = $wp_error->get_error_data( $code );
							foreach ( $wp_error->get_error_messages( $code ) as $error_message ) {
								if ( 'message' === $severity ) {
									$messages .= '	' . $error_message . "<br />\n";
								} else {
									$errors .= '	' . $error_message . "<br />\n";
								}
							}
						}

						if ( ! empty( $errors ) ) {
							/**
							 * Filters the error messages displayed above the login form.
							 *
							 * @since 2.1.0
							 *
							 * @param string $errors Login error message.
							 */
							echo '<div id="login_error">' . wp_kses_post( apply_filters( 'login_errors', $errors ) ) . "</div>\n";
						}

						if ( ! empty( $messages ) ) {
							/**
							 * Filters instructional messages displayed above the login form.
							 *
							 * @since 2.5.0
							 *
							 * @param string $messages Login messages.
							 */
							echo '<p class="message">' . wp_kses_post( apply_filters( 'login_messages', $messages ) ) . "</p>\n";
						}
					}
} // End of login_header()

/**
 * Outputs the footer for the login page.
 *
 * @since 3.1.0
 *
 * @param string $input_id Which input to auto-focus.
 */
function login_footer( $input_id = '' ) {
	global $interim_login;

	// Don't allow interim logins to navigate away from the page.
	if ( ! $interim_login ) {
		?>
			<p id="backtoblog"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php

		/* translators: %s: Site title. */
			printf( _x( '&larr; Back to %s', 'site', 'loginfy' ), esc_html( get_bloginfo( 'title', 'display' ) ) );
		?>
			</a></p>
		<?php
			// the_privacy_policy_link( '<div class="privacy-policy-page-link">', '</div>' );
	}

	?>
				</div>
				<?php
				// End of <div id="login">
				?>
			</div>

		</div>

		<?php

		/**
		 * Fires in the login page footer.
		 *
		 * @since 3.1.0
		 */
		do_action( 'login_footer' );

		?>
		<div class="clear"></div>
	</body>

	</html>
	<?php
}

				/**
				 * Outputs the Javascript to handle the form shaking.
				 *
				 * @since 3.0.0
				 */
function wp_shake_js() {
	?>
	<script type="text/javascript">
		addLoadEvent = function(func) {
			if (typeof jQuery != "undefined") jQuery(document).ready(func);
			else if (typeof wpOnload != 'function') {
				wpOnload = func;
			} else {
				var oldonload = wpOnload;
				wpOnload = function() {
					oldonload();
					func();
				}
			}
		};

		function s(id, pos) {
			g(id).left = pos + 'px';
		}

		function g(id) {
			return document.getElementById(id).style;
		}

		function shake(id, a, d) {
			c = a.shift();
			s(id, c);
			if (a.length > 0) {
				setTimeout(function() {
					shake(id, a, d);
				}, d);
			} else {
				try {
					g(id).position = 'static';
					wp_attempt_focus();
				} catch (e) {}
			}
		}
		addLoadEvent(function() {
			var p = new Array(15, 30, 15, 0, -15, -30, -15, 0);
			p = p.concat(p.concat(p));
			var i = document.forms[0].id;
			g(i).position = 'relative';
			shake(i, p, 20);
		});
	</script>
	<?php
}

				/**
				 * Outputs the viewport meta tag.
				 *
				 * @since 3.7.0
				 */
function wp_login_viewport_meta() {
	?>
	<meta name="viewport" content="width=device-width" />
	<?php
}

				/**
				 * Handles sending password retrieval email to user.
				 *
				 * @since 2.5.0
				 *
				 * @return bool|WP_Error True: when finish. WP_Error on error
				 */
function jlt_loginfy_retrieve_password() {
	$errors = new WP_Error();

	if ( empty( sanitize_text_field( wp_unslash( $_POST['user_login'] ) ) ) || ! is_string( sanitize_text_field( wp_unslash( $_POST['user_login'] ) ) ) ) {
		$errors->add( 'empty_username', __( '<strong>ERROR</strong>: Enter a username or email address.', 'loginfy' ) );
	} elseif ( strpos( sanitize_text_field( wp_unslash( $_POST['user_login'] ) ), '@' ) ) {
		$user_data = get_user_by( 'email', trim( wp_unslash( sanitize_text_field( sanitize_text_field( wp_unslash( $_POST['user_login'] ) ) ) ) ) );
		if ( empty( $user_data ) ) {
			$errors->add( 'invalid_email', __( '<strong>ERROR</strong>: There is no account with that username or email address.', 'loginfy' ) );
		}
	} else {
		$login     = trim( sanitize_text_field( sanitize_text_field( wp_unslash( $_POST['user_login'] ) ) ) );
		$user_data = get_user_by( 'login', $login );
	}

	/**
	 * Fires before errors are returned from a password reset request.
	 *
	 * @since 2.1.0
	 * @since 4.4.0 Added the `$errors` parameter.
	 *
	 * @param WP_Error $errors A WP_Error object containing any errors generated
	 *                         by using invalid credentials.
	 */
	do_action( 'lostpassword_post', $errors );

	if ( $errors->has_errors() ) {
		return $errors;
	}

	if ( ! $user_data ) {
		$errors->add( 'invalidcombo', __( '<strong>ERROR</strong>: There is no account with that username or email address.', 'loginfy' ) );
		return $errors;
	}

	// Redefining user_login ensures we return the right case in the email.
	$user_login = $user_data->user_login;
	$user_email = $user_data->user_email;
	$key        = get_password_reset_key( $user_data );

	if ( is_wp_error( $key ) ) {
		return $key;
	}

	if ( is_multisite() ) {
		$site_name = get_network()->site_name;
	} else {
		/*
		* The blogname option is escaped with esc_html on the way into the database
		* in sanitize_option we want to reverse this for the plain text arena of emails.
		*/
		$site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
	}

	$message = __( 'Someone has requested a password reset for the following account:', 'loginfy' ) . "\r\n\r\n";
	/* translators: %s: Site name. */
	$message .= sprintf( __( 'Site Name: %s', 'loginfy' ), $site_name ) . "\r\n\r\n";
	/* translators: %s: User login. */
	$message .= sprintf( __( 'Username: %s', 'loginfy' ), $user_login ) . "\r\n\r\n";
	$message .= __( 'If this was a mistake, just ignore this email and nothing will happen.', 'loginfy' ) . "\r\n\r\n";
	$message .= __( 'To reset your password, visit the following address:', 'loginfy' ) . "\r\n\r\n";
	$message .= '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . ">\r\n";

	/* translators: Password reset notification email subject. %s: Site title. */
	$title = sprintf( __( '[%s] Password Reset', 'loginfy' ), $site_name );

	/**
	 * Filters the subject of the password reset email.
	 *
	 * @since 2.8.0
	 * @since 4.4.0 Added the `$user_login` and `$user_data` parameters.
	 *
	 * @param string  $title      Default email title.
	 * @param string  $user_login The username for the user.
	 * @param WP_User $user_data  WP_User object.
	 */
	$title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );

	/**
	 * Filters the message body of the password reset mail.
	 *
	 * If the filtered message is empty, the password reset email will not be sent.
	 *
	 * @since 2.8.0
	 * @since 4.1.0 Added `$user_login` and `$user_data` parameters.
	 *
	 * @param string  $message    Default mail message.
	 * @param string  $key        The activation key.
	 * @param string  $user_login The username for the user.
	 * @param WP_User $user_data  WP_User object.
	 */
	$message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );

	if ( $message && ! wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ) {
		$errors->add(
			'retrieve_password_email_failure',
			sprintf(
				/* translators: %s: Documentation URL. */
				__( '<strong>ERROR</strong>: The email could not be sent. Your site may not be correctly configured to send emails. <a href="%s">Get support for resetting your password</a>.', 'loginfy' ),
				esc_url( 'https://wordpress.org/support/article/resetting-your-password/' )
			)
		);
		return $errors;
	}

	return true;
}

//
// Main.
//

$action = isset( $_REQUEST['action'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) : 'login';
$errors = new \WP_Error();

if ( isset( $_GET['key'] ) ) {
	$action = 'resetpass';
}

				$default_actions = [
					'confirm_admin_email',
					'postpass',
					'logout',
					'lostpassword',
					'retrievepassword',
					'resetpass',
					'rp',
					'register',
					'login',
					'confirmaction',
					WP_Recovery_Mode_Link_Service::LOGIN_ACTION_ENTERED,
				];

				// Validate action so as to default to the login screen.
				if ( ! in_array( $action, $default_actions, true ) && false === has_filter( 'login_form_' . $action ) ) {
					$action = 'login';
				}

				nocache_headers();

				header( 'Content-Type: ' . get_bloginfo( 'html_type' ) . '; charset=' . get_bloginfo( 'charset' ) );

				if ( defined( 'RELOCATE' ) && RELOCATE ) { // Move flag is set
					if ( ( isset( $_SERVER['PATH_INFO'] ) && isset( $_SERVER['PHP_SELF'] ) ) && ( $_SERVER['PATH_INFO'] !== $_SERVER['PHP_SELF'] ) ) {
						$_SERVER['PHP_SELF'] = str_replace( sanitize_text_field( wp_unslash( $_SERVER['PATH_INFO'] ) ), '', sanitize_text_field( wp_unslash( $_SERVER['PHP_SELF'] ) ) );
					}

					$loginfy_site_url = dirname( set_url_scheme( 'http://' . sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) . sanitize_text_field( wp_unslash( $_SERVER['PHP_SELF'] ) ) ) );

					if ( $loginfy_site_url !== get_option( 'siteurl' ) ) {
						update_option( 'siteurl', $loginfy_site_url );
					}
				}

				// Set a cookie now to see if they are supported by the browser.
				$secure = ( 'https' === parse_url( wp_login_url(), PHP_URL_SCHEME ) );
				setcookie( TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN, $secure );

				if ( SITECOOKIEPATH != COOKIEPATH ) {
					setcookie( TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN, $secure );
				}

				/**
				 * Fires when the login form is initialized.
				 *
				 * @since 3.2.0
				 */
				do_action( 'login_init' );

				/**
				 * Fires before a specified login form action.
				 *
				 * The dynamic portion of the hook name, `$action`, refers to the action
				 * that brought the visitor to the login form. Actions include 'postpass',
				 * 'logout', 'lostpassword', etc.
				 *
				 * @since 2.8.0
				 */
				do_action( "login_form_{$action}" );

				$http_post     = !empty($_SERVER['REQUEST_METHOD']) ? ( 'POST' === $_SERVER['REQUEST_METHOD'] ) : '';
				$interim_login = isset( $_REQUEST['interim-login'] );

				/**
				 * Filters the separator used between login form navigation links.
				 *
				 * @since 4.9.0
				 *
				 * @param string $login_link_separator The separator used between login form navigation links.
				 */
				$login_link_separator = apply_filters( 'login_link_separator', ' | ' );

				switch ( $action ) {
					case 'confirm_admin_email':
						// Note that `is_user_logged_in()` will return false immediately after logging in
						// as the current user is not set, see wp-includes/pluggable.php.
						// However this action runs on a redirect after logging in.
						if ( ! is_user_logged_in() ) {
							wp_safe_redirect( wp_login_url() );
							exit;
						}

						if ( ! empty( esc_url_raw( wp_unslash( $_REQUEST['redirect_to'] ) ) ) ) {
							$redirect_to = esc_url_raw( wp_unslash( $_REQUEST['redirect_to'] ) );
						} else {
							$redirect_to = admin_url();
						}

						if ( current_user_can( 'manage_options' ) ) {
							$admin_email = get_option( 'admin_email' );
						} else {
							wp_safe_redirect( $redirect_to );
							exit;
						}

						/**
						 * Filters the interval for dismissing the admin email confirmation screen.
						 *
						 * If `0` (zero) is returned, the "Remind me later" link will not be displayed.
						 *
						 * @since 5.3.1
						 *
						 * @param int $interval Interval time (in seconds). Default is 3 days.
						 */
						$remind_interval = (int) apply_filters( 'admin_email_remind_interval', 3 * DAY_IN_SECONDS );

						if ( ! empty( sanitize_text_field( wp_unslash( $_GET['remind_me_later'] ) ) ) ) {
							if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['remind_me_later'] ) ), 'remind_me_later_nonce' ) ) {
								wp_safe_redirect( wp_login_url() );
								exit;
							}

							if ( $remind_interval > 0 ) {
								update_option( 'admin_email_lifespan', time() + $remind_interval );
							}

							wp_safe_redirect( $redirect_to );
							exit;
						}

						if ( ! empty( $_POST['correct-admin-email'] ) ) {
							if ( ! check_admin_referer( 'confirm_admin_email', 'confirm_admin_email_nonce' ) ) {
								wp_safe_redirect( wp_login_url() );
								exit;
							}

							/**
							 * Filters the interval for redirecting the user to the admin email confirmation screen.
							 *
							 * If `0` (zero) is returned, the user will not be redirected.
							 *
							 * @since 5.3.0
							 *
							 * @param int $interval Interval time (in seconds). Default is 6 months.
							 */
							$admin_email_check_interval = (int) apply_filters( 'admin_email_check_interval', 6 * MONTH_IN_SECONDS );

							if ( $admin_email_check_interval > 0 ) {
								update_option( 'admin_email_lifespan', time() + $admin_email_check_interval );
							}

							wp_safe_redirect( $redirect_to );
							exit;
						}

						login_header( __( 'Confirm your administration email', 'loginfy' ), '', $errors );

						/**
						 * Fires before the admin email confirm form.
						 *
						 * @since 5.3.0
						 *
						 * @param WP_Error $errors A `WP_Error` object containing any errors generated by using invalid
						 *                         credentials. Note that the error object may not contain any errors.
						 */
						do_action( 'admin_email_confirm', $errors );

						?>

		<form class="admin-email-confirm-form" name="admin-email-confirm-form" action="<?php echo esc_url( site_url( 'wp-login.php?action=confirm_admin_email', 'login_post' ) ); ?>" method="post">
						<?php
						/**
						 * Fires inside the admin-email-confirm-form form tags, before the hidden fields.
						 *
						 * @since 5.3.0
						 */
						do_action( 'admin_email_confirm_form' );

						wp_nonce_field( 'confirm_admin_email', 'confirm_admin_email_nonce' );

						?>
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>" />

			<h1 class="admin-email__heading">
						<?php esc_html_e( 'Administration email verification' ); ?>
			</h1>
			<p class="admin-email__details">
						<?php esc_html_e( 'Please verify that the <strong>administration email</strong> for this website is still correct.' ); ?>
						<?php

						/* translators: URL to the WordPress help section about admin email. */
						$admin_email_help_url = 'https://wordpress.org/support/article/settings-general-screen/#email-address';

						/*
						 translators: accessibility text */
						// $accessibility_text = sprintf( '<span class="screen-reader-text"> %s</span>', __( '(opens in a new tab)' ) );

						printf(
							'<a href="%1$s" rel="noopener noreferrer" target="_blank">%2$s<span class="screen-reader-text"> %3$s</span></a>',
							esc_url( $admin_email_help_url ),
							esc_html__( 'Why is this important?' ),
                            esc_html__( '(opens in a new tab)', 'loginfy' )
						);

						?>
			</p>
			<p class="admin-email__details">
						<?php

						printf(
                            /* translators: %s: Admin email address. */
                            esc_html__( 'Current administration email: %s', 'loginfy' ),
							'<strong>' . esc_html( $admin_email ) . '</strong>'
						);

						?>
			</p>
			<p class="admin-email__details">
						<?php esc_html_e( 'This email may be different from your personal email address.', 'loginfy' ); ?>
			</p>

			<div class="admin-email__actions">
				<div class="admin-email__actions-primary">
						<?php

						$change_link = admin_url( 'options-general.php' );
						$change_link = add_query_arg( 'highlight', 'confirm_admin_email', $change_link );

						?>
					<a class="button button-large" href="<?php echo esc_url( $change_link ); ?>"><?php esc_html_e( 'Update', 'loginfy' ); ?></a>
					<input type="submit" name="correct-admin-email" id="correct-admin-email" class="button button-primary button-large" value="<?php esc_attr_e( 'The email is correct', 'loginfy' ); ?>" />
				</div>
						<?php if ( $remind_interval > 0 ) : ?>
					<div class="admin-email__actions-secondary">
							<?php

							$remind_me_link = wp_login_url( $redirect_to );
							$remind_me_link = add_query_arg(
								[
									'action'          => 'confirm_admin_email',
									'remind_me_later' => wp_create_nonce( 'remind_me_later_nonce' ),
								],
								$remind_me_link
							);

							?>
						<a href="<?php echo esc_url( $remind_me_link ); ?>"><?php esc_html_e( 'Remind me later', 'loginfy' ); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</form>

						<?php

						login_footer();
						break;

					case 'postpass':
						if ( ! array_key_exists( 'post_password', $_POST ) ) {
							wp_safe_redirect( wp_get_referer() );
							exit;
						}

						require_once ABSPATH . WPINC . '/class-phpass.php';
						$hasher = new PasswordHash( 8, true );

						/**
						 * Filters the life span of the post password cookie.
						 *
						 * By default, the cookie expires 10 days from creation. To turn this
						 * into a session cookie, return 0.
						 *
						 * @since 3.7.0
						 *
						 * @param int $expires The expiry time, as passed to setcookie().
						 */
						$expire  = apply_filters( 'post_password_expires', time() + 10 * DAY_IN_SECONDS );
						$referer = wp_get_referer();

						if ( $referer ) {
							$secure = ( 'https' === parse_url( $referer, PHP_URL_SCHEME ) );
						} else {
							$secure = false;
						}

						setcookie( 'wp-postpass_' . COOKIEHASH, $hasher->HashPassword( wp_unslash( $_POST['post_password'] ) ), $expire, COOKIEPATH, COOKIE_DOMAIN, $secure );

						wp_safe_redirect( wp_get_referer() );
						exit();

					case 'logout':
						check_admin_referer( 'log-out' );

						$user = wp_get_current_user();

						wp_logout();

						if ( ! empty( esc_url_raw( wp_unslash( $_REQUEST['redirect_to'] ) ) ) ) {
							$redirect_to           = esc_url_raw( esc_url_raw( wp_unslash( $_REQUEST['redirect_to'] ) ) );
							$requested_redirect_to = $redirect_to;
						} else {
							$redirect_to = add_query_arg(
								[
									'loggedout' => 'true',
									'wp_lang'   => get_user_locale( $user ),
								],
								wp_login_url()
							);

							$requested_redirect_to = '';
						}

						/**
						 * Filters the log out redirect URL.
						 *
						 * @since 4.2.0
						 *
						 * @param string  $redirect_to           The redirect destination URL.
						 * @param string  $requested_redirect_to The requested redirect destination URL passed as a parameter.
						 * @param WP_User $user                  The WP_User object for the user that's logging out.
						 */
						$redirect_to = apply_filters( 'logout_redirect', $redirect_to, $requested_redirect_to, $user );

						wp_safe_redirect( $redirect_to );
						exit();

					case 'lostpassword':
					case 'retrievepassword':
						if ( $http_post ) {
							$errors = jlt_loginfy_retrieve_password();

							if ( ! is_wp_error( $errors ) ) {
								$redirect_to = ! empty( esc_url_raw( wp_unslash( $_REQUEST['redirect_to'] ) ) ) ? esc_url_raw( esc_url_raw( wp_unslash( $_REQUEST['redirect_to'] ) ) ) : 'wp-login.php?checkemail=confirm';
								wp_safe_redirect( $redirect_to );
								exit();
							}
						}

						if ( isset( $_GET['error'] ) ) {
							if ( 'invalidkey' === $_GET['error'] ) {
								$errors->add( 'invalidkey', __( 'Your password reset link appears to be invalid. Please request a new link below.', 'loginfy' ) );
							} elseif ( 'expiredkey' === $_GET['error'] ) {
								$errors->add( 'expiredkey', __( 'Your password reset link has expired. Please request a new link below.', 'loginfy' ) );
							}
						}

						$lostpassword_redirect = ! empty( esc_url_raw( wp_unslash( $_REQUEST['redirect_to'] ) ) ) ? esc_url_raw( wp_unslash( $_REQUEST['redirect_to'] ) ) : '';
						/**
						 * Filters the URL redirected to after submitting the lostpassword/retrievepassword form.
						 *
						 * @since 3.0.0
						 *
						 * @param string $lostpassword_redirect The redirect destination URL.
						 */
						$redirect_to = apply_filters( 'lostpassword_redirect', $lostpassword_redirect );

						/**
						 * Fires before the lost password form.
						 *
						 * @since 1.5.1
						 * @since 5.1.0 Added the `$errors` parameter.
						 *
						 * @param WP_Error $errors A `WP_Error` object containing any errors generated by using invalid
						 *                         credentials. Note that the error object may not contain any errors.
						 */
						do_action( 'lost_password', $errors );

						login_header( __( 'Lost Password', 'loginfy' ), '<p class="message">' . __( 'Please enter your username or email address. You will receive a link to create a new password via email.', 'loginfy' ) . '</p>', $errors );

						$user_login = '';

						if ( isset( $_POST['user_login'] ) && is_string( $_POST['user_login'] ) ) {
							$user_login = sanitize_user(wp_unslash( $_POST['user_login'] ));
						}

						?>

		<form name="lostpasswordform" id="lostpasswordform" action="<?php echo esc_url( network_site_url( 'wp-login.php?action=lostpassword', 'login_post' ) ); ?>" method="post">
			<p>
				<label for="user_login"><?php esc_html_e( 'Username or Email Address', 'loginfy' ); ?></label>
				<input type="text" name="user_login" id="user_login" class="input" value="<?php echo esc_attr( $user_login ); ?>" size="20" autocapitalize="off" />
			</p>
						<?php

						/**
						 * Fires inside the lostpassword form tags, before the hidden fields.
						 *
						 * @since 2.1.0
						 */
						do_action( 'lostpassword_form' );

						?>
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>" />
			<p class="submit">
				<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Get New Password', 'loginfy' ); ?>" />
			</p>
		</form>

		<p id="nav">
			<a href="<?php echo esc_url( wp_login_url() ); ?>"><?php esc_html_e( 'Log in', 'loginfy' ); ?></a>
						<?php

						if ( get_option( 'users_can_register' ) ) {
							$registration_url = sprintf( __( '<a href="%1$s">%2$s</a>', 'loginfy' ), esc_url( wp_registration_url() ), __( 'Register', 'loginfy' ) );

							echo esc_html( $login_link_separator );

							/** This filter is documented in wp-includes/general-template.php */
							echo esc_url( apply_filters( 'register', $registration_url ) );
						}

						?>
		</p>
						<?php

						login_footer( 'user_login' );
						break;

					case 'resetpass':
					case 'rp':
						list($rp_path) = !empty($_SERVER['REQUEST_URI'] ) ?  explode( '?', wp_unslash( esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) ) : '';
						$rp_cookie     = 'wp-resetpass-' . COOKIEHASH;

						if ( isset( $_GET['key'] ) ) {
							$value = sprintf( '%s:%s', sanitize_user( wp_unslash( $_GET['login'] ) ), sanitize_key( wp_unslash( $_GET['key'] ) ) );
							setcookie( $rp_cookie, $value, 0, $rp_path, COOKIE_DOMAIN, is_ssl(), true );

							wp_safe_redirect( remove_query_arg( [ 'key', 'login' ] ) );
							exit;
						}

						if ( !empty( $_COOKIE[ $rp_cookie ] ) && 0 < strpos(sanitize_text_field(wp_unslash($_COOKIE[ $rp_cookie ])), ':' ) ) {
							list($rp_login, $rp_key) = explode( ':',sanitize_text_field( wp_unslash( $_COOKIE[ $rp_cookie ] ) ), 2 );

							$user = check_password_reset_key( $rp_key, $rp_login );

							if ( isset( $_POST['pass1'] ) && ( !empty($_POST['rp_key']) && hash_equals( $rp_key, sanitize_text_field( wp_unslash( $_POST['rp_key'] ) ) ) ) ) {
								$user = false;
							}
						} else {
							$user = false;
						}

						if ( ! $user || is_wp_error( $user ) ) {
							setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true );

							if ( $user && $user->get_error_code() === 'expired_key' ) {
								wp_redirect( site_url( 'wp-login.php?action=lostpassword&error=expiredkey' ) );
							} else {
								wp_redirect( site_url( 'wp-login.php?action=lostpassword&error=invalidkey' ) );
							}

							exit;
						}

						$errors = new WP_Error();

						if ( isset( $_POST['pass1'] ) && $_POST['pass1'] !== $_POST['pass2'] ) {
							$errors->add( 'password_reset_mismatch', __( 'The passwords do not match.', 'loginfy' ) );
						}

						/**
						 * Fires before the password reset procedure is validated.
						 *
						 * @since 3.5.0
						 *
						 * @param object           $errors WP Error object.
						 * @param WP_User|WP_Error $user   WP_User object if the login and reset key match. WP_Error object otherwise.
						 */
						do_action( 'validate_password_reset', $errors, $user );

						if ( ( ! $errors->has_errors() ) && isset( $_POST['pass1'] ) && ! empty( $_POST['pass1'] ) ) {
							reset_password( $user, sanitize_text_field( wp_unslash( $_POST['pass1'] ) ) );
							setcookie( $rp_cookie, ' ', time() - YEAR_IN_SECONDS, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
							login_header( __( 'Password Reset', 'loginfy' ), '<p class="message reset-pass">' . __( 'Your password has been reset.', 'loginfy' ) . ' <a href="' . esc_url( wp_login_url() ) . '">' . __( 'Log in', 'loginfy' ) . '</a></p>' );
							login_footer();
							exit;
						}

						wp_enqueue_script( 'utils' );
						wp_enqueue_script( 'user-profile' );

						login_header( __( 'Reset Password', 'loginfy' ), '<p class="message reset-pass">' . __( 'Enter your new password below.', 'loginfy' ) . '</p>', $errors );

						?>
		<form name="resetpassform" id="resetpassform" action="<?php echo esc_url( network_site_url( 'wp-login.php?action=resetpass', 'login_post' ) ); ?>" method="post" autocomplete="off">
			<input type="hidden" id="user_login" value="<?php echo esc_attr( $rp_login ); ?>" autocomplete="off" />

			<div class="user-pass1-wrap">
				<p>
					<label for="pass1"><?php esc_html_e( 'New password', 'loginfy' ); ?></label>
				</p>

				<div class="wp-pwd">
					<input type="password" data-reveal="1" data-pw="<?php echo esc_attr( wp_generate_password( 16 ) ); ?>" name="pass1" id="pass1" class="input password-input" size="24" value="" autocomplete="off" aria-describedby="pass-strength-result" />

					<button type="button" class="button button-secondary wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e( 'Hide password', 'loginfy' ); ?>">
						<span class="dashicons dashicons-hidden" aria-hidden="true"></span>
					</button>
					<div id="pass-strength-result" class="hide-if-no-js" aria-live="polite"><?php esc_html_e( 'Strength indicator', 'loginfy' ); ?></div>
				</div>
				<div class="pw-weak">
					<input type="checkbox" name="pw_weak" id="pw-weak" class="pw-checkbox" />
					<label for="pw-weak"><?php esc_html_e( 'Confirm use of weak password', 'loginfy' ); ?></label>
				</div>
			</div>

			<p class="user-pass2-wrap">
				<label for="pass2"><?php esc_html_e( 'Confirm new password', 'loginfy' ); ?></label>
				<input type="password" name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off" />
			</p>

			<p class="description indicator-hint">
						<?php echo wp_kses_post( wp_get_password_hint() ); ?>
			</p>
			<br class="clear" />

						<?php

						/**
						 * Fires following the 'Strength indicator' meter in the user password reset form.
						 *
						 * @since 3.9.0
						 *
						 * @param WP_User $user User object of the user whose password is being reset.
						 */
						do_action( 'resetpass_form', $user );

						?>
			<input type="hidden" name="rp_key" value="<?php echo esc_attr( $rp_key ); ?>" />
			<p class="submit">
				<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Reset Password', 'loginfy' ); ?>" />
			</p>
		</form>

		<p id="nav">
			<a href="<?php echo esc_url( wp_login_url() ); ?>"><?php esc_html_e( 'Log in', 'loginfy' ); ?></a>
						<?php

						if ( get_option( 'users_can_register' ) ) {
							$registration_url = sprintf( __( '<a href="%1$s">%2$s</a>', 'loginfy' ), esc_url( wp_registration_url() ), __( 'Register', 'loginfy' ) );

							echo esc_html( $login_link_separator );

							/** This filter is documented in wp-includes/general-template.php */
							echo esc_url( apply_filters( 'register', $registration_url ) );
						}

						?>
		</p>
						<?php

						login_footer( 'user_pass' );
						break;

					case 'register':
						if ( is_multisite() ) {
							/**
							 * Filters the Multisite sign up URL.
							 *
							 * @since 3.0.0
							 *
							 * @param string $sign_up_url The sign up URL.
							 */
							wp_redirect( apply_filters( 'wp_signup_location', network_site_url( 'wp-signup.php' ) ) );
							exit;
						}

						if ( ! get_option( 'users_can_register' ) ) {
							wp_redirect( site_url( 'wp-login.php?registration=disabled' ) );
							exit();
						}

						$user_login = '';
						$user_email = '';

						if ( $http_post ) {
							if ( isset( $_POST['user_login'] ) && is_string( $_POST['user_login'] ) ) {
								$user_login = sanitize_text_field( wp_unslash( $_POST['user_login'] ) );
							}

							if ( isset( $_POST['user_email'] ) && is_string( $_POST['user_email'] ) ) {
								$user_email = sanitize_email( wp_unslash( $_POST['user_email'] ) );
							}

							$errors = register_new_user( $user_login, $user_email );

							if ( ! is_wp_error( $errors ) ) {
								$redirect_to = ! empty( $_POST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ) : 'wp-login.php?checkemail=registered';
								wp_safe_redirect( $redirect_to );
								exit();
							}
						}

						$registration_redirect = ! empty( esc_url_raw( wp_unslash( $_REQUEST['redirect_to'] ) ) ) ? esc_url_raw( esc_url_raw( wp_unslash( $_REQUEST['redirect_to'] ) ) ) : '';

						/**
						 * Filters the registration redirect URL.
						 *
						 * @since 3.0.0
						 *
						 * @param string $registration_redirect The redirect destination URL.
						 */
						$redirect_to = apply_filters( 'registration_redirect', $registration_redirect );

						login_header( __( 'Registration Form', 'loginfy' ), '<p class="message register">' . __( 'Register For This Site', 'loginfy' ) . '</p>', $errors );

						?>
		<form name="registerform" id="registerform" action="<?php echo esc_url( site_url( 'wp-login.php?action=register', 'login_post' ) ); ?>" method="post" novalidate="novalidate">
			<p>
				<label for="user_login"><?php esc_html_e( 'Username', 'loginfy' ); ?></label>
				<input type="text" name="user_login" id="user_login" class="input" value="<?php echo esc_attr( wp_unslash( $user_login ) ); ?>" size="20" autocapitalize="off" />
			</p>
			<p>
				<label for="user_email"><?php esc_html_e( 'Email', 'loginfy' ); ?></label>
				<input type="email" name="user_email" id="user_email" class="input" value="<?php echo esc_attr( wp_unslash( $user_email ) ); ?>" size="25" />
			</p>
						<?php

						/**
						 * Fires following the 'Email' field in the user registration form.
						 *
						 * @since 2.1.0
						 */
						do_action( 'register_form' );

						?>
			<p id="reg_passmail">
						<?php esc_html_e( 'Registration confirmation will be emailed to you.', 'loginfy' ); ?>
			</p>
			<br class="clear" />
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>" />
			<p class="submit">
				<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Register', 'loginfy' ); ?>" />
			</p>
		</form>

		<p id="nav">
			<a href="<?php echo esc_url( wp_login_url() ); ?>"><?php esc_html_e( 'Log in', 'loginfy', 'loginfy' ); ?></a>
						<?php echo esc_html( $login_link_separator ); ?>
			<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'loginfy' ); ?></a>
		</p>
						<?php

						login_footer( 'user_login' );
						break;

					case 'confirmaction':
						if ( ! isset( $_GET['request_id'] ) ) {
							wp_die( esc_html__( 'Missing request ID.', 'loginfy' ) );
						}

						if ( ! isset( $_GET['confirm_key'] ) ) {
							wp_die( esc_html__( 'Missing confirm key.', 'loginfy' ) );
						}

						$request_id = (int) $_GET['request_id'];
						$key        = sanitize_text_field( wp_unslash( $_GET['confirm_key'] ) );
						$result     = wp_validate_user_request_key( $request_id, $key );

						if ( is_wp_error( $result ) ) {
							wp_die( wp_kses_post( $result ) );
						}

						/**
						 * Fires an action hook when the account action has been confirmed by the user.
						 *
						 * Using this you can assume the user has agreed to perform the action by
						 * clicking on the link in the confirmation email.
						 *
						 * After firing this action hook the page will redirect to wp-login a callback
						 * redirects or exits first.
						 *
						 * @since 4.9.6
						 *
						 * @param int $request_id Request ID.
						 */
						do_action( 'user_request_action_confirmed', $request_id );

						$message = _wp_privacy_account_request_confirmed_message( $request_id );

						login_header( __( 'User action confirmed.', 'loginfy' ), $message );
						login_footer();
						exit;

					case 'login':
					default:
						$secure_cookie   = '';
						$customize_login = isset( $_REQUEST['customize-login'] );

						if ( $customize_login ) {
							wp_enqueue_script( 'customize-base' );
						}

						login_header( __( 'Log In', 'loginfy' ), '', $errors );

						$rememberme = ! empty( $_POST['rememberme'] );

						$aria_describedby_error = '';
						?>

		<form name="loginform" id="loginform" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
			<p>
				<label for="user_login"><?php esc_html_e( 'Username or Email Address', 'loginfy' ); ?></label>
				<input type="text" name="log" id="user_login" <?php echo esc_attr( $aria_describedby_error ); ?>
					class="input" value="<?php echo esc_attr( $user_login ); ?>" size="20" autocapitalize="off" />
			</p>

			<div class="user-pass-wrap">
				<label for="user_pass"><?php esc_html_e( 'Password', 'loginfy' ); ?></label>
				<div class="wp-pwd">
					<input type="password" name="pwd" id="user_pass" <?php echo esc_attr( $aria_describedby_error ); ?> class="input password-input" value="" size="20" />
					<button type="button" class="button button-secondary wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e( 'Show password', 'loginfy' ); ?>">
						<span class="dashicons dashicons-visibility" aria-hidden="true"></span>
					</button>
				</div>
			</div>
						<?php

						/**
						 * Fires following the 'Password' field in the login form.
						 *
						 * @since 2.1.0
						 */
						do_action( 'login_form' );

						?>
			<p class="forgetmenot"><input name="rememberme" type="checkbox" id="rememberme" value="forever" <?php checked( $rememberme ); ?> /> <label for="rememberme"><?php esc_html_e( 'Remember Me', 'loginfy' ); ?></label></p>
			<p class="submit">
				<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Log In', 'loginfy' ); ?>" />
						<?php

						if ( $interim_login ) {
							?>
					<input type="hidden" name="interim-login" value="1" />
							<?php
						} else {
							?>
					<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>" />
							<?php
						}

						if ( $customize_login ) {
							?>
					<input type="hidden" name="customize-login" value="1" />
							<?php
						}

						?>
				<input type="hidden" name="testcookie" value="1" />
			</p>
		</form>

						<?php

						if ( ! $interim_login ) {
							?>
			<p id="nav">
							<?php

							if ( ! isset( $_GET['checkemail'] ) || ! in_array( $_GET['checkemail'], [ 'confirm', 'newpass' ], true ) ) {
								if ( get_option( 'users_can_register' ) ) {
									$registration_url = sprintf( '<a href="%s">%s</a>', esc_url( wp_registration_url() ), __( 'Register', 'loginfy' ) );

									/** This filter is documented in wp-includes/general-template.php */
									echo esc_url( apply_filters( 'register', $registration_url ) );

									echo esc_html( $login_link_separator );
								}

								?>
					<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'loginfy' ); ?></a>
								<?php
							}

							?>
			</p>
							<?php
						}

						if ( $interim_login ) {
							?>
			<script type="text/javascript">
				(function() {
					try {
						var i, links = document.getElementsByTagName('a');
						for (i in links) {
							if (links[i].href) {
								links[i].target = '_blank';
								links[i].rel = 'noreferrer noopener';
							}
						}
					} catch (er) {}
				}());
			</script>
							<?php
						}

						login_footer();
						break;
				} // End action switch.
				?>

<script>
	// To prevent error: Uncaught ReferenceError: _customizePartialRefreshExports is not defined.
	var _customizePartialRefreshExports = '';
</script>

<?php
wp_footer();
