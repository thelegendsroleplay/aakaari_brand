/* aakaari-checkout.js
   UI step navigation, coupon ajax and checkout submit for parent theme integration.
*/

(function ($) {
  $(function () {
    const ajaxUrl = (window.aakaariCheckout && window.aakaariCheckout.ajax_url) ? window.aakaariCheckout.ajax_url : '/wp-admin/admin-ajax.php';
    const nonce = (window.aakaariCheckout && window.aakaariCheckout.nonce) ? window.aakaariCheckout.nonce : '';
    const $checkoutForm = $('form.checkout');
    const $summaryBody = $('#orderSummaryBody');

    function renderSteps(step) {
      $('.checkout-steps .step').each(function () {
        const s = parseInt($(this).data('step'), 10);
        $(this).toggleClass('active', step >= s);
        $(this).toggleClass('completed', step > s);
      });
    }

    function scrollToForm() {
      const $target = $('.checkout-form');
      if ($target.length) {
        $('html, body').animate({ scrollTop: $target.offset().top - 12 }, 250);
      }
    }

    function showStep(step) {
      renderSteps(step);
      $('.step-content').hide()
        .filter('[data-step="' + step + '"]').show();
      $('.checkout-form').attr('data-current-step', step);
      if (step === 3) {
        $(document.body).trigger('update_checkout');
      }
      scrollToForm();
    }

    function syncSummaryFromReview() {
      if (!$summaryBody.length) return;
      const $review = $('#order_review');
      if (!$review.length) return;

      const items = $review.find('.summary-items').clone(true, true);
      const totals = $review.find('.summary-totals').clone(true, true);

      if (!items.length && !totals.length) {
        return;
      }

      const frag = $('<div/>');
      if (items.length) {
        frag.append(items);
      }
      if (totals.length) {
        frag.append('<div class="summary-divider"></div>');
        frag.append(totals);
      }
      $summaryBody.html(frag.html());
    }

    function syncBillingFromShipping() {
      if (!$('#ship_to_same').is(':checked')) {
        return;
      }
      $('[id^="shipping_"]').each(function () {
        const shippingId = this.id;
        const billingId = shippingId.replace(/^shipping_/, 'billing_');
        const $billing = $('#' + billingId);
        if ($billing.length) {
          const value = $(this).val();
          $billing.val(value).trigger('change');
        }
      });
    }

    function toggleBillingFields() {
      const $toggle = $('#ship_to_same');
      if (!$toggle.length) return;
      const same = $toggle.is(':checked');
      $('#billingFields').toggleClass('is-hidden', same).attr('aria-hidden', same ? 'true' : 'false');
      $('#ship_to_different_address_hidden').val(same ? '0' : '1');
      if (same) {
        syncBillingFromShipping();
      }
    }

    function bindEvents() {
      $(document).on('click', '#continueToBilling', function (e) {
        e.preventDefault();
        showStep(2);
      });

      $(document).on('click', '#backToShipping', function (e) {
        e.preventDefault();
        showStep(1);
      });

      $(document).on('click', '#continueToPayment', function (e) {
        e.preventDefault();
        syncBillingFromShipping();
        showStep(3);
      });

      $(document).on('click', '#backToBillingSummary', function (e) {
        e.preventDefault();
        showStep(2);
      });

      $(document).on('change keyup blur', '[id^="shipping_"]', function () {
        if ($('#ship_to_same').is(':checked')) {
          syncBillingFromShipping();
        }
      });

      $(document).on('change', '#ship_to_same', function () {
        toggleBillingFields();
      });

      $(document).on('click', '#applyCouponBtn', function (e) {
        e.preventDefault();
        const code = $('#couponInput').val();
        if (!code) {
          alert('Enter coupon');
          return;
        }
        const $btn = $(this);
        $btn.prop('disabled', true).text('Applying...');
        $.post(ajaxUrl, { action: 'aakaari_apply_coupon', coupon: code, nonce: nonce })
          .done(function (resp) {
            if (resp.success) {
              $(document.body).trigger('update_checkout');
            } else {
              alert(resp.data && resp.data.message ? resp.data.message : 'Coupon failed');
            }
          })
          .always(function () {
            $btn.prop('disabled', false).text('Apply');
          });
      });

      if ($checkoutForm.length) {
        $checkoutForm.on('submit', function () {
          if ($('#ship_to_same').is(':checked')) {
            syncBillingFromShipping();
          }
        });
      }

      $(document.body).on('updated_checkout', function () {
        syncSummaryFromReview();
      });
    }

    bindEvents();
    toggleBillingFields();
    syncSummaryFromReview();
    showStep(1);
  });
  document.addEventListener('DOMContentLoaded', function() {
    const methods = document.querySelectorAll('.wc_payment_method');
    const placeBtn = document.querySelector('#place_order');
    const termsCheckbox = document.querySelector('.woocommerce-terms-and-conditions-wrapper input[type="checkbox"]');
  
    // helper: update payment_box visibility
    function updateBoxes() {
      methods.forEach(li => {
        const radio = li.querySelector('.pm-radio');
        const box = li.querySelector('.payment_box');
        if (!radio || !box) return;
        if (radio.checked) {
          box.style.display = 'block';
          box.setAttribute('aria-hidden','false');
          radio.setAttribute('aria-checked','true');
        } else {
          box.style.display = 'none';
          box.setAttribute('aria-hidden','true');
          radio.setAttribute('aria-checked','false');
        }
      });
      updatePlaceEnabled();
    }
  
    function updatePlaceEnabled(){
      const checked = document.querySelector('.pm-radio:checked');
      const termsOk = termsCheckbox ? termsCheckbox.checked : true;
      if (placeBtn) placeBtn.disabled = !(checked && termsOk);
    }
  
    // attach handlers
    document.querySelectorAll('.pm-radio').forEach(radio =>
      radio.addEventListener('change', updateBoxes)
    );
    if (termsCheckbox) termsCheckbox.addEventListener('change', updateBoxes);
  
    // init on page load
    updateBoxes();
  });
})(jQuery);
