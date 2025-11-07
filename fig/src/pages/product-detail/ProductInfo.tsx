import React from 'react';
import { Star, Heart, ShoppingCart } from 'lucide-react';
import { Button } from '../../components/ui/button';
import { Product, ProductVariation } from '../../types';

interface ProductInfoProps {
  product: Product;
  selectedSize: string;
  selectedColor: string;
  quantity: number;
  selectedVariation: ProductVariation | null;
  inWishlist: boolean;
  onSizeChange: (size: string) => void;
  onColorChange: (color: string) => void;
  onQuantityChange: (qty: number) => void;
  onAddToCart: () => void;
  onToggleWishlist: () => void;
  getCurrentPrice: () => number;
  getOriginalPrice: () => number | null;
  getStockInfo: () => number;
}

export const ProductInfo: React.FC<ProductInfoProps> = ({
  product,
  selectedSize,
  selectedColor,
  quantity,
  inWishlist,
  onSizeChange,
  onColorChange,
  onQuantityChange,
  onAddToCart,
  onToggleWishlist,
  getCurrentPrice,
  getOriginalPrice,
  getStockInfo,
}) => {
  const currentPrice = getCurrentPrice();
  const originalPrice = getOriginalPrice();
  const stock = getStockInfo();

  return (
    <div className="product-info">
      <div className="product-header">
        <div>
          <h1>{product.name}</h1>
          <div className="rating-wrapper">
            <div className="stars">
              {[...Array(5)].map((_, i) => (
                <Star
                  key={i}
                  className={`star ${i < Math.floor(product.rating) ? 'filled' : ''}`}
                  size={16}
                />
              ))}
            </div>
            <span className="rating-text">
              {product.rating} ({product.reviewCount} reviews)
            </span>
          </div>
        </div>
      </div>

      <div className="price-section">
        <div className="price-wrapper">
          <span className="current-price">${currentPrice.toFixed(2)}</span>
          {originalPrice && (
            <>
              <span className="original-price">${originalPrice.toFixed(2)}</span>
              <span className="discount-badge">
                {Math.round(((originalPrice - currentPrice) / originalPrice) * 100)}% OFF
              </span>
            </>
          )}
        </div>
        <p className="stock-info">
          {stock > 0 ? (
            <span className="in-stock">{stock} in stock</span>
          ) : (
            <span className="out-of-stock">Out of stock</span>
          )}
        </p>
      </div>

      <div className="product-description">
        <p>{product.description}</p>
      </div>

      {/* Size Selection */}
      <div className="variant-section">
        <h3>Size</h3>
        <div className="size-options">
          {product.sizes.map(size => (
            <button
              key={size}
              className={`size-btn ${selectedSize === size ? 'active' : ''}`}
              onClick={() => onSizeChange(size)}
            >
              {size}
            </button>
          ))}
        </div>
      </div>

      {/* Color Selection */}
      <div className="variant-section">
        <h3>Color</h3>
        <div className="color-options">
          {product.colors.map(color => (
            <button
              key={color}
              className={`color-btn ${selectedColor === color ? 'active' : ''}`}
              style={{ backgroundColor: color.toLowerCase() }}
              onClick={() => onColorChange(color)}
              title={color}
            />
          ))}
        </div>
      </div>

      {/* Quantity */}
      <div className="quantity-section">
        <h3>Quantity</h3>
        <div className="quantity-controls">
          <button
            onClick={() => onQuantityChange(quantity - 1)}
            disabled={quantity <= 1}
            className="qty-btn"
          >
            -
          </button>
          <span className="qty-value">{quantity}</span>
          <button
            onClick={() => onQuantityChange(quantity + 1)}
            disabled={quantity >= stock}
            className="qty-btn"
          >
            +
          </button>
        </div>
      </div>

      {/* Action Buttons */}
      <div className="action-buttons">
        <Button
          size="lg"
          className="add-to-cart-btn"
          onClick={onAddToCart}
          disabled={stock === 0}
        >
          <ShoppingCart className="w-5 h-5 mr-2" />
          Add to Cart
        </Button>
        <Button
          size="lg"
          variant={inWishlist ? 'default' : 'outline'}
          className="wishlist-btn"
          onClick={onToggleWishlist}
        >
          <Heart className={`w-5 h-5 ${inWishlist ? 'fill-current' : ''}`} />
        </Button>
      </div>
    </div>
  );
};
