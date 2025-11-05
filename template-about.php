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
            <section class="about-hero bg-gradient-to-r from-gray-900 to-gray-800 text-white py-20">
                <div class="container mx-auto px-4 text-center">
                    <h1 class="text-5xl font-bold mb-4"><?php the_title(); ?></h1>
                    <p class="text-xl max-w-3xl mx-auto"><?php echo esc_html(get_bloginfo('description')); ?></p>
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
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="text-center p-6">
                            <div class="w-16 h-16 bg-black text-white rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-3">Quality First</h3>
                            <p class="text-gray-600">We never compromise on the quality of our products. Every item is made to last.</p>
                        </div>
                        <div class="text-center p-6">
                            <div class="w-16 h-16 bg-black text-white rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-3">Timeless Style</h3>
                            <p class="text-gray-600">Classic designs that never go out of fashion, combined with contemporary trends.</p>
                        </div>
                        <div class="text-center p-6">
                            <div class="w-16 h-16 bg-black text-white rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-3">Customer Focus</h3>
                            <p class="text-gray-600">Your satisfaction is our priority. We're here to ensure you have the best experience.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Team Section -->
            <section class="team py-16 bg-gray-50">
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
            <section class="cta py-16 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-center">
                <div class="container mx-auto px-4">
                    <h2 class="text-3xl font-bold mb-4">Ready to Elevate Your Style?</h2>
                    <p class="text-xl mb-8">Explore our collection and find the perfect pieces for your wardrobe.</p>
                    <?php if (class_exists('WooCommerce')) : ?>
                        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="inline-block bg-white text-blue-600 px-8 py-3 rounded font-semibold hover:bg-gray-100 transition-colors">
                            Shop Now
                        </a>
                    <?php endif; ?>
                </div>
            </section>

        </article>

    <?php endwhile; ?>

</main><!-- #primary -->

<?php
get_footer();
