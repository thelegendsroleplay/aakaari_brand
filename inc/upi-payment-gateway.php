<?php
/**
 * UPI Payment Gateway for WooCommerce
 * Supports multiple UPI PSPs: Razorpay, PayU, Cashfree, and Manual UPI
 *
 * @package Aakaari_Brand
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if WooCommerce is active
 */
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    return;
}

/**
 * Add UPI Payment Gateway to WooCommerce
 */
add_filter('woocommerce_payment_gateways', 'aakaari_add_upi_gateway');
function aakaari_add_upi_gateway($gateways) {
    $gateways[] = 'Aakaari_UPI_Gateway';
    return $gateways;
}

/**
 * UPI Payment Gateway Class
 */
add_action('plugins_loaded', 'aakaari_init_upi_gateway');
function aakaari_init_upi_gateway() {

    class Aakaari_UPI_Gateway extends WC_Payment_Gateway {

        /**
         * Constructor
         */
        public function __construct() {
            $this->id = 'aakaari_upi';
            $this->icon = '';
            $this->has_fields = true;
            $this->method_title = __('UPI Payment', 'aakaari-brand');
            $this->method_description = __('Accept payments via UPI using Razorpay, PayU, Cashfree, or Manual UPI ID', 'aakaari-brand');

            // Load settings
            $this->init_form_fields();
            $this->init_settings();

            // Define user set variables
            $this->title = $this->get_option('title');
            $this->description = $this->get_option('description');
            $this->provider = $this->get_option('provider', 'manual');
            $this->enabled = $this->get_option('enabled');

            // Razorpay settings
            $this->razorpay_key_id = $this->get_option('razorpay_key_id');
            $this->razorpay_key_secret = $this->get_option('razorpay_key_secret');

            // PayU settings
            $this->payu_merchant_key = $this->get_option('payu_merchant_key');
            $this->payu_merchant_salt = $this->get_option('payu_merchant_salt');

            // Cashfree settings
            $this->cashfree_app_id = $this->get_option('cashfree_app_id');
            $this->cashfree_secret_key = $this->get_option('cashfree_secret_key');

            // Manual UPI settings
            $this->upi_id = $this->get_option('upi_id');
            $this->payee_name = $this->get_option('payee_name');
            $this->qr_code_upload = $this->get_option('qr_code_upload');

            // Test mode
            $this->testmode = 'yes' === $this->get_option('testmode', 'no');

            // Actions
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            add_action('woocommerce_api_' . $this->id, array($this, 'webhook'));
            add_action('woocommerce_receipt_' . $this->id, array($this, 'receipt_page'));
        }

        /**
         * Initialize Gateway Settings Form Fields
         */
        public function init_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'aakaari-brand'),
                    'type' => 'checkbox',
                    'label' => __('Enable UPI Payment', 'aakaari-brand'),
                    'default' => 'no'
                ),
                'title' => array(
                    'title' => __('Title', 'aakaari-brand'),
                    'type' => 'text',
                    'description' => __('Payment method title that customers see during checkout.', 'aakaari-brand'),
                    'default' => __('UPI Payment', 'aakaari-brand'),
                    'desc_tip' => true,
                ),
                'description' => array(
                    'title' => __('Description', 'aakaari-brand'),
                    'type' => 'textarea',
                    'description' => __('Payment method description that customers see during checkout.', 'aakaari-brand'),
                    'default' => __('Pay securely using UPI (Google Pay, PhonePe, Paytm, BHIM)', 'aakaari-brand'),
                ),
                'testmode' => array(
                    'title' => __('Test Mode', 'aakaari-brand'),
                    'type' => 'checkbox',
                    'label' => __('Enable Test Mode', 'aakaari-brand'),
                    'default' => 'yes',
                    'description' => __('Use test API keys for testing. Disable for live transactions.', 'aakaari-brand'),
                ),
                'provider' => array(
                    'title' => __('Payment Provider', 'aakaari-brand'),
                    'type' => 'select',
                    'description' => __('Choose your UPI payment provider.', 'aakaari-brand'),
                    'default' => 'manual',
                    'desc_tip' => true,
                    'options' => array(
                        'razorpay' => __('Razorpay', 'aakaari-brand'),
                        'payu' => __('PayU', 'aakaari-brand'),
                        'cashfree' => __('Cashfree', 'aakaari-brand'),
                        'manual' => __('Manual UPI (QR Code)', 'aakaari-brand'),
                    ),
                ),
                'razorpay_section' => array(
                    'title' => __('Razorpay Settings', 'aakaari-brand'),
                    'type' => 'title',
                    'description' => __('Enter your Razorpay API credentials. Get them from <a href="https://dashboard.razorpay.com/" target="_blank">Razorpay Dashboard</a>', 'aakaari-brand'),
                ),
                'razorpay_key_id' => array(
                    'title' => __('Razorpay Key ID', 'aakaari-brand'),
                    'type' => 'text',
                    'description' => __('Your Razorpay API Key ID', 'aakaari-brand'),
                    'default' => '',
                    'desc_tip' => true,
                ),
                'razorpay_key_secret' => array(
                    'title' => __('Razorpay Key Secret', 'aakaari-brand'),
                    'type' => 'password',
                    'description' => __('Your Razorpay API Key Secret', 'aakaari-brand'),
                    'default' => '',
                    'desc_tip' => true,
                ),
                'payu_section' => array(
                    'title' => __('PayU Settings', 'aakaari-brand'),
                    'type' => 'title',
                    'description' => __('Enter your PayU credentials. Get them from <a href="https://www.payu.in/" target="_blank">PayU Dashboard</a>', 'aakaari-brand'),
                ),
                'payu_merchant_key' => array(
                    'title' => __('PayU Merchant Key', 'aakaari-brand'),
                    'type' => 'text',
                    'description' => __('Your PayU Merchant Key', 'aakaari-brand'),
                    'default' => '',
                    'desc_tip' => true,
                ),
                'payu_merchant_salt' => array(
                    'title' => __('PayU Merchant Salt', 'aakaari-brand'),
                    'type' => 'password',
                    'description' => __('Your PayU Merchant Salt', 'aakaari-brand'),
                    'default' => '',
                    'desc_tip' => true,
                ),
                'cashfree_section' => array(
                    'title' => __('Cashfree Settings', 'aakaari-brand'),
                    'type' => 'title',
                    'description' => __('Enter your Cashfree credentials. Get them from <a href="https://www.cashfree.com/" target="_blank">Cashfree Dashboard</a>', 'aakaari-brand'),
                ),
                'cashfree_app_id' => array(
                    'title' => __('Cashfree App ID', 'aakaari-brand'),
                    'type' => 'text',
                    'description' => __('Your Cashfree App ID', 'aakaari-brand'),
                    'default' => '',
                    'desc_tip' => true,
                ),
                'cashfree_secret_key' => array(
                    'title' => __('Cashfree Secret Key', 'aakaari-brand'),
                    'type' => 'password',
                    'description' => __('Your Cashfree Secret Key', 'aakaari-brand'),
                    'default' => '',
                    'desc_tip' => true,
                ),
                'manual_section' => array(
                    'title' => __('Manual UPI Settings', 'aakaari-brand'),
                    'type' => 'title',
                    'description' => __('For manual UPI payments via QR code', 'aakaari-brand'),
                ),
                'upi_id' => array(
                    'title' => __('UPI ID', 'aakaari-brand'),
                    'type' => 'text',
                    'description' => __('Your UPI ID (e.g., yourname@paytm)', 'aakaari-brand'),
                    'default' => '',
                    'desc_tip' => true,
                    'placeholder' => 'yourname@paytm',
                ),
                'payee_name' => array(
                    'title' => __('Payee Name', 'aakaari-brand'),
                    'type' => 'text',
                    'description' => __('Name to display on payment page', 'aakaari-brand'),
                    'default' => get_bloginfo('name'),
                    'desc_tip' => true,
                ),
                'qr_code_upload' => array(
                    'title' => __('UPI QR Code', 'aakaari-brand'),
                    'type' => 'text',
                    'description' => __('Upload your UPI QR code image URL', 'aakaari-brand'),
                    'default' => '',
                    'desc_tip' => true,
                ),
            );
        }

        /**
         * Payment fields on checkout
         */
        public function payment_fields() {
            if ($this->description) {
                echo wpautop(wptexturize($this->description));
            }

            if ($this->provider === 'manual') {
                ?>
                <div class="upi-payment-info" style="margin: 15px 0;">
                    <p style="margin-bottom: 10px;">
                        <strong><?php _e('Pay using any UPI app:', 'aakaari-brand'); ?></strong>
                    </p>
                    <div class="upi-apps" style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <span style="padding: 5px 12px; background: #f0f0f0; border-radius: 4px; font-size: 12px;">Google Pay</span>
                        <span style="padding: 5px 12px; background: #f0f0f0; border-radius: 4px; font-size: 12px;">PhonePe</span>
                        <span style="padding: 5px 12px; background: #f0f0f0; border-radius: 4px; font-size: 12px;">Paytm</span>
                        <span style="padding: 5px 12px; background: #f0f0f0; border-radius: 4px; font-size: 12px;">BHIM</span>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <div class="upi-payment-info" style="margin: 15px 0;">
                    <p><?php _e('You will be redirected to complete the payment securely.', 'aakaari-brand'); ?></p>
                </div>
                <?php
            }
        }

        /**
         * Process the payment
         */
        public function process_payment($order_id) {
            $order = wc_get_order($order_id);

            switch ($this->provider) {
                case 'razorpay':
                    return $this->process_razorpay_payment($order);
                case 'payu':
                    return $this->process_payu_payment($order);
                case 'cashfree':
                    return $this->process_cashfree_payment($order);
                case 'manual':
                default:
                    return $this->process_manual_payment($order);
            }
        }

        /**
         * Process Razorpay Payment
         */
        private function process_razorpay_payment($order) {
            // Mark as pending
            $order->update_status('pending', __('Awaiting UPI payment', 'aakaari-brand'));

            // Create Razorpay order
            $razorpay_order = $this->create_razorpay_order($order);

            if (!$razorpay_order) {
                wc_add_notice(__('Unable to process payment. Please try again.', 'aakaari-brand'), 'error');
                return array('result' => 'fail');
            }

            // Save Razorpay order ID
            $order->update_meta_data('_razorpay_order_id', $razorpay_order['id']);
            $order->save();

            // Reduce stock levels
            wc_reduce_stock_levels($order->get_id());

            // Remove cart
            WC()->cart->empty_cart();

            // Return success and redirect to receipt page
            return array(
                'result' => 'success',
                'redirect' => $order->get_checkout_payment_url(true)
            );
        }

        /**
         * Create Razorpay Order
         */
        private function create_razorpay_order($order) {
            $api_url = $this->testmode
                ? 'https://api.razorpay.com/v1/orders'
                : 'https://api.razorpay.com/v1/orders';

            $amount = (int)($order->get_total() * 100); // Convert to paise

            $data = array(
                'amount' => $amount,
                'currency' => get_woocommerce_currency(),
                'receipt' => 'order_' . $order->get_id(),
                'notes' => array(
                    'woocommerce_order_id' => $order->get_id(),
                )
            );

            $response = wp_remote_post($api_url, array(
                'method' => 'POST',
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode($this->razorpay_key_id . ':' . $this->razorpay_key_secret)
                ),
                'body' => json_encode($data),
                'timeout' => 70
            ));

            if (is_wp_error($response)) {
                return false;
            }

            $body = json_decode(wp_remote_retrieve_body($response), true);
            return $body;
        }

        /**
         * Process PayU Payment
         */
        private function process_payu_payment($order) {
            // Mark as pending
            $order->update_status('pending', __('Awaiting UPI payment', 'aakaari-brand'));

            // Reduce stock levels
            wc_reduce_stock_levels($order->get_id());

            // Remove cart
            WC()->cart->empty_cart();

            // Return success and redirect to receipt page
            return array(
                'result' => 'success',
                'redirect' => $order->get_checkout_payment_url(true)
            );
        }

        /**
         * Process Cashfree Payment
         */
        private function process_cashfree_payment($order) {
            // Mark as pending
            $order->update_status('pending', __('Awaiting UPI payment', 'aakaari-brand'));

            // Reduce stock levels
            wc_reduce_stock_levels($order->get_id());

            // Remove cart
            WC()->cart->empty_cart();

            // Return success and redirect to receipt page
            return array(
                'result' => 'success',
                'redirect' => $order->get_checkout_payment_url(true)
            );
        }

        /**
         * Process Manual UPI Payment
         */
        private function process_manual_payment($order) {
            // Mark as on-hold
            $order->update_status('on-hold', __('Awaiting UPI payment confirmation', 'aakaari-brand'));

            // Add order note
            $order->add_order_note(__('Customer redirected to UPI payment page. Awaiting payment confirmation.', 'aakaari-brand'));

            // Reduce stock levels
            wc_reduce_stock_levels($order->get_id());

            // Remove cart
            WC()->cart->empty_cart();

            // Return success and redirect to receipt page
            return array(
                'result' => 'success',
                'redirect' => $order->get_checkout_payment_url(true)
            );
        }

        /**
         * Receipt page
         */
        public function receipt_page($order_id) {
            $order = wc_get_order($order_id);

            if ($this->provider === 'razorpay') {
                $this->razorpay_receipt_page($order);
            } elseif ($this->provider === 'payu') {
                $this->payu_receipt_page($order);
            } elseif ($this->provider === 'cashfree') {
                $this->cashfree_receipt_page($order);
            } else {
                $this->manual_receipt_page($order);
            }
        }

        /**
         * Razorpay receipt page
         */
        private function razorpay_receipt_page($order) {
            $razorpay_order_id = $order->get_meta('_razorpay_order_id');

            ?>
            <div id="razorpay-payment-container" style="text-align: center; padding: 30px;">
                <h3><?php _e('Complete Your Payment', 'aakaari-brand'); ?></h3>
                <p><?php _e('Click the button below to pay with UPI', 'aakaari-brand'); ?></p>
                <button id="rzp-button" class="button alt" style="margin-top: 20px;">
                    <?php _e('Pay Now', 'aakaari-brand'); ?>
                </button>
            </div>

            <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
            <script>
            var options = {
                "key": "<?php echo esc_js($this->razorpay_key_id); ?>",
                "amount": "<?php echo esc_js($order->get_total() * 100); ?>",
                "currency": "<?php echo esc_js(get_woocommerce_currency()); ?>",
                "name": "<?php echo esc_js(get_bloginfo('name')); ?>",
                "description": "<?php echo esc_js('Order #' . $order->get_id()); ?>",
                "order_id": "<?php echo esc_js($razorpay_order_id); ?>",
                "handler": function (response) {
                    window.location.href = "<?php echo esc_url($this->get_return_url($order)); ?>";
                },
                "prefill": {
                    "name": "<?php echo esc_js($order->get_billing_first_name() . ' ' . $order->get_billing_last_name()); ?>",
                    "email": "<?php echo esc_js($order->get_billing_email()); ?>",
                    "contact": "<?php echo esc_js($order->get_billing_phone()); ?>"
                },
                "theme": {
                    "color": "#000000"
                },
                "method": {
                    "upi": true
                }
            };

            var rzp = new Razorpay(options);
            document.getElementById('rzp-button').onclick = function(e){
                rzp.open();
                e.preventDefault();
            }
            </script>
            <?php
        }

        /**
         * PayU receipt page
         */
        private function payu_receipt_page($order) {
            // PayU integration would go here
            echo '<p>' . __('PayU integration coming soon', 'aakaari-brand') . '</p>';
        }

        /**
         * Cashfree receipt page
         */
        private function cashfree_receipt_page($order) {
            // Cashfree integration would go here
            echo '<p>' . __('Cashfree integration coming soon', 'aakaari-brand') . '</p>';
        }

        /**
         * Manual UPI receipt page
         */
        private function manual_receipt_page($order) {
            $upi_id = $this->upi_id;
            $payee_name = $this->payee_name;
            $amount = $order->get_total();
            $qr_code = $this->qr_code_upload;

            // Generate UPI payment link
            $upi_link = $this->generate_upi_link($upi_id, $payee_name, $amount, $order->get_id());

            ?>
            <div class="manual-upi-payment" style="max-width: 500px; margin: 0 auto; padding: 30px; text-align: center;">
                <h3><?php _e('Complete Your UPI Payment', 'aakaari-brand'); ?></h3>

                <?php if ($qr_code) : ?>
                    <div class="upi-qr-code" style="margin: 30px 0;">
                        <img src="<?php echo esc_url($qr_code); ?>" alt="UPI QR Code" style="max-width: 300px; width: 100%; height: auto; border: 2px solid #e5e7eb; border-radius: 8px; padding: 10px;">
                        <p style="margin-top: 15px; color: #666;">
                            <?php _e('Scan this QR code with any UPI app', 'aakaari-brand'); ?>
                        </p>
                    </div>
                <?php endif; ?>

                <div class="upi-details" style="background: #f9fafb; padding: 20px; border-radius: 8px; margin: 20px 0;">
                    <p style="margin: 10px 0;"><strong><?php _e('UPI ID:', 'aakaari-brand'); ?></strong> <?php echo esc_html($upi_id); ?></p>
                    <p style="margin: 10px 0;"><strong><?php _e('Amount:', 'aakaari-brand'); ?></strong> <?php echo wc_price($amount); ?></p>
                    <p style="margin: 10px 0;"><strong><?php _e('Order ID:', 'aakaari-brand'); ?></strong> #<?php echo esc_html($order->get_id()); ?></p>
                </div>

                <?php if ($upi_link) : ?>
                    <div class="upi-pay-button" style="margin: 30px 0;">
                        <a href="<?php echo esc_url($upi_link); ?>" class="button alt" style="padding: 15px 30px; font-size: 16px;">
                            <?php _e('Pay with UPI App', 'aakaari-brand'); ?>
                        </a>
                        <p style="margin-top: 15px; font-size: 14px; color: #666;">
                            <?php _e('This will open your UPI app', 'aakaari-brand'); ?>
                        </p>
                    </div>
                <?php endif; ?>

                <div class="payment-confirmation" style="margin: 30px 0; padding: 20px; background: #fef3c7; border-radius: 8px;">
                    <p style="margin: 0; color: #92400e;">
                        <strong><?php _e('Important:', 'aakaari-brand'); ?></strong>
                        <?php _e('After making the payment, please wait for confirmation. Do not close this page.', 'aakaari-brand'); ?>
                    </p>
                </div>

                <form method="post" action="<?php echo esc_url(wc_get_endpoint_url('order-received', $order->get_id(), wc_get_checkout_url())); ?>">
                    <input type="hidden" name="order_id" value="<?php echo esc_attr($order->get_id()); ?>">
                    <button type="submit" class="button" style="margin-top: 20px;">
                        <?php _e('I have completed the payment', 'aakaari-brand'); ?>
                    </button>
                </form>
            </div>
            <?php
        }

        /**
         * Generate UPI payment link
         */
        private function generate_upi_link($upi_id, $payee_name, $amount, $order_id) {
            if (empty($upi_id)) {
                return false;
            }

            $params = array(
                'pa' => $upi_id,
                'pn' => $payee_name,
                'am' => $amount,
                'cu' => get_woocommerce_currency(),
                'tn' => 'Order #' . $order_id
            );

            return 'upi://pay?' . http_build_query($params);
        }

        /**
         * Webhook handler
         */
        public function webhook() {
            $provider = $this->provider;

            if ($provider === 'razorpay') {
                $this->razorpay_webhook();
            } elseif ($provider === 'payu') {
                $this->payu_webhook();
            } elseif ($provider === 'cashfree') {
                $this->cashfree_webhook();
            }
        }

        /**
         * Razorpay webhook
         */
        private function razorpay_webhook() {
            $webhook_secret = $this->razorpay_key_secret;
            $webhook_signature = $_SERVER['HTTP_X_RAZORPAY_SIGNATURE'] ?? '';
            $webhook_body = file_get_contents('php://input');

            // Verify signature
            $expected_signature = hash_hmac('sha256', $webhook_body, $webhook_secret);

            if ($webhook_signature !== $expected_signature) {
                status_header(400);
                exit;
            }

            $data = json_decode($webhook_body, true);

            if ($data['event'] === 'payment.captured') {
                $payment_id = $data['payload']['payment']['entity']['id'];
                $order_id = $data['payload']['payment']['entity']['notes']['woocommerce_order_id'] ?? 0;

                if ($order_id) {
                    $order = wc_get_order($order_id);
                    if ($order && $order->get_status() !== 'completed') {
                        $order->payment_complete($payment_id);
                        $order->add_order_note(sprintf(__('Payment successful via Razorpay UPI. Payment ID: %s', 'aakaari-brand'), $payment_id));
                    }
                }
            }

            status_header(200);
            exit;
        }

        /**
         * PayU webhook
         */
        private function payu_webhook() {
            // PayU webhook handling would go here
            status_header(200);
            exit;
        }

        /**
         * Cashfree webhook
         */
        private function cashfree_webhook() {
            // Cashfree webhook handling would go here
            status_header(200);
            exit;
        }
    }
}
