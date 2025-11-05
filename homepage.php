<?php
/**
 * Template Name: Homepage
 * Description: Custom homepage template with hero, categories, and featured products
 *
 * @package FashionMen
 * @since 1.0.0
 */

// Include homepage functions
require_once get_template_directory() . '/inc/homepage.php';

get_header();
?>

<main id="primary" class="site-main homepage">

    <!-- Hero Section -->
    <section class="hero-section">
        <?php fashionmen_hero_section(); ?>
    </section>

    <!-- Categories Section -->
    <section class="categories-section">
        <?php fashionmen_categories_section(); ?>
    </section>

    <!-- Featured Products Section -->
    <section class="featured-products-section">
        <?php fashionmen_featured_products_section(); ?>
    </section>

</main>

<?php
get_footer();
