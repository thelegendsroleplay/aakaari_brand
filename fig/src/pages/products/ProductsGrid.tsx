import React from 'react';
import { Filter } from 'lucide-react';
import { ProductCard } from '../../components/ProductCard';
import { Button } from '../../components/ui/button';
import { Product } from '../../types';

interface ProductsGridProps {
  products: Product[];
  onViewProduct: (product: Product) => void;
  onClearFilters: () => void;
}

export const ProductsGrid: React.FC<ProductsGridProps> = ({
  products,
  onViewProduct,
  onClearFilters,
}) => {
  if (products.length === 0) {
    return (
      <div className="no-products">
        <Filter className="w-16 h-16 text-gray-300 mb-4" />
        <h3>No products found</h3>
        <p>Try adjusting your filters</p>
        <Button onClick={onClearFilters} className="mt-4">
          Clear Filters
        </Button>
      </div>
    );
  }

  return (
    <div className="products-grid">
      {products.map(product => (
        <ProductCard
          key={product.id}
          product={product}
          onViewDetails={onViewProduct}
        />
      ))}
    </div>
  );
};
