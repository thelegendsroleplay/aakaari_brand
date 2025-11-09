<?php
/**
 * Aakaari Checkout - Complete Single-Page Design
 * Mobile-first, includes ALL required fields
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

// Force login
if ( ! is_user_logged_in() ) {
    echo '<div class="aakaari-checkout-login-required">';
    echo '<div class="login-required-card">';
    echo '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
    echo '<h2>' . esc_html__( 'Login Required', 'woocommerce' ) . '</h2>';
    echo '<p>' . esc_html__( 'Please sign in to complete your purchase', 'woocommerce' ) . '</p>';
    echo '<div class="login-actions">';
    echo '<a href="' . esc_url( wc_get_page_permalink( 'myaccount' ) ) . '" class="btn btn-primary">' . esc_html__( 'Sign In / Register', 'woocommerce' ) . '</a>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    return;
}

// Check cart
if ( WC()->cart->is_empty() ) {
    echo '<div class="aakaari-checkout-empty">';
    echo '<div class="empty-card">';
    echo '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>';
    echo '<h2>' . esc_html__( 'Your Cart is Empty', 'woocommerce' ) . '</h2>';
    echo '<p>' . esc_html__( 'Add items to your cart before checking out', 'woocommerce' ) . '</p>';
    echo '<a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '" class="btn btn-primary">' . esc_html__( 'Continue Shopping', 'woocommerce' ) . '</a>';
    echo '</div>';
    echo '</div>';
    return;
}

$checkout = WC()->checkout();
?>

<div class="aakaari-checkout">
    <div class="aakaari-checkout-container">

        <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

            <?php if ( $checkout->get_checkout_fields() ) : ?>

                <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                <div class="checkout-layout">

                    <!-- LEFT: ALL Form Fields -->
                    <div class="checkout-form-column">

                        <!-- Billing & Contact -->
                        <div class="checkout-section">
                            <h2 class="section-title">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <?php esc_html_e( 'Billing & Contact Details', 'woocommerce' ); ?>
                            </h2>

                            <?php do_action( 'woocommerce_checkout_billing' ); ?>

                        </div>

                        <!-- Shipping Address -->
                        <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
                        <div class="checkout-section">
                            <h2 class="section-title">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                <?php esc_html_e( 'Shipping Address', 'woocommerce' ); ?>
                            </h2>

                            <div class="ship-to-different-address">
                                <label class="checkbox-label">
                                    <input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" />
                                    <span><?php esc_html_e( 'Ship to a different address?', 'woocommerce' ); ?></span>
                                </label>
                            </div>

                            <div class="shipping-address-fields">
                                <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                            </div>

                        </div>
                        <?php endif; ?>

                        <!-- Order Notes -->
                        <?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>
                        <div class="checkout-section">
                            <h2 class="section-title">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                                <?php esc_html_e( 'Order Notes (Optional)', 'woocommerce' ); ?>
                            </h2>

                            <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

                        </div>
                        <?php endif; ?>

                    </div>

                    <!-- RIGHT: Order Summary -->
                    <div class="checkout-summary-column">
                        <div class="order-summary-sticky">
                            <div class="order-summary">
                                <h3 class="summary-title"><?php esc_html_e( 'Order Summary', 'woocommerce' ); ?></h3>

                                <!-- Cart Items -->
                                <div class="summary-items">
                                    <?php
                                    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                                        $_product = $cart_item['data'];
                                        if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 ) {
                                            continue;
                                        }
                                        ?>
                                        <div class="summary-item">
                                            <div class="item-image">
                                                <?php echo $_product->get_image( array( 60, 60 ) ); ?>
                                                <span class="item-qty"><?php echo esc_html( $cart_item['quantity'] ); ?></span>
                                            </div>
                                            <div class="item-details">
                                                <h4 class="item-name"><?php echo esc_html( $_product->get_name() ); ?></h4>
                                                <?php
                                                $item_data = wc_get_formatted_cart_item_data( $cart_item );
                                                if ( $item_data ) {
                                                    echo '<div class="item-meta">' . $item_data . '</div>';
                                                }
                                                ?>
                                            </div>
                                            <div class="item-price">
                                                <?php echo WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ); ?>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>

                                <!-- Coupon -->
                                <?php if ( wc_coupons_enabled() ) : ?>
                                <div class="coupon-section">
                                    <details class="coupon-toggle">
                                        <summary><?php esc_html_e( 'Have a coupon code?', 'woocommerce' ); ?></summary>
                                        <div class="coupon-form">
                                            <input type="text" name="coupon_code" class="input-field" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" />
                                            <button type="submit" class="btn btn-outline btn-sm" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply', 'woocommerce' ); ?></button>
                                        </div>
                                    </details>
                                </div>
                                <?php endif; ?>

                                <!-- Totals -->
                                <div class="summary-totals">
                                    <div class="total-row">
                                        <span><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></span>
                                        <span><?php echo WC()->cart->get_cart_subtotal(); ?></span>
                                    </div>

                                    <?php if ( WC()->cart->get_cart_discount_total() > 0 ) : ?>
                                    <div class="total-row discount">
                                        <span><?php esc_html_e( 'Discount', 'woocommerce' ); ?></span>
                                        <span>-<?php echo wc_price( WC()->cart->get_cart_discount_total() ); ?></span>
                                    </div>
                                    <?php endif; ?>

                                    <?php if ( WC()->cart->needs_shipping() ) : ?>
                                    <div class="total-row">
                                        <span><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></span>
                                        <span><?php echo WC()->cart->get_cart_shipping_total(); ?></span>
                                    </div>
                                    <?php endif; ?>

                                    <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
                                    <div class="total-row">
                                        <span><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
                                        <span><?php echo WC()->cart->get_taxes_total(); ?></span>
                                    </div>
                                    <?php endif; ?>

                                    <div class="total-row total-final">
                                        <span><?php esc_html_e( 'Total', 'woocommerce' ); ?></span>
                                        <span class="total-amount"><?php echo WC()->cart->get_total(); ?></span>
                                    </div>
                                </div>

                                <!-- Payment Methods -->
                                <section class="checkout-payment-section" aria-labelledby="payment-heading">
  <h3 id="payment-heading" class="payment-title">Payment</h3>

  <ul class="wc_payment_methods" role="radiogroup" aria-label="Payment methods">
    <li class="wc_payment_method" data-method="cod">
      <input id="pm-cod" class="pm-radio" name="payment_method" type="radio" value="cod" />
      <label for="pm-cod" class="pm-label">
        <div class="pm-left">
          <strong class="pm-name">Cash on delivery</strong>
          <span class="pm-sub">Pay with cash upon delivery</span>
        </div>
        <div class="pm-right" aria-hidden="true">
          <svg class="pm-check" width="20" height="20" viewBox="0 0 24 24" fill="none">
            <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
      </label>
      <div class="payment_box" id="box-cod" aria-hidden="true">
        <p>Deliveries currently accepted in your area. No extra fee.</p>
      </div>
    </li>

    <li class="wc_payment_method" data-method="upi">
      <input id="pm-upi" class="pm-radio" name="payment_method" type="radio" value="upi" />
      <label for="pm-upi" class="pm-label">
        <div class="pm-left">
          <strong class="pm-name">UPI / Wallet</strong>
          <span class="pm-sub">Fast & secure payments</span>
        </div>
        <div class="pm-right" aria-hidden="true">
          <svg class="pm-check" width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
      </label>
      <div class="payment_box" id="box-upi" aria-hidden="true">
        <p>Select your UPI app after placing order. Instant confirmation.</p>
      </div>
    </li>
  </ul>

  <div class="woocommerce-terms-and-conditions-wrapper">
    <label class="checkbox-label">
      <input type="checkbox" id="terms" />
      I have read and agree to the website terms and conditions
    </label>
  </div>

  <div class="place-order">
    <button id="place_order" type="submit" class="btn-primary" disabled>Place order</button>
  </div>
</section>


                            </div>
                        </div>
                    </div>

                </div>

            <?php endif; ?>

        </form>

    </div>
</div>
