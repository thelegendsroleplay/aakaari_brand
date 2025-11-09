/**
 * products.js - Shop Page JavaScript
 * Handles filtering, sorting, AJAX product loading, and interactions
 */

(function($) {
    'use strict';

    // State management
    const state = {
        filters: {
            categories: [],
            sizes: [],
            colors: [],
            priceMin: 0,
            priceMax: 2000,
            rating: 0,
            sortBy: 'popularity'
        },
        isLoading: false
    };

    /**
     * Initialize shop page functionality
     */
    function init() {
        if (!$('#products-grid').length) {
            return; // Not on shop page
        }

        initFilters();
        initPriceRangeSliders();
        initSorting();
        initToggleFilters();
        initClearFilters();
        initProductInteractions();

        // Load initial products
        loadProducts();
    }

    /**
     * Initialize filter checkboxes and color swatches
     */
    function initFilters() {
        // Category filters
        $('#categories-list input[type="checkbox"]').on('change', function() {
            const category = $(this).data('category');
            if ($(this).is(':checked')) {
                if (!state.filters.categories.includes(category)) {
                    state.filters.categories.push(category);
                }
            } else {
                state.filters.categories = state.filters.categories.filter(c => c !== category);
            }
            loadProducts();
        });

        // Size filters
        $('#sizes-list input[type="checkbox"]').on('change', function() {
            const size = $(this).data('size');
            if ($(this).is(':checked')) {
                if (!state.filters.sizes.includes(size)) {
                    state.filters.sizes.push(size);
                }
            } else {
                state.filters.sizes = state.filters.sizes.filter(s => s !== size);
            }
            loadProducts();
        });

        // Color swatches
        $('#colors-list .color-swatch').on('click', function() {
            const color = $(this).data('color');
            $(this).toggleClass('active');

            if ($(this).hasClass('active')) {
                if (!state.filters.colors.includes(color)) {
                    state.filters.colors.push(color);
                }
            } else {
                state.filters.colors = state.filters.colors.filter(c => c !== color);
            }
            loadProducts();
        });

        // Rating filters (only one can be selected at a time)
        $('#rating-list input[type="checkbox"]').on('change', function() {
            if ($(this).is(':checked')) {
                // Uncheck all other rating filters
                $('#rating-list input[type="checkbox"]').not(this).prop('checked', false);
                state.filters.rating = parseInt($(this).data('rating'));
            } else {
                state.filters.rating = 0;
            }
            loadProducts();
        });
    }

    /**
     * Initialize price range sliders
     */
    function initPriceRangeSliders() {
        const $minSlider = $('#price-min');
        const $maxSlider = $('#price-max');
        const $minLabel = $('#price-min-label');
        const $maxLabel = $('#price-max-label');

        function updatePriceLabels() {
            const min = parseInt($minSlider.val());
            const max = parseInt($maxSlider.val());

            // Prevent min from exceeding max
            if (min > max) {
                $minSlider.val(max);
                state.filters.priceMin = max;
            } else {
                state.filters.priceMin = min;
            }

            // Prevent max from being less than min
            if (max < min) {
                $maxSlider.val(min);
                state.filters.priceMax = min;
            } else {
                state.filters.priceMax = max;
            }

            $minLabel.text('$' + state.filters.priceMin);
            $maxLabel.text('$' + state.filters.priceMax);
        }

        // Update labels on input
        $minSlider.on('input', updatePriceLabels);
        $maxSlider.on('input', updatePriceLabels);

        // Load products on change (when user releases slider)
        $minSlider.on('change', loadProducts);
        $maxSlider.on('change', loadProducts);
    }

    /**
     * Initialize sorting dropdown
     */
    function initSorting() {
        $('#sort-by').on('change', function() {
            state.filters.sortBy = $(this).val();
            loadProducts();
        });
    }

    /**
     * Initialize toggle filters button
     */
    function initToggleFilters() {
        $('#toggle-filters').on('click', function() {
            const $sidebar = $('#filters');
            const $toggleText = $('#filters-toggle-text');

            $sidebar.toggleClass('hidden');

            if ($sidebar.hasClass('hidden')) {
                $toggleText.text('Show');
            } else {
                $toggleText.text('Hide');
            }
        });
    }

    /**
     * Initialize clear filters buttons
     */
    function initClearFilters() {
        $('#clear-filters, #clear-filters-2').on('click', function() {
            clearAllFilters();
        });
    }

    /**
     * Clear all active filters
     */
    function clearAllFilters() {
        // Reset state
        state.filters.categories = [];
        state.filters.sizes = [];
        state.filters.colors = [];
        state.filters.priceMin = 0;
        state.filters.priceMax = 2000;
        state.filters.rating = 0;
        state.filters.sortBy = 'popularity';

        // Reset UI elements
        $('#categories-list input[type="checkbox"]').prop('checked', false);
        $('#sizes-list input[type="checkbox"]').prop('checked', false);
        $('#colors-list .color-swatch').removeClass('active');
        $('#rating-list input[type="checkbox"]').prop('checked', false);
        $('#price-min').val(0);
        $('#price-max').val(2000);
        $('#price-min-label').text('$0');
        $('#price-max-label').text('$2000');
        $('#sort-by').val('popularity');

        // Reload products
        loadProducts();
    }

    /**
     * Load products via AJAX
     */
    function loadProducts() {
        if (state.isLoading) {
            return; // Prevent multiple simultaneous requests
        }

        state.isLoading = true;
        const $grid = $('#products-grid');
        const $count = $('#products-count');

        // Show loading state
        $grid.addClass('loading').css('opacity', '0.5');

        $.ajax({
            url: aakaari_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'aakaari_filter_products',
                nonce: aakaari_ajax.nonce,
                filters: JSON.stringify(state.filters)
            },
            success: function(response) {
                if (response.success) {
                    $grid.html(response.data.html);

                    // Update product count
                    const count = response.data.count || 0;
                    $count.text(count + ' Product' + (count !== 1 ? 's' : ''));

                    // Re-initialize product interactions for new products
                    initProductInteractions();

                    // Scroll to top of products grid smoothly
                    $('html, body').animate({
                        scrollTop: $grid.offset().top - 100
                    }, 300);
                } else {
                    console.error('Failed to load products:', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                $grid.html('<div class="no-products"><h3>Error loading products</h3><p>Please try again later.</p></div>');
            },
            complete: function() {
                state.isLoading = false;
                $grid.removeClass('loading').css('opacity', '1');
            }
        });
    }

    /**
     * Initialize product card interactions
     */
    function initProductInteractions() {
        initProductHover();
        initAddToCart();
        initWishlist();
        initProductCardClick();
    }

    /**
     * Handle product card hover for image swap
     */
    function initProductHover() {
        $('.product-card-image, .product-media').off('mouseenter mouseleave').hover(
            function() {
                // Mouse enter
                const $default = $(this).find('.default-image');
                const $hover = $(this).find('.hover-image');

                if ($hover.length) {
                    $default.fadeOut(200);
                    $hover.fadeIn(200);
                }
            },
            function() {
                // Mouse leave
                const $default = $(this).find('.default-image');
                const $hover = $(this).find('.hover-image');

                if ($hover.length) {
                    $hover.fadeOut(200);
                    $default.fadeIn(200);
                }
            }
        );
    }

    /**
     * Handle add to cart functionality
     */
    function initAddToCart() {
        // Remove previous handlers to avoid duplicates
        $(document).off('click', '.product-add-to-cart-btn, .ajax_add_to_cart');

        // Add to cart handler
        $(document).on('click', '.product-add-to-cart-btn, .ajax_add_to_cart', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $button = $(this);
            const productId = $button.data('id');
            const productType = $button.data('product_type');

            // Handle variable products - redirect to product page
            if (productType === 'variable' || productType === 'grouped') {
                const $card = $button.closest('.product-card');
                const productUrl = $card.data('product-url');
                if (productUrl) {
                    window.location.href = productUrl;
                }
                return;
            }

            // Disable button during request
            $button.prop('disabled', true).addClass('loading');
            const originalText = $button.html();
            $button.html('<svg class="spinner" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle></svg>');

            $.ajax({
                url: aakaari_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'woocommerce_ajax_add_to_cart',
                    product_id: productId,
                    quantity: 1
                },
                success: function(response) {
                    if (response.error) {
                        showToast('Error adding to cart', 'error');
                    } else {
                        // Trigger WooCommerce cart update event
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);

                        // Show success toast
                        showToast('Added to cart!', 'success');

                        // Update cart count in header if it exists
                        if (response.fragments && response.fragments['.cart-count']) {
                            $('.cart-count').html(response.fragments['.cart-count']);
                        }
                    }
                },
                error: function() {
                    showToast('Error adding to cart', 'error');
                },
                complete: function() {
                    $button.prop('disabled', false).removeClass('loading');
                    $button.html(originalText);
                }
            });
        });
    }

    /**
     * Handle wishlist functionality
     */
    function initWishlist() {
        // Remove previous handlers
        $(document).off('click', '.product-wishlist-btn');

        $(document).on('click', '.product-wishlist-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $button = $(this);
            const productId = $button.data('product-id');

            // Toggle active state immediately for better UX
            $button.toggleClass('active');

            // Here you would implement actual wishlist AJAX functionality
            // For now, we'll just show a toast notification
            if ($button.hasClass('active')) {
                showToast('Added to wishlist', 'success');
            } else {
                showToast('Removed from wishlist', 'info');
            }

            // TODO: Implement actual wishlist AJAX call
            // $.ajax({
            //     url: aakaari_ajax.ajax_url,
            //     type: 'POST',
            //     data: {
            //         action: 'toggle_wishlist',
            //         product_id: productId,
            //         nonce: aakaari_ajax.nonce
            //     }
            // });
        });
    }

    /**
     * Handle product card click to navigate to product page
     */
    function initProductCardClick() {
        $(document).off('click', '.product-card');

        $(document).on('click', '.product-card', function(e) {
            // Don't navigate if clicking on buttons
            if ($(e.target).closest('.product-add-to-cart-btn, .ajax_add_to_cart, .product-wishlist-btn, .color-swatch, a').length) {
                return;
            }

            const productUrl = $(this).data('product-url');
            if (productUrl) {
                window.location.href = productUrl;
            }
        });
    }

    /**
     * Show toast notification
     */
    function showToast(message, type = 'info') {
        // Check if toast notification system exists
        if (typeof window.showMessageToast === 'function') {
            window.showMessageToast('', message, type === 'error' ? 'info' : type);
        } else if (typeof window.showToast === 'function') {
            window.showToast({
                type: type === 'error' ? 'info' : type,
                message: message,
                duration: 2000
            });
        } else {
            // Fallback to console if toast system not available
            console.log('[Toast]', type, message);
        }
    }

    /**
     * AJAX Add to Cart handler for WooCommerce
     */
    function setupWooCommerceAjaxAddToCart() {
        // Register custom AJAX add to cart action if needed
        $(document).on('click', '.ajax_add_to_cart:not(.product-add-to-cart-btn)', function(e) {
            e.preventDefault();

            const $button = $(this);
            const productId = $button.data('product_id') || $button.data('id');

            if (!productId) {
                return;
            }

            $button.addClass('loading');

            $.ajax({
                url: aakaari_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'woocommerce_ajax_add_to_cart',
                    product_id: productId,
                    quantity: 1
                },
                success: function(response) {
                    if (!response.error) {
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);
                        showToast('Added to cart!', 'success');
                    }
                },
                complete: function() {
                    $button.removeClass('loading');
                }
            });
        });
    }

    /**
     * Handle WooCommerce AJAX Add to Cart
     */
    $(document).ready(function() {
        // Standard WooCommerce AJAX add to cart endpoint
        $.ajaxSetup({
            beforeSend: function(xhr, settings) {
                // Add product_id parameter for WooCommerce compatibility
                if (settings.data && settings.data.indexOf('action=woocommerce_ajax_add_to_cart') !== -1) {
                    // Ensure we're using the correct action
                    settings.data = settings.data.replace('action=woocommerce_ajax_add_to_cart', 'action=woocommerce_add_to_cart');
                }
            }
        });
    });

    // Initialize when DOM is ready
    $(document).ready(function() {
        init();
        setupWooCommerceAjaxAddToCart();
    });

    // Re-initialize after AJAX cart updates
    $(document.body).on('updated_cart_totals updated_checkout', function() {
        initProductInteractions();
    });

})(jQuery);
