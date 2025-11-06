import React, { useState } from 'react';
import { Star, Heart, ArrowLeft, ShoppingCart, Truck, RotateCcw } from 'lucide-react';
import { Product, ProductVariation } from '../../types';
import { useCart } from '../../contexts/CartContext';
import { useWishlist } from '../../contexts/WishlistContext';
import { useProducts } from '../../contexts/ProductsContext';
import { Button } from '../../components/ui/button';
import { ProductCarousel } from '../../components/ProductCarousel';
import { mockReviews } from '../../lib/mockData';
import './product-detail.css';

interface ProductDetailPageProps {
  product: Product;
  onNavigate: (page: string) => void;
  onViewProduct?: (product: Product) => void;
}

export const ProductDetailPage: React.FC<ProductDetailPageProps> = ({ product, onNavigate, onViewProduct }) => {
  const { addToCart } = useCart();
  const { addToWishlist, isInWishlist, removeFromWishlist } = useWishlist();
  const { products } = useProducts();
  
  const [selectedImage, setSelectedImage] = useState(0);
  const [selectedSize, setSelectedSize] = useState(product.sizes[0]);
  const [selectedColor, setSelectedColor] = useState(product.colors[0]);
  const [quantity, setQuantity] = useState(1);
  const [selectedVariation, setSelectedVariation] = useState<ProductVariation | null>(null);

  const inWishlist = isInWishlist(product.id);
  const reviews = mockReviews.filter(r => r.productId === product.id);
  const relatedProducts = products
    .filter(p => p.category === product.category && p.id !== product.id)
    .slice(0, 4);

  const handleVariationSelection = (attrName: string, value: string) => {
    if (product.productType === 'variable' && product.variations) {
      const selectedAttrs = { ...selectedVariation?.attributes } || {};
      selectedAttrs[attrName] = value;

      const matchingVariation = product.variations.find(v =>
        Object.entries(selectedAttrs).every(([key, val]) => v.attributes[key] === val)
      );

      if (matchingVariation) {
        setSelectedVariation(matchingVariation);
      }
    }
  };

  const getCurrentPrice = () => {
    if (product.productType === 'variable' && selectedVariation) {
      return selectedVariation.salePrice || selectedVariation.price;
    }
    return product.salePrice || product.price;
  };

  const getOriginalPrice = () => {
    if (product.productType === 'variable' && selectedVariation) {
      return selectedVariation.salePrice ? selectedVariation.price : null;
    }
    return product.salePrice ? product.price : null;
  };

  const getStockInfo = () => {
    if (product.productType === 'variable' && selectedVariation) {
      return selectedVariation.stock;
    }
    return product.stock;
  };

  const handleAddToCart = () => {
    addToCart(product, selectedSize, selectedColor, quantity);
  };

  const handleToggleWishlist = () => {
    if (inWishlist) {
      removeFromWishlist(product.id);
    } else {
      addToWishlist(product);
    }
  };

  const currentPrice = getCurrentPrice();
  const originalPrice = getOriginalPrice();
  const stockInfo = getStockInfo();
  const discount = originalPrice ? Math.round(((originalPrice - currentPrice) / originalPrice) * 100) : 0;

  return (
    <div className="product-page">
      <div className="product-container">
        <button onClick={() => onNavigate('products')} className="back-link">
          <ArrowLeft className="w-4 h-4" />
          Back to Products
        </button>

        <div className="product-layout">
          {/* Left - Images */}
          <div className="product-images">
            <div className="main-image-wrapper">
              <img src={product.images[selectedImage]} alt={product.name} />
              {discount > 0 && <span className="discount-badge">-{discount}%</span>}
            </div>
            <div className="thumbnail-list">
              {product.images.map((img, idx) => (
                <button
                  key={idx}
                  onClick={() => setSelectedImage(idx)}
                  className={`thumbnail-btn ${selectedImage === idx ? 'active' : ''}`}
                >
                  <img src={img} alt="" />
                </button>
              ))}
            </div>
          </div>

          {/* Right - Details */}
          <div className="product-info">
            <div className="info-header">
              <h1>{product.name}</h1>
              <button onClick={handleToggleWishlist} className="wishlist-icon">
                <Heart className={inWishlist ? 'filled' : ''} />
              </button>
            </div>

            <div className="rating-row">
              <div className="stars">
                {[...Array(5)].map((_, i) => (
                  <Star key={i} className={i < Math.floor(product.rating) ? 'star-filled' : 'star-empty'} />
                ))}
              </div>
              <span className="rating-text">{product.rating} ({product.reviewCount} reviews)</span>
            </div>

            <div className="price-row">
              <span className="price">${currentPrice.toFixed(2)}</span>
              {originalPrice && (
                <span className="old-price">${originalPrice.toFixed(2)}</span>
              )}
            </div>

            <p className="description">{product.description}</p>

            {/* Variable Product Attributes */}
            {product.productType === 'variable' && product.attributes && (
              <div className="options-section">
                {product.attributes.map(attr => (
                  <div key={attr.name} className="option-row">
                    <label>{attr.name}:</label>
                    <div className="option-btns">
                      {attr.values.map(value => (
                        <button
                          key={value}
                          onClick={() => handleVariationSelection(attr.name, value)}
                          className={selectedVariation?.attributes[attr.name] === value ? 'active' : ''}
                        >
                          {value}
                        </button>
                      ))}
                    </div>
                  </div>
                ))}
              </div>
            )}

            {/* Simple Product Options */}
            {product.productType === 'simple' && (
              <div className="options-section">
                <div className="option-row">
                  <label>Size:</label>
                  <div className="option-btns">
                    {product.sizes.map(size => (
                      <button
                        key={size}
                        onClick={() => setSelectedSize(size)}
                        className={selectedSize === size ? 'active' : ''}
                      >
                        {size}
                      </button>
                    ))}
                  </div>
                </div>

                <div className="option-row">
                  <label>Color:</label>
                  <div className="color-btns">
                    {product.colors.map(color => (
                      <button
                        key={color}
                        onClick={() => setSelectedColor(color)}
                        className={selectedColor === color ? 'active' : ''}
                        style={{ backgroundColor: color.toLowerCase() }}
                        title={color}
                      />
                    ))}
                  </div>
                </div>
              </div>
            )}

            <div className="quantity-row">
              <label>Quantity:</label>
              <div className="quantity-box">
                <button onClick={() => setQuantity(Math.max(1, quantity - 1))}>-</button>
                <span>{quantity}</span>
                <button onClick={() => setQuantity(Math.min(stockInfo, quantity + 1))}>+</button>
              </div>
              <span className="stock-text">
                {stockInfo > 0 ? `${stockInfo} in stock` : 'Out of stock'}
              </span>
            </div>

            <div className="action-row">
              <Button onClick={handleAddToCart} disabled={stockInfo === 0} className="add-cart-btn">
                <ShoppingCart className="w-5 h-5" />
                Add to Cart
              </Button>
              <Button 
                onClick={() => {
                  handleAddToCart();
                  onNavigate('checkout');
                }}
                disabled={stockInfo === 0}
                variant="outline"
                className="buy-btn"
              >
                Buy Now
              </Button>
            </div>

            <div className="features-row">
              <div className="feature">
                <Truck className="w-5 h-5" />
                <span>Free shipping over $100</span>
              </div>
              <div className="feature">
                <RotateCcw className="w-5 h-5" />
                <span>30-day returns</span>
              </div>
            </div>

            <div className="product-meta">
              <div><span>SKU:</span> {selectedVariation?.sku || product.sku}</div>
              <div><span>Category:</span> {product.category}</div>
            </div>
          </div>
        </div>

        {/* Reviews */}
        {reviews.length > 0 && (
          <div className="reviews-section">
            <h2>Customer Reviews</h2>
            <div className="reviews-list">
              {reviews.map(review => (
                <div key={review.id} className="review-item">
                  <div className="review-header">
                    <div className="stars">
                      {[...Array(5)].map((_, i) => (
                        <Star key={i} className={i < review.rating ? 'star-filled' : 'star-empty'} />
                      ))}
                    </div>
                    <span className="review-author">{review.userName}</span>
                  </div>
                  <h4>{review.title}</h4>
                  <p>{review.comment}</p>
                </div>
              ))}
            </div>
          </div>
        )}

        {/* Related Products */}
        {relatedProducts.length > 0 && (
          <div className="related-section">
            <h2>Related Products</h2>
            <ProductCarousel 
              products={relatedProducts} 
              onViewProduct={onViewProduct || (() => {})} 
            />
          </div>
        )}
      </div>
    </div>
  );
};