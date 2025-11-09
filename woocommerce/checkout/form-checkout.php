<?php
/**
 * Checkout Form
 *
 * @package Aakaari
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

// Defensive check
if ( ! WC()->cart || WC()->cart->is_empty() ) :
	?>
	<div class="checkout-page">
		<div class="checkout-container">
			<div class="empty-checkout">
				<h2><?php esc_html_e( 'Your cart is empty', 'aakaari' ); ?></h2>
				<p><?php esc_html_e( 'Please add some items to your cart before checking out', 'aakaari' ); ?></p>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn">
					<?php esc_html_e( 'Continue Shopping', 'aakaari' ); ?>
				</a>
			</div>
		</div>
	</div>
	<?php
	return;
endif;

// Get cart data
$cart_items = WC()->cart->get_cart();
$subtotal = WC()->cart->get_subtotal();
$shipping_total = WC()->cart->get_shipping_total();
$tax_total = WC()->cart->get_tax_totals();
$total = WC()->cart->get_total( 'edit' );
?>

<div class="checkout-page">
	<div class="checkout-container">

		<!-- Progress Steps -->
		<div class="checkout-steps">
			<div class="step active" data-step="1">
				<div class="step-number">1</div>
				<span><?php esc_html_e( 'Shipping', 'aakaari' ); ?></span>
			</div>
			<div class="step-line"></div>
			<div class="step" data-step="2">
				<div class="step-number">2</div>
				<span><?php esc_html_e( 'Billing', 'aakaari' ); ?></span>
			</div>
			<div class="step-line"></div>
			<div class="step" data-step="3">
				<div class="step-number">3</div>
				<span><?php esc_html_e( 'Payment', 'aakaari' ); ?></span>
			</div>
			<div class="step-line"></div>
			<div class="step" data-step="4">
				<div class="step-number">4</div>
				<span><?php esc_html_e( 'Confirm', 'aakaari' ); ?></span>
			</div>
		</div>

		<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

			<div class="checkout-grid">

				<!-- Checkout Form (Left Side) -->
				<div class="checkout-form">

					<!-- Step 1: Shipping Address -->
					<div class="form-section shipping-section" data-form-step="1">
						<h2>
							<svg class="inline-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M16 3h5v5M4 20L21 3M21 16v5h-5M15 15l6 6M4 4l5 5"/>
							</svg>
							<?php esc_html_e( 'Shipping Address', 'aakaari' ); ?>
						</h2>

						<div class="form-grid">
							<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

							<?php foreach ( $checkout->get_checkout_fields( 'shipping' ) as $key => $field ) : ?>
								<?php
								$field_name = str_replace( 'shipping_', '', $key );
								$col_span = in_array( $field_name, array( 'first_name', 'last_name', 'address_1', 'address_2' ) ) ? 'col-span-2' : '';
								?>
								<div class="form-group <?php echo esc_attr( $col_span ); ?>">
									<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
								</div>
							<?php endforeach; ?>

							<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>
						</div>

						<button type="button" class="btn btn-primary w-full next-step" data-next="2">
							<?php esc_html_e( 'Continue to Billing', 'aakaari' ); ?>
						</button>
					</div>

					<!-- Step 2: Billing Address -->
					<div class="form-section billing-section" data-form-step="2" style="display: none;">
						<h2>
							<svg class="inline-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
								<line x1="1" y1="10" x2="23" y2="10"/>
							</svg>
							<?php esc_html_e( 'Billing Address', 'aakaari' ); ?>
						</h2>

						<div class="same-address-check">
							<input type="checkbox" id="ship_to_different_address" name="ship_to_different_address" value="1" />
							<label for="ship_to_different_address">
								<?php esc_html_e( 'Same as shipping address', 'aakaari' ); ?>
							</label>
						</div>

						<div class="billing-fields" style="display: none;">
							<div class="form-grid">
								<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

								<?php foreach ( $checkout->get_checkout_fields( 'billing' ) as $key => $field ) : ?>
									<?php
									$field_name = str_replace( 'billing_', '', $key );
									$col_span = in_array( $field_name, array( 'first_name', 'last_name', 'address_1', 'address_2' ) ) ? 'col-span-2' : '';
									?>
									<div class="form-group <?php echo esc_attr( $col_span ); ?>">
										<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
									</div>
								<?php endforeach; ?>

								<?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
							</div>
						</div>

						<div class="form-actions">
							<button type="button" class="btn outline prev-step" data-prev="1">
								<?php esc_html_e( 'Back', 'aakaari' ); ?>
							</button>
							<button type="button" class="btn btn-primary next-step" data-next="3">
								<?php esc_html_e( 'Continue to Payment', 'aakaari' ); ?>
							</button>
						</div>
					</div>

					<!-- Step 3: Payment -->
					<div class="form-section payment-section" data-form-step="3" style="display: none;">
						<h2>
							<svg class="inline-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
								<line x1="1" y1="10" x2="23" y2="10"/>
							</svg>
							<?php esc_html_e( 'Payment Method', 'aakaari' ); ?>
						</h2>

						<?php if ( WC()->cart->needs_payment() ) : ?>
							<div class="payment-methods">
								<?php
								if ( ! empty( $available_gateways = WC()->payment_gateways()->get_available_payment_gateways() ) ) {
									foreach ( $available_gateways as $gateway ) {
										?>
										<div class="payment-option">
											<input
												type="radio"
												id="payment_method_<?php echo esc_attr( $gateway->id ); ?>"
												name="payment_method"
												value="<?php echo esc_attr( $gateway->id ); ?>"
												<?php checked( $gateway->chosen, true ); ?>
											/>
											<label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
												<?php echo wp_kses_post( $gateway->get_title() ); ?>
											</label>
										</div>
										<?php
										if ( $gateway->has_fields() || $gateway->get_description() ) {
											?>
											<div class="payment-box payment_method_<?php echo esc_attr( $gateway->id ); ?>" style="display: none;">
												<?php $gateway->payment_fields(); ?>
											</div>
											<?php
										}
									}
								}
								?>
							</div>
						<?php endif; ?>

						<div class="form-group">
							<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
							<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
						</div>

						<div class="form-actions">
							<button type="button" class="btn outline prev-step" data-prev="2">
								<?php esc_html_e( 'Back', 'aakaari' ); ?>
							</button>
							<button type="submit" class="btn btn-primary" name="woocommerce_checkout_place_order" id="place_order" value="<?php esc_attr_e( 'Place order', 'woocommerce' ); ?>">
								<?php esc_html_e( 'Place Order', 'aakaari' ); ?>
							</button>
						</div>
					</div>

					<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

				</div>

				<!-- Order Summary Sidebar (Right Side) -->
				<aside class="order-summary">
					<h3><?php esc_html_e( 'Order Summary', 'aakaari' ); ?></h3>

					<div class="summary-items">
						<?php foreach ( $cart_items as $cart_item_key => $cart_item ) :
							$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

							if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 ) {
								continue;
							}

							$product_id = $cart_item['product_id'];
							$product_name = $_product->get_name();
							$thumbnail = $_product->get_image( 'thumbnail' );
							$product_price = WC()->cart->get_product_price( $_product );
							$product_subtotal = WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] );
							?>
							<div class="summary-item">
								<div class="item-image">
									<?php echo wp_kses_post( $thumbnail ); ?>
								</div>
								<div class="item-info">
									<p class="item-name"><?php echo esc_html( $product_name ); ?></p>
									<p class="item-details">
										<?php
										// Get variation data if it's a variable product
										if ( isset( $cart_item['variation'] ) && is_array( $cart_item['variation'] ) ) {
											$variation_data = array();
											foreach ( $cart_item['variation'] as $name => $value ) {
												$variation_data[] = esc_html( wc_attribute_label( str_replace( 'attribute_', '', $name ) ) ) . ': ' . esc_html( $value );
											}
											echo implode( ' • ', $variation_data ) . ' • ';
										}
										?>
										<?php echo esc_html__( 'Qty:', 'aakaari' ) . ' ' . esc_html( $cart_item['quantity'] ); ?>
									</p>
								</div>
								<span class="item-price"><?php echo wp_kses_post( $product_subtotal ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>

					<div class="summary-separator"></div>

					<!-- Coupon Section -->
					<?php if ( wc_coupons_enabled() ) : ?>
						<div class="coupon-section">
							<input
								type="text"
								name="coupon_code"
								class="input-text"
								id="coupon_code"
								placeholder="<?php esc_attr_e( 'Coupon code', 'aakaari' ); ?>"
							/>
							<button type="button" class="btn outline apply-coupon" name="apply_coupon">
								<?php esc_html_e( 'Apply', 'aakaari' ); ?>
							</button>
						</div>
					<?php endif; ?>

					<!-- Totals -->
					<div class="summary-totals">
						<div class="total-row">
							<span><?php esc_html_e( 'Subtotal', 'aakaari' ); ?></span>
							<span><?php echo wc_price( $subtotal ); ?></span>
						</div>
						<div class="total-row">
							<span><?php esc_html_e( 'Shipping', 'aakaari' ); ?></span>
							<span><?php echo $shipping_total > 0 ? wc_price( $shipping_total ) : esc_html__( 'FREE', 'aakaari' ); ?></span>
						</div>
						<?php if ( ! empty( $tax_total ) ) : ?>
							<div class="total-row">
								<span><?php esc_html_e( 'Tax', 'aakaari' ); ?></span>
								<span>
									<?php
									$tax_amount = 0;
									foreach ( $tax_total as $tax ) {
										$tax_amount += $tax->amount;
									}
									echo wc_price( $tax_amount );
									?>
								</span>
							</div>
						<?php endif; ?>

						<?php if ( WC()->cart->get_discount_total() > 0 ) : ?>
							<div class="total-row discount">
								<span><?php esc_html_e( 'Discount', 'aakaari' ); ?></span>
								<span>-<?php echo wc_price( WC()->cart->get_discount_total() ); ?></span>
							</div>
						<?php endif; ?>

						<div class="summary-separator"></div>

						<div class="total-row grand-total">
							<span><?php esc_html_e( 'Total', 'aakaari' ); ?></span>
							<span><?php echo wc_price( $total ); ?></span>
						</div>
					</div>

				</aside>

			</div>

			<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>

		</form>

	</div>
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
