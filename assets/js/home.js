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
     * Initialize Product Carousels with Navigation
     */
    function initProductCarousels() {
        const carousels = document.querySelectorAll('.product-carousel');

        carousels.forEach(function(carousel) {
            const carouselId = carousel.getAttribute('data-carousel');
            const products = carousel.querySelectorAll('.product-card');

            if (products.length === 0) {
                return;
            }

            // Get navigation buttons
            const prevBtn = document.querySelector(`[data-carousel-nav="${carouselId}"][data-direction="prev"]`);
            const nextBtn = document.querySelector(`[data-carousel-nav="${carouselId}"][data-direction="next"]`);

            if (!prevBtn || !nextBtn) {
                return;
            }

            // Handle navigation clicks
            prevBtn.addEventListener('click', function() {
                const cardWidth = products[0].offsetWidth + 20; // width + gap
                carousel.scrollBy({
                    left: -cardWidth * 2,
                    behavior: 'smooth'
                });
            });

            nextBtn.addEventListener('click', function() {
                const cardWidth = products[0].offsetWidth + 20; // width + gap
                carousel.scrollBy({
                    left: cardWidth * 2,
                    behavior: 'smooth'
                });
            });

            // Update button states on scroll
            function updateNavButtons() {
                const scrollLeft = carousel.scrollLeft;
                const maxScroll = carousel.scrollWidth - carousel.clientWidth;

                if (scrollLeft <= 0) {
                    prevBtn.setAttribute('disabled', 'disabled');
                } else {
                    prevBtn.removeAttribute('disabled');
                }

                if (scrollLeft >= maxScroll - 5) { // -5 for rounding
                    nextBtn.setAttribute('disabled', 'disabled');
                } else {
                    nextBtn.removeAttribute('disabled');
                }
            }

            // Initial button state
            updateNavButtons();

            // Update on scroll
            carousel.addEventListener('scroll', updateNavButtons);

            // Add touch support for mobile swiping
            let startX = 0;
            let scrollLeft = 0;
            let isDown = false;

            carousel.addEventListener('mousedown', function(e) {
                isDown = true;
                carousel.style.cursor = 'grabbing';
                startX = e.pageX - carousel.offsetLeft;
                scrollLeft = carousel.scrollLeft;
            });

            carousel.addEventListener('mouseleave', function() {
                isDown = false;
                carousel.style.cursor = 'grab';
            });

            carousel.addEventListener('mouseup', function() {
                isDown = false;
                carousel.style.cursor = 'grab';
            });

            carousel.addEventListener('mousemove', function(e) {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - carousel.offsetLeft;
                const walk = (x - startX) * 2;
                carousel.scrollLeft = scrollLeft - walk;
            });

            // Touch events for mobile
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
     * Initialize Quick View Modal
     */
    function initQuickView() {
        const modal = document.getElementById('quickViewModal');
        const closeBtn = document.getElementById('quickViewClose');
        const contentDiv = document.getElementById('quickViewContent');

        // Open quick view
        $(document).on('click', '.quick-view-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const productId = $(this).data('product-id');
            openQuickView(productId, modal, contentDiv);
        });

        // Close quick view
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                closeQuickView(modal);
            });
        }

        // Close on overlay click
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeQuickView(modal);
                }
            });
        }

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal && modal.classList.contains('active')) {
                closeQuickView(modal);
            }
        });
    }

    /**
     * Open Quick View Modal
     */
    function openQuickView(productId, modal, contentDiv) {
        // Show modal with loading state
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';

        // Show loading content
        contentDiv.innerHTML = `
            <div class="quick-view-loading">
                <div class="quick-view-loading-spinner"></div>
                <p>Loading product details...</p>
            </div>
        `;

        // Fetch product details via AJAX
        $.ajax({
            type: 'POST',
            url: aakaariAjax.ajaxUrl,
            data: {
                action: 'aakaari_get_quick_view',
                product_id: productId,
                nonce: aakaariAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    contentDiv.innerHTML = response.data.html;
                    initQuickViewInteractions();
                } else {
                    contentDiv.innerHTML = `
                        <div class="quick-view-loading">
                            <p>Failed to load product details.</p>
                        </div>
                    `;
                }
            },
            error: function() {
                contentDiv.innerHTML = `
                    <div class="quick-view-loading">
                        <p>An error occurred. Please try again.</p>
                    </div>
                `;
            }
        });
    }

    /**
     * Close Quick View Modal
     */
    function closeQuickView(modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    /**
     * Initialize Quick View Interactions
     */
    function initQuickViewInteractions() {
        // Quantity controls
        $(document).on('click', '.quick-view-quantity-btn', function() {
            const $input = $(this).siblings('.quick-view-quantity-value');
            let value = parseInt($input.text()) || 1;

            if ($(this).hasClass('plus')) {
                value++;
            } else if ($(this).hasClass('minus') && value > 1) {
                value--;
            }

            $input.text(value);
        });

        // Variation selection
        $(document).on('click', '.quick-view-variation-option', function() {
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
        });

        // Thumbnail selection
        $(document).on('click', '.quick-view-thumbnail', function() {
            const newSrc = $(this).find('img').attr('src');
            $('.quick-view-main-image img').attr('src', newSrc);
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
        });

        // Add to cart from quick view
        $(document).on('click', '.quick-view-add-to-cart', function() {
            const $button = $(this);
            const productId = $button.data('product-id');
            const quantity = parseInt($('.quick-view-quantity-value').text()) || 1;

            $button.prop('disabled', true);
            const originalText = $button.text();
            $button.text('Adding...');

            $.ajax({
                type: 'POST',
                url: aakaariAjax.ajaxUrl,
                data: {
                    action: 'aakaari_add_to_cart',
                    product_id: productId,
                    quantity: quantity,
                    nonce: aakaariAjax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $button.text('Added!');
                        updateCartCount();
                        showNotification('Product added to cart!', 'success');

                        setTimeout(function() {
                            $button.text(originalText);
                            $button.prop('disabled', false);
                            closeQuickView(document.getElementById('quickViewModal'));
                        }, 1500);
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
                    url: aakaariAjax.ajaxUrl,
                    data: {
                        action: 'aakaari_add_to_cart',
                        product_id: productId,
                        quantity: 1,
                        nonce: aakaariAjax.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            $button.text('Added!');

                            // Update cart count
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
            url: aakaariAjax.ajaxUrl,
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
