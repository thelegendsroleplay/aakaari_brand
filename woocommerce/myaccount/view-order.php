<?php
/**
 * View Order
 *
 * Shows the details of a particular order on the account page.
 */

defined( 'ABSPATH' ) || exit;

$order = wc_get_order( $order_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

if ( ! $order ) {
	return;
}

$order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
$downloads             = $order->get_downloadable_items();
$show_downloads        = $order->has_downloadable_item() && $order->is_download_permitted();

if ( $show_downloads ) {
	wc_get_template(
		'order/order-downloads.php',
		array(
			'downloads'  => $downloads,
			'show_title' => true,
		)
	);
}
?>

<style>
.order-details-page {
	max-width: 1200px;
	margin: 0 auto;
	padding: 2rem 1.5rem;
}

.order-header {
	display: flex;
	justify-content: space-between;
	align-items: flex-start;
	margin-bottom: 2rem;
	padding-bottom: 1.5rem;
	border-bottom: 1px solid #e5e7eb;
	flex-wrap: wrap;
	gap: 1rem;
}

.order-header-left h1 {
	margin: 0 0 0.5rem;
	font-size: 1.5rem;
	color: #000;
}

.order-header-left p {
	margin: 0;
	color: #666;
	font-size: 0.875rem;
}

.order-status-badge {
	display: inline-block;
	padding: 0.5rem 1rem;
	border-radius: 9999px;
	font-size: 0.875rem;
	font-weight: 600;
	text-transform: capitalize;
}

.order-status-badge.pending {
	background: #fef3c7;
	color: #92400e;
}

.order-status-badge.processing {
	background: #dbeafe;
	color: #1e40af;
}

.order-status-badge.completed {
	background: #d1fae5;
	color: #065f46;
}

.order-status-badge.on-hold {
	background: #fef3c7;
	color: #92400e;
}

.order-status-badge.cancelled,
.order-status-badge.refunded,
.order-status-badge.failed {
	background: #fee2e2;
	color: #991b1b;
}

.order-actions {
	display: flex;
	gap: 0.5rem;
	flex-wrap: wrap;
}

.order-actions .button {
	padding: 0.5rem 1rem;
	background: #000;
	color: #fff;
	border: none;
	border-radius: 6px;
	font-size: 0.875rem;
	text-decoration: none;
	cursor: pointer;
	transition: background 0.2s ease;
	display: inline-block;
}

.order-actions .button:hover {
	background: #333;
}

.order-actions .button.secondary {
	background: #f3f4f6;
	color: #000;
	border: 1px solid #e5e7eb;
}

.order-actions .button.secondary:hover {
	background: #e5e7eb;
}

.order-content {
	display: grid;
	grid-template-columns: 1fr 350px;
	gap: 2rem;
}

.order-main {
	flex: 1;
}

.order-sidebar {
	width: 350px;
}

.order-section {
	background: #fff;
	border: 1px solid #e5e7eb;
	border-radius: 8px;
	padding: 1.5rem;
	margin-bottom: 1.5rem;
}

.order-section h2 {
	margin: 0 0 1.5rem;
	font-size: 1.125rem;
	color: #000;
	font-weight: 600;
}

.order-items {
	display: flex;
	flex-direction: column;
	gap: 1rem;
}

.order-item {
	display: flex;
	gap: 1rem;
	padding-bottom: 1rem;
	border-bottom: 1px solid #f0f0f0;
}

.order-item:last-child {
	border-bottom: none;
	padding-bottom: 0;
}

.order-item-image {
	width: 80px;
	height: 80px;
	flex-shrink: 0;
}

.order-item-image img {
	width: 100%;
	height: 100%;
	object-fit: cover;
	border-radius: 6px;
	background: #f5f5f5;
}

.order-item-details {
	flex: 1;
}

.order-item-details h3 {
	margin: 0 0 0.5rem;
	font-size: 1rem;
	color: #000;
}

.order-item-details h3 a {
	color: #000;
	text-decoration: none;
}

.order-item-details h3 a:hover {
	color: #666;
}

.order-item-meta {
	margin: 0;
	color: #666;
	font-size: 0.875rem;
}

.order-item-price {
	text-align: right;
	flex-shrink: 0;
}

.order-item-price .quantity {
	color: #666;
	font-size: 0.875rem;
	margin-bottom: 0.25rem;
}

.order-item-price .price {
	font-weight: 600;
	color: #000;
	font-size: 1rem;
}

.order-totals {
	border-top: 1px solid #e5e7eb;
	padding-top: 1rem;
	margin-top: 1rem;
}

.order-totals-row {
	display: flex;
	justify-content: space-between;
	padding: 0.5rem 0;
	font-size: 0.875rem;
}

.order-totals-row.total {
	font-size: 1.125rem;
	font-weight: 600;
	color: #000;
	padding-top: 1rem;
	margin-top: 0.5rem;
	border-top: 2px solid #e5e7eb;
}

.order-info-grid {
	display: grid;
	gap: 1rem;
}

.order-info-item {
	padding-bottom: 1rem;
	border-bottom: 1px solid #f0f0f0;
}

.order-info-item:last-child {
	border-bottom: none;
	padding-bottom: 0;
}

.order-info-label {
	font-size: 0.75rem;
	color: #666;
	text-transform: uppercase;
	letter-spacing: 0.5px;
	margin-bottom: 0.5rem;
}

.order-info-value {
	color: #000;
	font-size: 0.875rem;
	margin: 0;
}

.order-address {
	margin: 0;
	color: #000;
	font-size: 0.875rem;
	line-height: 1.6;
}

@media (max-width: 968px) {
	.order-content {
		grid-template-columns: 1fr;
	}

	.order-sidebar {
		width: 100%;
	}

	.order-header {
		flex-direction: column;
	}
}
</style>

<div class="order-details-page">
	<div class="order-header">
		<div class="order-header-left">
			<h1>
				<?php
				printf(
					/* translators: %s: Order ID. */
					esc_html__( 'Order #%s', 'woocommerce' ),
					'<strong>' . esc_html( $order->get_order_number() ) . '</strong>'
				);
				?>
			</h1>
			<p>
				<?php
				printf(
					/* translators: 1: Order date. */
					esc_html__( 'Placed on %s', 'woocommerce' ),
					'<time datetime="' . esc_attr( $order->get_date_created()->date( 'c' ) ) . '">' . esc_html( wc_format_datetime( $order->get_date_created() ) ) . '</time>'
				);
				?>
			</p>
		</div>
		<div class="order-header-right">
			<span class="order-status-badge <?php echo esc_attr( $order->get_status() ); ?>">
				<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
			</span>
		</div>
	</div>

	<div class="order-actions">
		<?php if ( $order->needs_payment() ) : ?>
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay">
				<?php esc_html_e( 'Pay for this order', 'woocommerce' ); ?>
			</a>
		<?php endif; ?>

		<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'order_id', $order->get_id(), wc_get_endpoint_url( 'order-tracking', '', wc_get_page_permalink( 'myaccount' ) ) ), 'track-order' ) ); ?>" class="button secondary">
			<?php esc_html_e( 'Track Order', 'woocommerce' ); ?>
		</a>

		<?php do_action( 'woocommerce_order_details_after_order_table_items', $order ); ?>
	</div>

	<div class="order-content">
		<div class="order-main">
			<!-- Order Items -->
			<div class="order-section">
				<h2><?php esc_html_e( 'Order Items', 'woocommerce' ); ?></h2>
				<div class="order-items">
					<?php
					foreach ( $order_items as $item_id => $item ) {
						$product = $item->get_product();

						if ( ! $product ) {
							continue;
						}

						$product_permalink = $product->is_visible() ? $product->get_permalink( $item ) : '';
						$thumbnail = $product->get_image( 'woocommerce_thumbnail' );
						?>
						<div class="order-item">
							<div class="order-item-image">
								<?php if ( $product_permalink ) : ?>
									<a href="<?php echo esc_url( $product_permalink ); ?>">
										<?php echo wp_kses_post( $thumbnail ); ?>
									</a>
								<?php else : ?>
									<?php echo wp_kses_post( $thumbnail ); ?>
								<?php endif; ?>
							</div>

							<div class="order-item-details">
								<h3>
									<?php
									if ( $product_permalink ) {
										echo '<a href="' . esc_url( $product_permalink ) . '">' . wp_kses_post( $item->get_name() ) . '</a>';
									} else {
										echo wp_kses_post( $item->get_name() );
									}
									?>
								</h3>

								<?php
								$item_meta = new WC_Order_Item_Product( $item_id );
								wc_display_item_meta( $item_meta, array( 'before' => '<p class="order-item-meta">', 'after' => '</p>', 'separator' => '<br>', 'echo' => true ) );
								?>

								<?php if ( $show_purchase_note && $product && ( $purchase_note = $product->get_purchase_note() ) ) : ?>
									<p class="order-item-meta"><?php echo wp_kses_post( wpautop( do_shortcode( $purchase_note ) ) ); ?></p>
								<?php endif; ?>
							</div>

							<div class="order-item-price">
								<p class="quantity"><?php echo esc_html( sprintf( __( 'Qty: %s', 'woocommerce' ), $item->get_quantity() ) ); ?></p>
								<p class="price"><?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?></p>
							</div>
						</div>
						<?php
					}
					?>
				</div>

				<!-- Order Totals -->
				<div class="order-totals">
					<div class="order-totals-row">
						<span><?php esc_html_e( 'Subtotal:', 'woocommerce' ); ?></span>
						<span><?php echo wp_kses_post( $order->get_subtotal_to_display() ); ?></span>
					</div>

					<?php if ( $order->get_shipping_method() ) : ?>
						<div class="order-totals-row">
							<span><?php esc_html_e( 'Shipping:', 'woocommerce' ); ?></span>
							<span><?php echo wp_kses_post( $order->get_shipping_to_display() ); ?></span>
						</div>
					<?php endif; ?>

					<?php if ( $order->get_total_tax() > 0 ) : ?>
						<div class="order-totals-row">
							<span><?php esc_html_e( 'Tax:', 'woocommerce' ); ?></span>
							<span><?php echo wp_kses_post( wc_price( $order->get_total_tax() ) ); ?></span>
						</div>
					<?php endif; ?>

					<?php if ( $order->get_total_discount() > 0 ) : ?>
						<div class="order-totals-row">
							<span><?php esc_html_e( 'Discount:', 'woocommerce' ); ?></span>
							<span>-<?php echo wp_kses_post( wc_price( $order->get_total_discount() ) ); ?></span>
						</div>
					<?php endif; ?>

					<div class="order-totals-row total">
						<span><?php esc_html_e( 'Total:', 'woocommerce' ); ?></span>
						<span><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></span>
					</div>

					<?php if ( $order->get_payment_method_title() ) : ?>
						<div class="order-totals-row">
							<span><?php esc_html_e( 'Payment method:', 'woocommerce' ); ?></span>
							<span><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></span>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="order-sidebar">
			<!-- Order Information -->
			<div class="order-section">
				<h2><?php esc_html_e( 'Order Information', 'woocommerce' ); ?></h2>
				<div class="order-info-grid">
					<div class="order-info-item">
						<div class="order-info-label"><?php esc_html_e( 'Order Number', 'woocommerce' ); ?></div>
						<p class="order-info-value"><?php echo esc_html( $order->get_order_number() ); ?></p>
					</div>

					<div class="order-info-item">
						<div class="order-info-label"><?php esc_html_e( 'Order Date', 'woocommerce' ); ?></div>
						<p class="order-info-value"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></p>
					</div>

					<div class="order-info-item">
						<div class="order-info-label"><?php esc_html_e( 'Status', 'woocommerce' ); ?></div>
						<p class="order-info-value"><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></p>
					</div>

					<?php if ( $order->get_customer_note() ) : ?>
						<div class="order-info-item">
							<div class="order-info-label"><?php esc_html_e( 'Note', 'woocommerce' ); ?></div>
							<p class="order-info-value"><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></p>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<?php if ( $show_customer_details ) : ?>
				<!-- Billing Address -->
				<?php if ( $order->get_billing_address_1() ) : ?>
					<div class="order-section">
						<h2><?php esc_html_e( 'Billing Address', 'woocommerce' ); ?></h2>
						<address class="order-address">
							<?php echo wp_kses_post( $order->get_formatted_billing_address( esc_html__( 'N/A', 'woocommerce' ) ) ); ?>
							<?php if ( $order->get_billing_phone() ) : ?>
								<br><strong><?php esc_html_e( 'Phone:', 'woocommerce' ); ?></strong> <?php echo esc_html( $order->get_billing_phone() ); ?>
							<?php endif; ?>
							<?php if ( $order->get_billing_email() ) : ?>
								<br><strong><?php esc_html_e( 'Email:', 'woocommerce' ); ?></strong> <?php echo esc_html( $order->get_billing_email() ); ?>
							<?php endif; ?>
						</address>
					</div>
				<?php endif; ?>

				<!-- Shipping Address -->
				<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && $order->get_shipping_address_1() ) : ?>
					<div class="order-section">
						<h2><?php esc_html_e( 'Shipping Address', 'woocommerce' ); ?></h2>
						<address class="order-address">
							<?php echo wp_kses_post( $order->get_formatted_shipping_address( esc_html__( 'N/A', 'woocommerce' ) ) ); ?>
							<?php if ( $order->get_shipping_phone() ) : ?>
								<br><strong><?php esc_html_e( 'Phone:', 'woocommerce' ); ?></strong> <?php echo esc_html( $order->get_shipping_phone() ); ?>
							<?php endif; ?>
						</address>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php do_action( 'woocommerce_view_order', $order_id ); ?>
