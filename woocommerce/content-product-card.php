<?php
/**
 * Product Card Template
 *
 * @package Aakaari_Brand
 */

if (!defined('ABSPATH')) {
    exit;
}

global $product;

// Ensure visibility
if (empty($product) || !$product->is_visible()) {
    return;
}
?>

<div class="product-card" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
    <div class="product-card-inner">
        <div class="product-card-image-wrapper">
            <a href="<?php echo esc_url($product->get_permalink()); ?>" class="product-card-image-link">
                <?php
                if ($product->is_on_sale()) {
                    echo '<span class="product-card-badge sale-badge">Sale</span>';
                }

                if ($product->get_stock_status() === 'outofstock') {
                    echo '<span class="product-card-badge out-of-stock-badge">Out of Stock</span>';
                }
                ?>

                <?php echo $product->get_image('medium', array('class' => 'product-card-image')); ?>
            </a>

            <div class="product-card-quick-view">
                <button class="quick-view-btn" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                    Quick View
                </button>
            </div>
        </div>

        <div class="product-card-content">
            <a href="<?php echo esc_url($product->get_permalink()); ?>" class="product-card-link">
                <h3 class="product-card-title"><?php echo esc_html($product->get_name()); ?></h3>
            </a>

            <?php
            $rating_count = $product->get_rating_count();
            $average_rating = $product->get_average_rating();
            ?>
            <div class="product-card-rating">
                <div class="product-card-stars">
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $average_rating) {
                            echo '<span class="star">★</span>';
                        } else {
                            echo '<span class="star empty">★</span>';
                        }
                    }
                    ?>
                </div>
                <?php if ($rating_count > 0) : ?>
                    <span class="product-card-rating-count">(<?php echo esc_html($rating_count); ?>)</span>
                <?php else : ?>
                    <span class="product-card-rating-count">(0)</span>
                <?php endif; ?>
            </div>

            <div class="product-card-footer">
                <div class="product-card-price">
                    <?php echo $product->get_price_html(); ?>
                </div>

                <?php if ($product->is_in_stock()) : ?>
                    <button class="product-card-add-to-cart"
                            data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                            data-product-type="<?php echo esc_attr($product->get_type()); ?>">
                        Add to Cart
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
