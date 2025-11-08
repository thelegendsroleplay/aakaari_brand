<?php
/**
 * Aakaari custom checkout template for parent theme
 * Place this file in: your-theme/woocommerce/checkout/form-checkout.php
 */

defined( 'ABSPATH' ) || exit;

$checkout = WC()->checkout();
$cart = WC()->cart;

if ( WC()->cart->is_empty() ) {
    wc_print_notice( 'Your cart is currently empty.', 'notice' );
}
?>

<div class="checkout-page">
  <div class="checkout-container">
    <div class="checkout-steps" id="steps">
      <div class="step" data-step="1"><div class="step-number">1</div><span>Shipping</span></div>
      <div class="step-line"></div>
      <div class="step" data-step="2"><div class="step-number">2</div><span>Billing</span></div>
      <div class="step-line"></div>
      <div class="step" data-step="3"><div class="step-number">3</div><span>Payment</span></div>
      <div class="step-line"></div>
      <div class="step" data-step="4"><div class="step-number">4</div><span>Confirm</span></div>
    </div>

    <div class="checkout-grid">
      <div class="checkout-form">
        <?php if ( ! is_user_logged_in() ) : ?>
          <div class="auth-required">
            <h2>Please log in to continue</h2>
            <p>You need to be logged in to complete your purchase</p>
            <div style="margin-top:1rem">
              <a class="btn" href="<?php echo esc_url( wp_login_url( wc_get_checkout_url() ) ); ?>">Log in</a>
            </div>
          </div>
        <?php else : ?>

          <?php if ( $cart->is_empty() ) : ?>
            <div class="empty-checkout">
              <h2>Your cart is empty</h2>
              <div style="margin-top:1rem"><a class="btn" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">Continue Shopping</a></div>
            </div>
          <?php else: ?>

            <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
              <?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>

              <div class="step-content" data-step="1">
                <div class="form-section">
                  <h2>Shipping Address</h2>
                  <div class="form-grid">
                    <?php
                    $shipping_fields = $checkout->get_checkout_fields( 'shipping' );
                    foreach ( $shipping_fields as $key => $field ) {
                        echo '<div class="form-group">';
                        woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
                        echo '</div>';
                    }
                    ?>
                  </div>

                  <div style="margin-top:0.75rem">
                    <button class="btn full" id="continueToBilling"><?php esc_html_e('Continue to Billing','woocommerce'); ?></button>
                  </div>
                </div>
              </div>

              <div class="step-content" data-step="2" style="display:none">
                <div class="form-section">
                  <h2>Billing Address</h2>

                  <div class="same-address-check">
                    <input id="ship_to_same" type="checkbox" checked />
                    <label for="ship_to_same">Same as shipping address</label>
                  </div>

                  <div id="billingFields">
                    <?php
                    $billing_fields = $checkout->get_checkout_fields( 'billing' );
                    foreach ( $billing_fields as $key => $field ) {
                      echo '<div class="form-group">';
                      woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
                      echo '</div>';
                    }
                    ?>
                  </div>

                  <div class="form-actions" style="margin-top:1rem">
                    <button class="btn" aria-variant="outline" id="backToShipping">Back</button>
                    <button class="btn" id="continueToPayment">Continue to Payment</button>
                  </div>
                </div>
              </div>

              <div class="step-content" data-step="3" style="display:none">
                <div class="form-section">
                  <h2>Payment Method</h2>

                  <?php
                  if ( WC()->cart->needs_payment() ) {
                      wc_get_template( 'checkout/payment.php' );
                  } else {
                      echo '<p>' . esc_html__( 'No payment required', 'woocommerce' ) . '</p>';
                  }
                  ?>

                  <div class="form-actions" style="margin-top:1rem">
                    <button class="btn" aria-variant="outline" id="backToBilling">Back</button>
                    <button class="btn" id="placeOrderBtn"><?php esc_html_e('Place Order','woocommerce'); ?></button>
                  </div>
                </div>
              </div>

              <div class="step-content" data-step="4" style="display:none">
                <div class="order-success" style="display:none">
                  <svg class="success-icon" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5"/></svg>
                  <h2>Order Placed Successfully!</h2>
                  <p style="color:#6b7280">Thank you for your purchase. Your order has been confirmed.</p>
                </div>
              </div>

              <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

            </form>

          <?php endif; // cart empty ?>

        <?php endif; // logged in ?>

      </div> <!-- .checkout-form -->

      <aside class="order-summary" id="orderSummary">
        <h3>Order Summary</h3>

        <div class="summary-items">
          <?php
          foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
              $_product  = $cart_item['data'];
              $name      = $_product->get_name();
              $qty       = $cart_item['quantity'];
              $size = isset($cart_item['variation']['attribute_pa_size']) ? $cart_item['variation']['attribute_pa_size'] : '';
              $color = isset($cart_item['variation']['attribute_pa_color']) ? $cart_item['variation']['attribute_pa_color'] : '';
              $img = wp_get_attachment_image_src( get_post_thumbnail_id( $_product->get_id() ), 'thumbnail' );
              $img_src = $img ? $img[0] : wc_placeholder_img_src();
              $line_total = wc_price( $cart_item['line_total'] );
              ?>
              <div class="summary-item">
                <img src="<?php echo esc_url( $img_src ); ?>" alt="<?php echo esc_attr( $name ); ?>">
                <div class="item-info">
                  <p class="item-name"><?php echo esc_html( $name ); ?></p>
                  <p class="item-details"><?php echo esc_html( trim( $size . ' • ' . $color ) ); ?> • Qty: <?php echo esc_html( $qty ); ?></p>
                </div>
                <div class="item-price"><?php echo wp_kses_post( $line_total ); ?></div>
              </div>
          <?php } ?>
        </div>

        <div style="height:1px;background:#e5e7eb;margin:0.75rem 0"></div>

        <div class="coupon-section">
          <input id="couponInput" placeholder="Coupon code" />
          <button class="btn" id="applyCouponBtn" aria-variant="outline">Apply</button>
        </div>

        <div class="summary-totals">
          <div class="total-row"><span>Subtotal</span><span><?php echo wc_price( WC()->cart->get_subtotal() ); ?></span></div>
          <div class="total-row"><span>Shipping</span><span><?php echo (WC()->cart->shipping_total > 0) ? wc_price( WC()->cart->shipping_total ) : 'FREE'; ?></span></div>
          <div class="total-row"><span>Tax</span><span><?php echo wc_price( WC()->cart->get_taxes_total() ); ?></span></div>
          <?php if ( WC()->cart->get_cart_discount_total() > 0 ) : ?>
            <div class="total-row discount"><span>Discount</span><span>-<?php echo wc_price( WC()->cart->get_cart_discount_total() ); ?></span></div>
          <?php endif; ?>
          <div style="height:8px"></div>
          <div class="total-row grand-total"><span>Total</span><span><?php echo WC()->cart->get_total(); ?></span></div>
        </div>

      </aside>

    </div> <!-- .checkout-grid -->
  </div>
</div>
