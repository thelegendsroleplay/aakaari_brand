/**
 * Shop Page JavaScript
 * Handles filters, sorting, and mobile interactions
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        initFiltersToggle();
        initPriceSliders();
        initColorSwatches();
        initClearFilters();
        initSortChange();
        initFilterForm();
    });

    /**
     * Initialize mobile filters toggle
     */
    function initFiltersToggle() {
        const toggleBtn = document.getElementById('filters-toggle');
        const sidebar = document.getElementById('filters-sidebar');

        if (!toggleBtn || !sidebar) {
            return;
        }

        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('hide');

            // Update button text
            const text = toggleBtn.querySelector('.filters-toggle-text');
            if (text) {
                text.textContent = sidebar.classList.contains('hide') ? 'Show Filters' : 'Hide Filters';
            }
        });

        // Initially hide on mobile
        if (window.innerWidth <= 1024) {
            sidebar.classList.add('hide');
        }

        // Handle resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 1024) {
                sidebar.classList.remove('hide');
            }
        });
    }

    /**
     * Initialize price range sliders
     */
    function initPriceSliders() {
        const minPriceSlider = document.getElementById('min-price');
        const maxPriceSlider = document.getElementById('max-price');
        const minPriceLabel = document.getElementById('min-price-label');
        const maxPriceLabel = document.getElementById('max-price-label');
        const form = document.getElementById('product-filters-form');

        if (!minPriceSlider || !maxPriceSlider) {
            return;
        }

        let priceTimeout;

        // Update labels when sliders change
        minPriceSlider.addEventListener('input', function() {
            let minVal = parseInt(this.value);
            let maxVal = parseInt(maxPriceSlider.value);

            // Ensure min doesn't exceed max
            if (minVal > maxVal - 10) {
                this.value = maxVal - 10;
                minVal = maxVal - 10;
            }

            if (minPriceLabel) {
                minPriceLabel.textContent = minVal;
            }
        });

        maxPriceSlider.addEventListener('input', function() {
            let maxVal = parseInt(this.value);
            let minVal = parseInt(minPriceSlider.value);

            // Ensure max doesn't go below min
            if (maxVal < minVal + 10) {
                this.value = minVal + 10;
                maxVal = minVal + 10;
            }

            if (maxPriceLabel) {
                maxPriceLabel.textContent = maxVal;
            }
        });

        // Auto-submit when slider is released (with debounce)
        function handlePriceChange() {
            clearTimeout(priceTimeout);
            priceTimeout = setTimeout(function() {
                if (form) {
                    form.dispatchEvent(new Event('submit'));
                }
            }, 500); // Wait 500ms after user stops dragging
        }

        minPriceSlider.addEventListener('change', handlePriceChange);
        maxPriceSlider.addEventListener('change', handlePriceChange);
    }

    /**
     * Initialize color swatch toggles
     */
    function initColorSwatches() {
        const colorSwatches = document.querySelectorAll('.color-swatch');
        const form = document.getElementById('product-filters-form');

        colorSwatches.forEach(function(swatch) {
            swatch.addEventListener('click', function(e) {
                e.preventDefault();

                // Toggle selected class
                this.classList.toggle('selected');

                // Toggle hidden checkbox
                const checkbox = this.querySelector('input[type="checkbox"]');
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;

                    // Auto-submit form for instant filtering
                    if (form) {
                        form.dispatchEvent(new Event('submit'));
                    }
                }
            });
        });
    }

    /**
     * Initialize clear filters buttons
     */
    function initClearFilters() {
        const clearBtns = document.querySelectorAll('#clear-filters, #clear-filters-empty');

        clearBtns.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                // Get the current URL without query parameters
                const baseUrl = window.location.origin + window.location.pathname;

                // Redirect to clean URL
                window.location.href = baseUrl;
            });
        });
    }

    /**
     * Initialize sort dropdown change
     */
    function initSortChange() {
        const sortSelect = document.getElementById('orderby');

        if (!sortSelect) {
            return;
        }

        sortSelect.addEventListener('change', function() {
            const form = document.getElementById('product-filters-form');
            const currentUrl = new URL(window.location.href);

            // Update orderby parameter
            currentUrl.searchParams.set('orderby', this.value);

            // Redirect to updated URL
            window.location.href = currentUrl.toString();
        });
    }

    /**
     * Initialize filter form submission
     */
    function initFilterForm() {
        const form = document.getElementById('product-filters-form');

        if (!form) {
            return;
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Build URL with filter parameters
            const formData = new FormData(form);
            const currentUrl = new URL(window.location.href);

            // Clear previous filter params
            currentUrl.searchParams.delete('filter_categories');
            currentUrl.searchParams.delete('filter_sizes');
            currentUrl.searchParams.delete('filter_colors');
            currentUrl.searchParams.delete('min_price');
            currentUrl.searchParams.delete('max_price');
            currentUrl.searchParams.delete('min_rating');

            // Categories
            const categories = [];
            formData.getAll('filter_categories[]').forEach(function(cat) {
                categories.push(cat);
            });
            if (categories.length > 0) {
                currentUrl.searchParams.set('filter_categories', categories.join(','));
            }

            // Sizes
            const sizes = [];
            formData.getAll('filter_sizes[]').forEach(function(size) {
                sizes.push(size);
            });
            if (sizes.length > 0) {
                currentUrl.searchParams.set('filter_sizes', sizes.join(','));
            }

            // Colors
            const colors = [];
            formData.getAll('filter_colors[]').forEach(function(color) {
                colors.push(color);
            });
            if (colors.length > 0) {
                currentUrl.searchParams.set('filter_colors', colors.join(','));
            }

            // Price range
            const minPrice = formData.get('min_price');
            const maxPrice = formData.get('max_price');
            if (minPrice && minPrice !== '0') {
                currentUrl.searchParams.set('min_price', minPrice);
            }
            if (maxPrice && maxPrice !== '1000') {
                currentUrl.searchParams.set('max_price', maxPrice);
            }

            // Rating
            const minRating = formData.get('min_rating');
            if (minRating && minRating !== '0') {
                currentUrl.searchParams.set('min_rating', minRating);
            }

            // Preserve orderby if exists
            const orderby = document.getElementById('orderby');
            if (orderby && orderby.value) {
                currentUrl.searchParams.set('orderby', orderby.value);
            }

            // Redirect to filtered URL
            window.location.href = currentUrl.toString();
        });

        // Instant filtering on checkbox/radio change
        const instantFilters = form.querySelectorAll('input[type="checkbox"]:not(.color-swatch input), input[type="radio"]');
        instantFilters.forEach(function(input) {
            input.addEventListener('change', function() {
                // Auto-submit form on filter change for instant filtering
                form.dispatchEvent(new Event('submit'));
            });
        });
    }

})();
