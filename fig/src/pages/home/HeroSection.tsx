import React from 'react';
import { Button } from '../../components/ui/button';

interface HeroSectionProps {
  onNavigate: (page: string) => void;
}

export const HeroSection: React.FC<HeroSectionProps> = ({ onNavigate }) => {
  return (
    <section className="hero-banner">
      <div className="hero-image-container">
        <img
          src="https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=1200&q=80"
          alt="New Collection"
          className="hero-banner-image"
        />
        <div className="hero-overlay">
          <div className="hero-content-wrapper">
            <div className="hero-text-content">
              <div className="hero-tag">NEW ARRIVAL</div>
              <h1 className="hero-main-title">
                Premium Streetwear Collection
              </h1>
              <p className="hero-main-subtitle">
                Discover our latest collection of premium t-shirts and hoodies
              </p>
              <div className="hero-cta-group">
                <Button 
                  size="lg"
                  onClick={() => onNavigate('products')}
                  className="hero-cta-button"
                >
                  Shop Now
                </Button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};
