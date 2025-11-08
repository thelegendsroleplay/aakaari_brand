<?php
/**
 * Aakaari custom checkout template that keeps WooCommerce backend logic intact
 */

defined( 'ABSPATH' ) || exit;

$checkout = WC()->checkout();
$cart     = WC()->cart;

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

$shipping_fields = $checkout->get_checkout_fields( 'shipping' );
$billing_fields  = $checkout->get_checkout_fields( 'billing' );
$has_shipping    = ! empty( $shipping_fields );
?>

<div class="checkout-page">
	<div class="checkout-container">
		<div class="checkout-steps" id="steps">
			<div class="step" data-step="1"><div class="step-number">1</div><span><?php esc_html_e( 'Shipping', 'aakaari' ); ?></span></div>
			<div class="step-line"></div>
			<div class="step" data-step="2"><div class="step-number">2</div><span><?php esc_html_e( 'Billing', 'aakaari' ); ?></span></div>
			<div class="step-line"></div>
			<div class="step" data-step="3"><div class="step-number">3</div><span><?php esc_html_e( 'Review & Pay', 'aakaari' ); ?></span></div>
		</div>

		<?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>

		<?php if ( $cart && $cart->is_empty() ) : ?>
			<div class="empty-checkout">
				<h2><?php esc_html_e( 'Your cart is empty', 'aakaari' ); ?></h2>
				<p><?php esc_html_e( 'Add items to continue with checkout.', 'aakaari' ); ?></p>
				<a class="btn-primary btn-full" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Continue Shopping', 'aakaari' ); ?></a>
			</div>
		<?php else : ?>

		<div class="checkout-grid">
			<form name="checkout" method="post" class="checkout woocommerce-checkout checkout-form" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" novalidate>

				<?php if ( $checkout->get_checkout_fields() ) : ?>

					<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

					<div class="step-content" data-step="1">
						<div class="form-section">
							<h2><?php esc_html_e( 'Shipping Address', 'aakaari' ); ?></h2>

							<?php if ( $has_shipping ) : ?>
								<div class="form-grid">
									<?php
									foreach ( $shipping_fields as $key => $field ) :
										echo '<div class="form-group">';
										woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
										echo '</div>';
									endforeach;
									?>
								</div>
							<?php else : ?>
								<p class="no-shipping-required"><?php esc_html_e( 'This order does not require shipping information.', 'aakaari' ); ?></p>
							<?php endif; ?>

							<div class="form-actions">
								<button class="btn-primary btn-full" id="continueToBilling"><?php esc_html_e( 'Continue to Billing', 'aakaari' ); ?></button>
							</div>
						</div>
					</div>

					<div class="step-content" data-step="2" style="display:none">
						<div class="form-section">
							<h2><?php esc_html_e( 'Billing Details', 'aakaari' ); ?></h2>

							<div class="same-address-check">
								<input id="ship_to_same" type="checkbox" checked>
								<label for="ship_to_same"><?php esc_html_e( 'Use shipping address as billing address', 'aakaari' ); ?></label>
							</div>
							<input type="hidden" name="ship_to_different_address" id="ship_to_different_address_hidden" value="0">

							<div id="billingFields">
								<div class="form-grid">
									<?php
									foreach ( $billing_fields as $key => $field ) :
										echo '<div class="form-group">';
										woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
										echo '</div>';
									endforeach;
									?>
								</div>
							</div>

							<div id="additionalFields">
								<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
							</div>

							<div class="form-actions">
								<button class="btn" aria-variant="outline" id="backToShipping"><?php esc_html_e( 'Back', 'aakaari' ); ?></button>
								<button class="btn-primary" id="continueToPayment"><?php esc_html_e( 'Review & Pay', 'aakaari' ); ?></button>
							</div>
						</div>
					</div>

					<div class="step-content" data-step="3" style="display:none">
						<div class="form-section">
							<h2><?php esc_html_e( 'Review & Payment', 'aakaari' ); ?></h2>

							<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

							<div id="order_review" class="woocommerce-checkout-review-order aakaari-order-review">
								<?php do_action( 'woocommerce_checkout_order_review' ); ?>
							</div>

							<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

							<div class="form-actions">
								<button class="btn" aria-variant="outline" id="backToBillingSummary"><?php esc_html_e( 'Back', 'aakaari' ); ?></button>
							</div>
						</div>
					</div>

				<?php endif; ?>

			</form>

			<aside class="order-summary" id="orderSummary" aria-live="polite">
				<h3><?php esc_html_e( 'Order Summary', 'aakaari' ); ?></h3>

				<div class="coupon-section">
					<input id="couponInput" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>">
					<button class="btn" id="applyCouponBtn" aria-variant="outline"><?php esc_html_e( 'Apply', 'woocommerce' ); ?></button>
				</div>

				<div class="summary-live" id="orderSummaryBody">
					<!-- Populated from order review via JS to stay in sync -->
				</div>
			</aside>
		</div><!-- .checkout-grid -->

		<?php endif; ?>

		<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
	</div>
</div>

<script>
// Simple coupon application
document.addEventListener('DOMContentLoaded', function() {
    const applyBtn = document.getElementById('apply_coupon');
    if ( applyBtn ) {
        applyBtn.addEventListener('click', function() {
            const code = document.getElementById('coupon_code').value.trim();
            if ( ! code ) {
                alert( '<?php esc_html_e( 'Please enter a coupon code', 'woocommerce' ); ?>' );
                return;
            }

            this.disabled = true;
            this.textContent = '<?php esc_html_e( 'Applying...', 'woocommerce' ); ?>';

            const data = new FormData();
            data.append( 'action', 'aakaari_apply_coupon' );
            data.append( 'coupon', code );
            data.append( 'nonce', '<?php echo wp_create_nonce( 'aakaari_checkout_nonce' ); ?>' );

            fetch( '<?php echo admin_url( 'admin-ajax.php' ); ?>', {
                method: 'POST',
                body: data
            })
            .then( r => r.json() )
            .then( data => {
                if ( data.success ) {
                    location.reload();
                } else {
                    alert( data.data.message || '<?php esc_html_e( 'Invalid coupon code', 'woocommerce' ); ?>' );
                    applyBtn.disabled = false;
                    applyBtn.textContent = '<?php esc_html_e( 'Apply', 'woocommerce' ); ?>';
                }
            })
            .catch( () => {
                alert( '<?php esc_html_e( 'Error applying coupon', 'woocommerce' ); ?>' );
                applyBtn.disabled = false;
                applyBtn.textContent = '<?php esc_html_e( 'Apply', 'woocommerce' ); ?>';
            });
        });
    }
});
</script>
