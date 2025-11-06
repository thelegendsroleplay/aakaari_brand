<?php
/**
 * The template for displaying the homepage
 *
 * @package Aakaari_Brand
 */

get_header();
?>

<div id="homepage-wrapper" class="homepage-wrapper">

    <?php
    // Hero Section
    aakaari_brand_hero_section();

    // Categories Section
    aakaari_brand_categories_section();

    // Featured Products Section
    aakaari_brand_featured_products_section();

    // Newsletter Section (Optional)
    aakaari_brand_newsletter_section();

    // Features Section (Optional)
    aakaari_brand_features_section();
    ?>

</div>

<?php
get_footer();
