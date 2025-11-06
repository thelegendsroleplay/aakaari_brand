<?php
/**
 * WooCommerce Login Form Override
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

do_action('woocommerce_before_customer_login_form'); ?>

<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <!-- Auth Header -->
            <div class="auth-header">
                <div class="auth-logo">
                    <?php if (has_custom_logo()) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <h1><?php bloginfo('name'); ?></h1>
                    <?php endif; ?>
                </div>
                <h2 id="auth-title">Welcome Back</h2>
                <p id="auth-subtitle">Sign in to your account to continue</p>
            </div>

            <?php if (isset($_GET['register']) && $_GET['register'] === 'success') : ?>
                <div class="woocommerce-message success-message">
                    Registration successful! Please check your email to verify your account.
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form class="woocommerce-form woocommerce-form-login login auth-form" method="post" id="wc-login-form">

                <?php do_action('woocommerce_login_form_start'); ?>

                <div class="form-group">
                    <label for="username"><?php esc_html_e('Username or email address', 'woocommerce'); ?> <span class="required">*</span></label>
                    <input type="text" class="form-input woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required />
                </div>

                <div class="form-group">
                    <label for="password"><?php esc_html_e('Password', 'woocommerce'); ?> <span class="required">*</span></label>
                    <div class="password-field">
                        <input class="form-input woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" required />
                        <button type="button" class="password-toggle" data-target="password">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                </div>

                <?php do_action('woocommerce_login_form'); ?>

                <div class="remember-forgot">
                    <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                        <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
                        <span><?php esc_html_e('Remember me', 'woocommerce'); ?></span>
                    </label>
                    <a href="<?php echo esc_url(wp_lostpassword_url()); ?>" class="forgot-link"><?php esc_html_e('Lost your password?', 'woocommerce'); ?></a>
                </div>

                <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
                <button type="submit" class="btn-primary btn-block woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e('Log in', 'woocommerce'); ?>">
                    <?php esc_html_e('Log in', 'woocommerce'); ?>
                </button>

                <?php do_action('woocommerce_login_form_end'); ?>

            </form>

            <!-- Social Login -->
            <?php if (apply_filters('aakaari_show_social_login', true)) : ?>
                <div class="divider">
                    <span><?php esc_html_e('or continue with', 'aakaari'); ?></span>
                </div>

                <div class="social-login">
                    <?php do_action('aakaari_social_login_buttons'); ?>

                    <!-- Default social buttons if no plugins are active -->
                    <button type="button" class="social-button" data-provider="google">
                        <svg width="20" height="20" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <span><?php esc_html_e('Continue with Google', 'aakaari'); ?></span>
                    </button>

                    <button type="button" class="social-button" data-provider="facebook">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="#1877F2">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        <span><?php esc_html_e('Continue with Facebook', 'aakaari'); ?></span>
                    </button>
                </div>
            <?php endif; ?>

            <!-- Auth Footer - Link to Registration -->
            <?php if (get_option('woocommerce_enable_myaccount_registration') === 'yes') : ?>
                <div class="auth-footer">
                    <p>
                        <?php esc_html_e("Don't have an account?", 'woocommerce'); ?>
                        <a href="<?php echo esc_url(add_query_arg('action', 'register', wc_get_page_permalink('myaccount'))); ?>" class="auth-link">
                            <?php esc_html_e('Sign up', 'woocommerce'); ?>
                        </a>
                    </p>
                </div>
            <?php endif; ?>

            <!-- Security Features -->
            <div class="security-features">
                <div class="security-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <span><?php esc_html_e('Secure & Encrypted', 'aakaari'); ?></span>
                </div>
                <div class="security-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                    <span><?php esc_html_e('Privacy Protected', 'aakaari'); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

do_action('woocommerce_after_customer_login_form');
