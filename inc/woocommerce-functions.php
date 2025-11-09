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

    if ($product_id <= 0) {
        wp_send_json_error(array('message' => 'Invalid product ID'));
        return;
    }

    $result = WC()->cart->add_to_cart($product_id, $quantity);

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
