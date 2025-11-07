import React from 'react';

interface ProductsHeaderProps {
  pageType: string;
}

export const ProductsHeader: React.FC<ProductsHeaderProps> = ({ pageType }) => {
  const getPageTitle = () => {
    switch (pageType) {
      case 'hoodies':
        return 'Hoodies';
      case 'new-arrivals':
        return 'New Arrivals';
      case 'sale':
        return 'Sale';
      case 'bestsellers':
        return 'Bestsellers';
      default:
        return 'T-Shirts';
    }
  };

  const getPageDescription = () => {
    switch (pageType) {
      case 'sale':
        return 'Exclusive deals on premium streetwear';
      case 'new-arrivals':
        return 'The latest drops in streetwear fashion';
      case 'hoodies':
        return 'Cozy hoodies for every season';
      default:
        return 'Essential tees for your wardrobe';
    }
  };

  return (
    <div className="page-header">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <h1 className="text-3xl">{getPageTitle()}</h1>
        <p className="text-gray-600 mt-2">{getPageDescription()}</p>
      </div>
    </div>
  );
};
