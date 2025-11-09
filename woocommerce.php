<?php
/**
 * WooCommerce Template
 *
 * This template is used for all WooCommerce pages (shop, cart, checkout, account, etc.)
 *
 * @package Aakaari_Brand
 */

// TEMPORARY DEBUG - Remove after fixing
if (file_exists(AAKAARI_THEME_DIR . '/DEBUG-CHECK.php')) {
    include(AAKAARI_THEME_DIR . '/DEBUG-CHECK.php');
}

get_header();
?>

<main id="main" class="site-main woocommerce-page">
    <?php
    // For cart and checkout pages, use the_content() to process shortcodes
    // For other WooCommerce pages (shop, products), use woocommerce_content()
    if (is_cart() || is_checkout() || is_account_page()) {
        while (have_posts()) :
            the_post();
            the_content();
        endwhile;
    } else {
        woocommerce_content();
    }
    ?>
</main>

<?php
get_footer();
