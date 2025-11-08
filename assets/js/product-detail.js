document.addEventListener('DOMContentLoaded', () => {
    const productPage = document.querySelector('.product-page');
    if (!productPage) {
        return; // Exit if not on the product page
    }

    // --- 1. Image Gallery ---
    const mainImage = document.getElementById('main-image');
    const thumbnails = document.getElementById('thumbnail-list');
    
    if (mainImage && thumbnails) {
        thumbnails.addEventListener('click', (e) => {
            const thumbnailBtn = e.target.closest('.thumbnail-btn');
            if (!thumbnailBtn) return;

            // Get new image source
            const newSrc = thumbnailBtn.dataset.imageSrc;
            if (newSrc) {
                mainImage.setAttribute('src', newSrc);
            }

            // Update active state
            thumbnails.querySelector('.thumbnail-btn.active')?.classList.remove('active');
            thumbnailBtn.classList.add('active');
        });
    }

    // --- 2. Quantity Selector ---
    const qtyDecrease = productPage.querySelector('.qty-decrease');
    const qtyIncrease = productPage.querySelector('.qty-increase');
    const qtyDisplay = productPage.querySelector('.quantity-display');
    const qtyInput = document.getElementById('quantity_input'); // The hidden WC input

    if (qtyDecrease && qtyIncrease && qtyDisplay && qtyInput) {
        qtyDecrease.addEventListener('click', () => {
            let currentVal = parseInt(qtyInput.value, 10);
            if (currentVal > 1) {
                currentVal--;
                qtyInput.value = currentVal;
                qtyDisplay.textContent = currentVal;
            }
        });

        qtyIncrease.addEventListener('click', () => {
            let currentVal = parseInt(qtyInput.value, 10);
            const maxVal = parseInt(qtyInput.max, 10) || Infinity;
            if (currentVal < maxVal) {
                currentVal++;
                qtyInput.value = currentVal;
                qtyDisplay.textContent = currentVal;
            }
        });
    }

    // --- 3. Variable Product Logic ---
    const cartForm = productPage.querySelector('form.cart');
    if (cartForm && cartForm.dataset.product_variations) {
        const variations = JSON.parse(cartForm.dataset.product_variations);
        const attributeButtons = cartForm.querySelectorAll('.option-btns button, .color-btns button');
        const variationIdInput = cartForm.querySelector('.variation_id');
        const priceEl = document.getElementById('product-price');
        const oldPriceEl = document.getElementById('product-old-price');
        const stockEl = document.getElementById('stock-info');
        const skuEl = document.getElementById('product-sku');
        const discountBadge = document.getElementById('discount-badge');
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        const buyNowBtn = document.getElementById('buy-now-btn');

        const updateVariation = () => {
            // Collect selected attributes
            const selectedAttributes = {};
            cartForm.querySelectorAll('.option-row').forEach(row => {
                const attrName = row.querySelector('[data-attribute-name]').dataset.attributeName;
                const hiddenInput = document.getElementById(`selected-${attrName.replace(/^attribute_/, '')}`);
                if (hiddenInput && hiddenInput.value) {
                    selectedAttributes[attrName] = hiddenInput.value;
                }
            });

            // Find a matching variation
            const matchingVariation = variations.find(v => {
                return Object.entries(selectedAttributes).every(([key, value]) => {
                    return v.attributes[key] === value;
                });
            });

            if (matchingVariation) {
                // --- Update UI with variation data ---
                variationIdInput.value = matchingVariation.variation_id;

                // Price
                priceEl.innerHTML = matchingVariation.price_html.replace('<span class="woocommerce-Price-amount amount"><bdi>', '₹').replace('</bdi></span>', '');
                
                // Extract clean prices for discount calculation
                const currentPrice = parseFloat(matchingVariation.display_price);
                const regularPrice = parseFloat(matchingVariation.display_regular_price);
                
                if (matchingVariation.display_price !== matchingVariation.display_regular_price) {
                    oldPriceEl.innerHTML = '₹' + regularPrice.toFixed(2);
                    oldPriceEl.style.display = 'inline';
                    
                    const discount = Math.round(((regularPrice - currentPrice) / regularPrice) * 100);
                    discountBadge.innerHTML = `-${discount}%`;
                    discountBadge.style.display = 'inline-block';
                } else {
                    oldPriceEl.style.display = 'none';
                    discountBadge.style.display = 'none';
                }

                // Stock
                if (matchingVariation.is_in_stock) {
                    stockEl.textContent = matchingVariation.availability_html || 'In stock';
                    stockEl.style.color = '#10b981'; // Green
                    addToCartBtn.disabled = false;
                    buyNowBtn.disabled = false;
                } else {
                    stockEl.textContent = 'Out of stock';
                    stockEl.style.color = '#e53e3e'; // Red
                    addToCartBtn.disabled = true;
                    buyNowBtn.disabled = true;
                }
                
                // Image
                if (matchingVariation.image && matchingVariation.image.src) {
                    mainImage.setAttribute('src', matchingVariation.image.src);
                }

                // SKU
                if (skuEl && matchingVariation.sku) {
                    skuEl.textContent = matchingVariation.sku;
                }

            } else {
                // No full match found, reset
                variationIdInput.value = '';
                // You might want to reset price/stock here or leave as is
                // For now, just disable cart
                addToCartBtn.disabled = true;
                buyNowBtn.disabled = true;
            }
        };

        attributeButtons.forEach(button => {
            button.addEventListener('click', () => {
                const group = button.closest('[data-attribute-name]');
                const attrName = group.dataset.attributeName;
                const attrValue = button.dataset.value;
                const hiddenInput = document.getElementById(`selected-${attrName.replace(/^attribute_/, '')}`);

                // Toggle active state
                group.querySelectorAll('button').forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                // Update hidden input
                hiddenInput.value = attrValue;
                
                // Check for a match
                updateVariation();
            });
        });
    }

    // --- 4. Buy Now Button ---
    const buyNowBtn = document.getElementById('buy-now-btn');
    if (buyNowBtn && cartForm) {
        buyNowBtn.addEventListener('click', () => {
            // Check if "Buy Now" hidden input already exists
            let buyNowInput = cartForm.querySelector('input[name="aakaari_buy_now"]');
            
            if (!buyNowInput) {
                buyNowInput = document.createElement('input');
                buyNowInput.type = 'hidden';
                buyNowInput.name = 'aakaari_buy_now';
                buyNowInput.value = '1';
                cartForm.appendChild(buyNowInput);
            }
            
            // Submit the form
            cartForm.submit();
        });
    }

    // --- 5. Review Form Stars ---
    const reviewForm = document.querySelector('.comment-form');
    if (reviewForm) {
        const ratingStars = reviewForm.querySelectorAll('.star-rating-selector .star');
        const ratingInput = reviewForm.querySelector('#rating');
        
        ratingStars.forEach(star => {
            star.addEventListener('click', () => {
                const rating = star.dataset.rating;
                ratingInput.value = rating;
                
                // Update visual state
                ratingStars.forEach(s => {
                    s.classList.remove('selected');
                    if (parseInt(s.dataset.rating) <= parseInt(rating)) {
                        s.classList.add('selected');
                    }
                });
            });
        });
    }

});