/**
 * Product Page JavaScript
 * Handles image gallery, tabs, quantity, and customization
 */

(function($) {
  'use strict';

  // Initialize on document ready
  $(document).ready(function() {
    ProductGallery.init();
    ProductTabs.init();
    ProductQuantity.init();
    ProductCustomization.init();
    ProductReviews.init();
  });

  /**
   * Product Image Gallery Module
   */
  const ProductGallery = {
    currentImageIndex: 0,
    images: [],

    init: function() {
      this.images = $('.product-image-thumbnail').map(function() {
        return $(this).data('image-url');
      }).get();

      this.bindEvents();
    },

    bindEvents: function() {
      $('.product-image-thumbnail').on('click', this.handleThumbnailClick.bind(this));

      // Keyboard navigation
      $(document).on('keydown', this.handleKeyboard.bind(this));

      // Touch/swipe support for mobile
      let touchStartX = 0;
      let touchEndX = 0;

      $('.product-main-image').on('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
      });

      $('.product-main-image').on('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        this.handleSwipe(touchStartX, touchEndX);
      }.bind(this));
    },

    handleThumbnailClick: function(e) {
      const thumbnail = $(e.currentTarget);
      const imageUrl = thumbnail.data('image-url');
      const index = thumbnail.index();

      this.updateMainImage(imageUrl, index);
    },

    updateMainImage: function(imageUrl, index) {
      $('.product-main-image img').attr('src', imageUrl);
      $('.product-image-thumbnail').removeClass('active');
      $('.product-image-thumbnail').eq(index).addClass('active');
      this.currentImageIndex = index;
    },

    handleKeyboard: function(e) {
      // Left arrow - previous image
      if (e.keyCode === 37) {
        this.previousImage();
      }
      // Right arrow - next image
      else if (e.keyCode === 39) {
        this.nextImage();
      }
    },

    handleSwipe: function(startX, endX) {
      const threshold = 50;
      const diff = startX - endX;

      if (Math.abs(diff) > threshold) {
        if (diff > 0) {
          this.nextImage();
        } else {
          this.previousImage();
        }
      }
    },

    previousImage: function() {
      const newIndex = this.currentImageIndex > 0 ? this.currentImageIndex - 1 : this.images.length - 1;
      this.updateMainImage(this.images[newIndex], newIndex);
    },

    nextImage: function() {
      const newIndex = this.currentImageIndex < this.images.length - 1 ? this.currentImageIndex + 1 : 0;
      this.updateMainImage(this.images[newIndex], newIndex);
    }
  };

  /**
   * Product Tabs Module
   */
  const ProductTabs = {
    init: function() {
      this.bindEvents();
      this.showDefaultTab();
    },

    bindEvents: function() {
      $('.product-tab-button').on('click', this.handleTabClick.bind(this));
    },

    handleTabClick: function(e) {
      const button = $(e.currentTarget);
      const tabId = button.data('tab');

      this.showTab(tabId);
    },

    showTab: function(tabId) {
      // Hide all tabs
      $('.product-tab-content').removeClass('active');
      $('.product-tab-button').removeClass('active');

      // Show selected tab
      $('.product-tab-content[data-tab="' + tabId + '"]').addClass('active');
      $('.product-tab-button[data-tab="' + tabId + '"]').addClass('active');
    },

    showDefaultTab: function() {
      if ($('.product-tab-button').length > 0) {
        const firstTabId = $('.product-tab-button').first().data('tab');
        this.showTab(firstTabId);
      }
    }
  };

  /**
   * Product Quantity Module
   */
  const ProductQuantity = {
    init: function() {
      this.bindEvents();
    },

    bindEvents: function() {
      $('.product-quantity-decrease').on('click', this.decreaseQuantity.bind(this));
      $('.product-quantity-increase').on('click', this.increaseQuantity.bind(this));
      $('.product-quantity-input').on('change', this.validateQuantity.bind(this));
    },

    decreaseQuantity: function() {
      const input = $('.product-quantity-input');
      let value = parseInt(input.val()) || 1;
      if (value > 1) {
        input.val(value - 1).trigger('change');
      }
    },

    increaseQuantity: function() {
      const input = $('.product-quantity-input');
      let value = parseInt(input.val()) || 1;
      const max = parseInt(input.attr('max')) || 999;
      if (value < max) {
        input.val(value + 1).trigger('change');
      }
    },

    validateQuantity: function(e) {
      const input = $(e.target);
      let value = parseInt(input.val());
      const min = parseInt(input.attr('min')) || 1;
      const max = parseInt(input.attr('max')) || 999;

      if (isNaN(value) || value < min) {
        input.val(min);
      } else if (value > max) {
        input.val(max);
      }

      this.updatePrice();
    },

    updatePrice: function() {
      const quantity = parseInt($('.product-quantity-input').val()) || 1;
      const basePrice = parseFloat($('.product-price').data('base-price')) || 0;
      const customizationPrice = parseFloat($('.product-customization-price').data('price')) || 0;
      const totalPrice = (basePrice + customizationPrice) * quantity;

      $('.product-price').text('$' + totalPrice.toFixed(2));
    }
  };

  /**
   * Product Customization Module
   */
  const ProductCustomization = {
    init: function() {
      this.bindEvents();
    },

    bindEvents: function() {
      // Size selection
      $('.product-size-button').on('click', this.handleSizeSelection.bind(this));

      // Color selection
      $('.product-color-swatch').on('click', this.handleColorSelection.bind(this));

      // Customization options
      $('.customization-option').on('change', this.handleCustomizationChange.bind(this));

      // Customization modal
      $('.product-customization-button').on('click', this.openCustomizationModal.bind(this));
      $('.customization-modal-close').on('click', this.closeCustomizationModal.bind(this));
      $('.customization-save').on('click', this.saveCustomization.bind(this));
    },

    handleSizeSelection: function(e) {
      const button = $(e.currentTarget);

      if (button.prop('disabled')) return;

      $('.product-size-button').removeClass('selected');
      button.addClass('selected');

      // Update hidden input
      $('input[name="product-size"]').val(button.data('size'));
    },

    handleColorSelection: function(e) {
      const swatch = $(e.currentTarget);

      $('.product-color-swatch').removeClass('selected');
      swatch.addClass('selected');

      // Update hidden input
      $('input[name="product-color"]').val(swatch.data('color'));
    },

    handleCustomizationChange: function() {
      let totalCustomizationPrice = 0;

      $('.customization-option:checked').each(function() {
        totalCustomizationPrice += parseFloat($(this).data('price')) || 0;
      });

      $('.customization-option[type="text"]').each(function() {
        if ($(this).val()) {
          totalCustomizationPrice += parseFloat($(this).data('price')) || 0;
        }
      });

      $('.product-customization-price').data('price', totalCustomizationPrice);
      $('.product-customization-price').text('+$' + totalCustomizationPrice.toFixed(2));

      ProductQuantity.updatePrice();
    },

    openCustomizationModal: function() {
      $('.customization-modal').fadeIn();
      $('body').addClass('no-scroll');
    },

    closeCustomizationModal: function() {
      $('.customization-modal').fadeOut();
      $('body').removeClass('no-scroll');
    },

    saveCustomization: function() {
      // Collect customization data
      const customizations = {};

      $('.customization-option:checked').each(function() {
        customizations[$(this).attr('name')] = $(this).val();
      });

      $('.customization-option[type="text"]').each(function() {
        if ($(this).val()) {
          customizations[$(this).attr('name')] = $(this).val();
        }
      });

      // Update display
      this.updateCustomizationDisplay(customizations);

      // Close modal
      this.closeCustomizationModal();
    },

    updateCustomizationDisplay: function(customizations) {
      const count = Object.keys(customizations).length;

      if (count > 0) {
        $('.product-customization-button').text('Edit Customization');

        let html = '<div class="product-customization-details">';
        for (const [key, value] of Object.entries(customizations)) {
          const label = $('[name="' + key + '"]').data('label') || key;
          html += '<div>' + label + ': ' + value + '</div>';
        }
        html += '</div>';

        $('.product-customization-panel').append(html);
      } else {
        $('.product-customization-button').text('Add Customization');
        $('.product-customization-details').remove();
      }
    }
  };

  /**
   * Product Reviews Module
   */
  const ProductReviews = {
    init: function() {
      this.bindEvents();
    },

    bindEvents: function() {
      // Rating stars
      $('.review-rating-star').on('click', this.handleRatingClick.bind(this));
      $('.review-rating-star').on('mouseenter', this.handleRatingHover.bind(this));
      $('.review-rating-container').on('mouseleave', this.resetRatingHover.bind(this));

      // Review form
      $('#review-form').on('submit', this.handleReviewSubmit.bind(this));

      // Load more reviews
      $('.load-more-reviews').on('click', this.loadMoreReviews.bind(this));
    },

    handleRatingClick: function(e) {
      const star = $(e.currentTarget);
      const rating = star.data('rating');

      $('.review-rating-star').removeClass('selected');
      star.addClass('selected');
      star.prevAll('.review-rating-star').addClass('selected');

      $('input[name="rating"]').val(rating);
    },

    handleRatingHover: function(e) {
      const star = $(e.currentTarget);

      $('.review-rating-star').removeClass('hover');
      star.addClass('hover');
      star.prevAll('.review-rating-star').addClass('hover');
    },

    resetRatingHover: function() {
      $('.review-rating-star').removeClass('hover');
    },

    handleReviewSubmit: function(e) {
      e.preventDefault();

      const form = $(e.currentTarget);
      const formData = form.serialize();

      $.ajax({
        url: woocommerce_params.ajax_url,
        type: 'POST',
        data: formData + '&action=submit_product_review&nonce=' + woocommerce_params.review_nonce,
        beforeSend: function() {
          form.find('button[type="submit"]').prop('disabled', true);
        },
        success: function(response) {
          if (response.success) {
            // Show success message
            form.before('<div class="review-success">Thank you! Your review has been submitted.</div>');
            form[0].reset();

            // Reload reviews
            setTimeout(function() {
              location.reload();
            }, 2000);
          } else {
            // Show error message
            form.before('<div class="review-error">' + response.data.message + '</div>');
          }
        },
        complete: function() {
          form.find('button[type="submit"]').prop('disabled', false);
        }
      });
    },

    loadMoreReviews: function(e) {
      e.preventDefault();

      const button = $(e.currentTarget);
      const page = parseInt(button.data('page')) || 1;
      const productId = button.data('product-id');

      $.ajax({
        url: woocommerce_params.ajax_url,
        type: 'POST',
        data: {
          action: 'load_more_reviews',
          product_id: productId,
          page: page + 1,
          nonce: woocommerce_params.review_nonce
        },
        beforeSend: function() {
          button.prop('disabled', true);
        },
        success: function(response) {
          if (response.success) {
            $('.product-reviews-list').append(response.data.html);
            button.data('page', page + 1);

            if (!response.data.has_more) {
              button.hide();
            }
          }
        },
        complete: function() {
          button.prop('disabled', false);
        }
      });
    }
  };

  /**
   * Add to Cart Validation
   */
  $('.product-add-to-cart').on('click', function(e) {
    const sizeRequired = $('.product-size-button').length > 0;
    const sizeSelected = $('.product-size-button.selected').length > 0;

    if (sizeRequired && !sizeSelected) {
      e.preventDefault();

      // Show error message
      if (!$('.size-error').length) {
        $('.product-sizes').before('<div class="size-error" style="color: red; margin-bottom: 1rem;">Please select a size</div>');
      }

      // Scroll to size selection
      $('html, body').animate({
        scrollTop: $('.product-sizes').offset().top - 100
      }, 500);

      return false;
    }

    // Remove error if exists
    $('.size-error').remove();
  });

  /**
   * Related Products Click Handler
   */
  $(document).on('click', '.product-related-item', function() {
    const url = $(this).data('url');
    if (url) {
      window.location.href = url;
    }
  });

  /**
   * Sticky Add to Cart (appears on scroll)
   */
  const StickyAddToCart = {
    init: function() {
      if ($('.product-add-to-cart').length === 0) return;

      this.createStickyBar();
      this.bindScroll();
    },

    createStickyBar: function() {
      const productName = $('.product-title').text();
      const productPrice = $('.product-price').text();

      const html = `
        <div class="sticky-add-to-cart">
          <div class="sticky-product-info">
            <h4>${productName}</h4>
            <span class="sticky-price">${productPrice}</span>
          </div>
          <button class="sticky-atc-button">Add to Cart</button>
        </div>
      `;

      $('body').append(html);

      $('.sticky-atc-button').on('click', function() {
        $('.product-add-to-cart').trigger('click');
      });
    },

    bindScroll: function() {
      const addToCartOffset = $('.product-add-to-cart').offset().top;

      $(window).on('scroll', function() {
        if ($(window).scrollTop() > addToCartOffset + 200) {
          $('.sticky-add-to-cart').addClass('visible');
        } else {
          $('.sticky-add-to-cart').removeClass('visible');
        }
      });
    }
  };

  // Initialize sticky add to cart
  StickyAddToCart.init();

})(jQuery);
