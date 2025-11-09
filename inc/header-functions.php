<?php
/**
 * Header Functions
 *
 * @package Aakaari_Brand
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Default menu fallback for desktop navigation
 */
function aakaari_default_menu() {
    ?>
    <ul class="header-nav-menu">
        <li class="menu-item<?php echo is_front_page() ? ' current-menu-item' : ''; ?>">
            <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
        </li>
        <?php if (is_woocommerce_activated()) : ?>
            <li class="menu-item">
                <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">Shop</a>
            </li>
        <?php endif; ?>
    </ul>
    <?php
}

/**
 * Default menu fallback for mobile navigation
 */
function aakaari_default_mobile_menu() {
    ?>
    <ul class="header-mobile-nav">
        <li class="menu-item<?php echo is_front_page() ? ' current-menu-item' : ''; ?>">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="header-mobile-menu-item">Home</a>
        </li>
        <?php if (is_woocommerce_activated()) : ?>
            <li class="menu-item">
                <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="header-mobile-menu-item">Shop</a>
            </li>
        <?php endif; ?>
    </ul>
    <?php
}

/**
 * Custom menu walker for desktop nav
 */
class Aakaari_Desktop_Nav_Walker extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $class_names = 'header-nav-link';

        if (in_array('current-menu-item', $classes) || in_array('current_page_item', $classes)) {
            $class_names .= ' header-nav-link-active';
        }

        $output .= '<button onclick="window.location.href=\'' . esc_url($item->url) . '\'" class="' . esc_attr($class_names) . '">';
        $output .= esc_html($item->title);
        $output .= '</button>';
    }

    function start_lvl(&$output, $depth = 0, $args = null) {
        // No sub-menus for this design
    }

    function end_lvl(&$output, $depth = 0, $args = null) {
        // No sub-menus for this design
    }

    function end_el(&$output, $item, $depth = 0, $args = null) {
        // No closing tag needed for button
    }
}

/**
 * Custom menu walker for mobile nav
 */
class Aakaari_Mobile_Nav_Walker extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $class_names = 'header-mobile-menu-item';

        if (in_array('current-menu-item', $classes) || in_array('current_page_item', $classes)) {
            $class_names .= ' header-mobile-menu-item-active';
        }

        $output .= '<a href="' . esc_url($item->url) . '" class="' . esc_attr($class_names) . '">';
        $output .= esc_html($item->title);
        $output .= '</a>';
    }

    function start_lvl(&$output, $depth = 0, $args = null) {
        // No sub-menus for this design
    }

    function end_lvl(&$output, $depth = 0, $args = null) {
        // No sub-menus for this design
    }

    function end_el(&$output, $item, $depth = 0, $args = null) {
        // No closing tag needed
    }
}
