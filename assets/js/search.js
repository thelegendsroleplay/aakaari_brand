/**
 * Search Page JavaScript
 *
 * Handles search interactions, filters, and AJAX functionality
 *
 * @package Aakaari
 */

(function($) {
    'use strict';

    // Initialize when document is ready
    $(document).ready(function() {
        initSearchPage();
    });

    /**
     * Initialize Search Page
     */
    function initSearchPage() {
        // Search input handlers
        initSearchInput();

        // Filter handlers
        initQuickFilters();
        initSidebarFilters();

        // View toggle handlers
        initViewToggle();

        // Wishlist handlers
        initWishlistButtons();

        // Recent searches handlers
        initRecentSearches();

        // Clear filters handler
        initClearFilters();
    }

    /**
     * Initialize Search Input
     */
    function initSearchInput() {
        const searchInput = $('#search-input');
        const clearButton = $('#clear-search');
        let searchTimeout;

        // Auto-search on typing (with debounce)
        searchInput.on('input', function() {
            clearTimeout(searchTimeout);
            const searchValue = $(this).val();

            searchTimeout = setTimeout(function() {
                if (searchValue.length >= 3 || searchValue.length === 0) {
                    performSearch();
                }
            }, 500);
        });

        // Clear search
        clearButton.on('click', function() {
            searchInput.val('').focus();
            performSearch();
        });

        // Search on Enter key
        searchInput.on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                performSearch();
            }
        });
    }

    /**
     * Initialize Quick Filters
     */
    function initQuickFilters() {
        $('.quick-filter-chip').on('click', function() {
            // Toggle active state
            $('.quick-filter-chip').removeClass('active');
            $(this).addClass('active');

            // Get filter value
            const filter = $(this).data('filter');

            // Update URL and perform search
            updateURLParameter('filter', filter !== 'all' ? filter : null);
            performSearch();
        });
    }

    /**
     * Initialize Sidebar Filters
     */
    function initSidebarFilters() {
        // Category checkboxes
        $('.filter-option input[type="checkbox"]').on('change', function() {
            const checkedCategories = [];
            $('.filter-option input[name="product_cat[]"]:checked').each(function() {
                checkedCategories.push($(this).val());
            });

            updateURLParameter('product_cat', checkedCategories.length > 0 ? checkedCategories.join(',') : null);
            performSearch();
        });

        // Price inputs
        $('input[name="min_price"], input[name="max_price"]').on('change', function() {
            const minPrice = $('input[name="min_price"]').val();
            const maxPrice = $('input[name="max_price"]').val();

            updateURLParameter('min_price', minPrice || null);
            updateURLParameter('max_price', maxPrice || null);
            performSearch();
        });

        // Rating radio buttons
        $('.filter-option input[type="radio"][name="rating"]').on('change', function() {
            const rating = $(this).val();
            updateURLParameter('rating', rating || null);
            performSearch();
        });
    }

    /**
     * Initialize View Toggle
     */
    function initViewToggle() {
        $('.view-button').on('click', function() {
            const view = $(this).data('view');

            // Toggle active state
            $('.view-button').removeClass('active');
            $(this).addClass('active');

            // Update grid view
            const resultsGrid = $('#search-results-grid');
            if (view === 'list') {
                resultsGrid.addClass('list-view');
            } else {
                resultsGrid.removeClass('list-view');
            }

            // Save preference to localStorage
            localStorage.setItem('aakaari_search_view', view);
        });

        // Restore saved view preference
        const savedView = localStorage.getItem('aakaari_search_view');
        if (savedView === 'list') {
            $('.view-button[data-view="list"]').click();
        }
    }

    /**
     * Initialize Wishlist Buttons
     */
    function initWishlistButtons() {
        $(document).on('click', '.result-wishlist', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const button = $(this);
            const productId = button.data('product-id');
            const isActive = button.hasClass('active');

            // Toggle wishlist
            if (isActive) {
                removeFromWishlist(productId, button);
            } else {
                addToWishlist(productId, button);
            }
        });
    }

    /**
     * Add Product to Wishlist
     */
    function addToWishlist(productId, button) {
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'aakaari_add_to_wishlist',
                product_id: productId,
                nonce: ajax_object.nonce
            },
            beforeSend: function() {
                button.prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    button.addClass('active');
                    button.find('svg').attr('fill', 'currentColor');
                    showNotification('Added to wishlist!', 'success');
                } else {
                    showNotification(response.data.message || 'Failed to add to wishlist', 'error');
                }
            },
            error: function() {
                showNotification('An error occurred', 'error');
            },
            complete: function() {
                button.prop('disabled', false);
            }
        });
    }

    /**
     * Remove Product from Wishlist
     */
    function removeFromWishlist(productId, button) {
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'aakaari_remove_from_wishlist',
                product_id: productId,
                nonce: ajax_object.nonce
            },
            beforeSend: function() {
                button.prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    button.removeClass('active');
                    button.find('svg').attr('fill', 'none');
                    showNotification('Removed from wishlist', 'success');
                } else {
                    showNotification(response.data.message || 'Failed to remove from wishlist', 'error');
                }
            },
            error: function() {
                showNotification('An error occurred', 'error');
            },
            complete: function() {
                button.prop('disabled', false);
            }
        });
    }

    /**
     * Initialize Recent Searches
     */
    function initRecentSearches() {
        // Click on recent search chip
        $(document).on('click', '.recent-chip', function(e) {
            if (!$(e.target).hasClass('recent-chip-remove')) {
                const searchQuery = $(this).data('search');
                $('#search-input').val(searchQuery);
                performSearch();
            }
        });

        // Remove recent search
        $(document).on('click', '.recent-chip-remove', function(e) {
            e.stopPropagation();
            const searchQuery = $(this).data('search');
            removeRecentSearch(searchQuery);
            $(this).closest('.recent-chip').fadeOut(300, function() {
                $(this).remove();

                // Hide section if no more recent searches
                if ($('.recent-chip').length === 0) {
                    $('.recent-searches').fadeOut(300);
                }
            });
        });
    }

    /**
     * Remove Recent Search
     */
    function removeRecentSearch(searchQuery) {
        const recentSearches = getRecentSearches();
        const index = recentSearches.indexOf(searchQuery);

        if (index > -1) {
            recentSearches.splice(index, 1);
            setRecentSearches(recentSearches);
        }
    }

    /**
     * Get Recent Searches from Cookie
     */
    function getRecentSearches() {
        const cookie = getCookie('aakaari_recent_searches');
        return cookie ? JSON.parse(cookie) : [];
    }

    /**
     * Save Recent Searches to Cookie
     */
    function setRecentSearches(searches) {
        const expiryDate = new Date();
        expiryDate.setDate(expiryDate.getDate() + 30);
        document.cookie = `aakaari_recent_searches=${JSON.stringify(searches)}; expires=${expiryDate.toUTCString()}; path=/`;
    }

    /**
     * Get Cookie Value
     */
    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for(let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    /**
     * Initialize Clear Filters
     */
    function initClearFilters() {
        $('#clear-filters').on('click', function() {
            // Uncheck all checkboxes
            $('.filter-option input[type="checkbox"]').prop('checked', false);

            // Clear price inputs
            $('input[name="min_price"], input[name="max_price"]').val('');

            // Uncheck all radio buttons
            $('.filter-option input[type="radio"]').prop('checked', false);

            // Reset quick filter to 'all'
            $('.quick-filter-chip').removeClass('active');
            $('.quick-filter-chip[data-filter="all"]').addClass('active');

            // Clear URL parameters and perform search
            window.history.pushState({}, '', window.location.pathname + '?s=' + $('#search-input').val());
            performSearch();
        });
    }

    /**
     * Perform Search with Current Filters
     */
    function performSearch() {
        const searchQuery = $('#search-input').val();
        const filters = getActiveFilters();

        // Show loading state
        showLoading();

        // Perform AJAX search if available, otherwise submit form
        if (typeof ajax_object !== 'undefined') {
            $.ajax({
                url: ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'aakaari_filter_search',
                    search: searchQuery,
                    filters: $.param(filters),
                    nonce: ajax_object.nonce
                },
                success: function(response) {
                    if (response.success) {
                        updateSearchResults(response.data.html);
                    } else {
                        showNotification('Search failed', 'error');
                    }
                },
                error: function() {
                    showNotification('An error occurred', 'error');
                },
                complete: function() {
                    hideLoading();
                }
            });
        } else {
            // Fallback: submit the form
            $('.search-form').submit();
        }
    }

    /**
     * Get Active Filters
     */
    function getActiveFilters() {
        const filters = {};

        // Quick filter
        const activeQuickFilter = $('.quick-filter-chip.active').data('filter');
        if (activeQuickFilter && activeQuickFilter !== 'all') {
            filters.filter = activeQuickFilter;
        }

        // Categories
        const checkedCategories = [];
        $('.filter-option input[name="product_cat[]"]:checked').each(function() {
            checkedCategories.push($(this).val());
        });
        if (checkedCategories.length > 0) {
            filters.product_cat = checkedCategories.join(',');
        }

        // Price range
        const minPrice = $('input[name="min_price"]').val();
        const maxPrice = $('input[name="max_price"]').val();
        if (minPrice) filters.min_price = minPrice;
        if (maxPrice) filters.max_price = maxPrice;

        // Rating
        const rating = $('.filter-option input[type="radio"][name="rating"]:checked').val();
        if (rating) filters.rating = rating;

        return filters;
    }

    /**
     * Update Search Results
     */
    function updateSearchResults(html) {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        // Update results header and grid
        const newHeader = $(doc).find('.search-results-header');
        const newGrid = $(doc).find('.results-grid');
        const newPagination = $(doc).find('.search-pagination');
        const newEmpty = $(doc).find('.search-empty');

        if (newHeader.length) {
            $('.search-results-header').replaceWith(newHeader);
        }

        if (newGrid.length) {
            $('.results-grid').replaceWith(newGrid);
        } else if (newEmpty.length) {
            $('.search-main').html(newEmpty);
        }

        if (newPagination.length) {
            if ($('.search-pagination').length) {
                $('.search-pagination').replaceWith(newPagination);
            } else {
                $('.results-grid').after(newPagination);
            }
        } else {
            $('.search-pagination').remove();
        }

        // Re-initialize view toggle to maintain view
        initViewToggle();
    }

    /**
     * Update URL Parameter
     */
    function updateURLParameter(key, value) {
        const url = new URL(window.location);

        if (value === null || value === '') {
            url.searchParams.delete(key);
        } else {
            url.searchParams.set(key, value);
        }

        window.history.pushState({}, '', url);
    }

    /**
     * Show Loading State
     */
    function showLoading() {
        $('.search-main').addClass('loading');
        $('.results-grid').css('opacity', '0.5');
    }

    /**
     * Hide Loading State
     */
    function hideLoading() {
        $('.search-main').removeClass('loading');
        $('.results-grid').css('opacity', '1');
    }

    /**
     * Show Notification
     */
    function showNotification(message, type) {
        // Remove existing notifications
        $('.aakaari-notification').remove();

        // Create notification element
        const notification = $('<div>', {
            class: `aakaari-notification ${type}`,
            text: message
        });

        // Add to body
        $('body').append(notification);

        // Show notification
        setTimeout(function() {
            notification.addClass('show');
        }, 10);

        // Hide and remove after 3 seconds
        setTimeout(function() {
            notification.removeClass('show');
            setTimeout(function() {
                notification.remove();
            }, 300);
        }, 3000);
    }

    // Add notification styles
    if (!$('#aakaari-notification-styles').length) {
        $('<style id="aakaari-notification-styles">')
            .text(`
                .aakaari-notification {
                    position: fixed;
                    top: 2rem;
                    right: 2rem;
                    padding: 1rem 1.5rem;
                    border-radius: 8px;
                    background: white;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                    z-index: 10000;
                    transform: translateX(400px);
                    transition: transform 0.3s ease-out;
                }
                .aakaari-notification.show {
                    transform: translateX(0);
                }
                .aakaari-notification.success {
                    border-left: 4px solid #22c55e;
                }
                .aakaari-notification.error {
                    border-left: 4px solid #ef4444;
                }
            `)
            .appendTo('head');
    }

})(jQuery);
