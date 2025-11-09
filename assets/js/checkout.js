/**
 * Checkout page JavaScript
 * Handles multi-step checkout process
 */

(function() {
    'use strict';

    let currentStep = 1;

    /**
     * Initialize checkout functionality
     */
    function init() {
        setupStepNavigation();
        setupBillingToggle();
        setupPaymentMethods();
        setupCouponApplication();
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
     * Setup billing address toggle
     */
    function setupBillingToggle() {
        const checkbox = document.getElementById('ship_to_different_address');
        const billingFields = document.querySelector('.billing-fields');

        if (checkbox && billingFields) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    billingFields.style.display = 'none';
                    // Copy shipping to billing
                    copyShippingToBilling();
                } else {
                    billingFields.style.display = 'block';
                }
            });

            // Set initial state (checked = same as shipping)
            checkbox.checked = true;
            billingFields.style.display = 'none';
        }
    }

    /**
     * Copy shipping address to billing
     */
    function copyShippingToBilling() {
        const shippingFields = {
            'shipping_first_name': 'billing_first_name',
            'shipping_last_name': 'billing_last_name',
            'shipping_address_1': 'billing_address_1',
            'shipping_address_2': 'billing_address_2',
            'shipping_city': 'billing_city',
            'shipping_state': 'billing_state',
            'shipping_postcode': 'billing_postcode',
            'shipping_country': 'billing_country',
            'shipping_phone': 'billing_phone'
        };

        for (const [shippingField, billingField] of Object.entries(shippingFields)) {
            const shippingInput = document.getElementById(shippingField);
            const billingInput = document.getElementById(billingField);

            if (shippingInput && billingInput) {
                billingInput.value = shippingInput.value;
            }
        }
    }

    /**
     * Setup payment method selection
     */
    function setupPaymentMethods() {
        const paymentOptions = document.querySelectorAll('.payment-option input[type="radio"]');

        paymentOptions.forEach(radio => {
            radio.addEventListener('change', function() {
                // Hide all payment boxes
                const paymentBoxes = document.querySelectorAll('.payment-box');
                paymentBoxes.forEach(box => {
                    box.style.display = 'none';
                });

                // Show selected payment box
                const selectedBox = document.querySelector(`.payment_method_${this.value}`);
                if (selectedBox) {
                    selectedBox.style.display = 'block';
                }
            });

            // Trigger change on the checked radio
            if (radio.checked) {
                radio.dispatchEvent(new Event('change'));
            }
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
        formData.append('coupon_code', couponCode);
        formData.append('action', 'apply_coupon');

        fetch(wc_checkout_params.wc_ajax_url.toString().replace('%%endpoint%%', 'apply_coupon'), {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload page to show updated totals
                window.location.reload();
            } else {
                alert(data.data || 'Invalid coupon code');
            }
        })
        .catch(error => {
            console.error('Coupon application failed:', error);
            alert('Failed to apply coupon. Please try again.');
        });
    }

    /**
     * Handle form submission
     */
    function setupFormSubmission() {
        const checkoutForm = document.querySelector('form.checkout');

        if (checkoutForm) {
            checkoutForm.addEventListener('submit', function(e) {
                // If we're not on step 3 (payment), prevent submission
                if (currentStep !== 3) {
                    e.preventDefault();
                    return false;
                }

                // Let WooCommerce handle the actual submission
                return true;
            });
        }
    }

    /**
     * Initialize on DOM ready
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Also setup form submission handler
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupFormSubmission);
    } else {
        setupFormSubmission();
    }

})();
