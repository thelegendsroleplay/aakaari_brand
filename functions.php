<?php
/**
 * FashionMen Theme Functions
 *
 * @package FashionMen
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Theme version
define('FASHIONMEN_VERSION', '1.0.0');

/**
 * Theme Setup
 */
function fashionmen_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');

    // Set default thumbnail sizes
    set_post_thumbnail_size(1200, 800, true);
    add_image_size('fashionmen-product-thumb', 400, 500, true);
    add_image_size('fashionmen-product-single', 800, 1000, true);
    add_image_size('fashionmen-hero', 1920, 800, true);

    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'fashionmen'),
        'mobile' => esc_html__('Mobile Menu', 'fashionmen'),
        'footer' => esc_html__('Footer Menu', 'fashionmen'),
    ));

    // Switch default core markup to output valid HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add theme support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // Add support for editor styles
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor-style.css');

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for WooCommerce
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'fashionmen_setup');

/**
 * Set the content width in pixels
 */
function fashionmen_content_width() {
    $GLOBALS['content_width'] = apply_filters('fashionmen_content_width', 1200);
}
add_action('after_setup_theme', 'fashionmen_content_width', 0);

/**
 * Register widget areas
 */
function fashionmen_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Shop Sidebar', 'fashionmen'),
        'id'            => 'sidebar-shop',
        'description'   => esc_html__('Add widgets for shop pages here.', 'fashionmen'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Area 1', 'fashionmen'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Add widgets for footer column 1.', 'fashionmen'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Area 2', 'fashionmen'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Add widgets for footer column 2.', 'fashionmen'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Area 3', 'fashionmen'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Add widgets for footer column 3.', 'fashionmen'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Area 4', 'fashionmen'),
        'id'            => 'footer-4',
        'description'   => esc_html__('Add widgets for footer column 4.', 'fashionmen'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'fashionmen_widgets_init');

/**
 * Enqueue scripts and styles
 */
function fashionmen_scripts() {
    // Enqueue Tailwind CSS from CDN
    wp_enqueue_style('fashionmen-tailwind-cdn', 'https://cdn.jsdelivr.net/npm/tailwindcss@3.4.0/dist/tailwind.min.css', array(), '3.4.0');

    // Enqueue our custom Tailwind styles
    wp_enqueue_style('fashionmen-tailwind', get_template_directory_uri() . '/assets/css/tailwind-compiled.css', array('fashionmen-tailwind-cdn'), FASHIONMEN_VERSION);

    // Enqueue custom styles
    wp_enqueue_style('fashionmen-style', get_stylesheet_uri(), array('fashionmen-tailwind'), FASHIONMEN_VERSION);

    // Enqueue custom CSS
    wp_enqueue_style('fashionmen-custom', get_template_directory_uri() . '/assets/css/custom.css', array('fashionmen-style'), FASHIONMEN_VERSION);

    // Enqueue main JavaScript
    wp_enqueue_script('fashionmen-main', get_template_directory_uri() . '/assets/js/main.js', array(), FASHIONMEN_VERSION, true);

    // Enqueue navigation script
    wp_enqueue_script('fashionmen-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), FASHIONMEN_VERSION, true);

    // Localize script for AJAX
    wp_localize_script('fashionmen-main', 'fashionmenAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('fashionmen-nonce')
    ));

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'fashionmen_scripts');

/**
 * WooCommerce Customization
 */

// Remove default WooCommerce styles
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

// Customize WooCommerce products per page
function fashionmen_products_per_page() {
    return 12;
}
add_filter('loop_shop_per_page', 'fashionmen_products_per_page', 20);

// Customize WooCommerce product columns
function fashionmen_product_columns() {
    return 3; // 3 columns on desktop
}
add_filter('loop_shop_columns', 'fashionmen_product_columns');

// Add WooCommerce cart icon to navigation
function fashionmen_cart_icon() {
    if (!class_exists('WooCommerce')) {
        return;
    }

    $cart_count = WC()->cart->get_cart_contents_count();
    ?>
    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-icon">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <?php if ($cart_count > 0) : ?>
            <span class="cart-count"><?php echo esc_html($cart_count); ?></span>
        <?php endif; ?>
    </a>
    <?php
}

// Update cart count via AJAX
function fashionmen_update_cart_count() {
    if (!class_exists('WooCommerce')) {
        wp_send_json_error();
    }

    $cart_count = WC()->cart->get_cart_contents_count();
    wp_send_json_success(array('count' => $cart_count));
}
add_action('wp_ajax_fashionmen_update_cart_count', 'fashionmen_update_cart_count');
add_action('wp_ajax_nopriv_fashionmen_update_cart_count', 'fashionmen_update_cart_count');

/**
 * Include required files
 */
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/template-tags.php';

if (class_exists('WooCommerce')) {
    require get_template_directory() . '/inc/woocommerce.php';
}

/**
 * Custom template tags for this theme
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Theme setup and welcome page
 */
require get_template_directory() . '/inc/theme-setup.php';

/**
 * Create default pages on theme activation
 */
function fashionmen_create_default_pages() {
    // Check if pages have already been created
    $created_page_ids = get_option('fashionmen_created_page_ids', array());

    // Verify if the pages actually exist
    $pages_exist = false;
    if (!empty($created_page_ids)) {
        foreach ($created_page_ids as $page_id) {
            if (get_post_status($page_id) !== false) {
                $pages_exist = true;
                break;
            }
        }
    }

    // If flag is set and pages exist, don't recreate
    if (get_option('fashionmen_pages_created') && $pages_exist) {
        return;
    }

    // If flag is set but no pages exist, reset the flag
    if (get_option('fashionmen_pages_created') && !$pages_exist) {
        delete_option('fashionmen_pages_created');
        delete_option('fashionmen_created_page_ids');
    }

    // Array of pages to create
    $pages = array(
        array(
            'title'     => 'Home',
            'content'   => 'Welcome to our men\'s fashion store. Discover premium clothing and accessories for the modern gentleman.',
            'template'  => '',
            'is_front'  => true,
        ),
        array(
            'title'     => 'About',
            'slug'      => 'about',
            'content'   => '<h2>About Us</h2><p>We are a premium men\'s fashion brand dedicated to providing high-quality clothing and accessories for the modern gentleman.</p><p>Our collection features timeless pieces and contemporary designs that help you express your unique style.</p>',
            'template'  => 'template-about.php',
        ),
        array(
            'title'     => 'Contact',
            'slug'      => 'contact',
            'content'   => '<h2>Get In Touch</h2><p>Have questions? We\'d love to hear from you. Send us a message and we\'ll respond as soon as possible.</p>',
            'template'  => 'template-contact.php',
        ),
        array(
            'title'     => 'FAQ',
            'slug'      => 'faq',
            'content'   => '<h2>Frequently Asked Questions</h2><p>Find answers to common questions about our products, shipping, returns, and more.</p>',
            'template'  => 'template-faq.php',
        ),
        array(
            'title'     => 'Terms & Conditions',
            'slug'      => 'terms',
            'content'   => '<h2>Terms & Conditions</h2><p>Please read these terms and conditions carefully before using our website.</p>',
            'template'  => '',
        ),
        array(
            'title'     => 'Privacy Policy',
            'slug'      => 'privacy',
            'content'   => '<h2>Privacy Policy</h2><p>We are committed to protecting your privacy. This policy explains how we collect, use, and safeguard your information.</p>',
            'template'  => '',
        ),
        array(
            'title'     => 'Shipping Information',
            'slug'      => 'shipping',
            'content'   => '<h2>Shipping Information</h2><p>We offer worldwide shipping. Learn more about our shipping options, delivery times, and costs.</p>',
            'template'  => '',
        ),
    );

    $created_pages = array();

    foreach ($pages as $page_data) {
        // Check if page already exists
        $page_check = get_page_by_path($page_data['slug'] ?? sanitize_title($page_data['title']));

        if ($page_check) {
            $created_pages[$page_data['title']] = $page_check->ID;
            continue;
        }

        // Create the page
        $page_id = wp_insert_post(array(
            'post_title'     => $page_data['title'],
            'post_name'      => $page_data['slug'] ?? sanitize_title($page_data['title']),
            'post_content'   => $page_data['content'],
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'post_author'    => 1,
            'comment_status' => 'closed',
            'ping_status'    => 'closed',
        ));

        if ($page_id && !is_wp_error($page_id)) {
            $created_pages[$page_data['title']] = $page_id;

            // Assign template if specified
            if (!empty($page_data['template'])) {
                update_post_meta($page_id, '_wp_page_template', $page_data['template']);
            }

            // Set as front page if specified
            if (isset($page_data['is_front']) && $page_data['is_front']) {
                update_option('page_on_front', $page_id);
                update_option('show_on_front', 'page');
            }
        }
    }

    // Create navigation menus
    fashionmen_create_default_menus($created_pages);

    // Mark pages as created
    update_option('fashionmen_pages_created', true);
    update_option('fashionmen_created_page_ids', $created_pages);
}

/**
 * Create default navigation menus
 */
function fashionmen_create_default_menus($pages) {
    // Check if menus already exist
    $menu_exists = wp_get_nav_menu_object('Primary Menu');

    if ($menu_exists) {
        return;
    }

    // Create Primary Menu
    $primary_menu_id = wp_create_nav_menu('Primary Menu');

    if (!is_wp_error($primary_menu_id)) {
        // Add Home
        if (isset($pages['Home'])) {
            wp_update_nav_menu_item($primary_menu_id, 0, array(
                'menu-item-title'     => 'Home',
                'menu-item-object-id' => $pages['Home'],
                'menu-item-object'    => 'page',
                'menu-item-type'      => 'post_type',
                'menu-item-status'    => 'publish',
                'menu-item-position'  => 1,
            ));
        }

        // Add Shop (if WooCommerce is active)
        if (class_exists('WooCommerce')) {
            $shop_page_id = get_option('woocommerce_shop_page_id');
            if ($shop_page_id) {
                wp_update_nav_menu_item($primary_menu_id, 0, array(
                    'menu-item-title'     => 'Shop',
                    'menu-item-object-id' => $shop_page_id,
                    'menu-item-object'    => 'page',
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                    'menu-item-position'  => 2,
                ));
            }
        }

        // Add About
        if (isset($pages['About'])) {
            wp_update_nav_menu_item($primary_menu_id, 0, array(
                'menu-item-title'     => 'About',
                'menu-item-object-id' => $pages['About'],
                'menu-item-object'    => 'page',
                'menu-item-type'      => 'post_type',
                'menu-item-status'    => 'publish',
                'menu-item-position'  => 3,
            ));
        }

        // Add Contact
        if (isset($pages['Contact'])) {
            wp_update_nav_menu_item($primary_menu_id, 0, array(
                'menu-item-title'     => 'Contact',
                'menu-item-object-id' => $pages['Contact'],
                'menu-item-object'    => 'page',
                'menu-item-type'      => 'post_type',
                'menu-item-status'    => 'publish',
                'menu-item-position'  => 4,
            ));
        }

        // Add FAQ
        if (isset($pages['FAQ'])) {
            wp_update_nav_menu_item($primary_menu_id, 0, array(
                'menu-item-title'     => 'FAQ',
                'menu-item-object-id' => $pages['FAQ'],
                'menu-item-object'    => 'page',
                'menu-item-type'      => 'post_type',
                'menu-item-status'    => 'publish',
                'menu-item-position'  => 5,
            ));
        }

        // Assign menu to location
        $locations = get_theme_mod('nav_menu_locations');
        $locations['primary'] = $primary_menu_id;
        $locations['mobile'] = $primary_menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }

    // Create Footer Menu
    $footer_menu_id = wp_create_nav_menu('Footer Menu');

    if (!is_wp_error($footer_menu_id)) {
        $position = 1;

        foreach (array('Terms & Conditions', 'Privacy Policy', 'Shipping Information', 'FAQ') as $page_title) {
            if (isset($pages[$page_title])) {
                wp_update_nav_menu_item($footer_menu_id, 0, array(
                    'menu-item-title'     => $page_title,
                    'menu-item-object-id' => $pages[$page_title],
                    'menu-item-object'    => 'page',
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                    'menu-item-position'  => $position++,
                ));
            }
        }

        // Assign footer menu to location
        $locations = get_theme_mod('nav_menu_locations');
        $locations['footer'] = $footer_menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }
}

/**
 * Run on theme activation
 */
function fashionmen_theme_activation() {
    // Create default pages
    fashionmen_create_default_pages();

    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'fashionmen_theme_activation');

/**
 * Run when switching away from this theme
 */
function fashionmen_theme_deactivation() {
    // Reset the setup flag so pages can be created again if theme is reactivated
    delete_option('fashionmen_pages_created');
    delete_option('fashionmen_created_page_ids');
}
add_action('switch_theme', 'fashionmen_theme_deactivation');
