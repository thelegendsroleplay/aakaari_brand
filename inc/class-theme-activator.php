<?php
/**
 * Theme Activator Class
 *
 * Handles automatic theme activation tasks:
 * - Deletes all existing pages and posts
 * - Creates required pages
 * - Configures WordPress settings
 * - Sets up WooCommerce
 *
 * @package Aakaari_Brand
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Aakaari_Theme_Activator {

    /**
     * Run activation tasks
     */
    public function activate() {
        // Delete all pages and posts
        $this->delete_all_content();

        // Create required pages
        $this->create_required_pages();

        // Setup WordPress settings
        $this->setup_wordpress_settings();

        // Setup WooCommerce
        $this->setup_woocommerce();

        // Flush rewrite rules
        flush_rewrite_rules();

        // Set activation flag
        update_option( 'aakaari_theme_activated', true );
    }

    /**
     * Delete all existing pages and posts
     */
    private function delete_all_content() {
        global $wpdb;

        // Get all posts and pages
        $posts = get_posts( array(
            'post_type'      => array( 'post', 'page' ),
            'posts_per_page' => -1,
            'post_status'    => 'any',
        ) );

        // Delete each post/page
        foreach ( $posts as $post ) {
            wp_delete_post( $post->ID, true );
        }

        // Clean up post meta
        $wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE post_id NOT IN (SELECT ID FROM {$wpdb->posts})" );
    }

    /**
     * Create required pages
     */
    private function create_required_pages() {
        $pages = array(
            array(
                'title'    => 'Home',
                'slug'     => 'home',
                'template' => 'front-page.php',
                'content'  => '<!-- Home Page Content -->',
            ),
            array(
                'title'    => 'Shop',
                'slug'     => 'shop',
                'template' => '',
                'content'  => '<!-- Shop Page Content -->',
            ),
            array(
                'title'    => 'Cart',
                'slug'     => 'cart',
                'template' => '',
                'content'  => '[woocommerce_cart]',
            ),
            array(
                'title'    => 'Checkout',
                'slug'     => 'checkout',
                'template' => '',
                'content'  => '[woocommerce_checkout]',
            ),
            array(
                'title'    => 'My Account',
                'slug'     => 'my-account',
                'template' => '',
                'content'  => '[woocommerce_my_account]',
            ),
            array(
                'title'    => 'About',
                'slug'     => 'about',
                'template' => '',
                'content'  => '<h1>About Us</h1><p>Welcome to our store.</p>',
            ),
            array(
                'title'    => 'Contact',
                'slug'     => 'contact',
                'template' => '',
                'content'  => '<h1>Contact Us</h1><p>Get in touch with us.</p>',
            ),
            array(
                'title'    => 'Support',
                'slug'     => 'support',
                'template' => '',
                'content'  => '<h1>Support Center</h1><p>How can we help you?</p>',
            ),
            array(
                'title'    => 'Privacy Policy',
                'slug'     => 'privacy-policy',
                'template' => '',
                'content'  => '<h1>Privacy Policy</h1><p>Your privacy is important to us.</p>',
            ),
            array(
                'title'    => 'Terms & Conditions',
                'slug'     => 'terms-conditions',
                'template' => '',
                'content'  => '<h1>Terms & Conditions</h1><p>Please read these terms carefully.</p>',
            ),
        );

        foreach ( $pages as $page_data ) {
            $page_id = wp_insert_post( array(
                'post_title'   => $page_data['title'],
                'post_name'    => $page_data['slug'],
                'post_content' => $page_data['content'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_author'  => 1,
            ) );

            if ( $page_id && ! is_wp_error( $page_id ) ) {
                // Set page template if specified
                if ( ! empty( $page_data['template'] ) ) {
                    update_post_meta( $page_id, '_wp_page_template', $page_data['template'] );
                }

                // Store page IDs for later use
                update_option( 'aakaari_page_' . $page_data['slug'], $page_id );
            }
        }

        // Set front page
        $home_page_id = get_option( 'aakaari_page_home' );
        if ( $home_page_id ) {
            update_option( 'show_on_front', 'page' );
            update_option( 'page_on_front', $home_page_id );
        }

        // Set blog page (create if needed)
        $blog_page_id = wp_insert_post( array(
            'post_title'   => 'Blog',
            'post_name'    => 'blog',
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_author'  => 1,
        ) );
        if ( $blog_page_id && ! is_wp_error( $blog_page_id ) ) {
            update_option( 'page_for_posts', $blog_page_id );
        }
    }

    /**
     * Setup WordPress settings
     */
    private function setup_wordpress_settings() {
        // Permalink structure
        update_option( 'permalink_structure', '/%postname%/' );

        // Site title and tagline
        update_option( 'blogname', 'StreetStyle' );
        update_option( 'blogdescription', 'Premium Streetwear Collection' );

        // Date and time settings
        update_option( 'timezone_string', 'UTC' );
        update_option( 'date_format', 'F j, Y' );
        update_option( 'time_format', 'g:i a' );

        // Disable comments on pages by default
        update_option( 'default_comment_status', 'closed' );
        update_option( 'default_ping_status', 'closed' );

        // Media settings
        update_option( 'thumbnail_size_w', 300 );
        update_option( 'thumbnail_size_h', 300 );
        update_option( 'thumbnail_crop', 1 );
        update_option( 'medium_size_w', 600 );
        update_option( 'medium_size_h', 600 );
        update_option( 'large_size_w', 1024 );
        update_option( 'large_size_h', 1024 );

        // Reading settings
        update_option( 'posts_per_page', 10 );
    }

    /**
     * Setup WooCommerce settings
     */
    private function setup_woocommerce() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        // Set WooCommerce pages
        $shop_page_id     = get_option( 'aakaari_page_shop' );
        $cart_page_id     = get_option( 'aakaari_page_cart' );
        $checkout_page_id = get_option( 'aakaari_page_checkout' );
        $account_page_id  = get_option( 'aakaari_page_my-account' );

        if ( $shop_page_id ) {
            update_option( 'woocommerce_shop_page_id', $shop_page_id );
        }
        if ( $cart_page_id ) {
            update_option( 'woocommerce_cart_page_id', $cart_page_id );
        }
        if ( $checkout_page_id ) {
            update_option( 'woocommerce_checkout_page_id', $checkout_page_id );
        }
        if ( $account_page_id ) {
            update_option( 'woocommerce_myaccount_page_id', $account_page_id );
        }

        // Currency settings
        update_option( 'woocommerce_currency', 'USD' );
        update_option( 'woocommerce_currency_pos', 'left' );
        update_option( 'woocommerce_price_thousand_sep', ',' );
        update_option( 'woocommerce_price_decimal_sep', '.' );
        update_option( 'woocommerce_price_num_decimals', 2 );

        // Product settings
        update_option( 'woocommerce_shop_page_display', '' );
        update_option( 'woocommerce_category_archive_display', '' );
        update_option( 'woocommerce_default_catalog_orderby', 'menu_order' );

        // Product images
        update_option( 'woocommerce_thumbnail_image_width', 300 );
        update_option( 'woocommerce_thumbnail_image_height', 300 );
        update_option( 'woocommerce_thumbnail_cropping', '1:1' );
        update_option( 'woocommerce_single_image_width', 600 );

        // Inventory
        update_option( 'woocommerce_manage_stock', 'yes' );
        update_option( 'woocommerce_notify_low_stock', 'yes' );
        update_option( 'woocommerce_notify_no_stock', 'yes' );

        // Shipping
        update_option( 'woocommerce_ship_to_countries', '' );
        update_option( 'woocommerce_shipping_cost_requires_address', 'no' );

        // Tax
        update_option( 'woocommerce_calc_taxes', 'no' );

        // Checkout
        update_option( 'woocommerce_enable_guest_checkout', 'yes' );
        update_option( 'woocommerce_enable_signup_and_login_from_checkout', 'yes' );
        update_option( 'woocommerce_enable_checkout_login_reminder', 'yes' );

        // Account
        update_option( 'woocommerce_enable_myaccount_registration', 'yes' );

        // Privacy
        $privacy_page_id = get_option( 'aakaari_page_privacy-policy' );
        if ( $privacy_page_id ) {
            update_option( 'wp_page_for_privacy_policy', $privacy_page_id );
            update_option( 'woocommerce_privacy_policy_page_id', $privacy_page_id );
        }

        $terms_page_id = get_option( 'aakaari_page_terms-conditions' );
        if ( $terms_page_id ) {
            update_option( 'woocommerce_terms_page_id', $terms_page_id );
        }
    }
}
