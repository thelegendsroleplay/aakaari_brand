<?php
/**
 * The Template for displaying product archives, including the main shop page
 *
 * @package Aakaari_Brand
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

get_header();

// Get current filters from URL
$selected_categories = isset($_GET['filter_categories']) ? explode(',', sanitize_text_field($_GET['filter_categories'])) : array();
$selected_sizes = isset($_GET['filter_sizes']) ? explode(',', sanitize_text_field($_GET['filter_sizes'])) : array();
$selected_colors = isset($_GET['filter_colors']) ? explode(',', sanitize_text_field($_GET['filter_colors'])) : array();
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 1000;
$min_rating = isset($_GET['min_rating']) ? intval($_GET['min_rating']) : 0;
$orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'popularity';

// Get available product attributes
$available_sizes = aakaari_get_available_attribute_terms('pa_size');
$available_colors = aakaari_get_available_attribute_terms('pa_color');
$product_categories = get_terms(array(
    'taxonomy' => 'product_cat',
    'hide_empty' => true,
));
?>

<div class="products-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-container">
            <h1 class="page-title">
                <?php
                if (is_shop()) {
                    echo esc_html__('Shop', 'aakaari-brand');
                } elseif (is_product_category()) {
                    single_cat_title();
                } elseif (is_product_tag()) {
                    single_tag_title();
                } else {
                    echo esc_html__('Products', 'aakaari-brand');
                }
                ?>
            </h1>
            <p class="page-subtitle">
                <?php
                if (is_product_category()) {
                    echo esc_html(category_description());
                } else {
                    echo esc_html__('Essential tees and hoodies for your wardrobe', 'aakaari-brand');
                }
                ?>
            </p>
        </div>
    </div>

    <div class="products-container">
        <!-- Sidebar Filters -->
        <aside class="filters-sidebar" id="filters-sidebar">
            <div class="filters-header">
                <h2><?php esc_html_e('Filters', 'aakaari-brand'); ?></h2>
                <button class="filters-clear-btn" id="clear-filters">
                    <?php esc_html_e('Clear All', 'aakaari-brand'); ?>
                </button>
            </div>

            <form id="product-filters-form">
                <!-- Categories -->
                <?php if (!empty($product_categories)) : ?>
                    <div class="filter-section">
                        <h3><?php esc_html_e('Categories', 'aakaari-brand'); ?></h3>
                        <div class="filter-options">
                            <?php foreach ($product_categories as $category) : ?>
                                <label class="filter-checkbox">
                                    <input
                                        type="checkbox"
                                        name="filter_categories[]"
                                        value="<?php echo esc_attr($category->slug); ?>"
                                        <?php checked(in_array($category->slug, $selected_categories)); ?>
                                    />
                                    <span class="checkbox-custom"></span>
                                    <span><?php echo esc_html($category->name); ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Price Range -->
                <div class="filter-section">
                    <h3><?php esc_html_e('Price Range', 'aakaari-brand'); ?></h3>
                    <div class="price-range">
                        <input
                            type="range"
                            id="min-price"
                            name="min_price"
                            min="0"
                            max="1000"
                            step="10"
                            value="<?php echo esc_attr($min_price); ?>"
                            class="price-slider"
                        />
                        <input
                            type="range"
                            id="max-price"
                            name="max_price"
                            min="0"
                            max="1000"
                            step="10"
                            value="<?php echo esc_attr($max_price); ?>"
                            class="price-slider"
                        />
                        <div class="price-labels">
                            <span>₹<span id="min-price-label"><?php echo esc_html($min_price); ?></span></span>
                            <span>₹<span id="max-price-label"><?php echo esc_html($max_price); ?></span></span>
                        </div>
                    </div>
                </div>

                <!-- Sizes -->
                <?php if (!empty($available_sizes)) : ?>
                    <div class="filter-section">
                        <h3><?php esc_html_e('Sizes', 'aakaari-brand'); ?></h3>
                        <div class="filter-options">
                            <?php foreach ($available_sizes as $size) : ?>
                                <label class="filter-checkbox">
                                    <input
                                        type="checkbox"
                                        name="filter_sizes[]"
                                        value="<?php echo esc_attr($size->slug); ?>"
                                        <?php checked(in_array($size->slug, $selected_sizes)); ?>
                                    />
                                    <span class="checkbox-custom"></span>
                                    <span><?php echo esc_html($size->name); ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Colors -->
                <?php if (!empty($available_colors)) : ?>
                    <div class="filter-section">
                        <h3><?php esc_html_e('Colors', 'aakaari-brand'); ?></h3>
                        <div class="color-options">
                            <?php foreach ($available_colors as $color) :
                                // Get hex color from term metadata
                                $hex_color = get_term_meta($color->term_id, 'attribute_color', true);
                                // Fallback to color name if no hex color is set
                                $bg_color = $hex_color ? $hex_color : strtolower($color->name);
                            ?>
                                <button
                                    type="button"
                                    class="color-swatch <?php echo in_array($color->slug, $selected_colors) ? 'selected' : ''; ?>"
                                    data-color="<?php echo esc_attr($color->slug); ?>"
                                    title="<?php echo esc_attr($color->name); ?>"
                                    style="background-color: <?php echo esc_attr($bg_color); ?>;"
                                >
                                    <input
                                        type="checkbox"
                                        name="filter_colors[]"
                                        value="<?php echo esc_attr($color->slug); ?>"
                                        <?php checked(in_array($color->slug, $selected_colors)); ?>
                                        style="display: none;"
                                    />
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Rating -->
                <div class="filter-section">
                    <h3><?php esc_html_e('Minimum Rating', 'aakaari-brand'); ?></h3>
                    <div class="rating-options">
                        <?php for ($i = 4; $i >= 1; $i--) : ?>
                            <label class="filter-checkbox">
                                <input
                                    type="radio"
                                    name="min_rating"
                                    value="<?php echo esc_attr($i); ?>"
                                    <?php checked($min_rating, $i); ?>
                                />
                                <span class="checkbox-custom"></span>
                                <span><?php echo esc_html($i); ?>+ Stars</span>
                            </label>
                        <?php endfor; ?>
                        <label class="filter-checkbox">
                            <input
                                type="radio"
                                name="min_rating"
                                value="0"
                                <?php checked($min_rating, 0); ?>
                            />
                            <span class="checkbox-custom"></span>
                            <span><?php esc_html_e('All Ratings', 'aakaari-brand'); ?></span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="filters-apply-btn">
                    <?php esc_html_e('Apply Filters', 'aakaari-brand'); ?>
                </button>
            </form>
        </aside>

        <!-- Main Content -->
        <main class="products-main">
            <!-- Toolbar -->
            <div class="products-toolbar">
                <button class="filters-toggle-btn" id="filters-toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="4" y1="21" x2="4" y2="14"></line>
                        <line x1="4" y1="10" x2="4" y2="3"></line>
                        <line x1="12" y1="21" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12" y2="3"></line>
                        <line x1="20" y1="21" x2="20" y2="16"></line>
                        <line x1="20" y1="12" x2="20" y2="3"></line>
                        <line x1="1" y1="14" x2="7" y2="14"></line>
                        <line x1="9" y1="8" x2="15" y2="8"></line>
                        <line x1="17" y1="16" x2="23" y2="16"></line>
                    </svg>
                    <span class="filters-toggle-text"><?php esc_html_e('Filters', 'aakaari-brand'); ?></span>
                </button>

                <div class="toolbar-info">
                    <p>
                        <?php
                        if (woocommerce_products_will_display()) {
                            $total = wc_get_loop_prop('total');
                            printf(
                                esc_html(_n('%d Product', '%d Products', $total, 'aakaari-brand')),
                                esc_html($total)
                            );
                        }
                        ?>
                    </p>
                </div>

                <div class="toolbar-sort">
                    <label for="orderby" class="sort-label"><?php esc_html_e('Sort by:', 'aakaari-brand'); ?></label>
                    <select name="orderby" id="orderby" class="orderby">
                        <option value="popularity" <?php selected($orderby, 'popularity'); ?>><?php esc_html_e('Popularity', 'aakaari-brand'); ?></option>
                        <option value="rating" <?php selected($orderby, 'rating'); ?>><?php esc_html_e('Rating', 'aakaari-brand'); ?></option>
                        <option value="date" <?php selected($orderby, 'date'); ?>><?php esc_html_e('Newest', 'aakaari-brand'); ?></option>
                        <option value="price" <?php selected($orderby, 'price'); ?>><?php esc_html_e('Price: Low to High', 'aakaari-brand'); ?></option>
                        <option value="price-desc" <?php selected($orderby, 'price-desc'); ?>><?php esc_html_e('Price: High to Low', 'aakaari-brand'); ?></option>
                    </select>
                </div>
            </div>

            <!-- Products Grid -->
            <?php if (woocommerce_product_loop()) : ?>

                <?php woocommerce_product_loop_start(); ?>

                <div class="products-grid">
                    <?php
                    while (have_posts()) {
                        the_post();
                        wc_get_template_part('content', 'product-card');
                    }
                    ?>
                </div>

                <?php woocommerce_product_loop_end(); ?>

                <?php
                /**
                 * Hook: woocommerce_after_shop_loop.
                 *
                 * @hooked woocommerce_pagination - 10
                 */
                do_action('woocommerce_after_shop_loop');
                ?>

            <?php else : ?>

                <div class="no-products">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                    </svg>
                    <h3><?php esc_html_e('No products found', 'aakaari-brand'); ?></h3>
                    <p><?php esc_html_e('Try adjusting your filters', 'aakaari-brand'); ?></p>
                    <button class="no-products-clear-btn" id="clear-filters-empty">
                        <?php esc_html_e('Clear Filters', 'aakaari-brand'); ?>
                    </button>
                </div>

            <?php endif; ?>
        </main>
    </div>

    <!-- Quick View Modal -->
    <div class="quick-view-modal" id="quickViewModal">
        <div class="quick-view-content">
            <button class="quick-view-close" id="quickViewClose">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
            <div id="quickViewContent">
                <!-- Content will be loaded via AJAX -->
                <div class="quick-view-loading">
                    <div class="quick-view-loading-spinner"></div>
                    <p>Loading product details...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
