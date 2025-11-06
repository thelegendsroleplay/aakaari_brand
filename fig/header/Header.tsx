import React, { useState } from 'react';
import { ShoppingCart, Heart, User, Menu, X } from 'lucide-react';
import { useCart } from '../../contexts/CartContext';
import { useWishlist } from '../../contexts/WishlistContext';
import { useAuth } from '../../contexts/AuthContext';
import { Badge } from '../ui/badge';
import { Button } from '../ui/button';
import './header.css';

interface HeaderProps {
  onNavigate: (page: string) => void;
  currentPage: string;
}

export const Header: React.FC<HeaderProps> = ({ onNavigate, currentPage }) => {
  const { cartCount } = useCart();
  const { wishlistCount } = useWishlist();
  const { isAuthenticated, isAdmin, logout } = useAuth();
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  const menuItems = [
    { label: 'Home', page: 'home' },
    { label: 'T-Shirts', page: 'products' },
    { label: 'Hoodies', page: 'hoodies' },
    { label: 'New Arrivals', page: 'new-arrivals' },
    { label: 'Sale', page: 'sale' },
  ];

  return (
    <header className="header-main">
      {/* Main Header */}
      <div className="header-container">
        <div className="header-content">
          {/* Logo */}
          <button 
            onClick={() => onNavigate('home')} 
            className="header-logo"
          >
            <div className="header-logo-icon">
              <span className="header-logo-text">ST</span>
            </div>
            <span className="header-logo-name">StreetStyle</span>
          </button>

          {/* Desktop Navigation */}
          <nav className="header-nav">
            {menuItems.map(item => (
              <button
                key={item.page}
                onClick={() => onNavigate(item.page)}
                className={`header-nav-link ${
                  currentPage === item.page ? 'header-nav-link-active' : ''
                }`}
              >
                {item.label}
              </button>
            ))}
          </nav>

          {/* Actions */}
          <div className="header-actions">
            {isAuthenticated ? (
              <div className="header-user-menu">
                <Button
                  variant="ghost"
                  size="icon"
                  onClick={() => onNavigate('account')}
                  className="header-action-btn"
                >
                  <User className="w-4 h-4" />
                </Button>
                <div className="header-dropdown">
                  <button
                    onClick={() => onNavigate('account')}
                    className="header-dropdown-item"
                  >
                    Account
                  </button>
                  <button
                    onClick={() => onNavigate('orders')}
                    className="header-dropdown-item"
                  >
                    Orders
                  </button>
                  {isAdmin && (
                    <button
                      onClick={() => onNavigate('admin')}
                      className="header-dropdown-item header-dropdown-admin"
                    >
                      Admin
                    </button>
                  )}
                  <button
                    onClick={logout}
                    className="header-dropdown-item header-dropdown-logout"
                  >
                    Logout
                  </button>
                </div>
              </div>
            ) : (
              <Button 
                variant="ghost" 
                size="icon" 
                onClick={() => onNavigate('auth')} 
                className="header-action-btn"
              >
                <User className="w-4 h-4" />
              </Button>
            )}

            <Button
              variant="ghost"
              size="icon"
              onClick={() => onNavigate('wishlist')}
              className="header-action-btn header-wishlist-btn"
            >
              <Heart className="w-4 h-4" />
              {wishlistCount > 0 && (
                <Badge className="header-badge">
                  {wishlistCount}
                </Badge>
              )}
            </Button>

            <Button
              variant="ghost"
              size="icon"
              onClick={() => onNavigate('cart')}
              className="header-action-btn header-cart-btn"
            >
              <ShoppingCart className="w-4 h-4" />
              {cartCount > 0 && (
                <Badge className="header-badge">
                  {cartCount}
                </Badge>
              )}
            </Button>

            <Button
              variant="ghost"
              size="icon"
              className="header-mobile-toggle"
              onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
            >
              {mobileMenuOpen ? <X className="w-5 h-5" /> : <Menu className="w-5 h-5" />}
            </Button>
          </div>
        </div>
      </div>

      {/* Mobile Menu */}
      {mobileMenuOpen && (
        <nav className="header-mobile-menu">
          <div className="header-mobile-menu-content">
            {menuItems.map(item => (
              <button
                key={item.page}
                onClick={() => {
                  onNavigate(item.page);
                  setMobileMenuOpen(false);
                }}
                className={`header-mobile-menu-item ${
                  currentPage === item.page ? 'header-mobile-menu-item-active' : ''
                }`}
              >
                {item.label}
              </button>
            ))}
            {isAuthenticated && (
              <>
                <div className="header-mobile-divider"></div>
                <button
                  onClick={() => {
                    onNavigate('account');
                    setMobileMenuOpen(false);
                  }}
                  className="header-mobile-menu-item"
                >
                  Account
                </button>
                <button
                  onClick={() => {
                    onNavigate('orders');
                    setMobileMenuOpen(false);
                  }}
                  className="header-mobile-menu-item"
                >
                  Orders
                </button>
                {isAdmin && (
                  <button
                    onClick={() => {
                      onNavigate('admin');
                      setMobileMenuOpen(false);
                    }}
                    className="header-mobile-menu-item header-mobile-menu-admin"
                  >
                    Admin Dashboard
                  </button>
                )}
                <button
                  onClick={() => {
                    logout();
                    setMobileMenuOpen(false);
                  }}
                  className="header-mobile-menu-item header-mobile-menu-logout"
                >
                  Logout
                </button>
              </>
            )}
          </div>
        </nav>
      )}
    </header>
  );
};
