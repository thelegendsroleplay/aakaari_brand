<?php
/**
 * Register Form - Figma Design
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class('auth-body'); ?>>

<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Welcome</h1>
                <p>Sign in to your account or create a new one</p>
            </div>

            <div class="auth-tabs">
                <div class="tabs-list">
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="tab-trigger">Login</a>
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) . '?action=register' ); ?>" class="tab-trigger active">Register</a>
                </div>

                <div class="tab-content active">
                    <form method="post" class="woocommerce-form woocommerce-form-register register auth-form" <?php do_action( 'woocommerce_register_form_tag' ); ?>>

                        <?php do_action( 'woocommerce_register_form_start' ); ?>

                        <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

                            <div class="form-field">
                                <label for="reg_username">Username <span class="required">*</span></label>
                                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" placeholder="johndoe" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required />
                            </div>

                        <?php endif; ?>

                        <div class="form-field">
                            <label for="reg_email">Email <span class="required">*</span></label>
                            <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" placeholder="john@example.com" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required />
                        </div>

                        <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

                            <div class="form-field">
                                <label for="reg_password">Password <span class="required">*</span></label>
                                <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" placeholder="••••••••" required />
                            </div>

                        <?php else : ?>

                            <p><?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'woocommerce' ); ?></p>

                        <?php endif; ?>

                        <?php do_action( 'woocommerce_register_form' ); ?>

                        <div class="terms-check">
                            <input type="checkbox" id="terms" required />
                            <label for="terms">
                                I agree to the <a href="<?php echo esc_url( get_privacy_policy_url() ); ?>">Terms of Service and Privacy Policy</a>
                            </label>
                        </div>

                        <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit btn-primary btn-full" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>">
                            <?php esc_html_e( 'Create Account', 'woocommerce' ); ?>
                        </button>

                        <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>

                        <?php do_action( 'woocommerce_register_form_end' ); ?>

                    </form>
                </div>
            </div>

            <div class="social-login">
                <div class="divider">
                    <span>Or continue with</span>
                </div>
                <div class="social-buttons">
                    <button class="social-btn" type="button">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z"/>
                        </svg>
                        Google
                    </button>
                    <button class="social-btn" type="button">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M24,12.073c0,5.989-4.394,10.954-10.13,11.855v-8.363h2.789l0.531-3.46H13.87V9.86c0-0.947,0.464-1.869,1.95-1.869h1.509V5.045c0,0-1.37-0.234-2.679-0.234c-2.734,0-4.52,1.657-4.52,4.656v2.637H7.091v3.46h3.039v8.363C4.395,23.025,0,18.061,0,12.073c0-6.627,5.373-12,12-12S24,5.445,24,12.073z"/>
                        </svg>
                        Facebook
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
