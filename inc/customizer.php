<?php
/**
 * Theme Customizer
 *
 * @package Aakaari_Brand
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add customizer settings
 */
function aakaari_customize_register($wp_customize) {

    /**
     * Home Page Settings Section
     */
    $wp_customize->add_section('aakaari_home_settings', array(
        'title'    => __('Home Page Settings', 'aakaari-brand'),
        'priority' => 30,
    ));

    // Hero Section Settings
    $wp_customize->add_setting('aakaari_hero_tag', array(
        'default'           => 'NEW ARRIVAL',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aakaari_hero_tag', array(
        'label'       => __('Hero Tag', 'aakaari-brand'),
        'section'     => 'aakaari_home_settings',
        'type'        => 'text',
        'description' => __('Small tag text above hero title', 'aakaari-brand'),
    ));

    $wp_customize->add_setting('aakaari_hero_title', array(
        'default'           => 'Premium Streetwear Collection',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aakaari_hero_title', array(
        'label'   => __('Hero Title', 'aakaari-brand'),
        'section' => 'aakaari_home_settings',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('aakaari_hero_subtitle', array(
        'default'           => 'Discover our latest collection of premium t-shirts and hoodies',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('aakaari_hero_subtitle', array(
        'label'   => __('Hero Subtitle', 'aakaari-brand'),
        'section' => 'aakaari_home_settings',
        'type'    => 'textarea',
    ));

    $wp_customize->add_setting('aakaari_hero_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'aakaari_hero_image', array(
        'label'       => __('Hero Background Image', 'aakaari-brand'),
        'section'     => 'aakaari_home_settings',
        'description' => __('Upload a hero background image (recommended: 1920x600px)', 'aakaari-brand'),
    )));

    // Promo Section Settings
    $wp_customize->add_setting('aakaari_promo_badge', array(
        'default'           => 'Premium',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aakaari_promo_badge', array(
        'label'   => __('Promo Badge', 'aakaari-brand'),
        'section' => 'aakaari_home_settings',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('aakaari_promo_title', array(
        'default'           => 'Crafted for Excellence',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aakaari_promo_title', array(
        'label'   => __('Promo Title', 'aakaari-brand'),
        'section' => 'aakaari_home_settings',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('aakaari_promo_description', array(
        'default'           => 'Every piece is thoughtfully designed and made with premium materials. Experience comfort that lasts, style that stands out.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('aakaari_promo_description', array(
        'label'   => __('Promo Description', 'aakaari-brand'),
        'section' => 'aakaari_home_settings',
        'type'    => 'textarea',
    ));

    $wp_customize->add_setting('aakaari_promo_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'aakaari_promo_image', array(
        'label'       => __('Promo Image', 'aakaari-brand'),
        'section'     => 'aakaari_home_settings',
        'description' => __('Upload a promo image (recommended: 600x800px)', 'aakaari-brand'),
    )));

    /**
     * Logo Support
     */
    $wp_customize->add_setting('aakaari_logo_width', array(
        'default'           => '150',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aakaari_logo_width', array(
        'label'       => __('Logo Width (px)', 'aakaari-brand'),
        'section'     => 'title_tagline',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 50,
            'max'  => 500,
            'step' => 10,
        ),
    ));
}
add_action('customize_register', 'aakaari_customize_register');

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously
 */
function aakaari_customize_preview_js() {
    wp_enqueue_script(
        'aakaari-customizer',
        AAKAARI_THEME_URI . '/assets/js/customizer.js',
        array('customize-preview'),
        AAKAARI_THEME_VERSION,
        true
    );
}
add_action('customize_preview_init', 'aakaari_customize_preview_js');
