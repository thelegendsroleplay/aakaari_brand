<?php
/**
 * Aakaari Brand Theme Functions
 *
 * @package Aakaari_Brand
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Theme Setup
 */
function aakaari_brand_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support( 'post-thumbnails' );

    // Set default thumbnail size
    set_post_thumbnail_size( 300, 300, true );

    // Add additional image sizes
    add_image_size( 'aakaari-featured', 800, 600, true );
    add_image_size( 'aakaari-large', 1200, 800, true );

    // Register navigation menus
    register_nav_menus( array(
        'primary' => esc_html__( 'Primary Menu', 'aakaari-brand' ),
        'footer'  => esc_html__( 'Footer Menu', 'aakaari-brand' ),
    ) );

    // Switch default core markup for search form, comment form, and comments
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    // Add theme support for selective refresh for widgets
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Add support for custom logo
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    // Add support for custom background
    add_theme_support( 'custom-background', array(
        'default-color' => 'ffffff',
    ) );

    // Add support for editor styles
    add_theme_support( 'editor-styles' );
    add_editor_style();

    // Add support for responsive embeds
    add_theme_support( 'responsive-embeds' );
}
add_action( 'after_setup_theme', 'aakaari_brand_setup' );

/**
 * WooCommerce Support
 */
function aakaari_brand_woocommerce_setup() {
    // Declare WooCommerce support
    add_theme_support( 'woocommerce' );

    // Enable WooCommerce product gallery features
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'aakaari_brand_woocommerce_setup' );

/**
 * Set content width
 */
function aakaari_brand_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'aakaari_brand_content_width', 1200 );
}
add_action( 'after_setup_theme', 'aakaari_brand_content_width', 0 );

/**
 * Register Widget Areas
 */
function aakaari_brand_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'aakaari-brand' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'aakaari-brand' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widget Area 1', 'aakaari-brand' ),
        'id'            => 'footer-1',
        'description'   => esc_html__( 'Add widgets here to appear in footer.', 'aakaari-brand' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widget Area 2', 'aakaari-brand' ),
        'id'            => 'footer-2',
        'description'   => esc_html__( 'Add widgets here to appear in footer.', 'aakaari-brand' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widget Area 3', 'aakaari-brand' ),
        'id'            => 'footer-3',
        'description'   => esc_html__( 'Add widgets here to appear in footer.', 'aakaari-brand' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );

    // Shop Sidebar
    if ( class_exists( 'WooCommerce' ) ) {
        register_sidebar( array(
            'name'          => esc_html__( 'Shop Sidebar', 'aakaari-brand' ),
            'id'            => 'shop-sidebar',
            'description'   => esc_html__( 'Add widgets here to appear in shop pages.', 'aakaari-brand' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ) );
    }
}
add_action( 'widgets_init', 'aakaari_brand_widgets_init' );

/**
 * Enqueue Scripts and Styles
 */
function aakaari_brand_scripts() {
    // Main stylesheet
    wp_enqueue_style( 'aakaari-brand-style', get_stylesheet_uri(), array(), '1.0.0' );

    // Layout stylesheet (header, footer, mobile nav)
    wp_enqueue_style( 'aakaari-brand-layout', get_template_directory_uri() . '/assets/css/layout.css', array( 'aakaari-brand-style' ), '1.0.0' );

    // Custom JavaScript
    wp_enqueue_script( 'aakaari-brand-script', get_template_directory_uri() . '/js/main.js', array( 'jquery' ), '1.0.0', true );

    // Layout JavaScript (header, footer, mobile nav)
    wp_enqueue_script( 'aakaari-brand-layout', get_template_directory_uri() . '/assets/js/layout.js', array( 'jquery' ), '1.0.0', true );

    // Comment reply script
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'aakaari_brand_scripts' );

/**
 * Custom excerpt length
 */
function aakaari_brand_excerpt_length( $length ) {
    return 30;
}
add_filter( 'excerpt_length', 'aakaari_brand_excerpt_length', 999 );

/**
 * Custom excerpt more text
 */
function aakaari_brand_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'aakaari_brand_excerpt_more' );

/**
 * WooCommerce: Remove default wrapper
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * WooCommerce: Add custom wrapper
 */
function aakaari_brand_woocommerce_wrapper_start() {
    echo '<div id="primary" class="content-area">';
    echo '<main id="main" class="site-main">';
}
add_action( 'woocommerce_before_main_content', 'aakaari_brand_woocommerce_wrapper_start', 10 );

function aakaari_brand_woocommerce_wrapper_end() {
    echo '</main>';
    echo '</div>';
}
add_action( 'woocommerce_after_main_content', 'aakaari_brand_woocommerce_wrapper_end', 10 );

/**
 * WooCommerce: Change number of products per row
 */
function aakaari_brand_woocommerce_loop_columns() {
    return 4; // 4 products per row
}
add_filter( 'loop_shop_columns', 'aakaari_brand_woocommerce_loop_columns' );

/**
 * WooCommerce: Change number of products per page
 */
function aakaari_brand_woocommerce_products_per_page() {
    return 12;
}
add_filter( 'loop_shop_per_page', 'aakaari_brand_woocommerce_products_per_page', 20 );

/**
 * Add cart icon to menu
 */
function aakaari_brand_add_cart_to_menu( $items, $args ) {
    if ( class_exists( 'WooCommerce' ) && $args->theme_location == 'primary' ) {
        $cart_count = WC()->cart->get_cart_contents_count();
        $cart_url = wc_get_cart_url();

        $items .= '<li class="menu-item menu-item-cart">';
        $items .= '<a href="' . esc_url( $cart_url ) . '">';
        $items .= '<span class="cart-icon">🛒</span>';
        if ( $cart_count > 0 ) {
            $items .= '<span class="cart-count">' . esc_html( $cart_count ) . '</span>';
        }
        $items .= '</a>';
        $items .= '</li>';
    }
    return $items;
}
add_filter( 'wp_nav_menu_items', 'aakaari_brand_add_cart_to_menu', 10, 2 );

/**
 * Update cart count via AJAX
 */
function aakaari_brand_update_cart_count() {
    if ( class_exists( 'WooCommerce' ) ) {
        echo WC()->cart->get_cart_contents_count();
    }
    wp_die();
}
add_action( 'wp_ajax_update_cart_count', 'aakaari_brand_update_cart_count' );
add_action( 'wp_ajax_nopriv_update_cart_count', 'aakaari_brand_update_cart_count' );

/**
 * Custom template tags for this theme
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Homepage functions
 */
require get_template_directory() . '/inc/homepage.php';

/**
 * Enqueue homepage specific styles and scripts
 */
function aakaari_brand_homepage_scripts() {
    // Only load on front page
    if ( is_front_page() ) {
        // Homepage CSS
        wp_enqueue_style(
            'aakaari-brand-homepage',
            get_template_directory_uri() . '/assets/css/homepage.css',
            array( 'aakaari-brand-style' ),
            '1.0.0'
        );

        // Homepage JS
        wp_enqueue_script(
            'aakaari-brand-homepage',
            get_template_directory_uri() . '/assets/js/homepage.js',
            array( 'jquery' ),
            '1.0.0',
            true
        );

        // Localize script for AJAX
        wp_localize_script( 'aakaari-brand-homepage', 'aakaariBrandHomepage', array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'aakaari-brand-homepage' ),
        ) );
    }
}
add_action( 'wp_enqueue_scripts', 'aakaari_brand_homepage_scripts' );
