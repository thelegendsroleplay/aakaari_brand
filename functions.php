<?php
/**
 * Aakaari Brand Theme Functions
 *
 * @package Aakaari_Brand
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Theme constants
define( 'AAKAARI_VERSION', '1.0.0' );
define( 'AAKAARI_THEME_DIR', get_template_directory() );
define( 'AAKAARI_THEME_URI', get_template_directory_uri() );

/**
 * Theme Setup
 */
function aakaari_theme_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails
    add_theme_support( 'post-thumbnails' );

    // Add support for responsive embeds
    add_theme_support( 'responsive-embeds' );

    // Add support for custom logo
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    // Register navigation menus
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'aakaari-brand' ),
        'footer'  => __( 'Footer Menu', 'aakaari-brand' ),
    ) );

    // Add support for HTML5 markup
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'script',
        'style',
    ) );

    // WooCommerce support
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'aakaari_theme_setup' );

/**
 * Enqueue scripts and styles
 */
function aakaari_enqueue_scripts() {
    // Theme stylesheet
    wp_enqueue_style( 'aakaari-style', get_stylesheet_uri(), array(), AAKAARI_VERSION );

    // Layout CSS
    wp_enqueue_style( 'aakaari-layout', AAKAARI_THEME_URI . '/assets/css/layout.css', array(), AAKAARI_VERSION );

    // Header CSS
    wp_enqueue_style( 'aakaari-header', AAKAARI_THEME_URI . '/assets/css/header.css', array(), AAKAARI_VERSION );

    // Footer CSS
    wp_enqueue_style( 'aakaari-footer', AAKAARI_THEME_URI . '/assets/css/footer.css', array(), AAKAARI_VERSION );

    // Home CSS
    wp_enqueue_style( 'aakaari-home', AAKAARI_THEME_URI . '/assets/css/home.css', array(), AAKAARI_VERSION );

    // Layout JS
    wp_enqueue_script( 'aakaari-layout', AAKAARI_THEME_URI . '/assets/js/layout.js', array(), AAKAARI_VERSION, true );

    // Home JS
    if ( is_front_page() ) {
        wp_enqueue_script( 'aakaari-home', AAKAARI_THEME_URI . '/assets/js/home.js', array(), AAKAARI_VERSION, true );
    }

    // Localize script for AJAX
    wp_localize_script( 'aakaari-layout', 'aakaariData', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'aakaari-nonce' ),
        'homeUrl' => home_url( '/' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'aakaari_enqueue_scripts' );

/**
 * Register widget areas
 */
function aakaari_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Sidebar', 'aakaari-brand' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Add widgets here.', 'aakaari-brand' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'aakaari_widgets_init' );

/**
 * WooCommerce Customizations
 */
// Remove default WooCommerce styles
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

// Modify WooCommerce product columns
function aakaari_woocommerce_columns() {
    return 4;
}
add_filter( 'loop_shop_columns', 'aakaari_woocommerce_columns' );

// Modify products per page
function aakaari_products_per_page() {
    return 12;
}
add_filter( 'loop_shop_per_page', 'aakaari_products_per_page' );

/**
 * Include Theme Activator
 */
require_once AAKAARI_THEME_DIR . '/inc/class-theme-activator.php';

/**
 * Run theme activation on theme switch
 */
function aakaari_theme_activation() {
    if ( class_exists( 'Aakaari_Theme_Activator' ) ) {
        $activator = new Aakaari_Theme_Activator();
        $activator->activate();
    }
}
add_action( 'after_switch_theme', 'aakaari_theme_activation' );

/**
 * Custom template tags
 */

/**
 * Display cart count
 */
function aakaari_cart_count() {
    if ( function_exists( 'WC' ) ) {
        $count = WC()->cart->get_cart_contents_count();
        echo '<span class="cart-count">' . esc_html( $count ) . '</span>';
    }
}

/**
 * Display wishlist count (placeholder for future implementation)
 */
function aakaari_wishlist_count() {
    // This can be extended with a wishlist plugin
    echo '<span class="wishlist-count">0</span>';
}

/**
 * Custom breadcrumbs
 */
function aakaari_breadcrumbs() {
    if ( ! is_front_page() ) {
        echo '<nav class="breadcrumbs">';
        echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . __( 'Home', 'aakaari-brand' ) . '</a>';

        if ( is_category() || is_single() ) {
            echo ' &raquo; ';
            the_category( ', ' );
            if ( is_single() ) {
                echo ' &raquo; ';
                the_title();
            }
        } elseif ( is_page() ) {
            echo ' &raquo; ';
            the_title();
        } elseif ( is_search() ) {
            echo ' &raquo; ' . __( 'Search Results', 'aakaari-brand' );
        } elseif ( is_404() ) {
            echo ' &raquo; ' . __( '404', 'aakaari-brand' );
        }

        echo '</nav>';
    }
}

/**
 * AJAX handlers for cart updates
 */
function aakaari_update_cart_count() {
    check_ajax_referer( 'aakaari-nonce', 'nonce' );

    if ( function_exists( 'WC' ) ) {
        $count = WC()->cart->get_cart_contents_count();
        wp_send_json_success( array( 'count' => $count ) );
    }

    wp_send_json_error();
}
add_action( 'wp_ajax_aakaari_update_cart_count', 'aakaari_update_cart_count' );
add_action( 'wp_ajax_nopriv_aakaari_update_cart_count', 'aakaari_update_cart_count' );

/**
 * Customize excerpt length
 */
function aakaari_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'aakaari_excerpt_length' );

/**
 * Customize excerpt more
 */
function aakaari_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'aakaari_excerpt_more' );
