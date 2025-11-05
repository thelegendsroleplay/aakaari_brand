/**
 * Header JavaScript for FashionMen Theme
 * Search modal and cart updates
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        initSearchModal();
        initCartUpdate();
    });

    /**
     * Search Modal
     */
    function initSearchModal() {
        const searchToggle = $('#search-toggle');
        const searchModal = $('#search-modal');
        const searchClose = $('#search-close');

        if (!searchToggle.length || !searchModal.length) {
            return;
        }

        searchToggle.on('click', function() {
            searchModal.removeClass('hidden').css('display', 'flex');
            $('#woocommerce-product-search-field-0').focus();
        });

        searchClose.on('click', function() {
            searchModal.addClass('hidden');
        });

        searchModal.on('click', function(e) {
            if (e.target === this) {
                searchModal.addClass('hidden');
            }
        });

        // Close on escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && !searchModal.hasClass('hidden')) {
                searchModal.addClass('hidden');
            }
        });
    }

    /**
     * Cart Update via AJAX
     */
    function initCartUpdate() {
        $(document.body).on('added_to_cart', function(event, fragments, cart_hash) {
            updateCartCount();
        });

        $(document.body).on('removed_from_cart', function() {
            updateCartCount();
        });
    }

    function updateCartCount() {
        if (typeof fashionmenAjax === 'undefined') {
            return;
        }

        $.ajax({
            url: fashionmenAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'fashionmen_update_cart_count',
                nonce: fashionmenAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    const count = response.data.count;
                    const cartCount = $('.cart-count');

                    if (count > 0) {
                        if (cartCount.length) {
                            cartCount.text(count);
                        } else {
                            $('.cart-icon-link').append('<span class="cart-count absolute -top-1 -right-1 bg-black text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">' + count + '</span>');
                        }
                    } else {
                        cartCount.remove();
                    }
                }
            }
        });
    }

})(jQuery);
