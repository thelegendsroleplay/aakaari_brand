<?php
/**
 * Template Name: Wishlist
 *
 * Custom wishlist page template for Aakaari theme
 * Displays user's wishlist with add/remove and move to cart functionality
 *
 * @package Aakaari
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header(); ?>

<main id="primary" class="site-main wishlist-page">

    <?php
    /**
     * Hook: aakaari_before_wishlist_content
     *
     * Allows adding content before the wishlist sections
     */
    do_action( 'aakaari_before_wishlist_content' );

    // Load wishlist functions
    if ( file_exists( get_template_directory() . '/inc/wishlist.php' ) ) {
        require_once get_template_directory() . '/inc/wishlist.php';
    }
    ?>

    <div class="wishlist-container">

        <?php
        /**
         * Wishlist Header Section
         * Displays the wishlist title and stats
         */
        if ( function_exists( 'aakaari_render_wishlist_header' ) ) {
            aakaari_render_wishlist_header();
        }

        /**
         * Wishlist Actions Section
         * Displays bulk action buttons
         */
        if ( function_exists( 'aakaari_render_wishlist_actions' ) ) {
            aakaari_render_wishlist_actions();
        }

        /**
         * Wishlist Grid Section
         * Displays the wishlist products
         */
        if ( function_exists( 'aakaari_render_wishlist_grid' ) ) {
            aakaari_render_wishlist_grid();
        }
        ?>

    </div>

    <?php
    /**
     * Hook: aakaari_after_wishlist_content
     *
     * Allows adding additional content after the wishlist sections
     */
    do_action( 'aakaari_after_wishlist_content' );
    ?>

</main>

<?php
get_footer();
