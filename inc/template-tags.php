<?php
/**
 * Custom template tags for this theme
 *
 * @package FashionMen
 * @since 1.0.0
 */

/**
 * Prints HTML with meta information for the current post-date/time.
 */
function fashionmen_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

    $time_string = sprintf(
        $time_string,
        esc_attr(get_the_date(DATE_W3C)),
        esc_html(get_the_date())
    );

    $posted_on = sprintf(
        /* translators: %s: post date. */
        esc_html_x('Posted on %s', 'post date', 'fashionmen'),
        '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
    );

    echo '<span class="posted-on text-sm text-gray-600">' . $posted_on . '</span>';
}

/**
 * Prints HTML with meta information for the current author.
 */
function fashionmen_posted_by() {
    $byline = sprintf(
        /* translators: %s: post author. */
        esc_html_x('by %s', 'post author', 'fashionmen'),
        '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
    );

    echo '<span class="byline text-sm text-gray-600"> ' . $byline . '</span>';
}

/**
 * Display categories
 */
function fashionmen_entry_categories() {
    if ('post' === get_post_type()) {
        $categories_list = get_the_category_list(esc_html__(', ', 'fashionmen'));
        if ($categories_list) {
            printf('<span class="cat-links text-sm">' . esc_html__('Categories: %1$s', 'fashionmen') . '</span>', $categories_list);
        }
    }
}

/**
 * Display tags
 */
function fashionmen_entry_tags() {
    if ('post' === get_post_type()) {
        $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'fashionmen'));
        if ($tags_list) {
            printf('<span class="tags-links text-sm flex flex-wrap gap-2">' . esc_html__('Tags: %1$s', 'fashionmen') . '</span>', $tags_list);
        }
    }
}

/**
 * Post navigation
 */
function fashionmen_post_navigation() {
    the_post_navigation(array(
        'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'fashionmen') . '</span> <span class="nav-title">%title</span>',
        'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'fashionmen') . '</span> <span class="nav-title">%title</span>',
    ));
}
