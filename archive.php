<?php
/**
 * The template for displaying archive pages
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
                    <?php
                    the_archive_title( '<h1 class="page-title">', '</h1>' );
                    the_archive_description( '<div class="archive-description">', '</div>' );
                    ?>
                </header>

                <?php
                // Start the Loop
                while ( have_posts() ) :
                    the_post();
                    ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <?php
                            the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );

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
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail( 'aakaari-featured' ); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="entry-content">
                            <?php the_excerpt(); ?>
                            <a href="<?php echo esc_url( get_permalink() ); ?>" class="button">
                                <?php esc_html_e( 'Read More', 'aakaari-brand' ); ?>
                            </a>
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
                        <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'aakaari-brand' ); ?></p>
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
