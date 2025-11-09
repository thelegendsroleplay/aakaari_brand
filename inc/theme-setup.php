<?php
/**
 * Theme Setup Functions
 *
 * @package Aakaari_Brand
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Sets up theme defaults and registers support for various WordPress features
 */
function aakaari_theme_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'aakaari-brand'),
        'footer'  => __('Footer Menu', 'aakaari-brand'),
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

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Add WooCommerce support
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'aakaari_theme_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet
 */
function aakaari_content_width() {
    $GLOBALS['content_width'] = apply_filters('aakaari_content_width', 1200);
}
add_action('after_setup_theme', 'aakaari_content_width', 0);

/**
 * Disable WooCommerce default styles (we use custom styles)
 */
add_filter('woocommerce_enqueue_styles', '__return_empty_array');
