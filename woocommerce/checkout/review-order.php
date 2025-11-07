<?php
/**
 * Review order table - Figma Design
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="summary-items">
    <?php
    do_action( 'woocommerce_review_order_before_cart_contents' );

    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
            ?>
            <div class="summary-item">
                <div class="item-image-wrapper">
                    <?php echo apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( array( 60, 75 ) ), $cart_item, $cart_item_key ); ?>
                </div>
                <div class="item-info">
                    <p class="item-name"><?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ); ?></p>
                    <p class="item-details">
                        <?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
                        Qty: <?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '%s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); ?>
                    </p>
                </div>
                <span class="item-price">
                    <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
                </span>
            </div>
            <?php
        }
    }

    do_action( 'woocommerce_review_order_after_cart_contents' );
    ?>
</div>

<div style="border-top: 1px solid #e5e7eb; margin: 1rem 0;"></div>

<div class="summary-totals">
    <div class="total-row">
        <span><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></span>
        <span><?php wc_cart_totals_subtotal_html(); ?></span>
    </div>

    <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
        <div class="total-row discount">
            <span><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
            <span><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
        </div>
    <?php endforeach; ?>

    <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

        <?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

        <div class="total-row">
            <span><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></span>
            <span><?php wc_cart_totals_shipping_html(); ?></span>
        </div>

        <?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

    <?php endif; ?>

    <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
        <div class="total-row">
            <span><?php echo esc_html( $fee->name ); ?></span>
            <span><?php wc_cart_totals_fee_html( $fee ); ?></span>
        </div>
    <?php endforeach; ?>

    <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
        <?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
            <?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
                <div class="total-row">
                    <span><?php echo esc_html( $tax->label ); ?></span>
                    <span><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="total-row">
                <span><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
                <span><?php wc_cart_totals_taxes_total_html(); ?></span>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

    <div style="border-top: 1px solid #e5e7eb; margin: 1rem 0;"></div>

    <div class="total-row grand-total">
        <span><?php esc_html_e( 'Total', 'woocommerce' ); ?></span>
        <span><?php wc_cart_totals_order_total_html(); ?></span>
    </div>

    <?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
</div>

<div style="margin-top: 2rem;">
    <div id="payment" class="woocommerce-checkout-payment">
        <?php if ( WC()->cart->needs_payment() ) : ?>
            <h3 style="font-size: 1.125rem; font-weight: 600; margin: 0 0 1rem;">Payment Method</h3>
            <ul class="wc_payment_methods payment_methods methods">
                <?php
                if ( ! empty( $available_gateways ) ) {
                    foreach ( $available_gateways as $gateway ) {
                        wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
                    }
                } else {
                    echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</li>'; // @codingStandardsIgnoreLine
                }
                ?>
            </ul>
        <?php endif; ?>
        <div class="form-row place-order">
            <noscript>
                <?php esc_html_e( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the <em>Update Totals</em> button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ); ?>
                <br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
            </noscript>

            <?php wc_get_template( 'checkout/terms.php' ); ?>

            <?php do_action( 'woocommerce_review_order_before_submit' ); ?>

            <?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt btn-primary btn-full" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine ?>

            <?php do_action( 'woocommerce_review_order_after_submit' ); ?>

            <?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
        </div>
    </div>
</div>
