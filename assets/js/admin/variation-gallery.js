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
            var variationId = $button.data('variation-id');
            var $galleryContainer = $('.variation-gallery-images[data-variation-id="' + variationId + '"]');
            var $galleryInput = $galleryContainer.siblings('.variation-gallery-images-input');

            // Create the media frame
            variationGalleryFrame = wp.media({
                title: 'Add Variation Gallery Images',
                button: {
                    text: 'Add to gallery'
                },
                multiple: true
            });

            // When images are selected
            variationGalleryFrame.on('select', function() {
                var selection = variationGalleryFrame.state().get('selection');
                var imageIds = $galleryInput.val() ? $galleryInput.val().split(',') : [];

                selection.map(function(attachment) {
                    attachment = attachment.toJSON();

                    // Add to array if not already present
                    if (imageIds.indexOf(attachment.id.toString()) === -1) {
                        imageIds.push(attachment.id);

                        // Add thumbnail to gallery
                        var imageUrl = attachment.sizes && attachment.sizes.thumbnail
                            ? attachment.sizes.thumbnail.url
                            : attachment.url;

                        $galleryContainer.append(
                            '<li class="image" data-attachment-id="' + attachment.id + '">' +
                                '<img src="' + imageUrl + '" />' +
                                '<a href="#" class="delete-image" title="Remove image">&times;</a>' +
                            '</li>'
                        );
                    }
                });

                // Update hidden input
                $galleryInput.val(imageIds.join(','));
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
            var $galleryInput = $galleryContainer.siblings('.variation-gallery-images-input');

            // Remove from array
            var imageIds = $galleryInput.val().split(',');
            imageIds = imageIds.filter(function(id) {
                return id != imageId;
            });

            // Update hidden input
            $galleryInput.val(imageIds.join(','));

            // Remove from DOM
            $imageItem.remove();
        });

        // Make gallery sortable
        $('.variation-gallery-images').sortable({
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
                var $galleryContainer = $(this);
                var $galleryInput = $galleryContainer.siblings('.variation-gallery-images-input');
                var imageIds = [];

                $galleryContainer.find('li.image').each(function() {
                    imageIds.push($(this).data('attachment-id'));
                });

                $galleryInput.val(imageIds.join(','));
            }
        });
    }

})(jQuery);
