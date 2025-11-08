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
    let selectedValues = {}; // label -> machine-value
    let quantity = 1;
    let inWishlist = false;
    let selectedVariationId = '';

    // Initialize default selected values from attributes_options
    function initDefaults() {
      const attrOpts = product.attributes_options || {};
      Object.keys(attrOpts).forEach(label => {
        const opts = attrOpts[label];
        if (opts && opts.length) {
          selectedValues[label] = opts[0].value;
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

    // Render options using attributes_options and attribute_map exactly as provided from server
    function renderOptions() {
      if (!optionsWrap) return;
      optionsWrap.innerHTML = '';

      const attrOpts = product.attributes_options || {};
      const attrMap = product.attribute_map || {};

      Object.keys(attrOpts).forEach(label => {
        const opts = attrOpts[label];
        const inputKey = attrMap[label] || '';

        const row = document.createElement('div');
        row.className = 'option-row';
        const labEl = document.createElement('label');
        labEl.textContent = label + ':';
        row.appendChild(labEl);

        if (/color/i.test(label) || label === 'Colors') {
          const colorWrap = document.createElement('div');
          colorWrap.className = 'color-btns';

          opts.forEach(opt => {
            const b = document.createElement('button');
            b.type = 'button';
            b.title = opt.label;
            // prefer hex machine values; if not hex, generate a color
            if (/^#/.test(opt.value)) b.style.backgroundColor = opt.value;
            else b.style.backgroundColor = stringToColor(opt.value || opt.label);

            b.dataset.attrLabel = label;
            b.dataset.attrKey = inputKey;
            b.dataset.attrValue = opt.value;

            b.className = (selectedValues[label] && selectedValues[label] === opt.value) ? 'active' : '';
            b.addEventListener('click', function () {
              selectedValues[label] = this.dataset.attrValue;
              Array.from(colorWrap.children).forEach(ch => ch.classList.toggle('active', ch === this));
              setHiddenAttributeInput(inputKey, this.dataset.attrValue);
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
            b.dataset.attrLabel = label;
            b.dataset.attrKey = inputKey;
            b.dataset.attrValue = opt.value;
            b.className = (selectedValues[label] && selectedValues[label] === opt.value) ? 'active' : '';
            b.addEventListener('click', function () {
              selectedValues[label] = this.dataset.attrValue;
              Array.from(btns.children).forEach(ch => ch.classList.toggle('active', ch === this));
              setHiddenAttributeInput(inputKey, this.dataset.attrValue);
              resolveVariation(true);
            });
            btns.appendChild(b);
          });
          row.appendChild(btns);
        }

        optionsWrap.appendChild(row);

        // set initial hidden input for this attribute
        setHiddenAttributeInput(inputKey, selectedValues[label] || (opts[0] && opts[0].value) || '');
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

    function setHiddenAttributeInput(inputKey, value) {
      if (!inputKey) return;
      const id = 'aakaari_attr_' + inputKey.replace(/[^a-z0-9_\-]/gi, '');
      const el = document.getElementById(id);
      if (el) {
        el.value = value;
      } else {
        const byName = document.querySelector('input[name="' + inputKey + '"]');
        if (byName) byName.value = value;
      }
    }

    // Resolve variation by comparing variation.attributes (machine values) with selectedValues (machine values)
    function resolveVariation(updatePrice = false) {
      selectedVariationId = '';
      if (!product.variations || !product.variations.length) {
        if (variationInput) variationInput.value = '';
        return;
      }

      // build map inputKey -> label from product.attribute_map
      const attrMap = product.attribute_map || {};
      const keyToLabel = {};
      Object.keys(attrMap).forEach(lbl => {
        keyToLabel[attrMap[lbl]] = lbl;
      });

      let match = null;
      for (let v of product.variations) {
        let ok = true;
        if (v.attributes) {
          for (let attrKey in v.attributes) {
            if (!v.attributes.hasOwnProperty(attrKey)) continue;
            const varVal = (v.attributes[attrKey] || '').toString().toLowerCase();
            const label = keyToLabel[attrKey] || null;
            let selectedVal = null;
            if (label) selectedVal = (selectedValues[label] || '').toString().toLowerCase();
            else {
              // fallback try to find any selectedValues that equal varVal
              selectedVal = Object.values(selectedValues).find(sv => sv && sv.toString().toLowerCase() === varVal) || '';
            }
            if (selectedVal === '' || selectedVal !== varVal) {
              ok = false;
              break;
            }
          }
        }
        if (ok) {
          match = v;
          break;
        }
      }

      if (match) {
        selectedVariationId = match.variation_id || '';
        if (variationInput) variationInput.value = selectedVariationId;
        // price update if available
        if (updatePrice && match.price_html) {
          if (priceCurrent) priceCurrent.innerHTML = match.price_html;
          if (priceOld) priceOld.style.display = 'none';
        } else if (updatePrice && match.display_price) {
          if (priceCurrent) priceCurrent.innerHTML = formatPrice(match.display_price);
          if (priceOld) priceOld.style.display = 'none';
        }
      } else {
        selectedVariationId = '';
        if (variationInput) variationInput.value = '';
        // fallback to base product price_html
        if (updatePrice && product.price_html) {
          if (priceCurrent) priceCurrent.innerHTML = product.price_html;
        }
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
        // Fill hidden attribute inputs from selectedValues
        const attrMap = product.attribute_map || {};
        Object.keys(attrMap).forEach(label => {
          const inputKey = attrMap[label];
          const value = selectedValues[label] || '';
          setHiddenAttributeInput(inputKey, value);
        });

        if (qtyInput) qtyInput.value = String(quantity);
        if (variationInput) variationInput.value = selectedVariationId || '';
        if (buyNowInput) buyNowInput.value = '0';

        if (addToCartForm) addToCartForm.submit();
        else showToast('Unable to add to cart (form missing).');
      });

      buyNowBtn && buyNowBtn.addEventListener('click', function () {
        const attrMap = product.attribute_map || {};
        Object.keys(attrMap).forEach(label => {
          const inputKey = attrMap[label];
          const value = selectedValues[label] || '';
          setHiddenAttributeInput(inputKey, value);
        });

        if (qtyInput) qtyInput.value = String(quantity);
        if (variationInput) variationInput.value = selectedVariationId || '';
        if (buyNowInput) buyNowInput.value = '1';

        if (addToCartForm) addToCartForm.submit();
        else showToast('Unable to proceed to checkout (form missing).');
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
