import React, { useState } from 'react';
import { X, Plus, Trash2, Star, GripVertical, Image as ImageIcon } from 'lucide-react';
import { Button } from '../ui/button';
import { Input } from '../ui/input';
import { Label } from '../ui/label';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '../ui/tabs';
import { Product, ProductAttribute, ProductVariation } from '../../types';

interface ProductFormProps {
  product?: Product;
  onSubmit: (productData: any) => void;
  onClose: () => void;
}

export const ProductFormWithVariations: React.FC<ProductFormProps> = ({ product, onSubmit, onClose }) => {
  const [formData, setFormData] = useState({
    name: product?.name || '',
    price: product?.price || 0,
    salePrice: product?.salePrice || 0,
    category: product?.category || 'T-Shirts',
    description: product?.description || '',
    stock: product?.stock || 0,
    images: product?.images || [''],
    sizes: product?.sizes || ['S', 'M', 'L', 'XL'],
    colors: product?.colors || ['Black', 'White'],
    features: product?.features || ['100% Cotton', 'Comfortable Fit'],
    rating: product?.rating || 4.5,
    reviewCount: product?.reviewCount || 0,
    bestseller: product?.bestseller || false,
    newArrival: product?.newArrival || false,
    featured: product?.featured || false,
    productType: product?.productType || 'simple' as 'simple' | 'variable',
    attributes: product?.attributes || [] as ProductAttribute[],
    variations: product?.variations || [] as ProductVariation[],
  });

  const [newAttribute, setNewAttribute] = useState({ name: '', values: '' });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    onSubmit(formData);
    onClose();
  };

  const handleImageChange = (index: number, value: string) => {
    const newImages = [...formData.images];
    newImages[index] = value;
    setFormData({ ...formData, images: newImages });
  };

  const addImageField = () => {
    setFormData({ ...formData, images: [...formData.images, ''] });
  };

  const removeImageField = (index: number) => {
    const newImages = formData.images.filter((_, i) => i !== index);
    setFormData({ ...formData, images: newImages });
  };

  const setFeaturedImage = (index: number) => {
    if (index === 0) return; // Already featured
    const newImages = [...formData.images];
    const [featured] = newImages.splice(index, 1);
    newImages.unshift(featured);
    setFormData({ ...formData, images: newImages });
  };

  const moveImage = (fromIndex: number, toIndex: number) => {
    const newImages = [...formData.images];
    const [movedImage] = newImages.splice(fromIndex, 1);
    newImages.splice(toIndex, 0, movedImage);
    setFormData({ ...formData, images: newImages });
  };

  // Attribute Management
  const addAttribute = () => {
    if (newAttribute.name && newAttribute.values) {
      const attribute: ProductAttribute = {
        name: newAttribute.name,
        values: newAttribute.values.split(',').map(v => v.trim()),
        variation: true,
        visible: true,
      };
      setFormData({ 
        ...formData, 
        attributes: [...formData.attributes, attribute] 
      });
      setNewAttribute({ name: '', values: '' });
    }
  };

  const removeAttribute = (index: number) => {
    const newAttributes = formData.attributes.filter((_, i) => i !== index);
    setFormData({ ...formData, attributes: newAttributes });
  };

  // Generate all possible variations
  const generateVariations = () => {
    if (formData.attributes.length === 0) {
      alert('Please add attributes first');
      return;
    }

    const combinations: { [key: string]: string }[] = [];
    
    const generate = (index: number, current: { [key: string]: string }) => {
      if (index === formData.attributes.length) {
        combinations.push({ ...current });
        return;
      }
      
      const attr = formData.attributes[index];
      for (const value of attr.values) {
        current[attr.name] = value;
        generate(index + 1, current);
      }
    };

    generate(0, {});

    const newVariations: ProductVariation[] = combinations.map(attrs => ({
      id: Math.random().toString(36).substr(2, 9),
      attributes: attrs,
      sku: `SKU-${Math.random().toString(36).substr(2, 6).toUpperCase()}`,
      price: formData.price,
      salePrice: formData.salePrice,
      stock: 0,
      enabled: true,
    }));

    setFormData({ ...formData, variations: newVariations });
  };

  const updateVariation = (index: number, field: string, value: any) => {
    const newVariations = [...formData.variations];
    newVariations[index] = { ...newVariations[index], [field]: value };
    setFormData({ ...formData, variations: newVariations });
  };

  const deleteVariation = (index: number) => {
    const newVariations = formData.variations.filter((_, i) => i !== index);
    setFormData({ ...formData, variations: newVariations });
  };

  return (
    <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4 overflow-y-auto">
      <div className="bg-white rounded-lg max-w-5xl w-full max-h-[90vh] overflow-y-auto my-8">
        <div className="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center z-10">
          <h2 className="text-xl font-semibold">
            {product ? 'Edit Product' : 'Add New Product'}
          </h2>
          <Button variant="ghost" size="icon" onClick={onClose}>
            <X className="w-5 h-5" />
          </Button>
        </div>

        <form onSubmit={handleSubmit} className="p-6">
          <Tabs defaultValue="general">
            <TabsList className="mb-6">
              <TabsTrigger value="general">General</TabsTrigger>
              <TabsTrigger value="variations">Variations</TabsTrigger>
              <TabsTrigger value="images">Images</TabsTrigger>
              <TabsTrigger value="advanced">Advanced</TabsTrigger>
            </TabsList>

            {/* General Tab */}
            <TabsContent value="general" className="space-y-6">
              {/* Product Type */}
              <div>
                <Label>Product Type</Label>
                <div className="flex gap-4 mt-2">
                  <label className="flex items-center gap-2 cursor-pointer">
                    <input
                      type="radio"
                      name="productType"
                      value="simple"
                      checked={formData.productType === 'simple'}
                      onChange={(e) => setFormData({ ...formData, productType: 'simple' })}
                      className="w-4 h-4"
                    />
                    <span>Simple Product</span>
                  </label>
                  <label className="flex items-center gap-2 cursor-pointer">
                    <input
                      type="radio"
                      name="productType"
                      value="variable"
                      checked={formData.productType === 'variable'}
                      onChange={(e) => setFormData({ ...formData, productType: 'variable' })}
                      className="w-4 h-4"
                    />
                    <span>Variable Product</span>
                  </label>
                </div>
              </div>

              {/* Basic Info */}
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <Label htmlFor="name">Product Name *</Label>
                  <Input
                    id="name"
                    value={formData.name}
                    onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                    required
                  />
                </div>

                <div>
                  <Label htmlFor="category">Category *</Label>
                  <select
                    id="category"
                    className="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                    value={formData.category}
                    onChange={(e) => setFormData({ ...formData, category: e.target.value })}
                    required
                  >
                    <option value="T-Shirts">T-Shirts</option>
                    <option value="Hoodies">Hoodies</option>
                  </select>
                </div>

                {formData.productType === 'simple' && (
                  <>
                    <div>
                      <Label htmlFor="price">Regular Price ($) *</Label>
                      <Input
                        id="price"
                        type="number"
                        step="0.01"
                        value={formData.price}
                        onChange={(e) => setFormData({ ...formData, price: parseFloat(e.target.value) })}
                        required
                      />
                    </div>

                    <div>
                      <Label htmlFor="salePrice">Sale Price ($)</Label>
                      <Input
                        id="salePrice"
                        type="number"
                        step="0.01"
                        value={formData.salePrice || ''}
                        onChange={(e) => setFormData({ ...formData, salePrice: parseFloat(e.target.value) || 0 })}
                      />
                    </div>

                    <div>
                      <Label htmlFor="stock">Stock Quantity *</Label>
                      <Input
                        id="stock"
                        type="number"
                        value={formData.stock}
                        onChange={(e) => setFormData({ ...formData, stock: parseInt(e.target.value) })}
                        required
                      />
                    </div>
                  </>
                )}

                <div>
                  <Label htmlFor="rating">Rating (1-5)</Label>
                  <Input
                    id="rating"
                    type="number"
                    step="0.1"
                    min="1"
                    max="5"
                    value={formData.rating}
                    onChange={(e) => setFormData({ ...formData, rating: parseFloat(e.target.value) })}
                  />
                </div>
              </div>

              {/* Description */}
              <div>
                <Label htmlFor="description">Description</Label>
                <textarea
                  id="description"
                  className="flex min-h-[100px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                  value={formData.description}
                  onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                />
              </div>
            </TabsContent>

            {/* Variations Tab */}
            <TabsContent value="variations" className="space-y-6">
              {formData.productType === 'simple' ? (
                <div className="text-center py-8 text-gray-500">
                  <p>Variations are only available for Variable Products.</p>
                  <p className="text-sm mt-2">Change product type to "Variable Product" in the General tab.</p>
                </div>
              ) : (
                <>
                  {/* Attributes */}
                  <div>
                    <h3 className="font-semibold mb-4">Product Attributes</h3>
                    <div className="space-y-3">
                      {formData.attributes.map((attr, index) => (
                        <div key={index} className="flex items-center gap-3 p-3 bg-gray-50 rounded">
                          <div className="flex-1">
                            <p className="font-medium">{attr.name}</p>
                            <p className="text-sm text-gray-600">{attr.values.join(', ')}</p>
                          </div>
                          <Button
                            type="button"
                            variant="ghost"
                            size="sm"
                            onClick={() => removeAttribute(index)}
                          >
                            <Trash2 className="w-4 h-4 text-red-600" />
                          </Button>
                        </div>
                      ))}

                      <div className="flex gap-2">
                        <Input
                          placeholder="Attribute name (e.g., Color)"
                          value={newAttribute.name}
                          onChange={(e) => setNewAttribute({ ...newAttribute, name: e.target.value })}
                        />
                        <Input
                          placeholder="Values (comma-separated)"
                          value={newAttribute.values}
                          onChange={(e) => setNewAttribute({ ...newAttribute, values: e.target.value })}
                        />
                        <Button type="button" onClick={addAttribute}>
                          <Plus className="w-4 h-4 mr-1" />
                          Add
                        </Button>
                      </div>
                    </div>
                  </div>

                  {/* Generate Variations Button */}
                  {formData.attributes.length > 0 && (
                    <Button type="button" onClick={generateVariations} variant="outline">
                      Generate Variations
                    </Button>
                  )}

                  {/* Variations List */}
                  {formData.variations.length > 0 && (
                    <div>
                      <h3 className="font-semibold mb-4">Variations ({formData.variations.length})</h3>
                      <div className="space-y-3 max-h-96 overflow-y-auto">
                        {formData.variations.map((variation, index) => (
                          <div key={variation.id} className="border rounded-lg p-4">
                            <div className="flex items-start justify-between mb-3">
                              <div>
                                <p className="font-medium">
                                  {Object.entries(variation.attributes).map(([key, value]) => `${key}: ${value}`).join(' | ')}
                                </p>
                              </div>
                              <Button
                                type="button"
                                variant="ghost"
                                size="sm"
                                onClick={() => deleteVariation(index)}
                              >
                                <Trash2 className="w-4 h-4 text-red-600" />
                              </Button>
                            </div>
                            <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                              <div>
                                <Label className="text-xs">SKU</Label>
                                <Input
                                  size="sm"
                                  value={variation.sku}
                                  onChange={(e) => updateVariation(index, 'sku', e.target.value)}
                                />
                              </div>
                              <div>
                                <Label className="text-xs">Price ($)</Label>
                                <Input
                                  type="number"
                                  step="0.01"
                                  value={variation.price}
                                  onChange={(e) => updateVariation(index, 'price', parseFloat(e.target.value))}
                                />
                              </div>
                              <div>
                                <Label className="text-xs">Sale Price ($)</Label>
                                <Input
                                  type="number"
                                  step="0.01"
                                  value={variation.salePrice || ''}
                                  onChange={(e) => updateVariation(index, 'salePrice', parseFloat(e.target.value) || 0)}
                                />
                              </div>
                              <div>
                                <Label className="text-xs">Stock</Label>
                                <Input
                                  type="number"
                                  value={variation.stock}
                                  onChange={(e) => updateVariation(index, 'stock', parseInt(e.target.value))}
                                />
                              </div>
                            </div>
                          </div>
                        ))}
                      </div>
                    </div>
                  )}
                </>
              )}
            </TabsContent>

            {/* Images Tab */}
            <TabsContent value="images" className="space-y-4">
              <div className="flex justify-between items-center">
                <div>
                  <Label>Product Images</Label>
                  <p className="text-xs text-gray-500 mt-1">
                    First image will be the featured image. Click ⭐ to set as featured.
                  </p>
                </div>
                <Button type="button" variant="outline" size="sm" onClick={addImageField}>
                  <Plus className="w-4 h-4 mr-1" />
                  Add Image
                </Button>
              </div>

              <div className="space-y-3">
                {formData.images.map((image, index) => (
                  <div 
                    key={index} 
                    className={`border rounded-lg p-4 ${index === 0 ? 'border-yellow-400 bg-yellow-50/50' : 'border-gray-200'}`}
                  >
                    <div className="flex gap-4">
                      {/* Image Preview */}
                      <div className="relative flex-shrink-0">
                        <div className="w-20 h-20 rounded-md border-2 border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden">
                          {image ? (
                            <img 
                              src={image} 
                              alt={`Preview ${index + 1}`}
                              className="w-full h-full object-cover"
                              onError={(e) => {
                                e.currentTarget.style.display = 'none';
                                e.currentTarget.nextElementSibling?.classList.remove('hidden');
                              }}
                            />
                          ) : null}
                          <div className={image ? 'hidden' : 'text-gray-400'}>
                            <ImageIcon className="w-8 h-8" />
                          </div>
                        </div>
                        {index === 0 && (
                          <div className="absolute -top-2 -right-2 bg-yellow-400 text-white text-xs px-2 py-0.5 rounded-full font-semibold flex items-center gap-1">
                            <Star className="w-3 h-3 fill-white" />
                            Featured
                          </div>
                        )}
                      </div>

                      {/* Image URL Input */}
                      <div className="flex-1 flex flex-col gap-2">
                        <div className="flex gap-2">
                          <Input
                            placeholder="https://images.unsplash.com/photo-..."
                            value={image}
                            onChange={(e) => handleImageChange(index, e.target.value)}
                            className="flex-1"
                          />
                        </div>
                        <p className="text-xs text-gray-500">
                          Tip: Use Unsplash (https://images.unsplash.com/...) or any image URL
                        </p>
                      </div>

                      {/* Actions */}
                      <div className="flex flex-col gap-1 flex-shrink-0">
                        {index !== 0 && (
                          <Button
                            type="button"
                            variant="outline"
                            size="sm"
                            onClick={() => setFeaturedImage(index)}
                            title="Set as featured image"
                            className="h-8 px-2"
                          >
                            <Star className="w-4 h-4 text-yellow-500" />
                          </Button>
                        )}
                        {index > 0 && (
                          <Button
                            type="button"
                            variant="outline"
                            size="sm"
                            onClick={() => moveImage(index, index - 1)}
                            title="Move up"
                            className="h-8 px-2"
                          >
                            ↑
                          </Button>
                        )}
                        {index < formData.images.length - 1 && (
                          <Button
                            type="button"
                            variant="outline"
                            size="sm"
                            onClick={() => moveImage(index, index + 1)}
                            title="Move down"
                            className="h-8 px-2"
                          >
                            ↓
                          </Button>
                        )}
                        {formData.images.length > 1 && (
                          <Button
                            type="button"
                            variant="ghost"
                            size="sm"
                            onClick={() => removeImageField(index)}
                            title="Remove image"
                            className="h-8 px-2 text-red-600 hover:text-red-700 hover:bg-red-50"
                          >
                            <Trash2 className="w-4 h-4" />
                          </Button>
                        )}
                      </div>
                    </div>
                  </div>
                ))}
              </div>

              {formData.images.length === 0 && (
                <div className="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                  <ImageIcon className="w-12 h-12 text-gray-400 mx-auto mb-3" />
                  <p className="text-gray-500 mb-3">No images added yet</p>
                  <Button type="button" variant="outline" onClick={addImageField}>
                    <Plus className="w-4 h-4 mr-1" />
                    Add First Image
                  </Button>
                </div>
              )}
            </TabsContent>

            {/* Advanced Tab */}
            <TabsContent value="advanced" className="space-y-4">
              {/* Sizes */}
              <div>
                <Label htmlFor="sizes">Sizes (comma-separated)</Label>
                <Input
                  id="sizes"
                  value={formData.sizes.join(', ')}
                  onChange={(e) => setFormData({ ...formData, sizes: e.target.value.split(',').map(s => s.trim()) })}
                  placeholder="S, M, L, XL"
                />
              </div>

              {/* Colors */}
              <div>
                <Label htmlFor="colors">Colors (comma-separated)</Label>
                <Input
                  id="colors"
                  value={formData.colors.join(', ')}
                  onChange={(e) => setFormData({ ...formData, colors: e.target.value.split(',').map(c => c.trim()) })}
                  placeholder="Black, White, Gray"
                />
              </div>

              {/* Features */}
              <div>
                <Label htmlFor="features">Features (comma-separated)</Label>
                <Input
                  id="features"
                  value={formData.features.join(', ')}
                  onChange={(e) => setFormData({ ...formData, features: e.target.value.split(',').map(f => f.trim()) })}
                  placeholder="100% Cotton, Machine Washable"
                />
              </div>

              {/* Flags */}
              <div className="flex gap-4">
                <label className="flex items-center gap-2 cursor-pointer">
                  <input
                    type="checkbox"
                    checked={formData.bestseller}
                    onChange={(e) => setFormData({ ...formData, bestseller: e.target.checked })}
                    className="w-4 h-4"
                  />
                  <span className="text-sm">Bestseller</span>
                </label>

                <label className="flex items-center gap-2 cursor-pointer">
                  <input
                    type="checkbox"
                    checked={formData.newArrival}
                    onChange={(e) => setFormData({ ...formData, newArrival: e.target.checked })}
                    className="w-4 h-4"
                  />
                  <span className="text-sm">New Arrival</span>
                </label>

                <label className="flex items-center gap-2 cursor-pointer">
                  <input
                    type="checkbox"
                    checked={formData.featured}
                    onChange={(e) => setFormData({ ...formData, featured: e.target.checked })}
                    className="w-4 h-4"
                  />
                  <span className="text-sm">Featured Product</span>
                </label>
              </div>
            </TabsContent>
          </Tabs>

          {/* Submit Buttons */}
          <div className="flex gap-3 pt-6 mt-6 border-t border-gray-200">
            <Button type="submit" className="flex-1">
              {product ? 'Update Product' : 'Add Product'}
            </Button>
            <Button type="button" variant="outline" onClick={onClose}>
              Cancel
            </Button>
          </div>
        </form>
      </div>
    </div>
  );
};