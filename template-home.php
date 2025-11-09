<?php
/**
 * Template Name: Home Page
 * Template Post Type: page
 *
 * @package Aakaari_Brand
 */

get_header();
?>

<main id="main" class="site-main home-page">
    <div class="container">
        <?php
        while (have_posts()) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <!-- Add your custom home page sections here -->
                <section class="home-hero">
                    <h1>Welcome to Aakaari Brand</h1>
                </section>

                <?php if (is_woocommerce_activated()) : ?>
                    <section class="home-products">
                        <h2>Featured Products</h2>
                        <?php
                        // You can add WooCommerce product shortcodes or custom queries here
                        // Example: echo do_shortcode('[featured_products limit="4"]');
                        ?>
                    </section>
                <?php endif; ?>
            </article>
            <?php
        endwhile;
        ?>
    </div>
</main>

<?php
get_footer();
