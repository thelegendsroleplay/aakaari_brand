/**
 * FAQ Page JavaScript for FashionMen Theme
 * Accordion functionality
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        initFAQAccordion();
        initFAQCategories();
        initFAQSearch();
    });

    /**
     * FAQ Accordion
     */
    function initFAQAccordion() {
        $(document).on('click', '.faq-question', function() {
            const faqItem = $(this).closest('.faq-item');
            const isActive = faqItem.hasClass('active');

            // Close all other FAQ items
            $('.faq-item').removeClass('active');
            $('.faq-answer').slideUp(300);

            // Toggle current item
            if (!isActive) {
                faqItem.addClass('active');
                faqItem.find('.faq-answer').slideDown(300);
            }
        });
    }

    /**
     * FAQ Category Filter
     */
    function initFAQCategories() {
        $(document).on('click', '.faq-category-btn', function() {
            const category = $(this).data('category');

            // Update active button
            $('.faq-category-btn').removeClass('active');
            $(this).addClass('active');

            // Filter FAQ items
            if (category === 'all') {
                $('.faq-item').fadeIn(300);
            } else {
                $('.faq-item').hide();
                $('.faq-item[data-category="' + category + '"]').fadeIn(300);
            }
        });
    }

    /**
     * FAQ Search
     */
    function initFAQSearch() {
        const searchInput = $('#faq-search');

        if (!searchInput.length) {
            return;
        }

        searchInput.on('input', function() {
            const searchTerm = $(this).val().toLowerCase();

            $('.faq-item').each(function() {
                const question = $(this).find('.faq-question').text().toLowerCase();
                const answer = $(this).find('.faq-answer').text().toLowerCase();

                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    $(this).fadeIn(300);
                } else {
                    $(this).fadeOut(300);
                }
            });

            // Show "no results" message if all items are hidden
            setTimeout(() => {
                const visibleItems = $('.faq-item:visible').length;

                $('.no-results').remove();

                if (visibleItems === 0) {
                    $('.faq-container').append('<p class="no-results" style="text-align: center; color: #6b7280; padding: 2rem;">No FAQs found matching your search.</p>');
                }
            }, 350);
        });
    }

})(jQuery);
