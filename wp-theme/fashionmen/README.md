# FashionMen WordPress Theme

A modern, elegant e-commerce WordPress theme for men's fashion built with WooCommerce support. Features a clean black and white design with gradient accents, responsive layout, and comprehensive shop functionality.

## Features

### Design
- **Modern UI**: Clean, minimalist design with black and white color scheme
- **Gradient Accents**: Blue to purple gradients for call-to-action elements
- **Fully Responsive**: Mobile-first design that works on all devices
- **Tailwind CSS**: Utility-first CSS framework for rapid development
- **Custom Typography**: Optimized font sizes and spacing

### E-Commerce (WooCommerce)
- **Product Catalog**: Beautiful product grid with filters
- **Product Details**: Rich product pages with image gallery
- **Shopping Cart**: Streamlined cart experience
- **Checkout**: Optimized checkout process
- **Product Categories**: Easy category browsing
- **Product Search**: Powerful search functionality
- **Wishlist Support**: Ready for wishlist plugins (YITH WooCommerce Wishlist)
- **Quick View**: Quick product preview (customizable)

### Features
- **Custom Header**: Logo, navigation menu, cart icon, wishlist, search
- **Mobile Navigation**: Slide-out mobile menu
- **Search Modal**: Elegant search overlay
- **Widget Areas**: 4 footer widget areas + shop sidebar
- **Custom Homepage**: Featured sections for hero, categories, products
- **Page Templates**: Custom templates for different page types
- **Social Media Integration**: Social links in footer
- **Newsletter Form**: Email subscription form
- **SEO Friendly**: Semantic HTML and proper heading structure
- **Accessibility**: WCAG compliant with proper ARIA labels

### Developer Friendly
- **Well Documented**: Comprehensive inline comments
- **WordPress Coding Standards**: Follows best practices
- **Modular Code**: Organized file structure
- **Custom Hooks**: Filter and action hooks for customization
- **Translation Ready**: Full internationalization support
- **Child Theme Ready**: Easy to extend with child themes

## Requirements

- WordPress 6.0 or higher
- PHP 7.4 or higher
- WooCommerce 7.0 or higher (optional but recommended)

## Installation

### Via WordPress Admin

1. Download the theme ZIP file
2. Go to WordPress Admin > Appearance > Themes
3. Click "Add New" then "Upload Theme"
4. Choose the downloaded ZIP file
5. Click "Install Now"
6. Activate the theme

### Manual Installation

1. Download and unzip the theme
2. Upload the `fashionmen` folder to `/wp-content/themes/`
3. Go to WordPress Admin > Appearance > Themes
4. Activate the FashionMen theme

### Tailwind CSS Setup

This theme uses Tailwind CSS. To compile the CSS:

1. Navigate to the theme directory
2. Install dependencies: `npm install`
3. Build CSS: `npm run build`
4. For development with watch: `npm run dev`

## Setup & Configuration

### Initial Setup

1. **Install Required Plugins**:
   - WooCommerce (required for e-commerce features)
   - YITH WooCommerce Wishlist (optional, for wishlist functionality)

2. **Configure WooCommerce**:
   - Run the WooCommerce setup wizard
   - Configure shop pages, shipping, and payments

3. **Set Menus**:
   - Go to Appearance > Menus
   - Create menus for Primary, Mobile, and Footer locations

4. **Configure Widgets**:
   - Go to Appearance > Widgets
   - Add widgets to footer areas and shop sidebar

5. **Set Homepage**:
   - Go to Settings > Reading
   - Set "A static page" for homepage
   - Select your homepage

### Customization

#### Theme Customizer

Go to Appearance > Customize to configure:

- **Site Identity**: Logo, site title, tagline
- **Colors**: Customize theme colors
- **Menus**: Set navigation menus
- **Widgets**: Configure widget areas
- **Homepage Settings**: Set static front page
- **Theme Options**: Social media links, custom settings

#### Custom Homepage

The theme includes a custom homepage template (front-page.php) with:
- Hero section with image and CTA
- Category showcase
- Featured products
- Sale banner
- Newsletter signup

To customize:
1. Create a new page
2. Set as homepage in Settings > Reading

#### WooCommerce Settings

Recommended WooCommerce settings:
- **Products per page**: 12
- **Columns**: 3
- **Image sizes**: Automatically set on theme activation

### Menus

The theme supports three menu locations:

1. **Primary Menu** - Main navigation (desktop)
2. **Mobile Menu** - Mobile navigation
3. **Footer Menu** - Footer links

### Widget Areas

- **Shop Sidebar** - Product filters and information
- **Footer Area 1-4** - Four footer columns

## Customization

### Child Theme

Create a child theme for customizations:

```php
<?php
/*
Theme Name: FashionMen Child
Template: fashionmen
*/
```

### Custom Hooks

The theme includes several custom hooks:

```php
// Before main content
do_action('fashionmen_before_content');

// After main content
do_action('fashionmen_after_content');

// Custom header actions
do_action('fashionmen_header_actions');
```

### Filters

Customize output with filters:

```php
// Modify products per page
add_filter('loop_shop_per_page', function() {
    return 16;
});

// Customize breadcrumb
add_filter('woocommerce_breadcrumb_defaults', 'custom_breadcrumbs');
```

## File Structure

```
fashionmen/
├── assets/
│   ├── css/
│   │   ├── tailwind.css      # Tailwind source
│   │   ├── custom.css         # Custom styles
│   │   └── woocommerce.css    # WooCommerce styles
│   └── js/
│       ├── main.js            # Main JavaScript
│       ├── navigation.js      # Navigation scripts
│       └── customizer.js      # Customizer preview
├── inc/
│   ├── customizer.php         # Customizer settings
│   ├── template-functions.php # Template functions
│   ├── template-tags.php      # Template tags
│   └── woocommerce.php        # WooCommerce integration
├── template-parts/
│   ├── content.php            # Post content template
│   └── content-none.php       # No content template
├── woocommerce/
│   ├── archive-product.php    # Shop page
│   ├── content-product.php    # Product card
│   ├── single-product.php     # Product detail
│   └── cart/
│       └── cart.php           # Cart page
├── footer.php                 # Footer template
├── front-page.php            # Homepage template
├── functions.php             # Theme functions
├── header.php                # Header template
├── index.php                 # Main template
├── page.php                  # Page template
├── style.css                 # Theme stylesheet
└── README.md                 # This file
```

## Support

For support, please:
1. Check the documentation in this README
2. Review the inline code comments
3. Visit the WordPress.org support forums

## Credits

- **Design**: Converted from React-based Figma design
- **Framework**: WordPress & WooCommerce
- **CSS**: Tailwind CSS
- **Icons**: Lucide Icons (SVG)

## Changelog

### Version 1.0.0
- Initial release
- WooCommerce integration
- Responsive design
- Custom homepage template
- Product catalog and details
- Shopping cart and checkout
- Mobile navigation
- Search functionality

## License

This theme is licensed under the GNU General Public License v2 or later.

## Author

FashionMen Team

---

**Enjoy the theme! If you like it, please rate it on WordPress.org**
