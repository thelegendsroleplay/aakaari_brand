/**
 * Global JavaScript
 * Loaded on all pages for AJAX support
 *
 * @package FashionMen
 * @since 2.0.0
 */

(function($) {
    'use strict';

    // Global AJAX object is available as aakaari_ajax
    // Contains: ajax_url, search_nonce, wishlist_nonce

    // Mobile menu toggle
    $(document).ready(function() {
        $('.mobile-menu-toggle').on('click', function() {
            $('.main-navigation').toggleClass('mobile-active');
            $(this).toggleClass('active');
        });
    });

})(jQuery);
