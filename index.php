<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 *
 * @package Aakaari_Brand
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="page-container">
        <?php
        if ( have_posts() ) :
            if ( is_home() && ! is_front_page() ) :
                ?>
                <header>
                    <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                </header>
                <?php
            endif;

            /* Start the Loop */
            while ( have_posts() ) :
                the_post();
                get_template_part( 'template-parts/content', get_post_type() );
            endwhile;

            the_posts_navigation();

        else :
            get_template_part( 'template-parts/content', 'none' );
        endif;
        ?>
    </div>
</main><!-- #primary -->

<?php
get_footer();
