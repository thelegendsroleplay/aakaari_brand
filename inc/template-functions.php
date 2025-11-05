<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package FashionMen
 * @since 1.0.0
 */

/**
 * Adds custom classes to the array of body classes.
 */
function fashionmen_body_classes($classes) {
    // Adds a class of hfeed to non-singular pages.
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }

    // Adds a class of no-sidebar when there is no sidebar present.
    if (!is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }

    return $classes;
}
add_filter('body_class', 'fashionmen_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function fashionmen_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'fashionmen_pingback_header');

/**
 * Default menu fallback
 */
function fashionmen_default_menu() {
    echo '<ul class="flex items-center gap-8">';
    echo '<li><a href="' . esc_url(home_url('/')) . '" class="hover:text-gray-600 transition-colors">' . esc_html__('Home', 'fashionmen') . '</a></li>';

    if (class_exists('WooCommerce')) {
        echo '<li><a href="' . esc_url(get_permalink(wc_get_page_id('shop'))) . '" class="hover:text-gray-600 transition-colors">' . esc_html__('Shop', 'fashionmen') . '</a></li>';
    }

    $about_page = get_page_by_path('about');
    if ($about_page) {
        echo '<li><a href="' . esc_url(get_permalink($about_page)) . '" class="hover:text-gray-600 transition-colors">' . esc_html__('About', 'fashionmen') . '</a></li>';
    }

    $contact_page = get_page_by_path('contact');
    if ($contact_page) {
        echo '<li><a href="' . esc_url(get_permalink($contact_page)) . '" class="hover:text-gray-600 transition-colors">' . esc_html__('Contact', 'fashionmen') . '</a></li>';
    }

    $faq_page = get_page_by_path('faq');
    if ($faq_page) {
        echo '<li><a href="' . esc_url(get_permalink($faq_page)) . '" class="hover:text-gray-600 transition-colors">' . esc_html__('FAQ', 'fashionmen') . '</a></li>';
    }

    echo '</ul>';
}

/**
 * Estimated reading time
 */
function fashionmen_reading_time() {
    $content = get_post_field('post_content', get_the_ID());
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200);

    return $reading_time;
}

/**
 * Get excerpt with custom length
 */
function fashionmen_custom_excerpt($limit = 30) {
    $excerpt = get_the_excerpt();
    $excerpt = explode(' ', $excerpt, $limit);

    if (count($excerpt) >= $limit) {
        array_pop($excerpt);
        $excerpt = implode(' ', $excerpt) . '...';
    } else {
        $excerpt = implode(' ', $excerpt);
    }

    return $excerpt;
}
