<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * @package Aakaari_Brand
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
    echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'aakaari-brand' ) ) );
    return;
}

?>

<div class="checkout-container">
    <h1 class="text-3xl md:text-4xl mb-8"><?php esc_html_e( 'Checkout', 'aakaari-brand' ); ?></h1>

    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

        <?php if ( $checkout->get_checkout_fields() ) : ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Checkout Form -->
                <div class="lg:col-span-2 space-y-6">

                    <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                    <!-- Billing Information -->
                    <div class="checkout-form-section">
                        <h2 class="text-xl mb-6"><?php esc_html_e( 'Billing Information', 'aakaari-brand' ); ?></h2>

                        <?php do_action( 'woocommerce_checkout_billing' ); ?>
                    </div>

                    <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
                        <!-- Shipping Information -->
                        <div class="checkout-form-section">
                            <h2 class="text-xl mb-6"><?php esc_html_e( 'Shipping Information', 'aakaari-brand' ); ?></h2>

                            <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                        </div>
                    <?php endif; ?>

                    <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

                    <!-- Order Notes -->
                    <?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>
                        <div class="checkout-form-section">
                            <h2 class="text-xl mb-6"><?php esc_html_e( 'Additional Information', 'aakaari-brand' ); ?></h2>

                            <?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
                                <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Payment Methods -->
                    <div class="checkout-form-section" id="payment">
                        <h2 class="text-xl mb-6"><?php esc_html_e( 'Payment Method', 'aakaari-brand' ); ?></h2>

                        <?php if ( WC()->cart->needs_payment() ) : ?>
                            <div class="payment-methods">
                                <?php
                                if ( ! empty( $available_gateways = WC()->payment_gateways()->get_available_payment_gateways() ) ) {
                                    foreach ( $available_gateways as $gateway ) {
                                        wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
                                    }
                                } else {
                                    echo '<p class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . esc_html__( 'Sorry, it seems that there are no available payment methods. Please contact us if you require assistance or wish to make alternate arrangements.', 'aakaari-brand' ) . '</p>';
                                }
                                ?>
                            </div>
                        <?php endif; ?>

                        <div class="form-row place-order mt-6">
                            <noscript>
                                <?php esc_html_e( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the <em>Place order</em> button once only.', 'aakaari-brand' ); ?>
                            </noscript>

                            <?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>

                            <?php do_action( 'woocommerce_review_order_before_submit' ); ?>

                            <div class="terms-and-conditions-wrapper mt-4">
                                <?php
                                /**
                                 * Terms and conditions checkbox.
                                 */
                                do_action( 'woocommerce_checkout_terms_and_conditions' );
                                ?>
                            </div>

                            <?php do_action( 'woocommerce_review_order_after_submit' ); ?>
                        </div>
                    </div>

                </div>

                <!-- Order Summary Sidebar -->
                <div>
                    <div class="checkout-order-summary">
                        <h2 class="text-xl mb-6"><?php esc_html_e( 'Order Summary', 'aakaari-brand' ); ?></h2>

                        <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

                        <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

                        <div id="order_review" class="woocommerce-checkout-review-order">
                            <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                        </div>

                        <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
                    </div>
                </div>

            </div>

        <?php endif; ?>

    </form>
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
