import { CartPage } from '../../components/cart/CartPage';
import { CartItem, Page } from '../../lib/types';

interface CartPageContainerProps {
  cartItems: CartItem[];
  onUpdateQuantity: (index: number, quantity: number) => void;
  onRemoveItem: (index: number) => void;
  onNavigate: (page: Page) => void;
}

export function CartPageContainer({
  cartItems,
  onUpdateQuantity,
  onRemoveItem,
  onNavigate,
}: CartPageContainerProps) {
  return (
    <CartPage
      cartItems={cartItems}
      onUpdateQuantity={onUpdateQuantity}
      onRemoveItem={onRemoveItem}
      onNavigate={onNavigate}
    />
  );
}
