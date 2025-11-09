# Aakaari Brand - Custom WordPress WooCommerce Theme

A custom WordPress theme built for WooCommerce with a clean, organized file structure.

## File Structure

```
aakaari_brand/
├── assets/
│   ├── css/
│   │   └── main.css          # Main stylesheet
│   └── js/
│       └── main.js           # Main JavaScript file
├── inc/
│   ├── theme-setup.php       # Theme setup and WordPress features
│   ├── enqueue-scripts.php   # Enqueue CSS and JS files
│   └── woocommerce-functions.php  # WooCommerce specific functions
├── functions.php             # Main functions file (includes inc files)
├── style.css                 # Theme header and metadata
├── index.php                 # Main template file
├── page.php                  # Default page template
├── header.php                # Header template
├── footer.php                # Footer template
└── template-home.php         # Example custom page template
```

## Features

- Clean, organized file structure
- Page template system (not template parts)
- Individual function files for different features in `/inc` folder
- WooCommerce support built-in
- Responsive design ready
- Modern WordPress coding standards

## Installation

1. Upload the theme folder to `/wp-content/themes/`
2. Activate the theme in WordPress admin
3. Install and activate WooCommerce plugin
4. Start customizing!

## Creating Custom Page Templates

To create a new page template, create a new file in the root directory with the following header:

```php
<?php
/**
 * Template Name: Your Template Name
 * Template Post Type: page
 */
```

## Adding New Functions

Add new function files to the `/inc` folder and include them in `functions.php`:

```php
'/inc/your-new-file.php',
```

## Development

- CSS files go in `assets/css/`
- JavaScript files go in `assets/js/`
- Function files go in `inc/`
- Page templates go in the root directory

## Requirements

- WordPress 6.0 or higher
- PHP 7.4 or higher
- WooCommerce plugin

## License

GPL v2 or later
