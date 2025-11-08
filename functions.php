<?php
/**
 * functions.php
 * Aakaari custom WooCommerce theme
 *
 * - Enqueues CSS/JS from assets/css/ and assets/js/
 * - Deletes all pages on theme activation, then recreates required WooCommerce pages
 * (Home, Shop, Cart, Checkout, My Account, Terms)
 *
 * WARNING: Activating this theme permanently deletes all pages (if activation code runs).
 * Please back up your database before activating.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Include Custom Live Chat Support System
 */
require_once get_template_directory() . '/inc/live-chat-system.php';

/**
 * Include WooCommerce Product Filters
 */
require_once get_template_directory() . '/inc/wc-product-filters.php';

/**
 * Include WooCommerce Color Attributes
 */
require_once get_template_directory() . '/inc/wc-color-attributes.php';

/**
 * Include Shop Page Functionality (AJAX handlers, markup)
 */
require_once get_template_directory() . '/inc/shop.php';

/**
 * Include Custom Wishlist System
 */
require_once get_template_directory() . '/inc/wishlist.php';

/**
 * Theme setup (support, menus, image sizes)
 */
function aakaari_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'woocommerce' );

    // Enable AJAX add to cart
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'aakaari' ),
    ) );

    add_image_size( 'aakaari-product', 600, 600, true );
}
add_action( 'after_setup_theme', 'aakaari_theme_setup' );

/**
 * Enable AJAX add to cart for all products
 */
add_filter( 'woocommerce_product_add_to_cart_url', 'aakaari_enable_ajax_add_to_cart', 10, 2 );
function aakaari_enable_ajax_add_to_cart( $url, $product ) {
    // Return empty to use AJAX add to cart
    return '';
}

/**
 * Add AJAX add to cart class to all add to cart buttons
 */
add_filter( 'woocommerce_loop_add_to_cart_link', 'aakaari_ajax_add_to_cart_class', 10, 2 );
function aakaari_ajax_add_to_cart_class( $html, $product ) {
    // Add ajax_add_to_cart class
    $html = str_replace( 'class="', 'class="ajax_add_to_cart ', $html );
    return $html;
}

/**
 * Enqueue assets from assets/css and assets/js
 */
function aakaari_enqueue_assets() {
    $theme = wp_get_theme();
    $theme_version = $theme ? $theme->get( 'Version' ) : time();
    $assets_base = get_stylesheet_directory_uri() . '/assets';

    // CSS Reset - load first to override WordPress defaults
    wp_enqueue_style(
        'aakaari-reset',
        $assets_base . '/css/reset.css',
        array(),
        $theme_version
    );

    // Global styles (header, footer) - load on all pages
    wp_enqueue_style(
        'aakaari-header',
        $assets_base . '/css/header.css',
        array( 'aakaari-reset' ),
        $theme_version
    );

    wp_enqueue_style(
        'aakaari-footer',
        $assets_base . '/css/footer.css',
        array( 'aakaari-reset' ),
        $theme_version
    );

    // Header JS (mobile menu)
    wp_enqueue_script(
        'aakaari-header-js',
        $assets_base . '/js/header.js',
        array( 'jquery' ),
        $theme_version,
        true
    );

    // Homepage assets
    if ( is_front_page() || is_home() ) {
        wp_enqueue_style(
            'aakaari-homepage',
            $assets_base . '/css/homepage.css',
            array( 'aakaari-reset' ),
            $theme_version
        );

        wp_enqueue_script(
            'aakaari-homepage-js',
            $assets_base . '/js/homepage.js',
            array( 'jquery' ),
            $theme_version,
            true
        );

        wp_localize_script( 'aakaari-homepage-js', 'AakaariData', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'site_url' => home_url( '/' ),
        ) );
    }

    // Products/Shop pages - Check if WooCommerce is active
    if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) ) {
        // Enqueue products CSS (includes all shop page styles)
        wp_enqueue_style(
            'aakaari-products',
            $assets_base . '/css/products.css',
            array( 'aakaari-reset' ),
            time() // Force cache refresh
        );

        // Enqueue toast notifications CSS
        wp_enqueue_style(
            'aakaari-toast',
            $assets_base . '/css/toast-notifications.css',
            array(),
            time() // Force cache refresh
        );

        // Enqueue toast notifications JS (load before products.js)
        wp_enqueue_script(
            'aakaari-toast',
            $assets_base . '/js/toast-notifications.js',
            array(),
            time(), // Force cache refresh
            true
        );

        // Enqueue products JS for AJAX filtering
        wp_enqueue_script(
            'aakaari-products',
            $assets_base . '/js/products.js',
            array( 'aakaari-toast' ),
            time(), // Force cache refresh
            true
        );

        // Localize AJAX data for products page
        wp_localize_script( 'aakaari-products', 'aakaari_ajax', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'aakaari_ajax_nonce' ),
            'home_url' => home_url(),
        ) );

        // Optional page type for filtering logic
        $page_type = 'products';
        if ( is_product_category() ) {
            $category = get_queried_object();
            $page_type = $category->slug;
        }
        wp_add_inline_script( 'aakaari-products', 'window.aakaari_page_type = "' . esc_js( $page_type ) . '";', 'before' );
    }

    // Cart page
    if ( function_exists( 'is_cart' ) && is_cart() ) {
        wp_enqueue_style(
            'aakaari-cart',
            $assets_base . '/css/cart.css',
            array( 'aakaari-reset' ), // Added dependency
            $theme_version
        );

        wp_enqueue_script(
            'aakaari-cart-js',
            $assets_base . '/js/cart.js',
            array( 'jquery' ),
            $theme_version,
            true
        );
    }

    // ========================================================================
    // ** START: SINGLE PRODUCT PAGE FIX **
    // ========================================================================
    if ( function_exists( 'is_product' ) && is_product() ) {

        wp_enqueue_script(
            'aakaari-product-detail',
            $assets_base . '/js/product-detail.js',
            array( 'jquery' ),
            '2.0.0', // Updated: Major attribute/variation fixes
            true
        );

        wp_enqueue_style(
            'aakaari-product-detail-css',
            $assets_base . '/css/product-detail.css',
            array('aakaari-header', 'aakaari-footer'),
            '2.0.0' // Updated: Tabs, reviews, related products styles
        );
    }
    // ========================================================================
    // ** END: SINGLE PRODUCT PAGE FIX **
    // ========================================================================

    // Checkout page
    if ( function_exists( 'is_checkout' ) && is_checkout() ) {
        wp_enqueue_style(
            'aakaari-checkout',
            $assets_base . '/css/checkout.css',
            array( 'aakaari-reset' ), // Added dependency
            $theme_version
        );
    }

    // Account pages
    if ( function_exists( 'is_account_page' ) && is_account_page() ) {
        // Auth styles for login/register pages
        if ( ! is_user_logged_in() ) {
            wp_enqueue_style(
                'aakaari-auth',
                $assets_base . '/css/auth.css',
                array( 'aakaari-reset' ),
                $theme_version
            );
        } else {
            // Account dashboard styles
            wp_enqueue_style(
                'aakaari-account',
                $assets_base . '/css/account.css',
                array( 'aakaari-reset' ),
                $theme_version
            );
        }
    }

    // Page-specific styles
    if ( is_page() ) {
        $page_slug = get_post_field( 'post_name', get_post() );
        $page_css_file = $assets_base . '/css/' . $page_slug . '.css';

        // Check if a CSS file exists for this page
        if ( file_exists( get_stylesheet_directory() . '/assets/css/' . $page_slug . '.css' ) ) {
            wp_enqueue_style(
                'aakaari-page-' . $page_slug,
                $page_css_file,
                array('aakaari-header'), // Added dependency
                $theme_version
            );
        }
    }

    // Live Chat Widget - Load on all pages
    wp_enqueue_style(
        'aakaari-live-chat',
        $assets_base . '/css/live-chat.css',
        array(),
        $theme_version
    );

    wp_enqueue_script(
        'aakaari-live-chat-js',
        $assets_base . '/js/live-chat.js',
        array( 'jquery' ),
        $theme_version,
        true
    );

    // Localize script for AJAX
    wp_localize_script( 'aakaari-live-chat-js', 'aakaari_chat', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'aakaari_chat_nonce' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'aakaari_enqueue_assets' );

/**
 * Handle "Buy Now" button redirect.
 * This checks if our hidden field was submitted and redirects to checkout.
 */
function aakaari_buy_now_redirect( $url ) {
    // Check if buy now field is set AND equals '1'
    if ( ! isset( $_REQUEST['aakaari_buy_now'] ) || $_REQUEST['aakaari_buy_now'] !== '1' ) {
        return $url; // Not a buy now request, use default cart redirect
    }

    // It was a buy now request - redirect to checkout instead of cart
    return wc_get_checkout_url();
}
add_filter( 'woocommerce_add_to_cart_success_redirect', 'aakaari_buy_now_redirect', 99 );


/**
 * Include homepage helper functions if present
 */
if ( file_exists( get_template_directory() . '/inc/homepage.php' ) ) {
    require_once get_template_directory() . '/inc/homepage.php';
}


// ========================================================================
// ** !! DANGER !! **
// The function below is DESTRUCTIVE. It deletes ALL pages from your
// database every time the theme is activated.
// It is correctly commented out. DO NOT UN-COMMENT IT unless you
// are 100% sure you want to wipe your pages and start over.
// ========================================================================
/*
function aakaari_delete_all_pages_and_create_required() {
    // ... (rest of destructive function is commented out) ...
}
add_action( 'after_switch_theme', 'aakaari_delete_all_pages_and_create_required' );
*/


/**
 * WooCommerce Page Customizations
 * Remove default WooCommerce features that conflict with custom design
 */

// Disable WooCommerce default cart styles
add_filter( 'woocommerce_enqueue_styles', 'aakaari_disable_woocommerce_cart_styles' );
function aakaari_disable_woocommerce_cart_styles( $enqueue_styles ) {
    if ( is_cart() ) {
        // Disable default WooCommerce styles on cart page
        unset( $enqueue_styles['woocommerce-general'] );
        unset( $enqueue_styles['woocommerce-layout'] );
        unset( $enqueue_styles['woocommerce-smallscreen'] );
    }
    return $enqueue_styles;
}

// Remove WooCommerce cart page wrapper
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

// Remove WooCommerce breadcrumbs on cart page
add_action( 'template_redirect', 'aakaari_remove_cart_breadcrumbs' );
function aakaari_remove_cart_breadcrumbs() {
    if ( is_cart() ) {
        remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
    }
}

// This removes the default wrappers on the Single Product page
// so that your custom `.product-page` wrappers in `content-single-product.php`
// can take over the full page layout.
add_action( 'template_redirect', 'aakaari_remove_product_wrappers' );
function aakaari_remove_product_wrappers() {
    if ( is_product() ) {
        remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
        remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
    }
}

/**
 * Customize WooCommerce Product Tabs
 * Add custom Size Chart tab and customize default tabs
 */
add_filter( 'woocommerce_product_tabs', 'aakaari_customize_product_tabs', 98 );
function aakaari_customize_product_tabs( $tabs ) {
    global $product;

    // Customize Description tab
    if ( isset( $tabs['description'] ) ) {
        $tabs['description']['title'] = __( 'Description', 'aakaari' );
        $tabs['description']['priority'] = 10;
    }

    // Add custom Size Chart tab
    $tabs['size_chart'] = array(
        'title'    => __( 'Size Chart', 'aakaari' ),
        'priority' => 20,
        'callback' => 'aakaari_size_chart_tab_content'
    );

    // Customize Reviews tab
    if ( isset( $tabs['reviews'] ) ) {
        $tabs['reviews']['title'] = __( 'Reviews', 'aakaari' );
        $tabs['reviews']['priority'] = 30;
        $tabs['reviews']['callback'] = 'aakaari_reviews_tab_content';
    }

    // Remove Additional Information tab (we don't need it)
    unset( $tabs['additional_information'] );

    return $tabs;
}

/**
 * Size Chart Tab Content
 */
function aakaari_size_chart_tab_content() {
    global $product;

    // Try to get size chart from product meta
    $size_chart = get_post_meta( $product->get_id(), '_size_chart', true );

    // Try to get from product attribute
    if ( empty( $size_chart ) ) {
        $attributes = $product->get_attributes();
        foreach ( $attributes as $attr ) {
            $name = $attr->get_name();
            if ( stripos( $name, 'size' ) !== false && stripos( $name, 'chart' ) !== false ) {
                $size_chart = $attr->get_options();
                if ( is_array( $size_chart ) ) {
                    $size_chart = implode( ', ', $size_chart );
                }
                break;
            }
        }
    }

    echo '<div class="size-chart-content">';
    echo '<h2>' . esc_html__( 'Size Chart', 'aakaari' ) . '</h2>';

    if ( ! empty( $size_chart ) ) {
        echo '<div class="size-chart-table">' . wp_kses_post( $size_chart ) . '</div>';
    } else {
        // Default size chart table
        echo '<table class="size-chart-table">';
        echo '<thead><tr><th>Size</th><th>Chest (inches)</th><th>Waist (inches)</th><th>Length (inches)</th></tr></thead>';
        echo '<tbody>';
        echo '<tr><td>S</td><td>36-38</td><td>30-32</td><td>27-28</td></tr>';
        echo '<tr><td>M</td><td>38-40</td><td>32-34</td><td>28-29</td></tr>';
        echo '<tr><td>L</td><td>40-42</td><td>34-36</td><td>29-30</td></tr>';
        echo '<tr><td>XL</td><td>42-44</td><td>36-38</td><td>30-31</td></tr>';
        echo '<tr><td>XXL</td><td>44-46</td><td>38-40</td><td>31-32</td></tr>';
        echo '</tbody>';
        echo '</table>';
        echo '<p class="size-chart-note">' . esc_html__( 'Note: This is a standard size chart. Please check product-specific measurements if available.', 'aakaari' ) . '</p>';
    }

    echo '</div>';
}

/**
 * Custom Reviews Tab Content with Rating Summary
 */
function aakaari_reviews_tab_content() {
    global $product;

    if ( ! comments_open() ) {
        return;
    }

    $product_id = $product->get_id();
    $avg_rating = $product->get_average_rating();
    $review_count = $product->get_rating_count();

    echo '<div id="reviews" class="woocommerce-Reviews">';
    echo '<div id="comments">';

    // Reviews Summary Section
    echo '<div class="reviews-summary">';
    echo '<div class="reviews-score">';
    echo '<div class="score-number">' . number_format( $avg_rating, 1 ) . '</div>';
    echo '<div class="score-stars">';
    echo wc_get_rating_html( $avg_rating );
    echo '</div>';
    echo '<div class="score-text">' . sprintf( _n( '%s review', '%s reviews', $review_count, 'aakaari' ), number_format_i18n( $review_count ) ) . '</div>';
    echo '</div>';

    // Rating bars (5 to 1 stars)
    echo '<div class="reviews-bars">';
    for ( $i = 5; $i >= 1; $i-- ) {
        $rating_count = 0;
        $comments = get_comments( array(
            'post_id' => $product_id,
            'status'  => 'approve',
            'type'    => 'review',
            'meta_query' => array(
                array(
                    'key'     => 'rating',
                    'value'   => $i,
                    'compare' => '='
                )
            )
        ) );
        $rating_count = count( $comments );
        $percentage = $review_count > 0 ? ( $rating_count / $review_count ) * 100 : 0;

        echo '<div class="review-bar">';
        echo '<span class="bar-label">' . $i . ' ★</span>';
        echo '<div class="bar-track"><div class="bar-fill animated" style="width: ' . $percentage . '%"></div></div>';
        echo '<span class="bar-count">' . $rating_count . '</span>';
        echo '</div>';
    }
    echo '</div>'; // .reviews-bars
    echo '</div>'; // .reviews-summary

    // Display existing reviews
    if ( $review_count > 0 ) {
        echo '<div class="reviews-list">';
        echo '<h3 class="woocommerce-Reviews-title">' . esc_html__( 'Customer Reviews', 'aakaari' ) . '</h3>';

        $comments = get_comments( array(
            'post_id' => $product_id,
            'status'  => 'approve',
            'type'    => 'review',
            'orderby' => 'comment_date_gmt',
            'order'   => 'DESC'
        ) );

        echo '<ol class="commentlist">';
        wp_list_comments( array(
            'callback' => 'aakaari_review_display_callback'
        ), $comments );
        echo '</ol>';

        echo '</div>';
    }

    echo '</div>'; // #comments

    // Review Form
    if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product_id ) ) {
        echo '<div class="reviews-share">';
        echo '<p>' . esc_html__( 'Share your experience with this product', 'aakaari' ) . '</p>';
        echo '</div>';

        $commenter = wp_get_current_commenter();
        $req = get_option( 'require_name_email' );
        $aria_req = ( $req ? " aria-required='true'" : '' );

        $comment_form = array(
            'title_reply'          => esc_html__( 'Write a review', 'aakaari' ),
            'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'aakaari' ),
            'title_reply_before'   => '<span id="reply-title" class="comment-reply-title">',
            'title_reply_after'    => '</span>',
            'comment_notes_before' => '',
            'comment_notes_after'  => '',
            'label_submit'         => esc_html__( 'Submit Review', 'aakaari' ),
            'logged_in_as'         => '',
            'comment_field'        => '',
        );

        $comment_form['fields'] = array();

        $comment_form['fields']['author'] = '<p class="comment-form-author">' .
            '<label for="author">' . esc_html__( 'Name', 'aakaari' ) . '&nbsp;<span class="required">*</span></label> ' .
            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>';

        $comment_form['fields']['email'] = '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'aakaari' ) . '&nbsp;<span class="required">*</span></label> ' .
            '<input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>';

        $comment_form['comment_field'] = '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your rating', 'aakaari' ) . '&nbsp;<span class="required">*</span></label><select name="rating" id="rating" required>
            <option value="">' . esc_html__( 'Rate…', 'aakaari' ) . '</option>
            <option value="5">' . esc_html__( '5 - Excellent', 'aakaari' ) . '</option>
            <option value="4">' . esc_html__( '4 - Very good', 'aakaari' ) . '</option>
            <option value="3">' . esc_html__( '3 - Average', 'aakaari' ) . '</option>
            <option value="2">' . esc_html__( '2 - Not that bad', 'aakaari' ) . '</option>
            <option value="1">' . esc_html__( '1 - Very poor', 'aakaari' ) . '</option>
        </select></div>';

        $comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Your review', 'aakaari' ) . '&nbsp;<span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" required></textarea></p>';

        comment_form( $comment_form, $product_id );
    } else {
        echo '<p class="woocommerce-verification-required">' . esc_html__( 'Only logged in customers who have purchased this product may leave a review.', 'aakaari' ) . '</p>';
    }

    echo '</div>'; // #reviews
}

/**
 * Custom Review Display Callback
 */
function aakaari_review_display_callback( $comment, $args, $depth ) {
    $rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <div id="comment-<?php comment_ID(); ?>" class="comment_container review-item">
            <div class="review-header">
                <div class="stars">
                    <?php echo wc_get_rating_html( $rating ); ?>
                </div>
                <span class="review-author"><?php comment_author(); ?></span>
                <span class="review-date"><?php echo get_comment_date( 'M j, Y' ); ?></span>
            </div>
            <div class="review-content">
                <?php comment_text(); ?>
            </div>
        </div>
    </li>
    <?php
}

// Remove WooCommerce default related products
// We'll add a custom one in the template
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );