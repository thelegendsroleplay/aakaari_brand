import React from 'react';
import { ProductCarousel } from '../../components/ProductCarousel';
import { Product } from '../../types';

interface RelatedProductsProps {
  products: Product[];
  onViewProduct: (product: Product) => void;
}

export const RelatedProducts: React.FC<RelatedProductsProps> = ({ products, onViewProduct }) => {
  if (products.length === 0) return null;

  return (
    <div className="related-section">
      <h2>Related Products</h2>
      <ProductCarousel products={products} onViewDetails={onViewProduct} />
    </div>
  );
};
