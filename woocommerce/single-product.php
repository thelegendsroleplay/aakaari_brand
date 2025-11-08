<?php
/**
 * Single Product Template
 * Loads header, the content-single-product.php template, and footer.
 *
 * Put this file in your theme root as single-product.php
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( have_posts() ) {
    while ( have_posts() ) {
        the_post();

        /**
         * Allow WooCommerce / plugins to hook before product.
         * We intentionally DO NOT call the default wrapper (your functions.php removed it).
         */
        do_action( 'woocommerce_before_single_product' );

        // Load our custom content template (content-single-product.php)
        wc_get_template_part( 'content', 'single-product' );

        do_action( 'woocommerce_after_single_product' );
    }
}

get_footer();
