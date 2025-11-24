<?php
/**
 * Template Name: FAQ
 * Template Post Type: page
 *
 * @package Aakaari_Brand
 */

get_header();
?>

<main id="main" class="site-main faq-page">
    <div class="page-container">
        <div class="faq-content">
            <div class="faq-header">
                <h1 class="faq-title">Frequently Asked Questions</h1>
                <p class="faq-subtitle">Find answers to common questions about Herrenn products, orders, and policies</p>

                <div class="faq-search">
                    <input type="text" id="faqSearch" placeholder="Search for answers..." />
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                </div>
            </div>

            <div class="faq-categories">
                <button class="faq-category-btn active" data-category="all">All Questions</button>
                <button class="faq-category-btn" data-category="orders">Orders & Shipping</button>
                <button class="faq-category-btn" data-category="products">Products</button>
                <button class="faq-category-btn" data-category="returns">Returns & Exchanges</button>
                <button class="faq-category-btn" data-category="account">Account & Payment</button>
            </div>

            <div class="faq-list">
                <!-- Orders & Shipping -->
                <div class="faq-item" data-category="orders">
                    <button class="faq-question">
                        <span>How long does shipping take?</span>
                        <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p><strong>Standard Shipping:</strong> 5-7 business days across India</p>
                        <p><strong>Express Shipping:</strong> 2-3 business days (available in metro cities)</p>
                        <p>Free shipping is available on all orders above ₹499. Orders are processed within 24-48 hours.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="orders">
                    <button class="faq-question">
                        <span>Do you ship internationally?</span>
                        <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>Currently, we only ship within India. We're working on expanding our shipping to international destinations soon. Stay tuned for updates!</p>
                    </div>
                </div>

                <div class="faq-item" data-category="orders">
                    <button class="faq-question">
                        <span>How can I track my order?</span>
                        <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>Once your order is shipped, you'll receive a tracking number via email and SMS. You can:</p>
                        <ul>
                            <li>Visit our <a href="/track-order">Track Order</a> page</li>
                            <li>Log in to your account and check "My Orders"</li>
                            <li>Click the tracking link in your shipping confirmation email</li>
                        </ul>
                    </div>
                </div>

                <div class="faq-item" data-category="orders">
                    <button class="faq-question">
                        <span>Can I change my shipping address after placing an order?</span>
                        <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>Yes, if your order hasn't been shipped yet. Contact us immediately at <a href="mailto:support@herrenn.com">support@herrenn.com</a> with your order number and new address. Once shipped, address changes may not be possible.</p>
                    </div>
                </div>

                <!-- Products -->
                <div class="faq-item" data-category="products">
                    <button class="faq-question">
                        <span>What materials are your products made from?</span>
                        <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>We use premium quality materials including:</p>
                        <ul>
                            <li><strong>100% Premium Cotton</strong> for most t-shirts</li>
                            <li><strong>Cotton-Polyester Blends</strong> for hoodies and sweatshirts</li>
                            <li><strong>High-Quality Fabrics</strong> for durability and comfort</li>
                        </ul>
                        <p>Specific material information is available on each product page.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="products">
                    <button class="faq-question">
                        <span>How do I choose the right size?</span>
                        <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>Each product page includes a detailed size chart. We recommend:</p>
                        <ul>
                            <li>Measuring your chest, waist, and length</li>
                            <li>Comparing with our size chart</li>
                            <li>If between sizes, size up for a looser fit</li>
                            <li>Check product reviews for fit feedback</li>
                        </ul>
                        <p>If you need help, contact our support team!</p>
                    </div>
                </div>

                <div class="faq-item" data-category="products">
                    <button class="faq-question">
                        <span>How do I care for my Herrenn products?</span>
                        <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>To keep your products looking great:</p>
                        <ul>
                            <li>Machine wash cold with similar colors</li>
                            <li>Use mild detergent</li>
                            <li>Tumble dry low or hang dry</li>
                            <li>Iron inside out if needed</li>
                            <li>Avoid bleach and harsh chemicals</li>
                        </ul>
                        <p>Care labels are attached to all products for specific instructions.</p>
                    </div>
                </div>

                <!-- Returns & Exchanges -->
                <div class="faq-item" data-category="returns">
                    <button class="faq-question">
                        <span>What is your return policy?</span>
                        <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>We offer a <strong>7-day easy exchange policy</strong>. Items can be returned if:</p>
                        <ul>
                            <li>Request made within 7 days of delivery</li>
                            <li>Product is unused, unworn, and unwashed</li>
                            <li>Original tags and packaging intact</li>
                            <li>Valid proof of purchase provided</li>
                        </ul>
                        <p>See our <a href="/cancellation-refund-policy">full return policy</a> for details.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="returns">
                    <button class="faq-question">
                        <span>How do I initiate a return or exchange?</span>
                        <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>To return or exchange a product:</p>
                        <ol>
                            <li>Contact us at <a href="mailto:support@herrenn.com">support@herrenn.com</a> within 7 days</li>
                            <li>Provide your order number and reason for return</li>
                            <li>We'll send you a Return Authorization (RA) number</li>
                            <li>Pack the item securely with tags intact</li>
                            <li>Our courier will pick up the package</li>
                        </ol>
                    </div>
                </div>

                <div class="faq-item" data-category="returns">
                    <button class="faq-question">
                        <span>How long does it take to process a refund?</span>
                        <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>Refund processing times vary by payment method:</p>
                        <ul>
                            <li><strong>Credit/Debit Cards:</strong> 7-10 business days</li>
                            <li><strong>Net Banking/UPI:</strong> 5-7 business days</li>
                            <li><strong>Digital Wallets:</strong> 3-5 business days</li>
                        </ul>
                        <p>The refund is initiated once we receive and inspect your returned item.</p>
                    </div>
                </div>

                <!-- Account & Payment -->
                <div class="faq-item" data-category="account">
                    <button class="faq-question">
                        <span>Do I need an account to place an order?</span>
                        <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>No, you can checkout as a guest. However, creating an account offers benefits:</p>
                        <ul>
                            <li>Track orders easily</li>
                            <li>Save addresses for faster checkout</li>
                            <li>View order history</li>
                            <li>Receive exclusive offers</li>
                            <li>Manage returns and exchanges</li>
                        </ul>
                    </div>
                </div>

                <div class="faq-item" data-category="account">
                    <button class="faq-question">
                        <span>What payment methods do you accept?</span>
                        <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>We accept multiple secure payment methods:</p>
                        <ul>
                            <li>Credit and Debit Cards (Visa, Mastercard, RuPay)</li>
                            <li>UPI (Google Pay, PhonePe, Paytm, etc.)</li>
                            <li>Net Banking</li>
                            <li>Digital Wallets</li>
                            <li>Cash on Delivery (COD) - where available</li>
                        </ul>
                        <p>All transactions are secured with SSL encryption.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="account">
                    <button class="faq-question">
                        <span>Is my payment information secure?</span>
                        <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p>Absolutely! We take security seriously:</p>
                        <ul>
                            <li>All payments processed through secure, encrypted gateways</li>
                            <li>We never store complete card details</li>
                            <li>PCI DSS compliant payment processing</li>
                            <li>SSL certificate for data protection</li>
                        </ul>
                        <p>Your payment information is safe with us.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="account">
                    <button class="faq-question">
                        <span>Can I cancel my order?</span>
                        <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div class="faq-answer">
                        <p><strong>Before Shipment:</strong> Yes! Contact us immediately and we'll cancel your order with a full refund.</p>
                        <p><strong>After Shipment:</strong> Orders cannot be cancelled, but you can refuse delivery or return the item once received.</p>
                        <p>Email us at <a href="mailto:support@herrenn.com">support@herrenn.com</a> for assistance.</p>
                    </div>
                </div>
            </div>

            <div class="faq-cta">
                <h2>Still Have Questions?</h2>
                <p>Can't find what you're looking for? Our customer support team is here to help!</p>
                <div class="cta-buttons">
                    <a href="/contact" class="cta-button primary">Contact Support</a>
                    <a href="mailto:support@herrenn.com" class="cta-button secondary">Email Us</a>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
jQuery(document).ready(function($) {
    // FAQ accordion
    $('.faq-question').on('click', function() {
        var $item = $(this).closest('.faq-item');
        var $answer = $item.find('.faq-answer');
        var $icon = $(this).find('.faq-icon');

        if ($item.hasClass('active')) {
            $item.removeClass('active');
            $answer.slideUp(300);
            $icon.css('transform', 'rotate(0deg)');
        } else {
            $('.faq-item').removeClass('active');
            $('.faq-answer').slideUp(300);
            $('.faq-icon').css('transform', 'rotate(0deg)');

            $item.addClass('active');
            $answer.slideDown(300);
            $icon.css('transform', 'rotate(180deg)');
        }
    });

    // Category filter
    $('.faq-category-btn').on('click', function() {
        var category = $(this).data('category');

        $('.faq-category-btn').removeClass('active');
        $(this).addClass('active');

        if (category === 'all') {
            $('.faq-item').fadeIn(300);
        } else {
            $('.faq-item').hide();
            $('.faq-item[data-category="' + category + '"]').fadeIn(300);
        }
    });

    // Search functionality
    $('#faqSearch').on('keyup', function() {
        var searchTerm = $(this).val().toLowerCase();

        if (searchTerm === '') {
            $('.faq-item').fadeIn(300);
            return;
        }

        $('.faq-item').each(function() {
            var question = $(this).find('.faq-question span').text().toLowerCase();
            var answer = $(this).find('.faq-answer').text().toLowerCase();

            if (question.indexOf(searchTerm) > -1 || answer.indexOf(searchTerm) > -1) {
                $(this).fadeIn(300);
            } else {
                $(this).fadeOut(300);
            }
        });
    });
});
</script>

<?php
get_footer();
