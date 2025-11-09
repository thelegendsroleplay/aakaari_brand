/**
 * Cart Page JavaScript
 * Handles cart item management, quantity updates, and cart clearing
 */

(function() {
    'use strict';

    /**
     * Initialize cart functionality
     */
    function init() {
        setupQuantityControls();
        setupRemoveButtons();
        setupClearCart();
    }

    /**
     * Setup quantity +/- controls
     */
    function setupQuantityControls() {
        // Increase quantity
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('increase')) {
                const cartKey = e.target.dataset.key;
                const qtyInput = e.target.closest('.item-quantity').querySelector('.qty-value');

                if (qtyInput) {
                    const currentQty = parseInt(qtyInput.value);
                    const maxQty = parseInt(qtyInput.max) || 999;

                    if (currentQty < maxQty) {
                        qtyInput.value = currentQty + 1;
                        updateCartQuantity(cartKey, currentQty + 1);
                    }
                }
            }

            // Decrease quantity
            if (e.target.classList.contains('decrease')) {
                const cartKey = e.target.dataset.key;
                const qtyInput = e.target.closest('.item-quantity').querySelector('.qty-value');

                if (qtyInput) {
                    const currentQty = parseInt(qtyInput.value);

                    if (currentQty > 1) {
                        qtyInput.value = currentQty - 1;
                        updateCartQuantity(cartKey, currentQty - 1);
                    } else {
                        // Ask for confirmation before removing
                        if (confirm('Remove this item from cart?')) {
                            removeCartItem(cartKey);
                        }
                    }
                }
            }
        });

        // Manual quantity change in input field
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('qty-value')) {
                const cartItem = e.target.closest('.cart-item');
                if (cartItem) {
                    const cartKey = cartItem.dataset.key;
                    const newQty = parseInt(e.target.value);

                    if (newQty > 0) {
                        updateCartQuantity(cartKey, newQty);
                    } else {
                        e.target.value = 1;
                    }
                }
            }
        });
    }

    /**
     * Setup remove item buttons
     */
    function setupRemoveButtons() {
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('item-remove')) {
                e.preventDefault();

                const cartKey = e.target.dataset.key;
                const productName = e.target.closest('.cart-item')?.querySelector('.item-title')?.textContent;

                if (confirm(`Remove ${productName || 'this item'} from cart?`)) {
                    removeCartItem(cartKey);
                }
            }
        });
    }

    /**
     * Setup clear cart button
     */
    function setupClearCart() {
        const clearButton = document.getElementById('clear-cart-btn');

        if (clearButton) {
            clearButton.addEventListener('click', function(e) {
                e.preventDefault();

                if (confirm('Are you sure you want to clear your entire cart?')) {
                    clearCart();
                }
            });
        }
    }

    /**
     * Update cart item quantity via AJAX
     */
    function updateCartQuantity(cartKey, quantity) {
        if (typeof aakaariAjax === 'undefined') {
            console.error('aakaariAjax is not defined');
            alert('Unable to update cart. Please refresh the page.');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'aakaari_update_cart_quantity');
        formData.append('cart_item_key', cartKey);
        formData.append('quantity', quantity);
        formData.append('nonce', aakaariAjax.nonce);

        // Show loading state
        showLoadingState();

        fetch(aakaariAjax.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload page to update totals
                window.location.reload();
            } else {
                alert(data.data?.message || 'Failed to update quantity. Please try again.');
                hideLoadingState();
            }
        })
        .catch(error => {
            console.error('Cart update failed:', error);
            alert('Failed to update cart. Please refresh the page.');
            hideLoadingState();
        });
    }

    /**
     * Remove cart item via AJAX
     */
    function removeCartItem(cartKey) {
        if (typeof aakaariAjax === 'undefined') {
            console.error('aakaariAjax is not defined');
            alert('Unable to remove item. Please refresh the page.');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'aakaari_remove_cart_item');
        formData.append('cart_item_key', cartKey);
        formData.append('nonce', aakaariAjax.nonce);

        // Show loading state
        showLoadingState();

        fetch(aakaariAjax.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload page to update cart
                window.location.reload();
            } else {
                alert(data.data?.message || 'Failed to remove item. Please try again.');
                hideLoadingState();
            }
        })
        .catch(error => {
            console.error('Remove item failed:', error);
            alert('Failed to remove item. Please refresh the page.');
            hideLoadingState();
        });
    }

    /**
     * Clear entire cart via AJAX
     */
    function clearCart() {
        if (typeof aakaariAjax === 'undefined') {
            console.error('aakaariAjax is not defined');
            alert('Unable to clear cart. Please refresh the page.');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'aakaari_clear_cart');
        formData.append('nonce', aakaariAjax.nonce);

        // Show loading state
        showLoadingState();

        fetch(aakaariAjax.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload page to show empty cart
                window.location.reload();
            } else {
                alert(data.data?.message || 'Failed to clear cart. Please try again.');
                hideLoadingState();
            }
        })
        .catch(error => {
            console.error('Clear cart failed:', error);
            alert('Failed to clear cart. Please refresh the page.');
            hideLoadingState();
        });
    }

    /**
     * Show loading state
     */
    function showLoadingState() {
        const cartPage = document.querySelector('.cart-page');
        if (cartPage) {
            cartPage.style.opacity = '0.6';
            cartPage.style.pointerEvents = 'none';
        }
    }

    /**
     * Hide loading state
     */
    function hideLoadingState() {
        const cartPage = document.querySelector('.cart-page');
        if (cartPage) {
            cartPage.style.opacity = '1';
            cartPage.style.pointerEvents = 'auto';
        }
    }

    /**
     * Initialize on DOM ready
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
