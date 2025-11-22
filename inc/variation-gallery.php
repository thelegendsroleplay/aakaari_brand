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
    ?>
    <div class="form-row form-row-full variation-gallery-wrapper">
        <h4><?php esc_html_e('Variation Gallery Images', 'aakaari'); ?></h4>
        <p class="form-field">
            <a href="#" class="button variation-gallery-add-images" data-variation-id="<?php echo esc_attr($variation_id); ?>">
                <?php esc_html_e('Add gallery images', 'aakaari'); ?>
            </a>
        </p>
        <ul class="variation-gallery-images" data-variation-id="<?php echo esc_attr($variation_id); ?>">
            <?php
            if (!empty($gallery_images)) {
                $gallery_images_array = explode(',', $gallery_images);
                foreach ($gallery_images_array as $image_id) {
                    if ($image_id) {
                        $image = wp_get_attachment_image($image_id, 'thumbnail');
                        ?>
                        <li class="image" data-attachment-id="<?php echo esc_attr($image_id); ?>">
                            <?php echo $image; ?>
                            <a href="#" class="delete-image" title="<?php esc_attr_e('Remove image', 'aakaari'); ?>">&times;</a>
                        </li>
                        <?php
                    }
                }
            }
            ?>
        </ul>
        <input type="hidden" name="variation_gallery_images[<?php echo esc_attr($loop); ?>]" class="variation-gallery-images-input" value="<?php echo esc_attr($gallery_images); ?>">
    </div>
    <?php
}
add_action('woocommerce_variation_options_pricing', 'aakaari_add_variation_gallery_field', 10, 3);

/**
 * Save variation gallery images
 */
function aakaari_save_variation_gallery_images($variation_id, $i) {
    if (isset($_POST['variation_gallery_images'][$i])) {
        $gallery_images = sanitize_text_field($_POST['variation_gallery_images'][$i]);
        update_post_meta($variation_id, '_variation_gallery_images', $gallery_images);
    }
}
add_action('woocommerce_save_product_variation', 'aakaari_save_variation_gallery_images', 10, 2);

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

    wp_enqueue_media();
    wp_enqueue_script(
        'aakaari-variation-gallery-admin',
        get_template_directory_uri() . '/assets/js/admin/variation-gallery.js',
        array('jquery'),
        '1.0.0',
        true
    );

    wp_enqueue_style(
        'aakaari-variation-gallery-admin',
        get_template_directory_uri() . '/assets/css/admin/variation-gallery.css',
        array(),
        '1.0.0'
    );
}
add_action('admin_enqueue_scripts', 'aakaari_enqueue_variation_gallery_admin_scripts');
