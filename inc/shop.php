<?php
/**
 * Shop Functions
 *
 * Handles shop page filtering, sorting, and AJAX operations
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue shop scripts and styles
 */
function aakaari_enqueue_shop_assets() {
    if (is_shop() || is_product_category() || is_product_tag()) {
        wp_enqueue_style('aakaari-shop', get_template_directory_uri() . '/assets/css/shop.css', array(), '1.0.0');
        wp_enqueue_script('aakaari-shop', get_template_directory_uri() . '/assets/js/shop.js', array('jquery'), '1.0.0', true);

        // Localize script with AJAX URL and nonces
        wp_localize_script('aakaari-shop', 'woocommerce_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'filter_nonce' => wp_create_nonce('filter_products'),
            'sort_nonce' => wp_create_nonce('sort_products'),
            'wishlist_nonce' => wp_create_nonce('add_to_wishlist'),
            'quickview_nonce' => wp_create_nonce('quick_view_product')
        ));
    }
}
add_action('wp_enqueue_scripts', 'aakaari_enqueue_shop_assets');

/**
 * AJAX: Filter products
 */
function aakaari_filter_products() {
    check_ajax_referer('filter_products', 'nonce');

    $filters = isset($_POST['filters']) ? $_POST['filters'] : array();

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    );

    // Category filter
    if (!empty($filters['categories'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => $filters['categories'],
        );
    }

    // Price filter
    if (isset($filters['priceMin']) || isset($filters['priceMax'])) {
        $args['meta_query'][] = array(
            'key' => '_price',
            'value' => array($filters['priceMin'], $filters['priceMax']),
            'type' => 'NUMERIC',
            'compare' => 'BETWEEN',
        );
    }

    // Color filter (custom attribute)
    if (!empty($filters['colors'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'pa_color',
            'field' => 'slug',
            'terms' => array_map('sanitize_title', $filters['colors']),
        );
    }

    // Size filter (custom attribute)
    if (!empty($filters['sizes'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'pa_size',
            'field' => 'slug',
            'terms' => array_map('sanitize_title', $filters['sizes']),
        );
    }

    // Customizable filter
    if (!empty($filters['customizable'])) {
        $args['meta_query'][] = array(
            'key' => '_is_customizable',
            'value' => '1',
            'compare' => '=',
        );
    }

    // Set relation for tax_query if multiple taxonomies
    if (isset($args['tax_query']) && count($args['tax_query']) > 1) {
        $args['tax_query']['relation'] = 'AND';
    }

    // Set relation for meta_query if multiple meta queries
    if (isset($args['meta_query']) && count($args['meta_query']) > 1) {
        $args['meta_query']['relation'] = 'AND';
    }

    $query = new WP_Query($args);

    ob_start();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }
    } else {
        echo '<div class="shop-no-products">';
        echo '<h3>No products found</h3>';
        echo '<p>Try adjusting your filters to find what you\'re looking for.</p>';
        echo '</div>';
    }

    $html = ob_get_clean();

    wp_reset_postdata();

    wp_send_json_success(array(
        'html' => $html,
        'count' => $query->found_posts,
    ));
}
add_action('wp_ajax_filter_products', 'aakaari_filter_products');
add_action('wp_ajax_nopriv_filter_products', 'aakaari_filter_products');

/**
 * AJAX: Sort products
 */
function aakaari_sort_products() {
    check_ajax_referer('sort_products', 'nonce');

    $sort_by = isset($_POST['sort_by']) ? sanitize_text_field($_POST['sort_by']) : 'featured';
    $filters = isset($_POST['filters']) ? $_POST['filters'] : array();

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    );

    // Apply filters from the previous filter state
    // (Same as filter_products function)

    // Apply sorting
    switch ($sort_by) {
        case 'price-low':
            $args['meta_key'] = '_price';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'ASC';
            break;

        case 'price-high':
            $args['meta_key'] = '_price';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;

        case 'newest':
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
            break;

        case 'rating':
            $args['meta_key'] = '_wc_average_rating';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;

        case 'featured':
        default:
            $args['meta_key'] = '_featured';
            $args['orderby'] = 'meta_value';
            $args['order'] = 'DESC';
            break;
    }

    $query = new WP_Query($args);

    ob_start();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }
    }

    $html = ob_get_clean();

    wp_reset_postdata();

    wp_send_json_success(array(
        'html' => $html,
    ));
}
add_action('wp_ajax_sort_products', 'aakaari_sort_products');
add_action('wp_ajax_nopriv_sort_products', 'aakaari_sort_products');

/**
 * AJAX: Add to wishlist
 */
function aakaari_add_to_wishlist() {
    check_ajax_referer('add_to_wishlist', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Please log in to add items to your wishlist.'));
    }

    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $user_id = get_current_user_id();

    if ($product_id) {
        $wishlist = get_user_meta($user_id, '_wishlist', true);
        if (!is_array($wishlist)) {
            $wishlist = array();
        }

        if (!in_array($product_id, $wishlist)) {
            $wishlist[] = $product_id;
            update_user_meta($user_id, '_wishlist', $wishlist);
            wp_send_json_success(array('message' => 'Added to wishlist'));
        } else {
            wp_send_json_error(array('message' => 'Already in wishlist'));
        }
    }

    wp_send_json_error(array('message' => 'Invalid product'));
}
add_action('wp_ajax_add_to_wishlist', 'aakaari_add_to_wishlist');

/**
 * AJAX: Quick view product
 */
function aakaari_quick_view_product() {
    check_ajax_referer('quick_view_product', 'nonce');

    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

    if ($product_id) {
        global $post;
        $post = get_post($product_id);
        setup_postdata($post);

        ob_start();
        wc_get_template('quick-view.php', array('product_id' => $product_id));
        $html = ob_get_clean();

        wp_reset_postdata();

        wp_send_json_success(array('html' => $html));
    }

    wp_send_json_error(array('message' => 'Invalid product'));
}
add_action('wp_ajax_quick_view_product', 'aakaari_quick_view_product');
add_action('wp_ajax_nopriv_quick_view_product', 'aakaari_quick_view_product');

/**
 * Get available product categories
 */
function aakaari_get_product_categories() {
    $categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
    ));

    return $categories;
}

/**
 * Get available product colors
 */
function aakaari_get_product_colors() {
    $colors = get_terms(array(
        'taxonomy' => 'pa_color',
        'hide_empty' => true,
    ));

    return $colors;
}

/**
 * Get available product sizes
 */
function aakaari_get_product_sizes() {
    $sizes = get_terms(array(
        'taxonomy' => 'pa_size',
        'hide_empty' => true,
    ));

    return $sizes;
}

/**
 * Check if product is customizable
 */
function aakaari_is_product_customizable($product_id) {
    return get_post_meta($product_id, '_is_customizable', true) === '1';
}

/**
 * Modify WooCommerce product query
 */
function aakaari_modify_product_query($q) {
    if (!is_admin() && $q->is_main_query() && (is_shop() || is_product_category() || is_product_tag())) {
        // Set products per page
        $q->set('posts_per_page', 12);
    }
}
add_action('pre_get_posts', 'aakaari_modify_product_query');

/**
 * Add custom product classes
 */
function aakaari_product_classes($classes, $product) {
    if (aakaari_is_product_customizable($product->get_id())) {
        $classes[] = 'customizable-product';
    }

    if ($product->is_on_sale()) {
        $classes[] = 'on-sale';
    }

    if (!$product->is_in_stock()) {
        $classes[] = 'out-of-stock';
    }

    return $classes;
}
add_filter('woocommerce_post_class', 'aakaari_product_classes', 10, 2);

/**
 * Get user's wishlist
 */
function aakaari_get_user_wishlist() {
    if (!is_user_logged_in()) {
        return array();
    }

    $user_id = get_current_user_id();
    $wishlist = get_user_meta($user_id, '_wishlist', true);

    return is_array($wishlist) ? $wishlist : array();
}

/**
 * Check if product is in wishlist
 */
function aakaari_is_in_wishlist($product_id) {
    $wishlist = aakaari_get_user_wishlist();
    return in_array($product_id, $wishlist);
}

/**
 * Remove WooCommerce default styles (optional)
 */
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

/**
 * Customize product per page options
 */
function aakaari_products_per_page_options() {
    return array(12, 24, 36, -1);
}
add_filter('woocommerce_products_per_page_options', 'aakaari_products_per_page_options');
