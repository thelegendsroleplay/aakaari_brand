<?php
/**
 * The template for displaying product content in the single-product.php template
 * Custom design from main branch - Fully functional with WooCommerce
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
$regular_price = $product->get_regular_price();
$sale_price = $product->get_sale_price();

// For variable products, get price range or default price
if ( $product->is_type( 'variable' ) ) {
	$current_price = $product->get_price();
	// If no price set, get from first variation
	if ( empty( $current_price ) ) {
		$variations = $product->get_available_variations();
		if ( ! empty( $variations ) ) {
			$first_variation = reset( $variations );
			$current_price = $first_variation['display_price'];
			$regular_price = $first_variation['display_regular_price'];
		}
	}
} else {
	$current_price = $sale_price ? $sale_price : $regular_price;
}

// Ensure prices are floats
$current_price = floatval( $current_price );
$regular_price = floatval( $regular_price );
$sale_price = floatval( $sale_price );

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
if ( $sale_price && $regular_price && $sale_price < $regular_price ) {
	$discount_percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
}

// Get attributes for size/color if variable product
$sizes = array();
$colors = array();
$size_attribute_name = '';
$color_attribute_name = '';

if ( $product->is_type( 'variable' ) ) {
	$attributes = $product->get_variation_attributes();
	foreach ( $attributes as $attribute_name => $options ) {
		$attribute_label = wc_attribute_label( $attribute_name );
		if ( stripos( $attribute_label, 'size' ) !== false ) {
			$sizes = $options;
			$size_attribute_name = $attribute_name;
		} elseif ( stripos( $attribute_label, 'color' ) !== false || stripos( $attribute_label, 'colour' ) !== false ) {
			$colors = $options;
			$color_attribute_name = $attribute_name;
		}
	}
}
?>

<!-- Product Detail Page - WooCommerce Integration - Amazon/Flipkart Inspired -->
<div class="product-page">
	<div class="product-container">
		<!-- Breadcrumb Navigation -->
		<nav class="breadcrumb">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
			<span class="separator">›</span>
			<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>">Products</a>
			<?php if ( $categories ) : ?>
				<span class="separator">›</span>
				<span><?php echo wp_kses_post( strip_tags( $categories ) ); ?></span>
			<?php endif; ?>
		</nav>

		<div class="product-layout">
			<div class="product-images">
				<div class="main-image-wrapper">
					<?php if ( ! empty( $all_images ) ) : ?>
						<?php $first_image = reset( $all_images ); ?>
						<?php echo wp_get_attachment_image( $first_image, 'large', false, array(
							'id' => 'main-image',
							'alt' => $product->get_name(),
							'class' => 'zoomable-image'
						) ); ?>
					<?php else : ?>
						<img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" id="main-image" class="zoomable-image">
					<?php endif; ?>

					<?php if ( $discount_percentage > 0 ) : ?>
						<span class="discount-badge" id="discount-badge"><?php echo $discount_percentage; ?>% OFF</span>
					<?php endif; ?>

					<!-- Zoom lens for image zoom effect -->
					<div class="zoom-lens" id="zoom-lens"></div>
				</div>

				<?php if ( count( $all_images ) > 1 ) : ?>
					<div class="thumbnail-list" id="thumbnail-list">
						<?php foreach ( $all_images as $index => $image_id ) : ?>
							<button
								class="thumbnail-btn<?php echo $index === 0 ? ' active' : ''; ?>"
								type="button"
								data-image="<?php echo esc_url( wp_get_attachment_image_url( $image_id, 'large' ) ); ?>"
								data-index="<?php echo $index; ?>">
								<?php echo wp_get_attachment_image( $image_id, 'thumbnail', false, array(
									'alt' => $product->get_name() . ' - Image ' . ( $index + 1 )
								) ); ?>
							</button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="product-info">
				<div class="info-header">
					<div class="title-section">
						<span class="brand-badge">Aakaari Brand</span>
						<h1 id="product-name"><?php echo esc_html( $product->get_name() ); ?></h1>
					</div>
					<div class="header-actions">
						<button
							class="wishlist-icon"
							id="wishlist-btn"
							data-product-id="<?php echo esc_attr( $product_id ); ?>"
							aria-label="Add to wishlist">
							<svg id="wishlist-icon-svg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
							</svg>
						</button>
						<button class="share-icon" id="share-btn" aria-label="Share product">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<circle cx="18" cy="5" r="3"></circle>
								<circle cx="6" cy="12" r="3"></circle>
								<circle cx="18" cy="19" r="3"></circle>
								<line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
								<line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
							</svg>
						</button>
					</div>
				</div>

				<?php if ( $review_count > 0 ) : ?>
					<div class="rating-row">
						<div class="rating-badge">
							<span class="rating-value"><?php echo number_format( $rating, 1 ); ?> <svg class="star-icon" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg></span>
						</div>
						<span class="rating-text" id="product-rating-text"><?php echo number_format( $review_count ); ?> Ratings & <?php echo $review_count; ?> Reviews</span>
					</div>
				<?php endif; ?>

				<div class="price-section">
					<div class="price-row">
						<span class="price" id="product-price">₹<?php echo number_format( $current_price, 2 ); ?></span>
						<?php if ( $sale_price ) : ?>
							<span class="old-price" id="product-old-price">₹<?php echo number_format( $regular_price, 2 ); ?></span>
							<span class="savings-text"><?php echo $discount_percentage; ?>% off</span>
						<?php endif; ?>
					</div>
					<p class="tax-text">Inclusive of all taxes</p>
				</div>

				<!-- Available Offers Section -->
				<div class="offers-section">
					<h3 class="section-title">Available Offers</h3>
					<div class="offer-list">
						<div class="offer-item">
							<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<rect x="3" y="8" width="18" height="4" rx="1"></rect>
								<path d="M12 8v13"></path>
								<path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"></path>
								<path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"></path>
							</svg>
							<div class="offer-content">
								<strong>Bank Offer:</strong> 10% instant discount on Bank Debit Cards
							</div>
						</div>
						<div class="offer-item">
							<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
								<line x1="1" y1="10" x2="23" y2="10"></line>
							</svg>
							<div class="offer-content">
								<strong>Special Offer:</strong> Get extra 5% off (price inclusive of discount)
							</div>
						</div>
						<div class="offer-item">
							<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<rect x="1" y="3" width="15" height="13"></rect>
								<polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
								<circle cx="5.5" cy="18.5" r="2.5"></circle>
								<circle cx="18.5" cy="18.5" r="2.5"></circle>
							</svg>
							<div class="offer-content">
								<strong>No Cost EMI:</strong> Available on orders above ₹3000
							</div>
						</div>
					</div>
				</div>

				<!-- Product Highlights -->
				<?php if ( $short_desc ) : ?>
				<div class="highlights-section">
					<h3 class="section-title">Product Highlights</h3>
					<ul class="highlights-list">
						<?php
						// Convert short description to bullet points
						$highlights = explode( '.', strip_tags( $short_desc ) );
						foreach ( $highlights as $highlight ) {
							$highlight = trim( $highlight );
							if ( ! empty( $highlight ) ) {
								echo '<li>' . esc_html( $highlight ) . '</li>';
							}
						}
						?>
					</ul>
				</div>
				<?php endif; ?>

				<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

				<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>

					<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

					<?php if ( $product->is_type( 'variable' ) && ! empty( $sizes ) ) : ?>
					<div class="options-section">
						<div class="option-row">
							<label>Size:</label>
							<div class="option-btns" id="size-options">
								<?php foreach ( $sizes as $size ) : ?>
									<button type="button" class="size-btn" data-size="<?php echo esc_attr( $size ); ?>"><?php echo esc_html( $size ); ?></button>
								<?php endforeach; ?>
							</div>
							<input type="hidden" name="<?php echo esc_attr( $size_attribute_name ); ?>" id="selected-size" value="">
						</div>

						<?php if ( ! empty( $colors ) ) : ?>
						<div class="option-row">
							<label>Color:</label>
							<div class="color-btns" id="color-options">
								<?php foreach ( $colors as $color ) : ?>
									<button type="button" class="color-btn" data-color="<?php echo esc_attr( $color ); ?>" style="background-color: <?php echo esc_attr( strtolower( $color ) ); ?>" title="<?php echo esc_attr( $color ); ?>"></button>
								<?php endforeach; ?>
							</div>
							<input type="hidden" name="<?php echo esc_attr( $color_attribute_name ); ?>" id="selected-color" value="">
						</div>
						<?php endif; ?>
					</div>
					<?php endif; ?>

					<!-- Delivery Check Section -->
					<div class="delivery-section">
						<h4>Delivery</h4>
						<div class="pincode-checker">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
								<circle cx="12" cy="10" r="3"></circle>
							</svg>
							<input type="text" id="pincode-input" placeholder="Enter Delivery Pincode" maxlength="6" pattern="[0-9]{6}">
							<button type="button" id="check-pincode-btn">Check</button>
						</div>
						<div class="delivery-info" id="delivery-info" style="display: none;">
							<p class="delivery-date">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
									<rect x="1" y="3" width="15" height="13"></rect>
									<polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
									<circle cx="5.5" cy="18.5" r="2.5"></circle>
									<circle cx="18.5" cy="18.5" r="2.5"></circle>
								</svg>
								Delivery by <strong id="delivery-date-text"></strong>
							</p>
						</div>
					</div>

					<div class="quantity-row">
						<label>Quantity:</label>
						<div class="quantity-box">
							<button type="button" id="qty-decrease">-</button>
							<span id="quantity-display">1</span>
							<button type="button" id="qty-increase">+</button>
						</div>
						<?php
						// Hidden input for WooCommerce
						woocommerce_quantity_input( array(
							'min_value'   => 1,
							'max_value'   => $product->get_max_purchase_quantity(),
							'input_value' => 1,
							'classes'     => array( 'input-text', 'qty', 'text' ),
							'input_name'  => 'quantity',
						), $product, false );
						?>
						<span class="stock-text" id="stock-info">
							<?php if ( $stock_status === 'instock' ) : ?>
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<polyline points="20 6 9 17 4 12"></polyline>
								</svg>
								<?php if ( $stock_quantity ) : ?>
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

				<!-- Trust Badges -->
				<div class="trust-badges">
					<div class="badge-item">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<rect x="1" y="3" width="15" height="13"></rect>
							<polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
							<circle cx="5.5" cy="18.5" r="2.5"></circle>
							<circle cx="18.5" cy="18.5" r="2.5"></circle>
						</svg>
						<div class="badge-text">
							<strong>Free Delivery</strong>
							<span>On orders over ₹999</span>
						</div>
					</div>
					<div class="badge-item">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
						</svg>
						<div class="badge-text">
							<strong>Secure Transaction</strong>
							<span>100% secure payment</span>
						</div>
					</div>
					<div class="badge-item">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<polyline points="1 4 1 10 7 10"></polyline>
							<path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
						</svg>
						<div class="badge-text">
							<strong>7 Days Replacement</strong>
							<span>Easy returns</span>
						</div>
					</div>
					<div class="badge-item">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M9 11l3 3L22 4"></path>
							<path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
						</svg>
						<div class="badge-text">
							<strong>Warranty Policy</strong>
							<span>1 Year warranty</span>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Tabbed Product Information Section -->
		<div class="product-tabs-section">
			<div class="tabs-header">
				<button class="tab-btn active" data-tab="description">Description</button>
				<button class="tab-btn" data-tab="specifications">Specifications</button>
				<button class="tab-btn" data-tab="reviews">Reviews</button>
			</div>

			<div class="tabs-content">
				<!-- Description Tab -->
				<div class="tab-panel active" id="description-tab">
					<h3>Product Description</h3>
					<div class="description-content">
						<?php echo wp_kses_post( $product->get_description() ); ?>
						<?php if ( empty( $product->get_description() ) ) : ?>
							<p><?php echo wp_kses_post( $short_desc ); ?></p>
						<?php endif; ?>
					</div>
				</div>

				<!-- Specifications Tab -->
				<div class="tab-panel" id="specifications-tab">
					<h3>Specifications</h3>
					<table class="specifications-table">
						<tbody>
							<?php if ( $sku ) : ?>
							<tr>
								<td class="spec-label">SKU</td>
								<td class="spec-value"><?php echo esc_html( $sku ); ?></td>
							</tr>
							<?php endif; ?>
							<?php if ( $categories ) : ?>
							<tr>
								<td class="spec-label">Category</td>
								<td class="spec-value"><?php echo wp_kses_post( strip_tags( $categories ) ); ?></td>
							</tr>
							<?php endif; ?>
							<tr>
								<td class="spec-label">Stock Status</td>
								<td class="spec-value"><?php echo $stock_status === 'instock' ? 'In Stock' : 'Out of Stock'; ?></td>
							</tr>
							<?php if ( $stock_quantity ) : ?>
							<tr>
								<td class="spec-label">Available Quantity</td>
								<td class="spec-value"><?php echo esc_html( $stock_quantity ); ?> units</td>
							</tr>
							<?php endif; ?>
							<?php
							// Get product attributes
							$attributes = $product->get_attributes();
							if ( ! empty( $attributes ) ) :
								foreach ( $attributes as $attribute ) :
									if ( $attribute->get_variation() ) continue; // Skip variation attributes
									?>
									<tr>
										<td class="spec-label"><?php echo wc_attribute_label( $attribute->get_name() ); ?></td>
										<td class="spec-value">
											<?php
											$values = array();
											if ( $attribute->is_taxonomy() ) {
												$attribute_taxonomy = $attribute->get_taxonomy_object();
												$attribute_values = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) );
												foreach ( $attribute_values as $attribute_value ) {
													$values[] = esc_html( $attribute_value );
												}
											} else {
												$values = $attribute->get_options();
												foreach ( $values as &$value ) {
													$value = make_clickable( esc_html( $value ) );
												}
											}
											echo implode( ', ', $values );
											?>
										</td>
									</tr>
								<?php endforeach;
							endif;
							?>
							<tr>
								<td class="spec-label">Weight</td>
								<td class="spec-value"><?php echo $product->get_weight() ? esc_html( $product->get_weight() ) . ' ' . esc_html( get_option( 'woocommerce_weight_unit' ) ) : 'N/A'; ?></td>
							</tr>
							<?php if ( $product->get_dimensions( false ) ) : ?>
							<tr>
								<td class="spec-label">Dimensions</td>
								<td class="spec-value"><?php echo esc_html( $product->get_dimensions( false ) ); ?></td>
							</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>

				<!-- Reviews Tab -->
				<div class="tab-panel" id="reviews-tab">
					<div class="reviews-section" id="reviews-section">
			<h3>Customer Reviews</h3>

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
											echo '<svg class="star-filled" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
										} else {
											echo '<svg class="star-empty" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
										}
									}
									?>
								</div>
								<span class="review-author"><?php echo esc_html( $comment->comment_author ); ?></span>
							</div>
							<h4><?php echo esc_html( get_the_title( $product_id ) ); ?></h4>
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
					'label_submit'  => __( 'Submit Review', 'woocommerce' ),
					'logged_in_as'  => '',
					'comment_field' => '',
				);

// Rating input for reviews (single, properly-formed string)
$comment_form['comment_field'] = '<div class="comment-form-rating">'
    . '<label for="rating">' . esc_html__( 'Your rating', 'woocommerce' ) . ( wc_review_ratings_enabled() ? '&nbsp;<span class="required">*</span>' : '' ) . '</label>'
    . '<div class="star-rating-selector">'
        . '<span class="star" data-rating="1">★</span>'
        . '<span class="star" data-rating="2">★</span>'
        . '<span class="star" data-rating="3">★</span>'
        . '<span class="star" data-rating="4">★</span>'
        . '<span class="star" data-rating="5">★</span>'
    . '</div>'
    . '<input type="hidden" name="rating" id="rating" value="">'
. '</div>';

// Add comment_post_ID for proper review submission
$comment_form['comment_post_ID'] = $product_id;

comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );


				// Add comment_post_ID for proper review submission
				$comment_form['comment_post_ID'] = $product_id;
				comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
				?>
			</div>
			<?php endif; ?>
					</div>
				</div>
			</div>
		</div>

		<?php
		// Get related products, if none found get from same category, otherwise get latest
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

		// If still no products, get latest products
		if ( empty( $related_ids ) ) {
			$args = array(
				'post_type'      => 'product',
				'posts_per_page' => 4,
				'post__not_in'   => array( $product_id ),
				'orderby'        => 'date',
				'order'          => 'DESC',
			);
			$query = new WP_Query( $args );
			$related_ids = wp_list_pluck( $query->posts, 'ID' );
		}

		if ( ! empty( $related_ids ) ) :
			?>
			<div class="related-section" id="related-section">
				<h2>Related Products</h2>
				<div class="related-products-grid" id="related-products-grid">
					<?php
					foreach ( $related_ids as $related_id ) :
						$related_product = wc_get_product( $related_id );
						if ( ! $related_product ) continue;

						$related_image_id = $related_product->get_image_id();
						?>
						<a href="<?php echo esc_url( get_permalink( $related_id ) ); ?>" class="related-product-card">
							<?php if ( $related_image_id ) : ?>
								<?php echo wp_get_attachment_image( $related_image_id, 'woocommerce_thumbnail', false, array( 'alt' => $related_product->get_name() ) ); ?>
							<?php else : ?>
								<img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="<?php echo esc_attr( $related_product->get_name() ); ?>">
							<?php endif; ?>
							<div class="related-product-info">
								<h5><?php echo esc_html( $related_product->get_name() ); ?></h5>
								<p>₹<?php echo number_format( floatval( $related_product->get_price() ), 2 ); ?></p>
							</div>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

	</div>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
