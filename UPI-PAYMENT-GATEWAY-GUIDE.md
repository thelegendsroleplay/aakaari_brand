# UPI Payment Gateway Setup Guide

## Overview

Your Aakaari Brand theme now includes a comprehensive UPI payment gateway for WooCommerce with support for multiple payment providers and manual UPI payments.

## Features

✅ **Multiple Payment Providers**
- Razorpay (Fully Integrated)
- PayU (Coming Soon)
- Cashfree (Coming Soon)
- Manual UPI with QR Code

✅ **Razorpay Integration**
- Complete UPI payment flow
- Automatic payment verification
- Webhook support for real-time updates
- Test and live mode support

✅ **Manual UPI Support**
- Custom UPI ID
- QR Code display
- UPI deep links (opens UPI apps directly)
- Order tracking

✅ **WooCommerce Integration**
- Native payment gateway
- Order status management
- Stock reduction
- Email notifications

## What's Been Implemented

### Files Created/Modified

**New Files:**
- `inc/upi-payment-gateway.php` - Complete payment gateway implementation

**Modified Files:**
- `functions.php` - Added UPI gateway include

### Payment Gateway Features

1. **Admin Settings Panel**
   - Location: WooCommerce → Settings → Payments → UPI Payment
   - Configure payment provider
   - Add API credentials
   - Enable/disable test mode
   - Customize payment descriptions

2. **Razorpay Integration**
   - Secure checkout process
   - UPI-specific payment flow
   - Automatic order completion
   - Webhook for payment verification

3. **Manual UPI**
   - QR code display
   - UPI deep links
   - Order confirmation workflow
   - Payment instructions

## Setup Instructions

### Option 1: Razorpay (Recommended)

Razorpay is the most popular payment gateway in India with excellent UPI support.

#### Step 1: Create Razorpay Account

1. Go to [Razorpay Dashboard](https://dashboard.razorpay.com/)
2. Sign up for a free account
3. Complete KYC verification (required for live payments)
4. Note: You can test without KYC in test mode

#### Step 2: Get API Credentials

1. Log in to Razorpay Dashboard
2. Go to **Settings → API Keys**
3. Generate Test Keys first for testing
4. You'll see:
   - **Key ID** (starts with `rzp_test_` for test mode)
   - **Key Secret** (click "Generate Secret")
5. Copy both credentials

#### Step 3: Configure in WordPress

1. Go to **WordPress Admin → WooCommerce → Settings → Payments**
2. Find **UPI Payment** and click "Manage"
3. Configure the following:
   - ✅ **Enable/Disable**: Check to enable
   - **Title**: "UPI Payment" (or customize)
   - **Description**: "Pay with Google Pay, PhonePe, Paytm, BHIM"
   - ✅ **Test Mode**: Enable for testing
   - **Payment Provider**: Select "Razorpay"
   - **Razorpay Key ID**: Paste your Key ID
   - **Razorpay Key Secret**: Paste your Key Secret
4. Click **Save Changes**

#### Step 4: Set Up Webhooks (Important!)

Webhooks ensure automatic order status updates.

1. Go to **Razorpay Dashboard → Settings → Webhooks**
2. Click **"+ Add New Webhook"**
3. Configure:
   - **Webhook URL**: `https://yourdomain.com/?wc-api=aakaari_upi`
   - Replace `yourdomain.com` with your actual domain
   - **Alert Email**: Your email
   - **Secret**: Leave blank or generate one
4. Select events to receive:
   - ✅ **payment.captured**
   - ✅ **payment.failed**
5. Click **Create Webhook**

#### Step 5: Test Payment Flow

1. Add a product to cart
2. Go to checkout
3. Select **"UPI Payment"** as payment method
4. Complete the order
5. You'll see a "Pay Now" button
6. Click it to open Razorpay checkout
7. Use Razorpay test UPI: success@razorpay
8. Verify order status changes to "Processing"

#### Step 6: Go Live

1. Complete KYC verification in Razorpay
2. Get **Live API Keys** from Razorpay Dashboard
3. Go back to WordPress payment settings
4. **Disable Test Mode**
5. Replace test keys with live keys
6. Save changes
7. Test with real payments (small amount)

### Option 2: Manual UPI (No Payment Gateway Required)

Use this if you don't want to use a payment gateway or for direct UPI transfers.

#### Step 1: Enable Manual UPI

1. Go to **WordPress Admin → WooCommerce → Settings → Payments**
2. Find **UPI Payment** and click "Manage"
3. Configure:
   - ✅ **Enable/Disable**: Check to enable
   - **Title**: "UPI Payment"
   - **Description**: "Pay using any UPI app"
   - **Payment Provider**: Select "Manual UPI (QR Code)"

#### Step 2: Add Your UPI Details

1. **UPI ID**: Enter your UPI ID (e.g., `yourname@paytm`)
2. **Payee Name**: Your name or business name
3. **UPI QR Code**: (Optional but recommended)
   - Generate QR code from your UPI app
   - Upload to WordPress Media Library
   - Paste the image URL here

#### Step 3: Generate UPI QR Code

**Using Google Pay:**
1. Open Google Pay
2. Tap your profile photo
3. Tap "QR code"
4. Take screenshot
5. Upload to WordPress

**Using PhonePe:**
1. Open PhonePe
2. Tap "Profile" → "My QR"
3. Take screenshot
4. Upload to WordPress

**Using Online Generator:**
1. Go to [UPI QR Generator](https://www.upiqr.in/)
2. Enter your UPI ID
3. Download QR code
4. Upload to WordPress

#### Step 4: How Manual UPI Works

1. Customer places order
2. Order status: "On Hold"
3. Customer sees:
   - Your UPI QR code
   - UPI ID
   - Order amount
   - "Pay with UPI App" button (deep link)
4. Customer makes payment
5. Customer clicks "I have completed payment"
6. You verify payment manually
7. You mark order as "Processing" in admin

#### Step 5: Verify Manual Payments

1. Go to **WordPress Admin → WooCommerce → Orders**
2. Find orders with status "On Hold"
3. Check your UPI app for payments
4. Match order amount and Order ID
5. Update order status to "Processing"
6. Customer receives confirmation email

### Option 3: PayU (Coming Soon)

PayU integration is under development. To request priority implementation:
- Contact your developer
- Provide PayU merchant credentials

### Option 4: Cashfree (Coming Soon)

Cashfree integration is under development. To request priority implementation:
- Contact your developer
- Provide Cashfree credentials

## Payment Flow

### Razorpay Flow

```
1. Customer adds products to cart
2. Customer goes to checkout
3. Customer selects "UPI Payment"
4. Customer clicks "Place Order"
5. Order status: "Pending Payment"
6. Customer redirected to Razorpay page
7. Customer clicks "Pay Now"
8. Razorpay checkout opens
9. Customer selects UPI
10. Customer scans QR or enters VPA
11. Customer approves in UPI app
12. Razorpay captures payment
13. Webhook updates order status
14. Order status: "Processing"
15. Customer receives confirmation
```

### Manual UPI Flow

```
1. Customer adds products to cart
2. Customer goes to checkout
3. Customer selects "UPI Payment"
4. Customer clicks "Place Order"
5. Order status: "On Hold"
6. Customer sees QR code and UPI ID
7. Customer scans QR with UPI app
8. Customer completes payment
9. Customer clicks confirmation button
10. You receive order notification
11. You verify payment in UPI app
12. You update order status
13. Customer receives confirmation
```

## Settings Reference

### General Settings

| Setting | Description | Default |
|---------|-------------|---------|
| **Enable/Disable** | Turn the payment method on/off | Disabled |
| **Title** | Name shown at checkout | "UPI Payment" |
| **Description** | Description shown at checkout | "Pay securely using UPI..." |
| **Test Mode** | Use test credentials | Enabled |
| **Payment Provider** | Choose provider | Manual |

### Razorpay Settings

| Setting | Description | Where to Find |
|---------|-------------|---------------|
| **Key ID** | Razorpay API Key ID | Dashboard → Settings → API Keys |
| **Key Secret** | Razorpay API Secret | Dashboard → Settings → API Keys |

### Manual UPI Settings

| Setting | Description | Example |
|---------|-------------|---------|
| **UPI ID** | Your UPI Virtual Payment Address | `yourname@paytm` |
| **Payee Name** | Name for payment receipt | "Herrenn Store" |
| **QR Code URL** | URL to your UPI QR image | `https://site.com/qr.png` |

## Testing

### Testing with Razorpay

**Test Mode Credentials:**
- Already in your Razorpay test keys
- No real money is charged

**Test UPI IDs:**
- Success: `success@razorpay`
- Failure: `failure@razorpay`

**Test Flow:**
1. Enable test mode
2. Add test API keys
3. Place a test order
4. Use test UPI ID
5. Verify order completion

### Testing Manual UPI

1. Use a small amount (₹1)
2. Complete real payment
3. Verify you receive payment
4. Test order status workflow
5. Check email notifications

## Troubleshooting

### Common Issues

#### 1. "Payment method not available"

**Cause:** Gateway not enabled or missing credentials

**Solution:**
- Check gateway is enabled in settings
- Verify API credentials are entered
- Check currency is set to INR
- Ensure WooCommerce is active

#### 2. Razorpay checkout doesn't open

**Cause:** JavaScript error or invalid credentials

**Solution:**
- Check browser console for errors
- Verify Razorpay Key ID is correct
- Ensure test mode matches key type
- Clear WordPress and browser cache

#### 3. Webhook not working

**Cause:** Incorrect webhook URL or secret

**Solution:**
- Verify webhook URL: `https://yourdomain.com/?wc-api=aakaari_upi`
- Check HTTPS is enabled
- Ensure permalink structure is not "Plain"
- Test webhook in Razorpay dashboard

#### 4. Orders stuck in "Pending"

**Cause:** Webhook not configured or failing

**Solution:**
- Set up webhook in Razorpay
- Check webhook logs in Razorpay
- Manually update order status
- Check server error logs

#### 5. Manual UPI QR not showing

**Cause:** Invalid image URL

**Solution:**
- Upload image to WordPress Media
- Use direct image URL
- Check image is publicly accessible
- Verify URL is correct

#### 6. UPI deep link not working

**Cause:** Mobile browser restrictions

**Solution:**
- Test on different browsers
- Ensure UPI app is installed
- Use QR code as fallback
- Check UPI ID format

### Debug Mode

Enable WooCommerce logging:

1. Go to **WooCommerce → Status → Logs**
2. Look for `aakaari-upi-*` logs
3. Check for error messages
4. Share logs with support if needed

## Security Best Practices

### DO's ✅

- ✅ Always use HTTPS (SSL certificate)
- ✅ Keep API credentials secure
- ✅ Test in test mode first
- ✅ Set up webhooks for Razorpay
- ✅ Verify payments before shipping
- ✅ Keep WordPress and WooCommerce updated
- ✅ Use strong admin passwords
- ✅ Backup your site regularly

### DON'Ts ❌

- ❌ Never share API secrets publicly
- ❌ Don't skip test mode
- ❌ Don't hardcode credentials in files
- ❌ Don't ignore webhook failures
- ❌ Don't ship without payment confirmation
- ❌ Don't use HTTP (must be HTTPS)
- ❌ Don't commit credentials to Git

## Customization

### Change Payment Button Text

Edit `inc/upi-payment-gateway.php`, find line ~460:

```php
// Change from:
<?php _e('Pay Now', 'aakaari-brand'); ?>

// To:
<?php _e('Pay with UPI', 'aakaari-brand'); ?>
```

### Customize Receipt Page Design

Edit `inc/upi-payment-gateway.php`, look for `manual_receipt_page()` function around line ~520. Modify the HTML and inline styles.

### Add Custom UPI Apps

Edit `inc/upi-payment-gateway.php`, find `payment_fields()` function around line ~225:

```php
<span>Google Pay</span>
<span>PhonePe</span>
<span>Paytm</span>
<span>BHIM</span>
// Add your custom app
<span>Amazon Pay</span>
```

### Change Order Status

Edit `inc/upi-payment-gateway.php`, find `process_manual_payment()` around line ~320:

```php
// Change from "on-hold" to "pending"
$order->update_status('pending', __('Awaiting UPI payment', 'aakaari-brand'));
```

## Advanced Configuration

### Multiple Currency Support

Currently supports INR. To add more currencies, edit `inc/upi-payment-gateway.php`:

```php
public function is_available() {
    $currency = get_woocommerce_currency();
    if ($currency !== 'INR') {
        return false;
    }
    return parent::is_available();
}
```

### Minimum/Maximum Order Amount

Add to `__construct()` method:

```php
$this->supports = array('products');
$this->min_amount = 10; // Minimum ₹10
$this->max_amount = 100000; // Maximum ₹1,00,000
```

### Auto-approve Manual Payments

Not recommended for production, but for testing:

```php
private function process_manual_payment($order) {
    // Change from 'on-hold' to 'processing'
    $order->update_status('processing', __('Auto-approved', 'aakaari-brand'));
}
```

## FAQs

**Q: Do I need a business account for UPI?**
A: For Razorpay, yes (requires KYC). For manual UPI, your personal UPI works.

**Q: What are the transaction fees?**
A:
- Razorpay: 2% + GST per transaction
- Manual UPI: Zero fees (direct transfer)

**Q: Can I use multiple payment gateways?**
A: Yes, enable both UPI and other gateways (credit card, COD, etc.)

**Q: How long does it take to receive payment?**
A:
- Razorpay: Instant (settlements in 2 days)
- Manual UPI: Instant

**Q: Can customers get refunds?**
A:
- Razorpay: Yes, through Razorpay dashboard
- Manual UPI: Manual refund required

**Q: What if webhook fails?**
A: Orders stay "Pending". You can manually update status after verifying payment in Razorpay dashboard.

**Q: Can I customize the QR code size?**
A: Yes, edit the CSS in `manual_receipt_page()` function.

**Q: Does this work with WooCommerce subscriptions?**
A: Not yet. This is for one-time payments only.

**Q: Can I hide manual verification button?**
A: Yes, comment out the form section in `manual_receipt_page()`.

**Q: How to handle failed payments?**
A: Razorpay automatically marks them failed. For manual UPI, orders stay on-hold until you verify.

## Support & Resources

### Official Documentation

- [Razorpay Documentation](https://razorpay.com/docs/)
- [WooCommerce Payment Gateways](https://woocommerce.com/document/payment-gateway-api/)
- [UPI Specification](https://www.npci.org.in/what-we-do/upi/product-overview)

### Getting Help

1. Check this guide's troubleshooting section
2. Review WooCommerce status page
3. Check Razorpay dashboard logs
4. Enable debug logging
5. Contact support with error logs

## Changelog

### Version 1.0 (2025-11-23)
- ✅ Initial implementation
- ✅ Razorpay UPI integration
- ✅ Manual UPI with QR code
- ✅ WooCommerce payment gateway
- ✅ Webhook support
- ✅ Test mode support
- ✅ Comprehensive documentation

## Next Steps

1. ✅ Choose your payment provider (Razorpay or Manual)
2. ✅ Set up credentials in WooCommerce settings
3. ✅ Test payment flow thoroughly
4. ✅ Configure webhooks (for Razorpay)
5. ✅ Go live with real payments
6. ✅ Monitor transactions regularly

---

**Ready to Accept UPI Payments?**

Follow the setup instructions above and start accepting UPI payments in minutes!

**Need Help?**
Refer to the troubleshooting section or check WooCommerce → Status → Logs for detailed error messages.
