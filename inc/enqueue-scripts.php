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

    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'aakaari_enqueue_scripts');
