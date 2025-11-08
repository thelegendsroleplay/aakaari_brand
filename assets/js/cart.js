/**
 * Cart functionality - Quantity controls, remove items, clear cart
 */
(function() {
    'use strict';

    // Helper to get cart form
    function getCartForm() {
        return document.querySelector('.woocommerce-cart-form');
    }

    // Helper to update cart via AJAX
    function updateCart() {
        const form = getCartForm();
        if (!form) return;

        const formData = new FormData(form);
        formData.append('update_cart', '1');

        // Show loading state
        const cartItems = document.querySelector('.cart-items');
        if (cartItems) {
            cartItems.style.opacity = '0.6';
            cartItems.style.pointerEvents = 'none';
        }

        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(html => {
            // Reload the page to show updated cart
            window.location.reload();
        })
        .catch(error => {
            console.error('Cart update error:', error);
            if (cartItems) {
                cartItems.style.opacity = '1';
                cartItems.style.pointerEvents = 'auto';
            }
            alert('Could not update cart. Please try again.');
        });
    }

    // Quantity increase/decrease buttons
    document.addEventListener('click', function(e) {
        // Increase quantity
        if (e.target.classList.contains('increase') || e.target.closest('.increase')) {
            e.preventDefault();
            const btn = e.target.classList.contains('increase') ? e.target : e.target.closest('.increase');
            const qtyInput = btn.parentElement.querySelector('.qty-value');
            const max = parseInt(btn.dataset.max) || 999;
            const currentQty = parseInt(qtyInput.value) || 1;

            if (currentQty < max) {
                qtyInput.value = currentQty + 1;
                updateCart();
            }
        }

        // Decrease quantity
        if (e.target.classList.contains('decrease') || e.target.closest('.decrease')) {
            e.preventDefault();
            const btn = e.target.classList.contains('decrease') ? e.target : e.target.closest('.decrease');
            const qtyInput = btn.parentElement.querySelector('.qty-value');
            const currentQty = parseInt(qtyInput.value) || 1;

            if (currentQty > 1) {
                qtyInput.value = currentQty - 1;
                updateCart();
            }
        }

        // Remove item
        if (e.target.classList.contains('item-remove') || e.target.closest('.item-remove')) {
            e.preventDefault();
            const btn = e.target.classList.contains('item-remove') ? e.target : e.target.closest('.item-remove');
            const cartItemKey = btn.dataset.key;

            if (!confirm('Remove this item from cart?')) {
                return;
            }

            // Remove item by setting quantity to 0
            const cartItem = btn.closest('.cart-item');
            const qtyInput = cartItem.querySelector('.qty-value');
            if (qtyInput) {
                qtyInput.value = 0;
                updateCart();
            }
        }

        // Clear cart
        if (e.target.id === 'clear-cart-btn' || e.target.closest('#clear-cart-btn')) {
            e.preventDefault();

            if (!confirm('Are you sure you want to clear your cart?')) {
                return;
            }

            // Set all quantities to 0
            const qtyInputs = document.querySelectorAll('.qty-value');
            qtyInputs.forEach(input => {
                input.value = 0;
            });
            updateCart();
        }
    });

    // Update item count in header dynamically
    function updateItemCount() {
        const itemsCount = document.getElementById('items-count');
        if (!itemsCount) return;

        const cartItems = document.querySelectorAll('.cart-item');
        let totalItems = 0;

        cartItems.forEach(item => {
            const qtyInput = item.querySelector('.qty-value');
            if (qtyInput) {
                totalItems += parseInt(qtyInput.value) || 0;
            }
        });

        itemsCount.textContent = `${totalItems} ${totalItems === 1 ? 'item' : 'items'}`;
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        updateItemCount();
    });

})();
