/**
 * Authentication JavaScript
 * Handles form switching, validation, and AJAX submissions
 */

(function($) {
    'use strict';

    // Form state management
    const AuthForms = {
        currentForm: 'login', // 'login', 'signup', 'forgot'

        init: function() {
            this.bindEvents();
            this.setupPasswordToggles();
            this.setupFormValidation();
        },

        bindEvents: function() {
            // Switch between forms
            $('#auth-switch-link').on('click', this.switchToSignup.bind(this));
            $('#show-forgot-form').on('click', this.switchToForgot.bind(this));

            // Form submissions
            $('#login-form').on('submit', this.handleLogin.bind(this));
            $('#signup-form').on('submit', this.handleSignup.bind(this));
            $('#forgot-form').on('submit', this.handleForgot.bind(this));

            // Social login buttons
            $('.social-button').on('click', this.handleSocialLogin.bind(this));

            // Real-time password validation
            $('#signup_password').on('input', this.validatePasswordStrength.bind(this));
        },

        switchToSignup: function(e) {
            e.preventDefault();

            // Hide all forms
            $('#login-form, #forgot-form').hide();
            $('#signup-form').fadeIn(300);

            // Update header
            $('#auth-title').text('Create Account');
            $('#auth-subtitle').text('Sign up to get started');

            // Update footer
            $('#auth-switch-text').html(
                'Already have an account? <a href="#" class="auth-link" id="back-to-login">Sign in</a>'
            );

            // Show benefits
            $('#benefits-section').fadeIn(300);

            // Bind back to login
            $('#back-to-login').on('click', this.switchToLogin.bind(this));

            this.currentForm = 'signup';
        },

        switchToLogin: function(e) {
            e.preventDefault();

            // Hide all forms
            $('#signup-form, #forgot-form').hide();
            $('#login-form').fadeIn(300);

            // Update header
            $('#auth-title').text('Welcome Back');
            $('#auth-subtitle').text('Sign in to your account to continue');

            // Update footer
            $('#auth-switch-text').html(
                'Don\'t have an account? <a href="#" class="auth-link" id="auth-switch-link">Sign up</a>'
            );

            // Hide benefits
            $('#benefits-section').hide();

            // Show social login
            $('#social-divider, #social-login').show();

            // Rebind switch link
            $('#auth-switch-link').on('click', this.switchToSignup.bind(this));

            this.currentForm = 'login';
        },

        switchToForgot: function(e) {
            e.preventDefault();

            // Hide all forms
            $('#login-form, #signup-form').hide();
            $('#forgot-form').fadeIn(300);

            // Update header
            $('#auth-title').text('Reset Password');
            $('#auth-subtitle').text('Enter your email to receive a reset link');

            // Update footer
            $('#auth-switch-text').html(
                'Remember your password? <a href="#" class="auth-link" id="back-to-login-2">Sign in</a>'
            );

            // Hide social login and benefits
            $('#social-divider, #social-login, #benefits-section').hide();

            // Bind back to login
            $('#back-to-login-2').on('click', this.switchToLogin.bind(this));

            this.currentForm = 'forgot';
        },

        setupPasswordToggles: function() {
            $('.password-toggle').on('click', function() {
                const targetId = $(this).data('target');
                const $input = $('#' + targetId);
                const type = $input.attr('type') === 'password' ? 'text' : 'password';

                $input.attr('type', type);

                // Update icon
                if (type === 'text') {
                    $(this).html('<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>');
                } else {
                    $(this).html('<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>');
                }
            });
        },

        setupFormValidation: function() {
            // Clear error on input
            $('.form-input').on('input', function() {
                $(this).removeClass('input-error');
                const errorId = $(this).attr('id') + '_error';
                $('#' + errorId).removeClass('show').text('');
            });
        },

        validatePasswordStrength: function() {
            const password = $('#signup_password').val();
            const $requirements = $('.password-requirements li');

            // Reset all checks
            $requirements.css('color', '#666');

            if (password.length >= 8) {
                $requirements.eq(0).css('color', '#22c55e');
            }
            if (/[A-Z]/.test(password)) {
                $requirements.eq(1).css('color', '#22c55e');
            }
            if (/[a-z]/.test(password)) {
                $requirements.eq(2).css('color', '#22c55e');
            }
            if (/[0-9]/.test(password)) {
                $requirements.eq(3).css('color', '#22c55e');
            }
        },

        handleLogin: function(e) {
            e.preventDefault();

            const $form = $('#login-form');
            const $button = $form.find('button[type="submit"]');
            const $message = $('#login_message');

            // Clear previous errors
            this.clearErrors($form);

            // Get form data
            const formData = {
                action: 'aakaari_login',
                security: aakaariAuth.login_nonce,
                email: $('#login_email').val(),
                password: $('#login_password').val(),
                remember: $('input[name="remember"]').is(':checked') ? 1 : 0
            };

            // Validate
            if (!this.validateEmail(formData.email)) {
                this.showError('login_email', 'Please enter a valid email address');
                return;
            }

            if (!formData.password) {
                this.showError('login_password', 'Please enter your password');
                return;
            }

            // Show loading
            $button.addClass('loading').prop('disabled', true);

            // Submit via AJAX
            $.ajax({
                url: aakaariAuth.ajax_url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $message.addClass('success').removeClass('error').text(response.data.message).show();

                        // Redirect after short delay
                        setTimeout(function() {
                            window.location.href = response.data.redirect;
                        }, 1000);
                    } else {
                        $message.addClass('error').removeClass('success').text(response.data.message).show();
                        $button.removeClass('loading').prop('disabled', false);
                    }
                },
                error: function() {
                    $message.addClass('error').removeClass('success').text(aakaariAuth.strings.generic_error).show();
                    $button.removeClass('loading').prop('disabled', false);
                }
            });
        },

        handleSignup: function(e) {
            e.preventDefault();

            const $form = $('#signup-form');
            const $button = $form.find('button[type="submit"]');
            const $message = $('#signup_message');

            // Clear previous errors
            this.clearErrors($form);

            // Get form data
            const formData = {
                action: 'aakaari_signup',
                security: aakaariAuth.signup_nonce,
                name: $('#signup_name').val(),
                email: $('#signup_email').val(),
                password: $('#signup_password').val()
            };

            // Validate
            if (!formData.name) {
                this.showError('signup_name', 'Please enter your name');
                return;
            }

            if (!this.validateEmail(formData.email)) {
                this.showError('signup_email', 'Please enter a valid email address');
                return;
            }

            if (!this.validatePassword(formData.password)) {
                this.showError('signup_password', 'Password does not meet requirements');
                return;
            }

            // Check terms agreement
            if (!$('input[name="terms"]').is(':checked')) {
                $message.addClass('error').removeClass('success').text('Please agree to the terms and conditions').show();
                return;
            }

            // Show loading
            $button.addClass('loading').prop('disabled', true);

            // Submit via AJAX
            $.ajax({
                url: aakaariAuth.ajax_url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $message.addClass('success').removeClass('error').text(response.data.message).show();

                        // Redirect after short delay
                        setTimeout(function() {
                            window.location.href = response.data.redirect;
                        }, 1000);
                    } else {
                        // Show field-specific errors if available
                        if (response.data.errors) {
                            $.each(response.data.errors, function(field, message) {
                                AuthForms.showError('signup_' + field, message);
                            });
                        }

                        $message.addClass('error').removeClass('success').text(response.data.message).show();
                        $button.removeClass('loading').prop('disabled', false);
                    }
                },
                error: function() {
                    $message.addClass('error').removeClass('success').text(aakaariAuth.strings.generic_error).show();
                    $button.removeClass('loading').prop('disabled', false);
                }
            });
        },

        handleForgot: function(e) {
            e.preventDefault();

            const $form = $('#forgot-form');
            const $button = $form.find('button[type="submit"]');
            const $message = $('#forgot_message');

            // Clear previous errors
            this.clearErrors($form);

            // Get form data
            const formData = {
                action: 'aakaari_forgot_password',
                security: aakaariAuth.forgot_nonce,
                email: $('#forgot_email').val()
            };

            // Validate
            if (!this.validateEmail(formData.email)) {
                this.showError('forgot_email', 'Please enter a valid email address');
                return;
            }

            // Show loading
            $button.addClass('loading').prop('disabled', true);

            // Submit via AJAX
            $.ajax({
                url: aakaariAuth.ajax_url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $message.addClass('success').removeClass('error').text(response.data.message).show();
                        $form[0].reset();
                    } else {
                        $message.addClass('error').removeClass('success').text(response.data.message).show();
                    }
                    $button.removeClass('loading').prop('disabled', false);
                },
                error: function() {
                    $message.addClass('error').removeClass('success').text(aakaariAuth.strings.generic_error).show();
                    $button.removeClass('loading').prop('disabled', false);
                }
            });
        },

        handleSocialLogin: function(e) {
            e.preventDefault();
            const provider = $(e.currentTarget).data('provider');

            // This is a placeholder - actual implementation would depend on the social login plugin
            console.log('Social login with:', provider);
            alert('Social login with ' + provider + ' would be implemented here using a plugin like Nextend Social Login.');
        },

        validateEmail: function(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        },

        validatePassword: function(password) {
            // At least 8 characters, 1 uppercase, 1 lowercase, 1 number
            return password.length >= 8 &&
                   /[A-Z]/.test(password) &&
                   /[a-z]/.test(password) &&
                   /[0-9]/.test(password);
        },

        showError: function(fieldId, message) {
            $('#' + fieldId).addClass('input-error');
            $('#' + fieldId + '_error').addClass('show').text(message);
        },

        clearErrors: function($form) {
            $form.find('.input-error').removeClass('input-error');
            $form.find('.error-message').removeClass('show').text('');
            $form.find('.form-message').hide();
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AuthForms.init();
    });

})(jQuery);
