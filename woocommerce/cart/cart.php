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

        <h1 class="text-3xl md:text-4xl font-bold mb-8"><?php esc_html_e('Shopping Cart', 'fashionmen'); ?></h1>

        <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">

            <?php do_action('woocommerce_before_cart_table'); ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
                        $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                        $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                        if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) :
                            $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                    ?>
                        <div class="cart-item bg-white border border-gray-200 rounded-lg p-4">
                            <div class="flex gap-4">
                                <!-- Product Image -->
                                <div class="item-image w-24 h-24 md:w-32 md:h-32 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100">
                                    <?php
                                    $image = wp_get_attachment_image_src(get_post_thumbnail_id($_product->get_id()), 'medium');
                                    if ($image) :
                                        if (!$product_permalink) {
                                            echo '<img src="' . esc_url($image[0]) . '" alt="' . esc_attr($_product->get_name()) . '" class="w-full h-full object-cover">';
                                        } else {
                                            printf('<a href="%s"><img src="%s" alt="%s" class="w-full h-full object-cover"></a>', esc_url($product_permalink), esc_url($image[0]), esc_attr($_product->get_name()));
                                        }
                                    endif;
                                    ?>
                                </div>

                                <!-- Product Info -->
                                <div class="item-info flex-1 min-w-0">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h3 class="font-semibold mb-1">
                                                <?php
                                                if (!$product_permalink) {
                                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key));
                                                } else {
                                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                                }
                                                ?>
                                            </h3>
                                            <?php
                                            // Display variation data
                                            if (!empty($cart_item['variation'])) {
                                                echo '<p class="text-sm text-gray-600">';
                                                foreach ($cart_item['variation'] as $name => $value) {
                                                    echo wc_attribute_label(str_replace('attribute_', '', $name)) . ': ' . $value . ' ';
                                                }
                                                echo '</p>';
                                            }
                                            ?>
                                        </div>

                                        <!-- Remove Button -->
                                        <?php
                                        echo apply_filters(
                                            'woocommerce_cart_item_remove_link',
                                            sprintf(
                                                '<a href="%s" class="remove text-gray-400 hover:text-red-600 transition-colors" aria-label="%s" data-product_id="%s" data-product_sku="%s">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
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

                                    <div class="flex justify-between items-center">
                                        <!-- Quantity -->
                                        <div class="item-quantity flex items-center gap-2">
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
                                                        'classes'      => 'input-text qty text w-16 text-center',
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
                                            <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endif; endforeach; ?>

                    <div class="cart-actions mt-6 flex flex-col sm:flex-row gap-3">
                        <button type="submit" class="button bg-black text-white px-6 py-3 rounded font-semibold hover:bg-gray-900 transition-colors" name="update_cart" value="<?php esc_attr_e('Update cart', 'fashionmen'); ?>">
                            <?php esc_html_e('Update Cart', 'fashionmen'); ?>
                        </button>

                        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="button border border-gray-900 text-gray-900 px-6 py-3 rounded font-semibold hover:bg-gray-900 hover:text-white transition-colors text-center">
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
