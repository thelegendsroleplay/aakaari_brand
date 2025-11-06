import React, { useState } from 'react';
import { CreditCard, Truck, CheckCircle } from 'lucide-react';
import { useCart } from '../../contexts/CartContext';
import { useOrders } from '../../contexts/OrdersContext';
import { useAuth } from '../../contexts/AuthContext';
import { Button } from '../../components/ui/button';
import { Input } from '../../components/ui/input';
import { Label } from '../../components/ui/label';
import { RadioGroup, RadioGroupItem } from '../../components/ui/radio-group';
import { Separator } from '../../components/ui/separator';
import { Address } from '../../types';
import './checkout.css';

interface CheckoutPageProps {
  onNavigate: (page: string) => void;
}

export const CheckoutPage: React.FC<CheckoutPageProps> = ({ onNavigate }) => {
  const { cart, cartTotal, clearCart } = useCart();
  const { placeOrder } = useOrders();
  const { isAuthenticated } = useAuth();
  const [step, setStep] = useState(1);
  const [loading, setLoading] = useState(false);
  const [couponCode, setCouponCode] = useState('');
  const [discount, setDiscount] = useState(0);

  const [shippingAddress, setShippingAddress] = useState<Partial<Address>>({
    name: '',
    addressLine1: '',
    addressLine2: '',
    city: '',
    state: '',
    zipCode: '',
    country: 'USA',
    phone: ''
  });

  const [billingAddress, setBillingAddress] = useState<Partial<Address>>({
    name: '',
    addressLine1: '',
    addressLine2: '',
    city: '',
    state: '',
    zipCode: '',
    country: 'USA',
    phone: ''
  });

  const [sameAsShipping, setSameAsShipping] = useState(true);
  const [paymentMethod, setPaymentMethod] = useState('card');

  if (!isAuthenticated) {
    return (
      <div className="checkout-page">
        <div className="checkout-container">
          <div className="auth-required">
            <h2>Please log in to continue</h2>
            <p>You need to be logged in to complete your purchase</p>
            <Button onClick={() => onNavigate('auth')}>
              Go to Login
            </Button>
          </div>
        </div>
      </div>
    );
  }

  if (cart.length === 0) {
    return (
      <div className="checkout-page">
        <div className="checkout-container">
          <div className="empty-checkout">
            <h2>Your cart is empty</h2>
            <Button onClick={() => onNavigate('products')}>
              Continue Shopping
            </Button>
          </div>
        </div>
      </div>
    );
  }

  const subtotal = cartTotal;
  const shipping = subtotal >= 100 ? 0 : 10;
  const tax = subtotal * 0.08;
  const total = subtotal + shipping + tax - discount;

  const applyCoupon = () => {
    if (couponCode === 'WELCOME10') {
      setDiscount(subtotal * 0.1);
    } else if (couponCode === 'SAVE50') {
      setDiscount(50);
    }
  };

  const handlePlaceOrder = async () => {
    setLoading(true);
    try {
      const finalBilling = sameAsShipping ? shippingAddress : billingAddress;
      await placeOrder(
        cart,
        shippingAddress as Address,
        finalBilling as Address,
        paymentMethod,
        discount
      );
      clearCart();
      setStep(4);
    } catch (error) {
      console.error('Order placement failed:', error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="checkout-page">
      <div className="checkout-container">
        {/* Progress Steps */}
        <div className="checkout-steps">
          <div className={`step ${step >= 1 ? 'active' : ''} ${step > 1 ? 'completed' : ''}`}>
            <div className="step-number">1</div>
            <span>Shipping</span>
          </div>
          <div className="step-line" />
          <div className={`step ${step >= 2 ? 'active' : ''} ${step > 2 ? 'completed' : ''}`}>
            <div className="step-number">2</div>
            <span>Billing</span>
          </div>
          <div className="step-line" />
          <div className={`step ${step >= 3 ? 'active' : ''} ${step > 3 ? 'completed' : ''}`}>
            <div className="step-number">3</div>
            <span>Payment</span>
          </div>
          <div className="step-line" />
          <div className={`step ${step >= 4 ? 'active' : ''}`}>
            <div className="step-number">4</div>
            <span>Confirm</span>
          </div>
        </div>

        <div className="checkout-grid">
          <div className="checkout-form">
            {/* Step 1: Shipping */}
            {step === 1 && (
              <div className="form-section">
                <h2><Truck className="inline w-6 h-6 mr-2" />Shipping Address</h2>
                <div className="form-grid">
                  <div className="form-group col-span-2">
                    <Label>Full Name *</Label>
                    <Input
                      value={shippingAddress.name}
                      onChange={(e) => setShippingAddress({ ...shippingAddress, name: e.target.value })}
                    />
                  </div>
                  <div className="form-group col-span-2">
                    <Label>Address Line 1 *</Label>
                    <Input
                      value={shippingAddress.addressLine1}
                      onChange={(e) => setShippingAddress({ ...shippingAddress, addressLine1: e.target.value })}
                    />
                  </div>
                  <div className="form-group col-span-2">
                    <Label>Address Line 2</Label>
                    <Input
                      value={shippingAddress.addressLine2}
                      onChange={(e) => setShippingAddress({ ...shippingAddress, addressLine2: e.target.value })}
                    />
                  </div>
                  <div className="form-group">
                    <Label>City *</Label>
                    <Input
                      value={shippingAddress.city}
                      onChange={(e) => setShippingAddress({ ...shippingAddress, city: e.target.value })}
                    />
                  </div>
                  <div className="form-group">
                    <Label>State *</Label>
                    <Input
                      value={shippingAddress.state}
                      onChange={(e) => setShippingAddress({ ...shippingAddress, state: e.target.value })}
                    />
                  </div>
                  <div className="form-group">
                    <Label>ZIP Code *</Label>
                    <Input
                      value={shippingAddress.zipCode}
                      onChange={(e) => setShippingAddress({ ...shippingAddress, zipCode: e.target.value })}
                    />
                  </div>
                  <div className="form-group">
                    <Label>Phone *</Label>
                    <Input
                      type="tel"
                      value={shippingAddress.phone}
                      onChange={(e) => setShippingAddress({ ...shippingAddress, phone: e.target.value })}
                    />
                  </div>
                </div>
                <Button onClick={() => setStep(2)} className="w-full mt-4">
                  Continue to Billing
                </Button>
              </div>
            )}

            {/* Step 2: Billing */}
            {step === 2 && (
              <div className="form-section">
                <h2><CreditCard className="inline w-6 h-6 mr-2" />Billing Address</h2>
                <div className="same-address-check">
                  <input
                    type="checkbox"
                    checked={sameAsShipping}
                    onChange={(e) => setSameAsShipping(e.target.checked)}
                    id="sameAsShipping"
                  />
                  <label htmlFor="sameAsShipping">Same as shipping address</label>
                </div>

                {!sameAsShipping && (
                  <div className="form-grid">
                    <div className="form-group col-span-2">
                      <Label>Full Name *</Label>
                      <Input
                        value={billingAddress.name}
                        onChange={(e) => setBillingAddress({ ...billingAddress, name: e.target.value })}
                      />
                    </div>
                    <div className="form-group col-span-2">
                      <Label>Address Line 1 *</Label>
                      <Input
                        value={billingAddress.addressLine1}
                        onChange={(e) => setBillingAddress({ ...billingAddress, addressLine1: e.target.value })}
                      />
                    </div>
                    <div className="form-group">
                      <Label>City *</Label>
                      <Input
                        value={billingAddress.city}
                        onChange={(e) => setBillingAddress({ ...billingAddress, city: e.target.value })}
                      />
                    </div>
                    <div className="form-group">
                      <Label>State *</Label>
                      <Input
                        value={billingAddress.state}
                        onChange={(e) => setBillingAddress({ ...billingAddress, state: e.target.value })}
                      />
                    </div>
                  </div>
                )}

                <div className="form-actions">
                  <Button variant="outline" onClick={() => setStep(1)}>
                    Back
                  </Button>
                  <Button onClick={() => setStep(3)}>
                    Continue to Payment
                  </Button>
                </div>
              </div>
            )}

            {/* Step 3: Payment */}
            {step === 3 && (
              <div className="form-section">
                <h2><CreditCard className="inline w-6 h-6 mr-2" />Payment Method</h2>
                
                <RadioGroup value={paymentMethod} onValueChange={setPaymentMethod}>
                  <div className="payment-option">
                    <RadioGroupItem value="card" id="card" />
                    <Label htmlFor="card">Credit/Debit Card</Label>
                  </div>
                  <div className="payment-option">
                    <RadioGroupItem value="paypal" id="paypal" />
                    <Label htmlFor="paypal">PayPal</Label>
                  </div>
                  <div className="payment-option">
                    <RadioGroupItem value="cod" id="cod" />
                    <Label htmlFor="cod">Cash on Delivery</Label>
                  </div>
                </RadioGroup>

                {paymentMethod === 'card' && (
                  <div className="form-grid mt-4">
                    <div className="form-group col-span-2">
                      <Label>Card Number</Label>
                      <Input placeholder="1234 5678 9012 3456" />
                    </div>
                    <div className="form-group">
                      <Label>Expiry Date</Label>
                      <Input placeholder="MM/YY" />
                    </div>
                    <div className="form-group">
                      <Label>CVV</Label>
                      <Input placeholder="123" />
                    </div>
                  </div>
                )}

                <div className="form-actions">
                  <Button variant="outline" onClick={() => setStep(2)}>
                    Back
                  </Button>
                  <Button onClick={handlePlaceOrder} disabled={loading}>
                    {loading ? 'Processing...' : 'Place Order'}
                  </Button>
                </div>
              </div>
            )}

            {/* Step 4: Confirmation */}
            {step === 4 && (
              <div className="order-success">
                <CheckCircle className="success-icon" />
                <h2>Order Placed Successfully!</h2>
                <p>Thank you for your purchase. Your order has been confirmed.</p>
                <div className="success-actions">
                  <Button onClick={() => onNavigate('orders')}>
                    View Orders
                  </Button>
                  <Button variant="outline" onClick={() => onNavigate('home')}>
                    Continue Shopping
                  </Button>
                </div>
              </div>
            )}
          </div>

          {/* Order Summary Sidebar */}
          {step < 4 && (
            <div className="order-summary">
              <h3>Order Summary</h3>
              
              <div className="summary-items">
                {cart.map((item, idx) => (
                  <div key={idx} className="summary-item">
                    <img src={item.product.images[0]} alt={item.product.name} />
                    <div className="item-info">
                      <p className="item-name">{item.product.name}</p>
                      <p className="item-details">
                        {item.size} • {item.color} • Qty: {item.quantity}
                      </p>
                    </div>
                    <span className="item-price">
                      ${((item.product.salePrice || item.product.price) * item.quantity).toFixed(2)}
                    </span>
                  </div>
                ))}
              </div>

              <Separator className="my-4" />

              <div className="coupon-section">
                <Input
                  placeholder="Coupon code"
                  value={couponCode}
                  onChange={(e) => setCouponCode(e.target.value)}
                />
                <Button variant="outline" onClick={applyCoupon}>
                  Apply
                </Button>
              </div>

              <div className="summary-totals">
                <div className="total-row">
                  <span>Subtotal</span>
                  <span>${subtotal.toFixed(2)}</span>
                </div>
                <div className="total-row">
                  <span>Shipping</span>
                  <span>{shipping === 0 ? 'FREE' : `$${shipping.toFixed(2)}`}</span>
                </div>
                <div className="total-row">
                  <span>Tax</span>
                  <span>${tax.toFixed(2)}</span>
                </div>
                {discount > 0 && (
                  <div className="total-row discount">
                    <span>Discount</span>
                    <span>-${discount.toFixed(2)}</span>
                  </div>
                )}
                <Separator className="my-2" />
                <div className="total-row grand-total">
                  <span>Total</span>
                  <span>${total.toFixed(2)}</span>
                </div>
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};
