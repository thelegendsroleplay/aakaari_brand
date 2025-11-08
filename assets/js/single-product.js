/**
 * Single Product Page - Modern Interactive Features
 * Handles gallery, quantity, wishlist, and cart functionality
 */

(function() {
  'use strict';

  let currentImageIndex = 0;
  let qty = 1;
  let inWishlist = false;
  let productImages = [];

  /* DOM refs */
  let heroImg, thumbsContainer, qtyValue, decQtyBtn, incQtyBtn;
  let wishlistBtn, addToCartBtn, buyNowBtn;

  /**
   * Initialize on DOM ready
   */
  function init() {
    // Get DOM elements
    heroImg = document.getElementById('gallery-hero-img');
    thumbsContainer = document.getElementById('gallery-thumbs');
    qtyValue = document.getElementById('qty-value');
    decQtyBtn = document.getElementById('dec-qty');
    incQtyBtn = document.getElementById('inc-qty');
    wishlistBtn = document.getElementById('wishlist-btn');
    addToCartBtn = document.getElementById('add-to-cart-btn');
    buyNowBtn = document.getElementById('buy-now-btn');

    if (!heroImg || !thumbsContainer) return;

    // Get images from thumbnails
    const thumbs = thumbsContainer.querySelectorAll('.gallery-thumb');
    productImages = Array.from(thumbs).map(thumb => ({
      src: thumb.dataset.image || thumb.querySelector('img')?.src,
      thumb: thumb
    }));

    // Initialize gallery
    if (productImages.length > 0) {
      setHeroImage(0);
      enableHeroSwipe();
    }

    // Quantity controls
    if (decQtyBtn) {
      decQtyBtn.addEventListener('click', () => changeQty(qty - 1));
    }
    if (incQtyBtn) {
      incQtyBtn.addEventListener('click', () => changeQty(qty + 1));
    }

    // Wishlist
    if (wishlistBtn) {
      wishlistBtn.addEventListener('click', toggleWishlist);
    }

    // Add to cart
    if (addToCartBtn) {
      addToCartBtn.addEventListener('click', handleAddToCart);
    }

    // Buy now
    if (buyNowBtn) {
      buyNowBtn.addEventListener('click', handleBuyNow);
    }

    // Thumbnail clicks
    thumbs.forEach((thumb, index) => {
      thumb.addEventListener('click', () => setHeroImage(index));
    });

    // Variation selects (if variable product)
    const variationSelects = document.querySelectorAll('.variations select');
    variationSelects.forEach(select => {
      select.addEventListener('change', handleVariationChange);
    });

    // Initialize WooCommerce variation form
    initializeWooCommerceVariations();
  }

  /**
   * Set hero image by index
   */
  function setHeroImage(index) {
    if (!productImages[index] || !heroImg) return;

    const imageData = productImages[index];
    currentImageIndex = index;

    // Update hero image
    heroImg.src = imageData.src;

    // Update active thumbnail
    productImages.forEach((img, idx) => {
      if (img.thumb) {
        img.thumb.classList.toggle('active', idx === index);
      }
    });

    // Scroll thumbnail into view
    if (imageData.thumb) {
      imageData.thumb.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
    }
  }

  /**
   * Enable swipe gestures for hero image
   */
  function enableHeroSwipe() {
    const hero = document.querySelector('.gallery-hero');
    if (!hero) return;

    let startX = 0;
    let moved = false;
    const threshold = 40;

    hero.addEventListener('touchstart', e => {
      startX = e.touches[0].clientX;
      moved = false;
    }, { passive: true });

    hero.addEventListener('touchmove', e => {
      const dx = e.touches[0].clientX - startX;
      if (Math.abs(dx) > 8) moved = true;
    }, { passive: true });

    hero.addEventListener('touchend', e => {
      if (!moved) return;
      const dx = e.changedTouches[0].clientX - startX;

      if (dx < -threshold) {
        // Swipe left - next image
        const next = Math.min(productImages.length - 1, currentImageIndex + 1);
        setHeroImage(next);
      } else if (dx > threshold) {
        // Swipe right - previous image
        const prev = Math.max(0, currentImageIndex - 1);
        setHeroImage(prev);
      }
    }, { passive: true });
  }

  /**
   * Change quantity
   */
  function changeQty(newQty) {
    const maxQty = parseInt(qtyValue?.dataset.max || 9999);
    qty = Math.max(1, Math.min(maxQty, newQty));

    if (qtyValue) {
      qtyValue.textContent = qty;
    }

    // Update WooCommerce quantity input if exists
    const qtyInput = document.querySelector('input.qty');
    if (qtyInput) {
      qtyInput.value = qty;
    }
  }

  /**
   * Toggle wishlist
   */
  function toggleWishlist(e) {
    e.preventDefault();

    const productId = wishlistBtn?.dataset.productId;
    if (!productId) return;

    // Check if we have custom wishlist functions
    if (typeof aakaari_toggle_wishlist === 'function') {
      aakaari_toggle_wishlist(productId, function(success) {
        if (success) {
          inWishlist = !inWishlist;
          wishlistBtn.classList.toggle('active', inWishlist);
          wishlistBtn.setAttribute('aria-pressed', String(inWishlist));

          const message = inWishlist ? 'Added to wishlist' : 'Removed from wishlist';
          showToast(message);
        }
      });
    } else {
      // Fallback - just toggle UI
      inWishlist = !inWishlist;
      wishlistBtn.classList.toggle('active', inWishlist);
      wishlistBtn.setAttribute('aria-pressed', String(inWishlist));

      const message = inWishlist ? 'Added to wishlist' : 'Removed from wishlist';
      showToast(message);
    }
  }

  /**
   * Handle add to cart
   */
  function handleAddToCart(e) {
    e.preventDefault();

    const form = document.querySelector('form.cart');
    if (!form) {
      showToast('Unable to add to cart');
      return;
    }

    // Get form data
    const formData = new FormData(form);
    formData.set('quantity', qty);

    // Add AJAX action
    formData.append('action', 'woocommerce_add_to_cart');

    // Disable button
    addToCartBtn.disabled = true;
    addToCartBtn.textContent = 'Adding...';

    // Submit via AJAX
    fetch(wc_add_to_cart_params?.ajax_url || ajaxurl || '/wp-admin/admin-ajax.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        showToast(data.error || 'Could not add to cart');
      } else {
        showToast('Added to cart successfully!');

        // Update cart fragments
        if (typeof jQuery !== 'undefined' && jQuery(document.body).trigger) {
          jQuery(document.body).trigger('wc_fragment_refresh');
        }
      }
    })
    .catch(error => {
      console.error('Add to cart error:', error);
      showToast('Could not add to cart');
    })
    .finally(() => {
      addToCartBtn.disabled = false;
      addToCartBtn.textContent = 'Add to cart';
    });
  }

  /**
   * Handle buy now
   */
  function handleBuyNow(e) {
    e.preventDefault();

    // Add to cart first, then redirect to checkout
    handleAddToCart(e);

    setTimeout(() => {
      const checkoutUrl = wc_add_to_cart_params?.checkout_url || '/checkout';
      window.location.href = checkoutUrl;
    }, 500);
  }

  /**
   * Handle variation change
   */
  function handleVariationChange() {
    // WooCommerce handles variation logic
    // We just need to update our quantity based on stock
    setTimeout(() => {
      const stockSpan = document.querySelector('.stock');
      if (stockSpan) {
        const isInStock = stockSpan.classList.contains('in-stock');
        if (addToCartBtn) {
          addToCartBtn.disabled = !isInStock;
        }
        if (buyNowBtn) {
          buyNowBtn.disabled = !isInStock;
        }
      }
    }, 100);
  }

  /**
   * Initialize WooCommerce variations if present
   */
  function initializeWooCommerceVariations() {
    if (typeof jQuery === 'undefined') return;

    const $ = jQuery;
    const $form = $('form.variations_form');

    if ($form.length) {
      // WooCommerce variations form
      $form.on('found_variation', function(event, variation) {
        // Update price
        if (variation.display_price) {
          const priceEl = document.querySelector('.product-price-main');
          if (priceEl) {
            priceEl.textContent = variation.price_html || '$' + variation.display_price;
          }
        }

        // Update stock
        if (variation.is_in_stock) {
          const stockEl = document.querySelector('.product-stock');
          if (stockEl) {
            stockEl.textContent = variation.availability_html || 'In stock';
            stockEl.classList.remove('out-of-stock');
          }
        } else {
          const stockEl = document.querySelector('.product-stock');
          if (stockEl) {
            stockEl.textContent = 'Out of stock';
            stockEl.classList.add('out-of-stock');
          }
        }

        // Update image if variation has one
        if (variation.image && variation.image.src && heroImg) {
          heroImg.src = variation.image.src;
        }
      });

      $form.on('reset_data', function() {
        // Reset to default when variation is cleared
        const originalPrice = $form.data('original-price');
        if (originalPrice) {
          const priceEl = document.querySelector('.product-price-main');
          if (priceEl) {
            priceEl.textContent = originalPrice;
          }
        }
      });
    }
  }

  /**
   * Show toast notification
   */
  let toastTimer = null;
  function showToast(message) {
    let toast = document.getElementById('product-toast');

    if (!toast) {
      toast = document.createElement('div');
      toast.id = 'product-toast';
      toast.className = 'toast-notification';
      toast.setAttribute('role', 'status');
      toast.setAttribute('aria-live', 'polite');
      document.body.appendChild(toast);
    }

    toast.textContent = message;
    toast.classList.add('show');

    if (toastTimer) clearTimeout(toastTimer);
    toastTimer = setTimeout(() => {
      toast.classList.remove('show');
    }, 1700);
  }

  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
