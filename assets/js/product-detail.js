// assets/js/product-detail.js (ATTRIBUTE + VARIATION PRICE FIX)
(function () {
  'use strict';

  function ready(fn) {
    if (document.readyState !== 'loading') {
      fn();
    } else {
      document.addEventListener('DOMContentLoaded', fn);
    }
  }

  ready(function () {
    const product = window.aakaari_product || null;
    if (!product) return;

    // Elements
    const mainImage = document.getElementById('mainImage');
    const thumbnailList = document.getElementById('thumbnailList');
    const productName = document.getElementById('productName');
    const productDesc = document.getElementById('productDesc');
    const priceCurrent = document.getElementById('priceCurrent');
    const priceOld = document.getElementById('priceOld');
    const discountBadge = document.getElementById('discountBadge');
    const starsRow = document.getElementById('starsRow');
    const ratingText = document.getElementById('ratingText');
    const optionsWrap = document.getElementById('optionsWrap');
    const stockText = document.getElementById('stockText');
    const qtyNumber = document.getElementById('qtyNumber');
    const qtyInc = document.getElementById('qtyInc');
    const qtyDec = document.getElementById('qtyDec');
    const addCartBtn = document.getElementById('addCartBtn');
    const buyNowBtn = document.getElementById('buyNowBtn');
    const wishlistBtn = document.getElementById('wishlistBtn');

    // Form hidden inputs
    const qtyInput = document.getElementById('aakaari_qty_input');
    const variationInput = document.getElementById('aakaari_variation_id');
    const buyNowInput = document.getElementById('aakaari_buy_now');
    const addToCartForm = document.getElementById('aakaari_add_to_cart_form');

    // State
    let selectedImage = 0;
    let selectedValues = {}; // unique_key -> machine-value
    let quantity = 1;
    let inWishlist = false;
    let selectedVariationId = '';

    // Helper: Create consistent hidden input ID (matches PHP sanitize_key)
    function makeHiddenId(inputKey) {
      // Normalize to match PHP sanitize_key: lowercase, keep a-z0-9 and underscores only
      return 'aakaari_attr_' + String(inputKey).toLowerCase().replace(/[^a-z0-9_]/g, '');
    }

    // Helper: Set hidden attribute input value (with fallback)
    function setHiddenAttributeInput(inputKey, value) {
      if (!inputKey) {
        return;
      }

      // Try by ID first (normalized)
      const id = makeHiddenId(inputKey);
      const el = document.getElementById(id);
      if (el) {
        el.value = value;
        return;
      }

      // Fallback: try by name attribute
      const byName = document.querySelector('input[name="' + inputKey + '"]');
      if (byName) {
        byName.value = value;
      }
    }

    // Initialize default selected values from attributes_options (use slug as canonical value)
    function initDefaults() {
      const attrOpts = product.attributes_options || {};
      Object.keys(attrOpts).forEach(uniqueKey => {
        const attrData = attrOpts[uniqueKey];
        const opts = attrData.options || attrData;
        if (Array.isArray(opts) && opts.length) {
          // Use value_slug as canonical value (fallback to value for backward compatibility)
          selectedValues[uniqueKey] = opts[0].value_slug || opts[0].value;
        }
      });
    }

    function init() {
      initDefaults();

      if (productName) productName.textContent = product.name;
      if (productDesc) productDesc.textContent = product.description || '';
      if (priceCurrent) priceCurrent.innerHTML = product.price_html || formatPrice(product.salePrice || product.price);

      if (product.salePrice && product.salePrice < product.price) {
        if (priceOld) priceOld.innerHTML = formatPrice(product.price);
        if (discountBadge) {
          const discount = Math.round(((product.price - product.salePrice) / product.price) * 100);
          discountBadge.style.display = 'inline-block';
          discountBadge.textContent = '-' + discount + '%';
        }
      } else {
        if (priceOld) priceOld.style.display = 'none';
        if (discountBadge) discountBadge.style.display = 'none';
      }

      renderStars(product.rating || 0);
      if (ratingText) ratingText.textContent = (product.rating || 0).toFixed(1) + ' (' + (product.reviewCount || 0) + ' reviews)';

      renderThumbnails();
      setMainImage(0);

      renderOptions(); // sets hidden inputs for defaults
      resolveVariation(); // try to resolve initial variation (update price if matched)

      updateStockDisplay();

      bindEvents();
    }

    function formatPrice(v) {
      if (typeof v === 'number') return '$' + v.toFixed(2);
      return v;
    }

    function renderStars(rating) {
      if (!starsRow) return;
      starsRow.innerHTML = '';
      const full = Math.floor(rating || 0);
      for (let i = 0; i < 5; i++) {
        const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        svg.setAttribute('viewBox', '0 0 24 24');
        svg.setAttribute('width', '16');
        svg.setAttribute('height', '16');
        svg.setAttribute('aria-hidden', 'true');
        svg.innerHTML = i < full
          ? '<path d="M12 .587l3.668 7.431L24 9.748l-6 5.848 1.416 8.279L12 19.77 4.584 23.875 6 15.596 0 9.748l8.332-1.73z" class="star-filled"></path>'
          : '<path d="M12 17.27l6.18 3.73-1.64-7.03L21.5 9.24l-7.19-.62L12 2 9.69 8.62 2.5 9.24l5.96 4.72-1.64 7.03z" class="star-empty" fill="none"></path>';
        starsRow.appendChild(svg);
      }
    }

    function renderThumbnails() {
      if (!thumbnailList) return;
      thumbnailList.innerHTML = '';
      product.images.forEach((src, idx) => {
        const btn = document.createElement('button');
        btn.className = 'thumbnail-btn' + (idx === selectedImage ? ' active' : '');
        btn.type = 'button';
        btn.setAttribute('aria-label', 'View image ' + (idx + 1));
        const img = document.createElement('img');
        img.src = src;
        img.alt = product.name + ' image ' + (idx + 1);
        btn.appendChild(img);
        btn.addEventListener('click', () => {
          setMainImage(idx);
          Array.from(thumbnailList.children).forEach((c, i) => c.classList.toggle('active', i === idx));
        });
        thumbnailList.appendChild(btn);
      });
    }

    function setMainImage(idx) {
      selectedImage = idx;
      if (mainImage) {
        mainImage.src = product.images[idx];
        mainImage.alt = product.name + ' image ' + (idx + 1);
      }
    }

    // Render options using attributes_options and attribute_map (use slugs as canonical values)
    function renderOptions() {
      if (!optionsWrap) return;
      optionsWrap.innerHTML = '';

      const attrOpts = product.attributes_options || {};
      const attrMap = product.attribute_map || {};

      Object.keys(attrOpts).forEach(uniqueKey => {
        const attrData = attrOpts[uniqueKey];
        const inputKey = attrData.input_key || attrMap[uniqueKey] || '';

        // Use ui_label from new format, fallback to display_label or uniqueKey
        const displayLabel = attrData.ui_label || attrData.display_label || uniqueKey;
        const opts = attrData.options || attrData;

        if (!Array.isArray(opts) || !opts.length) return;

        const row = document.createElement('div');
        row.className = 'option-row';
        const labEl = document.createElement('label');
        labEl.textContent = displayLabel + ':';
        row.appendChild(labEl);

        // Check if this is a color attribute (by display label)
        const isColorAttr = /color/i.test(displayLabel);

        if (isColorAttr) {
          const colorWrap = document.createElement('div');
          colorWrap.className = 'color-btns';

          opts.forEach(opt => {
            const b = document.createElement('button');
            b.type = 'button';
            b.title = opt.label;

            // Use value_slug as canonical value
            const valueSlug = opt.value_slug || opt.value;

            // Try to detect color from slug or fallback to generated color
            if (/^#/.test(valueSlug)) {
              b.style.backgroundColor = valueSlug;
            } else {
              b.style.backgroundColor = stringToColor(valueSlug || opt.label);
            }

            b.dataset.uniqueKey = uniqueKey;
            b.dataset.attrKey = inputKey;
            b.dataset.attrValueSlug = valueSlug; // canonical slug
            b.dataset.attrValueId = opt.value_id || 0;

            b.className = (selectedValues[uniqueKey] && selectedValues[uniqueKey] === valueSlug) ? 'active' : '';
            b.addEventListener('click', function () {
              selectedValues[uniqueKey] = this.dataset.attrValueSlug; // use slug
              Array.from(colorWrap.children).forEach(ch => ch.classList.toggle('active', ch === this));
              setHiddenAttributeInput(inputKey, this.dataset.attrValueSlug); // post slug to form
              resolveVariation(true);
            });
            colorWrap.appendChild(b);
          });

          row.appendChild(colorWrap);
        } else {
          const btns = document.createElement('div');
          btns.className = 'option-btns';
          opts.forEach(opt => {
            const b = document.createElement('button');
            b.type = 'button';
            b.textContent = opt.label;

            // Use value_slug as canonical value
            const valueSlug = opt.value_slug || opt.value;

            b.dataset.uniqueKey = uniqueKey;
            b.dataset.attrKey = inputKey;
            b.dataset.attrValueSlug = valueSlug; // canonical slug
            b.dataset.attrValueId = opt.value_id || 0;

            b.className = (selectedValues[uniqueKey] && selectedValues[uniqueKey] === valueSlug) ? 'active' : '';
            b.addEventListener('click', function () {
              selectedValues[uniqueKey] = this.dataset.attrValueSlug; // use slug
              Array.from(btns.children).forEach(ch => ch.classList.toggle('active', ch === this));
              setHiddenAttributeInput(inputKey, this.dataset.attrValueSlug); // post slug to form
              resolveVariation(true);
            });
            btns.appendChild(b);
          });
          row.appendChild(btns);
        }

        optionsWrap.appendChild(row);

        // Set initial hidden input for this attribute (use slug)
        const initialValue = selectedValues[uniqueKey] || opts[0].value_slug || opts[0].value || '';
        setHiddenAttributeInput(inputKey, initialValue);
      });
    }

    function stringToColor(str) {
      let hash = 0;
      for (let i = 0; i < str.length; i++) {
        hash = str.charCodeAt(i) + ((hash << 5) - hash);
      }
      const c = (hash & 0x00FFFFFF).toString(16).toUpperCase();
      return '#' + '00000'.substring(0, 6 - c.length) + c;
    }

    // Resolve variation by comparing slugs (PHP normalized all variation attributes to slugs)
    function resolveVariation(updatePrice = false) {
      selectedVariationId = '';
      if (!product.variations || !product.variations.length) {
        if (variationInput) variationInput.value = '';
        return;
      }

      // Build map: inputKey (e.g., attribute_pa_color) -> uniqueKey (e.g., Colors)
      const attrMap = product.attribute_map || {};
      const inputKeyToUniqueKey = {};
      Object.keys(attrMap).forEach(uniqueKey => {
        const inputKey = attrMap[uniqueKey];
        inputKeyToUniqueKey[inputKey] = uniqueKey;
      });

      let match = null;

      // Try to find matching variation (compare slugs)
      for (let v of product.variations) {
        if (!v.attributes) continue;

        let ok = true;

        // Check all variation attributes match selected values (both are slugs now)
        for (let attrKey in v.attributes) {
          if (!v.attributes.hasOwnProperty(attrKey)) continue;

          // Variation attribute value (already normalized to slug by PHP)
          const varValSlug = (v.attributes[attrKey] || '').toString().toLowerCase().trim();

          // Find corresponding uniqueKey for this attribute
          const uniqueKey = inputKeyToUniqueKey[attrKey];

          // Get selected slug value
          let selectedValSlug = '';
          if (uniqueKey && selectedValues[uniqueKey]) {
            selectedValSlug = (selectedValues[uniqueKey] || '').toString().toLowerCase().trim();
          }

          // Compare slugs (both sides are slugs now)
          if (selectedValSlug === '' || selectedValSlug !== varValSlug) {
            ok = false;
            break;
          }
        }

        if (ok) {
          match = v;
          break; // Found exact match
        }
      }

      if (match) {
        selectedVariationId = match.variation_id || '';
        if (variationInput) variationInput.value = selectedVariationId;

        // Update price if available
        if (updatePrice) {
          if (match.price_html) {
            if (priceCurrent) priceCurrent.innerHTML = match.price_html;
            if (priceOld) priceOld.style.display = 'none';
          } else if (match.display_price) {
            if (priceCurrent) priceCurrent.innerHTML = formatPrice(match.display_price);
            if (priceOld) priceOld.style.display = 'none';
          }
        }

        // Debug log (showing slugs)
        console.log('✓ Variation matched:', match.variation_id, 'Selected slugs:', selectedValues, 'Variation attrs:', match.attributes);
      } else {
        selectedVariationId = '';
        if (variationInput) variationInput.value = '';

        // Fallback to base product price_html
        if (updatePrice && product.price_html) {
          if (priceCurrent) priceCurrent.innerHTML = product.price_html;
        }

        // Debug log (showing slugs)
        console.log('✗ No variation match. Selected slugs:', selectedValues, 'Available variations:', product.variations.map(v => v.attributes));
      }
    }

    function updateStockDisplay() {
      if (!stockText || typeof product.stock === 'undefined') return;
      stockText.textContent = product.stock > 0 ? (product.stock + ' in stock') : 'Out of stock';
      qtyNumber.textContent = String(quantity);
      if (qtyInput) qtyInput.value = String(quantity);
      if (qtyInc) qtyInc.disabled = product.stock <= quantity;
      if (qtyDec) qtyDec.disabled = quantity <= 1;
      if (addCartBtn) addCartBtn.disabled = product.stock === 0;
      if (buyNowBtn) buyNowBtn.disabled = product.stock === 0;
    }

    function bindEvents() {
      qtyInc && qtyInc.addEventListener('click', function () {
        if (quantity < product.stock) {
          quantity++;
          updateStockDisplay();
        }
      });
      qtyDec && qtyDec.addEventListener('click', function () {
        if (quantity > 1) {
          quantity--;
          updateStockDisplay();
        }
      });

      addCartBtn && addCartBtn.addEventListener('click', function () {
        // Validate variable products have variation selected
        if (product.productType === 'variable' && !selectedVariationId) {
          showToast('Please select all product options (color, size, etc.)');
          return;
        }

        // Prevent double submissions
        if (addCartBtn.disabled) return;
        addCartBtn.disabled = true;

        // Fill hidden attribute inputs from selectedValues
        const attrMap = product.attribute_map || {};
        Object.keys(attrMap).forEach(uniqueKey => {
          const inputKey = attrMap[uniqueKey];
          const value = selectedValues[uniqueKey] || '';
          setHiddenAttributeInput(inputKey, value);
        });

        if (qtyInput) qtyInput.value = String(quantity);
        if (variationInput) variationInput.value = selectedVariationId || '';
        if (buyNowInput) buyNowInput.value = '0';

        if (addToCartForm) {
          addToCartForm.submit();
        } else {
          showToast('Unable to add to cart (form missing).');
          addCartBtn.disabled = false;
        }
      });

      buyNowBtn && buyNowBtn.addEventListener('click', function () {
        // Validate variable products have variation selected
        if (product.productType === 'variable' && !selectedVariationId) {
          showToast('Please select all product options (color, size, etc.)');
          return;
        }

        // Prevent double submissions
        if (buyNowBtn.disabled) return;
        buyNowBtn.disabled = true;

        const attrMap = product.attribute_map || {};
        Object.keys(attrMap).forEach(uniqueKey => {
          const inputKey = attrMap[uniqueKey];
          const value = selectedValues[uniqueKey] || '';
          setHiddenAttributeInput(inputKey, value);
        });

        if (qtyInput) qtyInput.value = String(quantity);
        if (variationInput) variationInput.value = selectedVariationId || '';
        if (buyNowInput) buyNowInput.value = '1';

        if (addToCartForm) {
          addToCartForm.submit();
        } else {
          showToast('Unable to proceed to checkout (form missing).');
          buyNowBtn.disabled = false;
        }
      });

      wishlistBtn && wishlistBtn.addEventListener('click', function () {
        inWishlist = !inWishlist;
        wishlistBtn.setAttribute('aria-pressed', String(inWishlist));
        const heart = document.getElementById('heartIcon');
        if (inWishlist) {
          heart && heart.setAttribute('fill', '#e53e3e');
          heart && heart.setAttribute('stroke', '#e53e3e');
          showToast('Added to wishlist');
        } else {
          heart && heart.setAttribute('fill', 'none');
          heart && heart.setAttribute('stroke', '#333');
          showToast('Removed from wishlist');
        }
      });

      const backBtn = document.getElementById('backBtn');
      backBtn && backBtn.addEventListener('click', function () {
        if (document.referrer && document.referrer.indexOf(window.location.origin) === 0) {
          window.history.back();
        } else {
          window.location.href = (window.aakaari_ajax && window.aakaari_ajax.home_url) ? (window.aakaari_ajax.home_url + '/shop/') : '/shop/';
        }
      });
    }

    function showToast(msg) {
      const t = document.createElement('div');
      t.textContent = msg;
      t.style.position = 'fixed';
      t.style.right = '16px';
      t.style.bottom = '16px';
      t.style.background = 'rgba(17,24,39,0.95)';
      t.style.color = '#fff';
      t.style.padding = '10px 14px';
      t.style.borderRadius = '8px';
      t.style.fontSize = '14px';
      t.style.zIndex = 9999;
      t.style.boxShadow = '0 6px 18px rgba(0,0,0,0.12)';
      document.body.appendChild(t);
      setTimeout(function () {
        t.style.transition = 'opacity 300ms';
        t.style.opacity = '0';
        setTimeout(function () { t.remove(); }, 320);
      }, 1500);
    }

    init();
  });
})();
