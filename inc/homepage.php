<?php
/**
 * Homepage specific functions
 *
 * @package Aakaari_Brand
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Display Hero Section
 */
function aakaari_brand_hero_section() {
    // Get customizer values with defaults
    $hero_image       = get_theme_mod( 'aakaari_brand_hero_image', 'https://images.unsplash.com/photo-1641736755184-67380b9a002c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwZmFzaGlvbiUyMG1vZGVsfGVufDF8fHx8MTc2MjIzODI1Nnww&ixlib=rb-4.1.0&q=80&w=1080' );
    $hero_title       = get_theme_mod( 'aakaari_brand_hero_title', 'ELEVATE YOUR STYLE' );
    $hero_subtitle    = get_theme_mod( 'aakaari_brand_hero_subtitle', 'Premium men\'s fashion crafted for the modern gentleman' );
    $hero_button_text = get_theme_mod( 'aakaari_brand_hero_button_text', 'Shop Collection' );
    $hero_button_link = get_theme_mod( 'aakaari_brand_hero_button_link', get_permalink( wc_get_page_id( 'shop' ) ) );

    ?>
    <section class="home-hero-section" style="background-image: url('<?php echo esc_url( $hero_image ); ?>');" role="banner">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title"><?php echo esc_html( $hero_title ); ?></h1>
            <p class="hero-subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
            <div class="hero-cta">
                <a href="<?php echo esc_url( $hero_button_link ); ?>" class="hero-button primary">
                    <?php echo esc_html( $hero_button_text ); ?>
                </a>
                <a href="#categories" class="hero-button secondary">
                    <?php esc_html_e( 'Explore Categories', 'aakaari-brand' ); ?>
                </a>
            </div>
        </div>
    </section>
    <?php
}

/**
 * Display Categories Section
 */
function aakaari_brand_categories_section() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    // Get product categories
    $args = array(
        'taxonomy'   => 'product_cat',
        'orderby'    => 'name',
        'order'      => 'ASC',
        'hide_empty' => true,
        'number'     => apply_filters( 'aakaari_brand_homepage_categories_limit', 4 ),
        'parent'     => 0, // Only top-level categories
    );

    $categories = get_terms( $args );

    if ( empty( $categories ) || is_wp_error( $categories ) ) {
        return;
    }

    ?>
    <section id="categories" class="categories-section" role="region" aria-label="<?php esc_attr_e( 'Product Categories', 'aakaari-brand' ); ?>">
        <div class="site-container">
            <div class="section-header">
                <h2 class="section-title"><?php echo esc_html( get_theme_mod( 'aakaari_brand_categories_title', 'Shop by Category' ) ); ?></h2>
                <p class="section-subtitle"><?php echo esc_html( get_theme_mod( 'aakaari_brand_categories_subtitle', 'Discover our curated collections' ) ); ?></p>
            </div>

            <div class="categories-grid">
                <?php foreach ( $categories as $category ) :
                    $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
                    $image_url    = $thumbnail_id ? wp_get_attachment_url( $thumbnail_id ) : wc_placeholder_img_src();
                    $category_url = get_term_link( $category );

                    if ( is_wp_error( $category_url ) ) {
                        continue;
                    }
                    ?>
                    <div class="home-category-card" data-category-id="<?php echo esc_attr( $category->term_id ); ?>">
                        <a href="<?php echo esc_url( $category_url ); ?>" class="category-link">
                            <div class="category-image-wrapper">
                                <img
                                    src="<?php echo esc_url( $image_url ); ?>"
                                    alt="<?php echo esc_attr( $category->name ); ?>"
                                    class="category-image"
                                    loading="lazy"
                                />
                                <div class="category-overlay">
                                    <h3 class="category-name"><?php echo esc_html( $category->name ); ?></h3>
                                    <p class="category-count">
                                        <?php
                                        printf(
                                            esc_html( _n( '%s Item', '%s Items', $category->count, 'aakaari-brand' ) ),
                                            number_format_i18n( $category->count )
                                        );
                                        ?>
                                    </p>
                                </div>
                                <div class="category-arrow">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
}

/**
 * Display Featured Products Section
 */
function aakaari_brand_featured_products_section() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    // Get featured products
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => apply_filters( 'aakaari_brand_homepage_products_limit', 8 ),
        'orderby'        => 'date',
        'order'          => 'DESC',
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
            ),
        ),
    );

    $featured_query = new WP_Query( $args );

    // Fallback to recent products if no featured products
    if ( ! $featured_query->have_posts() ) {
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => apply_filters( 'aakaari_brand_homepage_products_limit', 8 ),
            'orderby'        => 'date',
            'order'          => 'DESC',
        );
        $featured_query = new WP_Query( $args );
    }

    if ( ! $featured_query->have_posts() ) {
        return;
    }

    ?>
    <section class="featured-section" role="region" aria-label="<?php esc_attr_e( 'Featured Products', 'aakaari-brand' ); ?>">
        <div class="site-container">
            <div class="section-header">
                <h2 class="section-title"><?php echo esc_html( get_theme_mod( 'aakaari_brand_featured_title', 'Featured Collection' ) ); ?></h2>
                <p class="section-subtitle"><?php echo esc_html( get_theme_mod( 'aakaari_brand_featured_subtitle', 'Discover our handpicked selection of premium pieces for the modern gentleman' ) ); ?></p>
            </div>

            <div class="home-featured-grid">
                <?php
                while ( $featured_query->have_posts() ) :
                    $featured_query->the_post();
                    global $product;

                    // Get product data
                    $product_id = get_the_ID();
                    $image_id   = $product->get_image_id();
                    $image_url  = $image_id ? wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' ) : wc_placeholder_img_src();
                    $product_url = get_permalink();

                    ?>
                    <div class="featured-product-card" data-product-id="<?php echo esc_attr( $product_id ); ?>">
                        <a href="<?php echo esc_url( $product_url ); ?>" class="product-link">
                            <div class="product-image-wrapper">
                                <img
                                    src="<?php echo esc_url( $image_url ); ?>"
                                    alt="<?php echo esc_attr( get_the_title() ); ?>"
                                    class="product-image"
                                    loading="lazy"
                                />

                                <?php
                                // Show badges
                                if ( $product->is_on_sale() ) {
                                    echo '<span class="product-badge sale">' . esc_html__( 'Sale', 'aakaari-brand' ) . '</span>';
                                } elseif ( $product->is_featured() ) {
                                    echo '<span class="product-badge new">' . esc_html__( 'Featured', 'aakaari-brand' ) . '</span>';
                                }

                                // Wishlist button
                                if ( function_exists( 'YITH_WCWL' ) ) {
                                    echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
                                } else {
                                    ?>
                                    <button class="product-wishlist" aria-label="<?php esc_attr_e( 'Add to Wishlist', 'aakaari-brand' ); ?>">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10 17.5L8.825 16.45C4.4 12.475 1.5 9.85 1.5 6.625C1.5 4 3.5 2 6.125 2C7.6 2 9.025 2.725 10 3.875C10.975 2.725 12.4 2 13.875 2C16.5 2 18.5 4 18.5 6.625C18.5 9.85 15.6 12.475 11.175 16.45L10 17.5Z" stroke="currentColor" stroke-width="1.5"/>
                                        </svg>
                                    </button>
                                    <?php
                                }
                                ?>
                            </div>

                            <div class="product-info">
                                <?php
                                // Product categories
                                $categories = get_the_terms( $product_id, 'product_cat' );
                                if ( $categories && ! is_wp_error( $categories ) ) {
                                    $category_names = array();
                                    foreach ( $categories as $category ) {
                                        $category_names[] = $category->name;
                                    }
                                    echo '<div class="product-category">' . esc_html( implode( ', ', $category_names ) ) . '</div>';
                                }
                                ?>

                                <h3 class="product-name"><?php the_title(); ?></h3>

                                <?php
                                // Star rating
                                $rating_count = $product->get_rating_count();
                                $average      = $product->get_average_rating();

                                if ( $rating_count > 0 ) :
                                    ?>
                                    <div class="product-rating">
                                        <div class="stars">
                                            <?php echo wc_get_rating_html( $average, $rating_count ); ?>
                                        </div>
                                        <span class="rating-count">(<?php echo esc_html( $rating_count ); ?>)</span>
                                    </div>
                                <?php endif; ?>

                                <div class="product-price-section">
                                    <span class="product-price"><?php echo $product->get_price_html(); ?></span>
                                    <?php
                                    if ( $product->is_on_sale() ) {
                                        $regular_price = $product->get_regular_price();
                                        $sale_price    = $product->get_sale_price();
                                        if ( $regular_price && $sale_price ) {
                                            $discount_percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
                                            echo '<span class="product-discount">-' . esc_html( $discount_percentage ) . '%</span>';
                                        }
                                    }
                                    ?>
                                </div>

                                <div class="product-actions">
                                    <?php
                                    // Add to cart button
                                    woocommerce_template_loop_add_to_cart();
                                    ?>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>

            <div class="text-center mt-2">
                <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="button">
                    <?php esc_html_e( 'View All Products', 'aakaari-brand' ); ?>
                </a>
            </div>
        </div>
    </section>
    <?php
}

/**
 * Display Newsletter Section
 */
function aakaari_brand_newsletter_section() {
    if ( ! get_theme_mod( 'aakaari_brand_show_newsletter', true ) ) {
        return;
    }

    ?>
    <section class="newsletter-section" role="region" aria-label="<?php esc_attr_e( 'Newsletter Signup', 'aakaari-brand' ); ?>">
        <div class="site-container">
            <div class="newsletter-content">
                <h2 class="newsletter-title">
                    <?php echo esc_html( get_theme_mod( 'aakaari_brand_newsletter_title', 'Join Our Newsletter' ) ); ?>
                </h2>
                <p class="newsletter-description">
                    <?php echo esc_html( get_theme_mod( 'aakaari_brand_newsletter_description', 'Subscribe to get special offers, free giveaways, and exclusive deals.' ) ); ?>
                </p>
                <form class="newsletter-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                    <input
                        type="hidden"
                        name="action"
                        value="aakaari_brand_newsletter_signup"
                    />
                    <?php wp_nonce_field( 'newsletter_signup', 'newsletter_nonce' ); ?>
                    <input
                        type="email"
                        name="newsletter_email"
                        class="newsletter-input"
                        placeholder="<?php esc_attr_e( 'Enter your email', 'aakaari-brand' ); ?>"
                        required
                        aria-label="<?php esc_attr_e( 'Email address', 'aakaari-brand' ); ?>"
                    />
                    <button type="submit" class="newsletter-button">
                        <?php esc_html_e( 'Subscribe', 'aakaari-brand' ); ?>
                    </button>
                </form>
            </div>
        </div>
    </section>
    <?php
}

/**
 * Display Features Section
 */
function aakaari_brand_features_section() {
    if ( ! get_theme_mod( 'aakaari_brand_show_features', true ) ) {
        return;
    }

    $features = array(
        array(
            'icon'        => '🚚',
            'title'       => __( 'Free Shipping', 'aakaari-brand' ),
            'description' => __( 'Free shipping on orders over $100', 'aakaari-brand' ),
        ),
        array(
            'icon'        => '🔒',
            'title'       => __( 'Secure Payment', 'aakaari-brand' ),
            'description' => __( '100% secure payment processing', 'aakaari-brand' ),
        ),
        array(
            'icon'        => '↩️',
            'title'       => __( 'Easy Returns', 'aakaari-brand' ),
            'description' => __( '30-day return policy on all items', 'aakaari-brand' ),
        ),
        array(
            'icon'        => '💬',
            'title'       => __( '24/7 Support', 'aakaari-brand' ),
            'description' => __( 'Dedicated customer support team', 'aakaari-brand' ),
        ),
    );

    $features = apply_filters( 'aakaari_brand_homepage_features', $features );

    ?>
    <section class="features-section" role="region" aria-label="<?php esc_attr_e( 'Our Features', 'aakaari-brand' ); ?>">
        <div class="site-container">
            <div class="features-grid">
                <?php foreach ( $features as $feature ) : ?>
                    <div class="feature-item">
                        <div class="feature-icon" role="img" aria-label="<?php echo esc_attr( $feature['title'] ); ?>">
                            <?php echo $feature['icon']; ?>
                        </div>
                        <h3 class="feature-title"><?php echo esc_html( $feature['title'] ); ?></h3>
                        <p class="feature-description"><?php echo esc_html( $feature['description'] ); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
}

/**
 * Handle newsletter signup
 */
function aakaari_brand_handle_newsletter_signup() {
    // Verify nonce
    if ( ! isset( $_POST['newsletter_nonce'] ) || ! wp_verify_nonce( $_POST['newsletter_nonce'], 'newsletter_signup' ) ) {
        wp_die( esc_html__( 'Security check failed', 'aakaari-brand' ) );
    }

    $email = isset( $_POST['newsletter_email'] ) ? sanitize_email( $_POST['newsletter_email'] ) : '';

    if ( ! is_email( $email ) ) {
        wp_redirect( add_query_arg( 'newsletter', 'invalid', wp_get_referer() ) );
        exit;
    }

    // Here you can integrate with your email marketing service
    // For now, we'll just store it as a custom post type or option
    $subscribers = get_option( 'aakaari_brand_newsletter_subscribers', array() );

    if ( ! in_array( $email, $subscribers ) ) {
        $subscribers[] = $email;
        update_option( 'aakaari_brand_newsletter_subscribers', $subscribers );

        // Send notification email to admin
        $admin_email = get_option( 'admin_email' );
        $subject     = sprintf( __( 'New Newsletter Subscriber: %s', 'aakaari-brand' ), $email );
        $message     = sprintf( __( 'A new user has subscribed to your newsletter: %s', 'aakaari-brand' ), $email );

        wp_mail( $admin_email, $subject, $message );
    }

    wp_redirect( add_query_arg( 'newsletter', 'success', wp_get_referer() ) );
    exit;
}
add_action( 'admin_post_nopriv_aakaari_brand_newsletter_signup', 'aakaari_brand_handle_newsletter_signup' );
add_action( 'admin_post_aakaari_brand_newsletter_signup', 'aakaari_brand_handle_newsletter_signup' );

/**
 * Add homepage customizer settings
 */
function aakaari_brand_homepage_customizer( $wp_customize ) {
    // Homepage Section
    $wp_customize->add_section( 'aakaari_brand_homepage', array(
        'title'    => __( 'Homepage Settings', 'aakaari-brand' ),
        'priority' => 35,
    ) );

    // Hero Image
    $wp_customize->add_setting( 'aakaari_brand_hero_image', array(
        'default'           => 'https://images.unsplash.com/photo-1641736755184-67380b9a002c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwZmFzaGlvbiUyMG1vZGVsfGVufDF8fHx8MTc2MjIzODI1Nnww&ixlib=rb-4.1.0&q=80&w=1080',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'aakaari_brand_hero_image', array(
        'label'    => __( 'Hero Background Image', 'aakaari-brand' ),
        'section'  => 'aakaari_brand_homepage',
        'settings' => 'aakaari_brand_hero_image',
    ) ) );

    // Hero Title
    $wp_customize->add_setting( 'aakaari_brand_hero_title', array(
        'default'           => 'ELEVATE YOUR STYLE',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'aakaari_brand_hero_title', array(
        'label'    => __( 'Hero Title', 'aakaari-brand' ),
        'section'  => 'aakaari_brand_homepage',
        'settings' => 'aakaari_brand_hero_title',
        'type'     => 'text',
    ) );

    // Hero Subtitle
    $wp_customize->add_setting( 'aakaari_brand_hero_subtitle', array(
        'default'           => 'Premium men\'s fashion crafted for the modern gentleman',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'aakaari_brand_hero_subtitle', array(
        'label'    => __( 'Hero Subtitle', 'aakaari-brand' ),
        'section'  => 'aakaari_brand_homepage',
        'settings' => 'aakaari_brand_hero_subtitle',
        'type'     => 'textarea',
    ) );

    // Hero Button Text
    $wp_customize->add_setting( 'aakaari_brand_hero_button_text', array(
        'default'           => 'Shop Collection',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'aakaari_brand_hero_button_text', array(
        'label'    => __( 'Hero Button Text', 'aakaari-brand' ),
        'section'  => 'aakaari_brand_homepage',
        'settings' => 'aakaari_brand_hero_button_text',
        'type'     => 'text',
    ) );

    // Categories Title
    $wp_customize->add_setting( 'aakaari_brand_categories_title', array(
        'default'           => 'Shop by Category',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'aakaari_brand_categories_title', array(
        'label'    => __( 'Categories Section Title', 'aakaari-brand' ),
        'section'  => 'aakaari_brand_homepage',
        'settings' => 'aakaari_brand_categories_title',
        'type'     => 'text',
    ) );

    // Featured Products Title
    $wp_customize->add_setting( 'aakaari_brand_featured_title', array(
        'default'           => 'Featured Collection',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'aakaari_brand_featured_title', array(
        'label'    => __( 'Featured Products Title', 'aakaari-brand' ),
        'section'  => 'aakaari_brand_homepage',
        'settings' => 'aakaari_brand_featured_title',
        'type'     => 'text',
    ) );

    // Show Newsletter
    $wp_customize->add_setting( 'aakaari_brand_show_newsletter', array(
        'default'           => true,
        'sanitize_callback' => 'aakaari_brand_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'aakaari_brand_show_newsletter', array(
        'label'    => __( 'Show Newsletter Section', 'aakaari-brand' ),
        'section'  => 'aakaari_brand_homepage',
        'settings' => 'aakaari_brand_show_newsletter',
        'type'     => 'checkbox',
    ) );

    // Show Features
    $wp_customize->add_setting( 'aakaari_brand_show_features', array(
        'default'           => true,
        'sanitize_callback' => 'aakaari_brand_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'aakaari_brand_show_features', array(
        'label'    => __( 'Show Features Section', 'aakaari-brand' ),
        'section'  => 'aakaari_brand_homepage',
        'settings' => 'aakaari_brand_show_features',
        'type'     => 'checkbox',
    ) );
}
add_action( 'customize_register', 'aakaari_brand_homepage_customizer' );
