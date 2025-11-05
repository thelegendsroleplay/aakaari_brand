<?php
/**
 * Template Name: About Page
 * Template Post Type: page
 *
 * @package FashionMen
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php
    while (have_posts()) :
        the_post();
    ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class('about-page'); ?>>

            <!-- Hero Section -->
            <section class="about-hero py-12 md:py-16">
                <div class="container mx-auto px-4">
                    <div class="max-w-4xl mx-auto text-center">
                        <h1 class="text-4xl md:text-5xl font-bold mb-4"><?php the_title(); ?></h1>
                        <p class="text-xl text-gray-600">
                            <?php echo esc_html(get_theme_mod('about_subtitle', 'Redefining men\'s fashion with style, quality, and innovation since 2020')); ?>
                        </p>
                    </div>
                </div>
            </section>

            <!-- Main Content -->
            <section class="about-content py-16">
                <div class="container mx-auto px-4">
                    <div class="max-w-4xl mx-auto">
                        <div class="entry-content prose prose-lg max-w-none">
                            <?php
                            the_content();
                            ?>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Our Story Section -->
            <section class="our-story py-16 bg-gray-50">
                <div class="container mx-auto px-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                        <div>
                            <h2 class="text-3xl font-bold mb-6">Our Story</h2>
                            <p class="text-lg mb-4">Founded with a passion for men's fashion, we've been providing premium clothing and accessories to discerning gentlemen worldwide.</p>
                            <p class="text-lg mb-4">Our commitment to quality, style, and customer satisfaction has made us a trusted name in men's fashion.</p>
                            <p class="text-lg">Every piece in our collection is carefully curated to ensure it meets our high standards of craftsmanship and design.</p>
                        </div>
                        <div class="bg-gray-300 rounded-lg aspect-square flex items-center justify-center">
                            <span class="text-gray-500">Our Story Image</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Values Section -->
            <section class="values py-16">
                <div class="container mx-auto px-4">
                    <h2 class="text-3xl font-bold text-center mb-12">Our Values</h2>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div class="text-center p-6">
                            <div class="w-16 h-16 bg-black text-white rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-3">Quality First</h3>
                            <p class="text-sm text-gray-600">We source only the finest materials and work with skilled craftsmen</p>
                        </div>
                        <div class="text-center p-6">
                            <div class="w-16 h-16 bg-black text-white rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-3">Customer Focused</h3>
                            <p class="text-sm text-gray-600">Your satisfaction is our top priority in everything we do</p>
                        </div>
                        <div class="text-center p-6">
                            <div class="w-16 h-16 bg-black text-white rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-3">Innovation</h3>
                            <p class="text-sm text-gray-600">Pushing boundaries with customizable options and modern designs</p>
                        </div>
                        <div class="text-center p-6">
                            <div class="w-16 h-16 bg-black text-white rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-3">Sustainability</h3>
                            <p class="text-sm text-gray-600">Committed to ethical practices and environmental responsibility</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Stats Section -->
            <section class="stats py-16 bg-gray-50">
                <div class="container mx-auto px-4">
                    <div class="max-w-6xl mx-auto">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                            <div>
                                <p class="text-4xl md:text-5xl font-bold mb-2">50K+</p>
                                <p class="text-gray-600">Happy Customers</p>
                            </div>
                            <div>
                                <p class="text-4xl md:text-5xl font-bold mb-2">500+</p>
                                <p class="text-gray-600">Products</p>
                            </div>
                            <div>
                                <p class="text-4xl md:text-5xl font-bold mb-2">25+</p>
                                <p class="text-gray-600">Countries</p>
                            </div>
                            <div>
                                <p class="text-4xl md:text-5xl font-bold mb-2">4.8</p>
                                <p class="text-gray-600">Average Rating</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Team Section -->
            <section class="team py-16">
                <div class="container mx-auto px-4">
                    <h2 class="text-3xl font-bold text-center mb-12">Meet Our Team</h2>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <?php for ($i = 1; $i <= 4; $i++) : ?>
                            <div class="text-center">
                                <div class="bg-gray-300 rounded-lg aspect-square mb-4 flex items-center justify-center">
                                    <span class="text-gray-500">Team Member</span>
                                </div>
                                <h3 class="text-lg font-semibold mb-1">Team Member <?php echo $i; ?></h3>
                                <p class="text-gray-600 text-sm">Position</p>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="cta py-16 text-center">
                <div class="container mx-auto px-4">
                    <div class="max-w-4xl mx-auto">
                        <h2 class="text-3xl font-bold mb-4">Join the FashionMen Family</h2>
                        <p class="text-gray-600 mb-8">Experience the difference of premium, customizable men's fashion</p>
                        <?php if (class_exists('WooCommerce')) : ?>
                            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="inline-block bg-black text-white px-8 py-3 rounded font-semibold hover:bg-gray-900 transition-colors">
                                Shop Now
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

        </article>

    <?php endwhile; ?>

</main><!-- #primary -->

<?php
get_footer();
