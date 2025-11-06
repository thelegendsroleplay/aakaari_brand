<?php
/**
 * Template Name: FAQ Page
 * Description: Frequently Asked Questions page with accordion
 *
 * @package FashionMen
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();

// Get FAQ data
$faq_categories = fashionmen_get_faq_categories();
$faq_questions = fashionmen_get_faq_questions();
?>

<div class="static-page">
    <div class="static-container">
        <!-- Hero Section -->
        <div class="static-hero">
            <h1 class="static-hero-title">Frequently Asked Questions</h1>
            <p class="static-hero-subtitle">Find answers to common questions about our products and services.</p>
        </div>

        <!-- Search Bar -->
        <div class="faq-search">
            <span class="faq-search-icon">🔍</span>
            <input type="text" id="faq-search-input" class="faq-search-input" placeholder="Search for questions...">
        </div>

        <!-- Category Filters -->
        <div class="faq-categories">
            <?php foreach ($faq_categories as $category) : ?>
                <button class="faq-category-chip <?php echo $category['id'] === 'all' ? 'active' : ''; ?>" data-category="<?php echo esc_attr($category['id']); ?>">
                    <span><?php echo esc_html($category['icon'] . ' ' . $category['name']); ?></span>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- FAQ List -->
        <div class="static-content">
            <div class="faq-list">
                <?php foreach ($faq_questions as $faq) : ?>
                    <div class="faq-item" data-category="<?php echo esc_attr($faq['category']); ?>">
                        <button class="faq-question" type="button">
                            <span><?php echo esc_html($faq['question']); ?></span>
                            <span class="faq-icon">▼</span>
                        </button>
                        <div class="faq-answer">
                            <div class="faq-answer-content">
                                <?php echo wp_kses_post($faq['answer']); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div id="no-results" style="display: none; text-align: center; padding: 3rem; color: #666;">
                <p>No questions found matching your search. Please try different keywords.</p>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="static-cta">
            <h2>Still Have Questions?</h2>
            <p>Can't find what you're looking for? Our customer service team is here to help.</p>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="cta-button">Contact Us</a>
        </div>
    </div>
</div>

<?php
get_footer();
