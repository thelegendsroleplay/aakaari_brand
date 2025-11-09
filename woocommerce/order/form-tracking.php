<?php
/**
 * Order Tracking Form and Results
 *
 * This template shows both the tracking form and results
 */

defined( 'ABSPATH' ) || exit;

global $post;

// Get order details if tracking
$order = false;

if ( isset( $_REQUEST['orderid'] ) && isset( $_REQUEST['order_email'] ) ) {
	$order_id    = empty( $_REQUEST['orderid'] ) ? 0 : ltrim( wc_clean( wp_unslash( $_REQUEST['orderid'] ) ), '#' );
	$order_email = sanitize_email( wp_unslash( $_REQUEST['order_email'] ) );

	if ( ! $order_id ) {
		wc_add_notice( __( 'Please enter a valid order ID', 'woocommerce' ), 'error' );
	} elseif ( ! $order_email ) {
		wc_add_notice( __( 'Please enter a valid email address', 'woocommerce' ), 'error' );
	} else {
		$order = wc_get_order( apply_filters( 'woocommerce_order_tracking_get_order_number', $order_id ) );

		if ( ! $order || ! hash_equals( strtolower( $order->get_billing_email() ), strtolower( $order_email ) ) ) {
			wc_add_notice( __( 'Sorry, the order could not be found. Please contact us if you are having difficulty finding your order details.', 'woocommerce' ), 'error' );
			$order = false;
		}
	}
}
?>

<style>
.tracking-page {
	max-width: 800px;
	margin: 3rem auto;
	padding: 0 1.5rem;
}

.tracking-header {
	text-align: center;
	margin-bottom: 3rem;
}

.tracking-header h1 {
	margin: 0 0 0.5rem;
	font-size: 2rem;
	color: #000;
}

.tracking-header p {
	margin: 0;
	color: #666;
	font-size: 0.875rem;
}

.tracking-form {
	background: #fff;
	border: 1px solid #e5e7eb;
	border-radius: 8px;
	padding: 2.5rem;
	margin-bottom: 2rem;
}

.tracking-form .form-row {
	margin-bottom: 1.5rem;
}

.tracking-form label {
	display: block;
	margin-bottom: 0.5rem;
	font-size: 0.875rem;
	font-weight: 500;
	color: #333;
}

.tracking-form input[type="text"],
.tracking-form input[type="email"] {
	width: 100%;
	padding: 0.75rem 1rem;
	border: 1px solid #e5e7eb;
	border-radius: 6px;
	font-size: 0.875rem;
	transition: all 0.2s ease;
}

.tracking-form input:focus {
	outline: none;
	border-color: #000;
	box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
}

.tracking-form button {
	width: 100%;
	padding: 0.875rem 1.5rem;
	background: #000;
	color: #fff;
	border: none;
	border-radius: 6px;
	font-size: 0.875rem;
	font-weight: 600;
	cursor: pointer;
	transition: background 0.2s ease;
}

.tracking-form button:hover {
	background: #333;
}

.tracking-result {
	background: #fff;
	border: 1px solid #e5e7eb;
	border-radius: 8px;
	padding: 2.5rem;
}

.order-status-header {
	text-align: center;
	padding-bottom: 2rem;
	margin-bottom: 2rem;
	border-bottom: 1px solid #e5e7eb;
}

.order-status-header h2 {
	margin: 0 0 1rem;
	font-size: 1.5rem;
	color: #000;
}

.order-status-header .status-badge {
	display: inline-block;
	padding: 0.5rem 1rem;
	border-radius: 9999px;
	font-size: 0.875rem;
	font-weight: 600;
	text-transform: capitalize;
}

.order-status-header .status-badge.pending {
	background: #fef3c7;
	color: #92400e;
}

.order-status-header .status-badge.processing {
	background: #dbeafe;
	color: #1e40af;
}

.order-status-header .status-badge.completed {
	background: #d1fae5;
	color: #065f46;
}

.order-status-header .status-badge.on-hold {
	background: #fef3c7;
	color: #92400e;
}

.order-status-header .status-badge.cancelled,
.order-status-header .status-badge.refunded,
.order-status-header .status-badge.failed {
	background: #fee2e2;
	color: #991b1b;
}

.order-status-header .order-number {
	color: #666;
	font-size: 0.875rem;
	margin-top: 0.5rem;
}

.tracking-timeline {
	position: relative;
	padding-left: 2rem;
}

.tracking-timeline::before {
	content: '';
	position: absolute;
	left: 0.5rem;
	top: 0.5rem;
	bottom: 0.5rem;
	width: 2px;
	background: #e5e7eb;
}

.tracking-step {
	position: relative;
	padding-bottom: 2rem;
}

.tracking-step:last-child {
	padding-bottom: 0;
}

.tracking-step::before {
	content: '';
	position: absolute;
	left: -1.5rem;
	top: 0.25rem;
	width: 1rem;
	height: 1rem;
	border-radius: 50%;
	background: #fff;
	border: 2px solid #e5e7eb;
	z-index: 1;
}

.tracking-step.completed::before {
	background: #10b981;
	border-color: #10b981;
}

.tracking-step.active::before {
	background: #3b82f6;
	border-color: #3b82f6;
	box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
}

.step-content h3 {
	margin: 0 0 0.25rem;
	font-size: 1rem;
	color: #000;
}

.step-content .step-date {
	color: #666;
	font-size: 0.875rem;
	margin: 0;
}

.step-content .step-note {
	margin: 0.5rem 0 0;
	padding: 0.75rem;
	background: #f9fafb;
	border-radius: 4px;
	font-size: 0.875rem;
	color: #666;
}

.order-details-summary {
	margin-top: 2rem;
	padding-top: 2rem;
	border-top: 1px solid #e5e7eb;
}

.order-details-summary h3 {
	margin: 0 0 1rem;
	font-size: 1.125rem;
	color: #000;
}

.order-details-grid {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
	gap: 1.5rem;
}

.order-detail-item .detail-label {
	font-size: 0.75rem;
	color: #666;
	text-transform: uppercase;
	letter-spacing: 0.5px;
	margin-bottom: 0.5rem;
}

.order-detail-item .detail-value {
	color: #000;
	font-size: 0.875rem;
	margin: 0;
}

.tracking-actions {
	margin-top: 2rem;
	text-align: center;
}

.tracking-actions a {
	display: inline-block;
	padding: 0.75rem 1.5rem;
	background: #000;
	color: #fff;
	text-decoration: none;
	border-radius: 6px;
	font-size: 0.875rem;
	font-weight: 500;
	transition: background 0.2s ease;
	margin: 0 0.5rem;
}

.tracking-actions a:hover {
	background: #333;
}

.tracking-actions a.secondary {
	background: #f3f4f6;
	color: #000;
	border: 1px solid #e5e7eb;
}

.tracking-actions a.secondary:hover {
	background: #e5e7eb;
}

@media (max-width: 768px) {
	.tracking-form {
		padding: 1.5rem;
	}

	.tracking-result {
		padding: 1.5rem;
	}

	.order-details-grid {
		grid-template-columns: 1fr;
	}

	.tracking-actions a {
		display: block;
		margin: 0.5rem 0;
	}
}
</style>

<div class="tracking-page">
	<?php if ( ! $order ) : ?>
		<div class="tracking-header">
			<h1><?php esc_html_e( 'Track Your Order', 'woocommerce' ); ?></h1>
			<p><?php esc_html_e( 'Enter your order details below to track your shipment', 'woocommerce' ); ?></p>
		</div>

		<?php wc_print_notices(); ?>

		<div class="tracking-form">
			<form action="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" method="post" class="woocommerce-form-track-order">

				<p class="form-row">
					<label for="orderid"><?php esc_html_e( 'Order ID', 'woocommerce' ); ?></label>
					<input class="input-text" type="text" name="orderid" id="orderid" value="<?php echo isset( $_REQUEST['orderid'] ) ? esc_attr( wp_unslash( $_REQUEST['orderid'] ) ) : ''; ?>" placeholder="<?php esc_attr_e( 'Found in your order confirmation email.', 'woocommerce' ); ?>" />
				</p>

				<p class="form-row">
					<label for="order_email"><?php esc_html_e( 'Billing email', 'woocommerce' ); ?></label>
					<input class="input-text" type="email" name="order_email" id="order_email" value="<?php echo isset( $_REQUEST['order_email'] ) ? esc_attr( wp_unslash( $_REQUEST['order_email'] ) ) : ''; ?>" placeholder="<?php esc_attr_e( 'Email you used during checkout.', 'woocommerce' ); ?>" />
				</p>

				<div class="clear"></div>

				<p class="form-row">
					<button type="submit" class="button" name="track" value="<?php esc_attr_e( 'Track', 'woocommerce' ); ?>">
						<?php esc_html_e( 'Track Order', 'woocommerce' ); ?>
					</button>
				</p>

				<?php wp_nonce_field( 'woocommerce-order_tracking', 'woocommerce-order-tracking-nonce' ); ?>

			</form>
		</div>
	<?php else : ?>
		<div class="tracking-result">
			<div class="order-status-header">
				<h2><?php esc_html_e( 'Order Status', 'woocommerce' ); ?></h2>
				<span class="status-badge <?php echo esc_attr( $order->get_status() ); ?>">
					<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
				</span>
				<p class="order-number">
					<?php
					printf(
						/* translators: %s: Order ID. */
						esc_html__( 'Order #%s', 'woocommerce' ),
						'<strong>' . esc_html( $order->get_order_number() ) . '</strong>'
					);
					?>
				</p>
			</div>

			<div class="tracking-timeline">
				<?php
				$status = $order->get_status();
				$statuses = array(
					'pending'    => array(
						'title' => __( 'Order Received', 'woocommerce' ),
						'note'  => __( 'We have received your order and will process it soon.', 'woocommerce' ),
					),
					'processing' => array(
						'title' => __( 'Processing', 'woocommerce' ),
						'note'  => __( 'Your order is currently being processed and prepared for shipment.', 'woocommerce' ),
					),
					'on-hold'    => array(
						'title' => __( 'On Hold', 'woocommerce' ),
						'note'  => __( 'Your order is on hold. Please contact us for more information.', 'woocommerce' ),
					),
					'completed'  => array(
						'title' => __( 'Delivered', 'woocommerce' ),
						'note'  => __( 'Your order has been delivered successfully.', 'woocommerce' ),
					),
					'cancelled'  => array(
						'title' => __( 'Cancelled', 'woocommerce' ),
						'note'  => __( 'This order has been cancelled.', 'woocommerce' ),
					),
					'refunded'   => array(
						'title' => __( 'Refunded', 'woocommerce' ),
						'note'  => __( 'This order has been refunded.', 'woocommerce' ),
					),
					'failed'     => array(
						'title' => __( 'Failed', 'woocommerce' ),
						'note'  => __( 'Payment failed or was declined.', 'woocommerce' ),
					),
				);

				$current_status = $order->get_status();
				$status_map = array( 'pending', 'processing', 'completed' );
				$current_index = array_search( $current_status, $status_map );

				if ( $current_status === 'on-hold' ) {
					$current_index = 1;
				} elseif ( in_array( $current_status, array( 'cancelled', 'refunded', 'failed' ) ) ) {
					$current_index = -1;
				}

				// Show order placed
				?>
				<div class="tracking-step completed">
					<div class="step-content">
						<h3><?php esc_html_e( 'Order Placed', 'woocommerce' ); ?></h3>
						<p class="step-date">
							<?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?>
						</p>
						<div class="step-note">
							<?php esc_html_e( 'Your order has been placed successfully.', 'woocommerce' ); ?>
						</div>
					</div>
				</div>

				<?php
				// Show current status
				if ( isset( $statuses[ $current_status ] ) ) {
					$step_class = in_array( $current_status, array( 'cancelled', 'refunded', 'failed' ) ) ? 'active' : 'active';
					?>
					<div class="tracking-step <?php echo esc_attr( $step_class ); ?>">
						<div class="step-content">
							<h3><?php echo esc_html( $statuses[ $current_status ]['title'] ); ?></h3>
							<?php if ( $order->get_date_modified() ) : ?>
								<p class="step-date">
									<?php echo esc_html( wc_format_datetime( $order->get_date_modified() ) ); ?>
								</p>
							<?php endif; ?>
							<div class="step-note">
								<?php echo esc_html( $statuses[ $current_status ]['note'] ); ?>
							</div>
						</div>
					</div>
					<?php
				}

				// Show future steps for normal flow
				if ( ! in_array( $current_status, array( 'cancelled', 'refunded', 'failed', 'completed' ) ) ) {
					if ( $current_index < 2 ) {
						?>
						<div class="tracking-step">
							<div class="step-content">
								<h3><?php esc_html_e( 'Out for Delivery', 'woocommerce' ); ?></h3>
								<p class="step-date"><?php esc_html_e( 'Pending', 'woocommerce' ); ?></p>
							</div>
						</div>
						<div class="tracking-step">
							<div class="step-content">
								<h3><?php esc_html_e( 'Delivered', 'woocommerce' ); ?></h3>
								<p class="step-date"><?php esc_html_e( 'Pending', 'woocommerce' ); ?></p>
							</div>
						</div>
						<?php
					}
				}
				?>
			</div>

			<div class="order-details-summary">
				<h3><?php esc_html_e( 'Order Details', 'woocommerce' ); ?></h3>
				<div class="order-details-grid">
					<div class="order-detail-item">
						<div class="detail-label"><?php esc_html_e( 'Order Number', 'woocommerce' ); ?></div>
						<p class="detail-value"><?php echo esc_html( $order->get_order_number() ); ?></p>
					</div>
					<div class="order-detail-item">
						<div class="detail-label"><?php esc_html_e( 'Order Date', 'woocommerce' ); ?></div>
						<p class="detail-value"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></p>
					</div>
					<div class="order-detail-item">
						<div class="detail-label"><?php esc_html_e( 'Total', 'woocommerce' ); ?></div>
						<p class="detail-value"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></p>
					</div>
					<div class="order-detail-item">
						<div class="detail-label"><?php esc_html_e( 'Payment Method', 'woocommerce' ); ?></div>
						<p class="detail-value"><?php echo esc_html( $order->get_payment_method_title() ); ?></p>
					</div>
				</div>
			</div>

			<div class="tracking-actions">
				<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() ) : ?>
					<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
						<?php esc_html_e( 'View Full Order Details', 'woocommerce' ); ?>
					</a>
				<?php endif; ?>
				<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" class="secondary">
					<?php esc_html_e( 'Track Another Order', 'woocommerce' ); ?>
				</a>
			</div>
		</div>
	<?php endif; ?>
</div>
