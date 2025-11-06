<?php
/**
 * Template Name: Login/Signup Page
 * Description: Custom login and signup page template
 */

// Redirect if user is already logged in
if (is_user_logged_in()) {
    wp_redirect(wc_get_page_permalink('myaccount'));
    exit;
}

get_header();
?>

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

            <!-- Login Form -->
            <form id="login-form" class="auth-form" method="post">
                <?php wp_nonce_field('ajax-login-nonce', 'login_security'); ?>

                <div class="form-group">
                    <label for="login_email">Email Address</label>
                    <input
                        type="email"
                        id="login_email"
                        name="email"
                        class="form-input"
                        placeholder="your@email.com"
                        required
                        autocomplete="email"
                    >
                    <span class="error-message" id="login_email_error"></span>
                </div>

                <div class="form-group">
                    <label for="login_password">Password</label>
                    <div class="password-field">
                        <input
                            type="password"
                            id="login_password"
                            name="password"
                            class="form-input"
                            placeholder="Enter your password"
                            required
                            autocomplete="current-password"
                        >
                        <button type="button" class="password-toggle" data-target="login_password">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                    <span class="error-message" id="login_password_error"></span>
                </div>

                <div class="remember-forgot">
                    <label>
                        <input type="checkbox" name="remember" value="1"> Remember me
                    </label>
                    <a href="#" class="forgot-link" id="show-forgot-form">Forgot password?</a>
                </div>

                <button type="submit" class="btn-primary btn-block">Sign In</button>

                <div class="form-message" id="login_message"></div>
            </form>

            <!-- Sign Up Form -->
            <form id="signup-form" class="auth-form" method="post" style="display: none;">
                <?php wp_nonce_field('ajax-signup-nonce', 'signup_security'); ?>

                <div class="form-group">
                    <label for="signup_name">Full Name</label>
                    <input
                        type="text"
                        id="signup_name"
                        name="name"
                        class="form-input"
                        placeholder="John Doe"
                        required
                        autocomplete="name"
                    >
                    <span class="error-message" id="signup_name_error"></span>
                </div>

                <div class="form-group">
                    <label for="signup_email">Email Address</label>
                    <input
                        type="email"
                        id="signup_email"
                        name="email"
                        class="form-input"
                        placeholder="your@email.com"
                        required
                        autocomplete="email"
                    >
                    <span class="error-message" id="signup_email_error"></span>
                </div>

                <div class="form-group">
                    <label for="signup_password">Password</label>
                    <div class="password-field">
                        <input
                            type="password"
                            id="signup_password"
                            name="password"
                            class="form-input"
                            placeholder="Create a password"
                            required
                            autocomplete="new-password"
                        >
                        <button type="button" class="password-toggle" data-target="signup_password">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                    <span class="error-message" id="signup_password_error"></span>
                </div>

                <div class="password-requirements">
                    <strong>Password must contain:</strong>
                    <ul>
                        <li>At least 8 characters</li>
                        <li>One uppercase letter</li>
                        <li>One lowercase letter</li>
                        <li>One number</li>
                    </ul>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="terms" required>
                        I agree to the <a href="<?php echo get_privacy_policy_url(); ?>">Terms & Conditions</a>
                    </label>
                </div>

                <button type="submit" class="btn-primary btn-block">Create Account</button>

                <div class="form-message" id="signup_message"></div>
            </form>

            <!-- Forgot Password Form -->
            <form id="forgot-form" class="auth-form" method="post" style="display: none;">
                <?php wp_nonce_field('ajax-forgot-nonce', 'forgot_security'); ?>

                <div class="form-group">
                    <label for="forgot_email">Email Address</label>
                    <input
                        type="email"
                        id="forgot_email"
                        name="email"
                        class="form-input"
                        placeholder="your@email.com"
                        required
                    >
                    <span class="error-message" id="forgot_email_error"></span>
                </div>

                <p>We'll send you a link to reset your password.</p>

                <button type="submit" class="btn-primary btn-block">Send Reset Link</button>

                <div class="form-message" id="forgot_message"></div>
            </form>

            <!-- Social Login -->
            <div class="divider" id="social-divider">
                <span>or continue with</span>
            </div>

            <div class="social-login" id="social-login">
                <button type="button" class="social-button" data-provider="google">
                    <svg width="20" height="20" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span>Continue with Google</span>
                </button>

                <button type="button" class="social-button" data-provider="facebook">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#1877F2">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    <span>Continue with Facebook</span>
                </button>
            </div>

            <!-- Auth Footer -->
            <div class="auth-footer">
                <p id="auth-switch-text">
                    Don't have an account?
                    <a href="#" class="auth-link" id="auth-switch-link">Sign up</a>
                </p>
            </div>

            <!-- Benefits Section (shown on signup) -->
            <div class="benefits-section" id="benefits-section" style="display: none;">
                <h3>Why Join Us?</h3>
                <div class="benefits-grid">
                    <div class="benefit-item">
                        <div class="benefit-icon">🎁</div>
                        <h4>Exclusive Offers</h4>
                        <p>Get member-only discounts</p>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">🚚</div>
                        <h4>Fast Shipping</h4>
                        <p>Free shipping on orders $50+</p>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">⭐</div>
                        <h4>Rewards Program</h4>
                        <p>Earn points with every purchase</p>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">🔒</div>
                        <h4>Secure Shopping</h4>
                        <p>Safe and encrypted checkout</p>
                    </div>
                </div>
            </div>

            <!-- Security Features -->
            <div class="security-features">
                <div class="security-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <span>Secure & Encrypted</span>
                </div>
                <div class="security-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                    <span>Privacy Protected</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
