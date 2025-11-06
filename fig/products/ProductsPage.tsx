import React, { useState, useMemo } from 'react';
import { Filter, X, SlidersHorizontal } from 'lucide-react';
import { ProductCard } from '../../components/ProductCard';
import { Button } from '../../components/ui/button';
import { Checkbox } from '../../components/ui/checkbox';
import { Slider } from '../../components/ui/slider';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../../components/ui/select';
import { useProducts } from '../../contexts/ProductsContext';
import { Product, FilterOptions } from '../../types';
import './products.css';

interface ProductsPageProps {
  onNavigate: (page: string) => void;
  onViewProduct: (product: Product) => void;
  pageType?: string;
}

export const ProductsPage: React.FC<ProductsPageProps> = ({ onNavigate, onViewProduct, pageType = 'products' }) => {
  const { products: allProducts } = useProducts();
  const [showFilters, setShowFilters] = useState(true);
  const [filters, setFilters] = useState<FilterOptions>({
    categories: [],
    priceRange: [0, 1000],
    sizes: [],
    colors: [],
    materials: [],
    rating: 0,
    sortBy: 'popularity'
  });

  // Available filters
  const categories = ['T-Shirts', 'Hoodies'];
  const sizes = ['S', 'M', 'L', 'XL', 'XXL'];
  const colors = ['Black', 'White', 'Gray', 'Navy', 'Olive', 'Beige'];

  // Get page title based on pageType
  const getPageTitle = () => {
    switch (pageType) {
      case 'hoodies':
        return 'Hoodies';
      case 'new-arrivals':
        return 'New Arrivals';
      case 'sale':
        return 'Sale';
      case 'bestsellers':
        return 'Bestsellers';
      default:
        return 'T-Shirts';
    }
  };

  const filteredProducts = useMemo(() => {
    let result = [...allProducts];

    // Filter by page type
    if (pageType === 'hoodies') {
      result = result.filter(p => p.category === 'Hoodies');
    } else if (pageType === 'products') {
      result = result.filter(p => p.category === 'T-Shirts');
    } else if (pageType === 'new-arrivals') {
      result = result.filter(p => p.newArrival);
    } else if (pageType === 'sale') {
      result = result.filter(p => p.salePrice);
    } else if (pageType === 'bestsellers') {
      result = result.filter(p => p.bestseller);
    }

    // Filter by category
    if (filters.categories.length > 0 && !filters.categories.includes('All')) {
      result = result.filter(p => filters.categories.includes(p.category));
    }

    // Filter by price
    result = result.filter(p => {
      const price = p.salePrice || p.price;
      return price >= filters.priceRange[0] && price <= filters.priceRange[1];
    });

    // Filter by size
    if (filters.sizes.length > 0) {
      result = result.filter(p => p.sizes.some(s => filters.sizes.includes(s)));
    }

    // Filter by color
    if (filters.colors.length > 0) {
      result = result.filter(p => p.colors.some(c => filters.colors.includes(c)));
    }

    // Filter by rating
    if (filters.rating > 0) {
      result = result.filter(p => p.rating >= filters.rating);
    }

    // Sort
    switch (filters.sortBy) {
      case 'price-low':
        result.sort((a, b) => (a.salePrice || a.price) - (b.salePrice || b.price));
        break;
      case 'price-high':
        result.sort((a, b) => (b.salePrice || b.price) - (a.salePrice || a.price));
        break;
      case 'newest':
        result.sort((a, b) => (b.newArrival ? 1 : 0) - (a.newArrival ? 1 : 0));
        break;
      case 'rating':
        result.sort((a, b) => b.rating - a.rating);
        break;
      default: // popularity
        result.sort((a, b) => b.reviewCount - a.reviewCount);
    }

    return result;
  }, [filters, pageType]);

  const toggleCategory = (category: string) => {
    setFilters(prev => ({
      ...prev,
      categories: prev.categories.includes(category)
        ? prev.categories.filter(c => c !== category)
        : [...prev.categories, category]
    }));
  };

  const toggleSize = (size: string) => {
    setFilters(prev => ({
      ...prev,
      sizes: prev.sizes.includes(size)
        ? prev.sizes.filter(s => s !== size)
        : [...prev.sizes, size]
    }));
  };

  const toggleColor = (color: string) => {
    setFilters(prev => ({
      ...prev,
      colors: prev.colors.includes(color)
        ? prev.colors.filter(c => c !== color)
        : [...prev.colors, color]
    }));
  };

  const clearFilters = () => {
    setFilters({
      categories: [],
      priceRange: [0, 1000],
      sizes: [],
      colors: [],
      materials: [],
      rating: 0,
      sortBy: 'popularity'
    });
  };

  return (
    <div className="products-page">
      {/* Page Header */}
      <div className="page-header">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 py-8">
          <h1 className="text-3xl">{getPageTitle()}</h1>
          <p className="text-gray-600 mt-2">
            {pageType === 'sale' ? 'Exclusive deals on premium streetwear' : 
             pageType === 'new-arrivals' ? 'The latest drops in streetwear fashion' :
             pageType === 'hoodies' ? 'Cozy hoodies for every season' :
             'Essential tees for your wardrobe'}
          </p>
        </div>
      </div>

      <div className="products-container">
        {/* Sidebar Filters */}
        <aside className={`filters-sidebar ${showFilters ? 'show' : 'hide'}`}>
          <div className="filters-header">
            <h2>Filters</h2>
            <Button variant="ghost" size="sm" onClick={clearFilters}>
              Clear All
            </Button>
          </div>

          {/* Categories */}
          <div className="filter-section">
            <h3>Categories</h3>
            <div className="filter-options">
              {categories.map(category => (
                <label key={category} className="filter-checkbox">
                  <Checkbox
                    checked={filters.categories.includes(category)}
                    onCheckedChange={() => toggleCategory(category)}
                  />
                  <span>{category}</span>
                </label>
              ))}
            </div>
          </div>

          {/* Price Range */}
          <div className="filter-section">
            <h3>Price Range</h3>
            <div className="price-range">
              <Slider
                value={filters.priceRange}
                onValueChange={(value) => setFilters(prev => ({ ...prev, priceRange: value as [number, number] }))}
                min={0}
                max={1000}
                step={10}
              />
              <div className="price-labels">
                <span>${filters.priceRange[0]}</span>
                <span>${filters.priceRange[1]}</span>
              </div>
            </div>
          </div>

          {/* Sizes */}
          <div className="filter-section">
            <h3>Sizes</h3>
            <div className="filter-options">
              {sizes.map(size => (
                <label key={size} className="filter-checkbox">
                  <Checkbox
                    checked={filters.sizes.includes(size)}
                    onCheckedChange={() => toggleSize(size)}
                  />
                  <span>{size}</span>
                </label>
              ))}
            </div>
          </div>

          {/* Colors */}
          <div className="filter-section">
            <h3>Colors</h3>
            <div className="color-options">
              {colors.map(color => (
                <button
                  key={color}
                  className={`color-swatch ${filters.colors.includes(color) ? 'selected' : ''}`}
                  style={{ backgroundColor: color.toLowerCase() }}
                  onClick={() => toggleColor(color)}
                  title={color}
                />
              ))}
            </div>
          </div>

          {/* Rating */}
          <div className="filter-section">
            <h3>Minimum Rating</h3>
            <div className="rating-options">
              {[4, 3, 2, 1].map(rating => (
                <label key={rating} className="filter-checkbox">
                  <Checkbox
                    checked={filters.rating === rating}
                    onCheckedChange={() => setFilters(prev => ({ ...prev, rating }))}
                  />
                  <span>{rating}+ Stars</span>
                </label>
              ))}
            </div>
          </div>
        </aside>

        {/* Main Content */}
        <main className="products-main">
          {/* Toolbar */}
          <div className="products-toolbar">
            <Button
              variant="outline"
              size="sm"
              className="lg:hidden"
              onClick={() => setShowFilters(!showFilters)}
            >
              <SlidersHorizontal className="w-4 h-4 mr-2" />
              {showFilters ? 'Hide' : 'Show'} Filters
            </Button>

            <div className="toolbar-info">
              <p>{filteredProducts.length} Products</p>
            </div>

            <div className="toolbar-sort">
              <span className="sort-label">Sort by:</span>
              <Select
                value={filters.sortBy}
                onValueChange={(value: any) => setFilters(prev => ({ ...prev, sortBy: value }))}
              >
                <SelectTrigger className="w-48">
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="popularity">Popularity</SelectItem>
                  <SelectItem value="price-low">Price: Low to High</SelectItem>
                  <SelectItem value="price-high">Price: High to Low</SelectItem>
                  <SelectItem value="newest">Newest</SelectItem>
                  <SelectItem value="rating">Rating</SelectItem>
                </SelectContent>
              </Select>
            </div>
          </div>

          {/* Products Grid */}
          {filteredProducts.length > 0 ? (
            <div className="products-grid">
              {filteredProducts.map(product => (
                <ProductCard
                  key={product.id}
                  product={product}
                  onViewDetails={onViewProduct}
                />
              ))}
            </div>
          ) : (
            <div className="no-products">
              <Filter className="w-16 h-16 text-gray-300 mb-4" />
              <h3>No products found</h3>
              <p>Try adjusting your filters</p>
              <Button onClick={clearFilters} className="mt-4">
                Clear Filters
              </Button>
            </div>
          )}
        </main>
      </div>
    </div>
  );
};