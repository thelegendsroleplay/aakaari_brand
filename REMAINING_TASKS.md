# Remaining Tasks

## ✅ COMPLETED
1. ✅ Account page header fixed (entry-header hidden)
2. ✅ Cart page now loads Figma design correctly
3. ✅ Ticket system removed and replaced with live chat
4. ✅ Auth pages (login/register) headers fixed
5. ✅ Checkout page headers fixed

---

## 🔨 REMAINING ISSUES TO FIX

### Issue #1: Wishlist Page - "No content found"
**Problem:** Wishlist page shows "No content found"

**Solution:**
1. Check if YITH WooCommerce Wishlist plugin is installed
2. If not, install it from WordPress plugins
3. Create template: `woocommerce/wishlist.php`
4. Copy design from Figma (wishlist context exists in `fig/src/contexts/WishlistContext.tsx`)
5. Style with wishlist.css

**Files Needed:**
- `woocommerce/wishlist.php` (template)
- `assets/css/wishlist.css` (styling)

---

### Issue #3: Category Pages (T-shirts, Hoodies, Sale) Not Matching Figma

**Problem:** T-shirt, hoodies, sale pages don't functionally work properly and don't match Figma design

**Current File:** `woocommerce/archive-product.php` (exists but may need updates)

**Solution:**
1. Check `fig/src/pages/products/ProductsPage.tsx` and `products.css`
2. Update `archive-product.php` to match Figma exactly
3. Ensure category filters work
4. Add sale page template if needed: `woocommerce/archive-product-sale.php`
5. Test filtering and sorting

**Files to Check/Update:**
- `woocommerce/archive-product.php`
- `assets/css/products.css` or `shop.css`
- May need `taxonomy-product_cat.php` for specific categories

---

### Issue #4: Order Tracking Page

**Problem:** Order tracking page from Figma needs to be converted

**Figma Source:** `fig/src/pages/orders/OrdersPage.tsx` and `orders.css`

**Solution:**
1. Create `page-order-tracking.php` template
2. Copy `fig/src/pages/orders/orders.css` to `assets/css/orders.css`
3. Convert React OrdersPage component to PHP
4. Integrate with WooCommerce orders
5. Add "Track Order" functionality with order number lookup
6. Update My Account page to link to orders

**Files to Create:**
- `page-order-tracking.php` (order tracking page)
- `assets/css/orders.css` (styling)
- Update `woocommerce/myaccount/my-account.php` to add Orders tab link

---

### Issue #7: Product Detail Page

**Problem:** Need to convert and replace current WooCommerce product page with Figma design

**Figma Source:** `fig/src/pages/product-detail/ProductDetailPage.tsx` and `product-detail.css`

**Current File:** Likely need to create `woocommerce/single-product.php`

**Solution:**
1. Copy `fig/src/pages/product-detail/product-detail.css` to `assets/css/product-detail.css`
2. Create/update `woocommerce/single-product.php`
3. Create `woocommerce/content-single-product.php` for product content
4. Features to include:
   - Image gallery with thumbnails
   - Size selector
   - Color selector
   - Quantity selector
   - Add to cart button
   - Product tabs (Description, Reviews, Shipping)
   - Related products
   - Breadcrumbs

**Files to Create/Update:**
- `woocommerce/single-product.php`
- `woocommerce/content-single-product.php`
- `assets/css/product-detail.css`

---

## 📋 QUICK REFERENCE: Figma Files Location

All Figma source files are in: `fig/src/pages/`

```
fig/src/pages/
├── orders/
│   ├── OrdersPage.tsx
│   └── orders.css
├── product-detail/
│   ├── ProductDetailPage.tsx
│   └── product-detail.css
├── products/
│   ├── ProductsPage.tsx
│   └── products.css
└── support/
    ├── SupportPage.tsx
    └── support.css
```

---

## 🎯 PRIORITY ORDER

**High Priority (Critical for functionality):**
1. Product Detail Page (Issue #7) - Users can't view product details properly
2. Category Pages (Issue #3) - Shop functionality broken
3. Order Tracking (Issue #4) - Customer service feature

**Medium Priority:**
4. Wishlist Page (Issue #1) - Nice to have feature

---

## 🔧 HOW TO CONTINUE

### Option 1: Continue in New Session
Due to context limits, start a new session and say:
> "Continue from REMAINING_TASKS.md - Let's start with the Product Detail Page (Issue #7)"

### Option 2: Manual Conversion
1. Copy CSS files from `fig/src/pages/` to `assets/css/`
2. Convert React components (.tsx) to PHP following existing patterns
3. Use cart.php and checkout.php as examples for conversion

---

## 📝 CONVERSION PATTERN

When converting React to PHP:

**React:**
```tsx
const ProductPage = () => {
  return (
    <div className="product-page">
      <h1>{product.name}</h1>
    </div>
  );
}
```

**PHP:**
```php
<?php
get_header();
?>
<div class="product-page">
  <h1><?php echo esc_html( $product->get_name() ); ?></h1>
</div>
<?php
get_footer();
?>
```

---

## 🗂️ FILES CREATED SO FAR

✅ **Auth Pages:**
- `woocommerce/myaccount/form-login.php`
- `woocommerce/myaccount/form-register.php`
- `woocommerce/myaccount/form-lost-password.php`
- `assets/css/auth.css`

✅ **Cart Page:**
- `woocommerce/cart/cart.php`
- `assets/css/cart.css`

✅ **Checkout Page:**
- `woocommerce/checkout/form-checkout.php`
- `woocommerce/checkout/review-order.php`
- `assets/css/checkout.css`

✅ **Account Page:**
- `woocommerce/myaccount/my-account.php`
- `assets/css/account.css`

✅ **Live Chat:**
- Added to `footer.php`
- Setup guide in `LIVE_CHAT_SETUP.md`

---

## 🚀 GIT STATUS

**Branch:** `claude/check-this-011CUsAAsBfnBACnXJ56tVdk`

**Latest Commit:** Replace ticket system with live chat support

**To Push New Changes:**
```bash
git add -A
git commit -m "Your commit message"
git push -u origin claude/check-this-011CUsAAsBfnBACnXJ56tVdk
```
