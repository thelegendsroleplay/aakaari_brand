<?php
/**
 * The main template file
 *
 * @package Aakaari_Brand
 */

get_header();
?>

<div class="site-container">
    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <?php
            if ( have_posts() ) :

                // Check if it's the blog index
                if ( is_home() && ! is_front_page() ) :
                    ?>
                    <header class="page-header">
                        <h1 class="page-title"><?php single_post_title(); ?></h1>
                    </header>
                    <?php
                endif;

                // Start the Loop
                while ( have_posts() ) :
                    the_post();
                    ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <?php
                            if ( is_singular() ) :
                                the_title( '<h1 class="entry-title">', '</h1>' );
                            else :
                                the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
                            endif;

                            if ( 'post' === get_post_type() ) :
                                ?>
                                <div class="entry-meta">
                                    <span class="posted-on">
                                        <?php echo get_the_date(); ?>
                                    </span>
                                    <span class="byline">
                                        by <?php the_author(); ?>
                                    </span>
                                </div>
                                <?php
                            endif;
                            ?>
                        </header>

                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="post-thumbnail">
                                <?php the_post_thumbnail( 'aakaari-featured' ); ?>
                            </div>
                        <?php endif; ?>

                        <div class="entry-content">
                            <?php
                            if ( is_singular() ) :
                                the_content();

                                wp_link_pages( array(
                                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aakaari-brand' ),
                                    'after'  => '</div>',
                                ) );
                            else :
                                the_excerpt();
                                ?>
                                <a href="<?php echo esc_url( get_permalink() ); ?>" class="button">
                                    <?php esc_html_e( 'Read More', 'aakaari-brand' ); ?>
                                </a>
                                <?php
                            endif;
                            ?>
                        </div>

                        <footer class="entry-footer">
                            <?php
                            if ( 'post' === get_post_type() ) :
                                $categories_list = get_the_category_list( ', ' );
                                if ( $categories_list ) :
                                    ?>
                                    <span class="cat-links">
                                        <?php echo $categories_list; ?>
                                    </span>
                                    <?php
                                endif;

                                $tags_list = get_the_tag_list( '', ', ' );
                                if ( $tags_list ) :
                                    ?>
                                    <span class="tags-links">
                                        <?php echo $tags_list; ?>
                                    </span>
                                    <?php
                                endif;
                            endif;
                            ?>
                        </footer>
                    </article>

                    <?php
                endwhile;

                // Pagination
                the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => __( '&laquo; Previous', 'aakaari-brand' ),
                    'next_text' => __( 'Next &raquo;', 'aakaari-brand' ),
                ) );

            else :
                ?>
                <section class="no-results not-found">
                    <header class="page-header">
                        <h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'aakaari-brand' ); ?></h1>
                    </header>

                    <div class="page-content">
                        <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'aakaari-brand' ); ?></p>
                        <?php get_search_form(); ?>
                    </div>
                </section>
                <?php
            endif;
            ?>

        </main>
    </div>

    <?php get_sidebar(); ?>
</div>

<?php
get_footer();
