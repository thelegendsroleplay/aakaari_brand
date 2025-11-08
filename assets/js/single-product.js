/**
 * Single Product Page - WooCommerce Integration
 * Matching provided HTML specification
 */

document.addEventListener('DOMContentLoaded', () => {

    // --- STATE VARIABLES ---
    let currentImageIndex = 0;
    let quantity = 1;
    let inWishlist = false;

    // --- DOM ELEMENT SELECTORS ---
    const mainImage = document.getElementById('main-image');
    const thumbnailList = document.getElementById('thumbnail-list');
    const qtyDecreaseBtn = document.getElementById('qty-decrease');
    const qtyIncreaseBtn = document.getElementById('qty-increase');
    const quantityDisplay = document.getElementById('quantity-display');
    const wishlistBtn = document.getElementById('wishlist-btn');
    const wishlistIcon = document.getElementById('wishlist-icon-svg');
    const addToCartBtn = document.getElementById('add-to-cart-btn');
    const buyNowBtn = document.getElementById('buy-now-btn');
    const backBtn = document.getElementById('back-btn');

    // Get max quantity from stock info or default
    const stockInfo = document.getElementById('stock-info');
    const stockText = stockInfo ? stockInfo.textContent.trim() : '';
    const stockMatch = stockText.match(/^(\d+)/);
    const maxQuantity = stockMatch ? parseInt(stockMatch[1]) : 9999;

    // --- INITIALIZATION ---

    // Initialize image gallery
    if (thumbnailList && mainImage) {
        const thumbnailButtons = thumbnailList.querySelectorAll('.thumbnail-btn');

        thumbnailButtons.forEach((btn, index) => {
            btn.addEventListener('click', () => selectImage(index, btn));
        });
    }

    // Initialize quantity controls
    if (qtyDecreaseBtn && qtyIncreaseBtn && quantityDisplay) {
        qtyDecreaseBtn.addEventListener('click', () => updateQuantity(-1));
        qtyIncreaseBtn.addEventListener('click', () => updateQuantity(1));
    }

    // Initialize wishlist
    if (wishlistBtn) {
        // Check if product is in wishlist on page load
        const productId = wishlistBtn.dataset.productId;
        if (productId && typeof aakaari_get_wishlist === 'function') {
            const wishlist = aakaari_get_wishlist();
            if (wishlist.includes(productId)) {
                inWishlist = true;
                wishlistBtn.classList.add('active');
                wishlistIcon.classList.add('filled');
            }
        }

        wishlistBtn.addEventListener('click', toggleWishlist);
    }

    // Initialize Buy Now button
    if (buyNowBtn) {
        buyNowBtn.addEventListener('click', handleBuyNow);
    }

    // Initialize Back button
    if (backBtn) {
        backBtn.addEventListener('click', () => {
            window.history.back();
        });
    }

    // Initialize Size/Color selection for variable products
    const sizeButtons = document.querySelectorAll('.size-btn');
    const colorButtons = document.querySelectorAll('.color-btn');
    const selectedSizeInput = document.getElementById('selected-size');
    const selectedColorInput = document.getElementById('selected-color');

    if (sizeButtons.length > 0) {
        sizeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all size buttons
                sizeButtons.forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                // Update hidden input
                if (selectedSizeInput) {
                    selectedSizeInput.value = this.dataset.size;
                }
            });
        });
    }

    if (colorButtons.length > 0) {
        colorButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all color buttons
                colorButtons.forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                // Update hidden input
                if (selectedColorInput) {
                    selectedColorInput.value = this.dataset.color;
                }
            });
        });
    }

    // --- EVENT HANDLER FUNCTIONS ---

    /**
     * Select main image
     */
    function selectImage(index, clickedBtn) {
        if (!mainImage || !thumbnailList) return;

        currentImageIndex = index;

        // Get the image URL from the clicked button
        const imageUrl = clickedBtn.dataset.image;
        if (imageUrl) {
            mainImage.src = imageUrl;
        }

        // Update active class on thumbnails
        const thumbnailButtons = thumbnailList.querySelectorAll('.thumbnail-btn');
        thumbnailButtons.forEach((btn, i) => {
            btn.classList.toggle('active', i === index);
        });
    }

    /**
     * Update quantity
     */
    function updateQuantity(change) {
        const newQuantity = quantity + change;

        if (newQuantity >= 1 && newQuantity <= maxQuantity) {
            quantity = newQuantity;
            quantityDisplay.textContent = quantity;

            // Update WooCommerce hidden quantity input
            const qtyInput = document.querySelector('input.qty');
            if (qtyInput) {
                qtyInput.value = quantity;
            }
        }
    }

    /**
     * Toggle wishlist
     */
    function toggleWishlist(e) {
        e.preventDefault();

        const productId = wishlistBtn.dataset.productId;
        if (!productId) return;

        // Check if we have custom wishlist functions
        if (typeof aakaari_toggle_wishlist === 'function') {
            aakaari_toggle_wishlist(productId, function(success, isInWishlist) {
                if (success) {
                    inWishlist = isInWishlist;
                    wishlistBtn.classList.toggle('active', inWishlist);
                    wishlistIcon.classList.toggle('filled', inWishlist);

                    const message = inWishlist ? 'Added to wishlist' : 'Removed from wishlist';
                    console.log(message);

                    // Show toast notification if available
                    if (typeof showToast === 'function') {
                        showToast(message);
                    } else if (typeof AakaariToast !== 'undefined') {
                        AakaariToast.show(message);
                    }
                }
            });
        } else {
            // Fallback - just toggle UI
            inWishlist = !inWishlist;
            wishlistBtn.classList.toggle('active', inWishlist);
            wishlistIcon.classList.toggle('filled', inWishlist);

            const message = inWishlist ? 'Added to wishlist' : 'Removed from wishlist';
            console.log(message);
        }
    }

    /**
     * Handle Buy Now button
     */
    function handleBuyNow(e) {
        e.preventDefault();

        // First, add the product to cart
        const form = document.querySelector('form.cart');
        if (!form) {
            console.error('Cart form not found');
            return;
        }

        // Update quantity in form
        const qtyInput = form.querySelector('input[name="quantity"]');
        if (qtyInput) {
            qtyInput.value = quantity;
        }

        // Submit the form to add to cart
        const formData = new FormData(form);

        // Disable button
        buyNowBtn.disabled = true;
        buyNowBtn.textContent = 'Processing...';

        // Submit via AJAX
        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            // After adding to cart, redirect to checkout
            if (typeof wc_single_product_params !== 'undefined' && wc_single_product_params.checkout_url) {
                window.location.href = wc_single_product_params.checkout_url;
            } else {
                // Fallback checkout URL
                window.location.href = '/checkout';
            }
        })
        .catch(error => {
            console.error('Buy now error:', error);
            buyNowBtn.disabled = false;
            buyNowBtn.textContent = 'Buy Now';
        });
    }

    // --- WOOCOMMERCE INTEGRATION ---

    /**
     * Handle WooCommerce add to cart
     */
    if (addToCartBtn) {
        const form = document.querySelector('form.cart');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Update quantity before submission
                const qtyInput = form.querySelector('input[name="quantity"]');
                if (qtyInput) {
                    qtyInput.value = quantity;
                }

                // Validate size/color selection for variable products
                if (selectedSizeInput && selectedSizeInput.value === '') {
                    e.preventDefault();
                    alert('Please select a size');
                    return false;
                }

                if (selectedColorInput && selectedColorInput.value === '') {
                    e.preventDefault();
                    alert('Please select a color');
                    return false;
                }
            });
        }
    }

    /**
     * Handle WooCommerce variations (if variable product)
     */
    if (typeof jQuery !== 'undefined') {
        const $ = jQuery;
        const $form = $('form.variations_form');

        if ($form.length) {
            // WooCommerce variations form
            $form.on('found_variation', function(event, variation) {
                // Update price
                if (variation.display_price) {
                    const priceEl = document.getElementById('product-price');
                    if (priceEl && variation.price_html) {
                        // Extract price from HTML
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = variation.price_html;
                        const priceText = tempDiv.textContent || tempDiv.innerText;
                        priceEl.textContent = priceText;
                    }
                }

                // Update stock
                const stockEl = document.getElementById('stock-info');
                if (stockEl) {
                    if (variation.is_in_stock) {
                        if (variation.max_qty) {
                            stockEl.textContent = variation.max_qty + ' in stock';
                        } else {
                            stockEl.textContent = 'In stock';
                        }
                        stockEl.style.color = '#10b981';

                        if (addToCartBtn) addToCartBtn.disabled = false;
                        if (buyNowBtn) buyNowBtn.disabled = false;
                    } else {
                        stockEl.textContent = 'Out of stock';
                        stockEl.style.color = '#e53e3e';

                        if (addToCartBtn) addToCartBtn.disabled = true;
                        if (buyNowBtn) buyNowBtn.disabled = true;
                    }
                }

                // Update image if variation has one
                if (variation.image && variation.image.url && mainImage) {
                    mainImage.src = variation.image.url;
                }
            });

            $form.on('reset_data', function() {
                // Reset to default when variation is cleared
                // This is handled by WooCommerce
            });
        }
    }

    /**
     * Star Rating Selector for Review Form
     */
    const stars = document.querySelectorAll('.star-rating-selector .star');
    const ratingInput = document.getElementById('rating');

    if (stars.length > 0 && ratingInput) {
        stars.forEach(star => {
            // Click event
            star.addEventListener('click', function() {
                const rating = this.dataset.rating;
                ratingInput.value = rating;

                // Update star appearance
                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
            });

            // Hover effect
            star.addEventListener('mouseenter', function() {
                const rating = this.dataset.rating;
                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.style.color = '#fbbf24';
                    }
                });
            });

            star.addEventListener('mouseleave', function() {
                const currentRating = ratingInput.value;
                stars.forEach((s, index) => {
                    if (index < currentRating) {
                        s.style.color = '#fbbf24';
                    } else {
                        s.style.color = '#d1d5db';
                    }
                });
            });
        });
    }

});
