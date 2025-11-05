import { ProductDetail } from '../../components/product/ProductDetail';
import { Product, CartItem } from '../../lib/types';

interface ProductDetailPageProps {
  product: Product;
  onAddToCart: (item: CartItem) => void;
  onBack: () => void;
  relatedProducts: Product[];
  onProductClick: (productId: string) => void;
  recentlyViewedProducts: Product[];
  onAddToWishlist: (productId: string) => void;
  wishlistIds: string[];
}

export function ProductDetailPage({
  product,
  onAddToCart,
  onBack,
  relatedProducts,
  onProductClick,
  recentlyViewedProducts,
  onAddToWishlist,
  wishlistIds,
}: ProductDetailPageProps) {
  return (
    <ProductDetail
      product={product}
      onAddToCart={onAddToCart}
      onBack={onBack}
      relatedProducts={relatedProducts}
      onProductClick={onProductClick}
      recentlyViewedProducts={recentlyViewedProducts}
      onAddToWishlist={onAddToWishlist}
      wishlistIds={wishlistIds}
    />
  );
}
