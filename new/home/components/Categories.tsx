import { Card } from '../../../components/ui/card';
import { Page } from '../../../lib/types';
import { ImageWithFallback } from '../../../components/figma/ImageWithFallback';
import { demoCategories } from '../data';

interface CategoriesProps {
  onNavigate: (page: Page) => void;
}

export function Categories({ onNavigate }: CategoriesProps) {
  return (
    <section className="py-12 md:py-16">
      <div className="container mx-auto px-4">
        <h2 className="text-3xl md:text-4xl text-center mb-8 md:mb-12">Shop by Category</h2>
        
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
          {demoCategories.map((category) => (
            <Card
              key={category.id}
              className="cursor-pointer group overflow-hidden border-none shadow-lg hover:shadow-xl transition-all"
              onClick={() => onNavigate('shop')}
            >
              <div className="relative aspect-square overflow-hidden">
                <ImageWithFallback
                  src={category.image}
                  alt={category.name}
                  className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                />
                <div className="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition-colors" />
                <div className="absolute inset-0 flex flex-col items-center justify-center text-white">
                  <h3 className="text-xl md:text-2xl mb-2">{category.name}</h3>
                  <p className="text-sm text-gray-200">{category.count} Items</p>
                </div>
              </div>
            </Card>
          ))}
        </div>
      </div>
    </section>
  );
}
