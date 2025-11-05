<?php
/**
 * The header for our theme
 *
 * @package FashionMen
 * @since 1.0.0
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'fashionmen'); ?></a>

    <header id="masthead" class="site-header sticky top-0 z-50 bg-white border-b border-gray-200">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-toggle" class="md:hidden p-2" aria-label="<?php esc_attr_e('Toggle mobile menu', 'fashionmen'); ?>">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <!-- Logo -->
                <div class="site-branding">
                    <?php if (has_custom_logo()) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="text-2xl tracking-wider">
                            <?php
                            $site_name = get_bloginfo('name');
                            if ($site_name) :
                                $name_parts = explode(' ', $site_name);
                                if (count($name_parts) > 1) :
                                    echo '<span>' . esc_html($name_parts[0]) . '</span>';
                                    echo '<span class="text-gray-400">' . esc_html($name_parts[1]) . '</span>';
                                else :
                                    echo esc_html($site_name);
                                endif;
                            endif;
                            ?>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Desktop Navigation -->
                <nav id="site-navigation" class="main-navigation hidden md:flex items-center gap-8" aria-label="<?php esc_attr_e('Primary Menu', 'fashionmen'); ?>">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'menu_class'     => 'flex items-center gap-8',
                        'fallback_cb'    => 'fashionmen_default_menu',
                    ));
                    ?>
                </nav>

                <!-- Right Icons (Search, Wishlist, Cart, User) -->
                <div class="header-actions flex items-center gap-4">
                    <!-- Search Icon -->
                    <button id="search-toggle" class="p-2 hover:text-gray-600 transition-colors" aria-label="<?php esc_attr_e('Search', 'fashionmen'); ?>">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>

                    <?php if (class_exists('WooCommerce')) : ?>
                        <!-- Wishlist Icon -->
                        <?php if (function_exists('YITH_WCWL')) : ?>
                            <a href="<?php echo esc_url(YITH_WCWL()->get_wishlist_url()); ?>" class="p-2 hover:text-gray-600 transition-colors" aria-label="<?php esc_attr_e('Wishlist', 'fashionmen'); ?>">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </a>
                        <?php endif; ?>

                        <!-- Cart Icon -->
                        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="p-2 hover:text-gray-600 transition-colors relative cart-icon-link" aria-label="<?php esc_attr_e('View cart', 'fashionmen'); ?>">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <?php
                            $cart_count = WC()->cart->get_cart_contents_count();
                            if ($cart_count > 0) :
                            ?>
                                <span class="cart-count absolute -top-1 -right-1 bg-black text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"><?php echo esc_html($cart_count); ?></span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>

                    <!-- User Icon -->
                    <?php if (is_user_logged_in()) : ?>
                        <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" class="p-2 hover:text-gray-600 transition-colors" aria-label="<?php esc_attr_e('My Account', 'fashionmen'); ?>">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </a>
                    <?php else : ?>
                        <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" class="p-2 hover:text-gray-600 transition-colors" aria-label="<?php esc_attr_e('Login', 'fashionmen'); ?>">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Search Modal -->
        <div id="search-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-start justify-center pt-20">
            <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-semibold"><?php esc_html_e('Search Products', 'fashionmen'); ?></h2>
                    <button id="search-close" class="text-gray-500 hover:text-gray-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <?php get_product_search_form(); ?>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <nav id="mobile-navigation" class="mobile-navigation hidden md:hidden fixed inset-0 bg-white z-40 overflow-y-auto" aria-label="<?php esc_attr_e('Mobile Menu', 'fashionmen'); ?>">
            <div class="p-4">
                <div class="flex justify-between items-center mb-8">
                    <span class="text-xl font-semibold"><?php esc_html_e('Menu', 'fashionmen'); ?></span>
                    <button id="mobile-menu-close" class="p-2">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'mobile',
                    'menu_id'        => 'mobile-menu',
                    'container'      => false,
                    'menu_class'     => 'mobile-menu-items',
                    'fallback_cb'    => 'fashionmen_default_menu',
                ));
                ?>
            </div>
        </nav>
    </header>

    <div id="content" class="site-content">
