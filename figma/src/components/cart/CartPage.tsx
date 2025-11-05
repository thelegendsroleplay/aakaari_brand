import { CartItem, Page } from '../../lib/types';
import { Button } from '../ui/button';
import { Card } from '../ui/card';
import { Minus, Plus, Trash2 } from 'lucide-react';
import { ImageWithFallback } from '../figma/ImageWithFallback';

interface CartPageProps {
  cartItems: CartItem[];
  onUpdateQuantity: (index: number, quantity: number) => void;
  onRemoveItem: (index: number) => void;
  onNavigate: (page: Page) => void;
}

export function CartPage({ cartItems, onUpdateQuantity, onRemoveItem, onNavigate }: CartPageProps) {
  const subtotal = cartItems.reduce((total, item) => {
    const customizationPrice = item.product.customizationOptions?.reduce((sum, option) => {
      if (item.customization && item.customization[option.id]) {
        return sum + (option.price || 0);
      }
      return sum;
    }, 0) || 0;
    return total + (item.product.price + customizationPrice) * item.quantity;
  }, 0);

  const shipping = subtotal > 100 ? 0 : 10;
  const total = subtotal + shipping;

  if (cartItems.length === 0) {
    return (
      <div className="container mx-auto px-4 py-16">
        <Card className="max-w-md mx-auto p-8 text-center">
          <h2 className="text-2xl mb-4">Your cart is empty</h2>
          <p className="text-gray-600 mb-6">Add some items to get started!</p>
          <Button onClick={() => onNavigate('shop')}>
            Continue Shopping
          </Button>
        </Card>
      </div>
    );
  }

  return (
    <div className="container mx-auto px-4 py-8">
      <h1 className="text-3xl md:text-4xl mb-8">Shopping Cart</h1>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Cart Items */}
        <div className="lg:col-span-2 space-y-4">
          {cartItems.map((item, index) => {
            const customizationPrice = item.product.customizationOptions?.reduce((sum, option) => {
              if (item.customization && item.customization[option.id]) {
                return sum + (option.price || 0);
              }
              return sum;
            }, 0) || 0;
            const itemPrice = item.product.price + customizationPrice;

            return (
              <Card key={index} className="p-4">
                <div className="flex gap-4">
                  <div className="w-24 h-24 md:w-32 md:h-32 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100">
                    <ImageWithFallback
                      src={item.product.image}
                      alt={item.product.name}
                      className="w-full h-full object-cover"
                    />
                  </div>

                  <div className="flex-1 min-w-0">
                    <div className="flex justify-between items-start mb-2">
                      <div>
                        <h3 className="mb-1">{item.product.name}</h3>
                        <p className="text-sm text-gray-600">
                          Size: {item.size} | Color: {item.color}
                        </p>
                        {item.customization && (
                          <div className="text-sm text-gray-600 mt-1">
                            {Object.entries(item.customization).map(([key, value]) => (
                              <div key={key}>
                                {item.product.customizationOptions?.find(o => o.id === key)?.name}: {value}
                              </div>
                            ))}
                          </div>
                        )}
                      </div>
                      <Button
                        variant="ghost"
                        size="icon"
                        onClick={() => onRemoveItem(index)}
                      >
                        <Trash2 className="h-4 w-4" />
                      </Button>
                    </div>

                    <div className="flex justify-between items-center">
                      <div className="flex items-center gap-2">
                        <Button
                          variant="outline"
                          size="icon"
                          className="h-8 w-8"
                          onClick={() => onUpdateQuantity(index, item.quantity - 1)}
                          disabled={item.quantity <= 1}
                        >
                          <Minus className="h-3 w-3" />
                        </Button>
                        <span className="w-8 text-center">{item.quantity}</span>
                        <Button
                          variant="outline"
                          size="icon"
                          className="h-8 w-8"
                          onClick={() => onUpdateQuantity(index, item.quantity + 1)}
                        >
                          <Plus className="h-3 w-3" />
                        </Button>
                      </div>
                      <span className="text-lg">${(itemPrice * item.quantity).toFixed(2)}</span>
                    </div>
                  </div>
                </div>
              </Card>
            );
          })}
        </div>

        {/* Order Summary */}
        <div>
          <Card className="p-6 sticky top-24">
            <h2 className="text-xl mb-6">Order Summary</h2>
            
            <div className="space-y-3 mb-6">
              <div className="flex justify-between text-gray-600">
                <span>Subtotal</span>
                <span>${subtotal.toFixed(2)}</span>
              </div>
              <div className="flex justify-between text-gray-600">
                <span>Shipping</span>
                <span>{shipping === 0 ? 'FREE' : `$${shipping.toFixed(2)}`}</span>
              </div>
              {subtotal < 100 && (
                <p className="text-sm text-gray-500">
                  Add ${(100 - subtotal).toFixed(2)} more for free shipping!
                </p>
              )}
              <div className="border-t pt-3">
                <div className="flex justify-between text-lg">
                  <span>Total</span>
                  <span>${total.toFixed(2)}</span>
                </div>
              </div>
            </div>

            <Button
              size="lg"
              className="w-full mb-4"
              onClick={() => onNavigate('checkout')}
            >
              Proceed to Checkout
            </Button>

            <Button
              variant="outline"
              className="w-full"
              onClick={() => onNavigate('shop')}
            >
              Continue Shopping
            </Button>
          </Card>
        </div>
      </div>
    </div>
  );
}
