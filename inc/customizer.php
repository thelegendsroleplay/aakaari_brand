<?php
/**
 * Theme Customizer
 *
 * @package Aakaari_Brand
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add customizer options
 */
function aakaari_brand_customize_register( $wp_customize ) {

    // Add custom color scheme section
    $wp_customize->add_section( 'aakaari_brand_colors', array(
        'title'    => __( 'Theme Colors', 'aakaari-brand' ),
        'priority' => 30,
    ) );

    // Primary Color
    $wp_customize->add_setting( 'aakaari_brand_primary_color', array(
        'default'           => '#0073aa',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aakaari_brand_primary_color', array(
        'label'    => __( 'Primary Color', 'aakaari-brand' ),
        'section'  => 'aakaari_brand_colors',
        'settings' => 'aakaari_brand_primary_color',
    ) ) );

    // Secondary Color
    $wp_customize->add_setting( 'aakaari_brand_secondary_color', array(
        'default'           => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aakaari_brand_secondary_color', array(
        'label'    => __( 'Secondary Color', 'aakaari-brand' ),
        'section'  => 'aakaari_brand_colors',
        'settings' => 'aakaari_brand_secondary_color',
    ) ) );

    // Header Settings Section
    $wp_customize->add_section( 'aakaari_brand_header', array(
        'title'    => __( 'Header Settings', 'aakaari-brand' ),
        'priority' => 40,
    ) );

    // Show/Hide Site Title
    $wp_customize->add_setting( 'aakaari_brand_show_site_title', array(
        'default'           => true,
        'sanitize_callback' => 'aakaari_brand_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'aakaari_brand_show_site_title', array(
        'label'    => __( 'Display Site Title', 'aakaari-brand' ),
        'section'  => 'aakaari_brand_header',
        'settings' => 'aakaari_brand_show_site_title',
        'type'     => 'checkbox',
    ) );

    // Show/Hide Tagline
    $wp_customize->add_setting( 'aakaari_brand_show_tagline', array(
        'default'           => true,
        'sanitize_callback' => 'aakaari_brand_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'aakaari_brand_show_tagline', array(
        'label'    => __( 'Display Tagline', 'aakaari-brand' ),
        'section'  => 'aakaari_brand_header',
        'settings' => 'aakaari_brand_show_tagline',
        'type'     => 'checkbox',
    ) );

    // Footer Settings Section
    $wp_customize->add_section( 'aakaari_brand_footer', array(
        'title'    => __( 'Footer Settings', 'aakaari-brand' ),
        'priority' => 50,
    ) );

    // Footer Copyright Text
    $wp_customize->add_setting( 'aakaari_brand_footer_copyright', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'aakaari_brand_footer_copyright', array(
        'label'       => __( 'Footer Copyright Text', 'aakaari-brand' ),
        'section'     => 'aakaari_brand_footer',
        'settings'    => 'aakaari_brand_footer_copyright',
        'type'        => 'text',
        'description' => __( 'Enter custom copyright text for the footer.', 'aakaari-brand' ),
    ) );

    // Layout Settings Section
    $wp_customize->add_section( 'aakaari_brand_layout', array(
        'title'    => __( 'Layout Settings', 'aakaari-brand' ),
        'priority' => 60,
    ) );

    // Sidebar Position
    $wp_customize->add_setting( 'aakaari_brand_sidebar_position', array(
        'default'           => 'right',
        'sanitize_callback' => 'aakaari_brand_sanitize_select',
    ) );

    $wp_customize->add_control( 'aakaari_brand_sidebar_position', array(
        'label'    => __( 'Sidebar Position', 'aakaari-brand' ),
        'section'  => 'aakaari_brand_layout',
        'settings' => 'aakaari_brand_sidebar_position',
        'type'     => 'select',
        'choices'  => array(
            'left'  => __( 'Left', 'aakaari-brand' ),
            'right' => __( 'Right', 'aakaari-brand' ),
            'none'  => __( 'No Sidebar', 'aakaari-brand' ),
        ),
    ) );

    // Container Width
    $wp_customize->add_setting( 'aakaari_brand_container_width', array(
        'default'           => 1200,
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'aakaari_brand_container_width', array(
        'label'       => __( 'Container Width (px)', 'aakaari-brand' ),
        'section'     => 'aakaari_brand_layout',
        'settings'    => 'aakaari_brand_container_width',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 960,
            'max'  => 1920,
            'step' => 10,
        ),
    ) );

    // Typography Section
    $wp_customize->add_section( 'aakaari_brand_typography', array(
        'title'    => __( 'Typography', 'aakaari-brand' ),
        'priority' => 70,
    ) );

    // Font Family
    $wp_customize->add_setting( 'aakaari_brand_font_family', array(
        'default'           => 'system',
        'sanitize_callback' => 'aakaari_brand_sanitize_select',
    ) );

    $wp_customize->add_control( 'aakaari_brand_font_family', array(
        'label'    => __( 'Font Family', 'aakaari-brand' ),
        'section'  => 'aakaari_brand_typography',
        'settings' => 'aakaari_brand_font_family',
        'type'     => 'select',
        'choices'  => array(
            'system'      => __( 'System Fonts', 'aakaari-brand' ),
            'arial'       => 'Arial',
            'helvetica'   => 'Helvetica',
            'georgia'     => 'Georgia',
            'times'       => 'Times New Roman',
            'courier'     => 'Courier New',
        ),
    ) );

    // Base Font Size
    $wp_customize->add_setting( 'aakaari_brand_font_size', array(
        'default'           => 16,
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'aakaari_brand_font_size', array(
        'label'       => __( 'Base Font Size (px)', 'aakaari-brand' ),
        'section'     => 'aakaari_brand_typography',
        'settings'    => 'aakaari_brand_font_size',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 12,
            'max'  => 24,
            'step' => 1,
        ),
    ) );
}
add_action( 'customize_register', 'aakaari_brand_customize_register' );

/**
 * Sanitize checkbox
 */
function aakaari_brand_sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && true === $checked ) ? true : false );
}

/**
 * Sanitize select
 */
function aakaari_brand_sanitize_select( $input, $setting ) {
    $input   = sanitize_key( $input );
    $choices = $setting->manager->get_control( $setting->id )->choices;
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Render customizer styles
 */
function aakaari_brand_customizer_css() {
    $primary_color     = get_theme_mod( 'aakaari_brand_primary_color', '#0073aa' );
    $secondary_color   = get_theme_mod( 'aakaari_brand_secondary_color', '#333333' );
    $container_width   = get_theme_mod( 'aakaari_brand_container_width', 1200 );
    $font_size         = get_theme_mod( 'aakaari_brand_font_size', 16 );
    $font_family       = get_theme_mod( 'aakaari_brand_font_family', 'system' );

    $font_family_map = array(
        'system'    => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
        'arial'     => 'Arial, sans-serif',
        'helvetica' => 'Helvetica, sans-serif',
        'georgia'   => 'Georgia, serif',
        'times'     => '"Times New Roman", Times, serif',
        'courier'   => '"Courier New", Courier, monospace',
    );

    $selected_font = isset( $font_family_map[ $font_family ] ) ? $font_family_map[ $font_family ] : $font_family_map['system'];

    ?>
    <style type="text/css">
        body {
            font-family: <?php echo $selected_font; ?>;
            font-size: <?php echo absint( $font_size ); ?>px;
        }
        .site-container {
            max-width: <?php echo absint( $container_width ); ?>px;
        }
        a,
        .button,
        button,
        input[type="submit"],
        .woocommerce ul.products li.product .price {
            color: <?php echo esc_attr( $primary_color ); ?>;
        }
        .button,
        button,
        input[type="submit"],
        input[type="button"],
        .main-navigation {
            background-color: <?php echo esc_attr( $secondary_color ); ?>;
        }
        .site-footer {
            background-color: <?php echo esc_attr( $secondary_color ); ?>;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'aakaari_brand_customizer_css' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously
 */
function aakaari_brand_customize_preview_js() {
    wp_enqueue_script(
        'aakaari-brand-customizer',
        get_template_directory_uri() . '/js/customizer.js',
        array( 'customize-preview' ),
        '1.0.0',
        true
    );
}
add_action( 'customize_preview_init', 'aakaari_brand_customize_preview_js' );
