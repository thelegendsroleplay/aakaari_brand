<?php
/**
 * Cart Functions
 *
 * Custom functions for WooCommerce cart page
 *
 * @package Aakaari_Brand
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue cart styles and scripts
 */
function aakaari_enqueue_cart_assets() {
    if ( is_cart() ) {
        wp_enqueue_style( 'aakaari-cart', get_template_directory_uri() . '/assets/css/cart.css', array(), '1.0.0' );
        wp_enqueue_script( 'aakaari-cart', get_template_directory_uri() . '/assets/js/cart.js', array( 'jquery' ), '1.0.0', true );

        // Localize script for AJAX
        wp_localize_script( 'aakaari-cart', 'aakaariCart', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'aakaari-cart-nonce' ),
        ) );
    }
}
add_action( 'wp_enqueue_scripts', 'aakaari_enqueue_cart_assets' );

/**
 * Customize cart totals output
 */
function aakaari_custom_cart_totals() {
    $cart = WC()->cart;
    $subtotal = $cart->get_subtotal();
    $shipping_total = $cart->get_shipping_total();
    $total = $cart->get_total( 'edit' );
    $free_shipping_threshold = 100;
    ?>
    <div class="space-y-3 mb-6">
        <div class="flex justify-between text-gray-600">
            <span><?php esc_html_e( 'Subtotal', 'aakaari-brand' ); ?></span>
            <span><?php echo wc_price( $subtotal ); ?></span>
        </div>
        <div class="flex justify-between text-gray-600">
            <span><?php esc_html_e( 'Shipping', 'aakaari-brand' ); ?></span>
            <span>
                <?php
                if ( $shipping_total == 0 ) {
                    echo '<span class="text-green-600 font-medium">' . esc_html__( 'FREE', 'aakaari-brand' ) . '</span>';
                } else {
                    echo wc_price( $shipping_total );
                }
                ?>
            </span>
        </div>

        <?php if ( $subtotal < $free_shipping_threshold && $shipping_total > 0 ) : ?>
            <p class="text-sm text-gray-500">
                <?php
                printf(
                    esc_html__( 'Add %s more for free shipping!', 'aakaari-brand' ),
                    wc_price( $free_shipping_threshold - $subtotal )
                );
                ?>
            </p>
        <?php endif; ?>

        <div class="border-t pt-3">
            <div class="flex justify-between text-lg font-medium">
                <span><?php esc_html_e( 'Total', 'aakaari-brand' ); ?></span>
                <span><?php echo wc_price( $total ); ?></span>
            </div>
        </div>
    </div>

    <?php
}

/**
 * Remove default cart totals and add custom
 */
function aakaari_override_cart_totals() {
    remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );
    add_action( 'woocommerce_cart_collaterals', 'aakaari_custom_cart_totals', 10 );
}
add_action( 'init', 'aakaari_override_cart_totals' );

/**
 * Custom proceed to checkout button
 */
function aakaari_custom_proceed_to_checkout_button() {
    ?>
    <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn btn-primary w-full block text-center checkout-button">
        <?php esc_html_e( 'Proceed to Checkout', 'aakaari-brand' ); ?>
    </a>
    <?php
}
remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
add_action( 'woocommerce_proceed_to_checkout', 'aakaari_custom_proceed_to_checkout_button', 20 );

/**
 * AJAX handler for updating cart quantity
 */
function aakaari_ajax_update_cart_quantity() {
    check_ajax_referer( 'aakaari-cart-nonce', 'nonce' );

    $cart_item_key = sanitize_text_field( $_POST['cart_item_key'] );
    $quantity = intval( $_POST['quantity'] );

    if ( $quantity <= 0 ) {
        WC()->cart->remove_cart_item( $cart_item_key );
    } else {
        WC()->cart->set_quantity( $cart_item_key, $quantity, true );
    }

    WC()->cart->calculate_totals();

    wp_send_json_success( array(
        'cart_hash'     => WC()->cart->get_cart_hash(),
        'cart_subtotal' => WC()->cart->get_cart_subtotal(),
        'cart_total'    => WC()->cart->get_total(),
        'fragments'     => apply_filters( 'woocommerce_update_cart_fragments', array() ),
    ) );
}
add_action( 'wp_ajax_aakaari_update_cart_quantity', 'aakaari_ajax_update_cart_quantity' );
add_action( 'wp_ajax_nopriv_aakaari_update_cart_quantity', 'aakaari_ajax_update_cart_quantity' );

/**
 * AJAX handler for removing cart item
 */
function aakaari_ajax_remove_cart_item() {
    check_ajax_referer( 'aakaari-cart-nonce', 'nonce' );

    $cart_item_key = sanitize_text_field( $_POST['cart_item_key'] );

    WC()->cart->remove_cart_item( $cart_item_key );
    WC()->cart->calculate_totals();

    wp_send_json_success( array(
        'cart_hash'     => WC()->cart->get_cart_hash(),
        'cart_count'    => WC()->cart->get_cart_contents_count(),
        'cart_subtotal' => WC()->cart->get_cart_subtotal(),
        'cart_total'    => WC()->cart->get_total(),
        'fragments'     => apply_filters( 'woocommerce_update_cart_fragments', array() ),
    ) );
}
add_action( 'wp_ajax_aakaari_remove_cart_item', 'aakaari_ajax_remove_cart_item' );
add_action( 'wp_ajax_nopriv_aakaari_remove_cart_item', 'aakaari_ajax_remove_cart_item' );

/**
 * Customize quantity input HTML
 */
function aakaari_custom_quantity_input( $html, $args ) {
    if ( ! is_cart() ) {
        return $html;
    }

    $input_id      = ! empty( $args['input_id'] ) ? $args['input_id'] : uniqid( 'quantity_' );
    $input_name    = ! empty( $args['input_name'] ) ? $args['input_name'] : 'quantity';
    $input_value   = isset( $args['input_value'] ) ? max( $args['min_value'], $args['input_value'] ) : $args['min_value'];
    $classes       = ! empty( $args['classes'] ) ? implode( ' ', (array) $args['classes'] ) : 'input-text qty text';
    $max           = ! empty( $args['max_value'] ) && $args['max_value'] > 0 ? $args['max_value'] : '';
    $min           = ! empty( $args['min_value'] ) ? $args['min_value'] : 0;
    $step          = ! empty( $args['step'] ) ? $args['step'] : 1;
    $pattern       = ! empty( $args['pattern'] ) ? $args['pattern'] : '[0-9]*';
    $inputmode     = ! empty( $args['inputmode'] ) ? $args['inputmode'] : 'numeric';
    $product_name  = ! empty( $args['product_name'] ) ? $args['product_name'] : '';
    $placeholder   = isset( $args['placeholder'] ) ? $args['placeholder'] : '';

    ob_start();
    ?>
    <button type="button" class="cart-quantity-button quantity-minus">
        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
        </svg>
    </button>
    <input
        type="number"
        id="<?php echo esc_attr( $input_id ); ?>"
        class="<?php echo esc_attr( $classes ); ?>"
        name="<?php echo esc_attr( $input_name ); ?>"
        value="<?php echo esc_attr( $input_value ); ?>"
        aria-label="<?php esc_attr_e( 'Product quantity', 'aakaari-brand' ); ?>"
        size="4"
        min="<?php echo esc_attr( $min ); ?>"
        max="<?php echo esc_attr( 0 < $max ? $max : '' ); ?>"
        step="<?php echo esc_attr( $step ); ?>"
        placeholder="<?php echo esc_attr( $placeholder ); ?>"
        inputmode="<?php echo esc_attr( $inputmode ); ?>"
        autocomplete="off"
        readonly
    />
    <button type="button" class="cart-quantity-button quantity-plus">
        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
    </button>
    <?php
    return ob_get_clean();
}
add_filter( 'woocommerce_quantity_input_html', 'aakaari_custom_quantity_input', 10, 2 );

/**
 * Add custom CSS classes to cart item
 */
function aakaari_cart_item_class( $class, $cart_item, $cart_item_key ) {
    $class .= ' aakaari-cart-item';
    return $class;
}
add_filter( 'woocommerce_cart_item_class', 'aakaari_cart_item_class', 10, 3 );

/**
 * Free shipping threshold
 */
function aakaari_free_shipping_threshold() {
    return apply_filters( 'aakaari_free_shipping_threshold', 100 );
}

/**
 * Display free shipping notice
 */
function aakaari_free_shipping_notice() {
    if ( ! is_cart() ) {
        return;
    }

    $cart_total = WC()->cart->get_subtotal();
    $threshold = aakaari_free_shipping_threshold();

    if ( $cart_total < $threshold ) {
        $remaining = $threshold - $cart_total;
        wc_print_notice(
            sprintf(
                __( 'Add %s more to get free shipping!', 'aakaari-brand' ),
                wc_price( $remaining )
            ),
            'notice'
        );
    }
}
add_action( 'woocommerce_before_cart', 'aakaari_free_shipping_notice' );
