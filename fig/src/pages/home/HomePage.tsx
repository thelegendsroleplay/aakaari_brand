import React from 'react';
import { useProducts } from '../../contexts/ProductsContext';
import { Product } from '../../types';
import { HeroSection } from './HeroSection';
import { CategorySection } from './CategorySection';
import { FeaturedProducts } from './FeaturedProducts';
import { PromoBanner } from './PromoBanner';
import { NewArrivals } from './NewArrivals';
import { TrustSection } from './TrustSection';
import './home.css';

interface HomePageProps {
  onNavigate: (page: string, productId?: string) => void;
  onViewProduct: (product: Product) => void;
}

export const HomePage: React.FC<HomePageProps> = ({ onNavigate, onViewProduct }) => {
  const { products } = useProducts();
  const featuredProducts = products.filter(p => p.featured).slice(0, 8);
  const newArrivals = products.filter(p => p.newArrival).slice(0, 8);

  return (
    <div className="home-page">
      <HeroSection onNavigate={onNavigate} />
      <CategorySection onNavigate={onNavigate} />
      <FeaturedProducts 
        products={featuredProducts} 
        onNavigate={onNavigate} 
        onViewProduct={onViewProduct} 
      />
      <PromoBanner onNavigate={onNavigate} />
      <NewArrivals 
        products={newArrivals} 
        onNavigate={onNavigate} 
        onViewProduct={onViewProduct} 
      />
      <TrustSection />
    </div>
  );
};
