/**
 * Home Page JavaScript
 * Aakaari Brand Theme
 */

(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {
        console.log('Home Page Loaded');

        // Initialize hero slider
        initHeroSlider();

        // Initialize product carousels
        initProductCarousels();

        // Quick view functionality
        initQuickView();

        // Add to cart functionality
        initAddToCart();

        // Category card interactions
        initCategoryCards();
    });

    /**
     * Initialize Hero Slider
     */
    function initHeroSlider() {
        const slides = document.querySelectorAll('.hero-slide');
        const dots = document.querySelectorAll('.hero-dot');

        if (!slides.length) return;

        let currentSlide = 0;
        let slideInterval;
        const slideDelay = 5000; // 5 seconds

        // Go to specific slide
        function goToSlide(n) {
            // Remove active class from all slides and dots
            slides.forEach(slide => {
                slide.classList.remove('active');
            });
            dots.forEach(dot => {
                dot.classList.remove('active');
            });

            // Wrap around if necessary
            if (n >= slides.length) {
                currentSlide = 0;
            } else if (n < 0) {
                currentSlide = slides.length - 1;
            } else {
                currentSlide = n;
            }

            // Add active class to current slide and dot
            slides[currentSlide].classList.add('active');
            if (dots[currentSlide]) {
                dots[currentSlide].classList.add('active');
            }
        }

        // Next slide
        function nextSlide() {
            goToSlide(currentSlide + 1);
        }

        // Previous slide
        function prevSlide() {
            goToSlide(currentSlide - 1);
        }

        // Auto play
        function startSlideShow() {
            slideInterval = setInterval(nextSlide, slideDelay);
        }

        // Stop auto play
        function stopSlideShow() {
            clearInterval(slideInterval);
        }

        // Dot navigation
        dots.forEach((dot, index) => {
            dot.addEventListener('click', function() {
                goToSlide(index);
                stopSlideShow();
                startSlideShow();
            });
        });

        // Touch support for mobile swipe
        let touchStartX = 0;
        let touchEndX = 0;
        const sliderContainer = document.querySelector('.hero-slider-container');

        if (sliderContainer) {
            sliderContainer.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
                stopSlideShow();
            });

            sliderContainer.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
                startSlideShow();
            });

            function handleSwipe() {
                const swipeThreshold = 50;
                const diff = touchStartX - touchEndX;

                if (Math.abs(diff) > swipeThreshold) {
                    if (diff > 0) {
                        // Swipe left - next slide
                        nextSlide();
                    } else {
                        // Swipe right - previous slide
                        prevSlide();
                    }
                }
            }

            // Pause on hover (desktop)
            sliderContainer.addEventListener('mouseenter', stopSlideShow);
            sliderContainer.addEventListener('mouseleave', startSlideShow);
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                prevSlide();
                stopSlideShow();
                startSlideShow();
            } else if (e.key === 'ArrowRight') {
                nextSlide();
                stopSlideShow();
                startSlideShow();
            }
        });

        // Start the slideshow
        startSlideShow();

        // Pause when page is not visible
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopSlideShow();
            } else {
                startSlideShow();
            }
        });
    }

    /**
     * Initialize Product Carousels with Navigation
     */
    function initProductCarousels() {
        const carousels = document.querySelectorAll('.product-carousel');

        carousels.forEach(function(carousel) {
            const carouselId = carousel.getAttribute('data-carousel');
            const products = carousel.querySelectorAll('.product-card');

            if (products.length === 0) {
                return;
            }

            // Get navigation buttons
            const prevBtn = document.querySelector(`[data-carousel-nav="${carouselId}"][data-direction="prev"]`);
            const nextBtn = document.querySelector(`[data-carousel-nav="${carouselId}"][data-direction="next"]`);

            if (!prevBtn || !nextBtn) {
                return;
            }

            // Handle navigation clicks
            prevBtn.addEventListener('click', function() {
                const cardWidth = products[0].offsetWidth + 20; // width + gap
                carousel.scrollBy({
                    left: -cardWidth * 2,
                    behavior: 'smooth'
                });
            });

            nextBtn.addEventListener('click', function() {
                const cardWidth = products[0].offsetWidth + 20; // width + gap
                carousel.scrollBy({
                    left: cardWidth * 2,
                    behavior: 'smooth'
                });
            });

            // Update button states on scroll
            function updateNavButtons() {
                const scrollLeft = carousel.scrollLeft;
                const maxScroll = carousel.scrollWidth - carousel.clientWidth;

                if (scrollLeft <= 0) {
                    prevBtn.setAttribute('disabled', 'disabled');
                } else {
                    prevBtn.removeAttribute('disabled');
                }

                if (scrollLeft >= maxScroll - 5) { // -5 for rounding
                    nextBtn.setAttribute('disabled', 'disabled');
                } else {
                    nextBtn.removeAttribute('disabled');
                }
            }

            // Initial button state
            updateNavButtons();

            // Update on scroll
            carousel.addEventListener('scroll', updateNavButtons);

            // Add touch support for mobile swiping
            let startX = 0;
            let scrollLeft = 0;
            let isDown = false;

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

            // Touch events for mobile
            carousel.addEventListener('touchstart', function(e) {
                startX = e.touches[0].pageX - carousel.offsetLeft;
                scrollLeft = carousel.scrollLeft;
            });

            carousel.addEventListener('touchmove', function(e) {
                if (!startX) return;
                const x = e.touches[0].pageX - carousel.offsetLeft;
                const walk = (x - startX) * 2;
                carousel.scrollLeft = scrollLeft - walk;
            });

            carousel.addEventListener('touchend', function() {
                startX = 0;
            });
        });
    }

    /**
     * Initialize Quick View Modal
     */
    function initQuickView() {
        const modal = document.getElementById('quickViewModal');
        const closeBtn = document.getElementById('quickViewClose');
        const contentDiv = document.getElementById('quickViewContent');

        // Open quick view
        $(document).on('click', '.quick-view-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const productId = $(this).data('product-id');
            openQuickView(productId, modal, contentDiv);
        });

        // Close quick view
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                closeQuickView(modal);
            });
        }

        // Close on overlay click
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeQuickView(modal);
                }
            });
        }

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal && modal.classList.contains('active')) {
                closeQuickView(modal);
            }
        });
    }

    /**
     * Open Quick View Modal
     */
    function openQuickView(productId, modal, contentDiv) {
        console.log('Opening quick view for product:', productId);
        console.log('aakaariAjax object:', aakaariAjax);

        // Show modal with loading state
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';

        // Show loading content
        contentDiv.innerHTML = `
            <div class="quick-view-loading">
                <div class="quick-view-loading-spinner"></div>
                <p>Loading product details...</p>
            </div>
        `;

        // Check if aakaariAjax is defined
        if (typeof aakaariAjax === 'undefined') {
            console.error('aakaariAjax is not defined!');
            contentDiv.innerHTML = `
                <div class="quick-view-loading">
                    <p>Configuration error. Please refresh the page.</p>
                </div>
            `;
            return;
        }

        // Fetch product details via AJAX
        $.ajax({
            type: 'POST',
            url: aakaariAjax.ajaxUrl,
            data: {
                action: 'aakaari_get_quick_view',
                product_id: productId,
                nonce: aakaariAjax.nonce
            },
            success: function(response) {
                console.log('AJAX Response:', response);
                if (response.success) {
                    contentDiv.innerHTML = response.data.html;
                    initQuickViewInteractions();
                } else {
                    console.error('AJAX Error:', response.data);
                    contentDiv.innerHTML = `
                        <div class="quick-view-loading">
                            <p>Failed to load product details.</p>
                            <p class="error-message">${response.data.message || 'Unknown error'}</p>
                        </div>
                    `;
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Request Failed:', {xhr, status, error});
                contentDiv.innerHTML = `
                    <div class="quick-view-loading">
                        <p>An error occurred. Please try again.</p>
                        <p class="error-message">Error: ${error}</p>
                    </div>
                `;
            }
        });
    }

    /**
     * Close Quick View Modal
     */
    function closeQuickView(modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    /**
     * Initialize Quick View Interactions
     */
    function initQuickViewInteractions() {
        // Quantity controls
        $(document).on('click', '.quick-view-quantity-btn', function() {
            const $input = $(this).siblings('.quick-view-quantity-value');
            let value = parseInt($input.text()) || 1;

            if ($(this).hasClass('plus')) {
                value++;
            } else if ($(this).hasClass('minus') && value > 1) {
                value--;
            }

            $input.text(value);
        });

        // Variation selection
        $(document).on('click', '.quick-view-variation-option', function() {
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
        });

        // Thumbnail selection
        $(document).on('click', '.quick-view-thumbnail', function() {
            const newSrc = $(this).find('img').attr('src');
            $('.quick-view-main-image img').attr('src', newSrc);
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
        });

        // Add to cart from quick view
        $(document).on('click', '.quick-view-add-to-cart', function() {
            const $button = $(this);
            const productId = $button.data('product-id');
            const quantity = parseInt($('.quick-view-quantity-value').text()) || 1;

            // Collect selected variations (for variable products)
            const selectedOptions = {};
            $('.quick-view-variation-option.active').each(function() {
                const attribute = $(this).data('attribute');
                const value = $(this).data('value');
                if (attribute && value) {
                    selectedOptions[attribute] = value;
                }
            });

            // Check if product has variations and if all are selected
            const $variationGroups = $('.quick-view-variation');
            if ($variationGroups.length > 0) {
                const selectedCount = Object.keys(selectedOptions).length;
                if (selectedCount < $variationGroups.length) {
                    showNotification('Please select all product options', 'error');
                    return;
                }
            }

            $button.prop('disabled', true);
            const originalText = $button.text();
            $button.text('Adding...');

            $.ajax({
                type: 'POST',
                url: aakaariAjax.ajaxUrl,
                data: {
                    action: 'aakaari_add_to_cart',
                    product_id: productId,
                    quantity: quantity,
                    options: selectedOptions,
                    nonce: aakaariAjax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $button.text('Added!');
                        updateCartCount();
                        showNotification('Product added to cart!', 'success');

                        setTimeout(function() {
                            $button.text(originalText);
                            $button.prop('disabled', false);
                            closeQuickView(document.getElementById('quickViewModal'));
                        }, 1500);
                    } else {
                        $button.text(originalText);
                        $button.prop('disabled', false);
                        showNotification('Failed to add product to cart.', 'error');
                    }
                },
                error: function() {
                    $button.text(originalText);
                    $button.prop('disabled', false);
                    showNotification('An error occurred. Please try again.', 'error');
                }
            });
        });
    }

    /**
     * Initialize Add to Cart
     */
    function initAddToCart() {
        console.log('=== initAddToCart function initialized ===');

        $(document).on('click', '.product-card-add-to-cart', function(e) {
            console.log('=== ADD TO CART BUTTON CLICKED ===');
            e.preventDefault();
            e.stopPropagation();

            const $button = $(this);
            const productId = $button.data('product-id');
            const productType = $button.data('product-type');

            console.log('Button element:', $button);
            console.log('Product ID:', productId);
            console.log('Product Type:', productType);
            console.log('Product Type strict check:', productType === 'simple', productType === 'variable');

            // Validate product ID
            if (!productId) {
                console.error('Product ID not found');
                showNotification('Unable to add product to cart', 'error');
                return;
            }

            // Disable button and show loading state
            $button.prop('disabled', true);
            const originalText = $button.text();
            $button.text('Adding...');

            console.log('Entering conditional - productType:', productType);

            // Simple products can be added directly via AJAX
            if (productType === 'simple') {
                console.log('=== SIMPLE PRODUCT PATH ===');
                console.log('Adding simple product to cart:', productId);
                $.ajax({
                    type: 'POST',
                    url: aakaariAjax.ajaxUrl,
                    data: {
                        action: 'aakaari_add_to_cart',
                        product_id: productId,
                        quantity: 1,
                        nonce: aakaariAjax.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            $button.text('Added!');

                            // Update cart count
                            updateCartCount();

                            // Reset button after 2 seconds
                            setTimeout(function() {
                                $button.text(originalText);
                                $button.prop('disabled', false);
                            }, 2000);

                            // Show success message
                            showNotification('Product added to cart!', 'success');
                        } else {
                            $button.text(originalText);
                            $button.prop('disabled', false);
                            showNotification('Failed to add product to cart.', 'error');
                        }
                    },
                    error: function() {
                        $button.text(originalText);
                        $button.prop('disabled', false);
                        showNotification('An error occurred. Please try again.', 'error');
                    }
                });
            } else {
                // For variable products, redirect to product page to select variations
                console.log('Redirecting to product page for variable product:', productId, 'Type:', productType);
                const productCard = $button.closest('.product-card');

                if (!productCard.length) {
                    console.error('Product card not found');
                    $button.prop('disabled', false);
                    $button.text(originalText);
                    showNotification('Unable to find product information', 'error');
                    return;
                }

                const productLink = productCard.find('.product-card-link').attr('href');
                const imageLink = productCard.find('.product-card-image-link').attr('href');

                console.log('Found product links:', {productLink, imageLink});

                // Ensure we have a valid URL before redirecting
                if (productLink && productLink !== 'undefined' && productLink.trim() !== '') {
                    console.log('Redirecting to product link:', productLink);
                    window.location.href = productLink;
                } else if (imageLink && imageLink !== 'undefined' && imageLink.trim() !== '') {
                    // Fallback: try to get from image link
                    console.log('Using image link as fallback:', imageLink);
                    window.location.href = imageLink;
                } else {
                    console.error('Could not find valid product link', {productLink, imageLink});
                    $button.prop('disabled', false);
                    $button.text(originalText);
                    showNotification('Unable to navigate to product page', 'error');
                }
            }
        });
    }

    /**
     * Update Cart Count
     */
    function updateCartCount() {
        $.ajax({
            type: 'POST',
            url: aakaariAjax.ajaxUrl,
            data: {
                action: 'aakaari_get_cart_count',
                nonce: aakaariAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('.cart-count').text(response.data.count);
                }
            }
        });
    }

    /**
     * Show Notification
     */
    function showNotification(message, type) {
        // Create notification element
        const $notification = $('<div class="aakaari-notification"></div>')
            .addClass('notification-' + type)
            .text(message);

        // Add to body
        $('body').append($notification);

        // Show notification
        setTimeout(function() {
            $notification.addClass('show');
        }, 100);

        // Hide and remove notification after 3 seconds
        setTimeout(function() {
            $notification.removeClass('show');
            setTimeout(function() {
                $notification.remove();
            }, 300);
        }, 3000);
    }

    /**
     * Initialize Category Cards
     */
    function initCategoryCards() {
        $('.category-card').on('mouseenter', function() {
            $(this).addClass('hover');
        }).on('mouseleave', function() {
            $(this).removeClass('hover');
        });
    }

    /**
     * Smooth Scroll for Anchor Links
     */
    $('a[href^="#"]').on('click', function(e) {
        const target = $(this.getAttribute('href'));

        if (target.length) {
            e.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 100
            }, 600);
        }
    });

})(jQuery);
