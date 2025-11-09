/**
 * Theme Customizer Live Preview
 * Aakaari Brand Theme
 */

(function($) {
    'use strict';

    // Hero Tag
    wp.customize('aakaari_hero_tag', function(value) {
        value.bind(function(newval) {
            $('.hero-tag').text(newval);
        });
    });

    // Hero Title
    wp.customize('aakaari_hero_title', function(value) {
        value.bind(function(newval) {
            $('.hero-main-title').text(newval);
        });
    });

    // Hero Subtitle
    wp.customize('aakaari_hero_subtitle', function(value) {
        value.bind(function(newval) {
            $('.hero-main-subtitle').text(newval);
        });
    });

    // Hero Image
    wp.customize('aakaari_hero_image', function(value) {
        value.bind(function(newval) {
            $('.hero-banner-image').attr('src', newval);
        });
    });

    // Promo Badge
    wp.customize('aakaari_promo_badge', function(value) {
        value.bind(function(newval) {
            $('.promo-badge').text(newval);
        });
    });

    // Promo Title
    wp.customize('aakaari_promo_title', function(value) {
        value.bind(function(newval) {
            $('.promo-title').text(newval);
        });
    });

    // Promo Description
    wp.customize('aakaari_promo_description', function(value) {
        value.bind(function(newval) {
            $('.promo-description').text(newval);
        });
    });

    // Promo Image
    wp.customize('aakaari_promo_image', function(value) {
        value.bind(function(newval) {
            $('.promo-image').attr('src', newval);
        });
    });

})(jQuery);
