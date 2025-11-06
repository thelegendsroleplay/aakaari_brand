/**
 * Layout JavaScript
 * Handles header, footer, and mobile navigation functionality
 *
 * @package Aakaari_Brand
 */

(function($) {
    'use strict';

    const AakaariBrandLayout = {
        /**
         * Initialize all layout components
         */
        init: function() {
            this.setupMobileMenu();
            this.setupBackToTop();
            this.setupHeaderScroll();
            this.setupAccessibility();
        },

        /**
         * Setup mobile menu functionality
         */
        setupMobileMenu: function() {
            const $mobileMenuToggle = $('#mobile-menu-toggle');
            const $mobileNavClose = $('#mobile-nav-close');
            const $mobileNavBackdrop = $('#mobile-nav-backdrop');
            const $mobileNavSidebar = $('#mobile-navigation');

            // Open mobile menu
            $mobileMenuToggle.on('click', function(e) {
                e.preventDefault();
                $mobileNavBackdrop.addClass('active');
                $mobileNavSidebar.addClass('active');
                $('body').css('overflow', 'hidden');

                // Update ARIA attributes
                $mobileMenuToggle.attr('aria-expanded', 'true');
                $mobileNavSidebar.attr('aria-hidden', 'false');

                // Focus first link for accessibility
                setTimeout(function() {
                    $mobileNavSidebar.find('a, button').first().focus();
                }, 300);
            });

            // Close mobile menu
            const closeMobileMenu = function(e) {
                if (e) e.preventDefault();

                $mobileNavBackdrop.removeClass('active');
                $mobileNavSidebar.removeClass('active');
                $('body').css('overflow', '');

                // Update ARIA attributes
                $mobileMenuToggle.attr('aria-expanded', 'false');
                $mobileNavSidebar.attr('aria-hidden', 'true');

                // Return focus to toggle button
                $mobileMenuToggle.focus();
            };

            $mobileNavClose.on('click', closeMobileMenu);
            $mobileNavBackdrop.on('click', closeMobileMenu);

            // Close on escape key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $mobileNavSidebar.hasClass('active')) {
                    closeMobileMenu();
                }
            });

            // Close menu when clicking on links
            $mobileNavSidebar.find('a').on('click', function() {
                // Small delay to allow navigation to start
                setTimeout(closeMobileMenu, 150);
            });

            // Trap focus within mobile menu when open
            $mobileNavSidebar.on('keydown', function(e) {
                if (e.key === 'Tab' && $mobileNavSidebar.hasClass('active')) {
                    const focusableElements = $mobileNavSidebar.find('a, button').filter(':visible');
                    const firstElement = focusableElements.first()[0];
                    const lastElement = focusableElements.last()[0];

                    if (e.shiftKey) {
                        // Shift + Tab
                        if (document.activeElement === firstElement) {
                            e.preventDefault();
                            lastElement.focus();
                        }
                    } else {
                        // Tab
                        if (document.activeElement === lastElement) {
                            e.preventDefault();
                            firstElement.focus();
                        }
                    }
                }
            });
        },

        /**
         * Setup back to top button functionality
         */
        setupBackToTop: function() {
            const $backToTop = $('#back-to-top');

            if ($backToTop.length === 0) return;

            // Show/hide button based on scroll position
            const toggleBackToTop = function() {
                if ($(window).scrollTop() > 300) {
                    $backToTop.addClass('visible');
                } else {
                    $backToTop.removeClass('visible');
                }
            };

            // Initial check
            toggleBackToTop();

            // Check on scroll
            $(window).on('scroll', function() {
                toggleBackToTop();
            });

            // Smooth scroll to top
            $backToTop.on('click', function(e) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: 0
                }, 600, 'swing');
            });
        },

        /**
         * Setup header scroll effects
         */
        setupHeaderScroll: function() {
            const $header = $('.header-main');
            let lastScrollTop = 0;

            $(window).on('scroll', function() {
                const scrollTop = $(window).scrollTop();

                // Add shadow on scroll
                if (scrollTop > 50) {
                    $header.addClass('scrolled');
                } else {
                    $header.removeClass('scrolled');
                }

                // Optional: Add compact class for smaller header
                if (scrollTop > 100) {
                    $header.addClass('compact');
                } else {
                    $header.removeClass('compact');
                }

                lastScrollTop = scrollTop;
            });
        },

        /**
         * Setup accessibility features
         */
        setupAccessibility: function() {
            // Add focus visible class for better keyboard navigation
            $(document).on('keydown', function(e) {
                if (e.key === 'Tab') {
                    $('body').addClass('keyboard-nav');
                }
            });

            $(document).on('mousedown', function() {
                $('body').removeClass('keyboard-nav');
            });

            // Improve skip link functionality
            $('.header-skip-link').on('click', function(e) {
                e.preventDefault();
                const target = $(this).attr('href');
                $(target).attr('tabindex', '-1').focus();
            });
        }
    };

    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        AakaariBrandLayout.init();
    });

})(jQuery);
