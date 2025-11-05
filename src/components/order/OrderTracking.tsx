import { useState } from 'react';
import { Button } from '../ui/button';
import { Card } from '../ui/card';
import { Input } from '../ui/input';
import { Label } from '../ui/label';
import { Page, Order } from '../../lib/types';
import { Package, Truck, CheckCircle, Clock, X, Search } from 'lucide-react';

interface OrderTrackingProps {
  orders: Order[];
  onNavigate: (page: Page) => void;
}

export function OrderTracking({ orders, onNavigate }: OrderTrackingProps) {
  const [trackingNumber, setTrackingNumber] = useState('');
  const [selectedOrder, setSelectedOrder] = useState<Order | null>(null);

  const handleTrack = (e: React.FormEvent) => {
    e.preventDefault();
    const order = orders.find((o) => o.id === trackingNumber);
    if (order) {
      setSelectedOrder(order);
    } else {
      alert('Order not found. Please check your order number.');
    }
  };

  const getStatusIcon = (status: Order['status']) => {
    switch (status) {
      case 'pending':
        return <Clock className="h-6 w-6" />;
      case 'processing':
        return <Package className="h-6 w-6" />;
      case 'shipped':
        return <Truck className="h-6 w-6" />;
      case 'delivered':
        return <CheckCircle className="h-6 w-6" />;
      case 'cancelled':
        return <X className="h-6 w-6" />;
      default:
        return <Package className="h-6 w-6" />;
    }
  };

  const getTrackingSteps = (status: Order['status']) => {
    const steps = [
      { label: 'Order Placed', status: 'pending', completed: true },
      { label: 'Processing', status: 'processing', completed: false },
      { label: 'Shipped', status: 'shipped', completed: false },
      { label: 'Delivered', status: 'delivered', completed: false },
    ];

    const statusIndex = steps.findIndex((step) => step.status === status);
    return steps.map((step, idx) => ({
      ...step,
      completed: idx <= statusIndex,
      active: idx === statusIndex,
    }));
  };

  return (
    <div className="container mx-auto px-4 py-12">
      <div className="max-w-3xl mx-auto">
        <h1 className="text-4xl mb-2">Track Your Order</h1>
        <p className="text-gray-600 mb-8">
          Enter your order number to see the latest updates
        </p>

        {/* Tracking Form */}
        {!selectedOrder && (
          <Card className="p-6 mb-8">
            <form onSubmit={handleTrack} className="space-y-4">
              <div>
                <Label htmlFor="tracking">Order Number</Label>
                <div className="flex gap-2 mt-1">
                  <div className="relative flex-1">
                    <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
                    <Input
                      id="tracking"
                      type="text"
                      placeholder="e.g., ORD-001"
                      value={trackingNumber}
                      onChange={(e) => setTrackingNumber(e.target.value)}
                      className="pl-10"
                      required
                    />
                  </div>
                  <Button type="submit">Track Order</Button>
                </div>
              </div>
            </form>
          </Card>
        )}

        {/* Tracking Results */}
        {selectedOrder && (
          <div className="space-y-6">
            {/* Order Header */}
            <Card className="p-6">
              <div className="flex items-start justify-between mb-4">
                <div>
                  <h2 className="text-2xl mb-1">Order #{selectedOrder.id}</h2>
                  <p className="text-gray-600">
                    Placed on {new Date(selectedOrder.createdAt).toLocaleDateString()}
                  </p>
                </div>
                <Button
                  variant="outline"
                  size="sm"
                  onClick={() => setSelectedOrder(null)}
                >
                  Track Another Order
                </Button>
              </div>

              {/* Current Status */}
              <div className="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                <div className="bg-white p-3 rounded-full">
                  {getStatusIcon(selectedOrder.status)}
                </div>
                <div>
                  <p className="text-sm text-gray-600">Current Status</p>
                  <p className="capitalize text-lg">{selectedOrder.status}</p>
                </div>
              </div>
            </Card>

            {/* Tracking Timeline */}
            <Card className="p-6">
              <h3 className="mb-6">Tracking Timeline</h3>
              <div className="space-y-8">
                {getTrackingSteps(selectedOrder.status).map((step, idx) => (
                  <div key={idx} className="flex gap-4">
                    {/* Timeline Icon */}
                    <div className="relative flex flex-col items-center">
                      <div
                        className={`w-10 h-10 rounded-full flex items-center justify-center ${
                          step.completed
                            ? 'bg-green-100 text-green-600'
                            : 'bg-gray-100 text-gray-400'
                        }`}
                      >
                        {step.completed ? (
                          <CheckCircle className="h-6 w-6" />
                        ) : (
                          <div className="w-3 h-3 rounded-full bg-gray-400" />
                        )}
                      </div>
                      {idx < 3 && (
                        <div
                          className={`w-0.5 h-16 ${
                            step.completed ? 'bg-green-200' : 'bg-gray-200'
                          }`}
                        />
                      )}
                    </div>

                    {/* Timeline Content */}
                    <div className="flex-1 pb-8">
                      <p
                        className={`${
                          step.active ? '' : step.completed ? '' : 'text-gray-400'
                        }`}
                      >
                        {step.label}
                      </p>
                      {step.completed && (
                        <p className="text-sm text-gray-600 mt-1">
                          {new Date(selectedOrder.updatedAt).toLocaleString()}
                        </p>
                      )}
                      {step.active && selectedOrder.status === 'shipped' && (
                        <p className="text-sm text-gray-600 mt-1">
                          Your package is on the way. Expected delivery: 2-3 business
                          days
                        </p>
                      )}
                    </div>
                  </div>
                ))}
              </div>
            </Card>

            {/* Order Items */}
            <Card className="p-6">
              <h3 className="mb-4">Order Items</h3>
              <div className="space-y-4">
                {selectedOrder.items.map((item, idx) => (
                  <div key={idx} className="flex items-center gap-4">
                    <img
                      src={item.product.image}
                      alt={item.product.name}
                      className="w-16 h-16 object-cover rounded"
                    />
                    <div className="flex-1">
                      <h4>{item.product.name}</h4>
                      <p className="text-sm text-gray-600">
                        Size: {item.size} | Color: {item.color} | Qty: {item.quantity}
                      </p>
                    </div>
                    <p>${(item.product.price * item.quantity).toFixed(2)}</p>
                  </div>
                ))}
              </div>
              <div className="mt-4 pt-4 border-t flex justify-between">
                <span>Total</span>
                <span className="text-lg">${selectedOrder.total.toFixed(2)}</span>
              </div>
            </Card>

            {/* Shipping Address */}
            <Card className="p-6">
              <h3 className="mb-4">Shipping Address</h3>
              <div className="text-gray-600">
                <p>{selectedOrder.shippingAddress.fullName}</p>
                <p>{selectedOrder.shippingAddress.street}</p>
                <p>
                  {selectedOrder.shippingAddress.city},{' '}
                  {selectedOrder.shippingAddress.state}{' '}
                  {selectedOrder.shippingAddress.zipCode}
                </p>
                <p>{selectedOrder.shippingAddress.country}</p>
                <p>{selectedOrder.shippingAddress.phone}</p>
              </div>
            </Card>

            {/* Actions */}
            <div className="flex gap-3">
              <Button onClick={() => onNavigate('user-dashboard')} className="flex-1">
                View All Orders
              </Button>
              <Button onClick={() => onNavigate('contact')} variant="outline" className="flex-1">
                Contact Support
              </Button>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
