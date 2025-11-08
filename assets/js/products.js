/**
 * Aakaari products page JS
 * Handles filter UI and AJAX requests to admin-ajax.php
 * Expects `aakaari_ajax` object localized with { ajax_url, nonce }
 */
(function(){
  const selectors = {
    categories: '#categories-list',
    sizes: '#sizes-list',
    colors: '#colors-list',
    rating: '#rating-list',
    priceMin: '#price-min',
    priceMax: '#price-max',
    priceMinLabel: '#price-min-label',
    priceMaxLabel: '#price-max-label',
    sortBy: '#sort-by',
    productsGrid: '#products-grid',
    productsCount: '#products-count',
    clearFilters: '#clear-filters',
    clearFilters2: '#clear-filters-2',
    toggleFilters: '#toggle-filters',
    filtersSidebar: '#filters',
    filtersToggleText: '#filters-toggle-text'
  };

  function $(s){ return document.querySelector(s) }
  function $all(s){ return Array.from(document.querySelectorAll(s)) }

  function getFilters(){
    const categories = Array.from(document.querySelectorAll(selectors.categories + ' input[type=checkbox]:checked')).map(i=>i.dataset.category);
    const sizes = Array.from(document.querySelectorAll(selectors.sizes + ' input[type=checkbox]:checked')).map(i=>i.dataset.size);
    const colors = Array.from(document.querySelectorAll(selectors.colors + '.selected, ' + selectors.colors + ' .color-swatch.selected')).map(b=>b.dataset.color).filter(Boolean);
    const ratingEl = document.querySelector(selectors.rating + ' input[type=checkbox]:checked');
    const rating = ratingEl ? ratingEl.dataset.rating : '';
    const priceMin = document.querySelector(selectors.priceMin) ? document.querySelector(selectors.priceMin).value : 0;
    const priceMax = document.querySelector(selectors.priceMax) ? document.querySelector(selectors.priceMax).value : 2000;
    const sortBy = document.querySelector(selectors.sortBy) ? document.querySelector(selectors.sortBy).value : 'popularity';
    return { categories, sizes, colors, rating, priceMin, priceMax, sortBy };
  }

  function renderLoading(){
    const grid = $(selectors.productsGrid);
    if (grid) {
      grid.innerHTML = '<div style="grid-column:1/-1;padding:2rem;text-align:center;color:var(--muted)">Loading…</div>';
    }
  }

  function renderProductsHtml(html){
    const grid = $(selectors.productsGrid);
    if (grid) {
      grid.innerHTML = html;
    }
  }

  function updateCount(n){
    const el = $(selectors.productsCount);
    if(el) el.textContent = n + ' Products';
  }

  function fetchProducts(){
    // Check if aakaari_ajax is defined
    if (typeof aakaari_ajax === 'undefined') {
      console.error('aakaari_ajax is not defined');
      return;
    }

    const filters = getFilters();
    renderLoading();
    const body = new FormData();
    body.append('action', 'aakaari_filter_products');
    body.append('nonce', aakaari_ajax.nonce);
    body.append('filters', JSON.stringify(filters));
    body.append('pageType', window.aakaari_page_type || 'products');

    fetch(aakaari_ajax.ajax_url, { method:'POST', body })
      .then(r=>r.json())
      .then(data=>{
        if(data.success){
          renderProductsHtml(data.data.html);
          updateCount(data.data.count);
          initializeWishlistStates();
        } else {
          renderProductsHtml('<div style="grid-column:1/-1;padding:2rem;text-align:center;color:#666">No products found</div>');
          updateCount(0);
        }
      }).catch(err=>{
        console.error(err);
        renderProductsHtml('<div style="grid-column:1/-1;padding:2rem;text-align:center;color:#666">Error loading products</div>');
        updateCount(0);
      });
  }

  // Initialize wishlist button states based on saved wishlist data
  function initializeWishlistStates() {
    if (typeof aakaari_wishlist !== 'undefined' && aakaari_wishlist.product_ids) {
      const wishlistIds = aakaari_wishlist.product_ids;
      $all('.favorite').forEach(btn => {
        const productId = btn.dataset.productId;
        if (productId && wishlistIds.includes(parseInt(productId))) {
          btn.classList.add('active');
          btn.textContent = '♥';
        }
      });
    }
  }

  /* UI wiring */
  document.addEventListener('click', function(e){
    // color swatch toggle
    if(e.target.closest('.color-swatch')){
      const btn = e.target.closest('.color-swatch');
      btn.classList.toggle('selected');
      fetchProducts();
    }

    // wishlist
    if(e.target.closest('.favorite')){
      e.preventDefault();
      e.stopPropagation();
      const btn = e.target.closest('.favorite');
      const card = btn.closest('.product-card');
      const productId = btn.dataset.productId || card.querySelector('.add-btn')?.dataset.id;

      if (!productId) return;

      // Get product details for toast
      const productName = card.querySelector('.product-title')?.textContent || 'Product';
      const productPrice = card.querySelector('.price')?.textContent || '';
      const productImage = card.querySelector('.product-media img')?.src || '';

      // Toggle wishlist state
      const isInWishlist = btn.classList.contains('active');

      if (!isInWishlist) {
        // Add to wishlist
        const formData = new FormData();
        formData.append('action', 'aakaari_add_to_wishlist');
        formData.append('nonce', aakaari_ajax.nonce);
        formData.append('product_id', productId);

        fetch(aakaari_ajax.ajax_url, {
          method: 'POST',
          body: formData
        })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            btn.classList.add('active');
            btn.textContent = '♥';

            if (typeof window.showWishlistToast === 'function') {
              window.showWishlistToast({
                name: productName,
                price: productPrice,
                image: productImage
              });
            }
          } else {
            if (typeof window.showMessageToast === 'function') {
              window.showMessageToast('Login Required', data.data.message || 'Please login to add to wishlist', 'info');
            }
          }
        })
        .catch(err => {
          console.error('Wishlist error:', err);
          if (typeof window.showMessageToast === 'function') {
            window.showMessageToast('Error', 'Could not add to wishlist', 'info');
          }
        });
      } else {
        // Remove from wishlist
        const formData = new FormData();
        formData.append('action', 'aakaari_remove_from_wishlist');
        formData.append('nonce', aakaari_ajax.nonce);
        formData.append('product_id', productId);

        fetch(aakaari_ajax.ajax_url, {
          method: 'POST',
          body: formData
        })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            btn.classList.remove('active');
            btn.textContent = '♡';

            if (typeof window.showMessageToast === 'function') {
              window.showMessageToast('Removed from Wishlist', 'Item removed', 'info');
            }
          }
        })
        .catch(err => console.error('Wishlist error:', err));
      }
    }

    // add to cart (in overlay)
    if(e.target.closest('.add-btn')){
      e.preventDefault();
      e.stopPropagation();
      const btn = e.target.closest('.add-btn');
      const id = btn.dataset.id;
      const card = btn.closest('.product-card');

      // Get product details for toast
      const productName = card.querySelector('.product-title')?.textContent || 'Product';
      const productPrice = card.querySelector('.price')?.textContent || '';
      const productImage = card.querySelector('.product-media img')?.src || '';

      // Check if product is variable (has variations)
      const isVariable = btn.dataset.productType === 'variable' || btn.classList.contains('product_type_variable');

      // If variable product, redirect to product page instead
      if (isVariable) {
        const productLink = card.querySelector('.product-title a, a.product-title');
        if (productLink) {
          window.location.href = productLink.href;
          return;
        }
      }

      // Disable button while adding
      btn.disabled = true;
      btn.textContent = 'Adding...';

      // Use WooCommerce AJAX add to cart
      const data = {
        product_id: id,
        quantity: 1
      };

      fetch('/?wc-ajax=add_to_cart', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(data)
      })
      .then(r => {
        if (!r.ok) {
          throw new Error('Network response was not ok');
        }
        return r.json();
      })
      .then(response => {
        // Re-enable button
        btn.disabled = false;
        btn.textContent = 'Add to Cart';

        console.log('Add to cart response:', response);

        // Check for various error conditions
        if (response.error || response.error_message) {
          const errorMsg = response.error_message || 'This product has options. Please select them on the product page.';
          console.error('Add to cart failed:', errorMsg);

          // If product has variations, redirect to product page
          if (response.product_url) {
            if (typeof window.showMessageToast === 'function') {
              window.showMessageToast('Product Options Required', 'Redirecting to product page...', 'info');
            }
            setTimeout(() => {
              window.location.href = response.product_url;
            }, 1000);
          } else if (typeof window.showMessageToast === 'function') {
            window.showMessageToast('Error', errorMsg, 'error');
          }
        } else if (response.fragments) {
          // Success! Update cart fragments
          // Update cart count in header
          jQuery.each(response.fragments, function(key, value) {
            jQuery(key).replaceWith(value);
          });

          // Trigger WooCommerce cart update event
          jQuery(document.body).trigger('wc_fragment_refresh');
          jQuery(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, btn]);

          // Show beautiful toast notification
          if (typeof window.showCartToast === 'function') {
            window.showCartToast({
              name: productName,
              price: productPrice,
              image: productImage
            });
          }
        } else {
          // Unexpected response format
          console.warn('Unexpected response format:', response);
          if (typeof window.showMessageToast === 'function') {
            window.showMessageToast('Added to Cart', 'Item added successfully', 'success');
          }
          // Reload to update cart
          setTimeout(() => window.location.reload(), 1000);
        }
      })
      .catch(err => {
        console.error('Add to cart error:', err);
        btn.disabled = false;
        btn.textContent = 'Add to Cart';

        if (typeof window.showMessageToast === 'function') {
          window.showMessageToast('Error', 'Could not add to cart. Please try again.', 'error');
        }
      });
    }
  });

  // Toggle filters on mobile
  document.addEventListener('click', function(e){
    if(e.target.matches(selectors.toggleFilters)){
      const sidebar = $(selectors.filtersSidebar);
      const toggleText = $(selectors.filtersToggleText);
      if (sidebar) {
        sidebar.classList.toggle('hide');
        sidebar.classList.toggle('show');
        if (toggleText) {
          toggleText.textContent = sidebar.classList.contains('hide') ? 'Show' : 'Hide';
        }
      }
    }
  });

  // Real-time price slider updates (while dragging)
  document.addEventListener('input', function(e){
    if(e.target.matches(selectors.priceMin) || e.target.matches(selectors.priceMax)){
      const minEl = $(selectors.priceMin);
      const maxEl = $(selectors.priceMax);
      const minLabel = $(selectors.priceMinLabel);
      const maxLabel = $(selectors.priceMaxLabel);

      if (minEl && maxEl) {
        let minValue = parseInt(minEl.value);
        let maxValue = parseInt(maxEl.value);

        // Update labels only - no validation during drag
        if (minLabel) minLabel.textContent = '$' + minValue;
        if (maxLabel) maxLabel.textContent = '$' + maxValue;
      }
    }
  });

  // checkboxes / ranges
  document.addEventListener('change', function(e){
    if(e.target.matches(selectors.categories + ' input[type=checkbox]') ||
       e.target.matches(selectors.sizes + ' input[type=checkbox]') ||
       e.target.matches(selectors.rating + ' input[type=checkbox]') ||
       e.target.matches(selectors.sortBy) ){
      fetchProducts();
    }
    if(e.target.matches(selectors.priceMin) || e.target.matches(selectors.priceMax)){
      const minEl = $(selectors.priceMin);
      const maxEl = $(selectors.priceMax);

      if (minEl && maxEl) {
        let minValue = parseInt(minEl.value);
        let maxValue = parseInt(maxEl.value);

        // Show toast if range is invalid but still allow filtering
        if (minValue > maxValue && typeof window.showMessageToast === 'function') {
          window.showMessageToast('Invalid Range', 'Min price is higher than max price', 'info');
        }

        fetchProducts();
      }
    }
  });

  // clear filters
  function clearAll(){
    $all(selectors.categories + ' input[type=checkbox]').forEach(i=>i.checked=false);
    $all(selectors.sizes + ' input[type=checkbox]').forEach(i=>i.checked=false);
    $all(selectors.rating + ' input[type=checkbox]').forEach(i=>i.checked=false);
    $all('.color-swatch.selected').forEach(b=>b.classList.remove('selected'));

    const minEl = $(selectors.priceMin);
    const maxEl = $(selectors.priceMax);
    const minLabel = $(selectors.priceMinLabel);
    const maxLabel = $(selectors.priceMaxLabel);

    if (minEl) {
      minEl.value = minEl.min || 0;
      if (minLabel) minLabel.textContent = '$' + minEl.value;
    }
    if (maxEl) {
      maxEl.value = maxEl.max || 2000;
      if (maxLabel) maxLabel.textContent = '$' + maxEl.value;
    }

    const sortEl = $(selectors.sortBy);
    if (sortEl) sortEl.value = 'popularity';

    fetchProducts();
  }

  document.addEventListener('click', function(e){
    if(e.target.matches(selectors.clearFilters) || e.target.matches(selectors.clearFilters2)) {
      e.preventDefault();
      clearAll();
    }
  });

  // initial load (small delay to allow localized aakaari_ajax to be present)
  document.addEventListener('DOMContentLoaded', function(){
    setTimeout(function(){
      if (typeof aakaari_ajax !== 'undefined') {
        fetchProducts();
      }
    }, 150);
  });
})();
