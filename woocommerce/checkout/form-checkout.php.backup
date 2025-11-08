<?php
/**
 * Checkout Form - Exact Figma Design
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo '<div class="checkout-page"><div class="checkout-container"><div class="auth-required">';
	echo '<h2>Please log in to continue</h2>';
	echo '<p>You need to be logged in to complete your purchase</p>';
	echo '<a href="' . esc_url( wc_get_page_permalink( 'myaccount' ) ) . '" class="btn-primary">Go to Login</a>';
	echo '</div></div></div>';
	return;
}

?>

<div class="checkout-page">
    <div class="checkout-container">

        <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

            <?php if ( $checkout->get_checkout_fields() ) : ?>

                <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                <div class="checkout-grid">
                    <!-- Checkout Form -->
                    <div class="checkout-form">
                        <div class="form-section">
                            <h2>
                                <svg style="display: inline-block; width: 1.5rem; height: 1.5rem; margin-right: 0.5rem; vertical-align: middle;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="1" y="3" width="15" height="13"></rect>
                                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                                </svg>
                                Billing & Shipping Address
                            </h2>
                            <?php do_action( 'woocommerce_checkout_billing' ); ?>
                            <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                        </div>

                        <?php if ( wc_get_page_id( 'terms' ) > 0 && apply_filters( 'woocommerce_checkout_show_terms', true ) ) : ?>
                            <div class="form-section">
                                <?php do_action( 'woocommerce_checkout_terms_and_conditions' ); ?>
                            </div>
                        <?php endif; ?>

                        <div class="form-section">
                            <h2>
                                <svg style="display: inline-block; width: 1.5rem; height: 1.5rem; margin-right: 0.5rem; vertical-align: middle;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                                Additional Information
                            </h2>
                            <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
                        </div>
                    </div>

                    <!-- Order Summary Sidebar -->
                    <div class="order-summary">
                        <h3><?php esc_html_e( 'Order Summary', 'woocommerce' ); ?></h3>

                        <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

                        <div id="order_review" class="woocommerce-checkout-review-order">
                            <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                        </div>

                        <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
                    </div>
                </div>

            <?php endif; ?>

        </form>

    </div>
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
