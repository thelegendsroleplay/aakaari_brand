import React from 'react';
import { ShoppingBag } from 'lucide-react';
import { Button } from '../../components/ui/button';

interface EmptyCartProps {
  onNavigate: (page: string) => void;
}

export const EmptyCart: React.FC<EmptyCartProps> = ({ onNavigate }) => {
  return (
    <div className="empty-cart">
      <ShoppingBag className="empty-icon" />
      <h2>Your cart is empty</h2>
      <p>Add some items to get started</p>
      <Button size="lg" onClick={() => onNavigate('products')}>
        Continue Shopping
      </Button>
    </div>
  );
};
