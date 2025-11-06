<?php
/**
 * inc/homepage.php
 * Helper functions for the front-page template (product queries).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get featured products (WooCommerce).
 *
 * @param int $limit Number of products to return.
 * @return WC_Product[]|array
 */
function aakaari_get_featured_products( $limit = 8 ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return array();
	}

	$args = array(
		'limit'    => $limit,
		'status'   => 'publish',
		'featured' => true,
	);

	try {
		$products = wc_get_products( $args );
	} catch ( Exception $e ) {
		$products = array();
	}

	return $products;
}

/**
 * Get latest / new arrival products.
 *
 * @param int $limit
 * @return WC_Product[]|array
 */
function aakaari_get_new_arrivals( $limit = 8 ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return array();
	}

	$args = array(
		'limit'  => $limit,
		'status' => 'publish',
		'orderby'=> 'date',
		'order'  => 'DESC',
	);

	try {
		$products = wc_get_products( $args );
	} catch ( Exception $e ) {
		$products = array();
	}

	return $products;
}

/**
 * Simple safe product data array for front-end JavaScript consumption if needed.
 *
 * @param WC_Product $product
 * @return array
 */
function aakaari_product_to_array( $product ) {
	if ( ! $product ) {
		return array();
	}

	$id    = $product->get_id();
	$image = '';
	$att   = $product->get_image_id();
	if ( $att ) {
		$image = wp_get_attachment_image_url( $att, 'aakaari-product' );
	}
	// fallback to placeholder
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
		'description' => wp_strip_all_tags( $product->get_short_description() ? $product->get_short_description() : $product->get_description() ),
	);
}
