<?php
/**
 * Checkout Form
 *
 * @package FashionMen
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'fashionmen')));
    return;
}

?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl md:text-4xl font-bold mb-8"><?php esc_html_e('Checkout', 'fashionmen'); ?></h1>

    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

        <?php if ($checkout->get_checkout_fields()) : ?>

            <?php do_action('woocommerce_checkout_before_customer_details'); ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Checkout Form -->
                <div class="lg:col-span-2 space-y-6" id="customer_details">

                    <!-- Billing Fields -->
                    <div class="woocommerce-billing-fields bg-white border border-gray-200 rounded-lg p-6">
                        <?php if (wc_ship_to_billing_address_only() && WC()->cart->needs_shipping()) : ?>
                            <h2 class="text-xl font-bold mb-6"><?php esc_html_e('Billing & Shipping', 'fashionmen'); ?></h2>
                        <?php else : ?>
                            <h2 class="text-xl font-bold mb-6"><?php esc_html_e('Shipping Information', 'fashionmen'); ?></h2>
                        <?php endif; ?>

                        <?php do_action('woocommerce_before_checkout_billing_form', $checkout); ?>

                        <div class="woocommerce-billing-fields__field-wrapper grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php
                            $fields = $checkout->get_checkout_fields('billing');

                            foreach ($fields as $key => $field) {
                                // Make full-width fields span 2 columns
                                $col_span = in_array($key, ['billing_address_1', 'billing_address_2', 'billing_company']) ? 'md:col-span-2' : '';
                                echo '<div class="' . $col_span . '">';
                                woocommerce_form_field($key, $field, $checkout->get_value($key));
                                echo '</div>';
                            }
                            ?>
                        </div>

                        <?php do_action('woocommerce_after_checkout_billing_form', $checkout); ?>
                    </div>

                    <?php if (!wc_ship_to_billing_address_only() && WC()->cart->needs_shipping()) : ?>
                        <!-- Shipping Fields -->
                        <div class="woocommerce-shipping-fields bg-white border border-gray-200 rounded-lg p-6">
                            <?php do_action('woocommerce_before_checkout_shipping_form', $checkout); ?>

                            <div class="woocommerce-shipping-fields__field-wrapper grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php
                                $fields = $checkout->get_checkout_fields('shipping');

                                foreach ($fields as $key => $field) {
                                    $col_span = in_array($key, ['shipping_address_1', 'shipping_address_2', 'shipping_company']) ? 'md:col-span-2' : '';
                                    echo '<div class="' . $col_span . '">';
                                    woocommerce_form_field($key, $field, $checkout->get_value($key));
                                    echo '</div>';
                                }
                                ?>
                            </div>

                            <?php do_action('woocommerce_after_checkout_shipping_form', $checkout); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Additional Fields -->
                    <?php do_action('woocommerce_before_order_notes', $checkout); ?>

                    <?php if (apply_filters('woocommerce_enable_order_notes_field', 'yes' === get_option('woocommerce_enable_order_comments', 'yes'))) : ?>
                        <?php if (!WC()->cart->needs_shipping() || wc_ship_to_billing_address_only()) : ?>
                            <div class="woocommerce-additional-fields bg-white border border-gray-200 rounded-lg p-6">
                        <?php endif; ?>

                        <h2 class="text-xl font-bold mb-6"><?php esc_html_e('Additional information', 'fashionmen'); ?></h2>

                        <div class="woocommerce-additional-fields__field-wrapper">
                            <?php foreach ($checkout->get_checkout_fields('order') as $key => $field) : ?>
                                <?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
                            <?php endforeach; ?>
                        </div>

                        <?php if (!WC()->cart->needs_shipping() || wc_ship_to_billing_address_only()) : ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php do_action('woocommerce_after_order_notes', $checkout); ?>

                    <!-- Payment Methods -->
                    <div class="woocommerce-checkout-payment bg-white border border-gray-200 rounded-lg p-6">
                        <h2 class="text-xl font-bold mb-6"><?php esc_html_e('Payment Method', 'fashionmen'); ?></h2>
                        <?php woocommerce_checkout_payment(); ?>
                    </div>

                </div>

                <?php do_action('woocommerce_checkout_after_customer_details'); ?>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white border border-gray-200 rounded-lg p-6 sticky top-24">
                        <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

                        <h2 class="text-xl font-bold mb-6" id="order_review_heading"><?php esc_html_e('Order Summary', 'fashionmen'); ?></h2>

                        <?php do_action('woocommerce_checkout_before_order_review'); ?>

                        <div id="order_review" class="woocommerce-checkout-review-order">
                            <?php do_action('woocommerce_checkout_order_review'); ?>
                        </div>

                        <?php do_action('woocommerce_checkout_after_order_review'); ?>
                    </div>
                </div>

            </div>

        <?php endif; ?>

    </form>
</div>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
