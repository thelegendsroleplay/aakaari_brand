import React, { useState } from 'react';
import { Heart, ShoppingCart, Star } from 'lucide-react';
import { Product } from '../types';
import { useCart } from '../contexts/CartContext';
import { useWishlist } from '../contexts/WishlistContext';
import { Button } from './ui/button';
import { Badge } from './ui/badge';

interface ProductCardProps {
  product: Product;
  onViewDetails: (product: Product) => void;
}

export const ProductCard: React.FC<ProductCardProps> = ({ product, onViewDetails }) => {
  const { addToCart } = useCart();
  const { addToWishlist, removeFromWishlist, isInWishlist } = useWishlist();
  const [imageIndex, setImageIndex] = useState(0);
  const inWishlist = isInWishlist(product.id);

  const handleAddToCart = (e: React.MouseEvent) => {
    e.stopPropagation();
    addToCart(product, product.sizes[0], product.colors[0], 1);
  };

  const handleToggleWishlist = (e: React.MouseEvent) => {
    e.stopPropagation();
    if (inWishlist) {
      removeFromWishlist(product.id);
    } else {
      addToWishlist(product);
    }
  };

  const discountPercentage = product.salePrice
    ? Math.round(((product.price - product.salePrice) / product.price) * 100)
    : 0;

  return (
    <div className="group relative bg-white rounded-lg overflow-hidden cursor-pointer" onClick={() => onViewDetails(product)}>
      {/* Image */}
      <div
        className="relative aspect-[3/4] overflow-hidden bg-gray-50"
        onMouseEnter={() => setImageIndex(1)}
        onMouseLeave={() => setImageIndex(0)}
      >
        <img
          src={product.images[imageIndex] || product.images[0]}
          alt={product.name}
          className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
        />
        
        {/* Badges - Minimal */}
        <div className="absolute top-3 left-3 flex flex-col gap-1.5">
          {product.salePrice && (
            <Badge variant="destructive" className="px-2 py-0.5 text-xs">
              -{discountPercentage}%
            </Badge>
          )}
          {product.newArrival && (
            <Badge className="px-2 py-0.5 text-xs bg-black">New</Badge>
          )}
        </div>

        {/* Wishlist Button - Cleaner */}
        <Button
          variant="ghost"
          size="icon"
          className={`absolute top-3 right-3 bg-white/90 backdrop-blur-sm hover:bg-white h-8 w-8 ${
            inWishlist ? 'text-red-500' : 'text-gray-700'
          }`}
          onClick={handleToggleWishlist}
        >
          <Heart className={`w-4 h-4 ${inWishlist ? 'fill-current' : ''}`} />
        </Button>

        {/* Add to Cart - Minimal Overlay */}
        <div className="absolute bottom-0 left-0 right-0 p-3 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
          <Button
            className="w-full bg-black hover:bg-gray-900 h-9 text-sm"
            size="sm"
            onClick={handleAddToCart}
          >
            <ShoppingCart className="w-4 h-4 mr-2" />
            Add to Cart
          </Button>
        </div>
      </div>

      {/* Details - Clean */}
      <div className="p-3">
        <p className="text-xs text-gray-500 mb-1">{product.category}</p>
        <h3 className="text-sm line-clamp-1 mb-1.5 text-gray-900">{product.name}</h3>

        {/* Rating - Minimal */}
        <div className="flex items-center gap-1.5 mb-2">
          <div className="flex gap-0.5">
            {[...Array(5)].map((_, i) => (
              <Star
                key={i}
                className={`w-3 h-3 ${
                  i < Math.floor(product.rating)
                    ? 'text-black fill-current'
                    : 'text-gray-300'
                }`}
              />
            ))}
          </div>
          <span className="text-xs text-gray-500">({product.reviewCount})</span>
        </div>

        {/* Price - Clean */}
        <div className="flex items-center gap-2">
          {product.salePrice ? (
            <>
              <span className="text-sm text-gray-900">${product.salePrice.toFixed(2)}</span>
              <span className="text-xs text-gray-400 line-through">${product.price.toFixed(2)}</span>
            </>
          ) : (
            <span className="text-sm text-gray-900">${product.price.toFixed(2)}</span>
          )}
        </div>
      </div>
    </div>
  );
};
