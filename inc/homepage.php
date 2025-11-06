<?php
/**
 * inc/homepage.php
 * Robust helper functions for the homepage.
 * - Uses WooCommerce if present
 * - Falls back to sensible placeholder data if no real products exist
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Return an array of featured products (WC_Product objects or fallback arrays)
 *
 * @param int $limit
 * @return array
 */
function aakaari_get_featured_products( $limit = 8 ) {
    // If WooCommerce is active, try to get featured products
    if ( class_exists( 'WooCommerce' ) ) {
        try {
            $args = array(
                'limit'    => $limit,
                'status'   => 'publish',
                'featured' => true,
            );
            $products = wc_get_products( $args );
            if ( ! empty( $products ) ) {
                return $products;
            }
        } catch ( Exception $e ) {
            // fall through to placeholders
        }
    }

    // Fallback placeholder product array (simple associative arrays)
    $placeholders = array();
    for ( $i = 1; $i <= $limit; $i++ ) {
        $placeholders[] = array(
            'id'        => 1000 + $i,
            'title'     => "Placeholder Product {$i}",
            'price'     => '$0.00',
            'image'     => get_stylesheet_directory_uri() . '/assets/images/placeholder-product.png',
            'permalink' => '#',
            'description' => 'This is a placeholder product. Add real products in WooCommerce.',
        );
    }
    return $placeholders;
}

/**
 * Return an array of latest products (WC_Product objects or fallback arrays)
 *
 * @param int $limit
 * @return array
 */
function aakaari_get_new_arrivals( $limit = 8 ) {
    if ( class_exists( 'WooCommerce' ) ) {
        try {
            $args = array(
                'limit'   => $limit,
                'status'  => 'publish',
                'orderby' => 'date',
                'order'   => 'DESC',
            );
            $products = wc_get_products( $args );
            if ( ! empty( $products ) ) {
                return $products;
            }
        } catch ( Exception $e ) {
            // fall through
        }
    }

    // Fallback placeholders (same style)
    $placeholders = array();
    for ( $i = 1; $i <= $limit; $i++ ) {
        $placeholders[] = array(
            'id'        => 2000 + $i,
            'title'     => "New Arrival {$i}",
            'price'     => '$0.00',
            'image'     => get_stylesheet_directory_uri() . '/assets/images/placeholder-product.png',
            'permalink' => '#',
            'description' => 'Placeholder new arrival. Add real products in WooCommerce.',
        );
    }
    return $placeholders;
}

/**
 * Convert a WC_Product object to a simple array for safe JSON output
 *
 * @param WC_Product|array $product
 * @return array
 */
function aakaari_product_to_array( $product ) {
    if ( is_object( $product ) && method_exists( $product, 'get_id' ) ) {
        $id    = $product->get_id();
        $image = '';
        $att   = $product->get_image_id();
        if ( $att ) {
            $image = wp_get_attachment_image_url( $att, 'aakaari-product' );
        }
        if ( ! $image ) {
            $image = wc_placeholder_img_src();
        }

        return array(
            'id'          => $id,
            'title'       => wp_strip_all_tags( $product->get_name() ),
            'price_html'  => $product->get_price_html(),
            'price'       => wc_price( $product->get_price() ),
            'image'       => esc_url_raw( $image ),
            'permalink'   => esc_url( $product->get_permalink() ),
            'description' => wp_strip_all_tags( $product->get_short_description() ?: $product->get_description() ),
        );
    }

    // If it's already a fallback array, ensure expected keys exist
    if ( is_array( $product ) ) {
        return array(
            'id'          => isset( $product['id'] ) ? $product['id'] : 0,
            'title'       => isset( $product['title'] ) ? $product['title'] : '',
            'price_html'  => isset( $product['price'] ) ? $product['price'] : '',
            'price'       => isset( $product['price'] ) ? $product['price'] : '',
            'image'       => isset( $product['image'] ) ? $product['image'] : wc_placeholder_img_src(),
            'permalink'   => isset( $product['permalink'] ) ? $product['permalink'] : '#',
            'description' => isset( $product['description'] ) ? $product['description'] : '',
        );
    }

    return array();
}
