<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Aakaari_Brand
 */

get_header();
?>

<div class="site-container">
    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <section class="error-404 not-found">
                <header class="page-header">
                    <h1 class="page-title"><?php esc_html_e( '404 - Page Not Found', 'aakaari-brand' ); ?></h1>
                </header>

                <div class="page-content">
                    <p><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aakaari-brand' ); ?></p>
                    <p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'aakaari-brand' ); ?></p>

                    <?php get_search_form(); ?>

                    <div class="error-404-widgets mt-2">
                        <div class="widget-column">
                            <h2><?php esc_html_e( 'Recent Posts', 'aakaari-brand' ); ?></h2>
                            <ul>
                                <?php
                                $recent_posts = wp_get_recent_posts( array(
                                    'numberposts' => 5,
                                    'post_status' => 'publish',
                                ) );

                                foreach ( $recent_posts as $post ) :
                                    ?>
                                    <li>
                                        <a href="<?php echo esc_url( get_permalink( $post['ID'] ) ); ?>">
                                            <?php echo esc_html( $post['post_title'] ); ?>
                                        </a>
                                    </li>
                                    <?php
                                endforeach;
                                wp_reset_postdata();
                                ?>
                            </ul>
                        </div>

                        <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                            <div class="widget-column">
                                <h2><?php esc_html_e( 'Popular Products', 'aakaari-brand' ); ?></h2>
                                <?php
                                $args = array(
                                    'post_type'      => 'product',
                                    'posts_per_page' => 5,
                                    'orderby'        => 'popularity',
                                );
                                $popular_products = new WP_Query( $args );

                                if ( $popular_products->have_posts() ) :
                                    echo '<ul>';
                                    while ( $popular_products->have_posts() ) :
                                        $popular_products->the_post();
                                        ?>
                                        <li>
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_title(); ?>
                                            </a>
                                        </li>
                                        <?php
                                    endwhile;
                                    echo '</ul>';
                                    wp_reset_postdata();
                                endif;
                                ?>
                            </div>
                        <?php endif; ?>

                        <div class="widget-column">
                            <h2><?php esc_html_e( 'Categories', 'aakaari-brand' ); ?></h2>
                            <ul>
                                <?php
                                wp_list_categories( array(
                                    'orderby'    => 'count',
                                    'order'      => 'DESC',
                                    'show_count' => 1,
                                    'title_li'   => '',
                                    'number'     => 10,
                                ) );
                                ?>
                            </ul>
                        </div>
                    </div>

                    <div class="text-center mt-2">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button">
                            <?php esc_html_e( 'Return to Homepage', 'aakaari-brand' ); ?>
                        </a>
                    </div>
                </div>
            </section>

        </main>
    </div>
</div>

<?php
get_footer();
