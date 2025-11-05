<?php
/**
 * Review order table
 *
 * @package FashionMen
 * @since 1.0.0
 */

defined('ABSPATH') || exit;
?>

<table class="shop_table woocommerce-checkout-review-order-table w-full">
    <tbody>
        <tr>
            <td colspan="2" class="p-0">
                <div class="space-y-3 mb-6 max-h-60 overflow-y-auto border-b pb-4">
                    <?php
                    do_action('woocommerce_review_order_before_cart_contents');

                    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                        $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

                        if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
                            ?>
                            <div class="flex justify-between text-sm <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                                <span class="text-gray-600">
                                    <?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key)) . '&nbsp;'; ?>
                                    <?php echo apply_filters('woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf('&times;&nbsp;%s', $cart_item['quantity']) . '</strong>', $cart_item, $cart_item_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                    <?php echo wc_get_formatted_cart_item_data($cart_item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </span>
                                <span>
                                    <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </span>
                            </div>
                            <?php
                        }
                    }

                    do_action('woocommerce_review_order_after_cart_contents');
                    ?>
                </div>
            </td>
        </tr>
    </tbody>
    <tfoot class="space-y-3">

        <tr class="cart-subtotal">
            <td colspan="2" class="pb-3">
                <div class="flex justify-between text-gray-600">
                    <span><?php esc_html_e('Subtotal', 'fashionmen'); ?></span>
                    <span><?php wc_cart_totals_subtotal_html(); ?></span>
                </div>
            </td>
        </tr>

        <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
            <tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
                <td colspan="2" class="pb-3">
                    <div class="flex justify-between text-gray-600">
                        <span><?php wc_cart_totals_coupon_label($coupon); ?></span>
                        <span><?php wc_cart_totals_coupon_html($coupon); ?></span>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>

        <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>

            <?php do_action('woocommerce_review_order_before_shipping'); ?>

            <?php wc_cart_totals_shipping_html(); ?>

            <?php do_action('woocommerce_review_order_after_shipping'); ?>

        <?php endif; ?>

        <?php foreach (WC()->cart->get_fees() as $fee) : ?>
            <tr class="fee">
                <td colspan="2" class="pb-3">
                    <div class="flex justify-between text-gray-600">
                        <span><?php echo esc_html($fee->name); ?></span>
                        <span><?php wc_cart_totals_fee_html($fee); ?></span>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>

        <?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
            <?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
                <?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
                    <tr class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
                        <td colspan="2" class="pb-3">
                            <div class="flex justify-between text-gray-600">
                                <span><?php echo esc_html($tax->label); ?></span>
                                <span><?php echo wp_kses_post($tax->formatted_amount); ?></span>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr class="tax-total">
                    <td colspan="2" class="pb-3">
                        <div class="flex justify-between text-gray-600">
                            <span><?php echo esc_html(WC()->countries->tax_or_vat()); ?></span>
                            <span><?php wc_cart_totals_taxes_total_html(); ?></span>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endif; ?>

        <?php do_action('woocommerce_review_order_before_order_total'); ?>

        <tr class="order-total">
            <td colspan="2" class="border-t pt-3">
                <div class="flex justify-between text-lg font-bold">
                    <span><?php esc_html_e('Total', 'fashionmen'); ?></span>
                    <span><?php wc_cart_totals_order_total_html(); ?></span>
                </div>
            </td>
        </tr>

        <?php do_action('woocommerce_review_order_after_order_total'); ?>

    </tfoot>
</table>
