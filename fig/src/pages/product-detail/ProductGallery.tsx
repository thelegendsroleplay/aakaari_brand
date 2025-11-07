import React from 'react';

interface ProductGalleryProps {
  images: string[];
  productName: string;
  selectedImage: number;
  onSelectImage: (index: number) => void;
}

export const ProductGallery: React.FC<ProductGalleryProps> = ({
  images,
  productName,
  selectedImage,
  onSelectImage,
}) => {
  return (
    <div className="product-gallery">
      <div className="main-image">
        <img src={images[selectedImage]} alt={`${productName} - View ${selectedImage + 1}`} />
      </div>
      <div className="thumbnail-grid">
        {images.map((image, index) => (
          <button
            key={index}
            className={`thumbnail ${selectedImage === index ? 'active' : ''}`}
            onClick={() => onSelectImage(index)}
          >
            <img src={image} alt={`${productName} - Thumbnail ${index + 1}`} />
          </button>
        ))}
      </div>
    </div>
  );
};
