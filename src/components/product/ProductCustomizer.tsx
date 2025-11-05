import { useState } from 'react';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription } from '../ui/dialog';
import { Button } from '../ui/button';
import { Input } from '../ui/input';
import { Label } from '../ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../ui/select';
import { Product } from '../../lib/types';
import { Sparkles } from 'lucide-react';

interface ProductCustomizerProps {
  product: Product;
  isOpen: boolean;
  onClose: () => void;
  onSave: (customization: Record<string, string>) => void;
}

export function ProductCustomizer({ product, isOpen, onClose, onSave }: ProductCustomizerProps) {
  const [customization, setCustomization] = useState<Record<string, string>>({});

  const handleSave = () => {
    onSave(customization);
    onClose();
  };

  const totalCustomizationPrice = product.customizationOptions?.reduce((total, option) => {
    if (customization[option.id]) {
      return total + (option.price || 0);
    }
    return total;
  }, 0) || 0;

  return (
    <Dialog open={isOpen} onOpenChange={onClose}>
      <DialogContent className="max-w-md">
        <DialogHeader>
          <DialogTitle className="flex items-center gap-2">
            <Sparkles className="h-5 w-5" />
            Customize Your {product.name}
          </DialogTitle>
          <DialogDescription>
            Add personal touches to make this item uniquely yours. Additional charges may apply.
          </DialogDescription>
        </DialogHeader>

        <div className="space-y-6 py-4">
          {product.customizationOptions?.map((option) => (
            <div key={option.id} className="space-y-2">
              <div className="flex justify-between items-center">
                <Label htmlFor={option.id}>{option.name}</Label>
                {option.price && option.price > 0 && (
                  <span className="text-sm text-gray-600">+${option.price}</span>
                )}
              </div>

              {option.type === 'text' && (
                <Input
                  id={option.id}
                  placeholder={`Enter ${option.name.toLowerCase()}`}
                  value={customization[option.id] || ''}
                  onChange={(e) =>
                    setCustomization({ ...customization, [option.id]: e.target.value })
                  }
                  maxLength={20}
                />
              )}

              {option.type === 'select' && option.options && (
                <Select
                  value={customization[option.id] || ''}
                  onValueChange={(value) =>
                    setCustomization({ ...customization, [option.id]: value })
                  }
                >
                  <SelectTrigger id={option.id}>
                    <SelectValue placeholder={`Select ${option.name.toLowerCase()}`} />
                  </SelectTrigger>
                  <SelectContent>
                    {option.options.map((opt) => (
                      <SelectItem key={opt} value={opt}>
                        {opt}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              )}

              {option.type === 'color' && option.options && (
                <div className="flex gap-2">
                  {option.options.map((color) => (
                    <button
                      key={color}
                      onClick={() =>
                        setCustomization({ ...customization, [option.id]: color })
                      }
                      className={`w-10 h-10 rounded-full border-2 ${
                        customization[option.id] === color
                          ? 'border-black'
                          : 'border-gray-300'
                      }`}
                      style={{
                        backgroundColor: color.toLowerCase() === 'white' ? '#fff' :
                                       color.toLowerCase() === 'black' ? '#000' :
                                       color.toLowerCase() === 'blue' ? '#3b82f6' :
                                       color.toLowerCase() === 'navy' ? '#1e3a8a' :
                                       color.toLowerCase() === 'grey' || color.toLowerCase() === 'gray' ? '#6b7280' :
                                       color.toLowerCase() === 'red' ? '#ef4444' :
                                       color.toLowerCase() === 'silver' ? '#c0c0c0' :
                                       color.toLowerCase() === 'gold' ? '#ffd700' :
                                       '#9ca3af'
                      }}
                      title={color}
                    />
                  ))}
                </div>
              )}
            </div>
          ))}

          {totalCustomizationPrice > 0 && (
            <div className="border-t pt-4">
              <div className="flex justify-between items-center">
                <span className="text-sm text-gray-600">Customization Total:</span>
                <span className="font-semibold">+${totalCustomizationPrice.toFixed(2)}</span>
              </div>
            </div>
          )}

          <div className="flex gap-3">
            <Button variant="outline" onClick={onClose} className="flex-1">
              Cancel
            </Button>
            <Button onClick={handleSave} className="flex-1">
              Apply Customization
            </Button>
          </div>
        </div>
      </DialogContent>
    </Dialog>
  );
}
