/**
 * Navigation JavaScript
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {

        // Mobile menu toggle
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenuClose = document.getElementById('mobile-menu-close');
        const mobileNav = document.getElementById('mobile-navigation');

        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', function() {
                mobileNav.classList.remove('hidden');
                setTimeout(function() {
                    mobileNav.classList.add('active');
                }, 10);
                document.body.style.overflow = 'hidden';
            });
        }

        if (mobileMenuClose) {
            mobileMenuClose.addEventListener('click', function() {
                mobileNav.classList.remove('active');
                setTimeout(function() {
                    mobileNav.classList.add('hidden');
                }, 300);
                document.body.style.overflow = '';
            });
        }

        // Sticky header
        const header = document.getElementById('masthead');
        let lastScroll = 0;

        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;

            if (currentScroll > 100) {
                header.classList.add('shadow-md');
            } else {
                header.classList.remove('shadow-md');
            }

            lastScroll = currentScroll;
        });

        // Active menu item
        const currentPath = window.location.pathname;
        const menuLinks = document.querySelectorAll('.main-navigation a, .mobile-menu-items a');

        menuLinks.forEach(function(link) {
            if (link.getAttribute('href') === currentPath) {
                link.parentElement.classList.add('current-menu-item');
            }
        });

    });

})();
