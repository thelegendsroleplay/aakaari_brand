<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * @package Aakaari
 */

defined('ABSPATH') || exit;

get_header('shop');

/**
 * Hook: woocommerce_before_main_content.
 */
do_action('woocommerce_before_main_content');

while (have_posts()) :
    the_post();

    global $product;
    $product_id = $product->get_id();
    $gallery_images = aakaari_get_product_gallery_images($product_id);
    $is_customizable = aakaari_has_customization_options($product_id);
    $rating_summary = aakaari_get_product_rating_summary($product_id);
    ?>

    <div class="product-detail-container">

        <!-- Back Button -->
        <button class="product-back-button" onclick="history.back()">
            ← <?php esc_html_e('Back to Shop', 'aakaari'); ?>
        </button>

        <div class="product-layout">

            <!-- Product Images -->
            <div class="product-image-section">
                <div class="product-image-gallery">
                    <!-- Main Image -->
                    <div class="product-main-image">
                        <?php
                        if (!empty($gallery_images)) {
                            echo '<img src="' . esc_url($gallery_images[0]['url']) . '" alt="' . esc_attr($product->get_name()) . '">';
                        } else {
                            echo woocommerce_get_product_thumbnail('full');
                        }
                        ?>
                    </div>

                    <!-- Thumbnails -->
                    <?php if (count($gallery_images) > 1) : ?>
                        <div class="product-thumbnails">
                            <?php foreach ($gallery_images as $index => $image) : ?>
                                <div class="product-image-thumbnail <?php echo $index === 0 ? 'active' : ''; ?>"
                                     data-image-url="<?php echo esc_url($image['url']); ?>">
                                    <img src="<?php echo esc_url($image['thumb']); ?>" alt="<?php echo esc_attr($product->get_name()); ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Info -->
            <div class="product-info-section">

                <!-- Product Header -->
                <div class="product-header">
                    <div>
                        <?php
                        $categories = wc_get_product_category_list($product_id);
                        if ($categories) :
                        ?>
                            <p class="product-category"><?php echo wp_kses_post($categories); ?></p>
                        <?php endif; ?>

                        <h1 class="product-title"><?php the_title(); ?></h1>
                    </div>

                    <?php if ($is_customizable) : ?>
                        <span class="product-badge">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                            </svg>
                            <?php esc_html_e('Customizable', 'aakaari'); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Rating -->
                <?php if ($rating_summary['count'] > 0) : ?>
                    <div class="product-rating">
                        <div class="product-stars">
                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                <span class="product-star <?php echo $i <= $rating_summary['average'] ? 'filled' : 'empty'; ?>">★</span>
                            <?php endfor; ?>
                        </div>
                        <span class="product-rating-text">
                            <?php echo esc_html($rating_summary['average']); ?>
                            (<?php echo esc_html($rating_summary['count']); ?>
                            <?php echo _n('review', 'reviews', $rating_summary['count'], 'aakaari'); ?>)
                        </span>
                    </div>
                <?php endif; ?>

                <!-- Price -->
                <div class="product-price" data-base-price="<?php echo esc_attr($product->get_price()); ?>">
                    <?php echo $product->get_price_html(); ?>
                </div>

                <!-- Description -->
                <div class="product-description">
                    <?php echo wp_kses_post($product->get_short_description()); ?>
                </div>

                <!-- Add to Cart Form -->
                <form class="cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data'>

                    <?php
                    // Size Selection
                    $available_variations = $product->is_type('variable') ? $product->get_available_variations() : array();
                    $attributes = $product->get_attributes();

                    if (isset($attributes['pa_size'])) :
                        $size_terms = wc_get_product_terms($product_id, 'pa_size', array('fields' => 'all'));
                    ?>
                        <div class="product-option">
                            <label class="product-option-label"><?php esc_html_e('Size', 'aakaari'); ?></label>
                            <div class="product-sizes">
                                <?php foreach ($size_terms as $size) : ?>
                                    <button type="button" class="product-size-button" data-size="<?php echo esc_attr($size->slug); ?>">
                                        <?php echo esc_html($size->name); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                            <input type="hidden" name="product-size" value="">
                        </div>
                    <?php endif; ?>

                    <?php
                    // Color Selection
                    if (isset($attributes['pa_color'])) :
                        $color_terms = wc_get_product_terms($product_id, 'pa_color', array('fields' => 'all'));
                    ?>
                        <div class="product-option">
                            <label class="product-option-label">
                                <?php esc_html_e('Color:', 'aakaari'); ?>
                                <span class="selected-color-name"></span>
                            </label>
                            <div class="product-colors">
                                <?php foreach ($color_terms as $color) : ?>
                                    <button type="button"
                                            class="product-color-swatch"
                                            data-color="<?php echo esc_attr($color->slug); ?>"
                                            style="background-color: <?php echo esc_attr($color->slug); ?>"
                                            title="<?php echo esc_attr($color->name); ?>">
                                    </button>
                                <?php endforeach; ?>
                            </div>
                            <input type="hidden" name="product-color" value="">
                        </div>
                    <?php endif; ?>

                    <!-- Quantity -->
                    <div class="product-option">
                        <label class="product-option-label"><?php esc_html_e('Quantity', 'aakaari'); ?></label>
                        <div class="product-quantity">
                            <button type="button" class="product-quantity-button product-quantity-decrease">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </button>
                            <input type="number" class="product-quantity-input" name="quantity" value="1" min="1" max="<?php echo esc_attr($product->get_stock_quantity() ?: 999); ?>">
                            <button type="button" class="product-quantity-button product-quantity-increase">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Customization Panel -->
                    <?php if ($is_customizable) : ?>
                        <div class="product-customization-panel">
                            <div class="product-customization-header">
                                <span><?php esc_html_e('Make it yours with customization', 'aakaari'); ?></span>
                                <span class="product-customization-price" data-price="0">+$0.00</span>
                            </div>
                            <button type="button" class="product-customization-button">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                                </svg>
                                <?php esc_html_e('Add Customization', 'aakaari'); ?>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Add to Cart Button -->
                    <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product_id); ?>" class="product-add-to-cart" <?php echo !$product->is_in_stock() ? 'disabled' : ''; ?>>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <?php echo $product->is_in_stock() ? esc_html__('Add to Cart', 'aakaari') : esc_html__('Out of Stock', 'aakaari'); ?>
                    </button>

                </form>

                <!-- Product Features -->
                <div class="product-features">
                    <div class="product-feature">✓ <?php esc_html_e('Free shipping on orders over $100', 'aakaari'); ?></div>
                    <div class="product-feature">✓ <?php esc_html_e('30-day return policy', 'aakaari'); ?></div>
                    <div class="product-feature">✓ <?php esc_html_e('Secure checkout', 'aakaari'); ?></div>
                </div>

            </div><!-- .product-info-section -->

        </div><!-- .product-layout -->

        <!-- Product Tabs -->
        <div class="product-tabs">
            <?php
            /**
             * Hook: woocommerce_after_single_product_summary.
             */
            do_action('woocommerce_after_single_product_summary');
            ?>
        </div>

        <!-- Related Products -->
        <?php
        $related_products = aakaari_get_related_products($product_id, 4);
        if (!empty($related_products)) :
        ?>
            <div class="product-related">
                <h2 class="product-related-title"><?php esc_html_e('You May Also Like', 'aakaari'); ?></h2>
                <div class="product-related-grid">
                    <?php foreach ($related_products as $related_product) :
                        $related_product_obj = wc_get_product($related_product->ID);
                    ?>
                        <div class="product-related-item" data-url="<?php echo esc_url(get_permalink($related_product->ID)); ?>">
                            <div class="product-related-image">
                                <?php echo $related_product_obj->get_image('medium'); ?>
                            </div>
                            <h3 class="product-related-name"><?php echo esc_html($related_product->post_title); ?></h3>
                            <div class="product-related-price"><?php echo $related_product_obj->get_price_html(); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Recently Viewed Products -->
        <?php
        $recently_viewed = aakaari_get_recently_viewed_products(4);
        if (!empty($recently_viewed)) :
        ?>
            <div class="product-related">
                <h2 class="product-related-title"><?php esc_html_e('Recently Viewed', 'aakaari'); ?></h2>
                <div class="product-related-grid">
                    <?php foreach ($recently_viewed as $viewed_product) :
                        $viewed_product_obj = wc_get_product($viewed_product->ID);
                    ?>
                        <div class="product-related-item" data-url="<?php echo esc_url(get_permalink($viewed_product->ID)); ?>">
                            <div class="product-related-image">
                                <?php echo $viewed_product_obj->get_image('medium'); ?>
                            </div>
                            <h3 class="product-related-name"><?php echo esc_html($viewed_product->post_title); ?></h3>
                            <div class="product-related-price"><?php echo $viewed_product_obj->get_price_html(); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div><!-- .product-detail-container -->

    <?php
    // Update product views
    aakaari_update_product_views($product_id);
    ?>

<?php endwhile; // end of the loop. ?>

<?php
/**
 * Hook: woocommerce_after_main_content.
 */
do_action('woocommerce_after_main_content');

get_footer('shop');
