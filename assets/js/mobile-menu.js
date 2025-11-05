/**
 * Mobile Menu JavaScript for FashionMen Theme
 * Mobile navigation drawer
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        initMobileMenu();
    });

    /**
     * Mobile Menu
     */
    function initMobileMenu() {
        const mobileMenuToggle = $('#mobile-menu-toggle');
        const mobileMenuClose = $('#mobile-menu-close');
        const mobileNav = $('#mobile-navigation');

        if (!mobileMenuToggle.length || !mobileNav.length) {
            return;
        }

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

        // Close on escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && mobileNav.hasClass('active')) {
                mobileMenuClose.trigger('click');
            }
        });
    }

})(jQuery);
