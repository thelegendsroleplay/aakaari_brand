import { Hero } from '../../components/home/Hero';
import { Categories } from '../../components/home/Categories';
import { FeaturedProducts } from '../../components/home/FeaturedProducts';
import { Product, Page } from '../../lib/types';

interface HomePageProps {
  products: Product[];
  onProductClick: (productId: string) => void;
  onNavigate: (page: Page) => void;
}

export function HomePage({ products, onProductClick, onNavigate }: HomePageProps) {
  return (
    <>
      <Hero onNavigate={onNavigate} />
      <Categories onNavigate={onNavigate} />
      <FeaturedProducts
        products={products}
        onProductClick={onProductClick}
        onNavigate={onNavigate}
      />
    </>
  );
}
