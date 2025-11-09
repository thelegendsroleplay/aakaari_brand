# Home Page Implementation Documentation

## Overview
This documentation covers the complete home page implementation for the Aakaari Brand WordPress WooCommerce theme. The design is based on the Figma design provided and is fully mobile-responsive.

## File Structure

```
aakaari_brand/
├── template-home.php                      # Home page template
├── woocommerce/
│   └── content-product-card.php          # Product card template
├── assets/
│   ├── css/
│   │   ├── home.css                      # Home page styles
│   │   └── main.css                      # Global styles (with notifications)
│   └── js/
│       ├── home.js                       # Home page JavaScript
│       └── customizer.js                 # Theme customizer preview
└── inc/
    ├── customizer.php                    # Theme customizer settings
    ├── woocommerce-functions.php         # WooCommerce AJAX functions
    └── enqueue-scripts.php               # Updated to enqueue home page assets
```

## Home Page Sections

### 1. Hero Section
- **Location**: Top of the page
- **Features**:
  - Full-width hero banner with image overlay
  - Customizable tag, title, and subtitle
  - Call-to-action button linking to shop page
- **Customizer Options**:
  - `aakaari_hero_tag` - Hero tag text
  - `aakaari_hero_title` - Main hero title
  - `aakaari_hero_subtitle` - Hero subtitle
  - `aakaari_hero_image` - Hero background image

### 2. Category Section
- **Location**: Below hero section
- **Features**:
  - Displays top 2 WooCommerce product categories
  - Hover effects with image zoom
  - Click to navigate to category page
- **Data Source**: WooCommerce product categories (taxonomy: `product_cat`)

### 3. Featured Products Section
- **Location**: After category section
- **Features**:
  - Displays up to 8 featured products
  - Product cards with image, title, price, and add to cart
  - Quick view button on hover
  - "View All" link to shop page
- **Data Source**: WooCommerce products with `_featured` meta key set to `yes`

### 4. Promo Banner Section
- **Location**: Middle of the page
- **Features**:
  - Dark gradient background
  - Badge, title, and description
  - Three feature items with checkmarks
  - Call-to-action button
- **Customizer Options**:
  - `aakaari_promo_badge` - Promo badge text
  - `aakaari_promo_title` - Promo title
  - `aakaari_promo_description` - Promo description
  - `aakaari_promo_image` - Promo image (hidden on mobile)

### 5. New Arrivals Section
- **Location**: After promo banner
- **Features**:
  - Similar to Featured Products
  - Displays up to 8 newest products
  - Same product card design
- **Data Source**: WooCommerce products ordered by date (DESC)

### 6. Trust Section
- **Location**: Bottom of the page
- **Features**:
  - Three trust badges (Free Shipping, Secure Payment, Easy Returns)
  - SVG icons with text
  - Light gray background

## Product Card Features

Each product card includes:
- Product image with hover zoom effect
- Sale badge (if product is on sale)
- Out of stock badge (if applicable)
- Quick view button (on hover)
- Product title
- Short description (truncated to 10 words)
- Price (with sale price styling)
- Add to cart button (AJAX-enabled for simple products)

## JavaScript Functionality

### home.js Features:
1. **Product Carousel Initialization**
   - Touch support for mobile swiping
   - Smooth scrolling

2. **Quick View**
   - Event handler for quick view buttons
   - Can be extended to open a modal

3. **AJAX Add to Cart**
   - Adds simple products to cart without page reload
   - Shows success/error notifications
   - Updates cart count in header
   - Disables button during processing

4. **Cart Count Update**
   - Real-time cart count updates via AJAX
   - Uses WooCommerce fragments

5. **Notifications System**
   - Toast-style notifications
   - Auto-dismiss after 3 seconds
   - Success, error, and info variants

## AJAX Endpoints

### 1. Add to Cart
- **Action**: `aakaari_add_to_cart`
- **Method**: POST
- **Parameters**:
  - `product_id` (int) - WooCommerce product ID
  - `quantity` (int) - Quantity to add (default: 1)
  - `nonce` - Security nonce
- **Response**:
  - Success: `{ success: true, message: 'Product added to cart', cart_count: 3 }`
  - Error: `{ success: false, message: 'Error message' }`

### 2. Get Cart Count
- **Action**: `aakaari_get_cart_count`
- **Method**: POST
- **Parameters**:
  - `nonce` - Security nonce
- **Response**: `{ success: true, count: 3 }`

## Theme Customizer

Access customizer settings at: **Appearance → Customize → Home Page Settings**

Available options:
- Hero section content and image
- Promo section content and image
- Logo width setting (in Title & Tagline section)

All changes preview in real-time thanks to `customizer.js`.

## Responsive Design

The home page is mobile-first and includes breakpoints:

### Desktop (Default)
- Full-width layouts
- 3-column trust section
- Multi-column product grid
- 2-column category grid

### Tablet (≤ 1024px)
- Adjusted font sizes
- Responsive product grid

### Mobile (≤ 768px)
- Single column category grid
- Stacked trust items
- 2-column product grid
- Adjusted hero height (450px)
- Full-width CTA buttons

### Small Mobile (≤ 480px)
- Single column product grid
- Smaller hero (400px)
- Reduced font sizes
- Compact spacing

## WooCommerce Integration

### Required WooCommerce Features:
1. Product categories with images
2. Featured products (mark products as featured in WooCommerce)
3. Product images
4. Product pricing
5. Cart functionality

### Custom WooCommerce Template:
- `woocommerce/content-product-card.php` - Custom product card template

### WooCommerce Functions:
Located in `inc/woocommerce-functions.php`:
- AJAX add to cart handler
- Cart count getter
- Cart fragments for real-time updates

## Setup Instructions

### 1. Create a Home Page
1. Go to **Pages → Add New**
2. Create a page titled "Home"
3. Assign the **Home Page** template from the page attributes
4. Publish the page

### 2. Set as Front Page
1. Go to **Settings → Reading**
2. Select "A static page" under "Your homepage displays"
3. Choose your "Home" page as the homepage
4. Save changes

### 3. Configure WooCommerce
1. Install and activate WooCommerce plugin
2. Create product categories with images
3. Add products and mark some as "Featured"
4. Ensure products have images and prices

### 4. Customize Content
1. Go to **Appearance → Customize → Home Page Settings**
2. Update hero section content and image
3. Update promo section content and image
4. Preview and publish changes

### 5. Upload Images
Recommended image sizes:
- **Hero Image**: 1920x600px (landscape)
- **Promo Image**: 600x800px (portrait)
- **Category Images**: 800x800px (square)
- **Product Images**: 800x1000px (portrait)

## CSS Classes Reference

### Hero Section
- `.hero-banner` - Main hero container
- `.hero-image-container` - Image wrapper
- `.hero-banner-image` - Background image
- `.hero-overlay` - Dark overlay
- `.hero-tag` - Small tag above title
- `.hero-main-title` - Main heading
- `.hero-main-subtitle` - Subtitle text
- `.hero-cta-button` - CTA button

### Category Section
- `.category-section` - Section wrapper
- `.category-grid` - Grid container
- `.category-card` - Individual category card
- `.category-card-image` - Category image
- `.category-card-overlay` - Dark gradient overlay
- `.category-card-title` - Category name
- `.category-card-subtitle` - "Explore Collection" text

### Products Section
- `.products-section` - Section wrapper
- `.section-title-wrapper` - Title and "View All" container
- `.product-carousel` - Product grid
- `.product-card` - Individual product card
- `.product-card-image` - Product image
- `.product-card-badge` - Sale/stock badge
- `.product-card-quick-view` - Quick view button
- `.product-card-add-to-cart` - Add to cart button

### Promo Section
- `.promo-section` - Section wrapper
- `.promo-card` - Main promo container
- `.promo-badge` - Small badge
- `.promo-title` - Main title
- `.promo-description` - Description text
- `.promo-features` - Features list
- `.promo-feature-item` - Individual feature

### Trust Section
- `.trust-section` - Section wrapper
- `.trust-grid` - Grid container
- `.trust-item` - Individual trust item
- `.trust-icon-box` - Icon circle
- `.trust-icon` - SVG icon
- `.trust-title` - Title text
- `.trust-desc` - Description text

## Troubleshooting

### Products not showing
- Ensure WooCommerce is installed and activated
- Mark products as "Featured" for the featured section
- Check that products are published

### Images not displaying
- Upload images through the customizer or media library
- Check image URLs in customizer settings
- Ensure images are publicly accessible

### Add to cart not working
- Check browser console for JavaScript errors
- Verify AJAX URL is correct
- Ensure WooCommerce cart is enabled
- Check nonce verification

### Styles not applying
- Clear browser cache
- Clear WordPress cache (if using a caching plugin)
- Check that home.css is being enqueued
- Verify template is assigned to the page

## Browser Compatibility

Tested and compatible with:
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile Safari (iOS)
- Chrome Mobile (Android)

## Performance Optimization

### Image Optimization
- Use WebP format for better compression
- Lazy load images below the fold
- Use srcset for responsive images

### CSS Optimization
- Minify CSS in production
- Consider critical CSS for above-the-fold content

### JavaScript Optimization
- Scripts loaded in footer for better performance
- AJAX requests are throttled
- Event delegation for better performance

## Future Enhancements

Potential improvements:
1. Add Swiper.js for advanced carousel features
2. Implement quick view modal with product details
3. Add product filtering and sorting
4. Add wishlist functionality
5. Add product comparison feature
6. Implement infinite scroll for products
7. Add loading skeleton screens
8. Add image lazy loading
9. Add schema markup for SEO
10. Add A/B testing for different layouts

## Support

For issues or questions:
1. Check this documentation first
2. Review browser console for errors
3. Check WordPress debug log
4. Verify WooCommerce settings

## Version History

- **1.0.0** - Initial home page implementation
  - Hero section with customizer
  - Category section with WooCommerce integration
  - Featured products carousel
  - Promo banner with customizer
  - New arrivals section
  - Trust badges section
  - AJAX add to cart
  - Mobile-responsive design
  - Product card template
  - Notification system
