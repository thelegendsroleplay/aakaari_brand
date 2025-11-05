<?php
/**
 * WooCommerce Compatibility File
 *
 * @package FashionMen
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * WooCommerce setup function
 */
function fashionmen_woocommerce_setup() {
    add_theme_support('woocommerce', array(
        'thumbnail_image_width' => 400,
        'single_image_width'    => 800,
        'product_grid'          => array(
            'default_rows'    => 3,
            'min_rows'        => 1,
            'default_columns' => 3,
            'min_columns'     => 1,
            'max_columns'     => 6,
        ),
    ));

    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'fashionmen_woocommerce_setup');

/**
 * WooCommerce specific scripts & stylesheets
 */
function fashionmen_woocommerce_scripts() {
    wp_enqueue_style('fashionmen-woocommerce-style', get_template_directory_uri() . '/assets/css/woocommerce.css', array(), FASHIONMEN_VERSION);
    wp_enqueue_script('fashionmen-woocommerce', get_template_directory_uri() . '/assets/js/woocommerce.js', array('jquery'), FASHIONMEN_VERSION, true);
}
add_action('wp_enqueue_scripts', 'fashionmen_woocommerce_scripts');

/**
 * Remove default WooCommerce wrappers
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

/**
 * Add custom WooCommerce wrappers
 */
function fashionmen_wrapper_start() {
    echo '<main id="primary" class="site-main container mx-auto px-4 py-8">';
}
add_action('woocommerce_before_main_content', 'fashionmen_wrapper_start', 10);

function fashionmen_wrapper_end() {
    echo '</main>';
}
add_action('woocommerce_after_main_content', 'fashionmen_wrapper_end', 10);

/**
 * Customize product display
 */
function fashionmen_woocommerce_product_loop_start() {
    echo '<div class="products grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">';
}
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

/**
 * Custom add to cart button
 */
function fashionmen_add_to_cart_button() {
    global $product;

    echo '<a href="' . esc_url($product->add_to_cart_url()) . '"
          data-quantity="1"
          class="button product_type_' . esc_attr($product->get_type()) . ' add_to_cart_button ajax_add_to_cart bg-black text-white px-6 py-3 rounded hover:bg-gray-800 transition-colors"
          data-product_id="' . esc_attr($product->get_id()) . '"
          data-product_sku="' . esc_attr($product->get_sku()) . '"
          aria-label="' . esc_attr($product->add_to_cart_description()) . '"
          rel="nofollow">' . esc_html($product->add_to_cart_text()) . '</a>';
}

/**
 * Customize breadcrumbs
 */
function fashionmen_woocommerce_breadcrumbs() {
    return array(
        'delimiter'   => '<span class="breadcrumb-separator mx-2">/</span>',
        'wrap_before' => '<nav class="woocommerce-breadcrumb breadcrumbs text-sm mb-6" aria-label="breadcrumb">',
        'wrap_after'  => '</nav>',
        'before'      => '<span class="breadcrumb-item">',
        'after'       => '</span>',
        'home'        => _x('Home', 'breadcrumb', 'fashionmen'),
    );
}
add_filter('woocommerce_breadcrumb_defaults', 'fashionmen_woocommerce_breadcrumbs');

/**
 * Customize product image size
 */
function fashionmen_woocommerce_image_dimensions() {
    global $pagenow;

    if (!isset($_GET['activated']) || $pagenow != 'themes.php') {
        return;
    }

    $catalog = array(
        'width'  => '400',
        'height' => '500',
        'crop'   => 1,
    );

    $single = array(
        'width'  => '800',
        'height' => '1000',
        'crop'   => 1,
    );

    $thumbnail = array(
        'width'  => '150',
        'height' => '150',
        'crop'   => 1,
    );

    update_option('shop_catalog_image_size', $catalog);
    update_option('shop_single_image_size', $single);
    update_option('shop_thumbnail_image_size', $thumbnail);
}
add_action('after_switch_theme', 'fashionmen_woocommerce_image_dimensions', 1);

/**
 * Related Products Args
 */
function fashionmen_woocommerce_related_products_args($args) {
    $defaults = array(
        'posts_per_page' => 4,
        'columns'        => 4,
    );

    $args = wp_parse_args($defaults, $args);

    return $args;
}
add_filter('woocommerce_output_related_products_args', 'fashionmen_woocommerce_related_products_args');

/**
 * Remove default sorting dropdown
 */
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

/**
 * Custom product card structure
 */
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);

function fashionmen_product_title() {
    echo '<h3 class="text-lg font-semibold mb-2"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h3>';
}
add_action('woocommerce_shop_loop_item_title', 'fashionmen_product_title', 10);
