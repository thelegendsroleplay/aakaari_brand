import { useState } from 'react';
import { Button } from '../ui/button';
import { Card } from '../ui/card';
import { Textarea } from '../ui/textarea';
import { Input } from '../ui/input';
import { Label } from '../ui/label';
import { Progress } from '../ui/progress';
import { Badge } from '../ui/badge';
import { Review } from '../../lib/types';
import { Star, ThumbsUp, CheckCircle } from 'lucide-react';
import { toast } from 'sonner@2.0.3';

interface ProductReviewsProps {
  productId: string;
  reviews: Review[];
  averageRating: number;
  totalReviews: number;
  onAddReview: (review: Omit<Review, 'id' | 'createdAt' | 'helpful'>) => void;
}

export function ProductReviews({
  productId,
  reviews,
  averageRating,
  totalReviews,
  onAddReview,
}: ProductReviewsProps) {
  const [showReviewForm, setShowReviewForm] = useState(false);
  const [rating, setRating] = useState(5);
  const [hoverRating, setHoverRating] = useState(0);
  const [title, setTitle] = useState('');
  const [comment, setComment] = useState('');

  // Calculate rating distribution
  const ratingDistribution = [5, 4, 3, 2, 1].map((star) => {
    const count = reviews.filter((r) => r.rating === star).length;
    const percentage = totalReviews > 0 ? (count / totalReviews) * 100 : 0;
    return { star, count, percentage };
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    onAddReview({
      productId,
      userId: 'user-1', // Replace with actual user ID
      userName: 'Current User', // Replace with actual user name
      rating,
      title,
      comment,
      verified: true,
    });
    toast.success('Review submitted successfully!');
    setShowReviewForm(false);
    setRating(5);
    setTitle('');
    setComment('');
  };

  return (
    <div className="space-y-6">
      {/* Reviews Summary */}
      <Card className="p-6">
        <div className="grid md:grid-cols-2 gap-8">
          {/* Overall Rating */}
          <div className="text-center md:text-left">
            <p className="text-gray-600 mb-2">Overall Rating</p>
            <div className="flex items-center justify-center md:justify-start gap-3 mb-2">
              <span className="text-5xl">{averageRating.toFixed(1)}</span>
              <div>
                <div className="flex gap-1">
                  {[1, 2, 3, 4, 5].map((star) => (
                    <Star
                      key={star}
                      className={`h-5 w-5 ${
                        star <= averageRating
                          ? 'fill-yellow-500 text-yellow-500'
                          : 'text-gray-300'
                      }`}
                    />
                  ))}
                </div>
                <p className="text-sm text-gray-600 mt-1">
                  Based on {totalReviews} reviews
                </p>
              </div>
            </div>
          </div>

          {/* Rating Distribution */}
          <div className="space-y-2">
            {ratingDistribution.map(({ star, count, percentage }) => (
              <div key={star} className="flex items-center gap-3">
                <span className="text-sm w-12">{star} stars</span>
                <Progress value={percentage} className="flex-1" />
                <span className="text-sm text-gray-600 w-8">{count}</span>
              </div>
            ))}
          </div>
        </div>

        {/* Write Review Button */}
        <div className="mt-6 pt-6 border-t">
          {!showReviewForm ? (
            <Button onClick={() => setShowReviewForm(true)}>
              Write a Review
            </Button>
          ) : (
            <form onSubmit={handleSubmit} className="space-y-4">
              {/* Star Rating */}
              <div>
                <Label>Your Rating</Label>
                <div className="flex gap-2 mt-2">
                  {[1, 2, 3, 4, 5].map((star) => (
                    <button
                      key={star}
                      type="button"
                      onClick={() => setRating(star)}
                      onMouseEnter={() => setHoverRating(star)}
                      onMouseLeave={() => setHoverRating(0)}
                      className="focus:outline-none"
                    >
                      <Star
                        className={`h-8 w-8 ${
                          star <= (hoverRating || rating)
                            ? 'fill-yellow-500 text-yellow-500'
                            : 'text-gray-300'
                        }`}
                      />
                    </button>
                  ))}
                </div>
              </div>

              {/* Review Title */}
              <div>
                <Label htmlFor="title">Review Title</Label>
                <Input
                  id="title"
                  value={title}
                  onChange={(e) => setTitle(e.target.value)}
                  placeholder="Sum up your experience"
                  required
                />
              </div>

              {/* Review Comment */}
              <div>
                <Label htmlFor="comment">Your Review</Label>
                <Textarea
                  id="comment"
                  value={comment}
                  onChange={(e) => setComment(e.target.value)}
                  placeholder="Share your thoughts about this product"
                  rows={4}
                  required
                />
              </div>

              {/* Submit Buttons */}
              <div className="flex gap-2">
                <Button type="submit">Submit Review</Button>
                <Button
                  type="button"
                  variant="outline"
                  onClick={() => setShowReviewForm(false)}
                >
                  Cancel
                </Button>
              </div>
            </form>
          )}
        </div>
      </Card>

      {/* Reviews List */}
      <div className="space-y-4">
        <h3 className="text-xl">Customer Reviews</h3>
        
        {reviews.length === 0 ? (
          <Card className="p-8 text-center text-gray-600">
            No reviews yet. Be the first to review this product!
          </Card>
        ) : (
          reviews.map((review) => (
            <Card key={review.id} className="p-6">
              {/* Review Header */}
              <div className="flex items-start justify-between mb-3">
                <div>
                  <div className="flex items-center gap-2 mb-1">
                    <span>{review.userName}</span>
                    {review.verified && (
                      <Badge variant="outline" className="text-green-600 border-green-600">
                        <CheckCircle className="h-3 w-3 mr-1" />
                        Verified Purchase
                      </Badge>
                    )}
                  </div>
                  <div className="flex gap-1 mb-2">
                    {[1, 2, 3, 4, 5].map((star) => (
                      <Star
                        key={star}
                        className={`h-4 w-4 ${
                          star <= review.rating
                            ? 'fill-yellow-500 text-yellow-500'
                            : 'text-gray-300'
                        }`}
                      />
                    ))}
                  </div>
                </div>
                <span className="text-sm text-gray-600">
                  {new Date(review.createdAt).toLocaleDateString()}
                </span>
              </div>

              {/* Review Content */}
              <h4 className="mb-2">{review.title}</h4>
              <p className="text-gray-600 mb-4">{review.comment}</p>

              {/* Review Actions */}
              <div className="flex items-center gap-4">
                <button className="flex items-center gap-1 text-sm text-gray-600 hover:text-black">
                  <ThumbsUp className="h-4 w-4" />
                  Helpful ({review.helpful})
                </button>
              </div>
            </Card>
          ))
        )}
      </div>
    </div>
  );
}
