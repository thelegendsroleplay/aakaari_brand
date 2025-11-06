<?php
/**
 * Homepage Section Functions
 *
 * @package Aakaari_Brand
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Hero Section
 */
function aakaari_brand_hero_section() {
    $hero_image = get_theme_mod( 'aakaari_brand_hero_image', 'https://images.unsplash.com/photo-1641736755184-67380b9a002c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwZmFzaGlvbiUyMG1vZGVsfGVufDF8fHx8MTc2MjIzODI1Nnww&ixlib=rb-4.1.0&q=80&w=1080' );
    $hero_title = get_theme_mod( 'aakaari_brand_hero_title', __( 'ELEVATE YOUR STYLE', 'aakaari-brand' ) );
    $hero_subtitle = get_theme_mod( 'aakaari_brand_hero_subtitle', __( 'Premium men\'s fashion crafted for the modern gentleman', 'aakaari-brand' ) );
    $shop_url = class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop' );
    ?>

    <section class="home-hero-section" style="background-image: url('<?php echo esc_url( $hero_image ); ?>');">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title"><?php echo esc_html( $hero_title ); ?></h1>
            <p class="hero-subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
            <div class="hero-cta">
                <a href="<?php echo esc_url( $shop_url ); ?>" class="hero-button primary">
                    <?php esc_html_e( 'Shop Collection', 'aakaari-brand' ); ?>
                </a>
                <a href="<?php echo esc_url( home_url( '/customization' ) ); ?>" class="hero-button secondary">
                    <?php esc_html_e( 'Explore Customization', 'aakaari-brand' ); ?>
                </a>
            </div>
        </div>
    </section>

    <?php
}

/**
 * Categories Section
 */
function aakaari_brand_categories_section() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    // Get product categories
    $categories = get_terms( array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'number'     => 5,
        'orderby'    => 'count',
        'order'      => 'DESC',
    ) );

    if ( empty( $categories ) || is_wp_error( $categories ) ) {
        // Use demo categories if no real categories exist
        $categories = array(
            (object) array(
                'term_id' => 1,
                'name' => 'Jackets',
                'slug' => 'jackets',
                'count' => 45,
                'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwamFja2V0fGVufDF8fHx8MTc2MjI0NTE1MHww&ixlib=rb-4.1.0&q=80&w=1080',
            ),
            (object) array(
                'term_id' => 2,
                'name' => 'Shirts',
                'slug' => 'shirts',
                'count' => 67,
                'image' => 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwc2hpcnR8ZW58MXx8fHwxNzYyMjQ1MTUwfDA&ixlib=rb-4.1.0&q=80&w=1080',
            ),
            (object) array(
                'term_id' => 3,
                'name' => 'Pants',
                'slug' => 'pants',
                'count' => 52,
                'image' => 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwcGFudHN8ZW58MXx8fHwxNzYyMjQ1MTUwfDA&ixlib=rb-4.1.0&q=80&w=1080',
            ),
            (object) array(
                'term_id' => 4,
                'name' => 'Shoes',
                'slug' => 'shoes',
                'count' => 38,
                'image' => 'https://images.unsplash.com/photo-1560343090-f0409e92791a?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwc2hvZXN8ZW58MXx8fHwxNzYyMjQ1MTUxfDA&ixlib=rb-4.1.0&q=80&w=1080',
            ),
            (object) array(
                'term_id' => 5,
                'name' => 'Accessories',
                'slug' => 'accessories',
                'count' => 29,
                'image' => 'https://images.unsplash.com/photo-1523293182086-7651a899d37f?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwYWNjZXNzb3JpZXN8ZW58MXx8fHwxNzYyMjQ1MTUxfDA&ixlib=rb-4.1.0&q=80&w=1080',
            ),
        );
    }
    ?>

    <section class="categories-section">
        <div class="section-header">
            <h2 class="section-title"><?php esc_html_e( 'Shop by Category', 'aakaari-brand' ); ?></h2>
            <p class="section-subtitle"><?php esc_html_e( 'Discover our curated collections', 'aakaari-brand' ); ?></p>
        </div>

        <div class="categories-grid">
            <?php foreach ( $categories as $category ) :
                $category_link = is_object( $category ) && isset( $category->term_id )
                    ? get_term_link( $category->term_id, 'product_cat' )
                    : get_permalink( wc_get_page_id( 'shop' ) );
                $category_image = '';

                if ( is_object( $category ) && isset( $category->term_id ) ) {
                    $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
                    if ( $thumbnail_id ) {
                        $category_image = wp_get_attachment_url( $thumbnail_id );
                    }
                }

                // Fallback to demo image if no image exists
                if ( empty( $category_image ) && isset( $category->image ) ) {
                    $category_image = $category->image;
                }
                ?>

                <div class="home-category-card" onclick="window.location.href='<?php echo esc_url( $category_link ); ?>'">
                    <div class="category-image-wrapper">
                        <?php if ( $category_image ) : ?>
                            <img src="<?php echo esc_url( $category_image ); ?>"
                                 alt="<?php echo esc_attr( $category->name ); ?>"
                                 class="category-image">
                        <?php endif; ?>
                        <div class="category-overlay">
                            <h3 class="category-name"><?php echo esc_html( $category->name ); ?></h3>
                            <p class="category-count"><?php echo esc_html( $category->count ); ?> <?php esc_html_e( 'Items', 'aakaari-brand' ); ?></p>
                        </div>
                        <div class="category-arrow">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    </section>

    <?php
}

/**
 * Featured Products Section
 */
function aakaari_brand_featured_products_section() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    // Query featured products
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 4,
        'post_status'    => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
            ),
        ),
    );

    $products = new WP_Query( $args );

    // If no featured products, get latest products
    if ( ! $products->have_posts() ) {
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => 4,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        );
        $products = new WP_Query( $args );
    }

    if ( ! $products->have_posts() ) {
        return;
    }
    ?>

    <section class="featured-section">
        <div class="section-header">
            <h2 class="section-title"><?php esc_html_e( 'Featured Collection', 'aakaari-brand' ); ?></h2>
            <p class="section-subtitle"><?php esc_html_e( 'Discover our handpicked selection of premium pieces for the modern gentleman', 'aakaari-brand' ); ?></p>
        </div>

        <div class="home-featured-grid">
            <?php while ( $products->have_posts() ) : $products->the_post();
                global $product;
                $product_id = get_the_ID();
                $product_obj = wc_get_product( $product_id );

                // Product badges
                $is_on_sale = $product_obj->is_on_sale();
                $is_new = ( time() - ( 60 * 60 * 24 * 30 ) ) < strtotime( get_the_date() );

                // Wishlist
                $wishlist_url = '#';
                $wishlist_class = 'product-wishlist';
                if ( function_exists( 'yith_wcwl_is_product_in_wishlist' ) ) {
                    $in_wishlist = yith_wcwl_is_product_in_wishlist( $product_id );
                    if ( $in_wishlist ) {
                        $wishlist_class .= ' in-wishlist';
                    }
                }
                ?>

                <div class="featured-product-card" data-product-id="<?php echo esc_attr( $product_id ); ?>">
                    <div class="product-image-wrapper">
                        <a href="<?php echo esc_url( get_permalink() ); ?>">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'woocommerce_thumbnail', array( 'class' => 'product-image' ) ); ?>
                            <?php else : ?>
                                <img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="product-image">
                            <?php endif; ?>
                        </a>

                        <?php if ( $is_on_sale ) : ?>
                            <span class="product-badge sale"><?php esc_html_e( 'Sale', 'aakaari-brand' ); ?></span>
                        <?php elseif ( $is_new ) : ?>
                            <span class="product-badge new"><?php esc_html_e( 'New', 'aakaari-brand' ); ?></span>
                        <?php endif; ?>

                        <?php if ( function_exists( 'YITH_WCWL' ) ) : ?>
                            <button class="<?php echo esc_attr( $wishlist_class ); ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                            </button>
                        <?php endif; ?>
                    </div>

                    <div class="product-info">
                        <?php
                        $categories = get_the_terms( $product_id, 'product_cat' );
                        if ( $categories && ! is_wp_error( $categories ) ) :
                            $category = array_shift( $categories );
                            ?>
                            <p class="product-category"><?php echo esc_html( $category->name ); ?></p>
                        <?php endif; ?>

                        <h3 class="product-name">
                            <a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
                        </h3>

                        <?php if ( $product_obj->get_average_rating() ) : ?>
                            <div class="product-rating">
                                <div class="stars">
                                    <?php echo wc_get_rating_html( $product_obj->get_average_rating() ); ?>
                                </div>
                                <span class="rating-count">(<?php echo esc_html( $product_obj->get_review_count() ); ?>)</span>
                            </div>
                        <?php endif; ?>

                        <div class="product-price-section">
                            <?php echo $product_obj->get_price_html(); ?>
                            <?php if ( $is_on_sale && $product_obj->get_regular_price() && $product_obj->get_sale_price() ) :
                                $discount = round( ( ( $product_obj->get_regular_price() - $product_obj->get_sale_price() ) / $product_obj->get_regular_price() ) * 100 );
                                ?>
                                <span class="product-discount">-<?php echo esc_html( $discount ); ?>%</span>
                            <?php endif; ?>
                        </div>

                        <div class="product-actions">
                            <?php
                            echo apply_filters(
                                'woocommerce_loop_add_to_cart_link',
                                sprintf(
                                    '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
                                    esc_url( $product_obj->add_to_cart_url() ),
                                    esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
                                    esc_attr( 'add-to-cart button alt ajax_add_to_cart' ),
                                    isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
                                    esc_html( $product_obj->add_to_cart_text() )
                                ),
                                $product_obj,
                                $args
                            );
                            ?>
                            <button class="quick-view" data-product-id="<?php echo esc_attr( $product_id ); ?>" aria-label="<?php esc_attr_e( 'Quick View', 'aakaari-brand' ); ?>">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

            <?php endwhile; wp_reset_postdata(); ?>
        </div>

        <div class="section-footer">
            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="view-all-button">
                <?php esc_html_e( 'View All Products', 'aakaari-brand' ); ?>
            </a>
        </div>
    </section>

    <?php
}
