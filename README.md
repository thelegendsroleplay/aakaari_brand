# Aakaari Brand - Custom WordPress WooCommerce Theme

A modern, responsive WordPress theme built specifically for WooCommerce stores. This theme provides a clean, professional foundation for your e-commerce website with extensive customization options.

## Features

### Core Features
- ✅ Full WooCommerce support
- ✅ Responsive design (mobile-friendly)
- ✅ Custom logo support
- ✅ Multiple widget areas (sidebar, footer, shop)
- ✅ Custom navigation menus
- ✅ Post thumbnails/featured images
- ✅ Theme customizer integration
- ✅ HTML5 markup
- ✅ Translation ready

### WooCommerce Features
- ✅ Product gallery with zoom, lightbox, and slider
- ✅ Customizable products per page
- ✅ Adjustable products per row
- ✅ Shop sidebar
- ✅ Cart icon in navigation with live count
- ✅ Custom WooCommerce templates

### Customization Options
- Color scheme customization (primary & secondary colors)
- Typography settings (font family & size)
- Layout options (sidebar position, container width)
- Header settings (show/hide title & tagline)
- Footer copyright text
- Custom logo and background

## Installation

### Method 1: Upload via WordPress Admin

1. Download the theme files and create a ZIP archive
2. Go to **Appearance > Themes > Add New**
3. Click **Upload Theme**
4. Choose the ZIP file and click **Install Now**
5. Activate the theme

### Method 2: Manual Installation

1. Download the theme files
2. Upload the `aakaari_brand` folder to `/wp-content/themes/`
3. Go to **Appearance > Themes** in WordPress admin
4. Activate the **Aakaari Brand** theme

## Setup Instructions

### 1. Install Required Plugins

This theme requires **WooCommerce** to be installed and activated:

1. Go to **Plugins > Add New**
2. Search for "WooCommerce"
3. Install and activate WooCommerce
4. Follow the WooCommerce setup wizard

### 2. Configure Theme Settings

#### Logo and Site Identity
1. Go to **Appearance > Customize > Site Identity**
2. Upload your logo
3. Set your site title and tagline

#### Menus
1. Go to **Appearance > Menus**
2. Create a new menu
3. Add pages/products to your menu
4. Assign to "Primary Menu" location

#### Widgets
1. Go to **Appearance > Widgets**
2. Add widgets to available areas:
   - Sidebar
   - Footer Widget Area 1, 2, 3
   - Shop Sidebar (for WooCommerce pages)

#### Theme Colors
1. Go to **Appearance > Customize > Theme Colors**
2. Set your primary color (links, buttons)
3. Set your secondary color (header, footer)

#### Layout Settings
1. Go to **Appearance > Customize > Layout Settings**
2. Choose sidebar position (left, right, or none)
3. Adjust container width

#### Typography
1. Go to **Appearance > Customize > Typography**
2. Select font family
3. Adjust base font size

### 3. WooCommerce Configuration

1. **Products**: Go to **Products > Add New** to create products
2. **Shop Page**: Go to **WooCommerce > Settings > Products** to configure shop page
3. **Payment**: Go to **WooCommerce > Settings > Payments** to set up payment methods
4. **Shipping**: Go to **WooCommerce > Settings > Shipping** to configure shipping

## Theme Structure

```
aakaari_brand/
├── style.css              # Main stylesheet with theme header
├── functions.php          # Theme functions and features
├── index.php             # Main template file
├── header.php            # Header template
├── footer.php            # Footer template
├── sidebar.php           # Sidebar template
├── woocommerce.php       # WooCommerce pages template
├── page.php              # Single page template
├── single.php            # Single post template
├── archive.php           # Archive template
├── search.php            # Search results template
├── 404.php               # 404 error page
├── comments.php          # Comments template
├── searchform.php        # Search form template
├── inc/
│   ├── template-tags.php # Custom template tags
│   └── customizer.php    # Theme customizer settings
├── js/
│   ├── main.js           # Main JavaScript
│   └── customizer.js     # Customizer preview JavaScript
└── README.md             # This file
```

## Template Files

- **index.php**: Main blog/archive template
- **page.php**: Static pages
- **single.php**: Individual blog posts
- **archive.php**: Category, tag, date archives
- **search.php**: Search results
- **404.php**: Page not found
- **woocommerce.php**: All WooCommerce pages

## Hooks and Filters

The theme uses standard WordPress and WooCommerce hooks:

### WordPress Hooks
- `after_setup_theme` - Theme setup
- `widgets_init` - Register widget areas
- `wp_enqueue_scripts` - Enqueue styles and scripts

### WooCommerce Hooks
- `woocommerce_before_main_content` - Custom wrapper start
- `woocommerce_after_main_content` - Custom wrapper end
- `loop_shop_columns` - Products per row (default: 4)
- `loop_shop_per_page` - Products per page (default: 12)

## Customization

### Child Theme (Recommended)

To make customizations that won't be overwritten by theme updates:

1. Create a new folder: `/wp-content/themes/aakaari-brand-child/`
2. Create `style.css`:

```css
/*
Theme Name: Aakaari Brand Child
Template: aakaari_brand
*/
```

3. Create `functions.php`:

```php
<?php
function aakaari_brand_child_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'aakaari_brand_child_enqueue_styles' );
```

4. Activate the child theme

### Custom CSS

Add custom CSS via **Appearance > Customize > Additional CSS**

### Override Templates

Copy template files to your child theme and modify as needed.

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- WooCommerce 5.0 or higher (recommended)

## Support

For issues and questions:
- GitHub Issues: [https://github.com/thelegendsroleplay/aakaari_brand/issues](https://github.com/thelegendsroleplay/aakaari_brand/issues)

## Changelog

### Version 1.0.0
- Initial release
- Full WooCommerce support
- Responsive design
- Theme customizer options
- Widget areas
- Custom navigation menus

## Credits

- Built with WordPress best practices
- WooCommerce integration
- Font Awesome icons (if used)
- jQuery

## License

GNU General Public License v2 or later
http://www.gnu.org/licenses/gpl-2.0.html

## Author

The Legends Roleplay
GitHub: [https://github.com/thelegendsroleplay](https://github.com/thelegendsroleplay)
