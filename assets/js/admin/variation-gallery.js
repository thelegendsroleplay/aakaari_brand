/**
 * Variation Gallery Admin JavaScript
 * Handles multiple image uploads for product variations
 */

(function($) {
    'use strict';

    var variationGalleryFrame;

    $(document).ready(function() {
        initVariationGallery();
    });

    /**
     * Initialize variation gallery functionality
     */
    function initVariationGallery() {
        // Use event delegation for dynamically added variations
        $(document).on('click', '.variation-gallery-add-images', function(e) {
            e.preventDefault();

            var $button = $(this);
            var loop = $button.data('loop');
            var $galleryContainer = $('.variation-gallery-images[data-loop="' + loop + '"]');
            var $galleryInput = $('.variation-gallery-images-input[data-loop="' + loop + '"]');

            // Create the media frame
            variationGalleryFrame = wp.media({
                title: 'Add Variation Gallery Images',
                button: {
                    text: 'Add to gallery'
                },
                multiple: true,
                library: {
                    type: 'image'
                }
            });

            // When images are selected
            variationGalleryFrame.on('select', function() {
                var selection = variationGalleryFrame.state().get('selection');
                var imageIds = $galleryInput.val() ? $galleryInput.val().split(',').filter(function(id) { return id; }) : [];

                selection.map(function(attachment) {
                    attachment = attachment.toJSON();

                    // Add to array if not already present
                    if (imageIds.indexOf(attachment.id.toString()) === -1) {
                        imageIds.push(attachment.id);

                        // Add thumbnail to gallery
                        var imageUrl = attachment.sizes && attachment.sizes.thumbnail
                            ? attachment.sizes.thumbnail.url
                            : attachment.url;

                        var $imageItem = $('<li class="image" data-attachment-id="' + attachment.id + '" style="position: relative; width: 80px; height: 80px; border: 1px solid #ddd; border-radius: 4px; overflow: hidden; background: #fff; cursor: move;">' +
                                '<img src="' + imageUrl + '" style="width: 100%; height: 100%; object-fit: cover;" />' +
                                '<a href="#" class="delete-image" title="Remove image" style="position: absolute; top: 2px; right: 2px; width: 20px; height: 20px; background: #dc3232; color: #fff; text-decoration: none; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; line-height: 1;">&times;</a>' +
                            '</li>');

                        $galleryContainer.append($imageItem);
                    }
                });

                // Update hidden input
                $galleryInput.val(imageIds.join(','));

                // Trigger change event so WooCommerce knows the variation needs saving
                triggerVariationChange($galleryInput);

                // Re-initialize sortable for new items
                initSortable($galleryContainer, $galleryInput);
            });

            // Open the media frame
            variationGalleryFrame.open();
        });

        // Delete image from gallery
        $(document).on('click', '.variation-gallery-images .delete-image', function(e) {
            e.preventDefault();

            var $imageItem = $(this).closest('li.image');
            var imageId = $imageItem.data('attachment-id');
            var $galleryContainer = $imageItem.closest('.variation-gallery-images');
            var loop = $galleryContainer.data('loop');
            var $galleryInput = $('.variation-gallery-images-input[data-loop="' + loop + '"]');

            // Remove from array
            var imageIds = $galleryInput.val() ? $galleryInput.val().split(',').filter(function(id) { return id; }) : [];
            imageIds = imageIds.filter(function(id) {
                return id != imageId;
            });

            // Update hidden input
            $galleryInput.val(imageIds.join(','));

            // Trigger change event so WooCommerce knows the variation needs saving
            triggerVariationChange($galleryInput);

            // Remove from DOM
            $imageItem.remove();
        });

        // Initialize sortable for existing galleries
        $('.variation-gallery-images').each(function() {
            var $galleryContainer = $(this);
            var loop = $galleryContainer.data('loop');
            var $galleryInput = $('.variation-gallery-images-input[data-loop="' + loop + '"]');
            initSortable($galleryContainer, $galleryInput);
        });
    }

    /**
     * Initialize sortable functionality for a gallery
     */
    function initSortable($galleryContainer, $galleryInput) {
        if ($galleryContainer.hasClass('ui-sortable')) {
            $galleryContainer.sortable('destroy');
        }

        $galleryContainer.sortable({
            items: 'li.image',
            cursor: 'move',
            scrollSensitivity: 40,
            forcePlaceholderSize: true,
            forceHelperSize: false,
            helper: 'clone',
            opacity: 0.65,
            placeholder: 'variation-gallery-sortable-placeholder',
            start: function(event, ui) {
                ui.item.css('background-color', '#f6f6f6');
            },
            stop: function(event, ui) {
                ui.item.removeAttr('style');
            },
            update: function(event, ui) {
                var imageIds = [];

                $galleryContainer.find('li.image').each(function() {
                    imageIds.push($(this).data('attachment-id'));
                });

                $galleryInput.val(imageIds.join(','));

                // Trigger change event so WooCommerce knows the variation needs saving
                triggerVariationChange($galleryInput);
            }
        });
    }

    /**
     * Trigger WooCommerce variation change detection
     * This makes the "Save changes" button active
     */
    function triggerVariationChange($input) {
        // Find the variation wrapper
        var $variation = $input.closest('.woocommerce_variation');

        if ($variation.length) {
            // Mark variation as needing update
            $variation.addClass('variation-needs-update');
        }

        // Enable the save changes button
        var $saveButton = $('button.save-variation-changes, button.cancel-variation-changes');
        if ($saveButton.length) {
            $saveButton.removeAttr('disabled');
        }

        // Trigger change event on the input itself
        $input.trigger('change');
    }

})(jQuery);
