/**
 * Checkout Page JavaScript
 *
 * Handles checkout interactions including form validation, payment method selection, and shipping method updates
 */

(function($) {
    'use strict';

    const AakaariCheckout = {
        /**
         * Initialize checkout functionality
         */
        init: function() {
            this.bindEvents();
            this.initializeSteps();
            this.setupPaymentMethods();
            this.setupShippingMethods();
            this.setupFormValidation();
        },

        /**
         * Bind event listeners
         */
        bindEvents: function() {
            // Payment method changes
            $(document).on('change', 'input[name="payment_method"]', this.handlePaymentMethodChange);

            // Shipping method changes
            $(document).on('change', 'input[name^="shipping_method"]', this.handleShippingMethodChange);

            // Checkout form submission
            $(document).on('submit', 'form.checkout', this.handleCheckoutSubmit);

            // Field changes for step progression
            $(document).on('change blur', '.woocommerce-checkout input, .woocommerce-checkout select', this.updateSteps);

            // Coupon form
            $(document).on('submit', '.checkout-coupon', this.handleCouponSubmit);

            // Terms and conditions
            $(document).on('change', '#terms', this.handleTermsChange);
        },

        /**
         * Initialize checkout steps indicator
         */
        initializeSteps: function() {
            this.updateSteps();
        },

        /**
         * Update checkout steps based on form completion
         */
        updateSteps: function() {
            const $steps = $('.checkout-step');

            // Check if billing information is filled
            const billingComplete = AakaariCheckout.isBillingComplete();
            if (billingComplete) {
                $steps.eq(0).addClass('completed');
                $steps.eq(1).addClass('active');
            }

            // Check if shipping is selected
            const shippingComplete = AakaariCheckout.isShippingComplete();
            if (shippingComplete && billingComplete) {
                $steps.eq(1).addClass('completed');
                $steps.eq(2).addClass('active');
            }

            // Check if payment is selected
            const paymentComplete = AakaariCheckout.isPaymentComplete();
            if (paymentComplete && shippingComplete && billingComplete) {
                $steps.eq(2).addClass('completed');
            }
        },

        /**
         * Check if billing information is complete
         */
        isBillingComplete: function() {
            const requiredFields = [
                '#billing_first_name',
                '#billing_last_name',
                '#billing_address_1',
                '#billing_city',
                '#billing_postcode',
                '#billing_email'
            ];

            return requiredFields.every(function(field) {
                const $field = $(field);
                return $field.length === 0 || $field.val().trim() !== '';
            });
        },

        /**
         * Check if shipping is complete
         */
        isShippingComplete: function() {
            const $shippingMethod = $('input[name^="shipping_method"]:checked');
            return $shippingMethod.length > 0;
        },

        /**
         * Check if payment is complete
         */
        isPaymentComplete: function() {
            const $paymentMethod = $('input[name="payment_method"]:checked');
            return $paymentMethod.length > 0;
        },

        /**
         * Setup payment method interactions
         */
        setupPaymentMethods: function() {
            // Mark selected payment method
            $('input[name="payment_method"]:checked').closest('.checkout-payment-method').addClass('selected');

            // Show/hide payment method details
            $('.payment-method-details').hide();
            $('input[name="payment_method"]:checked').closest('.checkout-payment-method').find('.payment-method-details').show();
        },

        /**
         * Handle payment method changes
         */
        handlePaymentMethodChange: function() {
            const $radio = $(this);
            const $method = $radio.closest('.checkout-payment-method');

            // Update selected state
            $('.checkout-payment-method').removeClass('selected');
            $method.addClass('selected');

            // Show/hide payment details
            $('.payment-method-details').slideUp(200);
            $method.find('.payment-method-details').slideDown(200);

            // Update order button text if specified
            const buttonText = $radio.data('order_button_text');
            if (buttonText) {
                $('#place_order').text(buttonText);
            }

            // Update steps
            AakaariCheckout.updateSteps();

            // Trigger WooCommerce event
            $(document.body).trigger('payment_method_selected');
        },

        /**
         * Setup shipping method interactions
         */
        setupShippingMethods: function() {
            // Mark selected shipping method
            $('input[name^="shipping_method"]:checked').closest('.checkout-shipping-method').addClass('selected');
        },

        /**
         * Handle shipping method changes
         */
        handleShippingMethodChange: function(e) {
            const $radio = $(this);
            const $method = $radio.closest('.checkout-shipping-method');

            // Update selected state
            $('.checkout-shipping-method').removeClass('selected');
            $method.addClass('selected');

            // Update steps
            AakaariCheckout.updateSteps();

            // Let WooCommerce handle the AJAX update
            $(document.body).trigger('update_checkout');
        },

        /**
         * Setup form validation
         */
        setupFormValidation: function() {
            // Add real-time validation
            $('.woocommerce-checkout input, .woocommerce-checkout select').on('blur', function() {
                const $field = $(this);
                const $row = $field.closest('.form-row');

                if ($field.prop('required') && $field.val().trim() === '') {
                    $row.addClass('woocommerce-invalid');
                } else {
                    $row.removeClass('woocommerce-invalid');
                }

                // Email validation
                if ($field.attr('type') === 'email' && $field.val().trim() !== '') {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test($field.val())) {
                        $row.addClass('woocommerce-invalid');
                    } else {
                        $row.removeClass('woocommerce-invalid');
                    }
                }
            });
        },

        /**
         * Handle checkout form submission
         */
        handleCheckoutSubmit: function(e) {
            const $form = $(this);
            const $button = $('#place_order');

            // Check if already processing
            if ($form.hasClass('processing') || $button.hasClass('disabled')) {
                e.preventDefault();
                return false;
            }

            // Validate required fields
            let hasErrors = false;
            $form.find('input[required], select[required]').each(function() {
                const $field = $(this);
                const $row = $field.closest('.form-row');

                if ($field.val().trim() === '') {
                    $row.addClass('woocommerce-invalid');
                    hasErrors = true;
                } else {
                    $row.removeClass('woocommerce-invalid');
                }
            });

            // Validate email
            const $email = $('#billing_email');
            if ($email.length && $email.val().trim() !== '') {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test($email.val())) {
                    $email.closest('.form-row').addClass('woocommerce-invalid');
                    hasErrors = true;
                }
            }

            // Check terms and conditions
            if ($('#terms').length && !$('#terms').prop('checked')) {
                hasErrors = true;
                alert('Please accept the terms and conditions to proceed.');
            }

            if (hasErrors) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $('.woocommerce-invalid').first().offset().top - 100
                }, 500);
                return false;
            }

            // Show loading state
            $form.addClass('processing');
            $button.addClass('disabled').text('Processing...');

            // Let WooCommerce handle the actual submission
            return true;
        },

        /**
         * Handle coupon form submission
         */
        handleCouponSubmit: function(e) {
            e.preventDefault();

            const $form = $(this);
            const $input = $form.find('input[name="coupon_code"]');
            const couponCode = $input.val().trim();

            if (!couponCode) {
                alert('Please enter a coupon code.');
                return;
            }

            // Show loading state
            $form.addClass('processing');
            $form.find('button').prop('disabled', true).text('Applying...');

            // Use WooCommerce's apply coupon functionality
            $.ajax({
                url: wc_checkout_params.wc_ajax_url.toString().replace('%%endpoint%%', 'apply_coupon'),
                type: 'POST',
                data: {
                    coupon_code: couponCode
                },
                success: function(response) {
                    if (response.error) {
                        alert(response.error);
                    } else {
                        // Reload the checkout to show updated totals
                        $(document.body).trigger('update_checkout');
                    }

                    $form.removeClass('processing');
                    $form.find('button').prop('disabled', false).text('Apply');
                    $input.val('');
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                    $form.removeClass('processing');
                    $form.find('button').prop('disabled', false).text('Apply');
                }
            });
        },

        /**
         * Handle terms and conditions change
         */
        handleTermsChange: function() {
            const $checkbox = $(this);
            const $button = $('#place_order');

            if ($checkbox.prop('checked')) {
                $button.removeClass('disabled');
            } else {
                $button.addClass('disabled');
            }
        },

        /**
         * Scroll to errors
         */
        scrollToError: function() {
            const $error = $('.woocommerce-error, .woocommerce-invalid').first();
            if ($error.length) {
                $('html, body').animate({
                    scrollTop: $error.offset().top - 100
                }, 500);
            }
        }
    };

    // Initialize when DOM is ready
    $(document).ready(function() {
        AakaariCheckout.init();
    });

    // Reinitialize after AJAX updates
    $(document.body).on('updated_checkout', function() {
        AakaariCheckout.setupPaymentMethods();
        AakaariCheckout.setupShippingMethods();
        AakaariCheckout.updateSteps();
    });

    // Handle checkout errors
    $(document.body).on('checkout_error', function() {
        $('.checkout').removeClass('processing');
        $('#place_order').removeClass('disabled').text($('#place_order').data('value') || 'Place Order');
        AakaariCheckout.scrollToError();
    });

    // Handle payment method selection
    $(document.body).on('payment_method_selected', function() {
        // Custom handling if needed
    });

    // Handle shipping method update
    $(document.body).on('updated_shipping_method', function() {
        AakaariCheckout.setupShippingMethods();
    });

    // Make AakaariCheckout globally accessible
    window.AakaariCheckout = AakaariCheckout;

})(jQuery);
