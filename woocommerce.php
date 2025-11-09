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
    is_woocommerce(): <?php echo is_woocommerce() ? 'YES' : 'NO'; ?><br>
</div>

<main id="main" class="site-main woocommerce-page">
    <?php woocommerce_content(); ?>
</main>

<?php
get_footer();
