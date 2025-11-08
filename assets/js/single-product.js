/**
 * Single Product Page - Amazon/Flipkart Inspired Design
 * Enhanced functionality for improved UX
 */

document.addEventListener('DOMContentLoaded', () => {

    // ===================================
    // STATE VARIABLES
    // ===================================
    let currentImageIndex = 0;
    let quantity = 1;
    let inWishlist = false;
    let currentTab = 'description';

    // ===================================
    // DOM ELEMENT SELECTORS
    // ===================================
    const mainImage = document.getElementById('main-image');
    const thumbnailList = document.getElementById('thumbnail-list');
    const qtyDecreaseBtn = document.getElementById('qty-decrease');
    const qtyIncreaseBtn = document.getElementById('qty-increase');
    const quantityDisplay = document.getElementById('quantity-display');
    const wishlistBtn = document.getElementById('wishlist-btn');
    const wishlistIcon = document.getElementById('wishlist-icon-svg');
    const addToCartBtn = document.getElementById('add-to-cart-btn');
    const buyNowBtn = document.getElementById('buy-now-btn');
    const shareBtn = document.getElementById('share-btn');
    const pincodeInput = document.getElementById('pincode-input');
    const checkPincodeBtn = document.getElementById('check-pincode-btn');
    const deliveryInfo = document.getElementById('delivery-info');
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanels = document.querySelectorAll('.tab-panel');

    // Get max quantity from stock info or default
    const stockInfo = document.getElementById('stock-info');
    const stockText = stockInfo ? stockInfo.textContent.trim() : '';
    const stockMatch = stockText.match(/^(\d+)/);
    const maxQuantity = stockMatch ? parseInt(stockMatch[1]) : 9999;

    // ===================================
    // IMAGE GALLERY
    // ===================================

    if (thumbnailList && mainImage) {
        const thumbnailButtons = thumbnailList.querySelectorAll('.thumbnail-btn');

        thumbnailButtons.forEach((btn, index) => {
            btn.addEventListener('click', () => selectImage(index, btn));
        });
    }

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

    // ===================================
    // IMAGE ZOOM EFFECT
    // ===================================

    if (mainImage && mainImage.classList.contains('zoomable-image')) {
        const imageWrapper = mainImage.parentElement;

        imageWrapper.addEventListener('mousemove', (e) => {
            const rect = imageWrapper.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;

            mainImage.style.transformOrigin = `${x}% ${y}%`;
        });

        imageWrapper.addEventListener('mouseleave', () => {
            mainImage.style.transformOrigin = 'center center';
        });
    }

    // ===================================
    // QUANTITY CONTROLS
    // ===================================

    if (qtyDecreaseBtn && qtyIncreaseBtn && quantityDisplay) {
        qtyDecreaseBtn.addEventListener('click', () => updateQuantity(-1));
        qtyIncreaseBtn.addEventListener('click', () => updateQuantity(1));
    }

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

    // ===================================
    // WISHLIST
    // ===================================

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
                    showNotification(message);
                }
            });
        } else {
            // Fallback - just toggle UI
            inWishlist = !inWishlist;
            wishlistBtn.classList.toggle('active', inWishlist);
            wishlistIcon.classList.toggle('filled', inWishlist);

            const message = inWishlist ? 'Added to wishlist' : 'Removed from wishlist';
            showNotification(message);
        }
    }

    // ===================================
    // SHARE BUTTON
    // ===================================

    if (shareBtn) {
        shareBtn.addEventListener('click', handleShare);
    }

    function handleShare(e) {
        e.preventDefault();

        const shareData = {
            title: document.getElementById('product-name')?.textContent || 'Check out this product',
            text: document.querySelector('.description')?.textContent || '',
            url: window.location.href
        };

        // Check if Web Share API is supported
        if (navigator.share) {
            navigator.share(shareData)
                .then(() => showNotification('Shared successfully!'))
                .catch((error) => {
                    if (error.name !== 'AbortError') {
                        fallbackShare();
                    }
                });
        } else {
            fallbackShare();
        }
    }

    function fallbackShare() {
        // Copy URL to clipboard
        navigator.clipboard.writeText(window.location.href)
            .then(() => showNotification('Link copied to clipboard!'))
            .catch(() => showNotification('Unable to share'));
    }

    // ===================================
    // PINCODE CHECKER
    // ===================================

    if (checkPincodeBtn && pincodeInput && deliveryInfo) {
        checkPincodeBtn.addEventListener('click', checkPincode);
        pincodeInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                checkPincode();
            }
        });

        // Only allow numbers in pincode
        pincodeInput.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });
    }

    function checkPincode() {
        const pincode = pincodeInput.value.trim();

        if (pincode.length !== 6) {
            showNotification('Please enter a valid 6-digit pincode');
            return;
        }

        // Show loading state
        checkPincodeBtn.textContent = 'Checking...';
        checkPincodeBtn.disabled = true;

        // Simulate API call (replace with actual API in production)
        setTimeout(() => {
            // Calculate delivery date (3-5 days from now)
            const deliveryDate = new Date();
            deliveryDate.setDate(deliveryDate.getDate() + Math.floor(Math.random() * 3) + 3);

            const options = { weekday: 'short', month: 'short', day: 'numeric' };
            const dateText = deliveryDate.toLocaleDateString('en-US', options);

            // Update UI
            document.getElementById('delivery-date-text').textContent = dateText;
            deliveryInfo.style.display = 'block';

            checkPincodeBtn.textContent = 'Change';
            checkPincodeBtn.disabled = false;

            showNotification('Delivery available!');
        }, 1000);
    }

    // ===================================
    // TAB SWITCHING
    // ===================================

    if (tabBtns.length > 0 && tabPanels.length > 0) {
        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const tabName = btn.dataset.tab;
                switchTab(tabName);
            });
        });
    }

    function switchTab(tabName) {
        currentTab = tabName;

        // Update button states
        tabBtns.forEach(btn => {
            btn.classList.toggle('active', btn.dataset.tab === tabName);
        });

        // Update panel visibility
        tabPanels.forEach(panel => {
            panel.classList.toggle('active', panel.id === `${tabName}-tab`);
        });

        // Smooth scroll to tabs section
        const tabsSection = document.querySelector('.product-tabs-section');
        if (tabsSection) {
            tabsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // ===================================
    // SIZE & COLOR SELECTION
    // ===================================

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

    // ===================================
    // BUY NOW BUTTON
    // ===================================

    if (buyNowBtn) {
        buyNowBtn.addEventListener('click', handleBuyNow);
    }

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
        const originalText = buyNowBtn.textContent;
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
            buyNowBtn.textContent = originalText;
            showNotification('Error processing request');
        });
    }

    // ===================================
    // ADD TO CART VALIDATION
    // ===================================

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
                    showNotification('Please select a size');
                    return false;
                }

                if (selectedColorInput && selectedColorInput.value === '') {
                    e.preventDefault();
                    showNotification('Please select a color');
                    return false;
                }

                // Show loading state
                addToCartBtn.disabled = true;
                const originalText = addToCartBtn.innerHTML;
                addToCartBtn.innerHTML = '<span>Adding...</span>';

                // Re-enable after delay (WooCommerce handles the actual submission)
                setTimeout(() => {
                    addToCartBtn.disabled = false;
                    addToCartBtn.innerHTML = originalText;
                }, 2000);
            });
        }
    }

    // ===================================
    // WOOCOMMERCE VARIATIONS
    // ===================================

    if (typeof jQuery !== 'undefined') {
        const $ = jQuery;
        const $form = $('form.variations_form');

        if ($form.length) {
            // WooCommerce variations form
            $form.on('found_variation', function(event, variation) {
                // Update price
                if (variation.display_price) {
                    const priceEl = document.getElementById('product-price');
                    if (priceEl) {
                        priceEl.textContent = '₹' + parseFloat(variation.display_price).toFixed(2);
                    }
                }

                // Update stock
                const stockEl = document.getElementById('stock-info');
                if (stockEl) {
                    const checkIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';

                    if (variation.is_in_stock) {
                        if (variation.max_qty) {
                            stockEl.innerHTML = checkIcon + ' ' + variation.max_qty + ' in stock';
                        } else {
                            stockEl.innerHTML = checkIcon + ' In stock';
                        }
                        stockEl.style.color = '#26a541';

                        if (addToCartBtn) addToCartBtn.disabled = false;
                        if (buyNowBtn) buyNowBtn.disabled = false;
                    } else {
                        stockEl.innerHTML = 'Out of stock';
                        stockEl.style.color = '#ff6161';

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

    // ===================================
    // STAR RATING SELECTOR FOR REVIEWS
    // ===================================

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
                        s.style.color = '#ffa41c';
                    }
                });
            });

            star.addEventListener('mouseleave', function() {
                const currentRating = ratingInput.value;
                stars.forEach((s, index) => {
                    if (index < currentRating) {
                        s.style.color = '#ffa41c';
                    } else {
                        s.style.color = '#ddd';
                    }
                });
            });
        });

        // Add validation for review form
        const reviewForm = document.querySelector('.comment-form');
        if (reviewForm) {
            reviewForm.addEventListener('submit', function(e) {
                if (!ratingInput.value || ratingInput.value === '') {
                    e.preventDefault();
                    showNotification('Please select a rating by clicking on the stars');
                    return false;
                }
            });
        }
    }

    // ===================================
    // SCROLL TO REVIEWS FROM RATING
    // ===================================

    const ratingRow = document.querySelector('.rating-row');
    if (ratingRow) {
        ratingRow.style.cursor = 'pointer';
        ratingRow.addEventListener('click', () => {
            switchTab('reviews');
        });
    }

    // ===================================
    // UTILITY FUNCTIONS
    // ===================================

    /**
     * Show notification/toast message
     */
    function showNotification(message) {
        // Check if custom toast function exists
        if (typeof showToast === 'function') {
            showToast(message);
        } else if (typeof AakaariToast !== 'undefined') {
            AakaariToast.show(message);
        } else {
            // Fallback to simple notification
            const notification = document.createElement('div');
            notification.textContent = message;
            notification.style.cssText = `
                position: fixed;
                bottom: 2rem;
                left: 50%;
                transform: translateX(-50%);
                background: #212121;
                color: white;
                padding: 1rem 2rem;
                border-radius: 4px;
                z-index: 10000;
                font-size: 0.9375rem;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
                animation: slideUp 0.3s ease;
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideDown 0.3s ease';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        console.log(message);
    }

    // ===================================
    // KEYBOARD NAVIGATION
    // ===================================

    document.addEventListener('keydown', (e) => {
        // Arrow keys for image navigation
        if (thumbnailList && mainImage) {
            const thumbnailButtons = thumbnailList.querySelectorAll('.thumbnail-btn');
            const totalImages = thumbnailButtons.length;

            if (e.key === 'ArrowLeft' && currentImageIndex > 0) {
                selectImage(currentImageIndex - 1, thumbnailButtons[currentImageIndex - 1]);
            } else if (e.key === 'ArrowRight' && currentImageIndex < totalImages - 1) {
                selectImage(currentImageIndex + 1, thumbnailButtons[currentImageIndex + 1]);
            }
        }
    });

    // ===================================
    // PERFORMANCE OPTIMIZATION
    // ===================================

    // Lazy load related product images
    if ('IntersectionObserver' in window) {
        const lazyImages = document.querySelectorAll('.related-product-card img');
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                    }
                    imageObserver.unobserve(img);
                }
            });
        });

        lazyImages.forEach(img => imageObserver.observe(img));
    }

    // ===================================
    // ADD CSS ANIMATIONS
    // ===================================

    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideUp {
            from {
                transform: translateX(-50%) translateY(100%);
                opacity: 0;
            }
            to {
                transform: translateX(-50%) translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                transform: translateX(-50%) translateY(0);
                opacity: 1;
            }
            to {
                transform: translateX(-50%) translateY(100%);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

    // ===================================
    // INITIALIZATION COMPLETE
    // ===================================

    console.log('Single product page initialized with enhanced features');
});
