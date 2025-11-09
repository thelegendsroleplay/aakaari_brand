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

<!-- DEBUG: woocommerce.php is loading -->
<div style="background: lightblue; padding: 10px; margin: 10px; border: 2px solid blue;">
    DEBUG: woocommerce.php template is being used!<br>
    is_cart(): <?php echo is_cart() ? 'YES' : 'NO'; ?><br>
    is_checkout(): <?php echo is_checkout() ? 'YES' : 'NO'; ?><br>
    is_woocommerce(): <?php echo is_woocommerce() ? 'YES' : 'NO'; ?><br>
</div>

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
