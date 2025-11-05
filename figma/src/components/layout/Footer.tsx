import { useState } from 'react';
import { Facebook, Instagram, Twitter, Mail } from 'lucide-react';
import { Input } from '../ui/input';
import { Button } from '../ui/button';
import { Page } from '../../lib/types';
import { toast } from 'sonner@2.0.3';

interface FooterProps {
  onNavigate: (page: Page) => void;
}

export function Footer({ onNavigate }: FooterProps) {
  const [email, setEmail] = useState('');
  return (
    <footer className="bg-black text-white mt-auto">
      <div className="container mx-auto px-4 py-12">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
          {/* Brand */}
          <div>
            <h3 className="text-xl tracking-wider mb-4">FASHIONMEN</h3>
            <p className="text-gray-400 text-sm">
              Premium men's fashion for the modern gentleman. Quality craftsmanship and timeless style.
            </p>
          </div>

          {/* Quick Links */}
          <div>
            <h4 className="mb-4">Quick Links</h4>
            <ul className="space-y-2 text-sm text-gray-400">
              <li><button onClick={() => onNavigate('about')} className="hover:text-white transition-colors">About Us</button></li>
              <li><button onClick={() => onNavigate('contact')} className="hover:text-white transition-colors">Contact</button></li>
              <li><button onClick={() => onNavigate('shop')} className="hover:text-white transition-colors">Shop</button></li>
              <li><button onClick={() => onNavigate('shipping')} className="hover:text-white transition-colors">Shipping Info</button></li>
            </ul>
          </div>

          {/* Customer Service */}
          <div>
            <h4 className="mb-4">Customer Service</h4>
            <ul className="space-y-2 text-sm text-gray-400">
              <li><button onClick={() => onNavigate('shipping')} className="hover:text-white transition-colors">Returns & Exchanges</button></li>
              <li><button onClick={() => onNavigate('faq')} className="hover:text-white transition-colors">FAQ</button></li>
              <li><button onClick={() => onNavigate('privacy')} className="hover:text-white transition-colors">Privacy Policy</button></li>
              <li><button onClick={() => onNavigate('terms')} className="hover:text-white transition-colors">Terms & Conditions</button></li>
            </ul>
          </div>

          {/* Newsletter */}
          <div>
            <h4 className="mb-4">Newsletter</h4>
            <p className="text-sm text-gray-400 mb-4">
              Subscribe to get special offers and updates.
            </p>
            <div className="flex gap-2 mb-4">
              <Input 
                type="email" 
                placeholder="Your email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                className="bg-white/10 border-white/20 text-white placeholder:text-gray-500"
              />
              <Button 
                variant="outline" 
                className="bg-white text-black hover:bg-gray-100"
                onClick={() => {
                  if (email) {
                    toast.success('Thank you for subscribing!');
                    setEmail('');
                  }
                }}
              >
                <Mail className="h-4 w-4" />
              </Button>
            </div>
            <div className="flex gap-4">
              <a href="#" className="hover:text-gray-400 transition-colors">
                <Facebook className="h-5 w-5" />
              </a>
              <a href="#" className="hover:text-gray-400 transition-colors">
                <Instagram className="h-5 w-5" />
              </a>
              <a href="#" className="hover:text-gray-400 transition-colors">
                <Twitter className="h-5 w-5" />
              </a>
            </div>
          </div>
        </div>

        <div className="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
          <p>&copy; 2025 FASHIONMEN. All rights reserved.</p>
        </div>
      </div>
    </footer>
  );
}
