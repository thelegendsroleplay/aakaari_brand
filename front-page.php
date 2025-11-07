<?php
/**
 * front-page.php - Homepage matching Figma design exactly
 * Uses header.php and footer.php from theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load helper functions for products
if ( file_exists( get_template_directory() . '/inc/homepage.php' ) ) {
	require_once get_template_directory() . '/inc/homepage.php';
}

// Get featured/new arrivals products
$featured = function_exists( 'aakaari_get_featured_products' ) ? aakaari_get_featured_products( 8 ) : array();
$new_arrivals = function_exists( 'aakaari_get_new_arrivals' ) ? aakaari_get_new_arrivals( 8 ) : array();

get_header();
?>

<div class="home-page">
	<!-- HERO SECTION -->
	<section class="hero-banner">
		<div class="hero-image-container">
			<img
				src="https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=1200&q=80"
				alt="New Collection"
				class="hero-banner-image"
				onerror="this.style.display='none'; this.parentElement.style.background='linear-gradient(135deg, #667eea 0%, #764ba2 100%)';"
			>
			<div class="hero-overlay">
				<div class="hero-content-wrapper">
					<div class="hero-text-content">
						<div class="hero-tag">NEW ARRIVAL</div>
						<h1 class="hero-main-title">Premium Streetwear Collection</h1>
						<p class="hero-main-subtitle">Discover our latest collection of premium t-shirts and hoodies</p>
						<div class="hero-cta-group">
							<?php if ( function_exists( 'wc_get_page_permalink' ) ) : ?>
								<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="hero-cta-button">
									Shop Now
								</a>
							<?php else : ?>
								<a href="<?php echo esc_url( home_url('/shop/') ); ?>" class="hero-cta-button">
									Shop Now
								</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- CATEGORY SECTION -->
	<section class="category-section">
		<div class="page-container">
			<h2 class="category-section-title">Shop by Category</h2>
			<div class="category-grid">
				<!-- T-Shirts Category -->
				<?php
				$tshirts_link = home_url('/shop/');
				if ( function_exists( 'get_term_link' ) ) {
					$term = get_term_by( 'slug', 't-shirts', 'product_cat' );
					if ( $term && ! is_wp_error( $term ) ) {
						$tshirts_link = get_term_link( $term );
					}
				}
				?>
				<a href="<?php echo esc_url( $tshirts_link ); ?>" class="category-card">
					<img
						src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&q=80"
						alt="T-Shirts"
						class="category-card-image"
						onerror="this.style.display='none';"
					>
					<div class="category-card-overlay">
						<h3 class="category-card-title">T-Shirts</h3>
						<p class="category-card-subtitle">Explore Collection</p>
					</div>
				</a>

				<!-- Hoodies Category -->
				<?php
				$hoodies_link = home_url('/shop/');
				if ( function_exists( 'get_term_link' ) ) {
					$term = get_term_by( 'slug', 'hoodies', 'product_cat' );
					if ( $term && ! is_wp_error( $term ) ) {
						$hoodies_link = get_term_link( $term );
					}
				}
				?>
				<a href="<?php echo esc_url( $hoodies_link ); ?>" class="category-card">
					<img
						src="https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=800&q=80"
						alt="Hoodies"
						class="category-card-image"
						onerror="this.style.display='none';"
					>
					<div class="category-card-overlay">
						<h3 class="category-card-title">Hoodies</h3>
						<p class="category-card-subtitle">Explore Collection</p>
					</div>
				</a>
			</div>
		</div>
	</section>

	<!-- FEATURED PRODUCTS SECTION -->
	<section class="products-section">
		<div class="page-container">
			<div class="section-title-wrapper">
				<h2 class="section-main-title">Featured Products</h2>
				<?php if ( function_exists( 'wc_get_page_permalink' ) ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="section-view-link">
						View All
					</a>
				<?php endif; ?>
			</div>

			<div class="product-carousel-wrapper">
				<?php if ( ! empty( $featured ) ) : ?>
					<div class="product-carousel-container">
						<button class="carousel-arrow carousel-arrow-left" id="featured-prev" style="display:none;">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<polyline points="15 18 9 12 15 6"></polyline>
							</svg>
						</button>

						<div class="carousel-track" id="featured-carousel">
							<div class="carousel-items">
								<?php foreach ( $featured as $product ) :
									if ( ! is_object( $product ) ) continue;

									// Get product data
									$product_id = $product->get_id();
									$image_id = $product->get_image_id();
									$gallery_image_ids = $product->get_gallery_image_ids();
									$hover_image_id = ! empty( $gallery_image_ids ) ? $gallery_image_ids[0] : $image_id;

									// Get category
									$categories = get_the_terms( $product_id, 'product_cat' );
									$category_name = '';
									if ( $categories && ! is_wp_error( $categories ) ) {
										$category = array_shift( $categories );
										$category_name = $category->name;
									}

									// Calculate discount
									$discount_percentage = 0;
									if ( $product->is_on_sale() && $product->get_regular_price() && $product->get_sale_price() ) {
										$discount_percentage = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
									}

									// Check if new
									$product_date = get_the_date( 'U', $product_id );
									$is_new = ( ( time() - $product_date ) / ( 60 * 60 * 24 ) ) < 30;
									$is_featured = $product->is_featured();
								?>
									<div class="carousel-item">
										<div class="product-card" onclick="window.location='<?php echo esc_url( get_permalink( $product_id ) ); ?>'">
											<!-- Image -->
											<div class="product-card-image">
												<?php echo wp_get_attachment_image( $image_id, 'woocommerce_thumbnail', false, array( 'class' => 'default-image', 'alt' => $product->get_name() ) ); ?>
												<?php if ( $hover_image_id !== $image_id ) : ?>
													<?php echo wp_get_attachment_image( $hover_image_id, 'woocommerce_thumbnail', false, array( 'class' => 'hover-image', 'alt' => $product->get_name(), 'style' => 'display:none;' ) ); ?>
												<?php endif; ?>

												<!-- Badges -->
												<div class="product-badges">
													<?php if ( $discount_percentage > 0 ) : ?>
														<span class="product-badge badge-sale">-<?php echo esc_html( $discount_percentage ); ?>%</span>
													<?php endif; ?>
													<?php if ( $is_new ) : ?>
														<span class="product-badge badge-new">New</span>
													<?php endif; ?>
													<?php if ( $is_featured ) : ?>
														<span class="product-badge badge-featured">Featured</span>
													<?php endif; ?>
												</div>

												<!-- Wishlist -->
												<?php if ( function_exists( 'YITH_WCWL' ) ) : ?>
													<button class="product-wishlist-btn" onclick="event.stopPropagation();" aria-label="Add to wishlist">
														<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
															<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
														</svg>
													</button>
												<?php endif; ?>

												<!-- Add to Cart -->
												<?php if ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) : ?>
													<div class="product-cart-overlay">
														<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="product-add-to-cart-btn ajax_add_to_cart add_to_cart_button" data-product_id="<?php echo esc_attr( $product_id ); ?>" onclick="event.stopPropagation();" rel="nofollow">
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
												<?php if ( $category_name ) : ?>
													<p class="product-category"><?php echo esc_html( $category_name ); ?></p>
												<?php endif; ?>
												<h3 class="product-card-title">
													<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
												</h3>
												<?php if ( $product->get_average_rating() ) : ?>
													<div class="product-rating">
														<div class="product-stars">
															<?php
															$rating = $product->get_average_rating();
															for ( $i = 1; $i <= 5; $i++ ) {
																if ( $i <= floor( $rating ) ) {
																	echo '<svg class="star-filled" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
																} else {
																	echo '<svg class="star-empty" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
																}
															}
															?>
														</div>
														<span class="product-review-count">(<?php echo esc_html( $product->get_review_count() ); ?>)</span>
													</div>
												<?php endif; ?>
												<div class="product-price"><?php echo $product->get_price_html(); ?></div>
											</div>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>

						<button class="carousel-arrow carousel-arrow-right" id="featured-next">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<polyline points="9 18 15 12 9 6"></polyline>
							</svg>
						</button>

						<!-- Carousel Dots (Mobile) -->
						<div class="carousel-dots" id="featured-dots"></div>
					</div>
				<?php else : ?>
					<p style="text-align: center; color: #666; padding: 2rem 0;">No featured products available yet.</p>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<!-- PROMO BANNER SECTION -->
	<section class="promo-section">
		<div class="page-container">
			<div class="promo-card">
				<div class="promo-content">
					<div class="promo-badge">Premium</div>
					<h2 class="promo-title">Crafted for Excellence</h2>
					<p class="promo-description">
						Every piece is thoughtfully designed and made with premium materials.
						Experience comfort that lasts, style that stands out.
					</p>

					<div class="promo-features">
						<div class="promo-feature-item">
							<div class="promo-feature-icon">✓</div>
							<span>100% Premium Cotton</span>
						</div>
						<div class="promo-feature-item">
							<div class="promo-feature-icon">✓</div>
							<span>Sustainable Production</span>
						</div>
						<div class="promo-feature-item">
							<div class="promo-feature-icon">✓</div>
							<span>Lifetime Quality Guarantee</span>
						</div>
					</div>

					<?php if ( function_exists( 'wc_get_page_permalink' ) ) : ?>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="promo-button">
							Explore Collection
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<!-- NEW ARRIVALS SECTION -->
	<section class="products-section arrivals-section">
		<div class="page-container">
			<div class="section-title-wrapper">
				<h2 class="section-main-title">New Arrivals</h2>
				<a href="<?php echo esc_url( home_url('/new-arrivals/') ); ?>" class="section-view-link">
					View All
				</a>
			</div>

			<div class="product-carousel-wrapper">
				<?php if ( ! empty( $new_arrivals ) ) : ?>
					<div class="product-carousel-container">
						<button class="carousel-arrow carousel-arrow-left" id="arrivals-prev" style="display:none;">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<polyline points="15 18 9 12 15 6"></polyline>
							</svg>
						</button>

						<div class="carousel-track" id="arrivals-carousel">
							<div class="carousel-items">
								<?php foreach ( $new_arrivals as $product ) :
									if ( ! is_object( $product ) ) continue;

									// Get product data
									$product_id = $product->get_id();
									$image_id = $product->get_image_id();
									$gallery_image_ids = $product->get_gallery_image_ids();
									$hover_image_id = ! empty( $gallery_image_ids ) ? $gallery_image_ids[0] : $image_id;

									// Get category
									$categories = get_the_terms( $product_id, 'product_cat' );
									$category_name = '';
									if ( $categories && ! is_wp_error( $categories ) ) {
										$category = array_shift( $categories );
										$category_name = $category->name;
									}

									// Calculate discount
									$discount_percentage = 0;
									if ( $product->is_on_sale() && $product->get_regular_price() && $product->get_sale_price() ) {
										$discount_percentage = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
									}

									// Check if new
									$product_date = get_the_date( 'U', $product_id );
									$is_new = ( ( time() - $product_date ) / ( 60 * 60 * 24 ) ) < 30;
									$is_featured = $product->is_featured();
								?>
									<div class="carousel-item">
										<div class="product-card" onclick="window.location='<?php echo esc_url( get_permalink( $product_id ) ); ?>'">
											<!-- Image -->
											<div class="product-card-image">
												<?php echo wp_get_attachment_image( $image_id, 'woocommerce_thumbnail', false, array( 'class' => 'default-image', 'alt' => $product->get_name() ) ); ?>
												<?php if ( $hover_image_id !== $image_id ) : ?>
													<?php echo wp_get_attachment_image( $hover_image_id, 'woocommerce_thumbnail', false, array( 'class' => 'hover-image', 'alt' => $product->get_name(), 'style' => 'display:none;' ) ); ?>
												<?php endif; ?>

												<!-- Badges -->
												<div class="product-badges">
													<?php if ( $discount_percentage > 0 ) : ?>
														<span class="product-badge badge-sale">-<?php echo esc_html( $discount_percentage ); ?>%</span>
													<?php endif; ?>
													<?php if ( $is_new ) : ?>
														<span class="product-badge badge-new">New</span>
													<?php endif; ?>
													<?php if ( $is_featured ) : ?>
														<span class="product-badge badge-featured">Featured</span>
													<?php endif; ?>
												</div>

												<!-- Wishlist -->
												<?php if ( function_exists( 'YITH_WCWL' ) ) : ?>
													<button class="product-wishlist-btn" onclick="event.stopPropagation();" aria-label="Add to wishlist">
														<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
															<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
														</svg>
													</button>
												<?php endif; ?>

												<!-- Add to Cart -->
												<?php if ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) : ?>
													<div class="product-cart-overlay">
														<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="product-add-to-cart-btn ajax_add_to_cart add_to_cart_button" data-product_id="<?php echo esc_attr( $product_id ); ?>" onclick="event.stopPropagation();" rel="nofollow">
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
												<?php if ( $category_name ) : ?>
													<p class="product-category"><?php echo esc_html( $category_name ); ?></p>
												<?php endif; ?>
												<h3 class="product-card-title">
													<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
												</h3>
												<?php if ( $product->get_average_rating() ) : ?>
													<div class="product-rating">
														<div class="product-stars">
															<?php
															$rating = $product->get_average_rating();
															for ( $i = 1; $i <= 5; $i++ ) {
																if ( $i <= floor( $rating ) ) {
																	echo '<svg class="star-filled" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
																} else {
																	echo '<svg class="star-empty" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
																}
															}
															?>
														</div>
														<span class="product-review-count">(<?php echo esc_html( $product->get_review_count() ); ?>)</span>
													</div>
												<?php endif; ?>
												<div class="product-price"><?php echo $product->get_price_html(); ?></div>
											</div>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>

						<button class="carousel-arrow carousel-arrow-right" id="arrivals-next">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<polyline points="9 18 15 12 9 6"></polyline>
							</svg>
						</button>

						<!-- Carousel Dots (Mobile) -->
						<div class="carousel-dots" id="arrivals-dots"></div>
					</div>
				<?php else : ?>
					<p style="text-align: center; color: #666; padding: 2rem 0;">No new arrivals available yet.</p>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<!-- TRUST SECTION -->
	<section class="trust-section">
		<div class="page-container">
			<div class="trust-grid">
				<div class="trust-item">
					<div class="trust-icon-box">
						<svg class="trust-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
							<rect x="1" y="3" width="15" height="13"></rect>
							<polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
							<circle cx="5.5" cy="18.5" r="2.5"></circle>
							<circle cx="18.5" cy="18.5" r="2.5"></circle>
						</svg>
					</div>
					<div class="trust-text">
						<h4 class="trust-title">Free Shipping</h4>
						<p class="trust-desc">On orders over $75</p>
					</div>
				</div>

				<div class="trust-item">
					<div class="trust-icon-box">
						<svg class="trust-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
							<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
						</svg>
					</div>
					<div class="trust-text">
						<h4 class="trust-title">Secure Payment</h4>
						<p class="trust-desc">100% protected</p>
					</div>
				</div>

				<div class="trust-item">
					<div class="trust-icon-box">
						<svg class="trust-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
							<polyline points="23 4 23 10 17 10"></polyline>
							<polyline points="1 20 1 14 7 14"></polyline>
							<path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
						</svg>
					</div>
					<div class="trust-text">
						<h4 class="trust-title">Easy Returns</h4>
						<p class="trust-desc">30-day policy</p>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php
get_footer();
