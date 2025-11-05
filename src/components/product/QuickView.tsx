import { useState } from 'react';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription } from '../ui/dialog';
import { Button } from '../ui/button';
import { Badge } from '../ui/badge';
import { Product, CartItem } from '../../lib/types';
import { ShoppingCart, Heart, Star, X } from 'lucide-react';
import { toast } from 'sonner@2.0.3';

interface QuickViewProps {
  product: Product | null;
  isOpen: boolean;
  onClose: () => void;
  onAddToCart: (item: CartItem) => void;
  onAddToWishlist: (productId: string) => void;
  onViewDetails: (productId: string) => void;
}

export function QuickView({
  product,
  isOpen,
  onClose,
  onAddToCart,
  onAddToWishlist,
  onViewDetails,
}: QuickViewProps) {
  const [selectedSize, setSelectedSize] = useState('');
  const [selectedColor, setSelectedColor] = useState('');
  const [quantity, setQuantity] = useState(1);

  if (!product) return null;

  const handleAddToCart = () => {
    if (!selectedSize && product.sizes.length > 0) {
      toast.error('Please select a size');
      return;
    }
    if (!selectedColor && product.colors.length > 0) {
      toast.error('Please select a color');
      return;
    }

    onAddToCart({
      product,
      quantity,
      size: selectedSize || product.sizes[0],
      color: selectedColor || product.colors[0],
    });

    toast.success('Added to cart!');
    onClose();
  };

  const handleAddToWishlist = () => {
    onAddToWishlist(product.id);
    toast.success('Added to wishlist!');
  };

  return (
    <Dialog open={isOpen} onOpenChange={onClose}>
      <DialogContent className="max-w-4xl max-h-[90vh] overflow-y-auto">
        <DialogHeader>
          <DialogTitle>Quick View</DialogTitle>
          <DialogDescription>
            Preview product details and add to cart without leaving the page.
          </DialogDescription>
        </DialogHeader>

        <div className="grid md:grid-cols-2 gap-6">
          {/* Product Image */}
          <div className="aspect-square bg-gray-100 rounded-lg overflow-hidden">
            <img
              src={product.image}
              alt={product.name}
              className="w-full h-full object-cover"
            />
          </div>

          {/* Product Details */}
          <div className="space-y-4">
            {/* Title and Price */}
            <div>
              <h2 className="text-2xl mb-2">{product.name}</h2>
              <div className="flex items-center gap-4 mb-2">
                <p className="text-3xl">${product.price.toFixed(2)}</p>
                {product.isCustomizable && (
                  <Badge>Customizable</Badge>
                )}
              </div>

              {/* Rating */}
              <div className="flex items-center gap-2">
                <div className="flex gap-1">
                  {[1, 2, 3, 4, 5].map((star) => (
                    <Star
                      key={star}
                      className={`h-4 w-4 ${
                        star <= product.rating
                          ? 'fill-yellow-500 text-yellow-500'
                          : 'text-gray-300'
                      }`}
                    />
                  ))}
                </div>
                <span className="text-sm text-gray-600">
                  {product.rating} ({product.reviews} reviews)
                </span>
              </div>
            </div>

            {/* Description */}
            <p className="text-gray-600">{product.description}</p>

            {/* Stock Status */}
            <div>
              {product.inStock ? (
                <Badge variant="outline" className="text-green-600 border-green-600">
                  In Stock
                </Badge>
              ) : (
                <Badge variant="outline" className="text-red-600 border-red-600">
                  Out of Stock
                </Badge>
              )}
            </div>

            {/* Size Selection */}
            {product.sizes.length > 0 && (
              <div>
                <label className="block mb-2">Size</label>
                <div className="flex flex-wrap gap-2">
                  {product.sizes.map((size) => (
                    <button
                      key={size}
                      onClick={() => setSelectedSize(size)}
                      className={`px-4 py-2 border rounded-md ${
                        selectedSize === size
                          ? 'border-black bg-black text-white'
                          : 'border-gray-300 hover:border-gray-400'
                      }`}
                    >
                      {size}
                    </button>
                  ))}
                </div>
              </div>
            )}

            {/* Color Selection */}
            {product.colors.length > 0 && (
              <div>
                <label className="block mb-2">Color</label>
                <div className="flex flex-wrap gap-2">
                  {product.colors.map((color) => (
                    <button
                      key={color}
                      onClick={() => setSelectedColor(color)}
                      className={`px-4 py-2 border rounded-md ${
                        selectedColor === color
                          ? 'border-black bg-black text-white'
                          : 'border-gray-300 hover:border-gray-400'
                      }`}
                    >
                      {color}
                    </button>
                  ))}
                </div>
              </div>
            )}

            {/* Quantity */}
            <div>
              <label className="block mb-2">Quantity</label>
              <div className="flex items-center gap-3">
                <button
                  onClick={() => setQuantity(Math.max(1, quantity - 1))}
                  className="w-10 h-10 border rounded-md hover:bg-gray-50"
                >
                  -
                </button>
                <span className="w-12 text-center">{quantity}</span>
                <button
                  onClick={() => setQuantity(quantity + 1)}
                  className="w-10 h-10 border rounded-md hover:bg-gray-50"
                >
                  +
                </button>
              </div>
            </div>

            {/* Actions */}
            <div className="flex gap-3 pt-4">
              <Button
                onClick={handleAddToCart}
                disabled={!product.inStock}
                className="flex-1"
              >
                <ShoppingCart className="h-4 w-4 mr-2" />
                Add to Cart
              </Button>
              <Button variant="outline" onClick={handleAddToWishlist}>
                <Heart className="h-4 w-4" />
              </Button>
            </div>

            {/* View Full Details */}
            <Button
              variant="link"
              onClick={() => {
                onViewDetails(product.id);
                onClose();
              }}
              className="w-full"
            >
              View Full Details
            </Button>
          </div>
        </div>
      </DialogContent>
    </Dialog>
  );
}
