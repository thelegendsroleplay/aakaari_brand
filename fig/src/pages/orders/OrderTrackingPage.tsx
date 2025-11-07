import React from 'react';
import { ArrowLeft, Package, Truck, CheckCircle, MapPin } from 'lucide-react';
import { Button } from '../../components/ui/button';
import { Card } from '../../components/ui/card';
import { Order } from '../../types';
import './orders.css';

interface OrderTrackingPageProps {
  order: Order;
  onNavigate: (page: string) => void;
}

export const OrderTrackingPage: React.FC<OrderTrackingPageProps> = ({ order, onNavigate }) => {
  const trackingSteps = [
    {
      id: 'placed',
      label: 'Order Placed',
      icon: Package,
      date: order.createdAt,
      completed: true,
    },
    {
      id: 'processing',
      label: 'Processing',
      icon: Package,
      date: order.createdAt,
      completed: ['processing', 'shipped', 'delivered'].includes(order.status.toLowerCase()),
    },
    {
      id: 'shipped',
      label: 'Shipped',
      icon: Truck,
      date: order.shippedAt,
      completed: ['shipped', 'delivered'].includes(order.status.toLowerCase()),
    },
    {
      id: 'delivered',
      label: 'Delivered',
      icon: CheckCircle,
      date: order.deliveredAt,
      completed: order.status.toLowerCase() === 'delivered',
    },
  ];

  const currentStepIndex = trackingSteps.findIndex(step => !step.completed);
  const activeStep = currentStepIndex === -1 ? trackingSteps.length - 1 : Math.max(0, currentStepIndex - 1);

  return (
    <div className="order-tracking-page">
      <div className="tracking-container">
        <Button variant="ghost" onClick={() => onNavigate('orders')} className="mb-6">
          <ArrowLeft className="w-4 h-4 mr-2" />
          Back to Orders
        </Button>

        <div className="tracking-header">
          <h1>Track Order</h1>
          <p className="order-number">Order {order.orderNumber}</p>
          <p className="order-date">
            Placed on {new Date(order.createdAt).toLocaleDateString('en-US', {
              year: 'numeric',
              month: 'long',
              day: 'numeric'
            })}
          </p>
        </div>

        {/* Tracking Timeline */}
        <Card className="tracking-timeline-card">
          <h2 className="timeline-title">Delivery Status</h2>
          
          <div className="tracking-timeline">
            {trackingSteps.map((step, index) => {
              const Icon = step.icon;
              const isCompleted = step.completed;
              const isActive = index === activeStep;
              const isLast = index === trackingSteps.length - 1;

              return (
                <div key={step.id} className="timeline-step">
                  <div className="timeline-step-content">
                    <div className={`timeline-icon ${isCompleted ? 'completed' : ''} ${isActive ? 'active' : ''}`}>
                      <Icon className="w-6 h-6" />
                    </div>
                    <div className="timeline-info">
                      <h3 className={isCompleted ? 'completed' : ''}>{step.label}</h3>
                      {step.date && (
                        <p className="timeline-date">
                          {new Date(step.date).toLocaleDateString('en-US', {
                            month: 'short',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                          })}
                        </p>
                      )}
                    </div>
                  </div>
                  {!isLast && (
                    <div className={`timeline-connector ${isCompleted ? 'completed' : ''}`} />
                  )}
                </div>
              );
            })}
          </div>

          {order.trackingNumber && (
            <div className="tracking-number-section">
              <p className="tracking-label">Tracking Number:</p>
              <p className="tracking-number">{order.trackingNumber}</p>
            </div>
          )}

          {order.estimatedDelivery && (
            <div className="estimated-delivery">
              <MapPin className="w-5 h-5" />
              <span>
                Estimated Delivery: {new Date(order.estimatedDelivery).toLocaleDateString('en-US', {
                  weekday: 'long',
                  month: 'long',
                  day: 'numeric'
                })}
              </span>
            </div>
          )}
        </Card>

        {/* Order Items */}
        <Card className="order-items-card">
          <h2>Order Items</h2>
          <div className="tracking-items-list">
            {order.items.map(item => (
              <div key={item.id} className="tracking-item">
                <img src={item.product.images[0]} alt={item.product.name} />
                <div className="tracking-item-info">
                  <h3>{item.product.name}</h3>
                  <p className="item-details">
                    Size: {item.size} | Color: {item.color} | Qty: {item.quantity}
                  </p>
                  <p className="item-price">${(item.price * item.quantity).toFixed(2)}</p>
                </div>
              </div>
            ))}
          </div>
        </Card>

        {/* Shipping Address */}
        <Card className="shipping-address-card">
          <h2>Shipping Address</h2>
          <div className="address-details">
            <p className="address-name">{order.shippingAddress.fullName}</p>
            <p>{order.shippingAddress.address}</p>
            <p>
              {order.shippingAddress.city}, {order.shippingAddress.state} {order.shippingAddress.zipCode}
            </p>
            <p>{order.shippingAddress.country || 'United States'}</p>
            {order.shippingAddress.phone && <p>Phone: {order.shippingAddress.phone}</p>}
          </div>
        </Card>

        {/* Order Summary */}
        <Card className="order-summary-card">
          <h2>Order Summary</h2>
          <div className="summary-details">
            <div className="summary-row">
              <span>Subtotal</span>
              <span>${order.subtotal.toFixed(2)}</span>
            </div>
            {order.discount > 0 && (
              <div className="summary-row discount">
                <span>Discount</span>
                <span>-${order.discount.toFixed(2)}</span>
              </div>
            )}
            <div className="summary-row">
              <span>Shipping</span>
              <span>{order.shippingCost === 0 ? 'Free' : `$${order.shippingCost.toFixed(2)}`}</span>
            </div>
            <div className="summary-row">
              <span>Tax</span>
              <span>${order.tax.toFixed(2)}</span>
            </div>
            <div className="summary-row total">
              <span>Total</span>
              <span>${order.total.toFixed(2)}</span>
            </div>
          </div>
        </Card>

        {/* Actions */}
        <div className="tracking-actions">
          <Button variant="outline" onClick={() => window.print()}>
            Print Receipt
          </Button>
          <Button variant="outline" onClick={() => onNavigate('support')}>
            Contact Support
          </Button>
        </div>
      </div>
    </div>
  );
};
