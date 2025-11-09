<?php
/**
 * Patched Cart Template - Aakaari
 *
 * Place at: wp-content/themes/your-theme/woocommerce/cart/cart.php
 *
 * Purpose:
 *  - Defensive checks for WooCommerce and WC()->cart
 *  - Safe iteration of cart items
 *  - Fall back to empty state gracefully
 *  - Preserve existing hooks for compatibility
 *
 * @package Aakaari_Brand
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------
 * Defensive checks
 * ------------------------- */
if ( ! class_exists( 'WooCommerce' ) ) : ?>
    <div class="cart-page cart-page-error">
        <div class="cart-container">
            <div class="empty-cart">
                <div class="empty-icon">⚠️</div>
                <h1>WooCommerce not active</h1>
                <p>WooCommerce is required to display the cart. Please activate the plugin.</p>
            </div>
        </div>
    </div>
<?php
    return;
endif;

// Sometimes WC()->cart may not be initialized early (object cache / bootstrap edge cases)
if ( ! WC()->cart ) {
    // Log for debugging (only if WP_DEBUG is enabled)
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
        error_log( 'DEBUG: WC()->cart is not available in cart template.' );
    }
    ?>
    <div class="cart-page cart-page-error">
        <div class="cart-container">
            <div class="empty-cart">
                <div class="empty-icon">⚠️</div>
                <h1>Cart temporarily unavailable</h1>
                <p>We couldn't load your cart right now. Please refresh the page or try again later.</p>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-full">Continue Shopping</a>
            </div>
        </div>
    </div>
    <?php
    return;
}

/* -------------------------
 * Begin template output
 * ------------------------- */
do_action( 'woocommerce_before_cart' );

// WC()->cart should be present now
$cart_count = WC()->cart->get_cart_contents_count();

// If cart is empty — show empty UI
if ( WC()->cart->is_empty() ) :
    ?>
    <div class="cart-page cart-page-empty">
        <div class="cart-container">
            <div class="empty-cart">
                <div class="empty-icon">🛍️</div>
                <h1><?php esc_html_e( 'Your cart is empty', 'aakaari' ); ?></h1>
                <p><?php esc_html_e( "Looks like you haven't added anything yet. Start exploring our latest collection!", 'aakaari' ); ?></p>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-full">
                    <?php esc_html_e( 'Continue Shopping', 'aakaari' ); ?>
                </a>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="muted-link"><?php esc_html_e( 'Back to Home', 'aakaari' ); ?></a>
            </div>
        </div>
    </div>
    <?php
    do_action( 'woocommerce_after_cart' );
    return;
endif;
?>

<div class="cart-page">
    <div class="cart-container">

        <?php wc_print_notices(); ?>

        <div class="cart-header">
            <h1><?php esc_html_e( 'Shopping Cart', 'aakaari' ); ?></h1>
            <p id="items-count"><?php echo esc_html( $cart_count . ' ' . ( $cart_count === 1 ? __( 'item', 'aakaari' ) : __( 'items', 'aakaari' ) ) ); ?></p>
        </div>

        <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post" enctype="multipart/form-data">
            <?php do_action( 'woocommerce_before_cart_table' ); ?>

            <div class="cart-grid">
                <!-- Left: Cart Items -->
                <div class="cart-items" aria-live="polite">
                    <?php
                    // Iterate cart safely
                    $cart_items = WC()->cart->get_cart();
                    if ( empty( $cart_items ) || ! is_array( $cart_items ) ) {
                        echo '<p>' . esc_html__( 'No items found in cart.', 'aakaari' ) . '</p>';
                    } else {
                        foreach ( $cart_items as $cart_item_key => $cart_item ) {
                            // Guard: ensure array keys exist
                            if ( ! is_array( $cart_item ) || ! isset( $cart_item['data'] ) ) {
                                continue;
                            }

                            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                            if ( ! $_product || ! $_product->exists() ) {
                                continue;
                            }

                            $quantity = isset( $cart_item['quantity'] ) ? intval( $cart_item['quantity'] ) : 0;
                            if ( $quantity <= 0 ) {
                                continue;
                            }

                            if ( ! apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                                continue;
                            }

                            $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );

                            // price & total safely
                            $price = $_product->get_price();
                            $item_subtotal = WC()->cart->get_product_subtotal( $_product, $quantity );
                            $max_quantity = $_product->get_max_purchase_quantity();
                            if ( $max_quantity < 0 ) {
                                $max_quantity = 999;
                            }
                            ?>
                            <div class="cart-item" data-key="<?php echo esc_attr( $cart_item_key ); ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>">
                                <div class="item-image">
                                    <?php
                                    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'woocommerce_thumbnail' ), $cart_item, $cart_item_key );
                                    if ( empty( $product_permalink ) ) {
                                        echo $thumbnail;
                                    } else {
                                        printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
                                    }
                                    ?>
                                </div>

                                <div class="item-details">
                                    <h3 class="item-title">
                                        <?php
                                        if ( empty( $product_permalink ) ) {
                                            echo wp_kses_post( $_product->get_name() );
                                        } else {
                                            echo wp_kses_post( sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ) );
                                        }
                                        ?>
                                    </h3>

                                    <?php
                                    $item_data = wc_get_formatted_cart_item_data( $cart_item );
                                    if ( $item_data ) {
                                        echo '<div class="item-meta">' . wp_kses_post( $item_data ) . '</div>';
                                    }

                                    // Discount / sale notice
                                    if ( $_product->is_on_sale() ) {
                                        $regular = (float) $_product->get_regular_price();
                                        $sale    = (float) $_product->get_sale_price();
                                        if ( $regular > $sale ) {
                                            $discount_total = ( $regular - $sale ) * $quantity;
                                            echo '<p class="item-discount">' . sprintf( __( 'Save %s', 'aakaari' ), wc_price( $discount_total ) ) . '</p>';
                                        }
                                    }
                                    ?>
                                </div>

                                <div class="item-quantity" aria-label="<?php esc_attr_e( 'Quantity', 'aakaari' ); ?>">
                                    <button type="button" class="qty-btn decrease" data-key="<?php echo esc_attr( $cart_item_key ); ?>">−</button>

                                    <input
                                        type="number"
                                        name="cart[<?php echo esc_attr( $cart_item_key ); ?>][qty]"
                                        value="<?php echo esc_attr( $quantity ); ?>"
                                        min="1"
                                        max="<?php echo esc_attr( $max_quantity ); ?>"
                                        class="qty-value"
                                        aria-live="polite"
                                    />

                                    <button type="button" class="qty-btn increase" data-key="<?php echo esc_attr( $cart_item_key ); ?>" data-max="<?php echo esc_attr( $max_quantity ); ?>">+</button>
                                </div>

                                <div class="item-price">
                                    <span class="price-label"><?php esc_html_e( 'Price:', 'aakaari' ); ?></span>
                                    <span class="price-value">
                                        <?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ) ); ?>
                                    </span>
                                </div>

                                <div class="item-total">
                                    <span class="total-label"><?php esc_html_e( 'Total:', 'aakaari' ); ?></span>
                                    <span class="total-value">
                                        <?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_subtotal', $item_subtotal, $cart_item, $cart_item_key ) ); ?>
                                    </span>
                                </div>

                                <button type="button"
                                        class="item-remove"
                                        data-key="<?php echo esc_attr( $cart_item_key ); ?>"
                                        data-product-id="<?php echo esc_attr( $product_id ); ?>"
                                        aria-label="<?php echo esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $_product->get_name() ) ) ); ?>">
                                    🗑️
                                </button>
                            </div>
                            <?php
                        } // end foreach
                    } // end else
                    ?>

                    <?php do_action( 'woocommerce_cart_contents' ); ?>

                    <div class="cart-actions">
                        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn outline">
                            <?php esc_html_e( 'Continue Shopping', 'aakaari' ); ?>
                        </a>

                        <button type="button" id="clear-cart-btn" class="btn destructive" data-nonce="<?php echo esc_attr( wp_create_nonce( 'aakaari-ajax-nonce' ) ); ?>">
                            <?php esc_html_e( 'Clear Cart', 'aakaari' ); ?>
                        </button>

                        <button type="submit" class="btn btn-primary" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>">
                            <?php esc_html_e( 'Update cart', 'woocommerce' ); ?>
                        </button>
                    </div>

                    <?php do_action( 'woocommerce_cart_actions' ); ?>
                    <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                </div> <!-- .cart-items -->

                <!-- Right: Cart Summary -->
                <aside class="cart-summary" id="cart-summary" aria-labelledby="items-count">
                    <h2><?php esc_html_e( 'Order Summary', 'aakaari' ); ?></h2>

                    <?php do_action( 'woocommerce_before_cart_totals' ); ?>

                    <div class="summary-rows" id="summary-rows">
                        <div class="summary-row">
                            <span><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></span>
                            <span><?php wc_cart_totals_subtotal_html(); ?></span>
                        </div>

                        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                            <div class="summary-row discount-row">
                                <span><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
                                <span><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
                            </div>
                        <?php endforeach; ?>

                        <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
                            <?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>
                            <?php wc_cart_totals_shipping_html(); ?>
                            <?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>
                        <?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>
                            <div class="summary-row">
                                <span><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></span>
                                <span><?php woocommerce_shipping_calculator(); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php
                        // Free shipping progress example (customize threshold)
                        $subtotal = (float) WC()->cart->get_subtotal();
                        $free_shipping_threshold = apply_filters( 'aakaari_free_shipping_threshold', 100 );
                        if ( $subtotal >= $free_shipping_threshold ) : ?>
                            <p class="free-shipping-note"><?php esc_html_e( '🎉 You got free shipping!', 'aakaari' ); ?></p>
                        <?php else : ?>
                            <p class="free-shipping-progress">
                                <?php
                                printf(
                                    /* translators: %s = amount remaining */
                                    esc_html__( 'Add %s more for free shipping', 'aakaari' ),
                                    wp_kses_post( wc_price( $free_shipping_threshold - $subtotal ) )
                                );
                                ?>
                            </p>
                        <?php endif; ?>

                        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
                            <div class="summary-row">
                                <span><?php echo esc_html( $fee->name ); ?></span>
                                <span><?php wc_cart_totals_fee_html( $fee ); ?></span>
                            </div>
                        <?php endforeach; ?>

                        <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
                            <?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
                                <?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
                                    <div class="summary-row">
                                        <span><?php echo esc_html( $tax->label ); ?></span>
                                        <span><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <div class="summary-row">
                                    <span><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
                                    <span><?php wc_cart_totals_taxes_total_html(); ?></span>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>
                    </div> <!-- .summary-rows -->

                    <hr style="border:none; border-top:1px solid #e5e7eb; margin:1rem 0;" />

                    <div class="summary-total">
                        <span><?php esc_html_e( 'Total', 'woocommerce' ); ?></span>
                        <span><?php wc_cart_totals_order_total_html(); ?></span>
                    </div>

                    <?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

                    <div class="wc-proceed-to-checkout">
                        <?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
                    </div>

                    <?php do_action( 'woocommerce_after_cart_totals' ); ?>
                </aside>
            </div> <!-- .cart-grid -->

            <?php do_action( 'woocommerce_after_cart_table' ); ?>
        </form>
    </div>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
