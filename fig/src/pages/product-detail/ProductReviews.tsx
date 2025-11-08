import React, { useState } from 'react';
import { Star, ThumbsUp, ThumbsDown, Filter } from 'lucide-react';
import { Review } from '../../types';

interface ProductReviewsProps {
  reviews: Review[];
  productRating: number;
}

export const ProductReviews: React.FC<ProductReviewsProps> = ({ reviews, productRating }) => {
  const [selectedRating, setSelectedRating] = useState<number | null>(null);
  const [sortBy, setSortBy] = useState<'newest' | 'helpful' | 'highest'>('newest');

  // Calculate rating distribution
  const ratingDistribution = {
    5: reviews.filter(r => r.rating === 5).length,
    4: reviews.filter(r => r.rating === 4).length,
    3: reviews.filter(r => r.rating === 3).length,
    2: reviews.filter(r => r.rating === 2).length,
    1: reviews.filter(r => r.rating === 1).length,
  };

  // Filter reviews based on selected rating
  let filteredReviews = selectedRating ? reviews.filter(r => r.rating === selectedRating) : reviews;

  // Sort reviews
  if (sortBy === 'helpful') {
    filteredReviews.sort((a, b) => (b.helpful || 0) - (a.helpful || 0));
  } else if (sortBy === 'highest') {
    filteredReviews.sort((a, b) => b.rating - a.rating);
  } else {
    // Default: newest first
    filteredReviews.reverse();
  }

  return (
    <div className="reviews-section">
      <h2>Customer Reviews & Ratings</h2>

      {/* Rating Summary and Distribution */}
      <div className="reviews-header">
        <div className="rating-summary">
          <div className="summary-card">
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
            <span className="review-count">Based on {reviews.length} reviews</span>
          </div>

          {/* Rating Distribution Bars */}
          <div className="rating-distribution">
            {[5, 4, 3, 2, 1].map((rating) => {
              const count = ratingDistribution[rating as keyof typeof ratingDistribution];
              const percentage = reviews.length > 0 ? (count / reviews.length) * 100 : 0;

              return (
                <button
                  key={rating}
                  className={`distribution-row ${selectedRating === rating ? 'active' : ''}`}
                  onClick={() => setSelectedRating(selectedRating === rating ? null : rating)}
                  title={`${count} reviews with ${rating} stars`}
                >
                  <span className="rating-label">{rating} ★</span>
                  <div className="rating-bar">
                    <div className="rating-bar-fill" style={{ width: `${percentage}%` }} />
                  </div>
                  <span className="rating-percentage">{Math.round(percentage)}%</span>
                </button>
              );
            })}
          </div>
        </div>
      </div>

      {/* Filter and Sort */}
      <div className="reviews-controls">
        <div className="filter-info">
          {selectedRating && (
            <span className="active-filter">
              Showing {filteredReviews.length} reviews with {selectedRating} star(s)
              <button className="clear-filter" onClick={() => setSelectedRating(null)}>
                Clear
              </button>
            </span>
          )}
        </div>
        <div className="sort-controls">
          <Filter size={18} />
          <select value={sortBy} onChange={(e) => setSortBy(e.target.value as any)}>
            <option value="newest">Newest First</option>
            <option value="helpful">Most Helpful</option>
            <option value="highest">Highest Rating</option>
          </select>
        </div>
      </div>

      {/* Reviews List */}
      <div className="reviews-list">
        {filteredReviews.length > 0 ? (
          filteredReviews.map((review) => (
            <div key={review.id} className="review-item">
              <div className="review-header">
                <div className="reviewer-info">
                  <span className="reviewer-name">{review.userName}</span>
                  <div className="review-meta">
                    {review.verified && <span className="verified-badge">✓ Verified Purchase</span>}
                    <span className="review-date">
                      {review.createdAt instanceof Date
                        ? review.createdAt.toLocaleDateString()
                        : new Date(review.createdAt).toLocaleDateString()}
                    </span>
                  </div>
                </div>
              </div>
              <div className="review-stars">
                {[...Array(5)].map((_, i) => (
                  <Star
                    key={i}
                    className={`star ${i < review.rating ? 'filled' : ''}`}
                    size={16}
                  />
                ))}
              </div>
              <p className="review-title">{review.title}</p>
              <p className="review-comment">{review.comment}</p>
              <div className="review-footer">
                <button className="helpful-btn">
                  <ThumbsUp size={16} />
                  Helpful ({review.helpful || 0})
                </button>
                <button className="unhelpful-btn">
                  <ThumbsDown size={16} />
                  Not helpful
                </button>
              </div>
            </div>
          ))
        ) : (
          <div className="no-reviews">No reviews match your filter.</div>
        )}
      </div>
    </div>
  );
};
