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
    <section class="hero-section relative bg-gray-100 py-20 md:py-32">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div class="hero-content">
                    <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                        <?php
                        $hero_title = get_theme_mod('hero_title', 'Elevate Your Style');
                        echo esc_html($hero_title);
                        ?>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8">
                        <?php
                        $hero_subtitle = get_theme_mod('hero_subtitle', 'Discover premium men\'s fashion for the modern gentleman. Timeless pieces, contemporary designs.');
                        echo esc_html($hero_subtitle);
                        ?>
                    </p>
                    <div class="hero-buttons flex gap-4">
                        <?php if (class_exists('WooCommerce')) : ?>
                            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="button">
                                <?php esc_html_e('Shop Now', 'fashionmen'); ?>
                            </a>
                            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>?on_sale=true" class="button-outline">
                                <?php esc_html_e('View Sale', 'fashionmen'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="hero-image">
                    <?php
                    $hero_image = get_theme_mod('hero_image');
                    if ($hero_image) :
                        echo '<img src="' . esc_url($hero_image) . '" alt="' . esc_attr__('Hero Image', 'fashionmen') . '" class="rounded-lg shadow-2xl">';
                    else :
                        echo '<div class="bg-gray-300 rounded-lg aspect-square flex items-center justify-center">
                            <span class="text-gray-500">' . esc_html__('Hero Image', 'fashionmen') . '</span>
                        </div>';
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <?php if (class_exists('WooCommerce')) : ?>
        <section class="categories-section py-16">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12"><?php esc_html_e('Shop by Category', 'fashionmen'); ?></h2>

                <div class="categories-grid grid grid-cols-2 md:grid-cols-4 gap-6">
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
                            <a href="<?php echo esc_url(get_term_link($category)); ?>" class="category-card group">
                                <div class="category-image relative overflow-hidden rounded-lg mb-3">
                                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($category->name); ?>" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">
                                    <div class="absolute inset-0 bg-black bg-opacity-20 group-hover:bg-opacity-30 transition-all"></div>
                                </div>
                                <h3 class="text-lg font-semibold text-center"><?php echo esc_html($category->name); ?></h3>
                                <p class="text-sm text-gray-600 text-center"><?php echo esc_html($category->count) . ' ' . esc_html__('Products', 'fashionmen'); ?></p>
                            </a>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </section>

        <!-- Featured Products Section -->
        <section class="featured-products-section py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center mb-12">
                    <h2 class="text-3xl font-bold"><?php esc_html_e('Featured Products', 'fashionmen'); ?></h2>
                    <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="button-outline">
                        <?php esc_html_e('View All', 'fashionmen'); ?>
                    </a>
                </div>

                <div class="products grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
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
