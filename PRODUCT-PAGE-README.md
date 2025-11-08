# WooCommerce Product Page - Implementation Guide

This document explains the WooCommerce single product page implementation for the Aakaari theme.

## Overview

The product page has been fully integrated with WooCommerce and includes:

1. **Dynamic Product Data** - All product information (title, price, images, attributes, variations, stock) is pulled from WooCommerce
2. **Product Tabs** - Description, Size Chart, and Reviews tabs
3. **Working Add-to-Cart** - Full support for simple and variable products
4. **Review System** - Display and submission of product reviews with rating bars
5. **Related Products** - "You May Also Like" section with working add-to-cart buttons
6. **Mobile-First Design** - Fully responsive layouts optimized for mobile devices

## Files Modified

- `functions.php` - Added WooCommerce product tabs filters and review/size chart functionality
- `woocommerce/content-single-product.php` - Main product page template with dynamic WooCommerce data
- `woocommerce/single-product.php` - Single product wrapper template
- `assets/css/product-detail.css` - Comprehensive mobile-first styles for all product page sections
- `assets/js/product-detail.js` - UI interaction handlers (gallery, quantity, variations, add-to-cart)

## Features

### 1. Product Information
- **Dynamic Data**: Product title, price, SKU, category, images, and stock pulled from WooCommerce
- **Variable Products**: Automatic attribute selection (colors, sizes) with variation price updates
- **Image Gallery**: Thumbnail navigation with main image display
- **Ratings**: Star display with average rating and review count

### 2. Product Tabs

#### Description Tab
- Displays the full product description from WooCommerce
- Uses `the_content()` for full product description

#### Size Chart Tab
- **Per-Product Size Chart**: Add custom size chart using product meta field `_size_chart`
- **Default Size Chart**: If no custom size chart is set, displays a standard clothing size chart
- See "Size Chart Configuration" below for details

#### Reviews Tab
- **Rating Summary**: Shows average rating with visual star display
- **Rating Bars**: Breakdown of 5-star to 1-star reviews with percentage bars
- **Reviews List**: All approved product reviews with author, date, rating, and comment
- **Add Review Form**: Mobile-friendly review submission with rating selector (1-5 stars)
- **Verification**: Respects WooCommerce review verification settings

### 3. Related Products
- Displays up to 8 related products based on category/tags
- Each product shows:
  - Product image with hover effect
  - "Sale" badge for on-sale products
  - Product title (clickable)
  - Star rating and review count
  - Price (with sale price styling)
  - Working "Add to Cart" button
- Responsive grid layout (4 columns on desktop, 2 on mobile)

### 4. Add to Cart Functionality
- **Simple Products**: Direct add-to-cart
- **Variable Products**:
  - Attribute selection (colors, sizes, etc.)
  - Automatic variation matching
  - Price updates when variation selected
- **Buy Now**: Adds to cart and redirects to checkout
- **Quantity Selection**: +/- buttons with stock validation
- **Stock Display**: Shows available quantity or "Out of stock"

## Size Chart Configuration

There are three ways to set up size charts:

### Option 1: Product Meta Field (Recommended)

Add a custom field to the product:

1. Edit product in WordPress admin
2. Scroll to "Custom Fields" section
3. Add new custom field:
   - **Name**: `_size_chart`
   - **Value**: HTML table or text for your size chart

Example value:
```html
<table>
  <tr><th>Size</th><th>Chest</th><th>Waist</th></tr>
  <tr><td>S</td><td>36-38"</td><td>30-32"</td></tr>
  <tr><td>M</td><td>38-40"</td><td>32-34"</td></tr>
</table>
```

### Option 2: Product Attribute

Create a product attribute named "Size Chart" and add the chart data as attribute value.

### Option 3: Default Chart

If no custom size chart is provided, a standard clothing size chart is displayed automatically.

## Mobile-First Design

All sections are optimized for mobile devices:

- **Breakpoints**: 768px (tablet), 480px (mobile)
- **Touch Targets**: Minimum 44px for buttons and interactive elements
- **Tabs**: Stack vertically on mobile instead of horizontal
- **Reviews**: Horizontal layout on desktop, stacked on mobile
- **Related Products**: 4 columns → 2 columns → 1 column based on screen width
- **Forms**: Full-width inputs on mobile for easy typing

## Testing Checklist

### Simple Products
- ✓ Product displays correct title, price, images, and description
- ✓ Add to cart button works
- ✓ Stock quantity is accurate
- ✓ "Buy Now" redirects to checkout

### Variable Products
- ✓ Attribute selectors appear (colors, sizes, etc.)
- ✓ Selecting attributes updates the variation
- ✓ Price updates when variation is selected
- ✓ Add to cart adds the correct variation

### Tabs
- ✓ Description tab shows product description
- ✓ Size Chart tab displays (custom or default)
- ✓ Reviews tab shows rating summary and reviews list
- ✓ Tabs are clickable and switch content

### Reviews
- ✓ Existing reviews are displayed
- ✓ Rating bars show correct percentages
- ✓ Review form appears and accepts submissions
- ✓ Star rating selector works (1-5 stars)
- ✓ Form validation works (required fields)

### Related Products
- ✓ Related products appear below tabs
- ✓ Products show correct images, titles, prices
- ✓ Add to cart buttons work on related products
- ✓ Clicking product navigates to that product page

### Mobile Responsiveness
- ✓ Layout stacks correctly on mobile (360px, 412px, 768px)
- ✓ All buttons are easy to tap (≥44px)
- ✓ Images scale properly
- ✓ Forms are usable on small screens

### Accessibility
- ✓ Keyboard navigation works for tabs
- ✓ Images have alt text
- ✓ Form inputs have labels
- ✓ ARIA attributes present for interactive elements

## WooCommerce Compatibility

The implementation uses standard WooCommerce functions and hooks:

- `global $product` - Access product object
- `wc_get_product()` - Get product by ID
- `woocommerce_template_single_add_to_cart()` - Add to cart functionality
- `woocommerce_template_loop_add_to_cart()` - Loop add to cart (related products)
- `wc_get_related_products()` - Get related products
- `comment_form()` - Review submission form
- `get_comments()` - Fetch reviews

This ensures compatibility with WooCommerce plugins and future updates.

## Customization

### Change Number of Related Products

Edit `content-single-product.php` line 286:
```php
$related_ids = wc_get_related_products( $product_id, 8 ); // Change 8 to desired number
```

### Customize Review Form Fields

Modify the `aakaari_reviews_tab_content()` function in `functions.php` starting at line 479.

### Modify Size Chart Default Table

Edit the `aakaari_size_chart_tab_content()` function in `functions.php` starting at line 432.

### Adjust Tab Order

Change the `priority` values in `aakaari_customize_product_tabs()` function in `functions.php` at line 399.

## Support & Maintenance

For issues or questions:
1. Check WooCommerce settings (Reviews, Products)
2. Verify product has required data (description, images, price)
3. Test with default WooCommerce theme to isolate issues
4. Check browser console for JavaScript errors

## Version History

- **1.0** - Initial implementation with full WooCommerce integration, tabs, reviews, and related products
