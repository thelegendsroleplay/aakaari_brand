<?php
/**
 * Aakaari Checkout - Mobile-First, Clean Design
 * Only for logged-in users, completely custom design using WooCommerce backend
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

// Force login requirement
if ( ! is_user_logged_in() ) {
    echo '<div class="aakaari-checkout-login-required">';
    echo '<div class="login-required-card">';
    echo '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
    echo '<h2>' . esc_html__( 'Login Required', 'woocommerce' ) . '</h2>';
    echo '<p>' . esc_html__( 'Please sign in to complete your purchase', 'woocommerce' ) . '</p>';
    echo '<div class="login-actions">';
    echo '<a href="' . esc_url( wc_get_page_permalink( 'myaccount' ) . '?redirect=' . urlencode( wc_get_checkout_url() ) ) . '" class="btn btn-primary">' . esc_html__( 'Sign In', 'woocommerce' ) . '</a>';
    echo '<a href="' . esc_url( wc_get_page_permalink( 'myaccount' ) ) . '" class="btn btn-outline">' . esc_html__( 'Create Account', 'woocommerce' ) . '</a>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    return;
}

// Check if cart is empty
if ( WC()->cart->is_empty() ) {
    echo '<div class="aakaari-checkout-empty">';
    echo '<div class="empty-card">';
    echo '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>';
    echo '<h2>' . esc_html__( 'Your Cart is Empty', 'woocommerce' ) . '</h2>';
    echo '<p>' . esc_html__( 'Add some items to your cart before checking out', 'woocommerce' ) . '</p>';
    echo '<a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '" class="btn btn-primary">' . esc_html__( 'Continue Shopping', 'woocommerce' ) . '</a>';
    echo '</div>';
    echo '</div>';
    return;
}

$checkout = WC()->checkout();
?>

<div class="aakaari-checkout">
    <div class="aakaari-checkout-container">

        <!-- Progress Indicator (Mobile-First) -->
        <div class="checkout-progress">
            <div class="progress-step active">
                <div class="step-circle">1</div>
                <span class="step-label"><?php esc_html_e( 'Details', 'woocommerce' ); ?></span>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <div class="step-circle">2</div>
                <span class="step-label"><?php esc_html_e( 'Payment', 'woocommerce' ); ?></span>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <div class="step-circle">3</div>
                <span class="step-label"><?php esc_html_e( 'Done', 'woocommerce' ); ?></span>
            </div>
        </div>

        <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

            <?php if ( $checkout->get_checkout_fields() ) : ?>

                <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                <div class="checkout-layout">

                    <!-- LEFT: Form Fields (Mobile-First Priority) -->
                    <div class="checkout-form-column">

                        <!-- Contact Information -->
                        <div class="checkout-section">
                            <h2 class="section-title">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <?php esc_html_e( 'Contact Information', 'woocommerce' ); ?>
                            </h2>
                            <div class="field-group">
                                <?php
                                $billing_email = $checkout->get_value( 'billing_email' );
                                if ( empty( $billing_email ) && is_user_logged_in() ) {
                                    $current_user = wp_get_current_user();
                                    $billing_email = $current_user->user_email;
                                }
                                ?>
                                <label for="billing_email"><?php esc_html_e( 'Email', 'woocommerce' ); ?> <span class="required">*</span></label>
                                <input type="email" class="input-field" name="billing_email" id="billing_email" placeholder="<?php esc_attr_e( 'john@example.com', 'woocommerce' ); ?>" value="<?php echo esc_attr( $billing_email ); ?>" required />
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <div class="checkout-section">
                            <h2 class="section-title">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                <?php esc_html_e( 'Shipping Address', 'woocommerce' ); ?>
                            </h2>

                            <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                        </div>

                        <!-- Payment Method -->
                        <div class="checkout-section">
                            <h2 class="section-title">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                                <?php esc_html_e( 'Payment Method', 'woocommerce' ); ?>
                            </h2>

                            <?php if ( WC()->cart->needs_payment() ) : ?>
                                <div class="checkout-payment">
                                    <?php woocommerce_checkout_payment(); ?>
                                </div>
                            <?php else : ?>
                                <p class="no-payment-required"><?php esc_html_e( 'No payment required for this order.', 'woocommerce' ); ?></p>
                            <?php endif; ?>
                        </div>

                    </div>

                    <!-- RIGHT: Order Summary (Sticky on Desktop) -->
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
                                                <?php
                                                $thumbnail = $_product->get_image( array( 60, 60 ) );
                                                echo $thumbnail;
                                                ?>
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

                                <!-- Coupon Code -->
                                <div class="coupon-section">
                                    <details class="coupon-toggle">
                                        <summary><?php esc_html_e( 'Have a coupon code?', 'woocommerce' ); ?></summary>
                                        <div class="coupon-form">
                                            <input type="text" id="coupon_code" placeholder="<?php esc_attr_e( 'Enter code', 'woocommerce' ); ?>" class="input-field" />
                                            <button type="button" id="apply_coupon" class="btn btn-outline btn-sm"><?php esc_html_e( 'Apply', 'woocommerce' ); ?></button>
                                        </div>
                                    </details>
                                </div>

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

                            </div>
                        </div>
                    </div>

                </div>

                <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

            <?php endif; ?>

        </form>

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
