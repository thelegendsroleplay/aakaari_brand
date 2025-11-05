import { Button } from '../ui/button';
import { Card } from '../ui/card';
import { Page } from '../../lib/types';
import { Users, Target, Award, Heart } from 'lucide-react';

interface AboutPageProps {
  onNavigate: (page: Page) => void;
}

export function AboutPage({ onNavigate }: AboutPageProps) {
  return (
    <div className="container mx-auto px-4 py-12">
      {/* Hero Section */}
      <div className="max-w-4xl mx-auto text-center mb-16">
        <h1 className="text-5xl mb-4">About FashionMen</h1>
        <p className="text-xl text-gray-600">
          Redefining men's fashion with style, quality, and innovation since 2020
        </p>
      </div>

      {/* Story Section */}
      <div className="max-w-6xl mx-auto mb-16">
        <div className="grid md:grid-cols-2 gap-12 items-center">
          <div>
            <h2 className="text-3xl mb-4">Our Story</h2>
            <p className="text-gray-600 mb-4">
              Founded in 2020, FashionMen started with a simple mission: to bring
              premium, customizable men's fashion to everyone. We believe that every
              man deserves to express his unique style through quality clothing that
              fits perfectly.
            </p>
            <p className="text-gray-600 mb-4">
              What began as a small boutique has grown into a leading online
              destination for men's fashion, serving thousands of customers worldwide.
              Our commitment to excellence and customer satisfaction remains unwavering.
            </p>
          </div>
          <div className="aspect-square bg-gray-200 rounded-lg"></div>
        </div>
      </div>

      {/* Values Section */}
      <div className="max-w-6xl mx-auto mb-16">
        <h2 className="text-3xl text-center mb-12">Our Values</h2>
        <div className="grid md:grid-cols-4 gap-8">
          <Card className="p-6 text-center">
            <div className="bg-black text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <Target className="h-8 w-8" />
            </div>
            <h3 className="mb-2">Quality First</h3>
            <p className="text-sm text-gray-600">
              We source only the finest materials and work with skilled craftsmen
            </p>
          </Card>
          
          <Card className="p-6 text-center">
            <div className="bg-black text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <Users className="h-8 w-8" />
            </div>
            <h3 className="mb-2">Customer Focused</h3>
            <p className="text-sm text-gray-600">
              Your satisfaction is our top priority in everything we do
            </p>
          </Card>
          
          <Card className="p-6 text-center">
            <div className="bg-black text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <Award className="h-8 w-8" />
            </div>
            <h3 className="mb-2">Innovation</h3>
            <p className="text-sm text-gray-600">
              Pushing boundaries with customizable options and modern designs
            </p>
          </Card>
          
          <Card className="p-6 text-center">
            <div className="bg-black text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <Heart className="h-8 w-8" />
            </div>
            <h3 className="mb-2">Sustainability</h3>
            <p className="text-sm text-gray-600">
              Committed to ethical practices and environmental responsibility
            </p>
          </Card>
        </div>
      </div>

      {/* Stats Section */}
      <div className="max-w-6xl mx-auto mb-16">
        <Card className="p-12 bg-gray-50">
          <div className="grid md:grid-cols-4 gap-8 text-center">
            <div>
              <p className="text-5xl mb-2">50K+</p>
              <p className="text-gray-600">Happy Customers</p>
            </div>
            <div>
              <p className="text-5xl mb-2">500+</p>
              <p className="text-gray-600">Products</p>
            </div>
            <div>
              <p className="text-5xl mb-2">25+</p>
              <p className="text-gray-600">Countries</p>
            </div>
            <div>
              <p className="text-5xl mb-2">4.8</p>
              <p className="text-gray-600">Average Rating</p>
            </div>
          </div>
        </Card>
      </div>

      {/* CTA Section */}
      <div className="max-w-4xl mx-auto text-center">
        <h2 className="text-3xl mb-4">Join the FashionMen Family</h2>
        <p className="text-gray-600 mb-8">
          Experience the difference of premium, customizable men's fashion
        </p>
        <Button onClick={() => onNavigate('shop')} size="lg">
          Shop Now
        </Button>
      </div>
    </div>
  );
}
