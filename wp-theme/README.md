# FashionMen WordPress Theme

This directory contains the WordPress WooCommerce theme converted from the React-based Figma design.

## Theme Location

The theme is located in: `wp-theme/fashionmen/`

## Installation

1. **Zip the theme folder**:
   ```bash
   cd wp-theme
   zip -r fashionmen.zip fashionmen/
   ```

2. **Install in WordPress**:
   - Go to WordPress Admin > Appearance > Themes
   - Click "Add New" > "Upload Theme"
   - Upload the `fashionmen.zip` file
   - Activate the theme

## Requirements

- WordPress 6.0+
- PHP 7.4+
- WooCommerce 7.0+ (for e-commerce functionality)

## Features

- ✅ Fully responsive design
- ✅ WooCommerce integration
- ✅ Custom homepage template
- ✅ Product catalog and filters
- ✅ Shopping cart and checkout
- ✅ Mobile navigation
- ✅ Search functionality
- ✅ Wishlist support (requires plugin)
- ✅ SEO friendly
- ✅ Translation ready

## Development

### Compile Tailwind CSS

```bash
cd fashionmen
npm install
npm run build
```

For development with watch mode:
```bash
npm run dev
```

## Documentation

See the full documentation in `fashionmen/README.md`

## Theme Structure

```
fashionmen/
├── assets/           # CSS, JS, and images
├── inc/              # PHP includes
├── template-parts/   # Template partials
├── woocommerce/      # WooCommerce templates
├── functions.php     # Theme functions
├── style.css         # Main stylesheet
└── README.md         # Theme documentation
```

## Support

For detailed setup instructions and customization options, refer to the README.md file inside the theme folder.

## License

GPL v2 or later
