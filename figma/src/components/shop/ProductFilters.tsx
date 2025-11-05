import { Card } from '../ui/card';
import { Checkbox } from '../ui/checkbox';
import { Label } from '../ui/label';
import { Slider } from '../ui/slider';
import { Button } from '../ui/button';

interface ProductFiltersProps {
  onFilterChange: (filters: any) => void;
}

export function ProductFilters({ onFilterChange }: ProductFiltersProps) {
  return (
    <Card className="p-4 md:p-6">
      <div className="space-y-6">
        <div>
          <h3 className="mb-4">Categories</h3>
          <div className="space-y-3">
            {['All', 'Jackets', 'Shirts', 'Pants', 'Shoes', 'Accessories'].map((category) => (
              <div key={category} className="flex items-center space-x-2">
                <Checkbox id={category} />
                <Label htmlFor={category} className="cursor-pointer">
                  {category}
                </Label>
              </div>
            ))}
          </div>
        </div>

        <div className="border-t pt-6">
          <h3 className="mb-4">Price Range</h3>
          <Slider defaultValue={[0, 500]} max={500} step={10} className="mb-4" />
          <div className="flex justify-between text-sm text-gray-600">
            <span>$0</span>
            <span>$500</span>
          </div>
        </div>

        <div className="border-t pt-6">
          <h3 className="mb-4">Size</h3>
          <div className="flex flex-wrap gap-2">
            {['XS', 'S', 'M', 'L', 'XL', 'XXL'].map((size) => (
              <Button
                key={size}
                variant="outline"
                size="sm"
                className="w-12"
              >
                {size}
              </Button>
            ))}
          </div>
        </div>

        <div className="border-t pt-6">
          <h3 className="mb-4">Color</h3>
          <div className="flex flex-wrap gap-2">
            {[
              { name: 'Black', color: '#000' },
              { name: 'White', color: '#fff' },
              { name: 'Blue', color: '#3b82f6' },
              { name: 'Navy', color: '#1e3a8a' },
              { name: 'Grey', color: '#6b7280' },
              { name: 'Red', color: '#ef4444' },
            ].map((color) => (
              <button
                key={color.name}
                className="w-8 h-8 rounded-full border-2 border-gray-300 hover:border-black transition-colors"
                style={{ backgroundColor: color.color }}
                title={color.name}
              />
            ))}
          </div>
        </div>

        <div className="border-t pt-6">
          <div className="flex items-center space-x-2">
            <Checkbox id="customizable" />
            <Label htmlFor="customizable" className="cursor-pointer">
              Customizable Only
            </Label>
          </div>
        </div>

        <Button className="w-full" variant="outline">
          Clear Filters
        </Button>
      </div>
    </Card>
  );
}
