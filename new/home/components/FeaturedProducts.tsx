import { Product, Page } from '../../../lib/types';
import { ProductCard } from '../../../components/shop/ProductCard';
import { Button } from '../../../components/ui/button';

interface FeaturedProductsProps {
  products: Product[];
  onProductClick: (productId: string) => void;
  onNavigate: (page: Page) => void;
}

export function FeaturedProducts({ products, onProductClick, onNavigate }: FeaturedProductsProps) {
  return (
    <section className="py-12 md:py-16 bg-gray-50">
      <div className="container mx-auto px-4">
        <div className="text-center mb-8 md:mb-12">
          <h2 className="text-3xl md:text-4xl mb-4">Featured Collection</h2>
          <p className="text-gray-600 max-w-2xl mx-auto">
            Discover our handpicked selection of premium pieces for the modern gentleman
          </p>
        </div>

        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
          {products.slice(0, 4).map((product) => (
            <ProductCard
              key={product.id}
              product={product}
              onClick={() => onProductClick(product.id)}
            />
          ))}
        </div>

        <div className="text-center">
          <Button
            size="lg"
            variant="outline"
            onClick={() => onNavigate('shop')}
          >
            View All Products
          </Button>
        </div>
      </div>
    </section>
  );
}
