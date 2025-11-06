import React, { useState, useRef } from 'react';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import { ProductCard } from './ProductCard';
import { Product } from '../types';
import './ProductCarousel.css';

interface ProductCarouselProps {
  products: Product[];
  onViewDetails: (product: Product) => void;
}

export const ProductCarousel: React.FC<ProductCarouselProps> = ({ products, onViewDetails }) => {
  const [currentIndex, setCurrentIndex] = useState(0);
  const carouselRef = useRef<HTMLDivElement>(null);

  const scroll = (direction: 'left' | 'right') => {
    if (!carouselRef.current) return;

    const container = carouselRef.current;
    const scrollAmount = container.offsetWidth;

    if (direction === 'left') {
      container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
      setCurrentIndex(Math.max(0, currentIndex - 1));
    } else {
      container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
      setCurrentIndex(Math.min(products.length - 1, currentIndex + 1));
    }
  };

  const canScrollLeft = currentIndex > 0;
  const canScrollRight = currentIndex < products.length - 4;

  return (
    <div className="product-carousel-container">
      {canScrollLeft && (
        <button
          className="carousel-arrow carousel-arrow-left"
          onClick={() => scroll('left')}
          aria-label="Previous products"
        >
          <ChevronLeft />
        </button>
      )}

      <div className="carousel-track" ref={carouselRef}>
        <div className="carousel-items">
          {products.map((product) => (
            <div key={product.id} className="carousel-item">
              <ProductCard product={product} onViewDetails={onViewDetails} />
            </div>
          ))}
        </div>
      </div>

      {canScrollRight && (
        <button
          className="carousel-arrow carousel-arrow-right"
          onClick={() => scroll('right')}
          aria-label="Next products"
        >
          <ChevronRight />
        </button>
      )}

      {/* Dots indicator for mobile */}
      <div className="carousel-dots">
        {Array.from({ length: Math.ceil(products.length / 2) }).map((_, idx) => (
          <button
            key={idx}
            className={`carousel-dot ${Math.floor(currentIndex / 2) === idx ? 'active' : ''}`}
            onClick={() => {
              setCurrentIndex(idx * 2);
              if (carouselRef.current) {
                carouselRef.current.scrollTo({
                  left: idx * carouselRef.current.offsetWidth,
                  behavior: 'smooth'
                });
              }
            }}
            aria-label={`Go to slide ${idx + 1}`}
          />
        ))}
      </div>
    </div>
  );
};