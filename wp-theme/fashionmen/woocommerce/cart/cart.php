<?php
/**
 * Cart Page
 *
 * @package FashionMen
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart');
?>

<div class="cart-page">
    <div class="container mx-auto px-4 py-8">

        <h1 class="text-3xl font-bold mb-8"><?php esc_html_e('Shopping Cart', 'fashionmen'); ?></h1>

        <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">

            <?php do_action('woocommerce_before_cart_table'); ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="cart-items bg-white border border-gray-200 rounded-lg overflow-hidden">

                        <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
                            $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                            $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                            if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) :
                                $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                        ?>
                            <div class="cart-item flex gap-4 p-4 border-b border-gray-200 last:border-b-0">
                                <!-- Product Image -->
                                <div class="item-image w-24 h-24 flex-shrink-0">
                                    <?php
                                    $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

                                    if (!$product_permalink) {
                                        echo $thumbnail;
                                    } else {
                                        printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail);
                                    }
                                    ?>
                                </div>

                                <!-- Product Info -->
                                <div class="item-info flex-1">
                                    <h3 class="text-lg font-semibold mb-2">
                                        <?php
                                        if (!$product_permalink) {
                                            echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
                                        } else {
                                            echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                        }
                                        ?>
                                    </h3>

                                    <!-- Quantity -->
                                    <div class="item-quantity flex items-center gap-2 mb-2">
                                        <?php
                                        if ($_product->is_sold_individually()) {
                                            $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
                                        } else {
                                            $product_quantity = woocommerce_quantity_input(
                                                array(
                                                    'input_name'   => "cart[{$cart_item_key}][qty]",
                                                    'input_value'  => $cart_item['quantity'],
                                                    'max_value'    => $_product->get_max_purchase_quantity(),
                                                    'min_value'    => '0',
                                                    'product_name' => $_product->get_name(),
                                                ),
                                                $_product,
                                                false
                                            );
                                        }

                                        echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item);
                                        ?>
                                    </div>

                                    <!-- Price -->
                                    <div class="item-price text-lg font-semibold">
                                        <?php echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); ?>
                                    </div>
                                </div>

                                <!-- Remove Button -->
                                <div class="item-remove">
                                    <?php
                                    echo apply_filters(
                                        'woocommerce_cart_item_remove_link',
                                        sprintf(
                                            '<a href="%s" class="remove p-2 hover:text-red-600 transition-colors" aria-label="%s" data-product_id="%s" data-product_sku="%s">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </a>',
                                            esc_url(wc_get_cart_remove_url($cart_item_key)),
                                            esc_html__('Remove this item', 'fashionmen'),
                                            esc_attr($product_id),
                                            esc_attr($_product->get_sku())
                                        ),
                                        $cart_item_key
                                    );
                                    ?>
                                </div>
                            </div>

                        <?php endif; endforeach; ?>

                    </div>

                    <div class="cart-actions mt-4 flex justify-between">
                        <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e('Update cart', 'fashionmen'); ?>">
                            <?php esc_html_e('Update cart', 'fashionmen'); ?>
                        </button>

                        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="button-outline">
                            <?php esc_html_e('Continue Shopping', 'fashionmen'); ?>
                        </a>
                    </div>
                </div>

                <!-- Cart Totals -->
                <div class="lg:col-span-1">
                    <?php do_action('woocommerce_before_cart_collaterals'); ?>

                    <div class="cart-collaterals">
                        <?php
                        /**
                         * Cart collaterals hook.
                         */
                        do_action('woocommerce_cart_collaterals');
                        ?>
                    </div>
                </div>

            </div>

            <?php do_action('woocommerce_after_cart_table'); ?>
        </form>

        <?php do_action('woocommerce_after_cart'); ?>

    </div>
</div>
