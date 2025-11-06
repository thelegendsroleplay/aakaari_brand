import React from 'react';

interface CategorySectionProps {
  onNavigate: (page: string) => void;
}

export const CategorySection: React.FC<CategorySectionProps> = ({ onNavigate }) => {
  return (
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
  );
};
