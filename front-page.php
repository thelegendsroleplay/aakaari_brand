<?php
/**
 * front-page.php
 * Self-contained homepage (will render even if header.php/footer.php are missing)
 *
 * This template intentionally inlines the basic header/footer hooks (wp_head/wp_footer)
 * so you will see the homepage layout immediately.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// load helper functions if present (for products)
if ( file_exists( get_template_directory() . '/inc/homepage.php' ) ) {
	require_once get_template_directory() . '/inc/homepage.php';
}

// Get featured/new arrivals arrays (safe fallbacks)
$featured = function_exists( 'aakaari_get_featured_products' ) ? aakaari_get_featured_products( 8 ) : array();
$new_arrivals = function_exists( 'aakaari_get_new_arrivals' ) ? aakaari_get_new_arrivals( 8 ) : array();

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo esc_html( get_bloginfo( 'name' ) . ' — ' . 'Home' ); ?></title>

	<?php
	// Ensure wp_head runs so plugins and WP can enqueue the usual things
	wp_head();

	// Also include our homepage CSS directly if for some reason enqueue didn't occur.
	$css_uri = get_stylesheet_directory_uri() . '/assets/css/homepage.css';
	if ( file_exists( get_stylesheet_directory() . '/assets/css/homepage.css' ) ) : ?>
	<link rel="stylesheet" href="<?php echo esc_url( $css_uri ); ?>">
	<?php endif; ?>
	<style>
	/* small safeguard so if CSS missing the page isn't totally unreadable */
	body { margin:0; font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial; background:#fff; color:#111; }
	.page-container { width:100%; max-width:1280px; margin:0 auto; padding:0 16px; }
	.header-title { padding:18px 0; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center; }
	.header-title .brand { font-weight:800; font-size:20px; cursor:pointer; }
	</style>
</head>
<body <?php body_class(); ?>>

	<header class="page-container header-title" role="banner">
		<div class="brand" onclick="location.href='<?php echo esc_url( home_url('/') ); ?>'"><?php bloginfo( 'name' ); ?></div>
		<nav class="main-nav">
			<a href="<?php echo esc_url( home_url('/') ); ?>">Home</a>
			<?php if ( function_exists( 'wc_get_page_permalink' ) ) : ?>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">Shop</a>
			<?php endif; ?>
		</nav>
	</header>

	<main id="aakaari-home" class="page-container" role="main">
		<!-- HERO -->
		<section class="hero-banner">
			<div class="hero-image-container">
				<img class="hero-banner-image" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/hero-default.jpg' ); ?>" alt="Hero" onerror="this.removeAttribute('src');">
				<div class="hero-overlay">
					<div class="hero-content-wrapper">
						<div class="hero-text-content">
							<div class="hero-tag">NEW ARRIVAL</div>
							<h1 class="hero-main-title">Premium Streetwear Collection</h1>
							<p class="hero-main-subtitle">Discover our latest collection of premium t-shirts and hoodies</p>
							<div class="hero-cta-group">
								<a class="hero-cta-button" href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url('/') ); ?>">Shop Now</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- CATEGORIES -->
		<section class="category-section">
			<h2 class="category-section-title">Shop by Category</h2>
			<div class="category-grid">
				<a class="category-card" href="<?php echo esc_url( function_exists( 'get_term_link' ) ? get_term_link( 't-shirts', 'product_cat' ) : wc_get_page_permalink( 'shop' ) ); ?>">
					<img class="category-card-image" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/category-tshirt.jpg' ); ?>" alt="T-Shirts" onerror="this.removeAttribute('src');">
					<div class="category-card-overlay">
						<h3 class="category-card-title">T-Shirts</h3>
						<p class="category-card-subtitle">Explore Collection</p>
					</div>
				</a>

				<a class="category-card" href="<?php echo esc_url( function_exists( 'get_term_link' ) ? get_term_link( 'hoodies', 'product_cat' ) : wc_get_page_permalink( 'shop' ) ); ?>">
					<img class="category-card-image" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/category-hoodie.jpg' ); ?>" alt="Hoodies" onerror="this.removeAttribute('src');">
					<div class="category-card-overlay">
						<h3 class="category-card-title">Hoodies</h3>
						<p class="category-card-subtitle">Explore Collection</p>
					</div>
				</a>
			</div>
		</section>

		<!-- FEATURED -->
		<section class="products-section">
			<div class="section-title-wrapper">
				<h2 class="section-main-title">Featured Products</h2>
			</div>

			<?php if ( ! empty( $featured ) ) : ?>
				<div class="carousel" id="featured-carousel">
					<div class="carousel-track" id="featured-track">
						<?php foreach ( $featured as $p ) : 
							// $p is WC_Product or array depending on helper
							$pid = is_object( $p ) ? $p->get_id() : ( isset( $p['id'] ) ? $p['id'] : 0 );
							$name = is_object( $p ) ? $p->get_name() : ( isset( $p['title'] ) ? $p['title'] : '' );
							$img = '';
							if ( is_object( $p ) ) {
								$image_id = $p->get_image_id();
								$img = $image_id ? wp_get_attachment_image_url( $image_id, 'aakaari-product' ) : wc_placeholder_img_src();
							} else {
								$img = isset( $p['image'] ) ? $p['image'] : wc_placeholder_img_src();
							}
						?>
							<div class="product-card" data-product-id="<?php echo esc_attr( $pid ); ?>">
								<img class="product-image" src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $name ); ?>">
								<div class="product-body">
									<h3 class="product-title"><?php echo esc_html( $name ); ?></h3>
									<div class="product-actions">
										<a class="btn primary" href="<?php echo esc_url( is_object( $p ) ? $p->get_permalink() : ( isset( $p['permalink'] ) ? $p['permalink'] : '#' ) ); ?>">View</a>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php else : ?>
				<p>No featured products found.</p>
			<?php endif; ?>
		</section>

		<!-- PROMO -->
		<section class="promo-section">
			<div class="promo-card">
				<div class="promo-content">
					<div class="promo-badge">Premium</div>
					<h2 class="promo-title">Crafted for Excellence</h2>
					<p class="promo-description">Every piece is thoughtfully designed and made with premium materials.</p>
					<a class="promo-button" href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url('/') ); ?>">Explore Collection</a>
				</div>
			</div>
		</section>

		<!-- TRUST -->
		<section class="trust-section">
			<div class="trust-grid">
				<div class="trust-item"><div class="trust-icon-box">🚚</div><div class="trust-text"><h4 class="trust-title">Free Shipping</h4><p class="trust-desc">On orders over $75</p></div></div>
				<div class="trust-item"><div class="trust-icon-box">🔒</div><div class="trust-text"><h4 class="trust-title">Secure Payment</h4><p class="trust-desc">100% protected</p></div></div>
				<div class="trust-item"><div class="trust-icon-box">🔁</div><div class="trust-text"><h4 class="trust-title">Easy Returns</h4><p class="trust-desc">30-day policy</p></div></div>
			</div>
		</section>
	</main>

	<footer class="page-container" role="contentinfo" style="padding:18px 0; border-top:1px solid #eee; margin-top:34px;">
		<p style="margin:0;">© <?php echo date_i18n( 'Y' ); ?> <?php bloginfo( 'name' ); ?> — All rights reserved</p>
	</footer>

	<?php
	// ensure wp_footer to run enqueued JS
	wp_footer();

	// include homepage JS as fail-safe if enqueue didn't happen
	$js_uri = get_stylesheet_directory_uri() . '/assets/js/homepage.js';
	if ( file_exists( get_stylesheet_directory() . '/assets/js/homepage.js' ) ) : ?>
	<script src="<?php echo esc_url( $js_uri ); ?>"></script>
	<?php endif; ?>
</body>
</html>
