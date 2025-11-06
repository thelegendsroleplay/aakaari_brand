<?php
/**
 * Template Name: About Page
 */

get_header();
?>

<div class="about-page">
    <div class="about-container" style="max-width: 1280px; margin: 0 auto; padding: 3rem 1rem;">
        <section class="hero-about" style="text-align: center; padding: 3rem 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 1rem; color: white; margin-bottom: 3rem;">
            <h1 style="font-size: 3rem; margin: 0 0 1rem; font-weight: 700;">About <?php bloginfo('name'); ?></h1>
            <p style="font-size: 1.25rem; margin: 0;">Premium streetwear for the modern lifestyle</p>
        </section>

        <section class="about-content">
            <div class="content-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <div class="content-block" style="background: white; padding: 2rem; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                    <h2 style="font-size: 1.5rem; margin: 0 0 1rem; font-weight: 600;">Our Story</h2>
                    <p style="color: #666; line-height: 1.6;">Founded in 2020, <?php bloginfo('name'); ?> has been providing premium quality streetwear for discerning individuals worldwide. Our commitment to quality, style, and customer satisfaction sets us apart.</p>
                </div>
                <div class="content-block" style="background: white; padding: 2rem; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                    <h2 style="font-size: 1.5rem; margin: 0 0 1rem; font-weight: 600;">Our Mission</h2>
                    <p style="color: #666; line-height: 1.6;">To provide timeless, high-quality fashion that empowers people to look and feel their best, combining classic style with modern design.</p>
                </div>
                <div class="content-block" style="background: white; padding: 2rem; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                    <h2 style="font-size: 1.5rem; margin: 0 0 1rem; font-weight: 600;">Quality Promise</h2>
                    <p style="color: #666; line-height: 1.6;">Every piece in our collection is carefully selected and crafted using premium materials and ethical manufacturing practices.</p>
                </div>
            </div>
        </section>

        <?php
        while ( have_posts() ) :
            the_post();
            if ( get_the_content() ) :
        ?>
            <div class="page-content" style="margin-top: 3rem; background: white; padding: 2rem; border-radius: 0.75rem;">
                <?php the_content(); ?>
            </div>
        <?php
            endif;
        endwhile;
        ?>
    </div>
</div>

<?php
get_footer();
