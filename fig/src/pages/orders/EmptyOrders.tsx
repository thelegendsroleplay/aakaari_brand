import React from 'react';
import { Package } from 'lucide-react';
import { Card } from '../../components/ui/card';
import { Button } from '../../components/ui/button';

interface EmptyOrdersProps {
  onNavigate: (page: string) => void;
}

export const EmptyOrders: React.FC<EmptyOrdersProps> = ({ onNavigate }) => {
  return (
    <Card className="empty-orders">
      <Package className="w-16 h-16 text-gray-300 mb-4" />
      <h2>No orders yet</h2>
      <p className="text-gray-600 mb-6">Start shopping to see your orders here</p>
      <Button onClick={() => onNavigate('products')} size="lg">
        Browse Products
      </Button>
    </Card>
  );
};
