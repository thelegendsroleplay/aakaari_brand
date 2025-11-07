<?php
/**
 * The template for displaying product content within loops
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}
?>
<div <?php wc_product_class( 'product-card', $product ); ?>>
    <div class="product-card-inner">
        <!-- Product Image -->
        <div class="product-card-image">
            <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">
                <?php echo $product->get_image( 'woocommerce_thumbnail' ); ?>
            </a>

            <?php if ( $product->is_on_sale() ) : ?>
                <span class="product-badge sale-badge">
                    <?php
                    if ( $product->get_regular_price() && $product->get_sale_price() ) {
                        $discount = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
                        echo '-' . $discount . '%';
                    } else {
                        echo 'SALE';
                    }
                    ?>
                </span>
            <?php endif; ?>

            <?php if ( ! $product->is_in_stock() ) : ?>
                <span class="product-badge out-of-stock-badge">Out of Stock</span>
            <?php endif; ?>

            <?php
            // Check if product is marked as new (you can customize this logic)
            $product_date = get_the_date( 'U' );
            $days_since_published = ( time() - $product_date ) / ( 60 * 60 * 24 );
            if ( $days_since_published < 30 ) : // Less than 30 days old
            ?>
                <span class="product-badge new-badge">New</span>
            <?php endif; ?>

            <!-- Quick Actions -->
            <div class="product-card-actions">
                <?php if ( function_exists( 'YITH_WCWL' ) ) : ?>
                    <button class="product-action-btn wishlist-btn" aria-label="Add to Wishlist">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </button>
                <?php endif; ?>

                <?php if ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) : ?>
                    <?php echo apply_filters( 'woocommerce_loop_add_to_cart_link',
                        sprintf( '<a href="%s" data-quantity="%s" class="%s" %s><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="21" r="1"></circle><circle cx="19" cy="21" r="1"></circle><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path></svg></a>',
                            esc_url( $product->add_to_cart_url() ),
                            esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
                            esc_attr( isset( $args['class'] ) ? $args['class'] : 'product-action-btn add-to-cart-btn' ),
                            isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : ''
                        ),
                    $product, $args ); ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Info -->
        <div class="product-card-content">
            <div class="product-card-header">
                <h3 class="product-card-title">
                    <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">
                        <?php echo esc_html( $product->get_name() ); ?>
                    </a>
                </h3>

                <?php if ( $product->get_average_rating() ) : ?>
                    <div class="product-rating">
                        <div class="product-stars">
                            <?php
                            $rating = $product->get_average_rating();
                            for ( $i = 1; $i <= 5; $i++ ) {
                                if ( $i <= $rating ) {
                                    echo '<svg class="star-filled" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                                } else {
                                    echo '<svg class="star-empty" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                                }
                            }
                            ?>
                        </div>
                        <span class="product-review-count">(<?php echo $product->get_review_count(); ?>)</span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Price -->
            <div class="product-card-footer">
                <div class="product-price">
                    <?php echo $product->get_price_html(); ?>
                </div>

                <?php
                // Get product category
                $categories = get_the_terms( $product->get_id(), 'product_cat' );
                if ( $categories && ! is_wp_error( $categories ) ) {
                    $category = array_shift( $categories );
                    echo '<span class="product-category">' . esc_html( $category->name ) . '</span>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Product Card Styles */
.product-card {
    background: white;
    border-radius: 0.75rem;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.product-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.product-card-inner {
    display: flex;
    flex-direction: column;
}

.product-card-image {
    position: relative;
    overflow: hidden;
    aspect-ratio: 1;
    background: #f8f9fa;
}

.product-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-card-image img {
    transform: scale(1.05);
}

.product-badge {
    position: absolute;
    top: 0.75rem;
    left: 0.75rem;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 0.25rem;
    z-index: 2;
}

.sale-badge {
    background: #dc2626;
    color: white;
}

.new-badge {
    background: #10b981;
    color: white;
    left: auto;
    right: 0.75rem;
}

.out-of-stock-badge {
    background: #6b7280;
    color: white;
}

.product-card-actions {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    display: flex;
    gap: 0.5rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-card:hover .product-card-actions {
    opacity: 1;
}

.product-action-btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.product-action-btn:hover {
    background: #000;
    color: white;
}

.product-card-content {
    padding: 1rem;
}

.product-card-title {
    margin: 0 0 0.5rem;
    font-size: 1rem;
    font-weight: 600;
}

.product-card-title a {
    color: #000;
    text-decoration: none;
}

.product-card-title a:hover {
    color: #666;
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    margin-bottom: 0.5rem;
}

.product-stars {
    display: flex;
    gap: 2px;
}

.star-filled {
    color: #fbbf24;
}

.star-empty {
    color: #d1d5db;
}

.product-review-count {
    font-size: 0.75rem;
    color: #6b7280;
}

.product-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 0.5rem;
}

.product-price {
    font-size: 1.125rem;
    font-weight: 700;
    color: #000;
}

.product-category {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
}
</style>
