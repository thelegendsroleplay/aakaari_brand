<?php
/**
 * The main template file
 *
 * @package FashionMen
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main container mx-auto px-4 py-8">

    <?php if (have_posts()) : ?>

        <?php if (is_home() && !is_front_page()) : ?>
            <header class="page-header mb-8">
                <h1 class="page-title text-3xl font-bold"><?php single_post_title(); ?></h1>
            </header>
        <?php endif; ?>

        <div class="posts-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            // Start the Loop.
            while (have_posts()) :
                the_post();
                get_template_part('template-parts/content', get_post_type());
            endwhile;
            ?>
        </div>

        <?php
        // Pagination
        the_posts_pagination(array(
            'mid_size'  => 2,
            'prev_text' => __('← Previous', 'fashionmen'),
            'next_text' => __('Next →', 'fashionmen'),
        ));
        ?>

    <?php else : ?>

        <?php get_template_part('template-parts/content', 'none'); ?>

    <?php endif; ?>

</main><!-- #primary -->

<?php
get_footer();
