/**
 * Header Mobile Menu Toggle
 */
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = document.querySelector('.menu-icon');
    const closeIcon = document.querySelector('.close-icon');

    if (mobileToggle && mobileMenu) {
        mobileToggle.addEventListener('click', function() {
            const isOpen = mobileMenu.style.display === 'block';

            if (isOpen) {
                mobileMenu.style.display = 'none';
                menuIcon.style.display = 'block';
                closeIcon.style.display = 'none';
            } else {
                mobileMenu.style.display = 'block';
                menuIcon.style.display = 'none';
                closeIcon.style.display = 'block';
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!mobileToggle.contains(event.target) && !mobileMenu.contains(event.target)) {
                mobileMenu.style.display = 'none';
                menuIcon.style.display = 'block';
                closeIcon.style.display = 'none';
            }
        });

        // Close mobile menu when resizing to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                mobileMenu.style.display = 'none';
                menuIcon.style.display = 'block';
                closeIcon.style.display = 'none';
            }
        });
    }

    // AJAX cart count update (if WooCommerce AJAX is available)
    if (typeof jQuery !== 'undefined') {
        jQuery(document.body).on('added_to_cart removed_from_cart updated_cart_totals', function() {
            // Update cart count via AJAX
            jQuery.ajax({
                url: wc_add_to_cart_params ? wc_add_to_cart_params.ajax_url : '/wp-admin/admin-ajax.php',
                type: 'POST',
                data: {
                    action: 'get_cart_count'
                },
                success: function(response) {
                    if (response.success && response.data) {
                        const cartBadge = document.querySelector('.header-badge.cart-count');
                        if (cartBadge) {
                            cartBadge.textContent = response.data;
                            cartBadge.style.display = response.data > 0 ? 'flex' : 'none';
                        }
                    }
                }
            });
        });
    }
});
