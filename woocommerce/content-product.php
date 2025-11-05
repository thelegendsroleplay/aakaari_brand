<?php
/**
 * The template for displaying product content within loops
 *
 * @package FashionMen
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
?>

<div <?php wc_product_class('product-card bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300', $product); ?>>

    <div class="product-image relative group">
        <a href="<?php echo esc_url(get_permalink()); ?>" class="block">
            <?php
            /**
             * Hook: woocommerce_before_shop_loop_item_title.
             */
            do_action('woocommerce_before_shop_loop_item_title');
            ?>
        </a>

        <!-- Quick View Button -->
        <button class="quick-view-button absolute top-4 right-4 bg-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300 hover:bg-gray-100"
                data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                aria-label="<?php esc_attr_e('Quick View', 'fashionmen'); ?>">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
        </button>

        <!-- Sale Badge -->
        <?php if ($product->is_on_sale()) : ?>
            <span class="sale-badge absolute top-4 left-4 bg-red-500 text-white text-xs font-semibold px-3 py-1 rounded-full">
                <?php esc_html_e('Sale', 'fashionmen'); ?>
            </span>
        <?php endif; ?>
    </div>

    <div class="product-info p-4">
        <?php
        /**
         * Hook: woocommerce_shop_loop_item_title.
         */
        do_action('woocommerce_shop_loop_item_title');
        ?>

        <!-- Product Category -->
        <?php
        $categories = get_the_terms($product->get_id(), 'product_cat');
        if ($categories && !is_wp_error($categories)) :
            $category = array_shift($categories);
            ?>
            <p class="product-category text-sm text-gray-500 mb-2">
                <?php echo esc_html($category->name); ?>
            </p>
        <?php endif; ?>

        <!-- Product Price -->
        <div class="product-price mb-3">
            <?php
            /**
             * Hook: woocommerce_after_shop_loop_item_title.
             */
            do_action('woocommerce_after_shop_loop_item_title');
            ?>
        </div>

        <!-- Product Rating -->
        <?php if (wc_review_ratings_enabled()) : ?>
            <div class="product-rating mb-3">
                <?php woocommerce_template_loop_rating(); ?>
            </div>
        <?php endif; ?>

        <!-- Add to Cart Button -->
        <div class="product-actions flex gap-2">
            <?php
            /**
             * Hook: woocommerce_after_shop_loop_item.
             */
            do_action('woocommerce_after_shop_loop_item');
            ?>

            <!-- Wishlist Button -->
            <?php if (function_exists('YITH_WCWL')) : ?>
                <button class="wishlist-button p-2 border border-gray-300 rounded hover:border-black transition-colors"
                        aria-label="<?php esc_attr_e('Add to wishlist', 'fashionmen'); ?>">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </button>
            <?php endif; ?>
        </div>
    </div>

</div>
