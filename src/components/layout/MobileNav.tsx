import { X, Home, ShoppingBag, User, Settings, Info, Mail, HelpCircle } from 'lucide-react';
import { Button } from '../ui/button';
import { Page } from '../../lib/types';

interface MobileNavProps {
  isOpen: boolean;
  onClose: () => void;
  onNavigate: (page: Page) => void;
  currentUser: 'customer' | 'admin' | null;
}

export function MobileNav({ isOpen, onClose, onNavigate, currentUser }: MobileNavProps) {
  if (!isOpen) return null;

  const handleNavigation = (page: Page) => {
    onNavigate(page);
    onClose();
  };

  return (
    <>
      {/* Backdrop */}
      <div
        className="fixed inset-0 bg-black/50 z-40 md:hidden"
        onClick={onClose}
      />

      {/* Sidebar */}
      <div className="fixed top-0 left-0 h-full w-64 bg-white z-50 shadow-xl md:hidden overflow-y-auto">
        <div className="flex items-center justify-between p-4 border-b">
          <span className="tracking-wider">MENU</span>
          <Button variant="ghost" size="icon" onClick={onClose}>
            <X className="h-6 w-6" />
          </Button>
        </div>

        <nav className="p-4 space-y-2">
          <button
            onClick={() => handleNavigation('home')}
            className="flex items-center gap-3 w-full p-3 rounded-lg hover:bg-gray-100 transition-colors"
          >
            <Home className="h-5 w-5" />
            <span>Home</span>
          </button>

          <button
            onClick={() => handleNavigation('shop')}
            className="flex items-center gap-3 w-full p-3 rounded-lg hover:bg-gray-100 transition-colors"
          >
            <ShoppingBag className="h-5 w-5" />
            <span>Shop</span>
          </button>

          <div className="border-t border-gray-200 my-2" />

          <button
            onClick={() => handleNavigation('about')}
            className="flex items-center gap-3 w-full p-3 rounded-lg hover:bg-gray-100 transition-colors"
          >
            <Info className="h-5 w-5" />
            <span>About</span>
          </button>

          <button
            onClick={() => handleNavigation('contact')}
            className="flex items-center gap-3 w-full p-3 rounded-lg hover:bg-gray-100 transition-colors"
          >
            <Mail className="h-5 w-5" />
            <span>Contact</span>
          </button>

          <button
            onClick={() => handleNavigation('faq')}
            className="flex items-center gap-3 w-full p-3 rounded-lg hover:bg-gray-100 transition-colors"
          >
            <HelpCircle className="h-5 w-5" />
            <span>FAQ</span>
          </button>

          <div className="border-t border-gray-200 my-2" />

          <button
            onClick={() => handleNavigation(currentUser === 'admin' ? 'admin-dashboard' : 'user-dashboard')}
            className="flex items-center gap-3 w-full p-3 rounded-lg hover:bg-gray-100 transition-colors"
          >
            <User className="h-5 w-5" />
            <span>{currentUser === 'admin' ? 'Admin Dashboard' : 'My Account'}</span>
          </button>

          {currentUser === 'admin' && (
            <button
              onClick={() => handleNavigation('admin-dashboard')}
              className="flex items-center gap-3 w-full p-3 rounded-lg hover:bg-gray-100 transition-colors"
            >
              <Settings className="h-5 w-5" />
              <span>Admin Panel</span>
            </button>
          )}
        </nav>
      </div>
    </>
  );
}
