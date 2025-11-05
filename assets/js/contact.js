/**
 * Contact Page JavaScript for FashionMen Theme
 * Form validation and submission
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        initContactForm();
    });

    /**
     * Contact Form Validation and Submission
     */
    function initContactForm() {
        $('.contact-form').on('submit', function(e) {
            e.preventDefault();

            const form = $(this);
            let isValid = true;

            // Clear previous errors
            form.find('.error-message').remove();
            form.find('.error').removeClass('error');

            // Validate name
            const name = form.find('input[name="name"]');
            if (!name.val() || name.val().trim() === '') {
                isValid = false;
                name.addClass('error');
                name.after('<span class="error-message" style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem; display: block;">Please enter your name</span>');
            }

            // Validate email
            const email = form.find('input[name="email"]');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email.val() || !emailRegex.test(email.val())) {
                isValid = false;
                email.addClass('error');
                email.after('<span class="error-message" style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem; display: block;">Please enter a valid email address</span>');
            }

            // Validate subject
            const subject = form.find('input[name="subject"]');
            if (subject.length && (!subject.val() || subject.val().trim() === '')) {
                isValid = false;
                subject.addClass('error');
                subject.after('<span class="error-message" style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem; display: block;">Please enter a subject</span>');
            }

            // Validate message
            const message = form.find('textarea[name="message"]');
            if (!message.val() || message.val().trim() === '') {
                isValid = false;
                message.addClass('error');
                message.after('<span class="error-message" style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem; display: block;">Please enter your message</span>');
            }

            if (!isValid) {
                return false;
            }

            // Show loading state
            const submitBtn = form.find('button[type="submit"]');
            const originalBtnText = submitBtn.text();
            submitBtn.prop('disabled', true).text('Sending...');

            // Simulate AJAX submission (replace with actual implementation)
            setTimeout(() => {
                // Show success message
                form.prepend('<div class="success-message" style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1rem; border-left: 4px solid #10b981;">Thank you for your message! We\'ll get back to you soon.</div>');

                // Reset form
                form[0].reset();

                // Reset button
                submitBtn.prop('disabled', false).text(originalBtnText);

                // Scroll to success message
                $('html, body').animate({
                    scrollTop: form.offset().top - 100
                }, 300);

                // Remove success message after 5 seconds
                setTimeout(() => {
                    $('.success-message').fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 5000);
            }, 1500);
        });

        // Remove error on input
        $('.contact-form input, .contact-form textarea').on('input', function() {
            $(this).removeClass('error');
            $(this).next('.error-message').remove();
        });
    }

})(jQuery);
