/**
 * Cart Page JavaScript
 *
 * @package Aakaari Brand
 */

(function($) {
    'use strict';

    console.log('Cart Page Loaded');

    /**
     * Initialize Cart Functionality
     */
    function initCart() {
        // Remove item from cart
        initRemoveItem();

        // Clear entire cart
        initClearCart();

        // Update quantity
        initQuantityUpdate();
    }

    /**
     * Remove Single Item from Cart
     */
    function initRemoveItem() {
        $(document).on('click', '.item-remove', function(e) {
            e.preventDefault();

            const button = $(this);
            const cartItemKey = button.data('cart-item-key');
            const cartItem = button.closest('.cart-item');

            // Confirm removal
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                return;
            }

            // Show loading state
            button.prop('disabled', true);
            cartItem.css('opacity', '0.5');

            // Remove item via AJAX
            $.ajax({
                url: aakaariAjax.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'remove_cart_item',
                    cart_item_key: cartItemKey,
                    nonce: aakaariAjax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Reload page to update cart
                        window.location.reload();
                    } else {
                        alert('Failed to remove item. Please try again.');
                        button.prop('disabled', false);
                        cartItem.css('opacity', '1');
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                    button.prop('disabled', false);
                    cartItem.css('opacity', '1');
                }
            });
        });
    }

    /**
     * Clear Entire Cart
     */
    function initClearCart() {
        $(document).on('click', '.clear-cart-btn', function(e) {
            e.preventDefault();

            // Confirm clear
            if (!confirm('Are you sure you want to clear your entire cart?')) {
                return;
            }

            const button = $(this);
            button.prop('disabled', true).text('Clearing...');

            // Clear cart via AJAX
            $.ajax({
                url: aakaariAjax.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'clear_cart',
                    nonce: aakaariAjax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Reload page to show empty cart
                        window.location.reload();
                    } else {
                        alert('Failed to clear cart. Please try again.');
                        button.prop('disabled', false).text('Clear Cart');
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                    button.prop('disabled', false).text('Clear Cart');
                }
            });
        });
    }

    /**
     * Update Quantity
     */
    function initQuantityUpdate() {
        let updateTimeout;

        // Handle quantity input changes
        $(document).on('change', '.item-quantity input[type="number"]', function() {
            const input = $(this);
            const cartItem = input.closest('.cart-item');

            // Clear existing timeout
            clearTimeout(updateTimeout);

            // Set new timeout to update cart
            updateTimeout = setTimeout(function() {
                updateCartQuantities();
            }, 500);
        });

        // Handle plus/minus buttons
        $(document).on('click', '.item-quantity input[type="button"]', function() {
            // Clear existing timeout
            clearTimeout(updateTimeout);

            // Set new timeout to update cart
            updateTimeout = setTimeout(function() {
                updateCartQuantities();
            }, 500);
        });
    }

    /**
     * Update Cart Quantities via Form Submission
     */
    function updateCartQuantities() {
        // WooCommerce handles quantity updates through form submission
        // We'll trigger a page reload to update totals
        const quantities = {};
        let hasChanges = false;

        $('.item-quantity input[type="number"]').each(function() {
            const input = $(this);
            const name = input.attr('name');
            const value = input.val();

            if (name && value) {
                quantities[name] = value;
                hasChanges = true;
            }
        });

        if (hasChanges) {
            // Submit to WooCommerce cart update
            $.ajax({
                url: window.location.href,
                type: 'POST',
                data: {
                    update_cart: 1,
                    ...quantities
                },
                success: function() {
                    // Reload to show updated cart
                    window.location.reload();
                }
            });
        }
    }

    /**
     * Initialize on Document Ready
     */
    $(document).ready(function() {
        initCart();
    });

})(jQuery);
