<?php
/**
 * The main template file (Fallback)
 *
 * This is the template that is used by default if no more specific template exists.
 *
 * @package Aakaari Brand
 * @since 1.0.0
 */

get_header(); // Loads header.php
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php
        if ( have_posts() ) :
            // Start the Loop.
            while ( have_posts() ) :
                the_post();

                // Simple fallback content display
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                    </header>
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </article>
                <?php

            endwhile;

        else :

            echo '<p>No content found.</p>';

        endif;
        ?>

    </main></div><?php
get_footer(); // Loads footer.php