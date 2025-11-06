<?php
/**
 * Template Name: Homepage
 *
 * Custom homepage template for Aakaari theme
 * Displays hero section, categories, and featured products
 *
 * @package Aakaari
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header(); ?>

<main id="primary" class="site-main homepage-template">

    <?php
    /**
     * Hook: aakaari_before_homepage_content
     *
     * Allows adding content before the homepage sections
     */
    do_action( 'aakaari_before_homepage_content' );

    // Load home functions
    if ( file_exists( get_template_directory() . '/inc/home.php' ) ) {
        require_once get_template_directory() . '/inc/home.php';
    }

    /**
     * Hero Section
     * Displays the main hero banner with customizable content
     */
    if ( function_exists( 'aakaari_render_hero_section' ) ) {
        aakaari_render_hero_section();
    }

    /**
     * Categories Section
     * Displays WooCommerce product categories in a grid layout
     */
    if ( function_exists( 'aakaari_render_categories_section' ) ) {
        aakaari_render_categories_section();
    }

    /**
     * Featured Products Section
     * Displays featured WooCommerce products
     */
    if ( function_exists( 'aakaari_render_featured_products_section' ) ) {
        aakaari_render_featured_products_section();
    }

    /**
     * Hook: aakaari_after_homepage_content
     *
     * Allows adding additional content after the homepage sections
     * Examples: Newsletter signup, promotional banners, testimonials, etc.
     */
    do_action( 'aakaari_after_homepage_content' );
    ?>

</main>

<?php
get_footer();
