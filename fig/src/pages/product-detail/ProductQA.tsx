import React, { useState } from 'react';
import { ChevronDown, ThumbsUp, MessageCircle } from 'lucide-react';

interface Question {
  id: string;
  question: string;
  answer: string;
  author: string;
  date: string;
  helpful: number;
}

interface ProductQAProps {
  productId: string;
}

export const ProductQA: React.FC<ProductQAProps> = ({ productId }) => {
  const [expandedId, setExpandedId] = useState<string | null>(null);
  const [showForm, setShowForm] = useState(false);

  // Mock Q&A data
  const questions: Question[] = [
    {
      id: '1',
      question: 'Is this product suitable for sensitive skin?',
      answer: 'Yes, our product is dermatologically tested and hypoallergenic. It has been specifically formulated to be gentle on sensitive skin types.',
      author: 'Sarah M.',
      date: '2 weeks ago',
      helpful: 24,
    },
    {
      id: '2',
      question: 'What is the shelf life after opening?',
      answer: 'Once opened, the product has a shelf life of 6 months if stored in a cool, dry place away from direct sunlight.',
      author: 'John D.',
      date: '1 month ago',
      helpful: 18,
    },
    {
      id: '3',
      question: 'Does it come with a guarantee?',
      answer: 'Yes, we offer a 2-year manufacturer warranty covering any defects in material or workmanship.',
      author: 'Emma L.',
      date: '1 month ago',
      helpful: 42,
    },
  ];

  const toggleExpand = (id: string) => {
    setExpandedId(expandedId === id ? null : id);
  };

  return (
    <div className="product-qa">
      <div className="qa-header">
        <h2>Frequently Asked Questions</h2>
        <button className="ask-question-btn" onClick={() => setShowForm(!showForm)}>
          <MessageCircle size={18} />
          Ask a Question
        </button>
      </div>

      {/* Ask Question Form */}
      {showForm && (
        <div className="ask-question-form">
          <div className="form-group">
            <label>Your Question</label>
            <textarea
              placeholder="Ask something about this product..."
              rows={4}
              className="form-input"
            />
          </div>
          <div className="form-group">
            <label>Your Name</label>
            <input type="text" placeholder="Your name" className="form-input" />
          </div>
          <div className="form-actions">
            <button className="btn-submit">Submit Question</button>
            <button className="btn-cancel" onClick={() => setShowForm(false)}>
              Cancel
            </button>
          </div>
        </div>
      )}

      {/* Questions List */}
      <div className="qa-list">
        {questions.map((q) => (
          <div key={q.id} className="qa-item">
            <button
              className={`qa-question ${expandedId === q.id ? 'expanded' : ''}`}
              onClick={() => toggleExpand(q.id)}
            >
              <div className="qa-question-text">
                <span className="qa-q">Q:</span>
                <span className="qa-text">{q.question}</span>
              </div>
              <ChevronDown
                size={20}
                className={`qa-icon ${expandedId === q.id ? 'rotated' : ''}`}
              />
            </button>

            {expandedId === q.id && (
              <div className="qa-answer">
                <div className="answer-content">
                  <span className="qa-a">A:</span>
                  <p>{q.answer}</p>
                </div>
                <div className="answer-meta">
                  <span className="meta-item">{q.author}</span>
                  <span className="meta-separator">•</span>
                  <span className="meta-item">{q.date}</span>
                  <button className="helpful-btn">
                    <ThumbsUp size={16} />
                    Helpful ({q.helpful})
                  </button>
                </div>
              </div>
            )}
          </div>
        ))}
      </div>
    </div>
  );
};
