<?php
/**
 * Login Form - Professional Figma Design with Tabs
 * Template Override: woocommerce/myaccount/form-login.php
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

do_action( 'woocommerce_before_customer_login_form' ); ?>

<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <!-- Header -->
            <div class="auth-header">
                <h1><?php esc_html_e( 'Welcome', 'woocommerce' ); ?></h1>
                <p><?php esc_html_e( 'Sign in to your account or create a new one', 'woocommerce' ); ?></p>
            </div>

            <!-- Tabs -->
            <div class="auth-tabs">
                <div class="tabs-list">
                    <button type="button" class="tab-trigger active" data-tab="login">
                        <?php esc_html_e( 'Login', 'woocommerce' ); ?>
                    </button>
                    <button type="button" class="tab-trigger" data-tab="register">
                        <?php esc_html_e( 'Register', 'woocommerce' ); ?>
                    </button>
                </div>

                <!-- LOGIN TAB -->
                <div class="tab-content active" data-tab-content="login">
                    <form class="woocommerce-form woocommerce-form-login login auth-form" method="post">

                        <?php do_action( 'woocommerce_login_form_start' ); ?>

                        <div class="form-field">
                            <label for="username">
                                <?php esc_html_e( 'Email', 'woocommerce' ); ?> <span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                class="woocommerce-Input woocommerce-Input--text input-text"
                                name="username"
                                id="username"
                                autocomplete="username"
                                placeholder="<?php esc_attr_e( 'john@example.com', 'woocommerce' ); ?>"
                                value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"
                                required
                            />
                        </div>

                        <div class="form-field">
                            <label for="password">
                                <?php esc_html_e( 'Password', 'woocommerce' ); ?> <span class="required">*</span>
                            </label>
                            <input
                                class="woocommerce-Input woocommerce-Input--text input-text"
                                type="password"
                                name="password"
                                id="password"
                                autocomplete="current-password"
                                placeholder="<?php esc_attr_e( '••••••••', 'woocommerce' ); ?>"
                                required
                            />
                        </div>

                        <div class="form-footer">
                            <label class="remember-me woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                                <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
                                <span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
                            </label>
                            <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="forgot-password">
                                <?php esc_html_e( 'Forgot password?', 'woocommerce' ); ?>
                            </a>
                        </div>

                        <?php do_action( 'woocommerce_login_form' ); ?>

                        <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                        <button type="submit" class="woocommerce-button button woocommerce-form-login__submit auth-submit-btn" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>">
                            <?php esc_html_e( 'Sign In', 'woocommerce' ); ?>
                        </button>

                        <?php do_action( 'woocommerce_login_form_end' ); ?>

                    </form>
                </div>

                <!-- REGISTER TAB -->
                <div class="tab-content" data-tab-content="register">
                    <form method="post" class="woocommerce-form woocommerce-form-register register auth-form" <?php do_action( 'woocommerce_register_form_tag' ); ?>>

                        <?php do_action( 'woocommerce_register_form_start' ); ?>

                        <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
                            <div class="form-field">
                                <label for="reg_username">
                                    <?php esc_html_e( 'Username', 'woocommerce' ); ?> <span class="required">*</span>
                                </label>
                                <input
                                    type="text"
                                    class="woocommerce-Input woocommerce-Input--text input-text"
                                    name="username"
                                    id="reg_username"
                                    autocomplete="username"
                                    placeholder="<?php esc_attr_e( 'johndoe', 'woocommerce' ); ?>"
                                    value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"
                                    required
                                />
                            </div>
                        <?php endif; ?>

                        <div class="form-field">
                            <label for="reg_email">
                                <?php esc_html_e( 'Email', 'woocommerce' ); ?> <span class="required">*</span>
                            </label>
                            <input
                                type="email"
                                class="woocommerce-Input woocommerce-Input--text input-text"
                                name="email"
                                id="reg_email"
                                autocomplete="email"
                                placeholder="<?php esc_attr_e( 'john@example.com', 'woocommerce' ); ?>"
                                value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>"
                                required
                            />
                        </div>

                        <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
                            <div class="form-field">
                                <label for="reg_password">
                                    <?php esc_html_e( 'Password', 'woocommerce' ); ?> <span class="required">*</span>
                                </label>
                                <input
                                    type="password"
                                    class="woocommerce-Input woocommerce-Input--text input-text"
                                    name="password"
                                    id="reg_password"
                                    autocomplete="new-password"
                                    placeholder="<?php esc_attr_e( '••••••••', 'woocommerce' ); ?>"
                                    required
                                />
                            </div>
                        <?php else : ?>
                            <p class="password-note"><?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'woocommerce' ); ?></p>
                        <?php endif; ?>

                        <?php do_action( 'woocommerce_register_form' ); ?>

                        <div class="terms-check">
                            <input type="checkbox" id="terms" name="terms" required />
                            <label for="terms">
                                <?php esc_html_e( 'I agree to the', 'woocommerce' ); ?>
                                <a href="<?php echo esc_url( get_privacy_policy_url() ); ?>" target="_blank">
                                    <?php esc_html_e( 'Terms of Service and Privacy Policy', 'woocommerce' ); ?>
                                </a>
                            </label>
                        </div>

                        <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                        <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit auth-submit-btn" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>">
                            <?php esc_html_e( 'Create Account', 'woocommerce' ); ?>
                        </button>

                        <?php do_action( 'woocommerce_register_form_end' ); ?>

                    </form>
                </div>

            </div>

            <!-- Social Login Section -->
            <?php
            // Check if social login is available
            $social_login = aakaari_social_login();
            $google_enabled = $social_login->is_google_enabled();
            $facebook_enabled = $social_login->is_facebook_enabled();

            if ($google_enabled || $facebook_enabled) :
            ?>
            <div class="social-login">
                <div class="divider">
                    <span><?php esc_html_e( 'Or continue with', 'woocommerce' ); ?></span>
                </div>
                <div class="social-buttons">
                    <?php if ($google_enabled) : ?>
                    <a href="<?php echo esc_url($social_login->get_google_auth_url()); ?>" class="social-btn google-login-btn">
                        <svg class="social-icon" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z"/>
                        </svg>
                        <?php esc_html_e( 'Google', 'woocommerce' ); ?>
                    </a>
                    <?php endif; ?>

                    <?php if ($facebook_enabled) : ?>
                    <a href="<?php echo esc_url($social_login->get_facebook_auth_url()); ?>" class="social-btn facebook-login-btn">
                        <svg class="social-icon" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M24,12.073c0,5.989-4.394,10.954-10.13,11.855v-8.363h2.789l0.531-3.46H13.87V9.86c0-0.947,0.464-1.869,1.95-1.869h1.509V5.045c0,0-1.37-0.234-2.679-0.234c-2.734,0-4.52,1.657-4.52,4.656v2.637H7.091v3.46h3.039v8.363C4.395,23.025,0,18.061,0,12.073c0-6.627,5.373-12,12-12S24,5.445,24,12.073z"/>
                        </svg>
                        <?php esc_html_e( 'Facebook', 'woocommerce' ); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<script>
// Tab switching functionality
document.addEventListener('DOMContentLoaded', function() {
    const triggers = document.querySelectorAll('.tab-trigger');
    const contents = document.querySelectorAll('.tab-content');

    triggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
            const tab = this.getAttribute('data-tab');

            // Remove active class from all triggers and contents
            triggers.forEach(t => t.classList.remove('active'));
            contents.forEach(c => c.classList.remove('active'));

            // Add active class to clicked trigger and corresponding content
            this.classList.add('active');
            document.querySelector(`[data-tab-content="${tab}"]`).classList.add('active');
        });
    });
});
</script>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
