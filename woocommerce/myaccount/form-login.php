<?php
/**
 * Login Form - Figma Design
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

do_action( 'woocommerce_before_customer_login_form' ); ?>

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
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="tab-trigger active">Login</a>
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) . '?action=register' ); ?>" class="tab-trigger">Register</a>
                </div>

                <div class="tab-content active">
                    <?php if ( ! empty( $_GET['login'] ) && $_GET['login'] === 'failed' ) : ?>
                        <div class="woocommerce-error" style="margin-bottom: 1rem; padding: 1rem; background: #fee; border: 1px solid #fcc; border-radius: 0.5rem; color: #c33;">
                            Invalid email or password. Please try again.
                        </div>
                    <?php endif; ?>

                    <form class="woocommerce-form woocommerce-form-login login auth-form" method="post">

                        <?php do_action( 'woocommerce_login_form_start' ); ?>

                        <div class="form-field">
                            <label for="username">Email <span class="required">*</span></label>
                            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" placeholder="john@example.com" required />
                        </div>

                        <div class="form-field">
                            <label for="password">Password <span class="required">*</span></label>
                            <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" placeholder="••••••••" required />
                        </div>

                        <?php do_action( 'woocommerce_login_form' ); ?>

                        <div class="form-footer">
                            <label class="remember-me">
                                <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
                                <span>Remember me</span>
                            </label>
                            <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="forgot-password">Forgot password?</a>
                        </div>

                        <button type="submit" class="woocommerce-button button woocommerce-form-login__submit btn-primary btn-full" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>">
                            <?php esc_html_e( 'Sign In', 'woocommerce' ); ?>
                        </button>

                        <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>

                        <?php do_action( 'woocommerce_login_form_end' ); ?>

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

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>

<?php wp_footer(); ?>
</body>
</html>
