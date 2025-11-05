import { Button } from '../ui/button';
import { Card } from '../ui/card';
import { Page, Order } from '../../lib/types';
import { CheckCircle, Package, Truck, MapPin, CreditCard } from 'lucide-react';

interface OrderConfirmationProps {
  order: Order;
  onNavigate: (page: Page) => void;
}

export function OrderConfirmation({ order, onNavigate }: OrderConfirmationProps) {
  return (
    <div className="container mx-auto px-4 py-12">
      <div className="max-w-3xl mx-auto">
        {/* Success Message */}
        <div className="text-center mb-8">
          <div className="flex justify-center mb-4">
            <div className="bg-green-100 p-4 rounded-full">
              <CheckCircle className="h-16 w-16 text-green-600" />
            </div>
          </div>
          <h1 className="text-4xl mb-2">Order Confirmed!</h1>
          <p className="text-gray-600 text-lg">
            Thank you for your purchase. Your order has been received.
          </p>
        </div>

        {/* Order Details Card */}
        <Card className="p-6 mb-6">
          <div className="grid md:grid-cols-2 gap-6 mb-6">
            <div>
              <div className="flex items-center gap-2 mb-2">
                <Package className="h-5 w-5 text-gray-600" />
                <h3>Order Number</h3>
              </div>
              <p className="text-gray-600">{order.id}</p>
            </div>
            <div>
              <div className="flex items-center gap-2 mb-2">
                <CreditCard className="h-5 w-5 text-gray-600" />
                <h3>Payment Method</h3>
              </div>
              <p className="text-gray-600">{order.paymentMethod}</p>
            </div>
          </div>

          {/* Shipping Address */}
          <div className="border-t pt-6 mb-6">
            <div className="flex items-center gap-2 mb-3">
              <MapPin className="h-5 w-5 text-gray-600" />
              <h3>Shipping Address</h3>
            </div>
            <div className="text-gray-600">
              <p>{order.shippingAddress.fullName}</p>
              <p>{order.shippingAddress.street}</p>
              <p>
                {order.shippingAddress.city}, {order.shippingAddress.state}{' '}
                {order.shippingAddress.zipCode}
              </p>
              <p>{order.shippingAddress.country}</p>
              <p>{order.shippingAddress.phone}</p>
            </div>
          </div>

          {/* Order Items */}
          <div className="border-t pt-6">
            <div className="flex items-center gap-2 mb-4">
              <Truck className="h-5 w-5 text-gray-600" />
              <h3>Order Items</h3>
            </div>
            <div className="space-y-4">
              {order.items.map((item, idx) => {
                const customizationPrice =
                  item.product.customizationOptions?.reduce((sum, option) => {
                    if (item.customization && item.customization[option.id]) {
                      return sum + (option.price || 0);
                    }
                    return sum;
                  }, 0) || 0;

                const itemPrice = item.product.price + customizationPrice;

                return (
                  <div
                    key={idx}
                    className="flex items-center gap-4 pb-4 border-b last:border-0"
                  >
                    <img
                      src={item.product.image}
                      alt={item.product.name}
                      className="w-20 h-20 object-cover rounded"
                    />
                    <div className="flex-1">
                      <h4>{item.product.name}</h4>
                      <p className="text-sm text-gray-600">
                        Size: {item.size} | Color: {item.color}
                      </p>
                      {item.customization && (
                        <p className="text-sm text-gray-600">
                          Customized: {Object.entries(item.customization).map(([key, value]) => `${key}: ${value}`).join(', ')}
                        </p>
                      )}
                      <p className="text-sm text-gray-600">Qty: {item.quantity}</p>
                    </div>
                    <div className="text-right">
                      <p>${(itemPrice * item.quantity).toFixed(2)}</p>
                    </div>
                  </div>
                );
              })}
            </div>

            {/* Order Total */}
            <div className="mt-6 pt-6 border-t">
              <div className="flex justify-between mb-2">
                <span className="text-gray-600">Subtotal</span>
                <span>${order.total.toFixed(2)}</span>
              </div>
              <div className="flex justify-between mb-2">
                <span className="text-gray-600">Shipping</span>
                <span className="text-green-600">Free</span>
              </div>
              <div className="flex justify-between pt-2 border-t">
                <span className="text-lg">Total</span>
                <span className="text-lg">${order.total.toFixed(2)}</span>
              </div>
            </div>
          </div>
        </Card>

        {/* Next Steps */}
        <Card className="p-6 mb-6 bg-gray-50">
          <h3 className="mb-3">What's Next?</h3>
          <ul className="space-y-2 text-gray-600">
            <li className="flex items-start gap-2">
              <span className="text-green-600 mt-1">✓</span>
              <span>You'll receive an email confirmation shortly</span>
            </li>
            <li className="flex items-start gap-2">
              <span className="text-green-600 mt-1">✓</span>
              <span>Track your order status in your dashboard</span>
            </li>
            <li className="flex items-start gap-2">
              <span className="text-green-600 mt-1">✓</span>
              <span>Estimated delivery: 3-5 business days</span>
            </li>
          </ul>
        </Card>

        {/* Action Buttons */}
        <div className="flex flex-col sm:flex-row gap-3">
          <Button onClick={() => onNavigate('order-tracking')} className="flex-1">
            Track Order
          </Button>
          <Button onClick={() => onNavigate('shop')} variant="outline" className="flex-1">
            Continue Shopping
          </Button>
        </div>
      </div>
    </div>
  );
}
