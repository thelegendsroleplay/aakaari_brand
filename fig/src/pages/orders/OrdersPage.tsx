import React from 'react';
import { ArrowLeft } from 'lucide-react';
import { Button } from '../../components/ui/button';
import { useOrders } from '../../contexts/OrdersContext';
import { Order } from '../../types';
import { OrderCard } from './OrderCard';
import { EmptyOrders } from './EmptyOrders';
import './orders.css';

interface OrdersPageProps {
  onNavigate: (page: string) => void;
  onViewOrderTracking?: (order: Order) => void;
}

export const OrdersPage: React.FC<OrdersPageProps> = ({ onNavigate, onViewOrderTracking }) => {
  const { orders } = useOrders();

  const handleTrackOrder = (order: Order) => {
    if (onViewOrderTracking) {
      onViewOrderTracking(order);
    }
  };

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
              <OrderCard
                key={order.id}
                order={order}
                onTrackOrder={handleTrackOrder}
              />
            ))}
          </div>
        ) : (
          <EmptyOrders onNavigate={onNavigate} />
        )}
      </div>
    </div>
  );
};
