<?php
/**
 * The main template file
 *
 * @package Aakaari_Brand
 */

get_header();
?>

<main id="main" class="site-main">
    <div class="container">
        <?php
        if (have_posts()) :
            while (have_posts()) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <h1 class="entry-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h1>
                    </header>

                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </article>
                <?php
            endwhile;

            // Pagination
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => __('&laquo; Previous', 'aakaari-brand'),
                'next_text' => __('Next &raquo;', 'aakaari-brand'),
            ));
        else :
            ?>
            <p><?php _e('No content found.', 'aakaari-brand'); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
