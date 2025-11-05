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
define('FASHIONMEN_VERSION', '1.0.1');

/**
 * Theme Setup
 */
function fashionmen_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');

    // Add theme support for WooCommerce
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'fashionmen'),
        'footer' => esc_html__('Footer Menu', 'fashionmen'),
    ));

    // Add support for HTML5 markup
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));
}
add_action('after_setup_theme', 'fashionmen_setup');

/**
 * Enqueue Scripts and Styles
 */
function fashionmen_enqueue_assets() {
    // Enqueue main stylesheet (global base styles)
    wp_enqueue_style('fashionmen-style', get_stylesheet_uri(), array(), FASHIONMEN_VERSION);

    // Check if we're on a page using the Homepage template
    if (is_page_template('homepage.php')) {
        // Enqueue homepage CSS (all custom styles, no Tailwind)
        wp_enqueue_style(
            'fashionmen-homepage',
            get_template_directory_uri() . '/assets/css/homepage.css',
            array('fashionmen-style'),
            FASHIONMEN_VERSION
        );

        // Enqueue homepage JavaScript
        wp_enqueue_script(
            'fashionmen-homepage',
            get_template_directory_uri() . '/assets/js/homepage.js',
            array(),
            FASHIONMEN_VERSION,
            true
        );
    }

    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'fashionmen_enqueue_assets');

/**
 * Add Customizer Options for Homepage
 */
function fashionmen_customize_register($wp_customize) {
    // Add Homepage Section
    $wp_customize->add_section('fashionmen_homepage', array(
        'title'    => __('Homepage Settings', 'fashionmen'),
        'priority' => 30,
    ));

    // Hero Image
    $wp_customize->add_setting('fashionmen_hero_image', array(
        'default'           => 'https://images.unsplash.com/photo-1641736755184-67380b9a002c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwZmFzaGlvbiUyMG1vZGVsfGVufDF8fHx8MTc2MjIzODI1Nnww&ixlib=rb-4.1.0&q=80&w=1080',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'fashionmen_hero_image', array(
        'label'    => __('Hero Image', 'fashionmen'),
        'section'  => 'fashionmen_homepage',
        'settings' => 'fashionmen_hero_image',
    )));

    // Hero Title
    $wp_customize->add_setting('fashionmen_hero_title', array(
        'default'           => 'ELEVATE YOUR STYLE',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('fashionmen_hero_title', array(
        'label'    => __('Hero Title', 'fashionmen'),
        'section'  => 'fashionmen_homepage',
        'type'     => 'text',
    ));

    // Hero Subtitle
    $wp_customize->add_setting('fashionmen_hero_subtitle', array(
        'default'           => 'Premium men\'s fashion crafted for the modern gentleman',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('fashionmen_hero_subtitle', array(
        'label'    => __('Hero Subtitle', 'fashionmen'),
        'section'  => 'fashionmen_homepage',
        'type'     => 'textarea',
    ));

    // Categories Title
    $wp_customize->add_setting('fashionmen_categories_title', array(
        'default'           => 'Shop by Category',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('fashionmen_categories_title', array(
        'label'    => __('Categories Section Title', 'fashionmen'),
        'section'  => 'fashionmen_homepage',
        'type'     => 'text',
    ));

    // Featured Products Title
    $wp_customize->add_setting('fashionmen_featured_title', array(
        'default'           => 'Featured Collection',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('fashionmen_featured_title', array(
        'label'    => __('Featured Products Title', 'fashionmen'),
        'section'  => 'fashionmen_homepage',
        'type'     => 'text',
    ));

    // Featured Products Subtitle
    $wp_customize->add_setting('fashionmen_featured_subtitle', array(
        'default'           => 'Discover our handpicked selection of premium pieces for the modern gentleman',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('fashionmen_featured_subtitle', array(
        'label'    => __('Featured Products Subtitle', 'fashionmen'),
        'section'  => 'fashionmen_homepage',
        'type'     => 'textarea',
    ));
}
add_action('customize_register', 'fashionmen_customize_register');

/**
 * Register Widget Areas
 */
function fashionmen_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Footer Area', 'fashionmen'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Add widgets for footer.', 'fashionmen'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'fashionmen_widgets_init');
