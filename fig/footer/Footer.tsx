import React from 'react';
import { Instagram, Twitter, Facebook } from 'lucide-react';
import './footer.css';

interface FooterProps {
  onNavigate: (page: string) => void;
}

export const Footer: React.FC<FooterProps> = ({ onNavigate }) => {
  return (
    <footer className="footer-main">
      {/* Main Footer */}
      <div className="footer-container">
        <div className="footer-grid">
          {/* Brand */}
          <div className="footer-brand">
            <div className="footer-brand-logo">
              <div className="footer-logo-icon">
                <span className="footer-logo-text">ST</span>
              </div>
              <span className="footer-brand-name">StreetStyle</span>
            </div>
            <p className="footer-brand-description">
              Premium streetwear essentials for the modern lifestyle. Quality you can feel.
            </p>
            <div className="footer-social">
              <button className="footer-social-btn">
                <Instagram className="footer-social-icon" />
              </button>
              <button className="footer-social-btn">
                <Twitter className="footer-social-icon" />
              </button>
              <button className="footer-social-btn">
                <Facebook className="footer-social-icon" />
              </button>
            </div>
          </div>

          {/* Links */}
          <div className="footer-links-grid">
            {/* Shop */}
            <div className="footer-links-column">
              <h4 className="footer-links-title">Shop</h4>
              <ul className="footer-links-list">
                <li>
                  <button onClick={() => onNavigate('products')} className="footer-link">
                    T-Shirts
                  </button>
                </li>
                <li>
                  <button onClick={() => onNavigate('hoodies')} className="footer-link">
                    Hoodies
                  </button>
                </li>
                <li>
                  <button onClick={() => onNavigate('new-arrivals')} className="footer-link">
                    New Arrivals
                  </button>
                </li>
                <li>
                  <button onClick={() => onNavigate('sale')} className="footer-link">
                    Sale
                  </button>
                </li>
              </ul>
            </div>

            {/* Help */}
            <div className="footer-links-column">
              <h4 className="footer-links-title">Help</h4>
              <ul className="footer-links-list">
                <li>
                  <button onClick={() => onNavigate('support')} className="footer-link">
                    Support Center
                  </button>
                </li>
                <li>
                  <button onClick={() => onNavigate('contact')} className="footer-link">
                    Contact Us
                  </button>
                </li>
                <li>
                  <button onClick={() => onNavigate('shipping')} className="footer-link">
                    Shipping
                  </button>
                </li>
                <li>
                  <button onClick={() => onNavigate('track-order')} className="footer-link">
                    Track Order
                  </button>
                </li>
                <li>
                  <button onClick={() => onNavigate('support')} className="footer-link">
                    FAQ
                  </button>
                </li>
              </ul>
            </div>

            {/* Company */}
            <div className="footer-links-column">
              <h4 className="footer-links-title">Company</h4>
              <ul className="footer-links-list">
                <li>
                  <button onClick={() => onNavigate('about')} className="footer-link">
                    About
                  </button>
                </li>
                <li>
                  <button onClick={() => onNavigate('privacy')} className="footer-link">
                    Privacy
                  </button>
                </li>
                <li>
                  <button onClick={() => onNavigate('terms')} className="footer-link">
                    Terms
                  </button>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      {/* Bottom Bar */}
      <div className="footer-bottom">
        <div className="footer-bottom-container">
          <div className="footer-bottom-content">
            <p className="footer-copyright">
              &copy; {new Date().getFullYear()} StreetStyle. All rights reserved.
            </p>
            <div className="footer-payments">
              <span className="footer-payments-label">Secure payments via</span>
              <div className="footer-payment-badges">
                <div className="footer-payment-badge">VISA</div>
                <div className="footer-payment-badge">MASTERCARD</div>
                <div className="footer-payment-badge">PAYPAL</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
};
