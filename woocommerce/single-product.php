<?php
/**
 * The Template for displaying single products
 *
 * @package Aakaari_Brand
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

get_header();

while (have_posts()) :
    the_post();
    global $product;
?>

<div class="product-page">
    <div class="product-container">
        <!-- Back Button -->
        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="back-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Products
        </a>

        <div class="product-layout">
            <!-- Left - Images -->
            <div class="product-images">
                <?php
                $attachment_ids = $product->get_gallery_image_ids();
                $main_image = get_the_post_thumbnail_url($product->get_id(), 'large');

                // Calculate discount
                $discount = 0;
                if ($product->is_on_sale()) {
                    $regular_price = (float) $product->get_regular_price();
                    $sale_price = (float) $product->get_sale_price();
                    if ($regular_price > 0) {
                        $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
                    }
                }
                ?>

                <div class="main-image-wrapper">
                    <img id="mainProductImage" src="<?php echo esc_url($main_image ? $main_image : wc_placeholder_img_src()); ?>" alt="<?php echo esc_attr($product->get_name()); ?>">
                    <?php if ($discount > 0) : ?>
                        <span class="discount-badge">-<?php echo esc_html($discount); ?>%</span>
                    <?php endif; ?>
                </div>

                <?php if ($main_image || !empty($attachment_ids)) : ?>
                    <div class="thumbnail-list">
                        <!-- Main image thumbnail -->
                        <?php if ($main_image) : ?>
                            <button class="thumbnail-btn active" data-image="<?php echo esc_url($main_image); ?>">
                                <img src="<?php echo esc_url(get_the_post_thumbnail_url($product->get_id(), 'thumbnail')); ?>" alt="">
                            </button>
                        <?php endif; ?>

                        <!-- Gallery thumbnails -->
                        <?php foreach ($attachment_ids as $attachment_id) : ?>
                            <button class="thumbnail-btn" data-image="<?php echo esc_url(wp_get_attachment_image_url($attachment_id, 'large')); ?>">
                                <img src="<?php echo esc_url(wp_get_attachment_image_url($attachment_id, 'thumbnail')); ?>" alt="">
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right - Details -->
            <div class="product-info">
                <div class="info-header">
                    <h1><?php the_title(); ?></h1>
                    <button class="wishlist-icon" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </button>
                </div>

                <!-- Rating -->
                <?php
                $rating_count = $product->get_rating_count();
                $average_rating = $product->get_average_rating();
                ?>
                <?php if ($rating_count > 0) : ?>
                    <div class="rating-row">
                        <div class="stars">
                            <?php
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $average_rating) {
                                    echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="#fbbf24" stroke="#fbbf24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="star-filled"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                                } else {
                                    echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="star-empty"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                                }
                            }
                            ?>
                        </div>
                        <span class="rating-text"><?php echo esc_html($average_rating); ?> (<?php echo esc_html($rating_count); ?> reviews)</span>
                    </div>
                <?php endif; ?>

                <!-- Price -->
                <div class="price-row">
                    <span class="price"><?php echo $product->get_price_html(); ?></span>
                </div>

                <!-- Description -->
                <?php if ($product->get_short_description()) : ?>
                    <div class="description">
                        <?php echo wpautop($product->get_short_description()); ?>
                    </div>
                <?php endif; ?>

                <!-- Variable Product Options -->
                <?php if ($product->is_type('variable')) :
                    $attributes = $product->get_variation_attributes();
                    if (!empty($attributes)) :
                ?>
                    <div class="options-section">
                        <?php foreach ($attributes as $attribute_name => $options) : ?>
                            <div class="option-row">
                                <label><?php echo wc_attribute_label($attribute_name); ?>:</label>
                                <div class="option-btns" data-attribute="<?php echo esc_attr(sanitize_title($attribute_name)); ?>">
                                    <?php foreach ($options as $option) : ?>
                                        <button type="button" class="option-btn" data-value="<?php echo esc_attr($option); ?>">
                                            <?php echo esc_html($option); ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; endif; ?>

                <!-- Simple Product Options (Size/Color) -->
                <?php if ($product->is_type('simple')) :
                    $sizes = $product->get_attribute('size');
                    $colors = $product->get_attribute('color');
                ?>
                    <?php if ($sizes || $colors) : ?>
                        <div class="options-section">
                            <?php if ($sizes) :
                                $size_options = explode(',', $sizes);
                            ?>
                                <div class="option-row">
                                    <label>Size:</label>
                                    <div class="option-btns">
                                        <?php foreach ($size_options as $size) : ?>
                                            <button type="button" class="option-btn"><?php echo esc_html(trim($size)); ?></button>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($colors) :
                                $color_options = explode(',', $colors);
                            ?>
                                <div class="option-row">
                                    <label>Color:</label>
                                    <div class="color-btns">
                                        <?php foreach ($color_options as $color) : ?>
                                            <button type="button" class="color-btn" style="background-color: <?php echo esc_attr(strtolower(trim($color))); ?>" title="<?php echo esc_attr(trim($color)); ?>"></button>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Quantity and Stock -->
                <div class="quantity-row">
                    <label>Quantity:</label>
                    <div class="quantity-box">
                        <button type="button" class="quantity-minus">−</button>
                        <span class="quantity-value" data-product-id="<?php echo esc_attr($product->get_id()); ?>">1</span>
                        <button type="button" class="quantity-plus">+</button>
                    </div>
                    <span class="stock-text">
                        <?php
                        $stock_quantity = $product->get_stock_quantity();
                        if ($product->is_in_stock()) {
                            if ($stock_quantity) {
                                echo esc_html($stock_quantity) . ' in stock';
                            } else {
                                echo 'In stock';
                            }
                        } else {
                            echo 'Out of stock';
                        }
                        ?>
                    </span>
                </div>

                <!-- Action Buttons -->
                <div class="action-row">
                    <button class="add-cart-btn" data-product-id="<?php echo esc_attr($product->get_id()); ?>" <?php echo !$product->is_in_stock() ? 'disabled' : ''; ?>>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <?php echo $product->is_in_stock() ? 'Add to Cart' : 'Out of Stock'; ?>
                    </button>
                    <button class="buy-btn" data-product-id="<?php echo esc_attr($product->get_id()); ?>" <?php echo !$product->is_in_stock() ? 'disabled' : ''; ?>>
                        Buy Now
                    </button>
                </div>

                <!-- Features -->
                <div class="features-row">
                    <div class="feature">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="3" width="15" height="13"></rect>
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                            <circle cx="5.5" cy="18.5" r="2.5"></circle>
                            <circle cx="18.5" cy="18.5" r="2.5"></circle>
                        </svg>
                        <span>Free shipping over $100</span>
                    </div>
                    <div class="feature">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="23 4 23 10 17 10"></polyline>
                            <polyline points="1 20 1 14 7 14"></polyline>
                            <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                        </svg>
                        <span>30-day returns</span>
                    </div>
                </div>

                <!-- Product Meta -->
                <div class="product-meta">
                    <?php if ($product->get_sku()) : ?>
                        <div><span>SKU:</span> <?php echo esc_html($product->get_sku()); ?></div>
                    <?php endif; ?>
                    <?php
                    $categories = wp_get_post_terms($product->get_id(), 'product_cat');
                    if (!empty($categories)) :
                    ?>
                        <div><span>Category:</span> <?php echo esc_html($categories[0]->name); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <?php
        $reviews = get_comments(array(
            'post_id' => $product->get_id(),
            'status' => 'approve',
            'type' => 'review'
        ));
        if (!empty($reviews)) :
        ?>
            <div class="reviews-section">
                <h2>Customer Reviews</h2>
                <div class="reviews-list">
                    <?php foreach ($reviews as $review) :
                        $rating = get_comment_meta($review->comment_ID, 'rating', true);
                    ?>
                        <div class="review-item">
                            <div class="review-header">
                                <div class="stars">
                                    <?php for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $rating) {
                                            echo '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="#fbbf24" stroke="#fbbf24" stroke-width="2" class="star-filled"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                                        } else {
                                            echo '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="2" class="star-empty"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                                        }
                                    } ?>
                                </div>
                                <span class="review-author"><?php echo esc_html($review->comment_author); ?></span>
                            </div>
                            <h4><?php echo esc_html(get_comment_meta($review->comment_ID, 'review_title', true)); ?></h4>
                            <p><?php echo esc_html($review->comment_content); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Related Products -->
        <?php
        $related_products = wc_get_related_products($product->get_id(), 4);
        if (!empty($related_products)) :
        ?>
            <div class="related-section">
                <h2>Related Products</h2>
                <div class="product-carousel" data-carousel="related">
                    <?php
                    foreach ($related_products as $related_product_id) {
                        $related_product = wc_get_product($related_product_id);
                        if ($related_product) {
                            global $product;
                            $product = $related_product;
                            wc_get_template_part('content', 'product-card');
                        }
                    }
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
endwhile;

get_footer();
