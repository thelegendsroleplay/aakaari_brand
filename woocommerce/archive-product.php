<?php
/**
 * The Template for displaying product archives, including the main shop page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * @package Aakaari
 */

defined('ABSPATH') || exit;

get_header('shop');

/**
 * Hook: woocommerce_before_main_content.
 */
do_action('woocommerce_before_main_content');

?>

<div class="shop-container">

    <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
        <div class="shop-header">
            <h1 class="shop-title"><?php woocommerce_page_title(); ?></h1>

            <div class="shop-controls">
                <div class="shop-product-count">
                    <?php
                    $total = wc_get_loop_prop('total');
                    echo esc_html($total) . ' ' . _n('product', 'products', $total, 'aakaari');
                    ?>
                </div>

                <div class="shop-controls-right">
                    <!-- Mobile Filter Toggle -->
                    <button class="shop-filter-toggle">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="4" y1="6" x2="20" y2="6"></line>
                            <line x1="4" y1="12" x2="20" y2="12"></line>
                            <line x1="4" y1="18" x2="20" y2="18"></line>
                        </svg>
                        Filters
                    </button>

                    <!-- Sort Dropdown -->
                    <select class="shop-sort-dropdown">
                        <option value="featured"><?php esc_html_e('Featured', 'aakaari'); ?></option>
                        <option value="price-low"><?php esc_html_e('Price: Low to High', 'aakaari'); ?></option>
                        <option value="price-high"><?php esc_html_e('Price: High to Low', 'aakaari'); ?></option>
                        <option value="newest"><?php esc_html_e('Newest', 'aakaari'); ?></option>
                        <option value="rating"><?php esc_html_e('Top Rated', 'aakaari'); ?></option>
                    </select>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="shop-layout">

        <!-- Sidebar Filters -->
        <aside class="shop-filter-panel">
            <button class="shop-filter-close">&times;</button>

            <?php
            // Categories Filter
            $categories = aakaari_get_product_categories();
            if (!empty($categories)) :
            ?>
            <div class="shop-filter-section">
                <h3 class="shop-filter-title"><?php esc_html_e('Categories', 'aakaari'); ?></h3>
                <div class="shop-filter-options">
                    <?php foreach ($categories as $category) : ?>
                        <label class="shop-filter-checkbox">
                            <input type="checkbox" data-filter="category" value="<?php echo esc_attr($category->slug); ?>">
                            <span><?php echo esc_html($category->name); ?> (<?php echo esc_html($category->count); ?>)</span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Price Range Filter -->
            <div class="shop-filter-section">
                <h3 class="shop-filter-title"><?php esc_html_e('Price Range', 'aakaari'); ?></h3>
                <div class="shop-price-range">
                    <input type="range" id="price-min" min="0" max="500" value="0" step="10">
                    <input type="range" id="price-max" min="0" max="500" value="500" step="10">
                    <div class="shop-price-values">
                        <span id="price-min-value">$0</span>
                        <span id="price-max-value">$500</span>
                    </div>
                </div>
            </div>

            <?php
            // Size Filter
            $sizes = aakaari_get_product_sizes();
            if (!empty($sizes)) :
            ?>
            <div class="shop-filter-section">
                <h3 class="shop-filter-title"><?php esc_html_e('Size', 'aakaari'); ?></h3>
                <div class="shop-size-options">
                    <?php foreach ($sizes as $size) : ?>
                        <button class="shop-size-button" data-size="<?php echo esc_attr($size->slug); ?>">
                            <?php echo esc_html($size->name); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php
            // Color Filter
            $colors = aakaari_get_product_colors();
            if (!empty($colors)) :
            ?>
            <div class="shop-filter-section">
                <h3 class="shop-filter-title"><?php esc_html_e('Color', 'aakaari'); ?></h3>
                <div class="shop-color-options">
                    <?php foreach ($colors as $color) : ?>
                        <button
                            class="shop-color-swatch"
                            data-color="<?php echo esc_attr($color->slug); ?>"
                            style="background-color: <?php echo esc_attr($color->slug); ?>"
                            title="<?php echo esc_attr($color->name); ?>"
                            aria-label="<?php echo esc_attr($color->name); ?>">
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Customizable Filter -->
            <div class="shop-filter-section">
                <label class="shop-filter-checkbox">
                    <input type="checkbox" name="customizable">
                    <span><?php esc_html_e('Customizable Only', 'aakaari'); ?></span>
                </label>
            </div>

            <!-- Clear Filters -->
            <button class="shop-clear-filters"><?php esc_html_e('Clear Filters', 'aakaari'); ?></button>
        </aside>

        <!-- Product Grid -->
        <div class="shop-main-content">
            <?php
            if (woocommerce_product_loop()) {

                /**
                 * Hook: woocommerce_before_shop_loop.
                 */
                do_action('woocommerce_before_shop_loop');

                echo '<div class="shop-product-grid">';

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

                echo '</div>';

                /**
                 * Hook: woocommerce_after_shop_loop.
                 */
                do_action('woocommerce_after_shop_loop');
            } else {
                /**
                 * Hook: woocommerce_no_products_found.
                 */
                do_action('woocommerce_no_products_found');

                echo '<div class="shop-no-products">';
                echo '<h3>' . esc_html__('No products found', 'aakaari') . '</h3>';
                echo '<p>' . esc_html__('Try adjusting your filters to find what you\'re looking for.', 'aakaari') . '</p>';
                echo '</div>';
            }
            ?>
        </div>

    </div><!-- .shop-layout -->

</div><!-- .shop-container -->

<!-- Filter Overlay for Mobile -->
<div class="shop-filter-overlay"></div>

<?php
/**
 * Hook: woocommerce_after_main_content.
 */
do_action('woocommerce_after_main_content');

/**
 * Hook: woocommerce_sidebar.
 */
do_action('woocommerce_sidebar');

get_footer('shop');
