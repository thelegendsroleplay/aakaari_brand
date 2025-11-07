<?php
/**
 * functions.php
 * Aakaari custom WooCommerce theme
 *
 * - Enqueues CSS/JS from assets/css/ and assets/js/
 * - Deletes all pages on theme activation, then recreates required WooCommerce pages
 *   (Home, Shop, Cart, Checkout, My Account, Terms)
 *
 * WARNING: Activating this theme permanently deletes all pages (if activation code runs).
 * Please back up your database before activating.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme setup (support, menus, image sizes)
 */
function aakaari_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'woocommerce' );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'aakaari' ),
	) );

	add_image_size( 'aakaari-product', 600, 600, true );
}
add_action( 'after_setup_theme', 'aakaari_theme_setup' );

/**
 * Enqueue assets from assets/css and assets/js
 */
function aakaari_enqueue_assets() {
	$theme = wp_get_theme();
	$theme_version = $theme ? $theme->get( 'Version' ) : time();
	$assets_base = get_stylesheet_directory_uri() . '/assets';

	// CSS Reset - load first to override WordPress defaults
	wp_enqueue_style(
		'aakaari-reset',
		$assets_base . '/css/reset.css',
		array(),
		$theme_version
	);

	// Global styles (header, footer) - load on all pages
	wp_enqueue_style(
		'aakaari-header',
		$assets_base . '/css/header.css',
		array( 'aakaari-reset' ),
		$theme_version
	);

	wp_enqueue_style(
		'aakaari-footer',
		$assets_base . '/css/footer.css',
		array( 'aakaari-reset' ),
		$theme_version
	);

	// Header JS (mobile menu)
	wp_enqueue_script(
		'aakaari-header-js',
		$assets_base . '/js/header.js',
		array( 'jquery' ),
		$theme_version,
		true
	);

	// Homepage assets
	if ( is_front_page() || is_home() ) {
		wp_enqueue_style(
			'aakaari-homepage',
			$assets_base . '/css/homepage.css',
			array(),
			$theme_version
		);

		wp_enqueue_script(
			'aakaari-homepage-js',
			$assets_base . '/js/homepage.js',
			array( 'jquery' ),
			$theme_version,
			true
		);

		wp_localize_script( 'aakaari-homepage-js', 'AakaariData', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'site_url' => home_url( '/' ),
		) );
	}

	// Products/Shop pages - Check if WooCommerce is active
	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) ) {
		wp_enqueue_style(
			'aakaari-shop',
			$assets_base . '/css/shop.css',
			array( 'aakaari-reset' ),
			$theme_version
		);

		wp_enqueue_style(
			'aakaari-product-card',
			$assets_base . '/css/product-card.css',
			array( 'aakaari-reset' ),
			$theme_version
		);

		wp_enqueue_style(
			'aakaari-products',
			$assets_base . '/css/products.css',
			array( 'aakaari-reset' ),
			$theme_version
		);
	}

	// Single product page
	if ( function_exists( 'is_product' ) && is_product() ) {
		wp_enqueue_style(
			'aakaari-product-detail',
			$assets_base . '/css/product-detail.css',
			array(),
			$theme_version
		);
	}

	// Cart page
	if ( function_exists( 'is_cart' ) && is_cart() ) {
		wp_enqueue_style(
			'aakaari-cart',
			$assets_base . '/css/cart.css',
			array(),
			$theme_version
		);
	}

	// Checkout page
	if ( function_exists( 'is_checkout' ) && is_checkout() ) {
		wp_enqueue_style(
			'aakaari-checkout',
			$assets_base . '/css/checkout.css',
			array(),
			$theme_version
		);
	}

	// Account pages
	if ( function_exists( 'is_account_page' ) && is_account_page() ) {
		wp_enqueue_style(
			'aakaari-account',
			$assets_base . '/css/account.css',
			array(),
			$theme_version
		);
	}

	// Page-specific styles
	if ( is_page() ) {
		$page_slug = get_post_field( 'post_name', get_post() );
		$page_css_file = $assets_base . '/css/' . $page_slug . '.css';

		// Check if a CSS file exists for this page
		if ( file_exists( get_stylesheet_directory() . '/assets/css/' . $page_slug . '.css' ) ) {
			wp_enqueue_style(
				'aakaari-page-' . $page_slug,
				$page_css_file,
				array(),
				$theme_version
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'aakaari_enqueue_assets' );

/**
 * Include homepage helper functions if present
 */
if ( file_exists( get_stylesheet_directory() . '/inc/homepage.php' ) ) {
	require_once get_stylesheet_directory() . '/inc/homepage.php';
}

/**
 * Activation routine: delete all pages and create required pages
 *
 * NOTE: This runs on after_switch_theme. It is destructive. Use with care.
 */
function aakaari_delete_all_pages_and_create_required() {
	// Ensure we only run in the activation hook context
	if ( ! did_action( 'after_switch_theme' ) ) {
		return;
	}

	// 1) Delete ALL existing pages (force delete)
	$all_pages = get_posts( array(
		'post_type'   => 'page',
		'post_status' => 'any',
		'numberposts' => -1,
		'fields'      => 'ids',
	) );

	if ( ! empty( $all_pages ) ) {
		foreach ( $all_pages as $page_id ) {
			wp_delete_post( $page_id, true ); // true = force delete (bypass trash)
		}
	}

	// 2) Create required pages and set WooCommerce options where applicable
	$created_ids = array();

	// Home
	$home_id = wp_insert_post( array(
		'post_title'   => 'Home',
		'post_status'  => 'publish',
		'post_type'    => 'page',
		'post_content' => '<!-- Homepage content is generated by front-page.php -->',
	) );
	if ( ! is_wp_error( $home_id ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', (int) $home_id );
		$created_ids['home'] = (int) $home_id;
	}

	// Shop
	$shop_id = wp_insert_post( array(
		'post_title'   => 'Shop',
		'post_status'  => 'publish',
		'post_type'    => 'page',
		'post_content' => '[products limit="12" columns="4"]',
	) );
	if ( ! is_wp_error( $shop_id ) ) {
		update_option( 'woocommerce_shop_page_id', (int) $shop_id );
		$created_ids['shop'] = (int) $shop_id;
	}

	// Cart
	$cart_id = wp_insert_post( array(
		'post_title'   => 'Cart',
		'post_status'  => 'publish',
		'post_type'    => 'page',
		'post_content' => '[woocommerce_cart]',
	) );
	if ( ! is_wp_error( $cart_id ) ) {
		update_option( 'woocommerce_cart_page_id', (int) $cart_id );
		$created_ids['cart'] = (int) $cart_id;
	}

	// Checkout
	$checkout_id = wp_insert_post( array(
		'post_title'   => 'Checkout',
		'post_status'  => 'publish',
		'post_type'    => 'page',
		'post_content' => '[woocommerce_checkout]',
	) );
	if ( ! is_wp_error( $checkout_id ) ) {
		update_option( 'woocommerce_checkout_page_id', (int) $checkout_id );
		$created_ids['checkout'] = (int) $checkout_id;
	}

	// My Account
	$account_id = wp_insert_post( array(
		'post_title'   => 'My Account',
		'post_status'  => 'publish',
		'post_type'    => 'page',
		'post_content' => '[woocommerce_my_account]',
	) );
	if ( ! is_wp_error( $account_id ) ) {
		update_option( 'woocommerce_myaccount_page_id', (int) $account_id );
		update_option( 'woocommerce_my_account_page_id', (int) $account_id ); // older option name
		$created_ids['my_account'] = (int) $account_id;
	}

	// Terms & Conditions
	$terms_id = wp_insert_post( array(
		'post_title'   => 'Terms & Conditions',
		'post_status'  => 'publish',
		'post_type'    => 'page',
		'post_content' => 'Enter your store terms and conditions here.',
	) );
	if ( ! is_wp_error( $terms_id ) ) {
		update_option( 'woocommerce_terms_page_id', (int) $terms_id );
		$created_ids['terms'] = (int) $terms_id;
	}

	// 3) Flush rewrite rules so permalinks work
	flush_rewrite_rules();

	// Save created IDs for admin debugging
	update_option( 'aakaari_created_pages', $created_ids );
}
add_action( 'after_switch_theme', 'aakaari_delete_all_pages_and_create_required' );
