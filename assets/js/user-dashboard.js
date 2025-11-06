/**
 * User Dashboard JavaScript
 * Handles dashboard interactions, wishlist, and dynamic content
 */

(function($) {
    'use strict';

    const Dashboard = {
        init: function() {
            this.bindEvents();
            this.setupOrderFilters();
            this.setupWishlist();
            this.setupAnimations();
        },

        bindEvents: function() {
            // Navigation highlighting
            this.highlightActiveNav();

            // Order card clicks
            $('.order-card').on('click', this.handleOrderClick.bind(this));

            // Wishlist actions
            $('.remove-from-wishlist').on('click', this.removeFromWishlist.bind(this));
            $('.add-to-cart-btn').on('click', this.addToCartFromWishlist.bind(this));

            // Address actions
            $('.address-action-btn').on('click', this.handleAddressAction.bind(this));

            // Mobile nav toggle
            this.setupMobileNav();
        },

        highlightActiveNav: function() {
            const currentUrl = window.location.href;
            $('.nav-item').each(function() {
                const navUrl = $(this).attr('href');
                if (currentUrl.indexOf(navUrl) !== -1 && navUrl.length > 1) {
                    $('.nav-item').removeClass('active');
                    $(this).addClass('active');
                }
            });
        },

        handleOrderClick: function(e) {
            // Don't trigger if clicking on a button within the order card
            if ($(e.target).is('button') || $(e.target).closest('button').length) {
                return;
            }

            const $orderCard = $(e.currentTarget);
            const orderId = $orderCard.data('order-id');

            if (orderId) {
                // Redirect to order details page
                window.location.href = wc_get_endpoint_url('view-order', orderId);
            }
        },

        setupOrderFilters: function() {
            // Add filter buttons if on orders page
            if ($('.orders-list').length) {
                this.addOrderFilters();
            }
        },

        addOrderFilters: function() {
            const filterHtml = `
                <div class="order-filters" style="margin-bottom: 1.5rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    <button class="filter-btn active" data-status="all">All</button>
                    <button class="filter-btn" data-status="completed">Completed</button>
                    <button class="filter-btn" data-status="processing">Processing</button>
                    <button class="filter-btn" data-status="pending">Pending</button>
                    <button class="filter-btn" data-status="cancelled">Cancelled</button>
                </div>
            `;

            $('.orders-list').before(filterHtml);

            // Filter button styling
            $('<style>')
                .text(`
                    .filter-btn {
                        padding: 0.5rem 1rem;
                        border: 1px solid #e5e5e5;
                        background: white;
                        border-radius: 8px;
                        cursor: pointer;
                        transition: all 0.2s;
                        font-size: 0.875rem;
                    }
                    .filter-btn:hover {
                        background: #f9f9f9;
                        border-color: #000;
                    }
                    .filter-btn.active {
                        background: #000;
                        color: white;
                        border-color: #000;
                    }
                `)
                .appendTo('head');

            // Filter functionality
            $('.filter-btn').on('click', function() {
                const status = $(this).data('status');

                $('.filter-btn').removeClass('active');
                $(this).addClass('active');

                if (status === 'all') {
                    $('.order-card').fadeIn(300);
                } else {
                    $('.order-card').hide();
                    $('.order-card').filter(function() {
                        return $(this).find('.order-status').hasClass(status);
                    }).fadeIn(300);
                }
            });
        },

        setupWishlist: function() {
            // Wishlist product hover effects
            $('.wishlist-item').hover(
                function() {
                    $(this).find('.wishlist-item-actions').fadeIn(200);
                },
                function() {
                    $(this).find('.wishlist-item-actions').fadeOut(200);
                }
            );

            // Add to wishlist from product pages
            $(document).on('click', '.add-to-wishlist-btn', this.addToWishlist.bind(this));
        },

        addToWishlist: function(e) {
            e.preventDefault();

            const $button = $(e.currentTarget);
            const productId = $button.data('product-id');

            if (!productId) {
                return;
            }

            // Show loading
            const originalText = $button.text();
            $button.text('Adding...').prop('disabled', true);

            $.ajax({
                url: aakariDashboard.ajax_url,
                type: 'POST',
                data: {
                    action: 'aakaari_add_to_wishlist',
                    nonce: aakariDashboard.nonce,
                    product_id: productId
                },
                success: function(response) {
                    if (response.success) {
                        $button.text('Added!').addClass('added');

                        // Update wishlist count
                        $('.wishlist-count').text(response.data.count);

                        // Show notification
                        Dashboard.showNotification('Product added to wishlist!', 'success');

                        // Revert button after delay
                        setTimeout(function() {
                            $button.text('In Wishlist').prop('disabled', true);
                        }, 1500);
                    } else {
                        $button.text(originalText).prop('disabled', false);
                        Dashboard.showNotification(response.data.message, 'error');
                    }
                },
                error: function() {
                    $button.text(originalText).prop('disabled', false);
                    Dashboard.showNotification('An error occurred. Please try again.', 'error');
                }
            });
        },

        removeFromWishlist: function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $button = $(e.currentTarget);
            const productId = $button.data('product-id');
            const $wishlistItem = $button.closest('.wishlist-item');

            if (!productId) {
                return;
            }

            if (!confirm(aakariDashboard.strings.confirm_delete)) {
                return;
            }

            $.ajax({
                url: aakariDashboard.ajax_url,
                type: 'POST',
                data: {
                    action: 'aakaari_remove_from_wishlist',
                    nonce: aakariDashboard.nonce,
                    product_id: productId
                },
                success: function(response) {
                    if (response.success) {
                        // Animate removal
                        $wishlistItem.fadeOut(300, function() {
                            $(this).remove();

                            // Update wishlist count
                            $('.wishlist-count').text(response.data.count);

                            // Show empty state if no items left
                            if ($('.wishlist-item').length === 0) {
                                location.reload();
                            }
                        });

                        Dashboard.showNotification('Product removed from wishlist', 'success');
                    } else {
                        Dashboard.showNotification(response.data.message, 'error');
                    }
                },
                error: function() {
                    Dashboard.showNotification('An error occurred. Please try again.', 'error');
                }
            });
        },

        addToCartFromWishlist: function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $button = $(e.currentTarget);
            const productId = $button.data('product-id');

            if (!productId) {
                return;
            }

            const originalText = $button.text();
            $button.text('Adding...').prop('disabled', true);

            // Use WooCommerce AJAX add to cart
            $.ajax({
                url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
                type: 'POST',
                data: {
                    product_id: productId,
                    quantity: 1
                },
                success: function(response) {
                    if (response.error) {
                        $button.text(originalText).prop('disabled', false);
                        Dashboard.showNotification(response.error, 'error');
                    } else {
                        $button.text('Added!');

                        // Trigger cart update event
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash]);

                        Dashboard.showNotification('Product added to cart!', 'success');

                        // Revert button after delay
                        setTimeout(function() {
                            $button.text(originalText).prop('disabled', false);
                        }, 2000);
                    }
                },
                error: function() {
                    $button.text(originalText).prop('disabled', false);
                    Dashboard.showNotification('An error occurred. Please try again.', 'error');
                }
            });
        },

        handleAddressAction: function(e) {
            e.preventDefault();

            const action = $(e.currentTarget).data('action');
            const addressId = $(e.currentTarget).data('address-id');

            // Handle different address actions
            console.log('Address action:', action, addressId);

            // This would typically redirect to WooCommerce address edit pages
            // or open a modal for editing
        },

        setupAnimations: function() {
            // Animate stat cards on load
            $('.stat-card').each(function(index) {
                $(this).css({
                    opacity: 0,
                    transform: 'translateY(20px)'
                }).delay(index * 100).animate({
                    opacity: 1
                }, 400, function() {
                    $(this).css('transform', 'translateY(0)');
                });
            });

            // Animate sections on scroll
            this.setupScrollAnimations();
        },

        setupScrollAnimations: function() {
            // Simple scroll animation for dashboard sections
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('animate-in');
                            observer.unobserve(entry.target);
                        }
                    });
                }, observerOptions);

                $('.dashboard-section').each(function() {
                    observer.observe(this);
                });

                // Add animation styles
                $('<style>')
                    .text(`
                        .dashboard-section {
                            opacity: 0;
                            transform: translateY(20px);
                            transition: opacity 0.4s ease, transform 0.4s ease;
                        }
                        .dashboard-section.animate-in {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    `)
                    .appendTo('head');
            }
        },

        setupMobileNav: function() {
            // Add mobile nav toggle if needed
            if ($(window).width() < 1024) {
                const $sidebar = $('.dashboard-sidebar');
                const $nav = $('.dashboard-nav');

                // Create toggle button
                const $toggle = $('<button>')
                    .addClass('mobile-nav-toggle')
                    .html('<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>')
                    .insertBefore($sidebar);

                // Add styles
                $('<style>')
                    .text(`
                        .mobile-nav-toggle {
                            display: none;
                            position: fixed;
                            bottom: 2rem;
                            right: 2rem;
                            width: 56px;
                            height: 56px;
                            background: #000;
                            color: white;
                            border: none;
                            border-radius: 50%;
                            cursor: pointer;
                            z-index: 1000;
                            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
                        }
                        @media (max-width: 1024px) {
                            .mobile-nav-toggle {
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            }
                            .dashboard-sidebar {
                                position: fixed;
                                top: 0;
                                left: -100%;
                                width: 280px;
                                height: 100vh;
                                z-index: 999;
                                transition: left 0.3s ease;
                                overflow-y: auto;
                            }
                            .dashboard-sidebar.open {
                                left: 0;
                            }
                        }
                    `)
                    .appendTo('head');

                // Toggle functionality
                $toggle.on('click', function() {
                    $sidebar.toggleClass('open');
                });

                // Close on nav item click
                $('.nav-item').on('click', function() {
                    if ($(window).width() < 1024) {
                        $sidebar.removeClass('open');
                    }
                });

                // Close on outside click
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.dashboard-sidebar, .mobile-nav-toggle').length) {
                        $sidebar.removeClass('open');
                    }
                });
            }
        },

        showNotification: function(message, type) {
            // Create notification element
            const $notification = $('<div>')
                .addClass('dashboard-notification')
                .addClass(type)
                .text(message);

            // Add styles
            if (!$('#dashboard-notification-styles').length) {
                $('<style id="dashboard-notification-styles">')
                    .text(`
                        .dashboard-notification {
                            position: fixed;
                            top: 2rem;
                            right: 2rem;
                            padding: 1rem 1.5rem;
                            border-radius: 8px;
                            background: white;
                            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                            z-index: 9999;
                            animation: slideInRight 0.3s ease;
                        }
                        .dashboard-notification.success {
                            border-left: 4px solid #22c55e;
                        }
                        .dashboard-notification.error {
                            border-left: 4px solid #ef4444;
                        }
                        @keyframes slideInRight {
                            from {
                                transform: translateX(100%);
                                opacity: 0;
                            }
                            to {
                                transform: translateX(0);
                                opacity: 1;
                            }
                        }
                    `)
                    .appendTo('head');
            }

            // Add to page
            $('body').append($notification);

            // Remove after delay
            setTimeout(function() {
                $notification.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        Dashboard.init();
    });

})(jQuery);
