import React, { createContext, useContext, useState } from 'react';
import { Order, CartItem, Address } from '../types';
import { toast } from 'sonner@2.0.3';

interface OrdersContextType {
  orders: Order[];
  placeOrder: (
    items: CartItem[],
    shippingAddress: Address,
    billingAddress: Address,
    paymentMethod: string,
    couponDiscount: number
  ) => Promise<Order>;
  getOrderById: (orderId: string) => Order | undefined;
  cancelOrder: (orderId: string) => void;
}

const OrdersContext = createContext<OrdersContextType | undefined>(undefined);

export const OrdersProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [orders, setOrders] = useState<Order[]>([]);

  const placeOrder = async (
    items: CartItem[],
    shippingAddress: Address,
    billingAddress: Address,
    paymentMethod: string,
    couponDiscount: number = 0
  ): Promise<Order> => {
    // Calculate totals
    const subtotal = items.reduce((sum, item) => {
      const price = item.product.salePrice || item.product.price;
      return sum + (price * item.quantity);
    }, 0);
    
    const tax = subtotal * 0.08; // 8% tax
    const shippingCost = subtotal >= 100 ? 0 : 10; // Free shipping over $100
    const total = subtotal + tax + shippingCost - couponDiscount;

    const newOrder: Order = {
      id: Math.random().toString(36).substr(2, 9),
      userId: '1',
      orderNumber: `ORD-${Date.now()}`,
      items,
      subtotal,
      tax,
      shippingCost,
      discount: couponDiscount,
      total,
      status: 'pending',
      paymentMethod,
      paymentStatus: 'completed',
      shippingAddress,
      billingAddress,
      trackingNumber: `TRK${Math.random().toString(36).substr(2, 9).toUpperCase()}`,
      createdAt: new Date(),
      updatedAt: new Date(),
      estimatedDelivery: new Date(Date.now() + 7 * 24 * 60 * 60 * 1000) // 7 days from now
    };

    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1500));
    
    setOrders(prev => [newOrder, ...prev]);
    toast.success('Order placed successfully!');
    
    return newOrder;
  };

  const getOrderById = (orderId: string) => {
    return orders.find(order => order.id === orderId);
  };

  const cancelOrder = (orderId: string) => {
    setOrders(prev =>
      prev.map(order =>
        order.id === orderId ? { ...order, status: 'cancelled' as const } : order
      )
    );
    toast.success('Order cancelled');
  };

  return (
    <OrdersContext.Provider value={{ orders, placeOrder, getOrderById, cancelOrder }}>
      {children}
    </OrdersContext.Provider>
  );
};

export const useOrders = () => {
  const context = useContext(OrdersContext);
  if (!context) throw new Error('useOrders must be used within OrdersProvider');
  return context;
};
