/**
 * Layout JavaScript
 * Handles global functionality for the theme
 */

(function() {
    'use strict';

    // Mobile menu toggle
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuToggle && mobileMenu) {
        mobileMenuToggle.addEventListener('click', function() {
            const isOpen = mobileMenu.style.display === 'block';

            mobileMenu.style.display = isOpen ? 'none' : 'block';

            // Toggle icons
            const menuIcon = mobileMenuToggle.querySelector('.menu-icon');
            const closeIcon = mobileMenuToggle.querySelector('.close-icon');

            if (menuIcon && closeIcon) {
                menuIcon.style.display = isOpen ? 'block' : 'none';
                closeIcon.style.display = isOpen ? 'none' : 'block';
            }
        });

        // Close mobile menu when clicking on a link
        const mobileMenuLinks = mobileMenu.querySelectorAll('a');
        mobileMenuLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                mobileMenu.style.display = 'none';
                const menuIcon = mobileMenuToggle.querySelector('.menu-icon');
                const closeIcon = mobileMenuToggle.querySelector('.close-icon');

                if (menuIcon && closeIcon) {
                    menuIcon.style.display = 'block';
                    closeIcon.style.display = 'none';
                }
            });
        });
    }

    // Update cart count via AJAX
    function updateCartCount() {
        if (typeof aakaariData === 'undefined') {
            return;
        }

        fetch(aakaariData.ajaxUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'aakaari_update_cart_count',
                nonce: aakaariData.nonce
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.count !== undefined) {
                const cartBadge = document.querySelector('.header-cart-btn .header-badge');
                if (cartBadge) {
                    cartBadge.textContent = data.data.count;
                    cartBadge.style.display = data.data.count > 0 ? 'flex' : 'none';
                }
            }
        })
        .catch(error => console.error('Error updating cart count:', error));
    }

    // Listen for WooCommerce events to update cart count
    document.body.addEventListener('added_to_cart', updateCartCount);
    document.body.addEventListener('removed_from_cart', updateCartCount);
    document.body.addEventListener('updated_cart_totals', updateCartCount);

    // Smooth scroll for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add to cart button loading state
    const addToCartButtons = document.querySelectorAll('.add_to_cart_button');
    addToCartButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            this.classList.add('loading');
            this.textContent = 'Adding...';
        });
    });

    // Remove loading state after add to cart
    document.body.addEventListener('added_to_cart', function() {
        addToCartButtons.forEach(function(button) {
            button.classList.remove('loading');
            button.textContent = 'Add to cart';
        });
    });

    // Sticky header on scroll
    const header = document.querySelector('.header-main');
    let lastScroll = 0;

    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;

        if (currentScroll <= 0) {
            header.classList.remove('scroll-up');
            return;
        }

        if (currentScroll > lastScroll && !header.classList.contains('scroll-down')) {
            // Scrolling down
            header.classList.remove('scroll-up');
            header.classList.add('scroll-down');
        } else if (currentScroll < lastScroll && header.classList.contains('scroll-down')) {
            // Scrolling up
            header.classList.remove('scroll-down');
            header.classList.add('scroll-up');
        }

        lastScroll = currentScroll;
    });

})();
