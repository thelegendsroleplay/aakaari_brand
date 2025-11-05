<?php
/**
 * Template Name: FAQ Page
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

        <article id="post-<?php the_ID(); ?>" <?php post_class('faq-page'); ?>>

            <!-- Header -->
            <section class="page-header bg-gray-50 py-12">
                <div class="container mx-auto px-4 text-center">
                    <h1 class="text-4xl font-bold mb-4"><?php the_title(); ?></h1>
                    <p class="text-xl text-gray-600">Find answers to common questions</p>
                </div>
            </section>

            <!-- FAQ Content -->
            <section class="faq-content py-16">
                <div class="container mx-auto px-4">
                    <div class="max-w-4xl mx-auto">

                        <div class="entry-content prose prose-lg mb-12">
                            <?php the_content(); ?>
                        </div>

                        <!-- FAQ Categories -->
                        <div class="space-y-12">

                            <!-- Orders & Shipping -->
                            <div class="faq-category">
                                <h2 class="text-2xl font-bold mb-6 pb-3 border-b-2 border-black">Orders & Shipping</h2>
                                <div class="space-y-4">
                                    <div class="faq-item border border-gray-200 rounded-lg">
                                        <button class="faq-question w-full text-left p-6 font-semibold flex justify-between items-center hover:bg-gray-50" onclick="this.parentElement.classList.toggle('active')">
                                            <span>How long does shipping take?</span>
                                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="faq-answer hidden p-6 pt-0 text-gray-600">
                                            <p>Standard shipping typically takes 5-7 business days. Express shipping is available and takes 2-3 business days. International shipping times vary by location but generally take 7-14 business days.</p>
                                        </div>
                                    </div>

                                    <div class="faq-item border border-gray-200 rounded-lg">
                                        <button class="faq-question w-full text-left p-6 font-semibold flex justify-between items-center hover:bg-gray-50" onclick="this.parentElement.classList.toggle('active')">
                                            <span>Do you offer free shipping?</span>
                                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="faq-answer hidden p-6 pt-0 text-gray-600">
                                            <p>Yes! We offer free standard shipping on all orders over $100. Express shipping is available for an additional fee.</p>
                                        </div>
                                    </div>

                                    <div class="faq-item border border-gray-200 rounded-lg">
                                        <button class="faq-question w-full text-left p-6 font-semibold flex justify-between items-center hover:bg-gray-50" onclick="this.parentElement.classList.toggle('active')">
                                            <span>Can I track my order?</span>
                                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="faq-answer hidden p-6 pt-0 text-gray-600">
                                            <p>Absolutely! Once your order ships, you'll receive a tracking number via email. You can use this number to track your package on our website or the carrier's website.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Returns & Exchanges -->
                            <div class="faq-category">
                                <h2 class="text-2xl font-bold mb-6 pb-3 border-b-2 border-black">Returns & Exchanges</h2>
                                <div class="space-y-4">
                                    <div class="faq-item border border-gray-200 rounded-lg">
                                        <button class="faq-question w-full text-left p-6 font-semibold flex justify-between items-center hover:bg-gray-50" onclick="this.parentElement.classList.toggle('active')">
                                            <span>What is your return policy?</span>
                                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="faq-answer hidden p-6 pt-0 text-gray-600">
                                            <p>We offer a 30-day return policy. Items must be unworn, unwashed, and in their original condition with tags attached. Simply contact our customer service team to initiate a return.</p>
                                        </div>
                                    </div>

                                    <div class="faq-item border border-gray-200 rounded-lg">
                                        <button class="faq-question w-full text-left p-6 font-semibold flex justify-between items-center hover:bg-gray-50" onclick="this.parentElement.classList.toggle('active')">
                                            <span>How do I exchange an item?</span>
                                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="faq-answer hidden p-6 pt-0 text-gray-600">
                                            <p>To exchange an item, please return it following our return process and place a new order for the item you'd like. This ensures you get the item you want as quickly as possible.</p>
                                        </div>
                                    </div>

                                    <div class="faq-item border border-gray-200 rounded-lg">
                                        <button class="faq-question w-full text-left p-6 font-semibold flex justify-between items-center hover:bg-gray-50" onclick="this.parentElement.classList.toggle('active')">
                                            <span>Who pays for return shipping?</span>
                                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="faq-answer hidden p-6 pt-0 text-gray-600">
                                            <p>For returns due to defects or errors on our part, we cover return shipping. For other returns, customers are responsible for return shipping costs.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Products & Sizing -->
                            <div class="faq-category">
                                <h2 class="text-2xl font-bold mb-6 pb-3 border-b-2 border-black">Products & Sizing</h2>
                                <div class="space-y-4">
                                    <div class="faq-item border border-gray-200 rounded-lg">
                                        <button class="faq-question w-full text-left p-6 font-semibold flex justify-between items-center hover:bg-gray-50" onclick="this.parentElement.classList.toggle('active')">
                                            <span>How do I find my size?</span>
                                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="faq-answer hidden p-6 pt-0 text-gray-600">
                                            <p>Each product page includes a detailed size guide. We recommend measuring yourself and comparing to our size charts for the best fit. If you're between sizes, we generally recommend sizing up.</p>
                                        </div>
                                    </div>

                                    <div class="faq-item border border-gray-200 rounded-lg">
                                        <button class="faq-question w-full text-left p-6 font-semibold flex justify-between items-center hover:bg-gray-50" onclick="this.parentElement.classList.toggle('active')">
                                            <span>Are your products true to size?</span>
                                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="faq-answer hidden p-6 pt-0 text-gray-600">
                                            <p>Yes, our products generally run true to size. However, sizing can vary slightly between styles. We recommend checking the size guide for each specific item and reading customer reviews for fit feedback.</p>
                                        </div>
                                    </div>

                                    <div class="faq-item border border-gray-200 rounded-lg">
                                        <button class="faq-question w-full text-left p-6 font-semibold flex justify-between items-center hover:bg-gray-50" onclick="this.parentElement.classList.toggle('active')">
                                            <span>How do I care for my clothing?</span>
                                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="faq-answer hidden p-6 pt-0 text-gray-600">
                                            <p>Each item comes with care instructions on the label. Generally, we recommend washing in cold water and hanging to dry. For specific care instructions, please refer to the product details or contact customer service.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment & Account -->
                            <div class="faq-category">
                                <h2 class="text-2xl font-bold mb-6 pb-3 border-b-2 border-black">Payment & Account</h2>
                                <div class="space-y-4">
                                    <div class="faq-item border border-gray-200 rounded-lg">
                                        <button class="faq-question w-full text-left p-6 font-semibold flex justify-between items-center hover:bg-gray-50" onclick="this.parentElement.classList.toggle('active')">
                                            <span>What payment methods do you accept?</span>
                                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="faq-answer hidden p-6 pt-0 text-gray-600">
                                            <p>We accept all major credit cards (Visa, Mastercard, American Express, Discover), PayPal, and Shop Pay. All transactions are secure and encrypted.</p>
                                        </div>
                                    </div>

                                    <div class="faq-item border border-gray-200 rounded-lg">
                                        <button class="faq-question w-full text-left p-6 font-semibold flex justify-between items-center hover:bg-gray-50" onclick="this.parentElement.classList.toggle('active')">
                                            <span>Is it safe to shop on your website?</span>
                                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="faq-answer hidden p-6 pt-0 text-gray-600">
                                            <p>Absolutely! Our website uses SSL encryption to protect your personal information. We never store your complete payment information on our servers. Your security is our top priority.</p>
                                        </div>
                                    </div>

                                    <div class="faq-item border border-gray-200 rounded-lg">
                                        <button class="faq-question w-full text-left p-6 font-semibold flex justify-between items-center hover:bg-gray-50" onclick="this.parentElement.classList.toggle('active')">
                                            <span>Do I need an account to place an order?</span>
                                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div class="faq-answer hidden p-6 pt-0 text-gray-600">
                                            <p>No, you can check out as a guest. However, creating an account allows you to track orders, save addresses, and check out faster on future purchases.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Still Have Questions -->
                        <div class="mt-16 text-center p-8 bg-gray-50 rounded-lg">
                            <h2 class="text-2xl font-bold mb-4">Still Have Questions?</h2>
                            <p class="text-gray-600 mb-6">Can't find what you're looking for? Our customer service team is here to help.</p>
                            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="button">
                                Contact Us
                            </a>
                        </div>

                    </div>
                </div>
            </section>

        </article>

    <?php endwhile; ?>

</main><!-- #primary -->

<style>
.faq-item.active .faq-question svg {
    transform: rotate(180deg);
}
.faq-item.active .faq-answer {
    display: block;
}
</style>

<?php
get_footer();
