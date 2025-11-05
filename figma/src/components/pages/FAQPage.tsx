import { Card } from '../ui/card';
import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from '../ui/accordion';
import { Page } from '../../lib/types';
import { Button } from '../ui/button';

interface FAQPageProps {
  onNavigate: (page: Page) => void;
}

export function FAQPage({ onNavigate }: FAQPageProps) {
  const faqs = [
    {
      category: 'Orders & Shipping',
      questions: [
        {
          q: 'How long does shipping take?',
          a: 'Standard shipping typically takes 3-5 business days. Express shipping is available and takes 1-2 business days. International orders may take 7-14 business days.',
        },
        {
          q: 'Do you offer free shipping?',
          a: 'Yes! We offer free standard shipping on all orders over $100. Express shipping is available for an additional fee.',
        },
        {
          q: 'Can I track my order?',
          a: 'Absolutely! Once your order ships, you\'ll receive a tracking number via email. You can also track your order anytime in your account dashboard.',
        },
        {
          q: 'Do you ship internationally?',
          a: 'Yes, we ship to over 25 countries worldwide. International shipping rates and delivery times vary by location.',
        },
      ],
    },
    {
      category: 'Returns & Exchanges',
      questions: [
        {
          q: 'What is your return policy?',
          a: 'We offer a 30-day return policy for unworn, unwashed items with original tags attached. Items must be in original condition for a full refund.',
        },
        {
          q: 'How do I initiate a return?',
          a: 'Log into your account, go to your orders, and select the item you wish to return. Follow the prompts to print a return label. Returns are free for US customers.',
        },
        {
          q: 'Can I exchange an item?',
          a: 'Yes! We offer free exchanges for different sizes or colors. Simply select "Exchange" instead of "Return" when processing your request.',
        },
        {
          q: 'Are customized items returnable?',
          a: 'Due to the personalized nature of customized items, they cannot be returned unless there is a manufacturing defect or error in customization.',
        },
      ],
    },
    {
      category: 'Product Customization',
      questions: [
        {
          q: 'What items can be customized?',
          a: 'Products marked with a "Customizable" badge can be personalized. This includes monogramming, custom text, color selections, and special design options.',
        },
        {
          q: 'How much does customization cost?',
          a: 'Customization pricing varies by product and complexity. Most text-based customizations range from $5-$15. Detailed pricing is shown during product selection.',
        },
        {
          q: 'How long does customization take?',
          a: 'Customized orders typically require an additional 3-5 business days for production before shipping. Rush customization is available for select items.',
        },
        {
          q: 'Can I preview my customization?',
          a: 'Yes! Our customizer tool provides a real-time preview of your personalization before you add the item to your cart.',
        },
      ],
    },
    {
      category: 'Account & Payment',
      questions: [
        {
          q: 'Do I need an account to place an order?',
          a: 'While you can checkout as a guest, creating an account allows you to track orders, save addresses, maintain a wishlist, and access exclusive member benefits.',
        },
        {
          q: 'What payment methods do you accept?',
          a: 'We accept all major credit cards (Visa, Mastercard, American Express, Discover), PayPal, Apple Pay, Google Pay, and Shop Pay.',
        },
        {
          q: 'Is my payment information secure?',
          a: 'Absolutely! We use industry-standard SSL encryption to protect your payment information. We never store your full credit card details on our servers.',
        },
        {
          q: 'Can I use multiple payment methods?',
          a: 'Currently, we support one payment method per order. However, you can use store credit or gift cards in combination with other payment methods.',
        },
      ],
    },
    {
      category: 'Sizing & Fit',
      questions: [
        {
          q: 'How do I find my size?',
          a: 'Each product page includes a detailed size guide. Click "Size Guide" to view measurements for that specific item. We recommend measuring yourself and comparing to our charts.',
        },
        {
          q: 'What if I\'m between sizes?',
          a: 'If you\'re between sizes, we generally recommend sizing up for a more comfortable fit. Check the product description for specific fit notes (slim, regular, relaxed).',
        },
        {
          q: 'Do your sizes run true to size?',
          a: 'Yes, our sizing is consistent with standard US sizing. Each product includes detailed measurements and fit information to help you choose the right size.',
        },
      ],
    },
  ];

  return (
    <div className="container mx-auto px-4 py-12">
      <div className="max-w-4xl mx-auto">
        {/* Header */}
        <div className="text-center mb-12">
          <h1 className="text-5xl mb-4">Frequently Asked Questions</h1>
          <p className="text-xl text-gray-600">
            Find answers to common questions about our products and services
          </p>
        </div>

        {/* FAQ Sections */}
        <div className="space-y-8">
          {faqs.map((category, idx) => (
            <Card key={idx} className="p-6">
              <h2 className="text-2xl mb-4">{category.category}</h2>
              <Accordion type="single" collapsible className="w-full">
                {category.questions.map((faq, qIdx) => (
                  <AccordionItem key={qIdx} value={`item-${idx}-${qIdx}`}>
                    <AccordionTrigger>{faq.q}</AccordionTrigger>
                    <AccordionContent className="text-gray-600">
                      {faq.a}
                    </AccordionContent>
                  </AccordionItem>
                ))}
              </Accordion>
            </Card>
          ))}
        </div>

        {/* Contact CTA */}
        <Card className="p-8 text-center mt-12 bg-gray-50">
          <h2 className="text-2xl mb-2">Still have questions?</h2>
          <p className="text-gray-600 mb-6">
            Our customer support team is here to help
          </p>
          <Button onClick={() => onNavigate('contact')}>
            Contact Support
          </Button>
        </Card>
      </div>
    </div>
  );
}
