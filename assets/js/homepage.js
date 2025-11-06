/**
 * Homepage JavaScript
 * @package Aakaari_Brand
 */

(function($) {
    'use strict';

    /**
     * Homepage functionality
     */
    const AakaariBrandHomepage = {

        /**
         * Initialize
         */
        init: function() {
            this.setupAnimations();
            this.setupWishlist();
            this.setupNewsletterForm();
            this.setupSmoothScroll();
            this.setupCategoryHover();
            this.setupProductHover();
            this.setupLazyLoad();
        },

        /**
         * Setup scroll animations
         */
        setupAnimations: function() {
            // Check if IntersectionObserver is supported
            if (!('IntersectionObserver' in window)) {
                // Fallback: show all elements immediately
                $('.animate-on-scroll').addClass('visible');
                return;
            }

            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        $(entry.target).addClass('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // Observe sections
            $('.categories-section, .featured-section, .newsletter-section, .features-section').each(function() {
                $(this).addClass('animate-on-scroll');
                observer.observe(this);
            });
        },

        /**
         * Setup wishlist functionality
         */
        setupWishlist: function() {
            $(document).on('click', '.product-wishlist', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const $button = $(this);
                const $productCard = $button.closest('.featured-product-card');
                const productId = $productCard.data('product-id');

                // Toggle active state
                $button.toggleClass('active');

                // Add visual feedback
                if ($button.hasClass('active')) {
                    $button.html('<svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M10 17.5L8.825 16.45C4.4 12.475 1.5 9.85 1.5 6.625C1.5 4 3.5 2 6.125 2C7.6 2 9.025 2.725 10 3.875C10.975 2.725 12.4 2 13.875 2C16.5 2 18.5 4 18.5 6.625C18.5 9.85 15.6 12.475 11.175 16.45L10 17.5Z"/></svg>');

                    // Show notification
                    AakaariBrandHomepage.showNotification('Added to wishlist', 'success');
                } else {
                    $button.html('<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 17.5L8.825 16.45C4.4 12.475 1.5 9.85 1.5 6.625C1.5 4 3.5 2 6.125 2C7.6 2 9.025 2.725 10 3.875C10.975 2.725 12.4 2 13.875 2C16.5 2 18.5 4 18.5 6.625C18.5 9.85 15.6 12.475 11.175 16.45L10 17.5Z" stroke="currentColor" stroke-width="1.5"/></svg>');

                    // Show notification
                    AakaariBrandHomepage.showNotification('Removed from wishlist', 'info');
                }

                // Store in localStorage
                AakaariBrandHomepage.updateWishlistStorage(productId, $button.hasClass('active'));
            });

            // Load wishlist state from storage
            AakaariBrandHomepage.loadWishlistState();
        },

        /**
         * Update wishlist in localStorage
         */
        updateWishlistStorage: function(productId, isActive) {
            let wishlist = JSON.parse(localStorage.getItem('aakaari_wishlist') || '[]');

            if (isActive && !wishlist.includes(productId)) {
                wishlist.push(productId);
            } else if (!isActive) {
                wishlist = wishlist.filter(id => id !== productId);
            }

            localStorage.setItem('aakaari_wishlist', JSON.stringify(wishlist));
        },

        /**
         * Load wishlist state from localStorage
         */
        loadWishlistState: function() {
            const wishlist = JSON.parse(localStorage.getItem('aakaari_wishlist') || '[]');

            wishlist.forEach(function(productId) {
                const $button = $('.featured-product-card[data-product-id="' + productId + '"] .product-wishlist');
                $button.addClass('active');
                $button.html('<svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M10 17.5L8.825 16.45C4.4 12.475 1.5 9.85 1.5 6.625C1.5 4 3.5 2 6.125 2C7.6 2 9.025 2.725 10 3.875C10.975 2.725 12.4 2 13.875 2C16.5 2 18.5 4 18.5 6.625C18.5 9.85 15.6 12.475 11.175 16.45L10 17.5Z"/></svg>');
            });
        },

        /**
         * Setup newsletter form
         */
        setupNewsletterForm: function() {
            $('.newsletter-form').on('submit', function(e) {
                const $form = $(this);
                const $button = $form.find('.newsletter-button');
                const $input = $form.find('.newsletter-input');
                const email = $input.val();

                // Validate email
                if (!AakaariBrandHomepage.isValidEmail(email)) {
                    e.preventDefault();
                    AakaariBrandHomepage.showNotification('Please enter a valid email address', 'error');
                    $input.focus();
                    return false;
                }

                // Show loading state
                $button.prop('disabled', true);
                $button.html('<span class="loading">Subscribing...</span>');
            });

            // Check for newsletter success/error messages
            const urlParams = new URLSearchParams(window.location.search);
            const newsletterStatus = urlParams.get('newsletter');

            if (newsletterStatus === 'success') {
                AakaariBrandHomepage.showNotification('Successfully subscribed to newsletter!', 'success');
                // Remove query param
                window.history.replaceState({}, document.title, window.location.pathname);
            } else if (newsletterStatus === 'invalid') {
                AakaariBrandHomepage.showNotification('Please enter a valid email address', 'error');
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        },

        /**
         * Validate email address
         */
        isValidEmail: function(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        },

        /**
         * Show notification
         */
        showNotification: function(message, type) {
            type = type || 'info';

            // Remove existing notifications
            $('.aakaari-notification').remove();

            // Create notification element
            const $notification = $('<div>', {
                class: 'aakaari-notification aakaari-notification-' + type,
                html: message
            });

            // Add to body
            $('body').append($notification);

            // Show notification
            setTimeout(function() {
                $notification.addClass('show');
            }, 100);

            // Hide notification after 3 seconds
            setTimeout(function() {
                $notification.removeClass('show');
                setTimeout(function() {
                    $notification.remove();
                }, 300);
            }, 3000);
        },

        /**
         * Setup smooth scroll
         */
        setupSmoothScroll: function() {
            $('a[href^="#"]').on('click', function(e) {
                const target = $(this).attr('href');

                if (target !== '#' && $(target).length) {
                    e.preventDefault();

                    $('html, body').animate({
                        scrollTop: $(target).offset().top - 100
                    }, 800, 'swing');
                }
            });
        },

        /**
         * Setup category hover effects
         */
        setupCategoryHover: function() {
            $('.home-category-card').on('mouseenter', function() {
                $(this).find('.category-image').css('transform', 'scale(1.1)');
            }).on('mouseleave', function() {
                $(this).find('.category-image').css('transform', 'scale(1)');
            });
        },

        /**
         * Setup product hover effects
         */
        setupProductHover: function() {
            $('.featured-product-card').on('mouseenter', function() {
                $(this).find('.product-image').css('transform', 'scale(1.05)');
            }).on('mouseleave', function() {
                $(this).find('.product-image').css('transform', 'scale(1)');
            });
        },

        /**
         * Setup lazy loading for images
         */
        setupLazyLoad: function() {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            if (img.dataset.src) {
                                img.src = img.dataset.src;
                                img.classList.remove('lazy');
                                imageObserver.unobserve(img);
                            }
                        }
                    });
                });

                document.querySelectorAll('img[data-src]').forEach(function(img) {
                    imageObserver.observe(img);
                });
            } else {
                // Fallback: load all images immediately
                $('img[data-src]').each(function() {
                    $(this).attr('src', $(this).data('src'));
                });
            }
        },

        /**
         * Track category clicks
         */
        trackCategoryClick: function(categoryId, categoryName) {
            // Google Analytics tracking (if available)
            if (typeof gtag !== 'undefined') {
                gtag('event', 'category_click', {
                    'event_category': 'Homepage',
                    'event_label': categoryName,
                    'value': categoryId
                });
            }

            // Facebook Pixel (if available)
            if (typeof fbq !== 'undefined') {
                fbq('track', 'ViewContent', {
                    content_name: categoryName,
                    content_category: 'Category',
                    content_ids: [categoryId],
                    content_type: 'product_group'
                });
            }
        },

        /**
         * Track product clicks
         */
        trackProductClick: function(productId, productName) {
            // Google Analytics tracking (if available)
            if (typeof gtag !== 'undefined') {
                gtag('event', 'select_content', {
                    'content_type': 'product',
                    'content_id': productId,
                    'items': [{
                        'id': productId,
                        'name': productName
                    }]
                });
            }

            // Facebook Pixel (if available)
            if (typeof fbq !== 'undefined') {
                fbq('track', 'ViewContent', {
                    content_name: productName,
                    content_ids: [productId],
                    content_type: 'product'
                });
            }
        }
    };

    /**
     * Initialize when document is ready
     */
    $(document).ready(function() {
        AakaariBrandHomepage.init();
    });

    // Add notification styles dynamically
    const notificationStyles = `
        <style>
            .aakaari-notification {
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                background: white;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                z-index: 9999;
                transform: translateX(400px);
                transition: transform 0.3s ease;
                font-weight: 500;
                max-width: 300px;
            }
            .aakaari-notification.show {
                transform: translateX(0);
            }
            .aakaari-notification-success {
                background: #22c55e;
                color: white;
            }
            .aakaari-notification-error {
                background: #ef4444;
                color: white;
            }
            .aakaari-notification-info {
                background: #3b82f6;
                color: white;
            }
            .product-wishlist.active {
                background: #ef4444;
                color: white;
            }
            @media (max-width: 768px) {
                .aakaari-notification {
                    right: 10px;
                    left: 10px;
                    max-width: none;
                }
            }
        </style>
    `;

    $('head').append(notificationStyles);

})(jQuery);
