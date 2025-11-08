<?php
/**
 * Register Form - Aakaari Custom Design
 * Template Override: woocommerce/myaccount/form-register.php
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>

<div class="auth-card-inner">
    <div class="auth-header">
        <h2><?php esc_html_e( 'Create Account', 'woocommerce' ); ?></h2>
        <p><?php esc_html_e( 'Sign up to start shopping', 'woocommerce' ); ?></p>
    </div>

    <form method="post" class="woocommerce-form woocommerce-form-register register auth-form" <?php do_action( 'woocommerce_register_form_tag' ); ?>>

        <?php do_action( 'woocommerce_register_form_start' ); ?>

        <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

            <div class="form-field">
                <label for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?> <span class="required">*</span></label>
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" placeholder="<?php esc_attr_e( 'johndoe', 'woocommerce' ); ?>" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required />
            </div>

        <?php endif; ?>

        <div class="form-field">
            <label for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?> <span class="required">*</span></label>
            <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" placeholder="<?php esc_attr_e( 'john@example.com', 'woocommerce' ); ?>" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required />
        </div>

        <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

            <div class="form-field">
                <label for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
                <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" placeholder="<?php esc_attr_e( '••••••••', 'woocommerce' ); ?>" required />
            </div>

        <?php else : ?>

            <p class="woocommerce-info"><?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'woocommerce' ); ?></p>

        <?php endif; ?>

        <?php do_action( 'woocommerce_register_form' ); ?>

        <?php if ( get_privacy_policy_url() ) : ?>
        <div class="terms-check">
            <input type="checkbox" id="terms_agree" name="terms_agree" required />
            <label for="terms_agree">
                <?php
                printf(
                    __( 'I agree to the %s', 'woocommerce' ),
                    '<a href="' . esc_url( get_privacy_policy_url() ) . '" target="_blank">' . __( 'Terms of Service and Privacy Policy', 'woocommerce' ) . '</a>'
                );
                ?>
            </label>
        </div>
        <?php endif; ?>

        <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit btn-primary btn-full" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>">
            <?php esc_html_e( 'Create Account', 'woocommerce' ); ?>
        </button>

        <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>

        <?php do_action( 'woocommerce_register_form_end' ); ?>

    </form>

    <div class="auth-footer">
        <p><?php esc_html_e( 'Already have an account?', 'woocommerce' ); ?> <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php esc_html_e( 'Sign in', 'woocommerce' ); ?></a></p>
    </div>
</div>
