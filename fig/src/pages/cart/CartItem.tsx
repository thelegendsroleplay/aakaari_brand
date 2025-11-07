import React from 'react';
import { Trash2, Plus, Minus } from 'lucide-react';
import { CartItem as CartItemType } from '../../types';

interface CartItemProps {
  item: CartItemType;
  index: number;
  onUpdateQuantity: (productId: string, size: string, color: string, quantity: number) => void;
  onRemove: (productId: string, size: string, color: string) => void;
}

export const CartItem: React.FC<CartItemProps> = ({ item, index, onUpdateQuantity, onRemove }) => {
  const price = item.product.salePrice || item.product.price;
  const itemTotal = price * item.quantity;

  return (
    <div key={`${item.product.id}-${item.size}-${item.color}-${index}`} className="cart-item">
      <div className="item-image">
        <img src={item.product.images[0]} alt={item.product.name} />
      </div>

      <div className="item-details">
        <h3>{item.product.name}</h3>
        <p className="item-meta">
          Size: {item.size} | Color: {item.color}
        </p>
        {item.product.salePrice && (
          <p className="item-discount">
            Save ${((item.product.price - item.product.salePrice) * item.quantity).toFixed(2)}
          </p>
        )}
      </div>

      <div className="item-quantity">
        <button
          onClick={() => onUpdateQuantity(item.product.id, item.size, item.color, item.quantity - 1)}
          className="qty-btn"
          disabled={item.quantity <= 1}
        >
          <Minus className="w-4 h-4" />
        </button>
        <span className="qty-value">{item.quantity}</span>
        <button
          onClick={() => onUpdateQuantity(item.product.id, item.size, item.color, item.quantity + 1)}
          className="qty-btn"
          disabled={item.quantity >= item.product.stock}
        >
          <Plus className="w-4 h-4" />
        </button>
      </div>

      <div className="item-price">
        <span className="price-label">Price:</span>
        <span className="price-value">${price.toFixed(2)}</span>
      </div>

      <div className="item-total">
        <span className="total-label">Total:</span>
        <span className="total-value">${itemTotal.toFixed(2)}</span>
      </div>

      <button
        onClick={() => onRemove(item.product.id, item.size, item.color)}
        className="item-remove"
      >
        <Trash2 className="w-5 h-5" />
      </button>
    </div>
  );
};
