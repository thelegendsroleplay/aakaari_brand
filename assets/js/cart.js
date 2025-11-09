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
        console.log('Cart.js initialized');
        setupQuantityControls();
        setupRemoveButtons();
        setupClearCart();
    }

    /**
     * Setup quantity +/- controls
     */
    function setupQuantityControls() {
        // Handle all button clicks in cart items
        document.addEventListener('click', function(e) {
            let target = e.target;

            // If clicked on button content, get the button element
            if (!target.classList.contains('qty-btn')) {
                target = target.closest('.qty-btn');
            }

            if (!target) return;

            // Handle increase button
            if (target.classList.contains('increase')) {
                e.preventDefault();
                console.log('Increase button clicked');

                const cartKey = target.getAttribute('data-key');
                const itemQuantity = target.closest('.item-quantity');

                if (!itemQuantity) {
                    console.error('Could not find .item-quantity parent');
                    return;
                }

                const qtyInput = itemQuantity.querySelector('.qty-value');

                if (qtyInput) {
                    const currentQty = parseInt(qtyInput.value);
                    const maxQty = parseInt(qtyInput.getAttribute('max')) || 999;

                    console.log('Current qty:', currentQty, 'Max qty:', maxQty);

                    if (currentQty < maxQty) {
                        qtyInput.value = currentQty + 1;
                        updateCartQuantity(cartKey, currentQty + 1);
                    }
                }
            }

            // Handle decrease button
            if (target.classList.contains('decrease')) {
                e.preventDefault();
                console.log('Decrease button clicked');

                const cartKey = target.getAttribute('data-key');
                const itemQuantity = target.closest('.item-quantity');

                if (!itemQuantity) {
                    console.error('Could not find .item-quantity parent');
                    return;
                }

                const qtyInput = itemQuantity.querySelector('.qty-value');

                if (qtyInput) {
                    const currentQty = parseInt(qtyInput.value);

                    console.log('Current qty:', currentQty);

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
                    const cartKey = cartItem.getAttribute('data-key');
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
            let target = e.target;

            // If clicked on button content, get the button element
            if (!target.classList.contains('item-remove')) {
                target = target.closest('.item-remove');
            }

            if (!target) return;

            if (target.classList.contains('item-remove')) {
                e.preventDefault();
                console.log('Remove button clicked');

                const cartKey = target.getAttribute('data-key');
                const productName = target.closest('.cart-item')?.querySelector('.item-title')?.textContent;

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
            console.log('Clear cart button found');
            clearButton.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Clear cart button clicked');

                if (confirm('Are you sure you want to clear your entire cart?')) {
                    clearCart();
                }
            });
        } else {
            console.warn('Clear cart button not found');
        }
    }

    /**
     * Update cart item quantity via AJAX
     */
    function updateCartQuantity(cartKey, quantity) {
        console.log('updateCartQuantity called:', cartKey, quantity);

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

        console.log('Sending AJAX request to update quantity');

        // Show loading state
        showLoadingState();

        fetch(aakaariAjax.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Update quantity response:', data);
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
        console.log('removeCartItem called:', cartKey);

        if (typeof aakaariAjax === 'undefined') {
            console.error('aakaariAjax is not defined');
            alert('Unable to remove item. Please refresh the page.');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'aakaari_remove_cart_item');
        formData.append('cart_item_key', cartKey);
        formData.append('nonce', aakaariAjax.nonce);

        console.log('Sending AJAX request to remove item');

        // Show loading state
        showLoadingState();

        fetch(aakaariAjax.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Remove item response:', data);
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
        console.log('clearCart called');

        if (typeof aakaariAjax === 'undefined') {
            console.error('aakaariAjax is not defined');
            alert('Unable to clear cart. Please refresh the page.');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'aakaari_clear_cart');
        formData.append('nonce', aakaariAjax.nonce);

        console.log('Sending AJAX request to clear cart');

        // Show loading state
        showLoadingState();

        fetch(aakaariAjax.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Clear cart response:', data);
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
