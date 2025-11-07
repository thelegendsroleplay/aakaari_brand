import React, { useState, useMemo } from 'react';
import { useProducts } from '../../contexts/ProductsContext';
import { Product, FilterOptions } from '../../types';
import { ProductsHeader } from './ProductsHeader';
import { FiltersSidebar } from './FiltersSidebar';
import { ProductsToolbar } from './ProductsToolbar';
import { ProductsGrid } from './ProductsGrid';
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
  }, [filters, pageType, allProducts]);

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

  const handlePriceRangeChange = (range: [number, number]) => {
    setFilters(prev => ({ ...prev, priceRange: range }));
  };

  const handleRatingChange = (rating: number) => {
    setFilters(prev => ({ ...prev, rating }));
  };

  const handleSortChange = (value: string) => {
    setFilters(prev => ({ ...prev, sortBy: value }));
  };

  return (
    <div className="products-page">
      <ProductsHeader pageType={pageType} />

      <div className="products-container">
        <FiltersSidebar
          filters={filters}
          showFilters={showFilters}
          onClearFilters={clearFilters}
          onToggleCategory={toggleCategory}
          onToggleSize={toggleSize}
          onToggleColor={toggleColor}
          onPriceRangeChange={handlePriceRangeChange}
          onRatingChange={handleRatingChange}
        />

        <main className="products-main">
          <ProductsToolbar
            productsCount={filteredProducts.length}
            showFilters={showFilters}
            sortBy={filters.sortBy}
            onToggleFilters={() => setShowFilters(!showFilters)}
            onSortChange={handleSortChange}
          />

          <ProductsGrid
            products={filteredProducts}
            onViewProduct={onViewProduct}
            onClearFilters={clearFilters}
          />
        </main>
      </div>
    </div>
  );
};
