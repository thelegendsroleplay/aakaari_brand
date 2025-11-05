import { Star, Sparkles, Heart, Eye } from 'lucide-react';
import { Card } from '../ui/card';
import { Badge } from '../ui/badge';
import { Button } from '../ui/button';
import { Product } from '../../lib/types';
import { ImageWithFallback } from '../figma/ImageWithFallback';

interface ProductCardProps {
  product: Product;
  onClick: () => void;
  onAddToWishlist?: (productId: string) => void;
  onQuickView?: (productId: string) => void;
  isInWishlist?: boolean;
}

export function ProductCard({ 
  product, 
  onClick, 
  onAddToWishlist,
  onQuickView,
  isInWishlist = false 
}: ProductCardProps) {
  return (
    <Card 
      className="group overflow-hidden border-none shadow-md hover:shadow-xl transition-all"
    >
      <div className="relative aspect-square overflow-hidden bg-gray-100">
        <ImageWithFallback
          src={product.image}
          alt={product.name}
          className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 cursor-pointer"
          onClick={onClick}
        />
        
        {/* Action Buttons - Show on Hover */}
        <div className="absolute top-2 right-2 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
          {onAddToWishlist && (
            <Button
              size="icon"
              variant="secondary"
              className="h-10 w-10 rounded-full shadow-md"
              onClick={(e) => {
                e.stopPropagation();
                onAddToWishlist(product.id);
              }}
            >
              <Heart
                className={`h-5 w-5 ${isInWishlist ? 'fill-red-500 text-red-500' : ''}`}
              />
            </Button>
          )}
          {onQuickView && (
            <Button
              size="icon"
              variant="secondary"
              className="h-10 w-10 rounded-full shadow-md"
              onClick={(e) => {
                e.stopPropagation();
                onQuickView(product.id);
              }}
            >
              <Eye className="h-5 w-5" />
            </Button>
          )}
        </div>
        
        {product.isCustomizable && (
          <Badge className="absolute top-2 left-2 bg-black text-white">
            <Sparkles className="h-3 w-3 mr-1" />
            Customizable
          </Badge>
        )}
        
        {!product.inStock && (
          <div className="absolute inset-0 bg-black/60 flex items-center justify-center">
            <Badge variant="outline" className="bg-white">Out of Stock</Badge>
          </div>
        )}
      </div>

      <div className="p-4 cursor-pointer" onClick={onClick}>
        <h3 className="mb-1 line-clamp-1">{product.name}</h3>
        <p className="text-sm text-gray-600 mb-2">{product.category}</p>
        
        <div className="flex items-center gap-2 mb-2">
          <div className="flex items-center">
            <Star className="h-4 w-4 fill-yellow-400 text-yellow-400" />
            <span className="text-sm ml-1">{product.rating}</span>
          </div>
          <span className="text-sm text-gray-500">({product.reviews})</span>
        </div>
        
        <div className="flex items-center justify-between">
          <span className="text-xl">${product.price.toFixed(2)}</span>
          <div className="flex gap-1">
            {product.colors.slice(0, 3).map((color, index) => (
              <div
                key={index}
                className="w-4 h-4 rounded-full border border-gray-300"
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
      </div>
    </Card>
  );
}
