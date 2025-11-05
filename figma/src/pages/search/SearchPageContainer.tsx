import { SearchPage } from '../../components/search/SearchPage';
import { Product } from '../../lib/types';

interface SearchPageContainerProps {
  products: Product[];
  onProductClick: (productId: string) => void;
  onAddToWishlist: (productId: string) => void;
  wishlistIds: string[];
}

export function SearchPageContainer({
  products,
  onProductClick,
  onAddToWishlist,
  wishlistIds,
}: SearchPageContainerProps) {
  return (
    <SearchPage
      products={products}
      onProductClick={onProductClick}
      onAddToWishlist={onAddToWishlist}
      wishlistIds={wishlistIds}
    />
  );
}
