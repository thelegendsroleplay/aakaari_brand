import { ShopPage } from './components/ShopPage';
import { Product } from '../../lib/types';

interface ShopPageContainerProps {
  products: Product[];
  onProductClick: (productId: string) => void;
  onAddToWishlist: (productId: string) => void;
  onQuickView: (productId: string) => void;
  wishlistIds: string[];
}

export function ShopPageContainer({
  products,
  onProductClick,
  onAddToWishlist,
  onQuickView,
  wishlistIds,
}: ShopPageContainerProps) {
  return (
    <ShopPage
      products={products}
      onProductClick={onProductClick}
      onAddToWishlist={onAddToWishlist}
      onQuickView={onQuickView}
      wishlistIds={wishlistIds}
    />
  );
}
