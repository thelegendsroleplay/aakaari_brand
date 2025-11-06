<?php
/**
 * My Account page - User Dashboard
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * @package WooCommerce\Templates
 * @version 7.0.0
 */

defined('ABSPATH') || exit;

// Get current user
$current_user = wp_get_current_user();
$customer = new WC_Customer(get_current_user_id());
$orders = wc_get_orders(array(
    'customer' => get_current_user_id(),
    'limit' => -1,
));

// Calculate stats
$total_spent = 0;
foreach ($orders as $order) {
    $total_spent += $order->get_total();
}
$rewards_points = floor($total_spent * 10);

get_header();

do_action('woocommerce_account_navigation'); ?>

<div class="user-dashboard">
    <div class="dashboard-container">
        <div class="dashboard-grid">
            <!-- Dashboard Sidebar -->
            <aside class="dashboard-sidebar">
                <div class="user-profile-section">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($current_user->display_name, 0, 1)); ?>
                    </div>
                    <div class="user-name"><?php echo esc_html($current_user->display_name); ?></div>
                    <div class="user-email"><?php echo esc_html($current_user->user_email); ?></div>
                    <span class="user-tier">Silver Member</span>
                </div>

                <nav class="dashboard-nav">
                    <?php
                    $menu_items = array(
                        'dashboard' => array(
                            'label' => __('Overview', 'aakaari'),
                            'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>'
                        ),
                        'orders' => array(
                            'label' => __('Orders', 'aakaari'),
                            'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>',
                            'badge' => count($orders) > 0 ? count($orders) : null
                        ),
                        'edit-address' => array(
                            'label' => __('Addresses', 'aakaari'),
                            'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>'
                        ),
                        'payment-methods' => array(
                            'label' => __('Payment', 'aakaari'),
                            'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>'
                        ),
                        'edit-account' => array(
                            'label' => __('Account Details', 'aakaari'),
                            'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>'
                        ),
                        'customer-logout' => array(
                            'label' => __('Logout', 'aakaari'),
                            'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>'
                        )
                    );

                    $current_endpoint = WC()->query->get_current_endpoint();

                    foreach ($menu_items as $endpoint => $item) {
                        $url = wc_get_endpoint_url($endpoint, '', wc_get_page_permalink('myaccount'));
                        $is_active = ($current_endpoint === $endpoint) || (empty($current_endpoint) && $endpoint === 'dashboard');
                        ?>
                        <a href="<?php echo esc_url($url); ?>" class="nav-item <?php echo $is_active ? 'active' : ''; ?>" data-endpoint="<?php echo esc_attr($endpoint); ?>">
                            <?php echo $item['icon']; ?>
                            <span><?php echo esc_html($item['label']); ?></span>
                            <?php if (isset($item['badge']) && $item['badge']): ?>
                                <span class="nav-badge"><?php echo esc_html($item['badge']); ?></span>
                            <?php endif; ?>
                        </a>
                        <?php
                    }
                    ?>
                </nav>
            </aside>

            <!-- Dashboard Main Content -->
            <main class="dashboard-main">
                <?php
                /**
                 * My Account content.
                 */
                do_action('woocommerce_account_content');
                ?>

                <?php if (!WC()->query->get_current_endpoint()) : ?>
                    <!-- Dashboard Overview -->
                    <div class="dashboard-header">
                        <div class="header-top">
                            <h1 class="greeting">Welcome back, <?php echo esc_html(explode(' ', $current_user->display_name)[0]); ?>!</h1>
                            <div class="header-actions">
                                <a href="<?php echo esc_url(wc_get_endpoint_url('orders')); ?>" class="btn-secondary">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                    </svg>
                                    View All Orders
                                </a>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="quick-stats">
                            <div class="stat-card">
                                <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                    </svg>
                                </div>
                                <div class="stat-label">Total Orders</div>
                                <div class="stat-value"><?php echo count($orders); ?></div>
                            </div>

                            <div class="stat-card">
                                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg>
                                </div>
                                <div class="stat-label">Total Spent</div>
                                <div class="stat-value"><?php echo wc_price($total_spent); ?></div>
                            </div>

                            <div class="stat-card">
                                <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <circle cx="12" cy="8" r="7"></circle>
                                        <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                                    </svg>
                                </div>
                                <div class="stat-label">Rewards Points</div>
                                <div class="stat-value"><?php echo number_format($rewards_points); ?></div>
                            </div>

                            <div class="stat-card">
                                <div class="stat-icon" style="background: rgba(236, 72, 153, 0.1); color: #ec4899;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                    </svg>
                                </div>
                                <div class="stat-label">Wishlist Items</div>
                                <div class="stat-value"><?php echo aakaari_get_wishlist_count(); ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <?php if (count($orders) > 0) : ?>
                        <div class="dashboard-section">
                            <div class="section-header">
                                <h2 class="section-title">Recent Orders</h2>
                                <a href="<?php echo esc_url(wc_get_endpoint_url('orders')); ?>" class="section-action">View All</a>
                            </div>

                            <div class="orders-list">
                                <?php
                                $recent_orders = array_slice($orders, 0, 3);
                                foreach ($recent_orders as $order) :
                                    $order_id = $order->get_id();
                                    $order_status = $order->get_status();
                                    $order_total = $order->get_total();
                                    $order_date = $order->get_date_created();
                                    $order_items = $order->get_items();
                                ?>
                                    <div class="order-card">
                                        <div class="order-header">
                                            <div class="order-number">
                                                Order #<?php echo esc_html($order_id); ?>
                                            </div>
                                            <span class="order-status <?php echo esc_attr($order_status); ?>">
                                                <?php echo esc_html(wc_get_order_status_name($order_status)); ?>
                                            </span>
                                        </div>

                                        <div class="order-items">
                                            <?php
                                            $item_count = 0;
                                            foreach ($order_items as $item) :
                                                if ($item_count >= 3) break;
                                                $product = $item->get_product();
                                                if ($product && $product->get_image_id()) :
                                            ?>
                                                    <img src="<?php echo esc_url(wp_get_attachment_image_url($product->get_image_id(), 'thumbnail')); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" class="order-item-image">
                                                <?php
                                                else :
                                                ?>
                                                    <div class="order-item-image" style="background: #f9f9f9; display: flex; align-items: center; justify-content: center;">
                                                        <?php echo esc_html(substr($item->get_name(), 0, 1)); ?>
                                                    </div>
                                                <?php
                                                endif;
                                                $item_count++;
                                            endforeach;

                                            if (count($order_items) > 3) :
                                                ?>
                                                <div class="order-items-more">+<?php echo (count($order_items) - 3); ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="order-footer">
                                            <span class="order-date">
                                                <?php echo esc_html($order_date->date_i18n('F j, Y')); ?>
                                            </span>
                                            <span class="order-total"><?php echo wc_price($order_total); ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="dashboard-section">
                            <div class="empty-state">
                                <div class="empty-state-icon">📦</div>
                                <h3 class="empty-state-title">No orders yet</h3>
                                <p class="empty-state-description">Start shopping to see your orders here</p>
                                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn-primary">Start Shopping</a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Loyalty Program -->
                    <div class="dashboard-section">
                        <div class="loyalty-card">
                            <div class="loyalty-tier">Silver Member</div>
                            <div class="loyalty-points"><?php echo number_format($rewards_points); ?> Points</div>
                            <p>Keep shopping to reach Gold tier and unlock exclusive benefits!</p>
                            <div class="loyalty-progress">
                                <small>Progress to Gold Tier</small>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?php echo min(100, ($total_spent / 1000) * 100); ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>
</div>

<?php
get_footer();
