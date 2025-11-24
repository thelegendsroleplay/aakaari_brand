<?php
/**
 * WooCommerce Functions
 *
 * @package Aakaari_Brand
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if WooCommerce is active
 */
if (!function_exists('is_woocommerce_activated')) {
    function is_woocommerce_activated() {
        return class_exists('WooCommerce');
    }
}

/**
 * WooCommerce specific setup
 */
function aakaari_woocommerce_setup() {
    if (!is_woocommerce_activated()) {
        return;
    }

    // Add custom WooCommerce functions here
}
add_action('after_setup_theme', 'aakaari_woocommerce_setup');

/**
 * Disable default WooCommerce styles (optional - uncomment if you want full control)
 */
// add_filter('woocommerce_enqueue_styles', '__return_empty_array');

/**
 * AJAX Add to Cart
 */
function aakaari_ajax_add_to_cart() {
    check_ajax_referer('aakaari-ajax-nonce', 'nonce');

    if (!is_woocommerce_activated()) {
        wp_send_json_error(array('message' => 'WooCommerce is not active'));
        return;
    }

    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $options = isset($_POST['options']) ? $_POST['options'] : array();

    if ($product_id <= 0) {
        wp_send_json_error(array('message' => 'Invalid product ID'));
        return;
    }

    $product = wc_get_product($product_id);

    if (!$product) {
        wp_send_json_error(array('message' => 'Product not found'));
        return;
    }

    $variation_id = 0;
    $variation = array();

    // Handle variable products
    if ($product->is_type('variable')) {
        if (empty($options)) {
            wp_send_json_error(array('message' => 'Please select product options'));
            return;
        }

        // Build variation attributes array
        foreach ($options as $attribute_name => $attribute_value) {
            // Normalize attribute name to WooCommerce format
            // Convert hyphens back to underscores (sanitize_title converts _ to -)
            $attribute_name = str_replace('-', '_', $attribute_name);

            // If it doesn't start with 'attribute_', add it
            if (strpos($attribute_name, 'attribute_') !== 0) {
                $attribute_key = 'attribute_' . $attribute_name;
            } else {
                $attribute_key = $attribute_name;
            }

            // Sanitize the value to match WooCommerce format
            $variation[$attribute_key] = sanitize_title($attribute_value);
        }

        // Find matching variation ID
        $data_store = WC_Data_Store::load('product');
        $variation_id = $data_store->find_matching_product_variation($product, $variation);

        if (!$variation_id) {
            // Log for debugging
            error_log('Variation matching failed. Product ID: ' . $product_id);
            error_log('Requested attributes: ' . print_r($variation, true));

            wp_send_json_error(array(
                'message' => 'Selected variation not found. Please try different options.',
                'debug' => $variation
            ));
            return;
        }

        // Add variation to cart
        $result = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);
    } else {
        // Add simple product to cart
        $result = WC()->cart->add_to_cart($product_id, $quantity);
    }

    if ($result) {
        wp_send_json_success(array(
            'message' => 'Product added to cart',
            'cart_count' => WC()->cart->get_cart_contents_count()
        ));
    } else {
        wp_send_json_error(array('message' => 'Failed to add product to cart'));
    }
}
add_action('wp_ajax_aakaari_add_to_cart', 'aakaari_ajax_add_to_cart');
add_action('wp_ajax_nopriv_aakaari_add_to_cart', 'aakaari_ajax_add_to_cart');

/**
 * AJAX Get Cart Count
 */
function aakaari_ajax_get_cart_count() {
    check_ajax_referer('aakaari-ajax-nonce', 'nonce');

    if (!is_woocommerce_activated()) {
        wp_send_json_error(array('message' => 'WooCommerce is not active'));
        return;
    }

    wp_send_json_success(array(
        'count' => WC()->cart->get_cart_contents_count()
    ));
}
add_action('wp_ajax_aakaari_get_cart_count', 'aakaari_ajax_get_cart_count');
add_action('wp_ajax_nopriv_aakaari_get_cart_count', 'aakaari_ajax_get_cart_count');

/**
 * AJAX Update Cart Quantity
 */
function aakaari_ajax_update_cart_quantity() {
    check_ajax_referer('aakaari-ajax-nonce', 'nonce');

    if (!is_woocommerce_activated()) {
        wp_send_json_error(array('message' => 'WooCommerce is not active'));
        return;
    }

    $cart_item_key = isset($_POST['cart_item_key']) ? sanitize_text_field($_POST['cart_item_key']) : '';
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;

    if (empty($cart_item_key)) {
        wp_send_json_error(array('message' => 'Invalid cart item'));
        return;
    }

    if ($quantity < 0) {
        wp_send_json_error(array('message' => 'Invalid quantity'));
        return;
    }

    // Update the cart
    if ($quantity == 0) {
        // Remove item if quantity is 0
        WC()->cart->remove_cart_item($cart_item_key);
    } else {
        // Update quantity
        WC()->cart->set_quantity($cart_item_key, $quantity, true);
    }

    wp_send_json_success(array(
        'message' => 'Cart updated',
        'cart_count' => WC()->cart->get_cart_contents_count()
    ));
}
add_action('wp_ajax_aakaari_update_cart_quantity', 'aakaari_ajax_update_cart_quantity');
add_action('wp_ajax_nopriv_aakaari_update_cart_quantity', 'aakaari_ajax_update_cart_quantity');

/**
 * AJAX Remove Cart Item
 */
function aakaari_ajax_remove_cart_item() {
    check_ajax_referer('aakaari-ajax-nonce', 'nonce');

    if (!is_woocommerce_activated()) {
        wp_send_json_error(array('message' => 'WooCommerce is not active'));
        return;
    }

    $cart_item_key = isset($_POST['cart_item_key']) ? sanitize_text_field($_POST['cart_item_key']) : '';

    if (empty($cart_item_key)) {
        wp_send_json_error(array('message' => 'Invalid cart item'));
        return;
    }

    // Remove from cart
    $removed = WC()->cart->remove_cart_item($cart_item_key);

    if ($removed) {
        wp_send_json_success(array(
            'message' => 'Item removed from cart',
            'cart_count' => WC()->cart->get_cart_contents_count()
        ));
    } else {
        wp_send_json_error(array('message' => 'Failed to remove item'));
    }
}
add_action('wp_ajax_aakaari_remove_cart_item', 'aakaari_ajax_remove_cart_item');
add_action('wp_ajax_nopriv_aakaari_remove_cart_item', 'aakaari_ajax_remove_cart_item');

/**
 * AJAX Clear Cart
 */
function aakaari_ajax_clear_cart() {
    check_ajax_referer('aakaari-ajax-nonce', 'nonce');

    if (!is_woocommerce_activated()) {
        wp_send_json_error(array('message' => 'WooCommerce is not active'));
        return;
    }

    // Clear the entire cart
    WC()->cart->empty_cart();

    wp_send_json_success(array(
        'message' => 'Cart cleared',
        'cart_count' => 0
    ));
}
add_action('wp_ajax_aakaari_clear_cart', 'aakaari_ajax_clear_cart');
add_action('wp_ajax_nopriv_aakaari_clear_cart', 'aakaari_ajax_clear_cart');

/**
 * Update cart count via fragments (for AJAX cart)
 */
function aakaari_add_to_cart_fragment($fragments) {
    ob_start();
    ?>
    <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
    <?php
    $fragments['.cart-count'] = ob_get_clean();
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'aakaari_add_to_cart_fragment');

/**
 * AJAX Get Quick View Product Details
 */
function aakaari_ajax_get_quick_view() {
    check_ajax_referer('aakaari-ajax-nonce', 'nonce');

    if (!is_woocommerce_activated()) {
        wp_send_json_error(array('message' => 'WooCommerce is not active'));
        return;
    }

    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

    if ($product_id <= 0) {
        wp_send_json_error(array('message' => 'Invalid product ID'));
        return;
    }

    $product = wc_get_product($product_id);

    if (!$product) {
        wp_send_json_error(array('message' => 'Product not found'));
        return;
    }

    ob_start();
    ?>
    <div class="quick-view-inner">
        <div class="quick-view-images">
            <div class="quick-view-main-image">
                <?php echo $product->get_image('large'); ?>
            </div>
            <?php
            $gallery_images = $product->get_gallery_image_ids();
            if (!empty($gallery_images)) :
            ?>
                <div class="quick-view-thumbnails">
                    <div class="quick-view-thumbnail active">
                        <?php echo $product->get_image('thumbnail'); ?>
                    </div>
                    <?php foreach ($gallery_images as $image_id) : ?>
                        <div class="quick-view-thumbnail">
                            <?php echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="quick-view-details">
            <h2 class="quick-view-title"><?php echo esc_html($product->get_name()); ?></h2>

            <div class="quick-view-price">
                <?php echo $product->get_price_html(); ?>
            </div>

            <?php
            $rating_count = $product->get_rating_count();
            $average_rating = $product->get_average_rating();

            if ($rating_count > 0) :
            ?>
                <div class="quick-view-rating">
                    <div class="product-card-stars">
                        <?php
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $average_rating) {
                                echo '<span class="star">★</span>';
                            } else {
                                echo '<span class="star empty">★</span>';
                            }
                        }
                        ?>
                    </div>
                    <span class="product-card-rating-count">(<?php echo esc_html($rating_count); ?> reviews)</span>
                </div>
            <?php endif; ?>

            <?php if ($product->get_short_description()) : ?>
                <div class="quick-view-description">
                    <?php echo wpautop($product->get_short_description()); ?>
                </div>
            <?php endif; ?>

            <?php if ($product->is_type('variable')) : ?>
                <div class="quick-view-variations">
                    <?php
                    $attributes = $product->get_variation_attributes();
                    foreach ($attributes as $attribute_name => $options) :
                        $attribute_slug = sanitize_title($attribute_name);
                        $is_color_attribute = (strpos($attribute_slug, 'color') !== false || strpos($attribute_slug, 'pa_color') !== false);
                        ?>
                        <div class="quick-view-variation">
                            <label class="quick-view-variation-label"><?php echo wc_attribute_label($attribute_name); ?></label>
                            <?php if ($is_color_attribute) : ?>
                                <div class="quick-view-variation-options color-options">
                                    <?php foreach ($options as $option) :
                                        // Get term object to retrieve color metadata
                                        $term = get_term_by('slug', sanitize_title($option), $attribute_name);
                                        if (!$term) {
                                            $term = get_term_by('name', $option, $attribute_name);
                                        }
                                        $hex_color = '';
                                        if ($term) {
                                            $hex_color = get_term_meta($term->term_id, 'attribute_color', true);
                                        }
                                        // Fallback to color name if no hex color is set
                                        $bg_color = $hex_color ? $hex_color : strtolower($option);
                                    ?>
                                        <button class="quick-view-variation-option color-option"
                                                style="background-color: <?php echo esc_attr($bg_color); ?>; width: 2.5rem; height: 2.5rem; border-radius: 50%; padding: 0;"
                                                data-attribute="<?php echo esc_attr($attribute_name); ?>"
                                                data-value="<?php echo esc_attr($option); ?>"
                                                title="<?php echo esc_attr($option); ?>">
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            <?php else : ?>
                                <div class="quick-view-variation-options">
                                    <?php foreach ($options as $option) :
                                        // Get term object to retrieve proper label
                                        $term = get_term_by('slug', sanitize_title($option), $attribute_name);
                                        if (!$term) {
                                            $term = get_term_by('name', $option, $attribute_name);
                                        }
                                        // Use term name if found, otherwise use the option value
                                        $display_label = $term ? $term->name : $option;
                                    ?>
                                        <button class="quick-view-variation-option" data-attribute="<?php echo esc_attr($attribute_name); ?>" data-value="<?php echo esc_attr($option); ?>">
                                            <?php echo esc_html($display_label); ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php
                    endforeach;
                    ?>
                </div>
            <?php endif; ?>

            <?php if ($product->is_in_stock()) : ?>
                <div class="quick-view-quantity">
                    <label class="quick-view-quantity-label">Quantity:</label>
                    <div class="quick-view-quantity-input">
                        <button class="quick-view-quantity-btn minus">−</button>
                        <span class="quick-view-quantity-value">1</span>
                        <button class="quick-view-quantity-btn plus">+</button>
                    </div>
                </div>

                <div class="quick-view-actions">
                    <button class="quick-view-add-to-cart" data-product-id="<?php echo esc_attr($product_id); ?>">
                        Add to Cart
                    </button>
                    <a href="<?php echo esc_url($product->get_permalink()); ?>" class="quick-view-view-full">
                        View Full Details
                    </a>
                </div>
            <?php else : ?>
                <div class="quick-view-actions">
                    <button class="quick-view-add-to-cart" disabled>
                        Out of Stock
                    </button>
                    <a href="<?php echo esc_url($product->get_permalink()); ?>" class="quick-view-view-full">
                        View Full Details
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php

    $html = ob_get_clean();

    wp_send_json_success(array('html' => $html));
}
add_action('wp_ajax_aakaari_get_quick_view', 'aakaari_ajax_get_quick_view');
add_action('wp_ajax_nopriv_aakaari_get_quick_view', 'aakaari_ajax_get_quick_view');

/**
 * AJAX handler for submitting product reviews
 */
function aakaari_ajax_submit_review() {
    check_ajax_referer('aakaari-ajax-nonce', 'nonce');

    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $comment = isset($_POST['comment']) ? sanitize_textarea_field($_POST['comment']) : '';

    // Validate inputs
    if (!$product_id || !$name || !$email || !$rating || !$comment) {
        wp_send_json_error(array('message' => 'Please fill in all required fields'));
        return;
    }

    if ($rating < 1 || $rating > 5) {
        wp_send_json_error(array('message' => 'Invalid rating value'));
        return;
    }

    if (!is_email($email)) {
        wp_send_json_error(array('message' => 'Invalid email address'));
        return;
    }

    // Check if product exists
    $product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error(array('message' => 'Product not found'));
        return;
    }

    // Prepare comment data
    $comment_data = array(
        'comment_post_ID'      => $product_id,
        'comment_author'       => $name,
        'comment_author_email' => $email,
        'comment_content'      => $comment,
        'comment_type'         => 'review',
        'comment_parent'       => 0,
        'user_id'              => get_current_user_id(),
        'comment_approved'     => 0, // Set to pending for moderation
    );

    // Insert comment
    $comment_id = wp_insert_comment($comment_data);

    if ($comment_id) {
        // Add rating meta
        update_comment_meta($comment_id, 'rating', $rating);

        wp_send_json_success(array('message' => 'Review submitted successfully!'));
    } else {
        wp_send_json_error(array('message' => 'Failed to submit review'));
    }
}
add_action('wp_ajax_aakaari_submit_review', 'aakaari_ajax_submit_review');
add_action('wp_ajax_nopriv_aakaari_submit_review', 'aakaari_ajax_submit_review');

/**
 * AJAX handler to get variation data including images
 */
function aakaari_ajax_get_variation_data() {
    check_ajax_referer('aakaari-ajax-nonce', 'nonce');

    if (!is_woocommerce_activated()) {
        wp_send_json_error(array('message' => 'WooCommerce is not active'));
        return;
    }

    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $attributes = isset($_POST['attributes']) ? $_POST['attributes'] : array();

    if ($product_id <= 0) {
        wp_send_json_error(array('message' => 'Invalid product ID'));
        return;
    }

    $product = wc_get_product($product_id);

    if (!$product || !$product->is_type('variable')) {
        wp_send_json_error(array('message' => 'Product not found or not a variable product'));
        return;
    }

    // Build variation attributes array
    $variation_attributes = array();
    foreach ($attributes as $attribute_name => $attribute_value) {
        // Normalize attribute name
        $attribute_name = str_replace('-', '_', $attribute_name);

        if (strpos($attribute_name, 'attribute_') !== 0) {
            $attribute_key = 'attribute_' . $attribute_name;
        } else {
            $attribute_key = $attribute_name;
        }

        $variation_attributes[$attribute_key] = sanitize_title($attribute_value);
    }

    // Find matching variation
    $data_store = WC_Data_Store::load('product');
    $variation_id = $data_store->find_matching_product_variation($product, $variation_attributes);

    if (!$variation_id) {
        wp_send_json_error(array('message' => 'No matching variation found'));
        return;
    }

    $variation = wc_get_product($variation_id);

    if (!$variation) {
        wp_send_json_error(array('message' => 'Variation not found'));
        return;
    }

    // Get variation image
    $variation_image_id = $variation->get_image_id();
    $main_image_url = '';
    $thumbnail_url = '';

    if ($variation_image_id) {
        $main_image_url = wp_get_attachment_image_url($variation_image_id, 'large');
        $thumbnail_url = wp_get_attachment_image_url($variation_image_id, 'thumbnail');
    }

    // Get variation gallery images
    $gallery_images = array();

    // Check if variation has custom gallery images
    $variation_gallery = get_post_meta($variation_id, '_variation_gallery_images', true);

    if (!empty($variation_gallery)) {
        // Use variation-specific gallery images
        $variation_gallery_ids = explode(',', $variation_gallery);

        foreach ($variation_gallery_ids as $gallery_image_id) {
            $gallery_image_id = trim($gallery_image_id);
            if ($gallery_image_id && is_numeric($gallery_image_id)) {
                $large_url = wp_get_attachment_image_url($gallery_image_id, 'large');
                $thumb_url = wp_get_attachment_image_url($gallery_image_id, 'thumbnail');

                // Only add if both URLs are valid
                if ($large_url && $thumb_url) {
                    $gallery_images[] = array(
                        'large' => $large_url,
                        'thumbnail' => $thumb_url,
                        'id' => $gallery_image_id
                    );
                }
            }
        }
    } else {
        // Fallback to variation main image + product gallery
        // First, add the variation's main image if it exists
        if ($variation_image_id) {
            $gallery_images[] = array(
                'large' => $main_image_url,
                'thumbnail' => $thumbnail_url,
                'id' => $variation_image_id
            );
        }

        // Get product gallery images as fallback/additional images
        $product_gallery_ids = $product->get_gallery_image_ids();
        foreach ($product_gallery_ids as $gallery_image_id) {
            // Skip if this is already the variation image
            if ($gallery_image_id != $variation_image_id) {
                $gallery_images[] = array(
                    'large' => wp_get_attachment_image_url($gallery_image_id, 'large'),
                    'thumbnail' => wp_get_attachment_image_url($gallery_image_id, 'thumbnail'),
                    'id' => $gallery_image_id
                );
            }
        }
    }

    wp_send_json_success(array(
        'variation_id' => $variation_id,
        'image_url' => $main_image_url,
        'thumbnail_url' => $thumbnail_url,
        'gallery_images' => $gallery_images,
        'price_html' => $variation->get_price_html(),
        'stock_html' => $variation->is_in_stock() ? 'In stock' : 'Out of stock',
        'is_in_stock' => $variation->is_in_stock()
    ));
}
add_action('wp_ajax_aakaari_get_variation_data', 'aakaari_ajax_get_variation_data');
add_action('wp_ajax_nopriv_aakaari_get_variation_data', 'aakaari_ajax_get_variation_data');
