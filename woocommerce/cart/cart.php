<?php
/**
 * Cart Page - Figma Design
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<div class="cart-page">
    <div class="cart-container">

        <?php if ( WC()->cart->is_empty() ) : ?>

            <div class="empty-cart">
                <svg class="empty-icon" xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="8" cy="21" r="1"></circle>
                    <circle cx="19" cy="21" r="1"></circle>
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                </svg>
                <h2>Your cart is empty</h2>
                <p>Add some items to get started</p>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="button btn-primary btn-lg">
                    Continue Shopping
                </a>
            </div>

        <?php else : ?>

            <div class="cart-header">
                <h1>Shopping Cart</h1>
                <p><?php echo WC()->cart->get_cart_contents_count(); ?> <?php echo WC()->cart->get_cart_contents_count() === 1 ? 'item' : 'items'; ?></p>
            </div>

            <div class="cart-grid">
                <!-- Cart Items -->
                <div class="cart-items">
                    <?php
                    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                        $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                            $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                            ?>
                            <div class="cart-item">
                                <div class="item-image">
                                    <?php
                                    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                                    if ( ! $product_permalink ) {
                                        echo $thumbnail;
                                    } else {
                                        printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
                                    }
                                    ?>
                                </div>

                                <div class="item-details">
                                    <h3>
                                        <?php
                                        if ( ! $product_permalink ) {
                                            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
                                        } else {
                                            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                        }
                                        ?>
                                    </h3>

                                    <?php
                                    // Meta data
                                    echo wc_get_formatted_cart_item_data( $cart_item );

                                    // Backorder notification
                                    if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
                                    }
                                    ?>

                                    <?php if ( $_product->is_on_sale() ) : ?>
                                        <p class="item-discount">
                                            Save <?php echo wc_price( ( $_product->get_regular_price() - $_product->get_sale_price() ) * $cart_item['quantity'] ); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <div class="item-quantity">
                                    <?php
                                    if ( $_product->is_sold_individually() ) {
                                        $min_quantity = 1;
                                        $max_quantity = 1;
                                    } else {
                                        $min_quantity = 0;
                                        $max_quantity = $_product->get_max_purchase_quantity();
                                    }

                                    $product_quantity = woocommerce_quantity_input(
                                        array(
                                            'input_name'   => "cart[{$cart_item_key}][qty]",
                                            'input_value'  => $cart_item['quantity'],
                                            'max_value'    => $max_quantity,
                                            'min_value'    => $min_quantity,
                                            'product_name' => $_product->get_name(),
                                        ),
                                        $_product,
                                        false
                                    );

                                    echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
                                    ?>
                                </div>

                                <div class="item-price">
                                    <span class="price-label">Price:</span>
                                    <span class="price-value">
                                        <?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
                                    </span>
                                </div>

                                <div class="item-total">
                                    <span class="total-label">Total:</span>
                                    <span class="total-value">
                                        <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
                                    </span>
                                </div>

                                <button class="item-remove" type="button" onclick="removeCartItem('<?php echo esc_js( $cart_item_key ); ?>')">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                </button>
                            </div>
                            <?php
                        }
                    }
                    ?>

                    <div class="cart-actions">
                        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="button btn-outline">
                            Continue Shopping
                        </a>
                        <button type="submit" class="button btn-destructive" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>" onclick="clearCart()">
                            <?php esc_html_e( 'Clear Cart', 'woocommerce' ); ?>
                        </button>
                    </div>

                    <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                </div>

                <!-- Cart Summary -->
                <div class="cart-summary">
                    <h2>Order Summary</h2>

                    <div class="summary-rows">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span><?php wc_cart_totals_subtotal_html(); ?></span>
                        </div>

                        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                            <div class="summary-row">
                                <span><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
                                <span><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
                            </div>
                        <?php endforeach; ?>

                        <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
                            <div class="summary-row">
                                <span>Shipping</span>
                                <span><?php echo WC()->cart->get_cart_shipping_total(); ?></span>
                            </div>

                            <?php if ( WC()->cart->get_cart_subtotal_for_display() >= 100 ) : ?>
                                <p class="free-shipping-note">🎉 You got free shipping!</p>
                            <?php elseif ( WC()->cart->get_cart_subtotal_for_display() < 100 ) : ?>
                                <p class="free-shipping-progress">
                                    Add <?php echo wc_price( 100 - WC()->cart->subtotal ); ?> more for free shipping
                                </p>
                            <?php endif; ?>
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
                    </div>

                    <div style="border-top: 1px solid #e5e7eb; margin: 1rem 0;"></div>

                    <div class="summary-total">
                        <span>Total</span>
                        <span><?php wc_cart_totals_order_total_html(); ?></span>
                    </div>

                    <div class="checkout-btn">
                        <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="button btn-primary btn-lg btn-full">
                            <?php esc_html_e( 'Proceed to Checkout', 'woocommerce' ); ?>
                        </a>
                    </div>

                    <div class="payment-methods">
                        <p>We accept:</p>
                        <div class="payment-icons">
                            <span style="display:inline-block; padding: 0.25rem 0.5rem; background: #666; color: white; font-size: 0.625rem; border-radius: 2px;">VISA</span>
                            <span style="display:inline-block; padding: 0.25rem 0.5rem; background: #666; color: white; font-size: 0.625rem; border-radius: 2px;">MASTERCARD</span>
                            <span style="display:inline-block; padding: 0.25rem 0.5rem; background: #666; color: white; font-size: 0.625rem; border-radius: 2px;">PAYPAL</span>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>

    </div>
</div>

<script>
function removeCartItem(cartItemKey) {
    if (confirm('Are you sure you want to remove this item?')) {
        window.location.href = '<?php echo esc_url( wc_get_cart_remove_url( 'CART_ITEM_KEY' ) ); ?>'.replace('CART_ITEM_KEY', cartItemKey);
    }
}

function clearCart() {
    if (confirm('Are you sure you want to clear your cart?')) {
        window.location.href = '<?php echo esc_url( add_query_arg( 'empty-cart', 'true', wc_get_cart_url() ) ); ?>';
    }
}

// Handle empty cart action
<?php if ( isset( $_GET['empty-cart'] ) && $_GET['empty-cart'] === 'true' ) : ?>
    WC()->cart->empty_cart();
    window.location.href = '<?php echo esc_url( wc_get_cart_url() ); ?>';
<?php endif; ?>
</script>

<?php do_action( 'woocommerce_after_cart' ); ?>
