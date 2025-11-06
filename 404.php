<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Aakaari_Brand
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="page-container">
        <section class="error-404 not-found" style="padding: 80px 0; text-align: center;">
            <header class="page-header">
                <h1 class="page-title" style="font-size: 72px; font-weight: 700; margin-bottom: 16px;">
                    <?php esc_html_e( '404', 'aakaari-brand' ); ?>
                </h1>
                <p style="font-size: 24px; color: #666666; margin-bottom: 32px;">
                    <?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aakaari-brand' ); ?>
                </p>
            </header>

            <div class="page-content">
                <p style="margin-bottom: 32px;">
                    <?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search or go back to the home page?', 'aakaari-brand' ); ?>
                </p>

                <div style="max-width: 400px; margin: 0 auto 32px;">
                    <?php get_search_form(); ?>
                </div>

                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button">
                    <?php esc_html_e( 'Go to Home Page', 'aakaari-brand' ); ?>
                </a>
            </div>
        </section>
    </div>
</main>

<?php
get_footer();
