<?php
/**
 * TEMPORARY DEBUG FILE
 * This file is automatically included in woocommerce.php for debugging
 * DELETE THIS FILE AFTER DEBUGGING
 */

// Add to header so we can see in browser console
add_action('wp_head', function() {
    ?>
    <script>
    console.log('=== DEBUG INFO ===');
    console.log('is_shop: <?php echo is_shop() ? 'YES' : 'NO'; ?>');
    console.log('is_product: <?php echo is_product() ? 'YES' : 'NO'; ?>');
    console.log('is_product_category: <?php echo is_product_category() ? 'YES' : 'NO'; ?>');
    console.log('is_cart: <?php echo is_cart() ? 'YES' : 'NO'; ?>');
    console.log('is_checkout: <?php echo is_checkout() ? 'YES' : 'NO'; ?>');
    console.log('Theme Version: <?php echo AAKAARI_THEME_VERSION; ?>');
    console.log('Theme URI: <?php echo AAKAARI_THEME_URI; ?>');
    console.log('=== ENQUEUED STYLES ===');
    <?php
    global $wp_styles;
    if (isset($wp_styles->queue)) {
        foreach ($wp_styles->queue as $handle) {
            if (strpos($handle, 'aakaari') !== false) {
                echo "console.log('Style: $handle');";
            }
        }
    }
    ?>
    console.log('=== ENQUEUED SCRIPTS ===');
    <?php
    global $wp_scripts;
    if (isset($wp_scripts->queue)) {
        foreach ($wp_scripts->queue as $handle) {
            if (strpos($handle, 'aakaari') !== false) {
                echo "console.log('Script: $handle');";
            }
        }
    }
    ?>
    console.log('=== END DEBUG INFO ===');
    </script>
    <?php
}, 999);
