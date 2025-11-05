/**
 * Homepage JavaScript for FashionMen Theme
 * Hero slider, category interactions, featured products
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        initHeroSlider();
        initCategoryHover();
        initFeaturedProducts();
    });

    /**
     * Hero Slider (if multiple hero images)
     */
    function initHeroSlider() {
        const heroSlider = $('.hero-slider');

        if (!heroSlider.length || heroSlider.children().length <= 1) {
            return;
        }

        let currentSlide = 0;
        const slides = heroSlider.children();
        const slideCount = slides.length;

        // Auto-advance slider
        setInterval(() => {
            currentSlide = (currentSlide + 1) % slideCount;
            heroSlider.css('transform', `translateX(-${currentSlide * 100}%)`);
        }, 5000);
    }

    /**
     * Category Card Hover Effects
     */
    function initCategoryHover() {
        $('.category-card').on('mouseenter', function() {
            $(this).find('img').css('transform', 'scale(1.05)');
        }).on('mouseleave', function() {
            $(this).find('img').css('transform', 'scale(1)');
        });
    }

    /**
     * Featured Products Carousel
     */
    function initFeaturedProducts() {
        const carousel = $('.featured-products-carousel');

        if (!carousel.length) {
            return;
        }

        // Add navigation buttons
        carousel.wrap('<div class="carousel-wrapper"></div>');
        carousel.parent().append('<button class="carousel-prev">‹</button><button class="carousel-next">›</button>');

        let scrollPosition = 0;
        const scrollAmount = 300;

        $('.carousel-next').on('click', function() {
            const maxScroll = carousel[0].scrollWidth - carousel[0].clientWidth;
            scrollPosition = Math.min(scrollPosition + scrollAmount, maxScroll);
            carousel.animate({ scrollLeft: scrollPosition }, 300);
        });

        $('.carousel-prev').on('click', function() {
            scrollPosition = Math.max(scrollPosition - scrollAmount, 0);
            carousel.animate({ scrollLeft: scrollPosition }, 300);
        });
    }

    /**
     * Newsletter Subscription
     */
    $('.newsletter-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const email = form.find('input[type="email"]').val();
        const submitBtn = form.find('button[type="submit"]');

        // Simple email validation
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            alert('Please enter a valid email address');
            return;
        }

        // Show loading state
        submitBtn.prop('disabled', true).text('Subscribing...');

        // Simulate AJAX request (replace with actual implementation)
        setTimeout(() => {
            submitBtn.text('Subscribed!');
            form.find('input[type="email"]').val('');

            setTimeout(() => {
                submitBtn.prop('disabled', false).text('Subscribe');
            }, 2000);
        }, 1000);
    });

    /**
     * Countdown Timer (for sales/promotions)
     */
    function initCountdown() {
        const countdown = $('.countdown-timer');

        if (!countdown.length) {
            return;
        }

        const endDate = new Date(countdown.data('end-date'));

        setInterval(() => {
            const now = new Date();
            const diff = endDate - now;

            if (diff <= 0) {
                countdown.html('Sale Ended!');
                return;
            }

            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            countdown.html(`
                <span class="time-unit"><span class="time-value">${days}</span><span class="time-label">Days</span></span>
                <span class="time-unit"><span class="time-value">${hours}</span><span class="time-label">Hours</span></span>
                <span class="time-unit"><span class="time-value">${minutes}</span><span class="time-label">Min</span></span>
                <span class="time-unit"><span class="time-value">${seconds}</span><span class="time-label">Sec</span></span>
            `);
        }, 1000);
    }

    initCountdown();

})(jQuery);
