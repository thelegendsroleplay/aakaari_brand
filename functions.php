<?php
/**
 * FashionMen Theme Functions
 *
 * @package FashionMen
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Theme version
define('FASHIONMEN_VERSION', '2.0.0');

/**
 * Theme Setup
 */
function fashionmen_theme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
    add_theme_support('custom-logo');
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'fashionmen'),
        'footer' => __('Footer Menu', 'fashionmen'),
    ));

    // Set image sizes
    add_image_size('fashionmen-product-thumb', 400, 500, true);
    add_image_size('fashionmen-product-large', 800, 1000, true);
    add_image_size('fashionmen-category', 600, 600, true);
}
add_action('after_setup_theme', 'fashionmen_theme_setup');

/**
 * Enqueue Scripts and Styles
 */
function fashionmen_enqueue_assets() {
    // Main stylesheet
    wp_enqueue_style('fashionmen-style', get_stylesheet_uri(), array(), FASHIONMEN_VERSION);

    // Page-specific assets
    if (is_page_template('homepage.php') || is_front_page()) {
        // Home page
        wp_enqueue_style('fashionmen-home', get_template_directory_uri() . '/assets/css/home.css', array('fashionmen-style'), FASHIONMEN_VERSION);
        wp_enqueue_script('fashionmen-home', get_template_directory_uri() . '/assets/js/home.js', array(), FASHIONMEN_VERSION, true);
    }

    if (is_shop() || is_product_category() || is_product_tag()) {
        // Shop page
        wp_enqueue_style('fashionmen-shop', get_template_directory_uri() . '/assets/css/shop.css', array('fashionmen-style'), FASHIONMEN_VERSION);
        wp_enqueue_script('fashionmen-shop', get_template_directory_uri() . '/assets/js/shop.js', array(), FASHIONMEN_VERSION, true);
    }

    if (is_product()) {
        // Product detail page
        wp_enqueue_style('fashionmen-product', get_template_directory_uri() . '/assets/css/product.css', array('fashionmen-style'), FASHIONMEN_VERSION);
        wp_enqueue_script('fashionmen-product', get_template_directory_uri() . '/assets/js/product.js', array(), FASHIONMEN_VERSION, true);
    }

    if (is_cart()) {
        // Cart page
        wp_enqueue_style('fashionmen-cart', get_template_directory_uri() . '/assets/css/cart.css', array('fashionmen-style'), FASHIONMEN_VERSION);
        wp_enqueue_script('fashionmen-cart', get_template_directory_uri() . '/assets/js/cart.js', array(), FASHIONMEN_VERSION, true);
    }

    if (is_checkout()) {
        // Checkout page
        wp_enqueue_style('fashionmen-checkout', get_template_directory_uri() . '/assets/css/checkout.css', array('fashionmen-style'), FASHIONMEN_VERSION);
        wp_enqueue_script('fashionmen-checkout', get_template_directory_uri() . '/assets/js/checkout.js', array(), FASHIONMEN_VERSION, true);
    }

    if (is_page_template('page-login.php') || is_account_page()) {
        // Auth & User Dashboard
        wp_enqueue_style('fashionmen-auth', get_template_directory_uri() . '/assets/css/auth.css', array('fashionmen-style'), FASHIONMEN_VERSION);
        wp_enqueue_style('fashionmen-user-dashboard', get_template_directory_uri() . '/assets/css/user-dashboard.css', array('fashionmen-style'), FASHIONMEN_VERSION);
        wp_enqueue_script('fashionmen-auth', get_template_directory_uri() . '/assets/js/auth.js', array(), FASHIONMEN_VERSION, true);
        wp_enqueue_script('fashionmen-user-dashboard', get_template_directory_uri() . '/assets/js/user-dashboard.js', array(), FASHIONMEN_VERSION, true);
    }

    if (is_search()) {
        // Search page
        wp_enqueue_style('fashionmen-search', get_template_directory_uri() . '/assets/css/search.css', array('fashionmen-style'), FASHIONMEN_VERSION);
        wp_enqueue_script('fashionmen-search', get_template_directory_uri() . '/assets/js/search.js', array('jquery'), FASHIONMEN_VERSION, true);
        wp_localize_script('fashionmen-search', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aakaari-search-nonce')
        ));
    }

    if (is_page_template('page-wishlist.php')) {
        // Wishlist page
        wp_enqueue_style('fashionmen-wishlist', get_template_directory_uri() . '/assets/css/wishlist.css', array('fashionmen-style'), FASHIONMEN_VERSION);
        wp_enqueue_script('fashionmen-wishlist', get_template_directory_uri() . '/assets/js/wishlist.js', array('jquery'), FASHIONMEN_VERSION, true);
        wp_localize_script('fashionmen-wishlist', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aakaari-wishlist-nonce')
        ));
    }

    if (is_page_template('page-about.php') || is_page_template('page-contact.php') || is_page_template('page-faq.php')) {
        // Static pages
        wp_enqueue_style('fashionmen-static', get_template_directory_uri() . '/assets/css/static.css', array('fashionmen-style'), FASHIONMEN_VERSION);
        wp_enqueue_script('fashionmen-static', get_template_directory_uri() . '/assets/js/static.js', array(), FASHIONMEN_VERSION, true);
    }

    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Global AJAX support for wishlist functionality (used across all pages)
    wp_localize_script('fashionmen-style', 'aakaari_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'search_nonce' => wp_create_nonce('aakaari-search-nonce'),
        'wishlist_nonce' => wp_create_nonce('aakaari-wishlist-nonce')
    ));
}
add_action('wp_enqueue_scripts', 'fashionmen_enqueue_assets');

/**
 * Register Widget Areas
 */
function fashionmen_widgets_init() {
    register_sidebar(array(
        'name'          => __('Shop Sidebar', 'fashionmen'),
        'id'            => 'shop-sidebar',
        'description'   => __('Appears on shop and product pages', 'fashionmen'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widget Area', 'fashionmen'),
        'id'            => 'footer-widgets',
        'description'   => __('Appears in the footer', 'fashionmen'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'fashionmen_widgets_init');

/**
 * Customizer Options
 */
function fashionmen_customize_register($wp_customize) {
    // Hero Section
    $wp_customize->add_section('fashionmen_hero', array(
        'title' => __('Homepage Hero', 'fashionmen'),
        'priority' => 30,
    ));

    $wp_customize->add_setting('fashionmen_hero_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'fashionmen_hero_image', array(
        'label' => __('Hero Background Image', 'fashionmen'),
        'section' => 'fashionmen_hero',
        'settings' => 'fashionmen_hero_image',
    )));

    $wp_customize->add_setting('fashionmen_hero_title', array(
        'default' => 'ELEVATE YOUR STYLE',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('fashionmen_hero_title', array(
        'label' => __('Hero Title', 'fashionmen'),
        'section' => 'fashionmen_hero',
        'type' => 'text',
    ));

    $wp_customize->add_setting('fashionmen_hero_subtitle', array(
        'default' => 'Premium men\'s fashion crafted for the modern gentleman',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('fashionmen_hero_subtitle', array(
        'label' => __('Hero Subtitle', 'fashionmen'),
        'section' => 'fashionmen_hero',
        'type' => 'textarea',
    ));
}
add_action('customize_register', 'fashionmen_customize_register');

/**
 * WooCommerce Customizations
 */

// Change number of products per page
add_filter('loop_shop_per_page', function() {
    return 12;
});

// Remove WooCommerce default styles (we'll use our own)
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

/**
 * Include Page Functions
 */
// Home page functions
if (file_exists(get_template_directory() . '/inc/home.php')) {
    require_once get_template_directory() . '/inc/home.php';
}

// Shop page functions
if (file_exists(get_template_directory() . '/inc/shop.php')) {
    require_once get_template_directory() . '/inc/shop.php';
}

// Product page functions
if (file_exists(get_template_directory() . '/inc/product.php')) {
    require_once get_template_directory() . '/inc/product.php';
}

// Cart page functions
if (file_exists(get_template_directory() . '/inc/cart.php')) {
    require_once get_template_directory() . '/inc/cart.php';
}

// Checkout page functions
if (file_exists(get_template_directory() . '/inc/checkout.php')) {
    require_once get_template_directory() . '/inc/checkout.php';
}

// Auth functions
if (file_exists(get_template_directory() . '/inc/auth.php')) {
    require_once get_template_directory() . '/inc/auth.php';
}

// User dashboard functions
if (file_exists(get_template_directory() . '/inc/user-dashboard.php')) {
    require_once get_template_directory() . '/inc/user-dashboard.php';
}

// Search functions
if (file_exists(get_template_directory() . '/inc/search.php')) {
    require_once get_template_directory() . '/inc/search.php';
}

// Wishlist functions
if (file_exists(get_template_directory() . '/inc/wishlist.php')) {
    require_once get_template_directory() . '/inc/wishlist.php';
}

// Static pages functions
if (file_exists(get_template_directory() . '/inc/static-pages.php')) {
    require_once get_template_directory() . '/inc/static-pages.php';
}
