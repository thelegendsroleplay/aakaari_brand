import React from 'react';
import { Truck, RotateCcw, Shield, Award } from 'lucide-react';

interface ProductTrustIndicatorsProps {
  stock: number;
}

export const ProductTrustIndicators: React.FC<ProductTrustIndicatorsProps> = ({ stock }) => {
  const trustIndicators = [
    {
      icon: Truck,
      title: 'Free Shipping',
      description: 'On orders over $50',
    },
    {
      icon: RotateCcw,
      title: 'Easy Returns',
      description: '30-day return policy',
    },
    {
      icon: Shield,
      title: 'Secure Checkout',
      description: '100% SSL encrypted',
    },
    {
      icon: Award,
      title: '2-Year Warranty',
      description: 'Manufacturer guarantee',
    },
  ];

  return (
    <div className="trust-indicators">
      <div className="trust-grid">
        {trustIndicators.map((indicator, index) => {
          const IconComponent = indicator.icon;
          return (
            <div key={index} className="trust-item">
              <div className="trust-icon">
                <IconComponent size={24} />
              </div>
              <div className="trust-content">
                <h4 className="trust-title">{indicator.title}</h4>
                <p className="trust-description">{indicator.description}</p>
              </div>
            </div>
          );
        })}
      </div>

      {/* Additional Info */}
      <div className="shipping-info">
        <div className="info-item">
          <span className="info-label">Seller:</span>
          <span className="info-value">Official Aakaari Store</span>
        </div>
        <div className="info-item">
          <span className="info-label">Ships from:</span>
          <span className="info-value">United States</span>
        </div>
        <div className="info-item">
          <span className="info-label">Delivery:</span>
          <span className="info-value">2-5 business days</span>
        </div>
        {stock > 0 && (
          <div className="info-item in-stock-badge">
            <span className="badge-text">✓ In Stock - Order now!</span>
          </div>
        )}
      </div>
    </div>
  );
};
