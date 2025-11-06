/**
 * Wishlist Page JavaScript
 *
 * Handles wishlist interactions, add/remove, and move to cart functionality
 *
 * @package Aakaari
 */

(function($) {
    'use strict';

    // Initialize when document is ready
    $(document).ready(function() {
        initWishlistPage();
    });

    /**
     * Initialize Wishlist Page
     */
    function initWishlistPage() {
        // Remove from wishlist
        initRemoveButtons();

        // Add to cart
        initAddToCartButtons();

        // Bulk actions
        initBulkActions();

        // Share wishlist
        initShareWishlist();

        // Clear wishlist
        initClearWishlist();

        // Add all to cart
        initAddAllToCart();
    }

    /**
     * Initialize Remove Buttons
     */
    function initRemoveButtons() {
        $(document).on('click', '.wishlist-remove', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const button = $(this);
            const productId = button.data('product-id');
            const card = button.closest('.wishlist-card');

            if (confirm('Remove this item from your wishlist?')) {
                removeFromWishlist(productId, card);
            }
        });
    }

    /**
     * Remove Product from Wishlist
     */
    function removeFromWishlist(productId, card) {
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'aakaari_remove_from_wishlist',
                product_id: productId,
                nonce: ajax_object.nonce
            },
            beforeSend: function() {
                card.css('opacity', '0.5');
            },
            success: function(response) {
                if (response.success) {
                    // Animate card removal
                    card.fadeOut(300, function() {
                        $(this).remove();

                        // Check if wishlist is empty
                        if ($('.wishlist-card').length === 0) {
                            showEmptyWishlist();
                        }

                        // Update stats
                        updateWishlistStats();
                    });

                    showNotification('Removed from wishlist', 'success');
                } else {
                    card.css('opacity', '1');
                    showNotification(response.data.message || 'Failed to remove from wishlist', 'error');
                }
            },
            error: function() {
                card.css('opacity', '1');
                showNotification('An error occurred', 'error');
            }
        });
    }

    /**
     * Initialize Add to Cart Buttons
     */
    function initAddToCartButtons() {
        $(document).on('click', '.add-to-cart-btn', function(e) {
            e.preventDefault();

            const button = $(this);
            const productId = button.data('product-id');

            if (button.prop('disabled')) {
                return;
            }

            addToCart(productId, button);
        });
    }

    /**
     * Add Product to Cart
     */
    function addToCart(productId, button) {
        const originalText = button.html();

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'aakaari_add_to_cart_from_wishlist',
                product_id: productId,
                remove_from_wishlist: false,
                nonce: ajax_object.nonce
            },
            beforeSend: function() {
                button.prop('disabled', true);
                button.html('<span>Adding...</span>');
            },
            success: function(response) {
                if (response.success) {
                    button.html('<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Added');

                    // Update cart count if available
                    if (response.data.cart_count) {
                        updateCartCount(response.data.cart_count);
                    }

                    showNotification('Added to cart!', 'success');

                    // Reset button after 2 seconds
                    setTimeout(function() {
                        button.html(originalText);
                        button.prop('disabled', false);
                    }, 2000);
                } else {
                    button.html(originalText);
                    button.prop('disabled', false);
                    showNotification(response.data.message || 'Failed to add to cart', 'error');
                }
            },
            error: function() {
                button.html(originalText);
                button.prop('disabled', false);
                showNotification('An error occurred', 'error');
            }
        });
    }

    /**
     * Initialize Bulk Actions
     */
    function initBulkActions() {
        let selectedItems = [];

        // Checkbox change
        $(document).on('change', '.wishlist-card-checkbox', function() {
            const productId = $(this).val();

            if ($(this).is(':checked')) {
                selectedItems.push(productId);
            } else {
                const index = selectedItems.indexOf(productId);
                if (index > -1) {
                    selectedItems.splice(index, 1);
                }
            }

            // Show/hide bulk actions bar
            if (selectedItems.length > 0) {
                showBulkActionsBar(selectedItems.length);
            } else {
                hideBulkActionsBar();
            }
        });

        // Bulk add to cart
        $(document).on('click', '#bulk-add-to-cart', function() {
            bulkAddToCart(selectedItems);
        });

        // Bulk remove
        $(document).on('click', '#bulk-remove', function() {
            if (confirm(`Remove ${selectedItems.length} items from your wishlist?`)) {
                bulkRemove(selectedItems);
                selectedItems = [];
            }
        });

        // Cancel bulk actions
        $(document).on('click', '#bulk-cancel', function() {
            $('.wishlist-card-checkbox').prop('checked', false);
            selectedItems = [];
            hideBulkActionsBar();
        });
    }

    /**
     * Show Bulk Actions Bar
     */
    function showBulkActionsBar(count) {
        let bar = $('.bulk-actions-bar');

        if (bar.length === 0) {
            bar = $(`
                <div class="bulk-actions-bar">
                    <div class="bulk-actions-info">
                        <span class="selected-count">${count} selected</span>
                    </div>
                    <div class="bulk-actions-buttons">
                        <button class="bulk-action-btn" id="bulk-add-to-cart">Add to Cart</button>
                        <button class="bulk-action-btn danger" id="bulk-remove">Remove</button>
                        <button class="bulk-action-btn" id="bulk-cancel">Cancel</button>
                    </div>
                </div>
            `);
            $('body').append(bar);
        } else {
            bar.find('.selected-count').text(`${count} selected`);
        }
    }

    /**
     * Hide Bulk Actions Bar
     */
    function hideBulkActionsBar() {
        $('.bulk-actions-bar').fadeOut(300, function() {
            $(this).remove();
        });
    }

    /**
     * Bulk Add to Cart
     */
    function bulkAddToCart(productIds) {
        let addedCount = 0;
        let totalCount = productIds.length;

        productIds.forEach(function(productId, index) {
            $.ajax({
                url: ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'aakaari_add_to_cart_from_wishlist',
                    product_id: productId,
                    remove_from_wishlist: false,
                    nonce: ajax_object.nonce
                },
                success: function(response) {
                    if (response.success) {
                        addedCount++;
                    }
                },
                complete: function() {
                    // Check if all requests are complete
                    if (index === totalCount - 1) {
                        if (addedCount > 0) {
                            showNotification(`${addedCount} items added to cart!`, 'success');
                            $('.wishlist-card-checkbox').prop('checked', false);
                            hideBulkActionsBar();
                        } else {
                            showNotification('No items could be added to cart', 'error');
                        }
                    }
                }
            });
        });
    }

    /**
     * Bulk Remove
     */
    function bulkRemove(productIds) {
        let removedCount = 0;
        let totalCount = productIds.length;

        productIds.forEach(function(productId, index) {
            $.ajax({
                url: ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'aakaari_remove_from_wishlist',
                    product_id: productId,
                    nonce: ajax_object.nonce
                },
                success: function(response) {
                    if (response.success) {
                        removedCount++;
                        $(`.wishlist-card[data-product-id="${productId}"]`).fadeOut(300, function() {
                            $(this).remove();

                            if ($('.wishlist-card').length === 0) {
                                showEmptyWishlist();
                            }
                        });
                    }
                },
                complete: function() {
                    if (index === totalCount - 1) {
                        if (removedCount > 0) {
                            showNotification(`${removedCount} items removed`, 'success');
                            hideBulkActionsBar();
                            updateWishlistStats();
                        }
                    }
                }
            });
        });
    }

    /**
     * Initialize Share Wishlist
     */
    function initShareWishlist() {
        $('#share-wishlist').on('click', function() {
            const url = window.location.href;
            const title = 'Check out my wishlist!';

            // Create share menu
            const shareMenu = $(`
                <div class="share-menu" style="
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background: white;
                    padding: 2rem;
                    border-radius: 12px;
                    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
                    z-index: 1000;
                    text-align: center;
                ">
                    <h3 style="margin-bottom: 1rem;">Share Wishlist</h3>
                    <div style="display: flex; gap: 1rem; justify-content: center; margin-bottom: 1rem;">
                        <button class="share-option" data-method="copy" style="padding: 0.75rem 1.5rem; border: 1px solid #e5e5e5; border-radius: 8px; background: white; cursor: pointer;">
                            Copy Link
                        </button>
                        <button class="share-option" data-method="email" style="padding: 0.75rem 1.5rem; border: 1px solid #e5e5e5; border-radius: 8px; background: white; cursor: pointer;">
                            Email
                        </button>
                    </div>
                    <button class="close-share" style="padding: 0.5rem 1rem; background: #000; color: white; border: none; border-radius: 6px; cursor: pointer;">Close</button>
                </div>
                <div class="share-overlay" style="
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.5);
                    z-index: 999;
                "></div>
            `);

            $('body').append(shareMenu);

            // Share options
            $('.share-option').on('click', function() {
                const method = $(this).data('method');

                if (method === 'copy') {
                    copyToClipboard(url);
                    showNotification('Link copied to clipboard!', 'success');
                } else if (method === 'email') {
                    window.location.href = `mailto:?subject=${encodeURIComponent(title)}&body=${encodeURIComponent(url)}`;
                }

                $('.share-menu, .share-overlay').remove();
            });

            // Close share menu
            $('.close-share, .share-overlay').on('click', function() {
                $('.share-menu, .share-overlay').remove();
            });
        });
    }

    /**
     * Initialize Clear Wishlist
     */
    function initClearWishlist() {
        $('#clear-wishlist').on('click', function() {
            if (confirm('Are you sure you want to clear your entire wishlist?')) {
                clearWishlist();
            }
        });
    }

    /**
     * Clear Wishlist
     */
    function clearWishlist() {
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'aakaari_clear_wishlist',
                nonce: ajax_object.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('.wishlist-grid').fadeOut(300, function() {
                        showEmptyWishlist();
                    });
                    showNotification('Wishlist cleared', 'success');
                } else {
                    showNotification(response.data.message || 'Failed to clear wishlist', 'error');
                }
            },
            error: function() {
                showNotification('An error occurred', 'error');
            }
        });
    }

    /**
     * Initialize Add All to Cart
     */
    function initAddAllToCart() {
        $('#add-all-to-cart').on('click', function() {
            addAllToCart();
        });
    }

    /**
     * Add All Items to Cart
     */
    function addAllToCart() {
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'aakaari_add_all_to_cart',
                nonce: ajax_object.nonce
            },
            beforeSend: function() {
                $('#add-all-to-cart').prop('disabled', true).html('Adding...');
            },
            success: function(response) {
                if (response.success) {
                    showNotification(response.data.message, 'success');

                    // Update cart count if available
                    if (response.data.cart_count) {
                        updateCartCount(response.data.cart_count);
                    }
                } else {
                    showNotification(response.data.message || 'Failed to add items to cart', 'error');
                }
            },
            error: function() {
                showNotification('An error occurred', 'error');
            },
            complete: function() {
                $('#add-all-to-cart').prop('disabled', false).html(`
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"/>
                        <circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                    </svg>
                    Add All to Cart
                `);
            }
        });
    }

    /**
     * Show Empty Wishlist State
     */
    function showEmptyWishlist() {
        const emptyState = `
            <div class="wishlist-empty">
                <div class="empty-icon">❤️</div>
                <h2 class="empty-title">Your wishlist is empty</h2>
                <p class="empty-description">Start adding items you love to your wishlist!</p>
                <a href="${getShopUrl()}" class="hero-button primary">Browse Products</a>
            </div>
        `;

        $('.wishlist-container').html(emptyState);
    }

    /**
     * Update Wishlist Stats
     */
    function updateWishlistStats() {
        const totalItems = $('.wishlist-card').length;
        $('.wishlist-stat .stat-value').first().text(totalItems);

        // You could also recalculate total value and in-stock count here
    }

    /**
     * Update Cart Count
     */
    function updateCartCount(count) {
        $('.cart-count').text(count);
    }

    /**
     * Get Shop URL
     */
    function getShopUrl() {
        // Try to get WooCommerce shop URL, fallback to homepage
        return typeof wc_add_to_cart_params !== 'undefined' && wc_add_to_cart_params.wc_ajax_url ?
            wc_add_to_cart_params.wc_ajax_url.replace('wc-ajax', 'shop') :
            '/shop/';
    }

    /**
     * Copy to Clipboard
     */
    function copyToClipboard(text) {
        const tempInput = $('<input>');
        $('body').append(tempInput);
        tempInput.val(text).select();
        document.execCommand('copy');
        tempInput.remove();
    }

    /**
     * Show Notification
     */
    function showNotification(message, type) {
        // Remove existing notifications
        $('.aakaari-notification').remove();

        // Create notification element
        const notification = $('<div>', {
            class: `aakaari-notification ${type}`,
            text: message
        });

        // Add to body
        $('body').append(notification);

        // Show notification
        setTimeout(function() {
            notification.addClass('show');
        }, 10);

        // Hide and remove after 3 seconds
        setTimeout(function() {
            notification.removeClass('show');
            setTimeout(function() {
                notification.remove();
            }, 300);
        }, 3000);
    }

    // Add notification styles if not already added
    if (!$('#aakaari-notification-styles').length) {
        $('<style id="aakaari-notification-styles">')
            .text(`
                .aakaari-notification {
                    position: fixed;
                    top: 2rem;
                    right: 2rem;
                    padding: 1rem 1.5rem;
                    border-radius: 8px;
                    background: white;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                    z-index: 10000;
                    transform: translateX(400px);
                    transition: transform 0.3s ease-out;
                }
                .aakaari-notification.show {
                    transform: translateX(0);
                }
                .aakaari-notification.success {
                    border-left: 4px solid #22c55e;
                }
                .aakaari-notification.error {
                    border-left: 4px solid #ef4444;
                }
            `)
            .appendTo('head');
    }

})(jQuery);
