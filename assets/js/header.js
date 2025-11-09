/**
 * Header JavaScript
 * Handles mobile menu toggle, cart updates, and user interactions
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        initMobileMenu();
        initCartUpdate();
        initMobileDropdowns();
    });

    /**
     * Initialize mobile menu toggle
     */
    function initMobileMenu() {
        const mobileToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        if (!mobileToggle || !mobileMenu) {
            return;
        }

        // Toggle mobile menu
        mobileToggle.addEventListener('click', function() {
            const isExpanded = mobileToggle.getAttribute('aria-expanded') === 'true';

            mobileToggle.setAttribute('aria-expanded', !isExpanded);

            if (isExpanded) {
                mobileMenu.style.display = 'none';
            } else {
                mobileMenu.style.display = 'block';
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!mobileToggle.contains(event.target) && !mobileMenu.contains(event.target)) {
                if (mobileToggle.getAttribute('aria-expanded') === 'true') {
                    mobileToggle.setAttribute('aria-expanded', 'false');
                    mobileMenu.style.display = 'none';
                }
            }
        });

        // Close mobile menu when window is resized to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                mobileToggle.setAttribute('aria-expanded', 'false');
                mobileMenu.style.display = 'none';
            }
        });

        // Handle escape key to close menu
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && mobileToggle.getAttribute('aria-expanded') === 'true') {
                mobileToggle.setAttribute('aria-expanded', 'false');
                mobileMenu.style.display = 'none';
            }
        });
    }

    /**
     * Initialize cart count updates
     */
    function initCartUpdate() {
        // Listen for WooCommerce cart updates
        if (typeof jQuery !== 'undefined') {
            jQuery(document.body).on('added_to_cart removed_from_cart updated_cart_totals', function() {
                updateCartCount();
            });
        }

        // Also update on page load
        updateCartCount();
    }

    /**
     * Update cart count via AJAX
     */
    function updateCartCount() {
        if (typeof aakaariAjax === 'undefined') {
            return;
        }

        const cartBadge = document.querySelector('.header-cart-btn .header-badge');

        if (!cartBadge) {
            return;
        }

        // Use fetch API for AJAX request
        const formData = new FormData();
        formData.append('action', 'aakaari_get_cart_count');
        formData.append('nonce', aakaariAjax.nonce);

        fetch(aakaariAjax.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.count !== undefined) {
                const count = parseInt(data.data.count);
                cartBadge.textContent = count;

                // Show/hide badge based on count
                if (count > 0) {
                    cartBadge.style.display = 'flex';
                } else {
                    cartBadge.style.display = 'none';
                }
            }
        })
        .catch(error => {
            console.error('Error updating cart count:', error);
        });
    }

    /**
     * Initialize mobile dropdowns (user menu on mobile)
     */
    function initMobileDropdowns() {
        const mobileMenu = document.getElementById('mobile-menu');

        if (!mobileMenu) {
            return;
        }

        // Handle any mobile-specific dropdown interactions here
        // Currently, mobile menu items are simple links, so no additional JS needed
        // But this function is here for future enhancements
    }

    /**
     * Expose updateCartCount globally for use by other scripts
     */
    window.aakaariUpdateCartCount = updateCartCount;

})();
