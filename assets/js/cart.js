/**
 * Cart Page JavaScript for FashionMen Theme
 * Cart updates, quantity changes, coupon application
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        initCartQuantity();
        initCartUpdate();
        initCoupon();
        initShippingProgress();
    });

    /**
     * Cart Quantity Updates
     */
    function initCartQuantity() {
        // Auto-update cart on quantity change
        $(document).on('change', '.cart-item input[type="number"]', function() {
            const cartForm = $(this).closest('form');
            const updateCartButton = cartForm.find('button[name="update_cart"]');

            // Trigger update cart button
            if (updateCartButton.length) {
                updateCartButton.prop('disabled', false);
                updateCartButton.trigger('click');
            }
        });
    }

    /**
     * Cart Update Events
     */
    function initCartUpdate() {
        $(document.body).on('updated_cart_totals', function() {
            console.log('Cart totals updated');
            updateShippingProgress();
        });
    }

    /**
     * Coupon Code
     */
    function initCoupon() {
        // Apply coupon on enter key
        $(document).on('keypress', 'input[name="coupon_code"]', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                $('button[name="apply_coupon"]').trigger('click');
            }
        });
    }

    /**
     * Free Shipping Progress Bar
     */
    function initShippingProgress() {
        updateShippingProgress();
    }

    function updateShippingProgress() {
        const progressBar = $('.shipping-progress-bar');
        if (!progressBar.length) {
            return;
        }

        const freeShippingMin = parseFloat(progressBar.data('min')) || 100;
        const currentTotal = parseFloat($('.cart-total-row.total .amount').text().replace(/[^0-9.]/g, '')) || 0;
        const percentage = Math.min((currentTotal / freeShippingMin) * 100, 100);

        progressBar.css('width', percentage + '%');

        // Update message
        const remaining = freeShippingMin - currentTotal;
        if (remaining > 0) {
            $('.shipping-message').html('Add $' + remaining.toFixed(2) + ' more for free shipping!');
        } else {
            $('.shipping-message').html('🎉 You qualify for free shipping!');
        }
    }

    /**
     * Remove Item Confirmation
     */
    $(document).on('click', '.remove-item', function(e) {
        return confirm('Are you sure you want to remove this item from your cart?');
    });

})(jQuery);
