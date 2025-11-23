<?php
/**
 * Template Name: Home Page
 * Template Post Type: page
 *
 * @package Aakaari_Brand
 */

get_header();
?>

<main id="main" class="site-main">
    <div class="home-page">

        <section class="hero-slider-section">
            <div class="hero-slider-container" id="heroSliderContainer">
                
                <div class="hero-slide active hero-slide--grind">
                    <?php
                    // Get hero image from Customizer
                    $hero_image_1 = get_theme_mod(
                        'aakaari_hero_slide_1_image',
                        'https://your-site.com/wp-content/uploads/hero-herrenn-1600x600.jpg' // fallback URL
                    );
                    ?>
                    
                    <div class="hero-slide-background hero-slide-background--image"
                         style="background-image: url('<?php echo esc_url( $hero_image_1 ); ?>');">
                    </div>

                    <div class="hero-slide-overlay">
                        <div class="hero-corner-badge animate-slide-up">
                            <?php echo esc_html(get_theme_mod('aakaari_hero_slide_1_tag', 'FRESH ARRIVAL')); ?>
                        </div>

                        <div class="hero-slide-content">
                            <h1 class="hero-slide-title animate-slide-up">
                                <?php echo esc_html(get_theme_mod('aakaari_hero_slide_1_title', 'Built for the Grind')); ?>
                            </h1>

                            <p class="hero-slide-subtitle animate-slide-up">
                                <?php echo esc_html(get_theme_mod('aakaari_hero_slide_1_subtitle', 'Streetwear that moves with you')); ?>
                            </p>

                            <div class="hero-slide-actions animate-slide-up">
                                <a href="<?php echo esc_url(get_theme_mod('aakaari_hero_slide_1_button_link', get_permalink(wc_get_page_id('shop')))); ?>"
                                   class="hero-cta-primary">
                                    <?php echo esc_html(get_theme_mod('aakaari_hero_slide_1_button_text', 'SHOP NOW')); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="hero-slide hero-slide--culture">
                    <?php
                    // FIX: Define the variable before using it
                    $hero_image_2 = get_theme_mod(
                        'aakaari_hero_slide_2_image',
                        'https://your-site.com/wp-content/uploads/hero-culture.jpg' // fallback URL
                    );
                    ?>
            
                    <div class="hero-slide-background hero-slide-background--image"
                         style="background-image: url('<?php echo esc_url( $hero_image_2 ); ?>');">
                    </div>

                    <div class="hero-slide-overlay">
                        
                        <div class="hero-corner-badge animate-slide-up">
                            <?php echo esc_html(get_theme_mod('aakaari_hero_slide_2_tag', 'NEW DROP')); ?>
                        </div>

                        <div class="hero-slide-content">
                            <h1 class="hero-slide-title animate-slide-up">
                                <?php echo wp_kses_post(get_theme_mod('aakaari_hero_slide_2_title', 'STREET CULTURE<br>REDEFINED')); ?>
                            </h1>

                            <p class="hero-slide-subtitle animate-slide-up">
                                <?php echo esc_html(get_theme_mod('aakaari_hero_slide_2_subtitle', 'Premium quality meets authentic style.')); ?>
                            </p>

                            <div class="hero-slide-actions animate-slide-up">
                                <a href="<?php echo esc_url(get_theme_mod('aakaari_hero_slide_2_btn_link', wc_get_page_id('shop'))); ?>" 
                                   class="hero-cta-primary">
                                    <?php echo esc_html(get_theme_mod('aakaari_hero_slide_2_btn_text', 'EXPLORE NOW')); ?>
                                </a>

                                <a href="<?php echo esc_url(get_theme_mod('aakaari_hero_slide_2_btn2_link', '#')); ?>" 
                                   class="hero-cta-secondary">
                                    <?php echo esc_html(get_theme_mod('aakaari_hero_slide_2_btn2_text', 'LIMITED EDITION')); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hero-slider-dots">
                    <button class="hero-dot active" data-slide="0" aria-label="Go to slide 1"></button>
                    <button class="hero-dot" data-slide="1" aria-label="Go to slide 2"></button>
                </div>
            </div>
        </section>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sliderContainer = document.getElementById('heroSliderContainer');
            if (!sliderContainer) return;

            const slides = sliderContainer.querySelectorAll('.hero-slide');
            const dots = sliderContainer.querySelectorAll('.hero-dot');
            const slideInterval = 6000; // Change slide every 6 seconds
            let currentSlide = 0;
            let autoSlideTimer;

            // Function to switch to a specific slide
            function goToSlide(n) {
                slides[currentSlide].classList.remove('active');
                dots[currentSlide].classList.remove('active');
                
                currentSlide = (n + slides.length) % slides.length; // Ensure index wraps around
                
                slides[currentSlide].classList.add('active');
                dots[currentSlide].classList.add('active');
            }

            // Function to go to next slide automatically
            function nextSlide() {
                goToSlide(currentSlide + 1);
            }

            // Event listeners for dots
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    clearInterval(autoSlideTimer); // Stop auto-play on manual interaction
                    goToSlide(index);
                    autoSlideTimer = setInterval(nextSlide, slideInterval); // Restart auto-play
                });
            });

            // Start automatic sliding
            autoSlideTimer = setInterval(nextSlide, slideInterval);
        });
        </script>

<section class="category-section">
    <div class="page-container">
        <h2 class="category-section-title">Shop by Category</h2>
        <div class="category-grid">
            <?php
            // Get product categories
            $categories = get_terms(array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => false,
                'number'     => 2,
                'orderby'    => 'count',
                'order'      => 'DESC',
            ));

            if (!empty($categories) && !is_wp_error($categories)) :
                foreach ($categories as $category) :
                    $slug = $category->slug;

                    // Custom images for specific categories
                    if ($slug === 't-shirts' || $slug === 't-shirt' || $slug === 'tshirts-men') {
                        // T-shirt category image
                        $image_url = 'https://herrenn.com/wp-content/uploads/2025/11/ChatGPT-Image-Nov-23-2025-08_05_26-PM.png';
                    } elseif ($slug === 'hoodies' || $slug === 'hoodie') {
                        // Hoodies category image
                        $image_url = 'https://herrenn.com/wp-content/uploads/2025/11/8936b3a3-dd76-4aa9-b4cf-2b8699823291.png';
                    } else {
                        // Fallback: use WooCommerce category thumbnail or a default
                        $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                        $image_url = $thumbnail_id 
                            ? wp_get_attachment_url($thumbnail_id) 
                            : 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&q=80';
                    }

                    $category_link = get_term_link($category);
                    ?>
                    <div class="category-card" onclick="window.location.href='<?php echo esc_url($category_link); ?>'">
                        <img src="<?php echo esc_url($image_url); ?>"
                             alt="<?php echo esc_attr($category->name); ?>"
                             class="category-card-image" />
                        <div class="category-card-overlay">
                            <h3 class="category-card-title"><?php echo esc_html($category->name); ?></h3>
                            <p class="category-card-subtitle">Explore Collection</p>
                        </div>
                    </div>
                    <?php
                endforeach;
            endif;
            ?>
        </div>
    </div>
</section>


        <section class="products-section">
            <div class="page-container">
                <div class="section-title-wrapper">
                    <h2 class="section-main-title">Featured Products</h2>
                    <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="section-view-link">
                        View All
                    </a>
                </div>

                <div class="product-carousel-wrapper">
                    <button class="carousel-nav-button prev" data-carousel-nav="featured" data-direction="prev">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </button>
                    <button class="carousel-nav-button next" data-carousel-nav="featured" data-direction="next">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>
                    <div class="product-carousel" data-carousel="featured">
                        <?php
                        $featured_ids = wc_get_featured_product_ids();

                        if (!empty($featured_ids)) :
                            $featured_args = array(
                                'post_type'      => 'product',
                                'posts_per_page' => 8,
                                'post__in'       => $featured_ids,
                                'orderby'        => 'post__in',
                            );

                            $featured_products = new WP_Query($featured_args);

                            if ($featured_products->have_posts()) :
                                while ($featured_products->have_posts()) : $featured_products->the_post();
                                    wc_get_template_part('content', 'product-card');
                                endwhile;
                                wp_reset_postdata();
                            endif;
                        else :
                            ?>
                            <div style="text-align: center; padding: 3rem 1rem; color: #666;">
                                <p>No featured products yet. Please mark some products as featured in the admin panel.</p>
                            </div>
                            <?php
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </section>

        <section class="promo-section">
            <div class="page-container">
                <div class="promo-card">
                    <div class="promo-content">
                        <div class="promo-badge"><?php echo esc_html(get_theme_mod('aakaari_promo_badge', 'Premium')); ?></div>
                        <h2 class="promo-title"><?php echo esc_html(get_theme_mod('aakaari_promo_title', 'Crafted for Excellence')); ?></h2>
                        <p class="promo-description">
                            <?php echo esc_html(get_theme_mod('aakaari_promo_description', 'Every piece is thoughtfully designed and made with premium materials. Experience comfort that lasts, style that stands out.')); ?>
                        </p>

                        <div class="promo-features">
                            <div class="promo-feature-item">
                                <div class="promo-feature-icon">✓</div>
                                <span>100% Premium Cotton</span>
                            </div>
                            <div class="promo-feature-item">
                                <div class="promo-feature-icon">✓</div>
                                <span>Sustainable Production</span>
                            </div>
                            <div class="promo-feature-item">
                                <div class="promo-feature-icon">✓</div>
                                <span>Lifetime Quality Guarantee</span>
                            </div>
                        </div>

                        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="promo-button">
                            Explore Collection
                        </a>
                    </div>
                    <div class="promo-image-wrapper">
                        <?php
                        $promo_image = get_theme_mod('aakaari_promo_image', 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=600&q=80');
                        ?>
                        <img src="<?php echo esc_url($promo_image); ?>"
                             alt="Premium Collection"
                             class="promo-image" />
                        <div class="promo-image-overlay">
                            <div class="promo-quality-badge">
                                <span class="quality-badge-label">Premium Quality</span>
                                <span class="quality-badge-subtitle">Since 2024</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="products-section">
            <div class="page-container">
                <div class="section-title-wrapper">
                    <h2 class="section-main-title">New Arrivals</h2>
                    <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="section-view-link">
                        View All
                    </a>
                </div>

                <div class="product-carousel-wrapper">
                     <button class="carousel-nav-button prev" data-carousel-nav="new-arrivals" data-direction="prev">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </button>
                    <button class="carousel-nav-button next" data-carousel-nav="new-arrivals" data-direction="next">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>
                    <div class="product-carousel" data-carousel="new-arrivals">
                        <?php
                        $new_arrivals_args = array(
                            'post_type'      => 'product',
                            'posts_per_page' => 8,
                            'orderby'        => 'date',
                            'order'          => 'DESC',
                        );

                        $new_arrivals = new WP_Query($new_arrivals_args);

                        if ($new_arrivals->have_posts()) :
                            while ($new_arrivals->have_posts()) : $new_arrivals->the_post();
                                wc_get_template_part('content', 'product-card');
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </section>

        <section class="trust-section">
            <div class="page-container">
                <div class="trust-grid">
                    <div class="trust-item">
                        <div class="trust-icon-box">
                            <svg class="trust-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="3" width="15" height="13"></rect>
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                <circle cx="18.5" cy="18.5" r="2.5"></circle>
                            </svg>
                        </div>
                        <div class="trust-text">
                            <h4 class="trust-title">Free Shipping</h4>
                            <p class="trust-desc">On orders over ₹499</p>
                        </div>
                    </div>

                    <div class="trust-item">
                        <div class="trust-icon-box">
                            <svg class="trust-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                        </div>
                        <div class="trust-text">
                            <h4 class="trust-title">Secure Payment</h4>
                            <p class="trust-desc">100% protected</p>
                        </div>
                    </div>

                    <div class="trust-item">
                        <div class="trust-icon-box">
                            <svg class="trust-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="1 4 1 10 7 10"></polyline>
                                <polyline points="23 20 23 14 17 14"></polyline>
                                <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path>
                            </svg>
                        </div>
                        <div class="trust-text">
                            <h4 class="trust-title">Easy Returns</h4>
                            <p class="trust-desc">7 Days Exchange policy</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <div class="quick-view-modal" id="quickViewModal">
        <div class="quick-view-content">
            <button class="quick-view-close" id="quickViewClose">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
            <div id="quickViewContent">
                <div class="quick-view-loading">
                    <div class="quick-view-loading-spinner"></div>
                    <p>Loading product details...</p>
                </div>
            </div>
        </div>
    </div>

</main>

<?php
get_footer();