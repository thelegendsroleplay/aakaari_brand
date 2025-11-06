import React from 'react';
import { ProductCarousel } from '../../components/ProductCarousel';
import { Product } from '../../types';

interface FeaturedProductsProps {
  products: Product[];
  onNavigate: (page: string) => void;
  onViewProduct: (product: Product) => void;
}

export const FeaturedProducts: React.FC<FeaturedProductsProps> = ({ 
  products, 
  onNavigate, 
  onViewProduct 
}) => {
  return (
    <section className="products-section">
      <div className="page-container">
        <div className="section-title-wrapper">
          <h2 className="section-main-title">Featured Products</h2>
          <button 
            className="section-view-link"
            onClick={() => onNavigate('products')}
          >
            View All
          </button>
        </div>

        <div className="product-carousel-wrapper">
          <ProductCarousel
            products={products}
            onViewDetails={onViewProduct}
          />
        </div>
      </div>
    </section>
  );
};
