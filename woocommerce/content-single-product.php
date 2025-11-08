<?php
/**
 * The template for displaying product content in the single-product.php template
 * Clean design matching fig/src/pages/product-detail
 *
 * @package Aakaari
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure $product is set
if ( ! is_a( $product, 'WC_Product' ) ) {
    return;
}

$product_id = $product->get_id();
$image_ids = $product->get_gallery_image_ids();
$main_image_id = $product->get_image_id();

// Combine main image with gallery
$all_images = array_merge( array( $main_image_id ), $image_ids );
$all_images = array_unique( array_filter( $all_images ) );

// Get product data
$rating = $product->get_average_rating();
$review_count = $product->get_review_count();

// Get prices - handle both simple and variable products
$regular_price_val = $product->get_regular_price();
$sale_price_val = $product->get_sale_price();
$current_price_val = $product->get_price();

// For variable products, get price range or default price
if ( $product->is_type( 'variable' ) ) {
    $default_attributes = $product->get_default_attributes();
    $variations = $product->get_available_variations();
    $variation_data_json = wp_json_encode( $variations );
    
    // Get prices from default variation if set
    if ( ! empty( $default_attributes ) ) {
        $default_variation = $product->get_matching_variation( $default_attributes );
        if ( $default_variation ) {
            $variation_obj = wc_get_product( $default_variation );
            $current_price_val = $variation_obj->get_price();
            $regular_price_val = $variation_obj->get_regular_price();
            $sale_price_val = $variation_obj->get_sale_price();
        }
    }
    
    // Fallback if no default, just get min price
    if ( empty( $current_price_val ) ) {
        $current_price_val = $product->get_variation_price( 'min', true );
        $regular_price_val = $product->get_variation_regular_price( 'min', true );
        $sale_price_val = $product->get_variation_sale_price( 'min', true );
    }

} else {
    $variation_data_json = '[]';
}

// Ensure prices are floats
$current_price_val = floatval( $current_price_val );
$regular_price_val = floatval( $regular_price_val );
$sale_price_val = floatval( $sale_price_val );

// Use sale price as current if it's set
if ( $sale_price_val > 0 && $sale_price_val < $regular_price_val ) {
    $current_price_val = $sale_price_val;
} else {
    // If not on sale, regular price is the current price
    $current_price_val = $regular_price_val;
    $sale_price_val = 0; // No sale
}


$stock_status = $product->get_stock_status();
$stock_quantity = $product->get_stock_quantity();
$sku = $product->get_sku();
$categories = wc_get_product_category_list( $product_id, ', ' );

// Get short description
$short_desc = $product->get_short_description();
if ( empty( $short_desc ) ) {
    $short_desc = wp_trim_words( $product->get_description(), 30, '...' );
}

// Calculate discount percentage
$discount_percentage = 0;
if ( $sale_price_val && $regular_price_val && $sale_price_val < $regular_price_val ) {
    $discount_percentage = round( ( ( $regular_price_val - $sale_price_val ) / $regular_price_val ) * 100 );
}

?>

<div class="product-page">
    <div class="product-container">
        <button id="back-btn" class="back-link" onclick="window.history.back()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Products
        </button>

        <div class="product-layout">
            <div class="product-images">
                <div class="main-image-wrapper">
                    <?php if ( ! empty( $all_images ) ) : ?>
                        <?php $first_image = reset( $all_images ); ?>
                        <?php echo wp_get_attachment_image( $first_image, 'large', false, array(
                            'id' => 'main-image',
                            'alt' => $product->get_name(),
                            'data-large-src' => wp_get_attachment_image_url( $first_image, 'large' )
                        ) ); ?>
                    <?php else : ?>
                        <img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" id="main-image" data-large-src="<?php echo esc_url( wc_placeholder_img_src() ); ?>">
                    <?php endif; ?>

                    <?php if ( $discount_percentage > 0 ) : ?>
                        <span class="discount-badge" id="discount-badge">-<?php echo $discount_percentage; ?>%</span>
                    <?php else : ?>
                        <span class="discount-badge" id="discount-badge" style="display: none;"></span>
                    <?php endif; ?>
                </div>

                <?php if ( count( $all_images ) > 1 ) : ?>
                    <div class="thumbnail-list" id="thumbnail-list">
                        <?php foreach ( $all_images as $index => $image_id ) : 
                            $thumbnail_url = wp_get_attachment_image_url( $image_id, 'thumbnail' );
                            $large_url = wp_get_attachment_image_url( $image_id, 'large' );
                        ?>
                            <button
                                class="thumbnail-btn<?php echo $index === 0 ? ' active' : ''; ?>"
                                type="button"
                                data-image-src="<?php echo esc_url( $large_url ); ?>"
                                data-index="<?php echo $index; ?>">
                                <img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php echo esc_attr( $product->get_name() . ' - Image ' . ( $index + 1 ) ); ?>">
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="product-info">
                <div class="info-header">
                    <h1 id="product-name"><?php echo esc_html( $product->get_name() ); ?></h1>
                    <?php
                        // For Wishlist functionality, you need a plugin like 'YITH WooCommerce Wishlist'
                        // If installed, you can use its shortcode:
                        if ( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
                            echo do_shortcode( '[yith_wcwl_add_to_wishlist button_class="wishlist-icon" icon="none" label="" already_in_wishlist_text="" browse_wishlist_text=""]' );
                        } else {
                            // Fallback button if no plugin is active
                            echo '<button class="wishlist-icon" aria-label="Add to wishlist">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                            </button>';
                        }
                    ?>
                </div>

                <?php if ( $review_count > 0 ) : ?>
                    <div class="rating-row">
                        <div class="stars" id="product-rating-stars">
                            <?php
                            for ( $i = 1; $i <= 5; $i++ ) {
                                if ( $i <= floor( $rating ) ) {
                                    echo '<svg class="star-filled" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                                } else {
                                    echo '<svg class="star-empty" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                                }
                            }
                            ?>
                        </div>
                        <span class="rating-text" id="product-rating-text"><?php echo number_format( $rating, 1 ); ?> (<?php echo $review_count; ?> reviews)</span>
                    </div>
                <?php endif; ?>

                <div class="price-row">
                    <span class="price" id="product-price"><?php echo '₹' . number_format( $current_price_val, 2 ); ?></span>
                    <?php if ( $sale_price_val ) : ?>
                        <span class="old-price" id="product-old-price"><?php echo '₹' . number_format( $regular_price_val, 2 ); ?></span>
                    <?php else : ?>
                        <span class="old-price" id="product-old-price" style="display: none;"></span>
                    <?php endif; ?>
                </div>

                <?php if ( $short_desc ) : ?>
                    <p class="description" id="product-description"><?php echo wp_kses_post( $short_desc ); ?></p>
                <?php endif; ?>

                <?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

                <form class
="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo $product_id; ?>" data-product_variations="<?php echo esc_attr( $variation_data_json ); ?>">

                    <?php if ( $product->is_type( 'variable' ) ) : ?>
                        <div class="options-section">
                            <?php
                            $attributes = $product->get_variation_attributes();
                            foreach ( $attributes as $attribute_name => $options ) :
                                $attribute_label = wc_attribute_label( $attribute_name );
                                // Check if this attribute is a "color" attribute
                                $is_color_attr = stripos( $attribute_label, 'color' ) !== false || stripos( $attribute_label, 'colour' ) !== false;
                            ?>
                                <div class="option-row">
                                    <label><?php echo esc_html( $attribute_label ); ?>:</label>
                                    <div class="<?php echo $is_color_attr ? 'color-btns' : 'option-btns'; ?>" data-attribute-name="<?php echo esc_attr( $attribute_name ); ?>">
                                        <?php foreach ( $options as $option_slug ) :
                                            // Get the term name from the slug
                                            $term = get_term_by( 'slug', $option_slug, $attribute_name );
                                            $option_name = $term ? $term->name : $option_slug;
                                        ?>
                                            <?php if ( $is_color_attr ) : ?>
                                                <button
                                                    type="button"
                                                    class="color-btn"
                                                    data-value="<?php echo esc_attr( $option_slug ); ?>"
                                                    style="background-color: <?php echo esc_attr( strtolower( $option_name ) ); ?>"
                                                    title="<?php echo esc_attr( $option_name ); ?>"></button>
                                            <?php else : ?>
                                                <button
                                                    type="button"
                                                    class="size-btn"
                                                    data-value="<?php echo esc_attr( $option_slug ); ?>">
                                                    <?php echo esc_html( $option_name ); ?>
                                                </button>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                    <input type="hidden" name="<?php echo esc_attr( $attribute_name ); ?>" id="selected-<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>" value="">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <input type="hidden" name="variation_id" class="variation_id" value="">

                    <div class="quantity-row">
                        <label>Quantity:</label>
                        <div class="quantity-box">
                            <button type="button" class="qty-decrease">-</button>
                            <span class="quantity-display">1</span>
                            <button type="button" class="qty-increase">+</button>
                        </div>
                        <?php
                        // Hidden input for WooCommerce
                        woocommerce_quantity_input( array(
                            'min_value'   => 1,
                            'max_value'   => $product->get_max_purchase_quantity(),
                            'input_value' => 1,
                            'classes'     => array( 'input-text', 'qty', 'text' ),
                            'input_name'  => 'quantity',
                            'input_id'    => 'quantity_input',
                            'styles'      => 'display:none;', // Hide this
                        ), $product, false );
                        ?>
                        <span class="stock-text" id="stock-info">
                            <?php if ( $stock_status === 'instock' ) : ?>
                                <?php if ( $stock_quantity && !$product->is_type( 'variable' ) ) : ?>
                                    <?php echo $stock_quantity; ?> in stock
                                <?php else : ?>
                                    In stock
                                <?php endif; ?>
                            <?php else : ?>
                                Out of stock
                            <?php endif; ?>
                        </span>
                    </div>

                    <div class="action-row">
                        <button
                            type="submit"
                            name="add-to-cart"
                            value="<?php echo esc_attr( $product_id ); ?>"
                            class="add-cart-btn"
                            id="add-to-cart-btn"
                            <?php echo $stock_status !== 'instock' ? 'disabled' : ''; ?>>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            Add to Cart
                        </button>
                        <button
                            type="button"
                            class="buy-btn"
                            id="buy-now-btn"
                            <?php echo $stock_status !== 'instock' ? 'disabled' : ''; ?>>
                            Buy Now
                        </button>
                    </div>

                    <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

                </form>

                <?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

                <div class="features-row">
                    <div class="feature">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="3" width="15" height="13"></rect>
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                            <circle cx="5.5" cy="18.5" r="2.5"></circle>
                            <circle cx="18.5" cy="18.5" r="2.5"></circle>
                        </svg>
                        <span>Free shipping on orders over ₹999</span>
                    </div>
                    <div class="feature">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="1 4 1 10 7 10"></polyline>
                            <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                        </svg>
                        <span>Replacement only</span>
                    </div>
                </div>

                <div class="product-meta">
                    <?php if ( $sku ) : ?>
                        <div><span>SKU:</span> <span id="product-sku"><?php echo esc_html( $sku ); ?></span></div>
                    <?php endif; ?>
                    <?php if ( $categories ) : ?>
                        <div><span>Category:</span> <span id="product-category"><?php echo wp_kses_post( strip_tags( $categories ) ); ?></span></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="reviews-section" id="reviews-section">
            <h2>Customer Reviews</h2>

            <div class="reviews-list" id="reviews-list">
                <?php
                $comments = get_comments( array(
                    'post_id' => $product_id,
                    'status'  => 'approve',
                    'type'    => 'review',
                ) );

                if ( ! empty( $comments ) ) :
                    foreach ( $comments as $comment ) :
                        $review_rating = get_comment_meta( $comment->comment_ID, 'rating', true );
                        ?>
                        <div class="review-item">
                            <div class="review-header">
                                <div class="stars">
                                    <?php
                                    for ( $i = 1; $i <= 5; $i++ ) {
                                        if ( $i <= $review_rating ) {
                                            echo '<svg class="star-filled" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                                        } else {
                                            echo '<svg class="star-empty" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                                        }
                                    }
                                    ?>
                                </div>
                                <span class="review-author"><?php echo esc_html( $comment->comment_author ); ?></span>
                            </div>
                            <p><?php echo esc_html( $comment->comment_content ); ?></p>
                        </div>
                    <?php endforeach;
                else : ?>
                    <p>No reviews yet. Be the first to review this product!</p>
                <?php endif; ?>
            </div>

            <?php if ( comments_open() ) : ?>
            <div class="review-form-wrapper">
                <h3>Write a Review</h3>
                <?php
                $commenter = wp_get_current_commenter();
                $comment_form = array(
                    'title_reply'          => '',
                    'title_reply_before'   => '',
                    'title_reply_after'    => '',
                    'comment_notes_before' => '',
                    'comment_notes_after'  => '',
                    'fields'               => array(
                        'author' => '<p class="comment-form-author">' .
                                    '<label for="author">' . esc_html__( 'Name', 'woocommerce' ) . '&nbsp;<span class="required">*</span></label> ' .
                                    '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" required /></p>',
                        'email'  => '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'woocommerce' ) . '&nbsp;<span class="required">*</span></label> ' .
                                    '<input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" required /></p>',
                    ),
                    'label_submit'         => __( 'Submit Review', 'woocommerce' ),
                    'logged_in_as'         => '',
                    'comment_field'        => '',
                );

                // Rating input
                $comment_form['comment_field'] = '<div class="comment-form-rating">'
                    . '<label for="rating">' . esc_html__( 'Your rating', 'woocommerce' ) . ( wc_review_ratings_enabled() ? '&nbsp;<span class="required">*</span>' : '' ) . '</label>'
                    . '<div class="star-rating-selector">'
                        . '<span class="star" data-rating="1">★</span>'
                        . '<span class="star" data-rating="2">★</span>'
                        . '<span class="star" data-rating="3">★</span>'
                        . '<span class="star" data-rating="4">★</span>'
                        . '<span class="star" data-rating="5">★</span>'
                    . '</div>'
                    . '<input type="hidden" name="rating" id="rating" value="" required>'
                . '</div>';
                
                // Comment/Review text area
                $comment_form['comment_field'] .= '<p class="comment-form-comment">'
                    . '<label for="comment">' . esc_html__( 'Your review', 'woocommerce' ) . '&nbsp;<span class="required">*</span></label>'
                    . '<textarea id="comment" name="comment" cols="45" rows="8" required></textarea>'
                    . '</p>';

                // Add comment_post_ID for proper review submission
                $comment_form['comment_post_ID'] = $product_id;
                comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
                ?>
            </div>
            <?php endif; ?>

        </div>

        <?php
        // Get related products
        $related_ids = wc_get_related_products( $product_id, 4 );

        // If no related products, get products from same category
        if ( empty( $related_ids ) ) {
            $terms = get_the_terms( $product_id, 'product_cat' );
            if ( $terms && ! is_wp_error( $terms ) ) {
                $term_ids = wp_list_pluck( $terms, 'term_id' );
                $args = array(
                    'post_type'      => 'product',
                    'posts_per_page' => 4,
                    'post__not_in'   => array( $product_id ),
                    'tax_query'      => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field'    => 'term_id',
                            'terms'    => $term_ids,
                        ),
                    ),
                );
                $query = new WP_Query( $args );
                $related_ids = wp_list_pluck( $query->posts, 'ID' );
            }
        }

        if ( ! empty( $related_ids ) ) :
            ?>
            <div class="related-section" id="related-section">
                <h2>Related Products</h2>
                <div class="related-products-grid" id="related-products-grid">
                    <?php
                    // Note: Your React component used a 'ProductCarousel'.
                    // This is a simple grid. To make it a carousel, you would need
                    // to enqueue a JS library (like Swiper.js) and initialize it.
                    foreach ( $related_ids as $related_id ) :
                        $related_product = wc_get_product( $related_id );
                        if ( ! $related_product ) continue;

                        $related_image_id = $related_product->get_image_id();
                        ?>
                        <a href="<?php echo esc_url( get_permalink( $related_id ) ); ?>" class="related-product-card">
                            <div class="related-product-image">
                                <?php if ( $related_image_id ) : ?>
                                    <?php echo wp_get_attachment_image( $related_image_id, 'woocommerce_thumbnail', false, array( 'alt' => $related_product->get_name() ) ); ?>
                                <?php else : ?>
                                    <img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="<?php echo esc_attr( $related_product->get_name() ); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="related-product-info">
                                <h5><?php echo esc_html( $related_product->get_name() ); ?></h5>
                                <span class="price"><?php echo $related_product->get_price_html(); ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>