import { CheckoutPage } from '../../components/checkout/CheckoutPage';
import { CartItem, Address, Page } from '../../lib/types';

interface CheckoutPageContainerProps {
  cartItems: CartItem[];
  onPlaceOrder: (address: Address, paymentMethod: string) => void;
  onNavigate: (page: Page) => void;
}

export function CheckoutPageContainer({
  cartItems,
  onPlaceOrder,
  onNavigate,
}: CheckoutPageContainerProps) {
  return (
    <CheckoutPage
      cartItems={cartItems}
      onPlaceOrder={onPlaceOrder}
      onNavigate={onNavigate}
    />
  );
}
