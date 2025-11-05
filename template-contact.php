<?php
/**
 * Template Name: Contact Page
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

        <article id="post-<?php the_ID(); ?>" <?php post_class('contact-page'); ?>>

            <!-- Header -->
            <section class="page-header bg-gray-50 py-12">
                <div class="container mx-auto px-4 text-center">
                    <h1 class="text-4xl font-bold mb-4"><?php the_title(); ?></h1>
                    <p class="text-xl text-gray-600">We'd love to hear from you</p>
                </div>
            </section>

            <!-- Contact Content -->
            <section class="contact-content py-16">
                <div class="container mx-auto px-4">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

                        <!-- Contact Form -->
                        <div>
                            <h2 class="text-2xl font-bold mb-6">Send Us a Message</h2>

                            <?php
                            // Check if form was submitted
                            if (isset($_POST['fashionmen_contact_submit']) && wp_verify_nonce($_POST['fashionmen_contact_nonce'], 'fashionmen_contact_form')) {
                                // Process form (in a real implementation, you'd send an email here)
                                echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                                    <p class="font-semibold">Thank you for your message!</p>
                                    <p>We\'ll get back to you as soon as possible.</p>
                                </div>';
                            }
                            ?>

                            <form method="post" action="" class="contact-form space-y-6">
                                <?php wp_nonce_field('fashionmen_contact_form', 'fashionmen_contact_nonce'); ?>

                                <div>
                                    <label for="contact-name" class="block text-sm font-semibold mb-2">Name *</label>
                                    <input type="text" id="contact-name" name="contact_name" required class="w-full px-4 py-3 border border-gray-300 rounded focus:outline-none focus:border-black">
                                </div>

                                <div>
                                    <label for="contact-email" class="block text-sm font-semibold mb-2">Email *</label>
                                    <input type="email" id="contact-email" name="contact_email" required class="w-full px-4 py-3 border border-gray-300 rounded focus:outline-none focus:border-black">
                                </div>

                                <div>
                                    <label for="contact-phone" class="block text-sm font-semibold mb-2">Phone</label>
                                    <input type="tel" id="contact-phone" name="contact_phone" class="w-full px-4 py-3 border border-gray-300 rounded focus:outline-none focus:border-black">
                                </div>

                                <div>
                                    <label for="contact-subject" class="block text-sm font-semibold mb-2">Subject *</label>
                                    <input type="text" id="contact-subject" name="contact_subject" required class="w-full px-4 py-3 border border-gray-300 rounded focus:outline-none focus:border-black">
                                </div>

                                <div>
                                    <label for="contact-message" class="block text-sm font-semibold mb-2">Message *</label>
                                    <textarea id="contact-message" name="contact_message" rows="6" required class="w-full px-4 py-3 border border-gray-300 rounded focus:outline-none focus:border-black"></textarea>
                                </div>

                                <button type="submit" name="fashionmen_contact_submit" class="button w-full">
                                    Send Message
                                </button>
                            </form>
                        </div>

                        <!-- Contact Information -->
                        <div>
                            <h2 class="text-2xl font-bold mb-6">Get In Touch</h2>

                            <div class="entry-content prose prose-lg mb-8">
                                <?php the_content(); ?>
                            </div>

                            <div class="space-y-6">
                                <!-- Address -->
                                <div class="flex items-start">
                                    <div class="w-12 h-12 bg-black text-white rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="font-semibold mb-1">Address</h3>
                                        <p class="text-gray-600">123 Fashion Street<br>New York, NY 10001<br>United States</p>
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="flex items-start">
                                    <div class="w-12 h-12 bg-black text-white rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="font-semibold mb-1">Phone</h3>
                                        <p class="text-gray-600">+1 (555) 123-4567</p>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="flex items-start">
                                    <div class="w-12 h-12 bg-black text-white rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="font-semibold mb-1">Email</h3>
                                        <p class="text-gray-600">info@fashionmen.com</p>
                                    </div>
                                </div>

                                <!-- Hours -->
                                <div class="flex items-start">
                                    <div class="w-12 h-12 bg-black text-white rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="font-semibold mb-1">Business Hours</h3>
                                        <p class="text-gray-600">Monday - Friday: 9:00 AM - 6:00 PM<br>Saturday: 10:00 AM - 4:00 PM<br>Sunday: Closed</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <!-- Map Section (Placeholder) -->
            <section class="map-section">
                <div class="bg-gray-300 w-full h-96 flex items-center justify-center">
                    <span class="text-gray-500 text-xl">Map Placeholder</span>
                </div>
            </section>

        </article>

    <?php endwhile; ?>

</main><!-- #primary -->

<?php
get_footer();
