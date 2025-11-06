/**
 * Homepage JavaScript
 * Handles animations, wishlist, and interactive features
 *
 * @package Aakaari_Brand
 */

(function($) {
    'use strict';

    const AakaariBrandHome = {
        /**
         * Initialize all homepage components
         */
        init: function() {
            this.setupScrollAnimations();
            this.setupWishlist();
            this.setupQuickView();
            this.setupAddToCart();
        },

        /**
         * Setup scroll animations
         */
        setupScrollAnimations: function() {
            // Add intersection observer for scroll animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, observerOptions);

            // Observe category cards
            $('.home-category-card').each(function(index) {
                $(this).addClass('animate-on-scroll');
                $(this).css('transition-delay', (index * 0.1) + 's');
                observer.observe(this);
            });

            // Observe product cards
            $('.featured-product-card').each(function(index) {
                $(this).addClass('animate-on-scroll');
                $(this).css('transition-delay', (index * 0.1) + 's');
                observer.observe(this);
            });

            // Observe sections
            $('.categories-section, .featured-section').each(function() {
                $(this).addClass('animate-on-scroll');
                observer.observe(this);
            });
        },

        /**
         * Setup wishlist functionality
         */
        setupWishlist: function() {
            const self = this;

            $('.product-wishlist').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const $button = $(this);
                const productId = $button.data('product-id');

                // Check if YITH Wishlist plugin is available
                if (typeof yith_wcwl_l10n !== 'undefined') {
                    // Use YITH Wishlist
                    self.toggleYITHWishlist($button, productId);
                } else {
                    // Fallback to localStorage
                    self.toggleLocalWishlist($button, productId);
                }
            });
        },

        /**
         * Toggle YITH Wishlist
         */
        toggleYITHWishlist: function($button, productId) {
            const isInWishlist = $button.hasClass('in-wishlist');

            $.ajax({
                url: yith_wcwl_l10n.ajax_url,
                type: 'POST',
                data: {
                    action: isInWishlist ? 'remove_from_wishlist' : 'add_to_wishlist',
                    add_to_wishlist: productId,
                    product_id: productId
                },
                success: function(response) {
                    $button.toggleClass('in-wishlist');

                    // Show notification
                    const message = isInWishlist
                        ? 'Removed from wishlist'
                        : 'Added to wishlist';
                    AakaariBrandHome.showNotification(message);
                },
                error: function() {
                    AakaariBrandHome.showNotification('Error updating wishlist', 'error');
                }
            });
        },

        /**
         * Toggle local storage wishlist (fallback)
         */
        toggleLocalWishlist: function($button, productId) {
            let wishlist = JSON.parse(localStorage.getItem('aakaari_wishlist') || '[]');
            const index = wishlist.indexOf(productId.toString());

            if (index > -1) {
                wishlist.splice(index, 1);
                $button.removeClass('in-wishlist');
                this.showNotification('Removed from wishlist');
            } else {
                wishlist.push(productId.toString());
                $button.addClass('in-wishlist');
                this.showNotification('Added to wishlist');
            }

            localStorage.setItem('aakaari_wishlist', JSON.stringify(wishlist));
        },

        /**
         * Setup quick view functionality
         */
        setupQuickView: function() {
            $('.quick-view').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const productId = $(this).data('product-id');

                // Trigger WooCommerce quick view if available
                if (typeof $.fn.wc_quick_view !== 'undefined') {
                    $.fn.wc_quick_view(productId);
                } else {
                    // Fallback: redirect to product page
                    const $card = $(this).closest('.featured-product-card');
                    const productUrl = $card.find('.product-name a').attr('href');
                    if (productUrl) {
                        window.location.href = productUrl;
                    }
                }
            });
        },

        /**
         * Setup add to cart functionality
         */
        setupAddToCart: function() {
            $(document).on('click', '.featured-product-card .ajax_add_to_cart', function(e) {
                const $button = $(this);
                $button.addClass('loading');

                // WooCommerce will handle the AJAX
                // We just need to update UI on success
            });

            // Handle add to cart success
            $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
                AakaariBrandHome.showNotification('Product added to cart');

                // Update cart count in header
                if (fragments && fragments['.header-badge']) {
                    $('.header-badge').replaceWith(fragments['.header-badge']);
                }
            });
        },

        /**
         * Show notification
         */
        showNotification: function(message, type) {
            type = type || 'success';

            // Remove existing notifications
            $('.home-notification').remove();

            // Create notification
            const $notification = $('<div>')
                .addClass('home-notification')
                .addClass('home-notification-' + type)
                .text(message);

            // Add to page
            $('body').append($notification);

            // Show notification
            setTimeout(function() {
                $notification.addClass('show');
            }, 10);

            // Hide notification after 3 seconds
            setTimeout(function() {
                $notification.removeClass('show');
                setTimeout(function() {
                    $notification.remove();
                }, 300);
            }, 3000);
        }
    };

    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        AakaariBrandHome.init();
    });

})(jQuery);
