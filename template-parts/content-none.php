<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @package FashionMen
 * @since 1.0.0
 */
?>

<section class="no-results not-found text-center py-12">

    <header class="page-header mb-8">
        <h1 class="page-title text-3xl font-bold"><?php esc_html_e('Nothing Found', 'fashionmen'); ?></h1>
    </header>

    <div class="page-content">
        <?php if (is_home() && current_user_can('publish_posts')) : ?>

            <p class="text-xl text-gray-600 mb-6">
                <?php
                printf(
                    wp_kses(
                        __('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'fashionmen'),
                        array(
                            'a' => array(
                                'href' => array(),
                            ),
                        )
                    ),
                    esc_url(admin_url('post-new.php'))
                );
                ?>
            </p>

        <?php elseif (is_search()) : ?>

            <p class="text-xl text-gray-600 mb-6"><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'fashionmen'); ?></p>
            <?php get_search_form(); ?>

        <?php else : ?>

            <p class="text-xl text-gray-600 mb-6"><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'fashionmen'); ?></p>
            <?php get_search_form(); ?>

        <?php endif; ?>
    </div>

</section>
