<?php
/**
 * The Template for displaying product archives, including the main shop page
 *
 * @package FashionMen
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

get_header('shop');

?>

<div class="woocommerce-page shop-page">
    <div class="container mx-auto px-4 py-8">

        <?php
        /**
         * Hook: woocommerce_before_main_content.
         */
        do_action('woocommerce_before_main_content');
        ?>

        <header class="woocommerce-products-header mb-8">
            <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
                <h1 class="woocommerce-products-header__title page-title text-4xl font-bold mb-4"><?php woocommerce_page_title(); ?></h1>
            <?php endif; ?>

            <?php
            /**
             * Hook: woocommerce_archive_description.
             */
            do_action('woocommerce_archive_description');
            ?>
        </header>

        <div class="flex flex-col lg:flex-row gap-8">

            <!-- Sidebar Filters -->
            <?php if (is_active_sidebar('sidebar-shop')) : ?>
                <aside class="shop-sidebar w-full lg:w-64 flex-shrink-0">
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4"><?php esc_html_e('Filters', 'fashionmen'); ?></h3>
                        <?php dynamic_sidebar('sidebar-shop'); ?>
                    </div>
                </aside>
            <?php endif; ?>

            <!-- Products Grid -->
            <div class="shop-content flex-1">

                <?php if (woocommerce_product_loop()) : ?>

                    <!-- Toolbar -->
                    <div class="shop-toolbar flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                        <div class="result-count">
                            <?php woocommerce_result_count(); ?>
                        </div>
                        <div class="catalog-ordering">
                            <?php woocommerce_catalog_ordering(); ?>
                        </div>
                    </div>

                    <?php
                    woocommerce_product_loop_start();

                    if (wc_get_loop_prop('total')) {
                        while (have_posts()) {
                            the_post();

                            /**
                             * Hook: woocommerce_shop_loop.
                             */
                            do_action('woocommerce_shop_loop');

                            wc_get_template_part('content', 'product');
                        }
                    }

                    woocommerce_product_loop_end();

                    /**
                     * Hook: woocommerce_after_shop_loop.
                     */
                    do_action('woocommerce_after_shop_loop');
                    ?>

                <?php else : ?>

                    <div class="no-products-found text-center py-12">
                        <p class="text-xl text-gray-600 mb-4"><?php esc_html_e('No products were found matching your selection.', 'fashionmen'); ?></p>
                        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="inline-block bg-black text-white px-6 py-3 rounded hover:bg-gray-800 transition-colors">
                            <?php esc_html_e('Return to Shop', 'fashionmen'); ?>
                        </a>
                    </div>

                <?php endif; ?>

            </div>

        </div>

        <?php
        /**
         * Hook: woocommerce_after_main_content.
         */
        do_action('woocommerce_after_main_content');
        ?>

    </div>
</div>

<?php
get_footer('shop');
