<?php
/**
 * Checkout Form - Figma Design
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
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
                            <?php do_action( 'woocommerce_checkout_billing' ); ?>
                        </div>

                        <div class="form-section">
                            <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                        </div>

                        <div class="form-section">
                            <h2>Additional Information</h2>
                            <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
                        </div>
                    </div>

                    <!-- Order Summary Sidebar -->
                    <div class="order-summary">
                        <h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'woocommerce' ); ?></h3>

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
