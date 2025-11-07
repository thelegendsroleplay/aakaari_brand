import React from 'react';
import { Button } from '../../components/ui/button';
import { Checkbox } from '../../components/ui/checkbox';
import { Slider } from '../../components/ui/slider';
import { FilterOptions } from '../../types';

interface FiltersSidebarProps {
  filters: FilterOptions;
  showFilters: boolean;
  onClearFilters: () => void;
  onToggleCategory: (category: string) => void;
  onToggleSize: (size: string) => void;
  onToggleColor: (color: string) => void;
  onPriceRangeChange: (range: [number, number]) => void;
  onRatingChange: (rating: number) => void;
}

export const FiltersSidebar: React.FC<FiltersSidebarProps> = ({
  filters,
  showFilters,
  onClearFilters,
  onToggleCategory,
  onToggleSize,
  onToggleColor,
  onPriceRangeChange,
  onRatingChange,
}) => {
  const categories = ['T-Shirts', 'Hoodies'];
  const sizes = ['S', 'M', 'L', 'XL', 'XXL'];
  const colors = ['Black', 'White', 'Gray', 'Navy', 'Olive', 'Beige'];

  return (
    <aside className={`filters-sidebar ${showFilters ? 'show' : 'hide'}`}>
      <div className="filters-header">
        <h2>Filters</h2>
        <Button variant="ghost" size="sm" onClick={onClearFilters}>
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
                onCheckedChange={() => onToggleCategory(category)}
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
            onValueChange={(value) => onPriceRangeChange(value as [number, number])}
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
                onCheckedChange={() => onToggleSize(size)}
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
              onClick={() => onToggleColor(color)}
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
                onCheckedChange={() => onRatingChange(rating)}
              />
              <span>{rating}+ Stars</span>
            </label>
          ))}
        </div>
      </div>
    </aside>
  );
};
