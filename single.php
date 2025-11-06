<?php
/**
 * The template for displaying all single posts
 *
 * @package Aakaari_Brand
 */

get_header();
?>

<div class="site-container">
    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <?php
            while ( have_posts() ) :
                the_post();
                ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

                        <div class="entry-meta">
                            <span class="posted-on">
                                <?php echo get_the_date(); ?>
                            </span>
                            <span class="byline">
                                by <?php the_author(); ?>
                            </span>
                            <?php
                            $categories_list = get_the_category_list( ', ' );
                            if ( $categories_list ) :
                                ?>
                                <span class="cat-links">
                                    <?php echo $categories_list; ?>
                                </span>
                                <?php
                            endif;
                            ?>
                        </div>
                    </header>

                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="post-thumbnail">
                            <?php the_post_thumbnail( 'aakaari-large' ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="entry-content">
                        <?php
                        the_content();

                        wp_link_pages( array(
                            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aakaari-brand' ),
                            'after'  => '</div>',
                        ) );
                        ?>
                    </div>

                    <footer class="entry-footer">
                        <?php
                        $tags_list = get_the_tag_list( '', ', ' );
                        if ( $tags_list ) :
                            ?>
                            <span class="tags-links">
                                <strong><?php esc_html_e( 'Tags:', 'aakaari-brand' ); ?></strong>
                                <?php echo $tags_list; ?>
                            </span>
                            <?php
                        endif;
                        ?>
                    </footer>
                </article>

                <?php
                // Post navigation
                the_post_navigation( array(
                    'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'aakaari-brand' ) . '</span> <span class="nav-title">%title</span>',
                    'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'aakaari-brand' ) . '</span> <span class="nav-title">%title</span>',
                ) );

                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;

            endwhile;
            ?>

        </main>
    </div>

    <?php get_sidebar(); ?>
</div>

<?php
get_footer();
