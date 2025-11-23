<?php
/**
 * Social Login Handler
 * Handles Google and Facebook OAuth authentication
 *
 * @package Aakaari_Brand
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Social Login Class
 */
class Aakaari_Social_Login {

    /**
     * Constructor
     */
    public function __construct() {
        // Register AJAX handlers for non-logged-in users
        add_action('wp_ajax_nopriv_google_login', array($this, 'handle_google_login'));
        add_action('wp_ajax_nopriv_facebook_login', array($this, 'handle_facebook_login'));

        // Register OAuth callback handlers
        add_action('init', array($this, 'handle_oauth_callback'));

        // Add settings to admin
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * Get Google Client ID
     */
    private function get_google_client_id() {
        return get_option('aakaari_google_client_id', '');
    }

    /**
     * Get Google Client Secret
     */
    private function get_google_client_secret() {
        return get_option('aakaari_google_client_secret', '');
    }

    /**
     * Get Facebook App ID
     */
    private function get_facebook_app_id() {
        return get_option('aakaari_facebook_app_id', '');
    }

    /**
     * Get Facebook App Secret
     */
    private function get_facebook_app_secret() {
        return get_option('aakaari_facebook_app_secret', '');
    }

    /**
     * Check if Google login is enabled
     */
    public function is_google_enabled() {
        return !empty($this->get_google_client_id()) && !empty($this->get_google_client_secret());
    }

    /**
     * Check if Facebook login is enabled
     */
    public function is_facebook_enabled() {
        return !empty($this->get_facebook_app_id()) && !empty($this->get_facebook_app_secret());
    }

    /**
     * Get Google OAuth URL
     */
    public function get_google_auth_url() {
        if (!$this->is_google_enabled()) {
            return '#';
        }

        $redirect_uri = home_url('/oauth-callback/google/');
        $client_id = $this->get_google_client_id();

        $params = array(
            'client_id' => $client_id,
            'redirect_uri' => $redirect_uri,
            'response_type' => 'code',
            'scope' => 'email profile',
            'state' => wp_create_nonce('google_oauth_state'),
            'access_type' => 'online',
        );

        return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
    }

    /**
     * Get Facebook OAuth URL
     */
    public function get_facebook_auth_url() {
        if (!$this->is_facebook_enabled()) {
            return '#';
        }

        $redirect_uri = home_url('/oauth-callback/facebook/');
        $app_id = $this->get_facebook_app_id();

        $params = array(
            'client_id' => $app_id,
            'redirect_uri' => $redirect_uri,
            'response_type' => 'code',
            'scope' => 'email,public_profile',
            'state' => wp_create_nonce('facebook_oauth_state'),
        );

        return 'https://www.facebook.com/v18.0/dialog/oauth?' . http_build_query($params);
    }

    /**
     * Handle OAuth callback
     */
    public function handle_oauth_callback() {
        // Check if this is an OAuth callback
        $request_uri = $_SERVER['REQUEST_URI'];

        if (strpos($request_uri, '/oauth-callback/google/') !== false) {
            $this->handle_google_callback();
        } elseif (strpos($request_uri, '/oauth-callback/facebook/') !== false) {
            $this->handle_facebook_callback();
        }
    }

    /**
     * Handle Google OAuth callback
     */
    private function handle_google_callback() {
        // Check for authorization code
        if (!isset($_GET['code'])) {
            wp_redirect(wc_get_page_permalink('myaccount'));
            exit;
        }

        // Verify state nonce
        if (!isset($_GET['state']) || !wp_verify_nonce($_GET['state'], 'google_oauth_state')) {
            wc_add_notice(__('Security verification failed. Please try again.', 'aakaari-brand'), 'error');
            wp_redirect(wc_get_page_permalink('myaccount'));
            exit;
        }

        $code = sanitize_text_field($_GET['code']);

        // Exchange code for access token
        $token_response = wp_remote_post('https://oauth2.googleapis.com/token', array(
            'body' => array(
                'code' => $code,
                'client_id' => $this->get_google_client_id(),
                'client_secret' => $this->get_google_client_secret(),
                'redirect_uri' => home_url('/oauth-callback/google/'),
                'grant_type' => 'authorization_code',
            ),
        ));

        if (is_wp_error($token_response)) {
            wc_add_notice(__('Failed to connect with Google. Please try again.', 'aakaari-brand'), 'error');
            wp_redirect(wc_get_page_permalink('myaccount'));
            exit;
        }

        $token_data = json_decode(wp_remote_retrieve_body($token_response), true);

        if (!isset($token_data['access_token'])) {
            wc_add_notice(__('Failed to authenticate with Google. Please try again.', 'aakaari-brand'), 'error');
            wp_redirect(wc_get_page_permalink('myaccount'));
            exit;
        }

        // Get user info from Google
        $user_response = wp_remote_get('https://www.googleapis.com/oauth2/v2/userinfo', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $token_data['access_token'],
            ),
        ));

        if (is_wp_error($user_response)) {
            wc_add_notice(__('Failed to get user information from Google.', 'aakaari-brand'), 'error');
            wp_redirect(wc_get_page_permalink('myaccount'));
            exit;
        }

        $user_data = json_decode(wp_remote_retrieve_body($user_response), true);

        // Create or login user
        $this->process_social_login($user_data, 'google');
    }

    /**
     * Handle Facebook OAuth callback
     */
    private function handle_facebook_callback() {
        // Check for authorization code
        if (!isset($_GET['code'])) {
            wp_redirect(wc_get_page_permalink('myaccount'));
            exit;
        }

        // Verify state nonce
        if (!isset($_GET['state']) || !wp_verify_nonce($_GET['state'], 'facebook_oauth_state')) {
            wc_add_notice(__('Security verification failed. Please try again.', 'aakaari-brand'), 'error');
            wp_redirect(wc_get_page_permalink('myaccount'));
            exit;
        }

        $code = sanitize_text_field($_GET['code']);

        // Exchange code for access token
        $token_url = 'https://graph.facebook.com/v18.0/oauth/access_token?' . http_build_query(array(
            'client_id' => $this->get_facebook_app_id(),
            'client_secret' => $this->get_facebook_app_secret(),
            'redirect_uri' => home_url('/oauth-callback/facebook/'),
            'code' => $code,
        ));

        $token_response = wp_remote_get($token_url);

        if (is_wp_error($token_response)) {
            wc_add_notice(__('Failed to connect with Facebook. Please try again.', 'aakaari-brand'), 'error');
            wp_redirect(wc_get_page_permalink('myaccount'));
            exit;
        }

        $token_data = json_decode(wp_remote_retrieve_body($token_response), true);

        if (!isset($token_data['access_token'])) {
            wc_add_notice(__('Failed to authenticate with Facebook. Please try again.', 'aakaari-brand'), 'error');
            wp_redirect(wc_get_page_permalink('myaccount'));
            exit;
        }

        // Get user info from Facebook
        $user_url = 'https://graph.facebook.com/v18.0/me?' . http_build_query(array(
            'fields' => 'id,name,email,picture',
            'access_token' => $token_data['access_token'],
        ));

        $user_response = wp_remote_get($user_url);

        if (is_wp_error($user_response)) {
            wc_add_notice(__('Failed to get user information from Facebook.', 'aakaari-brand'), 'error');
            wp_redirect(wc_get_page_permalink('myaccount'));
            exit;
        }

        $user_data = json_decode(wp_remote_retrieve_body($user_response), true);

        // Create or login user
        $this->process_social_login($user_data, 'facebook');
    }

    /**
     * Process social login
     */
    private function process_social_login($user_data, $provider) {
        // Extract user information based on provider
        if ($provider === 'google') {
            $email = isset($user_data['email']) ? sanitize_email($user_data['email']) : '';
            $name = isset($user_data['name']) ? sanitize_text_field($user_data['name']) : '';
            $social_id = isset($user_data['id']) ? sanitize_text_field($user_data['id']) : '';
            $picture = isset($user_data['picture']) ? esc_url_raw($user_data['picture']) : '';
        } else { // facebook
            $email = isset($user_data['email']) ? sanitize_email($user_data['email']) : '';
            $name = isset($user_data['name']) ? sanitize_text_field($user_data['name']) : '';
            $social_id = isset($user_data['id']) ? sanitize_text_field($user_data['id']) : '';
            $picture = isset($user_data['picture']['data']['url']) ? esc_url_raw($user_data['picture']['data']['url']) : '';
        }

        // Validate email
        if (empty($email) || !is_email($email)) {
            wc_add_notice(__('Could not retrieve your email address. Please try a different login method.', 'aakaari-brand'), 'error');
            wp_redirect(wc_get_page_permalink('myaccount'));
            exit;
        }

        // Check if user exists by email
        $user = get_user_by('email', $email);

        if ($user) {
            // User exists, log them in
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID, true);

            // Update social login meta
            update_user_meta($user->ID, 'aakaari_' . $provider . '_id', $social_id);
            update_user_meta($user->ID, 'aakaari_social_login_provider', $provider);

            do_action('wp_login', $user->user_login, $user);

            wc_add_notice(sprintf(__('Welcome back, %s!', 'aakaari-brand'), $user->display_name), 'success');
        } else {
            // Create new user
            $username = $this->generate_username($email, $name);
            $password = wp_generate_password(20, true, true);

            $user_id = wp_create_user($username, $password, $email);

            if (is_wp_error($user_id)) {
                wc_add_notice(__('Failed to create account. Please try again or use email registration.', 'aakaari-brand'), 'error');
                wp_redirect(wc_get_page_permalink('myaccount'));
                exit;
            }

            // Update user meta
            wp_update_user(array(
                'ID' => $user_id,
                'display_name' => $name,
                'first_name' => $this->extract_first_name($name),
                'last_name' => $this->extract_last_name($name),
            ));

            // Save social login information
            update_user_meta($user_id, 'aakaari_' . $provider . '_id', $social_id);
            update_user_meta($user_id, 'aakaari_social_login_provider', $provider);
            update_user_meta($user_id, 'aakaari_social_picture', $picture);

            // Set user role to customer
            $user = new WP_User($user_id);
            $user->set_role('customer');

            // Log the user in
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id, true);

            do_action('woocommerce_created_customer', $user_id);
            do_action('wp_login', $username, $user);

            wc_add_notice(sprintf(__('Welcome, %s! Your account has been created successfully.', 'aakaari-brand'), $name), 'success');
        }

        // Redirect to my account page
        wp_redirect(wc_get_page_permalink('myaccount'));
        exit;
    }

    /**
     * Generate unique username
     */
    private function generate_username($email, $name) {
        // Try to use name first
        if (!empty($name)) {
            $username = sanitize_user(strtolower(str_replace(' ', '', $name)));
        } else {
            $username = sanitize_user(strtolower(explode('@', $email)[0]));
        }

        // Make sure username is unique
        if (username_exists($username)) {
            $i = 1;
            while (username_exists($username . $i)) {
                $i++;
            }
            $username = $username . $i;
        }

        return $username;
    }

    /**
     * Extract first name from full name
     */
    private function extract_first_name($full_name) {
        $parts = explode(' ', $full_name);
        return isset($parts[0]) ? $parts[0] : $full_name;
    }

    /**
     * Extract last name from full name
     */
    private function extract_last_name($full_name) {
        $parts = explode(' ', $full_name);
        if (count($parts) > 1) {
            array_shift($parts);
            return implode(' ', $parts);
        }
        return '';
    }

    /**
     * AJAX handler for Google login (client-side initiated)
     */
    public function handle_google_login() {
        check_ajax_referer('social_login_nonce', 'nonce');

        wp_send_json_success(array(
            'redirect_url' => $this->get_google_auth_url(),
        ));
    }

    /**
     * AJAX handler for Facebook login (client-side initiated)
     */
    public function handle_facebook_login() {
        check_ajax_referer('social_login_nonce', 'nonce');

        wp_send_json_success(array(
            'redirect_url' => $this->get_facebook_auth_url(),
        ));
    }

    /**
     * Add settings page to admin menu
     */
    public function add_settings_page() {
        add_options_page(
            __('Social Login Settings', 'aakaari-brand'),
            __('Social Login', 'aakaari-brand'),
            'manage_options',
            'aakaari-social-login',
            array($this, 'render_settings_page')
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('aakaari_social_login', 'aakaari_google_client_id');
        register_setting('aakaari_social_login', 'aakaari_google_client_secret');
        register_setting('aakaari_social_login', 'aakaari_facebook_app_id');
        register_setting('aakaari_social_login', 'aakaari_facebook_app_secret');

        // Google Settings Section
        add_settings_section(
            'aakaari_google_section',
            __('Google OAuth Settings', 'aakaari-brand'),
            array($this, 'render_google_section'),
            'aakaari-social-login'
        );

        add_settings_field(
            'aakaari_google_client_id',
            __('Google Client ID', 'aakaari-brand'),
            array($this, 'render_text_field'),
            'aakaari-social-login',
            'aakaari_google_section',
            array('field' => 'aakaari_google_client_id')
        );

        add_settings_field(
            'aakaari_google_client_secret',
            __('Google Client Secret', 'aakaari-brand'),
            array($this, 'render_text_field'),
            'aakaari-social-login',
            'aakaari_google_section',
            array('field' => 'aakaari_google_client_secret')
        );

        // Facebook Settings Section
        add_settings_section(
            'aakaari_facebook_section',
            __('Facebook OAuth Settings', 'aakaari-brand'),
            array($this, 'render_facebook_section'),
            'aakaari-social-login'
        );

        add_settings_field(
            'aakaari_facebook_app_id',
            __('Facebook App ID', 'aakaari-brand'),
            array($this, 'render_text_field'),
            'aakaari-social-login',
            'aakaari_facebook_section',
            array('field' => 'aakaari_facebook_app_id')
        );

        add_settings_field(
            'aakaari_facebook_app_secret',
            __('Facebook App Secret', 'aakaari-brand'),
            array($this, 'render_text_field'),
            'aakaari-social-login',
            'aakaari_facebook_section',
            array('field' => 'aakaari_facebook_app_secret')
        );
    }

    /**
     * Render Google section description
     */
    public function render_google_section() {
        ?>
        <p><?php _e('Configure Google OAuth credentials. Get your credentials from the <a href="https://console.cloud.google.com/" target="_blank">Google Cloud Console</a>.', 'aakaari-brand'); ?></p>
        <p><strong><?php _e('Authorized Redirect URI:', 'aakaari-brand'); ?></strong> <code><?php echo esc_url(home_url('/oauth-callback/google/')); ?></code></p>
        <?php
    }

    /**
     * Render Facebook section description
     */
    public function render_facebook_section() {
        ?>
        <p><?php _e('Configure Facebook OAuth credentials. Get your credentials from <a href="https://developers.facebook.com/" target="_blank">Facebook Developers</a>.', 'aakaari-brand'); ?></p>
        <p><strong><?php _e('Valid OAuth Redirect URI:', 'aakaari-brand'); ?></strong> <code><?php echo esc_url(home_url('/oauth-callback/facebook/')); ?></code></p>
        <?php
    }

    /**
     * Render text field
     */
    public function render_text_field($args) {
        $field = $args['field'];
        $value = get_option($field, '');
        ?>
        <input type="text" name="<?php echo esc_attr($field); ?>" value="<?php echo esc_attr($value); ?>" class="regular-text" />
        <?php
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Social Login Settings', 'aakaari-brand'); ?></h1>

            <form method="post" action="options.php">
                <?php
                settings_fields('aakaari_social_login');
                do_settings_sections('aakaari-social-login');
                submit_button();
                ?>
            </form>

            <hr>

            <h2><?php _e('Setup Instructions', 'aakaari-brand'); ?></h2>

            <h3><?php _e('Google OAuth Setup', 'aakaari-brand'); ?></h3>
            <ol>
                <li><?php _e('Go to <a href="https://console.cloud.google.com/" target="_blank">Google Cloud Console</a>', 'aakaari-brand'); ?></li>
                <li><?php _e('Create a new project or select an existing one', 'aakaari-brand'); ?></li>
                <li><?php _e('Enable Google+ API or Google Identity Services', 'aakaari-brand'); ?></li>
                <li><?php _e('Go to Credentials → Create Credentials → OAuth 2.0 Client ID', 'aakaari-brand'); ?></li>
                <li><?php _e('Select "Web application" as the application type', 'aakaari-brand'); ?></li>
                <li><?php _e('Add the following to Authorized redirect URIs:', 'aakaari-brand'); ?> <code><?php echo esc_url(home_url('/oauth-callback/google/')); ?></code></li>
                <li><?php _e('Copy the Client ID and Client Secret and paste them above', 'aakaari-brand'); ?></li>
            </ol>

            <h3><?php _e('Facebook OAuth Setup', 'aakaari-brand'); ?></h3>
            <ol>
                <li><?php _e('Go to <a href="https://developers.facebook.com/" target="_blank">Facebook Developers</a>', 'aakaari-brand'); ?></li>
                <li><?php _e('Create a new app or select an existing one', 'aakaari-brand'); ?></li>
                <li><?php _e('Add "Facebook Login" product to your app', 'aakaari-brand'); ?></li>
                <li><?php _e('Go to Facebook Login → Settings', 'aakaari-brand'); ?></li>
                <li><?php _e('Add the following to Valid OAuth Redirect URIs:', 'aakaari-brand'); ?> <code><?php echo esc_url(home_url('/oauth-callback/facebook/')); ?></code></li>
                <li><?php _e('Go to Settings → Basic to find your App ID and App Secret', 'aakaari-brand'); ?></li>
                <li><?php _e('Copy the App ID and App Secret and paste them above', 'aakaari-brand'); ?></li>
            </ol>

            <h3><?php _e('Important Notes', 'aakaari-brand'); ?></h3>
            <ul>
                <li><?php _e('Your site must use HTTPS for OAuth to work properly', 'aakaari-brand'); ?></li>
                <li><?php _e('Update your Privacy Policy to include information about social login', 'aakaari-brand'); ?></li>
                <li><?php _e('Test the login flow in a private/incognito browser window', 'aakaari-brand'); ?></li>
                <li><?php _e('Never share your Client Secret or App Secret publicly', 'aakaari-brand'); ?></li>
            </ul>
        </div>
        <?php
    }
}

// Initialize the social login class
new Aakaari_Social_Login();

/**
 * Helper function to get social login instance
 */
function aakaari_social_login() {
    static $instance = null;
    if (null === $instance) {
        $instance = new Aakaari_Social_Login();
    }
    return $instance;
}
