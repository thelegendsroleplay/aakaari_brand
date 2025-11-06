/**
 * Main JavaScript file for Aakaari Brand theme
 *
 * @package Aakaari_Brand
 */

(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {

        // Mobile menu toggle
        initMobileMenu();

        // Smooth scroll for anchor links
        initSmoothScroll();

        // Update cart count on AJAX add to cart
        updateCartCount();

        // Initialize accessibility features
        initAccessibility();
    });

    /**
     * Mobile menu functionality
     */
    function initMobileMenu() {
        // Add mobile menu toggle button
        if ($('.main-navigation').length) {
            $('.main-navigation').before('<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">☰ Menu</button>');

            $('.menu-toggle').on('click', function() {
                var $this = $(this);
                var $menu = $('.main-navigation ul');

                $menu.slideToggle(300);
                $this.toggleClass('active');

                if ($this.attr('aria-expanded') === 'false') {
                    $this.attr('aria-expanded', 'true');
                } else {
                    $this.attr('aria-expanded', 'false');
                }
            });
        }

        // Close mobile menu on window resize
        $(window).on('resize', function() {
            if ($(window).width() > 768) {
                $('.main-navigation ul').removeAttr('style');
                $('.menu-toggle').attr('aria-expanded', 'false');
            }
        });
    }

    /**
     * Smooth scroll for anchor links
     */
    function initSmoothScroll() {
        $('a[href*="#"]:not([href="#"])').on('click', function(e) {
            if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') &&
                location.hostname === this.hostname) {

                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');

                if (target.length) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top - 100
                    }, 800);
                }
            }
        });
    }

    /**
     * Update cart count via AJAX
     */
    function updateCartCount() {
        if (typeof wc_add_to_cart_params === 'undefined') {
            return;
        }

        $(document.body).on('added_to_cart', function() {
            $.ajax({
                url: wc_add_to_cart_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'update_cart_count'
                },
                success: function(response) {
                    $('.cart-count').text(response);
                }
            });
        });
    }

    /**
     * Accessibility features
     */
    function initAccessibility() {
        // Skip link focus fix for screen readers
        var isWebkit = navigator.userAgent.toLowerCase().indexOf('webkit') > -1,
            isOpera = navigator.userAgent.toLowerCase().indexOf('opera') > -1,
            isIe = navigator.userAgent.toLowerCase().indexOf('msie') > -1;

        if ((isWebkit || isOpera || isIe) && document.getElementById && window.addEventListener) {
            window.addEventListener('hashchange', function() {
                var id = location.hash.substring(1),
                    element;

                if (!(/^[A-z0-9_-]+$/.test(id))) {
                    return;
                }

                element = document.getElementById(id);

                if (element) {
                    if (!(/^(?:a|select|input|button|textarea)$/i.test(element.tagName))) {
                        element.tabIndex = -1;
                    }
                    element.focus();
                }
            }, false);
        }

        // Focus management for modal dialogs
        $(document).on('keydown', function(e) {
            // Escape key to close modals
            if (e.keyCode === 27) {
                $('.modal, .overlay').fadeOut();
            }
        });
    }

    /**
     * Sticky header on scroll
     */
    function initStickyHeader() {
        var $header = $('.site-header');
        var headerOffset = $header.offset().top;

        $(window).on('scroll', function() {
            if ($(window).scrollTop() > headerOffset) {
                $header.addClass('sticky');
            } else {
                $header.removeClass('sticky');
            }
        });
    }

    /**
     * Back to top button
     */
    function initBackToTop() {
        // Add back to top button
        $('body').append('<button id="back-to-top" title="Back to Top">↑</button>');

        var $backToTop = $('#back-to-top');

        $(window).on('scroll', function() {
            if ($(this).scrollTop() > 300) {
                $backToTop.fadeIn();
            } else {
                $backToTop.fadeOut();
            }
        });

        $backToTop.on('click', function() {
            $('html, body').animate({scrollTop: 0}, 800);
            return false;
        });
    }

    /**
     * Product gallery lightbox enhancement
     */
    function enhanceProductGallery() {
        if ($('.woocommerce-product-gallery').length) {
            // Add zoom on hover effect
            $('.woocommerce-product-gallery__image').on('mouseenter', function() {
                $(this).addClass('zoomed');
            }).on('mouseleave', function() {
                $(this).removeClass('zoomed');
            });
        }
    }

    /**
     * Initialize lazy loading for images
     */
    function initLazyLoad() {
        if ('IntersectionObserver' in window) {
            var imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        var image = entry.target;
                        if (image.dataset.src) {
                            image.src = image.dataset.src;
                            image.classList.remove('lazy');
                            imageObserver.unobserve(image);
                        }
                    }
                });
            });

            document.querySelectorAll('img.lazy').forEach(function(img) {
                imageObserver.observe(img);
            });
        }
    }

})(jQuery);
