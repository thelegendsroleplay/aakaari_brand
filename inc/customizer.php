<?php
/**
 * Theme Customizer
 *
 * @package FashionMen
 * @since 1.0.0
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 */
function fashionmen_customize_register($wp_customize) {
    $wp_customize->get_setting('blogname')->transport         = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport  = 'postMessage';

    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            array(
                'selector'        => '.site-title a',
                'render_callback' => 'fashionmen_customize_partial_blogname',
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            array(
                'selector'        => '.site-description',
                'render_callback' => 'fashionmen_customize_partial_blogdescription',
            )
        );
    }

    // Add theme options section
    $wp_customize->add_section('fashionmen_options', array(
        'title'    => esc_html__('Theme Options', 'fashionmen'),
        'priority' => 130,
    ));

    // Add social media settings
    $social_networks = array(
        'facebook'  => esc_html__('Facebook URL', 'fashionmen'),
        'instagram' => esc_html__('Instagram URL', 'fashionmen'),
        'twitter'   => esc_html__('Twitter URL', 'fashionmen'),
        'pinterest' => esc_html__('Pinterest URL', 'fashionmen'),
    );

    foreach ($social_networks as $network => $label) {
        $wp_customize->add_setting('fashionmen_' . $network, array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control('fashionmen_' . $network, array(
            'label'    => $label,
            'section'  => 'fashionmen_options',
            'type'     => 'url',
        ));
    }
}
add_action('customize_register', 'fashionmen_customize_register');

/**
 * Render the site title for the selective refresh partial.
 */
function fashionmen_customize_partial_blogname() {
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 */
function fashionmen_customize_partial_blogdescription() {
    bloginfo('description');
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function fashionmen_customize_preview_js() {
    wp_enqueue_script('fashionmen-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array('customize-preview'), FASHIONMEN_VERSION, true);
}
add_action('customize_preview_init', 'fashionmen_customize_preview_js');
