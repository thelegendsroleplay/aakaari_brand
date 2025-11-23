# Legal Documents Implementation Guide

**For Herrenn (Aakaari)**

This guide will help you implement all legal documents on your WordPress website.

---

## 📋 Documents Created

We've created 5 comprehensive legal documents for your e-commerce website:

1. ✅ **Terms and Conditions** (`TERMS-AND-CONDITIONS.md`)
2. ✅ **Privacy Policy** (`PRIVACY-POLICY.md`)
3. ✅ **Cancellation and Refund Policy** (`CANCELLATION-AND-REFUND-POLICY.md`)
4. ✅ **Shipping and Delivery Policy** (`SHIPPING-AND-DELIVERY-POLICY.md`)
5. ✅ **Contact Us** (`CONTACT-US.md`)

---

## 🎯 What's Special About These Documents

### Comprehensive Coverage

All documents include specific sections for:
- ✅ **Social Login** (Google, Facebook)
- ✅ **UPI Payments** (Razorpay, Manual UPI)
- ✅ **E-commerce Operations**
- ✅ **Indian Laws and Compliance**
- ✅ **GDPR/CCPA Compliance**

### Compliance Ready

- Information Technology Act, 2000
- Consumer Protection Act, 2019
- Indian privacy and data protection laws
- E-commerce regulations
- Payment gateway compliance

---

## 🚀 Quick Implementation Steps

### Step 1: Create WordPress Pages

1. **Login to WordPress Admin**
   - Go to your WordPress admin panel
   - Navigate to **Pages → Add New**

2. **Create 5 New Pages:**

   **Page 1: Terms and Conditions**
   - Title: "Terms and Conditions"
   - Slug: `terms-and-conditions`
   - Copy content from `TERMS-AND-CONDITIONS.md`
   - Paste in WordPress editor
   - Publish

   **Page 2: Privacy Policy**
   - Title: "Privacy Policy"
   - Slug: `privacy-policy`
   - Copy content from `PRIVACY-POLICY.md`
   - Set as Privacy Policy page in **Settings → Privacy**
   - Publish

   **Page 3: Cancellation & Refund Policy**
   - Title: "Cancellation and Refund Policy"
   - Slug: `cancellation-refund-policy`
   - Copy content from `CANCELLATION-AND-REFUND-POLICY.md`
   - Publish

   **Page 4: Shipping & Delivery Policy**
   - Title: "Shipping and Delivery Policy"
   - Slug: `shipping-delivery-policy`
   - Copy content from `SHIPPING-AND-DELIVERY-POLICY.md`
   - Publish

   **Page 5: Contact Us**
   - Title: "Contact Us"
   - Slug: `contact`
   - Copy content from `CONTACT-US.md`
   - Publish

### Step 2: Replace Placeholder Information

In ALL documents, find and replace:

**Email Addresses:**
- `[support@yourdomain.com]` → Your support email
- `[orders@yourdomain.com]` → Your orders email
- `[returns@yourdomain.com]` → Your returns email
- `[billing@yourdomain.com]` → Your billing email
- `[your-email@domain.com]` → Your general email

**Phone Numbers:**
- `[your-phone-number]` → Your phone number
- `[+91-XXXXXXXXXX]` → Your WhatsApp number

**Business Information:**
- `[Your Complete Business Address]` → Your actual address
- `[Your City]` → Your city
- `[Your GST Number]` → Your GST number
- `[Current Date]` → Today's date

**Website URLs:**
- `[Your Website URL]` → Your domain (e.g., herrenn.com)
- `[yourdomain.com]` → Your domain
- All internal links

**Other Placeholders:**
- `[Amount]` → Your free shipping threshold (e.g., ₹999)
- `[Grievance Officer Name]` → Actual name
- `[Current Year]` → 2025 (or current year)

### Step 3: Customize Specific Details

**Shipping Charges Table:**
Update the shipping charges in `SHIPPING-AND-DELIVERY-POLICY.md`:
```
| Order Value | Metro Cities | Non-Metro | Express |
|-------------|--------------|-----------|---------|
| Below ₹499 | ₹99 | ₹149 | ₹199 |
```
Replace with your actual charges.

**Business Hours:**
Update everywhere it appears:
- Modify timings if different
- Add any specific closed days
- Update response times if different

**Social Media Links:**
In `CONTACT-US.md`, update:
- Instagram URL
- Facebook URL
- Twitter URL
- Other social media

### Step 4: Add to Website Footer

1. **Go to Appearance → Menus**
2. **Create a new menu called "Legal"**
3. **Add pages:**
   - Terms and Conditions
   - Privacy Policy
   - Cancellation & Refund Policy
   - Shipping & Delivery Policy
   - Contact Us
4. **Assign to Footer location**

Or edit `footer.php` directly to add links.

### Step 5: Link Policies at Checkout

**For Registration Form:**
Edit `woocommerce/myaccount/form-login.php` (already done):
```php
<a href="<?php echo esc_url( get_privacy_policy_url() ); ?>" target="_blank">
    Terms of Service and Privacy Policy
</a>
```

**For Checkout Page:**
Add policy links in WooCommerce settings:
- Go to **WooCommerce → Settings → Advanced**
- Add terms and conditions page
- Customers must accept before checkout

### Step 6: Update WooCommerce Settings

1. **Set Privacy Policy Page:**
   - **Settings → Privacy**
   - Select your Privacy Policy page
   - Save

2. **Set Terms Page:**
   - **WooCommerce → Settings → Advanced**
   - Terms and Conditions
   - Select your Terms page
   - Enable checkbox: "Force customers to accept"
   - Save

---

## 📝 Document-Specific Instructions

### Terms and Conditions

**Key Sections:**
- ✅ Social login terms (Section 3)
- ✅ UPI payment terms (Section 6)
- ✅ 7-day replacement policy (Section 9)
- ✅ No refunds policy (Section 9)

**Important:**
- Link to this from registration checkbox
- Reference in order confirmation emails
- Display at checkout

### Privacy Policy

**Key Sections:**
- ✅ Social login data collection (Section 3)
- ✅ UPI payment data handling (Section 4)
- ✅ Cookie policy (Section 5)
- ✅ User rights (Section 9)

**Important:**
- Set as official Privacy Policy in WordPress
- Link from all forms collecting data
- Link in footer
- Reference in email signatures

**Social Login Specific:**
This policy includes detailed sections about:
- What data is collected from Google/Facebook
- How social login data is used
- Third-party privacy policies (Google, Facebook)
- How to disconnect social login
- What we DON'T access

**UPI Payment Specific:**
- What payment data is collected
- What is NOT stored (CVV, PIN)
- Payment gateway partner information
- Transaction data retention
- Refund processing

### Cancellation and Refund Policy

**Key Points:**
- ❌ **NO CANCELLATIONS** after payment
- ❌ **NO REFUNDS** under any circumstances
- ✅ **7-DAY REPLACEMENT ONLY**

**Important:**
- Make this VERY clear to customers
- Link on product pages
- Mention in order confirmation
- Display at checkout

**Customer Communication:**
Add this notice prominently:
```
⚠️ IMPORTANT POLICY:
• No cancellations after payment
• No refunds provided
• Only replacements within 7 days
• Product must be unused with tags
```

### Shipping and Delivery Policy

**Customize:**
- Update delivery timelines for your location
- Set actual shipping charges
- List your courier partners
- Set free shipping threshold

**Important:**
- Link on checkout page
- Mention in product descriptions
- Include in order confirmation emails

### Contact Us

**Must Update:**
- All email addresses
- Phone numbers
- WhatsApp number
- Office address
- Social media links
- Business hours

**Add to:**
- Main navigation menu
- Footer
- Help section
- Order confirmation emails

---

## 🔗 Where to Link These Policies

### 1. Website Footer

Add all 5 documents to footer:
```
[Terms & Conditions] | [Privacy Policy] | [Refund Policy]
[Shipping Policy] | [Contact Us]
```

### 2. Checkout Page

- Terms and Conditions (checkbox required)
- Privacy Policy (link)
- Refund Policy (link)
- Shipping Policy (link)

### 3. Registration/Login Page

- Terms and Conditions (link)
- Privacy Policy (link in checkbox)

### 4. Product Pages

- Shipping Policy (link near "Add to Cart")
- Refund Policy (link)

### 5. Order Confirmation Email

Include links to all policies.

### 6. Help/FAQ Section

Reference all policies with links.

---

## ⚠️ Legal Disclaimer

### Important Notes

1. **Review with Legal Counsel:**
   - These documents are templates
   - Have a lawyer review them
   - Customize for your specific business
   - Ensure compliance with local laws

2. **Keep Updated:**
   - Review annually
   - Update when services change
   - Keep dated versions
   - Notify users of significant changes

3. **Record Keeping:**
   - Keep copies of all versions
   - Document when changes were made
   - Track user acceptances
   - Maintain audit trail

### What These Documents Cover

✅ **Covered:**
- Social login (Google, Facebook)
- UPI payments (Razorpay, manual)
- E-commerce operations
- Indian compliance
- Basic GDPR/CCPA

❌ **Not Covered (May Need Separate Docs):**
- Specific product warranties
- B2B contracts
- Affiliate program terms
- Loyalty program terms
- Custom services

### Consult Professionals For:

- Company incorporation documents
- GST registration and filings
- Trademark registration
- Domain disputes
- Complex legal matters
- International shipping compliance

---

## 📊 Compliance Checklist

After implementing all documents:

- [ ] All placeholder text replaced
- [ ] Pages created in WordPress
- [ ] Links added to footer
- [ ] Privacy Policy set in WordPress settings
- [ ] Terms page set in WooCommerce
- [ ] Checkout shows terms acceptance
- [ ] Registration form links to policies
- [ ] Contact information is correct
- [ ] Email addresses are active
- [ ] Phone numbers are correct
- [ ] Social media links work
- [ ] Grievance officer assigned
- [ ] GST number added
- [ ] Business address updated
- [ ] All internal links work
- [ ] Documents reviewed by legal counsel
- [ ] Customer support team trained on policies
- [ ] Returns process documented
- [ ] Shipping charges confirmed
- [ ] Payment gateway terms match
- [ ] All dates are current

---

## 🎯 Quick Reference

### Document Purposes

| Document | Purpose | Required? |
|----------|---------|-----------|
| Terms & Conditions | Legal agreement with customers | **Essential** |
| Privacy Policy | Data protection disclosure | **Essential** (Legal requirement) |
| Cancellation & Refund | Return/refund process | **Essential** (Consumer law) |
| Shipping & Delivery | Shipping terms and timelines | **Essential** |
| Contact Us | Customer support information | **Recommended** |

### Legal Requirements

**Must Have (Legally Required in India):**
1. ✅ Terms and Conditions
2. ✅ Privacy Policy
3. ✅ Return/Refund Policy
4. ✅ Contact Information
5. ✅ Grievance Officer Details

**Recommended:**
1. ✅ Shipping Policy
2. ✅ FAQ Section
3. ✅ Size Guide
4. ✅ Care Instructions

---

## 📞 Need Help?

If you need assistance implementing these documents:

1. **For Technical Help:**
   - WordPress support for page creation
   - Theme developer for footer links
   - Web developer for custom integration

2. **For Legal Review:**
   - Consult with a lawyer
   - Ensure compliance with your jurisdiction
   - Review business-specific terms

3. **For Content Updates:**
   - Copywriter for tone and clarity
   - Translator if needed for regional languages
   - Proofreader for errors

---

## 🎉 You're All Set!

Once you've:
1. ✅ Created all pages in WordPress
2. ✅ Replaced all placeholders
3. ✅ Added links to footer
4. ✅ Set up required checkboxes
5. ✅ Reviewed with legal counsel

Your website will be **legally compliant** and **customer-friendly**!

---

## 📚 Additional Resources

### Indian E-commerce Laws

- Consumer Protection (E-Commerce) Rules, 2020
- Information Technology Act, 2000
- Legal Metrology (Packaged Commodities) Rules, 2011
- GST Laws

### Best Practices

- Update policies annually
- Inform users of changes
- Keep records of all versions
- Train staff on policies
- Monitor compliance regularly

---

**Congratulations!**

You now have a complete set of legal documents for your Herrenn e-commerce store. These documents cover all aspects of your business including social login and UPI payments.

© [Current Year] Aakaari. All rights reserved.
