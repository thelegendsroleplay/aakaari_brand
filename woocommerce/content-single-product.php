<?php
/**
 * The template for displaying product content in the single-product.php template
 * Modern mobile-first design matching provided HTML specification
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
$price = $product->get_price_html();
$regular_price = $product->get_regular_price();
$sale_price = $product->get_sale_price();
$stock_status = $product->get_stock_status();
$stock_quantity = $product->get_stock_quantity();
$sku = $product->get_sku();
$categories = wc_get_product_category_list( $product_id );

// Get short description
$short_desc = $product->get_short_description();
if ( empty( $short_desc ) ) {
	$short_desc = wp_trim_words( $product->get_description(), 20, '...' );
}
?>

<div class="single-product-page">
	<div class="single-product-container">
		<div class="single-product-card" role="main" aria-label="Product detail">

			<!-- Back Button -->
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="product-back">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
					<path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
				Back to shop
			</a>

			<div class="product-main">

				<!-- Gallery -->
				<div class="product-gallery" id="gallery">
					<div class="gallery-hero-stage">
						<div class="gallery-hero" id="gallery-hero">
							<?php if ( ! empty( $all_images ) ) : ?>
								<?php $first_image = reset( $all_images ); ?>
								<?php echo wp_get_attachment_image( $first_image, 'large', false, array(
									'id' => 'gallery-hero-img',
									'alt' => $product->get_name()
								) ); ?>
							<?php else : ?>
								<div style="color: #cbd5e1; font-size: 14px;">No image available</div>
							<?php endif; ?>
						</div>
					</div>

					<?php if ( count( $all_images ) > 1 ) : ?>
						<div class="gallery-thumbs" id="gallery-thumbs">
							<?php foreach ( $all_images as $index => $image_id ) : ?>
								<button
									class="gallery-thumb<?php echo $index === 0 ? ' active' : ''; ?>"
									type="button"
									data-image="<?php echo esc_url( wp_get_attachment_image_url( $image_id, 'large' ) ); ?>"
									aria-label="View image <?php echo $index + 1; ?>">
									<?php echo wp_get_attachment_image( $image_id, 'thumbnail', false, array(
										'alt' => $product->get_name() . ' - Image ' . ( $index + 1 )
									) ); ?>
								</button>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
				<!-- /Gallery -->

				<!-- Product Info -->
				<div class="product-info">

					<!-- Title & Wishlist -->
					<div class="product-title-row">
						<h1 class="product-title"><?php echo esc_html( $product->get_name() ); ?></h1>
						<button
							class="product-wishlist"
							id="wishlist-btn"
							data-product-id="<?php echo esc_attr( $product_id ); ?>"
							aria-pressed="false"
							title="Add to wishlist">
							<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor">
								<path d="M20.8 7.2a5.2 5.2 0 0 0-7.364-.4L12 8l-1.436-1.2a5.2 5.2 0 0 0-7.364.4A5.2 5.2 0 0 0 3 11.6c0 4.1 6.4 8.6 9 10.2 2.6-1.6 9-6.1 9-10.2a5.2 5.2 0 0 0-1.2-4.4z"/>
							</svg>
						</button>
					</div>

					<!-- Rating -->
					<?php if ( $review_count > 0 ) : ?>
						<div class="product-rating-row">
							<span class="product-rating-stars">
								<?php echo wc_get_rating_html( $rating ); ?>
							</span>
							<span><?php echo number_format( $rating, 1 ); ?> (<?php echo $review_count; ?>)</span>
						</div>
					<?php endif; ?>

					<!-- Price -->
					<div class="product-price-row">
						<div class="product-price-main">
							<?php echo $price; ?>
						</div>
					</div>

					<!-- Description -->
					<?php if ( $short_desc ) : ?>
						<p class="product-description"><?php echo wp_kses_post( $short_desc ); ?></p>
					<?php endif; ?>

					<!-- WooCommerce Form -->
					<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

					<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>

						<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

						<!-- Options (Variations for Variable Products) -->
						<?php if ( $product->is_type( 'variable' ) ) : ?>
							<div class="product-options">
								<?php
								$attributes = $product->get_variation_attributes();
								$available_variations = $product->get_available_variations();

								foreach ( $attributes as $attribute_name => $options ) :
									$attribute_label = wc_attribute_label( $attribute_name );
									?>
									<div class="option-group">
										<label class="option-label" for="<?php echo sanitize_title( $attribute_name ); ?>">
											<?php echo esc_html( $attribute_label ); ?>
										</label>
										<div class="option-buttons">
											<?php
											wc_dropdown_variation_attribute_options( array(
												'options'   => $options,
												'attribute' => $attribute_name,
												'product'   => $product,
												'class'     => 'option-select',
											) );
											?>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

						<!-- Quantity & Stock -->
						<div class="product-qty-row">
							<div>
								<label class="option-label">Quantity</label>
								<div class="product-qty">
									<button type="button" id="dec-qty" aria-label="Decrease quantity">−</button>
									<div class="qty-value" id="qty-value" data-max="<?php echo $stock_quantity ?: 9999; ?>">1</div>
									<button type="button" id="inc-qty" aria-label="Increase quantity">+</button>
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
							</div>
							<div class="product-stock <?php echo $stock_status !== 'instock' ? 'out-of-stock' : ''; ?>">
								<?php if ( $stock_status === 'instock' ) : ?>
									<?php if ( $stock_quantity ) : ?>
										<?php echo $stock_quantity; ?> in stock
									<?php else : ?>
										In stock
									<?php endif; ?>
								<?php else : ?>
									Out of stock
								<?php endif; ?>
							</div>
						</div>

						<!-- Action Buttons -->
						<div class="product-actions">
							<button
								type="submit"
								name="add-to-cart"
								value="<?php echo esc_attr( $product_id ); ?>"
								class="product-btn primary"
								id="add-to-cart-btn"
								<?php echo $stock_status !== 'instock' ? 'disabled' : ''; ?>>
								Add to cart
							</button>
							<button
								type="button"
								class="product-btn ghost"
								id="buy-now-btn"
								<?php echo $stock_status !== 'instock' ? 'disabled' : ''; ?>>
								Buy now
							</button>
						</div>

						<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

					</form>

					<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

					<!-- Features -->
					<div class="product-features">
						<div>
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
								<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
								<circle cx="12" cy="10" r="3"/>
							</svg>
							Free shipping over $100
						</div>
						<div>
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
								<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
							</svg>
							30-day returns
						</div>
						<div>
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
								<rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
								<line x1="1" y1="10" x2="23" y2="10"/>
							</svg>
							Secure payments
						</div>
					</div>

					<!-- Meta -->
					<div class="product-meta">
						<?php if ( $sku ) : ?>
							<div><strong>SKU:</strong> <?php echo esc_html( $sku ); ?></div>
						<?php endif; ?>
						<?php if ( $categories ) : ?>
							<div><strong>Category:</strong> <?php echo wp_kses_post( $categories ); ?></div>
						<?php endif; ?>
					</div>

				</div>
				<!-- /Product Info -->

			</div> <!-- /product-main -->

			<!-- Product Description Tab -->
			<?php if ( $product->get_description() ) : ?>
				<div class="product-tabs">
					<h3>Description</h3>
					<div class="woocommerce-product-details__description">
						<?php echo wpautop( do_shortcode( $product->get_description() ) ); ?>
					</div>
				</div>
			<?php endif; ?>

			<!-- Reviews -->
			<?php if ( comments_open() || get_comments_number() ) : ?>
				<div class="product-reviews">
					<h3>Customer Reviews</h3>
					<?php comments_template(); ?>
				</div>
			<?php endif; ?>

			<!-- Related Products -->
			<?php
			$related_ids = wc_get_related_products( $product_id, 6 );
			if ( ! empty( $related_ids ) ) :
				?>
				<div class="related-products">
					<h3>Related Products</h3>
					<div class="related-products-list">
						<?php
						foreach ( $related_ids as $related_id ) :
							$related_product = wc_get_product( $related_id );
							if ( ! $related_product ) continue;

							// Get product data
							$related_image_id = $related_product->get_image_id();
							$related_gallery_ids = $related_product->get_gallery_image_ids();
							$related_hover_image_id = ! empty( $related_gallery_ids ) ? $related_gallery_ids[0] : $related_image_id;

							// Get category
							$related_categories = get_the_terms( $related_id, 'product_cat' );
							$related_category_name = '';
							if ( $related_categories && ! is_wp_error( $related_categories ) ) {
								$related_category = array_shift( $related_categories );
								$related_category_name = $related_category->name;
							}

							// Calculate discount
							$related_discount = 0;
							if ( $related_product->is_on_sale() && $related_product->get_regular_price() && $related_product->get_sale_price() ) {
								$related_discount = round( ( ( $related_product->get_regular_price() - $related_product->get_sale_price() ) / $related_product->get_regular_price() ) * 100 );
							}

							// Check if new (within 30 days)
							$related_date = get_the_date( 'U', $related_id );
							$related_is_new = ( ( time() - $related_date ) / ( 60 * 60 * 24 ) ) < 30;
							$related_is_featured = $related_product->is_featured();

							// Get rating
							$related_rating = $related_product->get_average_rating();
							$related_review_count = $related_product->get_review_count();
							?>
							<div class="product-card" style="min-width: 200px; max-width: 220px;" onclick="window.location='<?php echo esc_url( get_permalink( $related_id ) ); ?>'">
								<!-- Image -->
								<div class="product-card-image">
									<?php if ( $related_image_id ) : ?>
										<?php echo wp_get_attachment_image( $related_image_id, 'woocommerce_thumbnail', false, array( 'class' => 'default-image', 'alt' => $related_product->get_name() ) ); ?>
										<?php if ( $related_hover_image_id !== $related_image_id ) : ?>
											<?php echo wp_get_attachment_image( $related_hover_image_id, 'woocommerce_thumbnail', false, array( 'class' => 'hover-image', 'alt' => $related_product->get_name(), 'style' => 'display:none;' ) ); ?>
										<?php endif; ?>
									<?php endif; ?>

									<!-- Badges -->
									<div class="product-badges">
										<?php if ( $related_discount > 0 ) : ?>
											<span class="product-badge badge-sale">-<?php echo esc_html( $related_discount ); ?>%</span>
										<?php endif; ?>
										<?php if ( $related_is_new ) : ?>
											<span class="product-badge badge-new">New</span>
										<?php endif; ?>
										<?php if ( $related_is_featured ) : ?>
											<span class="product-badge badge-featured">Featured</span>
										<?php endif; ?>
									</div>

									<!-- Wishlist Button -->
									<button class="product-wishlist-btn" onclick="event.stopPropagation();" aria-label="Add to wishlist">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
										</svg>
									</button>

									<!-- Add to Cart Overlay -->
									<?php if ( $related_product->is_type( 'simple' ) && $related_product->is_purchasable() && $related_product->is_in_stock() ) : ?>
										<div class="product-cart-overlay">
											<a href="<?php echo esc_url( $related_product->add_to_cart_url() ); ?>" class="product-add-to-cart-btn ajax_add_to_cart add_to_cart_button" data-product_id="<?php echo esc_attr( $related_id ); ?>" onclick="event.stopPropagation();" rel="nofollow">
												<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
									<?php if ( $related_category_name ) : ?>
										<p class="product-category"><?php echo esc_html( $related_category_name ); ?></p>
									<?php endif; ?>
									<h4 class="product-card-title">
										<a href="<?php echo esc_url( get_permalink( $related_id ) ); ?>">
											<?php echo esc_html( $related_product->get_name() ); ?>
										</a>
									</h4>
									<div class="product-rating">
										<div class="product-stars">
											<?php
											for ( $i = 1; $i <= 5; $i++ ) {
												if ( $i <= floor( $related_rating ) ) {
													echo '<svg class="star-filled" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
												} else {
													echo '<svg class="star-empty" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
												}
											}
											?>
										</div>
										<span class="product-review-count">(<?php echo esc_html( $related_review_count ); ?>)</span>
									</div>
									<div class="product-price"><?php echo $related_product->get_price_html(); ?></div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

		</div>
	</div>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
