import React from 'react';
import { useCart } from '../../contexts/CartContext';
import { Button } from '../../components/ui/button';
import { EmptyCart } from './EmptyCart';
import { CartItem } from './CartItem';
import { CartSummary } from './CartSummary';
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
          <EmptyCart onNavigate={onNavigate} />
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
            {cart.map((item, index) => (
              <CartItem
                key={`${item.product.id}-${item.size}-${item.color}-${index}`}
                item={item}
                index={index}
                onUpdateQuantity={updateQuantity}
                onRemove={removeFromCart}
              />
            ))}

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
          <CartSummary
            subtotal={subtotal}
            shipping={shipping}
            tax={tax}
            total={total}
            onCheckout={() => onNavigate('checkout')}
          />
        </div>
      </div>
    </div>
  );
};
