/**
 * Global JavaScript Utilities for FashionMen Theme
 * Scroll to top, smooth scroll, accessibility
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        initScrollToTop();
        initSmoothScroll();
        initSelect2();
        initAccessibility();
    });

    /**
     * Scroll to Top Button
     */
    function initScrollToTop() {
        const scrollBtn = $('<button>', {
            class: 'scroll-to-top fixed bottom-8 right-8 bg-black text-white p-3 rounded-full shadow-lg opacity-0 transition-opacity duration-300 z-50 hover:bg-gray-800',
            'aria-label': 'Scroll to top',
            html: '<svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>'
        });

        $('body').append(scrollBtn);

        $(window).on('scroll', function() {
            if ($(this).scrollTop() > 300) {
                scrollBtn.css('opacity', '1').css('pointer-events', 'auto');
            } else {
                scrollBtn.css('opacity', '0').css('pointer-events', 'none');
            }
        });

        scrollBtn.on('click', function() {
            $('html, body').animate({ scrollTop: 0 }, 600);
        });
    }

    /**
     * Smooth Scroll for Anchor Links
     */
    function initSmoothScroll() {
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
    }

    /**
     * Select2 for Select Boxes
     */
    function initSelect2() {
        if (typeof $.fn.select2 !== 'undefined') {
            $('select').not('.no-select2').select2({
                minimumResultsForSearch: 10,
                width: '100%'
            });
        }
    }

    /**
     * Accessibility Enhancements
     */
    function initAccessibility() {
        // Add keyboard navigation for custom elements
        $(document).on('keydown', '[role="button"]', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $(this).trigger('click');
            }
        });

        // Skip to content link
        $('.skip-link').on('click', function(e) {
            const target = $($(this).attr('href'));
            if (target.length) {
                e.preventDefault();
                target.attr('tabindex', '-1').focus();
                $('html, body').animate({
                    scrollTop: target.offset().top
                }, 300);
            }
        });
    }

    /**
     * Lazy Loading Images
     */
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img.lazy').forEach(img => {
            imageObserver.observe(img);
        });
    }

    /**
     * Form Input Animations
     */
    $('.form-group input, .form-group textarea').on('focus', function() {
        $(this).parent().addClass('focused');
    }).on('blur', function() {
        if (!$(this).val()) {
            $(this).parent().removeClass('focused');
        }
    });

    /**
     * Copy to Clipboard Functionality
     */
    $(document).on('click', '[data-copy]', function() {
        const text = $(this).data('copy');
        const btn = $(this);

        navigator.clipboard.writeText(text).then(() => {
            const originalText = btn.text();
            btn.text('Copied!');

            setTimeout(() => {
                btn.text(originalText);
            }, 2000);
        });
    });

    /**
     * Responsive Tables
     */
    $('table').each(function() {
        if (!$(this).parent().hasClass('table-wrapper')) {
            $(this).wrap('<div class="table-wrapper" style="overflow-x: auto;"></div>');
        }
    });

    /**
     * External Links
     */
    $('a[href^="http"]').not('[href*="' + window.location.host + '"]').attr({
        target: '_blank',
        rel: 'noopener noreferrer'
    });

})(jQuery);
