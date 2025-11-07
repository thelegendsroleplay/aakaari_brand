<?php
/**
 * The Template for displaying product archives (Shop Page)
 * Converted from Figma Design (fig/src/pages/products)
 */

defined( 'ABSPATH' ) || exit;

get_header();

// Get page title based on current archive
$page_title = woocommerce_page_title( false );
$page_description = '';

if ( is_product_category() ) {
    $category = get_queried_object();
    $page_description = $category->description ?: 'Browse our collection of premium products';
} elseif ( is_shop() ) {
    $page_description = 'Essential items for your wardrobe';
}

?>

<div class="products-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8" style="max-width: 1280px; margin: 0 auto; padding: 2rem 1rem;">
            <h1 class="text-3xl" style="font-size: 1.875rem; font-weight: 600; margin: 0 0 0.5rem; color: #000;">
                <?php echo esc_html( $page_title ); ?>
            </h1>
            <?php if ( $page_description ) : ?>
                <p class="text-gray-600 mt-2" style="font-size: 1rem; color: #666; margin: 0;">
                    <?php echo esc_html( $page_description ); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <div class="products-container" style="padding: 0 1rem 2rem;">
        <!-- Sidebar Filters -->
        <aside class="filters-sidebar" id="filters-sidebar">
            <div class="filters-header">
                <h2>Filters</h2>
                <a href="<?php echo esc_url( remove_query_arg( array( 'product_cat', 'min_price', 'max_price', 'pa_size', 'pa_color', 'rating_filter' ) ) ); ?>"
                   style="font-size: 0.875rem; color: #666; text-decoration: none; padding: 0.25rem 0.5rem; border-radius: 4px; transition: background 0.2s;">
                    Clear All
                </a>
            </div>

            <?php
            // Get product categories
            $product_categories = get_terms( array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => true,
            ) );

            if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) :
            ?>
            <!-- Categories -->
            <div class="filter-section">
                <h3>Categories</h3>
                <div class="filter-options">
                    <?php foreach ( $product_categories as $category ) :
                        if ( $category->slug === 'uncategorized' ) continue;
                        $current_cat = is_product_category( $category->slug );
                    ?>
                        <label class="filter-checkbox">
                            <input type="checkbox" <?php checked( $current_cat ); ?>
                                   onchange="window.location.href='<?php echo esc_url( get_term_link( $category ) ); ?>'"
                                   style="width: 16px; height: 16px; border-radius: 4px; border: 1px solid #d1d5db; cursor: pointer;">
                            <span><?php echo esc_html( $category->name ); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Price Range -->
            <div class="filter-section">
                <h3>Price Range</h3>
                <div class="price-range">
                    <form method="get">
                        <?php
                        // Preserve other query vars
                        foreach ( $_GET as $key => $value ) {
                            if ( ! in_array( $key, array( 'min_price', 'max_price' ) ) ) {
                                echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '">';
                            }
                        }

                        $min_price = isset( $_GET['min_price'] ) ? absint( $_GET['min_price'] ) : 0;
                        $max_price = isset( $_GET['max_price'] ) ? absint( $_GET['max_price'] ) : 1000;
                        ?>
                        <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <input type="number" name="min_price" value="<?php echo esc_attr( $min_price ); ?>"
                                   placeholder="Min" min="0"
                                   style="flex: 1; padding: 0.5rem; border: 1px solid #e5e7eb; border-radius: 4px; font-size: 0.875rem;">
                            <span style="align-self: center;">-</span>
                            <input type="number" name="max_price" value="<?php echo esc_attr( $max_price ); ?>"
                                   placeholder="Max" min="0"
                                   style="flex: 1; padding: 0.5rem; border: 1px solid #e5e7eb; border-radius: 4px; font-size: 0.875rem;">
                        </div>
                        <div class="price-labels">
                            <span>$<?php echo esc_html( $min_price ); ?></span>
                            <span>$<?php echo esc_html( $max_price ); ?></span>
                        </div>
                        <button type="submit"
                                style="width: 100%; padding: 0.5rem; margin-top: 0.5rem; background: #000; color: #fff; border: none; border-radius: 4px; font-size: 0.875rem; cursor: pointer;">
                            Apply
                        </button>
                    </form>
                </div>
            </div>

            <?php
            // Size attribute filter
            $sizes = wc_get_attribute_taxonomy_names();
            if ( in_array( 'pa_size', $sizes ) ) :
                $size_terms = get_terms( array(
                    'taxonomy'   => 'pa_size',
                    'hide_empty' => true,
                ) );

                if ( ! empty( $size_terms ) && ! is_wp_error( $size_terms ) ) :
            ?>
            <!-- Sizes -->
            <div class="filter-section">
                <h3>Sizes</h3>
                <div class="filter-options">
                    <?php foreach ( $size_terms as $size ) :
                        $is_filtered = isset( $_GET['filter_pa_size'] ) && $_GET['filter_pa_size'] === $size->slug;
                    ?>
                        <label class="filter-checkbox">
                            <input type="checkbox" <?php checked( $is_filtered ); ?>
                                   onchange="location.href='<?php echo esc_url( add_query_arg( 'filter_pa_size', $size->slug ) ); ?>'"
                                   style="width: 16px; height: 16px; border-radius: 4px; border: 1px solid #d1d5db; cursor: pointer;">
                            <span><?php echo esc_html( $size->name ); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php
                endif;
            endif;
            ?>
        </aside>

        <!-- Main Content -->
        <main class="products-main">
            <!-- Toolbar -->
            <div class="products-toolbar">
                <button class="filter-toggle-btn lg:hidden" onclick="toggleFilters()"
                        style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: transparent; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 0.875rem; cursor: pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="4" y1="21" x2="4" y2="14"></line>
                        <line x1="4" y1="10" x2="4" y2="3"></line>
                        <line x1="12" y1="21" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12" y2="3"></line>
                        <line x1="20" y1="21" x2="20" y2="16"></line>
                        <line x1="20" y1="12" x2="20" y2="3"></line>
                    </svg>
                    <span id="filter-toggle-text">Show</span> Filters
                </button>

                <div class="toolbar-info">
                    <?php
                    $total = $GLOBALS['wp_query']->found_posts;
                    ?>
                    <p><?php echo esc_html( $total ); ?> Product<?php echo $total !== 1 ? 's' : ''; ?></p>
                </div>

                <div class="toolbar-sort">
                    <span class="sort-label">Sort by:</span>
                    <form method="get" onchange="this.submit()">
                        <?php
                        // Preserve other query vars
                        foreach ( $_GET as $key => $value ) {
                            if ( $key !== 'orderby' ) {
                                echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '">';
                            }
                        }

                        $current_orderby = isset( $_GET['orderby'] ) ? $_GET['orderby'] : 'popularity';
                        ?>
                        <select name="orderby"
                                style="padding: 0.5rem 2rem 0.5rem 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 0.875rem; background: white; cursor: pointer; min-width: 200px;">
                            <option value="popularity" <?php selected( $current_orderby, 'popularity' ); ?>>Popularity</option>
                            <option value="price" <?php selected( $current_orderby, 'price' ); ?>>Price: Low to High</option>
                            <option value="price-desc" <?php selected( $current_orderby, 'price-desc' ); ?>>Price: High to Low</option>
                            <option value="date" <?php selected( $current_orderby, 'date' ); ?>>Newest</option>
                            <option value="rating" <?php selected( $current_orderby, 'rating' ); ?>>Rating</option>
                        </select>
                    </form>
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
                    <p>Try adjusting your filters</p>
                    <a href="<?php echo esc_url( remove_query_arg( array( 'product_cat', 'min_price', 'max_price', 'pa_size', 'pa_color', 'rating_filter' ) ) ); ?>"
                       style="display: inline-block; margin-top: 1rem; padding: 0.5rem 1rem; background: #000; color: #fff; text-decoration: none; border-radius: 6px; font-size: 0.875rem;">
                        Clear Filters
                    </a>
                </div>
                <?php
            }
            ?>
        </main>
    </div>
</div>

<script>
function toggleFilters() {
    const sidebar = document.getElementById('filters-sidebar');
    const toggleText = document.getElementById('filter-toggle-text');

    if (sidebar.classList.contains('hide')) {
        sidebar.classList.remove('hide');
        sidebar.classList.add('show');
        toggleText.textContent = 'Hide';
    } else {
        sidebar.classList.add('hide');
        sidebar.classList.remove('show');
        toggleText.textContent = 'Show';
    }
}

// Mobile responsive - hide filters by default on mobile
if (window.innerWidth < 1024) {
    const sidebar = document.getElementById('filters-sidebar');
    sidebar.classList.add('hide');
}

// Show filters on desktop
window.addEventListener('resize', function() {
    const sidebar = document.getElementById('filters-sidebar');
    if (window.innerWidth >= 1024) {
        sidebar.classList.remove('hide');
        sidebar.classList.remove('show');
    }
});
</script>

<?php
get_footer();
