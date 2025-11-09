<?php
/**
 * WooCommerce Template
 *
 * This template is used for all WooCommerce pages (shop, cart, checkout, account, etc.)
 *
 * @package Aakaari_Brand
 */

get_header();
?>

<main id="main" class="site-main woocommerce-page">
    <?php
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
    ?>
</main>

<?php
get_footer();
