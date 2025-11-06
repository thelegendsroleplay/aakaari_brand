import { useState } from 'react';
import { Product } from '../../../lib/types';
import { ProductCard } from '../../../components/shop/ProductCard';
import { ProductFilters } from '../../../components/shop/ProductFilters';
import { Button } from '../../../components/ui/button';
import { SlidersHorizontal } from 'lucide-react';
import { Sheet, SheetContent, SheetTrigger } from '../../../components/ui/sheet';

interface ShopPageProps {
  products: Product[];
  onProductClick: (productId: string) => void;
  onAddToWishlist?: (productId: string) => void;
  onQuickView?: (productId: string) => void;
  wishlistIds?: string[];
}

export function ShopPage({ 
  products, 
  onProductClick,
  onAddToWishlist,
  onQuickView,
  wishlistIds = []
}: ShopPageProps) {
  const [sortBy, setSortBy] = useState('featured');

  return (
    <div className="container mx-auto px-4 py-8">
      <div className="mb-8">
        <h1 className="text-3xl md:text-4xl mb-4">Shop All</h1>
        <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <p className="text-gray-600">{products.length} products</p>
          
          <div className="flex gap-2 w-full sm:w-auto">
            {/* Mobile Filter */}
            <Sheet>
              <SheetTrigger asChild>
                <Button variant="outline" className="flex-1 sm:hidden">
                  <SlidersHorizontal className="h-4 w-4 mr-2" />
                  Filters
                </Button>
              </SheetTrigger>
              <SheetContent side="left" className="w-80">
                <div className="mt-6">
                  <ProductFilters onFilterChange={() => {}} />
                </div>
              </SheetContent>
            </Sheet>

            {/* Sort Dropdown */}
            <select
              value={sortBy}
              onChange={(e) => setSortBy(e.target.value)}
              className="flex-1 sm:flex-none px-4 py-2 border border-gray-300 rounded-md"
            >
              <option value="featured">Featured</option>
              <option value="price-low">Price: Low to High</option>
              <option value="price-high">Price: High to Low</option>
              <option value="newest">Newest</option>
              <option value="rating">Top Rated</option>
            </select>
          </div>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {/* Desktop Filters */}
        <div className="hidden lg:block">
          <ProductFilters onFilterChange={() => {}} />
        </div>

        {/* Product Grid */}
        <div className="lg:col-span-3">
          <div className="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
            {products.map((product) => (
              <ProductCard
                key={product.id}
                product={product}
                onClick={() => onProductClick(product.id)}
                onAddToWishlist={onAddToWishlist}
                onQuickView={onQuickView}
                isInWishlist={wishlistIds.includes(product.id)}
              />
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
