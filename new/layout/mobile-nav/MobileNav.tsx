import { X, Home, ShoppingBag, User, Settings, Info, Mail, HelpCircle, LogOut, Package, Heart } from 'lucide-react';
import { Button } from '../../ui/button';
import { Page } from '../../../lib/types';
import './styles.css';

interface MobileNavProps {
  isOpen: boolean;
  onClose: () => void;
  onNavigate: (page: Page) => void;
  currentUser: 'customer' | 'admin' | null;
  isAuthenticated?: boolean;
}

export function MobileNav({ isOpen, onClose, onNavigate, currentUser, isAuthenticated = false }: MobileNavProps) {
  if (!isOpen) return null;

  const handleNavigation = (page: Page) => {
    onNavigate(page);
    onClose();
  };

  return (
    <>
      {/* Backdrop */}
      <div
        className="mobile-nav-backdrop"
        onClick={onClose}
      />

      {/* Sidebar */}
      <div className="mobile-nav-sidebar">
        {/* Header */}
        <div className="mobile-nav-header">
          <div className="mobile-nav-logo">
            <span className="mobile-nav-title">FASHIONMEN</span>
            <span className="mobile-nav-subtitle">Menu</span>
          </div>
          <Button variant="ghost" size="icon" onClick={onClose} className="mobile-nav-close">
            <X className="h-6 w-6" />
          </Button>
        </div>

        {/* User Section */}
        {isAuthenticated && (
          <div className="mobile-nav-user">
            <div className="mobile-nav-user-avatar">
              <User className="h-6 w-6" />
            </div>
            <div className="mobile-nav-user-info">
              <p className="mobile-nav-user-name">
                {currentUser === 'admin' ? 'Admin User' : 'Welcome Back'}
              </p>
              <p className="mobile-nav-user-role">
                {currentUser === 'admin' ? 'Administrator' : 'Customer'}
              </p>
            </div>
          </div>
        )}

        {/* Navigation */}
        <nav className="mobile-nav-menu">
          {/* Main Navigation */}
          <div className="mobile-nav-section">
            <p className="mobile-nav-section-title">Navigation</p>
            <button
              onClick={() => handleNavigation('home')}
              className="mobile-nav-link"
            >
              <Home className="h-5 w-5" />
              <span>Home</span>
            </button>

            <button
              onClick={() => handleNavigation('shop')}
              className="mobile-nav-link"
            >
              <ShoppingBag className="h-5 w-5" />
              <span>Shop</span>
            </button>

            <button
              onClick={() => handleNavigation('wishlist')}
              className="mobile-nav-link"
            >
              <Heart className="h-5 w-5" />
              <span>Wishlist</span>
            </button>
          </div>

          {/* User Actions */}
          {isAuthenticated && (
            <div className="mobile-nav-section">
              <p className="mobile-nav-section-title">Account</p>
              <button
                onClick={() => handleNavigation(currentUser === 'admin' ? 'admin-dashboard' : 'user-dashboard')}
                className="mobile-nav-link"
              >
                <User className="h-5 w-5" />
                <span>{currentUser === 'admin' ? 'Dashboard' : 'My Account'}</span>
              </button>

              <button
                onClick={() => handleNavigation('user-dashboard')}
                className="mobile-nav-link"
              >
                <Package className="h-5 w-5" />
                <span>My Orders</span>
              </button>

              {currentUser === 'admin' && (
                <button
                  onClick={() => handleNavigation('admin-dashboard')}
                  className="mobile-nav-link"
                >
                  <Settings className="h-5 w-5" />
                  <span>Admin Panel</span>
                </button>
              )}
            </div>
          )}

          {/* Information */}
          <div className="mobile-nav-section">
            <p className="mobile-nav-section-title">Information</p>
            <button
              onClick={() => handleNavigation('about')}
              className="mobile-nav-link"
            >
              <Info className="h-5 w-5" />
              <span>About Us</span>
            </button>

            <button
              onClick={() => handleNavigation('contact')}
              className="mobile-nav-link"
            >
              <Mail className="h-5 w-5" />
              <span>Contact</span>
            </button>

            <button
              onClick={() => handleNavigation('faq')}
              className="mobile-nav-link"
            >
              <HelpCircle className="h-5 w-5" />
              <span>FAQ</span>
            </button>
          </div>

          {/* Auth Actions */}
          <div className="mobile-nav-section">
            {!isAuthenticated ? (
              <button
                onClick={() => handleNavigation('login')}
                className="mobile-nav-link mobile-nav-link-primary"
              >
                <User className="h-5 w-5" />
                <span>Sign In</span>
              </button>
            ) : (
              <button
                onClick={() => handleNavigation('login')}
                className="mobile-nav-link mobile-nav-link-danger"
              >
                <LogOut className="h-5 w-5" />
                <span>Sign Out</span>
              </button>
            )}
          </div>
        </nav>

        {/* Footer */}
        <div className="mobile-nav-footer">
          <p className="mobile-nav-footer-text">
            &copy; 2025 FASHIONMEN
          </p>
        </div>
      </div>
    </>
  );
}
