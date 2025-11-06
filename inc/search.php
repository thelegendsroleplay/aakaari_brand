<?php
/**
 * Search Page Functions
 *
 * PHP functions for rendering search page sections with WooCommerce integration
 *
 * @package Aakaari
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Render Search Header
 *
 * Displays the search input and quick filters
 */
function aakaari_render_search_header( $search_query = '' ) {
    $recent_searches = aakaari_get_recent_searches();
    ?>
    <div class="search-header">
        <div class="search-input-wrapper">
            <span class="search-input-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
            </span>
            <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <input type="search"
                       class="search-input"
                       placeholder="Search for products, brands, or categories..."
                       value="<?php echo esc_attr( $search_query ); ?>"
                       name="s"
                       id="search-input">
                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                    <input type="hidden" name="post_type" value="product">
                <?php endif; ?>
            </form>
            <?php if ( ! empty( $search_query ) ) : ?>
                <button class="search-clear" id="clear-search">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            <?php endif; ?>
        </div>

        <?php if ( empty( $search_query ) && ! empty( $recent_searches ) ) : ?>
            <div class="recent-searches">
                <h3>Recent Searches</h3>
                <div class="recent-search-chips">
                    <?php foreach ( $recent_searches as $recent_search ) : ?>
                        <div class="recent-chip" data-search="<?php echo esc_attr( $recent_search ); ?>">
                            <span><?php echo esc_html( $recent_search ); ?></span>
                            <button class="recent-chip-remove" data-search="<?php echo esc_attr( $recent_search ); ?>">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                    <line x1="6" y1="6" x2="18" y2="18"/>
                                </svg>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php
        // Quick filters
        $quick_filters = array(
            'all' => 'All Products',
            'new' => 'New Arrivals',
            'sale' => 'On Sale',
            'featured' => 'Featured',
        );
        ?>
        <div class="quick-filters">
            <?php foreach ( $quick_filters as $filter_key => $filter_label ) :
                $active_class = ( isset( $_GET['filter'] ) && $_GET['filter'] === $filter_key ) || ( ! isset( $_GET['filter'] ) && $filter_key === 'all' ) ? 'active' : '';
            ?>
                <button class="quick-filter-chip <?php echo esc_attr( $active_class ); ?>"
                        data-filter="<?php echo esc_attr( $filter_key ); ?>">
                    <?php echo esc_html( $filter_label ); ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * Render Search Sidebar
 *
 * Displays filters for categories, price, and ratings
 */
function aakaari_render_search_sidebar() {
    // Get product categories
    $categories = get_terms( array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'parent'     => 0,
    ) );

    // Get price range
    global $wpdb;
    $price_range = $wpdb->get_row( "
        SELECT MIN(CAST(meta_value AS DECIMAL)) as min_price,
               MAX(CAST(meta_value AS DECIMAL)) as max_price
        FROM {$wpdb->postmeta}
        WHERE meta_key = '_price'
    " );

    ?>
    <aside class="search-sidebar">

        <!-- Categories Filter -->
        <div class="filter-section">
            <h3 class="filter-title">Categories</h3>
            <div class="filter-options">
                <?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
                    foreach ( $categories as $category ) :
                        $checked = isset( $_GET['product_cat'] ) && in_array( $category->slug, explode( ',', $_GET['product_cat'] ) ) ? 'checked' : '';
                ?>
                    <label class="filter-option">
                        <input type="checkbox"
                               name="product_cat[]"
                               value="<?php echo esc_attr( $category->slug ); ?>"
                               <?php echo $checked; ?>>
                        <span><?php echo esc_html( $category->name ); ?></span>
                        <span class="filter-count"><?php echo esc_html( $category->count ); ?></span>
                    </label>
                <?php endforeach;
                endif; ?>
            </div>
        </div>

        <!-- Price Filter -->
        <div class="filter-section">
            <h3 class="filter-title">Price Range</h3>
            <div class="price-range-inputs">
                <input type="number"
                       class="price-input"
                       placeholder="Min"
                       name="min_price"
                       value="<?php echo isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : ''; ?>"
                       min="<?php echo esc_attr( $price_range->min_price ); ?>"
                       max="<?php echo esc_attr( $price_range->max_price ); ?>">
                <input type="number"
                       class="price-input"
                       placeholder="Max"
                       name="max_price"
                       value="<?php echo isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : ''; ?>"
                       min="<?php echo esc_attr( $price_range->min_price ); ?>"
                       max="<?php echo esc_attr( $price_range->max_price ); ?>">
            </div>
        </div>

        <!-- Rating Filter -->
        <div class="filter-section">
            <h3 class="filter-title">Customer Rating</h3>
            <div class="filter-options">
                <?php for ( $i = 5; $i >= 1; $i-- ) :
                    $checked = isset( $_GET['rating'] ) && intval( $_GET['rating'] ) === $i ? 'checked' : '';
                ?>
                    <label class="filter-option">
                        <input type="radio"
                               name="rating"
                               value="<?php echo esc_attr( $i ); ?>"
                               <?php echo $checked; ?>>
                        <span class="stars">
                            <?php echo str_repeat( '★', $i ); ?>
                            <?php echo str_repeat( '☆', 5 - $i ); ?>
                        </span>
                        <span>& Up</span>
                    </label>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Clear Filters -->
        <button class="action-button" id="clear-filters">Clear All Filters</button>

    </aside>
    <?php
}

/**
 * Render Search Results
 *
 * Displays the product search results
 */
function aakaari_render_search_results( $search_query = '' ) {
    // Save search to recent searches
    if ( ! empty( $search_query ) ) {
        aakaari_save_recent_search( $search_query );
    }

    // Build query args
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 12,
        'post_status'    => 'publish',
        'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
    );

    // Add search query
    if ( ! empty( $search_query ) ) {
        $args['s'] = $search_query;
    }

    // Apply quick filter
    if ( isset( $_GET['filter'] ) ) {
        switch ( $_GET['filter'] ) {
            case 'new':
                $args['date_query'] = array(
                    array(
                        'after' => '30 days ago',
                    ),
                );
                break;
            case 'sale':
                $args['meta_query'] = array(
                    array(
                        'key'     => '_sale_price',
                        'value'   => 0,
                        'compare' => '>',
                        'type'    => 'NUMERIC',
                    ),
                );
                break;
            case 'featured':
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'product_visibility',
                        'field'    => 'name',
                        'terms'    => 'featured',
                    ),
                );
                break;
        }
    }

    // Apply category filter
    if ( isset( $_GET['product_cat'] ) && ! empty( $_GET['product_cat'] ) ) {
        $categories = explode( ',', $_GET['product_cat'] );
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $categories,
            ),
        );
    }

    // Apply price filter
    if ( isset( $_GET['min_price'] ) || isset( $_GET['max_price'] ) ) {
        if ( ! isset( $args['meta_query'] ) ) {
            $args['meta_query'] = array();
        }

        if ( isset( $_GET['min_price'] ) && ! empty( $_GET['min_price'] ) ) {
            $args['meta_query'][] = array(
                'key'     => '_price',
                'value'   => floatval( $_GET['min_price'] ),
                'compare' => '>=',
                'type'    => 'NUMERIC',
            );
        }

        if ( isset( $_GET['max_price'] ) && ! empty( $_GET['max_price'] ) ) {
            $args['meta_query'][] = array(
                'key'     => '_price',
                'value'   => floatval( $_GET['max_price'] ),
                'compare' => '<=',
                'type'    => 'NUMERIC',
            );
        }
    }

    // Apply rating filter
    if ( isset( $_GET['rating'] ) && ! empty( $_GET['rating'] ) ) {
        if ( ! isset( $args['meta_query'] ) ) {
            $args['meta_query'] = array();
        }

        $args['meta_query'][] = array(
            'key'     => '_wc_average_rating',
            'value'   => floatval( $_GET['rating'] ),
            'compare' => '>=',
            'type'    => 'NUMERIC',
        );
    }

    $search_results = new WP_Query( $args );

    ?>
    <div class="search-main">

        <!-- Results Header -->
        <div class="search-results-header">
            <div class="results-info">
                <span class="results-count">
                    <?php
                    if ( $search_results->found_posts > 0 ) {
                        printf(
                            esc_html( _n( '%d result found', '%d results found', $search_results->found_posts, 'aakaari' ) ),
                            $search_results->found_posts
                        );
                    } else {
                        esc_html_e( 'No results found', 'aakaari' );
                    }
                    ?>
                </span>
                <?php if ( ! empty( $search_query ) ) : ?>
                    <span>for "<?php echo esc_html( $search_query ); ?>"</span>
                <?php endif; ?>
            </div>

            <div class="view-toggle">
                <button class="view-button active" data-view="grid">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/>
                    </svg>
                </button>
                <button class="view-button" data-view="list">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="8" y1="6" x2="21" y2="6"/>
                        <line x1="8" y1="12" x2="21" y2="12"/>
                        <line x1="8" y1="18" x2="21" y2="18"/>
                        <line x1="3" y1="6" x2="3.01" y2="6"/>
                        <line x1="3" y1="12" x2="3.01" y2="12"/>
                        <line x1="3" y1="18" x2="3.01" y2="18"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Results Grid -->
        <?php if ( $search_results->have_posts() ) : ?>
            <div class="results-grid" id="search-results-grid">
                <?php
                while ( $search_results->have_posts() ) : $search_results->the_post();
                    global $product;

                    // Get product data
                    $product_id = get_the_ID();
                    $product_image = wp_get_attachment_image_url( $product->get_image_id(), 'full' );
                    if ( ! $product_image ) {
                        $product_image = wc_placeholder_img_src();
                    }
                    $product_name = $product->get_name();
                    $product_link = get_permalink();
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

                    // Check if product is new
                    $post_date = get_the_date( 'U' );
                    $current_date = current_time( 'timestamp' );
                    $is_new = ( $current_date - $post_date ) < ( 30 * DAY_IN_SECONDS );

                    // Check if in wishlist
                    $in_wishlist = aakaari_is_product_in_wishlist( $product_id );
                ?>
                    <div class="search-result-card">
                        <div class="result-image-wrapper">
                            <a href="<?php echo esc_url( $product_link ); ?>">
                                <img src="<?php echo esc_url( $product_image ); ?>"
                                     alt="<?php echo esc_attr( $product_name ); ?>"
                                     class="result-image">
                            </a>

                            <?php if ( $is_on_sale ) : ?>
                                <span class="result-badge">-<?php echo esc_html( $discount_percent ); ?>%</span>
                            <?php elseif ( $is_new ) : ?>
                                <span class="result-badge">New</span>
                            <?php endif; ?>

                            <button class="result-wishlist <?php echo $in_wishlist ? 'active' : ''; ?>"
                                    data-product-id="<?php echo esc_attr( $product_id ); ?>"
                                    aria-label="Add to wishlist">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="<?php echo $in_wishlist ? 'currentColor' : 'none'; ?>" stroke="currentColor" stroke-width="2">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                </svg>
                            </button>
                        </div>

                        <div class="result-info">
                            <?php if ( ! empty( $category_name ) ) : ?>
                                <p class="result-category"><?php echo esc_html( $category_name ); ?></p>
                            <?php endif; ?>

                            <h3 class="result-name">
                                <a href="<?php echo esc_url( $product_link ); ?>">
                                    <?php echo esc_html( $product_name ); ?>
                                </a>
                            </h3>

                            <?php if ( $product_rating > 0 ) : ?>
                                <div class="result-rating">
                                    <div class="stars">
                                        <?php echo aakaari_get_star_rating_html( $product_rating ); ?>
                                    </div>
                                    <span class="rating-count">(<?php echo esc_html( $rating_count ); ?>)</span>
                                </div>
                            <?php endif; ?>

                            <div class="result-price">
                                <?php if ( $is_on_sale ) : ?>
                                    <span class="current-price"><?php echo wc_price( $sale_price ); ?></span>
                                    <span class="original-price"><?php echo wc_price( $regular_price ); ?></span>
                                <?php else : ?>
                                    <span class="current-price"><?php echo wc_price( $regular_price ); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <?php
            $total_pages = $search_results->max_num_pages;
            if ( $total_pages > 1 ) :
            ?>
                <div class="search-pagination">
                    <?php
                    echo paginate_links( array(
                        'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                        'format'    => '?paged=%#%',
                        'current'   => max( 1, get_query_var( 'paged' ) ),
                        'total'     => $total_pages,
                        'prev_text' => '&larr; Previous',
                        'next_text' => 'Next &rarr;',
                    ) );
                    ?>
                </div>
            <?php endif; ?>

        <?php else : ?>
            <!-- Empty State -->
            <div class="search-empty">
                <div class="empty-icon">🔍</div>
                <h2>No results found</h2>
                <p>Try adjusting your search or filters to find what you're looking for.</p>
                <?php if ( ! empty( $search_query ) ) : ?>
                    <button class="hero-button primary" onclick="document.getElementById('search-input').focus();">
                        Try Another Search
                    </button>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
    <?php

    wp_reset_postdata();
}

/**
 * Get recent searches for current user
 */
function aakaari_get_recent_searches() {
    if ( ! isset( $_COOKIE['aakaari_recent_searches'] ) ) {
        return array();
    }

    $recent_searches = json_decode( stripslashes( $_COOKIE['aakaari_recent_searches'] ), true );
    return is_array( $recent_searches ) ? $recent_searches : array();
}

/**
 * Save search to recent searches
 */
function aakaari_save_recent_search( $search_query ) {
    $recent_searches = aakaari_get_recent_searches();

    // Remove if already exists
    $recent_searches = array_diff( $recent_searches, array( $search_query ) );

    // Add to beginning
    array_unshift( $recent_searches, $search_query );

    // Keep only last 5
    $recent_searches = array_slice( $recent_searches, 0, 5 );

    // Save to cookie
    setcookie( 'aakaari_recent_searches', json_encode( $recent_searches ), time() + ( 30 * DAY_IN_SECONDS ), '/' );
}

/**
 * Check if product is in wishlist
 */
function aakaari_is_product_in_wishlist( $product_id ) {
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $wishlist = get_user_meta( $user_id, 'aakaari_wishlist', true );
        return is_array( $wishlist ) && in_array( $product_id, $wishlist );
    } else {
        if ( isset( $_COOKIE['aakaari_wishlist'] ) ) {
            $wishlist = json_decode( stripslashes( $_COOKIE['aakaari_wishlist'] ), true );
            return is_array( $wishlist ) && in_array( $product_id, $wishlist );
        }
    }
    return false;
}

/**
 * Get star rating HTML
 */
function aakaari_get_star_rating_html( $rating ) {
    $full_stars = floor( $rating );
    $half_star = ( $rating - $full_stars ) >= 0.5;
    $empty_stars = 5 - ceil( $rating );

    $html = '';

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

    return $html;
}

/**
 * AJAX handler for search filters
 */
add_action( 'wp_ajax_aakaari_filter_search', 'aakaari_ajax_filter_search' );
add_action( 'wp_ajax_nopriv_aakaari_filter_search', 'aakaari_ajax_filter_search' );

function aakaari_ajax_filter_search() {
    check_ajax_referer( 'aakaari-search-nonce', 'nonce' );

    ob_start();

    // Get search query from AJAX request
    $search_query = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';

    // Simulate $_GET parameters from AJAX
    if ( isset( $_POST['filters'] ) ) {
        parse_str( $_POST['filters'], $_GET );
    }

    aakaari_render_search_results( $search_query );

    $html = ob_get_clean();

    wp_send_json_success( array( 'html' => $html ) );
}
