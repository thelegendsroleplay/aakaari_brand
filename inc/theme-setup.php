<?php
/**
 * Theme Setup and Welcome Page
 *
 * @package FashionMen
 * @since 1.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Add theme setup page to admin menu
 */
function fashionmen_add_setup_page() {
    add_theme_page(
        __('FashionMen Setup', 'fashionmen'),
        __('Theme Setup', 'fashionmen'),
        'manage_options',
        'fashionmen-setup',
        'fashionmen_render_setup_page'
    );
}
add_action('admin_menu', 'fashionmen_add_setup_page');

/**
 * Handle manual page creation
 */
function fashionmen_handle_manual_setup() {
    if (isset($_POST['fashionmen_create_pages']) && check_admin_referer('fashionmen_setup', 'fashionmen_setup_nonce')) {
        // Reset the flag
        delete_option('fashionmen_pages_created');
        delete_option('fashionmen_created_page_ids');

        // Run the setup
        if (function_exists('fashionmen_create_default_pages')) {
            fashionmen_create_default_pages();

            // Redirect back with success message
            wp_safe_redirect(add_query_arg('setup_done', '1', admin_url('themes.php?page=fashionmen-setup')));
            exit;
        }
    }
}
add_action('admin_init', 'fashionmen_handle_manual_setup');

/**
 * Display setup success notice
 */
function fashionmen_setup_admin_notices() {
    if (isset($_GET['setup_done']) && $_GET['setup_done'] === '1') {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php esc_html_e('✅ Pages and menus have been created successfully!', 'fashionmen'); ?></p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'fashionmen_setup_admin_notices');

/**
 * Redirect to setup page on theme activation
 */
function fashionmen_setup_redirect() {
    global $pagenow;

    if (is_admin() && 'themes.php' === $pagenow && isset($_GET['activated'])) {
        wp_safe_redirect(admin_url('themes.php?page=fashionmen-setup'));
        exit;
    }
}
add_action('admin_init', 'fashionmen_setup_redirect');

/**
 * Render the setup page
 */
function fashionmen_render_setup_page() {
    $pages_created = get_option('fashionmen_pages_created');
    $created_page_ids = get_option('fashionmen_created_page_ids', array());
    ?>
    <div class="wrap fashionmen-setup-page">
        <h1><?php esc_html_e('Welcome to FashionMen Theme', 'fashionmen'); ?></h1>

        <div class="fashionmen-setup-content" style="max-width: 1200px;">

            <!-- Hero Section -->
            <div style="background: linear-gradient(135deg, #030213 0%, #1f1f2e 100%); color: white; padding: 40px; border-radius: 8px; margin: 20px 0;">
                <h2 style="color: white; margin-top: 0;"><?php esc_html_e('🎉 Your Theme is Ready!', 'fashionmen'); ?></h2>
                <p style="font-size: 18px; margin-bottom: 0;"><?php esc_html_e('FashionMen has been successfully activated and configured with essential pages and settings.', 'fashionmen'); ?></p>
            </div>

            <!-- Setup Status -->
            <div class="card" style="margin: 20px 0; padding: 20px; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-radius: 8px;">
                <h2><?php esc_html_e('🔧 Theme Setup Status', 'fashionmen'); ?></h2>

                <?php if ($pages_created && !empty($created_page_ids)) : ?>
                    <div style="background: #d1fae5; border-left: 4px solid #10b981; padding: 15px; margin: 15px 0; border-radius: 4px;">
                        <h3 style="margin-top: 0; color: #065f46;"><?php esc_html_e('✅ Pages Created', 'fashionmen'); ?></h3>
                        <p><?php esc_html_e('The following pages have been automatically created:', 'fashionmen'); ?></p>
                        <ul>
                            <?php foreach ($created_page_ids as $title => $page_id) : ?>
                                <li>
                                    <strong><?php echo esc_html($title); ?></strong> -
                                    <a href="<?php echo esc_url(get_permalink($page_id)); ?>" target="_blank"><?php esc_html_e('View', 'fashionmen'); ?></a> |
                                    <a href="<?php echo esc_url(get_edit_post_link($page_id)); ?>"><?php esc_html_e('Edit', 'fashionmen'); ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div style="background: #dbeafe; border-left: 4px solid #3b82f6; padding: 15px; margin: 15px 0; border-radius: 4px;">
                        <h3 style="margin-top: 0; color: #1e40af;"><?php esc_html_e('✅ Navigation Menus', 'fashionmen'); ?></h3>
                        <p><?php esc_html_e('Primary and Footer menus have been created and assigned.', 'fashionmen'); ?></p>
                        <a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>" class="button"><?php esc_html_e('Manage Menus', 'fashionmen'); ?></a>
                    </div>
                <?php else : ?>
                    <div style="background: #fee2e2; border-left: 4px solid #ef4444; padding: 15px; margin: 15px 0; border-radius: 4px;">
                        <h3 style="margin-top: 0; color: #991b1b;"><?php esc_html_e('⚠️ Pages Not Created', 'fashionmen'); ?></h3>
                        <p><?php esc_html_e('The automatic page creation did not run. This can happen if:', 'fashionmen'); ?></p>
                        <ul>
                            <li><?php esc_html_e('Pages with the same name already exist', 'fashionmen'); ?></li>
                            <li><?php esc_html_e('The theme was previously activated', 'fashionmen'); ?></li>
                            <li><?php esc_html_e('There was an error during activation', 'fashionmen'); ?></li>
                        </ul>
                        <p><strong><?php esc_html_e('Click the button below to create pages manually:', 'fashionmen'); ?></strong></p>
                        <form method="post" action="" style="margin-top: 15px;">
                            <?php wp_nonce_field('fashionmen_setup', 'fashionmen_setup_nonce'); ?>
                            <button type="submit" name="fashionmen_create_pages" class="button button-primary button-hero">
                                <?php esc_html_e('🚀 Create Pages & Menus Now', 'fashionmen'); ?>
                            </button>
                        </form>
                    </div>
                <?php endif; ?>

                <!-- Manual Recreate Option -->
                <?php if ($pages_created) : ?>
                    <div style="margin-top: 20px; padding: 15px; background: #f9fafb; border-radius: 4px;">
                        <p style="margin: 0; color: #6b7280;">
                            <strong><?php esc_html_e('Need to recreate pages?', 'fashionmen'); ?></strong>
                            <?php esc_html_e('This will delete the setup flag and allow you to create pages again (existing pages won\'t be deleted).', 'fashionmen'); ?>
                        </p>
                        <form method="post" action="" style="margin-top: 10px;">
                            <?php wp_nonce_field('fashionmen_setup', 'fashionmen_setup_nonce'); ?>
                            <button type="submit" name="fashionmen_create_pages" class="button" onclick="return confirm('<?php esc_attr_e('This will reset the setup and create pages. Continue?', 'fashionmen'); ?>');">
                                <?php esc_html_e('Recreate Pages & Menus', 'fashionmen'); ?>
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Next Steps -->
            <div class="card" style="margin: 20px 0; padding: 20px; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-radius: 8px;">
                <h2><?php esc_html_e('📋 Next Steps', 'fashionmen'); ?></h2>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">

                    <!-- WooCommerce Setup -->
                    <?php if (class_exists('WooCommerce')) : ?>
                        <div style="border: 1px solid #e5e7eb; padding: 20px; border-radius: 8px;">
                            <h3 style="margin-top: 0;">🛍️ <?php esc_html_e('WooCommerce', 'fashionmen'); ?></h3>
                            <p><?php esc_html_e('Configure your store settings and add products.', 'fashionmen'); ?></p>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=wc-admin')); ?>" class="button button-primary"><?php esc_html_e('WooCommerce Settings', 'fashionmen'); ?></a>
                        </div>
                    <?php else : ?>
                        <div style="border: 1px solid #e5e7eb; padding: 20px; border-radius: 8px; background: #fef3c7;">
                            <h3 style="margin-top: 0;">🛍️ <?php esc_html_e('Install WooCommerce', 'fashionmen'); ?></h3>
                            <p><?php esc_html_e('This theme is designed for WooCommerce. Install it to unlock all features.', 'fashionmen'); ?></p>
                            <a href="<?php echo esc_url(admin_url('plugin-install.php?s=woocommerce&tab=search&type=term')); ?>" class="button button-primary"><?php esc_html_e('Install WooCommerce', 'fashionmen'); ?></a>
                        </div>
                    <?php endif; ?>

                    <!-- Customize -->
                    <div style="border: 1px solid #e5e7eb; padding: 20px; border-radius: 8px;">
                        <h3 style="margin-top: 0;">🎨 <?php esc_html_e('Customize', 'fashionmen'); ?></h3>
                        <p><?php esc_html_e('Customize colors, fonts, and layout options.', 'fashionmen'); ?></p>
                        <a href="<?php echo esc_url(admin_url('customize.php')); ?>" class="button button-primary"><?php esc_html_e('Customize Theme', 'fashionmen'); ?></a>
                    </div>

                    <!-- Add Content -->
                    <div style="border: 1px solid #e5e7eb; padding: 20px; border-radius: 8px;">
                        <h3 style="margin-top: 0;">✍️ <?php esc_html_e('Add Content', 'fashionmen'); ?></h3>
                        <p><?php esc_html_e('Edit the default pages and add your own content.', 'fashionmen'); ?></p>
                        <a href="<?php echo esc_url(admin_url('edit.php?post_type=page')); ?>" class="button button-primary"><?php esc_html_e('Manage Pages', 'fashionmen'); ?></a>
                    </div>

                    <!-- Upload Logo -->
                    <div style="border: 1px solid #e5e7eb; padding: 20px; border-radius: 8px;">
                        <h3 style="margin-top: 0;">🖼️ <?php esc_html_e('Upload Logo', 'fashionmen'); ?></h3>
                        <p><?php esc_html_e('Add your brand logo to the header.', 'fashionmen'); ?></p>
                        <a href="<?php echo esc_url(admin_url('customize.php?autofocus[control]=custom_logo')); ?>" class="button button-primary"><?php esc_html_e('Upload Logo', 'fashionmen'); ?></a>
                    </div>

                    <!-- Add Hero Image -->
                    <div style="border: 1px solid #e5e7eb; padding: 20px; border-radius: 8px;">
                        <h3 style="margin-top: 0;">🏞️ <?php esc_html_e('Hero Image', 'fashionmen'); ?></h3>
                        <p><?php esc_html_e('Set a hero image for your homepage.', 'fashionmen'); ?></p>
                        <a href="<?php echo esc_url(admin_url('customize.php')); ?>" class="button button-primary"><?php esc_html_e('Set Hero Image', 'fashionmen'); ?></a>
                    </div>

                    <!-- Documentation -->
                    <div style="border: 1px solid #e5e7eb; padding: 20px; border-radius: 8px;">
                        <h3 style="margin-top: 0;">📚 <?php esc_html_e('Documentation', 'fashionmen'); ?></h3>
                        <p><?php esc_html_e('Learn more about theme features and customization.', 'fashionmen'); ?></p>
                        <a href="<?php echo esc_url(home_url()); ?>" class="button" target="_blank"><?php esc_html_e('View Site', 'fashionmen'); ?></a>
                    </div>

                </div>
            </div>

            <!-- Theme Features -->
            <div class="card" style="margin: 20px 0; padding: 20px; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-radius: 8px;">
                <h2><?php esc_html_e('✨ Theme Features', 'fashionmen'); ?></h2>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-top: 20px;">
                    <div style="padding: 15px; border-left: 3px solid #030213;">
                        <strong><?php esc_html_e('Figma Design Match', 'fashionmen'); ?></strong>
                        <p style="margin: 5px 0 0; color: #6b7280;"><?php esc_html_e('Professional design matching Figma specifications', 'fashionmen'); ?></p>
                    </div>
                    <div style="padding: 15px; border-left: 3px solid #030213;">
                        <strong><?php esc_html_e('WooCommerce Ready', 'fashionmen'); ?></strong>
                        <p style="margin: 5px 0 0; color: #6b7280;"><?php esc_html_e('Full e-commerce support with custom product cards', 'fashionmen'); ?></p>
                    </div>
                    <div style="padding: 15px; border-left: 3px solid #030213;">
                        <strong><?php esc_html_e('Responsive Design', 'fashionmen'); ?></strong>
                        <p style="margin: 5px 0 0; color: #6b7280;"><?php esc_html_e('Mobile-first, works perfectly on all devices', 'fashionmen'); ?></p>
                    </div>
                    <div style="padding: 15px; border-left: 3px solid #030213;">
                        <strong><?php esc_html_e('Tailwind CSS', 'fashionmen'); ?></strong>
                        <p style="margin: 5px 0 0; color: #6b7280;"><?php esc_html_e('Modern utility-first CSS framework', 'fashionmen'); ?></p>
                    </div>
                    <div style="padding: 15px; border-left: 3px solid #030213;">
                        <strong><?php esc_html_e('Auto Setup', 'fashionmen'); ?></strong>
                        <p style="margin: 5px 0 0; color: #6b7280;"><?php esc_html_e('Pages and menus created automatically', 'fashionmen'); ?></p>
                    </div>
                    <div style="padding: 15px; border-left: 3px solid #030213;">
                        <strong><?php esc_html_e('Custom Templates', 'fashionmen'); ?></strong>
                        <p style="margin: 5px 0 0; color: #6b7280;"><?php esc_html_e('About, Contact, FAQ page templates', 'fashionmen'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Support -->
            <div class="card" style="margin: 20px 0; padding: 20px; background: #f9fafb; border-radius: 8px;">
                <h2><?php esc_html_e('💡 Need Help?', 'fashionmen'); ?></h2>
                <p><?php esc_html_e('If you need assistance with the theme, here are some resources:', 'fashionmen'); ?></p>
                <ul>
                    <li><?php esc_html_e('Check the WordPress Codex for general WordPress help', 'fashionmen'); ?></li>
                    <li><?php esc_html_e('Visit WooCommerce documentation for e-commerce features', 'fashionmen'); ?></li>
                    <li><?php esc_html_e('Contact your theme developer for specific customizations', 'fashionmen'); ?></li>
                </ul>
            </div>

        </div>
    </div>

    <style>
        .fashionmen-setup-page h2 {
            font-size: 24px;
            margin-bottom: 15px;
        }
        .fashionmen-setup-page h3 {
            font-size: 18px;
        }
        .fashionmen-setup-page .button {
            margin-top: 10px;
        }
        .fashionmen-setup-page ul {
            list-style: disc;
            padding-left: 20px;
        }
        .fashionmen-setup-page ul li {
            margin: 8px 0;
        }
    </style>
    <?php
}
