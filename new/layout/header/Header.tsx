import { ShoppingCart, User, Menu, Search, Heart } from 'lucide-react';
import { Button } from '../../ui/button';
import { Badge } from '../../ui/badge';
import { Page } from '../../../lib/types';
import './styles.css';

interface HeaderProps {
  currentPage: Page;
  onNavigate: (page: Page) => void;
  cartItemsCount: number;
  wishlistCount?: number;
  onMobileMenuToggle: () => void;
  currentUser: 'customer' | 'admin' | null;
  isAuthenticated?: boolean;
}

export function Header({ 
  currentPage, 
  onNavigate, 
  cartItemsCount, 
  wishlistCount = 0,
  onMobileMenuToggle, 
  currentUser,
  isAuthenticated = false
}: HeaderProps) {
  return (
    <header className="header-main">
      <div className="header-container">
        <div className="header-content">
          {/* Mobile Menu Button */}
          <Button
            variant="ghost"
            size="icon"
            className="header-mobile-menu-btn"
            onClick={onMobileMenuToggle}
          >
            <Menu className="h-6 w-6" />
          </Button>

          {/* Logo */}
          <button 
            onClick={() => onNavigate('home')}
            className="header-logo"
          >
            FASHION<span className="header-logo-accent">MEN</span>
          </button>

          {/* Desktop Navigation */}
          <nav className="header-nav">
            <button
              onClick={() => onNavigate('home')}
              className={`header-nav-link ${
                currentPage === 'home' ? 'header-nav-link-active' : ''
              }`}
            >
              Home
            </button>
            <button
              onClick={() => onNavigate('shop')}
              className={`header-nav-link ${
                currentPage === 'shop' ? 'header-nav-link-active' : ''
              }`}
            >
              Shop
            </button>
            <button
              onClick={() => onNavigate('about')}
              className={`header-nav-link ${
                currentPage === 'about' ? 'header-nav-link-active' : ''
              }`}
            >
              About
            </button>
            <button
              onClick={() => onNavigate('contact')}
              className={`header-nav-link ${
                currentPage === 'contact' ? 'header-nav-link-active' : ''
              }`}
            >
              Contact
            </button>
            <button
              onClick={() => onNavigate('faq')}
              className={`header-nav-link ${
                currentPage === 'faq' ? 'header-nav-link-active' : ''
              }`}
            >
              FAQ
            </button>
            {currentUser === 'admin' && (
              <button
                onClick={() => onNavigate('admin-dashboard')}
                className={`header-nav-link ${
                  currentPage.startsWith('admin') ? 'header-nav-link-active' : ''
                }`}
              >
                Admin
              </button>
            )}
          </nav>

          {/* Actions */}
          <div className="header-actions">
            <Button 
              variant="ghost" 
              size="icon" 
              className="header-action-btn header-action-search"
              onClick={() => onNavigate('search')}
            >
              <Search className="h-5 w-5" />
            </Button>
            
            <Button
              variant="ghost"
              size="icon"
              className="header-action-btn"
              onClick={() => {
                if (isAuthenticated) {
                  onNavigate(currentUser === 'admin' ? 'admin-dashboard' : 'user-dashboard');
                } else {
                  onNavigate('login');
                }
              }}
            >
              <User className="h-5 w-5" />
            </Button>

            <Button
              variant="ghost"
              size="icon"
              className="header-action-btn header-wishlist-btn"
              onClick={() => onNavigate('wishlist')}
            >
              <Heart className="h-5 w-5" />
              {wishlistCount > 0 && (
                <Badge className="header-badge">
                  {wishlistCount}
                </Badge>
              )}
            </Button>

            <Button
              variant="ghost"
              size="icon"
              className="header-action-btn header-cart-btn"
              onClick={() => onNavigate('cart')}
            >
              <ShoppingCart className="h-5 w-5" />
              {cartItemsCount > 0 && (
                <Badge className="header-badge">
                  {cartItemsCount}
                </Badge>
              )}
            </Button>
          </div>
        </div>
      </div>
    </header>
  );
}
