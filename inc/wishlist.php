<?php
/**
 * Wishlist Page Functions
 *
 * PHP functions for managing wishlist with WooCommerce integration
 *
 * @package Aakaari
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Render Wishlist Header
 *
 * Displays the wishlist title and stats
 */
function aakaari_render_wishlist_header() {
    $wishlist_items = aakaari_get_wishlist_items();
    $total_items = count( $wishlist_items );
    $total_value = 0;

    // Calculate total value
    foreach ( $wishlist_items as $product_id ) {
        $product = wc_get_product( $product_id );
        if ( $product ) {
            $total_value += floatval( $product->get_price() );
        }
    }

    ?>
    <div class="wishlist-header">
        <div class="wishlist-title-section">
            <h1>My Wishlist</h1>
            <button class="action-button" id="share-wishlist">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="18" cy="5" r="3"/>
                    <circle cx="6" cy="12" r="3"/>
                    <circle cx="18" cy="19" r="3"/>
                    <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
                    <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                </svg>
                Share Wishlist
            </button>
        </div>

        <div class="wishlist-stats">
            <div class="wishlist-stat">
                <span class="stat-label">Total Items</span>
                <span class="stat-value"><?php echo esc_html( $total_items ); ?></span>
            </div>
            <div class="wishlist-stat">
                <span class="stat-label">Total Value</span>
                <span class="stat-value"><?php echo wc_price( $total_value ); ?></span>
            </div>
            <div class="wishlist-stat">
                <span class="stat-label">In Stock</span>
                <span class="stat-value"><?php echo esc_html( aakaari_count_in_stock_items( $wishlist_items ) ); ?></span>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render Wishlist Actions
 *
 * Displays bulk action buttons
 */
function aakaari_render_wishlist_actions() {
    $wishlist_items = aakaari_get_wishlist_items();

    if ( empty( $wishlist_items ) ) {
        return;
    }

    ?>
    <div class="wishlist-actions">
        <button class="action-button primary" id="add-all-to-cart">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1"/>
                <circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
            </svg>
            Add All to Cart
        </button>

        <button class="action-button" id="clear-wishlist">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
            </svg>
            Clear All
        </button>
    </div>
    <?php
}

/**
 * Render Wishlist Grid
 *
 * Displays the wishlist products
 */
function aakaari_render_wishlist_grid() {
    $wishlist_items = aakaari_get_wishlist_items();

    if ( empty( $wishlist_items ) ) {
        aakaari_render_empty_wishlist();
        return;
    }

    ?>
    <div class="wishlist-grid" id="wishlist-grid">
        <?php
        foreach ( $wishlist_items as $product_id ) {
            $product = wc_get_product( $product_id );

            if ( ! $product ) {
                continue;
            }

            // Get product data
            $product_image = wp_get_attachment_image_url( $product->get_image_id(), 'full' );
            if ( ! $product_image ) {
                $product_image = wc_placeholder_img_src();
            }
            $product_name = $product->get_name();
            $product_link = get_permalink( $product_id );
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

            // Check stock status
            $stock_status = $product->get_stock_status();
            $stock_quantity = $product->get_stock_quantity();

            // Determine stock badge
            $stock_badge_class = 'in-stock';
            $stock_badge_text = 'In Stock';

            if ( $stock_status === 'outofstock' ) {
                $stock_badge_class = 'out-of-stock';
                $stock_badge_text = 'Out of Stock';
            } elseif ( $stock_quantity && $stock_quantity <= 5 ) {
                $stock_badge_class = 'low-stock';
                $stock_badge_text = 'Only ' . $stock_quantity . ' left';
            }

            // Get date added to wishlist
            $date_added = aakaari_get_wishlist_item_date( $product_id );
        ?>
            <div class="wishlist-card" data-product-id="<?php echo esc_attr( $product_id ); ?>">
                <input type="checkbox" class="wishlist-card-checkbox" value="<?php echo esc_attr( $product_id ); ?>">

                <div class="wishlist-image-wrapper">
                    <a href="<?php echo esc_url( $product_link ); ?>">
                        <img src="<?php echo esc_url( $product_image ); ?>"
                             alt="<?php echo esc_attr( $product_name ); ?>"
                             class="wishlist-image">
                    </a>

                    <span class="stock-badge <?php echo esc_attr( $stock_badge_class ); ?>">
                        <?php echo esc_html( $stock_badge_text ); ?>
                    </span>

                    <button class="wishlist-remove"
                            data-product-id="<?php echo esc_attr( $product_id ); ?>"
                            aria-label="Remove from wishlist">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>

                <div class="wishlist-info">
                    <?php if ( ! empty( $category_name ) ) : ?>
                        <p class="wishlist-category"><?php echo esc_html( $category_name ); ?></p>
                    <?php endif; ?>

                    <h3 class="wishlist-name">
                        <a href="<?php echo esc_url( $product_link ); ?>">
                            <?php echo esc_html( $product_name ); ?>
                        </a>
                    </h3>

                    <?php if ( $product_rating > 0 ) : ?>
                        <div class="wishlist-rating">
                            <div class="stars">
                                <?php echo aakaari_get_star_rating_html( $product_rating ); ?>
                            </div>
                            <span class="rating-count">(<?php echo esc_html( $rating_count ); ?>)</span>
                        </div>
                    <?php endif; ?>

                    <div class="wishlist-price-section">
                        <div class="wishlist-price">
                            <?php if ( $is_on_sale ) : ?>
                                <span class="current-price"><?php echo wc_price( $sale_price ); ?></span>
                                <span class="original-price"><?php echo wc_price( $regular_price ); ?></span>
                            <?php else : ?>
                                <span class="current-price"><?php echo wc_price( $regular_price ); ?></span>
                            <?php endif; ?>
                        </div>

                        <?php if ( $discount_percent > 0 ) : ?>
                            <span class="discount-badge">-<?php echo esc_html( $discount_percent ); ?>%</span>
                        <?php endif; ?>
                    </div>

                    <?php if ( $date_added ) : ?>
                        <p class="date-added">Added <?php echo esc_html( human_time_diff( $date_added, current_time( 'timestamp' ) ) ); ?> ago</p>
                    <?php endif; ?>

                    <?php if ( $is_on_sale ) : ?>
                        <div class="price-alert">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                            Price dropped by <?php echo esc_html( $discount_percent ); ?>%!
                        </div>
                    <?php endif; ?>
                </div>

                <div class="wishlist-card-actions">
                    <button class="add-to-cart-btn"
                            data-product-id="<?php echo esc_attr( $product_id ); ?>"
                            <?php echo $stock_status === 'outofstock' ? 'disabled' : ''; ?>>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="9" cy="21" r="1"/>
                            <circle cx="20" cy="21" r="1"/>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>
                        <?php echo $stock_status === 'outofstock' ? 'Out of Stock' : 'Add to Cart'; ?>
                    </button>

                    <button class="quick-view-btn" data-product-id="<?php echo esc_attr( $product_id ); ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>
        <?php } ?>
    </div>
    <?php
}

/**
 * Render Empty Wishlist
 */
function aakaari_render_empty_wishlist() {
    ?>
    <div class="wishlist-empty">
        <div class="empty-icon">❤️</div>
        <h2 class="empty-title">Your wishlist is empty</h2>
        <p class="empty-description">Start adding items you love to your wishlist!</p>
        <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="hero-button primary">
            Browse Products
        </a>
    </div>
    <?php
}

/**
 * Get Wishlist Items
 *
 * @return array Array of product IDs
 */
function aakaari_get_wishlist_items() {
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $wishlist = get_user_meta( $user_id, 'aakaari_wishlist', true );
        return is_array( $wishlist ) ? $wishlist : array();
    } else {
        if ( isset( $_COOKIE['aakaari_wishlist'] ) ) {
            $wishlist = json_decode( stripslashes( $_COOKIE['aakaari_wishlist'] ), true );
            return is_array( $wishlist ) ? $wishlist : array();
        }
    }
    return array();
}

/**
 * Count In Stock Items
 */
function aakaari_count_in_stock_items( $product_ids ) {
    $in_stock_count = 0;

    foreach ( $product_ids as $product_id ) {
        $product = wc_get_product( $product_id );
        if ( $product && $product->is_in_stock() ) {
            $in_stock_count++;
        }
    }

    return $in_stock_count;
}

/**
 * Get Wishlist Item Date
 */
function aakaari_get_wishlist_item_date( $product_id ) {
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $wishlist_dates = get_user_meta( $user_id, 'aakaari_wishlist_dates', true );
        if ( is_array( $wishlist_dates ) && isset( $wishlist_dates[ $product_id ] ) ) {
            return intval( $wishlist_dates[ $product_id ] );
        }
    } else {
        if ( isset( $_COOKIE['aakaari_wishlist_dates'] ) ) {
            $wishlist_dates = json_decode( stripslashes( $_COOKIE['aakaari_wishlist_dates'] ), true );
            if ( is_array( $wishlist_dates ) && isset( $wishlist_dates[ $product_id ] ) ) {
                return intval( $wishlist_dates[ $product_id ] );
            }
        }
    }
    return null;
}

/**
 * AJAX: Add to Wishlist
 */
add_action( 'wp_ajax_aakaari_add_to_wishlist', 'aakaari_ajax_add_to_wishlist' );
add_action( 'wp_ajax_nopriv_aakaari_add_to_wishlist', 'aakaari_ajax_add_to_wishlist' );

function aakaari_ajax_add_to_wishlist() {
    check_ajax_referer( 'aakaari-wishlist-nonce', 'nonce' );

    $product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;

    if ( ! $product_id ) {
        wp_send_json_error( array( 'message' => 'Invalid product ID' ) );
    }

    // Get current wishlist
    $wishlist = aakaari_get_wishlist_items();
    $wishlist_dates = array();

    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $wishlist_dates = get_user_meta( $user_id, 'aakaari_wishlist_dates', true );
        if ( ! is_array( $wishlist_dates ) ) {
            $wishlist_dates = array();
        }
    } else {
        if ( isset( $_COOKIE['aakaari_wishlist_dates'] ) ) {
            $wishlist_dates = json_decode( stripslashes( $_COOKIE['aakaari_wishlist_dates'] ), true );
            if ( ! is_array( $wishlist_dates ) ) {
                $wishlist_dates = array();
            }
        }
    }

    // Add product to wishlist
    if ( ! in_array( $product_id, $wishlist ) ) {
        $wishlist[] = $product_id;
        $wishlist_dates[ $product_id ] = current_time( 'timestamp' );

        // Save wishlist
        if ( is_user_logged_in() ) {
            $user_id = get_current_user_id();
            update_user_meta( $user_id, 'aakaari_wishlist', $wishlist );
            update_user_meta( $user_id, 'aakaari_wishlist_dates', $wishlist_dates );
        } else {
            setcookie( 'aakaari_wishlist', json_encode( $wishlist ), time() + ( 30 * DAY_IN_SECONDS ), '/' );
            setcookie( 'aakaari_wishlist_dates', json_encode( $wishlist_dates ), time() + ( 30 * DAY_IN_SECONDS ), '/' );
        }

        wp_send_json_success( array( 'message' => 'Added to wishlist' ) );
    } else {
        wp_send_json_error( array( 'message' => 'Already in wishlist' ) );
    }
}

/**
 * AJAX: Remove from Wishlist
 */
add_action( 'wp_ajax_aakaari_remove_from_wishlist', 'aakaari_ajax_remove_from_wishlist' );
add_action( 'wp_ajax_nopriv_aakaari_remove_from_wishlist', 'aakaari_ajax_remove_from_wishlist' );

function aakaari_ajax_remove_from_wishlist() {
    check_ajax_referer( 'aakaari-wishlist-nonce', 'nonce' );

    $product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;

    if ( ! $product_id ) {
        wp_send_json_error( array( 'message' => 'Invalid product ID' ) );
    }

    // Get current wishlist
    $wishlist = aakaari_get_wishlist_items();
    $wishlist_dates = array();

    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $wishlist_dates = get_user_meta( $user_id, 'aakaari_wishlist_dates', true );
        if ( ! is_array( $wishlist_dates ) ) {
            $wishlist_dates = array();
        }
    } else {
        if ( isset( $_COOKIE['aakaari_wishlist_dates'] ) ) {
            $wishlist_dates = json_decode( stripslashes( $_COOKIE['aakaari_wishlist_dates'] ), true );
            if ( ! is_array( $wishlist_dates ) ) {
                $wishlist_dates = array();
            }
        }
    }

    // Remove product from wishlist
    $key = array_search( $product_id, $wishlist );
    if ( $key !== false ) {
        unset( $wishlist[ $key ] );
        $wishlist = array_values( $wishlist ); // Re-index array
        unset( $wishlist_dates[ $product_id ] );

        // Save wishlist
        if ( is_user_logged_in() ) {
            $user_id = get_current_user_id();
            update_user_meta( $user_id, 'aakaari_wishlist', $wishlist );
            update_user_meta( $user_id, 'aakaari_wishlist_dates', $wishlist_dates );
        } else {
            setcookie( 'aakaari_wishlist', json_encode( $wishlist ), time() + ( 30 * DAY_IN_SECONDS ), '/' );
            setcookie( 'aakaari_wishlist_dates', json_encode( $wishlist_dates ), time() + ( 30 * DAY_IN_SECONDS ), '/' );
        }

        wp_send_json_success( array( 'message' => 'Removed from wishlist' ) );
    } else {
        wp_send_json_error( array( 'message' => 'Not in wishlist' ) );
    }
}

/**
 * AJAX: Add to Cart from Wishlist
 */
add_action( 'wp_ajax_aakaari_add_to_cart_from_wishlist', 'aakaari_ajax_add_to_cart_from_wishlist' );
add_action( 'wp_ajax_nopriv_aakaari_add_to_cart_from_wishlist', 'aakaari_ajax_add_to_cart_from_wishlist' );

function aakaari_ajax_add_to_cart_from_wishlist() {
    check_ajax_referer( 'aakaari-wishlist-nonce', 'nonce' );

    $product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;
    $remove_from_wishlist = isset( $_POST['remove_from_wishlist'] ) ? boolval( $_POST['remove_from_wishlist'] ) : false;

    if ( ! $product_id ) {
        wp_send_json_error( array( 'message' => 'Invalid product ID' ) );
    }

    // Add to cart
    $added = WC()->cart->add_to_cart( $product_id, 1 );

    if ( $added ) {
        // Optionally remove from wishlist
        if ( $remove_from_wishlist ) {
            $wishlist = aakaari_get_wishlist_items();
            $key = array_search( $product_id, $wishlist );
            if ( $key !== false ) {
                unset( $wishlist[ $key ] );
                $wishlist = array_values( $wishlist );

                if ( is_user_logged_in() ) {
                    $user_id = get_current_user_id();
                    update_user_meta( $user_id, 'aakaari_wishlist', $wishlist );
                } else {
                    setcookie( 'aakaari_wishlist', json_encode( $wishlist ), time() + ( 30 * DAY_IN_SECONDS ), '/' );
                }
            }
        }

        wp_send_json_success( array(
            'message' => 'Added to cart',
            'cart_count' => WC()->cart->get_cart_contents_count()
        ) );
    } else {
        wp_send_json_error( array( 'message' => 'Failed to add to cart' ) );
    }
}

/**
 * AJAX: Clear Wishlist
 */
add_action( 'wp_ajax_aakaari_clear_wishlist', 'aakaari_ajax_clear_wishlist' );
add_action( 'wp_ajax_nopriv_aakaari_clear_wishlist', 'aakaari_ajax_clear_wishlist' );

function aakaari_ajax_clear_wishlist() {
    check_ajax_referer( 'aakaari-wishlist-nonce', 'nonce' );

    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        delete_user_meta( $user_id, 'aakaari_wishlist' );
        delete_user_meta( $user_id, 'aakaari_wishlist_dates' );
    } else {
        setcookie( 'aakaari_wishlist', '', time() - 3600, '/' );
        setcookie( 'aakaari_wishlist_dates', '', time() - 3600, '/' );
    }

    wp_send_json_success( array( 'message' => 'Wishlist cleared' ) );
}

/**
 * AJAX: Add All to Cart
 */
add_action( 'wp_ajax_aakaari_add_all_to_cart', 'aakaari_ajax_add_all_to_cart' );
add_action( 'wp_ajax_nopriv_aakaari_add_all_to_cart', 'aakaari_ajax_add_all_to_cart' );

function aakaari_ajax_add_all_to_cart() {
    check_ajax_referer( 'aakaari-wishlist-nonce', 'nonce' );

    $wishlist = aakaari_get_wishlist_items();
    $added_count = 0;

    foreach ( $wishlist as $product_id ) {
        $product = wc_get_product( $product_id );
        if ( $product && $product->is_in_stock() ) {
            WC()->cart->add_to_cart( $product_id, 1 );
            $added_count++;
        }
    }

    if ( $added_count > 0 ) {
        wp_send_json_success( array(
            'message' => sprintf( '%d items added to cart', $added_count ),
            'cart_count' => WC()->cart->get_cart_contents_count()
        ) );
    } else {
        wp_send_json_error( array( 'message' => 'No items could be added to cart' ) );
    }
}
