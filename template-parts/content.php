<?php
/**
 * Template part for displaying posts
 *
 * @package FashionMen
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow'); ?>>

    <?php if (has_post_thumbnail()) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('large', array('class' => 'w-full h-64 object-cover')); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="post-content p-6">

        <header class="entry-header mb-4">
            <?php
            if (is_singular()) :
                the_title('<h1 class="entry-title text-3xl font-bold mb-4">', '</h1>');
            else :
                the_title('<h2 class="entry-title text-2xl font-bold mb-2"><a href="' . esc_url(get_permalink()) . '" class="hover:text-gray-600">', '</a></h2>');
            endif;
            ?>

            <div class="entry-meta flex flex-wrap gap-4 text-sm text-gray-600">
                <?php
                fashionmen_posted_on();
                fashionmen_posted_by();
                ?>
            </div>
        </header>

        <div class="entry-content prose max-w-none">
            <?php
            if (is_singular()) {
                the_content(sprintf(
                    wp_kses(
                        __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'fashionmen'),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    wp_kses_post(get_the_title())
                ));

                wp_link_pages(array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'fashionmen'),
                    'after'  => '</div>',
                ));
            } else {
                the_excerpt();
                ?>
                <a href="<?php the_permalink(); ?>" class="inline-block mt-4 button">
                    <?php esc_html_e('Read More', 'fashionmen'); ?>
                </a>
                <?php
            }
            ?>
        </div>

        <?php if (is_singular()) : ?>
            <footer class="entry-footer mt-6 pt-6 border-t border-gray-200">
                <?php fashionmen_entry_categories(); ?>
                <?php fashionmen_entry_tags(); ?>
            </footer>
        <?php endif; ?>

    </div>

</article>
