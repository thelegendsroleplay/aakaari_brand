<?php
/**
 * User Dashboard Functions
 * Handles dashboard display, orders, addresses, payment methods, and user data
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Enqueue dashboard scripts and styles
 */
function aakaari_enqueue_dashboard_assets() {
    // Only enqueue on my account page
    if (is_account_page()) {
        wp_enqueue_style(
            'aakaari-user-dashboard',
            get_template_directory_uri() . '/assets/css/user-dashboard.css',
            array(),
            '1.0.0'
        );

        wp_enqueue_script(
            'aakaari-user-dashboard',
            get_template_directory_uri() . '/assets/js/user-dashboard.js',
            array('jquery'),
            '1.0.0',
            true
        );

        // Localize script with AJAX URL and nonces
        wp_localize_script('aakaari-user-dashboard', 'aakariDashboard', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('dashboard-nonce'),
            'user_id' => get_current_user_id(),
            'strings' => array(
                'confirm_delete' => __('Are you sure you want to delete this?', 'aakaari'),
                'success' => __('Action completed successfully!', 'aakaari'),
                'error' => __('An error occurred. Please try again.', 'aakaari'),
            ),
        ));
    }
}
add_action('wp_enqueue_scripts', 'aakaari_enqueue_dashboard_assets');

/**
 * Get wishlist count
 */
function aakaari_get_wishlist_count() {
    // Default wishlist implementation using user meta
    $user_id = get_current_user_id();
    if (!$user_id) {
        return 0;
    }

    $wishlist = get_user_meta($user_id, '_aakaari_wishlist', true);
    if (!is_array($wishlist)) {
        return 0;
    }

    return count($wishlist);
}

/**
 * Add product to wishlist
 */
function aakaari_add_to_wishlist() {
    check_ajax_referer('dashboard-nonce', 'nonce');

    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error(array('message' => __('Please login to add items to wishlist.', 'aakaari')));
    }

    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    if (!$product_id) {
        wp_send_json_error(array('message' => __('Invalid product.', 'aakaari')));
    }

    // Get current wishlist
    $wishlist = get_user_meta($user_id, '_aakaari_wishlist', true);
    if (!is_array($wishlist)) {
        $wishlist = array();
    }

    // Add product if not already in wishlist
    if (!in_array($product_id, $wishlist)) {
        $wishlist[] = $product_id;
        update_user_meta($user_id, '_aakaari_wishlist', $wishlist);

        wp_send_json_success(array(
            'message' => __('Product added to wishlist!', 'aakaari'),
            'count' => count($wishlist)
        ));
    } else {
        wp_send_json_error(array('message' => __('Product already in wishlist.', 'aakaari')));
    }
}
add_action('wp_ajax_aakaari_add_to_wishlist', 'aakaari_add_to_wishlist');

/**
 * Remove product from wishlist
 */
function aakaari_remove_from_wishlist() {
    check_ajax_referer('dashboard-nonce', 'nonce');

    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error(array('message' => __('Please login to manage wishlist.', 'aakaari')));
    }

    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    if (!$product_id) {
        wp_send_json_error(array('message' => __('Invalid product.', 'aakaari')));
    }

    // Get current wishlist
    $wishlist = get_user_meta($user_id, '_aakaari_wishlist', true);
    if (!is_array($wishlist)) {
        $wishlist = array();
    }

    // Remove product
    $key = array_search($product_id, $wishlist);
    if ($key !== false) {
        unset($wishlist[$key]);
        $wishlist = array_values($wishlist); // Re-index array
        update_user_meta($user_id, '_aakaari_wishlist', $wishlist);

        wp_send_json_success(array(
            'message' => __('Product removed from wishlist.', 'aakaari'),
            'count' => count($wishlist)
        ));
    } else {
        wp_send_json_error(array('message' => __('Product not in wishlist.', 'aakaari')));
    }
}
add_action('wp_ajax_aakaari_remove_from_wishlist', 'aakaari_remove_from_wishlist');

/**
 * Get user wishlist
 */
function aakaari_get_wishlist() {
    $user_id = get_current_user_id();
    if (!$user_id) {
        return array();
    }

    $wishlist = get_user_meta($user_id, '_aakaari_wishlist', true);
    if (!is_array($wishlist)) {
        return array();
    }

    return $wishlist;
}

/**
 * Add custom WooCommerce My Account endpoints
 */
function aakaari_add_custom_endpoints() {
    // Add custom endpoint for wishlist
    add_rewrite_endpoint('wishlist', EP_ROOT | EP_PAGES);

    // Flush rewrite rules on theme activation
    flush_rewrite_rules();
}
add_action('init', 'aakaari_add_custom_endpoints');

/**
 * Add wishlist to My Account menu
 */
function aakaari_add_wishlist_menu_item($items) {
    // Insert wishlist before logout
    $logout = $items['customer-logout'];
    unset($items['customer-logout']);

    $items['wishlist'] = __('Wishlist', 'aakaari');
    $items['customer-logout'] = $logout;

    return $items;
}
add_filter('woocommerce_account_menu_items', 'aakaari_add_wishlist_menu_item');

/**
 * Wishlist endpoint content
 */
function aakaari_wishlist_endpoint_content() {
    $wishlist = aakaari_get_wishlist();

    echo '<div class="dashboard-section">';
    echo '<div class="section-header">';
    echo '<h2 class="section-title">' . esc_html__('My Wishlist', 'aakaari') . '</h2>';
    echo '</div>';

    if (empty($wishlist)) {
        echo '<div class="empty-state">';
        echo '<div class="empty-state-icon">💝</div>';
        echo '<h3 class="empty-state-title">' . esc_html__('Your wishlist is empty', 'aakaari') . '</h3>';
        echo '<p class="empty-state-description">' . esc_html__('Start adding products you love!', 'aakaari') . '</p>';
        echo '<a href="' . esc_url(wc_get_page_permalink('shop')) . '" class="btn-primary">' . esc_html__('Browse Products', 'aakaari') . '</a>';
        echo '</div>';
    } else {
        echo '<div class="wishlist-items">';

        foreach ($wishlist as $product_id) {
            $product = wc_get_product($product_id);
            if (!$product) continue;

            echo '<div class="wishlist-item" data-product-id="' . esc_attr($product_id) . '">';

            if ($product->get_image_id()) {
                echo '<img src="' . esc_url(wp_get_attachment_image_url($product->get_image_id(), 'medium')) . '" alt="' . esc_attr($product->get_name()) . '" class="wishlist-item-image">';
            } else {
                echo '<div class="wishlist-item-image" style="background: #f9f9f9;"></div>';
            }

            echo '<div class="wishlist-item-info">';
            echo '<h4>' . esc_html($product->get_name()) . '</h4>';
            echo '<p class="price">' . $product->get_price_html() . '</p>';
            echo '<div class="wishlist-item-actions">';
            echo '<button class="btn-secondary btn-sm add-to-cart-btn" data-product-id="' . esc_attr($product_id) . '">' . esc_html__('Add to Cart', 'aakaari') . '</button>';
            echo '<button class="btn-text btn-sm remove-from-wishlist" data-product-id="' . esc_attr($product_id) . '">' . esc_html__('Remove', 'aakaari') . '</button>';
            echo '</div>';
            echo '</div>';

            echo '</div>';
        }

        echo '</div>';
    }

    echo '</div>';
}
add_action('woocommerce_account_wishlist_endpoint', 'aakaari_wishlist_endpoint_content');

/**
 * Calculate user tier based on total spent
 */
function aakaari_get_user_tier($total_spent = null) {
    if ($total_spent === null) {
        $user_id = get_current_user_id();
        if (!$user_id) {
            return 'Bronze';
        }

        $total_spent = 0;
        $orders = wc_get_orders(array(
            'customer' => $user_id,
            'limit' => -1,
            'status' => array('completed')
        ));

        foreach ($orders as $order) {
            $total_spent += $order->get_total();
        }
    }

    if ($total_spent >= 5000) {
        return 'Platinum';
    } elseif ($total_spent >= 1000) {
        return 'Gold';
    } elseif ($total_spent >= 500) {
        return 'Silver';
    } else {
        return 'Bronze';
    }
}

/**
 * Get next tier information
 */
function aakaari_get_next_tier_info($current_tier, $total_spent) {
    $tiers = array(
        'Bronze' => array('next' => 'Silver', 'threshold' => 500),
        'Silver' => array('next' => 'Gold', 'threshold' => 1000),
        'Gold' => array('next' => 'Platinum', 'threshold' => 5000),
        'Platinum' => array('next' => null, 'threshold' => null),
    );

    if (!isset($tiers[$current_tier])) {
        return null;
    }

    $tier_info = $tiers[$current_tier];

    if ($tier_info['next'] === null) {
        return array(
            'is_max' => true,
            'message' => __('You have reached the highest tier!', 'aakaari')
        );
    }

    $remaining = $tier_info['threshold'] - $total_spent;
    $progress = ($total_spent / $tier_info['threshold']) * 100;

    return array(
        'is_max' => false,
        'next_tier' => $tier_info['next'],
        'remaining' => $remaining,
        'progress' => min(100, $progress),
        'threshold' => $tier_info['threshold']
    );
}

/**
 * Customize WooCommerce account page title
 */
function aakaari_account_page_title($title) {
    global $wp_query;

    $endpoint = WC()->query->get_current_endpoint();

    if ($endpoint === 'wishlist') {
        return __('My Wishlist', 'aakaari');
    }

    return $title;
}
add_filter('woocommerce_account_menu_item_title', 'aakaari_account_page_title');

/**
 * Add order tracking information
 */
function aakaari_add_order_tracking($order_id) {
    $order = wc_get_order($order_id);
    if (!$order) {
        return;
    }

    $tracking_number = get_post_meta($order_id, '_tracking_number', true);
    $tracking_provider = get_post_meta($order_id, '_tracking_provider', true);

    if ($tracking_number && $tracking_provider) {
        echo '<div class="order-tracking">';
        echo '<h3>' . esc_html__('Tracking Information', 'aakaari') . '</h3>';
        echo '<p><strong>' . esc_html__('Tracking Number:', 'aakaari') . '</strong> ' . esc_html($tracking_number) . '</p>';
        echo '<p><strong>' . esc_html__('Provider:', 'aakaari') . '</strong> ' . esc_html($tracking_provider) . '</p>';
        echo '</div>';
    }
}
add_action('woocommerce_view_order', 'aakaari_add_order_tracking');

/**
 * Customize order status badges
 */
function aakaari_get_order_status_badge($status) {
    $status_classes = array(
        'pending' => 'pending',
        'processing' => 'processing',
        'on-hold' => 'pending',
        'completed' => 'delivered',
        'cancelled' => 'cancelled',
        'refunded' => 'cancelled',
        'failed' => 'cancelled',
    );

    $class = isset($status_classes[$status]) ? $status_classes[$status] : 'pending';

    return sprintf(
        '<span class="order-status %s">%s</span>',
        esc_attr($class),
        esc_html(wc_get_order_status_name($status))
    );
}

/**
 * Add custom user meta fields
 */
function aakaari_add_user_meta_fields($user) {
    ?>
    <h3><?php _e('Aakaari Profile Information', 'aakaari'); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="user_tier"><?php _e('Membership Tier', 'aakaari'); ?></label></th>
            <td>
                <?php
                $total_spent = 0;
                $orders = wc_get_orders(array(
                    'customer' => $user->ID,
                    'limit' => -1,
                    'status' => array('completed')
                ));
                foreach ($orders as $order) {
                    $total_spent += $order->get_total();
                }
                $tier = aakaari_get_user_tier($total_spent);
                ?>
                <input type="text" name="user_tier" id="user_tier" value="<?php echo esc_attr($tier); ?>" class="regular-text" disabled />
                <p class="description"><?php _e('Tier is automatically calculated based on total purchases.', 'aakaari'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="rewards_points"><?php _e('Rewards Points', 'aakaari'); ?></label></th>
            <td>
                <input type="number" name="rewards_points" id="rewards_points" value="<?php echo esc_attr(floor($total_spent * 10)); ?>" class="regular-text" disabled />
                <p class="description"><?php _e('Points are earned automatically (10 points per $1 spent).', 'aakaari'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'aakaari_add_user_meta_fields');
add_action('edit_user_profile', 'aakaari_add_user_meta_fields');

/**
 * Disable WooCommerce default styles on account page
 */
function aakaari_disable_woocommerce_account_styles() {
    if (is_account_page()) {
        wp_dequeue_style('woocommerce-general');
    }
}
add_action('wp_enqueue_scripts', 'aakaari_disable_woocommerce_account_styles', 100);
