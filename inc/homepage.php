<?php
/**
 * Homepage Functions
 * Contains all functions for rendering homepage sections
 *
 * @package FashionMen
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Hero Section
 * Displays the main hero banner with image, heading, subtitle, and CTA buttons
 */
function fashionmen_hero_section() {
    // Get hero image from customizer or use default
    $hero_image = get_theme_mod('fashionmen_hero_image', 'https://images.unsplash.com/photo-1641736755184-67380b9a002c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwZmFzaGlvbiUyMG1vZGVsfGVufDF8fHx8MTc2MjIzODI1Nnww&ixlib=rb-4.1.0&q=80&w=1080');
    $hero_title = get_theme_mod('fashionmen_hero_title', 'ELEVATE YOUR STYLE');
    $hero_subtitle = get_theme_mod('fashionmen_hero_subtitle', 'Premium men\'s fashion crafted for the modern gentleman');
    $shop_button_text = get_theme_mod('fashionmen_shop_button_text', 'Shop Collection');
    $custom_button_text = get_theme_mod('fashionmen_custom_button_text', 'Explore Customization');

    // Get shop page URL
    $shop_url = class_exists('WooCommerce') ? get_permalink(wc_get_page_id('shop')) : home_url('/shop');
    $custom_url = home_url('/customize');
    ?>

    <div class="hero-wrapper relative">
        <img src="<?php echo esc_url($hero_image); ?>"
             alt="<?php echo esc_attr($hero_title); ?>"
             class="hero-image">

        <div class="hero-overlay">
            <div class="hero-content">
                <h1 class="hero-title"><?php echo esc_html($hero_title); ?></h1>
                <p class="hero-subtitle"><?php echo esc_html($hero_subtitle); ?></p>
                <div class="hero-buttons">
                    <a href="<?php echo esc_url($shop_url); ?>" class="btn btn-primary">
                        <?php echo esc_html($shop_button_text); ?>
                    </a>
                    <a href="<?php echo esc_url($custom_url); ?>" class="btn btn-outline">
                        <?php echo esc_html($custom_button_text); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php
}

/**
 * Categories Section
 * Displays product categories in a grid layout
 */
function fashionmen_categories_section() {
    $section_title = get_theme_mod('fashionmen_categories_title', 'Shop by Category');

    // Define default categories with placeholder images
    $default_categories = array(
        array(
            'name' => 'Jackets',
            'slug' => 'jackets',
            'image' => 'https://images.unsplash.com/photo-1634136912882-61fd36144a3a?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwamFja2V0JTIwY2FzdWFsfGVufDF8fHx8MTc2MjI0NTE0NXww&ixlib=rb-4.1.0&q=80&w=1080',
            'count' => 45,
        ),
        array(
            'name' => 'Shirts',
            'slug' => 'shirts',
            'image' => 'https://images.unsplash.com/photo-1661802365632-bb2b2f68eb51?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwc2hpcnQlMjBmYXNoaW9ufGVufDF8fHx8MTc2MjI0NTE0NXww&ixlib=rb-4.1.0&q=80&w=1080',
            'count' => 67,
        ),
        array(
            'name' => 'Shoes',
            'slug' => 'shoes',
            'image' => 'https://images.unsplash.com/photo-1624006389438-c03488175975?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwc25lYWtlcnMlMjBzaG9lc3xlbnwxfHx8fDE3NjIxNzE4OTR8MA&ixlib=rb-4.1.0&q=80&w=1080',
            'count' => 34,
        ),
        array(
            'name' => 'Accessories',
            'slug' => 'accessories',
            'image' => 'https://images.unsplash.com/photo-1706892807280-f8648dda29ef?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwYWNjZXNzb3JpZXMlMjB3YXRjaHxlbnwxfHx8fDE3NjIxNzUwNjZ8MA&ixlib=rb-4.1.0&q=80&w=1080',
            'count' => 28,
        ),
    );

    // If WooCommerce is active, try to get real categories
    if (class_exists('WooCommerce')) {
        $categories = array();
        $product_categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'number' => 4,
            'exclude' => array(get_option('default_product_cat')),
        ));

        if (!empty($product_categories) && !is_wp_error($product_categories)) {
            foreach ($product_categories as $index => $category) {
                $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                $image = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : $default_categories[$index]['image'];

                $categories[] = array(
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'url' => get_term_link($category),
                    'image' => $image,
                    'count' => $category->count,
                );
            }
        }
    }

    // Use default categories if none found
    if (empty($categories)) {
        $categories = $default_categories;
        // Add URLs for default categories
        foreach ($categories as &$cat) {
            $cat['url'] = home_url('/shop');
        }
    }
    ?>

    <div class="categories-wrapper">
        <div class="container">
            <h2 class="section-title"><?php echo esc_html($section_title); ?></h2>

            <div class="categories-grid">
                <?php foreach ($categories as $category) : ?>
                    <a href="<?php echo esc_url($category['url']); ?>" class="category-card">
                        <div class="category-image-wrapper">
                            <img src="<?php echo esc_url($category['image']); ?>"
                                 alt="<?php echo esc_attr($category['name']); ?>"
                                 class="category-image">
                            <div class="category-overlay"></div>
                            <div class="category-content">
                                <h3 class="category-name"><?php echo esc_html($category['name']); ?></h3>
                                <p class="category-count"><?php echo esc_html($category['count']); ?> Items</p>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php
}

/**
 * Featured Products Section
 * Displays featured WooCommerce products
 */
function fashionmen_featured_products_section() {
    $section_title = get_theme_mod('fashionmen_featured_title', 'Featured Collection');
    $section_subtitle = get_theme_mod('fashionmen_featured_subtitle', 'Discover our handpicked selection of premium pieces for the modern gentleman');
    $button_text = get_theme_mod('fashionmen_featured_button_text', 'View All Products');

    // Get shop URL
    $shop_url = class_exists('WooCommerce') ? get_permalink(wc_get_page_id('shop')) : home_url('/shop');

    // Get featured products if WooCommerce is active
    $products = array();
    if (class_exists('WooCommerce')) {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 4,
            'meta_query' => array(
                array(
                    'key' => '_featured',
                    'value' => 'yes'
                )
            ),
        );

        $featured_query = new WP_Query($args);

        if ($featured_query->have_posts()) {
            while ($featured_query->have_posts()) {
                $featured_query->the_post();
                global $product;

                $products[] = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'url' => get_permalink(),
                    'image' => get_the_post_thumbnail_url(get_the_ID(), 'medium'),
                    'price' => $product->get_price_html(),
                );
            }
            wp_reset_postdata();
        }
    }

    // Show placeholder message if no products
    $has_products = !empty($products);
    ?>

    <div class="featured-products-wrapper">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php echo esc_html($section_title); ?></h2>
                <p class="section-subtitle"><?php echo esc_html($section_subtitle); ?></p>
            </div>

            <?php if ($has_products) : ?>
                <div class="products-grid">
                    <?php foreach ($products as $product) : ?>
                        <div class="product-card">
                            <a href="<?php echo esc_url($product['url']); ?>" class="product-link">
                                <?php if ($product['image']) : ?>
                                    <img src="<?php echo esc_url($product['image']); ?>"
                                         alt="<?php echo esc_attr($product['title']); ?>"
                                         class="product-image">
                                <?php else : ?>
                                    <div class="product-placeholder"></div>
                                <?php endif; ?>
                                <div class="product-info">
                                    <h3 class="product-title"><?php echo esc_html($product['title']); ?></h3>
                                    <div class="product-price"><?php echo wp_kses_post($product['price']); ?></div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="section-footer">
                    <a href="<?php echo esc_url($shop_url); ?>" class="btn btn-outline-large">
                        <?php echo esc_html($button_text); ?>
                    </a>
                </div>
            <?php else : ?>
                <div class="no-products-message">
                    <p><?php esc_html_e('No featured products found. Please add products and mark them as featured in WooCommerce.', 'fashionmen'); ?></p>
                    <?php if (current_user_can('manage_options')) : ?>
                        <a href="<?php echo admin_url('edit.php?post_type=product'); ?>" class="btn btn-primary">
                            <?php esc_html_e('Add Products', 'fashionmen'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php
}
