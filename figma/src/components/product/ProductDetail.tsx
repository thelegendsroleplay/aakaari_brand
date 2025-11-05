import { useState } from 'react';
import { Product, CartItem } from '../../lib/types';
import { Button } from '../ui/button';
import { Badge } from '../ui/badge';
import { Star, Sparkles, Minus, Plus, ShoppingCart } from 'lucide-react';
import { ProductCustomizer } from './ProductCustomizer';
import { ImageWithFallback } from '../figma/ImageWithFallback';
import { toast } from 'sonner@2.0.3';

interface ProductDetailProps {
  product: Product;
  onAddToCart: (item: CartItem) => void;
  onBack: () => void;
  relatedProducts?: Product[];
  onProductClick?: (productId: string) => void;
  recentlyViewedProducts?: Product[];
  onAddToWishlist?: (productId: string) => void;
  wishlistIds?: string[];
}

export function ProductDetail({ 
  product, 
  onAddToCart, 
  onBack,
  relatedProducts = [],
  onProductClick,
  recentlyViewedProducts = [],
  onAddToWishlist,
  wishlistIds = []
}: ProductDetailProps) {
  const [selectedSize, setSelectedSize] = useState(product.sizes[0] || '');
  const [selectedColor, setSelectedColor] = useState(product.colors[0] || '');
  const [quantity, setQuantity] = useState(1);
  const [isCustomizerOpen, setIsCustomizerOpen] = useState(false);
  const [customization, setCustomization] = useState<Record<string, string>>({});

  const handleAddToCart = () => {
    if (!selectedSize) {
      toast.error('Please select a size');
      return;
    }

    const cartItem: CartItem = {
      product,
      quantity,
      size: selectedSize,
      color: selectedColor,
      customization: Object.keys(customization).length > 0 ? customization : undefined,
    };

    onAddToCart(cartItem);
    toast.success('Added to cart!');
  };

  const customizationPrice = product.customizationOptions?.reduce((total, option) => {
    if (customization[option.id]) {
      return total + (option.price || 0);
    }
    return total;
  }, 0) || 0;

  const totalPrice = (product.price + customizationPrice) * quantity;

  return (
    <div className="container mx-auto px-4 py-8">
      <Button variant="ghost" onClick={onBack} className="mb-6">
        ← Back to Shop
      </Button>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
        {/* Product Images */}
        <div className="space-y-4">
          <div className="aspect-square rounded-lg overflow-hidden bg-gray-100">
            <ImageWithFallback
              src={product.image}
              alt={product.name}
              className="w-full h-full object-cover"
            />
          </div>
          <div className="grid grid-cols-4 gap-4">
            {product.images.slice(0, 4).map((img, idx) => (
              <div key={idx} className="aspect-square rounded-lg overflow-hidden bg-gray-100 cursor-pointer border-2 border-transparent hover:border-black transition-colors">
                <ImageWithFallback
                  src={img}
                  alt={`${product.name} ${idx + 1}`}
                  className="w-full h-full object-cover"
                />
              </div>
            ))}
          </div>
        </div>

        {/* Product Info */}
        <div>
          <div className="flex items-start justify-between mb-4">
            <div>
              <p className="text-sm text-gray-600 mb-2">{product.category}</p>
              <h1 className="text-3xl md:text-4xl mb-2">{product.name}</h1>
            </div>
            {product.isCustomizable && (
              <Badge className="bg-black text-white">
                <Sparkles className="h-3 w-3 mr-1" />
                Customizable
              </Badge>
            )}
          </div>

          <div className="flex items-center gap-2 mb-4">
            <div className="flex items-center">
              {[...Array(5)].map((_, i) => (
                <Star
                  key={i}
                  className={`h-4 w-4 ${
                    i < Math.floor(product.rating)
                      ? 'fill-yellow-400 text-yellow-400'
                      : 'text-gray-300'
                  }`}
                />
              ))}
            </div>
            <span className="text-sm text-gray-600">
              {product.rating} ({product.reviews} reviews)
            </span>
          </div>

          <p className="text-3xl mb-6">${totalPrice.toFixed(2)}</p>

          <p className="text-gray-600 mb-6">{product.description}</p>

          {/* Size Selection */}
          <div className="mb-6">
            <Label className="mb-3 block">Size</Label>
            <div className="flex flex-wrap gap-2">
              {product.sizes.map((size) => (
                <Button
                  key={size}
                  variant={selectedSize === size ? 'default' : 'outline'}
                  onClick={() => setSelectedSize(size)}
                  className="min-w-[60px]"
                >
                  {size}
                </Button>
              ))}
            </div>
          </div>

          {/* Color Selection */}
          <div className="mb-6">
            <Label className="mb-3 block">Color: {selectedColor}</Label>
            <div className="flex gap-2">
              {product.colors.map((color) => (
                <button
                  key={color}
                  onClick={() => setSelectedColor(color)}
                  className={`w-10 h-10 rounded-full border-2 ${
                    selectedColor === color ? 'border-black' : 'border-gray-300'
                  }`}
                  style={{
                    backgroundColor: color.toLowerCase() === 'white' ? '#fff' :
                                   color.toLowerCase() === 'black' ? '#000' :
                                   color.toLowerCase() === 'blue' ? '#3b82f6' :
                                   color.toLowerCase() === 'navy' ? '#1e3a8a' :
                                   color.toLowerCase() === 'grey' || color.toLowerCase() === 'gray' ? '#6b7280' :
                                   color.toLowerCase() === 'red' ? '#ef4444' :
                                   color.toLowerCase() === 'khaki' ? '#c3b091' :
                                   color.toLowerCase() === 'silver' ? '#c0c0c0' :
                                   color.toLowerCase() === 'gold' ? '#ffd700' :
                                   '#9ca3af'
                  }}
                  title={color}
                />
              ))}
            </div>
          </div>

          {/* Quantity */}
          <div className="mb-6">
            <Label className="mb-3 block">Quantity</Label>
            <div className="flex items-center gap-3">
              <Button
                variant="outline"
                size="icon"
                onClick={() => setQuantity(Math.max(1, quantity - 1))}
              >
                <Minus className="h-4 w-4" />
              </Button>
              <span className="w-12 text-center">{quantity}</span>
              <Button
                variant="outline"
                size="icon"
                onClick={() => setQuantity(quantity + 1)}
              >
                <Plus className="h-4 w-4" />
              </Button>
            </div>
          </div>

          {/* Customization */}
          {product.isCustomizable && (
            <div className="mb-6 p-4 bg-gray-50 rounded-lg">
              <div className="flex justify-between items-center mb-2">
                <span>Make it yours with customization</span>
                {customizationPrice > 0 && (
                  <span className="text-sm">+${customizationPrice}</span>
                )}
              </div>
              <Button
                variant="outline"
                className="w-full"
                onClick={() => setIsCustomizerOpen(true)}
              >
                <Sparkles className="h-4 w-4 mr-2" />
                {Object.keys(customization).length > 0 ? 'Edit' : 'Add'} Customization
              </Button>
              {Object.keys(customization).length > 0 && (
                <div className="mt-3 text-sm text-gray-600">
                  {Object.entries(customization).map(([key, value]) => (
                    <div key={key}>
                      {product.customizationOptions?.find(o => o.id === key)?.name}: {value}
                    </div>
                  ))}
                </div>
              )}
            </div>
          )}

          {/* Add to Cart */}
          <Button
            size="lg"
            className="w-full mb-4"
            onClick={handleAddToCart}
            disabled={!product.inStock}
          >
            <ShoppingCart className="h-5 w-5 mr-2" />
            {product.inStock ? 'Add to Cart' : 'Out of Stock'}
          </Button>

          <div className="text-sm text-gray-600 space-y-2">
            <p>✓ Free shipping on orders over $100</p>
            <p>✓ 30-day return policy</p>
            <p>✓ Secure checkout</p>
          </div>
        </div>
      </div>

      <ProductCustomizer
        product={product}
        isOpen={isCustomizerOpen}
        onClose={() => setIsCustomizerOpen(false)}
        onSave={setCustomization}
      />

      {/* Related Products */}
      {relatedProducts.length > 0 && onProductClick && (
        <div className="mt-16">
          <h2 className="text-2xl mb-6">You May Also Like</h2>
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
            {relatedProducts.slice(0, 4).map((relatedProduct) => (
              <div
                key={relatedProduct.id}
                onClick={() => onProductClick(relatedProduct.id)}
                className="cursor-pointer group"
              >
                <div className="aspect-square bg-gray-100 rounded-lg overflow-hidden mb-2">
                  <ImageWithFallback
                    src={relatedProduct.image}
                    alt={relatedProduct.name}
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                  />
                </div>
                <h3 className="text-sm mb-1 line-clamp-1">{relatedProduct.name}</h3>
                <p className="text-sm">${relatedProduct.price.toFixed(2)}</p>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* Recently Viewed */}
      {recentlyViewedProducts.length > 0 && onProductClick && (
        <div className="mt-16">
          <h2 className="text-2xl mb-6">Recently Viewed</h2>
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
            {recentlyViewedProducts.slice(0, 4).map((viewedProduct) => (
              <div
                key={viewedProduct.id}
                onClick={() => onProductClick(viewedProduct.id)}
                className="cursor-pointer group"
              >
                <div className="aspect-square bg-gray-100 rounded-lg overflow-hidden mb-2">
                  <ImageWithFallback
                    src={viewedProduct.image}
                    alt={viewedProduct.name}
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                  />
                </div>
                <h3 className="text-sm mb-1 line-clamp-1">{viewedProduct.name}</h3>
                <p className="text-sm">${viewedProduct.price.toFixed(2)}</p>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
}

function Label({ children, className }: { children: React.ReactNode; className?: string }) {
  return <label className={className}>{children}</label>;
}
