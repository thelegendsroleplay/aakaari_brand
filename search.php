<?php
/**
 * Template Name: Search Results
 *
 * Custom search template for Aakaari theme
 * Displays search results with filters and product grid
 *
 * @package Aakaari
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header(); ?>

<main id="primary" class="site-main search-page">

    <?php
    /**
     * Hook: aakaari_before_search_content
     *
     * Allows adding content before the search sections
     */
    do_action( 'aakaari_before_search_content' );

    // Load search functions
    if ( file_exists( get_template_directory() . '/inc/search.php' ) ) {
        require_once get_template_directory() . '/inc/search.php';
    }

    // Get search query
    $search_query = get_search_query();
    ?>

    <div class="search-container">

        <?php
        /**
         * Search Header Section
         * Displays the search input and quick filters
         */
        if ( function_exists( 'aakaari_render_search_header' ) ) {
            aakaari_render_search_header( $search_query );
        }
        ?>

        <div class="search-results">
            <?php
            /**
             * Search Sidebar
             * Displays filters for categories, price, and ratings
             */
            if ( function_exists( 'aakaari_render_search_sidebar' ) ) {
                aakaari_render_search_sidebar();
            }

            /**
             * Search Results Grid
             * Displays the product search results
             */
            if ( function_exists( 'aakaari_render_search_results' ) ) {
                aakaari_render_search_results( $search_query );
            }
            ?>
        </div>

    </div>

    <?php
    /**
     * Hook: aakaari_after_search_content
     *
     * Allows adding additional content after the search sections
     */
    do_action( 'aakaari_after_search_content' );
    ?>

</main>

<?php
get_footer();
