import React from 'react';
import { ArrowRight, Truck, Shield, RotateCcw } from 'lucide-react';
import { ProductCarousel } from '../../components/ProductCarousel';
import { Button } from '../../components/ui/button';
import { useProducts } from '../../contexts/ProductsContext';
import { Product } from '../../types';
import './home.css';

interface HomePageProps {
  onNavigate: (page: string, productId?: string) => void;
  onViewProduct: (product: Product) => void;
}

export const HomePage: React.FC<HomePageProps> = ({ onNavigate, onViewProduct }) => {
  const { products } = useProducts();
  const featuredProducts = products.filter(p => p.featured).slice(0, 8);
  const newArrivals = products.filter(p => p.newArrival).slice(0, 8);

  return (
    <div className="home-page">
      {/* Hero Banner */}
      <section className="hero-banner">
        <div className="hero-image-container">
          <img
            src="https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=1200&q=80"
            alt="New Collection"
            className="hero-banner-image"
          />
          <div className="hero-overlay">
            <div className="hero-content-wrapper">
              <div className="hero-text-content">
                <div className="hero-tag">NEW ARRIVAL</div>
                <h1 className="hero-main-title">
                  Premium Streetwear Collection
                </h1>
                <p className="hero-main-subtitle">
                  Discover our latest collection of premium t-shirts and hoodies
                </p>
                <div className="hero-cta-group">
                  <Button 
                    size="lg"
                    onClick={() => onNavigate('products')}
                    className="hero-cta-button"
                  >
                    Shop Now
                  </Button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Category Cards */}
      <section className="category-section">
        <div className="page-container">
          <h2 className="category-section-title">Shop by Category</h2>
          <div className="category-grid">
            <div className="category-card" onClick={() => onNavigate('products')}>
              <img
                src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&q=80"
                alt="T-Shirts"
                className="category-card-image"
              />
              <div className="category-card-overlay">
                <h3 className="category-card-title">T-Shirts</h3>
                <p className="category-card-subtitle">Explore Collection</p>
              </div>
            </div>

            <div className="category-card" onClick={() => onNavigate('hoodies')}>
              <img
                src="https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=800&q=80"
                alt="Hoodies"
                className="category-card-image"
              />
              <div className="category-card-overlay">
                <h3 className="category-card-title">Hoodies</h3>
                <p className="category-card-subtitle">Explore Collection</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Featured Products */}
      <section className="products-section">
        <div className="page-container">
          <div className="section-title-wrapper">
            <h2 className="section-main-title">Featured Products</h2>
            <button 
              className="section-view-link"
              onClick={() => onNavigate('products')}
            >
              View All
            </button>
          </div>

          <div className="product-carousel-wrapper">
            <ProductCarousel
              products={featuredProducts}
              onViewDetails={onViewProduct}
            />
          </div>
        </div>
      </section>

      {/* Promo Banner */}
      <section className="promo-section">
        <div className="page-container">
          <div className="promo-card">
            <div className="promo-content">
              <div className="promo-badge">Premium</div>
              <h2 className="promo-title">Crafted for Excellence</h2>
              <p className="promo-description">
                Every piece is thoughtfully designed and made with premium materials. 
                Experience comfort that lasts, style that stands out.
              </p>
              
              <div className="promo-features">
                <div className="promo-feature-item">
                  <div className="promo-feature-icon">✓</div>
                  <span>100% Premium Cotton</span>
                </div>
                <div className="promo-feature-item">
                  <div className="promo-feature-icon">✓</div>
                  <span>Sustainable Production</span>
                </div>
                <div className="promo-feature-item">
                  <div className="promo-feature-icon">✓</div>
                  <span>Lifetime Quality Guarantee</span>
                </div>
              </div>

              <Button 
                variant="secondary"
                onClick={() => onNavigate('products')}
                className="promo-button"
              >
                Explore Collection
              </Button>
            </div>
            <div className="promo-image-wrapper">
              <img
                src="https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=600&q=80"
                alt="Premium Collection"
                className="promo-image"
              />
              <div className="promo-image-overlay">
                <div className="promo-quality-badge">
                  <span className="quality-badge-label">Premium Quality</span>
                  <span className="quality-badge-subtitle">Since 2024</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* New Arrivals */}
      <section className="products-section arrivals-section">
        <div className="page-container">
          <div className="section-title-wrapper">
            <h2 className="section-main-title">New Arrivals</h2>
            <button 
              className="section-view-link"
              onClick={() => onNavigate('new-arrivals')}
            >
              View All
            </button>
          </div>

          <div className="product-carousel-wrapper">
            <ProductCarousel
              products={newArrivals}
              onViewDetails={onViewProduct}
            />
          </div>
        </div>
      </section>

      {/* Features/Trust Badges */}
      <section className="trust-section">
        <div className="page-container">
          <div className="trust-grid">
            <div className="trust-item">
              <div className="trust-icon-box">
                <Truck className="trust-icon" strokeWidth={1.5} />
              </div>
              <div className="trust-text">
                <h4 className="trust-title">Free Shipping</h4>
                <p className="trust-desc">On orders over $75</p>
              </div>
            </div>

            <div className="trust-item">
              <div className="trust-icon-box">
                <Shield className="trust-icon" strokeWidth={1.5} />
              </div>
              <div className="trust-text">
                <h4 className="trust-title">Secure Payment</h4>
                <p className="trust-desc">100% protected</p>
              </div>
            </div>

            <div className="trust-item">
              <div className="trust-icon-box">
                <RotateCcw className="trust-icon" strokeWidth={1.5} />
              </div>
              <div className="trust-text">
                <h4 className="trust-title">Easy Returns</h4>
                <p className="trust-desc">30-day policy</p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};