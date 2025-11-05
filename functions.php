<?php
/**
 * FashionMen Theme Functions
 *
 * @package FashionMen
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Theme version
define('FASHIONMEN_VERSION', '1.0.0');

/**
 * Theme Setup
 */
function fashionmen_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');

    // Set default thumbnail sizes
    set_post_thumbnail_size(1200, 800, true);
    add_image_size('fashionmen-product-thumb', 400, 500, true);
    add_image_size('fashionmen-product-single', 800, 1000, true);
    add_image_size('fashionmen-hero', 1920, 800, true);

    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'fashionmen'),
        'mobile' => esc_html__('Mobile Menu', 'fashionmen'),
        'footer' => esc_html__('Footer Menu', 'fashionmen'),
    ));

    // Switch default core markup to output valid HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add theme support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // Add support for editor styles
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor-style.css');

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for WooCommerce
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'fashionmen_setup');

/**
 * Set the content width in pixels
 */
function fashionmen_content_width() {
    $GLOBALS['content_width'] = apply_filters('fashionmen_content_width', 1200);
}
add_action('after_setup_theme', 'fashionmen_content_width', 0);

/**
 * Register widget areas
 */
function fashionmen_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Shop Sidebar', 'fashionmen'),
        'id'            => 'sidebar-shop',
        'description'   => esc_html__('Add widgets for shop pages here.', 'fashionmen'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Area 1', 'fashionmen'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Add widgets for footer column 1.', 'fashionmen'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Area 2', 'fashionmen'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Add widgets for footer column 2.', 'fashionmen'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Area 3', 'fashionmen'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Add widgets for footer column 3.', 'fashionmen'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Area 4', 'fashionmen'),
        'id'            => 'footer-4',
        'description'   => esc_html__('Add widgets for footer column 4.', 'fashionmen'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'fashionmen_widgets_init');

/**
 * Enqueue scripts and styles
 */
function fashionmen_scripts() {
    // Enqueue Tailwind CSS (compiled)
    wp_enqueue_style('fashionmen-tailwind', get_template_directory_uri() . '/assets/css/tailwind.css', array(), FASHIONMEN_VERSION);

    // Enqueue custom styles
    wp_enqueue_style('fashionmen-style', get_stylesheet_uri(), array('fashionmen-tailwind'), FASHIONMEN_VERSION);

    // Enqueue custom CSS
    wp_enqueue_style('fashionmen-custom', get_template_directory_uri() . '/assets/css/custom.css', array('fashionmen-style'), FASHIONMEN_VERSION);

    // Enqueue main JavaScript
    wp_enqueue_script('fashionmen-main', get_template_directory_uri() . '/assets/js/main.js', array(), FASHIONMEN_VERSION, true);

    // Enqueue navigation script
    wp_enqueue_script('fashionmen-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), FASHIONMEN_VERSION, true);

    // Localize script for AJAX
    wp_localize_script('fashionmen-main', 'fashionmenAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('fashionmen-nonce')
    ));

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'fashionmen_scripts');

/**
 * WooCommerce Customization
 */

// Remove default WooCommerce styles
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

// Customize WooCommerce products per page
function fashionmen_products_per_page() {
    return 12;
}
add_filter('loop_shop_per_page', 'fashionmen_products_per_page', 20);

// Customize WooCommerce product columns
function fashionmen_product_columns() {
    return 3; // 3 columns on desktop
}
add_filter('loop_shop_columns', 'fashionmen_product_columns');

// Add WooCommerce cart icon to navigation
function fashionmen_cart_icon() {
    if (!class_exists('WooCommerce')) {
        return;
    }

    $cart_count = WC()->cart->get_cart_contents_count();
    ?>
    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-icon">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <?php if ($cart_count > 0) : ?>
            <span class="cart-count"><?php echo esc_html($cart_count); ?></span>
        <?php endif; ?>
    </a>
    <?php
}

// Update cart count via AJAX
function fashionmen_update_cart_count() {
    if (!class_exists('WooCommerce')) {
        wp_send_json_error();
    }

    $cart_count = WC()->cart->get_cart_contents_count();
    wp_send_json_success(array('count' => $cart_count));
}
add_action('wp_ajax_fashionmen_update_cart_count', 'fashionmen_update_cart_count');
add_action('wp_ajax_nopriv_fashionmen_update_cart_count', 'fashionmen_update_cart_count');

/**
 * Include required files
 */
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/template-tags.php';

if (class_exists('WooCommerce')) {
    require get_template_directory() . '/inc/woocommerce.php';
}

/**
 * Custom template tags for this theme
 */
require get_template_directory() . '/inc/customizer.php';
