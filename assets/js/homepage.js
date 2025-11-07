/**
 * homepage.js - Product Carousel Functionality
 * Exact match to Figma design carousel behavior
 */

(function() {
    'use strict';

    // Initialize carousels when DOM is ready
    function initCarousels() {
        // Featured Products Carousel
        initCarousel('featured');
        // New Arrivals Carousel
        initCarousel('arrivals');
    }

    function initCarousel(name) {
        const carouselId = name === 'featured' ? 'featured-carousel' : 'arrivals-carousel';
        const prevId = name === 'featured' ? 'featured-prev' : 'arrivals-prev';
        const nextId = name === 'featured' ? 'featured-next' : 'arrivals-next';

        const carousel = document.getElementById(carouselId);
        const prevBtn = document.getElementById(prevId);
        const nextBtn = document.getElementById(nextId);

        if (!carousel || !prevBtn || !nextBtn) {
            return;
        }

        // Scroll carousel left
        prevBtn.addEventListener('click', function() {
            scrollCarousel(carousel, 'left');
            updateArrowsVisibility(carousel, prevBtn, nextBtn);
        });

        // Scroll carousel right
        nextBtn.addEventListener('click', function() {
            scrollCarousel(carousel, 'right');
            updateArrowsVisibility(carousel, prevBtn, nextBtn);
        });

        // Update arrows on scroll
        carousel.addEventListener('scroll', function() {
            updateArrowsVisibility(carousel, prevBtn, nextBtn);
        });

        // Initial arrow state
        updateArrowsVisibility(carousel, prevBtn, nextBtn);
    }

    function scrollCarousel(carousel, direction) {
        const scrollAmount = carousel.offsetWidth;

        if (direction === 'left') {
            carousel.scrollBy({
                left: -scrollAmount,
                behavior: 'smooth'
            });
        } else {
            carousel.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        }
    }

    function updateArrowsVisibility(carousel, prevBtn, nextBtn) {
        const scrollLeft = carousel.scrollLeft;
        const maxScroll = carousel.scrollWidth - carousel.clientWidth;

        // Show/hide previous arrow
        if (scrollLeft <= 0) {
            prevBtn.style.display = 'none';
        } else {
            prevBtn.style.display = 'flex';
        }

        // Show/hide next arrow
        if (scrollLeft >= maxScroll - 1) {
            nextBtn.style.display = 'none';
        } else {
            nextBtn.style.display = 'flex';
        }
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCarousels);
    } else {
        initCarousels();
    }

})();
