import React, { useState } from 'react';
import { X, ZoomIn } from 'lucide-react';

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
  const [isZoomed, setIsZoomed] = useState(false);
  const [lightboxOpen, setLightboxOpen] = useState(false);
  const [zoomPosition, setZoomPosition] = useState({ x: 0, y: 0 });

  const handleMouseMove = (e: React.MouseEvent<HTMLDivElement>) => {
    if (!isZoomed) return;

    const rect = e.currentTarget.getBoundingClientRect();
    const x = ((e.clientX - rect.left) / rect.width) * 100;
    const y = ((e.clientY - rect.top) / rect.height) * 100;
    setZoomPosition({ x, y });
  };

  const handlePrevImage = () => {
    const newIndex = selectedImage === 0 ? images.length - 1 : selectedImage - 1;
    onSelectImage(newIndex);
  };

  const handleNextImage = () => {
    const newIndex = selectedImage === images.length - 1 ? 0 : selectedImage + 1;
    onSelectImage(newIndex);
  };

  return (
    <div className="product-gallery">
      {/* Main Image with Zoom */}
      <div
        className={`main-image-container ${isZoomed ? 'zoomed' : ''}`}
        onMouseMove={handleMouseMove}
        onMouseEnter={() => setIsZoomed(true)}
        onMouseLeave={() => setIsZoomed(false)}
      >
        <img
          src={images[selectedImage]}
          alt={`${productName} - View ${selectedImage + 1}`}
          className="main-image"
          onClick={() => setLightboxOpen(true)}
          style={
            isZoomed
              ? {
                  transformOrigin: `${zoomPosition.x}% ${zoomPosition.y}%`,
                }
              : undefined
          }
        />
        <button className="zoom-button" title="Click to expand">
          <ZoomIn size={20} />
        </button>

        {/* Image Counter */}
        <div className="image-counter">
          {selectedImage + 1}/{images.length}
        </div>

        {/* Navigation Arrows */}
        <button className="nav-arrow prev-arrow" onClick={handlePrevImage}>
          ‹
        </button>
        <button className="nav-arrow next-arrow" onClick={handleNextImage}>
          ›
        </button>
      </div>

      {/* Thumbnail Grid */}
      <div className="thumbnail-grid">
        {images.map((image, index) => (
          <button
            key={index}
            className={`thumbnail ${selectedImage === index ? 'active' : ''}`}
            onClick={() => onSelectImage(index)}
            title={`View image ${index + 1}`}
          >
            <img src={image} alt={`${productName} - Thumbnail ${index + 1}`} />
            {images.length > 5 && index === 4 && images.length > 5 && (
              <div className="more-images">+{images.length - 5}</div>
            )}
          </button>
        ))}
      </div>

      {/* Lightbox Modal */}
      {lightboxOpen && (
        <div className="lightbox-modal">
          <div className="lightbox-content">
            <button className="lightbox-close" onClick={() => setLightboxOpen(false)}>
              <X size={24} />
            </button>
            <div className="lightbox-image-container">
              <button className="lightbox-nav prev" onClick={handlePrevImage}>
                ‹
              </button>
              <img
                src={images[selectedImage]}
                alt={`${productName} - Full view ${selectedImage + 1}`}
              />
              <button className="lightbox-nav next" onClick={handleNextImage}>
                ›
              </button>
            </div>
            <div className="lightbox-counter">
              {selectedImage + 1}/{images.length}
            </div>
          </div>
        </div>
      )}
    </div>
  );
};
