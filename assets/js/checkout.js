/**
 * Checkout page JavaScript
 * Handles multi-step checkout, auth, quantity editing, and security
 */

(function() {
    'use strict';

    let currentStep = 1;

    /**
     * Initialize checkout functionality
     */
    function init() {
        setupAuthTabs();
        setupAuthForms();
        setupStepNavigation();
        setupQuantityControls();
        setupCouponApplication();
        setupShippingToggle();
    }

    /**
     * Setup auth tabs switching
     */
    function setupAuthTabs() {
        const authTabs = document.querySelectorAll('.auth-tab');
        const authPanels = document.querySelectorAll('.auth-panel');

        authTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const targetPanel = this.dataset.tab;

                // Remove active class from all tabs and panels
                authTabs.forEach(t => t.classList.remove('active'));
                authPanels.forEach(p => p.classList.remove('active'));

                // Add active class to clicked tab and corresponding panel
                this.classList.add('active');
                const panel = document.querySelector(`[data-panel="${targetPanel}"]`);
                if (panel) {
                    panel.classList.add('active');
                }
            });
        });
    }

    /**
     * Setup auth forms (login/register)
     */
    function setupAuthForms() {
        // Handle login form
        const loginForm = document.querySelector('.quick-login-form');
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                const submitButton = this.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.textContent = 'Logging in...';
                    submitButton.disabled = true;
                }
            });
        }

        // Handle register form
        const registerForm = document.querySelector('.quick-register-form');
        if (registerForm) {
            registerForm.addEventListener('submit', function(e) {
                const submitButton = this.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.textContent = 'Registering...';
                    submitButton.disabled = true;
                }
            });
        }
    }

    /**
     * Setup step navigation
     */
    function setupStepNavigation() {
        // Next step buttons
        const nextButtons = document.querySelectorAll('.next-step');
        nextButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const nextStep = parseInt(this.dataset.next);

                if (validateCurrentStep()) {
                    goToStep(nextStep);
                }
            });
        });

        // Previous step buttons
        const prevButtons = document.querySelectorAll('.prev-step');
        prevButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const prevStep = parseInt(this.dataset.prev);
                goToStep(prevStep);
            });
        });

        // Edit links in review step
        const editLinks = document.querySelectorAll('.edit-link');
        editLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const editStep = parseInt(this.dataset.editStep);
                goToStep(editStep);
            });
        });
    }

    /**
     * Navigate to specific step
     */
    function goToStep(stepNumber) {
        // Hide all form sections
        const formSections = document.querySelectorAll('.form-section');
        formSections.forEach(section => {
            section.style.display = 'none';
        });

        // Show target section
        const targetSection = document.querySelector(`[data-form-step="${stepNumber}"]`);
        if (targetSection) {
            targetSection.style.display = 'block';
        }

        // Update step indicators
        updateStepIndicators(stepNumber);

        // If going to step 3 (review), populate review data
        if (stepNumber === 3) {
            populateReviewStep();
        }

        // Update current step
        currentStep = stepNumber;

        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    /**
     * Update step indicators
     */
    function updateStepIndicators(activeStep) {
        const steps = document.querySelectorAll('.step');

        steps.forEach((step, index) => {
            const stepNum = index + 1;

            step.classList.remove('active', 'completed');

            if (stepNum === activeStep) {
                step.classList.add('active');
            } else if (stepNum < activeStep) {
                step.classList.add('completed');
            }
        });
    }

    /**
     * Validate current step
     */
    function validateCurrentStep() {
        const currentSection = document.querySelector(`[data-form-step="${currentStep}"]`);
        if (!currentSection) return true;

        const requiredFields = currentSection.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            // Skip validation for hidden fields
            if (field.offsetParent === null) return;

            if (!field.value || field.value.trim() === '') {
                isValid = false;
                field.style.borderColor = '#ef4444';

                // Reset border color on input
                field.addEventListener('input', function() {
                    this.style.borderColor = '#d1d5db';
                }, { once: true });
            }
        });

        if (!isValid) {
            alert('Please fill in all required fields');
        }

        return isValid;
    }

    /**
     * Populate review step with form data
     */
    function populateReviewStep() {
        // Billing details
        const billingReview = document.getElementById('billing-review');
        if (billingReview) {
            const billingData = [];

            const billingName = document.getElementById('billing_first_name')?.value + ' ' + (document.getElementById('billing_last_name')?.value || '');
            const billingAddress = document.getElementById('billing_address_1')?.value;
            const billingCity = document.getElementById('billing_city')?.value;
            const billingState = document.getElementById('billing_state')?.value;
            const billingPostcode = document.getElementById('billing_postcode')?.value;
            const billingEmail = document.getElementById('billing_email')?.value;
            const billingPhone = document.getElementById('billing_phone')?.value;

            if (billingName.trim()) billingData.push(billingName);
            if (billingAddress) billingData.push(billingAddress);
            if (billingCity && billingState) billingData.push(`${billingCity}, ${billingState} ${billingPostcode || ''}`);
            if (billingEmail) billingData.push(`Email: ${billingEmail}`);
            if (billingPhone) billingData.push(`Phone: ${billingPhone}`);

            billingReview.innerHTML = billingData.join('<br>');
        }

        // Payment method
        const paymentReview = document.getElementById('payment-review');
        if (paymentReview) {
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
            if (selectedPayment) {
                const paymentLabel = selectedPayment.closest('.wc_payment_method')?.querySelector('label')?.textContent || 'Payment method selected';
                paymentReview.innerHTML = paymentLabel;
            }
        }
    }

    /**
     * Setup shipping address toggle
     */
    function setupShippingToggle() {
        const shippingCheckbox = document.getElementById('ship-to-different-address-checkbox');
        const shippingAddress = document.querySelector('.shipping_address');

        if (shippingCheckbox && shippingAddress) {
            shippingCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    shippingAddress.style.display = 'block';
                } else {
                    shippingAddress.style.display = 'none';
                }
            });
        }
    }

    /**
     * Setup quantity controls in order summary
     */
    function setupQuantityControls() {
        // Handle quantity button clicks
        document.addEventListener('click', function(e) {
            // Check if clicked element or its parent is a quantity button
            const target = e.target.closest('.qty-btn');

            if (!target) return;

            e.preventDefault();
            e.stopPropagation();

            const cartKey = target.dataset.cartKey;
            const qtyInput = document.querySelector(`.qty-value[data-cart-key="${cartKey}"]`);

            if (!qtyInput) return;

            const currentQty = parseInt(qtyInput.value) || 1;

            // Increase quantity
            if (target.classList.contains('increase')) {
                const maxQty = parseInt(qtyInput.max) || 999;

                if (currentQty < maxQty) {
                    qtyInput.value = currentQty + 1;
                    updateCartQuantity(cartKey, currentQty + 1);
                }
            }

            // Decrease quantity
            if (target.classList.contains('decrease')) {
                if (currentQty > 1) {
                    qtyInput.value = currentQty - 1;
                    updateCartQuantity(cartKey, currentQty - 1);
                } else {
                    // Ask for confirmation before removing
                    if (confirm('Remove this item from cart?')) {
                        updateCartQuantity(cartKey, 0);
                    }
                }
            }
        });

        // Manual quantity change
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('qty-value')) {
                const cartKey = e.target.dataset.cartKey;
                const newQty = parseInt(e.target.value);

                if (newQty > 0) {
                    updateCartQuantity(cartKey, newQty);
                } else {
                    e.target.value = 1;
                }
            }
        });
    }

    /**
     * Update cart quantity via AJAX
     */
    function updateCartQuantity(cartKey, quantity) {
        const formData = new FormData();
        formData.append('action', 'update_cart_item_quantity');
        formData.append('cart_item_key', cartKey);
        formData.append('quantity', quantity);
        formData.append('nonce', aakaariAjax.nonce);

        fetch(aakaariAjax.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload page to update totals
                window.location.reload();
            } else {
                alert('Failed to update quantity. Please try again.');
            }
        })
        .catch(error => {
            console.error('Cart update failed:', error);
            alert('Failed to update cart. Please refresh the page.');
        });
    }

    /**
     * Setup coupon application
     */
    function setupCouponApplication() {
        const applyButton = document.querySelector('.apply-coupon');

        if (applyButton) {
            applyButton.addEventListener('click', function(e) {
                e.preventDefault();

                const couponInput = document.getElementById('coupon_code');
                const couponCode = couponInput ? couponInput.value.trim() : '';

                if (!couponCode) {
                    alert('Please enter a coupon code');
                    return;
                }

                applyCoupon(couponCode);
            });
        }
    }

    /**
     * Apply coupon code via AJAX
     */
    function applyCoupon(couponCode) {
        const formData = new FormData();
        formData.append('action', 'apply_coupon');
        formData.append('coupon_code', couponCode);
        formData.append('security', aakaariAjax.nonce);

        const applyButton = document.querySelector('.apply-coupon');
        if (applyButton) {
            applyButton.textContent = 'Applying...';
            applyButton.disabled = true;
        }

        fetch(aakaariAjax.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload page to show updated totals
                window.location.reload();
            } else {
                alert(data.data.message || 'Invalid coupon code');
                if (applyButton) {
                    applyButton.textContent = 'Apply';
                    applyButton.disabled = false;
                }
            }
        })
        .catch(error => {
            console.error('Coupon application failed:', error);
            alert('Failed to apply coupon. Please try again.');
            if (applyButton) {
                applyButton.textContent = 'Apply';
                applyButton.disabled = false;
            }
        });
    }

    /**
     * Handle form submission
     */
    function setupFormSubmission() {
        const checkoutForm = document.querySelector('form.checkout');

        if (checkoutForm) {
            checkoutForm.addEventListener('submit', function(e) {
                // If we're not on step 3 (review), prevent submission
                if (currentStep !== 3) {
                    e.preventDefault();
                    return false;
                }

                // Show loading state on place order button
                const placeOrderButton = document.getElementById('place_order');
                if (placeOrderButton) {
                    placeOrderButton.textContent = 'Processing...';
                    placeOrderButton.disabled = true;
                }

                // Let WooCommerce handle the actual submission
                return true;
            });
        }
    }

    /**
     * Security: Prevent multiple rapid submissions (fraud prevention)
     */
    let submissionInProgress = false;
    function preventDuplicateSubmission() {
        const checkoutForm = document.querySelector('form.checkout');
        if (!checkoutForm) return;

        checkoutForm.addEventListener('submit', function(e) {
            if (submissionInProgress) {
                e.preventDefault();
                return false;
            }

            submissionInProgress = true;

            // Reset after 10 seconds (in case submission fails)
            setTimeout(() => {
                submissionInProgress = false;
            }, 10000);
        });
    }

    /**
     * Initialize on DOM ready
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            init();
            setupFormSubmission();
            preventDuplicateSubmission();
        });
    } else {
        init();
        setupFormSubmission();
        preventDuplicateSubmission();
    }

})();
