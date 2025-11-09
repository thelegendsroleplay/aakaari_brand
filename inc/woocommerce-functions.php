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
