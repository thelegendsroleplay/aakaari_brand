import React from 'react';
import { SlidersHorizontal } from 'lucide-react';
import { Button } from '../../components/ui/button';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../../components/ui/select';

interface ProductsToolbarProps {
  productsCount: number;
  showFilters: boolean;
  sortBy: string;
  onToggleFilters: () => void;
  onSortChange: (value: string) => void;
}

export const ProductsToolbar: React.FC<ProductsToolbarProps> = ({
  productsCount,
  showFilters,
  sortBy,
  onToggleFilters,
  onSortChange,
}) => {
  return (
    <div className="products-toolbar">
      <Button
        variant="outline"
        size="sm"
        className="lg:hidden"
        onClick={onToggleFilters}
      >
        <SlidersHorizontal className="w-4 h-4 mr-2" />
        {showFilters ? 'Hide' : 'Show'} Filters
      </Button>

      <div className="toolbar-info">
        <p>{productsCount} Products</p>
      </div>

      <div className="toolbar-sort">
        <span className="sort-label">Sort by:</span>
        <Select value={sortBy} onValueChange={onSortChange}>
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
  );
};
