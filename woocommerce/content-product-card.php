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
        <a href="<?php echo esc_url($product->get_permalink()); ?>" class="product-card-link">
            <div class="product-card-image-wrapper">
                <?php
                if ($product->is_on_sale()) {
                    echo '<span class="product-card-badge sale-badge">Sale</span>';
                }

                if ($product->get_stock_status() === 'outofstock') {
                    echo '<span class="product-card-badge out-of-stock-badge">Out of Stock</span>';
                }
                ?>

                <?php echo $product->get_image('medium', array('class' => 'product-card-image')); ?>

                <div class="product-card-quick-view">
                    <button class="quick-view-btn" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                        Quick View
                    </button>
                </div>
            </div>

            <div class="product-card-content">
                <h3 class="product-card-title"><?php echo esc_html($product->get_name()); ?></h3>

                <?php if ($product->get_short_description()) : ?>
                    <p class="product-card-description">
                        <?php echo wp_trim_words($product->get_short_description(), 10, '...'); ?>
                    </p>
                <?php endif; ?>

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
        </a>
    </div>
</div>
