<?php
/**
 * Product Functions
 *
 * Handles single product page functionality including tabs, reviews, and related products
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue product scripts and styles
 */
function aakaari_enqueue_product_assets() {
    if (is_product()) {
        wp_enqueue_style('aakaari-product', get_template_directory_uri() . '/assets/css/product.css', array(), '1.0.0');
        wp_enqueue_script('aakaari-product', get_template_directory_uri() . '/assets/js/product.js', array('jquery'), '1.0.0', true);

        // Localize script with AJAX URL and nonces
        wp_localize_script('aakaari-product', 'woocommerce_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'review_nonce' => wp_create_nonce('submit_product_review'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'aakaari_enqueue_product_assets');

/**
 * Get product gallery images
 */
function aakaari_get_product_gallery_images($product_id) {
    $product = wc_get_product($product_id);
    $gallery_images = array();

    if ($product) {
        // Main image
        $main_image_id = $product->get_image_id();
        if ($main_image_id) {
            $gallery_images[] = array(
                'id' => $main_image_id,
                'url' => wp_get_attachment_url($main_image_id),
                'thumb' => wp_get_attachment_image_url($main_image_id, 'thumbnail'),
                'full' => wp_get_attachment_image_url($main_image_id, 'full'),
            );
        }

        // Gallery images
        $gallery_image_ids = $product->get_gallery_image_ids();
        foreach ($gallery_image_ids as $image_id) {
            $gallery_images[] = array(
                'id' => $image_id,
                'url' => wp_get_attachment_url($image_id),
                'thumb' => wp_get_attachment_image_url($image_id, 'thumbnail'),
                'full' => wp_get_attachment_image_url($image_id, 'full'),
            );
        }
    }

    return $gallery_images;
}

/**
 * Get product customization options
 */
function aakaari_get_customization_options($product_id) {
    $customization_options = get_post_meta($product_id, '_customization_options', true);

    if (!is_array($customization_options)) {
        return array();
    }

    return $customization_options;
}

/**
 * Check if product has customization options
 */
function aakaari_has_customization_options($product_id) {
    $options = aakaari_get_customization_options($product_id);
    return !empty($options);
}

/**
 * Get related products
 */
function aakaari_get_related_products($product_id, $limit = 4) {
    $product = wc_get_product($product_id);

    if (!$product) {
        return array();
    }

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $limit,
        'post__not_in' => array($product_id),
        'orderby' => 'rand',
        'post_status' => 'publish',
    );

    // Get products from the same category
    $categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids'));
    if (!empty($categories)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $categories,
            ),
        );
    }

    $query = new WP_Query($args);
    return $query->posts;
}

/**
 * Get recently viewed products
 */
function aakaari_get_recently_viewed_products($limit = 4) {
    $viewed_products = array();

    if (isset($_COOKIE['woocommerce_recently_viewed'])) {
        $viewed_products = (array) explode('|', wp_unslash($_COOKIE['woocommerce_recently_viewed']));
        $viewed_products = array_reverse(array_filter(array_map('absint', $viewed_products)));
    }

    if (empty($viewed_products)) {
        return array();
    }

    $args = array(
        'post_type' => 'product',
        'post__in' => $viewed_products,
        'posts_per_page' => $limit,
        'orderby' => 'post__in',
    );

    $query = new WP_Query($args);
    return $query->posts;
}

/**
 * Track recently viewed products
 */
function aakaari_track_product_view() {
    if (!is_singular('product')) {
        return;
    }

    global $post;

    if (empty($_COOKIE['woocommerce_recently_viewed'])) {
        $viewed_products = array();
    } else {
        $viewed_products = (array) explode('|', wp_unslash($_COOKIE['woocommerce_recently_viewed']));
    }

    // Remove duplicates
    $keys = array_flip($viewed_products);
    if (isset($keys[$post->ID])) {
        unset($viewed_products[$keys[$post->ID]]);
    }

    $viewed_products[] = $post->ID;

    // Limit to 8 products
    if (count($viewed_products) > 8) {
        array_shift($viewed_products);
    }

    // Store for 30 days
    wc_setcookie('woocommerce_recently_viewed', implode('|', $viewed_products), time() + 60 * 60 * 24 * 30);
}
add_action('template_redirect', 'aakaari_track_product_view');

/**
 * AJAX: Submit product review
 */
function aakaari_submit_product_review() {
    check_ajax_referer('submit_product_review', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Please log in to submit a review.'));
    }

    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $comment = isset($_POST['comment']) ? sanitize_textarea_field($_POST['comment']) : '';

    if (!$product_id || !$rating || !$comment) {
        wp_send_json_error(array('message' => 'Please fill in all required fields.'));
    }

    if ($rating < 1 || $rating > 5) {
        wp_send_json_error(array('message' => 'Invalid rating.'));
    }

    $user = wp_get_current_user();

    $commentdata = array(
        'comment_post_ID' => $product_id,
        'comment_author' => $user->display_name,
        'comment_author_email' => $user->user_email,
        'comment_content' => $comment,
        'comment_type' => 'review',
        'comment_parent' => 0,
        'user_id' => $user->ID,
        'comment_approved' => 1,
    );

    $comment_id = wp_insert_comment($commentdata);

    if ($comment_id) {
        update_comment_meta($comment_id, 'rating', $rating);
        wp_send_json_success(array('message' => 'Review submitted successfully!'));
    }

    wp_send_json_error(array('message' => 'Failed to submit review.'));
}
add_action('wp_ajax_submit_product_review', 'aakaari_submit_product_review');

/**
 * AJAX: Load more reviews
 */
function aakaari_load_more_reviews() {
    check_ajax_referer('submit_product_review', 'nonce');

    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $per_page = 5;

    if (!$product_id) {
        wp_send_json_error(array('message' => 'Invalid product.'));
    }

    $args = array(
        'post_id' => $product_id,
        'status' => 'approve',
        'type' => 'review',
        'number' => $per_page,
        'offset' => ($page - 1) * $per_page,
    );

    $reviews = get_comments($args);

    ob_start();
    foreach ($reviews as $review) {
        aakaari_display_review($review);
    }
    $html = ob_get_clean();

    $total_reviews = get_comments(array(
        'post_id' => $product_id,
        'status' => 'approve',
        'type' => 'review',
        'count' => true,
    ));

    $has_more = ($page * $per_page) < $total_reviews;

    wp_send_json_success(array(
        'html' => $html,
        'has_more' => $has_more,
    ));
}
add_action('wp_ajax_load_more_reviews', 'aakaari_load_more_reviews');
add_action('wp_ajax_nopriv_load_more_reviews', 'aakaari_load_more_reviews');

/**
 * Display a single review
 */
function aakaari_display_review($review) {
    $rating = get_comment_meta($review->comment_ID, 'rating', true);
    ?>
    <div class="product-review">
        <div class="product-review-header">
            <div>
                <div class="product-review-author"><?php echo esc_html($review->comment_author); ?></div>
                <div class="product-rating">
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                        <span class="product-star <?php echo $i <= $rating ? 'filled' : 'empty'; ?>">★</span>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="product-review-date"><?php echo esc_html(get_comment_date('F j, Y', $review->comment_ID)); ?></div>
        </div>
        <div class="product-review-text"><?php echo esc_html($review->comment_content); ?></div>
    </div>
    <?php
}

/**
 * Get product rating summary
 */
function aakaari_get_product_rating_summary($product_id) {
    $product = wc_get_product($product_id);

    if (!$product) {
        return array(
            'average' => 0,
            'count' => 0,
            'ratings' => array(5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0),
        );
    }

    $average = $product->get_average_rating();
    $count = $product->get_review_count();

    // Get rating breakdown
    $ratings = array(5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0);

    $reviews = get_comments(array(
        'post_id' => $product_id,
        'status' => 'approve',
        'type' => 'review',
    ));

    foreach ($reviews as $review) {
        $rating = intval(get_comment_meta($review->comment_ID, 'rating', true));
        if ($rating >= 1 && $rating <= 5) {
            $ratings[$rating]++;
        }
    }

    return array(
        'average' => $average,
        'count' => $count,
        'ratings' => $ratings,
    );
}

/**
 * Get product size guide
 */
function aakaari_get_size_guide($product_id) {
    return get_post_meta($product_id, '_size_guide', true);
}

/**
 * Get shipping information
 */
function aakaari_get_shipping_info($product_id) {
    $product = wc_get_product($product_id);

    if (!$product) {
        return array();
    }

    $info = array(
        'weight' => $product->get_weight(),
        'dimensions' => array(
            'length' => $product->get_length(),
            'width' => $product->get_width(),
            'height' => $product->get_height(),
        ),
        'shipping_class' => $product->get_shipping_class(),
    );

    return $info;
}

/**
 * Add custom product meta fields
 */
function aakaari_add_product_meta_fields() {
    global $post;

    echo '<div class="options_group">';

    // Customizable checkbox
    woocommerce_wp_checkbox(array(
        'id' => '_is_customizable',
        'label' => __('Customizable', 'aakaari'),
        'description' => __('Enable product customization', 'aakaari'),
    ));

    echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', 'aakaari_add_product_meta_fields');

/**
 * Save custom product meta fields
 */
function aakaari_save_product_meta_fields($post_id) {
    $is_customizable = isset($_POST['_is_customizable']) ? 'yes' : 'no';
    update_post_meta($post_id, '_is_customizable', $is_customizable);
}
add_action('woocommerce_process_product_meta', 'aakaari_save_product_meta_fields');

/**
 * Modify product tabs
 */
function aakaari_product_tabs($tabs) {
    // Add custom tab
    $tabs['size_guide'] = array(
        'title' => __('Size Guide', 'aakaari'),
        'priority' => 25,
        'callback' => 'aakaari_size_guide_tab_content',
    );

    // Reorder tabs
    $tabs['description']['priority'] = 10;
    $tabs['additional_information']['priority'] = 20;
    $tabs['reviews']['priority'] = 30;

    return $tabs;
}
add_filter('woocommerce_product_tabs', 'aakaari_product_tabs');

/**
 * Size guide tab content
 */
function aakaari_size_guide_tab_content() {
    global $product;
    $size_guide = aakaari_get_size_guide($product->get_id());

    if ($size_guide) {
        echo '<div class="size-guide-content">';
        echo wp_kses_post($size_guide);
        echo '</div>';
    } else {
        echo '<p>Size guide not available for this product.</p>';
    }
}

/**
 * Update product view count
 */
function aakaari_update_product_views($product_id) {
    $views = get_post_meta($product_id, '_product_views', true);
    $views = $views ? intval($views) + 1 : 1;
    update_post_meta($product_id, '_product_views', $views);
}

/**
 * Get product views
 */
function aakaari_get_product_views($product_id) {
    $views = get_post_meta($product_id, '_product_views', true);
    return $views ? intval($views) : 0;
}
