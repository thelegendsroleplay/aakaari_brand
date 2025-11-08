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
 * Include Custom Live Chat Support System
 */
require_once get_template_directory() . '/inc/live-chat-system.php';

/**
 * Include WooCommerce Product Filters
 */
require_once get_template_directory() . '/inc/wc-product-filters.php';

/**
 * Include WooCommerce Color Attributes
 */
require_once get_template_directory() . '/inc/wc-color-attributes.php';

/**
 * Include Shop Page Functionality (AJAX handlers, markup)
 */
require_once get_template_directory() . '/inc/shop.php';

/**
 * Include Custom Wishlist System
 */
require_once get_template_directory() . '/inc/wishlist.php';

/**
 * Theme setup (support, menus, image sizes)
 */
function aakaari_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'woocommerce' );

	// Enable AJAX add to cart
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'aakaari' ),
	) );

	add_image_size( 'aakaari-product', 600, 600, true );
}
add_action( 'after_setup_theme', 'aakaari_theme_setup' );

/**
 * Enable AJAX add to cart for all products
 */
add_filter( 'woocommerce_product_add_to_cart_url', 'aakaari_enable_ajax_add_to_cart', 10, 2 );
function aakaari_enable_ajax_add_to_cart( $url, $product ) {
	// Return empty to use AJAX add to cart
	return '';
}

/**
 * Add AJAX add to cart class to all add to cart buttons
 */
add_filter( 'woocommerce_loop_add_to_cart_link', 'aakaari_ajax_add_to_cart_class', 10, 2 );
function aakaari_ajax_add_to_cart_class( $html, $product ) {
	// Add ajax_add_to_cart class
	$html = str_replace( 'class="', 'class="ajax_add_to_cart ', $html );
	return $html;
}

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
			array( 'aakaari-reset' ),
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
		// Enqueue products CSS (includes all shop page styles)
		wp_enqueue_style(
			'aakaari-products',
			$assets_base . '/css/products.css',
			array( 'aakaari-reset' ),
			time() // Force cache refresh
		);

		// Enqueue toast notifications CSS
		wp_enqueue_style(
			'aakaari-toast',
			$assets_base . '/css/toast-notifications.css',
			array(),
			time() // Force cache refresh
		);

		// Enqueue toast notifications JS (load before products.js)
		wp_enqueue_script(
			'aakaari-toast',
			$assets_base . '/js/toast-notifications.js',
			array(),
			time(), // Force cache refresh
			true
		);

		// Enqueue products JS for AJAX filtering
		wp_enqueue_script(
			'aakaari-products',
			$assets_base . '/js/products.js',
			array( 'aakaari-toast' ),
			time(), // Force cache refresh
			true
		);

		// Localize AJAX data for products page
		wp_localize_script( 'aakaari-products', 'aakaari_ajax', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'aakaari_ajax_nonce' ),
			'home_url' => home_url(),
		) );

		// Optional page type for filtering logic
		$page_type = 'products';
		if ( is_product_category() ) {
			$category = get_queried_object();
			$page_type = $category->slug;
		}
		wp_add_inline_script( 'aakaari-products', 'window.aakaari_page_type = "' . esc_js( $page_type ) . '";', 'before' );
	}

	// Cart page
	if ( function_exists( 'is_cart' ) && is_cart() ) {
		wp_enqueue_style(
			'aakaari-cart',
			$assets_base . '/css/cart.css',
			array(),
			$theme_version
		);

		wp_enqueue_script(
			'aakaari-cart-js',
			$assets_base . '/js/cart.js',
			array( 'jquery' ),
			$theme_version,
			true
		);
	}

	// Single Product page
	if ( function_exists( 'is_product' ) && is_product() ) {
		wp_enqueue_style(
			'aakaari-single-product',
			$assets_base . '/css/single-product.css',
			array(),
			time() // Force cache refresh
		);

		wp_enqueue_script(
			'aakaari-single-product-js',
			$assets_base . '/js/single-product.js',
			array( 'jquery' ),
			time(), // Force cache refresh
			true
		);

		wp_localize_script( 'aakaari-single-product-js', 'wc_single_product_params', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'checkout_url' => wc_get_checkout_url(),
		) );
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
		// Auth styles for login/register pages
		if ( ! is_user_logged_in() ) {
			wp_enqueue_style(
				'aakaari-auth',
				$assets_base . '/css/auth.css',
				array( 'aakaari-reset' ),
				$theme_version
			);
		} else {
			// Account dashboard styles
			wp_enqueue_style(
				'aakaari-account',
				$assets_base . '/css/account.css',
				array( 'aakaari-reset' ),
				$theme_version
			);
		}
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

	// Live Chat Widget - Load on all pages
	wp_enqueue_style(
		'aakaari-live-chat',
		$assets_base . '/css/live-chat.css',
		array(),
		$theme_version
	);

	wp_enqueue_script(
		'aakaari-live-chat-js',
		$assets_base . '/js/live-chat.js',
		array( 'jquery' ),
		$theme_version,
		true
	);

	// Localize script for AJAX
	wp_localize_script( 'aakaari-live-chat-js', 'aakaari_chat', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'aakaari_chat_nonce' ),
	) );
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

/**
 * WooCommerce Cart Page Customizations
 * Remove default WooCommerce features that conflict with custom design
 */

// Disable WooCommerce default cart styles
add_filter( 'woocommerce_enqueue_styles', 'aakaari_disable_woocommerce_cart_styles' );
function aakaari_disable_woocommerce_cart_styles( $enqueue_styles ) {
	if ( is_cart() ) {
		// Disable default WooCommerce styles on cart page
		unset( $enqueue_styles['woocommerce-general'] );
		unset( $enqueue_styles['woocommerce-layout'] );
		unset( $enqueue_styles['woocommerce-smallscreen'] );
	}
	return $enqueue_styles;
}

// Remove WooCommerce cart page wrapper
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

// Remove WooCommerce breadcrumbs on cart page
add_action( 'template_redirect', 'aakaari_remove_cart_breadcrumbs' );
function aakaari_remove_cart_breadcrumbs() {
	if ( is_cart() ) {
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
	}
}
