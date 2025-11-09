/**
 * Home Page JavaScript
 * Aakaari Brand Theme
 */

(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {
        console.log('Home Page Loaded');

        // Initialize product carousels
        initProductCarousels();

        // Quick view functionality
        initQuickView();

        // Add to cart functionality
        initAddToCart();

        // Category card interactions
        initCategoryCards();
    });

    /**
     * Initialize Product Carousels
     */
    function initProductCarousels() {
        const carousels = document.querySelectorAll('.product-carousel');

        carousels.forEach(function(carousel) {
            // Add smooth scrolling for carousel if needed
            // This is a simple implementation - you can add a library like Swiper.js for more features

            const products = carousel.querySelectorAll('.product-card');

            if (products.length === 0) {
                return;
            }

            // Add touch support for mobile swiping
            let startX = 0;
            let scrollLeft = 0;

            carousel.addEventListener('touchstart', function(e) {
                startX = e.touches[0].pageX - carousel.offsetLeft;
                scrollLeft = carousel.scrollLeft;
            });

            carousel.addEventListener('touchmove', function(e) {
                if (!startX) return;

                const x = e.touches[0].pageX - carousel.offsetLeft;
                const walk = (x - startX) * 2;
                carousel.scrollLeft = scrollLeft - walk;
            });

            carousel.addEventListener('touchend', function() {
                startX = 0;
            });
        });
    }

    /**
     * Initialize Quick View
     */
    function initQuickView() {
        $(document).on('click', '.quick-view-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const productId = $(this).data('product-id');

            // You can implement a modal here to show product details
            // For now, we'll just redirect to the product page
            console.log('Quick view for product:', productId);

            // Example: Open in a modal (you'll need to create the modal HTML)
            // openQuickViewModal(productId);
        });
    }

    /**
     * Initialize Add to Cart
     */
    function initAddToCart() {
        $(document).on('click', '.product-card-add-to-cart', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $button = $(this);
            const productId = $button.data('product-id');
            const productType = $button.data('product-type');

            // Disable button and show loading state
            $button.prop('disabled', true);
            const originalText = $button.text();
            $button.text('Adding...');

            // Simple products can be added directly
            if (productType === 'simple') {
                $.ajax({
                    type: 'POST',
                    url: aakaariAjax.ajaxurl,
                    data: {
                        action: 'aakaari_add_to_cart',
                        product_id: productId,
                        quantity: 1,
                        nonce: aakaariAjax.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            $button.text('Added!');

                            // Update cart count if you have a cart counter
                            updateCartCount();

                            // Reset button after 2 seconds
                            setTimeout(function() {
                                $button.text(originalText);
                                $button.prop('disabled', false);
                            }, 2000);

                            // Show success message
                            showNotification('Product added to cart!', 'success');
                        } else {
                            $button.text(originalText);
                            $button.prop('disabled', false);
                            showNotification('Failed to add product to cart.', 'error');
                        }
                    },
                    error: function() {
                        $button.text(originalText);
                        $button.prop('disabled', false);
                        showNotification('An error occurred. Please try again.', 'error');
                    }
                });
            } else {
                // For variable products, redirect to product page
                window.location.href = $button.closest('.product-card-link').attr('href');
            }
        });
    }

    /**
     * Update Cart Count
     */
    function updateCartCount() {
        $.ajax({
            type: 'POST',
            url: aakaariAjax.ajaxurl,
            data: {
                action: 'aakaari_get_cart_count',
                nonce: aakaariAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('.cart-count').text(response.data.count);
                }
            }
        });
    }

    /**
     * Show Notification
     */
    function showNotification(message, type) {
        // Create notification element
        const $notification = $('<div class="aakaari-notification"></div>')
            .addClass('notification-' + type)
            .text(message);

        // Add to body
        $('body').append($notification);

        // Show notification
        setTimeout(function() {
            $notification.addClass('show');
        }, 100);

        // Hide and remove notification after 3 seconds
        setTimeout(function() {
            $notification.removeClass('show');
            setTimeout(function() {
                $notification.remove();
            }, 300);
        }, 3000);
    }

    /**
     * Initialize Category Cards
     */
    function initCategoryCards() {
        // Category cards already have onclick in HTML
        // This function can be used for additional interactions if needed

        $('.category-card').on('mouseenter', function() {
            $(this).addClass('hover');
        }).on('mouseleave', function() {
            $(this).removeClass('hover');
        });
    }

    /**
     * Smooth Scroll for Anchor Links
     */
    $('a[href^="#"]').on('click', function(e) {
        const target = $(this.getAttribute('href'));

        if (target.length) {
            e.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 100
            }, 600);
        }
    });

})(jQuery);
