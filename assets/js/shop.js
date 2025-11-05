/**
 * Shop Page JavaScript for FashionMen Theme
 * Product filtering, sorting, quick view
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        initQuickView();
        initProductFilters();
        initWishlist();
    });

    /**
     * Quick View Modal
     */
    function initQuickView() {
        $(document).on('click', '.quick-view-button', function(e) {
            e.preventDefault();
            const productId = $(this).data('product-id');

            // Show loading state
            $(this).addClass('loading');

            // Here you would implement AJAX to load product quick view
            console.log('Quick view for product:', productId);

            // Remove loading state after completion
            setTimeout(() => {
                $(this).removeClass('loading');
            }, 500);
        });
    }

    /**
     * Product Filters
     */
    function initProductFilters() {
        // Price range filter
        $(document).on('change', '#price-range', function() {
            $(this).closest('form').submit();
        });

        // Category filter
        $(document).on('change', '.category-filter', function() {
            const selectedCategories = [];
            $('.category-filter:checked').each(function() {
                selectedCategories.push($(this).val());
            });

            // Update URL or trigger AJAX filter
            console.log('Selected categories:', selectedCategories);
        });

        // Clear filters
        $(document).on('click', '.clear-filters', function(e) {
            e.preventDefault();
            $('.category-filter').prop('checked', false);
            $('#price-range').val('all');
            $(this).closest('form').submit();
        });
    }

    /**
     * Wishlist Toggle
     */
    function initWishlist() {
        $(document).on('click', '.wishlist-btn', function(e) {
            e.preventDefault();
            const btn = $(this);
            const productId = btn.data('product-id');

            btn.toggleClass('active');

            // Here you would implement AJAX to add/remove from wishlist
            console.log('Toggle wishlist for product:', productId);

            // Show feedback
            if (btn.hasClass('active')) {
                showNotification('Added to wishlist!');
            } else {
                showNotification('Removed from wishlist');
            }
        });
    }

    /**
     * Show Notification
     */
    function showNotification(message) {
        const notification = $('<div>', {
            class: 'notification fixed bottom-8 right-8 bg-black text-white px-6 py-3 rounded-lg shadow-lg z-50',
            text: message
        });

        $('body').append(notification);

        setTimeout(() => {
            notification.fadeOut(300, function() {
                $(this).remove();
            });
        }, 2000);
    }

})(jQuery);
