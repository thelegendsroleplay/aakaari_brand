import { WishlistPage } from '../../components/wishlist/WishlistPage';
import { Product, Page } from '../../lib/types';

interface WishlistPageContainerProps {
  wishlistProducts: Product[];
  onProductClick: (productId: string) => void;
  onRemoveFromWishlist: (productId: string) => void;
  onAddToCart: (productId: string) => void;
  onNavigate: (page: Page) => void;
}

export function WishlistPageContainer({
  wishlistProducts,
  onProductClick,
  onRemoveFromWishlist,
  onAddToCart,
  onNavigate,
}: WishlistPageContainerProps) {
  return (
    <WishlistPage
      wishlistProducts={wishlistProducts}
      onProductClick={onProductClick}
      onRemoveFromWishlist={onRemoveFromWishlist}
      onAddToCart={onAddToCart}
      onNavigate={onNavigate}
    />
  );
}
