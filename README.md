# Aakaari Brand - WordPress WooCommerce Theme

A premium WordPress WooCommerce theme for streetwear e-commerce with modern design and automatic deployment features.

## Features

- **Modern Design**: Clean, minimal design optimized for fashion e-commerce
- **WooCommerce Ready**: Full WooCommerce integration with custom styling
- **Auto-Deployment**: Automatically sets up pages and settings on theme activation
- **Responsive**: Mobile-first design that works on all devices
- **Performance**: Optimized for fast loading times
- **SEO Friendly**: Clean code structure for better search engine visibility

## Theme Activation

When you activate this theme, it will automatically:

1. **Delete all existing pages and posts** (clean slate)
2. **Create required pages**:
   - Home (set as front page)
   - Shop
   - Cart
   - Checkout
   - My Account
   - About
   - Contact
   - Support
   - Privacy Policy
   - Terms & Conditions
   - Blog (set as posts page)

3. **Configure WordPress settings**:
   - Set permalink structure
   - Configure media sizes
   - Set site title and tagline
   - Disable comments on pages

4. **Configure WooCommerce**:
   - Set WooCommerce pages
   - Configure currency settings
   - Set up product display options
   - Configure image sizes

## Installation

1. Upload the theme folder to `/wp-content/themes/`
2. Activate the theme through the WordPress admin panel
3. Install and activate WooCommerce plugin
4. The theme will automatically set up all required pages and settings

## Requirements

- WordPress 6.0 or higher
- PHP 7.4 or higher
- WooCommerce 7.0 or higher

## Theme Structure

```
aakaari-brand/
├── assets/
│   ├── css/
│   │   ├── header.css
│   │   ├── footer.css
│   │   ├── home.css
│   │   └── layout.css
│   └── js/
│       ├── layout.js
│       └── home.js
├── inc/
│   └── class-theme-activator.php
├── templates/
├── woocommerce/
├── fig/ (source design files)
├── functions.php
├── style.css
├── header.php
├── footer.php
├── front-page.php
├── index.php
└── README.md
```

## Customization

### Logo

Go to **Appearance > Customize > Site Identity** to upload your custom logo.

### Menus

Create and assign menus at **Appearance > Menus**.

### Colors

The theme uses a black and white color scheme by default. You can customize colors by editing the CSS files in `assets/css/`.

### WooCommerce

All WooCommerce templates can be overridden by creating files in the `woocommerce/` directory.

## Development

The theme is built from Figma designs located in the `fig/` folder. Each page has its own folder with:
- React component (`.tsx`)
- CSS styles (`.css`)

## Support

For issues and feature requests, please contact the development team.

## Credits

- Theme Development: Aakaari Team
- Design: Based on modern e-commerce UI patterns
- Icons: Lucide Icons (converted to inline SVG)

## License

GNU General Public License v2 or later
http://www.gnu.org/licenses/gpl-2.0.html

## Changelog

### Version 1.0.0
- Initial release
- Home page template
- WooCommerce integration
- Auto-deployment feature
- Responsive design
