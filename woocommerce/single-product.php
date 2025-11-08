<?php
/**
 * single-product.php
 * Simple wrapper for product single. Place in your theme's root (it will be used by WP).
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( have_posts() ) :
  while ( have_posts() ) : the_post();
    // If using WooCommerce template structure, it often loads content-single-product.php
    // We'll include our template directly.
    wc_get_template_part( 'content', 'single-product' );
  endwhile;
endif;

get_footer();
