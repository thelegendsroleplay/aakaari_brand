<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * @package Aakaari Brand
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart'); ?>

<div class="cart-page">
    <div class="cart-container">
        <?php if (WC()->cart->is_empty()) : ?>
            <!-- Empty Cart State -->
            <div class="empty-cart">
                <svg class="empty-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
                <h2>Your cart is empty</h2>
                <p>Add some items to get started</p>
                <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="button button-primary button-lg">
                    Continue Shopping
                </a>
            </div>

            <?php do_action('woocommerce_cart_is_empty'); ?>

        <?php else : ?>
            <!-- Cart Header -->
            <div class="cart-header">
                <h1>Shopping Cart</h1>
                <p><?php echo WC()->cart->get_cart_contents_count(); ?> <?php echo WC()->cart->get_cart_contents_count() === 1 ? 'item' : 'items'; ?></p>
            </div>

            <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
                <?php do_action('woocommerce_before_cart_table'); ?>

                <div class="cart-grid">
                    <!-- Cart Items -->
                    <div class="cart-items">
                        <?php
                        do_action('woocommerce_before_cart_contents');

                        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                            $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                            if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                                $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                                ?>
                                <div class="cart-item" data-key="<?php echo esc_attr($cart_item_key); ?>">
                                    <!-- Product Image -->
                                    <div class="item-image">
                                        <?php
                                        $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

                                        if (!$product_permalink) {
                                            echo $thumbnail;
                                        } else {
                                            printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail);
                                        }
                                        ?>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="item-details">
                                        <h3>
                                            <?php
                                            if (!$product_permalink) {
                                                echo wp_kses_post($_product->get_name());
                                            } else {
                                                echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                            }
                                            ?>
                                        </h3>

                                        <?php
                                        // Get variation attributes (size, color, etc.)
                                        if (!empty($cart_item['variation'])) {
                                            $variation_data = [];
                                            foreach ($cart_item['variation'] as $name => $value) {
                                                $taxonomy = str_replace('attribute_', '', $name);
                                                $term = get_term_by('slug', $value, $taxonomy);
                                                $label = wc_attribute_label($taxonomy);
                                                $variation_data[] = $label . ': ' . ($term ? $term->name : $value);
                                            }
                                            if (!empty($variation_data)) {
                                                echo '<p class="item-meta">' . implode(' | ', $variation_data) . '</p>';
                                            }
                                        }

                                        // Show discount if on sale
                                        if ($_product->is_on_sale()) {
                                            $regular_price = (float) $_product->get_regular_price();
                                            $sale_price = (float) $_product->get_sale_price();
                                            $savings = ($regular_price - $sale_price) * $cart_item['quantity'];
                                            if ($savings > 0) {
                                                echo '<p class="item-discount">Save ' . wc_price($savings) . '</p>';
                                            }
                                        }
                                        ?>
                                    </div>

                                    <!-- Quantity Controls -->
                                    <div class="item-quantity">
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

                                    <!-- Item Price -->
                                    <div class="item-price">
                                        <span class="price-label">Price:</span>
                                        <span class="price-value">
                                            <?php echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); ?>
                                        </span>
                                    </div>

                                    <!-- Item Total -->
                                    <div class="item-total">
                                        <span class="total-label">Total:</span>
                                        <span class="total-value">
                                            <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
                                        </span>
                                    </div>

                                    <!-- Remove Button -->
                                    <button type="button" class="item-remove" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>" title="Remove this item">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </button>
                                </div>
                                <?php
                            }
                        }

                        do_action('woocommerce_cart_contents');
                        ?>

                        <!-- Cart Actions -->
                        <div class="cart-actions">
                            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="button button-outline">
                                Continue Shopping
                            </a>
                            <button type="button" class="button button-destructive clear-cart-btn">
                                Clear Cart
                            </button>
                        </div>

                        <?php do_action('woocommerce_cart_actions'); ?>

                        <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                    </div>

                    <!-- Cart Summary -->
                    <div class="cart-summary">
                        <?php do_action('woocommerce_before_cart_collaterals'); ?>

                        <div class="cart-collaterals">
                            <?php
                            /**
                             * Cart collaterals hook.
                             *
                             * @hooked woocommerce_cross_sell_display
                             * @hooked woocommerce_cart_totals - 10
                             */
                            do_action('woocommerce_cart_collaterals');
                            ?>

                            <h2>Order Summary</h2>

                            <div class="summary-rows">
                                <!-- Subtotal -->
                                <div class="summary-row">
                                    <span>Subtotal</span>
                                    <span><?php wc_cart_totals_subtotal_html(); ?></span>
                                </div>

                                <?php
                                // Calculate free shipping threshold
                                $subtotal = WC()->cart->get_subtotal();
                                $free_shipping_threshold = 100; // $100 for free shipping
                                $shipping_cost = 10; // $10 shipping fee
                                ?>

                                <!-- Shipping -->
                                <div class="summary-row">
                                    <span>Shipping</span>
                                    <span>
                                        <?php if ($subtotal >= $free_shipping_threshold) : ?>
                                            FREE
                                        <?php else : ?>
                                            <?php echo wc_price($shipping_cost); ?>
                                        <?php endif; ?>
                                    </span>
                                </div>

                                <!-- Free Shipping Messages -->
                                <?php if ($subtotal >= $free_shipping_threshold) : ?>
                                    <p class="free-shipping-note">🎉 You got free shipping!</p>
                                <?php else : ?>
                                    <?php $remaining = $free_shipping_threshold - $subtotal; ?>
                                    <p class="free-shipping-progress">
                                        Add <?php echo wc_price($remaining); ?> more for free shipping
                                    </p>
                                <?php endif; ?>

                                <!-- Tax -->
                                <?php
                                $tax_rate = 0.08; // 8% tax
                                $tax_amount = $subtotal * $tax_rate;
                                ?>
                                <div class="summary-row">
                                    <span>Tax (8%)</span>
                                    <span><?php echo wc_price($tax_amount); ?></span>
                                </div>
                            </div>

                            <div class="summary-separator"></div>

                            <!-- Total -->
                            <div class="summary-total">
                                <span>Total</span>
                                <span>
                                    <?php
                                    $shipping = $subtotal >= $free_shipping_threshold ? 0 : $shipping_cost;
                                    $total = $subtotal + $shipping + $tax_amount;
                                    echo wc_price($total);
                                    ?>
                                </span>
                            </div>

                            <!-- Checkout Button -->
                            <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="button button-primary button-lg checkout-btn">
                                Proceed to Checkout
                            </a>

                            <!-- Payment Methods -->
                            <div class="payment-methods">
                                <p>We accept:</p>
                                <div class="payment-icons">
                                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='25' viewBox='0 0 40 25'%3E%3Crect fill='%23666' width='40' height='25' rx='3'/%3E%3Ctext x='50%25' y='50%25' fill='%23fff' font-size='10' font-family='Arial' text-anchor='middle' dominant-baseline='middle'%3EVISA%3C/text%3E%3C/svg%3E" alt="Visa" />
                                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='25' viewBox='0 0 40 25'%3E%3Crect fill='%23666' width='40' height='25' rx='3'/%3E%3Ctext x='50%25' y='50%25' fill='%23fff' font-size='10' font-family='Arial' text-anchor='middle' dominant-baseline='middle'%3EMC%3C/text%3E%3C/svg%3E" alt="Mastercard" />
                                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='25' viewBox='0 0 40 25'%3E%3Crect fill='%23666' width='40' height='25' rx='3'/%3E%3Ctext x='50%25' y='50%25' fill='%23fff' font-size='8' font-family='Arial' text-anchor='middle' dominant-baseline='middle'%3EAMEX%3C/text%3E%3C/svg%3E" alt="Amex" />
                                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='25' viewBox='0 0 40 25'%3E%3Crect fill='%23666' width='40' height='25' rx='3'/%3E%3Ctext x='50%25' y='50%25' fill='%23fff' font-size='10' font-family='Arial' text-anchor='middle' dominant-baseline='middle'%3EPP%3C/text%3E%3C/svg%3E" alt="PayPal" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php do_action('woocommerce_after_cart_table'); ?>
            </form>

            <?php do_action('woocommerce_after_cart'); ?>

        <?php endif; ?>
    </div>
</div>
