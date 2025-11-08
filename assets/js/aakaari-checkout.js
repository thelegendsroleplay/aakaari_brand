/* aakaari-checkout.js
   UI step navigation, coupon ajax and checkout submit for parent theme integration.
*/

(function($){
  $(function(){
    const ajaxUrl = (window.aakaariCheckout && window.aakaariCheckout.ajax_url) ? window.aakaariCheckout.ajax_url : '/wp-admin/admin-ajax.php';
    const nonce = (window.aakaariCheckout && window.aakaariCheckout.nonce) ? window.aakaariCheckout.nonce : '';

    function renderSteps(step) {
      $('.checkout-steps .step').each(function(){
        var s = parseInt($(this).data('step'),10);
        $(this).toggleClass('active', step >= s);
        $(this).toggleClass('completed', step > s);
      });
    }

    function bindStepActions(){
      $(document).on('click', '#continueToBilling', function(e){
        e.preventDefault();
        var required = ['#shipping_first_name','#shipping_address_1','#shipping_city','#shipping_state','#shipping_postcode','#shipping_phone'];
        // If Woo fields have different IDs, keep validation minimal
        for(var i=0;i<required.length;i++){
          var $el = $(required[i]);
          if($el.length && !$el.val()){
            alert('Please fill required shipping fields.');
            return false;
          }
        }
        showStep(2);
      });

      $(document).on('click', '#continueToPayment', function(e){
        e.preventDefault();
        showStep(3);
      });

      $(document).on('click', '#backToShipping', function(e){
        e.preventDefault();
        showStep(1);
      });

      $(document).on('click', '#backToBilling', function(e){
        e.preventDefault();
        showStep(2);
      });

      $(document).on('click', '.payment-option', function(){
        $('.payment-option').removeClass('selected');
        $(this).addClass('selected');
      });

      $(document).on('click', '#applyCouponBtn', function(e){
        e.preventDefault();
        var code = $('#couponInput').val();
        if(!code){ alert('Enter coupon'); return; }
        var $btn = $(this);
        $btn.prop('disabled', true).text('Applying...');
        $.post(ajaxUrl, { action: 'aakaari_apply_coupon', coupon: code, nonce: nonce })
          .done(function(resp){
            if(resp.success){
              alert('Coupon applied');
              if(resp.data && resp.data.total){
                $('.summary-totals').html(
                  '<div class="total-row"><span>Subtotal</span><span>' + resp.data.subtotal + '</span></div>' +
                  ( resp.data.discount ? '<div class="total-row discount"><span>Discount</span><span>' + resp.data.discount + '</span></div>' : '' ) +
                  '<div style="height:8px"></div><div class="total-row grand-total"><span>Total</span><span>' + resp.data.total + '</span></div>'
                );
              } else {
                location.reload();
              }
            } else {
              alert(resp.data && resp.data.message ? resp.data.message : 'Coupon failed');
            }
          })
          .always(function(){
            $btn.prop('disabled', false).text('Apply');
          });
      });

      $(document).on('click', '#placeOrderBtn', function(e){
        e.preventDefault();
        var $form = $('form.checkout');
        if($form.length){
          // Let WooCommerce handle the real validation & submission
          $form.submit();
        } else {
          alert('Checkout form not found. Ensure WooCommerce checkout template is loaded.');
        }
      });
    }

    function showStep(step){
      renderSteps(step);
      $('.step-content').hide();
      $('.step-content[data-step="'+step+'"]').show();
      $('html, body').animate({ scrollTop: $('.checkout-form').offset().top - 10 }, 250);
    }

    bindStepActions();
    showStep(1);
  });
})(jQuery);
