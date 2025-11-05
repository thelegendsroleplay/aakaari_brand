<?php
/**
 * The template for displaying the home/front page
 *
 * @package FashionMen
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">

    <!-- Hero Section -->
    <section class="hero-section relative h-[500px] md:h-[600px] bg-gray-100">
        <?php
        $hero_image = get_theme_mod('hero_image', get_template_directory_uri() . '/assets/images/hero-placeholder.jpg');
        ?>
        <img src="<?php echo esc_url($hero_image); ?>" alt="<?php esc_attr_e('Hero fashion', 'fashionmen'); ?>" class="w-full h-full object-cover">

        <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
            <div class="text-center text-white px-4">
                <h1 class="text-4xl md:text-6xl mb-4 tracking-wide">
                    <?php
                    $hero_title = get_theme_mod('hero_title', 'ELEVATE YOUR STYLE');
                    echo esc_html($hero_title);
                    ?>
                </h1>
                <p class="text-lg md:text-xl mb-8 text-gray-200">
                    <?php
                    $hero_subtitle = get_theme_mod('hero_subtitle', 'Premium men\'s fashion crafted for the modern gentleman');
                    echo esc_html($hero_subtitle);
                    ?>
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <?php if (class_exists('WooCommerce')) : ?>
                        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="inline-block bg-white text-black px-8 py-3 rounded font-semibold hover:bg-gray-100 transition-colors">
                            <?php esc_html_e('Shop Collection', 'fashionmen'); ?>
                        </a>
                        <a href="<?php echo esc_url(home_url('/customize')); ?>" class="inline-block border border-white text-white px-8 py-3 rounded font-semibold hover:bg-white hover:text-black transition-colors">
                            <?php esc_html_e('Explore Customization', 'fashionmen'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <?php if (class_exists('WooCommerce')) : ?>
        <section class="categories-section py-12 md:py-16">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl md:text-4xl text-center mb-8 md:mb-12"><?php esc_html_e('Shop by Category', 'fashionmen'); ?></h2>

                <div class="categories-grid grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                    <?php
                    $categories = get_terms(array(
                        'taxonomy' => 'product_cat',
                        'hide_empty' => true,
                        'number' => 4,
                        'exclude' => array(get_option('default_product_cat')),
                    ));

                    if (!empty($categories) && !is_wp_error($categories)) :
                        foreach ($categories as $category) :
                            $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                            $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : wc_placeholder_img_src();
                    ?>
                            <div class="category-card cursor-pointer group overflow-hidden border-none shadow-lg hover:shadow-xl transition-all rounded-lg">
                                <a href="<?php echo esc_url(get_term_link($category)); ?>" class="block">
                                    <div class="relative aspect-square overflow-hidden">
                                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($category->name); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition-colors"></div>
                                        <div class="absolute inset-0 flex flex-col items-center justify-center text-white">
                                            <h3 class="text-xl md:text-2xl mb-2"><?php echo esc_html($category->name); ?></h3>
                                            <p class="text-sm text-gray-200"><?php echo esc_html($category->count) . ' ' . esc_html__('Items', 'fashionmen'); ?></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </section>

        <!-- Featured Products Section -->
        <section class="featured-products-section py-12 md:py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-8 md:mb-12">
                    <h2 class="text-3xl md:text-4xl mb-4"><?php esc_html_e('Featured Collection', 'fashionmen'); ?></h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        <?php esc_html_e('Discover our handpicked selection of premium pieces for the modern gentleman', 'fashionmen'); ?>
                    </p>
                </div>

                <div class="products grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
                    <?php
                    $args = array(
                        'post_type' => 'product',
                        'posts_per_page' => 4,
                        'meta_query' => array(
                            array(
                                'key' => '_featured',
                                'value' => 'yes'
                            )
                        )
                    );

                    $featured_query = new WP_Query($args);

                    if ($featured_query->have_posts()) :
                        while ($featured_query->have_posts()) : $featured_query->the_post();
                            wc_get_template_part('content', 'product');
                        endwhile;
                        wp_reset_postdata();
                    else :
                        echo '<p class="col-span-full text-center text-gray-600">' . esc_html__('No featured products found.', 'fashionmen') . '</p>';
                    endif;
                    ?>
                </div>

                <div class="text-center">
                    <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="inline-block border border-gray-900 text-gray-900 px-8 py-3 rounded font-semibold hover:bg-gray-900 hover:text-white transition-colors">
                        <?php esc_html_e('View All Products', 'fashionmen'); ?>
                    </a>
                </div>
            </div>
        </section>

        <!-- Sale Banner -->
        <section class="sale-banner-section py-16">
            <div class="container mx-auto px-4">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-12 text-center text-white">
                    <h2 class="text-4xl font-bold mb-4"><?php esc_html_e('Summer Sale', 'fashionmen'); ?></h2>
                    <p class="text-xl mb-8"><?php esc_html_e('Up to 50% off on selected items', 'fashionmen'); ?></p>
                    <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>?on_sale=true" class="inline-block bg-white text-blue-600 px-8 py-3 rounded font-semibold hover:bg-gray-100 transition-colors">
                        <?php esc_html_e('Shop Sale', 'fashionmen'); ?>
                    </a>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Newsletter Section -->
    <section class="newsletter-section py-16 bg-gray-900 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4"><?php esc_html_e('Stay Updated', 'fashionmen'); ?></h2>
            <p class="text-xl text-gray-300 mb-8"><?php esc_html_e('Subscribe to get special offers, free giveaways, and exclusive deals.', 'fashionmen'); ?></p>
            <form class="newsletter-form max-w-md mx-auto flex gap-2">
                <input type="email" placeholder="<?php esc_attr_e('Your email address', 'fashionmen'); ?>" class="flex-1 px-4 py-3 rounded bg-gray-800 border border-gray-700 focus:outline-none focus:border-gray-500" required>
                <button type="submit" class="bg-white text-black px-6 py-3 rounded font-semibold hover:bg-gray-200 transition-colors">
                    <?php esc_html_e('Subscribe', 'fashionmen'); ?>
                </button>
            </form>
        </div>
    </section>

</main><!-- #primary -->

<?php
get_footer();
