<?php
/**
 * The front page template file
 *
 * @package Aakaari_Brand
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="home-page">
        <!-- Hero Banner -->
        <section class="hero-banner">
            <div class="hero-image-container">
                <img
                    src="https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=1200&q=80"
                    alt="<?php esc_attr_e( 'New Collection', 'aakaari-brand' ); ?>"
                    class="hero-banner-image"
                />
                <div class="hero-overlay">
                    <div class="hero-content-wrapper">
                        <div class="hero-text-content">
                            <div class="hero-tag"><?php esc_html_e( 'NEW ARRIVAL', 'aakaari-brand' ); ?></div>
                            <h1 class="hero-main-title">
                                <?php esc_html_e( 'Premium Streetwear Collection', 'aakaari-brand' ); ?>
                            </h1>
                            <p class="hero-main-subtitle">
                                <?php esc_html_e( 'Discover our latest collection of premium t-shirts and hoodies', 'aakaari-brand' ); ?>
                            </p>
                            <div class="hero-cta-group">
                                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="hero-cta-button">
                                    <?php esc_html_e( 'Shop Now', 'aakaari-brand' ); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Category Cards -->
        <section class="category-section">
            <div class="page-container">
                <h2 class="category-section-title"><?php esc_html_e( 'Shop by Category', 'aakaari-brand' ); ?></h2>
                <div class="category-grid">
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="category-card">
                        <img
                            src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&q=80"
                            alt="<?php esc_attr_e( 'T-Shirts', 'aakaari-brand' ); ?>"
                            class="category-card-image"
                        />
                        <div class="category-card-overlay">
                            <h3 class="category-card-title"><?php esc_html_e( 'T-Shirts', 'aakaari-brand' ); ?></h3>
                            <p class="category-card-subtitle"><?php esc_html_e( 'Explore Collection', 'aakaari-brand' ); ?></p>
                        </div>
                    </a>

                    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="category-card">
                        <img
                            src="https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=800&q=80"
                            alt="<?php esc_attr_e( 'Hoodies', 'aakaari-brand' ); ?>"
                            class="category-card-image"
                        />
                        <div class="category-card-overlay">
                            <h3 class="category-card-title"><?php esc_html_e( 'Hoodies', 'aakaari-brand' ); ?></h3>
                            <p class="category-card-subtitle"><?php esc_html_e( 'Explore Collection', 'aakaari-brand' ); ?></p>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="products-section" id="products-section">
            <div class="page-container">
                <div class="section-title-wrapper">
                    <h2 class="section-main-title"><?php esc_html_e( 'Featured Products', 'aakaari-brand' ); ?></h2>
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="section-view-link">
                        <?php esc_html_e( 'View All', 'aakaari-brand' ); ?>
                    </a>
                </div>

                <div class="product-carousel-wrapper">
                    <div class="product-list">
                        <?php
                        if ( class_exists( 'WooCommerce' ) ) {
                            $featured_products = wc_get_products( array(
                                'status'   => 'publish',
                                'featured' => true,
                                'limit'    => 8,
                            ) );

                            if ( $featured_products ) {
                                foreach ( $featured_products as $product ) {
                                    $product_id = $product->get_id();
                                    $product_name = $product->get_name();
                                    $product_price = $product->get_price_html();
                                    $product_image = wp_get_attachment_image_url( $product->get_image_id(), 'medium' );
                                    $product_link = get_permalink( $product_id );
                                    $categories = wc_get_product_category_list( $product_id );

                                    ?>
                                    <a href="<?php echo esc_url( $product_link ); ?>" class="product-card">
                                        <?php if ( $product_image ) : ?>
                                            <img src="<?php echo esc_url( $product_image ); ?>" alt="<?php echo esc_attr( $product_name ); ?>" class="product-image">
                                        <?php else : ?>
                                            <img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="<?php echo esc_attr( $product_name ); ?>" class="product-image">
                                        <?php endif; ?>
                                        <div class="product-info">
                                            <p class="product-category"><?php echo wp_strip_all_tags( $categories ); ?></p>
                                            <h4 class="product-name"><?php echo esc_html( $product_name ); ?></h4>
                                            <div class="product-price"><?php echo wp_kses_post( $product_price ); ?></div>
                                        </div>
                                    </a>
                                    <?php
                                }
                            } else {
                                // Fallback placeholder products if no featured products
                                $placeholder_products = array(
                                    array( 'name' => 'Essential Cotton Tee', 'category' => 'T-shirt', 'price' => '$35.00', 'image' => 'https://images.unsplash.com/photo-1603252109315-cb2860a92f03?w=400&q=80' ),
                                    array( 'name' => 'Minimalist Hoodie', 'category' => 'Outerwear', 'price' => '$79.99', 'image' => 'https://images.unsplash.com/photo-1593032128860-6421591f4229?w=400&q=80' ),
                                    array( 'name' => 'Slim Fit Chinos', 'category' => 'Trousers', 'price' => '$59.50', 'image' => 'https://images.unsplash.com/photo-1604176354204-92b8d5066668?w=400&q=80' ),
                                    array( 'name' => 'Graphic Print Tee', 'category' => 'T-shirt', 'price' => '$39.00', 'image' => 'https://images.unsplash.com/photo-1596756608931-e404b8b80b01?w=400&q=80' ),
                                    array( 'name' => 'Heavyweight Pullover', 'category' => 'Hoodie', 'price' => '$85.00', 'image' => 'https://images.unsplash.com/photo-1592209774640-c3d5e23769c0?w=400&q=80' ),
                                    array( 'name' => 'Denim Trucker Jacket', 'category' => 'Jacket', 'price' => '$120.00', 'image' => 'https://images.unsplash.com/photo-1588820698188-75553e414c62?w=400&q=80' ),
                                );

                                foreach ( $placeholder_products as $item ) {
                                    ?>
                                    <div class="product-card">
                                        <img src="<?php echo esc_url( $item['image'] ); ?>" alt="<?php echo esc_attr( $item['name'] ); ?>" class="product-image">
                                        <div class="product-info">
                                            <p class="product-category"><?php echo esc_html( $item['category'] ); ?></p>
                                            <h4 class="product-name"><?php echo esc_html( $item['name'] ); ?></h4>
                                            <p class="product-price"><?php echo esc_html( $item['price'] ); ?></p>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Promo Banner -->
        <section class="promo-section">
            <div class="page-container">
                <div class="promo-card">
                    <div class="promo-content">
                        <div class="promo-badge"><?php esc_html_e( 'Premium', 'aakaari-brand' ); ?></div>
                        <h2 class="promo-title"><?php esc_html_e( 'Crafted for Excellence', 'aakaari-brand' ); ?></h2>
                        <p class="promo-description">
                            <?php esc_html_e( 'Every piece is thoughtfully designed and made with premium materials. Experience comfort that lasts, style that stands out.', 'aakaari-brand' ); ?>
                        </p>

                        <div class="promo-features">
                            <div class="promo-feature-item">
                                <div class="promo-feature-icon">✓</div>
                                <span><?php esc_html_e( '100% Premium Cotton', 'aakaari-brand' ); ?></span>
                            </div>
                            <div class="promo-feature-item">
                                <div class="promo-feature-icon">✓</div>
                                <span><?php esc_html_e( 'Sustainable Production', 'aakaari-brand' ); ?></span>
                            </div>
                            <div class="promo-feature-item">
                                <div class="promo-feature-icon">✓</div>
                                <span><?php esc_html_e( 'Lifetime Quality Guarantee', 'aakaari-brand' ); ?></span>
                            </div>
                        </div>

                        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="promo-button">
                            <?php esc_html_e( 'Explore Collection', 'aakaari-brand' ); ?>
                        </a>
                    </div>
                    <div class="promo-image-wrapper">
                    </div>
                </div>
            </div>
        </section>

        <!-- New Arrivals -->
        <section class="products-section arrivals-section">
            <div class="page-container">
                <div class="section-title-wrapper">
                    <h2 class="section-main-title"><?php esc_html_e( 'New Arrivals', 'aakaari-brand' ); ?></h2>
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="section-view-link">
                        <?php esc_html_e( 'View All', 'aakaari-brand' ); ?>
                    </a>
                </div>

                <div class="product-carousel-wrapper">
                    <div class="product-list">
                        <?php
                        if ( class_exists( 'WooCommerce' ) ) {
                            $new_products = wc_get_products( array(
                                'status'  => 'publish',
                                'limit'   => 8,
                                'orderby' => 'date',
                                'order'   => 'DESC',
                            ) );

                            if ( $new_products ) {
                                foreach ( $new_products as $product ) {
                                    $product_id = $product->get_id();
                                    $product_name = $product->get_name();
                                    $product_price = $product->get_price_html();
                                    $product_image = wp_get_attachment_image_url( $product->get_image_id(), 'medium' );
                                    $product_link = get_permalink( $product_id );
                                    $categories = wc_get_product_category_list( $product_id );

                                    ?>
                                    <a href="<?php echo esc_url( $product_link ); ?>" class="product-card">
                                        <?php if ( $product_image ) : ?>
                                            <img src="<?php echo esc_url( $product_image ); ?>" alt="<?php echo esc_attr( $product_name ); ?>" class="product-image">
                                        <?php else : ?>
                                            <img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="<?php echo esc_attr( $product_name ); ?>" class="product-image">
                                        <?php endif; ?>
                                        <div class="product-info">
                                            <p class="product-category"><?php echo wp_strip_all_tags( $categories ); ?></p>
                                            <h4 class="product-name"><?php echo esc_html( $product_name ); ?></h4>
                                            <div class="product-price"><?php echo wp_kses_post( $product_price ); ?></div>
                                        </div>
                                    </a>
                                    <?php
                                }
                            } else {
                                // Fallback placeholder products
                                $placeholder_products = array(
                                    array( 'name' => 'Minimalist Backpack', 'category' => 'Accessories', 'price' => '$65.00', 'image' => 'https://images.unsplash.com/photo-1599852230005-9f5a77c7847b?w=400&q=80' ),
                                    array( 'name' => 'Canvas Sneakers', 'category' => 'Footwear', 'price' => '$89.00', 'image' => 'https://images.unsplash.com/photo-1602737632616-538a7c2e0b50?w=400&q=80' ),
                                    array( 'name' => 'Lightweight Bomber', 'category' => 'Outerwear', 'price' => '$110.00', 'image' => 'https://images.unsplash.com/photo-1527719363071-86311d43a50d?w=400&q=80' ),
                                    array( 'name' => 'Oversized Oxford Shirt', 'category' => 'Shirting', 'price' => '$75.00', 'image' => 'https://images.unsplash.com/photo-1512496016147-380d39e31ff8?w=400&q=80' ),
                                    array( 'name' => 'Pocket Tee', 'category' => 'T-shirt', 'price' => '$38.00', 'image' => 'https://images.unsplash.com/photo-1582046294320-b570e676105c?w=400&q=80' ),
                                );

                                foreach ( $placeholder_products as $item ) {
                                    ?>
                                    <div class="product-card">
                                        <img src="<?php echo esc_url( $item['image'] ); ?>" alt="<?php echo esc_attr( $item['name'] ); ?>" class="product-image">
                                        <div class="product-info">
                                            <p class="product-category"><?php echo esc_html( $item['category'] ); ?></p>
                                            <h4 class="product-name"><?php echo esc_html( $item['name'] ); ?></h4>
                                            <p class="product-price"><?php echo esc_html( $item['price'] ); ?></p>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features/Trust Badges -->
        <section class="trust-section">
            <div class="page-container">
                <div class="trust-grid">
                    <div class="trust-item">
                        <div class="trust-icon-box">
                            <i class="fa-solid fa-truck trust-icon" aria-hidden="true"></i>
                        </div>
                        <div class="trust-text">
                            <h4 class="trust-title"><?php esc_html_e( 'Free Shipping', 'aakaari-brand' ); ?></h4>
                            <p class="trust-desc"><?php esc_html_e( 'On orders over $75', 'aakaari-brand' ); ?></p>
                        </div>
                    </div>

                    <div class="trust-item">
                        <div class="trust-icon-box">
                            <i class="fa-solid fa-shield-halved trust-icon" aria-hidden="true"></i>
                        </div>
                        <div class="trust-text">
                            <h4 class="trust-title"><?php esc_html_e( 'Secure Payment', 'aakaari-brand' ); ?></h4>
                            <p class="trust-desc"><?php esc_html_e( '100% protected', 'aakaari-brand' ); ?></p>
                        </div>
                    </div>

                    <div class="trust-item">
                        <div class="trust-icon-box">
                            <i class="fa-solid fa-rotate-left trust-icon" aria-hidden="true"></i>
                        </div>
                        <div class="trust-text">
                            <h4 class="trust-title"><?php esc_html_e( 'Easy Returns', 'aakaari-brand' ); ?></h4>
                            <p class="trust-desc"><?php esc_html_e( '30-day policy', 'aakaari-brand' ); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main><!-- #primary -->

<?php
get_footer();
