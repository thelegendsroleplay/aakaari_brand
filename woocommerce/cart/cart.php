<?php
/**
 * Cart Page - Modern Design
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<?php if ( WC()->cart->is_empty() ) : ?>

<div class="cart-page cart-page-empty">
    <div class="cart-container">
        <div class="empty-cart">
            <div class="empty-icon">🛍️</div>
            <h1>Your cart is empty</h1>
            <p>Looks like you haven't added anything yet.<br />Start exploring our latest collection!</p>
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-full">
                Continue Shopping
            </a>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="muted-link">Back to Home</a>
        </div>
    </div>
</div>

<?php else : ?>

<div class="cart-page">
    <div class="cart-container">
        <div class="cart-header">
            <h1>Shopping Cart</h1>
            <p id="items-count"><?php echo WC()->cart->get_cart_contents_count(); ?> <?php echo WC()->cart->get_cart_contents_count() === 1 ? 'item' : 'items'; ?></p>
        </div>

        <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
            <?php do_action( 'woocommerce_before_cart_table' ); ?>

            <div class="cart-grid">
                    <!-- Left: Cart Items -->
                    <div class="cart-items">
                        <?php
                        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                                $price = $_product->get_price();
                                $item_total = $price * $cart_item['quantity'];
                                $discount = 0;

                                if ( $_product->is_on_sale() ) {
                                    $discount = ( $_product->get_regular_price() - $_product->get_sale_price() ) * $cart_item['quantity'];
                                }

                                $max_quantity = $_product->get_max_purchase_quantity();
                                if ( $max_quantity < 0 ) {
                                    $max_quantity = 999;
                                }
                                ?>
                                <div class="cart-item" data-key="<?php echo esc_attr( $cart_item_key ); ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>">
                                    <div class="item-image">
                                        <?php
                                        $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'woocommerce_thumbnail' ), $cart_item, $cart_item_key );
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
                                                echo wp_kses_post( $_product->get_name() );
                                            } else {
                                                echo wp_kses_post( sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ) );
                                            }
                                            ?>
                                        </h3>

                                        <?php
                                        $item_data = wc_get_formatted_cart_item_data( $cart_item );
                                        if ( $item_data ) {
                                            echo '<p class="item-meta">' . $item_data . '</p>';
                                        }
                                        ?>

                                        <?php if ( $discount > 0 ) : ?>
                                            <p class="item-discount">
                                                Save <?php echo wc_price( $discount ); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="item-quantity">
                                        <button type="button" class="qty-btn decrease" data-key="<?php echo esc_attr( $cart_item_key ); ?>">−</button>
                                        <input type="number"
                                               name="cart[<?php echo $cart_item_key; ?>][qty]"
                                               value="<?php echo esc_attr( $cart_item['quantity'] ); ?>"
                                               min="1"
                                               max="<?php echo esc_attr( $max_quantity ); ?>"
                                               class="qty-value"
                                               readonly />
                                        <button type="button" class="qty-btn increase" data-key="<?php echo esc_attr( $cart_item_key ); ?>" data-max="<?php echo esc_attr( $max_quantity ); ?>">+</button>
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

                                    <button type="button"
                                            class="item-remove"
                                            data-key="<?php echo esc_attr( $cart_item_key ); ?>"
                                            data-product-id="<?php echo esc_attr( $product_id ); ?>"
                                            aria-label="<?php echo esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $_product->get_name() ) ) ); ?>">
                                        🗑️
                                    </button>
                                </div>
                                <?php
                            }
                        }
                        ?>

                        <?php do_action( 'woocommerce_cart_contents' ); ?>

                        <div class="cart-actions">
                            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn outline">
                                Continue Shopping
                            </a>
                            <button type="button" id="clear-cart-btn" class="btn destructive">
                                Clear Cart
                            </button>
                        </div>

                        <?php do_action( 'woocommerce_cart_actions' ); ?>
                        <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                    </div>

                    <!-- Right: Cart Summary -->
                    <aside class="cart-summary" id="cart-summary">
                        <h2>Order Summary</h2>

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
                            // Free shipping progress indicator
                            $subtotal = WC()->cart->get_subtotal();
                            $free_shipping_threshold = 100; // $100 for free shipping
                            ?>

                            <?php if ( $subtotal >= $free_shipping_threshold ) : ?>
                                <p class="free-shipping-note">🎉 You got free shipping!</p>
                            <?php else : ?>
                                <p class="free-shipping-progress">
                                    Add <?php echo wc_price( $free_shipping_threshold - $subtotal ); ?> more for free shipping
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
                        </div>

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
                </div>

            <?php do_action( 'woocommerce_after_cart_table' ); ?>
        </form>
    </div>
</div>

<?php endif; ?>

<?php do_action( 'woocommerce_after_cart' ); ?>
