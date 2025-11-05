import { Button } from '../ui/button';
import { Page } from '../../lib/types';
import { ImageWithFallback } from '../figma/ImageWithFallback';

interface HeroProps {
  onNavigate: (page: Page) => void;
}

export function Hero({ onNavigate }: HeroProps) {
  return (
    <section className="relative h-[500px] md:h-[600px] bg-gray-100">
      <ImageWithFallback
        src="https://images.unsplash.com/photo-1641736755184-67380b9a002c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtZW5zJTIwZmFzaGlvbiUyMG1vZGVsfGVufDF8fHx8MTc2MjIzODI1Nnww&ixlib=rb-4.1.0&q=80&w=1080"
        alt="Hero fashion"
        className="w-full h-full object-cover"
      />
      
      <div className="absolute inset-0 bg-black/40 flex items-center justify-center">
        <div className="text-center text-white px-4">
          <h1 className="text-4xl md:text-6xl mb-4 tracking-wide">
            ELEVATE YOUR STYLE
          </h1>
          <p className="text-lg md:text-xl mb-8 text-gray-200">
            Premium men's fashion crafted for the modern gentleman
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Button
              size="lg"
              className="bg-white text-black hover:bg-gray-100"
              onClick={() => onNavigate('shop')}
            >
              Shop Collection
            </Button>
            <Button
              size="lg"
              variant="outline"
              className="border-white text-white hover:bg-white hover:text-black"
            >
              Explore Customization
            </Button>
          </div>
        </div>
      </div>
    </section>
  );
}
