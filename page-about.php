<?php
/**
 * Template Name: About Page
 * Description: About Us page template
 *
 * @package FashionMen
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();
?>

<div class="static-page">
    <div class="static-container">
        <!-- Hero Section -->
        <div class="static-hero">
            <h1 class="static-hero-title">About Our Brand</h1>
            <p class="static-hero-subtitle">Crafting premium men's fashion since 2010. We believe in quality, style, and sustainability.</p>
        </div>

        <!-- Main Content -->
        <div class="static-content">
            <!-- Our Story -->
            <section class="about-story">
                <h2>Our Story</h2>
                <p>Founded in 2010, we started with a simple mission: to create timeless, high-quality men's fashion that combines classic style with modern sensibility. What began as a small workshop has grown into a global brand trusted by thousands of customers worldwide.</p>
                <p>Every piece we create is a testament to our commitment to excellence. From the initial design concept to the final stitch, we obsess over every detail to ensure that our products meet the highest standards of quality and craftsmanship.</p>
            </section>

            <!-- Stats -->
            <div class="about-stats">
                <div class="about-stat">
                    <div class="about-stat-value">15+</div>
                    <div class="about-stat-label">Years in Business</div>
                </div>
                <div class="about-stat">
                    <div class="about-stat-value">50K+</div>
                    <div class="about-stat-label">Happy Customers</div>
                </div>
                <div class="about-stat">
                    <div class="about-stat-value">100+</div>
                    <div class="about-stat-label">Products</div>
                </div>
                <div class="about-stat">
                    <div class="about-stat-value">25</div>
                    <div class="about-stat-label">Countries Served</div>
                </div>
            </div>

            <!-- Our Mission -->
            <section class="about-mission">
                <h2>Our Mission</h2>
                <p>To empower men to look and feel their best through exceptional quality, timeless design, and sustainable practices. We believe that great style should be accessible, and great fashion should be responsible.</p>
            </section>

            <!-- Our Values -->
            <section class="about-values">
                <h2>Our Values</h2>
                <div class="values-grid">
                    <div class="value-card">
                        <div class="value-icon">🎯</div>
                        <h3 class="value-title">Quality First</h3>
                        <p class="value-description">We use only the finest materials and craftsmanship to ensure every piece meets our high standards.</p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon">🌍</div>
                        <h3 class="value-title">Sustainable</h3>
                        <p class="value-description">Committed to ethical sourcing and environmentally responsible manufacturing practices.</p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon">💡</div>
                        <h3 class="value-title">Innovation</h3>
                        <p class="value-description">Constantly evolving our designs while staying true to timeless style principles.</p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon">❤️</div>
                        <h3 class="value-title">Customer Focused</h3>
                        <p class="value-description">Your satisfaction is our priority. We stand behind every product we sell.</p>
                    </div>
                </div>
            </section>

            <!-- Our Team -->
            <section class="about-team">
                <h2>Meet Our Team</h2>
                <div class="team-grid">
                    <div class="team-member">
                        <img src="https://images.unsplash.com/photo-1714328564923-d4826427c991?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwZm9ybWFsJTIwd2VhcnxlbnwxfHx8fDE3NjIzMjQwNDZ8MA&ixlib=rb-4.1.0&q=80&w=1080" alt="Michael Chen" class="team-member-photo" />
                        <h3 class="team-member-name">Michael Chen</h3>
                        <p class="team-member-role">Founder & CEO</p>
                        <p class="team-member-bio">15 years of fashion industry experience</p>
                    </div>
                    <div class="team-member">
                        <img src="https://images.unsplash.com/photo-1656786779124-3eb10b7014a5?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwY2FzdWFsJTIwZmFzaGlvbnxlbnwxfHx8fDE3NjIzMjM1OTJ8MA&ixlib=rb-4.1.0&q=80&w=1080" alt="Sarah Johnson" class="team-member-photo" />
                        <h3 class="team-member-name">Sarah Johnson</h3>
                        <p class="team-member-role">Creative Director</p>
                        <p class="team-member-bio">Award-winning designer with global recognition</p>
                    </div>
                    <div class="team-member">
                        <img src="https://images.unsplash.com/photo-1635650805015-2fa50682873a?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwc3RyZWV0d2VhcnxlbnwxfHx8fDE3NjI0MTQxMDN8MA&ixlib=rb-4.1.0&q=80&w=1080" alt="David Martinez" class="team-member-photo" />
                        <h3 class="team-member-name">David Martinez</h3>
                        <p class="team-member-role">Head of Production</p>
                        <p class="team-member-bio">Expert in sustainable manufacturing</p>
                    </div>
                </div>
            </section>
        </div>

        <!-- CTA Section -->
        <div class="static-cta">
            <h2>Ready to Upgrade Your Wardrobe?</h2>
            <p>Discover our latest collection of premium men's fashion.</p>
            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="cta-button">Shop Now</a>
        </div>
    </div>
</div>

<?php
get_footer();
