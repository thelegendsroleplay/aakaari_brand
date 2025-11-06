<?php
/**
 * The template for displaying WooCommerce pages
 *
 * @package Aakaari_Brand
 */

get_header();
?>

<div class="site-container">
    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <?php
            // WooCommerce content will be inserted here
            woocommerce_content();
            ?>

        </main>
    </div>

    <?php
    // Display shop sidebar if active
    if ( is_active_sidebar( 'shop-sidebar' ) ) :
        ?>
        <aside id="secondary" class="widget-area shop-sidebar" role="complementary">
            <?php dynamic_sidebar( 'shop-sidebar' ); ?>
        </aside>
        <?php
    endif;
    ?>
</div>

<?php
get_footer();
