<?php
/**
 * The template for displaying product content within loops
 * Converted from Figma ProductCard.tsx
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}

// Get product images
$image_id = $product->get_image_id();
$gallery_image_ids = $product->get_gallery_image_ids();
$hover_image_id = ! empty( $gallery_image_ids ) ? $gallery_image_ids[0] : $image_id;

// Get product categories
$categories = get_the_terms( $product->get_id(), 'product_cat' );
$category_name = '';
if ( $categories && ! is_wp_error( $categories ) ) {
    $category = array_shift( $categories );
    $category_name = $category->name;
}

// Calculate discount percentage
$discount_percentage = 0;
if ( $product->is_on_sale() && $product->get_regular_price() && $product->get_sale_price() ) {
    $discount_percentage = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
}

// Check if product is new (less than 30 days old)
$product_date = get_the_date( 'U' );
$days_since_published = ( time() - $product_date ) / ( 60 * 60 * 24 );
$is_new = $days_since_published < 30;

// Check if product is featured
$is_featured = $product->is_featured();
?>

<div <?php wc_product_class( 'product-card group', $product ); ?> onclick="window.location='<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>'">
    <!-- Image -->
    <div class="product-card-image">
        <?php echo wp_get_attachment_image( $image_id, 'woocommerce_thumbnail', false, array(
            'class' => 'default-image',
            'alt' => $product->get_name(),
        ) ); ?>

        <?php if ( $hover_image_id !== $image_id ) : ?>
            <?php echo wp_get_attachment_image( $hover_image_id, 'woocommerce_thumbnail', false, array(
                'class' => 'hover-image',
                'alt' => $product->get_name(),
                'style' => 'display: none;',
            ) ); ?>
        <?php endif; ?>

        <!-- Badges -->
        <div class="product-badges">
            <?php if ( $discount_percentage > 0 ) : ?>
                <span class="product-badge badge-sale">
                    -<?php echo esc_html( $discount_percentage ); ?>%
                </span>
            <?php endif; ?>

            <?php if ( $is_new ) : ?>
                <span class="product-badge badge-new">New</span>
            <?php endif; ?>

            <?php if ( $is_featured ) : ?>
                <span class="product-badge badge-featured">Featured</span>
            <?php endif; ?>
        </div>

        <!-- Wishlist Button -->
        <?php if ( function_exists( 'YITH_WCWL' ) ) : ?>
            <button class="product-wishlist-btn"
                    onclick="event.stopPropagation(); addToWishlist(<?php echo esc_js( $product->get_id() ); ?>)"
                    aria-label="Add to wishlist">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                </svg>
            </button>
        <?php endif; ?>

        <!-- Add to Cart Overlay -->
        <?php if ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) : ?>
            <div class="product-cart-overlay">
                <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
                   class="product-add-to-cart-btn ajax_add_to_cart add_to_cart_button"
                   data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
                   data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>"
                   data-quantity="1"
                   onclick="event.stopPropagation();"
                   rel="nofollow">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="8" cy="21" r="1"></circle>
                        <circle cx="19" cy="21" r="1"></circle>
                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                    </svg>
                    Add to Cart
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Details -->
    <div class="product-card-content">
        <?php if ( $category_name ) : ?>
            <p class="product-category"><?php echo esc_html( $category_name ); ?></p>
        <?php endif; ?>

        <h3 class="product-card-title">
            <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">
                <?php echo esc_html( $product->get_name() ); ?>
            </a>
        </h3>

        <!-- Rating -->
        <div class="product-rating">
            <div class="product-stars">
                <?php
                $rating = $product->get_average_rating();
                for ( $i = 1; $i <= 5; $i++ ) {
                    if ( $i <= floor( $rating ) ) {
                        echo '<svg class="star-filled" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                    } else {
                        echo '<svg class="star-empty" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                    }
                }
                ?>
            </div>
            <span class="product-review-count">(<?php echo esc_html( $product->get_review_count() ); ?>)</span>
        </div>

        <!-- Price -->
        <div class="product-price">
            <?php echo $product->get_price_html(); ?>
        </div>
    </div>
</div>

<script>
// Image hover effect
document.querySelectorAll('.product-card-image').forEach(function(imageContainer) {
    const defaultImage = imageContainer.querySelector('.default-image');
    const hoverImage = imageContainer.querySelector('.hover-image');

    if (hoverImage) {
        imageContainer.addEventListener('mouseenter', function() {
            defaultImage.style.display = 'none';
            hoverImage.style.display = 'block';
        });

        imageContainer.addEventListener('mouseleave', function() {
            defaultImage.style.display = 'block';
            hoverImage.style.display = 'none';
        });
    }
});

// Wishlist function
function addToWishlist(productId) {
    // Add your wishlist AJAX logic here
    console.log('Add to wishlist:', productId);
}
</script>
