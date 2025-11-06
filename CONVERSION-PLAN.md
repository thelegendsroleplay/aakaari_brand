# WordPress + WooCommerce Conversion Plan

## Overview
Converting 11 page types from Figma/React to WordPress + WooCommerce templates with their respective CSS files.

---

## 📁 Pages to Convert

### 1. **Home Page** ✅ (Already Done)
- **Source**: `new/home/` (694 lines CSS)
- **WordPress Files**:
  - `homepage.php` (template)
  - `inc/homepage.php` (functions)
  - `assets/css/homepage.css`
  - `assets/js/homepage.js`
- **Status**: Already converted in current branch

---

### 2. **Shop Page**
- **Source**: `new/shop/` (34 lines CSS)
- **WordPress Approach**: WooCommerce Archive Override
- **Files to Create**:
  - `woocommerce/archive-product.php` (override WooCommerce shop template)
  - `inc/shop.php` (custom filters and layout functions)
  - `assets/css/shop.css`
  - `assets/js/shop.js`
- **Features**:
  - Product grid with filters
  - Category sidebar
  - Sort options
  - Price filters
  - View switcher (grid/list)

---

### 3. **Product Detail Page**
- **Source**: `new/product/` (CSS lines TBD)
- **WordPress Approach**: WooCommerce Single Product Override
- **Files to Create**:
  - `woocommerce/single-product.php`
  - `woocommerce/single-product/` (component templates)
  - `inc/product.php`
  - `assets/css/product.css`
  - `assets/js/product.js`
- **Features**:
  - Image gallery with zoom
  - Product info tabs
  - Reviews section
  - Related products
  - Size guide
  - Add to cart with variations

---

### 4. **Cart Page**
- **Source**: `new/cart/` (76 lines CSS)
- **WordPress Approach**: WooCommerce Cart Override
- **Files to Create**:
  - `woocommerce/cart/cart.php`
  - `inc/cart.php`
  - `assets/css/cart.css`
  - `assets/js/cart.js`
- **Features**:
  - Cart items table
  - Quantity updaters
  - Remove items
  - Coupon codes
  - Cart totals
  - Proceed to checkout

---

### 5. **Checkout Page**
- **Source**: `new/checkout/` (118 lines CSS)
- **WordPress Approach**: WooCommerce Checkout Override
- **Files to Create**:
  - `woocommerce/checkout/form-checkout.php`
  - `inc/checkout.php`
  - `assets/css/checkout.css`
  - `assets/js/checkout.js`
- **Features**:
  - Multi-step checkout
  - Billing/shipping forms
  - Payment methods
  - Order review
  - Place order

---

### 6. **Authentication (Login/Signup)**
- **Source**: `new/auth/` (264 lines CSS)
- **WordPress Approach**: Custom Page Template + WooCommerce My Account Override
- **Files to Create**:
  - `page-login.php` (custom template)
  - `woocommerce/myaccount/form-login.php` (WooCommerce override)
  - `inc/auth.php`
  - `assets/css/auth.css`
  - `assets/js/auth.js`
- **Features**:
  - Login form
  - Registration form
  - Social login placeholders
  - Password reset
  - Remember me

---

### 7. **User Dashboard**
- **Source**: `new/user-dashboard/` (641 lines CSS)
- **WordPress Approach**: WooCommerce My Account Override
- **Files to Create**:
  - `woocommerce/myaccount/my-account.php`
  - `woocommerce/myaccount/dashboard.php`
  - `woocommerce/myaccount/orders.php`
  - `inc/user-dashboard.php`
  - `assets/css/user-dashboard.css`
  - `assets/js/user-dashboard.js`
- **Features**:
  - Dashboard overview
  - Order history
  - Addresses
  - Payment methods
  - Account details
  - Wishlist integration

---

### 8. **Admin Dashboard**
- **Source**: `new/admin/` (CSS lines TBD)
- **WordPress Approach**: Custom Admin Page (WordPress Admin Area)
- **Files to Create**:
  - `inc/admin/` (folder for admin functions)
  - `inc/admin/dashboard.php`
  - `inc/admin/products.php`
  - `inc/admin/orders.php`
  - `assets/css/admin.css`
  - `assets/js/admin.js`
- **Features**:
  - Sales statistics
  - Product management
  - Order management
  - Customer data
  - Analytics
- **Note**: This extends WordPress/WooCommerce admin, not front-end

---

### 9. **Search Page**
- **Source**: `new/search/` (CSS lines TBD)
- **WordPress Approach**: Custom Search Template
- **Files to Create**:
  - `search.php` (WordPress search template)
  - `inc/search.php`
  - `assets/css/search.css`
  - `assets/js/search.js`
- **Features**:
  - Search results
  - Filter by category/price
  - Search suggestions
  - Recent searches
  - Product results

---

### 10. **Wishlist Page**
- **Source**: `new/wishlist/` (CSS lines TBD)
- **WordPress Approach**: Custom Page Template + Plugin Integration
- **Files to Create**:
  - `page-wishlist.php` (template)
  - `inc/wishlist.php`
  - `assets/css/wishlist.css`
  - `assets/js/wishlist.js`
- **Features**:
  - Wishlist grid
  - Add/remove items
  - Move to cart
  - Share wishlist
  - Stock alerts
  - Price drop alerts
- **Note**: May integrate with YITH WooCommerce Wishlist or custom solution

---

### 11. **Static Pages** (About, Contact, FAQ, etc.)
- **Source**: `new/static/` (CSS lines TBD)
- **WordPress Approach**: Multiple Custom Page Templates
- **Files to Create**:
  - `page-about.php`
  - `page-contact.php`
  - `page-faq.php`
  - `page-shipping.php`
  - `page-privacy.php`
  - `inc/static-pages.php`
  - `assets/css/static.css`
  - `assets/js/static.js`
- **Features**:
  - About us with team
  - Contact form
  - FAQ accordion
  - Shipping info
  - Privacy policy
  - Terms of service

---

## 🏗️ File Structure

```
fashionmen/
├── assets/
│   ├── css/
│   │   ├── homepage.css ✅
│   │   ├── shop.css
│   │   ├── product.css
│   │   ├── cart.css
│   │   ├── checkout.css
│   │   ├── auth.css
│   │   ├── user-dashboard.css
│   │   ├── admin.css
│   │   ├── search.css
│   │   ├── wishlist.css
│   │   └── static.css
│   └── js/
│       ├── homepage.js ✅
│       ├── shop.js
│       ├── product.js
│       ├── cart.js
│       ├── checkout.js
│       ├── auth.js
│       ├── user-dashboard.js
│       ├── admin.js
│       ├── search.js
│       ├── wishlist.js
│       └── static.js
├── inc/
│   ├── homepage.php ✅
│   ├── shop.php
│   ├── product.php
│   ├── cart.php
│   ├── checkout.php
│   ├── auth.php
│   ├── user-dashboard.php
│   ├── search.php
│   ├── wishlist.php
│   ├── static-pages.php
│   └── admin/
│       ├── dashboard.php
│       ├── products.php
│       └── orders.php
├── woocommerce/
│   ├── archive-product.php (Shop)
│   ├── single-product.php (Product Detail)
│   ├── cart/
│   │   └── cart.php
│   ├── checkout/
│   │   └── form-checkout.php
│   └── myaccount/
│       ├── my-account.php
│       ├── dashboard.php
│       ├── form-login.php
│       └── orders.php
├── homepage.php ✅
├── page-login.php
├── page-wishlist.php
├── page-about.php
├── page-contact.php
├── page-faq.php
├── page-shipping.php
├── page-privacy.php
├── search.php
├── functions.php
├── header.php
├── footer.php
├── style.css
└── index.php
```

---

## 🔄 Conversion Process

### For Each Page:

1. **Extract CSS from Figma folder**
   - Copy `styles.css` from `new/{page}/`
   - Review and adapt for WordPress structure
   - Ensure mobile responsiveness

2. **Create WordPress Template**
   - Determine template type (page template, WooCommerce override, etc.)
   - Create main template file
   - Add WordPress template header comment

3. **Create Functions File**
   - Create `inc/{page}.php`
   - Extract reusable functions
   - Add WordPress/WooCommerce integration

4. **Create JavaScript File**
   - Port interactions from React/TypeScript
   - Use vanilla JS or jQuery (WordPress standard)
   - Add WordPress script enqueuing

5. **Update functions.php**
   - Add conditional CSS/JS enqueuing
   - Register new page templates
   - Add any custom post types or taxonomies

6. **Test Integration**
   - Verify template loads correctly
   - Check WooCommerce compatibility
   - Test responsive design
   - Validate HTML/CSS

---

## 🎯 Priority Order

### Phase 1: E-commerce Core (High Priority)
1. ✅ Home page (Done)
2. Shop page
3. Product detail page
4. Cart page
5. Checkout page

### Phase 2: User Experience (Medium Priority)
6. Authentication (Login/Signup)
7. User Dashboard
8. Wishlist

### Phase 3: Supporting Pages (Medium Priority)
9. Search page
10. Static pages (About, Contact, FAQ, etc.)

### Phase 4: Admin (Low Priority - Can use default WooCommerce)
11. Admin Dashboard (optional enhancement)

---

## ⚙️ Technical Considerations

### WooCommerce Integration
- Use WooCommerce hooks and filters
- Override templates by copying to theme
- Don't modify WooCommerce core files
- Use `woocommerce_after_*` and `woocommerce_before_*` hooks

### CSS Strategy
- Keep CSS modular per page
- Load conditionally based on page/template
- Use same design system from Figma
- Maintain mobile-first approach

### JavaScript Strategy
- Convert React components to vanilla JS
- Use WordPress jQuery if needed
- Enqueue scripts conditionally
- Add inline scripts for dynamic data

### Data & Content
- Use WooCommerce for products/cart/checkout
- Use WordPress posts/pages for content
- Use Customizer for editable content
- Create demo data import option

---

## 📊 Estimated Timeline

- **Home page**: ✅ Complete
- **Shop page**: 2-3 hours
- **Product page**: 3-4 hours
- **Cart page**: 2 hours
- **Checkout page**: 3-4 hours
- **Auth pages**: 2-3 hours
- **User Dashboard**: 4-5 hours
- **Wishlist**: 2-3 hours
- **Search page**: 2 hours
- **Static pages**: 3-4 hours
- **Admin dashboard**: 4-5 hours (optional)

**Total**: ~30-40 hours of development

---

## 🚀 Next Steps

1. **Confirm approach** with user
2. **Choose starting point** (recommend Phase 1)
3. **Begin conversion** one page at a time
4. **Test each page** before moving to next
5. **Document** any customizations needed
6. **Commit and push** progress regularly

---

## 📝 Questions for User

1. Do you want me to convert ALL pages or focus on specific ones first?
2. Should I follow the priority order (E-commerce core first)?
3. Do you want the admin dashboard or can we use default WooCommerce admin?
4. Any specific pages you need urgently?
5. Should I create demo content/products for testing?
