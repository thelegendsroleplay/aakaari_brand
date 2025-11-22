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

                <!-- Short Description -->
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
                        <?php foreach ($attributes as $attribute_name => $options) :
                            $attribute_slug = sanitize_title($attribute_name);
                            $is_color_attribute = (strpos($attribute_slug, 'color') !== false || strpos($attribute_slug, 'pa_color') !== false);
                        ?>
                            <div class="option-row">
                                <label><?php echo wc_attribute_label($attribute_name); ?>:</label>
                                <?php if ($is_color_attribute) : ?>
                                    <div class="color-btns" data-attribute="<?php echo esc_attr($attribute_slug); ?>">
                                        <?php foreach ($options as $option) :
                                            // Get term object to retrieve color metadata
                                            $term = get_term_by('slug', sanitize_title($option), $attribute_name);
                                            if (!$term) {
                                                $term = get_term_by('name', $option, $attribute_name);
                                            }
                                            $hex_color = '';
                                            if ($term) {
                                                $hex_color = get_term_meta($term->term_id, 'attribute_color', true);
                                            }
                                            // Fallback to color name if no hex color is set
                                            $bg_color = $hex_color ? $hex_color : strtolower($option);
                                        ?>
                                            <button type="button" class="color-btn"
                                                    style="background-color: <?php echo esc_attr($bg_color); ?>"
                                                    title="<?php echo esc_attr($option); ?>"
                                                    data-value="<?php echo esc_attr($option); ?>"></button>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else : ?>
                                    <div class="option-btns" data-attribute="<?php echo esc_attr($attribute_slug); ?>">
                                        <?php foreach ($options as $option) :
                                            // Get term object to retrieve proper name
                                            $term = get_term_by('slug', sanitize_title($option), $attribute_name);
                                            if (!$term) {
                                                $term = get_term_by('name', $option, $attribute_name);
                                            }
                                            $display_name = $term ? $term->name : $option;
                                        ?>
                                            <button type="button" class="option-btn" data-value="<?php echo esc_attr($option); ?>">
                                                <?php echo esc_html($display_name); ?>
                                            </button>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
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
                                        <?php foreach ($color_options as $color) :
                                            $color_name = trim($color);
                                            // Get term object to retrieve color metadata
                                            $term = get_term_by('name', $color_name, 'pa_color');
                                            if (!$term) {
                                                $term = get_term_by('slug', sanitize_title($color_name), 'pa_color');
                                            }
                                            $hex_color = '';
                                            if ($term) {
                                                $hex_color = get_term_meta($term->term_id, 'attribute_color', true);
                                            }
                                            // Fallback to color name if no hex color is set
                                            $bg_color = $hex_color ? $hex_color : strtolower($color_name);
                                        ?>
                                            <button type="button" class="color-btn"
                                                    style="background-color: <?php echo esc_attr($bg_color); ?>"
                                                    title="<?php echo esc_attr($color_name); ?>"></button>
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
                        <span>Free shipping over ₹499</span>
                    </div>
                    <div class="feature">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="23 4 23 10 17 10"></polyline>
                            <polyline points="1 20 1 14 7 14"></polyline>
                            <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                        </svg>
                        <span>7 days exchange</span>
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

        <!-- Tabs Section -->
        <div class="product-tabs-section">
            <div class="tabs-header">
                <button class="tab-btn active" data-tab="description">Product Description</button>
                <button class="tab-btn" data-tab="reviews">Reviews</button>
                <button class="tab-btn" data-tab="size-chart">Size Chart</button>
            </div>

            <div class="tabs-content">
                <!-- Description Tab -->
                <div class="tab-content active" id="tab-description">
                    <div class="description-content">
                        <?php
                        if ($product->get_description()) {
                            echo wpautop($product->get_description());
                        } else {
                            echo '<p>No product description available.</p>';
                        }
                        ?>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div class="tab-content" id="tab-reviews">
                    <?php
                    $reviews = get_comments(array(
                        'post_id' => $product->get_id(),
                        'status' => 'approve',
                        'type' => 'review'
                    ));
                    ?>

                    <?php if (!empty($reviews)) : ?>
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
                                        <span class="review-date"><?php echo esc_html(date('F j, Y', strtotime($review->comment_date))); ?></span>
                                    </div>
                                    <p><?php echo esc_html($review->comment_content); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <p class="no-reviews">No reviews yet. Be the first to review this product!</p>
                    <?php endif; ?>

                    <!-- Add Review Form -->
                    <?php if (comments_open()) : ?>
                        <div class="review-form-wrapper">
                            <h3>Add Your Review</h3>
                            <form class="review-form" id="reviewForm">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="review-email">Email *</label>
                                        <input type="email" id="review-email" name="email" required <?php if (is_user_logged_in()) : ?>value="<?php echo esc_attr(wp_get_current_user()->user_email); ?>" readonly<?php endif; ?>>
                                    </div>
                                    <div class="form-group">
                                        <label for="review-name">Name *</label>
                                        <input type="text" id="review-name" name="name" required <?php if (is_user_logged_in()) : ?>value="<?php echo esc_attr(wp_get_current_user()->display_name); ?>" readonly<?php endif; ?>>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Your Rating *</label>
                                    <div class="rating-input">
                                        <?php for ($i = 5; $i >= 1; $i--) : ?>
                                            <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" required>
                                            <label for="star<?php echo $i; ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                </svg>
                                            </label>
                                        <?php endfor; ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="review-comment">Your Review *</label>
                                    <textarea id="review-comment" name="comment" rows="5" required></textarea>
                                </div>

                                <input type="hidden" name="product_id" value="<?php echo esc_attr($product->get_id()); ?>">

                                <button type="submit" class="submit-review-btn">Submit Review</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Size Chart Tab -->
                <div class="tab-content" id="tab-size-chart">
                    <div class="size-chart-content">
                        <h3>Size Guide</h3>
                        <table class="size-chart-table">
                            <thead>
                                <tr>
                                    <th>Size</th>
                                    <th>Chest (inches)</th>
                                    <th>Waist (inches)</th>
                                    <th>Hips (inches)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>XS</td>
                                    <td>32-34</td>
                                    <td>24-26</td>
                                    <td>34-36</td>
                                </tr>
                                <tr>
                                    <td>S</td>
                                    <td>34-36</td>
                                    <td>26-28</td>
                                    <td>36-38</td>
                                </tr>
                                <tr>
                                    <td>M</td>
                                    <td>36-38</td>
                                    <td>28-30</td>
                                    <td>38-40</td>
                                </tr>
                                <tr>
                                    <td>L</td>
                                    <td>38-40</td>
                                    <td>30-32</td>
                                    <td>40-42</td>
                                </tr>
                                <tr>
                                    <td>XL</td>
                                    <td>40-42</td>
                                    <td>32-34</td>
                                    <td>42-44</td>
                                </tr>
                                <tr>
                                    <td>XXL</td>
                                    <td>42-44</td>
                                    <td>34-36</td>
                                    <td>44-46</td>
                                </tr>
                            </tbody>
                        </table>
                        <p class="size-chart-note">All measurements are approximate. For the best fit, we recommend measuring yourself and comparing to the size chart above.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <?php
        // Get related products
        $related_products = wc_get_related_products($product->get_id(), 4);

        // If no related products, get random products
        if (empty($related_products)) {
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => 4,
                'orderby'        => 'rand',
                'post__not_in'   => array($product->get_id()),
                'post_status'    => 'publish',
            );
            $random_products = get_posts($args);
            $related_products = wp_list_pluck($random_products, 'ID');
        }

        if (!empty($related_products)) :
        ?>
            <div class="related-section">
                <h2>Related Products</h2>
                <div class="product-carousel" data-carousel="related">
                    <?php
                    foreach ($related_products as $related_product_id) {
                        $related_product = wc_get_product($related_product_id);
                        if ($related_product) {
                            $old_product = $product;
                            $product = $related_product;
                            wc_get_template_part('content', 'product-card');
                            $product = $old_product;
                        }
                    }
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
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

<?php
endwhile;

get_footer();
