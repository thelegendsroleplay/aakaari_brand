<?php
/**
 * TEMPORARY DEBUG FILE
 * Add this to the top of woocommerce.php to debug what's loading
 * DELETE THIS FILE AFTER DEBUGGING
 */

// Debug: Check page type
echo '<!-- DEBUG INFO -->';
echo '<!-- is_shop: ' . (is_shop() ? 'YES' : 'NO') . ' -->';
echo '<!-- is_product: ' . (is_product() ? 'YES' : 'NO') . ' -->';
echo '<!-- is_product_category: ' . (is_product_category() ? 'YES' : 'NO') . ' -->';
echo '<!-- is_cart: ' . (is_cart() ? 'YES' : 'NO') . ' -->';
echo '<!-- is_checkout: ' . (is_checkout() ? 'YES' : 'NO') . ' -->';
echo '<!-- Theme Version: ' . AAKAARI_THEME_VERSION . ' -->';
echo '<!-- Theme URI: ' . AAKAARI_THEME_URI . ' -->';

// Check if styles are enqueued
global $wp_styles;
echo '<!-- Enqueued Styles: -->';
if (isset($wp_styles->queue)) {
    foreach ($wp_styles->queue as $handle) {
        if (strpos($handle, 'aakaari') !== false) {
            echo '<!-- Style: ' . $handle . ' -->';
        }
    }
}

// Check if scripts are enqueued
global $wp_scripts;
echo '<!-- Enqueued Scripts: -->';
if (isset($wp_scripts->queue)) {
    foreach ($wp_scripts->queue as $handle) {
        if (strpos($handle, 'aakaari') !== false) {
            echo '<!-- Script: ' . $handle . ' -->';
        }
    }
}
echo '<!-- END DEBUG INFO -->';
