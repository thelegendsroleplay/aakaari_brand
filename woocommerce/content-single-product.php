<?php
/**
 * Custom single product content — Aakaari (ATTRIBUTE FIX: Colors + size)
 *
 * Place this file at:
 *  - your-theme/woocommerce/content-single-product.php
 *
 * This version normalizes attributes so that:
 *  - color-like attributes map to label "Colors"
 *  - size-like attributes map to label "size"
 *
 */

defined( 'ABSPATH' ) || exit;

global $product, $post;

if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
    return;
}

$product_id   = $product->get_id();
$product_name = $product->get_name();
$sku          = $product->get_sku();
$category     = '';
$cats         = wp_get_post_terms( $product_id, 'product_cat' );
if ( ! is_wp_error( $cats ) && ! empty( $cats ) ) {
    $category = $cats[0]->name;
}

$gallery_ids = $product->get_gallery_image_ids();
$images      = array();
if ( has_post_thumbnail( $product_id ) ) {
    $images[] = wp_get_attachment_image_url( get_post_thumbnail_id( $product_id ), 'aakaari-product' );
}
foreach ( $gallery_ids as $img_id ) {
    $images[] = wp_get_attachment_image_url( $img_id, 'aakaari-product' );
}
if ( empty( $images ) ) {
    $images[] = wc_placeholder_img_src();
}

$regular_price = (float) $product->get_regular_price();
$sale_price    = $product->is_on_sale() ? (float) $product->get_sale_price() : 0.0;
$avg_rating    = (float) $product->get_average_rating();
$review_count  = (int) $product->get_rating_count();
$stock_qty     = $product->is_in_stock() ? wc_stock_amount( $product->get_stock_quantity() ) : 0;

/**
 * ATTRIBUTES: Build attributes_options and attribute_map.
 *
 * attributes_options: unique_key => [ { label: human, value: machine } ]
 * attribute_map: unique_key => input_name (attribute_pa_color or attribute_size etc)
 *
 * We normalize display labels for UI consistency but keep unique keys to avoid collisions.
 */
$attributes       = $product->get_attributes();
$attributes_meta  = array();
$attribute_map    = array();

foreach ( $attributes as $attr ) {
    $raw_name = $attr->get_name(); // e.g. pa_color or custom
    $label    = wc_attribute_label( $raw_name );

    // Normalize display label for UI consistency
    $display_label = $label;
    if ( stripos( $label, 'color' ) !== false ) {
        $display_label = 'Colors';
    } elseif ( stripos( $label, 'size' ) !== false ) {
        $display_label = 'size';
    }

    // Create unique key to avoid collisions when multiple attributes normalize to same label
    $unique_key = $display_label;
    if ( isset( $attributes_meta[ $unique_key ] ) ) {
        // Collision detected - make key unique by appending raw attribute name
        $unique_key = $display_label . '_' . sanitize_key( $raw_name );
    }

    if ( taxonomy_exists( $raw_name ) ) {
        $input_key = 'attribute_' . $raw_name; // e.g. attribute_pa_color
        $options = $attr->get_options();
        $opts = array();
        foreach ( $options as $opt ) {
            // $opt is slug for taxonomy attributes
            $term = get_term_by( 'slug', $opt, $raw_name );
            $label_text = $term ? $term->name : $opt;
            $opts[] = array( 'label' => $label_text, 'value' => $opt );
        }
    } else {
        // custom attribute - use raw name for input key
        $input_key = 'attribute_' . sanitize_title( $raw_name );
        $options = $attr->get_options();
        $opts = array();
        foreach ( $options as $opt ) {
            // Use raw value to match variation attributes (don't double-sanitize)
            $opts[] = array( 'label' => $opt, 'value' => (string) $opt );
        }
    }

    if ( ! empty( $opts ) ) {
        $attributes_meta[ $unique_key ] = array(
            'display_label' => $display_label,
            'options' => $opts
        );
        $attribute_map[ $unique_key ]   = $input_key;
    }
}

/**
 * Variations: Fetch variation data using variation product objects for accuracy
 */
$variations_data = array();
if ( $product->is_type( 'variable' ) ) {
    $available_variations = $product->get_available_variations();
    foreach ( $available_variations as $v ) {
        $variation_id = $v['variation_id'];
        $variation_obj = wc_get_product( $variation_id );

        if ( ! $variation_obj ) {
            continue;
        }

        $variations_data[] = array(
            'variation_id'  => $variation_id,
            'attributes'    => $v['attributes'],
            'price_html'    => $variation_obj->get_price_html(),
            'display_price' => (float) $variation_obj->get_price(),
            'is_in_stock'   => $variation_obj->is_in_stock(),
            'is_purchasable'=> $variation_obj->is_purchasable(),
        );
    }
}

/* JS product payload (for UI interactions only) */
$js_product = array(
    'id'                 => $product_id,
    'name'               => $product_name,
    'description'        => wp_strip_all_tags( apply_filters( 'woocommerce_short_description', $post->post_excerpt ) ?: $product->get_description() ),
    'images'             => $images,
    'price'              => (float) $regular_price,
    'salePrice'          => $sale_price ? (float) $sale_price : null,
    'price_html'         => wp_kses_post( $product->get_price_html() ),
    'rating'             => $avg_rating,
    'reviewCount'        => $review_count,
    'sku'                => $sku,
    'category'           => $category,
    'stock'              => is_null( $stock_qty ) ? 9999 : (int) $stock_qty,
    'attributes_options' => $attributes_meta, // label => [{label,value}]
    'attribute_map'      => $attribute_map,   // label => input_key
    'productType'        => $product->get_type(),
    'variations'         => $variations_data,
    'add_to_cart_url'    => esc_url( $product->add_to_cart_url() ),
);

?>
<main class="product-page">
  <div class="product-container">
    <button id="backBtn" class="back-link" type="button"><?php esc_html_e( 'Back to Products', 'aakaari' ); ?></button>

    <div class="product-layout" role="region" aria-label="<?php echo esc_attr( $product_name ); ?>">
      <div class="product-images">
        <div class="main-image-wrapper" id="mainImageWrapper">
          <img id="mainImage" src="<?php echo esc_url( $js_product['images'][0] ); ?>" alt="<?php echo esc_attr( $product_name ); ?>">
          <?php if ( $product->is_on_sale() ) : 
              $discount = $regular_price ? round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 ) : 0;
          ?>
            <span id="discountBadge" class="discount-badge">-<?php echo esc_html( $discount ); ?>%</span>
          <?php else: ?>
            <span id="discountBadge" class="discount-badge" style="display:none"></span>
          <?php endif; ?>
        </div>

        <div class="thumbnail-list" id="thumbnailList" aria-label="<?php esc_attr_e( 'Product thumbnails', 'aakaari' ); ?>">
          <?php foreach ( $js_product['images'] as $idx => $img_url ) : ?>
            <button class="thumbnail-btn<?php echo $idx === 0 ? ' active' : ''; ?>" type="button" aria-label="<?php echo esc_attr( $product_name ) . ' image ' . ( $idx + 1 ); ?>">
              <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $product_name ); ?> thumbnail <?php echo esc_attr( $idx + 1 ); ?>">
            </button>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="product-info">
        <div class="info-header">
          <h1 id="productName"><?php echo esc_html( $product_name ); ?></h1>
          <button id="wishlistBtn" class="wishlist-icon" aria-pressed="false" aria-label="<?php esc_attr_e( 'Add to wishlist', 'aakaari' ); ?>">
            <svg id="heartIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#333">
              <path d="M20.8 7.4a5.1 5.1 0 0 0-7.2 0L12 9l-1.6-1.6a5.1 5.1 0 1 0-7.2 7.2L12 21l8.8-6.4a5.1 5.1 0 0 0 0-7.2z" stroke-width="1.2" stroke-linejoin="round"/>
            </svg>
          </button>
        </div>

        <div class="rating-row">
          <div class="stars" id="starsRow" aria-hidden="true"></div>
          <span id="ratingText" class="rating-text"><?php echo esc_html( number_format( $avg_rating, 1 ) ) . ' (' . esc_html( $review_count ) . ' ' . __( 'reviews', 'aakaari' ) . ')'; ?></span>
        </div>

        <div class="price-row">
          <span id="priceCurrent" class="price"><?php echo wp_kses_post( $js_product['price_html'] ); ?></span>
          <span id="priceOld" class="old-price" style="display:none"></span>
        </div>

        <p id="productDesc" class="description"><?php echo esc_html( $js_product['description'] ); ?></p>

        <div id="optionsWrap" class="options-section" aria-live="polite"></div>

        <form class="cart" method="post" enctype="multipart/form-data" id="aakaari_add_to_cart_form">
          <input type="hidden" name="add-to-cart" id="aakaari_add_to_cart_input" value="<?php echo esc_attr( $product_id ); ?>">
          <input type="hidden" name="product_id" value="<?php echo esc_attr( $product_id ); ?>">
          <input type="hidden" name="variation_id" id="aakaari_variation_id" value="">
          <input type="hidden" name="aakaari_buy_now" id="aakaari_buy_now" value="0">

          <?php foreach ( $attribute_map as $label => $input_key ) : ?>
            <input type="hidden" name="<?php echo esc_attr( $input_key ); ?>" id="aakaari_attr_<?php echo esc_attr( sanitize_key( $input_key ) ); ?>" value="">
          <?php endforeach; ?>

          <div class="quantity-row">
            <label for="aakaari_qty"><?php esc_html_e( 'Quantity:', 'aakaari' ); ?></label>
            <div class="quantity-box" id="quantityBox">
              <button type="button" id="qtyDec" aria-label="<?php esc_attr_e( 'Decrease quantity', 'aakaari' ); ?>">-</button>
              <span id="qtyNumber">1</span>
              <button type="button" id="qtyInc" aria-label="<?php esc_attr_e( 'Increase quantity', 'aakaari' ); ?>">+</button>
            </div>
            <span id="stockText" class="stock-text"><?php echo esc_html( $js_product['stock'] > 0 ? $js_product['stock'] . ' in stock' : 'Out of stock' ); ?></span>
            <input type="hidden" name="quantity" id="aakaari_qty_input" value="1">
          </div>

          <div class="action-row">
            <button type="button" id="addCartBtn" class="add-cart-btn" aria-label="<?php esc_attr_e( 'Add to cart', 'aakaari' ); ?>">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" style="vertical-align:middle"><path d="M6 6h15l-1.5 9h-12z" stroke="#fff" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
              <?php esc_html_e( 'Add to Cart', 'aakaari' ); ?>
            </button>

            <button type="button" id="buyNowBtn" class="buy-btn" aria-label="<?php esc_attr_e( 'Buy now', 'aakaari' ); ?>">
              <?php esc_html_e( 'Buy Now', 'aakaari' ); ?>
            </button>
          </div>
        </form>

        <div class="features-row" style="margin-top:6px;">
          <div class="feature"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M3 7h13v8H3zM16 7l4 4v4" stroke="#000" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg><span><?php echo esc_html__( 'Free shipping over ₹100', 'aakaari' ); ?></span></div>
          <div class="feature"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M21 12a9 9 0 1 1-3-6.6" stroke="#000" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg><span><?php echo esc_html__( '7 days replacement (no return)', 'aakaari' ); ?></span></div>
        </div>

        <div class="product-meta" style="margin-top:6px;">
          <div><span><?php esc_html_e( 'SKU:', 'aakaari' ); ?></span> <span id="skuText"><?php echo esc_html( $sku ? $sku : 'N/A' ); ?></span></div>
          <div><span><?php esc_html_e( 'Category:', 'aakaari' ); ?></span> <span id="catText"><?php echo esc_html( $category ); ?></span></div>
        </div>

      </div>
    </div>

    <?php
    /**
     * Product Tabs (Description, Size Chart, Reviews)
     * WooCommerce will output tabs here
     */
    do_action( 'woocommerce_after_single_product_summary' );
    ?>

    <?php
    /**
     * Related Products Section
     * Display related products with proper WooCommerce functionality
     */
    $related_ids = wc_get_related_products( $product_id, 8 );
    if ( ! empty( $related_ids ) ) :
    ?>
      <section class="related-products-section">
        <h2><?php esc_html_e( 'You May Also Like', 'aakaari' ); ?></h2>
        <div class="related-products-grid">
          <?php
          foreach ( $related_ids as $related_id ) :
            $related_product = wc_get_product( $related_id );
            if ( ! $related_product ) continue;

            $related_image_id = $related_product->get_image_id();
            $related_image_url = $related_image_id ? wp_get_attachment_image_url( $related_image_id, 'aakaari-product' ) : wc_placeholder_img_src();
            ?>
            <div class="related-product-item">
              <a href="<?php echo esc_url( get_permalink( $related_id ) ); ?>" class="related-product-link">
                <div class="related-product-image">
                  <img src="<?php echo esc_url( $related_image_url ); ?>" alt="<?php echo esc_attr( $related_product->get_name() ); ?>">
                  <?php if ( $related_product->is_on_sale() ) : ?>
                    <span class="related-product-badge"><?php esc_html_e( 'Sale', 'aakaari' ); ?></span>
                  <?php endif; ?>
                </div>
                <h3 class="related-product-title"><?php echo esc_html( $related_product->get_name() ); ?></h3>
                <div class="related-product-rating">
                  <?php echo wc_get_rating_html( $related_product->get_average_rating() ); ?>
                  <span class="related-product-reviews">(<?php echo esc_html( $related_product->get_rating_count() ); ?>)</span>
                </div>
                <div class="related-product-price">
                  <?php echo $related_product->get_price_html(); ?>
                </div>
              </a>
              <div class="related-product-actions">
                <?php
                // Add to cart button
                woocommerce_template_loop_add_to_cart( array( 'product' => $related_product ) );
                ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif; ?>

  </div>
</main>

<script type="text/javascript">
    window.aakaari_product = <?php echo wp_json_encode( $js_product ); ?>;
    window.aakaari_ajax = {
      ajax_url: "<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>",
      home_url: "<?php echo esc_js( home_url() ); ?>",
      theme_url: "<?php echo esc_js( get_stylesheet_directory_uri() ); ?>"
    };

    // DEBUG: Log product data to console (remove this after debugging)
    console.log('=== PRODUCT DEBUG INFO ===');
    console.log('Product Type:', window.aakaari_product.productType);
    console.log('Attributes Options:', window.aakaari_product.attributes_options);
    console.log('Attribute Map:', window.aakaari_product.attribute_map);
    console.log('Variations:', window.aakaari_product.variations);
    console.log('Full Product Data:', window.aakaari_product);
</script>
