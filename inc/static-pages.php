<?php
/**
 * Static Pages Functions
 * Handles contact form submission and FAQ data
 *
 * @package FashionMen
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Get FAQ Categories
 *
 * @return array FAQ categories
 */
function fashionmen_get_faq_categories() {
    return array(
        array('id' => 'all', 'name' => 'All Questions', 'icon' => '📋'),
        array('id' => 'orders', 'name' => 'Orders & Shipping', 'icon' => '📦'),
        array('id' => 'returns', 'name' => 'Returns & Exchanges', 'icon' => '↩️'),
        array('id' => 'products', 'name' => 'Products', 'icon' => '👔'),
        array('id' => 'account', 'name' => 'Account', 'icon' => '👤'),
        array('id' => 'payments', 'name' => 'Payments', 'icon' => '💳'),
    );
}

/**
 * Get FAQ Questions
 *
 * @return array FAQ questions and answers
 */
function fashionmen_get_faq_questions() {
    return array(
        // Orders & Shipping
        array(
            'category' => 'orders',
            'question' => 'How long does shipping take?',
            'answer' => 'Standard shipping typically takes 5-7 business days. Express shipping takes 2-3 business days, and overnight shipping arrives the next business day. All orders are processed within 24 hours of receipt.',
        ),
        array(
            'category' => 'orders',
            'question' => 'Do you ship internationally?',
            'answer' => 'Yes, we ship to over 25 countries worldwide. International shipping times vary by location but typically take 7-14 business days. Customs fees may apply depending on your country.',
        ),
        array(
            'category' => 'orders',
            'question' => 'How can I track my order?',
            'answer' => 'Once your order ships, you\'ll receive a tracking number via email. You can also track your order by logging into your account and viewing your order history.',
        ),
        array(
            'category' => 'orders',
            'question' => 'Can I change my shipping address after placing an order?',
            'answer' => 'If your order hasn\'t been processed yet, we can update the shipping address. Please contact us immediately at support@mensafashion.com with your order number and the new address.',
        ),

        // Returns & Exchanges
        array(
            'category' => 'returns',
            'question' => 'What is your return policy?',
            'answer' => 'We offer a 30-day return policy for unworn, unwashed items with original tags attached. Returns are free for domestic orders. Simply initiate a return through your account dashboard.',
        ),
        array(
            'category' => 'returns',
            'question' => 'How do I exchange an item?',
            'answer' => 'To exchange an item, please initiate a return and place a new order for the desired item. This ensures you get your preferred item as quickly as possible.',
        ),
        array(
            'category' => 'returns',
            'question' => 'Can I return customized items?',
            'answer' => 'Unfortunately, customized items cannot be returned or exchanged unless there is a manufacturing defect. Please review your customization carefully before placing your order.',
        ),
        array(
            'category' => 'returns',
            'question' => 'How long does it take to process a refund?',
            'answer' => 'Once we receive your return, refunds are typically processed within 5-7 business days. The refund will be issued to your original payment method.',
        ),

        // Products
        array(
            'category' => 'products',
            'question' => 'How do I find my size?',
            'answer' => 'Each product page includes a detailed size guide. Click the "Size Guide" button on any product page to view measurements. We recommend measuring a similar item you own for the best fit.',
        ),
        array(
            'category' => 'products',
            'question' => 'Are your products sustainable?',
            'answer' => 'Yes, we\'re committed to sustainability. We use ethically sourced materials, environmentally responsible manufacturing processes, and recyclable packaging whenever possible.',
        ),
        array(
            'category' => 'products',
            'question' => 'What is the product customizer?',
            'answer' => 'Our product customizer allows you to personalize select items with custom text, colors, and other options. Look for the "Customize" option on eligible product pages.',
        ),
        array(
            'category' => 'products',
            'question' => 'How do I care for my items?',
            'answer' => 'Care instructions are provided on the product tag and on each product page. Generally, we recommend following the care label instructions carefully to maintain the quality of your items.',
        ),

        // Account
        array(
            'category' => 'account',
            'question' => 'How do I create an account?',
            'answer' => 'Click the "Sign Up" button in the top right corner. Fill in your information to create an account. You can also sign up using your Google, Facebook, or Apple account.',
        ),
        array(
            'category' => 'account',
            'question' => 'What are the benefits of creating an account?',
            'answer' => 'Account holders enjoy order tracking, wishlist features, faster checkout, exclusive offers, and access to our loyalty rewards program.',
        ),
        array(
            'category' => 'account',
            'question' => 'How do I reset my password?',
            'answer' => 'Click "Forgot Password" on the login page. Enter your email address and we\'ll send you instructions to reset your password.',
        ),
        array(
            'category' => 'account',
            'question' => 'Can I delete my account?',
            'answer' => 'Yes, you can request account deletion by contacting our customer service team. Please note that this action is permanent and cannot be undone.',
        ),

        // Payments
        array(
            'category' => 'payments',
            'question' => 'What payment methods do you accept?',
            'answer' => 'We accept all major credit cards (Visa, Mastercard, American Express), PayPal, Apple Pay, and Google Pay. All payments are securely processed.',
        ),
        array(
            'category' => 'payments',
            'question' => 'Is my payment information secure?',
            'answer' => 'Yes, we use industry-standard SSL encryption to protect your payment information. We never store your full credit card details on our servers.',
        ),
        array(
            'category' => 'payments',
            'question' => 'Do you offer payment plans?',
            'answer' => 'Currently, we require full payment at checkout. However, you may use services like PayPal Credit if available in your region.',
        ),
        array(
            'category' => 'payments',
            'question' => 'Why was my payment declined?',
            'answer' => 'Payment declines can occur for various reasons including insufficient funds, incorrect billing information, or security holds. Please contact your bank or try a different payment method.',
        ),
    );
}

/**
 * Handle Contact Form Submission via AJAX
 */
function fashionmen_handle_contact_form() {
    // Verify nonce
    if (!isset($_POST['contact_nonce']) || !wp_verify_nonce($_POST['contact_nonce'], 'fashionmen_contact_form')) {
        wp_send_json_error(array('message' => 'Security verification failed. Please refresh the page and try again.'));
        return;
    }

    // Sanitize and validate form data
    $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
    $subject = isset($_POST['subject']) ? sanitize_text_field($_POST['subject']) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';

    // Validate required fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        wp_send_json_error(array('message' => 'Please fill in all required fields.'));
        return;
    }

    // Validate email
    if (!is_email($email)) {
        wp_send_json_error(array('message' => 'Please enter a valid email address.'));
        return;
    }

    // Prepare email
    $to = get_option('admin_email'); // Send to site admin email
    $email_subject = 'Contact Form Submission: ' . $subject;
    $email_message = "New contact form submission:\n\n";
    $email_message .= "Name: {$name}\n";
    $email_message .= "Email: {$email}\n";
    if (!empty($phone)) {
        $email_message .= "Phone: {$phone}\n";
    }
    $email_message .= "Subject: {$subject}\n\n";
    $email_message .= "Message:\n{$message}\n";

    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>',
        'Reply-To: ' . $name . ' <' . $email . '>',
    );

    // Send email
    $sent = wp_mail($to, $email_subject, $email_message, $headers);

    if ($sent) {
        // Optionally store submission in database or send autoresponder
        wp_send_json_success(array('message' => 'Thank you for contacting us! We\'ll get back to you within 24 hours.'));
    } else {
        wp_send_json_error(array('message' => 'There was an error sending your message. Please try again or email us directly at ' . get_option('admin_email') . '.'));
    }
}
add_action('wp_ajax_fashionmen_contact_form', 'fashionmen_handle_contact_form');
add_action('wp_ajax_nopriv_fashionmen_contact_form', 'fashionmen_handle_contact_form');

/**
 * Enqueue static pages assets with localization
 */
function fashionmen_static_pages_localize() {
    if (is_page_template('page-contact.php')) {
        wp_localize_script('fashionmen-static', 'fashionmenAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('fashionmen_contact_form'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'fashionmen_static_pages_localize', 20);
