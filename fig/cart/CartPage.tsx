import React from 'react';
import { Trash2, Plus, Minus, ShoppingBag } from 'lucide-react';
import { useCart } from '../../contexts/CartContext';
import { Button } from '../../components/ui/button';
import { Separator } from '../../components/ui/separator';
import './cart.css';

interface CartPageProps {
  onNavigate: (page: string) => void;
}

export const CartPage: React.FC<CartPageProps> = ({ onNavigate }) => {
  const { cart, removeFromCart, updateQuantity, cartTotal, clearCart } = useCart();

  const subtotal = cartTotal;
  const shipping = subtotal >= 100 ? 0 : 10;
  const tax = subtotal * 0.08;
  const total = subtotal + shipping + tax;

  if (cart.length === 0) {
    return (
      <div className="cart-page">
        <div className="cart-container">
          <div className="empty-cart">
            <ShoppingBag className="empty-icon" />
            <h2>Your cart is empty</h2>
            <p>Add some items to get started</p>
            <Button size="lg" onClick={() => onNavigate('products')}>
              Continue Shopping
            </Button>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="cart-page">
      <div className="cart-container">
        <div className="cart-header">
          <h1>Shopping Cart</h1>
          <p>{cart.length} {cart.length === 1 ? 'item' : 'items'}</p>
        </div>

        <div className="cart-grid">
          {/* Cart Items */}
          <div className="cart-items">
            {cart.map((item, index) => {
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
                      onClick={() => updateQuantity(item.product.id, item.size, item.color, item.quantity - 1)}
                      className="qty-btn"
                      disabled={item.quantity <= 1}
                    >
                      <Minus className="w-4 h-4" />
                    </button>
                    <span className="qty-value">{item.quantity}</span>
                    <button
                      onClick={() => updateQuantity(item.product.id, item.size, item.color, item.quantity + 1)}
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
                    onClick={() => removeFromCart(item.product.id, item.size, item.color)}
                    className="item-remove"
                  >
                    <Trash2 className="w-5 h-5" />
                  </button>
                </div>
              );
            })}

            <div className="cart-actions">
              <Button variant="outline" onClick={() => onNavigate('products')}>
                Continue Shopping
              </Button>
              <Button variant="destructive" onClick={clearCart}>
                Clear Cart
              </Button>
            </div>
          </div>

          {/* Cart Summary */}
          <div className="cart-summary">
            <h2>Order Summary</h2>

            <div className="summary-rows">
              <div className="summary-row">
                <span>Subtotal</span>
                <span>${subtotal.toFixed(2)}</span>
              </div>
              <div className="summary-row">
                <span>Shipping</span>
                <span>{shipping === 0 ? 'FREE' : `$${shipping.toFixed(2)}`}</span>
              </div>
              {shipping === 0 && (
                <p className="free-shipping-note">🎉 You got free shipping!</p>
              )}
              {subtotal < 100 && (
                <p className="free-shipping-progress">
                  Add ${(100 - subtotal).toFixed(2)} more for free shipping
                </p>
              )}
              <div className="summary-row">
                <span>Tax (8%)</span>
                <span>${tax.toFixed(2)}</span>
              </div>
            </div>

            <Separator className="my-4" />

            <div className="summary-total">
              <span>Total</span>
              <span>${total.toFixed(2)}</span>
            </div>

            <Button size="lg" className="w-full checkout-btn" onClick={() => onNavigate('checkout')}>
              Proceed to Checkout
            </Button>

            <div className="payment-methods">
              <p>We accept:</p>
              <div className="payment-icons">
                <img src="https://via.placeholder.com/40x25/666/fff?text=VISA" alt="Visa" />
                <img src="https://via.placeholder.com/40x25/666/fff?text=MC" alt="Mastercard" />
                <img src="https://via.placeholder.com/40x25/666/fff?text=AMEX" alt="Amex" />
                <img src="https://via.placeholder.com/40x25/666/fff?text=PP" alt="PayPal" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
