<?php
/**
 * The Header for the Aakaari Brand Theme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <header id="masthead" class="site-header">
        <div class="site-branding">
            <?php the_custom_logo(); ?>
            <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
            <p class="site-description"><?php bloginfo( 'description' ); ?></p>
        </div><nav id="site-navigation" class="main-navigation">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary', // Matches the name 'primary' in your functions.php
                'menu_id'        => 'primary-menu',
                'container'      => false,
            ) );
            ?>
        </nav></header><div id="content" class="site-content">
        
</body>
</html>