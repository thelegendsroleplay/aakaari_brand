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
        <section class="products-section">
            <div class="page-container">
                <div class="section-title-wrapper">
                    <h2 class="section-main-title"><?php esc_html_e( 'Featured Products', 'aakaari-brand' ); ?></h2>
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="section-view-link">
                        <?php esc_html_e( 'View All', 'aakaari-brand' ); ?>
                    </a>
                </div>

                <div class="product-carousel-wrapper">
                    <?php
                    if ( class_exists( 'WooCommerce' ) ) {
                        $featured_products = wc_get_products( array(
                            'status'   => 'publish',
                            'featured' => true,
                            'limit'    => 8,
                        ) );

                        if ( $featured_products ) {
                            echo '<div class="products-grid">';
                            foreach ( $featured_products as $product ) {
                                wc_get_template_part( 'content', 'product' );
                                global $product;
                                $product = $product; // Set global product
                                wc_setup_product_data( $product->get_id() );
                            }
                            echo '</div>';
                        } else {
                            echo '<p>' . esc_html__( 'No featured products found.', 'aakaari-brand' ) . '</p>';
                        }
                    }
                    ?>
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
                        <img
                            src="https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=600&q=80"
                            alt="<?php esc_attr_e( 'Premium Collection', 'aakaari-brand' ); ?>"
                            class="promo-image"
                        />
                        <div class="promo-image-overlay">
                            <div class="promo-quality-badge">
                                <span class="quality-badge-label"><?php esc_html_e( 'Premium Quality', 'aakaari-brand' ); ?></span>
                                <span class="quality-badge-subtitle"><?php esc_html_e( 'Since 2024', 'aakaari-brand' ); ?></span>
                            </div>
                        </div>
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
                    <?php
                    if ( class_exists( 'WooCommerce' ) ) {
                        $new_products = wc_get_products( array(
                            'status'  => 'publish',
                            'limit'   => 8,
                            'orderby' => 'date',
                            'order'   => 'DESC',
                        ) );

                        if ( $new_products ) {
                            echo '<div class="products-grid">';
                            foreach ( $new_products as $product ) {
                                wc_get_template_part( 'content', 'product' );
                            }
                            echo '</div>';
                        } else {
                            echo '<p>' . esc_html__( 'No new products found.', 'aakaari-brand' ) . '</p>';
                        }
                    }
                    ?>
                </div>
            </div>
        </section>

        <!-- Features/Trust Badges -->
        <section class="trust-section">
            <div class="page-container">
                <div class="trust-grid">
                    <div class="trust-item">
                        <div class="trust-icon-box">
                            <svg class="trust-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"></path>
                            </svg>
                        </div>
                        <div class="trust-text">
                            <h4 class="trust-title"><?php esc_html_e( 'Free Shipping', 'aakaari-brand' ); ?></h4>
                            <p class="trust-desc"><?php esc_html_e( 'On orders over $75', 'aakaari-brand' ); ?></p>
                        </div>
                    </div>

                    <div class="trust-item">
                        <div class="trust-icon-box">
                            <svg class="trust-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"></path>
                            </svg>
                        </div>
                        <div class="trust-text">
                            <h4 class="trust-title"><?php esc_html_e( 'Secure Payment', 'aakaari-brand' ); ?></h4>
                            <p class="trust-desc"><?php esc_html_e( '100% protected', 'aakaari-brand' ); ?></p>
                        </div>
                    </div>

                    <div class="trust-item">
                        <div class="trust-icon-box">
                            <svg class="trust-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"></path>
                            </svg>
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
