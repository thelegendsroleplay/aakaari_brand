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
									// Handle both WC_Product objects and arrays
									$product_id = is_object( $product ) ? $product->get_id() : ( isset( $product['id'] ) ? $product['id'] : 0 );
									$product_name = is_object( $product ) ? $product->get_name() : ( isset( $product['title'] ) ? $product['title'] : 'Product' );
									$product_price = is_object( $product ) ? $product->get_price_html() : ( isset( $product['price'] ) ? wc_price( $product['price'] ) : '' );
									$product_link = is_object( $product ) ? get_permalink( $product_id ) : ( isset( $product['permalink'] ) ? $product['permalink'] : '#' );

									// Get product image
									if ( is_object( $product ) ) {
										$image_id = $product->get_image_id();
										$product_image = $image_id ? wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' ) : wc_placeholder_img_src();
									} else {
										$product_image = isset( $product['image'] ) ? $product['image'] : wc_placeholder_img_src();
									}
								?>
									<div class="carousel-item">
										<div class="product-card-mini">
											<a href="<?php echo esc_url( $product_link ); ?>" class="product-card-link">
												<div class="product-card-image-wrapper">
													<img src="<?php echo esc_url( $product_image ); ?>" alt="<?php echo esc_attr( $product_name ); ?>" class="product-card-img">
												</div>
												<div class="product-card-body">
													<h3 class="product-card-name"><?php echo esc_html( $product_name ); ?></h3>
													<div class="product-card-price"><?php echo $product_price; ?></div>
												</div>
											</a>
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
									$product_id = is_object( $product ) ? $product->get_id() : ( isset( $product['id'] ) ? $product['id'] : 0 );
									$product_name = is_object( $product ) ? $product->get_name() : ( isset( $product['title'] ) ? $product['title'] : 'Product' );
									$product_price = is_object( $product ) ? $product->get_price_html() : ( isset( $product['price'] ) ? wc_price( $product['price'] ) : '' );
									$product_link = is_object( $product ) ? get_permalink( $product_id ) : ( isset( $product['permalink'] ) ? $product['permalink'] : '#' );

									if ( is_object( $product ) ) {
										$image_id = $product->get_image_id();
										$product_image = $image_id ? wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' ) : wc_placeholder_img_src();
									} else {
										$product_image = isset( $product['image'] ) ? $product['image'] : wc_placeholder_img_src();
									}
								?>
									<div class="carousel-item">
										<div class="product-card-mini">
											<a href="<?php echo esc_url( $product_link ); ?>" class="product-card-link">
												<div class="product-card-image-wrapper">
													<img src="<?php echo esc_url( $product_image ); ?>" alt="<?php echo esc_attr( $product_name ); ?>" class="product-card-img">
												</div>
												<div class="product-card-body">
													<h3 class="product-card-name"><?php echo esc_html( $product_name ); ?></h3>
													<div class="product-card-price"><?php echo $product_price; ?></div>
												</div>
											</a>
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
