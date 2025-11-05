/**
 * Main JavaScript for FashionMen Theme
 */

(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {

        // Initialize all components
        initMobileMenu();
        initSearchModal();
        initCartUpdate();
        initQuickView();
        initProductQuantity();
        initScrollToTop();

    });

    /**
     * Mobile Menu
     */
    function initMobileMenu() {
        const mobileMenuToggle = $('#mobile-menu-toggle');
        const mobileMenuClose = $('#mobile-menu-close');
        const mobileNav = $('#mobile-navigation');

        mobileMenuToggle.on('click', function() {
            mobileNav.removeClass('hidden').addClass('active');
            $('body').addClass('overflow-hidden');
        });

        mobileMenuClose.on('click', function() {
            mobileNav.removeClass('active');
            setTimeout(function() {
                mobileNav.addClass('hidden');
            }, 300);
            $('body').removeClass('overflow-hidden');
        });

        // Close on overlay click
        mobileNav.on('click', function(e) {
            if (e.target === this) {
                mobileMenuClose.trigger('click');
            }
        });
    }

    /**
     * Search Modal
     */
    function initSearchModal() {
        const searchToggle = $('#search-toggle');
        const searchModal = $('#search-modal');
        const searchClose = $('#search-close');

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

    /**
     * Quick View
     */
    function initQuickView() {
        $(document).on('click', '.quick-view-button', function(e) {
            e.preventDefault();
            const productId = $(this).data('product-id');

            // Show loading state
            $(this).addClass('loading');

            // Here you would implement the quick view modal
            // This is a placeholder for the actual implementation
            console.log('Quick view for product:', productId);

            // Remove loading state
            $(this).removeClass('loading');
        });
    }

    /**
     * Product Quantity Controls
     */
    function initProductQuantity() {
        // Increase quantity
        $(document).on('click', '.quantity-plus', function() {
            const input = $(this).siblings('input[type="number"]');
            const max = input.attr('max') ? parseInt(input.attr('max')) : 999;
            const currentVal = parseInt(input.val()) || 0;

            if (currentVal < max) {
                input.val(currentVal + 1).trigger('change');
            }
        });

        // Decrease quantity
        $(document).on('click', '.quantity-minus', function() {
            const input = $(this).siblings('input[type="number"]');
            const min = input.attr('min') ? parseInt(input.attr('min')) : 0;
            const currentVal = parseInt(input.val()) || 0;

            if (currentVal > min) {
                input.val(currentVal - 1).trigger('change');
            }
        });
    }

    /**
     * Scroll to Top
     */
    function initScrollToTop() {
        const scrollBtn = $('<button>', {
            class: 'scroll-to-top fixed bottom-8 right-8 bg-black text-white p-3 rounded-full shadow-lg opacity-0 transition-opacity duration-300 z-50',
            html: '<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>'
        });

        $('body').append(scrollBtn);

        $(window).on('scroll', function() {
            if ($(this).scrollTop() > 300) {
                scrollBtn.css('opacity', '1');
            } else {
                scrollBtn.css('opacity', '0');
            }
        });

        scrollBtn.on('click', function() {
            $('html, body').animate({ scrollTop: 0 }, 600);
        });
    }

    /**
     * Product Image Gallery
     */
    if (typeof $.fn.zoom !== 'undefined') {
        $('.woocommerce-product-gallery__image').zoom();
    }

    /**
     * Select2 for select boxes
     */
    if (typeof $.fn.select2 !== 'undefined') {
        $('select').select2({
            minimumResultsForSearch: 10,
            width: '100%'
        });
    }

    /**
     * Smooth scroll for anchor links
     */
    $('a[href*="#"]:not([href="#"])').on('click', function(e) {
        if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
            const target = $(this.hash);
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 80
                }, 600);
            }
        }
    });

    /**
     * Newsletter form
     */
    $('.newsletter-form').on('submit', function(e) {
        e.preventDefault();
        const email = $(this).find('input[type="email"]').val();

        // Add your newsletter subscription logic here
        console.log('Newsletter subscription:', email);

        // Show success message
        alert('Thank you for subscribing!');
    });

})(jQuery);
