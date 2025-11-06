# Aakaari Brand Theme Structure

## Complete File Structure

```
aakaari_brand/
│
├── style.css                    # Main stylesheet (REQUIRED)
├── functions.php                # Theme functions (REQUIRED)
├── index.php                    # Main template fallback (REQUIRED)
├── screenshot.png               # Theme screenshot (1200x900px recommended)
├── README.md                    # Theme documentation
├── THEME_STRUCTURE.md          # This file
├── .editorconfig               # Editor configuration
├── .gitattributes              # Git attributes
│
├── Template Files/
│   ├── header.php              # Header template
│   ├── footer.php              # Footer template
│   ├── sidebar.php             # Main sidebar
│   ├── page.php                # Single page template
│   ├── single.php              # Single post template
│   ├── archive.php             # Archive pages
│   ├── search.php              # Search results
│   ├── 404.php                 # Error page
│   ├── comments.php            # Comments template
│   ├── searchform.php          # Search form
│   └── woocommerce.php         # WooCommerce template
│
├── inc/                        # Include files directory
│   ├── template-tags.php       # Custom template functions
│   └── customizer.php          # Theme customizer settings
│
├── js/                         # JavaScript files
│   ├── main.js                 # Main theme JavaScript
│   └── customizer.js           # Customizer preview JS
│
├── woocommerce/               # WooCommerce template overrides
│   └── templates/             # Custom WooCommerce templates
│
└── new/                       # Original design assets (from Figma)
    ├── home/
    ├── shop/
    ├── product/
    ├── cart/
    ├── checkout/
    ├── auth/
    ├── user-dashboard/
    ├── wishlist/
    ├── search/
    ├── admin/
    └── static/
```

## File Descriptions

### Core WordPress Files (Required)

1. **style.css**
   - Contains theme metadata in header comment
   - Main stylesheet for theme styling
   - Includes responsive design rules
   - WooCommerce specific styles

2. **functions.php**
   - Theme setup and configuration
   - WooCommerce support declaration
   - Widget area registration
   - Navigation menu registration
   - Script and style enqueuing
   - Custom functions and filters

3. **index.php**
   - Fallback template for all content
   - Main loop implementation
   - Used when no specific template exists

### Template Hierarchy Files

4. **header.php**
   - Site header markup
   - Navigation menu
   - Logo/branding area
   - wp_head() hook

5. **footer.php**
   - Site footer markup
   - Footer widgets
   - Copyright information
   - wp_footer() hook

6. **sidebar.php**
   - Main sidebar widget area
   - Conditional display logic

7. **page.php**
   - Template for static pages
   - Full-width content area

8. **single.php**
   - Template for single blog posts
   - Post metadata display
   - Post navigation
   - Comments integration

9. **archive.php**
   - Template for archives (category, tag, date)
   - Archive title and description
   - Post loop with pagination

10. **search.php**
    - Search results display
    - Custom search messaging
    - Result count

11. **404.php**
    - Error page template
    - Helpful navigation options
    - Search form
    - Recent posts/products

12. **comments.php**
    - Comments display
    - Comment form
    - Comment navigation

13. **searchform.php**
    - Search form markup
    - Accessible form structure

14. **woocommerce.php**
    - Main template for all WooCommerce pages
    - Shop sidebar integration
    - Calls woocommerce_content()

### Include Files (/inc/)

15. **inc/template-tags.php**
    - Custom template functions
    - Post meta display functions
    - Breadcrumb function
    - Pagination helpers

16. **inc/customizer.php**
    - Theme customizer settings
    - Color controls
    - Layout options
    - Typography settings
    - Live preview JavaScript

### JavaScript Files (/js/)

17. **js/main.js**
    - Mobile menu toggle
    - Smooth scrolling
    - Cart count updates
    - Accessibility features
    - Back to top button
    - Lazy loading

18. **js/customizer.js**
    - Live preview updates
    - Customizer control bindings
    - Real-time style changes

## WordPress Template Hierarchy

The theme follows WordPress template hierarchy:

```
Homepage:
front-page.php → home.php → index.php

Single Post:
single-{post-type}.php → single.php → singular.php → index.php

Page:
page-{slug}.php → page-{id}.php → page.php → singular.php → index.php

Archive:
archive-{post-type}.php → archive.php → index.php

Category:
category-{slug}.php → category-{id}.php → category.php → archive.php → index.php

Search:
search.php → index.php

404:
404.php → index.php
```

## WooCommerce Template Structure

WooCommerce templates can be overridden by placing them in:
```
aakaari_brand/woocommerce/
```

Common WooCommerce templates to override:
- `archive-product.php` - Shop page
- `single-product.php` - Product page
- `cart/cart.php` - Cart page
- `checkout/form-checkout.php` - Checkout page
- `myaccount/my-account.php` - Account page

## Theme Hooks

### WordPress Actions Used:
- `after_setup_theme` - Theme setup
- `widgets_init` - Register sidebars
- `wp_enqueue_scripts` - Load styles/scripts
- `wp_head` - Header hook
- `wp_footer` - Footer hook
- `customize_register` - Customizer settings

### WooCommerce Filters Used:
- `woocommerce_before_main_content` - Wrapper start
- `woocommerce_after_main_content` - Wrapper end
- `loop_shop_columns` - Products per row
- `loop_shop_per_page` - Products per page
- `wp_nav_menu_items` - Add cart to menu

## Widget Areas

The theme registers these widget areas:

1. **Sidebar** (`sidebar-1`)
   - Main blog/page sidebar
   - Location: Right side of content

2. **Footer Widget Area 1** (`footer-1`)
   - Footer column 1

3. **Footer Widget Area 2** (`footer-2`)
   - Footer column 2

4. **Footer Widget Area 3** (`footer-3`)
   - Footer column 3

5. **Shop Sidebar** (`shop-sidebar`)
   - WooCommerce pages sidebar
   - Product filters, categories, etc.

## Navigation Menus

The theme registers these menu locations:

1. **Primary Menu** (`primary`)
   - Main site navigation
   - Location: Below header
   - Includes cart icon (if WooCommerce active)

2. **Footer Menu** (`footer`)
   - Footer links
   - Location: Footer area
   - Single level only

## Theme Support Features

The theme declares support for:
- `title-tag` - Let WordPress handle title
- `post-thumbnails` - Featured images
- `custom-logo` - Logo uploader
- `custom-background` - Background customization
- `html5` - HTML5 markup
- `responsive-embeds` - Responsive videos
- `editor-styles` - Editor styling
- `automatic-feed-links` - RSS feeds
- `woocommerce` - WooCommerce support
- `wc-product-gallery-zoom` - Product zoom
- `wc-product-gallery-lightbox` - Image lightbox
- `wc-product-gallery-slider` - Image slider

## Image Sizes

Registered image sizes:
- `post-thumbnail`: 300×300 (hard crop)
- `aakaari-featured`: 800×600 (hard crop)
- `aakaari-large`: 1200×800 (hard crop)

## Customizer Settings

Available in Appearance → Customize:

### Theme Colors
- Primary Color (links, buttons)
- Secondary Color (header, footer)

### Header Settings
- Display Site Title (toggle)
- Display Tagline (toggle)

### Footer Settings
- Copyright Text (custom text)

### Layout Settings
- Sidebar Position (left/right/none)
- Container Width (960-1920px)

### Typography
- Font Family (system/Arial/etc)
- Base Font Size (12-24px)

## Best Practices Followed

1. **Security**
   - All outputs escaped
   - All inputs sanitized
   - Nonces for forms
   - Capability checks

2. **Internationalization**
   - All strings translatable
   - Text domain: `aakaari-brand`
   - Translation ready

3. **Accessibility**
   - Semantic HTML5
   - ARIA labels
   - Skip links
   - Keyboard navigation

4. **Performance**
   - Minimal HTTP requests
   - Optimized CSS/JS
   - Lazy loading support
   - Conditional loading

5. **Coding Standards**
   - WordPress Coding Standards
   - WooCommerce standards
   - Proper indentation
   - Commented code

## Future Enhancements

Potential additions:
- More WooCommerce template overrides
- Additional page templates
- Custom post type templates
- More customizer options
- Advanced typography options
- Block editor patterns
- Custom Gutenberg blocks
- Performance optimizations
