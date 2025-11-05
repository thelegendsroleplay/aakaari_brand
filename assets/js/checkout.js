/**
 * Checkout Page JavaScript for FashionMen Theme
 * Form validation, payment methods, billing/shipping
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        initPaymentMethods();
        initBillingShipping();
        initCheckoutValidation();
    });

    /**
     * Payment Methods Selection
     */
    function initPaymentMethods() {
        $(document).on('click', '.payment-method', function() {
            $('.payment-method').removeClass('selected');
            $(this).addClass('selected');
            $(this).find('input[type="radio"]').prop('checked', true);

            // Show/hide payment method description
            $('.payment-box').slideUp();
            $(this).find('.payment-box').slideDown();
        });
    }

    /**
     * Billing/Shipping Toggle
     */
    function initBillingShipping() {
        $(document).on('change', '#ship-to-different-address-checkbox', function() {
            if ($(this).is(':checked')) {
                $('.shipping-fields').slideDown();
            } else {
                $('.shipping-fields').slideUp();
            }
        });
    }

    /**
     * Checkout Form Validation
     */
    function initCheckoutValidation() {
        $('form.checkout').on('submit', function(e) {
            let isValid = true;
            const requiredFields = $(this).find('.required input, .required select, .required textarea');

            requiredFields.each(function() {
                if (!$(this).val() || $(this).val().trim() === '') {
                    isValid = false;
                    $(this).addClass('error');

                    if (!$(this).next('.error-message').length) {
                        $(this).after('<span class="error-message" style="color: #ef4444; font-size: 0.875rem;">This field is required</span>');
                    }
                } else {
                    $(this).removeClass('error');
                    $(this).next('.error-message').remove();
                }
            });

            // Email validation
            const emailField = $('input[type="email"]');
            if (emailField.length) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailField.val())) {
                    isValid = false;
                    emailField.addClass('error');

                    if (!emailField.next('.error-message').length) {
                        emailField.after('<span class="error-message" style="color: #ef4444; font-size: 0.875rem;">Please enter a valid email address</span>');
                    }
                }
            }

            if (!isValid) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $('.error').first().offset().top - 100
                }, 300);
            }
        });

        // Remove error on input
        $(document).on('input', '.error', function() {
            $(this).removeClass('error');
            $(this).next('.error-message').remove();
        });
    }

    /**
     * Order Review Update
     */
    $(document.body).on('update_checkout', function() {
        console.log('Checkout updated');
    });

    /**
     * Place Order Button Loading State
     */
    $(document).on('click', '.place-order-btn', function() {
        const btn = $(this);
        btn.prop('disabled', true);
        btn.html('<span class="loading-spinner"></span> Processing...');
    });

})(jQuery);
