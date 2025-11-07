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
      btn.textContent = btn.textContent === '♥' ? '♡' : '♥';
      // TODO: integrate with YITH wishlist or custom wishlist
    }

    // add to cart (in overlay)
    if(e.target.closest('.add-btn')){
      e.preventDefault();
      e.stopPropagation();
      const id = e.target.closest('.add-btn').dataset.id;

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
      .then(r => r.json())
      .then(response => {
        if (response.error) {
          alert('Error adding to cart');
        } else {
          // Trigger WooCommerce cart update event
          document.body.dispatchEvent(new Event('wc_fragment_refresh'));
          alert('Added to cart!');
        }
      })
      .catch(err => {
        console.error(err);
        alert('Added to cart!');
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
      const minLabel = $(selectors.priceMinLabel);
      const maxLabel = $(selectors.priceMaxLabel);

      if (minLabel) minLabel.textContent = '$' + (e.target.matches(selectors.priceMin) ? e.target.value : minEl.value);
      if (maxLabel) maxLabel.textContent = '$' + (e.target.matches(selectors.priceMax) ? e.target.value : maxEl.value);
      fetchProducts();
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
