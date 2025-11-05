import { Button } from '../ui/button';
import { Card } from '../ui/card';
import { Badge } from '../ui/badge';
import { Product, Page } from '../../lib/types';
import { Heart, ShoppingCart, Trash2, Share2 } from 'lucide-react';
import { toast } from 'sonner@2.0.3';

interface WishlistPageProps {
  wishlistProducts: Product[];
  onProductClick: (productId: string) => void;
  onRemoveFromWishlist: (productId: string) => void;
  onAddToCart: (productId: string) => void;
  onNavigate: (page: Page) => void;
}

export function WishlistPage({
  wishlistProducts,
  onProductClick,
  onRemoveFromWishlist,
  onAddToCart,
  onNavigate,
}: WishlistPageProps) {
  const handleShare = () => {
    toast.success('Wishlist link copied to clipboard!');
  };

  if (wishlistProducts.length === 0) {
    return (
      <div className="container mx-auto px-4 py-16">
        <div className="max-w-md mx-auto text-center">
          <div className="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
            <Heart className="h-12 w-12 text-gray-400" />
          </div>
          <h1 className="text-3xl mb-2">Your Wishlist is Empty</h1>
          <p className="text-gray-600 mb-8">
            Save your favorite items to your wishlist and shop them later
          </p>
          <Button onClick={() => onNavigate('shop')}>
            Start Shopping
          </Button>
        </div>
      </div>
    );
  }

  return (
    <div className="container mx-auto px-4 py-8">
      {/* Header */}
      <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
          <h1 className="text-4xl mb-2">My Wishlist</h1>
          <p className="text-gray-600">
            {wishlistProducts.length} {wishlistProducts.length === 1 ? 'item' : 'items'}
          </p>
        </div>
        <Button variant="outline" onClick={handleShare}>
          <Share2 className="h-4 w-4 mr-2" />
          Share Wishlist
        </Button>
      </div>

      {/* Wishlist Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        {wishlistProducts.map((product) => (
          <Card key={product.id} className="overflow-hidden group">
            {/* Product Image */}
            <div className="relative aspect-square overflow-hidden bg-gray-100">
              <img
                src={product.image}
                alt={product.name}
                className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 cursor-pointer"
                onClick={() => onProductClick(product.id)}
              />
              
              {/* Remove Button */}
              <button
                onClick={() => onRemoveFromWishlist(product.id)}
                className="absolute top-3 right-3 bg-white p-2 rounded-full shadow-lg hover:bg-red-50 transition-colors"
                title="Remove from wishlist"
              >
                <Trash2 className="h-4 w-4 text-red-600" />
              </button>

              {/* Stock Badge */}
              {!product.inStock && (
                <Badge className="absolute top-3 left-3 bg-red-600">
                  Out of Stock
                </Badge>
              )}

              {/* Customizable Badge */}
              {product.isCustomizable && (
                <Badge className="absolute bottom-3 left-3 bg-blue-600">
                  Customizable
                </Badge>
              )}
            </div>

            {/* Product Info */}
            <div className="p-4">
              <h3
                className="mb-1 line-clamp-1 cursor-pointer hover:underline"
                onClick={() => onProductClick(product.id)}
              >
                {product.name}
              </h3>
              <p className="text-gray-600 text-sm mb-2 line-clamp-2">
                {product.description}
              </p>

              {/* Rating */}
              <div className="flex items-center gap-2 mb-3">
                <div className="flex items-center gap-1">
                  <span className="text-yellow-500">★</span>
                  <span className="text-sm">{product.rating}</span>
                </div>
                <span className="text-sm text-gray-500">
                  ({product.reviews} reviews)
                </span>
              </div>

              {/* Price */}
              <p className="text-2xl mb-4">${product.price.toFixed(2)}</p>

              {/* Actions */}
              <div className="flex gap-2">
                {product.inStock ? (
                  <Button
                    onClick={() => onAddToCart(product.id)}
                    className="flex-1"
                  >
                    <ShoppingCart className="h-4 w-4 mr-2" />
                    Add to Cart
                  </Button>
                ) : (
                  <Button variant="outline" className="flex-1" disabled>
                    Out of Stock
                  </Button>
                )}
              </div>
            </div>
          </Card>
        ))}
      </div>

      {/* Action Buttons */}
      <div className="mt-8 flex flex-col sm:flex-row gap-4">
        <Button
          variant="outline"
          onClick={() => {
            wishlistProducts.forEach(p => {
              if (p.inStock) onAddToCart(p.id);
            });
          }}
          className="flex-1"
        >
          Add All to Cart
        </Button>
        <Button
          onClick={() => onNavigate('shop')}
          variant="outline"
          className="flex-1"
        >
          Continue Shopping
        </Button>
      </div>
    </div>
  );
}
