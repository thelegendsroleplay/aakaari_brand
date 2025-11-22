<?php
/**
 * Order Received (Thank You) Page
 * Professional order confirmation matching Aakaari theme
 *
 * @package Aakaari_Brand
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="order-received-wrapper">
    <?php
    if ( $order ) :
        do_action( 'woocommerce_before_thankyou', $order->get_id() );
        ?>

        <?php if ( $order->has_status( 'failed' ) ) : ?>
            <!-- Failed Order -->
            <div class="order-status-header order-failed">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <h1 class="order-title">Payment Failed</h1>
                <p class="order-subtitle">Unfortunately we could not process your payment. Please try again.</p>
            </div>

            <div class="order-actions">
                <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button order-button-primary">
                    Try Again
                </a>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="button order-button-secondary">
                    Continue Shopping
                </a>
            </div>

        <?php else : ?>
            <!-- Successful Order -->
            <div class="order-status-header order-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <h1 class="order-title">Thank you for your order!</h1>
                <p class="order-subtitle">Your order has been received and is being processed.</p>
            </div>

            <!-- Order Details Grid -->
            <div class="order-details-grid">
                <div class="order-detail-card">
                    <span class="order-detail-label">Order Number</span>
                    <span class="order-detail-value"><?php echo esc_html( $order->get_order_number() ); ?></span>
                </div>

                <div class="order-detail-card">
                    <span class="order-detail-label">Date</span>
                    <span class="order-detail-value"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></span>
                </div>

                <div class="order-detail-card">
                    <span class="order-detail-label">Email</span>
                    <span class="order-detail-value"><?php echo esc_html( $order->get_billing_email() ); ?></span>
                </div>

                <div class="order-detail-card">
                    <span class="order-detail-label">Total</span>
                    <span class="order-detail-value"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></span>
                </div>

                <div class="order-detail-card">
                    <span class="order-detail-label">Payment Method</span>
                    <span class="order-detail-value"><?php echo esc_html( $order->get_payment_method_title() ); ?></span>
                </div>
            </div>

            <?php if ( $order->get_payment_method_title() === 'Cash on delivery' ) : ?>
                <div class="order-payment-note">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <span>Pay with cash upon delivery.</span>
                </div>
            <?php endif; ?>

            <?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>

            <!-- Order Items -->
            <div class="order-section">
                <h2 class="order-section-title">Order Details</h2>
                <?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
            </div>

            <!-- Addresses -->
            <div class="order-addresses-grid">
                <div class="order-address-card">
                    <h3 class="order-address-title">Billing Address</h3>
                    <address class="order-address-content">
                        <?php echo wp_kses_post( $order->get_formatted_billing_address( __( 'N/A', 'woocommerce' ) ) ); ?>

                        <?php if ( $order->get_billing_phone() ) : ?>
                            <div class="order-address-phone">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                                <span><?php echo esc_html( $order->get_billing_phone() ); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ( $order->get_billing_email() ) : ?>
                            <div class="order-address-email">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                                <span><?php echo esc_html( $order->get_billing_email() ); ?></span>
                            </div>
                        <?php endif; ?>
                    </address>
                </div>

                <?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() ) : ?>
                    <div class="order-address-card">
                        <h3 class="order-address-title">Shipping Address</h3>
                        <address class="order-address-content">
                            <?php echo wp_kses_post( $order->get_formatted_shipping_address( __( 'N/A', 'woocommerce' ) ) ); ?>
                        </address>
                    </div>
                <?php endif; ?>
            </div>

        <?php endif; ?>

    <?php else : ?>
        <!-- No Order Found -->
        <div class="order-status-header order-not-found">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <h1 class="order-title">Order Not Found</h1>
            <p class="order-subtitle"><?php esc_html_e( 'Unfortunately we could not find your order.', 'woocommerce' ); ?></p>
        </div>

        <div class="order-actions">
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="button order-button-primary">
                Continue Shopping
            </a>
        </div>

    <?php endif; ?>
</div>

<style>
/* Order Received Page Styling */
.order-received-wrapper {
    max-width: 900px;
    margin: 0 auto;
    padding: 40px 20px;
}

/* Header Styles */
.order-status-header {
    text-align: center;
    padding: 48px 24px;
    margin-bottom: 40px;
    border-radius: 12px;
}

.order-status-header svg {
    margin: 0 auto 24px;
    display: block;
}

.order-success {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border: 1px solid #86efac;
}

.order-success svg {
    color: #16a34a;
}

.order-failed {
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    border: 1px solid #fca5a5;
}

.order-failed svg {
    color: #dc2626;
}

.order-not-found {
    background: linear-gradient(135deg, #fefce8 0%, #fef3c7 100%);
    border: 1px solid #fde047;
}

.order-not-found svg {
    color: #ca8a04;
}

.order-title {
    font-size: 32px;
    font-weight: 700;
    color: #000;
    margin: 0 0 12px 0;
    line-height: 1.2;
}

.order-subtitle {
    font-size: 16px;
    color: #6b7280;
    margin: 0;
    line-height: 1.5;
}

/* Order Details Grid */
.order-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 32px;
}

.order-detail-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.order-detail-label {
    font-size: 12px;
    font-weight: 500;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.order-detail-value {
    font-size: 16px;
    font-weight: 600;
    color: #000;
}

/* Payment Note */
.order-payment-note {
    background: #fefce8;
    border: 1px solid #fde047;
    border-radius: 8px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 32px;
    font-size: 14px;
    color: #854d0e;
}

.order-payment-note svg {
    flex-shrink: 0;
    color: #ca8a04;
}

/* Sections */
.order-section {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 32px;
    margin-bottom: 32px;
}

.order-section-title {
    font-size: 20px;
    font-weight: 700;
    color: #000;
    margin: 0 0 24px 0;
}

/* Address Grid */
.order-addresses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
}

.order-address-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
}

.order-address-title {
    font-size: 16px;
    font-weight: 600;
    color: #000;
    margin: 0 0 16px 0;
}

.order-address-content {
    font-size: 14px;
    line-height: 1.6;
    color: #374151;
    font-style: normal;
}

.order-address-phone,
.order-address-email {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 12px;
    font-size: 14px;
    color: #374151;
}

.order-address-phone svg,
.order-address-email svg {
    flex-shrink: 0;
    color: #6b7280;
}

/* Action Buttons */
.order-actions {
    display: flex;
    gap: 16px;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 32px;
}

.order-button-primary {
    background: #000;
    color: #fff !important;
    padding: 14px 32px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none !important;
    border: 2px solid #000;
    transition: all 0.2s ease;
    cursor: pointer;
}

.order-button-primary:hover {
    background: transparent;
    color: #000 !important;
}

.order-button-secondary {
    background: transparent;
    color: #000 !important;
    padding: 14px 32px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none !important;
    border: 2px solid #e5e7eb;
    transition: all 0.2s ease;
    cursor: pointer;
}

.order-button-secondary:hover {
    border-color: #000;
}

/* Responsive */
@media (max-width: 640px) {
    .order-received-wrapper {
        padding: 24px 16px;
    }

    .order-title {
        font-size: 24px;
    }

    .order-subtitle {
        font-size: 14px;
    }

    .order-details-grid {
        grid-template-columns: 1fr;
    }

    .order-addresses-grid {
        grid-template-columns: 1fr;
    }
}
</style>
