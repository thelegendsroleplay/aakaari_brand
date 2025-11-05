<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * @package FashionMen
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 */
do_action('woocommerce_before_single_product');

if (!is_a($product, 'WC_Product')) {
    return;
}
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>

    <div class="container mx-auto px-4 py-8">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">

            <!-- Product Images -->
            <div class="product-images space-y-4">
                <div class="aspect-square rounded-lg overflow-hidden bg-gray-100">
                    <?php
                    $attachment_ids = $product->get_gallery_image_ids();
                    $main_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
                    if ($main_image) :
                    ?>
                        <img id="main-product-image" src="<?php echo esc_url($main_image[0]); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" class="w-full h-full object-cover">
                    <?php else : ?>
                        <?php echo wc_placeholder_img('large'); ?>
                    <?php endif; ?>
                </div>

                <?php if (!empty($attachment_ids)) : ?>
                    <div class="grid grid-cols-4 gap-4">
                        <?php
                        // Show main image as thumbnail first
                        if ($main_image) :
                        ?>
                            <div class="aspect-square rounded-lg overflow-hidden bg-gray-100 cursor-pointer border-2 border-black transition-colors gallery-thumb" data-image="<?php echo esc_url($main_image[0]); ?>">
                                <img src="<?php echo esc_url($main_image[0]); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" class="w-full h-full object-cover">
                            </div>
                        <?php endif; ?>

                        <?php foreach (array_slice($attachment_ids, 0, 3) as $attachment_id) :
                            $image_link = wp_get_attachment_url($attachment_id);
                        ?>
                            <div class="aspect-square rounded-lg overflow-hidden bg-gray-100 cursor-pointer border-2 border-transparent hover:border-black transition-colors gallery-thumb" data-image="<?php echo esc_url($image_link); ?>">
                                <?php echo wp_get_attachment_image($attachment_id, 'medium', false, array('class' => 'w-full h-full object-cover')); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Product Info -->
            <div class="product-info">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <?php
                        $categories = get_the_terms($product->get_id(), 'product_cat');
                        if ($categories && !is_wp_error($categories)) :
                            $category = array_shift($categories);
                        ?>
                            <p class="text-sm text-gray-600 mb-2"><?php echo esc_html($category->name); ?></p>
                        <?php endif; ?>

                        <h1 class="text-3xl md:text-4xl font-bold mb-2"><?php the_title(); ?></h1>
                    </div>

                    <?php
                    $is_customizable = get_post_meta($product->get_id(), '_is_customizable', true);
                    if ($is_customizable === 'yes') :
                    ?>
                        <span class="bg-black text-white text-xs font-semibold px-3 py-1 rounded inline-flex items-center gap-1">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                            <?php esc_html_e('Customizable', 'fashionmen'); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Rating -->
                <?php if (wc_review_ratings_enabled() && $product->get_rating_count() > 0) : ?>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex items-center">
                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                <svg class="h-4 w-4 <?php echo $i <= $product->get_average_rating() ? 'fill-yellow-400 text-yellow-400' : 'text-gray-300'; ?>" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                                </svg>
                            <?php endfor; ?>
                        </div>
                        <span class="text-sm text-gray-600">
                            <?php echo esc_html(number_format($product->get_average_rating(), 1)); ?> (<?php echo esc_html($product->get_rating_count()); ?> <?php esc_html_e('reviews', 'fashionmen'); ?>)
                        </span>
                    </div>
                <?php endif; ?>

                <!-- Price -->
                <p class="text-3xl font-bold mb-6"><?php echo $product->get_price_html(); ?></p>

                <!-- Description -->
                <?php if ($product->get_short_description()) : ?>
                    <div class="text-gray-600 mb-6">
                        <?php echo wp_kses_post($product->get_short_description()); ?>
                    </div>
                <?php endif; ?>

                <!-- Add to Cart Form -->
                <?php
                /**
                 * Hook: woocommerce_single_product_summary.
                 */
                do_action('woocommerce_before_add_to_cart_form');
                ?>

                <form class="cart space-y-6" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data'>

                    <?php
                    /**
                     * Hook: woocommerce_before_add_to_cart_button.
                     */
                    do_action('woocommerce_before_add_to_cart_button');
                    ?>

                    <?php
                    /**
                     * Hook: woocommerce_after_add_to_cart_button.
                     */
                    do_action('woocommerce_after_add_to_cart_button');
                    ?>

                    <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="single_add_to_cart_button button alt w-full bg-black text-white px-8 py-3 rounded font-semibold hover:bg-gray-900 transition-colors flex items-center justify-center gap-2 text-lg">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <?php echo esc_html($product->single_add_to_cart_text()); ?>
                    </button>
                </form>

                <?php
                /**
                 * Hook: woocommerce_after_add_to_cart_form.
                 */
                do_action('woocommerce_after_add_to_cart_form');
                ?>

                <!-- Product Meta -->
                <div class="text-sm text-gray-600 space-y-2 mt-6">
                    <p>✓ <?php esc_html_e('Free shipping on orders over $100', 'fashionmen'); ?></p>
                    <p>✓ <?php esc_html_e('30-day return policy', 'fashionmen'); ?></p>
                    <p>✓ <?php esc_html_e('Secure checkout', 'fashionmen'); ?></p>
                </div>
            </div>

        </div>

        <!-- Product Tabs / Description -->
        <div class="mt-16">
            <?php
            /**
             * Hook: woocommerce_after_single_product_summary.
             */
            do_action('woocommerce_after_single_product_summary');
            ?>
        </div>

    </div>

</div>

<script>
// Gallery thumbnail click handler
document.addEventListener('DOMContentLoaded', function() {
    const thumbs = document.querySelectorAll('.gallery-thumb');
    const mainImage = document.getElementById('main-product-image');

    thumbs.forEach(function(thumb) {
        thumb.addEventListener('click', function() {
            const newImage = this.getAttribute('data-image');
            if (mainImage && newImage) {
                mainImage.src = newImage;

                // Update border on thumbs
                thumbs.forEach(t => t.classList.remove('border-black'));
                thumbs.forEach(t => t.classList.add('border-transparent'));
                this.classList.remove('border-transparent');
                this.classList.add('border-black');
            }
        });
    });
});
</script>

<?php
/**
 * Hook: woocommerce_after_single_product.
 */
do_action('woocommerce_after_single_product');
?>
