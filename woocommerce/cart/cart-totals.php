<?php
/**
 * Cart totals
 *
 * @package FashionMen
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

?>
<div class="cart_totals <?php echo (WC()->customer->has_calculated_shipping()) ? 'calculated_shipping' : ''; ?>">

    <?php do_action('woocommerce_before_cart_totals'); ?>

    <div class="bg-white border border-gray-200 rounded-lg p-6 sticky top-24">
        <h2 class="text-xl font-bold mb-6"><?php esc_html_e('Order Summary', 'fashionmen'); ?></h2>

        <div class="shop_table shop_table_responsive space-y-3">

            <div class="cart-subtotal flex justify-between text-gray-600">
                <span><?php esc_html_e('Subtotal', 'fashionmen'); ?></span>
                <span data-title="<?php esc_attr_e('Subtotal', 'fashionmen'); ?>"><?php wc_cart_totals_subtotal_html(); ?></span>
            </div>

            <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
                <div class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?> flex justify-between text-gray-600">
                    <span><?php wc_cart_totals_coupon_label($coupon); ?></span>
                    <span data-title="<?php echo esc_attr(wc_cart_totals_coupon_label($coupon, false)); ?>"><?php wc_cart_totals_coupon_html($coupon); ?></span>
                </div>
            <?php endforeach; ?>

            <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>

                <?php do_action('woocommerce_cart_totals_before_shipping'); ?>

                <?php wc_cart_totals_shipping_html(); ?>

                <?php do_action('woocommerce_cart_totals_after_shipping'); ?>

            <?php elseif (WC()->cart->needs_shipping() && 'yes' === get_option('woocommerce_enable_shipping_calc')) : ?>

                <div class="shipping flex justify-between text-gray-600">
                    <span><?php esc_html_e('Shipping', 'fashionmen'); ?></span>
                    <span data-title="<?php esc_attr_e('Shipping', 'fashionmen'); ?>">
                        <?php woocommerce_shipping_calculator(); ?>
                    </span>
                </div>

            <?php endif; ?>

            <?php foreach (WC()->cart->get_fees() as $fee) : ?>
                <div class="fee flex justify-between text-gray-600">
                    <span><?php echo esc_html($fee->name); ?></span>
                    <span data-title="<?php echo esc_attr($fee->name); ?>"><?php wc_cart_totals_fee_html($fee); ?></span>
                </div>
            <?php endforeach; ?>

            <?php
            if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) {
                $taxable_address = WC()->customer->get_taxable_address();
                $estimated_text  = '';

                if (WC()->customer->is_customer_outside_base() && !WC()->customer->has_calculated_shipping()) {
                    /* translators: %s location. */
                    $estimated_text = sprintf(' <small>' . esc_html__('(estimated for %s)', 'fashionmen') . '</small>', WC()->countries->estimated_for_prefix($taxable_address[0]) . WC()->countries->countries[$taxable_address[0]]);
                }

                if ('itemized' === get_option('woocommerce_tax_total_display')) {
                    foreach (WC()->cart->get_tax_totals() as $code => $tax) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
                        ?>
                        <div class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?> flex justify-between text-gray-600">
                            <span><?php echo esc_html($tax->label) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                            <span data-title="<?php echo esc_attr($tax->label); ?>"><?php echo wp_kses_post($tax->formatted_amount); ?></span>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="tax-total flex justify-between text-gray-600">
                        <span><?php echo esc_html(WC()->countries->tax_or_vat()) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                        <span data-title="<?php echo esc_attr(WC()->countries->tax_or_vat()); ?>"><?php wc_cart_totals_taxes_total_html(); ?></span>
                    </div>
                    <?php
                }
            }
            ?>

            <?php do_action('woocommerce_cart_totals_before_order_total'); ?>

            <?php
            // Check if free shipping threshold exists
            $packages = WC()->shipping()->get_packages();
            $show_free_shipping_notice = false;
            if (!empty($packages)) {
                foreach ($packages['rates'] ?? [] as $rate_id => $rate) {
                    if ($rate->method_id === 'free_shipping') {
                        $show_free_shipping_notice = true;
                        break;
                    }
                }
            }

            $cart_total = WC()->cart->get_subtotal();
            $free_shipping_min = 100; // You can make this configurable

            if ($cart_total < $free_shipping_min) :
            ?>
                <p class="text-sm text-gray-500">
                    <?php printf(esc_html__('Add $%s more for free shipping!', 'fashionmen'), number_format($free_shipping_min - $cart_total, 2)); ?>
                </p>
            <?php endif; ?>

            <div class="order-total border-t pt-3 mt-3">
                <div class="flex justify-between text-lg font-bold">
                    <span><?php esc_html_e('Total', 'fashionmen'); ?></span>
                    <span data-title="<?php esc_attr_e('Total', 'fashionmen'); ?>"><?php wc_cart_totals_order_total_html(); ?></span>
                </div>
            </div>

        </div>

        <div class="wc-proceed-to-checkout mt-6">
            <?php do_action('woocommerce_proceed_to_checkout'); ?>
        </div>

        <?php do_action('woocommerce_after_cart_totals'); ?>

    </div>

</div>
