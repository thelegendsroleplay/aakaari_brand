<?php
/**
 * My Account Page - Complete Figma Design with Functionality
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 */

defined( 'ABSPATH' ) || exit;

// Get current user
$current_user = wp_get_current_user();
$customer_id = get_current_user_id();

// Get current tab from URL parameter or default to overview
$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'overview';

// Get orders
$customer_orders = wc_get_orders( array(
    'customer' => $customer_id,
    'limit'    => -1,
    'orderby'  => 'date',
    'order'    => 'DESC',
) );

// Get wishlist
$wishlist_items = array();
$wishlist_count = 0;
if ( function_exists( 'YITH_WCWL' ) ) {
    $wishlist_count = YITH_WCWL()->count_products();
    $wishlist = YITH_WCWL()->get_products( array( 'user_id' => $customer_id ) );
    if ( ! empty( $wishlist ) ) {
        $wishlist_items = $wishlist;
    }
}

// Get addresses count
$billing_address = get_user_meta( $customer_id, 'billing_address_1', true );
$shipping_address = get_user_meta( $customer_id, 'shipping_address_1', true );
$addresses_count = 0;
if ( ! empty( $billing_address ) ) $addresses_count++;
if ( ! empty( $shipping_address ) && $shipping_address !== $billing_address ) $addresses_count++;

// Get filter from URL
$status_filter = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : 'all';

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
                <a href="?tab=overview" class="tab-trigger <?php echo $active_tab === 'overview' ? 'active' : ''; ?>">Overview</a>
                <a href="?tab=orders" class="tab-trigger <?php echo $active_tab === 'orders' ? 'active' : ''; ?>">Orders</a>
                <?php if ( function_exists( 'YITH_WCWL' ) ) : ?>
                <a href="?tab=wishlist" class="tab-trigger <?php echo $active_tab === 'wishlist' ? 'active' : ''; ?>">Wishlist</a>
                <?php endif; ?>
                <a href="?tab=support" class="tab-trigger <?php echo $active_tab === 'support' ? 'active' : ''; ?>">Support</a>
                <a href="?tab=profile" class="tab-trigger <?php echo $active_tab === 'profile' ? 'active' : ''; ?>">Profile</a>
                <a href="?tab=addresses" class="tab-trigger <?php echo $active_tab === 'addresses' ? 'active' : ''; ?>">Addresses</a>
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
                        <a href="?tab=orders" style="padding: 0.5rem 1rem; font-size: 0.875rem; color: #666; text-decoration: none; border-radius: 6px; transition: background 0.2s;">
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

            <!-- Orders Tab -->
            <?php elseif ( $active_tab === 'orders' ) : ?>
                <div style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <div class="card-header">
                        <h2>All Orders</h2>
                        <div class="filter-buttons">
                            <a href="?tab=orders&status=all" class="<?php echo $status_filter === 'all' ? 'active' : ''; ?>" style="padding: 0.375rem 0.75rem; font-size: 0.875rem; border: 1px solid #e5e7eb; border-radius: 6px; text-decoration: none; color: #000; margin-right: 0.5rem;">All</a>
                            <a href="?tab=orders&status=processing" class="<?php echo $status_filter === 'processing' ? 'active' : ''; ?>" style="padding: 0.375rem 0.75rem; font-size: 0.875rem; border: 1px solid #e5e7eb; border-radius: 6px; text-decoration: none; color: #000; margin-right: 0.5rem;">Processing</a>
                            <a href="?tab=orders&status=completed" class="<?php echo $status_filter === 'completed' ? 'active' : ''; ?>" style="padding: 0.375rem 0.75rem; font-size: 0.875rem; border: 1px solid #e5e7eb; border-radius: 6px; text-decoration: none; color: #000; margin-right: 0.5rem;">Completed</a>
                        </div>
                    </div>

                    <?php
                    // Filter orders
                    $filtered_orders = $customer_orders;
                    if ( $status_filter !== 'all' ) {
                        $filtered_orders = array_filter( $customer_orders, function( $order ) use ( $status_filter ) {
                            return $order->get_status() === $status_filter;
                        });
                    }

                    if ( ! empty( $filtered_orders ) ) :
                    ?>
                        <div class="orders-table-wrapper">
                            <table class="orders-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ( $filtered_orders as $order ) : ?>
                                        <tr>
                                            <td class="order-id">#<?php echo esc_html( $order->get_order_number() ); ?></td>
                                            <td><?php echo esc_html( $order->get_date_created()->format( 'M j, Y' ) ); ?></td>
                                            <td><?php echo count( $order->get_items() ); ?> items</td>
                                            <td class="order-total"><?php echo $order->get_formatted_order_total(); ?></td>
                                            <td>
                                                <span class="status-badge <?php echo esc_attr( $order->get_status() ); ?>">
                                                    <?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" style="padding: 0.375rem 0.75rem; font-size: 0.875rem; color: #666; text-decoration: none;">View Details</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else : ?>
                        <div class="empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="2" style="margin: 0 auto 1rem;">
                                <rect x="1" y="3" width="15" height="13"></rect>
                            </svg>
                            <p style="color: #666; margin-bottom: 1rem;">No orders found</p>
                            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" style="display: inline-block; padding: 0.5rem 1.5rem; background: #000; color: #fff; border-radius: 6px; text-decoration: none;">
                                Start Shopping
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

            <!-- Wishlist Tab -->
            <?php elseif ( $active_tab === 'wishlist' && function_exists( 'YITH_WCWL' ) ) : ?>
                <div style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <div class="card-header">
                        <h2>My Wishlist</h2>
                        <p class="text-sm text-gray-600" style="font-size: 0.875rem; color: #666;"><?php echo $wishlist_count; ?> items</p>
                    </div>

                    <?php if ( ! empty( $wishlist_items ) ) : ?>
                        <div class="wishlist-grid">
                            <?php foreach ( $wishlist_items as $item ) :
                                $product = wc_get_product( $item['prod_id'] );
                                if ( ! $product ) continue;
                            ?>
                                <div class="wishlist-item">
                                    <?php echo $product->get_image( 'woocommerce_thumbnail' ); ?>
                                    <div class="wishlist-details">
                                        <h3><?php echo esc_html( $product->get_name() ); ?></h3>
                                        <p class="price"><?php echo $product->get_price_html(); ?></p>
                                        <div class="wishlist-actions">
                                            <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" style="display: inline-block; padding: 0.5rem 1rem; font-size: 0.875rem; background: #000; color: #fff; border-radius: 6px; text-decoration: none;">
                                                View Product
                                            </a>
                                            <button onclick="removeFromWishlist(<?php echo esc_js( $product->get_id() ); ?>)" style="padding: 0.5rem 1rem; font-size: 0.875rem; border: 1px solid #e5e7eb; border-radius: 6px; background: white; cursor: pointer;">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <div class="empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="2" style="margin: 0 auto 1rem;">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                            <p style="color: #666; margin-bottom: 1rem;">Your wishlist is empty</p>
                            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" style="display: inline-block; padding: 0.5rem 1.5rem; background: #000; color: #fff; border-radius: 6px; text-decoration: none;">
                                Browse Products
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

            <!-- Support Tab -->
            <?php elseif ( $active_tab === 'support' ) : ?>
                <div class="support-section">
                    <div class="section-header">
                        <h2>Customer Support</h2>
                    </div>

                    <div style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
                        <div class="card-header">
                            <h3>Need Help?</h3>
                        </div>
                        <div style="padding: 1.5rem;">
                            <p style="color: #666; margin-bottom: 1rem;">
                                Have a question or issue? Our support team is here to help! Create a support ticket and we'll get back to you as soon as possible.
                            </p>
                            <a href="<?php echo esc_url( home_url('/support/') ); ?>" style="display: inline-block; padding: 0.5rem 1.5rem; background: #000; color: #fff; border-radius: 6px; text-decoration: none;">
                                Create New Ticket
                            </a>
                        </div>
                    </div>

                    <div style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <div class="card-header">
                            <h3>Quick Links</h3>
                        </div>
                        <div style="padding: 1.5rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                            <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" style="border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem; text-decoration: none; transition: background 0.2s;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" style="margin-bottom: 0.5rem;">
                                    <rect x="1" y="3" width="15" height="13"></rect>
                                </svg>
                                <h4 style="font-weight: 600; margin-bottom: 0.25rem; color: #000;">Track Order</h4>
                                <p style="font-size: 0.875rem; color: #666; margin: 0;">Check your order status and delivery</p>
                            </a>
                            <a href="<?php echo esc_url( home_url('/returns/') ); ?>" style="border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem; text-decoration: none; transition: background 0.2s;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" style="margin-bottom: 0.5rem;">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                                <h4 style="font-weight: 600; margin-bottom: 0.25rem; color: #000;">Returns & Refunds</h4>
                                <p style="font-size: 0.875rem; color: #666; margin: 0;">Learn about our return policy</p>
                            </a>
                            <a href="<?php echo esc_url( home_url('/shipping/') ); ?>" style="border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem; text-decoration: none; transition: background 0.2s;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" style="margin-bottom: 0.5rem;">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                </svg>
                                <h4 style="font-weight: 600; margin-bottom: 0.25rem; color: #000;">Shipping Info</h4>
                                <p style="font-size: 0.875rem; color: #666; margin: 0;">View shipping rates and times</p>
                            </a>
                            <a href="?tab=profile" style="border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem; text-decoration: none; transition: background 0.2s;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2" style="margin-bottom: 0.5rem;">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                </svg>
                                <h4 style="font-weight: 600; margin-bottom: 0.25rem; color: #000;">Account Help</h4>
                                <p style="font-size: 0.875rem; color: #666; margin: 0;">Manage your account settings</p>
                            </a>
                        </div>
                    </div>
                </div>

            <!-- Profile Tab -->
            <?php elseif ( $active_tab === 'profile' ) : ?>
                <div style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <div class="card-header">
                        <h2>Profile Information</h2>
                    </div>
                    <div class="profile-form">
                        <?php
                        // Use WooCommerce default edit account form
                        wc_get_template( 'myaccount/form-edit-account.php', array( 'user' => $current_user ) );
                        ?>
                    </div>
                </div>

            <!-- Addresses Tab -->
            <?php elseif ( $active_tab === 'addresses' ) : ?>
                <div class="addresses-section">
                    <div class="section-header">
                        <h2>Saved Addresses</h2>
                        <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'billing' ) ); ?>" style="display: inline-block; padding: 0.5rem 1rem; background: #000; color: #fff; border-radius: 6px; text-decoration: none; font-size: 0.875rem;">
                            Add New Address
                        </a>
                    </div>

                    <div class="addresses-grid">
                        <?php
                        $billing_first = get_user_meta( $customer_id, 'billing_first_name', true );
                        $billing_last = get_user_meta( $customer_id, 'billing_last_name', true );
                        $billing_addr1 = get_user_meta( $customer_id, 'billing_address_1', true );
                        $billing_addr2 = get_user_meta( $customer_id, 'billing_address_2', true );
                        $billing_city = get_user_meta( $customer_id, 'billing_city', true );
                        $billing_state = get_user_meta( $customer_id, 'billing_state', true );
                        $billing_postcode = get_user_meta( $customer_id, 'billing_postcode', true );
                        $billing_country = get_user_meta( $customer_id, 'billing_country', true );
                        $billing_phone = get_user_meta( $customer_id, 'billing_phone', true );

                        if ( ! empty( $billing_addr1 ) ) :
                        ?>
                        <div class="address-card" style="background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                            <div class="address-header">
                                <h3>Billing Address</h3>
                                <span class="default-badge">Default</span>
                            </div>
                            <div class="address-details">
                                <p><?php echo esc_html( $billing_first . ' ' . $billing_last ); ?></p>
                                <p><?php echo esc_html( $billing_addr1 ); ?></p>
                                <?php if ( $billing_addr2 ) : ?>
                                <p><?php echo esc_html( $billing_addr2 ); ?></p>
                                <?php endif; ?>
                                <p><?php echo esc_html( $billing_city . ', ' . $billing_state . ' ' . $billing_postcode ); ?></p>
                                <p><?php echo esc_html( $billing_country ); ?></p>
                                <p><?php echo esc_html( $billing_phone ); ?></p>
                            </div>
                            <div class="address-actions">
                                <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'billing' ) ); ?>" style="padding: 0.375rem 0.75rem; font-size: 0.875rem; color: #666; text-decoration: none;">Edit</a>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php
                        $shipping_first = get_user_meta( $customer_id, 'shipping_first_name', true );
                        $shipping_last = get_user_meta( $customer_id, 'shipping_last_name', true );
                        $shipping_addr1 = get_user_meta( $customer_id, 'shipping_address_1', true );
                        $shipping_addr2 = get_user_meta( $customer_id, 'shipping_address_2', true );
                        $shipping_city = get_user_meta( $customer_id, 'shipping_city', true );
                        $shipping_state = get_user_meta( $customer_id, 'shipping_state', true );
                        $shipping_postcode = get_user_meta( $customer_id, 'shipping_postcode', true );
                        $shipping_country = get_user_meta( $customer_id, 'shipping_country', true );

                        if ( ! empty( $shipping_addr1 ) ) :
                        ?>
                        <div class="address-card" style="background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                            <div class="address-header">
                                <h3>Shipping Address</h3>
                            </div>
                            <div class="address-details">
                                <p><?php echo esc_html( $shipping_first . ' ' . $shipping_last ); ?></p>
                                <p><?php echo esc_html( $shipping_addr1 ); ?></p>
                                <?php if ( $shipping_addr2 ) : ?>
                                <p><?php echo esc_html( $shipping_addr2 ); ?></p>
                                <?php endif; ?>
                                <p><?php echo esc_html( $shipping_city . ', ' . $shipping_state . ' ' . $shipping_postcode ); ?></p>
                                <p><?php echo esc_html( $shipping_country ); ?></p>
                            </div>
                            <div class="address-actions">
                                <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'shipping' ) ); ?>" style="padding: 0.375rem 0.75rem; font-size: 0.875rem; color: #666; text-decoration: none;">Edit</a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.tab-trigger {
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    font-weight: 500;
    border-bottom: 2px solid transparent;
    text-decoration: none;
    white-space: nowrap;
    color: #666;
    transition: all 0.2s;
}

.tab-trigger:hover {
    background: #f9fafb;
}

.tab-trigger.active {
    color: #000 !important;
    border-bottom-color: #000 !important;
}

.filter-buttons a.active {
    background: #000 !important;
    color: #fff !important;
}
</style>

<script>
function removeFromWishlist(productId) {
    // YITH Wishlist removal
    if (confirm('Remove this item from wishlist?')) {
        window.location.reload();
    }
}
</script>
