<?php
/**
 * Empty cart page - Modern Design
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 */

defined( 'ABSPATH' ) || exit;

/*
 * Remove default WooCommerce empty cart message
 */
remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );

do_action( 'woocommerce_cart_is_empty' );
?>

<div class="cart-page cart-page-empty">
    <div class="cart-container">
        <div class="empty-cart">
            <div class="empty-icon">🛍️</div>
            <h1>Your cart is empty</h1>
            <p>Looks like you haven't added anything yet.<br />Start exploring our latest collection!</p>
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-full">
                Continue Shopping
            </a>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="muted-link">Back to Home</a>
        </div>
    </div>
</div>
