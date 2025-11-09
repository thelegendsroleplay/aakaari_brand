<?php
/**
 * inc/shop.php
 * Outputs the shop page markup and registers AJAX handler.
 *
 * Handles AJAX filtering for products page
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ------------------------
   Helper: Get attribute taxonomy
   ------------------------ */
function aakaari_get_attribute_taxonomy( $type ) {
  $taxonomies = array();

  if ( $type === 'size' ) {
    $taxonomies = array( 'pa_size', 'pa_sizes' );
  } elseif ( $type === 'color' ) {
    $taxonomies = array( 'pa_color', 'pa_colors', 'pa_colour' );
  }

  foreach ( $taxonomies as $taxonomy ) {
    if ( taxonomy_exists( $taxonomy ) ) {
      return $taxonomy;
    }
  }

  return false;
}

/* ------------------------
   AJAX: filter products
   ------------------------ */
add_action( 'wp_ajax_nopriv_aakaari_filter_products', 'aakaari_filter_products_ajax' );
add_action( 'wp_ajax_aakaari_filter_products', 'aakaari_filter_products_ajax' );

function aakaari_filter_products_ajax() {
  check_ajax_referer( 'aakaari_ajax_nonce', 'nonce' );

  $filters_json = isset( $_POST['filters'] ) ? wp_unslash( $_POST['filters'] ) : '{}';
  $filters = json_decode( $filters_json, true );
  if ( ! is_array( $filters ) ) $filters = array();

  $paged = 1;
  $args = array(
    'status' => 'publish',
    'limit'  => 48,
    'orderby'=> 'date',
    'order'  => 'DESC',
  );

  // categories
  if ( ! empty( $filters['categories'] ) ) {
    $cats = array_map( 'sanitize_text_field', $filters['categories'] );
    $args['category'] = $cats;
  }

  // price range
  $min = isset( $filters['priceMin'] ) ? floatval( $filters['priceMin'] ) : 0;
  $max = isset( $filters['priceMax'] ) ? floatval( $filters['priceMax'] ) : 100000;

  // sizes and colors are attribute filters
  $tax_query = array();
  if ( ! empty( $filters['sizes'] ) ) {
    $size_taxonomy = aakaari_get_attribute_taxonomy( 'size' );
    if ( $size_taxonomy ) {
      $sizes = array_map( 'sanitize_text_field', $filters['sizes'] );
      $tax_query[] = array(
        'taxonomy' => $size_taxonomy,
        'field'    => 'slug',
        'terms'    => $sizes,
      );
    }
  }
  if ( ! empty( $filters['colors'] ) ) {
    $color_taxonomy = aakaari_get_attribute_taxonomy( 'color' );
    if ( $color_taxonomy ) {
      $colors = array_map( 'sanitize_text_field', $filters['colors'] );
      $tax_query[] = array(
        'taxonomy' => $color_taxonomy,
        'field'    => 'slug',
        'terms'    => $colors,
      );
    }
  }
  if ( ! empty( $tax_query ) ) {
    $args['tax_query'] = $tax_query;
  }

  // rating
  $rating_filter = ! empty( $filters['rating'] ) ? intval( $filters['rating'] ) : 0;

  // sorting
  if ( ! empty( $filters['sortBy'] ) ) {
    switch ( $filters['sortBy'] ) {
      case 'price-low':
        $args['orderby'] = 'price';
        $args['order']   = 'ASC';
        break;
      case 'price-high':
        $args['orderby'] = 'price';
        $args['order']   = 'DESC';
        break;
      case 'rating':
        $args['orderby'] = 'rating';
        $args['order']   = 'DESC';
        break;
      case 'newest':
        $args['orderby'] = 'date';
        $args['order']   = 'DESC';
        break;
      default:
        $args['orderby'] = 'popularity';
        $args['order']   = 'DESC';
    }
  }

  // Fetch products
  $products = wc_get_products( $args );

  // apply price filtering manually
  if ( $min || $max ) {
    $products = array_filter( $products, function( $p ) use ( $min, $max ) {
      $price = (float) $p->get_price();
      if ( $min && $price < $min ) return false;
      if ( $max && $price > $max ) return false;
      return true;
    } );
  }

  // rating filter (post-filter)
  if ( $rating_filter ) {
    $products = array_filter( $products, function( $p ) use ( $rating_filter ) {
      $avg = floatval( $p->get_average_rating() );
      return floor( $avg ) >= $rating_filter;
    } );
  }

  // build HTML
  ob_start();
  if ( ! empty( $products ) ) {
    foreach ( $products as $product ) {
      echo aakaari_render_product_card( $product );
    }
  } else {
    echo '<div class="no-products">
      <div style="font-size:48px;color:#e6e9ee;margin-bottom:12px;">🛒</div>
      <h3>No products found</h3>
      <p>Try adjusting your filters</p>
    </div>';
  }
  $html = ob_get_clean();

  wp_send_json_success( array( 'html' => $html, 'count' => count( $products ) ) );
}

/* ------------------------
   product card renderer
   ------------------------ */
function aakaari_render_product_card( $product ) {
  // $product is a WC_Product object
  $id = $product->get_id();
  $title = esc_html( $product->get_name() );
  $permalink = esc_url( $product->get_permalink() );
  $regular_price = $product->get_regular_price();
  $sale_price = $product->get_sale_price();

  // Get images
  $image_id = $product->get_image_id();
  $gallery_ids = $product->get_gallery_image_ids();
  $hover_image_id = ! empty( $gallery_ids ) ? $gallery_ids[0] : $image_id;

  // Get category
  $categories = get_the_terms( $id, 'product_cat' );
  $category_name = '';
  if ( $categories && ! is_wp_error( $categories ) ) {
    $category = array_shift( $categories );
    $category_name = $category->name;
  }

  // Calculate discount
  $discount = 0;
  if ( $product->is_on_sale() && $regular_price && $sale_price ) {
    $discount = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
  }

  // Check if new (within 30 days)
  $product_date = get_the_date( 'U', $id );
  $is_new = ( ( time() - $product_date ) / ( 60 * 60 * 24 ) ) < 30;
  $is_featured = $product->is_featured();

  // Get rating
  $rating = $product->get_average_rating();
  $review_count = $product->get_review_count();

  ob_start();
  ?>
  <article class="product-card" data-product-url="<?php echo esc_attr( $permalink ); ?>">
    <!-- Image -->
    <div class="product-card-image product-media">
      <?php if ( $image_id ) : ?>
        <?php echo wp_get_attachment_image( $image_id, 'woocommerce_thumbnail', false, array( 'class' => 'default-image', 'alt' => $title ) ); ?>
        <?php if ( $hover_image_id !== $image_id ) : ?>
          <?php echo wp_get_attachment_image( $hover_image_id, 'woocommerce_thumbnail', false, array( 'class' => 'hover-image', 'alt' => $title, 'style' => 'display:none;' ) ); ?>
        <?php endif; ?>
      <?php endif; ?>

      <!-- Badges -->
      <div class="product-badges">
        <?php if ( $discount > 0 ) : ?>
          <span class="product-badge badge-sale">-<?php echo esc_html( $discount ); ?>%</span>
        <?php endif; ?>
        <?php if ( $is_new ) : ?>
          <span class="product-badge badge-new">New</span>
        <?php endif; ?>
        <?php if ( $is_featured ) : ?>
          <span class="product-badge badge-featured">Featured</span>
        <?php endif; ?>
      </div>

      <!-- Wishlist Button -->
      <button class="product-wishlist-btn" data-product-id="<?php echo esc_attr( $id ); ?>" aria-label="Add to wishlist">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
        </svg>
      </button>

      <!-- Add to Cart Overlay -->
      <?php if ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) : ?>
        <div class="product-cart-overlay">
          <button type="button" class="product-add-to-cart-btn ajax_add_to_cart add-btn" data-id="<?php echo esc_attr( $id ); ?>" data-product_type="<?php echo esc_attr( $product->get_type() ); ?>" aria-label="<?php echo esc_attr( 'Add ' . $title . ' to cart' ); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="8" cy="21" r="1"></circle>
              <circle cx="19" cy="21" r="1"></circle>
              <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
            </svg>
            Add to Cart
          </button>
        </div>
      <?php endif; ?>
    </div>

    <!-- Details -->
    <div class="product-card-content">
      <?php if ( $category_name ) : ?>
        <p class="product-category"><?php echo esc_html( $category_name ); ?></p>
      <?php endif; ?>
      <h3 class="product-card-title product-title">
        <a href="<?php echo $permalink; ?>">
          <?php echo $title; ?>
        </a>
      </h3>
      <div class="product-rating">
        <div class="product-stars">
          <?php
          for ( $i = 1; $i <= 5; $i++ ) {
            if ( $i <= floor( $rating ) ) {
              echo '<svg class="star-filled" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
            } else {
              echo '<svg class="star-empty" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
            }
          }
          ?>
        </div>
        <span class="product-review-count">(<?php echo esc_html( $review_count ); ?>)</span>
      </div>
      <div class="product-price price"><?php echo $product->get_price_html(); ?></div>
    </div>
  </article>
  <?php
  return ob_get_clean();
}

/* ------------------------
   initial markup output
   ------------------------ */
function aakaari_shop_markup(){
  // Get page title and description based on context
  $page_title = 'Shop';
  $page_desc = 'Essential tees for your wardrobe';

  if ( is_shop() ) {
    $page_title = woocommerce_page_title( false );
    $page_desc = 'Discover our collection of premium streetwear';
  } elseif ( is_product_category() ) {
    $term = get_queried_object();
    $page_title = $term->name;
    $page_desc = $term->description ?: 'Browse our ' . strtolower( $term->name ) . ' collection';
  } elseif ( is_product_tag() ) {
    $term = get_queried_object();
    $page_title = $term->name;
    $page_desc = $term->description ?: '';
  }

  ?>
  <div class="products-page">
    <div class="page-header">
      <div class="inner">
        <h1 id="page-title"><?php echo esc_html( $page_title ); ?></h1>
        <p id="page-desc"><?php echo esc_html( $page_desc ); ?></p>
      </div>
    </div>

    <div class="products-container">
      <aside id="filters" class="filters-sidebar">
        <div class="filters-header">
          <h2>Filters</h2>
          <button id="clear-filters" class="btn">Clear All</button>
        </div>

        <div class="filter-section">
          <h3>Categories</h3>
          <div class="filter-options" id="categories-list">
            <?php
            // show product categories
            $terms = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true, 'exclude' => get_option( 'default_product_cat' ) ) );
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
              foreach ( $terms as $t ) {
                if ( $t->slug === 'uncategorized' ) continue;
                $slug = esc_attr( $t->slug );
                $name = esc_html( $t->name );
                echo "<label class=\"filter-checkbox\"><input data-category=\"{$slug}\" type=\"checkbox\"> <span>{$name}</span></label>";
              }
            }
            ?>
          </div>
        </div>

        <div class="filter-section">
          <h3>Price Range</h3>
          <div class="price-range">
            <div class="ranges">
              <input id="price-min" type="range" min="0" max="2000" step="10" value="0" />
              <input id="price-max" type="range" min="0" max="2000" step="10" value="2000" />
            </div>
            <div class="price-labels"><span id="price-min-label">$0</span><span id="price-max-label">$2000</span></div>
          </div>
        </div>

        <div class="filter-section">
          <h3>Sizes</h3>
          <div class="filter-options" id="sizes-list">
            <?php
            $size_taxonomy = aakaari_get_attribute_taxonomy( 'size' );
            if ( $size_taxonomy ) {
              $terms = get_terms( array( 'taxonomy' => $size_taxonomy, 'hide_empty' => false ) );
              if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                foreach ( $terms as $t ) {
                  echo "<label class=\"filter-checkbox\"><input data-size=\"" . esc_attr( $t->slug ) . "\" type=\"checkbox\"> <span>" . esc_html( $t->name ) . "</span></label>";
                }
              } else {
                echo '<p style="font-size:0.75rem;color:var(--muted);">No sizes found. Add sizes in Products → Attributes → Size</p>';
              }
            } else {
              echo '<p style="font-size:0.75rem;color:var(--muted);">Size attribute not found. Create "Size" attribute in Products → Attributes</p>';
            }
            ?>
          </div>
        </div>

        <div class="filter-section">
          <h3>Colors</h3>
          <div class="color-options" id="colors-list">
            <?php
            $color_taxonomy = aakaari_get_attribute_taxonomy( 'color' );
            if ( $color_taxonomy ) {
              $color_terms = get_terms( array( 'taxonomy' => $color_taxonomy, 'hide_empty' => false ) );
              if ( ! empty( $color_terms ) && ! is_wp_error( $color_terms ) ) {
                foreach ( $color_terms as $color ) {
                  $slug = esc_attr( $color->slug );
                  $name = esc_html( $color->name );
                  // Get actual color from meta (set via color picker)
                  $color_hex = get_term_meta( $color->term_id, 'attribute_color', true );
                  if ( ! $color_hex ) {
                    // Fallback: use color name as CSS color
                    $color_hex = strtolower( $color->name );
                  }
                  echo '<button type="button" class="color-swatch" data-color="'.$slug.'" title="'.$name.'" style="background:'.esc_attr($color_hex).'"></button>';
                }
              } else {
                echo '<p style="font-size:0.75rem;color:var(--muted);">No colors found. Add colors in Products → Attributes → Color</p>';
              }
            } else {
              echo '<p style="font-size:0.75rem;color:var(--muted);">Color attribute not found. Create "Color" attribute in Products → Attributes</p>';
            }
            ?>
          </div>
        </div>

        <div class="filter-section">
          <h3>Minimum Rating</h3>
          <div class="filter-options" id="rating-list">
            <?php foreach ( array(4,3,2,1) as $r ) : ?>
              <label class="filter-checkbox"><input data-rating="<?php echo esc_attr($r); ?>" type="checkbox" /> <span><?php echo esc_html($r); ?>+ Stars</span></label>
            <?php endforeach; ?>
          </div>
        </div>
      </aside>

      <main class="products-main">
        <div class="products-toolbar">
          <button id="toggle-filters" class="btn"><span id="filters-toggle-text">Hide</span> Filters</button>
          <div class="toolbar-info"><p id="products-count">0 Products</p></div>
          <div class="toolbar-sort">
            <span class="sort-label">Sort by:</span>
            <select id="sort-by">
              <option value="popularity">Popularity</option>
              <option value="price-low">Price: Low to High</option>
              <option value="price-high">Price: High to Low</option>
              <option value="newest">Newest</option>
              <option value="rating">Rating</option>
            </select>
          </div>
        </div>

        <div id="products-grid" class="products-grid" aria-live="polite"></div>

        <div id="no-products" class="no-products hidden">
          <div style="font-size:48px;color:#e6e9ee;margin-bottom:12px;">🛒</div>
          <h3>No products found</h3>
          <p>Try adjusting your filters</p>
          <div style="margin-top:14px;"><button id="clear-filters-2" class="btn">Clear Filters</button></div>
        </div>
      </main>
    </div>
  </div>
  <?php
}
