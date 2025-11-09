/**
 * Single Product Page JavaScript
 * Handles image gallery and interactions
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        initImageGallery();
        initOptions();
        initQuantityControls();
        initAddToCart();
        initRelatedProductsCarousel();
    });

    /**
     * Initialize product image gallery
     */
    function initImageGallery() {
        const mainImage = document.getElementById('mainProductImage');
        const thumbnailBtns = document.querySelectorAll('.thumbnail-btn');

        if (!mainImage || thumbnailBtns.length === 0) {
            return;
        }

        thumbnailBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                // Remove active class from all thumbnails
                thumbnailBtns.forEach(function(thumb) {
                    thumb.classList.remove('active');
                });

                // Add active class to clicked thumbnail
                this.classList.add('active');

                // Update main image
                const newImageSrc = this.getAttribute('data-image');
                if (newImageSrc) {
                    mainImage.src = newImageSrc;
                }
            });
        });
    }

    /**
     * Initialize option buttons (size, color, variations)
     */
    function initOptions() {
        // Option buttons (sizes/variations)
        const optionBtns = document.querySelectorAll('.option-btn');
        optionBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                // Get all buttons in the same option group
                const parent = this.closest('.option-btns');
                if (parent) {
                    const siblings = parent.querySelectorAll('.option-btn');
                    siblings.forEach(function(sibling) {
                        sibling.classList.remove('active');
                    });
                }

                // Add active class to clicked button
                this.classList.add('active');
            });
        });

        // Color buttons
        const colorBtns = document.querySelectorAll('.color-btn');
        colorBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                // Remove active class from all color buttons
                colorBtns.forEach(function(colorBtn) {
                    colorBtn.classList.remove('active');
                });

                // Add active class to clicked button
                this.classList.add('active');
            });
        });

        // Set first option as active by default
        const firstOptionBtn = document.querySelector('.option-btn');
        if (firstOptionBtn) {
            firstOptionBtn.classList.add('active');
        }

        const firstColorBtn = document.querySelector('.color-btn');
        if (firstColorBtn) {
            firstColorBtn.classList.add('active');
        }
    }

    /**
     * Initialize quantity +/- controls
     */
    function initQuantityControls() {
        const minusBtn = document.querySelector('.quantity-minus');
        const plusBtn = document.querySelector('.quantity-plus');
        const quantityValue = document.querySelector('.quantity-value');

        if (!minusBtn || !plusBtn || !quantityValue) {
            return;
        }

        minusBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityValue.textContent);
            if (currentValue > 1) {
                quantityValue.textContent = currentValue - 1;
            }
        });

        plusBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityValue.textContent);
            // You can add max stock validation here if needed
            quantityValue.textContent = currentValue + 1;
        });
    }

    /**
     * Initialize add to cart and buy now buttons
     */
    function initAddToCart() {
        const addToCartBtn = document.querySelector('.add-cart-btn');
        const buyNowBtn = document.querySelector('.buy-btn');

        if (addToCartBtn) {
            addToCartBtn.addEventListener('click', function() {
                if (this.disabled) return;

                const productId = this.getAttribute('data-product-id');
                const quantityValue = document.querySelector('.quantity-value');
                const quantity = quantityValue ? parseInt(quantityValue.textContent) : 1;

                // Get selected options
                const selectedOptions = getSelectedOptions();

                // Add to cart via AJAX
                addProductToCart(productId, quantity, selectedOptions);
            });
        }

        if (buyNowBtn) {
            buyNowBtn.addEventListener('click', function() {
                if (this.disabled) return;

                const productId = this.getAttribute('data-product-id');
                const quantityValue = document.querySelector('.quantity-value');
                const quantity = quantityValue ? parseInt(quantityValue.textContent) : 1;

                // Get selected options
                const selectedOptions = getSelectedOptions();

                // Add to cart and redirect to checkout
                addProductToCart(productId, quantity, selectedOptions, true);
            });
        }
    }

    /**
     * Get selected product options
     */
    function getSelectedOptions() {
        const options = {};

        // Get selected variations (for variable products)
        const activeOptionBtns = document.querySelectorAll('.option-btn.active');
        activeOptionBtns.forEach(function(btn) {
            const parent = btn.closest('.option-btns');
            if (parent) {
                const attribute = parent.getAttribute('data-attribute');
                const value = btn.getAttribute('data-value') || btn.textContent.trim();
                if (attribute) {
                    options[attribute] = value;
                }
            }
        });

        // Get selected color
        const activeColorBtn = document.querySelector('.color-btn.active');
        if (activeColorBtn) {
            options.color = activeColorBtn.getAttribute('title') || '';
        }

        return options;
    }

    /**
     * Add product to cart via AJAX
     */
    function addProductToCart(productId, quantity, options, buyNow) {
        if (typeof aakaariAjax === 'undefined') {
            console.error('aakaariAjax is not defined');
            alert('Unable to add product to cart. Please refresh the page.');
            return;
        }

        $.ajax({
            type: 'POST',
            url: aakaariAjax.ajaxUrl,
            data: {
                action: 'aakaari_add_to_cart',
                product_id: productId,
                quantity: quantity,
                options: options,
                nonce: aakaariAjax.nonce
            },
            beforeSend: function() {
                $('.add-cart-btn, .buy-btn').prop('disabled', true).css('opacity', '0.6');
            },
            success: function(response) {
                if (response.success) {
                    // Update cart count in header
                    if (response.data.cart_count) {
                        $('.cart-count').text(response.data.cart_count);
                    }

                    // Show success message
                    showNotification('Product added to cart!', 'success');

                    // Redirect to checkout if buy now
                    if (buyNow) {
                        setTimeout(function() {
                            window.location.href = aakaariAjax.checkoutUrl || '/checkout';
                        }, 500);
                    }
                } else {
                    showNotification(response.data.message || 'Failed to add product to cart', 'error');
                }
            },
            error: function() {
                showNotification('Failed to add product to cart', 'error');
            },
            complete: function() {
                $('.add-cart-btn, .buy-btn').prop('disabled', false).css('opacity', '1');
            }
        });
    }

    /**
     * Show notification message
     */
    function showNotification(message, type) {
        // Remove existing notifications
        $('.product-notification').remove();

        // Create notification element
        const notification = $('<div class="product-notification ' + type + '">' + message + '</div>');
        $('body').append(notification);

        // Add CSS for notification
        if ($('#product-notification-styles').length === 0) {
            $('head').append('<style id="product-notification-styles">' +
                '.product-notification { position: fixed; top: 100px; right: 20px; padding: 16px 24px; ' +
                'background: #10b981; color: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); ' +
                'z-index: 9999; animation: slideInRight 0.3s ease; }' +
                '.product-notification.error { background: #ef4444; }' +
                '@keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } ' +
                'to { transform: translateX(0); opacity: 1; } }' +
                '</style>');
        }

        // Auto remove after 3 seconds
        setTimeout(function() {
            notification.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }

    /**
     * Initialize related products carousel navigation
     */
    function initRelatedProductsCarousel() {
        const carousel = document.querySelector('.related-section .product-carousel');

        if (!carousel) {
            return;
        }

        // Touch support for mobile
        let isDown = false;
        let startX;
        let scrollLeft;

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

        // Set initial cursor
        carousel.style.cursor = 'grab';
    }

})(jQuery);
