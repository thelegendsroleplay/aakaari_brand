import { Product } from '../../lib/types';
import { ProductCard } from '../shop/ProductCard';

interface RecentlyViewedProps {
  products: Product[];
  onProductClick: (productId: string) => void;
  onAddToWishlist?: (productId: string) => void;
  wishlistIds?: string[];
}

export function RecentlyViewed({
  products,
  onProductClick,
  onAddToWishlist,
  wishlistIds = [],
}: RecentlyViewedProps) {
  if (products.length === 0) {
    return null;
  }

  return (
    <div className="py-12 border-t">
      <h2 className="text-2xl mb-6">Recently Viewed</h2>
      
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {products.slice(0, 4).map((product) => (
          <ProductCard
            key={product.id}
            product={product}
            onClick={() => onProductClick(product.id)}
            onAddToWishlist={onAddToWishlist}
            isInWishlist={wishlistIds.includes(product.id)}
          />
        ))}
      </div>
    </div>
  );
}
