/**
 * Home Page JavaScript
 * Handles functionality specific to the home page
 */

(function() {
    'use strict';

    // Hero banner animation
    const heroContent = document.querySelector('.hero-text-content');
    if (heroContent) {
        // Add fade-in animation class
        heroContent.style.opacity = '0';
        heroContent.style.transform = 'translateY(20px)';
        heroContent.style.transition = 'opacity 0.8s ease, transform 0.8s ease';

        setTimeout(function() {
            heroContent.style.opacity = '1';
            heroContent.style.transform = 'translateY(0)';
        }, 100);
    }

    // Category cards hover effect
    const categoryCards = document.querySelectorAll('.category-card');
    categoryCards.forEach(function(card) {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.02)';
            this.style.transition = 'transform 0.3s ease';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });

    // Product carousel/grid scroll
    const productCarousels = document.querySelectorAll('.product-carousel-wrapper');
    productCarousels.forEach(function(carousel) {
        // Enable horizontal scrolling with mouse wheel
        carousel.addEventListener('wheel', function(e) {
            if (e.deltaY !== 0) {
                e.preventDefault();
                carousel.scrollLeft += e.deltaY;
            }
        }, { passive: false });
    });

    // Intersection Observer for scroll animations
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    // Observe sections for animation
    const sections = document.querySelectorAll('.category-section, .products-section, .promo-section, .trust-section');
    sections.forEach(function(section) {
        section.style.opacity = '0';
        section.style.transform = 'translateY(30px)';
        section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';

        observer.observe(section);
    });

    // Add CSS for animate-in class
    const style = document.createElement('style');
    style.textContent = `
        .animate-in {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
    `;
    document.head.appendChild(style);

    // Promo section parallax effect
    const promoSection = document.querySelector('.promo-section');
    if (promoSection) {
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const promoPosition = promoSection.offsetTop;
            const windowHeight = window.innerHeight;

            if (scrolled > promoPosition - windowHeight && scrolled < promoPosition + promoSection.offsetHeight) {
                const rate = (scrolled - (promoPosition - windowHeight)) * 0.1;
                const promoContent = promoSection.querySelector('.promo-content');
                if (promoContent) {
                    promoContent.style.transform = 'translateY(' + rate + 'px)';
                }
            }
        });
    }

    // Trust badges counter animation
    const trustSection = document.querySelector('.trust-section');
    if (trustSection) {
        const trustObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    const trustItems = entry.target.querySelectorAll('.trust-item');
                    trustItems.forEach(function(item, index) {
                        setTimeout(function() {
                            item.style.opacity = '1';
                            item.style.transform = 'translateY(0)';
                        }, index * 100);
                    });
                    trustObserver.unobserve(entry.target);
                }
            });
        }, observerOptions);

        const trustItems = trustSection.querySelectorAll('.trust-item');
        trustItems.forEach(function(item) {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        });

        trustObserver.observe(trustSection);
    }

    // Quick view functionality for products (can be extended)
    const products = document.querySelectorAll('.products-grid .product');
    products.forEach(function(product) {
        product.addEventListener('mouseenter', function() {
            const addToCartButton = this.querySelector('.add_to_cart_button');
            if (addToCartButton) {
                addToCartButton.style.opacity = '1';
                addToCartButton.style.transform = 'translateY(0)';
            }
        });

        product.addEventListener('mouseleave', function() {
            const addToCartButton = this.querySelector('.add_to_cart_button');
            if (addToCartButton) {
                addToCartButton.style.opacity = '0';
                addToCartButton.style.transform = 'translateY(10px)';
            }
        });
    });

})();
