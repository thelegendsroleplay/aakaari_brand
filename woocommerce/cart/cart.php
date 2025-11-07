<?php
/**
 * Cart Page - Exact Figma Design
 * Template Name: Cart Page
 */

defined( 'ABSPATH' ) || exit;

get_header();

// Hide WooCommerce notices on this page
remove_action( 'woocommerce_before_cart', 'woocommerce_output_all_notices', 10 );
?>

<div class="cart-page">
    <div class="cart-container">

        <?php if ( WC()->cart->is_empty() ) : ?>

            <div class="empty-cart">
                <svg class="empty-icon" xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="8" cy="21" r="1"></circle>
                    <circle cx="19" cy="21" r="1"></circle>
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                </svg>
                <h2>Your cart is empty</h2>
                <p>Add some items to get started</p>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-primary btn-lg">
                    Continue Shopping
                </a>
            </div>

        <?php else : ?>

            <div class="cart-header">
                <h1>Shopping Cart</h1>
                <p><?php echo WC()->cart->get_cart_contents_count(); ?> <?php echo WC()->cart->get_cart_contents_count() === 1 ? 'item' : 'items'; ?></p>
            </div>

            <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
                <div class="cart-grid">
                    <!-- Cart Items -->
                    <div class="cart-items">
                        <?php
                        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) {
                                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                                $price = $_product->get_price();
                                $item_total = $price * $cart_item['quantity'];
                                $item_subtotal = $cart_item['line_subtotal'];
                                $discount = 0;

                                if ( $_product->is_on_sale() ) {
                                    $discount = ( $_product->get_regular_price() - $_product->get_sale_price() ) * $cart_item['quantity'];
                                }
                                ?>
                                <div class="cart-item">
                                    <div class="item-image">
                                        <?php
                                        $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'woocommerce_thumbnail' ), $cart_item, $cart_item_key );
                                        if ( ! $product_permalink ) {
                                            echo $thumbnail;
                                        } else {
                                            printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
                                        }
                                        ?>
                                    </div>

                                    <div class="item-details">
                                        <h3>
                                            <?php
                                            if ( ! $product_permalink ) {
                                                echo wp_kses_post( $_product->get_name() );
                                            } else {
                                                echo wp_kses_post( sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ) );
                                            }
                                            ?>
                                        </h3>

                                        <?php
                                        // Display variation attributes
                                        $item_data = wc_get_formatted_cart_item_data( $cart_item );
                                        if ( $item_data ) {
                                            echo '<p class="item-meta">' . $item_data . '</p>';
                                        }
                                        ?>

                                        <?php if ( $discount > 0 ) : ?>
                                            <p class="item-discount">
                                                Save <?php echo wc_price( $discount ); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="item-quantity">
                                        <button type="button" class="qty-btn" onclick="updateCartQuantity('<?php echo esc_js( $cart_item_key ); ?>', -1, <?php echo $cart_item['quantity']; ?>)">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </button>
                                        <span class="qty-value"><?php echo $cart_item['quantity']; ?></span>
                                        <input type="hidden" name="cart[<?php echo $cart_item_key; ?>][qty]" value="<?php echo $cart_item['quantity']; ?>" id="qty-<?php echo esc_attr( $cart_item_key ); ?>" />
                                        <button type="button" class="qty-btn" onclick="updateCartQuantity('<?php echo esc_js( $cart_item_key ); ?>', 1, <?php echo $cart_item['quantity']; ?>)">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="item-price">
                                        <span class="price-label">Price:</span>
                                        <span class="price-value"><?php echo wc_price( $price ); ?></span>
                                    </div>

                                    <div class="item-total">
                                        <span class="total-label">Total:</span>
                                        <span class="total-value"><?php echo wc_price( $item_total ); ?></span>
                                    </div>

                                    <button type="button" class="item-remove" onclick="removeCartItem('<?php echo esc_js( $cart_item_key ); ?>')">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </div>
                                <?php
                            }
                        }
                        ?>

                        <div class="cart-actions">
                            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-outline">
                                Continue Shopping
                            </a>
                            <button type="button" class="btn-destructive" onclick="if(confirm('Clear all items from cart?')) { document.querySelector('[name=update_cart]').click(); WC()->cart->empty_cart(); window.location.reload(); }">
                                Clear Cart
                            </button>
                        </div>

                        <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>" style="display: none;"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>
                        <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                    </div>

                    <!-- Cart Summary -->
                    <div class="cart-summary">
                        <h2>Order Summary</h2>

                        <div class="summary-rows">
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span><?php echo WC()->cart->get_cart_subtotal(); ?></span>
                            </div>

                            <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                                <div class="summary-row discount-row">
                                    <span><?php echo esc_html( $coupon->get_code() ); ?></span>
                                    <span>-<?php echo WC()->cart->get_coupon_discount_amount( $code ); ?></span>
                                </div>
                            <?php endforeach; ?>

                            <div class="summary-row">
                                <span>Shipping</span>
                                <span><?php
                                    if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) {
                                        if ( WC()->cart->get_cart_contents_total() >= 100 ) {
                                            echo 'FREE';
                                        } else {
                                            echo wc_price( 10 );
                                        }
                                    } else {
                                        echo wc_price( 0 );
                                    }
                                ?></span>
                            </div>

                            <?php if ( WC()->cart->get_cart_contents_total() >= 100 ) : ?>
                                <p class="free-shipping-note">🎉 You got free shipping!</p>
                            <?php elseif ( WC()->cart->get_cart_contents_total() < 100 ) : ?>
                                <p class="free-shipping-progress">
                                    Add <?php echo wc_price( 100 - WC()->cart->get_cart_contents_total() ); ?> more for free shipping
                                </p>
                            <?php endif; ?>

                            <?php if ( wc_tax_enabled() ) : ?>
                                <div class="summary-row">
                                    <span>Tax (8%)</span>
                                    <span><?php echo WC()->cart->get_tax_totals() ? array_sum( wp_list_pluck( WC()->cart->get_tax_totals(), 'amount' ) ) : 0; ?><?php echo WC()->cart->get_cart_tax(); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="summary-separator"></div>

                        <div class="summary-total">
                            <span>Total</span>
                            <span><?php echo WC()->cart->get_total(); ?></span>
                        </div>

                        <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn-primary btn-full checkout-btn">
                            Proceed to Checkout
                        </a>

                        <div class="payment-methods">
                            <p>We accept:</p>
                            <div class="payment-icons">
                                <span class="payment-badge">VISA</span>
                                <span class="payment-badge">MASTERCARD</span>
                                <span class="payment-badge">PAYPAL</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        <?php endif; ?>

    </div>
</div>

<script>
function updateCartQuantity(cartItemKey, change, currentQty) {
    const newQty = currentQty + change;
    if (newQty < 1) return;

    const input = document.getElementById('qty-' + cartItemKey);
    if (input) {
        input.value = newQty;
        input.closest('form').querySelector('[name="update_cart"]').click();
    }
}

function removeCartItem(cartItemKey) {
    if (confirm('Remove this item from cart?')) {
        window.location.href = '<?php echo esc_url( wc_get_cart_url() ); ?>?remove_item=' + cartItemKey + '&_wpnonce=<?php echo wp_create_nonce( 'woocommerce-cart' ); ?>';
    }
}

// Handle remove_item query param
<?php if ( isset( $_GET['remove_item'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'woocommerce-cart' ) ) : ?>
    WC()->cart->remove_cart_item('<?php echo esc_js( sanitize_text_field( $_GET['remove_item'] ) ); ?>');
    window.location.href = '<?php echo esc_url( wc_get_cart_url() ); ?>';
<?php endif; ?>
</script>

<?php get_footer(); ?>
