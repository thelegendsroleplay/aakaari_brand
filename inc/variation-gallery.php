<?php
/**
 * Product Variation Gallery Support
 * Adds ability to upload multiple images per variation
 *
 * @package Aakaari_Brand
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add variation gallery field to each variation
 */
function aakaari_add_variation_gallery_field($loop, $variation_data, $variation) {
    $variation_id = $variation->ID;
    $gallery_images = get_post_meta($variation_id, '_variation_gallery_images', true);

    // Debug: Log what we retrieved
    error_log('Loading variation gallery for variation ID ' . $variation_id . ': ' . $gallery_images);
    ?>
    <div class="form-row form-row-full variation-gallery-wrapper" style="clear: both; margin-top: 15px;">
        <h4 style="margin-bottom: 10px; font-weight: 600;"><?php esc_html_e('Variation Gallery Images', 'aakaari'); ?></h4>
        <p class="form-field" style="margin-bottom: 10px;">
            <label style="display: block; margin-bottom: 5px;"><?php esc_html_e('Add multiple images for this variation:', 'aakaari'); ?></label>
            <a href="#" class="button variation-gallery-add-images" data-variation-id="<?php echo esc_attr($variation_id); ?>" data-loop="<?php echo esc_attr($loop); ?>">
                <?php esc_html_e('Add gallery images', 'aakaari'); ?>
            </a>
        </p>
        <ul class="variation-gallery-images" data-variation-id="<?php echo esc_attr($variation_id); ?>" data-loop="<?php echo esc_attr($loop); ?>" style="list-style: none; margin: 10px 0; padding: 0; display: flex; flex-wrap: wrap; gap: 8px;">
            <?php
            if (!empty($gallery_images)) {
                $gallery_images_array = explode(',', $gallery_images);
                foreach ($gallery_images_array as $image_id) {
                    if ($image_id && is_numeric($image_id)) {
                        $image = wp_get_attachment_image($image_id, 'thumbnail');
                        if ($image) {
                            ?>
                            <li class="image" data-attachment-id="<?php echo esc_attr($image_id); ?>" style="position: relative; width: 80px; height: 80px; border: 1px solid #ddd; border-radius: 4px; overflow: hidden; background: #fff; cursor: move;">
                                <?php echo $image; ?>
                                <a href="#" class="delete-image" title="<?php esc_attr_e('Remove image', 'aakaari'); ?>" style="position: absolute; top: 2px; right: 2px; width: 20px; height: 20px; background: #dc3232; color: #fff; text-decoration: none; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; line-height: 1;">&times;</a>
                            </li>
                            <?php
                        }
                    }
                }
            }
            ?>
        </ul>
        <input type="hidden" name="variable_gallery_images[<?php echo esc_attr($loop); ?>]" class="variation-gallery-images-input" data-loop="<?php echo esc_attr($loop); ?>" value="<?php echo esc_attr($gallery_images); ?>">
    </div>
    <?php
}
add_action('woocommerce_product_after_variable_attributes', 'aakaari_add_variation_gallery_field', 10, 3);

/**
 * Save variation gallery images
 */
function aakaari_save_variation_gallery_images($variation_id, $i) {
    // Log for debugging
    error_log('==== VARIATION GALLERY SAVE HOOK FIRED ====');
    error_log('Saving variation gallery for variation ID: ' . $variation_id . ', loop index: ' . $i);
    error_log('POST data keys: ' . print_r(array_keys($_POST), true));

    if (isset($_POST['variable_gallery_images'][$i])) {
        $gallery_images = sanitize_text_field($_POST['variable_gallery_images'][$i]);
        error_log('Gallery images to save: ' . $gallery_images);

        if (!empty($gallery_images)) {
            update_post_meta($variation_id, '_variation_gallery_images', $gallery_images);
            error_log('Successfully saved gallery images for variation ' . $variation_id);
        } else {
            delete_post_meta($variation_id, '_variation_gallery_images');
            error_log('Deleted empty gallery for variation ' . $variation_id);
        }
    } else {
        error_log('No variable_gallery_images found in POST for index: ' . $i);
    }
}
add_action('woocommerce_save_product_variation', 'aakaari_save_variation_gallery_images', 10, 2);

/**
 * Alternative save hook to debug if the variation-specific hook isn't firing
 */
function aakaari_debug_product_save($post_id) {
    error_log('==== PRODUCT UPDATE HOOK FIRED FOR POST ID: ' . $post_id . ' ====');
    error_log('All POST keys: ' . print_r(array_keys($_POST), true));

    if (isset($_POST['variable_gallery_images'])) {
        error_log('Found variable_gallery_images in POST: ' . print_r($_POST['variable_gallery_images'], true));
    } else {
        error_log('No variable_gallery_images in POST data');
    }

    if (isset($_POST['variable_post_id'])) {
        error_log('Found variable_post_id (variation IDs): ' . print_r($_POST['variable_post_id'], true));
    }
}
add_action('woocommerce_update_product', 'aakaari_debug_product_save', 10, 1);

/**
 * Verify hooks are registered - run on init
 */
function aakaari_verify_hooks_registered() {
    global $wp_filter;
    error_log('==== VERIFYING VARIATION GALLERY HOOKS ====');

    if (isset($wp_filter['woocommerce_save_product_variation'])) {
        error_log('woocommerce_save_product_variation hook IS registered');
        error_log('Callbacks: ' . print_r($wp_filter['woocommerce_save_product_variation'], true));
    } else {
        error_log('woocommerce_save_product_variation hook NOT registered');
    }

    if (isset($wp_filter['woocommerce_update_product'])) {
        error_log('woocommerce_update_product hook IS registered');
    } else {
        error_log('woocommerce_update_product hook NOT registered');
    }
}
add_action('init', 'aakaari_verify_hooks_registered', 999);

/**
 * Enqueue admin scripts for variation gallery
 */
function aakaari_enqueue_variation_gallery_admin_scripts($hook) {
    if ('post.php' !== $hook && 'post-new.php' !== $hook) {
        return;
    }

    global $post;
    if (!$post || 'product' !== $post->post_type) {
        return;
    }

    // Enqueue WordPress media library
    wp_enqueue_media();

    // Enqueue jQuery UI sortable
    wp_enqueue_script('jquery-ui-sortable');

    // Enqueue our custom script
    wp_enqueue_script(
        'aakaari-variation-gallery-admin',
        get_template_directory_uri() . '/assets/js/admin/variation-gallery.js',
        array('jquery', 'jquery-ui-sortable'),
        '1.0.4', // Changed version to force refresh
        true
    );

    // Enqueue our custom styles
    wp_enqueue_style(
        'aakaari-variation-gallery-admin',
        get_template_directory_uri() . '/assets/css/admin/variation-gallery.css',
        array(),
        '1.0.4' // Changed version to force refresh
    );
}
add_action('admin_enqueue_scripts', 'aakaari_enqueue_variation_gallery_admin_scripts');
