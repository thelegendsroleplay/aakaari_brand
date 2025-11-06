import { useState } from 'react';
import { Facebook, Instagram, Twitter, Mail, Linkedin, Youtube } from 'lucide-react';
import { Input } from '../../ui/input';
import { Button } from '../../ui/button';
import { Page } from '../../../lib/types';
import { toast } from 'sonner@2.0.3';
import './styles.css';

interface FooterProps {
  onNavigate: (page: Page) => void;
}

export function Footer({ onNavigate }: FooterProps) {
  const [email, setEmail] = useState('');

  const handleSubscribe = () => {
    if (email) {
      toast.success('Thank you for subscribing!');
      setEmail('');
    } else {
      toast.error('Please enter a valid email address');
    }
  };

  return (
    <footer className="footer-main">
      <div className="footer-container">
        {/* Main Footer Content */}
        <div className="footer-grid">
          {/* Brand */}
          <div className="footer-column footer-brand">
            <h3 className="footer-logo">FASHIONMEN</h3>
            <p className="footer-description">
              Premium men's fashion for the modern gentleman. Quality craftsmanship and timeless style.
            </p>
            <div className="footer-social">
              <a href="#" className="footer-social-link" aria-label="Facebook">
                <Facebook className="h-5 w-5" />
              </a>
              <a href="#" className="footer-social-link" aria-label="Instagram">
                <Instagram className="h-5 w-5" />
              </a>
              <a href="#" className="footer-social-link" aria-label="Twitter">
                <Twitter className="h-5 w-5" />
              </a>
              <a href="#" className="footer-social-link" aria-label="LinkedIn">
                <Linkedin className="h-5 w-5" />
              </a>
              <a href="#" className="footer-social-link" aria-label="YouTube">
                <Youtube className="h-5 w-5" />
              </a>
            </div>
          </div>

          {/* Quick Links */}
          <div className="footer-column">
            <h4 className="footer-column-title">Quick Links</h4>
            <ul className="footer-links">
              <li>
                <button onClick={() => onNavigate('about')} className="footer-link">
                  About Us
                </button>
              </li>
              <li>
                <button onClick={() => onNavigate('contact')} className="footer-link">
                  Contact
                </button>
              </li>
              <li>
                <button onClick={() => onNavigate('shop')} className="footer-link">
                  Shop
                </button>
              </li>
              <li>
                <button onClick={() => onNavigate('shipping')} className="footer-link">
                  Shipping Info
                </button>
              </li>
              <li>
                <button onClick={() => onNavigate('faq')} className="footer-link">
                  FAQ
                </button>
              </li>
            </ul>
          </div>

          {/* Customer Service */}
          <div className="footer-column">
            <h4 className="footer-column-title">Customer Service</h4>
            <ul className="footer-links">
              <li>
                <button onClick={() => onNavigate('shipping')} className="footer-link">
                  Returns & Exchanges
                </button>
              </li>
              <li>
                <button onClick={() => onNavigate('faq')} className="footer-link">
                  Help & FAQ
                </button>
              </li>
              <li>
                <button onClick={() => onNavigate('privacy')} className="footer-link">
                  Privacy Policy
                </button>
              </li>
              <li>
                <button onClick={() => onNavigate('terms')} className="footer-link">
                  Terms & Conditions
                </button>
              </li>
              <li>
                <button onClick={() => onNavigate('contact')} className="footer-link">
                  Track Order
                </button>
              </li>
            </ul>
          </div>

          {/* Newsletter */}
          <div className="footer-column">
            <h4 className="footer-column-title">Newsletter</h4>
            <p className="footer-newsletter-text">
              Subscribe to get special offers, free giveaways, and exclusive updates.
            </p>
            <div className="footer-newsletter-form">
              <Input 
                type="email" 
                placeholder="Enter your email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                onKeyDown={(e) => e.key === 'Enter' && handleSubscribe()}
                className="footer-newsletter-input"
              />
              <Button 
                variant="outline" 
                className="footer-newsletter-btn"
                onClick={handleSubscribe}
              >
                <Mail className="h-4 w-4" />
              </Button>
            </div>
            <p className="footer-newsletter-privacy">
              We respect your privacy. Unsubscribe anytime.
            </p>
          </div>
        </div>

        {/* Payment Methods */}
        <div className="footer-payment">
          <p className="footer-payment-title">Secure Payment Methods</p>
          <div className="footer-payment-icons">
            <div className="footer-payment-icon">VISA</div>
            <div className="footer-payment-icon">MC</div>
            <div className="footer-payment-icon">AMEX</div>
            <div className="footer-payment-icon">PayPal</div>
            <div className="footer-payment-icon">GPay</div>
          </div>
        </div>

        {/* Bottom Bar */}
        <div className="footer-bottom">
          <p className="footer-copyright">
            &copy; 2025 FASHIONMEN. All rights reserved.
          </p>
          <div className="footer-bottom-links">
            <button onClick={() => onNavigate('privacy')} className="footer-bottom-link">
              Privacy
            </button>
            <span className="footer-divider">•</span>
            <button onClick={() => onNavigate('terms')} className="footer-bottom-link">
              Terms
            </button>
            <span className="footer-divider">•</span>
            <button onClick={() => onNavigate('contact')} className="footer-bottom-link">
              Support
            </button>
          </div>
        </div>
      </div>
    </footer>
  );
}
