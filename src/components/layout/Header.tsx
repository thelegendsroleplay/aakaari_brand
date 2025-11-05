import { ShoppingCart, User, Menu, Search, Heart } from 'lucide-react';
import { Button } from '../ui/button';
import { Badge } from '../ui/badge';
import { Page } from '../../lib/types';

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
    <header className="sticky top-0 z-50 bg-white border-b border-gray-200">
      <div className="container mx-auto px-4">
        <div className="flex items-center justify-between h-16">
          {/* Mobile Menu Button */}
          <Button
            variant="ghost"
            size="icon"
            className="md:hidden"
            onClick={onMobileMenuToggle}
          >
            <Menu className="h-6 w-6" />
          </Button>

          {/* Logo */}
          <button 
            onClick={() => onNavigate('home')}
            className="text-2xl tracking-wider cursor-pointer"
          >
            FASHION<span className="text-gray-400">MEN</span>
          </button>

          {/* Desktop Navigation */}
          <nav className="hidden md:flex items-center gap-8">
            <button
              onClick={() => onNavigate('home')}
              className={`hover:text-gray-600 transition-colors ${
                currentPage === 'home' ? 'border-b-2 border-black pb-1' : ''
              }`}
            >
              Home
            </button>
            <button
              onClick={() => onNavigate('shop')}
              className={`hover:text-gray-600 transition-colors ${
                currentPage === 'shop' ? 'border-b-2 border-black pb-1' : ''
              }`}
            >
              Shop
            </button>
            <button
              onClick={() => onNavigate('about')}
              className={`hover:text-gray-600 transition-colors ${
                currentPage === 'about' ? 'border-b-2 border-black pb-1' : ''
              }`}
            >
              About
            </button>
            <button
              onClick={() => onNavigate('contact')}
              className={`hover:text-gray-600 transition-colors ${
                currentPage === 'contact' ? 'border-b-2 border-black pb-1' : ''
              }`}
            >
              Contact
            </button>
            <button
              onClick={() => onNavigate('faq')}
              className={`hover:text-gray-600 transition-colors ${
                currentPage === 'faq' ? 'border-b-2 border-black pb-1' : ''
              }`}
            >
              FAQ
            </button>
            {currentUser === 'admin' && (
              <button
                onClick={() => onNavigate('admin-dashboard')}
                className={`hover:text-gray-600 transition-colors ${
                  currentPage.startsWith('admin') ? 'border-b-2 border-black pb-1' : ''
                }`}
              >
                Admin
              </button>
            )}
          </nav>

          {/* Actions */}
          <div className="flex items-center gap-2 md:gap-4">
            <Button 
              variant="ghost" 
              size="icon" 
              className="hidden md:flex"
              onClick={() => onNavigate('search')}
            >
              <Search className="h-5 w-5" />
            </Button>
            
            <Button
              variant="ghost"
              size="icon"
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
              className="relative"
              onClick={() => onNavigate('wishlist')}
            >
              <Heart className="h-5 w-5" />
              {wishlistCount > 0 && (
                <Badge className="absolute -top-1 -right-1 h-5 w-5 flex items-center justify-center p-0 text-xs">
                  {wishlistCount}
                </Badge>
              )}
            </Button>

            <Button
              variant="ghost"
              size="icon"
              className="relative"
              onClick={() => onNavigate('cart')}
            >
              <ShoppingCart className="h-5 w-5" />
              {cartItemsCount > 0 && (
                <Badge className="absolute -top-1 -right-1 h-5 w-5 flex items-center justify-center p-0 text-xs">
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
