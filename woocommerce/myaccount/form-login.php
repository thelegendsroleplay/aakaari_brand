<?php
/**
 * Login Form - Aakaari Custom Design
 * Template Override: woocommerce/myaccount/form-login.php
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

do_action( 'woocommerce_before_customer_login_form' ); ?>

<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1><?php esc_html_e( 'Welcome Back', 'woocommerce' ); ?></h1>
                <p><?php esc_html_e( 'Sign in to your account or create a new one', 'woocommerce' ); ?></p>
            </div>

            <?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

            <div class="auth-tabs">
                <div class="tabs-list">
                    <button type="button" class="tab-trigger active" data-tab="login"><?php esc_html_e( 'Login', 'woocommerce' ); ?></button>
                    <button type="button" class="tab-trigger" data-tab="register"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
                </div>

                <!-- LOGIN TAB -->
                <div class="tab-content active" data-tab-content="login">
                    <?php if ( ! empty( $_GET['login'] ) && $_GET['login'] === 'failed' ) : ?>
                        <div class="woocommerce-error" style="margin-bottom: 1rem; padding: 1rem; background: #fee; border: 1px solid #fcc; border-radius: 0.5rem; color: #c33;">
                            <?php esc_html_e( 'Invalid email or password. Please try again.', 'woocommerce' ); ?>
                        </div>
                    <?php endif; ?>

                    <form class="woocommerce-form woocommerce-form-login login auth-form" method="post">

                        <?php do_action( 'woocommerce_login_form_start' ); ?>

                        <div class="form-field">
                            <label for="username"><?php esc_html_e( 'Username or email', 'woocommerce' ); ?> <span class="required">*</span></label>
                            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" placeholder="<?php esc_attr_e( 'john@example.com', 'woocommerce' ); ?>" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required />
                        </div>

                        <div class="form-field">
                            <label for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
                            <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" placeholder="<?php esc_attr_e( '••••••••', 'woocommerce' ); ?>" required />
                        </div>

                        <?php do_action( 'woocommerce_login_form' ); ?>

                        <div class="form-footer">
                            <label class="remember-me">
                                <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
                                <span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
                            </label>
                            <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="forgot-password"><?php esc_html_e( 'Forgot password?', 'woocommerce' ); ?></a>
                        </div>

                        <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>

                        <button type="submit" class="woocommerce-button button woocommerce-form-login__submit btn-primary btn-full" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>">
                            <?php esc_html_e( 'Sign In', 'woocommerce' ); ?>
                        </button>

                        <?php do_action( 'woocommerce_login_form_end' ); ?>

                    </form>
                </div>

                <!-- REGISTER TAB -->
                <div class="tab-content" data-tab-content="register">
                    <?php wc_get_template( 'myaccount/form-register.php' ); ?>
                </div>

            </div>

            <?php else : ?>

            <!-- LOGIN ONLY (Registration Disabled) -->
            <div class="auth-card-inner">
                <?php if ( ! empty( $_GET['login'] ) && $_GET['login'] === 'failed' ) : ?>
                    <div class="woocommerce-error" style="margin-bottom: 1rem; padding: 1rem; background: #fee; border: 1px solid #fcc; border-radius: 0.5rem; color: #c33;">
                        <?php esc_html_e( 'Invalid email or password. Please try again.', 'woocommerce' ); ?>
                    </div>
                <?php endif; ?>

                <form class="woocommerce-form woocommerce-form-login login auth-form" method="post">

                    <?php do_action( 'woocommerce_login_form_start' ); ?>

                    <div class="form-field">
                        <label for="username"><?php esc_html_e( 'Username or email', 'woocommerce' ); ?> <span class="required">*</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" placeholder="<?php esc_attr_e( 'john@example.com', 'woocommerce' ); ?>" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required />
                    </div>

                    <div class="form-field">
                        <label for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
                        <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" placeholder="<?php esc_attr_e( '••••••••', 'woocommerce' ); ?>" required />
                    </div>

                    <?php do_action( 'woocommerce_login_form' ); ?>

                    <div class="form-footer">
                        <label class="remember-me">
                            <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
                            <span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
                        </label>
                        <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="forgot-password"><?php esc_html_e( 'Forgot password?', 'woocommerce' ); ?></a>
                    </div>

                    <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>

                    <button type="submit" class="woocommerce-button button woocommerce-form-login__submit btn-primary btn-full" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>">
                        <?php esc_html_e( 'Sign In', 'woocommerce' ); ?>
                    </button>

                    <?php do_action( 'woocommerce_login_form_end' ); ?>

                </form>
            </div>

            <?php endif; ?>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabTriggers = document.querySelectorAll('.tab-trigger');
    const tabContents = document.querySelectorAll('.tab-content');

    tabTriggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');

            // Remove active class from all
            tabTriggers.forEach(t => t.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));

            // Add active to clicked
            this.classList.add('active');
            document.querySelector(`[data-tab-content="${tabName}"]`).classList.add('active');
        });
    });
});
</script>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
