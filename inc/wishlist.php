<?php
/**
 * Custom Wishlist System - Aakaari Brand
 *
 * Simple wishlist system without plugin dependencies
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Create wishlist table on theme activation
 */
function aakaari_create_wishlist_table() {
  global $wpdb;

  $table_name = $wpdb->prefix . 'aakaari_wishlist';
  $charset_collate = $wpdb->get_charset_collate();

  $sql = "CREATE TABLE IF NOT EXISTS $table_name (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) NOT NULL,
    product_id bigint(20) NOT NULL,
    added_date datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY  (id),
    UNIQUE KEY user_product (user_id, product_id),
    KEY user_id (user_id),
    KEY product_id (product_id)
  ) $charset_collate;";

  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta( $sql );
}
add_action( 'after_switch_theme', 'aakaari_create_wishlist_table' );

/**
 * Add product to wishlist - AJAX handler
 */
function aakaari_add_to_wishlist_ajax() {
  check_ajax_referer( 'aakaari_ajax_nonce', 'nonce' );

  if ( ! is_user_logged_in() ) {
    wp_send_json_error( array( 'message' => 'Please login to add items to wishlist' ) );
  }

  $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

  if ( ! $product_id ) {
    wp_send_json_error( array( 'message' => 'Invalid product' ) );
  }

  global $wpdb;
  $table_name = $wpdb->prefix . 'aakaari_wishlist';
  $user_id = get_current_user_id();

  // Check if already in wishlist
  $exists = $wpdb->get_var( $wpdb->prepare(
    "SELECT id FROM $table_name WHERE user_id = %d AND product_id = %d",
    $user_id,
    $product_id
  ) );

  if ( $exists ) {
    wp_send_json_error( array( 'message' => 'Already in wishlist' ) );
  }

  // Add to wishlist
  $inserted = $wpdb->insert(
    $table_name,
    array(
      'user_id' => $user_id,
      'product_id' => $product_id
    ),
    array( '%d', '%d' )
  );

  if ( $inserted ) {
    wp_send_json_success( array( 'message' => 'Added to wishlist' ) );
  } else {
    wp_send_json_error( array( 'message' => 'Could not add to wishlist' ) );
  }
}
add_action( 'wp_ajax_aakaari_add_to_wishlist', 'aakaari_add_to_wishlist_ajax' );
add_action( 'wp_ajax_nopriv_aakaari_add_to_wishlist', 'aakaari_add_to_wishlist_ajax' );

/**
 * Remove product from wishlist - AJAX handler
 */
function aakaari_remove_from_wishlist_ajax() {
  check_ajax_referer( 'aakaari_ajax_nonce', 'nonce' );

  if ( ! is_user_logged_in() ) {
    wp_send_json_error( array( 'message' => 'Please login' ) );
  }

  $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

  if ( ! $product_id ) {
    wp_send_json_error( array( 'message' => 'Invalid product' ) );
  }

  global $wpdb;
  $table_name = $wpdb->prefix . 'aakaari_wishlist';
  $user_id = get_current_user_id();

  $deleted = $wpdb->delete(
    $table_name,
    array(
      'user_id' => $user_id,
      'product_id' => $product_id
    ),
    array( '%d', '%d' )
  );

  if ( $deleted ) {
    wp_send_json_success( array( 'message' => 'Removed from wishlist' ) );
  } else {
    wp_send_json_error( array( 'message' => 'Could not remove from wishlist' ) );
  }
}
add_action( 'wp_ajax_aakaari_remove_from_wishlist', 'aakaari_remove_from_wishlist_ajax' );

/**
 * Get user's wishlist product IDs
 */
function aakaari_get_wishlist_product_ids( $user_id = 0 ) {
  if ( ! $user_id ) {
    $user_id = get_current_user_id();
  }

  if ( ! $user_id ) {
    return array();
  }

  global $wpdb;
  $table_name = $wpdb->prefix . 'aakaari_wishlist';

  $product_ids = $wpdb->get_col( $wpdb->prepare(
    "SELECT product_id FROM $table_name WHERE user_id = %d ORDER BY added_date DESC",
    $user_id
  ) );

  return $product_ids ? array_map( 'absint', $product_ids ) : array();
}

/**
 * Check if product is in wishlist
 */
function aakaari_is_in_wishlist( $product_id, $user_id = 0 ) {
  if ( ! $user_id ) {
    $user_id = get_current_user_id();
  }

  if ( ! $user_id ) {
    return false;
  }

  $wishlist_ids = aakaari_get_wishlist_product_ids( $user_id );
  return in_array( $product_id, $wishlist_ids );
}

/**
 * Get wishlist count for user
 */
function aakaari_get_wishlist_count( $user_id = 0 ) {
  if ( ! $user_id ) {
    $user_id = get_current_user_id();
  }

  if ( ! $user_id ) {
    return 0;
  }

  global $wpdb;
  $table_name = $wpdb->prefix . 'aakaari_wishlist';

  $count = $wpdb->get_var( $wpdb->prepare(
    "SELECT COUNT(*) FROM $table_name WHERE user_id = %d",
    $user_id
  ) );

  return absint( $count );
}

/**
 * Localize wishlist data for JavaScript
 */
function aakaari_localize_wishlist_data() {
  if ( ! is_user_logged_in() ) {
    return;
  }

  $wishlist_ids = aakaari_get_wishlist_product_ids();

  wp_localize_script( 'aakaari-products', 'aakaari_wishlist', array(
    'product_ids' => $wishlist_ids,
    'count' => count( $wishlist_ids )
  ) );
}
add_action( 'wp_enqueue_scripts', 'aakaari_localize_wishlist_data', 20 );
