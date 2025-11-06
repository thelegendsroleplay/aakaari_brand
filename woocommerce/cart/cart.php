<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * @package Aakaari_Brand
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<div class="cart-container container mx-auto px-4 py-8">
    <h1 class="text-3xl md:text-4xl mb-8"><?php esc_html_e( 'Shopping Cart', 'aakaari-brand' ); ?></h1>

    <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
        <?php do_action( 'woocommerce_before_cart_table' ); ?>

        <?php if ( WC()->cart->is_empty() ) : ?>
            <!-- Empty Cart State -->
            <div class="cart-empty-state">
                <div class="max-w-md mx-auto bg-white border border-gray-200 rounded-lg p-8 text-center">
                    <h2 class="text-2xl mb-4"><?php esc_html_e( 'Your cart is empty', 'aakaari-brand' ); ?></h2>
                    <p class="text-gray-600 mb-6"><?php esc_html_e( 'Add some items to get started!', 'aakaari-brand' ); ?></p>
                    <a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="btn btn-primary">
                        <?php esc_html_e( 'Continue Shopping', 'aakaari-brand' ); ?>
                    </a>
                </div>
            </div>

        <?php else : ?>
            <!-- Cart with Items -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                        $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
                            $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                            ?>

                            <div class="cart-item-card bg-white border border-gray-200 rounded-lg p-4" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
                                <div class="flex gap-4">
                                    <!-- Product Image -->
                                    <div class="cart-item-image-wrapper w-24 h-24 md:w-32 md:h-32 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100">
                                        <?php
                                        $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                                        if ( ! $product_permalink ) {
                                            echo $thumbnail;
                                        } else {
                                            printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
                                        }
                                        ?>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <h3 class="mb-1">
                                                    <?php
                                                    if ( ! $product_permalink ) {
                                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) );
                                                    } else {
                                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                                    }
                                                    ?>
                                                </h3>

                                                <!-- Product Variations/Meta -->
                                                <?php if ( ! empty( $cart_item['variation'] ) ) : ?>
                                                    <p class="text-sm text-gray-600">
                                                        <?php
                                                        $variation_data = array();
                                                        foreach ( $cart_item['variation'] as $name => $value ) {
                                                            $taxonomy = wc_attribute_taxonomy_name( str_replace( 'attribute_pa_', '', urldecode( $name ) ) );

                                                            if ( taxonomy_exists( $taxonomy ) ) {
                                                                $term = get_term_by( 'slug', $value, $taxonomy );
                                                                if ( ! is_wp_error( $term ) && $term && $term->name ) {
                                                                    $value = $term->name;
                                                                }
                                                                $label = wc_attribute_label( $taxonomy );
                                                            } else {
                                                                $label = wc_attribute_label( str_replace( 'attribute_', '', $name ), $_product );
                                                            }

                                                            $variation_data[] = esc_html( $label ) . ': ' . esc_html( $value );
                                                        }
                                                        echo implode( ' | ', $variation_data );
                                                        ?>
                                                    </p>
                                                <?php endif; ?>

                                                <?php
                                                // Output item meta data
                                                echo wc_get_formatted_cart_item_data( $cart_item );
                                                ?>
                                            </div>

                                            <!-- Remove Button -->
                                            <?php
                                            echo apply_filters(
                                                'woocommerce_cart_item_remove_link',
                                                sprintf(
                                                    '<a href="%s" class="remove-cart-item btn-icon" aria-label="%s" data-product_id="%s" data-product_sku="%s"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></a>',
                                                    esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                                    esc_attr__( 'Remove this item', 'aakaari-brand' ),
                                                    esc_attr( $product_id ),
                                                    esc_attr( $_product->get_sku() )
                                                ),
                                                $cart_item_key
                                            );
                                            ?>
                                        </div>

                                        <!-- Quantity and Price -->
                                        <div class="flex justify-between items-center">
                                            <!-- Quantity Selector -->
                                            <div class="cart-quantity-selector">
                                                <?php
                                                if ( $_product->is_sold_individually() ) {
                                                    $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
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
                                                echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
                                                ?>
                                            </div>

                                            <!-- Item Total -->
                                            <span class="text-lg font-medium">
                                                <?php
                                                echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php do_action( 'woocommerce_cart_contents' ); ?>
                </div>

                <!-- Order Summary -->
                <div>
                    <div class="cart-summary-card">
                        <h2 class="text-xl mb-6"><?php esc_html_e( 'Order Summary', 'aakaari-brand' ); ?></h2>

                        <?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

                        <div class="cart-collaterals">
                            <?php
                            /**
                             * Cart collaterals hook.
                             *
                             * @hooked woocommerce_cross_sell_display
                             * @hooked woocommerce_cart_totals - 10
                             */
                            do_action( 'woocommerce_cart_collaterals' );
                            ?>
                        </div>

                        <!-- Coupon Code -->
                        <?php if ( wc_coupons_enabled() ) : ?>
                            <div class="coupon-section mt-4">
                                <label for="coupon_code" class="block text-sm mb-2"><?php esc_html_e( 'Coupon code', 'aakaari-brand' ); ?></label>
                                <div class="flex gap-2">
                                    <input type="text" name="coupon_code" class="input-field" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'aakaari-brand' ); ?>" />
                                    <button type="submit" class="btn btn-outline" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'aakaari-brand' ); ?>"><?php esc_html_e( 'Apply', 'aakaari-brand' ); ?></button>
                                </div>
                                <?php do_action( 'woocommerce_cart_coupon' ); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Action Buttons -->
                        <div class="mt-6 space-y-3">
                            <button type="submit" class="btn btn-primary w-full" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'aakaari-brand' ); ?>"><?php esc_html_e( 'Update cart', 'aakaari-brand' ); ?></button>

                            <?php do_action( 'woocommerce_proceed_to_checkout' ); ?>

                            <a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="btn btn-outline w-full block text-center">
                                <?php esc_html_e( 'Continue Shopping', 'aakaari-brand' ); ?>
                            </a>
                        </div>

                        <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php do_action( 'woocommerce_after_cart_table' ); ?>
    </form>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
