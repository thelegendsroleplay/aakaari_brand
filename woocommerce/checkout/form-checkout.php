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
$is_user_logged_in = is_user_logged_in();
?>

<div class="checkout-page">
	<div class="checkout-container">

		<?php if ( ! $is_user_logged_in && get_option( 'woocommerce_enable_guest_checkout' ) === 'yes' ) : ?>
			<!-- Quick Login/Register for Guest Users -->
			<div class="quick-auth-section">
				<div class="auth-tabs">
					<button class="auth-tab active" data-tab="guest"><?php esc_html_e( 'Guest Checkout', 'aakaari' ); ?></button>
					<button class="auth-tab" data-tab="login"><?php esc_html_e( 'Login', 'aakaari' ); ?></button>
					<button class="auth-tab" data-tab="register"><?php esc_html_e( 'Register', 'aakaari' ); ?></button>
				</div>

				<div class="auth-content">
					<!-- Guest Checkout Info -->
					<div class="auth-panel active" data-panel="guest">
						<p class="auth-message">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<circle cx="12" cy="12" r="10"/>
								<line x1="12" y1="16" x2="12" y2="12"/>
								<line x1="12" y1="8" x2="12.01" y2="8"/>
							</svg>
							<?php esc_html_e( 'Checkout as guest or login/register for faster checkout', 'aakaari' ); ?>
						</p>
					</div>

					<!-- Login Form -->
					<div class="auth-panel" data-panel="login">
						<form class="quick-login-form" method="post">
							<div class="form-row">
								<label><?php esc_html_e( 'Email Address', 'aakaari' ); ?> *</label>
								<input type="email" name="username" required />
							</div>
							<div class="form-row">
								<label><?php esc_html_e( 'Password', 'aakaari' ); ?> *</label>
								<input type="password" name="password" required />
							</div>
							<div class="form-row remember-row">
								<label>
									<input type="checkbox" name="rememberme" value="forever" />
									<?php esc_html_e( 'Remember me', 'aakaari' ); ?>
								</label>
							</div>
							<?php wp_nonce_field( 'aakaari-login', 'login-nonce' ); ?>
							<button type="submit" name="aakaari_login" class="btn btn-primary w-full">
								<?php esc_html_e( 'Login & Continue', 'aakaari' ); ?>
							</button>
						</form>
					</div>

					<!-- Register Form -->
					<div class="auth-panel" data-panel="register">
						<form class="quick-register-form" method="post">
							<div class="form-row">
								<label><?php esc_html_e( 'Email Address', 'aakaari' ); ?> *</label>
								<input type="email" name="email" required />
							</div>
							<div class="form-row">
								<label><?php esc_html_e( 'Password', 'aakaari' ); ?> *</label>
								<input type="password" name="password" required minlength="6" />
							</div>
							<?php wp_nonce_field( 'aakaari-register', 'register-nonce' ); ?>
							<button type="submit" name="aakaari_register" class="btn btn-primary w-full">
								<?php esc_html_e( 'Register & Continue', 'aakaari' ); ?>
							</button>
						</form>
					</div>
				</div>
			</div>
		<?php elseif ( $is_user_logged_in ) : ?>
			<!-- Logged In User Info -->
			<div class="logged-in-notice">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
					<circle cx="12" cy="7" r="4"></circle>
				</svg>
				<?php
				$current_user = wp_get_current_user();
				printf(
					esc_html__( 'Welcome back, %s!', 'aakaari' ),
					esc_html( $current_user->display_name )
				);
				?>
				<a href="<?php echo esc_url( wp_logout_url( wc_get_checkout_url() ) ); ?>" class="logout-link">
					<?php esc_html_e( 'Not you?', 'aakaari' ); ?>
				</a>
			</div>
		<?php endif; ?>

		<!-- Progress Steps -->
		<div class="checkout-steps">
			<div class="step active" data-step="1">
				<div class="step-number">1</div>
				<span><?php esc_html_e( 'Details', 'aakaari' ); ?></span>
			</div>
			<div class="step-line"></div>
			<div class="step" data-step="2">
				<div class="step-number">2</div>
				<span><?php esc_html_e( 'Payment', 'aakaari' ); ?></span>
			</div>
			<div class="step-line"></div>
			<div class="step" data-step="3">
				<div class="step-number">3</div>
				<span><?php esc_html_e( 'Review', 'aakaari' ); ?></span>
			</div>
		</div>

		<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

			<div class="checkout-grid">

				<!-- Checkout Form (Left Side) -->
				<div class="checkout-form">

					<!-- Step 1: Shipping & Billing Details (Combined) -->
					<div class="form-section details-section" data-form-step="1">
						<h2>
							<svg class="inline-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M16 3h5v5M4 20L21 3M21 16v5h-5M15 15l6 6M4 4l5 5"/>
							</svg>
							<?php esc_html_e( 'Shipping Details', 'aakaari' ); ?>
						</h2>

						<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

						<div class="woocommerce-billing-fields">
							<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

							<div class="woocommerce-billing-fields__field-wrapper">
								<?php
								$fields = $checkout->get_checkout_fields( 'billing' );
								foreach ( $fields as $key => $field ) {
									woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
								}
								?>
							</div>

							<?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
						</div>

						<?php if ( WC()->cart->needs_shipping() && WC()->cart->needs_shipping_address() ) : ?>
							<div class="woocommerce-shipping-fields">
								<h3>
									<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
										<input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" type="checkbox" name="ship_to_different_address" value="1" />
										<span><?php esc_html_e( 'Ship to a different address?', 'aakaari' ); ?></span>
									</label>
								</h3>

								<div class="shipping_address" style="display: none;">
									<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

									<div class="woocommerce-shipping-fields__field-wrapper">
										<?php
										$fields = $checkout->get_checkout_fields( 'shipping' );
										foreach ( $fields as $key => $field ) {
											woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
										}
										?>
									</div>

									<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>
								</div>
							</div>
						<?php endif; ?>

						<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

						<?php if ( $checkout->get_checkout_fields( 'order' ) ) : ?>
							<div class="woocommerce-additional-fields">
								<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

								<div class="woocommerce-additional-fields__field-wrapper">
									<?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
										<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
									<?php endforeach; ?>
								</div>

								<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
							</div>
						<?php endif; ?>

						<button type="button" class="btn btn-primary w-full next-step" data-next="2">
							<?php esc_html_e( 'Continue to Payment', 'aakaari' ); ?>
						</button>
					</div>

					<!-- Step 2: Payment -->
					<div class="form-section payment-section" data-form-step="2" style="display: none;">
						<h2>
							<svg class="inline-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
								<line x1="1" y1="10" x2="23" y2="10"/>
							</svg>
							<?php esc_html_e( 'Payment Method', 'aakaari' ); ?>
						</h2>

						<?php if ( WC()->cart->needs_payment() ) : ?>
							<div id="payment" class="woocommerce-checkout-payment">
								<?php
								if ( ! empty( $available_gateways = WC()->payment_gateways()->get_available_payment_gateways() ) ) {
									?>
									<ul class="wc_payment_methods payment_methods methods">
										<?php
										foreach ( $available_gateways as $gateway ) {
											?>
											<li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?>">
												<input
													id="payment_method_<?php echo esc_attr( $gateway->id ); ?>"
													type="radio"
													class="input-radio"
													name="payment_method"
													value="<?php echo esc_attr( $gateway->id ); ?>"
													<?php checked( $gateway->chosen, true ); ?>
												/>
												<label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
													<?php echo wp_kses_post( $gateway->get_title() ); ?>
													<?php echo wp_kses_post( $gateway->get_icon() ); ?>
												</label>
												<?php
												if ( $gateway->has_fields() || $gateway->get_description() ) {
													?>
													<div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?>" style="display: none;">
														<?php $gateway->payment_fields(); ?>
													</div>
													<?php
												}
												?>
											</li>
											<?php
										}
										?>
									</ul>
									<?php
								}
								?>
							</div>
						<?php endif; ?>

						<div class="form-actions">
							<button type="button" class="btn outline prev-step" data-prev="1">
								<?php esc_html_e( 'Back', 'aakaari' ); ?>
							</button>
							<button type="button" class="btn btn-primary next-step" data-next="3">
								<?php esc_html_e( 'Review Order', 'aakaari' ); ?>
							</button>
						</div>
					</div>

					<!-- Step 3: Review & Place Order -->
					<div class="form-section review-section" data-form-step="3" style="display: none;">
						<h2>
							<svg class="inline-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<polyline points="20 6 9 17 4 12"></polyline>
							</svg>
							<?php esc_html_e( 'Review Your Order', 'aakaari' ); ?>
						</h2>

						<div class="order-review-summary">
							<div class="review-section-item">
								<h4><?php esc_html_e( 'Billing Details', 'aakaari' ); ?></h4>
								<div id="billing-review"></div>
								<button type="button" class="edit-link" data-edit-step="1">
									<?php esc_html_e( 'Edit', 'aakaari' ); ?>
								</button>
							</div>

							<div class="review-section-item">
								<h4><?php esc_html_e( 'Payment Method', 'aakaari' ); ?></h4>
								<div id="payment-review"></div>
								<button type="button" class="edit-link" data-edit-step="2">
									<?php esc_html_e( 'Edit', 'aakaari' ); ?>
								</button>
							</div>
						</div>

						<?php do_action( 'woocommerce_checkout_before_terms_and_conditions' ); ?>

						<div class="woocommerce-terms-and-conditions-wrapper">
							<?php
							/**
							 * Terms and conditions hook.
							 */
							do_action( 'woocommerce_checkout_terms_and_conditions' );
							?>
						</div>

						<div class="form-actions">
							<button type="button" class="btn outline prev-step" data-prev="2">
								<?php esc_html_e( 'Back', 'aakaari' ); ?>
							</button>
							<?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="btn btn-primary" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( __( 'Place order', 'woocommerce' ) ) . '" data-value="' . esc_attr( __( 'Place order', 'woocommerce' ) ) . '">' . esc_html__( 'Place Order', 'aakaari' ) . '</button>' ); ?>
						</div>
					</div>

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
							<div class="summary-item" data-cart-key="<?php echo esc_attr( $cart_item_key ); ?>">
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
											echo implode( ' • ', $variation_data );
										}
										?>
									</p>
									<div class="item-quantity-controls">
										<button type="button" class="qty-btn decrease" data-cart-key="<?php echo esc_attr( $cart_item_key ); ?>">-</button>
										<input
											type="number"
											class="qty-value"
											value="<?php echo esc_attr( $cart_item['quantity'] ); ?>"
											min="1"
											max="<?php echo esc_attr( $_product->get_max_purchase_quantity() ); ?>"
											data-cart-key="<?php echo esc_attr( $cart_item_key ); ?>"
										/>
										<button type="button" class="qty-btn increase" data-cart-key="<?php echo esc_attr( $cart_item_key ); ?>">+</button>
									</div>
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

					<!-- Trust Badges -->
					<div class="trust-badges">
						<div class="trust-badge">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
							</svg>
							<span><?php esc_html_e( 'Secure Checkout', 'aakaari' ); ?></span>
						</div>
						<div class="trust-badge">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
								<line x1="1" y1="10" x2="23" y2="10"/>
							</svg>
							<span><?php esc_html_e( 'SSL Encrypted', 'aakaari' ); ?></span>
						</div>
					</div>

				</aside>

			</div>

			<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

		</form>

	</div>
</div>
