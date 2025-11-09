<?php
/**
 * Enqueue Scripts and Styles
 *
 * @package Aakaari_Brand
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Dequeue conflicting WordPress/WooCommerce styles
 */
function aakaari_dequeue_styles() {
    // Dequeue WordPress default block styles that might conflict
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('global-styles');
}
add_action('wp_enqueue_scripts', 'aakaari_dequeue_styles', 100);

/**
 * Enqueue theme styles
 */
function aakaari_enqueue_styles() {
    // Custom CSS file - load first (no dependencies)
    wp_enqueue_style(
        'aakaari-main',
        AAKAARI_THEME_URI . '/assets/css/main.css',
        array(),
        AAKAARI_THEME_VERSION,
        'all'
    );

    // Header styles - completely independent, load with high priority
    wp_enqueue_style(
        'aakaari-header',
        AAKAARI_THEME_URI . '/assets/css/header.css',
        array(),
        AAKAARI_THEME_VERSION,
        'all'
    );

    // Footer styles - completely independent, load with high priority
    wp_enqueue_style(
        'aakaari-footer',
        AAKAARI_THEME_URI . '/assets/css/footer.css',
        array(),
        AAKAARI_THEME_VERSION,
        'all'
    );

    // Main theme stylesheet - load last to allow overrides
    wp_enqueue_style(
        'aakaari-style',
        get_stylesheet_uri(),
        array('aakaari-main', 'aakaari-header', 'aakaari-footer'),
        AAKAARI_THEME_VERSION,
        'all'
    );

    // Home page styles (also needed for quick view modal and product cards on single product page)
    if (is_page_template('template-home.php') || is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_product()) {
        wp_enqueue_style(
            'aakaari-home',
            AAKAARI_THEME_URI . '/assets/css/home.css',
            array('aakaari-main'),
            AAKAARI_THEME_VERSION
        );
    }

    // Shop page styles
    if (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy()) {
        wp_enqueue_style(
            'aakaari-shop',
            AAKAARI_THEME_URI . '/assets/css/shop.css',
            array('aakaari-main'),
            AAKAARI_THEME_VERSION
        );
    }

    // Single product page styles
    if (is_product()) {
        wp_enqueue_style(
            'aakaari-single-product',
            AAKAARI_THEME_URI . '/assets/css/single-product.css',
            array('aakaari-main'),
            AAKAARI_THEME_VERSION
        );
    }

    // Cart page styles
    if (is_cart()) {
        wp_enqueue_style(
            'aakaari-cart',
            AAKAARI_THEME_URI . '/assets/css/cart.css',
            array('aakaari-main'),
            AAKAARI_THEME_VERSION
        );
    }

    // Checkout page styles
    if (is_checkout()) {
        wp_enqueue_style(
            'aakaari-checkout',
            AAKAARI_THEME_URI . '/assets/css/checkout.css',
            array('aakaari-main'),
            AAKAARI_THEME_VERSION
        );
    }

    // My Account page styles
    if (is_account_page()) {
        wp_enqueue_style(
            'aakaari-account',
            AAKAARI_THEME_URI . '/assets/css/account.css',
            array(),
            AAKAARI_THEME_VERSION,
            'all'
        );
    }

    // Live chat styles - load globally
    wp_enqueue_style(
        'aakaari-live-chat',
        AAKAARI_THEME_URI . '/assets/css/live-chat.css',
        array(),
        AAKAARI_THEME_VERSION,
        'all'
    );
}
add_action('wp_enqueue_scripts', 'aakaari_enqueue_styles', 20); // Priority 20 to load after default WordPress/WooCommerce styles

/**
 * Enqueue theme scripts
 */
function aakaari_enqueue_scripts() {
    // Main JavaScript file
    wp_enqueue_script(
        'aakaari-main',
        AAKAARI_THEME_URI . '/assets/js/main.js',
        array('jquery'),
        AAKAARI_THEME_VERSION,
        true
    );

    // Header scripts
    wp_enqueue_script(
        'aakaari-header',
        AAKAARI_THEME_URI . '/assets/js/header.js',
        array('jquery'),
        AAKAARI_THEME_VERSION,
        true
    );

    // Localize script for AJAX (used by both header and home scripts)
    wp_localize_script('aakaari-header', 'aakaariAjax', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('aakaari-ajax-nonce'),
    ));

    // Home page scripts (also needed for quick view and add to cart on shop pages and product cards on single product page)
    if (is_page_template('template-home.php') || is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_product()) {
        wp_enqueue_script(
            'aakaari-home',
            AAKAARI_THEME_URI . '/assets/js/home.js',
            array('jquery'),
            AAKAARI_THEME_VERSION,
            true
        );

        // Localize script for AJAX (home page needs it too)
        wp_localize_script('aakaari-home', 'aakaariAjax', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('aakaari-ajax-nonce'),
        ));
    }

    // Shop page scripts
    if (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy()) {
        wp_enqueue_script(
            'aakaari-shop',
            AAKAARI_THEME_URI . '/assets/js/shop.js',
            array('jquery', 'aakaari-home'),
            AAKAARI_THEME_VERSION,
            true
        );
    }

    // Single product page scripts
    if (is_product()) {
        wp_enqueue_script(
            'aakaari-single-product',
            AAKAARI_THEME_URI . '/assets/js/single-product.js',
            array('jquery'),
            AAKAARI_THEME_VERSION,
            true
        );

        // Localize script for AJAX (single product needs it for add to cart)
        wp_localize_script('aakaari-single-product', 'aakaariAjax', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('aakaari-ajax-nonce'),
            'checkoutUrl' => wc_get_checkout_url(),
        ));
    }

    // Cart page scripts
    if (is_cart()) {
        wp_enqueue_script(
            'aakaari-cart',
            AAKAARI_THEME_URI . '/assets/js/cart.js',
            array('jquery'),
            AAKAARI_THEME_VERSION,
            true
        );

        // Localize script for AJAX (cart needs it for remove/clear actions)
        wp_localize_script('aakaari-cart', 'aakaariAjax', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('aakaari-ajax-nonce'),
        ));
    }

    // Checkout page scripts
    if (is_checkout()) {
        wp_enqueue_script(
            'aakaari-checkout',
            AAKAARI_THEME_URI . '/assets/js/checkout.js',
            array('jquery'),
            AAKAARI_THEME_VERSION,
            true
        );

        // Localize script for AJAX (checkout needs WooCommerce params)
        wp_localize_script('aakaari-checkout', 'aakaariAjax', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('aakaari-ajax-nonce'),
        ));
    }

    // Live chat script - load globally
    wp_enqueue_script(
        'aakaari-live-chat',
        AAKAARI_THEME_URI . '/assets/js/live-chat.js',
        array('jquery'),
        AAKAARI_THEME_VERSION,
        true
    );

    // Localize script for AJAX (live chat needs it)
    wp_localize_script('aakaari-live-chat', 'liveChatAjax', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('live-chat-nonce'),
    ));

    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'aakaari_enqueue_scripts');
