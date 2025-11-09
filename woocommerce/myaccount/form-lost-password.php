<?php
/**
 * Lost Password Form - Figma Design
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
                <h1>Reset Password</h1>
                <p>Enter your email address and we'll send you a link to reset your password</p>
            </div>

            <?php do_action( 'woocommerce_before_lost_password_form' ); ?>

            <form method="post" class="woocommerce-ResetPassword lost_reset_password auth-form">

                <?php if ( isset( $_GET['reset'] ) && $_GET['reset'] === 'true' ) : ?>
                    <div class="woocommerce-message" style="margin-bottom: 1rem; padding: 1rem; background: #efe; border: 1px solid #cfc; border-radius: 0.5rem; color: #363;">
                        Password reset email has been sent. Please check your inbox.
                    </div>
                <?php endif; ?>

                <div class="form-field">
                    <label for="user_login">Email address <span class="required">*</span></label>
                    <input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" autocomplete="username" placeholder="john@example.com" required />
                </div>

                <?php do_action( 'woocommerce_lostpassword_form' ); ?>

                <button type="submit" class="woocommerce-Button button btn-primary btn-full" value="<?php esc_attr_e( 'Reset password', 'woocommerce' ); ?>">
                    <?php esc_html_e( 'Send Reset Link', 'woocommerce' ); ?>
                </button>

                <div style="text-align: center; margin-top: 1.5rem;">
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="forgot-password" style="font-size: 0.875rem;">
                        Back to Login
                    </a>
                </div>

                <?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>

            </form>

            <?php do_action( 'woocommerce_after_lost_password_form' ); ?>

        </div>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
