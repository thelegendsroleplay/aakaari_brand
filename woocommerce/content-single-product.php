<?php
/**
 * content-single-product.php
 * Place this inside your theme (e.g. wp-content/themes/your-theme/woocommerce/content-single-product.php)
 *
 * This tries to pull data from WooCommerce if available. If WooCommerce isn't active,
 * the JS will use the JSON fallback printed below (which contains your mock data).
 *
 * NOTE: For production, enqueue styles/scripts from functions.php; this file uses direct links
 * that assume assets are in "assets/css" and "assets/js" inside your theme.
 */

$theme_url = get_stylesheet_directory_uri();
?>
<link rel="stylesheet" href="<?php echo esc_url( $theme_url . '/assets/css/product-detail.css' ); ?>">

<!-- Product Detail Page - WooCommerce Integration -->
<div class="product-page">
  <div class="product-container">
    <button id="back-btn" class="back-link">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
      Back to Products
    </button>

    <div class="product-layout">
      <div class="product-images">
        <div class="main-image-wrapper">
          <img src="" alt="Main product image" id="main-image">
          <span class="discount-badge" id="discount-badge"></span>
        </div>
        <div class="thumbnail-list" id="thumbnail-list"></div>
      </div>

      <div class="product-info">
        <div class="info-header">
          <h1 id="product-name"></h1>
          <button class="wishlist-icon" id="wishlist-btn">
            <svg id="wishlist-icon-svg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
          </button>
        </div>

        <div class="rating_row rating-row">
          <div class="stars" id="product-rating-stars"></div>
          <span class="rating-text" id="product-rating-text"></span>
        </div>

        <div class="price-row">
          <span class="price" id="product-price"></span>
          <span class="old-price" id="product-old-price"></span>
        </div>

        <p class="description" id="product-description"></p>

        <div class="options-section">
          <div class="option-row">
            <label>Size:</label>
            <div class="option-btns" id="size-options"></div>
          </div>

          <div class="option-row">
            <label>Color:</label>
            <div class="color-btns" id="color-options"></div>
          </div>
        </div>

        <div class="quantity-row">
          <label>Quantity:</label>
          <div class="quantity-box">
            <button id="qty-decrease">-</button>
            <span id="quantity-display">1</span>
            <button id="qty-increase">+</button>
          </div>
          <span class="stock-text" id="stock-info"></span>
        </div>

        <div class="action-row">
          <button class="add-cart-btn" id="add-to-cart-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
            Add to Cart
          </button>
          <button class="buy-btn" id="buy-now-btn">Buy Now</button>
        </div>

        <div class="features-row">
          <div class="feature">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
            <span>Free shipping over $100</span>
          </div>
          <div class="feature">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"></polyline><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path></svg>
            <span>30-day returns</span>
          </div>
        </div>

        <div class="product-meta">
          <div><span>SKU:</span> <span id="product-sku"></span></div>
          <div><span>Category:</span> <span id="product-category"></span></div>
        </div>
      </div>
    </div>

    <div class="reviews-section" id="reviews-section">
      <h2>Customer Reviews</h2>
      <div class="reviews-list" id="reviews-list"></div>
    </div>

    <div class="related-section" id="related-section">
      <h2>Related Products</h2>
      <div class="related-products-grid" id="related-products-grid"></div>
    </div>
  </div>
</div>

<?php
/**
 * If WooCommerce is active we'll print a JSON blob with server data (images, price, etc).
 * Otherwise we print nothing and the JS will use the mock fallback.
 */
if ( function_exists( 'wc_get_product' ) ) {
  global $product;
  if ( ! $product ) {
    $product = wc_get_product( get_the_ID() );
  }

  if ( $product ) {
    // gather images
    $images = array();
    $featured_id = $product->get_image_id();
    if ( $featured_id ) {
      $images[] = wp_get_attachment_image_url( $featured_id, 'large' );
    }
    $gallery_ids = $product->get_gallery_image_ids();
    if ( $gallery_ids ) {
      foreach ( $gallery_ids as $gid ) {
        $images[] = wp_get_attachment_image_url( $gid, 'large' );
      }
    }

    // related
    $related = array();
    $related_ids = wc_get_related_products( $product->get_id(), 4 );
    foreach ( $related_ids as $rid ) {
      $rp = wc_get_product( $rid );
      if ( $rp ) {
        $related[] = array(
          'id' => $rp->get_id(),
          'name' => $rp->get_name(),
          'price' => (float) $rp->get_price(),
          'image' => wp_get_attachment_image_url( $rp->get_image_id(), 'medium' ),
          'permalink' => get_permalink( $rp->get_id() ),
        );
      }
    }

    $data = array(
      'id' => $product->get_id(),
      'name' => $product->get_name(),
      'price' => (float) $product->get_price(),
      'salePrice' => $product->is_on_sale() ? (float) $product->get_sale_price() : null,
      'description' => wp_strip_all_tags( $product->get_description() ),
      'images' => $images,
      'sizes' => array(), // unless using attributes - left empty
      'colors' => array(), // unless using attributes - left empty
      'rating' => (float) wc_get_rating_html( $product->get_average_rating() ) ? (float) $product->get_average_rating() : 0,
      'reviewCount' => (int) $product->get_review_count(),
      'stock' => $product->get_stock_quantity() ?: ( $product->is_in_stock() ? 999 : 0 ),
      'sku' => $product->get_sku(),
      'category' => wp_get_post_terms( $product->get_id(), 'product_cat', array( 'fields' => 'names' ) ),
      'related' => $related
    );

    // Print JSON blob that JS will read (id product-data)
    echo '<script id="product-data" type="application/json">' . wp_json_encode( $data ) . '</script>';
  }
}
?>

<!-- Include JS at end of file. Adjust path if you store JS elsewhere -->
<script src="<?php echo esc_url( $theme_url . '/assets/js/single-product.js' ); ?>"></script>
