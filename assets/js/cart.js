/**
 * Cart Page JavaScript
 *
 * Handles cart interactions including quantity changes and item removal
 */

(function($) {
    'use strict';

    const AakaariCart = {
        /**
         * Initialize cart functionality
         */
        init: function() {
            this.bindEvents();
            this.setupQuantityControls();
        },

        /**
         * Bind event listeners
         */
        bindEvents: function() {
            // Quantity button clicks
            $(document).on('click', '.cart-quantity-button', this.handleQuantityButton);

            // Direct quantity input changes
            $(document).on('change', '.cart-quantity-selector input[type="number"]', this.handleQuantityChange);

            // Remove item clicks
            $(document).on('click', '.remove-cart-item', this.handleRemoveItem);

            // Update cart button
            $(document).on('click', 'button[name="update_cart"]', this.handleUpdateCart);
        },

        /**
         * Setup custom quantity controls
         */
        setupQuantityControls: function() {
            $('.cart-quantity-selector').each(function() {
                const $container = $(this);
                const $input = $container.find('input[type="number"]');
                const $minus = $container.find('.quantity-minus');
                const $plus = $container.find('.quantity-plus');

                // Update button states
                const updateButtonStates = function() {
                    const value = parseInt($input.val()) || 1;
                    const min = parseInt($input.attr('min')) || 1;
                    const max = parseInt($input.attr('max')) || 999;

                    $minus.prop('disabled', value <= min);
                    $plus.prop('disabled', max > 0 && value >= max);
                };

                updateButtonStates();
                $input.on('change', updateButtonStates);
            });
        },

        /**
         * Handle quantity button clicks
         */
        handleQuantityButton: function(e) {
            e.preventDefault();

            const $button = $(this);
            const $container = $button.closest('.cart-quantity-selector');
            const $input = $container.find('input[type="number"]');
            const currentValue = parseInt($input.val()) || 1;
            const min = parseInt($input.attr('min')) || 1;
            const max = parseInt($input.attr('max')) || 999;

            let newValue = currentValue;

            if ($button.hasClass('quantity-minus')) {
                newValue = Math.max(min, currentValue - 1);
            } else if ($button.hasClass('quantity-plus')) {
                newValue = max > 0 ? Math.min(max, currentValue + 1) : currentValue + 1;
            }

            if (newValue !== currentValue) {
                $input.val(newValue).trigger('change');

                // Trigger form update if auto-update is enabled
                if (AakaariCart.shouldAutoUpdate()) {
                    AakaariCart.updateCartItem($container, newValue);
                }
            }
        },

        /**
         * Handle quantity input changes
         */
        handleQuantityChange: function(e) {
            const $input = $(this);
            const $container = $input.closest('.cart-quantity-selector');
            const value = parseInt($input.val()) || 1;
            const min = parseInt($input.attr('min')) || 1;
            const max = parseInt($input.attr('max')) || 999;

            // Validate value
            let newValue = value;
            if (value < min) {
                newValue = min;
            } else if (max > 0 && value > max) {
                newValue = max;
            }

            if (newValue !== value) {
                $input.val(newValue);
            }

            // Update button states
            const $minus = $container.find('.quantity-minus');
            const $plus = $container.find('.quantity-plus');
            $minus.prop('disabled', newValue <= min);
            $plus.prop('disabled', max > 0 && newValue >= max);
        },

        /**
         * Handle remove item clicks
         */
        handleRemoveItem: function(e) {
            e.preventDefault();

            if (!confirm('Are you sure you want to remove this item?')) {
                return;
            }

            const $link = $(this);
            const $cartItem = $link.closest('.cart-item-card');
            const cartItemKey = $cartItem.data('cart-item-key');

            // Show loading state
            $cartItem.addClass('cart-loading');

            // If AJAX is available, use it
            if (typeof aakaariCart !== 'undefined' && aakaariCart.ajax_url) {
                $.ajax({
                    url: aakaariCart.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'aakaari_remove_cart_item',
                        cart_item_key: cartItemKey,
                        nonce: aakaariCart.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            // Remove the item with animation
                            $cartItem.fadeOut(300, function() {
                                $(this).remove();

                                // Reload page if cart is empty
                                if ($('.cart-item-card').length === 0) {
                                    location.reload();
                                } else {
                                    // Update cart totals
                                    AakaariCart.updateCartTotals(response.data);
                                }
                            });
                        } else {
                            alert('Failed to remove item. Please try again.');
                            $cartItem.removeClass('cart-loading');
                        }
                    },
                    error: function() {
                        alert('An error occurred. Please try again.');
                        $cartItem.removeClass('cart-loading');
                    }
                });
            } else {
                // Fall back to default behavior
                window.location.href = $link.attr('href');
            }
        },

        /**
         * Handle update cart button
         */
        handleUpdateCart: function(e) {
            // Let the default form submission happen
            const $button = $(this);
            $button.prop('disabled', true).text('Updating...');
        },

        /**
         * Update a single cart item via AJAX
         */
        updateCartItem: function($container, quantity) {
            const $cartItem = $container.closest('.cart-item-card');
            const cartItemKey = $cartItem.data('cart-item-key');

            if (!cartItemKey) return;

            $cartItem.addClass('cart-loading');

            $.ajax({
                url: aakaariCart.ajax_url,
                type: 'POST',
                data: {
                    action: 'aakaari_update_cart_quantity',
                    cart_item_key: cartItemKey,
                    quantity: quantity,
                    nonce: aakaariCart.nonce
                },
                success: function(response) {
                    if (response.success) {
                        if (quantity <= 0) {
                            $cartItem.fadeOut(300, function() {
                                $(this).remove();
                                if ($('.cart-item-card').length === 0) {
                                    location.reload();
                                }
                            });
                        } else {
                            $cartItem.removeClass('cart-loading');
                            AakaariCart.updateCartTotals(response.data);
                        }
                    } else {
                        alert('Failed to update quantity. Please try again.');
                        $cartItem.removeClass('cart-loading');
                        location.reload();
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                    $cartItem.removeClass('cart-loading');
                }
            });
        },

        /**
         * Update cart totals display
         */
        updateCartTotals: function(data) {
            if (data.cart_subtotal) {
                // Update subtotal if element exists
                $('.cart-summary-card').find('.cart-subtotal').html(data.cart_subtotal);
            }

            if (data.cart_total) {
                // Update total if element exists
                $('.cart-summary-card').find('.cart-total').html(data.cart_total);
            }

            // Trigger WooCommerce update cart fragments
            if (data.fragments) {
                $.each(data.fragments, function(key, value) {
                    $(key).replaceWith(value);
                });
            }

            // Update cart count in header if exists
            if (data.cart_count !== undefined) {
                $('.cart-count, .cart-contents-count').text(data.cart_count);
            }

            // Trigger custom event
            $(document.body).trigger('aakaari_cart_updated', [data]);
        },

        /**
         * Check if auto-update should be enabled
         */
        shouldAutoUpdate: function() {
            // Enable auto-update if AJAX is available
            return typeof aakaariCart !== 'undefined' && aakaariCart.ajax_url;
        }
    };

    // Initialize when DOM is ready
    $(document).ready(function() {
        AakaariCart.init();
    });

    // Handle WooCommerce cart updated event
    $(document.body).on('updated_cart_totals', function() {
        AakaariCart.setupQuantityControls();
    });

    // Make AakaariCart globally accessible
    window.AakaariCart = AakaariCart;

})(jQuery);
