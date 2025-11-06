import React from 'react';
import { Truck, Shield, RotateCcw } from 'lucide-react';

export const TrustSection: React.FC = () => {
  return (
    <section className="trust-section">
      <div className="page-container">
        <div className="trust-grid">
          <div className="trust-item">
            <div className="trust-icon-box">
              <Truck className="trust-icon" strokeWidth={1.5} />
            </div>
            <div className="trust-text">
              <h4 className="trust-title">Free Shipping</h4>
              <p className="trust-desc">On orders over $75</p>
            </div>
          </div>

          <div className="trust-item">
            <div className="trust-icon-box">
              <Shield className="trust-icon" strokeWidth={1.5} />
            </div>
            <div className="trust-text">
              <h4 className="trust-title">Secure Payment</h4>
              <p className="trust-desc">100% protected</p>
            </div>
          </div>

          <div className="trust-item">
            <div className="trust-icon-box">
              <RotateCcw className="trust-icon" strokeWidth={1.5} />
            </div>
            <div className="trust-text">
              <h4 className="trust-title">Easy Returns</h4>
              <p className="trust-desc">30-day policy</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};
