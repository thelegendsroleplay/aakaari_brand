<?php
/**
 * Checkout Functions
 *
 * Custom functions for WooCommerce checkout page
 *
 * @package Aakaari_Brand
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue checkout styles and scripts
 */
function aakaari_enqueue_checkout_assets() {
    if ( is_checkout() ) {
        wp_enqueue_style( 'aakaari-checkout', get_template_directory_uri() . '/assets/css/checkout.css', array(), '1.0.0' );
        wp_enqueue_script( 'aakaari-checkout', get_template_directory_uri() . '/assets/js/checkout.js', array( 'jquery' ), '1.0.0', true );

        // Localize script for AJAX
        wp_localize_script( 'aakaari-checkout', 'aakaariCheckout', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'aakaari-checkout-nonce' ),
        ) );
    }
}
add_action( 'wp_enqueue_scripts', 'aakaari_enqueue_checkout_assets' );

/**
 * Customize checkout fields
 */
function aakaari_customize_checkout_fields( $fields ) {
    // Add custom classes to fields
    foreach ( $fields as $fieldset_key => $fieldset ) {
        foreach ( $fieldset as $key => $field ) {
            // Add custom class
            $fields[ $fieldset_key ][ $key ]['class'][] = 'checkout-field';

            // Add custom input class
            $fields[ $fieldset_key ][ $key ]['input_class'][] = 'checkout-input';
        }
    }

    // Customize billing fields
    if ( isset( $fields['billing'] ) ) {
        // Full name priority
        if ( isset( $fields['billing']['billing_first_name'] ) ) {
            $fields['billing']['billing_first_name']['priority'] = 10;
        }
        if ( isset( $fields['billing']['billing_last_name'] ) ) {
            $fields['billing']['billing_last_name']['priority'] = 20;
        }

        // Email and phone
        if ( isset( $fields['billing']['billing_email'] ) ) {
            $fields['billing']['billing_email']['priority'] = 30;
        }
        if ( isset( $fields['billing']['billing_phone'] ) ) {
            $fields['billing']['billing_phone']['priority'] = 40;
        }

        // Address fields
        if ( isset( $fields['billing']['billing_address_1'] ) ) {
            $fields['billing']['billing_address_1']['priority'] = 50;
            $fields['billing']['billing_address_1']['placeholder'] = __( 'Street Address', 'aakaari-brand' );
        }
        if ( isset( $fields['billing']['billing_address_2'] ) ) {
            $fields['billing']['billing_address_2']['priority'] = 60;
        }

        // City, State, Postcode
        if ( isset( $fields['billing']['billing_city'] ) ) {
            $fields['billing']['billing_city']['priority'] = 70;
        }
        if ( isset( $fields['billing']['billing_state'] ) ) {
            $fields['billing']['billing_state']['priority'] = 80;
        }
        if ( isset( $fields['billing']['billing_postcode'] ) ) {
            $fields['billing']['billing_postcode']['priority'] = 90;
        }
        if ( isset( $fields['billing']['billing_country'] ) ) {
            $fields['billing']['billing_country']['priority'] = 100;
        }
    }

    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'aakaari_customize_checkout_fields' );

/**
 * Custom order review table
 */
function aakaari_custom_order_review() {
    $cart = WC()->cart;
    ?>
    <div class="checkout-order-items space-y-3 mb-6 max-h-60 overflow-y-auto">
        <?php
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                ?>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">
                        <?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ); ?>
                        <strong class="product-quantity">&times;&nbsp;<?php echo esc_html( $cart_item['quantity'] ); ?></strong>
                    </span>
                    <span class="product-total">
                        <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
                    </span>
                </div>
                <?php
            }
        }
        ?>
    </div>

    <div class="checkout-totals space-y-3 border-t pt-4">
        <div class="flex justify-between text-gray-600">
            <span><?php esc_html_e( 'Subtotal', 'aakaari-brand' ); ?></span>
            <span><?php wc_cart_totals_subtotal_html(); ?></span>
        </div>

        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
            <div class="flex justify-between text-gray-600">
                <span>
                    <?php wc_cart_totals_coupon_label( $coupon ); ?>
                </span>
                <span>
                    <?php wc_cart_totals_coupon_html( $coupon ); ?>
                </span>
            </div>
        <?php endforeach; ?>

        <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
            <div class="flex justify-between text-gray-600">
                <span><?php esc_html_e( 'Shipping', 'aakaari-brand' ); ?></span>
                <span>
                    <?php
                    $shipping_total = WC()->cart->get_shipping_total();
                    if ( $shipping_total == 0 ) {
                        echo '<span class="text-green-600 font-medium">' . esc_html__( 'FREE', 'aakaari-brand' ) . '</span>';
                    } else {
                        wc_cart_totals_shipping_html();
                    }
                    ?>
                </span>
            </div>
        <?php endif; ?>

        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
            <div class="flex justify-between text-gray-600">
                <span><?php echo esc_html( $fee->name ); ?></span>
                <span><?php wc_cart_totals_fee_html( $fee ); ?></span>
            </div>
        <?php endforeach; ?>

        <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
            <?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
                <?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
                    <div class="flex justify-between text-gray-600">
                        <span><?php echo esc_html( $tax->label ); ?></span>
                        <span><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="flex justify-between text-gray-600">
                    <span><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
                    <span><?php wc_cart_totals_taxes_total_html(); ?></span>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="border-t pt-3">
            <div class="flex justify-between text-lg font-medium">
                <span><?php esc_html_e( 'Total', 'aakaari-brand' ); ?></span>
                <span><?php wc_cart_totals_order_total_html(); ?></span>
            </div>
        </div>
    </div>

    <!-- Place Order Button -->
    <div class="mt-6">
        <?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="btn btn-primary w-full btn-lg" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $checkout->get_value( 'order_button_text' ) ) . '" data-value="' . esc_attr( $checkout->get_value( 'order_button_text' ) ) . '">' . esc_html( $checkout->get_value( 'order_button_text' ) ) . '</button>' ); ?>
    </div>
    <?php
}
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
add_action( 'woocommerce_checkout_order_review', 'aakaari_custom_order_review', 10 );

/**
 * Customize payment method output
 */
function aakaari_custom_payment_method_html( $html, $gateway ) {
    ob_start();
    ?>
    <div class="checkout-payment-method <?php echo esc_attr( $gateway->id ); ?>" data-gateway-id="<?php echo esc_attr( $gateway->id ); ?>">
        <label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>" class="payment-method-label">
            <input
                id="payment_method_<?php echo esc_attr( $gateway->id ); ?>"
                type="radio"
                class="payment-method-radio"
                name="payment_method"
                value="<?php echo esc_attr( $gateway->id ); ?>"
                <?php checked( $gateway->chosen, true ); ?>
                data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>"
            />
            <span class="payment-method-title"><?php echo wp_kses_post( $gateway->get_title() ); ?></span>
            <?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
                <span class="payment-method-icon">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            <?php endif; ?>
        </label>

        <?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
            <div class="payment-method-details" <?php echo $gateway->chosen ? '' : 'style="display:none;"'; ?>>
                <?php $gateway->payment_fields(); ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Customize shipping method output
 */
function aakaari_custom_shipping_methods() {
    $packages = WC()->shipping()->get_packages();

    foreach ( $packages as $i => $package ) {
        $available_methods = $package['rates'];
        $chosen_method = isset( WC()->session->chosen_shipping_methods[ $i ] ) ? WC()->session->chosen_shipping_methods[ $i ] : '';

        if ( count( $available_methods ) > 0 ) {
            echo '<div class="shipping-methods-wrapper mb-4">';

            foreach ( $available_methods as $method ) {
                $is_selected = ( $chosen_method === $method->id );
                ?>
                <div class="checkout-shipping-method <?php echo $is_selected ? 'selected' : ''; ?>" data-method-id="<?php echo esc_attr( $method->id ); ?>">
                    <label for="shipping_method_<?php echo esc_attr( $method->id ); ?>" class="shipping-method-label">
                        <input
                            type="radio"
                            name="shipping_method[<?php echo esc_attr( $i ); ?>]"
                            id="shipping_method_<?php echo esc_attr( $method->id ); ?>"
                            value="<?php echo esc_attr( $method->id ); ?>"
                            <?php checked( $is_selected ); ?>
                            class="shipping-method-radio"
                        />
                        <span class="shipping-method-title"><?php echo wp_kses_post( $method->get_label() ); ?></span>
                        <span class="shipping-method-cost"><?php echo wc_price( $method->cost ); ?></span>
                    </label>
                </div>
                <?php
            }

            echo '</div>';
        }
    }
}

/**
 * Add checkout steps indicator
 */
function aakaari_checkout_steps() {
    ?>
    <div class="checkout-steps">
        <div class="checkout-step active">
            <div class="checkout-step-circle">1</div>
            <span class="checkout-step-label"><?php esc_html_e( 'Information', 'aakaari-brand' ); ?></span>
        </div>
        <div class="checkout-step">
            <div class="checkout-step-circle">2</div>
            <span class="checkout-step-label"><?php esc_html_e( 'Shipping', 'aakaari-brand' ); ?></span>
        </div>
        <div class="checkout-step">
            <div class="checkout-step-circle">3</div>
            <span class="checkout-step-label"><?php esc_html_e( 'Payment', 'aakaari-brand' ); ?></span>
        </div>
    </div>
    <?php
}
add_action( 'woocommerce_before_checkout_form', 'aakaari_checkout_steps', 5 );

/**
 * Customize order button text
 */
function aakaari_custom_order_button_text( $text ) {
    return __( 'Place Order', 'aakaari-brand' );
}
add_filter( 'woocommerce_order_button_text', 'aakaari_custom_order_button_text' );

/**
 * Add custom validation messages
 */
function aakaari_checkout_field_validation_messages() {
    ?>
    <script type="text/javascript">
        var aakaariValidationMessages = {
            required: '<?php esc_html_e( 'This field is required.', 'aakaari-brand' ); ?>',
            email: '<?php esc_html_e( 'Please enter a valid email address.', 'aakaari-brand' ); ?>',
            phone: '<?php esc_html_e( 'Please enter a valid phone number.', 'aakaari-brand' ); ?>',
            postcode: '<?php esc_html_e( 'Please enter a valid postcode.', 'aakaari-brand' ); ?>'
        };
    </script>
    <?php
}
add_action( 'wp_footer', 'aakaari_checkout_field_validation_messages' );

/**
 * Modify checkout field wrapper
 */
function aakaari_checkout_field_wrapper( $field, $key, $args, $value ) {
    // Add custom wrapper classes for grid layout
    if ( in_array( $key, array( 'billing_first_name', 'billing_last_name', 'billing_city', 'billing_state', 'billing_postcode', 'billing_country' ) ) ) {
        $field = str_replace( 'form-row', 'form-row checkout-field-half', $field );
    }

    return $field;
}
add_filter( 'woocommerce_form_field', 'aakaari_checkout_field_wrapper', 10, 4 );

/**
 * Add coupon form to checkout
 */
function aakaari_checkout_coupon_form() {
    if ( ! wc_coupons_enabled() ) {
        return;
    }
    ?>
    <div class="checkout-coupon-wrapper mb-6">
        <p class="mb-2 text-sm"><?php esc_html_e( 'Have a coupon?', 'aakaari-brand' ); ?></p>
        <form class="checkout-coupon" method="post">
            <div class="flex gap-2">
                <input type="text" name="coupon_code" class="input-field" placeholder="<?php esc_attr_e( 'Coupon code', 'aakaari-brand' ); ?>" id="coupon_code" value="" />
                <button type="submit" class="btn btn-outline" name="apply_coupon" value="<?php esc_attr_e( 'Apply', 'aakaari-brand' ); ?>"><?php esc_html_e( 'Apply', 'aakaari-brand' ); ?></button>
            </div>
        </form>
    </div>
    <?php
}
add_action( 'woocommerce_before_checkout_form', 'aakaari_checkout_coupon_form', 10 );
