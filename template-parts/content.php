<?php
/**
 * Template part for displaying posts
 *
 * @package Aakaari_Brand
 */
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
                <span class="posted-on"><?php echo get_the_date(); ?></span>
                <span class="byline"> by <?php the_author(); ?></span>
            </div>
        <?php endif; ?>
    </header>

    <?php if ( has_post_thumbnail() ) : ?>
        <div class="post-thumbnail">
            <?php the_post_thumbnail( 'large' ); ?>
        </div>
    <?php endif; ?>

    <div class="entry-content">
        <?php
        if ( is_singular() ) :
            the_content();
        else :
            the_excerpt();
        endif;
        ?>
    </div>

    <?php if ( ! is_singular() ) : ?>
        <footer class="entry-footer">
            <a href="<?php echo esc_url( get_permalink() ); ?>" class="read-more">
                <?php esc_html_e( 'Read More', 'aakaari-brand' ); ?>
            </a>
        </footer>
    <?php endif; ?>
</article>
