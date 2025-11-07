<?php
/**
 * WooCommerce Product Filters
 * Handles frontend filtering for shop page
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Filter products by price range
 */
function aakaari_filter_products_by_price( $query ) {
    if ( ! is_admin() && $query->is_main_query() && ( is_shop() || is_product_category() || is_product_taxonomy() ) ) {
        if ( isset( $_GET['min_price'] ) && isset( $_GET['max_price'] ) ) {
            $min_price = absint( $_GET['min_price'] );
            $max_price = absint( $_GET['max_price'] );

            $meta_query = $query->get( 'meta_query' ) ?: array();
            $meta_query[] = array(
                'key'     => '_price',
                'value'   => array( $min_price, $max_price ),
                'compare' => 'BETWEEN',
                'type'    => 'NUMERIC',
            );

            $query->set( 'meta_query', $meta_query );
        }
    }
}
add_action( 'pre_get_posts', 'aakaari_filter_products_by_price' );

/**
 * Filter products by size attribute
 */
function aakaari_filter_products_by_size( $query ) {
    if ( ! is_admin() && $query->is_main_query() && ( is_shop() || is_product_category() || is_product_taxonomy() ) ) {
        if ( isset( $_GET['filter_size'] ) && ! empty( $_GET['filter_size'] ) ) {
            $sizes = array_map( 'sanitize_text_field', explode( ',', $_GET['filter_size'] ) );

            $tax_query = $query->get( 'tax_query' ) ?: array();
            $tax_query[] = array(
                'taxonomy' => 'pa_size',
                'field'    => 'slug',
                'terms'    => $sizes,
                'operator' => 'IN',
            );

            $query->set( 'tax_query', $tax_query );
        }
    }
}
add_action( 'pre_get_posts', 'aakaari_filter_products_by_size' );

/**
 * Filter products by color attribute
 */
function aakaari_filter_products_by_color( $query ) {
    if ( ! is_admin() && $query->is_main_query() && ( is_shop() || is_product_category() || is_product_taxonomy() ) ) {
        if ( isset( $_GET['filter_color'] ) && ! empty( $_GET['filter_color'] ) ) {
            $colors = array_map( 'sanitize_text_field', explode( ',', $_GET['filter_color'] ) );

            $tax_query = $query->get( 'tax_query' ) ?: array();
            $tax_query[] = array(
                'taxonomy' => 'pa_color',
                'field'    => 'slug',
                'terms'    => $colors,
                'operator' => 'IN',
            );

            $query->set( 'tax_query', $tax_query );
        }
    }
}
add_action( 'pre_get_posts', 'aakaari_filter_products_by_color' );

/**
 * Filter products by rating
 */
function aakaari_filter_products_by_rating( $query ) {
    if ( ! is_admin() && $query->is_main_query() && ( is_shop() || is_product_category() || is_product_taxonomy() ) ) {
        if ( isset( $_GET['rating_filter'] ) && ! empty( $_GET['rating_filter'] ) ) {
            $rating = absint( $_GET['rating_filter'] );

            // Get product IDs with ratings >= $rating
            global $wpdb;
            $product_ids = $wpdb->get_col( $wpdb->prepare(
                "SELECT comment_post_ID FROM $wpdb->comments
                INNER JOIN $wpdb->commentmeta ON comment_ID = meta_id
                WHERE comment_type = 'review'
                AND comment_approved = '1'
                AND meta_key = 'rating'
                AND meta_value >= %d
                GROUP BY comment_post_ID",
                $rating
            ) );

            if ( ! empty( $product_ids ) ) {
                $query->set( 'post__in', $product_ids );
            } else {
                // No products match, return empty
                $query->set( 'post__in', array( 0 ) );
            }
        }
    }
}
add_action( 'pre_get_posts', 'aakaari_filter_products_by_rating' );

/**
 * Handle custom sorting options
 */
function aakaari_custom_woocommerce_sorting( $query ) {
    if ( ! is_admin() && $query->is_main_query() && ( is_shop() || is_product_category() || is_product_taxonomy() ) ) {
        if ( isset( $_GET['orderby'] ) ) {
            $orderby = sanitize_text_field( $_GET['orderby'] );

            switch ( $orderby ) {
                case 'popularity':
                    $query->set( 'meta_key', 'total_sales' );
                    $query->set( 'orderby', 'meta_value_num' );
                    $query->set( 'order', 'DESC' );
                    break;

                case 'rating':
                    $query->set( 'meta_key', '_wc_average_rating' );
                    $query->set( 'orderby', 'meta_value_num' );
                    $query->set( 'order', 'DESC' );
                    break;

                case 'date':
                    $query->set( 'orderby', 'date' );
                    $query->set( 'order', 'DESC' );
                    break;

                case 'price':
                    $query->set( 'meta_key', '_price' );
                    $query->set( 'orderby', 'meta_value_num' );
                    $query->set( 'order', 'ASC' );
                    break;

                case 'price-desc':
                    $query->set( 'meta_key', '_price' );
                    $query->set( 'orderby', 'meta_value_num' );
                    $query->set( 'order', 'DESC' );
                    break;

                case 'sale':
                    // Show only products on sale
                    $query->set( 'post__in', array_merge( array( 0 ), wc_get_product_ids_on_sale() ) );
                    $query->set( 'orderby', 'date' );
                    $query->set( 'order', 'DESC' );
                    break;
            }
        }
    }
}
add_action( 'pre_get_posts', 'aakaari_custom_woocommerce_sorting' );
