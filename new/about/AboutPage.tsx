import React from 'react';
import './about.css';

export const AboutPage: React.FC = () => {
  return (
    <div className="about-page">
      <div className="about-container">
        <section className="hero-about">
          <h1>About Men's Fashion</h1>
          <p>Premium clothing for the modern gentleman</p>
        </section>

        <section className="about-content">
          <div className="content-grid">
            <div className="content-block">
              <h2>Our Story</h2>
              <p>Founded in 2020, Men's Fashion has been providing premium quality clothing for discerning gentlemen worldwide. Our commitment to quality, style, and customer satisfaction sets us apart.</p>
            </div>
            <div className="content-block">
              <h2>Our Mission</h2>
              <p>To provide timeless, high-quality fashion that empowers men to look and feel their best, combining classic style with modern design.</p>
            </div>
            <div className="content-block">
              <h2>Quality Promise</h2>
              <p>Every piece in our collection is carefully selected and crafted using premium materials and ethical manufacturing practices.</p>
            </div>
          </div>
        </section>
      </div>
    </div>
  );
};
