<?php
/**
 * Enqueue Scripts and Styles
 *
 * @package Aakaari_Brand
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue theme styles
 */
function aakaari_enqueue_styles() {
    // Main theme stylesheet
    wp_enqueue_style(
        'aakaari-style',
        get_stylesheet_uri(),
        array(),
        AAKAARI_THEME_VERSION
    );

    // Custom CSS file
    wp_enqueue_style(
        'aakaari-main',
        AAKAARI_THEME_URI . '/assets/css/main.css',
        array(),
        AAKAARI_THEME_VERSION
    );

    // Header styles
    wp_enqueue_style(
        'aakaari-header',
        AAKAARI_THEME_URI . '/assets/css/header.css',
        array('aakaari-main'),
        AAKAARI_THEME_VERSION
    );

    // Home page styles
    if (is_page_template('template-home.php')) {
        wp_enqueue_style(
            'aakaari-home',
            AAKAARI_THEME_URI . '/assets/css/home.css',
            array('aakaari-main'),
            AAKAARI_THEME_VERSION
        );
    }
}
add_action('wp_enqueue_scripts', 'aakaari_enqueue_styles');

/**
 * Enqueue theme scripts
 */
function aakaari_enqueue_scripts() {
    // Main JavaScript file
    wp_enqueue_script(
        'aakaari-main',
        AAKAARI_THEME_URI . '/assets/js/main.js',
        array('jquery'),
        AAKAARI_THEME_VERSION,
        true
    );

    // Header scripts
    wp_enqueue_script(
        'aakaari-header',
        AAKAARI_THEME_URI . '/assets/js/header.js',
        array('jquery'),
        AAKAARI_THEME_VERSION,
        true
    );

    // Localize script for AJAX (used by both header and home scripts)
    wp_localize_script('aakaari-header', 'aakaariAjax', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('aakaari-ajax-nonce'),
    ));

    // Home page scripts
    if (is_page_template('template-home.php')) {
        wp_enqueue_script(
            'aakaari-home',
            AAKAARI_THEME_URI . '/assets/js/home.js',
            array('jquery'),
            AAKAARI_THEME_VERSION,
            true
        );

        // Localize script for AJAX (home page needs it too)
        wp_localize_script('aakaari-home', 'aakaariAjax', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('aakaari-ajax-nonce'),
        ));
    }

    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'aakaari_enqueue_scripts');
