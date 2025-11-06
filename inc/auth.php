<?php
/**
 * Authentication Functions
 * Handles login, signup, password reset, and validation
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Enqueue auth scripts and styles
 */
function aakaari_enqueue_auth_assets() {
    // Only enqueue on auth pages
    if (is_page_template('page-login.php') || is_account_page()) {
        wp_enqueue_style(
            'aakaari-auth',
            get_template_directory_uri() . '/assets/css/auth.css',
            array(),
            '1.0.0'
        );

        wp_enqueue_script(
            'aakaari-auth',
            get_template_directory_uri() . '/assets/js/auth.js',
            array('jquery'),
            '1.0.0',
            true
        );

        // Localize script with AJAX URL and nonces
        wp_localize_script('aakaari-auth', 'aakaariAuth', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'login_nonce' => wp_create_nonce('ajax-login-nonce'),
            'signup_nonce' => wp_create_nonce('ajax-signup-nonce'),
            'forgot_nonce' => wp_create_nonce('ajax-forgot-nonce'),
            'strings' => array(
                'login_success' => __('Login successful! Redirecting...', 'aakaari'),
                'signup_success' => __('Account created successfully! Redirecting...', 'aakaari'),
                'forgot_success' => __('Password reset link sent to your email.', 'aakaari'),
                'generic_error' => __('An error occurred. Please try again.', 'aakaari'),
            ),
        ));
    }
}
add_action('wp_enqueue_scripts', 'aakaari_enqueue_auth_assets');

/**
 * Handle AJAX login
 */
function aakaari_ajax_login() {
    // Verify nonce
    check_ajax_referer('ajax-login-nonce', 'security');

    // Get form data
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $remember = isset($_POST['remember']) ? true : false;

    // Validate inputs
    if (empty($email) || empty($password)) {
        wp_send_json_error(array(
            'message' => __('Please fill in all fields.', 'aakaari')
        ));
    }

    // Validate email format
    if (!is_email($email)) {
        wp_send_json_error(array(
            'message' => __('Please enter a valid email address.', 'aakaari')
        ));
    }

    // Get user by email
    $user = get_user_by('email', $email);

    if (!$user) {
        // Try username instead
        $user = get_user_by('login', $email);
    }

    if (!$user) {
        wp_send_json_error(array(
            'message' => __('Invalid email or password.', 'aakaari')
        ));
    }

    // Attempt login
    $creds = array(
        'user_login'    => $user->user_login,
        'user_password' => $password,
        'remember'      => $remember
    );

    $login = wp_signon($creds, is_ssl());

    if (is_wp_error($login)) {
        wp_send_json_error(array(
            'message' => __('Invalid email or password.', 'aakaari')
        ));
    }

    // Login successful
    wp_send_json_success(array(
        'message' => __('Login successful! Redirecting...', 'aakaari'),
        'redirect' => wc_get_page_permalink('myaccount')
    ));
}
add_action('wp_ajax_nopriv_aakaari_login', 'aakaari_ajax_login');

/**
 * Handle AJAX signup
 */
function aakaari_ajax_signup() {
    // Verify nonce
    check_ajax_referer('ajax-signup-nonce', 'security');

    // Get form data
    $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validate inputs
    $errors = array();

    if (empty($name)) {
        $errors['name'] = __('Name is required.', 'aakaari');
    }

    if (empty($email)) {
        $errors['email'] = __('Email is required.', 'aakaari');
    } elseif (!is_email($email)) {
        $errors['email'] = __('Please enter a valid email address.', 'aakaari');
    } elseif (email_exists($email)) {
        $errors['email'] = __('This email is already registered.', 'aakaari');
    }

    if (empty($password)) {
        $errors['password'] = __('Password is required.', 'aakaari');
    } else {
        // Validate password strength
        $password_validation = aakaari_validate_password($password);
        if (!$password_validation['valid']) {
            $errors['password'] = $password_validation['message'];
        }
    }

    if (!empty($errors)) {
        wp_send_json_error(array(
            'message' => __('Please correct the errors below.', 'aakaari'),
            'errors' => $errors
        ));
    }

    // Generate username from email
    $username = sanitize_user(current(explode('@', $email)));

    // Make username unique if it exists
    if (username_exists($username)) {
        $username = $username . '_' . wp_generate_password(4, false);
    }

    // Create user
    $user_id = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        wp_send_json_error(array(
            'message' => $user_id->get_error_message()
        ));
    }

    // Update user meta
    wp_update_user(array(
        'ID' => $user_id,
        'display_name' => $name,
        'first_name' => explode(' ', $name)[0],
        'last_name' => isset(explode(' ', $name)[1]) ? explode(' ', $name)[1] : '',
    ));

    // Set user role to customer
    $user = new WP_User($user_id);
    $user->set_role('customer');

    // Send new user notification
    wp_new_user_notification($user_id, null, 'both');

    // Auto login after registration
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id, true);

    // WooCommerce customer creation
    if (class_exists('WooCommerce')) {
        update_user_meta($user_id, 'billing_email', $email);
    }

    wp_send_json_success(array(
        'message' => __('Account created successfully! Redirecting...', 'aakaari'),
        'redirect' => wc_get_page_permalink('myaccount')
    ));
}
add_action('wp_ajax_nopriv_aakaari_signup', 'aakaari_ajax_signup');

/**
 * Handle AJAX forgot password
 */
function aakaari_ajax_forgot_password() {
    // Verify nonce
    check_ajax_referer('ajax-forgot-nonce', 'security');

    // Get email
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';

    // Validate email
    if (empty($email)) {
        wp_send_json_error(array(
            'message' => __('Please enter your email address.', 'aakaari')
        ));
    }

    if (!is_email($email)) {
        wp_send_json_error(array(
            'message' => __('Please enter a valid email address.', 'aakaari')
        ));
    }

    // Check if user exists
    $user = get_user_by('email', $email);

    if (!$user) {
        // Don't reveal if email exists or not for security
        wp_send_json_success(array(
            'message' => __('If an account exists with this email, you will receive a password reset link.', 'aakaari')
        ));
    }

    // Generate reset key
    $reset_key = get_password_reset_key($user);

    if (is_wp_error($reset_key)) {
        wp_send_json_error(array(
            'message' => __('Unable to generate reset link. Please try again.', 'aakaari')
        ));
    }

    // Send reset email
    $reset_url = add_query_arg(
        array(
            'key' => $reset_key,
            'id' => $user->ID,
        ),
        wc_get_endpoint_url('lost-password', '', wc_get_page_permalink('myaccount'))
    );

    // Email subject
    $subject = sprintf(__('[%s] Password Reset', 'aakaari'), get_bloginfo('name'));

    // Email message
    $message = sprintf(
        __("Hi %s,\n\nYou requested a password reset for your account.\n\nClick the link below to reset your password:\n\n%s\n\nIf you didn't request this, please ignore this email.\n\nThanks,\n%s Team", 'aakaari'),
        $user->display_name,
        $reset_url,
        get_bloginfo('name')
    );

    // Send email
    $sent = wp_mail($email, $subject, $message);

    if (!$sent) {
        wp_send_json_error(array(
            'message' => __('Unable to send reset email. Please try again.', 'aakaari')
        ));
    }

    wp_send_json_success(array(
        'message' => __('Password reset link has been sent to your email.', 'aakaari')
    ));
}
add_action('wp_ajax_nopriv_aakaari_forgot_password', 'aakaari_ajax_forgot_password');

/**
 * Validate password strength
 *
 * @param string $password
 * @return array
 */
function aakaari_validate_password($password) {
    $result = array(
        'valid' => true,
        'message' => ''
    );

    // Minimum length check
    if (strlen($password) < 8) {
        $result['valid'] = false;
        $result['message'] = __('Password must be at least 8 characters long.', 'aakaari');
        return $result;
    }

    // Check for uppercase letter
    if (!preg_match('/[A-Z]/', $password)) {
        $result['valid'] = false;
        $result['message'] = __('Password must contain at least one uppercase letter.', 'aakaari');
        return $result;
    }

    // Check for lowercase letter
    if (!preg_match('/[a-z]/', $password)) {
        $result['valid'] = false;
        $result['message'] = __('Password must contain at least one lowercase letter.', 'aakaari');
        return $result;
    }

    // Check for number
    if (!preg_match('/[0-9]/', $password)) {
        $result['valid'] = false;
        $result['message'] = __('Password must contain at least one number.', 'aakaari');
        return $result;
    }

    return $result;
}

/**
 * Customize WooCommerce registration validation
 */
function aakaari_woocommerce_registration_validation($errors, $username, $email) {
    // Additional validation if needed
    return $errors;
}
add_filter('woocommerce_registration_errors', 'aakaari_woocommerce_registration_validation', 10, 3);

/**
 * Redirect after login
 */
function aakaari_login_redirect($redirect, $user) {
    // Redirect to my account page
    return wc_get_page_permalink('myaccount');
}
add_filter('woocommerce_login_redirect', 'aakaari_login_redirect', 10, 2);

/**
 * Redirect after registration
 */
function aakaari_registration_redirect($redirect) {
    // Redirect to my account page
    return wc_get_page_permalink('myaccount');
}
add_filter('woocommerce_registration_redirect', 'aakaari_registration_redirect');

/**
 * Add social login action hook
 * This allows social login plugins to hook in
 */
function aakaari_render_social_login_buttons() {
    // Placeholder for social login plugins
    // Plugins can hook into this action to add their buttons
    do_action('aakaari_social_login_buttons_render');
}
add_action('aakaari_social_login_buttons', 'aakaari_render_social_login_buttons');
