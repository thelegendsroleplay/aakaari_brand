import React from 'react';
import { Button } from '../../components/ui/button';

interface PromoBannerProps {
  onNavigate: (page: string) => void;
}

export const PromoBanner: React.FC<PromoBannerProps> = ({ onNavigate }) => {
  return (
    <section className="promo-section">
      <div className="page-container">
        <div className="promo-card">
          <div className="promo-content">
            <div className="promo-badge">Premium</div>
            <h2 className="promo-title">Crafted for Excellence</h2>
            <p className="promo-description">
              Every piece is thoughtfully designed and made with premium materials. 
              Experience comfort that lasts, style that stands out.
            </p>
            
            <div className="promo-features">
              <div className="promo-feature-item">
                <div className="promo-feature-icon">✓</div>
                <span>100% Premium Cotton</span>
              </div>
              <div className="promo-feature-item">
                <div className="promo-feature-icon">✓</div>
                <span>Sustainable Production</span>
              </div>
              <div className="promo-feature-item">
                <div className="promo-feature-icon">✓</div>
                <span>Lifetime Quality Guarantee</span>
              </div>
            </div>

            <Button 
              variant="secondary"
              onClick={() => onNavigate('products')}
              className="promo-button"
            >
              Explore Collection
            </Button>
          </div>
          <div className="promo-image-wrapper">
            <img
              src="https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=600&q=80"
              alt="Premium Collection"
              className="promo-image"
            />
            <div className="promo-image-overlay">
              <div className="promo-quality-badge">
                <span className="quality-badge-label">Premium Quality</span>
                <span className="quality-badge-subtitle">Since 2024</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};
