import React from 'react';
import { Button } from '../../components/ui/button';
import { Separator } from '../../components/ui/separator';

interface CartSummaryProps {
  subtotal: number;
  shipping: number;
  tax: number;
  total: number;
  onCheckout: () => void;
}

export const CartSummary: React.FC<CartSummaryProps> = ({
  subtotal,
  shipping,
  tax,
  total,
  onCheckout,
}) => {
  return (
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

      <Button size="lg" className="w-full checkout-btn" onClick={onCheckout}>
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
  );
};
