import { useState, useEffect } from 'react';
import { Input } from '../ui/input';
import { Button } from '../ui/button';
import { Badge } from '../ui/badge';
import { Product } from '../../lib/types';
import { ProductCard } from '../shop/ProductCard';
import { Search, X, TrendingUp } from 'lucide-react';

interface SearchPageProps {
  products: Product[];
  onProductClick: (productId: string) => void;
  onAddToWishlist?: (productId: string) => void;
  wishlistIds?: string[];
  initialQuery?: string;
}

export function SearchPage({ 
  products, 
  onProductClick, 
  onAddToWishlist,
  wishlistIds = [],
  initialQuery = '' 
}: SearchPageProps) {
  const [searchQuery, setSearchQuery] = useState(initialQuery);
  const [searchResults, setSearchResults] = useState<Product[]>([]);
  const [recentSearches, setRecentSearches] = useState<string[]>([
    'Denim Jacket',
    'Cotton Shirt',
    'Designer Sneakers',
  ]);

  const trendingSearches = [
    'Winter Collection',
    'Formal Wear',
    'Casual Shirts',
    'Premium Watches',
    'Leather Shoes',
  ];

  useEffect(() => {
    if (searchQuery.trim()) {
      handleSearch(searchQuery);
    } else {
      setSearchResults([]);
    }
  }, [searchQuery]);

  const handleSearch = (query: string) => {
    const lowerQuery = query.toLowerCase();
    const results = products.filter(
      (product) =>
        product.name.toLowerCase().includes(lowerQuery) ||
        product.description.toLowerCase().includes(lowerQuery) ||
        product.category.toLowerCase().includes(lowerQuery)
    );
    setSearchResults(results);

    // Add to recent searches
    if (query.trim() && !recentSearches.includes(query)) {
      setRecentSearches([query, ...recentSearches.slice(0, 4)]);
    }
  };

  const handleQuickSearch = (query: string) => {
    setSearchQuery(query);
  };

  const clearSearch = () => {
    setSearchQuery('');
    setSearchResults([]);
  };

  return (
    <div className="container mx-auto px-4 py-8">
      {/* Search Bar */}
      <div className="max-w-2xl mx-auto mb-8">
        <div className="relative">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
          <Input
            type="text"
            placeholder="Search for products..."
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            className="pl-12 pr-12 py-6 text-lg"
            autoFocus
          />
          {searchQuery && (
            <button
              onClick={clearSearch}
              className="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
            >
              <X className="h-5 w-5" />
            </button>
          )}
        </div>
      </div>

      {/* Search Results */}
      {searchQuery.trim() ? (
        <div>
          <div className="mb-6">
            <h2 className="text-2xl mb-2">
              Search Results for "{searchQuery}"
            </h2>
            <p className="text-gray-600">
              {searchResults.length} {searchResults.length === 1 ? 'product' : 'products'} found
            </p>
          </div>

          {searchResults.length > 0 ? (
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
              {searchResults.map((product) => (
                <ProductCard
                  key={product.id}
                  product={product}
                  onClick={() => onProductClick(product.id)}
                  onAddToWishlist={onAddToWishlist}
                  isInWishlist={wishlistIds.includes(product.id)}
                />
              ))}
            </div>
          ) : (
            <div className="text-center py-12">
              <div className="text-gray-400 mb-4">
                <Search className="h-16 w-16 mx-auto" />
              </div>
              <h3 className="text-xl mb-2">No products found</h3>
              <p className="text-gray-600 mb-6">
                Try different keywords or browse our categories
              </p>
              <Button variant="outline">Browse All Products</Button>
            </div>
          )}
        </div>
      ) : (
        <div className="max-w-3xl mx-auto">
          {/* Recent Searches */}
          {recentSearches.length > 0 && (
            <div className="mb-8">
              <h3 className="mb-4">Recent Searches</h3>
              <div className="flex flex-wrap gap-2">
                {recentSearches.map((search, idx) => (
                  <Badge
                    key={idx}
                    variant="outline"
                    className="cursor-pointer hover:bg-gray-100 px-4 py-2"
                    onClick={() => handleQuickSearch(search)}
                  >
                    <Search className="h-3 w-3 mr-2" />
                    {search}
                  </Badge>
                ))}
              </div>
            </div>
          )}

          {/* Trending Searches */}
          <div className="mb-8">
            <div className="flex items-center gap-2 mb-4">
              <TrendingUp className="h-5 w-5" />
              <h3>Trending Searches</h3>
            </div>
            <div className="flex flex-wrap gap-2">
              {trendingSearches.map((search, idx) => (
                <Badge
                  key={idx}
                  className="cursor-pointer hover:bg-gray-800 px-4 py-2"
                  onClick={() => handleQuickSearch(search)}
                >
                  {search}
                </Badge>
              ))}
            </div>
          </div>

          {/* Popular Categories */}
          <div>
            <h3 className="mb-4">Popular Categories</h3>
            <div className="grid grid-cols-2 md:grid-cols-3 gap-4">
              {['Jackets', 'Shirts', 'Pants', 'Shoes', 'Accessories', 'Watches'].map(
                (category) => (
                  <button
                    key={category}
                    onClick={() => handleQuickSearch(category)}
                    className="p-4 border rounded-lg hover:bg-gray-50 transition-colors text-left"
                  >
                    <p>{category}</p>
                    <p className="text-sm text-gray-600 mt-1">
                      {products.filter((p) => p.category === category).length} items
                    </p>
                  </button>
                )
              )}
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
