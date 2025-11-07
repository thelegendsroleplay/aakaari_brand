/**
 * homepage.js - Enhanced Product Carousel with Touch Support
 * Mobile-optimized like Amazon/Flipkart
 */

(function() {
    'use strict';

    // Initialize all carousels when DOM is ready
    function initAllCarousels() {
        initCarousel('featured');
        initCarousel('arrivals');
    }

    function initCarousel(name) {
        const carouselId = name === 'featured' ? 'featured-carousel' : 'arrivals-carousel';
        const prevId = name === 'featured' ? 'featured-prev' : 'arrivals-prev';
        const nextId = name === 'featured' ? 'featured-next' : 'arrivals-next';
        const dotsId = name === 'featured' ? 'featured-dots' : 'arrivals-dots';

        const carousel = document.getElementById(carouselId);
        const prevBtn = document.getElementById(prevId);
        const nextBtn = document.getElementById(nextId);
        const dotsContainer = document.getElementById(dotsId);

        if (!carousel || !prevBtn || !nextBtn || !dotsContainer) {
            return;
        }

        const items = carousel.querySelectorAll('.carousel-item');
        if (items.length === 0) return;

        // Create dots
        const dotsCount = Math.ceil(items.length / getItemsPerView());
        createDots(dotsContainer, dotsCount, carousel);

        // Touch/Swipe support
        let startX = 0;
        let scrollStart = 0;
        let isDragging = false;

        carousel.addEventListener('touchstart', function(e) {
            startX = e.touches[0].pageX;
            scrollStart = carousel.scrollLeft;
            isDragging = true;
        }, { passive: true });

        carousel.addEventListener('touchmove', function(e) {
            if (!isDragging) return;
            const currentX = e.touches[0].pageX;
            const diff = startX - currentX;
            carousel.scrollLeft = scrollStart + diff;
        }, { passive: true });

        carousel.addEventListener('touchend', function() {
            isDragging = false;
            updateDotsAndArrows(carousel, prevBtn, nextBtn, dotsContainer);
        });

        // Mouse drag support for desktop
        carousel.addEventListener('mousedown', function(e) {
            startX = e.pageX;
            scrollStart = carousel.scrollLeft;
            isDragging = true;
            carousel.style.cursor = 'grabbing';
            carousel.style.userSelect = 'none';
        });

        carousel.addEventListener('mousemove', function(e) {
            if (!isDragging) return;
            e.preventDefault();
            const currentX = e.pageX;
            const diff = startX - currentX;
            carousel.scrollLeft = scrollStart + diff;
        });

        carousel.addEventListener('mouseup', function() {
            isDragging = false;
            carousel.style.cursor = 'grab';
            carousel.style.userSelect = 'auto';
            updateDotsAndArrows(carousel, prevBtn, nextBtn, dotsContainer);
        });

        carousel.addEventListener('mouseleave', function() {
            if (isDragging) {
                isDragging = false;
                carousel.style.cursor = 'grab';
                carousel.style.userSelect = 'auto';
            }
        });

        // Arrow button clicks
        prevBtn.addEventListener('click', function() {
            scrollCarousel(carousel, 'left');
            setTimeout(function() {
                updateDotsAndArrows(carousel, prevBtn, nextBtn, dotsContainer);
            }, 300);
        });

        nextBtn.addEventListener('click', function() {
            scrollCarousel(carousel, 'right');
            setTimeout(function() {
                updateDotsAndArrows(carousel, prevBtn, nextBtn, dotsContainer);
            }, 300);
        });

        // Update on scroll
        carousel.addEventListener('scroll', function() {
            updateDotsAndArrows(carousel, prevBtn, nextBtn, dotsContainer);
        });

        // Update on window resize
        window.addEventListener('resize', debounce(function() {
            const newDotsCount = Math.ceil(items.length / getItemsPerView());
            createDots(dotsContainer, newDotsCount, carousel);
            updateDotsAndArrows(carousel, prevBtn, nextBtn, dotsContainer);
        }, 250));

        // Initial state
        carousel.style.cursor = 'grab';
        updateDotsAndArrows(carousel, prevBtn, nextBtn, dotsContainer);
    }

    function scrollCarousel(carousel, direction) {
        const scrollAmount = carousel.offsetWidth * 0.8; // Scroll 80% of container width

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

    function updateDotsAndArrows(carousel, prevBtn, nextBtn, dotsContainer) {
        const scrollLeft = carousel.scrollLeft;
        const maxScroll = carousel.scrollWidth - carousel.clientWidth;

        // Update arrow visibility
        if (scrollLeft <= 10) {
            prevBtn.style.display = 'none';
        } else {
            prevBtn.style.display = 'flex';
        }

        if (scrollLeft >= maxScroll - 10) {
            nextBtn.style.display = 'none';
        } else {
            nextBtn.style.display = 'flex';
        }

        // Update active dot
        const dots = dotsContainer.querySelectorAll('.carousel-dot');
        const itemsPerView = getItemsPerView();
        const currentIndex = Math.round(scrollLeft / (carousel.scrollWidth / dots.length));

        dots.forEach(function(dot, index) {
            if (index === currentIndex) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
    }

    function createDots(container, count, carousel) {
        container.innerHTML = '';

        for (let i = 0; i < count; i++) {
            const dot = document.createElement('button');
            dot.className = 'carousel-dot';
            dot.setAttribute('aria-label', 'Go to slide ' + (i + 1));

            dot.addEventListener('click', function() {
                const scrollPosition = (carousel.scrollWidth / count) * i;
                carousel.scrollTo({
                    left: scrollPosition,
                    behavior: 'smooth'
                });
            });

            container.appendChild(dot);
        }
    }

    function getItemsPerView() {
        const width = window.innerWidth;

        if (width < 480) {
            return 2; // 2 items on small mobile
        } else if (width < 768) {
            return 3; // 3 items on mobile
        } else if (width < 1024) {
            return 3; // 3 items on tablet
        } else {
            return 4; // 4 items on desktop
        }
    }

    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                func.apply(context, args);
            }, wait);
        };
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAllCarousels);
    } else {
        initAllCarousels();
    }

})();
