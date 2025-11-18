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

    // Hero Slider Section Settings

    // === SLIDE 1 ===
    $wp_customize->add_setting('aakaari_hero_slide_1_image', array(
        'default'           => 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=1600&q=80',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'aakaari_hero_slide_1_image', array(
        'label'       => __('Slide 1 - Background Image', 'aakaari-brand'),
        'section'     => 'aakaari_home_settings',
        'description' => __('Upload background image for slide 1 (recommended: 1600x600px)', 'aakaari-brand'),
    )));

    $wp_customize->add_setting('aakaari_hero_slide_1_tag', array(
        'default'           => 'NEW ARRIVAL',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('aakaari_hero_slide_1_tag', array(
        'label'   => __('Slide 1 - Tag', 'aakaari-brand'),
        'section' => 'aakaari_home_settings',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('aakaari_hero_slide_1_title', array(
        'default'           => 'Premium Streetwear Collection',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('aakaari_hero_slide_1_title', array(
        'label'   => __('Slide 1 - Title', 'aakaari-brand'),
        'section' => 'aakaari_home_settings',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('aakaari_hero_slide_1_subtitle', array(
        'default'           => 'Discover our latest collection of premium t-shirts and hoodies',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('aakaari_hero_slide_1_subtitle', array(
        'label'   => __('Slide 1 - Subtitle', 'aakaari-brand'),
        'section' => 'aakaari_home_settings',
        'type'    => 'textarea',
    ));

    $wp_customize->add_setting('aakaari_hero_slide_1_button_text', array(
        'default'           => 'Shop Now',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('aakaari_hero_slide_1_button_text', array(
        'label'   => __('Slide 1 - Primary Button Text', 'aakaari-brand'),
        'section' => 'aakaari_home_settings',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('aakaari_hero_slide_1_button_link', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('aakaari_hero_slide_1_button_link', array(
        'label'       => __('Slide 1 - Primary Button Link', 'aakaari-brand'),
        'section'     => 'aakaari_home_settings',
        'type'        => 'url',
        'description' => __('Leave empty to use shop page', 'aakaari-brand'),
    ));

    $wp_customize->add_setting('aakaari_hero_slide_1_button_2_text', array(
        'default'           => 'New Arrivals',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('aakaari_hero_slide_1_button_2_text', array(
        'label'       => __('Slide 1 - Secondary Button Text', 'aakaari-brand'),
        'section'     => 'aakaari_home_settings',
        'type'        => 'text',
        'description' => __('Leave empty to hide second button', 'aakaari-brand'),
    ));

    $wp_customize->add_setting('aakaari_hero_slide_1_button_2_link', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('aakaari_hero_slide_1_button_2_link', array(
        'label'   => __('Slide 1 - Secondary Button Link', 'aakaari-brand'),
        'section' => 'aakaari_home_settings',
        'type'    => 'url',
    ));

    // === SLIDE 2 ===
    $wp_customize->add_setting('aakaari_hero_slide_2_image', array(
        'default'           => 'https://images.unsplash.com/photo-1620799140188-3b2a02fd9a77?w=1600&q=80',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'aakaari_hero_slide_2_image', array(
        'label'       => __('Slide 2 - Background Image', 'aakaari-brand'),
        'section'     => 'aakaari_home_settings',
        'description' => __('Upload background image for slide 2 (recommended: 1600x600px)', 'aakaari-brand'),
    )));

    $wp_customize->add_setting('aakaari_hero_slide_2_tag', array(
        'default'           => 'TRENDING NOW',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('aakaari_hero_slide_2_tag', array(
        'label'   => __('Slide 2 - Tag', 'aakaari-brand'),
        'section' => 'aakaari_home_settings',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('aakaari_hero_slide_2_title', array(
        'default'           => 'Exclusive Collection',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('aakaari_hero_slide_2_title', array(
        'label'   => __('Slide 2 - Title', 'aakaari-brand'),
        'section' => 'aakaari_home_settings',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('aakaari_hero_slide_2_subtitle', array(
        'default'           => 'Limited edition pieces crafted for the bold and stylish',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('aakaari_hero_slide_2_subtitle', array(
        'label'   => __('Slide 2 - Subtitle', 'aakaari-brand'),
        'section' => 'aakaari_home_settings',
        'type'    => 'textarea',
    ));

    $wp_customize->add_setting('aakaari_hero_slide_2_button_text', array(
        'default'           => 'Explore Now',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('aakaari_hero_slide_2_button_text', array(
        'label'   => __('Slide 2 - Button Text', 'aakaari-brand'),
        'section' => 'aakaari_home_settings',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('aakaari_hero_slide_2_button_link', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('aakaari_hero_slide_2_button_link', array(
        'label'       => __('Slide 2 - Button Link', 'aakaari-brand'),
        'section'     => 'aakaari_home_settings',
        'type'        => 'url',
        'description' => __('Leave empty to use shop page', 'aakaari-brand'),
    ));

    // === SLIDE 3 ===
    $wp_customize->add_setting('aakaari_hero_slide_3_image', array(
        'default'           => 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=1600&q=80',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'aakaari_hero_slide_3_image', array(
        'label'       => __('Slide 3 - Background Image', 'aakaari-brand'),
        'section'     => 'aakaari_home_settings',
        'description' => __('Upload background image for slide 3 (recommended: 1600x600px)', 'aakaari-brand'),
    )));

    $wp_customize->add_setting('aakaari_hero_slide_3_tag', array(
        'default'           => 'SALE',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('aakaari_hero_slide_3_tag', array(
        'label'   => __('Slide 3 - Tag', 'aakaari-brand'),
        'section' => 'aakaari_home_settings',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('aakaari_hero_slide_3_title', array(
        'default'           => 'Up to 40% Off',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('aakaari_hero_slide_3_title', array(
        'label'   => __('Slide 3 - Title', 'aakaari-brand'),
        'section' => 'aakaari_home_settings',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('aakaari_hero_slide_3_subtitle', array(
        'default'           => 'Don\'t miss out on amazing deals on selected items',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('aakaari_hero_slide_3_subtitle', array(
        'label'   => __('Slide 3 - Subtitle', 'aakaari-brand'),
        'section' => 'aakaari_home_settings',
        'type'    => 'textarea',
    ));

    $wp_customize->add_setting('aakaari_hero_slide_3_button_text', array(
        'default'           => 'Shop Sale',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('aakaari_hero_slide_3_button_text', array(
        'label'   => __('Slide 3 - Button Text', 'aakaari-brand'),
        'section' => 'aakaari_home_settings',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('aakaari_hero_slide_3_button_link', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('aakaari_hero_slide_3_button_link', array(
        'label'       => __('Slide 3 - Button Link', 'aakaari-brand'),
        'section'     => 'aakaari_home_settings',
        'type'        => 'url',
        'description' => __('Leave empty to use sale products page', 'aakaari-brand'),
    ));

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
