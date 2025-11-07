<?php
/**
 * The Template for displaying product archives - Products Page (Figma Design)
 */

defined( 'ABSPATH' ) || exit;

get_header();

?>
<div class="products-page">
    <!-- Page Header -->
    <div class="page-header" style="background: #fff; border-bottom: 1px solid #f1f1f1;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 2rem 1rem;">
            <h1 style="font-size: 2rem; font-weight: 600; margin: 0 0 0.5rem;">
                <?php woocommerce_page_title(); ?>
            </h1>
            <?php
            /**
             * Hook: woocommerce_archive_description.
             */
            do_action( 'woocommerce_archive_description' );
            ?>
        </div>
    </div>

    <div class="products-container">
        <!-- Sidebar Filters -->
        <aside class="filters-sidebar" id="filters-sidebar">
            <div class="filters-header">
                <h2>Filters</h2>
                <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="filter-clear-btn">Clear All</a>
            </div>

            <?php if ( is_active_sidebar( 'shop-sidebar' ) ) : ?>
                <?php dynamic_sidebar( 'shop-sidebar' ); ?>
            <?php else : ?>
                <!-- Categories -->
                <?php
                $product_categories = get_terms( array(
                    'taxonomy'   => 'product_cat',
                    'hide_empty' => true,
                ) );

                if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) :
                ?>
                <div class="filter-section">
                    <h3>Categories</h3>
                    <div class="filter-options">
                        <?php foreach ( $product_categories as $category ) :
                            if ( $category->slug === 'uncategorized' ) continue;
                        ?>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="product_cat[]" value="<?php echo esc_attr( $category->slug ); ?>">
                                <span><?php echo esc_html( $category->name ); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Price Range -->
                <div class="filter-section">
                    <h3>Price Range</h3>
                    <?php
                    $min_price = isset( $_GET['min_price'] ) ? absint( $_GET['min_price'] ) : 0;
                    $max_price = isset( $_GET['max_price'] ) ? absint( $_GET['max_price'] ) : 1000;
                    ?>
                    <form method="get" class="price-range">
                        <div class="price-inputs">
                            <input type="number" name="min_price" value="<?php echo esc_attr( $min_price ); ?>" placeholder="Min" min="0">
                            <span>-</span>
                            <input type="number" name="max_price" value="<?php echo esc_attr( $max_price ); ?>" placeholder="Max" min="0">
                        </div>
                        <button type="submit" class="price-filter-btn">Apply</button>
                    </form>
                </div>
            <?php endif; ?>
        </aside>

        <!-- Main Content -->
        <main class="products-main">
            <!-- Toolbar -->
            <div class="products-toolbar">
                <button class="filter-toggle-btn" onclick="toggleFilters()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="4" y1="21" x2="4" y2="14"></line>
                        <line x1="4" y1="10" x2="4" y2="3"></line>
                        <line x1="12" y1="21" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12" y2="3"></line>
                    </svg>
                    <span id="filter-toggle-text">Show</span> Filters
                </button>

                <div class="toolbar-info">
                    <?php
                    /**
                     * Hook: woocommerce_before_shop_loop.
                     */
                    do_action( 'woocommerce_before_shop_loop' );
                    ?>
                </div>
            </div>

            <!-- Products Grid -->
            <?php
            if ( woocommerce_product_loop() ) {
                ?>
                <div class="products-grid woocommerce-products-grid">
                    <?php
                    if ( wc_get_loop_prop( 'total' ) ) {
                        while ( have_posts() ) {
                            the_post();
                            do_action( 'woocommerce_shop_loop' );
                            wc_get_template_part( 'content', 'product' );
                        }
                    }
                    ?>
                </div>
                <?php
                /**
                 * Hook: woocommerce_after_shop_loop.
                 */
                do_action( 'woocommerce_after_shop_loop' );
            } else {
                ?>
                <div class="no-products">
                    <svg style="width: 64px; height: 64px; color: #d1d5db; margin-bottom: 1rem;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                    </svg>
                    <h3>No products found</h3>
                    <p>Try adjusting your filters or search term</p>
                </div>
                <?php
                do_action( 'woocommerce_no_products_found' );
            }
            ?>
        </main>
    </div>
</div>

<script>
function toggleFilters() {
    const sidebar = document.getElementById('filters-sidebar');
    const toggleText = document.getElementById('filter-toggle-text');
    if (sidebar.style.display === 'none' || sidebar.classList.contains('hide')) {
        sidebar.style.display = 'block';
        sidebar.classList.remove('hide');
        toggleText.textContent = 'Hide';
    } else {
        sidebar.style.display = 'none';
        sidebar.classList.add('hide');
        toggleText.textContent = 'Show';
    }
}

// Mobile responsive - hide filters by default on mobile
if (window.innerWidth < 1024) {
    const sidebar = document.getElementById('filters-sidebar');
    sidebar.style.display = 'none';
    sidebar.classList.add('hide');
}
</script>

<?php
get_footer();
