/**
 * Homepage JavaScript
 * Handles all homepage interactions and animations
 *
 * @package FashionMen
 * @since 1.0.0
 */

(function() {
    'use strict';

    /**
     * Initialize when DOM is ready
     */
    document.addEventListener('DOMContentLoaded', function() {
        initHeroSection();
        initCategoryCards();
        initProductCards();
        initScrollAnimations();
    });

    /**
     * Hero Section Interactions
     */
    function initHeroSection() {
        const heroButtons = document.querySelectorAll('.hero-buttons .btn');

        heroButtons.forEach(function(button) {
            // Add ripple effect on click
            button.addEventListener('click', function(e) {
                createRipple(e, this);
            });
        });
    }

    /**
     * Category Card Interactions
     */
    function initCategoryCards() {
        const categoryCards = document.querySelectorAll('.category-card');

        categoryCards.forEach(function(card) {
            // Add keyboard support
            card.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });

            // Add focus indicator
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-4px)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = '';
            });
        });
    }

    /**
     * Product Card Interactions
     */
    function initProductCards() {
        const productCards = document.querySelectorAll('.product-card');

        productCards.forEach(function(card) {
            // Add hover effect
            card.addEventListener('mouseenter', function() {
                const image = this.querySelector('.product-image');
                if (image) {
                    image.style.transform = 'scale(1.05)';
                }
            });

            card.addEventListener('mouseleave', function() {
                const image = this.querySelector('.product-image');
                if (image) {
                    image.style.transform = 'scale(1)';
                }
            });

            // Add keyboard support
            const link = card.querySelector('.product-link');
            if (link) {
                link.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.click();
                    }
                });
            }
        });
    }

    /**
     * Scroll-based Animations
     */
    function initScrollAnimations() {
        // Check if Intersection Observer is supported
        if (!('IntersectionObserver' in window)) {
            return;
        }

        const animatedElements = document.querySelectorAll(
            '.categories-section, .featured-products-section'
        );

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                    // Optional: Stop observing after animation
                    // observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        animatedElements.forEach(function(element) {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';
            element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(element);
        });

        // Add CSS class when element is visible
        const style = document.createElement('style');
        style.textContent = `
            .animate-in {
                opacity: 1 !important;
                transform: translateY(0) !important;
            }
        `;
        document.head.appendChild(style);
    }

    /**
     * Create Ripple Effect
     * @param {Event} e - Click event
     * @param {Element} element - Button element
     */
    function createRipple(e, element) {
        const ripple = document.createElement('span');
        const rect = element.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;

        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple-effect');

        element.style.position = 'relative';
        element.style.overflow = 'hidden';
        element.appendChild(ripple);

        // Remove ripple after animation
        setTimeout(function() {
            ripple.remove();
        }, 600);
    }

    /**
     * Lazy Load Images
     * Load images when they come into viewport
     */
    function initLazyLoading() {
        if (!('IntersectionObserver' in window)) {
            return;
        }

        const images = document.querySelectorAll('img[data-src]');

        const imageObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    imageObserver.unobserve(img);
                }
            });
        });

        images.forEach(function(img) {
            imageObserver.observe(img);
        });
    }

    /**
     * Smooth Scroll to Sections
     */
    function initSmoothScroll() {
        const links = document.querySelectorAll('a[href^="#"]');

        links.forEach(function(link) {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#') return;

                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    // Initialize lazy loading and smooth scroll
    initLazyLoading();
    initSmoothScroll();

    /**
     * Add ripple effect styles
     */
    const style = document.createElement('style');
    style.textContent = `
        .ripple-effect {
            position: absolute;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.5);
            pointer-events: none;
            transform: scale(0);
            animation: ripple-animation 0.6s ease-out;
        }

        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

})();
