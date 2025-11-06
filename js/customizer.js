/**
 * Theme Customizer enhancements for a better user experience
 *
 * @package Aakaari_Brand
 */

(function($) {
    'use strict';

    // Site title
    wp.customize('blogname', function(value) {
        value.bind(function(to) {
            $('.site-title a').text(to);
        });
    });

    // Site description
    wp.customize('blogdescription', function(value) {
        value.bind(function(to) {
            $('.site-description').text(to);
        });
    });

    // Primary color
    wp.customize('aakaari_brand_primary_color', function(value) {
        value.bind(function(to) {
            $('a, .button, button, input[type="submit"], .woocommerce ul.products li.product .price').css('color', to);
        });
    });

    // Secondary color
    wp.customize('aakaari_brand_secondary_color', function(value) {
        value.bind(function(to) {
            $('.button, button, input[type="submit"], input[type="button"], .main-navigation, .site-footer').css('background-color', to);
        });
    });

    // Container width
    wp.customize('aakaari_brand_container_width', function(value) {
        value.bind(function(to) {
            $('.site-container').css('max-width', to + 'px');
        });
    });

    // Font size
    wp.customize('aakaari_brand_font_size', function(value) {
        value.bind(function(to) {
            $('body').css('font-size', to + 'px');
        });
    });

    // Show/hide site title
    wp.customize('aakaari_brand_show_site_title', function(value) {
        value.bind(function(to) {
            if (to) {
                $('.site-title').show();
            } else {
                $('.site-title').hide();
            }
        });
    });

    // Show/hide tagline
    wp.customize('aakaari_brand_show_tagline', function(value) {
        value.bind(function(to) {
            if (to) {
                $('.site-description').show();
            } else {
                $('.site-description').hide();
            }
        });
    });

})(jQuery);
