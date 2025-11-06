/**
 * Home Page JavaScript
 *
 * Handles interactions and animations for the homepage
 *
 * @package Aakaari
 */

(function($) {
    'use strict';

    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        initScrollAnimations();
        initWishlistButtons();
        initQuickViewButtons();
        initAddToCartButtons();
    });

    /**
     * Scroll Animations
     * Animate elements when they come into viewport
     */
    function initScrollAnimations() {
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    // Optional: Unobserve after animation
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe all elements with animate-on-scroll class
        const animatedElements = document.querySelectorAll('.animate-on-scroll');
        animatedElements.forEach(function(element) {
            observer.observe(element);
        });
    }

    /**
     * Wishlist Buttons
     * Handle add to wishlist functionality
     */
    function initWishlistButtons() {
        $('.product-wishlist').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $button = $(this);
            const productId = $button.data('product-id');
            const $svg = $button.find('svg');

            // Toggle wishlist state
            const isInWishlist = $button.hasClass('in-wishlist');

            if (isInWishlist) {
                // Remove from wishlist
                $button.removeClass('in-wishlist');
                $svg.find('path').attr('fill', 'none');
                showNotification('Removed from wishlist', 'info');

                // Trigger custom event
                $(document).trigger('aakaari_wishlist_removed', [productId]);
            } else {
                // Add to wishlist
                $button.addClass('in-wishlist');
                $svg.find('path').attr('fill', 'currentColor');
                showNotification('Added to wishlist', 'success');

                // Trigger custom event
                $(document).trigger('aakaari_wishlist_added', [productId]);
            }

            // Make AJAX call to save wishlist state
            if (typeof aakaari_ajax !== 'undefined') {
                $.ajax({
                    url: aakaari_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'aakaari_toggle_wishlist',
                        product_id: productId,
                        nonce: aakaari_ajax.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log('Wishlist updated successfully');
                        }
                    },
                    error: function() {
                        console.error('Error updating wishlist');
                    }
                });
            }
        });
    }

    /**
     * Quick View Buttons
     * Handle quick view modal functionality
     */
    function initQuickViewButtons() {
        $('.quick-view').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $button = $(this);
            const productId = $button.data('product-id');

            // Show loading state
            $button.addClass('loading');

            // Trigger custom event for quick view
            $(document).trigger('aakaari_quick_view_triggered', [productId]);

            // Make AJAX call to get product quick view content
            if (typeof aakaari_ajax !== 'undefined') {
                $.ajax({
                    url: aakaari_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'aakaari_quick_view',
                        product_id: productId,
                        nonce: aakaari_ajax.nonce
                    },
                    success: function(response) {
                        $button.removeClass('loading');
                        if (response.success) {
                            // Show quick view modal with product content
                            showQuickViewModal(response.data);
                        } else {
                            showNotification('Unable to load product details', 'error');
                        }
                    },
                    error: function() {
                        $button.removeClass('loading');
                        showNotification('Error loading product', 'error');
                    }
                });
            } else {
                // Fallback: Redirect to product page
                const $card = $button.closest('.featured-product-card');
                const productLink = $card.find('.product-name a').attr('href');
                if (productLink) {
                    window.location.href = productLink;
                }
            }
        });
    }

    /**
     * Enhanced Add to Cart Buttons
     * Handle add to cart with visual feedback
     */
    function initAddToCartButtons() {
        $(document).on('click', '.add-to-cart', function(e) {
            const $button = $(this);

            // Add loading class
            $button.addClass('loading');

            // Store original text
            const originalText = $button.text();
            $button.text('Adding...');

            // Listen for WooCommerce added_to_cart event
            $(document.body).on('added_to_cart', function() {
                $button.removeClass('loading');
                $button.addClass('added');
                $button.text('Added!');

                showNotification('Product added to cart', 'success');

                // Reset button after 2 seconds
                setTimeout(function() {
                    $button.removeClass('added');
                    $button.text(originalText);
                }, 2000);
            });
        });
    }

    /**
     * Show Quick View Modal
     *
     * @param {Object} data - Product data from AJAX response
     */
    function showQuickViewModal(data) {
        // Create modal if it doesn't exist
        let $modal = $('#quick-view-modal');
        if ($modal.length === 0) {
            $modal = $('<div id="quick-view-modal" class="quick-view-modal"></div>');
            $('body').append($modal);
        }

        // Set modal content
        $modal.html(data.content);

        // Show modal
        $modal.addClass('active');
        $('body').addClass('modal-open');

        // Close on overlay click
        $modal.on('click', function(e) {
            if ($(e.target).hasClass('quick-view-modal') || $(e.target).hasClass('close-modal')) {
                closeQuickViewModal();
            }
        });

        // Close on ESC key
        $(document).on('keyup.quickview', function(e) {
            if (e.key === 'Escape') {
                closeQuickViewModal();
            }
        });
    }

    /**
     * Close Quick View Modal
     */
    function closeQuickViewModal() {
        const $modal = $('#quick-view-modal');
        $modal.removeClass('active');
        $('body').removeClass('modal-open');
        $(document).off('keyup.quickview');

        // Remove modal after animation
        setTimeout(function() {
            $modal.empty();
        }, 300);
    }

    /**
     * Show Notification
     *
     * @param {string} message - Notification message
     * @param {string} type - Notification type (success, error, info)
     */
    function showNotification(message, type) {
        type = type || 'info';

        // Remove existing notifications
        $('.aakaari-notification').remove();

        // Create notification element
        const $notification = $('<div class="aakaari-notification aakaari-notification-' + type + '">' + message + '</div>');

        // Append to body
        $('body').append($notification);

        // Show notification with animation
        setTimeout(function() {
            $notification.addClass('show');
        }, 10);

        // Auto-hide after 3 seconds
        setTimeout(function() {
            $notification.removeClass('show');
            setTimeout(function() {
                $notification.remove();
            }, 300);
        }, 3000);
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

    /**
     * Add parallax effect to hero section (optional)
     */
    if ($('.home-hero-section').length) {
        $(window).on('scroll', function() {
            const scrolled = $(window).scrollTop();
            const hero = $('.home-hero-section');
            const heroHeight = hero.outerHeight();

            if (scrolled < heroHeight) {
                // Parallax effect
                hero.css('transform', 'translateY(' + (scrolled * 0.5) + 'px)');
            }
        });
    }

    /**
     * Lazy load images on scroll (basic implementation)
     */
    function lazyLoadImages() {
        const images = document.querySelectorAll('img[data-src]');
        const imageObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    imageObserver.unobserve(img);
                }
            });
        });

        images.forEach(function(img) {
            imageObserver.observe(img);
        });
    }

    // Initialize lazy loading
    lazyLoadImages();

    /**
     * Add CSS for notifications
     */
    if ($('style#aakaari-home-dynamic-styles').length === 0) {
        const styles = `
            <style id="aakaari-home-dynamic-styles">
                .aakaari-notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    padding: 1rem 1.5rem;
                    background: #000;
                    color: white;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                    transform: translateX(400px);
                    transition: transform 0.3s ease;
                    z-index: 9999;
                    max-width: 300px;
                    font-size: 0.875rem;
                }

                .aakaari-notification.show {
                    transform: translateX(0);
                }

                .aakaari-notification-success {
                    background: #22c55e;
                }

                .aakaari-notification-error {
                    background: #ef4444;
                }

                .aakaari-notification-info {
                    background: #3b82f6;
                }

                .add-to-cart.loading,
                .quick-view.loading {
                    opacity: 0.6;
                    pointer-events: none;
                }

                .add-to-cart.added {
                    background: #22c55e !important;
                }

                .product-wishlist.in-wishlist {
                    background: #ef4444 !important;
                    color: white !important;
                }

                .modal-open {
                    overflow: hidden;
                }

                .quick-view-modal {
                    display: none;
                    position: fixed;
                    inset: 0;
                    background: rgba(0, 0, 0, 0.8);
                    z-index: 9998;
                    align-items: center;
                    justify-content: center;
                    padding: 2rem;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                }

                .quick-view-modal.active {
                    display: flex;
                    opacity: 1;
                }

                .quick-view-modal .modal-content {
                    background: white;
                    border-radius: 12px;
                    max-width: 900px;
                    width: 100%;
                    max-height: 90vh;
                    overflow-y: auto;
                    padding: 2rem;
                    position: relative;
                    transform: scale(0.9);
                    transition: transform 0.3s ease;
                }

                .quick-view-modal.active .modal-content {
                    transform: scale(1);
                }

                .quick-view-modal .close-modal {
                    position: absolute;
                    top: 1rem;
                    right: 1rem;
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    background: #f3f4f6;
                    border: none;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: background 0.2s;
                }

                .quick-view-modal .close-modal:hover {
                    background: #e5e7eb;
                }

                @media (max-width: 768px) {
                    .aakaari-notification {
                        right: 10px;
                        left: 10px;
                        max-width: none;
                    }

                    .quick-view-modal {
                        padding: 1rem;
                    }

                    .quick-view-modal .modal-content {
                        padding: 1.5rem;
                    }
                }
            </style>
        `;
        $('head').append(styles);
    }

    /**
     * Expose public API for external use
     */
    window.aakaariHome = {
        showNotification: showNotification,
        closeQuickViewModal: closeQuickViewModal
    };

})(jQuery);
