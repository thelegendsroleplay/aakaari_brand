import React from 'react';
import { ProductCarousel } from '../../components/ProductCarousel';
import { Product } from '../../types';

interface NewArrivalsProps {
  products: Product[];
  onNavigate: (page: string) => void;
  onViewProduct: (product: Product) => void;
}

export const NewArrivals: React.FC<NewArrivalsProps> = ({ 
  products, 
  onNavigate, 
  onViewProduct 
}) => {
  return (
    <section className="products-section arrivals-section">
      <div className="page-container">
        <div className="section-title-wrapper">
          <h2 className="section-main-title">New Arrivals</h2>
          <button 
            className="section-view-link"
            onClick={() => onNavigate('new-arrivals')}
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
