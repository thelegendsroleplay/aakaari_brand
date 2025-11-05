import { Card } from '../ui/card';
import { Page } from '../../lib/types';
import { ImageWithFallback } from '../figma/ImageWithFallback';

interface CategoriesProps {
  onNavigate: (page: Page) => void;
}

const categories = [
  {
    name: 'Jackets',
    image: 'https://images.unsplash.com/photo-1634136912882-61fd36144a3a?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwamFja2V0JTIwY2FzdWFsfGVufDF8fHx8MTc2MjI0NTE0NXww&ixlib=rb-4.1.0&q=80&w=1080',
    count: 45,
  },
  {
    name: 'Shirts',
    image: 'https://images.unsplash.com/photo-1661802365632-bb2b2f68eb51?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwc2hpcnQlMjBmYXNoaW9ufGVufDF8fHx8MTc2MjI0NTE0NXww&ixlib=rb-4.1.0&q=80&w=1080',
    count: 67,
  },
  {
    name: 'Shoes',
    image: 'https://images.unsplash.com/photo-1624006389438-c03488175975?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwc25lYWtlcnMlMjBzaG9lc3xlbnwxfHx8fDE3NjIxNzE4OTR8MA&ixlib=rb-4.1.0&q=80&w=1080',
    count: 34,
  },
  {
    name: 'Accessories',
    image: 'https://images.unsplash.com/photo-1706892807280-f8648dda29ef?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwYWNjZXNzb3JpZXMlMjB3YXRjaHxlbnwxfHx8fDE3NjIxNzUwNjZ8MA&ixlib=rb-4.1.0&q=80&w=1080',
    count: 28,
  },
];

export function Categories({ onNavigate }: CategoriesProps) {
  return (
    <section className="py-12 md:py-16">
      <div className="container mx-auto px-4">
        <h2 className="text-3xl md:text-4xl text-center mb-8 md:mb-12">Shop by Category</h2>
        
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
          {categories.map((category) => (
            <Card
              key={category.name}
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
