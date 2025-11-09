/**
 * aakaari-shop.js (updated)
 * Fix: prevent redirect after add-to-cart & show toast
 * - Robust add-to-cart that avoids full page reload / redirect
 * - Updates WooCommerce fragments if present, else fetches refreshed fragments
 * - Shows a friendly toast (uses window.showCartToast if provided)
 * Version: 1.2.0
 */
(function () {
  'use strict';

  /* --------------------
     Selectors & helpers
     -------------------- */
  const S = {
    productsGrid: '#products-grid',
    productsCount: '#products-count',
    categories: '#categories-list',
    sizes: '#sizes-list',
    colors: '#colors-list',
    rating: '#rating-list',
    priceMin: '#price-min',
    priceMax: '#price-max',
    priceMinLabel: '#price-min-label',
    priceMaxLabel: '#price-max-label',
    sortBy: '#sort-by',
    clearFilters: '#clear-filters',
    clearFilters2: '#clear-filters-2',
    toggleFilters: '#toggle-filters',
    filtersSidebar: '#filters',
    filtersToggleText: '#filters-toggle-text'
  };

  const $ = (sel) => document.querySelector(sel);
  const $$ = (sel) => Array.from(document.querySelectorAll(sel));

  // small in-page toast (fallback)
  function fallbackToast(title, message, type = 'success', timeout = 2500) {
    if (typeof window.showMessageToast === 'function') {
      // prefer site-provided toast if available
      window.showMessageToast(title, message, type);
      return;
    }
    // create simple non-blocking toast
    let container = document.getElementById('aakaari-toast-container');
    if (!container) {
      container = document.createElement('div');
      container.id = 'aakaari-toast-container';
      container.style.position = 'fixed';
      container.style.right = '16px';
      container.style.bottom = '16px';
      container.style.zIndex = 99999;
      container.style.display = 'flex';
      container.style.flexDirection = 'column';
      container.style.gap = '8px';
      document.body.appendChild(container);
    }
    const el = document.createElement('div');
    el.style.minWidth = '220px';
    el.style.maxWidth = '360px';
    el.style.padding = '10px 12px';
    el.style.borderRadius = '10px';
    el.style.boxShadow = '0 6px 20px rgba(0,0,0,0.12)';
    el.style.background = (type === 'success') ? '#0f172a' : (type === 'info' ? '#0f172a' : '#3b0b0b');
    el.style.color = '#fff';
    el.style.fontSize = '14px';
    el.style.fontWeight = 600;
    el.innerHTML = `<div style="font-weight:700;margin-bottom:4px">${title}</div><div style="font-weight:400;font-size:13px">${message}</div>`;
    container.appendChild(el);
    setTimeout(() => {
      el.style.transition = 'opacity 220ms, transform 220ms';
      el.style.opacity = '0';
      el.style.transform = 'translateX(10px)';
      setTimeout(() => { el.remove(); if (!container.childElementCount) container.remove(); }, 260);
    }, timeout);
  }

  // Unified toast wrapper: prefer showCartToast, else fallback
  function showAddedToCartToast(payload) {
    // payload: { name, price, image }
    if (typeof window.showCartToast === 'function') {
      try {
        window.showCartToast(payload);
        return;
      } catch (e) {
        // if custom toast throws, use fallback
      }
    }
    // use fallbackToast
    const name = payload.name || 'Item';
    const price = payload.price ? ` • ${payload.price}` : '';
    fallbackToast('Added to cart', `${name}${price}`, 'success', 2500);
  }

  /* --------------------
     Filters (kept minimal)
     -------------------- */
  function getFilters() {
    const categories = $$(S.categories + ' input[type=checkbox]:checked').map(i => i.dataset.category).filter(Boolean);
    const sizes = $$(S.sizes + ' input[type=checkbox]:checked').map(i => i.dataset.size).filter(Boolean);
    const colors = $$(S.colors + ' .color-swatch.selected').map(b => b.dataset.color).filter(Boolean);
    const ratingEl = document.querySelector(S.rating + ' input[type=checkbox]:checked');
    const rating = ratingEl ? ratingEl.dataset.rating : '';
    const priceMin = $(S.priceMin) ? $(S.priceMin).value : 0;
    const priceMax = $(S.priceMax) ? $(S.priceMax).value : 2000;
    const sortBy = $(S.sortBy) ? $(S.sortBy).value : 'popularity';
    return { categories, sizes, colors, rating, priceMin, priceMax, sortBy };
  }

  /* --------------------
     Products fetch (AJAX)
     -------------------- */
  function renderLoading() {
    const grid = $(S.productsGrid);
    if (grid) grid.innerHTML = '<div style="grid-column:1/-1;padding:2rem;text-align:center;color:#666">Loading…</div>';
  }

  function renderProductsHtml(html, count = 0) {
    const grid = $(S.productsGrid);
    if (grid) grid.innerHTML = html;
    updateCount(count);
    // Re-init wishlist states if needed (simple)
    initWishlistButtons();
  }

  function updateCount(n) {
    const el = $(S.productsCount);
    if (el) el.textContent = (n || 0) + ' Products';
  }

  function fetchProducts() {
    if (typeof aakaari_ajax === 'undefined') {
      console.warn('aakaari_ajax not found — skipping products fetch');
      return;
    }
    renderLoading();
    const body = new FormData();
    body.append('action', 'aakaari_filter_products');
    body.append('nonce', aakaari_ajax.nonce || '');
    body.append('filters', JSON.stringify(getFilters()));
    body.append('pageType', window.aakaari_page_type || 'products');

    fetch(aakaari_ajax.ajax_url, { method: 'POST', body })
      .then(r => r.json())
      .then(data => {
        if (data && data.success) {
          renderProductsHtml(data.data.html || '', data.data.count || 0);
        } else {
<<<<<<< Updated upstream
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
    if(e.target.closest('.product-add-to-cart-btn')){
      e.preventDefault();
      e.stopPropagation();
      const btn = e.target.closest('.product-add-to-cart-btn');
      const id = btn.dataset.productId || btn.dataset.id;
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

          // Store the cart item key for undo functionality
          const cartItemKey = response.cart_item_key || null;

          // Show beautiful toast notification with Undo button
          if (typeof window.showCartToast === 'function') {
            window.showCartToast({
              name: productName,
              price: productPrice,
              image: productImage
            }, cartItemKey);
          }
        } else {
          // Unexpected response format
          console.warn('Unexpected response format:', response);
          if (typeof window.showMessageToast === 'function') {
            window.showMessageToast('Added to Cart', 'Item added successfully', 'success');
          }
          // Reload to update cart
          setTimeout(() => window.location.reload(), 1000);
=======
          renderProductsHtml('<div style="grid-column:1/-1;padding:2rem;text-align:center;color:#666">No products found</div>', 0);
>>>>>>> Stashed changes
        }
      })
      .catch(err => {
        console.error('fetchProducts error', err);
        renderProductsHtml('<div style="grid-column:1/-1;padding:2rem;text-align:center;color:#666">Error loading products</div>', 0);
      });
  }

  /* --------------------
     Wishlist initialiser (simple)
     -------------------- */
  function initWishlistButtons() {
    // If you have server-set aakaari_wishlist.product_ids, apply active states
    if (typeof aakaari_wishlist !== 'undefined' && Array.isArray(aakaari_wishlist.product_ids)) {
      const ids = aakaari_wishlist.product_ids.map(n => parseInt(n));
      $$('.product-wishlist-btn').forEach(btn => {
        const pid = parseInt(btn.dataset.productId || btn.getAttribute('data-product-id'));
        if (pid && ids.includes(pid)) {
          btn.classList.add('active');
          btn.setAttribute('aria-pressed', 'true');
        } else {
          btn.classList.remove('active');
          btn.setAttribute('aria-pressed', 'false');
        }
      });
    }
  }

<<<<<<< Updated upstream
  /**
   * Undo add to cart - removes the last added item
   */
  window.undoAddToCart = function(cartItemKey, toast) {
    if (!cartItemKey) {
      console.error('No cart item key provided for undo');
      return;
    }

    // Show loading state
    const undoBtn = toast.querySelector('.undo-btn');
    if (undoBtn) {
      undoBtn.textContent = 'Removing...';
      undoBtn.style.pointerEvents = 'none';
    }

    // Use WooCommerce AJAX to remove cart item
    fetch('/?wc-ajax=remove_from_cart', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams({
        cart_item_key: cartItemKey
      })
    })
    .then(r => r.json())
    .then(response => {
      if (response.fragments) {
        // Update cart fragments
        jQuery.each(response.fragments, function(key, value) {
          jQuery(key).replaceWith(value);
        });

        // Trigger cart update events
        jQuery(document.body).trigger('wc_fragment_refresh');
        jQuery(document.body).trigger('removed_from_cart', [response.fragments, response.cart_hash]);

        // Dismiss the toast
        if (toast && typeof toast.remove === 'function') {
          toast.classList.add('hiding');
          setTimeout(() => toast.remove(), 200);
        }

        // Show confirmation
        if (typeof window.showMessageToast === 'function') {
          window.showMessageToast('Removed', 'Item removed from cart', 'info');
        }
      }
    })
    .catch(err => {
      console.error('Undo error:', err);
      if (undoBtn) {
        undoBtn.textContent = 'Undo';
        undoBtn.style.pointerEvents = 'all';
      }
      if (typeof window.showMessageToast === 'function') {
        window.showMessageToast('Error', 'Could not remove item', 'error');
      }
    });
  };

  // Toggle filters on mobile
  document.addEventListener('click', function(e){
    if(e.target.matches(selectors.toggleFilters)){
      const sidebar = $(selectors.filtersSidebar);
      const toggleText = $(selectors.filtersToggleText);
=======
  /* --------------------
     Update cart fragments helper
     - Uses fragments from add-to-cart response if present
     - If not present, tries to GET refreshed fragments via WooCommerce endpoint
     -------------------- */
  function updateFragmentsFromResponse(response) {
    // If fragments present in response, use them
    if (response && response.fragments) {
      if (window.jQuery) {
        jQuery.each(response.fragments, function (key, html) {
          jQuery(key).replaceWith(html);
        });
        jQuery(document.body).trigger('wc_fragment_refresh');
        jQuery(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash]);
      } else {
        // try to place fragments into DOM keys (basic)
        Object.keys(response.fragments).forEach(key => {
          try {
            const nodes = document.querySelectorAll(key);
            nodes.forEach(n => n.outerHTML = response.fragments[key]);
          } catch (e) {
            // ignore
          }
        });
      }
      return Promise.resolve(true);
    }

    // else, try to fetch refreshed fragments endpoint (standard Woo)
    // Use wc_cart_fragments_params if present, otherwise try the default endpoint
    let fragUrl = '/?wc-ajax=get_refreshed_fragments';
    if (typeof wc_cart_fragments_params !== 'undefined' && wc_cart_fragments_params.fragment_name) {
      // fallback attempt to use wc cart ajax generic
      fragUrl = (wc_cart_fragments_params && wc_cart_fragments_params.wc_ajax_url) ? wc_cart_fragments_params.wc_ajax_url.replace('%%endpoint%%', 'get_refreshed_fragments') : fragUrl;
    }
    return fetch(fragUrl, { method: 'GET', credentials: 'same-origin' })
      .then(r => r.json())
      .then(data => {
        if (data && data.fragments) {
          if (window.jQuery) {
            jQuery.each(data.fragments, function (key, html) {
              jQuery(key).replaceWith(html);
            });
            jQuery(document.body).trigger('wc_fragment_refresh');
            jQuery(document.body).trigger('added_to_cart', [data.fragments, data.cart_hash]);
          } else {
            Object.keys(data.fragments).forEach(key => {
              try {
                const nodes = document.querySelectorAll(key);
                nodes.forEach(n => n.outerHTML = data.fragments[key]);
              } catch (e) { }
            });
          }
          return true;
        }
        return false;
      })
      .catch(err => {
        console.warn('Could not refresh fragments', err);
        return false;
      });
  }

  /* --------------------
     Add to cart handler (robust & no redirect)
     -------------------- */
  function addToCartHandler(addBtn) {
    if (!addBtn) return;

    const card = addBtn.closest('.product-card');

    // Gather product id (support data-id, data-product_id)
    let id = addBtn.dataset.id || addBtn.dataset.productId || addBtn.dataset.product_id || addBtn.getAttribute('data-product_id') || addBtn.getAttribute('data-id');
    if (!id && card) {
      const fallback = card.querySelector('[data-product_id], [data-id], [data-product-id]');
      if (fallback) id = fallback.dataset.productId || fallback.dataset.product_id || fallback.dataset.id || fallback.getAttribute('data-product-id') || fallback.getAttribute('data-id');
    }
    if (!id) {
      console.warn('Add to cart: missing product id');
      return;
    }

    // Detect product type (variable products must redirect to product page)
    const productType = (addBtn.dataset.productType || addBtn.dataset.product_type || addBtn.getAttribute('data-product_type') || '').toLowerCase();
    const isVariable = productType === 'variable' || addBtn.classList.contains('product_type_variable') || addBtn.classList.contains('variable');
    if (isVariable && card) {
      const link = card.querySelector('.product-card-title a, a.product-title, .product-media a');
      if (link && link.href) {
        // Only redirect if product requires options; otherwise ignore.
        window.location.href = link.href;
        return;
      }
    }

    // UI feedback: disable & show loading state
    const origText = addBtn.textContent;
    addBtn.disabled = true;
    addBtn.setAttribute('aria-busy', 'true');
    addBtn.textContent = 'Adding…';

    // Build POST body
    const body = new URLSearchParams();
    body.append('product_id', id);
    body.append('quantity', 1);

    // Determine add-to-cart endpoint
    let ajaxEndpoint = '/?wc-ajax=add_to_cart';
    if (typeof wc_add_to_cart_params !== 'undefined' && wc_add_to_cart_params.wc_ajax_url) {
      try {
        ajaxEndpoint = wc_add_to_cart_params.wc_ajax_url.replace('%%endpoint%%', 'add_to_cart');
      } catch (e) { /* fallback */ }
    }

    fetch(ajaxEndpoint, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: body.toString(),
      credentials: 'same-origin'
    })
      .then(r => {
        if (!r.ok) throw new Error('Network failed');
        // try parse as JSON, fallback to text
        return r.json().catch(() => r.text());
      })
      .then(async response => {
        // restore UI
        addBtn.disabled = false;
        addBtn.removeAttribute('aria-busy');
        addBtn.textContent = origText;

        // If JSON response object
        if (typeof response === 'object') {
          // If error & product_url provided (variations/options needed) -> go to product page
          if (response.error || response.error_message) {
            if (response.product_url) {
              // show small toast then redirect
              fallbackToast('Select options', 'Opening product page for options', 'info', 900);
              setTimeout(() => window.location.href = response.product_url, 700);
              return;
            }
            // else show error toast
            const msg = response.error_message || 'Could not add to cart';
            fallbackToast('Error', msg, 'error', 2200);
            return;
          }

          // Success path: update fragments (from response if present)
          const updated = await updateFragmentsFromResponse(response);
          // Show toast with product info
          const payload = {
            name: card?.querySelector('.product-card-title a')?.textContent?.trim() || card?.querySelector('.product-title')?.textContent?.trim(),
            price: card?.querySelector('.product-price')?.textContent?.trim(),
            image: card?.querySelector('.product-card-image img')?.src
          };
          showAddedToCartToast(payload || {});
          // If fragments were not updated, try to fetch them
          if (!updated) {
            await updateFragmentsFromResponse(null);
          }
          return;
        }

        // If response is string/html, try to detect product_url or fragments; else attempt to refresh fragments
        const text = String(response || '');
        const productUrlMatch = text.match(/"product_url"\s*:\s*"([^"]+)"/i) || text.match(/href=["']([^"']+product[^"']+)["']/i);
        if (productUrlMatch && productUrlMatch[1]) {
          // When server returns product_url pointer, redirect only if it's necessary (rare)
          // But to prevent unexpected redirects, prefer showing a toast and not redirecting.
          fallbackToast('Added to cart', 'Item added to cart', 'success', 1600);
          // attempt to refresh fragments
          await updateFragmentsFromResponse(null);
          return;
        }

        // fallback: refresh fragments; then show toast
        const refreshed = await updateFragmentsFromResponse(null);
        showAddedToCartToast({
          name: card?.querySelector('.product-card-title a')?.textContent?.trim() || 'Item',
          price: card?.querySelector('.product-price')?.textContent?.trim(),
          image: card?.querySelector('.product-card-image img')?.src
        });
        if (!refreshed) {
          // If fragments couldn't be refreshed, we avoid redirecting and just show toast.
          console.warn('Fragments not refreshed; cart UI may not reflect change until reload.');
        }
      })
      .catch(err => {
        console.error('Add to cart failed', err);
        addBtn.disabled = false;
        addBtn.removeAttribute('aria-busy');
        addBtn.textContent = origText;
        fallbackToast('Error', 'Could not add to cart. Try again.', 'error', 2400);
      });
  }

  /* --------------------
     Event delegation
     -------------------- */
  document.addEventListener('click', function (e) {
    const el = e.target;

    // color swatch toggle
    const swatch = el.closest('.color-swatch');
    if (swatch) {
      e.preventDefault();
      swatch.classList.toggle('selected');
      fetchProducts();
      return;
    }

    // wishlist button (supports product-wishlist-btn)
    const wishlistBtn = el.closest('.product-wishlist-btn, .favorite');
    if (wishlistBtn) {
      e.preventDefault();
      e.stopPropagation();
      // simplest wishlist: call existing handler if defined, else attempt AJAX
      const pid = wishlistBtn.dataset.productId || wishlistBtn.getAttribute('data-product-id');
      if (pid && typeof window.addToWishlist === 'function') {
        window.addToWishlist(pid);
      } else {
        // fallback UI toggle
        wishlistBtn.classList.toggle('active');
        fallbackToast('Wishlist', wishlistBtn.classList.contains('active') ? 'Added to wishlist' : 'Removed from wishlist', 'info', 1400);
      }
      return;
    }

    // Add to cart - support button or anchor classes
    const addBtn = el.closest('.product-add-to-cart-btn, .add-btn, .ajax_add_to_cart, a.ajax_add_to_cart');
    if (addBtn) {
      e.preventDefault();
      e.stopPropagation();
      addToCartHandler(addBtn);
      return;
    }

    // product card click navigation: only navigate when clicking non-actionable areas
    const card = el.closest('.product-card');
    if (card) {
      const actionable = el.closest('a, button, input, select, textarea, label, .product-wishlist-btn, .product-add-to-cart-btn, .add-btn, .ajax_add_to_cart');
      if (!actionable) {
        const url = card.getAttribute('data-product-url') || card.querySelector('.product-card-title a')?.href;
        if (url) window.location.href = url;
      }
      return;
    }

    // filters toggle
    if (el.matches(S.toggleFilters) || el.closest(S.toggleFilters)) {
      const sidebar = $(S.filtersSidebar);
      const toggleText = $(S.filtersToggleText);
>>>>>>> Stashed changes
      if (sidebar) {
        sidebar.classList.toggle('hide');
        sidebar.classList.toggle('show');
        if (toggleText) toggleText.textContent = sidebar.classList.contains('hide') ? 'Show' : 'Hide';
      }
      return;
    }

    // Clear filters
    if (el.matches(S.clearFilters) || el.matches(S.clearFilters2) || el.closest(S.clearFilters) || el.closest(S.clearFilters2)) {
      e.preventDefault();
      clearAll();
      return;
    }
  }, false);

  // ranges & inputs
  document.addEventListener('input', function (e) {
    if (e.target.matches(S.priceMin) || e.target.matches(S.priceMax)) {
      const minEl = $(S.priceMin), maxEl = $(S.priceMax);
      const minLabel = $(S.priceMinLabel), maxLabel = $(S.priceMaxLabel);
      if (minEl && maxEl) {
        if (minLabel) minLabel.textContent = '$' + parseInt(minEl.value || 0);
        if (maxLabel) maxLabel.textContent = '$' + parseInt(maxEl.value || 0);
      }
    }
  });

  document.addEventListener('change', function (e) {
    if (e.target.matches(S.categories + ' input[type=checkbox]') ||
      e.target.matches(S.sizes + ' input[type=checkbox]') ||
      e.target.matches(S.rating + ' input[type=checkbox]') ||
      e.target.matches(S.sortBy)) {
      fetchProducts();
      return;
    }
    if (e.target.matches(S.priceMin) || e.target.matches(S.priceMax)) {
      const minEl = $(S.priceMin), maxEl = $(S.priceMax);
      if (minEl && maxEl) {
        if (parseInt(minEl.value || 0) > parseInt(maxEl.value || 0)) {
          if (typeof window.showMessageToast === 'function') window.showMessageToast('Invalid Range', 'Min price is higher than max price', 'info');
        }
        fetchProducts();
      }
      return;
    }
  });

  /* --------------------
     Clear filters helper
     -------------------- */
  function clearAll() {
    $$(S.categories + ' input[type=checkbox]').forEach(i => i.checked = false);
    $$(S.sizes + ' input[type=checkbox]').forEach(i => i.checked = false);
    $$(S.rating + ' input[type=checkbox]').forEach(i => i.checked = false);
    $$(S.colors + ' .color-swatch.selected').forEach(s => s.classList.remove('selected'));
    const minEl = $(S.priceMin), maxEl = $(S.priceMax);
    const minLabel = $(S.priceMinLabel), maxLabel = $(S.priceMaxLabel);
    if (minEl) { minEl.value = minEl.min || 0; if (minLabel) minLabel.textContent = '$' + minEl.value; }
    if (maxEl) { maxEl.value = maxEl.max || 2000; if (maxLabel) maxLabel.textContent = '$' + maxEl.value; }
    const sortEl = $(S.sortBy); if (sortEl) sortEl.value = 'popularity';
    fetchProducts();
  }

  /* --------------------
     Initialization
     -------------------- */
  document.addEventListener('DOMContentLoaded', function () {
    setTimeout(function () {
      initWishlistButtons();
      if (typeof aakaari_ajax !== 'undefined') fetchProducts();
      // improve UX: ensure product card clickable cursor
      $$('.product-card').forEach(c => c.style.cursor = 'pointer');
    }, 100);
  });

  // Expose for debugging
  window.aakaariShop = {
    fetchProducts,
    clearAll,
    addToCartHandler
  };

})();
