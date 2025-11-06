<?php
/**
 * The template for displaying search results pages
 *
 * @package Aakaari_Brand
 */

get_header();
?>

<div class="site-container">
    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <?php if ( have_posts() ) : ?>

                <header class="page-header">
                    <h1 class="page-title">
                        <?php
                        printf(
                            esc_html__( 'Search Results for: %s', 'aakaari-brand' ),
                            '<span>' . get_search_query() . '</span>'
                        );
                        ?>
                    </h1>
                </header>

                <?php
                // Start the Loop
                while ( have_posts() ) :
                    the_post();
                    ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>

                            <?php if ( 'post' === get_post_type() ) : ?>
                                <div class="entry-meta">
                                    <span class="posted-on">
                                        <?php echo get_the_date(); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </header>

                        <div class="entry-content">
                            <?php the_excerpt(); ?>
                        </div>
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
                        <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', 'aakaari-brand' ); ?></p>
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
