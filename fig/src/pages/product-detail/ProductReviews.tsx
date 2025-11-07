import React from 'react';
import { Star } from 'lucide-react';
import { Review } from '../../types';

interface ProductReviewsProps {
  reviews: Review[];
  productRating: number;
}

export const ProductReviews: React.FC<ProductReviewsProps> = ({ reviews, productRating }) => {
  return (
    <div className="reviews-section">
      <h2>Customer Reviews</h2>
      <div className="reviews-header">
        <div className="rating-summary">
          <span className="rating-number">{productRating.toFixed(1)}</span>
          <div className="stars-large">
            {[...Array(5)].map((_, i) => (
              <Star
                key={i}
                className={`star ${i < Math.floor(productRating) ? 'filled' : ''}`}
                size={20}
              />
            ))}
          </div>
          <span className="review-count">{reviews.length} reviews</span>
        </div>
      </div>

      <div className="reviews-list">
        {reviews.map(review => (
          <div key={review.id} className="review-item">
            <div className="review-header">
              <div className="reviewer-info">
                <span className="reviewer-name">{review.userName}</span>
                <div className="review-stars">
                  {[...Array(5)].map((_, i) => (
                    <Star
                      key={i}
                      className={`star ${i < review.rating ? 'filled' : ''}`}
                      size={14}
                    />
                  ))}
                </div>
              </div>
              <span className="review-date">{review.date}</span>
            </div>
            <p className="review-comment">{review.comment}</p>
          </div>
        ))}
      </div>
    </div>
  );
};
