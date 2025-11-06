import { ProductDetail } from '../../components/product/ProductDetail';
import { RelatedProducts } from '../../components/product/RelatedProducts';
import { RecentlyViewed } from '../../components/product/RecentlyViewed';
import { ProductReviews } from '../../components/product/ProductReviews';
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
  const related = relatedProducts
    .filter((p) => p.category === product.category && p.id !== product.id)
    .slice(0, 4);

  return (
    <div className="bg-white">
      <ProductDetail
        product={product}
        onAddToCart={onAddToCart}
        onBack={onBack}
        onAddToWishlist={onAddToWishlist}
        isInWishlist={wishlistIds.includes(product.id)}
      />

      <ProductReviews productId={product.id} />

      {related.length > 0 && (
        <RelatedProducts
          products={related}
          onProductClick={onProductClick}
        />
      )}

      {recentlyViewedProducts.length > 0 && (
        <RecentlyViewed
          products={recentlyViewedProducts}
          onProductClick={onProductClick}
        />
      )}
    </div>
  );
}
