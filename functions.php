<?php
/**
 * Aakaari Brand Theme Functions
 *
 * @package Aakaari_Brand
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('AAKAARI_THEME_VERSION', '1.2.9');
define('AAKAARI_THEME_DIR', get_template_directory());
define('AAKAARI_THEME_URI', get_template_directory_uri());

/**
 * Include required files from inc folder
 */
function aakaari_include_files() {
    $includes = array(
        '/inc/theme-setup.php',           // Theme setup and support
        '/inc/enqueue-scripts.php',       // Enqueue CSS and JS files
        '/inc/woocommerce-functions.php', // WooCommerce specific functions
        '/inc/customizer.php',            // Theme customizer settings
        '/inc/header-functions.php',      // Header functions and walkers
        '/inc/shop-functions.php',        // Shop page functions and filters
        '/inc/wc-color-attributes.php',   // WooCommerce color attributes
        '/inc/variation-gallery.php',     // Variation gallery support
        '/inc/wishlist.php',              // Wishlist functionality
        '/inc/live-chat-system.php',      // Live chat system
        '/inc/social-login.php',          // Social login (Google, Facebook)
        '/inc/upi-payment-gateway.php',   // UPI Payment Gateway
        // Add more inc files here as you create them
    );

    foreach ($includes as $file) {
        $filepath = AAKAARI_THEME_DIR . $file;
        if (file_exists($filepath)) {
            require_once $filepath;
        }
    }
}
add_action('after_setup_theme', 'aakaari_include_files', 1);

/**
 * Remove WordPress 2560px image size limit
 * By default, WordPress scales down images larger than 2560px
 * This filter disables that behavior to preserve full-size uploads
 */
add_filter('big_image_size_threshold', '__return_false');

