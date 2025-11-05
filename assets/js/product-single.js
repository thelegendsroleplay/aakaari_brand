/**
 * Single Product Page JavaScript for FashionMen Theme
 * Product gallery, variations, quantity controls
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        initProductGallery();
        initProductQuantity();
        initProductVariations();
    });

    /**
     * Product Image Gallery
     */
    function initProductGallery() {
        // Gallery thumbnail click
        $(document).on('click', '.gallery-thumb', function() {
            const newSrc = $(this).find('img').attr('src');
            const mainImage = $('.product-main-image img');

            // Update main image
            mainImage.attr('src', newSrc);

            // Update active state
            $('.gallery-thumb').removeClass('active');
            $(this).addClass('active');
        });

        // Initialize zoom if available
        if (typeof $.fn.zoom !== 'undefined') {
            $('.product-main-image').zoom();
        }
    }

    /**
     * Product Quantity Controls
     */
    function initProductQuantity() {
        // Increase quantity
        $(document).on('click', '.quantity-plus', function(e) {
            e.preventDefault();
            const input = $(this).siblings('input[type="number"]');
            const max = input.attr('max') ? parseInt(input.attr('max')) : 999;
            const currentVal = parseInt(input.val()) || 1;

            if (currentVal < max) {
                input.val(currentVal + 1).trigger('change');
            }
        });

        // Decrease quantity
        $(document).on('click', '.quantity-minus', function(e) {
            e.preventDefault();
            const input = $(this).siblings('input[type="number"]');
            const min = input.attr('min') ? parseInt(input.attr('min')) : 1;
            const currentVal = parseInt(input.val()) || 1;

            if (currentVal > min) {
                input.val(currentVal - 1).trigger('change');
            }
        });
    }

    /**
     * Product Variations (Size, Color selection)
     */
    function initProductVariations() {
        // Size selection
        $(document).on('click', '.size-btn', function() {
            $('.size-btn').removeClass('selected');
            $(this).addClass('selected');
            const size = $(this).data('size');
            $('input[name="size"]').val(size);
        });

        // Color selection
        $(document).on('click', '.color-btn', function() {
            $('.color-btn').removeClass('selected');
            $(this).addClass('selected');
            const color = $(this).data('color');
            $('input[name="color"]').val(color);
        });
    }

    /**
     * Product Tabs
     */
    $(document).on('click', '.tab-button', function() {
        const tabId = $(this).data('tab');

        // Update buttons
        $('.tab-button').removeClass('active');
        $(this).addClass('active');

        // Update content
        $('.tab-content').removeClass('active').hide();
        $('#' + tabId).addClass('active').fadeIn();
    });

})(jQuery);
