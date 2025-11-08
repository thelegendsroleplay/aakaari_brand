/* Save as single-product.js in your theme (e.g. wp-content/themes/your-theme/assets/js/single-product.js)
   This is your original DOM-ready script adapted to:
   - read data from #product-data (JSON inserted by PHP if WooCommerce present)
   - fallback to mock data (same mock you provided) when #product-data is not present
*/

document.addEventListener('DOMContentLoaded', () => {

    // Try to read server-rendered product JSON (inserted by PHP if WC active)
    let serverDataEl = document.getElementById('product-data');
    let mockProduct = null;
    let mockReviews = null;
    let mockRelatedProducts = null;

    if (serverDataEl) {
        try {
            const pdata = JSON.parse(serverDataEl.textContent);
            // normalize to the structure your UI expects
            mockProduct = {
                id: pdata.id || 'p1',
                name: pdata.name || 'Product',
                price: pdata.price || 0,
                salePrice: pdata.salePrice || null,
                description: pdata.description || '',
                images: (pdata.images && pdata.images.length) ? pdata.images : ['https://via.placeholder.com/600'],
                sizes: (pdata.sizes && pdata.sizes.length) ? pdata.sizes : ['S','M','L'],
                colors: (pdata.colors && pdata.colors.length) ? pdata.colors : ['Black','White','Gray'],
                rating: pdata.rating || 0,
                reviewCount: pdata.reviewCount || 0,
                stock: pdata.stock || 0,
                sku: pdata.sku || '',
                category: (Array.isArray(pdata.category) ? pdata.category.join(', ') : (pdata.category || '')),
            };

            // related block
            mockRelatedProducts = (pdata.related && pdata.related.length) ? pdata.related.map(r => ({
                id: r.id,
                name: r.name,
                price: r.price,
                image: r.image || 'https://via.placeholder.com/400'
            })) : [];
            // For reviews, keep empty (could fetch via REST later)
            mockReviews = [];
        } catch (e) {
            console.warn('Invalid JSON product-data; falling back to mock');
            serverDataEl = null;
        }
    }

    // If no server data, use your original mock
    if (!serverDataEl) {
        mockProduct = {
            id: 'p1',
            name: 'Essential Cotton T-Shirt',
            price: 24.99,
            salePrice: 19.99,
            description: 'A classic, comfortable t-shirt made from 100% premium cotton. Perfect for everyday wear, this tee offers a relaxed fit and a soft feel that lasts wash after wash. Available in a variety of colors to match any style.',
            images: [
                'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1620799140408-edc6d5f91708?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1620799140166-08a8f3a3ff3a?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1620799139834-6b8f74ee7be2?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'
            ],
            sizes: ['S', 'M', 'L', 'XL'],
            colors: ['Black', 'White', 'Gray', 'Blue'],
            rating: 4.5,
            reviewCount: 234,
            stock: 50,
            sku: 'A-12345',
            category: 'T-Shirts'
        };

        mockReviews = [
            { id: 'r1', userName: 'Alex Johnson', rating: 5, title: 'Perfect T-Shirt!', comment: 'Fits perfectly and the material is super soft. Will be buying more in other colors. Highly recommend!' },
            { id: 'r2', userName: 'Maria Garcia', rating: 4, title: 'Great value', comment: 'Good quality for the price. It shrunk a tiny bit after the first wash, but still fits well. Happy with my purchase.' },
            { id: 'r3', userName: 'David K.', rating: 5, title: 'My new favorite tee', comment: 'I love the fit and the color. It\'s simple, stylish, and comfortable. What more could you ask for?' }
        ];

        mockRelatedProducts = [
            { id: 'p2', name: 'Oversized T-Shirt', price: 31.99, image: 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80' },
            { id: 'p3', name: 'Vintage Wash T-Shirt', price: 32.99, image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80' },
            { id: 'p4', name: 'Pocket T-Shirt', price: 30.99, image: 'https://images.unsplash.com/photo-1618677843477-c9179d6b2f7f?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80' },
            { id: 'p5', name: 'Striped Crew Neck', price: 36.90, image: 'https://images.unsplash.com/photo-1554568218-0f1715e72254?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80' }
        ];
    }

    // --- STATE VARIABLES ---
    let currentImageIndex = 0;
    let selectedSize = mockProduct.sizes[0];
    let selectedColor = mockProduct.colors[0];
    let quantity = 1;
    let inWishlist = false;

    // --- DOM ELEMENT SELECTORS ---
    const mainImage = document.getElementById('main-image');
    const discountBadge = document.getElementById('discount-badge');
    const thumbnailList = document.getElementById('thumbnail-list');
    const productName = document.getElementById('product-name');
    const wishlistBtn = document.getElementById('wishlist-btn');
    const wishlistIcon = document.getElementById('wishlist-icon-svg');
    const starContainer = document.getElementById('product-rating-stars');
    const ratingText = document.getElementById('product-rating-text');
    const productPrice = document.getElementById('product-price');
    const productOldPrice = document.getElementById('product-old-price');
    const productDescription = document.getElementById('product-description');
    const sizeOptionsContainer = document.getElementById('size-options');
    const colorOptionsContainer = document.getElementById('color-options');
    const qtyDecreaseBtn = document.getElementById('qty-decrease');
    const qtyIncreaseBtn = document.getElementById('qty-increase');
    const quantityDisplay = document.getElementById('quantity-display');
    const stockInfo = document.getElementById('stock-info');
    const addToCartBtn = document.getElementById('add-to-cart-btn');
    const buyNowBtn = document.getElementById('buy-now-btn');
    const productSku = document.getElementById('product-sku');
    const productCategory = document.getElementById('product-category');
    const reviewsList = document.getElementById('reviews-list');
    const relatedProductsGrid = document.getElementById('related-products-grid');
    const reviewsSection = document.getElementById('reviews-section');
    const relatedSection = document.getElementById('related-section');
    const backBtn = document.getElementById('back-btn');

    // --- RENDER FUNCTIONS ---
    function renderProductDetails() {
        mainImage.src = mockProduct.images[currentImageIndex];
        mainImage.alt = mockProduct.name;
        productName.textContent = mockProduct.name;
        ratingText.textContent = `${mockProduct.rating} (${mockProduct.reviewCount} reviews)`;
        productDescription.textContent = mockProduct.description;
        stockInfo.textContent = `${mockProduct.stock} in stock`;
        productSku.textContent = mockProduct.sku;
        productCategory.textContent = mockProduct.category;

        // Price & Discount
        const currentPrice = mockProduct.salePrice || mockProduct.price;
        const originalPrice = mockProduct.salePrice ? mockProduct.price : null;
        productPrice.textContent = `$${currentPrice.toFixed(2)}`;

        if (originalPrice) {
            productOldPrice.textContent = `$${originalPrice.toFixed(2)}`;
            productOldPrice.style.display = 'inline';

            const discount = Math.round(((originalPrice - currentPrice) / originalPrice) * 100);
            discountBadge.textContent = `-${discount}%`;
            discountBadge.style.display = 'inline-block';
        } else {
            productOldPrice.style.display = 'none';
            discountBadge.style.display = 'none';
        }

        // Stock
        if (mockProduct.stock === 0) {
            stockInfo.textContent = 'Out of stock';
            stockInfo.style.color = '#e53e3e';
            addToCartBtn.disabled = true;
            buyNowBtn.disabled = true;
        } else {
            stockInfo.textContent = `${mockProduct.stock} in stock`;
            stockInfo.style.color = '#10b981';
            addToCartBtn.disabled = false;
            buyNowBtn.disabled = false;
        }
    }

    function renderThumbnails() {
        thumbnailList.innerHTML = '';
        mockProduct.images.forEach((img, idx) => {
            const btn = document.createElement('button');
            btn.className = `thumbnail-btn ${idx === currentImageIndex ? 'active' : ''}`;
            btn.onclick = () => selectImage(idx);

            const imgEl = document.createElement('img');
            imgEl.src = img;
            imgEl.alt = `Thumbnail ${idx + 1}`;

            btn.appendChild(imgEl);
            thumbnailList.appendChild(btn);
        });
    }

    function renderSizes() {
        sizeOptionsContainer.innerHTML = '';
        mockProduct.sizes.forEach(size => {
            const btn = document.createElement('button');
            btn.textContent = size;
            btn.className = size === selectedSize ? 'active' : '';
            btn.onclick = () => selectSize(size, btn);
            sizeOptionsContainer.appendChild(btn);
        });
    }

    function renderColors() {
        colorOptionsContainer.innerHTML = '';
        mockProduct.colors.forEach(color => {
            const btn = document.createElement('button');
            btn.title = color;
            // Attempt to set color background; if color isn't a CSS color, fallback to gray
            try {
                btn.style.backgroundColor = color.toLowerCase();
            } catch (e) {
                btn.style.backgroundColor = '#ccc';
            }
            btn.className = color === selectedColor ? 'active' : '';
            btn.onclick = () => selectColor(color, btn);
            colorOptionsContainer.appendChild(btn);
        });
    }

    function renderStars() {
        starContainer.innerHTML = '';
        const starSVG = {
            filled: '<svg class="star-filled" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>',
            empty: '<svg class="star-empty" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>'
        };

        for (let i = 0; i < 5; i++) {
            starContainer.innerHTML += (i < Math.floor(mockProduct.rating)) ? starSVG.filled : starSVG.empty;
        }
    }

    function renderReviews() {
        if (!mockReviews || mockReviews.length === 0) {
            reviewsSection.style.display = 'none';
            return;
        }

        reviewsList.innerHTML = '';
        mockReviews.forEach(review => {
            let reviewStars = '';
            for (let i = 0; i < 5; i++) {
                reviewStars += (i < review.rating) ?
                    '<svg class="star-filled" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>' :
                    '<svg class="star-empty" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
            }

            const reviewHTML = `
                <div class="review-item">
                    <div class="review-header">
                        <div class="stars">${reviewStars}</div>
                        <span class="review-author">${review.userName}</span>
                    </div>
                    <h4>${review.title}</h4>
                    <p>${review.comment}</p>
                </div>
            `;
            reviewsList.innerHTML += reviewHTML;
        });
    }

    function renderRelatedProducts() {
        if (!mockRelatedProducts || mockRelatedProducts.length === 0) {
            relatedSection.style.display = 'none';
            return;
        }

        relatedProductsGrid.innerHTML = '';
        mockRelatedProducts.forEach(product => {
            const productCardHTML = `
                <a href="#" class="related-product-card">
                    <img src="${product.image}" alt="${product.name}">
                    <div class="related-product-info">
                        <h5>${product.name}</h5>
                        <p>$${(product.price || 0).toFixed(2)}</p>
                    </div>
                </a>
            `;
            relatedProductsGrid.innerHTML += productCardHTML;
        });
    }

    // --- EVENT HANDLER FUNCTIONS ---
    function selectImage(idx) {
        currentImageIndex = idx;
        mainImage.src = mockProduct.images[idx];

        // Update active class on thumbnails
        const thumbnailButtons = thumbnailList.querySelectorAll('.thumbnail-btn');
        thumbnailButtons.forEach((btn, i) => {
            btn.classList.toggle('active', i === idx);
        });
    }

    function selectSize(size, btn) {
        selectedSize = size;
        sizeOptionsContainer.querySelectorAll('button').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }

    function selectColor(color, btn) {
        selectedColor = color;
        colorOptionsContainer.querySelectorAll('button').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }

    function updateQuantity(change) {
        const newQuantity = quantity + change;
        if (newQuantity >= 1 && newQuantity <= mockProduct.stock) {
            quantity = newQuantity;
            quantityDisplay.textContent = quantity;
        }
    }

    function toggleWishlist() {
        inWishlist = !inWishlist;
        wishlistIcon.classList.toggle('filled', inWishlist);

        if (inWishlist) {
            console.log('Added to wishlist');
        } else {
            console.log('Removed from wishlist');
        }
    }

    function handleAddToCart() {
        // If WooCommerce is present, you might want to call AJAX WC add-to-cart endpoint.
        console.log('--- Added to Cart ---');
        console.log('Product:', mockProduct.name);
        console.log('Size:', selectedSize);
        console.log('Color:', selectedColor);
        console.log('Quantity:', quantity);
        alert('Added to cart!');
    }

    function handleBuyNow() {
        handleAddToCart();
        console.log('Navigating to checkout...');
        alert('Navigating to checkout...');
    }

    // --- INITIALIZATION ---
    function initializePage() {
        renderProductDetails();
        renderThumbnails();
        renderSizes();
        renderColors();
        renderStars();
        renderReviews();
        renderRelatedProducts();

        // Add event listeners
        qtyDecreaseBtn.addEventListener('click', () => updateQuantity(-1));
        qtyIncreaseBtn.addEventListener('click', () => updateQuantity(1));
        wishlistBtn.addEventListener('click', toggleWishlist);
        addToCartBtn.addEventListener('click', handleAddToCart);
        buyNowBtn.addEventListener('click', handleBuyNow);
        backBtn.addEventListener('click', () => {
            // If there's a referrer go back, otherwise go to shop.
            if (document.referrer) {
                window.history.back();
            } else {
                window.location.href = '/shop';
            }
        });
    }

    initializePage();
});
/* Save as single-product.js in your theme (e.g. wp-content/themes/your-theme/assets/js/single-product.js)
   This is your original DOM-ready script adapted to:
   - read data from #product-data (JSON inserted by PHP if WooCommerce present)
   - fallback to mock data (same mock you provided) when #product-data is not present
*/

document.addEventListener('DOMContentLoaded', () => {

    // Try to read server-rendered product JSON (inserted by PHP if WC active)
    let serverDataEl = document.getElementById('product-data');
    let mockProduct = null;
    let mockReviews = null;
    let mockRelatedProducts = null;

    if (serverDataEl) {
        try {
            const pdata = JSON.parse(serverDataEl.textContent);
            // normalize to the structure your UI expects
            mockProduct = {
                id: pdata.id || 'p1',
                name: pdata.name || 'Product',
                price: pdata.price || 0,
                salePrice: pdata.salePrice || null,
                description: pdata.description || '',
                images: (pdata.images && pdata.images.length) ? pdata.images : ['https://via.placeholder.com/600'],
                sizes: (pdata.sizes && pdata.sizes.length) ? pdata.sizes : ['S','M','L'],
                colors: (pdata.colors && pdata.colors.length) ? pdata.colors : ['Black','White','Gray'],
                rating: pdata.rating || 0,
                reviewCount: pdata.reviewCount || 0,
                stock: pdata.stock || 0,
                sku: pdata.sku || '',
                category: (Array.isArray(pdata.category) ? pdata.category.join(', ') : (pdata.category || '')),
            };

            // related block
            mockRelatedProducts = (pdata.related && pdata.related.length) ? pdata.related.map(r => ({
                id: r.id,
                name: r.name,
                price: r.price,
                image: r.image || 'https://via.placeholder.com/400'
            })) : [];
            // For reviews, keep empty (could fetch via REST later)
            mockReviews = [];
        } catch (e) {
            console.warn('Invalid JSON product-data; falling back to mock');
            serverDataEl = null;
        }
    }

    // If no server data, use your original mock
    if (!serverDataEl) {
        mockProduct = {
            id: 'p1',
            name: 'Essential Cotton T-Shirt',
            price: 24.99,
            salePrice: 19.99,
            description: 'A classic, comfortable t-shirt made from 100% premium cotton. Perfect for everyday wear, this tee offers a relaxed fit and a soft feel that lasts wash after wash. Available in a variety of colors to match any style.',
            images: [
                'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1620799140408-edc6d5f91708?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1620799140166-08a8f3a3ff3a?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1620799139834-6b8f74ee7be2?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'
            ],
            sizes: ['S', 'M', 'L', 'XL'],
            colors: ['Black', 'White', 'Gray', 'Blue'],
            rating: 4.5,
            reviewCount: 234,
            stock: 50,
            sku: 'A-12345',
            category: 'T-Shirts'
        };

        mockReviews = [
            { id: 'r1', userName: 'Alex Johnson', rating: 5, title: 'Perfect T-Shirt!', comment: 'Fits perfectly and the material is super soft. Will be buying more in other colors. Highly recommend!' },
            { id: 'r2', userName: 'Maria Garcia', rating: 4, title: 'Great value', comment: 'Good quality for the price. It shrunk a tiny bit after the first wash, but still fits well. Happy with my purchase.' },
            { id: 'r3', userName: 'David K.', rating: 5, title: 'My new favorite tee', comment: 'I love the fit and the color. It\'s simple, stylish, and comfortable. What more could you ask for?' }
        ];

        mockRelatedProducts = [
            { id: 'p2', name: 'Oversized T-Shirt', price: 31.99, image: 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80' },
            { id: 'p3', name: 'Vintage Wash T-Shirt', price: 32.99, image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80' },
            { id: 'p4', name: 'Pocket T-Shirt', price: 30.99, image: 'https://images.unsplash.com/photo-1618677843477-c9179d6b2f7f?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80' },
            { id: 'p5', name: 'Striped Crew Neck', price: 36.90, image: 'https://images.unsplash.com/photo-1554568218-0f1715e72254?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80' }
        ];
    }

    // --- STATE VARIABLES ---
    let currentImageIndex = 0;
    let selectedSize = mockProduct.sizes[0];
    let selectedColor = mockProduct.colors[0];
    let quantity = 1;
    let inWishlist = false;

    // --- DOM ELEMENT SELECTORS ---
    const mainImage = document.getElementById('main-image');
    const discountBadge = document.getElementById('discount-badge');
    const thumbnailList = document.getElementById('thumbnail-list');
    const productName = document.getElementById('product-name');
    const wishlistBtn = document.getElementById('wishlist-btn');
    const wishlistIcon = document.getElementById('wishlist-icon-svg');
    const starContainer = document.getElementById('product-rating-stars');
    const ratingText = document.getElementById('product-rating-text');
    const productPrice = document.getElementById('product-price');
    const productOldPrice = document.getElementById('product-old-price');
    const productDescription = document.getElementById('product-description');
    const sizeOptionsContainer = document.getElementById('size-options');
    const colorOptionsContainer = document.getElementById('color-options');
    const qtyDecreaseBtn = document.getElementById('qty-decrease');
    const qtyIncreaseBtn = document.getElementById('qty-increase');
    const quantityDisplay = document.getElementById('quantity-display');
    const stockInfo = document.getElementById('stock-info');
    const addToCartBtn = document.getElementById('add-to-cart-btn');
    const buyNowBtn = document.getElementById('buy-now-btn');
    const productSku = document.getElementById('product-sku');
    const productCategory = document.getElementById('product-category');
    const reviewsList = document.getElementById('reviews-list');
    const relatedProductsGrid = document.getElementById('related-products-grid');
    const reviewsSection = document.getElementById('reviews-section');
    const relatedSection = document.getElementById('related-section');
    const backBtn = document.getElementById('back-btn');

    // --- RENDER FUNCTIONS ---
    function renderProductDetails() {
        mainImage.src = mockProduct.images[currentImageIndex];
        mainImage.alt = mockProduct.name;
        productName.textContent = mockProduct.name;
        ratingText.textContent = `${mockProduct.rating} (${mockProduct.reviewCount} reviews)`;
        productDescription.textContent = mockProduct.description;
        stockInfo.textContent = `${mockProduct.stock} in stock`;
        productSku.textContent = mockProduct.sku;
        productCategory.textContent = mockProduct.category;

        // Price & Discount
        const currentPrice = mockProduct.salePrice || mockProduct.price;
        const originalPrice = mockProduct.salePrice ? mockProduct.price : null;
        productPrice.textContent = `$${currentPrice.toFixed(2)}`;

        if (originalPrice) {
            productOldPrice.textContent = `$${originalPrice.toFixed(2)}`;
            productOldPrice.style.display = 'inline';

            const discount = Math.round(((originalPrice - currentPrice) / originalPrice) * 100);
            discountBadge.textContent = `-${discount}%`;
            discountBadge.style.display = 'inline-block';
        } else {
            productOldPrice.style.display = 'none';
            discountBadge.style.display = 'none';
        }

        // Stock
        if (mockProduct.stock === 0) {
            stockInfo.textContent = 'Out of stock';
            stockInfo.style.color = '#e53e3e';
            addToCartBtn.disabled = true;
            buyNowBtn.disabled = true;
        } else {
            stockInfo.textContent = `${mockProduct.stock} in stock`;
            stockInfo.style.color = '#10b981';
            addToCartBtn.disabled = false;
            buyNowBtn.disabled = false;
        }
    }

    function renderThumbnails() {
        thumbnailList.innerHTML = '';
        mockProduct.images.forEach((img, idx) => {
            const btn = document.createElement('button');
            btn.className = `thumbnail-btn ${idx === currentImageIndex ? 'active' : ''}`;
            btn.onclick = () => selectImage(idx);

            const imgEl = document.createElement('img');
            imgEl.src = img;
            imgEl.alt = `Thumbnail ${idx + 1}`;

            btn.appendChild(imgEl);
            thumbnailList.appendChild(btn);
        });
    }

    function renderSizes() {
        sizeOptionsContainer.innerHTML = '';
        mockProduct.sizes.forEach(size => {
            const btn = document.createElement('button');
            btn.textContent = size;
            btn.className = size === selectedSize ? 'active' : '';
            btn.onclick = () => selectSize(size, btn);
            sizeOptionsContainer.appendChild(btn);
        });
    }

    function renderColors() {
        colorOptionsContainer.innerHTML = '';
        mockProduct.colors.forEach(color => {
            const btn = document.createElement('button');
            btn.title = color;
            // Attempt to set color background; if color isn't a CSS color, fallback to gray
            try {
                btn.style.backgroundColor = color.toLowerCase();
            } catch (e) {
                btn.style.backgroundColor = '#ccc';
            }
            btn.className = color === selectedColor ? 'active' : '';
            btn.onclick = () => selectColor(color, btn);
            colorOptionsContainer.appendChild(btn);
        });
    }

    function renderStars() {
        starContainer.innerHTML = '';
        const starSVG = {
            filled: '<svg class="star-filled" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>',
            empty: '<svg class="star-empty" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>'
        };

        for (let i = 0; i < 5; i++) {
            starContainer.innerHTML += (i < Math.floor(mockProduct.rating)) ? starSVG.filled : starSVG.empty;
        }
    }

    function renderReviews() {
        if (!mockReviews || mockReviews.length === 0) {
            reviewsSection.style.display = 'none';
            return;
        }

        reviewsList.innerHTML = '';
        mockReviews.forEach(review => {
            let reviewStars = '';
            for (let i = 0; i < 5; i++) {
                reviewStars += (i < review.rating) ?
                    '<svg class="star-filled" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>' :
                    '<svg class="star-empty" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
            }

            const reviewHTML = `
                <div class="review-item">
                    <div class="review-header">
                        <div class="stars">${reviewStars}</div>
                        <span class="review-author">${review.userName}</span>
                    </div>
                    <h4>${review.title}</h4>
                    <p>${review.comment}</p>
                </div>
            `;
            reviewsList.innerHTML += reviewHTML;
        });
    }

    function renderRelatedProducts() {
        if (!mockRelatedProducts || mockRelatedProducts.length === 0) {
            relatedSection.style.display = 'none';
            return;
        }

        relatedProductsGrid.innerHTML = '';
        mockRelatedProducts.forEach(product => {
            const productCardHTML = `
                <a href="#" class="related-product-card">
                    <img src="${product.image}" alt="${product.name}">
                    <div class="related-product-info">
                        <h5>${product.name}</h5>
                        <p>$${(product.price || 0).toFixed(2)}</p>
                    </div>
                </a>
            `;
            relatedProductsGrid.innerHTML += productCardHTML;
        });
    }

    // --- EVENT HANDLER FUNCTIONS ---
    function selectImage(idx) {
        currentImageIndex = idx;
        mainImage.src = mockProduct.images[idx];

        // Update active class on thumbnails
        const thumbnailButtons = thumbnailList.querySelectorAll('.thumbnail-btn');
        thumbnailButtons.forEach((btn, i) => {
            btn.classList.toggle('active', i === idx);
        });
    }

    function selectSize(size, btn) {
        selectedSize = size;
        sizeOptionsContainer.querySelectorAll('button').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }

    function selectColor(color, btn) {
        selectedColor = color;
        colorOptionsContainer.querySelectorAll('button').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }

    function updateQuantity(change) {
        const newQuantity = quantity + change;
        if (newQuantity >= 1 && newQuantity <= mockProduct.stock) {
            quantity = newQuantity;
            quantityDisplay.textContent = quantity;
        }
    }

    function toggleWishlist() {
        inWishlist = !inWishlist;
        wishlistIcon.classList.toggle('filled', inWishlist);

        if (inWishlist) {
            console.log('Added to wishlist');
        } else {
            console.log('Removed from wishlist');
        }
    }

    function handleAddToCart() {
        // If WooCommerce is present, you might want to call AJAX WC add-to-cart endpoint.
        console.log('--- Added to Cart ---');
        console.log('Product:', mockProduct.name);
        console.log('Size:', selectedSize);
        console.log('Color:', selectedColor);
        console.log('Quantity:', quantity);
        alert('Added to cart!');
    }

    function handleBuyNow() {
        handleAddToCart();
        console.log('Navigating to checkout...');
        alert('Navigating to checkout...');
    }

    // --- INITIALIZATION ---
    function initializePage() {
        renderProductDetails();
        renderThumbnails();
        renderSizes();
        renderColors();
        renderStars();
        renderReviews();
        renderRelatedProducts();

        // Add event listeners
        qtyDecreaseBtn.addEventListener('click', () => updateQuantity(-1));
        qtyIncreaseBtn.addEventListener('click', () => updateQuantity(1));
        wishlistBtn.addEventListener('click', toggleWishlist);
        addToCartBtn.addEventListener('click', handleAddToCart);
        buyNowBtn.addEventListener('click', handleBuyNow);
        backBtn.addEventListener('click', () => {
            // If there's a referrer go back, otherwise go to shop.
            if (document.referrer) {
                window.history.back();
            } else {
                window.location.href = '/shop';
            }
        });
    }

    initializePage();
});
