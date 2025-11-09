<?php
/**
 * Shop Functions
 *
 * @package Aakaari_Brand
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get available product attribute terms
 */
function aakaari_get_available_attribute_terms($taxonomy) {
    $terms = get_terms(array(
        'taxonomy' => $taxonomy,
        'hide_empty' => true,
    ));

    if (is_wp_error($terms)) {
        return array();
    }

    return $terms;
}

/**
 * Modify WooCommerce product query based on filters
 */
function aakaari_filter_products_query($q) {
    if (!is_admin() && $q->is_main_query() && (is_shop() || is_product_taxonomy())) {

        // Price filter
        if (isset($_GET['min_price']) || isset($_GET['max_price'])) {
            $min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
            $max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : PHP_FLOAT_MAX;

            $q->set('meta_query', array(
                array(
                    'key' => '_price',
                    'value' => array($min_price, $max_price),
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC'
                )
            ));
        }

        // Category filter
        if (isset($_GET['filter_categories']) && !empty($_GET['filter_categories'])) {
            $categories = explode(',', sanitize_text_field($_GET['filter_categories']));

            $tax_query = $q->get('tax_query') ? $q->get('tax_query') : array();
            $tax_query[] = array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $categories,
                'operator' => 'IN'
            );

            $q->set('tax_query', $tax_query);
        }

        // Size filter
        if (isset($_GET['filter_sizes']) && !empty($_GET['filter_sizes'])) {
            $sizes = explode(',', sanitize_text_field($_GET['filter_sizes']));

            $tax_query = $q->get('tax_query') ? $q->get('tax_query') : array();
            $tax_query[] = array(
                'taxonomy' => 'pa_size',
                'field' => 'slug',
                'terms' => $sizes,
                'operator' => 'IN'
            );

            $q->set('tax_query', $tax_query);
        }

        // Color filter
        if (isset($_GET['filter_colors']) && !empty($_GET['filter_colors'])) {
            $colors = explode(',', sanitize_text_field($_GET['filter_colors']));

            $tax_query = $q->get('tax_query') ? $q->get('tax_query') : array();
            $tax_query[] = array(
                'taxonomy' => 'pa_color',
                'field' => 'slug',
                'terms' => $colors,
                'operator' => 'IN'
            );

            $q->set('tax_query', $tax_query);
        }

        // Rating filter
        if (isset($_GET['min_rating']) && intval($_GET['min_rating']) > 0) {
            $min_rating = intval($_GET['min_rating']);

            add_filter('posts_clauses', function($args) use ($min_rating) {
                global $wpdb;

                $args['join'] .= " LEFT JOIN {$wpdb->comments} ON {$wpdb->posts}.ID = {$wpdb->comments}.comment_post_ID
                                   LEFT JOIN {$wpdb->commentmeta} ON {$wpdb->comments}.comment_ID = {$wpdb->commentmeta}.comment_id";

                $args['where'] .= " AND {$wpdb->commentmeta}.meta_key = 'rating' AND {$wpdb->commentmeta}.meta_value >= {$min_rating}";

                $args['groupby'] = "{$wpdb->posts}.ID";

                return $args;
            });
        }

        // Sorting
        if (isset($_GET['orderby'])) {
            $orderby = sanitize_text_field($_GET['orderby']);

            switch ($orderby) {
                case 'popularity':
                    $q->set('meta_key', 'total_sales');
                    $q->set('orderby', 'meta_value_num');
                    $q->set('order', 'DESC');
                    break;

                case 'rating':
                    $q->set('meta_key', '_wc_average_rating');
                    $q->set('orderby', 'meta_value_num');
                    $q->set('order', 'DESC');
                    break;

                case 'date':
                    $q->set('orderby', 'date');
                    $q->set('order', 'DESC');
                    break;

                case 'price':
                    $q->set('meta_key', '_price');
                    $q->set('orderby', 'meta_value_num');
                    $q->set('order', 'ASC');
                    break;

                case 'price-desc':
                    $q->set('meta_key', '_price');
                    $q->set('orderby', 'meta_value_num');
                    $q->set('order', 'DESC');
                    break;
            }
        }
    }
}
add_action('pre_get_posts', 'aakaari_filter_products_query');

/**
 * Custom WooCommerce loop classes
 */
function aakaari_woocommerce_loop_start($html) {
    return '<div class="woocommerce-loop-container">';
}
add_filter('woocommerce_product_loop_start', 'aakaari_woocommerce_loop_start');

function aakaari_woocommerce_loop_end($html) {
    return '</div>';
}
add_filter('woocommerce_product_loop_end', 'aakaari_woocommerce_loop_end');

/**
 * Change products per page
 */
function aakaari_products_per_page() {
    return 12;
}
add_filter('loop_shop_per_page', 'aakaari_products_per_page', 20);

/**
 * Remove default WooCommerce result count and ordering
 */
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
