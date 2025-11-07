import React, { useState } from 'react';
import { ArrowLeft } from 'lucide-react';
import { Product, ProductVariation } from '../../types';
import { useCart } from '../../contexts/CartContext';
import { useWishlist } from '../../contexts/WishlistContext';
import { useProducts } from '../../contexts/ProductsContext';
import { Button } from '../../components/ui/button';
import { mockReviews } from '../../lib/mockData';
import { ProductGallery } from './ProductGallery';
import { ProductInfo } from './ProductInfo';
import { ProductFeatures } from './ProductFeatures';
import { ProductReviews } from './ProductReviews';
import { RelatedProducts } from './RelatedProducts';
import './product-detail.css';

interface ProductDetailPageProps {
  product: Product;
  onNavigate: (page: string) => void;
  onViewProduct?: (product: Product) => void;
}

export const ProductDetailPage: React.FC<ProductDetailPageProps> = ({ product, onNavigate, onViewProduct }) => {
  const { addToCart } = useCart();
  const { addToWishlist, isInWishlist, removeFromWishlist } = useWishlist();
  const { products } = useProducts();
  
  const [selectedImage, setSelectedImage] = useState(0);
  const [selectedSize, setSelectedSize] = useState(product.sizes[0]);
  const [selectedColor, setSelectedColor] = useState(product.colors[0]);
  const [quantity, setQuantity] = useState(1);
  const [selectedVariation, setSelectedVariation] = useState<ProductVariation | null>(null);

  const inWishlist = isInWishlist(product.id);
  const reviews = mockReviews.filter(r => r.productId === product.id);
  const relatedProducts = products
    .filter(p => p.category === product.category && p.id !== product.id)
    .slice(0, 4);

  const handleVariationSelection = (attrName: string, value: string) => {
    if (product.productType === 'variable' && product.variations) {
      const selectedAttrs = { ...selectedVariation?.attributes } || {};
      selectedAttrs[attrName] = value;

      const matchingVariation = product.variations.find(v =>
        Object.entries(selectedAttrs).every(([key, val]) => v.attributes[key] === val)
      );

      if (matchingVariation) {
        setSelectedVariation(matchingVariation);
      }
    }
  };

  const getCurrentPrice = () => {
    if (product.productType === 'variable' && selectedVariation) {
      return selectedVariation.salePrice || selectedVariation.price;
    }
    return product.salePrice || product.price;
  };

  const getOriginalPrice = () => {
    if (product.productType === 'variable' && selectedVariation) {
      return selectedVariation.salePrice ? selectedVariation.price : null;
    }
    return product.salePrice ? product.price : null;
  };

  const getStockInfo = () => {
    if (product.productType === 'variable' && selectedVariation) {
      return selectedVariation.stock;
    }
    return product.stock;
  };

  const handleAddToCart = () => {
    addToCart(product, selectedSize, selectedColor, quantity);
  };

  const handleToggleWishlist = () => {
    if (inWishlist) {
      removeFromWishlist(product.id);
    } else {
      addToWishlist(product);
    }
  };

  return (
    <div className="product-detail-page">
      {/* Back Button */}
      <div className="detail-container">
        <Button
          variant="ghost"
          onClick={() => onNavigate('products')}
          className="back-btn"
        >
          <ArrowLeft className="w-4 h-4 mr-2" />
          Back to Products
        </Button>
      </div>

      {/* Main Product Section */}
      <div className="detail-container">
        <div className="product-grid">
          <ProductGallery
            images={product.images}
            productName={product.name}
            selectedImage={selectedImage}
            onSelectImage={setSelectedImage}
          />

          <ProductInfo
            product={product}
            selectedSize={selectedSize}
            selectedColor={selectedColor}
            quantity={quantity}
            selectedVariation={selectedVariation}
            inWishlist={inWishlist}
            onSizeChange={setSelectedSize}
            onColorChange={setSelectedColor}
            onQuantityChange={setQuantity}
            onAddToCart={handleAddToCart}
            onToggleWishlist={handleToggleWishlist}
            getCurrentPrice={getCurrentPrice}
            getOriginalPrice={getOriginalPrice}
            getStockInfo={getStockInfo}
          />
        </div>
      </div>

      {/* Features */}
      <div className="detail-container">
        <ProductFeatures />
      </div>

      {/* Reviews */}
      <div className="detail-container">
        <ProductReviews reviews={reviews} productRating={product.rating} />
      </div>

      {/* Related Products */}
      <div className="detail-container">
        <RelatedProducts
          products={relatedProducts}
          onViewProduct={onViewProduct || (() => {})}
        />
      </div>
    </div>
  );
};
