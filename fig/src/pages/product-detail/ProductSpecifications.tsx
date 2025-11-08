import React, { useState } from 'react';
import { ChevronDown } from 'lucide-react';
import { Product } from '../../types';

interface ProductSpecificationsProps {
  product: Product;
}

export const ProductSpecifications: React.FC<ProductSpecificationsProps> = ({ product }) => {
  const [expandedSection, setExpandedSection] = useState<string | null>('overview');

  // Sample specifications - in real app, this would come from product data
  const specifications = {
    overview: {
      title: 'Product Overview',
      items: [
        { label: 'Product Type', value: product.productType || 'Standard' },
        { label: 'Brand', value: product.brand || 'Aakaari' },
        { label: 'SKU', value: product.id },
        { label: 'Category', value: product.category },
      ],
    },
    details: {
      title: 'Detailed Information',
      items: [
        { label: 'Material', value: 'Premium Quality' },
        { label: 'Weight', value: '0.5 kg' },
        { label: 'Dimensions', value: '30 x 20 x 10 cm' },
        { label: 'Color', value: product.colors?.join(', ') || 'Multiple' },
      ],
    },
    features: {
      title: 'Key Features',
      items: [
        { label: 'Feature 1', value: 'Long-lasting quality' },
        { label: 'Feature 2', value: 'Easy to maintain' },
        { label: 'Feature 3', value: 'Eco-friendly materials' },
        { label: 'Feature 4', value: 'Manufacturer warranty' },
      ],
    },
  };

  const sections = [specifications.overview, specifications.details, specifications.features];

  const toggleSection = (sectionTitle: string) => {
    setExpandedSection(expandedSection === sectionTitle ? null : sectionTitle);
  };

  return (
    <div className="product-specifications">
      <h2>Product Specifications</h2>
      <div className="specs-container">
        {sections.map((section) => (
          <div key={section.title} className="spec-section">
            <button
              className={`spec-header ${expandedSection === section.title ? 'expanded' : ''}`}
              onClick={() => toggleSection(section.title)}
            >
              <span className="spec-title">{section.title}</span>
              <ChevronDown
                size={20}
                className={`spec-icon ${expandedSection === section.title ? 'rotated' : ''}`}
              />
            </button>
            {expandedSection === section.title && (
              <div className="spec-content">
                <div className="spec-table">
                  {section.items.map((item, index) => (
                    <div key={index} className="spec-row">
                      <span className="spec-label">{item.label}</span>
                      <span className="spec-value">{item.value}</span>
                    </div>
                  ))}
                </div>
              </div>
            )}
          </div>
        ))}
      </div>
    </div>
  );
};
