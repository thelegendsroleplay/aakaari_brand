<?php
/**
 * The template for displaying product content within loops
 *
 * @package FashionMen
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
?>

<div <?php wc_product_class('product-card group overflow-hidden border-none shadow-md hover:shadow-xl transition-all rounded-lg bg-white', $product); ?>>

    <div class="product-image relative aspect-square overflow-hidden bg-gray-100">
        <a href="<?php echo esc_url(get_permalink()); ?>" class="block w-full h-full">
            <?php
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'medium');
            if ($image) :
            ?>
                <img src="<?php echo esc_url($image[0]); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            <?php else : ?>
                <div class="w-full h-full flex items-center justify-center bg-gray-200">
                    <span class="text-gray-400"><?php esc_html_e('No Image', 'fashionmen'); ?></span>
                </div>
            <?php endif; ?>
        </a>

        <!-- Action Buttons - Show on Hover -->
        <div class="absolute top-2 right-2 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
            <!-- Wishlist Button -->
            <button class="wishlist-button h-10 w-10 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-gray-100"
                    data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                    aria-label="<?php esc_attr_e('Add to wishlist', 'fashionmen'); ?>">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </button>

            <!-- Quick View Button -->
            <button class="quick-view-button h-10 w-10 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-gray-100"
                    data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                    aria-label="<?php esc_attr_e('Quick View', 'fashionmen'); ?>">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </button>
        </div>

        <!-- Customizable Badge -->
        <?php
        $is_customizable = get_post_meta($product->get_id(), '_is_customizable', true);
        if ($is_customizable === 'yes') :
        ?>
            <span class="absolute top-2 left-2 bg-black text-white text-xs font-semibold px-3 py-1 rounded inline-flex items-center gap-1">
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                </svg>
                <?php esc_html_e('Customizable', 'fashionmen'); ?>
            </span>
        <?php endif; ?>

        <!-- Out of Stock Badge -->
        <?php if (!$product->is_in_stock()) : ?>
            <div class="absolute inset-0 bg-black/60 flex items-center justify-center">
                <span class="bg-white px-4 py-2 rounded border border-gray-300 font-semibold">
                    <?php esc_html_e('Out of Stock', 'fashionmen'); ?>
                </span>
            </div>
        <?php endif; ?>
    </div>

    <div class="product-info p-4 cursor-pointer" onclick="window.location='<?php echo esc_url(get_permalink()); ?>'">
        <h3 class="text-base font-semibold mb-1 line-clamp-1">
            <a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html($product->get_name()); ?></a>
        </h3>

        <!-- Product Category -->
        <?php
        $categories = get_the_terms($product->get_id(), 'product_cat');
        if ($categories && !is_wp_error($categories)) :
            $category = array_shift($categories);
        ?>
            <p class="product-category text-sm text-gray-600 mb-2">
                <?php echo esc_html($category->name); ?>
            </p>
        <?php endif; ?>

        <!-- Product Rating -->
        <?php if (wc_review_ratings_enabled() && $product->get_rating_count() > 0) : ?>
            <div class="flex items-center gap-2 mb-2">
                <div class="flex items-center">
                    <svg class="h-4 w-4 fill-yellow-400 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                    </svg>
                    <span class="text-sm ml-1"><?php echo esc_html(number_format($product->get_average_rating(), 1)); ?></span>
                </div>
                <span class="text-sm text-gray-500">(<?php echo esc_html($product->get_rating_count()); ?>)</span>
            </div>
        <?php endif; ?>

        <!-- Price and Colors -->
        <div class="flex items-center justify-between">
            <span class="text-xl font-semibold"><?php echo $product->get_price_html(); ?></span>

            <!-- Color Dots -->
            <?php
            $color_attribute = $product->get_attribute('pa_color');
            if ($color_attribute) :
                $colors = array_map('trim', explode(',', $color_attribute));
                $color_map = array(
                    'white' => '#ffffff',
                    'black' => '#000000',
                    'blue' => '#3b82f6',
                    'navy' => '#1e3a8a',
                    'grey' => '#6b7280',
                    'gray' => '#6b7280',
                    'red' => '#ef4444',
                    'khaki' => '#c3b091',
                    'silver' => '#c0c0c0',
                    'gold' => '#ffd700',
                );
            ?>
                <div class="flex gap-1">
                    <?php foreach (array_slice($colors, 0, 3) as $color) :
                        $color_lower = strtolower($color);
                        $bg_color = isset($color_map[$color_lower]) ? $color_map[$color_lower] : '#9ca3af';
                    ?>
                        <div class="w-4 h-4 rounded-full border border-gray-300"
                             style="background-color: <?php echo esc_attr($bg_color); ?>;"
                             title="<?php echo esc_attr($color); ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>
