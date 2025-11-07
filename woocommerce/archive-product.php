<?php
/**
 * The Template for displaying product archives (Shop Page)
 * Matches Figma Design exactly: fig/src/pages/products/ProductsPage.tsx
 */

defined( 'ABSPATH' ) || exit;

get_header();

// Get page title and description based on current view
$page_title = woocommerce_page_title( false );
$page_description = '';

if ( is_product_category() ) {
    $category = get_queried_object();
    $page_description = $category->description;
    if ( ! $page_description ) {
        // Default descriptions based on category slug
        if ( $category->slug === 'hoodies' ) {
            $page_description = 'Cozy hoodies for every season';
        } elseif ( $category->slug === 't-shirts' ) {
            $page_description = 'Essential tees for your wardrobe';
        } else {
            $page_description = 'Browse our collection of premium products';
        }
    }
} elseif ( is_shop() ) {
    $page_description = 'Essential items for your wardrobe';
}

// Get current filters
$current_category = is_product_category() ? get_queried_object()->term_id : 0;
$min_price = isset( $_GET['min_price'] ) ? absint( $_GET['min_price'] ) : 0;
$max_price = isset( $_GET['max_price'] ) ? absint( $_GET['max_price'] ) : 1000;
$current_sizes = isset( $_GET['filter_size'] ) ? explode( ',', sanitize_text_field( $_GET['filter_size'] ) ) : array();
$current_colors = isset( $_GET['filter_color'] ) ? explode( ',', sanitize_text_field( $_GET['filter_color'] ) ) : array();
$current_rating = isset( $_GET['rating_filter'] ) ? absint( $_GET['rating_filter'] ) : 0;
$current_orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'popularity';

?>

<div class="products-page">
    <!-- Page Header -->
    <div class="page-header">
        <h1><?php echo esc_html( $page_title ); ?></h1>
        <?php if ( $page_description ) : ?>
            <p><?php echo esc_html( $page_description ); ?></p>
        <?php endif; ?>
    </div>

    <div class="products-container">
        <!-- Filters Sidebar -->
        <aside class="filters-sidebar" id="filters-sidebar">
            <div class="filters-header">
                <h2>Filters</h2>
                <a href="<?php echo esc_url( strtok( $_SERVER['REQUEST_URI'], '?' ) ); ?>" style="font-size: 0.875rem; color: #666; text-decoration: none;">
                    Clear All
                </a>
            </div>

            <?php
            // Categories Filter
            $product_categories = get_terms( array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => true,
                'exclude'    => get_option( 'default_product_cat' ),
            ) );

            if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) :
            ?>
            <div class="filter-section">
                <h3>Categories</h3>
                <div class="filter-options">
                    <?php foreach ( $product_categories as $category ) :
                        if ( $category->slug === 'uncategorized' ) continue;
                        $is_current = $current_category === $category->term_id;
                    ?>
                        <label class="filter-checkbox">
                            <input type="checkbox" <?php checked( $is_current ); ?>
                                   onchange="window.location.href='<?php echo esc_url( get_term_link( $category ) ); ?>'">
                            <span><?php echo esc_html( $category->name ); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Price Range Filter -->
            <div class="filter-section">
                <h3>Price Range</h3>
                <div class="price-range">
                    <!-- Price Slider Component (using WooCommerce widget or custom) -->
                    <?php the_widget( 'WC_Widget_Price_Filter' ); ?>
                    <div class="price-labels">
                        <span>$<?php echo esc_html( $min_price ); ?></span>
                        <span>$<?php echo esc_html( $max_price ); ?></span>
                    </div>
                </div>
            </div>

            <?php
            // Sizes Filter
            $size_terms = get_terms( array(
                'taxonomy'   => 'pa_size',
                'hide_empty' => true,
            ) );

            if ( ! empty( $size_terms ) && ! is_wp_error( $size_terms ) ) :
            ?>
            <div class="filter-section">
                <h3>Sizes</h3>
                <div class="filter-options">
                    <?php foreach ( $size_terms as $size ) :
                        $is_selected = in_array( $size->slug, $current_sizes );
                    ?>
                        <label class="filter-checkbox">
                            <input type="checkbox" value="<?php echo esc_attr( $size->slug ); ?>"
                                   <?php checked( $is_selected ); ?>
                                   onchange="toggleFilter('size', '<?php echo esc_js( $size->slug ); ?>')">
                            <span><?php echo esc_html( $size->name ); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php
            // Colors Filter
            $color_terms = get_terms( array(
                'taxonomy'   => 'pa_color',
                'hide_empty' => true,
            ) );

            if ( ! empty( $color_terms ) && ! is_wp_error( $color_terms ) ) :
            ?>
            <div class="filter-section">
                <h3>Colors</h3>
                <div class="color-options">
                    <?php foreach ( $color_terms as $color ) :
                        $is_selected = in_array( $color->slug, $current_colors );
                        // Get color hex from term meta or use default
                        $color_hex = get_term_meta( $color->term_id, 'color', true );
                        if ( ! $color_hex ) {
                            // Fallback: use color name as CSS color
                            $color_hex = strtolower( $color->name );
                        }
                    ?>
                        <button class="color-swatch <?php echo $is_selected ? 'selected' : ''; ?>"
                                style="background-color: <?php echo esc_attr( $color_hex ); ?>"
                                onclick="toggleFilter('color', '<?php echo esc_js( $color->slug ); ?>')"
                                title="<?php echo esc_attr( $color->name ); ?>">
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Rating Filter -->
            <div class="filter-section">
                <h3>Minimum Rating</h3>
                <div class="rating-options">
                    <?php foreach ( array( 4, 3, 2, 1 ) as $rating ) : ?>
                        <label class="filter-checkbox">
                            <input type="checkbox" value="<?php echo esc_attr( $rating ); ?>"
                                   <?php checked( $current_rating === $rating ); ?>
                                   onchange="setRatingFilter(<?php echo esc_js( $rating ); ?>)">
                            <span><?php echo esc_html( $rating ); ?>+ Stars</span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="products-main">
            <!-- Toolbar -->
            <div class="products-toolbar">
                <button class="lg:hidden" onclick="toggleFilters()" style="display: none; padding: 0.5rem 1rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; background: white; cursor: pointer;" id="filter-toggle-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle;">
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
                    <?php $total = $GLOBALS['wp_query']->found_posts; ?>
                    <p><?php echo esc_html( $total ); ?> Products</p>
                </div>

                <div class="toolbar-sort">
                    <span class="sort-label">Sort by:</span>
                    <form method="get" onchange="this.submit()">
                        <?php
                        foreach ( $_GET as $key => $value ) {
                            if ( $key !== 'orderby' ) {
                                echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '">';
                            }
                        }
                        ?>
                        <select name="orderby" style="padding: 0.5rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; min-width: 180px;">
                            <option value="popularity" <?php selected( $current_orderby, 'popularity' ); ?>>Popularity</option>
                            <option value="price" <?php selected( $current_orderby, 'price' ); ?>>Price: Low to High</option>
                            <option value="price-desc" <?php selected( $current_orderby, 'price-desc' ); ?>>Price: High to Low</option>
                            <option value="date" <?php selected( $current_orderby, 'date' ); ?>>Newest</option>
                            <option value="rating" <?php selected( $current_orderby, 'rating' ); ?>>Rating</option>
                            <option value="sale" <?php selected( $current_orderby, 'sale' ); ?>>On Sale</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Products Grid -->
            <?php
            if ( woocommerce_product_loop() ) {
                ?>
                <div class="products-grid">
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
                do_action( 'woocommerce_after_shop_loop' );
            } else {
                ?>
                <div class="no-products">
                    <svg style="width: 64px; height: 64px; color: #d1d5db; margin: 0 auto 1rem;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                    </svg>
                    <h3>No products found</h3>
                    <p>Try adjusting your filters</p>
                    <a href="<?php echo esc_url( strtok( $_SERVER['REQUEST_URI'], '?' ) ); ?>" style="display: inline-block; margin-top: 1rem; padding: 0.5rem 1rem; background: #000; color: #fff; text-decoration: none; border-radius: 0.5rem;">
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
// Filter Toggle for Mobile
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

// Multi-select filters
function toggleFilter(type, value) {
    const params = new URLSearchParams(window.location.search);
    const paramName = 'filter_' + type;
    const current = params.get(paramName);
    let values = current ? current.split(',') : [];

    if (values.includes(value)) {
        values = values.filter(v => v !== value);
    } else {
        values.push(value);
    }

    if (values.length > 0) {
        params.set(paramName, values.join(','));
    } else {
        params.delete(paramName);
    }

    window.location.search = params.toString();
}

// Rating filter
function setRatingFilter(rating) {
    const params = new URLSearchParams(window.location.search);
    const current = params.get('rating_filter');

    if (current == rating) {
        params.delete('rating_filter');
    } else {
        params.set('rating_filter', rating);
    }

    window.location.search = params.toString();
}

// Mobile responsive
window.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('filters-sidebar');
    const toggleBtn = document.getElementById('filter-toggle-btn');

    function checkMobile() {
        if (window.innerWidth < 1024) {
            sidebar.classList.add('hide');
            toggleBtn.style.display = 'inline-flex';
        } else {
            sidebar.classList.remove('hide');
            sidebar.classList.remove('show');
            toggleBtn.style.display = 'none';
        }
    }

    checkMobile();
    window.addEventListener('resize', checkMobile);
});
</script>

<?php
get_footer();
