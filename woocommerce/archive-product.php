<?php
/**
 * The Template for displaying product archives (Shop Page)
 * Uses AJAX filtering - loads products dynamically via inc/shop.php
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

// Output the shop page markup (loaded from inc/shop.php)
if ( function_exists( 'aakaari_shop_markup' ) ) {
	aakaari_shop_markup();
} else {
	// Fallback if shop.php not included
	echo '<div class="notice notice-error">Shop template function not found. Please ensure inc/shop.php is included in functions.php</div>';
}

get_footer( 'shop' );
