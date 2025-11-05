import { Button } from '../ui/button';
import { Page } from '../../lib/types';
import { Home, Search } from 'lucide-react';

interface NotFoundPageProps {
  onNavigate: (page: Page) => void;
}

export function NotFoundPage({ onNavigate }: NotFoundPageProps) {
  return (
    <div className="container mx-auto px-4 py-16">
      <div className="max-w-2xl mx-auto text-center">
        <h1 className="text-9xl mb-4">404</h1>
        <h2 className="text-4xl mb-4">Page Not Found</h2>
        <p className="text-xl text-gray-600 mb-8">
          Oops! The page you're looking for doesn't exist or has been moved.
        </p>
        
        <div className="flex flex-col sm:flex-row gap-4 justify-center">
          <Button onClick={() => onNavigate('home')} size="lg">
            <Home className="h-5 w-5 mr-2" />
            Go Home
          </Button>
          <Button onClick={() => onNavigate('search')} variant="outline" size="lg">
            <Search className="h-5 w-5 mr-2" />
            Search Products
          </Button>
        </div>

        {/* Suggestions */}
        <div className="mt-12 text-left">
          <h3 className="text-xl mb-4">You might want to:</h3>
          <ul className="space-y-2 text-gray-600">
            <li className="flex items-center gap-2">
              <span>•</span>
              <button 
                onClick={() => onNavigate('shop')}
                className="hover:underline"
              >
                Browse our shop
              </button>
            </li>
            <li className="flex items-center gap-2">
              <span>•</span>
              <button 
                onClick={() => onNavigate('search')}
                className="hover:underline"
              >
                Search for products
              </button>
            </li>
            <li className="flex items-center gap-2">
              <span>•</span>
              <button 
                onClick={() => onNavigate('contact')}
                className="hover:underline"
              >
                Contact our support team
              </button>
            </li>
            <li className="flex items-center gap-2">
              <span>•</span>
              <button 
                onClick={() => onNavigate('home')}
                className="hover:underline"
              >
                Return to homepage
              </button>
            </li>
          </ul>
        </div>
      </div>
    </div>
  );
}
