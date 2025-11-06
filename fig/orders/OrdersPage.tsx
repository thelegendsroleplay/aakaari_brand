import React from 'react';
import { Package, ArrowLeft } from 'lucide-react';
import { Button } from '../../components/ui/button';
import { Card } from '../../components/ui/card';
import { useOrders } from '../../contexts/OrdersContext';
import './orders.css';

interface OrdersPageProps {
  onNavigate: (page: string) => void;
}

export const OrdersPage: React.FC<OrdersPageProps> = ({ onNavigate }) => {
  const { orders } = useOrders();

  return (
    <div className="orders-page">
      <div className="orders-container">
        <div className="orders-header">
          <Button variant="ghost" onClick={() => onNavigate('account')} className="mb-4">
            <ArrowLeft className="w-4 h-4 mr-2" />
            Back to Account
          </Button>
          <h1>My Orders</h1>
          <p className="text-gray-600">View and track your orders</p>
        </div>

        {orders.length > 0 ? (
          <div className="orders-list">
            {orders.map(order => (
              <Card key={order.id} className="order-card">
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
                    <Button variant="outline">Track Order</Button>
                    <Button variant="ghost">View Invoice</Button>
                  </div>
                </div>
              </Card>
            ))}
          </div>
        ) : (
          <Card className="empty-orders">
            <Package className="w-16 h-16 text-gray-300 mb-4" />
            <h2>No orders yet</h2>
            <p className="text-gray-600 mb-6">Start shopping to see your orders here</p>
            <Button onClick={() => onNavigate('products')} size="lg">
              Browse Products
            </Button>
          </Card>
        )}
      </div>
    </div>
  );
};
