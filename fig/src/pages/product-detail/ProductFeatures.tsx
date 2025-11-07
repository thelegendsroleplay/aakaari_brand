import React from 'react';
import { Truck, RotateCcw, Shield } from 'lucide-react';

export const ProductFeatures: React.FC = () => {
  return (
    <div className="product-features">
      <div className="feature-item">
        <Truck className="feature-icon" />
        <div>
          <h4>Free Shipping</h4>
          <p>On orders over $75</p>
        </div>
      </div>
      <div className="feature-item">
        <RotateCcw className="feature-icon" />
        <div>
          <h4>Easy Returns</h4>
          <p>30-day return policy</p>
        </div>
      </div>
      <div className="feature-item">
        <Shield className="feature-icon" />
        <div>
          <h4>Secure Payment</h4>
          <p>100% protected</p>
        </div>
      </div>
    </div>
  );
};
