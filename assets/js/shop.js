/**
 * Shop Page JavaScript
 * Handles filtering, sorting, and product interactions
 */

(function($) {
  'use strict';

  // Initialize on document ready
  $(document).ready(function() {
    ShopFilters.init();
    ShopSort.init();
    ShopMobile.init();
  });

  /**
   * Shop Filters Module
   */
  const ShopFilters = {
    filters: {
      categories: [],
      colors: [],
      sizes: [],
      priceMin: 0,
      priceMax: 500,
      customizable: false
    },

    init: function() {
      this.bindEvents();
      this.loadFiltersFromURL();
    },

    bindEvents: function() {
      // Category filters
      $('.shop-filter-checkbox input[type="checkbox"][data-filter="category"]').on('change', this.handleCategoryChange.bind(this));

      // Color filters
      $('.shop-color-swatch').on('click', this.handleColorChange.bind(this));

      // Size filters
      $('.shop-size-button').on('click', this.handleSizeChange.bind(this));

      // Price range
      $('#price-min, #price-max').on('change', this.handlePriceChange.bind(this));

      // Customizable filter
      $('input[name="customizable"]').on('change', this.handleCustomizableChange.bind(this));

      // Clear filters
      $('.shop-clear-filters').on('click', this.clearFilters.bind(this));
    },

    handleCategoryChange: function(e) {
      const checkbox = $(e.target);
      const category = checkbox.val();

      if (checkbox.is(':checked')) {
        this.filters.categories.push(category);
      } else {
        const index = this.filters.categories.indexOf(category);
        if (index > -1) {
          this.filters.categories.splice(index, 1);
        }
      }

      this.applyFilters();
    },

    handleColorChange: function(e) {
      const swatch = $(e.currentTarget);
      const color = swatch.data('color');

      if (swatch.hasClass('active')) {
        swatch.removeClass('active');
        const index = this.filters.colors.indexOf(color);
        if (index > -1) {
          this.filters.colors.splice(index, 1);
        }
      } else {
        swatch.addClass('active');
        this.filters.colors.push(color);
      }

      this.applyFilters();
    },

    handleSizeChange: function(e) {
      const button = $(e.currentTarget);
      const size = button.data('size');

      if (button.hasClass('active')) {
        button.removeClass('active');
        const index = this.filters.sizes.indexOf(size);
        if (index > -1) {
          this.filters.sizes.splice(index, 1);
        }
      } else {
        button.addClass('active');
        this.filters.sizes.push(size);
      }

      this.applyFilters();
    },

    handlePriceChange: function() {
      this.filters.priceMin = parseFloat($('#price-min').val()) || 0;
      this.filters.priceMax = parseFloat($('#price-max').val()) || 500;

      this.applyFilters();
    },

    handleCustomizableChange: function(e) {
      this.filters.customizable = $(e.target).is(':checked');
      this.applyFilters();
    },

    applyFilters: function() {
      // Update URL with filters
      this.updateURL();

      // Send AJAX request to filter products
      $.ajax({
        url: woocommerce_params.ajax_url,
        type: 'POST',
        data: {
          action: 'filter_products',
          filters: this.filters,
          nonce: woocommerce_params.filter_nonce
        },
        beforeSend: function() {
          $('.shop-product-grid').addClass('loading');
          $('.shop-product-grid').css('opacity', '0.5');
        },
        success: function(response) {
          if (response.success) {
            $('.shop-product-grid').html(response.data.html);
            $('.shop-product-count').text(response.data.count + ' products');
          }
        },
        complete: function() {
          $('.shop-product-grid').removeClass('loading');
          $('.shop-product-grid').css('opacity', '1');
        }
      });
    },

    clearFilters: function() {
      this.filters = {
        categories: [],
        colors: [],
        sizes: [],
        priceMin: 0,
        priceMax: 500,
        customizable: false
      };

      // Reset UI
      $('.shop-filter-checkbox input[type="checkbox"]').prop('checked', false);
      $('.shop-color-swatch').removeClass('active');
      $('.shop-size-button').removeClass('active');
      $('#price-min').val(0);
      $('#price-max').val(500);
      $('input[name="customizable"]').prop('checked', false);

      this.applyFilters();
    },

    loadFiltersFromURL: function() {
      const urlParams = new URLSearchParams(window.location.search);

      // Load categories
      if (urlParams.has('categories')) {
        this.filters.categories = urlParams.get('categories').split(',');
      }

      // Load colors
      if (urlParams.has('colors')) {
        this.filters.colors = urlParams.get('colors').split(',');
      }

      // Load sizes
      if (urlParams.has('sizes')) {
        this.filters.sizes = urlParams.get('sizes').split(',');
      }

      // Load price range
      if (urlParams.has('price_min')) {
        this.filters.priceMin = parseFloat(urlParams.get('price_min'));
      }
      if (urlParams.has('price_max')) {
        this.filters.priceMax = parseFloat(urlParams.get('price_max'));
      }

      // Load customizable
      if (urlParams.has('customizable')) {
        this.filters.customizable = urlParams.get('customizable') === 'true';
      }

      // Update UI to reflect loaded filters
      this.updateUIFromFilters();
    },

    updateUIFromFilters: function() {
      // Update category checkboxes
      this.filters.categories.forEach(category => {
        $('input[type="checkbox"][value="' + category + '"]').prop('checked', true);
      });

      // Update color swatches
      this.filters.colors.forEach(color => {
        $('.shop-color-swatch[data-color="' + color + '"]').addClass('active');
      });

      // Update size buttons
      this.filters.sizes.forEach(size => {
        $('.shop-size-button[data-size="' + size + '"]').addClass('active');
      });

      // Update price inputs
      $('#price-min').val(this.filters.priceMin);
      $('#price-max').val(this.filters.priceMax);

      // Update customizable checkbox
      $('input[name="customizable"]').prop('checked', this.filters.customizable);
    },

    updateURL: function() {
      const params = new URLSearchParams();

      if (this.filters.categories.length > 0) {
        params.set('categories', this.filters.categories.join(','));
      }
      if (this.filters.colors.length > 0) {
        params.set('colors', this.filters.colors.join(','));
      }
      if (this.filters.sizes.length > 0) {
        params.set('sizes', this.filters.sizes.join(','));
      }
      if (this.filters.priceMin > 0) {
        params.set('price_min', this.filters.priceMin);
      }
      if (this.filters.priceMax < 500) {
        params.set('price_max', this.filters.priceMax);
      }
      if (this.filters.customizable) {
        params.set('customizable', 'true');
      }

      const newURL = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
      window.history.pushState({}, '', newURL);
    }
  };

  /**
   * Shop Sort Module
   */
  const ShopSort = {
    init: function() {
      this.bindEvents();
    },

    bindEvents: function() {
      $('.shop-sort-dropdown').on('change', this.handleSortChange.bind(this));
    },

    handleSortChange: function(e) {
      const sortBy = $(e.target).val();

      // Send AJAX request to sort products
      $.ajax({
        url: woocommerce_params.ajax_url,
        type: 'POST',
        data: {
          action: 'sort_products',
          sort_by: sortBy,
          filters: ShopFilters.filters,
          nonce: woocommerce_params.sort_nonce
        },
        beforeSend: function() {
          $('.shop-product-grid').addClass('loading');
          $('.shop-product-grid').css('opacity', '0.5');
        },
        success: function(response) {
          if (response.success) {
            $('.shop-product-grid').html(response.data.html);
          }
        },
        complete: function() {
          $('.shop-product-grid').removeClass('loading');
          $('.shop-product-grid').css('opacity', '1');
        }
      });
    }
  };

  /**
   * Shop Mobile Module
   */
  const ShopMobile = {
    init: function() {
      this.bindEvents();
    },

    bindEvents: function() {
      // Mobile filter toggle
      $('.shop-filter-toggle').on('click', this.toggleFilters.bind(this));

      // Close filter panel
      $('.shop-filter-close, .shop-filter-overlay').on('click', this.closeFilters.bind(this));
    },

    toggleFilters: function() {
      $('.shop-filter-panel').toggleClass('active');
      $('.shop-filter-overlay').toggleClass('active');
      $('body').toggleClass('no-scroll');
    },

    closeFilters: function() {
      $('.shop-filter-panel').removeClass('active');
      $('.shop-filter-overlay').removeClass('active');
      $('body').removeClass('no-scroll');
    }
  };

  // Add to wishlist functionality
  $(document).on('click', '.add-to-wishlist', function(e) {
    e.preventDefault();
    e.stopPropagation();

    const button = $(this);
    const productId = button.data('product-id');

    $.ajax({
      url: woocommerce_params.ajax_url,
      type: 'POST',
      data: {
        action: 'add_to_wishlist',
        product_id: productId,
        nonce: woocommerce_params.wishlist_nonce
      },
      success: function(response) {
        if (response.success) {
          button.addClass('in-wishlist');
          // Show notification
          if (typeof window.showNotification === 'function') {
            window.showNotification('Added to wishlist!');
          }
        }
      }
    });
  });

  // Quick view functionality
  $(document).on('click', '.quick-view', function(e) {
    e.preventDefault();
    e.stopPropagation();

    const productId = $(this).data('product-id');

    $.ajax({
      url: woocommerce_params.ajax_url,
      type: 'POST',
      data: {
        action: 'quick_view_product',
        product_id: productId,
        nonce: woocommerce_params.quickview_nonce
      },
      success: function(response) {
        if (response.success) {
          // Show quick view modal
          $('body').append(response.data.html);
          $('.quick-view-modal').fadeIn();
        }
      }
    });
  });

})(jQuery);
