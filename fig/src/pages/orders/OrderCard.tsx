import React from 'react';
import { Card } from '../../components/ui/card';
import { Button } from '../../components/ui/button';
import { Order } from '../../types';

interface OrderCardProps {
  order: Order;
  onTrackOrder: (order: Order) => void;
}

export const OrderCard: React.FC<OrderCardProps> = ({ order, onTrackOrder }) => {
  return (
    <Card className="order-card">
      <div className="order-header">
        <div>
          <p className="order-number">Order {order.orderNumber}</p>
          <p className="order-date">
            Placed on {new Date(order.createdAt).toLocaleDateString('en-US', {
              year: 'numeric',
              month: 'long',
              day: 'numeric'
            })}
          </p>
        </div>
        <span className={`status-badge ${order.status.toLowerCase()}`}>
          {order.status}
        </span>
      </div>

      <div className="order-items">
        {order.items.map(item => (
          <div key={item.id} className="order-item">
            <img src={item.product.images[0]} alt={item.product.name} />
            <div className="item-info">
              <h3>{item.product.name}</h3>
              <div className="item-details">
                <span>Size: {item.size}</span>
                <span>Color: {item.color}</span>
                <span>Qty: {item.quantity}</span>
              </div>
            </div>
            <div className="item-price">
              ${(item.price * item.quantity).toFixed(2)}
            </div>
          </div>
        ))}
      </div>

      <div className="order-summary">
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

      <div className="order-footer">
        <div className="shipping-info">
          <p className="info-label">Shipping Address</p>
          <p>{order.shippingAddress.fullName}</p>
          <p>{order.shippingAddress.address}</p>
          <p>
            {order.shippingAddress.city}, {order.shippingAddress.state} {order.shippingAddress.zipCode}
          </p>
        </div>
        <div className="order-actions">
          <Button variant="outline" onClick={() => onTrackOrder(order)}>
            Track Order
          </Button>
          <Button variant="ghost">View Invoice</Button>
        </div>
      </div>
    </Card>
  );
};
