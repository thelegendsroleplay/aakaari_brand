import { Product } from '../../lib/types';
import { ProductCard } from '../shop/ProductCard';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import { Button } from '../ui/button';
import { useState } from 'react';

interface RelatedProductsProps {
  products: Product[];
  currentProductId: string;
  onProductClick: (productId: string) => void;
  onAddToWishlist?: (productId: string) => void;
  wishlistIds?: string[];
}

export function RelatedProducts({
  products,
  currentProductId,
  onProductClick,
  onAddToWishlist,
  wishlistIds = [],
}: RelatedProductsProps) {
  const [startIndex, setStartIndex] = useState(0);
  const itemsPerPage = 4;

  // Filter out current product and get related products
  const relatedProducts = products.filter((p) => p.id !== currentProductId).slice(0, 8);

  if (relatedProducts.length === 0) {
    return null;
  }

  const visibleProducts = relatedProducts.slice(startIndex, startIndex + itemsPerPage);
  const canScrollLeft = startIndex > 0;
  const canScrollRight = startIndex + itemsPerPage < relatedProducts.length;

  const scrollLeft = () => {
    setStartIndex(Math.max(0, startIndex - itemsPerPage));
  };

  const scrollRight = () => {
    setStartIndex(Math.min(relatedProducts.length - itemsPerPage, startIndex + itemsPerPage));
  };

  return (
    <div className="py-12 border-t">
      <div className="flex items-center justify-between mb-6">
        <h2 className="text-2xl">You May Also Like</h2>
        
        {relatedProducts.length > itemsPerPage && (
          <div className="flex gap-2">
            <Button
              variant="outline"
              size="icon"
              onClick={scrollLeft}
              disabled={!canScrollLeft}
            >
              <ChevronLeft className="h-4 w-4" />
            </Button>
            <Button
              variant="outline"
              size="icon"
              onClick={scrollRight}
              disabled={!canScrollRight}
            >
              <ChevronRight className="h-4 w-4" />
            </Button>
          </div>
        )}
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {visibleProducts.map((product) => (
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
