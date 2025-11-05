import { useState } from 'react';
import { Product } from '../../lib/types';
import { Card } from '../ui/card';
import { Button } from '../ui/button';
import { Input } from '../ui/input';
import { Label } from '../ui/label';
import { Textarea } from '../ui/textarea';
import { Switch } from '../ui/switch';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '../ui/dialog';
import { Badge } from '../ui/badge';
import { Search, Plus, Edit, Trash2, Sparkles } from 'lucide-react';
import { ImageWithFallback } from '../figma/ImageWithFallback';
import { toast } from 'sonner@2.0.3';

interface ProductManagerProps {
  products: Product[];
  onAddProduct: (product: Product) => void;
  onUpdateProduct: (productId: string, product: Product) => void;
  onDeleteProduct: (productId: string) => void;
}

export function ProductManager({ products, onAddProduct, onUpdateProduct, onDeleteProduct }: ProductManagerProps) {
  const [searchTerm, setSearchTerm] = useState('');
  const [isAddDialogOpen, setIsAddDialogOpen] = useState(false);
  const [editingProduct, setEditingProduct] = useState<Product | null>(null);
  const [formData, setFormData] = useState<Partial<Product>>({
    name: '',
    description: '',
    price: 0,
    category: 'Shirts',
    image: '',
    images: [],
    sizes: [],
    colors: [],
    inStock: true,
    isCustomizable: false,
    customizationOptions: [],
    rating: 0,
    reviews: 0,
  });

  const filteredProducts = products.filter((product) =>
    product.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
    product.category.toLowerCase().includes(searchTerm.toLowerCase())
  );

  const handleOpenAddDialog = () => {
    setEditingProduct(null);
    setFormData({
      name: '',
      description: '',
      price: 0,
      category: 'Shirts',
      image: '',
      images: [],
      sizes: [],
      colors: [],
      inStock: true,
      isCustomizable: false,
      customizationOptions: [],
      rating: 0,
      reviews: 0,
    });
    setIsAddDialogOpen(true);
  };

  const handleOpenEditDialog = (product: Product) => {
    setEditingProduct(product);
    setFormData(product);
    setIsAddDialogOpen(true);
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!formData.name || !formData.description || !formData.price) {
      toast.error('Please fill in all required fields');
      return;
    }

    const productData: Product = {
      id: editingProduct?.id || `product-${Date.now()}`,
      name: formData.name || '',
      description: formData.description || '',
      price: formData.price || 0,
      category: formData.category || 'Shirts',
      image: formData.image || '',
      images: formData.images || [],
      sizes: formData.sizes || [],
      colors: formData.colors || [],
      inStock: formData.inStock ?? true,
      isCustomizable: formData.isCustomizable ?? false,
      customizationOptions: formData.customizationOptions || [],
      rating: formData.rating || 0,
      reviews: formData.reviews || 0,
    };

    if (editingProduct) {
      onUpdateProduct(editingProduct.id, productData);
      toast.success('Product updated successfully');
    } else {
      onAddProduct(productData);
      toast.success('Product added successfully');
    }

    setIsAddDialogOpen(false);
  };

  const handleDelete = (productId: string) => {
    if (confirm('Are you sure you want to delete this product?')) {
      onDeleteProduct(productId);
      toast.success('Product deleted');
    }
  };

  return (
    <div className="space-y-6">
      <div className="flex flex-col sm:flex-row justify-between gap-4">
        <h2 className="text-2xl">Product Management</h2>
        <div className="flex gap-2">
          <div className="relative flex-1 sm:flex-none sm:w-64">
            <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
            <Input
              placeholder="Search products..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="pl-10"
            />
          </div>
          <Button onClick={handleOpenAddDialog}>
            <Plus className="h-4 w-4 mr-2" />
            Add Product
          </Button>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {filteredProducts.map((product) => (
          <Card key={product.id} className="p-4">
            <div className="aspect-square rounded-lg overflow-hidden bg-gray-100 mb-4 relative">
              <ImageWithFallback
                src={product.image}
                alt={product.name}
                className="w-full h-full object-cover"
              />
              {product.isCustomizable && (
                <Badge className="absolute top-2 right-2 bg-black text-white">
                  <Sparkles className="h-3 w-3 mr-1" />
                  Custom
                </Badge>
              )}
            </div>
            <div className="space-y-2">
              <div className="flex justify-between items-start">
                <div>
                  <h3 className="line-clamp-1">{product.name}</h3>
                  <p className="text-sm text-gray-600">{product.category}</p>
                </div>
                <Badge variant={product.inStock ? 'outline' : 'secondary'}>
                  {product.inStock ? 'In Stock' : 'Out'}
                </Badge>
              </div>
              <p className="text-lg">${product.price.toFixed(2)}</p>
              <div className="flex gap-2">
                <Button
                  variant="outline"
                  size="sm"
                  className="flex-1"
                  onClick={() => handleOpenEditDialog(product)}
                >
                  <Edit className="h-3 w-3 mr-1" />
                  Edit
                </Button>
                <Button
                  variant="outline"
                  size="sm"
                  onClick={() => handleDelete(product.id)}
                >
                  <Trash2 className="h-3 w-3" />
                </Button>
              </div>
            </div>
          </Card>
        ))}
      </div>

      {/* Add/Edit Product Dialog */}
      <Dialog open={isAddDialogOpen} onOpenChange={setIsAddDialogOpen}>
        <DialogContent className="max-w-2xl max-h-[90vh] overflow-y-auto">
          <DialogHeader>
            <DialogTitle>
              {editingProduct ? 'Edit Product' : 'Add New Product'}
            </DialogTitle>
          </DialogHeader>

          <form onSubmit={handleSubmit} className="space-y-4">
            <div className="grid grid-cols-2 gap-4">
              <div className="col-span-2">
                <Label htmlFor="name">Product Name *</Label>
                <Input
                  id="name"
                  required
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                />
              </div>

              <div className="col-span-2">
                <Label htmlFor="description">Description *</Label>
                <Textarea
                  id="description"
                  required
                  value={formData.description}
                  onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                  rows={3}
                />
              </div>

              <div>
                <Label htmlFor="price">Price *</Label>
                <Input
                  id="price"
                  type="number"
                  step="0.01"
                  required
                  value={formData.price}
                  onChange={(e) => setFormData({ ...formData, price: parseFloat(e.target.value) })}
                />
              </div>

              <div>
                <Label htmlFor="category">Category *</Label>
                <select
                  id="category"
                  className="w-full px-3 py-2 border border-gray-300 rounded-md"
                  value={formData.category}
                  onChange={(e) => setFormData({ ...formData, category: e.target.value })}
                >
                  <option value="Jackets">Jackets</option>
                  <option value="Shirts">Shirts</option>
                  <option value="Pants">Pants</option>
                  <option value="Shoes">Shoes</option>
                  <option value="Accessories">Accessories</option>
                </select>
              </div>

              <div className="col-span-2">
                <Label htmlFor="image">Image URL *</Label>
                <Input
                  id="image"
                  required
                  value={formData.image}
                  onChange={(e) => setFormData({ ...formData, image: e.target.value, images: [e.target.value] })}
                />
              </div>

              <div className="col-span-2">
                <Label htmlFor="sizes">Sizes (comma separated)</Label>
                <Input
                  id="sizes"
                  placeholder="S, M, L, XL"
                  value={formData.sizes?.join(', ')}
                  onChange={(e) => setFormData({ ...formData, sizes: e.target.value.split(',').map(s => s.trim()) })}
                />
              </div>

              <div className="col-span-2">
                <Label htmlFor="colors">Colors (comma separated)</Label>
                <Input
                  id="colors"
                  placeholder="Black, White, Blue"
                  value={formData.colors?.join(', ')}
                  onChange={(e) => setFormData({ ...formData, colors: e.target.value.split(',').map(c => c.trim()) })}
                />
              </div>

              <div className="flex items-center space-x-2">
                <Switch
                  id="inStock"
                  checked={formData.inStock}
                  onCheckedChange={(checked) => setFormData({ ...formData, inStock: checked })}
                />
                <Label htmlFor="inStock">In Stock</Label>
              </div>

              <div className="flex items-center space-x-2">
                <Switch
                  id="isCustomizable"
                  checked={formData.isCustomizable}
                  onCheckedChange={(checked) => setFormData({ ...formData, isCustomizable: checked })}
                />
                <Label htmlFor="isCustomizable">Customizable</Label>
              </div>
            </div>

            {formData.isCustomizable && (
              <div className="border-t pt-4">
                <Label className="mb-2 block">Customization Options</Label>
                <p className="text-sm text-gray-600 mb-4">
                  Note: In a real application, you would add/edit customization options here.
                  For demo purposes, this field is simplified.
                </p>
              </div>
            )}

            <div className="flex gap-3">
              <Button type="button" variant="outline" onClick={() => setIsAddDialogOpen(false)} className="flex-1">
                Cancel
              </Button>
              <Button type="submit" className="flex-1">
                {editingProduct ? 'Update Product' : 'Add Product'}
              </Button>
            </div>
          </form>
        </DialogContent>
      </Dialog>
    </div>
  );
}
