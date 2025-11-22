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
        initTabs();
        initReviewForm();
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

                // Update variation data (image, price, etc.)
                updateVariationData();
            });
        });

        // Color buttons
        const colorBtns = document.querySelectorAll('.color-btn');
        colorBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                // Get all color buttons in the same group
                const parent = this.closest('.color-btns');
                if (parent) {
                    const siblings = parent.querySelectorAll('.color-btn');
                    siblings.forEach(function(sibling) {
                        sibling.classList.remove('active');
                    });
                }

                // Add active class to clicked button
                this.classList.add('active');

                // Update variation data (image, price, etc.)
                updateVariationData();
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

        // Load initial variation data if this is a variable product
        if (firstOptionBtn || firstColorBtn) {
            updateVariationData();
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

        // Get selected color (for both variable and simple products)
        const activeColorBtn = document.querySelector('.color-btn.active');
        if (activeColorBtn) {
            // Check if this is part of a variable product (inside options-section)
            const colorBtnsParent = activeColorBtn.closest('.color-btns');
            if (colorBtnsParent) {
                const attribute = colorBtnsParent.getAttribute('data-attribute');
                const value = activeColorBtn.getAttribute('data-value') || activeColorBtn.getAttribute('title') || '';
                if (attribute) {
                    options[attribute] = value;
                } else {
                    // For simple products, just use 'color' as key
                    options.color = value;
                }
            }
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
                    console.error('Add to cart failed:', response);
                    showNotification(response.data.message || 'Failed to add product to cart', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error);
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
     * Initialize tabs functionality
     */
    function initTabs() {
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        if (tabBtns.length === 0) {
            return;
        }

        tabBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const tabName = this.getAttribute('data-tab');

                // Remove active class from all tabs and contents
                tabBtns.forEach(function(b) {
                    b.classList.remove('active');
                });
                tabContents.forEach(function(content) {
                    content.classList.remove('active');
                });

                // Add active class to clicked tab and corresponding content
                this.classList.add('active');
                const targetContent = document.getElementById('tab-' + tabName);
                if (targetContent) {
                    targetContent.classList.add('active');
                }
            });
        });
    }

    /**
     * Initialize review form submission
     */
    function initReviewForm() {
        const reviewForm = document.getElementById('reviewForm');

        if (!reviewForm) {
            return;
        }

        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();

            if (typeof aakaariAjax === 'undefined') {
                console.error('aakaariAjax is not defined');
                alert('Unable to submit review. Please refresh the page.');
                return;
            }

            // Get form data
            const formData = new FormData(this);
            const name = formData.get('name');
            const email = formData.get('email');
            const rating = formData.get('rating');
            const comment = formData.get('comment');
            const productId = formData.get('product_id');

            // Validate rating
            if (!rating) {
                showNotification('Please select a rating', 'error');
                return;
            }

            // Submit via AJAX
            $.ajax({
                type: 'POST',
                url: aakaariAjax.ajaxUrl,
                data: {
                    action: 'aakaari_submit_review',
                    product_id: productId,
                    name: name,
                    email: email,
                    rating: rating,
                    comment: comment,
                    nonce: aakaariAjax.nonce
                },
                beforeSend: function() {
                    $('.submit-review-btn').prop('disabled', true).text('Submitting...');
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('Review submitted successfully! It will appear after approval.', 'success');
                        reviewForm.reset();

                        // Uncheck all rating stars
                        const ratingInputs = document.querySelectorAll('.rating-input input[type="radio"]');
                        ratingInputs.forEach(function(input) {
                            input.checked = false;
                        });
                    } else {
                        showNotification(response.data.message || 'Failed to submit review', 'error');
                    }
                },
                error: function() {
                    showNotification('Failed to submit review. Please try again.', 'error');
                },
                complete: function() {
                    $('.submit-review-btn').prop('disabled', false).text('Submit Review');
                }
            });
        });
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

    /**
     * Update variation data (image, price, stock) based on selected attributes
     */
    function updateVariationData() {
        // Check if this is a variable product
        const optionsSection = document.querySelector('.options-section');
        if (!optionsSection) {
            return;
        }

        const quantityValue = document.querySelector('.quantity-value');
        if (!quantityValue) {
            return;
        }

        const productId = quantityValue.getAttribute('data-product-id');
        if (!productId) {
            return;
        }

        // Get selected attributes
        const selectedAttributes = getSelectedOptions();

        // Check if all required attributes are selected
        const attributeGroups = document.querySelectorAll('.option-btns, .color-btns');
        let allSelected = true;

        attributeGroups.forEach(function(group) {
            const attribute = group.getAttribute('data-attribute');
            if (attribute && !selectedAttributes[attribute]) {
                allSelected = false;
            }
        });

        if (!allSelected) {
            return;
        }

        // Make AJAX call to get variation data
        $.ajax({
            type: 'POST',
            url: aakaariAjax.ajaxUrl,
            data: {
                action: 'aakaari_get_variation_data',
                product_id: productId,
                attributes: selectedAttributes,
                nonce: aakaariAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    const data = response.data;

                    // Update main image if variation has an image
                    if (data.image_url) {
                        const mainImage = document.getElementById('mainProductImage');
                        if (mainImage) {
                            mainImage.src = data.image_url;
                        }
                    }

                    // Update thumbnail gallery
                    if (data.gallery_images && data.gallery_images.length > 0) {
                        const thumbnailList = document.querySelector('.thumbnail-list');

                        if (thumbnailList) {
                            // Clear existing thumbnails
                            thumbnailList.innerHTML = '';

                            // Add new thumbnails
                            data.gallery_images.forEach(function(image, index) {
                                const button = document.createElement('button');
                                button.className = 'thumbnail-btn' + (index === 0 ? ' active' : '');
                                button.setAttribute('data-image', image.large);

                                const img = document.createElement('img');
                                img.src = image.thumbnail;
                                img.alt = '';

                                button.appendChild(img);
                                thumbnailList.appendChild(button);

                                // Add click event
                                button.addEventListener('click', function() {
                                    const mainImage = document.getElementById('mainProductImage');
                                    const allThumbnails = document.querySelectorAll('.thumbnail-btn');

                                    allThumbnails.forEach(function(thumb) {
                                        thumb.classList.remove('active');
                                    });

                                    button.classList.add('active');

                                    const newImageSrc = button.getAttribute('data-image');
                                    if (newImageSrc && mainImage) {
                                        mainImage.src = newImageSrc;
                                    }
                                });
                            });
                        }
                    }

                    // Update price
                    if (data.price_html) {
                        const priceElement = document.querySelector('.price-row .price');
                        if (priceElement) {
                            priceElement.innerHTML = data.price_html;
                        }
                    }

                    // Update stock status
                    if (data.stock_html) {
                        const stockElement = document.querySelector('.stock-text');
                        if (stockElement) {
                            stockElement.textContent = data.stock_html;
                        }
                    }

                    // Update add to cart button state
                    const addToCartBtn = document.querySelector('.add-cart-btn');
                    const buyNowBtn = document.querySelector('.buy-btn');

                    if (data.is_in_stock) {
                        if (addToCartBtn) {
                            addToCartBtn.disabled = false;
                            addToCartBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> Add to Cart';
                        }
                        if (buyNowBtn) {
                            buyNowBtn.disabled = false;
                        }
                    } else {
                        if (addToCartBtn) {
                            addToCartBtn.disabled = true;
                            addToCartBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> Out of Stock';
                        }
                        if (buyNowBtn) {
                            buyNowBtn.disabled = true;
                        }
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error getting variation data:', status, error);
            }
        });
    }

})(jQuery);
