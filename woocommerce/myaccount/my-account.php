<?php
/**
 * My Account Page - Figma Design
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 */

defined( 'ABSPATH' ) || exit;

// Get current user
$current_user = wp_get_current_user();

// Get current endpoint
global $wp;
$current_endpoint = isset( $wp->query_vars ) ? key( $wp->query_vars ) : 'dashboard';
if ( empty( $current_endpoint ) || $current_endpoint === 'page' ) {
    $current_endpoint = 'dashboard';
}

// Map endpoints to tab names
$tab_mapping = array(
    'dashboard' => 'overview',
    'orders' => 'orders',
    'edit-address' => 'addresses',
    'edit-account' => 'profile',
);

$active_tab = isset( $tab_mapping[ $current_endpoint ] ) ? $tab_mapping[ $current_endpoint ] : 'overview';

// Get orders
$customer_orders = wc_get_orders( array(
    'customer' => get_current_user_id(),
    'limit'    => -1,
) );

// Get wishlist count (if YITH installed)
$wishlist_count = 0;
if ( function_exists( 'YITH_WCWL' ) ) {
    $wishlist_count = YITH_WCWL()->count_products();
}

// Get addresses count
$billing_address = get_user_meta( get_current_user_id(), 'billing_address_1', true );
$shipping_address = get_user_meta( get_current_user_id(), 'shipping_address_1', true );
$addresses_count = 0;
if ( ! empty( $billing_address ) ) $addresses_count++;
if ( ! empty( $shipping_address ) && $shipping_address !== $billing_address ) $addresses_count++;

?>

<div class="account-page">
    <div class="account-container">
        <!-- Header -->
        <div class="account-header">
            <div>
                <h1>My Account</h1>
                <p class="text-gray-600">Welcome back, <?php echo esc_html( $current_user->display_name ); ?></p>
            </div>
            <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="button button-outline" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border: 1px solid #e5e7eb; border-radius: 6px; text-decoration: none; font-size: 0.875rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                Logout
            </a>
        </div>

        <!-- Tabs -->
        <div class="account-tabs">
            <div class="tabs-list" style="display: flex; gap: 0.5rem; margin-bottom: 2rem; border-bottom: 1px solid #e5e7eb; overflow-x: auto;">
                <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>"
                   class="tab-trigger <?php echo $active_tab === 'overview' ? 'active' : ''; ?>"
                   style="padding: 0.75rem 1rem; font-size: 0.875rem; font-weight: 500; border-bottom: 2px solid transparent; text-decoration: none; white-space: nowrap; color: #666; transition: all 0.2s;">
                    Overview
                </a>
                <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>"
                   class="tab-trigger <?php echo $active_tab === 'orders' ? 'active' : ''; ?>"
                   style="padding: 0.75rem 1rem; font-size: 0.875rem; font-weight: 500; border-bottom: 2px solid transparent; text-decoration: none; white-space: nowrap; color: #666; transition: all 0.2s;">
                    Orders
                </a>
                <?php if ( function_exists( 'YITH_WCWL' ) ) : ?>
                <a href="<?php echo esc_url( YITH_WCWL()->get_wishlist_url() ); ?>"
                   class="tab-trigger"
                   style="padding: 0.75rem 1rem; font-size: 0.875rem; font-weight: 500; border-bottom: 2px solid transparent; text-decoration: none; white-space: nowrap; color: #666; transition: all 0.2s;">
                    Wishlist
                </a>
                <?php endif; ?>
                <a href="<?php echo esc_url( home_url('/support/') ); ?>"
                   class="tab-trigger"
                   style="padding: 0.75rem 1rem; font-size: 0.875rem; font-weight: 500; border-bottom: 2px solid transparent; text-decoration: none; white-space: nowrap; color: #666; transition: all 0.2s;">
                    Support
                </a>
                <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-account' ) ); ?>"
                   class="tab-trigger <?php echo $active_tab === 'profile' ? 'active' : ''; ?>"
                   style="padding: 0.75rem 1rem; font-size: 0.875rem; font-weight: 500; border-bottom: 2px solid transparent; text-decoration: none; white-space: nowrap; color: #666; transition: all 0.2s;">
                    Profile
                </a>
                <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-address' ) ); ?>"
                   class="tab-trigger <?php echo $active_tab === 'addresses' ? 'active' : ''; ?>"
                   style="padding: 0.75rem 1rem; font-size: 0.875rem; font-weight: 500; border-bottom: 2px solid transparent; text-decoration: none; white-space: nowrap; color: #666; transition: all 0.2s;">
                    Addresses
                </a>
            </div>

            <!-- Overview Tab -->
            <?php if ( $active_tab === 'overview' ) : ?>
                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="stat-card" style="background: white; padding: 1.5rem; border-radius: 0.5rem; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <div class="stat-icon-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="1" y="3" width="15" height="13"></rect>
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                <circle cx="18.5" cy="18.5" r="2.5"></circle>
                            </svg>
                        </div>
                        <h3 class="stat-value"><?php echo count( $customer_orders ); ?></h3>
                        <p class="stat-label">Total Orders</p>
                    </div>

                    <div class="stat-card" style="background: white; padding: 1.5rem; border-radius: 0.5rem; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <div class="stat-icon-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                        </div>
                        <h3 class="stat-value"><?php echo esc_html( $wishlist_count ); ?></h3>
                        <p class="stat-label">Wishlist Items</p>
                    </div>

                    <div class="stat-card" style="background: white; padding: 1.5rem; border-radius: 0.5rem; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <div class="stat-icon-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                        </div>
                        <h3 class="stat-value"><?php echo esc_html( $addresses_count ); ?></h3>
                        <p class="stat-label">Saved Addresses</p>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="section-spacing">
                    <div class="section-header">
                        <h2>Recent Orders</h2>
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" style="padding: 0.5rem 1rem; font-size: 0.875rem; color: #666; text-decoration: none; border-radius: 6px; transition: background 0.2s;">
                            View All
                        </a>
                    </div>

                    <?php
                    $recent_orders = array_slice( $customer_orders, 0, 5 );
                    if ( ! empty( $recent_orders ) ) :
                    ?>
                        <div class="orders-list">
                            <?php foreach ( $recent_orders as $order ) : ?>
                                <div class="order-card" style="background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                    <div class="order-header">
                                        <div>
                                            <p class="order-id">Order #<?php echo esc_html( $order->get_order_number() ); ?></p>
                                            <p class="order-date"><?php echo esc_html( $order->get_date_created()->format( 'F j, Y' ) ); ?></p>
                                        </div>
                                        <span class="status-badge <?php echo esc_attr( $order->get_status() ); ?>">
                                            <?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
                                        </span>
                                    </div>

                                    <div class="order-items">
                                        <?php
                                        $items = $order->get_items();
                                        $displayed = 0;
                                        foreach ( $items as $item ) :
                                            if ( $displayed >= 3 ) break;
                                            $product = $item->get_product();
                                            if ( ! $product ) continue;
                                            $displayed++;
                                        ?>
                                            <div class="order-item">
                                                <?php echo $product->get_image( array( 60, 60 ) ); ?>
                                                <div class="item-details">
                                                    <p><?php echo esc_html( $item->get_name() ); ?></p>
                                                    <p class="text-sm text-gray-500" style="font-size: 0.875rem; color: #666;">Qty: <?php echo esc_html( $item->get_quantity() ); ?></p>
                                                </div>
                                                <p class="item-price"><?php echo $order->get_formatted_line_subtotal( $item ); ?></p>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <div class="order-footer">
                                        <p class="order-total">Total: <?php echo $order->get_formatted_order_total(); ?></p>
                                        <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" style="padding: 0.5rem 1rem; font-size: 0.875rem; border: 1px solid #e5e7eb; border-radius: 6px; text-decoration: none; color: #000;">
                                            Track Order
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <div class="empty-state" style="background: white; padding: 4rem 2rem; border-radius: 0.5rem; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="2" style="margin: 0 auto 1rem;">
                                <rect x="1" y="3" width="15" height="13"></rect>
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                <circle cx="18.5" cy="18.5" r="2.5"></circle>
                            </svg>
                            <p style="color: #666; margin-bottom: 1rem;">No orders yet</p>
                            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" style="display: inline-block; padding: 0.5rem 1.5rem; background: #000; color: #fff; border-radius: 6px; text-decoration: none;">
                                Start Shopping
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else : ?>
                <!-- WooCommerce Default Content -->
                <?php do_action( 'woocommerce_account_content' ); ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.tab-trigger.active {
    color: #000 !important;
    border-bottom-color: #000 !important;
}

.tab-trigger:hover {
    background: #f9fafb;
}
</style>
