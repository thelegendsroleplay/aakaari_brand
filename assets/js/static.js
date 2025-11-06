/**
 * Static Pages JavaScript
 * Handles FAQ accordion, contact form validation, and other interactions
 *
 * @package FashionMen
 * @since 2.0.0
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        initFAQAccordion();
        initFAQSearch();
        initFAQCategoryFilter();
        initContactForm();
        initSmoothScroll();
    });

    /**
     * Initialize FAQ Accordion
     */
    function initFAQAccordion() {
        const faqItems = document.querySelectorAll('.faq-item');

        faqItems.forEach(function(item) {
            const question = item.querySelector('.faq-question');

            if (question) {
                question.addEventListener('click', function() {
                    const isOpen = item.classList.contains('open');

                    // Close all other items
                    faqItems.forEach(function(otherItem) {
                        if (otherItem !== item) {
                            otherItem.classList.remove('open');
                        }
                    });

                    // Toggle current item
                    if (isOpen) {
                        item.classList.remove('open');
                    } else {
                        item.classList.add('open');
                    }
                });
            }
        });
    }

    /**
     * Initialize FAQ Search
     */
    function initFAQSearch() {
        const searchInput = document.getElementById('faq-search-input');
        const faqItems = document.querySelectorAll('.faq-item');
        const noResults = document.getElementById('no-results');

        if (!searchInput) return;

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let visibleCount = 0;

            faqItems.forEach(function(item) {
                const question = item.querySelector('.faq-question span');
                const answer = item.querySelector('.faq-answer-content');

                if (question && answer) {
                    const questionText = question.textContent.toLowerCase();
                    const answerText = answer.textContent.toLowerCase();

                    if (searchTerm === '' || questionText.includes(searchTerm) || answerText.includes(searchTerm)) {
                        item.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        item.classList.add('hidden');
                        item.classList.remove('open');
                    }
                }
            });

            // Show/hide no results message
            if (noResults) {
                if (visibleCount === 0 && searchTerm !== '') {
                    noResults.style.display = 'block';
                } else {
                    noResults.style.display = 'none';
                }
            }
        });
    }

    /**
     * Initialize FAQ Category Filter
     */
    function initFAQCategoryFilter() {
        const categoryChips = document.querySelectorAll('.faq-category-chip');
        const faqItems = document.querySelectorAll('.faq-item');
        const searchInput = document.getElementById('faq-search-input');

        categoryChips.forEach(function(chip) {
            chip.addEventListener('click', function() {
                const category = this.getAttribute('data-category');

                // Update active state
                categoryChips.forEach(function(c) {
                    c.classList.remove('active');
                });
                this.classList.add('active');

                // Clear search input
                if (searchInput) {
                    searchInput.value = '';
                }

                // Filter items
                faqItems.forEach(function(item) {
                    const itemCategory = item.getAttribute('data-category');

                    if (category === 'all' || itemCategory === category) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                        item.classList.remove('open');
                    }
                });

                // Hide no results message
                const noResults = document.getElementById('no-results');
                if (noResults) {
                    noResults.style.display = 'none';
                }
            });
        });
    }

    /**
     * Initialize Contact Form
     */
    function initContactForm() {
        const form = document.getElementById('contact-form');
        if (!form) return;

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Get form elements
            const submitButton = form.querySelector('.submit-button');
            const formResponse = document.getElementById('form-response');

            // Disable submit button
            submitButton.disabled = true;
            submitButton.textContent = 'Sending...';

            // Clear previous response
            if (formResponse) {
                formResponse.className = 'form-response';
                formResponse.textContent = '';
            }

            // Get form data
            const formData = new FormData(form);

            // Send AJAX request
            fetch(form.action, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                // Re-enable submit button
                submitButton.disabled = false;
                submitButton.textContent = 'Send Message';

                // Display response
                if (formResponse) {
                    if (data.success) {
                        formResponse.className = 'form-response success';
                        formResponse.textContent = data.data.message;
                        form.reset();
                    } else {
                        formResponse.className = 'form-response error';
                        formResponse.textContent = data.data.message;
                    }

                    // Scroll to response
                    formResponse.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            })
            .catch(function(error) {
                console.error('Error:', error);

                // Re-enable submit button
                submitButton.disabled = false;
                submitButton.textContent = 'Send Message';

                // Display error
                if (formResponse) {
                    formResponse.className = 'form-response error';
                    formResponse.textContent = 'An error occurred. Please try again later.';
                }
            });
        });

        // Real-time validation
        const emailInput = form.querySelector('#contact-email');
        if (emailInput) {
            emailInput.addEventListener('blur', function() {
                validateEmail(this);
            });
        }

        const nameInput = form.querySelector('#contact-name');
        if (nameInput) {
            nameInput.addEventListener('blur', function() {
                validateRequired(this);
            });
        }

        const subjectInput = form.querySelector('#contact-subject');
        if (subjectInput) {
            subjectInput.addEventListener('blur', function() {
                validateRequired(this);
            });
        }

        const messageInput = form.querySelector('#contact-message');
        if (messageInput) {
            messageInput.addEventListener('blur', function() {
                validateRequired(this);
            });
        }
    }

    /**
     * Validate Required Field
     */
    function validateRequired(input) {
        if (!input.value.trim()) {
            input.style.borderColor = '#dc3545';
            return false;
        } else {
            input.style.borderColor = '#e5e5e5';
            return true;
        }
    }

    /**
     * Validate Email Field
     */
    function validateEmail(input) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!input.value.trim()) {
            input.style.borderColor = '#dc3545';
            return false;
        } else if (!emailRegex.test(input.value)) {
            input.style.borderColor = '#dc3545';
            return false;
        } else {
            input.style.borderColor = '#e5e5e5';
            return true;
        }
    }

    /**
     * Initialize Smooth Scroll for Anchor Links
     */
    function initSmoothScroll() {
        const links = document.querySelectorAll('a[href^="#"]');

        links.forEach(function(link) {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');

                if (href === '#') return;

                const target = document.querySelector(href);

                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });

                    // Update URL without jumping
                    if (history.pushState) {
                        history.pushState(null, null, href);
                    }
                }
            });
        });
    }

    /**
     * Initialize on page visibility change (for better performance)
     */
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            // Page is visible again, refresh any dynamic content if needed
        }
    });

})();
