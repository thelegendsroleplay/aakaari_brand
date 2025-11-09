/**
 * Single Product Page JavaScript
 * Handles image gallery and interactions
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        initImageGallery();
        initRelatedProductsCarousel();
    });

    /**
     * Initialize product image gallery
     */
    function initImageGallery() {
        const mainImage = document.getElementById('mainProductImage');
        const thumbnailBtns = document.querySelectorAll('.thumbnail-btn');

        if (!mainImage || thumbnailBtns.length === 0) {
            return;
        }

        thumbnailBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                // Remove active class from all thumbnails
                thumbnailBtns.forEach(function(thumb) {
                    thumb.classList.remove('active');
                });

                // Add active class to clicked thumbnail
                this.classList.add('active');

                // Update main image
                const newImageSrc = this.getAttribute('data-image');
                if (newImageSrc) {
                    mainImage.src = newImageSrc;
                }
            });
        });
    }

    /**
     * Initialize related products carousel navigation
     */
    function initRelatedProductsCarousel() {
        const carousel = document.querySelector('.related-section .product-carousel');

        if (!carousel) {
            return;
        }

        // Touch support for mobile
        let isDown = false;
        let startX;
        let scrollLeft;

        carousel.addEventListener('mousedown', function(e) {
            isDown = true;
            carousel.style.cursor = 'grabbing';
            startX = e.pageX - carousel.offsetLeft;
            scrollLeft = carousel.scrollLeft;
        });

        carousel.addEventListener('mouseleave', function() {
            isDown = false;
            carousel.style.cursor = 'grab';
        });

        carousel.addEventListener('mouseup', function() {
            isDown = false;
            carousel.style.cursor = 'grab';
        });

        carousel.addEventListener('mousemove', function(e) {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - carousel.offsetLeft;
            const walk = (x - startX) * 2;
            carousel.scrollLeft = scrollLeft - walk;
        });

        // Set initial cursor
        carousel.style.cursor = 'grab';
    }

})(jQuery);
