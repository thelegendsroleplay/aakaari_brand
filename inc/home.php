<?php
/**
 * Home Page Functions
 *
 * PHP functions for rendering home page sections with WooCommerce integration
 *
 * @package Aakaari
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Render Hero Section
 *
 * Displays the main hero banner with customizable content from WordPress Customizer
 */
function aakaari_render_hero_section() {
    // Get customizable content
    $hero_bg_image = get_theme_mod( 'hero_background_image', get_template_directory_uri() . '/assets/images/hero-default.jpg' );
    $hero_title = get_theme_mod( 'hero_title', 'ELEVATE YOUR STYLE' );
    $hero_subtitle = get_theme_mod( 'hero_subtitle', 'Premium men\'s fashion crafted for the modern gentleman' );
    $hero_button_primary_text = get_theme_mod( 'hero_button_primary_text', 'Shop Collection' );
    $hero_button_primary_link = get_theme_mod( 'hero_button_primary_link', get_permalink( wc_get_page_id( 'shop' ) ) );
    $hero_button_secondary_text = get_theme_mod( 'hero_button_secondary_text', 'Explore Customization' );
    $hero_button_secondary_link = get_theme_mod( 'hero_button_secondary_link', '#' );

    // Allow plugins/themes to modify hero content
    $hero_data = apply_filters( 'aakaari_hero_data', array(
        'bg_image' => $hero_bg_image,
        'title' => $hero_title,
        'subtitle' => $hero_subtitle,
        'button_primary_text' => $hero_button_primary_text,
        'button_primary_link' => $hero_button_primary_link,
        'button_secondary_text' => $hero_button_secondary_text,
        'button_secondary_link' => $hero_button_secondary_link,
    ) );

    ?>
    <section class="home-hero-section" style="background-image: url('<?php echo esc_url( $hero_data['bg_image'] ); ?>');">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <?php if ( ! empty( $hero_data['title'] ) ) : ?>
                <h1 class="hero-title"><?php echo esc_html( $hero_data['title'] ); ?></h1>
            <?php endif; ?>

            <?php if ( ! empty( $hero_data['subtitle'] ) ) : ?>
                <p class="hero-subtitle"><?php echo esc_html( $hero_data['subtitle'] ); ?></p>
            <?php endif; ?>

            <div class="hero-cta">
                <?php if ( ! empty( $hero_data['button_primary_text'] ) ) : ?>
                    <a href="<?php echo esc_url( $hero_data['button_primary_link'] ); ?>" class="hero-button primary">
                        <?php echo esc_html( $hero_data['button_primary_text'] ); ?>
                    </a>
                <?php endif; ?>

                <?php if ( ! empty( $hero_data['button_secondary_text'] ) ) : ?>
                    <a href="<?php echo esc_url( $hero_data['button_secondary_link'] ); ?>" class="hero-button secondary">
                        <?php echo esc_html( $hero_data['button_secondary_text'] ); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php

    do_action( 'aakaari_after_hero_section' );
}

/**
 * Render Categories Section
 *
 * Displays WooCommerce product categories in a grid layout
 */
function aakaari_render_categories_section() {
    // Get customizable settings
    $section_title = get_theme_mod( 'categories_section_title', 'Shop by Category' );
    $section_subtitle = get_theme_mod( 'categories_section_subtitle', '' );
    $categories_to_show = get_theme_mod( 'categories_count', 4 );
    $exclude_empty = get_theme_mod( 'categories_exclude_empty', true );

    // Get WooCommerce product categories
    $args = array(
        'taxonomy'   => 'product_cat',
        'orderby'    => 'name',
        'order'      => 'ASC',
        'hide_empty' => $exclude_empty,
        'number'     => $categories_to_show,
        'parent'     => 0, // Only top-level categories
    );

    $categories = get_terms( $args );

    if ( empty( $categories ) || is_wp_error( $categories ) ) {
        return;
    }

    do_action( 'aakaari_before_categories_section' );
    ?>

    <section class="categories-section animate-on-scroll">
        <div class="section-header">
            <?php if ( ! empty( $section_title ) ) : ?>
                <h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>

            <?php if ( ! empty( $section_subtitle ) ) : ?>
                <p class="section-subtitle"><?php echo esc_html( $section_subtitle ); ?></p>
            <?php endif; ?>
        </div>

        <div class="categories-grid">
            <?php foreach ( $categories as $category ) :
                $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
                $image_url = $thumbnail_id ? wp_get_attachment_url( $thumbnail_id ) : wc_placeholder_img_src();
                $category_link = get_term_link( $category );
                $product_count = $category->count;
            ?>
                <a href="<?php echo esc_url( $category_link ); ?>" class="home-category-card">
                    <div class="category-image-wrapper">
                        <img src="<?php echo esc_url( $image_url ); ?>"
                             alt="<?php echo esc_attr( $category->name ); ?>"
                             class="category-image">
                        <div class="category-overlay">
                            <h3 class="category-name"><?php echo esc_html( $category->name ); ?></h3>
                            <p class="category-count"><?php echo esc_html( $product_count ); ?> Items</p>
                        </div>
                        <div class="category-arrow">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <?php
    do_action( 'aakaari_after_categories_section' );
}

/**
 * Render Featured Products Section
 *
 * Displays featured WooCommerce products in a grid layout
 */
function aakaari_render_featured_products_section() {
    // Get customizable settings
    $section_title = get_theme_mod( 'featured_section_title', 'Featured Collection' );
    $section_subtitle = get_theme_mod( 'featured_section_subtitle', 'Discover our handpicked selection of premium pieces for the modern gentleman' );
    $products_count = get_theme_mod( 'featured_products_count', 4 );

    // Query featured products
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $products_count,
        'post_status'    => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
                'operator' => 'IN',
            ),
        ),
    );

    $featured_query = new WP_Query( $args );

    if ( ! $featured_query->have_posts() ) {
        return;
    }

    do_action( 'aakaari_before_featured_products_section' );
    ?>

    <section class="featured-section animate-on-scroll">
        <div class="section-header">
            <?php if ( ! empty( $section_title ) ) : ?>
                <h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>

            <?php if ( ! empty( $section_subtitle ) ) : ?>
                <p class="section-subtitle"><?php echo esc_html( $section_subtitle ); ?></p>
            <?php endif; ?>
        </div>

        <div class="home-featured-grid">
            <?php while ( $featured_query->have_posts() ) : $featured_query->the_post();
                global $product;

                // Get product data
                $product_id = get_the_ID();
                $product_image = wp_get_attachment_image_url( $product->get_image_id(), 'full' );
                if ( ! $product_image ) {
                    $product_image = wc_placeholder_img_src();
                }
                $product_name = $product->get_name();
                $product_link = get_permalink();
                $product_price = $product->get_price_html();
                $product_rating = $product->get_average_rating();
                $rating_count = $product->get_rating_count();

                // Get categories
                $categories = wc_get_product_category_list( $product_id );
                $category_name = strip_tags( $categories );

                // Check if on sale
                $is_on_sale = $product->is_on_sale();
                $regular_price = $product->get_regular_price();
                $sale_price = $product->get_sale_price();

                // Calculate discount percentage
                $discount_percent = 0;
                if ( $is_on_sale && $regular_price > 0 ) {
                    $discount_percent = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
                }

                // Check if product is new (created within last 30 days)
                $post_date = get_the_date( 'U' );
                $current_date = current_time( 'timestamp' );
                $is_new = ( $current_date - $post_date ) < ( 30 * DAY_IN_SECONDS );
            ?>
                <div class="featured-product-card">
                    <div class="product-image-wrapper">
                        <a href="<?php echo esc_url( $product_link ); ?>">
                            <img src="<?php echo esc_url( $product_image ); ?>"
                                 alt="<?php echo esc_attr( $product_name ); ?>"
                                 class="product-image">
                        </a>

                        <?php if ( $is_on_sale ) : ?>
                            <span class="product-badge sale">Sale</span>
                        <?php elseif ( $is_new ) : ?>
                            <span class="product-badge new">New</span>
                        <?php endif; ?>

                        <button class="product-wishlist"
                                data-product-id="<?php echo esc_attr( $product_id ); ?>"
                                aria-label="Add to wishlist">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                        </button>
                    </div>

                    <div class="product-info">
                        <?php if ( ! empty( $category_name ) ) : ?>
                            <p class="product-category"><?php echo esc_html( $category_name ); ?></p>
                        <?php endif; ?>

                        <h3 class="product-name">
                            <a href="<?php echo esc_url( $product_link ); ?>">
                                <?php echo esc_html( $product_name ); ?>
                            </a>
                        </h3>

                        <?php if ( $product_rating > 0 ) : ?>
                            <div class="product-rating">
                                <div class="stars">
                                    <?php echo wc_get_rating_html( $product_rating ); ?>
                                </div>
                                <span class="rating-count">(<?php echo esc_html( $rating_count ); ?>)</span>
                            </div>
                        <?php endif; ?>

                        <div class="product-price-section">
                            <?php if ( $is_on_sale && $regular_price ) : ?>
                                <span class="product-price"><?php echo wc_price( $sale_price ); ?></span>
                                <span class="product-original-price"><?php echo wc_price( $regular_price ); ?></span>
                                <?php if ( $discount_percent > 0 ) : ?>
                                    <span class="product-discount">-<?php echo esc_html( $discount_percent ); ?>%</span>
                                <?php endif; ?>
                            <?php else : ?>
                                <span class="product-price"><?php echo $product_price; ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="product-actions">
                            <?php
                            // Add to cart button
                            echo apply_filters( 'aakaari_product_add_to_cart_link',
                                sprintf(
                                    '<a href="%s" data-quantity="1" class="add-to-cart button product_type_%s" data-product_id="%s" data-product_sku="%s" rel="nofollow">%s</a>',
                                    esc_url( $product->add_to_cart_url() ),
                                    esc_attr( $product->get_type() ),
                                    esc_attr( $product_id ),
                                    esc_attr( $product->get_sku() ),
                                    esc_html( $product->add_to_cart_text() )
                                ),
                                $product
                            );
                            ?>

                            <button class="quick-view"
                                    data-product-id="<?php echo esc_attr( $product_id ); ?>"
                                    aria-label="Quick view">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endwhile;
            wp_reset_postdata(); ?>
        </div>

        <div style="text-align: center; margin-top: 2rem;">
            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"
               class="hero-button secondary"
               style="display: inline-block;">
                View All Products
            </a>
        </div>
    </section>

    <?php
    do_action( 'aakaari_after_featured_products_section' );
}

/**
 * Get star rating HTML
 *
 * @param float $rating Average rating
 * @return string Star rating HTML
 */
function aakaari_get_star_rating_html( $rating ) {
    $full_stars = floor( $rating );
    $half_star = ( $rating - $full_stars ) >= 0.5;
    $empty_stars = 5 - ceil( $rating );

    $html = '<div class="stars">';

    // Full stars
    for ( $i = 0; $i < $full_stars; $i++ ) {
        $html .= '<span>★</span>';
    }

    // Half star
    if ( $half_star ) {
        $html .= '<span>⯨</span>';
    }

    // Empty stars
    for ( $i = 0; $i < $empty_stars; $i++ ) {
        $html .= '<span style="opacity: 0.3;">★</span>';
    }

    $html .= '</div>';

    return $html;
}
