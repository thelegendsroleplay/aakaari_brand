<?php
/**
 * The main template file
 *
 * @package FashionMen
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container" style="padding: 3rem 1rem; text-align: center;">
        <h1><?php esc_html_e('Welcome to FashionMen', 'fashionmen'); ?></h1>
        <p><?php esc_html_e('To use the homepage template, create a new page and select "Homepage" from the Template dropdown in the Page Attributes section.', 'fashionmen'); ?></p>
    </div>
</main>

<?php
get_footer();
