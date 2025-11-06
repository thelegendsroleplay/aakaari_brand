<?php
/**
 * Template Name: Homepage
 * The front page template file
 *
 * @package Aakaari_Brand
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    // Hero Section
    aakaari_brand_hero_section();

    // Categories Section
    aakaari_brand_categories_section();

    // Featured Products Section
    aakaari_brand_featured_products_section();
    ?>
</main>

<?php
get_footer();
